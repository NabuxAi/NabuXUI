import { useEffect, useRef, useState } from 'react';
import {
  ApprovalCard,
  Button,
  CodeBlock,
  DiffViewer,
  JsonViewer,
  StreamingText,
  Terminal,
  type TerminalLine,
  ThinkingOrbs,
  type ThinkingStep,
  ThinkingTrace,
  ToolCall,
  type ToolCallStatus,
  type OrbState,
} from '@nabuxai/ui-react';
import { simulateStream } from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useTr } from '../lang';

/** Timers that die with the component. */
function useTimers() {
  const timers = useRef<Array<ReturnType<typeof setTimeout>>>([]);
  useEffect(() => () => timers.current.forEach(clearTimeout), []);
  return {
    later(fn: () => void, ms: number) {
      timers.current.push(setTimeout(fn, ms));
    },
    clear() {
      timers.current.forEach(clearTimeout);
      timers.current = [];
    },
  };
}

function TraceDemo() {
  const tr = useTr();
  const timers = useTimers();
  const [active, setActive] = useState(false);
  const [steps, setSteps] = useState<ThinkingStep[]>([]);
  const [started, setStarted] = useState(Date.now());
  const plan = [
    tr('خواندن ساختار جدول سفارش‌ها', 'Reading the orders schema'),
    tr('یافتن کوئری‌های کند', 'Finding the slow queries'),
    tr('مقایسهٔ ایندکس ترکیبی با دو ایندکس تکی', 'Comparing a composite index with two single ones'),
    tr('نوشتن مایگریشن برگشت‌پذیر', 'Drafting a reversible migration'),
  ];
  const run = () => {
    timers.clear();
    setSteps([]);
    setStarted(Date.now());
    setActive(true);
    plan.forEach((label, i) =>
      timers.later(() => setSteps((list) => [...list.map((step) => ({ ...step, status: 'done' as const })), { id: `s${i}`, label, status: 'running' }]), 600 + i * 1200),
    );
    timers.later(() => {
      setSteps((list) => list.map((step) => ({ ...step, status: 'done' as const })));
      setActive(false);
    }, 600 + plan.length * 1200 + 400);
  };
  // eslint-disable-next-line react-hooks/exhaustive-deps
  useEffect(run, []);
  return (
    <div className="sc-stack" style={{ display: 'grid', gap: '0.75rem' }}>
      <ThinkingTrace active={active} startedAt={started} steps={steps} labels={{ thinking: tr('در حال فکر…', 'Thinking…'), thought: tr('{time} فکر کرد', 'Thought for {time}') }} />
      <div>
        <Button size="sm" variant="ghost" icon="sparkles" onClick={run}>
          {tr('دوباره بپرس', 'Ask again')}
        </Button>
      </div>
    </div>
  );
}

function StreamDemo() {
  const tr = useTr();
  const full = tr(
    'بهترین زمان سفر به تهران بهار است، از اواخر اسفند تا اردیبهشت [1]. روزها معتدل‌اند و البرز هنوز برف دارد [2]. در Livewire و Inertia همین متن بدون شکستن حروف پخش می‌شود.',
    'The best time to visit Tehran is spring, from late March to May [1]. Days are mild and the Alborz still has snow [2]. The same text streams in Livewire and Inertia without breaking a letter.',
  );
  const [text, setText] = useState('');
  const [streaming, setStreaming] = useState(true);
  const [round, setRound] = useState(0);
  useEffect(() => {
    setText('');
    setStreaming(true);
    return simulateStream(full, {
      interval: 70,
      onUpdate: (next, done) => {
        setText(next);
        setStreaming(!done);
      },
    });
  }, [full, round]);
  return (
    <div style={{ display: 'grid', gap: '0.75rem' }}>
      <StreamingText
        key={round}
        text={text}
        streaming={streaming}
        sources={[
          { title: tr('تهران — اقلیم', 'Tehran — climate'), url: 'https://en.wikipedia.org/wiki/Tehran', snippet: tr('اقلیم نیمه‌خشک با تابستان‌های گرم و زمستان‌های خنک.', 'A cold semi-arid climate with hot summers and cool winters.') },
          { title: tr('رشته‌کوه البرز', 'Alborz range'), url: 'https://en.wikipedia.org/wiki/Alborz', snippet: tr('قله‌های شمال تهران تا اواخر بهار برف دارند.', 'The peaks north of Tehran keep snow until late spring.') },
        ]}
        citeLabel={(n) => tr(`منبع ${n}`, `Source ${n}`)}
        openLabel={tr('باز کردن منبع', 'Open source')}
      />
      <div>
        <Button size="sm" variant="ghost" icon="sparkles" onClick={() => setRound((n) => n + 1)}>
          {tr('تولید دوباره', 'Regenerate')}
        </Button>
      </div>
    </div>
  );
}

function ToolDemo() {
  const tr = useTr();
  const timers = useTimers();
  const [status, setStatus] = useState<ToolCallStatus[]>(['queued', 'queued']);
  const run = () => {
    timers.clear();
    setStatus(['running', 'queued']);
    timers.later(() => setStatus(['success', 'running']), 1400);
    timers.later(() => setStatus(['success', 'success']), 2600);
  };
  // eslint-disable-next-line react-hooks/exhaustive-deps
  useEffect(run, []);
  const labels = { input: tr('ورودی', 'Input'), output: tr('خروجی', 'Output'), queued: tr('در صف', 'Queued'), running: tr('در حال اجرا', 'Running'), success: tr('انجام شد', 'Done'), error: tr('ناموفق', 'Failed') };
  return (
    <div style={{ display: 'grid', gap: '0.5rem' }}>
      <ToolCall name="search_flights" status={status[0]} duration={1380} labels={labels} args={{ from: 'THR', to: 'IFN', passengers: 2 }} output={{ results: 6, cheapest: { airline: 'Iran Air', price: 84 } }} />
      <ToolCall name="get_weather" status={status[1]} duration={410} labels={labels} args={{ city: 'Isfahan', days: 3 }} output="Sunny, 24°C / 11°C" />
      <ToolCall name="run_migrations" status="error" duration={2310} labels={labels} args={{ database: 'orders' }} output="SQLSTATE[42S21]: Duplicate column name 'archived_at'" />
      <div>
        <Button size="sm" variant="ghost" icon="sparkles" onClick={run}>
          {tr('اجرای دوباره', 'Run again')}
        </Button>
      </div>
    </div>
  );
}

const BEFORE = `return [
    'default' => env('CACHE_STORE', 'file'),
    'stores' => [
        'file' => ['driver' => 'file'],
    ],
    'prefix' => 'nabux_cache_',
];`;
const AFTER = `return [
    'default' => env('CACHE_STORE', 'redis'),
    'stores' => [
        'file' => ['driver' => 'file'],
        'redis' => ['driver' => 'redis', 'connection' => 'cache'],
    ],
    'prefix' => 'nabux_cache_',
];`;

function OrbsDemo() {
  const tr = useTr();
  const states: OrbState[] = ['idle', 'listening', 'thinking', 'speaking'];
  const words = { idle: tr('بیکار', 'Idle'), listening: tr('در حال شنیدن', 'Listening'), thinking: tr('در حال فکر', 'Thinking'), speaking: tr('در حال صحبت', 'Speaking') };
  return (
    <div style={{ display: 'flex', flexWrap: 'wrap', gap: '2rem', alignItems: 'center' }}>
      {states.map((state) => (
        <figure key={state} style={{ display: 'grid', justifyItems: 'center', gap: '0.5rem', margin: 0 }}>
          <ThinkingOrbs state={state} size="lg" label={words[state]} />
          <figcaption style={{ fontSize: 'var(--nx-text-xs)', color: 'var(--nx-text-muted)' }}>{words[state]}</figcaption>
        </figure>
      ))}
    </div>
  );
}

function TerminalDemo() {
  const tr = useTr();
  const timers = useTimers();
  const [lines, setLines] = useState<TerminalLine[]>([]);
  const script = [
    'Pulling main@4e1c9a2…',
    'Installing composer dependencies (82 packages)',
    '[warn] abandoned package: fruitcake/laravel-cors',
    'Building assets with Vite',
    '✓ assets built in 3.9s',
    'Running migrations',
    "ERROR: SQLSTATE[42S21] Duplicate column name 'archived_at'",
    '✓ rolled back the last batch',
    'پیام برای تیم: استقرار متوقف شد',
  ];
  const run = () => {
    timers.clear();
    setLines([]);
    script.forEach((text, i) => timers.later(() => setLines((list) => [...list, { id: `${Date.now()}-${i}`, text, time: `10:24:${String(i * 2 + 1).padStart(2, '0')}` }]), 300 + i * 450));
  };
  // eslint-disable-next-line react-hooks/exhaustive-deps
  useEffect(run, []);
  return (
    <div style={{ display: 'grid', gap: '0.75rem' }}>
      <Terminal title={tr('استقرار · محیط اصلی', 'deploy · production')} lines={lines} command="tail -f deploy.log" height="14rem" labels={{ all: tr('همه', 'All'), info: tr('اطلاع', 'Info'), warn: tr('هشدار', 'Warn'), error: tr('خطا', 'Error'), jump: tr('پرش به آخرین', 'Jump to latest') }} />
      <div>
        <Button size="sm" variant="ghost" icon="play" onClick={run}>
          {tr('پخش دوباره', 'Replay')}
        </Button>
      </div>
    </div>
  );
}

export function AgentBlocks() {
  const tr = useTr();
  return (
    <Section
      id="agent-blocks"
      eyebrow={tr('بلوک‌ها', 'Blocks')}
      title={tr('رابط عامل هوش مصنوعی', 'AI agent interfaces')}
      description={tr(
        'فکر کردن، پاسخ جاری با منبع، فراخوانی ابزار، اجازه گرفتن، کد، تفاوت، JSON و ترمینال — همه با یک CSS برای React و Livewire. کد و لاگ همیشه چپ‌به‌راست می‌مانند؛ متن فارسی هرگز حرف‌به‌حرف شکسته نمی‌شود.',
        'Reasoning, streamed answers with sources, tool calls, permission, code, diffs, JSON and logs — one CSS for React and Livewire. Code and logs stay left-to-right; Persian is never split letter by letter.',
      )}
      code={{
        react: `<ThinkingTrace active={thinking} steps={steps} />
<StreamingText text={answer} streaming={streaming} sources={sources} />
<ToolCall name="search_web" args={{ query }} status="running" />
<ApprovalCard title="Run migration?" tool="php artisan migrate" onApprove={run}>
  <DiffViewer oldText={before} newText={after} />
</ApprovalCard>
<ThinkingOrbs state="speaking" />
<CodeBlock filename="app.ts" language="ts" highlight="3-5" code={source} />
<JsonViewer data={response} defaultDepth={2} />
<Terminal title="deploy" lines={lines} />`,
        blade: `<x-nx::thinking-trace :active="$thinking" :steps="$steps" />
<x-nx::streaming-text :text="$answer" :streaming="$streaming" :sources="$sources" />
<x-nx::tool-call name="search_web" :args="['query' => $q]" status="running" />
<x-nx::approval-card title="Run migration?" tool="php artisan migrate" wire:model.live="decision">
    <x-nx::diff-viewer :old-text="$before" :new-text="$after" />
</x-nx::approval-card>
<x-nx::thinking-orbs state="speaking" />
<x-nx::code-block filename="app.ts" language="ts" highlight="3-5" :code="$source" />
<x-nx::json-viewer :data="$response" :depth="2" />
<x-nx::terminal title="deploy" :lines="$lines" />`,
      }}
    >
      <div className="sc-demos">
        <Demo title={tr('ردِ فکر', 'Thinking trace')}>
          <TraceDemo />
        </Demo>
        <Demo title={tr('متن جاری با منبع', 'Streaming text with sources')}>
          <StreamDemo />
        </Demo>
        <Demo title={tr('فراخوانی ابزار', 'Tool calls')}>
          <ToolDemo />
        </Demo>
        <Demo title={tr('گوی‌های فکر', 'Thinking orbs')} center>
          <OrbsDemo />
        </Demo>
        <Demo title={tr('کارت تأیید', 'Approval card')} wide>
          <ApprovalCard
            title={tr('کش محیط اصلی به Redis تغییر کند؟', 'Switch the production cache to Redis?')}
            summary={tr('فایل config/cache.php را ویرایش می‌کند.', 'Edits config/cache.php and restarts the workers.')}
            tool="edit_file · config/cache.php"
            undoable
            labels={{ approve: tr('تأیید', 'Approve'), deny: tr('رد', 'Deny'), always: tr('همیشه مجاز', 'Always allow'), approved: tr('تأیید شد', 'Approved'), denied: tr('رد شد', 'Denied'), alwaysAllowed: tr('همیشه مجاز شد', 'Always allowed'), undo: tr('برگرداندن', 'Undo') }}
          >
            <DiffViewer filename="config/cache.php" oldText={BEFORE} newText={AFTER} context={1} />
          </ApprovalCard>
        </Demo>
        <Demo title={tr('بلوک کد', 'Code block')} wide>
          <CodeBlock
            filename="resources/js/cart.ts"
            language="ts"
            highlight="3"
            code={`export function total(items: Item[], coupon?: Coupon) {
  const sum = items.reduce((acc, item) => acc + item.price * item.qty, 0);
  return Math.max(0, sum - (coupon?.amount ?? 0)); // never negative
}`}
          />
        </Demo>
        <Demo title={tr('نمایشگر تفاوت', 'Diff viewer')} wide>
          <DiffViewer filename="config/cache.php" oldText={BEFORE} newText={AFTER} defaultView="split" />
        </Demo>
        <Demo title={tr('نمایشگر JSON', 'JSON viewer')} wide>
          <JsonViewer
            defaultDepth={2}
            data={{ id: 'ord_8F2K19', status: 'shipped', paid: true, total: 1840000, customer: { name: 'سارا رضایی', city: 'Isfahan' }, items: [{ sku: 'TEA-SAF-250', qty: 2 }, { sku: 'NAB-PIS-500', qty: 1 }], refund: null }}
          />
        </Demo>
        <Demo title={tr('ترمینال', 'Terminal')} wide>
          <TerminalDemo />
        </Demo>
      </div>
    </Section>
  );
}
