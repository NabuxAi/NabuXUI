<script setup lang="ts">
/**
 * The plan comparison table — the hand-written Vue shape of the
 * `comparison-table` block (nx-comparison): plans as columns (the recommended
 * one lit up with its flag), features grouped under headings, boolean cells as
 * yes/no marks with visually-hidden words, and any text cell as text. Rows
 * rise staggered on reveal; the whole thing scrolls sideways on small screens.
 */
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { reveal } from '@nabuxai/ui-core';

export interface ComparisonPlan {
  id: string;
  name: string;
  price?: string;
  /** After the price ("/mo"). */
  period?: string;
  description?: string;
  action?: { label: string; href: string };
}

export interface ComparisonFeature {
  label: string;
  hint?: string;
  /** Rows sharing a group sit under its heading. */
  group?: string;
  /** Per plan id: true / false for a mark, or any text ("10 GB"). */
  values: Record<string, boolean | string>;
}

const props = withDefaults(
  defineProps<{
    plans: ComparisonPlan[];
    features: ComparisonFeature[];
    /** The plan id to light up. */
    recommended?: string;
    caption?: string;
    featureLabel?: string;
    recommendedLabel?: string;
    includedLabel?: string;
    excludedLabel?: string;
  }>(),
  {
    recommended: undefined,
    caption: undefined,
    featureLabel: undefined,
    recommendedLabel: undefined,
    includedLabel: undefined,
    excludedLabel: undefined,
  },
);

const root = ref<HTMLElement | null>(null);
let stop: (() => void) | null = null;
onMounted(() => {
  if (root.value) stop = reveal(root.value, { once: true, stagger: true });
});
onBeforeUnmount(() => stop?.());

/** Feature rows with their group heading when it changes, for the tbody loop. */
const rows = computed(() => {
  let last: string | undefined;
  return props.features.map((feature, i) => {
    const heading = feature.group && feature.group !== last ? feature.group : null;
    last = feature.group ?? last;
    return { i, feature, heading };
  });
});

const cell = (feature: ComparisonFeature, plan: ComparisonPlan) => feature.values[plan.id];
</script>

<template>
  <div ref="root" class="nx-comparison" data-nx-reveal="">
    <div class="nx-comparison-scroll" :role="caption ? 'region' : undefined" :tabindex="caption ? 0 : undefined" :aria-label="caption">
      <table class="nx-comparison-table">
        <caption v-if="caption" class="nx-visually-hidden">{{ caption }}</caption>
        <thead>
          <tr>
            <th scope="col" class="nx-comparison-corner">{{ featureLabel }}</th>
            <th
              v-for="(plan, j) in plans"
              :key="plan.id"
              scope="col"
              class="nx-comparison-plan"
              :data-featured="plan.id === recommended ? '' : undefined"
              :style="{ '--nx-j': j }"
            >
              <span class="nx-comparison-plan-inner">
                <span v-if="plan.id === recommended" class="nx-comparison-flag">{{ recommendedLabel }}</span>
                <span class="nx-comparison-plan-name">{{ plan.name }}</span>
                <span v-if="plan.price !== undefined" class="nx-comparison-price">
                  {{ plan.price }}
                  <small v-if="plan.period">{{ plan.period }}</small>
                </span>
                <span v-if="plan.description" class="nx-comparison-plan-description">{{ plan.description }}</span>
                <a
                  v-if="plan.action"
                  class="nx-button"
                  :data-variant="plan.id === recommended ? 'primary' : 'secondary'"
                  data-size="sm"
                  :href="plan.action.href"
                >
                  <span class="nx-button-label"><span class="nx-button-text">{{ plan.action.label }}</span></span>
                </a>
              </span>
            </th>
          </tr>
        </thead>
        <tbody>
          <template v-for="row in rows" :key="row.i">
            <tr v-if="row.heading" class="nx-comparison-group">
              <th scope="colgroup" :colspan="plans.length + 1">{{ row.heading }}</th>
            </tr>
            <tr :style="{ '--nx-i': row.i }">
              <th scope="row" class="nx-comparison-feature">
                {{ row.feature.label }}
                <span v-if="row.feature.hint" class="nx-comparison-feature-hint">{{ row.feature.hint }}</span>
              </th>
              <td
                v-for="(plan, j) in plans"
                :key="plan.id"
                :data-featured="plan.id === recommended ? '' : undefined"
                :style="{ '--nx-j': j }"
              >
                <template v-if="typeof cell(row.feature, plan) === 'boolean' || cell(row.feature, plan) === undefined">
                  <span class="nx-comparison-mark" :data-value="cell(row.feature, plan) ? 'yes' : 'no'" aria-hidden="true">
                    <svg viewBox="0 0 24 24">
                      <path :d="cell(row.feature, plan) ? 'M5 12.5l4.5 4.5L19 7.5' : 'M6 12h12'" />
                    </svg>
                  </span>
                  <span class="nx-visually-hidden">{{ cell(row.feature, plan) ? includedLabel : excludedLabel }}</span>
                </template>
                <span v-else class="nx-comparison-text">{{ cell(row.feature, plan) }}</span>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
  </div>
</template>
