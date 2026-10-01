<script lang="ts">
  /**
   * Analytics — the deep-dive the dashboard points at: three headline cards
   * (revenue, orders, visitors) each switchable across 7/30/90 days, the
   * quality chart (conversion, churn, latency — the two "lower is better" ones
   * invert their delta), a conversion card, and the top-pages table on the
   * shared sortable DataTable. Every curve is deterministic (the showcase's
   * trigonometric wave — no randomness, stable across reloads) and every chart
   * ships its visually-hidden table inside its block.
   */
  import { intlLocale, numberFmt, strings } from '../store.svelte';
  import { monthLabels, NOW } from '../data';
  import AnalyticsCard, { type AnalyticsPeriod } from '../lib/AnalyticsCard.svelte';
  import MetricChart from '../lib/MetricChart.svelte';
  import DataTable, { type Column } from '../lib/DataTable.svelte';

  const DAY = 86_400_000;

  /** A stable daily curve: a slow swell plus trigonometric noise (showcase's `wave`). */
  function daily(days: number, base: number, drift: number, swing: number): number[] {
    return Array.from({ length: days }, (_, i) =>
      Math.max(0, Math.round(base + drift * i + swing * Math.sin(i / 2.7) + swing * 0.35 * Math.sin(i * 12.9898) * Math.cos(i * 4.1414))),
    );
  }

  /* Headline series — revenue in tomans, orders and visitors as counts. */
  const REVENUE = { week: daily(7, 24e6, 1.2e6, 5e6), month: daily(30, 25e6, 0.25e6, 6e6), quarter: daily(90, 22e6, 0.11e6, 6e6) };
  const ORDERS = { week: daily(7, 42, 2.2, 11), month: daily(30, 38, 0.5, 13), quarter: daily(90, 32, 0.28, 14) };
  const VISITS = { week: daily(7, 880, 45, 320), month: daily(30, 830, 12, 380), quarter: daily(90, 760, 5.5, 420) };
  /* Conversion as points of a percent (the card's ticks stay readable). */
  const CONVERSION = { week: daily(7, 26, 0.08, 5), month: daily(30, 24, 0.03, 6), quarter: daily(90, 21, 0.17, 7) };

  /* The quality chart's twelve months. */
  const CONVERSION_BY_MONTH = [2.1, 2.3, 2.2, 2.5, 2.4, 2.7, 2.6, 2.8, 3.1, 2.9, 3.2, 3.4];
  const CHURN_BY_MONTH = [3.1, 2.9, 3.3, 2.8, 2.7, 2.9, 2.6, 2.7, 2.4, 2.5, 2.3, 2.2];
  const LATENCY_BY_MONTH = [238, 231, 236, 224, 219, 222, 214, 209, 212, 204, 198, 193];

  /* The top-pages table: one row per storefront page, deterministic. */
  type PageRow = { id: string; visits: number; orders: number; revenue: number };

  const s = $derived(strings());
  const a = $derived(s.pages.analytics);

  const sum = (values: number[]) => values.reduce((total, value) => total + value, 0);

  /** Day-of-month names for a card's bars, oldest first, hanging off the shared NOW. */
  const dayLabels = (days: number) =>
    Array.from({ length: days }, (_, i) => new Intl.DateTimeFormat(intlLocale(), { day: 'numeric' }).format(new Date(NOW - (days - 1 - i) * DAY)));

  const period = (id: string, label: string, values: number[], value: number, delta: number): AnalyticsPeriod => ({
    id,
    label,
    values,
    value,
    delta,
    labels: dayLabels(values.length),
  });

  const revenuePeriods = $derived([
    period('week', a.week, REVENUE.week, sum(REVENUE.week), 12.4),
    period('month', a.month, REVENUE.month, sum(REVENUE.month), 8.4),
    period('quarter', a.quarter, REVENUE.quarter, sum(REVENUE.quarter), 21.6),
  ]);
  const orderPeriods = $derived([
    period('week', a.week, ORDERS.week, sum(ORDERS.week), 9.1),
    period('month', a.month, ORDERS.month, sum(ORDERS.month), 6.1),
    period('quarter', a.quarter, ORDERS.quarter, sum(ORDERS.quarter), 14.8),
  ]);
  const visitPeriods = $derived([
    period('week', a.week, VISITS.week, sum(VISITS.week), 10.2),
    period('month', a.month, VISITS.month, sum(VISITS.month), 12.7),
    period('quarter', a.quarter, VISITS.quarter, sum(VISITS.quarter), 18.4),
  ]);
  const conversionPeriods = $derived([
    period('week', a.week, CONVERSION.week.map((v) => v / 1000), CONVERSION.week[CONVERSION.week.length - 1]! / 1000, 0.6),
    period('month', a.month, CONVERSION.month.map((v) => v / 1000), CONVERSION.month[CONVERSION.month.length - 1]! / 1000, 0.4),
    period('quarter', a.quarter, CONVERSION.quarter.map((v) => v / 1000), CONVERSION.quarter[CONVERSION.quarter.length - 1]! / 1000, 1.1),
  ]);

  const rows = $derived<PageRow[]>([
    { id: 'home', visits: 12400, orders: 318, revenue: 412_000_000 },
    { id: 'bag', visits: 8900, orders: 244, revenue: 611_000_000 },
    { id: 'scarf', visits: 6100, orders: 132, revenue: 238_000_000 },
    { id: 'watch', visits: 4300, orders: 86, revenue: 197_000_000 },
    { id: 'support', visits: 2700, orders: 41, revenue: 92_000_000 },
  ]);

  const pageName = $derived<Record<string, string>>({
    home: a.pageHome,
    bag: a.pageBag,
    scarf: a.pageScarf,
    watch: a.pageWatch,
    support: a.pageSupport,
  });
</script>

{#snippet pageCell(row: PageRow)}
  <span style="font-weight: 600">{pageName[row.id]}</span>
{/snippet}

{#snippet visitsCell(row: PageRow)}
  {numberFmt().format(row.visits)}
{/snippet}

{#snippet ordersCell(row: PageRow)}
  {numberFmt().format(row.orders)}
{/snippet}

{#snippet revenueCell(row: PageRow)}
  {new Intl.NumberFormat(intlLocale(), { notation: 'compact' }).format(row.revenue)}
{/snippet}

<div class="adm-dashboard">
  <div class="adm-analytics-cards">
    <AnalyticsCard title={a.revenue} periods={revenuePeriods} format={{ notation: 'compact' }} />
    <AnalyticsCard title={a.orders} periods={orderPeriods} />
    <AnalyticsCard title={a.visitors} periods={visitPeriods} />
  </div>

  <MetricChart
    title={a.qualityTitle}
    caption={a.vsPrev}
    labels={monthLabels(intlLocale())}
    metrics={[
      { id: 'conversion', label: `${a.conversion} (${a.percentSign})`, values: CONVERSION_BY_MONTH, delta: 0.6 },
      { id: 'churn', label: `${a.churn} (${a.percentSign})`, values: CHURN_BY_MONTH, delta: -0.5, invertDelta: true },
      { id: 'latency', label: a.latency, values: LATENCY_BY_MONTH, delta: -7.4, invertDelta: true, format: { style: 'unit', unit: 'millisecond', maximumFractionDigits: 0 } },
    ]}
  />

  <div class="nx-card">
    <div class="nx-card-header">
      <h2 class="nx-card-title">{a.topTitle}</h2>
    </div>
    <div class="nx-card-body">
      <DataTable
        rows={rows}
        columns={[
          { key: 'page', label: a.colPage, sortable: true, sortValue: (row: PageRow) => pageName[row.id], cell: pageCell },
          { key: 'visits', label: a.colVisits, sortable: true, align: 'end', cell: visitsCell },
          { key: 'orders', label: a.colOrders, sortable: true, align: 'end', cell: ordersCell },
          { key: 'revenue', label: a.colRevenue, sortable: true, align: 'end', cell: revenueCell },
        ]}
        caption={a.topTitle}
        emptyText={a.noMatch}
        defaultSort={{ key: 'visits', direction: 'descending' }}
        rowKey={(row: PageRow) => row.id}
      />
    </div>
  </div>

  <AnalyticsCard title={a.conversion} format={{ style: 'percent', maximumFractionDigits: 1 }} periods={conversionPeriods} />
</div>

<style>
  /* The three headline cards sit side by side like the React panel's .adm-wide grid. */
  .adm-analytics-cards {
    display: grid;
    gap: var(--nx-space-5);
    grid-template-columns: repeat(auto-fit, minmax(15rem, 1fr));
  }
</style>
