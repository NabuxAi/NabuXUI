/**
 * Framework-agnostic behaviours for the text & hero motion blocks.
 *
 *   splitGlyphs     letters for per-character motion, grouped so a word never
 *                   breaks across lines and a run written the other way keeps
 *                   its own direction
 *   scatterGlyphs   seeded offsets for the scroll text reveal (the Blade
 *                   component computes the same numbers in PHP)
 *   stickyProgress  0 → 1 through a tall section with a sticky stage: the
 *                   JavaScript twin of `animation-range: contain`
 *   scrollScramble  a headline that decodes with the scroll, and chips that fly
 *                   out of a pile into their places
 *   hoverReveal     the pointer position behind the hover-reveal glow
 *   dither          an animated ordered-dither field on a canvas
 */
import { type Cleanup, isBrowser, onReducedMotionChange, prefersReducedMotion } from '../env';
import { glyphs, hasJoiningScript, splitText, textDirection } from '../text';
import { THEME_EVENT } from '../theme';

const clamp01 = (value: number) => Math.min(1, Math.max(0, value));

/* ---- Splitting ---------------------------------------------------------------- */

/**
 * Scripts whose letters are shaped together — Arabic and Persian, Syriac, N'Ko,
 * the Indic scripts, Thai, Tibetan, Myanmar, Khmer, Mongolian. Boxing their
 * letters one by one would break the joins and the conjuncts, so they move by word.
 */
const SHAPED = /[؀-ۿ܀-ݏݐ-ݿ߀-߿ࢠ-ࣿऀ-෿฀-໿ༀ-࿿က-႟ក-៿᠀-᢯ﭐ-﷿ﹰ-﻿]/;
/** Scripts written without spaces (Chinese, Japanese): a line may wrap between any two letters. */
const LOOSE = /[　-ヿ㐀-䶿一-鿿豈-﫿＀-￯]/;
/** Strong characters, as in the core's textDirection. */
const RTL = /[֐-ࣿיִ-﷿ﹰ-﻿]/;
const LTR = /(?![֐-ࣿיִ-﷿ﹰ-﻿])\p{L}/u;

let segmenter: Intl.Segmenter | null | undefined;

/** User-perceived characters: an accent or an emoji sequence stays one piece. */
function graphemes(text: string): string[] {
  if (segmenter === undefined) {
    segmenter = typeof Intl !== 'undefined' && 'Segmenter' in Intl ? new Intl.Segmenter(undefined, { granularity: 'grapheme' }) : null;
  }
  return segmenter ? Array.from(segmenter.segment(text), (s) => s.segment) : Array.from(text);
}

export interface GlyphPiece {
  text: string;
  /** Position in reading order across the whole text, for staggering (--nx-i). */
  index: number;
  /** Position inside the marked part (the highlight), or −1 outside it. */
  mark: number;
}

export interface GlyphWord {
  /** Letters, or whole words for shaped scripts. */
  pieces: GlyphPiece[];
  /** Whitespace after the word, rendered outside it so the line can still wrap there. */
  space: string;
  /** Set when the word reads the other way from the text: it gets its own `dir`. */
  dir: 'ltr' | 'rtl' | null;
  /** Chinese and Japanese: may wrap between any two letters. */
  loose: boolean;
}

export interface GlyphSplit {
  /** The text's own direction: set it as `dir` on the pieces' container. */
  dir: 'ltr' | 'rtl';
  words: GlyphWord[];
  /** Number of pieces. */
  count: number;
  /** Number of pieces inside the marked part. */
  marked: number;
  /** The text uses a shaped script (taller line boxes, per-word motion). */
  shaped: boolean;
}

function wordDirection(word: string, base: 'ltr' | 'rtl'): 'ltr' | 'rtl' | null {
  if (RTL.test(word) || LTR.test(word)) {
    const own = textDirection(word);
    return own === base ? null : own;
  }
  // No letters at all: European digits still read left to right once they are boxed.
  return base === 'rtl' && /[0-9]/.test(word) ? 'ltr' : null;
}

/**
 * Split text into pieces for per-character motion.
 *
 * Every piece is an inline-block, and a line may break between any two of
 * them — so pieces are grouped by word (a word never breaks apart), a run of
 * words written the other way ("Livewire" inside Persian) is one group with its
 * own `dir` (its letters keep their order), and shaped scripts are split by word
 * instead of by letter. `mark` flags the pieces of a substring (a highlight).
 *
 * Mirrored line for line by the Blade components that split text.
 */
export function splitGlyphs(text: string, mark = ''): GlyphSplit {
  const dir = textDirection(text);
  const from = mark ? text.indexOf(mark) : -1;
  const to = from + mark.length;
  const words: GlyphWord[] = [];
  let offset = 0;
  let index = 0;
  let marked = 0;

  for (const chunk of splitText(text, 'word')) {
    const body = chunk.trimEnd();
    const parts = SHAPED.test(body) ? (body.match(/\S+\s*/g) ?? []) : graphemes(body);
    let at = offset;
    const pieces = parts.map((part) => {
      const inside = from >= 0 && at >= from && at < to;
      at += part.length;
      return { text: part, index: index++, mark: inside ? marked++ : -1 };
    });
    words.push({ pieces, space: chunk.slice(body.length), dir: wordDirection(body, dir), loose: LOOSE.test(body) });
    offset += chunk.length;
  }

  return { dir, words, count: index, marked, shaped: SHAPED.test(text) };
}

/* ---- Seeded scatter --------------------------------------------------------------- */

export interface GlyphOffset {
  /** Horizontal and vertical offset, ±350…1000 (thousandths of the spread set in CSS). */
  x: number;
  y: number;
  /** Rotation, −1000…1000 (thousandths of the largest angle). */
  r: number;
}

/**
 * Where each letter of the scroll text reveal starts, before it flies into
 * place. Random-looking but deterministic: a Park–Miller stream whose products
 * stay below 2^53, so JavaScript and PHP compute the same integers and server-
 * and client-rendered markup agree. No letter starts closer than 35% of the spread.
 */
export function scatterGlyphs(count: number, seed = 1): GlyphOffset[] {
  let state = Math.abs(Math.trunc(seed)) % 2147483647 || 1;
  const next = () => {
    state = (state * 48271) % 2147483647;
    return (state % 2001) - 1000;
  };
  const spread = (value: number) => (value < 0 ? -1 : 1) * (350 + Math.floor((Math.abs(value) * 13) / 20));
  return Array.from({ length: count }, () => {
    const x = spread(next());
    const y = spread(next());
    return { x, y, r: next() };
  });
}

/* ---- Sticky progress ----------------------------------------------------------------- */

/**
 * Progress through the part of the scroll where the element fills the viewport
 * (a tall section: from the moment its stage pins to the moment it lets go),
 * the same range as CSS `animation-range: contain 0% contain 100%`.
 */
export function containProgress(top: number, height: number, viewport: number): number {
  const start = Math.max(0, viewport - height);
  const span = Math.abs(viewport - height);
  if (span < 1) return top <= 0 ? 1 : 0;
  return clamp01((start - top) / span);
}

/** Whether CSS can run animations on a view timeline (with ranges). */
export function supportsViewTimeline(): boolean {
  return isBrowser && typeof CSS !== 'undefined' && typeof CSS.supports === 'function' && CSS.supports('(animation-timeline: view()) and (animation-range: entry)');
}

export interface StickyProgressOptions {
  /** Run even where CSS scroll-driven animations can drive the element (default: only where they cannot). */
  always?: boolean;
  /** The custom property that receives the progress. */
  property?: string;
  /** Called with every new progress, 0–1. */
  onProgress?: (progress: number) => void;
}

/**
 * Write an element's progress through its sticky range as `--nx-progress`
 * (0 when its stage pins, 1 when it lets go). By default this is the fallback
 * for browsers without scroll-driven animations and does nothing elsewhere.
 * Scroll is read once per frame, and not at all while the element is far away.
 */
export function stickyProgress(el: HTMLElement, { always = false, property = '--nx-progress', onProgress }: StickyProgressOptions = {}): Cleanup {
  if (!isBrowser || (!always && supportsViewTimeline())) return () => {};

  let frame = 0;
  let last = -1;
  let near = true;

  const measure = () => {
    frame = 0;
    const rect = el.getBoundingClientRect();
    const progress = containProgress(rect.top, rect.height, window.innerHeight);
    if (progress === last) return;
    last = progress;
    el.style.setProperty(property, String(Math.round(progress * 10000) / 10000));
    onProgress?.(progress);
  };

  const schedule = () => {
    if (near && !frame) frame = requestAnimationFrame(measure);
  };

  const observer =
    'IntersectionObserver' in window
      ? new IntersectionObserver(
          ([entry]) => {
            near = !!entry?.isIntersecting;
            // One more reading on the way out, so it rests at exactly 0 or 1.
            if (!frame) frame = requestAnimationFrame(measure);
          },
          { rootMargin: '25% 0px' },
        )
      : null;
  observer?.observe(el);

  // Capture catches every scroller on the page, not only the window.
  document.addEventListener('scroll', schedule, { passive: true, capture: true });
  window.addEventListener('resize', schedule, { passive: true });
  measure();

  return () => {
    cancelAnimationFrame(frame);
    observer?.disconnect();
    document.removeEventListener('scroll', schedule, { capture: true });
    window.removeEventListener('resize', schedule);
    el.style.removeProperty(property);
  };
}

/* ---- Scroll scramble --------------------------------------------------------------------- */

function mix(a: number, b: number): number {
  let h = Math.imul(a + 1, 0x9e3779b1) ^ Math.imul(b + 7, 0x85ebca77);
  h ^= h >>> 15;
  h = Math.imul(h, 0x2c1b3c6d);
  h ^= h >>> 12;
  return h >>> 0;
}

/**
 * One frame of a scroll-driven decode: letters before the cursor are the real
 * text, the rest are glyphs from `pool`. Each glyph follows from its position
 * and how far the scroll has come, so scrubbing back and forth replays the
 * same frames instead of flickering at random.
 */
export function scrambleFrame(letters: string[], progress: number, pool: string[], steps = 90): { settled: number; letters: string[] } {
  const settled = Math.round(clamp01(progress) * letters.length);
  const step = Math.floor(clamp01(progress) * steps);
  return {
    settled,
    letters: letters.map((letter, i) => (i < settled || !pool.length || /\s/.test(letter) ? letter : pool[mix(i, step) % pool.length]!)),
  };
}

export interface ScrollScrambleOptions {
  /** The real headline (read from the element when left out). */
  text?: string;
  /** Glyphs to cycle: latin, persian, cuneiform or your own string; picked from the text's script by default. */
  charset?: keyof typeof glyphs | string;
  /** The part of the section's progress over which the headline decodes. */
  range?: [number, number];
}

/**
 * The scroll scramble section. As it scrolls through its sticky range the
 * headline decodes in step with the scroll (`.nx-scroll-scramble-text`), and
 * `--nx-progress` drives the chips (`.nx-scroll-scramble-chip`) from a pile to
 * their places: this measures where each chip really sits (`--_dx`, `--_dy`,
 * relative to the centre of `.nx-scroll-scramble-chips`) and CSS interpolates.
 * Reduced motion shows the finished state.
 */
export function scrollScramble(el: HTMLElement, { text, charset, range = [0.02, 0.6] }: ScrollScrambleOptions = {}): Cleanup {
  if (!isBrowser) return () => {};

  const target = el.querySelector<HTMLElement>('.nx-scroll-scramble-text');
  const host = el.querySelector<HTMLElement>('.nx-scroll-scramble-chips');
  const real = text ?? target?.textContent ?? '';
  const letters = graphemes(real);
  const joining = hasJoiningScript(real);
  const set = charset ? (charset in glyphs ? glyphs[charset as keyof typeof glyphs] : charset) : joining ? glyphs.persian : glyphs.latin;
  const pool = graphemes(set);

  // Settled letters and pending glyphs in two spans, so the pending ones can take the accent.
  const settledEl = document.createElement('span');
  const pendingEl = document.createElement('span');
  pendingEl.className = 'nx-scroll-scramble-pending';
  target?.replaceChildren(settledEl, pendingEl);

  let shown = -1;
  const render = (progress: number) => {
    if (!target) return;
    const local = clamp01((progress - range[0]) / Math.max(0.001, range[1] - range[0]));
    const frame = scrambleFrame(letters, local, pool);
    const key = frame.settled * 1000 + Math.floor(local * 90);
    if (key === shown) return;
    shown = key;
    // Joining scripts change colour at a word boundary, so no word is cut in two.
    let split = frame.settled;
    if (joining) while (split > 0 && split < letters.length && !/\s/.test(letters[split - 1]!)) split -= 1;
    settledEl.textContent = frame.letters.slice(0, split).join('');
    pendingEl.textContent = frame.letters.slice(split).join('');
    target.toggleAttribute('data-scrambling', frame.settled < letters.length);
  };

  const chips = () => (host ? Array.from(host.querySelectorAll<HTMLElement>('.nx-scroll-scramble-chip')) : []);
  const measure = () => {
    if (!host) return;
    const list = chips();
    const jitter = scatterGlyphs(list.length, 7);
    const cx = host.clientWidth / 2;
    const cy = host.clientHeight / 2;
    list.forEach((chip, i) => {
      const j = jitter[i]!;
      // Layout positions (offset*), which the chip's own translate does not disturb.
      chip.style.setProperty('--_dx', `${(cx - chip.offsetLeft - chip.offsetWidth / 2 + j.x * 0.02).toFixed(1)}px`);
      chip.style.setProperty('--_dy', `${(cy - chip.offsetTop - chip.offsetHeight / 2 + j.y * 0.012).toFixed(1)}px`);
      chip.style.setProperty('--_r', String(j.r));
    });
  };

  const resize = typeof ResizeObserver !== 'undefined' ? new ResizeObserver(() => measure()) : null;
  const watch = () => {
    resize?.disconnect();
    if (host) resize?.observe(host);
    for (const chip of chips()) resize?.observe(chip);
    measure();
  };
  const mutations = host && typeof MutationObserver !== 'undefined' ? new MutationObserver(watch) : null;
  if (host) mutations?.observe(host, { childList: true });
  watch();

  const stop = prefersReducedMotion() ? (render(1), () => {}) : stickyProgress(el, { always: true, onProgress: render });

  return () => {
    stop();
    resize?.disconnect();
    mutations?.disconnect();
    for (const chip of chips()) for (const name of ['--_dx', '--_dy', '--_r']) chip.style.removeProperty(name);
    if (target) {
      target.textContent = real;
      target.removeAttribute('data-scrambling');
    }
  };
}

/* ---- Hover reveal -------------------------------------------------------------------------- */

export interface HoverRevealOptions {
  link?: string;
  label?: string;
}

/**
 * The hover-reveal glow: writes the pointer's position over the hovered link as
 * --nx-px / --nx-py on its label (in px from the label's corner), so the colour
 * clipped to the word follows the pointer even over the description or the gaps.
 */
export function hoverReveal(el: HTMLElement, { link = '.nx-hover-reveal-link', label = '.nx-hover-reveal-label' }: HoverRevealOptions = {}): Cleanup {
  if (!isBrowser) return () => {};
  let frame = 0;
  let last: PointerEvent | null = null;
  let active: HTMLElement | null = null;
  let rect: DOMRect | null = null;

  const flush = () => {
    frame = 0;
    const event = last;
    if (!event) return;
    const target = (event.target as Element | null)?.closest?.(link)?.querySelector<HTMLElement>(label) ?? null;
    if (!target) return;
    if (target !== active) {
      active = target;
      rect = null;
    }
    rect ??= target.getBoundingClientRect();
    target.style.setProperty('--nx-px', `${Math.round(event.clientX - rect.left)}px`);
    target.style.setProperty('--nx-py', `${Math.round(event.clientY - rect.top)}px`);
  };

  const onMove = (event: PointerEvent) => {
    last = event;
    if (!frame) frame = requestAnimationFrame(flush);
  };
  // Scrolling or resizing moves the label under a still pointer: measure again.
  const forget = () => {
    rect = null;
  };

  el.addEventListener('pointermove', onMove);
  el.addEventListener('pointerleave', forget);
  window.addEventListener('scroll', forget, { passive: true, capture: true });
  window.addEventListener('resize', forget);

  return () => {
    cancelAnimationFrame(frame);
    el.removeEventListener('pointermove', onMove);
    el.removeEventListener('pointerleave', forget);
    window.removeEventListener('scroll', forget, { capture: true });
    window.removeEventListener('resize', forget);
  };
}

/* ---- Stickers ------------------------------------------------------------------------------ */

export type StickerShape = 'star' | 'sparkle' | 'heart' | 'bolt' | 'smiley' | 'arrow';
export type StickerPosition = 'top-start' | 'top-end' | 'bottom-start' | 'bottom-end' | 'start' | 'end';
export type StickerTone = 'accent' | 'gold' | 'violet' | 'cyan' | 'pink';

export interface StickerArt {
  /** The shape, on a 64×64 box. */
  body: string;
  /** Drawn as a thick line rather than a filled shape. */
  line?: boolean;
  /** Dark details (a face). */
  ink?: string;
  /** A small highlight. */
  shine?: string;
  tone: StickerTone;
}

/** The text hero's stickers: filled shapes with a die-cut border, drawn for NabuXUI. */
export const stickerArt: Record<StickerShape, StickerArt> = {
  star: { body: 'M32 7L39 23.3 56.7 25 43.4 36.7 47.3 54 32 45 16.7 54 20.6 36.7 7.3 25 25 23.3Z', shine: 'M25.5 29l1.8-3.8', tone: 'gold' },
  sparkle: {
    body: 'M30 7C31.8 23.5 38.5 30.2 55 32 38.5 33.8 31.8 40.5 30 57 28.2 40.5 21.5 33.8 5 32 21.5 30.2 28.2 23.5 30 7ZM52 5c.5 4.5 2.5 6.5 7 7-4.5.5-6.5 2.5-7 7-.5-4.5-2.5-6.5-7-7 4.5-.5 6.5-2.5 7-7Z',
    tone: 'violet',
  },
  heart: {
    body: 'M32 55C18.5 46 8 37 8 24.5 8 16.5 14 10.5 21.5 10.5c4.7 0 8.3 2.5 10.5 6.3 2.2-3.8 5.8-6.3 10.5-6.3C50 10.5 56 16.5 56 24.5 56 37 45.5 46 32 55Z',
    shine: 'M16.5 21c1-2.6 3-4.1 5.5-4.3',
    tone: 'pink',
  },
  bolt: { body: 'M36 4 12 36h17l-4 24 27-33H36l7-23Z', shine: 'M31.5 12l-4.5 7', tone: 'accent' },
  smiley: {
    body: 'M32 6a26 26 0 1 1 0 52 26 26 0 1 1 0-52Z',
    ink: 'M24.5 24v5M39.5 24v5M21.5 37c3.5 6.5 17.5 6.5 21 0',
    shine: 'M15.5 24c1.2-3.6 3.6-6.3 7-7.8',
    tone: 'gold',
  },
  arrow: { body: 'M8 50c12 1.5 23-4.5 30-15.5 3.8-6 8-12.6 17-16.5M44.5 13.5 56 18.5l-5 11', line: true, tone: 'cyan' },
};

/* ---- Dither ----------------------------------------------------------------------------------- */

/**
 * A Bayer ordered-dither matrix as thresholds in (0, 1), row by row — the
 * pattern that makes a smooth gradient out of on/off cells.
 */
export function bayerMatrix(size: 2 | 4 | 8 = 8): number[] {
  let matrix = [0];
  let n = 1;
  while (n < size) {
    const next = new Array<number>(4 * n * n);
    for (let y = 0; y < n; y++) {
      for (let x = 0; x < n; x++) {
        const v = matrix[y * n + x]! * 4;
        next[y * 2 * n + x] = v;
        next[y * 2 * n + x + n] = v + 2;
        next[(y + n) * 2 * n + x] = v + 3;
        next[(y + n) * 2 * n + x + n] = v + 1;
      }
    }
    matrix = next;
    n *= 2;
  }
  return matrix.map((v) => (v + 0.5) / (n * n));
}

/** Ordered dithering: the palette level (0 … levels − 1) for an intensity (0–1) at a cell's matrix threshold. */
export function ditherLevel(value: number, threshold: number, levels = 3): number {
  const scaled = clamp01(value) * (levels - 1);
  const floor = Math.floor(scaled);
  return Math.min(levels - 1, floor + (scaled - floor > threshold ? 1 : 0));
}

export interface DitherOptions {
  /** One dither cell, in CSS pixels. */
  cell?: number;
  /** The Bayer matrix: 4 draws a coarser pattern, 8 a finer one. */
  matrix?: 4 | 8;
  /** Animation speed; 1 is the default drift. */
  speed?: number;
  /** Frame cap: ordered dither reads well at 30 fps and costs half as much. */
  fps?: number;
}

type Rgba = [number, number, number, number];

let probe: CanvasRenderingContext2D | null | undefined;

/** Any CSS colour as RGBA bytes, via a 1×1 canvas (so theme tokens in any syntax work). */
function rgba(value: string, fallback: Rgba): Rgba {
  if (!value) return fallback;
  if (probe === undefined) {
    const canvas = document.createElement('canvas');
    canvas.width = canvas.height = 1;
    probe = canvas.getContext('2d', { willReadFrequently: true });
  }
  if (!probe) return fallback;
  probe.clearRect(0, 0, 1, 1);
  probe.fillStyle = '#000';
  probe.fillStyle = value;
  probe.fillRect(0, 0, 1, 1);
  const [r = 0, g = 0, b = 0, a = 255] = probe.getImageData(0, 0, 1, 1).data;
  return [r, g, b, a];
}

/**
 * An animated ordered-dither field (slow waves over a low glow, with a faster
 * ripple catching the light) on a canvas at a fraction of the screen's
 * resolution, scaled up with `image-rendering: pixelated`. Cells take the
 * current theme's --nx-accent, the ripple's crests a blend of it with
 * --nx-text, and repaint when the theme changes. It pauses off-screen and in
 * hidden tabs, and draws one still frame under reduced motion.
 */
export function dither(canvas: HTMLCanvasElement, { cell = 4, matrix = 8, speed = 1, fps = 30 }: DitherOptions = {}): Cleanup {
  if (!isBrowser) return () => {};
  const ctx = canvas.getContext('2d');
  if (!ctx) return () => {};

  const thresholds = bayerMatrix(matrix);
  const box = canvas.parentElement ?? canvas;
  let cols = 0;
  let rows = 0;
  let size = cell;
  let image: ImageData | null = null;
  let colGlow = new Float32Array(0);
  let colPhase = new Float32Array(0);
  let rowGlow = new Float32Array(0);
  let rowPhase = new Float32Array(0);
  let palette: [Rgba, Rgba] = [[0, 0, 0, 0], [0, 0, 0, 0]];
  let visible = true;
  let still = prefersReducedMotion();
  let frame = 0;
  let lastDraw = 0;
  let time = 2.4;
  const origin = performance.now();

  const readPalette = () => {
    const style = getComputedStyle(canvas);
    const accent = rgba(style.getPropertyValue('--nx-accent').trim(), [86, 71, 230, 255]);
    const ink = rgba(style.getPropertyValue('--nx-text').trim(), [18, 20, 31, 255]);
    // Crests lean toward the text colour: deeper on light pages, luminous on dark ones.
    const crest = accent.map((v, i) => Math.round(v + (ink[i]! - v) * 0.45)) as Rgba;
    palette = [accent, crest];
  };

  /*
   * Two one-bit layers over a glow low in the section, like light on a horizon:
   * accent cells whose density follows slow waves (never above ~70%, so the
   * dither texture always shows), and sparse highlight cells along the crests of
   * a faster ripple. The highlight layer reads the matrix half a tile over, so
   * the two patterns never line up.
   */
  const draw = (t: number) => {
    if (!image) return;
    const data = image.data;
    const [base, crest] = palette;
    const half = matrix / 2;
    // Separable terms, computed once per column and row rather than per cell.
    const drift = 0.5 + 0.14 * Math.sin(t * 0.19);
    for (let x = 0; x < cols; x++) {
      const u = (x / cols - drift) * 1.15;
      colGlow[x] = Math.exp(-u * u * 2.4);
      colPhase[x] = x * size * 0.0105;
    }
    for (let y = 0; y < rows; y++) {
      const v = y / rows - 0.56;
      rowGlow[y] = Math.exp(-v * v * 4.2);
      rowPhase[y] = Math.sin(y * size * 0.006 + t * 0.5) * 1.7 + y * size * 0.004;
    }
    let i = 0;
    for (let y = 0; y < rows; y++) {
      const glowY = rowGlow[y]!;
      const phaseY = rowPhase[y]!;
      const row = (y % matrix) * matrix;
      const shifted = ((y + half) % matrix) * matrix;
      for (let x = 0; x < cols; x++, i += 4) {
        const glow = colGlow[x]! * glowY;
        const wave = Math.sin(colPhase[x]! + phaseY + t * 0.85) * 0.5 + 0.5;
        const ripple = Math.sin((x * 0.55 - y) * size * 0.012 - t * 1.4) * 0.5 + 0.5;
        const sharp = ripple * ripple * ripple * ripple;
        const color = ditherLevel(glow * sharp * wave * 0.9, thresholds[shifted + ((x + half) % matrix)]!, 2)
          ? crest
          : ditherLevel(glow * (0.15 + 0.55 * wave), thresholds[row + (x % matrix)]!, 2)
            ? base
            : null;
        if (!color) {
          data[i + 3] = 0;
          continue;
        }
        data[i] = color[0];
        data[i + 1] = color[1];
        data[i + 2] = color[2];
        data[i + 3] = color[3];
      }
    }
    ctx.putImageData(image, 0, 0);
  };

  const resize = () => {
    const rect = box.getBoundingClientRect();
    const ratio = window.devicePixelRatio || 1;
    // A whole number of device pixels per cell keeps every cell the same size.
    size = Math.max(1, Math.round(cell * ratio)) / ratio;
    const nextCols = Math.max(1, Math.ceil(rect.width / size));
    const nextRows = Math.max(1, Math.ceil(rect.height / size));
    if (nextCols === cols && nextRows === rows && image) return;
    cols = nextCols;
    rows = nextRows;
    canvas.width = cols;
    canvas.height = rows;
    canvas.style.width = `${cols * size}px`;
    canvas.style.height = `${rows * size}px`;
    image = ctx.createImageData(cols, rows);
    colGlow = new Float32Array(cols);
    colPhase = new Float32Array(cols);
    rowGlow = new Float32Array(rows);
    rowPhase = new Float32Array(rows);
    draw(time);
  };

  const running = () => visible && !still && !document.hidden;

  const loop = (now: number) => {
    frame = 0;
    if (!running()) return;
    if (now - lastDraw >= 1000 / fps - 2) {
      lastDraw = now;
      time = 2.4 + ((now - origin) / 1000) * speed;
      draw(time);
    }
    frame = requestAnimationFrame(loop);
  };

  const play = () => {
    if (running() && !frame) frame = requestAnimationFrame(loop);
  };
  const pause = () => {
    cancelAnimationFrame(frame);
    frame = 0;
  };

  const onTheme = () => {
    readPalette();
    if (!running()) draw(time);
  };

  readPalette();
  resize();

  const sizes = typeof ResizeObserver !== 'undefined' ? new ResizeObserver(() => resize()) : null;
  sizes?.observe(box);
  const views =
    'IntersectionObserver' in window
      ? new IntersectionObserver(([entry]) => {
          visible = !!entry?.isIntersecting;
          if (visible) play();
          else pause();
        })
      : null;
  views?.observe(canvas);

  const onVisibility = () => (document.hidden ? pause() : play());
  document.addEventListener('visibilitychange', onVisibility);

  // The theme can change through the core toggle, the system, or an app flipping html.dark.
  const scheme = window.matchMedia('(prefers-color-scheme: dark)');
  scheme.addEventListener('change', onTheme);
  window.addEventListener(THEME_EVENT, onTheme);
  const themeAttributes = typeof MutationObserver !== 'undefined' ? new MutationObserver(onTheme) : null;
  themeAttributes?.observe(document.documentElement, { attributes: true, attributeFilter: ['class', 'data-theme'] });

  const stopMotionWatch = onReducedMotionChange((reduce) => {
    still = reduce;
    if (still) {
      pause();
      draw(time);
    } else play();
  });

  play();

  return () => {
    pause();
    sizes?.disconnect();
    views?.disconnect();
    themeAttributes?.disconnect();
    document.removeEventListener('visibilitychange', onVisibility);
    scheme.removeEventListener('change', onTheme);
    window.removeEventListener(THEME_EVENT, onTheme);
    stopMotionWatch();
  };
}
