/**
 * Pointer behaviours. Each one only writes custom properties on the element;
 * the motion is in CSS (motion.css), so every framework gets the same feel.
 * They stand down for touch input and for reduced motion.
 */
import { type Cleanup, canHover, isBrowser, prefersReducedMotion } from './env';

function onPointer(el: HTMLElement, move: (event: PointerEvent, rect: DOMRect) => void, leave: () => void): Cleanup {
  let rect: DOMRect | null = null;
  let frame = 0;
  let last: PointerEvent | null = null;

  const flush = () => {
    frame = 0;
    if (last && rect) move(last, rect);
  };

  const onEnter = (event: PointerEvent) => {
    if (event.pointerType !== 'mouse' && event.pointerType !== 'pen') return;
    rect = el.getBoundingClientRect();
    el.setAttribute('data-nx-pointer', '');
  };

  const onMove = (event: PointerEvent) => {
    if (event.pointerType !== 'mouse' && event.pointerType !== 'pen') return;
    if (!rect) onEnter(event);
    last = event;
    if (!frame) frame = requestAnimationFrame(flush);
  };

  const onLeave = () => {
    if (frame) cancelAnimationFrame(frame);
    frame = 0;
    rect = null;
    last = null;
    el.removeAttribute('data-nx-pointer');
    leave();
  };

  el.addEventListener('pointerenter', onEnter);
  el.addEventListener('pointermove', onMove);
  el.addEventListener('pointerleave', onLeave);
  // Scrolling moves the element under a still pointer: measure again next time.
  const onScroll = () => { rect = null; };
  window.addEventListener('scroll', onScroll, { passive: true });

  return () => {
    onLeave();
    el.removeEventListener('pointerenter', onEnter);
    el.removeEventListener('pointermove', onMove);
    el.removeEventListener('pointerleave', onLeave);
    window.removeEventListener('scroll', onScroll);
  };
}

export interface MagneticOptions {
  /** Fraction of the pointer's offset from centre the element follows (0–1). */
  strength?: number;
}

/** The element leans toward the pointer and springs home when it leaves. */
export function magnetic(el: HTMLElement, { strength = 0.35 }: MagneticOptions = {}): Cleanup {
  if (!isBrowser || !canHover() || prefersReducedMotion()) return () => {};
  el.setAttribute('data-nx-magnetic', '');

  const stop = onPointer(
    el,
    (event, rect) => {
      const x = (event.clientX - (rect.left + rect.width / 2)) * strength;
      const y = (event.clientY - (rect.top + rect.height / 2)) * strength;
      el.style.setProperty('--nx-mx', `${x.toFixed(1)}px`);
      el.style.setProperty('--nx-my', `${y.toFixed(1)}px`);
    },
    () => {
      el.style.setProperty('--nx-mx', '0px');
      el.style.setProperty('--nx-my', '0px');
    },
  );

  return () => {
    stop();
    el.removeAttribute('data-nx-magnetic');
    el.style.removeProperty('--nx-mx');
    el.style.removeProperty('--nx-my');
  };
}

export interface TiltOptions {
  /** Largest rotation in degrees. */
  max?: number;
}

/** A 3D tilt that follows the pointer; also writes --nx-px/--nx-py for a glare. */
export function tilt(el: HTMLElement, { max = 8 }: TiltOptions = {}): Cleanup {
  if (!isBrowser || !canHover() || prefersReducedMotion()) return () => {};
  el.setAttribute('data-nx-tilt', '');

  const stop = onPointer(
    el,
    (event, rect) => {
      const px = (event.clientX - rect.left) / rect.width;
      const py = (event.clientY - rect.top) / rect.height;
      el.style.setProperty('--nx-rx', `${((0.5 - py) * max * 2).toFixed(2)}deg`);
      el.style.setProperty('--nx-ry', `${((px - 0.5) * max * 2).toFixed(2)}deg`);
      el.style.setProperty('--nx-px', `${(px * rect.width).toFixed(0)}px`);
      el.style.setProperty('--nx-py', `${(py * rect.height).toFixed(0)}px`);
    },
    () => {
      el.style.setProperty('--nx-rx', '0deg');
      el.style.setProperty('--nx-ry', '0deg');
    },
  );

  return () => {
    stop();
    el.removeAttribute('data-nx-tilt');
    for (const name of ['--nx-rx', '--nx-ry', '--nx-px', '--nx-py']) el.style.removeProperty(name);
  };
}

/**
 * Tracks the pointer inside the element as --nx-px / --nx-py (px), for the card
 * spotlight and the grid backdrop. Purely decorative, so it is fine on touch too.
 */
export function spotlight(el: HTMLElement): Cleanup {
  if (!isBrowser) return () => {};

  const onMove = (event: PointerEvent) => {
    const rect = el.getBoundingClientRect();
    el.style.setProperty('--nx-px', `${(event.clientX - rect.left).toFixed(0)}px`);
    el.style.setProperty('--nx-py', `${(event.clientY - rect.top).toFixed(0)}px`);
  };

  el.addEventListener('pointermove', onMove);
  return () => el.removeEventListener('pointermove', onMove);
}
