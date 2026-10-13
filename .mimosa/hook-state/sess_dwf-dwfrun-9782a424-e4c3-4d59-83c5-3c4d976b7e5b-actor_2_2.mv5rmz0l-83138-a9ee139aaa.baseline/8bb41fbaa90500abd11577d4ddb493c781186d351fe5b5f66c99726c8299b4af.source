{{--
    iOS progress indicators — the 12-blade activity spinner (each blade fades
    on a staggered delay, none rotates), the hairline 2px determinate bar and a
    ring percent — plus a real pull-to-refresh inside a scroll stage: the
    spinner rises from behind the top edge, stretches while pulling past the
    80px threshold, spins for a moment on release, then collapses with fresh
    content.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<style>
    .ioprg-root {
        --ios-blue: #007AFF; --ios-green: #34C759; --ios-red: #FF3B30; --ios-orange: #FF9500;
        --ios-teal: #5AC8FA; --ios-indigo: #5856D6; --ios-gray: #8E8E93;
        --ios-label: #000000; --ios-label-2: rgba(60, 60, 67, .6); --ios-label-3: rgba(60, 60, 67, .3);
        --ios-fill: rgba(120, 120, 128, .2); --ios-sep: rgba(60, 60, 67, .29);
        --ios-bg: #F2F2F7; --ios-card: #FFFFFF; --ios-gray5: #E5E5EA;
        font-family: -apple-system, system-ui, 'Segoe UI', sans-serif; color: var(--ios-label);
    }
    html[data-theme="dark"] .ioprg-root {
        --ios-blue: #0A84FF; --ios-green: #30D158; --ios-red: #FF453A; --ios-orange: #FF9F0A;
        --ios-teal: #64D2FF; --ios-indigo: #5E5CE6; --ios-gray: #8E8E93;
        --ios-label: #FFFFFF; --ios-label-2: rgba(235, 235, 245, .6); --ios-label-3: rgba(235, 235, 245, .3);
        --ios-fill: rgba(120, 120, 128, .36); --ios-sep: rgba(84, 84, 88, .6);
        --ios-bg: #000000; --ios-card: #1C1C1E; --ios-gray5: #2C2C2E;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .ioprg-root {
            --ios-blue: #0A84FF; --ios-green: #30D158; --ios-red: #FF453A; --ios-orange: #FF9F0A;
            --ios-teal: #64D2FF; --ios-indigo: #5E5CE6; --ios-gray: #8E8E93;
            --ios-label: #FFFFFF; --ios-label-2: rgba(235, 235, 245, .6); --ios-label-3: rgba(235, 235, 245, .3);
            --ios-fill: rgba(120, 120, 128, .36); --ios-sep: rgba(84, 84, 88, .6);
            --ios-bg: #000000; --ios-card: #1C1C1E; --ios-gray5: #2C2C2E;
        }
    }
    .ioprg-root :focus-visible { outline: 2px solid var(--ios-blue); outline-offset: 2px; }

    .ioprg-row { display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 2.2rem; }
    .ioprg-cell { display: grid; justify-items: center; gap: .55rem; }
    .ioprg-cell span { font-size: .74rem; color: var(--nx-text-muted); text-align: center; }

    .ioprg-spinner { position: relative; display: inline-block; inline-size: 28px; aspect-ratio: 1; }
    .ioprg-spinner i { position: absolute; inset: 0; animation: ioprg-fade 1s linear infinite; }
    .ioprg-spinner i::before { content: ''; display: block; margin: 1px auto; inline-size: 3px; block-size: 8px; border-radius: 999px; background: var(--ios-gray); }
    @keyframes ioprg-fade { 0% { opacity: 1 } 100% { opacity: 0 } }

    .ioprg-bar { position: relative; inline-size: min(13rem, 70vw); block-size: 2px; border-radius: 999px; background: var(--ios-fill); overflow: clip; }
    .ioprg-bar i { position: absolute; inset-block: 0; inset-inline-start: 0; border-radius: inherit; background: var(--ios-blue);
                   animation: ioprg-load 3.2s ease-in-out infinite; }
    @keyframes ioprg-load { 0% { inline-size: 4% } 70% { inline-size: 90% } 100% { inline-size: 100% } }

    .ioprg-ring { position: relative; inline-size: 60px; aspect-ratio: 1; }
    .ioprg-ring svg { transform: rotate(-90deg); }
    .ioprg-ring b { position: absolute; inset: 0; display: grid; place-items: center; font-size: .78rem; font-weight: 700; font-variant-numeric: tabular-nums; }
    .ioprg-ring circle { transition: stroke-dashoffset .9s cubic-bezier(.32, .72, .28, 1); }

    .ioprg-feed { position: relative; overflow: clip; inline-size: min(100%, 22rem); margin-inline: auto; border-radius: 20px;
                  background: var(--ios-bg); box-shadow: 0 18px 44px rgba(0, 0, 0, .14), inset 0 0 0 1px rgba(0, 0, 0, .06); }
    .ioprg-sc { block-size: 16rem; overflow-y: auto; scrollbar-width: thin; user-select: none; }
    .ioprg-ptr { position: relative; display: grid; place-items: center; overflow: clip; block-size: 0; }
    .ioprg-ptr.soft { transition: block-size .35s cubic-bezier(.3, .8, .4, 1.1); }
    .ioprg-ptr small { position: absolute; inset-block-end: .15rem; font-size: .68rem; color: var(--ios-label-2); }
    .ioprg-ptr .ioprg-spinner i { animation-play-state: paused; }
    .ioprg-ptr .ioprg-spinner.spin i { animation-play-state: running; }
    .ioprg-list { background: var(--ios-card); border-radius: 12px; margin: 0 .8rem .8rem; overflow: clip; }
    .ioprg-item { display: flex; align-items: center; gap: .6rem; padding: .65rem .8rem; }
    .ioprg-item + .ioprg-item { border-block-start: .5px solid var(--ios-sep); }
    .ioprg-item .dot { flex: none; inline-size: 8px; aspect-ratio: 1; border-radius: 50%; background: var(--ios-blue); }
    .ioprg-item b { font-size: .88rem; font-weight: 600; }
    .ioprg-item time { margin-inline-start: auto; font-size: .72rem; color: var(--ios-label-2); }
    .ioprg-item.fresh { animation: ioprg-flash 1.4s ease-out; }
    @keyframes ioprg-flash { 0% { background: color-mix(in oklab, var(--ios-blue) 16%, transparent) } 100% { background: transparent } }
    .ioprg-head { display: flex; align-items: center; justify-content: space-between; padding: .9rem .9rem .6rem; }
    .ioprg-head h4 { margin: 0; font: 700 1.05rem/-apple-system, system-ui, sans-serif; }
    .ioprg-head button { padding: .35rem .8rem; border-radius: 999px; background: var(--ios-fill); font-size: .78rem; font-weight: 600;
                         color: var(--ios-blue); transition: scale .12s ease; }
    .ioprg-head button:active { scale: .94; }
    .ioprg-head button:disabled { opacity: .5; }

    /* The shared «Important props» table below this stage reveals its rows on
       scroll (rows sit at opacity: 0 until an IntersectionObserver stamps
       [data-nx-revealed]; the CSS failsafe is off once .nx-live is set). A
       full-page capture never scrolls, so the rows stayed invisible and the
       table looked header-only. Pin this page's table rows visible — scoped
       through :has(.ioprg-root), so it never reaches another demo page. */
    :where(.nx-js) .pg:has(.ioprg-root) .nx-data-table[data-nx-reveal] tbody tr {
        opacity: 1; translate: none;
    }

    @media (prefers-reduced-motion: reduce) {
        .ioprg-spinner i, .ioprg-bar i { animation: none; }
        .ioprg-bar i { inline-size: 62%; }
        .ioprg-ptr.soft, .ioprg-item.fresh, .ioprg-ring circle { transition-duration: 1ms; animation: none; }
    }
</style>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Spinners, bars and rings', 'چرخ، نوار و حلقه') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The system activity indicator is twelve blades on a staggered fade — every blade fades in place, none of them rotates. Beside it, the 2px hairline bar fills, and the ring animates its percent on arrival.', 'شاخص فعالیت سیستم دوازده پره با محو پلکانی است — هر پره در جا محو می‌شود، هیچ‌کدام نمی‌چرخد. کنارش نوار موییِ ۲ پیکسلی پر می‌شود و حلقه، درصدش را در لحظهٔ رسیدن انیمیت می‌کند.') }}
        </p>
    </div>

    <div class="ioprg-root" x-data="{ pct: 0, rtl: document.documentElement.dir === 'rtl', faN(s) { return this.rtl ? String(s).replace(/[0-9]/g, d => '۰۱۲۳۴۵۶۷۸۹'[+d]) : String(s) } }"
         x-init="setTimeout(() => pct = 68, 350)">
        <div class="ioprg-row">
            <div class="ioprg-cell">
                <span class="ioprg-spinner" role="status" :aria-label="$say('Loading', 'در حال بارگذاری')">
                    @for ($i = 1; $i <= 12; $i++)
                        <i style="rotate: {{ ($i - 1) * 30 }}deg; animation-delay: -{{ ($i - 1) * 83 }}ms"></i>
                    @endfor
                </span>
                <span>{{ $say('12 blades, staggered', '۱۲ پرهٔ پلکانی') }}</span>
            </div>
            <div class="ioprg-cell">
                <span class="ioprg-bar" role="progressbar" :aria-label="$say('Importing library', 'وارد کردن کتابخانه')"><i></i></span>
                <span>{{ $say('Hairline 2px bar', 'نوار مویی ۲ پیکسلی') }}</span>
            </div>
            <div class="ioprg-cell">
                <span class="ioprg-ring" role="img" :aria-label="faN(pct) + (rtl ? ' درصد' : ' percent')">
                    <svg width="60" height="60" viewBox="0 0 60 60" fill="none" aria-hidden="true">
                        <circle cx="30" cy="30" r="25" stroke="var(--ios-fill)" stroke-width="5"/>
                        <circle cx="30" cy="30" r="25" stroke="var(--ios-blue)" stroke-width="5" stroke-linecap="round"
                                stroke-dasharray="157.1" :stroke-dashoffset="157.1 * (1 - pct / 100)"/>
                    </svg>
                    <b x-text="faN(pct) + (rtl ? '٪' : '%')">۰٪</b>
                </span>
                <span>{{ $say('Ring percent', 'حلقهٔ درصد') }}</span>
            </div>
        </div>
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Pull to refresh, for real', 'کشیدن برای تازه‌سازی، این بار واقعی') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Scroll to the top of the feed and drag down: the spinner rises from behind the edge and stretches as you pull; cross the 80px threshold and let go — it spins for a beat, then collapses and the fresh item flashes in. The button refreshes too, for keyboards.', 'تا بالای فهرست اسکرول کنید و به پایین بکشید: اسپینر از پشت لبه بالا می‌آید و با کشیدنت کش می‌آید؛ از آستانهٔ ۸۰ پیکسل بگذرید و رها کنید — یک لحظه می‌چرخد، بعد جمع می‌شود و آیتم تازه با جرقهٔ نوری می‌نشیند. دکمه هم برای کیبورد تازه‌سازی می‌کند.') }}
        </p>
    </div>

    <div class="ioprg-root" x-data="{
            pull: 0, startY: 0, dragging: false, refreshing: false, status: null, rtl: document.documentElement.dir === 'rtl',
            th: 80,
            items: [
                { t: ['Glass tab bar shipped', 'تب‌بار شیشه‌ای ارسال شد'], d: ['9:12', '۹:۱۲'] },
                { t: ['Wheel picker centre magnification', 'بزرگ‌نمایی مرکز در چرخ‌فلک'], d: ['Yesterday', 'دیروز'] },
                { t: ['Sheet detents get springy', 'توقف‌گاه‌های شیت فنری شدند'], d: ['Monday', 'دوشنبه'] },
                { t: ['Liquid Glass docs updated', 'مستندات شیشهٔ مایع به‌روز شد'], d: ['Sunday', 'یکشنبه'] },
            ],
            faN(s) { return this.rtl ? String(s).replace(/[0-9]/g, d => '۰۱۲۳۴۵۶۷۸۹'[+d]) : String(s) },
            down(e) { if (this.refreshing) return;
                      if (this.$refs.sc.scrollTop <= 0) { this.dragging = true; this.startY = e.clientY } },
            move(e) { if (!this.dragging || this.refreshing) return;
                      const d = e.clientY - this.startY; this.pull = d > 0 ? Math.min(120, d * .55) : 0 },
            up() { if (!this.dragging) return; this.dragging = false;
                   if (this.pull >= this.th) { this.refresh() } else { this.pull = 0 } },
            refresh() { this.refreshing = true; this.pull = 64; this.status = null;
                        setTimeout(() => {
                            this.items.unshift({ t: ['Morning digest is ready', 'گزارش صبح آماده است'], d: ['now', 'هم‌اکنون'], fresh: true });
                            this.refreshing = false; this.pull = 0;
                            this.status = this.rtl ? 'تازه‌سازی شد' : 'Refreshed';
                        }, 1500) },
        }">
        <div class="ioprg-feed" x-on:pointerdown="down($event)" x-on:pointermove.window="move($event)"
             x-on:pointerup.window="up()" x-on:pointercancel.window="up()">
            <div class="ioprg-head">
                <h4>{{ $say('Nabu Weekly', 'هفته‌نامهٔ نابو') }}</h4>
                <button type="button" x-on:click="refresh()" :disabled="refreshing" :aria-label="$say('Refresh feed', 'تازه‌سازی فهرست')">
                    {{ $say('Refresh', 'تازه‌سازی') }}
                </button>
            </div>

            <div class="ioprg-sc" x-ref="sc">
                <div class="ioprg-ptr" :class="{ soft: !dragging }" :style="'block-size: ' + pull + 'px'" aria-hidden="true">
                    <span class="ioprg-spinner" :class="{ spin: refreshing }" role="status"
                          :style="'opacity: ' + (refreshing ? 1 : Math.min(1, pull / th)) + '; transform: scaleY(' + (refreshing ? 1 : .5 + .6 * pull / th) + ')'"
                          :aria-label="$say('Refreshing', 'در حال تازه‌سازی')">
                        @for ($i = 1; $i <= 12; $i++)
                            <i style="rotate: {{ ($i - 1) * 30 }}deg; animation-delay: -{{ ($i - 1) * 83 }}ms"></i>
                        @endfor
                    </span>
                    <small x-show="pull > 16 && !refreshing" x-text="pull >= th ? (rtl ? 'رها کنید' : 'Release') : (rtl ? 'بکشید' : 'Pull')">…</small>
                </div>

                <div class="ioprg-list">
                    <template x-for="(it, i) in items" :key="it.d + '-' + i">
                        <div class="ioprg-item" :class="{ fresh: it.fresh && i === 0 }">
                            <span class="dot" aria-hidden="true"></span>
                            <b x-text="rtl ? it.t[1] : it.t[0]"></b>
                            <time x-text="rtl ? it.d[1] : it.d[0]"></time>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <p aria-live="polite" style="margin: .8rem 0 0; text-align: center; font-size: .82rem; color: var(--nx-text-muted)"
           x-text="status ?? (rtl ? 'برای تازه‌سازی، از بالا بکشید.' : 'Pull from the top to refresh.')">…</p>
    </div>
</section>
