/**
 * The dashboard — the panel’s front page, mirroring the Livewire dashboard:
 * a welcome card over a decorative backdrop, the day’s numbers (rolling
 * digits), the month-by-month metric chart, the support heatmap, the activity
 * feed and the storage usage card.
 */
import { Backdrop, Button, Heatmap, MetricChart, StatStrip, TimelineFeed, UsageCard, type TimelineEntry } from '@nabuxai/ui-react';
import { useLang, useStrings, useTr } from '../lang';
import {
  AGO,
  HEAT_DAYS,
  HEAT_TOTAL,
  monthLabels,
  ORDERS_BY_MONTH,
  ORDERS_DELTA,
  REVENUE_BY_MONTH,
  REVENUE_DELTA,
  STORAGE,
  VISITS_BY_MONTH,
  VISITS_DELTA,
} from '../data';
import { href } from '../router';

const INTL = { fa: 'fa-IR', en: 'en-US' } as const;

export function DashboardPage() {
  const lang = useLang();
  const tr = useTr();
  const d = useStrings().pages.dash;
  const intl = INTL[lang];

  const entries: TimelineEntry[] = [
    {
      id: 'order-1248',
      actor: tr('مریم رضایی', 'Maryam Rezaei'),
      text: tr('سفارش را تأیید کرد', 'confirmed order'),
      target: '#1248',
      time: AGO.minutes3.at,
      tone: 'success',
    },
    {
      id: 'backup',
      icon: 'upload',
      text: tr('پشتیبان‌گیری کامل شد', 'Backup finished'),
      target: tr('۱۲ گیگابایت', '12 gigabytes'),
      time: AGO.hours2.at,
      tone: 'info',
    },
    {
      id: 'card-moved',
      actor: tr('علی نیک‌پور', 'Ali Nikpour'),
      text: tr('کارت را به «بازبینی» برد', 'moved the card to Review'),
      target: tr('صفحهٔ پرداخت', 'Checkout page'),
      time: AGO.hour1.at,
    },
    {
      id: 'stock',
      icon: 'bell',
      text: tr('موجودی کم است:', 'Low stock:'),
      target: tr('کیف چرمی نابو', 'Nabu leather bag'),
      time: AGO.hours5.at,
      tone: 'warning',
    },
    {
      id: 'invite',
      actor: tr('سارا احمدی', 'Sara Ahmadi'),
      text: tr('کاربر تازه را دعوت کرد', 'invited a new user'),
      target: 'mina@nabu.shop',
      time: AGO.day1.at,
    },
  ];

  return (
    <div className="adm-dashboard">
      <Backdrop variant="aurora" className="adm-welcome adm-wide">
        <h1>{d.welcomeHi}</h1>
        <p>{d.welcomeText}</p>
        <div className="adm-welcome-actions">
          <Button variant="primary" icon="chart" href={href('analytics')}>
            {d.welcomeCta}
          </Button>
          <Button variant="secondary" icon="users" href={href('users')}>
            {d.welcomeSecondary}
          </Button>
        </div>
      </Backdrop>

      <StatStrip
        className="adm-wide"
        aria-label={d.statsLabel}
        stats={[
          { label: d.statRevenue, value: 84_500_000, icon: 'layers', caption: d.statRevenueCaption },
          { label: d.statOrders, value: 42, icon: 'grid', caption: d.statOrdersCaption },
          { label: d.statUsers, value: 24_318, icon: 'users', caption: d.statUsersCaption },
          { label: d.statSatisfaction, value: 96, icon: 'heart', caption: d.statSatisfactionCaption },
        ]}
      />

      <MetricChart
        className="adm-wide"
        title={d.metricTitle}
        caption={d.metricCaption}
        labels={monthLabels(intl)}
        metrics={[
          { id: 'revenue', label: d.metricRevenue, values: REVENUE_BY_MONTH.map((v) => v * 1_000_000), delta: REVENUE_DELTA, format: { notation: 'compact' } },
          { id: 'orders', label: d.metricOrders, values: ORDERS_BY_MONTH, delta: ORDERS_DELTA },
          { id: 'visits', label: d.metricVisits, values: VISITS_BY_MONTH, delta: VISITS_DELTA },
        ]}
      />

      <Heatmap
        title={d.heatTitle}
        subtitle={d.heatSubtitle}
        unit={d.heatUnit}
        weeks={26}
        data={HEAT_DAYS}
        summary={`${HEAT_TOTAL.toLocaleString(intl)} ${d.heatSummary}`}
      />

      <div className="adm-side">
        <TimelineFeed label={d.feedTitle} entries={entries} />
        <UsageCard
          title={d.usageTitle}
          plan={d.usagePlan}
          limit={STORAGE.limit}
          format={{ style: 'unit', unit: 'gigabyte', maximumFractionDigits: 1 }}
          categories={[
            { label: d.usageDocs, value: STORAGE.docs },
            { label: d.usageMedia, value: STORAGE.media },
            { label: d.usageVoice, value: STORAGE.voice },
            { label: d.usageEmbeddings, value: STORAGE.embeddings },
          ]}
          note={d.usageNote}
          action={{ label: d.usageAction, href: href('settings') }}
        />
      </div>
    </div>
  );
}
