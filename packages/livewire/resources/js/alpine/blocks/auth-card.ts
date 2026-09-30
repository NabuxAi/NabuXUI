/**
 * Alpine part for the auth card. The Blade component renders all three
 * panes (login | register | forgot); this moves between them: the panes
 * glide past each other while the viewport's height morphs (core
 * `morphShell` writes --nx-morph-h), focus lands on the pane that just
 * arrived, and the form only submits once its inputs are valid — a
 * `wire:submit` (or a plain POST) then goes through untouched.
 */
import { type Cleanup, morphShell } from '@nabuxai/ui-core';
import type { AlpineLike, Magics } from '../types';

type Mode = 'login' | 'register' | 'forgot';
type Self<T> = T & Magics;

const MODES: Mode[] = ['login', 'register', 'forgot'];

export function installAuthCardBlocks(Alpine: AlpineLike): void {
  /* ---- Auth card: three panes, one form ------------------------------------------- */
  Alpine.data('nxAuthCard', (mode: Mode = 'login', labels: Record<string, { title?: string; submit?: string }> = {}) => ({
    mode: (MODES as string[]).includes(mode) ? mode : 'login',
    labels,
    unmorph: (() => {}) as Cleanup,

    init(this: Self<{ mode: Mode; unmorph: Cleanup }>) {
      this.unmorph = morphShell(this.$refs.viewport, { content: this.$refs.measure, axis: 'block' });
    },

    destroy(this: { unmorph: Cleanup }) {
      this.unmorph();
    },

    /** Where a pane sits: toward the reading start, current, or toward the end. */
    pos(this: { mode: Mode }, which: Mode) {
      return MODES.indexOf(which) < MODES.indexOf(this.mode) ? 'before' : which === this.mode ? 'current' : 'after';
    },

    /** Switch panes in the browser; focus follows the pane that arrives. */
    set(this: Self<{ mode: Mode }>, next: Mode) {
      if (!(MODES as string[]).includes(next) || next === this.mode) return;
      this.mode = next;
      this.$dispatch('nx-mode-change', { mode: next });
      this.$nextTick(() => {
        const pane = this.$root.querySelector<HTMLElement>(`[data-pane="${next}"]`);
        (pane?.querySelector<HTMLElement>('input') ?? pane)?.focus({ preventScroll: true });
      });
    },

    /** Let the submit through only when the current pane's inputs are valid. */
    submit(this: Self<{ mode: Mode }>, event: Event) {
      const form = this.$root as HTMLFormElement;
      if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
        form.reportValidity();
      }
    },
  }));
}
