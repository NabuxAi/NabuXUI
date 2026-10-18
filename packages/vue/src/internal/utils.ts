/**
 * The small shared kit the Vue component parts are built on — the counterpart
 * of ui-react's `internal/hooks.ts`: class joining, the reactive behaviour
 * seat, width tracking and the inert toggle.
 */
import { onBeforeUnmount, onMounted, ref, watch, type Ref, type VNode } from 'vue';
import type { Cleanup } from '@nabuxai/ui-core';
import type { NxLang } from '../i18n';

/** Anything a prop or slot may hand a component to paint. */
export type VueNode = string | number | VNode | VNode[] | null | undefined;

/** The swipe stack's two verdicts. */
export type SwipeVerdict = 'accept' | 'reject';

/** A readable element ref — accepts both `ref()` seats and `useTemplateRef()`. */
export interface NxRefLike<E> {
  readonly value: E | null;
}

/** Join class names, skipping falsy parts — `cx('nx-card', className)`. */
export function cx(...parts: Array<string | false | null | undefined>): string {
  return parts.filter(Boolean).join(' ');
}

/** The Intl locale each shipped language formats numbers in by default. */
export const INTL_LOCALES: Record<NxLang, string> = { en: 'en-US', fa: 'fa-IR' };

/**
 * The reactive sibling of `useNxBehavior`: re-attaches when the element, the
 * options (by value) or the `enabled()` flag change, and cleans up on unmount.
 * Props-driven gates (`:spotlight`, `:tilt`…) need this one.
 */
export function useNxBehaviorReactive<E extends Element, O>(
  behavior: (el: E, options: O) => Cleanup,
  options: O,
  enabled: () => boolean,
): Ref<E | null> {
  const el = ref<E | null>(null) as Ref<E | null>;
  attachBehavior(el, behavior, options, enabled);
  return el;
}

/**
 * Same contract on a ref the caller owns — for elements that carry several
 * behaviours at once, or whose ref is bound with `useTemplateRef`.
 */
export function useNxBehaviorOn<E extends Element, O>(
  el: NxRefLike<E>,
  behavior: (el: E, options: O) => Cleanup,
  options: O,
  enabled: () => boolean,
): void {
  attachBehavior(el, behavior, options, enabled);
}

function attachBehavior<E extends Element, O>(
  el: NxRefLike<E>,
  behavior: (el: E, options: O) => Cleanup,
  options: O,
  enabled: () => boolean,
): void {
  let cleanup: Cleanup | undefined;

  const attach = () => {
    cleanup?.();
    cleanup = undefined;
    const node = el.value;
    if (enabled() && node) cleanup = behavior(node, options);
  };

  onMounted(attach);
  watch([() => el.value, () => JSON.stringify(options ?? null), enabled], attach, { flush: 'post' });
  onBeforeUnmount(() => cleanup?.());
}

/**
 * The element's content-box width, tracked as it resizes (the fallback until
 * measured) — the Vue counterpart of ui-react's `useWidth`.
 */
export function useNxWidth(el: NxRefLike<Element>, fallback = 0): Ref<number> {
  const width = ref(fallback);
  let observer: ResizeObserver | undefined;

  const measure = () => {
    width.value = el.value?.getBoundingClientRect().width || fallback;
  };

  onMounted(() => {
    measure();
    if (typeof ResizeObserver === 'undefined') return;
    observer = new ResizeObserver((entries) => {
      const entry = entries[0];
      if (entry) width.value = entry.contentRect.width;
    });
    if (el.value) observer.observe(el.value);
  });

  // The watched element itself can come and go (v-if / list reshuffles).
  watch(
    () => el.value,
    (node, previous) => {
      if (previous) observer?.unobserve(previous);
      if (node) observer?.observe(node);
      measure();
    },
  );

  onBeforeUnmount(() => observer?.disconnect());
  return width;
}

/**
 * Toggle the `inert` attribute directly — the face turned away must stay out
 * of the tab order and out of the accessibility tree.
 */
export function useNxInert(el: NxRefLike<Element>, inert: () => boolean): void {
  watch([() => el.value, inert], ([node, on]) => node?.toggleAttribute('inert', !!on), { flush: 'post' });
}
