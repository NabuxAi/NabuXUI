// @vitest-environment happy-dom
import { afterEach, beforeEach, describe, expect, it } from 'vitest';
import { carouselOffset, coverProgress, elasticStep, flip, pitProfile, pitSlider, ringCarousel, ringIndex, ringTurnFor, stackedScroll } from '../src/js/blocks/cards';
import { springs } from '../src/js/spring';

describe('carousel offsets', () => {
  it('measures the signed distance from the active item', () => {
    expect(carouselOffset(3, 1, 5, false)).toBe(2);
    expect(carouselOffset(0, 2, 5, false)).toBe(-2);
  });

  it('takes the short way round when looping', () => {
    expect(carouselOffset(4, 0, 5)).toBe(-1);
    expect(carouselOffset(0, 4, 5)).toBe(1);
    expect(carouselOffset(2, 0, 5)).toBe(2);
    expect(carouselOffset(3, 0, 5)).toBe(-2);
    // Exactly opposite in an even ring stays on the far (positive) side.
    expect(carouselOffset(2, 0, 4)).toBe(2);
  });
});

describe('ring turns', () => {
  it('names the item facing front at any turn, even negative', () => {
    expect(ringIndex(0, 6)).toBe(0);
    expect(ringIndex(7.4, 6)).toBe(1);
    expect(ringIndex(-1, 6)).toBe(5);
    expect(ringIndex(-6.6, 6)).toBe(5);
  });

  it('finds the nearest turn that brings an item to the front', () => {
    expect(ringTurnFor(1, 0, 6)).toBe(1);
    expect(ringTurnFor(5, 0, 6)).toBe(-1);
    // Twelve turns in, item 0 is one step ahead, not twelve back.
    expect(ringTurnFor(0, 11, 6)).toBe(12);
    expect(ringIndex(ringTurnFor(4, 23.2, 8), 8)).toBe(4);
    expect(Math.abs(ringTurnFor(4, 23.2, 8) - 23.2)).toBeLessThanOrEqual(4.5);
  });
});

describe('pit profile', () => {
  it('dips deepest under the thumb and recovers with distance', () => {
    const bars = pitProfile(21, 10, { depth: 0.6, spread: 2 });
    expect(bars).toHaveLength(21);
    expect(bars[10]).toBeCloseTo(0.4, 5);
    expect(bars[9]!).toBeGreaterThan(bars[10]!);
    expect(bars[7]!).toBeGreaterThan(bars[9]!);
    expect(bars[0]!).toBeGreaterThan(0.999);
    // Symmetric around the centre.
    expect(bars[8]).toBeCloseTo(bars[12]!, 10);
  });

  it('is flat without depth and follows a fractional centre', () => {
    expect(pitProfile(5, 2, { depth: 0 })).toEqual([1, 1, 1, 1, 1]);
    const between = pitProfile(4, 1.5, { depth: 0.5, spread: 1 });
    expect(between[1]).toBeCloseTo(between[2]!, 10);
    expect(pitProfile(0, 0)).toEqual([]);
  });
});

describe('cover progress', () => {
  it('runs from touching to resting one offset below', () => {
    // A 400px card stuck at 100 (bottom 500); the next rests 20px lower.
    expect(coverProgress(500, 560, 400, 20)).toBe(0);
    expect(coverProgress(500, 500, 400, 20)).toBe(0);
    expect(coverProgress(500, 310, 400, 20)).toBeCloseTo(0.5, 5);
    expect(coverProgress(500, 120, 400, 20)).toBe(1);
    expect(coverProgress(500, 40, 400, 20)).toBe(1);
  });
});

describe('elastic step', () => {
  it('settles on the target', () => {
    let state = { value: 0, velocity: 0 };
    for (let i = 0; i < 180; i++) state = elasticStep(state, 100, 1 / 60, springs.gentle);
    expect(state.value).toBeCloseTo(100, 1);
    expect(Math.abs(state.velocity)).toBeLessThan(0.5);
  });

  it('stays stable on long frames', () => {
    let state = { value: 0, velocity: 0 };
    for (let i = 0; i < 40; i++) state = elasticStep(state, 1, 0.25, springs.bouncy);
    expect(Number.isFinite(state.value)).toBe(true);
    expect(state.value).toBeCloseTo(1, 3);
  });

  it('overshoots when under-damped and not when damped hard', () => {
    const peak = (config: { stiffness: number; damping: number }) => {
      let state = { value: 0, velocity: 0 };
      let top = 0;
      for (let i = 0; i < 240; i++) {
        state = elasticStep(state, 1, 1 / 120, config);
        top = Math.max(top, state.value);
      }
      return top;
    };
    expect(peak(springs.bouncy)).toBeGreaterThan(1.1);
    expect(peak({ stiffness: 100, damping: 40 })).toBeLessThanOrEqual(1.0001);
  });
});

/* ---- Behaviours, wired to a DOM ------------------------------------------------ */

const frame = () => new Promise<void>((resolve) => requestAnimationFrame(() => resolve()));

describe('behaviours', () => {
  const realMatchMedia = window.matchMedia;

  beforeEach(() => {
    // Reduced motion: the ring jumps instead of springing, so the results are immediate.
    window.matchMedia = ((query: string) => ({
      matches: query.includes('reduce'),
      media: query,
      addEventListener() {},
      removeEventListener() {},
    })) as unknown as typeof window.matchMedia;
  });

  afterEach(() => {
    window.matchMedia = realMatchMedia;
    document.body.innerHTML = '';
  });

  it('turns the ring with the keys, the buttons and go(), the short way round', () => {
    const el = document.createElement('section');
    document.body.appendChild(el);
    const seen: number[] = [];
    const ring = ringCarousel(el, { count: 6, index: 0, onChange: (i) => seen.push(i) });
    el.dispatchEvent(new KeyboardEvent('keydown', { key: 'ArrowRight', bubbles: true }));
    expect(ring.index).toBe(1);
    expect(el.style.getPropertyValue('--nx-turn')).toBe('1.0000');
    ring.prev();
    ring.prev();
    expect(ring.index).toBe(5);
    expect(el.style.getPropertyValue('--nx-turn')).toBe('-1.0000');
    ring.go(3);
    expect(ring.index).toBe(3);
    el.dispatchEvent(new KeyboardEvent('keydown', { key: 'End', bubbles: true }));
    expect(ring.index).toBe(5);
    expect(seen).toEqual([1, 0, 5, 3, 5]);
    ring.destroy();
  });

  it('turns "next" toward inline-end in right-to-left pages', () => {
    const el = document.createElement('section');
    el.setAttribute('dir', 'rtl');
    el.style.direction = 'rtl';
    document.body.appendChild(el);
    const ring = ringCarousel(el, { count: 4 });
    el.dispatchEvent(new KeyboardEvent('keydown', { key: 'ArrowLeft', bubbles: true }));
    expect(ring.index).toBe(1);
    ring.destroy();
  });

  it('writes the stacked cards\' cover progress where there is no scroll timeline', () => {
    const el = document.createElement('section');
    el.innerHTML = '<ol><li class="nx-stacked-item"></li><li class="nx-stacked-item"></li></ol>';
    document.body.appendChild(el);
    const stop = stackedScroll(el, { force: true });
    const items = el.querySelectorAll<HTMLElement>('.nx-stacked-item');
    expect(el.hasAttribute('data-nx-scripted')).toBe(true);
    expect(items[0]!.style.getPropertyValue('--nx-progress')).not.toBe('');
    expect(items[1]!.style.getPropertyValue('--nx-depth')).toBe('0.000');
    stop();
    expect(items[0]!.style.getPropertyValue('--nx-progress')).toBe('');
  });

  it('keeps the pit flat under reduced motion but tracks the press', async () => {
    const el = document.createElement('div');
    el.innerHTML = '<span class="nx-pit-bar"></span><span class="nx-pit-bar"></span><span class="nx-pit-bar"></span><input type="range" min="0" max="100" value="50">';
    document.body.appendChild(el);
    const stop = pitSlider(el);
    const input = el.querySelector('input')!;
    input.dispatchEvent(new PointerEvent('pointerdown', { button: 0, bubbles: true }));
    await frame();
    expect(el.hasAttribute('data-dragging')).toBe(true);
    expect(el.querySelector<HTMLElement>('.nx-pit-bar')!.style.getPropertyValue('--h')).toBe('1');
    window.dispatchEvent(new PointerEvent('pointerup'));
    expect(el.hasAttribute('data-dragging')).toBe(false);
    stop();
  });

  it('digs the pit under the thumb while it is held', async () => {
    window.matchMedia = ((query: string) => ({ matches: false, media: query, addEventListener() {}, removeEventListener() {} })) as unknown as typeof window.matchMedia;
    const el = document.createElement('div');
    el.innerHTML = `${'<span class="nx-pit-bar"></span>'.repeat(21)}<input type="range" min="0" max="100" value="50">`;
    document.body.appendChild(el);
    const stop = pitSlider(el);
    el.querySelector('input')!.dispatchEvent(new PointerEvent('pointerdown', { button: 0, bubbles: true }));
    await frame();
    const heights = Array.from(el.querySelectorAll<HTMLElement>('.nx-pit-bar'), (bar) => Number(bar.style.getPropertyValue('--h')));
    // The thumb sits over the middle bar: the pit is deepest there and gone at the ends.
    expect(Math.min(...heights)).toBe(heights[10]);
    expect(heights[10]!).toBeLessThan(0.5);
    expect(heights[9]).toBeCloseTo(heights[11]!, 5);
    expect(heights[0]!).toBeGreaterThan(0.99);
    stop();
  });

  it('runs the FLIP mutation and settles, animated or not', async () => {
    const list = document.createElement('ul');
    list.innerHTML = '<li></li><li></li>';
    document.body.appendChild(list);
    let mutated = false;
    await flip(list.children, () => {
      mutated = true;
      list.append(list.firstElementChild!);
    });
    expect(mutated).toBe(true);
    let later = false;
    await flip(list.children, () => Promise.resolve().then(() => (later = true)));
    expect(later).toBe(true);
  });
});
