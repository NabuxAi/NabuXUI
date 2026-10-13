import { useState } from 'react';
import { FaqSection, LogosTrust, PricingSection, StatsBand, TestimonialWall } from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useTr } from '../lang';

export function SectionsBlocks() {
  const tr = useTr();
  const [interval, setInterval] = useState<'monthly' | 'yearly'>('yearly');

  return (
    <Section
      id="sections-blocks"
      eyebrow={tr('بخش‌های لندینگ', 'Landing sections')}
      title={tr('پنج بخشی که هر صفحهٔ محصولی لازم دارد', 'The five sections every product page needs')}
      description={tr(
        'جدول قیمت با تاگل ماهانه/سالانه و مقایسهٔ پلن‌ها، پرسش‌های پرتکرار با آکاردئون نرم تک‌بازشونده، نوار آمار تیره با شمارنده‌هایی که با اسکرول می‌غلتند، گرید لوگوهای خاکستری که با هاور رنگ می‌گیرند و دیوار نقل‌قول‌ها با نقل‌قول درشت و کارت‌های ستاره‌دار. همه‌چیز از توکن‌ها ساخته شده و در هر دو تم و هر دو جهت درست کار می‌کند.',
        'The pricing table with its monthly/yearly toggle and plan comparison, the FAQ with its soft single-open accordion, the dark stats band whose counters roll in as you scroll, the grayscale logos grid that takes its colour back on hover, and the testimonial wall with one big quote over star-rated cards. Everything is built from tokens and works in both themes and both directions.',
      )}
      code={{
        react: `<PricingSection interval={interval} onIntervalChange={setInterval}
  plans={[{ id: 'growth', name: 'Growth', monthly: 29, yearly: 23, featured: true, perks: ['10 team seats', …] }]}
  comparison={[{ label: 'SSO / SAML', values: [false, false, true] }]} />
<FaqSection items={[{ id: 'trial', question: '…', answer: '…' }]} defaultOpen="trial"
  ctaLabel="Talk to sales" ctaHref="mailto:sales@example.com" />
<StatsBand stats={[{ value: 99.99, unit: '%', format: { minimumFractionDigits: 2 }, label: 'measured uptime' }]} />
<LogosTrust logos={[{ id: 'lumina', name: 'Lumina', mark: 'ring', tone: 'cyan' }, …]} mode="marquee" />
<TestimonialWall quotes={quotes} shorts={shorts} />`,
        blade: `{{-- ui-core ships the CSS; the markup is the same classes --}}
<section class="nx-pricing-section" …>
  <div class="nx-pricing-section-toggle" data-interval="yearly" role="group" aria-label="Billing interval">…</div>
  <div class="nx-pricing-section-cards">…</div>
</section>
<section class="nx-faq">
  <div class="nx-faq-side">…</div>
  <div class="nx-faq-list">
    <div class="nx-faq-item" data-open>…</div>
  </div>
</section>
<section class="nx-stats-band" data-nx-reveal="group">…</section>`,
      }}
    >
      <div className="sc-demos">
        <Demo wide title={tr('جدول قیمت', 'The pricing table')} bare>
          <PricingSection
            interval={interval}
            onIntervalChange={setInterval}
            toggleLabel={tr('دورهٔ پرداخت', 'Billing interval')}
            compareLabel={tr('مقایسهٔ ویژگی‌ها', 'Feature comparison')}
            eyebrow={tr('مریدین · صورت‌حساب', 'Meridian · Billing')}
            title={tr('قیمتی که با شما مقیاس می‌گیرد، نه علیه شما', 'Pricing that scales with you, not against you')}
            subtitle={tr(
              'از رایگان شروع کنید و با رشد تیم ارتقا بدهید. همهٔ پلن‌ها روی همان زیرساخت اجرا می‌شوند — فرانکفورت، دوبلین و سنگاپور — فقط سقف‌ها فرق دارند.',
              'Start free and upgrade as the team grows. Every plan runs on the same infrastructure — Frankfurt, Dublin and Singapore — only the limits differ.',
            )}
            monthlyLabel={tr('ماهانه', 'Monthly')}
            yearlyLabel={tr('سالانه', 'Yearly')}
            savingsLabel={tr('۲ ماه رایگان', '2 months free')}
            featuredLabel={tr('محبوب‌ترین', 'Most popular')}
            priceSuffix={tr('به‌ازای هر نفر / ماه', '/ seat / mo')}
            includedLabel={tr('موجود', 'Included')}
            notIncludedLabel={tr('موجود نیست', 'Not included')}
            compareTitle={tr('مقایسهٔ آنچه هر پلن در بر دارد', 'Compare what each plan includes')}
            note={tr(
              'قیمت‌ها بدون مالیات بر ارزش افزوده است. تغییر پلن تا دقیقه دقیق‌حساب می‌شود — بدون ری‌استارت، بدون مهاجرت.',
              'Prices exclude VAT. Switching plans is prorated to the minute — no restarts, no migrations.',
            )}
            plans={[
              {
                id: 'starter',
                name: tr('استارتر', 'Starter'),
                tag: tr('برای پروژه‌های شخصی', 'For side projects'),
                monthly: 12,
                yearly: 9,
                perks: [tr('۳ عضو تیم', '3 team seats'), tr('۵ پروژهٔ فعال', '5 active projects'), tr('۵۰ هزار درخواست API در ماه', '50K API calls / mo'), tr('پشتیبانی انجمن', 'Community support')],
                ctaLabel: tr('انتخاب استارتر', 'Choose Starter'),
                ctaHref: '/components',
              },
              {
                id: 'growth',
                name: tr('رشد', 'Growth'),
                tag: tr('برای تیم‌های در حال عرضه', 'For shipping teams'),
                monthly: 29,
                yearly: 23,
                featured: true,
                perks: [
                  tr('۱۰ عضو تیم', '10 team seats'),
                  tr('۲۵ پروژهٔ فعال', '25 active projects'),
                  tr('۵۰۰ هزار درخواست API در ماه', '500K API calls / mo'),
                  tr('پشتیبانی اولویت‌دار', 'Priority support'),
                  tr('هشدار مصرف', 'Usage alerts'),
                ],
                ctaLabel: tr('شروع دورهٔ ۱۴ روزهٔ آزمایشی', 'Start the 14-day trial'),
                ctaHref: '/components',
              },
              {
                id: 'scale',
                name: tr('مقیاس', 'Scale'),
                tag: tr('برای پلتفرم‌ها', 'For platforms'),
                monthly: 79,
                yearly: 63,
                perks: [tr('عضو نامحدود', 'Unlimited seats'), tr('پروژهٔ نامحدود', 'Unlimited projects'), 'SSO / SAML', tr('گزارش حسابرسی', 'Audit log'), tr('مهندس اختصاصی', 'Dedicated engineer')],
                ctaLabel: tr('انتخاب مقیاس', 'Choose Scale'),
                ctaHref: '/components',
              },
            ]}
            comparison={[
              { label: tr('عضو تیم', 'Team seats'), values: [tr('۳', '3'), tr('۱۰', '10'), tr('نامحدود', 'Unlimited')] },
              { label: tr('پروژهٔ فعال', 'Active projects'), values: [tr('۵', '5'), tr('۲۵', '25'), tr('نامحدود', 'Unlimited')] },
              { label: tr('درخواست API در ماه', 'API calls / month'), values: [tr('۵۰ هزار', '50K'), tr('۵۰۰ هزار', '500K'), tr('نامحدود', 'Unlimited')] },
              { label: tr('داشبورد زمان‌واقعی', 'Realtime dashboards'), values: [true, true, true] },
              { label: 'SSO / SAML', values: [false, false, true] },
              { label: tr('گزارش حسابرسی', 'Audit log'), values: [false, true, true] },
              { label: tr('پشتیبانی', 'Support'), values: [tr('انجمن', 'Community'), tr('اولویت‌دار', 'Priority'), tr('اختصاصی', 'Dedicated')] },
            ]}
          />
        </Demo>

        <Demo wide title={tr('پرسش‌های پرتکرار', 'The FAQ')} bare>
          <FaqSection
            eyebrow={tr('پرسش‌های پرتکرار', 'Frequently asked')}
            title={tr('چیزی که تیم‌ها پیش از شروع می‌پرسند', 'What teams ask before they start')}
            intro={tr(
              'شش پاسخ رک دربارهٔ دورهٔ آزمایشی، داده‌ها و خروج. برای هر چیز دیگر، یک آدم واقعی در دوبلین یا سنگاپور زیر یک ساعت جواب می‌دهد.',
              'Six honest answers about the trial, the data and the exit. For anything else, a human in Dublin or Singapore replies within the hour.',
            )}
            defaultOpen="trial"
            ctaLabel={tr('گفت‌وگو با فروش', 'Talk to sales')}
            ctaHref="mailto:sales@meridian.dev"
            moreLabel={tr('همهٔ پرسش‌ها', 'All questions')}
            moreHref="/components"
            note={tr('میانگین نخستین پاسخ، ۹۰ روز اخیر: ۳۸ دقیقه.', 'Average first response, last 90 days: 38 minutes.')}
            items={[
              {
                id: 'trial',
                question: tr('آیا دورهٔ آزمایشی نیاز به کارت بانکی دارد؟', 'Does the trial need a credit card?'),
                answer: tr(
                  'نه؛ ۱۴ روز کامل، بدون کارت. روز دوازدهم یک ایمیل یادآوری می‌آید و هیچ چیزی خودکار شارژ نمی‌شود — ورک‌اسپیس فقط می‌ماند تا پلن انتخاب کنید.',
                  'No card, 14 full days. You get one reminder email on day 12 and nothing ever auto-charges — the workspace simply waits for you to pick a plan.',
                ),
              },
              {
                id: 'data',
                question: tr('داده‌هایمان کجا نگهداری می‌شود؟', 'Where does our data live?'),
                answer: tr(
                  'در همان ناحیه‌ای که هنگام ثبت‌نام انتخاب می‌کنید — فرانکفورت، دوبلین یا سنگاپور — و هرگز از آن خارج نمی‌شود. رمزگذاری در حالت سکون با AES-256 و در حال انتقال با TLS 1.3.',
                  'In the region you pick when signing up — Frankfurt, Dublin or Singapore — and it never leaves it. Encrypted at rest with AES-256 and in flight with TLS 1.3.',
                ),
              },
              {
                id: 'plan',
                question: tr('می‌توانیم وسط ماه پلن را عوض کنیم؟', 'Can we switch plans mid-month?'),
                answer: tr(
                  'بله. تغییرات تا دقیقه دقیق‌حساب می‌شود و فقط اختلاف در فاکتور بعدی می‌آید. بدون ری‌استارت، بدون مهاجرت، بدون قطعی.',
                  'Yes. Changes are prorated to the minute and only the difference shows up on the next invoice. No restarts, no migration, no downtime.',
                ),
              },
              {
                id: 'exit',
                question: tr('خروج از سرویس چطور است؟', 'What does leaving look like?'),
                answer: tr(
                  'با یک کلیک: خروجی کامل JSON و CSV از همهٔ فضاهای کاری، سپس حذف قطعی همه‌چیز ظرف ۳۰ روز — با تأیید ایمیلی و پیوستِ گزارش.',
                  'One click: a full JSON and CSV export of every workspace, then hard deletion of everything within 30 days — confirmed by email, with the log attached.',
                ),
              },
              {
                id: 'sso',
                question: tr('ورود تک‌نشان در کدام پلن‌ها هست؟', 'Which plans include SSO?'),
                answer: tr(
                  'ورود تک‌نشان SAML و OIDC همراه پلن مقیاس است؛ اکتا و اکتیو دایرکتوری پشتیبانی کامل می‌شوند. راه‌اندازی کاربر با SCIM نقشهٔ راه همین فصل است.',
                  'SAML and OIDC single sign-on ships with Scale; Okta and Active Directory are first-class. SCIM user provisioning is on the roadmap for this quarter.',
                ),
              },
              {
                id: 'support',
                question: tr('پشتیبانی چه ساعتی پاسخ می‌دهد؟', 'When does support answer?'),
                answer: tr(
                  'میز دوبلین و سنگاپور با هم پوشش ۲۴/۷ می‌دهند. نخستین پاسخ در پلن رشد زیر ۴ ساعت و در مقیاس زیر ۱ ساعت است — اندازه‌گیری‌شده، نه وعده‌ داده‌شده.',
                  'The Dublin and Singapore desks cover 24/7 between them. First response is under 4 hours on Growth and under 1 hour on Scale, measured — not promised.',
                ),
              },
            ]}
          />
        </Demo>

        <Demo wide title={tr('نوار آمار و لوگوهای اعتماد', 'The stats band and the trust logos')} bare>
          <div style={{ display: 'grid', gap: '1.5rem' }}>
            <StatsBand
              eyebrow={tr('مریدین در اعداد', 'Meridian in numbers')}
              title={tr('مقیاسی که صبح‌های شما به آن تکیه می‌کند', 'The scale your mornings rely on')}
              note={tr('میانگین غلتانِ ۹۰ روزه در ۳۴ ناحیه · بازرسی‌شده اکتبر ۲۰۲۶', 'Rolling 90-day average across 34 regions · audited October 2026')}
              stats={[
                { value: 99.99, unit: tr('٪', '%'), format: { minimumFractionDigits: 2, maximumFractionDigits: 2 }, label: tr('آپ‌تایم اندازه‌گیری‌شده', 'measured uptime'), icon: 'shield' },
                { value: 3.4, unit: 'B', format: { minimumFractionDigits: 1 }, label: tr('رویداد در روز', 'events a day'), icon: 'zap' },
                { value: 142, label: tr('کشور تحت پوشش', 'countries served'), icon: 'globe' },
                { value: 8200, unit: '+', format: { useGrouping: true }, label: tr('تیم روی برد', 'teams on board'), icon: 'users' },
              ]}
            />
            <LogosTrust
              eyebrow={tr('مورد اعتماد از اسلو تا سنگاپور', 'Trusted from Oslo to Singapore')}
              title={tr('مریدین همان‌جا می‌چرخد که تیم شما کار می‌کند', 'Meridian runs where your team already works')}
              gridLabel={tr('گرید', 'Grid')}
              marqueeLabel={tr('اسکرول آرام', 'Slow scroll')}
              switchLabel={tr('حالت نمایش لوگوها', 'Logo display mode')}
              footnote={tr('همهٔ نشان‌ها متعلق به صاحبانشان است. اعداد مهر ۲۰۲۶ در ۳۴ ناحیه اندازه‌گیری شده‌اند.', 'All marks belong to their owners. Numbers measured October 2026 across 34 regions.')}
              logos={[
                { id: 'lumina', name: 'Lumina', mark: 'ring', tone: 'cyan' },
                { id: 'orbital', name: 'Orbital', mark: 'orbit', tone: 'violet' },
                { id: 'kanso', name: 'Kanso', mark: 'enso', tone: 'ink' },
                { id: 'vantage', name: 'Vantage', mark: 'peak', tone: 'lapis' },
                { id: 'helios', name: 'Helios', mark: 'sun', tone: 'gold' },
                { id: 'northwind', name: 'Northwind', mark: 'north', tone: 'ink' },
                { id: 'quantia', name: 'Quantia', mark: 'wave', tone: 'rose' },
                { id: 'fabrik', name: 'Fabrik', mark: 'blocks', tone: 'green' },
              ]}
            />
          </div>
        </Demo>

        <Demo wide title={tr('دیوار نقل‌قول‌ها', 'The testimonial wall')} bare>
          <TestimonialWall
            score={tr('۴٫۹ از ۵ در بیش از ۲٬۴۰۰ نظر در G2', '4.9 / 5 across 2,400+ reviews on G2')}
            title={tr('تیم‌ها نمی‌روند — درباره‌اش می‌نویسند', 'Teams don’t leave — they write about it')}
            subtitle={tr(
              'از اسلو تا سنگاپور داستان تکرار می‌شود: کار اضافهٔ کمتر، مالکیت روشن‌تر، صبح‌های سریع‌تر.',
              'From Oslo to Singapore the story repeats: less busywork, clearer ownership, faster mornings.',
            )}
            quotes={[
              {
                id: 'amelie',
                text: tr(
                  'هفته‌ای که مریدین را آوردیم، سه ابزار را بازنشسته کردیم. صفحهٔ پرداختی که هیچ‌کس جرئت دست‌زدن به آن را نداشت، بالاخره صاحب گرفت.',
                  'The week we adopted Meridian we retired three tools. The billing page nobody dared to touch finally has an owner.',
                ),
                name: tr('املی لوران', 'Amelie Laurent'),
                role: tr('معاون مهندسی · نورت‌ویند، اسلو', 'VP Engineering · Northwind, Oslo'),
                initials: 'AL',
                tone: 'gold',
              },
              {
                id: 'diego',
                text: tr(
                  'گزارش حسابرسی همان هفتهٔ اول جایش را پر کرد — یک استقرار خراب را در چهار دقیقه تا یک متغیر محیطی ردیابی کردیم.',
                  'The audit log paid for itself in one incident — we traced a broken deploy to a single env var in four minutes.',
                ),
                name: tr('دیگو فرریرا', 'Diego Ferreira'),
                role: tr('راهبر SRE · هلیوس لبز، لیسبون', 'SRE Lead · Helios Labs, Lisbon'),
                initials: 'DF',
                tone: 'moss',
              },
              {
                id: 'mei',
                text: tr(
                  'آماده‌سازی مهندسان تازه از دو هفته به سه روز رسید. فقط ران‌بوک‌ها را می‌خوانند.',
                  'Onboarding new engineers dropped from two weeks to three days. They just read the runbooks.',
                ),
                name: tr('می تاناکا', 'Mei Tanaka'),
                role: tr('مدیر فنی · کوآنتیا، سنگاپور', 'CTO · Quantia, Singapore'),
                initials: 'MT',
                tone: 'clay',
              },
            ]}
            shorts={[
              { text: tr('پشتیبانی در چند دقیقه جواب می‌دهد، نه چند روز.', 'Support answers in minutes, not days.'), name: tr('پریا شارما', 'Priya Sharma'), co: tr('فابریک · تورنتو', 'Fabrik · Toronto'), stars: 5, initials: 'PS', tone: 'violet' },
              { text: tr('حالا گزارش تغییرات خودش نوشته می‌شود.', 'The changelog writes itself now.'), name: tr('یوناس وبر', 'Jonas Weber'), co: tr('کانسو · برلین', 'Kanso · Berlin'), stars: 5, initials: 'JW', tone: 'cyan' },
              { text: tr('داشبوردی که کل تیم واقعاً باز می‌کند.', 'A dashboard the whole team actually opens.'), name: tr('سوفیا روسی', 'Sofia Rossi'), co: tr('وانتاژ · میلان', 'Vantage · Milan'), stars: 4, initials: 'SR', tone: 'lapis' },
              { text: tr('بدون روز آموزش. تیم فقط ازش استفاده کرد.', 'Zero training day. The team just used it.'), name: tr('الکس کیم', 'Alex Kim'), co: tr('اوربیتال · سئول', 'Orbital · Seoul'), stars: 5, initials: 'AK', tone: 'gold' },
            ]}
          />
          <p className="sc-demo-title" style={{ marginTop: '0.75rem', fontWeight: 500 }}>
            {tr('نکته: کلیدهای ← → هم کار می‌کنند.', 'Tip: the ← → keys work too.')}
          </p>
        </Demo>
      </div>
    </Section>
  );
}
