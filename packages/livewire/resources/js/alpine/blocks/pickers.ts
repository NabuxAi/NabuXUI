/**
 * Alpine parts of the pickers & inputs blocks — the same core functions the
 * React components use, driving the markup the Blade components render:
 *
 *   nxCombobox · nxTagInput · nxContextMenu · nxHoverCard · nxBottomSheet ·
 *   nxDateRange · nxTimePicker · nxNumberField
 *
 * Every value-carrying block binds through x-modelable on its root, so
 * `wire:model` (any modifier) goes on the component tag itself; a `name`
 * posts the value in a plain form through hidden inputs.
 */
import {
  type BottomSheetController,
  type DateRange,
  type HoverCardController,
  type PickerCalendar,
  type PickerOption,
  type RangePresetId,
  bottomSheet,
  calendarParts,
  clockText,
  comboEdge,
  comboMatches,
  comboSegments,
  comboStep,
  createTypeahead,
  ctxMenuTrigger,
  dayFormatter,
  focusableItems,
  from12h,
  hoverCard,
  isoAddDays,
  isoDaysBetween,
  isoToday,
  monthStartOf,
  nearestMinute,
  orderRange,
  parseAmount,
  parseClock,
  pickerWeekdays,
  place,
  placeAtPoint,
  pressRepeat,
  rangeClick,
  rangeMark,
  rangeMonthWeeks,
  rangePreset,
  scrubber,
  shiftCalendarMonth,
  snapNumber,
  snapWheel,
  tagDraft,
  tagIndex,
  tagKey,
  to12h,
  wheelTo,
} from '@nabuxai/ui-core';
import { renderNumber } from '../number';
import type { AlpineLike, Magics } from '../types';

type Self<T> = T & Magics;
type Cleanup = () => void;
type Wire = { call: (method: string, ...params: unknown[]) => Promise<unknown> };

const supportsPopover = () => 'popover' in HTMLElement.prototype;

function showPop(el: HTMLElement | undefined, open: boolean) {
  if (!el) return;
  if (!supportsPopover()) {
    el.toggleAttribute('data-open', open);
    return;
  }
  try {
    const shown = el.matches(':popover-open');
    if (open && !shown) el.showPopover();
    if (!open && shown) el.hidePopover();
  } catch {
    /* not connected */
  }
}

const fill = (template: string, params: Record<string, string | number>) =>
  template.replace(/\{(\w+)\}|:(\w+)/g, (all, a: string | undefined, b: string | undefined) => {
    const key = (a ?? b)!;
    return key in params ? String(params[key]) : all;
  });

const escapeHtml = (s: string) => s.replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[c]!);

/** A label with the typed text in <mark>, escaped (for x-html). */
const markHtml = (text: string, query: string) =>
  comboSegments(text, query)
    .map((seg) => (seg.match ? `<mark>${escapeHtml(seg.text)}</mark>` : escapeHtml(seg.text)))
    .join('');

/* ==== Config shapes ========================================================================= */

export interface ComboOptionJson extends PickerOption {
  icon?: string | null;
  iconSvg?: string | null;
}

export interface ComboboxConfig {
  options: ComboOptionJson[];
  value: string | null;
  clearable: boolean;
  filter: boolean;
  /** A Livewire method that returns options for the typed text (server search). */
  search?: string | null;
  labels: Record<string, string>;
}

export interface TagInputConfig {
  value: string[];
  suggestions: string[];
  max: number | null;
  separators?: string[] | null;
  locale: string;
  labels: Record<string, string>;
}

export interface DateRangeConfig {
  value: DateRange;
  calendar: PickerCalendar;
  weekStart: number;
  locale: string;
  /** Built-in preset ids (labels come from `labels`) or your own ranges. */
  presets: Array<string | { id: string; label: string; start: string; end: string }>;
  min?: string | null;
  max?: string | null;
  months: number;
  today?: string | null;
  labels: Record<string, string>;
}

export interface TimePickerConfig {
  value: string | null;
  twelve: boolean;
  minutes: number[];
  locale: string;
}

export interface NumberFieldConfig {
  value: number | null;
  min: number | null;
  max: number | null;
  step: number;
  largeStep: number | null;
  locale: string;
  format?: Intl.NumberFormatOptions | null;
}

export function installPickersBlocks(Alpine: AlpineLike): void {
  /* ==== Combobox ============================================================================ */
  Alpine.data('nxCombobox', (config: ComboboxConfig) => {
    let unplace: Cleanup = () => {};
    let timer: ReturnType<typeof setTimeout> | undefined;
    let ticket = 0;

    interface S {
      options: ComboOptionJson[];
      value: string | null;
      model: unknown;
      query: string;
      typed: boolean;
      open: boolean;
      active: number;
      loading: boolean;
      visible: number[];
      current: ComboOptionJson | null;
      off: (i: number) => boolean;
      close: (restore?: boolean) => void;
      choose: (i: number) => void;
      input: (text: string) => void;
      firstActive: () => void;
      reveal: () => void;
    }

    return {
      options: config.options,
      value: config.value,
      model: config.value as unknown,
      query: config.options.find((o) => o.value === config.value)?.label ?? '',
      typed: false,
      open: false,
      active: -1,
      loading: false,

      init(this: Self<S>) {
        this.$watch('open', (open: boolean) => {
          unplace();
          showPop(this.$refs.pop, open);
          if (open) unplace = place(this.$refs.field, this.$refs.pop, { side: 'bottom', align: 'start', offset: 6, matchWidth: true });
        });
        this.$watch('value', (next: string | null) => {
          if (this.model !== next) this.model = next;
          if (!this.typed) this.query = this.current?.label ?? '';
        });
        this.$watch('model', (next: unknown) => {
          const v = next == null || next === '' ? null : String(next);
          if (v !== this.value) this.value = v;
        });
      },

      destroy() {
        unplace();
        clearTimeout(timer);
      },

      get current(): ComboOptionJson | null {
        const self = this as unknown as S;
        return self.options.find((o) => o.value === self.value) ?? null;
      },

      get visible(): number[] {
        const self = this as unknown as S;
        return config.filter && self.typed ? comboMatches(self.options, self.query) : self.options.map((_, i) => i);
      },

      off(this: S, i: number) {
        return !!this.options[i]?.disabled;
      },

      labelHtml(this: S, i: number) {
        return markHtml(this.options[i]?.label ?? '', this.typed ? this.query : '');
      },

      optionId(i: number) {
        return `${(this as unknown as Self<S>).$root.id || 'nx-cb'}-opt-${i}`;
      },

      firstActive(this: S) {
        const at = this.visible.indexOf(this.options.findIndex((o) => o.value === this.value));
        this.active = this.typed || at < 0 ? comboEdge(this.visible, 'first', (i) => this.off(i)) : this.visible[at]!;
      },

      reveal(this: Self<S>) {
        this.$nextTick(() => this.$refs.list?.querySelector(`[data-index="${this.active}"]`)?.scrollIntoView({ block: 'nearest' }));
      },

      show(this: S) {
        if (this.open) return;
        this.open = true;
        this.firstActive();
      },

      close(this: S, restore = true) {
        this.open = false;
        this.active = -1;
        if (restore) {
          this.typed = false;
          this.query = this.current?.label ?? '';
        }
      },

      choose(this: Self<S>, i: number) {
        const option = this.options[i];
        if (!option || option.disabled) return;
        this.value = option.value;
        this.typed = false;
        this.query = option.label;
        this.close(false);
        this.$dispatch('nx-change', option.value);
      },

      input(this: Self<S>, text: string) {
        this.query = text;
        this.typed = true;
        this.open = true;
        if (text === '' && config.clearable) this.value = null;
        const wire = (this as unknown as { $wire?: Wire }).$wire;
        if (config.search && wire) {
          clearTimeout(timer);
          this.loading = true;
          const mine = ++ticket;
          timer = setTimeout(() => {
            wire
              .call(config.search!, text)
              .then((result) => {
                if (mine !== ticket) return;
                this.options = Array.isArray(result) ? (result as ComboOptionJson[]).map((o) => ({ ...o, value: String(o.value), label: String(o.label ?? o.value) })) : [];
                this.loading = false;
                this.firstActive();
              })
              .catch(() => {
                if (mine === ticket) this.loading = false;
              });
          }, 250);
        }
        this.$nextTick(() => this.firstActive());
      },

      keydown(this: Self<S>, event: KeyboardEvent) {
        const k = event.key;
        const off = (i: number) => this.off(i);
        if (k === 'ArrowDown' || k === 'ArrowUp') {
          event.preventDefault();
          if (!this.open) return (this as unknown as { show: () => void }).show();
          this.active = comboStep(this.visible, this.active, k === 'ArrowDown' ? 1 : -1, off);
          this.reveal();
        } else if ((k === 'Home' || k === 'End') && this.open) {
          event.preventDefault();
          this.active = comboEdge(this.visible, k === 'Home' ? 'first' : 'last', off);
          this.reveal();
        } else if (k === 'Enter' && this.open && this.active >= 0) {
          event.preventDefault();
          this.choose(this.active);
        } else if (k === 'Escape') {
          if (this.open) {
            event.preventDefault();
            this.close();
          } else if (config.clearable && this.query) {
            event.preventDefault();
            this.input('');
          }
        } else if (k === 'Tab' && this.open) this.close();
      },

      blur(this: Self<S>, event: FocusEvent) {
        const to = event.relatedTarget as Node | null;
        if (to && this.$root.contains(to)) return;
        if (this.open) this.close();
        else if (this.typed) {
          this.typed = false;
          this.query = this.current?.label ?? '';
        }
      },

      clear(this: Self<S>) {
        this.input('');
        (this.$refs.input as HTMLInputElement).focus();
      },

      toggle(this: Self<S>) {
        if (this.open) this.close();
        else (this as unknown as { show: () => void }).show();
        (this.$refs.input as HTMLInputElement).focus();
      },
    };
  });

  /* ==== Tag input =========================================================================== */
  Alpine.data('nxTagInput', (config: TagInputConfig) => {
    let unplace: Cleanup = () => {};
    let sweep: ReturnType<typeof setTimeout> | undefined;
    let flashTimer: ReturnType<typeof setTimeout> | undefined;
    const number = new Intl.NumberFormat(config.locale);

    interface Chip { tag: string; key: string; state: 'idle' | 'exit' }
    interface S {
      tags: string[];
      model: unknown;
      chips: Chip[];
      draft: string;
      active: number;
      focused: boolean;
      flash: string | null;
      announce: string;
      offered: string[];
      full: boolean;
      open: boolean;
      add: (text: string, commit: boolean) => void;
      remove: (tag: string, from?: HTMLElement) => void;
      reconcile: () => void;
      say: (text: string) => void;
    }

    return {
      tags: [...config.value],
      model: [...config.value] as unknown,
      chips: config.value.map((tag) => ({ tag, key: tagKey(tag), state: 'idle' })) as Chip[],
      draft: '',
      active: -1,
      focused: false,
      flash: null as string | null,
      announce: '',
      labels: config.labels,

      init(this: Self<S>) {
        this.$watch('tags', (next: string[]) => {
          this.reconcile();
          if (JSON.stringify(this.model) !== JSON.stringify(next)) this.model = [...next];
        });
        this.$watch('model', (next: unknown) => {
          if (!Array.isArray(next)) {
            this.model = [...this.tags];
            return;
          }
          const clean = next.map(String);
          if (JSON.stringify(clean) !== JSON.stringify(this.tags)) this.tags = clean;
        });
        this.$watch('open', (open: boolean) => {
          unplace();
          showPop(this.$refs.pop, open);
          if (open && this.$refs.pop) unplace = place(this.$refs.field, this.$refs.pop, { side: 'bottom', align: 'start', offset: 6, matchWidth: true });
        });
        this.$watch('draft', () => (this.active = -1));
      },

      destroy() {
        unplace();
        clearTimeout(sweep);
        clearTimeout(flashTimer);
      },

      get full(): boolean {
        const self = this as unknown as S;
        return config.max != null && self.tags.length >= config.max;
      },

      get offered(): string[] {
        const self = this as unknown as S;
        const q = self.draft.trim();
        if (!q || !config.suggestions.length) return [];
        const opts = config.suggestions.filter((s) => tagIndex(self.tags, s) < 0).map((s) => ({ value: s, label: s }));
        return comboMatches(opts, q).slice(0, 8).map((i) => opts[i]!.label);
      },

      get open(): boolean {
        const self = this as unknown as S;
        return self.focused && self.offered.length > 0 && !self.full;
      },

      get countText(): string {
        const self = this as unknown as S;
        return config.max != null ? `${number.format(self.tags.length)} / ${number.format(config.max)}` : '';
      },

      markHtml(this: S, text: string) {
        return markHtml(text, this.draft);
      },

      say(this: S, text: string) {
        this.announce = this.announce === text ? `${text}​` : text;
      },

      reconcile(this: S) {
        const wanted = new Set(this.tags.map(tagKey));
        const known = new Set(this.chips.map((c) => c.key));
        const chips: Chip[] = this.chips.map((c) => ({ ...c, state: wanted.has(c.key) ? 'idle' : 'exit' }));
        for (const tag of this.tags) if (!known.has(tagKey(tag))) chips.push({ tag, key: tagKey(tag), state: 'idle' });
        this.chips = chips;
        clearTimeout(sweep);
        if (chips.some((c) => c.state === 'exit')) sweep = setTimeout(() => (this.chips = this.chips.filter((c) => c.state !== 'exit')), 220);
      },

      add(this: Self<S>, text: string, commit: boolean) {
        const result = tagDraft(text, this.tags, commit, { separators: config.separators ?? undefined, max: config.max });
        if (result.added.length) {
          this.tags = [...this.tags, ...result.added];
          this.say(fill(config.labels.added!, { name: result.added.join('، ') }));
          this.$dispatch('nx-change', this.tags);
        }
        if (result.duplicates.length) {
          const existing = this.tags[tagIndex(this.tags, result.duplicates[0]!)];
          if (existing) {
            this.flash = tagKey(existing);
            clearTimeout(flashTimer);
            flashTimer = setTimeout(() => (this.flash = null), 420);
          }
          this.say(fill(config.labels.duplicate!, { name: result.duplicates[0]! }));
        }
        if (result.overflow.length && config.max != null) this.say(fill(config.labels.limit!, { max: number.format(config.max) }));
        this.draft = result.rest;
      },

      remove(this: Self<S>, tag: string, from?: HTMLElement) {
        const at = tagIndex(this.tags, tag);
        if (at < 0) return;
        if (from) {
          const buttons = Array.from(this.$refs.field.querySelectorAll<HTMLButtonElement>('.nx-taginput-chip:not([data-state="exit"]) .nx-taginput-chip-remove'));
          const i = buttons.indexOf(from as HTMLButtonElement);
          (buttons[i + 1] ?? buttons[i - 1] ?? (this.$refs.input as HTMLInputElement)).focus();
        }
        this.tags = this.tags.filter((_, i) => i !== at);
        this.say(fill(config.labels.removed!, { name: tag }));
        this.$dispatch('nx-change', this.tags);
      },

      typing(this: S, text: string) {
        if (/[,،;\n]/.test(text) || config.separators?.some((s) => text.includes(s))) this.add(text, false);
        else this.draft = text;
      },

      paste(this: S, event: ClipboardEvent) {
        const text = event.clipboardData?.getData('text') ?? '';
        if (/[,،;\n]/.test(text)) {
          event.preventDefault();
          this.add(this.draft + text, true);
        }
      },

      keydown(this: S, event: KeyboardEvent) {
        const k = event.key;
        if (this.open && (k === 'ArrowDown' || k === 'ArrowUp')) {
          event.preventDefault();
          this.active = comboStep(this.offered.map((_, i) => i), this.active, k === 'ArrowDown' ? 1 : -1);
        } else if (k === 'Enter') {
          if (this.open && this.active >= 0) {
            event.preventDefault();
            this.add(this.offered[this.active]!, true);
          } else if (this.draft.trim()) {
            event.preventDefault();
            this.add(this.draft, true);
          }
        } else if (k === 'Backspace' && this.draft === '' && this.tags.length) {
          event.preventDefault();
          this.remove(this.tags[this.tags.length - 1]!);
        } else if (k === 'Escape' && this.open) {
          event.preventDefault();
          this.draft = '';
        }
      },

      blur(this: Self<S>, event: FocusEvent) {
        if (this.$refs.pop?.contains(event.relatedTarget as Node)) return;
        this.focused = false;
        if (this.draft.trim()) this.add(this.draft, true);
      },

      removeText(tag: string) {
        return fill(config.labels.remove!, { name: tag });
      },
    };
  });

  /* ==== Context menu ======================================================================== */
  Alpine.data('nxContextMenu', (config: { pressDelay?: number } = {}) => {
    let off: Cleanup = () => {};
    let restore: HTMLElement | null = null;
    let hoverTimer: ReturnType<typeof setTimeout> | undefined;
    const unplaces = new Map<HTMLElement, Cleanup>();
    const typeahead = createTypeahead();
    const ITEM = ':scope > .nx-ctxmenu-item';

    interface S {
      menu: HTMLElement;
      openSub: (button: HTMLElement, focusFirst: boolean) => void;
      closeSub: (sub: HTMLElement) => void;
    }

    return {
      get menu(): HTMLElement {
        return (this as unknown as Magics).$refs.menu;
      },

      init(this: Self<S>) {
        const area = this.$root;
        const menu = this.menu;
        menu.addEventListener('toggle', (event: Event) => {
          if ((event as ToggleEvent).newState === 'closed' && restore && (menu.contains(document.activeElement) || document.activeElement === document.body)) restore.focus();
        });
        // Each submenu reports its own open state to its button.
        menu.querySelectorAll<HTMLElement>('.nx-ctxmenu-menu[data-sub]').forEach((sub) => {
          sub.addEventListener('toggle', (event: Event) => {
            const open = (event as ToggleEvent).newState === 'open';
            const button = sub.previousElementSibling as HTMLElement | null;
            button?.setAttribute('aria-expanded', String(open));
            button?.toggleAttribute('data-open', open);
            if (!open) {
              unplaces.get(sub)?.();
              unplaces.delete(sub);
            }
          });
        });
        off = ctxMenuTrigger(area, {
          delay: config.pressDelay ?? 500,
          onOpen: (point, event) => {
            restore = document.activeElement instanceof HTMLElement && area.contains(document.activeElement) ? document.activeElement : area;
            showPop(menu, false);
            showPop(menu, true);
            placeAtPoint(menu, point);
            const keyboard = event.type === 'keydown';
            requestAnimationFrame(() => (keyboard ? focusableItems(menu, ITEM)[0]?.focus() : menu.focus({ preventScroll: true })));
          },
        });
      },

      destroy() {
        off();
        clearTimeout(hoverTimer);
        unplaces.forEach((u) => u());
      },

      openSub(this: S, button: HTMLElement, focusFirst: boolean) {
        const sub = button.nextElementSibling as HTMLElement | null;
        if (!sub || button.getAttribute('aria-disabled') === 'true') return;
        showPop(sub, true);
        unplaces.get(sub)?.();
        unplaces.set(sub, place(button, sub, { side: 'inline-end', align: 'start', offset: 2 }));
        if (focusFirst) requestAnimationFrame(() => focusableItems(sub, ITEM)[0]?.focus());
      },

      closeSub(sub: HTMLElement) {
        showPop(sub, false);
        (sub.previousElementSibling as HTMLElement | null)?.focus();
      },

      hoverSub(this: S, button: HTMLElement) {
        clearTimeout(hoverTimer);
        hoverTimer = setTimeout(() => this.openSub(button, false), 120);
      },

      leaveSub() {
        clearTimeout(hoverTimer);
      },

      pick(this: Self<S>, button: HTMLElement) {
        if (button.getAttribute('aria-disabled') === 'true') return;
        if (button.hasAttribute('aria-haspopup')) return this.openSub(button, true);
        showPop(this.menu, false);
        this.$dispatch('nx-select', button.dataset.id ?? '');
      },

      keydown(this: Self<S>, event: KeyboardEvent) {
        const target = event.target as HTMLElement;
        const host = target.closest<HTMLElement>('.nx-ctxmenu-menu');
        if (!host) return;
        const rtl = getComputedStyle(host).direction === 'rtl';
        const forward = rtl ? 'ArrowLeft' : 'ArrowRight';
        const back = rtl ? 'ArrowRight' : 'ArrowLeft';
        const rows = focusableItems(host, ITEM);
        const at = rows.indexOf(document.activeElement as HTMLElement);
        if (event.key === 'ArrowDown' || event.key === 'ArrowUp' || event.key === 'Home' || event.key === 'End') {
          event.preventDefault();
          let next: number;
          if (event.key === 'Home') next = 0;
          else if (event.key === 'End') next = rows.length - 1;
          else if (at < 0) next = event.key === 'ArrowDown' ? 0 : rows.length - 1;
          else next = (at + (event.key === 'ArrowDown' ? 1 : -1) + rows.length) % rows.length;
          rows[next]?.focus();
        } else if (event.key === forward && target.hasAttribute('aria-haspopup')) {
          event.preventDefault();
          this.openSub(target, true);
        } else if ((event.key === back || event.key === 'Escape') && host.hasAttribute('data-sub')) {
          event.preventDefault();
          event.stopPropagation();
          this.closeSub(host);
        } else if (event.key === 'Tab') {
          event.preventDefault();
          showPop(this.menu, false);
        } else {
          const hit = typeahead(event.key, rows, at);
          if (hit) hit.focus();
        }
      },
    };
  });

  /* ==== Hover card ========================================================================== */
  Alpine.data('nxHoverCard', (config: { openDelay?: number; closeDelay?: number; side?: string; align?: string } = {}) => {
    let ctrl: HoverCardController | null = null;
    let unplace: Cleanup = () => {};
    return {
      open: false,
      init(this: Self<{ open: boolean }>) {
        const trigger = this.$refs.trigger;
        const card = this.$refs.card;
        ctrl = hoverCard(trigger, card, {
          openDelay: config.openDelay ?? 500,
          closeDelay: config.closeDelay ?? 250,
          onOpenChange: (open) => (this.open = open),
        });
        this.$watch('open', (open: boolean) => {
          unplace();
          showPop(card, open);
          if (open) unplace = place(trigger, card, { side: (config.side ?? 'bottom') as 'bottom', align: (config.align ?? 'center') as 'center', offset: 8 });
        });
      },
      destroy() {
        ctrl?.destroy();
        unplace();
      },
    };
  });

  /* ==== Bottom sheet ======================================================================== */
  Alpine.data('nxBottomSheet', (config: { snaps: number[]; initial: number; dismissible: boolean; open: boolean; id: string }) => {
    let ctrl: BottomSheetController | null = null;
    let listen: (event: Event) => void = () => {};

    interface S {
      open: boolean;
      snap: number;
      dialog: HTMLDialogElement;
      sync: () => void;
    }

    return {
      open: config.open,
      snap: config.initial,

      get dialog(): HTMLDialogElement {
        return (this as unknown as Magics).$refs.dialog as HTMLDialogElement;
      },

      get percent(): string {
        const self = this as unknown as S;
        return new Intl.NumberFormat(document.documentElement.lang || undefined, { style: 'percent' }).format(config.snaps[self.snap] ?? 0);
      },

      init(this: Self<S>) {
        this.$watch('open', () => this.sync());
        this.dialog.addEventListener('close', () => (this.open = false));
        this.dialog.addEventListener('cancel', (event) => {
          event.preventDefault();
          if (config.dismissible) this.open = false;
        });
        this.dialog.addEventListener('click', (event) => {
          if (event.target === this.dialog && config.dismissible && event.clientY < this.dialog.getBoundingClientRect().top) this.open = false;
        });
        listen = (event: Event) => {
          if ((event as CustomEvent).detail === config.id) this.open = true;
        };
        window.addEventListener('nx-open-sheet', listen);
        // Anything inside can close it: $dispatch('nx-close-sheet').
        this.$root.addEventListener('nx-close-sheet', () => (this.open = false));
        if (this.open) this.$nextTick(() => this.sync());
      },

      destroy() {
        ctrl?.destroy();
        window.removeEventListener('nx-open-sheet', listen);
      },

      sync(this: Self<S>) {
        const el = this.dialog;
        if (this.open && !el.open) {
          ctrl?.destroy();
          ctrl = bottomSheet(el, {
            snaps: config.snaps,
            initial: config.initial,
            dismissible: config.dismissible,
            onSnap: (i) => {
              this.snap = i;
              this.$dispatch('nx-snap', i);
            },
            onDismiss: () => (this.open = false),
          });
          this.snap = config.initial;
          el.showModal();
        }
        if (!this.open && el.open) {
          el.close();
          ctrl?.destroy();
          ctrl = null;
        }
      },

      cycle(this: S) {
        ctrl?.snapTo((this.snap + 1) % config.snaps.length);
      },

      handleKey(this: S, event: KeyboardEvent) {
        if (event.key !== 'ArrowUp' && event.key !== 'ArrowDown') return;
        event.preventDefault();
        ctrl?.snapTo(this.snap + (event.key === 'ArrowUp' ? 1 : -1));
      },
    };
  });

  /* ==== Date-range picker =================================================================== */
  Alpine.data('nxDateRange', (config: DateRangeConfig) => {
    const cal = config.calendar;
    const fmt = {
      title: dayFormatter(config.locale, cal, { month: 'long', year: 'numeric' }),
      day: dayFormatter(config.locale, cal, { day: 'numeric' }),
      long: dayFormatter(config.locale, cal, { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }),
      short: dayFormatter(config.locale, cal, { day: 'numeric', month: 'short', year: 'numeric' }),
      number: new Intl.NumberFormat(config.locale),
    };
    let unplace: Cleanup = () => {};
    let enterTimer: ReturnType<typeof setTimeout> | undefined;

    interface Cell { iso: string; day: string; label: string }
    interface S {
      value: DateRange;
      model: unknown;
      draft: DateRange;
      hover: string | null;
      view: string;
      focusDay: string;
      enter: string | null;
      open: boolean;
      today: string;
      go: (step: number) => void;
      commit: (range: DateRange) => void;
      close: () => void;
      moveFocus: (iso: string) => void;
      blocked: (iso: string) => boolean;
      shown: string[];
    }

    const today = config.today || isoToday();
    const clean = (r: unknown): DateRange => {
      const o = (r && typeof r === 'object' ? r : {}) as Record<string, unknown>;
      const pick = (v: unknown) => (typeof v === 'string' && /^\d{4}-\d{2}-\d{2}/.test(v) ? v.slice(0, 10) : null);
      return { start: pick(o.start), end: pick(o.end) };
    };

    return {
      value: clean(config.value),
      model: clean(config.value) as unknown,
      draft: clean(config.value),
      hover: null as string | null,
      view: monthStartOf(clean(config.value).start ?? today, cal),
      focusDay: clean(config.value).start ?? today,
      enter: null as string | null,
      open: false,
      today,
      presets: config.presets.map((p) => {
        if (typeof p !== 'string') return p;
        const r = rangePreset(p as RangePresetId, today, cal);
        return { id: p, label: config.labels[p] ?? p, start: r.start!, end: r.end! };
      }),
      weekdays: pickerWeekdays(config.locale, config.weekStart, 'narrow'),
      longWeekdays: pickerWeekdays(config.locale, config.weekStart, 'long'),

      init(this: Self<S>) {
        const trigger = this.$refs.trigger;
        const pop = this.$refs.pop;
        if (supportsPopover()) trigger.setAttribute('popovertarget', pop.id);
        pop.addEventListener('toggle', (event: Event) => {
          this.open = (event as ToggleEvent).newState === 'open';
        });
        this.$watch('open', (open: boolean) => {
          unplace();
          if (!open) return;
          this.draft = { ...this.value };
          this.hover = null;
          const anchor = this.value.start ?? this.today;
          this.view = monthStartOf(anchor, cal);
          this.focusDay = anchor;
          unplace = place(trigger, pop, { side: 'bottom', align: 'start', offset: 8 });
          this.$nextTick(() => pop.querySelector<HTMLButtonElement>('.nx-daterange-day[tabindex="0"]')?.focus());
        });
        this.$watch('value', (next: DateRange) => {
          if (JSON.stringify(this.model) !== JSON.stringify(next)) this.model = { ...next };
        });
        this.$watch('model', (next: unknown) => {
          const r = clean(next);
          if (r.start !== this.value.start || r.end !== this.value.end) this.value = r;
        });
      },

      destroy() {
        unplace();
        clearTimeout(enterTimer);
      },

      get shown(): string[] {
        const self = this as unknown as S;
        return Array.from({ length: config.months }, (_, i) => shiftCalendarMonth(self.view, i, cal));
      },

      /** The months on show, each with its title and its weeks of day cells (null = blank). */
      get monthsData(): Array<{ start: string; title: string; weeks: Array<Array<Cell | null>> }> {
        const self = this as unknown as S;
        return self.shown.map((start) => ({
          start,
          title: fmt.title(isoAddDays(start, 14)),
          weeks: rangeMonthWeeks(start, cal, config.weekStart).map((row) => row.map((iso) => (iso ? { iso, day: fmt.day(iso), label: fmt.long(iso) } : null))),
        }));
      },

      blocked(iso: string) {
        return (config.min != null && iso < config.min) || (config.max != null && iso > config.max);
      },

      mark(this: S, iso: string) {
        const preview = this.draft.start && !this.draft.end ? this.hover : null;
        return rangeMark(iso, this.draft.start, this.draft.end ?? preview);
      },

      previewing(this: S, iso: string) {
        const m = (this as unknown as { mark: (iso: string) => string | null }).mark(iso);
        return !!(this.draft.start && !this.draft.end && this.hover && m && m !== 'single');
      },

      get valueText(): string {
        const self = this as unknown as S;
        const { start, end } = self.value;
        if (!start || !end) return '';
        return start === end ? fmt.short(start) : `${fmt.short(start)} – ${fmt.short(end)}`;
      },

      get summary(): string {
        const self = this as unknown as S;
        const { start, end } = self.draft;
        const preview = start && !end ? self.hover : null;
        if (!start) return config.labels.pickRange!;
        const other = end ?? preview;
        if (!other) return `${fmt.short(start)} · ${config.labels.selectEnd}`;
        const [a, b] = orderRange(start, other);
        return `${fmt.short(a)} – ${fmt.short(b)} · ${fill(config.labels.nights!, { count: fmt.number.format(Math.abs(isoDaysBetween(a, b)) + 1) })}`;
      },

      presetActive(this: S, p: { start: string; end: string }) {
        return p.start === this.value.start && p.end === this.value.end;
      },

      go(this: S, step: number) {
        this.enter = step > 0 ? 'next' : 'prev';
        this.view = shiftCalendarMonth(this.view, step, cal);
        clearTimeout(enterTimer);
        enterTimer = setTimeout(() => (this.enter = null), 400);
      },

      close(this: Self<S>) {
        showPop(this.$refs.pop, false);
        this.$refs.trigger.focus();
      },

      commit(this: Self<S>, range: DateRange) {
        this.value = { ...range };
        this.$dispatch('nx-change', this.value);
        setTimeout(() => this.close(), 220);
      },

      click(this: S, iso: string) {
        if (this.blocked(iso)) return;
        const next = rangeClick(this.draft, iso);
        this.draft = next;
        this.focusDay = iso;
        if (next.end) this.commit(next);
      },

      preset(this: S, p: { start: string; end: string }) {
        this.draft = { start: p.start, end: p.end };
        this.view = monthStartOf(p.start, cal);
        this.commit(this.draft);
      },

      clearRange(this: Self<S>) {
        this.draft = { start: null, end: null };
        this.value = { start: null, end: null };
        this.$dispatch('nx-change', this.value);
      },

      hoverDay(this: S, iso: string) {
        if (this.draft.start && !this.draft.end) this.hover = iso;
      },

      moveFocus(this: Self<S>, iso: string) {
        this.focusDay = iso;
        if (this.draft.start && !this.draft.end) this.hover = iso;
        const first = this.shown[0]!;
        const after = shiftCalendarMonth(this.shown[this.shown.length - 1]!, 1, cal);
        if (iso < first) this.go(-1);
        else if (iso >= after) this.go(1);
        this.$nextTick(() => this.$refs.pop.querySelector<HTMLButtonElement>(`.nx-daterange-day[data-date="${iso}"]`)?.focus());
      },

      gridKey(this: S, event: KeyboardEvent) {
        const rtl = getComputedStyle(event.currentTarget as Element).direction === 'rtl';
        const side = rtl ? -1 : 1;
        const f = this.focusDay;
        const col = (new Date(`${f}T00:00:00Z`).getUTCDay() - config.weekStart + 7) % 7;
        let next: string | null = null;
        switch (event.key) {
          case 'ArrowRight': next = isoAddDays(f, side); break;
          case 'ArrowLeft': next = isoAddDays(f, -side); break;
          case 'ArrowDown': next = isoAddDays(f, 7); break;
          case 'ArrowUp': next = isoAddDays(f, -7); break;
          case 'PageDown': next = isoAddDays(shiftCalendarMonth(f, 1, cal), calendarParts(f, cal).day - 1); break;
          case 'PageUp': next = isoAddDays(shiftCalendarMonth(f, -1, cal), calendarParts(f, cal).day - 1); break;
          case 'Home': next = isoAddDays(f, -col); break;
          case 'End': next = isoAddDays(f, 6 - col); break;
          default: return;
        }
        event.preventDefault();
        this.moveFocus(next);
      },

      triggerClick(this: Self<S>) {
        if (!supportsPopover()) showPop(this.$refs.pop, !this.open);
        if (!supportsPopover()) this.open = !this.open;
      },
    };
  });

  /* ==== Time picker ========================================================================= */
  Alpine.data('nxTimePicker', (config: TimePickerConfig) => {
    const offs: Cleanup[] = [];
    const settled = new Map<string, number>();
    const timeFormat = new Intl.DateTimeFormat(config.locale, { hour: 'numeric', minute: '2-digit', hour12: config.twelve, timeZone: 'UTC' });

    interface S {
      value: string | null;
      model: unknown;
      clock: { hour: number; minute: number };
      hourIndex: number;
      minuteIndex: number;
      periodIndex: number;
      set: (hour: number, minute: number) => void;
      pick: (wheel: string, index: number) => void;
      scroll: (smooth: boolean) => void;
    }

    return {
      value: parseClock(config.value) ? clockText(parseClock(config.value)!) : null,
      model: config.value as unknown,

      init(this: Self<S>) {
        for (const wheel of ['hour', 'minute', 'period']) {
          const list = this.$refs[wheel];
          if (!list) continue;
          offs.push(
            snapWheel(list, {
              onSelect: (i) => {
                if (settled.get(wheel) === i) return;
                settled.set(wheel, i);
                this.pick(wheel, i);
              },
            }),
          );
        }
        this.$nextTick(() => this.scroll(false));
        this.$watch('value', (next: string | null) => {
          if (this.model !== next) this.model = next;
          this.scroll(true);
        });
        this.$watch('model', (next: unknown) => {
          const c = parseClock(typeof next === 'string' ? next : null);
          const text = c ? clockText(c) : null;
          if (text !== this.value) this.value = text;
        });
      },

      destroy() {
        offs.forEach((off) => off());
      },

      get clock(): { hour: number; minute: number } {
        return parseClock((this as unknown as S).value) ?? { hour: 9, minute: 0 };
      },

      get hourIndex(): number {
        const c = (this as unknown as S).clock;
        return config.twelve ? to12h(c.hour).hour - 1 : c.hour;
      },

      get minuteIndex(): number {
        const c = (this as unknown as S).clock;
        const step = config.minutes[1] ? config.minutes[1] - config.minutes[0]! : 1;
        return Math.max(0, config.minutes.indexOf(nearestMinute(c.minute, step)));
      },

      get periodIndex(): number {
        return to12h((this as unknown as S).clock.hour).pm ? 1 : 0;
      },

      get timeText(): string {
        const self = this as unknown as S;
        return self.value ? timeFormat.format(new Date(Date.UTC(2026, 0, 1, self.clock.hour, self.clock.minute))) : '—';
      },

      scroll(this: Self<S>, smooth: boolean) {
        const at: Record<string, number> = { hour: this.hourIndex, minute: this.minuteIndex, period: this.periodIndex };
        for (const wheel of Object.keys(at)) {
          const list = this.$refs[wheel];
          if (!list) continue;
          settled.set(wheel, at[wheel]!);
          wheelTo(list, at[wheel]!, smooth);
        }
      },

      set(this: Self<S>, hour: number, minute: number) {
        this.value = clockText({ hour, minute });
        this.$dispatch('nx-change', this.value);
      },

      pick(this: S, wheel: string, index: number) {
        const c = this.clock;
        const face = to12h(c.hour);
        if (wheel === 'hour') this.set(config.twelve ? from12h(index + 1, face.pm) : index, c.minute);
        else if (wheel === 'minute') this.set(c.hour, config.minutes[index] ?? 0);
        else this.set(from12h(face.hour, index === 1), c.minute);
      },

      key(this: S, wheel: string, event: KeyboardEvent) {
        const list = (this as unknown as Magics).$refs[wheel];
        const n = list?.children.length ?? 0;
        const now = wheel === 'hour' ? this.hourIndex : wheel === 'minute' ? this.minuteIndex : this.periodIndex;
        let next: number | null = null;
        if (event.key === 'ArrowDown') next = (now + 1) % n;
        else if (event.key === 'ArrowUp') next = (now - 1 + n) % n;
        else if (event.key === 'PageDown') next = Math.min(n - 1, now + 5);
        else if (event.key === 'PageUp') next = Math.max(0, now - 5);
        else if (event.key === 'Home') next = 0;
        else if (event.key === 'End') next = n - 1;
        if (next === null) return;
        event.preventDefault();
        this.pick(wheel, next);
      },
    };
  });

  /* ==== Number field ======================================================================== */
  Alpine.data('nxNumberField', (config: NumberFieldConfig) => {
    const offs: Cleanup[] = [];
    const bounds = { min: config.min ?? -Infinity, max: config.max ?? Infinity, step: config.step };
    const formatter = new Intl.NumberFormat(config.locale, { maximumFractionDigits: 20, ...(config.format ?? {}) });

    interface S {
      value: number | null;
      model: unknown;
      draft: string | null;
      text: string;
      nudge: (steps: number) => void;
      commit: () => void;
      paint: () => void;
    }

    return {
      value: config.value,
      model: config.value as unknown,
      draft: null as string | null,

      init(this: Self<S>) {
        if (this.$refs.down) offs.push(pressRepeat(this.$refs.down, { onStep: () => this.nudge(-1) }));
        if (this.$refs.up) offs.push(pressRepeat(this.$refs.up, { onStep: () => this.nudge(1) }));
        if (this.$refs.label && !this.$root.hasAttribute('data-no-scrub')) offs.push(scrubber(this.$refs.label, { onSteps: (n) => this.nudge(n) }));
        this.$watch('value', (next: number | null) => {
          if (this.model !== next) this.model = next;
          this.paint();
          this.$dispatch('nx-change', next);
        });
        this.$watch('model', (next: unknown) => {
          const n = next === null || next === '' || next === undefined ? null : Number(next);
          const v = n === null || Number.isNaN(n) ? null : n;
          if (v !== this.value) this.value = v;
        });
        this.paint();
      },

      destroy() {
        offs.forEach((off) => off());
      },

      get text(): string {
        const v = (this as unknown as S).value;
        return v == null ? '' : formatter.format(v);
      },

      get atMin(): boolean {
        const v = (this as unknown as S).value;
        return config.min != null && v != null && v <= config.min;
      },

      get atMax(): boolean {
        const v = (this as unknown as S).value;
        return config.max != null && v != null && v >= config.max;
      },

      paint(this: Self<S>) {
        const display = this.$refs.display;
        if (!display) return;
        display.hidden = this.value == null;
        if (this.value != null) renderNumber(display, this.value, config.locale, config.format ?? undefined);
      },

      nudge(this: S, steps: number) {
        const from = this.value ?? (config.min ?? 0);
        this.value = snapNumber(from + steps * config.step, bounds);
      },

      commit(this: S) {
        if (this.draft === null) return;
        const parsed = parseAmount(this.draft, config.locale);
        this.value = this.draft.trim() === '' ? null : snapNumber(Number.isNaN(parsed) ? (this.value ?? 0) : parsed, bounds);
        this.draft = null;
      },

      keydown(this: S, event: KeyboardEvent) {
        const big = config.largeStep ?? config.step * 10;
        const k = event.key;
        if (k === 'ArrowUp' || k === 'ArrowDown') {
          event.preventDefault();
          this.commit();
          this.nudge((k === 'ArrowUp' ? 1 : -1) * (event.shiftKey ? big / config.step : 1));
        } else if (k === 'PageUp' || k === 'PageDown') {
          event.preventDefault();
          this.nudge((k === 'PageUp' ? 1 : -1) * (big / config.step));
        } else if (k === 'Home' && config.min != null) {
          event.preventDefault();
          this.value = config.min;
        } else if (k === 'End' && config.max != null) {
          event.preventDefault();
          this.value = config.max;
        } else if (k === 'Enter') this.commit();
      },
    };
  });
}
