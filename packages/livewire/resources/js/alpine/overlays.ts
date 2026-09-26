/**
 * Toaster, dialog, menu, popover, tooltip and command palette — the same
 * top-layer mechanics as the React package, driven by Alpine state.
 */
import {
  type Toast,
  createTypeahead,
  focusableItems,
  hotkey,
  iconSvg,
  indicator,
  leave,
  place,
  roveFocus,
  stackToasts,
  swipe,
  toasts,
} from '@nabuxai/ui-core';
import type { AlpineLike, Magics } from './types';

type Self<T> = T & Magics;

const TONE_ICON = { neutral: 'bell', accent: 'sparkles', success: 'check-circle', warning: 'alert-triangle', danger: 'alert-circle', info: 'info' } as const;

const supportsPopover = () => 'popover' in HTMLElement.prototype;

function showTop(el: HTMLElement) {
  if (supportsPopover()) {
    try {
      el.showPopover();
    } catch {
      /* already open */
    }
  } else el.setAttribute('data-open', '');
}

function hideTop(el: HTMLElement) {
  el.removeAttribute('data-open');
  try {
    if (el.matches(':popover-open')) el.hidePopover();
  } catch {
    /* no popover support */
  }
}

/** Fold Arabic/Persian letter variants together so either spelling matches. */
const normalise = (text: string) =>
  text
    .toLocaleLowerCase()
    .replace(/[يى]/g, 'ی')
    .replace(/ك/g, 'ک')
    .replace(/[ً-ٰٟ‌]/g, '')
    .normalize('NFKC');

export function installOverlays(Alpine: AlpineLike): void {
  /* ---- Toaster ----------------------------------------------------------------- */
  Alpine.data('nxToaster', (initial: Array<Partial<Toast> & { title: string }> = []) => ({
    items: [] as Toast[],
    stop: () => {},

    init(this: Self<{ items: Toast[]; stop: () => void; sync: () => void }>) {
      this.stop = toasts.subscribe((next) => {
        this.items = [...next];
        this.$nextTick(() => this.sync());
      });
      this.items = [...toasts.getSnapshot()];
      for (const item of initial) toasts.show(item);
      showTop(this.$root);
      // Keep the stack in the top layer above dialogs opened later.
      document.addEventListener('nx-dialog-open', () => {
        hideTop(this.$root);
        showTop(this.$root);
      });
    },

    destroy(this: { stop: () => void }) {
      this.stop();
    },

    push(detail: Partial<Toast> & { title: string }) {
      toasts.show(detail);
    },

    dismiss(id: string) {
      toasts.dismiss(id);
    },

    pause() {
      toasts.pause();
    },

    resume() {
      toasts.resume();
    },

    icon(tone: keyof typeof TONE_ICON) {
      return iconSvg(TONE_ICON[tone] ?? 'bell');
    },

    /** Called by each toast's x-init: swipe to dismiss. */
    mount(el: HTMLElement, item: Toast) {
      swipe(el, { axis: 'x', threshold: 80, onSwipe: () => toasts.dismiss(item.id) });
    },

    sync(this: Self<{ items: Toast[] }>) {
      const list = this.$refs.list;
      if (!list) return;
      stackToasts(list);
      for (const el of Array.from(list.querySelectorAll<HTMLElement>('.nx-toast[data-state="closing"]:not([data-leaving])'))) {
        el.setAttribute('data-leaving', '');
        const id = el.dataset.id!;
        leave(el).then(() => toasts.remove(id));
      }
    },
  }));

  /* ---- Dialog ------------------------------------------------------------------ */
  // x-data="nxDialog(@entangle(...))" or nxDialog(false, 'invite') opened by $dispatch('nx-open', 'invite').
  Alpine.data('nxDialog', (open: boolean = false, name: string | null = null) => ({
    open,
    name,

    init(this: Self<{ open: boolean; sync: (v: boolean) => void }>) {
      const dialog = this.$refs.dialog as HTMLDialogElement;
      dialog.addEventListener('close', () => {
        this.open = false;
      });
      this.$watch('open', (value: boolean) => this.sync(value));
      this.sync(this.open);
    },

    sync(this: Self<object>, value: boolean) {
      const dialog = this.$refs.dialog as HTMLDialogElement;
      if (value && !dialog.open) {
        dialog.showModal();
        document.dispatchEvent(new CustomEvent('nx-dialog-open'));
      }
      if (!value && dialog.open) dialog.close();
    },

    openNamed(this: { open: boolean; name: string | null }, target: unknown) {
      if (target === this.name) this.open = true;
    },

    closeNamed(this: { open: boolean; name: string | null }, target: unknown) {
      if (target === this.name || target === undefined) this.open = false;
    },

    backdrop(this: { open: boolean }, event: MouseEvent) {
      const dialog = event.currentTarget as HTMLDialogElement;
      if (event.target !== dialog) return;
      const r = dialog.getBoundingClientRect();
      const inside = event.clientX >= r.left && event.clientX <= r.right && event.clientY >= r.top && event.clientY <= r.bottom;
      if (!inside) this.open = false;
    },
  }));

  /* ---- Menu (dropdown) ------------------------------------------------------------ */
  Alpine.data('nxMenu', (side: 'bottom' | 'top' = 'bottom', align: 'start' | 'center' | 'end' = 'start') => ({
    open: false,
    button: null as HTMLElement | null,
    typeahead: createTypeahead(),
    focusFirst: 'first' as 'first' | 'last',
    unplace: () => {},

    init(this: Self<{ open: boolean; button: HTMLElement | null; unplace: () => void; focusFirst: 'first' | 'last'; show: (w: 'first' | 'last') => void; hide: (f?: boolean) => void }>) {
      const menu = this.$refs.menu;
      this.button = this.$refs.trigger.querySelector<HTMLElement>('button, a, [tabindex]') ?? this.$refs.trigger;
      const button = this.button;
      button.setAttribute('aria-haspopup', 'menu');
      button.setAttribute('aria-expanded', 'false');
      button.setAttribute('aria-controls', menu.id);
      if (supportsPopover() && button.tagName === 'BUTTON') button.setAttribute('popovertarget', menu.id);
      else button.addEventListener('click', () => (this.open ? this.hide(false) : this.show('first')));

      button.addEventListener('keydown', (event: KeyboardEvent) => {
        if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
          event.preventDefault();
          this.show(event.key === 'ArrowUp' ? 'last' : 'first');
        }
      });

      menu.addEventListener('toggle', (event: Event) => {
        this.open = (event as ToggleEvent).newState === 'open';
      });

      this.$watch('open', (value: boolean) => {
        button.setAttribute('aria-expanded', String(value));
        this.unplace();
        if (!value) return;
        this.unplace = place(button, menu, { side, align, offset: 6 });
        const items = focusableItems(menu, '[role^="menuitem"]');
        (this.focusFirst === 'last' ? items[items.length - 1] : items[0])?.focus();
        this.focusFirst = 'first';
      });
    },

    show(this: Self<{ open: boolean; focusFirst: 'first' | 'last' }>, where: 'first' | 'last' = 'first') {
      this.focusFirst = where;
      const menu = this.$refs.menu;
      if (supportsPopover()) showTop(menu);
      else {
        menu.setAttribute('data-open', '');
        this.open = true;
      }
    },

    hide(this: Self<{ open: boolean; button: HTMLElement | null }>, returnFocus = true) {
      hideTop(this.$refs.menu);
      if (!supportsPopover()) this.open = false;
      if (returnFocus) this.button?.focus();
    },

    onKey(this: Self<{ typeahead: ReturnType<typeof createTypeahead>; hide: (f?: boolean) => void }>, event: KeyboardEvent) {
      const menu = this.$refs.menu;
      if (roveFocus(event, menu, '[role^="menuitem"]', { orientation: 'vertical' })) return;
      if (event.key === 'Tab') this.hide(false);
      if (event.key === 'Escape') {
        event.preventDefault();
        this.hide();
      }
      const items = focusableItems(menu, '[role^="menuitem"]');
      this.typeahead(event.key, items, items.indexOf(document.activeElement as HTMLElement))?.focus();
    },
  }));

  /* ---- Popover ------------------------------------------------------------------ */
  Alpine.data('nxPopover', (side: 'top' | 'bottom' | 'start' | 'end' = 'bottom', align: 'start' | 'center' | 'end' = 'center') => ({
    open: false,
    unplace: () => {},

    init(this: Self<{ open: boolean; unplace: () => void }>) {
      const panel = this.$refs.panel;
      const button = this.$refs.trigger.querySelector<HTMLElement>('button, a, [tabindex]') ?? this.$refs.trigger;
      button.setAttribute('aria-controls', panel.id);
      button.setAttribute('aria-expanded', 'false');
      if (supportsPopover() && button.tagName === 'BUTTON') button.setAttribute('popovertarget', panel.id);
      else button.addEventListener('click', () => (this.open ? hideTop(panel) : showTop(panel), (this.open = !this.open)));
      panel.addEventListener('toggle', (event: Event) => {
        this.open = (event as ToggleEvent).newState === 'open';
      });
      const sides = { top: 'top', bottom: 'bottom', start: 'inline-start', end: 'inline-end' } as const;
      this.$watch('open', (value: boolean) => {
        button.setAttribute('aria-expanded', String(value));
        this.unplace();
        if (value) this.unplace = place(button, panel, { side: sides[side], align, offset: 8 });
      });
    },
  }));

  /* ---- Tooltip -------------------------------------------------------------------- */
  Alpine.data('nxTooltip', (side: 'top' | 'bottom' | 'start' | 'end' = 'top', delay = 350) => ({
    timer: 0 as unknown as ReturnType<typeof setTimeout>,
    unplace: () => {},

    init(this: Self<{ timer: ReturnType<typeof setTimeout>; unplace: () => void; schedule: (open: boolean, wait: number) => void }>) {
      const tip = this.$refs.tip;
      const anchor = (this.$root.firstElementChild === tip ? null : this.$root.firstElementChild) as HTMLElement | null;
      if (!anchor) return;
      anchor.setAttribute('aria-describedby', [anchor.getAttribute('aria-describedby'), tip.id].filter(Boolean).join(' '));
      anchor.addEventListener('pointerenter', () => this.schedule(true, delay));
      anchor.addEventListener('pointerleave', () => this.schedule(false, 100));
      anchor.addEventListener('focus', () => this.schedule(true, 0));
      anchor.addEventListener('blur', () => this.schedule(false, 0));
      tip.addEventListener('pointerenter', () => clearTimeout(this.timer));
      tip.addEventListener('pointerleave', () => this.schedule(false, 100));
      document.addEventListener('keydown', (event) => event.key === 'Escape' && this.schedule(false, 0));
      const sides = { top: 'top', bottom: 'bottom', start: 'inline-start', end: 'inline-end' } as const;
      this.schedule = (open: boolean, wait: number) => {
        clearTimeout(this.timer);
        this.timer = setTimeout(() => {
          this.unplace();
          if (open) {
            showTop(tip);
            this.unplace = place(anchor, tip, { side: sides[side], align: 'center', offset: 8 });
          } else hideTop(tip);
        }, wait);
      };
    },

    schedule(_open: boolean, _wait: number) {},
  }));

  /* ---- Command palette -------------------------------------------------------------- */
  type Item = { id: string; label: string; icon?: string; hint?: string; shortcut?: string; keywords?: string[]; href?: string; event?: string; params?: unknown; disabled?: boolean };
  type Group = { label: string; items: Item[] };

  Alpine.data('nxCommand', (groups: Group[] = [], combo: string | null = 'mod+k') => ({
    open: false,
    query: '',
    active: 0,
    groups,
    ind: null as ReturnType<typeof indicator> | null,

    init(this: Self<{ open: boolean; query: string; active: number; ind: ReturnType<typeof indicator> | null; follow: () => void }>) {
      const dialog = this.$refs.dialog as HTMLDialogElement;
      if (combo) hotkey(combo, () => (this.open = !this.open));
      dialog.addEventListener('close', () => (this.open = false));
      this.$watch('open', (value: boolean) => {
        if (value && !dialog.open) {
          this.query = '';
          this.active = 0;
          dialog.showModal();
          document.dispatchEvent(new CustomEvent('nx-dialog-open'));
          requestAnimationFrame(() => this.$refs.input.focus());
        }
        if (!value && dialog.open) dialog.close();
      });
      this.$watch('query', () => (this.active = 0));
      this.$watch('active', () => this.$nextTick(() => this.follow()));
      this.$watch('query', () => this.$nextTick(() => this.follow()));
    },

    get filtered(): Group[] {
      const q = normalise((this as unknown as { query: string }).query.trim());
      return (this as unknown as { groups: Group[] }).groups
        .map((group) => ({ ...group, items: group.items.filter((item) => !q || normalise([item.label, item.hint, ...(item.keywords ?? [])].join(' ')).includes(q)) }))
        .filter((group) => group.items.length > 0);
    },

    get flat(): Item[] {
      return (this as unknown as { filtered: Group[] }).filtered.flatMap((group) => group.items);
    },

    indexOf(this: { flat: Item[] }, item: Item) {
      return this.flat.indexOf(item);
    },

    follow(this: Self<{ ind: ReturnType<typeof indicator> | null; active: number }>) {
      const list = this.$refs.list;
      this.ind ??= indicator(list);
      const el = list.querySelector<HTMLElement>(`[data-index="${this.active}"]`);
      this.ind.update(el);
      el?.scrollIntoView({ block: 'nearest' });
    },

    move(this: { active: number; flat: Item[] }, step: number) {
      const count = this.flat.length;
      if (count) this.active = (this.active + step + count) % count;
    },

    choose(this: { open: boolean; flat: Item[]; active: number }, item?: Item) {
      const target = item ?? this.flat[this.active];
      if (!target || target.disabled) return;
      this.open = false;
      const livewire = (window as unknown as { Livewire?: { navigate?: (url: string) => void; dispatch?: (name: string, params?: unknown) => void } }).Livewire;
      if (target.href) {
        if (livewire?.navigate && target.href.startsWith('/')) livewire.navigate(target.href);
        else window.location.assign(target.href);
      }
      if (target.event) {
        if (livewire?.dispatch) livewire.dispatch(target.event, target.params);
        else window.dispatchEvent(new CustomEvent(target.event, { detail: target.params }));
      }
    },

    icon(name?: string) {
      return name ? iconSvg(name as never) : '';
    },
  }));
}
