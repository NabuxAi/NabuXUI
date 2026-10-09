{{--
    Polaris Index Table as the orders desk of a real shop: per-row checkboxes,
    a select-all with an indeterminate state, a dark bulk-actions bar that
    rises over the header on first selection (fulfill flashes, archive really
    removes), and pinned pagination that pages the rows for real.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $orders = [
        ['id' => 1001, 'no' => $say('#1001', '#۱۰۰۱'), 'cust' => $say('Sara Ahmadi', 'سارا احمدی'), 'date' => $say('Jan 12', '۱۲ دی'), 'sum' => $say('$245.00', '۲٬۴۵۰٬۰۰۰ تومان'), 'st' => 'paid'],
        ['id' => 1002, 'no' => $say('#1002', '#۱۰۰۲'), 'cust' => $say('Reza Kaviani', 'رضا کاویانی'), 'date' => $say('Jan 12', '۱۲ دی'), 'sum' => $say('$89.00', '۸۹۰٬۰۰۰ تومان'), 'st' => 'pending'],
        ['id' => 1003, 'no' => $say('#1003', '#۱۰۰۳'), 'cust' => $say('Mahsa Karimi', 'مهسا کریمی'), 'date' => $say('Jan 11', '۱۱ دی'), 'sum' => $say('$125.00', '۱٬۲۵۰٬۰۰۰ تومان'), 'st' => 'paid'],
        ['id' => 1004, 'no' => $say('#1004', '#۱۰۰۴'), 'cust' => $say('Arash Samadi', 'آرش صمدی'), 'date' => $say('Jan 10', '۱۰ دی'), 'sum' => $say('$368.00', '۳٬۶۸۰٬۰۰۰ تومان'), 'st' => 'refunded'],
        ['id' => 1005, 'no' => $say('#1005', '#۱۰۰۵'), 'cust' => $say('Negar Tehrani', 'نگار تهرانی'), 'date' => $say('Jan 9', '۹ دی'), 'sum' => $say('$64.00', '۶۴۰٬۰۰۰ تومان'), 'st' => 'paid'],
        ['id' => 1006, 'no' => $say('#1006', '#۱۰۰۶'), 'cust' => $say('Bahram Nik', 'بهرام نیک'), 'date' => $say('Jan 9', '۹ دی'), 'sum' => $say('$110.00', '۱٬۱۰۰٬۰۰۰ تومان'), 'st' => 'pending'],
    ];
    $stLabel = ['paid' => $say('Paid', 'پرداخت‌شده'), 'pending' => $say('Pending', 'در انتظار'), 'refunded' => $say('Refunded', 'مرجوع')];
    $ordersJson = e(json_encode($orders));
    $stLabelJson = e(json_encode($stLabel));
@endphp
<style>
    [x-cloak] { display: none !important; }
    .plit-root {
        --plit-surface: #FFFFFF; --plit-raised: #F6F6F6; --plit-text: #303030; --plit-subdued: #616161;
        --plit-border: #E3E3E3; --plit-strong: #8A8A8A; --plit-hover: color-mix(in srgb, var(--plit-text) 4%, var(--plit-surface));
        --plit-green: #008060; --plit-on-green: #FFFFFF; --plit-green-hover: #004C3F;
        --plit-tint: color-mix(in srgb, var(--plit-green) 9%, var(--plit-surface));
        --plit-critical: #D72C0D; --plit-warn: #8A6116; --plit-info: #2C6ECB; --plit-focus: #005BD3;
        --plit-bulk: #1F3A33; --plit-on-bulk: #F1F7F4;
        font-family: Inter, -apple-system, "Segoe UI", Roboto, system-ui, sans-serif;
        display: grid; gap: 1.5rem; justify-items: center;
    }
    html[data-theme="dark"] .plit-root {
        --plit-surface: #202020; --plit-raised: #2B2B2B; --plit-text: #F1F1F1; --plit-subdued: #B5B5B5;
        --plit-border: #454545; --plit-strong: #8A8A8A;
        --plit-green: #00A97F; --plit-on-green: #08211A; --plit-green-hover: #00BA93;
        --plit-critical: #FF8D75; --plit-warn: #FFC96B; --plit-info: #6EA6FF;
        --plit-bulk: #0D211B; --plit-on-bulk: #D6EDE4;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .plit-root {
            --plit-surface: #202020; --plit-raised: #2B2B2B; --plit-text: #F1F1F1; --plit-subdued: #B5B5B5;
            --plit-border: #454545; --plit-strong: #8A8A8A;
            --plit-green: #00A97F; --plit-on-green: #08211A; --plit-green-hover: #00BA93;
            --plit-critical: #FF8D75; --plit-warn: #FFC96B; --plit-info: #6EA6FF;
            --plit-bulk: #0D211B; --plit-on-bulk: #D6EDE4;
        }
    }
    .plit-frame { position: relative; overflow: clip; inline-size: min(100%, 46rem); border: 1px solid var(--plit-border); border-radius: 12px; background: var(--plit-surface); box-shadow: 0 1px 4px rgba(0, 0, 0, .07); }
    .plit-head { display: flex; align-items: center; gap: .75rem; padding: .9rem 1rem; }
    .plit-head b { font: 600 .95rem/1.3 Inter, system-ui, sans-serif; color: var(--plit-text); }
    .plit-head small { margin-inline-start: auto; font: 400 .78rem/1 Inter, system-ui, sans-serif; color: var(--plit-subdued); }
    .plit-scroll { overflow-x: auto; }
    .plit-table { inline-size: 100%; min-inline-size: 33rem; border-collapse: collapse; font: 400 .85rem/1.4 Inter, system-ui, sans-serif; }
    .plit-table th { padding: .55rem .75rem; border-block: 1px solid var(--plit-border); background: var(--plit-raised); text-align: start; font: 500 .75rem/1 Inter, system-ui, sans-serif; color: var(--plit-subdued); white-space: nowrap; }
    .plit-table td { padding: .7rem .75rem; border-block-start: 1px solid var(--plit-border); text-align: start; color: var(--plit-text); white-space: nowrap; }
    .plit-table tr:first-child td { border-block-start: none; }
    .plit-table tbody tr { transition: background .12s; }
    .plit-table tbody tr:hover { background: var(--plit-hover); }
    .plit-table tbody tr[data-picked] { background: var(--plit-tint); }
    .plit-table input[type="checkbox"] { inline-size: 1.05rem; aspect-ratio: 1; accent-color: var(--plit-green); cursor: pointer; }
    .plit-num { font-variant-numeric: tabular-nums; color: var(--plit-subdued); }
    .plit-sum { font-weight: 600; }
    .plit-pill { display: inline-block; padding: .18rem .55rem; border-radius: 999px; font: 500 .72rem/1.4 Inter, system-ui, sans-serif; background: color-mix(in srgb, var(--plit-tone) 12%, var(--plit-surface)); color: var(--plit-tone); }
    .plit-pill[data-tone="paid"] { --plit-tone: var(--plit-green); }
    .plit-pill[data-tone="pending"] { --plit-tone: var(--plit-warn); }
    .plit-pill[data-tone="refunded"] { --plit-tone: var(--plit-critical); }
    .plit-pill[data-tone="info"] { --plit-tone: var(--plit-info); }
    .plit-bulk { position: absolute; inset-inline: .6rem; inset-block-start: .6rem; z-index: 6; display: flex; flex-wrap: wrap; align-items: center; gap: .4rem; padding-block: .5rem; padding-inline: .95rem .6rem; border-radius: 10px; background: var(--plit-bulk); color: var(--plit-on-bulk); box-shadow: 0 8px 20px -8px rgba(0, 0, 0, .45); animation: plit-rise .22s cubic-bezier(.2, 0, 0, 1); }
    @keyframes plit-rise { from { translate: 0 -110%; opacity: 0; } }
    .plit-bulk b { font: 600 .85rem/1 Inter, system-ui, sans-serif; }
    .plit-bulk-u { flex: 1; }
    .plit-bulk button { block-size: 1.9rem; padding-inline: .7rem; border: none; border-radius: 8px; background: transparent; color: inherit; cursor: pointer; font: 500 .8rem/1 Inter, system-ui, sans-serif; }
    .plit-bulk button:hover { background: color-mix(in srgb, var(--plit-on-bulk) 12%, transparent); }
    .plit-bulk button[data-go] { background: var(--plit-green); color: var(--plit-on-green); }
    .plit-bulk button[data-go]:hover { background: var(--plit-green-hover); }
    .plit-foot { display: flex; align-items: center; gap: .5rem; padding: .65rem .9rem; border-block-start: 1px solid var(--plit-border); background: var(--plit-raised); }
    .plit-foot small { margin-inline: auto; font: 400 .78rem/1 Inter, system-ui, sans-serif; color: var(--plit-subdued); }
    .plit-pg { display: inline-grid; place-items: center; inline-size: 1.9rem; aspect-ratio: 1; border: 1px solid var(--plit-strong); border-radius: 8px; background: var(--plit-surface); color: var(--plit-text); cursor: pointer; }
    .plit-pg:hover:not(:disabled) { background: var(--plit-hover); }
    .plit-pg:disabled { opacity: .4; cursor: not-allowed; }
    html[dir="rtl"] .plit-pg svg { scale: -1 1; }
    .plit-pg:focus-visible, .plit-bulk button:focus-visible { outline: 2px solid var(--plit-focus); outline-offset: 2px; }
    .plit-flash { margin: 0; font: 400 .82rem/1.5 Inter, system-ui, sans-serif; color: var(--plit-green); }
    .plit-variants { inline-size: min(100%, 46rem); display: flex; flex-wrap: wrap; gap: 1.25rem 2rem; justify-content: center; align-items: flex-start; }
    .plit-vcell { display: grid; gap: .55rem; justify-items: center; }
    .plit-vcell > small { font: 500 .72rem/1 Inter, system-ui, sans-serif; color: var(--nx-text-muted); }
    .plit-vrow { display: flex; align-items: center; gap: .7rem; inline-size: 15rem; padding: .6rem .75rem; border-radius: 10px; border: 1px solid var(--plit-border); background: var(--plit-surface); font: 400 .8rem/1.3 Inter, system-ui, sans-serif; color: var(--plit-text); }
    .plit-vrow[data-picked] { border-color: color-mix(in srgb, var(--plit-green) 45%, var(--plit-border)); background: var(--plit-tint); }
    .plit-thumb { flex: none; display: grid; place-items: center; inline-size: 2.6rem; aspect-ratio: 1; border-radius: 8px; background: linear-gradient(140deg, #36d399, #008060); color: #fff; font: 600 .9rem/1 Inter, system-ui, sans-serif; }
    .plit-vrow b { display: block; font-weight: 600; }
    .plit-vrow small { display: block; color: var(--plit-subdued); font-size: .72rem; }
    /* The shared "Important props" table below this stage reveals its rows on
       scroll; a full-page capture never scrolls. Pin this page's table rows
       visible — scoped through :has(.plit-root). */
    :where(.nx-js) .pg:has(.plit-root) .nx-data-table[data-nx-reveal] tbody tr {
        opacity: 1; translate: none;
    }
    @media (max-width: 480px) {
        .pg:has(.plit-root) .nx-data-table :is(th, td) {
            white-space: normal; padding-inline: .5rem; overflow-wrap: break-word;
        }
        .pg:has(.plit-root) pre code { white-space: pre-wrap; overflow-wrap: anywhere; }
        .plit-hide { display: none; }
    }
    @media (prefers-reduced-motion: reduce) {
        .plit-root * { animation-duration: .01ms !important; transition-duration: .01ms !important; }
    }
</style>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Pick a row — the bar rises', 'یک ردیف بردار — نوار بالا می‌آید') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('Tick any order: the bulk bar slides over the header with a real count. «Archive» deletes the picked rows, pagination really pages, and the header checkbox goes indeterminate.', 'سفارشی را تیک بزنید: نوار عملیات گروهی با شمارش واقعی روی سربرگ می‌لغزد. «آرشیو» ردیف‌های انتخابی را واقعاً حذف می‌کند، صفحه‌بندی واقعی ورق می‌زند و چک‌باکس سربرگ حالت سه‌وضعیتی می‌گیرد.') }}
        </p>
    </div>

    <div class="plit-root" style="inline-size: 100%"
        x-data="{
            fa: {{ $fa ? 'true' : 'false' }},
            all: {!! $ordersJson !!},
            stLabel: {!! $stLabelJson !!},
            page: 1, per: 4, sel: [], flash: '',
            fd(x) { return this.fa ? String(x).replace(/[0-9]/g, d => '۰۱۲۳۴۵۶۷۸۹'[+d]) : String(x) },
            get rows() { return this.all.slice((this.page - 1) * this.per, this.page * this.per) },
            get pageCount() { return Math.max(1, Math.ceil(this.all.length / this.per)) },
            get pagePicked() { return this.rows.length > 0 && this.rows.every(r => this.sel.includes(r.id)) },
            go(d) { this.page = Math.min(Math.max(1, this.page + d), this.pageCount) },
            toggleAll() {
                if (this.pagePicked) this.sel = this.sel.filter(id => !this.rows.some(r => r.id === id));
                else this.rows.forEach(r => { if (!this.sel.includes(r.id)) this.sel.push(r.id) });
            },
            say(msg) { this.flash = msg; clearTimeout(this.t); this.t = setTimeout(() => this.flash = '', 2600) },
            fulfill() { const n = this.sel.length; this.say(this.fd(n) + ' {{ $say('orders marked for fulfillment', 'سفارش برای بسته‌بندی علامت خورد') }}'); this.sel = [] },
            archive() {
                const n = this.sel.length;
                this.all = this.all.filter(r => !this.sel.includes(r.id));
                this.sel = []; this.page = Math.min(this.page, this.pageCount);
                this.say(this.fd(n) + ' {{ $say('orders archived', 'سفارش آرشیو شد') }}');
            },
        }" x-effect="if ($refs.all) $refs.all.indeterminate = sel.length > 0 && !pagePicked; $refs.all.checked = pagePicked">
        <div class="plit-frame">
            <div class="plit-head">
                <b>{{ $say('Recent orders', 'سفارش‌های اخیر') }}</b>
                <small x-text="fd(all.length) + ' {{ $say('orders', 'سفارش') }}'">۶ سفارش</small>
            </div>

            <div class="plit-bulk" x-show="sel.length" x-cloak role="toolbar" aria-label="{{ $say('Bulk actions', 'عملیات گروهی') }}">
                <b x-text="fd(sel.length) + ' {{ $say('selected', 'انتخاب شد') }}'">۱ انتخاب شد</b>
                <span class="plit-bulk-u"></span>
                <button type="button" x-on:click="fulfill()">{{ $say('Fulfill', 'بسته‌بندی') }}</button>
                <button type="button" x-on:click="archive()">{{ $say('Archive', 'آرشیو') }}</button>
                <button type="button" x-on:click="sel = []" aria-label="{{ $say('Clear selection', 'پاک‌کردن انتخاب‌ها') }}">✕</button>
            </div>

            <div class="plit-scroll">
                <table class="plit-table">
                    <thead>
                        <tr>
                            <th style="inline-size: 2.6rem"><input type="checkbox" x-ref="all" aria-label="{{ $say('Select all rows on this page', 'انتخاب همهٔ ردیف‌های این صفحه') }}" x-on:click.prevent="toggleAll()"></th>
                            <th>{{ $say('Order', 'سفارش') }}</th>
                            <th class="plit-hide">{{ $say('Date', 'تاریخ') }}</th>
                            <th class="plit-hide">{{ $say('Customer', 'مشتری') }}</th>
                            <th>{{ $say('Total', 'مجموع') }}</th>
                            <th>{{ $say('Status', 'وضعیت') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="r in rows" :key="r.id">
                            <tr x-bind:data-picked="sel.includes(r.id) ? '' : null">
                                <td><input type="checkbox" x-bind:checked="sel.includes(r.id)" x-on:click="sel.includes(r.id) ? sel = sel.filter(i => i !== r.id) : sel.push(r.id)" x-bind:aria-label="'{{ $say('Select order', 'انتخاب سفارش') }} ' + r.no"></td>
                                <td class="plit-num" x-text="r.no"></td>
                                <td class="plit-num plit-hide" x-text="r.date"></td>
                                <td class="plit-hide" x-text="r.cust"></td>
                                <td class="plit-sum" x-text="r.sum"></td>
                                <td><span class="plit-pill" x-bind:data-tone="r.st" x-text="stLabel[r.st]"></span></td>
                            </tr>
                        </template>
                        <tr x-show="!all.length" x-cloak>
                            <td colspan="6" style="text-align: center; padding-block: 1.4rem; color: var(--plit-subdued)">{{ $say('No orders left — archive emptied the table', 'سفارشی نماند — آرشیو جدول را خالی کرد') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="plit-foot">
                <button type="button" class="plit-pg" x-on:click="go(-1)" x-bind:disabled="page <= 1" aria-label="{{ $say('Previous page', 'صفحهٔ قبل') }}"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 5l-7 7 7 7"/></svg></button>
                <small x-text="'{{ $say('Page', 'صفحهٔ') }} ' + fd(page) + ' {{ $say('of', 'از') }} ' + fd(pageCount)">صفحهٔ ۱ از ۲</small>
                <button type="button" class="plit-pg" x-on:click="go(1)" x-bind:disabled="page >= pageCount" aria-label="{{ $say('Next page', 'صفحهٔ بعد') }}"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 5l7 7-7 7"/></svg></button>
            </div>
        </div>
        <p class="plit-flash" x-show="flash" x-cloak aria-live="polite" x-text="flash"></p>
    </div>
</section>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('Row variants & status pills', 'گونه‌های ردیف و نشان‌های وضعیت') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('Product rows carry a thumbnail, picked rows sit on the brand tint, and every status is a subdued pill of its own tone.', 'ردیف محصول تامبنیل دارد، ردیف انتخاب‌شده روی ته‌رنگ برند می‌نشیند و هر وضعیت یک نشان ملایم از تُن خودش است.') }}
        </p>
    </div>
    <div class="plit-root plit-variants">
        <div class="plit-vcell">
            <div class="plit-vrow">
                <span class="plit-thumb" aria-hidden="true">ش</span>
                <span><b>{{ $say('Soy candle, minimal', 'شمع سویا مینیمال') }}</b><small>SKU: CD-220</small></span>
            </div>
            <small>media</small>
        </div>
        <div class="plit-vcell">
            <div class="plit-vrow" data-picked>
                <span class="plit-thumb" style="background: linear-gradient(140deg, #60a5fa, #2c6ecb)" aria-hidden="true">م</span>
                <span><b>{{ $say('Ceramic mug, 350 ml', 'ماگ سرامیکی ۳۵۰ میلی') }}</b><small>SKU: MG-350</small></span>
            </div>
            <small>picked</small>
        </div>
        <div class="plit-vcell">
            <div style="display: flex; gap: .5rem; flex-wrap: wrap; justify-content: center">
                <span class="plit-pill" data-tone="paid">{{ $say('Paid', 'پرداخت‌شده') }}</span>
                <span class="plit-pill" data-tone="pending">{{ $say('Pending', 'در انتظار') }}</span>
                <span class="plit-pill" data-tone="refunded">{{ $say('Refunded', 'مرجوع') }}</span>
                <span class="plit-pill" data-tone="info">{{ $say('Fulfilling', 'در حال ارسال') }}</span>
            </div>
            <small>status pills</small>
        </div>
    </div>
</section>
