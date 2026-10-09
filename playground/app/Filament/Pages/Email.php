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
 *
 * On top of that sit two real, persisted features of the page itself:
 *
 *  - «خواندن همه»: markAllRead() flips every unread inbox message to
 *    is_read in one query — the navigation badge, the page toolbar and
 *    the block's own folder badges all fall to zero (the latter through
 *    the nx-email-unread-cleared browser event that rewrites the block's
 *    live Alpine rows, which otherwise survive a morph with stale flags).
 *
 *  - آرشیو: archiveEmail()/restoreEmail() move a row between folder =
 *    inbox and folder = archive in the database; the nx-email-moved
 *    event keeps the Alpine row's folder in step so the message leaves
 *    the inbox the moment it is archived. The «بایگانی‌شده‌ها» tab on the
 *    toolbar switches to the archived folder (nx-email-folder drives the
 *    block's pick()), where the open message offers «بازگردانی».
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
        $this->subheading = 'صندوق ورودی روی دیتابیس؛ با باز کردن هر پیام خوانده‌شده علامت می‌خورد. «خواندن همه» همهٔ پیام‌های ناخوانده را یک‌جا می‌خواند و «بایگانی» هر پیام را به بایگانی می‌برد — از زبانهٔ «بایگانی‌شده‌ها» می‌توانید آن را به صندوق ورودی برگردانید.';
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
     * «خواندن همه»: every unread message of the inbox becomes read in one
     * update, the toolbar/counters re-render at zero, and the browser
     * event rewrites the block's live Alpine rows (their unread flags)
     * so its folder badges empty out without waiting for a reload.
     */
    public function markAllRead(): void
    {
        $marked = EmailModel::query()
            ->where('folder', 'inbox')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        if ($marked === 0) {
            return;
        }

        $this->dispatch('nx-email-unread-cleared', folder: 'inbox');
        $this->dispatch('refresh-sidebar');
        $this->dispatch('refresh-topbar');

        Notification::make()
            ->title('همهٔ پیام‌ها خوانده شد')
            ->body(NabuXUI::formatNumber($marked, 0).' پیام ناخواندهٔ صندوق ورودی خوانده‌شده علامت خورد.')
            ->success()
            ->send();
    }

    /**
     * آرشیو: the open (or addressed) message moves to folder = archive in
     * the database; nx-email-moved flips the Alpine row's folder so the
     * row leaves the inbox list the same instant.
     */
    public function archiveEmail(string $messageId): void
    {
        $this->moveEmail($messageId, 'archive');
    }

    /**
     * The archived tab's restore: the message returns to folder = inbox.
     */
    public function restoreEmail(string $messageId): void
    {
        $this->moveEmail($messageId, 'inbox');
    }

    /** The shared body of archive/restore: one folder flip, one event. */
    protected function moveEmail(string $messageId, string $folder): void
    {
        if (! in_array($folder, EmailModel::FOLDERS, true)) {
            return;
        }

        $email = EmailModel::query()->find($messageId);

        if ($email === null || $email->folder === $folder) {
            return;
        }

        $email->forceFill(['folder' => $folder])->save();

        $this->dispatch('nx-email-moved', id: (string) $email->getKey(), folder: $folder);

        $archived = $folder === 'archive';

        Notification::make()
            ->title($archived ? 'پیام بایگانی شد' : 'پیام به صندوق ورودی بازگشت')
            ->body($archived
                ? 'پیام از صندوق ورودی به بایگانی منتقل شد؛ از زبانهٔ «بایگانی‌شده‌ها» قابل بازگشت است.'
                : 'پیام دوباره در صندوق ورودی قرار گرفت.')
            ->success()
            ->send();
    }

    /**
     * The toolbar's folder tabs (inbox | archive): remember the pick and
     * drive the block's own pick() through a browser event, so the rail,
     * the tabs and Alpine agree without a full reload.
     */
    public function switchFolder(string $folder): void
    {
        if (! in_array($folder, EmailModel::FOLDERS, true) || $folder === $this->folder) {
            return;
        }

        $this->folder = $folder;

        $this->dispatch('nx-email-folder', folder: $folder);
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
     * The toolbar's numbers (unread in the inbox, the archive tally and
     * the open message's folder) come from the same single query.
     *
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        $emails = EmailModel::query()
            ->orderByDesc('received_at')
            ->get();

        $open = $this->openId === ''
            ? null
            : $emails->first(fn (EmailModel $email): bool => (string) $email->getKey() === $this->openId);

        return [
            'messages' => $emails
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
            'inboxUnread' => $emails->where('folder', 'inbox')->where('is_read', false)->count(),
            'archivedCount' => $emails->where('folder', 'archive')->count(),
            // The toolbar's archive/restore button reads the open message's
            // current folder to decide which way it points.
            'openFolder' => $open?->folder,
        ];
    }
}
