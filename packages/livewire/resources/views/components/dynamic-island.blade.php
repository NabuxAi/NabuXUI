{{--
    <x-nx::dynamic-island view="idle" label="Live activity">
        <x-nx::island-view name="idle">
            <button class="nx-island-toggle" x-on:click="show('timer')">…</button>
        </x-nx::island-view>
        <x-nx::island-view name="timer" size="expanded">…</x-nx::island-view>
    </x-nx::dynamic-island>

    A dark pill that morphs between views, springing its width, height and
    radius while the content cross-fades. Alpine holds `view`; anything inside
    calls show('name'). Bind it to Livewire with x-model / @entangle on `view`
    from outside, or dispatch `nx-island-show` with the view name.
--}}
@props(['view' => 'idle', 'label' => 'Live activity'])
<div {{ $attributes->class('nx-island-wrap')->merge(['role' => 'region', 'aria-label' => $label]) }}
    x-data="nxDynamicIsland(@js($view))" x-modelable="view" x-on:nx-island-show.window="show($event.detail)">
    <div class="nx-island" x-ref="island" x-bind:data-active="view" data-active="{{ $view }}" wire:ignore.self>{{ $slot }}</div>
</div>
