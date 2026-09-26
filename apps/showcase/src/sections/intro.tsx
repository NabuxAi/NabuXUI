import { useState } from 'react';
import {
  type BackdropVariant,
  Button,
  GradientText,
  Hero,
  Highlight,
  NumberTicker,
  ScrambleText,
  SegmentedControl,
  ShimmerText,
  TextReveal,
  WordRotate,
} from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useLang, useTr } from '../lang';

export function HeroSection() {
  const tr = useTr();
  const [backdrop, setBackdrop] = useState<BackdropVariant>('aurora');

  return (
    <div style={{ position: 'relative' }}>
      <Hero
        backdrop={backdrop}
        eyebrow={{ badge: tr('تازه', 'New'), label: tr('NabuXUI نسخهٔ ۰٫۱ منتشر شد', 'NabuXUI 0.1 is out'), href: '#text' }}
        title={
          <>
            <TextReveal>{tr('یک زبان حرکت برای', 'One motion language for')}</TextReveal>{' '}
            <GradientText variant="brand">
              <Highlight variant="underline" delay={900}>
                {tr('همهٔ محصولات Nabux', 'every Nabux product')}
              </Highlight>
            </GradientText>
          </>
        }
        subtitle={tr(
          'کامپوننت‌های انیمیشنی و دسترس‌پذیر برای Livewire، Inertia و React، روی یک هستهٔ مشترک. راست‌چین از پایه، با تم روشن و تیره.',
          'Animated, accessible components for Livewire, Inertia and React on one shared core. Right-to-left from the ground up, light and dark.',
        )}
        actions={
          <>
            <Button variant="primary" size="lg" shape="pill" effect="shine" iconEnd="arrow-right" href="#buttons">
              {tr('کامپوننت‌ها را ببینید', 'Browse components')}
            </Button>
            <Button variant="glow" size="lg" shape="pill" icon="command" magnetic onClick={() => window.dispatchEvent(new CustomEvent('sc-command'))}>
              {tr('جست‌وجوی سریع', 'Quick search')}
            </Button>
          </>
        }
      >
        <SegmentedControl
          size="sm"
          aria-label={tr('پس‌زمینه', 'Backdrop')}
          value={backdrop}
          onValueChange={(value) => setBackdrop(value as BackdropVariant)}
          options={[
            { value: 'aurora', label: tr('شفق', 'Aurora') },
            { value: 'grid', label: tr('شبکه', 'Grid') },
            { value: 'stars', label: tr('ستاره‌ها', 'Stars') },
            { value: 'beams', label: tr('پرتو', 'Beams') },
            { value: 'dots', label: tr('نقطه', 'Dots') },
          ]}
        />
      </Hero>
    </div>
  );
}

export function TextSection() {
  const tr = useTr();
  const lang = useLang();
  const [value, setValue] = useState(12840);

  return (
    <Section
      id="text"
      eyebrow={tr('متن', 'Text')}
      title={tr('متنی که با خواننده حرکت می‌کند', 'Type that moves with the reader')}
      description={tr(
        'گرادیان، درخشش، ظاهرشدن کلمه‌به‌کلمه، رمزگشایی و عددهایی که می‌چرخند. متن فارسی همیشه کلمه‌به‌کلمه تقسیم می‌شود تا اتصال حروف نشکند.',
        'Gradients, shimmer, word-by-word reveals, decoding and rolling numbers. Persian text is always split by word, so its letters stay joined.',
      )}
      code={{
        react: `<TextReveal as="h2">Ancient wisdom, modern intelligence</TextReveal>
<ShimmerText>Thinking…</ShimmerText>
<ScrambleText charset="cuneiform">NABU</ScrambleText>
<WordRotate words={['agents', 'automations', 'insights']} />
<NumberTicker value={12840} format={{ notation: 'compact' }} />
<Highlight variant="circle">every product</Highlight>`,
        blade: `<x-nx::text-reveal as="h2">خرد کهن، هوش امروز</x-nx::text-reveal>
<x-nx::shimmer>در حال فکر…</x-nx::shimmer>
<x-nx::scramble charset="cuneiform">NABU</x-nx::scramble>
<x-nx::word-rotate :words="['ایجنت‌ها', 'اتوماسیون', 'بینش']" />
<x-nx::number :value="$revenue" />
<x-nx::highlight variant="circle">همهٔ محصولات</x-nx::highlight>`,
      }}
    >
      <div className="sc-demos">
        <Demo title={tr('ظاهرشدن کلمه‌به‌کلمه', 'Word-by-word reveal')}>
          <TextReveal as="p" style={{ margin: 0, fontSize: 'var(--nx-text-2xl)', fontWeight: 700, lineHeight: 1.5 }}>
            {tr('نبو، کاتب خدایان، حالا برای شما می‌نویسد.', 'Nabu, the scribe of the gods, now writes for you.')}
          </TextReveal>
          <TextReveal as="p" by="char" style={{ margin: 0, color: 'var(--nx-text-muted)' }}>
            Character by character, in Latin.
          </TextReveal>
        </Demo>
        <Demo title={tr('درخشش و گرادیان', 'Shimmer and gradient')}>
          <ShimmerText style={{ fontSize: 'var(--nx-text-xl)', fontWeight: 600 }}>{tr('نبو در حال فکر کردن است…', 'Nabu is thinking…')}</ShimmerText>
          <ShimmerText variant="wave" style={{ fontSize: 'var(--nx-text-xl)', fontWeight: 600 }}>
            Generating your answer
          </ShimmerText>
          <GradientText animate style={{ fontSize: 'var(--nx-text-3xl)', fontWeight: 800 }}>
            {tr('هوش مصنوعی کاتب', 'The scribe AI')}
          </GradientText>
        </Demo>
        <Demo title={tr('رمزگشایی', 'Decode')}>
          <ScrambleText trigger="hover" style={{ fontSize: 'var(--nx-text-2xl)', fontWeight: 700, fontFamily: 'var(--nx-font-mono)' }}>
            NABUXAI · SCRIBE
          </ScrambleText>
          <ScrambleText trigger="view" charset="persian" style={{ fontSize: 'var(--nx-text-xl)', fontWeight: 700 }}>
            {tr('رمز لوح گلی باز شد', 'The clay tablet is decoded')}
          </ScrambleText>
          <p className="sc-demo-title">{tr('روی خط اول نشانگر را ببرید.', 'Hover the first line.')}</p>
        </Demo>
        <Demo title={tr('کلمه‌های چرخان', 'Rotating words')}>
          <p style={{ margin: 0, fontSize: 'var(--nx-text-2xl)', fontWeight: 700 }}>
            {tr('ساخته شده برای ', 'Built for ')}
            <GradientText>
              <WordRotate words={lang === 'fa' ? ['ایجنت‌ها', 'پشتیبانی', 'نویسندگی', 'تحلیل داده'] : ['agents', 'support', 'writing', 'analytics']} />
            </GradientText>
          </p>
        </Demo>
        <Demo title={tr('عدد چرخان', 'Rolling number')}>
          <div style={{ fontSize: 'var(--nx-text-5xl)', fontWeight: 800 }}>
            <NumberTicker value={value} />
          </div>
          <div className="sc-row">
            <Button size="sm" icon="plus" onClick={() => setValue((v) => v + Math.round(Math.random() * 4000))}>
              {tr('افزایش', 'Add')}
            </Button>
            <Button size="sm" variant="ghost" icon="minus" onClick={() => setValue((v) => Math.max(0, v - Math.round(Math.random() * 3000)))}>
              {tr('کاهش', 'Remove')}
            </Button>
          </div>
        </Demo>
        <Demo title={tr('علامت‌گذاری دستی', 'Hand-drawn marks')}>
          <p style={{ margin: 0, fontSize: 'var(--nx-text-xl)', lineHeight: 2.2 }}>
            {tr('پاسخ‌ها ', 'Answers ')}
            <Highlight>{tr('دقیق', 'precise')}</Highlight>
            {tr('، ', ', ')}
            <Highlight variant="circle" delay={300}>
              {tr('سریع', 'fast')}
            </Highlight>
            {tr(' و ', ' and ')}
            <Highlight variant="marker" delay={600}>
              {tr('به زبان خودتان', 'in your language')}
            </Highlight>
            .
          </p>
        </Demo>
      </div>
    </Section>
  );
}
