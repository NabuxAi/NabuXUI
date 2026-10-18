<script setup lang="ts">
/**
 * NxOrbitLoader — the Vue counterpart of ui-react's `OrbitLoader`: three
 * comet rings orbiting a core.
 */
import { computed, useTemplateRef, type CSSProperties } from 'vue';
import { useNxT } from './kit';
import { LOADER_SIZE, ORBIT_RINGS } from './loaders';

const props = withDefaults(defineProps<{ label?: string; size?: 'sm' | 'md' | 'lg' }>(), { size: 'md' });

const root = useTemplateRef<HTMLSpanElement>('root');
const t = useNxT(root);
const rings = ORBIT_RINGS;
const style = computed(() => ({ '--nx-loader-size': LOADER_SIZE[props.size ?? 'md'] }) as CSSProperties);
</script>

<template>
  <span ref="root" class="nx-orbit" role="status" :style="style">
    <svg viewBox="0 0 64 64" aria-hidden="true">
      <g v-for="ring in rings" :key="ring.r" class="nx-orbit-ring" :style="{ '--r': ring.r, '--c': ring.c, '--d': ring.d } as CSSProperties">
        <ellipse class="nx-orbit-path" cx="32" cy="32" rx="26" ry="10" />
        <ellipse class="nx-orbit-comet" cx="32" cy="32" rx="26" ry="10" :pathLength="100" />
      </g>
      <circle class="nx-orbit-core" cx="32" cy="32" r="4.5" />
    </svg>
    <span class="nx-visually-hidden">{{ label ?? t('loading') }}</span>
  </span>
</template>
