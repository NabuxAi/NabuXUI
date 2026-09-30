{{--
    /admin/kanban — the sprint board over the kanban block. move-action /
    add-action hand every drag, three-dot move and quick-add back to the
    Livewire component, so the columns above stay the single truth and the
    cards glide (FLIP) through each morph. All words come from admin.* keys.
--}}
<x-admin.page active="kanban" :title="__('admin.kanban_title')" :subtitle="__('admin.kanban_subtitle')">
    <div class="ap-grid">
        <x-nx::kanban :columns="$board" :label="__('admin.kanban_title')"
            move-action="moveCard" add-action="addCard" height="34rem" />
    </div>
</x-admin.page>
