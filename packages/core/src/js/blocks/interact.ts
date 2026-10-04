/**
 * Framework-agnostic behaviours for the interaction blocks: hold-to-confirm,
 * slide-to-confirm, the rolling odometer, the undo snackbar's timer, the
 * sortable list (pointer drag + keyboard reorder with FLIP), the lightbox's
 * stage (zoom, pan, swipe) and its open/close morph, the compare slider, swipe
 * actions, the password scoring, and the pull cord's verlet rope.
 *
 * As everywhere in NabuXUI, the motion lives in css/blocks/interact.css; these
 * measure, write custom properties and attributes, call back, and clean up.
 * Pure logic (password scoring, odometer diffing, reorder maths) is exported
 * on its own so it can be tested and reused without a DOM.
 */
import { type Cleanup, direction, isBrowser, prefersReducedMotion } from '../env';
import { type SpringEasing, type SpringName, springEasing, springs } from '../spring';
import { type NumberPart, localeDigits, numberParts } from '../text';
import { supportsViewTransitions } from '../view-transition';

const easings = new Map<SpringName, SpringEasing>();

/** The `linear()` easing and settle time of a named spring, computed once. */
function spring(name: SpringName): SpringEasing {
  let easing = easings.get(name);
  if (!easing) {
    easing = springEasing(springs[name]);
    easings.set(name, easing);
  }
  return easing;
}

const clamp = (value: number, min: number, max: number) => Math.min(max, Math.max(min, value));

/**
 * Rubber-band damping past a bound: the further you pull, the less it gives,
 * never more than `limit` px.
 */
export function rubberBand(overshoot: number, limit = 48): number {
  if (overshoot <= 0) return 0;
  return limit * (1 - 1 / ((overshoot * 0.55) / limit + 1));
}

/** Fill `{name}` placeholders in a message. */
export function fillMessage(template: string, params: Record<string, string | number>): string {
  return template.replace(/\{(\w+)\}/g, (match, key: string) => (key in params ? String(params[key]) : match));
}

/** The flick speed (px/ms) past which a short drag still counts. */
export const FLICK_VELOCITY = 0.11;

/** Tracks a pointer's speed along one axis over the last ~100ms. */
function velocityTracker() {
  let samples: Array<[number, number]> = [];
  return {
    reset(at: number) {
      samples = [[performance.now(), at]];
    },
    push(at: number) {
      const now = performance.now();
      samples.push([now, at]);
      while (samples.length > 2 && now - samples[0]![0] > 100) samples.shift();
    },
    /** px/ms, signed. */
    speed(): number {
      if (samples.length < 2) return 0;
      const [t0, a] = samples[0]!;
      const [t1, b] = samples[samples.length - 1]!;
      return (b - a) / Math.max(1, t1 - t0);
    },
  };
}

function buzz(ms = 8) {
  try {
    navigator.vibrate?.(ms);
  } catch {
    /* not allowed here */
  }
}

/* ---- Hold to confirm ------------------------------------------------------- */

export type HoldState = 'idle' | 'holding' | 'done';

export interface HoldToConfirmOptions {
  /** How long (ms) the press must be held. */
  duration?: number;
  /** Return to idle this long (ms) after confirming; 0 keeps the done state. */
  resetAfter?: number;
  onConfirm?: () => void;
  onStateChange?: (state: HoldState) => void;
}

/**
 * A button that confirms only after it has been held for `duration`: pointer
 * or Space/Enter. Releasing early drains the fill back. Writes `--_p` (0–1)
 * and `data-state` (idle | holding | done) on the element.
 */
export function holdToConfirm(el: HTMLElement, options: HoldToConfirmOptions = {}): Cleanup {
  if (!isBrowser) return () => {};
  const { duration = 1200, resetAfter = 2400 } = options;

  let state: HoldState = 'idle';
  let progress = 0;
  let frame = 0;
  let last = 0;
  let pointerId = -1;
  let resetTimer: ReturnType<typeof setTimeout> | undefined;

  const disabled = () => el.matches(':disabled, [aria-disabled="true"]');

  const write = () => el.style.setProperty('--_p', progress.toFixed(4));
  const setState = (next: HoldState) => {
    if (state === next) return;
    state = next;
    el.setAttribute('data-state', next);
    options.onStateChange?.(next);
  };

  const tick = (now: number) => {
    const dt = Math.min(64, now - last);
    last = now;
    if (state === 'holding') {
      progress = Math.min(1, progress + dt / duration);
      write();
      if (progress >= 1) {
        frame = 0;
        setState('done');
        buzz(14);
        options.onConfirm?.();
        if (resetAfter > 0) resetTimer = setTimeout(reset, resetAfter);
        return;
      }
    } else {
      // Released early: drain three times faster than it filled.
      progress = Math.max(0, progress - (dt / duration) * 3);
      write();
      if (progress <= 0) {
        frame = 0;
        return;
      }
    }
    frame = requestAnimationFrame(tick);
  };

  const run = () => {
    if (frame) return;
    last = performance.now();
    frame = requestAnimationFrame(tick);
  };

  const start = () => {
    if (state !== 'idle' || disabled()) return;
    setState('holding');
    buzz(6);
    run();
  };

  const release = () => {
    pointerId = -1;
    if (state !== 'holding') return;
    setState('idle');
    run();
  };

  function reset() {
    clearTimeout(resetTimer);
    if (frame) cancelAnimationFrame(frame);
    frame = 0;
    progress = 0;
    write();
    setState('idle');
  }

  const onDown = (event: PointerEvent) => {
    if (event.button !== 0 || !event.isPrimary || pointerId !== -1) return;
    pointerId = event.pointerId;
    try {
      el.setPointerCapture(pointerId);
    } catch {
      /* synthetic events */
    }
    start();
  };
  const onUp = (event: PointerEvent) => {
    if (event.pointerId === pointerId) release();
  };
  const isKey = (event: KeyboardEvent) => event.key === ' ' || event.key === 'Enter';
  const onKeyDown = (event: KeyboardEvent) => {
    if (!isKey(event)) return;
    event.preventDefault();
    if (!event.repeat) start();
  };
  const onKeyUp = (event: KeyboardEvent) => {
    if (!isKey(event)) return;
    event.preventDefault();
    release();
  };
  // A long press must not open the touch context menu.
  const onContext = (event: Event) => state === 'holding' && event.preventDefault();

  el.setAttribute('data-state', state);
  write();
  el.addEventListener('pointerdown', onDown);
  el.addEventListener('pointerup', onUp);
  el.addEventListener('pointercancel', onUp);
  el.addEventListener('lostpointercapture', onUp);
  el.addEventListener('keydown', onKeyDown);
  el.addEventListener('keyup', onKeyUp);
  el.addEventListener('blur', release);
  el.addEventListener('contextmenu', onContext);
  el.addEventListener('nx-reset', reset);

  return () => {
    clearTimeout(resetTimer);
    if (frame) cancelAnimationFrame(frame);
    el.removeEventListener('pointerdown', onDown);
    el.removeEventListener('pointerup', onUp);
    el.removeEventListener('pointercancel', onUp);
    el.removeEventListener('lostpointercapture', onUp);
    el.removeEventListener('keydown', onKeyDown);
    el.removeEventListener('keyup', onKeyUp);
    el.removeEventListener('blur', release);
    el.removeEventListener('contextmenu', onContext);
    el.removeEventListener('nx-reset', reset);
  };
}

/* ---- Slide to confirm ------------------------------------------------------ */

export type SlideState = 'idle' | 'dragging' | 'done';

export interface SlideToConfirmOptions {
  /** Fraction of the track (0–1) past which a release confirms. */
  threshold?: number;
  /** Return to the start this long (ms) after confirming; 0 keeps the done state. */
  resetAfter?: number;
  /** Keyboard step, as a fraction of the track. */
  step?: number;
  onConfirm?: () => void;
  onStateChange?: (state: SlideState) => void;
}

/**
 * A thumb (`.nx-slide-confirm-thumb`, a role="slider" button) dragged along
 * the element to confirm. Writes `--_x` (px along the inline axis), `--_p`
 * (0–1) and `data-state`; `[data-dragging]` turns the snap-back spring off.
 * Arrow keys step, Home returns, End confirms. Dispatch `nx-reset` to rewind.
 */
export function slideToConfirm(el: HTMLElement, options: SlideToConfirmOptions = {}): Cleanup {
  if (!isBrowser) return () => {};
  const found = el.querySelector<HTMLElement>('.nx-slide-confirm-thumb');
  if (!found) return () => {};
  const thumb: HTMLElement = found;
  const { threshold = 0.9, resetAfter = 0, step = 0.1 } = options;

  let x = 0;
  let max = 1;
  let startX = 0;
  let startPos = 0;
  let pointerId = -1;
  let done = false;
  let resetTimer: ReturnType<typeof setTimeout> | undefined;
  const velocity = velocityTracker();

  const measure = () => {
    const style = getComputedStyle(el);
    const pad = parseFloat(style.paddingInlineStart) + parseFloat(style.paddingInlineEnd);
    max = Math.max(1, el.clientWidth - pad - thumb.offsetWidth);
  };

  const write = () => {
    const p = clamp(x / max, 0, 1);
    el.style.setProperty('--_x', `${x.toFixed(1)}px`);
    el.style.setProperty('--_p', p.toFixed(3));
    thumb.setAttribute('aria-valuenow', String(Math.round(p * 100)));
  };

  const setState = (state: SlideState) => {
    el.setAttribute('data-state', state);
    options.onStateChange?.(state);
  };

  const confirm = () => {
    if (done) return;
    done = true;
    x = max;
    write();
    setState('done');
    thumb.setAttribute('aria-disabled', 'true');
    buzz(14);
    options.onConfirm?.();
    if (resetAfter > 0) resetTimer = setTimeout(reset, resetAfter);
  };

  function reset() {
    clearTimeout(resetTimer);
    done = false;
    x = 0;
    write();
    thumb.removeAttribute('aria-disabled');
    setState('idle');
  }

  const onDown = (event: PointerEvent) => {
    if (done || event.button !== 0 || !event.isPrimary || pointerId !== -1) return;
    if (el.matches('[aria-disabled="true"], [data-disabled]')) return;
    event.preventDefault();
    measure();
    pointerId = event.pointerId;
    thumb.setPointerCapture(pointerId);
    startX = event.clientX;
    startPos = x;
    velocity.reset(0);
    el.setAttribute('data-dragging', '');
    setState('dragging');
  };

  const onMove = (event: PointerEvent) => {
    if (event.pointerId !== pointerId) return;
    const travel = (event.clientX - startX) * direction(el);
    velocity.push(travel);
    const raw = startPos + travel;
    x = raw < 0 ? -rubberBand(-raw, 24) : raw > max ? max + rubberBand(raw - max, 16) : raw;
    write();
  };

  const onUp = (event: PointerEvent) => {
    if (event.pointerId !== pointerId) return;
    pointerId = -1;
    el.removeAttribute('data-dragging');
    const p = x / max;
    if (p >= threshold || (p >= 0.5 && velocity.speed() > FLICK_VELOCITY)) {
      confirm();
    } else {
      x = 0;
      write();
      setState('idle');
    }
  };

  const onKey = (event: KeyboardEvent) => {
    if (done) return;
    const dir = direction(el);
    const forward = dir === 1 ? 'ArrowRight' : 'ArrowLeft';
    const back = dir === 1 ? 'ArrowLeft' : 'ArrowRight';
    let next: number | null = null;
    measure();
    if (event.key === forward || event.key === 'ArrowUp') next = x + max * step;
    else if (event.key === back || event.key === 'ArrowDown') next = x - max * step;
    else if (event.key === 'Home') next = 0;
    else if (event.key === 'End') next = max;
    if (next === null) return;
    event.preventDefault();
    x = clamp(next, 0, max);
    if (x >= max - 0.5) confirm();
    else write();
  };

  // Keyboard progress that never reached the end springs back when focus leaves.
  const onBlur = () => {
    if (!done && pointerId === -1 && x !== 0) {
      x = 0;
      write();
    }
  };

  const observer = typeof ResizeObserver === 'undefined' ? null : new ResizeObserver(() => {
    measure();
    if (done) x = max;
    write();
  });
  observer?.observe(el);

  measure();
  write();
  el.setAttribute('data-state', 'idle');
  thumb.addEventListener('pointerdown', onDown);
  thumb.addEventListener('pointermove', onMove);
  thumb.addEventListener('pointerup', onUp);
  thumb.addEventListener('pointercancel', onUp);
  thumb.addEventListener('keydown', onKey);
  thumb.addEventListener('blur', onBlur);
  el.addEventListener('nx-reset', reset);

  return () => {
    clearTimeout(resetTimer);
    observer?.disconnect();
    thumb.removeEventListener('pointerdown', onDown);
    thumb.removeEventListener('pointermove', onMove);
    thumb.removeEventListener('pointerup', onUp);
    thumb.removeEventListener('pointercancel', onUp);
    thumb.removeEventListener('keydown', onKey);
    thumb.removeEventListener('blur', onBlur);
    el.removeEventListener('nx-reset', reset);
  };
}

/* ---- Odometer -------------------------------------------------------------- */

export interface OdometerColumn {
  kind: 'digit' | 'static';
  /** The characters the column passes through, from the old one to the new one ('' is blank). */
  steps: string[];
}

/**
 * The digits a single column rolls through, `from` → `to`, going up (7, 8, 9,
 * 0, 1, 2) or down (2, 1, 0, 9, 8, 7) the wheel.
 */
export function odometerSteps(from: number, to: number, up: boolean): number[] {
  const steps = [from];
  let current = from;
  while (current !== to) {
    current = (current + (up ? 1 : 9)) % 10;
    steps.push(current);
  }
  return steps;
}

/**
 * Compare two formatted numbers column by column, aligned on the last digit
 * (units against units), and say what every column of the new number rolls
 * through. Columns that appear roll in from blank; columns that disappear
 * roll out to blank. `up` is whether the value grew.
 */
export function odometerDiff(prev: NumberPart[], next: NumberPart[], up: boolean, digits: string[] = localeDigits('en')): OdometerColumn[] {
  const length = Math.max(prev.length, next.length);
  const columns: OdometerColumn[] = [];
  for (let k = 0; k < length; k++) {
    const p = prev[prev.length - 1 - k];
    const n = next[next.length - 1 - k];
    const kind = (n ?? p)!.kind;
    let steps: string[];
    if (p && n && p.kind === 'digit' && n.kind === 'digit') {
      steps = odometerSteps(p.value, n.value, up).map((d) => digits[d] ?? String(d));
    } else if (p && n && p.char === n.char) {
      steps = [n.char];
    } else {
      steps = [p ? p.char : '', n ? n.char : ''];
    }
    columns.unshift({ kind, steps });
  }
  return columns;
}

export interface OdometerOptions {
  value: number;
  /** Where the first roll starts (when `reveal` is on, it rolls from here on reveal). */
  from?: number;
  locale?: string;
  format?: Intl.NumberFormatOptions;
  /** Start rolling when the element scrolls into view. */
  reveal?: boolean;
}

export interface OdometerController {
  update(value: number): void;
  destroy(): void;
}

/**
 * A number whose digits roll in masked columns. The element gets a visually
 * hidden copy of the formatted value (`.nx-odometer-sr`) and an aria-hidden
 * roll (`.nx-odometer-roll`) of columns; each changing column stacks the
 * characters it passes through and slides along them on a spring — upward
 * when the value grows, downward when it shrinks.
 */
export function createOdometer(el: HTMLElement, options: OdometerOptions): OdometerController {
  const noop = { update() {}, destroy() {} };
  if (!isBrowser) return noop;
  const { locale, format } = options;
  const digits = localeDigits(locale);

  const sr = el.querySelector<HTMLElement>('.nx-odometer-sr') ?? el.appendChild(Object.assign(document.createElement('span'), { className: 'nx-odometer-sr nx-visually-hidden' }));
  let roll = el.querySelector<HTMLElement>('.nx-odometer-roll');
  if (!roll) {
    roll = document.createElement('span');
    roll.className = 'nx-odometer-roll';
    el.appendChild(roll);
  }
  roll.setAttribute('aria-hidden', 'true');
  const host = roll;

  let current = options.reveal ? (options.from ?? 0) : options.value;
  let pending: number | null = options.reveal ? options.value : null;
  let revealed = !options.reveal;
  let observer: IntersectionObserver | null = null;
  let token = 0;

  const parts = (value: number) => numberParts(value, locale, format);
  const formatted = (value: number) => parts(value).map((part) => part.char).join('');

  const column = (kind: string, char: string) => {
    const col = document.createElement('span');
    col.className = 'nx-odometer-col';
    col.setAttribute('data-kind', kind);
    const cell = document.createElement('span');
    cell.className = 'nx-odometer-cell';
    cell.textContent = char;
    col.appendChild(cell);
    return col;
  };

  const paint = (value: number) => {
    host.replaceChildren(...parts(value).map((part) => column(part.kind, part.char)));
  };

  const animate = (from: number, to: number) => {
    const run = ++token;
    const up = to >= from;
    const columns = odometerDiff(parts(from), parts(to), up, digits);
    const { easing, duration } = spring('gentle');
    const nodes: HTMLElement[] = [];
    let longest = 0;

    columns.forEach((col, index) => {
      const node = document.createElement('span');
      node.className = 'nx-odometer-col';
      node.setAttribute('data-kind', col.kind);
      if (col.steps.length < 2) {
        const cell = document.createElement('span');
        cell.className = 'nx-odometer-cell';
        cell.textContent = col.steps[0] ?? '';
        node.appendChild(cell);
        nodes.push(node);
        return;
      }
      node.setAttribute('data-rolling', '');
      const strip = document.createElement('span');
      strip.className = 'nx-odometer-strip';
      // Up: the old character sits on top and the strip rises; down: the strip is reversed and falls.
      const order = up ? col.steps : [...col.steps].reverse();
      for (const char of order) {
        const cell = document.createElement('span');
        cell.className = 'nx-odometer-cell';
        cell.textContent = char;
        strip.appendChild(cell);
      }
      node.appendChild(strip);
      nodes.push(node);
      const travel = ((col.steps.length - 1) / col.steps.length) * 100;
      const time = duration + Math.min(9, col.steps.length) * 40;
      // The further toward the units a column sits, the later it lands — the wheel's tail.
      const delay = index * 18;
      longest = Math.max(longest, time + delay);
      const keyframes = up
        ? [{ transform: 'translateY(0)' }, { transform: `translateY(-${travel}%)` }]
        : [{ transform: `translateY(-${travel}%)` }, { transform: 'translateY(0)' }];
      if (typeof strip.animate === 'function') strip.animate(keyframes, { duration: time, delay, easing, fill: 'both' });
    });

    host.replaceChildren(...nodes);
    setTimeout(() => {
      if (run === token) paint(to);
    }, longest + 60);
  };

  const set = (value: number) => {
    sr.textContent = formatted(value);
    if (value === current) return;
    const from = current;
    current = value;
    if (prefersReducedMotion()) paint(value);
    else animate(from, value);
  };

  sr.textContent = formatted(options.reveal ? options.value : current);
  paint(current);

  if (options.reveal) {
    if (!('IntersectionObserver' in window)) {
      revealed = true;
      set(options.value);
      pending = null;
    } else {
      observer = new IntersectionObserver(
        (entries) => {
          if (!entries.some((entry) => entry.isIntersecting)) return;
          revealed = true;
          observer?.disconnect();
          observer = null;
          if (pending !== null) set(pending);
          pending = null;
        },
        { threshold: 0.4 },
      );
      observer.observe(el);
    }
  }

  return {
    update(value: number) {
      if (!revealed) {
        pending = value;
        sr.textContent = formatted(value);
        return;
      }
      set(value);
    },
    destroy() {
      token++;
      observer?.disconnect();
    },
  };
}

/* ---- Undo snackbar --------------------------------------------------------- */

export interface SnackbarTimerOptions {
  duration?: number;
  onExpire?: () => void;
  /** Hovering or focusing this element pauses the timer (default: the item's `.nx-snackbars` stack). */
  pauseOn?: Element | null;
}

/**
 * The snackbar's countdown: expires after `duration`, pauses while the stack
 * is hovered or holds focus (and while the page is hidden), and writes
 * `--_duration` plus `[data-paused]` so the draining bar stays in step.
 */
export function snackbarTimer(item: HTMLElement, { duration = 6000, onExpire, pauseOn }: SnackbarTimerOptions = {}): Cleanup {
  if (!isBrowser || duration <= 0) return () => {};
  const area = pauseOn ?? item.closest('.nx-snackbars') ?? item;
  let remaining = duration;
  let started = performance.now();
  let timer: ReturnType<typeof setTimeout> | undefined;
  let hovered = false;
  let focused = false;
  let paused = false;

  item.style.setProperty('--_duration', `${duration}ms`);

  const resume = () => {
    paused = false;
    item.removeAttribute('data-paused');
    started = performance.now();
    timer = setTimeout(() => onExpire?.(), remaining);
  };
  const pause = () => {
    if (paused) return;
    paused = true;
    clearTimeout(timer);
    remaining = Math.max(0, remaining - (performance.now() - started));
    item.setAttribute('data-paused', '');
  };
  const sync = () => {
    if (hovered || focused || document.hidden) pause();
    else if (paused) resume();
  };

  const onEnter = () => {
    hovered = true;
    sync();
  };
  const onLeave = () => {
    hovered = false;
    sync();
  };
  const onFocusIn = () => {
    focused = true;
    sync();
  };
  const onFocusOut = (event: FocusEvent) => {
    if (area.contains(event.relatedTarget as Node | null)) return;
    focused = false;
    sync();
  };

  area.addEventListener('pointerenter', onEnter);
  area.addEventListener('pointerleave', onLeave);
  area.addEventListener('focusin', onFocusIn);
  area.addEventListener('focusout', onFocusOut as EventListener);
  document.addEventListener('visibilitychange', sync);
  hovered = area.matches(':hover');
  focused = area.contains(document.activeElement);
  if (hovered || focused) {
    paused = true;
    item.setAttribute('data-paused', '');
  } else {
    resume();
  }

  return () => {
    clearTimeout(timer);
    area.removeEventListener('pointerenter', onEnter);
    area.removeEventListener('pointerleave', onLeave);
    area.removeEventListener('focusin', onFocusIn);
    area.removeEventListener('focusout', onFocusOut as EventListener);
    document.removeEventListener('visibilitychange', sync);
  };
}

/* ---- Sortable list --------------------------------------------------------- */

/** A copy of `list` with the item at `from` moved to `to`. */
export function moveItem<T>(list: readonly T[], from: number, to: number): T[] {
  const next = list.slice();
  if (from < 0 || from >= next.length) return next;
  const target = clamp(to, 0, next.length - 1);
  const [item] = next.splice(from, 1);
  next.splice(target, 0, item as T);
  return next;
}

/**
 * Where a dragged item lands: the number of other items whose centre lies
 * before the dragged item's current centre. `centers` are the items'
 * resting centres in list order.
 */
export function sortTargetIndex(centers: readonly number[], from: number, center: number): number {
  let index = 0;
  centers.forEach((c, i) => {
    if (i !== from && c < center) index++;
  });
  return index;
}

/**
 * How far (px) each item shifts while `from` hovers over `to`: the items in
 * between step aside by the dragged item's size plus the gap.
 */
export function sortShifts(count: number, from: number, to: number, size: number): number[] {
  return Array.from({ length: count }, (_, i) => {
    if (i === from) return 0;
    if (from < to && i > from && i <= to) return -size;
    if (to < from && i >= to && i < from) return size;
    return 0;
  });
}

export interface SortMessages {
  grabbed: string;
  moved: string;
  dropped: string;
  cancelled: string;
}

export const sortMessages: SortMessages = {
  grabbed: '{name}, grabbed. Position {position} of {total}. Use the arrow keys to move, Space to drop, Escape to cancel.',
  moved: '{name}, moved to position {position} of {total}.',
  dropped: '{name}, dropped at position {position} of {total}.',
  cancelled: 'Reorder cancelled. {name} is back at position {position} of {total}.',
};

export interface SortableOptions {
  /** Item selector (each item carries a unique `data-nx-sort-key`). */
  items?: string;
  /** Drag handle selector inside an item (a button). */
  handle?: string;
  /**
   * Reorder your state here (React, Alpine x-for). Without it the behaviour
   * moves the DOM nodes itself. Either way the siblings FLIP into place.
   */
  onMove?: (from: number, to: number) => void;
  /** After a drop: the keys in their new order. */
  onChange?: (keys: string[]) => void;
  /** The item's name in announcements (default: its `data-nx-sort-label` or text). */
  name?: (item: HTMLElement) => string;
  messages?: Partial<SortMessages>;
  locale?: string;
}

/**
 * A reorderable list. Pointer: grab the handle, the item follows (rubber band
 * past the ends) while the siblings step aside, and everything FLIPs into the
 * new order on drop. Keyboard: Space/Enter on the handle grabs, the arrows
 * move, Space drops, Escape cancels — each step announced through the
 * `[data-nx-sort-live]` region inside `el`.
 */
export function sortable(el: HTMLElement, options: SortableOptions = {}): Cleanup {
  if (!isBrowser) return () => {};
  const itemSel = options.items ?? '[data-nx-sort-key]';
  const handleSel = options.handle ?? '[data-nx-sort-handle]';
  const messages = { ...sortMessages, ...options.messages };
  const live = el.querySelector<HTMLElement>('[data-nx-sort-live]');
  const number = new Intl.NumberFormat(options.locale ?? (el.closest('[lang]')?.getAttribute('lang') || undefined));

  const items = () => Array.from(el.querySelectorAll<HTMLElement>(itemSel));
  const keyOf = (item: HTMLElement) => item.getAttribute('data-nx-sort-key') ?? '';
  const nameOf = (item: HTMLElement) =>
    options.name?.(item) ?? item.getAttribute('data-nx-sort-label') ?? item.textContent?.trim().replace(/\s+/g, ' ') ?? '';

  const say = (template: string, item: HTMLElement) => {
    if (!live) return;
    const list = items();
    const text = fillMessage(template, { name: nameOf(item), position: number.format(list.indexOf(item) + 1), total: number.format(list.length) });
    // Re-set so the same sentence twice is still announced.
    live.textContent = '';
    requestAnimationFrame(() => (live.textContent = text));
  };

  /* FLIP: where each item was (visually) before the reorder → where it is now. */
  const snapshot = () => new Map(items().map((item) => [keyOf(item), item.getBoundingClientRect().top]));
  const flip = (before: Map<string, number>, quick: boolean) => {
    if (prefersReducedMotion()) return;
    const { easing, duration } = spring('gentle');
    for (const item of items()) {
      const old = before.get(keyOf(item));
      if (old === undefined) continue;
      const delta = old - item.getBoundingClientRect().top;
      if (Math.abs(delta) < 0.5 || typeof item.animate !== 'function') continue;
      item.animate([{ transform: `translateY(${delta}px)` }, { transform: 'translateY(0)' }], quick ? { duration: 150, easing: 'cubic-bezier(0.2, 0, 0, 1)' } : { duration, easing });
    }
  };

  const commit = (from: number, to: number, before: Map<string, number>, quick: boolean, then?: () => void) => {
    const finish = () => {
      flip(before, quick);
      then?.();
    };
    if (options.onMove) {
      options.onMove(from, to);
      // Frameworks render on the next microtask or frame.
      requestAnimationFrame(finish);
    } else {
      const list = items();
      const item = list[from];
      const others = list.filter((_, i) => i !== from);
      if (item) {
        const anchor = others[to];
        if (anchor) anchor.before(item);
        else others[others.length - 1]?.after(item);
      }
      finish();
    }
  };

  const emit = () => options.onChange?.(items().map(keyOf));

  /* ---- Pointer ---- */
  let drag: {
    item: HTMLElement;
    handle: HTMLElement;
    pointerId: number;
    from: number;
    to: number;
    startY: number;
    centers: number[];
    size: number;
    minDy: number;
    maxDy: number;
  } | null = null;

  const onDown = (event: PointerEvent) => {
    const handle = (event.target as Element).closest<HTMLElement>(handleSel);
    if (!handle || !el.contains(handle) || drag || event.button !== 0 || !event.isPrimary) return;
    const item = handle.closest<HTMLElement>(itemSel);
    if (!item) return;
    event.preventDefault();
    const list = items();
    const rects = list.map((node) => node.getBoundingClientRect());
    const from = list.indexOf(item);
    const own = rects[from]!;
    const next = rects[from + 1];
    const prev = rects[from - 1];
    const gap = next ? next.top - own.bottom : prev ? own.top - prev.bottom : 0;
    const first = rects[0]!;
    const lastRect = rects[rects.length - 1]!;
    drag = {
      item,
      handle,
      pointerId: event.pointerId,
      from,
      to: from,
      startY: event.clientY,
      centers: rects.map((r) => r.top + r.height / 2),
      size: own.height + Math.max(0, gap),
      minDy: first.top - own.top,
      maxDy: lastRect.bottom - own.bottom,
    };
    handle.setPointerCapture(event.pointerId);
    el.setAttribute('data-sorting', '');
    item.setAttribute('data-dragging', '');
  };

  const onMovePointer = (event: PointerEvent) => {
    if (!drag || event.pointerId !== drag.pointerId) return;
    const raw = event.clientY - drag.startY;
    const dy = raw < drag.minDy ? drag.minDy - rubberBand(drag.minDy - raw, 32) : raw > drag.maxDy ? drag.maxDy + rubberBand(raw - drag.maxDy, 32) : raw;
    drag.item.style.setProperty('--_dy', `${dy.toFixed(1)}px`);
    const to = sortTargetIndex(drag.centers, drag.from, drag.centers[drag.from]! + dy);
    if (to !== drag.to) {
      drag.to = to;
      const shifts = sortShifts(drag.centers.length, drag.from, to, drag.size);
      items().forEach((node, i) => {
        if (node !== drag!.item) node.style.setProperty('--_shift', `${shifts[i] ?? 0}px`);
      });
    }
  };

  const clearDrag = () => {
    for (const node of items()) {
      node.style.removeProperty('--_shift');
      node.style.removeProperty('--_dy');
      node.removeAttribute('data-dragging');
    }
    el.removeAttribute('data-sorting');
  };

  const onUpPointer = (event: PointerEvent) => {
    if (!drag || event.pointerId !== drag.pointerId) return;
    const { item, from, to } = drag;
    drag = null;
    if (from === to) {
      // Spring home: the CSS transitions --_dy back to 0 once [data-dragging] is gone.
      item.removeAttribute('data-dragging');
      item.style.setProperty('--_dy', '0px');
      el.removeAttribute('data-sorting');
      setTimeout(() => item.style.removeProperty('--_dy'), 400);
      return;
    }
    const before = snapshot();
    // Freeze the transitions while the transforms come off, so the FLIP starts from what you saw.
    el.setAttribute('data-settling', '');
    clearDrag();
    commit(from, to, before, false, () => {
      el.removeAttribute('data-settling');
      say(messages.dropped, item);
      emit();
    });
  };

  /* ---- Keyboard ---- */
  let grabbed: { item: HTMLElement; origin: number } | null = null;
  let moving = false;

  const refocus = (item: HTMLElement) => {
    const key = keyOf(item);
    const node = items().find((n) => keyOf(n) === key) ?? item;
    node.querySelector<HTMLElement>(handleSel)?.focus({ preventScroll: false });
  };

  const setGrabbed = (item: HTMLElement | null) => {
    for (const node of items()) {
      node.toggleAttribute('data-grabbed', node === item);
      node.querySelector(handleSel)?.setAttribute('aria-pressed', node === item ? 'true' : 'false');
    }
    el.toggleAttribute('data-sorting', !!item);
  };

  const keyMove = (to: number, message: string) => {
    if (!grabbed) return;
    const item = grabbed.item;
    const list = items();
    const from = list.indexOf(item);
    const target = clamp(to, 0, list.length - 1);
    if (target === from) {
      say(message, item);
      return;
    }
    moving = true;
    commit(from, target, snapshot(), true, () => {
      refocus(item);
      moving = false;
      say(message, item);
    });
  };

  const drop = (item: HTMLElement) => {
    grabbed = null;
    setGrabbed(null);
    say(messages.dropped, item);
    emit();
  };

  const onKey = (event: KeyboardEvent) => {
    const handle = (event.target as Element).closest<HTMLElement>(handleSel);
    if (!handle) return;
    const item = handle.closest<HTMLElement>(itemSel);
    if (!item) return;
    const index = items().indexOf(item);

    if (event.key === ' ' || event.key === 'Enter') {
      event.preventDefault();
      if (grabbed) drop(grabbed.item);
      else {
        grabbed = { item, origin: index };
        setGrabbed(item);
        say(messages.grabbed, item);
      }
      return;
    }
    if (!grabbed) return;
    if (event.key === 'ArrowUp' || event.key === 'ArrowLeft') {
      event.preventDefault();
      keyMove(index - 1, messages.moved);
    } else if (event.key === 'ArrowDown' || event.key === 'ArrowRight') {
      event.preventDefault();
      keyMove(index + 1, messages.moved);
    } else if (event.key === 'Home') {
      event.preventDefault();
      keyMove(0, messages.moved);
    } else if (event.key === 'End') {
      event.preventDefault();
      keyMove(items().length - 1, messages.moved);
    } else if (event.key === 'Escape') {
      event.preventDefault();
      event.stopPropagation();
      const { origin } = grabbed;
      keyMove(origin, messages.cancelled);
      grabbed = null;
      setGrabbed(null);
    } else if (event.key === 'Tab') {
      drop(grabbed.item);
    }
  };

  const onFocusOut = (event: FocusEvent) => {
    if (!grabbed || moving) return;
    if (el.contains(event.relatedTarget as Node | null)) return;
    drop(grabbed.item);
  };

  el.addEventListener('pointerdown', onDown);
  el.addEventListener('pointermove', onMovePointer);
  el.addEventListener('pointerup', onUpPointer);
  el.addEventListener('pointercancel', onUpPointer);
  el.addEventListener('keydown', onKey);
  el.addEventListener('focusout', onFocusOut);

  return () => {
    el.removeEventListener('pointerdown', onDown);
    el.removeEventListener('pointermove', onMovePointer);
    el.removeEventListener('pointerup', onUpPointer);
    el.removeEventListener('pointercancel', onUpPointer);
    el.removeEventListener('keydown', onKey);
    el.removeEventListener('focusout', onFocusOut);
  };
}

/* ---- Lightbox -------------------------------------------------------------- */

export interface LightboxStageOptions {
  maxZoom?: number;
  /** Zoom a double tap or double click goes to. */
  tapZoom?: number;
  onNext?: () => void;
  onPrev?: () => void;
  onClose?: () => void;
  onZoomChange?: (zoom: number) => void;
}

export interface LightboxStageController {
  zoom(): number;
  /** Multiply the zoom, around the stage centre. */
  zoomBy(factor: number): void;
  reset(): void;
  destroy(): void;
}

/**
 * Gestures on the lightbox's stage (`el`, holding the `img`): wheel and pinch
 * zoom around the pointer, double tap toggles zoom, drag pans while zoomed,
 * and at 1× a horizontal swipe goes next/previous (direction-aware) while a
 * swipe down closes. Writes `--_zoom`, `--_pan-x/y`, `--_swipe-x/y` and
 * `[data-zoomed]`, `[data-gesture]` on the stage.
 */
export function createLightboxStage(el: HTMLElement, options: LightboxStageOptions = {}): LightboxStageController {
  const noop = { zoom: () => 1, zoomBy() {}, reset() {}, destroy() {} };
  if (!isBrowser) return noop;
  const { maxZoom = 4, tapZoom = 2.5 } = options;

  let zoom = 1;
  let panX = 0;
  let panY = 0;
  let swipeX = 0;
  let swipeY = 0;
  const pointers = new Map<number, { x: number; y: number }>();
  let gesture: 'pan' | 'swipe' | 'pinch' | null = null;
  let start = { x: 0, y: 0, panX: 0, panY: 0, distance: 0, zoom: 1, mx: 0, my: 0 };
  let lastTap = { time: 0, x: 0, y: 0 };
  let moved = false;
  let axis: 'x' | 'y' | null = null;
  const vx = velocityTracker();
  const vy = velocityTracker();

  const img = () => el.querySelector<HTMLElement>('img');

  const bounds = (z: number) => {
    const media = img();
    const w = media ? media.offsetWidth : el.clientWidth;
    const h = media ? media.offsetHeight : el.clientHeight;
    return { x: Math.max(0, (w * z - el.clientWidth) / 2), y: Math.max(0, (h * z - el.clientHeight) / 2) };
  };

  const write = () => {
    el.style.setProperty('--_zoom', zoom.toFixed(3));
    el.style.setProperty('--_pan-x', `${panX.toFixed(1)}px`);
    el.style.setProperty('--_pan-y', `${panY.toFixed(1)}px`);
    el.style.setProperty('--_swipe-x', `${swipeX.toFixed(1)}px`);
    el.style.setProperty('--_swipe-y', `${swipeY.toFixed(1)}px`);
    el.style.setProperty('--_dismiss', clamp(Math.abs(swipeY) / 240, 0, 1).toFixed(3));
    el.toggleAttribute('data-zoomed', zoom > 1.01);
  };

  const clampPan = () => {
    const b = bounds(zoom);
    panX = clamp(panX, -b.x, b.x);
    panY = clamp(panY, -b.y, b.y);
  };

  /** Zoom to `next` keeping the point (cx, cy), relative to the stage centre, still. */
  const zoomAt = (next: number, cx: number, cy: number) => {
    const target = clamp(next, 1, maxZoom);
    const ratio = target / zoom;
    panX = cx - (cx - panX) * ratio;
    panY = cy - (cy - panY) * ratio;
    zoom = target;
    if (zoom <= 1.001) {
      zoom = 1;
      panX = 0;
      panY = 0;
    }
    clampPan();
    write();
    options.onZoomChange?.(zoom);
  };

  const local = (clientX: number, clientY: number) => {
    const rect = el.getBoundingClientRect();
    return { x: clientX - (rect.left + rect.width / 2), y: clientY - (rect.top + rect.height / 2) };
  };

  const onWheel = (event: WheelEvent) => {
    event.preventDefault();
    const p = local(event.clientX, event.clientY);
    const delta = event.deltaMode === 1 ? event.deltaY * 16 : event.deltaY;
    el.setAttribute('data-gesture', 'wheel');
    zoomAt(zoom * Math.exp(-delta * 0.0022), p.x, p.y);
    clearTimeout(wheelTimer);
    wheelTimer = setTimeout(() => el.removeAttribute('data-gesture'), 120);
  };
  let wheelTimer: ReturnType<typeof setTimeout> | undefined;

  const onDown = (event: PointerEvent) => {
    if (event.button !== 0 || (event.target as Element).closest('button, a')) return;
    if (pointers.size >= 2) return;
    pointers.set(event.pointerId, { x: event.clientX, y: event.clientY });
    el.setPointerCapture(event.pointerId);
    moved = false;
    if (pointers.size === 2) {
      const [a, b] = Array.from(pointers.values()) as [{ x: number; y: number }, { x: number; y: number }];
      const mid = local((a.x + b.x) / 2, (a.y + b.y) / 2);
      gesture = 'pinch';
      swipeX = 0;
      swipeY = 0;
      start = { ...start, distance: Math.hypot(a.x - b.x, a.y - b.y), zoom, panX, panY, mx: mid.x, my: mid.y };
    } else {
      gesture = zoom > 1.01 ? 'pan' : 'swipe';
      axis = null;
      start = { ...start, x: event.clientX, y: event.clientY, panX, panY };
      vx.reset(0);
      vy.reset(0);
    }
    el.setAttribute('data-gesture', gesture);
  };

  const onMove = (event: PointerEvent) => {
    const point = pointers.get(event.pointerId);
    if (!point) return;
    point.x = event.clientX;
    point.y = event.clientY;

    if (gesture === 'pinch' && pointers.size === 2) {
      const [a, b] = Array.from(pointers.values()) as [{ x: number; y: number }, { x: number; y: number }];
      const distance = Math.hypot(a.x - b.x, a.y - b.y);
      const target = clamp(start.zoom * (distance / Math.max(1, start.distance)), 1, maxZoom);
      const ratio = target / start.zoom;
      zoom = target;
      panX = start.mx - (start.mx - start.panX) * ratio;
      panY = start.my - (start.my - start.panY) * ratio;
      clampPan();
      moved = true;
      write();
      return;
    }

    const dx = event.clientX - start.x;
    const dy = event.clientY - start.y;
    if (!moved && Math.hypot(dx, dy) < 6) return;
    moved = true;

    if (gesture === 'pan') {
      const b = bounds(zoom);
      const rawX = start.panX + dx;
      const rawY = start.panY + dy;
      panX = rawX > b.x ? b.x + rubberBand(rawX - b.x) : rawX < -b.x ? -b.x - rubberBand(-b.x - rawX) : rawX;
      panY = rawY > b.y ? b.y + rubberBand(rawY - b.y) : rawY < -b.y ? -b.y - rubberBand(-b.y - rawY) : rawY;
      write();
    } else if (gesture === 'swipe') {
      if (!axis) axis = Math.abs(dx) > Math.abs(dy) ? 'x' : 'y';
      vx.push(dx);
      vy.push(dy);
      if (axis === 'x') swipeX = dx;
      else swipeY = dy > 0 ? dy : -rubberBand(-dy, 40);
      write();
    }
  };

  const onUp = (event: PointerEvent) => {
    if (!pointers.has(event.pointerId)) return;
    pointers.delete(event.pointerId);

    if (gesture === 'pinch') {
      if (pointers.size === 1) {
        // One finger stays down: carry on as a pan from here.
        const [rest] = Array.from(pointers.values()) as [{ x: number; y: number }];
        gesture = zoom > 1.01 ? 'pan' : 'swipe';
        start = { ...start, x: rest.x, y: rest.y, panX, panY };
        el.setAttribute('data-gesture', gesture);
        return;
      }
    }
    if (pointers.size > 0) return;
    el.removeAttribute('data-gesture');

    if (!moved) {
      // A tap: two in quick succession toggle the zoom.
      const now = performance.now();
      if (now - lastTap.time < 300 && Math.hypot(event.clientX - lastTap.x, event.clientY - lastTap.y) < 24) {
        const p = local(event.clientX, event.clientY);
        zoomAt(zoom > 1.01 ? 1 : tapZoom, p.x, p.y);
        lastTap = { time: 0, x: 0, y: 0 };
      } else {
        lastTap = { time: now, x: event.clientX, y: event.clientY };
      }
    }

    if (gesture === 'pan') {
      clampPan();
      write();
    } else if (gesture === 'swipe' && moved) {
      const dir = direction(el);
      const fastX = Math.abs(vx.speed()) > FLICK_VELOCITY * 4;
      const fastY = vy.speed() > FLICK_VELOCITY * 4;
      if (axis === 'x' && (Math.abs(swipeX) > 80 || (fastX && Math.abs(swipeX) > 24))) {
        // Content dragged toward inline-start brings the next one in.
        if (swipeX * dir < 0) options.onNext?.();
        else options.onPrev?.();
      } else if (axis === 'y' && (swipeY > 110 || (fastY && swipeY > 30))) {
        options.onClose?.();
      }
      swipeX = 0;
      swipeY = 0;
      write();
    }
    gesture = null;
  };

  const onDblClick = (event: MouseEvent) => {
    // Mouse double clicks are handled by the tap logic above; stop text selection.
    event.preventDefault();
  };

  write();
  el.addEventListener('wheel', onWheel, { passive: false });
  el.addEventListener('pointerdown', onDown);
  el.addEventListener('pointermove', onMove);
  el.addEventListener('pointerup', onUp);
  el.addEventListener('pointercancel', onUp);
  el.addEventListener('dblclick', onDblClick);

  return {
    zoom: () => zoom,
    zoomBy(factor: number) {
      zoomAt(zoom * factor, 0, 0);
    },
    reset() {
      zoom = 1;
      panX = 0;
      panY = 0;
      swipeX = 0;
      swipeY = 0;
      write();
      options.onZoomChange?.(1);
    },
    destroy() {
      clearTimeout(wheelTimer);
      el.removeEventListener('wheel', onWheel);
      el.removeEventListener('pointerdown', onDown);
      el.removeEventListener('pointermove', onMove);
      el.removeEventListener('pointerup', onUp);
      el.removeEventListener('pointercancel', onUp);
      el.removeEventListener('dblclick', onDblClick);
    },
  };
}

const HERO = 'nx-lightbox-hero';
type ViewTransitionLike = { finished: Promise<void>; updateCallbackDone: Promise<void> };
type StartViewTransition = (update: () => void) => ViewTransitionLike;

function rectMorph(from: DOMRect, to: DOMRect): string {
  const scale = Math.max(from.width / Math.max(1, to.width), from.height / Math.max(1, to.height));
  const dx = from.left + from.width / 2 - (to.left + to.width / 2);
  const dy = from.top + from.height / 2 - (to.top + to.height / 2);
  return `translate(${dx.toFixed(1)}px, ${dy.toFixed(1)}px) scale(${scale.toFixed(4)})`;
}

/**
 * Open the lightbox `dialog` so the thumbnail `from` grows into the stage
 * image (`hero()`): a View Transition where supported, a FLIP of the image
 * otherwise, a plain fade under reduced motion.
 */
export async function openLightbox(dialog: HTMLDialogElement, from: HTMLElement | null, hero: () => HTMLElement | null): Promise<void> {
  if (!isBrowser) return;
  const reduce = prefersReducedMotion();
  if (from && !reduce && supportsViewTransitions()) {
    from.style.setProperty('view-transition-name', HERO);
    dialog.setAttribute('data-morph', '');
    const start = (document as unknown as { startViewTransition: StartViewTransition }).startViewTransition.bind(document);
    const vt = start(() => {
      from.style.removeProperty('view-transition-name');
      if (!dialog.open) dialog.showModal();
      hero()?.style.setProperty('view-transition-name', HERO);
    });
    await vt.finished.catch(() => {});
    hero()?.style.removeProperty('view-transition-name');
    dialog.removeAttribute('data-morph');
    return;
  }
  if (!dialog.open) dialog.showModal();
  const target = hero();
  if (!from || !target || reduce || typeof target.animate !== 'function') return;
  const a = from.getBoundingClientRect();
  const b = target.getBoundingClientRect();
  if (!b.width || !b.height) return;
  const { easing, duration } = spring('soft');
  target.animate([{ transform: rectMorph(a, b), opacity: 0.6 }, { transform: 'none', opacity: 1 }], { duration, easing });
}

/**
 * Close the lightbox, shrinking the stage image back into the thumbnail `to`
 * (the one for the image now showing, which may differ from the one opened).
 */
export async function closeLightbox(dialog: HTMLDialogElement, to: HTMLElement | null, hero: () => HTMLElement | null): Promise<void> {
  if (!isBrowser || !dialog.open) return;
  const reduce = prefersReducedMotion();
  const source = hero();
  if (to && source && !reduce && supportsViewTransitions()) {
    source.style.setProperty('view-transition-name', HERO);
    dialog.setAttribute('data-morph', '');
    const start = (document as unknown as { startViewTransition: StartViewTransition }).startViewTransition.bind(document);
    const vt = start(() => {
      source.style.removeProperty('view-transition-name');
      dialog.close();
      to.style.setProperty('view-transition-name', HERO);
    });
    await vt.finished.catch(() => {});
    to.style.removeProperty('view-transition-name');
    dialog.removeAttribute('data-morph');
    return;
  }
  if (to && source && !reduce && typeof source.animate === 'function') {
    const a = to.getBoundingClientRect();
    const b = source.getBoundingClientRect();
    dialog.setAttribute('data-state', 'closing');
    const animation = source.animate([{ transform: 'none', opacity: 1 }, { transform: rectMorph(a, b), opacity: 0 }], { duration: 220, easing: 'cubic-bezier(0.4, 0, 1, 1)', fill: 'forwards' });
    await animation.finished.catch(() => {});
    dialog.close();
    dialog.removeAttribute('data-state');
    animation.cancel();
    return;
  }
  dialog.close();
}

/* ---- Compare slider -------------------------------------------------------- */

/**
 * Before/after: the `input[type=range]` inside `el` drives `--_pos` (0–100%),
 * which clips the after image and places the divider. The range does the
 * dragging, the keyboard and the right-to-left mapping natively.
 */
export function compareSlider(el: HTMLElement, { onChange }: { onChange?: (value: number) => void } = {}): Cleanup {
  if (!isBrowser) return () => {};
  const range = el.querySelector<HTMLInputElement>('input[type="range"]');
  if (!range) return () => {};
  const write = () => {
    const min = Number(range.min || 0);
    const max = Number(range.max || 100);
    const value = ((Number(range.value) - min) / Math.max(1, max - min)) * 100;
    el.style.setProperty('--_pos', `${value.toFixed(2)}%`);
  };
  const onInput = () => {
    write();
    onChange?.(Number(range.value));
  };
  const onDown = () => el.setAttribute('data-dragging', '');
  const onUp = () => el.removeAttribute('data-dragging');
  write();
  range.addEventListener('input', onInput);
  range.addEventListener('pointerdown', onDown);
  range.addEventListener('pointerup', onUp);
  range.addEventListener('pointercancel', onUp);
  return () => {
    range.removeEventListener('input', onInput);
    range.removeEventListener('pointerdown', onDown);
    range.removeEventListener('pointerup', onUp);
    range.removeEventListener('pointercancel', onUp);
  };
}

/* ---- Swipe actions --------------------------------------------------------- */

export type SwipeSide = 'start' | 'end';

export interface SwipeActionsOptions {
  /** A swipe past this fraction of the row's width fires the side's primary action. */
  fullSwipe?: number | false;
  onOpen?: (side: SwipeSide) => void;
  onClose?: () => void;
  /** Called before the primary button is clicked on a full swipe. */
  onFullSwipe?: (side: SwipeSide) => void;
}

/**
 * A row (`el`) whose `.nx-swipe-content` slides aside to reveal the action
 * buttons in `.nx-swipe-actions[data-side=start|end]` behind it. Writes `--_x`
 * (physical px), `data-open`, `data-armed`. Focusing an action opens its side,
 * so every action stays reachable from the keyboard; Escape closes.
 */
export function swipeActions(el: HTMLElement, options: SwipeActionsOptions = {}): Cleanup {
  if (!isBrowser) return () => {};
  const content = el.querySelector<HTMLElement>('.nx-swipe-content');
  if (!content) return () => {};
  const full = options.fullSwipe === false ? Infinity : (options.fullSwipe ?? 0.62);

  let x = 0;
  let pointerId = -1;
  let startX = 0;
  let startY = 0;
  let base = 0;
  let locked: 'x' | 'y' | null = null;
  let armed: SwipeSide | null = null;
  let suppressClick = false;
  const velocity = velocityTracker();

  const actions = (side: SwipeSide) => el.querySelector<HTMLElement>(`.nx-swipe-actions[data-side="${side}"]`);
  // The actions' natural width: measured while the row is closed (open, the box stretches with the drag).
  const natural = new Map<SwipeSide, number>();
  const width = (side: SwipeSide) => {
    if (x === 0 || !natural.has(side)) {
      const box = actions(side);
      natural.set(side, box ? Array.from(box.children).reduce((sum, child) => sum + (child as HTMLElement).offsetWidth, 0) : 0);
    }
    return natural.get(side) ?? 0;
  };
  /** Which side a physical offset reveals: moving toward inline-end uncovers the start side. */
  const sideOf = (offset: number): SwipeSide => (offset * direction(el) > 0 ? 'start' : 'end');
  const sign = (side: SwipeSide) => (side === 'start' ? 1 : -1) * direction(el);

  const write = () => {
    el.style.setProperty('--_x', `${x.toFixed(1)}px`);
    const side = x === 0 ? null : sideOf(x);
    const w = side ? (natural.get(side) ?? 0) : 0;
    el.style.setProperty('--_reveal', w ? clamp(Math.abs(x) / w, 0, 1).toFixed(3) : '0');
    el.setAttribute('data-side', side ?? '');
  };

  const open = (side: SwipeSide | null) => {
    x = side ? sign(side) * width(side) : 0;
    el.setAttribute('data-open', side ?? '');
    for (const s of ['start', 'end'] as const) actions(s)?.toggleAttribute('data-shown', s === side);
    write();
    if (side) options.onOpen?.(side);
    else options.onClose?.();
  };

  const onDown = (event: PointerEvent) => {
    if (event.button !== 0 || !event.isPrimary || pointerId !== -1) return;
    pointerId = event.pointerId;
    startX = event.clientX;
    startY = event.clientY;
    base = x;
    locked = null;
    velocity.reset(0);
    if (x === 0) {
      width('start');
      width('end');
    }
  };

  const onMove = (event: PointerEvent) => {
    if (event.pointerId !== pointerId) return;
    const dx = event.clientX - startX;
    const dy = event.clientY - startY;
    if (!locked) {
      if (Math.hypot(dx, dy) < 6) return;
      locked = Math.abs(dx) > Math.abs(dy) ? 'x' : 'y';
      if (locked === 'y') {
        pointerId = -1;
        return;
      }
      content.setPointerCapture(pointerId);
      el.setAttribute('data-dragging', '');
    }
    velocity.push(dx);
    const raw = base + dx;
    const side = sideOf(raw);
    const w = width(side);
    const limit = el.clientWidth;
    let next: number;
    if (!w) next = Math.sign(raw) * rubberBand(Math.abs(raw), 28);
    else if (Math.abs(raw) <= w || full !== Infinity) next = clamp(raw, -limit, limit);
    else next = Math.sign(raw) * (w + rubberBand(Math.abs(raw) - w, 40));
    x = next;
    const nowArmed = w && Math.abs(x) > limit * full && actions(side)?.querySelector('[data-primary]') ? side : null;
    if (nowArmed !== armed) {
      armed = nowArmed;
      el.toggleAttribute('data-armed', !!armed);
      if (armed) buzz(10);
    }
    write();
  };

  const onUp = (event: PointerEvent) => {
    if (event.pointerId !== pointerId) return;
    pointerId = -1;
    if (locked !== 'x') return;
    suppressClick = true;
    setTimeout(() => (suppressClick = false), 0);
    el.removeAttribute('data-dragging');
    const speed = velocity.speed();
    if (armed) {
      const side = armed;
      armed = null;
      el.removeAttribute('data-armed');
      x = sign(side) * el.clientWidth;
      el.setAttribute('data-fired', side);
      write();
      options.onFullSwipe?.(side);
      actions(side)?.querySelector<HTMLElement>('[data-primary]')?.click();
      setTimeout(() => {
        el.removeAttribute('data-fired');
        if (el.isConnected) open(null);
      }, 360);
      return;
    }
    const side = sideOf(x);
    const w = width(side);
    const outward = Math.sign(speed) === Math.sign(x) && Math.abs(speed) > FLICK_VELOCITY;
    const inward = Math.sign(speed) !== Math.sign(x) && Math.abs(speed) > FLICK_VELOCITY;
    if (w && !inward && (Math.abs(x) > w / 2 || (outward && Math.abs(x) > 12))) open(side);
    else open(null);
  };

  // A drag that ends over the content is not a click; while open, a tap on the content closes.
  const onClick = (event: MouseEvent) => {
    if (!content.contains(event.target as Node)) return;
    if (suppressClick || el.getAttribute('data-open')) {
      event.preventDefault();
      event.stopPropagation();
      if (!suppressClick) open(null);
    }
  };

  const onFocusIn = (event: FocusEvent) => {
    const box = (event.target as Element).closest<HTMLElement>('.nx-swipe-actions');
    const side = box?.getAttribute('data-side') as SwipeSide | null;
    if (side && el.getAttribute('data-open') !== side) open(side);
  };
  const onFocusOut = (event: FocusEvent) => {
    if (!el.contains(event.relatedTarget as Node | null) && el.getAttribute('data-open')) open(null);
  };
  const onKey = (event: KeyboardEvent) => {
    if (event.key === 'Escape' && el.getAttribute('data-open')) {
      event.preventDefault();
      open(null);
      content.querySelector<HTMLElement>('a, button, input, [tabindex]')?.focus();
    }
  };
  // An action pressed: the row closes behind it.
  const onActionClick = (event: MouseEvent) => {
    if ((event.target as Element).closest('.nx-swipe-actions button') && !el.hasAttribute('data-fired')) setTimeout(() => el.isConnected && open(null), 80);
  };

  write();
  content.addEventListener('pointerdown', onDown);
  content.addEventListener('pointermove', onMove);
  content.addEventListener('pointerup', onUp);
  content.addEventListener('pointercancel', onUp);
  el.addEventListener('click', onClick, true);
  el.addEventListener('click', onActionClick);
  el.addEventListener('focusin', onFocusIn);
  el.addEventListener('focusout', onFocusOut);
  el.addEventListener('keydown', onKey);
  const onReset = () => open(null);
  el.addEventListener('nx-reset', onReset);

  return () => {
    content.removeEventListener('pointerdown', onDown);
    content.removeEventListener('pointermove', onMove);
    content.removeEventListener('pointerup', onUp);
    content.removeEventListener('pointercancel', onUp);
    el.removeEventListener('click', onClick, true);
    el.removeEventListener('click', onActionClick);
    el.removeEventListener('focusin', onFocusIn);
    el.removeEventListener('focusout', onFocusOut);
    el.removeEventListener('keydown', onKey);
    el.removeEventListener('nx-reset', onReset);
  };
}

/* ---- Password strength ----------------------------------------------------- */

export type PasswordRuleId = 'length' | 'lower' | 'upper' | 'number' | 'symbol';

export interface PasswordStrengthOptions {
  minLength?: number;
  /** Words from the form (name, email) that make a password guessable. */
  userInputs?: string[];
}

export interface PasswordStrength {
  /** 0 too weak · 1 weak · 2 fair · 3 good · 4 strong. */
  score: 0 | 1 | 2 | 3 | 4;
  /** Estimated bits of entropy after the penalties. */
  entropy: number;
  rules: Array<{ id: PasswordRuleId; met: boolean }>;
}

export const passwordRuleIds: PasswordRuleId[] = ['length', 'lower', 'upper', 'number', 'symbol'];

/** The words the meter shows for each score, in English; pass your own through props. */
export const passwordScoreLabels = ['Too weak', 'Weak', 'Fair', 'Good', 'Strong'] as const;

const COMMON = ['password', 'passw0rd', 'qwerty', 'letmein', 'welcome', 'admin', 'iloveyou', 'monkey', 'dragon', 'abc123', '123456', '111111', 'sunshine', 'football', 'master', 'login', 'princess', 'secret'];

const hasLower = (s: string) => /\p{Ll}/u.test(s);
const hasUpper = (s: string) => /\p{Lu}/u.test(s);
const hasNumber = (s: string) => /\p{Nd}/u.test(s);
const hasSymbol = (s: string) => /[^\p{L}\p{Nd}]/u.test(s);
/** Letters of scripts without case (Persian, Arabic, CJK…). */
const hasCaseless = (s: string) => /\p{Lo}/u.test(s);

/**
 * Score a password from 0 to 4. An entropy estimate from the character pool,
 * discounted for repeats ("aaaa"), runs ("1234", "abcd") and common or
 * personal words; anything shorter than `minLength` scores at most 1.
 */
export function passwordStrength(password: string, { minLength = 8, userInputs = [] }: PasswordStrengthOptions = {}): PasswordStrength {
  const chars = Array.from(password);
  const rules: PasswordStrength['rules'] = [
    { id: 'length', met: chars.length >= minLength },
    { id: 'lower', met: hasLower(password) || hasCaseless(password) },
    { id: 'upper', met: hasUpper(password) },
    { id: 'number', met: hasNumber(password) },
    { id: 'symbol', met: hasSymbol(password) },
  ];
  if (chars.length === 0) return { score: 0, entropy: 0, rules };

  let pool = 0;
  if (hasLower(password)) pool += 26;
  if (hasUpper(password)) pool += 26;
  if (hasNumber(password)) pool += 10;
  if (hasSymbol(password)) pool += 33;
  if (hasCaseless(password)) pool += 32;

  // Each character adds a full share unless it repeats or continues a run.
  let effective = 0;
  for (let i = 0; i < chars.length; i++) {
    const code = chars[i]!.codePointAt(0)!;
    const prev = i > 0 ? chars[i - 1]!.codePointAt(0)! : null;
    if (prev === code) effective += 0.2;
    else if (prev !== null && Math.abs(code - prev) === 1) effective += 0.35;
    else effective += 1;
  }
  let entropy = effective * Math.log2(Math.max(2, pool));

  const lower = password.toLowerCase();
  const words = [...COMMON, ...userInputs.flatMap((input) => input.toLowerCase().split(/[^\p{L}\p{Nd}]+/u)).filter((word) => word.length >= 3)];
  for (const word of words) {
    if (lower.includes(word)) entropy -= Math.max(0, word.length - 1) * Math.log2(Math.max(2, pool)) * 0.85;
  }
  // Only one kind of character, all the same: hardly better than nothing.
  if (new Set(chars).size <= 2) entropy = Math.min(entropy, 10);
  entropy = Math.max(0, entropy);

  let score: PasswordStrength['score'] = entropy < 28 ? 0 : entropy < 40 ? 1 : entropy < 56 ? 2 : entropy < 72 ? 3 : 4;
  if (chars.length < minLength && score > 1) score = 1;
  return { score, entropy: Math.round(entropy * 10) / 10, rules };
}

/* ---- Pull cord ------------------------------------------------------------------------ */

export interface RopePoint {
  x: number;
  y: number;
  px: number;
  py: number;
}

/**
 * One Verlet step for a hanging rope: points[0] is the pin on the ceiling,
 * every free point carries gravity and damping, then `passes` rounds of
 * distance constraints pull the chain back together. Pure maths so it can be
 * tested on its own; the DOM part is pullCord below.
 */
export function ropeStep(
  points: RopePoint[],
  { gravity = 1400, damping = 0.985, segmentLength, passes = 5, dt = 1 / 60 }: {
    gravity?: number;
    damping?: number;
    segmentLength: number;
    passes?: number;
    dt?: number;
  },
): void {
  for (let i = 1; i < points.length; i++) {
    const point = points[i]!;
    const vx = (point.x - point.px) * damping;
    const vy = (point.y - point.py) * damping + gravity * dt * dt;
    point.px = point.x;
    point.py = point.y;
    point.x += vx;
    point.y += vy;
  }
  for (let pass = 0; pass < passes; pass++) {
    for (let i = 0; i < points.length - 1; i++) {
      const a = points[i]!;
      const b = points[i + 1]!;
      const dx = b.x - a.x;
      const dy = b.y - a.y;
      const dist = Math.hypot(dx, dy) || 0.0001;
      const offset = (dist - segmentLength) / dist / 2;
      if (i === 0) {
        // The pinned end does not move; the neighbour carries the whole correction.
        b.x -= dx * offset * 2;
        b.y -= dy * offset * 2;
      } else {
        a.x += dx * offset;
        a.y += dy * offset;
        b.x -= dx * offset;
        b.y -= dy * offset;
      }
    }
    points[0]!.x = points[0]!.px;
    points[0]!.y = points[0]!.py;
  }
}

export interface PullCordOptions {
  /** Vertical pull (px) past which the cord counts as pulled; release fires. */
  trigger?: number;
  /** How many segments the rope is simulated with (3–24). */
  segments?: number;
  /** Fired once when the cord passes the trigger and is let go. */
  onPull?: () => void;
  /** Fired as the cord arms (true) and un-arms (false) while held. */
  onArm?: (armed: boolean) => void;
}

/**
 * A ceiling pull-cord with a real rope: verlet integration, gravity and a
 * grab that drags the end along. Past `trigger` px of pull the cord arms
 * (data-nx-armed on the root); letting go fires onPull and flings the knob
 * home so the rope springs and wobbles. A deliberate gesture, so touch works
 * too; reduced motion skips the simulation and treats it as a plain
 * pull-and-release with the state as the only feedback.
 */
export function pullCord(el: HTMLElement, options: PullCordOptions = {}): Cleanup {
  if (!isBrowser) return () => {};
  const reduced = prefersReducedMotion();
  const segments = Math.max(3, Math.min(24, options.segments ?? 12));

  const svg = el.querySelector('svg.nx-pull-cord-rope');
  const knob = el.querySelector<HTMLElement>('.nx-pull-cord-knob');
  const knobEl = (knob ?? el) as HTMLElement;
  let points: RopePoint[] = [];
  let width = 10;
  let height = 40;
  let half = 17; // knob radius; the rope's end is the knob's centre
  let rest = 32; // resting cord length in px (~55% down the box; the rest is pull room)
  let armedAt = 72; // effective trigger, clamped to the pull room the box allows
  let raf = 0;
  let simUntil = 0; // keep simulating until this timestamp, then reset
  let down = false;
  let armed = false;

  const resize = () => {
    const rect = el.getBoundingClientRect();
    width = Math.max(10, rect.width);
    height = Math.max(40, rect.height);
    half = (knob?.offsetWidth ?? 34) / 2;
    rest = Math.max(24, Math.round(height * 0.55) - half);
    armedAt = Math.min(options.trigger ?? 72, Math.max(24, (height - rest - half) * 0.8));
    svg?.setAttribute('viewBox', `0 0 ${Math.round(width)} ${Math.round(height)}`);
    const cx = width / 2;
    points = Array.from({ length: segments + 1 }, (_, i) => ({ x: cx, y: (rest * i) / segments, px: cx, py: (rest * i) / segments }));
  };

  const draw = () => {
    if (!svg || !knob) return;
    const path = svg.querySelector('path');
    if (path && points.length > 2) {
      // Quadratic segments through midpoints render the sag with one path.
      let d = `M ${points[0]!.x.toFixed(1)} ${points[0]!.y.toFixed(1)}`;
      for (let i = 1; i < points.length - 1; i++) {
        const a = points[i]!;
        const b = points[i + 1]!;
        d += ` Q ${a.x.toFixed(1)} ${a.y.toFixed(1)} ${((a.x + b.x) / 2).toFixed(1)} ${((a.y + b.y) / 2).toFixed(1)}`;
      }
      const end = points[points.length - 1]!;
      d += ` L ${end.x.toFixed(1)} ${end.y.toFixed(1)}`;
      path.setAttribute('d', d);
    }
    const end = points[points.length - 1]!;
    // The knob's top-left corner starts at the mount; move its centre onto the rope's end.
    knob.style.transform = `translate(${(end.x - width / 2).toFixed(1)}px, ${(end.y - half).toFixed(1)}px)`;
  };

  const tick = () => {
    ropeStep(points, { segmentLength: rest / segments });
    draw();
    if (performance.now() > simUntil && !down) {
      raf = 0;
      resize();
      draw();
      return;
    }
    raf = requestAnimationFrame(tick);
  };

  const wake = (ms = 1500) => {
    if (!raf) raf = requestAnimationFrame(tick);
    simUntil = Math.max(simUntil, performance.now() + ms);
  };

  const setArmed = (value: boolean) => {
    if (armed === value) return;
    armed = value;
    if (value) el.setAttribute('data-nx-armed', '');
    else el.removeAttribute('data-nx-armed');
    options.onArm?.(value);
  };

  const local = (event: PointerEvent) => {
    const rect = el.getBoundingClientRect();
    return {
      x: event.clientX - rect.left,
      y: Math.min(height - half, Math.max(half * 0.5, event.clientY - rect.top)),
    };
  };

  const onDown = (event: PointerEvent) => {
    if (event.pointerType === 'mouse' && event.button !== 0) return;
    down = true;
    knobEl.setPointerCapture?.(event.pointerId);
    if (!reduced) wake(4000);
  };

  const onMove = (event: PointerEvent) => {
    if (!down) return;
    const { x, y } = local(event);
    const end = points[points.length - 1]!;
    if (reduced) {
      const pull = Math.max(0, y - rest);
      end.x = width / 2;
      end.y = rest + pull;
      end.px = end.x;
      end.py = end.y;
      setArmed(pull >= armedAt);
      draw();
      return;
    }
    end.x = x;
    end.y = y;
    end.px = x;
    end.py = y - 1; // a hair of upward bias keeps the rope taut under the pointer
    setArmed(y - rest >= armedAt);
  };

  const onUp = (event: PointerEvent) => {
    if (!down) return;
    down = false;
    knobEl.releasePointerCapture?.(event.pointerId);
    const end = points[points.length - 1]!;
    if (reduced) {
      const wasArmed = armed;
      setArmed(false);
      end.y = rest;
      end.py = rest;
      end.x = width / 2;
      draw();
      if (wasArmed) options.onPull?.();
      return;
    }
    if (armed) {
      // Fling the knob home; the simulation carries the spring and wobble.
      end.py = end.y + Math.max(22, (end.y - rest) * 0.5);
      setArmed(false);
      wake(1600);
      options.onPull?.();
    } else {
      wake(600);
    }
  };

  resize();
  draw();
  const observer = new ResizeObserver(() => {
    if (!down && !raf) {
      resize();
      draw();
    }
  });
  observer.observe(el);

  knobEl.addEventListener('pointerdown', onDown);
  knobEl.addEventListener('pointermove', onMove);
  knobEl.addEventListener('pointerup', onUp);
  knobEl.addEventListener('pointercancel', onUp);

  return () => {
    cancelAnimationFrame(raf);
    observer.disconnect();
    knobEl.removeEventListener('pointerdown', onDown);
    knobEl.removeEventListener('pointermove', onMove);
    knobEl.removeEventListener('pointerup', onUp);
    knobEl.removeEventListener('pointercancel', onUp);
  };
}
