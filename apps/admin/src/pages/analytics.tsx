/**
 * Analytics — the deep-dive view the dashboard points at: three headline
 * cards (revenue, orders, visitors) each switchable across 7/30/90 days, the
 * quality chart (conversion, churn, latency — the two "lower is better" ones
 * carry invertDelta), the plan comparison table and, beside it, the token
 * usage card plus a conversion card. Every curve is deterministic (the
 * showcase's trigonometric wave — no randomness, stable across reloads) and
 * every chart ships its visually-hidden table inside its block.
 */
import { AnalyticsCard, ComparisonTable, MetricChart, UsageCard, type AnalyticsPeriod, type ComparisonFeature, type ComparisonPlan } from '@nabuxai/ui-react';
import { useLang, useStrings, useTr } from '../lang';
import { monthLabels, NOW, ORDERS_DELTA, REVENUE_DELTA, VISITS_DELTA } from '../data';
import { href } from '../router';

const INTL = { fa: 'fa-IR', en: 'en-US' } as const;
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

/* Conversion as points of a percent (the chart's ticks stay readable). */
const CONVERSION = { week: daily(7, 26, 0.08, 5), month: daily(30, 24, 0.03, 6), quarter: daily(90, 21, 0.17, 7) };

/* The quality chart's twelve months. */
const CONVERSION_BY_MONTH = [2.1, 2.3, 2.2, 2.5, 2.4, 2.7, 2.6, 2.8, 3.1, 2.9, 3.2, 3.4];
const CHURN_BY_MONTH = [3.1, 2.9, 3.3, 2.8, 2.7, 2.9, 2.6, 2.7, 2.4, 2.5, 2.3, 2.2];
const LATENCY_BY_MONTH = [238, 231, 236, 224, 219, 222, 214, 209, 212, 204, 198, 193];

export function AnalyticsPage() {
  const lang = useLang();
  const tr = useTr();
  const a = useStrings().pages.analytics;
  const intl = INTL[lang];
  const number = new Intl.NumberFormat(intl);
  const sum = (values: number[]) => values.reduce((total, value) => total + value, 0);

  /** Day-of-month names for a card's bars, oldest first, hanging off the shared NOW. */
  const dayLabels = (days: number) =>
    Array.from({ length: days }, (_, i) => new Intl.DateTimeFormat(intl, { day: 'numeric' }).format(new Date(NOW - (days - 1 - i) * DAY)));

  const period = (id: string, label: string, values: number[], value: number, delta: number): AnalyticsPeriod => ({
    id,
    label,
    values,
    value,
    delta,
    labels: dayLabels(values.length),
  });

  const revenuePeriods = [
    period('week', a.week, REVENUE.week, sum(REVENUE.week), 12.4),
    period('month', a.month, REVENUE.month, sum(REVENUE.month), REVENUE_DELTA),
    period('quarter', a.quarter, REVENUE.quarter, sum(REVENUE.quarter), 21.6),
  ];
  const orderPeriods = [
    period('week', a.week, ORDERS.week, sum(ORDERS.week), 9.1),
    period('month', a.month, ORDERS.month, sum(ORDERS.month), ORDERS_DELTA),
    period('quarter', a.quarter, ORDERS.quarter, sum(ORDERS.quarter), 14.8),
  ];
  const visitPeriods = [
    period('week', a.week, VISITS.week, sum(VISITS.week), 10.2),
    period('month', a.month, VISITS.month, sum(VISITS.month), VISITS_DELTA),
    period('quarter', a.quarter, VISITS.quarter, sum(VISITS.quarter), 18.4),
  ];
  const conversionPeriods = [
    period('week', a.week, CONVERSION.week.map((v) => v / 1000), CONVERSION.week[CONVERSION.week.length - 1]! / 1000, 0.6),
    period('month', a.month, CONVERSION.month.map((v) => v / 1000), CONVERSION.month[CONVERSION.month.length - 1]! / 1000, 0.4),
    period('quarter', a.quarter, CONVERSION.quarter.map((v) => v / 1000), CONVERSION.quarter[CONVERSION.quarter.length - 1]! / 1000, 1.1),
  ];

  const capacity = tr('ظرفیت', 'Capacity');
  const insights = tr('بینش', 'Insights');
  const supportGroup = tr('پشتیبانی', 'Support');

  const plans: ComparisonPlan[] = [
    { id: 'basic', name: tr('پایه', 'Basic'), price: number.format(0), period: a.monthly, description: tr('برای شروع', 'To get going'), action: { label: a.cta, href: href('settings') } },
    { id: 'growth', name: tr('رشد', 'Growth'), price: number.format(990), period: a.monthly, description: tr('برای تیم‌های کوچک', 'For small teams'), action: { label: a.cta, href: href('settings') } },
    { id: 'pro', name: tr('حرفه‌ای', 'Pro'), price: number.format(2900), period: a.monthly, description: tr('برای فروشگاههای بزرگ', 'For large stores'), action: { label: a.cta, href: href('settings') } },
  ];

  const features: ComparisonFeature[] = [
    { group: capacity, label: tr('اعضای تیم', 'Team members'), values: { basic: number.format(3), growth: number.format(10), pro: tr('نامحدود', 'Unlimited') } },
    { group: capacity, label: tr('فضای ذخیره‌سازی', 'Storage'), values: { basic: '5 GB', growth: '50 GB', pro: '500 GB' } },
    { group: capacity, label: tr('کارت هدیه', 'Gift cards'), values: { basic: false, growth: true, pro: true } },
    { group: insights, label: tr('گزارشهای تحلیلی', 'Analytics reports'), values: { basic: false, growth: true, pro: true } },
    { group: insights, label: tr('خروجی داده', 'Data export'), values: { basic: false, growth: true, pro: true } },
    { group: insights, label: tr('نمودارهای مقایسهٔ دوره', 'Period comparison charts'), values: { basic: false, growth: false, pro: true } },
    { group: supportGroup, label: tr('پشتیبانی ایمیلی', 'Email support'), values: { basic: true, growth: true, pro: true } },
    { group: supportGroup, label: tr('پشتیبانی زنده', 'Live chat support'), values: { basic: false, growth: true, pro: true } },
    { group: supportGroup, label: tr('مدیر موفقیت اختصاصی', 'Dedicated success manager'), values: { basic: false, growth: false, pro: true } },
  ];

  const percent = tr('٪', '%');

  return (
    <div className="adm-dashboard">
      <div className="adm-wide" style={{ display: 'grid', gap: 'var(--nx-space-5)', gridTemplateColumns: 'repeat(auto-fit, minmax(15rem, 1fr))' }}>
        <AnalyticsCard title={a.revenue} periods={revenuePeriods} format={{ notation: 'compact' }} />
        <AnalyticsCard title={a.orders} periods={orderPeriods} />
        <AnalyticsCard title={a.visitors} periods={visitPeriods} />
      </div>

      <MetricChart
        className="adm-wide"
        title={tr('کیفیت فروشگاه', 'Store quality')}
        caption={a.vsPrev}
        labels={monthLabels(intl)}
        metrics={[
          { id: 'conversion', label: `${a.conversion} (${percent})`, values: CONVERSION_BY_MONTH, delta: 0.6 },
          { id: 'churn', label: `${a.churn} (${percent})`, values: CHURN_BY_MONTH, delta: -0.5, invertDelta: true },
          { id: 'latency', label: a.latency, values: LATENCY_BY_MONTH, delta: -7.4, invertDelta: true, format: { style: 'unit', unit: 'millisecond', maximumFractionDigits: 0 } },
        ]}
      />

      <ComparisonTable
        className="adm-wide"
        caption={a.comparisonTitle}
        featureLabel={tr('ویژگی', 'Feature')}
        recommendedLabel={a.recommended}
        includedLabel={a.included}
        excludedLabel={a.excluded}
        recommended="growth"
        plans={plans}
        features={features}
      />

      <div className="adm-side">
        <UsageCard
          title={a.usageTitle}
          plan={a.usagePlan}
          limit={2_000_000}
          format={{ notation: 'compact' }}
          categories={[
            { label: a.usageReplies, value: 940_000 },
            { label: a.usageSummaries, value: 420_000 },
            { label: a.usageTranslations, value: 260_000 },
          ]}
          note={a.usageNote}
          action={{ label: a.usageAction, href: href('settings') }}
        />
        <AnalyticsCard title={a.conversion} format={{ style: 'percent', maximumFractionDigits: 1 }} periods={conversionPeriods} />
      </div>
    </div>
  );
}
