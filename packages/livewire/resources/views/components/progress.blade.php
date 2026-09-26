{{-- Omit `value` for an indeterminate bar. --}}
@props(['value' => null, 'max' => 100, 'tone' => null, 'label' => null])
@php $pct = $value === null ? null : max(0, min(100, $value / ($max ?: 100) * 100)); @endphp
<progress {{ $attributes->class('nx-progress')->merge([
    'value' => $value,
    'max' => $max,
    'aria-label' => $label,
    'data-tone' => $tone && $tone !== 'accent' ? $tone : null,
    'style' => $pct === null ? null : "--nx-value: {$pct}",
]) }}></progress>
