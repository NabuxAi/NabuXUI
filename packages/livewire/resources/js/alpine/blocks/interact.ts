/**
 * Alpine parts for the interaction blocks. The Blade components render the
 * markup (and every native control a wire:model can sit on); these wire the
 * core behaviours to it and report back with DOM events (nx-confirm,
 * nx-change, nx-sort, nx-undone, nx-action) or a Livewire call (`action`).
 */
import {
  type Cleanup,
  type HoldState,
  type LightboxStageController,
  type PasswordRuleId,
  type PasswordStrength,
  type SlideState,
  type SortMessages,
  closeLightbox,
  compareSlider,
  createLightboxStage,
  createOdometer,
  direction,
  holdToConfirm,
  morphWidth,
  openLightbox,
  passwordStrength,
  slideToConfirm,
  snackbarTimer,
  sortable,
  swipeActions,
} from '@nabuxai/ui-core';
import type { AlpineLike, Magics } from '../types';

/** Livewire's $wire, when the component sits inside a Livewire component. */
interface Wire {
  call(method: string, ...params: unknown[]): Promise<unknown>;
}

type Self<T> = T & Magics & { $wire?: Wire };

type SnackbarCloseReason = 'timeout' | 'close' | 'undo';

/** Call a Livewire method by name (when inside a Livewire component). */
async function callWire(self: { $wire?: Wire }, action: string | null | undefined, ...params: unknown[]): Promise<unknown> {
  if (!action || !self.$wire) return undefined;
  return self.$wire.call(action, ...params);
}

interface ConfirmOptions {
  duration?: number;
  resetAfter?: number;
  threshold?: number;
  action?: string | null;
}

interface InlineEditOptions {
  action?: string | null;
  required?: boolean;
  blur?: 'save' | 'cancel';
  savingLabel?: string;
  errorLabel?: string;
  requiredLabel?: string;
}

interface SnackItem {
  id: string;
  message: string;
  undoable: boolean;
  duration: number;
  icon: string | null;
  /** Livewire method to call on Undo, with its params. */
  undo: string | null;
  params: unknown[];
  state: 'open' | 'closing';
}

interface LightboxItem {
  src: string;
  thumb?: string;
  alt: string;
  caption?: string;
}

let snackSeq = 0;

export function installInteractBlocks(Alpine: AlpineLike): void {
  /* ---- Hold to confirm -------------------------------------------------------------- */
  Alpine.data('nxHold', (options: ConfirmOptions = {}) => {
    let stop: Cleanup = () => {};
    return {
      state: 'idle' as HoldState,

      init(this: Self<{ state: HoldState }>) {
        stop = holdToConfirm(this.$root, {
          duration: options.duration,
          resetAfter: options.resetAfter,
          onStateChange: (state) => (this.state = state),
          onConfirm: () => {
            this.$dispatch('nx-confirm');
            void callWire(this, options.action);
          },
        });
      },

      destroy() {
        stop();
      },
    };
  });

  /* ---- Slide to confirm ------------------------------------------------------------- */
  Alpine.data('nxSlideConfirm', (options: ConfirmOptions = {}) => {
    let stop: Cleanup = () => {};
    return {
      state: 'idle' as SlideState,

      init(this: Self<{ state: SlideState }>) {
        stop = slideToConfirm(this.$root, {
          threshold: options.threshold,
          resetAfter: options.resetAfter,
          onStateChange: (state) => (this.state = state),
          onConfirm: () => {
            this.$dispatch('nx-confirm');
            void callWire(this, options.action);
          },
        });
      },

      destroy() {
        stop();
      },

      /** Rewind to the start (e.g. after the server refused). */
      reset(this: Self<object>) {
        this.$root.dispatchEvent(new CustomEvent('nx-reset'));
      },
    };
  });

  /* ---- Inline edit -------------------------------------------------------------------
   * `value` may be @entangle'd to a Livewire property: a successful save writes
   * it, and with `action` the save goes through $wire.call(action, value) first
   * (return false or a message from PHP to keep the field open with an error).
   */
  Alpine.data('nxInlineEdit', (initial: string = '', options: InlineEditOptions = {}) => ({
    value: initial ?? '',
    draft: '',
    state: 'idle' as 'idle' | 'editing' | 'saving' | 'error',
    message: '',

    /** Change mode and let the width spring from what it was. */
    go(this: Self<{ state: string }>, next: 'idle' | 'editing' | 'saving' | 'error', focus: 'input' | 'display' | null = null) {
      const from = this.$root.offsetWidth;
      this.state = next;
      void this.$nextTick(() => {
        morphWidth(this.$root, undefined, { from, spring: 'snappy' });
        if (focus === 'input') {
          this.$refs.input?.focus();
          (this.$refs.input as HTMLInputElement | undefined)?.select();
        } else if (focus === 'display') this.$refs.display?.focus();
      });
    },

    start(this: Self<{ value: string; draft: string; message: string; go: (s: 'editing', f: 'input') => void }>) {
      if (this.$root.hasAttribute('data-disabled')) return;
      this.draft = this.value;
      this.message = '';
      this.go('editing', 'input');
    },

    cancel(this: Self<{ value: string; draft: string; message: string; go: (s: 'idle', f: 'display' | null) => void }>, focus = true) {
      this.draft = this.value;
      this.message = '';
      this.go('idle', focus ? 'display' : null);
    },

    async commit(
      this: Self<{ value: string; draft: string; message: string; state: string; cancel: (f?: boolean) => void; go: (s: 'idle', f: 'display' | null) => void }>,
      focus = true,
    ) {
      if (this.state === 'saving') return;
      const next = this.draft.trim() === '' ? '' : this.draft;
      if (next === this.value) return this.cancel(focus);
      if (options.required && next.trim() === '') {
        this.message = options.requiredLabel ?? 'Required';
        this.state = 'error';
        return;
      }
      // Listeners may refuse the value: x-on:nx-validate="$event.detail.error = '…'".
      const check = new CustomEvent('nx-validate', { detail: { value: next, error: null as string | null } });
      this.$root.dispatchEvent(check);
      if (check.detail.error) {
        this.message = check.detail.error;
        this.state = 'error';
        return;
      }
      if (options.action && this.$wire) {
        this.state = 'saving';
        this.message = options.savingLabel ?? 'Saving…';
        try {
          const result = await callWire(this, options.action, next);
          if (result === false || typeof result === 'string') {
            this.message = typeof result === 'string' ? result : (options.errorLabel ?? 'Could not save');
            this.state = 'error';
            void this.$nextTick(() => this.$refs.input?.focus());
            return;
          }
        } catch {
          this.message = options.errorLabel ?? 'Could not save';
          this.state = 'error';
          void this.$nextTick(() => this.$refs.input?.focus());
          return;
        }
      }
      this.value = next;
      this.message = '';
      this.$dispatch('nx-change', next);
      this.go('idle', focus ? 'display' : null);
    },

    key(this: { commit: () => void; cancel: () => void }, event: KeyboardEvent) {
      if (event.key === 'Enter') {
        event.preventDefault();
        this.commit();
      } else if (event.key === 'Escape') {
        event.preventDefault();
        event.stopPropagation();
        this.cancel();
      }
    },

    blurred(this: { state: string; commit: (f?: boolean) => void; cancel: (f?: boolean) => void }) {
      if (this.state !== 'editing') return;
      if (options.blur === 'cancel') this.cancel(false);
      else this.commit(false);
    },

    typed(this: { state: string }) {
      if (this.state === 'error') this.state = 'editing';
    },
  }));

  /* ---- Odometer: follows data-value, which a Livewire re-render morphs ------------- */
  Alpine.data('nxOdometer', (options: { from?: number; locale?: string; format?: Intl.NumberFormatOptions; reveal?: boolean } = {}) => {
    let ctrl: ReturnType<typeof createOdometer> | null = null;
    let observer: MutationObserver | null = null;
    return {
      init(this: Self<object>) {
        const root = this.$root;
        const read = () => Number(root.getAttribute('data-value') ?? 0);
        ctrl = createOdometer(root, { value: read(), from: options.from, locale: options.locale, format: options.format, reveal: options.reveal });
        observer = new MutationObserver(() => ctrl?.update(read()));
        observer.observe(root, { attributes: true, attributeFilter: ['data-value'] });
      },

      /** Set it from the browser: x-on:click="set(count + 1)". */
      set(this: Self<object>, value: number) {
        this.$root.setAttribute('data-value', String(value));
      },

      destroy() {
        observer?.disconnect();
        ctrl?.destroy();
      },
    };
  });

  /* ---- Undo snackbars ----------------------------------------------------------------
   * Push from anywhere: window event `nx-undo` with { message, undo?, params?,
   * duration?, undoable?, icon?, id? } — Livewire: $this->dispatch('nx-undo', …).
   * Undo calls the Livewire method `undo` with `params` and fires `nx-undone`
   * (detail: { id, params }) on window for Alpine-only pages.
   */
  Alpine.data('nxSnackbars', (defaults: { duration?: number } = {}) => {
    const stops = new Map<string, Cleanup>();
    return {
      items: [] as SnackItem[],

      init(this: Self<object>) {
        // The top layer, so a transformed or clipping ancestor never traps the fixed stack.
        const root = this.$root;
        if (root.getAttribute('data-position') === 'inline' || typeof root.showPopover !== 'function') return;
        root.setAttribute('popover', 'manual');
        try {
          root.showPopover();
        } catch {
          /* already shown */
        }
      },

      push(this: { items: SnackItem[] }, detail: Partial<SnackItem> & { message?: string } = {}) {
        const id = String(detail.id ?? `nx-undo-${++snackSeq}`);
        this.items = this.items.filter((item) => item.id !== id);
        this.items.push({
          id,
          message: String(detail.message ?? ''),
          undoable: detail.undoable !== false,
          duration: Number(detail.duration ?? defaults.duration ?? 6000),
          icon: detail.icon ?? null,
          undo: detail.undo ?? null,
          params: Array.isArray(detail.params) ? detail.params : detail.params === undefined ? [] : [detail.params],
          state: 'open',
        });
      },

      mount(this: { dismiss: (id: string, reason: SnackbarCloseReason) => void }, el: HTMLElement, item: SnackItem) {
        stops.get(item.id)?.();
        stops.set(item.id, snackbarTimer(el, { duration: item.duration, onExpire: () => this.dismiss(item.id, 'timeout') }));
      },

      dismiss(this: { items: SnackItem[] }, id: string, reason: SnackbarCloseReason = 'close') {
        const item = this.items.find((entry) => entry.id === id);
        if (!item || item.state === 'closing') return;
        stops.get(id)?.();
        stops.delete(id);
        item.state = 'closing';
        window.dispatchEvent(new CustomEvent('nx-snackbar-closed', { detail: { id, reason } }));
        setTimeout(() => {
          this.items = this.items.filter((entry) => entry.id !== id);
        }, 160);
      },

      undo(this: Self<{ dismiss: (id: string, reason: SnackbarCloseReason) => void }>, item: SnackItem) {
        window.dispatchEvent(new CustomEvent('nx-undone', { detail: { id: item.id, params: item.params } }));
        void callWire(this, item.undo, ...item.params);
        this.dismiss(item.id, 'undo');
      },

      destroy() {
        for (const stop of stops.values()) stop();
        stops.clear();
      },
    };
  });

  /* ---- Sortable list: the core moves the nodes; the new order goes out ------------- */
  Alpine.data('nxSortable', (options: { action?: string | null; messages?: Partial<SortMessages>; locale?: string } = {}) => {
    let stop: Cleanup = () => {};
    return {
      init(this: Self<object>) {
        stop = sortable(this.$root, {
          messages: options.messages,
          locale: options.locale,
          onChange: (keys) => {
            this.$dispatch('nx-sort', keys);
            void callWire(this, options.action, keys);
          },
        });
      },

      destroy() {
        stop();
      },
    };
  });

  /* ---- Lightbox ------------------------------------------------------------------------ */
  Alpine.data('nxLightbox', (images: LightboxItem[] = []) => {
    let stage: LightboxStageController | null = null;
    return {
      images,
      index: 0,
      zoomed: false,

      current(this: { images: LightboxItem[]; index: number }): LightboxItem | undefined {
        return this.images[this.index];
      },

      init(this: Self<{ next: () => void; prev: () => void; close: () => void; zoomed: boolean }>) {
        stage = createLightboxStage(this.$refs.stage!, {
          onNext: () => this.next(),
          onPrev: () => this.prev(),
          onClose: () => this.close(),
          onZoomChange: (zoom) => (this.zoomed = zoom > 1.01),
        });
      },

      destroy() {
        stage?.destroy();
      },

      thumb(this: Self<object>, i: number): HTMLElement | null {
        return this.$root.querySelectorAll<HTMLElement>('.nx-lightbox-thumb')[i] ?? null;
      },

      async open(this: Self<{ index: number; thumb: (i: number) => HTMLElement | null; animate: (dir: string | null) => void }>, i: number) {
        this.index = i;
        this.animate(null);
        await this.$nextTick();
        await openLightbox(this.$refs.dialog as HTMLDialogElement, this.thumb(i)?.querySelector('img') ?? null, () => this.$refs.hero ?? null);
      },

      async close(this: Self<{ index: number; thumb: (i: number) => HTMLElement | null }>) {
        const dialog = this.$refs.dialog as HTMLDialogElement;
        if (!dialog.open) return;
        const thumb = this.thumb(this.index);
        await closeLightbox(dialog, thumb?.querySelector('img') ?? null, () => this.$refs.hero ?? null);
        stage?.reset();
        thumb?.focus();
      },

      go(this: Self<{ index: number; images: LightboxItem[]; animate: (dir: string | null) => void }>, next: number, dir: 'next' | 'prev') {
        const total = this.images.length;
        if (total < 2) return;
        this.index = (next + total) % total;
        stage?.reset();
        this.animate(dir);
      },

      /** Replay the slide-in from the side the new image comes from. */
      animate(this: Self<object>, dir: string | null) {
        const box = this.$refs.stage!;
        void this.$nextTick(() => {
          if (dir) box.setAttribute('data-nav', dir);
          else box.removeAttribute('data-nav');
          const img = this.$refs.hero;
          if (!img || !dir) return;
          img.style.animation = 'none';
          void img.offsetWidth;
          img.style.animation = '';
        });
      },

      next(this: { go: (n: number, d: 'next') => void; index: number }) {
        this.go(this.index + 1, 'next');
      },

      prev(this: { go: (n: number, d: 'prev') => void; index: number }) {
        this.go(this.index - 1, 'prev');
      },

      zoomBy(factor: number) {
        stage?.zoomBy(factor);
      },

      /** A number in the page's digits (the counter). */
      num(this: Self<object>, n: number): string {
        const lang = this.$root.closest('[lang]')?.getAttribute('lang') || undefined;
        return new Intl.NumberFormat(lang).format(n);
      },

      key(this: Self<{ next: () => void; prev: () => void; go: (n: number, d: 'next' | 'prev') => void; images: LightboxItem[] }>, event: KeyboardEvent) {
        const dir = direction(this.$root);
        const forward = dir === 1 ? 'ArrowRight' : 'ArrowLeft';
        const back = dir === 1 ? 'ArrowLeft' : 'ArrowRight';
        if (event.key === forward) this.next();
        else if (event.key === back) this.prev();
        else if (event.key === 'Home') this.go(0, 'prev');
        else if (event.key === 'End') this.go(this.images.length - 1, 'next');
        else if (event.key === '+' || event.key === '=') stage?.zoomBy(1.5);
        else if (event.key === '-') stage?.zoomBy(1 / 1.5);
        else if (event.key === '0') stage?.reset();
        else return;
        event.preventDefault();
      },
    };
  });

  /* ---- Compare slider: the range does the work; value may be @entangle'd ----------- */
  Alpine.data('nxCompare', () => {
    let stop: Cleanup = () => {};
    return {
      init(this: Self<object>) {
        stop = compareSlider(this.$root, { onChange: (value) => this.$dispatch('nx-change', value) });
      },
      destroy() {
        stop();
      },
    };
  });

  /* ---- Swipe actions ------------------------------------------------------------------- */
  Alpine.data('nxSwipeRow', (options: { fullSwipe?: number | false } = {}) => {
    let stop: Cleanup = () => {};
    return {
      open: null as string | null,

      init(this: Self<{ open: string | null }>) {
        stop = swipeActions(this.$root, {
          fullSwipe: options.fullSwipe,
          onOpen: (side) => (this.open = side),
          onClose: () => (this.open = null),
        });
      },

      destroy() {
        stop();
      },

      close(this: Self<object>) {
        this.$root.dispatchEvent(new CustomEvent('nx-reset'));
      },
    };
  });

  /* ---- Password strength: scored in the browser, the input keeps its wire:model ------ */
  Alpine.data('nxPassword', (options: { minLength?: number; userInputs?: string[]; scores?: string[] } = {}) => ({
    value: '',
    visible: false,

    init(this: Self<{ value: string }>) {
      const input = this.$refs.input as HTMLInputElement | undefined;
      if (input) this.value = input.value;
    },

    result(this: { value: string }): PasswordStrength {
      return passwordStrength(this.value, { minLength: options.minLength, userInputs: options.userInputs });
    },

    met(this: { result: () => PasswordStrength }, id: PasswordRuleId): boolean {
      return this.result().rules.some((rule) => rule.id === id && rule.met);
    },

    word(this: { value: string; result: () => PasswordStrength }): string {
      if (!this.value) return '';
      return options.scores?.[this.result().score] ?? '';
    },
  }));
}
