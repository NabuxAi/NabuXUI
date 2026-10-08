{{--
    The Material 3 FAB staged on a notes screen: the extended FAB really
    composes a note, the three square sizes sit beside it, and a second stage
    opens the FAB menu — items peek out upward, the + rotates, the backdrop
    dims, Escape and outside taps close it.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    .m3fab-root {
        --m3fab-primary: #6750A4; --m3fab-on-primary: #FFFFFF;
        --m3fab-primary-container: #EADDFF; --m3fab-on-primary-container: #21005D;
        --m3fab-secondary-container: #E8DEF8; --m3fab-on-secondary-container: #1D192B;
        --m3fab-surface: #FEF7FF; --m3fab-surface-container: #F3EDF7;
        --m3fab-surface-container-high: #ECE6F0; --m3fab-surface-container-highest: #E6E0E9;
        --m3fab-on-surface: #1D1B20; --m3fab-on-surface-variant: #49454F;
        --m3fab-outline-variant: #CAC4D0; --m3fab-scrim: rgba(0, 0, 0, .32);
        --m3fab-inverse-surface: #322F35; --m3fab-inverse-on-surface: #F5EFF7;
        --m3fab-ease: cubic-bezier(.2, 0, 0, 1); --m3fab-spring: cubic-bezier(.05, .7, .1, 1);
        font-family: Roboto, system-ui, sans-serif;
        display: grid; gap: 1.5rem; justify-items: center;
        /* definite width so the stage's min(100%, 22rem) resolves against a
           real box — the menu stage's only in-flow children are absolutely
           positioned, and a shrink-to-fit root sized itself to 0 */
        inline-size: 100%;
    }
    html[data-theme="dark"] .m3fab-root {
        --m3fab-primary: #D0BCFF; --m3fab-on-primary: #381E72;
        --m3fab-primary-container: #4F378B; --m3fab-on-primary-container: #EADDFF;
        --m3fab-secondary-container: #4A4458; --m3fab-on-secondary-container: #E8DEF8;
        --m3fab-surface: #141218; --m3fab-surface-container: #211F26;
        --m3fab-surface-container-high: #2B2930; --m3fab-surface-container-highest: #36343B;
        --m3fab-on-surface: #E6E0E9; --m3fab-on-surface-variant: #CAC4D0;
        --m3fab-outline-variant: #49454F;
        --m3fab-inverse-surface: #E6E0E9; --m3fab-inverse-on-surface: #1D1B20;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .m3fab-root {
            --m3fab-primary: #D0BCFF; --m3fab-on-primary: #381E72;
            --m3fab-primary-container: #4F378B; --m3fab-on-primary-container: #EADDFF;
            --m3fab-secondary-container: #4A4458; --m3fab-on-secondary-container: #E8DEF8;
            --m3fab-surface: #141218; --m3fab-surface-container: #211F26;
            --m3fab-surface-container-high: #2B2930; --m3fab-surface-container-highest: #36343B;
            --m3fab-on-surface: #E6E0E9; --m3fab-on-surface-variant: #CAC4D0;
            --m3fab-outline-variant: #49454F;
            --m3fab-inverse-surface: #E6E0E9; --m3fab-inverse-on-surface: #1D1B20;
        }
    }
    .m3fab-frame { inline-size: min(100%, 22rem); padding: .625rem; border-radius: 3rem; background: #17171b; box-shadow: 0 24px 48px -24px rgba(0, 0, 0, .5); }
    .m3fab-screen { position: relative; overflow: clip; block-size: 26rem; border-radius: 2.5rem; background: var(--m3fab-surface); display: flex; flex-direction: column; }
    .m3fab-status { display: flex; align-items: center; justify-content: space-between; padding: .8rem 1.4rem .3rem; color: var(--m3fab-on-surface); font: 600 .72rem/1 Roboto, system-ui, sans-serif; }
    .m3fab-head { display: flex; align-items: center; gap: .75rem; padding: .6rem 1.25rem .5rem; }
    .m3fab-head h4 { margin: 0; font: 500 1.25rem/1.2 Roboto, system-ui, sans-serif; color: var(--m3fab-on-surface); }
    .m3fab-list { display: grid; gap: .1rem; padding: .25rem .75rem 5.5rem; overflow-y: auto; }
    .m3fab-note { display: grid; gap: .3rem; padding: .8rem .9rem; border-radius: 1rem; background: var(--m3fab-surface-container-high); }
    .m3fab-note b { font: 500 .88rem/1.3 Roboto, system-ui, sans-serif; color: var(--m3fab-on-surface); }
    .m3fab-note span { font: 400 .78rem/1.4 Roboto, system-ui, sans-serif; color: var(--m3fab-on-surface-variant); }
    .m3fab, .m3fab-item-icon { position: relative; display: inline-flex; align-items: center; justify-content: center; gap: .75rem; border: none; cursor: pointer; font-family: Roboto, system-ui, sans-serif; background: var(--m3fab-primary-container); color: var(--m3fab-on-primary-container); box-shadow: 0 4px 8px 3px rgba(0, 0, 0, .15), 0 1px 3px rgba(0, 0, 0, .3); }
    .m3fab:focus-visible, .m3fab-item-icon:focus-visible { outline: 2px solid var(--m3fab-primary); outline-offset: 2px; }
    .m3fab::after, .m3fab-item-icon::after { content: ''; position: absolute; inset: 0; border-radius: inherit; background: currentColor; opacity: 0; transition: opacity .15s; pointer-events: none; }
    .m3fab:hover::after, .m3fab-item-icon:hover::after { opacity: .08; }
    .m3fab:active::after, .m3fab-item-icon:active::after { opacity: .12; }
    .m3fab-sm { inline-size: 2.5rem; aspect-ratio: 1; border-radius: .75rem; }
    .m3fab-md { inline-size: 3.5rem; aspect-ratio: 1; border-radius: 1rem; }
    .m3fab-lg { inline-size: 6rem; aspect-ratio: 1; border-radius: 1.75rem; }
    .m3fab-xl { block-size: 3.5rem; padding-inline: 1.25rem; border-radius: 1rem; font: 600 .9rem/1 Roboto, system-ui, sans-serif; }
    .m3fab-place { position: absolute; inset-block-end: 1.25rem; inset-inline-end: 1.25rem; z-index: 3; }
    /* min(100%, 22rem) collapsed to 0 here until .m3fab-root carried a
       definite width: the cluster's only in-flow children are absolutely
       positioned, so the shrink-to-fit root sized itself to 0 and
       overflow: clip erased the + with it. */
    .m3fab-cluster { position: relative; inline-size: min(100%, 22rem); block-size: 17rem; border-radius: 1.75rem; overflow: clip; background: var(--m3fab-surface-container); }
    .m3fab-veil { position: absolute; inset: 0; background: var(--m3fab-scrim); opacity: 0; transition: opacity .25s var(--m3fab-ease); pointer-events: none; }
    .m3fab-cluster[data-open='true'] .m3fab-veil { opacity: 1; pointer-events: auto; }
    .m3fab-menu { position: absolute; inset-block-end: 1.25rem; inset-inline-end: 1.25rem; display: grid; justify-items: end; gap: .75rem; z-index: 3; }
    .m3fab-actions { display: grid; gap: .6rem; justify-items: end; margin-block-end: .25rem; }
    .m3fab-action { display: flex; align-items: center; gap: .65rem; opacity: 0; translate: 0 .75rem; scale: .85; pointer-events: none; transition: opacity .2s var(--m3fab-ease), translate .2s var(--m3fab-spring), scale .2s var(--m3fab-spring); }
    .m3fab-cluster[data-open='true'] .m3fab-action { opacity: 1; translate: 0 0; scale: 1; pointer-events: auto; }
    .m3fab-cluster[data-open='true'] .m3fab-action:nth-child(1) { transition-delay: .1s; }
    .m3fab-cluster[data-open='true'] .m3fab-action:nth-child(2) { transition-delay: .06s; }
    .m3fab-cluster[data-open='true'] .m3fab-action:nth-child(3) { transition-delay: .02s; }
    .m3fab-action span { padding: .45rem .8rem; border-radius: .75rem; background: var(--m3fab-inverse-surface); color: var(--m3fab-inverse-on-surface); font: 500 .8rem/1 Roboto, system-ui, sans-serif; }
    .m3fab-item-icon { inline-size: 2.75rem; aspect-ratio: 1; border-radius: .875rem; }
    .m3fab-plus { display: grid; place-items: center; transition: rotate .3s var(--m3fab-spring); }
    .m3fab-cluster[data-open='true'] .m3fab-plus { rotate: 45deg; }
    .m3fab-sizes { display: flex; flex-wrap: wrap; gap: 1.25rem; justify-content: center; align-items: flex-end; }
    .m3fab-cell { display: grid; gap: .5rem; justify-items: center; }
    .m3fab-cell small { font: 500 .72rem/1 Roboto, system-ui, sans-serif; color: var(--nx-text-muted); }
    /* The shared "Important props" table below this stage reveals its rows on
       scroll (opacity: 0 until an IntersectionObserver stamps
       [data-nx-revealed]; the CSS failsafe is off once .nx-live is set). A
       full-page capture never scrolls, so the rows stayed invisible and the
       table looked header-only. Pin this page's table rows visible — scoped
       through :has(.m3fab-root), so it never reaches another demo page. */
    :where(.nx-js) .pg:has(.m3fab-root) .nx-data-table[data-nx-reveal] tbody tr {
        opacity: 1; translate: none;
    }
    /* At phone widths the table's nowrap cells run past the inline edge and
       the fourth column reads as cut off; let this page's table and snippet
       wrap so nothing is read as cut off. */
    @media (max-width: 480px) {
        .pg:has(.m3fab-root) .nx-data-table :is(th, td) {
            white-space: normal; padding-inline: .5rem; overflow-wrap: break-word;
        }
        .pg:has(.m3fab-root) pre code { white-space: pre-wrap; overflow-wrap: anywhere; }
    }
    @media (prefers-reduced-motion: reduce) {
        .m3fab-root * { animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
    }
</style>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A FAB that composes', 'دکمهٔ شناوری که می‌نویسد') }}</h3>
        <p style="margin: 0; max-width: 50ch; color: var(--nx-text-muted)">
            {{ $say('The extended FAB floats over the notes list in its primary-container colour. Tap it and a new note really lands in the list.', 'دکمهٔ شناورِ کشیده با رنگ محفظهٔ اصلی روی فهرست یادداشت‌ها شناور است. لمسش کنید تا یک یادداشت تازه واقعاً در فهرست بنشیند.') }}
        </p>
    </div>

    <div class="m3fab-root" x-data="{
            notes: [
                { t: 'فهرست خرید', s: 'پنیر، گردو، نان تازه، چای سیاه' },
                { t: 'جلسهٔ سه‌شنبه', s: 'اسلایدهای فصل را پیش از ظهر بفرست' },
                { t: 'کتاب‌های نیمه‌تمام', s: 'صد سال تنهایی — فصل هفتم' },
            ], c: 0,
            compose() { this.c++; this.notes.unshift({ t: 'یادداشت تازه', s: 'اینجا بنویس… (' + this.c + ')' }) },
        }">
        <div class="m3fab-frame">
            <div class="m3fab-screen">
                <div class="m3fab-status" aria-hidden="true">
                    <span>{{ $say('9:41', '۰۹:۴۱') }}</span>
                    <svg width="44" height="12" viewBox="0 0 44 12" fill="currentColor"><rect x="0" y="7" width="3" height="5" rx="1"/><rect x="5" y="5" width="3" height="7" rx="1"/><rect x="10" y="3" width="3" height="9" rx="1"/><rect x="15" y="1" width="3" height="11" rx="1"/><rect x="35" y="2" width="8" height="8" rx="2" opacity=".35"/><rect x="36.5" y="3.5" width="4" height="5" rx="1"/></svg>
                </div>
                <div class="m3fab-head">
                    <h4>{{ $say('Notes', 'یادداشت‌ها') }}</h4>
                    <span style="margin-inline-start: auto; font: 400 .78rem/1 Roboto, system-ui, sans-serif; color: var(--m3fab-on-surface-variant)" x-text="notes.length + ' {{ $say('notes', 'یادداشت') }}'">۳ یادداشت</span>
                </div>
                <div class="m3fab-list">
                    <template x-for="(n, i) in notes" :key="i + n.t + n.s">
                        <div class="m3fab-note">
                            <b x-text="n.t"></b>
                            <span x-text="n.s"></span>
                        </div>
                    </template>
                </div>
                <button type="button" class="m3fab m3fab-xl m3fab-place" x-on:click="compose()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                    {{ $say('New note', 'یادداشت جدید') }}
                </button>
            </div>
        </div>

        <div class="m3fab-sizes" aria-label="{{ $say('FAB sizes', 'اندازه‌های دکمهٔ شناور') }}">
            <div class="m3fab-cell"><button type="button" class="m3fab m3fab-sm" aria-label="{{ $say('Small FAB', 'دکمهٔ کوچک') }}"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button><small>small · 40</small></div>
            <div class="m3fab-cell"><button type="button" class="m3fab m3fab-md" aria-label="{{ $say('Medium FAB', 'دکمهٔ متوسط') }}"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button><small>medium · 56</small></div>
            <div class="m3fab-cell"><button type="button" class="m3fab m3fab-lg" aria-label="{{ $say('Large FAB', 'دکمهٔ بزرگ') }}"><svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></button><small>large · 96</small></div>
        </div>
    </div>
</section>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The FAB menu', 'منوی دکمهٔ شناور') }}</h3>
        <p style="margin: 0; max-width: 50ch; color: var(--nx-text-muted)">
            {{ $say('Open the + and three labelled actions peek upward, the backdrop dims, and Escape or a tap outside closes it again.', 'علامت + را باز کنید تا سه اقدامِ برچسب‌دار از پایین سر در بیاورند، پس‌زمینه محو شود و Escape یا لمس بیرون آن را ببندد.') }}
        </p>
    </div>

    <div class="m3fab-root">
        <div class="m3fab-cluster" x-data="{ open: false }"
            x-bind:data-open="open"
            x-on:click.outside="open = false"
            x-on:keydown.escape.window="open = false">
            <div class="m3fab-veil" x-on:click="open = false" aria-hidden="true"></div>
            <div class="m3fab-menu">
                <div class="m3fab-actions">
                    <div class="m3fab-action">
                        <span>{{ $say('From photo', 'از عکس') }}</span>
                        <button type="button" class="m3fab-item-icon" tabindex="-1" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="3"/><circle cx="9" cy="10" r="1.6"/><path d="M21 16l-5-5-9 8"/></svg></button>
                    </div>
                    <div class="m3fab-action">
                        <span>{{ $say('From files', 'از فایل‌ها') }}</span>
                        <button type="button" class="m3fab-item-icon" tabindex="-1" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Z"/></svg></button>
                    </div>
                    <div class="m3fab-action">
                        <span>{{ $say('Record voice', 'ضبط صدا') }}</span>
                        <button type="button" class="m3fab-item-icon" tabindex="-1" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="3" width="6" height="11" rx="3"/><path d="M5 11a7 7 0 0 0 14 0M12 18v3"/></svg></button>
                    </div>
                </div>
                <button type="button" class="m3fab m3fab-md" aria-expanded="false" x-bind:aria-expanded="open.toString()" aria-label="{{ $say('Create', 'ساخت') }}"
                    x-on:click="open = !open">
                    <span class="m3fab-plus"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg></span>
                </button>
            </div>
        </div>
    </div>
</section>
