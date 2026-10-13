/**
 * Exit animations for elements the framework is about to remove.
 *
 * Set the leaving state (data-state="closing" by default), wait for the CSS
 * transitions or animations it starts to finish, then resolve so the caller can
 * remove the element. A timeout guards against a transition that never ends
 * (display: none, reduced motion, a tab in the background).
 */
import { isBrowser, prefersReducedMotion } from './env';

export interface LeaveOptions {
  attribute?: string;
  value?: string;
  /** Upper bound in ms. */
  timeout?: number;
}

export function leave(el: Element, { attribute = 'data-state', value = 'closing', timeout = 900 }: LeaveOptions = {}): Promise<void> {
  if (!isBrowser) return Promise.resolve();
  el.setAttribute(attribute, value);
  if (prefersReducedMotion() && timeout > 250) timeout = 250;

  return new Promise((resolve) => {
    let done = false;
    const finish = () => {
      if (done) return;
      done = true;
      clearTimeout(timer);
      resolve();
    };

    const timer = setTimeout(finish, timeout);

    // Wait for everything the state change actually started.
    requestAnimationFrame(() => {
      const running = typeof (el as HTMLElement).getAnimations === 'function' ? (el as HTMLElement).getAnimations() : [];
      if (running.length === 0) {
        finish();
        return;
      }
      Promise.allSettled(running.map((animation) => animation.finished)).then(finish);
    });
  });
}
