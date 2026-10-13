{{--
    <x-nx::transaction-button wire:click="pay" :status="$paid ? 'success' : null"
        :labels="['idle' => 'Pay $49', 'loading' => 'Processing…', 'success' => 'Paid', 'error' => 'Declined']" />

    While wire:loading marks it aria-busy (or status="loading"), the label becomes
    the loading one, the width springs to fit and pixels ripple out from the
    centre; success turns the lock into a drawn check. Alpine reads the state
    from the element, so a server re-render, wire:loading or set('success')
    (e.g. x-on:click="set('loading')") all animate the same way.
--}}
@props([
    'status' => null,
    'labels' => [],
    'size' => 'md',
    'type' => 'button',
    'target' => null,
    'static' => false,
])
@php
    $clickTarget = $target ?? (($click = $attributes->get('wire:click')) ? preg_replace('/\(.*$/s', '', $click) : null);
    $status = in_array($status, ['loading', 'success', 'error'], true) ? $status : 'idle';
    $labels = array_merge(
        [
            'idle' => trim(strip_tags((string) $slot)) ?: 'Pay',
            'loading' => __('nabuxui::ui.loading').'…',
            'success' => 'Done',
            'error' => __('nabuxui::ui.failed'),
        ],
        array_filter((array) $labels, fn ($text) => $text !== null && $text !== ''),
    );
    $text = $labels[$status];
    $data = [
        'data-status' => $status === 'idle' ? null : $status,
        'data-size' => $size === 'md' ? null : $size,
        'data-static' => $static ? '' : null,
        'aria-busy' => $status === 'loading' ? 'true' : null,
        'aria-disabled' => $status === 'loading' ? 'true' : null,
    ];
@endphp
<button type="{{ $type }}" {{ $attributes->class('nx-tx-button')->merge($data) }}
    @if ($clickTarget) wire:loading.attr="aria-busy" wire:target="{{ $clickTarget }}" @endif
    x-data="nxTransactionButton(@js($labels))" x-on:click.capture="press($event)">
    <span class="nx-tx-button-waves" aria-hidden="true"></span>
    <span class="nx-tx-button-icon" aria-hidden="true">
        <svg class="nx-tx-lock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path class="nx-tx-shackle" d="M8 11V8a4 4 0 0 1 8 0v3"/><rect x="5" y="11" width="14" height="10" rx="2.5"/><path d="M12 15.25v2"/></svg>
        <svg class="nx-tx-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path class="nx-tx-mark" d="M8.5 12.5l2.5 2.5 4.5-5" pathLength="1"/></svg>
        <svg class="nx-tx-cross" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path class="nx-tx-mark" d="M9.5 9.5l5 5M14.5 9.5l-5 5" pathLength="1"/></svg>
    </span>
    <span class="nx-tx-button-label" x-ref="label" wire:ignore><span class="nx-tx-button-text" dir="{{ \NabuXUI\NabuXUI::direction($text) }}" aria-hidden="true">@foreach (\NabuXUI\NabuXUI::split($text) as $i => $piece)<span style="--nx-i: {{ $i }}">{{ $piece }}</span>@endforeach</span></span>
    <span class="nx-visually-hidden" aria-live="polite" x-ref="live" wire:ignore>{{ $text }}</span>
</button>
