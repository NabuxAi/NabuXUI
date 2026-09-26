import type { CSSProperties } from 'react';
import {
  AreaChart,
  Badge,
  BarChart,
  Bento,
  BentoItem,
  Button,
  Card,
  CuneiformLoader,
  DonutChart,
  FlipCard,
  Grid,
  Icon,
  Marquee,
  PricingTable,
  ProgressRing,
  Sparkline,
  StackCards,
  StatCard,
  SwipeStack,
} from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useLang, useTr } from '../lang';

const glow = (a: string, b: string) => ({ '--sc-glow': `radial-gradient(120% 80% at 50% 110%, ${a}, transparent 60%), radial-gradient(80% 60% at 80% 100%, ${b}, transparent 70%)` }) as CSSProperties;

export function CardsSection() {
  const tr = useTr();
  const team = [
    { name: tr('هانیه رضایی', 'Haniyeh Rezaei'), role: tr('مدیر محصول', 'Product lead'), style: glow('#7c3aed', '#5647e6') },
    { name: tr('آرش کریمی', 'Arash Karimi'), role: tr('مهندس هوش مصنوعی', 'AI engineer'), style: glow('#f4a93c', '#d98a1c') },
    { name: tr('نیلوفر احمدی', 'Niloufar Ahmadi'), role: tr('طراح تجربه', 'Experience designer'), style: glow('#06c7e3', '#6a63f4') },
  ];
  const products = [
    { name: 'NabuDesk', text: tr('پشتیبانی چندکاناله با هوش مصنوعی', 'Omnichannel AI support'), bg: 'radial-gradient(90% 70% at 20% 0%, #5647e6, transparent 70%), radial-gradient(70% 60% at 100% 100%, #7c3aed, transparent 70%)' },
    { name: 'NabuGate', text: tr('درگاه یکپارچهٔ مدل‌های زبانی', 'One gateway for every model'), bg: 'radial-gradient(90% 70% at 80% 0%, #06c7e3, transparent 70%), radial-gradient(70% 60% at 0% 100%, #5647e6, transparent 70%)' },
    { name: 'NabuWrite', text: tr('نوشتن بلند با صدای خود شما', 'Long-form writing in your voice'), bg: 'radial-gradient(90% 70% at 50% 0%, #f4a93c, transparent 70%), radial-gradient(70% 60% at 100% 100%, #d6428a, transparent 70%)' },
    { name: 'NabuVoice', text: tr('پاسخ‌گویی صوتی در تلگرام', 'Voice answers on Telegram'), bg: 'radial-gradient(90% 70% at 0% 0%, #1f9d57, transparent 70%), radial-gradient(70% 60% at 100% 100%, #0f9fb4, transparent 70%)' },
  ];

  return (
    <Section
      id="cards"
      eyebrow={tr('کارت', 'Card')}
      title={tr('کارت‌هایی با عمق و نور', 'Cards with depth and light')}
      description={tr(
        'نوری که زیر نشانگر روشن می‌شود، چرخش سه‌بعدی، کارت دورو، دستهٔ کارت‌هایی که باز می‌شوند و کارت‌هایی که با کشیدن کنار می‌روند.',
        'A light that follows the pointer, 3D tilt, two-sided cards, stacks that fan open and cards you swipe away.',
      )}
      code={{
        react: `<Card spotlight icon="sparkles" title="NabuGate" description="…" href="/gate" />
<Card tilt variant="glass">…</Card>
<FlipCard front={…} back={…} />
<StackCards items={team.map(renderMember)} />
<SwipeStack items={products} renderItem={renderProduct} onSwipe={save} />`,
        blade: `<x-nx::card spotlight icon="sparkles" title="NabuGate" href="/gate">…</x-nx::card>
<x-nx::card tilt variant="glass">…</x-nx::card>
<x-nx::flip-card>
  <x-slot:front>…</x-slot:front>
  <x-slot:back>…</x-slot:back>
</x-nx::flip-card>
<x-nx::stat-card label="درآمد" :value="$revenue" :delta="12.4" :trend="$last12" />`,
      }}
    >
      <div className="sc-demos">
        <Card spotlight interactive icon="sparkles" title={tr('نور زیر نشانگر', 'Pointer spotlight')} description={tr('نشانگر را روی کارت حرکت دهید؛ لبه و سطح کارت با آن روشن می‌شوند.', 'Move across the card: its surface and edge light up under the pointer.')} footer={<Badge tone="success" dot>{tr('فعال', 'Live')}</Badge>} />
        <Card tilt variant="gradient" icon="layers" title={tr('چرخش سه‌بعدی', '3D tilt')} description={tr('کارت به سمت نشانگر خم می‌شود و با فنر به جای خود برمی‌گردد.', 'The card leans toward the pointer and springs back when it leaves.')} />
        <Card variant="glass" icon="shield" title={tr('شیشه‌ای', 'Glass')} description={tr('پس‌زمینهٔ مات با لبهٔ روشن؛ روی تصویر و گرادیان خوش می‌نشیند.', 'Frosted surface with a lit edge that sits well on imagery and gradients.')} />
        <Demo title={tr('کارت دورو', 'Flip card')} center>
          <FlipCard
            trigger="click"
            flipLabel={tr('برگرداندن کارت', 'Flip card')}
            front={<Card title="NabuAuth" icon="lock" description={tr('ورود یکپارچه و کیف پول مشترک برای همهٔ سرویس‌ها.', 'Single sign-on and one shared wallet for every service.')} style={{ minBlockSize: '13rem', inlineSize: '18rem' }} />}
            back={<Card variant="inverse" title={tr('پشت کارت', 'On the back')} description={tr('OAuth2، OpenID Connect و PKCE؛ روی کارت کلیک کنید تا برگردد.', 'OAuth2, OpenID Connect and PKCE. Click the card to turn it back.')} style={{ minBlockSize: '13rem', inlineSize: '18rem' }} />}
          />
        </Demo>
        <Demo title={tr('دستهٔ کارت‌ها', 'Card stack')} center wide>
          <StackCards
            aria-label={tr('تیم', 'Team')}
            items={team.map((member) => (
              <div key={member.name} className="sc-team-card" style={member.style} tabIndex={0}>
                <div>
                  <strong>{member.name}</strong>
                  <br />
                  <span>{member.role}</span>
                </div>
              </div>
            ))}
          />
        </Demo>
        <Demo title={tr('کشیدن برای انتخاب', 'Swipe to decide')}>
          <SwipeStack
            items={products}
            getKey={(item) => item.name}
            renderItem={(item) => (
              <div className="sc-swipe-card" style={{ '--sc-bg': item.bg } as CSSProperties}>
                <h3>{item.name}</h3>
                <p>{item.text}</p>
              </div>
            )}
          />
        </Demo>
        <Demo title={tr('کارت آمار', 'Stat cards')}>
          <StatCard label={tr('درآمد ماهانه', 'Monthly revenue')} value={48260} format={{ style: 'currency', currency: 'USD', maximumFractionDigits: 0 }} delta={12.4} trend={[12, 18, 14, 22, 26, 24, 31, 35, 33, 41, 44, 48]} caption={tr('نسبت به ماه گذشته', 'vs last month')} />
          <StatCard label={tr('هزینهٔ هوش مصنوعی', 'AI spend')} value={1284} format={{ style: 'currency', currency: 'USD' }} delta={8.1} invertDelta trend={[8, 9, 7, 11, 10, 12, 13]} />
        </Demo>
      </div>
    </Section>
  );
}

export function ChartsSection() {
  const tr = useTr();
  const lang = useLang();
  const months = lang === 'fa' ? ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر'] : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'];

  return (
    <Section
      id="charts"
      eyebrow={tr('نمودار', 'Chart')}
      title={tr('داده‌ها، خوانا در هر دو تم', 'Data you can read in both themes')}
      description={tr(
        'پالت نمودارها برای انواع کوررنگی بررسی شده است. هر نمودار جدول داده‌ای برای صفحه‌خوان دارد و با کلیدهای جهت‌نما هم خوانده می‌شود.',
        'The chart palette is validated for colour-blind readers. Every chart carries a data table for screen readers and can be read with the arrow keys.',
      )}
      code={{
        react: `<AreaChart title="Conversations" labels={months}
  series={[{ name: 'Telegram', values: […] }, { name: 'WhatsApp', values: […] }]} />
<BarChart title="Tickets closed" data={[{ label: 'Mon', value: 42 }, …]} />
<DonutChart data={channels} centerLabel="Messages" />
<ProgressRing value={72} label="Quota used" />`,
        blade: `<x-nx::chart.area title="گفت‌وگوها" :labels="$months" :series="$series" />
<x-nx::chart.bar title="تیکت‌های بسته‌شده" :data="$tickets" />
<x-nx::chart.donut :data="$channels" center-label="پیام‌ها" />
<x-nx::progress-ring :value="72" label="سهمیهٔ مصرف‌شده" />`,
      }}
    >
      <div className="sc-demos">
        <Demo wide>
          <AreaChart
            title={tr('گفت‌وگوهای پاسخ‌داده‌شده', 'Conversations answered')}
            subtitle={tr('۹ ماه گذشته، به تفکیک کانال', 'Last 9 months, by channel')}
            labels={months}
            series={[
              { name: tr('تلگرام', 'Telegram'), values: [1200, 1900, 1700, 2600, 3100, 2900, 3800, 4200, 4700] },
              { name: tr('واتس‌اپ', 'WhatsApp'), values: [800, 1100, 1500, 1400, 1900, 2400, 2300, 2900, 3300] },
            ]}
          />
        </Demo>
        <Demo>
          <BarChart
            title={tr('تیکت‌های بسته‌شده', 'Tickets closed')}
            data={(lang === 'fa' ? ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنجشنبه', 'جمعه'] : ['Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri']).map((label, i) => ({ label, value: [42, 58, 51, 67, 73, 38, 21][i]! }))}
          />
        </Demo>
        <Demo>
          <DonutChart
            title={tr('کانال‌ها', 'Channels')}
            centerLabel={tr('پیام', 'messages')}
            data={[
              { label: tr('تلگرام', 'Telegram'), value: 4700 },
              { label: tr('واتس‌اپ', 'WhatsApp'), value: 3300 },
              { label: tr('وب', 'Web'), value: 1800 },
              { label: tr('ایمیل', 'Email'), value: 900 },
            ]}
          />
        </Demo>
        <Demo title={tr('حلقهٔ پیشرفت و خط روند', 'Rings and sparklines')}>
          <div className="sc-row" style={{ justifyContent: 'space-around' }}>
            <ProgressRing value={72} label={tr('سهمیهٔ مصرف‌شده', 'Quota used')} />
            <ProgressRing value={100} tone="success" label={tr('آپلود کامل شد', 'Upload complete')} size="4.5rem" />
            <ProgressRing value={38} tone="warning" label={tr('فضای ذخیره', 'Storage')} size="4.5rem" />
          </div>
          <Sparkline data={[4, 6, 5, 8, 7, 9, 12, 11, 14, 13, 17]} trend="up" />
          <Sparkline data={[17, 15, 16, 12, 13, 10, 9, 11, 7, 6]} trend="down" />
        </Demo>
      </div>
    </Section>
  );
}

export function PricingSection() {
  const tr = useTr();
  const lang = useLang();
  const fa = lang === 'fa';

  return (
    <Section
      id="pricing"
      eyebrow={tr('قیمت‌گذاری', 'Pricing')}
      title={tr('پلن‌ها، با قیمتی که می‌چرخد', 'Plans, with prices that roll')}
      description={tr('جابه‌جایی ماهانه و سالانه با شمارندهٔ چرخان و قاب درخشان برای پلن پیشنهادی.', 'Switch monthly and yearly with rolling numbers and a glowing frame on the recommended plan.')}
      code={{
        react: `<PricingTable plans={plans} yearlyNote="2 months free" />`,
        blade: `<x-nx::pricing :plans="$plans" yearly-note="۲ ماه رایگان" wire:model.live="billing" />`,
      }}
    >
      <PricingTable
        billingLabels={{ monthly: tr('ماهانه', 'Monthly'), yearly: tr('سالانه', 'Yearly') }}
        yearlyNote={tr('۲ ماه رایگان', '2 months free')}
        plans={[
          {
            id: 'starter',
            name: tr('شروع', 'Starter'),
            description: tr('برای کسب‌وکارهای کوچکی که تازه پشتیبانی هوشمند را امتحان می‌کنند.', 'For small teams trying AI support for the first time.'),
            price: fa ? { monthly: 490, yearly: 408 } : { monthly: 19, yearly: 15 },
            currency: fa ? undefined : '$',
            period: { monthly: tr('هزار تومان در ماه', '/month'), yearly: tr('هزار تومان در ماه', '/month') },
            features: [{ label: tr('۱ کانال پیام‌رسان', '1 messaging channel') }, { label: tr('۱٬۰۰۰ پاسخ خودکار', '1,000 automated replies') }, { label: tr('گزارش هفتگی', 'Weekly report') }, { label: tr('ایجنت سفارشی', 'Custom agent'), included: false }],
            cta: { label: tr('شروع رایگان', 'Start free') },
          },
          {
            id: 'pro',
            name: tr('حرفه‌ای', 'Pro'),
            description: tr('برای تیم‌هایی که همهٔ کانال‌ها را یک‌جا جواب می‌دهند.', 'For teams answering every channel in one place.'),
            price: fa ? { monthly: 1490, yearly: 1241 } : { monthly: 49, yearly: 41 },
            currency: fa ? undefined : '$',
            period: { monthly: tr('هزار تومان در ماه', '/month'), yearly: tr('هزار تومان در ماه', '/month') },
            featured: true,
            flag: tr('پیشنهاد ما', 'Most popular'),
            features: [{ label: tr('همهٔ کانال‌ها', 'Every channel') }, { label: tr('۱۰٬۰۰۰ پاسخ خودکار', '10,000 automated replies') }, { label: tr('ایجنت سفارشی', 'Custom agent') }, { label: tr('تحلیل گفت‌وگوها', 'Conversation analytics') }],
            cta: { label: tr('انتخاب حرفه‌ای', 'Choose Pro') },
          },
          {
            id: 'scale',
            name: tr('سازمانی', 'Enterprise'),
            description: tr('برای سازمان‌هایی که استقرار داخلی و قرارداد سطح خدمات می‌خواهند.', 'For organisations that need on-premise deployment and an SLA.'),
            price: fa ? { monthly: 4900, yearly: 4083 } : { monthly: 149, yearly: 124 },
            currency: fa ? undefined : '$',
            period: { monthly: tr('هزار تومان در ماه', '/month'), yearly: tr('هزار تومان در ماه', '/month') },
            features: [{ label: tr('پیام نامحدود', 'Unlimited messages') }, { label: tr('استقرار روی سرور شما', 'Runs on your servers') }, { label: tr('ورود یکپارچه با NabuAuth', 'SSO with NabuAuth') }, { label: tr('پشتیبانی اختصاصی', 'Dedicated support') }],
            cta: { label: tr('گفت‌وگو با فروش', 'Talk to sales') },
          },
        ]}
      />
    </Section>
  );
}

export function GridSection() {
  const tr = useTr();
  const products = ['NabuDesk', 'NabuCRM', 'NabuGate', 'NabuAuth', 'NabuHub', 'NabuWrite', 'NabuChat', 'NabuGen', 'NabuVoice', 'NabuPilot', 'NabuFunnel', 'NabuWatch'];

  return (
    <Section
      id="grid"
      eyebrow={tr('گرید و بنتو', 'Grid & bento')}
      title={tr('چیدمان‌هایی که با اسکرول جان می‌گیرند', 'Layouts that come alive as you scroll')}
      description={tr('خانه‌های گرید یکی‌یکی ظاهر می‌شوند، کاشی‌های بنتو زیر نشانگر روشن می‌شوند و نوار لوگوها بی‌پایان می‌چرخد و با یک دکمه می‌ایستد.', 'Grid cells arrive one by one, bento tiles light up under the pointer, and the logo strip loops forever and stops with one button.')}
      code={{
        react: `<Bento columns={3}>
  <BentoItem colSpan={2} title="…" description="…" media={<Chart />} />
</Bento>
<Grid min="14rem">{cards}</Grid>
<Marquee items={logos} />`,
        blade: `<x-nx::bento columns="3">
  <x-nx::bento-item col-span="2" title="…" description="…" />
</x-nx::bento>
<x-nx::grid min="14rem">…</x-nx::grid>
<x-nx::marquee>…</x-nx::marquee>`,
      }}
    >
      <Bento columns={3}>
        <BentoItem colSpan={2} rowSpan={2} title={tr('یک هسته، سه فریم‌ورک', 'One core, three frameworks')} description={tr('توکن‌ها، CSS و رفتارها یک‌بار نوشته شده‌اند؛ Livewire، Inertia و React فقط آن‌ها را به کار می‌گیرند.', 'Tokens, CSS and behaviours are written once; Livewire, Inertia and React just put them to work.')} media={<div className="sc-bento-art"><CuneiformLoader size="lg" label={tr('نمایش', 'Demo')} /></div>} />
        <BentoItem title={tr('راست‌چین از پایه', 'RTL-first')} description={tr('ویژگی‌های منطقی CSS؛ حرکت‌ها هم با جهت متن برمی‌گردند.', 'Logical CSS properties; even motion flips with the text direction.')} media={<div className="sc-bento-art"><Icon name="arrow-left" style={{ inlineSize: '2.5rem', blockSize: '2.5rem' }} /></div>} />
        <BentoItem title={tr('حرکت با فیزیک فنر', 'Spring physics')} description={tr('فنرها به منحنی linear() در CSS تبدیل شده‌اند؛ بدون کتابخانهٔ جاوااسکریپت.', 'Springs compiled to CSS linear() curves. No JavaScript animation library.')} media={<div className="sc-bento-art"><Icon name="zap" style={{ inlineSize: '2.5rem', blockSize: '2.5rem' }} /></div>} />
        <BentoItem colSpan={3} title={tr('در دسترس برای همه', 'Accessible by default')} description={tr('عناصر بومی، مدیریت فوکوس، برچسب برای صفحه‌خوان و احترام به تنظیم «حرکت کمتر».', 'Native elements, managed focus, screen-reader labels and respect for “reduce motion”.')} />
      </Bento>
      <Grid min="13rem">
        {['sparkles', 'message', 'chart', 'users', 'shield', 'globe', 'cpu', 'wand'].map((icon, i) => (
          <Card key={icon} size="sm" interactive icon={icon as never} title={tr(['نویسندگی', 'پشتیبانی', 'تحلیل', 'CRM', 'امنیت', 'چندزبانه', 'درگاه مدل', 'اتوماسیون'][i]!, ['Writing', 'Support', 'Analytics', 'CRM', 'Security', 'Multilingual', 'Model gateway', 'Automation'][i]!)} />
        ))}
      </Grid>
      <Marquee
        aria-label={tr('محصولات Nabux', 'Nabux products')}
        items={products.map((name) => (
          <span className="sc-logo-chip">
            <span className="sc-logo" aria-hidden="true">
              {name.slice(4, 5)}
            </span>
            {name}
          </span>
        ))}
      />
      <div className="sc-row" style={{ justifyContent: 'center' }}>
        <Button variant="outline" shape="pill" href="#navigation" iconEnd="arrow-right">
          {tr('ادامه: ناوبری', 'Next: navigation')}
        </Button>
      </div>
    </Section>
  );
}
