{{--
    /admin/chat — the conversations column, the open thread and the composer
    over the chat block. The threads are Livewire-local state ($threads in the
    class): send-action="sendMessage" appends each sent message server-side so
    the morph keeps the local echo, pins the thread's preview and clears its
    unread badge; wire:model="active" (the block's x-modelable) keeps the open
    thread in sync. The sidebar's "5" badge is the threads' unread sum.
--}}
<x-admin.page active="chat" :title="__('admin.chat_title')" :subtitle="__('admin.chat_subtitle')">
    <div class="ap-grid">
        <x-nx::chat :conversations="$conversations" :active="$active" wire:model="active"
            :typing="$typing" send-action="sendMessage" height="34rem" />
    </div>
</x-admin.page>
