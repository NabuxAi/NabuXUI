{{--
    The Material 3 slider staged as an audio-quality screen: a hollow handle
    4 px off the rail, a bubble value label that rises only while dragging, a
    stepped slider with tick dots, a two-handle range, live Persian-digit
    values and the official end icons.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    .m3sld-root {
        --m3sld-primary: #6750A4; --m3sld-on-primary: #FFFFFF;
        --m3sld-primary-container: #EADDFF; --m3sld-on-primary-container: #21005D;
        --m3sld-surface: #FEF7FF; --m3sld-surface-container: #F3EDF7;
        --m3sld-surface-container-high: #ECE6F0; --m3sld-surface-container-highest: #E6E0E9;
        --m3sld-on-surface: #1D1B20; --m3sld-on-surface-variant: #49454F;
        --m3sld-outline-variant: #CAC4D0;
        --m3sld-inverse-surface: #322F35; --m3sld-inverse-on-surface: #F5EFF7;
        --m3sld-ease: cubic-bezier(.2, 0, 0, 1);
        font-family: Roboto, system-ui, sans-serif;
        display: grid; gap: 1.5rem; justify-items: center;
    }
    html[data-theme="dark"] .m3sld-root {
        --m3sld-primary: #D0BCFF; --m3sld-on-primary: #381E72;
        --m3sld-primary-container: #4F378B; --m3sld-on-primary-container: #EADDFF;
        --m3sld-surface: #141218; --m3sld-surface-container: #211F26;
        --m3sld-surface-container-high: #2B2930; --m3sld-surface-container-highest: #36343B;
        --m3sld-on-surface: #E6E0E9; --m3sld-on-surface-variant: #CAC4D0;
        --m3sld-outline-variant: #49454F;
        --m3sld-inverse-surface: #E6E0E9; --m3sld-inverse-on-surface: #1D1B20;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .m3sld-root {
            --m3sld-primary: #D0BCFF; --m3sld-on-primary: #381E72;
            --m3sld-primary-container: #4F378B; --m3sld-on-primary-container: #EADDFF;
            --m3sld-surface: #141218; --m3sld-surface-container: #211F26;
            --m3sld-surface-container-high: #2B2930; --m3sld-surface-container-highest: #36343B;
            --m3sld-on-surface: #E6E0E9; --m3sld-on-surface-variant: #CAC4D0;
            --m3sld-outline-variant: #49454F;
            --m3sld-inverse-surface: #E6E0E9; --m3sld-inverse-on-surface: #1D1B20;
        }
    }
    .m3sld-frame { inline-size: min(100%, 22rem); padding: .625rem; border-radius: 3rem; background: #17171b; box-shadow: 0 24px 48px -24px rgba(0, 0, 0, .5); }
    .m3sld-screen { position: relative; overflow: clip; block-size: 27rem; border-radius: 2.5rem; background: var(--m3sld-surface); display: flex; flex-direction: column; }
    .m3sld-status { display: flex; align-items: center; justify-content: space-between; padding: .8rem 1.4rem .2rem; color: var(--m3sld-on-surface); font: 600 .72rem/1 Roboto, system-ui, sans-serif; }
    .m3sld-head { display: flex; align-items: center; gap: .6rem; padding: .55rem 1.1rem .3rem; }
    .m3sld-head h4 { margin: 0; font: 500 1.2rem/1.2 Roboto, system-ui, sans-serif; color: var(--m3sld-on-surface); }
    .m3sld-body { flex: 1; display: grid; align-content: start; gap: .35rem; padding: .4rem 1.25rem 1.25rem; overflow-y: auto; }
    .m3sld-block { display: grid; gap: .1rem; padding-block: .55rem; }
    .m3sld-block + .m3sld-block { border-block-start: 1px solid var(--m3sld-outline-variant); }
    .m3sld-titles { display: flex; align-items: baseline; gap: .5rem; }
    .m3sld-titles b { font: 500 .88rem/1.3 Roboto, system-ui, sans-serif; color: var(--m3sld-on-surface); }
    .m3sld-titles output { margin-inline-start: auto; font: 500 .8rem/1 Roboto, system-ui, sans-serif; color: var(--m3sld-primary); font-variant-numeric: tabular-nums; }
    .m3sld-row { position: relative; padding-block: 1.5rem 1.1rem; }
    .m3sld-row.m3sld-bubbling { padding-block: 1.9rem 1.1rem; }
    .m3sld-rail { position: absolute; inset-inline: 0; inset-block-start: 50%; translate: 0 -50%; display: flex; align-items: center; }
    .m3sld-way { position: relative; flex: 1; block-size: 1rem; border-radius: 999px; background: var(--m3sld-surface-container-highest); }
    .m3sld-fill { position: absolute; inset-block: 0; inset-inline-start: 0; border-radius: 999px; background: var(--m3sld-primary); }
    .m3sld-stop { flex: none; inline-size: 4px; aspect-ratio: 1; margin-inline-start: 4px; border-radius: 50%; background: var(--m3sld-primary); }
    .m3sld-ticks { position: absolute; inset-inline: 0; inset-block-start: 50%; translate: 0 -50%; display: flex; justify-content: space-between; padding-inline: 2px; pointer-events: none; }
    .m3sld-ticks i { inline-size: 4px; aspect-ratio: 1; border-radius: 50%; background: color-mix(in srgb, var(--m3sld-on-surface-variant) 55%, transparent); }
    .m3sld-ticks i.on { background: var(--m3sld-primary); }
    .m3sld input[type='range'] { position: absolute; inset-inline: 0; inset-block-start: 50%; translate: 0 -50%; inline-size: 100%; block-size: 2.75rem; margin: 0; appearance: none; -webkit-appearance: none; background: transparent; outline: none; }
    .m3sld input[type='range']::-webkit-slider-runnable-track { block-size: 2.75rem; background: transparent; }
    .m3sld input[type='range']::-moz-range-track { block-size: 2.75rem; background: transparent; }
    .m3sld input[type='range']::-webkit-slider-thumb { -webkit-appearance: none; appearance: none; inline-size: 1.25rem; aspect-ratio: 1; margin-block-start: calc((2.75rem - 1.25rem) / 2); border-radius: 50%; border: 2px solid var(--m3sld-primary); background: var(--m3sld-surface); transition: scale .15s var(--m3sld-ease); }
    .m3sld input[type='range']::-moz-range-thumb { inline-size: 1.25rem; aspect-ratio: 1; border-radius: 50%; border: 2px solid var(--m3sld-primary); background: var(--m3sld-surface); transition: scale .15s var(--m3sld-ease); }
    .m3sld input[type='range']:active::-webkit-slider-thumb { scale: 1.35; }
    .m3sld input[type='range']:active::-moz-range-thumb { scale: 1.35; }
    .m3sld input[type='range']:focus-visible { outline: 2px solid var(--m3sld-primary); outline-offset: 8px; border-radius: 999px; }
    .m3sld input.m3sld-under { pointer-events: none; }
    .m3sld input.m3sld-under::-webkit-slider-thumb { pointer-events: auto; }
    .m3sld input.m3sld-under::-moz-range-thumb { pointer-events: auto; }
    [x-cloak] { display: none !important; }
    /* The demo page's "Important props" table (rendered by the demo template,
       not this partial) reveals its rows on scroll: rows sit at opacity: 0
       until an IntersectionObserver marks the wrapper, and full-page captures
       never scroll — so the table was photographed with headers over an empty
       body. Keep this page's rows visible, and let cells wrap so the fourth
       column no longer runs off a 375px screen (unlayered rules beat the
       library's @layer nx.components cascade). */
    .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr { opacity: 1; translate: none; }
    .nx-data-table th, .nx-data-table td { white-space: normal; }
    @media (max-width: 30rem) {
        .nx-data-table th, .nx-data-table td { padding-inline: .5rem; }
    }
    .m3sld-bubble { position: absolute; inset-block-start: -.45rem; translate: -50% 0; padding: .3rem .6rem; min-inline-size: 1.9rem; border-radius: 999px; background: var(--m3sld-inverse-surface); color: var(--m3sld-inverse-on-surface); font: 500 .78rem/1 Roboto, system-ui, sans-serif; text-align: center; font-variant-numeric: tabular-nums; pointer-events: none; transition: opacity .15s var(--m3sld-ease), translate .15s var(--m3sld-ease); }
    .m3sld-icons { display: flex; align-items: center; gap: .9rem; color: var(--m3sld-on-surface-variant); }
    .m3sld-icons svg { flex: none; }
    .m3sld-note { margin: .2rem 0 0; font: 400 .78rem/1.5 Roboto, system-ui, sans-serif; color: var(--m3sld-on-surface-variant); }
    .m3sld-spec { inline-size: min(100%, 30rem); display: grid; gap: 1.35rem; }
    @media (prefers-reduced-motion: reduce) {
        .m3sld-root * { animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
    }
</style>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Audio quality, by hand', 'کیفیت صدا، با دست') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('Drag the hollow handle — the bubble rises only while you drag, the stepped rail ticks in fives, and the range answers both of your fingers.', 'دستگیرهٔ توخالی را بکشید — حباب فقط حین کشیدن بالا می‌آید، ریل پله‌ای پنج‌تا‌پنج‌تا تیک می‌خورد و اسلایدر بازه به هر دو انگشت شما جواب می‌دهد.') }}
        </p>
    </div>

    <div class="m3sld-root" x-data="{
            fa: {{ $fa ? 'true' : 'false' }},
            v: 40, step: 15, lo: 25, hi: 75, act: '',
            fd(n) { return this.fa ? String(n).replace(/[0-9]/g, d => '۰۱۲۳۴۵۶۷۸۹'[+d]) : String(n) },
            pct(x, min, max) { return Math.round((x - min) / (max - min) * 100) },
        }"
        x-on:pointerup.window="act = ''"
        x-on:pointercancel.window="act = ''">
        <div class="m3sld-frame">
            <div class="m3sld-screen">
                <div class="m3sld-status" aria-hidden="true">
                    <span>{{ $say('9:41', '۰۹:۴۱') }}</span>
                    <svg width="44" height="12" viewBox="0 0 44 12" fill="currentColor"><rect x="0" y="7" width="3" height="5" rx="1"/><rect x="5" y="5" width="3" height="7" rx="1"/><rect x="10" y="3" width="3" height="9" rx="1"/><rect x="15" y="1" width="3" height="11" rx="1"/><rect x="35" y="2" width="8" height="8" rx="2" opacity=".35"/><rect x="36.5" y="3.5" width="4" height="5" rx="1"/></svg>
                </div>
                <div class="m3sld-head">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" style="color: var(--m3sld-on-surface)"><path d="M4 9v6M8 5v14M12 8v8M16 4v16M20 10v4"/></svg>
                    <h4>{{ $say('Audio quality', 'کیفیت صدا') }}</h4>
                </div>

                <div class="m3sld-body">
                    <div class="m3sld-block">
                        <div class="m3sld-titles">
                            <b>{{ $say('Bitrate', 'بیت‌ریت') }}</b>
                            <output x-text="fd(v) + ' {{ $say('kb/s', 'کیلوبیت/ثانیه') }}'">۴۰</output>
                        </div>
                        <div class="m3sld-row m3sld-bubbling">
                            <span class="m3sld-rail" aria-hidden="true">
                                <span class="m3sld-way"><span class="m3sld-fill" x-bind:style="{ inlineSize: pct(v, 0, 100) + '%' }" style="inline-size: 40%"></span></span>
                                <span class="m3sld-stop"></span>
                            </span>
                            <span class="m3sld-bubble" x-show="act === 'v'" x-cloak
                                x-bind:style="{ insetInlineStart: pct(v, 0, 100) + '%', opacity: act === 'v' ? 1 : 0 }"
                                x-text="fd(v)"></span>
                            <label class="m3sld-icons">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 5 6 9H3v6h3l5 4z"/></svg>
                                <input type="range" min="0" max="100" step="1" x-model="v" x-on:pointerdown="act = 'v'" aria-label="{{ $say('Bitrate', 'بیت‌ریت') }}">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 5 6 9H3v6h3l5 4z"/><path d="M15.5 8.5a5 5 0 0 1 0 7M18.5 5.5a9 9 0 0 1 0 13"/></svg>
                            </label>
                        </div>
                    </div>

                    <div class="m3sld-block">
                        <div class="m3sld-titles">
                            <b>{{ $say('Gain', 'تقویت صدا') }}</b>
                            <output x-text="'+' + fd(step)">+۱۵</output>
                        </div>
                        <div class="m3sld-row m3sld-bubbling">
                            <span class="m3sld-rail" aria-hidden="true">
                                <span class="m3sld-way">
                                    <span class="m3sld-ticks">
                                        <template x-for="t in [0,5,10,15,20,25,30,35,40,45,50]" :key="t">
                                            <i x-bind:class="{ on: step >= t }"></i>
                                        </template>
                                    </span>
                                    <span class="m3sld-fill" x-bind:style="{ inlineSize: pct(step, 0, 50) + '%' }" style="inline-size: 30%"></span>
                                </span>
                                <span class="m3sld-stop"></span>
                            </span>
                            <span class="m3sld-bubble" x-show="act === 'step'"
                                x-bind:style="{ insetInlineStart: pct(step, 0, 50) + '%', opacity: act === 'step' ? 1 : 0 }"
                                x-text="'+' + fd(step)"></span>
                            <input type="range" min="0" max="50" step="5" x-model="step" x-on:pointerdown="act = 'step'" aria-label="{{ $say('Gain', 'تقویت صدا') }}">
                        </div>
                        <p class="m3sld-note">{{ $say('Steps of five — arrow keys move it too.', 'گام‌های پنج‌تایی — با فلش‌های کیبورد هم جابه‌جا می‌شود.') }}</p>
                    </div>

                    <div class="m3sld-block">
                        <div class="m3sld-titles">
                            <b>{{ $say('Frequency range', 'بازهٔ فرکانسی') }}</b>
                            <output x-text="fd(lo) + ' – ' + fd(hi)">۲۵ – ۷۵</output>
                        </div>
                        <div class="m3sld-row m3sld-bubbling">
                            <span class="m3sld-rail" aria-hidden="true">
                                <span class="m3sld-way">
                                    <span class="m3sld-fill" x-bind:style="{ insetInlineStart: lo + '%', inlineSize: (hi - lo) + '%' }" style="inset-inline-start: 25%; inline-size: 50%"></span>
                                </span>
                                <span class="m3sld-stop"></span>
                            </span>
                            <span class="m3sld-bubble" x-show="act === 'lo'"
                                x-bind:style="{ insetInlineStart: lo + '%', opacity: act === 'lo' ? 1 : 0 }"
                                x-text="fd(lo)"></span>
                            <span class="m3sld-bubble" x-show="act === 'hi'"
                                x-bind:style="{ insetInlineStart: hi + '%', opacity: act === 'hi' ? 1 : 0 }"
                                x-text="fd(hi)"></span>
                            <input type="range" min="0" max="100" x-model="lo" x-on:pointerdown="act = 'lo'"
                                x-bind:style="{ zIndex: lo > 50 ? 4 : 2 }" class="m3sld-under" aria-label="{{ $say('Low end', 'مرز پایین') }}">
                            <input type="range" min="0" max="100" x-model="hi" x-on:pointerdown="act = 'hi'"
                                style="z-index: 3" class="m3sld-under" aria-label="{{ $say('High end', 'مرز بالا') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The parts, naked', 'قطعه‌ها، برهنه') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('The signature 4 px gap between handle and rail, the stop dot at the end of the track, and the value bubble on Material’s emphasized curve.', 'گپ امضادار ۴ پیکسلی میان دستگیره و ریل، نقطهٔ توقف انتهای ریل و حباب مقدار روی منحنی تأکیدی متریال.') }}
        </p>
    </div>
    <div class="m3sld-root" x-data="{
            fa: {{ $fa ? 'true' : 'false' }},
            v: 60, act: '',
            fd(n) { return this.fa ? String(n).replace(/[0-9]/g, d => '۰۱۲۳۴۵۶۷۸۹'[+d]) : String(n) },
            pct(x) { return Math.round(x) + '%' },
        }"
        x-on:pointerup.window="act = ''"
        x-on:pointercancel.window="act = ''">
        <div class="m3sld-spec">
            <div class="m3sld-row m3sld-bubbling" style="min-inline-size: min(100%, 20rem)">
                <span class="m3sld-rail" aria-hidden="true">
                    <span class="m3sld-way"><span class="m3sld-fill" x-bind:style="{ inlineSize: pct(v) }" style="inline-size: 60%"></span></span>
                    <span class="m3sld-stop"></span>
                </span>
                <span class="m3sld-bubble" x-show="act === 'v'"
                    x-bind:style="{ insetInlineStart: pct(v), opacity: act === 'v' ? 1 : 0 }"
                    x-text="fd(v)"></span>
                <input type="range" min="0" max="100" x-model="v" x-on:pointerdown="act = 'v'" aria-label="{{ $say('Specimen slider', 'اسلایدر نمونه') }}">
            </div>
            <p class="m3sld-note" style="text-align: center" x-text="'{{ $say('Value', 'مقدار') }}: ' + fd(v)"></p>
        </div>
    </div>
</section>
