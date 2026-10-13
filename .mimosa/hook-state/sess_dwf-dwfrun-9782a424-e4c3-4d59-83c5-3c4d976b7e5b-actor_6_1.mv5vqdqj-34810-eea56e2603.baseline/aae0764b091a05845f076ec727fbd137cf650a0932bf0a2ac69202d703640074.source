{{--
    flip-cube's real scenario: the feature rail of the "Polaris Trail"
    outdoor watch — six selling points folded into one true CSS-3D cube
    (pure perspective + preserve-3d, no WebGL, no library): four side
    faces ring the Y axis and the top/bottom faces cap it on X. Drag
    spins and tilts the cube; on release the finger's velocity is
    projected a few frames ahead and the cube springs onto the nearest
    90° resting angle on both axes, so all six faces are reachable.
    The face facing the camera is highlighted, echoed by the dot rail
    and a status readout; buttons and the arrow keys walk face by face
    (up/down flip to the caps), each dot jumps by the shortest path.
    Transitions are interaction-driven only, so there is no RAF loop to
    park; the IntersectionObserver just plays the entrance once, and
    under reduced motion everything snaps instantly.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;

    // The watch's six selling points — one per face; 0–3 ring the sides,
    // 4 caps the top, 5 caps the bottom.
    $faces = [
        ['icon' => 'sun', 'fa' => 'نمایشگر آمولد', 'en' => 'AMOLED display', 'spec-fa' => '۲۰۰۰ نیت · همیشه‌روشن', 'spec-en' => '2,000 nits · always-on', 't' => 'rotateY(0deg)', 'paths' => [
            'M16 12a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z',
            'M12 2v2', 'M12 20v2', 'M4.93 4.93l1.41 1.41', 'M17.66 17.66l1.41 1.41',
            'M2 12h2', 'M20 12h2', 'M4.93 19.07l1.41-1.41', 'M17.66 6.34l1.41-1.41',
        ]],
        ['icon' => 'zap', 'fa' => 'باتری ۲۱ روزه', 'en' => '21-day battery', 'spec-fa' => 'شارژ خورشیدی در کمپ', 'spec-en' => 'Solar top-up on the trail', 't' => 'rotateY(90deg)', 'paths' => [
            'M13 2 3 14h9l-1 8 10-12h-9l1-8Z',
        ]],
        ['icon' => 'globe', 'fa' => 'جی‌پی‌اس دوباند', 'en' => 'Dual-band GPS', 'spec-fa' => 'نقشه‌های آفلاین قله‌ها', 'spec-en' => 'Offline summit maps', 't' => 'rotateY(180deg)', 'paths' => [
            'M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
            'M3 12h18',
            'M12 3a13.5 13.5 0 0 1 3.5 9A13.5 13.5 0 0 1 12 21a13.5 13.5 0 0 1-3.5-9A13.5 13.5 0 0 1 12 3Z',
        ]],
        ['icon' => 'layers', 'fa' => 'تیتانیوم درجه ۵', 'en' => 'Grade-5 titanium', 'spec-fa' => 'وزن ۴۲ گرم روی مچ', 'spec-en' => '42 g on the wrist', 't' => 'rotateY(270deg)', 'paths' => [
            'm12 2 9 5-9 5-9-5 9-5Z', 'm3 12 9 5 9-5', 'm3 17 9 5 9-5',
        ]],
        ['icon' => 'star', 'fa' => 'شیشهٔ یاقوت‌کبود', 'en' => 'Sapphire glass', 'spec-fa' => 'ضدخش، حتی روی سنگ', 'spec-en' => 'Scratch-proof on rock', 't' => 'rotateX(90deg)', 'paths' => [
            'M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2Z',
        ]],
        ['icon' => 'shield', 'fa' => 'مقاومت آب ۱۰۰ متر', 'en' => '100 m water rating', 'spec-fa' => 'استاندارد ISO 6425', 'spec-en' => 'ISO 6425 diver test', 't' => 'rotateX(-90deg)', 'paths' => [
            'M12 22s8-3.5 8-9.5V5l-8-3-8 3v7.5C4 18.5 12 22 12 22Z', 'm9 12 2 2 4-4',
        ]],
    ];

    // A locale-resolved copy for the Alpine dock readout.
    $facesJs = array_map(fn (array $f): array => [
        'label' => $say($f['en'], $f['fa']),
        'spec' => $say($f['spec-en'], $f['spec-fa']),
    ], $faces);
@endphp

<style>
    .d3fc-root {
        --d3fc-fg: #1c1917; --d3fc-muted: #78716c; --d3fc-border: #e7e5e4;
        --d3fc-card: #ffffff; --d3fc-accent: #4f46e5; --d3fc-accent-soft: #c7d2fe;
        --d3fc-stage-a: #fafaf9; --d3fc-stage-b: #f0eeec; --d3fc-s: min(56vw, 9.5rem);
        --d3fc-font: 'Inter', 'Vazirmatn', ui-sans-serif, system-ui, sans-serif;
        display: grid; gap: .9rem; font-family: var(--d3fc-font); color: var(--d3fc-fg);
    }
    html[data-theme="dark"] .d3fc-root {
        --d3fc-fg: #fafaf9; --d3fc-muted: #a8a29e; --d3fc-border: #292524;
        --d3fc-card: #1c1917; --d3fc-accent: #a5b4fc; --d3fc-accent-soft: #312e5f;
        --d3fc-stage-a: #16130f; --d3fc-stage-b: #211d19;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .d3fc-root {
            --d3fc-fg: #fafaf9; --d3fc-muted: #a8a29e; --d3fc-border: #292524;
            --d3fc-card: #1c1917; --d3fc-accent: #a5b4fc; --d3fc-accent-soft: #312e5f;
            --d3fc-stage-a: #16130f; --d3fc-stage-b: #211d19;
        }
    }
    .d3fc-stage {
        position: relative; display: grid; place-items: center;
        inline-size: 100%; aspect-ratio: 4 / 3; max-inline-size: 26rem; margin-inline: auto;
        border: 1px solid var(--d3fc-border); border-radius: 1.25rem;
        background: radial-gradient(120% 100% at 50% 0%, var(--d3fc-stage-a), var(--d3fc-stage-b) 72%);
        perspective: 1000px; overflow: clip; cursor: grab; user-select: none;
        -webkit-user-select: none; touch-action: none;
    }
    .d3fc-stage:active { cursor: grabbing; }
    .d3fc-stage:focus-visible { outline: 2px solid var(--d3fc-accent); outline-offset: 2px; }
    .d3fc-cube {
        position: relative; inline-size: var(--d3fc-s); block-size: var(--d3fc-s);
        transform-style: preserve-3d;
        transition: transform .55s cubic-bezier(.22, 1, .36, 1);
    }
    .d3fc-root[data-dragging="true"] .d3fc-cube { transition: none; }
    .d3fc-face {
        position: absolute; inset: 0; display: grid; place-content: center; justify-items: center;
        gap: .45rem; padding: .8rem; text-align: center; border-radius: .9rem;
        background: var(--d3fc-card); border: 1px solid var(--d3fc-border);
        backface-visibility: hidden; -webkit-backface-visibility: hidden;
        transform: var(--ft) translateZ(calc(var(--d3fc-s) / 2));
        transition: border-color .25s ease, box-shadow .25s ease;
    }
    .d3fc-face svg { inline-size: 1.7rem; block-size: 1.7rem; stroke: var(--d3fc-muted);
                     fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
                     transition: stroke .25s ease; }
    .d3fc-face b { font-size: clamp(.72rem, 3.2vw, .88rem); line-height: 1.4; }
    .d3fc-face small { font-size: .64rem; line-height: 1.5; color: var(--d3fc-muted); }
    .d3fc-face[data-on="true"] {
        border-color: var(--d3fc-accent);
        box-shadow: 0 0 0 2px var(--d3fc-accent-soft), 0 18px 40px -24px var(--d3fc-accent);
    }
    .d3fc-face[data-on="true"] svg { stroke: var(--d3fc-accent); }
    .d3fc-cap {
        position: absolute; inset-block-start: .8rem; inset-inline-start: .8rem; z-index: 1;
        padding: .32rem .75rem; border-radius: 999px; pointer-events: none;
        background: color-mix(in srgb, var(--d3fc-card) 78%, transparent);
        border: 1px solid var(--d3fc-border); color: var(--d3fc-fg);
        font-size: .74rem; backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
    }
    .d3fc-dock {
        display: flex; flex-wrap: wrap; align-items: center; justify-content: center;
        gap: .5rem .6rem; max-inline-size: 26rem; margin-inline: auto;
    }
    .d3fc-btn {
        display: inline-flex; align-items: center; gap: .4rem; padding: .5rem 1rem;
        border-radius: 999px; border: 1px solid var(--d3fc-border); background: var(--d3fc-card);
        color: var(--d3fc-fg); font: 600 .8rem var(--d3fc-font); cursor: pointer;
        transition: border-color .15s ease, color .15s ease;
    }
    .d3fc-btn:hover { border-color: var(--d3fc-accent); color: var(--d3fc-accent); }
    .d3fc-btn:focus-visible { outline: 2px solid var(--d3fc-accent); outline-offset: 2px; }
    .d3fc-btn svg { inline-size: .95rem; block-size: .95rem; stroke: currentColor; stroke-width: 2;
                    fill: none; stroke-linecap: round; stroke-linejoin: round; }
    html[dir="rtl"] .d3fc-btn svg { scale: -1 1; }
    .d3fc-dots { display: inline-flex; align-items: center; gap: .4rem; }
    .d3fc-dot {
        inline-size: .58rem; block-size: .58rem; padding: 0; border-radius: 50%;
        border: 1px solid var(--d3fc-muted); background: transparent; cursor: pointer;
        transition: background-color .2s ease, border-color .2s ease, scale .2s ease;
    }
    .d3fc-dot:hover { border-color: var(--d3fc-accent); }
    .d3fc-dot:focus-visible { outline: 2px solid var(--d3fc-accent); outline-offset: 2px; }
    .d3fc-dot[aria-current="true"] { background: var(--d3fc-accent); border-color: var(--d3fc-accent); scale: 1.25; }
    .d3fc-status {
        font-size: .74rem; color: var(--d3fc-muted); font-variant-numeric: tabular-nums;
        white-space: nowrap;
    }
    .d3fc-hint { margin: 0; text-align: center; font-size: .74rem; color: var(--d3fc-muted); }
    @media (max-width: 430px) {
        .d3fc-root { --d3fc-s: min(58vw, 8.75rem); }
        .d3fc-face { padding: .7rem; gap: .3rem; }
        .d3fc-face svg { inline-size: 1.4rem; block-size: 1.4rem; }
    }
    @media (prefers-reduced-motion: reduce) {
        .d3fc-cube, .d3fc-face, .d3fc-dot, .d3fc-btn { transition: none; }
    }
</style>

<section class="pg-box" style="gap: 1rem"
    x-data="{
        spin: 96, tilt: -12, dragging: false, entered: false,
        px: 0, py: 0, vx: 0, io: null,
        faces: @js($facesJs),
        fa: @js($fa),
        init() {
            // One-time entrance: the cube swings to its rest angle on first view.
            this.io = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting) {
                    this.entered = true;
                    this.spin = -24;
                    this.io.disconnect();
                }
            }, { threshold: 0.35 });
            this.io.observe(this.$refs.stage);
        },
        start(e) {
            this.$refs.stage.setPointerCapture(e.pointerId);
            this.dragging = true; this.px = e.clientX; this.py = e.clientY;
            this.vx = 0;
        },
        move(e) {
            if (!this.dragging) return;
            const dx = e.clientX - this.px, dy = e.clientY - this.py;
            this.px = e.clientX; this.py = e.clientY;
            this.spin += dx * 0.35;
            this.tilt = Math.min(90, Math.max(-90, this.tilt - dy * 0.25));
            this.vx = dx * 0.35;
        },
        end() {
            if (!this.dragging) return;
            const projected = Math.abs(this.vx) > 2 ? this.spin + this.vx * 6 : this.spin;
            this.dragging = false;
            this.spin = Math.round(projected / 90) * 90;
            this.tilt = Math.min(90, Math.max(-90, Math.round(this.tilt / 90) * 90));
        },
        step(d) { this.dragging = false; this.spin += d * 90; },
        stepTilt(d) {
            this.dragging = false;
            this.tilt = Math.min(90, Math.max(-90, this.tilt + d * 90));
        },
        jump(i) {
            if (i === 4) { this.dragging = false; this.tilt = -90; return; }
            if (i === 5) { this.dragging = false; this.tilt = 90; return; }
            this.tilt = 0;
            let d = i - this.side;
            if (d > 2) d -= 4;
            if (d < -2) d += 4;
            this.step(d);
        },
        get side() { return ((Math.round(-this.spin / 90) % 4) + 4) % 4; },
        get face() {
            const t = Math.round(this.tilt / 90);
            if (t === -1) return 4;
            if (t === 1) return 5;
            return this.side;
        },
        get cubeStyle() { return 'transform: rotateX(' + this.tilt.toFixed(2) + 'deg) rotateY(' + this.spin.toFixed(2) + 'deg)'; },
        get statusLabel() {
            const f = this.faces[this.face] || this.faces[0];
            const n = this.face + 1;
            return this.fa
                ? 'وجه ' + this.num(String(n)) + ' از ۶ · ' + f.label
                : 'Face ' + n + ' of 6 \u00b7 ' + f.label;
        },
        num(s) { return this.fa ? s.replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]) : s; },
        destroy() { if (this.io) this.io.disconnect(); },
    }"
    x-bind:data-dragging="dragging ? 'true' : 'false'">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Six selling points, one spinning cube', 'شش نقطهٔ فروش، یک مکعب چرخان') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The Polaris Trail Watch folds its feature list into a CSS-3D cube: drag anywhere to spin and tilt it — on release it reads your finger’s speed and springs onto the nearest face. The face toward you lights up, mirrored by the dot rail; the buttons and the arrow keys walk face by face, and every dot jumps by the shortest path.', 'ساعت Polaris Trail فهرست ویژگی‌هایش را در یک مکعب CSS-3D جمع می‌کند: هر جای صحنه را بکشید تا بچرخد و تمایل بگیرد — رها که کنید سرعت انگشتتان را می‌خواند و با فنر روی نزدیک‌ترین وجه می‌نشیند. وجه روبهٔ شما روشن می‌شود و ردیف نقطه‌ها آن را بازتاب می‌دهد؛ دکمه‌ها و کلیدهای جهت‌دار وجه‌به‌وجه می‌چرخانند و هر نقطه با کوتاه‌ترین مسیر به وجهش می‌پرد.') }}
        </p>
    </div>

    <div class="d3fc-root">
        <div class="d3fc-stage" x-ref="stage" tabindex="0" role="group"
            x-bind:aria-label="@js($say('Feature cube; drag to spin, or use the arrow keys', 'مکعب ویژگی‌ها؛ برای چرخش بکشید یا از کلیدهای جهت‌دار استفاده کنید'))"
            x-on:pointerdown="start($event)"
            x-on:pointermove="move($event)"
            x-on:pointerup="end()"
            x-on:pointercancel="end()"
            x-on:keydown.arrow-right.prevent="step(-1)"
            x-on:keydown.arrow-left.prevent="step(1)"
            x-on:keydown.arrow-up.prevent="stepTilt(-1)"
            x-on:keydown.arrow-down.prevent="stepTilt(1)">
            <span class="d3fc-cap">{{ $say('Drag · arrow keys', 'بکشید · کلیدهای جهت‌دار') }}</span>
            <div class="d3fc-cube" x-bind:style="cubeStyle">
                @foreach ($faces as $i => $face)
                    <div class="d3fc-face" style="--ft: {{ $face['t'] }}" x-bind:data-on="face === {{ $i }}">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            @foreach ($face['paths'] as $d)
                                <path d="{{ $d }}"/>
                            @endforeach
                        </svg>
                        <b>{{ $say($face['en'], $face['fa']) }}</b>
                        <small>{{ $say($face['spec-en'], $face['spec-fa']) }}</small>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="d3fc-dock">
            <button type="button" class="d3fc-btn" x-on:click="step(1)"
                x-bind:aria-label="@js($say('Previous face', 'وجه قبلی'))">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                {{ $say('Prev', 'قبلی') }}
            </button>
            <span class="d3fc-dots">
                @foreach ($faces as $i => $face)
                    <button type="button" class="d3fc-dot"
                        x-bind:aria-current="face === {{ $i }} ? 'true' : 'false'"
                        x-bind:aria-label="faces[{{ $i }}].label"
                        x-on:click="jump({{ $i }})"></button>
                @endforeach
            </span>
            <button type="button" class="d3fc-btn" x-on:click="step(-1)">
                {{ $say('Next', 'بعدی') }}
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
            </button>
            <span class="d3fc-status" role="status" x-text="statusLabel"></span>
        </div>

        <p class="d3fc-hint">
            {{ $say('pure CSS 3D — six faces on preserve-3d, snap rounds to the nearest 90°', 'CSS سه‌بعدی خالص — شش وجه روی preserve-3d، اسنپ به نزدیک‌ترین ۹۰ درجه') }}
        </p>
    </div>
</section>
