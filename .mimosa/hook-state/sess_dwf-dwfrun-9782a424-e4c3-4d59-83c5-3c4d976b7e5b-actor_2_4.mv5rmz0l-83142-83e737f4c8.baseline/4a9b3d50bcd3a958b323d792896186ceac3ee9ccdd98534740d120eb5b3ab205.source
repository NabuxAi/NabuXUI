{{--
    <x-nx::status-badge status="running" />            running | success | failed | queued | canceled
    <x-nx::status-badge :status="$job->status" live />  live: changes are announced (role="status")

    The badge follows its data-status attribute, so a Livewire re-render, or your own
    x-bind:data-status="…", morphs it: the pill's width follows the label, the icon swaps.
    labels: ['running' => 'Deploying…'] overrides the built-in words.
--}}
@props(['status' => 'queued', 'label' => null, 'labels' => [], 'size' => null, 'live' => false])
@php
    $lang = substr(app()->getLocale(), 0, 2);
    $words = [
        'fa' => ['running' => 'در حال اجرا', 'success' => 'موفق', 'failed' => 'ناموفق', 'queued' => 'در صف', 'canceled' => 'لغو شد'],
        'ar' => ['running' => 'قيد التشغيل', 'success' => 'نجح', 'failed' => 'فشل', 'queued' => 'في الانتظار', 'canceled' => 'أُلغي'],
    ][$lang] ?? ['running' => 'Running', 'success' => 'Succeeded', 'failed' => 'Failed', 'queued' => 'Queued', 'canceled' => 'Canceled'];
    $status = in_array($status, ['running', 'success', 'failed', 'queued', 'canceled'], true) ? $status : 'queued';
    $text = fn (string $s) => ($s === $status && $label) ? $label : ($labels[$s] ?? $words[$s]);
@endphp
<span {{ $attributes->class('nx-status-badge')->merge(['data-status' => $status, 'data-size' => $size && $size !== 'md' ? $size : null, 'role' => $live ? 'status' : null]) }} x-data="nxStatusBadge()">
    <span class="nx-visually-hidden">{{ $text($status) }}</span>
    <span class="nx-status-badge-layers" aria-hidden="true">
        @foreach (['running', 'success', 'failed', 'queued', 'canceled'] as $s)
            <span class="nx-status-badge-layer" data-for="{{ $s }}">
                <span class="nx-status-badge-icon">
                    @if ($s === 'running')
                        <span class="nx-status-badge-ring"></span><span class="nx-status-badge-dot"></span>
                    @elseif ($s === 'queued')
                        <span class="nx-status-badge-dots"><i></i><i></i><i></i></span>
                    @elseif ($s === 'canceled')
                        <svg viewBox="0 0 16 16"><circle cx="8" cy="8" r="6.25"/><path class="nx-status-badge-mark" d="M3.8 12.2L12.2 3.8" pathLength="1"/></svg>
                    @else
                        <svg viewBox="0 0 16 16"><circle class="nx-status-badge-disc" cx="8" cy="8" r="7.5"/><path class="nx-status-badge-mark" d="{{ $s === 'success' ? 'M4.9 8.3l2.1 2.1 4.1-4.4' : 'M5.7 5.7l4.6 4.6M10.3 5.7l-4.6 4.6' }}" pathLength="1"/></svg>
                    @endif
                </span>
                <span class="nx-status-badge-label">{{ $text($s) }}</span>
            </span>
        @endforeach
    </span>
</span>
