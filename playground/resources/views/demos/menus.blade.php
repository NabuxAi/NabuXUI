{{-- Livewire demos: menus, navigation & morphing panels. --}}
@php
    $people = [
        ['id' => 'kenji', 'name' => 'Kenji Sato', 'email' => 'kenji@nabux.jp'],
        ['id' => 'maria', 'name' => 'María López', 'email' => 'maria@nabux.es'],
        ['id' => 'amara', 'name' => 'Amara Okafor', 'email' => 'amara@nabux.ng'],
        ['id' => 'omar', 'name' => 'Omar Haddad', 'email' => 'omar@nabux.ae'],
        ['id' => 'lena', 'name' => 'Lena Fischer', 'email' => 'lena@nabux.de'],
        ['id' => 'priya', 'name' => 'Priya Nair', 'email' => 'priya@nabux.in'],
        ['id' => 'jiwoo', 'name' => '김지우', 'email' => 'jiwoo@nabux.kr'],
        ['id' => 'wei', 'name' => 'Zhang Wei', 'email' => 'wei@nabux.cn'],
    ];
@endphp

<section class="pg-grid">
    <div class="pg-box">
        <h2 class="pg-title">Fold menu</h2>
        <p style="margin:0;color:var(--nx-text-muted)">Each section unfolds like paper; Escape folds it back.</p>
        <div class="pg-row">
            <x-nx::fold-menu label="Menu · Menú · メニュー" :sections="[
                ['title' => 'Explore · Explorar', 'links' => [
                    ['label' => 'Home', 'href' => '#', 'icon' => 'home', 'current' => true],
                    ['label' => 'Agents · エージェント', 'href' => '#', 'icon' => 'sparkles', 'description' => 'Support that speaks every language'],
                    ['label' => 'Pricing · Tarifs', 'href' => '#', 'icon' => 'star'],
                ]],
                ['title' => 'Resources · Ressourcen', 'links' => [
                    ['label' => 'Docs · 文档', 'href' => '#', 'icon' => 'file'],
                    ['label' => 'Changelog · سجل التغييرات', 'href' => '#', 'icon' => 'layers'],
                ]],
                ['title' => 'Company · शिरकत', 'links' => [
                    ['label' => 'Say hi · 안녕하세요', 'icon' => 'message', 'click' => '$wire.ping(\'Hello from the fold menu\')'],
                ]],
            ]">
                <x-slot:footer>
                    <x-nx::button variant="primary" size="sm" block x-on:click="$wire.ping('Started a trial'); close()">Start free · Comenzar gratis</x-nx::button>
                </x-slot:footer>
            </x-nx::fold-menu>
        </div>
    </div>

    <div class="pg-box">
        <h2 class="pg-title">Morphing filter</h2>
        <p style="margin:0;color:var(--nx-text-muted)">The button grows into its list. <code>wire:model.live</code> on the checkboxes.</p>
        <div class="pg-row" style="min-block-size: 20rem; align-items: start">
            <x-nx::morph-menu label="Filter · Filtro" name="filters" :value="$state['filters'] ?? []" wire:model.live="state.filters" :options="[
                ['value' => 'design', 'label' => 'Design', 'icon' => 'edit'],
                ['value' => 'engineering', 'label' => 'Engineering · Ingeniería', 'icon' => 'cpu'],
                ['value' => 'support', 'label' => 'Support · Support client', 'icon' => 'message'],
                ['value' => 'sales', 'label' => 'Sales · Vertrieb', 'icon' => 'chart'],
                ['value' => 'research', 'label' => 'Research · 研究', 'icon' => 'globe'],
            ]" />
        </div>
        <p style="margin:0">Selected: <code>{{ json_encode($state['filters'] ?? []) }}</code></p>
    </div>

    <div class="pg-box">
        <h2 class="pg-title">Morph tabs</h2>
        <x-nx::morph-tabs label="Workspace" :value="$state['tab'] ?? 'home'" wire:model.live="state.tab" :items="[
            ['value' => 'home', 'label' => 'Home', 'icon' => 'home'],
            ['value' => 'inbox', 'label' => 'Inbox · Bandeja', 'icon' => 'mail'],
            ['value' => 'reports', 'label' => 'Reports · 报告', 'icon' => 'chart'],
            ['value' => 'team', 'label' => 'Team · فريق', 'icon' => 'users'],
        ]">
            <x-slot:home><p style="margin:0">Welcome back — Bienvenido de nuevo.</p></x-slot:home>
            <x-slot:inbox><p style="margin:0">3 new conversations · 3 nuevas conversaciones.</p></x-slot:inbox>
            <x-slot:reports><p style="margin:0">Resolution rate 94% · 解决率 94%</p></x-slot:reports>
            <x-slot:team><p style="margin:0">8 teammates across 6 time zones.</p></x-slot:team>
        </x-nx::morph-tabs>
        <p style="margin:0">Tab: <code>{{ $state['tab'] ?? 'home' }}</code></p>
    </div>

    <div class="pg-box">
        <h2 class="pg-title">Stack menu</h2>
        <p style="margin:0;color:var(--nx-text-muted)">Push into levels, search across all of them.</p>
        <div class="pg-row">
            <x-nx::stack-menu title="Settings" x-on:nx-select="$wire.ping('Chose ' + $event.detail.label)" :items="[
                ['id' => 'appearance', 'label' => 'Appearance', 'icon' => 'sun', 'children' => [
                    ['id' => 'theme', 'label' => 'Theme', 'icon' => 'moon', 'children' => [
                        ['id' => 'light', 'label' => 'Light'],
                        ['id' => 'dark', 'label' => 'Dark'],
                        ['id' => 'system', 'label' => 'System'],
                    ]],
                    ['id' => 'density', 'label' => 'Density', 'icon' => 'sliders', 'shortcut' => '⌘D'],
                ]],
                ['id' => 'language', 'label' => 'Language · Idioma · 言語', 'icon' => 'globe', 'children' => [
                    ['id' => 'en', 'label' => 'English'], ['id' => 'es', 'label' => 'Español'], ['id' => 'fr', 'label' => 'Français'],
                    ['id' => 'de', 'label' => 'Deutsch'], ['id' => 'ja', 'label' => '日本語'], ['id' => 'zh', 'label' => '中文'],
                    ['id' => 'ar', 'label' => 'العربية'], ['id' => 'fa', 'label' => 'فارسی'], ['id' => 'hi', 'label' => 'हिन्दी'],
                    ['id' => 'pt', 'label' => 'Português'], ['id' => 'ko', 'label' => '한국어'], ['id' => 'tr', 'label' => 'Türkçe'],
                ]],
                ['id' => 'notifications', 'label' => 'Notifications', 'icon' => 'bell', 'description' => 'Email, push, digests'],
                ['id' => 'signout', 'label' => 'Sign out', 'icon' => 'lock', 'tone' => 'danger'],
            ]">
                <x-slot:trigger><x-nx::button icon="sliders">Settings · Ajustes</x-nx::button></x-slot:trigger>
            </x-nx::stack-menu>
        </div>
    </div>

    <div class="pg-box">
        <h2 class="pg-title">Activity</h2>
        <div class="pg-row">
            <x-nx::activity-dropdown x-on:nx-mark-all-read="$wire.ping('All caught up')" :items="[
                ['id' => 'a1', 'actor' => ['name' => 'Amara Okafor'], 'text' => 'commented on', 'target' => 'Q3 roadmap', 'time' => now()->subMinutes(3), 'unread' => true, 'href' => '#'],
                ['id' => 'a2', 'actor' => ['name' => 'Kenji Sato'], 'text' => 'mentioned you in', 'target' => '設計レビュー', 'time' => now()->subMinutes(42), 'unread' => true],
                ['id' => 'a3', 'actor' => ['name' => 'María López'], 'text' => 'shared', 'target' => 'Informe de ventas', 'time' => now()->subHours(5), 'unread' => true],
                ['id' => 'a4', 'actor' => ['name' => 'Omar Haddad'], 'text' => 'approved', 'target' => 'خطة الإطلاق', 'time' => now()->subDay()],
                ['id' => 'a5', 'actor' => ['name' => 'Lena Fischer'], 'text' => 'joined the workspace', 'time' => 'Last week'],
            ]" />
            <x-nx::activity-dropdown title="Empty inbox" :items="[]" />
        </div>
    </div>

    <div class="pg-box">
        <h2 class="pg-title">Members</h2>
        <div class="pg-row">
            <x-nx::member-selector name="members" :members="$people" :value="$state['members'] ?? ['kenji', 'maria', 'amara']"
                wire:model.live="state.members" roles-model="state.roles" :roles="$state['roles'] ?? []" max="4" />
        </div>
        <p style="margin:0">Members: <code>{{ json_encode($state['members'] ?? ['kenji', 'maria', 'amara']) }}</code></p>
        <p style="margin:0">Roles: <code>{{ json_encode($state['roles'] ?? []) }}</code></p>
    </div>
</section>

<section class="pg-grid">
    <div class="pg-box" style="min-block-size: 26rem; align-content: space-between">
        <h2 class="pg-title">Dock panels</h2>
        <div class="pg-row" style="justify-content: center; margin-block-start: auto">
            <x-nx::dock-panels label="Quick panels" :items="[
                ['id' => 'music', 'label' => 'Now playing', 'icon' => 'play'],
                ['id' => 'inbox', 'label' => 'Inbox', 'icon' => 'mail'],
                ['id' => 'team', 'label' => 'Team', 'icon' => 'users'],
                ['id' => 'ideas', 'label' => 'Ideas', 'icon' => 'sparkles'],
            ]">
                <x-slot:music>
                    <h3 class="nx-dock-panels-heading">Now playing · Ahora suena</h3>
                    <p style="margin:0;color:var(--nx-text-muted)">Lo-fi beats for deep focus — 集中</p>
                </x-slot:music>
                <x-slot:inbox>
                    <h3 class="nx-dock-panels-heading">Inbox</h3>
                    <p style="margin:0;color:var(--nx-text-muted)">3 unread from Amara, Kenji and María.</p>
                    <x-nx::button size="sm" variant="primary" style="margin-block-start:.75rem" x-on:click="$wire.ping('Inbox opened')">Open inbox</x-nx::button>
                </x-slot:inbox>
                <x-slot:team>
                    <h3 class="nx-dock-panels-heading">Team · Équipe</h3>
                    <x-nx::avatar-group :people="array_map(fn ($p) => ['name' => $p['name']], $people)" max="5" />
                </x-slot:team>
                <x-slot:ideas>
                    <h3 class="nx-dock-panels-heading">Ideas · Ideen · 아이디어</h3>
                    <p style="margin:0;color:var(--nx-text-muted)">Ship the Persian keyboard shortcuts next.</p>
                </x-slot:ideas>
            </x-nx::dock-panels>
        </div>
    </div>

    <div class="pg-box" style="min-block-size: 26rem; justify-items: center">
        <h2 class="pg-title" style="justify-self: start">Audio room</h2>
        <x-nx::audio-room title="Design crit · 設計レビュー · مراجعة" :listeners="1284" x-on:nx-room-leave="$wire.ping('Left the room · Salió de la sala')" :members="[
            ['name' => 'Kenji Sato', 'role' => 'Host', 'speaking' => true],
            ['name' => 'María López', 'speaking' => true],
            ['name' => 'Amara Okafor', 'muted' => true],
            ['name' => 'Omar Haddad'],
            ['name' => 'Priya Nair', 'muted' => true],
            ['name' => 'Zhang Wei'],
        ]" />
    </div>

    <div class="pg-box">
        <h2 class="pg-title">Stacked accordion</h2>
        <x-nx::stacked-accordion type="single" :value="$state['faq'] ?? ['shipping']" wire:model.live="state.faq" :items="[
            ['id' => 'shipping', 'title' => 'Shipping · Envío', 'subtitle' => 'Worldwide in 3–5 days', 'icon' => 'globe', 'content' => 'We ship to 140 countries — de Lisboa a Tokio.'],
            ['id' => 'returns', 'title' => 'Returns · Retours', 'subtitle' => '30 days, no questions', 'icon' => 'arrow-left', 'content' => 'Send it back within 30 days for a full refund.'],
            ['id' => 'payments', 'title' => 'Payments · Zahlungen', 'subtitle' => 'Cards, wallets, bank transfer', 'icon' => 'lock', 'content' => 'Every payment is encrypted end to end.'],
            ['id' => 'support', 'title' => 'Support · サポート', 'subtitle' => 'Humans and agents, 24/7', 'icon' => 'message', 'content' => 'Reply in any language — English, Español, العربية, 中文…'],
        ]" />
        <p style="margin:0">Open: <code>{{ json_encode($state['faq'] ?? ['shipping']) }}</code></p>
    </div>

    <div class="pg-box">
        <h2 class="pg-title">Voice recorder</h2>
        <p style="margin:0;color:var(--nx-text-muted)">Never asks for the microphone: the bars are simulated here.</p>
        <div class="pg-row">
            <x-nx::voice-recorder max-duration="120" x-on:nx-record-stop="$wire.ping('Voice note · ' + $event.detail.duration + 's')" />
        </div>
    </div>

    <div class="pg-box" style="justify-items: center">
        <h2 class="pg-title" style="justify-self: start">Registration</h2>
        <x-nx::registration-card model="state.registration" wire:submit="$set('state.registered', true)" :success="! empty($state['registered'])" currency="EUR"
            :event="['title' => 'Nabu Summit 2026 · Cumbre · Sommet · 峰会 · قمة', 'date' => '12 Oct 2026', 'location' => 'Lisboa', 'badge' => 'Hybrid']"
            :tickets="[
                ['id' => 'general', 'label' => 'General', 'price' => 49, 'description' => 'Talks, workshops, lunch'],
                ['id' => 'vip', 'label' => 'VIP · 贵宾', 'price' => 149, 'description' => 'Front row and the speakers’ dinner', 'max' => 2],
                ['id' => 'student', 'label' => 'Student · Estudiante', 'price' => 0, 'max' => 1],
            ]" />
    </div>
</section>
