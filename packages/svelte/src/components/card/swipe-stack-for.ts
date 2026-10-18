import type { Component, Snippet } from 'svelte';
import SwipeStack from './swipe-stack.svelte';

export type SwipeVerdict = 'accept' | 'reject';

export interface SwipeStackProps<T> {
  items: T[];
  /** How each card renders — a snippet over the item, like React's renderItem. */
  renderItem: Snippet<[T]>;
  getKey?: (item: T, index: number) => string | number;
  onSwipe?: (item: T, verdict: SwipeVerdict) => void;
  /** Swiped cards go to the back of the deck instead of leaving. */
  loop?: boolean;
  /** Show accept / reject buttons (the keyboard path). */
  actions?: boolean;
  acceptLabel?: string;
  rejectLabel?: string;
  class?: string;
}

/**
 * Svelte components cannot take type arguments at the use site the way React's
 * `SwipeStack<T>` does; this factory hands back the same component typed for
 * your item shape:
 *
 *   const Stack = SwipeStackFor<Product>();
 */
export function SwipeStackFor<T>(): Component<SwipeStackProps<T>> {
  return SwipeStack as Component<SwipeStackProps<T>>;
}
