{{--
    items: [['label' => 'Products', 'columns' => 2, 'children' => [['label' => …, 'href' => …, 'description' => …, 'icon' => …]]],
            ['label' => 'Pricing', 'href' => '/pricing', 'current' => true]]
    Moving between triggers slides the new panel in from the side you came from.
--}}
@props(['items' => [], 'label' => null, 'navigate' => false])
@php $megaId = \NabuXUI\NabuXUI::id('nx-mega'); @endphp
<nav {{ $attributes->class('nx-mega')->merge(['aria-label' => $label]) }} x-data="nxMega()"
    @pointerleave="close(220)" @pointerenter="clearTimeout(timer)" @focusout="if (! $root.contains($event.relatedTarget)) close()">
    <ul class="nx-mega-list">
        @foreach ($items as $i => $item)
            <li>
                @if (! empty($item['children']))
                    <button type="button" class="nx-mega-trigger" data-trigger="{{ $i }}" aria-controls="{{ $megaId }}-panel-{{ $i }}" x-bind:aria-expanded="active === {{ $i }} ? 'true' : 'false'" aria-expanded="false"
                        @pointerenter="$event.pointerType === 'mouse' && open({{ $i }})" @click="toggle({{ $i }})"
                        @keydown.arrow-down="keyOpen($event, {{ $i }})" @keydown.enter="active !== {{ $i }} && keyOpen($event, {{ $i }})" @keydown.space="active !== {{ $i }} && keyOpen($event, {{ $i }})">
                        {{ $item['label'] }}{{ \NabuXUI\NabuXUI::icon('chevron-down') }}
                    </button>
                @else
                    <a class="nx-mega-link" href="{{ $item['href'] ?? '#' }}" @if (! empty($item['current'])) aria-current="page" @endif @if ($navigate) wire:navigate @endif
                        @pointerenter="close(120); ind && ind.update($el)">{{ $item['label'] }}</a>
                @endif
            </li>
        @endforeach
    </ul>
    <span class="nx-indicator" aria-hidden="true"></span>
    <div class="nx-mega-viewport" x-ref="viewport" x-bind:data-state="active === null ? 'closed' : 'open'" data-state="closed" x-bind:data-fresh="fresh ? '' : null" @keydown.escape="escape()">
        @foreach ($items as $i => $item)
            @if (! empty($item['children']))
                <div class="nx-mega-panel" id="{{ $megaId }}-panel-{{ $i }}" data-panel="{{ $i }}" x-show="active === {{ $i }} || leaving === {{ $i }}" style="display: none"
                    x-bind:data-motion="motionFor({{ $i }})" @animationend="leaving === {{ $i }} && (leaving = null)">
                    <div style="display: flex; gap: var(--nx-space-4)">
                        <ul class="nx-mega-grid" style="--nx-mega-cols: {{ $item['columns'] ?? 2 }}">
                            @foreach ($item['children'] as $child)
                                <li>
                                    <a class="nx-mega-item" href="{{ $child['href'] }}" @if ($navigate) wire:navigate @endif @click="close()">
                                        <span class="nx-mega-item-icon" aria-hidden="true">{{ \NabuXUI\NabuXUI::icon($child['icon'] ?? 'arrow-right') }}</span>
                                        <span class="nx-mega-item-title">{{ $child['label'] }}</span>
                                        @if (! empty($child['description']))<span class="nx-mega-item-description">{{ $child['description'] }}</span>@endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</nav>
