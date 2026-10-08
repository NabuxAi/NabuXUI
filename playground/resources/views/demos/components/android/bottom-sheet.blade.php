{{--
    Material's modal bottom sheet over a music library: the 32×4 handle is a
    real drag target, a downward flick faster than 0.5 px/ms dismisses from
    any height, a slow release snaps back, and the 32% scrim tap closes. The
    sheet body carries a real per-track action list.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    [x-cloak] { display: none !important; }
    .m3sheet-root {
        --m3sheet-primary: #6750A4; --m3sheet-on-primary: #FFFFFF;
        --m3sheet-primary-container: #EADDFF; --m3sheet-on-primary-container: #21005D;
        --m3sheet-secondary-container: #E8DEF8; --m3sheet-on-secondary-container: #1D192B;
        --m3sheet-surface: #FEF7FF; --m3sheet-surface-container: #F3EDF7;
        --m3sheet-surface-container-high: #ECE6F0;
        --m3sheet-on-surface: #1D1B20; --m3sheet-on-surface-variant: #49454F;
        --m3sheet-outline-variant: #CAC4D0; --m3sheet-error: #B3261E;
        --m3sheet-scrim: rgba(0, 0, 0, .32);
        --m3sheet-ease: cubic-bezier(.2, 0, 0, 1); --m3sheet-spring: cubic-bezier(.05, .7, .1, 1);
        font-family: Roboto, system-ui, sans-serif;
        display: grid; gap: 1.5rem; justify-items: center;
    }
    html[data-theme="dark"] .m3sheet-root {
        --m3sheet-primary: #D0BCFF; --m3sheet-on-primary: #381E72;
        --m3sheet-primary-container: #4F378B; --m3sheet-on-primary-container: #EADDFF;
        --m3sheet-secondary-container: #4A4458; --m3sheet-on-secondary-container: #E8DEF8;
        --m3sheet-surface: #141218; --m3sheet-surface-container: #211F26;
        --m3sheet-surface-container-high: #2B2930;
        --m3sheet-on-surface: #E6E0E9; --m3sheet-on-surface-variant: #CAC4D0;
        --m3sheet-outline-variant: #49454F; --m3sheet-error: #F2B8B5;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .m3sheet-root {
            --m3sheet-primary: #D0BCFF; --m3sheet-on-primary: #381E72;
            --m3sheet-primary-container: #4F378B; --m3sheet-on-primary-container: #EADDFF;
            --m3sheet-secondary-container: #4A4458; --m3sheet-on-secondary-container: #E8DEF8;
            --m3sheet-surface: #141218; --m3sheet-surface-container: #211F26;
            --m3sheet-surface-container-high: #2B2930;
            --m3sheet-on-surface: #E6E0E9; --m3sheet-on-surface-variant: #CAC4D0;
            --m3sheet-outline-variant: #49454F; --m3sheet-error: #F2B8B5;
        }
    }
    .m3sheet-frame { inline-size: min(100%, 22rem); padding: .625rem; border-radius: 3rem; background: #17171b; box-shadow: 0 24px 48px -24px rgba(0, 0, 0, .5); }
    .m3sheet-screen { position: relative; overflow: clip; block-size: 27rem; border-radius: 2.5rem; background: var(--m3sheet-surface); display: flex; flex-direction: column; }
    .m3sheet-status { display: flex; align-items: center; justify-content: space-between; padding: .8rem 1.4rem .2rem; color: var(--m3sheet-on-surface); font: 600 .72rem/1 Roboto, system-ui, sans-serif; }
    .m3sheet-head { display: flex; align-items: center; gap: .6rem; padding: .55rem 1.1rem .3rem; }
    .m3sheet-head h4 { margin: 0; font: 500 1.2rem/1.2 Roboto, system-ui, sans-serif; color: var(--m3sheet-on-surface); }
    .m3sheet-head small { margin-inline-start: auto; font: 400 .74rem/1 Roboto, system-ui, sans-serif; color: var(--m3sheet-on-surface-variant); }
    .m3sheet-list { flex: 1; display: grid; align-content: start; gap: .15rem; padding: .4rem .8rem; overflow-y: auto; }
    .m3sheet-row { display: flex; align-items: center; gap: .75rem; padding: .5rem .6rem; border-radius: 999px; border: none; background: transparent; cursor: pointer; text-align: start; width: 100%; font-family: Roboto, system-ui, sans-serif; -webkit-tap-highlight-color: transparent; }
    .m3sheet-row:hover { background: color-mix(in srgb, var(--m3sheet-on-surface) 6%, transparent); }
    .m3sheet-row:focus-visible { outline: 2px solid var(--m3sheet-primary); outline-offset: 1px; }
    .m3sheet-row i { flex: none; inline-size: 2.75rem; aspect-ratio: 1; border-radius: .6rem; }
    .m3sheet-row b { display: block; font: 500 .88rem/1.3 Roboto, system-ui, sans-serif; color: var(--m3sheet-on-surface); }
    .m3sheet-row small { display: block; font: 400 .74rem/1.3 Roboto, system-ui, sans-serif; color: var(--m3sheet-on-surface-variant); }
    .m3sheet-row .m3sheet-more { margin-inline-start: auto; }
    .m3sheet-scrim { position: absolute; inset: 0; background: var(--m3sheet-scrim); animation: m3sheet-veil .25s ease forwards; }
    @keyframes m3sheet-veil { from { opacity: 0; } to { opacity: 1; } }
    .m3sheet { position: absolute; inset-inline: 0; inset-block-end: 0; max-block-size: 82%; overflow-y: auto; border: none; border-radius: 28px 28px 0 0; padding: .75rem 1.5rem 1.5rem; background: var(--m3sheet-surface-container); color: var(--m3sheet-on-surface); animation: m3sheet-in .4s var(--m3sheet-spring); touch-action: none; }
    @keyframes m3sheet-in { from { translate: 0 100%; } }
    .m3sheet-handle { display: block; inline-size: 2rem; block-size: .25rem; margin: 0 auto .9rem; border-radius: 999px; background: var(--m3sheet-outline-variant); border: none; padding: 0; cursor: grab; }
    .m3sheet-handle:active { cursor: grabbing; }
    .m3sheet-handle:focus-visible { outline: 2px solid var(--m3sheet-primary); outline-offset: 4px; }
    .m3sheet-title { display: flex; align-items: center; gap: .75rem; padding-block-end: .75rem; }
    .m3sheet-title i { flex: none; inline-size: 2.5rem; aspect-ratio: 1; border-radius: .5rem; }
    .m3sheet-title b { display: block; font: 500 .92rem/1.3 Roboto, system-ui, sans-serif; }
    .m3sheet-title small { display: block; font: 400 .76rem/1.3 Roboto, system-ui, sans-serif; color: var(--m3sheet-on-surface-variant); }
    .m3sheet-menu { display: grid; padding-block-start: .35rem; }
    .m3sheet-action { position: relative; display: flex; align-items: center; gap: .9rem; padding: .8rem .35rem; border: none; border-block-start: 1px solid var(--m3sheet-outline-variant); background: transparent; color: var(--m3sheet-on-surface); cursor: pointer; text-align: start; font: 400 .88rem/1.3 Roboto, system-ui, sans-serif; font-family: Roboto, system-ui, sans-serif; }
    .m3sheet-action[data-tone='error'] { color: var(--m3sheet-error); }
    .m3sheet-action:hover::before { content: ''; position: absolute; inset: 0; border-radius: 999px; background: currentColor; opacity: .08; pointer-events: none; }
    .m3sheet-action:focus-visible { outline: 2px solid var(--m3sheet-primary); outline-offset: 1px; border-radius: 999px; }
    .m3sheet-hint { margin: .3rem 0 0; font: 400 .8rem/1.5 Roboto, system-ui, sans-serif; color: var(--nx-text-muted); max-width: 56ch; }

    /* The shared "Important props" table below this stage reveals its rows on
       scroll (opacity: 0 until an IntersectionObserver stamps
       [data-nx-revealed]; the CSS failsafe is off once .nx-live is set). A
       full-page capture never scrolls, so the rows stayed invisible and the
       table looked header-only. Pin this page's table rows visible — scoped
       through :has(.m3sheet-root), so it never reaches another demo page. */
    :where(.nx-js) .pg:has(.m3sheet-root) .nx-data-table[data-nx-reveal] tbody tr {
        opacity: 1; translate: none;
    }
    /* At phone widths the table's nowrap cells run past the inline edge and
       the fourth column is read as cut off; let this page's table wrap. */
    @media (max-width: 480px) {
        .pg:has(.m3sheet-root) .nx-data-table :is(th, td) {
            white-space: normal; padding-inline: .5rem; overflow-wrap: break-word;
        }
    }
    @media (prefers-reduced-motion: reduce) {
        .m3sheet-root * { animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
    }
</style>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A sheet you can throw away', 'شیتی که می‌شود پرتش کرد') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('Open a track’s sheet, grab the handle and drag — release slowly and it snaps back, flick downward and it flies away. Tapping the scrim or pressing Escape closes it too.', 'شیت آهنگ را باز کنید، دستگیره را بگیرید و بکشید — آهسته رها کنید تا با فنر برگردد، به پایین شلیک کنید تا پرت شود. لمس پرده یا Escape هم می‌بندد.') }}
        </p>
    </div>

    <div class="m3sheet-root" x-data="{
            open: false, y: 0, dragging: false, grab: 0, last: 0, lt: 0, vy: 0,
            current: { t: 'دلتنگی', a: 'همایون شجریان', g: 'linear-gradient(140deg,#c4b5fd,#6d28d9)' },
            tracks: [
                { t: 'دلتنگی', a: 'همایون شجریان', g: 'linear-gradient(140deg,#c4b5fd,#6d28d9)' },
                { t: 'جادهٔ شمال', a: 'گروه رستاک', g: 'linear-gradient(140deg,#86efac,#15803d)' },
                { t: 'کوچه‌های شیراز', a: 'سیما بینا', g: 'linear-gradient(140deg,#fdba74,#c2410c)' },
                { t: 'شبِ چای', a: 'کیهان کلهر', g: 'linear-gradient(140deg,#93c5fd,#1d4ed8)' },
            ],
            show(track) { this.current = track || this.tracks[0]; this.y = 0; this.vy = 0; this.open = true; $nextTick(() => this.$refs.handle.focus()); },
            down(e) { this.dragging = true; this.grab = e.clientY - this.y; this.last = e.clientY; this.lt = performance.now(); this.vy = 0; e.currentTarget.setPointerCapture(e.pointerId); },
            move(e) { if (!this.dragging) return; const now = performance.now(); this.y = Math.max(0, e.clientY - this.grab); this.vy = (e.clientY - this.last) / Math.max(1, now - this.lt); this.last = e.clientY; this.lt = now; },
            up() { if (!this.dragging) return; this.dragging = false; if (this.vy > 0.5 || this.y > 120) this.close(); else this.y = 0; },
            close() { this.open = false; this.dragging = false; this.y = 0; },
        }"
        x-on:keydown.escape.window="close()">
        <div class="m3sheet-frame">
            <div class="m3sheet-screen">
                <div class="m3sheet-status" aria-hidden="true">
                    <span>{{ $say('9:41', '۰۹:۴۱') }}</span>
                    <svg width="44" height="12" viewBox="0 0 44 12" fill="currentColor"><rect x="0" y="7" width="3" height="5" rx="1"/><rect x="5" y="5" width="3" height="7" rx="1"/><rect x="10" y="3" width="3" height="9" rx="1"/><rect x="15" y="1" width="3" height="11" rx="1"/><rect x="35" y="2" width="8" height="8" rx="2" opacity=".35"/><rect x="36.5" y="3.5" width="4" height="5" rx="1"/></svg>
                </div>
                <div class="m3sheet-head">
                    <h4>{{ $say('My library', 'کتابخانهٔ من') }}</h4>
                    <small x-text="tracks.length + ' {{ $say('tracks', 'قطعه') }}'">۴ قطعه</small>
                </div>
                <div class="m3sheet-list">
                    <template x-for="(t, i) in tracks" :key="t.t">
                        <button type="button" class="m3sheet-row" x-on:click="show(t)">
                            <i aria-hidden="true" x-bind:style="{ background: t.g }"></i>
                            <span>
                                <b x-text="t.t"></b>
                                <small x-text="t.a"></small>
                            </span>
                            <span class="m3sheet-more" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="5.5" r="1.7"/><circle cx="12" cy="12" r="1.7"/><circle cx="12" cy="18.5" r="1.7"/></svg></span>
                        </button>
                    </template>
                </div>

                <div class="m3sheet-scrim" x-show="open" x-cloak x-on:click="close()" aria-hidden="true"></div>
                <div class="m3sheet" x-show="open" x-cloak role="dialog" aria-modal="true" aria-label="{{ $say('Track actions', 'اقدام‌های قطعه') }}"
                    x-bind:style="{ transform: 'translateY(' + y + 'px)', transition: dragging ? 'none' : 'transform .35s cubic-bezier(.05,.7,.1,1)' }">
                    <button type="button" class="m3sheet-handle" x-ref="handle" aria-label="{{ $say('Drag to dismiss', 'برای بستن بکشید') }}"
                        x-on:pointerdown="down($event)" x-on:pointermove="move($event)" x-on:pointerup="up()" x-on:pointercancel="up()"></button>
                    <div class="m3sheet-title">
                        <i aria-hidden="true" x-bind:style="{ background: current.g }"></i>
                        <span>
                            <b x-text="current.t"></b>
                            <small x-text="current.a"></small>
                        </span>
                    </div>
                    <div class="m3sheet-menu">
                        <button type="button" class="m3sheet-action" x-on:click="close()">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                            {{ $say('Add to playlist', 'افزودن به لیست پخش') }}
                        </button>
                        <button type="button" class="m3sheet-action" x-on:click="close()">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v12m0 0 4-4m-4 4-4-4"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2"/></svg>
                            {{ $say('Download for offline', 'دانلود آفلاین') }}
                        </button>
                        <button type="button" class="m3sheet-action" x-on:click="close()">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="18" cy="5" r="2.5"/><circle cx="6" cy="12" r="2.5"/><circle cx="18" cy="19" r="2.5"/><path d="M8.2 10.8l7.6-4.5M8.2 13.2l7.6 4.5"/></svg>
                            {{ $say('Share', 'اشتراک‌گذاری') }}
                        </button>
                        <button type="button" class="m3sheet-action" x-on:click="close()">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18V6l10-2v12"/><circle cx="6.5" cy="18" r="2.5"/><circle cx="16.5" cy="16" r="2.5"/></svg>
                            {{ $say('Set as ringtone', 'تنظیم به‌عنوان زنگ') }}
                        </button>
                        <button type="button" class="m3sheet-action" data-tone="error" x-on:click="close()">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m3 0-1 13a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 7"/></svg>
                            {{ $say('Remove from library', 'حذف از کتابخانه') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <p class="m3sheet-hint">{{ $say('Velocity above 0.5 px/ms dismisses even from mid-drag — Material’s own threshold.', 'سرعتِ بیش از ۰٫۵ پیکسل بر میلی‌ثانیه حتی از میانهٔ کشیدن هم می‌بندد — آستانهٔ خود متریال.') }}</p>
    </div>
</section>
