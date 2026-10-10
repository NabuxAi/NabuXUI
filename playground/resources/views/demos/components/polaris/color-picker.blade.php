{{--
    Polaris Color Picker in full: a saturation rectangle you drag (pointer
    capture, physical mapping so it behaves in RTL), a hue slider, an alpha
    bar over a checkerboard, and a live hex field that both reports and
    accepts colors. The variants row carries preset swatches that really
    load, and the compact panel without alpha.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    [x-cloak] { display: none !important; }
    .plcp-root {
        --plcp-surface: #FFFFFF; --plcp-raised: #F6F6F6; --plcp-text: #303030; --plcp-subdued: #616161;
        --plcp-border: #E3E3E3; --plcp-strong: #8A8A8A;
        --plcp-green: #008060; --plcp-focus: #005BD3; --plcp-critical: #D72C0D;
        font-family: 'Inter', 'Vazirmatn', sans-serif;
        display: grid; gap: 1.5rem; justify-items: center;
    }
    html[data-theme="dark"] .plcp-root {
        --plcp-surface: #202020; --plcp-raised: #2B2B2B; --plcp-text: #F1F1F1; --plcp-subdued: #B5B5B5;
        --plcp-border: #454545; --plcp-strong: #8A8A8A;
        --plcp-green: #00A97F; --plcp-critical: #FF8D75;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .plcp-root {
            --plcp-surface: #202020; --plcp-raised: #2B2B2B; --plcp-text: #F1F1F1; --plcp-subdued: #B5B5B5;
            --plcp-border: #454545; --plcp-strong: #8A8A8A;
            --plcp-green: #00A97F; --plcp-critical: #FF8D75;
        }
    }
    .plcp-panel { inline-size: min(100%, 22rem); display: grid; gap: .8rem; padding: .9rem; border: 1px solid var(--plcp-border); border-radius: 12px; background: var(--plcp-surface); box-shadow: 0 1px 4px rgba(0, 0, 0, .07); }
    .plcp-area { position: relative; aspect-ratio: 16 / 9; border-radius: 8px; touch-action: none; cursor: crosshair; background-image: linear-gradient(to top, #000, transparent), linear-gradient(to right, #fff, transparent); background-color: hsl(160 100% 50%); }
    .plcp-thumb { position: absolute; inline-size: 1.1rem; aspect-ratio: 1; border-radius: 50%; border: 2px solid #fff; box-shadow: 0 0 0 1px rgba(0, 0, 0, .25), 0 1px 4px rgba(0, 0, 0, .3); translate: -50% -50%; pointer-events: none; }
    .plcp-range { -webkit-appearance: none; appearance: none; inline-size: 100%; block-size: 13px; border-radius: 999px; background: transparent; cursor: pointer; }
    .plcp-range::-webkit-slider-thumb { -webkit-appearance: none; appearance: none; inline-size: 1.1rem; aspect-ratio: 1; border-radius: 50%; background: #fff; border: none; box-shadow: 0 0 0 1px rgba(0, 0, 0, .25), 0 1px 4px rgba(0, 0, 0, .3); }
    .plcp-range::-moz-range-thumb { inline-size: 1.1rem; aspect-ratio: 1; border-radius: 50%; background: #fff; border: none; box-shadow: 0 0 0 1px rgba(0, 0, 0, .25), 0 1px 4px rgba(0, 0, 0, .3); }
    .plcp-range:focus-visible { outline: 2px solid var(--plcp-focus); outline-offset: 2px; }
    .plcp-hue { background: linear-gradient(to right, #ff0000 0%, #ffff00 17%, #00ff00 33%, #00ffff 50%, #0000ff 67%, #ff00ff 83%, #ff0000 100%); }
    .plcp-alphawrap { position: relative; }
    .plcp-alphawrap::before { content: ''; position: absolute; inset: 0; border-radius: 999px; background: repeating-conic-gradient(#c9c9c9 0 25%, #fff 0 50%); background-size: 10px 10px; }
    .plcp-alpha { position: relative; }
    .plcp-metarow { display: flex; align-items: center; gap: .7rem; }
    .plcp-hexin { flex: 1; min-inline-size: 0; block-size: 2.2rem; padding-inline: .7rem; border: 1px solid var(--plcp-strong); border-radius: 8px; background: var(--plcp-surface); color: var(--plcp-text); font: 400 .85rem/1 ui-monospace, SFMono-Regular, Menlo, monospace; letter-spacing: .4px; direction: ltr; text-align: start; }
    .plcp-hexin[data-bad] { border-color: var(--plcp-critical); }
    .plcp-hexin:focus-visible { outline: 2px solid var(--plcp-focus); outline-offset: 1px; }
    .plcp-preview { flex: none; inline-size: 2.2rem; aspect-ratio: 1; border-radius: 8px; border: 1px solid color-mix(in srgb, var(--plcp-text) 18%, transparent); }
    .plcp-reads { display: flex; flex-wrap: wrap; gap: .25rem 1rem; font: 400 .74rem/1.4 Inter, system-ui, sans-serif; color: var(--plcp-subdued); font-variant-numeric: tabular-nums; }
    .plcp-swatches { display: flex; flex-wrap: wrap; gap: .5rem; justify-content: center; }
    .plcp-sw { inline-size: 2.1rem; aspect-ratio: 1; border-radius: 8px; border: 1px solid color-mix(in srgb, var(--plcp-text) 18%, transparent); cursor: pointer; transition: scale .12s; }
    .plcp-sw:hover { scale: 1.08; }
    .plcp-sw:focus-visible { outline: 2px solid var(--plcp-focus); outline-offset: 2px; }
    .plcp-variants { inline-size: min(100%, 40rem); display: grid; gap: 1rem; justify-items: center; }
    .plcp-vcell { display: grid; gap: .5rem; justify-items: center; }
    .plcp-vcell > small { font: 500 .72rem/1 Inter, system-ui, sans-serif; color: var(--nx-text-muted); }
    .plcp-compact { inline-size: min(100%, 13rem); display: grid; gap: .55rem; padding: .7rem; border: 1px solid var(--plcp-border); border-radius: 12px; background: var(--plcp-surface); }
    :where(.nx-js) .pg:has(.plcp-root) .nx-data-table[data-nx-reveal] tbody tr {
        opacity: 1; translate: none;
    }
    @media (max-width: 480px) {
        .pg:has(.plcp-root) .nx-data-table :is(th, td) {
            white-space: normal; padding-inline: .5rem; overflow-wrap: break-word;
        }
        .pg:has(.plcp-root) pre code { white-space: pre-wrap; overflow-wrap: anywhere; }
    }
    @media (prefers-reduced-motion: reduce) {
        .plcp-root * { transition-duration: .01ms !important; }
    }
</style>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Drag the rectangle, own the hex', 'مستطیل را بکش، هگز را در بگیر') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('Drag inside the saturation field (or paste a hex — with or without alpha) and every control follows: the hue slider, the checkerboarded alpha bar, the preview swatch and the readouts.', 'داخل میدان اشباع بکشید یا یک هگز بچسبانید — با یا بدون آلفا — تا همهٔ کنترل‌ها دنبالش بروند: اسلایدر هیو، نوار آلفای شطرنجی، پیش‌نمایش و عدد و رقم‌ها.') }}
        </p>
    </div>

    <div class="plcp-root" style="inline-size: 100%"
        x-data="{
            h: 162, s: 92, v: 58, aPct: 100,
            hexInput: '', hexFocus: false, bad: false,
            get rgb() {
                const hh = ((this.h % 360) + 360) % 360, ss = this.s / 100, vv = this.v / 100;
                const c = vv * ss, x = c * (1 - Math.abs((hh / 60) % 2 - 1)), m = vv - c;
                let t = [0, 0, 0];
                if (hh < 60) t = [c, x, 0]; else if (hh < 120) t = [x, c, 0];
                else if (hh < 180) t = [0, c, x]; else if (hh < 240) t = [0, x, c];
                else if (hh < 300) t = [x, 0, c]; else t = [c, 0, x];
                return t.map(u => Math.round((u + m) * 255));
            },
            get hueCss() { return 'hsl(' + Math.round(this.h) + ' 100% 50%)' },
            get css() { const r = this.rgb; return 'rgba(' + r.join() + ',' + (this.aPct / 100) + ')' },
            get hex() {
                const body = this.rgb.map(u => u.toString(16).padStart(2, '0')).join('').toUpperCase();
                const alpha = Math.round(this.aPct / 100 * 255).toString(16).padStart(2, '0').toUpperCase();
                return '#' + body + (this.aPct < 100 ? alpha : '');
            },
            setHex() {
                let t = this.hexInput.trim().replace(/^#/, '');
                const ok = /^[0-9a-f]{3}$/i.test(t) || /^[0-9a-f]{6}$/i.test(t) || /^[0-9a-f]{8}$/i.test(t);
                this.bad = !ok;
                if (!ok) return;
                if (t.length === 3) t = t.split('').map(ch => ch + ch).join('');
                if (t.length === 8) this.aPct = Math.round(parseInt(t.slice(6, 8), 16) / 255 * 100);
                const r = parseInt(t.slice(0, 2), 16) / 255, g = parseInt(t.slice(2, 4), 16) / 255, b = parseInt(t.slice(4, 6), 16) / 255;
                const mx = Math.max(r, g, b), mn = Math.min(r, g, b), d = mx - mn;
                this.v = mx * 100; this.s = mx ? d / mx * 100 : 0;
                if (d) this.h = ((mx === r ? ((g - b) / d) % 6 : mx === g ? (b - r) / d + 2 : (r - g) / d + 4) * 60 + 360) % 360;
            },
            pick(e) {
                const r = e.currentTarget.getBoundingClientRect();
                const cl = u => Math.min(100, Math.max(0, u));
                this.s = cl((e.clientX - r.left) / r.width * 100);
                this.v = cl(100 - (e.clientY - r.top) / r.height * 100);
            },
            down(e) { e.currentTarget.setPointerCapture(e.pointerId); this.pick(e) },
            load(c) { this.hexInput = c; this.setHex() },
            fd(x) { return {{ $fa ? 'true' : 'false' }} ? String(x).replace(/[0-9]/g, d => '۰۱۲۳۴۵۶۷۸۹'[+d]) : String(x) },
        }" x-effect="if (!hexFocus) hexInput = hex">
        <div class="plcp-panel">
            <div class="plcp-area" x-bind:style="'background-color: ' + hueCss" x-on:pointerdown="down($event)" x-on:pointermove="$event.currentTarget.hasPointerCapture && $event.currentTarget.hasPointerCapture($event.pointerId) && pick($event)" role="slider" aria-label="{{ $say('Saturation and brightness', 'اشباع و روشنایی') }}" x-bind:aria-valuetext="'hsl ' + Math.round(h) + ', s ' + Math.round(s) + ', v ' + Math.round(v)">
                <span class="plcp-thumb" x-bind:style="'left: ' + s + '%; top: ' + (100 - v) + '%; background: ' + hueCss" aria-hidden="true"></span>
            </div>
            <input type="range" class="plcp-range plcp-hue" dir="ltr" min="0" max="360" step="1" x-model.number="h" aria-label="{{ $say('Hue', 'هیو') }}">
            <div class="plcp-alphawrap">
                <input type="range" class="plcp-range plcp-alpha" dir="ltr" min="0" max="100" step="1" x-model.number="aPct" x-bind:style="'background-image: linear-gradient(to right, rgba(' + rgb.join() + ',0), rgba(' + rgb.join() + ',1))'" aria-label="{{ $say('Opacity', 'شفافیت') }}">
            </div>
            <div class="plcp-metarow">
                <input type="text" class="plcp-hexin" x-model="hexInput" x-on:focus="hexFocus = true" x-on:blur="hexFocus = false; setHex()" x-on:input="setHex()" x-bind:data-bad="bad ? '' : null" spellcheck="false" aria-label="{{ $say('Hex color', 'رنگ هگز') }}">
                <span class="plcp-preview" x-bind:style="'background: ' + css" aria-hidden="true"></span>
            </div>
            <p class="plcp-reads" style="margin: 0" x-text="'{{ $say('Saturation', 'اشباع') }} ' + fd(Math.round(s)) + '{{ $say('%', '٪') }} · {{ $say('Brightness', 'روشنایی') }} ' + fd(Math.round(v)) + '{{ $say('%', '٪') }} · {{ $say('Opacity', 'شفافیت') }} ' + fd(Math.round(aPct)) + '{{ $say('%', '٪') }}'"></p>
        </div>
    </div>
</section>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('Preset swatches & the compact panel', 'سواچ‌های آماده و پنل جمع‌ونرم') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('The swatches really load into the picker above; the compact panel drops the alpha bar for tight variant editors.', 'سواچ‌ها واقعاً در انتخابگر بالا بارگذاری می‌شوند؛ پنل جمع‌ونرم برای ادیتورهای تنگ واریانت، نوار آلفا را حذف می‌کند.') }}
        </p>
    </div>
    <div class="plcp-root plcp-variants"
        x-data="{
            h: 162, s: 92, v: 58,
            swatches: ['#008060', '#1A1A1A', '#2C6ECB', '#D72C0D', '#FFC96B', '#7E22CE', '#B45309', '#E3E3E3'],
            get rgb() {
                const hh = ((this.h % 360) + 360) % 360, ss = this.s / 100, vv = this.v / 100;
                const c = vv * ss, x = c * (1 - Math.abs((hh / 60) % 2 - 1)), m = vv - c;
                let t = [0, 0, 0];
                if (hh < 60) t = [c, x, 0]; else if (hh < 120) t = [x, c, 0];
                else if (hh < 180) t = [0, c, x]; else if (hh < 240) t = [0, x, c];
                else if (hh < 300) t = [x, 0, c]; else t = [c, 0, x];
                return t.map(u => Math.round((u + m) * 255));
            },
            get hex() { return '#' + this.rgb.map(u => u.toString(16).padStart(2, '0')).join('').toUpperCase() },
            load(c) {
                const t = c.replace(/^#/, '');
                const r = parseInt(t.slice(0, 2), 16) / 255, g = parseInt(t.slice(2, 4), 16) / 255, b = parseInt(t.slice(4, 6), 16) / 255;
                const mx = Math.max(r, g, b), mn = Math.min(r, g, b), d = mx - mn;
                this.v = mx * 100; this.s = mx ? d / mx * 100 : 0;
                if (d) this.h = ((mx === r ? ((g - b) / d) % 6 : mx === g ? (b - r) / d + 2 : (r - g) / d + 4) * 60 + 360) % 360;
            },
        }">
        <div class="plcp-vcell">
            <div class="plcp-swatches">
                <template x-for="c in swatches" :key="c">
                    <button type="button" class="plcp-sw" x-bind:style="'background: ' + c" x-on:click="load(c)" x-bind:aria-label="'{{ $say('Load color', 'بارگذاری رنگ') }} ' + c"></button>
                </template>
            </div>
            <small>presets</small>
        </div>
        <div class="plcp-vcell">
            <div class="plcp-compact">
                <span style="display: block; aspect-ratio: 16 / 6; border-radius: 8px" x-bind:style="'background: rgb(' + rgb.join() + ')'" aria-hidden="true"></span>
                <span style="font: 500 .8rem/1 ui-monospace, SFMono-Regular, Menlo, monospace; direction: ltr; text-align: start" x-text="hex">#00A97F</span>
            </div>
            <small>no alpha</small>
        </div>
    </div>
</section>
