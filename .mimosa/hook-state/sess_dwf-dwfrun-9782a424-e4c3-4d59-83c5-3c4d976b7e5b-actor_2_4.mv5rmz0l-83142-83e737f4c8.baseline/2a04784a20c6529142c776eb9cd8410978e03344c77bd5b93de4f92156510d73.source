/** Small facts about the page every behaviour needs. */

export type Cleanup = () => void;

export const isBrowser = typeof window !== 'undefined' && typeof document !== 'undefined';

const REDUCE = '(prefers-reduced-motion: reduce)';

export function prefersReducedMotion(): boolean {
  return isBrowser && window.matchMedia(REDUCE).matches;
}

/** Calls `callback` whenever the reduced-motion preference changes. */
export function onReducedMotionChange(callback: (reduce: boolean) => void): Cleanup {
  if (!isBrowser) return () => {};
  const query = window.matchMedia(REDUCE);
  const listener = (event: MediaQueryListEvent) => callback(event.matches);
  query.addEventListener('change', listener);
  return () => query.removeEventListener('change', listener);
}

/** 1 for left-to-right, -1 for right-to-left, read from the element's own context. */
export function direction(el: Element): 1 | -1 {
  return getComputedStyle(el).direction === 'rtl' ? -1 : 1;
}

/** Whether the primary pointer can hover (a mouse or trackpad, not a finger). */
export function canHover(): boolean {
  return isBrowser && window.matchMedia('(hover: hover) and (pointer: fine)').matches;
}

/**
 * Marks the document as scripted. Content that reveals on scroll is only hidden
 * once `.nx-js` is present, and `.nx-live` switches off the CSS failsafe that
 * would otherwise show it after 2.5s. The head snippet sets `.nx-js` before the
 * first paint; this covers pages that do not include it.
 */
export function markScripted(): void {
  if (!isBrowser) return;
  const root = document.documentElement.classList;
  root.add('nx-js', 'nx-live');
}

let uid = 0;
/** A process-unique id for aria wiring when the caller did not supply one. */
export function nextId(prefix = 'nx'): string {
  uid += 1;
  return `${prefix}-${uid.toString(36)}`;
}

/** Resolve on the next animation frame (after styles for the current change apply). */
export function nextFrame(): Promise<void> {
  return new Promise((resolve) => requestAnimationFrame(() => requestAnimationFrame(() => resolve())));
}
