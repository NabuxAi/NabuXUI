{{--
    Polaris Filters as the orders grid toolbar: saved-view tabs preset the
    filters, the search and the filter popover feed removable chips, a
    one-click «Clear all filters» sweeps every chip, and the little order
    list underneath really live-filters with a live result count.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $stOpts = [
        ['k' => 'paid', 'l' => $say('Paid', 'پرداخت‌شده')],
        ['k' => 'pending', 'l' => $say('Pending', 'در انتظار')],
        ['k' => 'refunded', 'l' => $say('Refunded', 'مرجوع')],
    ];
    $chOpts = [
        ['k' => 'online', 'l' => $say('Online store', 'فروشگاه آنلاین')],
        ['k' => 'retail', 'l' => $say('In person', 'فروش حضوری')],
    ];
    $rows = [
        ['cust' => $say('Sara Lindqvist', 'سارا احمدی'), 'sum' => $say('$245.00', '۲٬۴۵۰٬۰۰۰ تومان'), 'st' => 'paid', 'ch' => 'online'],
        ['cust' => $say('Reza Kaviani', 'رضا کاویانی'), 'sum' => $say('$89.00', '۸۹۰٬۰۰۰ تومان'), 'st' => 'pending', 'ch' => 'online'],
        ['cust' => $say('Mahsa Karimi', 'مهسا کریمی'), 'sum' => $say('$125.00', '۱٬۲۵۰٬۰۰۰ تومان'), 'st' => 'paid', 'ch' => 'retail'],
        ['cust' => $say('Arash Samadi', 'آرش صمدی'), 'sum' => $say('$368.00', '۳٬۶۸۰٬۰۰۰ تومان'), 'st' => 'refunded', 'ch' => 'retail'],
        ['cust' => $say('Nora Keller', 'نگار کریمی'), 'sum' => $say('$64.00', '۶۴۰٬۰۰۰ تومان'), 'st' => 'paid', 'ch' => 'online'],
        ['cust' => $say('Bahram Nik', 'بهرام نیک'), 'sum' => $say('$110.00', '۱٬۱۰۰٬۰۰۰ تومان'), 'st' => 'pending', 'ch' => 'retail'],
    ];
    $stLabel = ['paid' => $say('Paid', 'پرداخت‌شده'), 'pending' => $say('Pending', 'در انتظار'), 'refunded' => $say('Refunded', 'مرجوع')];
    $views = [
        ['k' => 'all', 'l' => $say('All', 'همه')],
        ['k' => 'paid', 'l' => $say('Paid', 'پرداخت‌شده')],
        ['k' => 'refunded', 'l' => $say('Refunded', 'مرجوع')],
    ];
    $rowsJson = e(json_encode($rows));
    $stLabelJson = e(json_encode($stLabel));
    $stOptsJson = e(json_encode($stOpts));
    $chOptsJson = e(json_encode($chOpts));
    $viewsJson = e(json_encode($views));
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    [x-cloak] { display: none !important; }
    .plfl-root {
        --plfl-surface: #FFFFFF; --plfl-raised: #F6F6F6; --plfl-text: #303030; --plfl-subdued: #616161;
        --plfl-border: #E3E3E3; --plfl-strong: #8A8A8A; --plfl-hover: color-mix(in srgb, var(--plfl-text) 4%, var(--plfl-surface));
        --plfl-green: #008060; --plfl-on-green: #FFFFFF; --plfl-green-hover: #004C3F;
        --plfl-tint: color-mix(in srgb, var(--plfl-green) 9%, var(--plfl-surface));
        --plfl-critical: #D72C0D; --plfl-warn: #8A6116; --plfl-info: #2C6ECB; --plfl-focus: #005BD3;
        font-family: 'Inter', 'Vazirmatn', sans-serif;
        display: grid; gap: 1.5rem; justify-items: center;
    }
    html[data-theme="dark"] .plfl-root {
        --plfl-surface: #202020; --plfl-raised: #2B2B2B; --plfl-text: #F1F1F1; --plfl-subdued: #B5B5B5;
        --plfl-border: #454545; --plfl-strong: #8A8A8A;
        --plfl-green: #00A97F; --plfl-on-green: #08211A; --plfl-green-hover: #00BA93;
        --plfl-critical: #FF8D75; --plfl-warn: #FFC96B; --plfl-info: #6EA6FF;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .plfl-root {
            --plfl-surface: #202020; --plfl-raised: #2B2B2B; --plfl-text: #F1F1F1; --plfl-subdued: #B5B5B5;
            --plfl-border: #454545; --plfl-strong: #8A8A8A;
            --plfl-green: #00A97F; --plfl-on-green: #08211A; --plfl-green-hover: #00BA93;
            --plfl-critical: #FF8D75; --plfl-warn: #FFC96B; --plfl-info: #6EA6FF;
        }
    }
    .plfl-frame { inline-size: min(100%, 40rem); border: 1px solid var(--plfl-border); border-radius: 12px; background: var(--plfl-surface); box-shadow: 0 1px 4px rgba(0, 0, 0, .07); }
    .plfl-views { display: flex; gap: .25rem; padding: .4rem .6rem 0; border-block-end: 1px solid var(--plfl-border); overflow-x: auto; }
    .plfl-view { flex: none; padding: .55rem .8rem; border: none; border-block-end: 2px solid transparent; background: transparent; color: var(--plfl-subdued); cursor: pointer; font: 500 .8rem/1 Inter, system-ui, sans-serif; }
    .plfl-view:hover { color: var(--plfl-text); }
    .plfl-view[aria-selected="true"] { color: var(--plfl-text); border-block-end-color: var(--plfl-green); }
    .plfl-view:focus-visible, .plfl-bar button:focus-visible, .plfl-chip-x:focus-visible, .plfl-clear:focus-visible { outline: 2px solid var(--plfl-focus); outline-offset: 2px; }
    .plfl-bar { position: relative; display: flex; gap: .5rem; padding: .75rem .75rem .6rem; }
    .plfl-search { position: relative; flex: 1; min-inline-size: 0; }
    .plfl-search svg { position: absolute; inset-block-start: 50%; inset-inline-start: .65rem; translate: 0 -50%; color: var(--plfl-subdued); pointer-events: none; }
    .plfl-search input { inline-size: 100%; block-size: 2.25rem; padding-inline: 2.2rem .75rem; border: 1px solid var(--plfl-strong); border-radius: 8px; background: var(--plfl-surface); color: var(--plfl-text); font: 400 .85rem/1 Inter, system-ui, sans-serif; }
    .plfl-search input::placeholder { color: color-mix(in srgb, var(--plfl-subdued) 80%, transparent); }
    .plfl-search input:focus-visible { outline: 2px solid var(--plfl-focus); outline-offset: 1px; }
    .plfl-bar > button { flex: none; display: inline-flex; align-items: center; gap: .4rem; block-size: 2.25rem; padding-inline: .8rem; border: 1px solid var(--plfl-strong); border-radius: 8px; background: var(--plfl-surface); color: var(--plfl-text); cursor: pointer; font: 500 .8rem/1 Inter, system-ui, sans-serif; }
    .plfl-bar > button:hover { background: var(--plfl-hover); }
    .plfl-pop { position: absolute; inset-inline: .75rem; inset-block-start: calc(100% - .35rem); z-index: 7; display: grid; gap: .9rem; padding: .9rem; border: 1px solid var(--plfl-border); border-radius: 12px; background: var(--plfl-surface); box-shadow: 0 14px 34px -14px rgba(0, 0, 0, .35); }
    .plfl-pop b { font: 600 .78rem/1 Inter, system-ui, sans-serif; color: var(--plfl-subdued); }
    .plfl-opts { display: flex; flex-wrap: wrap; gap: .3rem 1rem; }
    .plfl-opts label { display: inline-flex; align-items: center; gap: .45rem; padding: .3rem .4rem; border-radius: 6px; font: 400 .82rem/1 Inter, system-ui, sans-serif; color: var(--plfl-text); cursor: pointer; }
    .plfl-opts label:hover { background: var(--plfl-hover); }
    .plfl-opts input { inline-size: 1rem; aspect-ratio: 1; accent-color: var(--plfl-green); }
    .plfl-pop-foot { display: flex; justify-content: flex-end; }
    .plfl-done { block-size: 2rem; padding-inline: .9rem; border: none; border-radius: 8px; background: var(--plfl-green); color: var(--plfl-on-green); cursor: pointer; font: 500 .8rem/1 Inter, system-ui, sans-serif; }
    .plfl-done:hover { background: var(--plfl-green-hover); }
    .plfl-chiprow { display: flex; flex-wrap: wrap; align-items: center; gap: .4rem; padding: 0 .75rem .7rem; }
    .plfl-chip { display: inline-flex; align-items: center; gap: .35rem; block-size: 1.7rem; padding-inline: .45rem .55rem; border: 1px solid var(--plfl-border); border-radius: 8px; background: var(--plfl-surface); font: 400 .76rem/1 Inter, system-ui, sans-serif; color: var(--plfl-text); }
    .plfl-chip[data-lock] { border-style: dashed; color: var(--plfl-subdued); }
    .plfl-chip-x { display: grid; place-items: center; inline-size: 1.15rem; aspect-ratio: 1; border: none; border-radius: 5px; background: transparent; color: var(--plfl-subdued); cursor: pointer; }
    .plfl-chip-x:hover { background: var(--plfl-hover); color: var(--plfl-text); }
    .plfl-clear { margin-inline-start: auto; border: none; background: transparent; color: var(--plfl-green); cursor: pointer; font: 500 .78rem/1 Inter, system-ui, sans-serif; }
    .plfl-clear:hover { text-decoration: underline; }
    .plfl-count { padding: 0 .95rem .25rem; font: 400 .76rem/1 Inter, system-ui, sans-serif; color: var(--plfl-subdued); font-variant-numeric: tabular-nums; }
    .plfl-list { display: grid; }
    .plfl-row { display: flex; align-items: center; gap: .6rem; padding: .55rem .95rem; }
    .plfl-row + .plfl-row { border-block-start: 1px solid var(--plfl-border); }
    .plfl-row b { font: 500 .82rem/1.3 Inter, system-ui, sans-serif; color: var(--plfl-text); }
    .plfl-row small { font: 400 .74rem/1.3 Inter, system-ui, sans-serif; color: var(--plfl-subdued); font-variant-numeric: tabular-nums; }
    .plfl-row .plfl-chip { margin-inline-start: auto; }
    .plfl-none { padding: 1.2rem .95rem; text-align: center; font: 400 .82rem/1.5 Inter, system-ui, sans-serif; color: var(--plfl-subdued); }
    .plfl-pill { display: inline-block; padding: .16rem .5rem; border-radius: 999px; font: 500 .7rem/1.4 Inter, system-ui, sans-serif; background: color-mix(in srgb, var(--plfl-tone) 12%, var(--plfl-surface)); color: var(--plfl-tone); }
    .plfl-pill[data-tone="paid"] { --plfl-tone: var(--plfl-green); }
    .plfl-pill[data-tone="pending"] { --plfl-tone: var(--plfl-warn); }
    .plfl-pill[data-tone="refunded"] { --plfl-tone: var(--plfl-critical); }
    .plfl-variants { inline-size: min(100%, 40rem); display: flex; flex-wrap: wrap; gap: 1.25rem 2.5rem; justify-content: center; align-items: center; }
    .plfl-vcell { display: grid; gap: .55rem; justify-items: center; }
    .plfl-vcell > small { font: 500 .72rem/1 Inter, system-ui, sans-serif; color: var(--nx-text-muted); }
    :where(.nx-js) .pg:has(.plfl-root) .nx-data-table[data-nx-reveal] tbody tr {
        opacity: 1; translate: none;
    }
    @media (max-width: 480px) {
        .pg:has(.plfl-root) .nx-data-table :is(th, td) {
            white-space: normal; padding-inline: .5rem; overflow-wrap: break-word;
        }
        .pg:has(.plfl-root) pre code { white-space: pre-wrap; overflow-wrap: anywhere; }
    }
    @media (prefers-reduced-motion: reduce) {
        .plfl-root * { transition-duration: .01ms !important; }
    }
</style>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Filter, chip, sweep', 'فیلتر کن، چیپ شو، پاک شو') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('Search as you type, open «Filters» and pick statuses or channels — each lands as a removable chip. One click on «Clear all filters» sweeps them, and the list underneath live-filters.', 'همان لحظه جست‌وجو کنید، «فیلتر» را باز کنید و وضعیت یا کانال بزنید — هر انتخاب یک چیپ قابل‌حذف می‌شود. یک کلیک روی «پاک‌کردن همهٔ فیلترها» همه را می‌زداید و فهرست پایین زنده فیلتر می‌شود.') }}
        </p>
    </div>

    <div class="plfl-root" style="inline-size: 100%"
        x-data="{
            fa: {{ $fa ? 'true' : 'false' }},
            rows: {!! $rowsJson !!}, stLabel: {!! $stLabelJson !!},
            stOpts: {!! $stOptsJson !!}, chOpts: {!! $chOptsJson !!},
            views: {!! $viewsJson !!},
            q: '', st: [], ch: [], view: 'all', panel: false,
            fd(x) { return this.fa ? String(x).replace(/[0-9]/g, d => '۰۱۲۳۴۵۶۷۸۹'[+d]) : String(x) },
            get out() {
                return this.rows.filter(r =>
                    (!this.q || r.cust.includes(this.q.trim())) &&
                    (!this.st.length || this.st.includes(r.st)) &&
                    (!this.ch.length || this.ch.includes(r.ch)));
            },
            get chips() {
                return [
                    ...this.stOpts.filter(o => this.st.includes(o.k)).map(o => ({ t: 'st', k: o.k, l: o.l })),
                    ...this.chOpts.filter(o => this.ch.includes(o.k)).map(o => ({ t: 'ch', k: o.k, l: o.l })),
                ];
            },
            setView(k) { this.view = k; this.st = k === 'all' ? [] : [k] },
            drop(t, k) { if (t === 'st') this.st = this.st.filter(x => x !== k); else this.ch = this.ch.filter(x => x !== k) },
            clearAll() { this.q = ''; this.st = []; this.ch = []; this.view = 'all' },
        }">
        <div class="plfl-frame">
            <div class="plfl-views" role="tablist" aria-label="{{ $say('Saved views', 'نمایه‌های ذخیره‌شده') }}">
                <template x-for="v in views" :key="v.k">
                    <button type="button" class="plfl-view" role="tab" x-on:click="setView(v.k)" x-bind:aria-selected="view === v.k ? 'true' : 'false'" x-text="v.l"></button>
                </template>
            </div>
            <div class="plfl-bar">
                <span class="plfl-search">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                    <input type="search" x-model="q" x-on:input="view = 'all'" placeholder="{{ $say('Search orders', 'جست‌وجوی سفارش‌ها') }}" aria-label="{{ $say('Search orders', 'جست‌وجوی سفارش‌ها') }}">
                </span>
                <button type="button" x-on:click="panel = !panel" x-bind:aria-expanded="panel ? 'true' : 'false'">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 6h16M7 12h10M10 18h4"/></svg>
                    {{ $say('Filters', 'فیلتر') }}
                </button>
                <div class="plfl-pop" x-show="panel" x-cloak x-on:click.outside="panel = false" x-transition.opacity.duration.150ms>
                    <div>
                        <b>{{ $say('Payment status', 'وضعیت پرداخت') }}</b>
                        <div class="plfl-opts">
                            <template x-for="o in stOpts" :key="o.k">
                                <label><input type="checkbox" x-bind:checked="st.includes(o.k)" x-on:click="st.includes(o.k) ? drop('st', o.k) : st.push(o.k)"><span x-text="o.l"></span></label>
                            </template>
                        </div>
                    </div>
                    <div>
                        <b>{{ $say('Sales channel', 'کانال فروش') }}</b>
                        <div class="plfl-opts">
                            <template x-for="o in chOpts" :key="o.k">
                                <label><input type="checkbox" x-bind:checked="ch.includes(o.k)" x-on:click="ch.includes(o.k) ? drop('ch', o.k) : ch.push(o.k)"><span x-text="o.l"></span></label>
                            </template>
                        </div>
                    </div>
                    <div class="plfl-pop-foot"><button type="button" class="plfl-done" x-on:click="panel = false">{{ $say('Done', 'انجام') }}</button></div>
                </div>
            </div>
            <div class="plfl-chiprow">
                <span class="plfl-chip" x-show="q.trim()" x-cloak>
                    <span x-text="'{{ $say('Search', 'جست‌وجو') }}: ' + q.trim()"></span>
                    <button type="button" class="plfl-chip-x" x-on:click="q = ''" aria-label="{{ $say('Remove search filter', 'حذف فیلتر جست‌وجو') }}">✕</button>
                </span>
                <template x-for="c in chips" :key="c.t + c.k">
                    <span class="plfl-chip">
                        <span x-text="c.l"></span>
                        <button type="button" class="plfl-chip-x" x-on:click="drop(c.t, c.k)" x-bind:aria-label="'{{ $say('Remove filter', 'حذف فیلتر') }} ' + c.l">✕</button>
                    </span>
                </template>
                <button type="button" class="plfl-clear" x-show="chips.length || q.trim()" x-cloak x-on:click="clearAll()">{{ $say('Clear all filters', 'پاک‌کردن همهٔ فیلترها') }}</button>
            </div>
            <p class="plfl-count" aria-live="polite" x-text="fd(out.length) + ' {{ $say('results', 'نتیجه') }}'">{{ $say('6 results', '۶ نتیجه') }}</p>
            <div class="plfl-list">
                <template x-for="(r, i) in out" :key="r.cust + i">
                    <div class="plfl-row">
                        <b x-text="r.cust"></b>
                        <small x-text="r.sum"></small>
                        <span class="plfl-pill" x-bind:data-tone="r.st" x-text="stLabel[r.st]"></span>
                    </div>
                </template>
                <p class="plfl-none" x-show="!out.length" x-cloak>{{ $say('Nothing matches these filters', 'با این فیلترها چیزی پیدا نشد') }}</p>
            </div>
        </div>
    </div>
</section>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('Chip variants', 'گونه‌های چیپ') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('Applied chips carry an ×, locked chips (store defaults) render dashed without one, and tone chips keep their status colour.', 'چیپ اعمال‌شده ضربدر دارد؛ چیپ قفل‌شده (پیش‌فرض فروشگاه) خط‌چین و بدون ضربدر است و چیپ تُن‌دار رنگ وضعیتش را نگه می‌دارد.') }}
        </p>
    </div>
    <div class="plfl-root plfl-variants">
        <div class="plfl-vcell">
            <span class="plfl-chip">
                <span>{{ $say('Paid', 'پرداخت‌شده') }}</span>
                <button type="button" class="plfl-chip-x" aria-label="{{ $say('Remove filter', 'حذف فیلتر') }}">✕</button>
            </span>
            <small>removable</small>
        </div>
        <div class="plfl-vcell">
            <span class="plfl-chip" data-lock>
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
                <span>{{ $say('Shop default', 'پیش‌فرض فروشگاه') }}</span>
            </span>
            <small>locked</small>
        </div>
        <div class="plfl-vcell">
            <span class="plfl-chip" style="border-color: color-mix(in srgb, #D72C0D 35%, transparent); color: #D72C0D">
                <span>{{ $say('At risk', 'در معرض ریسک') }}</span>
                <button type="button" class="plfl-chip-x" style="color: inherit" aria-label="{{ $say('Remove filter', 'حذف فیلتر') }}">✕</button>
            </span>
            <small>tone</small>
        </div>
    </div>
</section>
