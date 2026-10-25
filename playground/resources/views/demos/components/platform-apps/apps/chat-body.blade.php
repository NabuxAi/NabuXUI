{{--
    The chat app's shared body — included as the default slot of every
    <x-nx::app-window> on the chat demo page (all four platform skins).
    A deliberately light, static take with no nx-chat dependency: a
    conversation list + one message thread, held together by a few lines of
    local Alpine (current conversation, quick local echo of the composer).
    Sample data only, no backend. Scoped under .pch-*; tokens only, no Blade
    inside the style block.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $people = [
        'sara' => ['name' => $say('Sara', 'سارا'), 'hue' => 'var(--nx-violet-500)'],
        'navid' => ['name' => $say('Navid', 'نوید'), 'hue' => 'var(--nx-lapis-500)'],
        'minoo' => ['name' => $say('Minoo', 'مینو'), 'hue' => 'var(--nx-gold-500)'],
        'team' => ['name' => $say('Design team', 'تیم طراحی'), 'hue' => 'var(--nx-cyan-500)'],
    ];

    $chats = [
        ['id' => 'sara', 'last' => $say('Sent the deck — slide 9 is the one.', 'دک را فرستادم — اسلاید ۹ همان است.'), 'time' => $say('now', 'الان'), 'unread' => 2],
        ['id' => 'navid', 'last' => $say('Coffee tomorrow, 8?', 'فردا ساعت ۸ قهوه؟'), 'time' => $say('12m', '۱۲د'), 'unread' => 1],
        ['id' => 'minoo', 'last' => $say('The violet one. Definitely.', 'همین بنفشش. قطعاً.'), 'time' => $say('1h', '۱س'), 'unread' => 0],
        ['id' => 'team', 'last' => $say('Kian: pushed the fix ✅', 'کیان: فیکس پوش شد ✅'), 'time' => $say('3h', '۳س'), 'unread' => 0],
    ];

    // One short thread per person; shown when their conversation is current.
    $threads = [
        'sara' => [
            ['me' => false, 'text' => $say('Morning! Did the deck survive the review?', 'صبح بخیر! دک از جلسهٔ بازبینی سالم بیرون آمد؟')],
            ['me' => true, 'text' => $say('Mostly. They want the numbers bigger.', 'تقریباً. می‌خواهند اعداد بزرگ‌تر باشند.')],
            ['me' => false, 'text' => $say('Sent the deck — slide 9 is the one.', 'دک را فرستادم — اسلاید ۹ همان است.')],
        ],
        'navid' => [
            ['me' => false, 'text' => $say('Coffee tomorrow, 8?', 'فردا ساعت ۸ قهوه؟')],
            ['me' => true, 'text' => $say('Make it 8:30 and I am in.', '۸:۳۰ بگذار که بیایم.')],
        ],
        'minoo' => [
            ['me' => false, 'text' => $say('Blue or violet for the cover?', 'جلد آبی باشد یا بنفش؟')],
            ['me' => true, 'text' => $say('The violet one. Definitely.', 'همین بنفشش. قطعاً.')],
        ],
        'team' => [
            ['me' => false, 'text' => $say('Kian: pushed the fix ✅', 'کیان: فیکس پوش شد ✅')],
            ['me' => true, 'text' => $say('Nice — shipping tonight then.', 'عالی — پس امشب شروع می‌کنیم.')],
        ],
    ];
@endphp
<style>
    .pch-body { display: flex; flex-wrap: wrap; gap: .875rem; }
    .pch-list { flex: 1 1 8.5rem; display: grid; gap: .25rem; align-content: start; }
    .pch-chat { display: grid; grid-template-columns: auto 1fr; gap: .5rem .625rem; align-items: center;
                padding: .55rem .7rem; border: 0; border-radius: var(--nx-radius-md);
                background: transparent; color: var(--nx-text); text-align: start; cursor: pointer; font: inherit;
                transition-property: scale, background-color; transition-duration: var(--nx-dur-fast); }
    .pch-chat:hover { background: var(--nx-surface-2); }
    .pch-chat:active { scale: calc(1 - 0.04 * var(--nx-motion)); }
    .pch-chat:focus-visible { outline: 2px solid var(--nx-ring); outline-offset: 2px; }
    .pch-chat[aria-current='true'] { background: var(--nx-accent-soft); }
    .pch-avatar { grid-row: span 2; inline-size: 2rem; block-size: 2rem; border-radius: var(--nx-radius-full);
                  display: grid; place-items: center; color: var(--nx-accent-contrast);
                  font: 700 var(--nx-text-xs) / 1 var(--nx-font-sans); }
    .pch-chat-name { font: 600 var(--nx-text-sm) / 1.4 var(--nx-font-sans); }
    .pch-chat-last { display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 1; overflow: hidden;
                     font-size: var(--nx-text-xs); color: var(--nx-text-muted); }
    .pch-chat-meta { display: flex; gap: .4rem; align-items: center; justify-content: space-between;
                     font-size: var(--nx-text-xs); color: var(--nx-text-subtle); }
    .pch-dot { aspect-ratio: 1; inline-size: .45rem; border-radius: var(--nx-radius-full); background: var(--nx-accent); }
    .pch-thread { flex: 3 1 14rem; display: grid; gap: .75rem; align-content: start; }
    .pch-name { margin: 0; font: 700 var(--nx-text-sm) / 1.4 var(--nx-font-sans); color: var(--nx-text); }
    .pch-msgs { display: grid; gap: .5rem; padding: .75rem; border: 1px solid var(--nx-border);
                border-radius: var(--nx-radius-lg); background: var(--nx-surface-2); }
    .pch-msg { max-inline-size: 85%; padding: .5rem .7rem; border-radius: var(--nx-radius-lg);
               font: 400 var(--nx-text-sm) / 1.7 var(--nx-font-sans); }
    .pch-msg[data-side='in'] { justify-self: start; border-start-end-radius: var(--nx-radius-sm);
                               background: var(--nx-surface-3); color: var(--nx-text); }
    .pch-msg[data-side='out'] { justify-self: end; border-start-start-radius: var(--nx-radius-sm);
                                background: var(--nx-accent-soft); color: var(--nx-accent-text); }
    .pch-composer { display: flex; gap: .5rem; }
    .pch-input { flex: 1; min-inline-size: 0; padding: .5rem .75rem; border: 1px solid var(--nx-border);
                 border-radius: var(--nx-radius-full); background: var(--nx-surface); color: var(--nx-text);
                 font: 400 var(--nx-text-sm) / 1.4 var(--nx-font-sans); }
    .pch-input::placeholder { color: var(--nx-text-subtle); }
    .pch-input:focus-visible { outline: 2px solid var(--nx-ring); outline-offset: 1px; }
    .pch-send { flex: none; inline-size: 2.3rem; block-size: 2.3rem; display: grid; place-items: center;
                border: 1px solid var(--nx-accent-border); border-radius: var(--nx-radius-full);
                background: var(--nx-accent-soft); color: var(--nx-accent-text); cursor: pointer;
                transition-property: scale, background-color; transition-duration: var(--nx-dur-fast); }
    .pch-send:hover { background: var(--nx-accent); }
    .pch-send:active { scale: calc(1 - 0.04 * var(--nx-motion)); }
    .pch-send:focus-visible { outline: 2px solid var(--nx-ring); outline-offset: 2px; }
    .pch-send .nx-icon { inline-size: 1rem; block-size: 1rem; }
    .pch-echo { justify-self: end; max-inline-size: 85%; padding: .5rem .7rem; border-radius: var(--nx-radius-lg);
                border-start-start-radius: var(--nx-radius-sm); background: var(--nx-accent-soft);
                color: var(--nx-accent-text); font: 400 var(--nx-text-sm) / 1.7 var(--nx-font-sans); }
</style>
<div class="pch-body" x-data="{ current: 'sara', draft: '', echo: null }">
    <div class="pch-list" role="list" aria-label="{{ $say('Conversations', 'گفت‌وگوها') }}">
        @foreach ($chats as $chat)
            @php
                $person = $people[$chat['id']];
                $initial = mb_strtoupper(mb_substr($person['name'], 0, 1));
            @endphp
            <button type="button" class="pch-chat" role="listitem" @if ($loop->first) aria-current="true" @endif
                x-bind:aria-current="current === @js($chat['id']) ? 'true' : null" x-on:click="current = @js($chat['id'])">
                <span class="pch-avatar" aria-hidden="true" style="background: {{ $person['hue'] }}">{{ $initial }}</span>
                <span class="pch-chat-name">{{ $person['name'] }}</span>
                <span class="pch-chat-last">{{ $chat['last'] }}</span>
                <span class="pch-chat-meta">
                    <span>{{ $chat['time'] }}</span>
                    @if ($chat['unread'] > 0)
                        <span class="pch-dot" aria-hidden="true"></span>
                        <span class="nx-visually-hidden">{{ NabuXUI::formatNumber($chat['unread']).' '.$say('unread', 'خوانده‌نشده') }}</span>
                    @endif
                </span>
            </button>
        @endforeach
    </div>
    <div class="pch-thread">
        @foreach ($threads as $id => $messages)
            @php $person = $people[$id]; @endphp
            <div x-show="current === @js($id)" @if ($id !== 'sara') x-cloak @endif style="display: grid; gap: .75rem">
                <h4 class="pch-name">{{ $person['name'] }}</h4>
                <div class="pch-msgs" role="log" aria-live="polite" aria-label="{{ $person['name'] }}">
                    @foreach ($messages as $message)
                        <p class="pch-msg" data-side="{{ $message['me'] ? 'out' : 'in' }}">{{ $message['text'] }}</p>
                    @endforeach
                    <p class="pch-echo" x-show="current === @js($id) && echo !== null" x-cloak x-text="echo"></p>
                </div>
                <form class="pch-composer" x-on:submit.prevent="if (draft.trim()) { echo = draft.trim(); draft = '' }">
                    <input class="pch-input" type="text" x-model="draft"
                        placeholder="{{ $say('Message ', 'پیام به ') }}{{ $person['name'] }}"
                        aria-label="{{ $say('Message ', 'پیام به ') }}{{ $person['name'] }}">
                    <button type="submit" class="pch-send" aria-label="{{ $say('Send', 'فرستادن') }}">{{ NabuXUI::icon('zap') }}</button>
                </form>
            </div>
        @endforeach
    </div>
</div>
