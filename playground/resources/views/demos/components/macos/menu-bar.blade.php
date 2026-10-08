{{--
    The Mac menu bar on a desktop stage: a 24px translucent bar with the
    Apple menu and File menu (⌘ shortcut column, separators, one ticked row),
    status items on the trailing side, and a Control Centre popover whose
    brightness slider really dims the wallpaper via a CSS variable.
    Click-outside and Escape close everything.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    .mcmenu-root {
        --mcmenu-text: #1E1E1E; --mcmenu-text2: #6D6D72; --mcmenu-accent: #007AFF;
        --mcmenu-hair: rgba(0, 0, 0, .15); --mcmenu-div: rgba(0, 0, 0, .1);
        --mcmenu-bar: rgba(255, 255, 255, .55); --mcmenu-glass: rgba(245, 245, 245, .92);
        font-family: system-ui, -apple-system, "Vazirmatn", sans-serif;
    }
    html[data-theme="dark"] .mcmenu-root {
        --mcmenu-text: #F5F5F5; --mcmenu-text2: #A5A5AA; --mcmenu-accent: #0A84FF;
        --mcmenu-hair: rgba(255, 255, 255, .15); --mcmenu-div: rgba(255, 255, 255, .1);
        --mcmenu-bar: rgba(28, 28, 30, .6); --mcmenu-glass: rgba(40, 40, 40, .85);
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .mcmenu-root {
            --mcmenu-text: #F5F5F5; --mcmenu-text2: #A5A5AA; --mcmenu-accent: #0A84FF;
            --mcmenu-hair: rgba(255, 255, 255, .15); --mcmenu-div: rgba(255, 255, 255, .1);
            --mcmenu-bar: rgba(28, 28, 30, .6); --mcmenu-glass: rgba(40, 40, 40, .85);
        }
    }

    .mcmenu-stage {
        position: relative; overflow: clip; min-block-size: 26rem; padding: 3.5rem 1rem 1.25rem;
        border-radius: var(--nx-radius-2xl); color: var(--mcmenu-text);
    }
    .mcmenu-wall {
        position: absolute; inset: 0; opacity: calc(.2 + .8 * var(--mcmenu-bright, 85) / 100);
        transition: opacity .15s linear;
        background: linear-gradient(140deg, #27406B 0%, #8A5E8E 52%, #E8A87C 100%);
    }
    .mcmenu-wall i { position: absolute; inline-size: 46%; aspect-ratio: 1; border-radius: 50%; filter: blur(44px); opacity: .7; animation: mcmenu-drift 18s ease-in-out infinite alternate; }
    .mcmenu-wall i:nth-child(1) { inset-block-start: -10%; inset-inline-start: 4%; background: #5AC8FA; }
    .mcmenu-wall i:nth-child(2) { inset-block-end: -16%; inset-inline-start: 34%; background: #FF9F0A; animation-delay: -6s; }
    .mcmenu-wall i:nth-child(3) { inset-block-start: 18%; inset-inline-end: -8%; background: #BF5AF2; animation-delay: -12s; }
    @keyframes mcmenu-drift { to { translate: 6% -8%; scale: 1.14; } }
    @media (prefers-reduced-motion: reduce) { .mcmenu-wall i { animation: none; } }

    .mcmenu-bar {
        position: absolute; inset-inline: 0; inset-block-start: 0; z-index: 40;
        display: flex; align-items: stretch; block-size: 24px; padding-inline: 10px 20px;
        background: var(--mcmenu-bar); backdrop-filter: blur(20px) saturate(1.8);
        box-shadow: 0 .5px 0 var(--mcmenu-div); font: 400 13px/1 system-ui, -apple-system, "Vazirmatn", sans-serif;
    }
    .mcmenu-slot { position: relative; display: flex; }
    .mcmenu-item {
        display: flex; align-items: center; gap: 5px; padding-inline: 10px; border: 0; cursor: pointer;
        border-radius: 4px; margin-block: 2px; color: var(--mcmenu-text); background: transparent;
        font: inherit; white-space: nowrap;
    }
    .mcmenu-item:hover, .mcmenu-item[data-open] { background: rgba(0, 0, 0, .12); }
    html[data-theme="dark"] .mcmenu-item:hover, html[data-theme="dark"] .mcmenu-item[data-open] { background: rgba(255, 255, 255, .14); }
    .mcmenu-item svg { inline-size: 15px; block-size: 15px; }
    .mcmenu-item[data-apple] svg { inline-size: 14px; block-size: 14px; fill: currentColor; }
    .mcmenu-space { flex: 1; }
    .mcmenu-status { display: flex; align-items: center; gap: 2px; padding-inline: 4px; font-size: 12px; white-space: nowrap; }
    .mcmenu-batt { position: relative; display: inline-block; inline-size: 22px; block-size: 11px; margin-inline: 4px 6px; border: 1px solid var(--mcmenu-text2); border-radius: 3px; }
    .mcmenu-batt::after { content: ''; position: absolute; inset-inline-end: -3.5px; inset-block: 2.5px; inline-size: 2px; border-radius: 0 1px 1px 0; background: var(--mcmenu-text2); }
    .mcmenu-batt i { position: absolute; inset: 1.5px; inset-inline-end: calc(100% - 69%); border-radius: 1px; background: var(--mcmenu-text); }
    /* On narrow stages the bar is tight; slimmer items keep every status item
       inside the bar once the trailing side is inset past the 28px corner. */
    @media (max-width: 480px) {
        .mcmenu-item { padding-inline: 7px; }
    }

    .mcmenu-drop {
        position: absolute; inset-block-start: 100%; inset-inline-start: 0; z-index: 50;
        inline-size: 232px; padding: 4px; border-radius: 6px;
        background: var(--mcmenu-glass); backdrop-filter: blur(40px) saturate(1.8);
        box-shadow: 0 10px 40px rgba(0, 0, 0, .22), 0 0 0 .5px var(--mcmenu-hair);
    }
    .mcmenu-row {
        display: flex; align-items: center; gap: 8px; inline-size: 100%; padding: 4px 10px;
        border: 0; border-radius: 4px; cursor: pointer; background: transparent; color: var(--mcmenu-text);
        font: 400 13px/1.5 system-ui, -apple-system, "Vazirmatn", sans-serif; text-align: start;
    }
    .mcmenu-row:hover, .mcmenu-row:focus-visible { background: var(--mcmenu-accent); color: #fff; }
    .mcmenu-row:hover .mcmenu-key, .mcmenu-row:focus-visible .mcmenu-key { color: #fff; }
    .mcmenu-row .mcmenu-gutter { inline-size: 12px; flex: none; text-align: center; font-size: 11px; }
    .mcmenu-row .mcmenu-key { margin-inline-start: auto; padding-inline-start: 18px; color: var(--mcmenu-text2); font-size: 12px; }
    .mcmenu-sep { block-size: 1px; margin: 4px 10px; background: var(--mcmenu-div); }
    .mcmenu-row[disabled] { color: var(--mcmenu-text2); }
    .mcmenu-row[disabled]:hover { background: transparent; color: var(--mcmenu-text2); }

    .mcmenu-cc {
        position: absolute; inset-block-start: 32px; inset-inline-end: 8px; z-index: 50;
        inline-size: min(300px, calc(100% - 16px)); padding: 10px; border-radius: 14px;
        background: var(--mcmenu-glass); backdrop-filter: blur(40px) saturate(1.6);
        box-shadow: 0 14px 44px rgba(0, 0, 0, .26), 0 0 0 .5px var(--mcmenu-hair);
        display: grid; gap: 8px;
    }
    .mcmenu-tile {
        display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 12px;
        background: color-mix(in srgb, var(--mcmenu-glass) 55%, rgba(127, 127, 127, .12));
        box-shadow: 0 0 0 .5px var(--mcmenu-div);
    }
    .mcmenu-tile b { display: block; font: 600 13px/1.3 system-ui, -apple-system, "Vazirmatn", sans-serif; }
    .mcmenu-tile small { display: block; color: var(--mcmenu-text2); font-size: 11px; }
    .mcmenu-ico {
        flex: none; display: grid; place-items: center; inline-size: 30px; aspect-ratio: 1;
        border-radius: 50%; background: var(--mcmenu-accent); color: #fff;
    }
    .mcmenu-ico svg { inline-size: 16px; block-size: 16px; }
    .mcmenu-ico[data-dim] { background: color-mix(in srgb, var(--mcmenu-text2) 55%, transparent); }
    .mcmenu-ico[data-bt] { background: #0A84FF; }
    .mcmenu-on { margin-inline-start: auto; color: var(--mcmenu-accent); font: 600 11px/1 system-ui, -apple-system, "Vazirmatn", sans-serif; }
    .mcmenu-slab { flex: 1; display: grid; gap: 6px; }
    .mcmenu-slab input[type='range'] { margin: 0; }
    .mcmenu-slider { position: relative; display: flex; align-items: center; block-size: 28px; flex: 1; }
    .mcmenu-slider input {
        -webkit-appearance: none; appearance: none; inline-size: 100%; block-size: 22px; margin: 0;
        background: transparent; cursor: pointer;
    }
    .mcmenu-slider input::-webkit-slider-runnable-track { block-size: 22px; border-radius: 7px; background: color-mix(in srgb, var(--mcmenu-text2) 28%, transparent); box-shadow: inset 0 0 1px rgba(0, 0, 0, .3); }
    .mcmenu-slider input::-webkit-slider-thumb { -webkit-appearance: none; inline-size: 20px; aspect-ratio: 1; margin-block-start: 1px; border-radius: 50%; background: #fff; box-shadow: 0 .5px 3px rgba(0, 0, 0, .4), 0 0 0 .5px rgba(0, 0, 0, .15); }
    .mcmenu-slider input::-moz-range-track { block-size: 22px; border-radius: 7px; background: color-mix(in srgb, var(--mcmenu-text2) 28%, transparent); }
    .mcmenu-slider input::-moz-range-thumb { inline-size: 20px; aspect-ratio: 1; border: 0; border-radius: 50%; background: #fff; box-shadow: 0 .5px 3px rgba(0, 0, 0, .4); }
    .mcmenu-pct { min-inline-size: 38px; text-align: end; font: 500 12px/1 system-ui, -apple-system, "Vazirmatn", sans-serif; color: var(--mcmenu-text2); font-variant-numeric: tabular-nums; }
    .mcmenu-clock { position: relative; z-index: 1; align-self: center; margin-inline-start: auto; color: #fff; text-shadow: 0 1px 2px rgba(16, 10, 38, .6), 0 2px 14px rgba(16, 10, 38, .45); font: 600 13px/1.6 system-ui, -apple-system, "Vazirmatn", sans-serif; text-align: center; }
    .mcmenu-clock small { display: block; font-weight: 400; font-size: 11px; }
    .mcmenu-root :is(button, input):focus-visible { outline: 2px solid var(--mcmenu-accent); outline-offset: 1px; }
    /* The props table (rendered by the demo page wrapper) reveals its tbody
       rows only when scrolled into view, and the package's own failsafe skips
       Livewire pages — so in static captures the table looks empty. Mirror that
       failsafe on this page: the rows are always rendered. */
    :where(.nx-js) .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr { opacity: 1; translate: none; }
</style>

<section class="pg-box mcmenu-root">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Menu bar & Control Centre', 'نوار منو و مرکز کنترل') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Open the Apple and File menus for real dropdowns with ⌘ shortcuts and a ticked row, then pull down Control Centre — its brightness slider genuinely dims the wallpaper.', 'منوهای Apple و پرونده را با میان‌برهای ⌘ و ردیف تیک‌خورده باز کنید، بعد مرکز کنترل را پایین بکشید — اسلایدر روشنایی‌اش واقعاً کاغذدیواری را کم‌نور می‌کند.') }}
        </p>
    </div>

    <div class="mcmenu-stage"
         x-data="{
                open: null, bright: 85, vol: 40,
                pick(m) { this.open = this.open === m ? null : m },
            }"
         :style="'--mcmenu-bright:' + bright"
         x-on:click.outside="open = null"
         x-on:keydown.escape.window="open = null">
        <div class="mcmenu-wall" aria-hidden="true"><i></i><i></i><i></i></div>

        <p class="mcmenu-clock">{{ $say('Thursday 16 Mehr', 'پنج‌شنبه ۱۶ مهر') }}<small>۱۴:۰۵</small></p>

        <nav class="mcmenu-bar" aria-label="{{ $say('Menu bar', 'نوار منو') }}">
            <div class="mcmenu-slot">
                <button type="button" class="mcmenu-item" data-apple aria-haspopup="menu"
                        :aria-expanded="open === 'apple' ? 'true' : 'false'"
                        :data-open="open === 'apple' ? '' : null"
                        aria-label="Apple" x-on:click="pick('apple')">
                    <svg viewBox="0 0 384 512" aria-hidden="true"><path d="M318.7 268.7c-.2-36.7 16.4-64.4 50-84.8-18.8-26.9-47.2-41.7-84.7-44.6-35.5-2.8-74.3 20.7-88.5 20.7-15 0-49.4-19.7-76.4-19.7C63.3 141.2 4 184.8 4 273.5q0 39.3 14.4 81.2c12.8 36.7 59 126.7 107.2 125.2 25.2-.6 43-17.9 75.8-17.9 31.8 0 48.3 17.9 76.4 17.9 48.6-.7 90.4-82.5 102.6-119.3-65.2-30.7-61.7-90-61.7-91.9zm-56.6-164.2c27.3-32.4 24.8-61.9 24-72.5-24.1 1.4-52 16.4-67.9 34.9-17.5 19.8-27.8 44.3-25.6 71.9 26.1 2 49.9-11.4 69.5-34.3z"/></svg>
                </button>
                <div class="mcmenu-drop" role="menu" aria-label="Apple" x-show="open === 'apple'" x-cloak x-transition.opacity.duration.120ms>
                    <button type="button" class="mcmenu-row" role="menuitem">{{ $say('About This Mac', 'دربارهٔ این مک') }}</button>
                    <div class="mcmenu-sep" role="separator"></div>
                    <button type="button" class="mcmenu-row" role="menuitem">{{ $say('System Settings…', 'تنظیمات سیستم…') }}</button>
                    <button type="button" class="mcmenu-row" role="menuitem">App Store…</button>
                    <div class="mcmenu-sep" role="separator"></div>
                    <button type="button" class="mcmenu-row" role="menuitem"><span class="mcmenu-gutter" aria-hidden="true"></span>{{ $say('Sleep', 'خواب') }}<span class="mcmenu-key">⇧⌃⏏</span></button>
                    <button type="button" class="mcmenu-row" role="menuitem"><span class="mcmenu-gutter" aria-hidden="true"></span>{{ $say('Restart…', 'راه‌اندازی دوباره…') }}</button>
                    <button type="button" class="mcmenu-row" role="menuitem"><span class="mcmenu-gutter" aria-hidden="true"></span>{{ $say('Shut Down…', 'خاموش کردن…') }}</button>
                    <div class="mcmenu-sep" role="separator"></div>
                    <button type="button" class="mcmenu-row" role="menuitem"><span class="mcmenu-gutter" aria-hidden="true"></span>{{ $say('Lock Screen', 'قفل صفحه') }}<span class="mcmenu-key">⌃⌘Q</span></button>
                    <button type="button" class="mcmenu-row" role="menuitem"><span class="mcmenu-gutter" aria-hidden="true"></span>{{ $say('Log Out…', 'خروج از «نابو»…') }}<span class="mcmenu-key">⇧⌘Q</span></button>
                </div>
            </div>

            <div class="mcmenu-slot">
                <button type="button" class="mcmenu-item" aria-haspopup="menu"
                        :aria-expanded="open === 'file' ? 'true' : 'false'"
                        :data-open="open === 'file' ? '' : null"
                        x-on:click="pick('file')">{{ $say('File', 'پرونده') }}</button>
                <div class="mcmenu-drop" role="menu" aria-label="{{ $say('File', 'پرونده') }}" x-show="open === 'file'" x-cloak x-transition.opacity.duration.120ms>
                    <button type="button" class="mcmenu-row" role="menuitem"><span class="mcmenu-gutter" aria-hidden="true"></span>{{ $say('New Window', 'پنجرهٔ جدید') }}<span class="mcmenu-key">⌘N</span></button>
                    <button type="button" class="mcmenu-row" role="menuitem"><span class="mcmenu-gutter" aria-hidden="true"></span>{{ $say('New Folder', 'پوشهٔ جدید') }}<span class="mcmenu-key">⇧⌘N</span></button>
                    <button type="button" class="mcmenu-row" role="menuitem"><span class="mcmenu-gutter" aria-hidden="true"></span>{{ $say('Open…', 'باز کردن…') }}<span class="mcmenu-key">⌘O</span></button>
                    <div class="mcmenu-sep" role="separator"></div>
                    <button type="button" class="mcmenu-row" role="menuitem"><span class="mcmenu-gutter" aria-hidden="true">✓</span>{{ $say('Show Sidebar', 'نمایش نوار کنار') }}<span class="mcmenu-key">⌥⌘S</span></button>
                    <button type="button" class="mcmenu-row" role="menuitem" disabled><span class="mcmenu-gutter" aria-hidden="true"></span>{{ $say('Save', 'ذخیره') }}<span class="mcmenu-key">⌘S</span></button>
                    <div class="mcmenu-sep" role="separator"></div>
                    <button type="button" class="mcmenu-row" role="menuitem"><span class="mcmenu-gutter" aria-hidden="true"></span>{{ $say('Close Window', 'بستن پنجره') }}<span class="mcmenu-key">⌘W</span></button>
                </div>
            </div>

            <div class="mcmenu-slot">
                <button type="button" class="mcmenu-item" aria-haspopup="menu"
                        :aria-expanded="open === 'edit' ? 'true' : 'false'"
                        :data-open="open === 'edit' ? '' : null"
                        x-on:click="pick('edit')">{{ $say('Edit', 'ویرایش') }}</button>
                <div class="mcmenu-drop" role="menu" aria-label="{{ $say('Edit', 'ویرایش') }}" x-show="open === 'edit'" x-cloak x-transition.opacity.duration.120ms>
                    <button type="button" class="mcmenu-row" role="menuitem"><span class="mcmenu-gutter" aria-hidden="true"></span>{{ $say('Undo', 'واگرد') }}<span class="mcmenu-key">⌘Z</span></button>
                    <button type="button" class="mcmenu-row" role="menuitem"><span class="mcmenu-gutter" aria-hidden="true"></span>{{ $say('Redo', 'ازنو') }}<span class="mcmenu-key">⇧⌘Z</span></button>
                    <div class="mcmenu-sep" role="separator"></div>
                    <button type="button" class="mcmenu-row" role="menuitem"><span class="mcmenu-gutter" aria-hidden="true"></span>{{ $say('Cut', 'برش') }}<span class="mcmenu-key">⌘X</span></button>
                    <button type="button" class="mcmenu-row" role="menuitem"><span class="mcmenu-gutter" aria-hidden="true"></span>{{ $say('Copy', 'رونوشت') }}<span class="mcmenu-key">⌘C</span></button>
                    <button type="button" class="mcmenu-row" role="menuitem"><span class="mcmenu-gutter" aria-hidden="true"></span>{{ $say('Paste', 'چسباندن') }}<span class="mcmenu-key">⌘V</span></button>
                </div>
            </div>

            <span class="mcmenu-space"></span>

            <span class="mcmenu-status" aria-hidden="true">
                <svg viewBox="0 0 24 24" style="inline-size: 16px; block-size: 16px; fill: none; stroke: currentColor; stroke-width: 1.9; stroke-linecap: round"><path d="M4 9.8C6.3 7.7 9 6.6 12 6.6s5.7 1.1 8 3.2"/><path d="M6.8 13.4C8.3 12 10 11.3 12 11.3s3.7.7 5.2 2.1"/><circle cx="12" cy="17.2" r="1.7" fill="currentColor" stroke="none"/></svg>
            </span>
            <span class="mcmenu-status" aria-hidden="true">
                <span class="mcmenu-batt"><i></i></span>
            </span>
            <div class="mcmenu-slot">
                <button type="button" class="mcmenu-item" aria-haspopup="dialog"
                        :aria-expanded="open === 'cc' ? 'true' : 'false'"
                        :data-open="open === 'cc' ? '' : null"
                        aria-label="{{ $say('Control Centre', 'مرکز کنترل') }}" x-on:click="pick('cc')">
                    <svg viewBox="0 0 26 22" aria-hidden="true"><rect x="1" y="1.5" width="24" height="8.5" rx="4.25" fill="none" stroke="currentColor" stroke-width="1.6"/><circle cx="6" cy="5.75" r="2.5" fill="currentColor"/><rect x="1" y="12" width="24" height="8.5" rx="4.25" fill="none" stroke="currentColor" stroke-width="1.6"/><circle cx="20" cy="16.25" r="2.5" fill="currentColor"/></svg>
                </button>
            </div>
        </nav>

        <div class="mcmenu-cc" role="dialog" aria-label="{{ $say('Control Centre', 'مرکز کنترل') }}"
             x-show="open === 'cc'" x-cloak x-transition.opacity.duration.150ms>
            <div class="mcmenu-tile">
                <span class="mcmenu-ico" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 9.8C6.3 7.7 9 6.6 12 6.6s5.7 1.1 8 3.2"/><path d="M6.8 13.4C8.3 12 10 11.3 12 11.3s3.7.7 5.2 2.1"/><circle cx="12" cy="17.2" r="1.7" fill="currentColor" stroke="none"/></svg>
                </span>
                <span><b>Wi-Fi</b><small>{{ $say('Nabu Studio', 'نابو استودیو') }}</small></span>
                <span class="mcmenu-on">{{ $say('On', 'روشن') }}</span>
            </div>
            <div class="mcmenu-tile">
                <span class="mcmenu-ico" data-bt aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 7l10 10-5 4V3l5 4L7 17"/></svg>
                </span>
                <span><b>Bluetooth</b><small>{{ $say('Keyboard, Trackpad', 'کیبورد، ترک‌پد') }}</small></span>
                <span class="mcmenu-on">{{ $say('On', 'روشن') }}</span>
            </div>
            <div class="mcmenu-tile">
                <span class="mcmenu-ico {{ $fa ? '' : '' }}" data-dim aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"><circle cx="12" cy="12" r="4"/><path d="M12 2.5v2.6M12 18.9v2.6M2.5 12h2.6M18.9 12h2.6M5 5l1.8 1.8M17.2 17.2L19 19M19 5l-1.8 1.8M6.8 17.2L5 19"/></svg>
                </span>
                <span class="mcmenu-slab">
                    <b>{{ $say('Display', 'نمایشگر') }}</b>
                    <span class="mcmenu-slider">
                        <input type="range" min="20" max="100" x-model.number="bright" aria-label="{{ $say('Brightness', 'روشندگی') }}">
                    </span>
                </span>
            </div>
            <div class="mcmenu-tile">
                <span class="mcmenu-ico" data-dim aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9.5v5h3.5L12 18.5v-13L7.5 9.5zM15.5 9a4.2 4.2 0 0 1 0 6M18 6.5a8 8 0 0 1 0 11"/></svg>
                </span>
                <span class="mcmenu-slab">
                    <b>{{ $say('Sound', 'صدا') }}</b>
                    <span class="mcmenu-slider">
                        <input type="range" min="0" max="100" x-model.number="vol" aria-label="{{ $say('Volume', 'بلندی صدا') }}">
                        <span class="mcmenu-pct" x-text="vol + '٪'">۴۰٪</span>
                    </span>
                </span>
            </div>
        </div>
    </div>
</section>
