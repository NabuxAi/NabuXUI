{{--
    <x-nx::thinking-orbs state="thinking" size="sm" label="در حال فکر" />

    Pure CSS: idle (three dots breathing) · thinking (dots orbiting) · speaking (a little
    equaliser) · listening (rings opening out). Under reduced motion they only fade.
    Bind the state from Alpine with x-bind:data-state, or re-render it from Livewire.
--}}
@props(['state' => 'thinking', 'size' => 'md', 'label' => null])
@php
    $state = in_array($state, ['idle', 'thinking', 'speaking', 'listening'], true) ? $state : 'thinking';
    $words = ['idle' => 'Idle', 'thinking' => 'Thinking', 'speaking' => 'Speaking', 'listening' => 'Listening'];
@endphp
<span {{ $attributes->class('nx-orbs')->merge(['role' => 'status', 'data-state' => $state, 'data-size' => $size !== 'md' ? $size : null]) }}>
    <i aria-hidden="true"></i><i aria-hidden="true"></i><i aria-hidden="true"></i>
    <span class="nx-visually-hidden">{{ $label ?? $words[$state] }}</span>
</span>
