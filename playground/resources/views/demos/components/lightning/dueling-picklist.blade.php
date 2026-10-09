{{--
    The Dueling Picklist from Lightning setup: «Available» and «Chosen»
    listboxes with multi-select, the move rail that only arms when a
    selection exists, double arrows that take everything, and reorder
    arrows inside the chosen list — configuring which columns the deal
    reports show. The specimen row shows option states and the rail.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    // Report columns: name fa/en, meta fa/en
    $fields = [
        ['نام فرصت', 'Opportunity name', 'متن', 'text'],
        ['مرحله', 'Stage', 'فهرست انتخاب', 'picklist'],
        ['مبلغ', 'Amount', 'عدد', 'number'],
        ['تاریخ بستن', 'Close date', 'تاریخ', 'date'],
        ['نام مالک', 'Owner name', 'ارجاع', 'lookup'],
        ['احتمال موفقیت', 'Probability', 'درصد', 'percent'],
        ['حساب', 'Account', 'ارجاع', 'lookup'],
        ['گام بعدی', 'Next step', 'متن', 'text'],
    ];
@endphp
<style>
    .slk-root {
        --slk-blue: #0176D3; --slk-blue-soft: color-mix(in srgb, #0176D3 8%, #FFFFFF);
        --slk-green: #04844B; --slk-purple: #6739B7;
        --slk-text: #181818; --slk-weak: #444444; --slk-muted: #706E6B;
        --slk-border: #DDDBDA; --slk-bg: #F3F3F3; --slk-card: #FFFFFF;
        font-family: 'Salesforce Sans', -apple-system, 'Segoe UI', Roboto, sans-serif;
        color: var(--slk-text);
        display: grid; gap: 1.5rem;
    }
    html[data-theme="dark"] .slk-root {
        --slk-blue: #0D9DDA; --slk-blue-soft: color-mix(in srgb, #0D9DDA 16%, #232323);
        --slk-green: #0E9E5B; --slk-purple: #A57DE8;
        --slk-text: #F3F3F3; --slk-weak: #CECECE; --slk-muted: #A5A5A5;
        --slk-border: #474747; --slk-bg: #181818; --slk-card: #232323;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .slk-root {
            --slk-blue: #0D9DDA; --slk-blue-soft: color-mix(in srgb, #0D9DDA 16%, #232323);
            --slk-green: #0E9E5B; --slk-purple: #A57DE8;
            --slk-text: #F3F3F3; --slk-weak: #CECECE; --slk-muted: #A5A5A5;
            --slk-border: #474747; --slk-bg: #181818; --slk-card: #232323;
        }
    }
    .slk-root, .slk-root *, .slk-root *::before, .slk-root *::after { box-sizing: border-box; }
    .slk-root :focus-visible { outline: 2px solid var(--slk-blue); outline-offset: 2px; }

    .slk-duel { inline-size: min(100%, 46rem); margin-inline: auto; display: grid; grid-template-columns: minmax(0, 1fr) auto minmax(0, 1fr);
                gap: .75rem; align-items: start; }
    @media (max-width: 560px) { .slk-duel { grid-template-columns: 1fr; } .slk-rail { flex-direction: row; justify-content: center; } }
    .slk-listbox { border: 1px solid var(--slk-border); border-radius: .25rem; background: var(--slk-card); padding: 0; margin: 0; }
    .slk-listbox legend { padding: .55rem .75rem .4rem; font-size: .82rem; font-weight: 700; display: flex; gap: .35rem; }
    .slk-listbox legend small { font-weight: 400; color: var(--slk-muted); }
    .slk-options { margin: 0; padding: 0 .25rem .35rem; list-style: none; max-block-size: 15rem; overflow-y: auto; }
    .slk-opt { border: 0; inline-size: 100%; background: transparent; color: inherit; font: inherit; cursor: pointer;
               padding: .45rem .6rem; border-radius: .25rem; text-align: start; transition: background .12s ease; }
    .slk-opt + .slk-opt { margin-block-start: 1px; }
    .slk-opt b { display: block; font-size: .84rem; font-weight: 600; }
    .slk-opt small { display: block; font-size: .72rem; color: var(--slk-muted); }
    .slk-opt:hover { background: var(--slk-bg); }
    .slk-opt[aria-selected="true"] { background: var(--slk-blue-soft); box-shadow: inset 3px 0 0 var(--slk-blue); }
    [dir="rtl"] .slk-opt[aria-selected="true"] { box-shadow: inset -3px 0 0 var(--slk-blue); }
    .slk-rail { display: grid; gap: .3rem; padding-block-start: 2.4rem; }
    .slk-move { display: grid; place-items: center; inline-size: 2.25rem; block-size: 2rem; border: 1px solid var(--slk-border);
                border-radius: .25rem; background: var(--slk-card); color: var(--slk-blue); cursor: pointer;
                transition: background .15s ease, opacity .15s ease; }
    .slk-move:hover:not(:disabled) { background: var(--slk-blue-soft); }
    .slk-move:disabled { color: color-mix(in srgb, var(--slk-text) 35%, transparent); cursor: not-allowed; }
    .slk-order { display: grid; gap: .3rem; padding-block-start: 2.4rem; }
    .slk-save { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; justify-content: center; margin-block-start: .35rem; }
    .slk-btn { padding: .45rem 1.1rem; border: 1px solid var(--slk-border); border-radius: .25rem; background: var(--slk-card);
               color: var(--slk-text); font: inherit; font-size: .84rem; cursor: pointer; transition: background .15s ease; }
    .slk-btn:hover { background: var(--slk-bg); }
    .slk-btn[data-variant="brand"] { background: var(--slk-blue); border-color: var(--slk-blue); color: #FFFFFF; font-weight: 600; }
    .slk-btn[data-variant="brand"]:hover { background: color-mix(in srgb, var(--slk-blue) 85%, #000); }
    .slk-note { margin: 0; font-size: .78rem; color: var(--slk-muted); }
    .slk-note b { color: var(--slk-green); }

    .slk-spec { display: flex; flex-wrap: wrap; gap: 1rem 1.75rem; justify-content: center; align-items: flex-start; }
    .slk-spec-cell { display: grid; gap: .4rem; justify-items: center; inline-size: min(100%, 12rem); }
    .slk-spec-cell > small { font-size: .72rem; color: var(--slk-muted); }
    .slk-spec-opt { display: block; inline-size: 100%; padding: .4rem .6rem; border: 1px solid var(--slk-border);
               border-radius: .25rem; background: var(--slk-card); }
    .slk-spec-opt b { display: block; font-size: .8rem; font-weight: 600; }
    .slk-spec-opt small { display: block; font-size: .7rem; color: var(--slk-muted); }
    .slk-spec-opt[data-selected="true"] { background: var(--slk-blue-soft); box-shadow: inset 3px 0 0 var(--slk-blue); }
    [dir="rtl"] .slk-spec-opt[data-selected="true"] { box-shadow: inset -3px 0 0 var(--slk-blue); }
    .slk-spec-rail { display: flex; gap: .3rem; }
    :where(.nx-js) .pg:has(.slk-root) .nx-data-table[data-nx-reveal] tbody tr { opacity: 1; translate: none; }
    @media (prefers-reduced-motion: reduce) {
        .slk-root * { transition-duration: .01ms !important; }
    }
</style>

<div class="slk-root"
    x-data="{
        avail: [
            { n: { fa: 'نام مالک', en: 'Owner name' }, m: { fa: 'ارجاع', en: 'lookup' } },
            { n: { fa: 'احتمال موفقیت', en: 'Probability' }, m: { fa: 'درصد', en: 'percent' } },
            { n: { fa: 'حساب', en: 'Account' }, m: { fa: 'ارجاع', en: 'lookup' } },
            { n: { fa: 'گام بعدی', en: 'Next step' }, m: { fa: 'متن', en: 'text' } },
        ],
        chosen: [
            { n: { fa: 'نام فرصت', en: 'Opportunity name' }, m: { fa: 'متن', en: 'text' } },
            { n: { fa: 'مرحله', en: 'Stage' }, m: { fa: 'فهرست انتخاب', en: 'picklist' } },
            { n: { fa: 'مبلغ', en: 'Amount' }, m: { fa: 'عدد', en: 'number' } },
            { n: { fa: 'تاریخ بستن', en: 'Close date' }, m: { fa: 'تاریخ', en: 'date' } },
        ],
        selA: [],
        selC: [],
        savedMsg: '',
        get t() { return document.documentElement.lang === 'fa' ? 'fa' : 'en' },
        num(n) { return this.t === 'fa' ? String(n).replace(/\d/g, d => '۰۱۲۳۴۵۶۷۸۹'[d]) : String(n) },
        name(o) { return this.t === 'fa' ? o.n.fa : o.n.en },
        meta(o) { return this.t === 'fa' ? o.m.fa : o.m.en },
        toggle(list, i) {
            const sel = list === 'a' ? 'selA' : 'selC';
            const at = this[sel].indexOf(i);
            at === -1 ? this[sel].push(i) : this[sel].splice(at, 1);
            this.savedMsg = '';
        },
        take(all) {
            const picks = all ? [...this.avail.keys()] : [...this.selA].sort((x, y) => y - x);
            picks.forEach(i => this.chosen.push(this.avail.splice(i, 1)[0]));
            this.selA = []; this.savedMsg = '';
        },
        give(all) {
            const picks = all ? [...this.chosen.keys()] : [...this.selC].sort((x, y) => y - x);
            picks.forEach(i => this.avail.push(this.chosen.splice(i, 1)[0]));
            this.selC = []; this.savedMsg = '';
        },
        shift(dir) {
            const order = [...this.selC].sort((x, y) => dir === 'up' ? y - x : x - y);
            order.forEach(i => {
                const to = dir === 'up' ? i - 1 : i + 1;
                if (to < 0 || to >= this.chosen.length) return;
                [this.chosen[i], this.chosen[to]] = [this.chosen[to], this.chosen[i]];
            });
            this.selC = this.selC.map(i => i).sort();
            this.savedMsg = '';
        },
        save() {
            this.savedMsg = this.num(this.chosen.length) + ' ' + (this.t === 'fa' ? 'ستون در «گزارش معامله‌ها» ذخیره شد' : 'columns saved to «Deals report»');
        },
    }">
    <section class="pg-box" style="justify-items: center; inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Pick the report columns', 'ستون‌های گزارش را بچینید') }}</h3>
            <p style="margin: 0; max-width: 46ch; color: var(--nx-text-muted)">
                {{ $say('Click options to select (Ctrl/⌘ for several), move them across the rail, order them in «Chosen», then save the report layout.', 'گزینه‌ها را کلیک کنید (با Ctrl/⌘ چندتایی)، از ریل عبور دهید، در «انتخاب‌شده» مرتبشان کنید و چیدمان گزارش را ذخیره کنید.') }}
            </p>
        </div>

        <form class="slk-duel" x-on:submit.prevent="save()">
            <fieldset class="slk-listbox">
                <legend>{{ $say('Available', 'در دسترس') }} (<small x-text="num(avail.length)"></small>)</legend>
                <ul class="slk-options" role="listbox" aria-multiselectable="true" aria-label="{{ $say('Available fields', 'فیلدهای در دسترس') }}">
                    <template x-for="(o, i) in avail" :key="name(o) + i">
                        <li role="option" :aria-selected="selA.includes(i)">
                            <button type="button" class="slk-opt" x-on:click="toggle('a', i)">
                                <b x-text="name(o)"></b>
                                <small x-text="meta(o)"></small>
                            </button>
                        </li>
                    </template>
                    <li class="slk-opt" x-show="avail.length === 0" x-cloak style="color: var(--slk-muted); cursor: default">
                        {{ $say('Everything is chosen', 'همه انتخاب شده‌اند') }}
                    </li>
                </ul>
            </fieldset>

            <div class="slk-rail" role="group" aria-label="{{ $say('Move fields', 'جابه‌جایی فیلدها') }}">
                <button type="button" class="slk-move" :disabled="! selA.length"
                    aria-label="{{ $say('Move selection to Chosen', 'بردن انتخاب به «انتخاب‌شده»') }}" x-on:click="take(false)">
                    <x-nx::icon name="chevron-right" style="inline-size: 1.1em; block-size: 1.1em" />
                </button>
                <button type="button" class="slk-move" :disabled="! avail.length"
                    aria-label="{{ $say('Move all to Chosen', 'بردن همه به «انتخاب‌شده»') }}" x-on:click="take(true)">
                    <x-nx::icon name="chevron-right" style="inline-size: 1.1em; block-size: 1.1em" /><x-nx::icon name="chevron-right" style="inline-size: 1.1em; block-size: 1.1em; margin-inline-start: -.55em" />
                </button>
                <button type="button" class="slk-move" :disabled="! selC.length"
                    aria-label="{{ $say('Move selection back to Available', 'برگرداندن انتخاب به «در دسترس»') }}" x-on:click="give(false)">
                    <x-nx::icon name="chevron-left" style="inline-size: 1.1em; block-size: 1.1em" />
                </button>
                <button type="button" class="slk-move" :disabled="! chosen.length"
                    aria-label="{{ $say('Move all back to Available', 'برگرداندن همه به «در دسترس»') }}" x-on:click="give(true)">
                    <x-nx::icon name="chevron-left" style="inline-size: 1.1em; block-size: 1.1em" /><x-nx::icon name="chevron-left" style="inline-size: 1.1em; block-size: 1.1em; margin-inline-start: -.55em" />
                </button>
            </div>

            <fieldset class="slk-listbox">
                <legend>{{ $say('Chosen', 'انتخاب‌شده') }} (<small x-text="num(chosen.length)"></small>)</legend>
                <div style="display: flex; gap: .3rem; padding: 0 .4rem">
                    <ul class="slk-options" role="listbox" aria-multiselectable="true" style="flex: 1; padding-inline-start: .1rem" aria-label="{{ $say('Chosen fields', 'فیلدهای انتخاب‌شده') }}">
                        <template x-for="(o, i) in chosen" :key="name(o) + i">
                            <li role="option" :aria-selected="selC.includes(i)">
                                <button type="button" class="slk-opt" x-on:click="toggle('c', i)">
                                    <b x-text="num(i + 1) + '. ' + name(o)"></b>
                                    <small x-text="meta(o)"></small>
                                </button>
                            </li>
                        </template>
                        <li class="slk-opt" x-show="chosen.length === 0" x-cloak style="color: var(--slk-muted); cursor: default">
                            {{ $say('Nothing chosen yet', 'هنوز چیزی انتخاب نشده') }}
                        </li>
                    </ul>
                    <div class="slk-order" style="padding-block-start: .2rem" role="group" aria-label="{{ $say('Reorder chosen fields', 'ترتیب فیلدهای انتخاب‌شده') }}">
                        <button type="button" class="slk-move" :disabled="! selC.length"
                            aria-label="{{ $say('Move up', 'بالا بردن') }}" x-on:click="shift('up')">
                            <x-nx::icon name="chevron-up" style="inline-size: 1.1em; block-size: 1.1em" />
                        </button>
                        <button type="button" class="slk-move" :disabled="! selC.length"
                            aria-label="{{ $say('Move down', 'پایین بردن') }}" x-on:click="shift('down')">
                            <x-nx::icon name="chevron-down" style="inline-size: 1.1em; block-size: 1.1em" />
                        </button>
                    </div>
                </div>
            </fieldset>

            <div class="slk-save" style="grid-column: 1 / -1">
                <button type="button" class="slk-btn" x-on:click="give(true)">{{ $say('Reset', 'بازنشانی') }}</button>
                <button type="submit" class="slk-btn" data-variant="brand">{{ $say('Save layout', 'ذخیرهٔ چیدمان') }}</button>
                <p class="slk-note" x-show="savedMsg" x-cloak><b>✓</b> <span x-text="savedMsg"></span></p>
            </div>
        </form>
    </section>

    <section class="pg-box" style="justify-items: center; inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('Option states and the rail', 'حالت‌های گزینه و ریل') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('The selected row carries the brand wash and edge bar; the move arrows stay grey until a selection exists.', 'ردیف انتخاب‌شده شستوی برند و خط لبه می‌گیرد؛ فلش‌های انتقال تا وقتی انتخابی نباشد خاکستری می‌مانند.') }}
            </p>
        </div>
        <div class="slk-spec" style="inline-size: 100%">
            <div class="slk-spec-cell">
                <span class="slk-spec-opt"><b>{{ $say('Stage', 'مرحله') }}</b><small>{{ $say('picklist', 'فهرست انتخاب') }}</small></span>
                <small>default</small>
            </div>
            <div class="slk-spec-cell">
                <span class="slk-spec-opt" data-selected="true"><b>{{ $say('Amount', 'مبلغ') }}</b><small>{{ $say('number', 'عدد') }}</small></span>
                <small>selected</small>
            </div>
            <div class="slk-spec-cell">
                <span class="slk-spec-rail">
                    <span class="slk-move" aria-hidden="true"><x-nx::icon name="chevron-right" style="inline-size: 1.1em; block-size: 1.1em" /></span>
                    <span class="slk-move" aria-hidden="true" style="color: color-mix(in srgb, var(--slk-text) 35%, transparent)"><x-nx::icon name="chevron-left" style="inline-size: 1.1em; block-size: 1.1em" /></span>
                </span>
                <small>move rail</small>
            </div>
        </div>
    </section>
</div>
