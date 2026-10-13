/**
 * Alpine part for the order-tracking block. The Blade component renders the
 * route, the event log and the live status server-side; this reveals them (the
 * sections first, then the event rows), keeps the relative times drifting, and
 * — when the route is wider than its row — scrolls the current step into view.
 * The status badge morphs on its own (nxStatusBadge) when Livewire re-renders.
 *
 * Register from the package's installer when the block is wired up:
 *
 *   import { installOrderTrackingBlocks } from './alpine/blocks/order-tracking';
 *   installOrderTrackingBlocks(Alpine);   // x-data="nxOrderTracking(config)"
 */
import { activityTime, reveal } from '@nabuxai/ui-core';
import type { AlpineLike, Magics } from '../types';

type Self<T> = T & Magics;

export interface OrderTrackingConfig {
  /** An Intl locale for the drifting relative times ("fa-IR"). */
  locale?: string;
}

export function installOrderTrackingBlocks(Alpine: AlpineLike): void {
  Alpine.data('nxOrderTracking', (config: OrderTrackingConfig = {}) => {
    let timer: number | undefined;

    interface OrderTrackingSelf {
      $root: HTMLElement;
      paintTimes: () => void;
    }

    return {
      init(this: Self<OrderTrackingSelf>) {
        const root = this.$root;
        reveal(root, { once: true, stagger: true });
        const log = root.querySelector<HTMLElement>('.nx-order-tracking-events-list');
        if (log) reveal(log, { once: true, stagger: true });

        // A long route scrolls; bring the current step into the frame.
        const row = root.querySelector<HTMLElement>('.nx-order-tracking-steps');
        const here = root.querySelector<HTMLElement>('.nx-order-tracking-step[data-state="current"] .nx-order-tracking-marker');
        if (row && here && row.scrollWidth > row.clientWidth + 1) {
          const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
          here.scrollIntoView({ block: 'nearest', inline: 'center', behavior: reduce ? 'auto' : 'smooth' });
        }

        this.paintTimes();
        timer = window.setInterval(() => this.paintTimes(), 30_000);
      },

      destroy() {
        window.clearInterval(timer);
      },

      /** "۳ دقیقه پیش" → "۴ دقیقه پیش": every dated time in the block, re-read from its datetime. */
      paintTimes(this: Self<OrderTrackingSelf>) {
        for (const el of this.$root.querySelectorAll<HTMLElement>('time[datetime]')) {
          const at = Date.parse(el.getAttribute('datetime') ?? '');
          if (!Number.isNaN(at)) el.textContent = activityTime(at, Date.now(), config.locale);
        }
      },
    };
  });
}
