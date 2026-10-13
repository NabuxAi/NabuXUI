import { useState } from 'react';
import { HeroMarquee, HeroSplit, HeroSpotlight, type HeroMarqueeLogo } from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useTr } from '../lang';

/** Neutral wordmarks for the belt; the marks come from the built-in geometric set. */
const LOGOS: HeroMarqueeLogo[] = [
  { name: 'Nordhaus' },
  { name: 'Kitework' },
  { name: 'Fjordline' },
  { name: 'Bluepeak' },
  { name: 'Halden' },
  { name: 'Montra' },
  { name: 'Vektor' },
  { name: 'Auralis' },
];

export function HeroesBlocks() {
  const tr = useTr();
  const [joined, setJoined] = useState(false);
  const [building, setBuilding] = useState(false);
  const [seated, setSeated] = useState(false);

  return (
    <Section
      id="heroes-blocks"
      eyebrow={tr('هیروها', 'Heroes')}
      title={tr('اولین صفحهٔ واقعی', 'A real first screen')}
      description={tr(
        'سه هیرو از دموهای پلی‌گراوند تقطیر شده‌اند: صحنهٔ مرکب با هالهٔ نوری که دنبال موس می‌آید، چیدمان دوستونه با ماکاپ کج و چیپ‌های شناور، و تیتر با نوار بی‌نهایت لوگوها. نور و کج‌شدن رفتار هستهٔ موجودند (spotlight و tilt) — این خانواده رفتار خودش را ندارد و متن‌ها همه از props می‌آیند.',
        'Three heroes distilled from the playground demos: an ink stage whose halo of light follows the pointer, a split layout with a tilted mockup and floating chips, and a title over an endless logo belt. The light and the tilt ride the existing core behaviours (spotlight, tilt) — the family ships no behaviour of its own, and every word arrives through props.',
      )}
      code={{
        react: `<HeroSpotlight
  badge="Asterly 3.0 — generally available"
  title={<>Ship with the <em>lights on</em></>}
  subtitle="Every deploy under one calm surface."
  primary={{ label: 'Start free', iconEnd: 'arrow-right' }}
  secondary={{ label: 'Watch the 4-min tour', icon: 'play' }}
  trust={[{ icon: 'shield', label: 'SOC 2 Type II' }, …]}
/>
<HeroSplit
  title={<>Your <em>console</em> for every rollout</>}
  chips={[{ icon: 'zap', label: 'Traces in 12ms' }, …]}
  note="Free for 30 days · no credit card"
/>
<HeroMarquee
  logos={[{ name: 'Nordhaus' }, { name: 'Kitework' }, …]}
  logoLabel="Trusted by shipping teams at"
  speed={32}
/>`,
        blade: `<x-nx::spotlight-hero badge="Asterly 3.0 — generally available">
  <x-slot:title>Ship with the <em>lights on</em></x-slot:title>
  <x-slot:subtitle>Every deploy under one calm surface.</x-slot:subtitle>
  <x-slot:trust>:trust="$trust"</x-slot:trust>
</x-nx::spotlight-hero>
<x-nx::split-hero title="Your console for every rollout" :chips="$chips" note="Free for 30 days · no credit card" />
<x-nx::marquee-hero :logos="$logos" logo-label="Trusted by shipping teams at" :speed="32" />`,
      }}
    >
      <div className="sc-demos">
        <Demo wide bare>
          <HeroSpotlight
            as="h2"
            badge={tr('استرلی ۳٫۰ — اکنون به‌صورت عمومی', 'Asterly 3.0 — generally available')}
            title={
              <>
                {tr('با نورِ', 'Ship with the ')}
                <em>{tr('کامل', 'lights on')}</em>
                {tr(' منتشر کن', '')}
              </>
            }
            subtitle={tr(
              'استرلی هر استقرار در فرانکفورت، دوبلین و سنگاپور را زیر یک نور می‌گیرد — تریس‌ها، لاگ‌ها و انتشارها روی یک سطحِ آرام.',
              'Asterly puts every deploy in Frankfurt, Dublin and Singapore under one beam — traces, logs and releases on a single calm surface.',
            )}
            primary={{
              label: joined ? tr('خوش آمدی', 'Welcome aboard') : tr('شروع رایگان', 'Start free'),
              iconEnd: 'arrow-right',
              onClick: () => setJoined(!joined),
            }}
            secondary={{ label: tr('تور چهاردقیقه‌ای', 'Watch the 4-min tour'), icon: 'play' }}
            trust={[
              { icon: 'shield', label: tr('SOC 2 نوع دوم', 'SOC 2 Type II') },
              { icon: 'check-circle', label: tr('آپ‌تایم ۹۹٫۹۹٪ با SLA', '99.99% uptime SLA') },
              { icon: 'users', label: tr('بیش از ۴٬۲۰۰ تیم', '4,200+ teams') },
            ]}
          />
        </Demo>

        <Demo wide bare>
          <HeroSplit
            as="h2"
            badge={tr('برای تیم‌های پلتفرم', 'For platform teams')}
            title={
              <>
                {tr('', 'Your ')}
                <em>{tr('کنسولِ', 'console')}</em>
                {tr(' تو برای هر انتشار', ' for every rollout')}
              </>
            }
            subtitle={tr(
              'استرلی بین پایپ‌لاین و کاربرانت می‌نشیند — انتشار تدریجی، تریس زنده و واگردِ تک‌کلیکی روی یک صفحه.',
              'Asterly sits between your pipeline and your users — progressive delivery, live traces and one-click rollback on a single plane.',
            )}
            primary={{
              label: building ? tr('سندباکس آماده است', 'Sandbox ready') : tr('شروع ساخت', 'Start building'),
              iconEnd: 'arrow-right',
              onClick: () => setBuilding(!building),
            }}
            secondary={{ label: tr('گفت‌وگو با یک مهندس', 'Talk to an engineer'), icon: 'message' }}
            note={tr('۳۰ روز رایگان · بدون کارت بانکی · استقرار از گیتهاب اورگِ خودت', 'Free for 30 days · no credit card · deploys from your own GitHub org')}
            chips={[
              { icon: 'zap', label: tr('تریس در ۱۲ms', 'Traces in 12ms') },
              { icon: 'check-circle', label: tr('انتشار بدون داون‌تایم', 'Zero-downtime rollouts') },
              { icon: 'layers', label: tr('شاخهٔ پیش‌نمایش برای هر PR', 'Preview branches per PR') },
            ]}
          />
        </Demo>

        <Demo wide bare>
          <HeroMarquee
            as="h2"
            logos={LOGOS}
            speed={32}
            logoLabel={tr('مورد اعتمادِ تیم‌های ارسال در', 'Trusted by shipping teams at')}
            badge={tr('سری A — با رهبری نورث‌لاین ونچرز', 'Series A — led by Northline Ventures')}
            title={
              <>
                {tr('جایی که تیم‌های ', 'Where ')}
                <em>{tr('سریع', 'fast')}</em>
                {tr(' منتشر می‌کنند', ' teams ship')}
              </>
            }
            subtitle={tr(
              'استرلی لایهٔ انتشارِی است که تیم‌های مهندسی به آن ساعت‌های شلوغشان را می‌سپارند — از استارت‌آپ تورنتو تا ناوگان جهانی سنگاپور.',
              'Asterly is the rollout layer engineering teams trust with their busiest hours — from seed-stage in Toronto to global fleets in Singapore.',
            )}
            primary={{
              label: seated ? tr('صندلی رزرو شد', 'Seat reserved') : tr('شروع رایگان', 'Start free'),
              iconEnd: 'arrow-right',
              onClick: () => setSeated(!seated),
            }}
            secondary={{ label: tr('خواندن بنچمارک', 'Read the benchmark'), icon: 'chart' }}
            note={tr('بدون کارت بانکی · لغو با یک کلیک', 'No credit card · cancel with one click')}
          />
        </Demo>
      </div>
    </Section>
  );
}
