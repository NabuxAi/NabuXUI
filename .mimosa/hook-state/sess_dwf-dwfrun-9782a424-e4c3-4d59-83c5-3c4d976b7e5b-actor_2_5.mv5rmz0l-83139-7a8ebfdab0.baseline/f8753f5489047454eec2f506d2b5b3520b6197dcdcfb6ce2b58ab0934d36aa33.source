{{--
    <x-nx::slide-to-confirm label="Slide to pay $49" done-label="Paid" action="pay" />

    Drag the thumb to the end to confirm (arrow keys step, End confirms, Home
    returns). A short drag springs back. On confirm it dispatches `nx-confirm`
    and, with `action`, calls that Livewire method; reset() rewinds it.
--}}
@props([
    'label' => '',
    'doneLabel' => 'Done',
    'thumbLabel' => null,
    'hint' => 'Drag to the end, or press End, to confirm',
    'threshold' => 0.9,
    'resetAfter' => 0,
    'tone' => 'accent',
    'icon' => 'arrow-right',
    'doneIcon' => 'check',
    'action' => null,
    'disabled' => false,
])
@php
    use NabuXUI\NabuXUI;
    $id = NabuXUI::id('nx-slide');
    $data = [
        'data-tone' => $tone === 'accent' ? null : $tone,
        'data-state' => 'idle',
        'aria-disabled' => $disabled ? 'true' : null,
    ];
@endphp
<div {{ $attributes->class('nx-slide-confirm')->merge($data) }}
    x-data="nxSlideConfirm(@js(['threshold' => (float) $threshold, 'resetAfter' => (int) $resetAfter, 'action' => $action]))" wire:ignore>
    <span class="nx-slide-confirm-fill" aria-hidden="true"></span>
    <span class="nx-slide-confirm-label" aria-hidden="true">
        <span>{{ $label }}</span>
        <span data-off>{{ $doneLabel }}</span>
    </span>
    <span class="nx-slide-confirm-thumb" role="slider" tabindex="{{ $disabled ? '-1' : '0' }}" aria-label="{{ $thumbLabel ?? $label }}"
        aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" aria-describedby="{{ $id }}-hint" @if ($disabled) aria-disabled="true" @endif>
        <span class="nx-slide-confirm-icon" aria-hidden="true">
            <span>{{ NabuXUI::icon($icon) }}</span>
            <span data-off>{{ NabuXUI::icon($doneIcon) }}</span>
        </span>
    </span>
    <span class="nx-visually-hidden" id="{{ $id }}-hint">{{ $hint }}</span>
    <span class="nx-visually-hidden" aria-live="polite" x-text="state === 'done' ? @js($doneLabel) : ''"></span>
</div>
