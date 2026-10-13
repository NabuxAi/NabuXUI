{{--
    perspective-grid's real scenario: the hero of "Nightdrive FM", a retro synth
    station — a perspective-rotated floor whose 64px grid streams toward the
    viewer on a seamless one-cell translate loop, a fog band eating the horizon
    and a half-sunken synthwave sun. The slider retunes the speed live
    (0.6–3s per cell); the lines sit flat grey in the light theme and glow
    fuchsia in the dark one. The stream parks off screen and freezes under
    reduced motion.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<style>
    .ambg-root {
        --ambg-ink: #1c1917; --ambg-muted: #78716c; --ambg-border: #e7e5e4;
        --ambg-font: 'Inter', 'Vazirmatn', ui-sans-serif, system-ui, sans-serif;
        display: grid; gap: 1rem; font-family: var(--ambg-font); color: var(--ambg-ink);
    }
    html[data-theme="dark"] .ambg-root {
        --ambg-ink: #fafaf9; --ambg-muted: #a8a29e; --ambg-border: #292524;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .ambg-root {
            --ambg-ink: #fafaf9; --ambg-muted: #a8a29e; --ambg-border: #292524;
        }
    }
    .ambg-stage {
        --ambg-bg: #fdfcfe; --ambg-line: #78716c; --ambg-line-alpha: .28;
        --ambg-ink2: #1c1917; --ambg-sub: #57534e; --ambg-chip: rgba(28, 25, 23, .06);
        --ambg-chip-border: rgba(28, 25, 23, .16); --ambg-accent: #a21caf;
        position: relative; overflow: clip; border-radius: 1.25rem;
        min-block-size: 24rem; display: grid; place-content: center; justify-items: center;
        gap: .9rem; padding: 3rem 1.25rem 4.5rem; text-align: center;
        background: var(--ambg-bg); color: var(--ambg-ink2); perspective: 420px;
    }
    html[data-theme="dark"] .ambg-stage {
        --ambg-bg: #171228; --ambg-line: #f0abfc; --ambg-line-alpha: .38;
        --ambg-ink2: #fdf4ff; --ambg-sub: #d8b4fe; --ambg-chip: rgba(240, 171, 252, .1);
        --ambg-chip-border: rgba(240, 171, 252, .3); --ambg-accent: #f0abfc;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .ambg-stage {
            --ambg-bg: #171228; --ambg-line: #f0abfc; --ambg-line-alpha: .38;
            --ambg-ink2: #fdf4ff; --ambg-sub: #d8b4fe; --ambg-chip: rgba(240, 171, 252, .1);
            --ambg-chip-border: rgba(240, 171, 252, .3); --ambg-accent: #f0abfc;
        }
    }
    .ambg-sun {
        position: absolute; inset-inline: 0; inset-block-start: 30%;
        margin-inline: auto; inline-size: 9.5rem; block-size: 9.5rem; border-radius: 50%;
        background: linear-gradient(#fde68a, #f472b6 65%, #c026d3);
        opacity: .5; z-index: 0;
    }
    .ambg-floor {
        position: absolute; inset-inline: -60%; inset-block-start: 52%; block-size: 150%;
        transform: rotateX(58deg); transform-origin: 50% 0; z-index: 1;
    }
    .ambg-lines {
        position: absolute; inset-inline: 0; inset-block: -100% 0; display: block;
        background-image:
            repeating-linear-gradient(to right, color-mix(in oklab, var(--ambg-line) calc(var(--ambg-line-alpha) * 100%), transparent) 0 2px, transparent 2px 64px),
            repeating-linear-gradient(to bottom, color-mix(in oklab, var(--ambg-line) calc(var(--ambg-line-alpha) * 100%), transparent) 0 2px, transparent 2px 64px);
        animation: ambg-run var(--ambg-dur, 1.6s) linear infinite;
        will-change: transform;
    }
    html[data-theme="dark"] .ambg-lines {
        filter: drop-shadow(0 0 6px rgba(240, 171, 252, .45));
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .ambg-lines {
            filter: drop-shadow(0 0 6px rgba(240, 171, 252, .45));
        }
    }
    .ambg-stage[data-running="false"] .ambg-lines { animation-play-state: paused; }
    @keyframes ambg-run { to { translate: 0 64px; } }
    .ambg-fog {
        position: absolute; inset-inline: 0; inset-block-start: 0; block-size: 56%; z-index: 2;
        background: linear-gradient(var(--ambg-bg) 14%, transparent);
        pointer-events: none;
    }
    .ambg-fog-2 {
        position: absolute; inset-inline: 0; inset-block-end: 0; block-size: 18%; z-index: 2;
        background: linear-gradient(transparent, var(--ambg-bg));
        pointer-events: none;
    }
    .ambg-copy { position: relative; z-index: 3; display: grid; justify-items: center; gap: .9rem; }
    .ambg-badge {
        display: inline-flex; align-items: center; gap: .45rem;
        padding: .35rem .85rem; border-radius: 999px;
        background: var(--ambg-chip); border: 1px solid var(--ambg-chip-border);
        font-size: .76rem; font-weight: 600; color: var(--ambg-sub);
    }
    .ambg-badge svg { inline-size: .85rem; block-size: .85rem; stroke: currentColor; stroke-width: 2;
                      fill: none; stroke-linecap: round; stroke-linejoin: round; }
    .ambg-h { max-inline-size: 32rem; margin: 0; font-size: clamp(1.6rem, 4.5vw, 2.4rem);
              font-weight: 700; line-height: 1.25; letter-spacing: -.01em; }
    .ambg-p { max-inline-size: 28rem; margin: 0; color: var(--ambg-sub); font-size: .95rem; line-height: 1.7; }
    .ambg-speed {
        display: flex; flex-wrap: wrap; align-items: center; justify-content: center;
        gap: .6rem 1rem; margin-block-start: .75rem;
        padding: .55rem 1.1rem; border-radius: 999px;
        background: var(--ambg-chip); border: 1px solid var(--ambg-chip-border);
    }
    .ambg-speed label { display: inline-flex; align-items: center; gap: .65rem; font-size: .78rem; font-weight: 600; color: var(--ambg-sub); }
    .ambg-speed input[type="range"] {
        inline-size: clamp(8rem, 32vw, 13rem); accent-color: var(--ambg-accent); cursor: pointer;
    }
    .ambg-speed input[type="range"]:focus-visible { outline: 2px solid var(--ambg-accent); outline-offset: 3px; }
    .ambg-read { min-inline-size: 5.5ch; font-size: .76rem; font-variant-numeric: tabular-nums; color: var(--ambg-sub); }
    .ambg-hint { margin: 0; font: 400 .74rem/1.6 ui-monospace, monospace; color: var(--ambg-muted); text-align: center; }
    @media (prefers-reduced-motion: reduce) {
        .ambg-lines { animation: none; }
    }
</style>

<section class="pg-box" style="gap: 1.25rem"
    x-data="{
        io: null,
        running: true,
        dur: 1.6,
        fa: @js($fa),
        init() {
            this.io = new IntersectionObserver((entries) => {
                this.running = entries[0].isIntersecting;
            }, { threshold: 0.15 });
            this.io.observe(this.$refs.stage);
        },
        destroy() { if (this.io) this.io.disconnect(); },
        get durLabel() {
            const s = this.dur.toFixed(1);
            return this.fa ? s.replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]) + ' ثانیه/خانه' : s + 's / cell';
        },
    }">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A floor that streams toward you', 'کفی که به سمت شما جریان دارد') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('One rotateX(58°) plane, one repeating 64px grid and a seamless one-cell translate loop — the grid rushes at the viewer without ever re-laying pixels. The fog band eats the horizon, the sun sinks half behind it, and the slider retunes the tempo from cruising to warp. Flat grey in daylight, fuchsia glow after dark.', 'یک صفحهٔ rotateX(58 درجه)، یک شبکهٔ تکرارشوندهٔ ۶۴ پیکسلی و یک حلقهٔ translate بی‌درز به اندازهٔ یک خانه — شبکه بدون بازچینش پیکسل‌ها به سمت بیننده هجوم می‌آورد. نوار مه افق را می‌بلعد، خورشید نیمه پشت آن غرق می‌شود و اسلایدر حال‌وهوا را از گشت‌وگذار تا وارپ تنظیم می‌کند. در روشنایی تخت و خاکستری، در تاریکی بنفش درخشان.') }}
        </p>
    </div>

    <div class="ambg-stage" x-ref="stage"
        x-bind:data-running="running ? 'true' : 'false'"
        x-bind:style="`--ambg-dur: ${dur}s`">
        <div class="ambg-sun" aria-hidden="true"></div>
        <div class="ambg-floor" aria-hidden="true"><i class="ambg-lines"></i></div>
        <div class="ambg-fog" aria-hidden="true"></div>
        <div class="ambg-fog-2" aria-hidden="true"></div>
        <div class="ambg-copy">
            <span class="ambg-badge">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>
                {{ $say('Nightdrive FM · live from the grid', 'Nightdrive FM · زنده از دل شبکه') }}
            </span>
            <h4 class="ambg-h">{{ $say('Thirty-two years of synth, one horizon', 'سی‌ودو سال سینت‌ویو، یک افق') }}</h4>
            <p class="ambg-p">{{ $say('From Tokyo nights to Toronto dawns — Nightdrive FM streams the classics and the fresh presses back to back, 24 hours a day.', 'از شب‌های توکیو تا سپیده‌های تورنتو — Nightdrive FM کلاسیک‌ها و تازه‌ها را پشت‌سرهم، شبانه‌روز پخش می‌کند.') }}</p>
            <div class="ambg-speed">
                <label>
                    <span aria-hidden="true">🐢</span>
                    <input type="range" min="0.6" max="3" step="0.1" x-model.number="dur"
                        aria-label="{{ $say('Grid speed', 'سرعت شبکه') }}">
                    <span aria-hidden="true">🚀</span>
                </label>
                <span class="ambg-read" role="status" x-text="durLabel"></span>
            </div>
        </div>
    </div>

    <p class="ambg-hint">
        {{ $say('rotateX(58°) plane · 64px cells · seamless one-cell translate loop · drop-shadow glow in dark · pauses off screen', 'صفحهٔ rotateX(۵۸°) · خانه‌های ۶۴px · حلقهٔ translate یک‌خانه‌ای بی‌درز · درخشش drop-shadow در تیره · توقف بیرون از دید') }}
    </p>
</section>
