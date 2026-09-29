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
import { installTextBlocks } from './alpine/blocks/text';
import { installActionsBlocks } from './alpine/blocks/actions';
import { installCardsBlocks } from './alpine/blocks/cards';
import { installDataBlocks } from './alpine/blocks/data';
import { installMenusBlocks } from './alpine/blocks/menus';
import { installChipFilterBlocks } from './alpine/blocks/chip-filter';
import { installStatStripBlocks } from './alpine/blocks/stat-strip';
import { installSortPillBlocks } from './alpine/blocks/sort-pill';
import { installGlassBlocks } from './alpine/blocks/glass';


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
  installTextBlocks(Alpine);
  installActionsBlocks(Alpine);
  installCardsBlocks(Alpine);
  installDataBlocks(Alpine);
  installMenusBlocks(Alpine);
  installChipFilterBlocks(Alpine);
  installStatStripBlocks(Alpine);
  installSortPillBlocks(Alpine);
  installGlassBlocks(Alpine);
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
