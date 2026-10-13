{{--
    The UINavigationBar large-title pattern on a real scroll container: the
    34px title shrinks to 17px and slides to the bar's centre as the content
    passes under it, where the frosted background and the hairline fade in.
    The back chevron sits on the leading side and mirrors in RTL.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<style>
    .ionav-root {
        --ios-blue: #007AFF; --ios-green: #34C759; --ios-red: #FF3B30; --ios-orange: #FF9500;
        --ios-teal: #5AC8FA; --ios-indigo: #5856D6; --ios-gray: #8E8E93;
        --ios-label: #000000; --ios-label-2: rgba(60, 60, 67, .6); --ios-label-3: rgba(60, 60, 67, .3);
        --ios-fill: rgba(120, 120, 128, .2); --ios-sep: rgba(60, 60, 67, .29);
        --ios-bg: #F2F2F7; --ios-card: #FFFFFF; --ios-gray5: #E5E5EA;
        --ionav-frost-bg: rgba(249, 249, 249, .82);
        font-family: -apple-system, system-ui, 'Segoe UI', sans-serif; color: var(--ios-label);
    }
    html[data-theme="dark"] .ionav-root {
        --ios-blue: #0A84FF; --ios-green: #30D158; --ios-red: #FF453A; --ios-orange: #FF9F0A;
        --ios-teal: #64D2FF; --ios-indigo: #5E5CE6; --ios-gray: #8E8E93;
        --ios-label: #FFFFFF; --ios-label-2: rgba(235, 235, 245, .6); --ios-label-3: rgba(235, 235, 245, .3);
        --ios-fill: rgba(120, 120, 128, .36); --ios-sep: rgba(84, 84, 88, .6);
        --ios-bg: #000000; --ios-card: #1C1C1E; --ios-gray5: #2C2C2E;
        --ionav-frost-bg: rgba(28, 28, 30, .72);
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .ionav-root {
            --ios-blue: #0A84FF; --ios-green: #30D158; --ios-red: #FF453A; --ios-orange: #FF9F0A;
            --ios-teal: #64D2FF; --ios-indigo: #5E5CE6; --ios-gray: #8E8E93;
            --ios-label: #FFFFFF; --ios-label-2: rgba(235, 235, 245, .6); --ios-label-3: rgba(235, 235, 245, .3);
            --ios-fill: rgba(120, 120, 128, .36); --ios-sep: rgba(84, 84, 88, .6);
            --ios-bg: #000000; --ios-card: #1C1C1E; --ios-gray5: #2C2C2E;
            --ionav-frost-bg: rgba(28, 28, 30, .72);
        }
    }
    .ionav-root :focus-visible { outline: 2px solid var(--ios-blue); outline-offset: 2px; }

    /* overflow: hidden, not clip — clip forbids scrolling entirely, which would
       leave the scroll-driven collapse (and the sticky bar) permanently dead;
       hidden clips the rounded corners identically but stays scrollable. */
    .ionav-stage { position: relative; overflow: hidden; inline-size: min(100%, 22rem); block-size: 24rem; margin-inline: auto;
                   border-radius: 20px; background: var(--ios-bg); box-shadow: 0 18px 44px rgba(0, 0, 0, .18), inset 0 0 0 1px rgba(0, 0, 0, .06); }
    html[data-theme="dark"] .ionav-stage, .ionav-root[data-dark] .ionav-stage { box-shadow: 0 18px 44px rgba(0, 0, 0, .5), inset 0 0 0 1px rgba(255, 255, 255, .08); }

    .ionav-bar { position: sticky; inset-block-start: 0; z-index: 2; display: block; block-size: calc(96px - 52px * var(--ionav-p, 0)); }
    .ionav-frost { position: absolute; inset: 0; opacity: 0; background: var(--ionav-frost-bg);
                   backdrop-filter: blur(20px) saturate(1.8); -webkit-backdrop-filter: blur(20px) saturate(1.8);
                   border-block-end: .5px solid var(--ios-sep); }
    .ionav-back { position: absolute; inset-block-start: 8px; inset-inline-start: 6px; z-index: 1; display: inline-flex; align-items: center; gap: .15rem;
                  block-size: 30px; padding-inline: .4rem; border-radius: 8px; color: var(--ios-blue); font-size: 17px; }
    .ionav-back .chev { font-size: 24px; line-height: 1; translate: 0 -1px; }
    [dir="rtl"] .ionav-back .chev { transform: scaleX(-1); }
    /* --ionav-fit (measured in JS) shrinks the resting size on narrow stages so
       "Sounds & Haptics" never clips mid-word; the collapse still settles at 17px. */
    .ionav-title { position: absolute; inset-inline: 16px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;
                   font-weight: 700; line-height: 1.15;
                   font-size: calc(34px * var(--ionav-fit, 1) - (34px * var(--ionav-fit, 1) - 17px) * var(--ionav-p, 0));
                   inset-block-start: calc(47px - 36px * var(--ionav-p, 0)); }

    .ionav-body { padding: .9rem; display: grid; gap: 1rem; align-content: start; }
    .ionav-group { background: var(--ios-card); border-radius: 12px; overflow: clip; }
    .ionav-group h4 { margin: 0; padding: .7rem .9rem .3rem; font: 500 .78rem/1.4 -apple-system, system-ui, sans-serif; color: var(--ios-label-2); text-transform: uppercase; }
    .ionav-row { display: flex; align-items: center; gap: .6rem; min-block-size: 44px; padding: .5rem .9rem; font-size: .95rem; }
    .ionav-row + .ionav-row { border-block-start: .5px solid var(--ios-sep); }
    .ionav-row .ic { display: grid; place-items: center; flex: none; inline-size: 28px; block-size: 28px; border-radius: 6px; color: #fff; font-size: .8rem; }
    .ionav-row .val { margin-inline-start: auto; color: var(--ios-label-2); font-size: .88rem; }
    .ionav-row .chev { color: var(--ios-label-3); font-size: 1rem; }
    [dir="rtl"] .ionav-row .chev { transform: scaleX(-1); }
    .ionav-mini { position: relative; flex: 1; max-inline-size: 7rem; margin-inline-start: auto; block-size: 4px; border-radius: 999px; background: var(--ios-fill); }
    .ionav-mini i { position: absolute; inset-block: 0; inset-inline-start: 0; inline-size: 62%; border-radius: inherit; background: var(--ios-red); }
    .ionav-note { padding: 0 .9rem; font-size: .78rem; color: var(--ios-label-2); }

    /* The «Important props» table that the demo page renders after this partial
       fades its rows in only when a scroll observer marks the table revealed.
       The section sits below the fold, so in full-page captures the observer
       never fires and the rows stay invisible (on this Livewire page .nx-live
       also switches off the 2.5s CSS failsafe). The rule ships only with this
       partial, so it is page-scoped: show the rows unconditionally. */
    .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr { opacity: 1; translate: none; }

    @media (prefers-reduced-motion: reduce) {
        .ionav-stage { scroll-behavior: auto; }
    }
</style>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The large title that shrinks into place', 'تیتر بزرگی که جمع می‌شود سرِ جایش') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Scroll the sound settings: the 34px title collapses to 17px and slides to the centre of the bar in one motion, and only once the content passes underneath do the frosted background and the hairline divider fade in. The back chevron leads the way and mirrors itself in RTL.', 'تنظیمات صدا را اسکرول کنید: تیتر ۳۴ پیکسلی در یک حرکت به ۱۷ پیکسل جمع و به مرکز نوار می‌لغزد، و فقط وقتی محتوا زیرش رد شد پشت‌زمینهٔ شیشه‌ای و خط جداکنندهٔ مو ظاهر می‌شوند. شورون بازگشت سمت شروع است و در راست‌به‌چپ خودش را آینه می‌کند.') }}
        </p>
    </div>

    <div class="ionav-root" x-data="{
            p: 0, w0: 0, bw: 0, fit: 1, rtl: document.documentElement.dir === 'rtl',
            measure() { const t = this.$refs.title; if (!t) return;
                const prev = this.p; this.p = 0; this.fit = 1;
                this.$nextTick(() => { const r = document.createRange(); r.selectNodeContents(t);
                    this.w0 = r.getBoundingClientRect().width || t.getBoundingClientRect().width;
                    this.bw = this.$refs.sc.clientWidth;
                    /* 36 = the 16px insets plus 4px slack so sub-pixel rounding
                       never re-triggers the ellipsis. */
                    this.fit = this.w0 > 0 ? Math.min(1, (this.bw - 36) / this.w0) : 1; this.p = prev; }) },
            get shift() { if (!this.w0 || !this.bw) return 0;
                const wNow = this.w0 * (this.fit - (this.fit - .5) * this.p);
                return ((this.bw - 32 - wNow) / 2) * (this.rtl ? -1 : 1) * this.p },
            onScroll() { this.p = Math.min(1, Math.max(0, this.$refs.sc.scrollTop / 56)) },
        }"
         x-init="measure()" x-on:resize.window="measure()">
        <div class="ionav-stage" x-ref="sc" x-on:scroll.passive="onScroll()">
            <header class="ionav-bar" :style="'--ionav-p: ' + p">
                <span class="ionav-frost" aria-hidden="true" :style="'opacity: ' + Math.min(1, p * 3)"></span>
                <button type="button" class="ionav-back">
                    <span class="chev" aria-hidden="true">‹</span>
                    <span>{{ $say('Settings', 'تنظیمات') }}</span>
                </button>
                <h3 class="ionav-title" x-ref="title" :style="'--ionav-p: ' + p + '; --ionav-fit: ' + fit + '; translate: ' + shift + 'px 0'">{{ $say('Sounds & Haptics', 'صدا و لمس') }}</h3>
            </header>

            <div class="ionav-body">
                <div class="ionav-group">
                    <h4>{{ $say('Ringtones', 'زنگ‌ها') }}</h4>
                    <div class="ionav-row">
                        <span class="ic" style="background: var(--ios-red)" aria-hidden="true">♪</span>
                        <span>{{ $say('Ringtone', 'آهنگ زنگ') }}</span>
                        <span class="val">{{ $say('Shab-neshini', 'شب‌نشینی') }} <span class="chev" aria-hidden="true">›</span></span>
                    </div>
                    <div class="ionav-row">
                        <span class="ic" style="background: var(--ios-orange)" aria-hidden="true">✉</span>
                        <span>{{ $say('Text tone', 'صدای پیام') }}</span>
                        <span class="val">{{ $say('Note', 'نت') }} <span class="chev" aria-hidden="true">›</span></span>
                    </div>
                    <div class="ionav-row">
                        <span class="ic" style="background: var(--ios-indigo)" aria-hidden="true">☏</span>
                        <span>{{ $say('Ring volume', 'بلندی زنگ') }}</span>
                        <span class="ionav-mini" role="img" :aria-label="@js($say('Ring volume: 62 percent', 'بلندی زنگ: ۶۲ درصد'))"><i></i></span>
                    </div>
                </div>

                <div class="ionav-group">
                    <h4>{{ $say('Sounds and patterns', 'صدا و الگوها') }}</h4>
                    <div class="ionav-row">
                        <span class="ic" style="background: var(--ios-teal)" aria-hidden="true">⌨</span>
                        <span>{{ $say('Keyboard clicks', 'صدای صفحه‌کلید') }}</span>
                        <span class="val">{{ $say('On', 'روشن') }}</span>
                    </div>
                    <div class="ionav-row">
                        <span class="ic" style="background: var(--ios-green)" aria-hidden="true">🔓</span>
                        <span>{{ $say('Lock sound', 'صدای قفل') }}</span>
                        <span class="val">{{ $say('On', 'روشن') }}</span>
                    </div>
                    <div class="ionav-row">
                        <span class="ic" style="background: var(--ios-blue)" aria-hidden="true">⌚</span>
                        <span>{{ $say('Haptic strength', 'قدرت لرزش') }}</span>
                        <span class="val">{{ $say('Medium', 'متوسط') }} <span class="chev" aria-hidden="true">›</span></span>
                    </div>
                    <div class="ionav-row">
                        <span class="ic" style="background: var(--ios-gray)" aria-hidden="true">☾</span>
                        <span>{{ $say('Silent mode', 'حالت بی‌صدا') }}</span>
                        <span class="val">{{ $say('Off', 'خاموش') }}</span>
                    </div>
                    <div class="ionav-row">
                        <span class="ic" style="background: var(--ios-orange)" aria-hidden="true">☀</span>
                        <span>{{ $say('Morning chime', 'زنگ صبح') }}</span>
                        <span class="val">{{ $say('7:00', '۷:۰۰') }} <span class="chev" aria-hidden="true">›</span></span>
                    </div>
                </div>
                <p class="ionav-note">{{ $say('Changes apply to calls, FaceTime and alerts.', 'تغییرها روی تماس‌ها، فیس‌تایم و هشدارها اعمال می‌شود.') }}</p>
            </div>
        </div>
    </div>
</section>
