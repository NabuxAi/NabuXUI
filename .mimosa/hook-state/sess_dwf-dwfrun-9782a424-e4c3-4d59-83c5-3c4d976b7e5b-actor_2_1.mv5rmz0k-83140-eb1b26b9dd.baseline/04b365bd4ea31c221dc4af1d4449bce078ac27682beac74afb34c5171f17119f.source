/**
 * Charts plus: the framework-agnostic half of the advanced charts (stacked
 * area, stacked bar, composed bar + line, brush, forecast, radar, radial bar,
 * loading skeleton).
 *
 * Everything here is pure geometry — numbers in, SVG path strings and
 * positions out — so the React components and the Alpine ones draw exactly the
 * same marks. It builds on the base chart maths in ../charts (linearScale,
 * niceTicks, linePath, bands, barPath, nearestIndex) and only adds what those
 * do not cover: stacking with offsets, step curves, closed bands, polar points
 * and arcs, brush ranges, a tween for animated restacks, SVG fill patterns and
 * one model per chart. The only DOM code is the brush pan behaviour.
 */
import { type Point, bands, barPath, linePath, linearScale, niceTicks } from '../charts';
import { type Cleanup, isBrowser, prefersReducedMotion } from '../env';

const round = (n: number) => Math.round(n * 100) / 100;
const finite = (n: unknown): number => (typeof n === 'number' && Number.isFinite(n) ? n : 0);

/** The plot's inner padding (the base charts use the same numbers). */
export const chartPad = { top: 16, right: 12, bottom: 28, left: 44 } as const;

/** Series colour i, in the fixed --nx-chart-1…7 order. */
export const chartColor = (i: number): string => `var(--nx-chart-${(((i % 7) + 7) % 7) + 1})`;

/* ---- The blocks' own words ---------------------------------------------------------------- */

export const chartsPlusWords = {
  en: {
    total: 'Total',
    actual: 'Actual',
    forecast: 'Forecast',
    today: 'Today',
    low: 'Low estimate',
    high: 'High estimate',
    max: 'High',
    min: 'Low',
    range: 'Selected range',
    rangeStart: 'Range start',
    rangeEnd: 'Range end',
    moveRange: 'Move the selected range',
    rangeValue: '{from} – {to}',
    loading: 'Loading chart',
    series: 'Series',
    value: 'Value',
    share: 'Share',
    of: '{value} of {max}',
    legend: 'Show or hide series',
  },
  fa: {
    total: 'جمع',
    actual: 'واقعی',
    forecast: 'پیش‌بینی',
    today: 'امروز',
    low: 'برآورد پایین',
    high: 'برآورد بالا',
    max: 'بیشینه',
    min: 'کمینه',
    range: 'بازهٔ انتخاب‌شده',
    rangeStart: 'آغاز بازه',
    rangeEnd: 'پایان بازه',
    moveRange: 'جابه‌جایی بازهٔ انتخاب‌شده',
    rangeValue: '{from} تا {to}',
    loading: 'در حال بارگذاری نمودار',
    series: 'سری',
    value: 'مقدار',
    share: 'سهم',
    of: '{value} از {max}',
    legend: 'نمایش یا پنهان‌کردن سری‌ها',
  },
  ar: {
    total: 'المجموع',
    actual: 'الفعلي',
    forecast: 'التوقع',
    today: 'اليوم',
    low: 'التقدير الأدنى',
    high: 'التقدير الأعلى',
    max: 'الأعلى',
    min: 'الأدنى',
    range: 'النطاق المحدد',
    rangeStart: 'بداية النطاق',
    rangeEnd: 'نهاية النطاق',
    moveRange: 'تحريك النطاق المحدد',
    rangeValue: '{from} – {to}',
    loading: 'جارٍ تحميل المخطط',
    series: 'السلسلة',
    value: 'القيمة',
    share: 'الحصة',
    of: '{value} من {max}',
    legend: 'إظهار السلاسل أو إخفاؤها',
  },
} as const;

export type ChartsPlusWord = keyof (typeof chartsPlusWords)['en'];

/** `chartsPlusWord('fa', 'forecast')`; unknown languages fall back to English. */
export function chartsPlusWord(locale: string | undefined, key: ChartsPlusWord, params: Record<string, string | number> = {}): string {
  const language = (locale ?? 'en').slice(0, 2).toLowerCase();
  const table = (chartsPlusWords as Record<string, Record<string, string>>)[language] ?? chartsPlusWords.en;
  const text = table[key] ?? chartsPlusWords.en[key];
  return text.replace(/\{(\w+)\}/g, (_, name: string) => String(params[name] ?? `{${name}}`));
}

/** Persian and Arabic charts mirror their value axes and horizontal bars. */
export const isRtlLocale = (locale: string | undefined): boolean => /^(fa|ar|he|ur)/i.test(locale ?? '');

/* ---- Curves and bands ------------------------------------------------------------------- */

export type ChartCurve = 'smooth' | 'linear' | 'step';

/** A stepped line that changes level halfway between points. */
export function stepPath(points: readonly Point[]): string {
  if (points.length === 0) return '';
  let d = `M${round(points[0]![0])},${round(points[0]![1])}`;
  for (let i = 1; i < points.length; i++) {
    const [x0] = points[i - 1]!;
    const [x1, y1] = points[i]!;
    const mid = round((x0 + x1) / 2);
    d += `H${mid}V${round(y1)}`;
    if (i === points.length - 1) d += `H${round(x1)}`;
  }
  return d;
}

/** A line through the points in one of the three curves (smooth = monotone, never overshoots). */
export function chartCurvePath(points: readonly Point[], curve: ChartCurve = 'smooth'): string {
  if (curve === 'step') return stepPath(points);
  return linePath(points, curve === 'smooth');
}

/**
 * A closed band between an upper and a lower edge (a stacked layer, a
 * confidence interval): the upper edge forward, the lower edge back.
 */
export function chartBandPath(upper: readonly Point[], lower: readonly Point[], curve: ChartCurve = 'smooth'): string {
  if (upper.length === 0) return '';
  const top = chartCurvePath(upper, curve);
  const back = chartCurvePath([...lower].reverse(), curve).replace(/^M/, 'L');
  return `${top}${back}Z`;
}

/** A horizontal bar from `baseline` to `valueX`, rounded only at its data end. */
export function hBarPath(y: number, height: number, valueX: number, baseline: number, radius = 4): string {
  const right = valueX >= baseline;
  const left = Math.min(valueX, baseline);
  const width = Math.abs(valueX - baseline);
  const r = Math.min(radius, height / 2, width);
  const [x0, x1, y0, y1] = [round(left), round(left + width), round(y), round(y + height)];
  if (r <= 0) return `M${x0},${y0}H${x1}V${y1}H${x0}Z`;
  return right
    ? `M${x0},${y0}H${round(x1 - r)}Q${x1},${y0} ${x1},${round(y0 + r)}V${round(y1 - r)}Q${x1},${y1} ${round(x1 - r)},${y1}H${x0}Z`
    : `M${x1},${y0}H${round(x0 + r)}Q${x0},${y0} ${x0},${round(y0 + r)}V${round(y1 - r)}Q${x0},${y1} ${round(x0 + r)},${y1}H${x1}Z`;
}

/* ---- Stacking ------------------------------------------------------------------------------ */

/**
 * stacked  — layers piled from zero (absolute values).
 * percent  — every column normalised to 100%.
 * expanded — a streamgraph: the pile is centred on zero so the silhouette
 *            breathes symmetrically.
 */
export type StackMode = 'stacked' | 'percent' | 'expanded';

export interface ChartStack {
  /** lower[s][i] / upper[s][i]: the band of series s at index i, in axis units. */
  lower: number[][];
  upper: number[][];
  /** Raw column totals (weighted, before percent normalisation). */
  totals: number[];
  /** The value axis: nice ticks and the domain they span. */
  ticks: number[];
  domain: [number, number];
}

/**
 * Stack the series. Negative values count as zero. `weights` (0…1 per series)
 * scale a series in or out — 0 hides it, and tweening a weight is what makes a
 * legend toggle restack smoothly instead of jumping.
 */
export function chartStack(values: readonly (readonly number[])[], mode: StackMode = 'stacked', weights?: readonly number[]): ChartStack {
  const count = values.reduce((n, s) => Math.max(n, s.length), 0);
  const lower = values.map(() => new Array<number>(count).fill(0));
  const upper = values.map(() => new Array<number>(count).fill(0));
  const totals = new Array<number>(count).fill(0);

  for (let i = 0; i < count; i++) {
    const column = values.map((s, si) => Math.max(0, finite(s[i])) * (weights ? Math.min(1, Math.max(0, finite(weights[si]))) : 1));
    const total = column.reduce((a, b) => a + b, 0);
    totals[i] = round(total);
    const scale = mode === 'percent' ? (total > 0 ? 100 / total : 0) : 1;
    let cursor = mode === 'expanded' ? -total / 2 : 0;
    column.forEach((v, si) => {
      lower[si]![i] = round(cursor);
      cursor += v * scale;
      upper[si]![i] = round(cursor);
    });
  }

  let ticks: number[];
  if (mode === 'percent') ticks = [0, 25, 50, 75, 100];
  else if (mode === 'expanded') {
    const half = Math.max(1, ...totals) / 2;
    const top = niceTicks(0, half, 2);
    const step = top[1]! - top[0]!;
    const end = top[top.length - 1]!;
    ticks = [];
    for (let v = -end; v <= end + step / 2; v += step) ticks.push(round(v));
  } else ticks = niceTicks(0, Math.max(1, ...totals), 4);

  return { lower, upper, totals, ticks, domain: [ticks[0]!, ticks[ticks.length - 1]!] };
}

const lerp = (a: number, b: number, t: number) => a + (b - a) * t;

/** A stack part-way between two stacks of the same shape (the ticks are the target's). */
export function lerpChartStack(from: ChartStack, to: ChartStack, t: number): ChartStack {
  const mix = (a: number[][], b: number[][]) => b.map((row, s) => row.map((v, i) => round(lerp(a[s]?.[i] ?? v, v, t))));
  return {
    lower: mix(from.lower, to.lower),
    upper: mix(from.upper, to.upper),
    totals: to.totals.map((v, i) => round(lerp(from.totals[i] ?? v, v, t))),
    ticks: to.ticks,
    domain: [lerp(from.domain[0], to.domain[0], t), lerp(from.domain[1], to.domain[1], t)],
  };
}

/** Ease-out quint: quick to respond, long gentle settle. */
export const chartEase = (t: number): number => 1 - (1 - t) ** 5;

/**
 * Drive `onFrame(t)` from 0 to 1 over `duration` ms on animation frames. Under
 * reduced motion (or without a browser) it jumps straight to 1. Returns a
 * cancel function; cancelling leaves the last frame where it is, so a new
 * tween can start from there (interruptible).
 */
export function chartTween(duration: number, onFrame: (t: number) => void, ease: (t: number) => number = chartEase): Cleanup {
  if (!isBrowser || duration <= 0 || prefersReducedMotion() || typeof requestAnimationFrame === 'undefined') {
    onFrame(1);
    return () => {};
  }
  let raf = 0;
  const start = performance.now();
  const step = (now: number) => {
    const t = Math.min(1, (now - start) / duration);
    onFrame(ease(t));
    if (t < 1) raf = requestAnimationFrame(step);
  };
  raf = requestAnimationFrame(step);
  return () => cancelAnimationFrame(raf);
}

/* ---- Extremes ---------------------------------------------------------------------------------- */

export interface ChartExtreme {
  index: number;
  value: number;
}

/** The highest and lowest values (first occurrence), skipping nulls. */
export function chartExtremes(values: readonly (number | null | undefined)[]): { max: ChartExtreme | null; min: ChartExtreme | null } {
  let max: ChartExtreme | null = null;
  let min: ChartExtreme | null = null;
  values.forEach((value, index) => {
    if (typeof value !== 'number' || !Number.isFinite(value)) return;
    if (!max || value > max.value) max = { index, value };
    if (!min || value < min.value) min = { index, value };
  });
  return { max, min };
}

/* ---- Fills: gradient, solid, hatched and duotone via <pattern> / <linearGradient> --------------- */

export type ChartFill = 'gradient' | 'solid' | 'hatched' | 'duotone';

/**
 * The <defs> body for `count` series in one fill style, as markup (static per
 * id, so React can inline it too). `area` softens gradients for washes.
 */
export function chartFillDefs(id: string, count: number, fill: ChartFill, area = true): string {
  let defs = '';
  for (let i = 0; i < count; i++) {
    const c = chartColor(i);
    const key = `${id}-f${i}`;
    if (fill === 'gradient') {
      const [a, b] = area ? [0.42, 0.06] : [0.95, 0.6];
      defs += `<linearGradient id="${key}" x1="0" x2="0" y1="0" y2="1"><stop offset="0%" style="stop-color:${c};stop-opacity:${a}"/><stop offset="100%" style="stop-color:${c};stop-opacity:${b}"/></linearGradient>`;
    } else if (fill === 'hatched') {
      defs +=
        `<pattern id="${key}" width="6" height="6" patternUnits="userSpaceOnUse" patternTransform="rotate(45)">` +
        `<rect width="6" height="6" style="fill:${c};fill-opacity:0.16"/>` +
        `<line x1="0" y1="0" x2="0" y2="6" style="stroke:${c};stroke-width:2.5;stroke-opacity:0.85"/></pattern>`;
    } else if (fill === 'duotone') {
      defs +=
        `<linearGradient id="${key}" x1="0" x2="0" y1="0" y2="1">` +
        `<stop offset="0%" style="stop-color:${c}"/>` +
        `<stop offset="52%" style="stop-color:${c}"/>` +
        `<stop offset="52%" style="stop-color:color-mix(in oklab, ${c} 42%, var(--nx-surface))"/>` +
        `<stop offset="100%" style="stop-color:color-mix(in oklab, ${c} 30%, var(--nx-surface))"/></linearGradient>`;
    }
  }
  return defs;
}

/** The fill paint of series i: a url() for patterned fills, the colour for solid. */
export function chartFillPaint(id: string, i: number, fill: ChartFill): string {
  return fill === 'solid' ? chartColor(i) : `url(#${id}-f${i})`;
}

/* ---- The value axis ----------------------------------------------------------------------------- */

export interface ChartTick {
  value: number;
  /** Position along the value axis, in px. */
  at: number;
}

/* ---- Model: stacked area ------------------------------------------------------------------------ */

export interface StackedAreaModel {
  xs: number[];
  ticks: ChartTick[];
  layers: Array<{ area: string; line: string; top: Point[] }>;
  /** y (px) of the pile's top edge per index — where the tooltip parks. */
  tops: number[];
  plot: { x0: number; x1: number; y0: number; y1: number };
  /** Label every n-th category so they never collide. */
  every: number;
}

export function stackedAreaModel(stack: ChartStack, count: number, width: number, height: number, curve: ChartCurve = 'smooth'): StackedAreaModel {
  const p = chartPad;
  const x = linearScale([0, Math.max(1, count - 1)], [p.left, width - p.right]);
  const y = linearScale(stack.domain, [height - p.bottom, p.top]);
  const xs = Array.from({ length: count }, (_, i) => round(x(i)));
  const layers = stack.upper.map((row, s) => {
    const top = row.map((v, i) => [xs[i]!, round(y(v))] as Point);
    const bottom = stack.lower[s]!.map((v, i) => [xs[i]!, round(y(v))] as Point);
    return { area: chartBandPath(top, bottom, curve), line: chartCurvePath(top, curve), top };
  });
  const tops = xs.map((_, i) => round(y(Math.max(...stack.upper.map((row) => row[i] ?? 0)))));
  const [lo, hi] = [Math.min(...stack.domain), Math.max(...stack.domain)];
  const ticks = stack.ticks.filter((t) => t >= lo - 1e-6 && t <= hi + 1e-6).map((value) => ({ value, at: round(y(value)) }));
  return {
    xs,
    ticks,
    layers,
    tops,
    plot: { x0: p.left, x1: width - p.right, y0: p.top, y1: height - p.bottom },
    every: Math.max(1, Math.ceil(count / Math.max(2, Math.floor(width / 90)))),
  };
}

/* ---- Model: stacked / percent / grouped bars, vertical or horizontal --------------------------- */

export type BarMode = 'stacked' | 'percent' | 'grouped';
export type BarOrientation = 'vertical' | 'horizontal';

export interface BarMark {
  series: number;
  index: number;
  d: string;
  /** The segment's centre, for value labels. */
  cx: number;
  cy: number;
}

export interface StackedBarModel {
  bars: BarMark[];
  ticks: ChartTick[];
  /** Category centres along the category axis. */
  centers: number[];
  /** Per category: the band (for the hover target) and where the tooltip parks. */
  hits: Array<{ x: number; y: number; w: number; h: number; tipX: number; tipY: number }>;
  plot: { x0: number; x1: number; y0: number; y1: number };
  /** The baseline (value 0) along the value axis, px. */
  base: number;
}

export interface StackedBarInput {
  /** The stack for stacked / percent, or null for grouped. */
  stack: ChartStack | null;
  values: readonly (readonly number[])[];
  weights?: readonly number[];
  count: number;
  width: number;
  height: number;
  orientation?: BarOrientation;
  /** Horizontal bars grow toward inline-end: right to left in RTL. */
  rtl?: boolean;
  /** Left gutter for horizontal category names (px). */
  gutter?: number;
}

export function stackedBarModel(input: StackedBarInput): StackedBarModel {
  const { stack, values, count, width, height, orientation = 'vertical', rtl = false } = input;
  const weights = input.weights ?? values.map(() => 1);
  const horizontal = orientation === 'horizontal';
  const gutter = input.gutter ?? 96;
  const p = horizontal
    ? { top: 8, bottom: 28, left: rtl ? 16 : gutter, right: rtl ? gutter : 16 }
    : { top: chartPad.top, bottom: chartPad.bottom, left: chartPad.left, right: chartPad.right };
  const plot = { x0: p.left, x1: width - p.right, y0: p.top, y1: height - p.bottom };

  // Value axis.
  let ticks: number[];
  let domain: [number, number];
  if (stack) {
    ticks = stack.ticks;
    domain = stack.domain;
  } else {
    const all = values.flatMap((s, si) => s.map((v) => Math.max(0, finite(v)) * (weights[si] ?? 1)));
    ticks = niceTicks(0, Math.max(1, ...all), 4);
    domain = [ticks[0]!, ticks[ticks.length - 1]!];
  }
  const v = horizontal
    ? linearScale(domain, rtl ? [plot.x1, plot.x0] : [plot.x0, plot.x1])
    : linearScale(domain, [plot.y1, plot.y0]);
  const length = horizontal ? plot.y1 - plot.y0 : plot.x1 - plot.x0;
  const visible = weights.filter((w) => w > 0.001).length || 1;
  const grouped = !stack;
  const groups = bands(count, length, grouped ? { maxBar: 18 * visible + 3 * (visible - 1), fill: 0.72 } : { maxBar: horizontal ? 26 : 40, fill: 0.62 });
  const start = horizontal ? plot.y0 : plot.x0;
  const base = round(v(Math.max(domain[0], 0)));

  const bars: BarMark[] = [];
  const hits: StackedBarModel['hits'] = [];

  groups.forEach((g, index) => {
    const bandStart = start + g.x;
    let tipValue = 0;
    if (stack) {
      // The top-most segment with any height gets the rounded data end.
      let cap = -1;
      stack.upper.forEach((row, s) => {
        if ((row[index] ?? 0) - (stack.lower[s]?.[index] ?? 0) > 0.0001) cap = s;
      });
      stack.upper.forEach((row, s) => {
        const hi = row[index] ?? 0;
        const lo = stack.lower[s]?.[index] ?? 0;
        if (hi - lo <= 0.0001) return;
        const a = v(lo);
        const b = v(hi);
        const d = horizontal ? hBarPath(bandStart, g.width, b, a, s === cap ? 4 : 0) : barPath(bandStart, g.width, b, a, s === cap ? 4 : 0);
        bars.push({ series: s, index, d, cx: horizontal ? round((a + b) / 2) : round(bandStart + g.width / 2), cy: horizontal ? round(bandStart + g.width / 2) : round((a + b) / 2) });
      });
      tipValue = Math.max(...stack.upper.map((row) => row[index] ?? 0));
    } else {
      const gap = 3;
      const shown = values.map((_, s) => s).filter((s) => (weights[s] ?? 1) > 0.001);
      const w = (g.width - gap * (shown.length - 1)) / Math.max(1, shown.length);
      shown.forEach((s, k) => {
        const value = Math.max(0, finite(values[s]?.[index])) * (weights[s] ?? 1);
        const at = bandStart + k * (w + gap);
        const end = v(value);
        const d = horizontal ? hBarPath(at, w, end, base, 4) : barPath(at, w, end, base, 4);
        bars.push({ series: s, index, d, cx: horizontal ? round((end + base) / 2) : round(at + w / 2), cy: horizontal ? round(at + w / 2) : round((end + base) / 2) });
        tipValue = Math.max(tipValue, value);
      });
    }
    const center = round(start + g.center);
    const tip = round(v(tipValue));
    hits.push(
      horizontal
        ? { x: plot.x0, y: round(start + g.bandX), w: plot.x1 - plot.x0, h: round(g.bandWidth), tipX: tip, tipY: center }
        : { x: round(start + g.bandX), y: plot.y0, w: round(g.bandWidth), h: plot.y1 - plot.y0, tipX: center, tipY: tip },
    );
  });

  const [lo, hi] = [Math.min(...domain), Math.max(...domain)];
  return {
    bars,
    ticks: ticks.filter((t) => t >= lo - 1e-6 && t <= hi + 1e-6).map((value) => ({ value, at: round(v(value)) })),
    centers: groups.map((g) => round(start + g.center)),
    hits,
    plot,
    base,
  };
}

/* ---- Model: composed (bars + lines, two value axes) --------------------------------------------- */

export interface ComposedInput {
  bars: readonly (readonly number[])[];
  lines: readonly (readonly number[])[];
  count: number;
  width: number;
  height: number;
  /** In RTL the primary axis sits on the right and categories run right to left. */
  rtl?: boolean;
  curve?: ChartCurve;
}

export interface ComposedModel {
  bars: Array<{ series: number; index: number; d: string }>;
  lines: Array<{ d: string; points: Point[] }>;
  /** The bars' axis (inline-start) and the lines' axis (inline-end). */
  primary: { ticks: ChartTick[]; x: number; anchor: 'start' | 'end' };
  secondary: { ticks: ChartTick[]; x: number; anchor: 'start' | 'end' };
  centers: number[];
  hits: Array<{ x: number; w: number }>;
  tops: number[];
  plot: { x0: number; x1: number; y0: number; y1: number };
}

export function composedModel({ bars, lines, count, width, height, rtl = false, curve = 'smooth' }: ComposedInput): ComposedModel {
  const p = { top: chartPad.top, bottom: chartPad.bottom, left: 48, right: 48 };
  const plot = { x0: p.left, x1: width - p.right, y0: p.top, y1: height - p.bottom };
  const barTicks = niceTicks(0, Math.max(1, ...bars.flat().map(finite)), 4);
  const lineValues = lines.flat().map(finite);
  const lineTicks = niceTicks(Math.min(0, ...lineValues), Math.max(1, ...lineValues), 4);
  const yb = linearScale([barTicks[0]!, barTicks[barTicks.length - 1]!], [plot.y1, plot.y0]);
  const yl = linearScale([lineTicks[0]!, lineTicks[lineTicks.length - 1]!], [plot.y1, plot.y0]);
  const n = Math.max(1, bars.length);
  const groups = bands(count, plot.x1 - plot.x0, { maxBar: 22 * n + 3 * (n - 1), fill: 0.62 });
  const mirror = (x: number, w = 0) => (rtl ? plot.x0 + plot.x1 - x - w : x);

  const out: ComposedModel['bars'] = [];
  groups.forEach((g, index) => {
    const w = (g.width - 3 * (n - 1)) / n;
    bars.forEach((s, si) => {
      const x = mirror(plot.x0 + g.x + si * (w + 3), w);
      out.push({ series: si, index, d: barPath(x, w, yb(Math.max(0, finite(s[index]))), yb(0), 4) });
    });
  });
  const centers = groups.map((g) => round(mirror(plot.x0 + g.center)));
  const lineMarks = lines.map((s) => {
    const points = centers.map((cx, i) => [cx, round(yl(finite(s[i])))] as Point);
    const ordered = rtl ? [...points].reverse() : points;
    return { d: chartCurvePath(ordered, curve), points };
  });
  const tops = centers.map((_, i) => round(Math.min(yb(Math.max(0, ...bars.map((s) => finite(s[i])))), ...lineMarks.map((l) => l.points[i]![1]))));
  const at = (side: 'start' | 'end') => {
    const left = (side === 'start') !== rtl;
    return { x: left ? plot.x0 - 8 : plot.x1 + 8, anchor: left ? ('end' as const) : ('start' as const) };
  };
  return {
    bars: out,
    lines: lineMarks,
    primary: { ticks: barTicks.map((value) => ({ value, at: round(yb(value)) })), ...at('start') },
    secondary: { ticks: lineTicks.map((value) => ({ value, at: round(yl(value)) })), ...at('end') },
    centers,
    hits: groups.map((g) => ({ x: round(mirror(plot.x0 + g.bandX, g.bandWidth)), w: round(g.bandWidth) })),
    tops,
    plot,
  };
}

/* ---- Model: line / area over a window (the brush chart's main and mini plots) ------------------ */

export interface LineModel {
  xs: number[];
  ticks: ChartTick[];
  series: Array<{ line: string; area: string; points: Point[] }>;
  baseline: number;
  plot: { x0: number; x1: number; y0: number; y1: number };
}

export function lineModel(
  values: readonly (readonly number[])[],
  width: number,
  height: number,
  { pad = chartPad as { top: number; right: number; bottom: number; left: number }, curve = 'smooth' as ChartCurve, domain }: { pad?: { top: number; right: number; bottom: number; left: number }; curve?: ChartCurve; domain?: [number, number] } = {},
): LineModel {
  const count = values.reduce((n, s) => Math.max(n, s.length), 0);
  const all = values.flat().map(finite);
  const ticks = domain ? niceTicks(domain[0], domain[1], 4) : niceTicks(Math.min(0, ...all), Math.max(1, ...all), 4);
  const plot = { x0: pad.left, x1: width - pad.right, y0: pad.top, y1: height - pad.bottom };
  const x = linearScale([0, Math.max(1, count - 1)], [plot.x0, plot.x1]);
  const y = linearScale([ticks[0]!, ticks[ticks.length - 1]!], [plot.y1, plot.y0]);
  const xs = Array.from({ length: count }, (_, i) => round(x(i)));
  const baseline = round(y(ticks[0]!));
  return {
    xs,
    ticks: ticks.map((value) => ({ value, at: round(y(value)) })),
    series: values.map((s) => {
      const points = s.map((v, i) => [xs[i]!, round(y(finite(v)))] as Point);
      const line = chartCurvePath(points, curve);
      const last = points[points.length - 1];
      const first = points[0];
      const area = first && last ? `${line}L${last[0]},${baseline}L${first[0]},${baseline}Z` : '';
      return { line, area, points };
    }),
    baseline,
    plot,
  };
}

/* ---- Brush ranges ------------------------------------------------------------------------------ */

export type ChartRange = [number, number];

/** A valid inclusive index range inside [0, count − 1], at least `minSpan` wide. */
export function brushClamp(range: readonly [number, number], count: number, minSpan = 1): ChartRange {
  const last = Math.max(0, count - 1);
  const span = Math.min(Math.max(0, Math.round(minSpan)), last);
  let a = Math.round(Math.min(range[0], range[1]));
  let b = Math.round(Math.max(range[0], range[1]));
  a = Math.min(Math.max(0, a), last);
  b = Math.min(Math.max(0, b), last);
  if (b - a < span) {
    b = Math.min(last, a + span);
    a = Math.max(0, b - span);
  }
  return [a, b];
}

/** Slide the whole window by `delta` indices, keeping its width; stops at the ends. */
export function brushPan(range: readonly [number, number], delta: number, count: number): ChartRange {
  const span = range[1] - range[0];
  const last = Math.max(0, count - 1);
  const a = Math.min(Math.max(0, Math.round(range[0] + delta)), Math.max(0, last - span));
  return [a, a + span];
}

/** Move one edge to `index`, never letting the window shrink below `minSpan`. */
export function brushEdge(range: readonly [number, number], edge: 'start' | 'end', index: number, count: number, minSpan = 1): ChartRange {
  const last = Math.max(0, count - 1);
  const span = Math.min(Math.max(0, Math.round(minSpan)), last);
  const i = Math.round(index);
  return edge === 'start'
    ? [Math.min(Math.max(0, i), range[1] - span), range[1]]
    : [range[0], Math.max(Math.min(last, i), range[0] + span)];
}

/** The window's place on a track of `width` px, as start offset and size (px). */
export function brushWindow(range: readonly [number, number], count: number, width: number): { start: number; size: number } {
  const step = width / Math.max(1, count - 1);
  return { start: round(range[0] * step), size: round(Math.max(0, range[1] - range[0]) * step) };
}

export interface ChartBrushOptions {
  count: number;
  /** The current range (read at the start of each drag). */
  get: () => ChartRange;
  onChange: (range: ChartRange) => void;
}

/**
 * Pan behaviour for a brush window (a native <button> inside the track):
 * pointer drag with capture (first touch only) and arrow keys
 * (Shift = a whole window), Home/End to the ends. The two edges are native
 * range inputs and need no help. The track is always drawn left to right,
 * like the plot above it.
 */
export function chartBrush(handle: HTMLElement, options: ChartBrushOptions): Cleanup {
  const track = (handle.closest('[data-brush-track]') as HTMLElement | null) ?? handle.parentElement!;
  let pointer: number | null = null;
  let startX = 0;
  let startRange: ChartRange = [0, 0];

  const down = (event: PointerEvent) => {
    if (pointer !== null || (event.pointerType === 'mouse' && event.button !== 0)) return;
    pointer = event.pointerId;
    startX = event.clientX;
    startRange = options.get();
    handle.setPointerCapture?.(event.pointerId);
    handle.setAttribute('data-dragging', '');
    event.preventDefault();
  };
  const move = (event: PointerEvent) => {
    if (event.pointerId !== pointer) return;
    const width = track.getBoundingClientRect().width || 1;
    const step = width / Math.max(1, options.count - 1);
    const delta = Math.round((event.clientX - startX) / step);
    const next = brushPan(startRange, delta, options.count);
    const current = options.get();
    if (next[0] !== current[0] || next[1] !== current[1]) options.onChange(next);
  };
  const up = (event: PointerEvent) => {
    if (event.pointerId !== pointer) return;
    pointer = null;
    handle.removeAttribute('data-dragging');
  };
  const key = (event: KeyboardEvent) => {
    const range = options.get();
    const span = Math.max(1, range[1] - range[0]);
    let next: ChartRange | null = null;
    if (event.key === 'ArrowRight' || event.key === 'ArrowUp') next = brushPan(range, event.shiftKey ? span : 1, options.count);
    else if (event.key === 'ArrowLeft' || event.key === 'ArrowDown') next = brushPan(range, event.shiftKey ? -span : -1, options.count);
    else if (event.key === 'Home') next = brushPan(range, -range[0], options.count);
    else if (event.key === 'End') next = brushPan(range, options.count, options.count);
    if (!next) return;
    event.preventDefault();
    if (next[0] !== range[0] || next[1] !== range[1]) options.onChange(next);
  };

  handle.addEventListener('pointerdown', down);
  handle.addEventListener('pointermove', move);
  handle.addEventListener('pointerup', up);
  handle.addEventListener('pointercancel', up);
  handle.addEventListener('keydown', key);
  return () => {
    handle.removeEventListener('pointerdown', down);
    handle.removeEventListener('pointermove', move);
    handle.removeEventListener('pointerup', up);
    handle.removeEventListener('pointercancel', up);
    handle.removeEventListener('keydown', key);
  };
}

/* ---- Model: forecast ------------------------------------------------------------------------------ */

export interface ForecastInput {
  actual: readonly number[];
  forecast: readonly number[];
  lower?: readonly number[];
  upper?: readonly number[];
  width: number;
  height: number;
  curve?: ChartCurve;
}

export interface ForecastModel {
  xs: number[];
  ticks: ChartTick[];
  actual: { line: string; area: string; points: Point[] };
  /** Starts at the last actual point so the two lines meet. */
  forecast: { line: string; points: Point[] };
  band: string;
  today: number;
  /** The y of the top-most mark per index (tooltip parking). */
  tops: number[];
  plot: { x0: number; x1: number; y0: number; y1: number };
}

export function forecastModel({ actual, forecast, lower = [], upper = [], width, height, curve = 'smooth' }: ForecastInput): ForecastModel {
  const k = actual.length;
  const count = k + forecast.length;
  const all = [...actual, ...forecast, ...lower, ...upper].map(finite);
  const ticks = niceTicks(Math.min(0, ...all), Math.max(1, ...all), 4);
  const plot = { x0: chartPad.left, x1: width - chartPad.right, y0: chartPad.top, y1: height - chartPad.bottom };
  const x = linearScale([0, Math.max(1, count - 1)], [plot.x0, plot.x1]);
  const y = linearScale([ticks[0]!, ticks[ticks.length - 1]!], [plot.y1, plot.y0]);
  const xs = Array.from({ length: count }, (_, i) => round(x(i)));
  const baseline = round(y(ticks[0]!));

  const actualPoints = actual.map((v, i) => [xs[i]!, round(y(finite(v)))] as Point);
  const actualLine = chartCurvePath(actualPoints, curve);
  const first = actualPoints[0];
  const last = actualPoints[actualPoints.length - 1];
  const actualArea = first && last ? `${actualLine}L${last[0]},${baseline}L${first[0]},${baseline}Z` : '';

  const join = last ? [last] : [];
  const forecastPoints = [...join, ...forecast.map((v, i) => [xs[k + i]!, round(y(finite(v)))] as Point)];
  const hasBand = lower.length > 0 && upper.length > 0;
  const lastValue = finite(actual[k - 1]);
  const bandTop = hasBand ? [...join, ...upper.map((v, i) => [xs[k + i]!, round(y(finite(v)))] as Point)] : [];
  const bandBottom = hasBand ? [...(last ? [[last[0], round(y(lastValue))] as Point] : []), ...lower.map((v, i) => [xs[k + i]!, round(y(finite(v)))] as Point)] : [];

  const tops = xs.map((_, i) => {
    if (i < k) return actualPoints[i]![1];
    const j = i - k;
    return round(Math.min(y(finite(forecast[j])), hasBand ? y(finite(upper[j])) : Infinity));
  });

  return {
    xs,
    ticks: ticks.map((value) => ({ value, at: round(y(value)) })),
    actual: { line: actualLine, area: actualArea, points: actualPoints },
    forecast: { line: chartCurvePath(forecastPoints, curve), points: forecastPoints },
    band: hasBand ? chartBandPath(bandTop, bandBottom, curve) : '',
    today: last ? last[0] : plot.x0,
    tops,
    plot,
  };
}

/* ---- Polar: radar and radial bars ----------------------------------------------------------------- */

/** A point at `angle` degrees (0 = 12 o'clock, clockwise) and radius r around (cx, cy). */
export function polarPoint(cx: number, cy: number, r: number, angle: number): Point {
  const rad = (angle * Math.PI) / 180;
  return [round(cx + r * Math.sin(rad)), round(cy - r * Math.cos(rad))];
}

/** The angle of axis i of n (clockwise from the top; counter-clockwise when mirrored for RTL). */
export const radarAngle = (i: number, n: number, clockwise = true): number => ((clockwise ? 1 : -1) * 360 * i) / Math.max(1, n);

/** Each value as a point on its spoke, scaled so `max` touches the rim. */
export function radarPoints(values: readonly number[], max: number, cx: number, cy: number, radius: number, clockwise = true): Point[] {
  const top = max > 0 ? max : 1;
  return values.map((v, i) => polarPoint(cx, cy, (Math.min(top, Math.max(0, finite(v))) / top) * radius, radarAngle(i, values.length, clockwise)));
}

/** A closed polygon through the points. */
export function polygonPath(points: readonly Point[]): string {
  if (points.length === 0) return '';
  return `${points.map(([x, y], i) => `${i ? 'L' : 'M'}${round(x)},${round(y)}`).join('')}Z`;
}

export interface RadarModel {
  cx: number;
  cy: number;
  radius: number;
  rings: string[];
  spokes: Array<[number, number, number, number]>;
  labels: Array<{ x: number; y: number; anchor: 'start' | 'middle' | 'end' }>;
  shapes: Array<{ d: string; points: Point[] }>;
  max: number;
}

export function radarModel(series: readonly (readonly number[])[], axes: number, size: number, { max, rings = 4, clockwise = true }: { max?: number; rings?: number; clockwise?: boolean } = {}): RadarModel {
  const cx = size / 2;
  const cy = size / 2;
  const radius = Math.max(10, size / 2 - 34);
  const top = max ?? niceTicks(0, Math.max(1, ...series.flat().map(finite)), rings).slice(-1)[0]!;
  const ringPaths = Array.from({ length: rings }, (_, r) => {
    const rr = (radius * (r + 1)) / rings;
    return polygonPath(Array.from({ length: axes }, (_, i) => polarPoint(cx, cy, rr, radarAngle(i, axes, clockwise))));
  });
  const spokes = Array.from({ length: axes }, (_, i) => {
    const [x, y] = polarPoint(cx, cy, radius, radarAngle(i, axes, clockwise));
    return [cx, cy, x, y] as [number, number, number, number];
  });
  const labels = Array.from({ length: axes }, (_, i) => {
    const angle = radarAngle(i, axes, clockwise);
    const [x, y] = polarPoint(cx, cy, radius + 14, angle);
    const s = Math.sin((angle * Math.PI) / 180);
    return { x, y, anchor: Math.abs(s) < 0.2 ? ('middle' as const) : s > 0 ? ('start' as const) : ('end' as const) };
  });
  const shapes = series.map((values) => {
    const points = radarPoints(values, top, cx, cy, radius, clockwise);
    return { d: polygonPath(points), points };
  });
  return { cx, cy, radius, rings: ringPaths, spokes, labels, shapes, max: top };
}

/**
 * An SVG arc from `start` to `end` degrees (0 = 12 o'clock) — clockwise by
 * default, counter-clockwise for RTL. Draw with pathLength="100" to dash it.
 */
export function radialArcPath(cx: number, cy: number, r: number, start: number, end: number, clockwise = true): string {
  const sweep = Math.min(359.99, Math.abs(end - start));
  const a = clockwise ? start : -start;
  const b = clockwise ? start + sweep : -start - sweep;
  const [x0, y0] = polarPoint(cx, cy, r, a);
  const [x1, y1] = polarPoint(cx, cy, r, b);
  return `M${x0},${y0}A${round(r)},${round(r)} 0 ${sweep > 180 ? 1 : 0} ${clockwise ? 1 : 0} ${x1},${y1}`;
}

export interface RadialBarModel {
  rings: Array<{ r: number; track: string; share: number }>;
  thickness: number;
}

/**
 * Concentric rings (outermost first) in a 100 × 100 view box. `share` is the
 * filled part of each ring's sweep, 0…100 (the dash length on pathLength 100).
 */
export function radialBarModel(
  values: readonly number[],
  maxes: readonly number[],
  { sweep = 270, thickness, gap = 2.2, clockwise = true }: { sweep?: number; thickness?: number; gap?: number; clockwise?: boolean } = {},
): RadialBarModel {
  const n = Math.max(1, values.length);
  const outer = 46;
  const inner = 14;
  const t = thickness ?? Math.max(2.5, Math.min(8, (outer - inner - gap * (n - 1)) / n));
  const start = -sweep / 2;
  return {
    thickness: round(t),
    rings: values.map((v, i) => {
      const r = round(outer - t / 2 - i * (t + gap));
      const max = finite(maxes[i]) || 100;
      return { r, track: radialArcPath(50, 50, r, start, start + sweep, clockwise), share: round(Math.min(100, Math.max(0, (finite(v) / max) * 100))) };
    }),
  };
}
