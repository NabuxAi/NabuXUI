{{-- Slots: front, back. trigger="hover" turns on hover/focus; "click" on click or the corner button. --}}
@props(['trigger' => 'hover', 'flipLabel' => 'Flip card'])
@if ($trigger === 'click')
<div {{ $attributes->class('nx-flip')->merge(['data-trigger' => 'click']) }} x-data="{ flipped: false }" x-bind:data-flipped="flipped ? '' : null"
    @click="if (! $event.target.closest('a, button, input, select, textarea')) flipped = ! flipped">
    <div class="nx-flip-inner">
        <div class="nx-flip-face nx-flip-front" x-bind:inert="flipped">{{ $front }}</div>
        <div class="nx-flip-face nx-flip-back" x-bind:inert="! flipped" inert>{{ $back }}</div>
    </div>
    <button type="button" class="nx-button" data-variant="secondary" data-size="sm" data-shape="pill" data-icon-only
        style="position: absolute; inset-block-start: var(--nx-space-3); inset-inline-end: var(--nx-space-3); z-index: 2"
        aria-label="{{ $flipLabel }}" x-bind:aria-pressed="flipped ? 'true' : 'false'" @click.stop="flipped = ! flipped">
        <span class="nx-button-label">{{ \NabuXUI\NabuXUI::icon('sliders') }}</span>
    </button>
</div>
@else
<div {{ $attributes->class('nx-flip')->merge(['data-trigger' => 'hover']) }}>
    <div class="nx-flip-inner">
        <div class="nx-flip-face nx-flip-front">{{ $front }}</div>
        <div class="nx-flip-face nx-flip-back">{{ $back }}</div>
    </div>
</div>
@endif
