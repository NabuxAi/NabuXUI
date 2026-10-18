<script setup lang="ts">
/**
 * NxToaster — the Vue counterpart of ui-react's `Toaster`: render once near
 * the root, then call `toast(...)` from anywhere. The stack lays itself out
 * after every change and when a toast resizes; hovering it holds the timers.
 */
import { onBeforeUnmount, onMounted, shallowRef, useTemplateRef, watch } from 'vue';
import { type Toast, type ToastStore, stackToasts, toasts as defaultStore } from '@nabuxai/ui-core';
import NxToastItem from './nx-toast-item.vue';
import { useNxT } from './kit';

const props = withDefaults(
  defineProps<{
    position?: 'bottom-end' | 'bottom-start' | 'bottom-center' | 'top-end' | 'top-start' | 'top-center';
    /** Another store, if the page runs more than one (rare). */
    store?: ToastStore;
    /** `ripple`: a ring spreads from each toast as it lands. */
    effect?: 'ripple';
  }>(),
  { position: 'bottom-end', store: undefined },
);

const section = useTemplateRef<HTMLElement>('section');
const list = useTemplateRef<HTMLOListElement>('list');
const t = useNxT(section);

const store = () => props.store ?? defaultStore;

const items = shallowRef<readonly Toast[]>(store().getSnapshot());
let unsubscribe: (() => void) | undefined;

// Lay out after every change, and whenever any toast changes size.
let observer: ResizeObserver | undefined;
function relayout() {
  if (list.value) stackToasts(list.value);
  if (typeof ResizeObserver === 'undefined' || !list.value) return;
  observer?.disconnect();
  observer = new ResizeObserver(() => list.value && stackToasts(list.value));
  for (const child of Array.from(list.value.children)) observer.observe(child);
}

// In the top layer, so toasts stay visible above open dialogs.
onMounted(() => {
  const el = section.value;
  try {
    if (typeof el?.showPopover === 'function') el.showPopover();
  } catch {
    /* already open */
  }
  unsubscribe = store().subscribe((next) => (items.value = [...next]));
  relayout();
});

// A dialog opened after the toaster would cover it: step back on top when a toast arrives.
watch(
  () => items.value.length,
  () => {
    const el = section.value;
    if (typeof el?.showPopover !== 'function' || !document.querySelector('dialog:modal')) return;
    try {
      el.hidePopover();
      el.showPopover();
    } catch {
      /* not supported */
    }
  },
);

watch(items, () => relayout(), { flush: 'post' });

onBeforeUnmount(() => {
  unsubscribe?.();
  observer?.disconnect();
});

function onBlur(event: FocusEvent) {
  if (!(event.currentTarget as HTMLElement).contains(event.relatedTarget as Node)) store().resume();
}
</script>

<template>
  <section
    ref="section"
    class="nx-toaster"
    :data-position="position"
    :data-effect="effect"
    :aria-label="t('notifications')"
    popover="manual"
    @pointerenter="store().pause()"
    @pointerleave="store().resume()"
    @focusin="store().pause()"
    @focusout="onBlur"
  >
    <ol ref="list" class="nx-toast-list" aria-live="polite">
      <NxToastItem v-for="item in items" :key="item.id" :toast="item" :store="store()" />
    </ol>
  </section>
</template>
