{{--
    /login · /register · /forgot-password — the auth-card gate: the animated
    brand panel beside the three-pane form. The card switches panes in the
    browser and mirrors each switch into $mode (nx-mode-change) so the single
    wire:submit knows which pane sent it. Already "signed in" (demo session)?
    An alert offers a jump straight into the panel.
--}}
@php
    use App\Support\Panel;
@endphp
<div class="nx-page ap-auth" wire:transition.navigate="nx-page">
    <div class="ap-auth-top">
        <x-nx::button size="sm" variant="ghost" icon="arrow-left" href="{{ url('/') }}">{{ __('admin.auth_back') }}</x-nx::button>
        <div class="ap-row">
            <x-nx::theme-toggle />
            <x-nx::language-menu :languages="Panel::languages()" :value="app()->getLocale()" wire:model.live="locale" :label="__('admin.language')" />
        </div>
    </div>

    @if (session('admin_auth'))
        <x-nx::alert class="ap-auth-alert" tone="info" :title="__('admin.auth_signed_in')">
            <x-slot:actions>
                <x-nx::button size="sm" variant="primary" icon="grid" href="{{ route('admin.dashboard') }}">{{ __('admin.auth_go_panel') }}</x-nx::button>
            </x-slot:actions>
        </x-nx::alert>
    @endif

    <x-nx::auth-card :mode="$mode" :brand="__('admin.brand')" :tagline="__('admin.auth_tagline')"
        :perks="[__('admin.auth_perk_1'), __('admin.auth_perk_2'), __('admin.auth_perk_3')]"
        model="form" wire:submit="submit"
        x-on:nx-mode-change="$wire.set('mode', $event.detail.mode)" />
</div>
