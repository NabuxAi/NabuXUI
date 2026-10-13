/**
 * Alpine parts for the liquid-glass family. The optics and gestures are the
 * core behaviours the React components use; here they are directives, plus
 * small x-data objects where a value is bound to Livewire.
 */
import {
  type GlassController,
  followLight,
  glassDock,
  glassPane,
  glassSegmented,
  glassSlider,
  glassSwitch,
  glassTabBar,
  liquidRipple,
  readingGlass,
  sweepLight,
} from '@nabuxai/ui-core';
import type { AlpineLike, Magics } from '../types';

type WithController = Magics & { ctrl: GlassController | null };

export function installGlassBlocks(Alpine: AlpineLike): void {
  // x-nx-glass            bend the backdrop at the rim (Chromium; frosted elsewhere)
  // x-nx-glass.follow     …and turn the rim light toward the pointer
  Alpine.directive('nx-glass', (el, { modifiers }, { cleanup }) => {
    cleanup(glassPane(el));
    if (modifiers.includes('follow')) cleanup(followLight(el));
  });

  Alpine.directive('nx-glass-dock', (el, _meta, { cleanup }) => cleanup(glassDock(el)));
  Alpine.directive('nx-glass-switch', (el, _meta, { cleanup }) => cleanup(glassSwitch(el)));

  // x-nx-liquid-ripple="{ strength: 18, duration: 1100 }"
  Alpine.directive('nx-liquid-ripple', (el, { expression }, { cleanup, evaluate }) => {
    cleanup(liquidRipple(el, expression ? (evaluate(expression) as Parameters<typeof liquidRipple>[1]) : {}));
  });

  // x-nx-reading-glass="{ x: 0.5, y: 0.3 }"
  Alpine.directive('nx-reading-glass', (el, { expression }, { cleanup, evaluate }) => {
    cleanup(readingGlass(el, expression ? (evaluate(expression) as Parameters<typeof readingGlass>[1]) : {}));
  });

  // $nxSweepLight(): send the light once around every glass rim.
  Alpine.magic('nxSweepLight', () => sweepLight);

  // x-data="nxGlassSegmented(value)" — value may be @entangle'd with Livewire.
  Alpine.data('nxGlassSegmented', (value: string) => ({
    value,
    ctrl: null as GlassController | null,
    init(this: WithController & { value: string }) {
      this.ctrl = glassSegmented(this.$el, {
        onPick: (next) => {
          this.value = next;
        },
      });
      // Bindings on the children (x-model, x-bind:checked) apply after init: measure once they have.
      this.$nextTick(() => this.ctrl?.refresh());
      this.$watch('value', () => this.$nextTick(() => this.ctrl?.refresh()));
    },
    destroy(this: WithController) {
      this.ctrl?.destroy();
    },
  }));

  // x-data="nxGlassSlider(value)" — the input carries x-model; --p and the lens follow it.
  Alpine.data('nxGlassSlider', (value: number) => ({
    value,
    ctrl: null as GlassController | null,
    init(this: WithController) {
      this.ctrl = glassSlider(this.$el);
      // Bindings on the children (x-model, x-bind:checked) apply after init: measure once they have.
      this.$nextTick(() => this.ctrl?.refresh());
      this.$watch('value', () => this.$nextTick(() => this.ctrl?.refresh()));
    },
    destroy(this: WithController) {
      this.ctrl?.destroy();
    },
  }));

  // x-data="nxGlassTabBar(value, minimize)" — minimize: true (window) or a selector for a scrolling container.
  Alpine.data('nxGlassTabBar', (value: string, minimize: boolean | string = false) => ({
    value,
    ctrl: null as GlassController | null,
    init(this: WithController) {
      const scroller = typeof minimize === 'string' ? document.querySelector<HTMLElement>(minimize) : minimize;
      this.ctrl = glassTabBar(this.$el, { minimize: scroller });
      // Bindings on the children (x-model, x-bind:checked) apply after init: measure once they have.
      this.$nextTick(() => this.ctrl?.refresh());
      this.$watch('value', () => this.$nextTick(() => this.ctrl?.refresh()));
    },
    destroy(this: WithController) {
      this.ctrl?.destroy();
    },
  }));
}
