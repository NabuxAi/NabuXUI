{{--
    <x-nx::auto-height>
        @if ($expanded) …long… @else …short… @endif
    </x-nx::auto-height>

    A frame whose height springs to its content whenever the content changes —
    a Livewire re-render, a tab switch, an expanding card. `collapsed` folds
    it to zero (animated natively where interpolate-size exists).
--}}
@props(['spring' => 'gentle', 'collapsed' => false])
<div {{ $attributes->class('nx-auto-height')->merge([
    'data-collapsed' => $collapsed ? '' : null,
    'aria-hidden' => $collapsed ? 'true' : null,
    'inert' => $collapsed ? true : null,
]) }} x-data="nxAutoHeight(@js($spring))" wire:ignore.self><div>{{ $slot }}</div></div>
