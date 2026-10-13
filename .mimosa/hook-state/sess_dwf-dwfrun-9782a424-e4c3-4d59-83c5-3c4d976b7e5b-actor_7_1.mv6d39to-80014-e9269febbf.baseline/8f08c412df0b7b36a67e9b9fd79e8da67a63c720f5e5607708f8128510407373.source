import { describe, expect, it } from 'vitest';
import { lensMap, rippleMap } from '../src/js/glass';

const at = (map: ReturnType<typeof lensMap>, x: number, y: number) => {
  const o = (y * map.width + x) * 4;
  return { r: map.data[o], g: map.data[o + 1], b: map.data[o + 2], a: map.data[o + 3] };
};

describe('lensMap', () => {
  it('leaves the middle of a plain lens alone', () => {
    const map = lensMap({ width: 100, height: 60, bezel: 12, density: 1 });
    const mid = at(map, 50, 30);
    expect(mid.r).toBe(128);
    expect(mid.g).toBe(128);
    expect(mid.a).toBe(255);
  });

  it('bends the rim outward for positive refraction and inward for negative', () => {
    const out = lensMap({ width: 100, height: 60, bezel: 12, refraction: 8, density: 1 });
    const edge = at(out, 1, 30);
    // Left rim samples further left (R below 128), right rim further right.
    expect(edge.r).toBeLessThan(128);
    expect(at(out, 98, 30).r).toBeGreaterThan(128);
    // The pill end is round: a pixel just below the axis bends a little downward too.
    expect(Math.abs(edge.g - 128)).toBeLessThanOrEqual(3);

    const inward = lensMap({ width: 100, height: 60, bezel: 12, refraction: -8, density: 1 });
    expect(at(inward, 1, 30).r).toBeGreaterThan(128);
  });

  it('magnifies by sampling toward the centre', () => {
    const map = lensMap({ width: 80, height: 80, bezel: 1, refraction: 0, zoom: 2, density: 1 });
    // Right of centre samples back toward the centre (R below 128).
    expect(at(map, 60, 40).r).toBeLessThan(128);
    expect(at(map, 20, 40).r).toBeGreaterThan(128);
    // The scale decodes the largest offset: at x = 79.5 − 40 the pull is 39.5 × (1 − 1/2).
    expect(map.scale).toBeCloseTo((39.5 * 0.5 * 255) / 127, 0);
  });

  it('cuts the corners of the shape', () => {
    const map = lensMap({ width: 60, height: 60, radius: 30, density: 1 });
    expect(at(map, 0, 0).a).toBe(0);
    expect(at(map, 30, 0).a).toBeGreaterThan(0);
    expect(at(map, 30, 30).a).toBe(255);
  });

  it('is mirror-symmetric', () => {
    const map = lensMap({ width: 90, height: 40, bezel: 10, refraction: 6, density: 1 });
    for (const [x, y] of [[3, 7], [12, 20], [40, 2]]) {
      const a = at(map, x, y);
      const b = at(map, map.width - 1 - x, map.height - 1 - y);
      expect(a.r + b.r).toBeGreaterThanOrEqual(255);
      expect(a.r + b.r).toBeLessThanOrEqual(257);
      expect(a.g + b.g).toBeGreaterThanOrEqual(255);
      expect(a.g + b.g).toBeLessThanOrEqual(257);
    }
  });

  it('draws the map at twice the density for small lenses', () => {
    expect(lensMap({ width: 40, height: 20 }).width).toBe(80);
    expect(lensMap({ width: 400, height: 200 }).width).toBe(400);
  });
});

describe('rippleMap', () => {
  it('is calm in the middle and moves content in the ring', () => {
    const map = rippleMap(64, 0.4);
    const mid = at(map, 32, 32);
    expect(mid.r).toBe(128);
    expect(mid.g).toBe(128);
    let moved = 0;
    for (let x = 32; x < 64; x += 1) moved = Math.max(moved, Math.abs(at(map, x, 32).r - 128));
    expect(moved).toBeGreaterThan(60);
    expect(at(map, 0, 0).a).toBe(0);
  });
});
