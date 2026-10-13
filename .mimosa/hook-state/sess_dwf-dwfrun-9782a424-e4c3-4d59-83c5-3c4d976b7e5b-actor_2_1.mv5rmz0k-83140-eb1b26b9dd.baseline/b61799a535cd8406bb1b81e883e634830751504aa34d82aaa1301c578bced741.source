/**
 * Drag-to-dismiss and swipe gestures (toasts, the swipe stack).
 *
 * While dragging it writes --nx-dx / --nx-dy, --nx-drag-rotate and
 * --nx-drag-progress (-1…1 against the threshold) and sets [data-dragging];
 * on release it either calls `onSwipe` with the direction or springs back.
 * Only the swiped axis is captured, so the page still scrolls the other way.
 */
import { type Cleanup, isBrowser } from './env';

export type SwipeDirection = 'left' | 'right' | 'up' | 'down';

export interface SwipeOptions {
  axis?: 'x' | 'y' | 'both';
  /** Distance (px) past which a release counts as a swipe. */
  threshold?: number;
  /** Degrees of rotation per 100px of horizontal travel (the swipe stack uses this). */
  rotate?: number;
  /** A fast flick counts even when it is shorter than the threshold (px/ms). */
  velocity?: number;
  onSwipe?: (direction: SwipeDirection) => void;
  onStart?: () => void;
  onCancel?: () => void;
  /** Ignore drags that start on these elements (buttons inside a toast, say). */
  ignore?: string;
}

export function swipe(el: HTMLElement, options: SwipeOptions = {}): Cleanup {
  if (!isBrowser) return () => {};
  const { axis = 'x', threshold = 96, rotate = 0, velocity = 0.6, ignore = 'button, a, input, textarea, select, [data-nx-no-swipe]' } = options;

  let startX = 0;
  let startY = 0;
  let startTime = 0;
  let dx = 0;
  let dy = 0;
  let active = false;
  let locked: 'x' | 'y' | null = null;
  let pointerId = -1;

  const write = () => {
    el.style.setProperty('--nx-dx', `${axis === 'y' ? 0 : dx}px`);
    el.style.setProperty('--nx-dy', `${axis === 'x' ? 0 : dy}px`);
    if (rotate) el.style.setProperty('--nx-drag-rotate', `${((dx / 100) * rotate).toFixed(2)}deg`);
    const travel = axis === 'y' ? dy : dx;
    el.style.setProperty('--nx-drag-progress', Math.max(-1, Math.min(1, travel / threshold)).toFixed(3));
  };

  const reset = () => {
    dx = 0;
    dy = 0;
    write();
  };

  const onDown = (event: PointerEvent) => {
    if (event.button !== 0 || (event.target as Element).closest(ignore)) return;
    active = true;
    locked = null;
    pointerId = event.pointerId;
    startX = event.clientX;
    startY = event.clientY;
    startTime = performance.now();
    dx = 0;
    dy = 0;
  };

  const onMove = (event: PointerEvent) => {
    if (!active || event.pointerId !== pointerId) return;
    const mx = event.clientX - startX;
    const my = event.clientY - startY;

    if (!locked) {
      if (Math.hypot(mx, my) < 6) return;
      locked = Math.abs(mx) > Math.abs(my) ? 'x' : 'y';
      // A vertical drag on a horizontal swiper is a scroll: let the page have it.
      if (axis !== 'both' && locked !== axis) {
        active = false;
        return;
      }
      el.setPointerCapture(pointerId);
      el.setAttribute('data-dragging', '');
      el.setAttribute('data-swiping', '');
      options.onStart?.();
    }

    dx = axis === 'y' ? 0 : mx;
    dy = axis === 'x' ? 0 : my;
    write();
  };

  const onUp = (event: PointerEvent) => {
    if (!active || event.pointerId !== pointerId) return;
    active = false;
    el.removeAttribute('data-dragging');
    el.removeAttribute('data-swiping');
    if (!locked) return;

    const elapsed = Math.max(1, performance.now() - startTime);
    const travel = axis === 'y' ? dy : dx;
    const speed = Math.abs(travel) / elapsed;

    if (Math.abs(travel) >= threshold || (speed >= velocity && Math.abs(travel) > 24)) {
      const direction: SwipeDirection = axis === 'y' ? (dy > 0 ? 'down' : 'up') : dx > 0 ? 'right' : 'left';
      el.style.setProperty('--nx-exit-x', axis === 'y' ? '0' : dx > 0 ? '1' : '-1');
      options.onSwipe?.(direction);
    } else {
      reset();
      options.onCancel?.();
    }
  };

  el.addEventListener('pointerdown', onDown);
  el.addEventListener('pointermove', onMove);
  el.addEventListener('pointerup', onUp);
  el.addEventListener('pointercancel', onUp);

  return () => {
    el.removeEventListener('pointerdown', onDown);
    el.removeEventListener('pointermove', onMove);
    el.removeEventListener('pointerup', onUp);
    el.removeEventListener('pointercancel', onUp);
  };
}
