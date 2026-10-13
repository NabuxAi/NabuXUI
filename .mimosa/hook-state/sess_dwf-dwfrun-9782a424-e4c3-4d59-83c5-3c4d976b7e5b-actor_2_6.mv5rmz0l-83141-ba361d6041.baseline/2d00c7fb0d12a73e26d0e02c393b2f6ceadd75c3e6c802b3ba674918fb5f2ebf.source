{{--
    <x-nx::flip-button label="Get started" hover-label="Let's go" icon="arrow-right" hover-icon="sparkles" />
    <x-nx::flip-button variant="secondary" href="/demo">Book a demo</x-nx::flip-button>

    Each part is a cube: hover (fine pointers) or keyboard focus rolls it a quarter
    turn to show the second label and icon. variant: primary | secondary | inverse.
--}}
@props([
    'label' => null,
    'hoverLabel' => null,
    'icon' => 'arrow-right',
    'hoverIcon' => 'sparkles',
    'href' => null,
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'static' => false,
])
@php
    $label ??= $slot;
    $renderIcon = fn ($i) => is_string($i) && \NabuXUI\NabuXUI::hasIcon($i) ? \NabuXUI\NabuXUI::icon($i) : $i;
    $data = [
        'data-variant' => $variant,
        'data-size' => $size === 'md' ? null : $size,
        'data-static' => $static ? '' : null,
    ];
@endphp
@if ($href)
<a href="{{ $href }}" {{ $attributes->class('nx-flip-button')->merge($data) }}>
@else
<button type="{{ $type }}" {{ $attributes->class('nx-flip-button')->merge($data) }}>
@endif
    <span class="nx-flip-button-part" data-part="label">
        <span class="nx-flip-button-cube">
            <span class="nx-flip-button-face">{{ $label }}</span>
            <span class="nx-flip-button-face" data-face="back" aria-hidden="true">{{ $hoverLabel ?? $label }}</span>
        </span>
    </span>
    <span class="nx-flip-button-part" data-part="icon" aria-hidden="true">
        <span class="nx-flip-button-cube">
            <span class="nx-flip-button-face">{{ $renderIcon($icon) }}</span>
            <span class="nx-flip-button-face" data-face="back">{{ $renderIcon($hoverIcon) }}</span>
        </span>
    </span>
@if ($href)
</a>
@else
</button>
@endif
