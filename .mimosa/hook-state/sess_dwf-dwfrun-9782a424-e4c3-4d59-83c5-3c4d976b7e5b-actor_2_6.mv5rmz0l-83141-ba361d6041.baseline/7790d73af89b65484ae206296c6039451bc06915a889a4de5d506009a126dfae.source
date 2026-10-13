{{--
    The Mac's classic controls staged as a display-preferences panel: push
    buttons with the hairline bezel and the blue default that answers to
    Enter, the round 14px checkbox and radio, the 28px pop-up button with its
    blue double chevron and a 3-item menu, the linear-well slider with a
    value bubble, and the round two-lobe stepper. Values read in Persian
    digits when the locale is fa.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $themes = [
        ['fa' => 'روشن', 'en' => 'Light'],
        ['fa' => 'خودکار', 'en' => 'Auto'],
        ['fa' => 'تاریک', 'en' => 'Dark'],
    ];
@endphp
<style>
    .mcctl-root {
        --mcctl-win: #ECECEC; --mcctl-text: #1E1E1E; --mcctl-text2: #6D6D72; --mcctl-accent: #007AFF;
        --mcctl-hair: rgba(0, 0, 0, .15); --mcctl-div: rgba(0, 0, 0, .1);
        --mcctl-ctrl: #FFFFFF;
        --mcctl-ctrl-sh: 0 .5px 1.5px rgba(0, 0, 0, .2), 0 0 0 .5px rgba(0, 0, 0, .15);
        --mcctl-wall: linear-gradient(150deg, #DDE4EE 0%, #E9E3DC 60%, #F0DCD2 100%);
        font-family: system-ui, -apple-system, "Vazirmatn", sans-serif;
    }
    html[data-theme="dark"] .mcctl-root {
        --mcctl-win: #282828; --mcctl-text: #F5F5F5; --mcctl-text2: #A5A5AA; --mcctl-accent: #0A84FF;
        --mcctl-hair: rgba(255, 255, 255, .15); --mcctl-div: rgba(255, 255, 255, .1);
        --mcctl-ctrl: #333336;
        --mcctl-ctrl-sh: 0 .5px 1.5px rgba(0, 0, 0, .55), 0 0 0 .5px rgba(255, 255, 255, .14);
        --mcctl-wall: linear-gradient(150deg, #23293A 0%, #2C2830 60%, #38291F 100%);
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .mcctl-root {
            --mcctl-win: #282828; --mcctl-text: #F5F5F5; --mcctl-text2: #A5A5AA; --mcctl-accent: #0A84FF;
            --mcctl-hair: rgba(255, 255, 255, .15); --mcctl-div: rgba(255, 255, 255, .1);
            --mcctl-ctrl: #333336;
            --mcctl-ctrl-sh: 0 .5px 1.5px rgba(0, 0, 0, .55), 0 0 0 .5px rgba(255, 255, 255, .14);
            --mcctl-wall: linear-gradient(150deg, #23293A 0%, #2C2830 60%, #38291F 100%);
        }
    }

    .mcctl-stage {
        position: relative; overflow: clip; display: grid; align-items: center;
        grid-template-columns: minmax(0, 1fr);
        min-block-size: 26rem; padding: 2.25rem 1rem; border-radius: var(--nx-radius-2xl);
        background: var(--mcctl-wall);
    }
    .mcctl-panel {
        position: relative; display: grid; gap: 16px; padding: 20px; margin-inline: auto;
        inline-size: min(100% - 2.5rem, 30rem); min-inline-size: 0; border-radius: 10px;
        color: var(--mcctl-text); background: var(--mcctl-win);
        box-shadow: 0 22px 70px 4px rgba(0, 0, 0, .22), 0 0 0 .5px var(--mcctl-hair);
    }
    .mcctl-panel h4 { margin: 0; font: 700 15px/1.4 system-ui, -apple-system, "Vazirmatn", sans-serif; }
    .mcctl-panel h4 small { display: block; font: 400 12px/1.5 system-ui, -apple-system, "Vazirmatn", sans-serif; color: var(--mcctl-text2); }

    .mcctl-row { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
    .mcctl-row > .mcctl-lab { inline-size: 7.5rem; flex: none; font: 400 13px/1.4 system-ui, -apple-system, "Vazirmatn", sans-serif; }
    .mcctl-row > .mcctl-field { flex: 1 1 auto; display: flex; align-items: center; gap: 10px; flex-wrap: wrap; min-inline-size: 0; }

    .mcctl-push {
        block-size: 28px; padding-inline: 14px; border: 0; border-radius: 6px; cursor: pointer;
        display: inline-flex; align-items: center; gap: 7px;
        font: 400 13px/1 system-ui, -apple-system, "Vazirmatn", sans-serif;
        color: var(--mcctl-text); background: var(--mcctl-ctrl); box-shadow: var(--mcctl-ctrl-sh);
    }
    .mcctl-push:hover { background: color-mix(in srgb, var(--mcctl-ctrl) 88%, var(--mcctl-text) 12%); }
    .mcctl-push[data-default] { color: #fff; background: var(--mcctl-accent); box-shadow: 0 .5px 1.5px rgba(0, 0, 0, .3), 0 0 0 .5px rgba(0, 0, 0, .1); }
    .mcctl-push[data-default]:hover { background: color-mix(in srgb, var(--mcctl-accent) 88%, #fff 12%); }
    .mcctl-kbd {
        display: inline-grid; place-items: center; min-inline-size: 15px; block-size: 15px; padding-inline: 3px;
        border-radius: 4px; font: 600 10px/1 system-ui, sans-serif;
        background: rgba(255, 255, 255, .25); box-shadow: inset 0 0 0 .5px rgba(255, 255, 255, .45);
    }
    .mcctl-status {
        margin: 0; min-block-size: 1.25rem; font: 500 12px/1.6 system-ui, -apple-system, "Vazirmatn", sans-serif; color: var(--mcctl-accent);
    }

    .mcctl-check, .mcctl-radio {
        display: inline-flex; align-items: center; gap: 7px; cursor: pointer;
        font: 400 13px/1.4 system-ui, -apple-system, "Vazirmatn", sans-serif;
    }
    .mcctl-check input, .mcctl-radio input {
        position: absolute; inline-size: 1px; block-size: 1px; opacity: 0; pointer-events: none;
    }
    .mcctl-check i, .mcctl-radio i {
        position: relative; display: grid; place-items: center; inline-size: 14px; aspect-ratio: 1; flex: none;
        border-radius: 50%; background: var(--mcctl-ctrl);
        box-shadow: 0 1px 2px rgba(0, 0, 0, .15), inset 0 0 0 .5px rgba(0, 0, 0, .25);
        transition: background .12s;
    }
    .mcctl-check input:checked + i { background: var(--mcctl-accent); }
    .mcctl-check input:checked + i::after {
        content: ''; inline-size: 7px; block-size: 4px; margin-block-start: -1px;
        border-inline-start: 1.6px solid #fff; border-block-end: 1.6px solid #fff; rotate: -45deg;
    }
    .mcctl-radio input:checked + i { background: var(--mcctl-accent); }
    .mcctl-radio input:checked + i::after { content: ''; inline-size: 5px; aspect-ratio: 1; border-radius: 50%; background: #fff; }
    .mcctl-check input:focus-visible + i, .mcctl-radio input:focus-visible + i { outline: 2px solid var(--mcctl-accent); outline-offset: 2px; }
    .mcctl-pick { display: flex; gap: 14px; flex-wrap: wrap; }

    .mcctl-popwrap { position: relative; }
    .mcctl-pop {
        display: inline-flex; align-items: center; justify-content: space-between; gap: 8px;
        block-size: 28px; min-inline-size: min(11rem, 100%); padding-inline: 9px; border: 0; border-radius: 6px;
        cursor: pointer; text-align: start;
        font: 400 13px/1 system-ui, -apple-system, "Vazirmatn", sans-serif;
        color: var(--mcctl-text); background: var(--mcctl-ctrl); box-shadow: var(--mcctl-ctrl-sh);
    }
    .mcctl-pop svg { inline-size: 9px; block-size: 13px; fill: none; stroke: var(--mcctl-accent); stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
    .mcctl-popmenu {
        position: absolute; inset-block-start: calc(100% + 5px); inset-inline: 0; z-index: 20;
        padding: 4px; border-radius: 6px;
        background: color-mix(in srgb, var(--mcctl-win) 82%, transparent);
        backdrop-filter: blur(30px) saturate(1.5);
        box-shadow: 0 10px 30px rgba(0, 0, 0, .25), 0 0 0 .5px var(--mcctl-hair);
    }
    .mcctl-popitem {
        display: flex; align-items: center; inline-size: 100%; block-size: 26px; padding-inline: 9px;
        border: 0; border-radius: 4px; cursor: pointer; background: transparent; text-align: start;
        font: 400 13px/1 system-ui, -apple-system, "Vazirmatn", sans-serif; color: var(--mcctl-text);
    }
    .mcctl-popitem:hover, .mcctl-popitem:focus-visible { background: var(--mcctl-accent); color: #fff; }
    .mcctl-popitem .mcctl-tick { inline-size: 14px; font-size: 11px; }

    .mcctl-slider { position: relative; flex: 1; display: grid; justify-items: center; min-inline-size: 8rem; }
    .mcctl-bubble {
        position: absolute; inset-block-end: calc(100% - 2px); inline-size: max-content;
        padding: 2px 8px; border-radius: 5px; opacity: 0; translate: 0 4px;
        background: var(--mcctl-accent); color: #fff; font: 600 11px/1.6 system-ui, -apple-system, "Vazirmatn", sans-serif;
        pointer-events: none; transition: opacity .15s, translate .15s; z-index: 5;
    }
    .mcctl-slider:hover .mcctl-bubble, .mcctl-slider:focus-within .mcctl-bubble { opacity: 1; translate: 0 0; }
    .mcctl-slider input {
        -webkit-appearance: none; appearance: none; inline-size: 100%; block-size: 22px; margin: 0;
        background: transparent; cursor: pointer;
    }
    .mcctl-slider input::-webkit-slider-runnable-track {
        block-size: 4px; border-radius: 2px;
        background: color-mix(in srgb, var(--mcctl-text2) 35%, transparent);
        box-shadow: inset 0 0 1px rgba(0, 0, 0, .4);
    }
    .mcctl-slider input::-webkit-slider-thumb {
        -webkit-appearance: none; inline-size: 16px; aspect-ratio: 1; margin-block-start: -6px; border-radius: 50%;
        background: var(--mcctl-ctrl); box-shadow: 0 .5px 3px rgba(0, 0, 0, .35), 0 0 0 .5px rgba(0, 0, 0, .2);
    }
    .mcctl-slider input::-moz-range-track { block-size: 4px; border-radius: 2px; background: color-mix(in srgb, var(--mcctl-text2) 35%, transparent); }
    .mcctl-slider input::-moz-range-thumb { inline-size: 16px; aspect-ratio: 1; border: 0; border-radius: 50%; background: var(--mcctl-ctrl); box-shadow: 0 .5px 3px rgba(0, 0, 0, .35), 0 0 0 .5px rgba(0, 0, 0, .2); }
    .mcctl-val { min-inline-size: 3rem; text-align: end; font: 500 13px/1 system-ui, -apple-system, "Vazirmatn", sans-serif; font-variant-numeric: tabular-nums; }

    .mcctl-stepval {
        display: inline-grid; place-items: center; block-size: 28px; min-inline-size: 3.2rem; padding-inline: 8px;
        border-radius: 6px; background: var(--mcctl-ctrl); box-shadow: var(--mcctl-ctrl-sh);
        font: 400 13px/1 system-ui, -apple-system, "Vazirmatn", sans-serif; font-variant-numeric: tabular-nums;
    }
    .mcctl-stepper { display: grid; grid-template-rows: 14px 14px; inline-size: 19px; border-radius: 6px; overflow: clip; box-shadow: var(--mcctl-ctrl-sh); }
    .mcctl-stepper button {
        display: grid; place-items: center; padding: 0; border: 0; cursor: pointer;
        background: var(--mcctl-ctrl); color: var(--mcctl-text2);
    }
    .mcctl-stepper button:hover { background: color-mix(in srgb, var(--mcctl-ctrl) 85%, var(--mcctl-text) 15%); }
    .mcctl-stepper button + button { border-block-start: .5px solid var(--mcctl-hair); }
    .mcctl-stepper button:first-child { border-start-start-radius: 6px; border-start-end-radius: 6px; }
    .mcctl-stepper button:last-child { border-end-start-radius: 6px; border-end-end-radius: 6px; }
    .mcctl-stepper svg { inline-size: 8px; block-size: 5px; fill: none; stroke: currentColor; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
    .mcctl-root :is(button, input):focus-visible { outline: 2px solid var(--mcctl-accent); outline-offset: 2px; }
    @media (prefers-reduced-motion: reduce) { .mcctl-bubble, .mcctl-check i, .mcctl-radio i { transition: none; } }

    /* The shared snippet below this stage sets the mono stack, which has no
       Persian glyphs; the sample Persian words fell through to a mismatched
       system fallback — cramped and colliding with the adjacent code
       punctuation (<button>…, <i></i>…, <b>…) in either theme. Let Persian
       resolve into Vazirmatn, already loaded by the layout, while Latin code
       keeps the mono face — scoped through :has(.mcctl-root), so it never
       reaches another demo page. */
    .pg:has(.mcctl-root) pre code { font-family: var(--nx-font-mono), "Vazirmatn", sans-serif; }

    /* At phone widths the shared "Important props" table's nowrap cells and
       the snippet's long lines run past the inline edge and are read as cut
       off; let this page's table and snippet wrap so every value stays
       visible — same remedy the popover page ships for its own table. */
    @media (max-width: 480px) {
        .pg:has(.mcctl-root) .nx-data-table :is(th, td) {
            white-space: normal; padding-inline: .5rem; overflow-wrap: break-word;
        }
        .pg:has(.mcctl-root) pre code { white-space: pre-wrap; overflow-wrap: anywhere; }
    }
</style>

<section class="pg-box mcctl-root">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Classic controls', 'کنترل‌های کلاسیک') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Every control works: the blue default answers to Enter anywhere in the panel, the pop-up opens a real menu, and the slider shows its value bubble on hover — all numbers in Persian digits.', 'همهٔ کنترل‌ها کار می‌کنند: دکمهٔ آبیِ پیش‌فرض به Enter در هر جای پنل جواب می‌دهد، دکمهٔ pop-up منوی واقعی باز می‌کند و اسلایدر با hover بادکنک مقدارش را نشان می‌دهد — همهٔ عددها با رقم فارسی.') }}
        </p>
    </div>

    <div class="mcctl-stage"
         x-data="{
                fa: {{ $fa ? 'true' : 'false' }},
                themes: {{ json_encode($themes) }},
                themeIdx: 1, menuOpen: false,
                hints: true, size: 'md', bright: 70, level: 12,
                status: '', statusT: null,
                n2(v) { const s = String(v); return this.fa ? s.replace(/\d/g, d => '۰۱۲۳۴۵۶۷۸۹'[+d]) : s },
                themeName() { const t = this.themes[this.themeIdx]; return this.fa ? t.fa : t.en },
                note(m) { this.status = m; clearTimeout(this.statusT); this.statusT = setTimeout(() => { this.status = '' }, 2400) },
                applyDefault() { this.menuOpen = false; this.note(this.fa ? 'صحنهٔ پیش‌فرض اعمال شد' : 'Default scene applied') },
                pick(i) { this.themeIdx = i; this.menuOpen = false },
                step(d) { this.level = Math.min(20, Math.max(0, this.level + d)) },
                key(e) {
                    if (e.target.closest('button, input, [role=option]')) return;
                    if (e.key === 'Enter') { e.preventDefault(); this.applyDefault() }
                },
            }"
         x-on:keydown="key($event)">
        <div class="mcctl-panel" role="group" aria-label="{{ $say('Display preferences', 'تنظیمات نمایش') }}">
            <h4>{{ $say('Display', 'نمایشگر') }}<small>{{ $say('Scene «Studio» — unsaved changes', 'صحنهٔ «استودیو» — تغییرات ذخیره‌نشده') }}</small></h4>

            <div class="mcctl-row">
                <span class="mcctl-lab" id="mcctl-act">{{ $say('Actions', 'کنش‌ها') }}</span>
                <div class="mcctl-field" role="group" aria-labelledby="mcctl-act">
                    <button type="button" class="mcctl-push" x-on:click="note(fa ? 'حالت کم‌نور شد' : 'Dimmed')">{{ $say('Dim', 'بی‌اعتبار') }}</button>
                    <button type="button" class="mcctl-push" data-default aria-keyshortcuts="Enter" x-on:click="applyDefault()">
                        {{ $say('Defaults', 'پیش‌فرض') }} <kbd class="mcctl-kbd" aria-hidden="true">⏎</kbd>
                    </button>
                </div>
            </div>

            <div class="mcctl-row">
                <span class="mcctl-lab" id="mcctl-vis">{{ $say('Visibility', 'نمایش') }}</span>
                <div class="mcctl-field mcctl-pick" role="group" aria-labelledby="mcctl-vis">
                    <label class="mcctl-check">
                        <input type="checkbox" x-model="hints">
                        <i aria-hidden="true"></i>
                        <span>{{ $say('Show hints', 'نمایش راهنماها') }}</span>
                    </label>
                    <span role="radiogroup" aria-label="{{ $say('Cursor size', 'اندازهٔ نشانگر') }}" style="display: inline-flex; gap: 8px 12px; flex-wrap: wrap">
                        @foreach ([['id' => 'sm', 'fa' => 'کوچک', 'en' => 'Small'], ['id' => 'md', 'fa' => 'متوسط', 'en' => 'Medium'], ['id' => 'lg', 'fa' => 'بزرگ', 'en' => 'Large']] as $opt)
                            <label class="mcctl-radio">
                                <input type="radio" name="mcctl-size" value="{{ $opt['id'] }}" x-model="size">
                                <i aria-hidden="true"></i>
                                <span>{{ $say($opt['en'], $opt['fa']) }}</span>
                            </label>
                        @endforeach
                    </span>
                </div>
            </div>

            <div class="mcctl-row">
                <span class="mcctl-lab" id="mcctl-theme">{{ $say('Appearance', 'ظاهر') }}</span>
                <div class="mcctl-field">
                    <div class="mcctl-popwrap">
                        <button type="button" class="mcctl-pop" aria-haspopup="listbox"
                                :aria-expanded="menuOpen ? 'true' : 'false'"
                                x-on:click="menuOpen = ! menuOpen">
                            <span x-text="themeName()">{{ $say('Auto', 'خودکار') }}</span>
                            <svg viewBox="0 0 10 14" aria-hidden="true"><path d="M1.6 5L5 1.6 8.4 5"/><path d="M1.6 9L5 12.4 8.4 9"/></svg>
                        </button>
                        <div class="mcctl-popmenu" role="listbox" aria-labelledby="mcctl-theme"
                             x-show="menuOpen" x-cloak x-transition.opacity.duration.120ms x-on:click.outside="menuOpen = false" x-on:keydown.escape="menuOpen = false">
                            @foreach ($themes as $i => $t)
                                <button type="button" class="mcctl-popitem" role="option"
                                        :aria-selected="themeIdx === {{ $i }} ? 'true' : 'false'"
                                        x-on:click="pick({{ $i }})">
                                    <span class="mcctl-tick" aria-hidden="true" x-text="themeIdx === {{ $i }} ? '✓' : ''"></span>
                                    {{ $say($t['en'], $t['fa']) }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="mcctl-row">
                <span class="mcctl-lab" id="mcctl-bri">{{ $say('Brightness', 'روشندگی') }}</span>
                <div class="mcctl-field">
                    <div class="mcctl-slider">
                        <output class="mcctl-bubble" x-text="n2(bright) + '٪'" :style="'inset-inline-start: calc(' + ((bright - 10) / 90 * 100) + '% - 20px)'">۷۰٪</output>
                        <input type="range" min="10" max="100" x-model.number="bright" aria-labelledby="mcctl-bri">
                    </div>
                    <span class="mcctl-val" x-text="n2(bright) + '٪'">۷۰٪</span>
                </div>
            </div>

            <div class="mcctl-row">
                <span class="mcctl-lab" id="mcctl-lvl">{{ $say('Intensity', 'شدت') }}</span>
                <div class="mcctl-field">
                    <span class="mcctl-stepval" x-text="n2(level)">۱۲</span>
                    <span class="mcctl-stepper">
                        <button type="button" aria-label="{{ $say('Increase', 'افزایش') }}" x-on:click="step(1)">
                            <svg viewBox="0 0 8 5" aria-hidden="true"><path d="M1 4l3-3 3 3"/></svg>
                        </button>
                        <button type="button" aria-label="{{ $say('Decrease', 'کاهش') }}" x-on:click="step(-1)">
                            <svg viewBox="0 0 8 5" aria-hidden="true"><path d="M1 1l3 3 3-3"/></svg>
                        </button>
                    </span>
                    <span class="mcctl-status" x-cloak x-show="status" x-text="status" role="status"></span>
                </div>
            </div>
        </div>
    </div>
</section>
