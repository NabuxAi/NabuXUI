{{--
    confetti-burst's real scenario: the Nimbus Mail beta signup — a tiny email
    form whose success fires a particle confetti cannon on a 2D canvas:
    pieces leap from the submit button with gravity, air drag, spin and the
    theme's five-colour palette; a density slider (40–240) tunes the blast and
    the "celebrate again" button re-fires it. The cannon parks off screen,
    stays quiet under reduced motion, and every frame, observer and timer is
    cleaned up on destroy.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<style>
    .mcfb-root {
        --mcfb-fg: #1c1917; --mcfb-muted: #78716c; --mcfb-border: #e7e5e4;
        --mcfb-bg: #ffffff; --mcfb-soft: #f5f5f4; --mcfb-accent: #4f46e5;
        --mcfb-danger: #e11d48; --mcfb-ok: #059669;
        --mcfb-c1: #f43f5e; --mcfb-c2: #f59e0b; --mcfb-c3: #10b981;
        --mcfb-c4: #3b82f6; --mcfb-c5: #a855f7;
        display: grid; gap: 1rem;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, system-ui, sans-serif;
        color: var(--mcfb-fg);
    }
    html[data-theme="dark"] .mcfb-root {
        --mcfb-fg: #fafaf9; --mcfb-muted: #a8a29e; --mcfb-border: #292524;
        --mcfb-bg: #1c1917; --mcfb-soft: #292524; --mcfb-accent: #a5b4fc;
        --mcfb-danger: #fb7185; --mcfb-ok: #34d399;
        --mcfb-c1: #fb7185; --mcfb-c2: #fbbf24; --mcfb-c3: #34d399;
        --mcfb-c4: #60a5fa; --mcfb-c5: #c084fc;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .mcfb-root {
            --mcfb-fg: #fafaf9; --mcfb-muted: #a8a29e; --mcfb-border: #292524;
            --mcfb-bg: #1c1917; --mcfb-soft: #292524; --mcfb-accent: #a5b4fc;
            --mcfb-danger: #fb7185; --mcfb-ok: #34d399;
            --mcfb-c1: #fb7185; --mcfb-c2: #fbbf24; --mcfb-c3: #34d399;
            --mcfb-c4: #60a5fa; --mcfb-c5: #c084fc;
        }
    }
    .mcfb-stage {
        position: relative; display: grid; place-items: center;
        min-block-size: 21rem; padding: 1rem; overflow: clip;
        border: 1px solid var(--mcfb-border); border-radius: 1.1rem;
        background: color-mix(in oklab, var(--mcfb-soft) 55%, var(--mcfb-bg));
    }
    .mcfb-cv { position: absolute; inset: 0; inline-size: 100%; block-size: 100%;
               pointer-events: none; z-index: 2; }
    .mcfb-card {
        position: relative; z-index: 1; display: grid; gap: .65rem;
        inline-size: min(100%, 22rem); padding: 1.4rem;
        border: 1px solid var(--mcfb-border); border-radius: 1rem;
        background: var(--mcfb-bg);
        box-shadow: 0 22px 44px -30px rgb(0 0 0 / .45);
    }
    .mcfb-eyebrow { font-size: .7rem; font-weight: 700; letter-spacing: .08em;
                    text-transform: uppercase; color: var(--mcfb-accent); }
    .mcfb-label { font-size: .8rem; font-weight: 600; color: var(--mcfb-muted); }
    .mcfb-input {
        block-size: 44px; padding-inline: .9rem; border-radius: .7rem;
        border: 1px solid var(--mcfb-border); background: var(--mcfb-soft);
        color: var(--mcfb-fg); font: inherit; inline-size: 100%;
        box-sizing: border-box;
    }
    .mcfb-input:focus-visible { outline: 2px solid var(--mcfb-accent); outline-offset: 1px; }
    .mcfb-input[aria-invalid="true"] { border-color: var(--mcfb-danger); }
    .mcfb-input::placeholder { color: var(--mcfb-muted); opacity: .7; }
    .mcfb-err {
        display: flex; align-items: center; gap: .4rem; margin: 0;
        font-size: .78rem; font-weight: 500; color: var(--mcfb-danger);
    }
    .mcfb-err svg { inline-size: .95rem; block-size: .95rem; flex: none; stroke: currentColor;
                    stroke-width: 2; fill: none; stroke-linecap: round; stroke-linejoin: round; }
    .mcfb-go {
        block-size: 44px; margin-block-start: .3rem; border: none; border-radius: .7rem;
        background: var(--mcfb-accent); color: var(--mcfb-bg); cursor: pointer;
        font-family: inherit; font-size: .9rem; font-weight: 600;
        transition: filter .15s ease, translate .15s ease;
    }
    html[data-theme="dark"] .mcfb-go { color: #1c1917; }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .mcfb-go { color: #1c1917; }
    }
    .mcfb-go:hover { filter: brightness(1.08); translate: 0 -1px; }
    .mcfb-go:focus-visible { outline: 2px solid var(--mcfb-accent); outline-offset: 2px; }
    .mcfb-legal { margin: 0; font-size: .72rem; color: var(--mcfb-muted); }
    .mcfb-win { justify-items: center; text-align: center; gap: .55rem; }
    .mcfb-win[data-win="true"] { animation: mcfb-pop .45s cubic-bezier(.34, 1.56, .64, 1); }
    @keyframes mcfb-pop { from { scale: .8; opacity: 0; } }
    .mcfb-win svg { inline-size: 3rem; block-size: 3rem; stroke: var(--mcfb-ok);
                    stroke-width: 1.6; fill: none; stroke-linecap: round; stroke-linejoin: round; }
    .mcfb-win h4 { margin: 0; font-size: 1.05rem; }
    .mcfb-win p { margin: 0; font-size: .82rem; color: var(--mcfb-muted); }
    .mcfb-foot {
        display: flex; flex-wrap: wrap; align-items: center;
        gap: .75rem 1.25rem; justify-content: space-between;
    }
    .mcfb-density {
        display: inline-flex; align-items: center; gap: .6rem;
        font-size: .8rem; font-weight: 500; color: var(--mcfb-muted);
    }
    .mcfb-density input { inline-size: 9rem; accent-color: var(--mcfb-accent); }
    .mcfb-num {
        min-inline-size: 2.4em; text-align: center; font-variant-numeric: tabular-nums;
        font-weight: 700; color: var(--mcfb-fg);
        padding: .15rem .45rem; border-radius: .5rem; background: var(--mcfb-soft);
    }
    .mcfb-again {
        display: inline-flex; align-items: center; gap: .45rem;
        padding: .55rem 1.1rem; border-radius: 999px; cursor: pointer;
        border: 1px solid var(--mcfb-border); background: var(--mcfb-bg);
        color: var(--mcfb-fg); font-family: inherit; font-size: .82rem; font-weight: 600;
        transition: border-color .15s ease, color .15s ease;
    }
    .mcfb-again:hover { border-color: var(--mcfb-accent); color: var(--mcfb-accent); }
    .mcfb-again:focus-visible { outline: 2px solid var(--mcfb-accent); outline-offset: 2px; }
    .mcfb-again svg { inline-size: .9rem; block-size: .9rem; stroke: currentColor; stroke-width: 2;
                      fill: none; stroke-linecap: round; stroke-linejoin: round; }
    .mcfb-hint { margin: 0; font-family: ui-monospace, monospace;
                 font-size: .72rem; color: var(--mcfb-muted); }
    @media (prefers-reduced-motion: reduce) {
        .mcfb-go, .mcfb-again { transition: none; }
        .mcfb-go:hover { translate: 0 0; }
        .mcfb-win[data-win="true"] { animation: none; }
    }
</style>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A win the whole screen can hear', 'پیروزی‌ای که کل صفحه می‌فهمد') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('A particle confetti cannon on a plain 2D canvas — no library, just integration in a rAF loop. Each piece carries its own velocity, gravity, exponential air drag, spin and life; the palette is read from five CSS variables at fire time, so the dark mirror recolours the party too. The loop parks while off screen and dies the moment the last piece lands.', 'یک توپ کانفتی ذره‌ای روی بوم دوبعدیِ ساده — بدون کتابخانه، فقط انتگرال‌گیری در حلقهٔ rAF. هر ذره سرعت، جاذبه، پسای نمایی هوا، چرخش و عمر خودش را دارد؛ پالت هنگام شلیک از پنج متغیر CSS خوانده می‌شود، پس آینهٔ تیره هم رنگ جشن را عوض می‌کند. حلقه خارج از دید پارک می‌شود و به‌محض فرود آخرین ذره می‌میرد.') }}
        </p>
    </div>

    <div class="mcfb-root"
        x-data="{
            io: null, ro: null, mq: null, ctx: null, raf: 0, last: 0,
            parts: [], visible: true,
            email: '', error: false, done: false, density: 120,
            fa: @js($fa),
            faNum(n) {
                const s = String(n);
                return this.fa ? s.replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]) : s;
            },
            init() {
                const cv = this.$refs.cv;
                this.ctx = cv.getContext('2d');
                this.mq = matchMedia('(prefers-reduced-motion: reduce)');
                this.ro = new ResizeObserver(() => this.size());
                this.ro.observe(cv);
                this.io = new IntersectionObserver((entries) => {
                    this.visible = entries[0].isIntersecting;
                    this.run();
                });
                this.io.observe(cv);
                this.size();
            },
            size() {
                const cv = this.$refs.cv;
                const dpr = Math.min(2, window.devicePixelRatio || 1);
                cv.width = Math.max(2, Math.round(cv.clientWidth * dpr));
                cv.height = Math.max(2, Math.round(cv.clientHeight * dpr));
                this.ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
                this.paint();
            },
            palette() {
                const s = getComputedStyle(this.$el);
                return ['--mcfb-c1', '--mcfb-c2', '--mcfb-c3', '--mcfb-c4', '--mcfb-c5']
                    .map((v) => s.getPropertyValue(v).trim() || '#888888');
            },
            burst(x, y) {
                if (this.mq.matches) return;
                const cols = this.palette();
                for (let i = 0; i < this.density; i++) {
                    const a = -Math.PI / 2 + (Math.random() - 0.5) * Math.PI * 0.9;
                    const sp = 320 + Math.random() * 520;
                    this.parts.push({
                        x: x, y: y,
                        vx: Math.cos(a) * sp, vy: Math.sin(a) * sp,
                        r: 3 + Math.random() * 4,
                        rot: Math.random() * 6.283, vr: (Math.random() - 0.5) * 14,
                        c: cols[i % cols.length],
                        life: 1.6 + Math.random() * 0.9, t: 0,
                        shape: i % 5,
                    });
                }
                this.run();
            },
            step(now) {
                const cv = this.$refs.cv;
                const dt = Math.min(0.032, (now - this.last) / 1000) || 0.016;
                this.last = now;
                this.parts = this.parts.filter((p) => {
                    p.t += dt;
                    p.vy += 1500 * dt;
                    p.vx *= Math.exp(-dt * 1.1);
                    p.x += p.vx * dt;
                    p.y += p.vy * dt;
                    p.rot += p.vr * dt;
                    if (p.t > p.life || p.y > cv.clientHeight + 40) return false;
                    return true;
                });
                this.paint();
                if (this.parts.length && this.visible && !this.mq.matches) {
                    this.raf = requestAnimationFrame((n) => this.step(n));
                } else if (this.parts.length && this.visible && this.mq.matches) {
                    this.parts = []; this.paint();
                } else {
                    this.raf = 0;
                }
            },
            paint() {
                const cv = this.$refs.cv;
                if (!this.ctx) return;
                this.ctx.clearRect(0, 0, cv.clientWidth, cv.clientHeight);
                for (const p of this.parts) {
                    const ctx = this.ctx;
                    ctx.save();
                    ctx.globalAlpha = Math.max(0, 1 - Math.pow(p.t / p.life, 2));
                    ctx.translate(p.x, p.y);
                    ctx.rotate(p.rot);
                    ctx.fillStyle = p.c;
                    if (p.shape === 0) {
                        ctx.beginPath();
                        ctx.arc(0, 0, p.r * 0.7, 0, 6.283);
                        ctx.fill();
                    } else if (p.shape === 1) {
                        ctx.fillRect(-p.r, -p.r * 0.6, p.r * 2, p.r * 1.2);
                    } else if (p.shape === 2) {
                        ctx.beginPath();
                        ctx.moveTo(-p.r, 0);
                        ctx.lineTo(0, -p.r * 1.3);
                        ctx.lineTo(p.r, 0);
                        ctx.lineTo(0, p.r * 1.3);
                        ctx.closePath();
                        ctx.fill();
                    } else if (p.shape === 3) {
                        ctx.beginPath();
                        ctx.ellipse(0, 0, p.r * 1.4, p.r * 0.55, 0, 0, 6.283);
                        ctx.fill();
                    } else {
                        ctx.fillRect(-p.r * 0.5, -p.r * 1.1, p.r, p.r * 2.2);
                    }
                    ctx.restore();
                }
            },
            run() {
                cancelAnimationFrame(this.raf);
                this.last = 0;
                if (!this.parts.length || !this.visible || this.mq.matches) {
                    if (this.mq.matches) { this.parts = []; this.paint(); }
                    return;
                }
                this.raf = requestAnimationFrame((n) => this.step(n));
            },
            fireAt(el) {
                const cv = this.$refs.cv;
                const r = el.getBoundingClientRect();
                const b = cv.getBoundingClientRect();
                this.burst(r.left + r.width / 2 - b.left, r.top - b.top);
            },
            submit(e) {
                if (!/.+@.+\..+/.test(this.email.trim())) {
                    this.error = true;
                    if (!this.mq.matches && this.$refs.card && this.$refs.card.animate) {
                        this.$refs.card.animate(
                            { translate: ['0 0', '-6px 0', '5px 0', '-3px 0', '0 0'] },
                            { duration: 260, easing: 'ease-out' },
                        );
                    }
                    return;
                }
                this.error = false;
                const btn = e.target.querySelector('.mcfb-go');
                this.fireAt(btn || this.$refs.card);
                this.done = true;
            },
            again() {
                this.email = '';
                this.error = false;
                this.done = false;
                this.$nextTick(() => this.fireAt(this.$refs.card));
            },
            destroy() {
                cancelAnimationFrame(this.raf);
                this.parts = [];
                if (this.io) this.io.disconnect();
                if (this.ro) this.ro.disconnect();
            },
        }">
        <div class="mcfb-stage">
            <canvas class="mcfb-cv" x-ref="cv" aria-hidden="true"></canvas>

            <form class="mcfb-card" x-ref="card" x-show="!done" novalidate
                x-bind:aria-label="@js($say('Nimbus Mail beta signup', 'عضویت در بتای نبولاس میل'))"
                @submit.prevent="submit($event)">
                <span class="mcfb-eyebrow">Nimbus Mail · {{ $say('Beta', 'بتا') }}</span>
                <label class="mcfb-label" for="mcfb-mail">{{ $say('Work email', 'ایمیل کاری') }}</label>
                <input id="mcfb-mail" class="mcfb-input" type="email" x-model="email"
                    autocomplete="email" inputmode="email" placeholder="you@studio.com"
                    x-bind:aria-invalid="error ? 'true' : 'false'">
                <p class="mcfb-err" x-show="error" x-cloak role="alert">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 8v5"/><path d="M12 16.5v.01"/><path d="M10.3 3.9 2.4 18a2 2 0 0 0 1.7 3h15.8a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/></svg>
                    {{ $say('That address looks incomplete.', 'نشانی ایمیل کامل به نظر نمی‌رسد.') }}
                </p>
                <button type="submit" class="mcfb-go">{{ $say('Join the beta', 'عضویت در بتا') }}</button>
                <p class="mcfb-legal">{{ $say('Release notes only — no marketing.', 'فقط یادداشت انتشار — بدون تبلیغ.') }}</p>
            </form>

            <div class="mcfb-card mcfb-win" x-show="done" x-cloak x-ref="win" data-win="true">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9.5"/><path d="m8.5 12.2 2.4 2.4 4.6-5"/></svg>
                <h4>{{ $say('You are on the list', 'در فهرست هستید') }}</h4>
                <p>{{ $say('Build 0.9 of Nimbus Mail lands in your inbox on Friday — along with 2,400 other early readers.', 'نسخهٔ ۰٫۹ نبولاس میل جمعه به صندوق شما می‌آید — همراه با ۲٬۴۰۰ خوانندهٔ اولیهٔ دیگر.') }}</p>
            </div>
        </div>

        <div class="mcfb-foot">
            <label class="mcfb-density">
                <span>{{ $say('Density', 'تراکم') }}</span>
                <input type="range" min="40" max="240" step="20" x-model.number="density"
                    x-bind:aria-label="@js($say('Confetti density', 'تراکم کانفتی'))">
                <span class="mcfb-num" x-text="faNum(density)"></span>
            </label>
            <button type="button" class="mcfb-again" x-on:click="again()">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>
                {{ $say('Celebrate again', 'دوباره جشن بگیر') }}
            </button>
            <p class="mcfb-hint">
                {{ $say('canvas 2D · gravity 1500px/s² · exp drag · dpr-aware · parked off screen · silent under reduced motion', 'بوم دوبعدی · جاذبه ۱۵۰۰px/s² · پسای نمایی · سازگار با dpr · پارک خارج از دید · بی‌صدا در حرکت کاهش‌یافته') }}
            </p>
        </div>
    </div>
</section>
