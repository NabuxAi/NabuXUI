@props(['value' => 0, 'label' => null, 'tone' => null, 'size' => null, 'thickness' => null])
@php $value = max(0, min(100, (float) $value)); @endphp
<div {{ $attributes->class('nx-ring')->merge(['data-nx-reveal' => '', 'style' => trim(($size ? "--nx-ring-size: {$size};" : '').($thickness ? " --nx-ring-thickness: {$thickness};" : ''))]) }} x-data x-nx-reveal>
    <progress class="nx-ring-progress" value="{{ $value }}" max="100" @if ($label) aria-label="{{ $label }}" @endif @if ($tone && $tone !== 'accent') data-tone="{{ $tone }}" @endif style="--nx-value: {{ $value }}"></progress>
    <span class="nx-ring-label" aria-hidden="true">@if (trim((string) $slot) !== ''){{ $slot }}@else{{ \NabuXUI\NabuXUI::formatNumber(round($value)) }}%@endif</span>
</div>
