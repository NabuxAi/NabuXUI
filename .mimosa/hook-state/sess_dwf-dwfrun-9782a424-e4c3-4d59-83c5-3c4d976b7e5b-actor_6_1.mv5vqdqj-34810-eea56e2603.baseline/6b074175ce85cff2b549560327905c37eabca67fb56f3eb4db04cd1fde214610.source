import { type CSSProperties } from 'react';
import { CursorHalo, MarqueeRows, SpotlightGrid } from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useTr } from '../lang';

/* ---- Spotlight grid demo: the feature wall of a product page -------------------- */

function SpotlightDemo() {
  const tr = useTr();
  const items = [
    {
      id: 'edge',
      title: tr('تحویل لبه', 'Edge delivery'),
      note: tr('شبکهٔ تحویل با ۲۸+ نقطه، از فرانکفورت تا سنگاپور.', 'A delivery network with 28+ points, Frankfurt to Singapore.'),
      meta: '28+ PoP',
    },
    {
      id: 'query',
      title: tr('کوئری زنده', 'Live queries'),
      note: tr('پاسخ زیر ۴۰ میلی‌ثانیه حتی روی رویدادهای امروز.', 'Answers under 40ms even on today’s events.'),
      meta: 'p95 38ms',
    },
    {
      id: 'replay',
      title: tr('بازپخش نشست', 'Session replay'),
      note: tr('همان کلیک، همان مکث — با پوشش دادهٔ حساس.', 'The exact click, the exact pause — with sensitive fields masked.'),
      meta: '4K · 60',
    },
    {
      id: 'guards',
      title: tr('ریل‌های نگهبان', 'Guardrails'),
      note: tr('هشدار بودجه پیش از رسیدن قبض، نه بعدش.', 'Budget alerts before the invoice lands, not after.'),
      meta: tr('۳ سطح', '3 tiers'),
    },
    {
      id: 'sync',
      title: tr('همگام‌سازی انبار', 'Warehouse sync'),
      note: tr('BigQuery و Snowflake، هر پانزده دقیقه.', 'BigQuery and Snowflake, every fifteen minutes.'),
      meta: '15min',
    },
    {
      id: 'sso',
      title: tr('ورود یکپارچه', 'Enterprise SSO'),
      note: tr('SAML و SCIM با یک کلید، بدون پروژهٔ شش‌ماهه.', 'SAML and SCIM with one toggle, no six-month project.'),
      meta: 'SAML · SCIM',
    },
    {
      id: 'sdk',
      title: tr('کیت‌های توسعه', 'SDKs'),
      note: tr('پنج فریم‌ورک، یک API؛ از رویداد خام تا داشبورد.', 'Five frameworks, one API; raw event to dashboard.'),
      meta: tr('۵ فریم‌ورک', '5 frameworks'),
    },
    {
      id: 'status',
      title: tr('وضعیت و هشدار', 'Status & alerts'),
      note: tr('صفحهٔ وضعیت عمومی و هشدارهایی که به‌وقت می‌رسند.', 'A public status page and alerts that arrive on time.'),
      meta: '99.99%',
    },
  ];
  return <SpotlightGrid items={items} radius={280} aria-label={tr('دیوار امکانات', 'Feature wall')} />;
}

/* ---- Cursor halo demo: a launch stage ------------------------------------------- */

const stageStyle: CSSProperties = {
  display: 'grid',
  gap: 'var(--nx-space-6)',
  padding: 'clamp(1.25rem, 4vw, 2.5rem)',
  border: '1px solid var(--nx-border)',
  borderRadius: 'var(--nx-radius-xl)',
  background: 'var(--nx-surface)',
};

const bodyStyle: CSSProperties = {
  display: 'grid',
  gap: 'var(--nx-space-4)',
  gridTemplateColumns: '1.2fr 0.8fr',
  alignItems: 'stretch',
  minInlineSize: 0,
};

const panelStyle: CSSProperties = {
  margin: 0,
  padding: 'var(--nx-space-5)',
  border: '1px solid var(--nx-border)',
  borderRadius: 'var(--nx-radius-lg)',
  background: 'var(--nx-surface-2)',
  lineHeight: 1.9,
};

const trailerStyle: CSSProperties = {
  display: 'grid',
  gap: 'var(--nx-space-4)',
  alignContent: 'center',
  justifyItems: 'center',
  padding: 'var(--nx-space-5)',
  border: '1px solid var(--nx-border)',
  borderRadius: 'var(--nx-radius-lg)',
  background: 'radial-gradient(120% 90% at 20% 0%, var(--nx-accent-soft), transparent 60%), var(--nx-surface-2)',
};

const playStyle: CSSProperties = {
  display: 'inline-flex',
  alignItems: 'center',
  gap: 'var(--nx-space-2)',
  padding: '0.8rem 1.5rem',
  border: 0,
  borderRadius: 'var(--nx-radius-full)',
  background: 'var(--nx-text)',
  color: 'var(--nx-bg)',
  font: '600 var(--nx-text-sm) / 1 var(--nx-font-display)',
  cursor: 'pointer',
};

function CursorDemo() {
  const tr = useTr();
  return (
    <CursorHalo as="section" style={stageStyle} aria-label={tr('صحنهٔ رونمایی', 'Launch stage')}>
      <div style={{ display: 'grid', gap: 'var(--nx-space-2)', textAlign: 'center', justifyItems: 'center' }}>
        <p style={{ margin: 0, fontSize: 'var(--nx-text-xs)', fontWeight: 600, letterSpacing: 'var(--nx-tracking-wider)', textTransform: 'uppercase', color: 'var(--nx-text-muted)' }}>
          {tr('مجموعهٔ پاییز · ۰۴ · کیوتو', 'Autumn drop · 04 · Kyoto')}
        </p>
        <a
          href="#motion-fx-blocks"
          data-cursor="link"
          style={{
            font: `800 clamp(1.75rem, 6vw, 3.25rem) / 1.15 var(--nx-font-display)`,
            color: 'var(--nx-text)',
            textDecoration: 'none',
            paddingInline: '0.25rem',
          }}
        >
          {tr('در کل مجموعه بچرخ', 'Wander the whole collection')}
        </a>
      </div>

      <div style={bodyStyle}>
        <p style={panelStyle} data-cursor="text">
          {tr(
            'دوازده قطعه که در کیوتو و کپنهاگ بریده شده‌اند — کاپشنی که در جیب خودش جا می‌شود، بافت‌های پشمی با رنگ چای و بوت‌هایی که در همان پیاده‌روی اول نرم می‌شوند. اشاره‌گر را روی این بند ببرید تا کرسر به میلهٔ خواندن باریک شود.',
            'Twelve pieces cut in Kyoto and Copenhagen — a coat that packs into its own pocket, wool knits dyed with tea, and boots that break in on the first walk. Move your pointer over this paragraph and the cursor thins into a reading bar.',
          )}
        </p>
        <div style={trailerStyle}>
          <button type="button" style={playStyle} data-cursor="play" data-cursor-label={tr('پخش', 'Play')}>
            {tr('پخش تریلر', 'Play the trailer')}
          </button>
          <small style={{ color: 'var(--nx-text-muted)' }}>{tr('۲:۱۴ · فیلم‌برداری‌شده در محل', '2:14 · shot on location')}</small>
        </div>
      </div>

      <p style={{ margin: 0, textAlign: 'center', fontSize: 'var(--nx-text-xs)', color: 'var(--nx-text-muted)' }}>
        {tr(
          'روی تیتر حلقه، روی متن میله، روی دکمه برچسب — از صحنه بیرون بروید همه محو می‌شود؛ روی لمس، کرسر بومی سرِ جای خودش می‌ماند.',
          'Ring over the title, bar over the copy, label over the button — leave the stage and it all fades; on touch the native cursor stays.',
        )}
      </p>
    </CursorHalo>
  );
}

/* ---- Marquee wall demo: the testimonial band ------------------------------------ */

function MarqueeDemo() {
  const tr = useTr();
  const row1 = [
    { id: 'lk', avatar: 'LK', quote: tr('داشبورد پیش از قهوه‌ام بالا می‌آید.', 'The dashboard loads before my coffee does.'), caption: tr('لنا — برلین', 'Lena · Berlin') },
    { id: 'ro', avatar: 'RO', quote: tr('همگام‌سازی انبار داده هر ۱۵ دقیقه، بی‌حرف.', 'Warehouse sync just works, every fifteen minutes.'), caption: tr('رای — سنگاپور', 'Ravi · Singapore') },
    { id: 'am', avatar: 'AM', quote: tr('بازپخش نشست، باگ را در همان یک تماشا پیدا کرد.', 'Session replay found the bug in a single watch.'), caption: tr('ایفی — دوبلین', 'Aoife · Dublin') },
    { id: 'jt', avatar: 'JT', quote: tr('ریل‌های نگهبان بودجهٔ سه‌ماهه را نجات دادند.', 'Guardrails saved the quarterly budget.'), caption: tr('یوناس — تورنتو', 'Jonas · Toronto') },
    { id: 'ms', avatar: 'MS', quote: tr('تأخیر از اینجا؟ فقط ۲۶ میلی‌ثانیه.', 'Latency from here? Twenty-six milliseconds.'), caption: tr('مِی — اوساکا', 'Mei · Osaka') },
    { id: 'cr', avatar: 'CR', quote: tr('حالا همهٔ تیم هر روز صبحش را می‌خواند.', 'The whole team reads it every morning now.'), caption: tr('کریستینا — لیسبون', 'Cristina · Lisbon') },
  ];
  const row2 = [
    { id: 'ph', avatar: 'PH', quote: tr('راه‌اندازی SSO یک بعدازظهر تمام شد.', 'SSO was done in one afternoon.'), caption: tr('پدرو — هلسینکی', 'Pedro · Helsinki') },
    { id: 'sk', avatar: 'SK', quote: tr('کوئری‌های زنده حتی روی دادهٔ امروز سریع‌اند.', 'Live queries stay fast even on today’s data.'), caption: tr('سورا — سئول', 'Sora · Seoul') },
    { id: 'nv', avatar: 'NV', quote: tr('هشدارها دقیقاً همان‌جا می‌رسند که باید.', 'Alerts land exactly where they should.'), caption: tr('ناتالی — آمستردام', 'Nathalie · Amsterdam') },
    { id: 'dm', avatar: 'DM', quote: tr('از پنج فریم‌ورک استفاده کردیم؛ API یکی بود.', 'We used all five SDKs; one API.'), caption: tr('دومنیکو — میلان', 'Domenico · Milan') },
    { id: 'ab', avatar: 'AB', quote: tr('صفحهٔ وضعیت دیگر کسی را نگران نمی‌کند.', 'The status page quieted every worry.'), caption: tr('آیرین — بارسلونا', 'Irene · Barcelona') },
    { id: 'fw', avatar: 'FW', quote: tr('صورت‌حساب شفاف، ریل‌های نگهبان دقیق.', 'A transparent bill and precise guardrails.'), caption: tr('فین — زوریخ', 'Finn · Zurich') },
  ];
  const row3 = [
    { id: 'hg', avatar: 'HG', quote: tr('مهاجرت از ابزار قبلی یک بعدازظهر طول کشید.', 'Migrating off our old tool took one afternoon.'), caption: tr('هانا — کپنهاگ', 'Hanna · Copenhagen') },
    { id: 'lb', avatar: 'LB', quote: tr('مصرف هر سرویس کارت خودش را دارد.', 'Every service’s spend shows on its own card.'), caption: tr('لویی — مونترال', 'Louis · Montreal') },
    { id: 'ta', avatar: 'TA', quote: tr('لبهٔ تحویل اروپا واقعاً حس می‌شود.', 'You can feel the European edge.'), caption: tr('تارو — فرانکفورت', 'Taro · Frankfurt') },
    { id: 'ks', avatar: 'KS', quote: tr('گزارش هفتگی را دیگر هیچ‌کس نمی‌نویسد.', 'Nobody writes the weekly report anymore.'), caption: tr('کلارا — وین', 'Klara · Vienna') },
    { id: 'om', avatar: 'OM', quote: tr('پشتیبانی در کمتر از یک ساعت جواب می‌دهد.', 'Support answers in under an hour.'), caption: tr('عمر — دبی', 'Omar · Dubai') },
    { id: 'sl', avatar: 'SL', quote: tr('تیم داده‌مان بالاخره یک ابزار مشترک دارد.', 'Our data team finally shares one tool.'), caption: tr('سیلویا — سائوپائولو', 'Sílvia · São Paulo') },
  ];
  return (
    <MarqueeRows
      aria-label={tr('دیوار نقل‌قول‌ها', 'Testimonial wall')}
      flipLabel={tr('تعویض جهت همه', 'Flip every direction')}
      rows={[
        { id: 'slow', items: row1, duration: 38 },
        { id: 'fast', items: row2, duration: 26, reverse: true },
        { id: 'mid', items: row3, duration: 32 },
      ]}
    />
  );
}

/* ---- The section ------------------------------------------------------------------ */

export function MotionFxBlocks() {
  const tr = useTr();
  return (
    <Section
      id="motion-fx-blocks"
      eyebrow={tr('حرکت‌های تعاملی', 'Motion FX')}
      title={tr('نوری که دست شما را دنبال می‌کند', 'Light that follows your hand')}
      description={tr(
        'سه حرکت که به اشاره‌گر پاسخ می‌دهند: نورافکنی که روی دیواری از کارت‌ها دنبال دست می‌آید و کارت زیر دست را از درون روشن می‌کند؛ حلقه‌ای که کرسر را دنبال می‌کند و روی لینک، متن و دکمه حالت عوض می‌کند؛ و دیواری از مارکی‌های چندردیفه با سرعت و جهت‌های ناهمگام که بیرون از دید می‌ایستند.',
        'Three motions that answer the pointer: a follow-spot that sweeps a wall of cards and lights the one under your hand from inside; a ring that trails the cursor and changes state over links, text and buttons; and a wall of marquee rows at unsynchronised speeds and directions that parks itself off screen.',
      )}
      code={{
        react: `<SpotlightGrid items={features} radius={280} aria-label="Feature wall" />
<CursorHalo as="section">
  <a href="…" data-cursor="link">Wander the collection</a>
  <p data-cursor="text">…</p>
  <button data-cursor="play" data-cursor-label="Play">Play the trailer</button>
</CursorHalo>
<MarqueeRows
  flipLabel="Flip every direction"
  rows={[{ items: quotes, duration: 38 }, { items: more, duration: 26, reverse: true }]}
/>`,
        blade: `{{-- shared core CSS + the framework-agnostic behaviours --}}
<div class="nx-spotlight-grid">{{ pointerSpot($el, ['radius' => 280]) }}</div>
<section class="nx-cursor-halo">{{ cursorHalo($el) }}
  <a href="…" data-cursor="link">Wander the collection</a>
</section>
<div class="nx-marquee-wall" data-reversed>{{ marqueeWall($el) }}
  <button class="nx-marquee-flip" aria-pressed="false">Flip every direction</button>
</div>`,
      }}
    >
      <Demo title={tr('دیوار امکانات با نورافکن', 'The feature wall under a follow-spot')} wide>
        <SpotlightDemo />
      </Demo>

      <Demo title={tr('حلقهٔ کرسر با حالت‌ها', 'The cursor ring and its states')} wide>
        <CursorDemo />
      </Demo>

      <Demo title={tr('دیوار مارکی سه‌ردیفی', 'The three-row marquee wall')} wide>
        <MarqueeDemo />
      </Demo>
    </Section>
  );
}
