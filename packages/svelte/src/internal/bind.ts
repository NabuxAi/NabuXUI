import type { Cleanup } from '@nabuxai/ui-core';
import type { Action } from 'svelte/action';

/**
 * Wrap one core behaviour as a plain Svelte action — `nxB(reveal, { once: true })`
 * gives an action that forwards `(node, params)` straight to the behaviour and
 * returns its cleanup:
 *
 *   const revealOnce = nxB(reveal, { once: true });
 *   <figure use:revealOnce>
 *
 * The element type stays as wide as the behaviour's own (Element for reveal, so
 * it also binds to svgs the HTMLElement-typed `nxBehavior` cannot reach).
 */
export function nxB<E extends Element, O>(behavior: (el: E, options: O) => Cleanup, defaults?: O): Action<E, O | undefined> {
  const action = (node: E, params?: O) => behavior(node, (params ?? defaults) as O);
  return action as Action<E, O | undefined>;
}
