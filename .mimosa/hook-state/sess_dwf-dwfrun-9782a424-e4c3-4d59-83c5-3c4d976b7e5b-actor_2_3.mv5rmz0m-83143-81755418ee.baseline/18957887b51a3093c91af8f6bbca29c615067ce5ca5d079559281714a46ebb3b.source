/**
 * Text helpers: splitting for piecewise animation, the scramble effect and the
 * digit columns behind rolling numbers.
 */
import { type Cleanup, isBrowser, prefersReducedMotion } from './env';

/** Arabic, Persian, Urdu… scripts whose letters join to their neighbours. */
const JOINING_SCRIPT = /[؀-ۿݐ-ݿࢠ-ࣿﭐ-﷿ﹰ-﻿]/;

export function hasJoiningScript(text: string): boolean {
  return JOINING_SCRIPT.test(text);
}

export type SplitBy = 'word' | 'char';

const RTL_CHAR = /[\u0590-\u08FF\uFB1D-\uFDFF\uFE70-\uFEFF]/;
/** Any letter of a left-to-right script: Latin, CJK, Devanagari, Cyrillic… (everything that is not RTL). */
const LTR_CHAR = /(?![\u0590-\u08FF\uFB1D-\uFDFF\uFE70-\uFEFF])\p{L}/u;

/** The direction a piece of text reads in, from its first strong character. */
export function textDirection(text: string): 'ltr' | 'rtl' {
  for (const char of text) {
    if (RTL_CHAR.test(char)) return 'rtl';
    if (LTR_CHAR.test(char)) return 'ltr';
  }
  return 'ltr';
}

/**
 * Split text into animatable pieces.
 *
 * Each piece becomes an inline-block, and inline-blocks are laid out in the
 * paragraph's direction, not their own — so the pieces carry the text's
 * direction (see `textDirection`, set it as `dir` on their container), and a
 * run of words written the other way ("Livewire و Inertia" inside Persian) is
 * kept as one piece so its words do not come out reversed.
 *
 * Whitespace stays with the preceding word so spacing survives. Letters of
 * joining scripts are never separated — that would break the joins — so they
 * are split by word even when characters were asked for.
 */
export function splitText(text: string, by: SplitBy = 'word'): string[] {
  if (by === 'char' && !hasJoiningScript(text)) {
    const segmenter = typeof Intl !== 'undefined' && 'Segmenter' in Intl ? new Intl.Segmenter(undefined, { granularity: 'grapheme' }) : null;
    return segmenter ? Array.from(segmenter.segment(text), (s) => s.segment) : Array.from(text);
  }

  const words = text.match(/\S+\s*|\s+/g) ?? [];
  const base = textDirection(text);
  const other = base === 'rtl' ? LTR_CHAR : RTL_CHAR;
  const pieces: string[] = [];
  let run = '';

  for (const word of words) {
    const foreign = other.test(word) && !(base === 'rtl' ? RTL_CHAR : LTR_CHAR).test(word);
    const neutral = !RTL_CHAR.test(word) && !LTR_CHAR.test(word);
    if (foreign || (run && neutral)) {
      run += word;
      continue;
    }
    if (run) {
      pieces.push(run);
      run = '';
    }
    pieces.push(word);
  }
  if (run) pieces.push(run);
  return pieces;
}

/* ---- Scramble ------------------------------------------------------------- */

export const glyphs = {
  latin: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789#%&*+<>/\\',
  persian: 'ابپتثجچحخدذرزژسشصضطظعغفقکگلمنوهی۰۱۲۳۴۵۶۷۸۹',
  cuneiform: '𒀀𒀁𒀂𒀃𒀄𒀅𒀆𒀇𒀈𒀉𒀊𒀋𒀌𒀍𒀎𒀏𒀐𒀑𒀒𒀓𒀔𒀕𒀖𒀗𒀘𒀙𒀚',
} as const;

export interface ScrambleOptions {
  /** Total time in ms for the text to settle. */
  duration?: number;
  /** Which glyphs to cycle through; picked from the text's script by default. */
  charset?: keyof typeof glyphs | string;
  onDone?: () => void;
}

/**
 * Cycle random glyphs through `el`'s text, left to right, until each position
 * lands on its real character. The element should be aria-hidden with the real
 * text beside it for assistive technology.
 */
export function scramble(el: HTMLElement, text: string, { duration = 1100, charset, onDone }: ScrambleOptions = {}): Cleanup {
  if (!isBrowser || prefersReducedMotion()) {
    el.textContent = text;
    onDone?.();
    return () => {};
  }

  const pool = Array.from(
    charset ? (charset in glyphs ? glyphs[charset as keyof typeof glyphs] : charset) : hasJoiningScript(text) ? glyphs.persian : glyphs.latin,
  );
  const target = Array.from(text);
  const start = performance.now();
  let frame = 0;
  let lastTick = 0;
  el.setAttribute('data-scrambling', '');

  const tick = (now: number) => {
    const progress = Math.min(1, (now - start) / duration);
    if (now - lastTick > 45 || progress === 1) {
      lastTick = now;
      const settled = Math.floor(progress * target.length);
      el.textContent = target
        .map((char, i) => (i < settled || /\s/.test(char) ? char : pool[Math.floor(Math.random() * pool.length)]))
        .join('');
    }
    if (progress < 1) {
      frame = requestAnimationFrame(tick);
    } else {
      el.textContent = text;
      el.removeAttribute('data-scrambling');
      onDone?.();
    }
  };

  frame = requestAnimationFrame(tick);
  return () => {
    cancelAnimationFrame(frame);
    el.textContent = text;
    el.removeAttribute('data-scrambling');
  };
}

/* ---- Numbers -------------------------------------------------------------- */

export interface NumberPart {
  /** A digit column (value 0–9) or a fixed character (separator, sign, unit). */
  kind: 'digit' | 'static';
  value: number;
  char: string;
}

/** The ten digits of the locale's numbering system, 0 → 9. */
export function localeDigits(locale?: string): string[] {
  const format = new Intl.NumberFormat(locale, { useGrouping: false });
  return Array.from({ length: 10 }, (_, i) => format.format(i));
}

export function formatNumber(value: number, locale?: string, options?: Intl.NumberFormatOptions): string {
  return new Intl.NumberFormat(locale, options).format(value);
}

/**
 * Break a formatted number into digit columns and fixed characters, in any
 * numbering system: "۱٬۲۸۴" becomes four digit columns around a separator.
 */
export function numberParts(value: number, locale?: string, options?: Intl.NumberFormatOptions): NumberPart[] {
  const digits = localeDigits(locale);
  return Array.from(formatNumber(value, locale, options)).map((char) => {
    const index = digits.indexOf(char);
    if (index !== -1) return { kind: 'digit', value: index, char };
    // Formatters sometimes emit ASCII digits even for other numbering systems.
    if (char >= '0' && char <= '9') return { kind: 'digit', value: Number(char), char };
    return { kind: 'static', value: 0, char };
  });
}
