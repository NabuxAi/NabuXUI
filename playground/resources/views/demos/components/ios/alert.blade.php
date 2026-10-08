{{--
    iOS's two system dialogs over a notes screen: the frosted 270px alert with
    hairline-divided buttons and a red destructive action, and the action sheet
    rising from the bottom with a vertical list and a separated destructive
    row. Scrim tap or Escape closes; focus moves in and back out.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<style>
    .ioalert-root {
        --ios-blue: #007AFF; --ios-green: #34C759; --ios-red: #FF3B30; --ios-orange: #FF9500;
        --ios-teal: #5AC8FA; --ios-indigo: #5856D6; --ios-gray: #8E8E93;
        --ios-label: #000000; --ios-label-2: rgba(60, 60, 67, .6); --ios-label-3: rgba(60, 60, 67, .3);
        --ios-fill: rgba(120, 120, 128, .2); --ios-sep: rgba(60, 60, 67, .29);
        --ios-bg: #F2F2F7; --ios-card: #FFFFFF; --ios-gray5: #E5E5EA;
        --ioalert-face: rgba(242, 242, 242, .92);
        font-family: -apple-system, system-ui, 'Segoe UI', sans-serif; color: var(--ios-label);
    }
    html[data-theme="dark"] .ioalert-root {
        --ios-blue: #0A84FF; --ios-green: #30D158; --ios-red: #FF453A; --ios-orange: #FF9F0A;
        --ios-teal: #64D2FF; --ios-indigo: #5E5CE6; --ios-gray: #8E8E93;
        --ios-label: #FFFFFF; --ios-label-2: rgba(235, 235, 245, .6); --ios-label-3: rgba(235, 235, 245, .3);
        --ios-fill: rgba(120, 120, 128, .36); --ios-sep: rgba(84, 84, 88, .6);
        --ios-bg: #000000; --ios-card: #1C1C1E; --ios-gray5: #2C2C2E;
        --ioalert-face: rgba(38, 38, 40, .86);
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .ioalert-root {
            --ios-blue: #0A84FF; --ios-green: #30D158; --ios-red: #FF453A; --ios-orange: #FF9F0A;
            --ios-teal: #64D2FF; --ios-indigo: #5E5CE6; --ios-gray: #8E8E93;
            --ios-label: #FFFFFF; --ios-label-2: rgba(235, 235, 245, .6); --ios-label-3: rgba(235, 235, 245, .3);
            --ios-fill: rgba(120, 120, 128, .36); --ios-sep: rgba(84, 84, 88, .6);
            --ios-bg: #000000; --ios-card: #1C1C1E; --ios-gray5: #2C2C2E;
            --ioalert-face: rgba(38, 38, 40, .86);
        }
    }
    .ioalert-root :focus-visible { outline: 2px solid var(--ios-blue); outline-offset: 2px; }

    .ioalert-stage { position: relative; overflow: clip; inline-size: min(100%, 22rem); block-size: 26rem; margin-inline: auto;
                     border-radius: 20px; background: var(--ios-bg); box-shadow: 0 18px 44px rgba(0, 0, 0, .18), inset 0 0 0 1px rgba(0, 0, 0, .06); }
    .ioalert-head { display: flex; align-items: baseline; gap: .5rem; padding: 1.1rem 1rem .4rem; }
    .ioalert-head h4 { margin: 0; font: 700 1.5rem/-apple-system, system-ui, sans-serif; }
    .ioalert-head span { font-size: .8rem; color: var(--ios-label-2); }
    .ioalert-list { margin: .4rem .7rem; background: var(--ios-card); border-radius: 12px; overflow: clip; }
    .ioalert-note { display: flex; align-items: center; gap: .7rem; padding: .65rem .8rem; text-align: start; }
    .ioalert-note + .ioalert-note { border-block-start: .5px solid var(--ios-sep); }
    .ioalert-note > div { flex: 1 1 auto; min-inline-size: 0; }
    .ioalert-note b { display: block; font-size: .92rem; font-weight: 600; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .ioalert-note small { display: block; font-size: .76rem; color: var(--ios-label-2); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-inline-size: 100%; }
    .ioalert-note time { margin-inline-start: auto; flex: none; font-size: .7rem; color: var(--ios-label-2); }
    .ioalert-note .del { flex: none; display: grid; place-items: center; inline-size: 28px; aspect-ratio: 1; border-radius: 50%;
                         color: var(--ios-red); transition: scale .12s ease; }
    .ioalert-note .del:active { scale: .88; }
    .ioalert-undo { display: flex; align-items: center; gap: .6rem; margin: .7rem; padding: .55rem .8rem; border-radius: 12px;
                    background: var(--ios-fill); font-size: .82rem; }
    .ioalert-undo button { margin-inline-start: auto; color: var(--ios-blue); font-size: .82rem; font-weight: 600; }

    .ioalert-scrim { position: absolute; inset: 0; z-index: 1; background: rgba(0, 0, 0, .34); opacity: 0; pointer-events: none;
                     transition: opacity .3s ease; }
    .ioalert-scrim.on { opacity: 1; pointer-events: auto; }

    .ioalert-center { position: absolute; inset: 0; z-index: 2; display: grid; place-items: center; pointer-events: none; }
    .ioalert-box { inline-size: 270px; max-inline-size: calc(100% - 2rem); border-radius: 14px; overflow: clip; text-align: center;
                   background: var(--ioalert-face); backdrop-filter: blur(40px) saturate(1.6); -webkit-backdrop-filter: blur(40px) saturate(1.6);
                   box-shadow: 0 10px 40px rgba(0, 0, 0, .25); scale: 1.18; opacity: 0; pointer-events: none;
                   transition: scale .28s cubic-bezier(.32, .72, .3, 1.1), opacity .2s ease; }
    .ioalert-center.on { pointer-events: auto; }
    .ioalert-center.on .ioalert-box { scale: 1; opacity: 1; pointer-events: auto; }
    .ioalert-body { padding: 1rem 1rem .9rem; }
    .ioalert-body h4 { margin: 0 0 .25rem; font: 600 1.05rem/-apple-system, system-ui, sans-serif; }
    .ioalert-body p { margin: 0; font-size: .82rem; color: var(--ios-label-2); }
    .ioalert-actions { display: flex; border-block-start: .5px solid var(--ios-sep); }
    .ioalert-actions button { flex: 1; block-size: 44px; color: var(--ios-blue); font-size: .98rem; }
    .ioalert-actions button + button { border-inline-start: .5px solid var(--ios-sep); }
    .ioalert-actions button:active { background: rgba(120, 120, 128, .2); }
    .ioalert-actions button.cancel { font-weight: 600; }
    .ioalert-actions button[data-destructive] { color: var(--ios-red); }

    .ioaction { position: absolute; inset-inline: .5rem; inset-block-end: .7rem; z-index: 2; display: grid; gap: .5rem;
                pointer-events: none; translate: 0 110%; transition: translate .38s cubic-bezier(.32, .72, .28, 1); }
    .ioaction.on { pointer-events: auto; translate: 0 0; }
    .ioaction-group { border-radius: 14px; overflow: clip; background: var(--ioalert-face);
                      backdrop-filter: blur(40px) saturate(1.6); -webkit-backdrop-filter: blur(40px) saturate(1.6);
                      box-shadow: 0 10px 40px rgba(0, 0, 0, .25); }
    .ioaction-group button { display: flex; inline-size: 100%; align-items: center; justify-content: center; gap: .5rem;
                             min-block-size: 56px; font-size: 1.05rem; color: var(--ios-blue); }
    .ioaction-group button + button { border-block-start: .5px solid var(--ios-sep); }
    .ioaction-group button:active { background: rgba(120, 120, 128, .2); }
    .ioaction-group button[data-destructive] { color: var(--ios-red); }
    .ioaction-group button.cancel { font-weight: 700; }

    @media (prefers-reduced-motion: reduce) {
        .ioalert-scrim, .ioalert-box, .ioaction { transition-duration: 1ms; }
    }

    /* The «Important props» table that the demo page renders after this partial
       fades its rows in only when a scroll observer marks the table revealed.
       The section sits below the fold, so in full-page captures the observer
       never fires and the body stays invisible (on this Livewire page .nx-live
       also switches off the 2.5s CSS failsafe). The rule ships only with this
       partial, so it is page-scoped: show the rows unconditionally. */
    .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr { opacity: 1; translate: none; }
</style>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Two dialogs, two weights of intent', 'دو گفت‌وگو، دو وزنِ تصمیم') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The trash on a note opens the 270px frosted alert — buttons split by hairlines, the destructive action in system red — while the ••• button raises the action sheet from the bottom edge with a separated red row. Both really delete (with an undo), and scrim tap or Escape closes either; focus lands on the first button and returns when dismissed.', 'زباله‌دانِ هر یادداشت، آلرت شیشه‌ای ۲۷۰ پیکسلی را باز می‌کند — دکمه‌ها با خط مو از هم جدا شده‌اند و کنش مخرب قرمز سیستم است — و دکمهٔ ••• اکشن‌شیت را از لبهٔ پایین با ردیف قرمزِ جدا بالا می‌آورد. هر دو واقعاً حذف می‌کنند (با بازگردانی)، و لمس اسکریم یا Escape هر دو را می‌بندد؛ فوکوس روی اولین دکمه می‌نشیند و با بستن برمی‌گردد.') }}
        </p>
    </div>

    <div class="ioalert-root" x-data="{
            mode: null, pending: null, undo: null, status: null, last: null, rtl: document.documentElement.dir === 'rtl',
            notes: [
                { t: ['Weekly shopping list', 'لیست خرید هفته'], p: ['Milk, sangak bread, Liqvan cheese', 'شیر، نان سنگک، پنیر لیقوان'], d: ['10:24', '۱۰:۲۴'] },
                { t: ['Design review notes', 'یادداشت جلسهٔ طراحی'], p: ['Ship the glass tab bar first', 'اول تب‌بار شیشه‌ای را بفرستیم'], d: ['Yesterday', 'دیروز'] },
                { t: ['North trip ideas', 'ایده‌های سفر شمال'], p: ['Namak Abrood, a cabin, morning mist', 'نمک‌آبرود، یک کلبه، مه صبحگاهی'], d: ['Monday', 'دوشنبه'] },
            ],
            open(m, i) { this.mode = m; this.pending = i ?? null; this.last = document.activeElement;
                         this.$nextTick(() => this.$refs[m + 'Box']?.querySelector('button')?.focus()) },
            close() { this.mode = null; this.pending = null; this.last?.focus?.() },
            del() { if (this.pending !== null) { this.undo = this.notes.splice(this.pending, 1)[0] } this.close() },
            restore() { if (this.undo) { this.notes.unshift(this.undo); this.undo = null } },
            act(a) { this.status = a; this.close() },
        }"
         x-on:keydown.escape.window="mode && close()">
        <div class="ioalert-stage">
            <div class="ioalert-head">
                <h4>{{ $say('Notes', 'یادداشت‌ها') }}</h4>
                <span x-text="notes.length + (rtl ? ' یادداشت' : ' notes')">۳ یادداشت</span>
            </div>

            <div class="ioalert-list">
                <template x-for="(n, i) in notes" :key="i">
                    <div class="ioalert-note">
                        <div>
                            <b x-text="rtl ? n.t[1] : n.t[0]"></b>
                            <small x-text="rtl ? n.p[1] : n.p[0]"></small>
                        </div>
                        <time x-text="rtl ? n.d[1] : n.d[0]"></time>
                        <button type="button" class="del" x-on:click="open('alert', i)"
                                :aria-label="$say('Delete note', 'حذف یادداشت')">
                            <svg width="15" height="15" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2.5 4h11M6.5 4V2.8c0-.4.3-.8.8-.8h1.4c.5 0 .8.4.8.8V4M4 4l.7 9c0 .6.5 1 1 1h4.6c.5 0 1-.4 1-1L12 4M6.6 7v4M9.4 7v4"/></svg>
                        </button>
                    </div>
                </template>
            </div>

            <div class="ioalert-undo" x-show="undo" x-cloak aria-live="polite">
                <span>{{ $say('Note deleted.', 'یادداشت حذف شد.') }}</span>
                <button type="button" x-on:click="restore()">{{ $say('Undo', 'بازگردانی') }}</button>
            </div>

            <div style="display: flex; justify-content: center; padding-block-end: 1rem">
                <button type="button" x-on:click="open('sheet')" style="padding: .45rem 1rem; border-radius: 999px; background: var(--ios-fill); font-size: .85rem; font-weight: 500"
                        :aria-label="$say('More actions', 'کنش‌های بیشتر')">•••</button>
            </div>

            <button type="button" class="ioalert-scrim" :class="{ on: mode }" x-on:click="close()" :tabindex="mode ? 0 : -1"
                    :aria-label="$say('Close dialog', 'بستن گفت‌وگو')"></button>

            <div class="ioalert-center" :class="{ on: mode === 'alert' }">
                <div class="ioalert-box" x-ref="alertBox" role="alertdialog" :aria-label="$say('Delete note?', 'حذف یادداشت؟')">
                    <div class="ioalert-body">
                        <h4>{{ $say('Delete note?', 'حذف یادداشت؟') }}</h4>
                        <p>{{ $say('This note will be moved out of your list. You can undo it right after.', 'این یادداشت از فهرست شما حذف می‌شود. بلافاصله پس از آن می‌توانید بازش گردانید.') }}</p>
                    </div>
                    <div class="ioalert-actions">
                        <button type="button" class="cancel" x-on:click="close()">{{ $say('Cancel', 'لغو') }}</button>
                        <button type="button" data-destructive x-on:click="del()">{{ $say('Delete', 'حذف') }}</button>
                    </div>
                </div>
            </div>

            <div class="ioaction" :class="{ on: mode === 'sheet' }">
                <div class="ioaction-group" x-ref="sheetBox" role="menu" :aria-label="$say('Note actions', 'کنش‌های یادداشت')">
                    <button type="button" role="menuitem" x-on:click="act(rtl ? 'اشتراک‌گذاری' : 'Share')">
                        <svg width="17" height="17" viewBox="0 0 17 17" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8.5 1.8v9M5.3 4.6l3.2-3 3.2 3M3 9.5v4a1.6 1.6 0 0 0 1.6 1.6h7.8a1.6 1.6 0 0 0 1.6-1.6v-4"/></svg>
                        {{ $say('Share', 'اشتراک‌گذاری') }}
                    </button>
                    <button type="button" role="menuitem" x-on:click="act(rtl ? 'نقل‌قول' : 'Duplicate')">
                        <svg width="17" height="17" viewBox="0 0 17 17" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="5.5" y="5.5" width="9" height="9" rx="2"/><path d="M11.5 5.5v-1a2 2 0 0 0-2-2h-6a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h1"/></svg>
                        {{ $say('Duplicate', ' تکثیر') }}
                    </button>
                    <button type="button" role="menuitem" data-destructive x-on:click="del()">
                        <svg width="17" height="17" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2.5 4h11M6.5 4V2.8c0-.4.3-.8.8-.8h1.4c.5 0 .8.4.8.8V4M4 4l.7 9c0 .6.5 1 1 1h4.6c.5 0 1-.4 1-1L12 4M6.6 7v4M9.4 7v4"/></svg>
                        {{ $say('Delete note', 'حذف یادداشت') }}
                    </button>
                </div>
                <div class="ioaction-group">
                    <button type="button" class="cancel" x-on:click="close()">{{ $say('Cancel', 'لغو') }}</button>
                </div>
            </div>
        </div>

        <p aria-live="polite" style="margin: .8rem 0 0; text-align: center; font-size: .82rem; color: var(--nx-text-muted)"
           x-text="status ? (rtl ? 'آخرین کنش: ' + status : 'Last action: ' + status) : (rtl ? 'کنشی انتخاب نشده.' : 'No action chosen yet.')">…</p>
    </div>
</section>
