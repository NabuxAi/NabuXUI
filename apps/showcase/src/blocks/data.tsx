/** Showcase demos: Dashboards & data. */
import { useEffect, useRef, useState } from 'react';
import {
  AnalyticsCard,
  Avatar,
  AvatarGroup,
  Badge,
  BranchConnector,
  Button,
  ComparisonTable,
  CurrencyConverter,
  CurvedTimeline,
  DataTable,
  DotMatrixChart,
  Heatmap,
  type JobStatus,
  MetricChart,
  MultiSelect,
  StatusBadge,
  SupportAgentCard,
  UsageCard,
  WorkspaceShell,
  toast,
} from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useTr } from '../lang';

/** Deterministic "random" numbers: the same page on every visit. */
const wave = (i: number, base: number, swing: number, noise = 0.35) =>
  Math.max(0, Math.round(base + swing * Math.sin(i / 2.7) + swing * noise * Math.sin(i * 12.9898) * Math.cos(i * 4.1414)));

const HEAT = Array.from({ length: 26 * 7 }, (_, d) => {
  const day = new Date(Date.UTC(2026, 8, 26) - d * 86_400_000);
  const weekend = day.getUTCDay() === 0 || day.getUTCDay() === 6;
  return { date: day.toISOString().slice(0, 10), value: d % 11 === 3 ? 0 : wave(d, weekend ? 2 : 7, weekend ? 2 : 6, 0.9) };
});

const MONTHS = ['Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'];

const METRICS = [
  { id: 'revenue', label: 'Revenue', values: MONTHS.map((_, i) => 32000 + i * 1850 + wave(i, 0, 2600)), format: { style: 'currency', currency: 'USD', maximumFractionDigits: 0 } as const, delta: 12.4 },
  { id: 'users', label: 'Active users', values: MONTHS.map((_, i) => 8200 + i * 410 + wave(i + 3, 0, 900)), delta: 4.1 },
  { id: 'latency', label: 'Latency', values: MONTHS.map((_, i) => 240 - i * 6 + wave(i + 7, 0, 22)), format: { style: 'unit', unit: 'millisecond', maximumFractionDigits: 0 } as const, delta: -8.2, invertDelta: true },
];

const PROJECTS = [
  { id: 1, name: 'Atlas Migration', owner: 'Amara Okafor', status: 'running', region: 'Lagos', budget: 48200, progress: 0.62, updated: '2026-09-24' },
  { id: 2, name: 'Proyecto Faro', owner: 'Lucía Fernández', status: 'success', region: 'Madrid', budget: 31750, progress: 1, updated: '2026-09-18' },
  { id: 3, name: 'Projet Lumière', owner: 'Élodie Moreau', status: 'queued', region: 'Lyon', budget: 12900, progress: 0.08, updated: '2026-09-25' },
  { id: 4, name: 'Projekt Nordlicht', owner: 'Jonas Becker', status: 'failed', region: 'Hamburg', budget: 22400, progress: 0.41, updated: '2026-09-21' },
  { id: 5, name: '東京ローンチ', owner: 'Kenji Watanabe', status: 'running', region: 'Tokyo', budget: 67300, progress: 0.77, updated: '2026-09-26' },
  { id: 6, name: '长城 API', owner: 'Li Wei', status: 'success', region: 'Shenzhen', budget: 54100, progress: 1, updated: '2026-09-12' },
  { id: 7, name: 'مشروع الواحة', owner: 'Layla Haddad', status: 'canceled', region: 'Dubai', budget: 8800, progress: 0.15, updated: '2026-08-30' },
  { id: 8, name: 'پروژهٔ سیمرغ', owner: 'Niloufar Ahmadi', status: 'running', region: 'Tehran', budget: 19600, progress: 0.54, updated: '2026-09-23' },
  { id: 9, name: 'परियोजना गंगा', owner: 'Priya Sharma', status: 'queued', region: 'Bengaluru', budget: 26450, progress: 0.03, updated: '2026-09-26' },
  { id: 10, name: 'Projeto Aurora', owner: 'João Silva', status: 'success', region: 'São Paulo', budget: 41000, progress: 1, updated: '2026-09-02' },
  { id: 11, name: '프로젝트 한강', owner: 'Min-jun Park', status: 'running', region: 'Seoul', budget: 37250, progress: 0.33, updated: '2026-09-22' },
  { id: 12, name: 'Proje Boğaziçi', owner: 'Elif Yılmaz', status: 'failed', region: 'İstanbul', budget: 15300, progress: 0.27, updated: '2026-09-19' },
];

const LANGUAGES = [
  { value: 'en', label: 'English', description: 'English' },
  { value: 'es', label: 'Español', description: 'Spanish' },
  { value: 'fr', label: 'Français', description: 'French' },
  { value: 'de', label: 'Deutsch', description: 'German' },
  { value: 'ja', label: '日本語', description: 'Japanese' },
  { value: 'zh', label: '中文', description: 'Chinese' },
  { value: 'ar', label: 'العربية', description: 'Arabic' },
  { value: 'fa', label: 'فارسی', description: 'Persian' },
  { value: 'hi', label: 'हिन्दी', description: 'Hindi' },
  { value: 'pt', label: 'Português', description: 'Portuguese' },
  { value: 'ko', label: '한국어', description: 'Korean' },
  { value: 'tr', label: 'Türkçe', description: 'Turkish' },
];

const NETWORKS = [
  { value: 'ethereum', label: 'Ethereum', icon: 'layers', description: 'Layer 1 · ~12s blocks' },
  { value: 'base', label: 'Base', icon: 'zap', description: 'Layer 2 · low fees' },
  { value: 'arbitrum', label: 'Arbitrum', icon: 'shield', description: 'Optimistic rollup' },
  { value: 'solana', label: 'Solana', icon: 'sparkles', description: 'High throughput' },
  { value: 'polygon', label: 'Polygon', icon: 'grid', description: 'Sidechain · PoS' },
  { value: 'bitcoin', label: 'Bitcoin', icon: 'lock', description: 'Not supported yet', disabled: true },
] as const;

const RATES = { USD: 1, EUR: 0.92, JPY: 149.8, INR: 83.2, BRL: 5.02, KRW: 1335, TRY: 32.4, AED: 3.6725, GBP: 0.79, CNY: 7.24 };

const DAYS = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
const PERIODS = [
  { id: '7d', label: '7d', value: 18420, delta: 12.5, caption: 'vs previous 7 days', labels: DAYS, values: DAYS.map((_, i) => wave(i, 2500, 700)) },
  { id: '30d', label: '30d', value: 76100, delta: 4.2, caption: 'vs previous 30 days', labels: Array.from({ length: 30 }, (_, i) => `Day ${i + 1}`), values: Array.from({ length: 30 }, (_, i) => wave(i, 2500, 900)) },
  { id: '90d', label: '90d', value: 214300, delta: -2.1, caption: 'vs previous 90 days', labels: Array.from({ length: 13 }, (_, i) => `Week ${i + 1}`), values: Array.from({ length: 13 }, (_, i) => wave(i + 4, 16000, 3500)) },
];

const INBOX = [
  ['Olá! O pedido #4821 chegou?', 'Beatriz · WhatsApp'],
  ['How do I rotate my API key?', 'Sam · Web'],
  ['¿Tienen factura en euros?', 'Carmen · Telegram'],
  ['返品の手続きを教えてください', 'Haruto · LINE'],
  ['هل يمكنني تغيير خطتي؟', 'Omar · WhatsApp'],
  ['Rechnung für September?', 'Lena · E-mail'],
  ['मेरा रिफंड कब आएगा?', 'Aarav · Web'],
];

/** A tiny pipeline: each job walks queued → running → its outcome. */
function usePipeline() {
  const [jobs, setJobs] = useState<Array<{ name: string; status: JobStatus }>>([
    { name: 'Build', status: 'success' },
    { name: 'Test · 日本語', status: 'success' },
    { name: 'Deploy · São Paulo', status: 'failed' },
  ]);
  const timers = useRef<Array<ReturnType<typeof setTimeout>>>([]);
  useEffect(() => () => timers.current.forEach(clearTimeout), []);

  const run = (fail: boolean) => {
    timers.current.forEach(clearTimeout);
    setJobs((list) => list.map((job) => ({ ...job, status: 'queued' })));
    const steps: Array<[number, number, JobStatus]> = [
      [300, 0, 'running'],
      [1500, 0, 'success'],
      [1700, 1, 'running'],
      [2900, 1, 'success'],
      [3100, 2, 'running'],
      [4400, 2, fail ? 'failed' : 'success'],
    ];
    timers.current = steps.map(([at, index, status]) => setTimeout(() => setJobs((list) => list.map((job, i) => (i === index ? { ...job, status } : job))), at));
  };

  const cancel = () => {
    timers.current.forEach(clearTimeout);
    setJobs((list) => list.map((job) => (job.status === 'running' || job.status === 'queued' ? { ...job, status: 'canceled' } : job)));
  };

  return { jobs, run, cancel };
}

export function DataBlocks() {
  const tr = useTr();
  const [networks, setNetworks] = useState<string[]>(['ethereum', 'base']);
  const [view, setView] = useState('inbox');
  const pipeline = usePipeline();

  return (
    <Section
      id="data-blocks"
      eyebrow={tr('داده و داشبورد', 'Data & dashboards')}
      title={tr('داشبوردهایی که نفس می‌کشند', 'Dashboards that breathe')}
      description={tr(
        'جدول‌هایی که با مرتب‌سازی جابه‌جا می‌شوند، نمودارهایی که میان شاخص‌ها شکل عوض می‌کنند، کارت‌هایی که یکی‌یکی پر می‌شوند و پوسته‌ای شیشه‌ای برای اپلیکیشن. هر نمودار همان عددها را به‌صورت جدول برای صفحه‌خوان هم دارد.',
        'Tables that glide when sorted, charts that morph from one metric to the next, cards that fill in turn and an app shell with a glass sidebar. Every visual also ships its numbers as a table for screen readers.',
      )}
      code={{
        react: `<MultiSelect options={networks} value={picked} onValueChange={setPicked} max={3} placeholder="Networks" />
<Heatmap title="Conversations" unit="conversations" weeks={26} data={days} />
<DataTable caption="Projects" rows={projects} defaultSort={{ key: 'budget', direction: 'descending' }} columns={[
  { key: 'name', label: 'Project', sortable: true },
  { key: 'status', label: 'Status', format: 'status', sortable: true },
  { key: 'budget', label: 'Budget', format: 'currency:USD', sortable: true },
]} />
<StatusBadge status={job.status} live />
<MetricChart title="Workspace health" labels={months} metrics={[{ id: 'revenue', label: 'Revenue', values, delta: 12.4 }]} />
<CurrencyConverter rates={{ USD: 1, EUR: 0.92, JPY: 149.8 }} defaultValue={{ amount: 1000, from: 'USD', to: 'JPY' }} />
<WorkspaceShell brand="Nabu Desk" items={nav} header={…} promptPlaceholder="Ask Nabu…" onPromptSubmit={ask}>…</WorkspaceShell>
<SupportAgentCard name="Amara Okafor" status="online" metrics={[{ label: 'CSAT', value: 96, display: '96%' }]} trend={last12} />
<AnalyticsCard title="Visitors" periods={[{ id: '7d', label: '7d', value: 18420, delta: 12.5, values }]} />
<DotMatrixChart title="Tickets by language" labels={codes} series={[{ name: 'Solved', values }]} />
<BranchConnector source={{ label: 'Inbound message', icon: 'message' }} targets={agents} />
<CurvedTimeline items={milestones} />
<UsageCard title="Storage" limit={10} format={{ style: 'unit', unit: 'gigabyte', maximumFractionDigits: 1 }} categories={usage} action={{ label: 'Upgrade' }} />
<ComparisonTable plans={plans} features={features} recommended="growth" />`,
        blade: `<x-nx::multi-select :options="$networks" :max="3" placeholder="Networks" wire:model.live="networks" />
<x-nx::heatmap title="Conversations" unit="conversations" :weeks="26" :data="$days" />
<x-nx::data-table caption="Projects" sort="budget:descending" :rows="$projects" :columns="[
    ['key' => 'name', 'label' => 'Project', 'sortable' => true],
    ['key' => 'status', 'label' => 'Status', 'format' => 'status', 'sortable' => true],
    ['key' => 'budget', 'label' => 'Budget', 'format' => 'currency:USD', 'sortable' => true],
]" />                                   {{-- or sort-action="sortBy" to sort on the server --}}
<x-nx::status-badge :status="$job->status" live />
<x-nx::metric-chart title="Workspace health" :labels="$months" :metrics="$metrics" />
<x-nx::currency-converter :rates="$rates" :amount="1000" from="USD" to="JPY" wire:model.live="fx" />
<x-nx::workspace-shell brand="Nabu Desk" :items="$nav" wire:model.live="view">…</x-nx::workspace-shell>
<x-nx::support-agent-card name="Amara Okafor" status="online" :metrics="$stats" :trend="$last12" />
<x-nx::analytics-card title="Visitors" :periods="$periods" />
<x-nx::dot-matrix-chart title="Tickets by language" :labels="$codes" :series="$series" />
<x-nx::branch-connector :source="$source" :targets="$agents" />
<x-nx::curved-timeline :items="$milestones" />
<x-nx::usage-card title="Storage" :limit="10" unit="GB" :decimals="1" :categories="$usage" />
<x-nx::comparison-table :plans="$plans" :features="$features" recommended="growth" />`,
      }}
    >
      <div className="sc-demos">
        <Demo title={tr('انتخاب چندگانه', 'Multi-select tag picker')}>
          <MultiSelect options={LANGUAGES} defaultValue={['en', 'es', 'ja', 'ar']} placeholder={tr('زبان‌های پاسخ', 'Reply languages')} label={tr('زبان‌های پاسخ', 'Reply languages')} name="languages" />
          <MultiSelect options={[...NETWORKS]} value={networks} onValueChange={setNetworks} max={3} placeholder={tr('شبکه‌ها (حداکثر ۳)', 'Networks (max 3)')} label={tr('شبکه‌ها', 'Networks')} />
          <p className="sc-demo-title" dir="ltr">
            {networks.join(' · ') || '—'}
          </p>
        </Demo>

        <Demo title={tr('نشان وضعیت', 'Status badge')}>
          <div className="sc-row">
            {(['running', 'success', 'failed', 'queued', 'canceled'] as const).map((status) => (
              <StatusBadge key={status} status={status} />
            ))}
          </div>
          <div className="sc-stack" style={{ gap: 'var(--nx-space-2)' }}>
            {pipeline.jobs.map((job) => (
              <div key={job.name} className="sc-row" style={{ justifyContent: 'space-between' }}>
                <span style={{ fontSize: 'var(--nx-text-sm)', fontWeight: 500 }}>{job.name}</span>
                <StatusBadge status={job.status} live />
              </div>
            ))}
          </div>
          <div className="sc-row">
            <Button size="sm" variant="primary" icon="play" onClick={() => pipeline.run(false)}>
              {tr('اجرا', 'Run pipeline')}
            </Button>
            <Button size="sm" icon="alert-triangle" onClick={() => pipeline.run(true)}>
              {tr('اجرا با خطا', 'Run, then fail')}
            </Button>
            <Button size="sm" variant="ghost" onClick={pipeline.cancel}>
              {tr('لغو', 'Cancel')}
            </Button>
          </div>
        </Demo>

        <Demo title={tr('جدول داده', 'Data table')} wide>
          <DataTable
            caption={tr('پروژه‌ها در منطقه‌های Nabu', 'Projects across the Nabu regions')}
            captionHidden
            maxHeight="24rem"
            rows={PROJECTS}
            defaultSort={{ key: 'budget', direction: 'descending' }}
            columns={[
              { key: 'name', label: tr('پروژه', 'Project'), sortable: true },
              { key: 'owner', label: tr('مسئول', 'Owner'), sortable: true },
              { key: 'status', label: tr('وضعیت', 'Status'), format: 'status', sortable: true },
              { key: 'region', label: tr('منطقه', 'Region'), sortable: true },
              { key: 'budget', label: tr('بودجه', 'Budget'), format: 'currency:USD', sortable: true },
              { key: 'progress', label: tr('پیشرفت', 'Progress'), format: 'percent', sortable: true },
              { key: 'updated', label: tr('به‌روزرسانی', 'Updated'), format: 'date', sortable: true },
            ]}
          />
        </Demo>

        <Demo wide>
          <Heatmap
            title={tr('گفت‌وگوهای پاسخ‌داده‌شده', 'Conversations answered')}
            subtitle={tr('۲۶ هفتهٔ اخیر · تیم‌های توکیو، برلین و سائوپائولو', 'Last 26 weeks · Tokyo, Berlin and São Paulo teams')}
            unit={tr('گفت‌وگو', 'conversations')}
            weeks={26}
            data={HEAT}
            summary={tr(`${HEAT.reduce((s, d) => s + d.value, 0).toLocaleString('fa-IR')} گفت‌وگو در شش ماه`, `${HEAT.reduce((s, d) => s + d.value, 0).toLocaleString('en-US')} conversations in 6 months`)}
          />
        </Demo>

        <MetricChart
          title={tr('سلامت فضای کار', 'Workspace health')}
          caption={tr('نسبت به ماه قبل', 'vs last month')}
          labels={MONTHS}
          metrics={METRICS}
          style={{ gridColumn: '1 / -1' }}
        />

        <Demo>
          <DotMatrixChart
            title={tr('تیکت‌ها بر اساس زبان', 'Tickets by language')}
            subtitle={tr('این هفته', 'This week')}
            labels={['EN', 'ES', 'FR', 'DE', 'JA', 'ZH', 'AR', 'FA', 'HI', 'PT', 'KO', 'TR']}
            series={[
              { name: tr('حل‌شده توسط ایجنت', 'Solved by the agent'), values: [86, 64, 41, 38, 52, 47, 33, 29, 44, 36, 31, 27] },
              { name: tr('ارجاع به انسان', 'Handed to a human'), values: [12, 9, 6, 7, 5, 8, 6, 4, 9, 5, 4, 6] },
            ]}
          />
        </Demo>

        <AnalyticsCard title={tr('بازدیدکننده‌ها', 'Visitors')} periods={PERIODS} />

        <CurrencyConverter title={tr('تبدیل ارز', 'Convert')} rates={RATES} defaultValue={{ amount: 1250, from: 'USD', to: 'JPY' }} note={tr('نرخ میانه · داده نمایشی', 'Mid-market · demo rates')} />

        <SupportAgentCard
          name="Amara Okafor"
          role="Billing · Lagos"
          status="online"
          metrics={[
            { label: tr('تیکت‌های حل‌شده', 'Tickets resolved'), value: 342, max: 400, display: '342 / 400' },
            { label: 'CSAT', value: 96, display: '96%', tone: 'success' },
            { label: tr('اولین پاسخ', 'First response'), value: 72, display: '1m 42s', tone: 'info' },
          ]}
          trend={[12, 18, 14, 22, 26, 24, 31, 29, 35, 33, 41, 44]}
          trendLabel={tr('حل‌شده، ۱۲ هفته', 'Resolved, 12 weeks')}
          trendValue="329"
          action={{ label: tr('ارجاع تیکت', 'Assign a ticket'), icon: 'arrow-right', onClick: () => toast.success(tr('تیکت به آمارا سپرده شد', 'Ticket assigned to Amara')) }}
        />

        <SupportAgentCard
          name="Kenji Watanabe"
          role="Onboarding · 東京"
          status="away"
          metrics={[
            { label: tr('تیکت‌های حل‌شده', 'Tickets resolved'), value: 214, max: 400, display: '214 / 400' },
            { label: 'CSAT', value: 91, display: '91%', tone: 'success' },
            { label: tr('اولین پاسخ', 'First response'), value: 48, display: '3m 05s', tone: 'warning' },
          ]}
          trend={[30, 28, 31, 27, 25, 26, 22, 24, 21, 23, 20, 19]}
          trendLabel={tr('حل‌شده، ۱۲ هفته', 'Resolved, 12 weeks')}
          trendValue="296"
          action={{ label: tr('پیام به کنجی', 'Message Kenji'), icon: 'message', onClick: () => toast(tr('گفت‌وگو باز شد', 'Conversation opened')) }}
        />

        <UsageCard
          title={tr('فضای ذخیره‌سازی', 'Storage')}
          plan="Pro"
          limit={10}
          format={{ style: 'unit', unit: 'gigabyte', maximumFractionDigits: 1 }}
          categories={[
            { label: tr('اسناد', 'Documents'), value: 3.2 },
            { label: tr('پیام‌های صوتی', 'Voice notes'), value: 2.1 },
            { label: tr('تصویرها', 'Images'), value: 1.4 },
            { label: 'Embeddings', value: 0.7 },
          ]}
          note={tr('اول هر ماه صفر می‌شود', 'Resets on the 1st')}
          action={{ label: tr('ارتقای طرح', 'Upgrade plan') }}
        />

        <UsageCard
          title={tr('توکن‌های مدل', 'Model tokens')}
          plan="Growth"
          limit={5_000_000}
          format={{ notation: 'compact', maximumFractionDigits: 2 }}
          categories={[
            { label: tr('پاسخ‌ها', 'Replies'), value: 2_900_000 },
            { label: tr('خلاصه‌ها', 'Summaries'), value: 1_100_000 },
            { label: tr('ترجمه‌ها', 'Translations'), value: 660_000 },
          ]}
          note={tr('۹۳٪ مصرف شده؛ ایجنت‌ها در ۱۰۰٪ متوقف می‌شوند', '93% used: agents pause at 100%')}
          action={{ label: tr('ارتقا', 'Upgrade'), onClick: () => toast.success(tr('درخواست ارتقا ثبت شد', 'Upgrade requested')) }}
        />

        <Demo title={tr('اتصال شاخه‌ای', 'Branch connector')}>
          <BranchConnector
            source={{ label: tr('پیام ورودی', 'Inbound message'), description: 'WhatsApp · Telegram · Web', icon: 'message' }}
            targets={[
              { label: 'Nabu agent', description: 'English · Français', icon: 'sparkles' },
              { label: 'Agente Nabu', description: 'Español · Português', icon: 'globe' },
              { label: 'Nabu エージェント', description: '日本語 · 한국어', icon: 'cpu' },
              { label: tr('ارجاع به انسان', 'Human handoff'), description: tr('صف: صورت‌حساب', 'Queue: billing'), icon: 'users', state: 'idle' },
            ]}
          />
        </Demo>

        <Demo title={tr('پوستهٔ فضای کار', 'AI workspace shell')} wide bare>
          <WorkspaceShell
            brand="Nabu Desk"
            aria-label={tr('فضای کار', 'Workspace')}
            height="34rem"
            value={view}
            onValueChange={setView}
            items={[
              { id: 'inbox', label: tr('صندوق', 'Inbox'), icon: 'message', badge: 12 },
              { id: 'agents', label: tr('ایجنت‌ها', 'Agents'), icon: 'sparkles' },
              { id: 'knowledge', label: tr('دانش', 'Knowledge'), icon: 'layers' },
              { id: 'analytics', label: tr('تحلیل', 'Analytics'), icon: 'chart' },
              { id: 'settings', label: tr('تنظیمات', 'Settings'), icon: 'sliders' },
            ]}
            header={
              <>
                <strong style={{ fontSize: 'var(--nx-text-md)' }}>{view[0]!.toUpperCase() + view.slice(1)}</strong>
                <Badge tone="success" pulse>
                  {tr('زنده', 'Live')}
                </Badge>
                <span style={{ marginInlineStart: 'auto' }}>
                  <AvatarGroup size="sm" people={[{ name: 'Amara Okafor' }, { name: 'Kenji Watanabe' }, { name: 'Lucía Fernández' }, { name: 'Layla Haddad' }]} />
                </span>
              </>
            }
            footer={
              <div style={{ display: 'flex', alignItems: 'center', gap: '0.75rem', minBlockSize: '2.5rem', paddingInline: '0.75rem', fontSize: 'var(--nx-text-sm)', fontWeight: 500, whiteSpace: 'nowrap' }}>
                <Avatar name="Hussein" size="xs" status="online" />
                <span className="nx-workspace-label">Hussein</span>
              </div>
            }
            promptPlaceholder={tr('هر چه می‌خواهید از Nabu بپرسید…', 'Ask Nabu anything — en cualquier idioma…')}
            onPromptSubmit={(text) => toast({ title: tr('Nabu در حال پاسخ است', 'Nabu is on it'), description: text, tone: 'accent' })}
          >
            <div style={{ display: 'grid', gap: 'var(--nx-space-3)' }}>
              {INBOX.map(([text, who]) => (
                <div key={text} className="sc-demo" style={{ padding: 'var(--nx-space-4)', gap: 'var(--nx-space-1)' }}>
                  <span style={{ fontWeight: 600 }}>{text}</span>
                  <span style={{ fontSize: 'var(--nx-text-xs)', color: 'var(--nx-text-muted)' }}>{who}</span>
                </div>
              ))}
            </div>
          </WorkspaceShell>
        </Demo>

        <Demo title={tr('خط زمانی منحنی', 'Curved timeline')} wide>
          <CurvedTimeline
            aria-label={tr('نقشهٔ راه', 'Roadmap')}
            items={[
              { date: 'Q1 2025', title: tr('بتای خصوصی در برلین', 'Private beta in Berlin'), description: tr('چهل تیم پشتیبانی، سه زبان، یک صندوق.', 'Forty support teams, three languages, one inbox.') },
              { date: 'Q3 2025', title: tr('دوازده زبان', 'Twelve languages'), description: 'Español, Français, 日本語, العربية…' },
              { date: 'Q1 2026', title: tr('صدا در توکیو', 'Voice in Tokyo'), description: '音声での応答: agents that listen and speak on the phone.' },
              { date: 'Q2 2026', title: tr('دفتر سائوپائولو', 'São Paulo office'), description: 'Atendimento em português, 24 horas por dia.' },
              { date: 'Q4 2026', title: tr('راست‌به‌چپ همه‌جا', 'Right-to-left everywhere'), description: 'فارسی و العربية with mirrored layouts across every surface.' },
            ]}
          />
        </Demo>

        <Demo wide bare>
          <ComparisonTable
            caption={tr('مقایسهٔ طرح‌های Nabu', 'Compare Nabu plans')}
            recommended="growth"
            plans={[
              { id: 'starter', name: 'Starter', price: '$0', period: '/mo', description: tr('برای آزمودن Nabu', 'For trying Nabu out'), action: { label: tr('شروع رایگان', 'Start free') } },
              { id: 'growth', name: 'Growth', price: '$49', period: '/mo', description: tr('برای تیم‌های پشتیبانی رو به رشد', 'For growing support teams'), action: { label: tr('انتخاب Growth', 'Choose Growth'), onClick: () => toast.success('Growth ✓') } },
              { id: 'scale', name: 'Scale', price: '$199', period: '/mo', description: tr('برای عملیات جهانی', 'For global operations'), action: { label: tr('گفت‌وگو با فروش', 'Talk to sales') } },
            ]}
            features={[
              { group: tr('ایجنت‌ها', 'Agents'), label: tr('زبان‌ها', 'Languages'), values: { starter: '3', growth: '12', scale: '40+' } },
              { group: tr('ایجنت‌ها', 'Agents'), label: tr('پاسخ صوتی', 'Voice replies'), hint: 'WhatsApp · Telegram', values: { starter: false, growth: true, scale: true } },
              { group: tr('ایجنت‌ها', 'Agents'), label: tr('دانش اختصاصی', 'Custom knowledge'), values: { starter: true, growth: true, scale: true } },
              { group: tr('کانال‌ها', 'Channels'), label: 'WhatsApp & Telegram', values: { starter: true, growth: true, scale: true } },
              { group: tr('کانال‌ها', 'Channels'), label: tr('خط تلفن', 'Phone lines'), values: { starter: false, growth: false, scale: true } },
              { group: tr('امنیت', 'Security'), label: 'SSO / SAML', values: { starter: false, growth: false, scale: true } },
              { group: tr('امنیت', 'Security'), label: tr('گزارش ممیزی', 'Audit log'), values: { starter: false, growth: true, scale: true } },
              { group: tr('امنیت', 'Security'), label: tr('محل نگهداری داده', 'Data residency'), values: { starter: '—', growth: 'EU', scale: 'EU · US · Asia' } },
            ]}
          />
        </Demo>
      </div>
    </Section>
  );
}
