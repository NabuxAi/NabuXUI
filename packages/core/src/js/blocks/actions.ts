/**
 * Framework-agnostic behaviours for the buttons, loaders & micro-interactions
 * blocks: the width morph and label swap of the transaction button, emoji
 * bursts, drag-to-scroll rows, the fill button's entry point, the marching
 * border's speed, and the geometry behind the pixel and parametric loaders.
 *
 * The motion itself lives in css/blocks/actions.css; these only measure, write
 * custom properties or attributes, and clean up after themselves.
 */
import { type Cleanup, direction, isBrowser, prefersReducedMotion } from '../env';
import { type SpringEasing, type SpringName, springEasing, springs } from '../spring';
import { splitText, textDirection } from '../text';

const easings = new Map<SpringName, SpringEasing>();

/** The `linear()` easing and duration of a named spring, computed once. */
function spring(name: SpringName): SpringEasing {
  let easing = easings.get(name);
  if (!easing) {
    easing = springEasing(springs[name]);
    easings.set(name, easing);
  }
  return easing;
}

/* ---- Width morph ----------------------------------------------------------- */

const MORPH_WIDTH = 'nx-morph-width';

export interface MorphWidthOptions {
  /**
   * Start from this width (px) instead of measuring before `mutate` runs — for
   * frameworks that have already written the new content (React measures in a
   * layout effect before it swaps the label).
   */
  from?: number;
  spring?: SpringName;
}

/**
 * Change an element's content and let its width follow on a spring.
 *
 * Measures the width, runs `mutate`, measures again and animates `inline-size`
 * between the two with the Web Animations API. A morph already running is
 * picked up where it is, so rapid changes never jump. Reduced motion (or no
 * change in width) skips the animation.
 */
export function morphWidth(el: HTMLElement, mutate?: () => void, { from, spring: name = 'gentle' }: MorphWidthOptions = {}): Animation | null {
  if (!isBrowser) {
    mutate?.();
    return null;
  }
  // offsetWidth ignores transforms (a pressed button is scaled) but includes a running morph.
  const start = from ?? el.offsetWidth;
  for (const animation of el.getAnimations?.() ?? []) if (animation.id === MORPH_WIDTH) animation.cancel();
  mutate?.();
  const end = el.offsetWidth;
  if (prefersReducedMotion() || Math.abs(start - end) < 1 || typeof el.animate !== 'function') return null;

  const { easing, duration } = spring(name);
  const animation = el.animate([{ inlineSize: `${start}px` }, { inlineSize: `${end}px` }], { duration, easing });
  animation.id = MORPH_WIDTH;
  return animation;
}

/**
 * Swap the text inside `host` for `text`: the current copy leaves (out of flow,
 * up and blurred) while the new one rises in word by word. Both copies are
 * `aria-hidden`; keep the real text in a visually hidden element beside them.
 *
 *   <span class="nx-tx-button-text" data-state="enter|leave" dir="…" aria-hidden="true">
 *     <span style="--nx-i: 0">Pay </span><span style="--nx-i: 1">$49</span>
 *   </span>
 */
export function morphLabel(host: HTMLElement, text: string, { className = 'nx-tx-button-text' }: { className?: string } = {}): void {
  if (!isBrowser) return;
  for (const old of Array.from(host.children) as HTMLElement[]) {
    if (!old.classList.contains(className)) continue;
    if (old.getAttribute('data-state') === 'leave') {
      old.remove();
      continue;
    }
    old.setAttribute('data-state', 'leave');
    const remove = () => old.remove();
    old.addEventListener('animationend', (event) => event.target === old && remove());
    setTimeout(remove, 450);
  }

  const next = document.createElement('span');
  next.className = className;
  next.setAttribute('data-state', 'enter');
  next.setAttribute('aria-hidden', 'true');
  next.dir = textDirection(text);
  splitText(text).forEach((piece, i) => {
    const span = document.createElement('span');
    span.textContent = piece;
    span.style.setProperty('--nx-i', String(i));
    next.appendChild(span);
  });
  host.appendChild(next);
}

/* ---- Emoji burst ----------------------------------------------------------- */

export interface EmojiBurstOptions {
  count?: number;
  /** How far (px) the particles travel, on average. */
  distance?: number;
}

/**
 * Throw copies of an emoji out from the centre of `el`. The particles live in
 * a fixed layer on top of the page (the top layer where popovers are
 * supported), so a scrolling or clipping parent never cuts them off.
 */
export function emojiBurst(el: Element, emoji: string, { count = 8, distance = 54 }: EmojiBurstOptions = {}): void {
  if (!isBrowser || !emoji || prefersReducedMotion()) return;
  const rect = el.getBoundingClientRect();
  const host = document.createElement('span');
  host.className = 'nx-emoji-burst';
  host.setAttribute('aria-hidden', 'true');
  host.style.left = `${(rect.left + rect.width / 2).toFixed(1)}px`;
  host.style.top = `${(rect.top + rect.height / 2).toFixed(1)}px`;

  for (let i = 0; i < count; i++) {
    const angle = (Math.PI * 2 * i) / count + (Math.random() - 0.5) * 0.8;
    const reach = distance * (0.65 + Math.random() * 0.55);
    const particle = document.createElement('span');
    particle.className = 'nx-emoji-burst-particle';
    particle.textContent = emoji;
    particle.style.setProperty('--_dx', `${(Math.cos(angle) * reach).toFixed(1)}px`);
    // A little lift: particles rise before gravity takes them.
    particle.style.setProperty('--_dy', `${(Math.sin(angle) * reach - 10).toFixed(1)}px`);
    particle.style.setProperty('--_rot', `${Math.round((Math.random() - 0.5) * 120)}deg`);
    particle.style.setProperty('--_size', (0.75 + Math.random() * 0.5).toFixed(2));
    particle.style.setProperty('--_delay', `${Math.round(Math.random() * 60)}ms`);
    host.appendChild(particle);
  }

  document.body.appendChild(host);
  if (typeof host.showPopover === 'function') {
    host.setAttribute('popover', 'manual');
    try {
      host.showPopover();
    } catch {
      /* stays a fixed layer */
    }
  }
  setTimeout(() => host.remove(), 1000);
}

export interface ShakeAndBurstOptions {
  /** The emoji thrown out of each item as it bursts (the particles are the emoji itself). */
  emoji?: (item: HTMLElement) => string | null | undefined;
  /** Runs as the items burst: deselect them here. */
  onBurst?: () => void;
  /** Runs once every item has settled. */
  onDone?: () => void;
}

const SHAKE_MS = 320;
const BURST_MS = 420;
const BURST_STAGGER = 40;

/**
 * The "clear" sequence: the items shake, then burst one after another (the CSS
 * keys on [data-shaking] and [data-exploding], staggered by --nx-i) while
 * their emoji fly out. Reduced motion skips straight to `onBurst`.
 */
export function shakeAndBurst(items: HTMLElement[], { emoji, onBurst, onDone }: ShakeAndBurstOptions = {}): Cleanup {
  if (!isBrowser || items.length === 0 || prefersReducedMotion()) {
    onBurst?.();
    onDone?.();
    return () => {};
  }

  const timers: Array<ReturnType<typeof setTimeout>> = [];
  const reset = () => {
    for (const item of items) {
      item.removeAttribute('data-shaking');
      item.removeAttribute('data-exploding');
    }
  };

  items.forEach((item, i) => {
    item.style.setProperty('--nx-i', String(i));
    item.setAttribute('data-shaking', '');
  });

  timers.push(
    setTimeout(() => {
      items.forEach((item, i) => {
        item.removeAttribute('data-shaking');
        item.setAttribute('data-exploding', '');
        timers.push(
          setTimeout(() => {
            const glyph = emoji?.(item);
            if (glyph) emojiBurst(item, glyph, { count: 6, distance: 46 });
          }, i * BURST_STAGGER),
        );
      });
      onBurst?.();
      timers.push(
        setTimeout(() => {
          reset();
          onDone?.();
        }, BURST_MS + items.length * BURST_STAGGER),
      );
    }, SHAKE_MS),
  );

  return () => {
    timers.forEach(clearTimeout);
    reset();
  };
}

/* ---- Drag to scroll -------------------------------------------------------- */

export interface DragScrollOptions {
  /** The element that stretches past the ends (rubber band). Defaults to the first child. */
  track?: HTMLElement | null;
  /** How much of the glide survives each frame (0–1). */
  friction?: number;
  /** Settle on the nearest item (the track's children) when the glide ends. */
  snap?: boolean;
}

/**
 * Drag a horizontal scroller with the mouse or a pen: the content follows the
 * pointer, stretches with resistance past either end, glides on after a flick
 * and settles on the nearest item. Touch keeps the browser's own scrolling
 * (and its scroll snapping). A drag never counts as a click on what is under it.
 *
 * While the pointer is held the scroller has [data-dragging]; until the glide
 * has settled it has [data-coasting] (turn scroll snapping off for both).
 */
export function dragScroll(el: HTMLElement, { track = el.firstElementChild as HTMLElement | null, friction = 0.94, snap = true }: DragScrollOptions = {}): Cleanup {
  if (!isBrowser) return () => {};

  let pointerId: number | null = null;
  let dragging = false;
  let suppressClick = false;
  let startX = 0;
  let startScroll = 0;
  let lastX = 0;
  let lastTime = 0;
  let velocity = 0;
  let stretched = 0;
  let frame = 0;
  let settleTimer: ReturnType<typeof setTimeout> | undefined;

  const range = (): [number, number] => {
    const max = Math.max(0, el.scrollWidth - el.clientWidth);
    // Right-to-left scrollers count scrollLeft down from 0.
    return direction(el) === -1 ? [-max, 0] : [0, max];
  };
  const clamp = (value: number) => {
    const [lo, hi] = range();
    return Math.min(hi, Math.max(lo, value));
  };

  const stretch = (px: number) => {
    stretched = px;
    if (track) track.style.translate = Math.abs(px) < 0.5 ? '' : `${px.toFixed(1)}px 0`;
  };
  // The further past the end, the less it follows.
  const rubber = (distance: number) => {
    const width = el.clientWidth || 1;
    return (1 - 1 / ((Math.abs(distance) * 0.55) / width + 1)) * width * Math.sign(distance);
  };

  const finish = () => {
    clearTimeout(settleTimer);
    el.removeAttribute('data-coasting');
  };

  const nearest = (): number | null => {
    if (!track || track.children.length === 0) return null;
    const rtl = direction(el) === -1;
    const box = el.getBoundingClientRect();
    const padding = parseFloat(getComputedStyle(el).scrollPaddingInlineStart) || 0;
    let best: number | null = null;
    let bestDistance = Infinity;
    for (const item of Array.from(track.children)) {
      const rect = item.getBoundingClientRect();
      const offset = rtl ? box.right - padding - rect.right : rect.left - (box.left + padding);
      const target = clamp(el.scrollLeft + (rtl ? -offset : offset));
      const distance = Math.abs(target - el.scrollLeft);
      if (distance < bestDistance) {
        bestDistance = distance;
        best = target;
      }
    }
    return best;
  };

  const settle = (atEdge = false) => {
    frame = 0;
    const target = snap && !atEdge ? nearest() : null;
    if (target === null || Math.abs(target - el.scrollLeft) < 1) {
      finish();
      return;
    }
    el.scrollTo({ left: target, behavior: prefersReducedMotion() ? 'auto' : 'smooth' });
    // Snapping comes back on once the smooth scroll has arrived.
    settleTimer = setTimeout(finish, 600);
    el.addEventListener('scrollend', finish, { once: true });
  };

  const glide = () => {
    let speed = velocity * 16;
    let position = el.scrollLeft;
    if (prefersReducedMotion() || Math.abs(speed) < 0.6) {
      settle();
      return;
    }
    const step = () => {
      position = clamp(position + speed);
      el.scrollLeft = position;
      speed *= friction;
      const [lo, hi] = range();
      if (Math.abs(speed) < 0.35 || position <= lo || position >= hi) settle(position <= lo || position >= hi);
      else frame = requestAnimationFrame(step);
    };
    frame = requestAnimationFrame(step);
  };

  const onDown = (event: PointerEvent) => {
    // One pointer at a time; touch scrolls natively.
    if (pointerId !== null || event.pointerType === 'touch' || event.button !== 0) return;
    cancelAnimationFrame(frame);
    frame = 0;
    finish();
    pointerId = event.pointerId;
    dragging = false;
    suppressClick = false;
    startX = lastX = event.clientX;
    startScroll = el.scrollLeft;
    lastTime = event.timeStamp;
    velocity = 0;
  };

  const onMove = (event: PointerEvent) => {
    if (event.pointerId !== pointerId) return;
    const dx = event.clientX - startX;
    if (!dragging) {
      if (Math.abs(dx) < 4) return;
      dragging = true;
      suppressClick = true;
      el.setPointerCapture(event.pointerId);
      el.setAttribute('data-dragging', '');
      el.setAttribute('data-coasting', '');
    }
    const wanted = startScroll - dx;
    const scrolled = clamp(wanted);
    el.scrollLeft = scrolled;
    stretch(rubber(scrolled - wanted));
    const elapsed = Math.max(1, event.timeStamp - lastTime);
    velocity = velocity * 0.2 + ((lastX - event.clientX) / elapsed) * 0.8;
    lastX = event.clientX;
    lastTime = event.timeStamp;
  };

  const onUp = (event: PointerEvent) => {
    if (event.pointerId !== pointerId) return;
    pointerId = null;
    if (!dragging) return;
    dragging = false;
    if (el.hasPointerCapture(event.pointerId)) el.releasePointerCapture(event.pointerId);
    el.removeAttribute('data-dragging');
    const wasStretched = Math.abs(stretched) > 0.5;
    stretch(0);
    // The click (if any) follows pointerup in the same task; forget the drag after it.
    setTimeout(() => (suppressClick = false), 0);
    // A pointer that stopped before letting go has no momentum left.
    if (event.timeStamp - lastTime > 90) velocity = 0;
    if (wasStretched) settle(true);
    else glide();
  };

  const onClick = (event: MouseEvent) => {
    if (!suppressClick) return;
    suppressClick = false;
    event.preventDefault();
    event.stopPropagation();
  };

  const onDragStart = (event: DragEvent) => event.preventDefault();

  el.addEventListener('pointerdown', onDown);
  el.addEventListener('pointermove', onMove);
  el.addEventListener('pointerup', onUp);
  el.addEventListener('pointercancel', onUp);
  el.addEventListener('click', onClick, true);
  el.addEventListener('dragstart', onDragStart);

  return () => {
    cancelAnimationFrame(frame);
    finish();
    stretch(0);
    el.removeAttribute('data-dragging');
    el.removeEventListener('pointerdown', onDown);
    el.removeEventListener('pointermove', onMove);
    el.removeEventListener('pointerup', onUp);
    el.removeEventListener('pointercancel', onUp);
    el.removeEventListener('click', onClick, true);
    el.removeEventListener('dragstart', onDragStart);
    el.removeEventListener('scrollend', finish);
  };
}

/* ---- Fill button: where the pointer came in and went out ------------------- */

/**
 * Writes --nx-px / --nx-py (px) where the pointer entered the element and where
 * it left, plus --nx-fill-reach: a radius that covers the element from any
 * point inside it. The fill button's circle grows from the first and retreats
 * to the second. Keyboard focus fills from the centre.
 */
export function pointerEntry(el: HTMLElement): Cleanup {
  if (!isBrowser) return () => {};
  let enteredAt = 0;

  const write = (x: number, y: number, rect: DOMRect) => {
    el.style.setProperty('--nx-px', `${x.toFixed(1)}px`);
    el.style.setProperty('--nx-py', `${y.toFixed(1)}px`);
    el.style.setProperty('--nx-fill-reach', `${Math.ceil(Math.hypot(rect.width, rect.height) + 2)}px`);
  };

  const onEnter = (event: PointerEvent) => {
    enteredAt = event.timeStamp;
    const rect = el.getBoundingClientRect();
    write(event.clientX - rect.left, event.clientY - rect.top, rect);
  };

  const onLeave = (event: PointerEvent) => {
    // A quick pass never finished filling: retreat to where it came from instead of jumping.
    if (event.timeStamp - enteredAt < 260) return;
    const rect = el.getBoundingClientRect();
    write(event.clientX - rect.left, event.clientY - rect.top, rect);
  };

  const onFocus = () => {
    if (!el.matches(':focus-visible')) return;
    const rect = el.getBoundingClientRect();
    write(rect.width / 2, rect.height / 2, rect);
  };

  el.addEventListener('pointerenter', onEnter);
  el.addEventListener('pointerleave', onLeave);
  el.addEventListener('focus', onFocus);
  return () => {
    el.removeEventListener('pointerenter', onEnter);
    el.removeEventListener('pointerleave', onLeave);
    el.removeEventListener('focus', onFocus);
    for (const name of ['--nx-px', '--nx-py', '--nx-fill-reach']) el.style.removeProperty(name);
  };
}

/* ---- Marching border: speed up without a jump ------------------------------ */

export interface MarchingBorderOptions {
  /** How much faster the dashes march under the pointer or keyboard focus. */
  rate?: number;
  /** The stroke whose CSS animation is sped up. */
  selector?: string;
}

/**
 * Changing a CSS animation's duration makes it jump. This eases the marching
 * border's playback rate instead, so the dashes accelerate while hovered or
 * focused and coast back down after, from wherever they are.
 */
export function marchingBorder(el: HTMLElement, { rate = 2.6, selector = '.nx-border-button-march' }: MarchingBorderOptions = {}): Cleanup {
  if (!isBrowser) return () => {};
  let current = 1;
  let target = 1;
  let frame = 0;

  const apply = () => {
    const stroke = el.querySelector(selector);
    if (!stroke || typeof stroke.getAnimations !== 'function') return;
    for (const animation of stroke.getAnimations()) animation.playbackRate = current;
  };

  const tick = () => {
    current += (target - current) * 0.14;
    if (Math.abs(target - current) < 0.02) current = target;
    apply();
    frame = current === target ? 0 : requestAnimationFrame(tick);
  };

  const to = (next: number) => {
    target = next;
    if (!frame) frame = requestAnimationFrame(tick);
  };

  const onEnter = (event: PointerEvent) => {
    if (event.pointerType === 'mouse' || event.pointerType === 'pen') to(rate);
  };
  const onLeave = () => to(el.matches(':focus-visible') ? rate : 1);
  const onFocus = () => el.matches(':focus-visible') && to(rate);
  const onBlur = () => to(el.matches(':hover') ? rate : 1);
  // A march that restarts (after success or error) starts at the current rate.
  const onStart = () => apply();

  el.addEventListener('pointerenter', onEnter);
  el.addEventListener('pointerleave', onLeave);
  el.addEventListener('focus', onFocus);
  el.addEventListener('blur', onBlur);
  el.addEventListener('animationstart', onStart);
  return () => {
    cancelAnimationFrame(frame);
    current = 1;
    apply();
    el.removeEventListener('pointerenter', onEnter);
    el.removeEventListener('pointerleave', onLeave);
    el.removeEventListener('focus', onFocus);
    el.removeEventListener('blur', onBlur);
    el.removeEventListener('animationstart', onStart);
  };
}

/* ---- Pixel loader geometry ------------------------------------------------- */

export interface PixelCell {
  /** Column and row. */
  x: number;
  y: number;
  /** Distance from the centre, 0 (centre) to 1 (corner). */
  d: number;
  /** Two pseudo-random numbers in [0, 1), fixed per cell. */
  r: number;
  s: number;
}

const round3 = (value: number) => Math.round(value * 1000) / 1000;

/**
 * A pseudo-random number in [0, 1) for a cell index: a quadratic residue, all
 * integer arithmetic, so the Blade component computes exactly the same values.
 */
export function pixelNoise(index: number, salt = 1): number {
  const q = (index * 7919 + salt * 3571 + 17) % 10007;
  return round3(((q * q) % 10007) / 10007);
}

/**
 * The cells of a pixel loader: their position, distance from the centre (for
 * the centre-out ripple) and fixed pseudo-random numbers (for the chaotic
 * flicker), so the variants stay CSS-only.
 */
export function pixelLoaderCells(rows = 5, cols = 5): PixelCell[] {
  const r = Math.max(1, Math.floor(rows));
  const c = Math.max(1, Math.floor(cols));
  const cx = (c - 1) / 2;
  const cy = (r - 1) / 2;
  const far = Math.hypot(cx, cy) || 1;
  const cells: PixelCell[] = [];
  for (let y = 0; y < r; y++) {
    for (let x = 0; x < c; x++) {
      const i = y * c + x;
      cells.push({ x, y, d: round3(Math.hypot(x - cx, y - cy) / far), r: pixelNoise(i, 1), s: pixelNoise(i, 2) });
    }
  }
  return cells;
}

/* ---- Parametric curves ------------------------------------------------------ */

export type ParametricKind = 'rose' | 'spiro' | 'lissajous';

export interface ParametricOptions {
  /** Rose: r = cos(k·θ) with k = n / d (n petals for odd n and d = 1). */
  n?: number;
  d?: number;
  /** Spiro (hypotrochoid): fixed circle R, rolling circle r, pen `offset` from its centre. */
  R?: number;
  r?: number;
  offset?: number;
  /** Lissajous: x = sin(a·t + phase), y = sin(b·t). */
  a?: number;
  b?: number;
  phase?: number;
  /** Points along one full period (defaults grow with the period). */
  samples?: number;
  /** Space kept free around the curve inside the box. */
  padding?: number;
  /** The square box the curve is fitted into. */
  size?: number;
}

const TAU = Math.PI * 2;

function gcd(a: number, b: number): number {
  let x = Math.abs(Math.round(a));
  let y = Math.abs(Math.round(b));
  while (y) [x, y] = [y, x % y];
  return x || 1;
}

const fmt = (value: number) => String(Math.round(value * 10) / 10);

/**
 * An SVG path through a parametric curve in a `size`×`size` box (100 by
 * default): the curve's centre sits in the middle of the box and its furthest
 * point along either axis touches the padding. The path is closed, so a dash
 * travelling along it with pathLength="1" loops seamlessly.
 *
 *   rose       r = cos(k·θ), k = n / d
 *   spiro      a hypotrochoid: a pen on a circle of radius r rolling inside one of radius R
 *   lissajous  x = sin(a·t + phase), y = sin(b·t)
 */
export function parametricPath(kind: ParametricKind = 'rose', options: ParametricOptions = {}): string {
  const { padding = 8, size = 100 } = options;
  let period: number;
  let point: (t: number) => [number, number];

  if (kind === 'spiro') {
    const R = options.R ?? 7;
    const r = options.r ?? 3;
    const offset = options.offset ?? 5;
    const ratio = (R - r) / r;
    period = TAU * (Math.round(r) / gcd(R, r));
    point = (t) => [(R - r) * Math.cos(t) + offset * Math.cos(ratio * t), (R - r) * Math.sin(t) - offset * Math.sin(ratio * t)];
  } else if (kind === 'lissajous') {
    const a = options.a ?? 3;
    const b = options.b ?? 2;
    const phase = options.phase ?? Math.PI / 2;
    period = TAU;
    point = (t) => [Math.sin(a * t + phase), Math.sin(b * t)];
  } else {
    const divisor = gcd(options.n ?? 5, options.d ?? 1);
    const n = Math.round(options.n ?? 5) / divisor;
    const d = Math.round(options.d ?? 1) / divisor;
    const k = n / d;
    // Closes after π·d when n·d is odd, 2π·d otherwise.
    period = Math.PI * d * ((n * d) % 2 === 1 ? 1 : 2);
    point = (t) => [Math.cos(k * t) * Math.cos(t), Math.cos(k * t) * Math.sin(t)];
  }

  const samples = Math.max(12, Math.round(options.samples ?? Math.min(480, Math.max(160, (period / TAU) * 100))));
  const points: Array<[number, number]> = [];
  let reach = 0;
  for (let i = 0; i < samples; i++) {
    const [x, y] = point((i / samples) * period);
    points.push([x, y]);
    reach = Math.max(reach, Math.abs(x), Math.abs(y));
  }

  // The curve's own centre (the origin) goes to the middle of the box, so a
  // loader that turns slowly spins in place instead of wobbling.
  const half = size / 2;
  const scale = (half - padding) / (reach || 1);
  return `${points.map(([x, y], i) => `${i ? 'L' : 'M'}${fmt(half + x * scale)},${fmt(half + y * scale)}`).join('')}Z`;
}
