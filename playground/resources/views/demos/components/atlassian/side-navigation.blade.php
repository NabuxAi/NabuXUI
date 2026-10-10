{{--
    Atlassian's side navigation as the skeleton of a real cloud app: a
    workspace switcher, collapsible sections with rotating chevrons, square
    icon tiles per project, counters, and the blue selected state that really
    retitles the content pane. A rail variant and a flat list sit below.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
    .atsn-root {
        --atsn-blue: #0052CC; --atsn-ink: #172B4D; --atsn-ink-strong: #091E42; --atsn-muted: #626F86;
        --atsn-subtle: #44546F; --atsn-border: #DFE1E6; --atsn-hover: #F1F2F4; --atsn-surface: #FFFFFF; --atsn-page: #F7F8F9;
        --atsn-tint: #E9F2FF;
        font-family: 'Inter', 'Vazirmatn', sans-serif; color: var(--atsn-ink);
    }
    html[data-theme="dark"] .atsn-root {
        --atsn-blue: #388BFF; --atsn-ink: #C7D1DB; --atsn-ink-strong: #E4EAF0; --atsn-muted: #8590A2;
        --atsn-subtle: #A9B8C4; --atsn-border: #2C3136; --atsn-hover: #1D2125; --atsn-surface: #161A1D; --atsn-page: #101214;
        --atsn-tint: #17263B;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .atsn-root {
            --atsn-blue: #388BFF; --atsn-ink: #C7D1DB; --atsn-ink-strong: #E4EAF0; --atsn-muted: #8590A2;
            --atsn-subtle: #A9B8C4; --atsn-border: #2C3136; --atsn-hover: #1D2125; --atsn-surface: #161A1D; --atsn-page: #101214;
            --atsn-tint: #17263B;
        }
    }
    .atsn-root :focus-visible { outline: 2px solid var(--atsn-blue); outline-offset: 2px; }

    .atsn-app { display: flex; flex-wrap: wrap; inline-size: min(100%, 36rem); margin-inline: auto; gap: .9rem; align-items: stretch; }
    .atsn-nav { flex: none; inline-size: 14rem; box-sizing: border-box; border: 1px solid var(--atsn-border); border-radius: 6px;
                background: var(--atsn-surface); padding: .6rem; display: grid; gap: .15rem; align-content: start; }
    .atsn-ws { display: flex; align-items: center; gap: .55rem; padding: .35rem .4rem; border-radius: 4px; border: none; background: none;
               cursor: pointer; text-align: start; inline-size: 100%; }
    .atsn-ws:hover { background: var(--atsn-hover); }
    .atsn-ws i { flex: none; display: grid; place-items: center; inline-size: 1.75rem; aspect-ratio: 1; border-radius: 4px;
                 background: linear-gradient(135deg, #0052CC, #0747A6); color: #fff; font: 700 .8rem Inter, Vazirmatn, system-ui; font-style: normal; }
    .atsn-ws span { min-inline-size: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font: 600 .8rem/1.3 Inter, Vazirmatn, system-ui; color: var(--atsn-ink-strong); }
    .atsn-ws small { display: block; font: 400 .68rem/1.3 Inter, Vazirmatn, system-ui; color: var(--atsn-muted); }
    .atsn-ws svg { flex: none; margin-inline-start: auto; stroke: var(--atsn-muted); fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

    .atsn-label { margin: .55rem .4rem .15rem; font: 700 .66rem/1.4 Inter, Vazirmatn, system-ui; letter-spacing: .5px; color: var(--atsn-muted); }
    .atsn-item { display: flex; align-items: center; gap: .55rem; inline-size: 100%; padding: .4rem .45rem; border: none; border-radius: 4px;
                 background: none; cursor: pointer; text-align: start; font: 500 .8rem/1.3 Inter, Vazirmatn, system-ui; color: var(--atsn-subtle);
                 transition: background .15s ease, color .15s ease; }
    .atsn-item:hover { background: var(--atsn-hover); color: var(--atsn-ink-strong); }
    .atsn-item[data-selected] { background: var(--atsn-tint); color: var(--atsn-blue); font-weight: 600; }
    .atsn-tile { flex: none; display: grid; place-items: center; inline-size: 1.25rem; aspect-ratio: 1; border-radius: 4px; color: #fff; }
    .atsn-tile svg { inline-size: .8rem; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .atsn-item > svg.chev { flex: none; inline-size: .85rem; stroke: var(--atsn-muted); fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
                            transition: rotate .2s ease; }
    .atsn-item[aria-expanded='true'] svg.chev { rotate: 90deg; }
    .atsn-count { margin-inline-start: auto; font: 600 .68rem Inter, Vazirmatn, system-ui; color: var(--atsn-muted); }
    .atsn-item[data-selected] .atsn-count { color: var(--atsn-blue); }

    .atsn-collapse { display: grid; grid-template-rows: 0fr; transition: grid-template-rows .3s cubic-bezier(.2, 0, 0, 1); }
    .atsn-collapse[data-open='true'] { grid-template-rows: 1fr; }
    .atsn-collapse > div { overflow: hidden; display: grid; gap: .15rem; }
    .atsn-collapse .atsn-item { padding-inline-start: .55rem; }

    .atsn-content { flex: 1 1 12rem; min-inline-size: 0; box-sizing: border-box; border: 1px solid var(--atsn-border); border-radius: 6px;
                    background: var(--atsn-page); padding: 1rem; display: grid; gap: .7rem; align-content: start; }
    .atsn-content h4 { margin: 0; font: 500 1.05rem/1.3 "Charlie Display", Inter, Vazirmatn, system-ui; color: var(--atsn-ink-strong); }
    .atsn-skel { block-size: 2.4rem; border: 1px solid var(--atsn-border); border-radius: 4px; background: var(--atsn-surface);
                 display: flex; align-items: center; gap: .55rem; padding-inline: .6rem; font: 400 .76rem/1.4 Inter, Vazirmatn, system-ui; color: var(--atsn-subtle); }
    .atsn-skel i { flex: none; inline-size: 1.1rem; aspect-ratio: 1; border-radius: 3px; background: var(--atsn-tint); }
    .atsn-skel b { margin-inline-start: auto; font-weight: 600; font-size: .68rem; color: var(--atsn-muted); }
    @media (max-width: 560px) {
        .atsn-content { display: none; }
        .atsn-nav { inline-size: 100%; }
    }

    .atsn-variants { display: flex; flex-wrap: wrap; gap: 1rem; justify-content: center; inline-size: 100%; }
    .atsn-rail { inline-size: 3.4rem; box-sizing: border-box; border: 1px solid var(--atsn-border); border-radius: 6px; background: var(--atsn-surface);
                 padding: .6rem .45rem; display: grid; gap: .45rem; justify-items: center; align-content: start; }
    .atsn-rail i { display: grid; place-items: center; inline-size: 2rem; aspect-ratio: 1; border-radius: 4px; color: var(--atsn-subtle); }
    .atsn-rail i[data-selected] { background: var(--atsn-tint); color: var(--atsn-blue); }
    .atsn-rail i svg { inline-size: 1.05rem; fill: none; stroke: currentColor; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
    .atsn-flat { inline-size: 12rem; box-sizing: border-box; border: 1px solid var(--atsn-border); border-radius: 6px; background: var(--atsp-surface, var(--atsn-surface));
                 padding: .6rem; display: grid; gap: .15rem; align-content: start; }
    .atsn-cap { font: 500 .72rem/1.4 Inter, Vazirmatn, system-ui; color: var(--nx-text-muted); display: grid; gap: .3rem; justify-items: center; text-align: center; }
    @media (prefers-reduced-motion: reduce) {
        .atsn-root * { animation-duration: .01ms !important; transition-duration: .01ms !important; }
    }

    /* Pin the page's «Important props» rows for full-page captures — ships
       with this partial only, so it stays scoped to this demo page. */
    .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr { opacity: 1; translate: none; }
</style>

<div class="atsn-root"
    x-data="{
        selected: 'assigned',
        open: { projects: true, filters: true },
        items: @js([
            'assigned' => $say('Assigned to me', 'واگذارشده به من'),
            'starred' => $say('Starred', 'ستاره‌دار'),
            'recent' => $say('Recently viewed', 'اخیراً دیده‌شده'),
            'platform' => $say('Nabu platform', 'نابو پلتفرم'),
            'mobile' => $say('Nabu mobile', 'نابو موبایل'),
            'web' => $say('Nabu web', 'نابو وب'),
            'mine' => $say('Only mine', 'مخصوص من'),
            'open' => $say('Unresolved', 'انجام‌نشده‌ها'),
        ]),
        counts: @js(['assigned' => $fa ? '۴' : '4', 'starred' => $fa ? '۲' : '2', 'mine' => $fa ? '۱۲' : '12']),
    }">
    <section class="pg-box" style="justify-items: center">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The skeleton every cloud app wears', 'اسکلتی که هر اپ ابری بر تن دارد') }}</h3>
            <p style="margin: 0; max-width: 50ch; color: var(--nx-text-muted)">
                {{ $say('Pick items and fold sections — the square icon tiles, the counters and the blue selection all keep working, and the pane retitles itself.', 'آیتم‌ها را انتخاب کنید و بخش‌ها را تا کنید — کاشی‌های آیکن مربعی، شمارنده‌ها و انتخاب آبی سرِ کارشان می‌مانند و قاب کناری خودش را بازنویسی می‌کند.') }}
            </p>
        </div>

        <div class="atsn-app">
            <nav class="atsn-nav" aria-label="{{ $say('Main navigation', 'ناوبری اصلی') }}">
                <button type="button" class="atsn-ws">
                    <i aria-hidden="true">{{ $fa ? 'ن' : 'N' }}</i>
                    <span><b style="font-weight: inherit">{{ $say('Nabu platform', 'نابو پلتفرم') }}</b><small>{{ $fa ? '۱۲ همکار' : '12 teammates' }}</small></span>
                    <svg viewBox="0 0 24 24" width="14" height="14" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
                </button>

                <p class="atsn-label">{{ $say('YOUR WORK', 'کارهای شما') }}</p>
                <button type="button" class="atsn-item" x-on:click="selected = 'assigned'" :data-selected="selected === 'assigned' ? '' : null">
                    <span class="atsn-tile" style="background: #0052CC" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 13h5l2 3h4l2-3h5"/><path d="M5 6h14l2 7v5H3v-5z"/></svg></span>
                    {{ $say('Assigned to me', 'واگذارشده به من') }}
                    <b class="atsn-count" x-text="counts.assigned">{{ $fa ? '۴' : '4' }}</b>
                </button>
                <button type="button" class="atsn-item" x-on:click="selected = 'starred'" :data-selected="selected === 'starred' ? '' : null">
                    <span class="atsn-tile" style="background: #B38600" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 4l2.5 5 5.5.8-4 3.9.9 5.3-4.9-2.6-4.9 2.6.9-5.3-4-3.9 5.5-.8z"/></svg></span>
                    {{ $say('Starred', 'ستاره‌دار') }}
                    <b class="atsn-count" x-text="counts.starred">{{ $fa ? '۲' : '2' }}</b>
                </button>
                <button type="button" class="atsn-item" x-on:click="selected = 'recent'" :data-selected="selected === 'recent' ? '' : null">
                    <span class="atsn-tile" style="background: #44546F" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="8"/><path d="M12 8v4l3 2"/></svg></span>
                    {{ $say('Recently viewed', 'اخیراً دیده‌شده') }}
                </button>

                <button type="button" class="atsn-item" x-on:click="open.projects = !open.projects" :aria-expanded="open.projects ? 'true' : 'false'">
                    <svg class="chev" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6l6 6-6 6"/></svg>
                    {{ $say('Projects', 'پروژه‌ها') }}
                </button>
                <div class="atsn-collapse" :data-open="open.projects ? 'true' : 'false'">
                    <div>
                        <button type="button" class="atsn-item" x-on:click="selected = 'platform'" :data-selected="selected === 'platform' ? '' : null">
                            <span class="atsn-tile" style="background: #0052CC" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="4" y="4" width="7" height="7" rx="1.5"/><rect x="13" y="4" width="7" height="7" rx="1.5"/><rect x="4" y="13" width="7" height="7" rx="1.5"/><rect x="13" y="13" width="7" height="7" rx="1.5"/></svg></span>
                            {{ $say('Nabu platform', 'نابو پلتفرم') }}
                        </button>
                        <button type="button" class="atsn-item" x-on:click="selected = 'mobile'" :data-selected="selected === 'mobile' ? '' : null">
                            <span class="atsn-tile" style="background: #1F845A" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="7" y="3" width="10" height="18" rx="2.5"/><path d="M11 17.5h2"/></svg></span>
                            {{ $say('Nabu mobile', 'نابو موبایل') }}
                        </button>
                        <button type="button" class="atsn-item" x-on:click="selected = 'web'" :data-selected="selected === 'web' ? '' : null">
                            <span class="atsn-tile" style="background: #6E5DC6" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="8.5"/><path d="M3.5 12h17M12 3.5c2.6 2.3 4 5.3 4 8.5s-1.4 6.2-4 8.5c-2.6-2.3-4-5.3-4-8.5s1.4-6.2 4-8.5z"/></svg></span>
                            {{ $say('Nabu web', 'نابو وب') }}
                        </button>
                    </div>
                </div>

                <button type="button" class="atsn-item" x-on:click="open.filters = !open.filters" :aria-expanded="open.filters ? 'true' : 'false'">
                    <svg class="chev" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6l6 6-6 6"/></svg>
                    {{ $say('Filters', 'فیلترها') }}
                </button>
                <div class="atsn-collapse" :data-open="open.filters ? 'true' : 'false'">
                    <div>
                        <button type="button" class="atsn-item" x-on:click="selected = 'mine'" :data-selected="selected === 'mine' ? '' : null">
                            {{ $say('Only mine', 'مخصوص من') }}
                            <b class="atsn-count" x-text="counts.mine">{{ $fa ? '۱۲' : '12' }}</b>
                        </button>
                        <button type="button" class="atsn-item" x-on:click="selected = 'open'" :data-selected="selected === 'open' ? '' : null">
                            {{ $say('Unresolved', 'انجام‌نشده‌ها') }}
                        </button>
                    </div>
                </div>
            </nav>

            <div class="atsn-content" aria-live="polite">
                <h4 x-text="items[selected]">{{ $say('Assigned to me', 'واگذارشده به من') }}</h4>
                <div class="atsk-row" style="display: grid; gap: .55rem">
                    <div class="atsn-skel"><i aria-hidden="true"></i>NABU-241 · {{ $say('Payment webhook', 'وب‌هوک پرداخت') }}<b>{{ $fa ? 'در جریان' : 'In progress' }}</b></div>
                    <div class="atsn-skel"><i aria-hidden="true"></i>NABU-247 · {{ $say('Search tokens', 'توکن‌های جست‌وجو') }}<b>{{ $fa ? 'بازبینی' : 'Review' }}</b></div>
                    <div class="atsn-skel"><i aria-hidden="true"></i>NABU-253 · {{ $say('Dark theme audit', 'ممیزی تم تیره') }}<b>{{ $fa ? 'عقب' : 'Backlog' }}</b></div>
                </div>
            </div>
        </div>
    </section>
</div>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('Rail and flat siblings', 'برادران ریل و فهرست‌تخت') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('Collapsed to a rail the same items keep their tiles and their blue; without sections the list reads like a quiet menu.', 'در حالت ریل همان آیتم‌ها کاشی و رنگ آبی‌شان را نگه می‌دارند؛ بدون بخش‌بندی، فهرست مثل یک منوی آرام خوانده می‌شود.') }}
        </p>
    </div>
    <div class="atsn-root" style="inline-size: 100%">
        <div class="atsn-variants">
            <div class="atsn-cap">
                <nav class="atsn-rail" aria-label="{{ $say('Collapsed rail', 'ریل جمع‌شده') }}">
                    <i aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="4" y="4" width="7" height="7" rx="1.5"/><rect x="13" y="4" width="7" height="7" rx="1.5"/><rect x="4" y="13" width="7" height="7" rx="1.5"/><rect x="13" y="13" width="7" height="7" rx="1.5"/></svg></i>
                    <i aria-hidden="true" data-selected><svg viewBox="0 0 24 24"><path d="M3 13h5l2 3h4l2-3h5"/><path d="M5 6h14l2 7v5H3v-5z"/></svg></i>
                    <i aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 4l2.5 5 5.5.8-4 3.9.9 5.3-4.9-2.6-4.9 2.6.9-5.3-4-3.9 5.5-.8z"/></svg></i>
                    <i aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h10"/></svg></i>
                </nav>
                <small>collapsed rail</small>
            </div>
            <div class="atsn-cap">
                <nav class="atsn-flat" aria-label="{{ $say('Flat list', 'فهرست تخت') }}">
                    <button type="button" class="atsn-item" data-selected>
                        <span class="atsn-tile" style="background: #0052CC" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 13h5l2 3h4l2-3h5"/><path d="M5 6h14l2 7v5H3v-5z"/></svg></span>
                        {{ $say('Assigned to me', 'واگذارشده به من') }}
                        <b class="atsn-count">{{ $fa ? '۴' : '4' }}</b>
                    </button>
                    <button type="button" class="atsn-item">
                        <span class="atsn-tile" style="background: #1F845A" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="7" y="3" width="10" height="18" rx="2.5"/><path d="M11 17.5h2"/></svg></span>
                        {{ $say('Nabu mobile', 'نابو موبایل') }}
                    </button>
                    <button type="button" class="atsn-item">
                        <span class="atsn-tile" style="background: #6E5DC6" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="8.5"/><path d="M3.5 12h17M12 3.5c2.6 2.3 4 5.3 4 8.5s-1.4 6.2-4 8.5c-2.6-2.3-4-5.3-4-8.5s1.4-6.2 4-8.5z"/></svg></span>
                        {{ $say('Nabu web', 'نابو وب') }}
                    </button>
                </nav>
                <small>{{ $say('flat list', 'فهرست تخت') }}</small>
            </div>
        </div>
    </div>
</section>
