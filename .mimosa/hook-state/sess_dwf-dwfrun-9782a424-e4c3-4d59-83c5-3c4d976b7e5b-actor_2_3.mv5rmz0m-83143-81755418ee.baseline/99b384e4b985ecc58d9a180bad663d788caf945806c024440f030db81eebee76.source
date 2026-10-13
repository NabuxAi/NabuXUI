{{--
    <x-nx::support-agent-card name="Amara Okafor" role="Billing · Lagos" status="online"
        :metrics="[
            ['label' => 'Tickets resolved', 'value' => 342, 'max' => 400],
            ['label' => 'CSAT', 'value' => 96, 'display' => '96%', 'tone' => 'success'],
            ['label' => 'First response', 'value' => 72, 'display' => '1m 42s', 'tone' => 'info'],
        ]"
        :trend="[12, 18, 14, 22, 26, 24, 31]" trend-label="Resolved, last 7 days" trend-value="167"
        :action="['label' => 'Assign a ticket', 'icon' => 'arrow-right', 'href' => '#']" />

    Bars are value / max (100 by default) and fill in turn when the card scrolls into view.
    status: online | away | busy | offline. An <x-slot:actions> replaces the button (wire:click and all).
--}}
@props(['name', 'role' => null, 'avatar' => null, 'status' => 'online', 'statusLabel' => null, 'metrics' => [], 'trend' => null, 'trendLabel' => null, 'trendValue' => null, 'action' => null])
@php
    use NabuXUI\NabuXUI;
    $lang = substr(app()->getLocale(), 0, 2);
    $presence = [
        'fa' => ['online' => 'آنلاین', 'away' => 'دور از میز', 'busy' => 'مشغول', 'offline' => 'آفلاین'],
        'ar' => ['online' => 'متصل', 'away' => 'بعيد', 'busy' => 'مشغول', 'offline' => 'غير متصل'],
    ][$lang] ?? ['online' => 'Online', 'away' => 'Away', 'busy' => 'Busy', 'offline' => 'Offline'];
    $rising = $trend && count($trend) > 1 ? end($trend) >= reset($trend) : true;
@endphp
<article {{ $attributes->class('nx-agent-card')->merge(['data-nx-reveal' => '']) }} x-data x-nx-reveal>
    <header class="nx-agent-card-head">
        <x-nx::avatar :name="$name" :src="$avatar" size="lg" :status="$status" />
        <div class="nx-agent-card-identity">
            <h3 class="nx-agent-card-name">{{ $name }}</h3>
            @if ($role)<p class="nx-agent-card-role">{{ $role }}</p>@endif
        </div>
        <span class="nx-agent-card-status" data-status="{{ $status }}"><i aria-hidden="true"></i>{{ $statusLabel ?? ($presence[$status] ?? $status) }}</span>
    </header>
    @if ($metrics)
        <ul class="nx-agent-card-metrics">
            @foreach ($metrics as $i => $metric)
                @php $share = max(0, min(1, ($metric['value'] ?? 0) / (($metric['max'] ?? 100) ?: 100))); @endphp
                <li class="nx-agent-card-metric" @if (! empty($metric['tone']) && $metric['tone'] !== 'accent') data-tone="{{ $metric['tone'] }}" @endif style="--nx-i: {{ $i }}; --nx-value: {{ round($share, 4) }}">
                    <div class="nx-agent-card-metric-head">
                        <span>{{ $metric['label'] }}</span>
                        <strong>{{ $metric['display'] ?? NabuXUI::formatNumber($metric['value'] ?? 0) }}</strong>
                    </div>
                    <span class="nx-agent-card-bar" aria-hidden="true"><span class="nx-agent-card-fill"></span></span>
                </li>
            @endforeach
        </ul>
    @endif
    @if ($trend && count($trend) > 1)
        <div class="nx-agent-card-trend">
            <span class="nx-agent-card-trend-label">{{ $trendLabel }}@if ($trendValue !== null)<strong>{{ $trendValue }}</strong>@endif</span>
            <x-nx::sparkline :data="$trend" :trend="$rising ? 'up' : 'down'" />
            <span class="nx-visually-hidden">{{ implode(', ', array_map(fn ($v) => NabuXUI::formatNumber($v), $trend)) }}</span>
        </div>
    @endif
    @if (isset($actions))
        <footer class="nx-agent-card-action">{{ $actions }}</footer>
    @elseif ($action)
        <footer class="nx-agent-card-action">
            <x-nx::button variant="primary" block :icon="$action['icon'] ?? null" :href="$action['href'] ?? null">{{ $action['label'] }}</x-nx::button>
        </footer>
    @endif
</article>
