<script setup lang="ts">
/**
 * NxBento — the Vue counterpart of ui-react's `Bento`: the bento grid itself;
 * each tile spans columns and rows (see BentoItem).
 */
import { computed, useTemplateRef, type CSSProperties } from 'vue';
import { reveal } from '@nabuxai/ui-core';
import { useNxBehaviorOn } from './utils';

const props = withDefaults(
  defineProps<{
    columns?: number;
    /** Minimum row height. */
    rowHeight?: string;
  }>(),
  { columns: 3 },
);

const bento = useTemplateRef<HTMLDivElement>('bento');
useNxBehaviorOn(bento, reveal, { stagger: true, once: true }, () => true);

const style = computed(() => ({ '--nx-cols': props.columns, ...(props.rowHeight ? { '--nx-row': props.rowHeight } : null) }) as CSSProperties);
</script>

<template>
  <div ref="bento" class="nx-bento" data-nx-reveal="group" :style="style">
    <slot />
  </div>
</template>
