<script lang="ts">
/**
 * NxPopover — the Vue counterpart of ui-react's `Popover`: a light-dismiss
 * panel on the native Popover API (with a data-open fallback), placed by
 * the core around its trigger. The trigger comes in through the `trigger`
 * slot and is augmented the way React's cloneElement does.
 */
</script>

<script setup lang="ts">
import { cloneVNode, mergeProps, onMounted, ref, useId, useSlots, watchEffect, type ComponentPublicInstance } from 'vue';
import { place, type Align, type Side } from '@nabuxai/ui-core';
import { NxNode } from '../kit';

defineOptions({ inheritAttrs: false });

const props = withDefaults(defineProps<{ side?: 'top' | 'bottom' | 'start' | 'end'; align?: Align; label?: string }>(), { side: 'bottom', align: 'center' });

const id = `nx-pop${useId().replace(/[:-]/g, '')}`;
const panel = ref<HTMLDivElement | null>(null);
const button = ref<HTMLElement | null>(null);
const open = ref(false);
const slots = useSlots();

const supportsPopover = () => typeof HTMLElement !== 'undefined' && 'popover' in HTMLElement.prototype;

const SIDES: Record<'top' | 'bottom' | 'start' | 'end', Side> = { top: 'top', bottom: 'bottom', start: 'inline-start', end: 'inline-end' };

// Declarative invoker: the browser toggles the panel and exempts the button
// from light dismiss.
onMounted(() => {
  if (supportsPopover()) button.value?.setAttribute('popovertarget', id);
});

// The popover opens and light-dismisses natively; keep our view in step.
const onToggle = (event: Event) => {
  open.value = (event as ToggleEvent).newState === 'open';
};

// Place the panel around its button while open.
watchEffect(
  (onCleanup) => {
    if (!open.value || !panel.value || !button.value) return;
    onCleanup(place(button.value, panel.value, { side: SIDES[props.side], align: props.align, offset: 8 }));
  },
  { flush: 'post' },
);

/** The trigger slot's own vnode, augmented — recomputed every render so the slot stays live. */
const triggerVNode = () => {
  const vnode = slots.trigger?.()[0];
  if (!vnode) return null;
  return cloneVNode(
    vnode,
    mergeProps(vnode.props ?? {}, {
      'aria-expanded': open.value,
      'aria-controls': id,
      ref: (el: Element | ComponentPublicInstance | null) => {
        // A seat component as the trigger hands its instance over; take the element either way.
        button.value = el instanceof HTMLElement ? el : ((el as ComponentPublicInstance | null)?.$el instanceof HTMLElement ? ((el as ComponentPublicInstance).$el as HTMLElement) : null);
      },
      onClick: () => {
        if (!supportsPopover()) {
          const el = panel.value;
          el?.toggleAttribute('data-open');
          open.value = !!el?.hasAttribute('data-open');
        }
      },
    }),
  );
};
</script>

<template>
  <NxNode :node="triggerVNode()" />
  <div
    ref="panel"
    v-bind="$attrs"
    :id="id"
    class="nx-popover"
    role="dialog"
    :aria-label="label"
    popover="auto"
    @toggle="onToggle"
  >
    <slot />
  </div>
</template>
