/**
 * Alpine parts for the cards, sliders & carousels blocks. The Blade components
 * render the markup (and the first frame) on the server; these drive the same
 * core behaviours the React components use.
 */
import {
  type ElasticGridOptions,
  type OrbitShowcaseController,
  type RingCarouselController,
  carouselOffset,
  direction,
  elasticGrid,
  flip,
  orbitShowcase,
  pitSlider,
  ringCarousel,
  spotlight,
  stackedScroll,
  swipe,
  textDirection,
} from '@nabuxai/ui-core';
import { renderNumber } from '../number';
import type { AlpineLike, Magics } from '../types';

type Self<T> = T & Magics;

const wrap = (value: number, count: number) => (count > 0 ? ((value % count) + count) % count : 0);
const clampIndex = (value: number, count: number) => Math.min(Math.max(value, 0), Math.max(count - 1, 0));

/** Extra fingers after the first never restart a drag. */
function primaryPointerOnly(el: HTMLElement): () => void {
  const guard = (event: PointerEvent) => {
    if (!event.isPrimary) event.stopImmediatePropagation();
  };
  el.addEventListener('pointerdown', guard, true);
  return () => el.removeEventListener('pointerdown', guard, true);
}

type LivewireHooks = { hook?: (name: string, callback: () => void) => unknown };

/**
 * Run `fn` after Livewire morphs a component (the server may have moved a
 * wire:model value, or reset what a behaviour wrote). Coalesced per tick.
 */
function afterMorph(fn: () => void): () => void {
  const livewire = (window as Window & { Livewire?: LivewireHooks }).Livewire;
  if (typeof livewire?.hook !== 'function') return () => {};
  let queued = false;
  const off = livewire.hook('morphed', () => {
    if (queued) return;
    queued = true;
    queueMicrotask(() => {
      queued = false;
      fn();
    });
  });
  return typeof off === 'function' ? (off as () => void) : () => {};
}

/** A number in the page's numbering system, with an optional prefix and unit. */
function formatter(locale?: string, format?: Intl.NumberFormatOptions | null, prefix = '', suffix = '') {
  const intl = new Intl.NumberFormat(locale, format ?? undefined);
  return (value: number) => `${prefix}${intl.format(value)}${suffix ? ` ${suffix}` : ''}`.trim();
}

/** Keeps a range's fraction on its container as --nx-frac / --nx-pct, and tells assistive tech the formatted value. */
function readRange(root: HTMLElement, input: HTMLInputElement) {
  const min = Number(input.min || 0);
  const max = Number(input.max || 100);
  const value = Number(input.value);
  const fraction = Math.min(1, Math.max(0, (value - min) / (max - min || 1)));
  root.style.setProperty('--nx-frac', fraction.toFixed(4));
  root.style.setProperty('--nx-pct', `${(fraction * 100).toFixed(2)}%`);
  return value;
}

export function installCardsBlocks(Alpine: AlpineLike): void {
  /* ---- Stacked scroll cards: the fallback where CSS has no scroll timelines ------------- */
  Alpine.data('nxStackedScroll', () => {
    let stop = () => {};
    let unhook = () => {};
    return {
      init(this: Self<object>) {
        const start = () => {
          stop();
          stop = stackedScroll(this.$root);
        };
        start();
        unhook = afterMorph(start);
      },
      destroy() {
        stop();
        unhook();
      },
    };
  });

  /* ---- Ring carousel ----------------------------------------------------------------------- */
  // x-data="nxRingCarousel(0, 8, titles, 'en')" or nxRingCarousel(@entangle('slide'), …).
  Alpine.data('nxRingCarousel', (index: number = 0, count: number = 1, titles: string[] = [], locale?: string) => {
    let ring: RingCarouselController | null = null;
    let timer = 0;
    // The last index the ring itself reported: only other changes (Livewire) should turn it.
    let reported = 0;
    const number = new Intl.NumberFormat(locale);
    return {
      index,
      layers: ['', ''] as [string, string],
      front: 0,
      morphing: false,

      init(this: Self<{ index: number; layers: [string, string]; morph: () => void }>) {
        const start = wrap(Number(this.index) || 0, count);
        this.index = start;
        this.layers = [titles[start] ?? '', ''];
        reported = start;
        ring = ringCarousel(this.$root, {
          count,
          index: start,
          onChange: (i) => {
            reported = i;
            this.index = i;
          },
        });
        this.$watch('index', (next: number) => {
          const i = wrap(Number(next) || 0, count);
          if (ring && i !== reported) {
            reported = i;
            ring.go(i);
          }
          this.morph();
        });
      },

      destroy() {
        ring?.destroy();
        clearTimeout(timer);
      },

      /** The new title arrives on whichever layer is hidden; the goo runs while they cross. */
      morph(this: { index: number; layers: [string, string]; front: number; morphing: boolean }) {
        const title = titles[wrap(Number(this.index) || 0, count)] ?? '';
        if (this.layers[this.front] === title) return;
        const front = this.front === 0 ? 1 : 0;
        const layers: [string, string] = [this.layers[0], this.layers[1]];
        layers[front] = title;
        this.layers = layers;
        this.front = front;
        this.morphing = true;
        clearTimeout(timer);
        timer = window.setTimeout(() => (this.morphing = false), 460);
      },

      current(this: { index: number }) {
        return wrap(Number(this.index) || 0, count);
      },

      dir(text: string) {
        return textDirection(text);
      },

      num(n: number) {
        return number.format(n);
      },

      pick(this: { current: () => number }, i: number) {
        if (i !== this.current()) ring?.go(i);
      },

      next() {
        ring?.next();
      },

      prev() {
        ring?.prev();
      },
    };
  });

  /* ---- Expandable stack ---------------------------------------------------------------------- */
  // x-data="nxExpandableStack(false)" or nxExpandableStack(@entangle('open')).
  Alpine.data('nxExpandableStack', (open: boolean = false) => ({
    open,
    shown: false,

    init(this: Self<{ open: boolean; shown: boolean }>) {
      this.shown = !!this.open;
      this.$watch('open', (value: boolean) => {
        const list = this.$refs.list;
        if (!list) {
          this.shown = !!value;
          return;
        }
        void flip(list.children, () => {
          this.shown = !!value;
          return this.$nextTick();
        }, { stagger: 30, container: list });
      });
    },

    toggle(this: { open: boolean }) {
      this.open = !this.open;
    },
  }));

  /* ---- Orbit showcase ------------------------------------------------------------------------- */
  Alpine.data('nxOrbitShowcase', (count: number = 1, period: number = 40) => {
    let orbit: OrbitShowcaseController | null = null;
    return {
      front: 0,
      paused: false,

      init(this: Self<{ front: number }>) {
        orbit = orbitShowcase(this.$root, { count, period, onFront: (i) => (this.front = i) });
      },

      destroy() {
        orbit?.destroy();
      },

      toggle(this: { paused: boolean }) {
        this.paused = !this.paused;
        if (this.paused) orbit?.pause();
        else orbit?.play();
      },
    };
  });

  /* ---- Depth carousel ---------------------------------------------------------------------------- */
  // x-data="nxDepthCarousel(0, 5, true)" or nxDepthCarousel(@entangle('slide'), 5, true).
  Alpine.data('nxDepthCarousel', (index: number = 0, count: number = 1, loop: boolean = true, locale?: string) => {
    let stop = () => {};
    const number = new Intl.NumberFormat(locale);
    return {
      index,

      init(this: Self<{ index: number; current: () => number; step: (by: number) => void }>) {
        this.index = this.current();
        const stage = this.$refs.stage;
        if (!stage) return;
        let dragged = false;
        const release = () => setTimeout(() => (dragged = false));
        // The release that ends a drag is not a click on the card under it.
        const swallow = (event: MouseEvent) => {
          if (!dragged) return;
          dragged = false;
          event.preventDefault();
          event.stopPropagation();
        };
        const guard = primaryPointerOnly(stage);
        stage.addEventListener('click', swallow, true);
        const off = swipe(stage, {
          axis: 'x',
          threshold: 56,
          velocity: 0.11,
          ignore: 'input, textarea, select, [data-nx-no-swipe]',
          onStart: () => (dragged = true),
          onCancel: release,
          onSwipe: (swiped) => {
            release();
            stage.style.setProperty('--nx-dx', '0px');
            // "Next" sits toward inline-end: dragging toward inline-start brings it in.
            this.step((swiped === 'left') === (direction(stage) === 1) ? 1 : -1);
          },
        });
        stop = () => {
          off();
          guard();
          stage.removeEventListener('click', swallow, true);
        };
      },

      destroy() {
        stop();
      },

      current(this: { index: number }) {
        const i = Number(this.index) || 0;
        return loop ? wrap(i, count) : clampIndex(i, count);
      },

      offset(this: { current: () => number }, i: number) {
        return carouselOffset(i, this.current(), count, loop);
      },

      go(this: { index: number; current: () => number }, to: number) {
        const next = loop ? wrap(to, count) : clampIndex(to, count);
        if (next !== this.current()) this.index = next;
      },

      step(this: { current: () => number; go: (i: number) => void }, by: number) {
        this.go(this.current() + by);
      },

      num(n: number) {
        return number.format(n);
      },

      key(this: Self<{ step: (by: number) => void; go: (i: number) => void }>, event: KeyboardEvent) {
        if ((event.target as Element).closest('input, textarea, select, [contenteditable]')) return;
        const dir = direction(this.$root);
        if (event.key === 'ArrowRight') this.step(dir);
        else if (event.key === 'ArrowLeft') this.step(-dir);
        else if (event.key === 'Home') this.go(0);
        else if (event.key === 'End') this.go(count - 1);
        else return;
        event.preventDefault();
      },
    };
  });

  /* ---- Cycle stack -------------------------------------------------------------------------------- */
  // x-data="nxCycleStack(0, 4)" or nxCycleStack(@entangle('card'), 4).
  Alpine.data('nxCycleStack', (index: number = 0, count: number = 1, locale?: string) => {
    let stop = () => {};
    let timer = 0;
    const number = new Intl.NumberFormat(locale);
    return {
      index,
      tucking: null as number | null,

      init(this: Self<{ index: number; front: () => number; bind: () => void }>) {
        this.index = this.front();
        this.$watch('index', () => this.$nextTick(() => this.bind()));
        this.$watch('tucking', () => this.$nextTick(() => this.bind()));
        this.$nextTick(() => this.bind());
      },

      destroy() {
        stop();
        clearTimeout(timer);
      },

      front(this: { index: number }) {
        return wrap(Number(this.index) || 0, count);
      },

      /** 0 in front; while the top card drops, the rest already step up. */
      depth(this: { tucking: number | null; front: () => number }, i: number) {
        if (this.tucking === i) return 0;
        return wrap(i - this.front() - (this.tucking !== null ? 1 : 0), count);
      },

      isTop(this: { tucking: number | null; depth: (i: number) => number }, i: number) {
        return this.tucking === null && this.depth(i) === 0;
      },

      state(this: { tucking: number | null; isTop: (i: number) => boolean }, i: number) {
        return this.tucking === i ? 'tucking' : this.isTop(i) ? 'top' : null;
      },

      num(n: number) {
        return number.format(n);
      },

      cycle(this: Self<{ index: number; tucking: number | null; front: () => number }>) {
        if (this.tucking !== null || count < 2) return;
        const top = this.front();
        this.$root.querySelector<HTMLElement>(`[data-card="${top}"]`)?.style.setProperty('--nx-dy', '0px');
        this.tucking = top;
        // It drops first; then it rises behind the others as they step up.
        timer = window.setTimeout(() => {
          this.tucking = null;
          this.index = wrap(top + 1, count);
        }, 230);
      },

      bind(this: Self<{ tucking: number | null; front: () => number; cycle: () => void }>) {
        stop();
        stop = () => {};
        if (this.tucking !== null || count < 2) return;
        const el = this.$root.querySelector<HTMLElement>(`[data-card="${this.front()}"]`);
        if (!el) return;
        let dragged = false;
        const release = () => setTimeout(() => (dragged = false));
        const onClick = (event: MouseEvent) => {
          if (dragged) {
            dragged = false;
            event.preventDefault();
            event.stopPropagation();
            return;
          }
          if (!(event.target as Element).closest('a, button, input, textarea, select, label, [data-nx-no-swipe]')) this.cycle();
        };
        const guard = primaryPointerOnly(el);
        el.addEventListener('click', onClick);
        const off = swipe(el, {
          axis: 'y',
          threshold: 64,
          velocity: 0.11,
          onStart: () => (dragged = true),
          onCancel: release,
          onSwipe: (swiped) => {
            release();
            if (swiped === 'down') this.cycle();
            else el.style.setProperty('--nx-dy', '0px');
          },
        });
        stop = () => {
          off();
          guard();
          el.removeEventListener('click', onClick);
          for (const name of ['--nx-dx', '--nx-dy', '--nx-drag-progress', '--nx-exit-x']) el.style.removeProperty(name);
        };
      },
    };
  });

  /* ---- Elastic grid ------------------------------------------------------------------------------ */
  Alpine.data('nxElasticGrid', (options: ElasticGridOptions = {}) => {
    let stop = () => {};
    let unhook = () => {};
    return {
      init(this: Self<object>) {
        // Started again after a morph, which may have replaced the columns.
        const start = () => {
          stop();
          stop = elasticGrid(this.$root, options ?? {});
        };
        start();
        unhook = afterMorph(start);
      },
      destroy() {
        stop();
        unhook();
      },
    };
  });

  /* ---- Pit slider: the sticks follow the native range (and its wire:model) --------------------- */
  Alpine.data('nxPitSlider', (locale?: string, format?: Intl.NumberFormatOptions | null, prefix: string = '', suffix: string = '') => {
    const text = formatter(locale, format, prefix, suffix);
    let stop = () => {};
    let unhook = () => {};
    let input: HTMLInputElement | null = null;
    let sync = () => {};
    return {
      shown: '',

      init(this: Self<{ shown: string }>) {
        input = this.$root.querySelector<HTMLInputElement>('input[type="range"]');
        if (!input) return;
        sync = () => {
          if (!input) return;
          this.shown = text(readRange(this.$root, input));
          input.setAttribute('aria-valuetext', this.shown);
        };
        stop = pitSlider(this.$root);
        input.addEventListener('input', sync);
        unhook = afterMorph(sync);
        sync();
        // wire:model sets the value once the input itself initialises.
        this.$nextTick(sync);
      },

      destroy() {
        stop();
        unhook();
        input?.removeEventListener('input', sync);
      },
    };
  });

  /* ---- Precision slider: the big number rolls with the range ----------------------------------- */
  Alpine.data('nxPrecisionSlider', (locale?: string, format?: Intl.NumberFormatOptions | null, prefix: string = '', suffix: string = '') => {
    const text = formatter(locale, format, prefix, suffix);
    let unhook = () => {};
    let input: HTMLInputElement | null = null;
    let sync = () => {};
    return {
      init(this: Self<object>) {
        input = this.$root.querySelector<HTMLInputElement>('input[type="range"]');
        if (!input) return;
        sync = () => {
          if (!input) return;
          const value = readRange(this.$root, input);
          // The inner slider's track fill too: a morph may have reset its inline style.
          const track = input.closest<HTMLElement>('.nx-slider');
          if (track) readRange(track, input);
          if (this.$refs.number) renderNumber(this.$refs.number, value, locale, format ?? undefined);
          input.setAttribute('aria-valuetext', text(value));
        };
        input.addEventListener('input', sync);
        unhook = afterMorph(sync);
        this.$nextTick(sync);
      },

      destroy() {
        unhook();
        input?.removeEventListener('input', sync);
      },
    };
  });
  Alpine.data('nxInfiniteGrid', (cell = 28, min = 12, max = 48, step = 4) => ({
    cell,
    min,
    max,
    step,
    stop: null as ReturnType<typeof spotlight> | null,

    init(this: Self<{ stop: ReturnType<typeof spotlight> | null }>) {
      if (this.$refs.root) this.stop = spotlight(this.$refs.root);
    },

    destroy(this: { stop: ReturnType<typeof spotlight> | null }) {
      this.stop?.();
      this.stop = null;
    },

    get canShrink() {
      const self = this as unknown as { cell: number; min: number; step: number };
      return self.cell - self.step >= self.min;
    },

    get canGrow() {
      const self = this as unknown as { cell: number; max: number; step: number };
      return self.cell + self.step <= self.max;
    },

    nudge(this: { cell: number; min: number; max: number; step: number }, direction: number) {
      this.cell = Math.min(this.max, Math.max(this.min, this.cell + direction * this.step));
    },
  }));
}
