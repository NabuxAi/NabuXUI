import { type CSSProperties, useState } from 'react';
import { ConfettiButton, DragSpring, LiveTicker, ReactionBar, RippleSurface } from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useTr } from '../lang';

/* ---- Confetti demo: a beta signup that celebrates ---------------------------------- */

const signupCard: CSSProperties = {
  display: 'grid',
  gap: 'var(--nx-space-3)',
  inlineSize: 'min(100%, 22rem)',
  padding: 'var(--nx-space-6)',
  border: '1px solid var(--nx-border)',
  borderRadius: 'var(--nx-radius-xl)',
  background: 'var(--nx-surface)',
  boxShadow: 'var(--nx-shadow-md)',
};

const signupInput: CSSProperties = {
  minBlockSize: '2.75rem',
  paddingInline: 'var(--nx-space-3)',
  border: '1px solid var(--nx-border)',
  borderRadius: 'var(--nx-radius-md)',
  background: 'var(--nx-surface-2)',
  color: 'var(--nx-text)',
  font: 'inherit',
  boxSizing: 'border-box',
  inlineSize: '100%',
};

function ConfettiDemo() {
  const tr = useTr();
  const [done, setDone] = useState(false);
  return (
    <div style={signupCard} aria-label={tr('عضویت در بتای فیلدنوتس', 'Fieldnotes beta signup')}>
      <span
        style={{
          fontWeight: 700,
          fontSize: 'var(--nx-text-xs)',
          letterSpacing: 'var(--nx-tracking-wider)',
          textTransform: 'uppercase',
          color: 'var(--nx-accent-text)',
        }}
      >
        Fieldnotes · {tr('بتا', 'Beta')}
      </span>
      <label style={{ display: 'grid', gap: 'var(--nx-space-1)', fontSize: 'var(--nx-text-sm)', fontWeight: 600, color: 'var(--nx-text-muted)' }} htmlFor="micro-fx-mail">
        {tr('ایمیل کاری', 'Work email')}
      </label>
      <input id="micro-fx-mail" type="email" placeholder="you@studio.com" style={signupInput} autoComplete="email" inputMode="email" />
      <ConfettiButton
        label={tr('عضویت در بتا', 'Join the beta')}
        doneLabel={tr('در فهرست هستید', 'You’re on the list')}
        icon="mail"
        doneIcon="check-circle"
        count={140}
        done={done}
        onDoneChange={setDone}
      />
      <p style={{ margin: 0, fontSize: 'var(--nx-text-xs)', color: 'var(--nx-text-muted)' }}>
        {done
          ? tr('نسخهٔ ۰٫۹ جمعه به صندوق شما می‌آید — همراه ۲٬۴۰۰ خوانندهٔ اولیهٔ دیگر.', 'Build 0.9 lands in your inbox on Friday — with 2,400 other early readers.')
          : tr('فقط یادداشت انتشار — بدون تبلیغ. دوباره فشار دهید تا جشن تکرار شود.', 'Release notes only — no marketing. Press again to celebrate once more.')}
      </p>
    </div>
  );
}

/* ---- Ripple demo: a changelog card where every surface waves ------------------------ */

const noteCard: CSSProperties = {
  display: 'grid',
  gap: 'var(--nx-space-4)',
  inlineSize: 'min(100%, 28rem)',
  padding: 'var(--nx-space-6)',
  border: '1px solid var(--nx-border)',
  borderRadius: 'var(--nx-radius-xl)',
  background: 'var(--nx-surface)',
  boxShadow: 'var(--nx-shadow-md)',
  cursor: 'pointer',
};

const noteTabs: CSSProperties = {
  display: 'inline-flex',
  gap: 'var(--nx-space-1)',
  padding: 'var(--nx-space-1)',
  border: '1px solid var(--nx-border)',
  borderRadius: 'var(--nx-radius-full)',
  background: 'var(--nx-surface-2)',
  justifySelf: 'start',
};

const notePanel: CSSProperties = {
  margin: 0,
  minBlockSize: '3.4rem',
  fontSize: 'var(--nx-text-sm)',
  lineHeight: 1.8,
  color: 'var(--nx-text-muted)',
};

const noteActions: CSSProperties = {
  display: 'flex',
  flexWrap: 'wrap',
  gap: 'var(--nx-space-2)',
  justifyContent: 'space-between',
  alignItems: 'center',
};

function RippleDemo() {
  const tr = useTr();
  const [tab, setTab] = useState<'new' | 'speed' | 'fixes'>('new');
  const [reaction, setReaction] = useState<string | null>(null);
  const tabs = [
    { id: 'new', label: tr('تازه‌ها', 'What’s new') },
    { id: 'speed', label: tr('سرعت', 'Speed') },
    { id: 'fixes', label: tr('رفع‌اشکال', 'Fixes') },
  ] as const;
  const panels = {
    new: tr('نوت‌های همزمان، جست‌وجو در کل انبار، و اشتراک‌گذاری یک‌کلکی برای کل تیم.', 'Real-time notes, search across the whole vault, and one-click sharing for the whole team.'),
    speed: tr('باز کردن دفترچه ۴۰٪ سریع‌تر؛ جست‌وجو زیر ۵۰ میلی‌ثانیه حتی روی بزرگ‌ترین انبارها.', 'Notebooks open 40% faster; search answers under 50ms even on the largest vaults.'),
    fixes: tr('۲۷ گزارش کاربر بسته شد — از همگام‌سازی دوبارهٔ برچسب‌ها تا چرخش صفحه روی تبلت.', 'Twenty-seven user reports closed — from tags syncing twice to the page rotating on tablets.'),
  };
  return (
    <RippleSurface as="div" variant="plain" style={noteCard} aria-label={tr('کارت یادداشت انتشار نرم‌افزار فیلدنوتس', 'Fieldnotes release card')}>
      <div style={{ display: 'flex', alignItems: 'baseline', gap: 'var(--nx-space-3)', flexWrap: 'wrap' }}>
        <strong style={{ fontSize: 'var(--nx-text-md)' }}>Fieldnotes 3.2</strong>
        <span style={{ marginInlineStart: 'auto', fontVariantNumeric: 'tabular-nums', fontWeight: 700, color: 'var(--nx-accent-text)' }}>2,148 {tr('نصب فعال', 'active installs')}</span>
      </div>

      <div style={noteTabs} role="tablist" aria-label={tr('بخش‌های یادداشت انتشار', 'Release sections')}>
        {tabs.map((entry) => (
          <RippleSurface
            key={entry.id}
            as="button"
            variant="plain"
            role="tab"
            aria-selected={tab === entry.id}
            onClick={() => setTab(entry.id)}
            style={{
              padding: '0.5rem 1rem',
              borderRadius: 'var(--nx-radius-full)',
              border: 'none',
              font: 'inherit',
              fontSize: 'var(--nx-text-sm)',
              fontWeight: 600,
              cursor: 'pointer',
              color: tab === entry.id ? 'var(--nx-accent-contrast)' : 'var(--nx-text-muted)',
              background: tab === entry.id ? 'var(--nx-accent)' : 'transparent',
            }}
          >
            {entry.label}
          </RippleSurface>
        ))}
      </div>

      <p style={notePanel}>{panels[tab]}</p>

      <div style={noteActions}>
        <ReactionBar
          value={reaction}
          onValueChange={setReaction}
          items={[
            { id: 'love', label: tr('عشق', 'Love it'), icon: 'heart', count: 128, tint: 'var(--nx-danger)' },
            { id: 'useful', label: tr('مفید', 'Useful'), icon: 'star', count: 96, tint: 'var(--nx-gold)' },
            { id: 'wow', label: tr('الهام‌بخش', 'Wow'), icon: 'sparkles', count: 41, tint: 'var(--nx-accent)' },
            { id: 'fast', label: tr('برق‌آسا', 'Lightning'), icon: 'zap', count: 203, tint: 'var(--nx-warning)' },
          ]}
        />
        <RippleSurface as="button" variant="outline">
          {tr('دریافت نسخهٔ ۳٫۲', 'Get 3.2')}
        </RippleSurface>
      </div>
    </RippleSurface>
  );
}

/* ---- Drag-spring demo: onboarding a skill ------------------------------------------- */

function DragDemo() {
  const tr = useTr();
  const [held, setHeld] = useState(false);
  return (
    <div style={{ display: 'grid', gap: 'var(--nx-space-2)', justifyItems: 'start', inlineSize: '100%' }}>
      <DragSpring
        held={held}
        onHeldChange={setHeld}
        chipIcon="edit"
        homeLabel={tr('سینی مهارت‌ها', 'Skills tray')}
        targetLabel={tr('مهارت‌های اصلی', 'Core skills')}
        targetNote={tr('اینجا رها کنید', 'drop here')}
        hint={tr('کارت «طراحی رابط» را بکشید و روی خانهٔ مهارت‌های اصلی رها کنید — یا با Tab و Enter منتسب کنید.', 'Drag the “Interface design” card onto the core-skills slot — or Tab to it and press Enter.')}
        heldNote={tr('«طراحی رابط» به مهارت‌های اصلی پروفایل شما اضافه شد.', '“Interface design” is now on your profile’s core skills.')}
        style={{ inlineSize: '100%' }}
      >
        {tr('طراحی رابط', 'Interface design')}
      </DragSpring>
      {held && (
        <button
          type="button"
          onClick={() => setHeld(false)}
          style={{
            justifySelf: 'start',
            display: 'inline-flex',
            alignItems: 'center',
            gap: 'var(--nx-space-2)',
            padding: 'var(--nx-space-2) var(--nx-space-4)',
            border: '1px solid var(--nx-border)',
            borderRadius: 'var(--nx-radius-full)',
            background: 'var(--nx-surface)',
            color: 'var(--nx-text)',
            font: '600 var(--nx-text-sm) / 1 var(--nx-font-sans)',
            cursor: 'pointer',
          }}
        >
          {tr('بازگشت به سینی', 'Back to the tray')}
        </button>
      )}
    </div>
  );
}

/* ---- Live-ticker demo: the Meridian watchlist ---------------------------------------- */

function TickerDemo() {
  const tr = useTr();
  return (
    <LiveTicker
      aria-label={tr('دیده‌بان مریدین — قیمت زندهٔ پنج دارایی خیالی', 'Meridian watchlist — live prices of five fictional assets')}
      interval={1600}
      step={0.003}
      quotes={[
        { id: 'aur', symbol: 'AUR', name: tr('آئوروم', 'Aurum'), value: 1284.52 },
        { id: 'hel', symbol: 'HEL', name: tr('هلیوس', 'Helios'), value: 87.19 },
        { id: 'tid', symbol: 'TID', name: tr('تایدل', 'Tidal'), value: 12.84, precision: 4 },
        { id: 'vel', symbol: 'VEL', name: tr('ولا', 'Vela'), value: 341.07 },
        { id: 'cas', symbol: 'CAS', name: tr('کاسکید', 'Cascade'), value: 56.4 },
      ]}
    />
  );
}

/* ---- The section ---------------------------------------------------------------------- */

export function MicroFxBlocks() {
  const tr = useTr();
  return (
    <Section
      id="micro-fx-blocks"
      eyebrow={tr('ریزتعامل‌ها', 'Micro FX')}
      title={tr('لحظه‌هایی که کار را تمام می‌کنند', 'The moments that finish the job')}
      description={tr(
        'پنج ریزتعامل که به دست پاسخ می‌دهند: دکمهٔ موفقیت با انفجار کانفتی روی بوم دوبعدی و رنگ‌های تم؛ موج کلیک از نقطهٔ دقیق فشار روی هر سطحی؛ کارت کشیدنی روی یک فنر اویلر که یا در خانهٔ رهاسازی می‌نشیند یا فنری برمی‌گردد؛ ری‌اکشن‌هایی با پارتیکل رنگی و شمارندهٔ رقمیِ غلتان؛ و نوار قیمت زنده که هر رقمش در ستون خودش می‌غلتد و فلش رنگی جهت نشست را می‌گوید — همه در RTL و LTR درست.',
        'Five micro-interactions that answer the hand: a success button with a canvas confetti burst in the theme’s own colours; a click wave from the exact point of the press on any surface; a draggable card on one Euler spring that settles onto its slot or springs home; reactions with a tinted particle burst and rolling digit counters; and a live price strip where every digit rolls in its own column while a coloured arrow carries the session sign — all correct in RTL and LTR.',
      )}
      code={{
        react: `<ConfettiButton label="Join the beta" doneLabel="You’re on the list" count={140} />
<RippleSurface as="div" variant="plain"> {{/* the card */}}
  <RippleSurface as="button" variant="outline">Get 3.2</RippleSurface>
</RippleSurface>
<DragSpring homeLabel="Skills tray" targetLabel="Core skills">Interface design</DragSpring>
<ReactionBar items={[{ id: 'love', icon: 'heart', count: 128, tint: 'var(--nx-danger)' }]} />
<LiveTicker quotes={[{ id: 'aur', symbol: 'AUR', value: 1284.52 }]} />`,
        blade: `{{-- shared core CSS + the framework-agnostic behaviours (confetti, rippleSurface, dragSpring) --}}
<span class="nx-confetti-button-host">
  <button class="nx-confetti-button" data-state="done">You’re on the list</button>
</span>
<button class="nx-ripple-surface" data-nx-ripple data-variant="outline">Get 3.2</button>
<div class="nx-drag-board">
  <button class="nx-drag-chip">Interface design</button>
  <div class="nx-drag-pad nx-drag-slot">…</div>
</div>`,
      }}
    >
      <Demo title={tr('عضویتی که جشن دارد', 'A signup with a party')} center>
        <ConfettiDemo />
      </Demo>

      <Demo title={tr('کارت یادی که همه‌جایش موج می‌گیرد', 'A card where every surface waves')} center>
        <RippleDemo />
      </Demo>

      <Demo title={tr('فیزیکی که در مچ دست حس می‌شود', 'Physics you can feel in your wrist')} wide>
        <DragDemo />
      </Demo>

      <Demo title={tr('عددهایی که مثل عدد حرکت می‌کنند', 'Numbers that move like numbers')} wide>
        <TickerDemo />
      </Demo>
    </Section>
  );
}
