{{--
    Glass notifications over the drifting colour field: first the single pill
    — icon, copy, an action and a close — draining its own timer along a hair
    of a progress bar that freezes while you hover or tab into it; then the
    stack of three (a shipped build, a climbing error rate, the weekly digest)
    living their own countdowns, with a replay glass button once the feed has
    gone quiet.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹', '.' => '٫', '%' => '٪']) : $s;
@endphp
<style>
    .gnt-root {
        --gnt-glass: rgba(255,255,255,.15); --gnt-glass-2: rgba(255,255,255,.06);
        --gnt-rim: rgba(255,255,255,.44); --gnt-sheen: rgba(255,255,255,.5);
        --gnt-ink: #10142e; --gnt-muted: rgba(16,20,46,.64);
        --gnt-hover: rgba(255,255,255,.22); --gnt-track: rgba(255,255,255,.28);
        --gnt-ok: #12a150; --gnt-warn: #c07807; --gnt-info: #3d63dd;
        display: grid; gap: 2.25rem; justify-items: center; inline-size: 100%;
    }
    html[data-theme="dark"] .gnt-root {
        --gnt-glass: rgba(21,26,56,.5); --gnt-glass-2: rgba(21,26,56,.3);
        --gnt-rim: rgba(255,255,255,.24); --gnt-sheen: rgba(255,255,255,.22);
        --gnt-ink: #eef1ff; --gnt-muted: rgba(238,241,255,.66);
        --gnt-hover: rgba(255,255,255,.08); --gnt-track: rgba(255,255,255,.18);
        --gnt-ok: #3ecf8e; --gnt-warn: #ffc65c; --gnt-info: #8a94ff;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .gnt-root {
            --gnt-glass: rgba(21,26,56,.5); --gnt-glass-2: rgba(21,26,56,.3);
            --gnt-rim: rgba(255,255,255,.24); --gnt-sheen: rgba(255,255,255,.22);
            --gnt-ink: #eef1ff; --gnt-muted: rgba(238,241,255,.66);
            --gnt-hover: rgba(255,255,255,.08); --gnt-track: rgba(255,255,255,.18);
            --gnt-ok: #3ecf8e; --gnt-warn: #ffc65c; --gnt-info: #8a94ff;
        }
    }

    .gnt-stage { position: relative; isolation: isolate; overflow: clip; display: grid; place-items: center;
                 inline-size: 100%; min-block-size: 17rem; padding: 3rem 1.25rem;
                 border-radius: var(--nx-radius-2xl); background: var(--nx-bg); }
    .gnt-blobs { position: absolute; inset: 0; z-index: -2; filter: blur(30px) saturate(1.25); }
    .gnt-blobs i { position: absolute; inline-size: 44%; aspect-ratio: 1; border-radius: 50%; opacity: .85;
                   animation: gnt-drift 19s ease-in-out infinite alternate; }
    .gnt-blobs i:nth-child(1) { inset-block-start: -16%; inset-inline-start: -6%; background: var(--nx-lapis-500); }
    .gnt-blobs i:nth-child(2) { inset-block-end: -22%; inset-inline-start: 24%; background: var(--nx-violet-500); animation-delay: -7s; }
    .gnt-blobs i:nth-child(3) { inset-block-start: 4%; inset-inline-end: -12%; background: var(--nx-cyan-400); animation-delay: -12s; }
    @keyframes gnt-drift { to { translate: calc(7% * var(--nx-motion, 1)) calc(-6% * var(--nx-motion, 1)); scale: calc(1 + .1 * var(--nx-motion, 1)); } }

    .gnt-toast { position: relative; z-index: 1; display: flex; align-items: center; gap: .75rem;
                 inline-size: min(100%, 28rem); padding: .8rem .9rem; border-radius: 1.1rem; overflow: clip;
                 background: linear-gradient(125deg, var(--gnt-glass), var(--gnt-glass-2));
                 border: 1px solid var(--gnt-rim);
                 box-shadow: inset 0 1px 0 var(--gnt-sheen), 0 14px 34px rgba(13,16,50,.16);
                 backdrop-filter: blur(16px) saturate(1.4); -webkit-backdrop-filter: blur(16px) saturate(1.4);
                 transition: opacity .25s ease, translate .25s ease; }
    .gnt-bye { opacity: 0; translate: 0 .5rem; }
    .gnt-icon { display: grid; place-items: center; flex: none; inline-size: 2.15rem; aspect-ratio: 1;
                border-radius: 50%; border: 1px solid var(--gnt-rim);
                background: color-mix(in oklab, var(--gnt-tone) 24%, transparent); color: var(--gnt-tone); }
    .gnt-icon svg { inline-size: 1.05rem; block-size: 1.05rem; }
    .gnt-toast[data-tone="success"] { --gnt-tone: var(--gnt-ok); }
    .gnt-toast[data-tone="warning"] { --gnt-tone: var(--gnt-warn); }
    .gnt-toast[data-tone="info"] { --gnt-tone: var(--gnt-info); }
    .gnt-body { display: grid; gap: .1rem; min-inline-size: 0; flex: 1; }
    .gnt-title { margin: 0; font-size: .88rem; font-weight: 600; color: var(--gnt-ink);
                 overflow-wrap: anywhere; }
    .gnt-meta { margin: 0; font-size: .74rem; color: var(--gnt-muted); overflow-wrap: anywhere; }
    .gnt-act { flex: none; display: inline-flex; align-items: center; gap: .35rem; block-size: 2rem;
               padding-inline: .8rem; border-radius: 999px; border: 1px solid var(--gnt-rim);
               background: var(--gnt-glass); color: var(--gnt-ink); font: 600 .76rem / 1 var(--nx-font-sans);
               cursor: pointer; white-space: nowrap;
               transition: background-color .18s ease, border-color .18s ease, translate .18s ease; }
    .gnt-act svg { inline-size: .8rem; block-size: .8rem; }
    html[dir="rtl"] .gnt-act svg { transform: scaleX(-1); }
    .gnt-act:hover { background: var(--gnt-hover); border-color: color-mix(in oklab, var(--gnt-rim) 70%, white); translate: 0 -1px; }
    .gnt-act:focus-visible, .gnt-x:focus-visible { outline: 2px solid var(--gnt-tone); outline-offset: 2px; }
    .gnt-x { display: inline-grid; place-items: center; flex: none; inline-size: 1.7rem; aspect-ratio: 1;
             padding: 0; border: 1px solid transparent; border-radius: 50%; background: none;
             color: var(--gnt-muted); cursor: pointer; transition: color .18s ease, background-color .18s ease; }
    .gnt-x svg { inline-size: .85rem; block-size: .85rem; }
    .gnt-x:hover { color: var(--gnt-ink); background: var(--gnt-hover); }
    .gnt-bar { position: absolute; inset-block-end: 0; inset-inline-start: 0; block-size: 3px; max-inline-size: 100%;
               border-radius: 999px; background: linear-gradient(90deg, color-mix(in oklab, var(--gnt-tone) 30%, transparent), var(--gnt-tone));
               transition: inline-size .12s linear; }
    html[dir="rtl"] .gnt-bar { background: linear-gradient(270deg, color-mix(in oklab, var(--gnt-tone) 30%, transparent), var(--gnt-tone)); }
    .gnt-track { position: absolute; inset-block-end: 0; inset-inline: 0; block-size: 3px; background: var(--gnt-track); }
    .gnt-stack { display: grid; gap: .8rem; justify-items: center; inline-size: 100%; }
    .gnt-feed { display: grid; gap: .5rem; justify-items: center; }
    .gnt-last { margin: 0; min-block-size: 1.2em; font-size: .78rem; color: var(--gnt-muted); }
    .gnt-replay { display: inline-flex; align-items: center; gap: .5rem; block-size: 2.6rem; padding-inline: 1.2rem;
                  border-radius: 999px; border: 1px solid var(--gnt-rim);
                  background: linear-gradient(125deg, var(--gnt-glass), var(--gnt-glass-2));
                  color: var(--gnt-ink); font: 600 .82rem / 1 var(--nx-font-sans); cursor: pointer;
                  backdrop-filter: blur(14px) saturate(1.4); -webkit-backdrop-filter: blur(14px) saturate(1.4);
                  box-shadow: inset 0 1px 0 var(--gnt-sheen), 0 8px 22px rgba(13,16,50,.14);
                  transition: background-color .18s ease, translate .18s ease; }
    .gnt-replay svg { inline-size: .95rem; block-size: .95rem; }
    .gnt-replay:hover { background: var(--gnt-hover); translate: 0 -1px; }
    .gnt-replay:focus-visible { outline: 2px solid var(--gnt-info); outline-offset: 2px; }
    @media (max-width: 480px) {
        .gnt-toast { flex-wrap: wrap; }
        .gnt-body { flex-basis: calc(100% - 2.9rem); }
        .gnt-act { margin-inline-start: 2.9rem; }
    }
    @media (prefers-reduced-motion: reduce) {
        .gnt-blobs i { animation: none; }
        .gnt-root * { transition-duration: .01ms !important; animation-duration: .01ms !important; }
    }
</style>

<div class="gnt-root"
    x-data="{
        alive: [1, 2, 3, 4],
        items: {
            1: { left: 7000,  dur: 7000 },
            2: { left: 6200,  dur: 6200 },
            3: { left: 10600, dur: 10600 },
            4: { left: 8800,  dur: 8800 },
        },
        frozen: null,
        lastA: '',
        lastB: '',
        pct(id) { return Math.max(0, Math.round((this.items[id].left / this.items[id].dur) * 100)); },
        dismiss(id) { this.alive = this.alive.filter((i) => i !== id); },
        tick() { for (const id of [...this.alive]) { if (this.frozen === id) continue; this.items[id].left -= 100; if (this.items[id].left <= 0) this.dismiss(id); } },
        init() { setInterval(() => this.tick(), 100); },
        replay() {
            this.alive = [1, 2, 3, 4];
            for (const id of this.alive) { this.items[id].left = this.items[id].dur; }
            this.frozen = null; this.lastA = ''; this.lastB = '';
        },
    }">
    <section class="pg-box" style="justify-items: center">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('One pill, its own countdown', 'یک پیل، شمارش معکوس خودش') }}</h3>
            <p style="margin: 0; max-width: 54ch; color: var(--nx-text-muted)">
                {{ $say('Icon, copy, an action and a close — the hair at the bottom drains the clock; hover it (or tab into its buttons) and the clock stands still until you leave.', 'آیکون، متن، یک کنش و یک بستن — خطِ باریک پایین ساعت را خالی می‌کند؛ هاور کن (یا با تب به دکمه‌هایش برو) و ساعت تا رفتنِ تو می‌ایستد.') }}
            </p>
        </div>

        <div class="gnt-stage">
            <div class="gnt-blobs" aria-hidden="true"><i></i><i></i><i></i></div>
            <div class="gnt-feed">
                <div class="gnt-toast" data-tone="info" role="status" x-show="alive.includes(1)"
                    x-transition:leave="gnt-bye"
                    @mouseenter="frozen = 1" @mouseleave="frozen = null"
                    @focusin="frozen = 1" @focusout="frozen = null">
                    <span class="gnt-icon" aria-hidden="true">{!! \NabuXUI\NabuXUI::icon('globe') !!}</span>
                    <div class="gnt-body">
                        <p class="gnt-title">{{ $say('Singapore region is live', 'ناحیهٔ سنگاپور فعال شد') }}</p>
                        <p class="gnt-meta">{{ $say('Route your traces to ap-se-1 — first 30 days unmetered.', 'تریس‌هایتان را به ap-se-1 بفرستید — ۳۰ روز اول بدون meter.') }}</p>
                    </div>
                    <button type="button" class="gnt-act"
                        x-on:click="dismiss(1); lastA = '{{ $say('You opened the status page — all four regions green.', 'صفحهٔ وضعیت را باز کردی — هر چهار ناحیه سبز.') }}'">
                        {{ $say('Open status', 'وضعیت') }}{!! \NabuXUI\NabuXUI::icon('arrow-right') !!}
                    </button>
                    <button type="button" class="gnt-x" x-on:click="dismiss(1)"
                        :aria-label="frozen === 1 ? ('{{ $say('Close — timer paused', 'بستن — تایمر ایستاده') }}') : ('{{ $say('Close notification', 'بستن اعلان') }}')">
                        {!! \NabuXUI\NabuXUI::icon('x') !!}
                    </button>
                    <span class="gnt-track" aria-hidden="true"></span>
                    <i class="gnt-bar" aria-hidden="true" :style="'inline-size: ' + pct(1) + '%'"></i>
                </div>
                <p class="gnt-last" role="status" x-text="lastA"></p>
            </div>
        </div>
    </section>

    <section class="pg-box" style="justify-items: center">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The stack, three clocks ticking', 'استک، سه ساعت در حال تیک‌زدن') }}</h3>
            <p style="margin: 0; max-width: 54ch; color: var(--nx-text-muted)">
                {{ $say('A shipped build, a climbing error rate and the weekly digest — each with its own pace and its own tone; hover any of them to hold its clock, close it yourself, or replay the feed once it goes quiet.', 'یک بیلد منتشرشده، یک نرخ خطای در حال رشد و خلاصهٔ هفتگی — هرکدام با ریتم و رنگ خودش؛ هاور کن تا ساعتش نگه دارد، خودت ببند، یا که ساکت شد دوباره پخشش کن.') }}
            </p>
        </div>

        <div class="gnt-stage" style="min-block-size: 21rem">
            <div class="gnt-blobs" aria-hidden="true"><i></i><i></i><i></i></div>
            <div class="gnt-stack">
                <div class="gnt-toast" data-tone="success" role="status" x-show="alive.includes(2)"
                    x-transition:leave="gnt-bye"
                    @mouseenter="frozen = 2" @mouseleave="frozen = null"
                    @focusin="frozen = 2" @focusout="frozen = null">
                    <span class="gnt-icon" aria-hidden="true">{!! \NabuXUI\NabuXUI::icon('check-circle') !!}</span>
                    <div class="gnt-body">
                        <p class="gnt-title">{{ $say('Build ' . $num('842') . ' shipped to Frankfurt', 'بیلد ' . $num('842') . ' به فرانکفورت رسید') }}</p>
                        <p class="gnt-meta">{{ $say('2 minutes ago · 0 failing checks · rollback open for an hour', '۲ دقیقه پیش · ۰ چکِ ناموفق · واگرد تا یک ساعت باز') }}</p>
                    </div>
                    <button type="button" class="gnt-act"
                        x-on:click="dismiss(2); lastB = '{{ $say('Build 842 opened — the diff is 41 files, mostly the new glass surfaces.', 'بیلد ۸۴۲ باز شد — دیف ۴۱ فایل است، بیشترش سطوح شیشه‌ای تازه.') }}'">
                        {{ $say('View', 'دیدن') }}{!! \NabuXUI\NabuXUI::icon('arrow-right') !!}
                    </button>
                    <button type="button" class="gnt-x" x-on:click="dismiss(2)" aria-label="{{ $say('Close notification', 'بستن اعلان') }}">
                        {!! \NabuXUI\NabuXUI::icon('x') !!}
                    </button>
                    <span class="gnt-track" aria-hidden="true"></span>
                    <i class="gnt-bar" aria-hidden="true" :style="'inline-size: ' + pct(2) + '%'"></i>
                </div>

                <div class="gnt-toast" data-tone="warning" role="alert" x-show="alive.includes(3)"
                    x-transition:leave="gnt-bye"
                    @mouseenter="frozen = 3" @mouseleave="frozen = null"
                    @focusin="frozen = 3" @focusout="frozen = null">
                    <span class="gnt-icon" aria-hidden="true">{!! \NabuXUI\NabuXUI::icon('alert-triangle') !!}</span>
                    <div class="gnt-body">
                        <p class="gnt-title">{{ $say('Error rate climbing in Dublin', 'نرخ خطا در دوبلین بالا می‌رود') }}</p>
                        <p class="gnt-meta">{{ $say('Checkout API · 2.4% of requests 5xx · started 6 minutes ago', 'API پرداخت · ۲٫۴٪ درخواست‌ها 5xx · از ۶ دقیقه پیش') }}</p>
                    </div>
                    <button type="button" class="gnt-act"
                        x-on:click="dismiss(3); lastB = '{{ $say('Incident 2214 claimed — Lena is on it from the Dublin rotation.', 'رخداد ۲۲۱۴ گرفته شد — لنا از چرخهٔ دوبلین روی آن است.') }}'">
                        {{ $say('Inspect', 'بررسی') }}{!! \NabuXUI\NabuXUI::icon('arrow-right') !!}
                    </button>
                    <button type="button" class="gnt-x" x-on:click="dismiss(3)" aria-label="{{ $say('Close notification', 'بستن اعلان') }}">
                        {!! \NabuXUI\NabuXUI::icon('x') !!}
                    </button>
                    <span class="gnt-track" aria-hidden="true"></span>
                    <i class="gnt-bar" aria-hidden="true" :style="'inline-size: ' + pct(3) + '%'"></i>
                </div>

                <div class="gnt-toast" data-tone="info" role="status" x-show="alive.includes(4)"
                    x-transition:leave="gnt-bye"
                    @mouseenter="frozen = 4" @mouseleave="frozen = null"
                    @focusin="frozen = 4" @focusout="frozen = null">
                    <span class="gnt-icon" aria-hidden="true">{!! \NabuXUI\NabuXUI::icon('sparkles') !!}</span>
                    <div class="gnt-body">
                        <p class="gnt-title">{{ $say('Your weekly digest is ready', 'خلاصهٔ هفتگی‌ات آماده است') }}</p>
                        <p class="gnt-meta">{{ $say('12 deploys · 3 incidents · 41% faster p95 across Singapore', '۱۲ استقرار · ۳ رخداد · p95 در سنگاپور ۴۱٪ سریع‌تر') }}</p>
                    </div>
                    <button type="button" class="gnt-act"
                        x-on:click="dismiss(4); lastB = '{{ $say('Digest opened — three minutes of reading, says the estimate.', 'خلاصه باز شد — سه دقیقه خواندن دارد، تخمین می‌زند.') }}'">
                        {{ $say('Read', 'خواندن') }}{!! \NabuXUI\NabuXUI::icon('arrow-right') !!}
                    </button>
                    <button type="button" class="gnt-x" x-on:click="dismiss(4)" aria-label="{{ $say('Close notification', 'بستن اعلان') }}">
                        {!! \NabuXUI\NabuXUI::icon('x') !!}
                    </button>
                    <span class="gnt-track" aria-hidden="true"></span>
                    <i class="gnt-bar" aria-hidden="true" :style="'inline-size: ' + pct(4) + '%'"></i>
                </div>

                <button type="button" class="gnt-replay" x-show="alive.length === 0" x-cloak x-on:click="replay()">
                    {!! \NabuXUI\NabuXUI::icon('play') !!}
                    {{ $say('Replay the feed', 'پخش دوبارهٔ جریان') }}
                </button>
                <p class="gnt-last" role="status" x-text="lastB" x-show="alive.length > 0"></p>
            </div>
        </div>
    </section>
</div>
