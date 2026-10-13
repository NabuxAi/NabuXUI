{{--
    noise-field's real scenario: the engine-room section of Meridian Cloud —
    a domain-warped fbm field rendering behind the copy as a raw WebGL1
    fragment shader. Scale and speed sliders drive the u_scale/u_speed
    uniforms live, the colour ramp deepens and brightens for contrast in the
    dark theme, the canvas runs at half resolution, parks off screen,
    freezes to a still frame under reduced motion and falls back to a static
    gradient without WebGL.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp

<style>
    .shnf-root {
        --shnf-text: #1e1b4b; --shnf-muted: #4b4768; --shnf-accent: #4338ca;
        --shnf-panel: rgba(255, 255, 255, .66); --shnf-border: rgba(255, 255, 255, .6);
        --shnf-font: 'Inter', 'Vazirmatn', ui-sans-serif, system-ui, sans-serif;
        display: grid; gap: .9rem; font-family: var(--shnf-font); color: var(--shnf-text);
    }
    html[data-theme="dark"] .shnf-root {
        --shnf-text: #e0f2fe; --shnf-muted: #7dd3fc; --shnf-accent: #38bdf8;
        --shnf-panel: rgba(2, 8, 24, .6); --shnf-border: rgba(125, 211, 252, .22);
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .shnf-root {
            --shnf-text: #e0f2fe; --shnf-muted: #7dd3fc; --shnf-accent: #38bdf8;
            --shnf-panel: rgba(2, 8, 24, .6); --shnf-border: rgba(125, 211, 252, .22);
        }
    }
    .shnf-stage {
        position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 22rem;
        display: grid; align-content: space-between; gap: 1.5rem; padding: 1.6rem;
        background: #eef2ff; isolation: isolate;
    }
    .shnf-cv { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; display: block; }
    .shnf-fallback { position: absolute; inset: 0; display: block;
                     background: linear-gradient(160deg, #eef2ff, #a5b4fc, #4338ca); }
    html[data-theme="dark"] .shnf-fallback { background: linear-gradient(160deg, #020617, #155e75, #7dd3fc); }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .shnf-fallback { background: linear-gradient(160deg, #020617, #155e75, #7dd3fc); }
    }
    .shnf-root[data-fallback="false"] .shnf-fallback { display: none; }
    .shnf-root[data-fallback="true"] .shnf-cv { display: none; }
    .shnf-copy { position: relative; z-index: 1; display: grid; gap: .55rem;
                 justify-items: start; max-inline-size: 34rem; }
    .shnf-eyebrow { margin: 0; font-size: .78rem; font-weight: 600; letter-spacing: .06em;
                    text-transform: uppercase; color: var(--shnf-muted); }
    .shnf-title { margin: 0; font-size: clamp(1.35rem, 4vw, 1.9rem); font-weight: 700; line-height: 1.4; }
    .shnf-sub { margin: 0; font-size: .9rem; line-height: 1.85; color: var(--shnf-muted); }
    .shnf-metrics { display: flex; flex-wrap: wrap; gap: .5rem; margin-block-start: .3rem; }
    .shnf-metric { padding: .32rem .75rem; border-radius: 999px; background: var(--shnf-panel);
                   border: 1px solid var(--shnf-border); backdrop-filter: blur(10px);
                   -webkit-backdrop-filter: blur(10px);
                   font-size: .76rem; font-weight: 600; font-variant-numeric: tabular-nums; }
    .shnf-panel {
        position: relative; z-index: 1; display: flex; flex-wrap: wrap; gap: 1rem 2rem;
        padding: .9rem 1.15rem; border-radius: 1rem; background: var(--shnf-panel);
        border: 1px solid var(--shnf-border); backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }
    .shnf-field { display: grid; gap: .4rem; min-inline-size: 11rem; flex: 1 1 11rem; }
    .shnf-field-head { display: flex; align-items: baseline; justify-content: space-between; gap: .75rem; }
    .shnf-field-head span { font-size: .78rem; font-weight: 600; }
    .shnf-field-head output { font-size: .76rem; color: var(--shnf-muted); font-variant-numeric: tabular-nums; }
    .shnf-field input[type="range"] { inline-size: 100%; accent-color: var(--shnf-accent); margin: 0; }
    .shnf-field input[type="range"]:focus-visible { outline: 2px solid var(--shnf-accent); outline-offset: 3px; }
    .shnf-hint { margin: 0; font-size: .74rem; color: var(--shnf-muted); text-align: center; }
    @media (max-width: 480px) {
        .shnf-stage { padding: 1.15rem; min-block-size: 24rem; }
        .shnf-field { min-inline-size: 9rem; }
    }
    @media (prefers-reduced-motion: reduce) {
        .shnf-root * { transition-duration: .01ms !important; animation-duration: .01ms !important; }
    }
</style>

<section class="pg-box" style="gap: 1rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A section that breathes', 'بخشی که نفس می‌کشد') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Five octaves of value noise, warped through a second fbm pass so the field folds instead of scrolling — the texture of an engine room without a single image. The sliders rewrite two uniforms live; the ramp swaps for real contrast when the theme goes dark.', 'پنج اکتاو نویز مقداری که از یک گذر fbm دوم پیچش می‌خورد تا میدان تاخوردگی بخورد به‌جای اسکرول کردن — بافتِ موتورخانه بدون حتی یک تصویر. اسلایدرها دو یونیفرم را زنده بازنویسی می‌کنند؛ رمپ رنگ با تیرا شدن تم، برای کنتراست واقعی عوض می‌شود.') }}
        </p>
    </div>

    <div class="shnf-root" data-fallback="true"
        x-data="{
            fallback: true, scale: 1.4, speed: 0.8,
            gl: null, u: {}, raf: 0, io: null, ro: null, mo: null, mq: null, mqDark: null, onMq: null, onDark: null,
            visible: true, t0: 0, last: 2,
            ramps: {
                light: ['#eef2ff', '#a5b4fc', '#4338ca'],
                dark: ['#020617', '#155e75', '#7dd3fc'],
            },
            fa: @js($fa),
            init() {
                const canvas = this.$refs.cv;
                const gl = canvas.getContext('webgl', { antialias: false, alpha: false, powerPreference: 'low-power' });
                if (!gl) return;
                this.gl = gl;
                const sh = (type, src) => { const s = gl.createShader(type); gl.shaderSource(s, src); gl.compileShader(s); return s; };
                const prog = gl.createProgram();
                gl.attachShader(prog, sh(gl.VERTEX_SHADER, 'attribute vec2 a; void main() { gl_Position = vec4(a, 0.0, 1.0); }'));
                gl.attachShader(prog, sh(gl.FRAGMENT_SHADER, `
                    precision mediump float;
                    uniform vec2 u_res; uniform float u_time; uniform float u_scale; uniform float u_speed;
                    uniform vec3 u_lo; uniform vec3 u_mid; uniform vec3 u_hi;
                    float hash(vec2 p) { return fract(sin(dot(p, vec2(127.1, 311.7))) * 43758.5453); }
                    float noise(vec2 p) {
                        vec2 i = floor(p); vec2 f = fract(p);
                        vec2 u = f * f * (3.0 - 2.0 * f);
                        return mix(mix(hash(i), hash(i + vec2(1.0, 0.0)), u.x),
                                   mix(hash(i + vec2(0.0, 1.0)), hash(i + vec2(1.0, 1.0)), u.x), u.y);
                    }
                    float fbm(vec2 p) {
                        float v = 0.0; float a = 0.5;
                        for (int k = 0; k < 5; k++) { v += a * noise(p); p = p * 2.02 + vec2(9.4, 3.7); a *= 0.5; }
                        return v;
                    }
                    void main() {
                        vec2 uv = (gl_FragCoord.xy * 2.0 - u_res) / min(u_res.x, u_res.y);
                        vec2 drift = vec2(u_time * 0.05, u_time * -0.04) * u_speed * 2.0;
                        vec2 q = uv * u_scale + drift;
                        float n = fbm(q + fbm(q * 1.7 + u_time * 0.02 * u_speed) * 0.6);
                        vec3 col = mix(u_lo, u_mid, smoothstep(0.15, 0.75, n));
                        col = mix(col, u_hi, smoothstep(0.72, 0.98, n));
                        gl_FragColor = vec4(col, 1.0);
                    }`));
                gl.linkProgram(prog); gl.useProgram(prog);
                gl.bindBuffer(gl.ARRAY_BUFFER, gl.createBuffer());
                gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 1, -1, -1, 1, 1, 1]), gl.STATIC_DRAW);
                const a = gl.getAttribLocation(prog, 'a');
                gl.enableVertexAttribArray(a); gl.vertexAttribPointer(a, 2, gl.FLOAT, false, 0, 0);
                for (const n of ['u_res', 'u_time', 'u_scale', 'u_speed', 'u_lo', 'u_mid', 'u_hi']) this.u[n] = gl.getUniformLocation(prog, n);
                this.t0 = performance.now();
                this.ro = new ResizeObserver(() => { this.size(); this.redraw(); });
                this.ro.observe(canvas);
                this.mq = matchMedia('(prefers-reduced-motion: reduce)');
                this.onMq = () => this.run();
                this.mq.addEventListener('change', this.onMq);
                this.mqDark = matchMedia('(prefers-color-scheme: dark)');
                this.onDark = () => this.redraw();
                this.mqDark.addEventListener('change', this.onDark);
                this.mo = new MutationObserver(() => this.redraw());
                this.mo.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
                this.io = new IntersectionObserver((entries) => { this.visible = entries[0].isIntersecting; this.run(); });
                this.io.observe(canvas);
                this.size(); this.run();
            },
            size() {
                const c = this.$refs.cv;
                c.width = Math.max(2, Math.round(c.clientWidth * 0.5));
                c.height = Math.max(2, Math.round(c.clientHeight * 0.5));
            },
            dark() {
                const t = document.documentElement.dataset.theme;
                if (t === 'dark') return true;
                if (t === 'light') return false;
                return (this.mqDark || matchMedia('(prefers-color-scheme: dark)')).matches;
            },
            rgb(h) { return [1, 3, 5].map((i) => parseInt(h.slice(i, i + 2), 16) / 255); },
            draw(t) {
                const gl = this.gl; if (!gl) return;
                gl.viewport(0, 0, gl.drawingBufferWidth, gl.drawingBufferHeight);
                gl.uniform2f(this.u.u_res, gl.drawingBufferWidth, gl.drawingBufferHeight);
                gl.uniform1f(this.u.u_time, t);
                gl.uniform1f(this.u.u_scale, this.scale);
                gl.uniform1f(this.u.u_speed, this.speed);
                const ramp = this.ramps[this.dark() ? 'dark' : 'light'];
                gl.uniform3f(this.u.u_lo, ...this.rgb(ramp[0]));
                gl.uniform3f(this.u.u_mid, ...this.rgb(ramp[1]));
                gl.uniform3f(this.u.u_hi, ...this.rgb(ramp[2]));
                gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
                if (this.fallback) this.fallback = false;
            },
            redraw() { if (this.gl) this.draw(this.mq && this.mq.matches ? 6.0 : this.last); },
            run() {
                cancelAnimationFrame(this.raf);
                if (!this.gl || !this.visible) return;
                if (this.mq.matches) { this.redraw(); return; }
                const loop = (now) => { this.last = (now - this.t0) / 1000; this.draw(this.last); this.raf = requestAnimationFrame(loop); };
                this.raf = requestAnimationFrame(loop);
            },
            fmt(v) {
                const s = Number(v).toFixed(1);
                return this.fa ? s.replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]).replace('.', '٫') : s;
            },
            destroy() {
                cancelAnimationFrame(this.raf);
                if (this.io) this.io.disconnect();
                if (this.ro) this.ro.disconnect();
                if (this.mo) this.mo.disconnect();
                if (this.mq && this.onMq) this.mq.removeEventListener('change', this.onMq);
                if (this.mqDark && this.onDark) this.mqDark.removeEventListener('change', this.onDark);
                if (this.gl) { const lose = this.gl.getExtension('WEBGL_lose_context'); if (lose) lose.loseContext(); this.gl = null; }
            },
        }"
        x-bind:data-fallback="fallback ? 'true' : 'false'">
        <div class="shnf-stage">
            <canvas class="shnf-cv" x-ref="cv" aria-hidden="true"></canvas>
            <div class="shnf-fallback"
                x-bind:style="'background: linear-gradient(160deg, ' + (dark() ? ramps.dark : ramps.light).join(', ') + ')'"></div>
            <div class="shnf-copy">
                <p class="shnf-eyebrow">{{ $say('Meridian Cloud · engine room', 'Meridian Cloud · موتورخانه') }}</p>
                <h4 class="shnf-title">{{ $say('Infrastructure that never sleeps', 'زیرساختی که نمی‌خوابد') }}</h4>
                <p class="shnf-sub">{{ $say('We route ' . $num('2.4') . 'M requests a second across ' . $num('38') . ' regions — Dublin to Singapore. This noise is that network’s pulse.', 'هر ثانیه ' . $num('2.4') . ' میلیون درخواست را در ' . $num('38') . ' منطقه جابه‌جا می‌کنیم — از دوبلین تا سنگاپور. این نویز، تپش همان شبکه است.') }}</p>
                <div class="shnf-metrics">
                    <span class="shnf-metric">{{ $num('99.99') }}% {{ $say('uptime', 'آپ‌تایم') }}</span>
                    <span class="shnf-metric">{{ $num('38') }} {{ $say('regions', 'منطقه') }}</span>
                    <span class="shnf-metric">{{ $num('81') }}ms p95</span>
                </div>
            </div>
            <div class="shnf-panel">
                <label class="shnf-field">
                    <span class="shnf-field-head">
                        <span>{{ $say('Scale', 'مقیاس') }}</span>
                        <output x-text="fmt(scale)"></output>
                    </span>
                    <input type="range" min="0.6" max="3" step="0.1" x-model.number="scale" x-on:input="redraw()"
                        x-bind:aria-label="@js($say('Field scale', 'مقیاس میدان'))">
                </label>
                <label class="shnf-field">
                    <span class="shnf-field-head">
                        <span>{{ $say('Speed', 'سرعت') }}</span>
                        <output x-text="fmt(speed)"></output>
                    </span>
                    <input type="range" min="0" max="2" step="0.1" x-model.number="speed" x-on:input="redraw()"
                        x-bind:aria-label="@js($say('Field speed', 'سرعت میدان'))">
                </label>
            </div>
        </div>
        <p class="shnf-hint">
            {{ $say('5 octaves of domain-warped fbm · half resolution · parked off screen · still under reduced motion.', '۵ اکتاو fbm با پیچش دامنه · نصف رزولوشن · پارک خارج از دید · قاب ثابت در حرکت کاهش‌یافته.') }}
        </p>
    </div>
</section>
