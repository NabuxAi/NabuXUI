/**
 * Framework-agnostic behaviours for the cards, sliders & carousels blocks:
 * FLIP layout moves, the pit slider's bars, the stacked scroll cards'
 * fallback, the ring and orbit spinners and the elastic grid.
 *
 * Like every core behaviour they only write custom properties and data
 * attributes; the look and the transitions live in css/blocks/cards.css.
 */
import { type Cleanup, direction, isBrowser, prefersReducedMotion } from '../env';
import { type SpringConfig, type SpringEasing, type SpringName, springEasing, springs } from '../spring';

const clamp = (value: number, min: number, max: number) => Math.min(max, Math.max(min, value));
const wrap = (value: number, count: number) => (count > 0 ? ((value % count) + count) % count : 0);

/* ---- Pure helpers (shared by React and Alpine) --------------------------------- */

/**
 * Signed distance of item `index` from the `active` one: negative before it,
 * positive after. Looping carousels take the short way round, so the last
 * item sits just before the first.
 */
export function carouselOffset(index: number, active: number, count: number, loop = true): number {
  const offset = index - active;
  if (!loop || count < 1) return offset;
  const wrapped = wrap(offset, count);
  return wrapped > count / 2 ? wrapped - count : wrapped;
}

/** The item facing front when a ring has turned `turn` items (turns are unbounded). */
export function ringIndex(turn: number, count: number): number {
  return wrap(Math.round(turn), count);
}

/** The turn nearest to `turn` that brings item `index` to the front. */
export function ringTurnFor(index: number, turn: number, count: number): number {
  const base = Math.round(turn);
  return base + carouselOffset(wrap(index, count), wrap(base, count), count, true);
}

export interface PitProfileOptions {
  /** How deep the pit dips at its centre, as a share of a bar (0–1). */
  depth?: number;
  /** Width of the falloff in bars (the gaussian's sigma). */
  spread?: number;
}

/** Heights (0–1) for `count` bars with a pit centred on bar `center` (fractional), with a gaussian falloff. */
export function pitProfile(count: number, center: number, { depth = 0.6, spread = 2.4 }: PitProfileOptions = {}): number[] {
  const width = 2 * spread * spread || 1;
  return Array.from({ length: Math.max(0, Math.floor(count)) }, (_, i) => {
    const d = i - center;
    return 1 - depth * Math.exp(-(d * d) / width);
  });
}

/**
 * How far the next card has slid over a stuck one: 0 as its top touches the
 * stuck card's bottom edge, 1 once it rests `offset` below the stuck card's top.
 */
export function coverProgress(bottom: number, nextTop: number, height: number, offset = 0): number {
  return clamp((bottom - nextTop) / Math.max(1, height - offset), 0, 1);
}

export interface ElasticState {
  value: number;
  velocity: number;
}

/**
 * Advance a damped spring that pulls `state.value` toward `target` by `dt`
 * seconds. Substepped, so it stays stable at any frame rate.
 */
export function elasticStep(state: ElasticState, target: number, dt: number, { stiffness, damping, mass = 1 }: SpringConfig): ElasticState {
  let { value, velocity } = state;
  const steps = Math.max(1, Math.ceil(dt * 240));
  const h = dt / steps;
  for (let i = 0; i < steps; i++) {
    velocity += ((-stiffness * (value - target) - damping * velocity) / mass) * h;
    value += velocity * h;
  }
  return { value, velocity };
}

/* ---- Internals ------------------------------------------------------------------- */

const timings: Partial<Record<SpringName, SpringEasing>> = {};

/** A spring as a Web Animations timing: its linear() curve where supported, a strong ease-out elsewhere. */
function springTiming(name: SpringName): SpringEasing {
  const spring = (timings[name] ??= springEasing(springs[name]));
  const linear = typeof CSS !== 'undefined' && CSS.supports('animation-timing-function', 'linear(0, 1)');
  return linear ? spring : { easing: 'cubic-bezier(0.16, 1, 0.3, 1)', duration: Math.round(spring.duration * 0.75) };
}

interface Driver {
  readonly value: number;
  readonly target: number;
  /** Spring toward `target`, optionally from a new velocity (units per second). */
  to(target: number, velocity?: number): void;
  /** Jump to `value` and hold it. */
  set(value: number): void;
  /** Stop where it is. */
  stop(): void;
}

/** A number that springs toward its target on animation frames, keeping its velocity when retargeted. */
function driver(onFrame: (value: number) => void, config: SpringConfig): Driver {
  let value = 0;
  let velocity = 0;
  let target = 0;
  let frame = 0;
  let last = 0;

  const tick = (now: number) => {
    const dt = Math.min(0.064, Math.max(0.001, (now - last) / 1000));
    last = now;
    ({ value, velocity } = elasticStep({ value, velocity }, target, dt, config));
    if (Math.abs(value - target) < 0.0005 && Math.abs(velocity) < 0.005) {
      value = target;
      velocity = 0;
      frame = 0;
    } else {
      frame = requestAnimationFrame(tick);
    }
    onFrame(value);
  };

  return {
    get value() {
      return value;
    },
    get target() {
      return target;
    },
    to(next, v) {
      target = next;
      if (v !== undefined) velocity = v;
      if (!frame) {
        last = performance.now();
        frame = requestAnimationFrame(tick);
      }
    },
    set(next) {
      cancelAnimationFrame(frame);
      frame = 0;
      value = target = next;
      velocity = 0;
      onFrame(value);
    },
    stop() {
      cancelAnimationFrame(frame);
      frame = 0;
      velocity = 0;
      target = value;
    },
  };
}

/** Parts of a widget where a press means typing or picking, never dragging. */
const NO_DRAG = 'input, textarea, select, [contenteditable], [data-nx-no-drag]';

/* ---- FLIP ----------------------------------------------------------------------- */

export interface FlipLayoutOptions {
  /** A CSS timing function; the gentle spring by default. */
  easing?: string;
  /** Duration in ms (the spring's own settle time by default). */
  duration?: number;
  /** Delay between one element and the next, ms. */
  stagger?: number;
  /** Also play size changes as a scale (the content stretches while it plays). */
  scale?: boolean;
  /** An element whose height should follow along instead of jumping. */
  container?: HTMLElement | null;
}

const flights = new WeakMap<Element, Animation>();

/**
 * First, Last, Invert, Play. Measures `elements`, runs `mutate` (synchronous,
 * or returning a promise when the framework renders later), measures again and
 * plays every element from where it was to where it landed with the gentle
 * spring. Animate wrappers that carry no transform of their own; what is
 * inside them can keep theirs. A move that interrupts a running one starts
 * from wherever the element is on screen, so reversing mid-flight is smooth.
 * Reduced motion: the elements fade in place instead.
 */
export async function flip(elements: Iterable<Element> | ArrayLike<Element>, mutate: () => unknown, options: FlipLayoutOptions = {}): Promise<void> {
  const list = Array.from(elements).filter((el): el is HTMLElement => el instanceof HTMLElement);
  if (!isBrowser || typeof HTMLElement.prototype.animate !== 'function') {
    await mutate();
    return;
  }

  const reduce = prefersReducedMotion();
  const first = list.map((el) => el.getBoundingClientRect());
  const box = options.container ?? null;
  const boxBefore = box?.getBoundingClientRect().height ?? 0;
  for (const el of [...list, ...(box ? [box] : [])]) {
    flights.get(el)?.cancel();
    flights.delete(el);
  }

  await mutate();

  const spring = springTiming('gentle');
  const easing = options.easing ?? spring.easing;
  const duration = options.duration ?? spring.duration;
  const stagger = options.stagger ?? 0;
  const running: Animation[] = [];
  const track = (el: HTMLElement, animation: Animation) => {
    flights.set(el, animation);
    running.push(animation);
    animation.finished.then(() => flights.get(el) === animation && flights.delete(el)).catch(() => {});
  };

  list.forEach((el, i) => {
    if (!el.isConnected) return;
    const before = first[i]!;
    const after = el.getBoundingClientRect();
    if (reduce) {
      if (before.top !== after.top || before.left !== after.left) track(el, el.animate([{ opacity: 0.35 }, { opacity: 1 }], { duration: 200, easing: 'ease-out' }));
      return;
    }
    // Centres, so a rotation inside the element cannot skew the measurement.
    const dx = before.left + before.width / 2 - (after.left + after.width / 2);
    const dy = before.top + before.height / 2 - (after.top + after.height / 2);
    const sx = options.scale && after.width ? before.width / after.width : 1;
    const sy = options.scale && after.height ? before.height / after.height : 1;
    if (Math.abs(dx) < 0.5 && Math.abs(dy) < 0.5 && Math.abs(sx - 1) < 0.005 && Math.abs(sy - 1) < 0.005) return;
    const from = `translate(${dx.toFixed(2)}px, ${dy.toFixed(2)}px)${sx !== 1 || sy !== 1 ? ` scale(${sx.toFixed(4)}, ${sy.toFixed(4)})` : ''}`;
    track(el, el.animate([{ transform: from }, { transform: 'none' }], { duration, easing, delay: i * stagger, fill: 'backwards' }));
  });

  if (box && !reduce) {
    const boxAfter = box.getBoundingClientRect().height;
    if (Math.abs(boxAfter - boxBefore) > 0.5) {
      track(box, box.animate([{ height: `${boxBefore}px` }, { height: `${boxAfter}px` }], { duration, easing: springTiming('soft').easing }));
    }
  }

  await Promise.allSettled(running.map((animation) => animation.finished));
}

/* ---- Pit slider ----------------------------------------------------------------- */

export interface PitSliderOptions extends PitProfileOptions {
  /** The bars inside the element. */
  bars?: string;
}

const STEP_KEYS = new Set(['ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'PageUp', 'PageDown', 'Home', 'End']);

/**
 * The pit slider: a row of bars over a transparent native range. While the
 * thumb is dragged (or nudged with the keys) the bars around it dip into a
 * pit; each bar gets its height as --h (0–1). The fill and the value bubble
 * follow --nx-frac, which the framework layer writes from the value.
 */
export function pitSlider(el: HTMLElement, { bars = '.nx-pit-bar', depth = 0.62, spread = 2.4 }: PitSliderOptions = {}): Cleanup {
  if (!isBrowser) return () => {};
  const input = el.querySelector<HTMLInputElement>('input[type="range"]');
  if (!input) return () => {};
  const reduce = prefersReducedMotion();
  let pressure = 0;
  let pressed = false;
  let frame = 0;
  let keyTimer = 0;

  const paint = () => {
    frame = 0;
    const nodes = Array.from(el.querySelectorAll<HTMLElement>(bars));
    if (!nodes.length) return;
    const min = Number(input.min || 0);
    const max = Number(input.max || 100);
    const fraction = clamp((Number(input.value) - min) / (max - min || 1), 0, 1);
    // The bars span exactly the thumb's travel, so bar 0 sits under the minimum.
    const heights = pressure > 0 ? pitProfile(nodes.length, fraction * (nodes.length - 1), { depth: depth * pressure, spread }) : null;
    nodes.forEach((node, i) => node.style.setProperty('--h', heights ? heights[i]!.toFixed(3) : '1'));
  };

  const schedule = () => {
    if (!frame) frame = requestAnimationFrame(paint);
  };

  const press = (next: number) => {
    pressure = reduce ? 0 : next;
    el.toggleAttribute('data-pit', pressure > 0);
    schedule();
  };

  const onDown = (event: PointerEvent) => {
    if (event.button !== 0 || input.disabled) return;
    pressed = true;
    el.setAttribute('data-dragging', '');
    press(1);
  };

  const onUp = () => {
    if (!pressed) return;
    pressed = false;
    el.removeAttribute('data-dragging');
    press(0);
  };

  const onKey = (event: KeyboardEvent) => {
    if (!STEP_KEYS.has(event.key) || pressed) return;
    clearTimeout(keyTimer);
    press(0.55);
    keyTimer = window.setTimeout(() => !pressed && press(0), 650);
  };

  const onBlur = () => {
    clearTimeout(keyTimer);
    if (!pressed) press(0);
  };

  input.addEventListener('pointerdown', onDown);
  window.addEventListener('pointerup', onUp);
  window.addEventListener('pointercancel', onUp);
  input.addEventListener('input', schedule);
  input.addEventListener('keydown', onKey);
  input.addEventListener('blur', onBlur);
  paint();

  return () => {
    cancelAnimationFrame(frame);
    clearTimeout(keyTimer);
    input.removeEventListener('pointerdown', onDown);
    window.removeEventListener('pointerup', onUp);
    window.removeEventListener('pointercancel', onUp);
    input.removeEventListener('input', schedule);
    input.removeEventListener('keydown', onKey);
    input.removeEventListener('blur', onBlur);
    el.removeAttribute('data-pit');
    el.removeAttribute('data-dragging');
  };
}

/* ---- Stacked scroll cards ------------------------------------------------------- */

/** The condition the stylesheet uses to run the stacked cards on a scroll timeline. */
const SCROLL_DRIVEN = '(animation-timeline: view()) and (animation-range: entry)';

export interface StackedScrollOptions {
  /** The sticky items. */
  item?: string;
  /** Run even where CSS scroll-driven animations already do the work. */
  force?: boolean;
}

/**
 * Fallback for browsers without scroll-driven animations: writes each sticky
 * item's cover progress (--nx-progress, 0–1: how far the next card has slid
 * over it) and depth (--nx-depth: how many cards lie on top of it). Where
 * `animation-timeline: view()` exists the stylesheet does it all and this
 * stands down.
 */
export function stackedScroll(el: HTMLElement, { item = '.nx-stacked-item', force = false }: StackedScrollOptions = {}): Cleanup {
  if (!isBrowser) return () => {};
  if (!force && typeof CSS !== 'undefined' && CSS.supports(SCROLL_DRIVEN)) return () => {};
  el.setAttribute('data-nx-scripted', '');

  let items: HTMLElement[] = [];
  let offsets: number[] = [];
  let frame = 0;
  let visible = true;

  const measure = () => {
    items = Array.from(el.querySelectorAll<HTMLElement>(item));
    const tops = items.map((node) => parseFloat(getComputedStyle(node).top) || 0);
    offsets = tops.map((top, i) => Math.max(0, (tops[i + 1] ?? top) - top));
  };

  const update = () => {
    frame = 0;
    const rects = items.map((node) => node.getBoundingClientRect());
    let depth = 0;
    for (let i = items.length - 1; i >= 0; i--) {
      const next = rects[i + 1];
      const progress = next ? coverProgress(rects[i]!.bottom, next.top, rects[i]!.height, offsets[i]) : 0;
      depth += progress;
      // Compared with what is on the element, so a server re-render cannot leave it stale.
      const node = items[i]!;
      if (node.style.getPropertyValue('--nx-progress') !== progress.toFixed(3)) node.style.setProperty('--nx-progress', progress.toFixed(3));
      if (node.style.getPropertyValue('--nx-depth') !== depth.toFixed(3)) node.style.setProperty('--nx-depth', depth.toFixed(3));
    }
  };

  const schedule = () => {
    if (visible && !frame) frame = requestAnimationFrame(update);
  };

  const onResize = () => {
    measure();
    schedule();
  };

  const observer = 'IntersectionObserver' in window
    ? new IntersectionObserver(([entry]) => {
        visible = !!entry?.isIntersecting;
        schedule();
      }, { rootMargin: '25% 0px' })
    : null;
  observer?.observe(el);

  measure();
  update();
  window.addEventListener('scroll', schedule, { passive: true, capture: true });
  window.addEventListener('resize', onResize);

  return () => {
    cancelAnimationFrame(frame);
    observer?.disconnect();
    window.removeEventListener('scroll', schedule, { capture: true });
    window.removeEventListener('resize', onResize);
    el.removeAttribute('data-nx-scripted');
    for (const node of items) {
      node.style.removeProperty('--nx-progress');
      node.style.removeProperty('--nx-depth');
    }
  };
}

/* ---- Ring carousel ---------------------------------------------------------------- */

export interface RingCarouselOptions {
  count: number;
  /** The item facing front at the start. */
  index?: number;
  /** The front item changed (live, as items pass the front while it spins). */
  onChange?: (index: number) => void;
  /** The cards, measured for how far a drag turns the ring. */
  item?: string;
}

export interface RingCarouselController {
  /** The item facing front now. */
  readonly index: number;
  go(index: number): void;
  next(): void;
  prev(): void;
  destroy: Cleanup;
}

/**
 * Spins a ring of cards: drag with inertia and a spring that snaps to the
 * nearest card, horizontal wheel or trackpad, arrow keys, Home and End.
 * Writes the ring's position as --nx-turn (in items, unbounded: 9.5 is half
 * way between the ninth and tenth step); the stylesheet turns it into angles.
 * "Next" always comes from the inline-end side.
 */
export function ringCarousel(el: HTMLElement, options: RingCarouselOptions): RingCarouselController {
  const count = Math.max(1, Math.floor(options.count));
  let index = wrap(Math.round(options.index ?? 0), count);
  if (!isBrowser) return { index, go() {}, next() {}, prev() {}, destroy() {} };

  const reduce = prefersReducedMotion();
  const spin = driver((turn) => {
    el.style.setProperty('--nx-turn', turn.toFixed(4));
    const next = ringIndex(turn, count);
    if (next !== index) {
      index = next;
      options.onChange?.(index);
    }
  }, springs.gentle);
  spin.set(index);

  const settle = (turn: number, velocity = 0) => (reduce ? spin.set(turn) : spin.to(turn, velocity));
  const go = (to: number) => settle(ringTurnFor(to, spin.target, count));
  const itemSize = () => (el.querySelector<HTMLElement>(options.item ?? '.nx-ring-card')?.offsetWidth || 220) * 1.15;

  let pointer = -1;
  let startX = 0;
  let startTurn = 0;
  let moved = false;
  let dir: 1 | -1 = 1;
  let size = 240;
  let samples: Array<{ t: number; turn: number }> = [];
  let swallow = false;

  const onDown = (event: PointerEvent) => {
    if (pointer !== -1 || event.button !== 0 || (event.target as Element).closest(NO_DRAG)) return;
    pointer = event.pointerId;
    startX = event.clientX;
    moved = false;
    dir = direction(el);
    size = itemSize();
    startTurn = spin.value;
    samples = [{ t: event.timeStamp, turn: startTurn }];
  };

  const onMove = (event: PointerEvent) => {
    if (event.pointerId !== pointer) return;
    const dx = event.clientX - startX;
    if (!moved) {
      if (Math.abs(dx) < 6) return;
      moved = true;
      spin.stop();
      startTurn = spin.value;
      startX = event.clientX;
      el.setPointerCapture(pointer);
      el.setAttribute('data-dragging', '');
      return;
    }
    const turn = startTurn - (dx * dir) / size;
    spin.set(turn);
    samples.push({ t: event.timeStamp, turn });
    while (samples.length > 2 && event.timeStamp - samples[0]!.t > 90) samples.shift();
  };

  const onUp = (event: PointerEvent) => {
    if (event.pointerId !== pointer) return;
    pointer = -1;
    if (!moved) return;
    el.removeAttribute('data-dragging');
    const a = samples[0]!;
    const b = samples[samples.length - 1]!;
    // Held still before letting go: no throw.
    const still = event.type === 'pointercancel' || b.t <= a.t || event.timeStamp - b.t > 80;
    const velocity = still ? 0 : ((b.turn - a.turn) / (b.t - a.t)) * 1000;
    // Throw: project where the flick would coast to, then snap to the card there.
    let target = Math.round(spin.value + velocity * 0.22);
    // A quick flick (over 0.11 px/ms) moves at least one card its way, however short.
    if ((Math.abs(velocity) * size) / 1000 > 0.11 && Math.sign(target - startTurn) !== Math.sign(velocity)) target = Math.round(startTurn) + Math.sign(velocity);
    const from = Math.round(spin.value);
    settle(clamp(target, from - count, from + count), velocity);
    swallow = true;
    setTimeout(() => (swallow = false));
  };

  // The release that ends a drag is not a click on the card under it.
  const onClick = (event: MouseEvent) => {
    if (!swallow) return;
    swallow = false;
    event.preventDefault();
    event.stopPropagation();
  };

  let wheelTimer = 0;
  const onWheel = (event: WheelEvent) => {
    if (Math.abs(event.deltaX) <= Math.abs(event.deltaY)) return;
    event.preventDefault();
    if (!wheelTimer) {
      dir = direction(el);
      size = itemSize();
    }
    const unit = event.deltaMode === 1 ? 16 : event.deltaMode === 2 ? el.clientWidth : 1;
    spin.set(spin.value + (event.deltaX * unit * dir) / size);
    clearTimeout(wheelTimer);
    wheelTimer = window.setTimeout(() => {
      wheelTimer = 0;
      settle(Math.round(spin.value));
    }, 140);
  };

  const onKey = (event: KeyboardEvent) => {
    if ((event.target as Element).closest(NO_DRAG)) return;
    const d = direction(el);
    const step = event.key === 'ArrowRight' ? d : event.key === 'ArrowLeft' ? -d : 0;
    if (step) settle(Math.round(spin.target) + step);
    else if (event.key === 'Home') go(0);
    else if (event.key === 'End') go(count - 1);
    else return;
    event.preventDefault();
  };

  el.addEventListener('pointerdown', onDown);
  el.addEventListener('pointermove', onMove);
  el.addEventListener('pointerup', onUp);
  el.addEventListener('pointercancel', onUp);
  el.addEventListener('click', onClick, true);
  el.addEventListener('wheel', onWheel, { passive: false });
  el.addEventListener('keydown', onKey);

  return {
    get index() {
      return index;
    },
    go,
    next: () => settle(Math.round(spin.target) + 1),
    prev: () => settle(Math.round(spin.target) - 1),
    destroy() {
      spin.stop();
      clearTimeout(wheelTimer);
      el.removeEventListener('pointerdown', onDown);
      el.removeEventListener('pointermove', onMove);
      el.removeEventListener('pointerup', onUp);
      el.removeEventListener('pointercancel', onUp);
      el.removeEventListener('click', onClick, true);
      el.removeEventListener('wheel', onWheel);
      el.removeEventListener('keydown', onKey);
      el.removeAttribute('data-dragging');
    },
  };
}

/* ---- Orbit showcase ----------------------------------------------------------------- */

export interface OrbitShowcaseOptions {
  count: number;
  /** Seconds for one full revolution. */
  period?: number;
  /** Start paused (the pause button's state). */
  paused?: boolean;
  /** The orbiting items. */
  item?: string;
  /** The item nearest the front changed. */
  onFront?: (index: number) => void;
}

export interface OrbitShowcaseController {
  readonly front: number;
  readonly paused: boolean;
  /** Bring item `index` to the front (it holds there a moment). */
  focus(index: number): void;
  pause(): void;
  play(): void;
  destroy: Cleanup;
}

/**
 * Keeps items orbiting slowly around a centre: writes --nx-orbit (the orbit's
 * position in items) and marks the front item [data-front]. Hovering pauses,
 * focusing or clicking an item springs it to the front, and nothing turns
 * while the orbit is off screen, the tab is hidden or motion is reduced.
 */
export function orbitShowcase(el: HTMLElement, options: OrbitShowcaseOptions): OrbitShowcaseController {
  const count = Math.max(1, Math.floor(options.count));
  const period = Math.max(4, options.period ?? 40);
  const selector = options.item ?? '.nx-orbit-item';
  let front = -1;
  let turn = 0;
  let paused = !!options.paused;
  if (!isBrowser) return { front: 0, paused, focus() {}, pause() {}, play() {}, destroy() {} };

  const reduce = prefersReducedMotion();
  let hovering = false;
  let focused = false;
  let visible = true;
  let holdUntil = 0;
  let spring: ElasticState | null = null;
  let target = 0;
  let frame = 0;
  let last = 0;

  const items = () => Array.from(el.querySelectorAll<HTMLElement>(selector));

  const write = () => {
    el.style.setProperty('--nx-orbit', turn.toFixed(4));
    const next = ringIndex(turn, count);
    if (next === front) return;
    front = next;
    items().forEach((node, i) => node.toggleAttribute('data-front', i === front));
    options.onFront?.(front);
  };

  const drifting = (now: number) => !paused && !hovering && !focused && visible && !reduce && !document.hidden && now >= holdUntil;

  const tick = (now: number) => {
    frame = 0;
    const dt = Math.min(0.064, Math.max(0, (now - last) / 1000));
    last = now;
    if (spring) {
      spring = elasticStep(spring, target, dt, springs.gentle);
      turn = spring.value;
      if (Math.abs(turn - target) < 0.0005 && Math.abs(spring.velocity) < 0.005) {
        turn = target;
        spring = null;
      }
    } else if (drifting(now)) {
      turn += (dt * count) / period;
    } else {
      // Resting; wake again once a hold expires.
      if (holdUntil > now && !paused) setTimeout(wake, holdUntil - now + 16);
      return;
    }
    write();
    frame = requestAnimationFrame(tick);
  };

  function wake() {
    if (frame) return;
    last = performance.now();
    frame = requestAnimationFrame(tick);
  }

  const bring = (index: number, hold = 0) => {
    target = ringTurnFor(index, spring ? target : turn, count);
    holdUntil = hold ? performance.now() + hold : holdUntil;
    if (reduce) {
      turn = target;
      spring = null;
      write();
      return;
    }
    // Carry the drift's speed into the spring so the hand-off has no hitch.
    spring = { value: turn, velocity: spring?.velocity ?? (drifting(performance.now()) ? count / period : 0) };
    wake();
  };

  const indexOf = (node: Element | null) => (node ? items().indexOf(node as HTMLElement) : -1);

  const onEnter = (event: PointerEvent) => {
    if (event.pointerType === 'mouse') hovering = true;
  };
  const onLeave = () => {
    hovering = false;
    wake();
  };
  const onFocusIn = (event: FocusEvent) => {
    const target = event.target as Element;
    // Keyboard focus holds the orbit while it stays inside; a click's focus only brings the item forward.
    if (target.matches(':focus-visible')) focused = true;
    const i = indexOf(target.closest(selector));
    if (i >= 0) bring(i);
  };
  const onFocusOut = (event: FocusEvent) => {
    if (el.contains(event.relatedTarget as Node | null)) return;
    focused = false;
    wake();
  };
  const onClick = (event: MouseEvent) => {
    const i = indexOf((event.target as Element).closest(selector));
    // The item in front follows its link; any other is brought forward first.
    if (i < 0 || i === front) return;
    event.preventDefault();
    bring(i, 4000);
  };
  const onVisibility = () => wake();

  const observer = 'IntersectionObserver' in window
    ? new IntersectionObserver(([entry]) => {
        visible = !!entry?.isIntersecting;
        wake();
      })
    : null;
  observer?.observe(el);

  el.addEventListener('pointerenter', onEnter);
  el.addEventListener('pointerleave', onLeave);
  el.addEventListener('focusin', onFocusIn);
  el.addEventListener('focusout', onFocusOut);
  el.addEventListener('click', onClick);
  document.addEventListener('visibilitychange', onVisibility);
  el.toggleAttribute('data-paused', paused);
  write();
  wake();

  return {
    get front() {
      return front;
    },
    get paused() {
      return paused;
    },
    focus: (index: number) => bring(index, 4000),
    pause() {
      paused = true;
      el.setAttribute('data-paused', '');
    },
    play() {
      paused = false;
      holdUntil = 0;
      el.removeAttribute('data-paused');
      wake();
    },
    destroy() {
      cancelAnimationFrame(frame);
      frame = -1;
      observer?.disconnect();
      el.removeEventListener('pointerenter', onEnter);
      el.removeEventListener('pointerleave', onLeave);
      el.removeEventListener('focusin', onFocusIn);
      el.removeEventListener('focusout', onFocusOut);
      el.removeEventListener('click', onClick);
      document.removeEventListener('visibilitychange', onVisibility);
    },
  };
}

/* ---- Elastic grid ---------------------------------------------------------------------- */

export interface ElasticGridOptions {
  /** The columns inside the element. */
  column?: string;
  /** Largest distance (px) a column may trail behind the scroll. */
  max?: number;
  /** Parallax travel (px) of alternate columns across the viewport. */
  parallax?: number;
}

/** Stiffness per column: each trails the scroll by its own amount. */
const COLUMN_STIFFNESS = [120, 64, 150, 84, 136, 58];

/**
 * Columns that follow the scroll on springs of different stiffness, so they
 * trail and settle one after another, with a little parallax between them.
 * Each column gets its offset as --nx-shift. Nothing runs off screen or
 * under reduced motion (the grid stays still).
 */
export function elasticGrid(el: HTMLElement, { column = '.nx-elastic-col', max = 90, parallax = 40 }: ElasticGridOptions = {}): Cleanup {
  if (!isBrowser || prefersReducedMotion()) return () => {};
  let columns: HTMLElement[] = [];
  let states: ElasticState[] = [];
  let frame = 0;
  let last = 0;
  let visible = false;

  const collect = () => {
    columns = Array.from(el.querySelectorAll<HTMLElement>(column));
    const y = -el.getBoundingClientRect().top;
    states = columns.map(() => ({ value: y, velocity: 0 }));
  };

  const tick = (now: number) => {
    frame = 0;
    const rect = el.getBoundingClientRect();
    const y = -rect.top;
    const view = window.innerHeight || 1;
    const progress = clamp((view - rect.top) / (view + rect.height), 0, 1);
    const dt = Math.min(0.064, Math.max(0.001, (now - last) / 1000));
    last = now;
    let moving = false;

    columns.forEach((col, i) => {
      const stiffness = COLUMN_STIFFNESS[i % COLUMN_STIFFNESS.length]!;
      const state = elasticStep(states[i]!, y, dt, { stiffness, damping: 2 * Math.sqrt(stiffness) * 0.72 });
      // A long jump (an anchor link) should not leave a column pinned at its limit.
      state.value = clamp(state.value, y - max * 1.5, y + max * 1.5);
      states[i] = state;
      const lag = clamp(y - state.value, -max, max);
      const drift = (progress - 0.5) * parallax * (i % 2 ? 1 : -1);
      const shift = `${(lag + drift).toFixed(1)}px`;
      if (col.style.getPropertyValue('--nx-shift') !== shift) col.style.setProperty('--nx-shift', shift);
      if (Math.abs(y - state.value) > 0.2 || Math.abs(state.velocity) > 0.5) moving = true;
    });

    if (moving && visible) frame = requestAnimationFrame(tick);
  };

  const wake = () => {
    if (frame || !visible) return;
    last = performance.now();
    frame = requestAnimationFrame(tick);
  };

  const onResize = () => {
    collect();
    wake();
  };

  const observer = 'IntersectionObserver' in window
    ? new IntersectionObserver(([entry]) => {
        const was = visible;
        visible = !!entry?.isIntersecting;
        if (visible && !was) collect();
        wake();
      }, { rootMargin: '15% 0px' })
    : null;
  if (observer) observer.observe(el);
  else visible = true;

  collect();
  wake();
  window.addEventListener('scroll', wake, { passive: true, capture: true });
  window.addEventListener('resize', onResize);

  return () => {
    cancelAnimationFrame(frame);
    observer?.disconnect();
    window.removeEventListener('scroll', wake, { capture: true });
    window.removeEventListener('resize', onResize);
    for (const col of columns) col.style.removeProperty('--nx-shift');
  };
}
