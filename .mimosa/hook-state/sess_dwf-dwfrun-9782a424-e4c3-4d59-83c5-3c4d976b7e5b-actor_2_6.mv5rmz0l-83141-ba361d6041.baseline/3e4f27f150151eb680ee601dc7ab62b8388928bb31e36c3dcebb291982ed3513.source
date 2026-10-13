{{--
    starfield's real scenario: the night-flights hero of "Skyward" — a 2D
    canvas where 180 stars each carry a z depth, stream toward the viewer
    (r and brightness derive from 1/z) and the pointer bends the vanishing
    point with an eased ±26px parallax. A "warp" button triples the approach
    for a beat. The RAF loop parks off screen, reduced motion gets one still
    sky, and everything — loop, observers, pointer listener — is torn down on
    destroy.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $destinations = [
        ['fa' => 'کیوتو', 'en' => 'Kyoto', 'price' => '$612'],
        ['fa' => 'لیسبون', 'en' => 'Lisbon', 'price' => '$318'],
        ['fa' => 'ریکیاویک', 'en' => 'Reykjavík', 'price' => '$289'],
        ['fa' => 'بوئنوس آیرس', 'en' => 'Buenos Aires', 'price' => '$544'],
    ];
@endphp

<style>
    .ambs-root {
        --ambs-ink: #1c1917; --ambs-muted: #78716c;
        --ambs-font: 'Inter', 'Vazirmatn', ui-sans-serif, system-ui, sans-serif;
        display: grid; gap: 1rem; font-family: var(--ambs-font); color: var(--ambs-ink);
    }
    html[data-theme="dark"] .ambs-root { --ambs-ink: #fafaf9; --ambs-muted: #a8a29e; }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .ambs-root { --ambs-ink: #fafaf9; --ambs-muted: #a8a29e; }
    }
    .ambs-stage {
        position: relative; overflow: clip; border-radius: 1.25rem;
        min-block-size: 24rem; display: grid; place-content: center; justify-items: center;
        gap: 1rem; padding: 3rem 1.25rem; text-align: center;
        background: #05070f; color: #e2e8f0;
    }
    .ambs-cv { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; display: block; }
    .ambs-copy { position: relative; z-index: 1; display: grid; justify-items: center; gap: 1rem; }
    .ambs-eyebrow {
        display: inline-flex; align-items: center; gap: .45rem;
        padding: .35rem .85rem; border-radius: 999px;
        background: rgba(226, 232, 240, .08); border: 1px solid rgba(226, 232, 240, .2);
        font-size: .76rem; font-weight: 600; color: #cbd5e1;
    }
    .ambs-eyebrow svg { inline-size: .85rem; block-size: .85rem; stroke: currentColor; stroke-width: 2;
                        fill: none; stroke-linecap: round; stroke-linejoin: round; }
    .ambs-h { max-inline-size: 32rem; margin: 0; font-size: clamp(1.6rem, 4.5vw, 2.4rem);
              font-weight: 700; line-height: 1.25; letter-spacing: -.01em; }
    .ambs-p { max-inline-size: 28rem; margin: 0; color: #94a3b8; font-size: .95rem; line-height: 1.7; }
    .ambs-dests {
        display: flex; flex-wrap: wrap; justify-content: center; gap: .5rem;
        margin-block-start: .35rem;
    }
    .ambs-dest {
        display: inline-flex; align-items: baseline; gap: .45rem;
        padding: .45rem .95rem; border-radius: 999px; cursor: pointer;
        background: rgba(226, 232, 240, .06); border: 1px solid rgba(226, 232, 240, .18);
        color: #e2e8f0; font-size: .8rem;
        transition: background-color .15s ease, border-color .15s ease, translate .15s ease;
    }
    .ambs-dest:hover { background: rgba(226, 232, 240, .14); border-color: rgba(226, 232, 240, .35); translate: 0 -1px; }
    .ambs-dest:focus-visible { outline: 2px solid #7dd3fc; outline-offset: 2px; }
    .ambs-dest small { color: #7dd3fc; font-variant-numeric: tabular-nums; font-weight: 600; }
    .ambs-dock {
        display: flex; flex-wrap: wrap; align-items: center; justify-content: center;
        gap: .5rem 1rem;
    }
    .ambs-warp {
        display: inline-flex; align-items: center; gap: .45rem; padding: .55rem 1.2rem;
        border-radius: 999px; border: 1px solid rgba(125, 211, 252, .35); cursor: pointer;
        background: rgba(125, 211, 252, .1); color: #e2e8f0;
        font: 600 .8rem var(--ambs-font);
        transition: background-color .15s ease;
    }
    .ambs-warp:hover { background: rgba(125, 211, 252, .2); }
    .ambs-warp:focus-visible { outline: 2px solid #7dd3fc; outline-offset: 2px; }
    .ambs-warp svg { inline-size: .9rem; block-size: .9rem; stroke: currentColor; stroke-width: 2;
                     fill: none; stroke-linecap: round; stroke-linejoin: round; }
    .ambs-live { font-size: .74rem; color: #64748b; font-variant-numeric: tabular-nums; }
    .ambs-hint { margin: 0; font: 400 .74rem/1.6 ui-monospace, monospace; color: var(--ambs-muted); text-align: center; }
    @media (prefers-reduced-motion: reduce) {
        .ambs-dest, .ambs-warp { transition: none; }
        .ambs-dest:hover { translate: 0 0; }
    }
</style>

<section class="pg-box" style="gap: 1.25rem"
    x-data="{
        io: null, ro: null, raf: 0, ctx: null,
        stars: [], par: { tx: 0, x: 0, ty: 0, y: 0 },
        visible: true, warpUntil: 0, last: 0, live: 0,
        fa: @js($fa),
        init() {
            const canvas = this.$refs.cv;
            this.ctx = canvas.getContext('2d');
            if (!this.ctx) return;
            this.dpr = Math.min(window.devicePixelRatio || 1, 2);
            this.w = 0; this.h = 0;
            this.mq = matchMedia('(prefers-reduced-motion: reduce)');
            this.onMq = () => this.run();
            this.mq.addEventListener('change', this.onMq);
            this.onMove = (e) => {
                if (this.mq.matches) return;
                const r = this.$refs.stage.getBoundingClientRect();
                this.par.tx = ((e.clientX - r.left) / r.width - 0.5) * 26;
                this.par.ty = ((e.clientY - r.top) / r.height - 0.5) * 26;
            };
            this.$refs.stage.addEventListener('pointermove', this.onMove);
            this.ro = new ResizeObserver(() => { this.seed(); this.run(); });
            this.ro.observe(canvas);
            this.io = new IntersectionObserver((entries) => {
                this.visible = entries[0].isIntersecting;
                this.run();
            }, { threshold: 0.15 });
            this.io.observe(canvas);
            this.seed();
            this.run();
        },
        seed() {
            const canvas = this.$refs.cv;
            this.w = canvas.clientWidth; this.h = canvas.clientHeight;
            canvas.width = Math.max(2, Math.round(this.w * this.dpr));
            canvas.height = Math.max(2, Math.round(this.h * this.dpr));
            this.ctx.setTransform(this.dpr, 0, 0, this.dpr, 0, 0);
            const n = Math.round(180 * Math.min(1, (this.w * this.h) / (900 * 500)));
            this.stars = Array.from({ length: n }, () => ({
                x: (Math.random() - 0.5) * this.w * 2,
                y: (Math.random() - 0.5) * this.h * 2,
                z: Math.random() * this.w,
            }));
        },
        draw(now) {
            const ctx = this.ctx; if (!ctx) return;
            const dt = Math.min((now - this.last) / 16.6, 3); this.last = now;
            this.par.x += (this.par.tx - this.par.x) * 0.06 * dt;
            this.par.y += (this.par.ty - this.par.y) * 0.06 * dt;
            ctx.fillStyle = '#05070f';
            ctx.fillRect(0, 0, this.w, this.h);
            const cx = this.w / 2 + this.par.x, cy = this.h / 2 + this.par.y;
            const speed = 0.9 * (now < this.warpUntil ? 3 : 1);
            let live = 0;
            for (const s of this.stars) {
                s.z -= speed * dt;
                if (s.z <= 1) {
                    s.z = this.w;
                    s.x = (Math.random() - 0.5) * this.w * 2;
                    s.y = (Math.random() - 0.5) * this.h * 2;
                }
                live++;
                const k = 0.5 / s.z;
                const sx = cx + s.x * k * this.w, sy = cy + s.y * k * this.h;
                const depth = 1 - s.z / this.w;
                const r = Math.max(0.4, depth * 2.2);
                const a = Math.min(1, depth * 1.4 + .12);
                ctx.beginPath();
                ctx.arc(sx, sy, r, 0, 6.2832);
                ctx.fillStyle = 'rgba(226,232,240,' + a.toFixed(3) + ')';
                ctx.fill();
            }
            this.live = live;
        },
        run() {
            cancelAnimationFrame(this.raf);
            if (!this.ctx || !this.visible) return;
            if (this.mq.matches) { this.draw(performance.now()); return; }
            const loop = (now) => { this.draw(now); this.raf = requestAnimationFrame(loop); };
            this.last = performance.now();
            this.raf = requestAnimationFrame(loop);
        },
        warp() {
            if (this.mq.matches) return;
            this.warpUntil = performance.now() + 1600;
        },
        get liveLabel() {
            const s = String(this.live);
            const n = this.fa ? s.replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]) : s;
            return this.fa ? n + ' ستاره در پرواز' : n + ' stars in flight';
        },
        destroy() {
            cancelAnimationFrame(this.raf);
            if (this.io) this.io.disconnect();
            if (this.ro) this.ro.disconnect();
            if (this.mq && this.onMq) this.mq.removeEventListener('change', this.onMq);
            if (this.$refs.stage && this.onMove) this.$refs.stage.removeEventListener('pointermove', this.onMove);
            this.ctx = null;
        },
    }">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Stars that fly past the headline', 'ستاره‌هایی که از کنار سرتیتر می‌گذرند') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('One canvas, one projection: every star owns a z, radius and brightness fall out of 1/z and the pointer eases the vanishing point by ±26px. Warp triples the approach for a second and a half. Density scales with canvas area, the loop parks off screen, and reduced motion gets a single still sky.', 'یک بوم، یک تصویرسازی: هر ستاره یک z دارد، شعاع و روشنایی از ۱/z درمی‌آید و موس نقطهٔ گریز را با ±۲۶ پیکسل نرم خم می‌کند. وارپ سرعت را برای یک‌ونیم ثانیه سه برابر می‌کند. تراکم با مساحت بوم مقیاس می‌شود، حلقه بیرون از دید پارک می‌شود و حرکت کاهش‌یافته یک آسمان ساکن می‌گیرد.') }}
        </p>
    </div>

    <div class="ambs-stage" x-ref="stage">
        <canvas class="ambs-cv" x-ref="cv" aria-hidden="true"></canvas>
        <div class="ambs-copy">
            <span class="ambs-eyebrow">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12h20"/><path d="m12 2 10 10-10 10L2 12Z"/></svg>
                {{ $say('Skyward · red-eye fares', 'Skyward · تعرفه‌های شب‌ران') }}
            </span>
            <h4 class="ambs-h">{{ $say('Nowhere is far anymore', 'هیچ‌جا دیگر دور نیست') }}</h4>
            <p class="ambs-p">{{ $say('Board at midnight, land at sunrise. Skyward flies the quiet hours to 42 cities so your first morning is a full one.', 'نیمه‌شب سوار شو، سپیده‌دم پیاده شو. Skyward ساعات آرام را به ۴۲ شهر پرواز می‌کند تا اولین صبحتان کامل باشد.') }}</p>
            <div class="ambs-dests" role="group" aria-label="{{ $say('Night destinations', 'مقصدهای شبانه') }}">
                @foreach ($destinations as $d)
                    <button type="button" class="ambs-dest">
                        {{ $say($d['en'], $d['fa']) }} <small>{{ $d['price'] }}</small>
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <div class="ambs-dock">
        <button type="button" class="ambs-warp" x-on:click="warp()">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
            {{ $say('Warp for 1.5s', 'وارپ برای ۱٫۵ ثانیه') }}
        </button>
        <span class="ambs-live" role="status" x-text="liveLabel"></span>
    </div>

    <p class="ambs-hint">
        {{ $say('2D canvas · star z ∈ (0, w] · r ∝ 1/z · eased ±26px parallax · parks off screen · still sky under reduced motion', 'بوم دوبعدی · z ستاره در بازهٔ (۰ تا عرض) · شعاع متناسب 1/z · پارالکس نرم ±۲۶px · پارک بیرون از دید · آسمان ساکن در حرکت کاهش‌یافته') }}
    </p>
</section>
