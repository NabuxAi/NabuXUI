{{--
    <x-nx::email :messages="[
        ['id' => 'm1', 'from' => ['name' => 'سارا رضایی', 'email' => 'sara@nabu.ai'], 'subject' => 'جلسهٔ فردا',
            'body' => "سلام؛\nقرار ساعت ۱۰ باشد؟", 'time' => now()->subMinutes(20), 'unread' => true, 'starred' => true],
        ['id' => 'm2', 'from' => ['name' => 'GitHub'], 'subject' => '[nabuxui] PR merged', 'folder' => 'archive'],
    ]" open="m1" folder="inbox" reply-action="sendReply" height="36rem" />

    Every message: ['id', 'from' => ['name', 'email', 'avatar'], 'to', 'subject', 'body', 'time', 'unread',
    'starred', 'folder']. The built-in folders are inbox/starred/sent/drafts/archive/trash ('starred' is
    virtual — it gathers starred messages); pass :folders="[['id' => 'team', 'label' => 'تیم', 'icon' =>
    'users']]" to replace them.

    State is Alpine's: the open folder and message (x-modelable="open" → wire:model binds the open
    message), read marking (opening a message folds its unread bar away), stars, and the reply
    composer. nx-folder / nx-open / nx-star / nx-reply events bubble for you to handle; with
    reply-action, $wire.replyAction(text, messageId) is called after the composer clears.
--}}
@props(['messages' => [], 'folders' => null, 'open' => null, 'folder' => null, 'replyAction' => null, 'height' => null, 'placeholder' => null, 'emptyText' => null, 'labels' => [], 'locale' => null, 'label' => null])
@php
    use NabuXUI\NabuXUI;

    $locale = str_replace('_', '-', $locale ?? app()->getLocale());
    $lang = substr($locale, 0, 2);
    // The mail client's own words live in the core i18n table (resources/lang, generated from it).
    $words = [
        'mail' => __('nabuxui::ui.emailMail', [], $lang),
        'folders' => __('nabuxui::ui.emailFolders', [], $lang),
        'messages' => __('nabuxui::ui.emailMessages', [], $lang),
        'empty' => __('nabuxui::ui.emailEmpty', [], $lang),
        'noMessage' => __('nabuxui::ui.emailNoMessage', [], $lang),
        'unread' => __('nabuxui::ui.emailUnread', [], $lang),
        'folderUnread' => __('nabuxui::ui.emailFolderUnread', [], $lang),
        'star' => __('nabuxui::ui.emailStar', [], $lang),
        'unstar' => __('nabuxui::ui.emailUnstar', [], $lang),
        'from' => __('nabuxui::ui.emailFrom', [], $lang),
        'to' => __('nabuxui::ui.emailTo', [], $lang),
        'replyTo' => __('nabuxui::ui.emailReplyTo', [], $lang),
        'placeholder' => __('nabuxui::ui.emailPlaceholder', [], $lang),
        'replySent' => __('nabuxui::ui.emailReplySent', [], $lang),
    ];
    $words = array_merge($words, is_array($labels) ? $labels : []);
    $say = fn (string $key, array $swap = []) => strtr($words[$key], $swap);

    // The folder rail: the six built-ins, or whatever the app hands over.
    $builtin = [
        ['id' => 'inbox', 'icon' => 'mail', 'key' => 'emailInbox'],
        ['id' => 'starred', 'icon' => 'star', 'key' => 'emailStarred'],
        ['id' => 'sent', 'icon' => 'arrow-right', 'key' => 'emailSent'],
        ['id' => 'drafts', 'icon' => 'edit', 'key' => 'emailDrafts'],
        ['id' => 'archive', 'icon' => 'folder', 'key' => 'emailArchive'],
        ['id' => 'trash', 'icon' => 'trash', 'key' => 'emailTrash'],
    ];
    $folders = $folders === null
        ? array_map(fn ($entry) => ['id' => $entry['id'], 'label' => __('nabuxui::ui.'.$entry['key'], [], $lang), 'icon' => $entry['icon']], $builtin)
        : array_values(array_map(function ($folder) {
            $folder = (array) $folder;
            $icon = isset($folder['icon']) && NabuXUI::hasIcon($folder['icon']) ? $folder['icon'] : 'folder';

            return ['id' => (string) ($folder['id'] ?? ''), 'label' => (string) ($folder['label'] ?? $folder['id'] ?? ''), 'icon' => $icon];
        }, (array) $folders));

    $messages = array_values(array_map(function ($message) {
        $message = (array) $message;
        $from = (array) ($message['from'] ?? []);

        return [
            'id' => (string) ($message['id'] ?? uniqid('m')),
            'from' => [
                'name' => (string) ($from['name'] ?? ''),
                'email' => isset($from['email']) ? (string) $from['email'] : null,
                'avatar' => isset($from['avatar']) ? (string) $from['avatar'] : null,
            ],
            'to' => isset($message['to']) ? (string) $message['to'] : null,
            'subject' => (string) ($message['subject'] ?? ''),
            'body' => isset($message['body']) ? (string) $message['body'] : '',
            'time' => $message['time'] ?? null,
            'unread' => ! empty($message['unread']),
            'starred' => ! empty($message['starred']),
            'folder' => (string) ($message['folder'] ?? 'inbox'),
        ];
    }, (array) $messages));

    $inFolder = fn (array $message, string $id) => $id === 'starred' ? $message['starred'] : $message['folder'] === $id;
    $countOf = function (string $id) use ($messages) {
        if ($id === 'starred') return count(array_filter($messages, fn ($message) => $message['starred']));

        return count(array_filter($messages, fn ($message) => $message['folder'] === $id && $message['unread']));
    };

    // A moment from anything the app might hand us: DateTime, timestamp, ISO — or null for a plain label.
    $moment = function ($time) {
        if ($time instanceof \DateTimeInterface) return $time->getTimestamp();
        if (is_numeric($time)) return (int) $time;
        if (is_string($time) && $time !== '' && ($parsed = strtotime($time)) !== false) return $parsed;

        return null;
    };

    $intl = class_exists(\IntlDateFormatter::class);
    $fmt = function (int $date, int $time, int $at) use ($intl, $locale) {
        if (! $intl) return null;

        return (string) (new \IntlDateFormatter($locale, $date, $time))->format($at);
    };
    // "10:24" today, a medium date any older day — for the list rows.
    $listWhen = function ($time) use ($moment, $fmt) {
        $at = $moment($time);
        if ($at === null) return is_string($time) && $time !== '' ? ['label' => $time, 'iso' => null] : null;
        $sameDay = date('Ymd', $at) === date('Ymd');
        $label = $fmt($sameDay ? \IntlDateFormatter::NONE : \IntlDateFormatter::MEDIUM, $sameDay ? \IntlDateFormatter::SHORT : \IntlDateFormatter::NONE, $at)
            ?? ($sameDay ? date('H:i', $at) : date('M j', $at));

        return ['label' => $label, 'iso' => date('c', $at)];
    };
    // The full moment — medium date, short time — for the reading pane.
    $fullWhen = function ($time) use ($moment, $fmt) {
        $at = $moment($time);
        if ($at === null) return is_string($time) && $time !== '' ? ['label' => $time, 'iso' => null] : null;

        return ['label' => $fmt(\IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT, $at) ?? date('M j, Y H:i', $at), 'iso' => date('c', $at)];
    };

    $snippetOf = function (string $body) {
        foreach (explode("\n", $body) as $line) {
            if (trim($line) !== '') return trim($line);
        }

        return null;
    };

    $folderIds = array_column($folders, 'id');
    $folder = (string) ($folder !== null && $folder !== '' && in_array($folder, $folderIds, true) ? $folder : ($folderIds[0] ?? 'inbox'));
    $open = (string) ($open !== null && $open !== '' ? $open : '');
    if ($open === '') {
        foreach ($messages as $message) {
            if ($inFolder($message, $folder)) {
                $open = $message['id'];
                break;
            }
        }
    }

    // The live state Alpine keeps: one row per message, everything else re-derives from it.
    $rows = array_map(fn ($message) => [
        'id' => $message['id'],
        'folder' => $message['folder'],
        'unread' => $message['unread'],
        'starred' => $message['starred'],
        'from' => $message['from']['name'],
        'subject' => $message['subject'],
    ], $messages);
    $openFromName = '';
    foreach ($messages as $message) {
        if ($message['id'] === $open) {
            $openFromName = $message['from']['name'];
            break;
        }
    }
@endphp
<section {{ $attributes->class('nx-email')->merge([
    'aria-label' => $label ?? $words['mail'],
    'data-nx-reveal' => '',
    'style' => $height ? "--nx-email-height: {$height}" : null,
]) }} x-data="nxEmail(@js(['rows' => $rows, 'folder' => $folder, 'open' => $open !== '' ? $open : null, 'action' => $replyAction, 'locale' => $locale, 'labels' => [
    'messages' => $words['messages'],
    'unread' => $words['unread'],
    'folderUnread' => $words['folderUnread'],
    'star' => $words['star'],
    'unstar' => $words['unstar'],
    'replyTo' => $words['replyTo'],
    'replySent' => $words['replySent'],
]]))" x-modelable="open">
    <div class="nx-email-frame">
        <nav class="nx-email-folders" aria-label="{{ $words['folders'] }}">
            <ul class="nx-email-folder-list">
                @foreach ($folders as $entry)
                    @php
                        $folderId = $entry['id'];
                        $count = $countOf($folderId);
                        $current = $folderId === $folder;
                    @endphp
                    <li wire:key="nx-email-f-{{ $folderId }}">
                        <button type="button" class="nx-email-folder" @if ($current) aria-current="true" @endif
                            x-bind:aria-current="folder === @js($folderId) ? 'true' : null"
                            x-on:click="pick(@js($folderId))">
                            {{ NabuXUI::icon($entry['icon']) }}
                            <span class="nx-email-folder-label">{{ $entry['label'] }}</span>
                            <span class="nx-email-folder-count" @if ($count === 0) hidden @endif
                                x-bind:hidden="unreadCount(@js($folderId)) === 0 ? '' : null"
                                x-text="countOf(@js($folderId))">{{ NabuXUI::formatNumber($count, 0, $locale) }}</span>
                            <span class="nx-visually-hidden" @if ($count === 0) hidden @endif
                                x-bind:hidden="unreadCount(@js($folderId)) === 0 ? '' : null"
                                x-text="unreadText(@js($folderId))">{{ str_replace(':count', NabuXUI::formatNumber($count, 0, $locale), $words['folderUnread']) }}</span>
                        </button>
                    </li>
                @endforeach
            </ul>
        </nav>

        <div class="nx-email-listcol">
            <ul class="nx-email-list" role="listbox" aria-label="{{ $words['messages'] }}" data-nx-reveal="group"
                x-data x-nx-reveal.group x-on:keydown="listKey($event)">
                @php
                    $visibleIndex = 0;
                @endphp
                @foreach ($messages as $message)
                    @php
                        $id = $message['id'];
                        $visible = $inFolder($message, $folder);
                        $slot = $visible ? $visibleIndex++ : 0;
                        $when = $listWhen($message['time']);
                        $snippet = $snippetOf($message['body']);
                        $starLabel = str_replace(':subject', $message['subject'], $message['starred'] ? $words['unstar'] : $words['star']);
                    @endphp
                    <li class="nx-email-item" role="presentation" wire:key="nx-email-r-{{ $id }}" style="--nx-i: {{ $slot }}"
                        @if (! $visible) hidden @endif
                        x-bind:hidden="inFolder(@js($id)) ? null : ''">
                        <button type="button" class="nx-email-row" role="option"
                            aria-selected="{{ $id === $open ? 'true' : 'false' }}"
                            x-bind:aria-selected="open === @js($id) ? 'true' : 'false'"
                            @if ($message['unread']) data-unread @endif
                            x-bind:data-unread="isUnread(@js($id)) ? '' : null"
                            x-on:click="openMsg(@js($id))">
                            <x-nx::avatar :name="$message['from']['name']" :src="$message['from']['avatar']" />
                            <span class="nx-email-cell">
                                <span class="nx-email-line">
                                    <span class="nx-email-from">{{ $message['from']['name'] }}</span>
                                    @if ($when !== null)
                                        <time class="nx-email-when" @if ($when['iso']) datetime="{{ $when['iso'] }}" @endif>{{ $when['label'] }}</time>
                                    @endif
                                </span>
                                <span class="nx-email-line">
                                    <span class="nx-email-subject">{{ $message['subject'] }}</span>
                                </span>
                                @if ($snippet !== null)<span class="nx-email-snippet">{{ $snippet }}</span>@endif
                            </span>
                            @if ($message['unread'])
                                <span class="nx-visually-hidden" x-show="isUnread(@js($id))">{{ $words['unread'] }}</span>
                            @endif
                        </button>
                        <button type="button" class="nx-email-star"
                            aria-pressed="{{ $message['starred'] ? 'true' : 'false' }}"
                            x-bind:aria-pressed="isStarred(@js($id)) ? 'true' : 'false'"
                            aria-label="{{ $starLabel }}"
                            x-bind:aria-label="starLabel(@js($id))"
                            x-on:click="star(@js($id))">
                            {{ NabuXUI::icon('star') }}
                        </button>
                    </li>
                @endforeach
                <li class="nx-email-empty" role="presentation" wire:key="nx-email-empty" hidden x-bind:hidden="anyVisible() ? '' : null">
                    <p>{{ $emptyText ?? $words['empty'] }}</p>
                </li>
            </ul>
        </div>

        <div class="nx-email-main">
            @foreach ($messages as $message)
                @php
                    $id = $message['id'];
                    $current = $id === $open;
                    $full = $fullWhen($message['time']);
                    $starLabel = str_replace(':subject', $message['subject'], $message['starred'] ? $words['unstar'] : $words['star']);
                    $lines = array_values(array_filter(array_map('trim', explode("\n", $message['body']))));
                @endphp
                <div class="nx-email-reading" wire:key="nx-email-p-{{ $id }}"
                    @if (! $current) x-cloak @endif x-show="open === @js($id)">
                    <header class="nx-email-head">
                        <div class="nx-email-head-main">
                            <h3 class="nx-email-subject-full">{{ $message['subject'] }}</h3>
                            <div class="nx-email-parties">
                                <x-nx::avatar :name="$message['from']['name']" :src="$message['from']['avatar']" size="sm" />
                                <div class="nx-email-party-group">
                                    <span class="nx-email-party">
                                        <span class="nx-email-role">{{ $words['from'] }}</span>
                                        <span class="nx-email-address">{{ $message['from']['name'] }}@if ($message['from']['email']) · {{ $message['from']['email'] }}@endif</span>
                                    </span>
                                    @if ($message['to'])
                                        <span class="nx-email-party">
                                            <span class="nx-email-role">{{ $words['to'] }}</span>
                                            <span class="nx-email-address">{{ $message['to'] }}</span>
                                        </span>
                                    @endif
                                    @if ($full !== null)
                                        <time class="nx-email-when-full" @if ($full['iso']) datetime="{{ $full['iso'] }}" @endif>{{ $full['label'] }}</time>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <button type="button" class="nx-email-star"
                            aria-pressed="{{ $message['starred'] ? 'true' : 'false' }}"
                            x-bind:aria-pressed="isStarred(@js($id)) ? 'true' : 'false'"
                            aria-label="{{ $starLabel }}"
                            x-bind:aria-label="starLabel(@js($id))"
                            x-on:click="star(@js($id))">
                            {{ NabuXUI::icon('star') }}
                        </button>
                    </header>
                    <div class="nx-email-body">
                        @foreach ($lines as $line)
                            <p wire:key="nx-email-b-{{ $id }}-{{ $loop->index }}">{{ $line }}</p>
                        @endforeach
                    </div>
                </div>
            @endforeach
            <div class="nx-email-noselect" @if ($open !== '') x-cloak @endif x-show="! open">
                <p>{{ $words['noMessage'] }}</p>
            </div>
            <form class="nx-email-composer" x-on:submit.prevent="send()">
                <span class="nx-email-reply">
                    {{ NabuXUI::icon('message') }}
                    <span x-text="replyLabel()">{{ $open !== '' ? str_replace(':name', $openFromName, $words['replyTo']) : $words['messages'] }}</span>
                </span>
                <div class="nx-email-compose">
                    <textarea class="nx-email-input" rows="1" x-ref="input" x-model="draft" x-nx-autogrow
                        aria-label="{{ $placeholder ?? $words['placeholder'] }}"
                        placeholder="{{ $placeholder ?? $words['placeholder'] }}"
                        x-bind:disabled="! open" x-on:keydown="key($event)"></textarea>
                    <button type="submit" class="nx-email-send" aria-label="{{ __('nabuxui::ui.send') }}"
                        x-bind:data-ready="draft.trim() && open ? '' : null"
                        x-bind:disabled="! draft.trim() || ! open">
                        {{ NabuXUI::icon('arrow-right') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    <p class="nx-visually-hidden" role="status" x-text="announce"></p>
</section>
