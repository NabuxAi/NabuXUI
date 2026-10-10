{{--
    The App Launcher: the famous 3×3 waffle in a Lightning header opening
    the «All apps» panel — a live search, two app sections of colour tiles
    with initials, and the All Apps footer link. Escape and the waffle close
    it; the search keeps honest counts. The specimen row shows tile sizes
    and states.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $apps = [
        'section' => [$say('All items', 'همهٔ آیتم‌ها'), $say('Sales', 'فروش')],
        'items' => [
            ['n' => 'فروش', 'e' => 'Sales', 'i' => 'ف', 'ie' => 'S', 'tint' => '#0B5CAB'],
            ['n' => 'خدمات', 'e' => 'Service', 'i' => 'خ', 'ie' => 'S', 'tint' => '#0176D3'],
            ['n' => 'بازاریابی', 'e' => 'Marketing', 'i' => 'ب', 'ie' => 'M', 'tint' => '#B32D69'],
            ['n' => 'چَتِر', 'e' => 'Chatter', 'i' => 'چ', 'ie' => 'C', 'tint' => '#04844B'],
            ['n' => 'گزارش‌ها', 'e' => 'Reports', 'i' => 'گ', 'ie' => 'R', 'tint' => '#FE9339'],
            ['n' => 'داشبوردها', 'e' => 'Dashboards', 'i' => 'د', 'ie' => 'D', 'tint' => '#6739B7'],
            ['n' => 'کارها', 'e' => 'Tasks', 'i' => 'ک', 'ie' => 'T', 'tint' => '#0B827C'],
            ['n' => 'یادداشت‌ها', 'e' => 'Notes', 'i' => 'ی', 'ie' => 'N', 'tint' => '#BA0517'],
        ],
    ];
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;600;700&display=swap');
    .sla-root {
        --sla-blue: #0176D3; --sla-blue-soft: color-mix(in srgb, #0176D3 8%, #FFFFFF);
        --sla-navy: #0B5CAB; --sla-green: #04844B; --sla-purple: #6739B7; --sla-orange: #FE9339;
        --sla-text: #181818; --sla-weak: #444444; --sla-muted: #706E6B;
        --sla-border: #DDDBDA; --sla-bg: #F3F3F3; --sla-card: #FFFFFF;
        font-family: 'Source Sans 3', 'Inter', 'Vazirmatn', ui-sans-serif, system-ui, sans-serif;
        color: var(--sla-text);
        display: grid; gap: 1.5rem;
    }
    html[data-theme="dark"] .sla-root {
        --sla-blue: #0D9DDA; --sla-blue-soft: color-mix(in srgb, #0D9DDA 16%, #232323);
        --sla-navy: #0B5CAB; --sla-green: #0E9E5B; --sla-purple: #A57DE8; --sla-orange: #FE9339;
        --sla-text: #F3F3F3; --sla-weak: #CECECE; --sla-muted: #A5A5A5;
        --sla-border: #474747; --sla-bg: #181818; --sla-card: #232323;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .sla-root {
            --sla-blue: #0D9DDA; --sla-blue-soft: color-mix(in srgb, #0D9DDA 16%, #232323);
            --sla-navy: #0B5CAB; --sla-green: #0E9E5B; --sla-purple: #A57DE8; --sla-orange: #FE9339;
            --sla-text: #F3F3F3; --sla-weak: #CECECE; --sla-muted: #A5A5A5;
            --sla-border: #474747; --sla-bg: #181818; --sla-card: #232323;
        }
    }
    .sla-root, .sla-root *, .sla-root *::before, .sla-root *::after { box-sizing: border-box; }
    .sla-root :focus-visible { outline: 2px solid var(--sla-blue); outline-offset: 2px; }

    .sla-header { position: relative; inline-size: min(100%, 46rem); margin-inline: auto; display: flex; align-items: center;
                  gap: .75rem; padding: .55rem .75rem; border: 1px solid var(--sla-border); border-radius: .25rem .25rem 0 0;
                  border-block-end: 0; background: var(--sla-navy); color: #FFFFFF; }
    .sla-waffle { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2.5px; padding: .45rem; border: 0; border-radius: .25rem;
                  background: transparent; color: #FFFFFF; cursor: pointer; transition: background .15s ease; }
    .sla-waffle:hover { background: color-mix(in srgb, #FFFFFF 16%, transparent); }
    .sla-waffle i { inline-size: 3.5px; aspect-ratio: 1; border-radius: 50%; background: currentColor; }
    .sla-waffle[aria-expanded="true"] { background: color-mix(in srgb, #FFFFFF 22%, transparent); }
    .sla-breadcrumb { font-size: .85rem; opacity: .92; }
    .sla-search-head { margin-inline-start: auto; display: flex; align-items: center; gap: .4rem; font-size: .8rem; opacity: .9; }

    .sla-panel { position: relative; inline-size: min(100%, 46rem); margin-inline: auto; border: 1px solid var(--sla-border);
                 border-radius: 0 0 .25rem .25rem; background: var(--sla-card); overflow: clip; }
    .sla-search { display: flex; align-items: center; gap: .5rem; padding: .65rem .9rem; border-block-end: 1px solid var(--sla-border); }
    .sla-search input { flex: 1; min-inline-size: 0; padding: .45rem .6rem; border: 1px solid var(--sla-border); border-radius: .25rem;
                 background: var(--sla-card); color: inherit; font: inherit; font-size: .85rem; }
    .sla-search input:focus-visible { outline: 2px solid var(--sla-blue); outline-offset: 0; }
    .sla-search .nx-icon { color: var(--sla-muted); }
    .sla-body { max-block-size: 21rem; overflow-y: auto; padding: .35rem .9rem 0; }
    .sla-section h3 { margin: .6rem 0 .4rem; font-size: .8rem; font-weight: 700; color: var(--sla-muted);
                  display: flex; align-items: center; gap: .5rem; }
    .sla-section h3::after { content: ''; flex: 1; block-size: 1px; background: var(--sla-border); }
    .sla-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(6.5rem, 1fr)); gap: .4rem; padding-block-end: .6rem; }
    .sla-app { display: grid; justify-items: center; gap: .35rem; padding: .6rem .35rem; border: 0; border-radius: .25rem;
               background: transparent; color: inherit; font: inherit; cursor: pointer; transition: background .15s ease; }
    .sla-app:hover { background: var(--sla-blue-soft); }
    .sla-tile { display: grid; place-items: center; inline-size: 2.6rem; block-size: 2.6rem; border-radius: .25rem;
                background: var(--sla-tint, var(--sla-navy)); color: #FFFFFF; font-size: 1.15rem; font-weight: 700; }
    .sla-app span { max-inline-size: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: .76rem; font-weight: 600; }
    .sla-empty { padding: 1.4rem 0 1.6rem; text-align: center; color: var(--sla-muted); font-size: .85rem; }
    .sla-foot { display: flex; justify-content: center; border-block-start: 1px solid var(--sla-border); padding: .55rem; }
    .sla-foot button { border: 0; background: transparent; color: var(--sla-blue); font: inherit; font-size: .8rem;
                  font-weight: 600; cursor: pointer; text-decoration: none; }
    .sla-foot button:hover { text-decoration: underline; }

    .sla-spec { display: flex; flex-wrap: wrap; gap: 1.25rem; justify-content: center; align-items: flex-start; }
    .sla-spec-cell { display: grid; gap: .4rem; justify-items: center; }
    .sla-spec-cell > small { font-size: .72rem; color: var(--sla-muted); }
    .sla-spec-app { display: grid; justify-items: center; gap: .3rem; font-size: .76rem; font-weight: 600; }
    .sla-spec-tile-lg { inline-size: 3.4rem; block-size: 3.4rem; font-size: 1.5rem; }
    .sla-spec-tile-sm { inline-size: 1.6rem; block-size: 1.6rem; font-size: .7rem; }
    :where(.nx-js) .pg:has(.sla-root) .nx-data-table[data-nx-reveal] tbody tr { opacity: 1; translate: none; }
    @media (prefers-reduced-motion: reduce) {
        .sla-root * { transition-duration: .01ms !important; }
    }
</style>

<div class="sla-root"
    x-data="{
        open: true,
        q: '',
        apps: {{ json_encode($apps['items'], JSON_UNESCAPED_UNICODE) }},
        get t() { return document.documentElement.lang === 'fa' ? 'fa' : 'en' },
        label(a) { return this.t === 'fa' ? a.n : a.e },
        initial(a) { return this.t === 'fa' ? a.i : a.ie },
        get matches() { return this.apps.filter(a => this.label(a).includes(this.q.trim())) },
        toggle() { this.open = !this.open; if (this.open) this.$nextTick(() => this.$refs.q && this.$refs.q.focus()) },
    }"
    x-on:keydown.escape="open = false"
    x-on:click.outside="open = false">
    <section class="pg-box" style="justify-items: center; inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Nine dots, every app', 'نه نقطه، همهٔ اپ‌ها') }}</h3>
            <p style="margin: 0; max-width: 46ch; color: var(--nx-text-muted)">
                {{ $say('The waffle opens the panel over the header; the search filters the tiles as you type and the counts stay honest. Escape or a click outside closes it.', 'وافل، پنل را روی سربرگ باز می‌کند؛ جست‌وجو همان لحظه کاشی‌ها را فیلتر می‌کند و شمارنده‌ها صادق می‌مانند. Escape یا کلیک بیرون آن را می‌بندد.') }}
            </p>
        </div>

        <div>
            <div class="sla-header">
                <button type="button" class="sla-waffle" :aria-expanded="open"
                    aria-label="{{ $say('Open the app launcher', 'باز کردن لانچر اپ‌ها') }}" x-on:click="toggle()">
                    <i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i>
                </button>
                <span class="sla-breadcrumb">{{ $say('Sales / Opportunity: Nabu annual license', 'فروش / فرصت: لایسنس سالانهٔ نابو') }}</span>
                <span class="sla-search-head"><x-nx::icon name="search" /> {{ $say('Search…', 'جست‌وجو…') }}</span>
            </div>

            <div class="sla-panel" role="dialog" aria-label="{{ $say('App launcher', 'لانچر اپ‌ها') }}" x-show="open" x-cloak>
                <div class="sla-search">
                    <x-nx::icon name="search" />
                    <input type="search" x-ref="q" x-model="q" placeholder="{{ $say('Find an app…', 'یک اپ پیدا کنید…') }}"
                        aria-label="{{ $say('Search apps', 'جست‌وجوی اپ‌ها') }}">
                </div>
                <div class="sla-body">
                    <section class="sla-section">
                        <h3>{{ $say('All items', 'همهٔ آیتم‌ها') }} · <span x-text="matches.length"></span></h3>
                        <div class="sla-grid">
                            <template x-for="a in matches" :key="a.e">
                                <button type="button" class="sla-app" :style="'--sla-tint: ' + a.tint">
                                    <span class="sla-tile" aria-hidden="true" x-text="initial(a)"></span>
                                    <span x-text="label(a)"></span>
                                </button>
                            </template>
                        </div>
                        <p class="sla-empty" x-show="matches.length === 0" x-cloak>
                            {{ $say('No app matches — try “Sales” or “Service”.', 'اپی پیدا نشد — «فروش» یا «Services» را امتحان کنید.') }}
                        </p>
                    </section>
                </div>
                <div class="sla-foot">
                    <button type="button">{{ $say('All apps →', 'همهٔ اپ‌ها ←') }}</button>
                </div>
            </div>
        </div>
    </section>

    <section class="pg-box" style="justify-items: center; inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('Tile sizes and states', 'اندازه‌ها و حالت‌های کاشی') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('Large for the launcher grid, small for dense rails; the hover state lifts the tile onto the soft brand wash.', 'بزرگ برای گرید لانچر، کوچک برای ریل‌های متراکم؛ حالت hover کاشی را روی شستوی ملایم برند بالا می‌برد.') }}
            </p>
        </div>
        <div class="sla-spec" style="inline-size: 100%">
            <div class="sla-spec-cell">
                <span class="sla-app sla-spec-app"><span class="sla-tile sla-spec-tile-lg" style="--sla-tint: var(--sla-navy)" aria-hidden="true">{{ $fa ? 'ف' : 'S' }}</span></span>
                <small>large</small>
            </div>
            <div class="sla-spec-cell">
                <span class="sla-app sla-spec-app"><span class="sla-tile" style="--sla-tint: var(--sla-green)" aria-hidden="true">{{ $fa ? 'چ' : 'C' }}</span></span>
                <small>standard</small>
            </div>
            <div class="sla-spec-cell">
                <span class="sla-app sla-spec-app"><span class="sla-tile sla-spec-tile-sm" style="--sla-tint: var(--sla-purple)" aria-hidden="true">{{ $fa ? 'د' : 'D' }}</span></span>
                <small>small</small>
            </div>
            <div class="sla-spec-cell">
                <span class="sla-app sla-spec-app" style="background: var(--sla-blue-soft); border-radius: .25rem"><span class="sla-tile" style="--sla-tint: var(--sla-orange)" aria-hidden="true">{{ $fa ? 'گ' : 'R' }}</span></span>
                <small>hover</small>
            </div>
        </div>
    </section>
</div>
