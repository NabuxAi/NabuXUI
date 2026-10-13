{{--
    loop-set's real scenario: the work queue of a sync console — six looping
    icons living inside real controls: a dot trio and an arc spinner, the
    counter-rotating sync arrows, a magnifier that pulses while the search
    field is filtering, an upload arrow whose fill is the progress bar, and
    a download arrow bouncing into its tray. Each sits in a status card with
    text and a time meta.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<style>
    .iml-list {
        --iml-fg: #1c1917; --iml-muted: #78716c; --iml-border: #e7e5e4;
        --iml-bg: #ffffff; --iml-accent: #4f46e5; --iml-soft: #eef2ff;
        display: grid;
        gap: .9rem;
        grid-template-columns: repeat(auto-fit, minmax(14.5rem, 1fr));
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        color: var(--iml-fg);
    }
    html[data-theme="dark"] .iml-list {
        --iml-fg: #fafaf9; --iml-muted: #a8a29e; --iml-border: #292524;
        --iml-bg: #1c1917; --iml-accent: #a5b4fc; --iml-soft: #26264a;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .iml-list {
            --iml-fg: #fafaf9; --iml-muted: #a8a29e; --iml-border: #292524;
            --iml-bg: #1c1917; --iml-accent: #a5b4fc; --iml-soft: #26264a;
        }
    }
    .iml-card {
        display: grid;
        gap: .8rem;
        align-content: start;
        padding: 1.1rem 1.1rem .95rem;
        border: 1px solid var(--iml-border);
        border-radius: 1rem;
        background: var(--iml-bg);
    }
    .iml-head { display: flex; align-items: center; gap: .8rem; }
    .iml-ico {
        flex: none;
        display: grid;
        place-items: center;
        inline-size: 2.6rem;
        block-size: 2.6rem;
        border-radius: .8rem;
        background: var(--iml-soft);
        color: var(--iml-accent);
    }
    .iml-ico > svg { inline-size: 1.5rem; block-size: 1.5rem; overflow: visible; }
    .iml-line { fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .iml-title { margin: 0; font-size: .84rem; font-weight: 700; }
    .iml-desc { margin: .15rem 0 0; font-size: .74rem; line-height: 1.6; color: var(--iml-muted); }
    .iml-meta {
        margin: 0; padding-block-start: .65rem;
        border-block-start: 1px dashed var(--iml-border);
        font-family: ui-monospace, SFMono-Regular, monospace;
        font-size: .68rem; color: var(--iml-muted);
    }
    .iml-wide { grid-column: 1 / -1; }

    /* dot trio */
    .iml-dots { display: flex; gap: .28rem; }
    .iml-dots i {
        inline-size: .38rem; block-size: .38rem; border-radius: 50%;
        background: currentColor; animation: iml-dot .9s ease-in-out infinite;
    }
    .iml-dots i:nth-child(2) { animation-delay: .15s; }
    .iml-dots i:nth-child(3) { animation-delay: .3s; }

    /* arc spinner */
    .iml-arc { animation: iml-spin 1.3s linear infinite; }
    .iml-arc circle {
        fill: none; stroke: currentColor; stroke-width: 2.4; stroke-linecap: round;
        stroke-dasharray: 30 70; transform-origin: center;
    }

    /* counter-rotating sync arrows */
    .iml-sync g { transform-origin: 12px 12px; }
    .iml-sync .iml-cw { animation: iml-spin 1.6s linear infinite; }
    .iml-sync .iml-ccw { animation: iml-spin 1.6s linear infinite reverse; }

    /* pulsing magnifier */
    .iml-magwrap { position: relative; }
    .iml-magwrap[data-busy="true"] .iml-mag { animation: iml-pulse .9s ease-in-out infinite; }
    .iml-magwrap[data-busy="true"]::after {
        content: ''; position: absolute; inset: 0; border-radius: inherit;
        border: 2px solid currentColor; animation: iml-halo 1.1s ease-out infinite;
    }
    .iml-field {
        display: flex; align-items: center; gap: .6rem;
        padding: .6rem .85rem;
        border: 1px solid var(--iml-border);
        border-radius: .8rem;
        background: var(--iml-bg);
        transition: border-color .2s ease;
    }
    .iml-field:focus-within { border-color: var(--iml-accent); }
    .iml-field input {
        flex: 1; min-inline-size: 0; border: none; outline: none; background: transparent;
        font: 500 .8rem/1.4 inherit; color: var(--iml-fg);
    }
    .iml-field input::placeholder { color: var(--iml-muted); }

    /* upload arrow — the fill inside the arrow is the progress bar */
    .iml-upfill { stroke: none; fill: currentColor; }
    .iml-pct { min-inline-size: 3.2em; text-align: end; font-family: ui-monospace, monospace; font-size: .72rem; color: var(--iml-accent); }

    /* download arrow bouncing into its tray */
    .iml-dlarrows { animation: iml-bounce 1.4s ease-in-out infinite; }

    @keyframes iml-dot { 0%, 100% { translate: 0 0; opacity: .45; } 40% { translate: 0 -.32rem; opacity: 1; } }
    @keyframes iml-spin { to { rotate: 360deg; } }
    @keyframes iml-pulse { 0%, 100% { scale: 1; } 50% { scale: 1.18; } }
    @keyframes iml-halo { from { opacity: .7; scale: .8; } to { opacity: 0; scale: 1.25; } }
    @keyframes iml-bounce {
        0%, 100% { translate: 0 0; }
        42% { translate: 0 3px; } 58% { translate: 0 -3.5px; } 76% { translate: 0 1px; }
    }
    @media (prefers-reduced-motion: reduce) {
        .iml-dots i, .iml-arc, .iml-sync .iml-cw, .iml-sync .iml-ccw,
        .iml-magwrap[data-busy="true"] .iml-mag, .iml-magwrap[data-busy="true"]::after,
        .iml-dlarrows { animation: none; }
    }
</style>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Working icons, working controls', 'آیکون‌های در حال کار، کنترل‌های در حال کار') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Useanimations-style looping icons inside the real controls of a sync console: a dot trio and an arc spinner, counter-rotating sync arrows, a magnifier that pulses while the search field filters, an upload arrow whose fill is the progress bar, and a download arrow bouncing into its tray — each in a status card with its time meta.', 'آیکون‌های حلقه‌ای به سبک Useanimations داخل کنترل‌های واقعیِ یک کنسول همگام‌سازی: سه‌نقطه و اسپینر کمانی، فلش‌های همگام‌سازی که خلاف هم می‌چرخند، ذره‌بینی که هنگام فیلتر شدن فیلد جست‌وجو نبض می‌زند، فلش آپلود که پرشدنش نوار پیشرفت است و فلش دانلود که در سینی خودش می‌جهد — هرکدام در کارت وضعیت با متای زمان.') }}
        </p>
    </div>

    <div class="iml-list"
        x-data="{
            fa: @js($fa),
            fmt(n) { const s = String(n); return this.fa ? s.replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]) : s; },
        }">

        {{-- dot trio --}}
        <article class="iml-card">
            <div class="iml-head">
                <span class="iml-ico" role="img" :aria-label="fa ? 'در حال پردازش' : 'processing'">
                    <span class="iml-dots" aria-hidden="true"><i></i><i></i><i></i></span>
                </span>
                <div>
                    <h4 class="iml-title">{{ $say('Processing payout', 'پردازش پرداخت') }}</h4>
                    <p class="iml-desc">{{ $say('Atlas Pay · closing 3 ledger entries', 'اطلس پی · بستن ۳ رکورد دفتر') }}</p>
                </div>
            </div>
            <p class="iml-meta">{{ $say('queued · 2s ago', 'در صف · ۲ ثانیه پیش') }}</p>
        </article>

        {{-- arc spinner --}}
        <article class="iml-card">
            <div class="iml-head">
                <span class="iml-ico" role="img" :aria-label="fa ? 'در حال بارگذاری' : 'loading'">
                    <svg class="iml-arc" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9" pathLength="100"/></svg>
                </span>
                <div>
                    <h4 class="iml-title">{{ $say('Loading dashboard', 'بارگذاری داشبورد') }}</h4>
                    <p class="iml-desc">{{ $say('Meridian analytics · 6 modules', 'تحلیل مریدین · ۶ ماژول') }}</p>
                </div>
            </div>
            <p class="iml-meta">{{ $say('streaming chunks · 08:41', 'قطعه‌قطعه می‌آید · ۰۸:۴۱') }}</p>
        </article>

        {{-- sync arrows --}}
        <article class="iml-card">
            <div class="iml-head">
                <span class="iml-ico" role="img" :aria-label="fa ? 'در حال همگام‌سازی' : 'syncing'">
                    <svg class="iml-sync" viewBox="0 0 24 24" aria-hidden="true">
                        <g class="iml-cw">
                            <path class="iml-line" d="M19.5 13.5A8 8 0 0 1 5.6 16.4"/>
                            <path class="iml-line" d="M4.5 13.5v3.8h3.8"/>
                        </g>
                        <g class="iml-ccw">
                            <path class="iml-line" d="M4.5 10.5A8 8 0 0 1 18.4 7.6"/>
                            <path class="iml-line" d="M19.5 10.5V6.7h-3.8"/>
                        </g>
                    </svg>
                </span>
                <div>
                    <h4 class="iml-title">{{ $say('Syncing workspace', 'همگام‌سازی ورک‌اسپیس') }}</h4>
                    <p class="iml-desc">{{ $say('Frankfurt cluster · 214 files', 'خوشهٔ فرانکفورت · ۲۱۴ فایل') }}</p>
                </div>
            </div>
            <p class="iml-meta">{{ $say('last sync: just now', 'آخرین همگام‌سازی: همین حالا') }}</p>
        </article>

        {{-- download --}}
        <article class="iml-card">
            <div class="iml-head">
                <span class="iml-ico" role="img" :aria-label="fa ? 'در حال دانلود' : 'downloading'">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <g class="iml-dlarrows">
                            <path class="iml-line" d="M12 4v11"/>
                            <path class="iml-line" d="m6.5 10.5 5.5 5.5 5.5-5.5"/>
                        </g>
                        <path class="iml-line" d="M5 20.5h14"/>
                    </svg>
                </span>
                <div>
                    <h4 class="iml-title">{{ $say('Fetching installer', 'دریافت بستهٔ نصب') }}</h4>
                    <p class="iml-desc">{{ $say('Vertex Studio 2026.10 · 42 MB', 'ورتکس استودیو ۲۰۲۶٫۱۰ · ۴۲ مگابایت') }}</p>
                </div>
            </div>
            <p class="iml-meta">{{ $say('mirror: Dublin · 09:12', 'آینه: دوبلین · ۰۹:۱۲') }}</p>
        </article>

        {{-- upload with progress inside the arrow --}}
        <article class="iml-card"
            x-data="{
                p: 0,
                resetting: false,
                up: null,
                init() {
                    this.up = setInterval(() => {
                        if (this.p < 100) { this.p = Math.min(100, this.p + 2); }
                        else if (!this.resetting) {
                            this.resetting = true;
                            setTimeout(() => { this.p = 0; this.resetting = false; }, 1400);
                        }
                    }, 90);
                },
                destroy() { clearInterval(this.up); },
            }">
            <div class="iml-head">
                <span class="iml-ico" role="img" :aria-label="fa ? 'در حال بارگذاری' : 'uploading'">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path class="iml-line" d="M12 15.5V4.5"/>
                        <path class="iml-line" d="m6.5 9.5 5.5-5 5.5 5"/>
                        <path class="iml-line" d="M5 20h14"/>
                        <g class="iml-upclip" :style="`clip-path: inset(${100 - p}% 0 0 0)`">
                            <path class="iml-upfill" d="M12 15.5V4.5"/>
                            <path class="iml-upfill" d="m6.5 9.5 5.5-5 5.5 5Z"/>
                        </g>
                    </svg>
                </span>
                <div>
                    <h4 class="iml-title">{{ $say('Uploading release cut', 'بارگذاری نسخهٔ برش') }}</h4>
                    <p class="iml-desc">{{ $say('q4-report.mp4 · 12.4 MB', 'q4-report.mp4 · ۱۲٫۴ مگابایت') }}</p>
                </div>
                <span class="iml-pct" role="status" x-text="fmt(p) + (fa ? '٪' : '%')"></span>
            </div>
            <p class="iml-meta">{{ $say('the arrow fill is the progress bar', 'پرشدن فلش، خودِ نوار پیشرفت است') }}</p>
        </article>

        {{-- live search with pulsing magnifier --}}
        <article class="iml-card iml-wide"
            x-data="{
                q: '',
                busy: false,
                t: null,
                destroy() { clearTimeout(this.t); },
            }">
            <div class="iml-head">
                <label class="iml-field">
                    <span class="iml-ico iml-magwrap" x-bind:data-busy="busy ? 'true' : 'false'"
                          role="img" :aria-label="busy ? (fa ? 'در حال فیلتر' : 'filtering') : 'search'">
                        <svg class="iml-mag" viewBox="0 0 24 24" aria-hidden="true">
                            <path class="iml-line" d="M17.5 10.5a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                            <path class="iml-line" d="m16 16 4.5 4.5"/>
                        </svg>
                    </span>
                    <input type="search"
                        :placeholder="fa ? 'جست‌وجو در اسناد تیم…' : 'Search the team docs…'"
                        :aria-label="fa ? 'جست‌وجو در اسناد' : 'Search docs'"
                        x-model="q"
                        x-on:input="busy = true; clearTimeout(t); t = setTimeout(() => (busy = false), 900)">
                </label>
            </div>
            <p class="iml-meta"
               x-text="busy
                    ? (fa ? 'در حال فیلتر ۲۴۸ سند…' : 'filtering 248 documents…')
                    : (q ? (fa ? '۲۴۸ سند برای «' + q + '» بررسی شد' : 'scanned 248 docs for “' + q + '”') : (fa ? '۲۴۸ سند در این فضا' : '248 documents in this space'))"></p>
        </article>
    </div>
</section>
