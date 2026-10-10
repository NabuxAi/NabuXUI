{{--
    Carbon's overflow menu on a file list: the kebab opens a flat, borderless
    item stack anchored under it, Escape and outside taps fold it back, and the
    destructive item sits quarantined at the bottom — deleting actually removes
    the row. A bottom-anchored card shows the flipped-up direction, and the
    variants row compares sizes.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap');
    .cbov-root {
        --cbov-accent: #0f62fe; --cbov-accent-hover: #0353e9;
        --cbov-text: #161616; --cbov-text-secondary: #525252;
        --cbov-danger: #da1e28;
        --cbov-border: #e0e0e0; --cbov-border-strong: #8d8d8d;
        --cbov-layer: #f4f4f4; --cbov-layer-hover: #e8e8e8; --cbov-layer-active: #d1d1d1;
        --cbov-font: 'IBM Plex Sans', 'Inter', 'Vazirmatn', sans-serif;
        font-family: var(--cbov-font);
        display: grid; gap: 2rem; justify-items: center;
    }
    html[data-theme="dark"] .cbov-root {
        --cbov-accent: #4589ff; --cbov-accent-hover: #78a9ff;
        --cbov-text: #f4f4f4; --cbov-text-secondary: #c6c6c6;
        --cbov-danger: #fa4d56;
        --cbov-border: #393939; --cbov-border-strong: #8d8d8d;
        --cbov-layer: #262626; --cbov-layer-hover: #333333; --cbov-layer-active: #474747;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .cbov-root {
            --cbov-accent: #4589ff; --cbov-accent-hover: #78a9ff;
            --cbov-text: #f4f4f4; --cbov-text-secondary: #c6c6c6;
            --cbov-danger: #fa4d56;
            --cbov-border: #393939; --cbov-border-strong: #8d8d8d;
            --cbov-layer: #262626; --cbov-layer-hover: #333333; --cbov-layer-active: #474747;
        }
    }
    .cbov-list { inline-size: min(100%, 34rem); border-block-end: 1px solid var(--cbov-border); }
    .cbov-file { position: relative; display: flex; align-items: center; gap: .875rem; padding: .75rem 1rem;
                 border-block-start: 1px solid var(--cbov-border); background: var(--cbov-layer);
                 transition: opacity .18s ease-out, translate .18s ease-out; }
    .cbov-file[data-dying] { opacity: 0; translate: 0 -25%; }
    .cbov-file svg { flex: none; color: var(--cbov-text-secondary); }
    .cbov-file b { font-weight: 600; font-size: .875rem; color: var(--cbov-text); }
    .cbov-file small { display: block; font-size: .75rem; color: var(--cbov-text-secondary); }
    .cbov-file .cbov-size { margin-inline-start: auto; font: 400 .75rem/1 'IBM Plex Mono', Menlo, Consolas, monospace; color: var(--cbov-text-secondary); }
    .cbov { position: relative; }
    .cbov-btn { display: grid; place-items: center; inline-size: 2rem; aspect-ratio: 1; padding: 0; border: none;
                background: transparent; color: var(--cbov-text); cursor: pointer; transition: background-color .11s ease-in; }
    .cbov-btn:hover { background: var(--cbov-layer-hover); }
    .cbov-btn:focus-visible { outline: 2px solid var(--cbov-accent); outline-offset: 1px; }
    .cbov[data-open='true'] .cbov-btn { background: var(--cbov-layer-active); }
    .cbov-menu { position: absolute; inset-block-start: calc(100% + 2px); inset-inline-end: 0; z-index: 6; min-inline-size: 11rem;
                 margin: 0; padding: 0; list-style: none; background: var(--cbov-layer); box-shadow: 0 2px 6px rgba(0, 0, 0, .25); }
    .cbov[data-open='false'] .cbov-menu { display: none; }
    .cbov-item { inline-size: 100%; display: flex; align-items: center; gap: .5rem; padding: .625rem 1rem; border: none;
                 border-block-end: 1px solid var(--cbov-border); background: transparent; color: var(--cbov-text);
                 font: 400 .875rem/1.3 var(--cbov-font); text-align: start; cursor: pointer; }
    .cbov-item:hover { background: var(--cbov-layer-hover); }
    .cbov-item:focus-visible { outline: 2px solid var(--cbov-accent); outline-offset: -2px; }
    .cbov-item[data-danger] { color: var(--cbov-danger); }
    .cbov[data-up='true'] .cbov-menu { inset-block-start: auto; inset-block-end: calc(100% + 2px); box-shadow: 0 -2px 6px rgba(0, 0, 0, .25); }
    .cbov-toast { display: flex; align-items: center; gap: .5rem; inline-size: min(100%, 34rem); min-block-size: 2.75rem;
                  padding: .5rem 1rem; background: var(--cbov-layer); border-block-start: 2px solid var(--cbov-danger);
                  font-size: .875rem; color: var(--cbov-text); }
    .cbov-toast button { margin-inline-start: auto; block-size: 2rem; padding-inline: .875rem; border: none;
                         background: transparent; color: var(--cbov-accent); font: 400 .875rem/1 var(--cbov-font); cursor: pointer; }
    .cbov-toast button:hover { text-decoration: underline; }
    .cbov-card { position: relative; inline-size: min(100%, 22rem); padding: 1rem; padding-block-end: 3.25rem;
                 background: var(--cbov-layer); }
    .cbov-card .cbov { position: absolute; inset-inline-end: 1rem; inset-block-end: .875rem; }
    .cbov-card b { display: block; font-weight: 600; font-size: .875rem; color: var(--cbov-text); }
    .cbov-card p { margin: .25rem 0 0; font-size: .8125rem; color: var(--cbov-text-secondary); }
    .cbov-spec { display: grid; gap: 1.5rem; justify-items: center; }
    .cbov-spec-row { display: flex; flex-wrap: wrap; gap: 2.5rem; justify-content: center; align-items: flex-start; }
    .cbov-spec-cell { display: grid; gap: .5rem; justify-items: center; }
    .cbov-spec-cell > small { font-size: .72rem; color: var(--nx-text-muted); }
    :where(.nx-js) .pg:has(.cbov-root) .nx-data-table[data-nx-reveal] tbody tr { opacity: 1; translate: none; }
    @media (max-width: 480px) {
        .pg:has(.cbov-root) .nx-data-table :is(th, td) { white-space: normal; padding-inline: .5rem; overflow-wrap: break-word; }
        .cbov-toast { flex-wrap: wrap; }
    }
    @media (prefers-reduced-motion: reduce) {
        .cbov-root * { transition-duration: .01ms !important; animation-duration: .01ms !important; }
    }
</style>

<div class="cbov-root"
    x-data="{
        openId: null,
        undo: null,
        files: [
            { id: 1, name: 'nabu-roadmap-{{ $fa ? '۱۴۰۵' : '2026' }}.md', meta: '{{ $say('Edited today, ' . $num('14:20'), 'ویرایش امروز، ۱۴:۲۰') }}', size: '{{ $say('18KB', '۱۸KB') }}' },
            { id: 2, name: 'q3-metrics.csv', meta: '{{ $say('Edited yesterday, ' . $num('09:48'), 'ویرایش دیروز، ۰۹:۴۸') }}', size: '{{ $say('240KB', '۲۴۰KB') }}' },
            { id: 3, name: 'onboarding-guide.pdf', meta: '{{ $say('Uploaded by Sara, Oct 2', 'بارگذاری سارا، ۲ مهر') }}', size: '{{ $say('1.2MB', '۱٫۲MB') }}' },
        ],
        dyingId: null,
        pick(file, msg) { this.openId = null; this.toast(msg) },
        toast(msg) { this.undo = { msg, timer: setTimeout(() => this.undo = null, 6000) } },
        remove(file) {
            this.openId = null;
            this.dyingId = file.id;
            setTimeout(() => {
                this.files = this.files.filter(f => f.id !== file.id);
                this.dyingId = null;
                this.toast('{{ $say('Deleted', 'حذف شد') }}: ' + file.name);
            }, 200);
        },
    }"
    x-on:keydown.escape.window="openId = null"
    x-on:click.outside="openId = null">
    <section class="pg-box" style="justify-items: center">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The ever-present kebab', 'سه‌نقطهٔ همیشه‌حاضر') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('Borderless flat items snap open beneath the 3-dot button — rename and share act through the toast, and delete quarantines itself at the bottom of the list in red, then really deletes the row.', 'آیتم‌های تختِ بی‌حاشیه زیر دکمهٔ سه‌نقطه باز می‌شوند — تغییر نام و هم‌رسانی از راه توست عمل می‌کنند و حذف، قرمز و ته فهرست قرنطینه شده، بعد واقعاً ردیف را پاک می‌کند.') }}
            </p>
        </div>

        <div class="cbov-list">
            <template x-for="file in files" :key="file.id">
                <div class="cbov-file" x-bind:data-dying="dyingId === file.id ? 'true' : null">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 3H7a1 1 0 0 0-1 1v16a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V7z"/><path d="M14 3v4h4"/></svg>
                    <span>
                        <b x-text="file.name"></b>
                        <small x-text="file.meta"></small>
                    </span>
                    <span class="cbov-size" x-text="file.size"></span>
                    <div class="cbov" x-bind:data-open="(openId === file.id).toString()">
                        <button type="button" class="cbov-btn" x-on:click="openId = openId === file.id ? null : file.id"
                            x-bind:aria-expanded="(openId === file.id).toString()" aria-haspopup="menu"
                            x-bind:aria-label="'{{ $say('Actions for', 'اقدام‌های') }} ' + file.name">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="12" cy="5.5" r="1.7"/><circle cx="12" cy="12" r="1.7"/><circle cx="12" cy="18.5" r="1.7"/></svg>
                        </button>
                        <ul class="cbov-menu" role="menu" x-bind:aria-label="'{{ $say('Actions for', 'اقدام‌های') }} ' + file.name">
                            <li role="none"><button type="button" role="menuitem" class="cbov-item" x-on:click="pick(file, '{{ $say('Renamed — new name in your clipboard', 'نام عوض شد — نام تازه در کلیپ‌بورد شماست') }}')">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 3l4 4L8 20l-5 1 1-5z"/></svg>
                                {{ $say('Rename', 'تغییر نام') }}</button></li>
                            <li role="none"><button type="button" role="menuitem" class="cbov-item" x-on:click="pick(file, '{{ $say('Download started', 'دانلود آغاز شد') }}')">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v12"/><path d="M7 11l5 5 5-5"/><path d="M4 20h16"/></svg>
                                {{ $say('Download', 'دانلود') }}</button></li>
                            <li role="none"><button type="button" role="menuitem" class="cbov-item" x-on:click="pick(file, '{{ $say('Share link copied', 'پیوند هم‌رسانی کپی شد') }}')">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="18" cy="5" r="2.5"/><circle cx="6" cy="12" r="2.5"/><circle cx="18" cy="19" r="2.5"/><path d="M8.2 10.8l7.6-4.5M8.2 13.2l7.6 4.5"/></svg>
                                {{ $say('Share', 'هم‌رسانی') }}</button></li>
                            <li role="none"><button type="button" role="menuitem" class="cbov-item" data-danger x-on:click="remove(file)">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16"/><path d="M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/><path d="M6 7l1 13a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-13"/></svg>
                                {{ $say('Delete', 'حذف') }}</button></li>
                        </ul>
                    </div>
                </div>
            </template>
            <p style="margin: .75rem 0 0; font-size: .8125rem; color: var(--nx-text-muted)" x-show="files.length === 0" x-cloak>
                {{ $say('All three files are gone — refresh the page to bring them back.', 'هر سه فایل رفتند — برای بازگرداندنشان صفحه را تازه کنید.') }}
            </p>
        </div>

        <div class="cbov-toast" role="status" x-show="undo" x-cloak>
            <span x-text="undo?.msg"></span>
            <button type="button" x-on:click="clearTimeout(undo.timer); undo = null">{{ $say('Dismiss', 'بستن') }}</button>
        </div>
    </section>

    <section class="pg-box" style="justify-items: center">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('Flipped up, near the edge', 'نزدیک لبه، رو به بالا') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('Pinned to a card’s bottom corner, the menu anchors upward instead — same flat frame, same red quarantine.', 'چسبیده به گوشهٔ پایین کارت، منو به بالا لنگر می‌شود — همان قاب تخت، همان قرنطینهٔ قرمز.') }}
            </p>
        </div>
        <div class="cbov-card" x-data="{ open: false }" x-on:keydown.escape.window="open = false" x-on:click.outside="open = false">
            <b>{{ $say('Weekly report', 'گزارش هفتگی') }}</b>
            <p>{{ $say('Auto-sends every Sunday at ' . $num('8:00') . ' to the ops channel.', 'هر یکشنبه ساعت ۸:۰۰ خودکار به کانال عملیات می‌رود.') }}</p>
            <div class="cbov" data-up="true" x-bind:data-open="open.toString()">
                <button type="button" class="cbov-btn" x-on:click="open = !open" x-bind:aria-expanded="open.toString()" aria-haspopup="menu" aria-label="{{ $say('Report actions', 'اقدام‌های گزارش') }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="12" cy="5.5" r="1.7"/><circle cx="12" cy="12" r="1.7"/><circle cx="12" cy="18.5" r="1.7"/></svg>
                </button>
                <ul class="cbov-menu" role="menu">
                    <li role="none"><button type="button" role="menuitem" class="cbov-item" x-on:click="open = false">{{ $say('Send now', 'ارسال فوری') }}</button></li>
                    <li role="none"><button type="button" role="menuitem" class="cbov-item" x-on:click="open = false">{{ $say('Change schedule', 'تغییر زمان‌بندی') }}</button></li>
                    <li role="none"><button type="button" role="menuitem" class="cbov-item" data-danger x-on:click="open = false">{{ $say('Stop sending', 'توقف ارسال') }}</button></li>
                </ul>
            </div>
        </div>
    </section>
</div>

<section class="pg-box" style="justify-items: center">
    <div class="cbov-root" style="inline-size: 100%">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The whole family', 'همهٔ خانواده') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('Small, medium and large — the kebab scales with its menu, and the danger row keeps its place at the end.', 'sm، md و lg — سه‌نقطه هم‌اندازهٔ منویش بزرگ می‌شود و ردیف مخرب سرِ جایش در انتها می‌ماند.') }}
            </p>
        </div>
        <div class="cbov-spec">
        <div class="cbov-spec-row" x-on:keydown.escape.window="openId = null" x-on:click.outside="openId = null"
            x-data="{ openId: null }">
            @foreach ([['sm', '1.5rem', '1.25rem'], ['md', '2rem', '1.5rem'], ['lg', '2.5rem', '2rem']] as [$size, $btn, $icon])
                <div class="cbov-spec-cell">
                    <div class="cbov" x-bind:data-open="(openId === '{{ $size }}').toString()">
                        <button type="button" class="cbov-btn" style="inline-size: {{ $btn }}"
                            x-on:click="openId = openId === '{{ $size }}' ? null : '{{ $size }}'"
                            x-bind:aria-expanded="(openId === '{{ $size }}').toString()" aria-haspopup="menu"
                            aria-label="{{ $say('Menu', 'منو') }} {{ $size }}">
                            <svg width="{{ ['sm' => 14, 'md' => 16, 'lg' => 18][$size] }}" height="{{ ['sm' => 14, 'md' => 16, 'lg' => 18][$size] }}" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="12" cy="5.5" r="1.7"/><circle cx="12" cy="12" r="1.7"/><circle cx="12" cy="18.5" r="1.7"/></svg>
                        </button>
                        <ul class="cbov-menu" role="menu" aria-label="menu {{ $size }}">
                            <li role="none"><button type="button" role="menuitem" class="cbov-item" x-on:click="openId = null">{{ $say('First action', 'اقدام اول') }}</button></li>
                            <li role="none"><button type="button" role="menuitem" class="cbov-item" data-danger x-on:click="openId = null">{{ $say('Delete', 'حذف') }}</button></li>
                        </ul>
                    </div>
                    <small>{{ $size }}</small>
                </div>
            @endforeach
        </div>
        </div>
    </div>
</section>
