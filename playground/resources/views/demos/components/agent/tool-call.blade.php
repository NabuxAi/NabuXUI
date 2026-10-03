{{--
    A travel agent working through a request: three tool calls run in turn (queued →
    running → done), each opening to its input and output. Below, a deploy agent's
    call that failed, opened on its error.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $labels = [
        'input' => $say('Input', 'ورودی'), 'output' => $say('Output', 'خروجی'),
        'queued' => $say('Queued', 'در صف'), 'running' => $say('Running', 'در حال اجرا'),
        'success' => $say('Done', 'انجام شد'), 'error' => $say('Failed', 'ناموفق'),
    ];
@endphp
<style>
    .agd-run { display: grid; gap: .5rem; max-inline-size: 44rem; }
    .agd-run-head { display: flex; align-items: center; gap: .625rem; margin: 0 0 .25rem; font: 600 var(--nx-text-sm) / 1.4 var(--nx-font-sans); color: var(--nx-text-muted); }
</style>

<section class="agd-run"
    x-data="{
        st: ['queued', 'queued', 'queued'],
        timers: [],
        run() {
            this.timers.forEach(clearTimeout);
            this.st = ['running', 'queued', 'queued'];
            const at = [1400, 2600, 4200];
            this.timers = [
                setTimeout(() => this.st = ['success', 'running', 'queued'], at[0]),
                setTimeout(() => this.st = ['success', 'success', 'running'], at[1]),
                setTimeout(() => this.st = ['success', 'success', 'success'], at[2]),
            ];
        },
        get busy() { return this.st.some((s) => s !== 'success') },
    }" x-init="run()">
    <p class="agd-run-head">
        <x-nx::thinking-orbs size="sm" x-bind:data-state="busy ? 'thinking' : 'idle'" />
        {{ $say('Plan a weekend in Isfahan for two, under $600', 'یک آخر هفته در اصفهان برای دو نفر، زیر ۶۰۰ دلار') }}
    </p>
    <x-nx::tool-call name="search_flights" status="queued" x-bind:data-status="st[0]" :labels="$labels" :duration="1380"
        :args="['from' => 'THR', 'to' => 'IFN', 'date' => '2026-10-15', 'passengers' => 2]"
        :output="['results' => 6, 'cheapest' => ['airline' => 'Iran Air', 'depart' => '07:40', 'price' => 84], 'currency' => 'USD']" />
    <x-nx::tool-call name="search_hotels" status="queued" x-bind:data-status="st[1]" :labels="$labels" :duration="1120"
        :args="['city' => 'Isfahan', 'nights' => 2, 'max_price' => 120, 'near' => 'Naqsh-e Jahan']"
        :output="[['name' => 'Abbasi Hotel', 'price' => 118, 'rating' => 4.7], ['name' => 'Ghasr Monshi', 'price' => 96, 'rating' => 4.8]]" />
    <x-nx::tool-call name="get_weather" status="queued" x-bind:data-status="st[2]" :labels="$labels" :duration="410"
        :args="['city' => 'Isfahan', 'days' => 3]" :output="'Sunny, 24°C / 11°C, wind 9 km/h'" />
    <div class="pg-row">
        <x-nx::button size="sm" variant="ghost" icon="sparkles" x-on:click="run()">{{ $say('Run again', 'اجرای دوباره') }}</x-nx::button>
    </div>
</section>

<section class="agd-run">
    <p class="agd-run-head">{{ $say('Deploy agent · production', 'عامل استقرار · محیط اصلی') }}</p>
    <x-nx::tool-call name="run_migrations" status="error" open :labels="$labels" :duration="2310"
        :args="['database' => 'orders', 'step' => true, 'pretend' => false]"
        output="SQLSTATE[42S21]: Column already exists: 1060 Duplicate column name 'archived_at'" />
    <x-nx::tool-call name="rollback" status="success" :labels="$labels" :duration="640" :args="['steps' => 1]" :output="['rolled_back' => ['2026_10_01_add_archived_at_to_orders']]" />
</section>
