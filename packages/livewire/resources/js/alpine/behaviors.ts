/**
 * x-nx-* directives: each wraps one core behaviour, so a Blade component gets
 * exactly the motion its React twin gets, and Alpine cleans it up when Livewire
 * morphs the element away.
 */
import { autogrow, dock, headerScroll, magnetic, reveal, ripple, spotlight, theme, tilt, toast } from '@nabuxai/ui-core';
import type { AlpineLike } from './types';

export function installBehaviors(Alpine: AlpineLike): void {
  // x-nx-reveal            reveal the element when it scrolls into view
  // x-nx-reveal.group      reveal its children one after another
  // x-nx-reveal.repeat     hide again when it leaves the view
  Alpine.directive('nx-reveal', (el, { modifiers }, { cleanup }) => {
    if (!el.hasAttribute('data-nx-reveal')) el.setAttribute('data-nx-reveal', modifiers.includes('group') ? 'group' : '');
    cleanup(reveal(el, { stagger: modifiers.includes('group'), once: !modifiers.includes('repeat') }));
  });

  Alpine.directive('nx-magnetic', (el, { expression }, { cleanup, evaluate }) => {
    cleanup(magnetic(el, expression ? { strength: Number(evaluate(expression)) } : {}));
  });

  Alpine.directive('nx-tilt', (el, { expression }, { cleanup, evaluate }) => {
    cleanup(tilt(el, expression ? { max: Number(evaluate(expression)) } : {}));
  });

  Alpine.directive('nx-spotlight', (el, _meta, { cleanup }) => cleanup(spotlight(el)));
  Alpine.directive('nx-ripple', (el, _meta, { cleanup }) => cleanup(ripple(el)));
  Alpine.directive('nx-dock', (el, _meta, { cleanup }) => cleanup(dock(el)));
  Alpine.directive('nx-autogrow', (el, _meta, { cleanup }) => cleanup(autogrow(el as HTMLTextAreaElement)));

  // x-nx-header / x-nx-header.hide
  Alpine.directive('nx-header', (el, { modifiers }, { cleanup }) => cleanup(headerScroll(el, { hide: modifiers.includes('hide') })));

  // $nxToast('Saved') · $nxToast.success(…) · $nxTheme.toggle()
  Alpine.magic('nxToast', () => toast);
  Alpine.magic('nxTheme', () => theme);
}
