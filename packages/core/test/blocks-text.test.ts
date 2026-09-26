import { describe, expect, it } from 'vitest';
import { bayerMatrix, containProgress, ditherLevel, scatterGlyphs, scrambleFrame, splitGlyphs, stickerArt } from '../src/js/blocks/text';

const texts = (text: string, mark?: string) => splitGlyphs(text, mark).words.map((word) => word.pieces.map((piece) => piece.text));

describe('splitGlyphs', () => {
  it('splits Latin by letter, keeping each word whole and the spaces between words', () => {
    const split = splitGlyphs('Write in every language');
    expect(texts('Write in every language')).toEqual([['W', 'r', 'i', 't', 'e'], ['i', 'n'], ['e', 'v', 'e', 'r', 'y'], ['l', 'a', 'n', 'g', 'u', 'a', 'g', 'e']]);
    expect(split.words.map((word) => word.space)).toEqual([' ', ' ', ' ', '']);
    expect(split.count).toBe(20);
    expect(split.words.flatMap((word) => word.pieces.map((piece) => piece.index))).toEqual(Array.from({ length: 20 }, (_, i) => i));
    expect(split.dir).toBe('ltr');
    expect(split.shaped).toBe(false);
  });

  it('moves shaped scripts by word, so letters stay joined and conjuncts stay whole', () => {
    expect(texts('با هر زبانی بنویس')).toEqual([['با'], ['هر'], ['زبانی'], ['بنویس']]);
    expect(texts('हिन्दी में लिखो')).toEqual([['हिन्दी'], ['में'], ['लिखो']]);
    expect(splitGlyphs('با هر زبانی بنویس').dir).toBe('rtl');
    expect(splitGlyphs('با هر زبانی بنویس').shaped).toBe(true);
  });

  it('keeps a run written the other way together, with its own direction', () => {
    const words = splitGlyphs('Write in every language · بنویس به هر زبانی').words;
    const last = words[words.length - 1]!;
    expect(last.dir).toBe('rtl');
    expect(last.pieces.map((piece) => piece.text)).toEqual(['بنویس ', 'به ', 'هر ', 'زبانی']);
    // Neutral punctuation follows the text around it.
    expect(words.find((word) => word.pieces[0]?.text === '·')!.dir).toBeNull();
  });

  it('boxes European digits left to right inside right-to-left text', () => {
    const words = splitGlyphs('سال 2025 است').words;
    expect(words[1]!.pieces.map((piece) => piece.text)).toEqual(['2', '0', '2', '5']);
    expect(words[1]!.dir).toBe('ltr');
    expect(words[0]!.dir).toBeNull();
  });

  it('lets Chinese and Japanese wrap between any two letters', () => {
    const [word] = splitGlyphs('世界中の言語で書く').words;
    expect(word!.loose).toBe(true);
    expect(word!.pieces).toHaveLength(9);
  });

  it('numbers the pieces of the marked part', () => {
    const split = splitGlyphs('Write in every language', 'every language');
    const marks = split.words.flatMap((word) => word.pieces.map((piece) => piece.mark));
    expect(split.marked).toBe(13);
    expect(marks.slice(0, 7)).toEqual(Array(7).fill(-1));
    expect(marks.slice(7)).toEqual(Array.from({ length: 13 }, (_, i) => i));
    expect(splitGlyphs('با هر زبانی بنویس', 'هر زبانی').words.map((word) => word.pieces[0]!.mark)).toEqual([-1, 0, 1, -1]);
    expect(splitGlyphs('No highlight here', 'missing').marked).toBe(0);
  });
});

describe('scatterGlyphs', () => {
  it('is deterministic, and matches the numbers the Blade component computes', () => {
    expect(scatterGlyphs(3, 1)).toEqual([
      { x: -839, y: -650, r: 512 },
      { x: 841, y: -853, r: -893 },
      { x: 666, y: 584, r: -461 },
    ]);
    expect(scatterGlyphs(40, 9)).toEqual(scatterGlyphs(40, 9));
    expect(scatterGlyphs(8, 2)).not.toEqual(scatterGlyphs(8, 3));
  });

  it('keeps every letter at least 35% of the spread away, and within range', () => {
    for (const { x, y, r } of scatterGlyphs(500, 42)) {
      expect(Math.abs(x)).toBeGreaterThanOrEqual(350);
      expect(Math.abs(x)).toBeLessThanOrEqual(1000);
      expect(Math.abs(y)).toBeGreaterThanOrEqual(350);
      expect(Math.abs(r)).toBeLessThanOrEqual(1000);
      expect(Number.isInteger(x) && Number.isInteger(y) && Number.isInteger(r)).toBe(true);
    }
  });
});

describe('containProgress', () => {
  it('runs 0 → 1 while a tall section keeps its stage pinned', () => {
    expect(containProgress(300, 2000, 800)).toBe(0);
    expect(containProgress(0, 2000, 800)).toBe(0);
    expect(containProgress(-600, 2000, 800)).toBe(0.5);
    expect(containProgress(-1200, 2000, 800)).toBe(1);
    expect(containProgress(-5000, 2000, 800)).toBe(1);
  });

  it('runs 0 → 1 while a short section is fully in view', () => {
    expect(containProgress(500, 300, 800)).toBe(0);
    expect(containProgress(250, 300, 800)).toBe(0.5);
    expect(containProgress(0, 300, 800)).toBe(1);
  });
});

describe('scrambleFrame', () => {
  const pool = Array.from('ABCDEFGH');
  const letters = Array.from('Hello world');

  it('settles letters in reading order and keeps spaces', () => {
    expect(scrambleFrame(letters, 0, pool).letters[5]).toBe(' ');
    expect(scrambleFrame(letters, 1, pool).letters.join('')).toBe('Hello world');
    const half = scrambleFrame(letters, 0.5, pool);
    expect(half.settled).toBe(6);
    expect(half.letters.slice(0, 6).join('')).toBe('Hello ');
    expect(half.letters.slice(6).every((glyph) => pool.includes(glyph))).toBe(true);
  });

  it('replays the same frame for the same scroll position', () => {
    expect(scrambleFrame(letters, 0.3, pool)).toEqual(scrambleFrame(letters, 0.3, pool));
    expect(scrambleFrame(letters, 0.1, pool).letters).not.toEqual(scrambleFrame(letters, 0.2, pool).letters);
  });
});

describe('dither', () => {
  it('builds Bayer matrices whose thresholds cover (0, 1) evenly', () => {
    expect(bayerMatrix(2)).toEqual([0.125, 0.625, 0.875, 0.375]);
    expect(bayerMatrix(4).map((value) => value * 16 - 0.5)).toEqual([0, 8, 2, 10, 12, 4, 14, 6, 3, 11, 1, 9, 15, 7, 13, 5]);
    const eight = bayerMatrix(8);
    expect(eight).toHaveLength(64);
    expect(new Set(eight).size).toBe(64);
    expect(Math.min(...eight)).toBeGreaterThan(0);
    expect(Math.max(...eight)).toBeLessThan(1);
  });

  it('picks a palette level by comparing against the threshold', () => {
    expect(ditherLevel(0, 0.5)).toBe(0);
    expect(ditherLevel(1, 0.5)).toBe(2);
    expect(ditherLevel(0.25, 0.4)).toBe(1);
    expect(ditherLevel(0.25, 0.6)).toBe(0);
    expect(ditherLevel(0.75, 0.4)).toBe(2);
    expect(ditherLevel(2, 0.1)).toBe(2);
  });
});

describe('stickers', () => {
  it('draws every shape with a tone', () => {
    expect(Object.keys(stickerArt).sort()).toEqual(['arrow', 'bolt', 'heart', 'smiley', 'sparkle', 'star']);
    for (const art of Object.values(stickerArt)) {
      expect(art.body).toMatch(/^M/);
      expect(['accent', 'gold', 'violet', 'cyan', 'pink']).toContain(art.tone);
    }
  });
});
