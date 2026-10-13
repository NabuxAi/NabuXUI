{{-- Word-by-word (or letter-by-letter) reveal. trigger="scroll" brightens each word as it scrolls through. --}}
@props(['as' => 'span', 'by' => 'word', 'trigger' => 'view', 'delay' => null])
@php $text = trim((string) $slot); @endphp
<{{ $as }} {{ $attributes->class('nx-text-reveal')->merge(['data-by' => $by, 'data-trigger' => $trigger, 'data-nx-reveal' => $trigger === 'view' ? '' : null, 'style' => $delay ? "--nx-delay: {$delay}ms" : null]) }}
    @if ($trigger === 'view') x-data x-nx-reveal @endif>
    <span class="nx-visually-hidden">{{ $text }}</span>
    <span class="nx-text-pieces" aria-hidden="true" dir="{{ \NabuXUI\NabuXUI::direction($text) }}">@foreach (\NabuXUI\NabuXUI::split($text, $by) as $i => $piece)<span class="nx-text-piece" style="--nx-i: {{ $i }}">{{ $piece }}</span>@endforeach</span>
</{{ $as }}>
