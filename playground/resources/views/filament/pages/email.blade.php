{{--
    ابزار «ایمیل» — the seeded inbox on the <x-nx::email> block, fed from
    App\Filament\Pages\Email::getViewData(): the folder rail (inbox +
    archive, the table's real folders), the message list and the reading
    pane with its reply composer. The block's bubbling events reach the
    page component on the root section itself: nx-open → openEmail()
    persists is_read and refreshes the navigation badges, nx-folder →
    rememberFolder() keeps the picked folder across renders, wire:model
    binds the open message to the page's openId, and reply-action
    calls sendReply after the composer clears.
--}}
<x-filament-panels::page>
    <x-nx::email
        :messages="$messages"
        :folders="$folders"
        :open="$open"
        :folder="$folder"
        wire:model="openId"
        reply-action="sendReply"
        height="36rem"
        x-on:nx-open="$wire.openEmail($event.detail)"
        x-on:nx-folder="$wire.rememberFolder($event.detail)"
    />
</x-filament-panels::page>
