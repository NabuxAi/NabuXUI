<script setup lang="ts">
/**
 * The analytics KPI card — the hand-written Vue shape of the `analytics-card`
 * block (nx-analytics-card): a period segmented control (native radios under
 * the core `indicator` thumb), a rolling KPI (RollNumber), a delta pill, and
 * bars that grow per period (`--nx-v` slots that fold away beyond the current
 * period). Pointer or arrow keys walk the bars with the block's tooltip (core
 * `placeChartTip`), and a visually-hidden table carries the same data.
 */
import { computed, nextTick, onBeforeUnmount, onMounted, ref, useId, watch } from 'vue';
import { indicator, placeChartTip, reveal, translate } from '@nabuxai/ui-core';
import { intlLocale, lang } from '../store';
import NxIcon from './NxIcon.vue';
import RollNumber from './RollNumber.vue';

export interface AnalyticsPeriod {
  id: string;
  label: string;
  values: number[];
  /** The headline figure (the sum, a rate…) — not necessarily a value of `values`. */
  value: number;
  /** Change against the previous period, in percent. */
  delta?: number;
  labels?: string[];
  caption?: string;
}

const props = defineProps<{
  title: string;
  periods: AnalyticsPeriod[];
  format?: Intl.NumberFormatOptions;
  /** When a rise is bad news (latency, costs). */
  invertDelta?: boolean;
}>();

const uid = useId();
const root = ref<HTMLElement | null>(null);
const frame = ref<HTMLElement | null>(null);
const tip = ref<HTMLElement | null>(null);
const control = ref<HTMLElement | null>(null);
const current = ref(props.periods[0]?.id ?? '');
const active = ref<number | null>(null);
let ind: ReturnType<typeof indicator> | null = null;
let stopReveal: (() => void) | null = null;

const period = computed(() => props.periods.find((p) => p.id === current.value) ?? props.periods[0]);
const slots = computed(() => Math.max(0, ...props.periods.map((p) => p.values.length)));
const count = computed(() => period.value?.values.length ?? 0);
const max = computed(() => Math.max(0, ...(period.value?.values ?? [])) || 1);
const nameOf = (i: number) => period.value?.labels?.[i] ?? String(i + 1);
const fmt = computed(() => new Intl.NumberFormat(intlLocale.value, props.format));
const percentFmt = computed(() => new Intl.NumberFormat(intlLocale.value, { style: 'percent', maximumFractionDigits: 1 }));

const trend = computed(() => {
  if (period.value?.delta === undefined) return undefined;
  const up = period.value.delta >= 0;
  return (props.invertDelta ? !up : up) ? 'up' : 'down';
});

const moveThumb = () => ind?.update(control.value?.querySelector('.nx-segment:has(:checked)') ?? null);

const pick = (id: string) => {
  current.value = id;
  active.value = null;
  requestAnimationFrame(moveThumb);
};

const place = () => {
  if (active.value === null || !frame.value || !tip.value) return;
  const bar = frame.value.querySelector(`.nx-analytics-card-slot[data-index="${active.value}"] .nx-analytics-card-bar`);
  if (bar) placeChartTip(frame.value, bar, tip.value);
};

watch(active, () => void nextTick(place));

const onOver = (event: PointerEvent) => {
  const slot = (event.target as HTMLElement).closest<HTMLElement>('[data-index]');
  const at = slot ? Number(slot.dataset.index) : -1;
  active.value = at >= 0 && at < count.value ? at : null;
};

/** Arrow keys along the bars: the next index, null to leave, undefined when not handled. */
function stepIndex(event: KeyboardEvent, at: number | null, total: number): number | null | undefined {
  if (!total) return undefined;
  const i = at ?? -1;
  switch (event.key) {
    case 'ArrowRight':
      return Math.min(total - 1, i + 1);
    case 'ArrowLeft':
      return Math.min(total - 1, Math.max(0, i === -1 ? 0 : i - 1));
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

const onBarsKey = (event: KeyboardEvent) => {
  const step = stepIndex(event, active.value, count.value);
  if (step === undefined) return;
  event.preventDefault();
  active.value = step;
};

onMounted(() => {
  if (root.value) stopReveal = reveal(root.value, { once: true });
  if (control.value) {
    ind = indicator(control.value);
    moveThumb();
  }
});
onBeforeUnmount(() => {
  ind?.destroy();
  stopReveal?.();
});
</script>

<template>
  <article ref="root" class="nx-analytics-card" data-nx-reveal="">
    <header class="nx-analytics-card-head">
      <p class="nx-analytics-card-title">{{ title }}</p>
      <div ref="control" class="nx-segmented" data-size="sm" role="radiogroup" :aria-label="title">
        <span class="nx-indicator" aria-hidden="true" />
        <label v-for="p in periods" :key="p.id" class="nx-segment">
          <input class="nx-segment-input" type="radio" :name="`nx-analytics-${uid}`" :value="p.id" :checked="p.id === current" @change="pick(p.id)" />
          <span>{{ p.label }}</span>
        </label>
      </div>
    </header>

    <div class="nx-analytics-card-kpi">
      <span class="nx-analytics-card-value">
        <RollNumber :value="period?.value ?? 0" :format="format" />
      </span>
      <span v-if="period?.delta !== undefined" class="nx-delta" :data-trend="trend">
        <NxIcon :name="trend === 'down' ? 'trend-down' : 'trend-up'" />
        {{ percentFmt.format(Math.abs(period!.delta) / 100) }}
      </span>
    </div>
    <p v-if="period?.caption" class="nx-analytics-card-caption">{{ period.caption }}</p>

    <div ref="frame" class="nx-analytics-card-frame">
      <div
        class="nx-analytics-card-bars"
        tabindex="0"
        :aria-label="`${title}. ${translate(lang, 'chartHint')}`"
        @pointerover="onOver"
        @pointerleave="active = null"
        @keydown="onBarsKey"
        @blur="active = null"
      >
        <span
          v-for="i in slots"
          :key="i"
          class="nx-analytics-card-slot"
          :data-index="i - 1"
          :data-state="i - 1 < count ? 'on' : 'off'"
          :data-current="i - 1 === count - 1 ? '' : undefined"
          :data-active="i - 1 === active ? '' : undefined"
          :style="{ '--nx-i': i - 1, '--nx-v': i - 1 < count ? Math.max(0.02, (period?.values[i - 1] ?? 0) / max) : 0 }"
        >
          <span class="nx-analytics-card-bar">
            <span class="nx-analytics-card-fill" />
          </span>
        </span>
      </div>
      <div ref="tip" class="nx-chart-tooltip" :data-open="active !== null ? '' : undefined" aria-hidden="true">
        <p class="nx-chart-tooltip-title">{{ active !== null ? nameOf(active) : '' }}</p>
        <div class="nx-chart-tooltip-row">
          <strong>{{ active !== null ? fmt.format(period?.values[active] ?? 0) : '' }}</strong>
          <span>{{ title }}</span>
        </div>
      </div>
    </div>

    <div v-if="count > 1" class="nx-analytics-card-axis" aria-hidden="true">
      <span>{{ nameOf(0) }}</span>
      <span>{{ nameOf(count - 1) }}</span>
    </div>

    <table class="nx-visually-hidden">
      <caption>{{ title }} · {{ period?.label }}</caption>
      <tbody>
        <tr v-for="(v, i) in period?.values ?? []" :key="i">
          <th scope="row">{{ nameOf(i) }}</th>
          <td>{{ fmt.format(v) }}</td>
        </tr>
      </tbody>
    </table>
  </article>
</template>
