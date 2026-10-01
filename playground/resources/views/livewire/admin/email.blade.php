{{--
    /admin/email — the full-page mail client: the nx-email block with its
    built-in folders (the block's own words come from the core i18n table),
    the reply composer wired to sendReply for its toast, and the header's
    unread tally agreeing with the sidebar badge. The compose dialog holds
    to / subject / body and either sends or parks the letter as a draft.
    Page copy comes from the admin.email_* keys.
--}}
<x-admin.page active="email" :title="__('admin.email_title')" :subtitle="__('admin.email_subtitle')">
    <style>
        /* Email-page pieces the shared block doesn't carry (tokens only, both themes). */
        .ep-unread { display: inline-flex; align-items: center; gap: 0.5rem; font-size: var(--nx-text-sm); color: var(--nx-text-muted); }
        .ep-unread-dot { inline-size: 0.5rem; block-size: 0.5rem; border-radius: var(--nx-radius-full); background: var(--nx-accent); }
        .ep-compose { display: grid; gap: var(--nx-space-4); }
    </style>
    <div class="ap-grid">
        <div class="ap-row" style="justify-content: space-between">
            <span class="ep-unread"><i class="ep-unread-dot" aria-hidden="true"></i>{{ $unreadText }}</span>
            <x-nx::button variant="primary" icon="edit" wire:click="$set('composing', true)">{{ __('admin.email_compose') }}</x-nx::button>
        </div>

        <x-nx::email :messages="$messages" reply-action="sendReply" height="34rem" />
    </div>

    <x-nx::dialog wire:model="composing" :title="__('admin.email_compose_title')" size="md">
        <div class="ep-compose">
            <x-nx::input :label="__('admin.email_compose_to')" wire:model="composeTo" />
            <x-nx::input :label="__('admin.email_compose_subject')" wire:model="composeSubject" />
            <x-nx::textarea :label="__('admin.email_compose_body')" :placeholder="__('admin.email_compose_placeholder')" wire:model="composeBody" />
        </div>
        <x-slot:footer>
            <div class="ap-row" style="justify-content: space-between">
                <x-nx::button variant="ghost" icon="file" wire:click="saveDraft">{{ __('admin.email_folder_drafts') }}</x-nx::button>
                <x-nx::button variant="primary" icon="arrow-right" wire:click="send">{{ __('admin.email_send') }}</x-nx::button>
            </div>
        </x-slot:footer>
    </x-nx::dialog>
</x-admin.page>
