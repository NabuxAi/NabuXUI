{{-- The same items as the mega menu, as a drill-down (for the drawer on small screens). --}}
@props(['items' => [], 'navigate' => false])
<div {{ $attributes->class('nx-drilldown') }} x-data="nxDrilldown()">
    <div class="nx-drilldown-track" x-bind:style="`--nx-level: ${sub === null ? 0 : 1}`">
        <div class="nx-drilldown-panel" x-ref="root">
            @foreach ($items as $i => $item)
                @if (! empty($item['children']))
                    <button type="button" class="nx-drilldown-item" style="--nx-i: {{ $i }}" x-bind:aria-expanded="sub === '{{ $i }}' ? 'true' : 'false'" @click="enter('{{ $i }}', $event)">
                        <span>{{ $item['label'] }}</span>{{ \NabuXUI\NabuXUI::icon('chevron-right') }}
                    </button>
                @else
                    <a class="nx-drilldown-item" href="{{ $item['href'] ?? '#' }}" style="--nx-i: {{ $i }}" @if (! empty($item['current'])) aria-current="page" @endif @if ($navigate) wire:navigate @endif><span>{{ $item['label'] }}</span></a>
                @endif
            @endforeach
            {{ $slot }}
        </div>
        <div class="nx-drilldown-panel" x-ref="sub" inert>
            @foreach ($items as $i => $item)
                @if (! empty($item['children']))
                    <div data-sub="{{ $i }}" x-show="sub === '{{ $i }}'" style="display: none">
                        <button type="button" class="nx-drilldown-back" @click="back()">{{ \NabuXUI\NabuXUI::icon('chevron-left') }}{{ __('nabuxui::ui.back') }}</button>
                        @foreach ($item['children'] as $c => $child)
                            <a class="nx-drilldown-item" href="{{ $child['href'] }}" style="--nx-i: {{ $c }}" @if ($navigate) wire:navigate @endif><span>{{ $child['label'] }}</span>@if (! empty($child['icon'])){{ \NabuXUI\NabuXUI::icon($child['icon']) }}@endif</a>
                        @endforeach
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
