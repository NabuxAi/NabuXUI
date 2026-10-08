{{--
    A macOS popover with its 9px tail aimed at the anchor button: frosted
    glass at 260px, a big soft shadow, a mini colour-picker row and a toggle
    inside. The placement (below or above the anchor) is re-calculated from
    the remaining stage space on every open; outside click and Escape dismiss
    it, and aria-expanded stays in sync.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $swatches = [
        ['fa' => 'نارنجی', 'en' => 'Orange', 'hex' => '#FF9F0A'],
        ['fa' => 'آبی', 'en' => 'Blue', 'hex' => '#0A84FF'],
        ['fa' => 'بنفش', 'en' => 'Purple', 'hex' => '#BF5AF2'],
        ['fa' => 'صورتی', 'en' => 'Pink', 'hex' => '#FF375F'],
        ['fa' => 'سبز', 'en' => 'Green', 'hex' => '#32D74B'],
        ['fa' => 'گرافیتی', 'en' => 'Graphite', 'hex' => '#8E8E93'],
    ];
@endphp
<style>
    .mcpop-root {
        --mcpop-win: #ECECEC; --mcpop-text: #1E1E1E; --mcpop-text2: #6D6D72; --mcpop-accent: #007AFF;
        --mcpop-hair: rgba(0, 0, 0, .15); --mcpop-div: rgba(0, 0, 0, .1);
        --mcpop-ctrl: #FFFFFF;
        --mcpop-ctrl-sh: 0 .5px 1.5px rgba(0, 0, 0, .2), 0 0 0 .5px rgba(0, 0, 0, .15);
        --mcpop-glass: rgba(245, 245, 245, .92);
        --mcpop-wall: linear-gradient(140deg, #35507E 0%, #7C5E93 52%, #D99976 100%);
        font-family: system-ui, -apple-system, "Vazirmatn", sans-serif;
    }
    html[data-theme="dark"] .mcpop-root {
        --mcpop-win: #282828; --mcpop-text: #F5F5F5; --mcpop-text2: #A5A5AA; --mcpop-accent: #0A84FF;
        --mcpop-hair: rgba(255, 255, 255, .15); --mcpop-div: rgba(255, 255, 255, .1);
        --mcpop-ctrl: #333336;
        --mcpop-ctrl-sh: 0 .5px 1.5px rgba(0, 0, 0, .55), 0 0 0 .5px rgba(255, 255, 255, .14);
        --mcpop-glass: rgba(40, 40, 40, .85);
        --mcpop-wall: linear-gradient(140deg, #1F3050 0%, #463455 52%, #6E4630 100%);
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .mcpop-root {
            --mcpop-win: #282828; --mcpop-text: #F5F5F5; --mcpop-text2: #A5A5AA; --mcpop-accent: #0A84FF;
            --mcpop-hair: rgba(255, 255, 255, .15); --mcpop-div: rgba(255, 255, 255, .1);
            --mcpop-ctrl: #333336;
            --mcpop-ctrl-sh: 0 .5px 1.5px rgba(0, 0, 0, .55), 0 0 0 .5px rgba(255, 255, 255, .14);
            --mcpop-glass: rgba(40, 40, 40, .85);
            --mcpop-wall: linear-gradient(140deg, #1F3050 0%, #463455 52%, #6E4630 100%);
        }
    }

    .mcpop-stage {
        position: relative; overflow: clip; min-block-size: 26rem; border-radius: var(--nx-radius-2xl);
        background: var(--mcpop-wall); color: var(--mcpop-text);
        display: grid; grid-template-rows: auto 1fr;
    }
    .mcpop-bar {
        position: relative; z-index: 5; display: flex; align-items: center; gap: 10px;
        padding: 10px 12px; background: color-mix(in srgb, var(--mcpop-win) 68%, transparent);
        backdrop-filter: blur(20px); border-block-end: 1px solid rgba(255, 255, 255, .12);
    }
    .mcpop-bar b { font: 600 13px/1 system-ui, -apple-system, "Vazirmatn", sans-serif; color: #fff; text-shadow: 0 1px 6px rgba(0, 0, 0, .3); }
    .mcpop-btn {
        block-size: 26px; padding-inline: 11px; border: 0; border-radius: 6px; cursor: pointer;
        font: 400 12px/1 system-ui, -apple-system, "Vazirmatn", sans-serif;
        color: var(--mcpop-text); background: var(--mcpop-ctrl); box-shadow: var(--mcpop-ctrl-sh);
    }
    .mcpop-btn:hover { background: color-mix(in srgb, var(--mcpop-ctrl) 88%, var(--mcpop-text) 12%); }
    .mcpop-btn[data-open] { color: #fff; background: var(--mcpop-accent); }
    .mcpop-body {
        position: relative; display: grid; place-items: center; padding: 16px;
    }
    .mcpop-float {
        position: absolute; inset-block-start: 55%; inset-inline-start: 50%; translate: -50% 0;
        display: inline-flex; align-items: center; gap: 7px; padding: 8px 12px; border-radius: 8px;
        background: color-mix(in srgb, var(--mcpop-win) 55%, transparent);
        box-shadow: 0 0 0 .5px rgba(255, 255, 255, .18);
        font: 400 12px/1.5 system-ui, -apple-system, "Vazirmatn", sans-serif; color: #fff;
    }

    .mcpop {
        position: absolute; z-index: 30; inline-size: min(260px, calc(100% - 20px));
        padding: 14px; border-radius: 10px;
        background: var(--mcpop-glass); backdrop-filter: blur(40px) saturate(1.6);
        box-shadow: 0 12px 48px rgba(0, 0, 0, .3), 0 0 0 .5px var(--mcpop-hair);
        display: grid; gap: 12px;
    }
    /* physical `left` on purpose: the popover is placed from JS-measured rects */
    .mcpop-tail {
        position: absolute; inline-size: 13px; block-size: 13px; rotate: 45deg;
        background: inherit; border-radius: 2px;
        inset-block-start: -6.5px; inset-inline-start: var(--mcpop-tail-x, 24px);
    }
    .mcpop[data-side='top'] .mcpop-tail { inset-block-start: auto; inset-block-end: -6.5px; }
    .mcpop h5 { margin: 0; font: 600 13px/1.4 system-ui, -apple-system, "Vazirmatn", sans-serif; }
    .mcpop-field { display: grid; gap: 7px; }
    .mcpop-field > span { font: 400 12px/1.4 system-ui, -apple-system, "Vazirmatn", sans-serif; color: var(--mcpop-text2); }
    .mcpop-swatches { display: flex; gap: 8px; flex-wrap: wrap; }
    .mcpop-swatch {
        inline-size: 24px; aspect-ratio: 1; padding: 0; border: 0; border-radius: 50%; cursor: pointer;
        box-shadow: inset 0 0 0 .5px rgba(0, 0, 0, .2);
        transition: scale .12s;
    }
    .mcpop-swatch:hover { scale: 1.12; }
    .mcpop-swatch[data-selected] { box-shadow: 0 0 0 2px var(--mcpop-glass), 0 0 0 3.5px var(--mcpop-accent); }
    .mcpop-switchrow { display: flex; align-items: center; gap: 10px; }
    .mcpop-switch {
        position: relative; inline-size: 34px; block-size: 20px; flex: none; padding: 0; border: 0;
        border-radius: 999px; cursor: pointer; background: color-mix(in srgb, var(--mcpop-text2) 40%, transparent);
        transition: background .18s;
    }
    .mcpop-switch::after {
        content: ''; position: absolute; inset-block-start: 2px; inset-inline-start: 2px;
        inline-size: 16px; aspect-ratio: 1; border-radius: 50%; background: #fff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .3); transition: translate .18s;
    }
    .mcpop-switch[aria-checked='true'] { background: var(--mcpop-accent); }
    .mcpop-switch[aria-checked='true']::after { translate: 14px 0; }
    .mcpop-switchrow span { font: 400 13px/1.4 system-ui, -apple-system, "Vazirmatn", sans-serif; }
    .mcpop-switchrow small { display: block; color: var(--mcpop-text2); font-size: 11px; }
    .mcpop-preview {
        display: flex; align-items: center; gap: 9px; padding: 9px 10px; border-radius: 8px;
        background: color-mix(in srgb, var(--mcpop-win) 45%, transparent);
        box-shadow: inset 0 0 0 .5px var(--mcpop-div);
        font: 500 12px/1.4 system-ui, -apple-system, "Vazirmatn", sans-serif;
    }
    .mcpop-chip { inline-size: 16px; aspect-ratio: 1; border-radius: 5px; flex: none; box-shadow: inset 0 0 0 .5px rgba(0, 0, 0, .25); }
    .mcpop-root :is(button, input):focus-visible { outline: 2px solid var(--mcpop-accent); outline-offset: 2px; }
    @media (prefers-reduced-motion: reduce) { .mcpop-swatch, .mcpop-switch, .mcpop-switch::after { transition: none; } }

    /* The shared "Important props" table below this stage reveals its rows on
       scroll (opacity: 0 until an IntersectionObserver stamps
       [data-nx-revealed]; the CSS failsafe is off once .nx-live is set). A
       full-page capture never scrolls, so the rows stayed invisible and the
       table looked header-only. Pin this page's table rows visible — scoped
       through :has(.mcpop-root), so it never reaches another demo page. */
    :where(.nx-js) .pg:has(.mcpop-root) .nx-data-table[data-nx-reveal] tbody tr {
        opacity: 1; translate: none;
    }
    /* At phone widths the table's nowrap cells and the snippet's long lines
       run past the inline edge; let this page's table and snippet wrap so
       nothing is read as cut off. */
    @media (max-width: 480px) {
        .pg:has(.mcpop-root) .nx-data-table :is(th, td) {
            white-space: normal; padding-inline: .5rem; overflow-wrap: break-word;
        }
        .pg:has(.mcpop-root) pre code { white-space: pre-wrap; overflow-wrap: anywhere; }
    }
</style>

<section class="pg-box mcpop-root">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Popover', 'پاپ‌اور') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The toolbar button and the floating chip each open the same frosted popover; its 9px tail re-aims and the panel flips above or below whichever anchor has room. Outside click and Escape dismiss it.', 'دکمهٔ toolbar و نشان شناور هر دو همان پاپ‌اور شیشه‌ای را باز می‌کنند؛ دمِ ۹ پیکسلی‌اش برمی‌گردد و پنل بر اساس جا، بالا یا پایینِ لنگر می‌ایستد. کلیک بیرون و Escape آن را می‌بندد.') }}
        </p>
    </div>

    <div class="mcpop-stage" x-ref="stage"
         x-data="{
                open: null, side: 'bottom', top: 0, left: 0, tailX: 24,
                color: '#0A84FF', guides: true,
                place(which) {
                    const stage = this.$refs.stage.getBoundingClientRect();
                    const btn = this.$refs['anchor' + which].getBoundingClientRect();
                    const h = 190;
                    this.side = (stage.bottom - btn.bottom) > h ? 'bottom' : 'top';
                    this.top = this.side === 'bottom' ? btn.bottom - stage.top + 9 : btn.top - stage.top - 9;
                    const center = btn.left + btn.width / 2 - stage.left;
                    this.left = Math.min(Math.max(center - 130, 8), stage.width - 268 - 8);
                    this.tailX = Math.min(Math.max(center - this.left - 7, 14), 226);
                    this.open = which;
                },
                toggle(which) { this.open === which ? this.open = null : this.place(which) },
            }"
         x-on:click.outside="open = null"
         x-on:keydown.escape.window="open = null">
        <div class="mcpop-bar">
            <b>{{ $say('Canvas — Studio', 'بوم — استودیو') }}</b>
            <span style="flex: 1"></span>
            <button type="button" class="mcpop-btn" x-ref="anchorA" x-on:click="toggle('A')"
                    :data-open="open === 'A' ? '' : null"
                    :aria-expanded="open === 'A' ? 'true' : 'false'"
                    aria-controls="mcpop-panel">{{ $say('Options', 'گزینه‌ها') }}</button>
        </div>

        <div class="mcpop-body">
            <button type="button" class="mcpop-float" x-ref="anchorB" x-on:click="toggle('B')"
                    :data-open="open === 'B' ? '' : null"
                    :aria-expanded="open === 'B' ? 'true' : 'false'"
                    aria-controls="mcpop-panel">
                <span class="mcpop-chip" :style="'background:' + color"></span>
                {{ $say('Label the frame', 'برچسب‌گذاری قاب') }}
            </button>

            <div class="mcpop" id="mcpop-panel" role="dialog" aria-label="{{ $say('Frame options', 'گزینه‌های قاب') }}"
                 x-show="open !== null" x-cloak x-transition.opacity.duration.130ms
                 :data-side="side"
                 :style="'inset-block-start:' + top + 'px; left:' + left + 'px; --mcpop-tail-x:' + tailX + 'px'">
                <span class="mcpop-tail" aria-hidden="true"></span>
                <h5>{{ $say('Frame options', 'گزینه‌های قاب') }}</h5>
                <div class="mcpop-field">
                    <span>{{ $say('Label colour', 'رنگ برچسب') }}</span>
                    <div class="mcpop-swatches" role="radiogroup" aria-label="{{ $say('Label colour', 'رنگ برچسب') }}">
                        @foreach ($swatches as $sw)
                            <button type="button" class="mcpop-swatch" role="radio"
                                    :aria-checked="color === '{{ $sw['hex'] }}' ? 'true' : 'false'"
                                    :data-selected="color === '{{ $sw['hex'] }}' ? '' : null"
                                    :aria-label="{{ $say($sw['en'], $sw['fa']) }}"
                                    :style="'background: {{ $sw['hex'] }}'"
                                    x-on:click="color = '{{ $sw['hex'] }}'"></button>
                        @endforeach
                    </div>
                </div>
                <div class="mcpop-switchrow">
                    <button type="button" class="mcpop-switch" role="switch"
                            :aria-checked="guides ? 'true' : 'false'"
                            :aria-label="$say('Show guides', 'نمایش خطوط راهنما')"
                            x-on:click="guides = ! guides"></button>
                    <span>
                        {{ $say('Show guides', 'نمایش خطوط راهنما') }}
                        <small>{{ $say('Baseline grid at 8px', 'شبکهٔ خط مبنا با گام ۸ پیکسل') }}</small>
                    </span>
                </div>
                <div class="mcpop-preview">
                    <span class="mcpop-chip" :style="'background:' + color" aria-hidden="true"></span>
                    {{ $say('Frame «Hero» · ۱۲ objects', 'قاب «قهرمان» · ۱۲ شیء') }}
                </div>
            </div>
        </div>
    </div>
</section>
