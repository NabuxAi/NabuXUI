{{--
    <x-nx::glitch-text text="سیستم آنلاین شد" trigger="loop" />

    Chromatic glitch: the real text once, two aria-hidden copies behind it that
    slice and shift in bursts. The text is never split into letters, so
    Persian keeps its joins. trigger: hover (default — also when a link or
    button around it is hovered or focused) | loop | always. Pure CSS.
--}}
@props(['text' => '', 'trigger' => 'hover', 'as' => 'span'])
@php
    $tag = in_array($as, ['span', 'strong', 'em', 'h1', 'h2', 'h3', 'p'], true) ? $as : 'span';
    $text = (string) $text;
@endphp
<{{ $tag }} {{ $attributes->class('nx-glitch')->merge(['data-trigger' => $trigger, 'dir' => \NabuXUI\NabuXUI::direction($text)]) }}><span class="nx-glitch-text">{{ $text }}</span><span class="nx-glitch-layer" data-layer="a" aria-hidden="true">{{ $text }}</span><span class="nx-glitch-layer" data-layer="b" aria-hidden="true">{{ $text }}</span></{{ $tag }}>
