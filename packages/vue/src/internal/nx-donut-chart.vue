<script setup lang="ts">
/**
 * NxDonutChart — the Vue counterpart of ui-react's `DonutChart`: ring segments
 * with a centre readout and a legend that highlights in step with the pointer.
 */
import { computed, ref, useTemplateRef, type CSSProperties } from 'vue';
import { donutSegments, reveal } from '@nabuxai/ui-core';
import { useNxLocale } from './kit';
import { NxChartTable, seriesColor, useChartFormat, type Series } from './chart-frame';
import { useNxBehaviorOn } from './utils';

const props = withDefaults(
  defineProps<{
    data: Array<{ label: string; value: number }>;
    title?: string;
    /** Shown in the centre; the total by default. */
    centerLabel?: string;
    format?: Intl.NumberFormatOptions;
    locale?: string;
    size?: string;
    thickness?: number;
  }>(),
  { thickness: 14 },
);

const figure = useTemplateRef<HTMLElement>('figure');
useNxBehaviorOn(figure, reveal, { once: true }, () => true);

const active = ref<number | null>(null);
const fmt = useChartFormat(() => props.locale, () => props.format, useNxLocale(figure));

const segments = computed(() => donutSegments(props.data.map((d) => d.value)));
const total = computed(() => props.data.reduce((sum, d) => sum + d.value, 0));
const radius = 50 - props.thickness / 2 - 4;

const shown = computed(() =>
  active.value === null
    ? { value: fmt.value.value(total.value), label: props.centerLabel ?? props.title }
    : { value: fmt.value.value(props.data[active.value]!.value), label: props.data[active.value]!.label },
);

const tableSeries = computed<Series[]>(() => props.data.map((d) => ({ name: d.label, values: [d.value] })));

const style = computed(
  () =>
    ({
      ...(props.size ? { '--nx-donut-size': props.size } : null),
      '--nx-donut-thickness': props.thickness,
    }) as CSSProperties,
);
</script>

<template>
  <figure ref="figure" class="nx-chart" data-type="donut" data-nx-reveal="">
    <div class="nx-donut" :style="style">
      <svg class="nx-chart-svg" viewBox="0 0 100 100" aria-hidden="true" @pointerleave="active = null">
        <circle
          v-for="(segment, i) in segments"
          :key="data[i]!.label"
          class="nx-donut-segment"
          cx="50"
          cy="50"
          :r="radius"
          :pathLength="100"
          :data-active="active === i ? '' : undefined"
          @pointerenter="active = i"
          :style="{ '--nx-series': seriesColor(i), '--nx-len': segment.length, '--nx-offset': segment.offset, '--nx-i': i }"
        />
      </svg>
      <div class="nx-donut-center" aria-hidden="true">
        <span class="nx-donut-value">{{ shown.value }}</span>
        <span v-if="shown.label" class="nx-donut-label">{{ shown.label }}</span>
      </div>
    </div>
    <ul class="nx-chart-legend" style="justify-content: center">
      <li v-for="(d, i) in data" :key="d.label" class="nx-legend-item" @pointerenter="active = i" @pointerleave="active = null">
        <span class="nx-legend-swatch" :style="{ '--nx-series': seriesColor(i) }" />
        {{ d.label }} · {{ fmt.percent(segments[i]!.share) }}
      </li>
    </ul>
    <NxChartTable :caption="title" :labels="['']" :series="tableSeries" :format="fmt.value" />
  </figure>
</template>
