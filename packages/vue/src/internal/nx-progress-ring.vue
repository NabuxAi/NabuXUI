<script setup lang="ts">
/**
 * NxProgressRing — the Vue counterpart of ui-react's `ProgressRing`: a
 * circular progress element with a centred label.
 */
import { computed, type CSSProperties } from 'vue';
import { useNxBehavior } from './composable';
import { reveal as revealBehavior } from '@nabuxai/ui-core';

const props = defineProps<{
  /** 0–100. */
  value: number;
  label?: string;
  size?: string;
  thickness?: string;
  tone?: 'accent' | 'success' | 'warning' | 'danger';
}>();

const seat = useNxBehavior<HTMLElement, { once: boolean }>(revealBehavior, { once: true });

const clamped = computed(() => Math.min(100, Math.max(0, props.value)));
const style = computed(
  () =>
    ({
      ...(props.size ? { '--nx-ring-size': props.size } : null),
      ...(props.thickness ? { '--nx-ring-thickness': props.thickness } : null),
    }) as CSSProperties,
);
</script>

<template>
  <div ref="seat" class="nx-ring" data-nx-reveal="" :style="style">
    <progress class="nx-ring-progress" :value="clamped" max="100" :aria-label="label" :data-tone="tone === 'accent' ? undefined : tone" :style="{ '--nx-value': clamped }" />
    <span class="nx-ring-label" aria-hidden="true">
      <slot>{{ `${Math.round(clamped)}%` }}</slot>
    </span>
  </div>
</template>
