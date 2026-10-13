{{--
    icon-browser's real scenario: the library page of a design system's icon
    set — 28 Lucide-style line icons with live Persian/English name search,
    click-to-copy the exact SVG (toast confirms), size and stroke sliders,
    colour swatches, a live result count and an honest empty state.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    // 28 icons, 24×24 Lucide style — every shape a plain path.
    $icons = [
        ['en' => 'heart', 'fa' => 'قلب', 'paths' => ['M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3.5c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z']],
        ['en' => 'star', 'fa' => 'ستاره', 'paths' => ['M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2Z']],
        ['en' => 'sun', 'fa' => 'خورشید', 'paths' => ['M16 12a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z', 'M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41']],
        ['en' => 'moon', 'fa' => 'ماه', 'paths' => ['M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z']],
        ['en' => 'search', 'fa' => 'جست‌وجو', 'paths' => ['M18 11a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z', 'm16.65 16.65 4.35 4.35']],
        ['en' => 'globe', 'fa' => 'کره', 'paths' => ['M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z', 'M3 12h18', 'M12 3a13.5 13.5 0 0 1 3.5 9A13.5 13.5 0 0 1 12 21a13.5 13.5 0 0 1-3.5-9A13.5 13.5 0 0 1 12 3Z']],
        ['en' => 'zap', 'fa' => 'برق', 'paths' => ['M13 2 3 14h9l-1 8 10-12h-9l1-8Z']],
        ['en' => 'shield', 'fa' => 'سپر', 'paths' => ['M12 22s8-3.5 8-9.5V5l-8-3-8 3v7.5C4 18.5 12 22 12 22Z']],
        ['en' => 'message', 'fa' => 'پیام', 'paths' => ['M21 15a2 2 0 0 1-2 2H8l-5 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2Z']],
        ['en' => 'lock', 'fa' => 'قفل', 'paths' => ['M5 11a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2Z', 'M8 11V7a4 4 0 0 1 8 0v4']],
        ['en' => 'mail', 'fa' => 'نامه', 'paths' => ['M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z', 'm22 7-10 6L2 7']],
        ['en' => 'bell', 'fa' => 'زنگ', 'paths' => ['M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9', 'M10.3 21a1.94 1.94 0 0 0 3.4 0']],
        ['en' => 'home', 'fa' => 'خانه', 'paths' => ['m3 10 9-7 9 7v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Z', 'M9 22V12h6v10']],
        ['en' => 'user', 'fa' => 'کاربر', 'paths' => ['M20 21a8 8 0 0 0-16 0', 'M12 3a4 4 0 1 0 0 8 4 4 0 0 0 0-8Z']],
        ['en' => 'users', 'fa' => 'گروه', 'paths' => ['M22 21a6 6 0 0 0-12 0', 'M9 3.5a4 4 0 1 0 0 8 4 4 0 0 0 0-8Z', 'M16 3.1a4 4 0 0 1 0 7.8', 'M22 21a6 6 0 0 0-6-6']],
        ['en' => 'settings', 'fa' => 'تنظیمات', 'paths' => ['M4 21v-7M4 10V3M12 21v-9M12 8V3M20 21v-5M20 12V3', 'M1 14h6M9 8h6M17 16h6']],
        ['en' => 'grid', 'fa' => 'گرید', 'paths' => ['M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7Z']],
        ['en' => 'chart', 'fa' => 'نمودار', 'paths' => ['M3 3v18h18', 'M7 15v3M12 10v8M17 6v12']],
        ['en' => 'layers', 'fa' => 'لایه‌ها', 'paths' => ['m12 2 9 4.5-9 4.5L3 6.5 12 2Z', 'm3 12 9 4.5 9-4.5', 'm3 17 9 4.5 9-4.5']],
        ['en' => 'cpu', 'fa' => 'پردازنده', 'paths' => ['M6 6h12v12H6z', 'M10 10h4v4h-4z', 'M12 2v3M12 19v3M2 12h3M19 12h3']],
        ['en' => 'command', 'fa' => 'فرمان', 'paths' => ['M15 6v12a3 3 0 1 0 3-3H6a3 3 0 1 0 3 3V6a3 3 0 1 0-3 3h12a3 3 0 1 0-3-3']],
        ['en' => 'wand', 'fa' => 'جادوگر', 'paths' => ['M4 20 14 10', 'm17 3 1 2.5L20.5 6.5 18 7.5 17 10l-1-2.5L13.5 6.5 16 5.5Z']],
        ['en' => 'sparkles', 'fa' => 'درخشش', 'paths' => ['M12 3l1.9 5.7a2 2 0 0 0 1.3 1.3L21 12l-5.8 2a2 2 0 0 0-1.3 1.3L12 21l-1.9-5.7a2 2 0 0 0-1.3-1.3L3 12l5.8-2a2 2 0 0 0 1.3-1.3L12 3Z', 'M19 3v2M18 4h2M5 17v2M4 18h2']],
        ['en' => 'edit', 'fa' => 'ویرایش', 'paths' => ['M17 3a2.8 2.8 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z']],
        ['en' => 'trash', 'fa' => 'سطل زباله', 'paths' => ['M3 6h18', 'M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2', 'M6 6l1 14a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-14', 'M10 11v6M14 11v6']],
        ['en' => 'upload', 'fa' => 'بارگذاری', 'paths' => ['M12 16V4', 'm6 9 6-6 6 6', 'M5 20h14']],
        ['en' => 'image', 'fa' => 'تصویر', 'paths' => ['M3 5h18v14H3z', 'M8.5 10a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z', 'M21 15l-5-5L5 19']],
        ['en' => 'mic', 'fa' => 'میکروفون', 'paths' => ['M12 2a3 3 0 0 0-3 3v6a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z', 'M19 10v1a7 7 0 0 1-14 0v-1', 'M12 18v4']],
    ];
@endphp

<style>
    .imb-wrap {
        --imb-fg: #1c1917; --imb-muted: #78716c; --imb-border: #e7e5e4;
        --imb-bg: #ffffff; --imb-accent: #4f46e5; --imb-soft: #eef2ff;
        position: relative;
        display: grid;
        gap: .95rem;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        color: var(--imb-fg);
    }
    html[data-theme="dark"] .imb-wrap {
        --imb-fg: #fafaf9; --imb-muted: #a8a29e; --imb-border: #292524;
        --imb-bg: #1c1917; --imb-accent: #a5b4fc; --imb-soft: #26264a;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .imb-wrap {
            --imb-fg: #fafaf9; --imb-muted: #a8a29e; --imb-border: #292524;
            --imb-bg: #1c1917; --imb-accent: #a5b4fc; --imb-soft: #26264a;
        }
    }
    .imb-search {
        display: flex; align-items: center; gap: .6rem;
        padding: .65rem .9rem;
        border: 1px solid var(--imb-border); border-radius: .85rem;
        background: var(--imb-bg);
        transition: border-color .2s ease;
    }
    .imb-search:focus-within { border-color: var(--imb-accent); }
    .imb-search svg { flex: none; inline-size: 1.05rem; block-size: 1.05rem; color: var(--imb-muted);
                      fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .imb-search input { flex: 1; min-inline-size: 0; border: none; outline: none; background: transparent;
                        font: 500 .84rem/1.4 inherit; color: var(--imb-fg); }
    .imb-search input::placeholder { color: var(--imb-muted); }
    .imb-controls { display: flex; flex-wrap: wrap; align-items: center; gap: .65rem 1.3rem; }
    .imb-ctrl { display: inline-flex; align-items: center; gap: .5rem; font-size: .74rem; font-weight: 600; color: var(--imb-muted); }
    .imb-ctrl input[type="range"] { inline-size: 6.5rem; accent-color: var(--imb-accent); }
    .imb-ctrl output { min-inline-size: 2.4em; font-family: ui-monospace, monospace; color: var(--imb-fg); }
    .imb-swatches { display: inline-flex; gap: .4rem; margin-inline-start: auto; }
    .imb-swatch {
        inline-size: 1.35rem; aspect-ratio: 1; border-radius: 50%;
        border: 2px solid transparent; cursor: pointer; padding: 0;
        transition: scale .15s ease, outline-color .15s ease;
    }
    .imb-swatch:hover { scale: 1.15; }
    .imb-swatch[aria-pressed="true"] { outline: 2px solid var(--imb-accent); outline-offset: 2px; }
    .imb-count { margin: 0; font-family: ui-monospace, monospace; font-size: .72rem; color: var(--imb-muted); }
    .imb-count b { color: var(--imb-fg); }
    .imb-grid {
        display: grid;
        gap: .6rem;
        grid-template-columns: repeat(auto-fill, minmax(6.4rem, 1fr));
    }
    .imb-tile {
        display: grid; gap: .6rem; justify-items: center;
        padding: .9rem .4rem .75rem;
        border: 1px solid var(--imb-border); border-radius: .8rem;
        background: var(--imb-bg); color: inherit; cursor: copy;
        transition: border-color .15s ease, translate .15s ease;
    }
    .imb-tile:hover { border-color: var(--imb-accent); translate: 0 -2px; }
    .imb-tile:focus-visible { outline: 2px solid var(--imb-accent); outline-offset: 2px; }
    .imb-tile svg { overflow: visible; }
    .imb-tile path { fill: none; stroke-linecap: round; stroke-linejoin: round; }
    .imb-name { font-size: .7rem; font-weight: 600; color: var(--imb-muted); }
    .imb-tile:hover .imb-name { color: var(--imb-fg); }
    .imb-empty {
        display: grid; justify-items: center; gap: .6rem;
        padding: 2.4rem 1rem; text-align: center;
        border: 1px dashed var(--imb-border); border-radius: 1rem;
    }
    .imb-empty p { margin: 0; font-size: .8rem; color: var(--imb-muted); }
    .imb-clear {
        padding: .45rem 1rem; border-radius: 999px; cursor: pointer;
        font: 600 .76rem/1 inherit; color: var(--imb-accent);
        background: var(--imb-soft); border: 1px solid transparent;
    }
    .imb-clear:hover { border-color: var(--imb-accent); }
    .imb-toast {
        position: absolute; inset-block-end: 1rem; inset-inline: 0;
        justify-self: center;
        display: inline-flex; align-items: center; gap: .5rem;
        padding: .55rem 1.1rem;
        border-radius: 999px;
        background: var(--imb-fg); color: var(--imb-bg);
        font-size: .76rem; font-weight: 600;
        box-shadow: 0 8px 24px -8px rgba(0, 0, 0, .35);
        animation: imb-rise .25s ease-out;
    }
    .imb-toast svg { inline-size: .9rem; block-size: .9rem; fill: none; stroke: currentColor;
                     stroke-width: 2.2; stroke-linecap: round; stroke-linejoin: round; }
    @keyframes imb-rise { from { opacity: 0; translate: 0 8px; } to { opacity: 1; translate: 0 0; } }
    @media (prefers-reduced-motion: reduce) {
        .imb-toast { animation: none; }
        .imb-tile:hover { translate: 0 0; }
        .imb-tile, .imb-swatch:hover { transition: none; }
    }
</style>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The icon library, browsable', 'کتابخانهٔ آیکون، قابل‌مرور') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('28 Lucide-style icons with live Persian/English search over their names. Click any tile to copy its exact SVG — the toast confirms with the icon’s name — while the sliders resize and restroke the whole grid and the swatches recolour it. The result count keeps score and the empty state stays honest.', '۲۸ آیکون به سبک لوسید با جست‌وجوی زندهٔ فارسی/انگلیسی روی نام‌ها. کلیک روی هر کاشی SVG دقیقش را کپی می‌کند — تُست با نام آیکون تأیید می‌کند — اسلایدرها کل گرید را تغییر اندازه و ضخامت می‌دهند و سواچ‌ها رنگش می‌کنند. شمارنده امتیاز می‌دهد و حالت خالی صادق می‌ماند.') }}
        </p>
    </div>

    <div class="imb-wrap"
        x-data="{
            fa: @js($fa),
            all: @js($icons),
            q: '',
            size: 24,
            sw: 2,
            color: '#4f46e5',
            toast: null,
            t: null,
            get hits() {
                const needle = this.q.trim().toLowerCase();
                if (!needle) return this.all;
                return this.all.filter((i) => i.en.includes(needle) || i.fa.includes(this.q.trim()));
            },
            fmt(n) { const s = String(n); return this.fa ? s.replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]) : s; },
            svgOf(icon) {
                const body = icon.paths.map((d) => '  <path d=&quot;' + d + '&quot;/>').join('\n');
                return '<svg xmlns=&quot;http://www.w3.org/2000/svg&quot; viewBox=&quot;0 0 24 24&quot; width=&quot;' + this.size
                    + '&quot; height=&quot;' + this.size + '&quot; fill=&quot;none&quot; stroke=&quot;' + this.color
                    + '&quot; stroke-width=&quot;' + this.sw + '&quot; stroke-linecap=&quot;round&quot; stroke-linejoin=&quot;round&quot;>\n'
                    + body + '\n</svg>';
            },
            async copy(icon) {
                const svg = this.svgOf(icon);
                let ok = false;
                try { await navigator.clipboard.writeText(svg); ok = true; }
                catch (e) {
                    const ta = document.createElement('textarea');
                    ta.value = svg; ta.setAttribute('readonly', '');
                    ta.style.position = 'absolute'; ta.style.insetInlineStart = '-9999px';
                    document.body.appendChild(ta); ta.select();
                    try { ok = document.execCommand('copy'); } catch (e2) { ok = false; }
                    ta.remove();
                }
                this.say(ok
                    ? (this.fa ? 'SVG «' + icon.fa + '» کپی شد' : 'Copied the ' + icon.en + ' SVG')
                    : (this.fa ? 'کپی در این مرورگر ممکن نشد' : 'This browser refused the copy'));
            },
            say(msg) {
                this.toast = msg;
                clearTimeout(this.t);
                this.t = setTimeout(() => (this.toast = null), 1800);
            },
        }"
>

        <label class="imb-search">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M18 11a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/><path d="m16.65 16.65 4.35 4.35"/>
            </svg>
            <input type="search" x-model="q"
                :placeholder="fa ? 'جست‌وجوی نام آیکون — فارسی یا English…' : 'Search icon names — English or Persian…'"
                :aria-label="fa ? 'جست‌وجوی آیکون' : 'Search icons'">
        </label>

        <div class="imb-controls">
            <span class="imb-ctrl">
                <label for="imb-size">{{ $say('size', 'اندازه') }}</label>
                <input id="imb-size" type="range" min="16" max="44" step="2" x-model.number="size">
                <output x-text="fmt(size) + 'px'"></output>
            </span>
            <span class="imb-ctrl">
                <label for="imb-sw">{{ $say('stroke', 'ضخامت') }}</label>
                <input id="imb-sw" type="range" min="1" max="3" step="0.25" x-model.number="sw">
                <output x-text="fmt(sw)"></output>
            </span>
            <span class="imb-swatches" role="group" :aria-label="fa ? 'رنگ آیکون' : 'Icon colour'">
                <template x-for="c in ['#4f46e5', '#0ea5e9', '#ec4899', '#f59e0b', '#10b981', '#1c1917']" :key="c">
                    <button type="button" class="imb-swatch" :style="`background: ${c}`"
                            x-bind:aria-pressed="color === c ? 'true' : 'false'"
                            :aria-label="c" x-on:click="color = c"></button>
                </template>
            </span>
        </div>

        <p class="imb-count" role="status">
            <span x-text="fmt(hits.length)"></span>
            <span x-text="fa ? 'نتیجه از ' + fmt(all.length) + ' آیکون' : 'of ' + fmt(all.length) + ' icons'"></span>
            <span x-show="q.trim() !== ''">· «<span x-text="q"></span>»</span>
        </p>

        <div class="imb-grid" x-show="hits.length > 0">
            <template x-for="icon in hits" :key="icon.en">
                <button type="button" class="imb-tile"
                        x-on:click="copy(icon)"
                        :aria-label="(fa ? 'کپی SVG ' + icon.fa : 'Copy the ' + icon.en + ' SVG')">
                    <svg viewBox="0 0 24 24" aria-hidden="true"
                         :stroke="color" :stroke-width="sw"
                         :style="`inline-size: ${size}px; block-size: ${size}px`"
                         x-html="icon.paths.map((d) => '<path d=&quot;' + d + '&quot;/>').join('')"></svg>
                    <span class="imb-name" x-text="fa ? icon.fa : icon.en"></span>
                </button>
            </template>
        </div>

        <div class="imb-empty" x-show="hits.length === 0" x-cloak>
            <p x-text="fa
                ? 'چیزی برای «' + q + '» پیدا نشد — نام انگلیسی (heart) یا فارسی (قلب) را امتحان کن.'
                : 'Nothing matched “' + q + '” — try the English name (heart) or the Persian one (قلب).'"></p>
            <button type="button" class="imb-clear" x-on:click="q = ''">{{ $say('Clear the search', 'پاک کردن جست‌وجو') }}</button>
        </div>

        <div class="imb-toast" x-show="toast" x-cloak role="status">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>
            <span x-text="toast"></span>
        </div>
    </div>
</section>
