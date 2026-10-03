/**
 * Alpine parts for the effects & layout-motion blocks. Each wraps one core
 * behaviour so a Blade block moves exactly like its React twin; `destroy`
 * cleans up when Livewire morphs the element away. The glitch text needs
 * none: it is pure CSS.
 */
import {
  type SpringName,
  type TypewriterOptions,
  autoHeight,
  borderBeam,
  dynamicIsland,
  imageReveal,
  resizablePanels,
  scrollProgress,
  scrollToStart,
  spotlightTour,
  tourStepInfo,
  typewriter,
} from '@nabuxai/ui-core';
import type { AlpineLike, Magics } from '../types';

type Self<T> = T & Magics;
type Running = { stop: () => void };

export interface GooItemData {
  id: string;
  label: string;
  icon?: string | null;
  state?: 'enter' | 'leave' | null;
}

export interface TourStepData {
  target?: string | null;
  title: string;
  body?: string | null;
  side?: 'top' | 'bottom' | 'inline-start' | 'inline-end';
}

export function installEffectsBlocks(Alpine: AlpineLike): void {
  /* ---- Border beam: pause off screen --------------------------------------------------- */
  Alpine.data('nxBorderBeam', (pauseOffscreen = true) => ({
    stop: () => {},
    init(this: Self<Running>) {
      this.stop = borderBeam(this.$root, { pauseOffscreen });
    },
    destroy(this: Running) {
      this.stop();
    },
  }));

  /* ---- Goo stack: Alpine owns the pills; add() and dismiss() animate them ---------------- */
  Alpine.data('nxGooStack', (initial: GooItemData[] = []) => ({
    items: initial.map((item) => ({ ...item, state: null })) as GooItemData[],
    seq: 0,
    /** Add a pill at the top (or `at` an index). Returns its id. */
    add(this: { items: GooItemData[]; seq: number }, item: { label: string; icon?: string | null; id?: string }, at = 0): string {
      this.seq += 1;
      const id = item.id ?? `goo-${Date.now().toString(36)}-${this.seq}`;
      this.items.splice(Math.max(0, Math.min(at, this.items.length)), 0, { id, label: item.label, icon: item.icon ?? null, state: 'enter' });
      return id;
    },
    dismiss(this: Self<{ items: GooItemData[] }>, id: string) {
      const item = this.items.find((entry) => entry.id === id);
      if (!item || item.state === 'leave') return;
      item.state = 'leave';
      this.$dispatch('nx-goo-dismiss', { id });
      window.setTimeout(() => {
        this.items = this.items.filter((entry) => entry.id !== id);
      }, 230);
    },
  }));

  /* ---- Typewriter ---------------------------------------------------------------------------- */
  Alpine.data('nxTypewriter', (options: Omit<TypewriterOptions, 'onPhrase'>) => ({
    stop: () => {},
    init(this: Self<Running>) {
      this.stop = typewriter(this.$root, { ...options, onPhrase: (index) => this.$dispatch('nx-typewriter-phrase', { index }) });
    },
    destroy(this: Running) {
      this.stop();
    },
  }));

  /* ---- Resizable panels ------------------------------------------------------------------------ */
  Alpine.data('nxResizablePanels', (storageKey: string | null = null, step: number | null = null) => ({
    stop: () => {},
    init(this: Self<Running>) {
      this.stop = resizablePanels(this.$root, {
        storageKey: storageKey ?? undefined,
        step: step ?? undefined,
        onResize: (sizes) => this.$dispatch('nx-resize', { sizes }),
      });
    },
    destroy(this: Running) {
      this.stop();
    },
  }));

  /* ---- Scroll progress ---------------------------------------------------------------------------- */
  Alpine.data('nxScrollProgress', (target: string | null = null, container: string | null = null) => ({
    stop: () => {},
    init(this: Self<Running>) {
      this.stop = scrollProgress(this.$root, { target, container });
    },
    toTop() {
      scrollToStart(container);
    },
    destroy(this: Running) {
      this.stop();
    },
  }));

  /* ---- Auto height ------------------------------------------------------------------------------------ */
  Alpine.data('nxAutoHeight', (spring: SpringName = 'gentle') => ({
    stop: () => {},
    init(this: Self<Running>) {
      this.stop = autoHeight(this.$root, { spring });
    },
    destroy(this: Running) {
      this.stop();
    },
  }));

  /* ---- Dynamic island: `view` drives data-active; show() switches ------------------------------------- */
  Alpine.data('nxDynamicIsland', (initial = 'idle') => ({
    view: initial,
    stop: () => {},
    init(this: Self<Running & { view: string }>) {
      const island = this.$refs.island;
      if (island) this.stop = dynamicIsland(island);
    },
    show(this: { view: string }, name: string) {
      this.view = name;
    },
    destroy(this: Running) {
      this.stop();
    },
  }));

  /* ---- Spotlight tour --------------------------------------------------------------------------------------- */
  Alpine.data('nxSpotlightTour', (steps: TourStepData[] = [], options: { id?: string | null; open?: boolean; padding?: number } = {}) => ({
    steps,
    open: false,
    index: 0,
    stop: () => {},
    returnTo: null as HTMLElement | null,
    get info() {
      const self = this as unknown as TourSelf;
      return tourStepInfo(self.index, self.steps.length);
    },
    get step(): TourStepData {
      const self = this as unknown as TourSelf;
      return self.steps[self.info.index] ?? { title: '' };
    },
    init(this: Self<TourSelf>) {
      if (options.open) this.$nextTick(() => this.start());
    },
    /** Opens the tour (from `index`), unless the event names another tour. */
    start(this: Self<TourSelf>, from = 0, id: string | null = null) {
      if (id && options.id && id !== options.id) return;
      if (this.steps.length === 0) return;
      this.returnTo = document.activeElement instanceof HTMLElement ? document.activeElement : null;
      this.index = Math.max(0, Math.min(from, this.steps.length - 1));
      this.open = true;
      const root = this.$root;
      try {
        if (typeof root.showPopover === 'function' && !root.matches(':popover-open')) root.showPopover();
      } catch {
        /* no popover support: it is a fixed layer anyway */
      }
      this.$nextTick(() => this.run());
    },
    run(this: Self<TourSelf>) {
      this.stop();
      if (!this.open) return;
      const pop = this.$refs.pop;
      if (pop) {
        // Replay the entrance for every step.
        pop.style.animation = 'none';
        void pop.offsetWidth;
        pop.style.animation = '';
      }
      this.stop = spotlightTour(this.$root, {
        target: this.step.target ?? null,
        padding: options.padding ?? 8,
        side: this.step.side ?? 'bottom',
        onNext: () => this.next(),
        onBack: () => this.back(),
        onClose: () => this.close(),
      });
      this.$dispatch('nx-tour-step', { index: this.index });
    },
    next(this: Self<TourSelf>) {
      if (this.info.last) {
        this.$dispatch('nx-tour-finish');
        this.close();
        return;
      }
      this.index += 1;
      this.run();
    },
    back(this: Self<TourSelf>) {
      if (this.info.first) return;
      this.index -= 1;
      this.run();
    },
    close(this: Self<TourSelf>) {
      if (!this.open) return;
      this.stop();
      this.stop = () => {};
      this.open = false;
      const root = this.$root;
      try {
        if (typeof root.hidePopover === 'function' && root.matches(':popover-open')) root.hidePopover();
      } catch {
        /* already hidden */
      }
      this.$dispatch('nx-tour-close');
      this.returnTo?.focus({ preventScroll: true });
    },
    destroy(this: Running) {
      this.stop();
    },
  }));

  /* ---- Image reveal: mirrors the morphable <data> sync element onto the ignored root ---------------------- */
  Alpine.data('nxImageReveal', () => ({
    stop: () => {},
    watcher: null as MutationObserver | null,
    init(this: Self<Running & { watcher: MutationObserver | null }>) {
      const root = this.$root;
      const sync = this.$refs.sync;
      const mirror = () => {
        if (!sync) return;
        const progress = sync.getAttribute('value');
        const src = sync.dataset.src ?? '';
        const pending = !src || (progress !== null && progress !== '' && Number(progress) < 100);
        root.toggleAttribute('data-pending', pending);
        if (progress !== null && progress !== '') root.dataset.progress = progress;
        else delete root.dataset.progress;
      };
      mirror();
      if (sync) {
        this.watcher = new MutationObserver(mirror);
        this.watcher.observe(sync, { attributes: true });
      }
      this.stop = imageReveal(root);
    },
    /** Feed progress and/or the finished image without Livewire (dispatch `nx-image-update` on the element). */
    update(this: Self<object>, detail: { progress?: number | null; src?: string | null } = {}) {
      const sync = this.$refs.sync;
      const img = this.$root.querySelector<HTMLImageElement>('.nx-image-reveal-img');
      if (sync && 'progress' in detail) {
        if (detail.progress === null || detail.progress === undefined) sync.removeAttribute('value');
        else sync.setAttribute('value', String(Math.max(0, Math.min(100, Math.round(detail.progress)))));
      }
      if ('src' in detail) {
        if (sync) sync.dataset.src = detail.src ?? '';
        if (img) {
          if (detail.src) img.src = detail.src;
          else img.removeAttribute('src');
        }
      }
      const value = sync?.getAttribute('value');
      const bar = this.$root.querySelector<HTMLElement>('.nx-image-reveal-bar > i');
      const status = this.$root.querySelector<HTMLElement>('.nx-image-reveal-status[role="progressbar"]');
      if (value) {
        bar?.style.setProperty('--_p', String(Number(value) / 100));
        status?.setAttribute('aria-valuenow', value);
        const figure = status?.querySelector('.nx-image-reveal-status-row > span:last-child');
        if (figure) figure.textContent = `${value}%`;
      }
    },
    destroy(this: Running & { watcher: MutationObserver | null }) {
      this.watcher?.disconnect();
      this.stop();
    },
  }));
}

interface TourSelf {
  steps: TourStepData[];
  open: boolean;
  index: number;
  stop: () => void;
  returnTo: HTMLElement | null;
  readonly info: ReturnType<typeof tourStepInfo>;
  readonly step: TourStepData;
  start(from?: number, id?: string | null): void;
  run(): void;
  next(): void;
  back(): void;
  close(): void;
}
