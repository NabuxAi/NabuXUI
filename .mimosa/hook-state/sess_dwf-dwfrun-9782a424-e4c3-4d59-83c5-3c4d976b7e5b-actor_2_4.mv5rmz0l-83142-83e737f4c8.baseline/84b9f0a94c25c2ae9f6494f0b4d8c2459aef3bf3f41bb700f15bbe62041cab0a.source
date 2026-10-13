/**
 * Framework-agnostic behaviours for the icon-motion blocks.
 *
 *   drawIn           inks line icons stroke by stroke: every stroke is normalised with
 *                    pathLength, hidden behind a dash, and drawn with a stepped delay
 *                    when the element scrolls into view
 *   redrawIn          blanks a drawn element for two frames so its strokes ink again
 *   pauseOffscreen    parks the looping icons (spinners, pulses) while their card is
 *                    outside the viewport
 *
 * The motion itself lives in css/blocks/icon-motion.css; these only measure,
 * write attributes and custom properties, and clean up after themselves.
 */
import { type Cleanup, isBrowser, markScripted } from '../env';

/** Every SVG shape a stroke can be drawn along. */
const GEOMETRY = 'path, circle, ellipse, line, polyline, polygon, rect';

export interface DrawInOptions {
  /** Which shapes to ink, relative to `el` (default: every SVG geometry element below it). */
  selector?: string;
  /** Milliseconds between one stroke and the next (default 130). */
  stagger?: number;
  /** Milliseconds before the first stroke — tiles use it to offset themselves after their neighbour. */
  delay?: number;
  /** How much of the element must be visible before the ink starts, 0–1 (default 0.35). */
  threshold?: number;
}

/**
 * Wire a `.nx-icon-draw-tile` (or any element carrying the ink classes): each
 * stroke gets `pathLength="1"` and a stepped `--_d`; an IntersectionObserver
 * writes `data-nx-drawn` when the element reaches view, and the stylesheet
 * turns the dashoffset loose. Without an observer the element draws at once;
 * without JS at all the CSS never hides the strokes in the first place.
 */
export function drawIn(el: HTMLElement, { selector, stagger = 130, delay = 0, threshold = 0.35 }: DrawInOptions = {}): Cleanup {
  if (!isBrowser) return () => {};
  markScripted();

  const shapes = Array.from(el.querySelectorAll<SVGGeometryElement>(selector ?? GEOMETRY));
  const adopted: SVGGeometryElement[] = [];
  shapes.forEach((shape, index) => {
    if (!shape.getAttribute('pathLength')) {
      shape.setAttribute('pathLength', '1');
      adopted.push(shape);
    }
    shape.style.setProperty('--_d', `${Math.round(delay + index * stagger)}ms`);
  });

  let drawn = false;
  const draw = () => {
    if (drawn) return;
    drawn = true;
    el.setAttribute('data-nx-drawn', '');
  };

  const strip = () => {
    el.removeAttribute('data-nx-drawn');
    for (const shape of shapes) shape.style.removeProperty('--_d');
    for (const shape of adopted) shape.removeAttribute('pathLength');
  };

  if (!('IntersectionObserver' in window)) {
    const frame = requestAnimationFrame(draw);
    return () => {
      cancelAnimationFrame(frame);
      strip();
    };
  }

  const observer = new IntersectionObserver(
    (entries) => {
      for (const entry of entries) {
        if (!entry.isIntersecting) continue;
        observer.disconnect();
        draw();
        return;
      }
    },
    // 0 too, so a tile taller than the viewport still starts when it enters.
    { threshold: [0, threshold] },
  );
  observer.observe(el);

  return () => {
    observer.disconnect();
    strip();
  };
}

/** Blank a drawn element for two frames and ink it again — the hover replay. */
export function redrawIn(el: Element): void {
  if (!isBrowser) return;
  el.removeAttribute('data-nx-drawn');
  requestAnimationFrame(() => requestAnimationFrame(() => el.setAttribute('data-nx-drawn', '')));
}

export interface PauseOffscreenOptions {
  /** Grows the viewport so loops park a little after they leave it (default 64px). */
  rootMargin?: string;
}

/**
 * Sets `data-paused` while `el` is outside the viewport, so a page full of
 * looping icons does not keep animating what nobody sees. The stylesheet
 * freezes (rather than resets) every animation under it.
 */
export function pauseOffscreen(el: HTMLElement, { rootMargin = '64px' }: PauseOffscreenOptions = {}): Cleanup {
  if (!isBrowser || !('IntersectionObserver' in window)) return () => {};
  const observer = new IntersectionObserver(([entry]) => el.toggleAttribute('data-paused', !entry?.isIntersecting), { rootMargin });
  observer.observe(el);
  return () => {
    observer.disconnect();
    el.removeAttribute('data-paused');
  };
}
