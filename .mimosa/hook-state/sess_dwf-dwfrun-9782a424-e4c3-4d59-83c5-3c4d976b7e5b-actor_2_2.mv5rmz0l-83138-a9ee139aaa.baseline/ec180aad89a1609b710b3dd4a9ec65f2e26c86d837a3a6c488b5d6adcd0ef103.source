{{--
    Material's snackbar as a real undo flow: deleting a row raises the
    snackbar with a «واگرد» action and a 4-second hairline countdown, a second
    delete queues behind the first instead of stacking, and the bar shifts to
    the inline end of the FAB so it clears it instead of covering it.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    [x-cloak] { display: none !important; }
    .m3snk-root {
        --m3snk-primary: #6750A4; --m3snk-on-primary: #FFFFFF;
        --m3snk-primary-container: #EADDFF; --m3snk-on-primary-container: #21005D;
        --m3snk-surface: #FEF7FF; --m3snk-surface-container: #F3EDF7;
        --m3snk-surface-container-high: #ECE6F0;
        --m3snk-on-surface: #1D1B20; --m3snk-on-surface-variant: #49454F;
        --m3snk-outline-variant: #CAC4D0; --m3snk-error: #B3261E;
        --m3snk-inverse-surface: #322F35; --m3snk-inverse-on-surface: #F5EFF7;
        --m3snk-inverse-primary: #D0BCFF;
        --m3snk-spring: cubic-bezier(.05, .7, .1, 1);
        font-family: Roboto, system-ui, sans-serif;
        display: grid; gap: 1.5rem; justify-items: center;
    }
    html[data-theme="dark"] .m3snk-root {
        --m3snk-primary: #D0BCFF; --m3snk-on-primary: #381E72;
        --m3snk-primary-container: #4F378B; --m3snk-on-primary-container: #EADDFF;
        --m3snk-surface: #141218; --m3snk-surface-container: #211F26;
        --m3snk-surface-container-high: #2B2930;
        --m3snk-on-surface: #E6E0E9; --m3snk-on-surface-variant: #CAC4D0;
        --m3snk-outline-variant: #49454F; --m3snk-error: #F2B8B5;
        --m3snk-inverse-surface: #E6E0E9; --m3snk-inverse-on-surface: #1D1B20;
        --m3snk-inverse-primary: #6750A4;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .m3snk-root {
            --m3snk-primary: #D0BCFF; --m3snk-on-primary: #381E72;
            --m3snk-primary-container: #4F378B; --m3snk-on-primary-container: #EADDFF;
            --m3snk-surface: #141218; --m3snk-surface-container: #211F26;
            --m3snk-surface-container-high: #2B2930;
            --m3snk-on-surface: #E6E0E9; --m3snk-on-surface-variant: #CAC4D0;
            --m3snk-outline-variant: #49454F; --m3snk-error: #F2B8B5;
            --m3snk-inverse-surface: #E6E0E9; --m3snk-inverse-on-surface: #1D1B20;
            --m3snk-inverse-primary: #6750A4;
        }
    }
    .m3snk-frame { inline-size: min(100%, 22rem); padding: .625rem; border-radius: 3rem; background: #17171b; box-shadow: 0 24px 48px -24px rgba(0, 0, 0, .5); }
    .m3snk-screen { position: relative; overflow: clip; block-size: 27rem; border-radius: 2.5rem; background: var(--m3snk-surface); display: flex; flex-direction: column; }
    .m3snk-status { display: flex; align-items: center; justify-content: space-between; padding: .8rem 1.4rem .2rem; color: var(--m3snk-on-surface); font: 600 .72rem/1 Roboto, system-ui, sans-serif; }
    .m3snk-head { display: flex; align-items: center; gap: .6rem; padding: .55rem 1.1rem .3rem; }
    .m3snk-head h4 { margin: 0; font: 500 1.2rem/1.2 Roboto, system-ui, sans-serif; color: var(--m3snk-on-surface); }
    .m3snk-head small { margin-inline-start: auto; font: 400 .74rem/1 Roboto, system-ui, sans-serif; color: var(--m3snk-on-surface-variant); }
    .m3snk-list { flex: 1; display: grid; align-content: start; gap: .1rem; padding: .35rem .7rem; overflow-y: auto; }
    .m3snk-row { display: flex; align-items: center; gap: .7rem; padding: .55rem .6rem; border-radius: .9rem; }
    .m3snk-row:hover { background: color-mix(in srgb, var(--m3snk-on-surface) 5%, transparent); }
    .m3snk-ava { flex: none; display: grid; place-items: center; inline-size: 2.4rem; aspect-ratio: 1; border-radius: 50%; color: #ffffff; font: 600 .85rem/1 Roboto, system-ui, sans-serif; }
    .m3snk-row b { display: block; font: 500 .84rem/1.3 Roboto, system-ui, sans-serif; color: var(--m3snk-on-surface); }
    .m3snk-row small { display: block; font: 400 .73rem/1.35 Roboto, system-ui, sans-serif; color: var(--m3snk-on-surface-variant); white-space: nowrap; overflow: clip; text-overflow: ellipsis; max-inline-size: 11rem; }
    .m3snk-del { position: relative; flex: none; margin-inline-start: auto; display: grid; place-items: center; inline-size: 2.25rem; aspect-ratio: 1; border: none; border-radius: 50%; background: transparent; color: var(--m3snk-on-surface-variant); cursor: pointer; }
    .m3snk-del:hover { background: color-mix(in srgb, var(--m3snk-error) 12%, transparent); color: var(--m3snk-error); }
    .m3snk-del:focus-visible { outline: 2px solid var(--m3snk-primary); outline-offset: 2px; }
    .m3snk-empty { padding: 1.4rem .6rem; text-align: center; font: 400 .82rem/1.5 Roboto, system-ui, sans-serif; color: var(--m3snk-on-surface-variant); }
    .m3snk-fab { position: absolute; inset-block-end: 1.1rem; inset-inline-end: 1.1rem; z-index: 5; display: inline-flex; align-items: center; gap: .6rem; block-size: 3.5rem; padding-inline: 1.1rem; border: none; border-radius: 1rem; background: var(--m3snk-primary-container); color: var(--m3snk-on-primary-container); box-shadow: 0 4px 8px 3px rgba(0, 0, 0, .15), 0 1px 3px rgba(0, 0, 0, .3); cursor: pointer; font: 600 .85rem/1 Roboto, system-ui, sans-serif; }
    .m3snk-fab::before { content: ''; position: absolute; inset: 0; border-radius: inherit; background: currentColor; opacity: 0; transition: opacity .15s; pointer-events: none; }
    .m3snk-fab:hover::before { opacity: .08; }
    .m3snk-fab:active::before { opacity: .12; }
    .m3snk-fab:focus-visible { outline: 2px solid var(--m3snk-primary); outline-offset: 2px; }
    .m3snk-layer { position: absolute; inset-block-end: 1.1rem; inset-inline-start: 1.1rem; inset-inline-end: 5.6rem; z-index: 4; display: grid; gap: .35rem; pointer-events: none; }
    .m3snk-queued { justify-self: start; padding: .25rem .6rem; border-radius: 999px; background: var(--m3snk-surface-container-high); color: var(--m3snk-on-surface-variant); font: 500 .7rem/1 Roboto, system-ui, sans-serif; }
    .m3snk { position: relative; display: flex; align-items: center; gap: .5rem; padding: .9rem .95rem; border-radius: .25rem; background: var(--m3snk-inverse-surface); color: var(--m3snk-inverse-on-surface); box-shadow: 0 3px 5px -1px rgba(0, 0, 0, .2), 0 6px 10px 0 rgba(0, 0, 0, .14); animation: m3snk-in .3s var(--m3snk-spring); pointer-events: auto; }
    @keyframes m3snk-in { from { opacity: 0; translate: 0 70%; } }
    .m3snk > span { font: 400 .82rem/1.35 Roboto, system-ui, sans-serif; white-space: nowrap; overflow: clip; text-overflow: ellipsis; }
    .m3snk-act { flex: none; margin-inline-start: auto; border: none; border-radius: .5rem; padding: .4rem .5rem; background: transparent; color: var(--m3snk-inverse-primary); cursor: pointer; font: 600 .82rem/1 Roboto, system-ui, sans-serif; }
    .m3snk-act:hover { background: color-mix(in srgb, var(--m3snk-inverse-primary) 14%, transparent); }
    .m3snk-act:focus-visible { outline: 2px solid var(--m3snk-inverse-primary); outline-offset: 1px; }
    .m3snk-line { position: absolute; inset-block-end: 0; inset-inline-start: 0; block-size: 2px; inline-size: 100%; border-radius: 999px; background: var(--m3snk-inverse-primary); animation: m3snk-drain 4s linear forwards; }
    @keyframes m3snk-drain { from { inline-size: 100%; } to { inline-size: 0; } }
    .m3snk-hint { margin: 0; font: 400 .8rem/1.5 Roboto, system-ui, sans-serif; color: var(--nx-text-muted); max-width: 56ch; }
    /* The shared "Important props" table below this stage reveals its rows on
       scroll (opacity: 0 until an IntersectionObserver stamps
       [data-nx-revealed]; the CSS failsafe is off once .nx-live is set). A
       full-page capture never scrolls, so the rows stayed invisible and the
       table looked header-only. Pin this page's table rows visible — scoped
       through :has(.m3snk-root), so it never reaches another demo page. */
    :where(.nx-js) .pg:has(.m3snk-root) .nx-data-table[data-nx-reveal] tbody tr {
        opacity: 1; translate: none;
    }
    /* At phone widths the table's nowrap cells run past the inline edge and
       the fourth column reads as cut off; let this page's table and snippet
       wrap so nothing is read as cut off. */
    @media (max-width: 480px) {
        .pg:has(.m3snk-root) .nx-data-table :is(th, td) {
            white-space: normal; padding-inline: .5rem; overflow-wrap: break-word;
        }
        .pg:has(.m3snk-root) pre code { white-space: pre-wrap; overflow-wrap: anywhere; }
    }
    @media (prefers-reduced-motion: reduce) {
        .m3snk-root * { animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
        .m3snk-line { animation-duration: 4s !important; }
    }
</style>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Delete, then undo', 'حذف کن، بعد واگرد بزن') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('Delete a mail — the snackbar counts down four seconds on its hairline and «Undo» really brings it back. Delete a second one while the first is up: it queues, never stacks.', 'نامه‌ای را حذف کنید — اسنک‌بار با نوار لایه‌ای‌اش چهار ثانیه می‌شمارد و «واگرد» واقعاً برمی‌گرداندش. وقتی اولی بالا است دومی را حذف کنید: صف می‌شود، روی هم نمی‌نشیند.') }}
        </p>
    </div>

    <div class="m3snk-root" x-data="{
            fa: {{ $fa ? 'true' : 'false' }},
            mails: [
                { t: 'سارا احمدی', s: 'قرارداد دورهٔ بعد پیوست شد', c: 'linear-gradient(140deg,#818cf8,#4338ca)' },
                { t: 'تیم نابو', s: 'یادداشت انتشار نسخهٔ ۲٫۴', c: 'linear-gradient(140deg,#34d399,#065f46)' },
                { t: 'رضا کاویانی', s: 'صورت‌حساب آبان رسید', c: 'linear-gradient(140deg,#fb7185,#9f1239)' },
                { t: 'کتابخانهٔ ملی', s: 'سفارش شما امروز ارسال می‌شود', c: 'linear-gradient(140deg,#fbbf24,#b45309)' },
            ], q: [], cur: null, n: 0, timer: null,
            fd(x) { return this.fa ? String(x).replace(/[0-9]/g, d => '۰۱۲۳۴۵۶۷۸۹'[+d]) : String(x) },
            drop(i) { const m = this.mails.splice(i, 1)[0]; this.q.push({ id: ++this.n, m, at: i }); if (!this.cur) this.next(); },
            next() { this.cur = this.q.shift() ?? null; if (this.cur) { clearTimeout(this.timer); this.timer = setTimeout(() => this.next(), 4000); } },
            undo() { if (!this.cur) return; this.mails.splice(Math.min(this.cur.at, this.mails.length), 0, this.cur.m); this.kill(); },
            kill() { clearTimeout(this.timer); this.cur = null; if (this.q.length) this.next(); },
        }">
        <div class="m3snk-frame">
            <div class="m3snk-screen">
                <div class="m3snk-status" aria-hidden="true">
                    <span>{{ $say('9:41', '۰۹:۴۱') }}</span>
                    <svg width="44" height="12" viewBox="0 0 44 12" fill="currentColor"><rect x="0" y="7" width="3" height="5" rx="1"/><rect x="5" y="5" width="3" height="7" rx="1"/><rect x="10" y="3" width="3" height="9" rx="1"/><rect x="15" y="1" width="3" height="11" rx="1"/><rect x="35" y="2" width="8" height="8" rx="2" opacity=".35"/><rect x="36.5" y="3.5" width="4" height="5" rx="1"/></svg>
                </div>
                <div class="m3snk-head">
                    <h4>{{ $say('Inbox', 'صندوق ورودی') }}</h4>
                    <small x-text="fd(mails.length) + ' {{ $say('unread', 'خوانده‌نشده') }}'">۴ خوانده‌نشده</small>
                </div>
                <div class="m3snk-list">
                    <template x-for="(m, i) in mails" :key="m.t + m.s">
                        <div class="m3snk-row">
                            <span class="m3snk-ava" x-bind:style="{ background: m.c }" x-text="m.t.slice(0, 1)" aria-hidden="true"></span>
                            <span style="min-inline-size: 0">
                                <b x-text="m.t"></b>
                                <small x-text="m.s"></small>
                            </span>
                            <button type="button" class="m3snk-del" x-on:click="drop(i)" x-bind:aria-label="'{{ $say('Delete mail from', 'حذف نامهٔ') }} ' + m.t">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m3 0-1 13a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 7"/></svg>
                            </button>
                        </div>
                    </template>
                    <p class="m3snk-empty" x-show="!mails.length" x-cloak>{{ $say('The inbox is empty — undo!', 'صندوق خالی شد — واگرد بزنید!') }}</p>
                </div>

                <button type="button" class="m3snk-fab" x-on:click="mails.unshift({ t: '{{ $say('Nabu', 'نابو') }}', s: '{{ $say('Your weekly digest is ready', 'خلاصهٔ هفتگی شما آماده است') }}', c: 'linear-gradient(140deg,#c4b5fd,#6d28d9)' })">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                    {{ $say('Compose', 'نوشتن') }}
                </button>

                <div class="m3snk-layer" aria-live="polite">
                    <span class="m3snk-queued" x-show="q.length" x-cloak x-text="'{{ $say('In queue', 'در صف') }}: ' + fd(q.length)"></span>
                    <template x-for="s in cur ? [cur] : []" :key="s.id">
                        <div class="m3snk" role="status">
                            <span x-text="s.m.t + ' — {{ $say('deleted', 'حذف شد') }}'"></span>
                            <button type="button" class="m3snk-act" x-on:click="undo()">{{ $say('Undo', 'واگرد') }}</button>
                            <span class="m3snk-line" aria-hidden="true"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>
        <p class="m3snk-hint">{{ $say('The bar sits at the inline end of the FAB — 16 px of clear air between them — and inverse-surface keeps it readable over any list.', 'نوار در سمتِ مقابل FAB نشسته — ۱۶ پیکسل هوای خالی میانشان — و سطح وارونه آن را روی هر فهرستی خوانا نگه می‌دارد.') }}</p>
    </div>
</section>
