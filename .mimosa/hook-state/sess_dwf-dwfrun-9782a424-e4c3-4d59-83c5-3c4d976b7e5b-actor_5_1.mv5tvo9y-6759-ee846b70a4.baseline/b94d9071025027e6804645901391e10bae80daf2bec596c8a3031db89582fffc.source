/**
 * Framework-agnostic behaviours for the kinetic-type blocks: text that answers
 * how fast it is being read.
 *
 *   scrollVelocity   the throttle everything else rides on: scroll deltas kick
 *                    a velocity, one rAF loop decays it back to rest and
 *                    reports px/frame while awake — parked when settled, off
 *                    screen, and under reduced motion
 *   velocityBands    marquee bands driven by that velocity: one runs with the
 *                    flick and one against it, a pause lets the damping pull
 *                    them back to a calm drift, and a modulo on half the track
 *                    keeps the wrap seamless
 *   wordFillScroll   a reading pane whose words each carry a --lit share of
 *                    the pane's own scroll progress (the scrollProgress
 *                    behaviour in reuse), so colour climbs the paragraph
 *                    exactly as fast as the reader
 *   elasticLetters   every unit of a wordmark is a small damped spring (the
 *                    cards' elasticStep integrator in reuse): hover pulls it
 *                    into a stretched shape, release wobbles it back
 *
 * The motion itself lives in css/blocks/kinetic.css; these measure, write
 * custom properties and attributes, and clean up after themselves.
 */
import { type Cleanup, canHover, isBrowser, onReducedMotionChange, prefersReducedMotion } from '../env';
import { type SpringConfig } from '../spring';
import { type ElasticState, elasticStep } from './cards';
import { scrollProgress } from './effects';

/** Velocity below this many px/frame counts as rest, so the loops can park. */
const REST = 0.05;

const clamp01 = (value: number) => Math.min(1, Math.max(0, value));

/** Write a custom property only when its value really changed, so styles are not invalidated mid-scroll. */
function setVar(el: HTMLElement, name: string, value: string): void {
  if (el.style.getPropertyValue(name) === value) return;
  el.style.setProperty(name, value);
}

/* ---- Scroll velocity ------------------------------------------------------------------- */

export interface ScrollVelocityOptions {
  /** The scrolling box whose scroll is measured (selector or element); the page when absent. */
  container?: string | Element | null;
  /** How much of each scroll delta lands on the velocity (default 0.35). */
  gain?: number;
  /** How much of the velocity survives each frame while it settles (default 0.92). */
  decay?: number;
  /** Called every frame while awake — and once per scroll under reduced motion: px per frame, negative = scrolled up. */
  onVelocity?: (velocity: number) => void;
}

/**
 * Measure how fast a scrolling box is being scrolled, smoothed: each scroll
 * delta kicks the velocity by `gain`, a rAF loop decays it back to rest and
 * reports it (px per frame, negative = scrolled up) every frame it is awake.
 * The loop parks itself when the velocity settles, while `el` is off screen,
 * and under reduced motion — where the jolt itself is still reported, once per
 * scroll event, because a measurement is not motion.
 */
export function scrollVelocity(el: HTMLElement, options: ScrollVelocityOptions | ((velocity: number) => void) = {}): Cleanup {
  if (!isBrowser) return () => {};
  const { container, gain = 0.35, decay = 0.92, onVelocity } = typeof options === 'function' ? { onVelocity: options } : options;
  const factor = Math.min(0.985, Math.max(0.8, decay));

  const box = typeof container === 'string' ? document.querySelector<HTMLElement>(container) : (container ?? null);
  const scroller = () => box ?? (document.scrollingElement ?? document.documentElement);

  let velocity = 0;
  let last = NaN;
  let frame = 0;
  let visible = true;

  const report = () => onVelocity?.(Math.round(velocity * 100) / 100);

  const settle = () => {
    velocity *= factor;
    if (Math.abs(velocity) < REST) {
      velocity = 0;
      frame = 0;
    } else {
      frame = requestAnimationFrame(settle);
    }
    report();
  };

  const wake = () => {
    if (!frame && visible && !prefersReducedMotion()) frame = requestAnimationFrame(settle);
  };

  const onScroll = () => {
    const y = scroller().scrollTop;
    const delta = Number.isNaN(last) ? 0 : y - last;
    last = y;
    if (delta === 0) return;
    velocity += delta * gain;
    if (prefersReducedMotion()) {
      report();
      velocity = 0;
      return;
    }
    wake();
  };

  const scrollerEvents: EventTarget = box ?? document;
  scrollerEvents.addEventListener('scroll', onScroll, { passive: true, capture: !box });
  const observer =
    'IntersectionObserver' in window
      ? new IntersectionObserver(
          ([entry]) => {
            visible = !!entry?.isIntersecting;
            if (visible) wake();
            else if (frame) {
              cancelAnimationFrame(frame);
              frame = 0;
            }
          },
          { rootMargin: '64px' },
        )
      : null;
  observer?.observe(el);

  return () => {
    if (frame) cancelAnimationFrame(frame);
    frame = 0;
    scrollerEvents.removeEventListener('scroll', onScroll, { capture: !box } as EventListenerOptions);
    observer?.disconnect();
  };
}

/* ---- Velocity bands -------------------------------------------------------------------- */

export interface VelocityBandsOptions {
  /** The pane whose scroll drives the bands (selector relative to `el`, default '.nx-velocity-pane'). */
  pane?: string;
  /** The tracks to drive (default '.nx-velocity-track'). */
  selector?: string;
  /** Base drift, px per frame in each band's own direction while the reader rests (default 0.6). */
  drift?: number;
  /** How much of each scroll delta lands on the bands (default 0.35). */
  gain?: number;
  /** How much of the scroll kick survives each frame (default 0.92). */
  decay?: number;
  /** Called with the lead band's speed, px/frame, about eight times a second — for readouts. */
  onSpeed?: (speed: number) => void;
}

/**
 * Drive the `.nx-velocity-track` bands of a `.nx-velocity` block from the
 * scroll of its `.nx-velocity-pane`: `scrollVelocity` measures the flick, and
 * every band runs at its own calm `drift` plus that kick — with it or against
 * it, per `data-reverse`. A modulo on half the track keeps the wrap seamless
 * (the drift is physical pixels, so it reads the same in RTL); `data-reversing`
 * lands on `el` while the reader scrolls up, and `onSpeed` feeds a readout.
 * The loop only ever parks off screen and under reduced motion — a marquee
 * with no drift to return to never settles.
 */
export function velocityBands(el: HTMLElement, { pane = '.nx-velocity-pane', selector = '.nx-velocity-track', drift = 0.6, gain = 0.35, decay = 0.92, onSpeed }: VelocityBandsOptions = {}): Cleanup {
  if (!isBrowser) return () => {};
  const tracks = Array.from(el.querySelectorAll<HTMLElement>(selector));
  const source = el.querySelector<HTMLElement>(pane);
  if (tracks.length === 0 || !source) return () => {};

  const positions = tracks.map(() => 0);
  let drive = 0;
  let frame = 0;
  let visible = true;
  let reportAt = 0;

  const place = () => {
    const now = performance.now();
    let speed = 0;
    tracks.forEach((track, i) => {
      const dir = track.hasAttribute('data-reverse') ? -1 : 1;
      const velocity = dir * drift + drive;
      if (i === 0) speed = velocity;
      const half = track.scrollWidth / 2;
      positions[i] = half > 1 ? (((positions[i] + velocity) % half + half) % half) : 0;
      track.style.translate = `${(-positions[i]).toFixed(1)}px 0`;
    });
    el.toggleAttribute('data-reversing', drive < -REST);
    if (onSpeed && now - reportAt > 120) {
      reportAt = now;
      onSpeed(Math.round(speed * 10) / 10);
    }
  };

  const loop = () => {
    place();
    if (drift === 0 && Math.abs(drive) < REST) {
      frame = 0;
      return;
    }
    frame = requestAnimationFrame(loop);
  };

  const run = () => {
    if (frame || !visible || prefersReducedMotion()) return;
    frame = requestAnimationFrame(loop);
  };

  const stop = scrollVelocity(source, { gain, decay, onVelocity: (value) => (drive = value) });

  const observer =
    'IntersectionObserver' in window
      ? new IntersectionObserver(
          ([entry]) => {
            visible = !!entry?.isIntersecting;
            if (visible) run();
            else if (frame) {
              cancelAnimationFrame(frame);
              frame = 0;
            }
          },
          { rootMargin: '64px' },
        )
      : null;
  observer?.observe(el);

  const stopMotion = onReducedMotionChange((reduce) => {
    if (reduce) {
      if (frame) {
        cancelAnimationFrame(frame);
        frame = 0;
      }
      tracks.forEach((track) => track.style.removeProperty('translate'));
    } else {
      run();
    }
  });

  run();

  return () => {
    if (frame) cancelAnimationFrame(frame);
    frame = 0;
    stop();
    observer?.disconnect();
    stopMotion();
    tracks.forEach((track) => track.style.removeProperty('translate'));
    el.removeAttribute('data-reversing');
  };
}

/* ---- Word fill ------------------------------------------------------------------------- */

export interface WordFillScrollOptions {
  /** The scrolling pane that reads the text (selector relative to `el`, default '.nx-wordfill-pane'). */
  pane?: string;
  /** The words that light up (default '.nx-wordfill-word'). */
  selector?: string;
  /** Called with the pane's reading progress (0–1) whenever it moves. */
  onProgress?: (progress: number) => void;
}

/**
 * Light the words of a `.nx-wordfill` reading pane as it scrolls. The pane's
 * own progress (the shared `scrollProgress`, writing `--nx-scroll` 0–1 and the
 * started/complete states on `el`) is shared out over the words, each carrying
 * its `--lit` share, so colour climbs the paragraph exactly as fast as the
 * reader. One rAF-throttled pass per scroll; under reduced motion the
 * stylesheet drops the word transitions and the lighting stays strictly
 * position-mapped.
 */
export function wordFillScroll(el: HTMLElement, { pane = '.nx-wordfill-pane', selector = '.nx-wordfill-word', onProgress }: WordFillScrollOptions = {}): Cleanup {
  if (!isBrowser) return () => {};
  const source = el.querySelector<HTMLElement>(pane);
  if (!source) return () => {};

  const words = () => Array.from(el.querySelectorAll<HTMLElement>(selector));
  const share = (progress: number) => {
    const spans = words();
    spans.forEach((word, i) => setVar(word, '--lit', clamp01(progress * spans.length - i).toFixed(3)));
    onProgress?.(progress);
  };

  const stop = scrollProgress(el, { container: source, onProgress: share });
  return () => {
    stop();
    el.style.removeProperty('--nx-scroll');
    el.removeAttribute('data-started');
    el.removeAttribute('data-complete');
    words().forEach((word) => word.style.removeProperty('--lit'));
  };
}

/* ---- Elastic letters -------------------------------------------------------------------- */

export interface ElasticLettersOptions {
  /** The pieces that stretch (default '[data-nx-unit]'). */
  selector?: string;
  /** Spring stiffness (default 170). */
  stiffness?: number;
  /** Spring damping — lower wobbles longer (default 10). */
  damping?: number;
  /** The horizontal pull at full stretch (default 0.5 → scaleX 1.5). */
  stretch?: number;
  /** The vertical squeeze that pays for it (default 0.24 → scaleY 0.76). */
  squeeze?: number;
}

/**
 * Give every unit of a `.nx-elastic` wordmark a damped spring: hovering pulls
 * it into a stretched shape (`scale` x/y) and releasing lets the integrator —
 * the cards' `elasticStep`, in reuse — wobble it back onto its baseline. Latin
 * units are letters, joining scripts whole words; the splitting is the
 * caller's (`splitText` already refuses to break joins). The loop parks once
 * every spring has settled, sleeps off screen, and under reduced motion the
 * stretch lands instantly with no shimmy. Fine pointers only — on touch the
 * wordmark simply sits still.
 */
export function elasticLetters(el: HTMLElement, { selector = '[data-nx-unit]', stiffness = 170, damping = 10, stretch = 0.5, squeeze = 0.24 }: ElasticLettersOptions = {}): Cleanup {
  if (!isBrowser || !canHover()) return () => {};

  const config: SpringConfig = { stiffness, damping };
  const shape = (energy: number) => `${(1 + stretch * energy).toFixed(3)} ${(1 - squeeze * energy).toFixed(3)}`;
  const reset = (unit: { state: ElasticState; over: boolean }) => {
    unit.state = { value: 0, velocity: 0 };
    unit.over = false;
  };

  let frame = 0;
  let last = 0;
  let visible = true;

  const tick = (now: number) => {
    const dt = Math.min(0.064, Math.max(0.001, (now - last) / 1000));
    last = now;
    let live = false;
    for (const { unit } of units) {
      const target = unit.over ? 1 : 0;
      unit.state = elasticStep(unit.state, target, dt, config);
      if (Math.abs(unit.state.velocity) < 0.002 && Math.abs(target - unit.state.value) < 0.002) {
        unit.state = { value: target, velocity: 0 };
      } else {
        live = true;
      }
      if (unit.state.value === 0) unit.target.style.removeProperty('scale');
      else unit.target.style.scale = shape(unit.state.value);
    }
    frame = live ? requestAnimationFrame(tick) : 0;
  };

  const run = () => {
    if (frame || !visible || prefersReducedMotion()) return;
    last = performance.now();
    frame = requestAnimationFrame(tick);
  };

  const units = Array.from(el.querySelectorAll<HTMLElement>(selector)).map((target) => {
    const unit = { target, state: { value: 0, velocity: 0 } as ElasticState, over: false };
    const enter = () => {
      if (prefersReducedMotion()) {
        target.style.scale = shape(1);
        return;
      }
      unit.over = true;
      run();
    };
    const leave = () => {
      if (prefersReducedMotion()) {
        target.style.removeProperty('scale');
        return;
      }
      unit.over = false;
      run();
    };
    return { target, unit, enter, leave };
  });

  for (const { target, enter, leave } of units) {
    target.addEventListener('pointerenter', enter);
    target.addEventListener('pointerleave', leave);
  }

  const observer =
    'IntersectionObserver' in window
      ? new IntersectionObserver(
          ([entry]) => {
            visible = !!entry?.isIntersecting;
            if (visible) run();
            else if (frame) {
              cancelAnimationFrame(frame);
              frame = 0;
            }
          },
          { rootMargin: '64px' },
        )
      : null;
  observer?.observe(el);

  const stopMotion = onReducedMotionChange((reduce) => {
    if (!reduce) return;
    if (frame) {
      cancelAnimationFrame(frame);
      frame = 0;
    }
    for (const { target, unit } of units) {
      reset(unit);
      target.style.removeProperty('scale');
    }
  });

  return () => {
    if (frame) cancelAnimationFrame(frame);
    frame = 0;
    observer?.disconnect();
    stopMotion();
    for (const { target, enter, leave, unit } of units) {
      target.removeEventListener('pointerenter', enter);
      target.removeEventListener('pointerleave', leave);
      reset(unit);
      target.style.removeProperty('scale');
    }
  };
}
