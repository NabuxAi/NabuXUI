{{--
    image-accordion's real scenario: the destination strip of a global
    travel journal — five scene panels (pure SVG artwork, prefixed gradient
    ids) in a horizontal accordion. The hovered, focused or tapped panel
    grows to its open share while the rest collapse; the open panel's
    number and title rise in from the fold and the collapsed ones carry a
    vertical running number. Arrow keys walk the panels (direction flipped
    for RTL), Home/End jump to the ends, and under reduced motion the
    share change is instant. No RAF loop — flex-grow transitions only.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (int $n): string => $fa
        ? strtr(sprintf('%02d', $n), ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹'])
        : sprintf('%02d', $n);

    // Five global destinations; each panel paints one SVG scene.
    $places = [
        ['fa' => 'کیوتو', 'en' => 'Kyoto', 'kind' => ['fa' => 'طلوع بر معبد', 'en' => 'Sunrise over the temple'], 'grad' => ['#4c1d95', '#f472b6']],
        ['fa' => 'پاتاگونیا', 'en' => 'Patagonia', 'kind' => ['fa' => 'قله‌های فیروزه‌ای', 'en' => 'Teal granite towers'], 'grad' => ['#134e4a', '#5eead4']],
        ['fa' => 'ساحل آمالفی', 'en' => 'Amalfi Coast', 'kind' => ['fa' => 'لیمو و دریای آبی', 'en' => 'Lemons and blue water'], 'grad' => ['#1d4ed8', '#fde047']],
        ['fa' => 'لاپ‌لند', 'en' => 'Lapland', 'kind' => ['fa' => 'شفق قطبی', 'en' => 'Aurora over the pines'], 'grad' => ['#052e16', '#4ade80']],
        ['fa' => 'بیگ سور', 'en' => 'Big Sur', 'kind' => ['fa' => 'غروب بر پرتگاه', 'en' => 'Cliff-line dusk'], 'grad' => ['#7c2d12', '#fbbf24']],
    ];
@endphp

<style>
    .iac-root {
        --iac-fg: #1c1917; --iac-muted: #78716c; --iac-border: #e7e5e4;
        --iac-bg: #fafaf9; --iac-accent: #4f46e5;
        --iac-font: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        display: grid; gap: 1rem; font-family: var(--iac-font); color: var(--iac-fg);
    }
    html[data-theme="dark"] .iac-root {
        --iac-fg: #fafaf9; --iac-muted: #a8a29e; --iac-border: #292524;
        --iac-bg: #1c1917; --iac-accent: #a5b4fc;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .iac-root {
            --iac-fg: #fafaf9; --iac-muted: #a8a29e; --iac-border: #292524;
            --iac-bg: #1c1917; --iac-accent: #a5b4fc;
        }
    }
    .iac-strip {
        display: flex; gap: .45rem; min-block-size: 22rem; padding: .45rem;
        border: 1px solid var(--iac-border); border-radius: 1.25rem;
        background: var(--iac-bg); overflow: clip; touch-action: pan-y;
    }
    .iac-panel {
        position: relative; flex: 1 1 0; min-inline-size: 3.1rem;
        border: 0; padding: 0; border-radius: .95rem; overflow: clip; cursor: pointer;
        background: #0b1020; outline: none;
        transition: flex-grow .55s cubic-bezier(.3, .8, .25, 1);
        display: grid; align-content: end; justify-items: start;
    }
    .iac-panel:focus-visible { outline: 2px solid var(--iac-accent); outline-offset: -2px; }
    .iac-panel[data-open="true"] { flex-grow: 4.6; }
    .iac-scene { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
    .iac-scene svg { display: block; inline-size: 100%; block-size: 100%; }
    .iac-veil {
        position: absolute; inset: 0; background: linear-gradient(to top, rgba(2, 6, 23, .82), rgba(2, 6, 23, .12) 55%, transparent);
        transition: opacity .4s ease;
    }
    .iac-panel[data-open="false"] .iac-veil { opacity: .55; }
    .iac-fold {
        position: relative; z-index: 1; display: grid; gap: .25rem; inline-size: 100%;
        padding: 1rem 1.15rem 1.1rem; color: #f8fafc; text-align: start;
    }
    .iac-no {
        font-size: .68rem; font-weight: 700; letter-spacing: .14em;
        color: #e0e7ff; font-variant-numeric: tabular-nums;
    }
    html[dir="rtl"] .iac-no { letter-spacing: 0; }
    .iac-title-wrap {
        display: grid; gap: .1rem; overflow: clip;
        opacity: 0; translate: 0 .9rem; visibility: hidden;
        transition: opacity .38s ease .12s, translate .38s cubic-bezier(.3, .8, .3, 1) .12s, visibility 0s linear .12s;
    }
    .iac-panel[data-open="true"] .iac-title-wrap {
        opacity: 1; translate: 0 0; visibility: visible;
    }
    .iac-title { font-size: clamp(1rem, 3.4cqi, 1.5rem); font-weight: 700; white-space: nowrap; }
    .iac-kind { font-size: .78rem; color: #cbd5e1; white-space: nowrap; }
    .iac-runner {
        position: absolute; z-index: 1; inset-block: 1rem; inset-inline-start: 50%;
        translate: -50% 0; writing-mode: vertical-rl;
        font-size: .72rem; font-weight: 700; letter-spacing: .18em; color: #e0e7ff;
        opacity: 1; transition: opacity .25s ease;
        font-variant-numeric: tabular-nums;
    }
    html[dir="rtl"] .iac-runner { translate: 50% 0; }
    .iac-panel[data-open="true"] .iac-runner { opacity: 0; }
    .iac-hint { margin: 0; font-size: .74rem; color: var(--iac-muted); text-align: center; }
    @media (max-width: 640px) {
        .iac-strip { min-block-size: 24rem; gap: .35rem; padding: .35rem; }
        .iac-panel { min-inline-size: 2.5rem; border-radius: .8rem; }
        .iac-fold { padding: .8rem .9rem .9rem; }
        .iac-title, .iac-kind { white-space: normal; }
    }
    @media (prefers-reduced-motion: reduce) {
        .iac-panel { transition: none; }
        .iac-title-wrap { transition: none; }
    }
</style>

<section class="pg-box iac-root" style="gap: 1rem; container-type: inline-size"
    x-data="{
        open: 1, panels: [], rtl: false,
        init() {
            this.rtl = getComputedStyle(document.documentElement).direction === 'rtl';
            this.panels = Array.from(this.$refs.strip.querySelectorAll('.iac-panel'));
        },
        openTo(i) { this.open = i; },
        step(from, delta) {
            const n = this.panels.length;
            const t = Math.min(n - 1, Math.max(0, from + delta));
            this.open = t;
            this.panels[t].focus();
        },
        onKey(e, i) {
            const dflt = this.rtl ? -1 : 1;
            if (e.key === 'ArrowRight') { e.preventDefault(); this.step(i, dflt); }
            else if (e.key === 'ArrowLeft') { e.preventDefault(); this.step(i, -dflt); }
            else if (e.key === 'Home') { e.preventDefault(); this.step(i, -i); }
            else if (e.key === 'End') { e.preventDefault(); this.step(i, this.panels.length - 1 - i); }
        },
    }">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Panels that unfold under the hand', 'پنل‌هایی که زیر دست باز می‌شوند') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Five destination scenes share one strip: the hovered, focused or tapped panel grows to its open share on a flex-grow curve while the others fold away — the open panel’s number and title rise in from the fold and the collapsed ones keep a vertical running number. Arrow keys walk the strip (flipped for RTL) and Home/End jump to the ends.', 'پنج صحنهٔ مقصد یک نوار را قسمت می‌کنند: پنلِ هاورشده یا فوکوس‌شده یا لمس‌شده روی منحنی flex-grow به سهم بازش می‌رسد و بقیه جمع می‌شوند — شماره و عنوان پنلِ باز از تاخیه بالا می‌آیند و پنل‌های جمع‌شده شمارهٔ عمودی خود را نگه می‌دارند. کلیدهای جهت‌دار نوار را می‌پیمایند (در راست‌به‌چپ وارونه) و Home/End به دو سر می‌پرند.') }}
        </p>
    </div>

    <div class="iac-strip" x-ref="strip" role="group" x-bind:aria-label="@js($say('Destinations', 'مقصد‌ها'))">
        @foreach ($places as $i => $place)
            <button type="button" class="iac-panel"
                x-bind:data-open="open === {{ $i }} ? 'true' : 'false'"
                x-bind:aria-expanded="open === {{ $i }} ? 'true' : 'false'"
                x-on:pointerenter="openTo({{ $i }})"
                x-on:click="openTo({{ $i }})"
                x-on:focus="openTo({{ $i }})"
                x-on:keydown="onKey($event, {{ $i }})">
                <span class="iac-scene" aria-hidden="true">
                    <svg viewBox="0 0 300 420" preserveAspectRatio="xMidYMid slice" focusable="false">
                        <defs>
                            <linearGradient id="iac-g{{ $i }}" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0" stop-color="{{ $place['grad'][0] }}"/>
                                <stop offset="1" stop-color="{{ $place['grad'][1] }}"/>
                            </linearGradient>
                        </defs>
                        <rect width="300" height="420" fill="url(#iac-g{{ $i }})"/>
                        @if ($i === 0)
                            {{-- Kyoto: sun over a pagoda silhouette --}}
                            <circle cx="150" cy="150" r="46" fill="#fde68a" opacity=".9"/>
                            <path d="M150 236 L96 262 L204 262 Z" fill="#3b0764"/>
                            <path d="M138 262 h24 v34 h-24 Z M118 296 h64 v34 h-64 Z M98 330 h104 v40 H98 Z" fill="#2e1065"/>
                            <path d="M0 370 h300 v50 H0 Z" fill="#1e1b4b"/>
                        @elseif ($i === 1)
                            {{-- Patagonia: teal towers with snow caps --}}
                            <path d="M0 420 L70 190 L120 300 L170 150 L240 320 L300 220 V420 Z" fill="#0f3d3a" opacity=".92"/>
                            <path d="M70 190 L88 226 L74 232 L58 222 Z" fill="#f0fdfa"/>
                            <path d="M170 150 L192 196 L176 202 L156 190 Z" fill="#f0fdfa"/>
                            <path d="M0 420 L60 330 L140 400 L220 340 L300 396 V420 Z" fill="#134e4a"/>
                        @elseif ($i === 2)
                            {{-- Amalfi: cliff coast and a lemon sun --}}
                            <circle cx="92" cy="128" r="44" fill="#fef9c3"/>
                            <circle cx="92" cy="128" r="54" fill="#fde047" opacity=".35"/>
                            <path d="M0 420 V240 L64 200 L120 250 L188 208 L240 258 L300 224 V420 Z" fill="#1e3a8a"/>
                            <path d="M0 420 V330 L90 300 L190 336 L300 308 V420 Z" fill="#172554"/>
                            <g fill="#facc15"><circle cx="228" cy="352" r="10"/><circle cx="252" cy="368" r="8"/></g>
                        @elseif ($i === 3)
                            {{-- Lapland: aurora ribbons over pines --}}
                            <path d="M20 160 Q90 60 160 130 Q220 180 290 110" fill="none" stroke="#86efac" stroke-opacity=".7" stroke-width="16" stroke-linecap="round"/>
                            <path d="M40 210 Q120 120 200 180 Q250 214 300 170" fill="none" stroke="#4ade80" stroke-opacity=".5" stroke-width="10" stroke-linecap="round"/>
                            <g fill="#052e16">
                                <path d="M60 420 L60 320 L44 320 L74 276 L104 320 L88 320 L88 420 Z"/>
                                <path d="M150 420 L150 288 L130 288 L168 232 L206 288 L186 288 L186 420 Z"/>
                                <path d="M246 420 L246 330 L230 330 L262 288 L294 330 L278 330 L278 420 Z"/>
                            </g>
                        @else
                            {{-- Big Sur: dusk cliff with a winding road --}}
                            <circle cx="212" cy="120" r="40" fill="#fed7aa"/>
                            <path d="M0 420 V260 L120 210 L210 260 L300 232 V420 Z" fill="#7c2d12"/>
                            <path d="M0 420 V340 L150 300 L300 342 V420 Z" fill="#431407"/>
                            <path d="M0 396 Q80 380 140 396 T300 392" fill="none" stroke="#fbbf24" stroke-width="5" stroke-dasharray="14 10" stroke-linecap="round"/>
                        @endif
                    </svg>
                </span>
                <span class="iac-veil" aria-hidden="true"></span>
                <span class="iac-runner" aria-hidden="true">{{ $num($i + 1) }}</span>
                <span class="iac-fold">
                    <span class="iac-title-wrap">
                        <span class="iac-no">{{ $num($i + 1) }} · {{ $say('Meridian Atlas', 'اطلس مریدین') }}</span>
                        <span class="iac-title">{{ $say($place['en'], $place['fa']) }}</span>
                        <span class="iac-kind">{{ $say($place['kind']['en'], $place['kind']['fa']) }}</span>
                    </span>
                </span>
            </button>
        @endforeach
    </div>

    <p class="iac-hint">
        {{ $say('hover, focus or tap a panel · arrows walk the strip (flipped in RTL) · Home/End jump · flex-grow .55s curve · instant under reduced motion', 'هاور، فوکوس یا لمس یک پنل · کلیدهای جهت نوار را می‌پیمایند (در RTL وارونه) · Home/End می‌پرند · منحنی flex-grow برابر ۰٫۵۵s · در حرکت کاهش‌یافته لحظه‌ای') }}
    </p>
</section>
