/**
 * Alpine parts for the menus, navigation & morphing panels blocks. The Blade
 * components render the markup; these drive it with the same core behaviours
 * as the React package (morphShell, lightDismiss, morphTabs, flipStack,
 * levelMeter), so both builds move identically.
 */
import {
  type Cleanup,
  type MenuTreeItem,
  type MorphTabsController,
  type PlaceOptions,
  burst,
  direction,
  downsampleLevels,
  flipStack,
  focusableItems,
  foldSearchText,
  formatClock,
  iconSvg,
  levelMeter,
  lightDismiss,
  localeDigits,
  menuPath,
  morphShell,
  morphTabs,
  place,
  roveFocus,
  searchMenuTree,
  ticketTotal,
  toggleSelection,
  voiceLevel,
} from '@nabuxai/ui-core';
import { renderNumber } from '../number';
import type { AlpineLike, Magics } from '../types';

type Self<T> = T & Magics;

/** The bits of Livewire's $wire these parts use (absent outside Livewire). */
interface Wire {
  $watch?: (name: string, callback: (value: unknown) => void) => void;
  $get?: (name: string) => unknown;
  $call?: (method: string, ...params: unknown[]) => unknown;
}

const wireOf = (self: object): Wire | undefined => {
  try {
    const wire = (self as { $wire?: Wire }).$wire;
    return wire && typeof wire === 'object' ? wire : undefined;
  } catch {
    return undefined;
  }
};

const supportsPopover = () => 'popover' in HTMLElement.prototype;
const toList = (value: unknown): string[] => (Array.isArray(value) ? value.map(String) : value == null || value === '' ? [] : [String(value)]);

/* ---- A native popover wired to a trigger ------------------------------------------- */

interface PopoverPart {
  show(): void;
  hide(): void;
  isOpen(): boolean;
  destroy: Cleanup;
}

function popoverPart(trigger: HTMLElement, panel: HTMLElement, placement: PlaceOptions, onChange: (open: boolean) => void): PopoverPart {
  let open = false;
  let unplace: Cleanup = () => {};
  let undismiss: Cleanup = () => {};

  const sync = (next: boolean) => {
    if (next === open) return;
    open = next;
    trigger.setAttribute('aria-expanded', String(next));
    unplace();
    undismiss();
    unplace = () => {};
    undismiss = () => {};
    if (next) {
      unplace = place(trigger, panel, placement);
      if (!supportsPopover()) undismiss = lightDismiss(panel, () => hide(), { inside: [trigger] });
    }
    onChange(next);
  };

  const show = () => {
    if (!supportsPopover()) {
      panel.setAttribute('data-open', '');
      sync(true);
      return;
    }
    try {
      panel.showPopover();
    } catch {
      /* already open */
    }
  };

  const hide = () => {
    if (!supportsPopover()) {
      panel.removeAttribute('data-open');
      sync(false);
      return;
    }
    try {
      if (panel.matches(':popover-open')) panel.hidePopover();
    } catch {
      /* already closed */
    }
  };

  const onClick = () => (open ? hide() : show());
  const onToggle = (event: Event) => sync((event as ToggleEvent).newState === 'open');

  trigger.setAttribute('aria-controls', panel.id);
  trigger.setAttribute('aria-expanded', 'false');
  if (supportsPopover() && trigger.tagName === 'BUTTON') trigger.setAttribute('popovertarget', panel.id);
  else trigger.addEventListener('click', onClick);
  panel.addEventListener('toggle', onToggle);

  return {
    show,
    hide,
    isOpen: () => open,
    destroy() {
      unplace();
      undismiss();
      trigger.removeEventListener('click', onClick);
      panel.removeEventListener('toggle', onToggle);
    },
  };
}

/** Digits of a fixed-shape text ("03:07") roll to their new values; the hidden copy is read aloud. */
function rollText(el: HTMLElement | undefined, text: string, locale?: string): void {
  if (!el) return;
  const digits = localeDigits(locale);
  const label = el.querySelector('.nx-visually-hidden');
  if (label) label.textContent = Array.from(text, (c) => (c >= '0' && c <= '9' ? digits[Number(c)] : c)).join('');
  const columns = Array.from(el.querySelectorAll<HTMLElement>('.nx-digit'));
  const values = Array.from(text).filter((c) => c >= '0' && c <= '9');
  // Keep the last columns in step with the last digits (a longer text only fills from the right).
  const offset = values.length - columns.length;
  columns.forEach((column, i) => column.style.setProperty('--d', values[i + offset] ?? '0'));
}

/** Restart a one-shot keyframe by toggling its attribute. */
function replay(el: Element | undefined | null, attribute: string): void {
  if (!el) return;
  el.removeAttribute(attribute);
  void (el as HTMLElement).offsetWidth;
  el.setAttribute(attribute, '');
}

/* ---- Types of the JSON the Blade components hand over ------------------------------ */

interface StackItem extends MenuTreeItem {
  id: string;
  label: string;
  icon?: string;
  shortcut?: string;
  description?: string;
  tone?: string;
  disabled?: boolean;
  href?: string;
  event?: string;
  params?: unknown;
  children?: StackItem[];
}

interface MemberJson {
  id: string;
  name: string;
  email?: string;
  avatar?: string;
}

export function installMenusBlocks(Alpine: AlpineLike): void {
  /* ---- Fold menu ------------------------------------------------------------------- */
  Alpine.data('nxFoldMenu', (align: 'start' | 'end' = 'start') => ({
    open: false,
    part: null as PopoverPart | null,
    focusFirst: false,

    init(this: Self<{ open: boolean; part: PopoverPart | null; focusFirst: boolean }>) {
      const panel = this.$refs.panel;
      this.part = popoverPart(this.$refs.trigger, panel, { side: 'bottom', align, offset: 10 }, (open) => {
        this.open = open;
        if (open && this.focusFirst) {
          this.focusFirst = false;
          requestAnimationFrame(() => panel.querySelector<HTMLElement>('.nx-fold-menu-link')?.focus({ preventScroll: true }));
        }
      });
    },

    destroy(this: { part: PopoverPart | null }) {
      this.part?.destroy();
    },

    keyTrigger(this: { open: boolean; focusFirst: boolean; part: PopoverPart | null }, event: KeyboardEvent) {
      if (event.key !== 'ArrowDown' || this.open) return;
      event.preventDefault();
      this.focusFirst = true;
      this.part?.show();
    },

    keyPanel(this: Self<object>, event: KeyboardEvent) {
      roveFocus(event, this.$refs.panel, '.nx-fold-menu-link', { orientation: 'vertical' });
    },

    close(this: Self<{ part: PopoverPart | null }>, focusTrigger = false) {
      this.part?.hide();
      if (focusTrigger) this.$refs.trigger.focus();
    },
  }));

  /* ---- Dock with expanding panels -------------------------------------------------------- */
  // x-data="nxDockPanels(@js($value))" or nxDockPanels(@entangle('prop')).
  Alpine.data('nxDockPanels', (value: string | null = null) => ({
    active: (value || null) as string | null,
    stop: 0,
    unmorph: (() => {}) as Cleanup,
    undismiss: (() => {}) as Cleanup,

    init(this: Self<{ active: string | null; unmorph: Cleanup; sync: () => void }>) {
      this.unmorph = morphShell(this.$refs.shell, { content: this.$refs.measure });
      this.$watch('active', () => this.sync());
      this.sync();
    },

    destroy(this: { unmorph: Cleanup; undismiss: Cleanup }) {
      this.unmorph();
      this.undismiss();
    },

    sync(this: Self<{ active: string | null; undismiss: Cleanup }>) {
      this.undismiss();
      this.undismiss = () => {};
      const current = this.active;
      if (!current) return;
      this.undismiss = lightDismiss(this.$root, (reason) => {
        this.active = null;
        if (reason === 'escape') this.$root.querySelector<HTMLElement>(`[data-item="${CSS.escape(current)}"]`)?.focus();
      });
    },

    toggle(this: { active: string | null }, id: string) {
      this.active = this.active === id ? null : id;
    },

    tabStop(this: { active: string | null; stop: number }, index: number, id: string) {
      return (this.active ? this.active === id : this.stop === index) ? 0 : -1;
    },

    onKey(this: Self<{ stop: number }>, event: KeyboardEvent) {
      const next = roveFocus(event, this.$refs.bar, '.nx-dock-panels-item');
      if (next) this.stop = Number(next.dataset.index ?? 0);
    },
  }));

  /* ---- Morphing filter menu ---------------------------------------------------------------- */
  // Checkboxes carry wire:model themselves; the count follows them (and $wire when bound).
  Alpine.data('nxMorphMenu', (model: string | null = null, side: 'bottom' | 'top' = 'bottom', lang: string | null = null) => ({
    open: false,
    selected: [] as string[],
    focusFirst: false,
    cleanups: [] as Cleanup[],
    undismiss: (() => {}) as Cleanup,

    init(this: Self<{ open: boolean; selected: string[]; focusFirst: boolean; cleanups: Cleanup[]; undismiss: Cleanup; sync: () => void; focusOption: (w: 'first' | 'last') => void }>) {
      const root = this.$root;
      // The root keeps the trigger's footprint; the shell follows everything inside it.
      this.cleanups.push(morphShell(root, { content: this.$refs.trigger }), morphShell(this.$refs.shell, { content: this.$refs.measure }));
      this.$nextTick(() => this.sync());
      root.addEventListener('change', (event) => {
        if ((event.target as Element).matches('.nx-morph-menu-input')) this.sync();
      });
      const wire = wireOf(this);
      if (model && typeof wire?.$watch === 'function') wire.$watch(model, () => this.$nextTick(() => this.sync()));

      this.$watch('selected', (value: string[]) => renderNumber(this.$refs.count, value.length, lang || undefined));
      this.$watch('open', (open: boolean) => {
        this.undismiss();
        this.undismiss = () => {};
        if (!open) return;
        if (this.focusFirst) {
          this.focusFirst = false;
          requestAnimationFrame(() => this.focusOption(side === 'top' ? 'last' : 'first'));
        }
        this.undismiss = lightDismiss(
          root,
          (reason) => {
            this.open = false;
            if (reason === 'escape') this.$refs.trigger.focus();
          },
          { focusOut: true },
        );
      });
    },

    destroy(this: { cleanups: Cleanup[]; undismiss: Cleanup }) {
      this.cleanups.forEach((stop) => stop());
      this.undismiss();
    },

    get count(): number {
      return (this as unknown as { selected: string[] }).selected.length;
    },

    sync(this: Self<{ selected: string[] }>) {
      this.selected = Array.from(this.$root.querySelectorAll<HTMLInputElement>('.nx-morph-menu-input:checked')).map((input) => input.value);
    },

    focusOption(this: Self<object>, where: 'first' | 'last') {
      const inputs = focusableItems(this.$refs.list, '.nx-morph-menu-input');
      (where === 'first' ? inputs[0] : inputs[inputs.length - 1])?.focus({ preventScroll: true });
    },

    keyTrigger(this: { open: boolean; focusFirst: boolean; focusOption: (w: 'first' | 'last') => void }, event: KeyboardEvent) {
      if (event.key !== (side === 'top' ? 'ArrowUp' : 'ArrowDown')) return;
      event.preventDefault();
      if (this.open) this.focusOption(side === 'top' ? 'last' : 'first');
      else {
        this.focusFirst = true;
        this.open = true;
      }
    },

    keyList(this: Self<object>, event: KeyboardEvent) {
      const target = event.target as HTMLElement;
      const inputs = focusableItems(this.$refs.list, '.nx-morph-menu-input');
      const index = inputs.indexOf(target);
      if (event.key === (side === 'top' ? 'ArrowDown' : 'ArrowUp') && index === (side === 'top' ? inputs.length - 1 : 0)) {
        event.preventDefault();
        this.$refs.trigger.focus();
        return;
      }
      if (event.key === 'Enter' && target.matches('.nx-morph-menu-input')) {
        event.preventDefault();
        target.click();
        return;
      }
      roveFocus(event, this.$refs.list, '.nx-morph-menu-input', { orientation: 'vertical', loop: false });
    },

    clear(this: Self<object>) {
      for (const input of Array.from(this.$root.querySelectorAll<HTMLInputElement>('.nx-morph-menu-input:checked'))) {
        input.checked = false;
        input.dispatchEvent(new Event('change', { bubbles: true }));
      }
    },
  }));

  /* ---- Stacked accordion ------------------------------------------------------------------- */
  Alpine.data('nxStackedAccordion', (value: string[] | string | null = [], type: 'single' | 'multiple' = 'single', collapsible = true) => ({
    open: toList(value),

    toggle(this: { open: string[] }, id: string) {
      if (type === 'multiple') this.open = toggleSelection(toList(this.open), id);
      else if (toList(this.open).includes(id)) {
        if (collapsible) this.open = [];
      } else this.open = [id];
    },

    isOpen(this: { open: string[] }, id: string) {
      return toList(this.open).includes(id);
    },

    onKey(this: Self<object>, event: KeyboardEvent) {
      if ((event.target as Element).matches('.nx-stacked-accordion-trigger')) roveFocus(event, this.$root, '.nx-stacked-accordion-trigger', { orientation: 'vertical', loop: false });
    },
  }));

  /* ---- Stack menu ---------------------------------------------------------------------------- */
  type LevelView = { key: string; title: string; items: StackItem[]; depth: number; state: 'active' | 'behind' | 'leaving' };

  Alpine.data('nxStackMenu', (items: StackItem[] = [], title = 'Menu', side: 'bottom' | 'top' = 'bottom', align: 'start' | 'center' | 'end' = 'start') => ({
    items,
    title,
    path: [] as string[],
    leaving: null as string[] | null,
    query: '',
    part: null as PopoverPart | null,
    button: null as HTMLElement | null,
    unmorph: (() => {}) as Cleanup,
    timer: 0 as unknown as ReturnType<typeof setTimeout>,

    init(this: Self<{ path: string[]; leaving: string[] | null; query: string; part: PopoverPart | null; button: HTMLElement | null; unmorph: Cleanup; timer: ReturnType<typeof setTimeout> }>) {
      const panel = this.$refs.panel;
      this.button = this.$refs.trigger.querySelector<HTMLElement>('button, a, [tabindex]') ?? this.$refs.trigger;
      this.button.setAttribute('aria-haspopup', 'dialog');
      this.unmorph = morphShell(this.$refs.viewport, { content: this.$refs.measure, axis: 'block' });
      this.part = popoverPart(this.button, panel, { side, align, offset: 8 }, (open) => {
        if (!open) return;
        // Every opening starts at the top level, searching from the field.
        this.path = [];
        this.leaving = null;
        this.query = '';
        requestAnimationFrame(() => this.$refs.input.focus({ preventScroll: true }));
      });
      this.$watch('leaving', (value: string[] | null) => {
        clearTimeout(this.timer);
        if (value) this.timer = setTimeout(() => (this.leaving = null), 450);
      });
    },

    destroy(this: { part: PopoverPart | null; unmorph: Cleanup }) {
      this.part?.destroy();
      this.unmorph();
    },

    get searching(): boolean {
      return (this as unknown as { query: string }).query.trim() !== '';
    },

    get results(): Array<{ item: StackItem; trail: StackItem[] }> {
      const self = this as unknown as { items: StackItem[]; query: string };
      return searchMenuTree(self.items, self.query);
    },

    get levels(): LevelView[] {
      const self = this as unknown as { items: StackItem[]; title: string; path: string[]; leaving: string[] | null };
      const chain = menuPath(self.items, self.path);
      const views: LevelView[] = [
        { key: '__root', title: self.title, items: self.items, depth: 0, state: self.path.length ? 'behind' : 'active' },
        ...chain.map((node, i): LevelView => ({ key: node.id, title: node.label, items: node.children ?? [], depth: i + 1, state: i === chain.length - 1 ? 'active' : 'behind' })),
      ];
      if (self.leaving) {
        const gone = menuPath(self.items, self.leaving);
        const node = gone[gone.length - 1];
        if (node && !views.some((view) => view.key === node.id)) views.push({ key: node.id, title: node.label, items: node.children ?? [], depth: self.leaving.length, state: 'leaving' });
      }
      return views;
    },

    icon(name?: string) {
      return name ? iconSvg(name as never) : '';
    },

    focusIn(this: Self<object>, target: 'first' | string) {
      this.$nextTick(() => {
        const view = this.$refs.panel.querySelector<HTMLElement>('[data-view]');
        if (!view) return;
        const el = target === 'first' ? focusableItems(view, '[role="menuitem"]')[0] : view.querySelector<HTMLElement>(`[data-item="${CSS.escape(target)}"]`);
        el?.focus({ preventScroll: true });
      });
    },

    push(this: { path: string[]; leaving: string[] | null; focusIn: (t: string) => void }, next: string[]) {
      if (this.leaving && this.leaving.join('/') === next.join('/')) this.leaving = null;
      this.path = next;
      this.focusIn('first');
    },

    pop(this: { path: string[]; leaving: string[] | null; focusIn: (t: string) => void }) {
      if (!this.path.length) return;
      const from = this.path;
      this.leaving = from;
      this.path = from.slice(0, -1);
      this.focusIn(from[from.length - 1]!);
    },

    ended(this: { leaving: string[] | null }, event: TransitionEvent, level: LevelView) {
      if (level.state === 'leaving' && event.propertyName === 'translate') this.leaving = null;
    },

    select(this: Self<{ items: StackItem[]; path: string[]; query: string; part: PopoverPart | null; button: HTMLElement | null; push: (p: string[]) => void }>, item: StackItem, trail?: StackItem[]) {
      if (item.disabled) return;
      if (item.children?.length) {
        const chain = trail ?? menuPath(this.items, this.path);
        this.query = '';
        this.push([...chain.map((node) => node.id), item.id]);
        return;
      }
      this.$dispatch('nx-select', { id: item.id, label: item.label, params: item.params });
      this.part?.hide();
      this.button?.focus();
      const livewire = (window as unknown as { Livewire?: { navigate?: (url: string) => void; dispatch?: (name: string, params?: unknown) => void } }).Livewire;
      if (item.href) {
        if (livewire?.navigate && item.href.startsWith('/')) livewire.navigate(item.href);
        else window.location.assign(item.href);
      }
      if (item.event) {
        if (livewire?.dispatch) livewire.dispatch(item.event, item.params);
        else window.dispatchEvent(new CustomEvent(item.event, { detail: item.params }));
      }
    },

    onKey(this: Self<{ path: string[]; query: string; searching: boolean; results: Array<{ item: StackItem; trail: StackItem[] }>; pop: () => void; select: (i: StackItem, t?: StackItem[]) => void }>, event: KeyboardEvent) {
      const panel = this.$refs.panel;
      const input = this.$refs.input;
      const target = event.target as HTMLElement;
      const view = panel.querySelector<HTMLElement>('[data-view]');
      const rtl = direction(panel) === -1;

      if (event.key === 'Escape') {
        if (this.searching || this.path.length) {
          event.preventDefault();
          event.stopPropagation();
          if (this.searching) {
            this.query = '';
            input.focus();
          } else this.pop();
        }
        return;
      }

      if (target === input) {
        if (event.key === 'ArrowDown' && view) {
          event.preventDefault();
          focusableItems(view, '[role="menuitem"]')[0]?.focus();
        } else if (event.key === 'Enter' && this.searching && this.results[0]) {
          event.preventDefault();
          this.select(this.results[0].item, this.results[0].trail);
        }
        return;
      }

      if (!view) return;
      if (event.key === (rtl ? 'ArrowRight' : 'ArrowLeft') && this.path.length && !this.searching) {
        event.preventDefault();
        this.pop();
        return;
      }
      if (event.key === (rtl ? 'ArrowLeft' : 'ArrowRight') && target.getAttribute('aria-haspopup') === 'menu') {
        event.preventDefault();
        target.click();
        return;
      }
      if (event.key === 'ArrowUp' && focusableItems(view, '[role="menuitem"]')[0] === target) {
        event.preventDefault();
        input.focus();
        return;
      }
      roveFocus(event, view, '[role="menuitem"]', { orientation: 'vertical', loop: false });
    },
  }));

  /* ---- Morph tabs ------------------------------------------------------------------------------- */
  // Works with wire:model too: x-data="nxMorphTabs(@entangle(...))".
  Alpine.data('nxMorphTabs', (value: string = '') => ({
    value,
    ctrl: null as MorphTabsController | null,

    init(this: Self<{ value: string; ctrl: MorphTabsController | null; move: () => void }>) {
      const rail = this.$refs.rail;
      this.ctrl = morphTabs(rail);
      if (!this.value) this.value = rail.querySelector<HTMLElement>('[role="tab"]')?.dataset.value ?? '';
      this.move();
      this.$watch('value', () => this.$nextTick(() => this.move()));
    },

    destroy(this: { ctrl: MorphTabsController | null }) {
      this.ctrl?.destroy();
    },

    move(this: Self<{ value: string; ctrl: MorphTabsController | null }>) {
      this.ctrl?.select(this.$refs.rail.querySelector<HTMLElement>(`[data-value="${CSS.escape(String(this.value))}"]`));
    },

    select(this: { value: string }, next: string) {
      this.value = next;
    },

    onKey(this: Self<{ value: string }>, event: KeyboardEvent) {
      const next = roveFocus(event, this.$refs.list, '[role="tab"]');
      // Automatic activation: moving focus selects the tab.
      if (next?.dataset.value) this.value = next.dataset.value;
    },
  }));

  /* ---- Audio room --------------------------------------------------------------------------------- */
  // The listener count and who is speaking come from the server's render (Livewire morphs them in).
  Alpine.data('nxAudioRoom', (open = false, muted = false, raised = false) => ({
    open,
    muted,
    raised,
    cleanups: [] as Cleanup[],
    undismiss: (() => {}) as Cleanup,

    init(this: Self<{ open: boolean; cleanups: Cleanup[]; undismiss: Cleanup }>) {
      this.cleanups.push(morphShell(this.$root, { content: this.$refs.pill }), morphShell(this.$refs.shell, { content: this.$refs.measure }));
      this.$watch('open', (value: boolean) => {
        this.undismiss();
        this.undismiss = () => {};
        if (!value) return;
        requestAnimationFrame(() => this.$refs.close?.focus({ preventScroll: true }));
        this.undismiss = lightDismiss(this.$root, (reason) => {
          this.open = false;
          if (reason !== 'outside') this.$refs.pill.focus();
        });
      });
    },

    destroy(this: { cleanups: Cleanup[]; undismiss: Cleanup }) {
      this.cleanups.forEach((stop) => stop());
      this.undismiss();
    },

    collapse(this: Self<{ open: boolean }>) {
      this.open = false;
      this.$refs.pill.focus();
    },

    toggleMute(this: Self<{ muted: boolean }>) {
      this.muted = !this.muted;
      this.$dispatch('nx-room-mute', { muted: this.muted });
    },

    toggleHand(this: Self<{ raised: boolean }>) {
      this.raised = !this.raised;
      this.$dispatch('nx-room-hand', { raised: this.raised });
    },

    leave(this: Self<object>) {
      this.$dispatch('nx-room-leave');
    },
  }));

  /* ---- Activity dropdown --------------------------------------------------------------------------- */
  // Items are server-rendered with data-server-unread; "read" is kept here until the server agrees.
  Alpine.data('nxActivityDropdown', (lang: string | null = null) => ({
    open: false,
    read: [] as string[],
    unread: 0,
    part: null as PopoverPart | null,
    observer: null as MutationObserver | null,

    init(this: Self<{ open: boolean; unread: number; part: PopoverPart | null; observer: MutationObserver | null; sync: () => void }>) {
      this.part = popoverPart(this.$refs.trigger, this.$refs.panel, { side: 'bottom', align: 'end', offset: 10 }, (open) => (this.open = open));
      this.sync();
      this.$watch('unread', (next: number, before: number) => {
        renderNumber(this.$refs.count, next, lang || undefined);
        if (next > before) {
          replay(this.$refs.badge, 'data-pop');
          replay(this.$refs.trigger, 'data-ring');
        }
      });
      // Livewire morphing new items in (or the server marking them read) recounts.
      this.observer = new MutationObserver(() => this.sync());
      this.observer.observe(this.$refs.panel, { subtree: true, childList: true, attributes: true, attributeFilter: ['data-server-unread'] });
    },

    destroy(this: { part: PopoverPart | null; observer: MutationObserver | null }) {
      this.part?.destroy();
      this.observer?.disconnect();
    },

    isUnread(this: { read: string[] }, id: string, unread: boolean) {
      return unread && !this.read.includes(id);
    },

    sync(this: Self<{ read: string[]; unread: number }>) {
      const ids = Array.from(this.$refs.panel.querySelectorAll<HTMLElement>('[data-server-unread]')).map((el) => el.dataset.id ?? '');
      this.unread = ids.filter((id) => !this.read.includes(id)).length;
    },

    markRead(this: Self<{ read: string[]; sync: () => void }>, id: string) {
      if (!this.read.includes(id)) this.read = [...this.read, id];
      this.sync();
    },

    markAll(this: Self<{ read: string[]; sync: () => void }>) {
      this.read = Array.from(this.$refs.panel.querySelectorAll<HTMLElement>('[data-id]')).map((el) => el.dataset.id ?? '');
      this.sync();
      this.$dispatch('nx-mark-all-read');
    },

    ringEnded(this: Self<object>, event: AnimationEvent) {
      if (event.animationName === 'nx-activity-dropdown-ring') this.$refs.trigger.removeAttribute('data-ring');
    },
  }));

  /* ---- Member selector ------------------------------------------------------------------------------ */
  Alpine.data('nxMemberSelector', (members: MemberJson[] = [], value: string[] = [], model: string | null = null, max = 4, lang: string | null = null) => ({
    members,
    selected: toList(value),
    open: false,
    query: '',
    fresh: null as string | null,
    part: null as PopoverPart | null,
    flip: null as ReturnType<typeof flipStack> | null,

    init(this: Self<{ selected: string[]; open: boolean; query: string; fresh: string | null; part: PopoverPart | null; flip: ReturnType<typeof flipStack> | null; rest: number }>) {
      const wire = wireOf(this);
      if (model && typeof wire?.$get === 'function') this.selected = toList(wire.$get(model));
      if (model && typeof wire?.$watch === 'function') wire.$watch(model, (next) => (this.selected = toList(next)));
      this.part = popoverPart(this.$refs.trigger, this.$refs.panel, { side: 'bottom', align: 'start', offset: 8 }, (open) => {
        this.open = open;
        if (open) requestAnimationFrame(() => this.$refs.search.focus({ preventScroll: true }));
        else this.query = '';
      });
      this.$nextTick(() => {
        this.flip = flipStack(this.$refs.stack, { selector: '[data-key]' });
      });
      this.$watch('selected', () => this.$nextTick(() => this.flip?.update()));
      this.$watch('rest', (rest: number) => {
        renderNumber(this.$refs.more, rest, lang || undefined);
        replay(this.$refs.more?.parentElement, 'data-new');
      });
    },

    destroy(this: { part: PopoverPart | null; flip: ReturnType<typeof flipStack> | null }) {
      this.part?.destroy();
      this.flip?.destroy();
    },

    get chosen(): MemberJson[] {
      const self = this as unknown as { members: MemberJson[]; selected: string[] };
      return toList(self.selected)
        .map((id) => self.members.find((member) => member.id === id))
        .filter((member): member is MemberJson => !!member);
    },

    get shown(): MemberJson[] {
      const chosen = (this as unknown as { chosen: MemberJson[] }).chosen;
      return chosen.length > max ? chosen.slice(0, max - 1) : chosen;
    },

    get rest(): number {
      const self = this as unknown as { chosen: MemberJson[]; shown: MemberJson[] };
      return self.chosen.length - self.shown.length;
    },

    isSelected(this: { selected: string[] }, id: string) {
      return toList(this.selected).includes(id);
    },

    /** Called on each checkbox change: keep the order people were added in. */
    changed(this: { selected: string[]; fresh: string | null }, id: string, checked: boolean) {
      const list = toList(this.selected);
      if (checked && !list.includes(id)) {
        this.selected = [...list, id];
        this.fresh = id;
      } else if (!checked) this.selected = list.filter((item) => item !== id);
    },

    matches(this: { query: string }, text: string) {
      const q = foldSearchText(this.query.trim());
      return !q || foldSearchText(text).includes(q);
    },

    get empty(): boolean {
      const self = this as unknown as { members: MemberJson[]; matches: (t: string) => boolean };
      return !self.members.some((member) => self.matches(`${member.name} ${member.email ?? ''}`));
    },

    initials(name: string) {
      return name
        .split(/\s+/)
        .filter(Boolean)
        .slice(0, 2)
        .map((part) => Array.from(part)[0])
        .join('')
        .toLocaleUpperCase();
    },

    summary(this: { chosen: MemberJson[] }, label: string, more: string, empty: string) {
      const names = this.chosen.map((member) => member.name);
      if (!names.length) return empty;
      return `${label}: ${names.slice(0, 3).join(', ')}${names.length > 3 ? `, ${more.replace(':count', String(names.length - 3))}` : ''}`;
    },
  }));

  /* ---- Registration card ----------------------------------------------------------------------------- */
  interface TicketJson {
    id: string;
    label: string;
    price: number;
    max?: number;
  }

  Alpine.data('nxRegistrationCard', (tickets: TicketJson[] = [], quantities: Record<string, number> = {}, currency = 'USD', lang: string | null = null, steps = 3) => ({
    step: 0,
    done: false,
    needTicket: false,
    name: '',
    email: '',
    quantities: { ...quantities } as Record<string, number>,
    unmorph: (() => {}) as Cleanup,
    observer: null as MutationObserver | null,

    init(this: Self<{ step: number; done: boolean; quantities: Record<string, number>; unmorph: Cleanup; observer: MutationObserver | null; fromServer: () => void; total: number }>) {
      this.unmorph = morphShell(this.$refs.viewport, { content: this.$refs.measure, axis: 'block' });
      renderNumber(this.$refs.total, this.total, lang || undefined, { style: 'currency', currency });
      this.fromServer();
      // Livewire re-rendering: a success flag, or errors to walk back to.
      this.observer = new MutationObserver(() => this.fromServer());
      this.observer.observe(this.$root, { subtree: true, attributes: true, attributeFilter: ['data-success', 'aria-invalid'] });
      this.$watch('quantities', () => {
        renderNumber(this.$refs.total, this.total, lang || undefined, { style: 'currency', currency });
        for (const ticket of tickets) {
          const count = this.$root.querySelector<HTMLElement>(`[data-qty="${CSS.escape(ticket.id)}"]`);
          if (count) renderNumber(count, this.quantities[ticket.id] ?? 0, lang || undefined);
        }
      });
    },

    destroy(this: { unmorph: Cleanup; observer: MutationObserver | null }) {
      this.unmorph();
      this.observer?.disconnect();
    },

    get total(): number {
      return ticketTotal(tickets, (this as unknown as { quantities: Record<string, number> }).quantities);
    },

    get count(): number {
      return Object.values((this as unknown as { quantities: Record<string, number> }).quantities).reduce((sum, qty) => sum + (Number(qty) || 0), 0);
    },

    /** Ticket kinds with a quantity, for the summary. */
    get picked(): Array<TicketJson & { qty: number }> {
      const self = this as unknown as { quantities: Record<string, number> };
      return tickets.map((ticket) => ({ ...ticket, qty: self.quantities[ticket.id] ?? 0 })).filter((ticket) => ticket.qty > 0);
    },

    money(amount: number, free: string) {
      return amount === 0 ? free : new Intl.NumberFormat(lang || undefined, { style: 'currency', currency }).format(amount);
    },

    number(value: number) {
      return new Intl.NumberFormat(lang || undefined).format(value);
    },

    pos(this: { step: number }, index: number) {
      return index < this.step ? 'before' : index === this.step ? 'current' : 'after';
    },

    status(this: { step: number; done: boolean }, index: number) {
      return this.done || index < this.step ? 'complete' : index === this.step ? 'current' : 'upcoming';
    },

    fromServer(this: Self<{ step: number; done: boolean; celebrate: () => void; go: (n: number) => void }>) {
      if (this.$root.dataset.success === 'true' && !this.done) {
        this.done = true;
        this.go(steps);
        this.celebrate();
        return;
      }
      // Server-side errors: go back to the first step that has one.
      const invalid = this.$root.querySelector<HTMLElement>('[data-step] [aria-invalid="true"]');
      const at = invalid ? Number(invalid.closest<HTMLElement>('[data-step]')?.dataset.step ?? -1) : -1;
      if (at >= 0 && at < this.step && !this.done) this.go(at);
    },

    celebrate(this: Self<object>) {
      setTimeout(() => {
        const check = this.$refs.check;
        if (check) burst(check, { count: 16, colors: ['var(--nx-success)', 'var(--nx-gold)', 'var(--nx-accent)', 'var(--nx-glow)'] });
      }, 420);
    },

    go(this: Self<{ step: number; done: boolean; name: string; email: string }>, next: number) {
      // The summary shows what the fields hold as the reader moves on.
      this.name = this.$root.querySelector<HTMLInputElement>('[data-field="name"]')?.value ?? '';
      this.email = this.$root.querySelector<HTMLInputElement>('[data-field="email"]')?.value ?? '';
      this.step = next;
      this.$nextTick(() => {
        const current = this.$root.querySelector<HTMLElement>('[data-pos="current"]');
        const target = this.done ? current?.querySelector<HTMLElement>('[tabindex="-1"]') : current?.querySelector<HTMLElement>('input:not([type="hidden"]), button:not(:disabled), select');
        target?.focus({ preventScroll: true });
      });
    },

    valid(this: Self<{ step: number; count: number; needTicket: boolean }>) {
      const fields = Array.from(this.$root.querySelectorAll<HTMLInputElement>(`[data-step="${this.step}"] input:not([type="hidden"])`));
      for (const field of fields) {
        if (!field.checkValidity()) {
          field.reportValidity();
          field.focus();
          return false;
        }
      }
      if (this.step === 1 && this.count === 0) {
        this.needTicket = true;
        return false;
      }
      return true;
    },

    /** Capture phase: steps before the last never reach wire:submit or the server. */
    submit(this: Self<{ step: number; done: boolean; valid: () => boolean; go: (n: number) => void }>, event: SubmitEvent) {
      if (this.done) {
        event.preventDefault();
        event.stopImmediatePropagation();
        return;
      }
      if (this.step < steps - 1 || !this.valid()) {
        event.preventDefault();
        event.stopImmediatePropagation();
        if (this.step < steps - 1 && this.valid()) this.go(this.step + 1);
      }
    },

    back(this: { step: number; go: (n: number) => void }) {
      if (this.step > 0) this.go(this.step - 1);
    },

    qty(this: Self<{ quantities: Record<string, number>; needTicket: boolean }>, id: string, delta: number) {
      const ticket = tickets.find((item) => item.id === id);
      const next = Math.max(0, Math.min(ticket?.max ?? 10, (this.quantities[id] ?? 0) + delta));
      this.needTicket = false;
      this.quantities = { ...this.quantities, [id]: next };
      // Hidden inputs carry wire:model: tell Livewire the value moved.
      this.$nextTick(() => this.$root.querySelector<HTMLInputElement>(`input[type="hidden"][data-ticket="${CSS.escape(id)}"]`)?.dispatchEvent(new Event('input', { bubbles: true })));
    },
  }));

  /* ---- Voice recorder ------------------------------------------------------------------------------------ */
  // Never asks for the microphone: apps feed levels with $dispatch('nx-record-level', 0.4) on the element.
  Alpine.data('nxVoiceRecorder', (maxDuration = 0, lang: string | null = null) => ({
    state: 'idle' as 'idle' | 'recording' | 'paused' | 'stopped',
    elapsed: 0,
    duration: 0,
    position: 0,
    playing: false,
    level: null as number | null,
    heardAt: 0,
    samples: [] as number[],
    clock: { start: 0, before: 0 },
    seed: Math.random() * 100,
    meter: null as ReturnType<typeof levelMeter> | null,
    ticker: 0 as unknown as ReturnType<typeof setInterval>,
    frame: 0,
    unmorph: (() => {}) as Cleanup,

    init(this: Self<{ level: number | null; heardAt: number; samples: number[]; meter: ReturnType<typeof levelMeter> | null; unmorph: Cleanup; seconds: () => number; seed: number }>) {
      this.unmorph = morphShell(this.$root, { content: this.$refs.measure });
      this.meter = levelMeter(this.$refs.wave, {
        level: () => (this.level !== null && performance.now() - this.heardAt < 600 ? this.level : voiceLevel(this.seconds(), this.seed)),
        onSample: (value) => this.samples.push(value),
      });
      this.$root.addEventListener('nx-record-level', (event) => {
        this.level = Number((event as CustomEvent).detail) || 0;
        this.heardAt = performance.now();
      });
    },

    destroy(this: { meter: ReturnType<typeof levelMeter> | null; unmorph: Cleanup; ticker: ReturnType<typeof setInterval>; frame: number }) {
      this.meter?.destroy();
      this.unmorph();
      clearInterval(this.ticker);
      cancelAnimationFrame(this.frame);
    },

    seconds(this: { clock: { start: number; before: number } }) {
      return (this.clock.before + (this.clock.start ? performance.now() - this.clock.start : 0)) / 1000;
    },

    focus(this: Self<object>, name: string) {
      this.$nextTick(() => this.$root.querySelector<HTMLElement>(`[data-focus="${name}"]`)?.focus({ preventScroll: true }));
    },

    tick(this: Self<{ elapsed: number; seconds: () => number; stop: () => void; show: () => void }>) {
      const now = this.seconds();
      if (Math.floor(now) !== this.elapsed) {
        this.elapsed = Math.floor(now);
        this.show();
      }
      if (maxDuration && now >= maxDuration) this.stop();
    },

    show(this: Self<{ state: string; elapsed: number; duration: number; position: number; playing: boolean }>) {
      const value = this.state === 'stopped' ? (this.playing || this.position > 0 ? this.position : this.duration) : this.elapsed;
      rollText(this.state === 'stopped' ? this.$refs.playTime : this.$refs.liveTime, formatClock(value), lang || undefined);
    },

    start(this: Self<{ state: string; elapsed: number; samples: number[]; clock: { start: number; before: number }; meter: ReturnType<typeof levelMeter> | null; ticker: ReturnType<typeof setInterval>; tick: () => void; show: () => void; focus: (n: string) => void }>) {
      this.samples = [];
      this.clock = { start: performance.now(), before: 0 };
      this.elapsed = 0;
      this.state = 'recording';
      this.show();
      this.meter?.show(Array.from({ length: this.$refs.wave.children.length }, () => 0));
      this.meter?.start();
      clearInterval(this.ticker);
      this.ticker = setInterval(() => this.tick(), 200);
      this.focus('stop');
      this.$dispatch('nx-record-start');
    },

    togglePause(this: Self<{ state: string; clock: { start: number; before: number }; meter: ReturnType<typeof levelMeter> | null; ticker: ReturnType<typeof setInterval>; tick: () => void }>) {
      if (this.state === 'recording') {
        this.clock = { start: 0, before: this.clock.before + performance.now() - this.clock.start };
        this.meter?.stop();
        clearInterval(this.ticker);
        this.state = 'paused';
        this.$dispatch('nx-record-pause');
      } else if (this.state === 'paused') {
        this.clock = { start: performance.now(), before: this.clock.before };
        this.meter?.start();
        this.ticker = setInterval(() => this.tick(), 200);
        this.state = 'recording';
        this.$dispatch('nx-record-resume');
      }
    },

    stop(this: Self<{ state: string; elapsed: number; duration: number; position: number; playing: boolean; samples: number[]; clock: { start: number; before: number }; meter: ReturnType<typeof levelMeter> | null; ticker: ReturnType<typeof setInterval>; seconds: () => number; show: () => void; focus: (n: string) => void }>) {
      const total = this.seconds();
      this.meter?.stop();
      clearInterval(this.ticker);
      this.clock = { start: 0, before: 0 };
      this.duration = total;
      this.elapsed = Math.floor(total);
      this.position = 0;
      this.playing = false;
      const take = downsampleLevels(this.samples, this.$refs.take.querySelectorAll('i').length);
      this.$refs.take.querySelectorAll<HTMLElement>('i').forEach((bar, i) => bar.style.setProperty('--_v', String(take[i] ?? 0)));
      this.state = 'stopped';
      this.$nextTick(() => this.show());
      this.focus('play');
      this.$dispatch('nx-record-stop', { duration: Math.round(total * 10) / 10, levels: [...this.samples] });
    },

    cancel(this: Self<{ state: string; clock: { start: number; before: number }; meter: ReturnType<typeof levelMeter> | null; ticker: ReturnType<typeof setInterval>; focus: (n: string) => void }>) {
      this.meter?.stop();
      clearInterval(this.ticker);
      this.clock = { start: 0, before: 0 };
      this.state = 'idle';
      this.focus('mic');
      this.$dispatch('nx-record-cancel');
    },

    discard(this: Self<{ state: string; playing: boolean; frame: number; focus: (n: string) => void }>) {
      cancelAnimationFrame(this.frame);
      this.playing = false;
      this.state = 'idle';
      this.focus('mic');
      this.$dispatch('nx-record-discard');
    },

    togglePlay(this: Self<{ playing: boolean; position: number; duration: number; frame: number; show: () => void }>) {
      if (this.playing) {
        this.playing = false;
        cancelAnimationFrame(this.frame);
        return;
      }
      if (this.position >= this.duration - 0.05) this.position = 0;
      this.playing = true;
      let last = performance.now();
      const step = (now: number) => {
        this.position = Math.min(this.duration, this.position + (now - last) / 1000);
        last = now;
        this.show();
        if (this.position >= this.duration) this.playing = false;
        else if (this.playing) this.frame = requestAnimationFrame(step);
      };
      this.frame = requestAnimationFrame(step);
    },

    seek(this: { position: number; show: () => void }, value: string) {
      this.position = Number(value) || 0;
      this.show();
    },

    clockText(value: number) {
      return formatClock(value);
    },
  }));
}
