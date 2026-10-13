{{--
    Classic iOS controls in a settings-style stack inside a phone frame: the
    switch springs between states, the slider's rail fattens under the finger,
    the stepper counts, the segmented thumb glides beneath the glyph and the
    page-control dots stay synced to a snap carousel.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<style>
    .ioctl-root {
        --ios-blue: #007AFF; --ios-green: #34C759; --ios-red: #FF3B30; --ios-orange: #FF9500;
        --ios-teal: #5AC8FA; --ios-indigo: #5856D6; --ios-gray: #8E8E93;
        --ios-label: #000000; --ios-label-2: rgba(60, 60, 67, .6); --ios-label-3: rgba(60, 60, 67, .3);
        --ios-fill: rgba(120, 120, 128, .2); --ios-sep: rgba(60, 60, 67, .29);
        --ios-bg: #F2F2F7; --ios-card: #FFFFFF; --ios-gray5: #E5E5EA;
        font-family: -apple-system, system-ui, 'Segoe UI', sans-serif;
        color: var(--ios-label);
    }
    html[data-theme="dark"] .ioctl-root {
        --ios-blue: #0A84FF; --ios-green: #30D158; --ios-red: #FF453A; --ios-orange: #FF9F0A;
        --ios-teal: #64D2FF; --ios-indigo: #5E5CE6; --ios-gray: #8E8E93;
        --ios-label: #FFFFFF; --ios-label-2: rgba(235, 235, 245, .6); --ios-label-3: rgba(235, 235, 245, .3);
        --ios-fill: rgba(120, 120, 128, .36); --ios-sep: rgba(84, 84, 88, .6);
        --ios-bg: #000000; --ios-card: #1C1C1E; --ios-gray5: #2C2C2E;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .ioctl-root {
            --ios-blue: #0A84FF; --ios-green: #30D158; --ios-red: #FF453A; --ios-orange: #FF9F0A;
            --ios-teal: #64D2FF; --ios-indigo: #5E5CE6; --ios-gray: #8E8E93;
            --ios-label: #FFFFFF; --ios-label-2: rgba(235, 235, 245, .6); --ios-label-3: rgba(235, 235, 245, .3);
            --ios-fill: rgba(120, 120, 128, .36); --ios-sep: rgba(84, 84, 88, .6);
            --ios-bg: #000000; --ios-card: #1C1C1E; --ios-gray5: #2C2C2E;
        }
    }
    .ioctl-root :focus-visible { outline: 2px solid var(--ios-blue); outline-offset: 2px; }
    /* The shell ships no .sr-only utility: without this the switches' accessible
       labels render as ghost text beside the knob and collide with the row. */
    .ioctl-root .sr-only { position: absolute; inline-size: 1px; block-size: 1px; margin: -1px; padding: 0; border: 0; overflow: clip; clip-path: inset(50%); white-space: nowrap; }

    /* The phone is a size container: the screen below draws its settings UI on
       a fixed 352px canvas (the screen's full width) and scales that canvas
       to the real screen width, so rows never squeeze the switch onto the
       label on narrow stages — the whole screen just renders proportionally
       smaller, like a real device shot. */
    .ioctl-phone { position: relative; container-type: inline-size; inline-size: min(100%, 22rem); margin-inline: auto; padding: .55rem; border-radius: 2.9rem;
                   background: #101014; box-shadow: 0 24px 60px rgba(0, 0, 0, .28), inset 0 0 0 1px rgba(255, 255, 255, .12); }
    .ioctl-screen { position: relative; overflow: clip; container-type: inline-size; display: flex; flex-direction: column;
                    aspect-ratio: 352 / 608; border-radius: 2.4rem; background: var(--ios-bg); }
    .ioctl-canvas { position: absolute; inset-block-start: 0; inset-inline-start: 0; display: flex; flex-direction: column; inline-size: 352px; block-size: 608px;
                    scale: tan(atan2(100cqw, 352px)); transform-origin: top left; }
    [dir="rtl"] .ioctl-canvas { transform-origin: top right; }
    .ioctl-pill { position: absolute; inset-block-start: .55rem; left: 50%; translate: -50% 0; inline-size: 5.2rem; block-size: 1.45rem;
                  border-radius: 999px; background: #101014; z-index: 3; }
    .ioctl-status { display: flex; align-items: center; justify-content: space-between; padding: .75rem 1.6rem .3rem; font: 600 .95rem/1.2 -apple-system, system-ui, sans-serif; }
    .ioctl-status-end { display: flex; align-items: center; gap: .35rem; }
    .ioctl-status-end .bars { display: flex; align-items: flex-end; gap: 1.5px; }
    .ioctl-status-end .bars i { inline-size: 3px; border-radius: 1px; background: var(--ios-label); }
    .ioctl-status-end .bars i:nth-child(1) { block-size: 4px; } .ioctl-status-end .bars i:nth-child(2) { block-size: 6px; }
    .ioctl-status-end .bars i:nth-child(3) { block-size: 8px; } .ioctl-status-end .bars i:nth-child(4) { block-size: 10px; opacity: .35; }
    .ioctl-batt { position: relative; inline-size: 23px; block-size: 11px; border: 1px solid var(--ios-label-3); border-radius: 3.5px; }
    .ioctl-batt::before { content: ''; position: absolute; inset: 1.5px; inset-inline-end: 5px; border-radius: 2px; background: var(--ios-label); }
    .ioctl-batt::after { content: ''; position: absolute; inset-block: 3px; inset-inline-end: -3px; inline-size: 2px; border-radius: 0 2px 2px 0; background: var(--ios-label-3); }
    .ioctl-body { flex: 1; overflow-y: auto; display: grid; gap: .9rem; align-content: start; padding: 1rem .9rem 1rem; }
    .ioctl-home { inline-size: 34%; block-size: 5px; margin: .1rem auto .55rem; border-radius: 999px; background: var(--ios-label); opacity: .8; }

    .ioctl-card { background: var(--ios-card); border-radius: 12px; overflow: clip; }
    .ioctl-card h4 { margin: 0; padding: .8rem .9rem .3rem; font: 500 .8rem/1.4 -apple-system, system-ui, sans-serif; color: var(--ios-label-2); text-transform: uppercase; }
    .ioctl-row { display: flex; align-items: center; gap: .7rem; min-block-size: 44px; padding: .5rem .9rem; }
    .ioctl-row + .ioctl-row { border-block-start: .5px solid var(--ios-sep); }
    .ioctl-ic { display: grid; place-items: center; flex: none; inline-size: 30px; block-size: 30px; border-radius: 7px; color: #fff; }
    .ioctl-name { font-size: 1rem; }
    .ioctl-end { display: flex; align-items: center; gap: .45rem; margin-inline-start: auto; font-size: .9rem; color: var(--ios-label-2); }
    .ioctl-chev { display: inline-block; color: var(--ios-label-3); font-size: 1.05rem; line-height: 1; }
    [dir="rtl"] .ioctl-chev { transform: scaleX(-1); }

    .ioctl-switch { position: relative; display: inline-block; flex: none; inline-size: 51px; block-size: 31px; }
    .ioctl-switch input { position: absolute; inset: 0; z-index: 1; margin: 0; opacity: 0; cursor: pointer; }
    .ioctl-track { position: absolute; inset: 0; border-radius: 999px; background: var(--ios-fill); transition: background .25s ease; }
    .ioctl-knob { position: absolute; inset-block-start: 2px; inset-inline-start: 2px; inline-size: 27px; aspect-ratio: 1; border-radius: 50%;
                  background: #fff; box-shadow: 0 3px 8px rgba(0, 0, 0, .16), 0 3px 1px rgba(0, 0, 0, .06);
                  transition: translate .35s cubic-bezier(.3, .9, .4, 1.15); }
    .ioctl-switch input:checked ~ .ioctl-track { background: var(--ios-green); }
    .ioctl-switch input:checked ~ .ioctl-knob { translate: 20px 0; }
    [dir="rtl"] .ioctl-switch input:checked ~ .ioctl-knob { translate: -20px 0; }
    .ioctl-switch input:focus-visible ~ .ioctl-track { outline: 2px solid var(--ios-blue); outline-offset: 2px; }

    .ioctl-slider { --dir: 90deg; appearance: none; -webkit-appearance: none; inline-size: 100%; block-size: 28px; margin: 0; background: transparent; cursor: pointer; }
    [dir="rtl"] .ioctl-slider { --dir: 270deg; }
    .ioctl-slider::-webkit-slider-runnable-track { block-size: 4px; border-radius: 999px;
        background: linear-gradient(var(--dir), var(--ios-blue) var(--ioctl-fill, 50%), var(--ios-fill) 0); transition: block-size .2s ease; }
    .ioctl-slider::-webkit-slider-thumb { -webkit-appearance: none; inline-size: 28px; block-size: 28px; margin-block-start: -12px;
        border-radius: 50%; background: #fff; box-shadow: 0 1px 4px rgba(0, 0, 0, .3), 0 0 0 .5px rgba(0, 0, 0, .04); transition: margin-block-start .2s ease; }
    .ioctl-slider:active::-webkit-slider-runnable-track { block-size: 10px; }
    .ioctl-slider:active::-webkit-slider-thumb { margin-block-start: -9px; }
    .ioctl-slider::-moz-range-track { block-size: 4px; border-radius: 999px;
        background: linear-gradient(var(--dir), var(--ios-blue) var(--ioctl-fill, 50%), var(--ios-fill) 0); transition: block-size .2s ease; }
    .ioctl-slider::-moz-range-thumb { inline-size: 28px; block-size: 28px; border: none; border-radius: 50%; background: #fff; box-shadow: 0 1px 4px rgba(0, 0, 0, .3); }
    .ioctl-slider:active::-moz-range-track { block-size: 10px; }

    .ioctl-stepper { display: inline-flex; align-items: stretch; border-radius: 8px; background: var(--ios-fill); overflow: clip; }
    .ioctl-stepper button { inline-size: 44px; block-size: 32px; font-size: 1.2rem; color: var(--ios-label); }
    .ioctl-stepper button:active:not(:disabled) { background: rgba(120, 120, 128, .22); }
    .ioctl-stepper button:disabled { opacity: .35; cursor: default; }
    .ioctl-stepper output { display: grid; place-items: center; min-inline-size: 3.2rem; padding-inline: .5rem; border-inline: 1px solid var(--ios-sep); font-size: .9rem; }

    .ioctl-seg { position: relative; display: grid; grid-template-columns: repeat(3, 1fr); margin: .4rem .9rem .9rem; border-radius: 9px; background: var(--ios-fill); }
    .ioctl-seg-thumb { position: absolute; inset-block: 2px; inset-inline-start: 2px; inline-size: calc((100% - 4px) / 3); border-radius: 7px;
        background: var(--ios-card); box-shadow: 0 1px 4px rgba(0, 0, 0, .14), 0 0 0 .5px rgba(0, 0, 0, .04);
        transition: translate .45s cubic-bezier(.32, .72, .35, 1.18); }
    .ioctl-seg button { position: relative; z-index: 1; block-size: 30px; border-radius: 7px; font-size: .82rem; font-weight: 500; color: var(--ios-label-2); }
    .ioctl-seg button[aria-pressed="true"] { color: var(--ios-label); font-weight: 600; }

    .ioctl-car { display: flex; overflow-x: auto; scroll-snap-type: x mandatory; scrollbar-width: none; }
    .ioctl-car::-webkit-scrollbar { display: none; }
    .ioctl-car figure { flex: 0 0 100%; display: grid; align-content: end; gap: .15rem; margin: 0; padding: .8rem; aspect-ratio: 16/9;
        border-radius: 10px; scroll-snap-align: center; color: #fff; }
    .ioctl-car figure:nth-child(1) { background: linear-gradient(135deg, #FF9500, #FF3B30 70%); }
    .ioctl-car figure:nth-child(2) { background: linear-gradient(135deg, #5856D6, #0A2A6B 75%); }
    .ioctl-car figure:nth-child(3) { background: linear-gradient(135deg, #30D158, #007AFF 80%); }
    .ioctl-car figcaption b { font-size: 1rem; } .ioctl-car figcaption span { font-size: .78rem; opacity: .85; }
    .ioctl-dots { display: flex; justify-content: center; gap: .45rem; padding: .7rem 0 .8rem; }
    .ioctl-dot { inline-size: .45rem; block-size: .45rem; border-radius: 999px; background: var(--ios-label-3);
                 transition: inline-size .3s ease, background .3s ease; }
    .ioctl-dot[aria-current="true"] { inline-size: 1.4rem; background: var(--ios-label-2); }

    .ioctl-tap { transition: scale .12s ease; } .ioctl-tap:active { scale: .96; }
    @media (prefers-reduced-motion: reduce) {
        .ioctl-knob, .ioctl-seg-thumb, .ioctl-dot, .ioctl-tap { transition: none; }
        .ioctl-slider::-webkit-slider-runnable-track, .ioctl-slider::-webkit-slider-thumb, .ioctl-slider::-moz-range-track { transition: none; }
    }
</style>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Classic controls, real physics', 'کنترل‌های کلاسیک، فیزیک واقعی') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('A settings stack with the official iOS controls: flip a switch to feel the spring, drag the slider and watch the 4px rail fatten to 10px under your finger, tap a segment to see the thumb glide beneath the glyph, and swipe the album row — the page-control dots follow.', 'پشته‌ای از کنترل‌های رسمی آی‌او‌اس: سوییچ را بزنید تا فنرش را حس کنید، اسلایدر را بکشید تا ریل ۴ پیکسلی زیر انگشت به ۱۰ پیکسل ضخیم شود، سگمنت را عوض کنید تا انگشت انتخاب زیر گلیف بلغزد، و ردیف آلبوم‌ها را ورق بزنید — نقطه‌های پیج‌کنترل دنبال می‌کنند.') }}
        </p>
    </div>

    <div class="ioctl-root" x-data="{
            get rtl() { return document.documentElement.dir === 'rtl' },
            wifi: true, notif: true, focus: false,
            vol: 60, guests: 2, seg: 1, page: 0,
            faN(n) { return this.rtl ? String(n).replace(/[0-9]/g, d => '۰۱۲۳۴۵۶۷۸۹'[+d]) : String(n) },
            segOff() { return (this.rtl ? -this.seg : this.seg) * 100 },
            goPage(i) { this.page = i; const el = this.$refs.car; el.scrollTo({ left: this.rtl ? -i * el.clientWidth : i * el.clientWidth, behavior: 'smooth' }) },
            onCar() { const el = this.$refs.car; const pos = this.rtl ? -el.scrollLeft : el.scrollLeft;
                      this.page = Math.min(2, Math.max(0, Math.round(pos / el.clientWidth))) },
        }">
            <div class="ioctl-phone">
                <div class="ioctl-screen">
                    <div class="ioctl-canvas">
                        <span class="ioctl-pill" aria-hidden="true"></span>
                    <div class="ioctl-status" aria-hidden="true">
                        <b>{{ $say('9:41', '۰۹:۴۱') }}</b>
                        <span class="ioctl-status-end">
                            <span class="bars"><i></i><i></i><i></i><i></i></span>
                            <span class="ioctl-batt"></span>
                        </span>
                    </div>

                    <div class="ioctl-body">
                        <div class="ioctl-card">
                            <div class="ioctl-row">
                                <span class="ioctl-ic" style="background: var(--ios-blue)" aria-hidden="true">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M1.5 5.5a9.2 9.2 0 0 1 13 0M4 8.2a5.7 5.7 0 0 1 8 0M6.5 10.9a2.4 2.4 0 0 1 3 0"/><circle cx="8" cy="13.4" r="1" fill="currentColor" stroke="none"/></svg>
                                </span>
                                <span class="ioctl-name">{{ $say('Wi-Fi', 'وای‌فای') }}</span>
                                <span class="ioctl-end">
                                    <span>NabuXUI-5G</span>
                                    <span class="ioctl-chev" aria-hidden="true">›</span>
                                </span>
                            </div>
                            <div class="ioctl-row">
                                <span class="ioctl-ic" style="background: var(--ios-red)" aria-hidden="true">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M8 1.8a4.2 4.2 0 0 1 4.2 4.2c0 3 .8 4 1.3 4.6H2.5c.5-.6 1.3-1.6 1.3-4.6A4.2 4.2 0 0 1 8 1.8ZM6.4 13.2a1.7 1.7 0 0 0 3.2 0"/></svg>
                                </span>
                                <span class="ioctl-name">{{ $say('Notifications', 'اعلان‌ها') }}</span>
                                <label class="ioctl-switch ioctl-tap">
                                    <input type="checkbox" checked x-model.boolean="notif">
                                    <span class="ioctl-track"></span>
                                    <span class="ioctl-knob"></span>
                                    <span class="sr-only">{{ $say('Notifications', 'اعلان‌ها') }}</span>
                                </label>
                            </div>
                            <div class="ioctl-row">
                                <span class="ioctl-ic" style="background: var(--ios-indigo)" aria-hidden="true">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"><circle cx="8" cy="6.4" r="2.1"/><circle cx="3.4" cy="7.4" r="1.4"/><circle cx="12.6" cy="7.4" r="1.4"/><path d="M1.8 12.6c.6-2 2.3-3 4.1-2.4M14.2 12.6c-.6-2-2.3-3-4.1-2.4M4.6 13.4c.5-1.9 1.9-3 3.4-3s2.9 1.1 3.4 3"/></svg>
                                </span>
                                <span class="ioctl-name">{{ $say('Focus mode', 'حالت تمرکز') }}</span>
                                <label class="ioctl-switch ioctl-tap">
                                    <input type="checkbox" x-model.boolean="focus">
                                    <span class="ioctl-track"></span>
                                    <span class="ioctl-knob"></span>
                                    <span class="sr-only">{{ $say('Focus mode', 'حالت تمرکز') }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="ioctl-card">
                            <h4>{{ $say('Volume', 'بلندی صدا') }}</h4>
                            <div class="ioctl-row" style="align-items: flex-start; flex-direction: column; gap: .2rem">
                                <div style="display: flex; inline-size: 100%; justify-content: space-between; font-size: .9rem; color: var(--ios-label-2)">
                                    <span>{{ $say('Output level', 'تراز خروجی') }}</span>
                                    <span x-text="faN(vol) + (rtl ? '٪' : '%')" style="font-variant-numeric: tabular-nums; color: var(--ios-label)">۶۰٪</span>
                                </div>
                                <input type="range" class="ioctl-slider" min="0" max="100" value="60" x-on:input="vol = +$el.value"
                                       :style="'--ioctl-fill: ' + vol + '%'" :aria-label="$say('Output level', 'تراز خروجی')">
                            </div>
                        </div>

                        <div class="ioctl-card">
                            <div class="ioctl-row">
                                <span class="ioctl-ic" style="background: var(--ios-orange)" aria-hidden="true">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M5 14V7.5L8.6 2c.9.4 1.4 1.3 1.2 2.3L9.2 6.6h3.4c.9 0 1.6.9 1.4 1.8l-1 4.8c-.1.7-.7 1.2-1.4 1.2H5ZM5 14H2.8V7.5H5"/></svg>
                                </span>
                                <span class="ioctl-name">{{ $say('Guests', 'مهمان‌ها') }}</span>
                                <span class="ioctl-end">
                                    <span class="ioctl-stepper ioctl-tap">
                                        <button type="button" x-on:click="guests = Math.max(1, guests - 1)" :disabled="guests <= 1"
                                                :aria-label="$say('One guest fewer', 'یک مهمان کمتر')">−</button>
                                        <output aria-live="polite" x-text="faN(guests)">۲</output>
                                        <button type="button" x-on:click="guests = Math.min(6, guests + 1)" :disabled="guests >= 6"
                                                :aria-label="$say('One guest more', 'یک مهمان بیشتر')">+</button>
                                    </span>
                                </span>
                            </div>
                        </div>

                        <div class="ioctl-card">
                            <h4>{{ $say('Listening time', 'زمان گوش‌دادن') }}</h4>
                            <div class="ioctl-seg ioctl-tap" role="group" :aria-label="$say('Listening time', 'زمان گوش‌دادن')">
                                <span class="ioctl-seg-thumb" aria-hidden="true" :style="'translate: ' + segOff() + '% 0'"></span>
                                <button type="button" :aria-pressed="seg === 0" x-on:click="seg = 0">{{ $say('Today', 'امروز') }}</button>
                                <button type="button" :aria-pressed="seg === 1" x-on:click="seg = 1">{{ $say('This week', 'این هفته') }}</button>
                                <button type="button" :aria-pressed="seg === 2" x-on:click="seg = 2">{{ $say('This month', 'این ماه') }}</button>
                            </div>
                        </div>

                        <div class="ioctl-card">
                            <h4>{{ $say('Recent albums', 'آلبوم‌های اخیر') }}</h4>
                            <div style="padding-inline: .55rem">
                                <div class="ioctl-car" x-ref="car" x-on:scroll.passive="onCar()">
                                    <figure>
                                        <figcaption><b>{{ $say('Istanbul Alleys', 'کوچه‌های استانبول') }}</b><br><span>{{ $say('Bachar Choir · 2025', 'کر باچار · ۲۰۲۵') }}</span></figcaption>
                                    </figure>
                                    <figure>
                                        <figcaption><b>{{ $say('Rainy Night', 'شبِ باران') }}</b><br><span>{{ $say('Anna & Omar · 2024', 'هما و رضا · ۲۰۲۴') }}</span></figcaption>
                                    </figure>
                                    <figure>
                                        <figcaption><b>{{ $say('Southern Sea', 'دریای جنوب') }}</b><br><span>{{ $say('Bandari Nights · 2023', 'شب‌های بندری · ۲۰۲۳') }}</span></figcaption>
                                    </figure>
                                </div>
                            </div>
                            <div class="ioctl-dots">
                                @foreach ([0, 1, 2] as $i)
                                    <button type="button" class="ioctl-dot ioctl-tap" x-on:click="goPage({{ $i }})"
                                            :aria-current="page === {{ $i }} ? 'true' : null"
                                            aria-label="{{ $say('Go to album ' . ($i + 1), 'برو به آلبوم ' . strtr((string) ($i + 1), ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳'])) }}"></button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                        <div class="ioctl-home" aria-hidden="true"></div>
                    </div>
                </div>
            </div>
    </div>
</section>
