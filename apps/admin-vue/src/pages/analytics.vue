<script setup lang="ts">
/**
 * Analytics — the deep-dive the dashboard points at: three headline cards
 * (revenue, orders, visitors), each switchable across 7/30/90 days, the
 * quality chart (conversion, churn, latency — the two "lower is better" ones
 * invert their delta), the plan comparison table and, beside it, the token
 * usage card plus a conversion card. Every curve is deterministic (the
 * showcase's trigonometric wave — no randomness, stable across reloads).
 */
import { computed } from 'vue';
import { href } from '../router';
import { monthLabels, NOW } from '../data';
import { intlLocale, numberFmt, s } from '../store';
import AnalyticsCard, { type AnalyticsPeriod } from '../components/AnalyticsCard.vue';
import MetricChart, { type MetricChartMetric } from '../components/MetricChart.vue';
import ComparisonTable, { type ComparisonFeature, type ComparisonPlan } from '../components/ComparisonTable.vue';
import UsageCard from '../components/UsageCard.vue';

const DAY = 86_400_000;
const a = computed(() => s.value.pages.analytics);

/** A stable daily curve: a slow swell plus trigonometric noise (the showcase's `wave`). */
function daily(days: number, base: number, drift: number, swing: number): number[] {
  return Array.from({ length: days }, (_, i) =>
    Math.max(0, Math.round(base + drift * i + swing * Math.sin(i / 2.7) + swing * 0.35 * Math.sin(i * 12.9898) * Math.cos(i * 4.1414))),
  );
}

/* Headline series — revenue in tomans, orders and visitors as counts. */
const REVENUE = { week: daily(7, 24e6, 1.2e6, 5e6), month: daily(30, 25e6, 0.25e6, 6e6), quarter: daily(90, 22e6, 0.11e6, 6e6) };
const ORDERS = { week: daily(7, 42, 2.2, 11), month: daily(30, 38, 0.5, 13), quarter: daily(90, 32, 0.28, 14) };
const VISITS = { week: daily(7, 880, 45, 320), month: daily(30, 830, 12, 380), quarter: daily(90, 760, 5.5, 420) };

/* Conversion as points of a percent (the chart's ticks stay readable). */
const CONVERSION = { week: daily(7, 26, 0.08, 5), month: daily(30, 24, 0.03, 6), quarter: daily(90, 21, 0.17, 7) };

/* The quality chart's twelve months. */
const CONVERSION_BY_MONTH = [2.1, 2.3, 2.2, 2.5, 2.4, 2.7, 2.6, 2.8, 3.1, 2.9, 3.2, 3.4];
const CHURN_BY_MONTH = [3.1, 2.9, 3.3, 2.8, 2.7, 2.9, 2.6, 2.7, 2.4, 2.5, 2.3, 2.2];
const LATENCY_BY_MONTH = [238, 231, 236, 224, 219, 222, 214, 209, 212, 204, 198, 193];

const sum = (values: number[]) => values.reduce((total, value) => total + value, 0);

/** Day-of-month names for a card's bars, oldest first, hanging off the shared NOW. */
const dayLabels = (days: number) =>
  Array.from({ length: days }, (_, i) => new Intl.DateTimeFormat(intlLocale.value, { day: 'numeric' }).format(new Date(NOW - (days - 1 - i) * DAY)));

const period = (id: string, label: string, values: number[], value: number, delta: number): AnalyticsPeriod => ({
  id,
  label,
  values,
  value,
  delta,
  labels: dayLabels(values.length),
});

const revenuePeriods = computed<AnalyticsPeriod[]>(() => [
  period('week', a.value.week, REVENUE.week, sum(REVENUE.week), 12.4),
  period('month', a.value.month, REVENUE.month, sum(REVENUE.month), 12.4),
  period('quarter', a.value.quarter, REVENUE.quarter, sum(REVENUE.quarter), 21.6),
]);
const orderPeriods = computed<AnalyticsPeriod[]>(() => [
  period('week', a.value.week, ORDERS.week, sum(ORDERS.week), 9.1),
  period('month', a.value.month, ORDERS.month, sum(ORDERS.month), 9.1),
  period('quarter', a.value.quarter, ORDERS.quarter, sum(ORDERS.quarter), 14.8),
]);
const visitPeriods = computed<AnalyticsPeriod[]>(() => [
  period('week', a.value.week, VISITS.week, sum(VISITS.week), 10.2),
  period('month', a.value.month, VISITS.month, sum(VISITS.month), 10.2),
  period('quarter', a.value.quarter, VISITS.quarter, sum(VISITS.quarter), 18.4),
]);
const conversionPeriods = computed<AnalyticsPeriod[]>(() => [
  period('week', a.value.week, CONVERSION.week.map((v) => v / 1000), CONVERSION.week[CONVERSION.week.length - 1]! / 1000, 0.6),
  period('month', a.value.month, CONVERSION.month.map((v) => v / 1000), CONVERSION.month[CONVERSION.month.length - 1]! / 1000, 0.4),
  period('quarter', a.value.quarter, CONVERSION.quarter.map((v) => v / 1000), CONVERSION.quarter[CONVERSION.quarter.length - 1]! / 1000, 1.1),
]);

const percent = computed(() => a.value.percentSign);

const quality = computed<MetricChartMetric[]>(() => [
  { id: 'conversion', label: `${a.value.conversion} (${percent.value})`, values: CONVERSION_BY_MONTH, delta: 0.6 },
  { id: 'churn', label: `${a.value.churn} (${percent.value})`, values: CHURN_BY_MONTH, delta: -0.5, invertDelta: true },
  { id: 'latency', label: a.value.latency, values: LATENCY_BY_MONTH, delta: -7.4, invertDelta: true, format: { style: 'unit', unit: 'millisecond', maximumFractionDigits: 0 } },
]);

const plans = computed<ComparisonPlan[]>(() => [
  { id: 'basic', name: a.value.planBasic, price: numberFmt.value.format(0), period: a.value.monthly, description: a.value.planBasicDesc, action: { label: a.value.cta, href: href('settings') } },
  { id: 'growth', name: a.value.planGrowth, price: numberFmt.value.format(990), period: a.value.monthly, description: a.value.planGrowthDesc, action: { label: a.value.cta, href: href('settings') } },
  { id: 'pro', name: a.value.planPro, price: numberFmt.value.format(2900), period: a.value.monthly, description: a.value.planProDesc, action: { label: a.value.cta, href: href('settings') } },
]);

const features = computed<ComparisonFeature[]>(() => [
  { group: a.value.groupCapacity, label: a.value.featTeam, values: { basic: numberFmt.value.format(3), growth: numberFmt.value.format(10), pro: a.value.featUnlimited } },
  { group: a.value.groupCapacity, label: a.value.featStorage, values: { basic: '5 GB', growth: '50 GB', pro: '500 GB' } },
  { group: a.value.groupCapacity, label: a.value.featGiftCards, values: { basic: false, growth: true, pro: true } },
  { group: a.value.groupInsights, label: a.value.featReports, values: { basic: false, growth: true, pro: true } },
  { group: a.value.groupInsights, label: a.value.featExport, values: { basic: false, growth: true, pro: true } },
  { group: a.value.groupInsights, label: a.value.featCompare, values: { basic: false, growth: false, pro: true } },
  { group: a.value.groupSupport, label: a.value.featEmail, values: { basic: true, growth: true, pro: true } },
  { group: a.value.groupSupport, label: a.value.featLiveChat, values: { basic: false, growth: true, pro: true } },
  { group: a.value.groupSupport, label: a.value.featSuccessManager, values: { basic: false, growth: false, pro: true } },
]);

const months = computed(() => monthLabels(intlLocale.value));
</script>

<template>
  <div class="adm-dashboard">
    <div class="adm-analytics-cards">
      <AnalyticsCard :title="a.revenue" :periods="revenuePeriods" :format="{ notation: 'compact' }" />
      <AnalyticsCard :title="a.orders" :periods="orderPeriods" />
      <AnalyticsCard :title="a.visitors" :periods="visitPeriods" />
    </div>

    <MetricChart class="adm-wide" :title="a.qualityTitle" :caption="a.vsPrev" :labels="months" :metrics="quality" />

    <ComparisonTable
      class="adm-wide"
      :caption="a.comparisonTitle"
      :feature-label="a.featureLabel"
      :recommended-label="a.recommended"
      :included-label="a.included"
      :excluded-label="a.excluded"
      recommended="growth"
      :plans="plans"
      :features="features"
    />

    <div class="adm-side">
      <UsageCard
        :title="a.usageTitle"
        :plan="a.usagePlan"
        :limit="2_000_000"
        :format="{ notation: 'compact' }"
        :categories="[
          { label: a.usageReplies, value: 940_000 },
          { label: a.usageSummaries, value: 420_000 },
          { label: a.usageTranslations, value: 260_000 },
        ]"
        :note="a.usageNote"
        :action="{ label: a.usageAction, href: href('settings') }"
      />
      <AnalyticsCard :title="a.conversion" :format="{ style: 'percent', maximumFractionDigits: 1 }" :periods="conversionPeriods" />
    </div>
  </div>
</template>

<style scoped>
@media (min-width: 72rem) {
  .adm-wide {
    grid-column: 1 / -1;
  }
}
</style>
