import { useEffect, useRef, useState } from 'react';
import {
  AutoHeight,
  BorderBeam,
  Button,
  DynamicIsland,
  GlitchText,
  GooStack,
  type GooStackItem,
  ImageReveal,
  IslandView,
  ResizableHandle,
  ResizablePane,
  ResizablePanels,
  ScrollProgress,
  SpotlightTour,
  Typewriter,
} from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useTr } from '../lang';

const box = { border: '1px solid var(--nx-border)', borderRadius: 'var(--nx-radius-xl)', background: 'var(--nx-surface)' } as const;
const muted = { margin: 0, color: 'var(--nx-text-muted)', fontSize: 'var(--nx-text-sm)' } as const;

/** A generated landscape as an SVG data URI: the image the reveal resolves into. */
const landscape = (() => {
  const svg =
    '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 450" preserveAspectRatio="xMidYMid slice">' +
    '<defs><linearGradient id="s" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#1b1f4b"/><stop offset="1" stop-color="#f08a5d"/></linearGradient></defs>' +
    '<rect width="800" height="450" fill="url(#s)"/><circle cx="560" cy="190" r="60" fill="#ffd27a"/>' +
    '<path d="M0 290 Q150 220 300 270 T600 245 T800 260 V450 H0Z" fill="#3b2f63"/>' +
    '<path d="M0 350 Q200 290 380 340 T800 320 V450 H0Z" fill="#2a2350"/>' +
    '<path d="M0 400 Q250 360 480 400 T800 385 V450 H0Z" fill="#171433"/></svg>';
  return `data:image/svg+xml;charset=utf-8,${encodeURIComponent(svg)}`;
})();

function GooDemo() {
  const tr = useTr();
  const pool: Array<Omit<GooStackItem, 'id'>> = [
    { label: tr('سارا شما را دعوت کرد', 'Ava invited you'), icon: 'users' },
    { label: tr('استقرار ۳۱۸ تمام شد', 'Deploy #318 finished'), icon: 'check-circle' },
    { label: tr('گزارش هفتگی آماده است', 'Weekly report is ready'), icon: 'chart' },
  ];
  const [items, setItems] = useState<GooStackItem[]>([
    { id: 'a', label: tr('فاکتور ۱۰۴۲ پرداخت شد', 'Invoice #1042 was paid'), icon: 'check-circle' },
    { id: 'b', label: tr('علی نظر داد', 'Sam left a comment'), icon: 'message' },
  ]);
  const seq = useRef(0);
  return (
    <div style={{ display: 'grid', gap: '1rem', justifyItems: 'center' }}>
      <GooStack items={items} label={tr('اعلان‌ها', 'Notifications')} dismissLabel={tr('بستن', 'Dismiss')} onDismiss={(id) => setItems((list) => list.filter((item) => item.id !== id))} />
      <Button
        variant="secondary"
        icon="plus"
        onClick={() => {
          const next = pool[seq.current++ % pool.length]!;
          setItems((list) => [{ ...next, id: `n${seq.current}` }, ...list]);
        }}
      >
        {tr('اعلان تازه', 'New notification')}
      </Button>
    </div>
  );
}

function IslandDemo() {
  const tr = useTr();
  const [view, setView] = useState('idle');
  const [seconds, setSeconds] = useState(300);
  useEffect(() => {
    if (view !== 'timer' && view !== 'timer-open') return;
    const id = window.setInterval(() => setSeconds((s) => Math.max(0, s - 1)), 1000);
    return () => window.clearInterval(id);
  }, [view]);
  const clock = `${String(Math.floor(seconds / 60)).padStart(2, '0')}:${String(seconds % 60).padStart(2, '0')}`;
  return (
    <div style={{ display: 'grid', gap: '1rem', justifyItems: 'center' }}>
      <div style={{ inlineSize: 'min(100%, 22rem)', blockSize: '12rem', padding: '.75rem', borderRadius: '2.25rem', background: 'var(--nx-surface-2)', boxShadow: 'inset 0 0 0 1px var(--nx-border)' }}>
        <DynamicIsland view={view} label={tr('فعالیت زنده', 'Live activity')}>
          <IslandView name="idle">
            <div style={{ inlineSize: '6.5rem', blockSize: '1.25rem' }} />
          </IslandView>
          <IslandView name="timer">
            <button type="button" className="nx-island-toggle" onClick={() => setView('timer-open')} aria-label={tr('باز کردن تایمر', 'Expand timer')}>
              <span className="nx-island-dot" style={{ background: 'var(--nx-warning)' }} />
              <span className="nx-island-figure">{clock}</span>
            </button>
          </IslandView>
          <IslandView name="timer-open" size="expanded">
            <div className="nx-island-row" data-spread="">
              <div className="nx-island-stack">
                <span className="nx-island-sub">{tr('تایمر چای', 'Tea timer')}</span>
                <span className="nx-island-figure" style={{ fontSize: 'var(--nx-text-3xl)' }}>
                  {clock}
                </span>
              </div>
              <button type="button" className="nx-island-btn" data-tone="danger" onClick={() => setView('idle')}>
                {tr('توقف', 'Stop')}
              </button>
            </div>
          </IslandView>
          <IslandView name="call" size="expanded">
            <div className="nx-island-row">
              <span className="nx-island-avatar" aria-hidden="true">
                {tr('س', 'S')}
              </span>
              <div className="nx-island-stack">
                <span className="nx-island-sub">{tr('تماس ورودی', 'Incoming call')}</span>
                <span className="nx-island-title">{tr('سارا احمدی', 'Sara Ahmadi')}</span>
              </div>
            </div>
            <div className="nx-island-actions">
              <button type="button" className="nx-island-btn" data-tone="danger" style={{ flex: 1 }} onClick={() => setView('idle')}>
                {tr('رد', 'Decline')}
              </button>
              <button type="button" className="nx-island-btn" data-tone="success" style={{ flex: 1 }} onClick={() => setView('idle')}>
                {tr('پاسخ', 'Answer')}
              </button>
            </div>
          </IslandView>
        </DynamicIsland>
      </div>
      <div className="sc-row">
        <Button
          size="sm"
          variant="secondary"
          onClick={() => {
            setSeconds(300);
            setView('timer');
          }}
        >
          {tr('تایمر', 'Timer')}
        </Button>
        <Button size="sm" variant="secondary" onClick={() => setView('call')}>
          {tr('تماس', 'Call')}
        </Button>
        <Button size="sm" variant="ghost" onClick={() => setView('idle')}>
          {tr('آرام', 'Idle')}
        </Button>
      </div>
    </div>
  );
}

function RevealDemo() {
  const tr = useTr();
  const [progress, setProgress] = useState(0);
  const [src, setSrc] = useState<string | undefined>();
  const timer = useRef(0);
  useEffect(() => () => window.clearInterval(timer.current), []);
  const generate = () => {
    window.clearInterval(timer.current);
    setSrc(undefined);
    setProgress(0);
    let value = 0;
    timer.current = window.setInterval(() => {
      value = Math.min(100, value + 4 + Math.round(Math.random() * 6));
      setProgress(value);
      if (value >= 100) {
        window.clearInterval(timer.current);
        setSrc(landscape);
      }
    }, 220);
  };
  return (
    <div style={{ display: 'grid', gap: '.75rem' }}>
      <ImageReveal src={src} progress={progress} ratio="16 / 9" alt={tr('فانوس دریایی در غروب', 'A lighthouse at dusk')} label={tr('در حال ساخت', 'Generating')} />
      <div className="sc-row">
        <Button variant="primary" icon="sparkles" onClick={generate}>
          {tr('بساز', 'Generate')}
        </Button>
      </div>
    </div>
  );
}

export function EffectsBlocks() {
  const tr = useTr();
  const [tourOpen, setTourOpen] = useState(false);
  const [busy, setBusy] = useState(false);
  const [tab, setTab] = useState<'post' | 'courier'>('post');

  return (
    <Section
      id="effects-blocks"
      eyebrow={tr('بلوک‌ها', 'Blocks')}
      title={tr('افکت‌ها و حرکت چیدمان', 'Effects & layout motion')}
      description={tr(
        'پرتو دور لبه، قرص‌های مایع، متن گلیچ و تایپ‌شونده، پنل‌های قابل‌تغییر اندازه، پیشرفت مطالعه، ارتفاعی که با فنر می‌رسد، جزیرهٔ پویا، تور راهنما و تصویری که از دل نویز آشکار می‌شود.',
        'A beam around a border, liquid pills, glitching and typed text, resizable panes, reading progress, height that springs, a dynamic island, a spotlight tour and an image that resolves out of noise.',
      )}
      code={{
        react: `<BorderBeam tone="gold" radius="var(--nx-radius-xl)"><Card>…</Card></BorderBeam>
<GooStack items={items} onDismiss={remove} />
<GlitchText text="404 — nothing here" trigger="loop" />
<Typewriter phrases={['We build websites', 'We build apps']} />
<ResizablePanels storageKey="editor">
  <ResizablePane defaultSize={25} minSize={15} collapsible>…</ResizablePane>
  <ResizableHandle label="Resize sidebar" />
  <ResizablePane>…</ResizablePane>
</ResizablePanels>
<ScrollProgress />  <ScrollProgress variant="circle" />
<AutoHeight>{tab === 'a' ? <Short /> : <Long />}</AutoHeight>
<DynamicIsland view={view}><IslandView name="idle">…</IslandView>…</DynamicIsland>
<SpotlightTour open={open} onOpenChange={setOpen} steps={[{ target: '#new', title: 'Start here' }]} />
<ImageReveal src={url} progress={progress} alt="…" />`,
        blade: `<x-nx::border-beam tone="gold" radius="var(--nx-radius-xl)">…</x-nx::border-beam>
<x-nx::goo-stack :items="$notifications" />
<x-nx::glitch-text text="404 — nothing here" trigger="loop" />
<x-nx::typewriter :phrases="['We build websites', 'We build apps']" />
<x-nx::resizable-panels storage-key="editor">
  <x-nx::resizable-pane :size="25" :min="15" collapsible>…</x-nx::resizable-pane>
  <x-nx::resizable-handle label="Resize sidebar" />
  <x-nx::resizable-pane>…</x-nx::resizable-pane>
</x-nx::resizable-panels>
<x-nx::scroll-progress />  <x-nx::scroll-progress variant="circle" />
<x-nx::auto-height>…</x-nx::auto-height>
<x-nx::dynamic-island view="idle"><x-nx::island-view name="idle">…</x-nx::island-view></x-nx::dynamic-island>
<x-nx::spotlight-tour id="welcome" :steps="$steps" />
<x-nx::image-reveal :src="$url" :progress="$progress" alt="…" />`,
      }}
    >
      <div className="sc-demos">
        <Demo title={tr('پرتو دور لبه', 'Border beam')} center>
          <BorderBeam tone="gold" radius="var(--nx-radius-xl)" size={90}>
            <div style={{ ...box, display: 'grid', gap: '.5rem', padding: '1.25rem', minInlineSize: '14rem' }}>
              <strong>{tr('پلن استودیو', 'Studio plan')}</strong>
              <span style={{ font: '700 var(--nx-text-2xl)/1 var(--nx-font-display)' }}>{tr('۴۹۰ هزار / ماه', '$19 / mo')}</span>
              <Button variant="primary" size="sm">
                {tr('شروع', 'Start trial')}
              </Button>
            </div>
          </BorderBeam>
          <div
            className={busy ? 'nx-beam' : undefined}
            data-tone="violet"
            style={{ ...box, display: 'flex', gap: '.5rem', alignItems: 'center', padding: '.5rem .5rem .5rem 1rem', marginBlockStart: '1rem' }}
          >
            <span style={{ ...muted, flex: 1 }}>{busy ? tr('در حال فکر…', 'Thinking…') : tr('آماده', 'Ready')}</span>
            <Button
              size="sm"
              icon="sparkles"
              disabled={busy}
              onClick={() => {
                setBusy(true);
                window.setTimeout(() => setBusy(false), 3000);
              }}
            >
              {tr('بپرس', 'Ask')}
            </Button>
          </div>
        </Demo>

        <Demo title={tr('پشتهٔ مایع', 'Goo stack')} center>
          <GooDemo />
        </Demo>

        <Demo title={tr('متن گلیچ', 'Glitch text')} center>
          <div style={{ display: 'grid', gap: '1rem', justifyItems: 'center', textAlign: 'center' }}>
            <GlitchText text={tr('۴۰۴ — این‌جا چیزی نیست', '404 — nothing here')} trigger="loop" style={{ font: '800 var(--nx-text-3xl)/1.2 var(--nx-font-display)' }} />
            <a href="#effects-blocks" style={{ color: 'inherit', fontWeight: 600 }}>
              <GlitchText text={tr('وضعیت سرویس‌ها', 'System status')} />
            </a>
          </div>
        </Demo>

        <Demo title={tr('ماشین تحریر', 'Typewriter')}>
          <p style={{ margin: 0, font: '800 var(--nx-text-2xl)/1.3 var(--nx-font-display)', minBlockSize: '2.6em' }}>
            <Typewriter phrases={tr('ما وب‌سایت می‌سازیم|ما اپلیکیشن می‌سازیم|ما برند می‌سازیم', 'We build websites|We build mobile apps|We build brands').split('|')} />
          </p>
          <p style={{ ...muted, fontFamily: 'var(--nx-font-mono)' }}>
            <Typewriter by="word" loop={false} caret="block" typeSpeed={140} phrases={tr('در حال خواندن ۳ فایل…|پیش‌نویس آماده است.', 'Reading 3 files…|Your draft is ready.').split('|')} />
          </p>
        </Demo>

        <Demo title={tr('پنل‌های قابل‌تغییر اندازه', 'Resizable panels')} wide>
          <div style={{ ...box, blockSize: '18rem', overflow: 'hidden' }}>
            <ResizablePanels storageKey="sc-fx-panels">
              <ResizablePane defaultSize={25} minSize={15} maxSize={40} collapsible style={{ background: 'var(--nx-surface-2)', padding: '.75rem' }}>
                <p style={muted}>{tr('فایل‌ها', 'Files')}</p>
              </ResizablePane>
              <ResizableHandle label={tr('تغییر اندازهٔ نوار کناری', 'Resize sidebar')} />
              <ResizablePane>
                <ResizablePanels orientation="vertical">
                  <ResizablePane defaultSize={70} minSize={30} style={{ padding: '.75rem' }}>
                    <p style={muted}>{tr('ویرایشگر', 'Editor')}</p>
                  </ResizablePane>
                  <ResizableHandle label={tr('تغییر اندازهٔ ترمینال', 'Resize terminal')} />
                  <ResizablePane minSize={12} collapsible style={{ background: 'var(--nx-surface-2)', padding: '.75rem' }}>
                    <p style={{ ...muted, fontFamily: 'var(--nx-font-mono)' }} dir="ltr">
                      $ npm test
                    </p>
                  </ResizablePane>
                </ResizablePanels>
              </ResizablePane>
            </ResizablePanels>
          </div>
        </Demo>

        <Demo title={tr('پیشرفت مطالعه', 'Scroll progress')}>
          <div id="sc-fx-scroll" tabIndex={0} aria-label={tr('مقاله', 'Article')} style={{ ...box, position: 'relative', blockSize: '16rem', overflow: 'auto' }}>
            <ScrollProgress container="#sc-fx-scroll" position="sticky" label={tr('پیشرفت مطالعه', 'Reading progress')} />
            <div style={{ display: 'grid', gap: '.75rem', padding: '1rem' }}>
              {Array.from({ length: 10 }, (_, i) => (
                <p key={i} style={{ margin: 0, lineHeight: 1.8 }}>
                  {tr(
                    'هر صفحه را با یک پرسش سنجیدیم: آیا کاربر در پنج ثانیه می‌فهمد این صفحه برای چیست؟',
                    'We judged every page by one question: does a user get what it is for within five seconds?',
                  )}
                </p>
              ))}
            </div>
            <div style={{ position: 'sticky', insetBlockEnd: 0, display: 'flex', justifyContent: 'flex-end', padding: '0 .75rem .75rem', pointerEvents: 'none' }}>
              <ScrollProgress variant="circle" position="static" container="#sc-fx-scroll" label={tr('بازگشت به بالا', 'Back to top')} style={{ pointerEvents: 'auto' }} />
            </div>
          </div>
        </Demo>

        <Demo title={tr('ارتفاع خودکار', 'Auto height')}>
          <div style={{ ...box, overflow: 'hidden' }}>
            <div className="sc-row" style={{ padding: '.5rem', borderBlockEnd: '1px solid var(--nx-border)' }}>
              <Button size="sm" variant={tab === 'post' ? 'secondary' : 'ghost'} onClick={() => setTab('post')}>
                {tr('پست', 'Post')}
              </Button>
              <Button size="sm" variant={tab === 'courier' ? 'secondary' : 'ghost'} onClick={() => setTab('courier')}>
                {tr('پیک', 'Courier')}
              </Button>
            </div>
            <AutoHeight>
              <div style={{ display: 'grid', gap: '.5rem', padding: '1rem' }}>
                <strong>{tab === 'post' ? tr('۳ تا ۵ روز کاری', '3–5 working days') : tr('امروز، ظرف ۳ ساعت', 'Today, within 3 hours')}</strong>
                {tab === 'courier' && (
                  <>
                    <p style={muted}>{tr('فقط داخل شهر. پیک پیش از رسیدن تماس می‌گیرد.', 'Inside the city only. The courier calls before arriving.')}</p>
                    <p style={muted}>{tr('در صورت تأخیر هزینه برمی‌گردد.', 'Refunded if late.')}</p>
                  </>
                )}
              </div>
            </AutoHeight>
          </div>
        </Demo>

        <Demo title={tr('جزیرهٔ پویا', 'Dynamic island')} center>
          <IslandDemo />
        </Demo>

        <Demo title={tr('تور راهنما', 'Spotlight tour')}>
          <div style={{ display: 'grid', gap: '.75rem' }}>
            <div className="sc-row">
              <Button id="sc-fx-tour-new" variant="primary" icon="plus" size="sm">
                {tr('پروژهٔ تازه', 'New project')}
              </Button>
              <Button id="sc-fx-tour-share" variant="secondary" icon="users" size="sm">
                {tr('اشتراک', 'Share')}
              </Button>
            </div>
            <Button variant="ghost" icon="star" onClick={() => setTourOpen(true)}>
              {tr('شروع تور', 'Take the tour')}
            </Button>
          </div>
          <SpotlightTour
            open={tourOpen}
            onOpenChange={setTourOpen}
            labels={{ next: tr('بعدی', 'Next'), back: tr('قبلی', 'Back'), skip: tr('رد کردن', 'Skip tour'), done: tr('تمام', 'Done'), step: (i, n) => tr(`گام ${i + 1} از ${n}`, `Step ${i + 1} of ${n}`) }}
            steps={[
              { target: '#sc-fx-tour-new', title: tr('از این‌جا شروع کنید', 'Start here'), body: tr('برای هر مشتری یک پروژه بسازید.', 'Create a project for each client.') },
              { target: '#sc-fx-tour-share', title: tr('تیم را بیاورید', 'Bring your team'), body: tr('با یک لینک دعوت کنید.', 'Invite people with a link.') },
              { title: tr('آماده‌اید', 'You are all set'), body: tr('این تور از منوی راهنما دوباره پخش می‌شود.', 'Replay it any time from Help.') },
            ]}
          />
        </Demo>

        <Demo title={tr('آشکار شدن تصویر', 'Image reveal')}>
          <RevealDemo />
        </Demo>
      </div>
    </Section>
  );
}
