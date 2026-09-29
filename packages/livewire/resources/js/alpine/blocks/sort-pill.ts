/**
 * Alpine part for the sort pill block. The Blade component renders the markup;
 * this drives the native popover and the wire:model sync with the same core
 * behaviours as the React package (place, lightDismiss), so both builds move
 * identically.
 */
import { type Cleanup, type PlaceOptions, lightDismiss, place, roveFocus } from '@nabuxai/ui-core';
import type { AlpineLike, Magics } from '../types';

type Self<T> = T & Magics;

/** The bits of Livewire's $wire these parts use (absent outside Livewire). */
interface Wire {
  $watch?: (name: string, callback: (value: unknown) => void) => void;
  $get?: (name: string) => unknown;
  $set?: (name: string, value: unknown) => unknown;
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

/* ---- A native popover wired to a trigger ------------------------------------------- */

interface PopoverPart {
  show(): void;
  hide(): void;
  isOpen(): boolean;
  destroy: Cleanup;
}

/** Same wiring as menus.ts's popoverPart, kept local so the block files stay independent. */
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

interface SortOptionJson {
  value: string;
  label: string;
  icon?: string;
}

export function installSortPillBlocks(Alpine: AlpineLike): void {
  /* ---- Sort pill ---------------------------------------------------------------------- */
  // x-data="nxSortPill(@js($options), @js($value), @js($model), @js($align))".
  Alpine.data('nxSortPill', (options: SortOptionJson[] = [], value: string | null = null, model: string | null = null, align: 'start' | 'center' | 'end' = 'start') => ({
    options,
    selected: value ?? options[0]?.value ?? null,
    open: false,
    part: null as PopoverPart | null,

    init(this: Self<{ selected: string | null; open: boolean; part: PopoverPart | null }>) {
      const wire = wireOf(this);
      if (model && typeof wire?.$get === 'function') this.selected = (wire.$get(model) as string | null) ?? this.selected;
      if (model && typeof wire?.$watch === 'function')
        wire.$watch(model, (next) => (this.selected = typeof next === 'string' ? next : this.selected));
      this.part = popoverPart(this.$refs.trigger, this.$refs.panel, { side: 'bottom', align, offset: 8 }, (open) => (this.open = open));
    },

    destroy(this: { part: PopoverPart | null }) {
      this.part?.destroy();
    },

    get current(): SortOptionJson | null {
      const self = this as unknown as { options: SortOptionJson[]; selected: string | null };
      return self.options.find((option) => option.value === self.selected) ?? self.options[0] ?? null;
    },

    /** Pick an option: the trigger's label rolls into it, then the panel folds away. */
    choose(this: Self<{ selected: string | null; open: boolean; part: PopoverPart | null }>, id: string) {
      const wire = wireOf(this);
      this.selected = id;
      if (model && typeof wire?.$set === 'function') wire.$set(model, id);
      this.$dispatch('nx-change', { value: id });
      window.setTimeout(() => {
        if (this.open) this.part?.hide();
      }, 300);
    },

    keyList(this: Self<object>, event: KeyboardEvent) {
      roveFocus(event, this.$refs.list, '.nx-sort-pill-choice', { orientation: 'vertical' });
    },
  }));
}
