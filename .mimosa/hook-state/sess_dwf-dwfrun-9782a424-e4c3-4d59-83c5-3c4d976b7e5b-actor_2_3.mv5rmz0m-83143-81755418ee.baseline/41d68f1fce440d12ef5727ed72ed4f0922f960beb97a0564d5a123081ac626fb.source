{{--
    /admin/todo — the personal task list: the nx-todo block over Livewire as
    the single truth (toggle/remove/add/move actions round-trip, tasks keep
    their wire:key and glide through the morph). The chips filter what the
    block renders — all / open / done — the heading carries the open count,
    and the toolbar's button sweeps the completed rows away. An emptied list
    falls to the empty-state. Words from admin.todo_*; the block's own words
    (add task, empty, progress) live in the core i18n table.
--}}
<x-admin.page active="todo" :title="__('admin.todo_title')" :subtitle="__('admin.todo_subtitle')">
    <div class="ap-grid">
        @if ($total === 0)
            <x-nx::empty-state icon="check-circle" size="lg"
                :title="__('admin.todo_empty_title')" :description="__('admin.todo_empty_description')" />
        @else
            <section class="ap-box">
                <div class="ap-row" style="justify-content: space-between">
                    <x-nx::chip-filter :options="$chips" :counts="$counts" :label="__('admin.todo_title')" wire:model.live="filter" />
                    <x-nx::button size="sm" variant="ghost" icon="trash" wire:click="clearDone">{{ __('admin.todo_clear_done') }}</x-nx::button>
                </div>

                <x-nx::todo :groups="$shown" :heading="$leftText"
                    toggle-action="toggleTask" remove-action="removeTask"
                    add-action="addTask" move-action="moveTask" />
            </section>
        @endif
    </div>
</x-admin.page>
