<script setup lang="ts">
/**
 * The usage card — the hand-written Vue shape of the `usage-card` block
 * (nx-usage-card): the stacked bar (one segment per category, the free share
 * grey), the rolling used figure, the legend with per-category shares, and the
 * footer note + action. Segments grow in staggered on reveal; the percent
 * badge turns warning/danger past its thresholds. The "{used} of {limit}"
 * phrase comes from the page's language layer (analytics.usedOf).
 */
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { reveal } from '@nabuxai/ui-core';
import { intlLocale, s } from '../store';
import NxIcon from './NxIcon.vue';
import RollNumber from './RollNumber.vue';

export interface UsageCategory {
  label: string;
  value: number;
}

const props = withDefaults(
  defineProps<{
    title?: string;
    plan?: string;
    limit: number;
    categories: UsageCategory[];
    format?: Intl.NumberFormatOptions;
    note?: string;
    action?: { label: string; href: string };
  }>(),
  { title: undefined, plan: undefined, format: undefined, note: undefined, action: undefined },
);

const root = ref<HTMLElement | null>(null);
let stop: (() => void) | null = null;
onMounted(() => {
  if (root.value) stop = reveal(root.value, { once: true });
});
onBeforeUnmount(() => stop?.());

const series = (i: number) => `var(--nx-chart-${Math.min(Math.max(i, 0), 6) + 1})`;

const used = computed(() => props.categories.reduce((sum, c) => sum + Math.max(0, c.value), 0));
const ratio = computed(() => (props.limit > 0 ? used.value / props.limit : 0));
const level = computed(() => (ratio.value >= 0.9 ? 'danger' : ratio.value >= 0.75 ? 'warning' : undefined));
const fmt = computed(() => new Intl.NumberFormat(intlLocale.value, props.format));
const percent = computed(() => new Intl.NumberFormat(intlLocale.value, { style: 'percent', maximumFractionDigits: 0 }));

/** "{used} از {limit} استفاده شده" → the three literal pieces around the two slots. */
const usedOfParts = computed(() => {
  const phrase = s.value.pages.analytics.usedOf;
  const head = phrase.slice(0, phrase.indexOf('{used}'));
  const middle = phrase.slice(phrase.indexOf('{used}') + 6, phrase.indexOf('{limit}'));
  const tail = phrase.slice(phrase.indexOf('{limit}') + 8);
  return { head, middle, tail };
});
</script>

<template>
  <article ref="root" class="nx-usage-card" data-nx-reveal="">
    <header v-if="title || plan" class="nx-usage-card-head">
      <p v-if="title" class="nx-usage-card-title">{{ title }}</p>
      <span v-if="plan" class="nx-badge" data-tone="accent">{{ plan }}</span>
    </header>
    <p class="nx-usage-card-count">
      <span>
        {{ usedOfParts.head }}<RollNumber :value="used" :format="format" />{{ usedOfParts.middle }}{{ fmt.format(limit) }}{{ usedOfParts.tail }}
      </span>
      <span class="nx-usage-card-percent" :data-level="level">{{ percent.format(Math.min(ratio, 9.99)) }}</span>
    </p>
    <div class="nx-usage-card-bar" aria-hidden="true">
      <span
        v-for="(c, i) in categories"
        :key="c.label"
        class="nx-usage-card-segment"
        :style="{ '--nx-share': limit > 0 ? Math.max(0, c.value) / limit : 0, '--nx-series': series(i), '--nx-i': i }"
      />
      <span v-if="ratio < 1" class="nx-usage-card-free" :style="{ '--nx-share': 1 - ratio }" />
    </div>
    <ul class="nx-usage-card-legend">
      <li v-for="(c, i) in categories" :key="c.label" class="nx-usage-card-row" :style="{ '--nx-i': i }">
        <span class="nx-usage-card-swatch" :style="{ '--nx-series': series(i) }" aria-hidden="true" />
        <span class="nx-usage-card-label">{{ c.label }}</span>
        <span class="nx-usage-card-value">{{ fmt.format(c.value) }}</span>
        <span class="nx-usage-card-share">{{ percent.format(limit > 0 ? c.value / limit : 0) }}</span>
      </li>
    </ul>
    <footer v-if="note || action" class="nx-usage-card-foot">
      <p v-if="note" class="nx-usage-card-note" :data-level="level">{{ note }}</p>
      <a v-if="action" class="nx-button" :data-variant="level ? 'primary' : 'secondary'" data-size="sm" :href="action.href">
        <span class="nx-button-label">
          <NxIcon name="zap" />
          <span class="nx-button-text">{{ action.label }}</span>
        </span>
      </a>
    </footer>
  </article>
</template>
