{{--
    contour-lines' real scenario: the route planner of "Ridgeline Atlas", a
    guided-trek shop — eight contour bands drawn as wavy SVG paths that drift
    and phase on a slow RAF loop (each band mixes two sines with its own
    wavelength and speed, so the map never repeats). Three summit dots ride
    their bands; hover or focus retunes the band stroke to the peak's
    elevation colour and reads the summit out in the corner card. The loop
    parks off screen, reduced motion gets one still map, and every listener
    is torn down on destroy.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $peaks = [
        ['fa' => 'ماترهورن', 'en' => 'Matterhorn', 'range' => ['fa' => 'رشته‌الپ', 'en' => 'The Alps'], 'm' => ['fa' => '۴٬۴۷۸', 'en' => '4,478'], 'color' => '#d97706', 'band' => 5, 'x' => 320],
        ['fa' => 'دنالی', 'en' => 'Denali', 'range' => ['fa' => 'آلاسکا', 'en' => 'Alaska Range'], 'm' => ['fa' => '۶٬۱۹۰', 'en' => '6,190'], 'color' => '#ea580c', 'band' => 6, 'x' => 660],
        ['fa' => 'آکونکاگوا', 'en' => 'Aconcagua', 'range' => ['fa' => 'آند', 'en' => 'The Andes'], 'm' => ['fa' => '۶٬۹۶۱', 'en' => '6,961'], 'color' => '#dc2626', 'band' => 7, 'x' => 950],
    ];
@endphp

<style>
    .ambc-root {
        --ambc-ink: #1c1917; --ambc-muted: #78716c;
        --ambc-font: 'Inter', 'Vazirmatn', ui-sans-serif, system-ui, sans-serif;
        display: grid; gap: 1rem; font-family: var(--ambc-font); color: var(--ambc-ink);
    }
    html[data-theme="dark"] .ambc-root { --ambc-ink: #fafaf9; --ambc-muted: #a8a29e; }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .ambc-root { --ambc-ink: #fafaf9; --ambc-muted: #a8a29e; }
    }
    .ambc-stage {
        --ambc-bg: #f8fafc; --ambc-line: #0f766e; --ambc-line-alpha: .5;
        --ambc-paper: #e2e8f0; --ambc-ink2: #0f172a; --ambc-sub: #475569;
        --ambc-chip: rgba(15, 23, 42, .06); --ambc-chip-border: rgba(15, 23, 42, .16);
        --ambc-dot: #0f172a; --ambc-hot: #d97706;
        position: relative; overflow: clip; border-radius: 1.25rem;
        min-block-size: 24rem; display: grid; place-content: center; justify-items: center;
        gap: 1rem; padding: 3rem 1.25rem; text-align: center;
        background:
            radial-gradient(140% 100% at 50% 0%, color-mix(in oklab, var(--ambc-bg) 88%, white), var(--ambc-bg) 70%);
        color: var(--ambc-ink2);
    }
    html[data-theme="dark"] .ambc-stage {
        --ambc-bg: #071019; --ambc-line: #5eead4; --ambc-line-alpha: .42;
        --ambc-paper: #0b1c26; --ambc-ink2: #ecfeff; --ambc-sub: #99f6e4;
        --ambc-chip: rgba(94, 234, 212, .1); --ambc-chip-border: rgba(94, 234, 212, .3);
        --ambc-dot: #f0fdfa;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .ambc-stage {
            --ambc-bg: #071019; --ambc-line: #5eead4; --ambc-line-alpha: .42;
            --ambc-paper: #0b1c26; --ambc-ink2: #ecfeff; --ambc-sub: #99f6e4;
            --ambc-chip: rgba(94, 234, 212, .1); --ambc-chip-border: rgba(94, 234, 212, .3);
            --ambc-dot: #f0fdfa;
        }
    }
    .ambc-svg { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; display: block; }
    .ambc-l { fill: none; stroke: var(--ambc-line); stroke-linecap: round; stroke-linejoin: round; }
    .ambc-l.hot { stroke: var(--ambc-hot); stroke-opacity: .95; }
    html[data-theme="dark"] .ambc-svg { filter: drop-shadow(0 0 5px rgba(94, 234, 212, .18)); }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .ambc-svg { filter: drop-shadow(0 0 5px rgba(94, 234, 212, .18)); }
    }
    .ambc-peak-dot { fill: var(--ambc-dot); cursor: pointer; transition: r .15s ease; }
    .ambc-peak-dot:hover, .ambc-peak-dot:focus-visible { r: 8; }
    .ambc-peak-dot:focus-visible { outline: none; stroke: var(--ambc-hot); stroke-width: 3; }
    .ambc-peak-halo { fill: none; stroke: var(--ambc-hot); stroke-width: 2; opacity: 0;
                       transition: opacity .15s ease; }
    .ambc-peak.hot .ambc-peak-halo { opacity: .8; }
    .ambc-copy { position: relative; z-index: 1; display: grid; justify-items: center; gap: 1rem; }
    .ambc-eyebrow {
        display: inline-flex; align-items: center; gap: .45rem;
        padding: .35rem .85rem; border-radius: 999px;
        background: var(--ambc-chip); border: 1px solid var(--ambc-chip-border);
        font-size: .76rem; font-weight: 600; color: var(--ambc-sub);
    }
    .ambc-eyebrow svg { inline-size: .85rem; block-size: .85rem; stroke: currentColor; stroke-width: 2;
                        fill: none; stroke-linecap: round; stroke-linejoin: round; }
    .ambc-h { max-inline-size: 32rem; margin: 0; font-size: clamp(1.6rem, 4.5vw, 2.4rem);
              font-weight: 700; line-height: 1.25; letter-spacing: -.01em; }
    .ambc-p { max-inline-size: 28rem; margin: 0; color: var(--ambc-sub); font-size: .95rem; line-height: 1.7; }
    .ambc-cta-row { display: flex; flex-wrap: wrap; justify-content: center; gap: .6rem; margin-block-start: .4rem; }
    .ambc-cta {
        display: inline-flex; align-items: center; gap: .45rem; padding: .65rem 1.3rem;
        border-radius: 999px; border: 1px solid transparent; cursor: pointer;
        font: 600 .85rem var(--ambc-font); text-decoration: none;
        transition: translate .15s ease, background-color .15s ease;
    }
    .ambc-cta:hover { translate: 0 -1px; }
    .ambc-cta:focus-visible { outline: 2px solid var(--ambc-line); outline-offset: 2px; }
    .ambc-cta-primary { background: var(--ambc-ink2); color: var(--ambc-bg); }
    .ambc-cta-ghost { background: var(--ambc-chip); color: var(--ambc-ink2); border-color: var(--ambc-chip-border); }
    .ambc-cta svg { inline-size: .9rem; block-size: .9rem; stroke: currentColor; stroke-width: 2;
                    fill: none; stroke-linecap: round; stroke-linejoin: round; }
    .ambc-card {
        position: absolute; inset-block-start: .9rem; inset-inline-end: .9rem; z-index: 2;
        display: inline-flex; align-items: center; gap: .55rem;
        max-inline-size: 13rem; padding: .5rem .85rem; border-radius: 999px;
        background: var(--ambc-chip); border: 1px solid var(--ambc-chip-border);
        backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
        color: var(--ambc-ink2); font-size: .76rem; font-weight: 600;
        pointer-events: none; text-align: start;
    }
    .ambc-card-bar { inline-size: .55rem; block-size: .55rem; border-radius: 50%;
                     background: var(--ambc-hot); flex: none; }
    .ambc-card small { display: block; font-weight: 400; color: var(--ambc-sub); font-size: .7rem; }
    @media (max-width: 400px) { .ambc-card { max-inline-size: 10.5rem; font-size: .7rem; } }
    .ambc-hint { margin: 0; font: 400 .74rem/1.6 ui-monospace, monospace; color: var(--ambc-muted); text-align: center; }
    @media (prefers-reduced-motion: reduce) {
        .ambc-peak-dot, .ambc-cta { transition: none; }
        .ambc-cta:hover, .ambc-peak-dot:hover { translate: 0 0; }
    }
</style>

<section class="pg-box" style="gap: 1.25rem"
    x-data="{
        io: null, raf: 0, t: 0, last: 0, tick: 0,
        bands: [], paths: [], peaks: [], peakEls: [],
        visible: true, hover: -1, mq: null, onMq: null,
        fa: @js($fa),
        init() {
            const NS = 'http://www.w3.org/2000/svg';
            const g = this.$refs.g;
            this.bands = Array.from({ length: 8 }, (_, i) => ({
                y: 405 - i * 32, amp: 26 - i * 2.2,
                k1: (2 * Math.PI) / (300 - i * 12), k2: (2 * Math.PI) / (96 + i * 7),
                s1: 0.0011 + i * 0.00007, s2: 0.0019 - i * 0.00006, o2: i * 1.7,
            }));
            for (let i = 0; i < this.bands.length; i++) {
                const p = document.createElementNS(NS, 'path');
                p.setAttribute('class', 'ambc-l');
                p.setAttribute('stroke-width', (1 + i * 0.35).toFixed(2));
                p.setAttribute('stroke-opacity', (0.35 + i * 0.09).toFixed(2));
                g.appendChild(p);
                this.paths.push(p);
            }
            this.peaks = {{ json_encode($peaks, JSON_UNESCAPED_UNICODE) }};
            this.peaks.forEach((pk, j) => {
                const grp = document.createElementNS(NS, 'g');
                grp.setAttribute('class', 'ambc-peak');
                const halo = document.createElementNS(NS, 'circle');
                halo.setAttribute('class', 'ambc-peak-halo');
                halo.setAttribute('r', '13');
                const dot = document.createElementNS(NS, 'circle');
                dot.setAttribute('class', 'ambc-peak-dot');
                dot.setAttribute('r', '5');
                dot.setAttribute('tabindex', '0');
                dot.setAttribute('role', 'img');
                const label = this.fa ? pk.fa : pk.en;
                const range = this.fa ? pk.range.fa : pk.range.en;
                const meters = this.fa ? pk.m.fa : pk.m.en;
                dot.setAttribute('aria-label', label + ' · ' + range + ' · ' + meters + ' m');
                grp.appendChild(halo); grp.appendChild(dot);
                g.appendChild(grp);
                this.peakEls.push({ grp, halo, dot, band: pk.band, x: pk.x });
                dot.addEventListener('pointerenter', () => this.setHover(j));
                dot.addEventListener('focus', () => this.setHover(j));
                dot.addEventListener('pointerleave', () => this.setHover(-1));
                dot.addEventListener('blur', () => this.setHover(-1));
            });
            this.mq = matchMedia('(prefers-reduced-motion: reduce)');
            this.onMq = () => this.run();
            this.mq.addEventListener('change', this.onMq);
            this.io = new IntersectionObserver((entries) => {
                this.visible = entries[0].isIntersecting;
                this.run();
            }, { threshold: 0.15 });
            this.io.observe(this.$refs.stage);
            this.draw(0);
            this.run();
        },
        yAt(b, x, t) {
            return b.y + b.amp * Math.sin(b.k1 * x + t * b.s1)
                       + (b.amp * 0.35) * Math.sin(b.k2 * x + t * b.s2 + b.o2);
        },
        draw(t) {
            const x0 = -60, x1 = 1260, step = 24;
            for (let i = 0; i < this.bands.length; i++) {
                const b = this.bands[i];
                let d = '';
                for (let x = x0; x <= x1; x += step) {
                    d += (x === x0 ? 'M' : 'L') + x.toFixed(0) + ' ' + this.yAt(b, x, t).toFixed(1);
                }
                this.paths[i].setAttribute('d', d);
            }
            for (const pk of this.peakEls) {
                const y = this.yAt(this.bands[pk.band], pk.x, t);
                pk.grp.setAttribute('transform', 'translate(' + pk.x + ' ' + y.toFixed(1) + ')');
            }
        },
        run() {
            cancelAnimationFrame(this.raf);
            if (!this.visible) return;
            if (this.mq.matches) { this.draw(this.t); return; }
            const loop = (now) => {
                const dt = Math.min(now - this.last, 48);
                this.last = now; this.t += dt;
                if ((this.tick = (this.tick + 1) % 2) === 0) this.draw(this.t);
                this.raf = requestAnimationFrame(loop);
            };
            this.last = performance.now();
            this.raf = requestAnimationFrame(loop);
        },
        setHover(j) {
            if (this.hover === j) return;
            this.hover = j;
            const stage = this.$refs.stage;
            this.paths.forEach((p) => p.classList.remove('hot'));
            this.peakEls.forEach((pk) => pk.grp.classList.remove('hot'));
            if (j >= 0) {
                const pk = this.peaks[j];
                stage.style.setProperty('--ambc-hot', pk.color);
                this.paths[pk.band].classList.add('hot');
                this.paths[Math.max(0, pk.band - 1)].classList.add('hot');
                this.peakEls[j].grp.classList.add('hot');
            } else {
                stage.style.removeProperty('--ambc-hot');
            }
        },
        get cardLabel() {
            if (this.hover < 0) {
                return this.fa ? 'نقطه‌ها را هاور کنید' : 'Hover a summit dot';
            }
            const pk = this.peaks[this.hover];
            return (this.fa ? pk.fa : pk.en) + ' · ' + (this.fa ? pk.m.fa : pk.m.en) + ' m';
        },
        get cardRange() {
            if (this.hover < 0) return this.fa ? 'ارتفاع‌ها از اطلس تور' : 'Altitudes from the trek atlas';
            const pk = this.peaks[this.hover];
            return this.fa ? pk.range.fa : pk.range.en;
        },
        destroy() {
            cancelAnimationFrame(this.raf);
            if (this.io) this.io.disconnect();
            if (this.mq && this.onMq) this.mq.removeEventListener('change', this.onMq);
            this.peakEls.forEach((pk) => {
                if (!pk.dot) return;
                pk.dot.replaceWith(pk.dot.cloneNode(true));
            });
            this.paths = []; this.peakEls = [];
        },
    }">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A map that never stops surveying', 'نقشه‌ای که از نقشه‌برداری دست نمی‌کشد') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Eight contour bands, each a mix of two sines with its own wavelength and speed, redraw ~30 times a second on one SVG — the ridges drift and phase like weather over a paper map. Hover or focus a summit dot and its band retunes to the elevation colour while the corner card reads the peak out. The survey parks off screen and settles to one still map under reduced motion.', 'هشت نوار تراز، هرکدام ترکیب دو سینوس با طول‌موج و سرعت خودش، حدود ۳۰ بار در ثانیه روی یک SVG بازکشیده می‌شوند — خط‌الرأس‌ها مثل هوای روی نقشهٔ کاغذی جابه‌جا و فاز می‌گیرند. هاور یا فوکوس روی نقطهٔ قله، نوارش را به رنگ ارتفاع می‌برد و کارت گوشه قله را می‌خواند. نقشه‌برداری بیرون از دید پارک می‌شود و در حرکت کاهش‌یافته به یک نقشهٔ ساکن می‌نشیند.') }}
        </p>
    </div>

    <div class="ambc-stage" x-ref="stage" x-bind:data-hover="hover">
        <svg class="ambc-svg" viewBox="0 0 1200 480" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
            <g x-ref="g"></g>
        </svg>
        <div class="ambc-copy">
            <span class="ambc-eyebrow">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m2 20 6-11 4 7 3-4 7 8Z"/><path d="M14 6.5a2.5 2.5 0 1 0 5 0 2.5 2.5 0 1 0-5 0"/></svg>
                {{ $say('Ridgeline Atlas · guided treks', 'Ridgeline Atlas · تورهای راهنما') }}
            </span>
            <h4 class="ambc-h">{{ $say('Read the mountain like a map', 'کوه را مثل نقشه بخوان') }}</h4>
            <p class="ambc-p">{{ $say('Forty-two guided routes across the Alps, the Andes and Alaska — gradient profiles, camp water and retreat lines drawn by people who walked them first.', 'چهل‌ودو مسیر راهنما در آلپ، آند و آلاسکا — پروفایل شیب، آبِ اردوگاه و مسیر عقب‌نشینی، ترسیم‌شده توسط کسانی که اول خودشان رفتند.') }}</p>
            <div class="ambc-cta-row">
                <a class="ambc-cta ambc-cta-primary" href="#">{{ $say('Browse the atlas', 'مرور اطلس') }}</a>
                <a class="ambc-cta ambc-cta-ghost" href="#">
                    {{ $say('Download route GPX', 'دریافت GPX مسیر') }}
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
                </a>
            </div>
        </div>
        <p class="ambc-card" role="status" aria-live="polite">
            <span class="ambc-card-bar" aria-hidden="true"></span>
            <span><span x-text="cardLabel"></span><small x-text="cardRange"></small></span>
        </p>
    </div>

    <p class="ambc-hint">
        {{ $say('8 contour bands · two sines per band · ~30fps redraw on one SVG · elevation-coloured hover · parks off screen · still map under reduced motion', '۸ نوار تراز · دو سینوس در هر نوار · بازکشیدهٔ ~۳۰fps روی یک SVG · هاورِ رنگارنگ ارتفاع · پارک بیرون از دید · نقشهٔ ساکن در حرکت کاهش‌یافته') }}
    </p>
</section>
