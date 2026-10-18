<script setup lang="ts">
/**
 * NxAreaChart — the Vue counterpart of ui-react's `AreaChart`: lines with an
 * area wash (or the lines alone), crosshair, tooltip and keyboard reading.
 */
import { computed, ref, useId, useTemplateRef } from 'vue';
import { areaPath, linearScale, linePath, nearestIndex, niceTicks, reveal } from '@nabuxai/ui-core';
import { useNxLocale, useNxT } from './kit';
import { NxChartLegend, NxChartTable, NxChartTooltip, PAD, seriesColor, useChartFormat, useChartKeys, type Series } from './chart-frame';
import { useNxBehaviorOn, useNxWidth } from './utils';
import type { Point } from '@nabuxai/ui-core';

const props = withDefaults(
  defineProps<{
    labels: string[];
    series: Series[];
    title?: string;
    subtitle?: string;
    format?: Intl.NumberFormatOptions;
    locale?: string;
    height?: number;
    /** A wash under each line (area) or the lines alone. */
    variant?: 'area' | 'line';
    smooth?: boolean;
  }>(),
  { height: 260, variant: 'area', smooth: true },
);

const id = `nx-area${useId().replace(/[^a-zA-Z0-9-]/g, '')}`;
const figure = useTemplateRef<HTMLElement>('figure');
const plot = useTemplateRef<HTMLDivElement>('plot');
useNxBehaviorOn(figure, reveal, { once: true }, () => true);

const width = useNxWidth(plot, 560);
const active = ref<number | null>(null);

const lang = useNxLocale(plot);
const fmt = useChartFormat(() => props.locale, () => props.format, lang);
const hint = computed(() => `${props.title ? `${props.title}. ` : ''}${useNxT(plot)('chartHint')}`);

const ticks = computed(() => {
  const all = props.series.flatMap((s) => s.values);
  return niceTicks(Math.min(0, ...all), Math.max(0, ...all), 4);
});

const xs = computed(() => {
  const x = linearScale([0, Math.max(1, props.labels.length - 1)], [PAD.left, width.value - PAD.right]);
  return props.labels.map((_, i) => x(i));
});

const y = computed(() => {
  const innerH = props.height - PAD.top - PAD.bottom;
  return linearScale([ticks.value[0]!, ticks.value[ticks.value.length - 1]!], [PAD.top + innerH, PAD.top]);
});

const lines = computed<Point[][]>(() => props.series.map((s) => s.values.map((v, i) => [xs.value[i]!, y.value(v)] as const) as Point[]));

function onMove(event: PointerEvent) {
  const rect = (event.currentTarget as HTMLDivElement).getBoundingClientRect();
  active.value = nearestIndex(xs.value, event.clientX - rect.left);
}

const onKeys = useChartKeys(() => props.labels.length, active, (next) => (active.value = next));
const setActive = (next: number | null) => (active.value = next);

// Label only a few ticks along x so they never collide.
const every = computed(() => Math.max(1, Math.ceil(props.labels.length / Math.max(2, Math.floor(width.value / 90)))));

const crosshairX = computed(() => (active.value === null ? 0 : (xs.value[active.value] ?? 0)));

const tooltip = computed(() => {
  if (active.value === null) return { x: 0, y: 0, title: '', rows: [] as Array<{ name: string; value: string; color: string }> };
  const at = active.value;
  return {
    x: xs.value[at] ?? 0,
    y: Math.min(...lines.value.map((points) => points[at]?.[1] ?? props.height)),
    title: props.labels[at] ?? '',
    rows: props.series.map((s, i) => ({ name: s.name, value: fmt.value.value(s.values[at] ?? 0), color: seriesColor(i) })),
  };
});
</script>

<template>
  <figure ref="figure" class="nx-chart" :data-type="variant" data-nx-reveal="" :aria-labelledby="title || $slots.title ? `${id}-title` : undefined">
    <div v-if="title || $slots.title || series.length > 1" class="nx-chart-head">
      <div>
        <p v-if="title || $slots.title" class="nx-chart-title" :id="`${id}-title`">
          <slot name="title">{{ title }}</slot>
        </p>
        <p v-if="subtitle || $slots.subtitle" class="nx-chart-subtitle">
          <slot name="subtitle">{{ subtitle }}</slot>
        </p>
      </div>
      <NxChartLegend :series="series" :shape="variant === 'area' ? 'rect' : 'line'" />
    </div>
    <div
      ref="plot"
      class="nx-chart-plot"
      tabindex="0"
      :aria-label="hint"
      @pointermove="onMove"
      @pointerleave="setActive(null)"
      @keydown="onKeys"
      @blur="setActive(null)"
    >
      <svg class="nx-chart-svg" :width="width" :height="height" :viewBox="`0 0 ${width} ${height}`" aria-hidden="true">
        <defs>
          <linearGradient v-for="(s, i) in series" :key="s.name" :id="`${id}-fill-${i}`" x1="0" x2="0" y1="0" y2="1">
            <stop offset="0%" :stop-color="seriesColor(i)" stop-opacity="0.22" />
            <stop offset="100%" :stop-color="seriesColor(i)" stop-opacity="0.01" />
          </linearGradient>
        </defs>
        <g v-for="tick in ticks" :key="tick">
          <line :class="tick === ticks[0] ? 'nx-chart-baseline' : 'nx-chart-gridline'" :x1="PAD.left" :x2="width - PAD.right" :y1="y(tick)" :y2="y(tick)" />
          <text class="nx-chart-tick" :x="PAD.left - 8" :y="y(tick)" dy="0.32em" text-anchor="end">{{ fmt.tick(tick) }}</text>
        </g>
        <template v-for="(label, i) in labels" :key="label">
          <text v-if="i % every === 0 || i === labels.length - 1" class="nx-chart-tick" :x="xs[i]" :y="height - 8" :text-anchor="i === 0 ? 'start' : i === labels.length - 1 ? 'end' : 'middle'">
            {{ label }}
          </text>
        </template>
        <template v-if="variant === 'area'">
          <path
            v-for="(points, i) in lines"
            :key="`a${i}`"
            class="nx-chart-area"
            :d="areaPath(points, y(ticks[0]!), smooth)"
            :fill="`url(#${id}-fill-${i})`"
            :style="{ '--nx-series': seriesColor(i) }"
          />
        </template>
        <path
          v-for="(points, i) in lines"
          :key="`l${i}`"
          class="nx-chart-line"
          :d="linePath(points, smooth)"
          :pathLength="1"
          :style="{ '--nx-series': seriesColor(i), '--nx-i': i }"
        />
        <line class="nx-chart-crosshair" :data-active="active !== null ? '' : undefined" :x1="crosshairX" :x2="crosshairX" :y1="PAD.top" :y2="PAD.top + height - PAD.top - PAD.bottom" />
        <template v-for="(points, i) in lines" :key="`p${i}`">
          <circle
            v-for="(point, pi) in points"
            :key="pi"
            class="nx-chart-point"
            :cx="point[0]"
            :cy="point[1]"
            r="4"
            :data-active="active === pi ? '' : undefined"
            :data-end="pi === points.length - 1 ? '' : undefined"
            :style="{ '--nx-series': seriesColor(i) }"
          />
        </template>
      </svg>
      <NxChartTooltip :open="active !== null" :x="tooltip.x" :y="tooltip.y" :width="width" :title="tooltip.title" :rows="tooltip.rows" />
    </div>
    <NxChartTable :caption="title" :labels="labels" :series="series" :format="fmt.value" />
  </figure>
</template>
