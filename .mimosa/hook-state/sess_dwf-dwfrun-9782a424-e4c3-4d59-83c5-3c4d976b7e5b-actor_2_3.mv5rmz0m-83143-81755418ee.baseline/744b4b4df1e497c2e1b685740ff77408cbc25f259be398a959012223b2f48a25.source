{{--
    The search flow's real scenario: a Nordvana product finder — suggestions
    boil under the box while you type, the results page carries a left filter
    rail (categories, price cap, sort) that re-filters the same instant, the
    nothing-found state offers real way-outs, and clicking an item slides its
    details drawer in from the inline edge over a scrim. Alpine state only;
    the Escape listener tears itself down in destroy().
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp
<style>
    .flsq-root {
        --flsq-accent: #4f46e5; --flsq-accent-soft: #eef0fe; --flsq-on-accent: #fff;
        --flsq-text: #191b2a; --flsq-muted: #666b83;
        --flsq-border: #e4e6ef; --flsq-surface: #fff; --flsq-layer: #f5f6fb;
        --flsq-ok: #0f8a43; --flsq-ok-soft: #e7f6ec;
        display: grid; gap: 1.25rem; justify-items: center;
    }
    html[data-theme="dark"] .flsq-root {
        --flsq-accent: #8e94f8; --flsq-accent-soft: #262a4d; --flsq-on-accent: #14152b;
        --flsq-text: #ecedf6; --flsq-muted: #a6abc4;
        --flsq-border: #34364e; --flsq-surface: #1b1c2e; --flsq-layer: #222439;
        --flsq-ok: #5bd08d; --flsq-ok-soft: #1d3a2a;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .flsq-root {
            --flsq-accent: #8e94f8; --flsq-accent-soft: #262a4d; --flsq-on-accent: #14152b;
            --flsq-text: #ecedf6; --flsq-muted: #a6abc4;
            --flsq-border: #34364e; --flsq-surface: #1b1c2e; --flsq-layer: #222439;
            --flsq-ok: #5bd08d; --flsq-ok-soft: #1d3a2a;
        }
    }
    .flsq-shell { inline-size: min(100%, 52rem); display: grid; gap: 1rem; }
    .flsq-box { position: relative; }
    .flsq-input { inline-size: 100%; min-block-size: 3.1rem; padding-inline: 3rem;
                  border: 1px solid var(--flsq-border); border-radius: 999px; background: var(--flsq-surface);
                  color: var(--flsq-text); font: 500 .92rem / 1 inherit;
                  transition: border-color .18s ease, box-shadow .18s ease; }
    .flsq-input::placeholder { color: var(--flsq-muted); font-weight: 400; }
    .flsq-input::-webkit-search-cancel-button { display: none; }
    .flsq-input:focus { outline: none; border-color: var(--flsq-accent); box-shadow: 0 0 0 4px var(--flsq-accent-soft); }
    .flsq-icon { position: absolute; inset-inline-start: 1rem; inset-block-start: 50%; translate: 0 -50%;
                 color: var(--flsq-muted); pointer-events: none; }
    .flsq-icon svg { inline-size: 1.05rem; display: block; }
    .flsq-clear { position: absolute; inset-inline-end: .6rem; inset-block-start: 50%; translate: 0 -50%;
                  display: grid; place-items: center; inline-size: 2rem; aspect-ratio: 1; border: none;
                  border-radius: 50%; background: var(--flsq-layer); color: var(--flsq-muted); cursor: pointer; }
    .flsq-clear:hover { color: var(--flsq-text); }
    .flsq-clear svg { inline-size: .85rem; }
    .flsq-sugg { position: absolute; inset-inline: 0; inset-block-start: calc(100% + .4rem); z-index: 4; margin: 0;
                 padding: .35rem; list-style: none; border: 1px solid var(--flsq-border); border-radius: 1.1rem;
                 background: var(--flsq-surface); box-shadow: 0 16px 34px -18px rgb(20 22 48 / .35);
                 animation: flsq-drop .22s cubic-bezier(.2, 0, 0, 1); }
    @keyframes flsq-drop { from { opacity: 0; translate: 0 -6px; } }
    .flsq-sugg li + li { border-block-start: 1px solid var(--flsq-border); }
    .flsq-sugg button { display: grid; grid-template-columns: minmax(0, 1fr) auto; align-items: baseline; gap: .75rem;
                        inline-size: 100%; padding: .6rem .75rem; border: none; border-radius: .65rem;
                        background: transparent; color: var(--flsq-text); text-align: start; cursor: pointer; }
    .flsq-sugg button:hover, .flsq-sugg button:focus-visible { background: var(--flsq-layer); outline: none; }
    .flsq-sugg b { font-size: .88rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .flsq-sugg small { font-size: .74rem; color: var(--flsq-muted); font-variant-numeric: tabular-nums; }
    .flsq-home { display: grid; gap: 1.25rem; justify-items: center; padding-block: 1rem; }
    .flsq-trending { display: flex; flex-wrap: wrap; gap: .5rem; justify-content: center; align-items: center; }
    .flsq-trending > span { font-size: .8rem; color: var(--flsq-muted); }
    .flsq-pill { display: inline-flex; align-items: center; gap: .4rem; padding: .45rem 1rem; border: 1px solid var(--flsq-border);
                 border-radius: 999px; background: var(--flsq-surface); color: var(--flsq-text); font: 600 .8rem / 1 inherit;
                 cursor: pointer; transition: border-color .18s ease, background-color .18s ease; }
    .flsq-pill:hover { border-color: var(--flsq-accent); }
    .flsq-pill svg { inline-size: .8rem; color: var(--flsq-muted); }
    .flsq-pill:focus-visible { outline: 2px solid var(--flsq-accent); outline-offset: 2px; }
    .flsq-note { margin: 0; max-inline-size: 46ch; text-align: center; font-size: .82rem; color: var(--flsq-muted); }
    .flsq-body { position: relative; display: grid; grid-template-columns: 10.5rem minmax(0, 1fr); gap: 1rem; align-items: start; }
    .flsq-rail { display: grid; gap: .9rem; padding: 1.05rem; border: 1px solid var(--flsq-border);
                 border-radius: 1.2rem; background: var(--flsq-surface); }
    .flsq-rail h6 { margin: 0; font: 700 .78rem / 1.4 var(--nx-font-display, inherit); color: var(--flsq-muted);
                    text-transform: uppercase; letter-spacing: .05em; }
    .flsq-rail label { display: flex; align-items: center; gap: .5rem; font-size: .84rem; cursor: pointer; }
    .flsq-rail label small { margin-inline-start: auto; color: var(--flsq-muted); font-variant-numeric: tabular-nums; }
    .flsq-check { inline-size: 1.05rem; aspect-ratio: 1; accent-color: var(--flsq-accent); cursor: pointer; }
    .flsq-range { inline-size: 100%; accent-color: var(--flsq-accent); cursor: pointer; }
    .flsq-cap { display: flex; justify-content: space-between; font-size: .76rem; color: var(--flsq-muted);
                font-variant-numeric: tabular-nums; }
    .flsq-sort { display: grid; gap: .3rem; }
    .flsq-sort select { min-block-size: 2.3rem; padding-inline: .6rem; border: 1px solid var(--flsq-border);
                        border-radius: .65rem; background: var(--flsq-surface); color: var(--flsq-text); font: inherit; font-size: .82rem; }
    .flsq-head { display: flex; align-items: baseline; justify-content: space-between; gap: .75rem; margin-block-end: .15rem; }
    .flsq-head p { margin: 0; font-size: .84rem; color: var(--flsq-muted); font-variant-numeric: tabular-nums; }
    .flsq-back { border: none; background: none; padding: 0; color: var(--flsq-accent); font: 600 .78rem / 1 inherit;
                 cursor: pointer; display: inline-flex; align-items: center; gap: .3rem; }
    .flsq-back:hover { text-decoration: underline; }
    .flsq-back svg { inline-size: .85rem; }
    html[dir="rtl"] .flsq-back svg { rotate: 180deg; }
    .flsq-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(11rem, 1fr)); gap: .75rem; }
    .flsq-item { display: grid; gap: .45rem; padding: .9rem; border: 1px solid var(--flsq-border); border-radius: 1.1rem;
                 background: var(--flsq-surface); color: var(--flsq-text); text-align: start; cursor: pointer;
                 transition: border-color .18s ease, translate .18s ease, box-shadow .18s ease; }
    .flsq-item:hover { border-color: var(--flsq-accent); translate: 0 -2px; box-shadow: 0 10px 22px -14px rgb(20 22 48 / .4); }
    .flsq-item:focus-visible { outline: 2px solid var(--flsq-accent); outline-offset: 2px; }
    .flsq-thumb { display: grid; place-items: center; block-size: 4.6rem; border-radius: .8rem;
                  background: linear-gradient(140deg, var(--flsq-accent-soft), var(--flsq-layer));
                  color: var(--flsq-accent); font: 700 1.15rem / 1 var(--nx-font-display, inherit); }
    .flsq-item b { font-size: .88rem; overflow-wrap: anywhere; }
    .flsq-item .flsq-meta { display: flex; align-items: center; gap: .35rem; font-size: .74rem; color: var(--flsq-muted); }
    .flsq-item .flsq-meta svg { inline-size: .8rem; color: #eab308; }
    .flsq-item .flsq-meta b { color: var(--flsq-text); font-size: .74rem; }
    .flsq-item .flsq-price { font-weight: 700; font-size: .95rem; font-variant-numeric: tabular-nums; }
    .flsq-empty { display: grid; justify-items: center; gap: .9rem; padding: 2.25rem 1rem; border: 1px dashed var(--flsq-border);
                  border-radius: 1.2rem; background: var(--flsq-surface); text-align: center; }
    .flsq-empty-ic { display: grid; place-items: center; inline-size: 3.4rem; aspect-ratio: 1; border-radius: 50%;
                     background: var(--flsq-layer); color: var(--flsq-muted); }
    .flsq-empty-ic svg { inline-size: 1.5rem; }
    .flsq-empty p { margin: 0; color: var(--flsq-muted); font-size: .86rem; max-inline-size: 40ch; }
    .flsq-empty b { color: var(--flsq-text); overflow-wrap: anywhere; }
    .flsq-stage { position: relative; border-radius: 1.2rem; display: grid; }
    .flsq-scrim { position: absolute; inset: 0; z-index: 5; border-radius: 1.2rem; background: rgb(15 16 32 / .38);
                  opacity: 0; pointer-events: none; transition: opacity .28s ease; }
    .flsq-stage[data-open] .flsq-scrim { opacity: 1; pointer-events: auto; }
    .flsq-drawer { position: absolute; inset-block: 0; inset-inline-end: 0; z-index: 6; display: grid;
                   align-content: start; gap: .7rem; inline-size: min(100%, 20rem); padding: 1.2rem;
                   border-radius: 1.2rem; background: var(--flsq-surface); color: var(--flsq-text);
                   box-shadow: -14px 0 34px rgb(20 22 48 / .22); translate: 105% 0;
                   transition: translate .3s cubic-bezier(.2, 0, 0, 1), visibility .3s; overflow: auto; }
    .flsq-stage:not([data-open]) .flsq-drawer { visibility: hidden; }
    html[dir="rtl"] .flsq-drawer { box-shadow: 14px 0 34px rgb(20 22 48 / .22); translate: -105% 0; }
    .flsq-stage[data-open] .flsq-drawer, html[dir="rtl"] .flsq-stage[data-open] .flsq-drawer { translate: 0 0; }
    .flsq-drawer-x { position: absolute; inset-block-start: .8rem; inset-inline-end: .8rem; display: grid;
                     place-items: center; inline-size: 1.9rem; aspect-ratio: 1; border: none; border-radius: 50%;
                     background: var(--flsq-layer); color: var(--flsq-muted); cursor: pointer; }
    .flsq-drawer-x:hover { color: var(--flsq-text); }
    .flsq-drawer-x svg { inline-size: .9rem; }
    .flsq-drawer-hero { display: grid; place-items: center; block-size: 7.5rem; border-radius: 1rem;
                        background: linear-gradient(140deg, var(--flsq-accent-soft), var(--flsq-layer));
                        color: var(--flsq-accent); font: 700 2rem / 1 var(--nx-font-display, inherit); }
    .flsq-drawer h5 { margin: 0; font: 700 1.05rem / 1.4 var(--nx-font-display, inherit); }
    .flsq-drawer .flsq-meta { display: flex; align-items: center; gap: .4rem; font-size: .8rem; color: var(--flsq-muted); }
    .flsq-drawer .flsq-meta svg { inline-size: .9rem; color: #eab308; }
    .flsq-drawer dl { display: grid; margin: 0; font-size: .82rem; }
    .flsq-drawer dl > div { display: flex; justify-content: space-between; gap: 1rem; padding-block: .45rem;
                            border-block-start: 1px solid var(--flsq-border); }
    .flsq-drawer dt { color: var(--flsq-muted); }
    .flsq-drawer dd { margin: 0; font-weight: 600; font-variant-numeric: tabular-nums; }
    .flsq-drawer .flsq-price { font-weight: 700; font-size: 1.2rem; font-variant-numeric: tabular-nums; }
    .flsq-add { display: inline-flex; align-items: center; justify-content: center; gap: .5rem; min-block-size: 2.7rem;
                border: none; border-radius: 999px; background: var(--flsq-accent); color: var(--flsq-on-accent);
                font: 600 .88rem / 1 inherit; cursor: pointer; transition: filter .18s ease; }
    .flsq-add:hover { filter: brightness(1.08); }
    .flsq-add[data-added] { background: var(--flsq-ok); }
    .flsq-add svg { inline-size: 1rem; }
    .flsq-hint { margin: 0; max-inline-size: 60ch; text-align: center; font-size: .82rem; color: var(--nx-text-muted); }
    @media (max-width: 640px) {
        .flsq-body { grid-template-columns: 1fr; }
        .flsq-rail { order: 2; }
        .flsq-rail .flsq-checks { display: flex; flex-wrap: wrap; gap: .5rem .9rem; }
        .flsq-drawer { inline-size: 100%; border-start-start-radius: 0; border-start-end-radius: 0; }
    }
    @media (prefers-reduced-motion: reduce) {
        .flsq-root * { transition-duration: .01ms !important; animation-duration: .01ms !important; }
        .flsq-sugg { animation: none; }
    }
</style>

<div class="flsq-root"
    x-data="{
        fa: {{ $fa ? 'true' : 'false' }},
        q: '',
        view: 'home',
        detail: null,
        added: false,
        cats: { audio: true, desk: true, acc: true },
        max: 200,
        sort: 'relevance',
        items: {{ json_encode([
            ['id' => 'aurora', 'name' => $say('Aurora X2 headphones', 'هدفون Aurora X2'), 'cat' => 'audio', 'price' => 189, 'rating' => '4.8', 'meta' => $say('ANC · 30h battery', 'ANC · باتری ۳۰ ساعته')],
            ['id' => 'pulse', 'name' => $say('Pulse earbuds', 'ایرباد Pulse'), 'cat' => 'audio', 'price' => 99, 'rating' => '4.5', 'meta' => $say('Waterproof · 24h case', 'ضدآب · کیس ۲۴ ساعته')],
            ['id' => 'halo', 'name' => $say('Halo desk speaker', 'اسپیکر رومیزی Halo'), 'cat' => 'audio', 'price' => 129, 'rating' => '4.6', 'meta' => $say('Stereo pair · USB-C', 'جفت استریو · USB-C')],
            ['id' => 'nimbus', 'name' => $say('Nimbus K2 keyboard', 'کیبورد Nimbus K2'), 'cat' => 'desk', 'price' => 149, 'rating' => '4.7', 'meta' => $say('Tactile · wireless', 'تکتیکال · بی‌سیم')],
            ['id' => 'vela', 'name' => $say('Vela laptop stand', 'پایهٔ لپ‌تاپ Vela'), 'cat' => 'desk', 'price' => 59, 'rating' => '4.4', 'meta' => $say('Aluminium · folding', 'آلومینیومیِ تاشو')],
            ['id' => 'orbit', 'name' => $say('Orbit wireless mouse', 'ماوس بی‌سیم Orbit'), 'cat' => 'acc', 'price' => 45, 'rating' => '4.3', 'meta' => $say('Silent · compact', 'بی‌صدا · جمع‌وجور')],
            ['id' => 'atlas', 'name' => $say('Atlas monitor light', 'نور مانیتور Atlas'), 'cat' => 'acc', 'price' => 79, 'rating' => '4.6', 'meta' => $say('Auto-dim · bar', 'کم‌نورِ خودکار · میله‌ای')],
            ['id' => 'drift', 'name' => $say('Drift HD webcam', 'وب‌کم Drift HD'), 'cat' => 'acc', 'price' => 95, 'rating' => '4.2', 'meta' => $say('4K · privacy shutter', '۴K · پردهٔ حریم خصوصی')],
        ], JSON_UNESCAPED_UNICODE) }},
        catLabels: {{ json_encode([
            'audio' => $say('Audio', 'صدا'),
            'desk' => $say('Desk', 'میز'),
            'acc' => $say('Accessories', 'لوازم جانبی'),
        ], JSON_UNESCAPED_UNICODE) }},
        esc: null,
        init() {
            this.esc = (e) => { if (e.key === 'Escape') this.close(); };
            window.addEventListener('keydown', this.esc);
        },
        destroy() { window.removeEventListener('keydown', this.esc); },
        n(v) { const s = String(v); return this.fa ? s.replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]) : s; },
        catCount(c) { return this.items.filter((i) => i.cat === c).length; },
        get query() { return this.q.trim().toLowerCase(); },
        get suggestions() {
            if (!this.query) return [];
            return this.items.filter((i) => (i.name + ' ' + i.meta).toLowerCase().includes(this.query)).slice(0, 5);
        },
        get results() {
            let r = this.items.filter((i) => this.cats[i.cat] && i.price <= this.max
                && (i.name + ' ' + i.meta).toLowerCase().includes(this.query));
            if (this.sort === 'price-asc') r = [...r].sort((a, b) => a.price - b.price);
            if (this.sort === 'price-desc') r = [...r].sort((a, b) => b.price - a.price);
            if (this.sort === 'rating') r = [...r].sort((a, b) => b.rating - a.rating);
            return r;
        },
        get anyFilter() { return !this.cats.audio || !this.cats.desk || !this.cats.acc || this.max < 200; },
        search(term) { this.q = term ?? this.q; this.view = 'results'; },
        pick(item) { this.detail = item; this.added = false; },
        close() { this.detail = null; this.added = false; },
        add() {
            this.added = true;
            setTimeout(() => { this.added = false; }, 1600);
        },
        initials(name) { return name.split(/\s+/).slice(0, 2).map((w) => w[0]).join(''); },
    }">
    <section class="pg-box" style="gap: 1.25rem; justify-items: center">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Type one letter and the journey starts', 'یک حرف تایپ کنید و سفر شروع می‌شود') }}</h3>
            <p style="margin: 0; max-inline-size: 56ch; color: var(--nx-text-muted)">
                {{ $say('Suggestions boil under the box from the second character, the results page re-filters the instant the rail moves, the empty state hands you three real exits, and every item slides its own drawer in from the side — mirrored in Persian, closable by scrim, × or Escape.', 'از حرف دوم، پیشنهادها زیر خانه می‌جوشند، صفحهٔ نتایج با اولین حرکت ریل صافی می‌شود، حالت هیچ‌نتیجه سه راه خروج واقعی می‌دهد، و هر آیتم کشوی خودش را از کنار داخل می‌آورد — در فارسی آینه، و بستنش با پرده، × یا Escape.') }}
            </p>
        </div>

        <div class="flsq-shell">
            <div class="flsq-box">
                <span class="flsq-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                </span>
                <input type="search" class="flsq-input" x-model="q" role="combobox" aria-expanded="false"
                    :aria-expanded="view === 'home' && suggestions.length ? 'true' : 'false'"
                    aria-controls="flsq-sugg-list" aria-autocomplete="list"
                    placeholder="{{ $say('Search the store — try “Aurora” or “keyboard”', 'در فروشگاه بگردید — «Aurora» یا «کیبورد» را امتحان کنید') }}"
                    x-on:input="view === 'home' || (view = 'results'); detail = null"
                    x-on:keydown.enter.prevent="search()">
                <button type="button" class="flsq-clear" x-show="q" x-cloak aria-label="{{ $say('Clear search', 'پاک کردن جست‌وجو') }}"
                    x-on:click="q = ''; view = 'home'; detail = null">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg>
                </button>
                <ul class="flsq-sugg" id="flsq-sugg-list" role="listbox"
                    aria-label="{{ $say('Live suggestions', 'پیشنهادهای زنده') }}"
                    x-show="view === 'home' && suggestions.length" x-cloak>
                    <template x-for="s in suggestions" :key="s.id">
                        <li role="option" :aria-selected="false">
                            <button type="button" x-on:click="search(s.name)">
                                <b x-text="s.name"></b>
                                <small x-text="catLabels[s.cat] + ' · ' + n(s.price) + ' €'"></small>
                            </button>
                        </li>
                    </template>
                </ul>
            </div>

            <div class="flsq-home" x-show="view === 'home'">
                <div class="flsq-trending">
                    <span>{{ $say('Trending:', 'پرطرفدارها:') }}</span>
                    <template x-for="t in items.slice(0, 3)" :key="t.id">
                        <button type="button" class="flsq-pill" x-on:click="search(t.name)">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 17l6-6 4 4 8-8"/><path d="M14 7h7v7"/></svg>
                            <span x-text="t.name"></span>
                        </button>
                    </template>
                </div>
                <p class="flsq-note">{{ $say('Eight products, one catalog — everything you see below is filtered from this single list in your browser.', 'هشت محصول، یک کاتالوگ — همه‌چیز که پایین می‌بینید از همین فهرستِ واحد داخل مرورگر شما صافی می‌شود.') }}</p>
            </div>

            <div class="flsq-body" x-show="view === 'results'" x-cloak>
                <aside class="flsq-rail" aria-label="{{ $say('Filters', 'فیلترها') }}">
                    <div class="flsq-checks" style="display: grid; gap: .55rem">
                        <h6>{{ $say('Category', 'دسته') }}</h6>
                        <template x-for="(label, id) in catLabels" :key="id">
                            <label>
                                <input type="checkbox" class="flsq-check" x-model="cats[id]">
                                <span x-text="label"></span>
                                <small x-text="n(catCount(id))"></small>
                            </label>
                        </template>
                    </div>
                    <div>
                        <h6 style="margin-block-end: .45rem">{{ $say('Price cap', 'سقف قیمت') }}</h6>
                        <input type="range" class="flsq-range" min="40" max="200" step="5" x-model.number="max"
                            aria-label="{{ $say('Maximum price', 'بیشترین قیمت') }}">
                        <div class="flsq-cap">
                            <span>{{ $num('40') }} €</span>
                            <span><b x-text="n(max)"></b> €</span>
                        </div>
                    </div>
                    <div class="flsq-sort">
                        <h6>{{ $say('Sort', 'مرتب‌سازی') }}</h6>
                        <select x-model="sort">
                            <option value="relevance">{{ $say('Relevance', 'مرتبط‌ترین') }}</option>
                            <option value="price-asc">{{ $say('Price · low to high', 'قیمت · کم به زیاد') }}</option>
                            <option value="price-desc">{{ $say('Price · high to low', 'قیمت · زیاد به کم') }}</option>
                            <option value="rating">{{ $say('Top rated', 'بهترین امتیاز') }}</option>
                        </select>
                    </div>
                </aside>

                <div>
                    <div class="flsq-head">
                        <p>
                            <span x-text="n(results.length)"></span>
                            <span x-text="results.length === 1 ? '{{ $say('result', 'نتیجه') }}' : '{{ $say('results', 'نتیجه') }}'"></span>
                            <span x-show="query">— <b dir="auto" style="color: var(--flsq-text)" x-text="q"></b></span>
                        </p>
                        <button type="button" class="flsq-back" x-on:click="view = 'home'">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5m6-6l-6 6 6 6"/></svg>
                            {{ $say('Back to search', 'بازگشت به جست‌وجو') }}
                        </button>
                    </div>

                    <div class="flsq-grid" x-show="results.length">
                        <template x-for="i in results" :key="i.id">
                            <button type="button" class="flsq-item" x-on:click="pick(i)">
                                <span class="flsq-thumb" x-text="initials(i.name)" aria-hidden="true"></span>
                                <b x-text="i.name"></b>
                                <span class="flsq-meta">
                                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 3.6l2.5 5.2 5.7.7-4.2 3.9 1.1 5.6-5.1-2.8-5.1 2.8 1.1-5.6L3.8 9.5l5.7-.7z"/></svg>
                                    <b x-text="n(i.rating)"></b> · <span x-text="i.meta"></span>
                                </span>
                                <span class="flsq-price" x-text="n(i.price) + ' €'"></span>
                            </button>
                        </template>
                    </div>

                    <div class="flsq-empty" x-show="!results.length" x-cloak>
                        <span class="flsq-empty-ic" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5M8.5 11h5"/></svg>
                        </span>
                        <p>
                            {{ $say('Nothing matched', 'چیزی مطابق') }} <b x-text="q || '—'"></b>
                            {{ $say('with these filters.', 'با این فیلترها پیدا نشد.') }}
                            <span x-show="anyFilter" x-cloak>{{ $say('Loosen the rail — the price cap or a category may be holding everything back.', 'ریل را شل کنید — سقف قیمت یا یکی از دسته‌ها شاید همه را نگه داشته.') }}</span>
                        </p>
                        <div class="flsq-trending" style="justify-content: center">
                            <template x-for="t in items.slice(0, 3)" :key="t.id">
                                <button type="button" class="flsq-pill" x-on:click="q = t.name.split(' ')[0]">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 17l6-6 4 4 8-8"/><path d="M14 7h7v7"/></svg>
                                    <span x-text="t.name"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="flsq-stage" :data-open="detail ? '' : null">
                    <div class="flsq-scrim" x-on:click="close()" aria-hidden="true"></div>
                    <aside class="flsq-drawer" role="dialog" aria-modal="true"
                        :aria-label="detail ? detail.name : '{{ $say('Product details', 'جزئیات محصول') }}'">
                        <button type="button" class="flsq-drawer-x" aria-label="{{ $say('Close details', 'بستن جزئیات') }}" x-on:click="close()">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg>
                        </button>
                        <template x-if="detail">
                            <div style="display: grid; gap: .7rem">
                                <span class="flsq-drawer-hero" x-text="initials(detail.name)" aria-hidden="true"></span>
                                <h5 x-text="detail.name"></h5>
                                <span class="flsq-meta">
                                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 3.6l2.5 5.2 5.7.7-4.2 3.9 1.1 5.6-5.1-2.8-5.1 2.8 1.1-5.6L3.8 9.5l5.7-.7z"/></svg>
                                    <b x-text="n(detail.rating)"></b> · <span x-text="detail.meta"></span>
                                </span>
                                <dl>
                                    <div><dt>{{ $say('Category', 'دسته') }}</dt><dd x-text="catLabels[detail.cat]"></dd></div>
                                    <div><dt>{{ $say('Stock', 'موجودی') }}</dt><dd>{{ $say('In stock · ships from Dublin', 'موجود · ارسال از دوبلین') }}</dd></div>
                                    <div><dt>{{ $say('Delivery', 'تحویل') }}</dt><dd>{{ $say('2–4 days', '۲ تا ۴ روز') }}</dd></div>
                                </dl>
                                <span class="flsq-price" x-text="n(detail.price) + ' €'"></span>
                                <button type="button" class="flsq-add" :data-added="added ? '' : null" x-on:click="add()">
                                    <svg x-show="!added" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                                    <svg x-show="added" x-cloak viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4.5 12.5l5 5 10-11"/></svg>
                                    <span x-text="added ? '{{ $say('Added to cart', 'به سبد افزوده شد') }}' : '{{ $say('Add to cart', 'افزودن به سبد') }}'"></span>
                                </button>
                            </div>
                        </template>
                    </aside>
                </div>
            </div>
        </div>

        <p class="flsq-hint">{{ $say('Try typing “zzz” for the empty state, drag the price cap to 40 € with only Accessories checked, then open Aurora’s drawer and close it with Escape.', 'برای حالت هیچ‌نتیجه «zzz» را تایپ کنید، سقف قیمت را با تیکِ فقط لوازم جانبی تا ۴۰ یورو بکشید، بعد کشوی Aurora را باز کنید و با Escape ببندید.') }}</p>
    </section>
</div>
