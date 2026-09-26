/**
 * Floating placement for menus, popovers and tooltips.
 *
 * CSS anchor positioning would do this natively, but it is not yet available in
 * every browser NabuXUI supports, so the floating element (position: fixed, in the
 * top layer when it is a popover) is placed from measurements: preferred side
 * first, flipped when it would leave the viewport, then shifted to stay inside.
 * Sides are logical — `inline-start` / `inline-end` follow the text direction.
 */
import { type Cleanup, direction, isBrowser } from './env';

export type Side = 'top' | 'bottom' | 'inline-start' | 'inline-end';
export type Align = 'start' | 'center' | 'end';

export interface PlaceOptions {
  side?: Side;
  align?: Align;
  /** Gap between anchor and floating element, px. */
  offset?: number;
  /** Minimum distance from the viewport edge, px. */
  padding?: number;
  /** Make the floating element at least as wide as the anchor. */
  matchWidth?: boolean;
}

export interface Placement {
  x: number;
  y: number;
  side: 'top' | 'bottom' | 'left' | 'right';
}

const opposite = { top: 'bottom', bottom: 'top', left: 'right', right: 'left' } as const;

/** Pure geometry, exported for tests: where does a box of `size` go around `anchor`? */
export function computePlacement(
  anchor: { left: number; top: number; width: number; height: number },
  size: { width: number; height: number },
  viewport: { width: number; height: number },
  { side = 'bottom', align = 'start', offset = 8, padding = 8 }: PlaceOptions,
  dir: 1 | -1 = 1,
): Placement {
  const physical = (s: Side): Placement['side'] => {
    if (s === 'inline-start') return dir === 1 ? 'left' : 'right';
    if (s === 'inline-end') return dir === 1 ? 'right' : 'left';
    return s;
  };

  const fits = (s: Placement['side']) => {
    if (s === 'bottom') return anchor.top + anchor.height + offset + size.height <= viewport.height - padding;
    if (s === 'top') return anchor.top - offset - size.height >= padding;
    if (s === 'right') return anchor.left + anchor.width + offset + size.width <= viewport.width - padding;
    return anchor.left - offset - size.width >= padding;
  };

  let resolved = physical(side);
  if (!fits(resolved) && fits(opposite[resolved])) resolved = opposite[resolved];

  let x: number;
  let y: number;
  const vertical = resolved === 'top' || resolved === 'bottom';

  if (vertical) {
    y = resolved === 'bottom' ? anchor.top + anchor.height + offset : anchor.top - offset - size.height;
    // Alignment is logical: "start" is the reading start of the anchor.
    const startEdge = dir === 1 ? anchor.left : anchor.left + anchor.width - size.width;
    const endEdge = dir === 1 ? anchor.left + anchor.width - size.width : anchor.left;
    x = align === 'center' ? anchor.left + (anchor.width - size.width) / 2 : align === 'start' ? startEdge : endEdge;
  } else {
    x = resolved === 'right' ? anchor.left + anchor.width + offset : anchor.left - offset - size.width;
    y = align === 'center' ? anchor.top + (anchor.height - size.height) / 2 : align === 'start' ? anchor.top : anchor.top + anchor.height - size.height;
  }

  // Shift along the cross axis to stay on screen.
  x = Math.min(Math.max(x, padding), Math.max(padding, viewport.width - size.width - padding));
  y = Math.min(Math.max(y, padding), Math.max(padding, viewport.height - size.height - padding));

  return { x: Math.round(x), y: Math.round(y), side: resolved };
}

/**
 * Keep `floating` placed against `anchor` while it is shown: re-placed on scroll,
 * resize and size changes. Writes left/top plus data-side (for enter direction)
 * and --nx-origin (so it scales out of the anchor).
 */
export function place(anchor: Element, floating: HTMLElement, options: PlaceOptions = {}): Cleanup {
  if (!isBrowser) return () => {};

  const run = () => {
    const a = anchor.getBoundingClientRect();
    if (options.matchWidth) floating.style.minInlineSize = `${a.width}px`;
    const size = { width: floating.offsetWidth, height: floating.offsetHeight };
    const viewport = { width: document.documentElement.clientWidth, height: window.innerHeight };
    const result = computePlacement(a, size, viewport, options, direction(anchor));

    floating.style.position = 'fixed';
    floating.style.left = `${result.x}px`;
    floating.style.top = `${result.y}px`;
    floating.style.right = 'auto';
    floating.style.bottom = 'auto';
    floating.setAttribute('data-side', result.side);

    const originX = Math.min(Math.max(a.left + a.width / 2 - result.x, 0), size.width);
    const originY = result.side === 'top' ? size.height : result.side === 'bottom' ? 0 : a.top + a.height / 2 - result.y;
    floating.style.setProperty('--nx-origin', `${originX}px ${originY}px`);
  };

  run();
  let frame = 0;
  const schedule = () => {
    if (!frame) frame = requestAnimationFrame(() => { frame = 0; run(); });
  };

  window.addEventListener('scroll', schedule, { capture: true, passive: true });
  window.addEventListener('resize', schedule);
  const observer = 'ResizeObserver' in window ? new ResizeObserver(schedule) : null;
  observer?.observe(floating);
  observer?.observe(anchor);

  return () => {
    cancelAnimationFrame(frame);
    window.removeEventListener('scroll', schedule, { capture: true });
    window.removeEventListener('resize', schedule);
    observer?.disconnect();
  };
}
