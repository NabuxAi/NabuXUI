{{--
    image-trail's real scenario: the work index of an award-sweeping studio
    site — five projects as big links plus two marked hotspots on the
    canvas. Moving the pointer drags a chain of nine image cards where each
    card lerps toward its predecessor (never the pointer), so the chain
    fans open while the hand moves and fades card by card (55ms stair) when
    it stops for 140ms. Hovering or focusing a project arms its artwork;
    a tap or focus fans the chain around the link. The RAF loop parks off
    screen via IntersectionObserver; under reduced motion the chain is
    dropped and one static card parks beside the focused link.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    // Five global studio projects; scene N is the artwork that follows.
    $projects = [
        ['fa' => 'آرورا استودیو', 'en' => 'Aurora Studio', 'kind' => ['fa' => 'هویت بصری', 'en' => 'Brand identity']],
        ['fa' => 'اطلس شمال', 'en' => 'Nordic Atlas', 'kind' => ['fa' => 'وب‌سایت تعاملی', 'en' => 'Interactive site']],
        ['fa' => 'مریدین فیلمز', 'en' => 'Meridian Films', 'kind' => ['fa' => 'تیتراژ و پوستر', 'en' => 'Titles & poster']],
        ['fa' => 'دریفت تایپ', 'en' => 'Drift Type', 'kind' => ['fa' => 'قلم اختصاصی', 'en' => 'Custom typeface']],
        ['fa' => 'خلیج سولستیس', 'en' => 'Solstice Bay', 'kind' => ['fa' => 'کمپین تابستانی', 'en' => 'Summer campaign']],
    ];
@endphp

<style>
    .hvt-root {
        --hvt-fg: #1c1917; --hvt-muted: #78716c; --hvt-border: #e7e5e4;
        --hvt-bg: #fafaf9; --hvt-accent: #4f46e5;
        --hvt-font: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif;
        display: grid; gap: 1rem; font-family: var(--hvt-font); color: var(--hvt-fg);
    }
    html[data-theme="dark"] .hvt-root {
        --hvt-fg: #fafaf9; --hvt-muted: #a8a29e; --hvt-border: #292524;
        --hvt-bg: #1c1917; --hvt-accent: #a5b4fc;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .hvt-root {
            --hvt-fg: #fafaf9; --hvt-muted: #a8a29e; --hvt-border: #292524;
            --hvt-bg: #1c1917; --hvt-accent: #a5b4fc;
        }
    }
    .hvt-stage {
        position: relative; overflow: clip; min-block-size: 25rem;
        border: 1px solid var(--hvt-border); border-radius: 1.25rem;
        background:
            radial-gradient(1px 1px at 20% 30%, color-mix(in oklab, var(--hvt-fg) 22%, transparent) 50%, transparent 51%),
            radial-gradient(1px 1px at 70% 65%, color-mix(in oklab, var(--hvt-fg) 22%, transparent) 50%, transparent 51%);
        background-size: 100% 100%, 100% 100%;
        background-color: var(--hvt-bg);
        display: grid; grid-template-columns: minmax(0, 1.15fr) minmax(0, .85fr);
        cursor: crosshair; touch-action: pan-y;
    }
    .hvt-stage::before {
        content: ''; position: absolute; inset: 0; pointer-events: none;
        background-image: radial-gradient(color-mix(in oklab, var(--hvt-fg) 14%, transparent) 1px, transparent 1px);
        background-size: 26px 26px; opacity: .5;
    }
    .hvt-list {
        position: relative; z-index: 1; display: grid; align-content: center; gap: .1rem;
        margin: 0; padding: 1.4rem 1.1rem; list-style: none;
    }
    .hvt-row {
        display: flex; align-items: baseline; gap: .8rem; padding: .58rem .35rem;
        border-block-end: 1px solid color-mix(in oklab, var(--hvt-border) 70%, transparent);
    }
    .hvt-no { min-inline-size: 1.7rem; font-size: .72rem; color: var(--hvt-muted);
              font-variant-numeric: tabular-nums; }
    .hvt-link {
        flex: 1; text-decoration: none; color: inherit; font-weight: 650; font-size: 1.06rem;
        transition: color .18s ease, letter-spacing .18s ease;
        outline: none;
    }
    .hvt-link small { margin-inline-start: .5rem; font-weight: 400; font-size: .74rem;
                      color: var(--hvt-muted); letter-spacing: 0; }
    .hvt-row:hover .hvt-link, .hvt-link:focus-visible { color: var(--hvt-accent); }
    .hvt-row[data-armed="true"] .hvt-no { color: var(--hvt-accent); }
    .hvt-mark {
        position: relative; z-index: 1; display: grid; align-content: center; justify-items: center;
        gap: 1.4rem; padding: 1.2rem; border-inline-start: 1px dashed color-mix(in oklab, var(--hvt-border) 80%, transparent);
    }
    .hvt-dot {
        position: relative; inline-size: 2.4rem; aspect-ratio: 1; border-radius: 50%;
        border: 1px solid var(--hvt-border); background: var(--hvt-bg); cursor: pointer;
        display: grid; place-items: center; color: var(--hvt-muted);
        transition: border-color .2s ease, color .2s ease, scale .2s ease;
        outline: none;
    }
    .hvt-dot::after {
        content: ''; inline-size: .55rem; aspect-ratio: 1; border-radius: 50%;
        background: currentColor; transition: scale .2s ease;
    }
    .hvt-dot:hover, .hvt-dot:focus-visible { border-color: var(--hvt-accent); color: var(--hvt-accent); scale: 1.12; }
    .hvt-dot span {
        position: absolute; inset-block-start: calc(100% + .3rem); inset-inline-start: 50%;
        translate: -50% 0; white-space: nowrap; font-size: .68rem; color: var(--hvt-muted);
    }
    html[dir="rtl"] .hvt-dot span { translate: 50% 0; }
    .hvt-card {
        position: absolute; inset-block-start: 0; inset-inline-start: 0;
        inline-size: 10.4rem; aspect-ratio: 3 / 2; border-radius: .8rem; overflow: clip;
        pointer-events: none; opacity: 0; z-index: 3;
        box-shadow: 0 18px 34px -18px rgba(0, 0, 0, .45), 0 0 0 1px rgba(255, 255, 255, .08) inset;
        border: 1px solid rgba(255, 255, 255, .14);
        transition: opacity .38s ease; transition-delay: calc(var(--i) * 55ms);
        will-change: transform;
    }
    .hvt-card svg { display: block; inline-size: 100%; block-size: 100%; }
    .hvt-card svg [data-s] { display: none; }
    .hvt-card[data-scene="0"] svg [data-s="0"], .hvt-card[data-scene="1"] svg [data-s="1"],
    .hvt-card[data-scene="2"] svg [data-s="2"], .hvt-card[data-scene="3"] svg [data-s="3"],
    .hvt-card[data-scene="4"] svg [data-s="4"] { display: block; }
    .hvt-stage[data-live="true"] .hvt-card { opacity: 1; }
    .hvt-hint { margin: 0; font-size: .74rem; color: var(--hvt-muted); text-align: center; }
    @media (max-width: 640px) {
        .hvt-stage { grid-template-columns: 1fr; min-block-size: 30rem; }
        .hvt-mark { display: flex; justify-content: center; gap: 2.6rem; padding: .8rem;
                    border-inline-start: 0; border-block-start: 1px dashed color-mix(in oklab, var(--hvt-border) 80%, transparent); }
        .hvt-card { inline-size: 7.6rem; border-radius: .65rem; }
        .hvt-list { align-content: start; padding-block-start: 1rem; }
    }
    @media (prefers-reduced-motion: reduce) {
        .hvt-card { transition: none; }
        .hvt-link, .hvt-dot { transition: none; }
        .hvt-dot:hover, .hvt-dot:focus-visible { scale: 1; }
    }
</style>
@php
    // One shared defs block: five gradients, ids prefixed hvt-.
    $grads = [
        ['#312e81', '#0ea5e9'], ['#0f172a', '#f59e0b'], ['#083344', '#22d3ee'],
        ['#7c2d12', '#fbbf24'], ['#1e1b4b', '#e879f9'],
    ];
@endphp
<section class="pg-box hvt-root" style="gap: 1rem"
    x-data="{
        raf: 0, io: null, t: 0, mq: null, rtl: false, visible: false,
        px: 0, py: 0, cards: [], live: false, liveT: 0, hold: 0, armed: 0,
        els: [],
        init() {
            const stage = this.$refs.stage;
            this.mq = matchMedia('(prefers-reduced-motion: reduce)');
            this.rtl = getComputedStyle(document.documentElement).direction === 'rtl';
            this.px = stage.clientWidth / 2; this.py = stage.clientHeight / 2;
            this.cards = Array.from({ length: 9 }, () => ({ x: this.px, y: this.py }));
            this.els = Array.from(stage.querySelectorAll('.hvt-card'));
            this.io = new IntersectionObserver((entries) => {
                this.visible = entries[0].isIntersecting;
                if (!this.visible) { this.live = false; cancelAnimationFrame(this.raf); }
            }, { threshold: 0.05 });
            this.io.observe(stage);
        },
        move(e) {
            if (this.mq.matches) return;
            const r = this.$refs.stage.getBoundingClientRect();
            this.px = e.clientX - r.left; this.py = e.clientY - r.top;
            this.live = true; this.liveT = performance.now(); this.hold = 0;
            this.run();
        },
        run() {
            cancelAnimationFrame(this.raf);
            if (!this.live || !this.visible) return;
            const loop = (now) => {
                this.apply();
                if (now > Math.max(this.liveT + 140, this.hold)) { this.live = false; return; }
                this.raf = requestAnimationFrame(loop);
            };
            this.raf = requestAnimationFrame(loop);
        },
        apply() {
            let tx = this.px, ty = this.py;
            const cw = this.els[0] ? this.els[0].offsetWidth : 166;
            const ch = this.els[0] ? this.els[0].offsetHeight : 111;
            for (let i = 0; i < this.cards.length; i++) {
                const c = this.cards[i];
                const k = Math.max(.06, .3 - i * .022);
                c.x += (tx - c.x) * k; c.y += (ty - c.y) * k;
                tx = c.x; ty = c.y;
                const rot = Math.max(-16, Math.min(16, (this.px - c.x) * .9));
                const sc = (1 - i * .045).toFixed(3);
                this.els[i].style.zIndex = String(30 - i);
                this.els[i].style.transform =
                    'translate3d(' + (c.x - cw / 2) + 'px,' + (c.y - ch / 2) + 'px,0) rotate(' + rot.toFixed(2) + 'deg) scale(' + sc + ')';
            }
        },
        arm(i) { this.armed = i; },
        pin(el, i) {
            const stage = this.$refs.stage;
            const r = stage.getBoundingClientRect();
            const b = el.getBoundingClientRect();
            this.arm(i);
            const cx = b.left + b.width / 2 - r.left;
            const cy = b.top + b.height / 2 - r.top;
            clearTimeout(this.t);
            if (this.mq.matches) {
                // Reduced motion: one static card beside the link, no chase.
                const c = this.cards[0];
                c.x = cx + (this.rtl ? -120 : 120); c.y = cy;
                this.els.forEach((elc, j) => { if (j > 0) { this.cards[j].x = c.x; this.cards[j].y = c.y; } });
                this.px = c.x; this.py = c.y; this.apply();
                this.live = true;
                this.t = setTimeout(() => { this.live = false; }, 1700);
                return;
            }
            this.px = cx; this.py = cy;
            const dir = this.rtl ? -1 : 1;
            for (let j = 0; j < this.cards.length; j++) {
                this.cards[j].x = cx + (j - 4) * 24 * dir;
                this.cards[j].y = cy - Math.sin((j / 8) * Math.PI) * 64;
            }
            this.apply();
            this.live = true; this.hold = performance.now() + 1500;
            this.run();
        },
        destroy() {
            cancelAnimationFrame(this.raf);
            clearTimeout(this.t);
            if (this.io) this.io.disconnect();
        },
    }">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A gallery that chases the cursor', 'گالری‌ای که دنبال موس می‌آید') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Nine cards form a chain: each one lerps toward the card before it — never the pointer — so the fan opens with your movement and, 140ms after you stop, the cards fade one after another on a 55ms stair. Hovering a project arms its artwork; a tap or Enter fans the chain around the link.', 'نه کارت یک زنجیره می‌سازند: هر کارت به کارتِ قبلی خودش می‌چسبد — نه به موس — پس بازوی زنجیره با حرکت شما باز می‌شود و ۱۴۰ میلی‌ثانیه بعد از توقف، کارت‌ها با پلکانی ۵۵ میلی‌ثانیه‌ای پشت سر هم محو می‌شوند. هاور روی هر پروژه صحنهٔ آن را مسلح می‌کند؛ لمس یا Enter زنجیره را دور لینک باز می‌کند.') }}
        </p>
    </div>

    <div class="hvt-stage" x-ref="stage" x-bind:data-live="live ? 'true' : 'false'"
        x-on:pointermove="move($event)"
        x-on:pointerleave="live = false">
        <ul class="hvt-list">
            @foreach ($projects as $i => $project)
                <li class="hvt-row" x-bind:data-armed="armed === {{ $i }} ? 'true' : 'false'">
                    <span class="hvt-no">{{ $fa ? strtr(sprintf('%02d', $i + 1), ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : sprintf('%02d', $i + 1) }}</span>
                    <a class="hvt-link" href="#{{ strtolower(str_replace(' ', '-', $project['en'])) }}"
                        x-on:pointerenter="arm({{ $i }})"
                        x-on:focus="pin($el, {{ $i }})"
                        x-on:click.prevent="pin($el, {{ $i }})">
                        {{ $say($project['en'], $project['fa']) }}<small>{{ $say($project['kind']['en'], $project['kind']['fa']) }}</small>
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="hvt-mark" aria-hidden="false">
            @foreach ([0, 2] as $m)
                <button type="button" class="hvt-dot"
                    x-on:pointerenter="arm({{ $m }})"
                    x-on:focus="pin($el, {{ $m }})"
                    x-on:click.prevent="pin($el, {{ $m }})">
                    <span>{{ $say($projects[$m]['en'], $projects[$m]['fa']) }}</span>
                </button>
            @endforeach
        </div>

        @foreach (range(0, 8) as $i)
            <div class="hvt-card" style="--i: {{ $i }}" x-bind:data-scene="String((armed + {{ $i }}) % 5)">
                <svg viewBox="0 0 168 112" aria-hidden="true" focusable="false">
                    {{-- scene 0 — aurora --}}
                    <g data-s="0">
                        <rect width="168" height="112" fill="url(#hvt-g0)"/>
                        <path d="M8 84 Q44 30 78 66 Q104 92 128 58" fill="none" stroke="#34d399" stroke-opacity=".65" stroke-width="7" stroke-linecap="round"/>
                        <path d="M22 96 Q58 48 92 78" fill="none" stroke="#a5b4fc" stroke-opacity=".55" stroke-width="5" stroke-linecap="round"/>
                        <circle cx="140" cy="24" r="7" fill="#e0e7ff"/>
                        <circle cx="30" cy="26" r="1.6" fill="#e0e7ff"/><circle cx="58" cy="16" r="1.2" fill="#e0e7ff"/><circle cx="96" cy="30" r="1.4" fill="#e0e7ff"/>
                    </g>
                    {{-- scene 1 — mountain dawn --}}
                    <g data-s="1">
                        <rect width="168" height="112" fill="url(#hvt-g1)"/>
                        <circle cx="118" cy="42" r="15" fill="#fde68a"/>
                        <path d="M0 112 L46 46 L80 90 L108 56 L168 112 Z" fill="#0b1220"/>
                        <path d="M46 46 L60 66 L52 70 L38 62 Z" fill="#e2e8f0" opacity=".85"/>
                    </g>
                    {{-- scene 2 — cold sea --}}
                    <g data-s="2">
                        <rect width="168" height="112" fill="url(#hvt-g2)"/>
                        <circle cx="48" cy="38" r="12" fill="#cffafe"/>
                        <path d="M0 72 Q28 62 56 72 T112 72 T168 72 V112 H0 Z" fill="#155e75" opacity=".85"/>
                        <path d="M0 88 Q28 80 56 88 T112 88 T168 88 V112 H0 Z" fill="#0e3a4d"/>
                    </g>
                    {{-- scene 3 — desert dusk --}}
                    <g data-s="3">
                        <rect width="168" height="112" fill="url(#hvt-g3)"/>
                        <circle cx="84" cy="44" r="14" fill="#fff7ed"/>
                        <path d="M0 84 Q42 58 84 80 T168 76 V112 H0 Z" fill="#9a3412"/>
                        <path d="M0 100 Q56 84 112 98 T168 96 V112 H0 Z" fill="#7c2d12"/>
                    </g>
                    {{-- scene 4 — violet night city --}}
                    <g data-s="4">
                        <rect width="168" height="112" fill="url(#hvt-g4)"/>
                        <circle cx="132" cy="26" r="8" fill="#f5d0fe"/>
                        <rect x="14" y="58" width="20" height="54" fill="#312e81"/>
                        <rect x="40" y="44" width="16" height="68" fill="#3730a3"/>
                        <rect x="62" y="66" width="24" height="46" fill="#312e81"/>
                        <rect x="92" y="52" width="14" height="60" fill="#4338ca"/>
                        <g fill="#f0abfc" opacity=".9">
                            <rect x="19" y="66" width="4" height="4"/><rect x="26" y="76" width="4" height="4"/>
                            <rect x="45" y="54" width="4" height="4"/><rect x="68" y="74" width="4" height="4"/><rect x="96" y="62" width="4" height="4"/>
                        </g>
                    </g>
                </svg>
            </div>
        @endforeach
    </div>

    <svg width="0" height="0" style="position: absolute" aria-hidden="true" focusable="false">
        <defs>
            @foreach ($grads as $g => $pair)
                <linearGradient id="hvt-g{{ $g }}" x1="0" y1="0" x2="1" y2="1">
                    <stop offset="0" stop-color="{{ $pair[0] }}"/>
                    <stop offset="1" stop-color="{{ $pair[1] }}"/>
                </linearGradient>
            @endforeach
        </defs>
    </svg>

    <p class="hvt-hint">
        {{ $say('lerp chain .30 − i×.022 · 140ms idle → 55ms fade stair · parked off screen · chain dropped under reduced motion', 'زنجیرهٔ lerp با ‎.30 − i×.022‎ · توقف ۱۴۰ms ← پلکانی ۵۵ms محو · پارک خارج از دید · زنجیره در حرکت کاهش‌یافته حذف می‌شود') }}
    </p>
</section>
