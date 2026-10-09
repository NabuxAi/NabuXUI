<?php

namespace App\Filament\Pages;

use App\Models\Email as EmailModel;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use NabuXUI\NabuXUI;
use UnitEnum;

/**
 * The «ایمیل» tool: the seeded inbox on the <x-nx::email> block — the
 * folder rail, the message list and the reading pane with its reply
 * composer. Opening a message bubbles nx-open into openEmail(), which
 * persists is_read and refreshes the sidebar/topbar badges; nx-folder
 * keeps the picked folder in sync so Livewire renders agree with Alpine;
 * reply-action="sendReply" acknowledges the composer. The navigation
 * badge carries the unread tally in the panel's digits.
 */
class Email extends Page
{
    protected string $view = 'filament.pages.email';

    protected static string | UnitEnum | null $navigationGroup = 'ابزارها';

    protected static ?string $navigationLabel = 'ایمیل';

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-inbox-stack';

    protected static ?string $title = 'ایمیل';

    /** The message being read, bound to the block's x-modelable. */
    public string $openId = '';

    /** The open folder (inbox|archive), remembered across renders. */
    public string $folder = 'inbox';

    public function mount(): void
    {
        $this->subheading = 'صندوق ورودی روی دیتابیس؛ با باز کردن هر پیام خوانده‌شده علامت می‌خورد و شمارندهٔ ناخواندهٔ ناوبری به‌روز می‌شود.';
    }

    /**
     * nx-open from the block: the click opened the message in the reading
     * pane, so persist is_read once and let the sidebar/topbar re-count
     * their unread badges. openId rides along so the next render reopens
     * the same pane.
     */
    public function openEmail(string $messageId): void
    {
        $email = EmailModel::query()->find($messageId);

        if ($email === null) {
            return;
        }

        $this->openId = (string) $email->getKey();

        if (! $email->is_read) {
            $email->forceFill(['is_read' => true])->save();

            $this->dispatch('refresh-sidebar');
            $this->dispatch('refresh-topbar');
        }
    }

    /**
     * nx-folder from the block: remembering the pick keeps the server
     * render (visible rows, aria-current) in step with Alpine's state.
     */
    public function rememberFolder(string $folder): void
    {
        if (in_array($folder, EmailModel::FOLDERS, true)) {
            $this->folder = $folder;
        }
    }

    /**
     * The composer's reply-action: the block cleared itself and hands us
     * (text, messageId); the playground has no outgoing table, so the
     * reply is acknowledged with a notification.
     */
    public function sendReply(string $text, string $messageId): void
    {
        if (trim($text) === '' || EmailModel::query()->whereKey($messageId)->doesntExist()) {
            return;
        }

        Notification::make()
            ->title('پاسخ ارسال شد')
            ->body('پاسخ شما به فرستندهٔ این پیام ثبت شد.')
            ->success()
            ->send();
    }

    /** The nav badge: the unread tally, hidden at zero. */
    public static function getNavigationBadge(): ?string
    {
        $unread = static::unreadCount();

        return $unread > 0 ? NabuXUI::formatNumber($unread, 0) : null;
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return 'danger';
    }

    public static function getNavigationBadgeTooltip(): string | Htmlable | null
    {
        $unread = static::unreadCount();

        return $unread > 0 ? NabuXUI::formatNumber($unread, 0).' پیام ناخوانده' : null;
    }

    /** Emails still waiting to be read, across every folder. */
    protected static function unreadCount(): int
    {
        return EmailModel::query()->where('is_read', false)->count();
    }

    /**
     * Fresh rows on every render, shaped for the block: the two real
     * folders replace the six built-ins, and every record maps onto the
     * message shape (id, from, subject, body, time, unread, folder).
     *
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        return [
            'messages' => EmailModel::query()
                ->orderByDesc('received_at')
                ->get()
                ->map(fn (EmailModel $email): array => [
                    'id' => (string) $email->getKey(),
                    'from' => [
                        'name' => $email->sender_name,
                        'email' => $email->sender_email,
                    ],
                    'subject' => $email->subject,
                    'body' => $email->body,
                    'time' => $email->received_at,
                    'unread' => ! $email->is_read,
                    // The table has no starred column; the block keeps the
                    // star client-side.
                    'starred' => false,
                    'folder' => $email->folder,
                ])
                ->all(),
            'folders' => [
                ['id' => 'inbox', 'label' => 'صندوق ورودی', 'icon' => 'mail'],
                ['id' => 'archive', 'label' => 'بایگانی', 'icon' => 'folder'],
            ],
            'open' => $this->openId,
            'folder' => $this->folder,
        ];
    }
}
