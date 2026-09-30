{{--
    <x-nx::admin-shell brand="Nabu" active="dashboard" title="داشبورد" :groups="$nav" :user="$user" height="100dvh">
        <x-slot:actions><x-nx::theme-toggle /> <x-nx::language-menu :languages="$langs" /> <x-nx::activity-dropdown :items="$activity" /></x-slot:actions>
        … صفحهٔ پنل …
    </x-nx::admin-shell>

    The frame a whole admin page lives in: collapsible sidebar (spring width +
    spring highlight), topbar (search seat, actions slot, avatar menu) and the
    content area. Under 48rem the sidebar becomes a drawer the topbar's menu
    button opens (a native popover). Slots: sidebar, topbar, search, actions,
    title — each replaces the matching built-in built from the props above.
    wire:model on the root binds the current item's id (x-modelable).
--}}
@props([
    'groups' => [],
    'active' => null,
    'collapsed' => false,
    'brand' => null,
    'brandMark' => null,
    'navLabel' => null,
    'title' => null,
    'subtitle' => null,
    'searchPlaceholder' => null,
    'searchHint' => null,
    'user' => null,
    'height' => null,
    'minHeight' => null,
    'radius' => null,
    'border' => null,
])
@php
    use NabuXUI\NabuXUI;
    $drawerId = NabuXUI::id('nx-admin-drawer');
    $active = (string) ($active ?? ($groups[0]['items'][0]['id'] ?? ''));
    $style = implode('; ', array_filter([
        $height ? "--nx-admin-height: {$height}" : null,
        $minHeight ? "--nx-admin-min-height: {$minHeight}" : null,
        $radius ? "--nx-admin-radius: {$radius}" : null,
        $border ? "--nx-admin-border: {$border}" : null,
    ]));
@endphp
<div {{ $attributes->class('nx-admin') }} x-data="nxAdminShell(@js($active), @js((bool) $collapsed))" x-modelable="active"
    @if ($style) style="{{ $style }}" @endif>
    <div class="nx-admin-frame" @if ($collapsed) data-collapsed @endif x-bind:data-collapsed="collapsed ? '' : null">
        @isset($sidebar)
            {{ $sidebar }}
        @else
            <x-nx::admin-sidebar :wired="true" :groups="$groups" :active="$active" :collapsed="$collapsed" :brand="$brand" :brandMark="$brandMark" :label="$navLabel" />
        @endisset
        <div class="nx-admin-main">
            <header class="nx-admin-topbar">
                @isset($topbar)
                    {{ $topbar }}
                @else
                    <x-nx::admin-topbar :drawer="$drawerId" :title="$title" :subtitle="$subtitle"
                        :search-placeholder="$searchPlaceholder" :search-hint="$searchHint" :user="$user">
                        @isset($actions)
                            <x-slot:actions>{{ $actions }}</x-slot:actions>
                        @endisset
                        @isset($search)
                            <x-slot:search>{{ $search }}</x-slot:search>
                        @endisset
                        @isset($heading)
                            <x-slot:heading>{{ $heading }}</x-slot:heading>
                        @endisset
                        @isset($userMenu)
                            <x-slot:userMenu>{{ $userMenu }}</x-slot:userMenu>
                        @endisset
                    </x-nx::admin-topbar>
                @endisset
            </header>
            <div class="nx-admin-content">{{ $slot }}</div>
        </div>
    </div>
    <div class="nx-admin-drawer" id="{{ $drawerId }}" popover="auto" wire:ignore.self aria-label="{{ $navLabel ?? $brand ?? __('nabuxui::ui.menu') }}">
        <button type="button" class="nx-admin-drawer-close" x-on:click="closeDrawer()">
            {{ NabuXUI::icon('x') }}
            <span class="nx-visually-hidden">{{ __('nabuxui::ui.close') }}</span>
        </button>
        @isset($sidebar)
            {{ $sidebar }}
        @else
            <x-nx::admin-sidebar :wired="true" :groups="$groups" :active="$active" :brand="$brand" :brandMark="$brandMark" :label="$navLabel" />
        @endif
    </div>
</div>
