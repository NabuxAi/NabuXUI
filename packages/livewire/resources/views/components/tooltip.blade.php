{{-- Wrap one focusable element: <x-nx::tooltip text="Copy the URL"><x-nx::button …/></x-nx::tooltip> --}}
@props(['text', 'side' => 'top', 'delay' => 350])
@php $id = \NabuXUI\NabuXUI::id('nx-tip'); @endphp
<span style="display: contents" x-data="nxTooltip(@js($side), {{ (int) $delay }})">
    {{ $slot }}
    <div x-ref="tip" id="{{ $id }}" role="tooltip" popover="manual" {{ $attributes->class('nx-tooltip') }}>{{ $text }}</div>
</span>
