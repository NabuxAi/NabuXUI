<script setup lang="ts">
/**
 * NxGrid — the Vue counterpart of ui-react's `Grid`: a responsive card grid
 * whose children rise in one after another when it scrolls into view.
 */
import { computed, useTemplateRef, type CSSProperties } from 'vue';
import { reveal } from '@nabuxai/ui-core';
import { useNxBehaviorOn } from './utils';

const props = withDefaults(
  defineProps<{
    /** Narrowest a column may get before the grid wraps. */
    min?: string;
    gap?: string;
    /** Children rise in one after another when the grid scrolls into view. */
    stagger?: boolean;
  }>(),
  { stagger: true },
);

const grid = useTemplateRef<HTMLDivElement>('grid');
useNxBehaviorOn(grid, reveal, { stagger: true, once: true }, () => props.stagger);

const style = computed(
  () =>
    ({
      ...(props.min ? { '--nx-grid-min': props.min } : null),
      ...(props.gap ? { '--nx-grid-gap': props.gap } : null),
    }) as CSSProperties,
);
</script>

<template>
  <div ref="grid" class="nx-grid" :data-nx-reveal="stagger ? 'group' : undefined" :style="style">
    <slot />
  </div>
</template>
