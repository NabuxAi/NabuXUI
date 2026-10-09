<?php

namespace App\Filament\Pages;

use App\Models\ChatMessage;
use App\Models\Conversation;
use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;

/**
 * The «گفت‌وگو» tool: the two seeded conversations over the
 * <x-nx::chat> block. The block's send-action="sendMessage" calls this
 * page after its local echo — the message lands in chat_messages with
 * is_mine=true, and the re-render's keyed bubbles (nx-chat-m-…) replace
 * the echo while the list preview follows the new last message. The open
 * conversation stays in sync through the block's x-modelable via
 * wire:model="active".
 */
class Chat extends Page
{
    protected string $view = 'filament.pages.chat';

    protected static string | UnitEnum | null $navigationGroup = 'ابزارها';

    protected static ?string $navigationLabel = 'گفت‌وگو';

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $title = 'گفت‌وگو';

    /** The open conversation, bound to the block's x-modelable. */
    public string $active = '';

    public function mount(): void
    {
        $this->subheading = 'گفت‌وگوهای نمونه روی دیتابیس؛ پیام‌هایی که می‌فرستید با is_mine ذخیره می‌شوند.';
        $this->active = (string) (Conversation::query()->orderBy('id')->value('id') ?? '');
    }

    /**
     * The composer's send-action: the block already echoed the bubble in the
     * browser and hands us (text, conversationId); persisting the same text
     * makes the morph swap the echo for the keyed server bubble and pins the
     * list preview to it.
     */
    public function sendMessage(string $text, string $id): void
    {
        $text = trim($text);

        if ($text === '' || mb_strlen($text) > 2000) {
            return;
        }

        $conversation = Conversation::query()->find($id);

        if ($conversation === null) {
            return;
        }

        ChatMessage::create([
            'conversation_id' => $conversation->getKey(),
            'is_mine' => true,
            'body' => $text,
            'sent_at' => now(),
        ]);

        $this->active = (string) $conversation->getKey();
    }

    /**
     * Fresh rows on every render, shaped for the block: the participant is
     * the name, the thread title becomes the header role, the latest message
     * feeds the list preview, and is_mine maps to the block's "out" side.
     *
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        return [
            'conversations' => Conversation::query()
                ->with('messages')
                ->orderBy('id')
                ->get()
                ->map(function (Conversation $conversation): array {
                    $messages = $conversation->messages
                        ->sortBy(fn (ChatMessage $message): string => (string) $message->sent_at)
                        ->values()
                        ->map(fn (ChatMessage $message): array => [
                            'id' => (string) $message->getKey(),
                            'side' => $message->is_mine ? 'out' : 'in',
                            'text' => (string) $message->body,
                            'time' => $message->sent_at,
                        ])
                        ->all();

                    $last = $messages === [] ? null : $messages[count($messages) - 1];

                    return [
                        'id' => (string) $conversation->getKey(),
                        'name' => $conversation->participant_name,
                        'role' => $conversation->title,
                        'preview' => $last['text'] ?? '',
                        'time' => $last['time'] ?? $conversation->updated_at,
                        'unread' => 0,
                        'messages' => $messages,
                    ];
                })
                ->all(),
        ];
    }
}
