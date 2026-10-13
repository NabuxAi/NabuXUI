{{--
    self-draw's real scenario: a workspace illustration — window, plant,
    laptop and mug — that inks itself layer by layer. Scrolling inside the
    stage draws the paths in order; the "draw" button runs the sequence on
    its own, erasing resets, and once every path is in, the colours settle
    over the lines. A counter keeps score of the drawn paths.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    // Drawn in this exact order: desk, window, view, laptop, mug, plant.
    $paths = [
        'M14 170h292',                                                              // desk line
        'M28 34h82v108H28z',                                                        // window frame
        'M69 34v108',                                                               // mullion — vertical
        'M28 88h82',                                                                // mullion — horizontal
        'M32 134q16-22 32 0',                                                       // hill 1
        'M62 134q12-16 24 0',                                                       // hill 2
        'M150 82h96v62h-96z',                                                       // laptop screen
        'M138 144h136l10 16H128z',                                                  // laptop base
        'M156 156h92',                                                              // keyboard line
        'M266 118h26v34a8 8 0 0 1-8 8h-10a8 8 0 0 1-8-8z',                          // mug body
        'M292 126a9 9 0 0 1 0 18',                                                  // mug handle
        'M272 106q-4-6 0-12',                                                       // steam 1
        'M284 106q-4-6 0-12',                                                       // steam 2
        'M98 150h28l-4 20h-20z',                                                    // plant pot
        'M112 150c-2-14-12-24-24-26',                                               // leaf left
        'M112 150c2-16 12-26 24-28',                                                // leaf right
        'M112 150v-28',                                                             // middle stem
    ];
@endphp

<style>
    .imw-wrap {
        --imw-fg: #1c1917; --imw-muted: #78716c; --imw-border: #e7e5e4;
        --imw-bg: #ffffff; --imw-accent: #4f46e5;
        display: grid;
        gap: 1rem;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        color: var(--imw-fg);
    }
    html[data-theme="dark"] .imw-wrap {
        --imw-fg: #fafaf9; --imw-muted: #a8a29e; --imw-border: #292524;
        --imw-bg: #16130f; --imw-accent: #a5b4fc;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .imw-wrap {
            --imw-fg: #fafaf9; --imw-muted: #a8a29e; --imw-border: #292524;
            --imw-bg: #16130f; --imw-accent: #a5b4fc;
        }
    }
    .imw-stage {
        block-size: 15.5rem;
        overflow-y: auto;
        border: 1px solid var(--imw-border);
        border-radius: 1.1rem;
        background: var(--imw-bg);
        scrollbar-width: thin;
    }
    .imw-scene {
        position: sticky;
        inset-block-start: 0;
        display: block;
        inline-size: 100%;
        block-size: 15.5rem;
        background: var(--imw-bg);
    }
    .imw-spacer { block-size: 34rem; }
    .imw-colors { opacity: 0; transition: opacity .9s ease; }
    .imw-colors[data-on="true"] { opacity: 1; }
    .imw-scene .imw-ink {
        fill: none;
        stroke: var(--imw-fg);
        stroke-width: 2.5;
        stroke-linecap: round;
        stroke-linejoin: round;
        stroke-dasharray: 1;
        stroke-dashoffset: 1;
        transition: stroke-dashoffset .3s ease;
    }
    .imw-scene .imw-ink[data-drawn="true"] { stroke-dashoffset: 0; }
    .imw-toolbar { display: flex; flex-wrap: wrap; align-items: center; gap: .6rem; }
    .imw-btn {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        padding: .55rem 1.1rem;
        font: 600 .8rem/1 inherit;
        color: var(--imw-fg);
        background: var(--imw-bg);
        border: 1px solid var(--imw-border);
        border-radius: 999px;
        cursor: pointer;
        transition: border-color .15s ease, color .15s ease;
    }
    .imw-btn:hover { border-color: var(--imw-accent); color: var(--imw-accent); }
    .imw-btn:focus-visible { outline: 2px solid var(--imw-accent); outline-offset: 2px; }
    .imw-btn[disabled] { opacity: .45; cursor: default; }
    .imw-btn svg { inline-size: .9rem; block-size: .9rem; stroke: currentColor; stroke-width: 2;
                   fill: none; stroke-linecap: round; stroke-linejoin: round; }
    .imw-count {
        margin-inline-start: auto;
        padding: .4rem .85rem;
        border-radius: 999px;
        background: color-mix(in oklab, var(--imw-accent) 12%, transparent);
        color: var(--imw-accent);
        font: 700 .74rem/1.4 ui-monospace, monospace;
    }
    .imw-note { margin: 0; font-size: .74rem; line-height: 1.7; color: var(--imw-muted); }
    @media (prefers-reduced-motion: reduce) {
        .imw-scene .imw-ink { transition: none; }
        .imw-colors { transition: none; }
    }
</style>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The scene that draws its own product', 'صحنه‌ای که خودش محصولش را می‌کشد') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Svgator-style sequence drawing: scroll inside the frame and the workspace — window, hills, laptop, mug, steaming and the plant — inks itself path by path in layer order; at the end the colours settle over the lines. The draw button runs the whole sequence, erase resets, and the counter keeps score.', 'ترسیم سکانسی به سبک Svgator: داخل قاب اسکرول کن تا ورک‌اسپیس — پنجره، تپه‌ها، لپ‌تاپ، فنجان با بخارش و گلدان — لایه‌لایه و به‌ترتیب خودش را بکشد؛ آخر سر رنگ‌ها روی خطوط می‌نشینند. دکمهٔ بکش کل سکانس را اجرا می‌کند، پاک‌کردن صفر می‌زند و شمارنده امتیاز می‌دهد.') }}
        </p>
    </div>

    <div class="imw-wrap"
        x-data="{
            fa: @js($fa),
            total: {{ count($paths) }},
            drawn: 0,
            colored: false,
            t: null,
            onScroll() {
                const el = this.$refs.stage;
                const max = el.scrollHeight - el.clientHeight;
                const p = max > 0 ? el.scrollTop / max : 1;
                this.drawn = Math.round(p * this.total);
                this.colored = p >= 0.97;
            },
            erase() {
                clearInterval(this.t);
                this.$refs.stage.scrollTop = 0;
                this.drawn = 0;
                this.colored = false;
            },
            draw() {
                this.erase();
                this.t = setInterval(() => {
                    const el = this.$refs.stage;
                    const max = el.scrollHeight - el.clientHeight;
                    if (el.scrollTop >= max - 1) {
                        clearInterval(this.t);
                        this.onScroll();
                    } else {
                        el.scrollTop = Math.min(max, el.scrollTop + 7);
                        this.onScroll();
                    }
                }, 16);
            },
            destroy() { clearInterval(this.t); },
        }">

        <div class="imw-stage" x-ref="stage" x-on:scroll="onScroll()" tabindex="0"
             role="img"
             :aria-label="fa ? 'تصویرسازی خطی ورک‌اسپیس که با اسکرول ترسیم می‌شود' : 'Line illustration of a workspace, drawn by scrolling'">
            <svg class="imw-scene" viewBox="0 0 320 200" aria-hidden="true">
                {{-- the colour layer sits behind the ink and settles at the end --}}
                <g class="imw-colors" x-bind:data-on="colored ? 'true' : 'false'">
                    <rect x="30" y="36" width="78" height="104" rx="2" fill="#bfdbfe" opacity=".55"/>
                    <path d="M30 134q16-22 32 0Z" fill="#86efac" opacity=".5"/>
                    <path d="M60 134q14-19 26 0Z" fill="#4ade80" opacity=".4"/>
                    <rect x="152" y="84" width="92" height="58" rx="2" fill="#c7d2fe" opacity=".45"/>
                    <path d="M268 120h22v32a6 6 0 0 1-6 6h-10a6 6 0 0 1-6-6z" fill="#fca5a5" opacity=".45"/>
                    <path d="M112 150c-2-14-12-24-24-26Z" fill="#4ade80" opacity=".55"/>
                    <path d="M112 150c2-16 12-26 24-28Z" fill="#86efac" opacity=".5"/>
                </g>
                @foreach ($paths as $i => $d)
                    <path class="imw-ink" pathLength="1" d="{{ $d }}" x-bind:data-drawn="drawn > {{ $i }}" />
                @endforeach
            </svg>
            <div class="imw-spacer" aria-hidden="true"></div>
        </div>

        <div class="imw-toolbar">
            <button type="button" class="imw-btn" x-on:click="draw()">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>
                {{ $say('Draw', 'بکش') }}
            </button>
            <button type="button" class="imw-btn" x-on:click="erase()">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 19h16"/><path d="m6 15 8-8 4 4-8 8H6z"/></svg>
                {{ $say('Erase', 'پاک‌کردن') }}
            </button>
            <span class="imw-count" role="status"
                  x-text="(fa ? String(drawn).replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]) : drawn)
                      + ' / ' + (fa ? String(total).replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]) : total)
                      + (fa ? ' مسیر' : ' paths')"></span>
        </div>

        <p class="imw-note">
            {{ $say('Scroll the frame itself (it is focusable — arrow keys scroll it too). Each stroke is a pathLength-normalised path; the counter rounds the scroll position onto the 17 paths.', 'خودِ قاب را اسکرول کن (قابل‌فوکوس است — فلش‌های کیبورد هم آن را می‌لغزانند). هر خط یک مسیر نرمال‌شده با pathLength است؛ شمارنده موقعیت اسکرول را روی همین ۱۷ مسیر گرد می‌کند.') }}
        </p>
    </div>
</section>
