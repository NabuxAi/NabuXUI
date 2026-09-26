{{--
    <x-nx::button variant="primary" wire:click="save">Save</x-nx::button>

    With wire:click (or an explicit `target`), Livewire's wire:loading marks the button
    aria-busy and the label becomes a spinner at the same width. `status` sets
    loading | success | error by hand.
--}}
@props([
    'variant' => 'secondary',
    'size' => 'md',
    'shape' => null,
    'status' => null,
    'effect' => null,
    'magnetic' => false,
    'ripple' => false,
    'icon' => null,
    'iconEnd' => null,
    'iconOnly' => false,
    'block' => false,
    'href' => null,
    'type' => 'button',
    'target' => null,
])
@php
    $clickTarget = $target ?? (($click = $attributes->get('wire:click')) ? preg_replace('/\(.*$/s', '', $click) : null);
    $text = trim((string) $slot);
    $renderIcon = fn ($i) => is_string($i) && \NabuXUI\NabuXUI::hasIcon($i) ? \NabuXUI\NabuXUI::icon($i) : $i;
    $data = [
        'data-variant' => $variant,
        'data-size' => $size === 'md' ? null : $size,
        'data-shape' => $shape && $shape !== 'rounded' ? $shape : null,
        'data-status' => $status && $status !== 'idle' ? $status : null,
        'data-effect' => $effect,
        'data-icon-only' => $iconOnly ? '' : null,
        'data-block' => $block ? '' : null,
        'aria-busy' => $status === 'loading' ? 'true' : null,
    ];
@endphp
@if ($href)
<a href="{{ $href }}" {{ $attributes->class('nx-button')->merge($data) }} @if ($magnetic || $ripple) x-data @endif @if ($magnetic) x-nx-magnetic @endif @if ($ripple) x-nx-ripple @endif>
@else
<button type="{{ $type }}" {{ $attributes->class('nx-button')->merge($data) }}
    @if ($clickTarget) wire:loading.attr="aria-busy" wire:target="{{ $clickTarget }}" @endif
    @if ($magnetic || $ripple) x-data @endif @if ($magnetic) x-nx-magnetic @endif @if ($ripple) x-nx-ripple @endif>
@endif
    <span class="nx-button-label" @if ($effect === 'slide') data-text="{{ $text }}" @endif>
        @if ($icon){{ $renderIcon($icon) }}@endif
        @if ($text !== '')
            @if ($iconOnly)<span class="nx-visually-hidden">{{ $slot }}</span>@else<span class="nx-button-text">{{ $slot }}</span>@endif
        @endif
        @if ($iconEnd){{ $renderIcon($iconEnd) }}@endif
    </span>
    <x-nx::button-status />
@if ($href)
</a>
@else
</button>
@endif
