import { useState, type CSSProperties, type ReactNode } from 'react';
import { Button, ScrollHorizontal, type ScrollHorizontalItem, ScrollRoadmap, type ScrollRoadmapStop, ScrollStack, type ScrollStackItem } from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useTr } from '../lang';

const perks = { margin: 0, padding: 0, listStyle: 'none', display: 'grid', gap: '.55rem', color: 'var(--nx-text-muted)', fontSize: 'var(--nx-text-sm)' } as const;
const perk = { display: 'flex', alignItems: 'baseline', gap: '.6rem' } as const;
const perkDot = {
  flex: 'none',
  inlineSize: '1.3rem',
  aspectRatio: '1',
  display: 'grid',
  placeItems: 'center',
  alignSelf: 'center',
  borderRadius: '50%',
  color: 'var(--_accent, var(--nx-accent))',
  background: 'color-mix(in oklab, var(--_accent, var(--nx-accent)) 14%, transparent)',
  fontSize: 'var(--nx-text-xs)',
  fontWeight: 700,
} as const;
const audience = { margin: 0, color: 'var(--nx-text-muted)', fontSize: 'var(--nx-text-xs)' } as const;

/** The stack demo: a pricing runway whose cards pile up with depth, the pick staying live inside each card. */
function StackDemo() {
  const tr = useTr();
  const [plan, setPlan] = useState<string | null>(null);

  const items: ScrollStackItem[] = [
    {
      key: 'starter',
      kicker: tr('پلن ۱', 'Plan 1'),
      title: tr('آغازگر', 'Starter'),
      accent: 'success',
      icon: 'zap',
      text: (
        <ul style={perks}>
          {[tr('۲ خط انتشار', '2 rollout pipelines'), tr('۳ ناحیه', '3 regions'), tr('خلاصهٔ هفتگی', 'Weekly digest')].map((line) => (
            <li key={line} style={perk}>
              <span style={perkDot} aria-hidden="true">
                ✓
              </span>
              {line}
            </li>
          ))}
        </ul>
      ),
      foot: (
        <>
          <Button size="sm" variant={plan === 'starter' ? 'primary' : 'secondary'} aria-pressed={plan === 'starter'} onClick={() => setPlan('starter')}>
            {tr('انتخاب پلن', 'Choose plan')}
          </Button>
          <p style={audience}>{tr('برای نخستین انتشار', 'For the first release')}</p>
          {plan === 'starter' && <span className="nx-scroll-card-chip">{tr('در سبد شما', 'In your cart')}</span>}
        </>
      ),
    },
    {
      key: 'growth',
      kicker: tr('پلن ۲', 'Plan 2'),
      title: tr('رشد', 'Growth'),
      accent: 'violet',
      icon: 'trend-up',
      text: (
        <ul style={perks}>
          {[tr('۱۰ خط انتشار', '10 pipelines'), tr('۱۲ ناحیه', '12 regions'), tr('کاناری + واگرد خودکار', 'Canary + auto-rollback')].map((line) => (
            <li key={line} style={perk}>
              <span style={perkDot} aria-hidden="true">
                ✓
              </span>
              {line}
            </li>
          ))}
        </ul>
      ),
      foot: (
        <>
          <Button size="sm" variant={plan === 'growth' ? 'primary' : 'secondary'} aria-pressed={plan === 'growth'} onClick={() => setPlan('growth')}>
            {tr('انتخاب پلن', 'Choose plan')}
          </Button>
          <p style={audience}>{tr('برای تیم‌های هفتگی', 'For teams shipping weekly')}</p>
          {plan === 'growth' && <span className="nx-scroll-card-chip">{tr('در سبد شما', 'In your cart')}</span>}
        </>
      ),
    },
    {
      key: 'scale',
      kicker: tr('پلن ۳', 'Plan 3'),
      title: tr('مقیاس', 'Scale'),
      accent: 'gold',
      icon: 'layers',
      text: (
        <ul style={perks}>
          {[tr('خط انتشار نامحدود', 'Unlimited pipelines'), tr('۲۴ ناحیه', '24 regions'), tr('تحویل تدریجی + SLA', 'Progressive delivery + SLA')].map((line) => (
            <li key={line} style={perk}>
              <span style={perkDot} aria-hidden="true">
                ✓
              </span>
              {line}
            </li>
          ))}
        </ul>
      ),
      foot: (
        <>
          <Button size="sm" variant={plan === 'scale' ? 'primary' : 'secondary'} aria-pressed={plan === 'scale'} onClick={() => setPlan('scale')}>
            {tr('انتخاب پلن', 'Choose plan')}
          </Button>
          <p style={audience}>{tr('برای ناوگان سرویس‌ها', 'For fleets of services')}</p>
          {plan === 'scale' && <span className="nx-scroll-card-chip">{tr('در سبد شما', 'In your cart')}</span>}
        </>
      ),
    },
    {
      key: 'enterprise',
      kicker: tr('پلن ۴', 'Plan 4'),
      title: tr('سازمانی', 'Enterprise'),
      accent: 'lapis',
      icon: 'shield',
      text: (
        <ul style={perks}>
          {[tr('ابر اختصاصی', 'Private cloud'), tr('SOC 2 + رد حسابرسی', 'SOC 2 + audit trail'), tr('مهندس اختصاصی', 'Dedicated engineer')].map((line) => (
            <li key={line} style={perk}>
              <span style={perkDot} aria-hidden="true">
                ✓
              </span>
              {line}
            </li>
          ))}
        </ul>
      ),
      foot: (
        <>
          <Button size="sm" variant={plan === 'enterprise' ? 'primary' : 'secondary'} aria-pressed={plan === 'enterprise'} onClick={() => setPlan('enterprise')}>
            {tr('انتخاب پلن', 'Choose plan')}
          </Button>
          <p style={audience}>{tr('برای مقیاسِ زیر مقررات', 'For regulated scale')}</p>
          {plan === 'enterprise' && <span className="nx-scroll-card-chip">{tr('در سبد شما', 'In your cart')}</span>}
        </>
      ),
    },
  ];

  return (
    <div style={{ display: 'grid', gap: '.75rem' }}>
      <ScrollStack items={items} label={tr('پلن‌ها', 'Plans')} />
      <p role="status" style={{ margin: 0, textAlign: 'center', color: 'var(--nx-text-muted)', fontSize: 'var(--nx-text-sm)' }}>
        {plan
          ? tr(`ورک‌اسپیس با پلن ${plan} آماده شد — صورت‌حساب بعد از نخستین انتشار.`, `Workspace seeded with the ${plan} plan — billing starts after the first rollout.`)
          : tr('اسکرول کنید تا هر پلن روی قبلی بنشیند؛ انتخاب داخل همان کارت چسبان زنده است.', 'Scroll and each plan lands on the one before; picking a plan stays live inside the sticky card.')}
      </p>
    </div>
  );
}

/* ---- Strip artwork: five little deployment scenes, all token-coloured --------------------------- */

const svg = { display: 'block', inlineSize: '100%', blockSize: '100%' } as const;

function Scene({ children }: { children: ReactNode }) {
  return (
    <svg viewBox="0 0 320 200" preserveAspectRatio="xMidYMid slice" style={svg} aria-hidden="true" focusable="false">
      {children}
    </svg>
  );
}

function AtlasScene() {
  return (
    <Scene>
      <defs>
        <linearGradient id="sc-scr-a" x1="0" y1="0" x2="1" y2="1">
          <stop offset="0" style={{ stopColor: 'var(--nx-lapis-100)' }} />
          <stop offset="1" style={{ stopColor: 'var(--nx-lapis-200)' }} />
        </linearGradient>
      </defs>
      <rect width="320" height="200" fill="url(#sc-scr-a)" />
      <path d="M30 152 C 84 152 84 64 146 64 S 226 152 290 120" style={{ stroke: 'var(--nx-lapis-400)', strokeWidth: 3, fill: 'none', strokeDasharray: '1 8', strokeLinecap: 'round' }} />
      <circle cx="30" cy="152" r="13" style={{ fill: 'var(--nx-lapis-600)' }} />
      <circle cx="30" cy="152" r="5" style={{ fill: 'var(--nx-lapis-50)' }} />
      <circle cx="146" cy="64" r="13" style={{ fill: 'var(--nx-chart-7)' }} />
      <circle cx="146" cy="64" r="5" style={{ fill: 'var(--nx-lapis-50)' }} />
      <circle cx="290" cy="120" r="13" style={{ fill: 'var(--nx-gold-500)' }} />
      <circle cx="290" cy="120" r="5" style={{ fill: 'var(--nx-lapis-50)' }} />
    </Scene>
  );
}

function AuroraScene() {
  return (
    <Scene>
      <defs>
        <linearGradient id="sc-scr-b" x1="0" y1="0" x2="0" y2="1">
          <stop offset="0" style={{ stopColor: 'var(--nx-lapis-950)' }} />
          <stop offset="1" style={{ stopColor: 'var(--nx-lapis-800)' }} />
        </linearGradient>
      </defs>
      <rect width="320" height="200" fill="url(#sc-scr-b)" />
      <path d="M-10 120 C 60 60 120 160 190 110 S 300 60 340 100" style={{ stroke: 'var(--nx-cyan-400)', strokeWidth: 14, fill: 'none', opacity: 0.22, strokeLinecap: 'round' }} />
      <path d="M-10 145 C 70 90 130 185 200 135 S 310 85 340 125" style={{ stroke: 'var(--nx-cyan-300)', strokeWidth: 9, fill: 'none', opacity: 0.38, strokeLinecap: 'round' }} />
      <path d="M-10 168 C 80 120 140 205 210 158 S 320 110 340 150" style={{ stroke: 'var(--nx-lapis-100)', strokeWidth: 4, fill: 'none', opacity: 0.8, strokeLinecap: 'round' }} />
      <circle cx="250" cy="46" r="16" style={{ fill: 'var(--nx-cyan-300)', opacity: 0.9 }} />
    </Scene>
  );
}

function HarborScene() {
  return (
    <Scene>
      <defs>
        <linearGradient id="sc-scr-c" x1="0" y1="0" x2="1" y2="0">
          <stop offset="0" style={{ stopColor: 'var(--nx-lapis-50)' }} />
          <stop offset="1" style={{ stopColor: 'var(--nx-cyan-300)' }} />
        </linearGradient>
      </defs>
      <rect width="320" height="200" fill="url(#sc-scr-c)" />
      <path d="M160 12 L160 60" style={{ stroke: 'var(--nx-cyan-700)', strokeWidth: 4, strokeLinecap: 'round' }} />
      <path d="M160 12 L196 26 L160 40" style={{ stroke: 'var(--nx-cyan-700)', strokeWidth: 4, fill: 'none', strokeLinecap: 'round', strokeLinejoin: 'round' }} />
      <g style={{ fill: 'var(--nx-cyan-700)' }}>
        <rect x="52" y="112" width="56" height="34" rx="8" />
        <rect x="116" y="112" width="56" height="34" rx="8" opacity="0.55" />
        <rect x="180" y="112" width="56" height="34" rx="8" opacity="0.3" />
        <rect x="84" y="68" width="56" height="34" rx="8" opacity="0.75" />
        <rect x="148" y="68" width="56" height="34" rx="8" opacity="0.4" />
      </g>
      <rect x="42" y="152" width="236" height="6" rx="3" style={{ fill: 'var(--nx-cyan-700)', opacity: 0.2 }} />
    </Scene>
  );
}

function CascadeScene() {
  return (
    <Scene>
      <defs>
        <linearGradient id="sc-scr-d" x1="0" y1="0" x2="0" y2="1">
          <stop offset="0" style={{ stopColor: 'var(--nx-gold-200)' }} />
          <stop offset="1" style={{ stopColor: 'var(--nx-gold-300)' }} />
        </linearGradient>
      </defs>
      <rect width="320" height="200" fill="url(#sc-scr-d)" />
      <g style={{ fill: 'var(--nx-gold-700)' }}>
        <rect x="30" y="46" width="60" height="30" rx="8" opacity="0.3" />
        <rect x="80" y="84" width="60" height="30" rx="8" opacity="0.45" />
        <rect x="130" y="122" width="60" height="30" rx="8" opacity="0.65" />
        <rect x="180" y="160" width="60" height="30" rx="8" />
      </g>
      <path d="M240 168 L240 132 L286 142 L240 152" style={{ fill: 'var(--nx-chart-7)' }} />
    </Scene>
  );
}

function PolarScene() {
  return (
    <Scene>
      <defs>
        <linearGradient id="sc-scr-e" x1="0" y1="0" x2="1" y2="1">
          <stop offset="0" style={{ stopColor: 'var(--nx-lapis-50)' }} />
          <stop offset="1" style={{ stopColor: 'var(--nx-cyan-300)' }} />
        </linearGradient>
      </defs>
      <rect width="320" height="200" fill="url(#sc-scr-e)" />
      <g style={{ stroke: 'var(--nx-cyan-700)', fill: 'none', strokeWidth: 3 }}>
        <circle cx="160" cy="112" r="72" opacity="0.25" strokeDasharray="2 8" />
        <circle cx="160" cy="112" r="48" opacity="0.45" strokeDasharray="2 8" />
        <circle cx="160" cy="112" r="24" opacity="0.8" />
      </g>
      <path d="M160 112 L160 40 A 72 72 0 0 1 224 76 Z" style={{ fill: 'var(--nx-cyan-600)', opacity: 0.18 }} />
      <circle cx="160" cy="112" r="8" style={{ fill: 'var(--nx-cyan-700)' }} />
      <circle cx="214" cy="66" r="7" style={{ fill: 'var(--nx-chart-7)' }} />
      <circle cx="112" cy="146" r="7" style={{ fill: 'var(--nx-gold-500)' }} />
    </Scene>
  );
}

function StripDemo() {
  const tr = useTr();
  const items: ScrollHorizontalItem[] = [
    { key: 'atlas', title: tr('خط‌لولهٔ اطلس', 'Atlas pipeline'), state: 'live', stateLabel: tr('زنده', 'Live'), meta: tr('فرانکفورت · eu-de', 'Frankfurt · eu-de'), media: <AtlasScene /> },
    { key: 'aurora', title: tr('استقرار شفق', 'Aurora deploy'), state: 'canary', stateLabel: tr('کاناری', 'Canary'), meta: tr('استکهلم · eu-se', 'Stockholm · eu-se'), media: <AuroraScene /> },
    { key: 'harbor', title: tr('انتشار بندر', 'Harbor release'), state: 'live', stateLabel: tr('زنده', 'Live'), meta: tr('سنگاپور · ap-se', 'Singapore · ap-se'), media: <HarborScene /> },
    { key: 'cascade', title: tr('فلگ‌های آبشار', 'Cascade flags'), state: 'scheduled', stateLabel: tr('برنامه‌ریزی', 'Scheduled'), meta: tr('ونکوور · na-ca', 'Vancouver · na-ca'), media: <CascadeScene /> },
    { key: 'polar', title: tr('انتشار قطبی', 'Polar rollout'), state: 'live', stateLabel: tr('زنده', 'Live'), meta: tr('اسلو · eu-no', 'Oslo · eu-no'), media: <PolarScene /> },
  ];
  return (
    <ScrollHorizontal
      items={items}
      pages={2.2}
      label={tr('نوار انتشار', 'Rollout strip')}
      title={tr('نوار انتشار — فصل چهارم', 'Rollout strip — Q4')}
      subtitle={tr('پنج پروژهٔ انتشار فصل، در پنج ناحیه', 'Five rollout projects across five regions')}
      progressLabel={tr('پیشرفت نوار', 'Strip progress')}
    />
  );
}

/* ---- Roadmap demo --------------------------------------------------------------------------------- */

function RoadmapDemo() {
  const tr = useTr();
  const [joined, setJoined] = useState(false);
  const stops: ScrollRoadmapStop[] = [
    {
      key: 'q1',
      kicker: tr('فصل ۱ · منتشرشده', 'Q1 · shipped'),
      title: tr('موتور فلگ‌ها نسخهٔ ۲', 'Flags engine v2'),
      meta: tr('آزمایشگاه دوبلین · ارزیابی فلگ زیر ۸ms', 'Dublin lab · flag evaluation under 8ms'),
      state: 'live',
      stateLabel: tr('زنده', 'Live'),
    },
    {
      key: 'q2',
      kicker: tr('فصل ۲ · در جریان', 'Q2 · in flight'),
      title: tr('ایجنت‌های لبه در ۲۴ ناحیه', 'Edge agents in 24 regions'),
      meta: tr('ابتدا سنگاپور · واگرد روی لبه', 'Singapore first · rollback at the edge'),
      state: 'building',
      stateLabel: tr('در حال اجرا', 'Building'),
    },
    {
      key: 'q3',
      kicker: tr('فصل ۳ · در طراحی', 'Q3 · in design'),
      title: tr('نگهبان انتشار هوشمند', 'Smart rollout guard'),
      meta: tr('آزمایشگاه سئول · استقرارِ دروازهٔ ناهنجاری', 'Seoul lab · anomaly-gated deploys'),
      state: 'design',
      stateLabel: tr('در طراحی', 'Design'),
    },
    {
      key: 'q4',
      kicker: tr('فصل ۴ · شما', 'Q4 · you'),
      title: tr('شروع انتشار', 'Start rolling out'),
      text: tr('پلن رایگان، دو دقیقه، بدون کارت — نقشهٔ راه به نخستین انتشارِ شما ختم می‌شود.', 'Free tier, two minutes, no card — the roadmap ends at your first release.'),
      cta: true,
      foot: (
        <>
          <Button variant="primary" icon="sparkles" onClick={() => setJoined(true)}>
            {tr('ساخت ورک‌اسپیس', 'Create workspace')}
          </Button>
          {joined && (
            <p role="status" style={{ margin: 0, fontSize: 'var(--nx-text-xs)', color: 'var(--nx-gold-200)' }}>
              {tr('ورک‌اسپیس «aurora-42» آماده شد — نخستین خط انتشار از پیش بار شده است.', 'Workspace “aurora-42” is ready — first pipeline preloaded.')}
            </p>
          )}
        </>
      ),
    },
  ];
  return (
    <ScrollRoadmap
      stops={stops}
      label={tr('نقشهٔ راه', 'Roadmap')}
      title={tr('نقشهٔ راه ۲۰۲۷', '2027 roadmap')}
      subtitle={tr('خط پیشرفت اسکرول را می‌نوشد و ایستگاه‌ها به‌ترتیب بیدار می‌شوند', 'The line drinks the scroll and the stations wake in order')}
      meterLabel={tr('٪ طی‌شده', '% shipped')}
    />
  );
}

/* ---- Section -------------------------------------------------------------------------------------- */

const stageStyle = { '--_board': '36rem' } as CSSProperties;

export function ScrollBlocks() {
  const tr = useTr();

  return (
    <Section
      id="scroll-blocks"
      eyebrow={tr('بلوک‌ها', 'Blocks')}
      title={tr('صحنه‌های اسکرول', 'Scroll scenes')}
      description={tr(
        'یک صحنهٔ چسبان که پیشرفت اسکرولِ خودش آن را می‌راند: کارت‌هایی که با عمق انبار می‌شوند، گالری افقی که چرخِ عمودی می‌چرخاندش و نقشهٔ راهی که خطش با اسکرول پُر می‌شود.',
        'One pinned scene driven by its own scroll: cards that stack up with depth, a horizontal gallery the vertical wheel drives, and a roadmap whose line fills as you scroll.',
      )}
      code={{
        react: `<ScrollStack items={plans} inset="4.5rem" />
<ScrollHorizontal items={projects} pages={2.2} title="Rollout strip — Q4" />
<ScrollRoadmap stops={quarters} title="2027 roadmap" meterLabel="% shipped" />`,
        blade: `<x-nx::scroll-stack :items="$plans" inset="4.5rem" />
<x-nx::scroll-horizontal :items="$projects" :pages="2.2" title="Rollout strip — Q4" />
<x-nx::scroll-roadmap :stops="$quarters" title="2027 roadmap" meter-label="% shipped" />`,
      }}
    >
      <div className="sc-demos">
        <Demo title={tr('کارت‌های انبارشونده', 'Stacking cards')} wide>
          <StackDemo />
        </Demo>

        <Demo title={tr('نوار افقی', 'Horizontal strip')} wide>
          <div style={stageStyle}>
            <StripDemo />
          </div>
        </Demo>

        <Demo title={tr('نقشهٔ راه', 'Roadmap')} wide>
          <div style={stageStyle}>
            <RoadmapDemo />
          </div>
        </Demo>
      </div>
    </Section>
  );
}
