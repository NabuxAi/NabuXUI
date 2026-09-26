{{-- Livewire demos: Dashboards & data. $state (wire:model) and ping($message) come from App\Livewire\Blocks. --}}
@php
    // Deterministic "random" numbers so every render (and every Livewire round trip) matches.
    $wave = fn (int $i, float $base, float $swing, float $noise = 0.35) => max(0, round($base + $swing * sin($i / 2.7) + $swing * $noise * sin($i * 12.9898) * cos($i * 4.1414)));

    $end = new DateTimeImmutable('2026-09-26', new DateTimeZone('UTC'));
    $heat = [];
    for ($d = 0; $d < 26 * 7; $d++) {
        $day = $end->modify("-{$d} days");
        $weekend = in_array((int) $day->format('N'), [6, 7], true);
        $v = $wave($d, $weekend ? 2 : 7, $weekend ? 2 : 6, 0.9);
        $heat[] = ['date' => $day->format('Y-m-d'), 'value' => ($d % 11 === 3) ? 0 : $v];
    }

    $months = ['Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'];
    $metrics = [
        ['id' => 'revenue', 'label' => 'Revenue', 'values' => array_map(fn ($i) => 32000 + $i * 1850 + $wave($i, 0, 2600), range(0, 11)), 'format' => ['style' => 'currency', 'currency' => 'USD', 'maximumFractionDigits' => 0], 'delta' => 12.4],
        ['id' => 'users', 'label' => 'Active users', 'values' => array_map(fn ($i) => 8200 + $i * 410 + $wave($i + 3, 0, 900), range(0, 11)), 'delta' => 4.1],
        ['id' => 'latency', 'label' => 'Latency', 'values' => array_map(fn ($i) => 240 - $i * 6 + $wave($i + 7, 0, 22), range(0, 11)), 'format' => ['style' => 'unit', 'unit' => 'millisecond', 'maximumFractionDigits' => 0], 'delta' => -8.2, 'invertDelta' => true],
    ];

    $projects = [
        ['id' => 1, 'name' => 'Atlas Migration', 'owner' => 'Amara Okafor', 'status' => 'running', 'region' => 'Lagos', 'budget' => 48200, 'progress' => 0.62, 'updated' => '2026-09-24'],
        ['id' => 2, 'name' => 'Proyecto Faro', 'owner' => 'Lucía Fernández', 'status' => 'success', 'region' => 'Madrid', 'budget' => 31750, 'progress' => 1, 'updated' => '2026-09-18'],
        ['id' => 3, 'name' => 'Projet Lumière', 'owner' => 'Élodie Moreau', 'status' => 'queued', 'region' => 'Lyon', 'budget' => 12900, 'progress' => 0.08, 'updated' => '2026-09-25'],
        ['id' => 4, 'name' => 'Projekt Nordlicht', 'owner' => 'Jonas Becker', 'status' => 'failed', 'region' => 'Hamburg', 'budget' => 22400, 'progress' => 0.41, 'updated' => '2026-09-21'],
        ['id' => 5, 'name' => '東京ローンチ', 'owner' => 'Kenji Watanabe', 'status' => 'running', 'region' => 'Tokyo', 'budget' => 67300, 'progress' => 0.77, 'updated' => '2026-09-26'],
        ['id' => 6, 'name' => '长城 API', 'owner' => 'Li Wei', 'status' => 'success', 'region' => 'Shenzhen', 'budget' => 54100, 'progress' => 1, 'updated' => '2026-09-12'],
        ['id' => 7, 'name' => 'مشروع الواحة', 'owner' => 'Layla Haddad', 'status' => 'canceled', 'region' => 'Dubai', 'budget' => 8800, 'progress' => 0.15, 'updated' => '2026-08-30'],
        ['id' => 8, 'name' => 'پروژهٔ سیمرغ', 'owner' => 'Niloufar Ahmadi', 'status' => 'running', 'region' => 'Tehran', 'budget' => 19600, 'progress' => 0.54, 'updated' => '2026-09-23'],
        ['id' => 9, 'name' => 'परियोजना गंगा', 'owner' => 'Priya Sharma', 'status' => 'queued', 'region' => 'Bengaluru', 'budget' => 26450, 'progress' => 0.03, 'updated' => '2026-09-26'],
        ['id' => 10, 'name' => 'Projeto Aurora', 'owner' => 'João Silva', 'status' => 'success', 'region' => 'São Paulo', 'budget' => 41000, 'progress' => 1, 'updated' => '2026-09-02'],
        ['id' => 11, 'name' => '프로젝트 한강', 'owner' => 'Min-jun Park', 'status' => 'running', 'region' => 'Seoul', 'budget' => 37250, 'progress' => 0.33, 'updated' => '2026-09-22'],
        ['id' => 12, 'name' => 'Proje Boğaziçi', 'owner' => 'Elif Yılmaz', 'status' => 'failed', 'region' => 'İstanbul', 'budget' => 15300, 'progress' => 0.27, 'updated' => '2026-09-19'],
    ];

    $languages = [
        ['value' => 'en', 'label' => 'English', 'description' => 'English'],
        ['value' => 'es', 'label' => 'Español', 'description' => 'Spanish'],
        ['value' => 'fr', 'label' => 'Français', 'description' => 'French'],
        ['value' => 'de', 'label' => 'Deutsch', 'description' => 'German'],
        ['value' => 'ja', 'label' => '日本語', 'description' => 'Japanese'],
        ['value' => 'zh', 'label' => '中文', 'description' => 'Chinese'],
        ['value' => 'ar', 'label' => 'العربية', 'description' => 'Arabic'],
        ['value' => 'fa', 'label' => 'فارسی', 'description' => 'Persian'],
        ['value' => 'hi', 'label' => 'हिन्दी', 'description' => 'Hindi'],
        ['value' => 'pt', 'label' => 'Português', 'description' => 'Portuguese'],
        ['value' => 'ko', 'label' => '한국어', 'description' => 'Korean'],
        ['value' => 'tr', 'label' => 'Türkçe', 'description' => 'Turkish'],
    ];

    $networks = [
        ['value' => 'ethereum', 'label' => 'Ethereum', 'icon' => 'layers', 'description' => 'Layer 1 · ~12s blocks'],
        ['value' => 'base', 'label' => 'Base', 'icon' => 'zap', 'description' => 'Layer 2 · low fees'],
        ['value' => 'arbitrum', 'label' => 'Arbitrum', 'icon' => 'shield', 'description' => 'Optimistic rollup'],
        ['value' => 'solana', 'label' => 'Solana', 'icon' => 'sparkles', 'description' => 'High throughput'],
        ['value' => 'polygon', 'label' => 'Polygon', 'icon' => 'grid', 'description' => 'Sidechain · PoS'],
        ['value' => 'bitcoin', 'label' => 'Bitcoin', 'icon' => 'lock', 'description' => 'Not supported yet', 'disabled' => true],
    ];

    $rates = ['USD' => 1, 'EUR' => 0.92, 'JPY' => 149.8, 'INR' => 83.2, 'BRL' => 5.02, 'KRW' => 1335, 'TRY' => 32.4, 'AED' => 3.6725, 'GBP' => 0.79, 'CNY' => 7.24];

    $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    $periods = [
        ['id' => '7d', 'label' => '7d', 'value' => 18420, 'delta' => 12.5, 'caption' => 'vs previous 7 days', 'labels' => $days, 'values' => array_map(fn ($i) => $wave($i, 2500, 700), range(0, 6))],
        ['id' => '30d', 'label' => '30d', 'value' => 76100, 'delta' => 4.2, 'caption' => 'vs previous 30 days', 'labels' => array_map(fn ($i) => 'Day '.($i + 1), range(0, 29)), 'values' => array_map(fn ($i) => $wave($i, 2500, 900), range(0, 29))],
        ['id' => '90d', 'label' => '90d', 'value' => 214300, 'delta' => -2.1, 'caption' => 'vs previous 90 days', 'labels' => array_map(fn ($i) => 'Week '.($i + 1), range(0, 12)), 'values' => array_map(fn ($i) => $wave($i + 4, 16000, 3500), range(0, 12))],
    ];

    $codes = ['EN', 'ES', 'FR', 'DE', 'JA', 'ZH', 'AR', 'FA', 'HI', 'PT', 'KO', 'TR'];
    $solved = [86, 64, 41, 38, 52, 47, 33, 29, 44, 36, 31, 27];
    $handed = [12, 9, 6, 7, 5, 8, 6, 4, 9, 5, 4, 6];
@endphp

<section class="pg-box" id="multi-select">
    <h2 class="pg-title">Multi-select</h2>
    <div class="pg-grid">
        <div class="pg-box" style="padding: 0; border: 0">
            <x-nx::multi-select name="languages" placeholder="Reply languages" label="Reply languages" :options="$languages" :value="['en', 'es', 'ja', 'ar']" />
            <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">Plain form field: posts <code>languages[]</code>. Backspace removes the last chip.</p>
        </div>
        <div class="pg-box" style="padding: 0; border: 0">
            <x-nx::multi-select placeholder="Networks (max 3)" label="Networks" :options="$networks" :max="3" wire:model.live="state.networks" />
            <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">wire:model.live → <code>{{ implode(', ', (array) ($state['networks'] ?? [])) ?: '—' }}</code></p>
        </div>
    </div>
</section>

<section class="pg-box" id="status-badge">
    <h2 class="pg-title">Status badge</h2>
    <div class="pg-row">
        @foreach (['running', 'success', 'failed', 'queued', 'canceled'] as $s)
            <x-nx::status-badge :status="$s" />
        @endforeach
    </div>
    <div class="pg-row">
        <span>Livewire:</span>
        <x-nx::status-badge :status="$state['job'] ?? 'queued'" live />
        @foreach (['queued' => 'Queue', 'running' => 'Run', 'success' => 'Succeed', 'failed' => 'Fail', 'canceled' => 'Cancel'] as $s => $action)
            <x-nx::button size="xs" variant="ghost" wire:click="$set('state.job', '{{ $s }}')">{{ $action }}</x-nx::button>
        @endforeach
    </div>
    <div class="pg-row" x-data="{ s: 'queued', order: ['queued', 'running', 'success', 'running', 'failed', 'canceled'], i: 0 }">
        <span>Alpine (x-bind:data-status):</span>
        <x-nx::status-badge status="queued" size="lg" x-bind:data-status="s" />
        <x-nx::button size="xs" x-on:click="i = (i + 1) % order.length; s = order[i]">Next state</x-nx::button>
    </div>
</section>

<section class="pg-box" id="data-table">
    <h2 class="pg-title">Data table</h2>
    <x-nx::data-table caption="Projects across the Nabu regions" caption-hidden sort="budget:descending" max-height="24rem" :rows="$projects" :columns="[
        ['key' => 'name', 'label' => 'Project', 'sortable' => true],
        ['key' => 'owner', 'label' => 'Owner', 'sortable' => true],
        ['key' => 'status', 'label' => 'Status', 'format' => 'status', 'sortable' => true],
        ['key' => 'region', 'label' => 'Region', 'sortable' => true],
        ['key' => 'budget', 'label' => 'Budget', 'format' => 'currency:USD', 'sortable' => true],
        ['key' => 'progress', 'label' => 'Progress', 'format' => 'percent', 'sortable' => true],
        ['key' => 'updated', 'label' => 'Updated', 'format' => 'date', 'sortable' => true],
    ]" />
</section>

<section class="pg-grid" id="charts">
    <div class="pg-box" style="grid-column: 1 / -1">
        <x-nx::heatmap title="Conversations answered" subtitle="Last 26 weeks · Tokyo, Berlin and São Paulo teams" unit="conversations" :weeks="26" :data="$heat"
            summary="{{ \NabuXUI\NabuXUI::formatNumber(array_sum(array_column($heat, 'value'))) }} conversations in 6 months" />
    </div>
    <x-nx::metric-chart style="grid-column: 1 / -1" title="Workspace health" caption="vs last month" :labels="$months" :metrics="$metrics" />
    <div class="pg-box">
        <x-nx::dot-matrix-chart title="Tickets by language" subtitle="This week" :labels="$codes" :series="[
            ['name' => 'Solved by the agent', 'values' => $solved],
            ['name' => 'Handed to a human', 'values' => $handed],
        ]" />
    </div>
    <x-nx::analytics-card title="Visitors" active="7d" :periods="$periods" />
</section>

<section class="pg-grid" id="cards">
    <x-nx::support-agent-card name="Amara Okafor" role="Billing · Lagos" status="online"
        :metrics="[
            ['label' => 'Tickets resolved', 'value' => 342, 'max' => 400, 'display' => '342 / 400'],
            ['label' => 'CSAT', 'value' => 96, 'display' => '96%', 'tone' => 'success'],
            ['label' => 'First response', 'value' => 72, 'display' => '1m 42s', 'tone' => 'info'],
        ]"
        :trend="[12, 18, 14, 22, 26, 24, 31, 29, 35, 33, 41, 44]" trend-label="Resolved, 12 weeks" trend-value="329">
        <x-slot:actions><x-nx::button variant="primary" block icon="arrow-right" wire:click="ping('Ticket assigned to Amara')">Assign a ticket</x-nx::button></x-slot:actions>
    </x-nx::support-agent-card>
    <x-nx::support-agent-card name="Kenji Watanabe" role="Onboarding · 東京" status="away"
        :metrics="[
            ['label' => 'Tickets resolved', 'value' => 214, 'max' => 400, 'display' => '214 / 400'],
            ['label' => 'CSAT', 'value' => 91, 'display' => '91%', 'tone' => 'success'],
            ['label' => 'First response', 'value' => 48, 'display' => '3m 05s', 'tone' => 'warning'],
        ]"
        :trend="[30, 28, 31, 27, 25, 26, 22, 24, 21, 23, 20, 19]" trend-label="Resolved, 12 weeks" trend-value="296"
        :action="['label' => 'Message Kenji', 'icon' => 'message', 'href' => '#cards']" />
    <x-nx::usage-card title="Storage" plan="Pro" :limit="10" unit="GB" :decimals="1" note="Resets on the 1st"
        :categories="['Documents' => 3.2, 'Voice notes' => 2.1, 'Images' => 1.4, 'Embeddings' => 0.7]"
        :action="['label' => 'Upgrade plan', 'href' => '#cards']" />
    <x-nx::usage-card title="Model tokens" plan="Growth" :limit="5" unit="M" :decimals="2" note="93% used: agents pause at 100%"
        :categories="['Replies' => 2.9, 'Summaries' => 1.1, 'Translations' => 0.66]">
        <x-slot:actions><x-nx::button size="sm" variant="primary" effect="shine" icon="zap" wire:click="ping('Upgrade requested')">Upgrade</x-nx::button></x-slot:actions>
    </x-nx::usage-card>
</section>

<section class="pg-grid" id="converter">
    <div class="pg-box">
        <x-nx::currency-converter title="Convert" :rates="$rates" :amount="1250" from="USD" to="JPY" note="Mid-market · demo rates" wire:model.live="state.fx" />
        <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">wire:model.live → <code>{{ json_encode($state['fx'] ?? null, JSON_UNESCAPED_UNICODE) }}</code></p>
    </div>
    <div class="pg-box">
        <h2 class="pg-title">Branch connector</h2>
        <x-nx::branch-connector
            :source="['label' => 'Inbound message', 'description' => 'WhatsApp · Telegram · Web', 'icon' => 'message']"
            :targets="[
                ['label' => 'Nabu agent', 'description' => 'English · Français', 'icon' => 'sparkles'],
                ['label' => 'Agente Nabu', 'description' => 'Español · Português', 'icon' => 'globe'],
                ['label' => 'Nabu エージェント', 'description' => '日本語 · 한국어', 'icon' => 'cpu'],
                ['label' => 'Human handoff', 'description' => 'Queue: billing', 'icon' => 'users', 'state' => 'idle'],
            ]" />
    </div>
</section>

<section class="pg-box" id="workspace">
    <h2 class="pg-title">Workspace shell</h2>
    <x-nx::workspace-shell brand="Nabu Desk" label="Workspace" height="34rem" :active="$state['view'] ?? 'inbox'" wire:model.live="state.view" :items="[
        ['id' => 'inbox', 'label' => 'Inbox', 'icon' => 'message', 'badge' => 12],
        ['id' => 'agents', 'label' => 'Agents', 'icon' => 'sparkles'],
        ['id' => 'knowledge', 'label' => 'Knowledge', 'icon' => 'layers'],
        ['id' => 'analytics', 'label' => 'Analytics', 'icon' => 'chart'],
        ['id' => 'settings', 'label' => 'Settings', 'icon' => 'sliders'],
    ]">
        <x-slot:header>
            <strong style="font-size: var(--nx-text-md)">{{ ucfirst($state['view'] ?? 'inbox') }}</strong>
            <x-nx::badge tone="success" pulse>Live</x-nx::badge>
            <span style="margin-inline-start: auto"><x-nx::avatar-group size="sm" :people="[['name' => 'Amara Okafor'], ['name' => 'Kenji Watanabe'], ['name' => 'Lucía Fernández'], ['name' => 'Layla Haddad']]" /></span>
        </x-slot:header>
        <div style="display: grid; gap: var(--nx-space-3)">
            @foreach ([['Olá! O pedido #4821 chegou?', 'Beatriz · WhatsApp'], ['How do I rotate my API key?', 'Sam · Web'], ['¿Tienen factura en euros?', 'Carmen · Telegram'], ['返品の手続きを教えてください', 'Haruto · LINE'], ['هل يمكنني تغيير خطتي؟', 'Omar · WhatsApp'], ['Rechnung für September?', 'Lena · E-mail']] as [$text, $who])
                <div class="pg-box" style="padding: var(--nx-space-4); gap: var(--nx-space-1)">
                    <span style="font-weight: 600">{{ $text }}</span>
                    <span style="font-size: var(--nx-text-xs); color: var(--nx-text-muted)">{{ $who }}</span>
                </div>
            @endforeach
        </div>
        <x-slot:footer>
            <div style="display: flex; align-items: center; gap: 0.75rem; min-block-size: 2.5rem; padding-inline: 0.75rem; font-size: var(--nx-text-sm); font-weight: 500; white-space: nowrap"><x-nx::avatar name="Hussein" size="xs" status="online" /><span class="nx-workspace-label">Hussein</span></div>
        </x-slot:footer>
        <x-slot:prompt>
            <x-nx::prompt placeholder="Ask Nabu anything — en cualquier idioma…" x-on:submit.prevent="$wire.ping('Nabu is on it')" />
        </x-slot:prompt>
    </x-nx::workspace-shell>
</section>

<section class="pg-box" id="timeline">
    <h2 class="pg-title">Curved timeline</h2>
    <x-nx::curved-timeline label="Roadmap" :items="[
        ['date' => 'Q1 2025', 'title' => 'Private beta in Berlin', 'description' => 'Forty support teams, three languages, one inbox.'],
        ['date' => 'Q3 2025', 'title' => 'Twelve languages', 'description' => 'Español, Français, 日本語, العربية and more, answered natively.'],
        ['date' => 'Q1 2026', 'title' => 'Voice in Tokyo', 'description' => '音声での応答: agents that listen and speak on the phone.'],
        ['date' => 'Q2 2026', 'title' => 'São Paulo office', 'description' => 'Atendimento em português, 24 horas por dia.'],
        ['date' => 'Q4 2026', 'title' => 'Right-to-left everywhere', 'description' => 'فارسی و العربية with mirrored layouts across every surface.'],
    ]" />
</section>

<section class="pg-box" id="comparison">
    <h2 class="pg-title">Comparison table</h2>
    <x-nx::comparison-table caption="Compare Nabu plans" recommended="growth"
        :plans="[
            ['id' => 'starter', 'name' => 'Starter', 'price' => '$0', 'period' => '/mo', 'description' => 'For trying Nabu out', 'action' => ['label' => 'Start free', 'href' => '#comparison']],
            ['id' => 'growth', 'name' => 'Growth', 'price' => '$49', 'period' => '/mo', 'description' => 'For growing support teams', 'action' => ['label' => 'Choose Growth', 'href' => '#comparison']],
            ['id' => 'scale', 'name' => 'Scale', 'price' => '$199', 'period' => '/mo', 'description' => 'For global operations', 'action' => ['label' => 'Talk to sales', 'href' => '#comparison']],
        ]"
        :features="[
            ['group' => 'Agents', 'label' => 'Languages', 'values' => ['starter' => '3', 'growth' => '12', 'scale' => '40+']],
            ['group' => 'Agents', 'label' => 'Voice replies', 'hint' => 'WhatsApp and Telegram', 'values' => ['starter' => false, 'growth' => true, 'scale' => true]],
            ['group' => 'Agents', 'label' => 'Custom knowledge', 'values' => ['starter' => true, 'growth' => true, 'scale' => true]],
            ['group' => 'Channels', 'label' => 'WhatsApp & Telegram', 'values' => ['starter' => true, 'growth' => true, 'scale' => true]],
            ['group' => 'Channels', 'label' => 'Phone lines', 'values' => ['starter' => false, 'growth' => false, 'scale' => true]],
            ['group' => 'Security', 'label' => 'SSO / SAML', 'values' => ['starter' => false, 'growth' => false, 'scale' => true]],
            ['group' => 'Security', 'label' => 'Audit log', 'values' => ['starter' => false, 'growth' => true, 'scale' => true]],
            ['group' => 'Security', 'label' => 'Data residency', 'values' => ['starter' => '—', 'growth' => 'EU', 'scale' => 'EU · US · Asia']],
        ]" />
</section>
