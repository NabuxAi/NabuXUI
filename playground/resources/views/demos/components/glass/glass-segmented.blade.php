{{--
    The glass segmented control driving a revenue card: the lens springs (or
    is dragged) to the chosen period and the figures below re-render from the
    server with Persian digits. A thick-preset variant with icons closes it.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $revenue = [
        'day' => ['value' => 4820000, 'orders' => 61, 'delta' => ['+4%', '+٪۴']],
        'week' => ['value' => 31650000, 'orders' => 402, 'delta' => ['+12%', '+٪۱۲']],
        'month' => ['value' => 128400000, 'orders' => 1594, 'delta' => ['+9%', '+٪۹']],
        'year' => ['value' => 1490000000, 'orders' => 18620, 'delta' => ['+31%', '+٪۳۱']],
    ];
    $period = $state['period'] ?? 'week';
    $figure = $revenue[$period] ?? $revenue['week'];
    $delta = $fa ? $figure['delta'][1] : $figure['delta'][0];
@endphp
<style>
    .gsg-stage { position: relative; isolation: isolate; overflow: clip; display: grid; place-items: center; gap: 1.25rem; min-block-size: 20rem; padding: 2.5rem 1.25rem; border-radius: var(--nx-radius-2xl); background: var(--nx-bg); }
    .gsg-blobs { position: absolute; inset: 0; z-index: -2; filter: blur(26px) saturate(1.15); }
    .gsg-blobs i { position: absolute; inline-size: 42%; aspect-ratio: 1; border-radius: 50%; opacity: .8; animation: gsg-drift 17s ease-in-out infinite alternate; }
    .gsg-blobs i:nth-child(1) { inset-block-start: -14%; inset-inline-start: 10%; background: var(--nx-violet-500); }
    .gsg-blobs i:nth-child(2) { inset-block-end: -12%; inset-inline-end: -4%; background: var(--nx-cyan-400); animation-delay: -5s; }
    .gsg-blobs i:nth-child(3) { inset-block-end: 8%; inset-inline-start: -10%; inline-size: 30%; background: var(--nx-gold-400); animation-delay: -9s; }
    @keyframes gsg-drift { to { translate: calc(8% * var(--nx-motion)) calc(-8% * var(--nx-motion)); } }
    @media (prefers-reduced-motion: reduce) { .gsg-blobs i { animation: none; } }
</style>

<section class="gsg-stage">
    <div class="gsg-blobs" aria-hidden="true"><i></i><i></i><i></i></div>
    <div style="display: grid; gap: 1rem; justify-items: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl); color: var(--nx-text)">{{ $say('This quarter’s revenue, one lens', 'درآمد این فصل، یک عدسی') }}</h3>
        <x-nx::glass-segmented :label="$say('Period', 'دوره')" :value="$period" wire:model.live="state.period"
            :options="['day' => $say('Day', 'روز'), 'week' => $say('Week', 'هفته'), 'month' => $say('Month', 'ماه'), 'year' => $say('Year', 'سال')]" />
        <x-nx::glass-panel preset="clear" style="display: grid; gap: .875rem; padding: 1.375rem 1.75rem; justify-items: center; min-inline-size: 15rem">
            <span style="font: 700 var(--nx-text-3xl) / 1.1 var(--nx-font-display)">{{ NabuXUI::formatNumber($figure['value']).' '.$say('tomans', 'تومان') }}</span>
            <div class="pg-row" style="gap: .75rem">
                <x-nx::badge tone="success">{{ $delta }}</x-nx::badge>
                <span style="font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
                    {{ NabuXUI::formatNumber($figure['orders']).' '.$say('orders', 'سفارش') }}
                </span>
            </div>
        </x-nx::glass-panel>
        <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
            {{ $say('The lens springs on click — or grab it and drag it across.', 'عدسی با کلیک می‌پرد — یا بگیریدش و بکشیدش.') }}
        </p>
    </div>
</section>

<section class="gsg-stage" style="min-block-size: 14rem">
    <div class="gsg-blobs" aria-hidden="true"><i></i><i></i><i></i></div>
    <div style="display: grid; gap: .875rem; justify-items: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg); color: var(--nx-text)">{{ $say('Thick glass, icons, one disabled', 'شیشهٔ ستبر، با آیکون و یک گزینهٔ خاموش') }}</h3>
        <x-nx::glass-segmented preset="thick" :label="$say('Layout', 'چیدمان')"
            :value="$state['layout'] ?? 'grid'" wire:model.live="state.layout"
            :options="[
                'list' => ['label' => $say('List', 'فهرست'), 'icon' => 'menu'],
                'grid' => ['label' => $say('Grid', 'شبکه'), 'icon' => 'grid'],
                'chart' => ['label' => $say('Chart', 'نمودار'), 'icon' => 'chart'],
                'map' => ['label' => $say('Map', 'نقشه'), 'icon' => 'globe', 'disabled' => true],
            ]" />
        <span style="font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
            {{ $say('Bound to Livewire:', 'به Livewire بسته شده:') }} <code>{{ $state['layout'] ?? 'grid' }}</code>
        </span>
    </div>
</section>
