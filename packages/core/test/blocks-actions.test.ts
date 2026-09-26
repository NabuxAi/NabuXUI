import { describe, expect, it } from 'vitest';
import { parametricPath, pixelLoaderCells, pixelNoise } from '../src/js/blocks/actions';

/** Every coordinate pair of an M/L path. */
const coordinates = (d: string) => Array.from(d.matchAll(/[ML](-?\d+(?:\.\d+)?),(-?\d+(?:\.\d+)?)/g), (m) => [Number(m[1]), Number(m[2])] as const);

describe('parametricPath', () => {
  it('draws a closed path of straight segments', () => {
    for (const kind of ['rose', 'spiro', 'lissajous'] as const) {
      const d = parametricPath(kind);
      expect(d).toMatch(/^M[\d.]+,[\d.]+(L[\d.]+,[\d.]+)+Z$/);
    }
  });

  it('fits the curve inside the box, keeping the padding, and reaches it along some axis', () => {
    for (const kind of ['rose', 'spiro', 'lissajous'] as const) {
      const points = coordinates(parametricPath(kind, { padding: 10 }));
      const xs = points.map(([x]) => x);
      const ys = points.map(([, y]) => y);
      expect(Math.min(...xs, ...ys)).toBeGreaterThanOrEqual(9.9);
      expect(Math.max(...xs, ...ys)).toBeLessThanOrEqual(90.1);
      // Its furthest point from the centre, along x or y, touches the padding.
      expect(Math.max(...points.map(([x, y]) => Math.max(Math.abs(x - 50), Math.abs(y - 50))))).toBeGreaterThan(39.8);
    }
  });

  it('puts the centre of the curve in the middle, so a turning loader does not wobble', () => {
    // A five-petal rose is lopsided in its bounding box; its petals still meet at (50, 50).
    const rose = coordinates(parametricPath('rose', { n: 5, samples: 200 }));
    expect(rose.some(([x, y]) => Math.hypot(x - 50, y - 50) < 0.5)).toBe(true);
    const xs = rose.map(([x]) => x);
    expect((Math.max(...xs) + Math.min(...xs)) / 2).not.toBeCloseTo(50, 0);
  });

  it('samples as many points as asked', () => {
    expect(coordinates(parametricPath('lissajous', { samples: 64 }))).toHaveLength(64);
  });

  it('scales to another box size', () => {
    const points = coordinates(parametricPath('rose', { size: 24, padding: 2 }));
    expect(Math.max(...points.flat())).toBeLessThanOrEqual(22.1);
  });

  it('is deterministic and differs by kind and parameters', () => {
    expect(parametricPath('spiro')).toBe(parametricPath('spiro'));
    expect(parametricPath('rose')).not.toBe(parametricPath('lissajous'));
    expect(parametricPath('rose', { n: 5 })).not.toBe(parametricPath('rose', { n: 7 }));
    expect(parametricPath('spiro', { R: 5, r: 3 })).not.toBe(parametricPath('spiro', { R: 7, r: 4 }));
  });

  it('closes each curve after exactly one period', () => {
    // An odd rose (k = 5) closes after π; sampling past it would retrace the petals.
    const rose = coordinates(parametricPath('rose', { n: 5, samples: 200 }));
    const [first] = rose;
    const last = rose[rose.length - 1]!;
    expect(Math.hypot(first![0] - last[0], first![1] - last[1])).toBeLessThan(3);
    // The default hypotrochoid (R 7, r 3) needs three turns of the rolling circle to close.
    const spiro = coordinates(parametricPath('spiro', { samples: 300 }));
    expect(Math.hypot(spiro[0]![0] - spiro[299]![0], spiro[0]![1] - spiro[299]![1])).toBeLessThan(3);
  });
});

describe('pixel loader cells', () => {
  it('lays out rows × cols cells, row by row', () => {
    const cells = pixelLoaderCells(3, 24);
    expect(cells).toHaveLength(72);
    expect(cells[0]).toMatchObject({ x: 0, y: 0 });
    expect(cells[25]).toMatchObject({ x: 1, y: 1 });
  });

  it('measures the distance from the centre, 0 in the middle and 1 in the corners', () => {
    const cells = pixelLoaderCells(5, 5);
    expect(cells[12]!.d).toBe(0);
    expect(cells[0]!.d).toBe(1);
    expect(cells[24]!.d).toBe(1);
    expect(cells[7]!.d).toBeCloseTo(0.354, 3);
  });

  it('gives every cell fixed pseudo-random numbers in [0, 1) that look scattered', () => {
    const cells = pixelLoaderCells(3, 24);
    expect(pixelLoaderCells(3, 24)).toEqual(cells);
    const values = cells.map((cell) => cell.r);
    for (const value of [...values, ...cells.map((cell) => cell.s)]) {
      expect(value).toBeGreaterThanOrEqual(0);
      expect(value).toBeLessThan(1);
    }
    // Spread across the range, not bunched, and not a simple ramp.
    const mean = values.reduce((a, b) => a + b, 0) / values.length;
    expect(mean).toBeGreaterThan(0.35);
    expect(mean).toBeLessThan(0.65);
    expect(new Set(values.map((v) => Math.floor(v * 10))).size).toBeGreaterThanOrEqual(8);
    const rising = values.slice(1).filter((v, i) => v > values[i]!).length;
    expect(rising).toBeGreaterThan(20);
    expect(rising).toBeLessThan(52);
  });

  it('uses integer arithmetic only (so PHP renders the same pattern)', () => {
    expect(pixelNoise(0, 1)).toBe(Math.round((((3588 * 3588) % 10007) / 10007) * 1000) / 1000);
    expect(pixelNoise(123456, 2)).toBe(pixelNoise(123456, 2));
    expect(pixelNoise(4, 1)).not.toBe(pixelNoise(4, 2));
  });

  it('survives degenerate sizes', () => {
    expect(pixelLoaderCells(1, 1)).toEqual([{ x: 0, y: 0, d: 0, r: pixelNoise(0, 1), s: pixelNoise(0, 2) }]);
    expect(pixelLoaderCells(0, 0)).toHaveLength(1);
  });
});
