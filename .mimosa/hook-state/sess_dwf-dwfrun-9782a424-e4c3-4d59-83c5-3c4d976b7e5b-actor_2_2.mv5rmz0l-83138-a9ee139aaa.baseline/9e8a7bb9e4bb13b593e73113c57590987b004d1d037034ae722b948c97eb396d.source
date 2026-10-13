import { useEffect, useRef, useState } from 'react';
import { Button, IconDraw, IconLoop, IconToggleState } from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useTr } from '../lang';

const card = {
  display: 'grid',
  gap: '0.9rem',
  justifyItems: 'center',
  padding: '1.4rem 1rem 1.2rem',
  border: '1px solid var(--nx-border)',
  borderRadius: 'var(--nx-radius-lg)',
  background: 'var(--nx-surface)',
  textAlign: 'center',
} as const;

const cardTitle = { margin: 0, fontSize: 'var(--nx-text-sm)', fontWeight: 700 } as const;
const cardMeta = { margin: 0, fontSize: 'var(--nx-text-xs)', color: 'var(--nx-text-muted)' } as const;

const grid = {
  display: 'grid',
  gap: '0.9rem',
  gridTemplateColumns: 'repeat(auto-fit, minmax(min(100%, 13rem), 1fr))',
} as const;

const gridWide = {
  display: 'grid',
  gap: '0.9rem',
  gridTemplateColumns: 'repeat(auto-fit, minmax(min(100%, 15rem), 1fr))',
} as const;

const stateChip = {
  display: 'inline-block',
  padding: '0.18rem 0.7rem',
  borderRadius: 'var(--nx-radius-full)',
  fontSize: 'var(--nx-text-xs)',
  fontWeight: 700,
  background: 'color-mix(in oklab, var(--nx-text) 7%, transparent)',
  color: 'var(--nx-text-muted)',
  transition: 'background-color var(--nx-dur-base) var(--nx-ease-out), color var(--nx-dur-base) var(--nx-ease-out)',
} as const;

const stateChipOn = {
  background: 'var(--nx-accent-soft)',
  color: 'var(--nx-accent-text)',
} as const;

const statusCard = {
  display: 'grid',
  gap: '0.8rem',
  alignContent: 'start',
  padding: '1.1rem',
  border: '1px solid var(--nx-border)',
  borderRadius: 'var(--nx-radius-lg)',
  background: 'var(--nx-surface)',
} as const;

const statusHead = { display: 'flex', alignItems: 'center', gap: '0.8rem' } as const;
const statusTitle = { margin: 0, fontSize: 'var(--nx-text-sm)', fontWeight: 700 } as const;
const statusDesc = { margin: '0.15rem 0 0', fontSize: 'var(--nx-text-xs)', lineHeight: 1.6, color: 'var(--nx-text-muted)' } as const;
const statusMeta = {
  margin: 0,
  paddingBlockStart: '0.65rem',
  borderBlockStart: '1px dashed var(--nx-border)',
  fontSize: 'var(--nx-text-xs)',
  color: 'var(--nx-text-muted)',
} as const;

/** The pen's own set: every stroke a separate path, drawn in order. */
function drawSet(tr: (fa: string, en: string) => string) {
  return [
    { id: 'heart', name: tr('قلب', 'Heart'), paths: ['M12 20S4.5 15.4 4.5 9.8A4.3 4.3 0 0 1 12 7a4.3 4.3 0 0 1 7.5 2.8C19.5 15.4 12 20 12 20z'] },
    { id: 'bell', name: tr('زنگ', 'Bell'), paths: ['M6.5 16.5V11a5.5 5.5 0 0 1 11 0v5.5l1.6 2.2H4.9z', 'M10 22.2a2.1 2.1 0 0 0 4 0'] },
    { id: 'search', name: tr('جست‌وجو', 'Search'), paths: ['M17.5 10.5a7 7 0 1 1-14 0 7 7 0 0 1 14 0z', 'M16.5 16.5 21 21'] },
    { id: 'shield', name: tr('سپر', 'Shield'), paths: ['M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z', 'm9 12 2 2 4-4.5'] },
    { id: 'sun', name: tr('خورشید', 'Sun'), paths: ['M12 16a4 4 0 1 0 0-8 4 4 0 0 0 0 8z', 'M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4'] },
    { id: 'moon', name: tr('ماه', 'Moon'), paths: ['M20.5 14.5A8.5 8.5 0 0 1 9.5 3.5a8.5 8.5 0 1 0 11 11z'] },
    { id: 'message', name: tr('پیام', 'Message'), paths: ['M21 15a2 2 0 0 1-2 2H8l-5 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z', 'M8 9.5h8', 'M8 13.5h5'] },
    { id: 'zap', name: tr('برق', 'Zap'), paths: ['M13 2 4 14h7l-1 8 10-12h-7z'] },
  ];
}

function DrawDemo() {
  const tr = useTr();
  const [run, setRun] = useState(0);
  return (
    <div style={{ display: 'grid', gap: '1rem' }}>
      <IconDraw key={run} icons={drawSet(tr)} replayOnHover itemStagger={60} stagger={130} aria-label={tr('مجموعه آیکون‌ها', 'The icon set')} />
      <div className="sc-row">
        <Button size="sm" variant="secondary" icon="sparkles" onClick={() => setRun((n) => n + 1)}>
          {tr('همه را دوباره بکش', 'Draw them all again')}
        </Button>
      </div>
    </div>
  );
}

function ToggleDemo() {
  const tr = useTr();
  const [liked, setLiked] = useState(false);
  const [likes, setLikes] = useState(2481);
  const [alerts, setAlerts] = useState(false);
  const [alertCount, setAlertCount] = useState(2);
  const [playing, setPlaying] = useState(false);
  const [saved, setSaved] = useState(false);

  const chip = (on: boolean) => (
    <span style={{ ...stateChip, ...(on ? stateChipOn : null) }}>{on ? tr('فعال', 'active') : tr('خاموش', 'idle')}</span>
  );

  return (
    <div style={grid}>
      <article style={card}>
        <IconToggleState
          variant="heart"
          pressed={liked}
          onPressedChange={(next) => {
            setLiked(next);
            if (next) setLikes((n) => n + 1);
          }}
          label={tr('پسند', 'Like')}
          pressedLabel={tr('پسندیدی', 'Liked')}
        />
        <h4 style={cardTitle}>{tr('سیگنال · پست رونمایی', 'Signal · launch post')}</h4>
        <p style={cardMeta}>
          {tr(`${likes} نفر این را پسندیدند`, `${likes} people liked this`)}
        </p>
        {chip(liked)}
      </article>

      <article style={card}>
        <IconToggleState
          variant="bell"
          pressed={alerts}
          onPressedChange={(next) => {
            setAlerts(next);
            if (next) setAlertCount((n) => n + 1);
          }}
          label={tr('هشدار قیمت', 'Price alert')}
          pressedLabel={tr('هشدار روشن است', 'Alerts on')}
          count={alertCount}
        />
        <h4 style={cardTitle}>{tr('مریدین · زیر نظر داشتن EUR/USD', 'Meridian · EUR/USD watch')}</h4>
        <p style={cardMeta}>
          {tr(`${alertCount} هشدار امروز`, `${alertCount} alerts today`)}
        </p>
        {chip(alerts)}
      </article>

      <article style={card}>
        <IconToggleState variant="play" pressed={playing} onPressedChange={setPlaying} label={tr('پخش ویدیو', 'Play video')} pressedLabel={tr('در حال پخش', 'Playing')} />
        <h4 style={cardTitle}>{tr('آموزش · فصل ۱', 'Onboarding · chapter 1')}</h4>
        <p style={cardMeta}>{tr('۴:۱۲ · تور محصول', '4:12 · product tour')}</p>
        {chip(playing)}
      </article>

      <article style={card}>
        <IconToggleState variant="bookmark" pressed={saved} onPressedChange={setSaved} label={tr('ذخیره برای بعد', 'Read later')} pressedLabel={tr('ذخیره شد', 'Saved')} />
        <h4 style={cardTitle}>{tr('یادداشت‌های میدانی · گزارش Q4', 'Field notes · Q4 report')}</h4>
        <p style={cardMeta}>{tr('به فهرست خواندنت می‌رود', 'Goes to your reading list')}</p>
        {chip(saved)}
      </article>
    </div>
  );
}

/** The upload card: the arrow's fill is the progress bar, then it rests and starts over. */
function UploadDemo() {
  const tr = useTr();
  const [progress, setProgress] = useState(0);
  const timer = useRef(0);
  useEffect(() => {
    timer.current = window.setInterval(() => {
      setProgress((p) => (p < 100 ? Math.min(100, p + 2) : 0));
    }, 90);
    return () => window.clearInterval(timer.current);
  }, []);
  return (
    <article style={statusCard}>
      <div style={statusHead}>
        <IconLoop kind="upload" progress={progress} label={tr('در حال بارگذاری', 'Uploading')} />
        <div>
          <h4 style={statusTitle}>{tr('بارگذاری نسخهٔ برش', 'Uploading release cut')}</h4>
          <p style={statusDesc}>q4-report.mp4 · 12.4 MB</p>
        </div>
      </div>
      <p style={statusMeta}>{tr('پرشدن فلش، خودِ نوار پیشرفت است', 'the arrow fill is the progress bar')}</p>
    </article>
  );
}

/** The live search: the magnifier pulses while the field is filtering. */
function SearchDemo() {
  const tr = useTr();
  const [busy, setBusy] = useState(false);
  const timer = useRef(0);
  useEffect(() => () => window.clearTimeout(timer.current), []);
  const onInput = () => {
    setBusy(true);
    window.clearTimeout(timer.current);
    timer.current = window.setTimeout(() => setBusy(false), 900);
  };
  return (
    <article style={{ ...statusCard, borderInline: 0 }}>
      <label
        style={{
          display: 'flex',
          alignItems: 'center',
          gap: '0.6rem',
          minWidth: 0,
          padding: '0.45rem 0.7rem',
          border: '1px solid var(--nx-border)',
          borderRadius: 'var(--nx-radius-md)',
          background: 'var(--nx-surface-2)',
          transition: 'border-color var(--nx-dur-fast) var(--nx-ease-out)',
        }}
      >
        <IconLoop kind="search" busy={busy} label={busy ? tr('در حال فیلتر', 'filtering') : tr('جست‌وجو', 'search')} />
        <input
          type="search"
          placeholder={tr('جست‌وجو در اسناد تیم…', 'Search the team docs…')}
          aria-label={tr('جست‌وجو در اسناد', 'Search docs')}
          onInput={onInput}
          style={{ flex: 1, minWidth: 0, border: 'none', outline: 'none', background: 'transparent', font: 'inherit', fontSize: 'var(--nx-text-sm)', color: 'inherit' }}
        />
        <Button size="sm" variant="ghost" disabled>
          <IconLoop kind="arc" bare label={tr('در حال ذخیره', 'Saving')} />
          {tr('در حال ذخیره…', 'Saving…')}
        </Button>
      </label>
      <p style={statusMeta}>
        {busy ? tr('در حال فیلتر ۲۴۸ سند…', 'filtering 248 documents…') : tr('۲۴۸ سند در این فضا', '248 documents in this space')}
      </p>
    </article>
  );
}

function LoopDemo() {
  const tr = useTr();
  return (
    <div style={gridWide}>
      <article style={statusCard}>
        <div style={statusHead}>
          <IconLoop kind="dots" label={tr('در حال پردازش', 'processing')} />
          <div>
            <h4 style={statusTitle}>{tr('پردازش پرداخت', 'Processing payout')}</h4>
            <p style={statusDesc}>{tr('اطلس پی · بستن ۳ رکورد دفتر', 'Atlas Pay · closing 3 ledger entries')}</p>
          </div>
        </div>
        <p style={statusMeta}>{tr('در صف · ۲ ثانیه پیش', 'queued · 2s ago')}</p>
      </article>

      <article style={statusCard}>
        <div style={statusHead}>
          <IconLoop kind="arc" label={tr('در حال بارگذاری', 'loading')} />
          <div>
            <h4 style={statusTitle}>{tr('بارگذاری داشبورد', 'Loading dashboard')}</h4>
            <p style={statusDesc}>{tr('تحلیل مریدین · ۶ ماژول', 'Meridian analytics · 6 modules')}</p>
          </div>
        </div>
        <p style={statusMeta}>{tr('قطعه‌قطعه می‌آید · ۰۸:۴۱', 'streaming chunks · 08:41')}</p>
      </article>

      <article style={statusCard}>
        <div style={statusHead}>
          <IconLoop kind="sync" label={tr('در حال همگام‌سازی', 'syncing')} />
          <div>
            <h4 style={statusTitle}>{tr('همگام‌سازی ورک‌اسپیس', 'Syncing workspace')}</h4>
            <p style={statusDesc}>{tr('خوشهٔ فرانکفورت · ۲۱۴ فایل', 'Frankfurt cluster · 214 files')}</p>
          </div>
        </div>
        <p style={statusMeta}>{tr('آخرین همگام‌سازی: همین حالا', 'last sync: just now')}</p>
      </article>

      <article style={statusCard}>
        <div style={statusHead}>
          <IconLoop kind="download" label={tr('در حال دانلود', 'downloading')} />
          <div>
            <h4 style={statusTitle}>{tr('دریافت بستهٔ نصب', 'Fetching installer')}</h4>
            <p style={statusDesc}>Vertex Studio 2026.10 · 42 MB</p>
          </div>
        </div>
        <p style={statusMeta}>{tr('آینه: دوبلین · ۰۹:۱۲', 'mirror: Dublin · 09:12')}</p>
      </article>

      <UploadDemo />

      <div style={{ gridColumn: '1 / -1' }}>
        <SearchDemo />
      </div>
    </div>
  );
}

export function IconMotionBlocks() {
  const tr = useTr();
  return (
    <Section
      id="icon-motion-blocks"
      eyebrow={tr('آیکون‌های متحرک', 'Animated icons')}
      title={tr('آیکون‌هایی که خودشان را می‌کشند و کلیکشان را می‌ارزند', 'Icons that ink themselves in — and earn their click')}
      description={tr(
        'ست آیکون خطی که با رسیدن به دید، خط‌به‌خط خودش را می‌کشد؛ دکمه‌هایی که آیکونشان بین دو حالت مورف می‌شود — قلب با انفجار ذره‌ها پر می‌شود، زنگ می‌لرزد و بجش می‌شمارد، پخش و توقف در پنجرهٔ لغزان — و اسپینرهای حلقه‌ای برای دکمه و کارت وضعیت که بیرون از دید می‌ایستند.',
        'A set of line icons that draw themselves stroke by stroke as they reach view; buttons whose icons morph between two states — a heart that fills with a particle burst, a bell that swings while its badge counts, play and pause gliding through a sliding window — and looping spinners for buttons and status cards that park off screen.',
      )}
      code={{
        react: `<IconDraw icons={[{ name: 'Heart', paths: [heart] }]} replayOnHover />
<IconToggleState variant="heart" pressed={liked} onPressedChange={setLiked}
  label="Like" pressedLabel="Liked" count={likes} />
<IconLoop kind="upload" progress={progress} label="Uploading" />
<IconLoop kind="arc" bare label="Saving" />`,
        blade: `{{-- shared core CSS + the framework-agnostic behaviour --}}
<ul class="nx-icon-draw">
  <li class="nx-icon-draw-tile">
    <svg viewBox="0 0 24 24" aria-hidden="true">
      <path class="nx-icon-draw-ink" pathLength="1" d="…"/>
    </svg>
    <span class="nx-icon-draw-name">Heart</span>
  </li>
</ul>
<button class="nx-icon-toggle" type="button" data-variant="heart" aria-pressed="false">…</button>
<span class="nx-icon-loop" role="img" aria-label="Uploading" data-kind="upload" style="--_p:42%">…</span>`,
      }}
    >
      <Demo title={tr('ورود قلم‌به‌قلم', 'The pen enters stroke by stroke')} wide>
        <DrawDemo />
      </Demo>

      <Demo title={tr('مورف حالت روی دکمه‌های واقعی', 'State morphs on real buttons')} wide>
        <ToggleDemo />
      </Demo>

      <Demo title={tr('حلقه‌ها در کنترل‌های در حال کار', 'Loops inside working controls')} wide>
        <LoopDemo />
      </Demo>
    </Section>
  );
}
