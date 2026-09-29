/**
 * Alpine part for the stat strip block. The Blade component renders every
 * figure server-side with the current locale's digits; this holds each one at
 * zero until it scrolls into view, then rolls it up once — the same
 * reveal-driven count as the React package's NumberTicker. Livewire can change
 * a figure's data-value and the digits roll to the new number.
 */
import { type Cleanup, reveal } from '@nabuxai/ui-core';
import { renderNumber } from '../number';
import type { AlpineLike, Magics } from '../types';

type Self<T> = T & Magics;

export function installStatStripBlocks(Alpine: AlpineLike): void {
  /* ---- Stat strip ----------------------------------------------------------------- */
  Alpine.data('nxStatStrip', (lang: string | null = null) => ({
    shown: false,
    cleanups: [] as Cleanup[],
    // Whether each figure has rolled yet, so a Livewire morph can restore the flag.
    rolls: new WeakMap<HTMLElement, { rolled: boolean }>(),
    onMorph: () => {},

    init(this: Self<{ shown: boolean; cleanups: Cleanup[]; rolls: WeakMap<HTMLElement, { rolled: boolean }>; onMorph: () => void; sync: () => void }>) {
      // The items rise one after another; each figure rolls its own digits.
      this.cleanups.push(reveal(this.$root, { once: true, stagger: true, onReveal: () => (this.shown = true) }));
      this.sync();
      // Livewire morphs drop what scripts wrote: re-apply it and re-read the values.
      this.onMorph = () => this.sync();
      document.addEventListener('livewire:morph.updated', this.onMorph);
    },

    destroy(this: { cleanups: Cleanup[]; onMorph: () => void }) {
      this.cleanups.forEach((stop) => stop());
      document.removeEventListener('livewire:morph.updated', this.onMorph);
    },

    sync(this: Self<{ shown: boolean; cleanups: Cleanup[]; rolls: WeakMap<HTMLElement, { rolled: boolean }> }>) {
      // A morph wipes the reveal flags the observers wrote; restore them first.
      if (this.shown && !this.$root.hasAttribute('data-nx-revealed')) this.$root.setAttribute('data-nx-revealed', '');
      for (const el of Array.from(this.$root.querySelectorAll<HTMLElement>('.nx-stat-strip-value .nx-number[data-value]'))) {
        const known = this.rolls.get(el);
        const entry = known ?? { rolled: false };
        if (!known) {
          this.rolls.set(el, entry);
          this.cleanups.push(reveal(el, { once: true, onReveal: () => (entry.rolled = true) }));
        }
        if (entry.rolled && !el.hasAttribute('data-nx-revealed')) el.setAttribute('data-nx-revealed', '');
        renderNumber(el, Number(el.dataset.value) || 0, lang || undefined);
      }
    },
  }));
}
