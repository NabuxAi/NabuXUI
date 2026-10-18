<script setup lang="ts">
/**
 * NxToastItem — one rendered toast: swipeable, an action seat, a close button
 * and the timer bar, removed from the store once it has animated out.
 */
import { onBeforeUnmount, onMounted, useTemplateRef, watch, type CSSProperties } from 'vue';
import type { Toast, ToastStore } from '@nabuxai/ui-core';
import { leave, swipe } from '@nabuxai/ui-core';
import NxIcon from './icon';
import { NxSmartLink, useNxT } from './kit';
import { TONE_ICON } from './loaders';

const props = defineProps<{ toast: Toast; store: ToastStore }>();

const item = useTemplateRef<HTMLLIElement>('item');
const t = useNxT(item);

let stopSwipe: (() => void) | undefined;
onMounted(() => {
  const el = item.value;
  if (!el) return;
  stopSwipe = swipe(el, { axis: 'x', threshold: 80, onSwipe: () => props.store.dismiss(props.toast.id) });
});
onBeforeUnmount(() => stopSwipe?.());

let alive = true;
onBeforeUnmount(() => (alive = false));
watch(
  () => props.toast.state,
  (state) => {
    if (state !== 'closing' || !item.value) return;
    leave(item.value).then(() => alive && props.store.remove(props.toast.id));
  },
);
</script>

<template>
  <li ref="item" class="nx-toast" :data-tone="toast.tone" :data-state="toast.state" :role="toast.tone === 'danger' ? 'alert' : undefined">
    <span class="nx-toast-icon">
      <NxIcon :name="TONE_ICON[toast.tone]" />
    </span>
    <div class="nx-toast-body">
      <p class="nx-toast-title">{{ toast.title }}</p>
      <p v-if="toast.description" class="nx-toast-description">{{ toast.description }}</p>
    </div>
    <NxSmartLink
      v-if="toast.action && toast.action.href"
      class="nx-button nx-toast-action"
      data-variant="secondary"
      data-size="xs"
      :href="toast.action.href"
    >
      <span class="nx-button-label">{{ toast.action.label }}</span>
    </NxSmartLink>
    <button
      v-else-if="toast.action"
      type="button"
      class="nx-button nx-toast-action"
      data-variant="secondary"
      data-size="xs"
      @click="toast.action?.onClick?.(); store.dismiss(toast.id)"
    >
      <span class="nx-button-label">{{ toast.action.label }}</span>
    </button>
    <button type="button" class="nx-toast-close" :aria-label="t('dismiss')" @click="store.dismiss(toast.id)">
      <NxIcon name="x" />
    </button>
    <span
      v-if="toast.duration > 0 && toast.state === 'open'"
      class="nx-toast-timer"
      aria-hidden="true"
      :style="{ '--nx-duration': `${toast.duration}ms` } as CSSProperties"
    />
  </li>
</template>
