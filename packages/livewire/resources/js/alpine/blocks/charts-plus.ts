/**
 * Charts plus (Alpine): the Livewire twins of the React advanced charts. The
 * Blade components render the chrome (head, legend toggles, brush inputs,
 * hidden table); these draw the SVG marks from the same core models
 * (js/blocks/charts-plus.ts), so both builds put every mark in the same place.
 *
 * Like the base nxChart, the marks are drawn once per size / data change and
 * hover only toggles data-active and moves the crosshair, so loops (the ping,
 * the marching forecast dashes) are never restarted by the pointer.
 */
import {
  type ChartCurve,
  type ChartFill,
  type ChartRange,
  type ChartStack,
  type ChartsPlusWord,
  type StackMode,
  brushClamp,
  brushEdge,
  brushWindow,
  chartBrush,
  chartColor,
  chartExtremes,
  chartFillDefs,
  chartFillPaint,
  chartPad,
  chartStack,
  chartTween,
  chartsPlusWord,
  composedModel,
  forecastModel,
  isRtlLocale,
  lerpChartStack,
  lineModel,
  nearestIndex,
  radarModel,
  radialBarModel,
  reveal,
  stackedAreaModel,
  stackedBarModel,
} from '@nabuxai/ui-core';
import type { AlpineLike, Magics } from '../types';

type Self<T> = T & Magics;

interface PlusSeries {
  name: string;
  values: number[];
}

interface Common {
  locale?: string;
  format?: Intl.NumberFormatOptions | null;
  words?: Partial<Record<ChartsPlusWord, string>> | null;
  height?: number;
}

const esc = (value: unknown) => String(value).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
const uid = () => `nxc${Math.random().toString(36).slice(2, 8)}`;

/* ---- Shared plumbing ----------------------------------------------------------------------- */

function tools(config: Common, format?: Intl.NumberFormatOptions | null) {
  const intl = config.locale || (typeof document !== 'undefined' ? document.documentElement.lang : '') || 'en';
  const f = format ?? config.format ?? undefined;
  const value = new Intl.NumberFormat(intl, f);
  const tick = new Intl.NumberFormat(intl, { notation: 'compact', maximumFractionDigits: 1, ...(f?.style === 'currency' ? { style: 'currency', currency: f.currency } : null) });
  const pct = new Intl.NumberFormat(intl, { style: 'percent', maximumFractionDigits: 0 });
  return {
    intl,
    rtl: isRtlLocale(intl),
    value: (n: number) => value.format(n),
    tick: (n: number) => tick.format(n),
    percent: (n: number) => pct.format(n / 100),
    word: (k: ChartsPlusWord, params?: Record<string, string | number>) => config.words?.[k] ?? chartsPlusWord(intl, k, params),
  };
}

type Tools = ReturnType<typeof tools>;

interface TipRow {
  name: string;
  value: string;
  color?: string;
  shape?: 'line' | 'rect' | 'dash';
  total?: boolean;
}

/** Fill the frosted tooltip with text nodes (labels are data, never parsed as HTML). */
function showTip(tip: HTMLElement, open: boolean, opts: { x?: number; y?: number; width?: number; title?: string; rows?: TipRow[]; below?: boolean } = {}) {
  if (!open) {
    tip.removeAttribute('data-open');
    return;
  }
  const width = opts.width ?? 0;
  const x = Math.min(Math.max(opts.x ?? 0, 76), Math.max(76, width - 76));
  tip.style.setProperty('--nx-tx', `calc(${x}px - 50%)`);
  tip.style.setProperty('--nx-ty', opts.below ? `${(opts.y ?? 0) + 12}px` : `calc(${opts.y ?? 0}px - 100% - 12px)`);
  const title = document.createElement('p');
  title.className = 'nx-chart-tooltip-title';
  title.textContent = opts.title ?? '';
  const rows = (opts.rows ?? []).map((r) => {
    const row = document.createElement('div');
    row.className = 'nx-chart-tooltip-row';
    if (r.total) row.setAttribute('data-total', '');
    const key = document.createElement('span');
    key.className = 'nx-chart-tooltip-key';
    if (r.shape) key.dataset.shape = r.shape;
    if (r.color) key.style.setProperty('--nx-series', r.color);
    const value = document.createElement('strong');
    value.textContent = r.value;
    const name = document.createElement('span');
    name.textContent = r.name;
    row.append(key, value, name);
    return row;
  });
  tip.replaceChildren(title, ...rows);
  tip.setAttribute('data-open', '');
}

function setSvg(svg: HTMLElement, width: number, height: number, body: string) {
  svg.setAttribute('viewBox', `0 0 ${width} ${height}`);
  svg.setAttribute('width', String(width));
  svg.setAttribute('height', String(height));
  svg.innerHTML = body;
}

/** Measure the plot, redraw on resize, reveal once. */
function mount(self: Self<{ width: number; draw: () => void }>) {
  const plot = self.$refs.plot ?? self.$root;
  self.width = Math.round(plot.getBoundingClientRect().width) || 560;
  self.draw();
  reveal(self.$root, { once: true });
  if ('ResizeObserver' in window) {
    new ResizeObserver(([entry]) => {
      const width = Math.round(entry!.contentRect.width);
      if (width && Math.abs(width - self.width) > 1) {
        self.width = width;
        self.draw();
      }
    }).observe(plot);
  }
}

function grid(ticks: Array<{ value: number; at: number }>, x0: number, x1: number, label: (v: number) => string, baseline?: number) {
  const base = baseline ?? ticks[0]?.value;
  return ticks
    .map((t) => `<line class="${t.value === base ? 'nx-chart-baseline' : 'nx-chart-gridline'}" x1="${x0}" x2="${x1}" y1="${t.at}" y2="${t.at}"/><text class="nx-chart-tick" x="${x0 - 8}" y="${t.at}" dy="0.32em" text-anchor="end">${esc(label(t.value))}</text>`)
    .join('');
}

function xLabels(labels: string[], xs: number[], y: number, width: number) {
  const every = Math.max(1, Math.ceil(labels.length / Math.max(2, Math.floor(width / 90))));
  return labels
    .map((label, i) => {
      if (i !== labels.length - 1 && (i % every !== 0 || labels.length - 1 - i < every * 0.6)) return '';
      const anchor = i === 0 ? 'start' : i === labels.length - 1 ? 'end' : 'middle';
      return `<text class="nx-chart-tick" x="${xs[i]}" y="${y}" text-anchor="${anchor}">${esc(label)}</text>`;
    })
    .join('');
}

function ping(x: number, y: number, color: string) {
  return `<g style="--nx-series:${color}"><circle class="nx-chart-ping-ring" cx="${x}" cy="${y}" r="4.5"/><circle class="nx-chart-ping" cx="${x}" cy="${y}" r="4.5"/></g>`;
}

function marker(x: number, y: number, kind: string, value: string, color: string, x0: number, x1: number, below = false) {
  const width = Math.round((kind.length + value.length) * 6.2 + 18);
  const left = Math.min(Math.max(x - width / 2, x0), x1 - width);
  const top = below ? y + 9 : y - 27;
  return (
    `<g class="nx-chart-marker" style="--nx-series:${color}"><circle cx="${x}" cy="${y}" r="4"/>` +
    `<rect x="${left}" y="${top}" width="${width}" height="18" rx="9"/>` +
    `<text x="${left + width / 2}" y="${top + 12.5}" text-anchor="middle"><tspan class="nx-chart-marker-kind">${esc(kind)}</tspan> ${esc(value)}</text></g>`
  );
}

function setActiveMarks(root: Element, active: number | null) {
  root.toggleAttribute('data-active', active !== null);
  for (const el of Array.from(root.querySelectorAll('svg [data-index]'))) el.toggleAttribute('data-active', Number((el as HTMLElement).dataset.index) === active);
}

function moveCrosshair(svg: Element, x: number | null) {
  const cross = svg.querySelector('.nx-chart-crosshair');
  if (!cross) return;
  if (x !== null) {
    cross.setAttribute('x1', String(x));
    cross.setAttribute('x2', String(x));
  }
  cross.toggleAttribute('data-active', x !== null);
}

function walk(event: KeyboardEvent, count: number, active: number | null, mirrored = false): number | null | undefined {
  if (event.key === 'ArrowRight' || event.key === 'ArrowLeft') {
    event.preventDefault();
    const step = (event.key === 'ArrowRight') !== mirrored ? 1 : -1;
    return active === null ? (step > 0 ? 0 : count - 1) : Math.min(Math.max(active + step, 0), count - 1);
  }
  if (event.key === 'Home') return 0;
  if (event.key === 'End') return count - 1;
  if (event.key === 'Escape') return null;
  return undefined;
}

/* ---- Stacked area / stacked bar: shared toggle + tween state ------------------------------------- */

interface StackConfig extends Common {
  labels: string[];
  series: PlusSeries[];
  hidden?: string[];
}

interface StackState {
  off: number[];
  shown: ChartStack | null;
  stop: () => void;
}

function toggleSeries(self: StackState & { draw: () => void; target: () => ChartStack }, config: StackConfig, i: number) {
  const isOff = self.off.includes(i);
  if (!isOff && self.off.length >= config.series.length - 1) return;
  self.off = isOff ? self.off.filter((n) => n !== i) : [...self.off, i];
  const from = self.shown!;
  const to = self.target();
  self.stop();
  self.stop = chartTween(560, (t) => {
    self.shown = lerpChartStack(from, to, t);
    self.draw();
  });
}

export function installChartsPlusBlocks(Alpine: AlpineLike): void {
  /* ---- Stacked area ---------------------------------------------------------------------------- */
  Alpine.data('nxStackedArea', (config: StackConfig & { mode?: StackMode; curve?: ChartCurve; fill?: ChartFill; ping?: boolean; markers?: boolean }) => ({
    active: null as number | null,
    width: 560,
    off: config.series.map((s, i) => (config.hidden?.includes(s.name) ? i : -1)).filter((i) => i >= 0),
    shown: null as ChartStack | null,
    stop: () => {},
    id: uid(),
    xs: [] as number[],
    tops: [] as number[],
    tops0: [] as number[][],

    init(this: Self<{ width: number; draw: () => void; shown: ChartStack | null; target: () => ChartStack; show: () => void }>) {
      this.shown = this.target();
      mount(this);
      this.$watch('active', () => this.show());
    },

    target(this: { off: number[] }) {
      return chartStack(
        config.series.map((s) => s.values),
        config.mode ?? 'stacked',
        config.series.map((_, i) => (this.off.includes(i) ? 0 : 1)),
      );
    },

    toggle(this: StackState & { draw: () => void; target: () => ChartStack }, i: number) {
      toggleSeries(this, config, i);
    },

    draw(this: Self<{ width: number; shown: ChartStack | null; off: number[]; id: string; xs: number[]; tops: number[]; tops0: number[][]; active: number | null; target: () => ChartStack }>) {
      const tl = tools(config);
      const { labels, series } = config;
      const mode = config.mode ?? 'stacked';
      const curve = config.curve ?? 'smooth';
      const fill = config.fill ?? 'gradient';
      const height = config.height ?? 280;
      const stack = this.shown!;
      const m = stackedAreaModel(stack, labels.length, this.width, height, curve);
      this.xs = m.xs;
      this.tops = m.tops;
      this.tops0 = m.layers.map((l) => l.top.map((p) => p[1]));
      const visible = series.map((_, i) => i).filter((i) => !this.off.includes(i));
      const top = visible[visible.length - 1] ?? 0;
      const label = (v: number) => (mode === 'percent' ? tl.percent(v) : tl.tick(Math.abs(v)));
      let body = `<defs>${chartFillDefs(this.id, series.length, fill, true)}</defs>`;
      body += grid(m.ticks, m.plot.x0, m.plot.x1, label, mode === 'expanded' ? 0 : undefined);
      body += xLabels(labels, m.xs, height - 8, this.width);
      m.layers.forEach((layer, i) => {
        body += `<path class="nx-chart-layer" data-fill="${fill}" d="${layer.area}" fill="${chartFillPaint(this.id, i, fill)}" style="--nx-i:${i}"/>`;
      });
      m.layers.forEach((layer, i) => {
        const flat = stack.upper[i]!.every((v, k) => Math.abs(v - (stack.lower[i]![k] ?? 0)) < 0.01);
        if (!flat) body += `<path class="nx-chart-line" d="${layer.line}" pathLength="1" style="--nx-series:${chartColor(i)};--nx-i:${i}"/>`;
      });
      body += `<line class="nx-chart-crosshair" x1="0" x2="0" y1="${m.plot.y0}" y2="${m.plot.y1}"/>`;
      visible.forEach((i) =>
        m.layers[i]!.top.forEach(([x, y], k) => {
          body += `<circle class="nx-chart-point" data-index="${k}" cx="${x}" cy="${y}" r="4" style="--nx-series:${chartColor(i)}"/>`;
        }),
      );
      if (config.markers) {
        const goal = this.target();
        const gm = stackedAreaModel(goal, labels.length, this.width, height, curve);
        const { max, min } = chartExtremes(goal.totals);
        if (max) body += marker(m.xs[max.index]!, gm.tops[max.index]!, tl.word('max'), tl.value(goal.totals[max.index] ?? 0), chartColor(top), m.plot.x0, m.plot.x1);
        if (min && min.index !== max?.index) body += marker(m.xs[min.index]!, gm.tops[min.index]!, tl.word('min'), tl.value(goal.totals[min.index] ?? 0), chartColor(top), m.plot.x0, m.plot.x1, gm.tops[min.index]! < m.plot.y0 + 30);
      }
      if (config.ping !== false && labels.length) {
        const last = m.layers[top]!.top[labels.length - 1]!;
        body += ping(last[0], last[1], chartColor(top));
      }
      setSvg(this.$refs.svg, this.width, height, body);
      setActiveMarks(this.$root, this.active);
      if (this.active !== null) moveCrosshair(this.$refs.svg, this.xs[this.active]!);
    },

    move(this: { active: number | null; xs: number[] }, event: PointerEvent) {
      const rect = (event.currentTarget as HTMLElement).getBoundingClientRect();
      this.active = nearestIndex(this.xs, event.clientX - rect.left);
    },

    key(this: { active: number | null }, event: KeyboardEvent) {
      const next = walk(event, config.labels.length, this.active);
      if (next !== undefined) this.active = next;
    },

    show(this: Self<{ active: number | null; xs: number[]; tops: number[]; width: number; off: number[]; shown: ChartStack | null }>) {
      const tl = tools(config);
      const i = this.active;
      setActiveMarks(this.$root, i);
      moveCrosshair(this.$refs.svg, i === null ? null : this.xs[i]!);
      if (i === null) return showTip(this.$refs.tip, false);
      const mode = config.mode ?? 'stacked';
      const visible = config.series.map((_, s) => s).filter((s) => !this.off.includes(s));
      const stack = this.shown!;
      const rows: TipRow[] = visible
        .slice()
        .reverse()
        .map((s) => {
          const raw = config.series[s]!.values[i] ?? 0;
          const share = (stack.upper[s]![i] ?? 0) - (stack.lower[s]![i] ?? 0);
          return { name: config.series[s]!.name, value: mode === 'percent' ? `${tl.percent(share)} · ${tl.value(raw)}` : tl.value(raw), color: chartColor(s), shape: 'rect' };
        });
      rows.push({ name: tl.word('total'), value: tl.value(visible.reduce((sum, s) => sum + Math.max(0, config.series[s]!.values[i] ?? 0), 0)), total: true });
      showTip(this.$refs.tip, true, { x: this.xs[i], y: this.tops[i], width: this.width, title: config.labels[i], rows });
    },
  }));

  /* ---- Stacked / percent / grouped bars ---------------------------------------------------------- */
  Alpine.data('nxStackedBar', (config: StackConfig & { mode?: 'stacked' | 'percent' | 'grouped'; orientation?: 'vertical' | 'horizontal'; fill?: ChartFill; totals?: boolean }) => ({
    active: null as number | null,
    width: 560,
    off: config.series.map((s, i) => (config.hidden?.includes(s.name) ? i : -1)).filter((i) => i >= 0),
    shown: null as ChartStack | null,
    stop: () => {},
    id: uid(),
    centers: [] as number[],
    hits: [] as Array<{ tipX: number; tipY: number }>,
    rtl: false,

    init(this: Self<{ width: number; draw: () => void; shown: ChartStack | null; target: () => ChartStack; show: () => void; rtl: boolean }>) {
      this.rtl = getComputedStyle(this.$root).direction === 'rtl' && config.orientation === 'horizontal';
      this.$root.toggleAttribute('data-rtl', this.rtl);
      this.shown = this.target();
      mount(this);
      this.$watch('active', () => this.show());
    },

    target(this: { off: number[] }) {
      return chartStack(
        config.series.map((s) => s.values),
        config.mode === 'percent' ? 'percent' : 'stacked',
        config.series.map((_, i) => (this.off.includes(i) ? 0 : 1)),
      );
    },

    toggle(this: StackState & { draw: () => void; target: () => ChartStack }, i: number) {
      toggleSeries(this, config, i);
    },

    height(this: object) {
      return config.height ?? (config.orientation === 'horizontal' ? Math.max(160, config.labels.length * 40 + 40) : 260);
    },

    total(this: { off: number[] }, index: number) {
      return config.series.reduce((sum, s, i) => (this.off.includes(i) ? sum : sum + Math.max(0, s.values[index] ?? 0)), 0);
    },

    draw(this: Self<{ width: number; shown: ChartStack | null; off: number[]; id: string; centers: number[]; hits: Array<{ tipX: number; tipY: number }>; active: number | null; rtl: boolean; height: () => number; total: (i: number) => number }>) {
      const tl = tools(config);
      const { labels, series } = config;
      const mode = config.mode ?? 'stacked';
      const horizontal = config.orientation === 'horizontal';
      const fill = config.fill ?? 'solid';
      const height = this.height();
      const weights = series.map((_, i) => (this.off.includes(i) ? 0 : 1));
      const m = stackedBarModel({ stack: mode === 'grouped' ? null : this.shown, values: series.map((s) => s.values), weights, count: labels.length, width: this.width, height, orientation: config.orientation, rtl: this.rtl });
      this.centers = m.centers;
      this.hits = m.hits;
      const label = (v: number) => (mode === 'percent' ? tl.percent(v) : tl.tick(v));
      let body = `<defs>${chartFillDefs(this.id, series.length, fill, false)}</defs>`;
      if (horizontal) {
        m.ticks.forEach((t, i) => {
          body += `<line class="${i === 0 ? 'nx-chart-baseline' : 'nx-chart-gridline'}" x1="${t.at}" x2="${t.at}" y1="${m.plot.y0}" y2="${m.plot.y1}"/><text class="nx-chart-tick" x="${t.at}" y="${height - 8}" text-anchor="middle">${esc(label(t.value))}</text>`;
        });
      } else body += grid(m.ticks, m.plot.x0, m.plot.x1, label);
      m.hits.forEach((h, i) => {
        const hh = h as unknown as { x: number; y: number; w: number; h: number };
        body += `<rect class="nx-chart-hover" data-index="${i}" x="${hh.x}" y="${hh.y}" width="${hh.w}" height="${hh.h}" rx="6"/>`;
      });
      labels.forEach((_, index) => {
        body += `<g class="nx-chart-col" data-index="${index}" style="--nx-i:${index}">`;
        m.bars.filter((b) => b.index === index).forEach((b) => (body += `<path class="nx-chart-seg" d="${b.d}" fill="${chartFillPaint(this.id, b.series, fill)}"/>`));
        if (config.totals && mode !== 'grouped') {
          const total = this.total(index);
          const text = mode === 'percent' ? tl.value(total) : tl.tick(total);
          body += horizontal
            ? `<text class="nx-chart-seg-label" x="${m.hits[index]!.tipX + (this.rtl ? -6 : 6)}" y="${m.centers[index]}" dy="0.32em" text-anchor="${this.rtl ? 'end' : 'start'}">${esc(text)}</text>`
            : `<text class="nx-chart-seg-label" x="${m.centers[index]}" y="${m.hits[index]!.tipY - 6}" text-anchor="middle">${esc(text)}</text>`;
        }
        body += '</g>';
      });
      labels.forEach((name, i) => {
        body += horizontal
          ? `<text class="nx-chart-cat" x="${this.rtl ? m.plot.x1 + 10 : m.plot.x0 - 10}" y="${m.centers[i]}" dy="0.32em" text-anchor="${this.rtl ? 'start' : 'end'}">${esc(name)}</text>`
          : `<text class="nx-chart-tick" x="${m.centers[i]}" y="${height - 8}" text-anchor="middle">${esc(name)}</text>`;
      });
      setSvg(this.$refs.svg, this.width, height, body);
      setActiveMarks(this.$root, this.active);
    },

    move(this: { active: number | null; centers: number[] }, event: PointerEvent) {
      const rect = (event.currentTarget as HTMLElement).getBoundingClientRect();
      this.active = nearestIndex(this.centers, config.orientation === 'horizontal' ? event.clientY - rect.top : event.clientX - rect.left);
    },

    key(this: { active: number | null }, event: KeyboardEvent) {
      const count = config.labels.length;
      if (config.orientation === 'horizontal' && (event.key === 'ArrowDown' || event.key === 'ArrowUp')) {
        event.preventDefault();
        const step = event.key === 'ArrowDown' ? 1 : -1;
        this.active = this.active === null ? 0 : Math.min(Math.max(this.active + step, 0), count - 1);
        return;
      }
      const next = walk(event, count, this.active);
      if (next !== undefined) this.active = next;
    },

    show(this: Self<{ active: number | null; hits: Array<{ tipX: number; tipY: number }>; width: number; off: number[]; total: (i: number) => number }>) {
      const tl = tools(config);
      const i = this.active;
      setActiveMarks(this.$root, i);
      if (i === null) return showTip(this.$refs.tip, false);
      const mode = config.mode ?? 'stacked';
      const visible = config.series.map((_, s) => s).filter((s) => !this.off.includes(s));
      const total = this.total(i);
      const rows: TipRow[] = visible
        .slice()
        .reverse()
        .map((s) => {
          const raw = config.series[s]!.values[i] ?? 0;
          const share = total > 0 ? (Math.max(0, raw) / total) * 100 : 0;
          return { name: config.series[s]!.name, value: mode === 'percent' ? `${tl.percent(share)} · ${tl.value(raw)}` : tl.value(raw), color: chartColor(s), shape: 'rect' };
        });
      if (mode !== 'grouped') rows.push({ name: tl.word('total'), value: tl.value(total), total: true });
      const hit = this.hits[i]!;
      showTip(this.$refs.tip, true, { x: hit.tipX, y: config.orientation === 'horizontal' ? hit.tipY - 14 : hit.tipY, width: this.width, title: config.labels[i], rows });
    },
  }));

  /* ---- Composed: bars + lines on two axes --------------------------------------------------------- */
  Alpine.data(
    'nxComposedChart',
    (config: Common & { labels: string[]; bars: PlusSeries[]; lines: PlusSeries[]; lineFormat?: Intl.NumberFormatOptions | null; barAxis?: string | null; lineAxis?: string | null; curve?: ChartCurve; markers?: boolean; ping?: boolean; dir?: 'ltr' | 'rtl' | null }) => ({
      active: null as number | null,
      width: 560,
      centers: [] as number[],
      tops: [] as number[],
      rtl: false,

      init(this: Self<{ width: number; draw: () => void; show: () => void; rtl: boolean }>) {
        this.rtl = config.dir ? config.dir === 'rtl' : getComputedStyle(this.$root).direction === 'rtl';
        mount(this);
        this.$watch('active', () => this.show());
      },

      draw(this: Self<{ width: number; centers: number[]; tops: number[]; rtl: boolean; active: number | null }>) {
        const tl = tools(config);
        const ll = tools(config, config.lineFormat ?? config.format);
        const height = config.height ?? 280;
        const { labels, bars, lines } = config;
        const m = composedModel({ bars: bars.map((s) => s.values), lines: lines.map((s) => s.values), count: labels.length, width: this.width, height, rtl: this.rtl, curve: config.curve ?? 'smooth' });
        this.centers = m.centers;
        this.tops = m.tops;
        const lineColor = (i: number) => chartColor(bars.length + i);
        let body = '';
        m.primary.ticks.forEach((t, i) => (body += `<line class="${i === 0 ? 'nx-chart-baseline' : 'nx-chart-gridline'}" x1="${m.plot.x0}" x2="${m.plot.x1}" y1="${t.at}" y2="${t.at}"/>`));
        m.primary.ticks.forEach((t) => (body += `<text class="nx-chart-tick" x="${m.primary.x}" y="${t.at}" dy="0.32em" text-anchor="${m.primary.anchor}">${esc(tl.tick(t.value))}</text>`));
        m.secondary.ticks.forEach((t) => (body += `<text class="nx-chart-tick" x="${m.secondary.x}" y="${t.at}" dy="0.32em" text-anchor="${m.secondary.anchor}">${esc(ll.tick(t.value))}</text>`));
        if (config.barAxis) body += `<text class="nx-chart-axis-title" x="${m.primary.x}" y="${m.plot.y0 - 6}" text-anchor="${m.primary.anchor}">${esc(config.barAxis)}</text>`;
        if (config.lineAxis) body += `<text class="nx-chart-axis-title" x="${m.secondary.x}" y="${m.plot.y0 - 6}" text-anchor="${m.secondary.anchor}">${esc(config.lineAxis)}</text>`;
        m.hits.forEach((h, i) => (body += `<rect class="nx-chart-hover" data-index="${i}" x="${h.x}" y="${m.plot.y0}" width="${h.w}" height="${m.plot.y1 - m.plot.y0}" rx="6"/>`));
        labels.forEach((_, index) => {
          body += `<g class="nx-chart-col" data-index="${index}" style="--nx-i:${index}">`;
          m.bars.filter((b) => b.index === index).forEach((b) => (body += `<path class="nx-chart-seg" d="${b.d}" fill="${chartColor(b.series)}"/>`));
          body += '</g>';
        });
        labels.forEach((name, i) => (body += `<text class="nx-chart-tick" x="${m.centers[i]}" y="${height - 8}" text-anchor="middle">${esc(name)}</text>`));
        m.lines.forEach((l, i) => (body += `<path class="nx-chart-line" d="${l.d}" pathLength="1" style="--nx-series:${lineColor(i)};--nx-i:${i + 2}"/>`));
        m.lines.forEach((l, i) => l.points.forEach(([x, y], k) => (body += `<circle class="nx-chart-dot" data-index="${k}" cx="${x}" cy="${y}" r="3" style="--nx-series:${lineColor(i)}"/>`)));
        const first = lines[0];
        if (first) {
          const { max, min } = chartExtremes(first.values);
          const pts = m.lines[0]!.points;
          if (config.markers && max) body += marker(pts[max.index]![0], pts[max.index]![1], tl.word('max'), ll.value(max.value), lineColor(0), m.plot.x0, m.plot.x1);
          if (config.markers && min && min.index !== max?.index) body += marker(pts[min.index]![0], pts[min.index]![1], tl.word('min'), ll.value(min.value), lineColor(0), m.plot.x0, m.plot.x1, true);
          if (config.ping !== false && pts.length) body += ping(pts[pts.length - 1]![0], pts[pts.length - 1]![1], lineColor(0));
        }
        setSvg(this.$refs.svg, this.width, height, body);
        setActiveMarks(this.$root, this.active);
      },

      move(this: { active: number | null; centers: number[] }, event: PointerEvent) {
        const rect = (event.currentTarget as HTMLElement).getBoundingClientRect();
        this.active = nearestIndex(this.centers, event.clientX - rect.left);
      },

      key(this: { active: number | null; rtl: boolean }, event: KeyboardEvent) {
        const next = walk(event, config.labels.length, this.active, this.rtl);
        if (next !== undefined) this.active = next;
      },

      show(this: Self<{ active: number | null; centers: number[]; tops: number[]; width: number }>) {
        const tl = tools(config);
        const ll = tools(config, config.lineFormat ?? config.format);
        const i = this.active;
        setActiveMarks(this.$root, i);
        if (i === null) return showTip(this.$refs.tip, false);
        const rows: TipRow[] = [
          ...config.bars.map((s, k) => ({ name: s.name, value: tl.value(s.values[i] ?? 0), color: chartColor(k), shape: 'rect' as const })),
          ...config.lines.map((s, k) => ({ name: s.name, value: ll.value(s.values[i] ?? 0), color: chartColor(config.bars.length + k), shape: 'line' as const })),
        ];
        showTip(this.$refs.tip, true, { x: this.centers[i], y: this.tops[i], width: this.width, title: config.labels[i], rows });
      },
    }),
  );

  /* ---- Brush chart ------------------------------------------------------------------------------- */
  Alpine.data(
    'nxBrushChart',
    (config: Common & { labels: string[]; series: PlusSeries[]; variant?: 'area' | 'line'; curve?: ChartCurve; range?: ChartRange | null; minSpan?: number; brushHeight?: number; markers?: boolean }) => {
      const count = config.labels.length;
      const minSpan = config.minSpan ?? 2;
      const initial: ChartRange = config.range ?? [Math.max(0, count - Math.max(minSpan + 1, Math.ceil(count / 3))), count - 1];
      return {
        active: null as number | null,
        width: 560,
        range: brushClamp(initial, count, minSpan),
        xs: [] as number[],
        tops: [] as number[],
        id: uid(),

        init(this: Self<{ width: number; draw: () => void; show: () => void; range: ChartRange; set: (r: ChartRange) => void }>) {
          mount(this);
          this.$watch('active', () => this.show());
          this.$watch('range', () => this.draw());
          chartBrush(this.$refs.window, { count, get: () => this.range, onChange: (next) => this.set(next) });
        },

        set(this: Self<{ range: ChartRange; active: number | null }>, next: ChartRange) {
          this.range = brushClamp(next, count, minSpan);
          this.active = null;
          this.$dispatch('nx-range', { range: this.range, from: config.labels[this.range[0]], to: config.labels[this.range[1]] });
        },

        edge(this: { range: ChartRange; set: (r: ChartRange) => void }, which: 'start' | 'end', event: Event) {
          this.set(brushEdge(this.range, which, Number((event.target as HTMLInputElement).value), count, minSpan));
        },

        readout(this: { range: ChartRange }) {
          return tools(config).word('rangeValue', { from: config.labels[this.range[0]] ?? '', to: config.labels[this.range[1]] ?? '' });
        },

        draw(this: Self<{ width: number; range: ChartRange; xs: number[]; tops: number[]; id: string; active: number | null }>) {
          const tl = tools(config);
          const height = config.height ?? 240;
          const brushH = config.brushHeight ?? 56;
          const curve = config.curve ?? 'smooth';
          const [a, b] = this.range;
          const slice = config.series.map((s) => s.values.slice(a, b + 1));
          const labels = config.labels.slice(a, b + 1);
          const m = lineModel(slice, this.width, height, { curve });
          this.xs = m.xs;
          this.tops = m.xs.map((_, i) => Math.min(...m.series.map((s) => s.points[i]?.[1] ?? height)));
          let body = `<defs>${chartFillDefs(this.id, config.series.length, 'gradient', true)}</defs>`;
          body += grid(m.ticks, m.plot.x0, m.plot.x1, tl.tick);
          body += xLabels(labels, m.xs, height - 8, this.width);
          if ((config.variant ?? 'area') === 'area') m.series.forEach((s, i) => (body += `<path class="nx-chart-area" d="${s.area}" style="fill:${chartFillPaint(this.id, i, 'gradient')};fill-opacity:1"/>`));
          m.series.forEach((s, i) => (body += `<path class="nx-chart-line" d="${s.line}" pathLength="1" style="--nx-series:${chartColor(i)};--nx-i:${i}"/>`));
          body += `<line class="nx-chart-crosshair" x1="0" x2="0" y1="${m.plot.y0}" y2="${m.plot.y1}"/>`;
          m.series.forEach((s, i) => s.points.forEach(([x, y], k) => (body += `<circle class="nx-chart-point" data-index="${k}" cx="${x}" cy="${y}" r="4" style="--nx-series:${chartColor(i)}"/>`)));
          if (config.markers !== false && m.series[0]) {
            const { max, min } = chartExtremes(slice[0]!);
            const pts = m.series[0].points;
            if (max) body += marker(pts[max.index]![0], pts[max.index]![1], tl.word('max'), tl.value(max.value), chartColor(0), m.plot.x0, m.plot.x1);
            if (min && min.index !== max?.index) body += marker(pts[min.index]![0], pts[min.index]![1], tl.word('min'), tl.value(min.value), chartColor(0), m.plot.x0, m.plot.x1, true);
          }
          setSvg(this.$refs.svg, this.width, height, body);

          // The brush strip: the whole series, the window and the shades.
          const track = Math.max(1, this.width - chartPad.left - chartPad.right);
          const mini = lineModel(
            config.series.map((s) => s.values),
            track,
            brushH,
            { pad: { top: 6, bottom: 4, left: 0, right: 0 }, curve },
          );
          this.$refs.mini.setAttribute('viewBox', `0 0 ${track} ${brushH}`);
          this.$refs.mini.innerHTML = mini.series.map((s, i) => `<g style="--nx-series:${chartColor(i)}"><path class="nx-chart-area" d="${s.area}"/><path class="nx-chart-line" d="${s.line}"/></g>`).join('');
          const win = brushWindow(this.range, count, track);
          this.$refs.window.style.left = `${win.start}px`;
          this.$refs.window.style.width = `${win.size}px`;
          this.$refs.shadeStart.style.width = `${win.start}px`;
          this.$refs.shadeEnd.style.width = `${Math.max(0, track - win.start - win.size)}px`;
        },

        move(this: { active: number | null; xs: number[] }, event: PointerEvent) {
          const rect = (event.currentTarget as HTMLElement).getBoundingClientRect();
          this.active = nearestIndex(this.xs, event.clientX - rect.left);
        },

        key(this: { active: number | null; range: ChartRange }, event: KeyboardEvent) {
          const next = walk(event, this.range[1] - this.range[0] + 1, this.active);
          if (next !== undefined) this.active = next;
        },

        show(this: Self<{ active: number | null; xs: number[]; tops: number[]; width: number; range: ChartRange }>) {
          const tl = tools(config);
          const i = this.active;
          setActiveMarks(this.$root, i);
          moveCrosshair(this.$refs.svg, i === null ? null : this.xs[i]!);
          if (i === null) return showTip(this.$refs.tip, false);
          const at = this.range[0] + i;
          showTip(this.$refs.tip, true, {
            x: this.xs[i],
            y: this.tops[i],
            width: this.width,
            title: config.labels[at],
            rows: config.series.map((s, k) => ({ name: s.name, value: tl.value(s.values[at] ?? 0), color: chartColor(k) })),
          });
        },
      };
    },
  );

  /* ---- Forecast ------------------------------------------------------------------------------------ */
  Alpine.data(
    'nxForecast',
    (config: Common & { labels: string[]; actual: number[]; forecast: number[]; lower?: number[] | null; upper?: number[] | null; animated?: boolean; curve?: ChartCurve; markers?: boolean; ping?: boolean; todayLabel?: string | null }) => ({
      active: null as number | null,
      width: 560,
      xs: [] as number[],
      tops: [] as number[],
      points: [] as Array<[number, number]>,
      id: uid(),

      init(this: Self<{ width: number; draw: () => void; show: () => void }>) {
        mount(this);
        this.$watch('active', () => this.show());
      },

      draw(this: Self<{ width: number; xs: number[]; tops: number[]; points: Array<[number, number]>; id: string; active: number | null }>) {
        const tl = tools(config);
        const height = config.height ?? 280;
        const m = forecastModel({ actual: config.actual, forecast: config.forecast, lower: config.lower ?? [], upper: config.upper ?? [], width: this.width, height, curve: config.curve ?? 'smooth' });
        const k = config.actual.length;
        this.xs = m.xs;
        this.tops = m.tops;
        this.points = m.xs.map((_, i) => (i < k ? m.actual.points[i] : m.forecast.points[i - k + 1]) as [number, number]);
        const color = chartColor(0);
        let body = `<defs>${chartFillDefs(this.id, 1, 'gradient', true)}</defs>`;
        body += `<rect class="nx-chart-future" x="${m.today}" y="${m.plot.y0}" width="${Math.max(0, m.plot.x1 - m.today)}" height="${m.plot.y1 - m.plot.y0}" rx="4"/>`;
        body += grid(m.ticks, m.plot.x0, m.plot.x1, tl.tick);
        body += xLabels(config.labels, m.xs, height - 8, this.width);
        if (m.band) body += `<path class="nx-chart-band" d="${m.band}"/>`;
        body += `<path class="nx-chart-area" d="${m.actual.area}" style="fill:${chartFillPaint(this.id, 0, 'gradient')};fill-opacity:1"/>`;
        body += `<path class="nx-chart-line" d="${m.actual.line}" pathLength="1"/>`;
        body += `<path class="nx-chart-forecast" d="${m.forecast.line}"${config.animated ? ' data-animate=""' : ''}/>`;
        const today = config.todayLabel === undefined ? tl.word('today') : config.todayLabel;
        body += `<g class="nx-chart-today"><line x1="${m.today}" x2="${m.today}" y1="${m.plot.y0}" y2="${m.plot.y1}"/>`;
        if (today) {
          const w = Math.round(today.length * 6.4 + 16);
          body += `<rect x="${m.today - w / 2}" y="${m.plot.y0 - 14}" width="${w}" height="17" rx="8.5"/><text x="${m.today}" y="${m.plot.y0 - 2}" text-anchor="middle">${esc(today)}</text>`;
        }
        body += '</g>';
        body += `<line class="nx-chart-crosshair" x1="0" x2="0" y1="${m.plot.y0}" y2="${m.plot.y1}"/>`;
        this.points.forEach((p, i) => p && (body += `<circle class="nx-chart-point" data-index="${i}" cx="${p[0]}" cy="${p[1]}" r="4"/>`));
        if (config.markers !== false) {
          const { max, min } = chartExtremes(config.actual);
          const pts = m.actual.points;
          if (max) body += marker(pts[max.index]![0], pts[max.index]![1], tl.word('max'), tl.value(max.value), color, m.plot.x0, m.plot.x1);
          if (min && min.index !== max?.index) body += marker(pts[min.index]![0], pts[min.index]![1], tl.word('min'), tl.value(min.value), color, m.plot.x0, m.plot.x1, true);
        }
        if (config.ping !== false && k) body += ping(m.actual.points[k - 1]![0], m.actual.points[k - 1]![1], color);
        this.$refs.svg.style.setProperty('--nx-series', color);
        setSvg(this.$refs.svg, this.width, height, body);
      },

      move(this: { active: number | null; xs: number[] }, event: PointerEvent) {
        const rect = (event.currentTarget as HTMLElement).getBoundingClientRect();
        this.active = nearestIndex(this.xs, event.clientX - rect.left);
      },

      key(this: { active: number | null }, event: KeyboardEvent) {
        const next = walk(event, config.labels.length, this.active);
        if (next !== undefined) this.active = next;
      },

      show(this: Self<{ active: number | null; xs: number[]; tops: number[]; width: number }>) {
        const tl = tools(config);
        const i = this.active;
        setActiveMarks(this.$root, i);
        moveCrosshair(this.$refs.svg, i === null ? null : this.xs[i]!);
        if (i === null) return showTip(this.$refs.tip, false);
        const k = config.actual.length;
        const color = chartColor(0);
        let rows: TipRow[];
        if (i < k) rows = [{ name: tl.word('actual'), value: tl.value(config.actual[i] ?? 0), color, shape: 'line' }];
        else {
          const j = i - k;
          rows = [{ name: tl.word('forecast'), value: tl.value(config.forecast[j] ?? 0), color, shape: 'dash' }];
          if (config.lower && config.upper) rows.push({ name: `${tl.word('low')} – ${tl.word('high')}`, value: `${tl.value(config.lower[j] ?? 0)} – ${tl.value(config.upper[j] ?? 0)}`, total: true });
        }
        showTip(this.$refs.tip, true, { x: this.xs[i], y: this.tops[i], width: this.width, title: config.labels[i], rows });
      },
    }),
  );

  /* ---- Radar --------------------------------------------------------------------------------------- */
  Alpine.data('nxRadar', (config: Common & { axes: string[]; series: PlusSeries[]; max?: number | null; rings?: number; size?: number; hidden?: string[]; dir?: 'ltr' | 'rtl' | null }) => ({
    active: null as number | null,
    off: config.series.map((s, i) => (config.hidden?.includes(s.name) ? i : -1)).filter((i) => i >= 0),
    clockwise: true,
    model: null as ReturnType<typeof radarModel> | null,

    init(this: Self<{ draw: () => void; show: () => void; clockwise: boolean }>) {
      const rtl = config.dir ? config.dir === 'rtl' : getComputedStyle(this.$root).direction === 'rtl';
      this.clockwise = !rtl;
      this.draw();
      reveal(this.$root, { once: true });
      this.$watch('active', () => this.show());
      this.$watch('off', () => this.draw());
    },

    toggle(this: { off: number[] }, i: number) {
      const isOff = this.off.includes(i);
      if (!isOff && this.off.length >= config.series.length - 1) return;
      this.off = isOff ? this.off.filter((n) => n !== i) : [...this.off, i];
    },

    draw(this: Self<{ off: number[]; clockwise: boolean; model: ReturnType<typeof radarModel> | null; active: number | null }>) {
      const size = config.size ?? 340;
      const m = radarModel(
        config.series.map((s) => s.values),
        config.axes.length,
        size,
        { max: config.max ?? undefined, rings: config.rings ?? 4, clockwise: this.clockwise },
      );
      this.model = m;
      let body = `<g>${m.rings.map((d) => `<path class="nx-radar-ring" d="${d}"/>`).join('')}</g>`;
      m.spokes.forEach(([x1, y1, x2, y2], i) => (body += `<line class="nx-radar-spoke" data-index="${i}" x1="${x1}" y1="${y1}" x2="${x2}" y2="${y2}"/>`));
      m.labels.forEach((l, i) => (body += `<text class="nx-radar-label" data-index="${i}" x="${l.x}" y="${l.y}" dy="0.32em" text-anchor="${l.anchor}">${esc(config.axes[i])}</text>`));
      m.shapes.forEach((s, i) => (body += `<path class="nx-radar-shape" d="${s.d}"${this.off.includes(i) ? ' data-off=""' : ''} style="--nx-series:${chartColor(i)};--nx-i:${i}"/>`));
      m.shapes.forEach((s, i) => {
        if (this.off.includes(i)) return;
        s.points.forEach(([x, y], k) => (body += `<circle class="nx-radar-dot" data-index="${k}" cx="${x}" cy="${y}" r="4" style="--nx-series:${chartColor(i)}"/>`));
      });
      this.$refs.svg.setAttribute('viewBox', `0 0 ${size} ${size}`);
      this.$refs.svg.innerHTML = body;
      setActiveMarks(this.$root, this.active);
    },

    move(this: Self<{ active: number | null; clockwise: boolean; model: ReturnType<typeof radarModel> | null }>, event: PointerEvent) {
      const size = config.size ?? 340;
      const m = this.model!;
      const rect = this.$refs.svg.getBoundingClientRect();
      const scale = size / (rect.width || size);
      const dx = (event.clientX - rect.left) * scale - m.cx;
      const dy = (event.clientY - rect.top) * scale - m.cy;
      if (Math.hypot(dx, dy) < 8) return;
      let angle = (Math.atan2(dx, -dy) * 180) / Math.PI;
      if (!this.clockwise) angle = -angle;
      const n = config.axes.length;
      this.active = ((Math.round(((angle + 360) % 360) / (360 / n)) % n) + n) % n;
    },

    key(this: { active: number | null }, event: KeyboardEvent) {
      const n = config.axes.length;
      if (['ArrowRight', 'ArrowDown', 'ArrowLeft', 'ArrowUp'].includes(event.key)) {
        event.preventDefault();
        const step = event.key === 'ArrowRight' || event.key === 'ArrowDown' ? 1 : -1;
        this.active = this.active === null ? 0 : (this.active + step + n) % n;
      } else if (event.key === 'Escape') this.active = null;
    },

    show(this: Self<{ active: number | null; off: number[]; model: ReturnType<typeof radarModel> | null }>) {
      const tl = tools(config);
      const i = this.active;
      setActiveMarks(this.$root, i);
      if (i === null || !this.model) return showTip(this.$refs.tip, false);
      const size = config.size ?? 340;
      const plot = this.$refs.plot.getBoundingClientRect();
      const svg = this.$refs.svg.getBoundingClientRect();
      const scale = svg.width / size;
      const spoke = this.model.spokes[i]!;
      const rows: TipRow[] = config.series
        .map((s, k) => ({ s, k }))
        .filter(({ k }) => !this.off.includes(k))
        .map(({ s, k }) => ({ name: s.name, value: tl.value(s.values[i] ?? 0), color: chartColor(k), shape: 'rect' as const }));
      showTip(this.$refs.tip, true, { x: svg.left - plot.left + spoke[2] * scale, y: svg.top - plot.top + spoke[3] * scale, width: plot.width, title: config.axes[i], rows, below: spoke[3] < this.model.cy });
    },
  }));

  /* ---- Radial bars --------------------------------------------------------------------------------- */
  Alpine.data('nxRadialBar', (config: Common & { values: number[]; maxes: number[]; labels: string[]; sweep?: number; centerValue?: string | null; centerLabel?: string | null; dir?: 'ltr' | 'rtl' | null }) => ({
    active: null as number | null,
    shares: [] as number[],

    init(this: Self<{ draw: () => void; active: number | null }>) {
      this.draw();
      reveal(this.$root, { once: true });
      this.$watch('active', () => setActiveMarks(this.$root, this.active));
    },

    draw(this: Self<{ shares: number[] }>) {
      const rtl = config.dir ? config.dir === 'rtl' : getComputedStyle(this.$root).direction === 'rtl';
      const m = radialBarModel(config.values, config.maxes, { sweep: config.sweep ?? 270, clockwise: !rtl });
      this.shares = m.rings.map((r) => r.share);
      let body = m.rings.map((r) => `<path class="nx-radial-track" d="${r.track}"/>`).join('');
      m.rings.forEach((r, i) => {
        body += `<path class="nx-radial-value" data-index="${i}" d="${r.track}" pathLength="100"${r.share <= 0 ? ' data-empty=""' : ''} style="--nx-series:${chartColor(i)};--_v:${r.share};--nx-i:${i}"/>`;
      });
      this.$refs.svg.style.setProperty('--_t', String(m.thickness));
      this.$refs.svg.innerHTML = body;
      for (const el of Array.from(this.$refs.svg.querySelectorAll('.nx-radial-value'))) {
        el.addEventListener('pointerenter', () => ((this as unknown as { active: number | null }).active = Number((el as HTMLElement).dataset.index)));
      }
    },

    centerValue(this: { active: number | null; shares: number[] }) {
      const tl = tools(config);
      if (this.active !== null) return tl.percent(this.shares[this.active] ?? 0);
      if (config.centerValue) return config.centerValue;
      return tl.percent(this.shares.reduce((a, b) => a + b, 0) / Math.max(1, this.shares.length));
    },

    centerLabel(this: { active: number | null }) {
      if (this.active !== null) return config.labels[this.active] ?? '';
      return config.centerLabel ?? tools(config).word('share');
    },
  }));
}
