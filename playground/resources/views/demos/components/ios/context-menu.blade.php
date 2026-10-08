{{--
    The iOS context menu on a travel photo: a 500ms long-press, a right-click
    or a plain click lifts the card (swells, blurs), the stage backdrop takes a
    liquid blur, and a glass menu of tinted actions rises anchored to the card.
    Releasing outside the menu, pressing elsewhere or Escape cancels.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<style>
    .ioctx-root {
        --ios-blue: #007AFF; --ios-green: #34C759; --ios-red: #FF3B30; --ios-orange: #FF9500;
        --ios-teal: #5AC8FA; --ios-indigo: #5856D6; --ios-gray: #8E8E93;
        --ios-label: #000000; --ios-label-2: rgba(60, 60, 67, .6); --ios-label-3: rgba(60, 60, 67, .3);
        --ios-fill: rgba(120, 120, 128, .2); --ios-sep: rgba(60, 60, 67, .29);
        --ios-bg: #F2F2F7; --ios-card: #FFFFFF; --ios-gray5: #E5E5EA;
        --ioctx-face: rgba(240, 240, 240, .92);
        font-family: -apple-system, system-ui, 'Segoe UI', sans-serif; color: var(--ios-label);
    }
    html[data-theme="dark"] .ioctx-root {
        --ios-blue: #0A84FF; --ios-green: #30D158; --ios-red: #FF453A; --ios-orange: #FF9F0A;
        --ios-teal: #64D2FF; --ios-indigo: #5E5CE6; --ios-gray: #8E8E93;
        --ios-label: #FFFFFF; --ios-label-2: rgba(235, 235, 245, .6); --ios-label-3: rgba(235, 235, 245, .3);
        --ios-fill: rgba(120, 120, 128, .36); --ios-sep: rgba(84, 84, 88, .6);
        --ios-bg: #000000; --ios-card: #1C1C1E; --ios-gray5: #2C2C2E;
        --ioctx-face: rgba(44, 44, 46, .88);
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .ioctx-root {
            --ios-blue: #0A84FF; --ios-green: #30D158; --ios-red: #FF453A; --ios-orange: #FF9F0A;
            --ios-teal: #64D2FF; --ios-indigo: #5E5CE6; --ios-gray: #8E8E93;
            --ios-label: #FFFFFF; --ios-label-2: rgba(235, 235, 245, .6); --ios-label-3: rgba(235, 235, 245, .3);
            --ios-fill: rgba(120, 120, 128, .36); --ios-sep: rgba(84, 84, 88, .6);
            --ios-bg: #000000; --ios-card: #1C1C1E; --ios-gray5: #2C2C2E;
            --ioctx-face: rgba(44, 44, 46, .88);
        }
    }
    .ioctx-root :focus-visible { outline: 2px solid var(--ios-blue); outline-offset: 2px; }

    .ioctx-stage { position: relative; overflow: clip; inline-size: min(100%, 22rem); block-size: 26rem; margin-inline: auto;
                   padding: 1.1rem 1rem; border-radius: 20px; background: var(--ios-bg);
                   box-shadow: 0 18px 44px rgba(0, 0, 0, .18), inset 0 0 0 1px rgba(0, 0, 0, .06); }
    .ioctx-stage h4 { margin: 0 0 .2rem; font: 700 1.35rem/-apple-system, system-ui, sans-serif; }
    .ioctx-stage .sub { margin: 0 0 .9rem; font-size: .8rem; color: var(--ios-label-2); }
    .ioctx-scrim { position: absolute; inset: 0; z-index: 1; background: rgba(0, 0, 0, .2); opacity: 0; pointer-events: none;
                   backdrop-filter: blur(4px) saturate(1.4); -webkit-backdrop-filter: blur(4px) saturate(1.4);
                   transition: opacity .3s ease; }
    .ioctx-scrim.on { opacity: 1; }
    .ioctx-anchor { position: relative; z-index: 2; display: inline-block; }
    .ioctx-card { position: relative; display: block; inline-size: 15rem; max-inline-size: 100%; text-align: start; border-radius: 16px; overflow: clip;
                  box-shadow: 0 10px 26px rgba(0, 0, 0, .22);
                  transition: scale .3s cubic-bezier(.32, .72, .28, 1.1), filter .3s ease, box-shadow .3s ease, opacity .3s ease; }
    .ioctx-card .art { display: block; aspect-ratio: 4/3; background:
                       radial-gradient(40% 28% at 68% 30%, #ffe9b8 0%, transparent 55%),
                       linear-gradient(170deg, #5AC8FA, #007AFF 55%, #0A2A6B); }
    .ioctx-card figcaption { display: flex; align-items: center; gap: .45rem; padding: .6rem .8rem; background: var(--ios-card); }
    .ioctx-card figcaption b { font-size: .92rem; font-weight: 600; }
    .ioctx-card figcaption span { margin-inline-start: auto; font-size: .72rem; color: var(--ios-label-2); }
    .ioctx-anchor[data-open] .ioctx-card { scale: 1.05; filter: blur(1.5px); opacity: .92; box-shadow: 0 22px 48px rgba(0, 0, 0, .36); }
    .ioctx-strip { position: relative; z-index: 0; display: flex; gap: .5rem; margin-block-start: .9rem; }
    .ioctx-strip i { inline-size: 3.4rem; aspect-ratio: 1; border-radius: 10px; flex: none; }

    .ioctx-menu { position: absolute; inset-block-start: calc(100% + 10px); inset-inline-start: 0; z-index: 3; inline-size: 250px; max-inline-size: min(100%, 78vw);
                  padding: 6px; border-radius: 13px; background: var(--ioctx-face);
                  backdrop-filter: blur(40px) saturate(1.6); -webkit-backdrop-filter: blur(40px) saturate(1.6);
                  box-shadow: 0 12px 40px rgba(0, 0, 0, .28), inset 0 0 0 .5px rgba(255, 255, 255, .25);
                  opacity: 0; scale: .82; translate: 0 -6px; pointer-events: none; transform-origin: top;
                  transition: opacity .22s ease, scale .26s cubic-bezier(.32, .72, .3, 1.15), translate .22s ease; }
    .ioctx-anchor[data-open] .ioctx-menu { opacity: 1; scale: 1; translate: 0 0; pointer-events: auto; }
    .ioctx-menu button { display: flex; inline-size: 100%; align-items: center; gap: .65rem; block-size: 44px; padding-inline: .7rem;
                         border-radius: 8px; font-size: 1rem; text-align: start; }
    .ioctx-menu button:active { background: rgba(120, 120, 128, .2); }
    .ioctx-menu button svg { flex: none; inline-size: 19px; block-size: 19px; }
    .ioctx-menu button[data-destructive] { color: var(--ios-red); border-block-start: .5px solid var(--ios-sep); border-start-start-radius: 0; border-start-end-radius: 0; }

    @media (prefers-reduced-motion: reduce) {
        .ioctx-card, .ioctx-scrim, .ioctx-menu { transition-duration: 1ms; }
    }

    /* The shared "Important props" table below this stage reveals its rows on
       scroll (rows sit at opacity: 0 until an IntersectionObserver stamps
       [data-nx-revealed]; the CSS failsafe is off once .nx-live is set). A
       full-page capture never scrolls, so the rows stayed invisible and the
       table looked header-only. Pin this page's table rows visible — scoped
       through :has(.ioctx-root), so it never reaches another demo page. */
    :where(.nx-js) .pg:has(.ioctx-root) .nx-data-table[data-nx-reveal] tbody tr {
        opacity: 1; translate: none;
    }
    /* At phone widths the table's nowrap cells and the snippet's long lines
       run past the inline edge; let this page's table and snippet wrap so
       nothing is read as cut off. */
    @media (max-width: 480px) {
        .pg:has(.ioctx-root) .nx-data-table :is(th, td) {
            white-space: normal; padding-inline: .5rem; overflow-wrap: break-word;
        }
        .pg:has(.ioctx-root) pre code { white-space: pre-wrap; overflow-wrap: anywhere; }
    }
</style>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Press and hold — the menu surfaces', 'لمس طولانی — منو از زیر آب درمی‌آید') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Long-press the photo for half a second, right-click it, or just click it (a plain click opens the menu too, so keyboards work): the card swells and softens, the whole backdrop takes a liquid blur, and the glass menu rises with tinted actions. Releasing outside the menu — or pressing elsewhere, or Escape — cancels.', 'عکس را نیم‌ثانیه نگه دارید، رویش راست‌کلیک کنید یا ساده رویش کلیک کنید (کلیک ساده هم منو را باز می‌کند تا با کیبورد هم کار کند): کارت بزرگ و نرم می‌شود، تمام پس‌زمینه بلور مایع می‌گیرد و منوی شیشه‌ای با آیکن‌های رنگی بالا می‌آید. رهاکردن بیرون منو، لمس جای دیگر یا Escape همه را لغو می‌کند.') }}
        </p>
    </div>

    <div class="ioctx-root" x-data="{
            open: false, hold: false, timer: null, last: null, status: null, rtl: document.documentElement.dir === 'rtl',
            down() { if (this.open) return; this.hold = false;
                     this.timer = setTimeout(() => { this.hold = true; this.show() }, 500) },
            up(e) { if (this.timer) { clearTimeout(this.timer); this.timer = null; this.show() }
                    else if (this.hold && this.open && !this.$refs.menu.contains(e.target)) { this.close() } },
            rmb(e) { e.preventDefault(); this.cancelTimer(); this.show() },
            cancelTimer() { clearTimeout(this.timer); this.timer = null },
            show() { if (this.open) return; this.last = document.activeElement; this.open = true;
                     this.$nextTick(() => this.$refs.menu?.querySelector('button')?.focus()) },
            close() { this.open = false; this.hold = false; this.last?.focus?.() },
            act(a) { this.status = a; this.close() },
        }"
         x-on:keydown.escape.window="open && close()"
         x-on:pointerdown.window="if (open && !$refs.menu.contains($event.target) && !$refs.card.contains($event.target)) close()"
         x-on:pointerup.window="up($event)">
        <div class="ioctx-stage">
            <h4>{{ $say('Travel Gallery', 'گالری سفر') }}</h4>
            <p class="sub">{{ $say('Caspian trip · autumn 1404', 'سفر شمال · پاییز ۱۴۰۴') }}</p>

            <div class="ioctx-scrim" :class="{ on: open }" aria-hidden="true"></div>

            <div class="ioctx-anchor" :data-open="open ? '' : null">
                <button type="button" class="ioctx-card" x-ref="card" x-on:pointerdown="down()" x-on:pointerleave="cancelTimer()"
                        x-on:contextmenu.prevent="rmb($event)" x-on:click="$event.detail === 0 && show()"
                        aria-haspopup="menu" :aria-expanded="open">
                    <span class="art" role="img" :aria-label="$say('Morning mist over the Caspian Sea', 'مه صبحگاهی روی دریای خزر')"></span>
                    <figcaption>
                        <b>{{ $say('Caspian Morning', 'صبح خزر') }}</b>
                        <span>{{ $say('Namak Abrood', 'نمک‌آبرود') }}</span>
                    </figcaption>
                </button>

                <div class="ioctx-menu" x-ref="menu" role="menu" :aria-label="$say('Photo actions', 'کنش‌های عکس')">
                    <button type="button" role="menuitem" x-on:click="act(rtl ? 'اشتراک‌گذاری' : 'Share')" style="color: var(--ios-label)">
                        <svg viewBox="0 0 19 19" fill="none" stroke="#34C759" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9.5 2.2v10M6 5.4l3.5-3.2 3.5 3.2M3.4 10.8v4.4A1.8 1.8 0 0 0 5.2 17h8.6a1.8 1.8 0 0 0 1.8-1.8v-4.4"/></svg>
                        {{ $say('Share', 'اشتراک‌گذاری') }}
                    </button>
                    <button type="button" role="menuitem" x-on:click="act(rtl ? 'افزودن به مجموعه' : 'Add to album')" style="color: var(--ios-label)">
                        <svg viewBox="0 0 19 19" fill="none" stroke="#007AFF" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2.2" y="2.2" width="14.6" height="14.6" rx="3.4"/><path d="M9.5 6.4v6.2M6.4 9.5h6.2"/></svg>
                        {{ $say('Add to album', 'افزودن به مجموعه') }}
                    </button>
                    <button type="button" role="menuitem" data-destructive x-on:click="act(rtl ? 'حذف' : 'Delete')">
                        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2.5 4h11M6.5 4V2.8c0-.4.3-.8.8-.8h1.4c.5 0 .8.4.8.8V4M4 4l.7 9c0 .6.5 1 1 1h4.6c.5 0 1-.4 1-1L12 4M6.6 7v4M9.4 7v4"/></svg>
                        {{ $say('Delete', 'حذف') }}
                    </button>
                </div>
            </div>

            <div class="ioctx-strip" aria-hidden="true">
                <i style="background: linear-gradient(150deg, #FF9500, #FF3B30)"></i>
                <i style="background: linear-gradient(150deg, #30D158, #007AFF)"></i>
                <i style="background: linear-gradient(150deg, #64D2FF, #5856D6)"></i>
                <i style="background: linear-gradient(150deg, #FF3B75, #FF9F0A)"></i>
            </div>
        </div>

        <p aria-live="polite" style="margin: .8rem 0 0; text-align: center; font-size: .82rem; color: var(--nx-text-muted)"
           x-text="status ? (rtl ? 'کنش انتخاب‌شده: ' + status : 'Chosen action: ' + status) : (rtl ? 'لمس طولانی، راست‌کلیک یا کلیک ساده…' : 'Long-press, right-click or plain click…')">…</p>
    </div>
</section>
