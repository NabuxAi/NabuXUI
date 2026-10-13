// @vitest-environment happy-dom
import { describe, expect, it } from 'vitest';
import {
  isPaneCollapsed,
  islandRadius,
  normalizePaneSizes,
  readingProgress,
  resizePaneBoundary,
  spotlightRect,
  togglePaneCollapse,
  tourNeighbours,
  tourStepInfo,
  typewriterSteps,
  typewriterUnits,
} from '../src/js/blocks/effects';

const sum = (values: number[]) => Math.round(values.reduce((a, b) => a + b, 0) * 1000) / 1000;
const take = <T>(iterator: Iterator<T>, count: number): T[] => {
  const out: T[] = [];
  for (let i = 0; i < count; i++) {
    const next = iterator.next();
    if (next.done) break;
    out.push(next.value);
  }
  return out;
};

describe('normalizePaneSizes', () => {
  it('shares the space left over between panes without a size', () => {
    expect(normalizePaneSizes([30, undefined, undefined])).toEqual([30, 35, 35]);
    expect(normalizePaneSizes([undefined, undefined])).toEqual([50, 50]);
  });

  it('scales sizes that do not add up to 100', () => {
    const sizes = normalizePaneSizes([20, 20]);
    expect(sum(sizes)).toBe(100);
    expect(sizes).toEqual([50, 50]);
  });

  it('respects limits and hands the rest to panes with room', () => {
    const sizes = normalizePaneSizes([10, 10, 10], [{ max: 20 }, {}, { min: 30, max: 40 }]);
    expect(sum(sizes)).toBe(100);
    expect(sizes[0]).toBeLessThanOrEqual(20);
    expect(sizes[2]).toBeGreaterThanOrEqual(30);
    expect(sizes[2]).toBeLessThanOrEqual(40);
  });

  it('keeps a collapsed pane collapsed', () => {
    const constraints = [{ min: 20, collapsible: true }, {}];
    expect(normalizePaneSizes([0, 80], constraints)).toEqual([0, 100]);
    expect(isPaneCollapsed(0, constraints[0])).toBe(true);
    expect(isPaneCollapsed(0, {})).toBe(false);
  });
});

describe('resizePaneBoundary', () => {
  it('moves the boundary between two neighbours only', () => {
    expect(resizePaneBoundary([30, 40, 30], 0, 10)).toEqual([40, 30, 30]);
    expect(resizePaneBoundary([30, 40, 30], 1, -5)).toEqual([30, 35, 35]);
  });

  it('clamps to both panes’ limits', () => {
    const c = [{ min: 20, max: 60 }, { min: 25 }];
    expect(resizePaneBoundary([40, 60], 0, 50, c)).toEqual([60, 40]);
    expect(resizePaneBoundary([40, 60], 0, -50, c)).toEqual([20, 80]);
    expect(resizePaneBoundary([50, 50], 0, 40, [{}, { min: 25 }])).toEqual([75, 25]);
  });

  it('snaps a collapsible pane shut under half its minimum, and open past it', () => {
    const c = [{ min: 20, collapsible: true }, {}];
    expect(resizePaneBoundary([30, 70], 0, -15, c)).toEqual([20, 80]);
    expect(resizePaneBoundary([30, 70], 0, -25, c)).toEqual([0, 100]);
    expect(resizePaneBoundary([0, 100], 0, 5, c)).toEqual([0, 100]);
    expect(resizePaneBoundary([0, 100], 0, 12, c)).toEqual([20, 80]);
  });

  it('collapses the second pane when it is the collapsible one', () => {
    const c = [{}, { min: 20, collapsible: true }];
    expect(resizePaneBoundary([70, 30], 0, 25, c)).toEqual([100, 0]);
  });

  it('ignores a handle outside the group', () => {
    expect(resizePaneBoundary([50, 50], 1, 10)).toEqual([50, 50]);
  });
});

describe('togglePaneCollapse', () => {
  const c = [{ min: 15, collapsible: true }, {}, { min: 10, collapsible: true }];
  it('collapses into the next pane and restores the remembered size', () => {
    const shut = togglePaneCollapse([25, 50, 25], 0, c);
    expect(shut).toEqual([0, 75, 25]);
    expect(togglePaneCollapse(shut, 0, c, 25)).toEqual([25, 50, 25]);
  });

  it('collapses the last pane into the previous one', () => {
    expect(togglePaneCollapse([25, 50, 25], 2, c)).toEqual([25, 75, 0]);
  });

  it('leaves panes that cannot collapse alone', () => {
    expect(togglePaneCollapse([25, 50, 25], 1, c)).toEqual([25, 50, 25]);
  });
});

describe('typewriterUnits', () => {
  it('types Latin and emoji by user-perceived character', () => {
    expect(typewriterUnits('Hi 👋🏽')).toEqual(['H', 'i', ' ', '👋🏽']);
  });

  it('keeps a zero-width non-joiner with the Persian letter before it', () => {
    const units = typewriterUnits('می‌شود');
    expect(units.join('')).toBe('می‌شود');
    expect(units.some((unit) => unit === '‌')).toBe(false);
  });

  it('can type word by word', () => {
    expect(typewriterUnits('با هر زبانی', 'word')).toEqual(['با ', 'هر ', 'زبانی']);
  });
});

describe('typewriterSteps', () => {
  it('types, holds and stops on the last phrase without a loop', () => {
    const steps = Array.from(typewriterSteps(['abc'], { loop: false, typeSpeed: 10, hold: 100 }));
    expect(steps.map((s) => s.text)).toEqual(['a', 'ab', 'abc', 'abc']);
    expect(steps.map((s) => s.state)).toEqual(['typing', 'typing', 'typing', 'holding']);
    expect(steps[3]!.delay).toBe(100);
  });

  it('deletes only back to the prefix the next phrase shares', () => {
    const steps = Array.from(typewriterSteps(['We build sites', 'We build apps'], { loop: false }));
    const texts = steps.map((s) => s.text);
    const deleting = steps.filter((s) => s.state === 'deleting').map((s) => s.text);
    expect(deleting[deleting.length - 1]).toBe('We build ');
    expect(texts[texts.length - 1]).toBe('We build apps');
    expect(steps[steps.length - 1]!.phrase).toBe(1);
  });

  it('cycles forever with a loop', () => {
    const steps = take(typewriterSteps(['ab', 'cd'], { loop: true }), 40);
    expect(steps).toHaveLength(40);
    expect(new Set(steps.map((s) => s.phrase))).toEqual(new Set([0, 1]));
    expect(steps.filter((s) => s.state === 'holding').length).toBeGreaterThan(2);
  });

  it('lingers after punctuation', () => {
    const steps = Array.from(typewriterSteps(['a, b'], { loop: false, typeSpeed: 10 }));
    expect(steps.find((s) => s.text === 'a,')!.delay).toBe(50);
  });

  it('yields nothing for no phrases', () => {
    expect(Array.from(typewriterSteps([]))).toEqual([]);
  });
});

describe('tour helpers', () => {
  it('reports where the tour stands', () => {
    expect(tourStepInfo(0, 4)).toEqual({ index: 0, count: 4, first: true, last: false, progress: 0.25 });
    expect(tourStepInfo(9, 4)).toMatchObject({ index: 3, last: true, progress: 1 });
    expect(tourStepInfo(-2, 4).index).toBe(0);
    expect(tourStepInfo(0, 0)).toMatchObject({ count: 0, progress: 0 });
  });

  it('knows the neighbours of a step', () => {
    expect(tourNeighbours(0, 3)).toEqual({ next: 1, back: null });
    expect(tourNeighbours(1, 3)).toEqual({ next: 2, back: 0 });
    expect(tourNeighbours(2, 3)).toEqual({ next: null, back: 1 });
  });

  it('pads the spotlight and keeps it on screen', () => {
    expect(spotlightRect({ left: 100, top: 50, width: 200, height: 40 }, 8, { width: 1000, height: 800 })).toEqual({ x: 92, y: 42, width: 216, height: 56 });
    expect(spotlightRect({ left: -20, top: 790, width: 100, height: 40 }, 8, { width: 1000, height: 800 })).toEqual({ x: 0, y: 782, width: 88, height: 18 });
  });
});

describe('readingProgress and island radius', () => {
  it('measures progress through an element taller than the viewport', () => {
    expect(readingProgress(100, 2000, 800)).toBe(0);
    expect(readingProgress(-600, 2000, 800)).toBe(0.5);
    expect(readingProgress(-1500, 2000, 800)).toBe(1);
    expect(readingProgress(-10, 500, 800)).toBe(1);
  });

  it('rounds a short island into a pill and a tall one into a card', () => {
    expect(islandRadius(36)).toBe(18);
    expect(islandRadius(200)).toBe(32);
  });
});
