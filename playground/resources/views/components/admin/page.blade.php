{{--
    <x-admin.page active="dashboard" :title="__('admin.dashboard_title')" :subtitle="…">
        … the page's content (lands in the shell's content area) …
    </x-admin.page>

    The admin pages' common layout piece: <x-nx::admin-shell> with the shared
    sidebar groups, topbar (search seat → ⌘K command palette, theme toggle,
    fa/en language menu, activity bell, avatar menu with sign-out) and the
    command palette itself. Lives inside each Livewire page's view (not the
    blade layout) so the topbar's wire:model/wire:click land inside the
    component root. `active` matches the nav item ids in App\Support\Panel.
--}}
@props(['active', 'title' => null, 'subtitle' => null])
@php
    use App\Support\Panel;
    use NabuXUI\NabuXUI;
@endphp
<div class="nx-page ap-page" wire:transition.navigate="nx-page" x-on:nx-search.window="$dispatch('nx-command')">
    <x-nx::admin-shell :groups="Panel::nav()" :active="$active" :brand="__('admin.brand')" :nav-label="__('admin.nav_label')"
        :title="$title" :subtitle="$subtitle"
        :search-placeholder="__('admin.search_placeholder')" :search-hint="__('admin.search_hint')"
        :user="Panel::user()" height="calc(100dvh - 3rem)" radius="1.25rem" border="1px">
        <x-slot:actions>
            <x-nx::theme-toggle />
            <x-nx::language-menu :languages="Panel::languages()" :value="app()->getLocale()" wire:model.live="locale" :label="__('admin.language')" />
            <x-nx::activity-dropdown :items="Panel::activity()" x-on:nx-mark-all-read="$wire.markAllActivityRead()" />
        </x-slot:actions>
        <x-slot:userMenu>
            <div class="nx-admin-user-head">
                <p class="nx-admin-user-name">{{ __('admin.user_name') }}</p>
                <p class="nx-admin-user-role">{{ __('admin.user_role') }}</p>
            </div>
            <div role="menu" aria-label="{{ __('admin.user_name') }}">
                <a class="nx-admin-user-item" role="menuitem" href="{{ route('admin.profile') }}">{{ NabuXUI::icon('user') }}<span>{{ __('admin.menu_profile') }}</span></a>
                <a class="nx-admin-user-item" role="menuitem" href="{{ route('admin.settings') }}">{{ NabuXUI::icon('settings') }}<span>{{ __('admin.menu_settings') }}</span></a>
                <hr class="nx-admin-user-divider" />
                <button type="button" class="nx-admin-user-item" role="menuitem" wire:click="logout">{{ NabuXUI::icon('lock') }}<span>{{ __('admin.menu_logout') }}</span></button>
            </div>
        </x-slot:userMenu>
        {{ $slot }}
    </x-nx::admin-shell>

    <x-nx::command :groups="Panel::command()" :placeholder="__('admin.search_placeholder')" />
</div>
