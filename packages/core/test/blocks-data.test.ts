import { describe, expect, it } from 'vitest';
import {
  branchPath,
  compareValues,
  convertCurrency,
  dataWord,
  dotStack,
  dotUnit,
  exchangeRate,
  formatCell,
  heatLevel,
  heatmapWeeks,
  nextSort,
  parseAmount,
  pathShape,
  snakePath,
  sortRows,
  statusRank,
} from '../src/js/blocks/data';
import { linePath } from '../src/js/charts';

describe('data table sorting', () => {
  const rows = [
    { id: 'a', name: 'Zürich', score: 10, status: 'success' },
    { id: 'b', name: 'item 10', score: 2, status: 'failed' },
    { id: 'c', name: 'item 2', score: null, status: 'running' },
    { id: 'd', name: 'Ålesund', score: 10, status: 'queued' },
  ];

  it('sorts numbers numerically and keeps equal rows in order', () => {
    expect(sortRows(rows, 'score', 'ascending').map((r) => r.id)).toEqual(['b', 'a', 'd', 'c']);
    expect(sortRows(rows, 'score', 'descending').map((r) => r.id)).toEqual(['a', 'd', 'b', 'c']);
  });

  it('puts empty values last in both directions', () => {
    expect(sortRows(rows, 'score', 'ascending').at(-1)!.id).toBe('c');
    expect(sortRows(rows, 'score', 'descending').at(-1)!.id).toBe('c');
  });

  it('uses numeric collation for text', () => {
    expect(sortRows(rows, 'name', 'ascending', { locale: 'en' }).map((r) => r.name)).toEqual(['Ålesund', 'item 2', 'item 10', 'Zürich']);
    expect(compareValues('2', '10')).toBeLessThan(0);
  });

  it('orders statuses by urgency and cycles the direction', () => {
    expect(sortRows(rows, 'status', 'ascending', { value: (r) => statusRank(r.status) }).map((r) => r.status)).toEqual(['failed', 'running', 'queued', 'success']);
    expect(nextSort(null, 'name')).toEqual({ key: 'name', direction: 'ascending' });
    expect(nextSort({ key: 'name', direction: 'ascending' }, 'name').direction).toBe('descending');
    expect(nextSort({ key: 'name', direction: 'descending' }, 'score')).toEqual({ key: 'score', direction: 'ascending' });
  });

  it('formats cells with the named presets', () => {
    expect(formatCell(1234.5, 'number', 'en-US')).toBe('1,234.5');
    expect(formatCell(0.425, 'percent', 'en-US')).toBe('42.5%');
    expect(formatCell(12, 'currency:eur', 'de-DE')).toContain('€');
    expect(formatCell('2026-09-01', 'date', 'en-US')).toContain('2026');
    expect(formatCell(null, 'number')).toBe('—');
    expect(formatCell('plain', undefined)).toBe('plain');
  });
});

describe('heatmap', () => {
  it('quantises values into four levels above zero', () => {
    expect(heatLevel(0, 10)).toBe(0);
    expect(heatLevel(1, 10)).toBe(1);
    expect(heatLevel(5, 10)).toBe(2);
    expect(heatLevel(10, 10)).toBe(4);
    expect(heatLevel(3, 0)).toBe(0);
  });

  it('lays days out in week columns from the week start', () => {
    // 2026-09-01 is a Tuesday.
    const layout = heatmapWeeks(
      [
        { date: '2026-09-01', value: 4 },
        { date: '2026-09-02', value: 8 },
        { date: '2026-09-08', value: 2 },
      ],
      { weekStart: 0 },
    );
    expect(layout.weeks).toHaveLength(2);
    expect(layout.weeks[0]![0]).toBeNull(); // the Sunday before the range
    expect(layout.weeks[0]![2]).toMatchObject({ date: '2026-09-01', value: 4, row: 2, col: 0 });
    expect(layout.weeks[1]![2]).toMatchObject({ date: '2026-09-08', value: 2, col: 1 });
    expect(layout.weeks[1]![3]).toBeNull(); // after the last day
    expect(layout.max).toBe(8);
    expect(layout.total).toBe(14);
    expect(layout.months[0]).toEqual({ col: 0, date: '2026-09-01' });
  });

  it('follows the reader’s calendar for month labels when asked', () => {
    // 1 Mehr 1405 (Persian calendar) is 2026-09-23.
    const persianDay = new Intl.DateTimeFormat('fa-IR', { day: 'numeric', timeZone: 'UTC', numberingSystem: 'latn' });
    const layout = heatmapWeeks([{ date: '2026-09-26', value: 1 }], {
      weeks: 3,
      weekStart: 6,
      monthStart: (date) => persianDay.format(new Date(`${date}T00:00:00Z`)) === '1',
    });
    expect(layout.months.map((m) => m.date)).toContain('2026-09-23');
  });

  it('shows exactly the requested number of weeks and fills empty days with zero', () => {
    const layout = heatmapWeeks([{ date: '2026-09-26', value: 3 }], { weeks: 4, weekStart: 1 });
    expect(layout.weeks).toHaveLength(4);
    expect(layout.weeks[0]!.every((cell) => cell && cell.value === 0)).toBe(true);
    // Monday-first: Saturday the 26th is row 5.
    expect(layout.weeks[3]![5]).toMatchObject({ date: '2026-09-26', level: 4 });
  });
});

describe('dot matrix', () => {
  it('picks a round value per dot so the tallest column fits', () => {
    expect(dotUnit(87, 10)).toBe(10);
    expect(dotUnit(24, 12)).toBe(2);
    expect(dotUnit(0, 10)).toBe(1);
  });

  it('stacks series in order and keeps the total true with largest remainders', () => {
    expect(dotStack([3, 2], 1, 8)).toEqual([0, 0, 0, 1, 1, null, null, null]);
    // 1.6 + 1.7 + 0.7 = 4 dots: floors give 2, the two largest remainders win the spares.
    expect(dotStack([16, 17, 7], 10, 6)).toEqual([0, 1, 1, 2, null, null]);
    expect(dotStack([15, 14, 12], 10, 6)).toEqual([0, 0, 1, 2, null, null]);
    expect(dotStack([50], 1, 5)).toEqual([0, 0, 0, 0, 0]);
  });
});

describe('currency', () => {
  const rates = { USD: 1, EUR: 0.92, JPY: 149.5 };

  it('converts through the common base', () => {
    expect(exchangeRate('USD', 'EUR', rates)).toBeCloseTo(0.92);
    expect(exchangeRate('EUR', 'JPY', rates)).toBeCloseTo(162.5, 1);
    expect(convertCurrency(100, 'EUR', 'USD', rates)).toBeCloseTo(108.7, 1);
    expect(exchangeRate('USD', 'XXX', rates)).toBeNaN();
  });

  it('reads typed amounts in any convention and numbering system', () => {
    expect(parseAmount('1,234.56', 'en-US')).toBe(1234.56);
    expect(parseAmount('1.234,56', 'de-DE')).toBe(1234.56);
    expect(parseAmount('۱۲٬۳۴۵٫۶', 'fa-IR')).toBe(12345.6);
    expect(parseAmount('١٢٣', 'ar')).toBe(123);
    expect(parseAmount('1,5', 'en-US')).toBe(1.5);
    expect(parseAmount('1,500', 'en-US')).toBe(1500);
    expect(parseAmount('€ 12 500,75', 'fr-FR')).toBe(12500.75);
    expect(parseAmount('', 'en')).toBeNaN();
    expect(parseAmount('abc')).toBeNaN();
  });
});

describe('paths', () => {
  it('curves links level for side branches and downward for stacked ones', () => {
    expect(branchPath([0, 10], [100, 50])).toBe('M0,10C55,10 45,50 100,50');
    expect(branchPath([50, 0], [10, 100], 'vertical', 0.5)).toBe('M50,0C50,50 10,50 10,100');
  });

  it('snakes through every point with stops from 0 to 1', () => {
    const { d, stops } = snakePath([[50, 0], [50, 100], [50, 300], [50, 360]], { amplitude: 20 });
    expect(d.startsWith('M50,0C')).toBe(true);
    expect(pathShape(d)).toBe('MCCC');
    expect(stops[0]).toBe(0);
    expect(stops.at(-1)).toBe(1);
    for (let i = 1; i < stops.length; i++) expect(stops[i]!).toBeGreaterThan(stops[i - 1]!);
    // The taller middle segment carries the larger share of the length.
    expect(stops[2]! - stops[1]!).toBeGreaterThan(stops[1]! - stops[0]!);
    expect(snakePath([]).d).toBe('');
  });

  it('mirrors the swing in right-to-left layouts', () => {
    const ltr = snakePath([[0, 0], [0, 100]], { amplitude: 20, dir: 1 }).d;
    const rtl = snakePath([[0, 0], [0, 100]], { amplitude: 20, dir: -1 }).d;
    expect(ltr).toContain('C20,30');
    expect(rtl).toContain('C-20,30');
  });

  it('gives lines of the same length the same command shape, so they can morph', () => {
    const a = linePath([[0, 10], [10, 20], [20, 5], [30, 8]]);
    const b = linePath([[0, 40], [10, 2], [20, 15], [30, 30]]);
    expect(pathShape(a)).toBe(pathShape(b));
    expect(pathShape(a)).not.toBe(pathShape(linePath([[0, 1], [1, 2], [2, 3]])));
  });
});

describe('words', () => {
  it('speaks the blocks’ own words with parameters, falling back to English', () => {
    expect(dataWord('fa', 'less')).toBe('کمتر');
    expect(dataWord('en', 'usedOf', { used: '3 GB', limit: '5 GB' })).toBe('3 GB of 5 GB used');
    expect(dataWord('de-DE', 'swap')).toBe('Swap currencies');
    expect(dataWord(undefined, 'rate', { from: 'USD', rate: '0.92', to: 'EUR' })).toBe('1 USD = 0.92 EUR');
  });
});
