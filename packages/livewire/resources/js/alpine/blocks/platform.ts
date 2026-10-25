/**
 * Alpine part for the app-window block. The Blade component renders one app
 * window whose shell is skinned by data-platform (windows | macos | android |
 * ios); the whole state machine — minimize / maximize / restore, the Windows
 * snap-layouts menu on a held maximize, the nx-window-close event and the
 * data-platform ⇄ platform sync with its nx-platform-change event — lives in
 * the core platformWindow behaviour (popover guard included). This wrapper
 * feeds it the component's options and adds what Alpine is for: x-modelable
 * active (wire:model binds the current nav item's id), the spring nav
 * indicator (core `indicator`, one per .nx-app-nav), the $watch('platform')
 * reflection back onto the attribute, and a re-scan after Livewire morphs.
 *
 * Registered in ../../index.ts: installPlatformBlocks(Alpine) runs with the
 * other blocks on alpine:init, so any page that ships the bundle gets this
 * wiring for <x-nx::app-window> automatically.
 */
import { type Cleanup, type Platform, indicator, platformWindow } from '@nabuxai/ui-core';
import { afterMorph } from '../morph';
import type { AlpineLike, Magics } from '../types';

type Self<T> = T & Magics;

interface NavPart {
  el: HTMLElement;
  ctrl: ReturnType<typeof indicator>;
}

export function installPlatformBlocks(Alpine: AlpineLike): void {
  /* ---- App window -----------------------------------------------------------------
   * x-data="nxAppWindow(platform, active, snap)" x-modelable="active" on .nx-app-window.
   */
  Alpine.data(
    'nxAppWindow',
    (platform: Platform = 'windows', active = '', snap = true) => {
      let cleanup: Cleanup | null = null;
      let onMorph: (() => void) | null = null;
      let navs: NavPart[] = [];

      return {
        platform,
        active,

        init(this: Self<{ platform: Platform; active: string; rescan: () => void; move: () => void }>) {
          // The core behaviour owns data-state/data-shape, the caption buttons,
          // the snap menu and the MutationObserver that keeps an outside
          // data-platform bind in sync; options are the component's props.
          cleanup = platformWindow(this.$root, { snap });

          this.rescan();
          this.move();

          this.$watch('active', () => this.move());
          // Alpine state → attribute. The way back (someone binds data-platform
          // from outside) goes through the core's observer, which re-skins the
          // window and fires nx-platform-change.
          this.$watch('platform', (value: Platform) => {
            if (value) this.$root.setAttribute('data-platform', value);
          });

          // Livewire may re-render the nav from the server; re-scan and re-place.
          onMorph = afterMorph(this.$root, () => {
            this.rescan();
            this.move();
          });
        },

        destroy(this: Self<{ rescan: () => void }>) {
          this.rescan();
          cleanup?.();
          cleanup = null;
          if (onMorph) onMorph();
        },

        /** (Re)attach one indicator per nav in the window (the sidebar's). */
        rescan(this: Self<{ $root: HTMLElement }>) {
          navs.forEach(({ ctrl }) => ctrl.destroy());
          navs = Array.from(this.$root.querySelectorAll<HTMLElement>('.nx-app-nav')).map((el) => ({ el, ctrl: indicator(el) }));
        },

        /** Move the highlight to the current item. */
        move(this: Self<{ active: string }>) {
          const id = CSS.escape(String(this.active ?? ''));
          for (const { el, ctrl } of navs) ctrl.update(el.querySelector(`[data-value="${id}"]`));
        },

        /** An item was picked (sidebar or tab bar): make it current, tell the page. */
        select(this: Self<{ active: string; $dispatch: Magics['$dispatch'] }>, id: string) {
          this.active = id;
          this.$dispatch('nx-select', id);
        },
      };
    },
  );
}
