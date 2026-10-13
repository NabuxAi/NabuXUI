{{--
    /admin/profile — the account card (avatar, role badge, email) beside the
    three tabs: the overview fact sheet, the activity stream over the
    timeline-feed block, and the personal preferences. The tabs live in
    $tab (wire:model through the tabs block's entangle); the language row is
    the same fa/en switch the topbar uses, so both stay in step.
--}}
<x-admin.page active="profile" :title="__('admin.profile_title')" :subtitle="__('admin.profile_subtitle')">
    <style>
        /* The account card. */
        .ap-profile-card { gap: var(--nx-space-3); justify-items: start; }
        .ap-profile-name { margin: 0; font: 700 var(--nx-text-xl) / 1.4 var(--nx-font-display); letter-spacing: var(--nx-tracking-tight); color: var(--nx-text); }
        .ap-profile-mail { margin: 0; display: inline-flex; align-items: center; gap: var(--nx-space-2); color: var(--nx-text-muted); }
        .ap-profile-mail .nx-icon { inline-size: 1rem; block-size: 1rem; color: var(--nx-text-subtle); }

        /* The overview's fact sheet. */
        .ap-profile-facts { margin: 0; display: grid; gap: var(--nx-space-3); }
        .ap-profile-fact { display: grid; gap: var(--nx-space-1); padding-block-end: var(--nx-space-3); border-block-end: 1px solid var(--nx-border); }
        .ap-profile-fact:last-child { border-block-end: none; padding-block-end: 0; }
        .ap-profile-fact dt { color: var(--nx-text-subtle); }
        .ap-profile-fact dd { margin: 0; font-weight: 600; color: var(--nx-text); }

        /* The preferences rows: label beside the control. */
        .ap-prefs { display: grid; gap: var(--nx-space-4); align-content: start; }
        .ap-pref { display: flex; flex-wrap: wrap; gap: var(--nx-space-3); align-items: center; justify-content: space-between; }
        .ap-pref-label { color: var(--nx-text); font-weight: 600; }
        .ap-pref-label .nx-icon { inline-size: 1rem; block-size: 1rem; color: var(--nx-text-subtle); }
        .ap-prefs .nx-select { min-inline-size: min(14rem, 100%); }
    </style>

    <div class="ap-grid">
        <div class="ap-duo">
            <section class="ap-box ap-profile-card" aria-label="{{ $user['name'] }}">
                <x-nx::avatar :name="$user['name']" size="lg" />
                <p class="ap-profile-name">{{ $user['name'] }}</p>
                <x-nx::badge tone="accent">{{ $user['role'] }}</x-nx::badge>
                <p class="ap-profile-mail">{{ \NabuXUI\NabuXUI::icon('mail') }}<span>{{ $email }}</span></p>
            </section>

            <section class="ap-box">
                <x-nx::tabs wire:model="tab" :label="__('admin.profile_title')" :items="[
                    'overview' => ['label' => __('admin.profile_tab_overview'), 'icon' => 'user'],
                    'activity' => ['label' => __('admin.activity_title'), 'icon' => 'zap'],
                    'settings' => ['label' => __('admin.settings'), 'icon' => 'settings'],
                ]">
                    <x-slot:overview>
                        <dl class="ap-profile-facts">
                            <div class="ap-profile-fact">
                                <dt>{{ __('admin.profile_field_name') }}</dt>
                                <dd>{{ $user['name'] }}</dd>
                            </div>
                            <div class="ap-profile-fact">
                                <dt>{{ __('admin.profile_field_email') }}</dt>
                                <dd>{{ $email }}</dd>
                            </div>
                            <div class="ap-profile-fact">
                                <dt>{{ __('admin.profile_field_role') }}</dt>
                                <dd>{{ $user['role'] }}</dd>
                            </div>
                            <div class="ap-profile-fact">
                                <dt>{{ __('admin.profile_field_timezone') }}</dt>
                                <dd>{{ $timezone }}</dd>
                            </div>
                        </dl>
                    </x-slot:overview>

                    <x-slot:activity>
                        <x-nx::timeline-feed :items="$timeline" :label="__('admin.activity_title')" />
                    </x-slot:activity>

                    <x-slot:settings>
                        <div class="ap-prefs">
                            <x-nx::select :label="__('admin.profile_field_timezone')" :options="$timezones" wire:model="timezone" />
                            <div class="ap-pref">
                                <span class="ap-pref-label">{{ \NabuXUI\NabuXUI::icon('moon') }} {{ __('admin.settings_theme') }}</span>
                                <x-nx::theme-switch :value="'system'" />
                            </div>
                            <div class="ap-pref">
                                <span class="ap-pref-label">{{ \NabuXUI\NabuXUI::icon('globe') }} {{ __('admin.language') }}</span>
                                <x-nx::language-menu :languages="\App\Support\Panel::languages()" :value="app()->getLocale()"
                                    wire:model.live="locale" :label="__('admin.language')" />
                            </div>
                            <div class="ap-row">
                                <x-nx::button variant="primary" icon="check" wire:click="save">{{ __('admin.settings_save') }}</x-nx::button>
                            </div>
                        </div>
                    </x-slot:settings>
                </x-nx::tabs>
            </section>
        </div>
    </div>
</x-admin.page>
