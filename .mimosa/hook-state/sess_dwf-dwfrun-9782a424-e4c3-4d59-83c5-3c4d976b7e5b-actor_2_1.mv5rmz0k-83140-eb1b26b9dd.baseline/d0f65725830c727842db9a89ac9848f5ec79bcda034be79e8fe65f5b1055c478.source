{{--
    /admin/settings — the workspace form, the security and notification
    toggles, the appearance rows and the danger zone. Everything lives in
    $form (wire:submit="save" commits it with a toast); the appearance rows
    write the same stores the topbar reads — the theme-switch the core theme
    store, the language-menu the SwitchesDemoLocale session — and the danger
    zone's "delete workspace" opens the dialog block (a native <dialog>) whose
    confirm only toasts.
--}}
<x-admin.page active="settings" :title="__('admin.settings_title')" :subtitle="__('admin.settings_subtitle')">
    <style>
        /* The settings form: one box per section, its fields stacked. */
        .ap-settings-form { display: grid; gap: var(--nx-space-4); align-content: start; }
        .ap-settings-box { max-inline-size: 40rem; }
        .ap-settings-choices { display: grid; gap: var(--nx-space-3); }
        .ap-settings-actions { display: flex; flex-wrap: wrap; gap: var(--nx-space-3); align-items: center; }
        .ap-pref { display: flex; flex-wrap: wrap; gap: var(--nx-space-3); align-items: center; justify-content: space-between; }
        .ap-pref-label { color: var(--nx-text); font-weight: 600; }
        .ap-pref-label .nx-icon { inline-size: 1rem; block-size: 1rem; color: var(--nx-text-subtle); }

        /* The danger zone reads as one: a quiet red frame around the last resort. */
        .ap-danger { border-color: var(--nx-danger); }
        .ap-danger .ap-box-title { color: var(--nx-danger); }
        .ap-danger-text { margin: 0; color: var(--nx-text-muted); }
    </style>

    <form class="ap-grid ap-settings-form" wire:submit="save">
        <section class="ap-box ap-settings-box">
            <h3 class="ap-box-title">{{ __('admin.settings_section_workspace') }}</h3>
            <x-nx::input :label="__('admin.settings_field_workspace_name')" wire:model="form.workspace" autocomplete="organization" />
            <x-nx::select :label="__('admin.settings_field_default_role')" :options="$roles" wire:model="form.defaultRole" />
        </section>

        <section class="ap-box ap-settings-box">
            <h3 class="ap-box-title">{{ __('admin.settings_section_security') }}</h3>
            <div class="ap-settings-choices">
                <x-nx::switch :label="__('admin.settings_toggle_2fa')" wire:model="form.twoFactor" />
                <x-nx::checkbox :label="__('admin.settings_toggle_signin_alert')" wire:model="form.signinAlert" />
            </div>
        </section>

        <section class="ap-box ap-settings-box">
            <h3 class="ap-box-title">{{ __('admin.settings_section_notifications') }}</h3>
            <div class="ap-settings-choices">
                <x-nx::switch :label="__('admin.settings_notify_email')" wire:model="form.emailDigest" />
                <x-nx::switch :label="__('admin.settings_notify_product')" wire:model="form.productUpdates" />
            </div>
        </section>

        <section class="ap-box ap-settings-box">
            <h3 class="ap-box-title">{{ __('admin.settings_section_appearance') }}</h3>
            <div class="ap-pref">
                <span class="ap-pref-label">{{ \NabuXUI\NabuXUI::icon('moon') }} {{ __('admin.settings_theme') }}</span>
                <x-nx::theme-switch :value="'system'" />
            </div>
            <div class="ap-pref">
                <span class="ap-pref-label">{{ \NabuXUI\NabuXUI::icon('globe') }} {{ __('admin.language') }}</span>
                <x-nx::language-menu :languages="$languages" :value="app()->getLocale()" wire:model.live="locale" :label="__('admin.language')" />
            </div>
        </section>

        <div class="ap-settings-actions">
            <x-nx::button variant="primary" icon="check" type="submit">{{ __('admin.settings_save') }}</x-nx::button>
        </div>
    </form>

    <section class="ap-box ap-settings-box ap-danger">
        <h3 class="ap-box-title">{{ __('admin.settings_danger_title') }}</h3>
        <p class="ap-danger-text">{{ __('admin.settings_danger_text') }}</p>
        <div class="ap-settings-actions">
            <x-nx::button variant="danger" icon="trash" wire:click="$set('confirmingDelete', true)">{{ __('admin.settings_danger_title') }}</x-nx::button>
        </div>
    </section>

    <x-nx::dialog wire:model="confirmingDelete" :title="__('admin.settings_danger_title')" size="sm">
        <p>{{ __('admin.settings_danger_dialog_text') }}</p>
        <x-slot:footer>
            <x-nx::button variant="secondary" wire:click="$set('confirmingDelete', false)">{{ __('admin.settings_cancel') }}</x-nx::button>
            <x-nx::button variant="danger" icon="trash" wire:click="deleteWorkspace">{{ __('admin.settings_danger_confirm') }}</x-nx::button>
        </x-slot:footer>
    </x-nx::dialog>
</x-admin.page>
