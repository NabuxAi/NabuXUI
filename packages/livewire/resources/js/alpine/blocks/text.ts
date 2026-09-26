/**
 * Alpine parts for the text & hero motion blocks. Each wraps one core
 * behaviour, so a Blade block moves exactly like its React twin; `destroy`
 * cleans up when Livewire morphs the element away.
 *
 * The text hero, roll text and pulse button need none of their own: they use
 * x-nx-reveal, pure CSS and x-nx-magnetic.
 */
import { type DitherOptions, dither, hoverReveal, scrollScramble, stickyProgress } from '@nabuxai/ui-core';
import type { AlpineLike, Magics } from '../types';

type Self<T> = T & Magics;
type Running = { stop: () => void };

export function installTextBlocks(Alpine: AlpineLike): void {
  /* ---- Scroll text reveal: writes --nx-progress where CSS scroll timelines are missing ---- */
  Alpine.data('nxScrollTextReveal', () => ({
    stop: () => {},
    init(this: Self<Running>) {
      this.stop = stickyProgress(this.$root);
    },
    destroy(this: Running) {
      this.stop();
    },
  }));

  /* ---- Scroll scramble: the headline decodes with the scroll, chips fly into place ---------- */
  Alpine.data('nxScrollScramble', (text: string, charset: string | null = null) => ({
    stop: () => {},
    init(this: Self<Running>) {
      this.stop = scrollScramble(this.$root, { text, charset: charset ?? undefined });
    },
    destroy(this: Running) {
      this.stop();
    },
  }));

  /* ---- Hover reveal: the glow follows the pointer over the active link ---------------------- */
  Alpine.data('nxHoverReveal', () => ({
    stop: () => {},
    init(this: Self<Running>) {
      this.stop = hoverReveal(this.$root);
    },
    destroy(this: Running) {
      this.stop();
    },
  }));

  /* ---- Dither backdrop: the animated canvas ---------------------------------------------------- */
  Alpine.data('nxDither', (options: DitherOptions = {}) => ({
    stop: () => {},
    init(this: Self<Running>) {
      const canvas = this.$refs.canvas as HTMLCanvasElement | undefined;
      if (canvas) this.stop = dither(canvas, options);
    },
    destroy(this: Running) {
      this.stop();
    },
  }));
}
