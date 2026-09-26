{{-- A band of light over muted text (sweep) or letters rising in a wave. --}}
@props(['as' => 'span', 'variant' => 'sweep', 'duration' => null])
@php $text = trim((string) $slot); @endphp
@if ($variant === 'wave')
    <{{ $as }} {{ $attributes->class('nx-shimmer')->merge(['data-variant' => 'wave', 'style' => $duration ? "--nx-shimmer-duration: {$duration}s" : null]) }}>
        <span class="nx-visually-hidden">{{ $text }}</span>
        <span aria-hidden="true" dir="{{ \NabuXUI\NabuXUI::direction($text) }}">@foreach (\NabuXUI\NabuXUI::split($text, 'char') as $i => $piece)<span class="nx-text-piece" style="--nx-i: {{ $i }}">{{ $piece }}</span>@endforeach</span>
    </{{ $as }}>
@else
    <{{ $as }} {{ $attributes->class('nx-shimmer')->merge(['style' => $duration ? "--nx-shimmer-duration: {$duration}s" : null]) }}>{{ $text }}</{{ $as }}>
@endif
