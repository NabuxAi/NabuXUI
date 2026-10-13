{{--
    Material dialogs staged on a files screen: the basic dialog (icon,
    headline «حذف پوشه؟», supporting text, end-aligned text buttons, 28 px
    radius, spring from 80% scale, cancel focused on open, Escape closes, the
    delete really deletes) and the full-screen dialog for a multi-step flow —
    top bar with ✕ and «ذخیره», content, bottom bar — over the 32% scrim.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    [x-cloak] { display: none !important; }
    .m3dlg-root {
        --m3dlg-primary: #6750A4; --m3dlg-on-primary: #FFFFFF;
        --m3dlg-primary-container: #EADDFF; --m3dlg-on-primary-container: #21005D;
        --m3dlg-secondary-container: #E8DEF8; --m3dlg-on-secondary-container: #1D192B;
        --m3dlg-surface: #FEF7FF; --m3dlg-surface-container: #F3EDF7;
        --m3dlg-surface-container-high: #ECE6F0;
        --m3dlg-on-surface: #1D1B20; --m3dlg-on-surface-variant: #49454F;
        --m3dlg-outline: #79747E; --m3dlg-outline-variant: #CAC4D0;
        --m3dlg-error: #B3261E; --m3dlg-scrim: rgba(0, 0, 0, .32);
        --m3dlg-spring: cubic-bezier(.05, .7, .1, 1);
        font-family: Roboto, system-ui, sans-serif;
        display: grid; gap: 1.5rem; justify-items: center;
    }
    html[data-theme="dark"] .m3dlg-root {
        --m3dlg-primary: #D0BCFF; --m3dlg-on-primary: #381E72;
        --m3dlg-primary-container: #4F378B; --m3dlg-on-primary-container: #EADDFF;
        --m3dlg-secondary-container: #4A4458; --m3dlg-on-secondary-container: #E8DEF8;
        --m3dlg-surface: #141218; --m3dlg-surface-container: #211F26;
        --m3dlg-surface-container-high: #2B2930;
        --m3dlg-on-surface: #E6E0E9; --m3dlg-on-surface-variant: #CAC4D0;
        --m3dlg-outline: #938F99; --m3dlg-outline-variant: #49454F;
        --m3dlg-error: #F2B8B5;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .m3dlg-root {
            --m3dlg-primary: #D0BCFF; --m3dlg-on-primary: #381E72;
            --m3dlg-primary-container: #4F378B; --m3dlg-on-primary-container: #EADDFF;
            --m3dlg-secondary-container: #4A4458; --m3dlg-on-secondary-container: #E8DEF8;
            --m3dlg-surface: #141218; --m3dlg-surface-container: #211F26;
            --m3dlg-surface-container-high: #2B2930;
            --m3dlg-on-surface: #E6E0E9; --m3dlg-on-surface-variant: #CAC4D0;
            --m3dlg-outline: #938F99; --m3dlg-outline-variant: #49454F;
            --m3dlg-error: #F2B8B5;
        }
    }
    .m3dlg-frame { inline-size: min(100%, 22rem); padding: .625rem; border-radius: 3rem; background: #17171b; box-shadow: 0 24px 48px -24px rgba(0, 0, 0, .5); }
    .m3dlg-screen { position: relative; overflow: clip; block-size: 27rem; border-radius: 2.5rem; background: var(--m3dlg-surface); display: flex; flex-direction: column; }
    .m3dlg-status { display: flex; align-items: center; justify-content: space-between; padding: .8rem 1.4rem .2rem; color: var(--m3dlg-on-surface); font: 600 .72rem/1 Roboto, system-ui, sans-serif; }
    .m3dlg-head { display: flex; align-items: center; gap: .5rem; padding: .4rem .8rem .3rem; }
    .m3dlg-head h4 { margin: 0; font: 500 1.2rem/1.2 Roboto, system-ui, sans-serif; color: var(--m3dlg-on-surface); }
    .m3dlg-head small { margin-inline-start: auto; font: 400 .74rem/1 Roboto, system-ui, sans-serif; color: var(--m3dlg-on-surface-variant); }
    .m3dlg-grid { flex: 1; display: grid; grid-template-columns: repeat(2, 1fr); gap: .55rem; align-content: start; padding: .5rem .9rem 1rem; overflow-y: auto; }
    .m3dlg-folder { position: relative; display: grid; gap: .45rem; padding: .8rem; border-radius: 1rem; background: var(--m3dlg-surface-container); }
    .m3dlg-folder i { inline-size: 2.6rem; aspect-ratio: 1; border-radius: .65rem; }
    .m3dlg-folder b { font: 500 .85rem/1.3 Roboto, system-ui, sans-serif; color: var(--m3dlg-on-surface); }
    .m3dlg-folder small { font: 400 .72rem/1.3 Roboto, system-ui, sans-serif; color: var(--m3dlg-on-surface-variant); }
    .m3dlg-folder-x { position: absolute; inset-block-start: .35rem; inset-inline-end: .35rem; display: grid; place-items: center; inline-size: 2rem; aspect-ratio: 1; border: none; border-radius: 50%; background: transparent; color: var(--m3dlg-on-surface-variant); cursor: pointer; }
    .m3dlg-folder-x:hover { background: color-mix(in srgb, var(--m3dlg-error) 12%, transparent); color: var(--m3dlg-error); }
    .m3dlg-folder-x:focus-visible { outline: 2px solid var(--m3dlg-primary); outline-offset: 1px; }
    .m3dlg-empty { grid-column: 1 / -1; padding: 2rem .5rem; text-align: center; font: 400 .84rem/1.5 Roboto, system-ui, sans-serif; color: var(--m3dlg-on-surface-variant); }
    .m3dlg-veil { position: absolute; inset: 0; z-index: 4; display: grid; place-items: center; background: var(--m3dlg-scrim); animation: m3dlg-veil .25s ease forwards; }
    @keyframes m3dlg-veil { from { opacity: 0; } }
    .m3dlg-card { inline-size: min(calc(100% - 3rem), 19.5rem); border-radius: 28px; padding: 1.5rem; background: var(--m3dlg-surface-container-high); color: var(--m3dlg-on-surface); animation: m3dlg-pop .4s var(--m3dlg-spring); }
    @keyframes m3dlg-pop { from { scale: .8; opacity: 0; } }
    .m3dlg-ico { display: grid; place-items: center; inline-size: 3rem; aspect-ratio: 1; margin-block-end: 1rem; border-radius: 50%; background: var(--m3dlg-primary-container); color: var(--m3dlg-on-primary-container); }
    .m3dlg-card h2 { margin: 0 0 .5rem; font: 500 1.4rem/1.3 Roboto, system-ui, sans-serif; }
    .m3dlg-card p { margin: 0; font: 400 .84rem/1.55 Roboto, system-ui, sans-serif; color: var(--m3dlg-on-surface-variant); }
    .m3dlg-foot { display: flex; justify-content: flex-end; gap: .35rem; padding-block-start: 1.4rem; }
    .m3dlg-tbtn { border: none; border-radius: 999px; padding: .55rem .9rem; background: transparent; color: var(--m3dlg-primary); cursor: pointer; font: 600 .84rem/1 Roboto, system-ui, sans-serif; }
    .m3dlg-tbtn:hover { background: color-mix(in srgb, currentColor 10%, transparent); }
    .m3dlg-tbtn:focus-visible { outline: 2px solid var(--m3dlg-primary); outline-offset: 2px; }
    .m3dlg-tbtn[data-destructive] { color: var(--m3dlg-error); }
    .m3dlg-full { position: absolute; inset: 0; z-index: 5; display: flex; flex-direction: column; background: var(--m3dlg-surface); animation: m3dlg-pop .4s var(--m3dlg-spring); }
    .m3dlg-top { display: flex; align-items: center; gap: .4rem; padding: 1.1rem .9rem .5rem; }
    .m3dlg-top h2 { margin: 0; font: 500 1.15rem/1.2 Roboto, system-ui, sans-serif; color: var(--m3dlg-on-surface); }
    .m3dlg-body { flex: 1; display: grid; align-content: start; gap: 1rem; padding: 1rem 1.25rem; overflow-y: auto; }
    .m3dlg-field { position: relative; }
    .m3dlg-field label { position: absolute; inset-block-start: -.5rem; inset-inline-start: .75rem; padding-inline: .3rem; font: 500 .72rem/1 Roboto, system-ui, sans-serif; color: var(--m3dlg-on-surface-variant); background: var(--m3dlg-surface); border-radius: 999px; }
    .m3dlg-field input { inline-size: 100%; block-size: 3.25rem; padding-inline: 1rem; border: 1px solid var(--m3dlg-outline); border-radius: .5rem; background: transparent; color: var(--m3dlg-on-surface); font: 400 .9rem Roboto, system-ui, sans-serif; }
    .m3dlg-field input:focus-visible { outline: 2px solid var(--m3dlg-primary); outline-offset: 1px; }
    .m3dlg-tpl { display: grid; gap: .5rem; }
    .m3dlg-tpl b { font: 500 .8rem/1 Roboto, system-ui, sans-serif; color: var(--m3dlg-on-surface-variant); }
    .m3dlg-tpl-row { display: flex; flex-wrap: wrap; gap: .5rem; }
    .m3dlg-bottom { display: flex; justify-content: flex-end; gap: .35rem; padding: .6rem .9rem; border-block-start: 1px solid var(--m3dlg-outline-variant); }
    .m3dlg-tbtn[disabled] { color: color-mix(in srgb, var(--m3dlg-on-surface) 38%, transparent); cursor: not-allowed; background: transparent; }
    .m3dlg-hint { margin: 0; font: 400 .8rem/1.5 Roboto, system-ui, sans-serif; color: var(--nx-text-muted); max-width: 56ch; }
    /* The props table that component-demo renders on this page keeps its rows
       at opacity 0 until the scroll-reveal observer marks the table revealed,
       and .nx-live disables the CSS failsafe — a capture that never scrolls
       shows an empty body. Force the rows visible so the table reads without
       any JS, and let the long fourth column wrap instead of sliding past the
       right edge on narrow screens. Colourless rules: both themes unaffected. */
    .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr { opacity: 1; translate: none; }
    .nx-data-table :is(td, th) { white-space: normal; }
    @media (max-width: 640px) {
        .nx-data-table :is(td, th) { padding-inline: .5rem; }
    }
</style>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Two dialogs, one screen', 'دو دیالوگ، یک صفحه') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('The bin on each folder opens the basic dialog — icon, headline and end-aligned text buttons over the 32% scrim, springing from 80% scale. The + opens the full-screen flow with ✕, «Save» and a bottom bar. Escape closes both.', 'سطل هر پوشه دیالوگ پایه را باز می‌کند — آیکن، سرخط و دکمه‌های متنیِ انتهایی روی پردهٔ ۳۲٪، با فنر از مقیاس ۸۰٪. علامت + جریان تمام‌صفحه را با ✕ و «ذخیره» و نوار پایین باز می‌کند. Escape هر دو را می‌بندد.') }}
        </p>
    </div>

    <div class="m3dlg-root" x-data="{
            folders: [
                { n: 'عکس‌های سفر', c: '۲۴ فایل', g: 'linear-gradient(140deg,#93c5fd,#1d4ed8)' },
                { n: 'سندهای مالی', c: '۱۲ فایل', g: 'linear-gradient(140deg,#86efac,#15803d)' },
                { n: 'پروژهٔ نابو', c: '۳۱ فایل', g: 'linear-gradient(140deg,#c4b5fd,#6d28d9)' },
                { n: 'موسیقی', c: '۴۸ فایل', g: 'linear-gradient(140deg,#fdba74,#c2410c)' },
            ],
            basic: false, target: null, full: false, name: '',
            openBasic(f) { this.target = f; this.basic = true; $nextTick(() => this.$refs.cancelB.focus()); },
            openFull() { this.full = true; this.name = ''; $nextTick(() => this.$refs.nameI.focus()); },
            closeAll() { this.basic = false; this.full = false; },
            confirmDel() { this.folders = this.folders.filter(x => x !== this.target); this.basic = false; },
            save() { const n = this.name.trim(); if (!n) return; this.folders.unshift({ n, c: '۰ فایل', g: 'linear-gradient(140deg,#67e8f9,#0e7490)' }); this.full = false; },
        }"
        x-on:keydown.escape.window="closeAll()">
        <div class="m3dlg-frame">
            <div class="m3dlg-screen">
                <div class="m3dlg-status" aria-hidden="true">
                    <span>{{ $say('9:41', '۰۹:۴۱') }}</span>
                    <svg width="44" height="12" viewBox="0 0 44 12" fill="currentColor"><rect x="0" y="7" width="3" height="5" rx="1"/><rect x="5" y="5" width="3" height="7" rx="1"/><rect x="10" y="3" width="3" height="9" rx="1"/><rect x="15" y="1" width="3" height="11" rx="1"/><rect x="35" y="2" width="8" height="8" rx="2" opacity=".35"/><rect x="36.5" y="3.5" width="4" height="5" rx="1"/></svg>
                </div>
                <div class="m3dlg-head">
                    <h4>{{ $say('Files', 'پرونده‌ها') }}</h4>
                    <small x-text="folders.length + ' {{ $say('folders', 'پوشه') }}'">۴ پوشه</small>
                    <button type="button" class="m3dlg-tbtn" style="display: inline-flex; align-items: center; gap: .3rem; padding: .45rem .8rem; background: var(--m3dlg-secondary-container); color: var(--m3dlg-on-secondary-container)" x-on:click="openFull()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                        {{ $say('New folder', 'پوشهٔ جدید') }}
                    </button>
                </div>
                <div class="m3dlg-grid">
                    <template x-for="f in folders" :key="f.n">
                        <div class="m3dlg-folder">
                            <i aria-hidden="true" x-bind:style="{ background: f.g }"></i>
                            <b x-text="f.n"></b>
                            <small x-text="f.c"></small>
                            <button type="button" class="m3dlg-folder-x" x-on:click="openBasic(f)" aria-label="{{ $say('Delete folder', 'حذف پوشه') }}">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m3 0-1 13a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 7"/></svg>
                            </button>
                        </div>
                    </template>
                    <p class="m3dlg-empty" x-show="!folders.length" x-cloak>{{ $say('No folders left — everything is safe in the trash.', 'پوشه‌ای نماند — همه چیز در سطل بازیافت در امان است.') }}</p>
                </div>

                <div class="m3dlg-veil" x-show="basic" x-cloak x-on:click.self="closeAll()">
                    <div class="m3dlg-card" role="alertdialog" aria-modal="true" x-bind:aria-label="'{{ $say('Delete', 'حذف') }} ' + (target ? target.n : '')">
                        <span class="m3dlg-ico" aria-hidden="true">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m3 0-1 13a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 7"/><path d="M10 11v6M14 11v6"/></svg>
                        </span>
                        <h2>{{ $say('Delete this folder?', 'حذف پوشه؟') }}</h2>
                        <p x-show="target" x-text="'{{ $say('The folder “', 'پوشهٔ «') }}' + (target ? target.n : '') + '{{ $say('” and every file inside it will be deleted.', '» و همهٔ فایل‌های داخلش حذف می‌شوند.') }}'"></p>
                        <div class="m3dlg-foot">
                            <button type="button" class="m3dlg-tbtn" x-ref="cancelB" x-on:click="closeAll()">{{ $say('Cancel', 'انصراف') }}</button>
                            <button type="button" class="m3dlg-tbtn" data-destructive x-on:click="confirmDel()">{{ $say('Delete', 'حذف') }}</button>
                        </div>
                    </div>
                </div>

                <div class="m3dlg-full" x-show="full" x-cloak role="dialog" aria-modal="true" aria-label="{{ $say('New folder', 'پوشهٔ جدید') }}">
                    <div class="m3dlg-top">
                        <button type="button" class="m3dlg-ico" style="inline-size: 2.75rem; margin: 0; background: transparent; color: var(--m3dlg-on-surface)" x-on:click="closeAll()" aria-label="{{ $say('Close', 'بستن') }}">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
                        </button>
                        <h2>{{ $say('New folder', 'پوشهٔ جدید') }}</h2>
                        <button type="button" class="m3dlg-tbtn" style="margin-inline-start: auto" x-bind:disabled="!name.trim()" x-on:click="save()">{{ $say('Save', 'ذخیره') }}</button>
                    </div>
                    <div class="m3dlg-body">
                        <div class="m3dlg-field">
                            <label for="m3dlg-name">{{ $say('Folder name', 'نام پوشه') }}</label>
                            <input id="m3dlg-name" type="text" x-ref="nameI" x-model="name" x-on:keydown.enter="save()" autocomplete="off">
                        </div>
                        <div class="m3dlg-tpl">
                            <b>{{ $say('Start from a template', 'از یک قالب شروع کنید') }}</b>
                            <div class="m3dlg-tpl-row">
                                <button type="button" class="m3dlg-tbtn" style="background: var(--m3dlg-secondary-container); color: var(--m3dlg-on-secondary-container)" x-on:click="name = '{{ $say('Course photos', 'عکس‌های دوره') }}'">{{ $say('Course photos', 'عکس‌های دوره') }}</button>
                                <button type="button" class="m3dlg-tbtn" style="background: var(--m3dlg-secondary-container); color: var(--m3dlg-on-secondary-container)" x-on:click="name = '{{ $say('Invoices', 'صورت‌حساب‌ها') }}'">{{ $say('Invoices', 'صورت‌حساب‌ها') }}</button>
                                <button type="button" class="m3dlg-tbtn" style="background: var(--m3dlg-secondary-container); color: var(--m3dlg-on-secondary-container)" x-on:click="name = '{{ $say('Voice notes', 'یادداشت‌های صوتی') }}'">{{ $say('Voice notes', 'یادداشت‌های صوتی') }}</button>
                            </div>
                        </div>
                    </div>
                    <div class="m3dlg-bottom">
                        <button type="button" class="m3dlg-tbtn" x-on:click="closeAll()">{{ $say('Cancel', 'انصراف') }}</button>
                        <button type="button" class="m3dlg-tbtn" x-bind:disabled="!name.trim()" x-on:click="save()">{{ $say('Save', 'ذخیره') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <p class="m3dlg-hint">{{ $say('The cancel button takes focus the moment the dialog opens, exactly as the a11y guidance asks; the destructive action wears the error colour.', 'دکمهٔ انصراف همان لحظه که دیالوگ باز می‌شود فوکوس را می‌گیرد — همان‌طور که راهنمای دسترس‌پذیری می‌خواهد؛ اقدام مخرب رنگ خطا را پوشیده است.') }}</p>
    </div>
</section>
