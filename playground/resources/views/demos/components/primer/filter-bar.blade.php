{{--
    GitHub's query-syntax filter bar: the input is the source of truth, the
    Label/Sort/State menus edit tokens inside it, and the tiny PR list below
    obeys the query live — with the classic FilterList as the variant.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (int|string $n): string => $fa ? strtr((string) $n, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : (string) $n;
    $oct = function (string $name): string {
        $p = [
            'search' => '<path d="M10.68 11.74a6 6 0 0 1-7.922-8.982 6 6 0 0 1 8.982 7.922l3.04 3.04a.749.749 0 0 1-.326 1.275.749.749 0 0 1-.734-.215ZM11.5 7a4.499 4.499 0 1 0-8.997 0A4.499 4.499 0 0 0 11.5 7Z"/>',
            'pr' => '<path fill-rule="evenodd" d="M1.5 3.25a2.25 2.25 0 1 1 3 2.122v5.256a2.251 2.251 0 1 1-1.5 0V5.372A2.25 2.25 0 0 1 1.5 3.25Zm5.677-.177L9.573.677A.25.25 0 0 1 10 .854V2.5h1A2.5 2.5 0 0 1 13.5 5v5.628a2.251 2.251 0 1 1-1.5 0V5a1 1 0 0 0-1-1h-1v1.646a.25.25 0 0 1-.427.177L7.177 3.427a.25.25 0 0 1 0-.354ZM3.75 2.5a.75.75 0 1 0 0 1.5.75.75 0 0 0 0-1.5Zm0 9.5a.75.75 0 1 0 0 1.5.75.75 0 0 0 0-1.5Zm8.25.75a.75.75 0 1 0 1.5 0 .75.75 0 0 0-1.5 0Z"/>',
            'merge' => '<path fill-rule="evenodd" d="M5.45 5.154A4.25 4.25 0 0 0 9.25 7.5h1.378a2.251 2.251 0 1 1 0 1.5H9.25A5.734 5.734 0 0 1 5 7.123v3.505a2.25 2.25 0 1 1-1.5 0V5.372a2.25 2.25 0 1 1 1.95-.218ZM4.25 13.5a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm8.5-4.5a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM5 3.25a.75.75 0 1 0-1.5 0 .75.75 0 0 0 1.5 0Z"/>',
            'closed' => '<path fill-rule="evenodd" d="M2.343 13.657A8 8 0 1 1 13.658 2.343 8 8 0 0 1 2.343 13.657ZM6.03 4.97a.751.751 0 0 0-1.042.018.751.751 0 0 0-.018 1.042L6.94 8 4.97 9.97a.749.749 0 0 0 .326 1.275.749.749 0 0 0 .734-.215L8 9.06l1.97 1.97a.749.749 0 0 0 1.275-.326.749.749 0 0 0-.215-.734L9.06 8l1.97-1.97a.749.749 0 0 0-.326-1.275.749.749 0 0 0-.734.215L8 6.94Z"/>',
            'chevron' => '<path d="M12.78 5.22a.749.749 0 0 1 0 1.06l-4.25 4.25a.749.749 0 0 1-1.06 0L3.22 6.28a.749.749 0 1 1 1.06-1.06L8 8.939l3.72-3.719a.749.749 0 0 1 1.06 0Z"/>',
        ];
        return '<svg aria-hidden="true" viewBox="0 0 16 16" width="16" height="16" fill="currentColor" style="display:block">' . ($p[$name] ?? '') . '</svg>';
    };
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap');
    .prf-root {
        --prf-canvas: #ffffff; --prf-subtle: #f6f8fa; --prf-fg: #1f2328; --prf-muted: #59636e;
        --prf-border: #d1d9e0; --prf-accent: #0969da;
        --prf-success: #1a7f37; --prf-done: #8250df; --prf-danger: #cf222e;
        font-family: 'Figtree', 'Inter', 'Vazirmatn', sans-serif;
        color: var(--prf-fg);
    }
    html[data-theme="dark"] .prf-root {
        --prf-canvas: #0d1117; --prf-subtle: #151b23; --prf-fg: #f0f6fc; --prf-muted: #9198a1;
        --prf-border: #3d444d; --prf-accent: #4493f8;
        --prf-success: #3fb950; --prf-done: #ab7df8; --prf-danger: #f85149;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .prf-root {
            --prf-canvas: #0d1117; --prf-subtle: #151b23; --prf-fg: #f0f6fc; --prf-muted: #9198a1;
            --prf-border: #3d444d; --prf-accent: #4493f8;
            --prf-success: #3fb950; --prf-done: #ab7df8; --prf-danger: #f85149;
        }
    }
    .prf-root :focus-visible { outline: 2px solid var(--prf-accent); outline-offset: 2px; border-radius: 6px; }

    .prf-panel { inline-size: min(100%, 44rem); margin-inline: auto; }
    .prf-line { display: flex; flex-wrap: wrap; gap: .5rem; align-items: center; }
    .prf-box { flex: 1 1 16rem; min-inline-size: 0; display: flex; align-items: center; gap: .45rem; block-size: 2rem; padding-inline: .55rem; border: 1px solid var(--prf-border); border-radius: 6px; background: var(--prf-canvas); }
    .prf-box:focus-within { border-color: var(--prf-accent); }
    .prf-box svg { flex: none; color: var(--prf-muted); }
    .prf-input { flex: 1; min-inline-size: 0; border: 0; background: transparent; color: var(--prf-fg); font: 400 .8125rem/1 ui-monospace, SFMono-Regular, Menlo, monospace; }
    .prf-input::placeholder { color: color-mix(in srgb, var(--prf-muted) 80%, transparent); }
    .prf-menu { position: relative; }
    .prf-menu > button { display: inline-flex; align-items: center; gap: .3rem; block-size: 2rem; padding-inline: .65rem; border: 1px solid var(--prf-border); border-radius: 6px; background: var(--prf-subtle); color: var(--prf-fg); font: 500 .75rem/1 inherit; cursor: pointer; white-space: nowrap; }
    .prf-menu > button:hover { background: color-mix(in srgb, var(--prf-border) 40%, var(--prf-subtle)); }
    .prf-menu[data-open="true"] > button { border-color: var(--prf-accent); color: var(--prf-accent); }
    .prf-drop { position: absolute; inset-block-start: calc(100% + .35rem); inset-inline-end: 0; z-index: 6; min-inline-size: 11rem; padding: .3rem; border: 1px solid var(--prf-border); border-radius: 6px; background: var(--prf-canvas); box-shadow: 0 8px 24px rgba(140, 149, 159, .2); }
    .prf-drop header { padding: .3rem .5rem .35rem; border-block-end: 1px solid var(--prf-border); margin-block-end: .2rem; font: 600 .6875rem/1.3 inherit; color: var(--prf-muted); }
    .prf-opt { display: flex; inline-size: 100%; align-items: center; gap: .4rem; padding: .35rem .5rem; border: 0; border-radius: 4px; background: none; color: var(--prf-fg); font: 400 .78rem/1.4 inherit; cursor: pointer; text-align: start; }
    .prf-opt:hover { background: var(--prf-accent); color: #fff; }
    .prf-opt code { font: 500 .7rem/1 ui-monospace, SFMono-Regular, Menlo, monospace; }
    .prf-opt[aria-checked="true"] { font-weight: 600; }
    .prf-result { margin: .5rem 0 .75rem; font: 400 .75rem/1.4 inherit; color: var(--prf-muted); }
    .prf-result b { color: var(--prf-fg); font-weight: 600; }
    .prf-list { border: 1px solid var(--prf-border); border-radius: 6px; background: var(--prf-canvas); }
    .prf-row { display: flex; flex-wrap: wrap; gap: .4rem .6rem; align-items: baseline; padding: .5rem .85rem; border-block-end: 1px solid var(--prf-border); }
    .prf-row:last-child { border-block-end: 0; }
    .prf-row svg { align-self: center; flex: none; }
    .prf-row[data-state="open"] svg { color: var(--prf-success); }
    .prf-row[data-state="merged"] svg { color: var(--prf-done); }
    .prf-row[data-state="closed"] svg { color: var(--prf-danger); }
    .prf-row b { font: 400 .875rem/1.45 inherit; overflow-wrap: anywhere; }
    .prf-row:hover b { color: var(--prf-accent); }
    .prf-row small { color: var(--prf-muted); font: 400 .71875rem/1.4 inherit; white-space: nowrap; }
    .prf-empty { padding: 1.25rem .85rem; font: 400 .8125rem/1.5 inherit; color: var(--prf-muted); text-align: center; }
    .prf-side { display: grid; gap: .2rem; inline-size: min(100%, 18rem); margin-inline: auto; }
    .prf-filter { display: flex; align-items: baseline; gap: .45rem; padding: .35rem .55rem; border-radius: 6px; color: var(--prf-muted); font: 400 .8125rem/1.4 inherit; text-decoration: none; }
    .prf-filter:hover { background: var(--prf-subtle); color: var(--prf-fg); }
    .prf-filter[aria-current="page"] { color: var(--prf-fg); font-weight: 600; }
    .prf-filter[aria-current="page"] .prf-count { background: var(--prf-accent); color: #fff; }
    .prf-count { display: inline-grid; place-items: center; min-inline-size: 1.35rem; padding-inline: .4rem; border-radius: 2em; background: color-mix(in srgb, var(--prf-border) 55%, transparent); font: 500 .75rem/1.4 inherit; }
    /* The shared demo template hides the props table's rows until it scrolls
       into view (data-nx-reveal on x-nx::data-table); a capture that never
       scrolls records an empty table. Pin this page's rows visible. */
    html:has(.prf-root) .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr { opacity: 1; translate: none; }
    @media (max-width: 480px) {
        .prf-menu { flex: 1 1 30%; }
        .prf-menu > button { inline-size: 100%; justify-content: center; }
        .prf-drop { inset-inline: 0; }
    }
    @media (prefers-reduced-motion: reduce) {
        .prf-root * { transition-duration: .01ms !important; }
    }
</style>

<div class="prf-root" x-data="{
        rtl: document.documentElement.dir === 'rtl',
        menu: null,
        q: '{{ $say('is:open is:pr label:bug', 'is:open is:pr label:باگ') }}',
        labels: ['{{ $say('bug', 'باگ') }}', '{{ $say('design', 'طراحی') }}', '{{ $say('enhancement', 'بهبود') }}'],
        rows: [
            { num: 415, state: 'open', title: { en: 'PDF export stalls on 50-page reports', fa: 'خروجی PDF در گزارش‌های ۵۰ صفحه‌ای گیر می‌کند' }, label: { en: 'bug', fa: 'باگ' }, comments: 6, fresh: 1 },
            { num: 409, state: 'open', title: { en: 'Refresh the report empty-state art', fa: 'تازه‌سازی تصویر حالت خالی گزارش‌ها' }, label: { en: 'design', fa: 'طراحی' }, comments: 3, fresh: 2 },
            { num: 402, state: 'merged', title: { en: 'Queue the heavy renders off-request', fa: 'رندرهای سنگین را خارج از درخواست صف کن' }, label: { en: 'enhancement', fa: 'بهبود' }, comments: 11, fresh: 3 },
            { num: 396, state: 'closed', title: { en: 'Drop the legacy CSV mapper', fa: 'حذف نگاشت‌گر قدیمی CSV' }, label: { en: 'enhancement', fa: 'بهبود' }, comments: 8, fresh: 4 },
        ],
        setToken(key, val) {
            const re = new RegExp('(^|\\s)' + key + ':[^\\s]+');
            this.q = re.test(this.q) ? this.q.replace(re, '$1' + key + ':' + val) : (this.q.trim() + ' ' + key + ':' + val).trim();
        },
        get tokens() {
            const grab = (k) => (this.q.match(new RegExp(k + ':([^\\s]+)')) || [])[1] || null;
            return { label: grab('label'), sort: grab('sort') || 'newest', open: /(^|\s)is:open(\s|$)/.test(this.q), closed: /(^|\s)is:closed(\s|$)/.test(this.q) };
        },
        get matches() {
            const t = this.tokens;
            const out = this.rows.filter((r) => (t.open && r.state === 'open') || (t.closed && r.state !== 'open') || (!t.open && !t.closed));
            if (t.label) { const want = t.label; return out.filter((r) => r.label.fa === want || r.label.en === want); }
            const by = { newest: (a, b) => a.fresh - b.fresh, oldest: (a, b) => b.fresh - a.fresh, commented: (a, b) => b.comments - a.comments };
            return out.slice().sort(by[t.sort] || by.newest);
        },
        num(n) { return this.rtl ? String(n).replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]) : String(n) },
        title(r) { return this.rtl ? r.title.fa : r.title.en },
        label(r) { return this.rtl ? r.label.fa : r.label.en },
    }"
    x-on:keydown.window="if ($event.key === '/' && $refs.qinput && ! $refs.qinput.contains(document.activeElement)) { $refs.qinput.focus(); $event.preventDefault() }">
    <section class="pg-box" style="gap: 1.25rem">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A query, not a form', 'یک کوئری، نه یک فرم') }}</h3>
            <p style="margin: 0; max-width: 48ch; color: var(--nx-text-muted)">
                {{ $say('The menus never open a modal — they edit tokens inside the quiet gray input, and the list below obeys instantly. Press «/» anywhere to jump into the box.', 'منوها هیچ مودالی باز نمی‌کنند — توکن‌های درون اینپوت خاکستری را ویرایش می‌کنند و فهرست پایین همان لحظه اطاعت می‌کند. هر جای صفحه «/» بزنید تا داخل جعبه بپرید.') }}
            </p>
        </div>

        <div class="prf-panel">
            <div class="prf-line">
                <label class="prf-box">
                    {!! $oct('search') !!}
                    <input class="prf-input" type="text" x-ref="qinput" x-model="q" spellcheck="false"
                        aria-label="{{ $say('Search pull requests', 'جست‌وجوی پول‌ریکوئست‌ها') }}"
                        placeholder="{{ $say('is:pr is:open label:bug', 'is:pr is:open label:باگ') }}">
                </label>

                <div class="prf-menu" x-data="{ }" :data-open="menu === 'label'">
                    <button type="button" x-on:click="menu = menu === 'label' ? null : 'label'" :aria-expanded="menu === 'label'">
                        {{ $say('Label', 'لیبل') }} {!! $oct('chevron') !!}
                    </button>
                    <div class="prf-drop" x-show="menu === 'label'" x-cloak x-on:click.outside="menu = null">
                        <header>{{ $say('Filter by label', 'فیلتر با لیبل') }}</header>
                        <template x-for="l in labels" :key="l">
                            <button type="button" class="prf-opt" role="menuitemradio" :aria-checked="tokens.label === l" x-on:click="setToken('label', l); menu = null">
                                <code x-text="'label:' + l"></code>
                            </button>
                        </template>
                    </div>
                </div>

                <div class="prf-menu" :data-open="menu === 'sort'">
                    <button type="button" x-on:click="menu = menu === 'sort' ? null : 'sort'" :aria-expanded="menu === 'sort'">
                        {{ $say('Sort', 'مرتب‌سازی') }} {!! $oct('chevron') !!}
                    </button>
                    <div class="prf-drop" x-show="menu === 'sort'" x-cloak x-on:click.outside="menu = null">
                        <header>{{ $say('Sort by', 'مرتب‌سازی بر اساس') }}</header>
                        <button type="button" class="prf-opt" role="menuitemradio" :aria-checked="tokens.sort === 'newest'" x-on:click="setToken('sort', 'newest'); menu = null"><code>sort:newest</code></button>
                        <button type="button" class="prf-opt" role="menuitemradio" :aria-checked="tokens.sort === 'oldest'" x-on:click="setToken('sort', 'oldest'); menu = null"><code>sort:oldest</code></button>
                        <button type="button" class="prf-opt" role="menuitemradio" :aria-checked="tokens.sort === 'commented'" x-on:click="setToken('sort', 'commented'); menu = null"><code>sort:commented</code></button>
                    </div>
                </div>

                <div class="prf-menu" :data-open="menu === 'state'">
                    <button type="button" x-on:click="menu = menu === 'state' ? null : 'state'" :aria-expanded="menu === 'state'">
                        {{ $say('State', 'وضعیت') }} {!! $oct('chevron') !!}
                    </button>
                    <div class="prf-drop" x-show="menu === 'state'" x-cloak x-on:click.outside="menu = null">
                        <header>{{ $say('Filter by state', 'فیلتر با وضعیت') }}</header>
                        <button type="button" class="prf-opt" role="menuitemradio" :aria-checked="tokens.open && ! tokens.closed" x-on:click="setToken('is', 'open'); menu = null"><code>is:open</code></button>
                        <button type="button" class="prf-opt" role="menuitemradio" :aria-checked="tokens.closed" x-on:click="setToken('is', 'closed'); menu = null"><code>is:closed</code></button>
                    </div>
                </div>
            </div>

            <p class="prf-result">
                <b x-text="num(matches.length)"></b>
                {{ $say('pull requests match this query', 'پول‌ریکوئست با این کوئری می‌خواند') }}
            </p>

            <div class="prf-list">
                <template x-for="r in matches" :key="r.num">
                    <a class="prf-row" href="#" :data-state="r.state" x-on:click.prevent style="text-decoration: none; color: inherit">
                        <span x-show="r.state === 'open'">{!! $oct('pr') !!}</span>
                        <span x-show="r.state === 'merged'" x-cloak>{!! $oct('merge') !!}</span>
                        <span x-show="r.state === 'closed'" x-cloak>{!! $oct('closed') !!}</span>
                        <b x-text="title(r)"></b>
                        <small x-text="'#' + num(r.num)"></small>
                        <small style="margin-inline-start: auto" x-text="'#' + label(r)"></small>
                    </a>
                </template>
                <p class="prf-empty" x-show="matches.length === 0" x-cloak>
                    {{ $say('No pull requests match — loosen a token and they come back.', 'هیچ پول‌ریکوئستی نمی‌خواند — یک توکن را شل کنید و برمی‌گردند.') }}
                </p>
            </div>
        </div>
    </section>
</div>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('Its quiet cousin: the FilterList', 'پسرخالهٔ آرامش: FilterList') }}</h3>
        <p style="margin: 0; max-width: 50ch; color: var(--nx-text-muted)">
            {{ $say('The same power as plain links with CounterLabels — the selected one goes bold with the accent counter, no input required.', 'همان قدرت به‌شکل لینک‌های ساده با شمارنده — انتخاب‌شده پررنگ می‌شود و شمارنده‌اش آبی؛ نه اینپوتی لازم است.') }}
        </p>
    </div>
    <nav class="prf-root prf-side" aria-label="{{ $say('Filter views', 'نمای‌های فیلتر') }}">
        <a class="prf-filter" href="#" x-on:click.prevent aria-current="page">
            <span>{{ $say('Everything', 'همه‌چیز') }}</span>
            <span class="prf-count" style="margin-inline-start: auto">{{ $num(4) }}</span>
        </a>
        <a class="prf-filter" href="#" x-on:click.prevent>
            <span>{{ $say('Open pull requests', 'پول‌ریکوئست‌های باز') }}</span>
            <span class="prf-count" style="margin-inline-start: auto">{{ $num(2) }}</span>
        </a>
        <a class="prf-filter" href="#" x-on:click.prevent>
            <span>{{ $say('Your pull requests', 'پول‌ریکوئست‌های شما') }}</span>
            <span class="prf-count" style="margin-inline-start: auto">{{ $num(1) }}</span>
        </a>
        <a class="prf-filter" href="#" x-on:click.prevent>
            <span>{{ $say('Everything mentioning you', 'هرچه نام شما را برد') }}</span>
            <span class="prf-count" style="margin-inline-start: auto">{{ $num(3) }}</span>
        </a>
    </nav>
</section>
