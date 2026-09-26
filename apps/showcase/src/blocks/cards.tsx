/** Showcase demos: Cards, sliders & carousels. */
import { type CSSProperties, useState } from 'react';
import {
  CycleCard,
  CycleStack,
  DepthCarousel,
  ElasticGrid,
  ExpandableStack,
  FeatureCard,
  OrbitShowcase,
  PitSlider,
  PrecisionSlider,
  RingCarousel,
  StackedScrollCards,
  TeamCards,
  WorkflowCard,
} from '@nabuxai/ui-react';
import { Demo, Section, Snippet } from '../Section';
import { useTr } from '../lang';

/* Covers are gradients built from the palette tokens: no hotlinked images. */
const art = {
  lapis: 'radial-gradient(120% 90% at 12% 0%, var(--nx-lapis-300), transparent 58%), linear-gradient(140deg, var(--nx-lapis-600), var(--nx-violet-700))',
  violet: 'radial-gradient(110% 80% at 90% 10%, var(--nx-violet-300), transparent 55%), linear-gradient(160deg, var(--nx-violet-600), var(--nx-lapis-950))',
  cyan: 'radial-gradient(100% 80% at 15% 15%, var(--nx-cyan-300), transparent 58%), linear-gradient(200deg, var(--nx-cyan-600), var(--nx-lapis-800))',
  gold: 'radial-gradient(100% 90% at 80% 100%, var(--nx-gold-300), transparent 60%), linear-gradient(160deg, var(--nx-gold-500), var(--nx-violet-700))',
  rose: 'radial-gradient(100% 80% at 20% 0%, color-mix(in oklab, var(--nx-chart-2) 60%, var(--nx-ink-50)), transparent 60%), linear-gradient(150deg, var(--nx-chart-2), var(--nx-violet-700))',
  green: 'radial-gradient(90% 80% at 80% 20%, color-mix(in oklab, var(--nx-chart-7) 55%, var(--nx-ink-50)), transparent 60%), linear-gradient(170deg, var(--nx-chart-7), var(--nx-cyan-700))',
  ink: 'radial-gradient(90% 70% at 50% 0%, var(--nx-lapis-700), transparent 70%), linear-gradient(180deg, var(--nx-ink-800), var(--nx-ink-950))',
  aurora: 'radial-gradient(80% 80% at 50% 50%, transparent 30%, color-mix(in oklab, var(--nx-ink-950) 35%, transparent)), var(--nx-gradient-aurora)',
} as const;

const palette = [art.lapis, art.gold, art.cyan, art.rose, art.violet, art.green, art.ink, art.aurora];

/** Abstract app screens drawn in SVG: a chart, a card grid or a chat. */
function ScreenArt({ kind = 0 }: { kind?: number }) {
  const style: CSSProperties = { display: 'block', inlineSize: '100%', blockSize: '100%', color: 'var(--nx-ink-50)' };
  if (kind % 3 === 1) {
    return (
      <svg viewBox="0 0 160 100" preserveAspectRatio="xMidYMid slice" aria-hidden="true" style={style}>
        <rect x="12" y="12" width="48" height="7" rx="3.5" fill="currentColor" opacity="0.9" />
        {[0, 1, 2].map((col) =>
          [0, 1].map((row) => <rect key={`${col}-${row}`} x={12 + col * 47} y={30 + row * 33} width="40" height="26" rx="6" fill="currentColor" opacity={row === 0 && col === 1 ? 0.55 : 0.2} />),
        )}
      </svg>
    );
  }
  if (kind % 3 === 2) {
    return (
      <svg viewBox="0 0 160 100" preserveAspectRatio="xMidYMid slice" aria-hidden="true" style={style}>
        <rect x="12" y="14" width="78" height="16" rx="8" fill="currentColor" opacity="0.25" />
        <rect x="58" y="38" width="90" height="16" rx="8" fill="currentColor" opacity="0.85" />
        <rect x="12" y="62" width="62" height="16" rx="8" fill="currentColor" opacity="0.25" />
        <circle cx="136" cy="84" r="7" fill="currentColor" opacity="0.9" />
      </svg>
    );
  }
  return (
    <svg viewBox="0 0 160 100" preserveAspectRatio="xMidYMid slice" aria-hidden="true" style={style}>
      <rect x="12" y="12" width="56" height="8" rx="4" fill="currentColor" opacity="0.9" />
      <rect x="12" y="26" width="34" height="5" rx="2.5" fill="currentColor" opacity="0.45" />
      <path d="M12 84 C 40 62, 56 76, 80 56 S 124 42, 148 22 V 94 H 12 Z" fill="currentColor" opacity="0.14" />
      <path d="M12 84 C 40 62, 56 76, 80 56 S 124 42, 148 22" fill="none" stroke="currentColor" strokeWidth="3" strokeLinecap="round" />
      <circle cx="148" cy="22" r="4.5" fill="currentColor" />
    </svg>
  );
}

const projects = [
  { title: 'Aurora', subtitle: 'Brand system · London', tone: 'lapis' },
  { title: 'Mañana', subtitle: 'Reservas · Ciudad de México', tone: 'gold' },
  { title: 'Lumière', subtitle: 'Galerie en ligne · Lyon', tone: 'violet' },
  { title: '夜明け', subtitle: 'ニュースアプリ · 大阪', tone: 'cyan' },
  { title: 'Morgenrot', subtitle: 'Energie-Dashboard · Berlin', tone: 'gold' },
  { title: '星河', subtitle: '数据平台 · 深圳', tone: 'violet' },
  { title: 'فجر', subtitle: 'تطبيق مصرفي · عمّان', tone: 'lapis' },
  { title: 'Amanhecer', subtitle: 'Loja online · Porto', tone: 'cyan' },
] as const;

export function CardsBlocks() {
  const tr = useTr();
  const [slide, setSlide] = useState(0);
  const [stackOpen, setStackOpen] = useState(false);
  const [volume, setVolume] = useState(42);
  const [temperature, setTemperature] = useState(0.7);

  return (
    <Section
      id="cards-blocks"
      eyebrow={tr('بلوک‌ها · کارت، اسلایدر و کاروسل', 'Blocks · cards, sliders & carousels')}
      title={tr('کارت‌هایی که روی هم می‌نشینند، می‌چرخند و جا باز می‌کنند', 'Cards that stack, spin and make room')}
      description={tr(
        'دسته‌های چسبان با اسکرول، حلقهٔ سه‌بعدی با اینرسی و فنر، پشته‌ای که به گرید پرواز می‌کند، مدار پروژه‌ها، کارت‌های تیم، اسلایدرهای دقیق و گودالی. همه با کیبورد، راست‌چین و «حرکت کمتر».',
        'Sticky stacks driven by the scroll, a 3D ring with inertia and a spring snap, a pile that flies into a grid, orbiting projects, team cards, and sliders with a pit and a rolling number. All of them keyboard-ready, RTL-aware and calm under reduced motion.',
      )}
      code={{
        react: `import { RingCarousel, TeamCards, PitSlider } from '@nabuxai/ui-react';

<RingCarousel aria-label="Projects" items={projects} onIndexChange={setSlide} />
<TeamCards members={[{ name: 'Kenji Sato', role: 'Design lead', color: 'violet' }]} />
<PitSlider label="Volume" value={volume} onValueChange={setVolume} />`,
        blade: `<x-nx::ring-carousel label="Projects" :items="$projects" wire:model="slide" />
<x-nx::team-cards :members="$team" />
<x-nx::pit-slider label="Volume" min="0" max="100" wire:model.live="volume" />`,
      }}
    >
      <div className="sc-demos">
        <Demo title={tr('کارت‌های چسبان با اسکرول (خم سه‌بعدی)', 'Stacked scroll cards with 3D tilt')} wide>
          <StackedScrollCards
            items={[
              { title: 'Plan the launch', description: 'One board for briefs, owners and dates, shared with every team.', tone: 'lapis', cover: art.lapis, media: <ScreenArt kind={0} /> },
              { title: 'Diseña sin fricción', description: 'Componentes, tokens y movimiento en un solo sistema — en cualquier idioma.', tone: 'gold', cover: art.gold, media: <ScreenArt kind={1} /> },
              { title: 'Relisez à deux', description: 'Commentaires en ligne, versions et validations, sans quitter la page.', tone: 'violet', cover: art.violet, media: <ScreenArt kind={2} /> },
              { title: '世界へ公開する', description: 'Ship to every region at once: right-to-left, CJK and Devanagari included.', tone: 'cyan', cover: art.cyan, media: <ScreenArt kind={0} /> },
            ]}
          />
          <Snippet
            react={`<StackedScrollCards items={[
  { title: 'Plan the launch', description: '…', tone: 'lapis', cover: gradient, media: <Chart /> },
  { title: 'Diseña sin fricción', description: '…', tone: 'gold' },
]} top="6rem" />`}
            blade={`<x-nx::stacked-scroll-cards top="6rem" :items="[
  'plan' => ['title' => 'Plan the launch', 'description' => '…', 'tone' => 'lapis', 'cover' => $gradient],
  'design' => ['title' => 'Diseña sin fricción', 'tone' => 'gold'],
]">
  <x-slot:plan><img src="/img/plan.svg" alt=""></x-slot:plan>
</x-nx::stacked-scroll-cards>`}
          />
        </Demo>

        <Demo title={tr('حلقهٔ سه‌بعدی با عنوان چسبناک', 'Ring carousel with a gooey title')} wide>
          <RingCarousel
            aria-label={tr('پروژه‌ها', 'Projects')}
            items={projects.map((project, i) => ({ ...project, cover: palette[i % palette.length], media: <ScreenArt kind={i} /> }))}
          />
          <Snippet
            react={`<RingCarousel aria-label="Projects" items={[
  { title: 'Aurora', subtitle: 'Brand system', cover: gradient, href: '/work/aurora' },
  { title: '夜明け', subtitle: 'ニュースアプリ', image: '/img/yoake.jpg' },
]} index={slide} onIndexChange={setSlide} />`}
            blade={`<x-nx::ring-carousel label="Projects" wire:model="slide" :items="[
  ['title' => 'Aurora', 'subtitle' => 'Brand system', 'cover' => $gradient, 'href' => '/work/aurora'],
  ['title' => '夜明け', 'subtitle' => 'ニュースアプリ', 'image' => '/img/yoake.jpg'],
]" />`}
          />
        </Demo>

        <Demo title={tr('پشته‌ای که باز می‌شود', 'Expandable card stack')} wide>
          <ExpandableStack
            expanded={stackOpen}
            onExpandedChange={setStackOpen}
            expandLabel={tr('نمایش همه', 'Show all')}
            collapseLabel={tr('روی هم بچین', 'Stack them')}
            items={[
              { title: 'Moodboard', description: 'Colours and type for the spring campaign.', cover: art.rose, media: <ScreenArt kind={1} /> },
              { title: 'Wireframes', description: 'Checkout in three steps, not five.', cover: art.lapis, media: <ScreenArt kind={0} /> },
              { title: 'Prototipo', description: 'Flujo completo con datos reales.', cover: art.cyan, media: <ScreenArt kind={2} /> },
              { title: 'Tests utilisateurs', description: 'Huit sessions, trois découvertes.', cover: art.gold, media: <ScreenArt kind={1} /> },
              { title: 'リリース', description: 'Rollout to 10% of users first.', cover: art.violet, media: <ScreenArt kind={0} /> },
            ]}
          />
          <Snippet
            react={`<ExpandableStack items={cards} expanded={open} onExpandedChange={setOpen}
  expandLabel="Show all" collapseLabel="Stack them" />`}
            blade={`<x-nx::expandable-stack :items="$cards" wire:model="open"
  expand-label="Show all" collapse-label="Stack them" />`}
          />
        </Demo>

        <Demo title={tr('ویترین مداری پروژه‌ها', '3D work showcase')} wide>
          <OrbitShowcase items={projects.slice(0, 6).map((project, i) => ({ ...project, cover: palette[i % palette.length], media: <ScreenArt kind={i} /> }))} />
          <Snippet
            react={`<OrbitShowcase items={projects} period={40} center={<Logo />} />`}
            blade={`<x-nx::orbit-showcase :items="$projects" period="40">
  <x-slot:center><x-nx::loader.cuneiform /></x-slot:center>
</x-nx::orbit-showcase>`}
          />
        </Demo>

        <Demo title={tr('کارت‌های تیم با درخشش رنگی', 'Team cards with gradient hover')} wide>
          <TeamCards
            aria-label={tr('تیم', 'Team')}
            members={[
              { name: 'Kenji Sato', role: 'Design lead · 東京', color: 'violet' },
              { name: 'María López', role: 'Ingeniera de datos', color: 'cyan' },
              { name: 'Amara Okafor', role: 'Product · Lagos', color: 'gold' },
              { name: 'Omar Haddad', role: 'مهندس الواجهات', color: 'lapis' },
              { name: 'Lena Fischer', role: 'Forschung · Berlin', color: 'var(--nx-chart-2)' },
              { name: '김지우', role: '브랜드 디자이너', color: 'var(--nx-chart-7)' },
            ]}
          />
          <Snippet
            react={`<TeamCards aria-label="Team" members={[
  { name: 'Kenji Sato', role: 'Design lead', color: 'violet', avatar: '/img/kenji.jpg' },
  { name: 'María López', role: 'Ingeniera de datos', color: 'cyan' },
]} />`}
            blade={`<x-nx::team-cards label="Team" :members="[
  ['name' => 'Kenji Sato', 'role' => 'Design lead', 'color' => 'violet', 'avatar' => '/img/kenji.jpg'],
  ['name' => 'María López', 'role' => 'Ingeniera de datos', 'color' => 'cyan'],
]" />`}
          />
        </Demo>

        <Demo title={tr('اسلایدر عمق‌دار', 'Card slider with depth')} wide>
          <DepthCarousel
            aria-label={tr('داستان‌ها', 'Stories')}
            index={slide}
            onIndexChange={setSlide}
            items={[
              { title: 'Kyoto mornings', description: '京都の朝 — tea, rain and quiet streets.', cover: art.violet },
              { title: 'Lisboa ao entardecer', description: 'Elétricos, azulejos e luz dourada.', cover: art.gold },
              { title: 'Nuit à Montréal', description: 'Jazz, neige et poutine après minuit.', cover: art.lapis },
              { title: "İstanbul'da çay", description: 'Boğaz kıyısında ince belli bardaklar.', cover: art.rose },
              { title: 'मुंबई की बारिश', description: 'Monsoon evenings along Marine Drive.', cover: art.cyan },
            ]}
          />
          <Snippet
            react={`<DepthCarousel aria-label="Stories" items={stories} index={slide} onIndexChange={setSlide} loop />`}
            blade={`<x-nx::depth-carousel label="Stories" :items="$stories" wire:model.live="slide" />`}
          />
        </Demo>

        <Demo title={tr('پشتهٔ چرخشی با کشیدن', 'Swipe card stack')}>
          <CycleStack
            nextLabel={tr('بعدی', 'Next card')}
            items={[
              <CycleCard key="deploy" icon="zap" tone="cyan" title="Deploy finished" description="v2.4 is live in all regions." meta="2 min ago" />,
              <CycleCard key="pay" icon="check-circle" tone="gold" title="Pago recibido" description="Factura #1043 pagada por Estudio Sol." meta="hace 5 min" />,
              <CycleCard key="comment" icon="message" tone="violet" title="Nouveau commentaire" description="« On garde la version bleue ? »" meta="il y a 12 min" />,
              <CycleCard key="review" icon="star" tone="lapis" title="新しいレビュー" description="★★★★★ 「とても使いやすい」" meta="1 時間前" />,
              <CycleCard key="update" icon="bell" title="تم نشر التحديث" description="الإصدار الجديد متاح الآن." meta="منذ ساعتين" />,
            ]}
          />
          <Snippet
            react={`<CycleStack items={[
  <CycleCard icon="zap" title="Deploy finished" description="…" />,
  <MyCard />,
]} />`}
            blade={`<x-nx::cycle-stack :items="[
  'deploy' => ['icon' => 'zap', 'title' => 'Deploy finished', 'meta' => '2 min ago'],
  'custom' => [],
]">
  <x-slot:custom>…any content…</x-slot:custom>
</x-nx::cycle-stack>`}
          />
        </Demo>

        <Demo title={tr('کارت گردش‌کار', 'Workflow builder card')}>
          <div className="sc-stack">
            <WorkflowCard
              title="Nightly inventory sync"
              description="Pull orders from Shopify, update stock, ping #ops."
              status="Running"
              live
              icon="cpu"
              members={[{ name: 'Kenji Sato' }, { name: 'María López' }, { name: 'Priya Nair' }]}
              meta={[
                { label: 'Trigger', value: 'Every day · 02:00' },
                { label: 'Last run', value: '3 min ago' },
                { label: 'Success rate', value: '99.2%' },
              ]}
              actions={[
                { icon: 'play', label: 'Run now' },
                { icon: 'edit', label: 'Edit' },
                { icon: 'copy', label: 'Duplicate' },
                { icon: 'trash', label: 'Delete' },
              ]}
            />
            <WorkflowCard
              title="Resumen semanal"
              description="Envía un resumen con IA cada viernes."
              status="Pausado"
              statusTone="warning"
              icon="mail"
              members={[{ name: 'María López' }, { name: 'Omar Haddad' }]}
              meta={[{ label: 'Próximo envío', value: 'Viernes · 09:00' }]}
              actions={[
                { icon: 'play', label: 'Reanudar' },
                { icon: 'edit', label: 'Editar' },
              ]}
            />
          </div>
          <Snippet
            react={`<WorkflowCard title="Nightly sync" status="Running" live members={team}
  meta={[{ label: 'Trigger', value: '02:00' }]}
  actions={[{ icon: 'play', label: 'Run now', onClick: run }]} />`}
            blade={`<x-nx::workflow-card title="Nightly sync" status="Running" live :members="$team" :meta="['Trigger' => '02:00']">
  <x-slot:actions><x-nx::icon-button icon="play" label="Run now" wire:click="run" /></x-slot:actions>
</x-nx::workflow-card>`}
          />
        </Demo>

        <Demo title={tr('کارت‌های ویژگی متحرک', 'Animated feature cards')} wide>
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(min(100%, 15rem), 1fr))', gap: 'var(--nx-space-4)' }}>
            <FeatureCard visual="inbox" tone="cyan" title="Smart inbox" description="New mail lands on top and sorts itself; the old gets archived." />
            <FeatureCard visual="summary" tone="violet" title="Resúmenes con IA" description="Lee hilos largos y los convierte en un resumen claro." />
            <FeatureCard visual="processing" tone="gold" title="Traitement instantané" description="Des milliers de documents traités en quelques secondes." />
            <FeatureCard visual="team" tone="lapis" title="チームに自動で割り当て" description="Tickets find the right person, in any time zone." />
          </div>
          <Snippet
            react={`<FeatureCard visual="inbox" title="Smart inbox" description="…" tone="cyan" />
<FeatureCard visual="summary" | "processing" | "team" … />`}
            blade={`<x-nx::feature-card visual="inbox" title="Smart inbox" description="…" tone="cyan" />`}
          />
        </Demo>

        <Demo title={tr('گرید کشسان با اسکرول', 'Elastic grid scroll')} wide>
          <ElasticGrid
            items={[
              { cover: art.lapis, title: 'Tokyo', caption: '東京' },
              { cover: art.gold, title: 'Marrakech', caption: 'مراكش' },
              { cover: art.cyan, title: 'Reykjavík' },
              { cover: art.rose, title: 'Ciudad de México', caption: 'CDMX' },
              { cover: art.violet, title: 'Seoul', caption: '서울' },
              { cover: art.green, title: 'Nairobi' },
              { cover: art.ink, title: 'Berlin', caption: 'Kreuzberg' },
              { cover: art.aurora, title: 'Tehran', caption: 'تهران' },
              { cover: art.gold, title: 'São Paulo' },
              { cover: art.cyan, title: 'Mumbai', caption: 'मुंबई' },
              { cover: art.lapis, title: 'Paris', caption: 'Belleville' },
              { cover: art.rose, title: 'İstanbul' },
            ]}
          />
          <Snippet
            react={`<ElasticGrid columns={3} items={[{ cover: gradient, title: 'Tokyo', caption: '東京' }, <MyCell />]} />`}
            blade={`<x-nx::elastic-grid :columns="3" :items="[['cover' => $gradient, 'title' => 'Tokyo', 'caption' => '東京']]" />`}
          />
        </Demo>

        <Demo title={tr('اسلایدر گودالی مغناطیسی', 'Magnetic pit slider')}>
          <PitSlider label={tr('صدا', 'Volume · Lautstärke')} value={volume} onValueChange={setVolume} formatValue={(value) => `${value}%`} />
          <Snippet
            react={`<PitSlider label="Volume" value={volume} onValueChange={setVolume} formatValue={(v) => v + '%'} />`}
            blade={`<x-nx::pit-slider label="Volume" min="0" max="100" suffix="%" wire:model.live="volume" />`}
          />
        </Demo>

        <Demo title={tr('اسلایدر دقیق با عدد چرخان', 'Precision slider with animated value')}>
          <PrecisionSlider label={tr('دما', 'Temperature · Temperatura')} min={0} max={2} step={0.01} value={temperature} onValueChange={setTemperature} format={{ minimumFractionDigits: 2, maximumFractionDigits: 2 }} />
          <PrecisionSlider label={tr('بودجه', 'Budget · Orçamento')} prefix="$" min={0} max={5000} step={50} defaultValue={1200} />
          <Snippet
            react={`<PrecisionSlider label="Temperature" min={0} max={2} step={0.01} value={t} onValueChange={setT}
  format={{ minimumFractionDigits: 2, maximumFractionDigits: 2 }} />
<PrecisionSlider label="Budget" prefix="$" min={0} max={5000} step={50} />`}
            blade={`<x-nx::precision-slider label="Temperature" min="0" max="2" step="0.01" decimals="2" wire:model.live="temperature" />
<x-nx::precision-slider label="Budget" prefix="$" min="0" max="5000" step="50" value="1200" />`}
          />
        </Demo>
      </div>
    </Section>
  );
}
