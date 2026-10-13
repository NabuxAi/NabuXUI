import { useRef, useState } from 'react';
import { Button, ShaderNoise, ShaderPlasma, ShaderRipples, type ShaderColors, type ShaderRipplesHandle } from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useTr } from '../lang';

/* Themed frosted card that stays readable on the art in both themes. */
const panel = {
  background: 'color-mix(in oklab, var(--nx-surface) 80%, transparent)',
  border: '1px solid var(--nx-border)',
  backdropFilter: 'blur(14px)',
  WebkitBackdropFilter: 'blur(14px)',
  borderRadius: 'var(--nx-radius-xl)',
  boxShadow: 'var(--nx-shadow-xl)',
  color: 'var(--nx-text)',
} as const;

const chip = {
  padding: '0.3rem 0.7rem',
  borderRadius: 'var(--nx-radius-full)',
  border: '1px solid var(--nx-border)',
  background: 'color-mix(in oklab, var(--nx-surface) 55%, transparent)',
  backdropFilter: 'blur(10px)',
  WebkitBackdropFilter: 'blur(10px)',
  fontSize: 'var(--nx-text-xs)',
  fontWeight: 600,
  fontVariantNumeric: 'tabular-nums',
} as const;

const eyebrow = { margin: 0, fontSize: 'var(--nx-text-xs)', fontWeight: 600, letterSpacing: 'var(--nx-tracking-wide)', textTransform: 'uppercase', color: 'var(--nx-text-muted)' } as const;

function PlasmaDemo() {
  const tr = useTr();
  const palettes: Array<{ label: string; colors: ShaderColors }> = [
    { label: tr('شفق', 'Aurora'), colors: ['var(--nx-lapis-950)', 'var(--nx-violet-500)', 'var(--nx-cyan-300)'] },
    { label: tr('غروب', 'Sunset'), colors: ['var(--nx-ink-950)', 'var(--nx-red-600)', 'var(--nx-gold-300)'] },
    { label: tr('اقیانوس', 'Ocean'), colors: ['var(--nx-ink-950)', 'var(--nx-cyan-700)', 'var(--nx-cyan-300)'] },
    { label: tr('ماگما', 'Magma'), colors: ['var(--nx-ink-950)', 'var(--nx-red-500)', 'var(--nx-amber-400)'] },
  ];
  const [pick, setPick] = useState(0);
  const colors = palettes[pick]!.colors;

  return (
    <div style={{ display: 'grid', gap: 'var(--nx-space-4)' }}>
      <ShaderPlasma colors={colors} speed={0.9} style={{ minBlockSize: '22rem' }}>
        <div className="nx-shader-content" data-align="center" data-justify="center">
          <article style={{ ...panel, display: 'grid', gap: 'var(--nx-space-3)', justifyItems: 'center', padding: 'var(--nx-space-6)', maxInlineSize: '34rem', textAlign: 'center' }}>
            <p style={eyebrow}>{tr('سولستیس ۳.۰ · عرضهٔ جهانی', 'Solstice 3.0 · global launch')}</p>
            <h3 style={{ margin: 0, fontSize: 'var(--nx-text-2xl)', lineHeight: 1.35 }}>{tr('هر عرضه، همه‌جا، هم‌زمان', 'Every launch, everywhere, at once')}</h3>
            <p style={{ margin: 0, color: 'var(--nx-text-muted)', fontSize: 'var(--nx-text-sm)', lineHeight: 1.8, maxInlineSize: '42ch' }}>
              {tr(
                'داشبورد عرضهٔ شما از ثانیهٔ صفر زنده است — ترافیک، ثبت‌نام و نرخ خطا روی یک نقشه، از فرانکفورت تا سنگاپور.',
                'Your launch dashboard is live from second zero — traffic, signups and error rates on one map, Frankfurt to Singapore.',
              )}
            </p>
            <div className="sc-row" style={{ justifyContent: 'center' }}>
              <span style={chip}>Frankfurt 14ms</span>
              <span style={chip}>Dublin 21ms</span>
              <span style={chip}>Singapore 29ms</span>
            </div>
            <Button variant="primary" onClick={() => setPick((pick + 1) % palettes.length)}>
              {tr('شروع رایگان', 'Start free')}
            </Button>
          </article>
        </div>
      </ShaderPlasma>

      <div className="sc-row" role="group" aria-label={tr('پالت پلاسما', 'Plasma palette')} style={{ justifyContent: 'center', flexWrap: 'wrap' }}>
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

function NoiseDemo() {
  const tr = useTr();
  const [scale, setScale] = useState(1.6);
  const [speed, setSpeed] = useState(0.8);

  const field = (name: string, value: number, min: number, max: number, set: (next: number) => void, label: string) => (
    <label style={{ display: 'grid', gap: '0.4rem', minInlineSize: '11rem', flex: '1 1 11rem' }}>
      <span style={{ display: 'flex', alignItems: 'baseline', justifyContent: 'space-between', gap: '0.75rem', fontSize: 'var(--nx-text-sm)', fontWeight: 600 }}>
        {name}
        <output style={{ color: 'var(--nx-text-muted)', fontVariantNumeric: 'tabular-nums' }}>{value.toFixed(1)}</output>
      </span>
      <input
        type="range"
        min={min}
        max={max}
        step={0.1}
        value={value}
        aria-label={label}
        onChange={(event) => set(Number(event.target.value))}
        style={{ inlineSize: '100%', margin: 0, accentColor: 'var(--nx-accent)' }}
      />
    </label>
  );

  return (
    <ShaderNoise scale={scale} speed={speed} style={{ minBlockSize: '23rem' }}>
      <div className="nx-shader-content" data-align="between">
        <div style={{ ...panel, display: 'grid', gap: 'var(--nx-space-2)', justifyItems: 'start', padding: 'var(--nx-space-5)', maxInlineSize: '34rem' }}>
          <p style={eyebrow}>{tr('لبهٔ توزیع‌شده · موتورخانه', 'Distributed edge · engine room')}</p>
          <h3 style={{ margin: 0, fontSize: 'var(--nx-text-2xl)', lineHeight: 1.4 }}>{tr('زیرساختی که نمی‌خوابد', 'Infrastructure that never rests')}</h3>
          <p style={{ margin: 0, color: 'var(--nx-text-muted)', fontSize: 'var(--nx-text-sm)', lineHeight: 1.85 }}>
            {tr(
              'هر ثانیه ۲.۱ میلیون درخواست را در ۴۲ منطقه جابه‌جا می‌کنیم — از دوبلین تا سنگاپور. این نویز، تپش همان شبکه است.',
              'We route 2.1M requests a second across 42 regions — Dublin to Singapore. This noise is that network’s pulse.',
            )}
          </p>
          <div className="sc-row">
            <span style={chip}>99.98% uptime</span>
            <span style={chip}>{tr('۴۲ منطقه', '42 regions')}</span>
            <span style={chip}>76ms p95</span>
          </div>
        </div>
        <div style={{ ...panel, display: 'flex', flexWrap: 'wrap', gap: '1rem 2rem', padding: 'var(--nx-space-3) var(--nx-space-4)' }}>
          {field(tr('مقیاس', 'Scale'), scale, 0.6, 3, setScale, tr('مقیاس میدان', 'Field scale'))}
          {field(tr('سرعت', 'Speed'), speed, 0, 2, setSpeed, tr('سرعت میدان', 'Field speed'))}
        </div>
      </div>
    </ShaderNoise>
  );
}

function PondDemo() {
  const tr = useTr();
  const pond = useRef<ShaderRipplesHandle>(null);
  const [waves, setWaves] = useState(0);

  return (
    <div style={{ display: 'grid', gap: 'var(--nx-space-4)' }}>
      <div style={{ position: 'relative' }}>
        <ShaderRipples
          ref={pond}
          onWavesChange={setWaves}
          label={tr('برکهٔ تعاملی؛ برای انداختن موج کلیک کنید یا Enter را بزنید', 'Interactive pond; click or press Enter to drop a wave')}
        />
        <span
          style={{
            position: 'absolute',
            insetBlockStart: 'var(--nx-space-3)',
            insetInlineStart: 'var(--nx-space-3)',
            zIndex: 1,
            padding: '0.3rem 0.75rem',
            borderRadius: 'var(--nx-radius-full)',
            background: 'color-mix(in oklab, var(--nx-ink-50) 14%, transparent)',
            border: '1px solid color-mix(in oklab, var(--nx-ink-50) 22%, transparent)',
            color: 'var(--nx-ink-50)',
            fontSize: 'var(--nx-text-xs)',
            pointerEvents: 'none',
          }}
        >
          {tr('موج بیندازید — کلیک، لمس یا Enter', 'Drop a wave — click, tap or Enter')}
        </span>
      </div>

      <div className="sc-row" style={{ justifyContent: 'center', flexWrap: 'wrap' }}>
        <Button size="sm" variant="secondary" onClick={() => pond.current?.rain(1)}>
          {tr('یک قطره', 'Drop')}
        </Button>
        <Button size="sm" variant="secondary" onClick={() => pond.current?.rain(3)}>
          {tr('سه قطره', 'Triple drop')}
        </Button>
        <Button size="sm" variant="ghost" onClick={() => pond.current?.calm()}>
          {tr('آرام‌کردن سطح', 'Calm the surface')}
        </Button>
        <span role="status" style={{ paddingInline: 'var(--nx-space-2)', color: 'var(--nx-text-muted)', fontSize: 'var(--nx-text-xs)', fontVariantNumeric: 'tabular-nums' }}>
          {tr(`${waves} موج فعال`, `${waves} live ${waves === 1 ? 'wave' : 'waves'}`)}
        </span>
      </div>
    </div>
  );
}

export function ShadersBlocks() {
  const tr = useTr();
  return (
    <Section
      id="shaders-blocks"
      eyebrow={tr('شیدرها', 'Shaders')}
      title={tr('هنر WebGL خام، بدون وام‌گیری', 'Raw WebGL art, nothing borrowed')}
      description={tr(
        'یک موتور WebGL1 کوچک در هسته: کامپایل، حلقهٔ رندر که خارج از دید پارک می‌شود، قاب ثابت در حرکت کاهش‌یافته و فالبک گرادیانی بدون WebGL. پلاسما با موس خم می‌شود، نویز با اسلایدر کوک می‌شود و برکه به هر لمس موج می‌اندازد.',
        'One small WebGL1 engine in the core: compile, a render loop that parks off screen, a still frame under reduced motion and a gradient fallback without WebGL. The plasma bends with the pointer, the noise retunes with sliders, and the pond answers every touch with a wave.',
      )}
      code={{
        react: `<ShaderPlasma colors={['var(--nx-lapis-950)', 'var(--nx-violet-500)', 'var(--nx-cyan-300)']}>
  <div className="nx-shader-content" data-align="center" data-justify="center">…</div>
</ShaderPlasma>

<ShaderNoise scale={1.6} speed={0.8} />

<ShaderRipples ref={pond} onWavesChange={setWaves} label="Interactive pond" />`,
        blade: `{{-- core CSS + JS until the Blade components land --}}
<div class="nx-shader nx-shader-pond" data-fallback tabindex="0" role="img"
     aria-label="Interactive pond">
    <canvas class="nx-shader-canvas" aria-hidden="true"></canvas>
    <div class="nx-shader-fallback" aria-hidden="true"></div>
</div>

<script type="module">
    import { shaderStage, ripplesFrag } from '@nabuxai/ui-core';
    shaderStage(document.querySelector('.nx-shader-pond'), { frag: ripplesFrag });
</script>`,
      }}
    >
      <div className="sc-demos">
        <Demo wide bare title={tr('هیرویی که می‌جوشد', 'A hero that boils')}>
          <PlasmaDemo />
        </Demo>

        <Demo wide bare title={tr('بخشی که نفس می‌کشد', 'A section that breathes')}>
          <NoiseDemo />
        </Demo>

        <Demo wide bare title={tr('برکه‌ای که جواب می‌دهد', 'A pond that answers')}>
          <PondDemo />
        </Demo>
      </div>
    </Section>
  );
}
