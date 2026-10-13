/**
 * Numbers, text effects, charts, the swipe stack and the small stateful bits
 * (alerts, theme, marquee, route progress, pricing).
 */
import {
  areaPath,
  bands,
  barPath,
  leave,
  linePath,
  linearScale,
  nearestIndex,
  niceTicks,
  reveal,
  scramble,
  swipe,
  theme,
} from '@nabuxai/ui-core';
import { renderNumber } from './number';
import type { AlpineLike, Magics } from './types';

type Self<T> = T & Magics;

const esc = (value: unknown) => String(value).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
const seriesColor = (i: number) => `var(--nx-chart-${(i % 7) + 1})`;

interface ChartConfig {
  type: 'bar' | 'area' | 'line';
  labels: string[];
  series: Array<{ name: string; values: number[] }>;
  locale?: string;
  format?: Intl.NumberFormatOptions;
  height?: number;
  smooth?: boolean;
}

const PAD = { top: 16, right: 12, bottom: 28, left: 44 };

export function installDisplay(Alpine: AlpineLike): void {
  /* ---- Rolling number bound to a value (entangle or plain) ----------------------- */
  Alpine.data('nxNumber', (value: number = 0, locale?: string, format?: Intl.NumberFormatOptions) => ({
    value,
    init(this: Self<{ value: number }>) {
      this.$watch('value', (next: number) => renderNumber(this.$root, Number(next), locale, format));
    },
  }));

  /* ---- Rotating words -------------------------------------------------------------- */
  Alpine.data('nxWordRotate', (words: string[] = [], interval = 2600) => ({
    index: 0,
    previous: null as number | null,
    paused: false,

    init(this: Self<{ index: number; previous: number | null; paused: boolean; measure: () => void }>) {
      this.measure();
      if (words.length < 2) return;
      setInterval(() => {
        if (this.paused || document.hidden) return;
        this.previous = this.index;
        this.index = (this.index + 1) % words.length;
        this.$nextTick(() => this.measure());
      }, interval);
    },

    measure(this: Self<object>) {
      const current = this.$root.querySelector<HTMLElement>('[data-state="enter"], .nx-word-rotate-word:not([data-state])');
      if (current) this.$root.style.setProperty('--nx-word-width', `${current.scrollWidth}px`);
    },

    word(this: object, i: number | null) {
      return i === null ? '' : words[i];
    },
  }));

  /* ---- Scramble ------------------------------------------------------------------------ */
  Alpine.data('nxScramble', (text: string, trigger: 'mount' | 'view' | 'hover' = 'view', charset?: string, duration?: number) => ({
    stop: () => {},

    init(this: Self<{ run: () => void }>) {
      if (trigger === 'mount') this.run();
      if (trigger === 'hover') this.$root.addEventListener('pointerenter', () => this.run());
      if (trigger === 'view') {
        const observer = new IntersectionObserver(([entry]) => {
          if (entry?.isIntersecting) {
            this.run();
            observer.disconnect();
          }
        }, { threshold: 0.4 });
        observer.observe(this.$root);
      }
    },

    run(this: Self<{ stop: () => void }>) {
      this.stop();
      this.stop = scramble(this.$refs.text, text, { charset, duration });
    },
  }));

  /* ---- Charts ------------------------------------------------------------------------------ */
  Alpine.data('nxChart', (config: ChartConfig) => ({
    active: null as number | null,
    width: 560,
    xs: [] as number[],
    tops: [] as number[],

    init(this: Self<{ width: number; draw: () => void; show: () => void; active: number | null }>) {
      const plot = this.$refs.plot;
      this.width = plot.getBoundingClientRect().width || 560;
      this.draw();
      reveal(this.$root, { once: true });
      if ('ResizeObserver' in window) {
        new ResizeObserver(([entry]) => {
          const width = Math.round(entry!.contentRect.width);
          if (width && Math.abs(width - this.width) > 1) {
            this.width = width;
            this.draw();
          }
        }).observe(plot);
      }
      this.$watch('active', () => this.show());
    },

    fmt(value: number) {
      return new Intl.NumberFormat(config.locale, config.format).format(value);
    },

    tick(value: number) {
      return new Intl.NumberFormat(config.locale, { notation: 'compact', maximumFractionDigits: 1 }).format(value);
    },

    draw(this: Self<{ width: number; xs: number[]; tops: number[]; fmt: (v: number) => string; tick: (v: number) => string }>) {
      const { labels, series, type } = config;
      const height = config.height ?? (type === 'bar' ? 240 : 260);
      const width = this.width;
      const all = series.flatMap((s) => s.values);
      const ticks = type === 'bar' ? niceTicks(Math.min(0, ...all), Math.max(0, ...all), 4) : niceTicks(Math.min(0, ...all), Math.max(...all), 4);
      const innerH = height - PAD.top - PAD.bottom;
      const y = linearScale([ticks[0]!, ticks[ticks.length - 1]!], [PAD.top + innerH, PAD.top]);
      let body = '';

      for (const t of ticks) {
        const cls = type === 'bar' ? (t === 0 ? 'nx-chart-baseline' : 'nx-chart-gridline') : t === ticks[0] ? 'nx-chart-baseline' : 'nx-chart-gridline';
        body += `<line class="${cls}" x1="${PAD.left}" x2="${width - PAD.right}" y1="${y(t)}" y2="${y(t)}"/><text class="nx-chart-tick" x="${PAD.left - 8}" y="${y(t)}" dy="0.32em" text-anchor="end">${esc(this.tick(t))}</text>`;
      }

      if (type === 'bar') {
        const innerW = Math.max(0, width - PAD.left - PAD.right);
        const groups = bands(labels.length, innerW, { maxBar: 24 * series.length + 2 * (series.length - 1), fill: 0.7 });
        this.xs = groups.map((g) => PAD.left + g.center);
        this.tops = labels.map((_, i) => y(Math.max(...series.map((s) => s.values[i] ?? 0))));
        groups.forEach((group, index) => {
          const w = (group.width - 2 * (series.length - 1)) / series.length;
          series.forEach((s, si) => {
            const x = PAD.left + group.x + si * (w + 2);
            body += `<path class="nx-chart-bar" data-index="${index}" d="${barPath(x, w, y(s.values[index] ?? 0), y(0), 4)}" style="--nx-series:${seriesColor(si)};--nx-i:${index}"/>`;
          });
          body += `<text class="nx-chart-tick" x="${PAD.left + group.center}" y="${height - 8}" text-anchor="middle">${esc(labels[index])}</text>`;
        });
      } else {
        const x = linearScale([0, Math.max(1, labels.length - 1)], [PAD.left, width - PAD.right]);
        this.xs = labels.map((_, i) => x(i));
        const lines = series.map((s) => s.values.map((v, i) => [x(i), y(v)] as const));
        this.tops = labels.map((_, i) => Math.min(...lines.map((l) => l[i]?.[1] ?? height)));
        const id = Math.random().toString(36).slice(2, 8);
        let defs = '';
        const every = Math.max(1, Math.ceil(labels.length / Math.max(2, Math.floor(width / 90))));
        labels.forEach((label, i) => {
          if (i % every !== 0 && i !== labels.length - 1) return;
          const anchor = i === 0 ? 'start' : i === labels.length - 1 ? 'end' : 'middle';
          body += `<text class="nx-chart-tick" x="${this.xs[i]}" y="${height - 8}" text-anchor="${anchor}">${esc(label)}</text>`;
        });
        lines.forEach((points, i) => {
          if (type === 'area') {
            defs += `<linearGradient id="nx-${id}-${i}" x1="0" x2="0" y1="0" y2="1"><stop offset="0%" stop-color="${seriesColor(i)}" stop-opacity="0.22"/><stop offset="100%" stop-color="${seriesColor(i)}" stop-opacity="0.01"/></linearGradient>`;
            body += `<path class="nx-chart-area" d="${areaPath(points, y(ticks[0]!), config.smooth !== false)}" fill="url(#nx-${id}-${i})"/>`;
          }
        });
        lines.forEach((points, i) => {
          body += `<path class="nx-chart-line" d="${linePath(points, config.smooth !== false)}" pathLength="1" style="--nx-series:${seriesColor(i)};--nx-i:${i}"/>`;
        });
        body += `<line class="nx-chart-crosshair" x1="0" x2="0" y1="${PAD.top}" y2="${PAD.top + innerH}"/>`;
        lines.forEach((points, i) =>
          points.forEach(([px, py], pi) => {
            body += `<circle class="nx-chart-point" data-index="${pi}" cx="${px}" cy="${py}" r="4" ${pi === points.length - 1 ? 'data-end=""' : ''} style="--nx-series:${seriesColor(i)}"/>`;
          }),
        );
        body = `<defs>${defs}</defs>${body}`;
      }

      this.$refs.svg.setAttribute('viewBox', `0 0 ${width} ${height}`);
      this.$refs.svg.setAttribute('width', String(width));
      this.$refs.svg.setAttribute('height', String(height));
      this.$refs.svg.innerHTML = body;
    },

    move(this: { active: number | null; xs: number[] }, event: PointerEvent) {
      const rect = (event.currentTarget as HTMLElement).getBoundingClientRect();
      const x = event.clientX - rect.left;
      this.active = config.type === 'bar' ? nearestIndex(this.xs, x) : nearestIndex(this.xs, x);
    },

    key(this: { active: number | null }, event: KeyboardEvent) {
      const count = config.labels.length;
      if (event.key === 'ArrowRight' || event.key === 'ArrowLeft') {
        event.preventDefault();
        const step = event.key === 'ArrowRight' ? 1 : -1;
        this.active = this.active === null ? 0 : Math.min(Math.max(this.active + step, 0), count - 1);
      } else if (event.key === 'Home') this.active = 0;
      else if (event.key === 'End') this.active = count - 1;
      else if (event.key === 'Escape') this.active = null;
    },

    show(this: Self<{ active: number | null; xs: number[]; tops: number[]; width: number; fmt: (v: number) => string }>) {
      const svg = this.$refs.svg;
      const tip = this.$refs.tip;
      this.$root.toggleAttribute('data-active', this.active !== null);
      for (const el of Array.from(svg.querySelectorAll('[data-index]'))) el.toggleAttribute('data-active', Number((el as HTMLElement).dataset.index) === this.active);
      const cross = svg.querySelector('.nx-chart-crosshair');
      if (cross && this.active !== null) {
        cross.setAttribute('x1', String(this.xs[this.active]));
        cross.setAttribute('x2', String(this.xs[this.active]));
      }
      cross?.toggleAttribute('data-active', this.active !== null);
      if (this.active === null) {
        tip.removeAttribute('data-open');
        return;
      }
      const i = this.active;
      const x = Math.min(Math.max(this.xs[i]!, 70), Math.max(70, this.width - 70));
      tip.style.setProperty('--nx-tx', `calc(${x}px - 50%)`);
      tip.style.setProperty('--nx-ty', `calc(${this.tops[i]}px - 100% - 12px)`);
      // Labels are data: written as text, never parsed as HTML.
      const title = document.createElement('p');
      title.className = 'nx-chart-tooltip-title';
      title.textContent = config.labels[i] ?? '';
      const rows = config.series.map((s, si) => {
        const row = document.createElement('div');
        row.className = 'nx-chart-tooltip-row';
        const key = document.createElement('span');
        key.className = 'nx-chart-tooltip-key';
        key.style.setProperty('--nx-series', seriesColor(si));
        const value = document.createElement('strong');
        value.textContent = this.fmt(s.values[i] ?? 0);
        const name = document.createElement('span');
        name.textContent = s.name;
        row.append(key, value, name);
        return row;
      });
      tip.replaceChildren(title, ...rows);
      tip.setAttribute('data-open', '');
    },
  }));

  /* ---- Swipe stack --------------------------------------------------------------------------- */
  Alpine.data('nxSwipeStack', (count: number, loop = true) => ({
    order: Array.from({ length: count }, (_, i) => i),
    leaving: null as number | null,
    stop: () => {},

    init(this: Self<{ bind: () => void; order: number[] }>) {
      this.$watch('order', () => this.$nextTick(() => this.bind()));
      this.bind();
    },

    depth(this: { order: number[] }, index: number) {
      return this.order.indexOf(index);
    },

    bind(this: Self<{ stop: () => void; order: number[]; decide: (v: 'accept' | 'reject') => void }>) {
      this.stop();
      const top = this.$root.querySelector<HTMLElement>(`[data-card="${this.order[0]}"]`);
      if (top) this.stop = swipe(top, { axis: 'x', threshold: 110, rotate: 9, onSwipe: (dir) => this.decide(dir === 'right' ? 'accept' : 'reject') });
    },

    decide(this: Self<{ order: number[]; leaving: number | null }>, verdict: 'accept' | 'reject') {
      const top = this.order[0];
      if (top === undefined || this.leaving !== null) return;
      const el = this.$root.querySelector<HTMLElement>(`[data-card="${top}"]`);
      if (!el) return;
      el.style.setProperty('--nx-exit-x', verdict === 'accept' ? '1' : '-1');
      this.leaving = top;
      this.$dispatch('nx-swipe', { index: top, verdict });
      leave(el, { value: 'leaving', timeout: 700 }).then(() => {
        for (const name of ['--nx-dx', '--nx-dy', '--nx-drag-rotate', '--nx-drag-progress']) el.style.removeProperty(name);
        el.removeAttribute('data-state');
        this.leaving = null;
        this.order = loop ? [...this.order.slice(1), top] : this.order.slice(1);
      });
    },
  }));

  /* ---- Alert (dismiss collapses it away) ------------------------------------------------------ */
  Alpine.data('nxAlert', () => ({
    gone: false,
    dismiss(this: Self<{ gone: boolean }>) {
      leave(this.$root).then(() => {
        this.gone = true;
        this.$dispatch('nx-dismiss');
      });
    },
  }));

  /* ---- Theme toggle ------------------------------------------------------------------------------ */
  Alpine.data('nxTheme', () => ({
    dark: false,
    init(this: { dark: boolean }) {
      this.dark = theme.resolved() === 'dark';
      theme.watch((scheme) => (this.dark = scheme === 'dark'));
    },
    toggle(this: { dark: boolean }) {
      this.dark = theme.toggle() === 'dark';
    },
  }));

  /* ---- Route progress: Livewire navigate, or any loading flag ----------------------------------- */
  Alpine.data('nxRouteProgress', () => ({
    state: 'idle' as 'idle' | 'loading' | 'done',
    init(this: { state: 'idle' | 'loading' | 'done'; start: () => void; done: () => void }) {
      document.addEventListener('livewire:navigate', () => this.start());
      document.addEventListener('livewire:navigated', () => this.done());
    },
    start(this: { state: string }) {
      this.state = 'loading';
    },
    done(this: { state: string }) {
      if (this.state !== 'loading') return;
      this.state = 'done';
      setTimeout(() => this.state === 'done' && (this.state = 'idle'), 700);
    },
  }));

  /* ---- Pricing: billing switch drives every plan's rolling price ----------------------------------- */
  Alpine.data('nxPricing', (billing: string = 'monthly', locale?: string) => ({
    billing,
    init(this: Self<{ billing: string }>) {
      this.$watch('billing', () => {
        for (const price of Array.from(this.$root.querySelectorAll<HTMLElement>('[data-prices]'))) {
          const prices = JSON.parse(price.dataset.prices ?? '{}') as Record<string, number>;
          renderNumber(price, prices[this.billing] ?? 0, locale);
        }
      });
    },
  }));
}
