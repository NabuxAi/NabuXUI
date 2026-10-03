{{--
    <x-nx::typewriter :phrases="['وب‌سایت می‌سازیم', 'اپ موبایل می‌سازیم']" />

    Types each phrase a grapheme at a time (by="word" for whole words), deletes
    back to what the next phrase shares and types on; the caret blinks while a
    phrase holds. Screen readers get the phrases once as plain text; under
    reduced motion phrases swap whole. Dispatches `nx-typewriter-phrase`
    { index } when a phrase is complete.
--}}
@props([
    'phrases' => [],
    'by' => 'grapheme',
    'typeSpeed' => 55,
    'deleteSpeed' => 28,
    'hold' => 1800,
    'loop' => true,
    'startDelay' => 400,
    'caret' => 'bar',
])
@php
    $phrases = array_values(array_filter(array_map('strval', (array) $phrases), 'strlen'));
    $first = $phrases[0] ?? '';
    $options = [
        'phrases' => $phrases,
        'by' => $by === 'word' ? 'word' : 'grapheme',
        'typeSpeed' => (int) $typeSpeed,
        'deleteSpeed' => (int) $deleteSpeed,
        'hold' => (int) $hold,
        'loop' => (bool) $loop,
        'startDelay' => (int) $startDelay,
    ];
@endphp
<span {{ $attributes->class('nx-typewriter')->merge(['data-caret' => $caret === 'bar' ? null : $caret]) }} x-data="nxTypewriter(@js($options))" wire:ignore>
    <span class="nx-visually-hidden">{{ $loop ? implode(' · ', $phrases) : (end($phrases) ?: '') }}</span>
    <span class="nx-typewriter-line" aria-hidden="true" dir="{{ \NabuXUI\NabuXUI::direction($first) }}"><span class="nx-typewriter-text">{{ $first }}</span><span class="nx-typewriter-caret"></span></span>
</span>
