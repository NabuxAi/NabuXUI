import { describe, expect, it } from 'vitest';
import {
  brushClamp,
  brushEdge,
  brushPan,
  brushWindow,
  chartBandPath,
  chartColor,
  chartCurvePath,
  chartExtremes,
  chartFillDefs,
  chartFillPaint,
  chartStack,
  chartTween,
  chartsPlusWord,
  composedModel,
  forecastModel,
  hBarPath,
  isRtlLocale,
  lerpChartStack,
  lineModel,
  polarPoint,
  polygonPath,
  radarAngle,
  radarModel,
  radarPoints,
  radialArcPath,
  radialBarModel,
  stackedAreaModel,
  stackedBarModel,
  stepPath,
} from '../src/js/blocks/charts-plus';
import { linePath } from '../src/js/charts';

describe('chartColor', () => {
  it('cycles the seven chart tokens in fixed order', () => {
    expect(chartColor(0)).toBe('var(--nx-chart-1)');
    expect(chartColor(6)).toBe('var(--nx-chart-7)');
    expect(chartColor(7)).toBe('var(--nx-chart-1)');
  });
});

describe('words', () => {
  it('speaks fa, ar and falls back to English', () => {
    expect(chartsPlusWord('fa', 'forecast')).toBe('پیش‌بینی');
    expect(chartsPlusWord('ar-EG', 'today')).toBe('اليوم');
    expect(chartsPlusWord('de', 'total')).toBe('Total');
    expect(chartsPlusWord('en', 'of', { value: 3, max: 5 })).toBe('3 of 5');
  });
  it('knows the RTL locales', () => {
    expect(isRtlLocale('fa-IR')).toBe(true);
    expect(isRtlLocale('ar')).toBe(true);
    expect(isRtlLocale('en-US')).toBe(false);
  });
});

describe('curves', () => {
  const pts = [
    [0, 10],
    [10, 20],
    [20, 5],
  ] as const;

  it('steps change level halfway between points', () => {
    expect(stepPath(pts)).toBe('M0,10H5V20H15V5H20');
    expect(stepPath([])).toBe('');
  });

  it('routes smooth and linear to the base linePath', () => {
    expect(chartCurvePath(pts, 'smooth')).toBe(linePath(pts, true));
    expect(chartCurvePath(pts, 'linear')).toBe(linePath(pts, false));
    expect(chartCurvePath(pts, 'step')).toBe(stepPath(pts));
  });

  it('closes a band: upper forward, lower back', () => {
    const d = chartBandPath(
      [
        [0, 0],
        [10, 0],
      ],
      [
        [0, 10],
        [10, 10],
      ],
      'linear',
    );
    expect(d).toBe('M0,0L10,0L10,10L0,10Z');
    expect(chartBandPath([], [], 'linear')).toBe('');
  });

  it('draws horizontal bars rounded at the data end only', () => {
    expect(hBarPath(0, 10, 50, 0, 0)).toBe('M0,0H50V10H0Z');
    const right = hBarPath(0, 10, 50, 0, 4);
    expect(right.startsWith('M0,0H46Q50,0')).toBe(true);
    const left = hBarPath(0, 10, 0, 50, 4);
    expect(left.startsWith('M50,0H4Q0,0')).toBe(true);
  });
});

describe('chartStack', () => {
  const values = [
    [10, 20, 0],
    [30, 20, 0],
  ];

  it('piles layers from zero', () => {
    const s = chartStack(values, 'stacked');
    expect(s.lower[0]).toEqual([0, 0, 0]);
    expect(s.upper[0]).toEqual([10, 20, 0]);
    expect(s.lower[1]).toEqual([10, 20, 0]);
    expect(s.upper[1]).toEqual([40, 40, 0]);
    expect(s.totals).toEqual([40, 40, 0]);
    expect(s.domain[0]).toBe(0);
    expect(s.domain[1]).toBeGreaterThanOrEqual(40);
  });

  it('normalises to 100% (an empty column stays empty)', () => {
    const s = chartStack(values, 'percent');
    expect(s.upper[0]).toEqual([25, 50, 0]);
    expect(s.upper[1]).toEqual([100, 100, 0]);
    expect(s.domain).toEqual([0, 100]);
  });

  it('centres an expanded stack on zero', () => {
    const s = chartStack(values, 'expanded');
    expect(s.lower[0]![0]).toBe(-20);
    expect(s.upper[1]![0]).toBe(20);
    expect(s.domain[0]).toBe(-s.domain[1]);
  });

  it('weights scale series in and out; negatives count as zero', () => {
    const s = chartStack(
      [
        [10, -5],
        [10, 10],
      ],
      'stacked',
      [0, 1],
    );
    expect(s.upper[0]).toEqual([0, 0]);
    expect(s.upper[1]).toEqual([10, 10]);
  });

  it('lerps between two stacks', () => {
    const a = chartStack(values, 'stacked', [1, 0]);
    const b = chartStack(values, 'stacked', [1, 1]);
    const mid = lerpChartStack(a, b, 0.5);
    expect(mid.upper[1]![0]).toBe(25);
    expect(lerpChartStack(a, b, 1).upper).toEqual(b.upper);
    expect(mid.ticks).toEqual(b.ticks);
  });
});

describe('chartTween', () => {
  it('jumps straight to the end without a browser', () => {
    const frames: number[] = [];
    const cancel = chartTween(300, (t) => frames.push(t));
    expect(frames).toEqual([1]);
    expect(typeof cancel).toBe('function');
  });
});

describe('chartExtremes', () => {
  it('finds the first max and min, skipping nulls', () => {
    expect(chartExtremes([3, null, 9, 1, 9, 1])).toEqual({ max: { index: 2, value: 9 }, min: { index: 3, value: 1 } });
    expect(chartExtremes([])).toEqual({ max: null, min: null });
  });
});

describe('fills', () => {
  it('builds gradient, hatched and duotone defs keyed by id', () => {
    expect(chartFillDefs('c', 2, 'gradient')).toContain('id="c-f1"');
    expect(chartFillDefs('c', 1, 'hatched')).toContain('<pattern id="c-f0"');
    expect(chartFillDefs('c', 1, 'duotone')).toContain('color-mix');
    expect(chartFillDefs('c', 3, 'solid')).toBe('');
  });
  it('paints solid with the colour, the rest by url', () => {
    expect(chartFillPaint('c', 0, 'solid')).toBe('var(--nx-chart-1)');
    expect(chartFillPaint('c', 2, 'hatched')).toBe('url(#c-f2)');
  });
});

describe('stackedAreaModel', () => {
  it('draws one closed layer per series and parks the tooltip on the top edge', () => {
    const s = chartStack(
      [
        [1, 2, 3],
        [1, 1, 1],
      ],
      'stacked',
    );
    const m = stackedAreaModel(s, 3, 400, 200, 'linear');
    expect(m.layers).toHaveLength(2);
    expect(m.layers[0]!.area.endsWith('Z')).toBe(true);
    expect(m.xs[0]).toBe(44);
    expect(m.xs[2]).toBe(388);
    expect(m.tops[2]).toBeLessThan(m.tops[0]!);
    expect(m.ticks[0]!.value).toBe(0);
  });
});

describe('stackedBarModel', () => {
  const values = [
    [10, 20],
    [10, 0],
  ];

  it('rounds only the top segment of a stack', () => {
    const m = stackedBarModel({ stack: chartStack(values), values, count: 2, width: 400, height: 200 });
    // index 1: only series 0 has height, so it is the capped one.
    const second = m.bars.filter((b) => b.index === 1);
    expect(second).toHaveLength(1);
    expect(second[0]!.d).toContain('Q');
    const firstBottom = m.bars.find((b) => b.index === 0 && b.series === 0)!;
    expect(firstBottom.d).not.toContain('Q');
    expect(m.hits).toHaveLength(2);
  });

  it('lays grouped bars side by side and skips hidden series', () => {
    const m = stackedBarModel({ stack: null, values, weights: [1, 0], count: 2, width: 400, height: 200 });
    expect(m.bars.every((b) => b.series === 0)).toBe(true);
  });

  it('mirrors horizontal bars in RTL', () => {
    const ltr = stackedBarModel({ stack: chartStack(values), values, count: 2, width: 400, height: 200, orientation: 'horizontal' });
    const rtl = stackedBarModel({ stack: chartStack(values), values, count: 2, width: 400, height: 200, orientation: 'horizontal', rtl: true });
    expect(ltr.base).toBeLessThan(rtl.base);
    expect(ltr.ticks[0]!.at).toBeLessThan(ltr.ticks[ltr.ticks.length - 1]!.at);
    expect(rtl.ticks[0]!.at).toBeGreaterThan(rtl.ticks[rtl.ticks.length - 1]!.at);
  });
});

describe('composedModel', () => {
  it('gives bars and lines their own axes and swaps sides in RTL', () => {
    const input = { bars: [[10, 20, 30]], lines: [[0.1, 0.4, 0.2]], count: 3, width: 400, height: 200 };
    const ltr = composedModel(input);
    const rtl = composedModel({ ...input, rtl: true });
    expect(ltr.primary.x).toBeLessThan(ltr.secondary.x);
    expect(rtl.primary.x).toBeGreaterThan(rtl.secondary.x);
    expect(ltr.centers[0]).toBeLessThan(ltr.centers[2]!);
    expect(rtl.centers[0]).toBeGreaterThan(rtl.centers[2]!);
    expect(ltr.bars).toHaveLength(3);
    expect(ltr.lines[0]!.points).toHaveLength(3);
  });
});

describe('lineModel', () => {
  it('spans the plot and closes the area on the baseline', () => {
    const m = lineModel([[1, 5, 3]], 300, 100, { curve: 'linear' });
    expect(m.xs).toEqual([44, 166, 288]);
    expect(m.series[0]!.area.endsWith('Z')).toBe(true);
    expect(m.series[0]!.area).toContain(`L288,${m.baseline}`);
  });
});

describe('brush ranges', () => {
  it('clamps into bounds and keeps a minimum span', () => {
    expect(brushClamp([-3, 40], 10)).toEqual([0, 9]);
    expect(brushClamp([5, 5], 10, 2)).toEqual([5, 7]);
    expect(brushClamp([9, 9], 10, 2)).toEqual([7, 9]);
    expect(brushClamp([6, 2], 10)).toEqual([2, 6]);
  });
  it('pans without changing width and stops at the ends', () => {
    expect(brushPan([2, 5], 2, 10)).toEqual([4, 7]);
    expect(brushPan([2, 5], 20, 10)).toEqual([6, 9]);
    expect(brushPan([2, 5], -20, 10)).toEqual([0, 3]);
  });
  it('moves one edge and respects the minimum span', () => {
    expect(brushEdge([2, 6], 'start', 0, 10)).toEqual([0, 6]);
    expect(brushEdge([2, 6], 'start', 6, 10, 2)).toEqual([4, 6]);
    expect(brushEdge([2, 6], 'end', 1, 10, 1)).toEqual([2, 3]);
    expect(brushEdge([2, 6], 'end', 99, 10)).toEqual([2, 9]);
  });
  it('places the window on the track', () => {
    expect(brushWindow([2, 6], 11, 100)).toEqual({ start: 20, size: 40 });
  });
});

describe('forecastModel', () => {
  it('joins the forecast to the last actual point and puts today there', () => {
    const m = forecastModel({ actual: [10, 12, 14], forecast: [16, 18], lower: [14, 15], upper: [18, 21], width: 400, height: 200, curve: 'linear' });
    expect(m.xs).toHaveLength(5);
    expect(m.forecast.points[0]).toEqual(m.actual.points[2]);
    expect(m.today).toBe(m.xs[2]);
    expect(m.band.endsWith('Z')).toBe(true);
    expect(m.tops).toHaveLength(5);
  });
  it('skips the band without bounds', () => {
    expect(forecastModel({ actual: [1, 2], forecast: [3], width: 300, height: 100 }).band).toBe('');
  });
});

describe('polar', () => {
  it('measures angles clockwise from 12 o’clock', () => {
    expect(polarPoint(50, 50, 10, 0)).toEqual([50, 40]);
    expect(polarPoint(50, 50, 10, 90)).toEqual([60, 50]);
    expect(radarAngle(1, 4)).toBe(90);
    expect(radarAngle(1, 4, false)).toBe(-90);
  });
  it('scales radar values to the rim and clamps them', () => {
    const pts = radarPoints([10, 5, 20, 0], 10, 50, 50, 40);
    expect(pts[0]).toEqual([50, 10]);
    expect(pts[1]).toEqual([70, 50]);
    expect(pts[2]).toEqual([50, 90]);
    expect(pts[3]).toEqual([50, 50]);
    expect(polygonPath(pts)).toMatch(/^M50,10L70,50L50,90L50,50Z$/);
  });
  it('builds rings, spokes and shapes', () => {
    const m = radarModel([[1, 2, 3, 4, 5]], 5, 300, { rings: 4 });
    expect(m.rings).toHaveLength(4);
    expect(m.spokes).toHaveLength(5);
    expect(m.labels[0]!.anchor).toBe('middle');
    expect(m.shapes[0]!.points).toHaveLength(5);
    expect(m.max).toBeGreaterThanOrEqual(5);
  });
  it('draws arcs with the right flags in both directions', () => {
    expect(radialArcPath(50, 50, 40, -135, 135)).toMatch(/A40,40 0 1 1 /);
    expect(radialArcPath(50, 50, 40, 0, 90)).toMatch(/A40,40 0 0 1 /);
    expect(radialArcPath(50, 50, 40, -135, 135, false)).toMatch(/A40,40 0 1 0 /);
  });
  it('nests radial rings outermost first with shares clamped to 0…100', () => {
    const m = radialBarModel([50, 150, -2], [100, 100, 100]);
    expect(m.rings).toHaveLength(3);
    expect(m.rings[0]!.r).toBeGreaterThan(m.rings[1]!.r);
    expect(m.rings.map((r) => r.share)).toEqual([50, 100, 0]);
  });
});
