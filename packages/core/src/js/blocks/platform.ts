/**
 * Blocks: platform windows — one app-window frame, four platform shells.
 *
 * `platformWindow(el)` drives the chrome the CSS cannot do alone: the caption
 * state machine (`data-state` = rest | minimized | maximized), the Windows
 * snap-layouts flyout that arms while the maximize button is held or focused,
 * the close flash, and the live platform switch. Everything it decides is
 * written back as attributes on the root, so React, Alpine and plain HTML
 * share one behaviour over the markup described in css/blocks/platform.css:
 *
 *   .nx-app-window[data-platform][data-state][data-shape][data-closed]
 *     … .nx-app-caption-btn[data-kind=minimize|maximize|close]
 *     … .nx-app-snap > button[data-kind=maximize] + .nx-app-snap-menu[popover]
 *
 * The shell theme lives on the element itself — never on :root, because a page
 * may show four windows in four shells side by side. Wrappers that re-render
 * the platform prop just write `data-platform`; a MutationObserver here
 * re-syncs and announces the new shell with a bubbling `nx-platform-change`
 * event (`detail: { platform }`). The maximize button carries both labels —
 * `data-label-maximize` and `data-label-restore`, already translated by the
 * wrapper — and this behaviour swaps its `aria-label` as the state flips.
 *
 * With the default `autoRestore: true` a minimized window comes back after
 * 1.1s and a closed one after 0.8s, because the block's main use is demos;
 * a real window should pass `onClose` and set `autoRestore: false`.
 */
import { type Cleanup, isBrowser } from '../env';
import { lightDismiss } from './menus';
import { place } from '../place';

/** The four shells a window can wear. */
export const platforms = ['windows', 'macos', 'android', 'ios'] as const;
export type Platform = (typeof platforms)[number];

/** The shapes a snap zone flashes the window into. */
export const snapShapes = ['half', 'third', 'big', 'quarter', 'wide', 'mid', 'full'] as const;
export type SnapShape = (typeof snapShapes)[number];

/** The six snap-layout groups the Windows flyout offers. */
export const snapZones = ['halves', 'thirds', 'big-start', 'quads', 'big-top', 'mixed'] as const;
export type SnapZone = (typeof snapZones)[number];

export type WindowState = 'rest' | 'minimized' | 'maximized';

/** Bubbles off a window whose `data-platform` changed (from any direction). */
export const PLATFORM_EVENT = 'nx-platform-change';
/** Bubbles off a window whose close button was pressed. */
export const WINDOW_CLOSE_EVENT = 'nx-window-close';

export function isPlatform(value: unknown): value is Platform {
  return typeof value === 'string' && (platforms as readonly string[]).includes(value);
}

/**
 * Popover support is proven by the method, not the property: happy-dom keeps
 * `popover` on HTMLElement.prototype while `showPopover` is undefined, so the
 * prototype guard passes right before the call crashes.
 */
const supportsPopover = (): boolean => isBrowser && typeof document.createElement('div').showPopover === 'function';

export interface PlatformWindowOptions {
  /** Arm the snap-layouts flyout on a held or focused maximize button. */
  snap?: boolean;
  /** How long the maximize button must be held/focused before the flyout opens. */
  holdMs?: number;
  /** Bring minimized (after 1.1s) and closed (after 0.8s) windows back by themselves. */
  autoRestore?: boolean;
  /** Start from this shell; defaults to the element's `data-platform` (windows). */
  platform?: Platform;
  onMinimize?: () => void;
  onMaximize?: () => void;
  onRestore?: () => void;
  onClose?: () => void;
}

/**
 * Wire one `.nx-app-window`. Clicks are delegated from the root so caption
 * buttons and snap zones survive Livewire morphs; the hover/focus arming sits
 * on the maximize button found at init and simply goes quiet if that button
 * is ever replaced. Returns a cleanup that stops timers, listeners and
 * observers and tidies the flyout away.
 */
export function platformWindow(
  el: HTMLElement,
  { snap = true, holdMs = 500, autoRestore = true, platform, onMinimize, onMaximize, onRestore, onClose }: PlatformWindowOptions = {},
): Cleanup {
  if (!isBrowser) return () => {};

  const doc = el.ownerDocument;
  const menu = () => el.querySelector<HTMLElement>('.nx-app-snap-menu');
  const maxButton = () => el.querySelector<HTMLButtonElement>('.nx-app-caption-btn[data-kind="maximize"]');

  /* ---- State ------------------------------------------------------------------ */

  const states: readonly WindowState[] = ['rest', 'minimized', 'maximized'];
  let state: WindowState = states.includes(el.dataset.state as WindowState) ? (el.dataset.state as WindowState) : 'rest';
  el.dataset.state = state;

  let menuOpen = false;
  let armTimer: ReturnType<typeof setTimeout> | undefined;
  let shapeTimer: ReturnType<typeof setTimeout> | undefined;
  let restoreTimer: ReturnType<typeof setTimeout> | undefined;
  let closeTimer: ReturnType<typeof setTimeout> | undefined;
  let unplace: Cleanup = () => {};
  let undismiss: Cleanup = () => {};

  const setState = (next: WindowState) => {
    if (next === state) return;
    state = next;
    el.dataset.state = state;
    syncMaxLabel();
    if (next === 'minimized') onMinimize?.();
    else if (next === 'maximized') onMaximize?.();
    else onRestore?.();
  };

  /** Swap the maximize button's aria-label to match the state, from the two labels it carries. */
  const syncMaxLabel = () => {
    const button = maxButton();
    if (!button) return;
    const resting = button.getAttribute('data-label-maximize');
    const maximal = button.getAttribute('data-label-restore');
    if (resting && maximal) button.setAttribute('aria-label', state === 'maximized' ? maximal : resting);
  };

  /* ---- Snap flyout -------------------------------------------------------------- */

  const syncMenu = (open: boolean) => {
    if (menuOpen === open) return;
    menuOpen = open;
    maxButton()?.setAttribute('aria-expanded', String(open));
  };

  const openMenu = () => {
    const panel = menu();
    const button = maxButton();
    if (!panel || !button || menuOpen) return;
    if (supportsPopover()) {
      try {
        panel.showPopover();
      } catch {
        /* already open */
      }
      // A popover lives fixed in the top layer; keep it pinned under its button.
      unplace = place(button, panel, { side: 'bottom', align: 'end', offset: 8 });
    } else {
      panel.setAttribute('data-open', '');
      const wrap = panel.closest<HTMLElement>('.nx-app-snap');
      undismiss = lightDismiss(panel, () => closeMenu(), { inside: wrap && wrap !== panel ? [wrap] : [panel] });
      syncMenu(true);
    }
  };

  const closeMenu = () => {
    undismiss();
    undismiss = () => {};
    unplace();
    unplace = () => {};
    const panel = menu();
    if (panel && menuOpen) {
      if (supportsPopover()) {
        try {
          if (panel.matches(':popover-open')) panel.hidePopover();
        } catch {
          /* already closed */
        }
      } else panel.removeAttribute('data-open');
      syncMenu(false);
      if (doc.activeElement && panel.contains(doc.activeElement)) maxButton()?.focus();
    }
  };

  /** A held button opens the flyout; leaving, blurring or releasing before `holdMs` cancels. */
  const arm = () => {
    if (!snap || menuOpen) return;
    clearTimeout(armTimer);
    armTimer = setTimeout(() => {
      armTimer = undefined;
      openMenu();
    }, Math.max(0, holdMs));
  };
  const disarm = () => {
    clearTimeout(armTimer);
    armTimer = undefined;
  };

  /** Flash the window into a snap shape, then let it settle back after 950ms. */
  const pickShape = (shape: string) => {
    if (!(snapShapes as readonly string[]).includes(shape)) return;
    closeMenu();
    el.dataset.shape = shape;
    clearTimeout(shapeTimer);
    shapeTimer = setTimeout(() => {
      el.removeAttribute('data-shape');
      shapeTimer = undefined;
    }, 950);
  };

  /* ---- Caption buttons ------------------------------------------------------------ */

  const minimize = () => {
    if (state === 'minimized') {
      // The manual way back when autoRestore is off.
      setState('rest');
      return;
    }
    setState('minimized');
    clearTimeout(restoreTimer);
    if (autoRestore) restoreTimer = setTimeout(() => setState('rest'), 1100);
  };

  const toggleMaximize = () => {
    // A click right after the hold opened the flyout only folds it away.
    if (menuOpen) {
      closeMenu();
      return;
    }
    setState(state === 'maximized' ? 'rest' : 'maximized');
  };

  const close = () => {
    onClose?.();
    el.dispatchEvent(new CustomEvent(WINDOW_CLOSE_EVENT, { bubbles: true }));
    el.setAttribute('data-closed', '');
    clearTimeout(closeTimer);
    if (autoRestore) {
      closeTimer = setTimeout(() => {
        el.removeAttribute('data-closed');
        setState('rest');
      }, 800);
    }
  };

  const onClick = (event: Event) => {
    const target = event.target as Element | null;
    if (!target) return;
    const zone = target.closest<HTMLButtonElement>('.nx-app-snap-layout button[data-shape]');
    if (zone) {
      pickShape(zone.dataset.shape ?? '');
      return;
    }
    const button = target.closest<HTMLElement>('.nx-app-caption-btn[data-kind]');
    if (!button) return;
    const kind = button.dataset.kind;
    if (kind === 'minimize') minimize();
    else if (kind === 'maximize') toggleMaximize();
    else if (kind === 'close') close();
  };

  const onKeydown = (event: Event) => {
    if ((event as KeyboardEvent).key !== 'Escape') return;
    disarm();
    if (menuOpen) closeMenu();
  };

  const onWrapLeave = () => {
    disarm();
    if (menuOpen) closeMenu();
  };

  const onToggle = (event: Event) => syncMenu((event as Event & { newState?: string }).newState === 'open');

  el.addEventListener('click', onClick);
  doc.addEventListener('keydown', onKeydown);

  const button = maxButton();
  if (button) {
    button.setAttribute('aria-expanded', 'false');
    button.addEventListener('pointerenter', arm);
    button.addEventListener('pointerleave', disarm);
    button.addEventListener('pointerdown', arm);
    button.addEventListener('pointerup', disarm);
    button.addEventListener('focus', arm);
    button.addEventListener('blur', disarm);
  }
  const wrap = el.querySelector<HTMLElement>('.nx-app-snap');
  wrap?.addEventListener('mouseleave', onWrapLeave);
  menu()?.addEventListener('toggle', onToggle);
  syncMaxLabel();

  /* ---- Platform shell -------------------------------------------------------------- */

  let currentPlatform: Platform = isPlatform(platform) ? platform : isPlatform(el.getAttribute('data-platform')) ? (el.getAttribute('data-platform') as Platform) : 'windows';
  if (el.getAttribute('data-platform') !== currentPlatform) el.dataset.platform = currentPlatform;

  // Wrappers write data-platform (Alpine bind, React re-render); follow along
  // and tell the page which shell the window now wears.
  const platformObserver = new MutationObserver(() => {
    const value = el.getAttribute('data-platform');
    if (!isPlatform(value) || value === currentPlatform) return;
    currentPlatform = value;
    el.dispatchEvent(new CustomEvent(PLATFORM_EVENT, { detail: { platform: currentPlatform }, bubbles: true }));
  });
  platformObserver.observe(el, { attributeFilter: ['data-platform'] });

  return () => {
    clearTimeout(armTimer);
    clearTimeout(shapeTimer);
    clearTimeout(restoreTimer);
    clearTimeout(closeTimer);
    armTimer = shapeTimer = restoreTimer = closeTimer = undefined;
    undismiss();
    unplace();
    platformObserver.disconnect();
    doc.removeEventListener('keydown', onKeydown);
    el.removeEventListener('click', onClick);
    if (button) {
      button.removeEventListener('pointerenter', arm);
      button.removeEventListener('pointerleave', disarm);
      button.removeEventListener('pointerdown', arm);
      button.removeEventListener('pointerup', disarm);
      button.removeEventListener('focus', arm);
      button.removeEventListener('blur', disarm);
    }
    wrap?.removeEventListener('mouseleave', onWrapLeave);
    menu()?.removeEventListener('toggle', onToggle);
    // A flyout left open through the fallback must not linger in the DOM.
    const panel = menu();
    if (panel && !supportsPopover()) panel.removeAttribute('data-open');
  };
}
