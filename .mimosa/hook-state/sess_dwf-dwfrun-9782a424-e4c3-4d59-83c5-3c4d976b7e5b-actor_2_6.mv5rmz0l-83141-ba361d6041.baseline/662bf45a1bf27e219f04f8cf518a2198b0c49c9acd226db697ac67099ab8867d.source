{{--
    open-box's real scenario: the product page of "Halo Buds Pro" — the
    gift box itself is the hero. A pure-CSS cuboid (perspective +
    preserve-3d, no WebGL, no library) sits on a soft spotlight; moving
    the pointer across the stage tilts the box by up to ten degrees on a
    short CSS delay — inertia without a JavaScript loop. Hovering swings
    the lid 110° off its back hinge on an overshooting spring and the
    charging case with both buds leaps above the rim; a click pins the
    open state (aria-expanded on the stage and the button) and a second
    click seats everything back home. The price tag is two-sided so it
    can spin on its own axis forever — the IntersectionObserver parks it
    off screen, and under reduced motion every transition snaps and the
    tag stands still.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp

<style>
    .d3ob-root {
        --d3ob-fg: #1c1917; --d3ob-muted: #78716c; --d3ob-border: #e7e5e4;
        --d3ob-card: #ffffff; --d3ob-accent: #4f46e5; --d3ob-accent-deep: #4338ca;
        --d3ob-accent-side: #6366f1; --d3ob-accent-back: #3730a3; --d3ob-void: #1e1b4b;
        --d3ob-stage-a: #fafaf9; --d3ob-stage-b: #f0eeec; --d3ob-goods: #fafafa;
        --d3ob-font: 'Inter', 'Vazirmatn', ui-sans-serif, system-ui, sans-serif;
        display: grid; gap: .9rem; font-family: var(--d3ob-font); color: var(--d3ob-fg);
    }
    html[data-theme="dark"] .d3ob-root {
        --d3ob-fg: #fafaf9; --d3ob-muted: #a8a29e; --d3ob-border: #292524;
        --d3ob-card: #1c1917; --d3ob-accent: #6366f1; --d3ob-accent-deep: #4f46e5;
        --d3ob-accent-side: #4338ca; --d3ob-accent-back: #312e81; --d3ob-void: #0f0d2e;
        --d3ob-stage-a: #16130f; --d3ob-stage-b: #211d19; --d3ob-goods: #e7e5e4;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .d3ob-root {
            --d3ob-fg: #fafaf9; --d3ob-muted: #a8a29e; --d3ob-border: #292524;
            --d3ob-card: #1c1917; --d3ob-accent: #6366f1; --d3ob-accent-deep: #4f46e5;
            --d3ob-accent-side: #4338ca; --d3ob-accent-back: #312e81; --d3ob-void: #0f0d2e;
            --d3ob-stage-a: #16130f; --d3ob-stage-b: #211d19; --d3ob-goods: #e7e5e4;
        }
    }
    .d3ob-stage {
        position: relative; display: grid; place-items: center;
        inline-size: 100%; aspect-ratio: 4 / 3; max-inline-size: 26rem; margin-inline: auto;
        border: 1px solid var(--d3ob-border); border-radius: 1.25rem;
        background: radial-gradient(120% 100% at 50% 0%, var(--d3ob-stage-a), var(--d3ob-stage-b) 72%);
        perspective: 950px; overflow: clip; cursor: pointer; user-select: none;
        -webkit-user-select: none; touch-action: manipulation;
    }
    .d3ob-stage:focus-visible { outline: 2px solid var(--d3ob-accent); outline-offset: 2px; }
    .d3ob-shadow {
        position: absolute; inset-block-end: 14%; inline-size: 60%; block-size: 12%;
        background: radial-gradient(50% 50% at 50% 50%, rgb(0 0 0 / .22), transparent 70%);
        filter: blur(6px); pointer-events: none;
    }
    .d3ob-tilt { transition: transform .45s ease-out; transform-style: preserve-3d; }
    .d3ob-box {
        --d3ob-w: min(46vw, 10rem);
        --d3ob-h: calc(var(--d3ob-w) * .78);
        --d3ob-d: calc(var(--d3ob-w) * .52);
        position: relative; inline-size: var(--d3ob-w); block-size: var(--d3ob-h);
        transform-style: preserve-3d;
    }
    .d3ob-face { position: absolute; inset: 0; backface-visibility: hidden; -webkit-backface-visibility: hidden; }
    .d3ob-front {
        display: grid; place-content: center; justify-items: center; gap: .3rem;
        border-radius: .55rem; color: #fff; text-align: center;
        background:
            radial-gradient(60% 46% at 50% 34%, rgb(255 255 255 / .16), transparent 68%),
            linear-gradient(200deg, var(--d3ob-accent), var(--d3ob-accent-deep));
        transform: translateZ(calc(var(--d3ob-d) / 2));
    }
    .d3ob-front b { font-size: clamp(1.05rem, 5vw, 1.5rem); letter-spacing: .3em; padding-inline-start: .3em; }
    .d3ob-front small { font-size: .58rem; letter-spacing: .42em; padding-inline-start: .42em; opacity: .82; }
    .d3ob-front i {
        inline-size: 34%; aspect-ratio: 1; border-radius: 50%; margin-block-end: .35rem;
        border: 1px solid rgb(255 255 255 / .4);
        background: radial-gradient(circle at 38% 34%, rgb(255 255 255 / .5), transparent 42%);
    }
    .d3ob-back {
        border-radius: .55rem; background: var(--d3ob-accent-back);
        transform: rotateY(180deg) translateZ(calc(var(--d3ob-d) / 2));
    }
    .d3ob-left, .d3ob-right {
        inline-size: var(--d3ob-d); inset-inline-start: calc(50% - var(--d3ob-d) / 2);
        background: linear-gradient(180deg, var(--d3ob-accent-side), var(--d3ob-accent-deep));
    }
    .d3ob-left { transform: translateX(calc(var(--d3ob-w) / -2)) rotateY(-90deg); }
    .d3ob-right { transform: translateX(calc(var(--d3ob-w) / 2)) rotateY(90deg); }
    .d3ob-floor {
        position: absolute; inline-size: var(--d3ob-w); block-size: var(--d3ob-d);
        inset-inline: 0; inset-block-start: calc(50% - var(--d3ob-d) / 2);
        background: radial-gradient(60% 60% at 50% 40%, var(--d3ob-accent-back), var(--d3ob-void));
        backface-visibility: visible; -webkit-backface-visibility: visible;
        transform: translateY(calc(var(--d3ob-h) / 2)) rotateX(90deg);
    }
    .d3ob-lid {
        position: absolute; inline-size: var(--d3ob-w); block-size: var(--d3ob-d);
        inset-inline: 0; inset-block-start: 0; transform-origin: 50% 0;
        border-radius: .55rem .55rem 0 0;
        background:
            radial-gradient(42% 60% at 50% 0%, rgb(255 255 255 / .14), transparent 70%),
            linear-gradient(180deg, var(--d3ob-accent-side), var(--d3ob-accent-deep));
        box-shadow: inset 0 -14px 22px -16px rgb(0 0 0 / .55);
        transform: translateZ(calc(var(--d3ob-d) / -2)) rotateX(90deg);
        transition: transform .7s cubic-bezier(.34, 1.56, .64, 1);
    }
    .d3ob-root[data-open="true"] .d3ob-lid { transform: translateZ(calc(var(--d3ob-d) / -2)) rotateX(200deg); }
    .d3ob-goods {
        position: absolute; inset: 0; display: grid; place-content: center; justify-items: center;
        gap: .45rem; transform: translateY(6%) scale(.96);
        transition: transform .7s cubic-bezier(.34, 1.56, .64, 1);
    }
    .d3ob-root[data-open="true"] .d3ob-goods { transform: translateY(-62%) scale(1); }
    .d3ob-budsrow { display: flex; gap: .55rem; }
    .d3ob-bud {
        inline-size: 1.35rem; aspect-ratio: 1; border-radius: 50% 50% 50% 30%;
        background: linear-gradient(160deg, #ffffff, #d6d3d1);
        box-shadow: inset 0 -3px 5px rgb(0 0 0 / .18), 0 6px 12px -6px rgb(0 0 0 / .4);
        rotate: -18deg;
    }
    .d3ob-bud:last-child { border-radius: 50% 50% 30% 50%; rotate: 18deg; }
    .d3ob-case {
        inline-size: 4.1rem; block-size: 1.75rem; border-radius: .9rem;
        background: linear-gradient(180deg, #ffffff 62%, #d6d3d1 62%);
        box-shadow: inset 0 2px 4px rgb(255 255 255 / .8), 0 10px 18px -8px rgb(0 0 0 / .45);
        display: grid; place-items: center;
    }
    .d3ob-case::after {
        content: ''; inline-size: .3rem; block-size: .3rem; border-radius: 50%;
        background: var(--d3ob-accent); box-shadow: 0 0 6px var(--d3ob-accent);
    }
    .d3ob-goods b { font-size: .58rem; letter-spacing: .18em; color: var(--d3ob-muted);
                    background: var(--d3ob-goods); padding: .18rem .5rem; border-radius: 999px; }
    .d3ob-tagwrap {
        position: absolute; inset-inline-end: 8%; inset-block-end: 13%;
        perspective: 420px; pointer-events: none;
    }
    .d3ob-tag {
        position: relative; inline-size: 4.9rem; block-size: 3.1rem;
        transform-style: preserve-3d; animation: d3ob-tag-turn 7s linear infinite;
    }
    .d3ob-tface {
        position: absolute; inset: 0; display: grid; place-content: center; justify-items: center;
        gap: .1rem; border-radius: .55rem; border: 1px solid var(--d3ob-border);
        background: var(--d3ob-card); color: var(--d3ob-fg);
        backface-visibility: hidden; -webkit-backface-visibility: hidden;
        box-shadow: 0 12px 22px -14px rgb(0 0 0 / .5);
    }
    .d3ob-tface::before {
        content: ''; position: absolute; inset-block-start: .3rem; inset-inline: 0;
        margin-inline: auto; inline-size: .32rem; block-size: .32rem; border-radius: 50%;
        background: transparent; box-shadow: inset 0 0 0 1.5px var(--d3ob-border);
    }
    .d3ob-tface b { font-size: .95rem; }
    .d3ob-tface small { font-size: .5rem; letter-spacing: .12em; color: var(--d3ob-muted); }
    .d3ob-tface:last-child { transform: rotateY(180deg); }
    .d3ob-root[data-visible="false"] .d3ob-tag { animation-play-state: paused; }
    @keyframes d3ob-tag-turn { to { transform: rotateY(360deg); } }
    .d3ob-cap {
        position: absolute; inset-block-start: .8rem; inset-inline-start: .8rem; z-index: 1;
        padding: .32rem .75rem; border-radius: 999px; pointer-events: none;
        background: color-mix(in srgb, var(--d3ob-card) 78%, transparent);
        border: 1px solid var(--d3ob-border); color: var(--d3ob-fg);
        font-size: .74rem; backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
    }
    .d3ob-dock {
        display: flex; flex-wrap: wrap; align-items: center; justify-content: center;
        gap: .5rem .75rem; max-inline-size: 26rem; margin-inline: auto;
    }
    .d3ob-btn {
        display: inline-flex; align-items: center; gap: .4rem; padding: .5rem 1rem;
        border-radius: 999px; border: 1px solid var(--d3ob-border); background: var(--d3ob-card);
        color: var(--d3ob-fg); font: 600 .8rem var(--d3ob-font); cursor: pointer;
        transition: border-color .15s ease, color .15s ease;
    }
    .d3ob-btn:hover { border-color: var(--d3ob-accent); color: var(--d3ob-accent); }
    .d3ob-btn:focus-visible { outline: 2px solid var(--d3ob-accent); outline-offset: 2px; }
    .d3ob-btn[aria-expanded="true"] { border-color: var(--d3ob-accent); color: var(--d3ob-accent); }
    .d3ob-btn svg { inline-size: .95rem; block-size: .95rem; stroke: currentColor; stroke-width: 2;
                    fill: none; stroke-linecap: round; stroke-linejoin: round; }
    .d3ob-status {
        font-size: .74rem; color: var(--d3ob-muted); font-variant-numeric: tabular-nums;
        white-space: nowrap;
    }
    .d3ob-hint { margin: 0; text-align: center; font-size: .74rem; color: var(--d3ob-muted); }
    @media (max-width: 430px) {
        .d3ob-box { --d3ob-w: min(50vw, 8.75rem); }
        .d3ob-tagwrap { inset-inline-end: 5%; inset-block-end: 10%; }
        .d3ob-tag { inline-size: 4.3rem; block-size: 2.8rem; }
    }
    @media (prefers-reduced-motion: reduce) {
        .d3ob-tilt, .d3ob-lid, .d3ob-goods, .d3ob-btn { transition: none; }
        .d3ob-tag { animation: none; }
    }
</style>

<section class="pg-box" style="gap: 1rem"
    x-data="{
        pinned: false, hovering: false, visible: true,
        rx: -14, ry: 32, io: null, mq: null, onMq: null,
        fa: @js($fa),
        init() {
            this.mq = matchMedia('(prefers-reduced-motion: reduce)');
            this.onMq = () => { if (this.mq.matches) { this.rx = -14; this.ry = 32; } };
            this.mq.addEventListener('change', this.onMq);
            this.io = new IntersectionObserver((entries) => { this.visible = entries[0].isIntersecting; });
            this.io.observe(this.$refs.stage);
        },
        get open() { return this.pinned || this.hovering; },
        get boxStyle() {
            return 'transform: rotateX(' + this.rx.toFixed(2) + 'deg) rotateY(' + this.ry.toFixed(2) + 'deg)';
        },
        tilt(e) {
            if (this.mq.matches) return;
            const r = this.$refs.stage.getBoundingClientRect();
            this.ry = 32 + ((e.clientX - r.left) / r.width - 0.5) * 14;
            this.rx = -14 - ((e.clientY - r.top) / r.height - 0.5) * 10;
        },
        untilt() { this.rx = -14; this.ry = 32; },
        toggle() { this.pinned = !this.pinned; },
        get statusLabel() {
            if (!this.open) {
                return this.fa ? 'جعبه بسته · هاور یا کلیک کنید' : 'box closed \u00b7 hover or click';
            }
            const how = this.pinned
                ? (this.fa ? 'سنجاق‌شده با کلیک' : 'pinned by click')
                : (this.fa ? 'باز روی هاور' : 'open on hover');
            return this.fa
                ? 'درِ جعبه باز · ' + this.num('110') + ' درجه · ' + how
                : 'lid open \u00b7 110\u00b0 \u00b7 ' + how;
        },
        num(s) { return this.fa ? s.replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]) : s; },
        destroy() {
            if (this.io) this.io.disconnect();
            if (this.mq && this.onMq) this.mq.removeEventListener('change', this.onMq);
        },
    }">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The box is the hero of the product page', 'جعبه خودش قهرمانِ صفحهٔ محصول است') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Halo Buds Pro ships in a pure-CSS cuboid: hover the stage and the lid swings 110° off its back hinge on an overshooting spring while the charging case and both buds leap above the rim; click to pin the open state — aria-expanded announces it either way. Move the pointer and the box tilts after it by up to ten degrees on a short CSS delay, and the two-sided price tag keeps turning on its own axis until the stage leaves the view.', 'هدفون‌های Halo Buds Pro در یک مکعب‌ مستطیلِ CSS خالص ارسال می‌شوند: روی صحنه هاور کنید تا در، از لولای پشتی ۱۱۰ درجه باز شود و با فنری که کمی اورشوت دارد، قابل‌شارژ و هر دو گوشی از لبهٔ جعبه بالا بجهند؛ کلیک کنید تا حالت باز سنجاق شود — aria-expanded آن را هرطور که باشد اعلام می‌کند. اشاره را حرکت دهید تا جعبه تا ده درجه، با تأخیر کوتاهِ CSS، دنبالش متمایل شود و برچسب قیمت دوردو تا وقتی صحنه در دید است به دور خودش بچرخد.') }}
        </p>
    </div>

    <div class="d3ob-root"
        x-bind:data-open="open ? 'true' : 'false'"
        x-bind:data-visible="visible ? 'true' : 'false'">
        <div class="d3ob-stage" x-ref="stage" tabindex="0" role="button"
            x-bind:aria-expanded="open ? 'true' : 'false'"
            x-bind:aria-label="@js($say('Halo Buds Pro gift box; hover or click to open', 'جعبهٔ هدیهٔ Halo Buds Pro؛ برای باز شدن هاور یا کلیک کنید'))"
            x-on:pointerenter="hovering = true"
            x-on:pointerleave="hovering = false; untilt()"
            x-on:pointermove="tilt($event)"
            x-on:click="toggle()"
            x-on:keydown.enter.prevent="toggle()"
            x-on:keydown.space.prevent="toggle()">
            <span class="d3ob-cap">{{ $say('Hover · click', 'هاور · کلیک') }}</span>
            <span class="d3ob-shadow" aria-hidden="true"></span>
            <div class="d3ob-tilt" x-bind:style="boxStyle">
                <div class="d3ob-box">
                    <div class="d3ob-face d3ob-front">
                        <i aria-hidden="true"></i>
                        <b>HALO</b>
                        <small>BUDS PRO</small>
                    </div>
                    <div class="d3ob-face d3ob-back" aria-hidden="true"></div>
                    <div class="d3ob-face d3ob-left" aria-hidden="true"></div>
                    <div class="d3ob-face d3ob-right" aria-hidden="true"></div>
                    <div class="d3ob-floor" aria-hidden="true"></div>
                    <div class="d3ob-goods" aria-hidden="true">
                        <span class="d3ob-budsrow">
                            <span class="d3ob-bud"></span>
                            <span class="d3ob-bud"></span>
                        </span>
                        <span class="d3ob-case"></span>
                        <b>HALO BUDS PRO</b>
                    </div>
                    <div class="d3ob-lid" aria-hidden="true"></div>
                </div>
            </div>
            <div class="d3ob-tagwrap">
                <div class="d3ob-tag" aria-hidden="true">
                    <div class="d3ob-tface">
                        <b>{{ $say('$149', $num('149') . ' دلار') }}</b>
                        <small>HALO BUDS PRO</small>
                    </div>
                    <div class="d3ob-tface">
                        <b>{{ $say('$149', $num('149') . ' دلار') }}</b>
                        <small>HALO BUDS PRO</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="d3ob-dock">
            <button type="button" class="d3ob-btn" x-on:click="toggle()"
                x-bind:aria-expanded="open ? 'true' : 'false'">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3l1.9 5.7a2 2 0 0 0 1.3 1.3L21 12l-5.8 2a2 2 0 0 0-1.3 1.3L12 21l-1.9-5.7a2 2 0 0 0-1.3-1.3L3 12l5.8-2a2 2 0 0 0 1.3-1.3L12 3Z"/><path d="M19 3v2"/><path d="M18 4h2"/></svg>
                <span x-text="open
                    ? @js($say('Close the box', 'بستن جعبه'))
                    : @js($say('Open the box', 'باز کردن جعبه'))"></span>
            </button>
            <span class="d3ob-status" role="status" x-text="statusLabel"></span>
        </div>

        <p class="d3ob-hint">
            {{ $say('pure CSS 3D — hinged lid on preserve-3d, spring pop, two-sided spinning tag', 'CSS سه‌بعدی خالص — درِ لولادار روی preserve-3d، جهش فنری، برچسب چرخان دوردو') }}
        </p>
    </div>
</section>
