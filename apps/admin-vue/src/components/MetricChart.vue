<script setup lang="ts">
/**
 * The metric chart — the hand-written Vue shape of the `metric-chart` block
 * (nx-metric-chart): one line series at a time behind metric tabs (the core
 * `indicator` thumb + `roveFocus`), the geometry and the line-to-line morph
 * straight from the core (`metricGeometry` + `morphPath` — the same marks the
 * React chart draws), a pointer/keyboard crosshair with the block's tooltip
 * (`nearestIndex` + `placeChartTip`), and a visually-hidden table of every
 * series for screen readers.
 */
import { computed, onBeforeUnmount, onMounted, ref, useId, watchPostEffect } from 'vue';
import {
  indicator,
  metricGeometry,
  morphPath,
  nearestIndex,
  placeChartTip,
  reveal,
  translate,
} from '@nabuxai/ui-core';
import { intlLocale, lang } from '../store';
import NxIcon from './NxIcon.vue';

export interface MetricChartMetric {
  id: string;
  label: string;
  values: number[];
  format?: Intl.NumberFormatOptions;
  /** The headline number; the last value by default. */
  value?: number;
  /** Change against the previous period, in percent. */
  delta?: number;
  /** When a rise is bad news (latency, costs). */
  invertDelta?: boolean;
}

const props = withDefaults(
  defineProps<{
    labels: string[];
    metrics: MetricChartMetric[];
    title?: string;
    /** Beside the delta ("vs last month"). */
    caption?: string;
    /** Plot height in px. */
    height?: number;
  }>(),
  { title: undefined, caption: undefined, height: 200 },
);

const uid = useId();
const root = ref<HTMLElement | null>(null);
const tabs = ref<HTMLElement | null>(null);
const plot = ref<HTMLElement | null>(null);
const tip = ref<HTMLElement | null>(null);
const line = ref<SVGPathElement | null>(null);
const area = ref<SVGPathElement | null>(null);
const currentId = ref(props.metrics[0]?.id ?? '');
const active = ref<number | null>(null);
const width = ref(560);
let ind: ReturnType<typeof indicator> | null = null;
let stopReveal: (() => void) | null = null;
let observer: ResizeObserver | null = null;

const index = computed(() => Math.max(0, props.metrics.findIndex((m) => m.id === currentId.value)));
const metric = computed(() => props.metrics[index.value]);
const series = (i: number) => `var(--nx-chart-${Math.min(Math.max(i, 0), 6) + 1})`;

const fmt = computed(() => new Intl.NumberFormat(intlLocale.value, metric.value?.format));
const tickFmt = computed(() =>
  new Intl.NumberFormat(intlLocale.value, {
    notation: 'compact',
    maximumFractionDigits: 1,
    ...(metric.value?.format?.style === 'currency' ? { style: 'currency', currency: metric.value.format.currency } : null),
  }),
);
const percentFmt = computed(() => new Intl.NumberFormat(intlLocale.value, { style: 'percent', maximumFractionDigits: 1 }));

const geo = computed(() => metricGeometry(metric.value?.values ?? [], width.value, props.height));
const headline = computed(() => {
  const values = metric.value?.values ?? [];
  return metric.value?.value ?? (values.length ? values[values.length - 1]! : 0);
});
const xs = computed(() => geo.value.points.map((p) => p[0]));
const every = computed(() => Math.max(1, Math.ceil(props.labels.length / Math.max(2, Math.floor(width.value / 84)))));

const trend = computed(() => {
  if (metric.value?.delta === undefined) return undefined;
  const up = metric.value.delta >= 0;
  return (metric.value.invertDelta ? !up : up) ? 'up' : 'down';
});

const moveThumb = () => ind?.update(tabs.value?.querySelector(`[data-value="${CSS.escape(currentId.value)}"]`) ?? null);
const pick = (id: string) => {
  currentId.value = id;
  requestAnimationFrame(moveThumb);
};

// The plot follows its box; the geometry (and the morph) follow the width.
onMounted(() => {
  if (root.value) stopReveal = reveal(root.value, { once: true });
  if (tabs.value) {
    ind = indicator(tabs.value);
    moveThumb();
  }
  if (plot.value && 'ResizeObserver' in window) {
    observer = new ResizeObserver(() => {
      const next = Math.round(plot.value?.getBoundingClientRect().width ?? 0);
      if (next > 0) width.value = next;
    });
    observer.observe(plot.value);
  }
});
onBeforeUnmount(() => {
  observer?.disconnect();
  ind?.destroy();
  stopReveal?.();
});

// Switching metrics morphs the line and its wash into the new shape — the same
// core morphPath call the React chart makes, starting from the previous shape.
let drawn: { id: string; line: string; area: string } | null = null;
watchPostEffect(() => {
  const previous = drawn;
  drawn = { id: metric.value?.id ?? '', line: geo.value.line, area: geo.value.area };
  if (!previous || previous.id === drawn.id || !line.value || !area.value) return;
  morphPath(line.value, geo.value.line, { from: previous.line });
  morphPath(area.value, geo.value.area, { from: previous.area, fallback: 'fade' });
});

// The tooltip rides the active point.
watchPostEffect(() => {
  if (active.value === null || !plot.value || !tip.value) return;
  const point = plot.value.querySelector('.nx-metric-chart-point[data-active]');
  if (point) placeChartTip(plot.value, point, tip.value);
});

const onPlotMove = (event: PointerEvent) => {
  const rect = (event.currentTarget as HTMLElement).getBoundingClientRect();
  active.value = nearestIndex(xs.value, event.clientX - rect.left);
};

/** Arrow keys along one axis (a plot's points, a tablist's tabs): the next
 *  index, null to leave, undefined when the key is not ours. `dir` flips for
 *  right-to-left rows. */
function stepIndex(event: KeyboardEvent, at: number | null, total: number, dir = 1): number | null | undefined {
  if (!total) return undefined;
  const i = at ?? -1;
  switch (event.key) {
    case 'ArrowRight':
      return Math.min(total - 1, Math.max(0, i + dir));
    case 'ArrowLeft':
      return Math.min(total - 1, Math.max(0, i === -1 ? 0 : i - dir));
    case 'Home':
      return 0;
    case 'End':
      return total - 1;
    case 'Escape':
      return null;
    default:
      return undefined;
  }
}

const onPlotKey = (event: KeyboardEvent) => {
  const step = stepIndex(event, active.value, props.labels.length);
  if (step === undefined) return;
  event.preventDefault();
  active.value = step;
};

const onTabsKey = (event: KeyboardEvent) => {
  const dir = getComputedStyle(event.currentTarget as HTMLElement).direction === 'rtl' ? -1 : 1;
  const step = stepIndex(event, index.value, props.metrics.length, dir);
  if (step === undefined || step === null) return;
  event.preventDefault();
  currentId.value = props.metrics[step]!.id;
  requestAnimationFrame(() => {
    moveThumb();
    tabs.value?.querySelector<HTMLElement>(`[data-value="${CSS.escape(currentId.value)}"]`)?.focus();
  });
};
</script>

<template>
  <section
    ref="root"
    class="nx-metric-chart"
    data-nx-reveal=""
    :aria-labelledby="title ? `${uid}-title` : undefined"
    :style="{ '--nx-series': series(index) }"
  >
    <header class="nx-metric-chart-head">
      <div v-if="title">
        <p class="nx-metric-chart-title" :id="`${uid}-title`">{{ title }}</p>
      </div>
      <div ref="tabs" class="nx-metric-chart-tabs" role="tablist" :aria-label="title" @keydown="onTabsKey">
        <span class="nx-indicator" aria-hidden="true" />
        <button
          v-for="(m, i) in metrics"
          :key="m.id"
          type="button"
          role="tab"
          :id="`${uid}-tab-${i}`"
          class="nx-metric-chart-tab"
          :data-value="m.id"
          :aria-selected="i === index"
          :aria-controls="`${uid}-panel`"
          :tabindex="i === index ? 0 : -1"
          :style="{ '--nx-series': series(i) }"
          @click="pick(m.id)"
        >
          <span class="nx-metric-chart-key" aria-hidden="true" />
          {{ m.label }}
        </button>
      </div>
    </header>

    <div class="nx-metric-chart-panel" role="tabpanel" :id="`${uid}-panel`" :aria-labelledby="`${uid}-tab-${index}`">
      <div class="nx-metric-chart-summary">
        <span class="nx-metric-chart-value">{{ fmt.format(headline) }}</span>
        <span v-if="metric?.delta !== undefined" class="nx-delta" :data-trend="trend">
          <NxIcon :name="trend === 'down' ? 'trend-down' : 'trend-up'" />
          {{ percentFmt.format(Math.abs(metric!.delta) / 100) }}
        </span>
        <span v-if="caption" class="nx-metric-chart-caption">{{ caption }}</span>
      </div>
      <div
        ref="plot"
        class="nx-metric-chart-plot"
        tabindex="0"
        :aria-label="`${metric?.label}. ${translate(lang, 'chartHint')}`"
        @pointermove="onPlotMove"
        @pointerleave="active = null"
        @keydown="onPlotKey"
        @blur="active = null"
      >
        <svg class="nx-chart-svg" :width="width" :height="height" :viewBox="`0 0 ${width} ${height}`" aria-hidden="true">
          <defs>
            <linearGradient :id="`${uid}-fill`" x1="0" x2="0" y1="0" y2="1">
              <stop class="nx-metric-chart-stop" offset="0%" stop-opacity="0.24" />
              <stop class="nx-metric-chart-stop" offset="100%" stop-opacity="0.01" />
            </linearGradient>
          </defs>
          <g :key="metric?.id" class="nx-metric-chart-ticks">
            <g v-for="(tk, i) in geo.ticks" :key="tk.value">
              <line :class="i === 0 ? 'nx-chart-baseline' : 'nx-chart-gridline'" x1="48" :x2="width - 12" :y1="tk.y" :y2="tk.y" />
              <text class="nx-chart-tick" x="40" :y="tk.y" dy="0.32em" text-anchor="end">{{ tickFmt.format(tk.value) }}</text>
            </g>
          </g>
          <template v-for="(label, i) in labels" :key="`${label}-${i}`">
            <text
              v-if="i % every === 0 || i === labels.length - 1"
              class="nx-chart-tick"
              :x="xs[i]"
              :y="height - 6"
              :text-anchor="i === 0 ? 'start' : i === labels.length - 1 ? 'end' : 'middle'"
            >
              {{ label }}
            </text>
          </template>
          <path ref="area" class="nx-metric-chart-area" :d="geo.area" :fill="`url(#${uid}-fill)`" />
          <path ref="line" class="nx-metric-chart-line" :d="geo.line" pathLength="1" />
          <line
            class="nx-chart-crosshair"
            :data-active="active !== null ? '' : undefined"
            :x1="active === null ? 0 : xs[active]"
            :x2="active === null ? 0 : xs[active]"
            y1="12"
            :y2="geo.baseline"
          />
          <circle
            v-for="(p, i) in geo.points"
            v-show="i === active || i === geo.points.length - 1"
            :key="i"
            class="nx-metric-chart-point"
            :cx="p[0]"
            :cy="p[1]"
            r="4"
            :data-active="i === active ? '' : undefined"
            :data-end="i === geo.points.length - 1 ? '' : undefined"
          />
        </svg>
        <div ref="tip" class="nx-chart-tooltip" :data-open="active !== null ? '' : undefined" aria-hidden="true">
          <p class="nx-chart-tooltip-title">{{ active !== null ? labels[active] : '' }}</p>
          <div class="nx-chart-tooltip-row">
            <span class="nx-chart-tooltip-key" :style="{ '--nx-series': series(index) }" />
            <strong>{{ active !== null ? fmt.format(metric?.values[active] ?? 0) : '' }}</strong>
            <span>{{ metric?.label }}</span>
          </div>
        </div>
      </div>
    </div>

    <table class="nx-visually-hidden">
      <caption v-if="title">{{ title }}</caption>
      <thead>
        <tr>
          <td />
          <th v-for="m in metrics" :key="m.id" scope="col">{{ m.label }}</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(label, row) in labels" :key="`${label}-${row}`">
          <th scope="row">{{ label }}</th>
          <td v-for="m in metrics" :key="m.id">{{ new Intl.NumberFormat(intlLocale, m.format).format(m.values[row] ?? 0) }}</td>
        </tr>
      </tbody>
    </table>
  </section>
</template>
