{{--
    plasma-grad's real scenario: the launch hero of Solstice — a global
    launch-analytics product — with a raw WebGL1 plasma simmering behind the
    intro card. Swatches swap the palette (three vec3 uniforms at once), the
    pointer bends the flow with easing, the canvas renders at half
    resolution, parks off screen, freezes to a still frame under reduced
    motion and falls back to a static CSS gradient without WebGL.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;
@endphp

<style>
    .shpl-root {
        --shpl-text: #1e1b4b; --shpl-muted: #4b4768; --shpl-accent: #6d28d9;
        --shpl-card: rgba(255, 255, 255, .78); --shpl-border: rgba(255, 255, 255, .65);
        --shpl-chip: rgba(255, 255, 255, .6); --shpl-font: 'Inter', 'Vazirmatn', ui-sans-serif, system-ui, sans-serif;
        display: grid; gap: 1rem; font-family: var(--shpl-font); color: var(--shpl-text);
    }
    html[data-theme="dark"] .shpl-root {
        --shpl-text: #f5f3ff; --shpl-muted: #c4b5fd; --shpl-accent: #a78bfa;
        --shpl-card: rgba(17, 15, 34, .72); --shpl-border: rgba(148, 130, 220, .28);
        --shpl-chip: rgba(30, 27, 56, .68);
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .shpl-root {
            --shpl-text: #f5f3ff; --shpl-muted: #c4b5fd; --shpl-accent: #a78bfa;
            --shpl-card: rgba(17, 15, 34, .72); --shpl-border: rgba(148, 130, 220, .28);
            --shpl-chip: rgba(30, 27, 56, .68);
        }
    }
    .shpl-stage {
        position: relative; overflow: clip; border-radius: 1.25rem;
        min-block-size: 23rem; display: grid; place-items: center; background: #1e1b4b;
    }
    .shpl-cv { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; display: block; }
    .shpl-fallback { position: absolute; inset: 0; display: block;
                     background: linear-gradient(135deg, #1e1b4b, #7c3aed, #f472b6); }
    .shpl-root[data-fallback="false"] .shpl-fallback { display: none; }
    .shpl-root[data-fallback="true"] .shpl-cv { display: none; }
    .shpl-card {
        position: relative; z-index: 1; inline-size: min(100%, 33rem); margin: 2.25rem 1.25rem;
        padding: clamp(1.25rem, 4vw, 1.9rem); border-radius: 1.25rem; text-align: center;
        background: var(--shpl-card); border: 1px solid var(--shpl-border);
        backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);
        box-shadow: 0 24px 48px -24px rgba(10, 6, 32, .5);
        display: grid; gap: .8rem; justify-items: center;
    }
    .shpl-eyebrow { margin: 0; font-size: .78rem; font-weight: 600; letter-spacing: .06em;
                    text-transform: uppercase; color: var(--shpl-muted); }
    .shpl-title { margin: 0; font-size: clamp(1.45rem, 4.5vw, 2.1rem); font-weight: 700;
                  line-height: 1.35; letter-spacing: 0; }
    .shpl-sub { margin: 0; max-inline-size: 42ch; font-size: .9rem; line-height: 1.8; color: var(--shpl-muted); }
    .shpl-chips { display: flex; flex-wrap: wrap; justify-content: center; gap: .5rem; margin-block-start: .2rem; }
    .shpl-chip { padding: .3rem .7rem; border-radius: 999px; background: var(--shpl-chip);
                 border: 1px solid var(--shpl-border); font-size: .75rem; color: var(--shpl-text);
                 font-variant-numeric: tabular-nums; }
    .shpl-ctas { display: flex; flex-wrap: wrap; justify-content: center; gap: .6rem; margin-block-start: .4rem; }
    .shpl-cta { block-size: 2.6rem; padding-inline: 1.4rem; border: none; border-radius: 999px;
                background: var(--shpl-accent); color: #fff; font: 600 .88rem var(--shpl-font);
                cursor: pointer; transition: filter .15s ease, translate .15s ease; }
    .shpl-cta:hover { filter: brightness(1.1); translate: 0 -1px; }
    .shpl-cta:focus-visible { outline: 2px solid var(--shpl-accent); outline-offset: 2px; }
    .shpl-note { margin: 0; font-size: .78rem; color: var(--shpl-muted); }
    .shpl-bar { display: flex; flex-wrap: wrap; align-items: center; justify-content: center;
                gap: .75rem 1.25rem; }
    .shpl-swatches { display: flex; flex-wrap: wrap; justify-content: center; gap: .65rem; }
    .shpl-swatch { display: grid; gap: .35rem; justify-items: center; border: none; padding: 0;
                   background: none; cursor: pointer; font-family: var(--shpl-font); }
    .shpl-dot { inline-size: 2.1rem; block-size: 2.1rem; border-radius: 50%; border: 1px solid var(--shpl-border);
                box-shadow: 0 4px 10px -4px rgba(10, 6, 32, .45); transition: scale .15s ease; }
    .shpl-swatch:hover .shpl-dot { scale: 1.12; }
    .shpl-swatch[aria-pressed="true"] .shpl-dot { outline: 2px solid var(--shpl-accent); outline-offset: 3px; }
    .shpl-swatch:focus-visible { outline: 2px solid var(--shpl-accent); outline-offset: 2px; border-radius: .4rem; }
    .shpl-swatch small { font-size: .72rem; color: var(--shpl-muted); }
    .shpl-hint { margin: 0; font-size: .74rem; color: var(--shpl-muted); text-align: center; }
    @media (max-width: 480px) {
        .shpl-stage { min-block-size: 26rem; }
        .shpl-card { margin: 1.25rem .9rem; }
    }
    @media (prefers-reduced-motion: reduce) {
        .shpl-cta, .shpl-dot { transition: none; }
        .shpl-cta:hover { translate: 0 0; }
        .shpl-swatch:hover .shpl-dot { scale: 1; }
    }
</style>

<section class="pg-box" style="gap: 1rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A hero that boils', 'هیرویی که می‌جوشد') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The plasma is one small fragment shader on a half-resolution canvas — four sine terms phase-shifted by time, the pointer and distance. Swap the palette, bend the flow with your mouse, and watch the card stay perfectly readable on top.', 'پلاسما یک فرگمنت‌شیدر کوچک روی بومِ نصف‌رزولوشن است — چهار جملهٔ سینوسی که فازشان را از زمان، موس و فاصله می‌گیرند. پالت را عوض کنید، با موس جریان را خم کنید و ببینید کارت رویش چه بی‌سر‌وصدایی خوانا می‌ماند.') }}
        </p>
    </div>

    <div class="shpl-root" data-fallback="true"
        x-data="{
            fallback: true, palette: 0, started: false,
            palettes: [
                { label: @js($say('Aurora', 'شفق')), hex: ['#1e1b4b', '#7c3aed', '#f472b6'] },
                { label: @js($say('Sunset', 'غروب')), hex: ['#431407', '#ea580c', '#fbbf24'] },
                { label: @js($say('Ocean', 'اقیانوس')), hex: ['#082f49', '#0891b2', '#67e8f9'] },
                { label: @js($say('Magma', 'ماگما')), hex: ['#450a0a', '#dc2626', '#f59e0b'] },
            ],
            gl: null, u: {}, raf: 0, io: null, ro: null, mq: null, onMq: null,
            visible: true, t0: 0, last: 1, mx: 0, my: 0, tx: 0, ty: 0,
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
                    uniform vec2 u_res; uniform float u_time; uniform vec2 u_mouse;
                    uniform vec3 u_a; uniform vec3 u_b; uniform vec3 u_c;
                    void main() {
                        vec2 uv = (gl_FragCoord.xy * 2.0 - u_res) / min(u_res.x, u_res.y);
                        vec2 m = u_mouse * 0.45;
                        float v = sin((uv.x + m.x) * 2.6 + u_time * 0.7) + sin((uv.y - m.y) * 2.2 - u_time * 0.45)
                                + sin((uv.x + uv.y + m.x - m.y) * 1.9 + u_time * 0.3) + sin(length(uv - m) * 4.2 - u_time * 0.9);
                        float t = v / 8.0 + 0.5;
                        vec3 col = mix(u_a, u_b, smoothstep(0.0, 0.55, t));
                        col = mix(col, u_c, smoothstep(0.55, 1.0, t));
                        gl_FragColor = vec4(col, 1.0);
                    }`));
                gl.linkProgram(prog); gl.useProgram(prog);
                gl.bindBuffer(gl.ARRAY_BUFFER, gl.createBuffer());
                gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 1, -1, -1, 1, 1, 1]), gl.STATIC_DRAW);
                const a = gl.getAttribLocation(prog, 'a');
                gl.enableVertexAttribArray(a); gl.vertexAttribPointer(a, 2, gl.FLOAT, false, 0, 0);
                for (const n of ['u_res', 'u_time', 'u_mouse', 'u_a', 'u_b', 'u_c']) this.u[n] = gl.getUniformLocation(prog, n);
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
            rgb(h) { return [1, 3, 5].map((i) => parseInt(h.slice(i, i + 2), 16) / 255); },
            draw(t) {
                const gl = this.gl; if (!gl) return;
                this.mx += (this.tx - this.mx) * 0.07; this.my += (this.ty - this.my) * 0.07;
                gl.viewport(0, 0, gl.drawingBufferWidth, gl.drawingBufferHeight);
                gl.uniform2f(this.u.u_res, gl.drawingBufferWidth, gl.drawingBufferHeight);
                gl.uniform1f(this.u.u_time, t);
                gl.uniform2f(this.u.u_mouse, this.mx, this.my);
                const hex = this.palettes[this.palette].hex;
                gl.uniform3f(this.u.u_a, ...this.rgb(hex[0]));
                gl.uniform3f(this.u.u_b, ...this.rgb(hex[1]));
                gl.uniform3f(this.u.u_c, ...this.rgb(hex[2]));
                gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
                if (this.fallback) this.fallback = false;
            },
            redraw() { if (this.gl) this.draw(this.mq && this.mq.matches ? 4.2 : this.last); },
            run() {
                cancelAnimationFrame(this.raf);
                if (!this.gl || !this.visible) return;
                if (this.mq.matches) { this.redraw(); return; }
                const loop = (now) => { this.last = (now - this.t0) / 1000; this.draw(this.last); this.raf = requestAnimationFrame(loop); };
                this.raf = requestAnimationFrame(loop);
            },
            pick(i) { this.palette = i; this.redraw(); },
            bend(e) {
                if (this.fallback) return;
                const r = this.$refs.stage.getBoundingClientRect();
                this.tx = ((e.clientX - r.left) / r.width) * 2 - 1;
                this.ty = 1 - ((e.clientY - r.top) / r.height) * 2;
            },
            calm() { this.tx = 0; this.ty = 0; },
            destroy() {
                cancelAnimationFrame(this.raf);
                if (this.io) this.io.disconnect();
                if (this.ro) this.ro.disconnect();
                if (this.mq && this.onMq) this.mq.removeEventListener('change', this.onMq);
                if (this.gl) { const lose = this.gl.getExtension('WEBGL_lose_context'); if (lose) lose.loseContext(); this.gl = null; }
            },
        }"
        x-bind:data-fallback="fallback ? 'true' : 'false'">
        <div class="shpl-stage" x-ref="stage" x-on:pointermove="bend($event)" x-on:pointerleave="calm()">
            <canvas class="shpl-cv" x-ref="cv" aria-hidden="true"></canvas>
            <div class="shpl-fallback"
                x-bind:style="'background: linear-gradient(135deg, ' + palettes[palette].hex.join(', ') + ')'"></div>
            <article class="shpl-card">
                <p class="shpl-eyebrow">{{ $say('Solstice ' . $num('3.0') . ' · global launch', 'Solstice ' . $num('3.0') . ' · عرضهٔ جهانی') }}</p>
                <h4 class="shpl-title">{{ $say('Every launch, everywhere, at once', 'هر عرضه، همه‌جا، هم‌زمان') }}</h4>
                <p class="shpl-sub">{{ $say('From Frankfurt to Singapore, your launch dashboard is live from second zero — traffic, signups and error rates on one map.', 'از فرانکفورت تا سنگاپور، داشبورد عرضهٔ شما از ثانیهٔ صفر زنده است — ترافیک، ثبت‌نام و نرخ خطا روی یک نقشه.') }}</p>
                <div class="shpl-chips">
                    <span class="shpl-chip">{{ $say('Frankfurt', 'فرانکفورت') }} {{ $num('12') }}ms</span>
                    <span class="shpl-chip">{{ $say('Dublin', 'دوبلین') }} {{ $num('18') }}ms</span>
                    <span class="shpl-chip">{{ $say('Singapore', 'سنگاپور') }} {{ $num('23') }}ms</span>
                </div>
                <div class="shpl-ctas">
                    <button type="button" class="shpl-cta" x-on:click="started = true">{{ $say('Start free', 'شروع رایگان') }}</button>
                    <p class="shpl-note" x-show="started" x-cloak style="inline-size: 100%">
                        {{ $say('Workspace ready — the welcome email is on its way.', 'ورک‌اسپیس آماده شد — ایمیل خوش‌آمد در راه است.') }}
                    </p>
                </div>
            </article>
        </div>

        <div class="shpl-bar">
            <div class="shpl-swatches" role="group" x-bind:aria-label="@js($say('Plasma palette', 'پالت پلاسما'))">
                <template x-for="(p, i) in palettes" :key="p.label">
                    <button type="button" class="shpl-swatch" x-bind:aria-pressed="palette === i ? 'true' : 'false'" x-on:click="pick(i)">
                        <span class="shpl-dot" x-bind:style="'background: linear-gradient(135deg, ' + p.hex.join(', ') + ')'" aria-hidden="true"></span>
                        <small x-text="p.label"></small>
                    </button>
                </template>
            </div>
        </div>
        <p class="shpl-hint">
            {{ $say('Half-resolution canvas · pauses off screen · a still frame under reduced motion · a static gradient without WebGL.', 'بوم نصف‌رزولوشن · خارج از دید متوقف · در حرکت کاهش‌یافته یک قاب ثابت · بدون WebGL گرادیان ثابت.') }}
        </p>
    </div>
</section>
