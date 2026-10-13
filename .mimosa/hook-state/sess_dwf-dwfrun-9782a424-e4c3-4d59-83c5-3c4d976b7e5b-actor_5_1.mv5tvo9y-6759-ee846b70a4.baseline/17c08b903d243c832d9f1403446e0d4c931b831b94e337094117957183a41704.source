/**
 * Framework-agnostic behaviours for the CSS-3D depth blocks.
 *
 *   dragRotate          drag a stage to orbit its preserve-3d world: spin and
 *                       tilt follow the pointer, and on release the world
 *                       either coasts on the finger's speed (`inertia`, then a
 *                       slow idle drift) or springs onto a resting angle
 *                       (`snap`, 90 for cube faces). Writes --nx-spin /
 *                       --nx-tilt in degrees; the stylesheet decides which
 *                       axis each one drives, and anything inside may
 *                       counter-rotate against the same properties to stay
 *                       readable. The arrow keys orbit a focusable stage, and
 *                       the loop parks while off screen or under reduced
 *                       motion — the drag itself still answers there.
 *   pauseWhileOffscreen toggles data-paused while the element sits outside the
 *                       viewport, so looping art (the open box's price tag)
 *                       stops repainting what nobody sees.
 *
 * External control: dispatch an `nx-rotate` CustomEvent on the element with a
 * DragRotateCommand — the reset-view and face-stepping buttons use it instead
 * of the component reaching into the loop.
 */
import { type Cleanup, isBrowser, onReducedMotionChange, prefersReducedMotion } from '../env';

/* ---- Drag to orbit ---------------------------------------------------------------------- */

export interface DragRotateState {
  /** Rotation around the world's spin axis, rounded to whole degrees. */
  spin: number;
  /** Rotation around the horizontal axis, rounded to whole degrees. */
  tilt: number;
  /** Whether a drag is in progress (the release will coast or snap). */
  dragging: boolean;
}

/** One `nx-rotate` command: what to change about the current angles. */
export interface DragRotateCommand {
  /** Nudge relative to the current angles. */
  bySpin?: number;
  byTilt?: number;
  /** Absolute angles (tilt is still clamped). */
  toSpin?: number;
  toTilt?: number;
  /** Back to the initial spin and tilt. */
  reset?: boolean;
}

export interface DragRotateOptions {
  /** Coast on the release velocity, then drift slowly while idle (default true). */
  inertia?: boolean;
  /** Snap to a multiple of this many degrees on release — 90 for cube faces (default 0, free). */
  snap?: number;
  /** Initial spin in degrees (default −28). */
  spin?: number;
  /** Initial tilt in degrees (default 62). */
  tilt?: number;
  /** Lowest tilt the drag may reach (default −20; the cube passes −90). */
  minTilt?: number;
  /** Highest tilt the drag may reach (default 80; the cube passes 90). */
  maxTilt?: number;
  /** Degrees of idle drift per second while inertia is on (default 6; 0 parks the loop when still). */
  drift?: number;
  /** How many frames of velocity project ahead before snapping (default 6). */
  projection?: number;
  /** Degrees per arrow-key press on a focusable stage (default: the snap angle, or 12). */
  keyStep?: number;
  /** Called whenever the rounded spin or tilt (or the drag state) changes. */
  onRotate?: (state: DragRotateState) => void;
}

/** Degrees of spin a horizontal drag adds per pixel, and tilt per pixel vertically. */
const SPIN_PER_PX = 0.4;
const TILT_PER_PX = 0.3;
/** Below this speed the coast is considered over (degrees per frame at 60fps). */
const COAST_EPSILON = 0.02;

export function dragRotate(el: HTMLElement, options: DragRotateOptions = {}): Cleanup {
  if (!isBrowser) return () => {};
  const { inertia = true, snap = 0, drift = 6, projection = 6 } = options;
  const keyStep = options.keyStep ?? (snap || 12);
  const homeSpin = options.spin ?? -28;
  const homeTilt = options.tilt ?? 62;
  const minTilt = options.minTilt ?? -20;
  const maxTilt = options.maxTilt ?? 80;

  let spin = homeSpin;
  let tilt = homeTilt;
  let vx = 0;
  let vy = 0;
  let dragging = false;
  let pointerId = -1;
  let px = 0;
  let py = 0;
  let raf = 0;
  let last = 0;
  let visible = true;
  let reported = '';

  const clampTilt = (value: number): number => Math.min(maxTilt, Math.max(minTilt, value));

  const report = () => {
    const key = `${Math.round(spin)} ${Math.round(tilt)} ${dragging}`;
    if (key === reported) return;
    reported = key;
    options.onRotate?.({ spin: Math.round(spin), tilt: Math.round(tilt), dragging });
  };

  const paint = () => {
    el.style.setProperty('--nx-spin', `${spin.toFixed(2)}deg`);
    el.style.setProperty('--nx-tilt', `${tilt.toFixed(2)}deg`);
    report();
  };

  /** Coast on the release velocity, then drift — parked off screen and under reduced motion. */
  const loop = (now: number) => {
    raf = 0;
    if (!visible || prefersReducedMotion() || dragging) return;
    const dt = last ? Math.min(0.05, (now - last) / 1000) : 1 / 60;
    last = now;
    const frames = dt * 60;
    if (Math.abs(vx) > COAST_EPSILON || Math.abs(vy) > COAST_EPSILON) {
      spin += vx * frames;
      tilt = clampTilt(tilt + vy * frames);
      const decay = 0.94 ** frames;
      vx *= decay;
      vy *= decay;
    } else if (inertia && drift) {
      vx = 0;
      vy = 0;
      spin += drift * dt;
    }
    paint();
    raf = requestAnimationFrame(loop);
  };

  const run = () => {
    if (raf) cancelAnimationFrame(raf);
    raf = 0;
    last = 0;
    if (!visible || prefersReducedMotion() || !inertia) return;
    if (Math.abs(vx) <= COAST_EPSILON && Math.abs(vy) <= COAST_EPSILON && !drift) return;
    raf = requestAnimationFrame(loop);
  };

  const onDown = (event: PointerEvent) => {
    if (dragging) return; // the first pointer owns the drag; extra touches are ignored
    if (event.pointerType === 'mouse' && event.button !== 0) return;
    dragging = true;
    pointerId = event.pointerId;
    px = event.clientX;
    py = event.clientY;
    vx = 0;
    vy = 0;
    if (raf) cancelAnimationFrame(raf);
    raf = 0; // the finger is the clock now
    el.setPointerCapture(pointerId);
    el.setAttribute('data-dragging', '');
    report();
  };

  const onMove = (event: PointerEvent) => {
    if (!dragging || event.pointerId !== pointerId) return;
    const dx = event.clientX - px;
    const dy = event.clientY - py;
    px = event.clientX;
    py = event.clientY;
    spin += dx * SPIN_PER_PX;
    tilt = clampTilt(tilt - dy * TILT_PER_PX);
    vx = dx * SPIN_PER_PX;
    vy = -dy * TILT_PER_PX;
    paint();
  };

  const onUp = (event: PointerEvent) => {
    if (!dragging || event.pointerId !== pointerId) return;
    dragging = false;
    el.removeAttribute('data-dragging');
    if (snap) {
      // Read the finger's speed, project it a few frames ahead, settle on the resting angle.
      const projected = Math.abs(vx) > 2 ? spin + vx * projection : spin;
      spin = Math.round(projected / snap) * snap;
      tilt = clampTilt(Math.round(tilt / snap) * snap);
      vx = 0;
      vy = 0;
      paint();
    }
    run();
    report();
  };

  /** The arrow keys orbit a focusable stage by one step (the snap angle, or 12°). */
  const onKey = (event: KeyboardEvent) => {
    let bySpin = 0;
    let byTilt = 0;
    switch (event.key) {
      case 'ArrowLeft': bySpin = keyStep; break;
      case 'ArrowRight': bySpin = -keyStep; break;
      case 'ArrowUp': byTilt = -keyStep; break;
      case 'ArrowDown': byTilt = keyStep; break;
      default: return;
    }
    event.preventDefault();
    el.dispatchEvent(new CustomEvent<DragRotateCommand>('nx-rotate', { detail: { bySpin, byTilt } }));
  };

  const onCommand = (event: Event) => {
    const detail = (event as CustomEvent<DragRotateCommand>).detail;
    if (!detail) return;
    dragging = false;
    el.removeAttribute('data-dragging');
    if (raf) cancelAnimationFrame(raf);
    raf = 0;
    vx = 0;
    vy = 0;
    if (detail.reset) {
      spin = homeSpin;
      tilt = homeTilt;
    } else {
      if (detail.bySpin !== undefined) spin += detail.bySpin;
      if (detail.byTilt !== undefined) tilt = clampTilt(tilt + detail.byTilt);
      if (detail.toSpin !== undefined) spin = detail.toSpin;
      if (detail.toTilt !== undefined) tilt = clampTilt(detail.toTilt);
    }
    paint();
    report();
  };

  el.addEventListener('pointerdown', onDown);
  el.addEventListener('pointermove', onMove);
  el.addEventListener('pointerup', onUp);
  el.addEventListener('pointercancel', onUp);
  el.addEventListener('keydown', onKey);
  el.addEventListener('nx-rotate', onCommand);

  const observer =
    'IntersectionObserver' in window
      ? new IntersectionObserver(
          (entries) => {
            visible = !!entries[0]?.isIntersecting;
            run();
          },
          { rootMargin: '64px' },
        )
      : null;
  observer?.observe(el);
  const stopMotion = onReducedMotionChange(run);

  paint();
  run();

  return () => {
    if (raf) cancelAnimationFrame(raf);
    observer?.disconnect();
    stopMotion();
    el.removeEventListener('pointerdown', onDown);
    el.removeEventListener('pointermove', onMove);
    el.removeEventListener('pointerup', onUp);
    el.removeEventListener('pointercancel', onUp);
    el.removeEventListener('keydown', onKey);
    el.removeEventListener('nx-rotate', onCommand);
    el.removeAttribute('data-dragging');
    el.style.removeProperty('--nx-spin');
    el.style.removeProperty('--nx-tilt');
  };
}

/* ---- Pause off screen -------------------------------------------------------------------- */

/**
 * Toggle `data-paused` while the element sits outside the viewport — the same
 * contract marquee rows use, for any looping art (the open box's price tag).
 * Reduced motion still applies from the stylesheet; this is only about not
 * repainting what nobody sees.
 */
export function pauseWhileOffscreen(el: HTMLElement): Cleanup {
  if (!isBrowser || !('IntersectionObserver' in window)) return () => {};
  const observer = new IntersectionObserver(
    (entries) => {
      el.toggleAttribute('data-paused', !entries[0]?.isIntersecting);
    },
    { rootMargin: '96px' },
  );
  observer.observe(el);
  return () => {
    observer.disconnect();
    el.removeAttribute('data-paused');
  };
}
