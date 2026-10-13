import { useRef, useState, type CSSProperties } from 'react';
import { Button, Icon, NavbarFloating, NavbarGlass, NavbarMega } from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useTr } from '../lang';

/** A framed page the bar floats over: the stage scrolls for real. */
const stage: CSSProperties = {
  position: 'relative',
  inlineSize: 'min(100%, 46rem)',
  marginInline: 'auto',
  overflowY: 'auto',
  border: '1px solid var(--nx-border)',
  borderRadius: 'var(--nx-radius-xl)',
  background: 'var(--nx-surface-2)',
  scrollbarWidth: 'thin',
};

const card: CSSProperties = {
  display: 'grid',
  gap: 'var(--nx-space-1)',
  padding: 'var(--nx-space-4)',
  border: '1px solid var(--nx-border)',
  borderRadius: 'var(--nx-radius-lg)',
  background: 'var(--nx-surface)',
  textAlign: 'start',
};

const cardTitle: CSSProperties = {
  display: 'flex',
  alignItems: 'center',
  gap: 'var(--nx-space-2)',
  fontSize: 'var(--nx-text-sm)',
  fontWeight: 650,
};

const cardBody: CSSProperties = {
  margin: 0,
  color: 'var(--nx-text-muted)',
  fontSize: 'var(--nx-text-xs)',
  lineHeight: 1.6,
};

const cards: CSSProperties = {
  display: 'grid',
  gridTemplateColumns: 'repeat(auto-fit, minmax(min(13rem, 100%), 1fr))',
  gap: 'var(--nx-space-3)',
  padding: '0 var(--nx-space-4) var(--nx-space-4)',
};

/** The floating pill in a page that scrolls: the pill condenses past 24px. */
function FloatingDemo() {
  const tr = useTr();
  const [tab, setTab] = useState('product');
  const page = useRef<HTMLDivElement>(null);

  return (
    <div
      ref={page}
      style={{ ...stage, blockSize: '30rem', backgroundImage: 'radial-gradient(60rem 18rem at 50% -6rem, var(--nx-accent-soft), transparent 70%)' }}
    >
      <NavbarFloating
        aria-label={tr('اصلی', 'Primary')}
        value={tab}
        onValueChange={setTab}
        condenseOnScroll={page}
        brand={{ name: 'Meridian', href: '#navbars-blocks' }}
        cta={{ label: tr('شروع رایگان', 'Start free'), icon: 'arrow-right', href: '#navbars-blocks' }}
        links={[
          { value: 'product', label: tr('محصول', 'Product') },
          { value: 'solutions', label: tr('راه‌حل‌ها', 'Solutions') },
          { value: 'pricing', label: tr('قیمت', 'Pricing') },
          { value: 'docs', label: tr('مستندات', 'Docs') },
        ]}
      />

      <header style={{ padding: '8.5rem 1.5rem 3rem', textAlign: 'center' }}>
        <h4 style={{ margin: '0 0 var(--nx-space-2)', font: '800 clamp(1.3rem, 3.4vw, 1.9rem)/1.25 var(--nx-font-display)', letterSpacing: 'var(--nx-tracking-tight)', textWrap: 'balance' }}>
          {tr('هوش انتشار برای تیم‌های محصول جهانی', 'Release intelligence for global product teams')}
        </h4>
        <p style={{ margin: '0 auto', maxInlineSize: '46ch', color: 'var(--nx-text-muted)', fontSize: 'var(--nx-text-sm)', lineHeight: 1.7 }}>
          {tr(
            'ردیابی، انتشار و هشدارها در یک ورک‌اسپیس — از اولین کامیت در فرانکفورت تا آخرین استقرار در سنگاپور.',
            'Traces, releases and alerts in one workspace — from the first commit in Frankfurt to the last rollout in Singapore.',
          )}
        </p>
        <div style={{ display: 'flex', flexWrap: 'wrap', justifyContent: 'center', gap: 'var(--nx-space-2) var(--nx-space-6)', marginBlockStart: 'var(--nx-space-5)' }}>
          {(
            [
              [tr('۹۹.۹۸٪', '99.98%'), tr('پایداری API', 'API uptime')],
              [tr('۲۱۴ms', '214ms'), tr('p95 جهانی', 'p95 worldwide')],
              [tr('۱۲', '12'), tr('ناحیهٔ فعال', 'regions live')],
            ] as const
          ).map(([figure, caption]) => (
            <div key={caption} style={{ display: 'grid', gap: '2px' }}>
              <b style={{ fontSize: 'var(--nx-text-lg)', fontWeight: 750 }}>{figure}</b>
              <span style={{ color: 'var(--nx-text-muted)', fontSize: 'var(--nx-text-xs)' }}>{caption}</span>
            </div>
          ))}
        </div>
      </header>

      <div style={cards}>
        {(
          [
            ['zap', tr('ردیابی زنده', 'Real-time traces'), tr('هر درخواست در دوبلین، تورنتو و اوساکا — نمونه‌گیری‌شده اما کامل.', 'Every request across Dublin, Toronto and Osaka — sampled, yet complete.')],
            ['trend-up', tr('تحلیل انتشار', 'Release analytics'), tr('هشدار رگرسیون قبل از رسیدن استقرار به آخرین ناحیه می‌رسد.', 'Regression alerts land before the rollout reaches the last region.')],
            ['bell', tr('مسیریابی هشدار', 'Alert routing'), tr('آن‌کالِ خورشیدگرد برای مارکو، پریا و کل چرخه.', 'Follow-the-sun on-call for Marco, Priya and the whole rotation.')],
          ] as const
        ).map(([icon, title, body]) => (
          <div key={title} style={card}>
            <b style={cardTitle}>
              <Icon name={icon} />
              {title}
            </b>
            <span style={cardBody}>{body}</span>
          </div>
        ))}
      </div>
      <p style={{ padding: 'var(--nx-space-2) var(--nx-space-4) var(--nx-space-5)', textAlign: 'center', color: 'var(--nx-text-subtle)', fontSize: 'var(--nx-text-xs)' }}>
        Meridian — SOC 2 Type II · GDPR · {tr('مستقر در ۱۲ ناحیه', 'deployed in 12 regions')}
      </p>
    </div>
  );
}

/** A drifting, colourful hero: the glass proves itself by what shines through. */
function GlassDemo() {
  const tr = useTr();
  const [night, setNight] = useState(false);

  const chip: CSSProperties = {
    display: 'inline-flex',
    alignItems: 'center',
    gap: 'var(--nx-space-1)',
    padding: 'calc(var(--nx-space-1) + 2px) var(--nx-space-3)',
    borderRadius: 'var(--nx-radius-full)',
    background: 'rgb(255 255 255 / 14%)',
    boxShadow: 'inset 0 0 0 1px rgb(255 255 255 / 22%), inset 0 1px 0 rgb(255 255 255 / 25%)',
    backdropFilter: 'blur(8px)',
    fontSize: 'var(--nx-text-xs)',
    fontWeight: 600,
  };

  return (
    <div
      role="img"
      aria-label={tr('صفحهٔ فرود شبیه‌سازی‌شده با نوبار شیشه‌ای', 'A simulated landing page with a glass navbar')}
      style={{
        ...stage,
        minBlockSize: '27rem',
        overflow: 'clip',
        border: 'none',
        color: 'var(--nx-ink-50)',
        background: night
          ? 'linear-gradient(140deg, var(--nx-ink-950) 0%, var(--nx-lapis-700) 58%, var(--nx-violet-700) 100%)'
          : 'linear-gradient(140deg, var(--nx-cyan-400) 0%, var(--nx-violet-500) 52%, var(--nx-chart-2) 100%)',
      }}
    >
      {(
        [
          ['4.5rem', '10%', '6.5rem', 0],
          ['9rem', undefined, '4.5rem', -6],
          [undefined, '55%', '3.5rem', -11],
        ] as const
      ).map(([top, start, size, delay], i) => (
        <span
          key={i}
          aria-hidden="true"
          style={{
            position: 'absolute',
            insetBlockStart: top,
            insetInlineStart: start,
            insetInlineEnd: i === 1 ? '8%' : undefined,
            inlineSize: size,
            blockSize: size,
            borderRadius: '50%',
            background: `rgb(255 255 255 / ${i === 1 ? '18%' : '28%'})`,
            filter: 'blur(2px)',
            animation: `nx-float ${16 + i * 3}s ease-in-out ${delay}s infinite alternate`,
            pointerEvents: 'none',
          }}
        />
      ))}

      <NavbarGlass
        aria-label={tr('اصلی', 'Primary')}
        brand={{ name: 'Meridian', href: '#navbars-blocks' }}
        links={[
          { label: tr('محصول', 'Product'), href: '#navbars-blocks', current: true },
          { label: tr('قیمت', 'Pricing'), href: '#navbars-blocks' },
          { label: tr('مستندات', 'Docs'), href: '#navbars-blocks' },
        ]}
        actions={
          <>
            <button
              type="button"
              className="nx-glassbar-tool"
              aria-label={night ? tr('حالت روز', 'Day mood') : tr('حالت شب', 'Night mood')}
              onClick={() => setNight(!night)}
            >
              <Icon name={night ? 'sun' : 'moon'} />
            </button>
            <span className="nx-glassbar-avatar" title="Lena Kovács">
              LK
            </span>
          </>
        }
      />

      <div style={{ position: 'relative', zIndex: 1, padding: '10.5rem 1.5rem 3rem', textAlign: 'center', display: 'grid', gap: 'var(--nx-space-3)', justifyItems: 'center' }}>
        <h4 style={{ margin: 0, font: '800 clamp(1.3rem, 3.4vw, 1.9rem)/1.25 var(--nx-font-display)', letterSpacing: 'var(--nx-tracking-tight)', textWrap: 'balance' }}>
          {tr('تأخیری که حس می‌شود، داشبوردی که خوانده می‌شود', 'Latency you can feel, dashboards you can read')}
        </h4>
        <p style={{ margin: 0, maxInlineSize: '42ch', fontSize: 'var(--nx-text-sm)', lineHeight: 1.7, opacity: 0.92 }}>
          {tr(
            'مریدین هر انتشار را از فرانکفورت تا سنگاپور زیر نظر دارد — و وقتی چیزی افت می‌کند، آدمیزاد حرف می‌زند.',
            'Meridian watches every rollout from Frankfurt to Singapore — and speaks human when something dips.',
          )}
        </p>
        <div style={{ display: 'flex', flexWrap: 'wrap', justifyContent: 'center', gap: 'var(--nx-space-2)' }}>
          <span style={chip}>
            <Icon name="globe" />
            {tr('۱۲ ناحیه', '12 regions')}
          </span>
          <span style={chip}>
            <Icon name="zap" />
            {tr('۲۱۴ms p95', '214ms p95')}
          </span>
          <span style={chip}>
            <Icon name="shield" />
            SOC 2
          </span>
        </div>
      </div>
    </div>
  );
}

/** The mega menu over a dotted page: hover it, click it, tab into it. */
function MegaDemo() {
  const tr = useTr();

  return (
    <div
      style={{
        ...stage,
        minBlockSize: '27rem',
        padding: 'var(--nx-space-3) var(--nx-space-4) 0',
        backgroundImage: 'radial-gradient(color-mix(in oklab, var(--nx-text) 9%, transparent) 1px, transparent 1px)',
        backgroundSize: '1.4rem 1.4rem',
        display: 'grid',
        justifyItems: 'center',
        alignContent: 'start',
        gap: 'var(--nx-space-4)',
      }}
    >
      <NavbarMega
        aria-label={tr('اصلی', 'Primary')}
        brand={{ name: 'Meridian', href: '#navbars-blocks' }}
        trigger={tr('محصول', 'Product')}
        panelLabel={tr('منوی محصول', 'Product menu')}
        links={[
          { label: tr('قیمت', 'Pricing'), href: '#navbars-blocks' },
          { label: tr('مستندات', 'Docs'), href: '#navbars-blocks' },
          { label: tr('تغییرات', 'Changelog'), href: '#navbars-blocks' },
        ]}
        actions={
          <Button size="sm" variant="primary" icon="arrow-right">
            {tr('رزرو دمو', 'Book a demo')}
          </Button>
        }
        sections={[
          {
            title: tr('پلتفرم', 'Platform'),
            items: [
              { icon: 'chart', label: tr('داشبوردها', 'Dashboards'), description: tr('معماری زنده، بدون تنظیم', 'Live architecture, zero config'), href: '#navbars-blocks' },
              { icon: 'bell', label: tr('مسیریابی هشدار', 'Alert routing'), description: tr('آن‌کال خورشیدگرد', 'Follow-the-sun on-call'), href: '#navbars-blocks' },
              { icon: 'layers', label: tr('یکپارچه‌سازی', 'Integrations'), description: tr('۱۲۰+ اتصال‌دهنده', '120+ connectors'), href: '#navbars-blocks' },
              { icon: 'command', label: 'GraphQL API', description: tr('کلاینت تایپ‌دار در ۶ زبان', 'Typed clients in 6 languages'), href: '#navbars-blocks' },
            ],
          },
          {
            title: tr('برای تیم‌ها', 'For teams'),
            items: [
              { icon: 'trend-up', label: tr('تحلیل محصول', 'Product analytics'), description: tr('رویدادها بدون قیف', 'Events without funnels'), href: '#navbars-blocks' },
              { icon: 'shield', label: `SSO ${tr('و', 'and')} SCIM`, description: tr('آمادهٔ سازمان', 'Enterprise ready'), href: '#navbars-blocks' },
              { icon: 'sliders', label: tr('سهمیهٔ مصرف', 'Usage quotas'), description: tr('به‌ازای تیم و ناحیه', 'Per team, per region'), href: '#navbars-blocks' },
              { icon: 'file', label: tr('یادداشت انتشار', 'Release notes'), description: tr('خودنویس برای هر استقرار', 'Auto-drafted per rollout'), href: '#navbars-blocks' },
            ],
          },
        ]}
        promo={{
          icon: 'sparkles',
          title: tr('مریدین پرو', 'Meridian Pro'),
          body: tr(
            '۳۰٪ تخفیف پلن‌های سالانه تا ۳۱ اکتبر — با ناحیه‌های تازهٔ سنگاپور و تورنتو.',
            '30% off annual plans until Oct 31 — includes the new Singapore and Toronto regions.',
          ),
          actionLabel: tr('دریافت اعتبار', 'Claim credit'),
          href: '#navbars-blocks',
        }}
      />

      <div style={{ display: 'grid', gap: 'var(--nx-space-3)', padding: 'var(--nx-space-4)', textAlign: 'center', justifyItems: 'center' }}>
        <h4 style={{ margin: 0, font: '800 clamp(1.25rem, 3.2vw, 1.75rem)/1.25 var(--nx-font-display)', letterSpacing: 'var(--nx-tracking-tight)' }}>
          {tr('یک ورک‌اسپیس، همهٔ انتشارها', 'One workspace, every rollout')}
        </h4>
        <p style={{ margin: 0, maxInlineSize: '48ch', color: 'var(--nx-text-muted)', fontSize: 'var(--nx-text-sm)', lineHeight: 1.7 }}>
          {tr(
            'نشانگر محصول بالا را هاور کنید — پنل مگا کل نقشهٔ پلتفرم را حمل می‌کند و کارت تخفیف هیچ انتشار را بی‌سروصدا رد نمی‌کند.',
            'Hover the Product trigger above — the mega panel carries the whole map of the platform, and the promo card never lets a launch go unnoticed.',
          )}
        </p>
        <div style={cards}>
          {(
            [
              ['zap', tr('دریافت', 'Ingest'), tr('۳٫۲ میلیون اسپن بر ثانیه با p99 زیر 40ms.', '3.2M spans/s at p99 under 40ms.')],
              ['globe', tr('ناحیه‌ها', 'Regions'), tr('فرانکفورت، دوبلین، سنگاپور، تورنتو.', 'Frankfurt, Dublin, Singapore, Toronto.')],
              ['shield', tr('انطباق', 'Compliance'), tr('SOC 2 نوع ۲، GDPR، HIPAA.', 'SOC 2 Type II, GDPR, HIPAA.')],
            ] as const
          ).map(([icon, title, body]) => (
            <div key={title} style={card}>
              <b style={cardTitle}>
                <Icon name={icon} />
                {title}
              </b>
              <span style={cardBody}>{body}</span>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}

export function NavbarsBlocks() {
  const tr = useTr();

  return (
    <Section
      id="navbars-blocks"
      eyebrow={tr('بلاک‌ها', 'Blocks')}
      title={tr('نوبارها', 'Navbars')}
      description={tr(
        'قرص شناور با نشانگر لغزان که با اسکرول جمع می‌شود، نوار شیشه‌ای روی هیرو و مگامنویی که با هاور باز و با Escape بسته می‌شود. لوگو، لینک‌ها و CTA همه از طریق props می‌آیند.',
        'A floating pill whose indicator slides and condenses on scroll, a glass bar over a hero, and a mega menu that opens on hover and shuts on Escape. Logo, links and CTA all arrive as props.',
      )}
    >
      <div className="sc-demos">
        <Demo wide bare title={tr('قرص شناور داخل صفحهٔ در حال اسکرول', 'The floating pill inside a scrolling page')}>
          <FloatingDemo />
        </Demo>

        <Demo wide bare title={tr('شیشه روی هیرو', 'Glass over a hero')}>
          <GlassDemo />
        </Demo>

        <Demo wide bare title={tr('مگامنو: هاور، کلیک، کیبورد', 'Mega menu: hover, click, keyboard')}>
          <MegaDemo />
        </Demo>
      </div>
    </Section>
  );
}
