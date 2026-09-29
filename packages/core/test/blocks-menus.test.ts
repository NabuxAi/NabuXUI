import { describe, expect, it } from 'vitest';
import {
  activityTime,
  downsampleLevels,
  entrySide,
  facingSide,
  foldSearchText,
  formatClock,
  menuGlyphs,
  menuPath,
  menuWord,
  menuWords,
  parseCssTime,
  rmsLevel,
  searchMenuTree,
  ticketTotal,
  timeOf,
  toggleSelection,
  voiceLevel,
} from '../src/js/blocks/menus';

describe('menu words', () => {
  it('speaks the same keys in every language', () => {
    const keys = Object.keys(menuWords.en).sort();
    expect(Object.keys(menuWords.fa).sort()).toEqual(keys);
    expect(Object.keys(menuWords.ar).sort()).toEqual(keys);
  });

  it('fills parameters and falls back to English', () => {
    expect(menuWord('en', 'unread', { count: 3 })).toBe('3 unread');
    expect(menuWord('fa-IR', 'leave')).toBe('خروج');
    expect(menuWord('de', 'leave')).toBe('Leave');
    expect(menuWord(undefined, 'roleFor', { name: 'Kenji' })).toBe('Role for Kenji');
  });

  it('draws every extra glyph as a path', () => {
    for (const d of Object.values(menuGlyphs)) expect(d).toMatch(/^M[\d.]+ [\d.]+|^M[\d.]+/);
  });
});

describe('selection', () => {
  it('toggles any number of values', () => {
    expect(toggleSelection(['a'], 'b')).toEqual(['a', 'b']);
    expect(toggleSelection(['a', 'b'], 'a')).toEqual(['b']);
  });

  it('keeps at most one value in single mode', () => {
    expect(toggleSelection(['a'], 'b', false)).toEqual(['b']);
    expect(toggleSelection(['b'], 'b', false)).toEqual([]);
  });

  it('never mutates the list it was given', () => {
    const list = ['a'];
    toggleSelection(list, 'b');
    expect(list).toEqual(['a']);
  });
});

describe('css time', () => {
  it('reads milliseconds and seconds', () => {
    expect(parseCssTime('425ms')).toBe(425);
    expect(parseCssTime(' 0.4s ')).toBe(400);
    expect(parseCssTime('180')).toBe(180);
    expect(parseCssTime('fast')).toBeNaN();
  });
});

describe('clock', () => {
  it('pads minutes and seconds', () => {
    expect(formatClock(0)).toBe('00:00');
    expect(formatClock(7.9)).toBe('00:07');
    expect(formatClock(83)).toBe('01:23');
    expect(formatClock(725)).toBe('12:05');
  });

  it('keeps counting past an hour and ignores nonsense', () => {
    expect(formatClock(6000)).toBe('100:00');
    expect(formatClock(-4)).toBe('00:00');
    expect(formatClock(Number.NaN)).toBe('00:00');
  });
});

describe('activity time', () => {
  const now = Date.UTC(2026, 8, 26, 12, 0, 0);

  it('says "now" for the last few seconds', () => {
    expect(activityTime(now - 10_000, now, 'en')).toBe('now');
  });

  it('picks the unit that reads naturally', () => {
    expect(activityTime(now - 5 * 60_000, now, 'en')).toBe('5 minutes ago');
    expect(activityTime(now - 3 * 3_600_000, now, 'en')).toBe('3 hours ago');
    expect(activityTime(now - 86_400_000, now, 'en')).toBe('yesterday');
    expect(activityTime(now + 2 * 3_600_000, now, 'en')).toBe('in 2 hours');
  });

  it('accepts dates and ISO strings, and keeps labels as they are', () => {
    expect(activityTime(new Date(now - 120_000), now, 'en')).toBe('2 minutes ago');
    expect(activityTime(new Date(now - 120_000).toISOString(), now, 'en')).toBe('2 minutes ago');
    expect(activityTime('Last week', now, 'en')).toBe('Last week');
    expect(timeOf('May 5')).toBeNaN();
  });

  it('speaks the reader’s language', () => {
    expect(activityTime(now - 5 * 60_000, now, 'es')).toBe('hace 5 minutos');
  });
});

describe('menu tree', () => {
  const tree = [
    {
      id: 'appearance',
      label: 'Appearance',
      children: [
        { id: 'theme', label: 'Theme', keywords: ['dark mode'], children: [{ id: 'dark', label: 'Dark' }] },
        { id: 'density', label: 'Density' },
      ],
    },
    { id: 'language', label: 'Language', children: [{ id: 'fa', label: 'فارسی' }, { id: 'es', label: 'Español' }] },
    { id: 'help', label: 'Help' },
  ];

  it('walks a path of ids', () => {
    expect(menuPath(tree, ['appearance', 'theme']).map((item) => item.id)).toEqual(['appearance', 'theme']);
    expect(menuPath(tree, ['appearance', 'missing', 'dark']).map((item) => item.id)).toEqual(['appearance']);
    expect(menuPath(tree, [])).toEqual([]);
  });

  it('finds items at every depth with the trail that leads to them', () => {
    const [match] = searchMenuTree(tree, 'dark');
    expect(match!.item.id).toBe('theme');
    expect(searchMenuTree(tree, 'dark').map((m) => m.item.id)).toEqual(['theme', 'dark']);
    expect(searchMenuTree(tree, 'dark')[1]!.trail.map((item) => item.id)).toEqual(['appearance', 'theme']);
  });

  it('ignores case and accents, and returns nothing for an empty query', () => {
    expect(searchMenuTree(tree, 'ESPANOL').map((m) => m.item.id)).toEqual(['es']);
    expect(searchMenuTree(tree, '   ')).toEqual([]);
  });

  it('folds Arabic letter variants into their Persian forms', () => {
    expect(foldSearchText('فارسي')).toBe(foldSearchText('فارسی'));
    expect(searchMenuTree(tree, 'فارسي').map((m) => m.item.id)).toEqual(['fa']);
    expect(foldSearchText('Türkçe')).toBe('turkce');
  });
});

describe('tickets', () => {
  it('adds quantity × price over the ticket types', () => {
    const tickets = [
      { id: 'general', price: 49 },
      { id: 'vip', price: 149 },
      { id: 'student', price: 0 },
    ];
    expect(ticketTotal(tickets, { general: 2, vip: 1, student: 1 })).toBe(247);
    expect(ticketTotal(tickets, {})).toBe(0);
  });
});

describe('levels', () => {
  it('measures silence as zero and a loud signal near one', () => {
    expect(rmsLevel(new Uint8Array(64).fill(128))).toBe(0);
    const loud = Uint8Array.from({ length: 64 }, (_, i) => (i % 2 ? 255 : 0));
    expect(rmsLevel(loud)).toBeGreaterThan(0.95);
    expect(rmsLevel([])).toBe(0);
  });

  it('simulates speech within 0–1, the same way every time', () => {
    const take = Array.from({ length: 400 }, (_, i) => voiceLevel(i / 20, 3));
    expect(Math.min(...take)).toBeGreaterThanOrEqual(0);
    expect(Math.max(...take)).toBeLessThanOrEqual(1);
    // Words and pauses: the level really moves.
    expect(Math.max(...take) - Math.min(...take)).toBeGreaterThan(0.4);
    expect(voiceLevel(1.234, 3)).toBe(voiceLevel(1.234, 3));
  });

  it('summarises a take as its loudest moments', () => {
    expect(downsampleLevels([0.1, 0.9, 0.2, 0.4], 2)).toEqual([0.9, 0.4]);
    expect(downsampleLevels([0.5], 3)).toEqual([0.5, 0.5, 0.5]);
    expect(downsampleLevels([], 2)).toEqual([0, 0]);
    expect(downsampleLevels([0.3, 0.6], 0)).toEqual([]);
  });
});

describe('directional hover nav geometry', () => {
  const rect = { left: 0, top: 0, right: 100, bottom: 40 };

  it('names the edge the pointer arrived through', () => {
    expect(entrySide(rect, 50, -4)).toBe('top');
    expect(entrySide(rect, 50, 44)).toBe('bottom');
    expect(entrySide(rect, -6, 20)).toBe('left');
    expect(entrySide(rect, 106, 20)).toBe('right');
  });

  it('breaks corner ties toward the horizontal edges (a dropdown under a top bar)', () => {
    expect(entrySide(rect, -6, -4)).toBe('top');
    expect(entrySide(rect, 106, 44)).toBe('bottom');
  });

  it('aims the exit of one item at the item the pointer hopped to', () => {
    expect(facingSide(rect, { left: 140, top: 0, right: 240, bottom: 40 })).toBe('right');
    expect(facingSide(rect, { left: -100, top: 0, right: -20, bottom: 40 })).toBe('left');
    expect(facingSide(rect, { left: 30, top: 80, right: 130, bottom: 120 })).toBe('bottom');
    expect(facingSide(rect, { left: 30, top: -80, right: 130, bottom: -40 })).toBe('top');
  });
});
