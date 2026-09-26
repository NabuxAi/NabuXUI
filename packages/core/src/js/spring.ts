/**
 * Springs, expressed as CSS `linear()` easings.
 *
 * CSS cannot run a physics simulation, but `linear()` can replay one: sample a
 * damped oscillator from 0 to 1, keep only the points that matter, and the
 * browser interpolates between them on the compositor. The same string works
 * for `transition-timing-function`, `animation-timing-function` and the Web
 * Animations API, so a spring means the same thing in CSS, React and Alpine.
 */

export interface SpringConfig {
  /** Stiffness (k). Higher is faster. */
  stiffness: number;
  /** Damping (c). Lower bounces more. */
  damping: number;
  /** Mass (m). Higher is heavier and slower. */
  mass?: number;
}

export interface SpringEasing {
  /** A CSS `linear()` timing function. */
  easing: string;
  /** How long the spring takes to settle, in milliseconds. */
  duration: number;
}

/** Position of a unit spring released from 0 towards 1, at time t (seconds). */
export function springAt(t: number, { stiffness, damping, mass = 1 }: SpringConfig): number {
  const w0 = Math.sqrt(stiffness / mass);
  const zeta = damping / (2 * Math.sqrt(stiffness * mass));

  if (zeta < 1) {
    const wd = w0 * Math.sqrt(1 - zeta * zeta);
    return 1 - Math.exp(-zeta * w0 * t) * (Math.cos(wd * t) + ((zeta * w0) / wd) * Math.sin(wd * t));
  }

  if (zeta === 1) {
    return 1 - Math.exp(-w0 * t) * (1 + w0 * t);
  }

  const wd = w0 * Math.sqrt(zeta * zeta - 1);
  return 1 - Math.exp(-zeta * w0 * t) * (Math.cosh(wd * t) + ((zeta * w0) / wd) * Math.sinh(wd * t));
}

/** Seconds until the spring stays within `precision` of its target. */
export function settleTime(config: SpringConfig, precision = 0.001): number {
  const step = 1 / 600;
  let lastOutside = 0;
  for (let t = 0; t < 10; t += step) {
    if (Math.abs(springAt(t, config) - 1) > precision) lastOutside = t;
    else if (t - lastOutside > 0.25) break;
  }
  return lastOutside + step;
}

type Point = [number, number];

/** Ramer–Douglas–Peucker: drop points that sit on the line between their neighbours. */
function simplify(points: Point[], tolerance: number): Point[] {
  if (points.length < 3) return points;
  const [ax, ay] = points[0]!;
  const [bx, by] = points[points.length - 1]!;
  let index = 0;
  let furthest = 0;
  for (let i = 1; i < points.length - 1; i++) {
    const [px, py] = points[i]!;
    // Vertical distance from the chord: easing curves are functions of time.
    const expected = ay + ((px - ax) / (bx - ax)) * (by - ay);
    const distance = Math.abs(py - expected);
    if (distance > furthest) {
      furthest = distance;
      index = i;
    }
  }
  if (furthest <= tolerance) return [points[0]!, points[points.length - 1]!];
  const left = simplify(points.slice(0, index + 1), tolerance);
  const right = simplify(points.slice(index), tolerance);
  return left.slice(0, -1).concat(right);
}

const round = (value: number, digits: number) => {
  const factor = 10 ** digits;
  return Math.round(value * factor) / factor;
};

/** Turn a spring into a `linear()` easing plus the duration it needs. */
export function springEasing(config: SpringConfig, tolerance = 0.0025): SpringEasing {
  const duration = settleTime(config);
  const samples = 240;
  const points: Point[] = [];
  for (let i = 0; i <= samples; i++) {
    const progress = i / samples;
    points.push([progress, springAt(progress * duration, config)]);
  }
  points[points.length - 1] = [1, 1];

  const stops = simplify(points, tolerance).map(([x, y], i, all) => {
    const value = round(y, 3);
    // The first and last stops sit at 0% and 100% implicitly.
    if (i === 0 || i === all.length - 1) return String(value);
    return `${value} ${round(x * 100, 1)}%`;
  });

  return {
    easing: `linear(${stops.join(', ')})`,
    duration: Math.round(duration * 1000),
  };
}

/** The springs every NabuXUI component is tuned against. */
export const springs = {
  /** Small controls: switches, checkboxes, pills. Quick with a hint of overshoot. */
  snappy: { stiffness: 420, damping: 34 },
  /** The default for things that move: cards, panels, indicators. */
  gentle: { stiffness: 170, damping: 19 },
  /** Playful moments: likes, badges, celebrations. */
  bouncy: { stiffness: 260, damping: 12 },
  /** Large surfaces that should arrive without overshoot: sheets, pages. */
  soft: { stiffness: 210, damping: 30 },
} satisfies Record<string, SpringConfig>;

export type SpringName = keyof typeof springs;
