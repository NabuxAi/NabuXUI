{{--
    /admin/users — the member directory: an avatar-group summary + invite,
    live search over name/role/email, role chips, then the data-table block's
    own markup grown a checkbox column — selection toolbar → remove dialog.
    Sorting is server-side (the headers call sortBy) so the rows keep their
    wire:key and glide (FLIP) through the morph. Copy comes from admin.* keys.
--}}
<x-admin.page active="users" :title="__('admin.users_title')" :subtitle="__('admin.users_subtitle')">
    <style>
        /* Users-page pieces the shared blocks don't carry (tokens only, both themes). */
        .up-count { font-size: var(--nx-text-sm); color: var(--nx-text-muted); }
        .up-spacer { flex: 1 1 auto; }
        .up-toolbar { border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); padding: var(--nx-space-2) var(--nx-space-3); background: var(--nx-surface-2); }
        .up-check { inline-size: 3rem; }
        .up-member { display: inline-flex; align-items: center; gap: 0.6rem; }
        .up-member-text { display: grid; gap: 0.05rem; text-align: start; }
        .up-member-name { color: var(--nx-text); font-weight: 600; }
        .up-member-email { font-size: var(--nx-text-xs); color: var(--nx-text-subtle); }
        .up-table tr[data-selected] td { background: var(--nx-accent-soft); }
        .up-table tr[data-selected] td:first-child { color: var(--nx-accent-text); }
    </style>
    <div class="ap-grid">
        <div class="ap-row" style="justify-content: space-between">
            <div class="ap-row">
                <x-nx::avatar-group :people="$people" :label="__('admin.users_title')" />
                <span class="up-count">{{ $memberCount }} {{ __('admin.users_members') }}</span>
            </div>
            <x-nx::button variant="primary" icon="users" wire:click="invite">{{ __('admin.users_invite') }}</x-nx::button>
        </div>

        <section class="ap-box">
            <div class="ap-row" style="justify-content: space-between">
                <x-nx::input :label="__('admin.users_search_label')"
                    :placeholder="__('admin.users_search_placeholder')" icon="search"
                    wire:model.live.debounce.300ms="search" style="max-inline-size: 20rem" />
                <x-nx::chip-filter :options="$chips" :counts="$counts" :label="__('admin.users_filter_role')" wire:model.live="role" />
            </div>

            @if (count($selectedPeople))
                <div class="ap-row up-toolbar" role="status">
                    <x-nx::avatar-group :people="$selectedPeople" :label="__('admin.users_selected')" size="xs" />
                    <span class="up-count">{{ $selectedCount }} {{ __('admin.users_selected_count') }}</span>
                    <span class="up-spacer"></span>
                    <x-nx::button size="sm" variant="ghost" wire:click="$set('selected', [])">{{ __('admin.users_clear') }}</x-nx::button>
                    <x-nx::button size="sm" variant="danger" icon="trash" wire:click="$set('confirming', true)">{{ __('admin.users_remove') }}</x-nx::button>
                </div>
            @endif

            @if (count($this->members) === 0)
                <x-nx::empty-state icon="users" size="lg"
                    :title="__('admin.users_empty_title')" :description="__('admin.users_empty_description')">
                    <x-slot:actions>
                        <x-nx::button variant="primary" icon="users" wire:click="invite">{{ __('admin.users_invite') }}</x-nx::button>
                    </x-slot:actions>
                </x-nx::empty-state>
            @else
                <div class="nx-data-table up-table" data-nx-reveal style="--nx-table-max: 26rem"
                    x-data="nxDataTable(@js(['sort' => $sort, 'action' => 'sortBy', 'locale' => $locale]))">
                    <table>
                        <caption class="nx-visually-hidden">{{ __('admin.users_title') }}</caption>
                        <thead>
                            <tr>
                                <th scope="col" data-key="picked" class="up-check">
                                    <x-nx::checkbox wire:click="toggleAll" @checked($allVisibleSelected) aria-label="{{ __('admin.users_select_all') }}" />
                                </th>
                                @foreach ($columns as $column)
                                    @php
                                        $align = $column['align'] ?? 'start';
                                        $direction = $sort['key'] === $column['key'] ? $sort['direction'] : null;
                                    @endphp
                                    <th scope="col" data-key="{{ $column['key'] }}" @if ($align !== 'start') data-align="{{ $align }}" @endif @if ($direction) aria-sort="{{ $direction }}" @endif>
                                        @if (! empty($column['sortable']))
                                            <button type="button" class="nx-data-table-sort" @if ($direction) data-direction="{{ $direction }}" @endif x-on:click="press(@js($column['key']))">
                                                <span>{{ $column['label'] }}</span>
                                                <svg class="nx-data-table-sort-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 19V5M6 11l6-6 6 6"/></svg>
                                            </button>
                                        @else
                                            {{ $column['label'] }}
                                        @endif
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody x-ref="body">
                            @forelse ($rows as $row)
                                <tr data-key="{{ $row['id'] }}" wire:key="u{{ $row['id'] }}" style="--nx-i: {{ $row['index'] }}" @if (in_array($row['id'], $selected, true)) data-selected @endif>
                                    <td>
                                        <x-nx::checkbox wire:model.live="selected" value="{{ $row['id'] }}" :aria-label="__('admin.users_select_member', ['name' => $row['name']])" />
                                    </td>
                                    <td>
                                        <span class="up-member">
                                            <x-nx::avatar :name="$row['name']" size="xs" />
                                            <span class="up-member-text">
                                                <span class="up-member-name">{{ $row['name'] }}</span>
                                                <span class="up-member-email">{{ $row['email'] }}</span>
                                            </span>
                                        </span>
                                    </td>
                                    <td data-sort="{{ $row['roleSort'] }}">
                                        @if ($row['roleTone'])
                                            <x-nx::badge :tone="$row['roleTone']">{{ $row['roleLabel'] }}</x-nx::badge>
                                        @else
                                            <x-nx::badge>{{ $row['roleLabel'] }}</x-nx::badge>
                                        @endif
                                    </td>
                                    <td data-sort="{{ $row['ordersSort'] }}" data-align="end" data-numeric>{{ $row['orders'] }}</td>
                                    <td data-sort="{{ $row['activeSort'] }}">{{ $row['active'] }}</td>
                                </tr>
                            @empty
                                <tr><td class="nx-data-table-empty" colspan="{{ count($columns) + 1 }}">{{ __('nabuxui::ui.noResults') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>

    <x-nx::dialog wire:model="confirming" :title="__('admin.users_remove_title')" :description="$removeText" size="sm">
        <x-slot:footer>
            <div class="ap-row" style="justify-content: flex-end">
                <x-nx::button variant="ghost" wire:click="$set('confirming', false)">{{ __('admin.users_remove_keep') }}</x-nx::button>
                <x-nx::button variant="danger" icon="trash" wire:click="removeSelected">{{ __('admin.users_remove_confirm') }}</x-nx::button>
            </div>
        </x-slot:footer>
    </x-nx::dialog>
</x-admin.page>
