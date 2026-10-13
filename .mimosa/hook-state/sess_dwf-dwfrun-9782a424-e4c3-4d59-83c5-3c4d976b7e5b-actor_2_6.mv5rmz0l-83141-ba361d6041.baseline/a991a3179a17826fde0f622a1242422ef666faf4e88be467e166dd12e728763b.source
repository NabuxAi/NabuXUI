{{--
    product-room's real scenario: the hero shot of a global audio brand —
    the "Nimbus One" smart speaker staged as a multi-layer 3D room: pedestal,
    mesh shell, woofer, control ring and light dome stacked along the scene's
    Z axis inside one perspective world. Dragging spins and tilts the whole
    room with soft inertia plus a gentle idle drift; the "exploded view"
    switch lifts every layer apart and pins a billboarded label to each one
    that always faces the camera (it counter-rotates the world). Pure CSS 3D
    — perspective + preserve-3d — no WebGL, no library. The RAF loop parks
    off screen, and under reduced motion the room answers the drag directly
    but never coasts or drifts.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    // The speaker's build sheet — every layer of the stack, bottom-up.
    $layers = [
        ['kind' => 'pedestal', 'fa' => 'سکوی نمایش', 'en' => 'Display pedestal', 'spec-fa' => 'چوب گردوی روغن‌خورده', 'spec-en' => 'Oiled walnut'],
        ['kind' => 'mesh', 'fa' => 'بدنهٔ مش', 'en' => 'Mesh shell', 'spec-fa' => 'پارچهٔ صوتی تنفس‌پذیر', 'spec-en' => 'Breathable acoustic fabric'],
        ['kind' => 'driver', 'fa' => 'درایور ووفر', 'en' => 'Woofer driver', 'spec-fa' => '۴۰ وات · دهانهٔ ۱۰ سانتی', 'spec-en' => '40 W · 10 cm cone'],
        ['kind' => 'ring', 'fa' => 'حلقهٔ کنترل', 'en' => 'Control ring', 'spec-fa' => 'لمس و چرخش، با بازخورد نور', 'spec-en' => 'Touch + turn, light feedback'],
        ['kind' => 'dome', 'fa' => 'گنبد نور', 'en' => 'Light dome', 'spec-fa' => 'درخشش ۲۷۰۰ کلوین', 'spec-en' => '2700 K glow'],
    ];
@endphp

<style>
    .d3pr-root {
        --d3pr-fg: #1c1917; --d3pr-muted: #78716c; --d3pr-border: #e7e5e4;
        --d3pr-card: #ffffff; --d3pr-accent: #4f46e5; --d3pr-accent-soft: #e0e7ff;
        --d3pr-stage-a: #fafaf9; --d3pr-stage-b: #f0eeec; --d3pr-lift: 13px;
        --d3pr-d: min(36vw, 11rem);
        --d3pr-font: 'Inter', 'Vazirmatn', ui-sans-serif, system-ui, sans-serif;
        display: grid; gap: .9rem; font-family: var(--d3pr-font); color: var(--d3pr-fg);
    }
    html[data-theme="dark"] .d3pr-root {
        --d3pr-fg: #fafaf9; --d3pr-muted: #a8a29e; --d3pr-border: #292524;
        --d3pr-card: #1c1917; --d3pr-accent: #a5b4fc; --d3pr-accent-soft: #312e5f;
        --d3pr-stage-a: #16130f; --d3pr-stage-b: #211d19;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .d3pr-root {
            --d3pr-fg: #fafaf9; --d3pr-muted: #a8a29e; --d3pr-border: #292524;
            --d3pr-card: #1c1917; --d3pr-accent: #a5b4fc; --d3pr-accent-soft: #312e5f;
            --d3pr-stage-a: #16130f; --d3pr-stage-b: #211d19;
        }
    }
    .d3pr-root[data-exploded="true"] { --d3pr-lift: 42px; }
    .d3pr-stage {
        position: relative; display: grid; place-items: center;
        inline-size: 100%; aspect-ratio: 4 / 3; max-inline-size: 30rem; margin-inline: auto;
        border: 1px solid var(--d3pr-border); border-radius: 1.25rem;
        background: radial-gradient(120% 100% at 50% 0%, var(--d3pr-stage-a), var(--d3pr-stage-b) 72%);
        perspective: 1100px; overflow: clip; cursor: grab; user-select: none;
        -webkit-user-select: none; touch-action: none;
    }
    .d3pr-stage:active { cursor: grabbing; }
    .d3pr-stage:focus-visible { outline: 2px solid var(--d3pr-accent); outline-offset: 2px; }
    .d3pr-world {
        position: relative; inline-size: var(--d3pr-d); aspect-ratio: 1;
        transform-style: preserve-3d;
    }
    .d3pr-slab {
        position: absolute; inset: 0; border-radius: 50%;
        transform-style: preserve-3d;
        transform: translateZ(calc((var(--i) - 2) * var(--d3pr-lift)));
        transition: transform .8s cubic-bezier(.22, 1, .36, 1);
    }
    .d3pr-pedestal { background: linear-gradient(220deg, #57504a, #35302c); box-shadow: 0 0 0 1px #00000026; }
    .d3pr-mesh {
        background: radial-gradient(#d6d3d1 1.1px, transparent 1.5px) 0 0 / 10px 10px, #f5f5f4;
        box-shadow: inset 0 0 0 2px #e7e5e4;
    }
    .d3pr-driver { background: repeating-radial-gradient(circle, #57534e 0 6px, #78716c 6px 12px); }
    .d3pr-ring { background: transparent; border: calc(var(--d3pr-d) * .055) solid var(--d3pr-accent); }
    .d3pr-dome { background: radial-gradient(circle, #c7d2fe 0 30%, var(--d3pr-accent-soft) 70%); }
    .d3pr-pin { position: absolute; inset-block-start: 50%; inset-inline-start: 68%; }
    .d3pr-tag {
        position: absolute; display: block; white-space: nowrap; text-align: start;
        padding: .3rem .6rem; border-radius: .5rem;
        background: var(--d3pr-card); border: 1px solid var(--d3pr-border);
        color: var(--d3pr-fg); font: 600 .68rem/1.35 var(--d3pr-font);
        opacity: 0; visibility: hidden;
        transition: opacity .35s ease calc(var(--i) * 70ms), visibility 0s ease calc(var(--i) * 70ms + .35s);
    }
    .d3pr-root[data-exploded="true"] .d3pr-tag { opacity: 1; visibility: visible; }
    .d3pr-tag small { display: block; font-weight: 400; color: var(--d3pr-muted); }
    .d3pr-cap {
        position: absolute; inset-block-start: .8rem; inset-inline-start: .8rem; z-index: 1;
        padding: .32rem .75rem; border-radius: 999px; pointer-events: none;
        background: color-mix(in srgb, var(--d3pr-card) 78%, transparent);
        border: 1px solid var(--d3pr-border); color: var(--d3pr-fg);
        font-size: .74rem; backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
    }
    .d3pr-dock {
        display: flex; flex-wrap: wrap; align-items: center; justify-content: center;
        gap: .5rem .75rem; max-inline-size: 30rem; margin-inline: auto;
    }
    .d3pr-btn {
        display: inline-flex; align-items: center; gap: .4rem; padding: .5rem 1rem;
        border-radius: 999px; border: 1px solid var(--d3pr-border); background: var(--d3pr-card);
        color: var(--d3pr-fg); font: 600 .8rem var(--d3pr-font); cursor: pointer;
        transition: border-color .15s ease, color .15s ease;
    }
    .d3pr-btn:hover { border-color: var(--d3pr-accent); color: var(--d3pr-accent); }
    .d3pr-btn:focus-visible { outline: 2px solid var(--d3pr-accent); outline-offset: 2px; }
    .d3pr-btn[aria-pressed="true"] { border-color: var(--d3pr-accent); color: var(--d3pr-accent); }
    .d3pr-btn svg { inline-size: .95rem; block-size: .95rem; stroke: currentColor; stroke-width: 2;
                    fill: none; stroke-linecap: round; stroke-linejoin: round; }
    .d3pr-status {
        font-size: .74rem; color: var(--d3pr-muted); font-variant-numeric: tabular-nums;
        white-space: nowrap;
    }
    .d3pr-hint { margin: 0; text-align: center; font-size: .74rem; color: var(--d3pr-muted); }
    @media (max-width: 430px) {
        .d3pr-root { --d3pr-d: min(36vw, 8.5rem); }
        .d3pr-tag { font-size: .62rem; padding: .24rem .5rem; }
    }
    @media (prefers-reduced-motion: reduce) {
        .d3pr-slab, .d3pr-tag { transition: none; }
        .d3pr-btn { transition: none; }
    }
</style>

<section class="pg-box" style="gap: 1rem"
    x-data="{
        spin: -28, tilt: 62, exploded: false, dragging: false,
        vx: 0, vy: 0, px: 0, py: 0,
        raf: 0, io: null, visible: true, mq: null, onMq: null,
        fa: @js($fa),
        init() {
            this.mq = matchMedia('(prefers-reduced-motion: reduce)');
            this.onMq = () => { if (this.mq.matches) { this.vx = 0; this.vy = 0; cancelAnimationFrame(this.raf); } else { this.run(); } };
            this.mq.addEventListener('change', this.onMq);
            this.io = new IntersectionObserver((entries) => { this.visible = entries[0].isIntersecting; this.run(); });
            this.io.observe(this.$refs.stage);
            this.run();
        },
        start(e) {
            this.$refs.stage.setPointerCapture(e.pointerId);
            this.dragging = true; this.px = e.clientX; this.py = e.clientY;
            this.vx = 0; this.vy = 0;
            cancelAnimationFrame(this.raf);
        },
        move(e) {
            if (!this.dragging) return;
            const dx = e.clientX - this.px, dy = e.clientY - this.py;
            this.px = e.clientX; this.py = e.clientY;
            this.spin += dx * 0.4;
            this.tilt = Math.min(80, Math.max(30, this.tilt - dy * 0.3));
            this.vx = dx * 0.4; this.vy = -dy * 0.3;
        },
        end() { if (!this.dragging) return; this.dragging = false; this.run(); },
        run() {
            cancelAnimationFrame(this.raf);
            if (!this.visible || this.mq.matches) return;
            const loop = () => {
                if (!this.visible) return;
                if (Math.abs(this.vx) > 0.02 || Math.abs(this.vy) > 0.02) {
                    this.spin += this.vx;
                    this.tilt = Math.min(80, Math.max(30, this.tilt + this.vy));
                    this.vx *= 0.94; this.vy *= 0.94;
                } else if (!this.dragging) {
                    this.spin += 0.1;
                }
                this.raf = requestAnimationFrame(loop);
            };
            this.raf = requestAnimationFrame(loop);
        },
        get worldStyle() { return 'transform: rotateX(' + this.tilt.toFixed(2) + 'deg) rotateZ(' + this.spin.toFixed(2) + 'deg)'; },
        get tagStyle() { return 'transform: rotateZ(' + (-this.spin).toFixed(2) + 'deg) rotateX(' + (-this.tilt).toFixed(2) + 'deg)'; },
        num(n) { const s = String(n); return this.fa ? s.replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]) : s; },
        get statusLabel() {
            const a = ((Math.round(this.spin) % 360) + 360) % 360;
            const t = Math.round(this.tilt);
            return this.fa
                ? 'چرخش ' + this.num(a) + '° · تمایل ' + this.num(t) + '° · ' + (this.exploded ? 'نمای انفجاری' : 'لایه‌ها چیده')
                : 'spin ' + a + '\u00b0 \u00b7 tilt ' + t + '\u00b0 \u00b7 ' + (this.exploded ? 'exploded' : 'stacked');
        },
        destroy() {
            cancelAnimationFrame(this.raf);
            if (this.io) this.io.disconnect();
            if (this.mq && this.onMq) this.mq.removeEventListener('change', this.onMq);
        },
    }"
    x-bind:data-exploded="exploded ? 'true' : 'false'">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A product room you can take apart', 'اتاق محصولی که می‌شود بازش کرد') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The Nimbus One speaker lives in one perspective world: five layers stacked along the Z axis on a walnut pedestal. Drag anywhere to spin and tilt the room — it coasts on your finger’s speed, then drifts slowly while idle. The exploded view lifts the layers apart and pins a label to each one that counter-rotates so it always reads facing you.', 'بلندگوی Nimbus One در یک دنیای پرسپکتیو زندگی می‌کند: پنج لایه روی محور Z و روی سکوی چوب گردو چیده شده‌اند. هر جای صحنه را بکشید تا اتاق بچرخد و تمایل بگیرد — با سرعت انگشتتان سُر می‌خورد و بعد در آرامش آهسته می‌گردد. نمای انفجاری لایه‌ها را از هم باز می‌کند و به هر کدام برچسبی می‌دوزد که خلاف صحنه می‌چرخد و همیشه روبهٔ شما خوانده می‌شود.') }}
        </p>
    </div>

    <div class="d3pr-root">
        <div class="d3pr-stage" x-ref="stage" tabindex="0" role="img"
            x-bind:aria-label="@js($say('Three-dimensional product room; drag to spin and tilt', 'اتاق محصول سه‌بعدی؛ برای چرخش و تمایل دادن بکشید'))"
            x-on:pointerdown="start($event)"
            x-on:pointermove="move($event)"
            x-on:pointerup="end()"
            x-on:pointercancel="end()">
            <span class="d3pr-cap">{{ $say('Drag to orbit', 'بکشید و بچرخانید') }}</span>
            <div class="d3pr-world" x-bind:style="worldStyle">
                @foreach ($layers as $i => $layer)
                    <div class="d3pr-slab d3pr-{{ $layer['kind'] }}" style="--i: {{ $i }}">
                        <span class="d3pr-pin">
                            <span class="d3pr-tag" style="--i: {{ $i }}" x-bind:style="tagStyle">
                                <b>{{ $say($layer['en'], $layer['fa']) }}</b>
                                <small>{{ $say($layer['spec-en'], $layer['spec-fa']) }}</small>
                            </span>
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="d3pr-dock">
            <button type="button" class="d3pr-btn" x-on:click="exploded = !exploded"
                x-bind:aria-pressed="exploded ? 'true' : 'false'">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m12 2 9 5-9 5-9-5 9-5Z"/><path d="m3 12 9 5 9-5"/><path d="m3 17 9 5 9-5"/></svg>
                {{ $say('Exploded view', 'نمای انفجاری') }}
            </button>
            <button type="button" class="d3pr-btn" x-on:click="spin = -28; tilt = 62; vx = 0; vy = 0">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>
                {{ $say('Reset view', 'بازنشانی نما') }}
            </button>
            <span class="d3pr-status" role="status" x-text="statusLabel"></span>
        </div>

        <p class="d3pr-hint">
            {{ $say('pure CSS 3D — perspective + preserve-3d, no WebGL · labels billboard against the world rotation', 'CSS سه‌بعدی خالص — perspective و preserve-3d، بدون WebGL · برچسب‌ها خلاف چرخش صحنه روی دوربین قفل‌اند') }}
        </p>
    </div>
</section>
