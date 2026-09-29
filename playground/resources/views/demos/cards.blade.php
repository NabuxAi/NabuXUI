{{-- Livewire demos: Cards, sliders & carousels. --}}
@php
    // Covers are gradients built from the palette tokens: no hotlinked images.
    $art = [
        'lapis' => 'radial-gradient(120% 90% at 12% 0%, var(--nx-lapis-300), transparent 58%), linear-gradient(140deg, var(--nx-lapis-600), var(--nx-violet-700))',
        'violet' => 'radial-gradient(110% 80% at 90% 10%, var(--nx-violet-300), transparent 55%), linear-gradient(160deg, var(--nx-violet-600), var(--nx-lapis-950))',
        'cyan' => 'radial-gradient(100% 80% at 15% 15%, var(--nx-cyan-300), transparent 58%), linear-gradient(200deg, var(--nx-cyan-600), var(--nx-lapis-800))',
        'gold' => 'radial-gradient(100% 90% at 80% 100%, var(--nx-gold-300), transparent 60%), linear-gradient(160deg, var(--nx-gold-500), var(--nx-violet-700))',
        'rose' => 'radial-gradient(100% 80% at 20% 0%, color-mix(in oklab, var(--nx-chart-2) 60%, var(--nx-ink-50)), transparent 60%), linear-gradient(150deg, var(--nx-chart-2), var(--nx-violet-700))',
        'green' => 'radial-gradient(90% 80% at 80% 20%, color-mix(in oklab, var(--nx-chart-7) 55%, var(--nx-ink-50)), transparent 60%), linear-gradient(170deg, var(--nx-chart-7), var(--nx-cyan-700))',
        'ink' => 'radial-gradient(90% 70% at 50% 0%, var(--nx-lapis-700), transparent 70%), linear-gradient(180deg, var(--nx-ink-800), var(--nx-ink-950))',
    ];

    $projects = [
        ['title' => 'Aurora', 'subtitle' => 'Brand system · London', 'cover' => $art['lapis'], 'tone' => 'lapis'],
        ['title' => 'Mañana', 'subtitle' => 'Reservas · Ciudad de México', 'cover' => $art['gold'], 'tone' => 'gold'],
        ['title' => 'Lumière', 'subtitle' => 'Galerie en ligne · Lyon', 'cover' => $art['violet'], 'tone' => 'violet'],
        ['title' => '夜明け', 'subtitle' => 'ニュースアプリ · 大阪', 'cover' => $art['cyan'], 'tone' => 'cyan'],
        ['title' => 'Morgenrot', 'subtitle' => 'Energie-Dashboard · Berlin', 'cover' => $art['rose'], 'tone' => 'gold'],
        ['title' => '星河', 'subtitle' => '数据平台 · 深圳', 'cover' => $art['green'], 'tone' => 'violet'],
        ['title' => 'فجر', 'subtitle' => 'تطبيق مصرفي · عمّان', 'cover' => $art['ink'], 'tone' => 'lapis'],
        ['title' => 'Amanhecer', 'subtitle' => 'Loja online · Porto', 'cover' => $art['cyan'], 'tone' => 'cyan'],
    ];

    $team = [
        ['name' => 'Kenji Sato', 'role' => 'Design lead · 東京', 'color' => 'violet'],
        ['name' => 'María López', 'role' => 'Ingeniera de datos', 'color' => 'cyan'],
        ['name' => 'Amara Okafor', 'role' => 'Product · Lagos', 'color' => 'gold'],
        ['name' => 'Omar Haddad', 'role' => 'مهندس الواجهات', 'color' => 'lapis'],
        ['name' => 'Lena Fischer', 'role' => 'Forschung · Berlin', 'color' => 'var(--nx-chart-2)'],
        ['name' => 'Priya Nair', 'role' => 'प्रोडक्ट मैनेजर', 'color' => 'var(--nx-chart-7)'],
    ];

    $stories = [
        'kyoto' => ['title' => 'Kyoto mornings', 'description' => '京都の朝 — tea, rain and quiet streets.', 'cover' => $art['violet']],
        'lisboa' => ['title' => 'Lisboa ao entardecer', 'description' => 'Elétricos, azulejos e luz dourada.', 'cover' => $art['gold']],
        'montreal' => ['title' => 'Nuit à Montréal', 'description' => 'Jazz, neige et poutine après minuit.', 'cover' => $art['lapis']],
        'istanbul' => ['title' => "İstanbul'da çay", 'description' => 'Boğaz kıyısında ince belli bardaklar.', 'cover' => $art['rose']],
        'mumbai' => ['title' => 'मुंबई की बारिश', 'description' => 'Monsoon evenings along Marine Drive.', 'cover' => $art['cyan']],
    ];

    $cities = [
        ['cover' => $art['lapis'], 'title' => 'Tokyo', 'caption' => '東京'],
        ['cover' => $art['gold'], 'title' => 'Marrakech', 'caption' => 'مراكش'],
        ['cover' => $art['cyan'], 'title' => 'Reykjavík'],
        ['cover' => $art['rose'], 'title' => 'Ciudad de México', 'caption' => 'CDMX'],
        ['cover' => $art['violet'], 'title' => 'Seoul', 'caption' => '서울'],
        ['cover' => $art['green'], 'title' => 'Nairobi'],
        ['cover' => $art['ink'], 'title' => 'Berlin', 'caption' => 'Kreuzberg'],
        ['cover' => $art['lapis'], 'title' => 'Tehran', 'caption' => 'تهران'],
        ['cover' => $art['gold'], 'title' => 'São Paulo'],
    ];
@endphp

<section class="pg-box">
    <h2 class="pg-title">Stacked scroll cards</h2>
    <x-nx::stacked-scroll-cards top="1.5rem" height="min(24rem, 64svh)" :items="[
        'plan' => ['title' => 'Plan the launch', 'description' => 'One board for briefs, owners and dates, shared with every team.', 'tone' => 'lapis', 'cover' => $art['lapis']],
        'design' => ['title' => 'Diseña sin fricción', 'description' => 'Componentes, tokens y movimiento en un solo sistema.', 'tone' => 'gold', 'cover' => $art['gold']],
        'review' => ['title' => 'Relisez à deux', 'description' => 'Commentaires en ligne, versions et validations.', 'tone' => 'violet', 'cover' => $art['violet']],
        'ship' => ['title' => '世界へ公開する', 'description' => 'Right-to-left, CJK and Devanagari included.', 'tone' => 'cyan', 'cover' => $art['cyan']],
    ]">
        <x-slot:ship><div style="display:grid;place-items:center;block-size:100%;color:var(--nx-ink-50);font:700 var(--nx-text-5xl)/1 var(--nx-font-display)">🌏</div></x-slot:ship>
    </x-nx::stacked-scroll-cards>
</section>

<section class="pg-box">
    <h2 class="pg-title">Ring carousel</h2>
    <x-nx::ring-carousel label="Projects" :items="$projects" wire:model="state.ring" />
    <p style="margin:0">Front card (wire:model, sent with the next request): <code>{{ $state['ring'] ?? 0 }}</code></p>
</section>

<section class="pg-box">
    <h2 class="pg-title">Expandable card stack</h2>
    <x-nx::expandable-stack wire:model="state.open" expand-label="Show all" collapse-label="Stack them" :items="[
        'mood' => ['title' => 'Moodboard', 'description' => 'Colours and type for the spring campaign.', 'cover' => $art['rose']],
        'wire' => ['title' => 'Wireframes', 'description' => 'Checkout in three steps, not five.', 'cover' => $art['lapis']],
        'proto' => ['title' => 'Prototipo', 'description' => 'Flujo completo con datos reales.', 'cover' => $art['cyan']],
        'tests' => ['title' => 'Tests utilisateurs', 'description' => 'Huit sessions, trois découvertes.', 'cover' => $art['gold']],
        'ship' => ['title' => 'リリース', 'description' => 'Rollout to 10% of users first.', 'cover' => $art['violet']],
    ]" />
</section>

<section class="pg-box">
    <h2 class="pg-title">3D work showcase</h2>
    <x-nx::orbit-showcase :items="array_slice($projects, 0, 6)" />
</section>

<section class="pg-box">
    <h2 class="pg-title">Team cards</h2>
    <x-nx::team-cards label="Team" :members="$team" />
</section>

<section class="pg-box">
    <h2 class="pg-title">Card slider with depth</h2>
    <x-nx::depth-carousel label="Stories" :items="$stories" wire:model.live="state.slide" />
    <p style="margin:0">Slide on the server (wire:model.live): <code>{{ $state['slide'] ?? 0 }}</code></p>
</section>

<section class="pg-grid">
    <div class="pg-box">
        <h2 class="pg-title">Swipe card stack</h2>
        <x-nx::cycle-stack next-label="Next card" wire:model="state.card" :items="[
            'deploy' => ['icon' => 'zap', 'tone' => 'cyan', 'title' => 'Deploy finished', 'description' => 'v2.4 is live in all regions.', 'meta' => '2 min ago'],
            'pay' => ['icon' => 'check-circle', 'tone' => 'gold', 'title' => 'Pago recibido', 'description' => 'Factura #1043 pagada por Estudio Sol.', 'meta' => 'hace 5 min'],
            'comment' => ['icon' => 'message', 'tone' => 'violet', 'title' => 'Nouveau commentaire', 'description' => '« On garde la version bleue ? »', 'meta' => 'il y a 12 min'],
            'review' => ['icon' => 'star', 'tone' => 'lapis', 'title' => '新しいレビュー', 'description' => '★★★★★ 「とても使いやすい」', 'meta' => '1 時間前'],
            'update' => ['icon' => 'bell', 'title' => 'تم نشر التحديث', 'description' => 'الإصدار الجديد متاح الآن.', 'meta' => 'منذ ساعتين'],
        ]" />
    </div>
    <div class="pg-box">
        <h2 class="pg-title">Workflow builder card</h2>
        <x-nx::workflow-card title="Nightly inventory sync" description="Pull orders, update stock, ping #ops." status="Running" live icon="cpu"
            :members="[['name' => 'Kenji Sato'], ['name' => 'María López'], ['name' => 'Priya Nair']]"
            :meta="['Trigger' => 'Every day · 02:00', 'Last run' => '3 min ago', 'Success rate' => '99.2%']">
            <x-slot:actions>
                <x-nx::icon-button icon="play" label="Run now" size="sm" wire:click="ping('Workflow started')" />
                <x-nx::icon-button icon="edit" label="Edit" size="sm" />
                <x-nx::icon-button icon="copy" label="Duplicate" size="sm" wire:click="ping('Workflow duplicated')" />
            </x-slot:actions>
        </x-nx::workflow-card>
        <x-nx::workflow-card title="Resumen semanal" description="Envía un resumen con IA cada viernes." status="Pausado" status-tone="warning" icon="mail"
            :members="[['name' => 'María López'], ['name' => 'Omar Haddad']]" :meta="['Próximo envío' => 'Viernes · 09:00']">
            <x-slot:actions>
                <x-nx::icon-button icon="play" label="Reanudar" size="sm" wire:click="ping('Reanudado')" />
            </x-slot:actions>
        </x-nx::workflow-card>
    </div>
</section>

<section class="pg-box">
    <h2 class="pg-title">Animated feature cards</h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,15rem),1fr));gap:var(--nx-space-4)">
        <x-nx::feature-card visual="inbox" tone="cyan" title="Smart inbox" description="New mail lands on top and sorts itself." />
        <x-nx::feature-card visual="summary" tone="violet" title="Resúmenes con IA" description="Lee hilos largos y los resume." />
        <x-nx::feature-card visual="processing" tone="gold" title="Traitement instantané" description="Des milliers de documents en quelques secondes." />
        <x-nx::feature-card visual="team" tone="lapis" title="チームに自動で割り当て" description="Tickets find the right person." />
    </div>
</section>

<section class="pg-box">
    <h2 class="pg-title">Elastic grid scroll</h2>
    <x-nx::elastic-grid :columns="3" :items="$cities" />
</section>

<section class="pg-grid">
    <div class="pg-box">
        <h2 class="pg-title">Magnetic pit slider</h2>
        <x-nx::pit-slider label="Volume · Lautstärke" min="0" max="100" value="42" suffix="%" wire:model.live="state.volume" />
        <p style="margin:0">Volume on the server: <code>{{ $state['volume'] ?? '—' }}</code></p>
    </div>
    <div class="pg-box">
        <h2 class="pg-title">Precision slider</h2>
        <x-nx::precision-slider label="Temperature · Temperatura" min="0" max="2" step="0.01" value="0.7" decimals="2" wire:model.live="state.temperature" />
        <x-nx::precision-slider label="Budget · Orçamento" prefix="$" min="0" max="5000" step="50" value="1200" />
        <p style="margin:0">Temperature on the server: <code>{{ $state['temperature'] ?? '—' }}</code></p>
    </div>
</section>

<section class="pg-box">
    <h2 class="pg-title">Infinite grid</h2>
    <x-nx::infinite-grid>
        <h2 style="margin:0;font:700 var(--nx-text-display)/1.15 var(--nx-font-display)">شبکه‌ای که تمامی ندارد</h2>
        <p style="margin:0">نشانگر را حرکت بده: نقطه‌هایی که لمس می‌کند روشن می‌شوند؛ تراکم را هم با دکمه‌های گوشه کم و زیاد کن.</p>
    </x-nx::infinite-grid>
</section>
