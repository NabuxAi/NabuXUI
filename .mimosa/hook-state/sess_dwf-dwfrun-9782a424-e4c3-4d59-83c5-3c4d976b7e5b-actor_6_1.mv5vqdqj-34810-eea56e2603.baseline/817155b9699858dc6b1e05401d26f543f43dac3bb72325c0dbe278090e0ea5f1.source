{{--
    extrude-3d's real scenarios: the badge headline of a developer conference
    extruded by a stacked offset shadow, tilting under the pointer (and with
    the arrow keys) inside a perspective stage. The depth slider rebuilds the
    shadow string from 3 to 12 steps; the dark mirror turns the steps neon
    through two CSS variables the stack points at.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<style>
    .xtd-root {
        --xtd-bg: #ffffff; --xtd-ink: #1c1917; --xtd-muted: #78716c;
        --xtd-border: #e7e5e4; --xtd-stage: #fafaf9; --xtd-card: #fafaf9;
        --xtd-face: #7c3aed; --xtd-shade: #a8a29e; --xtd-glow: rgb(124 58 237 / .25);
        display: grid; gap: 2rem;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        color: var(--xtd-ink);
    }
    html[data-theme="dark"] .xtd-root {
        --xtd-bg: #171514; --xtd-ink: #fafaf9; --xtd-muted: #a8a29e;
        --xtd-border: #292524; --xtd-stage: #1c1917; --xtd-card: #1c1917;
        --xtd-face: #c4b5fd; --xtd-shade: #6d28d9; --xtd-glow: rgb(167 139 250 / .55);
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .xtd-root {
            --xtd-bg: #171514; --xtd-ink: #fafaf9; --xtd-muted: #a8a29e;
            --xtd-border: #292524; --xtd-stage: #1c1917; --xtd-card: #1c1917;
            --xtd-face: #c4b5fd; --xtd-shade: #6d28d9; --xtd-glow: rgb(167 139 250 / .55);
        }
    }
    .xtd-stage {
        display: grid; gap: 1.1rem; justify-items: center; text-align: center;
        padding: clamp(1.75rem, 5vw, 3.25rem) 1.25rem;
        border: 1px solid var(--xtd-border); border-radius: 1.25rem;
        background:
            radial-gradient(70% 90% at 50% 110%, color-mix(in oklab, var(--xtd-shade) 16%, transparent), transparent 70%),
            var(--xtd-stage);
        perspective: 900px; touch-action: none; cursor: crosshair; overflow: clip;
    }
    .xtd-stage:focus-visible { outline: 2px solid var(--xtd-face); outline-offset: 3px; }
    .xtd-kicker {
        margin: 0; padding: .3rem .85rem; border: 1px solid var(--xtd-border); border-radius: 999px;
        font-size: .74rem; font-weight: 600; color: var(--xtd-muted);
    }
    .xtd-title {
        margin: 0; font-weight: 900; letter-spacing: .01em; line-height: 1.2;
        font-size: clamp(2.3rem, 11vw, 4.75rem); color: var(--xtd-face);
        transition: transform .12s ease-out; overflow-wrap: anywhere;
        transform: perspective(900px);
    }
    .xtd-sub { margin: 0; font-size: .8rem; color: var(--xtd-muted); }
    .xtd-meta { display: flex; flex-wrap: wrap; justify-content: center; gap: .4rem 1rem; margin: 0; font-size: .78rem; color: var(--xtd-muted); }
    .xtd-ctl {
        display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: .8rem 1.4rem;
        padding: 1rem; border: 1px solid var(--xtd-border); border-radius: 1.1rem; background: var(--xtd-card);
    }
    .xtd-slider { display: inline-grid; gap: .3rem; font-size: .78rem; font-weight: 600; color: var(--xtd-muted); }
    .xtd-slider input { inline-size: min(10rem, 56vw); accent-color: var(--xtd-face); }
    .xtd-readout {
        font-size: .74rem; font-weight: 600; color: var(--xtd-muted);
        font-variant-numeric: tabular-nums; padding: .35rem .8rem;
        border-radius: 999px; background: color-mix(in oklab, var(--xtd-ink) 6%, transparent);
    }
    .xtd-readout b { color: var(--xtd-ink); }
    .xtd-hint { margin: 0; font-size: .76rem; color: var(--xtd-muted); }
    @media (prefers-reduced-motion: reduce) {
        .xtd-title { transition: none; }
    }
</style>

<div class="xtd-root"
    x-data="{
        fa: @js($fa),
        depth: 6, rx: 0, ry: 0,
        reduced: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
        fmt(n) {
            const s = String(Math.round(n * 10) / 10);
            return this.fa ? s.replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]).replace('-', '−').replace('.', '٫') : s;
        },
        get shadow() {
            const layers = Array.from({ length: this.depth }, (_, i) => (i + 1) + 'px ' + (i + 1) + 'px 0 var(--xtd-shade)');
            layers.push(this.depth + 'px ' + this.depth + 'px 16px var(--xtd-glow)');
            return layers.join(', ');
        },
        get transform() {
            return 'perspective(900px) rotateX(' + this.rx + 'deg) rotateY(' + this.ry + 'deg)';
        },
        move(e) {
            if (this.reduced) return;
            const r = e.currentTarget.getBoundingClientRect();
            this.ry = ((e.clientX - r.left) / r.width - .5) * 32;
            this.rx = -((e.clientY - r.top) / r.height - .5) * 24;
        },
        nudge(e) {
            const step = e.shiftKey ? 8 : 4;
            if (e.key === 'ArrowUp') { this.rx += step; e.preventDefault(); }
            else if (e.key === 'ArrowDown') { this.rx -= step; e.preventDefault(); }
            else if (e.key === 'ArrowLeft') { this.ry -= step; e.preventDefault(); }
            else if (e.key === 'ArrowRight') { this.ry += step; e.preventDefault(); }
        },
        reset() { this.rx = 0; this.ry = 0; },
    }">
    <section class="pg-box" style="gap: 1.25rem">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Type that stands off the page', 'حروفچینی که از صفحه بلند می‌شود') }}</h3>
            <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
                {{ $say('Every shadow layer sits one pixel further out — the stack is rebuilt from the depth slider, and each layer points at a CSS variable so the dark mirror repaints the steps neon without touching the string. Drag the pointer across the badge, or focus it and use the arrow keys.', 'هر لایهٔ سایه یک پیکسل جلوتر می‌نشیند — پشته با اسلایدر عمق دوباره ساخته می‌شود و هر لایه به یک متغیر CSS اشاره می‌کند تا آینهٔ تیره بی‌آنکه رشته را دست بزند، پله‌ها را نئونی کند. نشانگر را روی نشان بکشید، یا آن را فوکوس کنید و از فلش‌ها استفاده کنید.') }}
            </p>
        </div>

        <div class="xtd-stage"
            x-on:pointermove="move($event)"
            x-on:pointerleave="reset()"
            x-on:keydown="nudge($event)"
            tabindex="0"
            role="img"
            aria-label="{{ $say('NEXUS 2026 — the developer conference headline, extruded in 3D', 'نکسوس ۲۰۲۶ — تیتر کنفرانس توسعه‌دهندگان، برجسته در سه بعد') }}">
            <p class="xtd-kicker">{{ $say('The developer conference · 5th year', 'کنفرانس توسعه‌دهندگان · پنجمین سال') }}</p>
            <h4 class="xtd-title" x-bind:style="`text-shadow: ${shadow}; transform: ${transform}`">
                {{ $say('NEXUS 2026', 'نکسوس ۲۰۲۶') }}
            </h4>
            <p class="xtd-meta">
                <span>{{ $say('Singapore · March 4–6', 'سنگاپور · ۴ تا ۶ مارس') }}</span>
                <span>{{ $say('Early bird ends Jan 30', 'ثبت‌نام زودهنگام تا ۳۰ ژانویه') }}</span>
            </p>
        </div>

        <div class="xtd-ctl">
            <label class="xtd-slider">
                <span>{{ $say('Depth', 'عمق') }} · <output x-text="fmt(depth)"></output></span>
                <input type="range" min="3" max="12" step="1" x-model.number="depth">
            </label>
            <span class="xtd-readout" role="status">
                {{ $say('tilt', 'تیلت') }} <b x-text="fmt(rx) + '° / ' + fmt(ry) + '°'"></b>
            </span>
        </div>
        <p class="xtd-hint" style="text-align: center">
            {{ $say('Persian carries the same stack — the extrusion never splits letters, it only paints behind them.', 'فارسی همان پشته را حمل می‌کند — برجسته‌سازی هرگز حروف را نمی‌شکند، فقط پشتشان رنگ می‌کند.') }}
        </p>
    </section>
</div>
