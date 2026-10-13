{{--
    <x-nx::swipe-stack :count="count($products)"> <x-nx::swipe-card :index="$i">…</x-nx::swipe-card> … </x-nx::swipe-stack>
    Fires nx-swipe with { index, verdict: accept | reject } — listen with x-on:nx-swipe or wire it to a method.
--}}
@props(['count', 'loop' => true, 'actions' => true, 'acceptLabel' => null, 'rejectLabel' => null])
<div {{ $attributes }} x-data="nxSwipeStack({{ (int) $count }}, @js((bool) $loop))">
    <div class="nx-swipe-stack">{{ $slot }}</div>
    @if ($actions)
        <div class="nx-swipe-actions" x-show="order.length">
            <x-nx::button variant="secondary" shape="pill" size="lg" icon="x" icon-only :aria-label="$rejectLabel ?? __('nabuxui::ui.reject')" x-on:click="decide('reject')">{{ $rejectLabel ?? __('nabuxui::ui.reject') }}</x-nx::button>
            <x-nx::button variant="primary" shape="pill" size="lg" icon="heart" icon-only :aria-label="$acceptLabel ?? __('nabuxui::ui.accept')" x-on:click="decide('accept')">{{ $acceptLabel ?? __('nabuxui::ui.accept') }}</x-nx::button>
        </div>
    @endif
</div>
