<script setup lang="ts">
/**
 * NxSparkline — the Vue counterpart of ui-react's `Sparkline`: a small inline
 * trend line (with an area wash and a final dot) revealed on scroll.
 */
import { computed } from 'vue';
import { areaPath, linePath, linearScale, reveal as revealBehavior } from '@nabuxai/ui-core';
import { useNxBehavior } from './composable';

const props = withDefaults(
  defineProps<{
    data: number[];
    /** Colours the line: rising (good) or falling (bad). */
    trend?: 'up' | 'down';
    area?: boolean;
  }>(),
  { area: true },
);

const seat = useNxBehavior<SVGSVGElement, { once: boolean }>(revealBehavior, { once: true });

const width = 120;
const height = 40;
const points = computed(() => {
  const min = Math.min(...props.data);
  const max = Math.max(...props.data);
  const x = linearScale([0, Math.max(1, props.data.length - 1)], [2, width - 4]);
  const y = linearScale([min, max === min ? min + 1 : max], [height - 3, 3]);
  return props.data.map((v, i) => [x(i), y(v)] as const);
});
const last = computed(() => points.value[points.value.length - 1]);
</script>

<template>
  <svg ref="seat" class="nx-sparkline nx-chart" data-nx-reveal="" :data-trend="trend" :viewBox="`0 0 ${width} ${height}`" preserveAspectRatio="none" aria-hidden="true">
    <path v-if="area" class="nx-chart-area" :d="areaPath(points, height, true)" />
    <path class="nx-chart-line" :d="linePath(points, true)" :pathLength="1" />
    <circle v-if="last" class="nx-chart-point" :cx="last[0]" :cy="last[1]" r="3" data-end="" />
  </svg>
</template>
