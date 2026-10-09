{{--
    <x-nx::chat :conversations="[
        ['id' => 'sara', 'name' => 'سارا رضایی', 'status' => 'online', 'role' => 'طراح ارشد',
            'preview' => 'فایل نهایی را فرستادم', 'time' => now()->subMinutes(4), 'unread' => 2,
            'messages' => [
                ['id' => 'm1', 'side' => 'in', 'text' => 'سلام! نسخهٔ جدید کجاست؟', 'time' => now()->subHours(20)],
                ['id' => 'm2', 'side' => 'out', 'text' => 'همین حالا می‌فرستم', 'time' => now()->subMinutes(3)],
            ]],
    ]" active="sara" :typing="['sara' => true]" send-action="sendMessage" height="34rem" />

    Every message: ['id', 'side' => 'in|out', 'text', 'time' => Carbon|ISO|timestamp|label, 'day' => optional label].
    State is Alpine's: the search filter, the open thread (x-modelable="active" → wire:model binds it),
    and the send echo — a bubble is appended locally and nx-send {conversation, text} is dispatched;
    with send-action, $wire.sendAction(text, id) is called after the echo (the morph replaces the
    local node when your server render includes the message, so give it the same wire:key pattern).
    typing: raise/lower it from outside with $wire or JS via the component's setTyping(id, on).
--}}
@props(['conversations' => [], 'active' => null, 'typing' => [], 'sendAction' => null, 'height' => null, 'placeholder' => null, 'searchPlaceholder' => null, 'emptyText' => null, 'labels' => []])
@php
    use NabuXUI\NabuXUI;

    $locale = str_replace('_', '-', app()->getLocale());
    $lang = substr($locale, 0, 2);
    // The chat's own words live in the core i18n table (resources/lang, generated from it).
    $words = [
        'conversations' => __('nabuxui::ui.chatConversations', [], $lang),
        'messages' => __('nabuxui::ui.chatMessages', [], $lang),
        'placeholder' => __('nabuxui::ui.chatMessagePlaceholder', [], $lang),
        'typing' => __('nabuxui::ui.chatTyping', [], $lang),
        'unread' => __('nabuxui::ui.chatUnread', [], $lang),
        'empty' => __('nabuxui::ui.chatEmpty', [], $lang),
        'noMessages' => __('nabuxui::ui.chatNoMessages', [], $lang),
        'now' => __('nabuxui::ui.chatJustNow', [], $lang),
        'minute' => __('nabuxui::ui.chatMinutesAgo', [], $lang),
        'hour' => __('nabuxui::ui.chatHoursAgo', [], $lang),
        'day' => __('nabuxui::ui.chatDaysAgo', [], $lang),
    ];
    $words = array_merge($words, is_array($labels) ? $labels : []);
    $say = fn (string $key, array $swap = []) => strtr($words[$key], $swap);

    $statuses = ['online', 'busy', 'away', 'offline'];
    $fold = fn (string $text) => str_replace(
        ['ي', 'ى', 'ك'],
        ['ی', 'ی', 'ک'],
        mb_strtolower(trim($text)),
    );

    // A moment from anything the app might hand us: DateTime, timestamp, ISO — or null for a plain label.
    $moment = function ($time) {
        if ($time instanceof \DateTimeInterface) return $time->getTimestamp();
        if (is_numeric($time)) return (int) $time;
        if (is_string($time) && $time !== '' && ($parsed = strtotime($time)) !== false) return $parsed;
        return null;
    };

    $intl = class_exists(\IntlDateFormatter::class);
    $clockOf = function ($time) use ($moment, $locale, $intl) {
        $at = $moment($time);
        if ($at === null) return is_string($time) ? $time : null;
        if ($intl) {
            return (string) (new \IntlDateFormatter($locale, \IntlDateFormatter::NONE, \IntlDateFormatter::SHORT))->format($at);
        }
        return date('H:i', $at);
    };
    $dayLabel = function ($time) use ($moment, $locale, $intl) {
        $at = $moment($time);
        if ($at === null) return null;
        if ($intl) {
            return (string) (new \IntlDateFormatter($locale, \IntlDateFormatter::MEDIUM, \IntlDateFormatter::NONE))->format($at);
        }
        return date('M j, Y', $at);
    };
    // "۴ دقیقه پیش" for the conversations column; past a week the day itself says it better.
    $relative = function ($time) use ($moment, $dayLabel, $say, $locale) {
        $at = $moment($time);
        if ($at === null) return is_string($time) ? $time : null;
        $ago = max(0, time() - $at);
        return match (true) {
            $ago < 45 => $say('now'),
            $ago < 3600 => $say('minute', [':n' => NabuXUI::formatNumber((int) round($ago / 60), 0, $locale)]),
            $ago < 86400 => $say('hour', [':n' => NabuXUI::formatNumber((int) round($ago / 3600), 0, $locale)]),
            $ago < 604800 => $say('day', [':n' => NabuXUI::formatNumber((int) round($ago / 86400), 0, $locale)]),
            default => $dayLabel($time),
        };
    };

    $normalize = function (array $message) {
        return [
            'id' => (string) ($message['id'] ?? uniqid('m')),
            'side' => ($message['side'] ?? 'in') === 'out' ? 'out' : 'in',
            'text' => (string) ($message['text'] ?? ''),
            'time' => $message['time'] ?? null,
        ];
    };

    $conversations = array_values(array_map(function ($conversation) use ($statuses, $fold, $normalize) {
        $item = [
            'id' => (string) ($conversation['id'] ?? ''),
            'name' => (string) ($conversation['name'] ?? ''),
            'avatar' => isset($conversation['avatar']) ? (string) $conversation['avatar'] : null,
            'status' => in_array($conversation['status'] ?? null, $statuses, true) ? $conversation['status'] : null,
            'role' => isset($conversation['role']) ? (string) $conversation['role'] : null,
            'preview' => isset($conversation['preview']) ? (string) $conversation['preview'] : null,
            'time' => $conversation['time'] ?? null,
            'unread' => max(0, (int) ($conversation['unread'] ?? 0)),
            'messages' => [],
        ];
        foreach ((array) ($conversation['messages'] ?? []) as $message) {
            $item['messages'][] = $normalize((array) $message);
        }
        $item['search'] = $fold(($item['name'] ?? '').' '.($item['preview'] ?? ''));

        return $item;
    }, (array) $conversations));

    $active = (string) ($active !== null && $active !== '' ? $active : ($conversations[0]['id'] ?? ''));
    $typingMap = [];
    foreach ((array) $typing as $id => $on) {
        if ($on) $typingMap[(string) $id] = true;
    }
@endphp
<section {{ $attributes->class('nx-chat')->merge(['style' => $height ? "--nx-chat-height: {$height}" : null]) }}
    x-data="nxChat(@js(['texts' => array_column($conversations, 'search'), 'active' => $active, 'unread' => array_column($conversations, 'unread', 'id'), 'typing' => $typingMap, 'action' => $sendAction, 'locale' => $locale]))"
    x-modelable="active" aria-label="{{ $say('conversations') }}">
    <div class="nx-chat-frame">
        <aside class="nx-chat-side">
            <div class="nx-chat-search">
                {{ NabuXUI::icon('search') }}
                <input class="nx-chat-search-input" type="search" aria-label="{{ $searchPlaceholder ?? __('nabuxui::ui.search') }}"
                    placeholder="{{ $searchPlaceholder ?? __('nabuxui::ui.search') }}" autocomplete="off" x-model="query">
            </div>
            @if (count($conversations) === 0)
                <p class="nx-chat-empty">{{ $emptyText ?? $say('empty') }}</p>
            @else
                <ul class="nx-chat-list" role="listbox" aria-label="{{ $say('conversations') }}" x-on:keydown="listKey($event)">
                    @foreach ($conversations as $i => $conversation)
                        @php
                            $id = $conversation['id'];
                            $current = $id === $active;
                            $when = $relative($conversation['time']);
                            $at = $moment($conversation['time']);
                            $iso = $at === null ? null : date('c', $at);
                            $unreadShown = $conversation['unread'] > 0 && ! $current;
                        @endphp
                        <li role="presentation" wire:key="nx-chat-c-{{ $id }}" x-show="matches({{ $i }})">
                            <button type="button" class="nx-chat-conversation" role="option"
                                aria-selected="{{ $current ? 'true' : 'false' }}"
                                x-bind:aria-selected="active === @js($id) ? 'true' : 'false'"
                                aria-label="{{ $conversation['name'] }}{{ $unreadShown ? ' ('.NabuXUI::formatNumber($conversation['unread'], 0, $locale).' '.$say('unread').')' : '' }}"
                                x-bind:aria-label="unreadOf(@js($id)) > 0
                                    ? @js($conversation['name']) + ' (' + countOf(@js($id)) + ' ' + @js($say('unread')) + ')'
                                    : @js($conversation['name'])"
                                x-on:click="select(@js($id))">
                                <x-nx::avatar :name="$conversation['name']" :src="$conversation['avatar']" :status="$conversation['status']" />
                                <span class="nx-chat-cell">
                                    <span class="nx-chat-row">
                                        <span class="nx-chat-name">{{ $conversation['name'] }}</span>
                                        @if ($when !== null)<time class="nx-chat-time" @if ($iso) datetime="{{ $iso }}" @endif>{{ $when }}</time>@endif
                                    </span>
                                    <span class="nx-chat-row">
                                        <span class="nx-chat-preview">{{ $conversation['preview'] }}</span>
                                        @if ($conversation['unread'] > 0)
                                            <span class="nx-chat-unread" @if (! $current) x-show="unreadOf(@js($id)) > 0" @endif>{{ NabuXUI::formatNumber($conversation['unread'], 0, $locale) }}</span>
                                        @endif
                                    </span>
                                </span>
                            </button>
                        </li>
                    @endforeach
                    <li role="presentation" x-cloak x-show="count === 0">
                        <p class="nx-chat-empty">{{ __('nabuxui::ui.noResults') }}</p>
                    </li>
                </ul>
            @endif
        </aside>

        <div class="nx-chat-main">
            @foreach ($conversations as $conversation)
                @php
                    $id = $conversation['id'];
                    $current = $id === $active;
                    $isTyping = isset($typingMap[$id]);
                    $previous = null;
                @endphp
                <div class="nx-chat-panel" data-conversation="{{ $id }}" wire:key="nx-chat-p-{{ $id }}" @if (! $current) x-cloak @endif x-show="active === @js($id)">
                    <header class="nx-chat-head">
                        <x-nx::avatar :name="$conversation['name']" :src="$conversation['avatar']" :status="$conversation['status']" />
                        <div class="nx-chat-head-meta">
                            <span class="nx-chat-head-name">{{ $conversation['name'] }}</span>
                            @if ($conversation['role'])<span class="nx-chat-head-role">{{ $conversation['role'] }}</span>@endif
                        </div>
                    </header>
                    <div class="nx-chat-thread" role="log" aria-live="polite" aria-label="{{ $say('messages') }}">
                        @foreach ($conversation['messages'] as $message)
                            @php
                                $day = $dayLabel($message['time']);
                                $previousDay = $previous ? $dayLabel($previous['time']) : null;
                                $grouped = $previous && $previous['side'] === $message['side'] && $day === $previousDay;
                                $clock = $clockOf($message['time']);
                                $messageAt = $moment($message['time']);
                                $messageIso = $messageAt === null ? null : date('c', $messageAt);
                            @endphp
                            @if ($day !== null && $day !== $previousDay)
                                <div class="nx-chat-day" wire:key="nx-chat-d-{{ $id }}-{{ $message['id'] }}"><span>{{ $day }}</span></div>
                            @endif
                            <div class="nx-chat-message" data-side="{{ $message['side'] }}" @if ($grouped) data-grouped @endif wire:key="nx-chat-m-{{ $id }}-{{ $message['id'] }}">
                                <div class="nx-chat-bubble">{{ $message['text'] }}
                                    @if ($clock !== null)<time class="nx-chat-message-time" @if ($messageIso) datetime="{{ $messageIso }}" @endif>{{ $clock }}</time>@endif
                                </div>
                            </div>
                            @php
                                $previous = $message;
                            @endphp
                        @endforeach
                        <p class="nx-chat-typing" @if ($isTyping) data-open @else x-cloak @endif
                            x-bind:data-open="typing[@js($id)] ? '' : null">
                            <span class="nx-chat-typing-dots" aria-hidden="true"><i></i><i></i><i></i></span>
                            {{ $say('typing', [':name' => $conversation['name']]) }}
                        </p>
                        @if (count($conversation['messages']) === 0)
                            <p class="nx-chat-empty">{{ $say('noMessages') }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
            @if (count($conversations) === 0)
                <div class="nx-chat-panel"><p class="nx-chat-empty">{{ $emptyText ?? $say('empty') }}</p></div>
            @endif

            <form class="nx-chat-composer" x-on:submit.prevent="send()">
                <textarea class="nx-chat-input" rows="1" x-ref="input" x-model="draft" x-nx-autogrow
                    aria-label="{{ $placeholder ?? $say('placeholder') }}"
                    placeholder="{{ $placeholder ?? $say('placeholder') }}"
                    x-on:keydown="key($event)"></textarea>
                <button type="submit" class="nx-chat-send" aria-label="{{ __('nabuxui::ui.send') }}"
                    x-bind:data-ready="draft.trim() ? '' : null" x-bind:disabled="! draft.trim()">
                    {{ NabuXUI::icon('arrow-up') }}
                </button>
            </form>
        </div>
    </div>
</section>
