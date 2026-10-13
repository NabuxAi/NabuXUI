{{--
    <x-nx::admin-topbar title="داشبورد" subtitle="خلاصهٔ امروز" :drawer="$drawerId" search-placeholder="جست‌وجو…"
        :user="['name' => 'حسین مرادی', 'role' => 'مدیر سیستم', 'avatar' => '…', 'menu' => [
            ['label' => 'پروفایل', 'icon' => 'user', 'href' => '/admin/profile'],
            ['divider' => true],
            ['label' => 'خروج', 'icon' => 'lock'],
        ]]">
        <x-slot:actions>
            <x-nx::theme-toggle /> <x-nx::language-menu :languages="$langs" /> <x-nx::activity-dropdown :items="$activity" />
        </x-slot:actions>
    </x-nx::admin-topbar>

    The topbar row: mobile menu button (needs the shell's drawer id), heading,
    search/command seat, actions slot and an avatar menu on a native popover.
    Slots: heading (title/subtitle override), search (search seat override), actions, userMenu
    (the slot owns the menu semantics: keep the head outside a role="menu" wrapper of its own).
--}}
@props(['title' => null, 'subtitle' => null, 'drawer' => null, 'searchPlaceholder' => null, 'searchHint' => null, 'user' => null])
@php
    use NabuXUI\NabuXUI;
    $menuId = NabuXUI::id('nx-admin-user');
    $searchLabel = $searchPlaceholder ?? __('nabuxui::ui.searchPlaceholder');
    $hasUserMenu = is_array($user) && ! empty($user['menu']);
@endphp
<div class="nx-admin-topbar-start" x-data="nxAdminTopbar()">
    @if ($drawer)
        <button type="button" class="nx-admin-menu" popovertarget="{{ $drawer }}" aria-controls="{{ $drawer }}" aria-expanded="false">
            {{ NabuXUI::icon('menu') }}
            <span class="nx-visually-hidden">{{ __('nabuxui::ui.menu') }}</span>
        </button>
    @endif
    <div class="nx-admin-heading">
        @isset($heading){{ $heading }}@else
            @if ($title)<p class="nx-admin-title">{{ $title }}</p>@endif
            @if ($subtitle)<p class="nx-admin-subtitle">{{ $subtitle }}</p>@endif
        @endisset
    </div>
    <div class="nx-admin-search">
        @isset($search)
            {{ $search }}
        @else
            <button type="button" class="nx-admin-search-btn" x-on:click="$dispatch('nx-search')">
                {{ NabuXUI::icon('search') }}
                <span>{{ $searchLabel }}</span>
                @if ($searchHint)<kbd class="nx-kbd">{{ $searchHint }}</kbd>@endif
                <span class="nx-visually-hidden">{{ __('nabuxui::ui.search') }}</span>
            </button>
        @endisset
    </div>
    <div class="nx-admin-actions">
        {{ $actions ?? '' }}
        @if (is_array($user))
            <button type="button" class="nx-admin-user" x-ref="user" popovertarget="{{ $menuId }}" aria-controls="{{ $menuId }}" aria-expanded="false" aria-haspopup="menu">
                <x-nx::avatar :name="$user['name']" :src="$user['avatar'] ?? null" />
                <span class="nx-admin-user-text">
                    <span class="nx-admin-user-name">{{ $user['name'] }}</span>
                    @if (! empty($user['role']))<span class="nx-admin-user-role">{{ $user['role'] }}</span>@endif
                </span>
                {{ NabuXUI::icon('chevron-down') }}
            </button>
        @endif
    </div>
    @if (is_array($user) && ($hasUserMenu || isset($userMenu)))
        {{-- The panel itself carries no role: the head is panel chrome and a menu may
             only hold menuitems and separators, so the entries get their own wrapper. --}}
        <div class="nx-admin-user-menu" id="{{ $menuId }}" x-ref="userMenu" popover="auto" wire:ignore.self>
            @isset($userMenu)
                {{ $userMenu }}
            @else
                @if ($hasUserMenu)
                    <div class="nx-admin-user-head">
                        <p class="nx-admin-user-name">{{ $user['name'] }}</p>
                        @if (! empty($user['role']))<p class="nx-admin-user-role">{{ $user['role'] }}</p>@endif
                    </div>
                    <div role="menu" aria-label="{{ $user['name'] }}">
                        @foreach ($user['menu'] as $entry)
                            @if (! empty($entry['divider']))
                                <hr class="nx-admin-user-divider" />
                            @else
                                @if (! empty($entry['href']))
                                    <a class="nx-admin-user-item" role="menuitem" href="{{ $entry['href'] }}">@if (! empty($entry['icon'])){{ NabuXUI::icon($entry['icon']) }}@endif<span>{{ $entry['label'] }}</span></a>
                                @else
                                    <button type="button" class="nx-admin-user-item" role="menuitem">@if (! empty($entry['icon'])){{ NabuXUI::icon($entry['icon']) }}@endif<span>{{ $entry['label'] }}</span></button>
                                @endif
                            @endif
                        @endforeach
                    </div>
                @endif
            @endisset
        </div>
    @endif
</div>
