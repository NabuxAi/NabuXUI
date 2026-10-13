/**
 * Alpine part for the admin-shell block. The Blade components render the
 * markup; this drives the two springy bits the CSS cannot do alone — the
 * sidebar's moving highlight (core `indicator`, one per `.nx-admin-nav`,
 * including the drawer's copy) and the native-popover drawer and user menu
 * (place + lightDismiss, the same wiring as the sort pill).
 *
 * Registered in ../../index.ts: installAdminShellBlocks(Alpine) runs with the
 * other blocks on alpine:init, so any page that ships the bundle gets this
 * wiring for <x-nx::admin-shell> automatically.
 */
import { type Cleanup, type PlaceOptions, indicator, lightDismiss, place, roveFocus } from '@nabuxai/ui-core';
import { afterMorph } from '../morph';
import type { AlpineLike, Magics } from '../types';

type Self<T> = T & Magics;

const supportsPopover = () => 'popover' in HTMLElement.prototype;

interface NavPart {
  el: HTMLElement;
  ctrl: ReturnType<typeof indicator>;
}

interface PopoverPartOptions {
  /** Keep the panel placed against its trigger while it is open. */
  placement?: PlaceOptions;
  onOpen?: () => void;
  onClose?: () => void;
}

interface PopoverPart {
  show(): void;
  hide(): void;
  isOpen(): boolean;
  destroy: Cleanup;
}

/**
 * A native [popover] driven by hand: the trigger is its declarative invoker
 * (light dismiss, Escape and the top layer come from the browser); without
 * popover support it falls back to a data-open attribute + lightDismiss.
 * Same shape as the sort pill's part, kept local so block files stay independent.
 */
function popoverPart(trigger: HTMLElement, panel: HTMLElement, onChange: (open: boolean) => void, { placement, onOpen, onClose }: PopoverPartOptions = {}): PopoverPart {
  let open = false;
  let undismiss: Cleanup = () => {};
  let unplace: Cleanup = () => {};

  const sync = (next: boolean) => {
    if (next === open) return;
    open = next;
    trigger.setAttribute('aria-expanded', String(next));
    undismiss();
    undismiss = () => {};
    unplace();
    unplace = () => {};
    if (!next) {
      onClose?.();
    } else {
      if (placement) unplace = place(trigger, panel, placement);
      if (!supportsPopover()) undismiss = lightDismiss(panel, () => hide(), { inside: [trigger] });
      onOpen?.();
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

  trigger.setAttribute('aria-expanded', 'false');
  if (supportsPopover() && trigger.tagName === 'BUTTON' && !trigger.hasAttribute('popovertarget')) trigger.setAttribute('popovertarget', panel.id);
  if (!supportsPopover() || !trigger.hasAttribute('popovertarget')) trigger.addEventListener('click', onClick);
  panel.addEventListener('toggle', onToggle);

  return {
    show,
    hide,
    isOpen: () => open,
    destroy() {
      undismiss();
      unplace();
      trigger.removeEventListener('click', onClick);
      panel.removeEventListener('toggle', onToggle);
    },
  };
}

export function installAdminShellBlocks(Alpine: AlpineLike): void {
  /* ---- Admin shell -------------------------------------------------------------------------
   * x-modelable="active": wire:model on the root binds the current item's id.
   * x-data="nxAdminShell(active, collapsed)" on the .nx-admin root.
   */
  Alpine.data('nxAdminShell', (active = '', collapsed = false) => {
    let navs: NavPart[] = [];
    let drawer: PopoverPart | null = null;
    let onMorph: (() => void) | null = null;

    return {
      active,
      collapsed,
      drawerOpen: false,

      init(this: Self<{ active: string; collapsed: boolean; drawerOpen: boolean; rescan: () => void; move: () => void }>) {
        this.rescan();
        this.move();

        // The drawer: a native popover. When it opens, its copy of the nav gets
        // its highlight measured for the first time; when it closes from inside,
        // focus goes back to the menu button.
        const menu = this.$root.querySelector<HTMLElement>('.nx-admin-menu');
        const panel = this.$root.querySelector<HTMLElement>('.nx-admin-drawer');
        if (menu && panel && panel.id) {
          drawer = popoverPart(menu, panel, (open) => (this.drawerOpen = open), {
            onOpen: () => this.$nextTick(() => this.move()),
            onClose: () => {
              if (panel.contains(document.activeElement)) menu.focus();
            },
          });
        }

        this.$watch('active', () => this.move());
        this.$watch('collapsed', () => this.$nextTick(() => navs.forEach(({ ctrl }) => ctrl.refresh())));

        // Livewire may re-render the sidebar from the server; re-scan and re-place.
        onMorph = afterMorph(this.$root, () => {
          this.rescan();
          this.move();
        });
      },

      destroy(this: Self<{ rescan: () => void }>) {
        this.rescan();
        drawer?.destroy();
        if (onMorph) onMorph();
      },

      /** (Re)attach one indicator per nav in the shell — the inline one and the drawer's copy. */
      rescan(this: Self<{ $root: HTMLElement }>) {
        navs.forEach(({ ctrl }) => ctrl.destroy());
        navs = Array.from(this.$root.querySelectorAll<HTMLElement>('.nx-admin-nav')).map((el) => ({ el, ctrl: indicator(el) }));
      },

      /** Move every highlight to the current item (both the inline and drawer navs). */
      move(this: Self<{ active: string }>) {
        const id = CSS.escape(String(this.active ?? ''));
        for (const { el, ctrl } of navs) ctrl.update(el.querySelector(`[data-value="${id}"]`));
      },

      /** An item was picked: make it current, tell the page, fold the drawer away. */
      select(this: Self<{ active: string; drawerOpen: boolean; $dispatch: Magics['$dispatch'] }>, id: string) {
        this.active = id;
        this.$dispatch('nx-select', id);
        if (this.drawerOpen) window.setTimeout(() => drawer?.hide(), 240);
      },

      /** The drawer's own close button. */
      closeDrawer() {
        drawer?.hide();
      },
    };
  });

  /* ---- Admin topbar: the user menu popover -------------------------------------------------- */
  Alpine.data('nxAdminTopbar', () => ({
    userOpen: false,
    part: null as PopoverPart | null,

    init(this: Self<{ userOpen: boolean; part: PopoverPart | null; $refs: Record<string, HTMLElement> }>) {
      const trigger = this.$refs.user;
      const panel = this.$refs.userMenu;
      if (!trigger || !panel) return;
      this.part = popoverPart(trigger, panel, (open) => (this.userOpen = open), {
        placement: { side: 'bottom', align: 'end', offset: 8 },
        onClose: () => {
          if (panel.contains(document.activeElement)) trigger.focus();
        },
      });
      panel.addEventListener('keydown', (event: Event) =>
        roveFocus(event as KeyboardEvent, panel, '.nx-admin-user-item', { orientation: 'vertical' }),
      );
    },

    destroy(this: { part: PopoverPart | null }) {
      this.part?.destroy();
    },
  }));
}
