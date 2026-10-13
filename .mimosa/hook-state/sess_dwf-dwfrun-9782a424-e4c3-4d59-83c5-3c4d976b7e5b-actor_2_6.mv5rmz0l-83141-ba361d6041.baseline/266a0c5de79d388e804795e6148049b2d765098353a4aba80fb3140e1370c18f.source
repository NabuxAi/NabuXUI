{{-- <x-nx::accordion single> <x-nx::accordion-item title="…" open>…</x-nx::accordion-item> … </x-nx::accordion> --}}
@props(['single' => true, 'variant' => 'joined'])
@php $group = $single ? \NabuXUI\NabuXUI::id('nx-acc') : null; @endphp
<div {{ $attributes->class('nx-accordion')->merge(['data-variant' => $variant === 'separated' ? 'separated' : null]) }} @if ($group) data-accordion-group="{{ $group }}" x-data x-init="$el.querySelectorAll(':scope > details').forEach(d => d.name = @js($group))" @endif>{{ $slot }}</div>
