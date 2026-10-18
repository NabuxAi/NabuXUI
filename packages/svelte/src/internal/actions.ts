import type { Cleanup } from '@nabuxai/ui-core';
import type { Action } from 'svelte/action';

export interface NxBehaviorParams {
  /**
   * A core behaviour: takes the element, returns its cleanup. The options slot
   * is typed `any` on purpose — it bridges the option shape of every behaviour
   * the core ships (RevealOptions, MagneticOptions, …) into one directive.
   */
  behavior: (el: HTMLElement, options: any) => Cleanup;
  options: any;
  enabled?: boolean;
}

/**
 * Attach a core behaviour (reveal, magnetic, tilt…) to an element for as long
 * as it is mounted — the Svelte counterpart of ui-react's `useBehavior`:
 *
 *   <section use:nxBehavior={{ behavior: reveal, options: { stagger: true } }}>
 *
 * `update` re-attaches when the params change; `destroy` cleans up.
 */
export const nxBehavior: Action<HTMLElement, NxBehaviorParams> = (node, params) => {
  let cleanup: Cleanup | undefined;

  const attach = (next: NxBehaviorParams) => {
    cleanup?.();
    cleanup = next.enabled === false ? undefined : next.behavior(node, next.options);
  };

  attach(params);

  return {
    update: attach,
    destroy: () => cleanup?.(),
  };
};
