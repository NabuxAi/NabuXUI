{{--
    UIPickerView on the web: two scroll-snap wheel columns (40px rows, six
    visible in 240px) whose side rows shrink and fade by their real computed
    distance from the centre, hairline selection lines, and a live output line
    with the selected time in Persian digits.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $pd = function (int $n) use ($fa) {
        $s = str_pad((string) $n, 2, '0', STR_PAD_LEFT);
        return $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
    };
@endphp

<style>
    .iopick-root {
        --ios-blue: #007AFF; --ios-green: #34C759; --ios-red: #FF3B30; --ios-orange: #FF9500;
        --ios-teal: #5AC8FA; --ios-indigo: #5856D6; --ios-gray: #8E8E93;
        --ios-label: #000000; --ios-label-2: rgba(60, 60, 67, .6); --ios-label-3: rgba(60, 60, 67, .3);
        --ios-fill: rgba(120, 120, 128, .2); --ios-sep: rgba(60, 60, 67, .29);
        --ios-bg: #F2F2F7; --ios-card: #FFFFFF; --ios-gray5: #E5E5EA;
        font-family: -apple-system, system-ui, 'Segoe UI', sans-serif; color: var(--ios-label);
    }
    html[data-theme="dark"] .iopick-root {
        --ios-blue: #0A84FF; --ios-green: #30D158; --ios-red: #FF453A; --ios-orange: #FF9F0A;
        --ios-teal: #64D2FF; --ios-indigo: #5E5CE6; --ios-gray: #8E8E93;
        --ios-label: #FFFFFF; --ios-label-2: rgba(235, 235, 245, .6); --ios-label-3: rgba(235, 235, 245, .3);
        --ios-fill: rgba(120, 120, 128, .36); --ios-sep: rgba(84, 84, 88, .6);
        --ios-bg: #000000; --ios-card: #1C1C1E; --ios-gray5: #2C2C2E;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .iopick-root {
            --ios-blue: #0A84FF; --ios-green: #30D158; --ios-red: #FF453A; --ios-orange: #FF9F0A;
            --ios-teal: #64D2FF; --ios-indigo: #5E5CE6; --ios-gray: #8E8E93;
            --ios-label: #FFFFFF; --ios-label-2: rgba(235, 235, 245, .6); --ios-label-3: rgba(235, 235, 245, .3);
            --ios-fill: rgba(120, 120, 128, .36); --ios-sep: rgba(84, 84, 88, .6);
            --ios-bg: #000000; --ios-card: #1C1C1E; --ios-gray5: #2C2C2E;
        }
    }
    .iopick-root :focus-visible { outline: 2px solid var(--ios-blue); outline-offset: 2px; }

    .iopick-card { inline-size: min(100%, 20rem); margin-inline: auto; padding: 1rem 1rem .9rem; border-radius: 14px; background: var(--ios-card);
                   box-shadow: 0 14px 36px rgba(0, 0, 0, .12), inset 0 0 0 1px rgba(0, 0, 0, .05); }
    .iopick-caption { display: flex; align-items: baseline; justify-content: space-between; margin-block-end: .8rem; }
    .iopick-caption h4 { margin: 0; font: 600 1rem/-apple-system, system-ui, sans-serif; }
    .iopick-caption output { font: 600 1.15rem/-apple-system, system-ui, sans-serif; color: var(--ios-blue); font-variant-numeric: tabular-nums; }
    .iopick-cols { display: flex; justify-content: center; gap: .4rem; }
    .iopick-col { position: relative; inline-size: 6rem; }
    .iopick-col small { display: block; text-align: center; padding-block-end: .3rem; font-size: .72rem; font-weight: 500; color: var(--ios-label-2); text-transform: uppercase; }
    .iopick-frame { position: relative; }
    .iopick-wheel { position: relative; block-size: 240px; overflow-y: auto; scroll-snap-type: y mandatory; scrollbar-width: none;
                    -webkit-mask-image: linear-gradient(transparent, #000 18%, #000 82%, transparent);
                    mask-image: linear-gradient(transparent, #000 18%, #000 82%, transparent); }
    .iopick-wheel::-webkit-scrollbar { display: none; }
    .iopick-wheel::before, .iopick-wheel::after { content: ''; display: block; inline-size: 100%; block-size: 100px; pointer-events: none; }
    .iopick-row { display: grid; place-items: center; block-size: 40px; scroll-snap-align: center;
                  font: 400 22px/-apple-system, system-ui, sans-serif; font-variant-numeric: tabular-nums;
                  transition: scale .15s ease, opacity .15s ease; }
    .iopick-lines { position: absolute; inset-inline: 0; inset-block-start: calc(50% - 20px); inset-block-end: calc(50% - 20px); pointer-events: none; }
    .iopick-lines::before, .iopick-lines::after { content: ''; position: absolute; inset-inline: 0; block-size: .5px; background: var(--ios-sep); }
    .iopick-lines::before { inset-block-start: 0; }
    .iopick-lines::after { inset-block-end: 0; }

    @media (prefers-reduced-motion: reduce) {
        .iopick-row { transition: none; }
    }

    /* The shared "Important props" table below this stage reveals its rows on
       scroll (opacity: 0 until an IntersectionObserver stamps
       [data-nx-revealed]; the CSS failsafe is off once .nx-live is set). A
       full-page capture never scrolls, so the rows stayed invisible and the
       table looked header-only. Pin this page's table rows visible — scoped
       through :has(.iopick-root), so it never reaches another demo page. */
    :where(.nx-js) .pg:has(.iopick-root) .nx-data-table[data-nx-reveal] tbody tr {
        opacity: 1; translate: none;
    }
</style>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The wheel that knows its centre', 'چرخ‌ای که وسطش را می‌شناسد') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Spin the hour or minute column: rows snap to the middle, and every row’s size and fade is genuinely computed from its distance to the centre on each scroll frame — nothing is pre-baked. The hairlines mark the selection and the output line keeps up in Persian digits.', 'ستون ساعت یا دقیقه را بچرخانید: ردیف‌ها وسط می‌ایستند، و اندازه و شفافیت هر ردیف واقعاً از فاصله‌اش تا مرکز در هر فریم اسکرول محاسبه می‌شود — چیزی از پیش‌پخته نیست. خط‌های مو انتخاب را نشان می‌دهند و خط خروجی با ارقام فارسی همراهی می‌کند.') }}
        </p>
    </div>

    <div class="iopick-root" x-data="{
            hour: 9, min: 41, rtl: document.documentElement.dir === 'rtl',
            faN(s) { return this.rtl ? String(s).replace(/[0-9]/g, d => '۰۱۲۳۴۵۶۷۸۹'[+d]) : String(s) },
            pad(n) { return String(n).padStart(2, '0') },
            spin(w) { const el = this.$refs[w]; const box = el.getBoundingClientRect(); const c = box.top + box.height / 2;
                      el.querySelectorAll('.iopick-row').forEach(r => {
                          const rc = r.getBoundingClientRect();
                          const t = Math.min(1, Math.abs(rc.top + rc.height / 2 - c) / (box.height / 2));
                          r.style.scale = String(1 - .38 * t);
                          r.style.opacity = String(1 - .78 * t);
                      }) },
            sync(w, key, max) { const i = Math.min(max, Math.max(0, Math.round(this.$refs[w].scrollTop / 40))); this[key] = i },
            onScroll(w, key, max) { this.spin(w); this.sync(w, key, max) },
            init() { ['hour', 'min'].forEach(w => { const el = this.$refs[w]; el.scrollTop = (w === 'hour' ? this.hour : this.min) * 40; this.spin(w) }) },
        }"
         x-init="init()">
        <div class="iopick-card">
            <div class="iopick-caption">
                <h4>{{ $say('Alarm time', 'ساعت هشدار') }}</h4>
                <output aria-live="polite" x-text="faN(pad(hour)) + ':' + faN(pad(min))">۰۹:۴۱</output>
            </div>
            <div class="iopick-cols">
                <div class="iopick-col">
                    <small>{{ $say('Hour', 'ساعت') }}</small>
                    <div class="iopick-frame">
                        <div class="iopick-wheel" x-ref="hour" tabindex="0" x-on:scroll.passive="onScroll('hour', 'hour', 23)"
                             :aria-label="$say('Hour column', 'ستون ساعت')">
                            @for ($h = 0; $h <= 23; $h++)
                                <div class="iopick-row">{{ $pd($h) }}</div>
                            @endfor
                        </div>
                        <span class="iopick-lines" aria-hidden="true"></span>
                    </div>
                </div>
                <div class="iopick-col">
                    <small>{{ $say('Minute', 'دقیقه') }}</small>
                    <div class="iopick-frame">
                        <div class="iopick-wheel" x-ref="min" tabindex="0" x-on:scroll.passive="onScroll('min', 'min', 59)"
                             :aria-label="$say('Minute column', 'ستون دقیقه')">
                            @for ($m = 0; $m <= 59; $m++)
                                <div class="iopick-row">{{ $pd($m) }}</div>
                            @endfor
                        </div>
                        <span class="iopick-lines" aria-hidden="true"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
