<?php

/**
 * Demo manifest of the "shaders" group — raw WebGL1 fragment shaders living
 * inside Blade partials, no library: a plasma gradient with swappable
 * palettes behind a product card, a domain-warped fbm noise field as a
 * section background with scale/speed sliders, and a ripple pond that drops
 * fading circular waves wherever you touch it. Every demo renders at half
 * resolution, parks off screen via IntersectionObserver, freezes to a still
 * frame under prefers-reduced-motion, falls back to a static gradient
 * without WebGL and cleans its context up on destroy. Scenarios live at
 * resources/views/demos/components/shaders/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'شیدرها', 'en' => 'Shaders'],

    'plasma-grad' => [
        'title' => ['fa' => 'گرادیان پلاسما', 'en' => 'Plasma gradient'],
        'icon' => 'wand',
        'oneLiner' => [
            'fa' => 'پلاسمای شیدری که پشت کارت معرفی محصول می‌جوشد؛ چهار پالت آماده روی سواچ‌ها و موسی که جریان را خم می‌کند. WebGL خام و بی‌کتابخانه — خارج از دید پارک می‌شود، روی حرکت کاهش‌یافته روی یک قاب ثابت می‌ایستد و بدون WebGL به گرادیان ثابتِ همان پالت فرو می‌نشیند.',
            'en' => 'A shader plasma simmering behind a product intro card; four ready palettes swap on swatches and the pointer bends the flow as it crosses. Raw WebGL1 with zero libraries — it parks off screen, freezes to a still frame under reduced motion and settles into a static gradient of the same palette without WebGL.',
        ],
        'js' => true,
        'docs' => 'https://www.shadertoy.com',
        'props' => [
            ['name' => 'palette', 'type' => 'uniform vec3 ×3', 'default' => "'aurora'", 'note' => [
                'fa' => 'چهار پالت (شفق، غروب، اقیانوس، ماگما) هرکدام سه رنگ؛ هر سواچ هر سه یونیفرم را یکجا عوض می‌کند.',
                'en' => 'Four palettes (aurora, sunset, ocean, magma) of three colours each; every swatch swaps all three uniforms at once.',
            ]],
            ['name' => 'u_time', 'type' => 'speed', 'default' => "'0.9 rad/s'", 'note' => [
                'fa' => 'سرعت جوشش؛ فاز چهار جملهٔ سینوسی پلاسما با زمان می‌چرخد.',
                'en' => 'The simmer speed; the phase of the plasma’s four sine terms rotates with time.',
            ]],
            ['name' => 'u_mouse', 'type' => 'uniform vec2', 'default' => "'eased'", 'note' => [
                'fa' => 'موس جریان را خم می‌کند؛ موقعیت با easing دنبال می‌شود و با بیرون رفتن نشانگر آرام به مرکز برمی‌گردد.',
                'en' => 'The pointer bends the flow; its position is chased with easing and drifts back to centre once it leaves.',
            ]],
            ['name' => 'resolution', 'type' => 'scale', 'default' => "'0.5×'", 'note' => [
                'fa' => 'بوم با نصف رزولوشن CSS رندر و بزرگ‌نمایی می‌شود — شیدر تمام‌صفحهٔ ارزان.',
                'en' => 'The canvas renders at half CSS resolution and upscales — a cheap full-screen shader.',
            ]],
            ['name' => 'fallback', 'type' => 'state', 'default' => "'static gradient'", 'note' => [
                'fa' => 'بدون WebGL بوم مخفی می‌شود و گرادیان ثابت CSS با همان پالت فعال نشان داده می‌شود.',
                'en' => 'Without WebGL the canvas hides and a static CSS gradient of the active palette shows instead.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="shpl" x-data="{ palette: 0 }">
            <div class="shpl-stage" @pointermove="bend($event)">
                <canvas class="shpl-cv" aria-hidden="true"></canvas>
                <article class="shpl-card">
                    <small>Solstice ۳.۰ · عرضهٔ جهانی</small>
                    <h2>هر عرضه، همه‌جا هم‌زمان</h2>
                    <p>از فرانکفورت تا سنگاپور، داشبورد عرضهٔ شما زنده است.</p>
                    <footer>
                        <button class="shpl-cta">شروع رایگان</button>
                        <button class="shpl-ghost">تماشای رویداد</button>
                    </footer>
                </article>
            </div>
            <div class="shpl-swatches" role="group" aria-label="پالت پلاسما">
                <button aria-pressed="true">شفق</button>
                <button>غروب</button>
                <button>اقیانوس</button>
                <button>ماگما</button>
            </div>
        </div>

        <style>
        .shpl-stage { position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 24rem;
                      display: grid; place-items: center; background: #1e1b4b; }
        .shpl-cv { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
        .shpl-card { position: relative; z-index: 1; inline-size: min(100%, 34rem); padding: 1.75rem;
                     border-radius: 1.25rem; background: #ffffffc8; backdrop-filter: blur(14px); text-align: center; }
        .shpl-swatches button[aria-pressed='true'] { outline: 2px solid #7c3aed; outline-offset: 2px; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="shpl" x-data="{ palette: 0 }">
        <div class="shpl-stage" @pointermove="bend($event)">
        <canvas class="shpl-cv" aria-hidden="true"></canvas>
        <article class="shpl-card">
        <small>Solstice 3.0 · global launch</small>
        <h2>Every launch, everywhere, at once</h2>
        <p>From Frankfurt to Singapore, your launch dashboard is live.</p>
        <footer>
        <button class="shpl-cta">Start free</button>
        <button class="shpl-ghost">Watch the keynote</button>
        </footer>
        </article>
        </div>
        <div class="shpl-swatches" role="group" aria-label="Plasma palette">
        <button aria-pressed="true">Aurora</button>
        <button>Sunset</button>
        <button>Ocean</button>
        <button>Magma</button>
        </div>
        </div>

        <style>
        .shpl-stage { position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 24rem;
                      display: grid; place-items: center; background: #1e1b4b; }
        .shpl-cv { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
        .shpl-card { position: relative; z-index: 1; inline-size: min(100%, 34rem); padding: 1.75rem;
                     border-radius: 1.25rem; background: #ffffffc8; backdrop-filter: blur(14px); text-align: center; }
        .shpl-swatches button[aria-pressed='true'] { outline: 2px solid #7c3aed; outline-offset: 2px; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Launch.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import PlasmaHero from '@/components/PlasmaHero';

        export default function Launch() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <PlasmaHero />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // PlasmaHero.jsx — shader plasma behind a product card, raw WebGL1
        import { useEffect, useRef, useState } from 'react';

        const PALETTES = [
          { id: 'aurora', hex: ['#1e1b4b', '#7c3aed', '#f472b6'] },
          { id: 'sunset', hex: ['#431407', '#ea580c', '#fbbf24'] },
          { id: 'ocean', hex: ['#082f49', '#0891b2', '#67e8f9'] },
          { id: 'magma', hex: ['#450a0a', '#dc2626', '#f59e0b'] },
        ];
        const FRAG = `precision mediump float;
        uniform vec2 u_res; uniform float u_time; uniform vec2 u_mouse;
        uniform vec3 u_a; uniform vec3 u_b; uniform vec3 u_c;
        void main() {
          vec2 uv = (gl_FragCoord.xy * 2.0 - u_res) / min(u_res.x, u_res.y);
          vec2 m = u_mouse * 0.45;
          float v = sin((uv.x + m.x) * 2.6 + u_time * 0.7) + sin((uv.y - m.y) * 2.2 - u_time * 0.45)
                  + sin((uv.x + uv.y) * 1.9 + u_time * 0.3) + sin(length(uv - m) * 4.2 - u_time * 0.9);
          float t = v / 8.0 + 0.5;
          vec3 col = mix(u_a, u_b, smoothstep(0.0, 0.55, t));
          col = mix(col, u_c, smoothstep(0.55, 1.0, t));
          gl_FragColor = vec4(col, 1.0);
        }`;
        const rgb = (h) => [1, 3, 5].map((i) => parseInt(h.slice(i, i + 2), 16) / 255);

        export default function PlasmaHero() {
          const canvasRef = useRef(null);
          const stageRef = useRef(null);
          const apiRef = useRef(null); // { redraw } — set once the GL effect runs
          const live = useRef({ palette: 0, mx: 0, my: 0, tx: 0, ty: 0 });
          const [pal, setPal] = useState(0);
          const [noGl, setNoGl] = useState(false);
          const [started, setStarted] = useState(false);

          useEffect(() => { live.current.palette = pal; apiRef.current?.redraw(); }, [pal]);

          useEffect(() => {
            const canvas = canvasRef.current;
            const gl = canvas.getContext('webgl', { antialias: false, powerPreference: 'low-power' });
            if (!gl) { setNoGl(true); return; }
            const sh = (type, src) => {
              const s = gl.createShader(type); gl.shaderSource(s, src); gl.compileShader(s); return s;
            };
            const prog = gl.createProgram();
            gl.attachShader(prog, sh(gl.VERTEX_SHADER, 'attribute vec2 a; void main() { gl_Position = vec4(a, 0.0, 1.0); }'));
            gl.attachShader(prog, sh(gl.FRAGMENT_SHADER, FRAG));
            gl.linkProgram(prog); gl.useProgram(prog);
            gl.bindBuffer(gl.ARRAY_BUFFER, gl.createBuffer());
            gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 1, -1, -1, 1, 1, 1]), gl.STATIC_DRAW);
            const a = gl.getAttribLocation(prog, 'a');
            gl.enableVertexAttribArray(a); gl.vertexAttribPointer(a, 2, gl.FLOAT, false, 0, 0);
            const U = {};
            for (const n of ['u_res', 'u_time', 'u_mouse', 'u_a', 'u_b', 'u_c']) U[n] = gl.getUniformLocation(prog, n);

            const mq = matchMedia('(prefers-reduced-motion: reduce)');
            const s = { raf: 0, visible: true, t0: performance.now(), last: 1 };
            const size = () => { // half resolution keeps the full-screen shader cheap
              canvas.width = Math.max(2, Math.round(canvas.clientWidth * 0.5));
              canvas.height = Math.max(2, Math.round(canvas.clientHeight * 0.5));
            };
            const draw = (t) => {
              const L = live.current;
              L.mx += (L.tx - L.mx) * 0.07; L.my += (L.ty - L.my) * 0.07; // the pointer eases through the flow
              gl.viewport(0, 0, gl.drawingBufferWidth, gl.drawingBufferHeight);
              gl.uniform2f(U.u_res, gl.drawingBufferWidth, gl.drawingBufferHeight);
              gl.uniform1f(U.u_time, t); gl.uniform2f(U.u_mouse, L.mx, L.my);
              const hex = PALETTES[L.palette].hex;
              gl.uniform3f(U.u_a, ...rgb(hex[0])); gl.uniform3f(U.u_b, ...rgb(hex[1])); gl.uniform3f(U.u_c, ...rgb(hex[2]));
              gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
            };
            const redraw = () => draw(mq.matches ? 4.2 : s.last); // still frame under reduced motion
            const run = () => {
              cancelAnimationFrame(s.raf);
              if (!s.visible) return;            // parked while off screen
              if (mq.matches) { redraw(); return; }
              const loop = (now) => { s.last = (now - s.t0) / 1000; draw(s.last); s.raf = requestAnimationFrame(loop); };
              s.raf = requestAnimationFrame(loop);
            };
            apiRef.current = { redraw };

            const io = new IntersectionObserver(([e]) => { s.visible = e.isIntersecting; run(); });
            io.observe(canvas);
            const ro = new ResizeObserver(() => { size(); redraw(); });
            ro.observe(canvas);
            const onMove = (e) => {
              const r = stageRef.current.getBoundingClientRect();
              live.current.tx = ((e.clientX - r.left) / r.width) * 2 - 1;
              live.current.ty = 1 - ((e.clientY - r.top) / r.height) * 2;
            };
            stageRef.current.addEventListener('pointermove', onMove);
            size(); run();

            return () => { // full cleanup — context included
              cancelAnimationFrame(s.raf); io.disconnect(); ro.disconnect();
              stageRef.current?.removeEventListener('pointermove', onMove);
              apiRef.current = null;
              gl.getExtension('WEBGL_lose_context')?.loseContext();
            };
          }, []);

          return (
            <>
              <style>{`
                .shpl-stage { position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 24rem;
                              display: grid; place-items: center; background: #1e1b4b; }
                .shpl-cv, .shpl-fallback { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
                .shpl-card { position: relative; z-index: 1; inline-size: min(100%, 34rem); margin: 2rem 1.25rem; padding: 1.75rem;
                             border-radius: 1.25rem; background: rgba(255, 255, 255, .8); backdrop-filter: blur(14px);
                             text-align: center; }
                .shpl-cta { block-size: 2.75rem; padding-inline: 1.5rem; border: none; border-radius: 999px;
                            background: #4f46e5; color: #fff; font: 600 .9rem system-ui; cursor: pointer; }
                .shpl-swatches { display: flex; gap: .75rem; justify-content: center; margin-block-start: 1rem; }
                .shpl-swatches button { inline-size: 2.5rem; block-size: 2.5rem; border-radius: 50%; cursor: pointer; }
                .shpl-swatches button[aria-pressed='true'] { outline: 2px solid #4f46e5; outline-offset: 3px; }
              `}</style>
              <div className="shpl-stage" ref={stageRef} onPointerMove={(e) => e.stopPropagation()}>
                {noGl
                  ? <div className="shpl-fallback" style={{ background: `linear-gradient(135deg, ${PALETTES[pal].hex.join(', ')})` }} />
                  : <canvas ref={canvasRef} className="shpl-cv" aria-hidden="true" />}
                <article className="shpl-card">
                  <small>Solstice 3.0 · global launch</small>
                  <h2>Every launch, everywhere, at once</h2>
                  <p>Frankfurt 12ms · Dublin 18ms · Singapore 23ms — the dashboard is live from second zero.</p>
                  <footer>
                    <button type="button" className="shpl-cta" onClick={() => setStarted(true)}>Start free</button>
                    {started && <p role="status">Workspace ready — the welcome email is on its way.</p>}
                  </footer>
                </article>
              </div>
              <div className="shpl-swatches" role="group" aria-label="Plasma palette">
                {PALETTES.map((p, i) => (
                  <button key={p.id} type="button" aria-pressed={pal === i} aria-label={p.id}
                    onClick={() => setPal(i)}
                    style={{ background: `linear-gradient(135deg, ${p.hex.join(', ')})` }} />
                ))}
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- PlasmaHero.vue — shader plasma behind a product card, raw WebGL1 -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';

        const PALETTES = [
          { id: 'aurora', hex: ['#1e1b4b', '#7c3aed', '#f472b6'] },
          { id: 'sunset', hex: ['#431407', '#ea580c', '#fbbf24'] },
          { id: 'ocean', hex: ['#082f49', '#0891b2', '#67e8f9'] },
          { id: 'magma', hex: ['#450a0a', '#dc2626', '#f59e0b'] },
        ];
        const FRAG = `precision mediump float;
        uniform vec2 u_res; uniform float u_time; uniform vec2 u_mouse;
        uniform vec3 u_a; uniform vec3 u_b; uniform vec3 u_c;
        void main() {
          vec2 uv = (gl_FragCoord.xy * 2.0 - u_res) / min(u_res.x, u_res.y);
          vec2 m = u_mouse * 0.45;
          float v = sin((uv.x + m.x) * 2.6 + u_time * 0.7) + sin((uv.y - m.y) * 2.2 - u_time * 0.45)
                  + sin((uv.x + uv.y) * 1.9 + u_time * 0.3) + sin(length(uv - m) * 4.2 - u_time * 0.9);
          float t = v / 8.0 + 0.5;
          vec3 col = mix(u_a, u_b, smoothstep(0.0, 0.55, t));
          col = mix(col, u_c, smoothstep(0.55, 1.0, t));
          gl_FragColor = vec4(col, 1.0);
        }`;
        const rgb = (h: string) => [1, 3, 5].map((i) => parseInt(h.slice(i, i + 2), 16) / 255);

        const canvas = ref<HTMLCanvasElement | null>(null);
        const stage = ref<HTMLElement | null>(null);
        const pal = ref(0);
        const noGl = ref(false);
        const started = ref(false);
        let stop: (() => void) | null = null;

        onMounted(() => {
          const cv = canvas.value!;
          const gl = cv.getContext('webgl', { antialias: false, powerPreference: 'low-power' });
          if (!gl) { noGl.value = true; return; }
          const sh = (type: number, src: string) => {
            const s = gl.createShader(type)!; gl.shaderSource(s, src); gl.compileShader(s); return s;
          };
          const prog = gl.createProgram()!;
          gl.attachShader(prog, sh(gl.VERTEX_SHADER, 'attribute vec2 a; void main() { gl_Position = vec4(a, 0.0, 1.0); }'));
          gl.attachShader(prog, sh(gl.FRAGMENT_SHADER, FRAG));
          gl.linkProgram(prog); gl.useProgram(prog);
          gl.bindBuffer(gl.ARRAY_BUFFER, gl.createBuffer());
          gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 1, -1, -1, 1, 1, 1]), gl.STATIC_DRAW);
          const a = gl.getAttribLocation(prog, 'a');
          gl.enableVertexAttribArray(a); gl.vertexAttribPointer(a, 2, gl.FLOAT, false, 0, 0);
          const U: Record<string, WebGLUniformLocation | null> = {};
          for (const n of ['u_res', 'u_time', 'u_mouse', 'u_a', 'u_b', 'u_c']) U[n] = gl.getUniformLocation(prog, n);

          const mq = matchMedia('(prefers-reduced-motion: reduce)');
          const live = { mx: 0, my: 0, tx: 0, ty: 0 };
          let raf = 0, visible = true, last = 1;
          const t0 = performance.now();
          const size = () => { // half resolution keeps the full-screen shader cheap
            cv.width = Math.max(2, Math.round(cv.clientWidth * 0.5));
            cv.height = Math.max(2, Math.round(cv.clientHeight * 0.5));
          };
          const draw = (t: number) => {
            live.mx += (live.tx - live.mx) * 0.07; live.my += (live.ty - live.my) * 0.07; // eased pointer
            gl.viewport(0, 0, gl.drawingBufferWidth, gl.drawingBufferHeight);
            gl.uniform2f(U.u_res, gl.drawingBufferWidth, gl.drawingBufferHeight);
            gl.uniform1f(U.u_time!, t); gl.uniform2f(U.u_mouse!, live.mx, live.my);
            const hex = PALETTES[pal.value].hex;
            gl.uniform3f(U.u_a!, ...rgb(hex[0])); gl.uniform3f(U.u_b!, ...rgb(hex[1])); gl.uniform3f(U.u_c!, ...rgb(hex[2]));
            gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
          };
          const redraw = () => draw(mq.matches ? 4.2 : last); // still frame under reduced motion
          const run = () => {
            cancelAnimationFrame(raf);
            if (!visible) return;            // parked while off screen
            if (mq.matches) { redraw(); return; }
            const loop = (now: number) => { last = (now - t0) / 1000; draw(last); raf = requestAnimationFrame(loop); };
            raf = requestAnimationFrame(loop);
          };

          const io = new IntersectionObserver(([e]) => { visible = e.isIntersecting; run(); });
          io.observe(cv);
          const ro = new ResizeObserver(() => { size(); redraw(); });
          ro.observe(cv);
          const onMove = (e: PointerEvent) => {
            const r = stage.value!.getBoundingClientRect();
            live.tx = ((e.clientX - r.left) / r.width) * 2 - 1;
            live.ty = 1 - ((e.clientY - r.top) / r.height) * 2;
          };
          stage.value!.addEventListener('pointermove', onMove);
          size(); run();

          stop = () => { // full cleanup — context included
            cancelAnimationFrame(raf); io.disconnect(); ro.disconnect();
            stage.value?.removeEventListener('pointermove', onMove);
            gl.getExtension('WEBGL_lose_context')?.loseContext();
          };
        });
        onBeforeUnmount(() => stop?.());
        </script>

        <template>
          <div class="shpl-stage" ref="stage">
            <div v-if="noGl" class="shpl-fallback"
                 :style="{ background: 'linear-gradient(135deg, ' + PALETTES[pal].hex.join(', ') + ')' }"></div>
            <canvas v-else ref="canvas" class="shpl-cv" aria-hidden="true"></canvas>
            <article class="shpl-card">
              <small>Solstice 3.0 · global launch</small>
              <h2>Every launch, everywhere, at once</h2>
              <p>Frankfurt 12ms · Dublin 18ms · Singapore 23ms — the dashboard is live from second zero.</p>
              <footer>
                <button type="button" class="shpl-cta" @click="started = true">Start free</button>
                <p v-if="started" role="status">Workspace ready — the welcome email is on its way.</p>
              </footer>
            </article>
          </div>
          <div class="shpl-swatches" role="group" aria-label="Plasma palette">
            <button v-for="(p, i) in PALETTES" :key="p.id" type="button" :aria-pressed="pal === i"
                    :aria-label="p.id" @click="pal = i"
                    :style="{ background: 'linear-gradient(135deg, ' + p.hex.join(', ') + ')' }"></button>
          </div>
        </template>

        <style scoped>
        .shpl-stage { position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 24rem;
                      display: grid; place-items: center; background: #1e1b4b; }
        .shpl-cv, .shpl-fallback { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
        .shpl-card { position: relative; z-index: 1; inline-size: min(100%, 34rem); margin: 2rem 1.25rem; padding: 1.75rem;
                     border-radius: 1.25rem; background: rgba(255, 255, 255, .8); backdrop-filter: blur(14px); text-align: center; }
        .shpl-cta { block-size: 2.75rem; padding-inline: 1.5rem; border: none; border-radius: 999px;
                    background: #4f46e5; color: #fff; font: 600 .9rem system-ui; cursor: pointer; }
        .shpl-swatches { display: flex; gap: .75rem; justify-content: center; margin-block-start: 1rem; }
        .shpl-swatches button { inline-size: 2.5rem; block-size: 2.5rem; border-radius: 50%; cursor: pointer; }
        .shpl-swatches button[aria-pressed='true'] { outline: 2px solid #4f46e5; outline-offset: 3px; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- PlasmaHero.svelte — shader plasma behind a product card, raw WebGL1 -->
        <script lang="ts">
          import { onMount } from 'svelte';

          const PALETTES = [
            { id: 'aurora', hex: ['#1e1b4b', '#7c3aed', '#f472b6'] },
            { id: 'sunset', hex: ['#431407', '#ea580c', '#fbbf24'] },
            { id: 'ocean', hex: ['#082f49', '#0891b2', '#67e8f9'] },
            { id: 'magma', hex: ['#450a0a', '#dc2626', '#f59e0b'] },
          ];
          const FRAG = `precision mediump float;
          uniform vec2 u_res; uniform float u_time; uniform vec2 u_mouse;
          uniform vec3 u_a; uniform vec3 u_b; uniform vec3 u_c;
          void main() {
            vec2 uv = (gl_FragCoord.xy * 2.0 - u_res) / min(u_res.x, u_res.y);
            vec2 m = u_mouse * 0.45;
            float v = sin((uv.x + m.x) * 2.6 + u_time * 0.7) + sin((uv.y - m.y) * 2.2 - u_time * 0.45)
                    + sin((uv.x + uv.y) * 1.9 + u_time * 0.3) + sin(length(uv - m) * 4.2 - u_time * 0.9);
            float t = v / 8.0 + 0.5;
            vec3 col = mix(u_a, u_b, smoothstep(0.0, 0.55, t));
            col = mix(col, u_c, smoothstep(0.55, 1.0, t));
            gl_FragColor = vec4(col, 1.0);
          }`;
          const rgb = (h: string) => [1, 3, 5].map((i) => parseInt(h.slice(i, i + 2), 16) / 255);

          let canvas: HTMLCanvasElement;
          let stage: HTMLElement;
          let pal = $state(0);
          let noGl = $state(false);
          let started = $state(false);

          onMount(() => {
            const gl = canvas.getContext('webgl', { antialias: false, powerPreference: 'low-power' });
            if (!gl) { noGl = true; return; }
            const sh = (type: number, src: string) => {
              const s = gl.createShader(type)!; gl.shaderSource(s, src); gl.compileShader(s); return s;
            };
            const prog = gl.createProgram()!;
            gl.attachShader(prog, sh(gl.VERTEX_SHADER, 'attribute vec2 a; void main() { gl_Position = vec4(a, 0.0, 1.0); }'));
            gl.attachShader(prog, sh(gl.FRAGMENT_SHADER, FRAG));
            gl.linkProgram(prog); gl.useProgram(prog);
            gl.bindBuffer(gl.ARRAY_BUFFER, gl.createBuffer());
            gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 1, -1, -1, 1, 1, 1]), gl.STATIC_DRAW);
            const a = gl.getAttribLocation(prog, 'a');
            gl.enableVertexAttribArray(a); gl.vertexAttribPointer(a, 2, gl.FLOAT, false, 0, 0);
            const U: Record<string, WebGLUniformLocation | null> = {};
            for (const n of ['u_res', 'u_time', 'u_mouse', 'u_a', 'u_b', 'u_c']) U[n] = gl.getUniformLocation(prog, n);

            const mq = matchMedia('(prefers-reduced-motion: reduce)');
            const live = { mx: 0, my: 0, tx: 0, ty: 0 };
            let raf = 0, visible = true, last = 1;
            const t0 = performance.now();
            const size = () => { // half resolution keeps the full-screen shader cheap
              canvas.width = Math.max(2, Math.round(canvas.clientWidth * 0.5));
              canvas.height = Math.max(2, Math.round(canvas.clientHeight * 0.5));
            };
            const draw = (t: number) => {
              live.mx += (live.tx - live.mx) * 0.07; live.my += (live.ty - live.my) * 0.07; // eased pointer
              gl.viewport(0, 0, gl.drawingBufferWidth, gl.drawingBufferHeight);
              gl.uniform2f(U.u_res!, gl.drawingBufferWidth, gl.drawingBufferHeight);
              gl.uniform1f(U.u_time!, t); gl.uniform2f(U.u_mouse!, live.mx, live.my);
              const hex = PALETTES[pal].hex;
              gl.uniform3f(U.u_a!, ...rgb(hex[0])); gl.uniform3f(U.u_b!, ...rgb(hex[1])); gl.uniform3f(U.u_c!, ...rgb(hex[2]));
              gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
            };
            const redraw = () => draw(mq.matches ? 4.2 : last); // still frame under reduced motion
            const run = () => {
              cancelAnimationFrame(raf);
              if (!visible) return;            // parked while off screen
              if (mq.matches) { redraw(); return; }
              const loop = (now: number) => { last = (now - t0) / 1000; draw(last); raf = requestAnimationFrame(loop); };
              raf = requestAnimationFrame(loop);
            };

            const io = new IntersectionObserver(([e]) => { visible = e.isIntersecting; run(); });
            io.observe(canvas);
            const ro = new ResizeObserver(() => { size(); redraw(); });
            ro.observe(canvas);
            const onMove = (e: PointerEvent) => {
              const r = stage.getBoundingClientRect();
              live.tx = ((e.clientX - r.left) / r.width) * 2 - 1;
              live.ty = 1 - ((e.clientY - r.top) / r.height) * 2;
            };
            stage.addEventListener('pointermove', onMove);
            size(); run();

            return () => { // full cleanup — context included
              cancelAnimationFrame(raf); io.disconnect(); ro.disconnect();
              stage.removeEventListener('pointermove', onMove);
              gl.getExtension('WEBGL_lose_context')?.loseContext();
            };
          });
        </script>

        <div class="shpl-stage" bind:this={stage}>
          {#if noGl}
            <div class="shpl-fallback" style={`background: linear-gradient(135deg, ${PALETTES[pal].hex.join(', ')})`}></div>
          {:else}
            <canvas bind:this={canvas} class="shpl-cv" aria-hidden="true"></canvas>
          {/if}
          <article class="shpl-card">
            <small>Solstice 3.0 · global launch</small>
            <h2>Every launch, everywhere, at once</h2>
            <p>Frankfurt 12ms · Dublin 18ms · Singapore 23ms — the dashboard is live from second zero.</p>
            <footer>
              <button type="button" class="shpl-cta" onclick={() => (started = true)}>Start free</button>
              {#if started}<p role="status">Workspace ready — the welcome email is on its way.</p>{/if}
            </footer>
          </article>
        </div>
        <div class="shpl-swatches" role="group" aria-label="Plasma palette">
          {#each PALETTES as p, i (p.id)}
            <button type="button" aria-pressed={pal === i} aria-label={p.id} onclick={() => (pal = i)}
                    style={`background: linear-gradient(135deg, ${p.hex.join(', ')})`}></button>
          {/each}
        </div>

        <style>
        .shpl-stage { position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 24rem;
                      display: grid; place-items: center; background: #1e1b4b; }
        .shpl-cv, .shpl-fallback { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
        .shpl-card { position: relative; z-index: 1; inline-size: min(100%, 34rem); margin: 2rem 1.25rem; padding: 1.75rem;
                     border-radius: 1.25rem; background: rgba(255, 255, 255, .8); backdrop-filter: blur(14px); text-align: center; }
        .shpl-cta { block-size: 2.75rem; padding-inline: 1.5rem; border: none; border-radius: 999px;
                    background: #4f46e5; color: #fff; font: 600 .9rem system-ui; cursor: pointer; }
        .shpl-swatches { display: flex; gap: .75rem; justify-content: center; margin-block-start: 1rem; }
        .shpl-swatches button { inline-size: 2.5rem; block-size: 2.5rem; border-radius: 50%; cursor: pointer; }
        .shpl-swatches button[aria-pressed='true'] { outline: 2px solid #4f46e5; outline-offset: 3px; }
        </style>
        SVELTE,
        ],
    ],

    'noise-field' => [
        'title' => ['fa' => 'میدان نویز', 'en' => 'Noise field'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'بافت پس‌زمینهٔ بخش از پنج اکتاو fbm با پیچش دامنه بافته می‌شود — نویزی سیال که با اسلایدرهای مقیاس و سرعت لگام می‌گیرد یا رها می‌شود. رمپ رنگ در تم تیره کنتراست را بالا می‌برد؛ WebGL خام، نصف رزولوشن، پارکِ خارج از دید، قاب ثابت در حرکت کاهش‌یافته و گرادیان ثابت بدون WebGL.',
            'en' => 'A section background woven from five octaves of domain-warped fbm — fluid noise you rein in or let loose with the scale and speed sliders. The colour ramp turns the contrast up in dark theme; raw WebGL1, half resolution, parked off screen, a still frame under reduced motion and a static gradient without WebGL.',
        ],
        'js' => true,
        'docs' => 'https://www.shadertoy.com',
        'props' => [
            ['name' => 'octaves', 'type' => 'int', 'default' => '5', 'note' => [
                'fa' => 'پنج اکتاو fbm؛ هر اکتاو نصف دامنه و حدود دو برابر بسامد قبلی.',
                'en' => 'Five fbm octaves; each octave halves the amplitude and roughly doubles the frequency.',
            ]],
            ['name' => 'scale', 'type' => 'range', 'default' => "'0.6 – 3.0'", 'note' => [
                'fa' => 'اسلایدر مقیاس؛ مختصات uv قبل از نمونه‌برداری fbm در آن ضرب می‌شود — از مه یکدست تا رگه‌های ریز.',
                'en' => 'The scale slider; uv is multiplied by it before the fbm sampling — from even mist to fine veins.',
            ]],
            ['name' => 'speed', 'type' => 'range', 'default' => "'0 – 2'", 'note' => [
                'fa' => 'اسلایدر سرعت؛ رانش زمانی میدان و پیچش دامنه با آن ضرب می‌شود.',
                'en' => 'The speed slider; the field’s time drift and domain warp are both multiplied by it.',
            ]],
            ['name' => 'contrast', 'type' => 'theme', 'default' => "'dark ramp'", 'note' => [
                'fa' => 'رمپ رنگ دوقطعه است: در تم تیره پایهٔ عمیق‌تر و موج‌های اوج روشن‌تر می‌شوند تا کنتراست حفظ شود.',
                'en' => 'The colour ramp is two-ended: in dark theme the base deepens and the crests brighten to keep contrast.',
            ]],
            ['name' => 'resolution', 'type' => 'scale', 'default' => "'0.5×'", 'note' => [
                'fa' => 'بوم با نصف رزولوشن CSS رندر و بزرگ‌نمایی می‌شود — fbm پنج‌اکتاوی ارزان می‌ماند.',
                'en' => 'The canvas renders at half CSS resolution and upscales — the five-octave fbm stays cheap.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="shnf" x-data="{ scale: 1.4, speed: 0.8 }">
            <div class="shnf-stage">
                <canvas class="shnf-cv" aria-hidden="true"></canvas>
                <div class="shnf-copy">
                    <small>Meridian Cloud · موتورخانه</small>
                    <h2>زیرساختی که نمی‌خوابد</h2>
                    <p>هر ثانیه ۲٫۴ میلیون درخواست را در ۳۸ منطقه جابه‌جا می‌کنیم.</p>
                </div>
                <div class="shnf-panel">
                    <label>مقیاس <input type="range" min="0.6" max="3" step="0.1" x-model.number="scale"></label>
                    <label>سرعت <input type="range" min="0" max="2" step="0.1" x-model.number="speed"></label>
                </div>
            </div>
        </div>

        <style>
        .shnf-stage { position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 22rem;
                      display: grid; align-content: space-between; padding: 1.75rem; background: #eef2ff; }
        .shnf-cv { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
        .shnf-copy, .shnf-panel { position: relative; z-index: 1; }
        .shnf-panel { display: flex; flex-wrap: wrap; gap: 1rem 2rem; padding: .9rem 1.1rem; border-radius: 1rem;
                      background: #ffffffb3; backdrop-filter: blur(12px); }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="shnf" x-data="{ scale: 1.4, speed: 0.8 }">
        <div class="shnf-stage">
        <canvas class="shnf-cv" aria-hidden="true"></canvas>
        <div class="shnf-copy">
        <small>Meridian Cloud · engine room</small>
        <h2>Infrastructure that never sleeps</h2>
        <p>We route 2.4M requests a second across 38 regions.</p>
        </div>
        <div class="shnf-panel">
        <label>Scale <input type="range" min="0.6" max="3" step="0.1" x-model.number="scale"></label>
        <label>Speed <input type="range" min="0" max="2" step="0.1" x-model.number="speed"></label>
        </div>
        </div>
        </div>

        <style>
        .shnf-stage { position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 22rem;
                      display: grid; align-content: space-between; padding: 1.75rem; background: #eef2ff; }
        .shnf-cv { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
        .shnf-copy, .shnf-panel { position: relative; z-index: 1; }
        .shnf-panel { display: flex; flex-wrap: wrap; gap: 1rem 2rem; padding: .9rem 1.1rem; border-radius: 1rem;
                      background: #ffffffb3; backdrop-filter: blur(12px); }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/EngineRoom.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import NoiseSection from '@/components/NoiseSection';

        export default function EngineRoom() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <NoiseSection />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // NoiseSection.jsx — domain-warped fbm as a section background, raw WebGL1
        import { useEffect, useRef, useState } from 'react';

        const FRAG = `precision mediump float;
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
          float n = fbm(q + fbm(q * 1.7 + u_time * 0.02 * u_speed) * 0.6); // domain warp keeps it fluid
          vec3 col = mix(u_lo, u_mid, smoothstep(0.15, 0.75, n));
          col = mix(col, u_hi, smoothstep(0.72, 0.98, n));
          gl_FragColor = vec4(col, 1.0);
        }`;
        const RAMPS = {
          light: ['#eef2ff', '#a5b4fc', '#4338ca'],
          dark: ['#020617', '#155e75', '#7dd3fc'], // deeper base, brighter crests — contrast in the dark
        };
        const rgb = (h) => [1, 3, 5].map((i) => parseInt(h.slice(i, i + 2), 16) / 255);
        const isDark = () => {
          const t = document.documentElement.dataset.theme;
          return t === 'dark' || (!t && matchMedia('(prefers-color-scheme: dark)').matches);
        };

        export default function NoiseSection() {
          const canvasRef = useRef(null);
          const apiRef = useRef(null);
          const live = useRef({ scale: 1.4, speed: 0.8 });
          const [scale, setScale] = useState(1.4);
          const [speed, setSpeed] = useState(0.8);
          const [noGl, setNoGl] = useState(false);

          useEffect(() => {
            live.current = { scale, speed }; apiRef.current?.redraw();
          }, [scale, speed]);

          useEffect(() => {
            const canvas = canvasRef.current;
            const gl = canvas.getContext('webgl', { antialias: false, powerPreference: 'low-power' });
            if (!gl) { setNoGl(true); return; }
            const sh = (type, src) => {
              const s = gl.createShader(type); gl.shaderSource(s, src); gl.compileShader(s); return s;
            };
            const prog = gl.createProgram();
            gl.attachShader(prog, sh(gl.VERTEX_SHADER, 'attribute vec2 a; void main() { gl_Position = vec4(a, 0.0, 1.0); }'));
            gl.attachShader(prog, sh(gl.FRAGMENT_SHADER, FRAG));
            gl.linkProgram(prog); gl.useProgram(prog);
            gl.bindBuffer(gl.ARRAY_BUFFER, gl.createBuffer());
            gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 1, -1, -1, 1, 1, 1]), gl.STATIC_DRAW);
            const a = gl.getAttribLocation(prog, 'a');
            gl.enableVertexAttribArray(a); gl.vertexAttribPointer(a, 2, gl.FLOAT, false, 0, 0);
            const U = {};
            for (const n of ['u_res', 'u_time', 'u_scale', 'u_speed', 'u_lo', 'u_mid', 'u_hi']) {
              U[n] = gl.getUniformLocation(prog, n);
            }

            const mq = matchMedia('(prefers-reduced-motion: reduce)');
            const s = { raf: 0, visible: true, t0: performance.now(), last: 2 };
            const size = () => { // half resolution keeps the fbm cheap
              canvas.width = Math.max(2, Math.round(canvas.clientWidth * 0.5));
              canvas.height = Math.max(2, Math.round(canvas.clientHeight * 0.5));
            };
            const draw = (t) => {
              gl.viewport(0, 0, gl.drawingBufferWidth, gl.drawingBufferHeight);
              gl.uniform2f(U.u_res, gl.drawingBufferWidth, gl.drawingBufferHeight);
              gl.uniform1f(U.u_time, t);
              gl.uniform1f(U.u_scale, live.current.scale); gl.uniform1f(U.u_speed, live.current.speed);
              const ramp = RAMPS[isDark() ? 'dark' : 'light'];
              gl.uniform3f(U.u_lo, ...rgb(ramp[0])); gl.uniform3f(U.u_mid, ...rgb(ramp[1])); gl.uniform3f(U.u_hi, ...rgb(ramp[2]));
              gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
            };
            const redraw = () => draw(mq.matches ? 6.0 : s.last); // still frame under reduced motion
            const run = () => {
              cancelAnimationFrame(s.raf);
              if (!s.visible) return;            // parked while off screen
              if (mq.matches) { redraw(); return; }
              const loop = (now) => { s.last = (now - s.t0) / 1000; draw(s.last); s.raf = requestAnimationFrame(loop); };
              s.raf = requestAnimationFrame(loop);
            };
            apiRef.current = { redraw };

            const io = new IntersectionObserver(([e]) => { s.visible = e.isIntersecting; run(); });
            io.observe(canvas);
            const ro = new ResizeObserver(() => { size(); redraw(); });
            ro.observe(canvas);
            const mo = new MutationObserver(redraw); // the data-theme flip re-ramps the colours
            mo.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
            size(); run();

            return () => { // full cleanup — context included
              cancelAnimationFrame(s.raf); io.disconnect(); ro.disconnect(); mo.disconnect();
              apiRef.current = null;
              gl.getExtension('WEBGL_lose_context')?.loseContext();
            };
          }, []);

          return (
            <>
              <style>{`
                .shnf-stage { position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 22rem;
                              display: grid; align-content: space-between; padding: 1.75rem; background: #eef2ff; }
                .shnf-cv, .shnf-fallback { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
                .shnf-copy, .shnf-panel { position: relative; z-index: 1; }
                .shnf-panel { display: flex; flex-wrap: wrap; gap: 1rem 2rem; padding: .9rem 1.1rem; border-radius: 1rem;
                              background: rgba(255, 255, 255, .7); backdrop-filter: blur(12px); }
                .shnf-panel label { display: grid; gap: .35rem; font: 600 .78rem system-ui; }
              `}</style>
              <div className="shnf-stage">
                {noGl
                  ? <div className="shnf-fallback" style={{ background: 'linear-gradient(160deg, #eef2ff, #a5b4fc 55%, #4338ca)' }} />
                  : <canvas ref={canvasRef} className="shnf-cv" aria-hidden="true" />}
                <div className="shnf-copy">
                  <small>Meridian Cloud · engine room</small>
                  <h2>Infrastructure that never sleeps</h2>
                  <p>We route 2.4M requests a second across 38 regions — Dublin to Singapore. This noise is that network’s pulse.</p>
                </div>
                <div className="shnf-panel">
                  <label>Scale
                    <input type="range" min="0.6" max="3" step="0.1" value={scale}
                           onChange={(e) => setScale(+e.target.value)} />
                  </label>
                  <label>Speed
                    <input type="range" min="0" max="2" step="0.1" value={speed}
                           onChange={(e) => setSpeed(+e.target.value)} />
                  </label>
                </div>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- NoiseSection.vue — domain-warped fbm as a section background, raw WebGL1 -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';

        const FRAG = `precision mediump float;
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
          float n = fbm(q + fbm(q * 1.7 + u_time * 0.02 * u_speed) * 0.6); // domain warp keeps it fluid
          vec3 col = mix(u_lo, u_mid, smoothstep(0.15, 0.75, n));
          col = mix(col, u_hi, smoothstep(0.72, 0.98, n));
          gl_FragColor = vec4(col, 1.0);
        }`;
        const RAMPS = {
          light: ['#eef2ff', '#a5b4fc', '#4338ca'],
          dark: ['#020617', '#155e75', '#7dd3fc'], // deeper base, brighter crests — contrast in the dark
        };
        const rgb = (h: string) => [1, 3, 5].map((i) => parseInt(h.slice(i, i + 2), 16) / 255);
        const isDark = () => {
          const t = document.documentElement.dataset.theme;
          return t === 'dark' || (!t && matchMedia('(prefers-color-scheme: dark)').matches);
        };

        const canvas = ref<HTMLCanvasElement | null>(null);
        const scale = ref(1.4);
        const speed = ref(0.8);
        const noGl = ref(false);
        let stop: (() => void) | null = null;

        onMounted(() => {
          const cv = canvas.value!;
          const gl = cv.getContext('webgl', { antialias: false, powerPreference: 'low-power' });
          if (!gl) { noGl.value = true; return; }
          const sh = (type: number, src: string) => {
            const s = gl.createShader(type)!; gl.shaderSource(s, src); gl.compileShader(s); return s;
          };
          const prog = gl.createProgram()!;
          gl.attachShader(prog, sh(gl.VERTEX_SHADER, 'attribute vec2 a; void main() { gl_Position = vec4(a, 0.0, 1.0); }'));
          gl.attachShader(prog, sh(gl.FRAGMENT_SHADER, FRAG));
          gl.linkProgram(prog); gl.useProgram(prog);
          gl.bindBuffer(gl.ARRAY_BUFFER, gl.createBuffer());
          gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 1, -1, -1, 1, 1, 1]), gl.STATIC_DRAW);
          const a = gl.getAttribLocation(prog, 'a');
          gl.enableVertexAttribArray(a); gl.vertexAttribPointer(a, 2, gl.FLOAT, false, 0, 0);
          const U: Record<string, WebGLUniformLocation | null> = {};
          for (const n of ['u_res', 'u_time', 'u_scale', 'u_speed', 'u_lo', 'u_mid', 'u_hi']) {
            U[n] = gl.getUniformLocation(prog, n);
          }

          const mq = matchMedia('(prefers-reduced-motion: reduce)');
          let raf = 0, visible = true, last = 2;
          const t0 = performance.now();
          const size = () => { // half resolution keeps the fbm cheap
            cv.width = Math.max(2, Math.round(cv.clientWidth * 0.5));
            cv.height = Math.max(2, Math.round(cv.clientHeight * 0.5));
          };
          const draw = (t: number) => {
            gl.viewport(0, 0, gl.drawingBufferWidth, gl.drawingBufferHeight);
            gl.uniform2f(U.u_res!, gl.drawingBufferWidth, gl.drawingBufferHeight);
            gl.uniform1f(U.u_time!, t);
            gl.uniform1f(U.u_scale!, scale.value); gl.uniform1f(U.u_speed!, speed.value);
            const ramp = RAMPS[isDark() ? 'dark' : 'light'];
            gl.uniform3f(U.u_lo!, ...rgb(ramp[0])); gl.uniform3f(U.u_mid!, ...rgb(ramp[1])); gl.uniform3f(U.u_hi!, ...rgb(ramp[2]));
            gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
          };
          const redraw = () => draw(mq.matches ? 6.0 : last); // still frame under reduced motion
          const run = () => {
            cancelAnimationFrame(raf);
            if (!visible) return;            // parked while off screen
            if (mq.matches) { redraw(); return; }
            const loop = (now: number) => { last = (now - t0) / 1000; draw(last); raf = requestAnimationFrame(loop); };
            raf = requestAnimationFrame(loop);
          };

          const io = new IntersectionObserver(([e]) => { visible = e.isIntersecting; run(); });
          io.observe(cv);
          const ro = new ResizeObserver(() => { size(); redraw(); });
          ro.observe(cv);
          const mo = new MutationObserver(redraw); // the data-theme flip re-ramps the colours
          mo.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
          size(); run();

          stop = () => { // full cleanup — context included
            cancelAnimationFrame(raf); io.disconnect(); ro.disconnect(); mo.disconnect();
            gl.getExtension('WEBGL_lose_context')?.loseContext();
          };
        });
        onBeforeUnmount(() => stop?.());
        </script>

        <template>
          <div class="shnf-stage">
            <div v-if="noGl" class="shnf-fallback"
                 style="background: linear-gradient(160deg, #eef2ff, #a5b4fc 55%, #4338ca)"></div>
            <canvas v-else ref="canvas" class="shnf-cv" aria-hidden="true"></canvas>
            <div class="shnf-copy">
              <small>Meridian Cloud · engine room</small>
              <h2>Infrastructure that never sleeps</h2>
              <p>We route 2.4M requests a second across 38 regions — Dublin to Singapore. This noise is that network’s pulse.</p>
            </div>
            <div class="shnf-panel">
              <label>Scale <input type="range" min="0.6" max="3" step="0.1" v-model.number="scale"></label>
              <label>Speed <input type="range" min="0" max="2" step="0.1" v-model.number="speed"></label>
            </div>
          </div>
        </template>

        <style scoped>
        .shnf-stage { position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 22rem;
                      display: grid; align-content: space-between; padding: 1.75rem; background: #eef2ff; }
        .shnf-cv, .shnf-fallback { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
        .shnf-copy, .shnf-panel { position: relative; z-index: 1; }
        .shnf-panel { display: flex; flex-wrap: wrap; gap: 1rem 2rem; padding: .9rem 1.1rem; border-radius: 1rem;
                      background: rgba(255, 255, 255, .7); backdrop-filter: blur(12px); }
        .shnf-panel label { display: grid; gap: .35rem; font: 600 .78rem system-ui; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- NoiseSection.svelte — domain-warped fbm as a section background, raw WebGL1 -->
        <script lang="ts">
          import { onMount } from 'svelte';

          const FRAG = `precision mediump float;
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
            float n = fbm(q + fbm(q * 1.7 + u_time * 0.02 * u_speed) * 0.6); // domain warp keeps it fluid
            vec3 col = mix(u_lo, u_mid, smoothstep(0.15, 0.75, n));
            col = mix(col, u_hi, smoothstep(0.72, 0.98, n));
            gl_FragColor = vec4(col, 1.0);
          }`;
          const RAMPS = {
            light: ['#eef2ff', '#a5b4fc', '#4338ca'],
            dark: ['#020617', '#155e75', '#7dd3fc'], // deeper base, brighter crests — contrast in the dark
          };
          const rgb = (h: string) => [1, 3, 5].map((i) => parseInt(h.slice(i, i + 2), 16) / 255);
          const isDark = () => {
            const t = document.documentElement.dataset.theme;
            return t === 'dark' || (!t && matchMedia('(prefers-color-scheme: dark)').matches);
          };

          let canvas: HTMLCanvasElement;
          let scale = $state(1.4);
          let speed = $state(0.8);
          let noGl = $state(false);

          onMount(() => {
            const gl = canvas.getContext('webgl', { antialias: false, powerPreference: 'low-power' });
            if (!gl) { noGl = true; return; }
            const sh = (type: number, src: string) => {
              const s = gl.createShader(type)!; gl.shaderSource(s, src); gl.compileShader(s); return s;
            };
            const prog = gl.createProgram()!;
            gl.attachShader(prog, sh(gl.VERTEX_SHADER, 'attribute vec2 a; void main() { gl_Position = vec4(a, 0.0, 1.0); }'));
            gl.attachShader(prog, sh(gl.FRAGMENT_SHADER, FRAG));
            gl.linkProgram(prog); gl.useProgram(prog);
            gl.bindBuffer(gl.ARRAY_BUFFER, gl.createBuffer());
            gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 1, -1, -1, 1, 1, 1]), gl.STATIC_DRAW);
            const a = gl.getAttribLocation(prog, 'a');
            gl.enableVertexAttribArray(a); gl.vertexAttribPointer(a, 2, gl.FLOAT, false, 0, 0);
            const U: Record<string, WebGLUniformLocation | null> = {};
            for (const n of ['u_res', 'u_time', 'u_scale', 'u_speed', 'u_lo', 'u_mid', 'u_hi']) {
              U[n] = gl.getUniformLocation(prog, n);
            }

            const mq = matchMedia('(prefers-reduced-motion: reduce)');
            let raf = 0, visible = true, last = 2;
            const t0 = performance.now();
            const size = () => { // half resolution keeps the fbm cheap
              canvas.width = Math.max(2, Math.round(canvas.clientWidth * 0.5));
              canvas.height = Math.max(2, Math.round(canvas.clientHeight * 0.5));
            };
            const draw = (t: number) => {
              gl.viewport(0, 0, gl.drawingBufferWidth, gl.drawingBufferHeight);
              gl.uniform2f(U.u_res!, gl.drawingBufferWidth, gl.drawingBufferHeight);
              gl.uniform1f(U.u_time!, t);
              gl.uniform1f(U.u_scale!, scale); gl.uniform1f(U.u_speed!, speed);
              const ramp = RAMPS[isDark() ? 'dark' : 'light'];
              gl.uniform3f(U.u_lo!, ...rgb(ramp[0])); gl.uniform3f(U.u_mid!, ...rgb(ramp[1])); gl.uniform3f(U.u_hi!, ...rgb(ramp[2]));
              gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
            };
            const redraw = () => draw(mq.matches ? 6.0 : last); // still frame under reduced motion
            const run = () => {
              cancelAnimationFrame(raf);
              if (!visible) return;            // parked while off screen
              if (mq.matches) { redraw(); return; }
              const loop = (now: number) => { last = (now - t0) / 1000; draw(last); raf = requestAnimationFrame(loop); };
              raf = requestAnimationFrame(loop);
            };

            const io = new IntersectionObserver(([e]) => { visible = e.isIntersecting; run(); });
            io.observe(canvas);
            const ro = new ResizeObserver(() => { size(); redraw(); });
            ro.observe(canvas);
            const mo = new MutationObserver(redraw); // the data-theme flip re-ramps the colours
            mo.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
            size(); run();

            return () => { // full cleanup — context included
              cancelAnimationFrame(raf); io.disconnect(); ro.disconnect(); mo.disconnect();
              gl.getExtension('WEBGL_lose_context')?.loseContext();
            };
          });
        </script>

        <div class="shnf-stage">
          {#if noGl}
            <div class="shnf-fallback" style="background: linear-gradient(160deg, #eef2ff, #a5b4fc 55%, #4338ca)"></div>
          {:else}
            <canvas bind:this={canvas} class="shnf-cv" aria-hidden="true"></canvas>
          {/if}
          <div class="shnf-copy">
            <small>Meridian Cloud · engine room</small>
            <h2>Infrastructure that never sleeps</h2>
            <p>We route 2.4M requests a second across 38 regions — Dublin to Singapore. This noise is that network’s pulse.</p>
          </div>
          <div class="shnf-panel">
            <label>Scale <input type="range" min="0.6" max="3" step="0.1" value={scale} oninput={(e) => (scale = +e.currentTarget.value)} /></label>
            <label>Speed <input type="range" min="0" max="2" step="0.1" value={speed} oninput={(e) => (speed = +e.currentTarget.value)} /></label>
          </div>
        </div>

        <style>
        .shnf-stage { position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 22rem;
                      display: grid; align-content: space-between; padding: 1.75rem; background: #eef2ff; }
        .shnf-cv, .shnf-fallback { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
        .shnf-copy, .shnf-panel { position: relative; z-index: 1; }
        .shnf-panel { display: flex; flex-wrap: wrap; gap: 1rem 2rem; padding: .9rem 1.1rem; border-radius: 1rem;
                      background: rgba(255, 255, 255, .7); backdrop-filter: blur(12px); }
        .shnf-panel label { display: grid; gap: .35rem; font: 600 .78rem system-ui; }
        </style>
        SVELTE,
        ],
    ],

    'ripple-pond' => [
        'title' => ['fa' => 'برکهٔ موج', 'en' => 'Ripple pond'],
        'icon' => 'zap',
        'oneLiner' => [
            'fa' => 'هر کلیک یا لمس روی بوم یک موج دایره‌ای می‌اندازد که با زمان محو می‌شود — تا دوازده موج هم‌زمان روی یک سطح. شیدر WebGL خام از حلقه‌های سینوسی با میرایی نمایی؛ دکمه‌های شناور زیر بوم قطره می‌ریزند، سه‌تایی می‌چپانند و سطح را آرام می‌کنند.',
            'en' => 'Every click or tap on the canvas drops a circular ripple that fades with time — up to twelve waves sharing one surface. A raw WebGL1 shader of sine rings with exponential decay; the floating buttons below rain a drop, drop three at once and calm the surface.',
        ],
        'js' => true,
        'docs' => 'https://www.shadertoy.com',
        'props' => [
            ['name' => 'ripples', 'type' => 'uniform vec4[12]', 'default' => "'ring buffer'", 'note' => [
                'fa' => 'تا ۱۲ موج هم‌زمان؛ هر قطره مختصات، زمان تولد و شدتش را در خانهٔ بعدی حلقه می‌نویسد.',
                'en' => 'Up to twelve waves at once; each drop writes its position, birth time and strength into the next ring-buffer slot.',
            ]],
            ['name' => 'decay', 'type' => 'exp', 'default' => "'≈ 3s'", 'note' => [
                'fa' => 'میرایی نمایی: دامنه در حدود سه ثانیه خاموش می‌شود و فاصله از مرکز هم ضرب در میرایی فضایی می‌شود.',
                'en' => 'Exponential decay: the amplitude dies out over about three seconds, and distance from the centre pays a spatial toll too.',
            ]],
            ['name' => 'ring', 'type' => 'sine', 'default' => "'26 rad⁻¹ · 8 rad/s'", 'note' => [
                'fa' => 'حلقه‌ها سینوسی‌اند؛ بسامد مکانی چگالی حلقه‌ها و بسامد زمانی سرعت پخششان را می‌سازد.',
                'en' => 'The rings are sine waves; the spatial frequency sets their density and the temporal one their outward speed.',
            ]],
            ['name' => 'pointer', 'type' => 'event', 'default' => "'pointerdown · Enter/Space'", 'note' => [
                'fa' => 'کلیک یا لمس دقیقاً در همان نقطه قطره می‌اندازد؛ با کیبورد از نزدیک مرکز.',
                'en' => 'A click or tap drops exactly where it lands; the keyboard drops near the centre.',
            ]],
            ['name' => 'resolution', 'type' => 'scale', 'default' => "'0.5×'", 'note' => [
                'fa' => 'بوم با نصف رزولوشن CSS رندر و بزرگ‌نمایی می‌شود — حلقه‌های نرم، GPU خوشحال.',
                'en' => 'The canvas renders at half CSS resolution and upscales — smooth rings, a happy GPU.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="shrp" x-data="{ count: 0 }">
            <div class="shrp-stage">
                <canvas class="shrp-cv" tabindex="0" role="img" aria-label="برکهٔ تعاملی؛ برای قطره کلیک یا Enter"
                        @pointerdown="drop($event)" @keydown.enter.prevent="dropAt(0, 0)"></canvas>
                <span class="shrp-cap">قطره بیندازید — کلیک، لمس یا Enter</span>
            </div>
            <div class="shrp-dock">
                <button @click="rainOne()">قطره</button>
                <button @click="rainThree()">سه قطره</button>
                <button @click="calm()">پاک‌کردن سطح</button>
                <span role="status" x-text="count + ' موج فعال'"></span>
            </div>
        </div>

        <style>
        .shrp-stage { position: relative; overflow: clip; border-radius: 1.25rem; aspect-ratio: 16 / 8;
                      background: #08131f; }
        .shrp-cv { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; touch-action: manipulation; }
        .shrp-cap { position: absolute; inset-block-start: .9rem; inset-inline-start: .9rem; z-index: 1;
                    padding: .35rem .8rem; border-radius: 999px; background: #ffffff24; color: #eaf6ff;
                    font-size: .76rem; backdrop-filter: blur(8px); }
        .shrp-dock { display: flex; flex-wrap: wrap; justify-content: center; gap: .5rem; margin-block-start: -1.4rem;
                     position: relative; z-index: 2; }
        .shrp-dock button { padding: .55rem 1.1rem; border-radius: 999px; border: 1px solid #ffffff33;
                            background: #101c2bd9; color: #eaf6ff; backdrop-filter: blur(10px); cursor: pointer; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="shrp" x-data="{ count: 0 }">
        <div class="shrp-stage">
        <canvas class="shrp-cv" tabindex="0" role="img" aria-label="Interactive pond; click or press Enter to drop"
                @pointerdown="drop($event)" @keydown.enter.prevent="dropAt(0, 0)"></canvas>
        <span class="shrp-cap">Drop a ripple — click, tap or Enter</span>
        </div>
        <div class="shrp-dock">
        <button @click="rainOne()">Drop</button>
        <button @click="rainThree()">Triple drop</button>
        <button @click="calm()">Calm the surface</button>
        <span role="status" x-text="count + ' live waves'"></span>
        </div>
        </div>

        <style>
        .shrp-stage { position: relative; overflow: clip; border-radius: 1.25rem; aspect-ratio: 16 / 8;
                      background: #08131f; }
        .shrp-cv { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; touch-action: manipulation; }
        .shrp-cap { position: absolute; inset-block-start: .9rem; inset-inline-start: .9rem; z-index: 1;
                    padding: .35rem .8rem; border-radius: 999px; background: #ffffff24; color: #eaf6ff;
                    font-size: .76rem; backdrop-filter: blur(8px); }
        .shrp-dock { display: flex; flex-wrap: wrap; justify-content: center; gap: .5rem; margin-block-start: -1.4rem;
                     position: relative; z-index: 2; }
        .shrp-dock button { padding: .55rem 1.1rem; border-radius: 999px; border: 1px solid #ffffff33;
                            background: #101c2bd9; color: #eaf6ff; backdrop-filter: blur(10px); cursor: pointer; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Pond.jsx — Inertia (React) page; renders the React snippet
        // below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import RipplePond from '@/components/RipplePond';

        export default function Pond() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <RipplePond />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // RipplePond.jsx — click-dropped sine ripples with exponential decay, raw WebGL1
        import { useEffect, useRef, useState } from 'react';

        const MAX = 12;
        const FRAG = `precision mediump float;
        uniform vec2 u_res; uniform float u_time; uniform float u_amp;
        uniform vec4 u_r[12];
        void main() {
          vec2 uv = (gl_FragCoord.xy * 2.0 - u_res) / min(u_res.x, u_res.y);
          vec3 col = mix(vec3(0.031, 0.08, 0.145), vec3(0.11, 0.27, 0.38), uv.y * 0.5 + 0.5);
          float ring = 0.0;
          for (int i = 0; i < 12; i++) {
            vec4 r = u_r[i];
            float age = u_time - r.z;
            if (age > 0.0 && age < 3.0) { // dead slots (t = -1000) never pass the guard
              float d = length(uv - r.xy);
              ring += sin(d * 26.0 - age * 8.0) * exp(-age * 1.7) * exp(-d * 1.25) * r.w;
            }
          }
          col += vec3(0.30, 0.55, 0.68) * max(ring, 0.0) * u_amp;  // crests catch the light
          col -= vec3(0.04, 0.07, 0.09) * max(-ring, 0.0) * u_amp; // troughs deepen
          float vig = 1.0 - 0.35 * dot(uv * 0.62, uv * 0.62);
          gl_FragColor = vec4(col * vig, 1.0);
        }`;

        export default function RipplePond() {
          const canvasRef = useRef(null);
          const apiRef = useRef(null);           // { drop, dropAt, calm } — set once GL is up
          const [count, setCount] = useState(0);
          const [noGl, setNoGl] = useState(false);

          useEffect(() => {
            const canvas = canvasRef.current;
            const gl = canvas.getContext('webgl', { antialias: false, alpha: false, powerPreference: 'low-power' });
            if (!gl) { setNoGl(true); return; }
            const sh = (type, src) => {
              const s = gl.createShader(type); gl.shaderSource(s, src); gl.compileShader(s); return s;
            };
            const prog = gl.createProgram();
            gl.attachShader(prog, sh(gl.VERTEX_SHADER, 'attribute vec2 a; void main() { gl_Position = vec4(a, 0.0, 1.0); }'));
            gl.attachShader(prog, sh(gl.FRAGMENT_SHADER, FRAG));
            gl.linkProgram(prog); gl.useProgram(prog);
            gl.bindBuffer(gl.ARRAY_BUFFER, gl.createBuffer());
            gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 1, -1, -1, 1, 1, 1]), gl.STATIC_DRAW);
            const a = gl.getAttribLocation(prog, 'a');
            gl.enableVertexAttribArray(a); gl.vertexAttribPointer(a, 2, gl.FLOAT, false, 0, 0);
            const U = { res: gl.getUniformLocation(prog, 'u_res'), time: gl.getUniformLocation(prog, 'u_time'),
                        amp: gl.getUniformLocation(prog, 'u_amp'), r: gl.getUniformLocation(prog, 'u_r') };

            const mq = matchMedia('(prefers-reduced-motion: reduce)');
            const s = { raf: 0, visible: true, t0: performance.now(), last: 0 };
            const buf = new Float32Array(MAX * 4).fill(-1000, 0).map((_, i) => (i % 4 === 2 ? -1000 : 0));
            let head = 0, timers = [];
            const clock = () => (performance.now() - s.t0) / 1000;
            const size = () => { // half resolution keeps the rings cheap
              canvas.width = Math.max(2, Math.round(canvas.clientWidth * 0.5));
              canvas.height = Math.max(2, Math.round(canvas.clientHeight * 0.5));
            };
            const draw = (t) => {
              let live = 0;
              for (let k = 0; k < MAX; k++) if (t - buf[k * 4 + 2] < 3.0 && buf[k * 4 + 2] > 0) live++;
              setCount(live);
              gl.viewport(0, 0, gl.drawingBufferWidth, gl.drawingBufferHeight);
              gl.uniform2f(U.res, gl.drawingBufferWidth, gl.drawingBufferHeight);
              gl.uniform1f(U.time, t);
              gl.uniform1f(U.amp, mq.matches ? 0.0 : 1.0); // reduced motion → still water
              gl.uniform4fv(U.r, buf);
              gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
            };
            const redraw = () => draw(mq.matches ? 0 : s.last);
            const run = () => {
              cancelAnimationFrame(s.raf);
              if (!s.visible) return;            // parked while off screen
              if (mq.matches) { redraw(); return; }
              const loop = (now) => { s.last = (now - s.t0) / 1000; draw(s.last); s.raf = requestAnimationFrame(loop); };
              s.raf = requestAnimationFrame(loop);
            };

            const spawn = (x, y, k = 1) => {
              const i = head; head = (head + 1) % MAX;
              buf[i * 4] = x; buf[i * 4 + 1] = y; buf[i * 4 + 2] = clock(); buf[i * 4 + 3] = k;
            };
            const drop = (e) => {
              const r = canvas.getBoundingClientRect(), m = Math.min(r.width, r.height);
              spawn((((e.clientX - r.left) * 2) - r.width) / m, ((r.height - (e.clientY - r.top) * 2)) / m);
            };
            const dropAt = (x, y) => spawn(x, y);
            const rain = (n) => { // staggered drops so the waves visibly share the surface
              for (let j = 0; j < n; j++) timers.push(setTimeout(() => spawn((Math.random() - 0.5) * 1.4, (Math.random() - 0.5) * 0.8), j * 180));
            };
            const calm = () => { timers.forEach(clearTimeout); timers = []; for (let k = 0; k < MAX; k++) buf[k * 4 + 2] = -1000; redraw(); };
            apiRef.current = { drop, dropAt, rain, calm };

            const io = new IntersectionObserver(([e]) => { s.visible = e.isIntersecting; run(); });
            io.observe(canvas);
            const ro = new ResizeObserver(() => { size(); redraw(); });
            ro.observe(canvas);
            const onDown = (e) => drop(e);
            canvas.addEventListener('pointerdown', onDown);
            size(); run();

            return () => { // full cleanup — timers and context included
              timers.forEach(clearTimeout);
              cancelAnimationFrame(s.raf); io.disconnect(); ro.disconnect();
              canvas.removeEventListener('pointerdown', onDown);
              apiRef.current = null;
              gl.getExtension('WEBGL_lose_context')?.loseContext();
            };
          }, []);

          const api = () => apiRef.current;

          return (
            <>
              <style>{`
                .shrp-stage { position: relative; overflow: clip; border-radius: 1.25rem; aspect-ratio: 16 / 8; background: #08131f; }
                .shrp-cv, .shrp-fallback { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; touch-action: manipulation; }
                .shrp-fallback { background: radial-gradient(120% 90% at 50% 10%, #1c4258, #08131f 70%); }
                .shrp-cap { position: absolute; inset-block-start: .9rem; inset-inline-start: .9rem; z-index: 1; padding: .35rem .8rem;
                            border-radius: 999px; background: rgba(255,255,255,.14); color: #eaf6ff; font-size: .76rem;
                            backdrop-filter: blur(8px); }
                .shrp-dock { display: flex; flex-wrap: wrap; justify-content: center; align-items: center; gap: .5rem;
                             margin-block-start: -1.4rem; position: relative; z-index: 2; }
                .shrp-dock button { padding: .55rem 1.1rem; border-radius: 999px; border: 1px solid rgba(255,255,255,.2);
                                    background: rgba(16,28,43,.85); color: #eaf6ff; backdrop-filter: blur(10px); cursor: pointer; }
              `}</style>
              <div className="shrp-stage">
                {noGl
                  ? <div className="shrp-fallback" />
                  : <canvas ref={canvasRef} className="shrp-cv" tabIndex={0} role="img"
                      aria-label="Interactive pond; click or press Enter to drop a ripple"
                      onKeyDown={(e) => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); api()?.dropAt(0, 0); } }} />}
                <span className="shrp-cap">Drop a ripple — click, tap or Enter</span>
              </div>
              <div className="shrp-dock">
                <button type="button" onClick={() => api()?.rain(1)}>Drop</button>
                <button type="button" onClick={() => api()?.rain(3)}>Triple drop</button>
                <button type="button" onClick={() => api()?.calm()}>Calm the surface</button>
                <span role="status">{count} live waves</span>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- RipplePond.vue — click-dropped sine ripples with exponential decay, raw WebGL1 -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';

        const MAX = 12;
        const FRAG = `precision mediump float;
        uniform vec2 u_res; uniform float u_time; uniform float u_amp;
        uniform vec4 u_r[12];
        void main() {
          vec2 uv = (gl_FragCoord.xy * 2.0 - u_res) / min(u_res.x, u_res.y);
          vec3 col = mix(vec3(0.031, 0.08, 0.145), vec3(0.11, 0.27, 0.38), uv.y * 0.5 + 0.5);
          float ring = 0.0;
          for (int i = 0; i < 12; i++) {
            vec4 r = u_r[i];
            float age = u_time - r.z;
            if (age > 0.0 && age < 3.0) { // dead slots (t = -1000) never pass the guard
              float d = length(uv - r.xy);
              ring += sin(d * 26.0 - age * 8.0) * exp(-age * 1.7) * exp(-d * 1.25) * r.w;
            }
          }
          col += vec3(0.30, 0.55, 0.68) * max(ring, 0.0) * u_amp;  // crests catch the light
          col -= vec3(0.04, 0.07, 0.09) * max(-ring, 0.0) * u_amp; // troughs deepen
          float vig = 1.0 - 0.35 * dot(uv * 0.62, uv * 0.62);
          gl_FragColor = vec4(col * vig, 1.0);
        }`;

        const canvas = ref<HTMLCanvasElement | null>(null);
        const count = ref(0);
        const noGl = ref(false);
        const api = ref<{ drop(e: PointerEvent): void; dropAt(x: number, y: number): void; rain(n: number): void; calm(): void } | null>(null);
        let stop: (() => void) | null = null;

        onMounted(() => {
          const cv = canvas.value!;
          const gl = cv.getContext('webgl', { antialias: false, alpha: false, powerPreference: 'low-power' });
          if (!gl) { noGl.value = true; return; }
          const sh = (type: number, src: string) => {
            const s = gl.createShader(type)!; gl.shaderSource(s, src); gl.compileShader(s); return s;
          };
          const prog = gl.createProgram()!;
          gl.attachShader(prog, sh(gl.VERTEX_SHADER, 'attribute vec2 a; void main() { gl_Position = vec4(a, 0.0, 1.0); }'));
          gl.attachShader(prog, sh(gl.FRAGMENT_SHADER, FRAG));
          gl.linkProgram(prog); gl.useProgram(prog);
          gl.bindBuffer(gl.ARRAY_BUFFER, gl.createBuffer());
          gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 1, -1, -1, 1, 1, 1]), gl.STATIC_DRAW);
          const a = gl.getAttribLocation(prog, 'a');
          gl.enableVertexAttribArray(a); gl.vertexAttribPointer(a, 2, gl.FLOAT, false, 0, 0);
          const U = { res: gl.getUniformLocation(prog, 'u_res')!, time: gl.getUniformLocation(prog, 'u_time')!,
                      amp: gl.getUniformLocation(prog, 'u_amp')!, r: gl.getUniformLocation(prog, 'u_r')! };

          const mq = matchMedia('(prefers-reduced-motion: reduce)');
          let raf = 0, visible = true, last = 0, head = 0;
          const t0 = performance.now();
          let timers: ReturnType<typeof setTimeout>[] = [];
          const buf = new Float32Array(MAX * 4);
          for (let k = 0; k < MAX; k++) buf[k * 4 + 2] = -1000;
          const clock = () => (performance.now() - t0) / 1000;
          const size = () => { // half resolution keeps the rings cheap
            cv.width = Math.max(2, Math.round(cv.clientWidth * 0.5));
            cv.height = Math.max(2, Math.round(cv.clientHeight * 0.5));
          };
          const draw = (t: number) => {
            let live = 0;
            for (let k = 0; k < MAX; k++) if (buf[k * 4 + 2] > 0 && t - buf[k * 4 + 2] < 3.0) live++;
            count.value = live;
            gl.viewport(0, 0, gl.drawingBufferWidth, gl.drawingBufferHeight);
            gl.uniform2f(U.res, gl.drawingBufferWidth, gl.drawingBufferHeight);
            gl.uniform1f(U.time, t);
            gl.uniform1f(U.amp, mq.matches ? 0.0 : 1.0); // reduced motion → still water
            gl.uniform4fv(U.r, buf);
            gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
          };
          const redraw = () => draw(mq.matches ? 0 : last);
          const run = () => {
            cancelAnimationFrame(raf);
            if (!visible) return;            // parked while off screen
            if (mq.matches) { redraw(); return; }
            const loop = (now: number) => { last = (now - t0) / 1000; draw(last); raf = requestAnimationFrame(loop); };
            raf = requestAnimationFrame(loop);
          };

          const spawn = (x: number, y: number, k = 1) => {
            const i = head; head = (head + 1) % MAX;
            buf[i * 4] = x; buf[i * 4 + 1] = y; buf[i * 4 + 2] = clock(); buf[i * 4 + 3] = k;
          };
          const drop = (e: PointerEvent) => {
            const r = cv.getBoundingClientRect(), m = Math.min(r.width, r.height);
            spawn((((e.clientX - r.left) * 2) - r.width) / m, ((r.height - (e.clientY - r.top) * 2)) / m);
          };
          const rain = (n: number) => { // staggered drops so the waves visibly share the surface
            for (let j = 0; j < n; j++) timers.push(setTimeout(() => spawn((Math.random() - 0.5) * 1.4, (Math.random() - 0.5) * 0.8), j * 180));
          };
          const calm = () => { timers.forEach(clearTimeout); timers = []; for (let k = 0; k < MAX; k++) buf[k * 4 + 2] = -1000; redraw(); };
          api.value = { drop, dropAt: spawn, rain, calm };

          const io = new IntersectionObserver(([e]) => { visible = e.isIntersecting; run(); });
          io.observe(cv);
          const ro = new ResizeObserver(() => { size(); redraw(); });
          ro.observe(cv);
          const onDown = (e: PointerEvent) => drop(e);
          cv.addEventListener('pointerdown', onDown);
          size(); run();

          stop = () => { // full cleanup — timers and context included
            timers.forEach(clearTimeout);
            cancelAnimationFrame(raf); io.disconnect(); ro.disconnect();
            cv.removeEventListener('pointerdown', onDown);
            gl.getExtension('WEBGL_lose_context')?.loseContext();
          };
        });
        onBeforeUnmount(() => stop?.());

        const onKey = (e: KeyboardEvent) => {
          if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); api.value?.dropAt(0, 0); }
        };
        </script>

        <template>
          <div class="shrp-stage">
            <div v-if="noGl" class="shrp-fallback"></div>
            <canvas v-else ref="canvas" class="shrp-cv" tabindex="0" role="img"
                    aria-label="Interactive pond; click or press Enter to drop a ripple"
                    @keydown="onKey"></canvas>
            <span class="shrp-cap">Drop a ripple — click, tap or Enter</span>
          </div>
          <div class="shrp-dock">
            <button type="button" @click="api?.rain(1)">Drop</button>
            <button type="button" @click="api?.rain(3)">Triple drop</button>
            <button type="button" @click="api?.calm()">Calm the surface</button>
            <span role="status">{{ count }} live waves</span>
          </div>
        </template>

        <style scoped>
        .shrp-stage { position: relative; overflow: clip; border-radius: 1.25rem; aspect-ratio: 16 / 8; background: #08131f; }
        .shrp-cv, .shrp-fallback { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; touch-action: manipulation; }
        .shrp-fallback { background: radial-gradient(120% 90% at 50% 10%, #1c4258, #08131f 70%); }
        .shrp-cap { position: absolute; inset-block-start: .9rem; inset-inline-start: .9rem; z-index: 1; padding: .35rem .8rem;
                    border-radius: 999px; background: rgba(255,255,255,.14); color: #eaf6ff; font-size: .76rem;
                    backdrop-filter: blur(8px); }
        .shrp-dock { display: flex; flex-wrap: wrap; justify-content: center; align-items: center; gap: .5rem;
                     margin-block-start: -1.4rem; position: relative; z-index: 2; }
        .shrp-dock button { padding: .55rem 1.1rem; border-radius: 999px; border: 1px solid rgba(255,255,255,.2);
                            background: rgba(16,28,43,.85); color: #eaf6ff; backdrop-filter: blur(10px); cursor: pointer; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- RipplePond.svelte — click-dropped sine ripples with exponential decay, raw WebGL1 -->
        <script lang="ts">
          import { onMount } from 'svelte';

          const MAX = 12;
          const FRAG = `precision mediump float;
          uniform vec2 u_res; uniform float u_time; uniform float u_amp;
          uniform vec4 u_r[12];
          void main() {
            vec2 uv = (gl_FragCoord.xy * 2.0 - u_res) / min(u_res.x, u_res.y);
            vec3 col = mix(vec3(0.031, 0.08, 0.145), vec3(0.11, 0.27, 0.38), uv.y * 0.5 + 0.5);
            float ring = 0.0;
            for (int i = 0; i < 12; i++) {
              vec4 r = u_r[i];
              float age = u_time - r.z;
              if (age > 0.0 && age < 3.0) { // dead slots (t = -1000) never pass the guard
                float d = length(uv - r.xy);
                ring += sin(d * 26.0 - age * 8.0) * exp(-age * 1.7) * exp(-d * 1.25) * r.w;
              }
            }
            col += vec3(0.30, 0.55, 0.68) * max(ring, 0.0) * u_amp;  // crests catch the light
            col -= vec3(0.04, 0.07, 0.09) * max(-ring, 0.0) * u_amp; // troughs deepen
            float vig = 1.0 - 0.35 * dot(uv * 0.62, uv * 0.62);
            gl_FragColor = vec4(col * vig, 1.0);
          }`;

          let canvas: HTMLCanvasElement;
          let count = $state(0);
          let noGl = $state(false);
          let drop: (e: PointerEvent) => void = () => {};
          let dropAt: (x: number, y: number) => void = () => {};
          let rain: (n: number) => void = () => {};
          let calm: () => void = () => {};

          function onKey(e: KeyboardEvent) {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); dropAt(0, 0); }
          }

          onMount(() => {
            const gl = canvas.getContext('webgl', { antialias: false, alpha: false, powerPreference: 'low-power' });
            if (!gl) { noGl = true; return; }
            const sh = (type: number, src: string) => {
              const s = gl.createShader(type)!; gl.shaderSource(s, src); gl.compileShader(s); return s;
            };
            const prog = gl.createProgram()!;
            gl.attachShader(prog, sh(gl.VERTEX_SHADER, 'attribute vec2 a; void main() { gl_Position = vec4(a, 0.0, 1.0); }'));
            gl.attachShader(prog, sh(gl.FRAGMENT_SHADER, FRAG));
            gl.linkProgram(prog); gl.useProgram(prog);
            gl.bindBuffer(gl.ARRAY_BUFFER, gl.createBuffer());
            gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 1, -1, -1, 1, 1, 1]), gl.STATIC_DRAW);
            const a = gl.getAttribLocation(prog, 'a');
            gl.enableVertexAttribArray(a); gl.vertexAttribPointer(a, 2, gl.FLOAT, false, 0, 0);
            const U = { res: gl.getUniformLocation(prog, 'u_res')!, time: gl.getUniformLocation(prog, 'u_time')!,
                        amp: gl.getUniformLocation(prog, 'u_amp')!, r: gl.getUniformLocation(prog, 'u_r')! };

            const mq = matchMedia('(prefers-reduced-motion: reduce)');
            let raf = 0, visible = true, last = 0, head = 0;
            const t0 = performance.now();
            let timers: ReturnType<typeof setTimeout>[] = [];
            const buf = new Float32Array(MAX * 4);
            for (let k = 0; k < MAX; k++) buf[k * 4 + 2] = -1000;
            const clock = () => (performance.now() - t0) / 1000;
            const size = () => { // half resolution keeps the rings cheap
              canvas.width = Math.max(2, Math.round(canvas.clientWidth * 0.5));
              canvas.height = Math.max(2, Math.round(canvas.clientHeight * 0.5));
            };
            const draw = (t: number) => {
              let live = 0;
              for (let k = 0; k < MAX; k++) if (buf[k * 4 + 2] > 0 && t - buf[k * 4 + 2] < 3.0) live++;
              count = live;
              gl.viewport(0, 0, gl.drawingBufferWidth, gl.drawingBufferHeight);
              gl.uniform2f(U.res, gl.drawingBufferWidth, gl.drawingBufferHeight);
              gl.uniform1f(U.time, t);
              gl.uniform1f(U.amp, mq.matches ? 0.0 : 1.0); // reduced motion → still water
              gl.uniform4fv(U.r, buf);
              gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
            };
            const redraw = () => draw(mq.matches ? 0 : last);
            const run = () => {
              cancelAnimationFrame(raf);
              if (!visible) return;            // parked while off screen
              if (mq.matches) { redraw(); return; }
              const loop = (now: number) => { last = (now - t0) / 1000; draw(last); raf = requestAnimationFrame(loop); };
              raf = requestAnimationFrame(loop);
            };

            const spawn = (x: number, y: number, k = 1) => {
              const i = head; head = (head + 1) % MAX;
              buf[i * 4] = x; buf[i * 4 + 1] = y; buf[i * 4 + 2] = clock(); buf[i * 4 + 3] = k;
            };
            drop = (e: PointerEvent) => {
              const r = canvas.getBoundingClientRect(), m = Math.min(r.width, r.height);
              spawn((((e.clientX - r.left) * 2) - r.width) / m, ((r.height - (e.clientY - r.top) * 2)) / m);
            };
            dropAt = spawn;
            rain = (n: number) => { // staggered drops so the waves visibly share the surface
              for (let j = 0; j < n; j++) timers.push(setTimeout(() => spawn((Math.random() - 0.5) * 1.4, (Math.random() - 0.5) * 0.8), j * 180));
            };
            calm = () => { timers.forEach(clearTimeout); timers = []; for (let k = 0; k < MAX; k++) buf[k * 4 + 2] = -1000; redraw(); };

            const io = new IntersectionObserver(([e]) => { visible = e.isIntersecting; run(); });
            io.observe(canvas);
            const ro = new ResizeObserver(() => { size(); redraw(); });
            ro.observe(canvas);
            const onDown = (e: PointerEvent) => drop(e);
            canvas.addEventListener('pointerdown', onDown);
            size(); run();

            return () => { // full cleanup — timers and context included
              timers.forEach(clearTimeout);
              cancelAnimationFrame(raf); io.disconnect(); ro.disconnect();
              canvas.removeEventListener('pointerdown', onDown);
              gl.getExtension('WEBGL_lose_context')?.loseContext();
            };
          });
        </script>

        <div class="shrp-stage">
          {#if noGl}
            <div class="shrp-fallback"></div>
          {:else}
            <canvas bind:this={canvas} class="shrp-cv" tabindex="0" role="img"
                    aria-label="Interactive pond; click or press Enter to drop a ripple"
                    onkeydown={onKey}></canvas>
          {/if}
          <span class="shrp-cap">Drop a ripple — click, tap or Enter</span>
        </div>
        <div class="shrp-dock">
          <button type="button" onclick={() => rain(1)}>Drop</button>
          <button type="button" onclick={() => rain(3)}>Triple drop</button>
          <button type="button" onclick={() => calm()}>Calm the surface</button>
          <span role="status">{count} live waves</span>
        </div>

        <style>
        .shrp-stage { position: relative; overflow: clip; border-radius: 1.25rem; aspect-ratio: 16 / 8; background: #08131f; }
        .shrp-cv, .shrp-fallback { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; touch-action: manipulation; }
        .shrp-fallback { background: radial-gradient(120% 90% at 50% 10%, #1c4258, #08131f 70%); }
        .shrp-cap { position: absolute; inset-block-start: .9rem; inset-inline-start: .9rem; z-index: 1; padding: .35rem .8rem;
                    border-radius: 999px; background: rgba(255,255,255,.14); color: #eaf6ff; font-size: .76rem;
                    backdrop-filter: blur(8px); }
        .shrp-dock { display: flex; flex-wrap: wrap; justify-content: center; align-items: center; gap: .5rem;
                     margin-block-start: -1.4rem; position: relative; z-index: 2; }
        .shrp-dock button { padding: .55rem 1.1rem; border-radius: 999px; border: 1px solid rgba(255,255,255,.2);
                            background: rgba(16,28,43,.85); color: #eaf6ff; backdrop-filter: blur(10px); cursor: pointer; }
        </style>
        SVELTE,
        ],
    ],
];
