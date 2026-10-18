<script setup lang="ts">
/**
 * NxBarChart — the Vue counterpart of ui-react's `BarChart`: grouped bars,
 * gridline ticks, a pointer-following tooltip and the keyboard reading.
 */
import { computed, ref, useId, useTemplateRef } from 'vue';
import { bands, barPath, linearScale, niceTicks, reveal } from '@nabuxai/ui-core';
import { useNxLocale, useNxT } from './kit';
import { NxChartLegend, NxChartTable, NxChartTooltip, PAD, seriesColor, useChartFormat, useChartKeys, type Series } from './chart-frame';
import { useNxBehaviorOn, useNxWidth } from './utils';

const props = withDefaults(
  defineProps<{
    labels?: string[];
    series?: Series[];
    /** One series: [{ label, value }]. */
    data?: Array<{ label: string; value: number }>;
    title?: string;
    subtitle?: string;
    format?: Intl.NumberFormatOptions;
    locale?: string;
    height?: number;
  }>(),
  { height: 240 },
);

const rawId = useId();
const id = `nx-bar${rawId.replace(/[^a-zA-Z0-9-]/g, '')}`;
const figure = useTemplateRef<HTMLElement>('figure');
const plot = useTemplateRef<HTMLDivElement>('plot');
useNxBehaviorOn(figure, reveal, { once: true }, () => true);

const width = useNxWidth(plot, 560);
const active = ref<number | null>(null);

const lang = useNxLocale(plot);
const fmt = useChartFormat(() => props.locale, () => props.format, lang);
const hint = computed(() => `${props.title ? `${props.title}. ` : ''}${useNxT(plot)('chartHint')}`);

const labels = computed(() => props.labels ?? props.data?.map((d) => d.label) ?? []);
const series = computed<Series[]>(() => props.series ?? [{ name: props.title || 'Value', values: props.data?.map((d) => d.value) ?? [] }]);

const ticks = computed(() => {
  const all = series.value.flatMap((s) => s.values);
  return niceTicks(Math.min(0, ...all), Math.max(0, ...all), 4);
});

const y = computed(() => {
  const innerH = props.height - PAD.top - PAD.bottom;
  return linearScale([ticks.value[0]!, ticks.value[ticks.value.length - 1]!], [PAD.top + innerH, PAD.top]);
});

const groups = computed(() => {
  const innerW = Math.max(0, width.value - PAD.left - PAD.right);
  return bands(labels.value.length, innerW, { maxBar: 24 * series.value.length + 2 * (series.value.length - 1), fill: 0.7 });
});

const barWidth = (group: { width: number }) => (group.width - 2 * (series.value.length - 1)) / series.value.length;
const barX = (group: { x: number; width: number }, si: number) => group.x + si * (barWidth(group) + 2);

function onMove(event: PointerEvent) {
  const rect = (event.currentTarget as HTMLDivElement).getBoundingClientRect();
  const x = event.clientX - rect.left - PAD.left;
  const index = Math.floor(x / (Math.max(0, width.value - PAD.left - PAD.right) / Math.max(1, labels.value.length)));
  active.value = index >= 0 && index < labels.value.length ? index : null;
}

const onKeys = useChartKeys(() => labels.value.length, active, (next) => (active.value = next));
const setActive = (next: number | null) => (active.value = next);

const tooltip = computed(() => {
  if (active.value === null) return { x: 0, y: 0, title: '', rows: [] as Array<{ name: string; value: string; color: string }> };
  const at = active.value;
  return {
    x: PAD.left + (groups.value[at]?.center ?? 0),
    y: y.value(Math.max(...series.value.map((s) => s.values[at] ?? 0))),
    title: labels.value[at] ?? '',
    rows: series.value.map((s, i) => ({ name: s.name, value: fmt.value.value(s.values[at] ?? 0), color: seriesColor(i) })),
  };
});
</script>

<template>
  <figure ref="figure" class="nx-chart" data-type="bar" data-nx-reveal="" :data-active="active !== null ? '' : undefined" :aria-labelledby="title || $slots.title ? `${id}-title` : undefined">
    <div v-if="title || $slots.title || series.length > 1" class="nx-chart-head">
      <div>
        <p v-if="title || $slots.title" class="nx-chart-title" :id="`${id}-title`">
          <slot name="title">{{ title }}</slot>
        </p>
        <p v-if="subtitle || $slots.subtitle" class="nx-chart-subtitle">
          <slot name="subtitle">{{ subtitle }}</slot>
        </p>
      </div>
      <NxChartLegend :series="series" shape="rect" />
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
        <g v-for="tick in ticks" :key="tick">
          <line :class="tick === 0 ? 'nx-chart-baseline' : 'nx-chart-gridline'" :x1="PAD.left" :x2="width - PAD.right" :y1="y(tick)" :y2="y(tick)" />
          <text class="nx-chart-tick" :x="PAD.left - 8" :y="y(tick)" dy="0.32em" text-anchor="end">{{ fmt.tick(tick) }}</text>
        </g>
        <g v-for="(group, index) in groups" :key="labels[index]" :transform="`translate(${PAD.left},0)`">
          <path
            v-for="(s, si) in series"
            :key="s.name"
            class="nx-chart-bar"
            :d="barPath(barX(group, si), barWidth(group), y(s.values[index] ?? 0), y(0), 4)"
            :data-active="active === index ? '' : undefined"
            :style="{ '--nx-series': seriesColor(si), '--nx-i': index }"
          />
          <text class="nx-chart-tick" :x="group.center" :y="height - 8" text-anchor="middle">{{ labels[index] }}</text>
        </g>
      </svg>
      <NxChartTooltip :open="active !== null" :x="tooltip.x" :y="tooltip.y" :width="width" :title="tooltip.title" :rows="tooltip.rows" />
    </div>
    <NxChartTable :caption="title" :labels="labels" :series="series" :format="fmt.value" />
  </figure>
</template>
