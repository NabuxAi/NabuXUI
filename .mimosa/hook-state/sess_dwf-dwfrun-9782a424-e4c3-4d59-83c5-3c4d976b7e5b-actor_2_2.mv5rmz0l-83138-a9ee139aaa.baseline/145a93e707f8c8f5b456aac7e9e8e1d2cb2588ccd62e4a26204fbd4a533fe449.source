{{--
    Win11 settings' expander: a 48px header whose chevron turns a springy
    half-circle, a body that unfolds with the grid-rows 0fr→1fr height
    animation, a content-above direction variant, and a border with its
    hairline that only appears while open. The whole header is a real button,
    so Enter and Space work.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    .flexp-root {
        --fl-accent: #005FB8;
        --fl-on-accent: #FFFFFF;
        --fl-text: #1B1B1B;
        --fl-text-2: #5D5D5D;
        --fl-window: #F3F3F3;
        --fl-layer: rgba(255, 255, 255, .70);
        --fl-control: rgba(255, 255, 255, .70);
        --fl-card-stroke: rgba(0, 0, 0, .10);
        --fl-control-stroke: rgba(0, 0, 0, .12);
        --fl-divider: rgba(0, 0, 0, .08);
        --fl-hover: rgba(0, 0, 0, .04);
        --fl-focus: #1B1B1B;
        font-family: "Segoe UI Variable Text", "Segoe UI", system-ui, sans-serif;
        color: var(--fl-text);
        display: grid;
        gap: .75rem;
    }
    html[data-theme="dark"] .flexp-root {
        --fl-accent: #4CC2FF;
        --fl-on-accent: #000000;
        --fl-text: #FFFFFF;
        --fl-text-2: #CFCFCF;
        --fl-window: #202020;
        --fl-layer: rgba(255, 255, 255, .05);
        --fl-control: rgba(255, 255, 255, .06);
        --fl-card-stroke: rgba(255, 255, 255, .08);
        --fl-control-stroke: rgba(255, 255, 255, .10);
        --fl-divider: rgba(255, 255, 255, .06);
        --fl-hover: rgba(255, 255, 255, .06);
        --fl-focus: #FFFFFF;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .flexp-root {
            --fl-accent: #4CC2FF;
            --fl-on-accent: #000000;
            --fl-text: #FFFFFF;
            --fl-text-2: #CFCFCF;
            --fl-window: #202020;
            --fl-layer: rgba(255, 255, 255, .05);
            --fl-control: rgba(255, 255, 255, .06);
            --fl-card-stroke: rgba(255, 255, 255, .08);
            --fl-control-stroke: rgba(255, 255, 255, .10);
            --fl-divider: rgba(255, 255, 255, .06);
            --fl-hover: rgba(255, 255, 255, .06);
            --fl-focus: #FFFFFF;
        }
    }
    .flexp-root button { font: inherit; color: inherit; background: none; border: 0; padding: 0; cursor: pointer; }
    .flexp-root :focus-visible { outline: 2px solid var(--fl-focus); outline-offset: 1px; border-radius: 4px; }

    .flexp-app {
        inline-size: min(100%, 30rem); margin-inline: auto; padding: .5rem;
        border-radius: 8px; background: var(--fl-window);
        box-shadow: 0 24px 48px rgba(0, 0, 0, .14), 0 0 0 1px var(--fl-card-stroke);
        display: grid; gap: .4rem;
    }
    .flexp-staticrow {
        display: flex; align-items: center; gap: .75rem; min-block-size: 48px;
        padding-inline: 1rem; border-radius: 4px; font-size: .8rem;
    }
    .flexp-staticrow span:last-child { margin-inline-start: auto; font-size: .72rem; color: var(--fl-text-2); }

    .flexp {
        border-radius: 4px; border: 1px solid transparent; background: var(--fl-control);
        transition: border-color .2s, background .2s;
    }
    .flexp[data-open="true"] { border-color: var(--fl-control-stroke); }
    .flexp-head {
        position: relative; display: flex; align-items: center; gap: .75rem;
        inline-size: 100%; min-block-size: 48px; padding: .5rem 1rem;
        text-align: start; border-radius: 4px;
    }
    .flexp-head::after { content: ""; position: absolute; inset: 0; border-radius: inherit; background: transparent; transition: background .12s; }
    .flexp-head:hover::after { background: var(--fl-hover); }
    .flexp-head:active::after { background: var(--fl-hover); }
    .flexp-head:active { color: var(--fl-text-2); }
    .flexp-hicon { flex: none; font-size: 1rem; }
    .flexp-htext { display: grid; gap: .1rem; min-inline-size: 0; flex: 1; }
    .flexp-htext strong { font-size: .8125rem; font-weight: 600; }
    .flexp-htext small { font-size: .7rem; color: var(--fl-text-2); }
    .flexp-chev { flex: none; color: var(--fl-text-2); transition: rotate .3s cubic-bezier(.34, 1.3, .5, 1); }
    .flexp[data-open="true"] .flexp-chev { rotate: 180deg; }
    .flexp[data-dir="up"] .flexp-chev { rotate: 180deg; }
    .flexp[data-dir="up"][data-open="true"] .flexp-chev { rotate: 0deg; }

    .flexp-body { display: grid; grid-template-rows: 0fr; transition: grid-template-rows .3s cubic-bezier(.2, .9, .25, 1); }
    .flexp[data-open="true"] .flexp-body { grid-template-rows: 1fr; }
    .flexp-inner { overflow: hidden; min-block-size: 0; }
    .flexp[data-dir="down"][data-open="true"] .flexp-body { border-block-start: 1px solid var(--fl-divider); }
    .flexp[data-dir="up"][data-open="true"] .flexp-body { border-block-end: 1px solid var(--fl-divider); }
    .flexp-content { padding: .75rem 1rem 1rem; display: grid; gap: .55rem; }

    .flexp-opt { display: flex; align-items: center; gap: .6rem; font-size: .78rem; padding-block: .15rem; cursor: pointer; position: relative; }
    .flexp-opt input { position: absolute; opacity: 0; inline-size: 1px; block-size: 1px; margin: 0; }
    .flexp-dot { position: relative; display: inline-grid; place-items: center; flex: none; inline-size: 18px; block-size: 18px; border-radius: 999px; border: 1px solid var(--fl-control-stroke); background: var(--fl-window); transition: border-color .12s; }
    .flexp-dot::after { content: ""; inline-size: 8px; block-size: 8px; border-radius: 999px; background: var(--fl-accent); scale: .3; opacity: 0; transition: scale .15s cubic-bezier(.2, .9, .3, 1.2), opacity .15s; }
    .flexp-opt:hover .flexp-dot { border-color: var(--fl-text-2); }
    .flexp-opt input:checked + .flexp-dot { border-color: var(--fl-accent); }
    .flexp-opt input:checked + .flexp-dot::after { scale: 1; opacity: 1; }
    .flexp-opt input:focus-visible + .flexp-dot { outline: 2px solid var(--fl-focus); outline-offset: 2px; }

    .flexp-spec { display: flex; align-items: baseline; justify-content: space-between; gap: 1rem; font-size: .78rem; padding-block: .3rem; }
    .flexp-spec + .flexp-spec { border-block-start: 1px solid var(--fl-divider); }
    .flexp-spec span { color: var(--fl-text-2); }
    .flexp-note { margin: 0; font-size: .68rem; line-height: 1.8; color: var(--fl-text-2); }
    .flexp-hint { margin: 0; text-align: center; font-size: .72rem; color: var(--fl-text-2); }

    @media (prefers-reduced-motion: reduce) {
        .flexp-body, .flexp-chev, .flexp-dot::after, .flexp, .flexp-head::after { transition: none; }
    }

    /* The shared «Important props» table this page renders below the stage
       reveals its rows on scroll: tbody rows sit at opacity: 0 until an
       IntersectionObserver stamps [data-nx-revealed] on the table, and once
       .nx-live is set the 2.5s CSS failsafe is off — so a full-page capture,
       which never scrolls, sees a header with an empty body. Keep this page's
       rows visible; scoped through :has(.flexp-root) so it never reaches
       another demo page. */
    :where(.nx-js) .pg:has(.flexp-root) .nx-data-table[data-nx-reveal] tbody tr {
        opacity: 1; translate: none;
    }
    /* At phone widths the table's nowrap cells run past the inline edge and
       the last header column is read as cut off; let this page's cells wrap. */
    @media (max-width: 480px) {
        .pg:has(.flexp-root) .nx-data-table :is(th, td) {
            white-space: normal; padding-inline: .5rem; overflow-wrap: break-word;
        }
    }
</style>

<section class="pg-box" style="gap: 1rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Settings that unfold', 'تنظیماتی که باز می‌شوند') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The header turns its chevron on a spring curve, the body unfolds by height — never by clipping tricks — and the border with its hairline only exists while open. The second expander opens upward.', 'سربرگ chevron را روی منحنی فنری می‌چرخاند، بدنه با ارتفاع باز می‌شود — نه با ترفند بریدن — و لبه با خط مویی‌اش فقط هنگام باز بودن وجود دارد. اکسپندر دوم رو به بالا باز می‌شود.') }}
        </p>
    </div>

    <div class="flexp-root" x-data="{ open1: true, open2: false }">
        <div class="flexp-app">
            <div class="flexp-staticrow">
                <span aria-hidden="true">📢</span>
                <span>{{ $say('Speakers', 'بلندگوها') }}</span>
                <span>Realtek® Audio — {{ $say('default', 'پیش‌فرض') }}</span>
            </div>

            <div class="flexp" data-dir="down" :data-open="open1 ? 'true' : 'false'">
                <button type="button" class="flexp-head" id="flexp-h1" aria-controls="flexp-p1"
                    :aria-expanded="open1 ? 'true' : 'false'"
                    x-on:click="open1 = !open1">
                    <span class="flexp-hicon" aria-hidden="true">🔊</span>
                    <span class="flexp-htext">
                        <strong>{{ $say('Audio quality', 'کیفیت صدا') }}</strong>
                        <small>{{ $say('Output format and bit rate', 'فرمت و نرخ بیت خروجی') }}</small>
                    </span>
                    <svg class="flexp-chev" width="12" height="12" viewBox="0 0 12 12" aria-hidden="true"><path d="M2 4l4 4 4-4" stroke="currentColor" stroke-width="1.3" fill="none"/></svg>
                </button>
                <div class="flexp-body" id="flexp-p1" role="region" aria-labelledby="flexp-h1">
                    <div class="flexp-inner">
                        <div class="flexp-content">
                            <label class="flexp-opt">
                                <input type="radio" name="flexp-fmt">
                                <span class="flexp-dot" aria-hidden="true"></span>
                                <span>{{ $say('Standard — AAC, 256 kbps', 'استاندارد — AAC، ۲۵۶ کیلوبیت') }}</span>
                            </label>
                            <label class="flexp-opt">
                                <input type="radio" name="flexp-fmt" checked>
                                <span class="flexp-dot" aria-hidden="true"></span>
                                <span>{{ $say('High — FLAC lossless', 'بالا — FLAC بی‌افت') }}</span>
                            </label>
                            <label class="flexp-opt">
                                <input type="radio" name="flexp-fmt">
                                <span class="flexp-dot" aria-hidden="true"></span>
                                <span>{{ $say('Studio — bit-perfect, exclusive mode', 'استودیو — بی‌افت کامل، حالت انحصاری') }}</span>
                            </label>
                            <p class="flexp-note">{{ $say('Exclusive mode mutes every other app while playing.', 'در حالت انحصاری، هنگام پخش بقیهٔ برنامه‌ها بی‌صدا می‌شوند.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flexp" data-dir="up" :data-open="open2 ? 'true' : 'false'">
                <div class="flexp-body" id="flexp-p2" role="region" aria-labelledby="flexp-h2">
                    <div class="flexp-inner">
                        <div class="flexp-content">
                            <div class="flexp-spec"><span>{{ $say('Device name', 'نام دستگاه') }}</span><strong>Nabu-Book Pro</strong></div>
                            <div class="flexp-spec"><span>{{ $say('Memory', 'حافظه') }}</span><strong>{{ $say('16 GB', '۱۶ گیگابایت') }}</strong></div>
                            <div class="flexp-spec"><span>{{ $say('Storage', 'فضای ذخیره') }}</span><strong>{{ $say('512 GB SSD', '۵۱۲ گیگابایت SSD') }}</strong></div>
                            <div class="flexp-spec"><span>{{ $say('Edition', 'ویرایش') }}</span><strong>{{ $say('Windows 11 Pro, 24H2', 'ویندوز ۱۱ پرو، ۲۴H۲') }}</strong></div>
                        </div>
                    </div>
                </div>
                <button type="button" class="flexp-head" id="flexp-h2" aria-controls="flexp-p2"
                    :aria-expanded="open2 ? 'true' : 'false'"
                    x-on:click="open2 = !open2">
                    <span class="flexp-hicon" aria-hidden="true">💻</span>
                    <span class="flexp-htext">
                        <strong>{{ $say('About this device', 'دربارهٔ دستگاه') }}</strong>
                        <small>{{ $say('Hardware specifications', 'مشخصات سخت‌افزاری') }}</small>
                    </span>
                    <svg class="flexp-chev" width="12" height="12" viewBox="0 0 12 12" aria-hidden="true"><path d="M2 4l4 4 4-4" stroke="currentColor" stroke-width="1.3" fill="none"/></svg>
                </button>
            </div>
        </div>
        <p class="flexp-hint">
            {{ $say('Click, Enter or Space — the whole header is the target. One opens down, the other up.', 'کلیک، Enter یا Space — همهٔ سربرگ هدف است. یکی رو به پایین باز می‌شود، دیگری رو به بالا.') }}
        </p>
    </div>
</section>
