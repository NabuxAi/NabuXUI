<script lang="ts">
/**
 * NxPricingTable — the Vue counterpart of ui-react's `PricingTable`:
 * plans side by side, the billing period switched by a segmented control
 * and prices rolling between periods.
 */
import type { VueNode } from '../utils';

export type NxBilling = 'monthly' | 'yearly';

export interface NxPricingPlan {
  id: string;
  name: VueNode;
  description?: VueNode;
  /** Amount shown for each billing period (e.g. per month on both). */
  price: Record<NxBilling, number>;
  /** A currency sign before the amount ("$", "€"); omit for units after it. */
  currency?: string;
  /** Text after the amount ("/month", "تومان در ماه"). */
  period?: Partial<Record<NxBilling, VueNode>>;
  featured?: boolean;
  /** Ribbon on a featured plan ("Most popular"). */
  flag?: VueNode;
  features: Array<{ label: VueNode; included?: boolean }>;
  cta: { label: VueNode; href?: string; onClick?: () => void };
}

export interface NxPricingTableProps {
  plans: NxPricingPlan[];
  defaultBilling?: NxBilling;
  /** Labels for the monthly / yearly switch. */
  billingLabels?: Record<NxBilling, VueNode>;
  /** A note beside the yearly option ("-20%"). */
  yearlyNote?: VueNode;
  locale?: string;
}
</script>

<script setup lang="ts">
import { computed } from 'vue';
import { NxNode, useControllableModel, useNxLocale } from '../kit';
import { INTL_LOCALES } from '../utils';
import NxIcon from '../icon';
import { NxBadge } from '../feedback';
import NxNumberTicker from '../nx-number-ticker.vue';
import NxSegmentedControl from '../nav/segmented.vue';
import NxPrivButton from '../priv/button.vue';

const props = withDefaults(defineProps<NxPricingTableProps>(), { defaultBilling: 'monthly', billingLabels: () => ({ monthly: 'Monthly', yearly: 'Yearly' }) });

const billing = defineModel<NxBilling | undefined>('billing');
const { current: period, set } = useControllableModel(billing, () => props.defaultBilling);

const lang = useNxLocale();
const intl = computed(() => props.locale ?? INTL_LOCALES[lang.value]);

const options = computed(() => [
  { value: 'monthly', label: props.billingLabels.monthly },
  { value: 'yearly', label: props.billingLabels.yearly },
]);

const choose = (value: string | undefined) => {
  if (value) set(value as NxBilling);
};

// The switch's label, when both sides are plain strings (for screen readers).
const switchLabel = computed(() =>
  typeof props.billingLabels.monthly === 'string' && typeof props.billingLabels.yearly === 'string'
    ? `${props.billingLabels.monthly} / ${props.billingLabels.yearly}`
    : undefined,
);
</script>

<template>
  <section class="nx-pricing">
    <div class="nx-pricing-toggle">
      <NxSegmentedControl :value="period" :options="options" :aria-label="switchLabel" @update:value="choose" />
      <NxBadge v-if="yearlyNote" tone="success" dot>
        <NxNode :node="yearlyNote" />
      </NxBadge>
    </div>
    <div class="nx-pricing-grid">
      <article v-for="plan in plans" :key="plan.id" class="nx-plan" :data-featured="plan.featured ? '' : undefined">
        <span v-if="plan.featured && plan.flag" class="nx-plan-flag"><NxNode :node="plan.flag" /></span>
        <h3 class="nx-plan-name"><NxNode :node="plan.name" /></h3>
        <p v-if="plan.description" class="nx-plan-description"><NxNode :node="plan.description" /></p>
        <p class="nx-plan-price">
          <span v-if="plan.currency" class="nx-plan-currency">{{ plan.currency }}</span>
          <NxNumberTicker :value="plan.price[period]" :locale="intl" :reveal="false" />
          <span v-if="plan.period?.[period]" class="nx-plan-period"><NxNode :node="plan.period[period]" /></span>
        </p>
        <ul class="nx-plan-features">
          <li v-for="(feature, i) in plan.features" :key="i" :data-excluded="feature.included === false ? '' : undefined">
            <NxIcon :name="feature.included === false ? 'minus' : 'check'" />
            <span><NxNode :node="feature.label" /></span>
          </li>
        </ul>
        <div class="nx-plan-action">
          <NxPrivButton
            block
            :variant="plan.featured ? 'primary' : 'secondary'"
            size="lg"
            :href="plan.cta.href"
            :effect="plan.featured ? 'shine' : undefined"
            @click="plan.cta.onClick?.()"
          >
            <NxNode :node="plan.cta.label" />
          </NxPrivButton>
        </div>
      </article>
    </div>
  </section>
</template>
