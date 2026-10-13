import { type CSSProperties, useEffect, useState } from 'react';
import { CurtainReveal, SplitFlap, StaggerList } from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useTr } from '../lang';

/* ---- Curtain demo: the launch page of a design studio ---------------------------- */

const navStyle: CSSProperties = {
  display: 'flex',
  alignItems: 'center',
  gap: 'var(--nx-space-4)',
  fontSize: 'var(--nx-text-sm)',
  fontWeight: 650,
  color: 'var(--nx-text-muted)',
};

const eyebrowStyle: CSSProperties = {
  margin: 0,
  fontSize: 'var(--nx-text-xs)',
  fontWeight: 700,
  letterSpacing: 'var(--nx-tracking-wider)',
  textTransform: 'uppercase',
  color: 'var(--nx-accent-text)',
};

const headStyle: CSSProperties = {
  margin: 0,
  maxInlineSize: '24ch',
  fontSize: 'clamp(1.5rem, 4.5vw, 2.4rem)',
  lineHeight: 1.25,
  fontWeight: 800,
  letterSpacing: 'var(--nx-tracking-tight)',
};

const subStyle: CSSProperties = {
  margin: 0,
  maxInlineSize: '42ch',
  fontSize: 'var(--nx-text-sm)',
  lineHeight: 1.8,
  color: 'var(--nx-text-muted)',
};

const ctaStyle: CSSProperties = {
  display: 'flex',
  flexWrap: 'wrap',
  gap: 'var(--nx-space-2)',
};

const buttonStyle = (solid: boolean): CSSProperties => ({
  padding: '0.6rem 1.15rem',
  border: '1px solid ' + (solid ? 'transparent' : 'var(--nx-border)'),
  borderRadius: 'var(--nx-radius-full)',
  background: solid ? 'var(--nx-text)' : 'transparent',
  color: solid ? 'var(--nx-bg)' : 'inherit',
  font: '700 var(--nx-text-sm) / 1 var(--nx-font-sans)',
  cursor: 'pointer',
});

const chipsStyle: CSSProperties = {
  display: 'flex',
  flexWrap: 'wrap',
  gap: 'var(--nx-space-2)',
};

const chipStyle: CSSProperties = {
  display: 'inline-flex',
  alignItems: 'baseline',
  gap: 'var(--nx-space-1)',
  padding: '0.45rem 0.85rem',
  border: '1px solid var(--nx-border)',
  borderRadius: 'var(--nx-radius-md)',
  background: 'var(--nx-surface-2)',
  fontSize: 'var(--nx-text-xs)',
  color: 'var(--nx-text-muted)',
};

function CurtainDemo() {
  const tr = useTr();
  return (
    <CurtainReveal
      label="NORTHLIGHT"
      duration={2400}
      replayLabel={tr('دوباره اجرا کن', 'Replay the overture')}
      stageLabel={tr('صفحهٔ معرفی پشت پردهٔ دوپنلی', 'Launch page behind a two-panel curtain')}
      items={[
        <nav style={navStyle} key="nav">
          <strong style={{ letterSpacing: 'var(--nx-tracking-wider)' }}>NORTHLIGHT</strong>
          <span>{tr('کارها', 'Work')}</span>
          <span>{tr('استودیو', 'Studio')}</span>
          <span>{tr('بلاگ', 'Journal')}</span>
        </nav>,
        <p style={eyebrowStyle} key="eyebrow">
          {tr('نورث‌لایت ۳٫۰ — بتای عمومی', 'Northlight 3.0 — public beta')}
        </p>,
        <h3 style={headStyle} key="head">
          {tr('پرده برای رابط بعدی شما بالا می‌رود', 'The curtain rises on your next interface')}
        </h3>,
        <p style={subStyle} key="sub">
          {tr(
            'ابزارهای طراحی نور و سایه برای تیم‌های محصول — از ایدهٔ اول تا دموی کارا، در یک نشست.',
            'Light-and-shadow design tools for product teams — from first idea to a working demo in one sitting.',
          )}
        </p>,
        <div style={ctaStyle} key="cta">
          <button type="button" style={buttonStyle(true)}>
            {tr('شروع ساخت', 'Start building')}
          </button>
          <button type="button" style={buttonStyle(false)}>
            {tr('تماشای ریل', 'Watch the reel')}
          </button>
        </div>,
        <div style={chipsStyle} key="chips">
          <span style={chipStyle}>
            <b>120+</b> {tr('قالب آماده', 'ready templates')}
          </span>
          <span style={chipStyle}>
            <b>40ms</b> {tr('پاسخ‌دهی تعامل', 'interactions')}
          </span>
          <span style={chipStyle}>
            <b>4.9/5</b> {tr('امتیاز تیم‌ها', 'team rating')}
          </span>
        </div>,
      ]}
    />
  );
}

/* ---- Stagger demo: release notes --------------------------------------------------- */

function StaggerDemo() {
  const tr = useTr();
  const items = [
    {
      id: 'cursors',
      icon: 'users' as const,
      title: tr('نشانگرهای لحظه‌ای', 'Realtime cursors'),
      note: tr('نشانگر، نام و انتخاب هر هم‌تیمی را زنده و با ۶۰ فریم ببینید.', 'See every teammate’s pointer, name and selection — live, at 60fps.'),
      tag: tr('تازه', 'New'),
    },
    {
      id: 'drafts',
      icon: 'file' as const,
      title: tr('پیش‌نویس آفلاین', 'Offline drafts'),
      note: tr('در پرواز هم بنویسید؛ فرود که آمدید، ویرایش‌ها تمیز ادغام می‌شوند.', 'Keep writing on the plane; edits merge cleanly when you land.'),
      tag: tr('بهبود', 'Improved'),
    },
    {
      id: 'palette',
      icon: 'command' as const,
      title: tr('پالت فرمان ۲', 'Command palette v2'),
      note: tr('جست‌وجوی فازی در اکشن‌ها، صفحه‌ها و فایل‌های اخیر — زیر ۴۰ms.', 'Fuzzy search across actions, pages and recent files — under 40ms.'),
      tag: tr('تازه', 'New'),
    },
    {
      id: 'timeline',
      icon: 'layers' as const,
      title: tr('خط زمان نسخه‌ها', 'Version timeline'),
      note: tr('هر فریم را در تاریخش بکشید و یک بازه را بازگردانید.', 'Scrub any frame back through its history and restore a range.'),
      tag: tr('بهبود', 'Improved'),
    },
    {
      id: 'focus',
      icon: 'zap' as const,
      title: tr('حالت تمرکز', 'Focus mode'),
      note: tr('با یک کلیک همهٔ پنل‌ها قایم و اعلان‌ها ۵۰ دقیقه ساکت می‌شوند.', 'One click hides every panel and mutes notifications for 50 minutes.'),
      tag: tr('تازه', 'New'),
    },
    {
      id: 'shared',
      icon: 'grid' as const,
      title: tr('کامپوننت‌های مشترک', 'Shared components'),
      note: tr('ویرایش کتابخانه حالا در چند ثانیه به بیش از ۱۲۰ قالب می‌رسد.', 'Library edits now propagate to all 120+ templates in seconds.'),
      tag: tr('بهبود', 'Improved'),
    },
    {
      id: 'audit',
      icon: 'shield' as const,
      title: tr('خروجی لاگ ممیزی', 'Audit log export'),
      note: tr('خروجی CSV سازگار با SOC-2 از همهٔ رویدادهای فضای کاری.', 'SOC-2 friendly CSV export of every workspace event.'),
      tag: tr('رفع اشکال', 'Fixed'),
    },
    {
      id: 'theme',
      icon: 'sun' as const,
      title: tr('همگام‌سازی پوسته', 'Theme sync'),
      note: tr('پوستهٔ روشن و تیره دوباره سیستم را دنبال می‌کنند، بدون پرش.', 'Light and dark themes follow the OS again, frame-accurate.'),
      tag: tr('رفع اشکال', 'Fixed'),
    },
  ];
  return (
    <StaggerList
      step={80}
      aria-label={tr('یادداشت‌های انتشار مریدین ۲٫۴', 'Meridian 2.4 release notes')}
      replayLabel={tr('موج را دوباره بفرست', 'Send the wave again')}
      items={items}
    />
  );
}

/* ---- Split-flap demo: the departure board ------------------------------------------ */

function FlapDemo() {
  const tr = useTr();
  const messages = ['BON VOYAGE', 'GATE 22', 'MILAN 7:40', 'NEXT 08:15'];
  const [index, setIndex] = useState(0);

  // The board re-spells itself every few seconds, like a real departures hall.
  useEffect(() => {
    const timer = window.setInterval(() => setIndex((current) => (current + 1) % messages.length), 4200);
    return () => window.clearInterval(timer);
  }, []);

  const presetStyle: CSSProperties = {
    padding: '0.4rem 0.85rem',
    border: '1px solid var(--nx-border)',
    borderRadius: 'var(--nx-radius-full)',
    background: 'var(--nx-surface)',
    font: '700 var(--nx-text-xs) / 1 var(--nx-font-mono)',
    letterSpacing: 'var(--nx-tracking-wide)',
    cursor: 'pointer',
    transitionProperty: 'border-color, color, scale',
    transitionDuration: 'var(--nx-dur-fast), var(--nx-dur-fast), var(--nx-spring-snappy-duration)',
    transitionTimingFunction: 'var(--nx-ease-out), var(--nx-ease-out), var(--nx-spring-snappy)',
  };

  return (
    <div style={{ display: 'grid', gap: 'var(--nx-space-4)', justifyItems: 'start' }}>
      <SplitFlap value={messages[index]} cells={10} brand="ATLAS RAIL" tag="DEPARTURES" label={tr('تابلوی حرکت', 'Departure board')} />
      <div style={{ display: 'flex', flexWrap: 'wrap', gap: 'var(--nx-space-2)' }} role="group" aria-label={tr('پیام‌های تابلو', 'Board messages')}>
        {messages.map((message, i) => (
          <button
            key={message}
            type="button"
            style={{ ...presetStyle, borderColor: i === index ? 'var(--nx-accent-border)' : undefined, color: i === index ? 'var(--nx-accent-text)' : undefined }}
            aria-pressed={i === index}
            onClick={() => setIndex(i)}
          >
            {message}
          </button>
        ))}
      </div>
    </div>
  );
}

/* ---- The section --------------------------------------------------------------------- */

export function EntrancesBlocks() {
  const tr = useTr();
  return (
    <Section
      id="entrances-blocks"
      eyebrow={tr('ورودها', 'Entrances')}
      title={tr('صفحه از راه می‌رسد', 'The page arrives')}
      description={tr(
        'سه ورود برای اولین لحظه‌های صفحه: پردهٔ دوپنلی با شمارندهٔ درصد که در صد باز می‌شود و محتوا را پله‌پله وارد می‌کند؛ فهرستی که آیتم‌هایش با فنر و فاصلهٔ قابل‌تنظیم موج‌وار می‌رسند؛ و تابلوی مکانیکی حروف نیم‌کره که متن تازه را برگ‌به‌برگ هجی می‌کند.',
        'Three entrances for a page’s first moments: a two-panel curtain with a percentage counter that opens at one hundred and waves the content in; a list whose items arrive in a spring-loaded, retunable wave; and a mechanical split-flap board that spells a new message leaf by leaf.',
      )}
      code={{
        react: `<CurtainReveal label="NORTHLIGHT" duration={2400} items={[<nav>…</nav>, <h3>…</h3>]} />
<StaggerList step={80} items={releaseNotes} />
<SplitFlap value="BON VOYAGE" cells={10} brand="ATLAS RAIL" />`,
        blade: `{{-- shared core CSS + the framework-agnostic behaviours --}}
<figure class="nx-curtain">{{ curtainReveal($el, ['duration' => 2400]) }}
  <div class="nx-curtain-stage" data-open="…">
    <div class="nx-curtain-item" style="--nx-i: 0">…</div>
  </div>
</figure>
<div class="nx-stagger">{{ staggerList($el, ['step' => 80]) }}
  <ul class="nx-stagger-list"><li class="nx-stagger-item">…</li></ul>
</div>
<div class="nx-split-flap" dir="ltr">{{ splitFlap($el, ['value' => 'BON VOYAGE']) }}</div>`,
      }}
    >
      <Demo title={tr('پردهٔ افتتاحیه با شمارنده', 'The overture behind two curtains')} wide>
        <CurtainDemo />
      </Demo>

      <Demo title={tr('موج یادداشت‌های انتشار', 'Release notes in waves')} wide>
        <StaggerDemo />
      </Demo>

      <Demo title={tr('تابلوی حرکت ایستگاه', 'The station departure board')} wide>
        <FlapDemo />
      </Demo>
    </Section>
  );
}
