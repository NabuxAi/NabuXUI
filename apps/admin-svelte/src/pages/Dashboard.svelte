<script lang="ts">
  /**
   * The dashboard — the front page: the welcome card, the day's numbers (the
   * rolling StatStrip), the store-health metric chart, the activity feed and
   * the storage card. Data is seeded in ../data and ../lang; digits follow the
   * panel language.
   */
  import { intlLocale, numberFmt, strings, tr } from '../store.svelte';
  import { href } from '../router.svelte';
  import { AGO, monthLabels, ORDERS_BY_MONTH, ORDERS_DELTA, REVENUE_BY_MONTH, REVENUE_DELTA, STORAGE, VISITS_BY_MONTH, VISITS_DELTA } from '../data';
  import StatStrip from '../lib/StatStrip.svelte';
  import MetricChart from '../lib/MetricChart.svelte';
  import NxIcon from '../lib/NxIcon.svelte';

  const s = $derived(strings());
  const d = $derived(s.pages.dash);

  const numbers = $derived.by(() => [
    { label: d.statRevenue, value: 84_500_000, caption: d.statRevenueCaption },
    { label: d.statOrders, value: 42, caption: d.statOrdersCaption },
    { label: d.statUsers, value: 24_318, caption: d.statUsersCaption },
    { label: d.statSatisfaction, value: 96, caption: d.statSatisfactionCaption },
  ]);

  const entries = $derived([
    { id: 'order', icon: 'check-circle' as const, text: tr('سفارش را تأیید کرد', 'confirmed order'), who: tr('مریم رضایی', 'Maryam Rezaei'), time: AGO.minutes3.at },
    { id: 'backup', icon: 'upload' as const, text: tr('پشتیبان‌گیری کامل شد', 'Backup finished'), who: tr('۱۲ گیگابایت', '12 gigabytes'), time: AGO.hours2.at },
    { id: 'card', icon: 'layers' as const, text: tr('کارت را به «بازبینی» برد', 'moved the card to Review'), who: tr('علی نیک‌پور', 'Ali Nikpour'), time: AGO.hour1.at },
    { id: 'stock', icon: 'bell' as const, text: tr('موجودی کم است:', 'Low stock:'), who: tr('کیف چرمی نابو', 'Nabu leather bag'), time: AGO.hours5.at },
    { id: 'invite', icon: 'mail' as const, text: tr('کاربر تازه را دعوت کرد', 'invited a new user'), who: 'mina@nabu.shop', time: AGO.day1.at },
  ]);

  /** "۱۸ دقیقه پیش" / "18m ago" — the locale's digits, measured from the shared NOW. */
  function agoWord(at: number): string {
    const minutes = Math.max(0, Math.round((Date.now() - at) / 60_000));
    const n = (value: number) => new Intl.NumberFormat(intlLocale()).format(value);
    if (minutes < 1) return tr('همین حالا', 'just now');
    if (minutes < 60) return tr(`${n(minutes)} دقیقه پیش`, `${n(minutes)}m ago`);
    const hours = Math.round(minutes / 60);
    if (hours < 24) return tr(`${n(hours)} ساعت پیش`, `${n(hours)}h ago`);
    const days = Math.round(hours / 24);
    return tr(`${n(days)} روز پیش`, `${n(days)}d ago`);
  }

  const usage = $derived([
    { label: d.usageDocs, value: STORAGE.docs },
    { label: d.usageMedia, value: STORAGE.media },
    { label: d.usageVoice, value: STORAGE.voice },
    { label: d.usageEmbeddings, value: STORAGE.embeddings },
  ]);

  const usedGb = $derived(usage.reduce((sum, row) => sum + row.value, 0));
  const usedPercent = $derived(Math.round((usedGb / STORAGE.limit) * 100));
  const gbFmt = $derived(new Intl.NumberFormat(intlLocale(), { style: 'unit', unit: 'gigabyte', maximumFractionDigits: 1 }));
</script>

<div class="adm-dashboard">
  <div class="adm-welcome">
    <h1>{d.welcomeHi}</h1>
    <p>{d.welcomeText}</p>
    <div class="adm-welcome-actions">
      <a class="nx-button" data-variant="primary" href={href('analytics')}>
        <span class="nx-button-label">
          <NxIcon name="chart" />
          <span class="nx-button-text">{d.welcomeCta}</span>
        </span>
      </a>
      <a class="nx-button" data-variant="secondary" href={href('users')}>
        <span class="nx-button-label">
          <NxIcon name="users" />
          <span class="nx-button-text">{d.welcomeSecondary}</span>
        </span>
      </a>
    </div>
  </div>

  <StatStrip stats={numbers} ariaLabel={d.statsLabel} />

  <MetricChart
    title={d.metricTitle}
    caption={d.metricCaption}
    labels={monthLabels(intlLocale())}
    metrics={[
      { id: 'revenue', label: d.metricRevenue, values: REVENUE_BY_MONTH.map((v) => v * 1_000_000), delta: REVENUE_DELTA, format: { notation: 'compact' } },
      { id: 'orders', label: d.metricOrders, values: ORDERS_BY_MONTH, delta: ORDERS_DELTA },
      { id: 'visits', label: d.metricVisits, values: VISITS_BY_MONTH, delta: VISITS_DELTA },
    ]}
  />

  <div class="nx-card">
    <div class="nx-card-header">
      <span class="nx-card-icon"><NxIcon name="heart" /></span>
      <h2 class="nx-card-title">{d.feedTitle}</h2>
    </div>
    <div class="nx-card-body">
      <ul class="adm-usage-list" style="gap: var(--nx-space-3)">
        {#each entries as entry (entry.id)}
          <li class="adm-usage-row" style="justify-content: flex-start">
            <NxIcon name={entry.icon} />
            <span style="color: var(--nx-text-muted)">{entry.text}</span>
            <span style="font-weight: 600">{entry.who}</span>
            <span style="margin-inline-start: auto; color: var(--nx-text-subtle); font-size: var(--nx-text-xs)">{agoWord(entry.time)}</span>
          </li>
        {/each}
      </ul>
    </div>
  </div>

  <div class="nx-card adm-usage">
    <div class="nx-card-header">
      <span class="nx-card-icon"><NxIcon name="layers" /></span>
      <h2 class="nx-card-title">{d.usageTitle}</h2>
      <p class="nx-card-description">{d.usagePlan} · {d.usageNote}</p>
    </div>
    <div class="nx-card-body adm-usage">
      <progress class="nx-progress" value={usedPercent} max="100" style:--nx-value={usedPercent} aria-label={d.usageTitle}></progress>
      <ul class="adm-usage-list">
        {#each usage as row (row.label)}
          <li class="adm-usage-row">
            <span>{row.label}</span>
            <span>{gbFmt.format(row.value)}</span>
          </li>
        {/each}
      </ul>
      <p class="adm-usage-meta">
        <span>{gbFmt.format(usedGb)} / {gbFmt.format(STORAGE.limit)}</span>
        <span>{numberFmt().format(usedPercent)}٪</span>
      </p>
    </div>
  </div>
</div>
