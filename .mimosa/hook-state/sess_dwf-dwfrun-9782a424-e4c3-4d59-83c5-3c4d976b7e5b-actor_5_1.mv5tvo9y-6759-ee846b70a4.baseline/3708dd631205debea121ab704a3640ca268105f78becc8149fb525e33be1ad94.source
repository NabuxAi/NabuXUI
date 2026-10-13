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

    بالای بلوک، نوار ابزار خودِ صفحه است: دو زبانهٔ «صندوق ورودی» و
    «بایگانی‌شده‌ها» (switchFolder)، دکمهٔ «خواندن همه» (markAllRead) و
    کنش بایگانی/بازگردانیِ پیامِ باز (archiveEmail/restoreEmail). سه
    رویداد مرورگری صفحه، وضعیتِ زندهٔ Alpine بلوک را همگام نگه می‌دارند —
    چون x-data بلوک با مورفِ Livewire دوباره ساخته نمی‌شود:

      nx-email-unread-cleared → unreadِ ردیف‌های پوشه را صفر می‌کند تا
        شمارنده‌های پوشه‌ها همان لحظه خالی شوند؛
      nx-email-moved → folderِ ردیفِ منتقل‌شده را عوض می‌کند تا پیامِ
        بایگانی‌شده بی‌درنگ از فهرست صندوق ورودی برود؛
      nx-email-folder → pick() بلوک را صدا می‌زند تا زبانه‌های نوار، ریل
        پوشه‌ها و Alpine یکی باشند.

    هشدار: هیچ data-nx-reveal / x-nx-reveal اضافه نشده — reveal با مورفِ
    Livewire محتوای صفحه را برای هم حذف می‌کند.
--}}
<x-filament-panels::page>
    <div class="nx-mailbar">
        <div class="nx-mailbar-tabs">
            <button type="button" class="nx-mailbar-tab" wire:key="nx-mailbar-tab-inbox"
                wire:click="switchFolder('inbox')"
                @if ($folder === 'inbox') aria-current="true" @endif>
                {{ \NabuXUI\NabuXUI::icon('mail') }}
                <span>صندوق ورودی</span>
                @if ($inboxUnread > 0)
                    <span class="nx-mailbar-count nx-mailbar-count--danger">{{ \NabuXUI\NabuXUI::formatNumber($inboxUnread, 0) }}</span>
                @endif
            </button>
            <button type="button" class="nx-mailbar-tab" wire:key="nx-mailbar-tab-archive"
                wire:click="switchFolder('archive')"
                @if ($folder === 'archive') aria-current="true" @endif>
                {{ \NabuXUI\NabuXUI::icon('folder') }}
                <span>بایگانی‌شده‌ها</span>
                <span class="nx-mailbar-count">{{ \NabuXUI\NabuXUI::formatNumber($archivedCount, 0) }}</span>
            </button>
        </div>

        <span class="nx-mailbar-spacer"></span>

        @if ($open !== '' && $openFolder === 'archive')
            <button type="button" class="nx-mailbar-action" wire:key="nx-mailbar-open-{{ $open }}-archive"
                wire:click="restoreEmail('{{ $open }}')"
                wire:loading.attr="disabled">
                {{ \NabuXUI\NabuXUI::icon('mail') }}
                <span>بازگردانی به صندوق ورودی</span>
            </button>
        @elseif ($open !== '' && $openFolder === 'inbox')
            <button type="button" class="nx-mailbar-action" wire:key="nx-mailbar-open-{{ $open }}-inbox"
                wire:click="archiveEmail('{{ $open }}')"
                wire:loading.attr="disabled">
                {{ \NabuXUI\NabuXUI::icon('folder') }}
                <span>بایگانی پیام</span>
            </button>
        @endif

        @if ($inboxUnread > 0)
            <button type="button" class="nx-mailbar-action nx-mailbar-action--primary" wire:key="nx-mailbar-read-all"
                wire:click="markAllRead"
                wire:loading.attr="disabled">
                {{ \NabuXUI\NabuXUI::icon('check-circle') }}
                <span>خواندن همه</span>
            </button>
        @endif
    </div>

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
        x-on:nx-email-unread-cleared.window="rows.forEach((row) => { if (row.folder === $event.detail.folder) row.unread = false })"
        x-on:nx-email-moved.window="(() => { const row = rowOf($event.detail.id); if (row) row.folder = $event.detail.folder })()"
        x-on:nx-email-folder.window="pick($event.detail.folder)"
    />

    <style>
    /* The page's own toolbar over the block: two folder tabs, the open
       message's archive/restore and «خواندن همه» — all on nx tokens so
       the light and dark themes both carry it. */
    .nx-mailbar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: var(--nx-space-2);
        margin-block-end: var(--nx-space-3);
    }
    .nx-mailbar-spacer { flex: 1 1 auto; }
    .nx-mailbar-tabs {
        display: inline-flex;
        gap: var(--nx-space-1);
        padding: var(--nx-space-1);
        border: 1px solid var(--nx-border);
        border-radius: var(--nx-radius-md);
        background: var(--nx-bg-subtle);
    }
    .nx-mailbar-tab {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.4rem 0.7rem;
        border: 0;
        border-radius: var(--nx-radius-sm);
        background: transparent;
        color: var(--nx-text-muted);
        font: 500 var(--nx-text-sm) / 1.4 var(--nx-font-sans);
        cursor: pointer;
        transition: background var(--nx-dur-fast) var(--nx-ease-out);
    }
    .nx-mailbar-tab:hover { background: var(--nx-accent-soft); }
    .nx-mailbar-tab[aria-current='true'] {
        background: var(--nx-accent-soft);
        color: var(--nx-accent-text);
        font-weight: 700;
    }
    .nx-mailbar-tab .nx-icon { width: 1rem; height: 1rem; }
    .nx-mailbar-count {
        min-inline-size: 1.35rem;
        padding: 0.1rem 0.35rem;
        border-radius: var(--nx-radius-full);
        background: color-mix(in oklab, currentColor 14%, transparent);
        font-size: var(--nx-text-xs, 0.75rem);
        font-variant-numeric: tabular-nums;
        text-align: center;
    }
    .nx-mailbar-count--danger {
        background: var(--nx-danger-soft);
        color: var(--nx-danger-text);
        font-weight: 700;
    }
    .nx-mailbar-action {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.45rem 0.8rem;
        border: 1px solid var(--nx-border);
        border-radius: var(--nx-radius-sm);
        background: var(--nx-bg-subtle);
        color: var(--nx-text);
        font: 600 var(--nx-text-sm) / 1.4 var(--nx-font-sans);
        cursor: pointer;
        transition: background var(--nx-dur-fast) var(--nx-ease-out), border-color var(--nx-dur-fast) var(--nx-ease-out);
    }
    .nx-mailbar-action:hover { border-color: var(--nx-accent-text); background: var(--nx-accent-soft); }
    .nx-mailbar-action:disabled { opacity: 0.6; cursor: default; }
    .nx-mailbar-action--primary {
        border-color: transparent;
        background: var(--nx-accent);
        color: #fff;
    }
    .nx-mailbar-action--primary:hover { background: var(--nx-accent-text); }
    .nx-mailbar-action .nx-icon { width: 1rem; height: 1rem; }
    </style>
</x-filament-panels::page>
