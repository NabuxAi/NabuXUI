/**
 * Alpine part for the chip-filter block. The Blade component renders the
 * radios; this moves the accent thumb (core `indicator`) under the checked
 * one — the same wiring as the segmented control, plus an `nx-change` event
 * for listeners and a catch-up after Livewire morphs the checked radio.
 */
import { indicator } from '@nabuxai/ui-core';
import type { AlpineLike, Magics } from '../types';

type Self<T> = T & Magics;
type Ind = ReturnType<typeof indicator>;

/** Smooth the picked chip into view, unless the reader opted out of motion. */
const reveal = (chip: Element | null): void => {
  if (!chip || typeof matchMedia !== 'function') return;
  const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
  chip.scrollIntoView({ block: 'nearest', inline: 'nearest', behavior: reduce ? 'auto' : 'smooth' });
};

export function installChipFilterBlocks(Alpine: AlpineLike): void {
  /* ---- Chip filter: native radios; the thumb follows the checked one --------------- */
  // The radios carry wire:model themselves; this part only follows them.
  Alpine.data('nxChipFilter', () => ({
    ind: null as Ind | null,
    onMorph: null as (() => void) | null,

    init(this: Self<{ ind: Ind | null; onMorph: (() => void) | null; move: (scroll?: boolean) => void }>) {
      const row = this.$refs.row;
      this.ind = indicator(row);
      this.move();
      this.$root.addEventListener('change', (event: Event) => {
        const input = event.target as HTMLInputElement;
        if (!input.matches('.nx-chip-filter-input')) return;
        this.move(true);
        this.$dispatch('nx-change', { value: input.value });
      });
      // Livewire may set the checked radio from the server.
      this.onMorph = () => this.$nextTick(() => this.move());
      document.addEventListener('livewire:morph.updated', this.onMorph);
    },

    destroy(this: { ind: Ind | null; onMorph: (() => void) | null }) {
      this.ind?.destroy();
      if (this.onMorph) document.removeEventListener('livewire:morph.updated', this.onMorph);
    },

    move(this: Self<{ ind: Ind | null }>, scroll = false) {
      const chip = this.$refs.row.querySelector('.nx-chip-filter-chip:has(:checked)');
      this.ind?.update(chip);
      if (scroll) reveal(chip);
    },
  }));
}
