// @vitest-environment happy-dom
import { describe, expect, it, vi } from 'vitest';

type Callback = (entries: Partial<IntersectionObserverEntry>[]) => void;
const observers: { callback: Callback; options: IntersectionObserverInit }[] = [];

vi.stubGlobal(
  'IntersectionObserver',
  class {
    constructor(callback: Callback, options: IntersectionObserverInit) {
      observers.push({ callback, options });
    }
    observe() {}
    unobserve() {}
    disconnect() {}
  },
);

const { reveal } = await import('../src/js/reveal');

const entry = (target: Element, height: number, ratio: number) => ({
  target,
  isIntersecting: ratio > 0,
  intersectionRatio: ratio,
  boundingClientRect: { height } as DOMRectReadOnly,
  rootBounds: { height: 800 } as DOMRectReadOnly,
});

describe('reveal', () => {
  it('waits for the threshold on an element that fits the viewport', () => {
    const el = document.createElement('div');
    reveal(el);
    const { callback, options } = observers.at(-1)!;
    expect(options.threshold).toEqual([0, 0.15]);

    callback([entry(el, 400, 0.05)]);
    expect(el.hasAttribute('data-nx-revealed')).toBe(false);
    callback([entry(el, 400, 0.2)]);
    expect(el.hasAttribute('data-nx-revealed')).toBe(true);
  });

  it('reveals an element too tall to ever reach the threshold as soon as it enters', () => {
    const el = document.createElement('div');
    reveal(el);
    observers.at(-1)!.callback([entry(el, 12000, 0.01)]);
    expect(el.hasAttribute('data-nx-revealed')).toBe(true);
  });
});
