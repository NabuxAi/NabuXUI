{{--
    <x-nx::border-button wire:click="save" success-label="Saved" :status="$saved ? 'success' : null">Save draft</x-nx::border-button>

    An outline whose dashes march (faster under the pointer). With wire:click (or
    `target`), Livewire's wire:loading marks it aria-busy and the dashes spin;
    status="success" closes them into a solid ring and draws a check, "error"
    shakes and turns it red. The label morphs to success-label / error-label.
--}}
@props([
    'status' => null,
    'successLabel' => 'Done',
    'errorLabel' => null,
    'icon' => null,
    'size' => 'md',
    'type' => 'button',
    'target' => null,
    'static' => false,
])
@php
    $clickTarget = $target ?? (($click = $attributes->get('wire:click')) ? preg_replace('/\(.*$/s', '', $click) : null);
    $status = in_array($status, ['loading', 'success', 'error'], true) ? $status : null;
    $errorLabel ??= __('nabuxui::ui.failed');
    $renderIcon = fn ($i) => is_string($i) && \NabuXUI\NabuXUI::hasIcon($i) ? \NabuXUI\NabuXUI::icon($i) : $i;
    $data = [
        'data-status' => $status,
        'data-size' => $size === 'md' ? null : $size,
        'data-static' => $static ? '' : null,
        'aria-busy' => $status === 'loading' ? 'true' : null,
    ];
@endphp
<button type="{{ $type }}" {{ $attributes->class('nx-border-button')->merge($data) }}
    @if ($clickTarget) wire:loading.attr="aria-busy" wire:target="{{ $clickTarget }}" @endif
    x-data="nxBorderButton">
    <svg class="nx-border-button-frame" aria-hidden="true" focusable="false">
        <rect class="nx-border-button-track" x="0.75" y="0.75" width="100%" height="100%" rx="12"/>
        <rect class="nx-border-button-march" x="0.75" y="0.75" width="100%" height="100%" rx="12"/>
        <rect class="nx-border-button-trace" x="0.75" y="0.75" width="100%" height="100%" rx="12" pathLength="100"/>
    </svg>
    <span class="nx-border-button-label" data-slot="idle" aria-hidden="true">
        @if ($icon){{ $renderIcon($icon) }}@endif
        <span>{{ $slot }}</span>
    </span>
    <span class="nx-border-button-label" data-slot="success" aria-hidden="true">
        <svg class="nx-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"><path class="nx-border-button-mark" d="M5 12.5l4.5 4.5L19 7.5" pathLength="1"/></svg>
        <span>{{ $successLabel }}</span>
    </span>
    <span class="nx-border-button-label" data-slot="error" aria-hidden="true">
        <svg class="nx-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"><path class="nx-border-button-mark" d="M7 7l10 10M17 7L7 17" pathLength="1"/></svg>
        <span>{{ $errorLabel }}</span>
    </span>
    <span class="nx-visually-hidden" aria-live="polite">@if ($status === 'success'){{ $successLabel }}@elseif ($status === 'error'){{ $errorLabel }}@else{{ $slot }}@endif</span>
</button>
