/**
 * NabuXUI for Livewire: the core behaviours plus an Alpine plugin.
 *
 * Loaded as a module in <head> (@nabuxuiScripts), so it runs before Livewire
 * starts Alpine and registers everything on alpine:init.
 */
import * as core from '@nabuxai/ui-core';
import { installBehaviors } from './alpine/behaviors';
import { installDisplay } from './alpine/display';
import { installForms } from './alpine/forms';
import { installNavigation } from './alpine/navigation';
import { installOverlays } from './alpine/overlays';
import type { AlpineLike } from './alpine/types';

declare global {
  interface Window {
    Alpine?: AlpineLike;
    NabuXUI?: typeof core;
  }
}

window.NabuXUI = core;
core.markScripted();
core.theme.watch();

let installed = false;
function install(Alpine: AlpineLike | undefined) {
  if (!Alpine || installed) return;
  installed = true;
  installBehaviors(Alpine);
  installOverlays(Alpine);
  installNavigation(Alpine);
  installForms(Alpine);
  installDisplay(Alpine);
}

if (window.Alpine) install(window.Alpine);
document.addEventListener('alpine:init', () => install(window.Alpine));

/*
 * wire:navigate. Livewire 4 runs page swaps inside a View Transition when the
 * page marks its content with wire:transition.navigate="nx-page". Livewire 3
 * swaps instantly, so there the new page's .nx-page rises in instead.
 */
document.addEventListener('livewire:navigated', () => {
  const page = document.querySelector<HTMLElement>('.nx-page');
  const viewTransitions = typeof document.startViewTransition === 'function' && document.querySelector('[wire\\:transition\\.navigate]');
  if (!page || viewTransitions) return;
  page.setAttribute('data-nx-entering', '');
  page.addEventListener('animationend', () => page.removeAttribute('data-nx-entering'), { once: true });
});
