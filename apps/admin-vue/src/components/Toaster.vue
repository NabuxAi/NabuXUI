<script setup lang="ts">
/**
 * The toaster — the markup React's `Toaster` renders, over the core toast
 * store: a manual popover in the top layer, the list re-laid-out with
 * `stackToasts` after every change, and the CSS animating the closing state
 * away while the store drops the toast after its grace period.
 */
import { nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { stackToasts, toasts, toast, translate, type IconName, type Toast, type ToastTone } from '@nabuxai/ui-core';
import { lang } from '../store';
import NxIcon from './NxIcon.vue';

const list = ref<HTMLOListElement | null>(null);
const section = ref<HTMLElement | null>(null);
const items = ref<readonly Toast[]>([]);

const TONE_ICON: Record<ToastTone, IconName> = {
  neutral: 'bell',
  accent: 'sparkles',
  success: 'check-circle',
  warning: 'alert-triangle',
  danger: 'alert-circle',
  info: 'info',
};

onMounted(() => {
  items.value = toasts.getSnapshot();
  const unsubscribe = toasts.subscribe((next) => (items.value = next));
  onBeforeUnmount(unsubscribe);
  try {
    section.value?.showPopover?.();
  } catch {
    /* already open */
  }
});

watch(items, () => void nextTick(() => list.value && stackToasts(list.value)), { flush: 'post' });

const dismiss = (id: string) => toasts.dismiss(id);
</script>

<template>
  <section
    ref="section"
    class="nx-toaster"
    popover="manual"
    :aria-label="translate(lang, 'notifications')"
    @pointerenter="toasts.pause()"
    @pointerleave="toasts.resume()"
    @focusin="toasts.pause()"
    @focusout="toasts.resume()"
  >
    <ol ref="list" class="nx-toast-list" aria-live="polite">
      <li
        v-for="item in items"
        :key="item.id"
        class="nx-toast"
        :data-tone="item.tone"
        :data-state="item.state"
        :role="item.tone === 'danger' ? 'alert' : undefined"
      >
        <span class="nx-toast-icon"><NxIcon :name="TONE_ICON[item.tone]" /></span>
        <div class="nx-toast-body">
          <p class="nx-toast-title">{{ item.title }}</p>
          <p v-if="item.description" class="nx-toast-description">{{ item.description }}</p>
        </div>
        <button
          v-if="item.action"
          type="button"
          class="nx-button nx-toast-action"
          data-variant="secondary"
          data-size="xs"
          @click="item.action?.onClick?.(); toast.dismiss(item.id)"
        >
          <span class="nx-button-label">{{ item.action.label }}</span>
        </button>
        <button type="button" class="nx-toast-close" :aria-label="translate(lang, 'dismiss')" @click="dismiss(item.id)">
          <NxIcon name="x" />
        </button>
        <span
          v-if="item.duration > 0 && item.state === 'open'"
          class="nx-toast-timer"
          aria-hidden="true"
          :style="{ '--nx-duration': `${item.duration}ms` }"
        />
      </li>
    </ol>
  </section>
</template>
