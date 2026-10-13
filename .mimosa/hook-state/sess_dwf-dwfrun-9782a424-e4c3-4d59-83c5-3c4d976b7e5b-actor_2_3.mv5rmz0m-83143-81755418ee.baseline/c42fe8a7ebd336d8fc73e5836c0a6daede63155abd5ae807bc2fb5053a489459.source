{{--
    ابزار «گفت‌وگو» — the two seeded conversations over the <x-nx::chat>
    block, fed from App\Filament\Pages\Chat::getViewData(). The block's
    x-modelable binds the open conversation to $active (wire:model, deferred),
    and send-action="sendMessage" calls the page action after the local echo,
    which persists the message with is_mine=true and re-renders the keyed
    bubbles over it.
--}}
<x-filament-panels::page>
    <x-nx::chat
        :conversations="$conversations"
        :active="$active"
        wire:model="active"
        send-action="sendMessage"
        height="34rem"
    />
</x-filament-panels::page>
