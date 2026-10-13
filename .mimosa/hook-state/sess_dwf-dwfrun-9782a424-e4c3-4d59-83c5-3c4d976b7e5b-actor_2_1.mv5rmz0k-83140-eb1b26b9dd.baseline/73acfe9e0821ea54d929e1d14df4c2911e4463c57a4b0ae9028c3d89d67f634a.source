import { describe, expect, it, vi } from 'vitest';
import { springAt, springEasing, springs } from '../src/js/spring';
import { areaPath, barPath, bands, donutSegments, linePath, linearScale, nearestIndex, niceTicks } from '../src/js/charts';
import { computePlacement } from '../src/js/place';
import { nextIndex } from '../src/js/roving';
import { hasJoiningScript, numberParts, splitText, textDirection } from '../src/js/text';
import { ToastStore } from '../src/js/toast';
import { translate } from '../src/js/i18n';
import { iconSvg, icons } from '../src/js/icons';

describe('springs', () => {
  it('start at 0 and settle at 1', () => {
    for (const config of Object.values(springs)) {
      expect(springAt(0, config)).toBeCloseTo(0, 5);
      expect(springAt(5, config)).toBeCloseTo(1, 3);
    }
  });

  it('become a compact linear() easing with a duration', () => {
    const { easing, duration } = springEasing(springs.gentle);
    expect(easing).toMatch(/^linear\(0, .*, 1\)$/);
    expect(easing.split(',').length).toBeLessThan(45);
    expect(duration).toBeGreaterThan(300);
    expect(duration).toBeLessThan(1500);
  });

  it('bouncy overshoots and soft does not', () => {
    const peak = (config: { stiffness: number; damping: number }) => Math.max(...Array.from({ length: 300 }, (_, i) => springAt(i / 150, config)));
    expect(peak(springs.bouncy)).toBeGreaterThan(1.1);
    expect(peak(springs.soft)).toBeLessThanOrEqual(1.0005);
  });
});

describe('chart geometry', () => {
  it('picks round ticks', () => {
    expect(niceTicks(0, 97, 5)).toEqual([0, 20, 40, 60, 80, 100]);
    expect(niceTicks(0, 0)).toEqual([0, 1]);
  });

  it('rounds a bar only at its data end', () => {
    const up = barPath(10, 20, 40, 100, 4);
    expect(up.startsWith('M10,100')).toBe(true);
    expect(up).toContain('Q10,40');
    const flat = barPath(10, 20, 100, 100, 4);
    expect(flat).toBe('M10,100H30V100H10Z');
  });

  it('caps bars at 24px and centres them in their band', () => {
    const [first] = bands(4, 400);
    expect(first!.width).toBe(24);
    expect(first!.x + first!.width / 2).toBe(first!.center);
    expect(first!.bandWidth).toBe(100);
  });

  it('never overshoots with monotone smoothing', () => {
    const d = linePath([[0, 50], [10, 50], [20, 0], [30, 0]]);
    const ys = Array.from(d.matchAll(/-?\d+(?:\.\d+)?,(-?\d+(?:\.\d+)?)/g), (m) => Number(m[1]));
    expect(Math.min(...ys)).toBeGreaterThanOrEqual(0);
    expect(Math.max(...ys)).toBeLessThanOrEqual(50);
    expect(areaPath([[0, 10], [10, 20]], 50)).toMatch(/Z$/);
  });

  it('splits a ring into gapped segments that start where the last ended', () => {
    const [a, b] = donutSegments([75, 25], 1);
    expect(a!.length).toBe(74);
    expect(b!.offset).toBe(-75);
    expect(donutSegments([10])[0]!.length).toBe(100);
  });

  it('scales and finds the nearest point', () => {
    const x = linearScale([0, 10], [0, 200]);
    expect(x(5)).toBe(100);
    expect(x.invert(50)).toBe(2.5);
    expect(nearestIndex([0, 50, 100], 70)).toBe(1);
  });
});

describe('placement', () => {
  const viewport = { width: 1000, height: 800 };
  const anchor = { left: 100, top: 100, width: 80, height: 40 };

  it('places below and aligns to the reading start', () => {
    expect(computePlacement(anchor, { width: 200, height: 100 }, viewport, { side: 'bottom', align: 'start', offset: 8 })).toEqual({ x: 100, y: 148, side: 'bottom' });
    // In RTL "start" is the anchor's right edge.
    expect(computePlacement(anchor, { width: 200, height: 100 }, viewport, { side: 'bottom', align: 'start', offset: 8 }, -1).x).toBe(8);
  });

  it('flips when the preferred side does not fit', () => {
    const low = { ...anchor, top: 740 };
    expect(computePlacement(low, { width: 200, height: 100 }, viewport, { side: 'bottom' }).side).toBe('top');
  });

  it('maps logical sides through the direction', () => {
    expect(computePlacement(anchor, { width: 50, height: 20 }, viewport, { side: 'inline-end' }, 1).side).toBe('right');
    expect(computePlacement({ ...anchor, left: 500 }, { width: 50, height: 20 }, viewport, { side: 'inline-end' }, -1).side).toBe('left');
  });
});

describe('roving focus', () => {
  it('follows the reading direction for horizontal arrows', () => {
    expect(nextIndex('ArrowRight', 0, 3, {}, 1)).toBe(1);
    expect(nextIndex('ArrowRight', 0, 3, {}, -1)).toBe(2);
    expect(nextIndex('ArrowDown', 0, 3, { orientation: 'horizontal' })).toBeNull();
    expect(nextIndex('End', 0, 3)).toBe(2);
    expect(nextIndex('ArrowUp', 0, 3, { orientation: 'vertical', loop: false })).toBe(0);
  });
});

describe('text', () => {
  it('never splits Persian letters apart', () => {
    expect(hasJoiningScript('سلام دنیا')).toBe(true);
    expect(splitText('سلام دنیا', 'char')).toEqual(['سلام ', 'دنیا']);
    expect(splitText('Hi you', 'char')).toEqual(['H', 'i', ' ', 'y', 'o', 'u']);
    expect(splitText('one two', 'word')).toEqual(['one ', 'two']);
  });

  it('keeps a run of the other direction together so its words stay in order', () => {
    expect(textDirection('سلام world')).toBe('rtl');
    expect(textDirection('Hello دنیا')).toBe('ltr');
    expect(splitText('ساخته با Nabux Desk برای شما')).toEqual(['ساخته ', 'با ', 'Nabux Desk ', 'برای ', 'شما']);
    expect(splitText('Built for نبو دسک today')).toEqual(['Built ', 'for ', 'نبو دسک ', 'today']);
    // CJK reads left to right: inside Arabic text a Japanese phrase stays one piece.
    expect(textDirection('日本語 عربي')).toBe('ltr');
    expect(splitText('مرحبا 你好 世界 يا')).toEqual(['مرحبا ', '你好 世界 ', 'يا']);
  });

  it('breaks numbers into digit columns in the locale numbering system', () => {
    const parts = numberParts(1284, 'fa-IR');
    const digits = parts.filter((p) => p.kind === 'digit');
    expect(digits.map((p) => p.value)).toEqual([1, 2, 8, 4]);
    expect(digits[0]!.char).toBe('۱');
    expect(parts.some((p) => p.kind === 'static')).toBe(true);
  });
});

describe('toast store', () => {
  it('adds newest first, dismisses and removes', () => {
    vi.useFakeTimers();
    const store = new ToastStore();
    const first = store.show({ title: 'One', duration: 1000 });
    store.show({ title: 'Two', duration: 0 });
    expect(store.getSnapshot().map((t) => t.title)).toEqual(['Two', 'One']);

    vi.advanceTimersByTime(1000);
    expect(store.getSnapshot().find((t) => t.id === first)!.state).toBe('closing');
    store.remove(first);
    expect(store.getSnapshot()).toHaveLength(1);
    vi.useRealTimers();
  });

  it('holds timers while paused', () => {
    vi.useFakeTimers();
    const store = new ToastStore();
    const id = store.show({ title: 'Wait', duration: 1000 });
    store.pause();
    vi.advanceTimersByTime(5000);
    expect(store.getSnapshot()[0]!.state).toBe('open');
    store.resume();
    vi.advanceTimersByTime(1300);
    expect(store.getSnapshot().find((t) => t.id === id)!.state).toBe('closing');
    vi.useRealTimers();
  });

  it('replaces a toast shown again with the same id', () => {
    const store = new ToastStore();
    store.show({ id: 'save', title: 'Saving…', duration: 0 });
    store.show({ id: 'save', title: 'Saved', tone: 'success' });
    expect(store.getSnapshot()).toHaveLength(1);
    expect(store.getSnapshot()[0]!.title).toBe('Saved');
  });
});

describe('i18n and icons', () => {
  it('translates with parameters and falls back to English', () => {
    expect(translate('fa', 'page', { page: 3 })).toBe('صفحهٔ 3');
    expect(translate('xx', 'close')).toBe('Close');
  });

  it('renders decorative icons hidden and labelled ones named', () => {
    expect(iconSvg('check')).toContain('aria-hidden="true"');
    expect(iconSvg('check', { label: 'Done' })).toContain('aria-label="Done"');
    expect(iconSvg('arrow-right')).toContain('data-directional');
    expect(Object.keys(icons).length).toBeGreaterThan(40);
  });
});
