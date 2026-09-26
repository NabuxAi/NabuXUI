/**
 * Tabs, segmented control, nav pill, pagination, mega menu and the mobile
 * drill-down — the moving highlight comes from the core indicator.
 */
import { indicator, roveFocus } from '@nabuxai/ui-core';
import type { AlpineLike, Magics } from './types';

type Self<T> = T & Magics;
type Ind = ReturnType<typeof indicator>;

export function installNavigation(Alpine: AlpineLike): void {
  /* ---- Tabs -------------------------------------------------------------------- */
  // Works with wire:model too: x-data="nxTabs(@entangle(...))".
  Alpine.data('nxTabs', (value: string = '') => ({
    value,
    ind: null as Ind | null,

    init(this: Self<{ value: string; ind: Ind | null; move: () => void }>) {
      const list = this.$refs.list;
      this.ind = indicator(list);
      if (!this.value) this.value = list.querySelector<HTMLElement>('[role="tab"]')?.dataset.value ?? '';
      this.move();
      this.$watch('value', () => this.$nextTick(() => this.move()));
    },

    destroy(this: { ind: Ind | null }) {
      this.ind?.destroy();
    },

    move(this: Self<{ value: string; ind: Ind | null }>) {
      this.ind?.update(this.$refs.list.querySelector(`[data-value="${CSS.escape(this.value)}"]`));
    },

    select(this: { value: string }, next: string) {
      this.value = next;
    },

    onKey(this: Self<{ value: string }>, event: KeyboardEvent) {
      const next = roveFocus(event, this.$refs.list, '[role="tab"]');
      if (next?.dataset.value) this.value = next.dataset.value;
    },
  }));

  /* ---- Segmented control: native radios; the thumb follows the checked one ----------- */
  Alpine.data('nxSegmented', () => ({
    ind: null as Ind | null,

    init(this: Self<{ ind: Ind | null; move: () => void }>) {
      this.ind = indicator(this.$root);
      this.move();
      this.$root.addEventListener('change', () => this.move());
      // Livewire may set the checked radio from the server.
      document.addEventListener('livewire:morph.updated', () => this.move());
    },

    destroy(this: { ind: Ind | null }) {
      this.ind?.destroy();
    },

    move(this: Self<{ ind: Ind | null }>) {
      this.ind?.update(this.$root.querySelector('.nx-segment:has(:checked)'));
    },
  }));

  /* ---- Nav pill, pagination: highlight the current link, follow the pointer -------- */
  Alpine.data('nxNavIndicator', (follow: boolean = true) => ({
    ind: null as Ind | null,

    init(this: Self<{ ind: Ind | null; home: () => void }>) {
      this.ind = indicator(this.$root);
      this.home();
      if (!follow) return;
      const onOver = (event: Event) => {
        const link = (event.target as Element).closest('a, button');
        if (link && this.$root.contains(link)) this.ind?.update(link);
      };
      this.$root.addEventListener('pointerover', onOver);
      this.$root.addEventListener('focusin', onOver);
      this.$root.addEventListener('pointerleave', () => this.home());
      this.$root.addEventListener('focusout', () => this.home());
    },

    destroy(this: { ind: Ind | null }) {
      this.ind?.destroy();
    },

    home(this: Self<{ ind: Ind | null }>) {
      this.ind?.update(this.$root.querySelector('[aria-current="page"]'));
    },
  }));

  /* ---- Mega menu ----------------------------------------------------------------------- */
  Alpine.data('nxMega', () => ({
    active: null as number | null,
    leaving: null as number | null,
    motion: null as string | null,
    leavingMotion: null as string | null,
    fresh: true,
    timer: 0 as unknown as ReturnType<typeof setTimeout>,
    ind: null as Ind | null,

    init(this: Self<{ active: number | null; ind: Ind | null; home: () => void; place: () => void }>) {
      this.ind = indicator(this.$root);
      this.home();
      this.$watch('active', () =>
        this.$nextTick(() => {
          this.place();
          const trigger = this.active === null ? null : this.$root.querySelector(`[data-trigger="${this.active}"]`);
          if (trigger) this.ind?.update(trigger);
          else this.home();
        }),
      );
    },

    home(this: Self<{ ind: Ind | null }>) {
      this.ind?.update(this.$root.querySelector('.nx-mega-list [aria-current="page"]'));
    },

    open(this: { active: number | null; leaving: number | null; motion: string | null; leavingMotion: string | null; fresh: boolean; timer: ReturnType<typeof setTimeout> }, index: number) {
      clearTimeout(this.timer);
      if (index === this.active) return;
      if (this.active === null) {
        this.fresh = true;
        this.motion = null;
        this.leaving = null;
      } else {
        const forward = index > this.active;
        this.fresh = false;
        this.motion = forward ? 'from-end' : 'from-start';
        this.leaving = this.active;
        this.leavingMotion = forward ? 'to-start' : 'to-end';
      }
      this.active = index;
    },

    close(this: { active: number | null; leaving: number | null; timer: ReturnType<typeof setTimeout> }, delay = 0) {
      clearTimeout(this.timer);
      this.timer = setTimeout(() => {
        this.active = null;
        this.leaving = null;
      }, delay);
    },

    toggle(this: { active: number | null; open: (i: number) => void; close: (d?: number) => void }, index: number) {
      if (this.active === index) this.close();
      else this.open(index);
    },

    motionFor(this: { active: number | null; leaving: number | null; motion: string | null; leavingMotion: string | null }, index: number) {
      if (index === this.active) return this.motion;
      if (index === this.leaving) return this.leavingMotion;
      return null;
    },

    keyOpen(this: Self<{ open: (i: number) => void }>, event: KeyboardEvent, index: number) {
      event.preventDefault();
      this.open(index);
      requestAnimationFrame(() => this.$root.querySelector<HTMLElement>(`[data-panel="${index}"] a`)?.focus());
    },

    escape(this: Self<{ active: number | null; close: (d?: number) => void }>) {
      if (this.active === null) return;
      const index = this.active;
      this.close();
      this.$root.querySelector<HTMLElement>(`[data-trigger="${index}"]`)?.focus();
    },

    place(this: Self<{ active: number | null }>) {
      const view = this.$refs.viewport;
      if (this.active === null || !view) return;
      const panel = view.querySelector<HTMLElement>(`[data-panel="${this.active}"]`);
      const trigger = this.$root.querySelector<HTMLElement>(`[data-trigger="${this.active}"]`);
      if (!panel || !trigger) return;
      const w = panel.offsetWidth;
      const h = panel.offsetHeight;
      const nav = this.$root.getBoundingClientRect();
      const t = trigger.getBoundingClientRect();
      const ideal = t.left + t.width / 2 - nav.left - w / 2;
      const min = -nav.left + 12;
      const max = document.documentElement.clientWidth - nav.left - w - 12;
      view.style.setProperty('--nx-mega-w', `${w}px`);
      view.style.setProperty('--nx-mega-h', `${h}px`);
      view.style.setProperty('--nx-mega-x', `${Math.round(Math.min(Math.max(ideal, min), Math.max(min, max)))}px`);
    },
  }));

  /* ---- Drill-down (mobile menu) ---------------------------------------------------------- */
  Alpine.data('nxDrilldown', () => ({
    sub: null as string | null,
    opener: null as HTMLElement | null,

    init(this: Self<{ sub: string | null; opener: HTMLElement | null; sync: () => void }>) {
      this.$watch('sub', () => this.$nextTick(() => this.sync()));
      this.sync();
    },

    enter(this: { sub: string | null; opener: HTMLElement | null }, key: string, event: Event) {
      this.opener = event.currentTarget as HTMLElement;
      this.sub = key;
    },

    back(this: { sub: string | null }) {
      this.sub = null;
    },

    sync(this: Self<{ sub: string | null; opener: HTMLElement | null }>) {
      const root = this.$refs.root;
      const panels = this.$refs.sub;
      root.toggleAttribute('inert', this.sub !== null);
      panels.toggleAttribute('inert', this.sub === null);
      if (this.sub !== null) panels.querySelector<HTMLElement>(`[data-sub="${this.sub}"] .nx-drilldown-back`)?.focus();
      else this.opener?.focus();
    },
  }));
}
