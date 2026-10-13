import { useState } from 'react';
import { GlassSegmented, GlassSlider, TextExtrude, TextGradientFlow, TextOutlineFill } from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useTr } from '../lang';

const box = { border: '1px solid var(--nx-border)', borderRadius: 'var(--nx-radius-xl)', background: 'var(--nx-surface)' } as const;
const muted = { margin: 0, color: 'var(--nx-text-muted)', fontSize: 'var(--nx-text-sm)' } as const;
const kicker = {
  ...muted,
  fontSize: 'var(--nx-text-xs)',
  fontWeight: 600,
  letterSpacing: 'var(--nx-tracking-wider)',
  textTransform: 'uppercase',
  padding: '.3rem .85rem',
  border: '1px solid var(--nx-border)',
  borderRadius: 'var(--nx-radius-full)',
} as const;

const TIERS = [
  { word: ['SPEED', 'سرعت'], copy: ['Sub-50ms responses across the EU edge', 'پاسخ زیر ۵۰ میلی‌ثانیه در لبهٔ اروپا'], meta: ['99.99% uptime', 'پایداری ۹۹٫۹۹٪'] },
  { word: ['SCALE', 'مقیاس'], copy: ['Up to 248 cores a region, no ticket needed', 'تا ۲۴۸ هسته در هر ناحیه، بدون تیکت'], meta: ['Dublin · Singapore', 'دوبلین · سنگاپور'] },
  { word: ['TRUST', 'اعتماد'], copy: ['Encryption at rest and in transit, on by default', 'رمزگذاری در سکون و عبور، به‌صورت پیش‌فرض'], meta: ['SOC 2 Type II', 'SOC 2 نوع دوم'] },
] as const;

export function TextFxBlocks() {
  const tr = useTr();
  const [speed, setSpeed] = useState(1);
  const [pal, setPal] = useState<'aurora' | 'ocean' | 'dusk'>('aurora');
  const [body, setBody] = useState<'gradient' | 'pattern'>('gradient');
  const [depth, setDepth] = useState(6);
  const [tone, setTone] = useState<'accent' | 'gold' | 'ink'>('accent');

  return (
    <Section
      id="text-fx-blocks"
      eyebrow={tr('جادوی متن', 'Text FX')}
      title={tr('متن که جریان دارد، پر می‌شود و از صفحه بلند می‌شود', 'Text that flows, fills and stands off the page')}
      description={tr(
        'گرادیانی که درون جوهر حروف می‌چرخد و سرعتش دست شماست؛ دورقی که با هاور یا ورود به دید از خط کف پر می‌شود؛ و حروف برجستهٔ سه‌بعدی که زیر نشانگر کج می‌شود. متن هیچ‌جا حرف‌به‌حرف نمی‌شکند — فارسی با اتصالاتش سالم می‌ماند.',
        'A gradient that laps inside the glyph ink at a speed you set; an outline that fills from the baseline on hover or in view; and extruded type that leans under the pointer. The text is never split letter by letter — Persian keeps its joins.',
      )}
      code={{
        react: `<TextGradientFlow as="h2" palette="ocean" speed={2}>Payments that flow</TextGradientFlow>
<TextOutlineFill body="pattern" trigger="inview">Scale</TextOutlineFill>
<TextExtrude as="h3" depth={8} tone="gold">Nexus 2026</TextExtrude>`,
        blade: `<x-nx::text-gradient-flow as="h2" palette="ocean" :speed="2">Payments that flow</x-nx::text-gradient-flow>
<x-nx::text-outline-fill body="pattern" trigger="inview">Scale</x-nx::text-outline-fill>
<x-nx::text-extrude as="h3" :depth="8" tone="gold">Nexus 2026</x-nx::text-extrude>`,
      }}
    >
      <div className="sc-demos">
        <Demo title={tr('جریان گرادیان', 'Gradient flow')} center>
          <div
            style={{
              ...box,
              background: 'radial-gradient(60% 90% at 50% 0%, color-mix(in oklab, var(--nx-violet-400) 12%, transparent), transparent 70%), var(--nx-bg)',
              display: 'grid',
              gap: '1rem',
              justifyItems: 'center',
              textAlign: 'center',
              inlineSize: '100%',
              padding: 'clamp(1.5rem, 4vw, 2.5rem) 1.25rem',
            }}
          >
            <p style={kicker}>{tr('مریدین پی · از امروز در دسترس عمومی', 'Meridian Pay · now generally available')}</p>
            <h3 style={{ margin: 0, font: '800 clamp(2rem, 7vw, 3.25rem)/1.12 var(--nx-font-display)' }}>
              <TextGradientFlow palette={pal} speed={speed}>
                {tr('پرداخت‌هایی که جاری‌اند', 'Payments that flow')}
              </TextGradientFlow>
              <br />
              <TextGradientFlow palette={pal} speed={speed}>
                {tr('در ۴۰ بازار', 'in 40 markets')}
              </TextGradientFlow>
            </h3>
            <p style={{ margin: 0, maxInlineSize: '44ch', font: '700 var(--nx-text-base)/1.6 var(--nx-font-sans)' }}>
              <TextGradientFlow palette={pal} speed={speed}>
                {tr('یک داشبورد برای هر ارزی که تسویه می‌کنید — با تطبیق زندهٔ حساب‌ها.', 'One dashboard for every currency you settle — live reconciliation included.')}
              </TextGradientFlow>
            </p>
          </div>
          <div className="sc-row">
            <GlassSlider aria-label={tr('سرعت جریان', 'Flow speed')} value={speed} onValueChange={setSpeed} min={0.5} max={3} step={0.25} format={(v) => `${v}×`} />
            <GlassSegmented
              aria-label={tr('پالت رنگ', 'Palette')}
              value={pal}
              onValueChange={(v) => setPal(v as 'aurora' | 'ocean' | 'dusk')}
              options={[
                { value: 'aurora', label: tr('شفق', 'Aurora') },
                { value: 'ocean', label: tr('اقیانوس', 'Ocean') },
                { value: 'dusk', label: tr('غروب', 'Dusk') },
              ]}
            />
          </div>
        </Demo>

        <Demo title={tr('دورق که پر می‌شود', 'Outline that fills')} center>
          <div className="sc-row">
            <GlassSegmented
              aria-label={tr('مایهٔ پُرشدن', 'Fill body')}
              value={body}
              onValueChange={(v) => setBody(v as 'gradient' | 'pattern')}
              options={[
                { value: 'gradient', label: tr('گرادیان', 'Gradient') },
                { value: 'pattern', label: tr('الگو', 'Pattern') },
              ]}
            />
          </div>
          <div
            style={{
              ...box,
              display: 'grid',
              gap: '1rem',
              justifyItems: 'center',
              textAlign: 'center',
              inlineSize: '100%',
              padding: 'clamp(1.5rem, 4vw, 2.75rem) 1.25rem',
            }}
          >
            <p style={kicker}>{tr('فیورد کلاد · از همین حالا', 'Fjord Cloud · now live')}</p>
            <TextOutlineFill body={body} trigger="hover" style={{ fontSize: 'clamp(2.2rem, 9vw, 4.25rem)' }}>
              {tr('فرانکفورت', 'FRANKFURT')}
            </TextOutlineFill>
            <p style={muted}>{tr('نشانگر را روی واژه ببرید — پرشدن از خط کف بالا می‌آید.', 'Hover the word — the wipe climbs in from the baseline.')}</p>
          </div>
          <div style={{ display: 'grid', gap: '1rem', gridTemplateColumns: 'repeat(auto-fit, minmax(min(15rem, 100%), 1fr))', inlineSize: '100%' }}>
            {TIERS.map((tier) => (
              <div key={tier.word[1]} style={{ ...box, display: 'grid', gap: '.8rem', justifyItems: 'center', textAlign: 'center', padding: '1.4rem 1rem 1.2rem' }}>
                <TextOutlineFill body={body} trigger="inview" style={{ fontSize: 'clamp(1.9rem, 7vw, 2.8rem)' }}>
                  {tr(tier.word[1], tier.word[0])}
                </TextOutlineFill>
                <p style={muted}>{tr(tier.copy[1], tier.copy[0])}</p>
                <small
                  style={{
                    fontSize: 'var(--nx-text-xs)',
                    fontWeight: 700,
                    padding: '.18rem .7rem',
                    borderRadius: 'var(--nx-radius-full)',
                    background: 'color-mix(in oklab, var(--nx-text) 7%, transparent)',
                  }}
                >
                  {tr(tier.meta[1], tier.meta[0])}
                </small>
              </div>
            ))}
          </div>
        </Demo>

        <Demo title={tr('برجستهٔ سه‌بعدی', 'Extruded type')} center>
          <div
            style={{
              ...box,
              background: 'radial-gradient(70% 90% at 50% 110%, color-mix(in oklab, var(--nx-violet-500) 14%, transparent), transparent 70%), var(--nx-bg)',
              display: 'grid',
              gap: '1rem',
              justifyItems: 'center',
              textAlign: 'center',
              inlineSize: '100%',
              padding: 'clamp(1.75rem, 5vw, 3rem) 1.25rem',
            }}
          >
            <p style={kicker}>{tr('کنفرانس توسعه‌دهندگان · پنجمین سال', 'The developer conference · 5th year')}</p>
            <TextExtrude as="h3" depth={depth} tone={tone} style={{ margin: 0, fontSize: 'clamp(2.2rem, 10vw, 4.5rem)' }}>
              {tr('اوربیت ۲۰۲۶', 'ORBIT 2026')}
            </TextExtrude>
            <p style={muted}>
              {tr('لیسبون · ۹ تا ۱۱ ژوئن', 'Lisbon · June 9–11')} · {tr('ثبت‌نام زودهنگام تا ۳ مه', 'Early bird ends May 3')}
            </p>
          </div>
          <div className="sc-row">
            <GlassSlider aria-label={tr('عمق', 'Depth')} value={depth} onValueChange={setDepth} min={3} max={12} step={1} ticks={10} />
            <GlassSegmented
              aria-label={tr('رنگ', 'Tone')}
              value={tone}
              onValueChange={(v) => setTone(v as 'accent' | 'gold' | 'ink')}
              options={[
                { value: 'accent', label: tr('یاسی', 'Violet') },
                { value: 'gold', label: tr('طلایی', 'Gold') },
                { value: 'ink', label: tr('جوهر', 'Ink') },
              ]}
            />
          </div>
          <p style={muted}>{tr('نشانگر را روی تیتر بچرخانید یا با فلش‌ها کجش کنید — Shift قدم بزرگ‌تر می‌دهد.', 'Glide the pointer across the headline, or tilt it with the arrow keys — Shift steps further.')}</p>
        </Demo>
      </div>
    </Section>
  );
}
