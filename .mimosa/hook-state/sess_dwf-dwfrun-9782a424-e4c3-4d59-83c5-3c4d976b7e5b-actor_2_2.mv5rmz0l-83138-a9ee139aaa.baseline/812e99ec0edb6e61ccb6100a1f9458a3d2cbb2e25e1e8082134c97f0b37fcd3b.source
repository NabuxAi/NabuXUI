{{--
    draw-entrance's real scenario: the icon grid of a design-system landing
    page — twelve Lucide-style line icons (uniform stroke, round joins) that
    enter blank and ink themselves stroke by stroke with a stepped cadence
    the moment the grid scrolls into view; hovering a tile replays just that
    icon and the "draw them all again" switch re-inks the whole set.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    // Lucide-style: 24×24, stroke 2, round caps and joins — every shape a
    // path so the ink animation treats circles and lines the same way.
    $icons = [
        ['fa' => 'قلب', 'en' => 'Heart', 'paths' => [
            'M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3.5c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z',
        ]],
        ['fa' => 'زنگ', 'en' => 'Bell', 'paths' => [
            'M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9',
            'M10.3 21a1.94 1.94 0 0 0 3.4 0',
        ]],
        ['fa' => 'ستاره', 'en' => 'Star', 'paths' => [
            'M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2Z',
        ]],
        ['fa' => 'خورشید', 'en' => 'Sun', 'paths' => [
            'M16 12a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z',
            'M12 2v2', 'M12 20v2', 'M4.93 4.93l1.41 1.41', 'M17.66 17.66l1.41 1.41',
            'M2 12h2', 'M20 12h2', 'M4.93 19.07l1.41-1.41', 'M17.66 6.34l1.41-1.41',
        ]],
        ['fa' => 'ماه', 'en' => 'Moon', 'paths' => [
            'M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z',
        ]],
        ['fa' => 'جست‌وجو', 'en' => 'Search', 'paths' => [
            'M18 11a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z',
            'm16.65 16.65 4.35 4.35',
        ]],
        ['fa' => 'کره', 'en' => 'Globe', 'paths' => [
            'M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
            'M3 12h18',
            'M12 3a13.5 13.5 0 0 1 3.5 9A13.5 13.5 0 0 1 12 21a13.5 13.5 0 0 1-3.5-9A13.5 13.5 0 0 1 12 3Z',
        ]],
        ['fa' => 'برق', 'en' => 'Zap', 'paths' => [
            'M13 2 3 14h9l-1 8 10-12h-9l1-8Z',
        ]],
        ['fa' => 'سپر', 'en' => 'Shield', 'paths' => [
            'M12 22s8-3.5 8-9.5V5l-8-3-8 3v7.5C4 18.5 12 22 12 22Z',
            'm9 12 2 2 4-4',
        ]],
        ['fa' => 'پیام', 'en' => 'Message', 'paths' => [
            'M21 15a2 2 0 0 1-2 2H8l-5 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2Z',
            'M8 9h8', 'M8 13h5',
        ]],
        ['fa' => 'قفل', 'en' => 'Lock', 'paths' => [
            'M5 11a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2Z',
            'M8 11V7a4 4 0 0 1 8 0v4',
        ]],
        ['fa' => 'درخشش', 'en' => 'Sparkles', 'paths' => [
            'M12 3l1.9 5.7a2 2 0 0 0 1.3 1.3L21 12l-5.8 2a2 2 0 0 0-1.3 1.3L12 21l-1.9-5.7a2 2 0 0 0-1.3-1.3L3 12l5.8-2a2 2 0 0 0 1.3-1.3L12 3Z',
            'M19 3v2', 'M18 4h2', 'M5 17v2', 'M4 18h2',
        ]],
    ];
@endphp

<style>
    .imd-grid {
        --imd-fg: #1c1917; --imd-muted: #78716c; --imd-border: #e7e5e4;
        --imd-bg: #ffffff; --imd-accent: #4f46e5;
        display: grid;
        gap: .75rem;
        margin: 0;
        padding: 0;
        list-style: none;
        grid-template-columns: repeat(auto-fill, minmax(7.25rem, 1fr));
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        color: var(--imd-fg);
    }
    html[data-theme="dark"] .imd-grid {
        --imd-fg: #fafaf9; --imd-muted: #a8a29e; --imd-border: #292524;
        --imd-bg: #1c1917; --imd-accent: #a5b4fc;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .imd-grid {
            --imd-fg: #fafaf9; --imd-muted: #a8a29e; --imd-border: #292524;
            --imd-bg: #1c1917; --imd-accent: #a5b4fc;
        }
    }
    .imd-tile {
        display: grid;
        gap: .55rem;
        justify-items: center;
        padding: 1.1rem .5rem .9rem;
        border: 1px solid var(--imd-border);
        border-radius: .9rem;
        background: var(--imd-bg);
        transition: border-color .2s ease, translate .2s ease;
    }
    .imd-tile:hover { border-color: var(--imd-accent); translate: 0 -2px; }
    .imd-tile svg { inline-size: 2.4rem; block-size: 2.4rem; }
    .imd-tile path {
        fill: none;
        stroke: currentColor;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
        stroke-dasharray: 1;
        stroke-dashoffset: 1;
        transition: stroke-dashoffset .55s ease var(--d, 0s);
    }
    .imd-tile[data-drawn="true"] path { stroke-dashoffset: 0; }
    .imd-tile:not([data-drawn="true"]) path { transition: none; }
    .imd-name { font-size: .78rem; font-weight: 600; color: var(--imd-muted); }
    .imd-tile[data-drawn="true"] .imd-name { color: var(--imd-fg); }
    .imd-foot {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: .75rem 1.25rem;
        justify-content: space-between;
    }
    .imd-again {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        padding: .55rem 1.1rem;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        font-size: .82rem;
        font-weight: 600;
        color: var(--imd-fg, #1c1917);
        background: var(--imd-bg, #fff);
        border: 1px solid var(--imd-border, #e7e5e4);
        border-radius: 999px;
        cursor: pointer;
        transition: border-color .15s ease, color .15s ease;
    }
    .imd-again:hover { border-color: var(--imd-accent, #4f46e5); color: var(--imd-accent, #4f46e5); }
    .imd-again:focus-visible { outline: 2px solid var(--imd-accent, #4f46e5); outline-offset: 2px; }
    .imd-again svg { inline-size: .9rem; block-size: .9rem; stroke: currentColor; stroke-width: 2;
                     fill: none; stroke-linecap: round; stroke-linejoin: round; }
    .imd-hint { margin: 0; font-family: ui-monospace, monospace; font-size: .72rem; color: var(--imd-muted, #78716c); }
    @media (prefers-reduced-motion: reduce) {
        .imd-tile path { transition: none; stroke-dashoffset: 0; }
        .imd-tile, .imd-tile:hover { transition: none; translate: 0 0; }
    }
</style>

<section class="pg-box" style="gap: 1.25rem"
    x-data="{
        io: null,
        redraw() {
            window.dispatchEvent(new CustomEvent('imd-erase'));
            requestAnimationFrame(() => requestAnimationFrame(
                () => window.dispatchEvent(new CustomEvent('imd-draw'))));
        },
        init() {
            this.io = new IntersectionObserver((entries) => entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    window.dispatchEvent(new CustomEvent('imd-draw'));
                    this.io.disconnect();
                }
            }), { threshold: 0.2 });
            this.io.observe(this.$refs.grid);
        },
        destroy() { if (this.io) this.io.disconnect(); },
    }">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Icons that ink themselves in', 'آیکون‌هایی که خودشان را می‌کشند') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Twelve Lucide-style line icons — a uniform 2px stroke with round joins — enter blank and draw themselves stroke by stroke as the grid reaches view; the pen hops from path to path on a stepped cadence. Hover any tile to replay just that icon.', 'دوازده آیکون خطی به سبک لوسید — ضخامت یکنواخت ۲ پیکسل با گوشه‌های گرد — خاموش وارد می‌شوند و با رسیدن گرید به دید، خط‌به‌خط خودشان را می‌کشند؛ قلم با تناوب پله‌ای از مسیری به مسیر بعد می‌جهد. هاور روی هر کاشی فقط همان آیکون را دوباره می‌کشد.') }}
        </p>
    </div>

    <ul class="imd-grid" x-ref="grid">
        @foreach ($icons as $i => $icon)
            <li class="imd-tile"
                x-data="{
                    d: false,
                    replay() {
                        if (!this.d) return;
                        this.d = false;
                        requestAnimationFrame(() => requestAnimationFrame(() => (this.d = true)));
                    },
                }"
                x-bind:data-drawn="d"
                @imd-draw.window="d = true"
                @imd-erase.window="d = false"
                @pointerenter="replay()">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    @foreach ($icon['paths'] as $p => $d)
                        <path pathLength="1" d="{{ $d }}" style="--d: {{ $i * 60 + $p * 130 }}ms" />
                    @endforeach
                </svg>
                <span class="imd-name">{{ $say($icon['en'], $icon['fa']) }}</span>
            </li>
        @endforeach
    </ul>

    <div class="imd-foot">
        <button type="button" class="imd-again" x-on:click="redraw()">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>
            {{ $say('Draw them all again', 'همه را دوباره بکش') }}
        </button>
        <p class="imd-hint">{{ $say('stroke-dasharray: 1 · pathLength normalized per stroke', 'stroke-dasharray: 1 · pathLength نرمال‌شده برای هر خط') }}</p>
    </div>
</section>
