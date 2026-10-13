{{--
    ripple-pond's real scenario: an interactive pond — every click, tap or
    Enter press on the canvas drops a circular ripple (position, birth time
    and strength written into a 12-slot ring-buffer uniform) whose sine
    rings decay exponentially over about three seconds; several waves share
    the surface at once. The floating dock below rains a drop, drops three
    staggered ones and calms the surface. Raw WebGL1 at half resolution,
    parked off screen, still water under reduced motion, a static water
    gradient without WebGL, full cleanup on destroy.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp

<style>
    .shrp-root {
        --shrp-ink: #eaf6ff; --shrp-muted: #9fc3d8; --shrp-accent: #7dd3fc;
        --shrp-dock: rgba(13, 24, 38, .82); --shrp-border: rgba(125, 211, 252, .25);
        --shrp-font: 'Inter', 'Vazirmatn', ui-sans-serif, system-ui, sans-serif;
        display: grid; gap: .9rem; font-family: var(--shrp-font);
    }
    html[data-theme="dark"] .shrp-root {
        --shrp-ink: #eaf6ff; --shrp-muted: #8fb8cf; --shrp-accent: #38bdf8;
        --shrp-dock: rgba(7, 14, 24, .85); --shrp-border: rgba(56, 189, 248, .28);
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .shrp-root {
            --shrp-ink: #eaf6ff; --shrp-muted: #8fb8cf; --shrp-accent: #38bdf8;
            --shrp-dock: rgba(7, 14, 24, .85); --shrp-border: rgba(56, 189, 248, .28);
        }
    }
    .shrp-stage {
        position: relative; overflow: clip; border-radius: 1.25rem;
        aspect-ratio: 16 / 8; background: #08131f;
    }
    @media (max-width: 480px) { .shrp-stage { aspect-ratio: 4 / 3; } }
    .shrp-cv { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; display: block;
               touch-action: manipulation; cursor: crosshair; }
    .shrp-cv:focus-visible { outline: 2px solid var(--shrp-accent); outline-offset: -2px; }
    .shrp-fallback { position: absolute; inset: 0; display: none;
                     background: radial-gradient(120% 90% at 50% 10%, #1c4258, #08131f 70%); }
    .shrp-root[data-fallback="true"] .shrp-cv { display: none; }
    .shrp-root[data-fallback="true"] .shrp-fallback { display: block; }
    .shrp-cap {
        position: absolute; inset-block-start: .9rem; inset-inline-start: .9rem; z-index: 1;
        padding: .35rem .8rem; border-radius: 999px; background: rgba(255, 255, 255, .14);
        border: 1px solid rgba(255, 255, 255, .18); color: var(--shrp-ink);
        font-size: .76rem; backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
        pointer-events: none;
    }
    .shrp-dock {
        position: relative; z-index: 2; display: flex; flex-wrap: wrap; align-items: center;
        justify-content: center; gap: .5rem; margin-block-start: -1.4rem;
        padding: .5rem .7rem; border-radius: 999px; background: var(--shrp-dock);
        border: 1px solid var(--shrp-border); backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px); box-shadow: 0 14px 30px -18px rgba(2, 8, 20, .9);
        inline-size: fit-content; margin-inline: auto;
    }
    .shrp-btn {
        display: inline-flex; align-items: center; gap: .4rem; padding: .5rem 1.05rem;
        border-radius: 999px; border: 1px solid transparent; background: none;
        color: var(--shrp-ink); font: 600 .8rem var(--shrp-font); cursor: pointer;
        transition: background-color .15s ease, border-color .15s ease;
    }
    .shrp-btn:hover { background: rgba(125, 211, 252, .14); border-color: var(--shrp-border); }
    .shrp-btn:focus-visible { outline: 2px solid var(--shrp-accent); outline-offset: 2px; }
    .shrp-btn svg { inline-size: .95rem; block-size: .95rem; stroke: currentColor; stroke-width: 2;
                    fill: none; stroke-linecap: round; stroke-linejoin: round; }
    .shrp-count { padding-inline: .55rem; font-size: .76rem; color: var(--shrp-muted);
                  font-variant-numeric: tabular-nums; white-space: nowrap; }
    .shrp-hint { margin: 0; font-size: .74rem; color: var(--shrp-muted); text-align: center; }
    @media (prefers-reduced-motion: reduce) {
        .shrp-btn { transition: none; }
    }
</style>

<section class="pg-box" style="gap: 1rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A pond that answers the touch', 'برکه‌ای که به لمس جواب می‌دهد') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Each press writes its position, birth time and strength into a twelve-slot ring-buffer uniform; the shader sums sine rings with exponential decay, so up to twelve waves share one surface before the oldest silently gives way. The dock floats below the waterline.', 'هر فشار، مختصات و زمان تولد و شدتش را در یونیفرمِ حلقه‌ایِ دوازده‌خانه‌ای می‌نویسد؛ شیدر حلقه‌های سینوسی را با میرایی نمایی جمع می‌زند، پس تا دوازده موج روی یک سطح جا می‌شوند تا قدیمی‌ترین بی‌صدا جایش را بدهد. داک زیر خط آب شناور است.') }}
        </p>
    </div>

    <div class="shrp-root"
        x-data="{
            fallback: false, active: 0,
            gl: null, u: {}, raf: 0, io: null, ro: null, mq: null, onMq: null,
            visible: true, t0: 0, last: 0, i: 0, timers: [], buf: null,
            fa: @js($fa),
            init() {
                const canvas = this.$refs.cv;
                const gl = canvas.getContext('webgl', { antialias: false, alpha: false, powerPreference: 'low-power' });
                if (!gl) { this.fallback = true; return; }
                this.gl = gl;
                const sh = (type, src) => { const s = gl.createShader(type); gl.shaderSource(s, src); gl.compileShader(s); return s; };
                const prog = gl.createProgram();
                gl.attachShader(prog, sh(gl.VERTEX_SHADER, 'attribute vec2 a; void main() { gl_Position = vec4(a, 0.0, 1.0); }'));
                gl.attachShader(prog, sh(gl.FRAGMENT_SHADER, `
                    precision mediump float;
                    uniform vec2 u_res; uniform float u_time; uniform float u_amp;
                    uniform vec4 u_r[12];
                    void main() {
                        vec2 uv = (gl_FragCoord.xy * 2.0 - u_res) / min(u_res.x, u_res.y);
                        vec3 col = mix(vec3(0.031, 0.08, 0.145), vec3(0.11, 0.27, 0.38), uv.y * 0.5 + 0.5);
                        float ring = 0.0;
                        for (int i = 0; i < 12; i++) {
                            vec4 r = u_r[i];
                            float age = u_time - r.z;
                            if (age > 0.0 && age < 3.0) {
                                float d = length(uv - r.xy);
                                ring += sin(d * 26.0 - age * 8.0) * exp(-age * 1.7) * exp(-d * 1.25) * r.w;
                            }
                        }
                        col += vec3(0.30, 0.55, 0.68) * max(ring, 0.0) * u_amp;
                        col -= vec3(0.04, 0.07, 0.09) * max(-ring, 0.0) * u_amp;
                        float vig = 1.0 - 0.35 * dot(uv * 0.62, uv * 0.62);
                        gl_FragColor = vec4(col * vig, 1.0);
                    }`));
                gl.linkProgram(prog); gl.useProgram(prog);
                gl.bindBuffer(gl.ARRAY_BUFFER, gl.createBuffer());
                gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 1, -1, -1, 1, 1, 1]), gl.STATIC_DRAW);
                const a = gl.getAttribLocation(prog, 'a');
                gl.enableVertexAttribArray(a); gl.vertexAttribPointer(a, 2, gl.FLOAT, false, 0, 0);
                for (const n of ['u_res', 'u_time', 'u_amp', 'u_r']) this.u[n] = gl.getUniformLocation(prog, n);
                this.buf = new Float32Array(48);
                for (let k = 0; k < 12; k++) this.buf[k * 4 + 2] = -1000;
                this.t0 = performance.now();
                this.ro = new ResizeObserver(() => { this.size(); this.redraw(); });
                this.ro.observe(canvas);
                this.mq = matchMedia('(prefers-reduced-motion: reduce)');
                this.onMq = () => this.run();
                this.mq.addEventListener('change', this.onMq);
                this.io = new IntersectionObserver((entries) => { this.visible = entries[0].isIntersecting; this.run(); });
                this.io.observe(canvas);
                this.size(); this.run();
            },
            size() {
                const c = this.$refs.cv;
                c.width = Math.max(2, Math.round(c.clientWidth * 0.5));
                c.height = Math.max(2, Math.round(c.clientHeight * 0.5));
            },
            clock() { return (performance.now() - this.t0) / 1000; },
            spawn(x, y, k) {
                if (!this.gl) return;
                const i = this.i; this.i = (this.i + 1) % 12;
                this.buf[i * 4] = x; this.buf[i * 4 + 1] = y;
                this.buf[i * 4 + 2] = this.clock(); this.buf[i * 4 + 3] = k || 1;
            },
            drop(e) {
                if (this.fallback) return;
                const r = this.$refs.cv.getBoundingClientRect();
                const m = Math.min(r.width, r.height);
                this.spawn((((e.clientX - r.left) * 2) - r.width) / m, ((r.height - (e.clientY - r.top) * 2)) / m);
            },
            dropAt(x, y) { this.spawn(x, y); },
            rain(n) {
                for (let j = 0; j < n; j++) {
                    this.timers.push(setTimeout(() => {
                        this.spawn((Math.random() - 0.5) * 1.4, (Math.random() - 0.5) * 0.8, 0.8 + Math.random() * 0.2);
                    }, j * 180));
                }
            },
            calm() {
                this.timers.forEach(clearTimeout); this.timers = [];
                for (let k = 0; k < 12; k++) this.buf[k * 4 + 2] = -1000;
                this.active = 0; this.redraw();
            },
            draw(t) {
                const gl = this.gl; if (!gl) return;
                let live = 0;
                for (let k = 0; k < 12; k++) if (this.buf[k * 4 + 2] > 0 && t - this.buf[k * 4 + 2] < 3) live++;
                this.active = live;
                gl.viewport(0, 0, gl.drawingBufferWidth, gl.drawingBufferHeight);
                gl.uniform2f(this.u.u_res, gl.drawingBufferWidth, gl.drawingBufferHeight);
                gl.uniform1f(this.u.u_time, t);
                gl.uniform1f(this.u.u_amp, this.mq.matches ? 0 : 1);
                gl.uniform4fv(this.u.u_r, this.buf);
                gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
            },
            redraw() { if (this.gl) this.draw(this.mq && this.mq.matches ? 0 : this.last); },
            run() {
                cancelAnimationFrame(this.raf);
                if (!this.gl || !this.visible) return;
                if (this.mq.matches) { this.redraw(); return; }
                const loop = (now) => { this.last = (now - this.t0) / 1000; this.draw(this.last); this.raf = requestAnimationFrame(loop); };
                this.raf = requestAnimationFrame(loop);
            },
            get countLabel() {
                const s = String(this.active);
                const n = this.fa ? s.replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]) : s;
                return this.fa ? n + ' موج فعال' : n + ' live waves';
            },
            destroy() {
                this.timers.forEach(clearTimeout); this.timers = [];
                cancelAnimationFrame(this.raf);
                if (this.io) this.io.disconnect();
                if (this.ro) this.ro.disconnect();
                if (this.mq && this.onMq) this.mq.removeEventListener('change', this.onMq);
                if (this.gl) { const lose = this.gl.getExtension('WEBGL_lose_context'); if (lose) lose.loseContext(); this.gl = null; }
            },
        }"
        x-bind:data-fallback="fallback ? 'true' : 'false'">
        <div class="shrp-stage">
            <canvas class="shrp-cv" x-ref="cv" tabindex="0" role="img"
                x-bind:aria-label="@js($say('Interactive pond; click or press Enter to drop a ripple', 'برکهٔ تعاملی؛ برای انداختن قطره کلیک کنید یا Enter را بزنید'))"
                x-on:pointerdown="drop($event)"
                x-on:keydown.enter.prevent="dropAt(0, 0)"
                x-on:keydown.space.prevent="dropAt(0.1, -0.1)"></canvas>
            <div class="shrp-fallback" aria-hidden="true"></div>
            <span class="shrp-cap">{{ $say('Drop a ripple — click, tap or Enter', 'قطره بیندازید — کلیک، لمس یا Enter') }}</span>
        </div>

        <div class="shrp-dock">
            <button type="button" class="shrp-btn" x-on:click="rain(1)">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3c3.2 4.2 5.5 7 5.5 9.6a5.5 5.5 0 1 1-11 0C6.5 10 8.8 7.2 12 3Z"/></svg>
                {{ $say('Drop', 'قطره') }}
            </button>
            <button type="button" class="shrp-btn" x-on:click="rain(3)">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 13c1.6 2.1 2.8 3.5 2.8 4.8a2.8 2.8 0 1 1-5.6 0c0-1.3 1.2-2.7 2.8-4.8Z"/><path d="M17 9c1.6 2.1 2.8 3.5 2.8 4.8a2.8 2.8 0 1 1-5.6 0c0-1.3 1.2-2.7 2.8-4.8Z"/><path d="M12 3.5c.9 1.2 1.5 2 1.5 2.7a1.5 1.5 0 1 1-3 0c0-.7.6-1.5 1.5-2.7Z"/></svg>
                {{ $say('Triple drop', 'سه قطره') }}
            </button>
            <button type="button" class="shrp-btn" x-on:click="calm()">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                {{ $say('Calm the surface', 'پاک‌کردن سطح') }}
            </button>
            <span class="shrp-count" role="status" x-text="countLabel"></span>
        </div>

        <p class="shrp-hint">
            {{ $say('12-slot ripple ring buffer · exp decay ≈ 3s · half resolution · parked off screen · still water under reduced motion.', 'حلقهٔ ۱۲ خانه‌ای موج · میرایی نمایی ≈ ۳ ثانیه · نصف رزولوشن · پارک خارج از دید · آب آرام در حرکت کاهش‌یافته.') }}
        </p>
    </div>
</section>
