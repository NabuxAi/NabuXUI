import { useRef, useState } from 'react';
import {
  AuroraBackdrop,
  Button,
  ContourLines,
  Metaballs,
  PerspectiveGrid,
  StarfieldBackdrop,
  type StarfieldHandle,
  type AuroraColors,
  type ContourPeak,
} from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useTr } from '../lang';

/* ---- Aurora demo: the hero of an edge-compute product -------------------------------- */

function AuroraDemo() {
  const tr = useTr();
  const palettes: Array<{ label: string; colors: AuroraColors }> = [
    { label: tr('شفق', 'Borealis'), colors: ['var(--nx-cyan-400)', 'var(--nx-chart-7)', 'var(--nx-violet-500)'] },
    { label: tr('غروب', 'Sunset'), colors: ['var(--nx-gold)', 'var(--nx-chart-2)', 'var(--nx-violet-500)'] },
    { label: tr('اقیانوس', 'Ocean'), colors: ['var(--nx-cyan-500)', 'var(--nx-lapis-500)', 'var(--nx-cyan-300)'] },
  ];
  const [pick, setPick] = useState(0);

  return (
    <div style={{ display: 'grid', gap: 'var(--nx-space-4)' }}>
      <AuroraBackdrop colors={palettes[pick]!.colors} aria-label={tr('هیروی ابر لبه', 'Edge cloud hero')}>
        <div className="nx-ambient-copy">
          <span className="nx-ambient-eyebrow">Borealis Cloud · {tr('شبکهٔ لبه', 'Edge network')}</span>
          <h3 className="nx-ambient-title">{tr('زیر نور شفق، استقرار سرد است', 'Cold deploys under the aurora')}</h3>
          <p className="nx-ambient-text">
            {tr(
              'رایانش لبه در ۳۱ شهر — تأخیر زیر ۵۰ میلی‌ثانیه، بدون پیکربندی. بیلد شما همان‌جا فرود می‌آید که کاربرانتید.',
              'Edge compute in 31 cities — sub-50ms latency, zero config. Your build lands where your users already are.',
            )}
          </p>
          <div className="nx-ambient-actions">
            <Button variant="primary">{tr('شروع استقرار', 'Start deploying')}</Button>
            <Button variant="secondary">{tr('گزارش تأخیر', 'Latency report')}</Button>
          </div>
        </div>
      </AuroraBackdrop>

      <div className="sc-row" role="group" aria-label={tr('پالت آسمان', 'Sky palette')} style={{ justifyContent: 'center', flexWrap: 'wrap' }}>
        {palettes.map((palette, index) => (
          <button
            key={palette.label}
            type="button"
            aria-pressed={pick === index}
            onClick={() => setPick(index)}
            style={{ display: 'grid', gap: '0.35rem', justifyItems: 'center', border: 0, padding: 0, background: 'none', color: 'var(--nx-text-muted)', font: 'inherit', cursor: 'pointer' }}
          >
            <span
              aria-hidden="true"
              style={{
                inlineSize: '2.1rem',
                blockSize: '2.1rem',
                borderRadius: '50%',
                border: '1px solid var(--nx-border)',
                boxShadow: 'var(--nx-shadow-md)',
                background: `linear-gradient(135deg, ${palette.colors.join(', ')})`,
                scale: pick === index ? '1.1' : undefined,
                outline: pick === index ? '2px solid var(--nx-accent)' : undefined,
                outlineOffset: '3px',
                transition: 'scale var(--nx-spring-snappy-duration) var(--nx-spring-snappy)',
              }}
            />
            <small style={{ fontSize: 'var(--nx-text-xs)' }}>{palette.label}</small>
          </button>
        ))}
      </div>
    </div>
  );
}

/* ---- Starfield demo: the night-flights hero ------------------------------------------- */

function StarfieldDemo() {
  const tr = useTr();
  const sky = useRef<StarfieldHandle>(null);
  const [count, setCount] = useState(0);
  const destinations = [
    { id: 'kyoto', name: tr('کیوتو', 'Kyoto'), price: '$612' },
    { id: 'lisbon', name: tr('لیسبون', 'Lisbon'), price: '$318' },
    { id: 'reykjavik', name: tr('ریکیاویک', 'Reykjavík'), price: '$289' },
    { id: 'buenos-aires', name: tr('بوئنوس آیرس', 'Buenos Aires'), price: '$544' },
  ];

  return (
    <div style={{ display: 'grid', gap: 'var(--nx-space-4)' }}>
      <StarfieldBackdrop ref={sky} density={200} onCount={setCount} aria-label={tr('هیروی پروازهای شبانه', 'Night-flights hero')}>
        <div className="nx-ambient-copy">
          <span className="nx-ambient-eyebrow">Skyward · {tr('تعرفه‌های شب‌ران', 'red-eye fares')}</span>
          <h3 className="nx-ambient-title">{tr('هیچ‌جا دیگر دور نیست', 'Nowhere is far anymore')}</h3>
          <p className="nx-ambient-text">
            {tr(
              'نیمه‌شب سوار شو، سپیده‌دم پیاده شو. Skyward ساعات آرام را به ۴۲ شهر پرواز می‌کند تا اولین صبحتان کامل باشد.',
              'Board at midnight, land at sunrise. Skyward flies the quiet hours to 42 cities so your first morning is a full one.',
            )}
          </p>
          <div className="nx-ambient-actions" role="group" aria-label={tr('مقصدهای شبانه', 'Night destinations')}>
            {destinations.map((destination) => (
              <span key={destination.id} className="nx-ambient-chip">
                {destination.name} <strong>{destination.price}</strong>
              </span>
            ))}
          </div>
        </div>
      </StarfieldBackdrop>

      <div className="sc-row" style={{ justifyContent: 'center', flexWrap: 'wrap' }}>
        <Button size="sm" variant="secondary" onClick={() => sky.current?.warp()}>
          {tr('وارپ برای ۱٫۵ ثانیه', 'Warp for 1.5s')}
        </Button>
        <span role="status" style={{ paddingInline: 'var(--nx-space-2)', color: 'var(--nx-text-muted)', fontSize: 'var(--nx-text-xs)', fontVariantNumeric: 'tabular-nums' }}>
          {tr(`${count} ستاره در پرواز`, `${count} ${count === 1 ? 'star' : 'stars'} in flight`)}
        </span>
      </div>
    </div>
  );
}

/* ---- Perspective grid demo: a synth station's hero ------------------------------------- */

function GridDemo() {
  const tr = useTr();
  const [dur, setDur] = useState(1.6);

  return (
    <div style={{ display: 'grid', gap: 'var(--nx-space-4)' }}>
      <PerspectiveGrid speed={dur} aria-label={tr('هیروی ایستگاه سینت', 'Synth station hero')}>
        <div className="nx-ambient-copy">
          <span className="nx-ambient-eyebrow">Nightdrive FM · {tr('زنده از دل شبکه', 'live from the grid')}</span>
          <h3 className="nx-ambient-title">{tr('سی‌ودو سال سینت، یک افق', 'Thirty-two years of synth, one horizon')}</h3>
          <p className="nx-ambient-text">
            {tr(
              'از شب‌های توکیو تا سپیده‌های تورنتو — کلاسیک‌ها و تازه‌ها را شبانه‌روز پخش می‌کنیم.',
              'From Tokyo nights to Toronto dawns — the classics and the fresh presses, back to back around the clock.',
            )}
          </p>
        </div>
      </PerspectiveGrid>

      <label
        style={{
          display: 'flex',
          flexWrap: 'wrap',
          alignItems: 'center',
          justifyContent: 'center',
          gap: 'var(--nx-space-3)',
          padding: 'var(--nx-space-2) var(--nx-space-4)',
          border: '1px solid var(--nx-border)',
          borderRadius: 'var(--nx-radius-full)',
          background: 'var(--nx-surface)',
          fontSize: 'var(--nx-text-sm)',
          fontWeight: 600,
          color: 'var(--nx-text-muted)',
          justifySelf: 'center',
        }}
      >
        <span aria-hidden="true">🐢</span>
        <input
          type="range"
          min={0.6}
          max={3}
          step={0.1}
          value={dur}
          aria-label={tr('سرعت شبکه', 'Grid speed')}
          onChange={(event) => setDur(Number(event.target.value))}
          style={{ inlineSize: 'clamp(8rem, 32vw, 13rem)', margin: 0, accentColor: 'var(--nx-accent)', cursor: 'pointer' }}
        />
        <span aria-hidden="true">🚀</span>
        <output style={{ minInlineSize: '5.5ch', fontSize: 'var(--nx-text-xs)', fontVariantNumeric: 'tabular-nums' }}>
          {tr(`${dur.toFixed(1)} ثانیه/خانه`, `${dur.toFixed(1)}s / cell`)}
        </output>
      </label>
    </div>
  );
}

/* ---- The map and the goo: two calmer skies, one demo ----------------------------------- */

interface Summit extends ContourPeak {
  name: string;
  range: string;
  meters: string;
}

function MapDemo() {
  const tr = useTr();
  const [hot, setHot] = useState<string | null>(null);
  const summits: Summit[] = [
    { id: 'matterhorn', name: tr('ماترهورن', 'Matterhorn'), range: tr('رشته‌الپ', 'The Alps'), meters: tr('۴٬۴۷۸', '4,478'), band: 5, x: 320, color: 'var(--nx-warning)', label: tr('ماترهورن · رشته‌الپ · ۴٬۴۷۸ متر', 'Matterhorn · The Alps · 4,478 m') },
    { id: 'denali', name: tr('دنالی', 'Denali'), range: tr('آلاسکا', 'Alaska Range'), meters: tr('۶٬۱۹۰', '6,190'), band: 6, x: 660, color: 'var(--nx-chart-2)', label: tr('دنالی · آلاسکا · ۶٬۱۹۰ متر', 'Denali · Alaska Range · 6,190 m') },
    { id: 'aconcagua', name: tr('آکونکاگوا', 'Aconcagua'), range: tr('آند', 'The Andes'), meters: tr('۶٬۹۶۱', '6,961'), band: 7, x: 950, color: 'var(--nx-danger)', label: tr('آکونکاگوا · آند · ۶٬۹۶۱ متر', 'Aconcagua · The Andes · 6,961 m') },
  ];
  const active = summits.find((summit) => summit.id === hot);

  return (
    <div style={{ display: 'grid', gap: 'var(--nx-space-4)' }}>
      <Metaballs aria-label={tr('هیروی فضای کاری', 'Workspace hero')}>
        <div className="nx-ambient-copy">
          <span className="nx-ambient-eyebrow">Meld · {tr('فضای کاری زنده', 'the living workspace')}</span>
          <h3 className="nx-ambient-title">{tr('جایی که کارها در هم می‌آمیزد', 'Where work melds together')}</h3>
          <p className="nx-ambient-text">
            {tr(
              'سندها، گفتگوها و کارها در یک سطح زنده جمع می‌شوند — یک بار جست‌وجو کنید و هرچه تیم نوشته بالا می‌آید.',
              'Docs, threads and tasks pool into one living surface — search once and everything your team ever wrote surfaces with it.',
            )}
          </p>
        </div>
      </Metaballs>

      <ContourLines peaks={summits} onPeakChange={setHot} aria-label={tr('نقشهٔ تورهای کوهستان', 'Trek map')}>
        <div className="nx-ambient-copy">
          <span className="nx-ambient-eyebrow">Ridgeline Atlas · {tr('تورهای راهنما', 'guided treks')}</span>
          <h3 className="nx-ambient-title">{tr('کوه را مثل نقشه بخوان', 'Read the mountain like a map')}</h3>
          <p className="nx-ambient-text">
            {tr(
              'چهل‌ودو مسیر راهنما در آلپ، آند و آلاسکا — پروفایل شیب و مسیر عقب‌نشینی، ترسیم‌شده توسط کسانی که اول خودشان رفتند.',
              'Forty-two guided routes across the Alps, the Andes and Alaska — drawn by people who walked them first.',
            )}
          </p>
        </div>
        <p className="nx-contour-card" role="status" aria-live="polite">
          <span className="nx-contour-card-bar" aria-hidden="true" />
          <span>
            {active ? `${active.name} · ${active.meters} m` : tr('نقطهٔ قله‌ها را هاور کنید', 'Hover a summit dot')}
            <small>{active ? active.range : tr('ارتفاع‌ها از اطلس تور', 'Altitudes from the trek atlas')}</small>
          </span>
        </p>
      </ContourLines>
    </div>
  );
}

/* ---- The section ----------------------------------------------------------------------- */

export function AmbientBlocks() {
  const tr = useTr();
  return (
    <Section
      id="ambient-blocks"
      eyebrow={tr('پس‌زمینه‌های محیطی', 'Ambient')}
      title={tr('آسمانی که خودش بخش است', 'A sky that is the section')}
      description={tr(
        'پنج پس‌زمینه که هرکدام یک بخش واقعی را حمل می‌کنند: شفقِ بلاب‌های نفس‌کش، کفِ رتروی روان به سمت بیننده، میدان ستارهٔ عمق‌دار با پارالکس موس و وارپ، نقشهٔ تراز فازگیر با قله‌های سواری، و گوی‌های چسبندهٔ SVG. همه فقط transform را انیمیت می‌کنند، بیرون از دید پارک می‌شوند و در حرکت کاهش‌یافته به قاب ثابت می‌نشینند.',
        'Five backgrounds that each carry a real section: an aurora of breathing blobs, a retro floor streaming toward you, a depth starfield with pointer parallax and a warp, a phased contour map with riding summits, and gooey SVG metaballs. All animate transform only, park off screen and settle to a still frame under reduced motion.',
      )}
      code={{
        react: `<AuroraBackdrop colors={['var(--nx-cyan-400)', 'var(--nx-chart-7)', 'var(--nx-violet-500)']}>
  <div className="nx-ambient-copy">…</div>
</AuroraBackdrop>

<PerspectiveGrid speed={1.6} />

<StarfieldBackdrop ref={sky} density={200} onCount={setCount} />
<button onClick={() => sky.current?.warp()}>Warp</button>

<ContourLines peaks={summits} onPeakChange={setHot} />

<Metaballs count={5} variant="dark" />`,
        blade: `{{-- core CSS + the framework-agnostic behaviours --}}
<section class="nx-aurora" style="--nx-aurora-c1: var(--nx-cyan-400); …">
    <div class="nx-aurora-sky" aria-hidden="true"><i class="nx-aurora-blob"></i>…</div>
    <div class="nx-ambient-copy">…</div>
</section>

<section class="nx-starfield">
    <canvas class="nx-starfield-canvas" aria-hidden="true"></canvas>
    <div class="nx-ambient-copy">…</div>
</section>

<script type="module">
    import { starfield } from '@nabuxai/ui-core';
    starfield(document.querySelector('.nx-starfield'), { density: 200 });
</script>`,
      }}
    >
      <div className="sc-demos">
        <Demo wide bare title={tr('آسمانی که زیر سرتیتر نفس می‌کشد', 'A sky that breathes under the headline')}>
          <AuroraDemo />
        </Demo>

        <Demo wide bare title={tr('ستاره‌هایی که از کنار سرتیتر می‌گذرند', 'Stars that fly past the headline')}>
          <StarfieldDemo />
        </Demo>

        <Demo wide bare title={tr('کفی که به سمت شما جریان دارد', 'A floor that streams toward you')}>
          <GridDemo />
        </Demo>

        <Demo wide bare title={tr('گو و نقشه: دو آسمان آرام‌تر', 'The goo and the map: two calmer skies')}>
          <MapDemo />
        </Demo>
      </div>
    </Section>
  );
}
