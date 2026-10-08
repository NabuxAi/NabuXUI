{{--
    Fluent 2 selection controls in a Settings-style pane: a 20px checkbox
    with a scaling checkmark, a radio trio, the 40×20 switch whose fill wipes
    across, a slider with its live value, a star rating you can walk with the
    arrow keys, and a number box with hold-to-repeat spin buttons — plus one
    row in the critical error state.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    .flsel-root {
        --fl-accent: #005FB8;
        --fl-on-accent: #FFFFFF;
        --fl-text: #1B1B1B;
        --fl-text-2: #5D5D5D;
        --fl-window: #F3F3F3;
        --fl-layer: rgba(255, 255, 255, .70);
        --fl-control: rgba(255, 255, 255, .70);
        --fl-card-stroke: rgba(0, 0, 0, .10);
        --fl-divider: rgba(0, 0, 0, .08);
        --fl-hover: rgba(0, 0, 0, .04);
        --fl-edge: rgba(0, 0, 0, .50);
        --fl-critical: #C42B1C;
        --fl-focus: #1B1B1B;
        font-family: "Segoe UI Variable Text", "Segoe UI", system-ui, sans-serif;
        color: var(--fl-text);
        display: grid;
        gap: .75rem;
    }
    html[data-theme="dark"] .flsel-root {
        --fl-accent: #4CC2FF;
        --fl-on-accent: #000000;
        --fl-text: #FFFFFF;
        --fl-text-2: #CFCFCF;
        --fl-window: #202020;
        --fl-layer: rgba(255, 255, 255, .05);
        --fl-control: rgba(255, 255, 255, .06);
        --fl-card-stroke: rgba(255, 255, 255, .08);
        --fl-divider: rgba(255, 255, 255, .06);
        --fl-hover: rgba(255, 255, 255, .06);
        --fl-edge: rgba(255, 255, 255, .55);
        --fl-critical: #FF99A4;
        --fl-focus: #FFFFFF;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .flsel-root {
            --fl-accent: #4CC2FF;
            --fl-on-accent: #000000;
            --fl-text: #FFFFFF;
            --fl-text-2: #CFCFCF;
            --fl-window: #202020;
            --fl-layer: rgba(255, 255, 255, .05);
            --fl-control: rgba(255, 255, 255, .06);
            --fl-card-stroke: rgba(255, 255, 255, .08);
            --fl-divider: rgba(255, 255, 255, .06);
            --fl-hover: rgba(255, 255, 255, .06);
            --fl-edge: rgba(255, 255, 255, .55);
            --fl-critical: #FF99A4;
            --fl-focus: #FFFFFF;
        }
    }
    .flsel-root button { font: inherit; color: inherit; background: none; border: 0; padding: 0; cursor: pointer; }
    .flsel-root :focus-visible { outline: 2px solid var(--fl-focus); outline-offset: 1px; border-radius: 4px; }
    .flsel-input { position: absolute; opacity: 0; inline-size: 1px; block-size: 1px; margin: 0; }

    .flsel-app {
        inline-size: min(100%, 30rem); margin-inline: auto; border-radius: 8px; overflow: clip;
        background: var(--fl-window);
        box-shadow: 0 24px 48px rgba(0, 0, 0, .16), 0 0 0 1px var(--fl-card-stroke);
    }
    .flsel-head { padding: .9rem 1.25rem .7rem; }
    .flsel-head h4 { margin: 0; font-size: 1rem; }
    .flsel-head p { margin: .15rem 0 0; font-size: .72rem; color: var(--fl-text-2); }
    .flsel-row { display: flex; align-items: center; gap: .9rem; padding: .8rem 1.25rem; border-block-start: 1px solid var(--fl-divider); }
    .flsel-rowtext { display: grid; gap: .15rem; min-inline-size: 0; flex: 1; }
    .flsel-rowtext strong { font-size: .8125rem; font-weight: 600; }
    .flsel-rowtext small { font-size: .7rem; line-height: 1.7; color: var(--fl-text-2); }
    .flsel-row[data-error="true"] .flsel-box { border-color: var(--fl-critical); }
    .flsel-err { font-size: .7rem; color: var(--fl-critical); }

    .flsel-check {
        position: relative; display: inline-grid; place-items: center; flex: none;
        inline-size: 20px; block-size: 20px; border-radius: 4px;
        border: 1px solid var(--fl-edge); background: var(--fl-control); transition: border-color .12s, background .12s;
    }
    .flsel-check svg { opacity: 0; scale: .5; transition: opacity .15s, scale .15s cubic-bezier(.2, .9, .3, 1.2); color: var(--fl-on-accent); }
    .flsel-opt:hover .flsel-check, .flsel-row label:hover .flsel-check { border-color: color-mix(in srgb, var(--fl-edge) 60%, var(--fl-text)); }
    .flsel-input:checked + .flsel-check { background: var(--fl-accent); border-color: var(--fl-accent); }
    .flsel-input:checked + .flsel-check svg { opacity: 1; scale: 1; }
    .flsel-input:focus-visible + .flsel-check, .flsel-input:focus-visible + .flsel-radio, .flsel-input:focus-visible + .flsel-switch { outline: 2px solid var(--fl-focus); outline-offset: 2px; }
    .flsel-row[data-error="true"] .flsel-input:checked + .flsel-check { background: var(--fl-critical); border-color: var(--fl-critical); }

    .flsel-radio {
        position: relative; display: inline-grid; place-items: center; flex: none;
        inline-size: 20px; block-size: 20px; border-radius: 999px;
        border: 1px solid var(--fl-edge); background: var(--fl-control); transition: border-color .12s;
    }
    .flsel-radio::after { content: ""; inline-size: 8px; block-size: 8px; border-radius: 999px; background: var(--fl-accent); scale: .3; opacity: 0; transition: scale .15s cubic-bezier(.2, .9, .3, 1.2), opacity .15s; }
    .flsel-opt:hover .flsel-radio { border-color: color-mix(in srgb, var(--fl-edge) 60%, var(--fl-text)); }
    .flsel-input:checked + .flsel-radio { border-color: var(--fl-accent); }
    .flsel-input:checked + .flsel-radio::after { scale: 1; opacity: 1; }
    .flsel-opt { display: flex; align-items: center; gap: .55rem; padding-block: .18rem; font-size: .78rem; cursor: pointer; }

    .flsel-switch {
        position: relative; display: inline-block; flex: none;
        inline-size: 40px; block-size: 20px; border-radius: 999px;
        box-shadow: inset 0 0 0 1px var(--fl-edge); transition: box-shadow .15s;
    }
    .flsel-fill { position: absolute; inset-block: 3px; inset-inline-start: 3px; inline-size: 0; border-radius: 999px; background: var(--fl-accent); transition: inline-size .2s cubic-bezier(.2, .9, .25, 1); }
    .flsel-thumb { position: absolute; inset-block-start: 3px; inset-inline-start: 3px; inline-size: 12px; block-size: 12px; border-radius: 999px; background: var(--fl-text-2); transition: inset-inline-start .2s cubic-bezier(.2, .9, .25, 1), background .2s; }
    .flsel-opt:hover .flsel-switch, .flsel-row label:hover .flsel-switch { box-shadow: inset 0 0 0 1px color-mix(in srgb, var(--fl-edge) 60%, var(--fl-text)); }
    .flsel-input:checked + .flsel-switch { box-shadow: inset 0 0 0 1px var(--fl-accent); }
    .flsel-input:checked + .flsel-switch .flsel-fill { inline-size: calc(100% - 6px); }
    .flsel-input:checked + .flsel-switch .flsel-thumb { inset-inline-start: calc(100% - 15px); background: var(--fl-on-accent); }

    .flsel-slider { position: relative; display: flex; align-items: center; flex: none; inline-size: min(100%, 10.5rem); block-size: 20px; }
    .flsel-track { position: absolute; inset-inline: 0; inset-block-start: 50%; translate: 0 -50%; block-size: 4px; border-radius: 999px; background: var(--fl-divider); overflow: clip; pointer-events: none; }
    .flsel-track i { position: absolute; inset-block: 0; inset-inline-start: 0; border-radius: 999px; background: var(--fl-accent); }
    .flsel-range { position: relative; z-index: 1; appearance: none; -webkit-appearance: none; inline-size: 100%; block-size: 20px; background: transparent; margin: 0; cursor: pointer; }
    .flsel-range::-webkit-slider-runnable-track { background: transparent; block-size: 20px; }
    .flsel-range::-webkit-slider-thumb { -webkit-appearance: none; inline-size: 20px; block-size: 20px; border: 0; border-radius: 999px; background: #FFFFFF; box-shadow: 0 0 0 1px rgba(0, 0, 0, .15), 0 1px 3px rgba(0, 0, 0, .25); }
    .flsel-range::-moz-range-thumb { inline-size: 20px; block-size: 20px; border: 0; border-radius: 999px; background: #FFFFFF; box-shadow: 0 0 0 1px rgba(0, 0, 0, .15), 0 1px 3px rgba(0, 0, 0, .25); }
    .flsel-val { flex: none; min-inline-size: 2.6rem; text-align: end; font-size: .75rem; color: var(--fl-text-2); font-variant-numeric: tabular-nums; }

    .flsel-stars { display: flex; align-items: center; gap: .1rem; }
    .flsel-star { display: grid; place-items: center; inline-size: 1.7rem; block-size: 1.7rem; border-radius: 4px; color: var(--fl-text-2); }
    .flsel-star:hover { background: var(--fl-hover); }
    .flsel-rateval { margin-inline-start: .5rem; font-size: .72rem; color: var(--fl-text-2); }

    .flsel-numbox { position: relative; flex: none; inline-size: 6.5rem; }
    .flsel-numfield {
        inline-size: 100%; block-size: 2rem; border: 0; border-radius: 4px;
        background: var(--fl-control); box-shadow: inset 0 0 0 1px var(--fl-edge);
        font: inherit; font-size: .8rem; color: var(--fl-text); text-align: center;
        padding-inline-end: 1.6rem;
    }
    .flsel-spin { position: absolute; inset-block: 4px; inset-inline-end: 4px; display: grid; grid-template-rows: 1fr 1fr; gap: 1px; inline-size: 1.3rem; }
    .flsel-spin button { display: grid; place-items: center; border-radius: 2px; font-size: .5rem; line-height: 1; color: var(--fl-text-2); }
    .flsel-spin button:hover { background: var(--fl-hover); color: var(--fl-text); }
    .flsel-spin button:active { background: var(--fl-hover); color: var(--fl-text-2); }

    @media (prefers-reduced-motion: reduce) {
        .flsel-check svg, .flsel-radio::after, .flsel-fill, .flsel-thumb { transition: none; }
    }
</style>

<section class="pg-box" style="gap: 1rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Controls that reveal themselves', 'کنترل‌هایی که خودشان را نشان می‌دهند') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The border darkens as you approach, the checkmark scales in, the switch fill wipes across, and the stars walk star-by-star with the arrow keys. Hold a spin button and it repeats.', 'لبه‌ها با نزدیک شدن اشاره‌گر تیره می‌شوند، تیک با مقیاس می‌آید، پرشدن سوییچ روی صفحه می‌کشید و ستاره‌ها با کلیدهای جهت ستاره‌به‌ستاره می‌روند. دکمهٔ اسپین را نگه دارید تا تکرار شود.') }}
        </p>
    </div>

    <div class="flsel-root" x-data="{
            fa: {{ $fa ? 'true' : 'false' }},
            bright: 70, rate: 3, num: 12, holdT: null, repT: null,
            toFa(n) { const s = String(n); return this.fa ? s.replace(/\d/g, d => '۰۱۲۳۴۵۶۷۸۹'[+d]) : s },
            step(d) { this.num = Math.min(72, Math.max(8, this.num + d)) },
            hold(d) { this.step(d); this.stop(); this.holdT = setTimeout(() => { this.repT = setInterval(() => this.step(d), 80) }, 400) },
            stop() { clearTimeout(this.holdT); clearInterval(this.repT) },
            focusStar(i) { const stars = this.$root.querySelectorAll('.flsel-star'); if (i >= 1 && i <= stars.length) { this.rate = i; stars[i - 1].focus() } },
        }" x-on:keyup="if ($event.key === 'Escape') stop()">
        <div class="flsel-app">
            <div class="flsel-head">
                <h4>{{ $say('Display', 'نمایش') }}</h4>
                <p>{{ $say('Personalisation › Display', 'شخصی‌سازی › نمایش') }}</p>
            </div>

            <label class="flsel-row">
                <input type="checkbox" class="flsel-input" checked>
                <span class="flsel-box flsel-check" aria-hidden="true">
                    <svg width="12" height="12" viewBox="0 0 12 12"><path d="M2 6.2l2.6 2.6L10 3.4" stroke="currentColor" stroke-width="1.6" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
                <span class="flsel-rowtext">
                    <strong>{{ $say('Show hidden icons', 'نمایش آیکن‌های مخفی') }}</strong>
                    <small>{{ $say('List system and hidden files in the explorer.', 'پرونده‌های سیستمی و مخفی در فهرست نشان داده شوند.') }}</small>
                </span>
            </label>

            <div class="flsel-row" data-error="true">
                <label class="flsel-opt">
                    <input type="checkbox" class="flsel-input">
                    <span class="flsel-box flsel-check" aria-hidden="true">
                        <svg width="12" height="12" viewBox="0 0 12 12"><path d="M2 6.2l2.6 2.6L10 3.4" stroke="currentColor" stroke-width="1.6" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </span>
                    <span>{{ $say('Auto-sync', 'همگام‌سازی خودکار') }}</span>
                </label>
                <span class="flsel-rowtext">
                    <span class="flsel-err">{{ $say('A restart is required — one file is still locked.', 'راه‌اندازی مجدد لازم است — یکی از پرونده‌ها قفل است.') }}</span>
                </span>
            </div>

            <div class="flsel-row">
                <span class="flsel-rowtext">
                    <strong>{{ $say('Power mode', 'حالت عملکرد') }}</strong>
                    <span role="radiogroup" aria-label="{{ $say('Power mode', 'حالت عملکرد') }}" style="margin-block-start: .3rem; display: grid">
                        <label class="flsel-opt">
                            <input type="radio" name="flsel-perf" class="flsel-input">
                            <span class="flsel-radio" aria-hidden="true"></span>
                            <span>{{ $say('Best power efficiency', 'بهینه‌ترین مصرف انرژی') }}</span>
                        </label>
                        <label class="flsel-opt">
                            <input type="radio" name="flsel-perf" class="flsel-input" checked>
                            <span class="flsel-radio" aria-hidden="true"></span>
                            <span>{{ $say('Balanced', 'متعادل') }}</span>
                        </label>
                        <label class="flsel-opt">
                            <input type="radio" name="flsel-perf" class="flsel-input">
                            <span class="flsel-radio" aria-hidden="true"></span>
                            <span>{{ $say('Best performance', 'بیشترین کارایی') }}</span>
                        </label>
                    </span>
                </span>
            </div>

            <label class="flsel-row">
                <input type="checkbox" class="flsel-input" checked>
                <span class="flsel-switch" aria-hidden="true"><i class="flsel-fill"></i><i class="flsel-thumb"></i></span>
                <span class="flsel-rowtext">
                    <strong>{{ $say('Night light', 'حالت شب') }}</strong>
                    <small>{{ $say('Warm the colours after sunset.', 'رنگ‌ها پس از غروب گرم شوند.') }}</small>
                </span>
            </label>

            <div class="flsel-row">
                <span class="flsel-rowtext">
                    <strong>{{ $say('Brightness', 'روشنایی') }}</strong>
                    <small>{{ $say('Screen luminance in percent.', 'روشنایی صفحه بر حسب درصد.') }}</small>
                </span>
                <span class="flsel-slider">
                    <span class="flsel-track" aria-hidden="true"><i :style="'inline-size:' + bright + '%'"></i></span>
                    <input type="range" class="flsel-range" min="0" max="100" x-model.number="bright" aria-label="{{ $say('Brightness', 'روشنایی') }}">
                </span>
                <span class="flsel-val" x-text="toFa(bright) + (fa ? '٪' : '%')">۷۰٪</span>
            </div>

            <div class="flsel-row">
                <span class="flsel-rowtext">
                    <strong>{{ $say('Rate this experience', 'امتیاز به این تجربه') }}</strong>
                    <small>{{ $say('Click a star, or walk with ← →.', 'ستاره‌ای را کلیک کنید یا با ← → حرکت کنید.') }}</small>
                </span>
                <span style="display: flex; align-items: center">
                    <span class="flsel-stars" role="radiogroup" aria-label="{{ $say('Star rating', 'امتیاز ستاره‌ای') }}"
                        x-on:keydown.arrow-right.prevent="focusStar(Math.min(5, rate + 1))"
                        x-on:keydown.arrow-left.prevent="focusStar(Math.max(1, rate - 1))">
                        <template x-for="i in 5" :key="i">
                            <button type="button" class="flsel-star" role="radio" :aria-checked="i <= rate ? 'true' : 'false'"
                                :tabindex="i === rate ? 0 : -1"
                                :aria-label="toFa(i) + (fa ? ' از ۵ ستاره' : ' of 5 stars')"
                                x-on:click="rate = i">
                                <svg width="18" height="18" viewBox="0 0 20 20" aria-hidden="true">
                                    <path d="M10 1.7l2.47 5.2 5.7.62-4.24 3.86 1.15 5.62L10 14.14l-5.08 2.86 1.15-5.62L1.83 7.52l5.7-.62z"
                                        :fill="i <= rate ? 'var(--fl-accent)' : 'none'"
                                        stroke="currentColor" stroke-width="1.2" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </template>
                    </span>
                    <span class="flsel-rateval" x-text="toFa(rate) + (fa ? ' از ۵' : ' of 5')">۳ از ۵</span>
                </span>
            </div>

            <div class="flsel-row">
                <span class="flsel-rowtext">
                    <strong>{{ $say('Font size', 'اندازهٔ فونت') }}</strong>
                    <small>{{ $say('Editor text size, 8 to 72.', 'اندازهٔ متن ویرایشگر، از ۸ تا ۷۲.') }}</small>
                </span>
                <span class="flsel-numbox">
                    <input type="text" class="flsel-numfield" readonly :value="toFa(num)" aria-label="{{ $say('Font size value', 'مقدار اندازهٔ فونت') }}" aria-live="polite">
                    <span class="flsel-spin">
                        <button type="button" aria-label="{{ $say('Increase', 'افزایش') }}"
                            x-on:pointerdown="hold(1)" x-on:pointerup="stop()" x-on:pointerleave="stop()" x-on:pointercancel="stop()">
                            <svg width="8" height="6" viewBox="0 0 8 6" aria-hidden="true"><path d="M1 5l3-3.4L7 5" stroke="currentColor" stroke-width="1.2" fill="none"/></svg>
                        </button>
                        <button type="button" aria-label="{{ $say('Decrease', 'کاهش') }}"
                            x-on:pointerdown="hold(-1)" x-on:pointerup="stop()" x-on:pointerleave="stop()" x-on:pointercancel="stop()">
                            <svg width="8" height="6" viewBox="0 0 8 6" aria-hidden="true"><path d="M1 1l3 3.4L7 1" stroke="currentColor" stroke-width="1.2" fill="none"/></svg>
                        </button>
                    </span>
                </span>
            </div>
        </div>
    </div>
</section>
