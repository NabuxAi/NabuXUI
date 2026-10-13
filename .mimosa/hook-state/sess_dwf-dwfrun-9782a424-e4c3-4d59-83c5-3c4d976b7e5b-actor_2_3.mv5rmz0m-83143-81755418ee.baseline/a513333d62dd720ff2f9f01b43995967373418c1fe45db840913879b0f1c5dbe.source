{{--
    /admin/roles — the permission matrix: a plain semantic table grown on the
    panel's .ap-box, sections on the row axis (products → orders → users →
    settings → reports) and the three roles on the columns, one live switch at
    every crossing (roles_note: grants land on every member at once). The
    toolbar's Save only wakes once a crossing leaves its default and then
    acknowledges with a toast. Copy comes from admin.roles_* + users_role_*;
    the caption keeps the table named for screen readers and the rows reveal
    in the family's staggered spring (asleep under reduced motion).
--}}
<x-admin.page active="roles" :title="__('admin.roles_title')" :subtitle="__('admin.roles_subtitle')">
    <style>
        /* Roles-page pieces the shared blocks don't carry (tokens only, both themes). */
        .rm-scroll { overflow-x: auto; }
        .rm-table { inline-size: 100%; border-collapse: collapse; }
        .rm-table :is(th, td) { padding: var(--nx-space-2) var(--nx-space-3); border-block-end: 1px solid var(--nx-border); }
        .rm-table tbody tr:last-child :is(th, td) { border-block-end: 0; }
        .rm-first { text-align: start; white-space: nowrap; }
        .rm-col { text-align: center; white-space: nowrap; }
        .rm-perm { text-align: start; font-weight: 500; color: var(--nx-text); white-space: nowrap; }
        .rm-cell { text-align: center; }
        .rm-group th { border-block: 1px solid var(--nx-border); background: var(--nx-surface-2);
            padding-block: var(--nx-space-1) var(--nx-space-2); text-align: start; white-space: nowrap;
            font: 600 var(--nx-text-xs) / 1.4 var(--nx-font-display);
            letter-spacing: var(--nx-tracking-wider); color: var(--nx-text-muted); }
        .rm-count { margin: 0; }
    </style>
    <div class="ap-grid">
        <div class="ap-row" style="justify-content: space-between">
            <p class="rm-count">{{ __('admin.roles_count', ['count' => $roleCount]) }}</p>
            <x-nx::button variant="primary" icon="check-circle" wire:click="save" :disabled="! $dirty">{{ __('admin.roles_save') }}</x-nx::button>
        </div>

        <section class="ap-box" data-nx-reveal x-nx-reveal>
            <h2 class="ap-box-title">{{ __('admin.roles_matrix_caption') }}</h2>
            <div class="rm-scroll">
                <table class="rm-table">
                    <caption class="nx-visually-hidden">{{ __('admin.roles_matrix_caption') }}</caption>
                    <thead>
                        <tr>
                            <th scope="col" class="rm-first">{{ __('admin.roles_column_permission') }}</th>
                            @foreach ($roles as $role)
                                <th scope="col" class="rm-col">
                                    @if ($role['tone'])
                                        <x-nx::badge :tone="$role['tone']">{{ $role['label'] }}</x-nx::badge>
                                    @else
                                        <x-nx::badge>{{ $role['label'] }}</x-nx::badge>
                                    @endif
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody data-nx-reveal="group" x-nx-reveal.group>
                        @foreach ($groups as $group)
                            <tr class="rm-group">
                                <th scope="colgroup" colspan="{{ count($roles) + 1 }}">{{ $group['label'] }}</th>
                            </tr>
                            @foreach ($group['perms'] as $perm)
                                <tr wire:key="r-{{ $perm['id'] }}">
                                    <th scope="row" class="rm-perm">{{ $perm['label'] }}</th>
                                    @foreach ($roles as $role)
                                        <td class="rm-cell">
                                            <x-nx::switch size="sm" wire:model.live="matrix.{{ $role['id'] }}.{{ $perm['id'] }}" aria-label="{{ $perm['grants'][$role['id']] }}" />
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="ap-box-more">{{ __('admin.roles_note') }}</p>
        </section>
    </div>
</x-admin.page>
