<script setup lang="ts">
/**
 * NxSpinner — the Vue counterpart of ui-react's `Spinner`: the circular
 * indeterminate progress.
 */
import { useTemplateRef } from 'vue';
import { useNxT } from './kit';

withDefaults(
  defineProps<{
    /** What is loading, for screen readers. */
    label?: string;
    size?: 'sm' | 'md' | 'lg';
    tone?: 'accent';
  }>(),
  {},
);

const root = useTemplateRef<HTMLProgressElement>('root');
const t = useNxT(root);
</script>

<template>
  <progress ref="root" class="nx-spinner" :aria-label="label ?? t('loading')" :data-size="size === 'md' ? undefined : size" :data-tone="tone" />
</template>
