import { onBeforeUnmount, onMounted, ref, watch, type Ref } from 'vue';
import type { Cleanup } from '@nabuxai/ui-core';

/**
 * Attach a core behaviour (reveal, magnetic, tilt…) to an element for as long
 * as it is mounted and `enabled` — the Vue counterpart of ui-react's
 * `useBehavior`. Returns the template ref to hand the element:
 *
 *   const seat = useNxBehavior(reveal, { stagger: true });
 *   <section ref="seat">…</section>
 *
 * Options are compared by value, exactly like the react hook.
 */
export function useNxBehavior<E extends Element, O>(
  behavior: (el: E, options: O) => Cleanup,
  options: O,
  enabled = true,
): Ref<E | null> {
  const el = ref<E | null>(null) as Ref<E | null>;
  let cleanup: Cleanup | undefined;

  const attach = () => {
    cleanup?.();
    cleanup = undefined;
    const node = el.value;
    if (enabled && node) cleanup = behavior(node, options);
  };

  onMounted(attach);
  watch([el, () => JSON.stringify(options ?? null)], attach, { flush: 'post' });
  onBeforeUnmount(() => cleanup?.());

  return el;
}
