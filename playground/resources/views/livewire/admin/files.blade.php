{{--
    /admin/files — the workspace's media library: the nx-file-manager with
    its breadcrumbs, live search and grid/list switch; the type chips filter
    server-side (folders always stay so the tree keeps walking). Both the
    manager's upload button and the file-drop below feed the same Livewire
    property — the toast lands when the round-trip finishes. The side rail
    carries the storage gauge and the library's count. Words from
    admin.files_*; the block's own words (search, views, upload…) live in
    the core i18n table.
--}}
<x-admin.page active="files" :title="__('admin.files_title')" :subtitle="__('admin.files_subtitle')">
    <style>
        /* Files-page pieces the shared blocks don't carry (tokens only, both themes). */
        .fp-count { font-size: var(--nx-text-sm); color: var(--nx-text-muted); }
        .fp-side { justify-items: center; text-align: center; }
        .fp-side-more { margin: 0; font-size: var(--nx-text-xs); color: var(--nx-text-subtle); }
    </style>
    <div class="ap-grid">
        <div class="ap-row" style="justify-content: space-between">
            <span class="fp-count">{{ $countText }}</span>
            <x-nx::chip-filter :options="$chips" :counts="$counts" :label="__('admin.files_filter_type')" wire:model.live="type" />
        </div>

        <div class="ap-duo">
            <div class="ap-grid">
                <x-nx::file-manager :items="$items" wire:model="uploads" height="30rem" />
                <x-nx::file-drop wire:model="uploads" multiple :title="__('admin.files_drop_hint')" />
            </div>

            <aside class="ap-box fp-side">
                <x-nx::gauge :value="6.4" :max="10" unit="GB" :decimals="1"
                    :title="__('admin.files_storage')" :subtitle="__('admin.files_storage_note')" />
            </aside>
        </div>
    </div>
</x-admin.page>
