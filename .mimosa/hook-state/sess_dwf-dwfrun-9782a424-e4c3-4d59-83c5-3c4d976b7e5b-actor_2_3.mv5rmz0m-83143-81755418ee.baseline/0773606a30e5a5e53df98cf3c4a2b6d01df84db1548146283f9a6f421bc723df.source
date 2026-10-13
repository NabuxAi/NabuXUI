import {
  type MutableRefObject,
  type Ref,
  type RefCallback,
  type RefObject,
  useCallback,
  useEffect,
  useLayoutEffect,
  useRef,
  useState,
} from 'react';
import { type Cleanup, type IndicatorController, indicator } from '@nabuxai/ui-core';

/** Join class names, skipping falsy parts. */
export function cx(...parts: Array<string | false | null | undefined>): string {
  return parts.filter(Boolean).join(' ');
}

/** useLayoutEffect in the browser, useEffect on the server (no SSR warning). */
export const useIsoLayoutEffect = typeof window !== 'undefined' ? useLayoutEffect : useEffect;

/** One ref callback that feeds several refs (a forwarded one and our own). */
export function mergeRefs<T>(...refs: Array<Ref<T> | undefined>): RefCallback<T> {
  return (value) => {
    for (const ref of refs) {
      if (typeof ref === 'function') ref(value);
      else if (ref) (ref as MutableRefObject<T | null>).current = value;
    }
  };
}

/** A callback whose identity never changes but always calls the latest function. */
export function useEvent<A extends unknown[], R>(fn: ((...args: A) => R) | undefined): (...args: A) => R | undefined {
  const ref = useRef(fn);
  useIsoLayoutEffect(() => {
    ref.current = fn;
  });
  return useCallback((...args: A) => ref.current?.(...args), []);
}

/**
 * State that is controlled when `value` is given and internal otherwise —
 * the value / defaultValue / onValueChange convention every stateful component uses.
 */
export function useControllable<T>(value: T | undefined, defaultValue: T, onChange?: (value: T) => void): [T, (next: T) => void] {
  const [internal, setInternal] = useState(defaultValue);
  const controlled = value !== undefined;
  const current = controlled ? (value as T) : internal;
  const notify = useEvent(onChange);

  const set = useCallback(
    (next: T) => {
      if (!controlled) setInternal(next);
      notify(next);
    },
    [controlled, notify],
  );

  return [current, set];
}

/**
 * Attach a core behaviour (reveal, magnetic, tilt…) to an element for as long
 * as it is mounted and `enabled`. Options are compared by value.
 */
export function useBehavior<E extends Element, O>(
  ref: RefObject<E | null>,
  behavior: (el: E, options: O) => Cleanup,
  options: O,
  enabled = true,
): void {
  const key = JSON.stringify(options ?? null);
  useEffect(() => {
    const el = ref.current;
    if (!enabled || !el) return;
    return behavior(el, options);
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [enabled, behavior, key]);
}

/** Has the component mounted in the browser yet (for markup that must match SSR). */
export function useMounted(): boolean {
  const [mounted, setMounted] = useState(false);
  useEffect(() => setMounted(true), []);
  return mounted;
}

/**
 * Toggle the `inert` attribute directly: React 18 and 19 disagree on how the
 * prop is spelled, but the DOM attribute means the same everywhere.
 */
export function useInert(ref: RefObject<Element | null>, inert: boolean): void {
  useIsoLayoutEffect(() => {
    ref.current?.toggleAttribute('inert', inert);
  }, [ref, inert]);
}

/** The element's content-box width, tracked as it resizes (0 until measured). */
export function useWidth(ref: RefObject<Element | null>, fallback = 0): number {
  const [width, setWidth] = useState(fallback);
  useIsoLayoutEffect(() => {
    const el = ref.current;
    if (!el) return;
    setWidth(el.getBoundingClientRect().width || fallback);
    if (typeof ResizeObserver === 'undefined') return;
    const observer = new ResizeObserver(([entry]) => entry && setWidth(entry.contentRect.width));
    observer.observe(el);
    return () => observer.disconnect();
  }, [ref, fallback]);
  return width;
}

/** The moving highlight (core `indicator`) on `container`, for as long as it is mounted. */
export function useIndicator(container: RefObject<HTMLElement | null>): RefObject<IndicatorController | null> {
  const ctrl = useRef<IndicatorController | null>(null);
  useIsoLayoutEffect(() => {
    if (!container.current) return;
    ctrl.current = indicator(container.current);
    return () => {
      ctrl.current?.destroy();
      ctrl.current = null;
    };
  }, [container]);
  return ctrl;
}
