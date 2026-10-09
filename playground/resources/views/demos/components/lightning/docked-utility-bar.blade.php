{{--
    The Docked Utility Bar docked inside a Lightning console frame: five
    utility icons with count badges, each popping its panel in place above
    the bar — chat, notifications, the online team, the file drawer and the
    mic — one panel at a time, the open icon lit with its white bar. A
    specimen row shows badge and active states.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $jsf = fn (string $s): string => str_replace('"', '&quot;', json_encode($s, JSON_UNESCAPED_UNICODE));
@endphp
<style>
    .sld-root {
        --sld-blue: #0176D3; --sld-blue-soft: color-mix(in srgb, #0176D3 8%, #FFFFFF);
        --sld-navy: #0B5CAB; --sld-green: #04844B; --sll-red: #BA0517; --sld-red: #BA0517;
        --sld-text: #181818; --sld-weak: #444444; --sld-muted: #706E6B;
        --sld-border: #DDDBDA; --sld-bg: #F3F3F3; --sld-card: #FFFFFF;
        font-family: 'Salesforce Sans', -apple-system, 'Segoe UI', Roboto, sans-serif;
        color: var(--sld-text);
        display: grid; gap: 1.5rem;
    }
    html[data-theme="dark"] .sld-root {
        --sld-blue: #0D9DDA; --sld-blue-soft: color-mix(in srgb, #0D9DDA 16%, #232323);
        --sld-navy: #0B5CAB; --sld-green: #0E9E5B; --sll-red: #FE5C4C; --sld-red: #FE5C4C;
        --sld-text: #F3F3F3; --sld-weak: #CECECE; --sld-muted: #A5A5A5;
        --sld-border: #474747; --sld-bg: #181818; --sld-card: #232323;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .sld-root {
            --sld-blue: #0D9DDA; --sld-blue-soft: color-mix(in srgb, #0D9DDA 16%, #232323);
            --sld-navy: #0B5CAB; --sld-green: #0E9E5B; --sll-red: #FE5C4C; --sld-red: #FE5C4C;
            --sld-text: #F3F3F3; --sld-weak: #CECECE; --sld-muted: #A5A5A5;
            --sld-border: #474747; --sld-bg: #181818; --sld-card: #232323;
        }
    }
    .sld-root, .sld-root *, .sld-root *::before, .sld-root *::after { box-sizing: border-box; }
    .sld-root :focus-visible { outline: 2px solid var(--sld-blue); outline-offset: 2px; }

    .sld-frame { position: relative; inline-size: min(100%, 46rem); margin-inline: auto; block-size: 27rem;
                 display: flex; flex-direction: column; overflow: clip; border: 1px solid var(--sld-border);
                 border-radius: .5rem; background: var(--sld-bg); }
    .sld-top { flex: none; display: flex; align-items: center; gap: .6rem; padding: .5rem .8rem;
               background: var(--sld-navy); color: #FFFFFF; font-size: .82rem; }
    .sld-top b { font-weight: 700; }
    .sld-top .nx-icon { inline-size: 1em; block-size: 1em; }
    .sld-body { flex: 1; overflow-y: auto; padding: .8rem; display: grid; gap: .5rem; align-content: start; }
    .sld-rec { display: flex; align-items: center; gap: .6rem; padding: .55rem .7rem; border: 1px solid var(--sld-border);
               border-radius: .25rem; background: var(--sld-card); }
    .sld-rec small { display: block; color: var(--sld-muted); font-size: .74rem; }
    .sld-rec b { font-size: .85rem; }
    .sld-badge-card { margin-inline-start: auto; font-size: .74rem; font-weight: 700; color: var(--sld-green); }
    .sld-panelzone { position: absolute; inset-inline: 0; inset-block-end: 2.75rem; z-index: 5;
                     display: flex; justify-content: center; padding: 0 .75rem; pointer-events: none; }
    .sld-panel { pointer-events: auto; inline-size: min(100%, 24rem); max-block-size: 17rem; display: flex; flex-direction: column;
                 border: 1px solid var(--sld-border); border-radius: .25rem .25rem 0 0;
                 border-block-end: 0; background: var(--sld-card); box-shadow: 0 -4px 14px rgba(0, 0, 0, .12);
                 overflow: hidden; animation: sld-rise .18s ease-out; }
    @keyframes sld-rise { from { translate: 0 6px; opacity: 0; } to { translate: 0 0; opacity: 1; } }
    .sld-panel-head { flex: none; display: flex; align-items: center; gap: .5rem; padding: .55rem .8rem;
                      border-block-end: 1px solid var(--sld-border); background: var(--sld-blue-soft); font-size: .82rem; font-weight: 700; }
    .sld-panel-head button { margin-inline-start: auto; border: 0; background: transparent; color: var(--sld-muted);
                      cursor: pointer; padding: .1rem .3rem; border-radius: .25rem; font-weight: 700; }
    .sld-panel-head button:hover { color: var(--sld-text); }
    .sld-panel-body { overflow-y: auto; padding: .6rem .8rem; font-size: .82rem; display: grid; gap: .55rem; align-content: start; }
    .sld-note { display: grid; gap: .1rem; padding-block-end: .55rem; border-block-end: 1px solid var(--sld-border); }
    .sld-note:last-child { border-block-end: 0; padding-block-end: 0; }
    .sld-note b { font-size: .8rem; }
    .sld-note span { color: var(--sld-muted); font-size: .76rem; }
    .sld-crew { display: flex; align-items: center; gap: .55rem; }
    .sld-ava { flex: none; display: grid; place-items: center; inline-size: 1.75rem; block-size: 1.75rem; border-radius: 50%;
               background: var(--sld-tint, var(--sld-navy)); color: #FFFFFF; font-size: .64rem; font-weight: 700; }
    .sld-dot { margin-inline-start: auto; inline-size: .5rem; block-size: .5rem; border-radius: 50%; background: var(--sld-green); }
    .sld-typing { display: flex; gap: .4rem; }
    .sld-typing input { flex: 1; min-inline-size: 0; padding: .4rem .55rem; border: 1px solid var(--sld-border);
               border-radius: .25rem; background: var(--sld-card); color: inherit; font: inherit; font-size: .8rem; }
    .sld-typing button { border: 0; border-radius: .25rem; background: var(--sld-blue); color: #FFFFFF; cursor: pointer;
               padding-inline: .7rem; font: inherit; font-size: .8rem; font-weight: 600; }

    .sld-bar { flex: none; display: flex; justify-content: center; gap: .15rem; block-size: 2.75rem;
               background: var(--sld-navy); color: #FFFFFF; }
    .sld-util { position: relative; display: grid; place-items: center; inline-size: 3.4rem; border: 0; background: transparent;
                color: #FFFFFF; cursor: pointer; transition: background .15s ease; }
    .sld-util:hover { background: color-mix(in srgb, #FFFFFF 14%, transparent); }
    .sld-util::after { content: ''; position: absolute; inset-inline: 15%; inset-block-end: 0; block-size: 3px;
                background: transparent; transition: background .15s ease; }
    .sld-util[aria-expanded="true"] { background: color-mix(in srgb, #FFFFFF 10%, transparent); }
    .sld-util[aria-expanded="true"]::after { background: #FFFFFF; }
    .sld-count { position: absolute; inset-block-start: .3rem; inset-inline-end: .45rem; min-inline-size: 1rem; padding-inline: .22rem;
                block-size: 1rem; display: grid; place-items: center; border-radius: .5rem; background: #EA001E;
                color: #FFFFFF; font-size: .6rem; font-weight: 700; box-shadow: 0 0 0 2px var(--sld-navy); }

    .sld-spec { display: flex; flex-wrap: wrap; gap: 1.25rem; justify-content: center; align-items: flex-start; }
    .sld-spec-card { inline-size: 3.4rem; block-size: 2.75rem; border-radius: .25rem; background: var(--sld-navy);
                color: #FFFFFF; display: grid; place-items: center; position: relative; }
    .sld-spec-cell { display: grid; gap: .4rem; justify-items: center; }
    .sld-spec-cell > small { font-size: .72rem; color: var(--sld-muted); }
    :where(.nx-js) .pg:has(.sld-root) .nx-data-table[data-nx-reveal] tbody tr { opacity: 1; translate: none; }
    @media (prefers-reduced-motion: reduce) {
        .sld-root * { animation-duration: .01ms !important; transition-duration: .01ms !important; }
    }
</style>

<div class="sld-root"
    x-data="{
        open: null,
        sent: [],
        crew: [
            { n: 'سارا', en: 'Sara', ini: 'سا', inie: 'SA', tint: '#0176D3', on: true },
            { n: 'رضا', en: 'Reza', ini: 'رک', inie: 'RK', tint: '#6739B7', on: true },
            { n: 'مریم', en: 'Maryam', ini: 'مر', inie: 'MR', tint: '#04844B', on: false },
        ],
        get t() { return document.documentElement.lang === 'fa' ? 'fa' : 'en' },
        toggle(id) { this.open = this.open === id ? null : id },
        send() {
            const el = this.$refs.chat;
            const v = el.value.trim();
            if (! v) return;
            this.sent.push(v); el.value = '';
        },
    }"
    x-on:keydown.escape="open = null"
    x-on:click.outside="open = null">
    <section class="pg-box" style="justify-items: center; inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The agent’s always-on toolbox', 'جعبه‌ابزارِ همیشه‌روشن کارشناس') }}</h3>
            <p style="margin: 0; max-width: 46ch; color: var(--nx-text-muted)">
                {{ $say('Five utilities docked to the console’s bottom edge; each click pops its panel in place and only one is open at a time — chat even sends.', 'پنج ابزار، چسبیده به لبهٔ پایین کنسول؛ هر کلیک پنلش را همان‌جا باز می‌کند و هر لحظه فقط یکی باز است — گفت‌وگو حتی ارسال هم دارد.') }}
            </p>
        </div>

        <div class="sld-frame">
            <div class="sld-top">
                <x-nx::icon name="grid" />
                <b>{{ $say('Sales Console', 'کنسول فروش') }}</b>
                <span style="opacity: .8">{{ $say('My open opportunities', 'فرصت‌های باز من') }}</span>
            </div>
            <div class="sld-body">
                <div class="sld-rec"><div><b>{{ $say('Nabu annual license', 'لایسنس سالانهٔ نابو') }}</b><small>{{ $say('Negotiation · ۲.۴B IRR', 'مذاکره · ۲٫۴ میلیارد ریال') }}</small></div><span class="sld-badge-card">{{ $say('Hot', 'داغ') }}</span></div>
                <div class="sld-rec"><div><b>{{ $say('Zagros factory rollout', 'استقرار کارخانه زاگرس') }}</b><small>{{ $say('Proposal · ۹۸۰M IRR', 'پیشنهاد · ۹۸۰ میلیون ریال') }}</small></div><span class="sld-badge-card">{{ $say('On track', 'طبق برنامه') }}</span></div>
                <div class="sld-rec"><div><b>{{ $say('Danial retail renewal', 'تمدید خرده‌فروشی دانیال') }}</b><small>{{ $say('Qualification · ۴۵۰M IRR', 'ارزیابی · ۴۵۰ میلیون ریال') }}</small></div><span class="sld-badge-card">{{ $say('At risk', 'در معرض خطر') }}</span></div>
                <div class="sld-rec"><div><b>{{ $say('Hara logistics pilot', 'پایلوت لجستیک هارا') }}</b><small>{{ $say('Discovery · ۱۲۰M IRR', 'کشف · ۱۲۰ میلیون ریال') }}</small></div><span class="sld-badge-card">{{ $say('New', 'تازه') }}</span></div>
            </div>

            <div class="sld-panelzone">
                <div class="sld-panel" role="dialog" aria-label="{{ $say('Chat with the deal team', 'گفت‌وگو با تیم معامله') }}"
                    x-show="open === 'chat'" x-cloak>
                    <div class="sld-panel-head"><x-nx::icon name="message" /> {{ $say('Deal team chat', 'گفت‌وگوی تیم معامله') }}
                        <button type="button" x-on:click="open = null" aria-label="{{ $say('Close', 'بستن') }}">✕</button></div>
                    <div class="sld-panel-body">
                        <p class="sld-note" style="margin: 0"><b>{{ $say('Sara', 'سارا') }}</b><span>{{ $say('Acme asked for the revised quote today.', 'آکمی امروز پیش‌فاکتور بازبینی‌شده را خواست.') }}</span></p>
                        <p class="sld-note" style="margin: 0"><b>{{ $say('Reza', 'رضا') }}</b><span>{{ $say('Pricing sheet is with legal now.', 'جدول قیمت الان دست حقوقی است.') }}</span></p>
                        <template x-for="(m, i) in sent" :key="i">
                            <p class="sld-note" style="margin: 0"><b>{{ $say('You', 'شما') }}</b><span x-text="m"></span></p>
                        </template>
                        <div class="sld-typing">
                            <input type="text" x-ref="chat" placeholder="{{ $say('Message the team…', 'به تیم پیام بدهید…') }}" x-on:keydown.enter="send()">
                            <button type="button" x-on:click="send()">{{ $say('Send', 'ارسال') }}</button>
                        </div>
                    </div>
                </div>

                <div class="sld-panel" role="dialog" aria-label="{{ $say('Notifications', 'اعلان‌ها') }}"
                    x-show="open === 'bell'" x-cloak>
                    <div class="sld-panel-head"><x-nx::icon name="bell" /> {{ $say('9 unread', '۹ ناخوانده') }}
                        <button type="button" x-on:click="open = null" aria-label="{{ $say('Close', 'بستن') }}">✕</button></div>
                    <div class="sld-panel-body">
                        <p class="sld-note" style="margin: 0"><b>{{ $say('Approval requested', 'درخواست تأیید') }}</b><span>{{ $say('۲.۴B discount needs a manager sign-off.', 'تخفیف ۲٫۴ میلیاردی امضای مدیر می‌خواهد.') }}</span></p>
                        <p class="sld-note" style="margin: 0"><b>{{ $say('Acme replied', 'آکمی پاسخ داد') }}</b><span>{{ $say('New comment on the quote — ۲ min ago.', 'کامنت تازه روی پیش‌فاکتور — ۲ دقیقه پیش.') }}</span></p>
                        <p class="sld-note" style="margin: 0"><b>{{ $say('Meeting moved', 'جلسه جابه‌جا شد') }}</b><span>{{ $say('Demo now Thursday ۱۰:۳۰.', 'دمو حالا پنجشنبه ۱۰:۳۰ است.') }}</span></p>
                    </div>
                </div>

                <div class="sld-panel" role="dialog" aria-label="{{ $say('Who’s online', 'چه کسی آنلاین است') }}"
                    x-show="open === 'users'" x-cloak>
                    <div class="sld-panel-head"><x-nx::icon name="users" /> {{ $say('Deal team — 2 online', 'تیم معامله — ۲ آنلاین') }}
                        <button type="button" x-on:click="open = null" aria-label="{{ $say('Close', 'بستن') }}">✕</button></div>
                    <div class="sld-panel-body">
                        <template x-for="p in crew" :key="p.en">
                            <div class="sld-crew">
                                <span class="sld-ava" :style="'--sld-tint: ' + p.tint" x-text="t === 'fa' ? p.ini : p.inie"></span>
                                <span x-text="t === 'fa' ? p.n : p.en"></span>
                                <span class="sld-dot" :style="p.on ? '' : 'background: var(--sld-muted)'" :aria-label="p.on ? {!! $jsf($say('away', 'دور')) !!} : {!! $jsf($say('online', 'آنلاین')) !!}"></span>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="sld-panel" role="dialog" aria-label="{{ $say('Files drawer', 'کشوی فایل‌ها') }}"
                    x-show="open === 'files'" x-cloak>
                    <div class="sld-panel-head"><x-nx::icon name="folder" /> {{ $say('Recent files', 'فایل‌های اخیر') }}
                        <button type="button" x-on:click="open = null" aria-label="{{ $say('Close', 'بستن') }}">✕</button></div>
                    <div class="sld-panel-body">
                        <p class="sld-note" style="margin: 0"><b>{{ $say('Quote-1404.pdf', 'پیش‌فاکتور-۱۴۰۴.pdf') }}</b><span>{{ $say('248 KB · shared with Acme', '۲۴۸ کیلوبایت · هم‌رسانده با آکمی') }}</span></p>
                        <p class="sld-note" style="margin: 0"><b>{{ $say('Pricing sheet Q4.xlsx', 'جدول قیمت پاییز.xlsx') }}</b><span>{{ $say('92 KB · edited by Reza', '۹۲ کیلوبایت · ویرایش رضا') }}</span></p>
                    </div>
                </div>

                <div class="sld-panel" role="dialog" aria-label="{{ $say('Voice note', 'یادداشت صوتی') }}"
                    x-show="open === 'mic'" x-cloak>
                    <div class="sld-panel-head"><x-nx::icon name="mic" /> {{ $say('Record a voice note', 'ضبط یادداشت صوتی') }}
                        <button type="button" x-on:click="open = null" aria-label="{{ $say('Close', 'بستن') }}">✕</button></div>
                    <div class="sld-panel-body">
                        <p style="margin: 0; color: var(--sld-muted)">{{ $say('Notes attach straight to «Nabu annual license» and land in the Chatter feed.', 'یادداشت‌ها مستقیم به «لایسنس سالانهٔ نابو» می‌چسبند و در فید چَتِر می‌افتند.') }}</p>
                    </div>
                </div>
            </div>

            <nav class="sld-bar" aria-label="{{ $say('Utility bar', 'نوار ابزار') }}">
                <button type="button" class="sld-util" :aria-expanded="open === 'chat'"
                    aria-label="{{ $say('Chat, 3 unread', 'گفت‌وگو، ۳ ناخوانده') }}" x-on:click="toggle('chat')">
                    <x-nx::icon name="message" /><span class="sld-count">{{ $fa ? '۳' : '3' }}</span>
                </button>
                <button type="button" class="sld-util" :aria-expanded="open === 'bell'"
                    aria-label="{{ $say('Notifications, 9 unread', 'اعلان‌ها، ۹ ناخوانده') }}" x-on:click="toggle('bell')">
                    <x-nx::icon name="bell" /><span class="sld-count">{{ $fa ? '۹' : '9' }}</span>
                </button>
                <button type="button" class="sld-util" :aria-expanded="open === 'users'"
                    aria-label="{{ $say('Who’s online', 'چه کسی آنلاین است') }}" x-on:click="toggle('users')">
                    <x-nx::icon name="users" />
                </button>
                <button type="button" class="sld-util" :aria-expanded="open === 'files'"
                    aria-label="{{ $say('Files', 'فایل‌ها') }}" x-on:click="toggle('files')">
                    <x-nx::icon name="folder" />
                </button>
                <button type="button" class="sld-util" :aria-expanded="open === 'mic'"
                    aria-label="{{ $say('Voice note', 'یادداشت صوتی') }}" x-on:click="toggle('mic')">
                    <x-nx::icon name="mic" />
                </button>
            </nav>
        </div>
    </section>

    <section class="pg-box" style="justify-items: center; inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('Badge and active states', 'نشان و حالت فعال') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('Counts clamp past nine, and the open utility keeps its white bar until the panel closes.', 'شمارنده‌ها بالای ۹ فشرده می‌شوند و ابزارِ باز، خط سفیدش را تا بسته‌شدن پنل نگه می‌دارد.') }}
            </p>
        </div>
        <div class="sld-spec" style="inline-size: 100%">
            <div class="sld-spec-cell">
                <span class="sld-spec-card" aria-hidden="true"><x-nx::icon name="bell" /><span class="sld-count">۹</span></span>
                <small>badge = ۹</small>
            </div>
            <div class="sld-spec-cell">
                <span class="sld-spec-card" aria-hidden="true"><x-nx::icon name="message" /><span class="sld-count">۹+</span></span>
                <small>badge = ۹+</small>
            </div>
            <div class="sld-spec-cell">
                <span class="sld-spec-card" data-open="true" style="background: color-mix(in srgb, #FFFFFF 12%, var(--sld-navy))" aria-hidden="true"><x-nx::icon name="users" /></span>
                <small>active</small>
            </div>
            <div class="sld-spec-cell">
                <span class="sld-spec-card" aria-hidden="true"><x-nx::icon name="mic" /></span>
                <small>idle</small>
            </div>
        </div>
    </section>
</div>
