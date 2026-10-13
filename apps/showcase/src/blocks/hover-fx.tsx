import { type CSSProperties, type ReactNode, useState } from 'react';
import { GlareCard, Icon, ImageAccordion, ImageTrail, LinkUnderline, MagneticItem, MagneticSet } from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useTr } from '../lang';

/* ---- Shared artwork: five little scenes, painted with the chart tokens ---------------- */

function Scene({ id, from, to, children }: { id: string; from: string; to: string; children?: ReactNode }) {
  return (
    <svg viewBox="0 0 168 112" aria-hidden="true" focusable="false" style={{ display: 'block', inlineSize: '100%', blockSize: '100%' }}>
      <defs>
        <linearGradient id={id} x1="0" y1="0" x2="1" y2="1">
          <stop offset="0" stopColor={from} />
          <stop offset="1" stopColor={to} />
        </linearGradient>
      </defs>
      <rect width="168" height="112" fill={`url(#${id})`} />
      {children}
    </svg>
  );
}

const trailScenes = [
  <Scene key="aurora" id="hfx-s0" from="var(--nx-lapis-900)" to="var(--nx-chart-4)">
    <path d="M8 84 Q44 30 78 66 Q104 92 128 58" fill="none" stroke="var(--nx-chart-3)" strokeOpacity=".65" strokeWidth="7" strokeLinecap="round" />
    <path d="M22 96 Q58 48 92 78" fill="none" stroke="var(--nx-chart-1)" strokeOpacity=".55" strokeWidth="5" strokeLinecap="round" />
    <circle cx="140" cy="24" r="7" fill="var(--nx-ink-50)" />
  </Scene>,
  <Scene key="dawn" id="hfx-s1" from="var(--nx-ink-900)" to="var(--nx-chart-5)">
    <circle cx="118" cy="42" r="15" fill="var(--nx-gold-200)" />
    <path d="M0 112 L46 46 L80 90 L108 56 L168 112 Z" fill="var(--nx-ink-950)" />
    <path d="M46 46 L60 66 L52 70 L38 62 Z" fill="var(--nx-ink-100)" opacity=".85" />
  </Scene>,
  <Scene key="sea" id="hfx-s2" from="var(--nx-lapis-950)" to="var(--nx-chart-4)">
    <circle cx="48" cy="38" r="12" fill="var(--nx-ink-50)" opacity=".9" />
    <path d="M0 72 Q28 62 56 72 T112 72 T168 72 V112 H0 Z" fill="var(--nx-chart-4)" opacity=".55" />
    <path d="M0 88 Q28 80 56 88 T112 88 T168 88 V112 H0 Z" fill="var(--nx-lapis-900)" />
  </Scene>,
  <Scene key="dusk" id="hfx-s3" from="var(--nx-chart-5)" to="var(--nx-gold-600)">
    <circle cx="84" cy="44" r="14" fill="var(--nx-ink-50)" />
    <path d="M0 84 Q42 58 84 80 T168 76 V112 H0 Z" fill="var(--nx-gold-700)" />
    <path d="M0 100 Q56 84 112 98 T168 96 V112 H0 Z" fill="var(--nx-ink-900)" />
  </Scene>,
  <Scene key="city" id="hfx-s4" from="var(--nx-lapis-950)" to="var(--nx-chart-2)">
    <circle cx="132" cy="26" r="8" fill="var(--nx-gold-200)" />
    <g fill="var(--nx-lapis-900)">
      <rect x="14" y="58" width="20" height="54" />
      <rect x="40" y="44" width="16" height="68" />
      <rect x="62" y="66" width="24" height="46" />
      <rect x="92" y="52" width="14" height="60" />
    </g>
    <g fill="var(--nx-chart-2)" opacity=".9">
      <rect x="19" y="66" width="4" height="4" />
      <rect x="26" y="76" width="4" height="4" />
      <rect x="45" y="54" width="4" height="4" />
      <rect x="68" y="74" width="4" height="4" />
      <rect x="96" y="62" width="4" height="4" />
    </g>
  </Scene>,
];

/* ---- Demo 1: the studio work index ---------------------------------------------------- */

function TrailDemo() {
  const tr = useTr();
  const projects = [
    { id: 'aurora', href: '#hover-fx-blocks', title: tr('آرورا استودیو', 'Aurora Studio'), kind: tr('هویت بصری', 'Brand identity') },
    { id: 'atlas', href: '#hover-fx-blocks', title: tr('اطلس شمال', 'Nordic Atlas'), kind: tr('وب‌سایت تعاملی', 'Interactive site') },
    { id: 'meridian', href: '#hover-fx-blocks', title: tr('مریدین فیلمز', 'Meridian Films'), kind: tr('تیتراژ و پوستر', 'Titles & poster') },
    { id: 'drift', href: '#hover-fx-blocks', title: tr('دریفت تایپ', 'Drift Type'), kind: tr('قلم اختصاصی', 'Custom typeface') },
    { id: 'solstice', href: '#hover-fx-blocks', title: tr('خلیج سولستیس', 'Solstice Bay'), kind: tr('کمپین تابستانی', 'Summer campaign') },
  ];

  const hotspot: CSSProperties = {
    position: 'relative',
    display: 'grid',
    placeItems: 'center',
    inlineSize: '2.4rem',
    aspectRatio: '1',
    borderRadius: 'var(--nx-radius-full)',
    border: '1px solid var(--nx-border)',
    background: 'var(--nx-surface)',
    color: 'var(--nx-text-muted)',
    cursor: 'pointer',
    transitionProperty: 'border-color, color',
    transitionDuration: 'var(--nx-dur-fast)',
  };

  return (
    <ImageTrail
      aria-label={tr('فهرست پروژه‌های استودیو', 'Studio work index')}
      items={projects}
      images={trailScenes.map((art) => ({ art }))}
      count={9}
      aside={
        <>
          {[0, 2].map((index) => (
            <button
              key={index}
              type="button"
              className="nx-focus-ring"
              data-trail-pin=""
              style={hotspot}
              aria-label={projects[index]!.title}
            >
              <Icon name="image" />
            </button>
          ))}
        </>
      }
    />
  );
}

/* ---- Demo 2: eight ways a link can answer back ---------------------------------------- */

const linkStyles: CSSProperties = {
  display: 'grid',
  gap: 'var(--nx-space-3)',
  alignContent: 'start',
  justifyItems: 'start',
  padding: 'var(--nx-space-4)',
  border: '1px solid var(--nx-border)',
  borderRadius: 'var(--nx-radius-lg)',
  background: 'var(--nx-surface)',
};

const linkName: CSSProperties = {
  fontSize: 'var(--nx-text-xs)',
  fontWeight: 600,
  letterSpacing: 'var(--nx-tracking-wide)',
  textTransform: 'uppercase',
  color: 'var(--nx-text-subtle)',
};

const linkDemo: CSSProperties = {
  display: 'flex',
  alignItems: 'center',
  minBlockSize: '2.6rem',
  fontSize: 'var(--nx-text-lg)',
};

function LinksDemo() {
  const tr = useTr();
  const variants = [
    { id: 'center', label: tr('قیمت‌گذاری', 'Pricing'), name: tr('زیرخط دوسویه', 'Centre underline') },
    { id: 'brackets', label: tr('مستندات', 'Docs'), name: tr('براکت‌ها', 'Brackets') },
    { id: 'arrow', label: tr('تغییرات', 'Changelog'), name: tr('فلش لغزنده', 'Sliding arrow') },
    { id: 'swap', label: tr('فرصت‌های شغلی', 'Careers'), name: tr('تعویض متن', 'Text swap') },
    { id: 'marker', label: tr('بلاگ', 'Blog'), name: tr('هایلایت مدادی', 'Marker highlight') },
    { id: 'circle', label: tr('انجمن', 'Community'), name: tr('دایرهٔ پرشونده', 'Bouncing circle') },
    { id: 'wave', label: tr('پشتیبانی', 'Support'), name: tr('موج حروف', 'Letter wave') },
    { id: 'icon', label: tr('وضعیت سرویس', 'Status'), name: tr('آیکون جابه‌جا', 'Shifting icon') },
  ] as const;

  return (
    <div style={{ display: 'grid', gap: 'var(--nx-space-3)', gridTemplateColumns: 'repeat(auto-fit, minmax(min(13.75rem, 100%), 1fr))' }}>
      {variants.map((variant) => (
        <div key={variant.id} style={linkStyles}>
          <span style={linkName}>{variant.name}</span>
          <div style={linkDemo}>
            <LinkUnderline href="#hover-fx-blocks" variant={variant.id}>
              {variant.label}
            </LinkUnderline>
          </div>
        </div>
      ))}
    </div>
  );
}

/* ---- Demo 3: the card that catches the light ------------------------------------------ */

const glareFootCell = { display: 'grid', gap: '0.15rem' } as const;

function GlareDemo() {
  const tr = useTr();
  const [frozen, setFrozen] = useState(false);

  return (
    <div style={{ display: 'flex', flexWrap: 'wrap', alignItems: 'center', gap: 'var(--nx-space-6)', justifyContent: 'center' }}>
      <GlareCard
        label={tr('کارت سفر هورایزن بانک مریدین', 'Meridian Bank Horizon travel card')}
        art={
          <svg viewBox="0 0 400 250" aria-hidden="true" focusable="false" style={{ display: 'block', inlineSize: '100%', blockSize: '100%' }}>
            <defs>
              <linearGradient id="hfx-sky" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0" stopColor="var(--nx-lapis-900)" />
                <stop offset=".55" stopColor="var(--nx-lapis-950)" />
                <stop offset="1" stopColor="var(--nx-ink-950)" />
              </linearGradient>
            </defs>
            <rect width="400" height="250" fill="url(#hfx-sky)" />
            <circle cx="322" cy="58" r="26" fill="var(--nx-ink-50)" opacity=".92" />
            <circle cx="322" cy="58" r="38" fill="var(--nx-chart-1)" opacity=".25" />
            <path d="M0 190 L84 118 L148 172 L216 108 L300 178 L360 140 L400 168 V250 H0 Z" fill="var(--nx-lapis-950)" />
            <path d="M0 214 L70 168 L150 206 L240 158 L330 200 L400 172 V250 H0 Z" fill="var(--nx-lapis-900)" opacity=".85" />
            <g fill="var(--nx-ink-50)" opacity=".8">
              <circle cx="46" cy="40" r="1.4" />
              <circle cx="98" cy="26" r="1.1" />
              <circle cx="160" cy="46" r="1.3" />
              <circle cx="212" cy="22" r="1.1" />
              <circle cx="268" cy="42" r="1.4" />
            </g>
          </svg>
        }
        actions={
          <button type="button" className="nx-glare-button" aria-pressed={frozen} onClick={() => setFrozen(!frozen)}
            onPointerDown={(event) => event.stopPropagation()}
            onPointerMove={(event) => event.stopPropagation()}
          >
            <Icon name="lock" />
            {frozen ? tr('رفع انجماد', 'Unfreeze') : tr('انجماد', 'Freeze')}
          </button>
        }
      >
        <div className="nx-glare-head">
          <span className="nx-glare-brand">
            <strong>{tr('بانک مریدین', 'Meridian Bank')}</strong>
            <span>{tr('هورایزن · سفر', 'Horizon · Travel')}</span>
          </span>
          <span className="nx-glare-chip" aria-hidden="true" />
        </div>
        <span className="nx-glare-line">{'5312\u00A0\u00A0••••\u00A0\u00A0••••\u00A0\u00A07890'}</span>
        <div className="nx-glare-foot">
          <span style={glareFootCell}>
            <small>{tr('دارندهٔ کارت', 'Card holder')}</small>
            <b>Alex Moreau</b>
          </span>
          <span style={glareFootCell}>
            <small>{tr('انقضا', 'Expires')}</small>
            <b>09/29</b>
          </span>
        </div>
      </GlareCard>

      <p style={{ margin: 0, maxInlineSize: '18rem', fontSize: 'var(--nx-text-sm)', lineHeight: 1.8, color: 'var(--nx-text-muted)' }}>
        {tr(
          'نشانگر را روی کارت بچرخانید: نوار گلیر با زاویهٔ دست شما می‌چرخد و صحنه، متن و دکمه هرکدام به فاصله‌ای متفاوت می‌لغزند. فوکوس کارت را در زاویه‌ای نشسته می‌کند و یک تپ گلیر را جارو می‌کند.',
          'Sweep the pointer across the slab: the glare streak rotates with your angle while the scene, the text and the button each drift a different distance. Focus poses the card; a tap sweeps the glare once.',
        )}
      </p>
    </div>
  );
}

/* ---- Demo 4: the destination console (accordion + magnetic dock) ----------------------- */

const panelArt = (index: number) => {
  const gradients = [
    ['var(--nx-lapis-900)', 'var(--nx-chart-2)'],
    ['var(--nx-chart-4)', 'var(--nx-chart-3)'],
    ['var(--nx-chart-1)', 'var(--nx-gold-400)'],
    ['var(--nx-ink-950)', 'var(--nx-chart-3)'],
    ['var(--nx-chart-5)', 'var(--nx-gold-300)'],
  ] as const;
  const [from, to] = gradients[index % gradients.length]!;
  return (
    <svg viewBox="0 0 300 420" preserveAspectRatio="xMidYMid slice" aria-hidden="true" focusable="false" style={{ display: 'block', inlineSize: '100%', blockSize: '100%' }}>
      <defs>
        <linearGradient id={`hfx-p${index}`} x1="0" y1="0" x2="0" y2="1">
          <stop offset="0" stopColor={from} />
          <stop offset="1" stopColor={to} />
        </linearGradient>
      </defs>
      <rect width="300" height="420" fill={`url(#hfx-p${index})`} />
      {index === 0 && (
        <g>
          <circle cx="150" cy="150" r="46" fill="var(--nx-gold-200)" opacity=".9" />
          <path d="M150 236 L96 262 L204 262 Z" fill="var(--nx-ink-950)" opacity=".8" />
          <path d="M0 370 h300 v50 H0 Z" fill="var(--nx-ink-950)" />
        </g>
      )}
      {index === 1 && (
        <g>
          <path d="M0 420 L70 190 L120 300 L170 150 L240 320 L300 220 V420 Z" fill="var(--nx-ink-950)" opacity=".55" />
          <path d="M70 190 L88 226 L74 232 L58 222 Z" fill="var(--nx-ink-50)" />
          <path d="M170 150 L192 196 L176 202 L156 190 Z" fill="var(--nx-ink-50)" />
        </g>
      )}
      {index === 2 && (
        <g>
          <circle cx="92" cy="128" r="44" fill="var(--nx-gold-200)" />
          <path d="M0 420 V240 L64 200 L120 250 L188 208 L240 258 L300 224 V420 Z" fill="var(--nx-lapis-950)" opacity=".8" />
          <g fill="var(--nx-gold-400)">
            <circle cx="228" cy="352" r="10" />
            <circle cx="252" cy="368" r="8" />
          </g>
        </g>
      )}
      {index === 3 && (
        <g>
          <path d="M20 160 Q90 60 160 130 Q220 180 290 110" fill="none" stroke="var(--nx-chart-3)" strokeOpacity=".7" strokeWidth="16" strokeLinecap="round" />
          <g fill="var(--nx-ink-950)">
            <path d="M60 420 L60 320 L44 320 L74 276 L104 320 L88 320 L88 420 Z" />
            <path d="M150 420 L150 288 L130 288 L168 232 L206 288 L186 288 L186 420 Z" />
            <path d="M246 420 L246 330 L230 330 L262 288 L294 330 L278 330 L278 420 Z" />
          </g>
        </g>
      )}
      {index === 4 && (
        <g>
          <circle cx="212" cy="120" r="40" fill="var(--nx-gold-200)" />
          <path d="M0 420 V260 L120 210 L210 260 L300 232 V420 Z" fill="var(--nx-ink-900)" opacity=".7" />
          <path d="M0 396 Q80 380 140 396 T300 392" fill="none" stroke="var(--nx-gold-400)" strokeWidth="5" strokeDasharray="14 10" strokeLinecap="round" />
        </g>
      )}
    </svg>
  );
};

function ConsoleDemo() {
  const tr = useTr();
  const [strength, setStrength] = useState(0.5);
  const [radius, setRadius] = useState(130);
  const [liked, setLiked] = useState(false);

  const destinations = [
    { id: 'kyoto', title: tr('کیوتو', 'Kyoto'), caption: tr('طلوع بر معبد', 'Sunrise over the temple') },
    { id: 'patagonia', title: tr('پاتاگونیا', 'Patagonia'), caption: tr('قله‌های فیروزه‌ای', 'Teal granite towers') },
    { id: 'amalfi', title: tr('ساحل آمالفی', 'Amalfi Coast'), caption: tr('لیمو و دریای آبی', 'Lemons and blue water') },
    { id: 'lapland', title: tr('لاپ‌لند', 'Lapland'), caption: tr('شفق قطبی', 'Aurora over the pines') },
    { id: 'bigsur', title: tr('بیگ سور', 'Big Sur'), caption: tr('غروب بر پرتگاه', 'Cliff-line dusk') },
  ].map((place, index) => ({ ...place, art: panelArt(index) }));

  const pill: CSSProperties = {
    display: 'inline-flex',
    alignItems: 'center',
    gap: 'var(--nx-space-2)',
    padding: '0.78rem 1.5rem',
    border: 0,
    borderRadius: 'var(--nx-radius-full)',
    background: 'var(--nx-accent)',
    color: 'var(--nx-accent-contrast)',
    font: '650 var(--nx-text-md) / 1 var(--nx-font-sans)',
    cursor: 'pointer',
  };

  const iconButton = (pressed: boolean): CSSProperties => ({
    display: 'grid',
    placeItems: 'center',
    inlineSize: '2.9rem',
    aspectRatio: '1',
    borderRadius: 'var(--nx-radius-full)',
    border: `1px solid ${pressed ? 'var(--nx-danger)' : 'var(--nx-border)'}`,
    background: 'var(--nx-surface)',
    color: pressed ? 'var(--nx-danger)' : 'var(--nx-text)',
    cursor: 'pointer',
    transitionProperty: 'color, border-color',
    transitionDuration: 'var(--nx-dur-fast)',
  });

  const chip: CSSProperties = {
    padding: '0.5rem 1rem',
    borderRadius: 'var(--nx-radius-full)',
    border: '1px solid var(--nx-border)',
    background: 'var(--nx-surface)',
    color: 'var(--nx-text-muted)',
    font: '600 var(--nx-text-sm) / 1 var(--nx-font-sans)',
    cursor: 'pointer',
    transitionProperty: 'border-color, color',
    transitionDuration: 'var(--nx-dur-fast)',
  };

  const dial: CSSProperties = {
    display: 'grid',
    gap: 'var(--nx-space-1)',
    inlineSize: 'min(13rem, 100%)',
  };

  const dialLabel: CSSProperties = {
    display: 'flex',
    justifyContent: 'space-between',
    gap: 'var(--nx-space-2)',
    fontSize: 'var(--nx-text-sm)',
    fontWeight: 600,
    color: 'var(--nx-text-muted)',
  };

  return (
    <div style={{ display: 'grid', gap: 'var(--nx-space-6)' }}>
      <ImageAccordion items={destinations} defaultValue={1} aria-label={tr('مقصد‌های سفر', 'Travel destinations')} />

      <MagneticSet strength={strength} radius={radius} aria-label={tr('میدان کنترل‌های سریع', 'Quick-action field')}>
        <div className="nx-magnetic-row">
          <MagneticItem pull={30} pose={[0, -12]}>
            <button type="button" className="nx-focus-ring" style={pill}>
              <Icon name="zap" />
              {tr('استقرار روی اج', 'Deploy to edge')}
            </button>
          </MagneticItem>

          <MagneticItem pull={22} pose={[0, -10]}>
            <button
              type="button"
              className="nx-focus-ring"
              style={iconButton(liked)}
              aria-pressed={liked}
              aria-label={liked ? tr('برداشتن پسند یادداشت انتشار', 'Unlike release note') : tr('پسندیدن یادداشت انتشار', 'Like release note')}
              onClick={() => setLiked(!liked)}
            >
              <Icon name="heart" />
            </button>
          </MagneticItem>

          {[
            tr('لاگ‌ها', 'Logs'),
            tr('متریک‌ها', 'Metrics'),
            tr('بازگشت نسخه', 'Rollback'),
          ].map((label) => (
            <MagneticItem key={label} pull={11} pose={[0, -6]}>
              <button type="button" className="nx-focus-ring" style={chip}>
                {label}
              </button>
            </MagneticItem>
          ))}
        </div>

        <div style={{ display: 'flex', flexWrap: 'wrap', justifyContent: 'center', gap: 'var(--nx-space-4)' }}>
          <label style={dial}>
            <span style={dialLabel}>
              {tr('شدت کشش', 'Pull strength')}
              <output style={{ color: 'var(--nx-accent-text)', fontVariantNumeric: 'tabular-nums' }}>{`${Math.round(strength * 100)}%`}</output>
            </span>
            <input
              type="range"
              min={0.1}
              max={0.95}
              step={0.05}
              value={strength}
              onChange={(event) => setStrength(Number(event.target.value))}
              style={{ accentColor: 'var(--nx-accent)', margin: 0 }}
            />
          </label>
          <label style={dial}>
            <span style={dialLabel}>
              {tr('شعاع میدان', 'Field radius')}
              <output style={{ color: 'var(--nx-accent-text)', fontVariantNumeric: 'tabular-nums' }}>{`${radius}px`}</output>
            </span>
            <input
              type="range"
              min={60}
              max={220}
              step={10}
              value={radius}
              onChange={(event) => setRadius(Number(event.target.value))}
              style={{ accentColor: 'var(--nx-accent)', margin: 0 }}
            />
          </label>
        </div>
      </MagneticSet>
    </div>
  );
}

/* ---- The section ------------------------------------------------------------------------ */

export function HoverFxBlocks() {
  const tr = useTr();
  return (
    <Section
      id="hover-fx-blocks"
      eyebrow={tr('هاورها', 'Hover FX')}
      title={tr('چیزی که زیر دست زنده می‌شود', 'What comes alive under the hand')}
      description={tr(
        'پنج جواب به هاور: زنجیره‌ای از کارت‌های تصویری که دنبال موس می‌آیند و هر کارت به کارت قبلی خودش می‌چسبد؛ هشت زبان هاور برای لینک‌های متنی؛ کارتی که نور را با زاویهٔ دست شما می‌گیرد؛ میدان مغناطیسی کنترل‌ها با شعاع و شدت قابل‌تنظیم؛ و آکاردئونی از صحنه‌ها که زیر هاور باز می‌شود.',
        'Five answers to the hover: a chain of image cards that chases the pointer, each card clinging to its predecessor; eight hover languages for text links; a card that catches the light at your angle; a magnetic field of controls with a live radius and strength; and an accordion of scenes that unfolds under the hand.',
      )}
      code={{
        react: `<ImageTrail items={projects} images={scenes} count={9} />
<LinkUnderline href="/docs" variant="wave">Docs</LinkUnderline>
<GlareCard label="Travel card" art={<SceneSvg />}>{cardBody}</GlareCard>
<MagneticSet strength={0.5} radius={130}>
  <MagneticItem pull={30} pose={[0, -12]}><button>Deploy to edge</button></MagneticItem>
</MagneticSet>
<ImageAccordion items={destinations} defaultValue={1} />`,
        blade: `{{-- shared core CSS + the framework-agnostic behaviours --}}
<div class="nx-image-trail">{{ imageTrail($el) }}
  <a class="nx-image-trail-link" href="…" data-trail-pin>Aurora Studio</a>
</div>
<a class="nx-link-underline" data-variant="wave" href="{{ url('docs') }}">Docs</a>
<div class="nx-glare-card" tabindex="0">{{ glareCard($el) }}…</div>
<div class="nx-magnetic-set" style="--nx-field-r: 130px; --nx-field-k: 0.5">{{ magneticField($el) }}
  <button class="nx-magnetic-item" data-pull="30" data-pose="0 -12">Deploy to edge</button>
</div>
<div class="nx-image-accordion">
  <button class="nx-image-accordion-panel" data-open aria-expanded="true">…</button>
</div>`,
      }}
    >
      <Demo title={tr('گالری‌ای که دنبال موس می‌آید', 'A gallery that chases the cursor')} wide>
        <TrailDemo />
      </Demo>

      <Demo title={tr('هشت راه که یک لینک جواب شما را می‌دهد', 'Eight ways a link answers back')} wide>
        <LinksDemo />
      </Demo>

      <Demo title={tr('کارتی که نور را می‌گیرد', 'A card that catches the light')}>
        <GlareDemo />
      </Demo>

      <Demo title={tr('کنسول مقصد: پنل‌ها و میدان مغناطیسی', 'Destination console: panels and the magnetic field')} wide>
        <ConsoleDemo />
      </Demo>
    </Section>
  );
}
