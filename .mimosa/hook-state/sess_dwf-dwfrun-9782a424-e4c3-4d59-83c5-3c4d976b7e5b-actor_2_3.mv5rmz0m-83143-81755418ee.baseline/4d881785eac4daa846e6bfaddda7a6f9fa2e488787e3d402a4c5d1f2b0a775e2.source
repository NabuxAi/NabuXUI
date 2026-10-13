/**
 * Framework-agnostic behaviour for the scroll blocks: one tall "stage" whose
 * own scroll run drives a scene pinned inside it.
 *
 *   scrollStage   writes the stage's progress (--nx-stage, 0–1) and, per mode,
 *                 the parts' scroll-driven values:
 *                 sticky      every .nx-scroll-card's --nx-buried — how far the
 *                             next card has travelled over this one
 *                 horizontal  --nx-shift — the px the .nx-scroll-track overhangs
 *                             its .nx-scroll-lane; CSS slides it with progress
 *                 zoom        every .nx-scroll-stop's --nx-gate — how open the
 *                             station stands at this progress
 *
 * One rAF-throttled pass on scroll, resize and content resize, measured
 * against the stage's own position (the effects/scroll-progress pattern), and
 * fully cleaned up. The pure helpers are exported for tests and frameworks.
 */
import { type Cleanup, isBrowser } from '../env';

const clamp01 = (value: number) => Math.min(1, Math.max(0, value));
const round3 = (value: number) => Math.round(value * 1000) / 1000;

/**
 * How far through an element's own scroll run the reader is: 0 while its top
 * is still below the top of the viewport, 1 once its bottom has reached the
 * viewport's bottom. `top` is measured against the viewport (or the scrolling
 * box), like getBoundingClientRect().top already is.
 */
export function stageProgress(top: number, height: number, viewport: number): number {
  const span = Math.max(height - viewport, 1);
  return clamp01(-top / span);
}

/**
 * How far the card after this one has travelled over it: 0 while the next card
 * is still entering at the viewport's bottom, 1 once it has landed on this
 * card's own sticky row. `top` / `nextTop` are viewport-relative tops.
 */
export function burialProgress(top: number, nextTop: number, viewport: number): number {
  return clamp01((viewport - nextTop) / Math.max(viewport - top, 1));
}

/**
 * Where each station of a `count`-stop roadmap starts opening: the first is
 * open from the very start (its gate sits just below zero), the last starts at
 * `reach` so there is runway left for it to finish before the stage ends.
 */
export function gateStarts(count: number, reach = 0.86): number[] {
  const total = Math.max(1, Math.floor(count));
  return Array.from({ length: total }, (_, i) => (i === 0 ? -0.09 : (i / (total - 1)) * reach));
}

/** How open a station is: 0–1 over `span` of progress after `start`. */
export function gateOpen(progress: number, start: number, span = 0.09): number {
  return clamp01((progress - start) / Math.max(span, 0.001));
}

export type ScrollStageMode = 'sticky' | 'horizontal' | 'zoom';

export interface ScrollStageOptions {
  /** Which parts of the stage are scroll-driven (default sticky). */
  mode?: ScrollStageMode;
  /** The scrolling box that drives the stage (selector or element); the page when absent. */
  container?: string | Element | null;
  /** Called with the stage's progress (0–1) whenever it moves. */
  onProgress?: (progress: number) => void;
}

/** Write a custom property only when its value really changed, so styles are not invalidated mid-scroll. */
function setVar(el: HTMLElement, name: string, value: string): void {
  if (el.style.getPropertyValue(name) === value) return;
  el.style.setProperty(name, value);
}

/**
 * Drive a `.nx-scroll-stage`: one scroll pass writes `--nx-stage` (0–1) on the
 * stage and the mode's part values (`--nx-buried`, `--nx-shift`, `--nx-gate`)
 * wherever they are needed. Sticky cards are indexed (`--nx-i`) when the
 * markup has not done it, so the CSS can stagger the row each one sticks to.
 * Works for any number of stages on a page; each runs its own copy.
 */
export function scrollStage(el: HTMLElement, { mode = 'sticky', container, onProgress }: ScrollStageOptions = {}): Cleanup {
  if (!isBrowser) return () => {};

  const box = typeof container === 'string' ? document.querySelector<HTMLElement>(container) : (container ?? null);
  const viewport = () => (box ? { top: box.getBoundingClientRect().top, height: box.clientHeight } : { top: 0, height: window.innerHeight });

  /** Cards we indexed ourselves (the markup may have set its own --nx-i; those stay). */
  const indexed = new Set<HTMLElement>();
  let last = -1;
  let frame = 0;

  const measure = () => {
    frame = 0;
    const view = viewport();
    const rect = el.getBoundingClientRect();
    const progress = round3(stageProgress(rect.top - view.top, rect.height, view.height));

    if (progress !== last) {
      last = progress;
      setVar(el, '--nx-stage', String(progress));
      el.toggleAttribute('data-started', progress > 0.005);
      el.toggleAttribute('data-complete', progress >= 0.999);
      onProgress?.(progress);
    }

    if (mode === 'sticky') {
      const cards = Array.from(el.querySelectorAll<HTMLElement>('.nx-scroll-card'));
      const tops = cards.map((card) => card.getBoundingClientRect().top);
      cards.forEach((card, i) => {
        if (!card.style.getPropertyValue('--nx-i')) {
          card.style.setProperty('--nx-i', String(i));
          indexed.add(card);
        }
        const next = tops[i + 1];
        setVar(card, '--nx-buried', next === undefined ? '0' : String(round3(burialProgress(tops[i]!, next, view.height))));
      });
    }

    if (mode === 'horizontal') {
      const lane = el.querySelector<HTMLElement>('.nx-scroll-lane');
      const track = el.querySelector<HTMLElement>('.nx-scroll-track');
      if (lane && track) setVar(el, '--nx-shift', `${Math.max(track.scrollWidth - lane.clientWidth, 0)}px`);
    }

    if (mode === 'zoom') {
      const stops = Array.from(el.querySelectorAll<HTMLElement>('.nx-scroll-stop'));
      const starts = gateStarts(stops.length);
      stops.forEach((stop, i) => setVar(stop, '--nx-gate', String(round3(gateOpen(progress, starts[i]!)))));
    }
  };

  const schedule = () => {
    if (!frame) frame = requestAnimationFrame(measure);
  };

  const scroller: EventTarget = box ?? document;
  scroller.addEventListener('scroll', schedule, { passive: true, capture: !box });
  window.addEventListener('resize', schedule, { passive: true });
  const observer = 'ResizeObserver' in window ? new ResizeObserver(schedule) : null;
  observer?.observe(el);
  if (mode === 'horizontal') {
    const lane = el.querySelector('.nx-scroll-lane');
    const track = el.querySelector('.nx-scroll-track');
    if (lane) observer?.observe(lane);
    if (track) observer?.observe(track);
  }
  measure();

  return () => {
    cancelAnimationFrame(frame);
    scroller.removeEventListener('scroll', schedule, { capture: !box } as EventListenerOptions);
    window.removeEventListener('resize', schedule);
    observer?.disconnect();
    el.style.removeProperty('--nx-stage');
    el.removeAttribute('data-started');
    el.removeAttribute('data-complete');
    el.style.removeProperty('--nx-shift');
    Array.from(el.querySelectorAll<HTMLElement>('.nx-scroll-card')).forEach((card) => card.style.removeProperty('--nx-buried'));
    Array.from(el.querySelectorAll<HTMLElement>('.nx-scroll-stop')).forEach((stop) => stop.style.removeProperty('--nx-gate'));
    indexed.forEach((card) => {
      card.style.removeProperty('--nx-i');
      indexed.delete(card);
    });
  };
}
