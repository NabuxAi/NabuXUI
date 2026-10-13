/**
 * Framework-agnostic behaviours for the effects & layout-motion blocks.
 *
 *   borderBeam        pauses a .nx-beam while it is off screen
 *   typewriter        types phrases a grapheme (or word) at a time, deletes back
 *                     to the shared prefix and types the next one
 *   resizablePanels   split panes with draggable, keyboard-operable separators
 *   scrollProgress    page / article / box reading progress as --nx-scroll
 *   autoHeight        a container that springs to its content's new height
 *   dynamicIsland     sizes the morphing pill to its active view
 *   spotlightTour     cuts the dimmed page open around a step's target and
 *                     places the step popover next to it
 *   imageReveal       marks an image loaded and drives the pixel-noise canvas
 *
 * The pure helpers (pane maths, typing script, tour step info, spotlight
 * geometry, reading progress) are exported for tests and for the frameworks.
 */
import { type Cleanup, direction, isBrowser, onReducedMotionChange, prefersReducedMotion } from '../env';
import { place, type Side } from '../place';
import { type SpringName, springEasing, springs } from '../spring';
import { textDirection } from '../text';

const clamp = (value: number, min: number, max: number) => Math.min(max, Math.max(min, value));
const clamp01 = (value: number) => clamp(value, 0, 1);
const round3 = (value: number) => Math.round(value * 1000) / 1000;
const EPS = 0.001;

/* ---- Border beam ------------------------------------------------------------------ */

export interface BorderBeamOptions {
  /** Stop the travelling light while the element is off screen (default true). */
  pauseOffscreen?: boolean;
}

/**
 * The beam itself is pure CSS (`.nx-beam`); this only pauses it — sets
 * `data-paused` — while the element is outside the viewport, so a page full
 * of beams does not keep repainting what nobody sees.
 */
export function borderBeam(el: HTMLElement, { pauseOffscreen = true }: BorderBeamOptions = {}): Cleanup {
  if (!isBrowser || !pauseOffscreen || !('IntersectionObserver' in window)) return () => {};
  const observer = new IntersectionObserver(([entry]) => el.toggleAttribute('data-paused', !entry?.isIntersecting), { rootMargin: '64px' });
  observer.observe(el);
  return () => {
    observer.disconnect();
    el.removeAttribute('data-paused');
  };
}

/* ---- Typewriter ----------------------------------------------------------------------- */

export type TypewriterUnit = 'grapheme' | 'word';

let graphemeSegmenter: Intl.Segmenter | null | undefined;

/**
 * The pieces a phrase is typed in. Graphemes by default — a user-perceived
 * character, so an accent, an emoji sequence, a Persian letter with its
 * diacritic or a zero-width non-joiner stays with the letter it belongs to
 * (a prefix of Persian text still shapes correctly, letters simply take their
 * final form until the next one arrives) — or whole words with their spaces.
 */
export function typewriterUnits(text: string, by: TypewriterUnit = 'grapheme'): string[] {
  if (by === 'word') return text.match(/\S+\s*|\s+/g) ?? [];
  if (graphemeSegmenter === undefined) {
    graphemeSegmenter = typeof Intl !== 'undefined' && 'Segmenter' in Intl ? new Intl.Segmenter(undefined, { granularity: 'grapheme' }) : null;
  }
  const raw = graphemeSegmenter ? Array.from(graphemeSegmenter.segment(text), (s) => s.segment) : Array.from(text);
  // Join controls (ZWNJ / ZWJ) never stand alone, whatever the segmenter thinks.
  const units: string[] = [];
  for (const unit of raw) {
    if (/^[‌‍]+$/.test(unit) && units.length) units[units.length - 1] += unit;
    else units.push(unit);
  }
  return units;
}

export interface TypewriterStep {
  /** What is on screen after this step. */
  text: string;
  /** How long to wait before the next step, ms. */
  delay: number;
  state: 'typing' | 'holding' | 'deleting';
  /** Index of the phrase being typed or deleted. */
  phrase: number;
}

export interface TypewriterTiming {
  by?: TypewriterUnit;
  /** ms per typed unit. */
  typeSpeed?: number;
  /** ms per deleted unit. */
  deleteSpeed?: number;
  /** ms a finished phrase stays before it is deleted. */
  hold?: number;
  /** Cycle forever (default) or stop on the last phrase. */
  loop?: boolean;
}

const PAUSE_AFTER = /[.,!?;:،؛؟…]\s*$/;

/**
 * The full typing script: every frame of text and how long it stays. Deletes
 * only back to the part the next phrase shares ("We build sites" → "We build
 * apps" keeps "We build "), and lingers a little after punctuation. With
 * `loop` the sequence never ends, so take what you need from the iterator.
 */
export function* typewriterSteps(phrases: string[], { by = 'grapheme', typeSpeed = 55, deleteSpeed = 28, hold = 1800, loop = true }: TypewriterTiming = {}): Generator<TypewriterStep, void, undefined> {
  const list = phrases.filter((phrase) => phrase.length > 0);
  if (list.length === 0) return;
  const units = list.map((phrase) => typewriterUnits(phrase, by));
  let typed = 0; // units of the current phrase already on screen
  let index = 0;

  for (;;) {
    const current = units[index]!;
    for (let k = typed + 1; k <= current.length; k++) {
      const text = current.slice(0, k).join('');
      yield { text, delay: PAUSE_AFTER.test(text) && k < current.length ? typeSpeed * 5 : typeSpeed, state: 'typing', phrase: index };
    }
    const last = index === list.length - 1;
    if (last && !loop) {
      yield { text: current.join(''), delay: hold, state: 'holding', phrase: index };
      return;
    }
    if (list.length === 1) {
      // One phrase on a loop: hold, delete everything, type it again.
      yield { text: current.join(''), delay: hold, state: 'holding', phrase: index };
      for (let k = current.length - 1; k >= 0; k--) yield { text: current.slice(0, k).join(''), delay: deleteSpeed, state: 'deleting', phrase: index };
      typed = 0;
      continue;
    }
    const nextIndex = (index + 1) % list.length;
    const next = units[nextIndex]!;
    let shared = 0;
    while (shared < current.length && shared < next.length && current[shared] === next[shared]) shared++;
    yield { text: current.join(''), delay: hold, state: 'holding', phrase: index };
    for (let k = current.length - 1; k >= shared; k--) {
      yield { text: current.slice(0, k).join(''), delay: k === shared ? deleteSpeed * 6 : deleteSpeed, state: 'deleting', phrase: index };
    }
    typed = shared;
    index = nextIndex;
  }
}

export interface TypewriterOptions extends TypewriterTiming {
  phrases: string[];
  /** Wait before the first letter, ms. */
  startDelay?: number;
  /** Called when a phrase is fully typed. */
  onPhrase?: (index: number) => void;
}

/**
 * Types into `.nx-typewriter-text` inside `el` (or `el` itself) and writes
 * `data-state` (typing | holding | deleting) on `el` so the caret blinks only
 * while idle. The line's `dir` follows the phrase. Under reduced motion the
 * phrases swap whole after each hold, with no typing and no deleting. Pauses
 * while the element is off screen.
 */
export function typewriter(el: HTMLElement, { phrases, startDelay = 400, onPhrase, ...timing }: TypewriterOptions): Cleanup {
  if (!isBrowser) return () => {};
  const list = phrases.filter(Boolean);
  const target = el.querySelector<HTMLElement>('.nx-typewriter-text') ?? el;
  const line = el.querySelector<HTMLElement>('.nx-typewriter-line') ?? target;
  if (list.length === 0) return () => {};

  let timer = 0;
  let visible = true;
  let pending: (() => void) | null = null;
  let steps: Generator<TypewriterStep, void, undefined>;
  let lastPhrase = -1;

  const write = (text: string, phrase: number) => {
    target.textContent = text;
    if (phrase !== lastPhrase) {
      lastPhrase = phrase;
      line.dir = textDirection(list[phrase] ?? text);
    }
  };

  const schedule = (fn: () => void, delay: number) => {
    timer = window.setTimeout(() => {
      timer = 0;
      if (visible) fn();
      else pending = fn;
    }, delay);
  };

  const tick = () => {
    const next = steps.next();
    if (next.done) {
      el.dataset.state = 'holding';
      return;
    }
    const step = next.value;
    write(step.text, step.phrase);
    el.dataset.state = step.state;
    if (step.state === 'holding') onPhrase?.(step.phrase);
    schedule(tick, step.delay);
  };

  // Reduced motion: whole phrases, swapped in place.
  const swap = (index: number) => {
    write(list[index]!, index);
    el.dataset.state = 'holding';
    onPhrase?.(index);
    const last = index === list.length - 1;
    if (last && timing.loop === false) return;
    if (list.length > 1) schedule(() => swap((index + 1) % list.length), (timing.hold ?? 1800) + 600);
  };

  const start = () => {
    window.clearTimeout(timer);
    pending = null;
    lastPhrase = -1;
    if (prefersReducedMotion()) {
      swap(0);
      return;
    }
    steps = typewriterSteps(list, timing);
    write('', 0);
    el.dataset.state = 'typing';
    schedule(tick, startDelay);
  };

  const observer =
    'IntersectionObserver' in window
      ? new IntersectionObserver(([entry]) => {
          visible = !!entry?.isIntersecting;
          if (visible && pending) {
            const fn = pending;
            pending = null;
            fn();
          }
        })
      : null;
  observer?.observe(el);
  const stopMotion = onReducedMotionChange(start);
  start();

  return () => {
    window.clearTimeout(timer);
    observer?.disconnect();
    stopMotion();
    target.textContent = list[0]!;
    delete el.dataset.state;
  };
}

/* ---- Resizable panels ------------------------------------------------------------------ */

/** Limits of one pane, in percent of the group. */
export interface PaneConstraint {
  min?: number;
  max?: number;
  /** May shrink past its minimum to `collapsedSize` (dragged under half the minimum, double-click, Enter). */
  collapsible?: boolean;
  collapsedSize?: number;
}

const limits = (c: PaneConstraint | undefined) => {
  const min = clamp(c?.min ?? 0, 0, 100);
  return { min, max: clamp(c?.max ?? 100, min, 100), collapsible: !!c?.collapsible, collapsed: clamp(c?.collapsedSize ?? 0, 0, min) };
};

/** Whether a pane sits at its collapsed size. */
export function isPaneCollapsed(size: number, constraint?: PaneConstraint): boolean {
  const c = limits(constraint);
  return c.collapsible && size <= c.collapsed + EPS && c.collapsed < c.min;
}

/**
 * Turn whatever sizes were given (some may be missing, they may not add up)
 * into percentages that respect every pane's limits and sum to 100. Missing
 * sizes share what is left; the difference is spread over the panes that have
 * room, so a pane that hits its limit hands the rest to its neighbours.
 */
export function normalizePaneSizes(sizes: Array<number | null | undefined>, constraints: PaneConstraint[] = []): number[] {
  const count = Math.max(sizes.length, constraints.length);
  if (count === 0) return [];
  const known = sizes.filter((s): s is number => typeof s === 'number' && Number.isFinite(s));
  const missing = count - known.length;
  const share = missing > 0 ? Math.max(0, 100 - known.reduce((a, b) => a + b, 0)) / missing : 0;
  const c = Array.from({ length: count }, (_, i) => limits(constraints[i]));

  const out = Array.from({ length: count }, (_, i) => {
    const raw = sizes[i];
    const size = typeof raw === 'number' && Number.isFinite(raw) ? raw : share;
    if (isPaneCollapsed(size, constraints[i])) return c[i]!.collapsed;
    return clamp(size, c[i]!.min, c[i]!.max);
  });

  let diff = 100 - out.reduce((a, b) => a + b, 0);
  for (let pass = 0; pass < count * 2 && Math.abs(diff) > EPS; pass++) {
    const open = out.map((size, i) => i).filter((i) => !isPaneCollapsed(out[i]!, constraints[i]) && (diff > 0 ? out[i]! < c[i]!.max - EPS : out[i]! > c[i]!.min + EPS));
    if (open.length === 0) break;
    const each = diff / open.length;
    for (const i of open) {
      const next = clamp(out[i]! + each, c[i]!.min, c[i]!.max);
      diff -= next - out[i]!;
      out[i] = next;
    }
  }
  // Nothing has room (every pane collapsed or at a limit): the last pane takes the rest.
  if (Math.abs(diff) > EPS) out[count - 1] = Math.max(0, out[count - 1]! + diff);

  const rounded = out.map(round3);
  const drift = round3(100 - rounded.reduce((a, b) => a + b, 0));
  if (drift !== 0) {
    const i = rounded.findIndex((size, k) => !isPaneCollapsed(size, constraints[k]));
    rounded[i < 0 ? count - 1 : i] = round3(rounded[i < 0 ? count - 1 : i]! + drift);
  }
  return rounded;
}

/**
 * Move the boundary between pane `index` and pane `index + 1` by `delta`
 * percent (positive grows the first). Only those two panes change; both keep
 * their limits. A collapsible pane dragged under half its minimum snaps shut,
 * and stays shut until it is dragged past half its minimum again.
 */
export function resizePaneBoundary(sizes: number[], index: number, delta: number, constraints: PaneConstraint[] = []): number[] {
  if (index < 0 || index >= sizes.length - 1) return sizes.slice();
  const a = sizes[index]!;
  const b = sizes[index + 1]!;
  const total = a + b;
  const ca = limits(constraints[index]);
  const cb = limits(constraints[index + 1]);
  const low = Math.max(ca.min, total - cb.max);
  const high = Math.min(ca.max, total - cb.min);
  const wanted = a + delta;
  let next = low > high + EPS ? a : clamp(wanted, low, high);

  const fitsB = (bSize: number) => bSize >= cb.min - EPS && bSize <= cb.max + EPS;
  const fitsA = (aSize: number) => aSize >= ca.min - EPS && aSize <= ca.max + EPS;
  if (ca.collapsible && wanted < ca.min) {
    const snapped = wanted < (ca.min + ca.collapsed) / 2 ? ca.collapsed : ca.min;
    if (fitsB(total - snapped)) next = snapped;
  } else if (cb.collapsible && total - wanted < cb.min) {
    const snapped = total - wanted < (cb.min + cb.collapsed) / 2 ? cb.collapsed : cb.min;
    if (fitsA(total - snapped)) next = total - snapped;
  }

  const out = sizes.slice();
  out[index] = round3(next);
  out[index + 1] = round3(total - next);
  return out;
}

/**
 * Collapse a collapsible pane into its neighbour (the next one, or the
 * previous for the last pane), or open it again to `restore` (default: its
 * minimum, at least a fifth of the space the pair shares).
 */
export function togglePaneCollapse(sizes: number[], index: number, constraints: PaneConstraint[] = [], restore?: number): number[] {
  const c = limits(constraints[index]);
  if (!c.collapsible || sizes.length < 2) return sizes.slice();
  const collapsed = isPaneCollapsed(sizes[index]!, constraints[index]);
  const neighbour = index < sizes.length - 1 ? index + 1 : index - 1;
  const pair = sizes[index]! + sizes[neighbour]!;
  const target = collapsed ? clamp(restore ?? Math.max(c.min, pair / 5), c.min, c.max) : c.collapsed;
  const delta = target - sizes[index]!;
  return neighbour > index ? resizePaneBoundary(sizes, index, delta, constraints) : resizePaneBoundary(sizes, neighbour, -delta, constraints);
}

export type PanesOrientation = 'horizontal' | 'vertical';

export interface ResizablePanelsOptions {
  /** Remember sizes under this localStorage key. */
  storageKey?: string;
  /** Keyboard step, percent (Shift: ×4). */
  step?: number;
  /** Called after every change with the new sizes. */
  onResize?: (sizes: number[]) => void;
}

const num = (value: string | undefined) => (value === undefined || value === '' ? undefined : Number(value));

/**
 * Wire a `.nx-resizable` group: its direct `.nx-resizable-pane` children
 * (data-size, data-min, data-max, data-collapsible, data-collapsed-size) and
 * the `.nx-resizable-handle` separators between them. Drag a handle (pointer
 * capture, first pointer only), use the arrows / Home / End on a focused
 * handle, or double-click / Enter to collapse a collapsible neighbour. Sizes
 * are flex-grow weights, so the panes always fill the group; right-to-left
 * groups move the boundary the way the pointer goes. Nested groups each run
 * their own copy.
 */
export function resizablePanels(el: HTMLElement, { storageKey, step = 2, onResize }: ResizablePanelsOptions = {}): Cleanup {
  if (!isBrowser) return () => {};
  const vertical = () => el.dataset.orientation === 'vertical';
  const panes = Array.from(el.children).filter((child): child is HTMLElement => child.classList.contains('nx-resizable-pane'));
  const handles = Array.from(el.children).filter((child): child is HTMLElement => child.classList.contains('nx-resizable-handle'));
  if (panes.length < 2) return () => {};

  const constraints: PaneConstraint[] = panes.map((pane) => ({
    min: num(pane.dataset.min),
    max: num(pane.dataset.max),
    collapsible: pane.hasAttribute('data-collapsible'),
    collapsedSize: num(pane.dataset.collapsedSize),
  }));
  const restoreTo: Array<number | undefined> = panes.map(() => undefined);

  let sizes = normalizePaneSizes(panes.map((pane) => num(pane.dataset.size)), constraints);
  if (storageKey) {
    try {
      const saved = JSON.parse(window.localStorage.getItem(storageKey) ?? 'null') as unknown;
      if (Array.isArray(saved) && saved.length === panes.length && saved.every((s) => typeof s === 'number')) sizes = normalizePaneSizes(saved as number[], constraints);
    } catch {
      /* storage may be blocked */
    }
  }

  panes.forEach((pane, i) => {
    if (!pane.id) pane.id = `nx-pane-${Math.random().toString(36).slice(2, 9)}-${i}`;
  });

  const apply = (next: number[], notify = true) => {
    sizes = next;
    panes.forEach((pane, i) => {
      pane.style.flex = `${sizes[i]} 1 0px`;
      pane.toggleAttribute('data-collapsed', isPaneCollapsed(sizes[i]!, constraints[i]));
    });
    handles.forEach((handle, i) => {
      if (!panes[i + 1]) return;
      const c = limits(constraints[i]);
      handle.setAttribute('aria-valuenow', String(Math.round(sizes[i]!)));
      handle.setAttribute('aria-valuemin', String(Math.round(c.collapsible ? c.collapsed : c.min)));
      handle.setAttribute('aria-valuemax', String(Math.round(c.max)));
      handle.setAttribute('aria-controls', panes[i]!.id);
      handle.setAttribute('aria-orientation', vertical() ? 'horizontal' : 'vertical');
    });
    if (notify) onResize?.(sizes.slice());
  };

  const save = () => {
    if (!storageKey) return;
    try {
      window.localStorage.setItem(storageKey, JSON.stringify(sizes));
    } catch {
      /* storage may be full or blocked */
    }
  };

  let animTimer = 0;
  const animated = (next: number[]) => {
    window.clearTimeout(animTimer);
    el.setAttribute('data-animating', '');
    apply(next);
    animTimer = window.setTimeout(() => el.removeAttribute('data-animating'), 800);
  };

  const available = () => {
    const rect = el.getBoundingClientRect();
    const handleSpace = handles.reduce((sum, h) => sum + (vertical() ? h.offsetHeight : h.offsetWidth), 0);
    return Math.max(1, (vertical() ? rect.height : rect.width) - handleSpace);
  };

  const collapseAround = (i: number) => {
    // Prefer the pane before the handle, then the one after.
    const target = constraints[i]?.collapsible ? i : constraints[i + 1]?.collapsible ? i + 1 : -1;
    if (target < 0) return;
    if (!isPaneCollapsed(sizes[target]!, constraints[target])) restoreTo[target] = sizes[target];
    animated(togglePaneCollapse(sizes, target, constraints, restoreTo[target]));
    save();
  };

  const stops: Cleanup[] = [];
  handles.forEach((handle, i) => {
    if (!panes[i + 1]) return;
    let pointer: number | null = null;
    let origin = 0;
    let start: number[] = sizes;
    let space = 1;

    const down = (event: PointerEvent) => {
      if (pointer !== null || (event.pointerType === 'mouse' && event.button !== 0)) return;
      pointer = event.pointerId;
      origin = vertical() ? event.clientY : event.clientX;
      start = sizes.slice();
      space = available();
      handle.setPointerCapture(event.pointerId);
      handle.setAttribute('data-dragging', '');
      el.setAttribute('data-dragging', '');
      event.preventDefault();
    };
    const move = (event: PointerEvent) => {
      if (event.pointerId !== pointer) return;
      const travel = (vertical() ? event.clientY : event.clientX) - origin;
      const signed = vertical() ? travel : travel * direction(el);
      apply(resizePaneBoundary(start, i, (signed / space) * 100, constraints));
    };
    const up = (event: PointerEvent) => {
      if (event.pointerId !== pointer) return;
      pointer = null;
      handle.removeAttribute('data-dragging');
      el.removeAttribute('data-dragging');
      if (handle.hasPointerCapture(event.pointerId)) handle.releasePointerCapture(event.pointerId);
      save();
    };
    const key = (event: KeyboardEvent) => {
      const amount = event.shiftKey ? step * 4 : step;
      const forward = vertical() ? 'ArrowDown' : direction(el) === 1 ? 'ArrowRight' : 'ArrowLeft';
      const back = vertical() ? 'ArrowUp' : direction(el) === 1 ? 'ArrowLeft' : 'ArrowRight';
      let next: number[] | null = null;
      if (event.key === forward) next = resizePaneBoundary(sizes, i, amount, constraints);
      else if (event.key === back) next = resizePaneBoundary(sizes, i, -amount, constraints);
      else if (event.key === 'Home') next = resizePaneBoundary(sizes, i, -100, constraints);
      else if (event.key === 'End') next = resizePaneBoundary(sizes, i, 100, constraints);
      else if (event.key === 'Enter') {
        event.preventDefault();
        collapseAround(i);
        return;
      } else return;
      event.preventDefault();
      apply(next);
      save();
    };
    const dbl = () => collapseAround(i);

    handle.addEventListener('pointerdown', down);
    handle.addEventListener('pointermove', move);
    handle.addEventListener('pointerup', up);
    handle.addEventListener('pointercancel', up);
    handle.addEventListener('keydown', key);
    handle.addEventListener('dblclick', dbl);
    stops.push(() => {
      handle.removeEventListener('pointerdown', down);
      handle.removeEventListener('pointermove', move);
      handle.removeEventListener('pointerup', up);
      handle.removeEventListener('pointercancel', up);
      handle.removeEventListener('keydown', key);
      handle.removeEventListener('dblclick', dbl);
    });
  });

  apply(sizes, false);

  return () => {
    window.clearTimeout(animTimer);
    stops.forEach((stop) => stop());
    el.removeAttribute('data-dragging');
    el.removeAttribute('data-animating');
  };
}

/* ---- Scroll progress -------------------------------------------------------------------- */

/**
 * How far through an element the reader is: 0 while its top is still below
 * the top of the viewport, 1 once its bottom has reached the viewport's
 * bottom. An element shorter than the viewport is 0 or 1.
 */
export function readingProgress(top: number, height: number, viewport: number): number {
  const span = height - viewport;
  if (span < 1) return top <= 0 ? 1 : 0;
  return clamp01(-top / span);
}

/** Whether CSS can run animations on a scroll timeline. */
export function supportsScrollTimeline(): boolean {
  return isBrowser && typeof CSS !== 'undefined' && typeof CSS.supports === 'function' && CSS.supports('animation-timeline: scroll()');
}

export interface ScrollProgressOptions {
  /** Measure reading progress through this element (selector or element) instead of the whole scroller. */
  target?: string | Element | null;
  /** The scrolling box (selector or element); the page when absent. */
  container?: string | Element | null;
  /** Called with every new progress, 0–1. */
  onProgress?: (progress: number) => void;
}

const resolve = (ref: string | Element | null | undefined) => (typeof ref === 'string' ? document.querySelector(ref) : (ref ?? null));

/**
 * Write the progress as `--nx-scroll` (0–1) on `el`, keep `aria-valuenow`
 * (0–100) in step when `el` is a progressbar, and set `data-complete` at the
 * end. For the plain page bar the stylesheet prefers a CSS scroll timeline
 * where the browser has one; this keeps the number and the fallback.
 */
export function scrollProgress(el: HTMLElement, { target, container, onProgress }: ScrollProgressOptions = {}): Cleanup {
  if (!isBrowser) return () => {};
  const box = resolve(container) as HTMLElement | null;
  const subject = resolve(target);
  const bar = el.getAttribute('role') === 'progressbar' ? el : el.querySelector<HTMLElement>('[role="progressbar"]');
  let frame = 0;
  let last = -1;

  const measure = () => {
    frame = 0;
    let progress: number;
    if (subject) {
      const rect = subject.getBoundingClientRect();
      const view = box ? box.getBoundingClientRect() : { top: 0, height: window.innerHeight };
      progress = readingProgress(rect.top - view.top, rect.height, view.height);
    } else {
      const scroller = box ?? document.scrollingElement ?? document.documentElement;
      const span = scroller.scrollHeight - scroller.clientHeight;
      progress = span < 1 ? 0 : clamp01(scroller.scrollTop / span);
    }
    progress = Math.round(progress * 1000) / 1000;
    if (progress === last) return;
    last = progress;
    el.style.setProperty('--nx-scroll', String(progress));
    el.toggleAttribute('data-complete', progress >= 0.999);
    el.toggleAttribute('data-started', progress > 0.005);
    bar?.setAttribute('aria-valuenow', String(Math.round(progress * 100)));
    onProgress?.(progress);
  };
  const schedule = () => {
    if (!frame) frame = requestAnimationFrame(measure);
  };

  const scrollTarget: EventTarget = box ?? document;
  scrollTarget.addEventListener('scroll', schedule, { passive: true, capture: !box });
  window.addEventListener('resize', schedule, { passive: true });
  const observer = 'ResizeObserver' in window ? new ResizeObserver(schedule) : null;
  if (subject) observer?.observe(subject);
  measure();

  return () => {
    cancelAnimationFrame(frame);
    scrollTarget.removeEventListener('scroll', schedule, { capture: !box } as EventListenerOptions);
    window.removeEventListener('resize', schedule);
    observer?.disconnect();
  };
}

/** Scroll the page, or a scrolling box, back to the top (instantly under reduced motion). */
export function scrollToStart(container?: string | Element | null): void {
  if (!isBrowser) return;
  const box = resolve(container);
  const behavior: ScrollBehavior = prefersReducedMotion() ? 'auto' : 'smooth';
  if (box) box.scrollTo({ top: 0, behavior });
  else window.scrollTo({ top: 0, behavior });
}

/* ---- Auto height ------------------------------------------------------------------------- */

export interface AutoHeightOptions {
  /** The spring the height follows. */
  spring?: SpringName;
}

const easingCache = new Map<SpringName, { easing: string; duration: number }>();
function springFor(name: SpringName) {
  let value = easingCache.get(name);
  if (!value) {
    value = springEasing(springs[name]);
    easingCache.set(name, value);
  }
  return value;
}

/**
 * Animate `el`'s height whenever its first child changes size: the content
 * re-lays out instantly, the frame springs from the old height to the new one
 * (interrupting cleanly mid-flight). The frame clips while it moves. Under
 * reduced motion the height simply jumps.
 */
export function autoHeight(el: HTMLElement, { spring = 'gentle' }: AutoHeightOptions = {}): Cleanup {
  if (!isBrowser || !('ResizeObserver' in window)) return () => {};
  const inner = el.firstElementChild as HTMLElement | null;
  if (!inner) return () => {};
  let last = inner.getBoundingClientRect().height;
  let animation: Animation | null = null;

  const observer = new ResizeObserver(() => {
    const next = inner.getBoundingClientRect().height;
    if (Math.abs(next - last) < 0.5) return;
    const from = animation ? el.getBoundingClientRect().height : last;
    last = next;
    animation?.cancel();
    animation = null;
    if (prefersReducedMotion() || typeof el.animate !== 'function') return;
    const { easing, duration } = springFor(spring);
    el.setAttribute('data-animating', '');
    animation = el.animate([{ height: `${from}px` }, { height: `${next}px` }], { duration, easing });
    const current = animation;
    current.onfinish = () => {
      if (animation !== current) return;
      animation = null;
      el.removeAttribute('data-animating');
    };
    current.oncancel = () => {
      if (animation === current || animation === null) el.removeAttribute('data-animating');
    };
  });
  observer.observe(inner);

  return () => {
    observer.disconnect();
    animation?.cancel();
    el.removeAttribute('data-animating');
  };
}

/* ---- Dynamic island ---------------------------------------------------------------------- */

/** The corner radius for an island of a given height: a pill while short, a rounded card once tall. */
export function islandRadius(height: number, max = 32): number {
  return Math.round(Math.min(height / 2, max));
}

/**
 * Size a `.nx-island` to its active view: reads `data-active` on `el`, finds
 * the direct `.nx-island-view[data-view=…]` child, writes its size as
 * `--_w` / `--_h` / `--_r` (the stylesheet springs width, height and radius
 * toward them) and makes every other view `inert` and hidden from assistive
 * tech while it fades out. Follows `data-active` changes and content resizes.
 */
export function dynamicIsland(el: HTMLElement): Cleanup {
  if (!isBrowser) return () => {};
  const views = () => Array.from(el.children).filter((child): child is HTMLElement => child.classList.contains('nx-island-view'));

  const sync = () => {
    const active = el.dataset.active ?? '';
    const all = views();
    const current = all.find((view) => view.dataset.view === active) ?? all[0];
    for (const view of all) {
      const on = view === current;
      view.toggleAttribute('inert', !on);
      if (on) view.removeAttribute('aria-hidden');
      else view.setAttribute('aria-hidden', 'true');
      view.toggleAttribute('data-current', on);
    }
    if (!current) return;
    const width = current.offsetWidth;
    const height = current.offsetHeight;
    el.style.setProperty('--_w', `${width}px`);
    el.style.setProperty('--_h', `${height}px`);
    el.style.setProperty('--_r', `${islandRadius(height)}px`);
  };

  el.setAttribute('data-instant', '');
  sync();
  const settle = requestAnimationFrame(() => requestAnimationFrame(() => el.removeAttribute('data-instant')));

  const mutations = new MutationObserver(sync);
  mutations.observe(el, { attributes: true, attributeFilter: ['data-active'], childList: true });
  const sizes = 'ResizeObserver' in window ? new ResizeObserver(sync) : null;
  views().forEach((view) => sizes?.observe(view));

  return () => {
    cancelAnimationFrame(settle);
    mutations.disconnect();
    sizes?.disconnect();
  };
}

/* ---- Spotlight tour ----------------------------------------------------------------------- */

export interface TourStepInfo {
  index: number;
  count: number;
  first: boolean;
  last: boolean;
  /** 0–1, counting the current step as done. */
  progress: number;
}

/** Where a tour stands at `index` of `count` steps (the index is clamped). */
export function tourStepInfo(index: number, count: number): TourStepInfo {
  const total = Math.max(0, Math.floor(count));
  const at = total === 0 ? 0 : clamp(Math.floor(index), 0, total - 1);
  return { index: at, count: total, first: at === 0, last: at >= total - 1, progress: total === 0 ? 0 : (at + 1) / total };
}

/** The step after `index` (or null at the end) and before it (or null at the start). */
export function tourNeighbours(index: number, count: number): { next: number | null; back: number | null } {
  const info = tourStepInfo(index, count);
  return { next: info.last ? null : info.index + 1, back: info.first ? null : info.index - 1 };
}

export interface SpotlightBox {
  x: number;
  y: number;
  width: number;
  height: number;
}

/** The cut-out around a target: grown by `padding` and kept inside the viewport. */
export function spotlightRect(rect: { left: number; top: number; width: number; height: number }, padding: number, viewport: { width: number; height: number }): SpotlightBox {
  const x1 = clamp(rect.left - padding, 0, viewport.width);
  const y1 = clamp(rect.top - padding, 0, viewport.height);
  const x2 = clamp(rect.left + rect.width + padding, 0, viewport.width);
  const y2 = clamp(rect.top + rect.height + padding, 0, viewport.height);
  return { x: Math.round(x1), y: Math.round(y1), width: Math.round(Math.max(0, x2 - x1)), height: Math.round(Math.max(0, y2 - y1)) };
}

export interface SpotlightTourOptions {
  /** The current step's target (selector or element). None: the popover centres on a dimmed page. */
  target?: string | Element | null;
  /** Space between the target and the cut-out, px. */
  padding?: number;
  /** Preferred side of the popover. */
  side?: Side;
  onNext?: () => void;
  onBack?: () => void;
  onClose?: () => void;
}

const FOCUSABLE = 'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])';

/**
 * Run one step of a `.nx-tour`: scroll the target into view, move the
 * `.nx-tour-hole` cut-out over it (`--_x/--_y/--_w/--_h`, followed on scroll
 * and resize), place `.nx-tour-pop` beside it and focus the popover. Keys
 * while the tour has focus: Escape closes, the reading-direction arrow goes
 * on, the other goes back, Tab stays inside the popover. Call again for each
 * step; the cleanup undoes this step only (restoring focus is the caller's).
 */
export function spotlightTour(el: HTMLElement, { target, padding = 8, side = 'bottom', onNext, onBack, onClose }: SpotlightTourOptions = {}): Cleanup {
  if (!isBrowser) return () => {};
  const hole = el.querySelector<HTMLElement>('.nx-tour-hole');
  const pop = el.querySelector<HTMLElement>('.nx-tour-pop');
  const subject = resolve(target);
  const stops: Cleanup[] = [];
  let frame = 0;

  const draw = () => {
    frame = 0;
    if (!hole) return;
    if (!subject) {
      el.setAttribute('data-centered', '');
      for (const name of ['--_x', '--_y', '--_w', '--_h']) hole.style.removeProperty(name);
      return;
    }
    el.removeAttribute('data-centered');
    const box = spotlightRect(subject.getBoundingClientRect(), padding, { width: document.documentElement.clientWidth, height: window.innerHeight });
    hole.style.setProperty('--_x', `${box.x}px`);
    hole.style.setProperty('--_y', `${box.y}px`);
    hole.style.setProperty('--_w', `${box.width}px`);
    hole.style.setProperty('--_h', `${box.height}px`);
  };
  const schedule = () => {
    if (!frame) frame = requestAnimationFrame(draw);
  };

  if (subject) {
    const rect = subject.getBoundingClientRect();
    const inView = rect.top >= 0 && rect.bottom <= window.innerHeight && rect.left >= 0 && rect.right <= window.innerWidth;
    if (!inView) subject.scrollIntoView({ block: 'center', inline: 'nearest', behavior: prefersReducedMotion() ? 'auto' : 'smooth' });
    if (pop) {
      pop.removeAttribute('data-centered');
      stops.push(place(subject, pop, { side, align: 'center', offset: padding + 12, padding: 12 }));
    }
  } else if (pop) {
    pop.setAttribute('data-centered', '');
    pop.style.removeProperty('left');
    pop.style.removeProperty('top');
  }
  draw();

  window.addEventListener('scroll', schedule, { capture: true, passive: true });
  window.addEventListener('resize', schedule, { passive: true });

  const key = (event: KeyboardEvent) => {
    const typing = event.target instanceof HTMLElement && event.target.closest('input, textarea, select, [contenteditable="true"]');
    if (event.key === 'Escape') {
      event.preventDefault();
      onClose?.();
      return;
    }
    if (typing) return;
    const forward = direction(el) === 1 ? 'ArrowRight' : 'ArrowLeft';
    const backward = direction(el) === 1 ? 'ArrowLeft' : 'ArrowRight';
    if (event.key === forward) {
      event.preventDefault();
      onNext?.();
    } else if (event.key === backward) {
      event.preventDefault();
      onBack?.();
    } else if (event.key === 'Tab' && pop) {
      const items = Array.from(pop.querySelectorAll<HTMLElement>(FOCUSABLE));
      if (items.length === 0) {
        event.preventDefault();
        return;
      }
      const first = items[0]!;
      const last = items[items.length - 1]!;
      const active = document.activeElement;
      if (event.shiftKey && (active === first || active === pop)) {
        event.preventDefault();
        last.focus();
      } else if (!event.shiftKey && (active === last || !pop.contains(active))) {
        event.preventDefault();
        first.focus();
      }
    }
  };
  el.addEventListener('keydown', key);

  // Focus after placement so the popover does not scroll the page to its old spot.
  const focusFrame = requestAnimationFrame(() => pop?.focus({ preventScroll: true }));

  return () => {
    cancelAnimationFrame(frame);
    cancelAnimationFrame(focusFrame);
    stops.forEach((stop) => stop());
    window.removeEventListener('scroll', schedule, { capture: true });
    window.removeEventListener('resize', schedule);
    el.removeEventListener('keydown', key);
  };
}

/* ---- Image reveal -------------------------------------------------------------------------- */

export interface RevealNoiseOptions {
  /** Size of a noise cell in CSS pixels at progress 0; it shrinks toward a third as progress grows. */
  cell?: number;
  /** Frames per second. */
  fps?: number;
  /** 0–1, read on every frame (a function so the caller can feed live progress). */
  progress?: () => number;
}

/**
 * Animated pixel noise on a canvas, drawn in the canvas' own `color` at
 * random strengths — so it reads in both themes — at a low frame rate, with
 * cells that get finer as `progress` grows. One still frame under reduced
 * motion; paused while off screen.
 */
export function revealNoise(canvas: HTMLCanvasElement, { cell = 14, fps = 12, progress = () => 0 }: RevealNoiseOptions = {}): Cleanup {
  if (!isBrowser) return () => {};
  const ctx = canvas.getContext('2d');
  if (!ctx) return () => {};
  let timer = 0;
  let visible = true;
  let seed = 1;
  const random = () => {
    seed = (seed * 16807) % 2147483647;
    return seed / 2147483647;
  };

  const draw = () => {
    const width = canvas.clientWidth;
    const height = canvas.clientHeight;
    if (width === 0 || height === 0) return;
    const size = Math.max(3, cell * (1 - 0.66 * clamp01(progress())));
    const cols = Math.ceil(width / size);
    const rows = Math.ceil(height / size);
    if (canvas.width !== cols || canvas.height !== rows) {
      canvas.width = cols;
      canvas.height = rows;
    }
    const color = getComputedStyle(canvas).color || 'rgb(128, 128, 128)';
    ctx.clearRect(0, 0, cols, rows);
    ctx.fillStyle = color;
    for (let y = 0; y < rows; y++) {
      for (let x = 0; x < cols; x++) {
        ctx.globalAlpha = random() * 0.55;
        ctx.fillRect(x, y, 1, 1);
      }
    }
    ctx.globalAlpha = 1;
  };

  const loop = () => {
    timer = 0;
    if (!visible) return;
    draw();
    if (!prefersReducedMotion()) timer = window.setTimeout(loop, 1000 / fps);
  };

  const observer =
    'IntersectionObserver' in window
      ? new IntersectionObserver(([entry]) => {
          visible = !!entry?.isIntersecting;
          if (visible && !timer) loop();
        })
      : null;
  observer?.observe(canvas);
  loop();

  return () => {
    window.clearTimeout(timer);
    timer = 0;
    visible = false;
    observer?.disconnect();
  };
}

/**
 * Wire a `.nx-image-reveal`: set `data-loaded` once its `.nx-image-reveal-img`
 * has loaded (or `data-error` when it fails), and run the pixel noise on its
 * `.nx-image-reveal-noise` canvas until the image is revealed — loaded and no
 * longer `data-pending` (the frameworks set that while a progress is below 100).
 * Progress for the noise is read from `data-progress` (0–100).
 */
export function imageReveal(el: HTMLElement): Cleanup {
  if (!isBrowser) return () => {};
  const img = el.querySelector<HTMLImageElement>('.nx-image-reveal-img');
  const canvas = el.querySelector<HTMLCanvasElement>('.nx-image-reveal-noise');
  let stopNoise: Cleanup | null = null;

  const revealed = () => el.hasAttribute('data-loaded') && !el.hasAttribute('data-pending');
  const update = () => {
    if (revealed()) {
      if (stopNoise) {
        // Let the dissolve play out before the canvas freezes.
        const stop = stopNoise;
        stopNoise = null;
        window.setTimeout(stop, 900);
      }
    } else if (!stopNoise && canvas) {
      stopNoise = revealNoise(canvas, { progress: () => Number(el.dataset.progress ?? 0) / 100 });
    }
  };

  const loaded = () => {
    el.removeAttribute('data-error');
    el.setAttribute('data-loaded', '');
  };
  const failed = () => el.setAttribute('data-error', '');

  const watch = (image: HTMLImageElement | null) => {
    if (!image) return;
    image.addEventListener('load', loaded);
    image.addEventListener('error', failed);
    if (image.complete && image.currentSrc) {
      if (image.naturalWidth > 0) loaded();
      else failed();
    }
  };
  watch(img);

  const mutations = new MutationObserver((records) => {
    for (const record of records) {
      if (record.target === img && record.attributeName === 'src') el.removeAttribute('data-loaded');
    }
    update();
  });
  mutations.observe(el, { attributes: true, attributeFilter: ['data-loaded', 'data-pending'] });
  if (img) mutations.observe(img, { attributes: true, attributeFilter: ['src'] });
  update();

  return () => {
    mutations.disconnect();
    img?.removeEventListener('load', loaded);
    img?.removeEventListener('error', failed);
    stopNoise?.();
  };
}
