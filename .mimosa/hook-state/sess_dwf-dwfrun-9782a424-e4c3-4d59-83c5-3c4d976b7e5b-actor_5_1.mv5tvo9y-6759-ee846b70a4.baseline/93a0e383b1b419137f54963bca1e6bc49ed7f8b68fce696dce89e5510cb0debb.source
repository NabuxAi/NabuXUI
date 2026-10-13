{{--
    <x-nx::header variant="floating" hide-on-scroll :items="$nav" navigate>
        <x-slot:brand>…logo…</x-slot:brand>
        <x-slot:actions> <x-nx::theme-toggle /> </x-slot:actions>
    </x-nx::header>
--}}
@props(['items' => [], 'variant' => 'bar', 'hideOnScroll' => false, 'brandHref' => '/', 'label' => null, 'navigate' => false])
<header {{ $attributes->class('nx-header')->merge(['data-variant' => $variant === 'floating' ? 'floating' : null]) }} x-data="{ menu: false }" x-nx-header{{ $hideOnScroll ? '.hide' : '' }}>
    <div class="nx-header-inner">
        <a class="nx-header-brand" href="{{ $brandHref }}" @if ($navigate) wire:navigate @endif>{{ $brand ?? config('app.name') }}</a>
        @if ($items)
            <div class="nx-header-nav"><x-nx::mega-menu :items="$items" :label="$label" :navigate="$navigate" /></div>
        @endif
        @isset($actions)<div class="nx-header-actions">{{ $actions }}</div>@endisset
        @if ($items)
            <button type="button" class="nx-header-toggle" x-bind:aria-expanded="menu ? 'true' : 'false'" aria-expanded="false" aria-label="{{ __('nabuxui::ui.menu') }}" @click="menu = true">
                <span class="nx-burger" aria-hidden="true"><i></i><i></i></span>
            </button>
        @endif
    </div>
    @if ($items)
        <div style="display: contents" x-effect="menu ? $dispatch('nx-open', 'nx-header-menu') : $dispatch('nx-close', 'nx-header-menu')"></div>
        <x-nx::dialog name="nx-header-menu" variant="drawer" :title="__('nabuxui::ui.menu')" x-on:close="menu = false">
            <x-nx::mobile-menu :items="$items" :navigate="$navigate">{{ $mobileActions ?? '' }}</x-nx::mobile-menu>
        </x-nx::dialog>
    @endif
</header>
