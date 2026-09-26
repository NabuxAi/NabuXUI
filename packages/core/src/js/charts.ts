/**
 * Chart geometry, shared by the React charts and the Alpine ones so both draw
 * exactly the same marks. Pure functions: numbers in, SVG path strings out.
 */

export type Point = readonly [number, number];

export interface LinearScale {
  (value: number): number;
  invert(position: number): number;
  domain: readonly [number, number];
  range: readonly [number, number];
}

export function linearScale(domain: readonly [number, number], range: readonly [number, number]): LinearScale {
  const [d0, d1] = domain;
  const [r0, r1] = range;
  const span = d1 - d0 || 1;
  const scale = ((value: number) => r0 + ((value - d0) / span) * (r1 - r0)) as LinearScale;
  scale.invert = (position: number) => d0 + ((position - r0) / (r1 - r0 || 1)) * span;
  scale.domain = domain;
  scale.range = range;
  return scale;
}

/** Round, human tick values (1, 2, 2.5, 5 × 10ⁿ) covering [min, max]. */
export function niceTicks(min: number, max: number, count = 5): number[] {
  if (min === max) {
    if (min === 0) return [0, 1];
    min = Math.min(0, min);
    max = Math.max(0, max);
  }
  const rough = (max - min) / Math.max(1, count);
  const magnitude = 10 ** Math.floor(Math.log10(rough));
  const step = [1, 2, 2.5, 5, 10].map((m) => m * magnitude).find((s) => s >= rough) ?? 10 * magnitude;
  const start = Math.floor(min / step) * step;
  const end = Math.ceil(max / step) * step;
  const ticks: number[] = [];
  for (let v = start; v <= end + step / 2; v += step) ticks.push(Number(v.toFixed(10)));
  return ticks;
}

const round = (n: number) => Math.round(n * 100) / 100;

/**
 * A line through the points. Smoothing uses monotone cubic interpolation
 * (Fritsch–Carlson), which never overshoots: a smoothed line cannot dip below
 * zero or invent a peak the data does not have.
 */
export function linePath(points: readonly Point[], smooth = true): string {
  if (points.length === 0) return '';
  if (points.length === 1) return `M${round(points[0]![0])},${round(points[0]![1])}`;
  if (!smooth || points.length === 2) return points.map(([x, y], i) => `${i ? 'L' : 'M'}${round(x)},${round(y)}`).join('');

  const n = points.length;
  const dx: number[] = [];
  const slope: number[] = [];
  for (let i = 0; i < n - 1; i++) {
    dx[i] = points[i + 1]![0] - points[i]![0];
    slope[i] = (points[i + 1]![1] - points[i]![1]) / (dx[i] || 1);
  }

  const tangent: number[] = [slope[0]!];
  for (let i = 1; i < n - 1; i++) {
    const a = slope[i - 1]!;
    const b = slope[i]!;
    tangent[i] = a * b <= 0 ? 0 : (3 * (dx[i - 1]! + dx[i]!)) / ((2 * dx[i]! + dx[i - 1]!) / a + (dx[i]! + 2 * dx[i - 1]!) / b);
  }
  tangent[n - 1] = slope[n - 2]!;

  let d = `M${round(points[0]![0])},${round(points[0]![1])}`;
  for (let i = 0; i < n - 1; i++) {
    const [x0, y0] = points[i]!;
    const [x1, y1] = points[i + 1]!;
    const h = dx[i]! / 3;
    d += `C${round(x0 + h)},${round(y0 + tangent[i]! * h)} ${round(x1 - h)},${round(y1 - tangent[i + 1]! * h)} ${round(x1)},${round(y1)}`;
  }
  return d;
}

/** The line closed down to a baseline, for the area wash under it. */
export function areaPath(points: readonly Point[], baseline: number, smooth = true): string {
  if (points.length === 0) return '';
  const first = points[0]!;
  const last = points[points.length - 1]!;
  return `${linePath(points, smooth)}L${round(last[0])},${round(baseline)}L${round(first[0])},${round(baseline)}Z`;
}

/**
 * A bar rising from `baseline` to `valueY`, rounded only at its data end and
 * square where it meets the axis (downward bars round at the bottom instead).
 */
export function barPath(x: number, width: number, valueY: number, baseline: number, radius = 4): string {
  const up = valueY <= baseline;
  const top = Math.min(valueY, baseline);
  const height = Math.abs(baseline - valueY);
  const r = Math.min(radius, width / 2, height);
  const [x0, x1, y0, y1] = [round(x), round(x + width), round(top), round(top + height)];
  if (r <= 0) return `M${x0},${y0}H${x1}V${y1}H${x0}Z`;
  return up
    ? `M${x0},${y1}V${round(y0 + r)}Q${x0},${y0} ${round(x0 + r)},${y0}H${round(x1 - r)}Q${x1},${y0} ${x1},${round(y0 + r)}V${y1}Z`
    : `M${x0},${y0}V${round(y1 - r)}Q${x0},${y1} ${round(x0 + r)},${y1}H${round(x1 - r)}Q${x1},${y1} ${x1},${round(y1 - r)}V${y0}Z`;
}

export interface BarBand {
  /** Left edge of the bar. */
  x: number;
  width: number;
  /** Centre of the band, for labels and the crosshair. */
  center: number;
  /** The whole band: the hover target is bigger than the mark. */
  bandX: number;
  bandWidth: number;
}

/** Bands for `count` categories across `width`; bars are capped at 24px and centred. */
export function bands(count: number, width: number, { maxBar = 24, fill = 0.6 } = {}): BarBand[] {
  const band = width / Math.max(1, count);
  const bar = Math.min(maxBar, band * fill);
  return Array.from({ length: count }, (_, i) => ({
    x: i * band + (band - bar) / 2,
    width: bar,
    center: i * band + band / 2,
    bandX: i * band,
    bandWidth: band,
  }));
}

export interface DonutSegment {
  /** Visible length, in units of pathLength="100". */
  length: number;
  /** stroke-dashoffset that starts the segment where the previous one ended. */
  offset: number;
  value: number;
  share: number;
}

/** Segments of a ring (pathLength 100) with a surface gap between neighbours. */
export function donutSegments(values: readonly number[], gap = 1.2): DonutSegment[] {
  const total = values.reduce((sum, v) => sum + Math.max(0, v), 0) || 1;
  const gaps = values.filter((v) => v > 0).length > 1 ? gap : 0;
  let cursor = 0;
  return values.map((value) => {
    const share = Math.max(0, value) / total;
    const length = Math.max(0, share * 100 - gaps);
    const segment = { length: round(length), offset: round(-cursor), value, share };
    cursor += share * 100;
    return segment;
  });
}

/** Index of the point nearest to `x` (for the crosshair). */
export function nearestIndex(xs: readonly number[], x: number): number {
  let best = 0;
  let distance = Infinity;
  xs.forEach((value, i) => {
    const d = Math.abs(value - x);
    if (d < distance) {
      distance = d;
      best = i;
    }
  });
  return best;
}
