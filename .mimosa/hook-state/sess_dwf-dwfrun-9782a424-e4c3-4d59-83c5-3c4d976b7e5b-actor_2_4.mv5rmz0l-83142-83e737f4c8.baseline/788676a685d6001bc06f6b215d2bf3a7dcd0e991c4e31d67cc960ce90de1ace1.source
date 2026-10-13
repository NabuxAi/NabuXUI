/**
 * Run a DOM update inside a View Transition when the browser can, and fall back
 * to a plain update when it cannot or the reader prefers reduced motion.
 * After the update, focus moves to `focus` (a heading or the new view), since
 * a transition that swaps content must not strand keyboard focus.
 */
import { isBrowser, prefersReducedMotion } from './env';

export interface TransitionOptions {
  /** Types for :active-view-transition-type(), e.g. ['forward'] or ['back']. */
  types?: string[];
  /** Element (or a getter, resolved after the update) that should take focus. */
  focus?: Element | null | (() => Element | null);
}

type Start = (arg: unknown) => { finished: Promise<void>; updateCallbackDone: Promise<void> };

function moveFocus(focus: TransitionOptions['focus']) {
  const target = typeof focus === 'function' ? focus() : focus;
  if (target instanceof HTMLElement) {
    if (!target.hasAttribute('tabindex') && !target.matches('a, button, input, select, textarea')) target.setAttribute('tabindex', '-1');
    target.focus({ preventScroll: true });
  }
}

export function supportsViewTransitions(): boolean {
  return isBrowser && typeof (document as Document & { startViewTransition?: unknown }).startViewTransition === 'function';
}

export async function transition(update: () => void | Promise<void>, { types, focus }: TransitionOptions = {}): Promise<void> {
  if (!supportsViewTransitions() || prefersReducedMotion()) {
    await update();
    moveFocus(focus);
    return;
  }

  const start = (document as unknown as { startViewTransition: Start }).startViewTransition.bind(document);
  let vt: ReturnType<Start>;
  try {
    vt = start(types?.length ? { update, types } : update);
  } catch {
    // Browsers that know the API but not the object form (types).
    vt = start(update);
  }

  try {
    await vt.updateCallbackDone;
  } finally {
    moveFocus(focus);
  }
  await vt.finished.catch(() => {});
}
