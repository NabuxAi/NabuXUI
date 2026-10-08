{{--
    The Win11 button family in a Notepad-like toolbar: the standard stroked
    button, the blue accent Save, the borderless subtle, a bold toggle with a
    real pressed state, a split button whose chevron opens an acrylic menu,
    and a hyperlink — all wearing the 4% state layer, pressed text dimming
    and the double-ring focus.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    .flbtn-root {
        --fl-accent: #005FB8;
        --fl-accent-hover: #1A75C5;
        --fl-accent-press: #005399;
        --fl-on-accent: #FFFFFF;
        --fl-text: #1B1B1B;
        --fl-text-2: #5D5D5D;
        --fl-window: #F3F3F3;
        --fl-layer: rgba(255, 255, 255, .70);
        --fl-control: rgba(255, 255, 255, .70);
        --fl-card-stroke: rgba(0, 0, 0, .10);
        --fl-control-stroke: rgba(0, 0, 0, .12);
        --fl-control-edge: rgba(0, 0, 0, .22);
        --fl-divider: rgba(0, 0, 0, .08);
        --fl-hover: rgba(0, 0, 0, .04);
        --fl-press: rgba(0, 0, 0, .06);
        --fl-focus: #1B1B1B;
        --fl-flyout-shadow: 0 8px 24px rgba(0, 0, 0, .20), 0 0 0 1px rgba(0, 0, 0, .07);
        font-family: "Segoe UI Variable Text", "Segoe UI", system-ui, sans-serif;
        color: var(--fl-text);
        display: grid;
        gap: .75rem;
    }
    html[data-theme="dark"] .flbtn-root {
        --fl-accent: #4CC2FF;
        --fl-accent-hover: #6BCFFF;
        --fl-accent-press: #2FA8DC;
        --fl-on-accent: #000000;
        --fl-text: #FFFFFF;
        --fl-text-2: #CFCFCF;
        --fl-window: #202020;
        --fl-layer: rgba(255, 255, 255, .05);
        --fl-control: rgba(255, 255, 255, .06);
        --fl-card-stroke: rgba(255, 255, 255, .08);
        --fl-control-stroke: rgba(255, 255, 255, .10);
        --fl-control-edge: rgba(255, 255, 255, .18);
        --fl-divider: rgba(255, 255, 255, .06);
        --fl-hover: rgba(255, 255, 255, .06);
        --fl-press: rgba(255, 255, 255, .03);
        --fl-focus: #FFFFFF;
        --fl-flyout-shadow: 0 8px 24px rgba(0, 0, 0, .55), 0 0 0 1px rgba(255, 255, 255, .08);
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .flbtn-root {
            --fl-accent: #4CC2FF;
            --fl-accent-hover: #6BCFFF;
            --fl-accent-press: #2FA8DC;
            --fl-on-accent: #000000;
            --fl-text: #FFFFFF;
            --fl-text-2: #CFCFCF;
            --fl-window: #202020;
            --fl-layer: rgba(255, 255, 255, .05);
            --fl-control: rgba(255, 255, 255, .06);
            --fl-card-stroke: rgba(255, 255, 255, .08);
            --fl-control-stroke: rgba(255, 255, 255, .10);
            --fl-control-edge: rgba(255, 255, 255, .18);
            --fl-divider: rgba(255, 255, 255, .06);
            --fl-hover: rgba(255, 255, 255, .06);
            --fl-press: rgba(255, 255, 255, .03);
            --fl-focus: #FFFFFF;
            --fl-flyout-shadow: 0 8px 24px rgba(0, 0, 0, .55), 0 0 0 1px rgba(255, 255, 255, .08);
        }
    }
    .flbtn-root button { font: inherit; color: inherit; background: none; border: 0; padding: 0; cursor: pointer; }
    .flbtn-root :focus-visible { outline: 2px solid var(--fl-focus); outline-offset: 1px; border-radius: 4px; }
    .flbtn-root svg { display: block; }

    .flbtn-app {
        inline-size: min(100%, 30rem); margin-inline: auto; border-radius: 8px; overflow: clip;
        background: var(--fl-window);
        box-shadow: 0 24px 48px rgba(0, 0, 0, .16), 0 0 0 1px var(--fl-card-stroke);
    }
    .flbtn-bar {
        position: relative; display: flex; flex-wrap: wrap; align-items: center; gap: .5rem;
        padding: .75rem; border-block-end: 1px solid var(--fl-divider);
    }

    .flbtn {
        position: relative; display: inline-flex; align-items: center; justify-content: center; gap: .5rem;
        min-inline-size: 5rem; block-size: 2rem; padding-inline: .8rem; border-radius: 4px;
        font-size: .8125rem; background: var(--fl-control); color: var(--fl-text);
        box-shadow: inset 0 0 0 1px var(--fl-control-stroke), inset 0 -1px 0 var(--fl-control-edge);
        transition: background .12s, color .12s, box-shadow .12s;
    }
    .flbtn::after { content: ""; position: absolute; inset: 0; border-radius: inherit; background: transparent; transition: background .1s; }
    .flbtn:hover::after { background: var(--fl-hover); }
    .flbtn:active::after { background: var(--fl-press); }
    .flbtn:active { color: var(--fl-text-2); }

    .flbtn[data-accent] { background: var(--fl-accent); color: var(--fl-on-accent); box-shadow: inset 0 0 0 1px var(--fl-accent), inset 0 -1px 0 rgba(0, 0, 0, .2); }
    .flbtn[data-accent]::after { display: none; }
    .flbtn[data-accent]:hover { background: var(--fl-accent-hover); }
    .flbtn[data-accent]:active { background: var(--fl-accent-press); color: color-mix(in srgb, var(--fl-on-accent) 75%, transparent); }

    .flbtn[data-subtle] { background: transparent; box-shadow: inset 0 0 0 1px transparent; }

    .flbtn[aria-pressed="true"] { background: var(--fl-accent); color: var(--fl-on-accent); box-shadow: inset 0 0 0 1px var(--fl-accent); }
    .flbtn[aria-pressed="true"]::after { display: none; }
    .flbtn[aria-pressed="true"]:hover { background: var(--fl-accent-hover); }
    .flbtn[aria-pressed="true"]:active { background: var(--fl-accent-press); color: color-mix(in srgb, var(--fl-on-accent) 75%, transparent); }

    .flbtn-split { position: relative; display: inline-flex; }
    .flbtn-split > .flbtn { min-inline-size: 0; }
    .flbtn-split > .flbtn:first-child { min-inline-size: 7rem; justify-content: flex-start; border-start-end-radius: 0; border-end-end-radius: 0; }
    .flbtn-split > .flbtn + .flbtn { block-size: 2rem; padding-inline: .45rem; border-start-start-radius: 0; border-end-start-radius: 0; box-shadow: inset 0 0 0 1px var(--fl-control-stroke), inset 1px 0 0 var(--fl-control-stroke), inset 0 -1px 0 var(--fl-control-edge); }
    .flbtn-split > .flbtn + .flbtn:hover { background: color-mix(in srgb, var(--fl-hover) 90%, transparent); }

    .flbtn-menu {
        position: absolute; inset-block-start: calc(100% + 4px); inset-inline-end: 0; z-index: 20;
        min-inline-size: 11rem; padding: .3rem; border-radius: 8px;
        background: var(--fl-layer);
        backdrop-filter: blur(30px) saturate(125%); -webkit-backdrop-filter: blur(30px) saturate(125%);
        box-shadow: var(--fl-flyout-shadow); display: grid;
    }
    .flbtn-menu button { display: flex; align-items: center; gap: .6rem; inline-size: 100%; padding: .45rem .6rem; border-radius: 4px; font-size: .8125rem; text-align: start; }
    .flbtn-menu button:hover { background: var(--fl-hover); }
    .flbtn-menu button:active { background: var(--fl-press); color: var(--fl-text-2); }

    .flbtn-link { position: relative; display: inline-flex; align-items: center; min-inline-size: 0; block-size: auto; padding: .2rem .3rem; border-radius: 4px; background: transparent; box-shadow: none; color: var(--fl-accent); font-size: .8125rem; }
    .flbtn-link::after { display: none; }
    .flbtn-link:hover { text-decoration: underline; }
    .flbtn-link:active { color: var(--fl-text-2); }

    .flbtn-doc { padding: 1rem 1.25rem 0; display: grid; gap: .35rem; }
    .flbtn-doc p { margin: 0; font-size: .8rem; line-height: 2; }
    .flbtn-status { margin: 0; padding: .6rem 1.25rem 1rem; font-size: .7rem; color: var(--fl-text-2); }

    @media (prefers-reduced-motion: reduce) {
        .flbtn, .flbtn::after, .flbtn-menu button { transition: none; }
    }

    /* The page's "Important props" table is rendered by the demo shell and
       reveals its rows on scroll. Capture environments that screenshot without
       scrolling never fire that reveal, leaving a header over an invisible
       body. Rows on this page always render; the staggered entrance is the
       only thing given up. (Specificity (0,3,4) beats the framework's
       :where(.nx-js) .nx-data-table... tbody tr rule at (0,3,2).) */
    html body .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr {
        opacity: 1;
        translate: none;
    }

    /* The same table on phones: its cells are nowrap by default
       (.nx-data-table th, td), so the four columns — above all the long
       "What it does" notes — cannot fit a 375px viewport and the last column
       was clipped mid-word at the page edge ("standard · a…", "The Win11 (…",
       "Light acces…"). Let cells wrap at spaces and let the note column break
       inside words too (tokens like #005FB8); on wide screens every line still
       fits, so nothing changes there. Under 30rem ease the cell padding so
       all four columns truly fit. Scoped to this demo page via the
       .flbtn-root ancestor. */
    html:has(.flbtn-root) .nx-data-table th,
    html:has(.flbtn-root) .nx-data-table td { white-space: normal; }
    html:has(.flbtn-root) .nx-data-table th:last-child,
    html:has(.flbtn-root) .nx-data-table td:last-child { overflow-wrap: anywhere; }
    @media (max-width: 30rem) {
        html:has(.flbtn-root) .nx-data-table th,
        html:has(.flbtn-root) .nx-data-table td { padding: .625rem .75rem; }
    }
</style>

<section class="pg-box" style="gap: 1rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('One family, six temperaments', 'یک خانواده، شش خُلق') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Standard, accent, subtle, toggle, split and hyperlink — each wearing a 4% state layer on hover, dimming its text when pressed, and doubling its ring under the keyboard. The toggle really bolds the first line.', 'استاندارد، accent، بی‌پس‌زمینه، دوحالته، split و hyperlink — همه با لایهٔ حالت ۴٪ در hover، کم‌رنگ شدن متن هنگام فشار و حلقهٔ دوخطه با کیبورد. دکمهٔ دوحالته واقعاً خط اول را درشت می‌کند.') }}
        </p>
    </div>

    <div class="flbtn-root" x-data="{
            on: false, menu: false,
            msg: {{ json_encode($say('Ready — nothing saved yet', 'آماده — هنوز چیزی ذخیره نشده')) }},
            tOn: {{ json_encode($say('Bold — on', 'درشت — روشن')) }},
            tOff: {{ json_encode($say('Bold — off', 'درشت — خاموش')) }},
            tPrint: {{ json_encode($say('Sent to the printer queue', 'به صف چاپ رفت')) }},
            tSave: {{ json_encode($say('Saved at 14:32', 'در ۱۴:۳۲ ذخیره شد')) }},
            tDelete: {{ json_encode($say('Paragraph deleted — bring it back from Edit', 'بند حذف شد — از «ویرایش» برگردانید')) }},
            tShare: {{ json_encode($say('Share link copied', 'پیوند اشتراک‌گذاری کپی شد')) }},
            tPdf: {{ json_encode($say('Exporting as PDF…', 'در حال خروجی PDF…')) }},
            tLink: {{ json_encode($say('Link copied to clipboard', 'پیوند به تخته‌گیر کپی شد')) }},
            tHelp: {{ json_encode($say('Opening the save guide…', 'در حال باز کردن راهنمای ذخیره…')) }},
            flash(m) { this.msg = m },
        }">
        <div class="flbtn-app">
            <div class="flbtn-bar" role="toolbar" aria-label="{{ $say('Document actions', 'کنش‌های سند') }}" x-on:keydown.escape="menu = false">
                <button type="button" class="flbtn" x-on:click="flash(tPrint)">{{ $say('Print', 'چاپ') }}</button>
                <button type="button" class="flbtn" data-accent x-on:click="flash(tSave)">{{ $say('Save', 'ذخیره') }}</button>
                <button type="button" class="flbtn" data-subtle x-on:click="flash(tDelete)">{{ $say('Delete', 'حذف') }}</button>
                <button type="button" class="flbtn" :aria-pressed="on ? 'true' : 'false'" x-on:click="on = !on; flash(on ? tOn : tOff)">{{ $say('Bold', 'درشت') }}</button>
                <div class="flbtn-split" x-ref="split">
                    <button type="button" class="flbtn" x-on:click="flash(tShare)">{{ $say('Share', 'اشتراک‌گذاری') }}</button>
                    <button type="button" class="flbtn" aria-haspopup="menu" :aria-expanded="menu ? 'true' : 'false'" aria-label="{{ $say('More share options', 'گزینه‌های بیشتر اشتراک') }}" x-on:click="menu = !menu">
                        <svg width="8" height="8" viewBox="0 0 8 8" aria-hidden="true"><path d="M1 2.5l3 3 3-3" stroke="currentColor" stroke-width="1.2" fill="none"/></svg>
                    </button>
                    <div class="flbtn-menu" role="menu" aria-label="{{ $say('Share options', 'گزینه‌های اشتراک') }}" x-show="menu" x-cloak x-transition.opacity.duration.150ms
                        x-on:click.outside="if (! $refs.split.contains($event.target)) menu = false">
                        <button type="button" role="menuitem" x-on:click="menu = false; flash(tPdf)">
                            <span aria-hidden="true">📄</span> {{ $say('Export as PDF', 'خروجی PDF') }}
                        </button>
                        <button type="button" role="menuitem" x-on:click="menu = false; flash(tLink)">
                            <span aria-hidden="true">🔗</span> {{ $say('Copy as link', 'کپی به‌عنوان پیوند') }}
                        </button>
                    </div>
                </div>
                <button type="button" class="flbtn-link" x-on:click="flash(tHelp)">{{ $say('Save guide', 'راهنمای ذخیره') }}</button>
            </div>
            <div class="flbtn-doc">
                <p :style="on ? 'font-weight: 700' : null">{{ $say('Sales report — summer quarter', 'گزارش فروش — فصل تابستان') }}</p>
                <p>{{ $say('Regular body text for comparing the type weight once the toggle is on.', 'متن معمولی سند تا وقتی کلید درشت روشن شود، وزن قلم را با هم مقایسه کنید.') }}</p>
            </div>
            <p class="flbtn-status" aria-live="polite" x-text="msg">{{ $say('Ready — nothing saved yet', 'آماده — هنوز چیزی ذخیره نشده') }}</p>
        </div>
    </div>
</section>
