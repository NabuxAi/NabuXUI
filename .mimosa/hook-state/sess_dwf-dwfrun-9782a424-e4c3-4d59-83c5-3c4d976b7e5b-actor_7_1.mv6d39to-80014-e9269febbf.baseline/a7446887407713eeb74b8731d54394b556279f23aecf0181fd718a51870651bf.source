/**
 * Framework-agnostic behaviours for the ambient background blocks.
 *
 *   starfield      one canvas, one projection: every star owns a z, radius and
 *                  brightness fall out of 1/z, the pointer eases the vanishing
 *                  point and a `speed` function can boost the approach for a
 *                  beat (the warp). The loop parks off screen, reduced motion
 *                  gets one still sky, and the sky/star colours are read from
 *                  the stage's own tokens so both themes render.
 *   contourLines   eight contour bands, each a mix of two sines with its own
 *                  wavelength and speed, redrawn on one SVG ~30 times a
 *                  second; `[data-contour-peak]` summits ride their bands and
 *                  hover/focus retunes the peak's band to its colour.
 *   ambientStage   parks the CSS loops of a stage (the aurora blobs, the grid
 *                  stream, the goo wander) while it sits outside the viewport,
 *                  by toggling the same `data-paused` the stylesheet reads.
 *
 * The look lives in css/blocks/ambient.css; these only measure, draw and
 * clean up after themselves.
 */
import { type Cleanup, isBrowser, onReducedMotionChange, prefersReducedMotion } from '../env';

/* ---- Starfield --------------------------------------------------------------------------- */

export interface StarfieldOptions {
  /** Star count at a 900×500 stage; scales with the stage's area (default 180). */
  density?: number;
  /** Approach multiplier, re-read every frame so a warp can boost it live (default 0.9). */
  speed?: number | (() => number);
  /** How far the pointer eases the vanishing point, px (default 26). */
  parallax?: number;
  /** Called with the star count after every reseed. */
  onCount?: (count: number) => void;
}

/** An `rgb()` / `rgba()` string to 0–1 channels (percent forms included). */
function parseColor(text: string): [number, number, number] {
  const raw = text.match(/-?[\d.]+%?/g);
  if (!raw || raw.length < 3) return [0, 0, 0];
  const percent = text.includes('%');
  const channel = (part: string): number => {
    const n = Number.parseFloat(part);
    if (!Number.isFinite(n)) return 0;
    const v = percent ? n / 100 : n > 1 ? n / 255 : n;
    return Math.min(1, Math.max(0, v));
  };
  return [channel(raw[0]!), channel(raw[1]!), channel(raw[2]!)];
}

interface Star {
  x: number;
  y: number;
  z: number;
}

/**
 * Wire a `.nx-starfield` stage: creates the canvas when the caller did not
 * render one, seeds a depth-scaled star field, streams the stars toward the
 * viewer on a RAF loop that parks while the stage is off screen and freezes to
 * one still sky under reduced motion. The pointer eases the vanishing point by
 * `parallax` px (skipped under reduced motion); `speed` as a function is
 * re-read every frame so a warp button can triple the approach without
 * rebuilding anything. Sky and star colours come from the stage's `--_sky` /
 * `--_star` tokens and are re-resolved when the theme flips. Cleans up
 * everything it touched.
 */
export function starfield(el: HTMLElement, { density = 180, speed = 0.9, parallax = 26, onCount }: StarfieldOptions = {}): Cleanup {
  if (!isBrowser) return () => {};

  const foundCanvas = el.querySelector<HTMLCanvasElement>('canvas.nx-starfield-canvas');
  const canvas = foundCanvas ?? document.createElement('canvas');
  const madeCanvas = !foundCanvas;
  if (madeCanvas) {
    canvas.className = 'nx-starfield-canvas';
    canvas.setAttribute('aria-hidden', 'true');
    el.prepend(canvas);
  }
  const ctx = canvas.getContext('2d');
  const dpr = Math.min(window.devicePixelRatio || 1, 2);
  const speedNow = (): number => (typeof speed === 'function' ? speed() : speed);

  let w = 0;
  let h = 0;
  let stars: Star[] = [];
  let raf = 0;
  let last = 0;
  let visible = true;
  let sky: [number, number, number] = [0.04, 0.03, 0.06];
  let star: [number, number, number] = [0.93, 0.94, 1];
  let probe: HTMLSpanElement | null = null;

  /* Colours: resolved through a probe inside the stage, so the token cascade
     (and any color-mix) lands as the RGB the current theme actually shows. */
  const cssColor = (value: string, fallback: [number, number, number]): [number, number, number] => {
    if (!value) return fallback;
    if (!probe) {
      probe = document.createElement('span');
      probe.className = 'nx-ambient-probe';
      probe.setAttribute('aria-hidden', 'true');
      el.appendChild(probe);
    }
    probe.style.color = '';
    probe.style.color = value;
    const text = getComputedStyle(probe).color;
    return text && text !== 'none' ? parseColor(text) : fallback;
  };
  const readColors = (): void => {
    const style = getComputedStyle(el);
    sky = cssColor(style.getPropertyValue('--_sky').trim(), sky);
    star = cssColor(style.getPropertyValue('--_star').trim(), star);
  };

  const seed = (): void => {
    w = el.clientWidth;
    h = el.clientHeight;
    canvas.width = Math.max(2, Math.round(w * dpr));
    canvas.height = Math.max(2, Math.round(h * dpr));
    ctx?.setTransform(dpr, 0, 0, dpr, 0, 0);
    const n = Math.max(24, Math.round(density * Math.min(1, (w * h) / (900 * 500))));
    stars = Array.from({ length: n }, () => ({
      x: (Math.random() - 0.5) * w * 2,
      y: (Math.random() - 0.5) * h * 2,
      z: Math.random() * Math.max(1, w),
    }));
    onCount?.(n);
  };

  /* The eased vanishing point; pointer-measured, so physical pixels are right. */
  let px = 0;
  let py = 0;
  let tx = 0;
  let ty = 0;

  const draw = (now: number): void => {
    if (!ctx || w === 0 || h === 0) return;
    const dt = Math.min((now - last) / 16.6, 3);
    last = now;
    px += (tx - px) * 0.06 * dt;
    py += (ty - py) * 0.06 * dt;
    ctx.fillStyle = `rgb(${(sky[0] * 255).toFixed(0)} ${(sky[1] * 255).toFixed(0)} ${(sky[2] * 255).toFixed(0)})`;
    ctx.fillRect(0, 0, w, h);
    const cx = w / 2 + px;
    const cy = h / 2 + py;
    const pace = speedNow() * dt;
    for (const s of stars) {
      s.z -= pace;
      if (s.z <= 1) {
        s.z = Math.max(1, w);
        s.x = (Math.random() - 0.5) * w * 2;
        s.y = (Math.random() - 0.5) * h * 2;
      }
      const k = 0.5 / s.z;
      const sx = cx + s.x * k * w;
      const sy = cy + s.y * k * h;
      if (sx < -8 || sx > w + 8 || sy < -8 || sy > h + 8) continue;
      const depth = 1 - s.z / Math.max(1, w);
      const r = Math.max(0.4, depth * 2.2);
      const a = Math.min(1, depth * 1.4 + 0.12);
      ctx.beginPath();
      ctx.arc(sx, sy, r, 0, 6.2832);
      ctx.fillStyle = `rgba(${(star[0] * 255).toFixed(0)},${(star[1] * 255).toFixed(0)},${(star[2] * 255).toFixed(0)},${a.toFixed(3)})`;
      ctx.fill();
    }
  };

  const loop = (now: number): void => {
    draw(now);
    raf = requestAnimationFrame(loop);
  };

  const run = (): void => {
    window.cancelAnimationFrame(raf);
    raf = 0;
    if (!ctx || !visible || w === 0 || h === 0) return;
    if (prefersReducedMotion()) {
      // One still sky: a frame with no drift, redrawn only when reseeded.
      px = tx = 0;
      py = ty = 0;
      last = performance.now();
      draw(last);
      return;
    }
    last = performance.now();
    raf = requestAnimationFrame(loop);
  };

  const sizes = 'ResizeObserver' in window ? new ResizeObserver(() => { seed(); run(); }) : null;
  sizes?.observe(el);
  const observer =
    'IntersectionObserver' in window
      ? new IntersectionObserver(([entry]) => {
          visible = !!entry?.isIntersecting;
          run();
        }, { rootMargin: '64px' })
      : null;
  observer?.observe(el);
  const stopMotion = onReducedMotionChange(run);

  /** Any theme flip (data-theme, dark/light classes, scheme change) re-resolves the sky. */
  const themeChanged = (): void => {
    readColors();
    if (!raf && visible) run();
  };
  const themes = new MutationObserver(themeChanged);
  themes.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme', 'class'] });
  const scheme = window.matchMedia('(prefers-color-scheme: dark)');
  scheme.addEventListener('change', themeChanged);

  const move = (event: PointerEvent): void => {
    if (prefersReducedMotion()) return;
    const rect = el.getBoundingClientRect();
    if (rect.width === 0 || rect.height === 0) return;
    tx = ((event.clientX - rect.left) / rect.width - 0.5) * parallax;
    ty = ((event.clientY - rect.top) / rect.height - 0.5) * parallax;
  };
  const calm = (): void => {
    tx = 0;
    ty = 0;
  };
  el.addEventListener('pointermove', move);
  el.addEventListener('pointerleave', calm);

  readColors();
  seed();
  run();

  return () => {
    window.cancelAnimationFrame(raf);
    sizes?.disconnect();
    observer?.disconnect();
    themes.disconnect();
    scheme.removeEventListener('change', themeChanged);
    stopMotion();
    el.removeEventListener('pointermove', move);
    el.removeEventListener('pointerleave', calm);
    probe?.remove();
    if (madeCanvas) canvas.remove();
  };
}

/* ---- Contour lines ----------------------------------------------------------------------- */

export interface ContourOptions {
  /** How many contour bands the survey draws (default 8). */
  bands?: number;
  /** Time multiplier, re-read every frame so a slider retunes the drift live (default 1). */
  speed?: number | (() => number);
  /** Called when the hot peak changes: its `data-contour-peak` value, or null when none. */
  onPeakChange?: (id: string | null) => void;
}

/** The viewBox the contour maths lives in; the SVG slices it to the stage. */
export const CONTOUR_SPACE = { width: 1200, height: 480, x0: -60, x1: 1260, step: 24 } as const;

interface Band {
  y: number;
  amp: number;
  k1: number;
  k2: number;
  s1: number;
  s2: number;
  o2: number;
}

/** The band parameters are pure functions of the index, so every framework draws the same map. */
function bandAt(index: number): Band {
  const i = Math.max(0, Math.min(9, index));
  return {
    y: 405 - i * 32,
    amp: 26 - i * 2.2,
    k1: (2 * Math.PI) / (300 - i * 12),
    k2: (2 * Math.PI) / (96 + i * 7),
    s1: 0.0011 + i * 0.00007,
    s2: 0.0019 - i * 0.00006,
    o2: i * 1.7,
  };
}

function bandY(band: Band, x: number, t: number): number {
  return band.y + band.amp * Math.sin(band.k1 * x + t * band.s1) + band.amp * 0.35 * Math.sin(band.k2 * x + t * band.s2 + band.o2);
}

/**
 * Wire a `.nx-contour` stage: fills the `.nx-contour-line` paths of its SVG
 * (creating them when the caller rendered a bare `<g>`), drifts and phases the
 * bands on a ~30fps RAF loop that parks off screen and settles to one still
 * map under reduced motion. Every `[data-contour-peak]` child (with `data-band`
 * and `data-x`, in viewBox units) rides its band, and hovering or focusing it
 * marks its band — and the one above — with `data-hot` while `--nx-contour-hot`
 * on the stage takes the peak's `data-colour`. Cleans up everything it touched.
 */
export function contourLines(el: HTMLElement, { bands = 8, speed = 1, onPeakChange }: ContourOptions = {}): Cleanup {
  if (!isBrowser) return () => {};
  const svg = el.querySelector<SVGSVGElement>('.nx-contour-svg');
  const group = el.querySelector<SVGGElement>('.nx-contour-lines') ?? svg;
  if (!group) return () => {};

  const count = Math.max(2, Math.min(9, bands));
  const speedNow = (): number => (typeof speed === 'function' ? speed() : speed);

  let made: SVGPathElement[] = [];
  const paths: SVGPathElement[] = [];
  const existing = Array.from(group.children).filter((child): child is SVGPathElement => child instanceof SVGPathElement);
  for (let i = 0; i < count; i++) {
    const path = existing[i] ?? document.createElementNS('http://www.w3.org/2000/svg', 'path');
    if (!existing[i]) {
      path.setAttribute('class', 'nx-contour-line');
      made.push(path);
      group.appendChild(path);
    }
    path.setAttribute('stroke-width', (1 + i * 0.35).toFixed(2));
    path.setAttribute('stroke-opacity', (0.35 + i * 0.09).toFixed(2));
    paths.push(path);
  }

  const peakEls = (): SVGElement[] => Array.from(el.querySelectorAll<SVGElement>('[data-contour-peak]'));
  let peaks = peakEls();
  const mutations = new MutationObserver(() => {
    peaks = peakEls();
  });
  mutations.observe(el, { childList: true, subtree: true });

  const table = Array.from({ length: count }, (_, i) => bandAt(i));

  const draw = (t: number): void => {
    for (let i = 0; i < paths.length; i++) {
      const band = table[i]!;
      let d = '';
      for (let x = CONTOUR_SPACE.x0; x <= CONTOUR_SPACE.x1; x += CONTOUR_SPACE.step) {
        d += (x === CONTOUR_SPACE.x0 ? 'M' : 'L') + x.toFixed(0) + ' ' + bandY(band, x, t).toFixed(1);
      }
      paths[i]!.setAttribute('d', d);
    }
    for (const peak of peaks) {
      const band = table[Math.max(0, Math.min(count - 1, Number(peak.getAttribute('data-band')) || 0))];
      const x = Number(peak.getAttribute('data-x')) || 0;
      peak.setAttribute('transform', `translate(${x.toFixed(0)} ${bandY(band, x, t).toFixed(1)})`);
    }
  };

  let raf = 0;
  let t = 0;
  let last = 0;
  let tick = 0;
  let visible = true;

  const loop = (now: number): void => {
    const dt = Math.min(now - last, 48);
    last = now;
    t += dt * speedNow();
    // Every other frame: ~30fps is more than a survey map needs.
    if ((tick = (tick + 1) % 2) === 0) draw(t);
    raf = requestAnimationFrame(loop);
  };

  const run = (): void => {
    window.cancelAnimationFrame(raf);
    raf = 0;
    if (!visible) return;
    if (prefersReducedMotion()) {
      draw(t);
      return;
    }
    last = performance.now();
    raf = requestAnimationFrame(loop);
  };

  const observer =
    'IntersectionObserver' in window
      ? new IntersectionObserver(([entry]) => {
          visible = !!entry?.isIntersecting;
          run();
        }, { rootMargin: '64px' })
      : null;
  observer?.observe(el);
  const stopMotion = onReducedMotionChange(run);

  /* Hover, and keyboard focus through focusin/focusout, retune the peak's band.
     Every pointerover states the current truth — over a summit or over the map —
     so leaving a dot clears as reliably as entering one sets. */
  let hot: string | null = null;
  const setHot = (id: string | null): void => {
    if (id === hot) return;
    hot = id;
    for (const path of paths) path.removeAttribute('data-hot');
    for (const peak of peaks) peak.removeAttribute('data-hot');
    el.style.removeProperty('--nx-contour-hot');
    if (id !== null) {
      const peak = peaks.find((candidate) => candidate.getAttribute('data-contour-peak') === id);
      const band = Math.max(0, Math.min(count - 1, Number(peak?.getAttribute('data-band')) || 0));
      const colour = peak?.getAttribute('data-color');
      paths[band]?.setAttribute('data-hot', '');
      paths[Math.max(0, band - 1)]?.setAttribute('data-hot', '');
      peak?.setAttribute('data-hot', '');
      if (colour) el.style.setProperty('--nx-contour-hot', colour);
    }
    onPeakChange?.(hot);
  };
  const over = (event: Event): void => {
    const target = event.target instanceof Element ? event.target.closest<SVGElement>('[data-contour-peak]') : null;
    setHot(target && el.contains(target) ? target.getAttribute('data-contour-peak') : null);
  };
  const out = (event: FocusEvent): void => {
    if (!(event.relatedTarget instanceof Element) || !event.relatedTarget.closest('[data-contour-peak]')) setHot(null);
  };
  el.addEventListener('pointerover', over);
  el.addEventListener('focusin', over);
  el.addEventListener('focusout', out);

  draw(0);
  run();

  return () => {
    window.cancelAnimationFrame(raf);
    observer?.disconnect();
    mutations.disconnect();
    stopMotion();
    el.removeEventListener('pointerover', over);
    el.removeEventListener('focusin', over);
    el.removeEventListener('focusout', out);
    setHot(null);
    for (const path of made) path.remove();
  };
}

/* ---- Ambient stage ----------------------------------------------------------------------- */

export interface AmbientStageOptions {
  /** Park the stage's loops while it is outside the viewport (default true). */
  pauseOffscreen?: boolean;
}

/**
 * Pause the CSS loops of an ambient stage — the aurora blobs, the grid stream,
 * the goo wander — while it sits outside the viewport, so a long page of
 * breathing backgrounds does not keep animating what nobody sees. The
 * stylesheet reads the same `data-paused` it uses for its own park controls.
 */
export function ambientStage(el: HTMLElement, { pauseOffscreen = true }: AmbientStageOptions = {}): Cleanup {
  if (!isBrowser || !pauseOffscreen || !('IntersectionObserver' in window)) return () => {};
  const observer = new IntersectionObserver(
    (entries) => {
      for (const entry of entries) (entry.target as HTMLElement).toggleAttribute('data-paused', !entry.isIntersecting);
    },
    { rootMargin: '96px' },
  );
  observer.observe(el);
  return () => {
    observer.disconnect();
    el.removeAttribute('data-paused');
  };
}
