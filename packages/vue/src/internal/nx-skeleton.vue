<script setup lang="ts">
/**
 * NxSkeleton — the Vue counterpart of ui-react's `Skeleton`: the shimmering
 * placeholder of content not here yet.
 */
import { computed, type CSSProperties } from 'vue';

const props = withDefaults(
  defineProps<{
    shape?: 'text' | 'circle' | 'rect' | 'block';
    /** For text: how many lines (the last one shorter). */
    lines?: number;
    width?: string;
    height?: string;
  }>(),
  { shape: 'text', lines: 1 },
);

const itemStyle = computed(
  () =>
    ({
      inlineSize: props.width,
      ...(props.height ? { blockSize: props.height } : null),
      ...(props.shape === 'circle' && props.width ? { '--nx-skeleton-size': props.width } : null),
    }) as CSSProperties,
);

const many = computed(() => props.shape === 'text' && props.lines > 1);
const seats = computed(() => Array.from({ length: props.lines }, (_, i) => i));
</script>

<template>
  <span v-if="many" style="display: grid; gap: 0.6em">
    <span
      v-for="i in seats"
      :key="i"
      class="nx-skeleton"
      :data-shape="shape === 'block' ? undefined : shape"
      aria-hidden="true"
      :style="itemStyle"
    />
  </span>
  <span v-else class="nx-skeleton" :data-shape="shape === 'block' ? undefined : shape" aria-hidden="true" :style="itemStyle" />
</template>
