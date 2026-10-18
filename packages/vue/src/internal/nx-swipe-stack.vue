<script setup lang="ts" generic="T">
/**
 * NxSwipeStack — the Vue counterpart of ui-react's `SwipeStack`: a deck the
 * pointer (or the buttons, or the keyboard via those buttons) swipes through.
 * The React `onSwipe` callback is the `@swipe` event here.
 */
import { computed, onBeforeUnmount, onMounted, ref, useTemplateRef, watch } from 'vue';
import { leave, swipe } from '@nabuxai/ui-core';
import { NxNode, useNxT } from './kit';
import NxSeatButton from './nx-seat-button.vue';
import type { SwipeVerdict, VueNode } from './utils';

const props = withDefaults(
  defineProps<{
    items: T[];
    renderItem: (item: T) => VueNode;
    getKey?: (item: T, index: number) => string | number;
    /** Swiped cards go to the back of the deck instead of leaving. */
    loop?: boolean;
    /** Show accept / reject buttons (the keyboard path). */
    actions?: boolean;
    acceptLabel?: string;
    rejectLabel?: string;
  }>(),
  { loop: true, actions: true },
);

const emit = defineEmits<{ swipe: [item: T, verdict: SwipeVerdict] }>();

const root = useTemplateRef<HTMLDivElement>('root');
const t = useNxT(root);
const order = ref<number[]>(props.items.map((_, i) => i));
const leaving = ref<number | null>(null);
const topEl = ref<HTMLElement | null>(null);
let alive = true;

watch(
  () => props.items,
  (items) => (order.value = items.map((_, i) => i)),
);

function decide(verdict: SwipeVerdict) {
  const top = order.value[0];
  if (top === undefined || leaving.value !== null || !topEl.value) return;
  const el = topEl.value;
  el.style.setProperty('--nx-exit-x', verdict === 'accept' ? '1' : '-1');
  leaving.value = top;
  emit('swipe', props.items[top]!, verdict);
  leave(el, { value: 'leaving', timeout: 700 }).then(() => {
    if (!alive) return;
    el.style.removeProperty('--nx-dx');
    el.style.removeProperty('--nx-dy');
    el.style.removeProperty('--nx-drag-rotate');
    el.style.removeProperty('--nx-drag-progress');
    leaving.value = null;
    order.value = props.loop ? [...order.value.slice(1), order.value[0]!] : order.value.slice(1);
  });
}

// The swipe gesture rides whichever card is on top; re-attach when it changes.
let stopSwipe: (() => void) | undefined;
const attachSwipe = () => {
  stopSwipe?.();
  stopSwipe = undefined;
  const el = topEl.value;
  if (!el) return;
  stopSwipe = swipe(el, { axis: 'x', threshold: 110, rotate: 9, onSwipe: (direction) => decide(direction === 'right' ? 'accept' : 'reject') });
};
onMounted(attachSwipe);
watch([order, topEl], attachSwipe, { flush: 'post' });
onBeforeUnmount(() => {
  alive = false;
  stopSwipe?.();
});

const setTop = (el: unknown) => (topEl.value = (el as HTMLElement | null) ?? null);
const visible = computed(() => order.value.slice(0, 4));
</script>

<template>
  <div ref="root">
    <div class="nx-swipe-stack">
      <template v-for="(itemIndex, depth) in visible" :key="getKey ? getKey(items[itemIndex]!, itemIndex) : itemIndex">
        <article
          :ref="depth === 0 ? setTop : undefined"
          class="nx-swipe-card"
          :data-state="leaving === itemIndex ? 'leaving' : depth === 0 ? 'top' : undefined"
          :aria-hidden="depth === 0 ? undefined : true"
          :style="{ '--i': depth }"
        >
          <NxNode :node="renderItem(items[itemIndex]!)" />
          <template v-if="depth === 0">
            <span class="nx-swipe-hint" data-side="accept" aria-hidden="true">{{ acceptLabel ?? t('accept') }}</span>
            <span class="nx-swipe-hint" data-side="reject" aria-hidden="true">{{ rejectLabel ?? t('reject') }}</span>
          </template>
        </article>
      </template>
    </div>
    <div v-if="actions && order.length > 0" class="nx-swipe-actions">
      <NxSeatButton variant="secondary" size="lg" icon="x" :aria-label="rejectLabel ?? t('reject')" @click="decide('reject')" />
      <NxSeatButton variant="primary" size="lg" icon="heart" :aria-label="acceptLabel ?? t('accept')" @click="decide('accept')" />
    </div>
  </div>
</template>
