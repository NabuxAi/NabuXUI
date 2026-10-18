<script setup lang="ts">
/**
 * NxStatCard — the Vue counterpart of ui-react's `StatCard`: label, ticking
 * value, signed delta and an optional sparkline of the recent trend.
 */
import { computed, useTemplateRef } from 'vue';
import NxIcon from './icon';
import { useNxLocale } from './kit';
import NxNumberTicker from './nx-number-ticker.vue';
import { INTL_LOCALES } from './utils';
import NxSparkline from './nx-sparkline.vue';

const props = defineProps<{
  label: string;
  value: number;
  /** Intl number format for the value (currency, compact notation…). */
  format?: Intl.NumberFormatOptions;
  /** Change in percent against the previous period. */
  delta?: number;
  /** When a rise is bad news (costs, churn), colour it as such. */
  invertDelta?: boolean;
  /** Recent values for the sparkline. */
  trend?: number[];
  caption?: string;
  locale?: string;
}>();

const root = useTemplateRef<HTMLElement>('root');
const lang = useNxLocale(root);
const intl = computed(() => props.locale ?? INTL_LOCALES[lang.value]);
const good = computed(() => (props.delta === undefined ? undefined : props.invertDelta ? props.delta <= 0 : props.delta >= 0));
const deltaText = computed(() =>
  new Intl.NumberFormat(intl.value, { style: 'percent', maximumFractionDigits: 1, signDisplay: 'exceptZero' }).format((props.delta ?? 0) / 100),
);
</script>

<template>
  <article ref="root" class="nx-stat">
    <div class="nx-stat-head">
      <span class="nx-stat-label">{{ label }}</span>
      <span v-if="delta !== undefined" class="nx-delta" :data-trend="good ? 'up' : 'down'">
        <NxIcon :name="delta >= 0 ? 'trend-up' : 'trend-down'" />
        {{ deltaText }}
      </span>
    </div>
    <div class="nx-stat-value">
      <NxNumberTicker :value="value" :format="format" :locale="intl" />
    </div>
    <NxSparkline v-if="trend && trend.length > 1" :data="trend" :trend="good === undefined ? undefined : good ? 'up' : 'down'" />
    <p v-if="caption" class="nx-stat-caption">{{ caption }}</p>
  </article>
</template>
