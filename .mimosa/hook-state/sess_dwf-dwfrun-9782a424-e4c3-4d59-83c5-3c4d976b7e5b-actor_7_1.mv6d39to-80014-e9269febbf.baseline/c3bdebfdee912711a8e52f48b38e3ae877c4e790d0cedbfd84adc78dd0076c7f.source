import { type FooterColumn, FooterMinimal, FooterNewsletter, FooterWordmark } from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useTr } from '../lang';

export function FootersBlocks() {
  const tr = useTr();

  const productLinks = [
    { label: tr('فیچر فلگ‌ها', 'Feature flags'), href: '#flags' },
    { label: tr('مشاهده‌پذیری', 'Observability'), href: '#observability' },
    { label: tr('اتوماسیون انتشار', 'Release automation'), href: '#automation' },
    { label: tr('یکپارچه‌سازی‌ها', 'Integrations'), href: '#integrations' },
    { label: tr('قیمت‌گذاری', 'Pricing'), href: '#pricing' },
  ];
  const columns: FooterColumn[] = [
    { title: tr('محصول', 'Product'), links: productLinks },
    {
      title: tr('شرکت', 'Company'),
      links: [
        { label: tr('دربارهٔ ما', 'About'), href: '#about' },
        { label: tr('فرصت‌های شغلی', 'Careers'), href: '#careers' },
        { label: tr('مشتریان', 'Customers'), href: '#customers' },
        { label: tr('برند', 'Brand'), href: '#brand' },
      ],
    },
    {
      title: tr('منابع', 'Resources'),
      links: [
        { label: tr('مستندات', 'Documentation'), href: '#docs' },
        { label: tr('مرجع API', 'API reference'), href: '#api' },
        { label: tr('راهنماها', 'Guides'), href: '#guides' },
        { label: tr('وضعیت سرویس', 'Status'), href: '#status' },
      ],
    },
    {
      title: tr('قانونی', 'Legal'),
      links: [
        { label: tr('حریم خصوصی', 'Privacy'), href: '#privacy' },
        { label: tr('شرایط', 'Terms'), href: '#terms' },
        { label: tr('امنیت', 'Security'), href: '#security' },
        { label: tr('کوکی‌ها', 'Cookies'), href: '#cookies' },
      ],
    },
  ];
  const social = [
    { label: 'X', href: '#x' },
    { label: 'GitHub', href: '#github' },
    { label: 'LinkedIn', href: '#linkedin' },
    { label: 'RSS', href: '#rss' },
  ];

  return (
    <Section
      id="footers-blocks"
      eyebrow={tr('فوترها', 'Footers')}
      title={tr('پایان صفحه، به‌اندازهٔ آغازش', 'The end of the page, as considered as its start')}
      description={tr(
        'سه فوتر کتابخانه‌ای: وردمارک غولِ outline که با هاور یا فوکوس روی فوتر از رنگ براینگ پر می‌شود، فرم خبرنامه با اعتبارسنجی زنده و پیام موفقیت، و تک‌سطری مینیمال با نقطهٔ وضعیت تپنده. همه فقط با توکن‌ها در دو تم.',
        'Three library footers: a giant outline wordmark that floods with the accent on hover or focus, a newsletter form with live validation and a success note, and a minimal one-liner with a pulsing status dot. Tokens only, both themes.',
      )}
      code={{
        react: `<FooterWordmark wordmark="Meridian" columns={columns}
  copyright="© 2026 Meridian — Frankfurt · Dublin · Singapore" social={social} />
<FooterNewsletter eyebrow="The Meridian letter" title="Ship notes, straight to your inbox."
  onSubscribe={(email) => console.log(email)} columns={columns.slice(0, 2)} social={social} />
<FooterMinimal copyright="© 2026 Meridian"
  status={{ label: 'All systems operational', href: '#status' }}
  links={[{ label: 'Docs', href: '#docs' }, { label: 'Privacy', href: '#privacy' }]} />`,
        blade: `<x-nx::footer-wordmark wordmark="Meridian" :columns="$columns"
  copyright="© 2026 Meridian — Frankfurt · Dublin · Singapore" :social="$social" />
<x-nx::footer-newsletter eyebrow="The Meridian letter" title="Ship notes, straight to your inbox."
  :columns="$columns" :social="$social" />
<x-nx::footer-minimal copyright="© 2026 Meridian"
  :status="['label' => 'All systems operational', 'href' => '#status']"
  :links="$links" />`,
      }}
    >
      <div className="sc-demos">
        <Demo wide bare title={tr('وردمارک غول', 'Giant wordmark')}>
          <FooterWordmark
            wordmark="Meridian"
            columns={columns}
            navLabel={tr('فوتر سایت', 'Site footer')}
            copyright={`© 2026 Meridian — ${tr('فرانکفورت · دوبلین · سنگاپور', 'Frankfurt · Dublin · Singapore')}`}
            social={social}
          />
        </Demo>

        <Demo wide bare title={tr('خبرنامه', 'Newsletter')}>
          <FooterNewsletter
            eyebrow={tr('نامهٔ مریدین', 'The Meridian letter')}
            title={tr('یادداشت‌های انتشار، مستقیم در صندوق ایمیل.', 'Ship notes, straight to your inbox.')}
            description={tr(
              'ماهی یک شماره: کاوش عمیق انتشارها و بازبینی‌هایی که منتشر می‌کنیم.',
              'One issue a month: release deep-dives and the postmortems we publish.',
            )}
            placeholder="you@studio.com"
            submitLabel={tr('عضویت', 'Subscribe')}
            labels={{
              email: tr('نشانی ایمیل', 'Email address'),
              empty: tr('اول ایمیلت را بنویس', 'Your email, first'),
              invalid: tr('این نشانی درست به نظر نمی‌رسد', 'That address doesn’t look right'),
              success: tr('ثبت شدی — شمارهٔ بعدی در راه است.', 'You’re on the list — the next issue is on its way.'),
              again: tr('با نشانی دیگر', 'Use another address'),
            }}
            note={tr('بدون هرزنامه، لغو عضویت با یک کلیک.', 'No spam, unsubscribe in one click.')}
            columns={columns.slice(0, 2)}
            social={social}
            navLabel={tr('فوتر', 'Footer')}
            onSubscribe={(email) => console.info('newsletter:', email)}
          />
        </Demo>

        <Demo wide bare title={tr('تک‌سطری مینیمال', 'Minimal one-liner')}>
          <FooterMinimal
            copyright="© 2026 Meridian"
            status={{ label: tr('همهٔ سرویس‌ها سالم', 'All systems operational'), href: '#status' }}
            links={[
              { label: tr('مستندات', 'Docs'), href: '#docs' },
              { label: 'API', href: '#api' },
              { label: tr('حریم خصوصی', 'Privacy'), href: '#privacy' },
            ]}
            navLabel={tr('فوتر', 'Footer')}
          />
        </Demo>

        <Demo wide bare title={tr('گسترده و با وضعیت هشدار', 'Spread, with a warning status')}>
          <FooterMinimal
            align="spread"
            copyright="© 2026 Meridian"
            status={{ label: tr('تأخیر جزئی در API', 'Partial API degradation'), tone: 'warning' }}
            links={[
              { label: tr('مستندات', 'Docs'), href: '#docs' },
              { label: tr('وضعیت سرویس', 'Status'), href: '#status' },
            ]}
            navLabel={tr('فوتر', 'Footer')}
          />
        </Demo>
      </div>
    </Section>
  );
}
