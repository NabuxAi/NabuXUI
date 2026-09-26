import { type Cleanup, isBrowser, markScripted } from './env';

export interface RevealOptions {
  /** Reveal once and stop watching (default), or hide again when scrolled away. */
  once?: boolean;
  /** How much of the element must be visible, 0–1. */
  threshold?: number;
  /** Shrinks the viewport so reveals start a little before the element is fully in. */
  rootMargin?: string;
  /** Give each direct child an index (--nx-i) so their transitions stagger. */
  stagger?: boolean | string;
  /** Called the first time the element is revealed. */
  onReveal?: () => void;
}

type Entry = { options: RevealOptions; revealed: boolean };

const watched = new WeakMap<Element, Entry>();
const observers = new Map<string, IntersectionObserver>();

function observerFor(threshold: number, rootMargin: string): IntersectionObserver {
  const key = `${threshold}|${rootMargin}`;
  let observer = observers.get(key);
  if (observer) return observer;

  observer = new IntersectionObserver(
    (entries) => {
      for (const entry of entries) {
        const target = entry.target;
        const state = watched.get(target);
        if (!state) continue;

        if (entry.isIntersecting) {
          target.setAttribute('data-nx-revealed', '');
          if (!state.revealed) state.options.onReveal?.();
          state.revealed = true;
          if (state.options.once !== false) observer!.unobserve(target);
        } else if (state.options.once === false) {
          target.removeAttribute('data-nx-revealed');
        }
      }
    },
    { threshold, rootMargin },
  );
  observers.set(key, observer);
  return observer;
}

/** Index children so CSS can stagger them: `transition-delay: calc(var(--nx-i) * var(--nx-stagger))`. */
export function staggerChildren(el: Element, selector?: string): void {
  const children = selector ? el.querySelectorAll<HTMLElement>(selector) : (el.children as HTMLCollectionOf<HTMLElement>);
  Array.from(children).forEach((child, index) => {
    if (!child.style.getPropertyValue('--nx-i')) child.style.setProperty('--nx-i', String(index));
  });
}

/**
 * Reveal an element (carrying `data-nx-reveal`) when it scrolls into view.
 * Elements already in view reveal on the next frame, so they still animate in.
 */
export function reveal(el: Element, options: RevealOptions = {}): Cleanup {
  if (!isBrowser) return () => {};
  markScripted();

  if (!el.hasAttribute('data-nx-reveal')) el.setAttribute('data-nx-reveal', '');
  if (options.stagger) staggerChildren(el, typeof options.stagger === 'string' ? options.stagger : undefined);

  if (!('IntersectionObserver' in window)) {
    el.setAttribute('data-nx-revealed', '');
    return () => {};
  }

  const observer = observerFor(options.threshold ?? 0.15, options.rootMargin ?? '0px 0px -8% 0px');
  watched.set(el, { options, revealed: el.hasAttribute('data-nx-revealed') });
  observer.observe(el);

  return () => {
    observer.unobserve(el);
    watched.delete(el);
  };
}
