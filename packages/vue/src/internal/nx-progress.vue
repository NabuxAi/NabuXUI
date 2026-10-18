<script setup lang="ts">
/**
 * NxProgress — the Vue counterpart of ui-react's `Progress`: the native bar,
 * indeterminate when no value is given.
 */
import { computed, type CSSProperties } from 'vue';

const props = withDefaults(
  defineProps<{
    /** Omit for an indeterminate bar. */
    value?: number;
    max?: number;
    tone?: 'accent' | 'brand' | 'success' | 'warning' | 'danger';
    label?: string;
  }>(),
  { max: 100 },
);

const pct = computed(() => (props.value === undefined ? undefined : Math.min(100, Math.max(0, (props.value / props.max) * 100))));
const style = computed(() => ({ ...(pct.value !== undefined ? { '--nx-value': pct.value } : null) }) as CSSProperties);
</script>

<template>
  <progress class="nx-progress" :value="value" :max="max" :aria-label="label" :data-tone="tone === 'accent' ? undefined : tone" :style="style" />
</template>
