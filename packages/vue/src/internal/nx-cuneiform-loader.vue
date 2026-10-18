<script setup lang="ts">
/**
 * NxCuneiformLoader — the Vue counterpart of ui-react's `CuneiformLoader`:
 * wedges pressed into clay one after another — the Nabu scribe at work.
 */
import { computed, useTemplateRef, type CSSProperties } from 'vue';
import { useNxT } from './kit';
import { CUNEIFORM_SIZE, WEDGES } from './loaders';

const props = withDefaults(defineProps<{ label?: string; size?: 'sm' | 'md' | 'lg' }>(), { size: 'md' });

const root = useTemplateRef<HTMLSpanElement>('root');
const t = useNxT(root);
const wedges = WEDGES;
const style = computed(() => ({ '--nx-loader-size': CUNEIFORM_SIZE[props.size ?? 'md'] }) as CSSProperties);
</script>

<template>
  <span ref="root" class="nx-cuneiform" role="status" :style="style">
    <svg viewBox="0 0 64 24" aria-hidden="true">
      <g v-for="(wedge, i) in wedges" :key="i" :transform="wedge.transform">
        <path class="nx-wedge" :d="wedge.d" :style="{ '--i': i } as CSSProperties" />
      </g>
    </svg>
    <span class="nx-visually-hidden">{{ label ?? t('loading') }}</span>
  </span>
</template>
