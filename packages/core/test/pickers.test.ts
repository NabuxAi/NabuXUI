// @vitest-environment happy-dom
import { describe, expect, it } from 'vitest';
import {
  calendarMonthLength,
  calendarParts,
  clockText,
  comboEdge,
  comboMatches,
  comboSegments,
  comboStep,
  ctxMenuPoint,
  defaultCalendar,
  from12h,
  isoAddDays,
  isoDaysBetween,
  minuteStops,
  monthStartOf,
  nearestMinute,
  parseClock,
  pickerWord,
  rangeClick,
  rangeMark,
  rangeMonthWeeks,
  rangePreset,
  sheetRelease,
  sheetRubber,
  shiftCalendarMonth,
  snapNumber,
  stepDecimals,
  tagDraft,
  tagIndex,
  to12h,
} from '../src/js/blocks/pickers';

const cities = [
  { value: 'thr', label: 'Tehran', keywords: ['تهران'] },
  { value: 'shz', label: 'Shiraz' },
  { value: 'ber', label: 'Berlin' },
  { value: 'nyc', label: 'New York', keywords: ['NYC'] },
  { value: 'yaz', label: 'یزد' },
];

describe('combobox', () => {
  it('filters, ranking label starts first', () => {
    expect(comboMatches(cities, '')).toEqual([0, 1, 2, 3, 4]);
    expect(comboMatches(cities, 'n')).toEqual([3, 0, 2]);
    expect(comboMatches(cities, 'nyc')).toEqual([3]);
    expect(comboMatches(cities, 'york')).toEqual([3]);
    expect(comboMatches(cities, 'تهران')).toEqual([0]);
  });

  it('folds Arabic letter variants', () => {
    expect(comboMatches(cities, 'يزد')).toEqual([4]);
  });

  it('marks every match', () => {
    expect(comboSegments('Berlin', 'er')).toEqual([
      { text: 'B', match: false },
      { text: 'er', match: true },
      { text: 'lin', match: false },
    ]);
    expect(comboSegments('New York', 'new yo')).toEqual([
      { text: 'New', match: true },
      { text: ' ', match: false },
      { text: 'Yo', match: true },
      { text: 'rk', match: false },
    ]);
    expect(comboSegments('Tehran', '')).toEqual([{ text: 'Tehran', match: false }]);
  });

  it('steps over disabled options and wraps', () => {
    const off = (i: number) => i === 1;
    expect(comboStep([0, 1, 2], 0, 1, off)).toBe(2);
    expect(comboStep([0, 1, 2], 2, 1, off)).toBe(0);
    expect(comboStep([0, 1, 2], -1, -1, off)).toBe(2);
    expect(comboStep([], -1, 1)).toBe(-1);
    expect(comboEdge([3, 4, 5], 'last', (i) => i === 5)).toBe(4);
  });
});

describe('tag input', () => {
  it('commits on separators and keeps the tail', () => {
    expect(tagDraft('design, motion,ui', [])).toEqual({ added: ['design', 'motion'], rest: 'ui', duplicates: [], overflow: [] });
    expect(tagDraft('طراحی، حرکت', [], true).added).toEqual(['طراحی', 'حرکت']);
  });

  it('guards duplicates and the limit', () => {
    const r = tagDraft('Design, a, b, c', ['design'], true, { max: 3 });
    expect(r.added).toEqual(['a', 'b']);
    expect(r.duplicates).toEqual(['Design']);
    expect(r.overflow).toEqual(['c']);
    expect(tagIndex(['Alpha', 'Beta'], ' beta ')).toBe(1);
  });
});

describe('context menu point', () => {
  const vp = { width: 800, height: 600 };
  const size = { width: 200, height: 150 };
  it('opens at the pointer, flipping near edges', () => {
    expect(ctxMenuPoint({ x: 100, y: 100 }, size, vp)).toEqual({ x: 100, y: 100, originX: 0, originY: 0 });
    expect(ctxMenuPoint({ x: 750, y: 580 }, size, vp)).toEqual({ x: 550, y: 430, originX: 200, originY: 150 });
  });
  it('opens toward the reading start in RTL', () => {
    expect(ctxMenuPoint({ x: 400, y: 100 }, size, vp, 8, -1).x).toBe(200);
    expect(ctxMenuPoint({ x: 50, y: 100 }, size, vp, 8, -1).x).toBe(50);
  });
});

describe('bottom sheet', () => {
  const snaps = [200, 400, 720];
  it('rubber-bands toward a limit', () => {
    expect(sheetRubber(0, 48)).toBe(0);
    expect(sheetRubber(100, 48)).toBeLessThan(48);
    expect(sheetRubber(1000, 48)).toBeGreaterThan(sheetRubber(100, 48));
  });
  it('settles on the nearest snap when slow', () => {
    expect(sheetRelease({ height: 380, snaps, velocity: 0 })).toBe(1);
    expect(sheetRelease({ height: 620, snaps, velocity: 0 })).toBe(2);
    expect(sheetRelease({ height: 60, snaps, velocity: 0 })).toBe(-1);
    expect(sheetRelease({ height: 60, snaps, velocity: 0, dismissible: false })).toBe(0);
  });
  it('a flick moves one snap even when short', () => {
    expect(sheetRelease({ height: 390, snaps, velocity: 0.2 })).toBe(0);
    expect(sheetRelease({ height: 410, snaps, velocity: -0.2 })).toBe(2);
    expect(sheetRelease({ height: 195, snaps, velocity: 0.5 })).toBe(-1);
  });
});

describe('dates', () => {
  it('does day math in UTC', () => {
    expect(isoAddDays('2026-02-28', 1)).toBe('2026-03-01');
    expect(isoDaysBetween('2026-01-01', '2026-03-01')).toBe(59);
  });

  it('builds Gregorian months', () => {
    expect(monthStartOf('2026-10-03')).toBe('2026-10-01');
    expect(calendarMonthLength('2026-02-01')).toBe(28);
    expect(shiftCalendarMonth('2026-01-31', 1)).toBe('2026-02-01');
    expect(shiftCalendarMonth('2026-01-15', -2)).toBe('2025-11-01');
    const weeks = rangeMonthWeeks('2026-10-15', 'gregory', 0);
    // 1 October 2026 is a Thursday.
    expect(weeks[0]).toEqual([null, null, null, null, '2026-10-01', '2026-10-02', '2026-10-03']);
    expect(weeks.flat().filter(Boolean)).toHaveLength(31);
  });

  it('builds Jalali months through Intl', () => {
    // 1 Mehr 1405 = 23 September 2026.
    expect(calendarParts('2026-09-23', 'persian')).toEqual({ year: 1405, month: 7, day: 1 });
    expect(monthStartOf('2026-10-03', 'persian')).toBe('2026-09-23');
    expect(calendarMonthLength('2026-09-23', 'persian')).toBe(30);
    // 1 Farvardin is 31 days.
    expect(calendarMonthLength('2026-03-21', 'persian')).toBe(31);
    expect(shiftCalendarMonth('2026-10-03', 1, 'persian')).toBe('2026-10-23');
    const weeks = rangeMonthWeeks('2026-10-03', 'persian', 6);
    expect(weeks.flat().filter(Boolean)).toHaveLength(30);
    expect(defaultCalendar('fa-IR')).toBe('persian');
    expect(defaultCalendar('en')).toBe('gregory');
  });

  it('marks and clicks ranges', () => {
    expect(rangeMark('2026-10-05', '2026-10-03', '2026-10-08')).toBe('middle');
    expect(rangeMark('2026-10-08', '2026-10-08', '2026-10-03')).toBe('end');
    expect(rangeMark('2026-10-03', '2026-10-03', null)).toBe('single');
    expect(rangeClick({ start: null, end: null }, '2026-10-05')).toEqual({ start: '2026-10-05', end: null });
    expect(rangeClick({ start: '2026-10-05', end: null }, '2026-10-01')).toEqual({ start: '2026-10-01', end: '2026-10-05' });
    expect(rangeClick({ start: '2026-10-01', end: '2026-10-05' }, '2026-10-09')).toEqual({ start: '2026-10-09', end: null });
  });

  it('resolves presets', () => {
    expect(rangePreset('last7', '2026-10-03')).toEqual({ start: '2026-09-27', end: '2026-10-03' });
    expect(rangePreset('lastMonth', '2026-10-03')).toEqual({ start: '2026-09-01', end: '2026-09-30' });
    expect(rangePreset('thisMonth', '2026-10-03', 'persian')).toEqual({ start: '2026-09-23', end: '2026-10-03' });
  });
});

describe('time', () => {
  it('parses and prints the clock', () => {
    expect(parseClock('09:05')).toEqual({ hour: 9, minute: 5 });
    expect(parseClock('۱۴:۳۰')).toEqual({ hour: 14, minute: 30 });
    expect(parseClock('25:00')).toBeNull();
    expect(clockText({ hour: 7, minute: 3 })).toBe('07:03');
  });
  it('converts 12 and 24 hours', () => {
    expect(to12h(0)).toEqual({ hour: 12, pm: false });
    expect(to12h(13)).toEqual({ hour: 1, pm: true });
    expect(from12h(12, false)).toBe(0);
    expect(from12h(12, true)).toBe(12);
    expect(from12h(7, true)).toBe(19);
  });
  it('steps minutes', () => {
    expect(minuteStops(15)).toEqual([0, 15, 30, 45]);
    expect(nearestMinute(38, 15)).toBe(45);
  });
});

describe('numbers', () => {
  it('snaps onto the step grid and clamps', () => {
    expect(stepDecimals(0.25)).toBe(2);
    expect(snapNumber(0.1 + 0.2, { step: 0.1 })).toBe(0.3);
    expect(snapNumber(7, { min: 1, max: 10, step: 2 })).toBe(7);
    expect(snapNumber(8, { min: 1, max: 10, step: 2 })).toBe(9);
    expect(snapNumber(42, { min: 0, max: 10 })).toBe(10);
    expect(snapNumber(Number.NaN, { min: 3 })).toBe(3);
  });
});

describe('words', () => {
  it('speaks the locale, with params', () => {
    expect(pickerWord('fa-IR', 'remove', { name: 'x' })).toBe('حذف x');
    expect(pickerWord('de', 'clear')).toBe('Clear');
  });
});
