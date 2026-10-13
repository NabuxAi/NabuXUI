/**
 * Framework-agnostic behaviours for the interactive-motion blocks.
 *
 *   pointerSpot   one halo follows the pointer across a whole grid while the
 *                 card under the hand lights its own edge from inside
 *   cursorHalo    a ring trails the pointer and a dot rides it; children ask
 *                 for a state with data-cursor — one container, never fixed
 *   marqueeWall   parks the marquee rows of a wall while they are off screen
 *
 * As everywhere in NabuXUI, the motion lives in css/blocks/motion-fx.css;
 * these measure, write custom properties and attributes, and clean up.
 */
import { type Cleanup, canHover, direction, isBrowser, onReducedMotionChange, prefersReducedMotion } from '../env';

/* ---- Pointer spot ------------------------------------------------------------------ */

export interface PointerSpotOptions {
  /** Radius of the halo that sweeps the whole area, px (default 260). */
  radius?: number;
  /** The cards that light their own edge under the pointer (default '[data-nx-spot]'). */
  selector?: string;
}

/**
 * A follow-spot for a wall of cards. Writes the pointer's position on `el`
 * (`--nx-spot-x/--nx-spot-y`, physical px) for the wall's one halo, and on the
 * `[data-nx-spot]` card under the hand (`--nx-px/--nx-py`, the names the card
 * spotlight already reads) for its lit edge. `data-spot` on `el` fades the
 * halo in and out, `data-hot` marks the card — and keyboard focus paints the
 * same glow with no pointer at all. Under reduced motion only the states
 * toggle, so the light does not chase the pointer; the stylesheet parks it at
 * the top instead.
 */
export function pointerSpot(el: HTMLElement, { radius = 260, selector = '[data-nx-spot]' }: PointerSpotOptions = {}): Cleanup {
  if (!isBrowser) return () => {};

  let targets = Array.from(el.querySelectorAll<HTMLElement>(selector));
  let frame = 0;
  let last: PointerEvent | null = null;
  let reduced = prefersReducedMotion();

  el.style.setProperty('--nx-spot-r', `${radius}px`);

  const mutations = new MutationObserver(() => {
    targets = Array.from(el.querySelectorAll<HTMLElement>(selector));
  });
  mutations.observe(el, { childList: true, subtree: true });

  const flush = () => {
    frame = 0;
    const event = last;
    if (!event) return;
    const rect = el.getBoundingClientRect();
    if (!reduced) {
      el.style.setProperty('--nx-spot-x', `${Math.round(event.clientX - rect.left)}px`);
      el.style.setProperty('--nx-spot-y', `${Math.round(event.clientY - rect.top)}px`);
    }
    el.setAttribute('data-spot', '');
    for (const target of targets) {
      const box = target.getBoundingClientRect();
      const inside = event.clientX >= box.left && event.clientX <= box.right && event.clientY >= box.top && event.clientY <= box.bottom;
      target.toggleAttribute('data-hot', inside);
      if (inside && !reduced) {
        target.style.setProperty('--nx-px', `${Math.round(event.clientX - box.left)}px`);
        target.style.setProperty('--nx-py', `${Math.round(event.clientY - box.top)}px`);
      }
    }
  };

  const schedule = (event: PointerEvent) => {
    last = event;
    if (!frame) frame = requestAnimationFrame(flush);
  };
  const clear = () => {
    if (frame) cancelAnimationFrame(frame);
    frame = 0;
    last = null;
    el.removeAttribute('data-spot');
    for (const target of targets) target.removeAttribute('data-hot');
  };

  el.addEventListener('pointermove', schedule);
  el.addEventListener('pointerdown', schedule);
  el.addEventListener('pointerleave', clear);
  el.addEventListener('pointercancel', clear);
  const stopMotion = onReducedMotionChange((value) => {
    reduced = value;
  });

  return () => {
    clear();
    mutations.disconnect();
    stopMotion();
    el.removeEventListener('pointermove', schedule);
    el.removeEventListener('pointerdown', schedule);
    el.removeEventListener('pointerleave', clear);
    el.removeEventListener('pointercancel', clear);
    for (const name of ['--nx-spot-r', '--nx-spot-x', '--nx-spot-y']) el.style.removeProperty(name);
    for (const target of targets) {
      for (const name of ['--nx-px', '--nx-py']) target.style.removeProperty(name);
    }
  };
}

/* ---- Cursor halo -------------------------------------------------------------------- */

export interface CursorHaloOptions {
  /** How fast the ring catches up with the pointer: 0 never arrives, 1 sticks to it (default 0.18). */
  factor?: number;
}

/**
 * The cursor pair of a `.nx-cursor-halo`: a ring that trails the pointer and a
 * dot that rides it, both carried by `translate` from the container's
 * inline-start edge — one container, never `position: fixed`. Children ask for
 * a cursor state with `data-cursor` (link | text | play, or anything you style
 * yourself) and name it with `data-cursor-label`. The pair fades in while the
 * pointer is inside and out on leave; fine pointers only, and under reduced
 * motion the ring stops trailing and sits straight on the pointer.
 */
export function cursorHalo(el: HTMLElement, { factor = 0.18 }: CursorHaloOptions = {}): Cleanup {
  if (!isBrowser || !canHover()) return () => {};
  const ring = el.querySelector<HTMLElement>('.nx-cursor-halo-ring');
  const dot = el.querySelector<HTMLElement>('.nx-cursor-halo-dot');
  const face = el.querySelector<HTMLElement>('.nx-cursor-halo-face');
  if (!ring || !dot) return () => {};

  el.setAttribute('data-fx', '');

  let frame = 0;
  let active = false;
  let first = true;
  let rtl = direction(el) === -1;
  let tx = 0;
  let ty = 0;
  let rx = 0;
  let ry = 0;

  const place = (target: HTMLElement, x: number, y: number) => {
    target.style.setProperty('translate', `${rtl ? -x : x}px ${y}px`);
  };

  const tick = () => {
    const k = prefersReducedMotion() ? 1 : factor;
    rx += (tx - rx) * k;
    ry += (ty - ry) * k;
    place(ring, rx, ry);
    place(dot, tx, ty);
    if (active || Math.abs(tx - rx) > 0.5 || Math.abs(ty - ry) > 0.5) frame = requestAnimationFrame(tick);
    else frame = 0;
  };
  const run = () => {
    if (!frame) frame = requestAnimationFrame(tick);
  };

  const move = (event: PointerEvent) => {
    const rect = el.getBoundingClientRect();
    const px = event.clientX - rect.left;
    const py = event.clientY - rect.top;
    rtl = direction(el) === -1;
    // The pair is anchored to the inline-start edge; translate is physical, so mirror x in RTL.
    tx = rtl ? rect.width - px : px;
    ty = py;
    if (first) {
      rx = tx;
      ry = ty;
      first = false;
    }
    const hit = event.target instanceof Element ? event.target.closest<HTMLElement>('[data-cursor]') : null;
    const mode = hit?.getAttribute('data-cursor') ?? '';
    if (mode) el.setAttribute('data-mode', mode);
    else el.removeAttribute('data-mode');
    if (face) face.textContent = hit?.getAttribute('data-cursor-label') ?? '';
    if (!active) {
      active = true;
      el.setAttribute('data-active', '');
    }
    run();
  };

  const leave = () => {
    active = false;
    el.removeAttribute('data-active');
    el.removeAttribute('data-mode');
    el.removeAttribute('data-pressed');
    if (face) face.textContent = '';
  };
  const down = () => el.setAttribute('data-pressed', '');
  const up = () => el.removeAttribute('data-pressed');

  el.addEventListener('pointermove', move);
  el.addEventListener('pointerleave', leave);
  el.addEventListener('pointerdown', down);
  el.addEventListener('pointerup', up);
  el.addEventListener('pointercancel', up);

  return () => {
    if (frame) cancelAnimationFrame(frame);
    frame = 0;
    el.removeEventListener('pointermove', move);
    el.removeEventListener('pointerleave', leave);
    el.removeEventListener('pointerdown', down);
    el.removeEventListener('pointerup', up);
    el.removeEventListener('pointercancel', up);
    leave();
    el.removeAttribute('data-fx');
    ring.style.removeProperty('translate');
    dot.style.removeProperty('translate');
  };
}

/* ---- Marquee wall -------------------------------------------------------------------- */

export interface MarqueeWallOptions {
  /** Park rows that are outside the viewport (default true). */
  pauseOffscreen?: boolean;
}

/**
 * Pause every `.nx-marquee` row of a wall while it sits outside the viewport,
 * so a long page of moving cards does not keep repainting what nobody sees.
 * Hover and focus still pause a row from the stylesheet; this only adds the
 * off-screen stop by toggling the same `data-paused`.
 */
export function marqueeWall(el: HTMLElement, { pauseOffscreen = true }: MarqueeWallOptions = {}): Cleanup {
  if (!isBrowser || !pauseOffscreen || !('IntersectionObserver' in window)) return () => {};
  const rows = Array.from(el.querySelectorAll<HTMLElement>('.nx-marquee'));
  if (rows.length === 0) return () => {};
  const observer = new IntersectionObserver(
    (entries) => {
      for (const entry of entries) (entry.target as HTMLElement).toggleAttribute('data-paused', !entry.isIntersecting);
    },
    { rootMargin: '96px' },
  );
  rows.forEach((row) => observer.observe(row));
  return () => {
    observer.disconnect();
    rows.forEach((row) => row.removeAttribute('data-paused'));
  };
}
