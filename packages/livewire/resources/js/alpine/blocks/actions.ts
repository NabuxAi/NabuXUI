/**
 * Alpine parts for the buttons, loaders & micro-interactions blocks. The Blade
 * components render the markup (and every native control that carries a
 * wire:model); these only add the behaviour: the marching border's speed, the
 * fill button's entry point, the transaction button's label and width, the
 * metal toggle, the interests rows and their Clear, and the label creator.
 *
 * The blob button needs no data of its own: it uses x-nx-spotlight.
 */
import {
  type Cleanup,
  dragScroll,
  emojiBurst,
  leave,
  marchingBorder,
  morphLabel,
  morphWidth,
  pointerEntry,
  prefersReducedMotion,
  shakeAndBurst,
} from '@nabuxai/ui-core';
import { afterMorph } from '../morph';
import type { AlpineLike, Magics } from '../types';

type Self<T> = T & Magics;

interface LabelItem {
  name: string;
  color: string;
}

interface LabelColor {
  value: string;
  label: string;
  color: string;
}

type Status = 'idle' | 'loading' | 'success' | 'error';

export function installActionsBlocks(Alpine: AlpineLike): void {
  /* ---- Border button: hover and focus speed the march without a jump -------------- */
  Alpine.data('nxBorderButton', () => ({
    stop: (() => {}) as Cleanup,

    init(this: Self<{ stop: Cleanup }>) {
      this.stop = marchingBorder(this.$root);
    },

    destroy(this: { stop: Cleanup }) {
      this.stop();
    },
  }));

  /* ---- Fill button: where the pointer came in and went out ------------------------ */
  Alpine.data('nxFillButton', () => ({
    stop: (() => {}) as Cleanup,

    init(this: Self<{ stop: Cleanup }>) {
      this.stop = pointerEntry(this.$root);
    },

    destroy(this: { stop: Cleanup }) {
      this.stop();
    },
  }));

  /* ---- Transaction button ----------------------------------------------------------
   * The state is read from the element itself — aria-busy (Livewire's
   * wire:loading) or data-status (the server's `status`, or set() by hand) — so
   * whoever changes it, the label swaps and the width springs to fit.
   */
  Alpine.data('nxTransactionButton', (labels: Partial<Record<Status, string>> = {}) => {
    // Kept out of Alpine's reactive state: a proxied native observer cannot be called.
    let observer: MutationObserver | null = null;

    return {
      current: 'idle' as Status,

      init(this: Self<{ current: Status; read: () => Status; sync: () => void }>) {
        this.current = this.read();
        observer = new MutationObserver(() => this.sync());
        observer.observe(this.$root, { attributes: true, attributeFilter: ['data-status', 'aria-busy'] });
      },

      destroy() {
        observer?.disconnect();
        observer = null;
      },

      read(this: Self<object>): Status {
        if (this.$root.getAttribute('aria-busy') === 'true') return 'loading';
        const status = this.$root.getAttribute('data-status');
        return status === 'loading' || status === 'success' || status === 'error' ? status : 'idle';
      },

      sync(this: Self<{ current: Status; read: () => Status }>) {
        const next = this.read();
        if (next === this.current) return;
        this.current = next;
        const text = labels[next] ?? labels.idle ?? '';
        this.$root.setAttribute('aria-disabled', next === 'loading' ? 'true' : 'false');
        morphWidth(this.$root, () => {
          morphLabel(this.$refs.label, text);
          this.$refs.live.textContent = text;
        });
      },

      /** Drive it by hand: set('loading'), then set('success') or set('error'), then set('idle'). */
      set(this: Self<object>, status: Status) {
        const root = this.$root;
        if (status === 'loading') root.setAttribute('aria-busy', 'true');
        else root.removeAttribute('aria-busy');
        if (status === 'idle') root.removeAttribute('data-status');
        else root.setAttribute('data-status', status);
      },

      /** Busy buttons ignore presses (a capture listener, ahead of wire:click). */
      press(this: { current: Status }, event: MouseEvent) {
        if (this.current === 'loading') {
          event.preventDefault();
          event.stopImmediatePropagation();
        }
      },
    };
  });

  /* ---- Metal button: a toggle that can follow a Livewire property ------------------ */
  Alpine.data('nxMetalButton', (pressed: boolean = false) => ({
    pressed,

    toggle(this: Self<{ pressed: boolean }>) {
      this.pressed = !this.pressed;
      this.$dispatch('nx-change', this.pressed);
    },
  }));

  /* ---- Interests picker: draggable rows, bursts, and a Clear that shakes them off --- */
  Alpine.data('nxInterests', () => ({
    count: 0,
    stops: [] as Cleanup[],
    cancel: (() => {}) as Cleanup,
    recount: () => {},

    init(this: Self<{ count: number; stops: Cleanup[]; recount: () => void; inputs: () => HTMLInputElement[] }>) {
      this.stops = Array.from(this.$root.querySelectorAll<HTMLElement>('.nx-interests-row')).map((row) => dragScroll(row));
      this.recount = () => {
        this.count = this.inputs().filter((input) => input.checked).length;
      };
      this.recount();
      // Livewire may tick boxes from the server.
      this.cancel = afterMorph(this.$root, this.recount);
    },

    destroy(this: { stops: Cleanup[]; cancel: Cleanup; recount: () => void }) {
      this.stops.forEach((stop) => stop());
      this.cancel();
    },

    inputs(this: Self<object>) {
      return Array.from(this.$root.querySelectorAll<HTMLInputElement>('.nx-interest-input'));
    },

    changed(this: Self<{ recount: () => void }>, event: Event) {
      const input = event.target as HTMLInputElement;
      if (!input.classList?.contains('nx-interest-input')) return;
      this.recount();
      const chip = input.closest<HTMLElement>('.nx-interest');
      if (input.checked && chip) emojiBurst(chip, chip.dataset.emoji ?? '');
    },

    clear(this: Self<{ cancel: Cleanup; inputs: () => HTMLInputElement[] }>) {
      const picked = this.inputs().filter((input) => input.checked);
      if (picked.length === 0) return;
      const chips = picked.map((input) => input.closest<HTMLElement>('.nx-interest')).filter((chip): chip is HTMLElement => chip !== null);
      this.cancel();
      this.cancel = shakeAndBurst(chips, {
        emoji: (chip) => chip.dataset.emoji,
        onBurst: () => {
          for (const input of picked) {
            input.checked = false;
            // wire:model (and x-model) listen for change on checkboxes.
            input.dispatchEvent(new Event('change', { bubbles: true }));
          }
        },
      });
    },
  }));

  /* ---- Label creator -----------------------------------------------------------------
   * x-data="nxLabelCreator(@entangle('labels'), colors, texts)" follows a Livewire
   * property; otherwise the list lives here and a hidden input can post it.
   */
  Alpine.data('nxLabelCreator', (labels: LabelItem[] | null = [], colors: LabelColor[] = [], texts: { remove?: string } = {}) => ({
    labels,
    colors,
    query: '',
    picked: '',
    creating: false,
    fresh: '',
    flash: '',

    init(this: Self<object>) {
      // The server drew the first chips; Alpine's own list takes their place.
      for (const chip of Array.from(this.$root.querySelectorAll('[data-ssr]'))) chip.remove();
    },

    list(this: { labels: LabelItem[] | null }): LabelItem[] {
      return Array.isArray(this.labels) ? this.labels : [];
    },

    typed(this: { query: string }) {
      return this.query.trim();
    },

    match(this: { list: () => LabelItem[]; typed: () => string }) {
      const typed = this.typed().toLocaleLowerCase();
      return this.list().find((item) => item.name.toLocaleLowerCase() === typed) ?? null;
    },

    open(this: { typed: () => string; match: () => LabelItem | null; creating: boolean }) {
      return (this.typed() !== '' && !this.match()) || this.creating;
    },

    color(this: { picked: string; colors: LabelColor[]; list: () => LabelItem[] }) {
      if (this.picked) return this.picked;
      const used = this.list();
      // The first colour no label wears yet; once all are taken, round they go.
      const free = this.colors.find((swatch) => !used.some((item) => item.color === swatch.value));
      return free?.value ?? (this.colors.length ? this.colors[used.length % this.colors.length]!.value : '');
    },

    colorOf(this: { colors: LabelColor[] }, value: string) {
      return this.colors.find((swatch) => swatch.value === value)?.color ?? value;
    },

    removeText(name: string) {
      return (texts.remove ?? 'Remove :name').replace(':name', name);
    },

    create(
      this: Self<{
        creating: boolean;
        labels: LabelItem[] | null;
        query: string;
        picked: string;
        fresh: string;
        flash: string;
        list: () => LabelItem[];
        typed: () => string;
        match: () => LabelItem | null;
        color: () => string;
      }>,
    ) {
      const name = this.typed();
      if (this.creating || !name) return;
      const existing = this.match();
      if (existing) {
        this.flash = existing.name;
        setTimeout(() => (this.flash = ''), 400);
        return;
      }
      const item: LabelItem = { name, color: this.color() };
      this.creating = true;
      // Long enough for the pixel loader to read as work, never a stall.
      setTimeout(
        () => {
          this.labels = [...this.list(), item];
          this.fresh = item.name;
          this.query = '';
          this.picked = '';
          this.creating = false;
          this.$dispatch('nx-label-created', item);
          this.$nextTick(() => (this.$refs.input as HTMLInputElement).focus());
          setTimeout(() => {
            if (this.fresh === item.name) this.fresh = '';
          }, 1200);
        },
        prefersReducedMotion() ? 150 : 650,
      );
    },

    remove(this: Self<{ labels: LabelItem[] | null; list: () => LabelItem[] }>, item: LabelItem, button: HTMLElement) {
      const chip = button.closest<HTMLElement>('.nx-label-chip');
      const drop = () => {
        this.labels = this.list().filter((other) => other.name !== item.name);
        (this.$refs.input as HTMLInputElement).focus();
      };
      if (!chip) return drop();
      chip.style.setProperty('--_w', `${chip.offsetWidth}px`);
      leave(chip, { attribute: 'data-leaving', value: '', timeout: 400 }).then(drop);
    },
  }));
}
