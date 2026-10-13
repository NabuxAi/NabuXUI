<?php

/**
 * Demo manifest of the "depth-3d" group — pure-CSS 3D depth: perspective and
 * preserve-3d only, no WebGL and no library. A product room that spins under
 * the pointer and explodes into labelled layers, a six-face feature cube
 * that drags and snaps with a spring, and a product box whose lid lifts on
 * hover or click while the goods pop out on a spring. Scenarios live at
 * resources/views/demos/components/depth-3d/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'عمق سه‌بعدی', 'en' => '3D depth'],

    'product-room' => [
        'title' => ['fa' => 'اتاق محصول', 'en' => 'Product room'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'صحنهٔ سه‌بعدی چندلایه با CSS خالص — بلندگوی هوشمند روی سکو، لایه‌به‌لایه روی محور Z — که با درگ می‌چرخد و تمایل می‌گیرد، اینرسی نرم دارد و در آرامش آهسته می‌گردد؛ دکمهٔ نمای انفجاری لایه‌ها را از هم باز می‌کند و برچسبِ همیشه-روبه-بیننده کنار هر لایه می‌نشیند.',
            'en' => 'A multi-layer 3D stage in pure CSS — a smart speaker on a pedestal, stacked layer by layer along Z — that spins and tilts under your drag with soft inertia and a gentle idle drift; the exploded-view button pulls the layers apart and pins a camera-facing label to every one of them.',
        ],
        'js' => true,
        'docs' => 'https://scrolltide.co',
        'props' => [
            ['name' => 'layers', 'type' => 'array', 'default' => "'[]'", 'note' => [
                'fa' => 'هر لایه: kind (پوسته/درایور/حلقه/گنبد/سکو)، برچسب دوزبانه و یک خط مشخصات؛ ترتیب آرایه همان ترتیب چیدن از پایین به بالاست.',
                'en' => 'Each layer: kind (shell, driver, ring, dome, pedestal), a bilingual label and one spec line; array order stacks them bottom-up.',
            ]],
            ['name' => 'spin · tilt', 'type' => 'deg', 'default' => "'-28 · 62'", 'note' => [
                'fa' => 'زاویهٔ آغازین صحنه؛ درگ افقی spin و درگ عمودی tilt را بین ۳۰ تا ۸۰ درجه جابه‌جا می‌کند.',
                'en' => 'The scene’s opening angles; horizontal drag moves spin, vertical drag clamps tilt between 30° and 80°.',
            ]],
            ['name' => 'exploded', 'type' => 'boolean', 'default' => 'false', 'note' => [
                'fa' => 'با روشن‌شدن، فاصلهٔ لایه‌ها از ۱۳ به ۴۲ پیکسل فنری باز می‌شود و برچسب هر لایه با تأخیر پله‌ای ظاهر می‌شود.',
                'en' => 'When on, the layer gap springs from 13 to 42px and each label appears on a stepped delay.',
            ]],
            ['name' => 'inertia · idle', 'type' => 'behaviour', 'default' => "'0.94 · 0.1°/f'", 'note' => [
                'fa' => 'پس از رها کردن، سرعت انگشت با ضریب ۰٫۹۴ میرا می‌شود و بعد از توقف، گردش آرام ۰٫۱ درجه بر فریم؛ در حرکت کاهش‌یافته و خارج از دید هر دو متوقف‌اند.',
                'en' => 'On release the finger’s velocity decays at 0.94 per frame, then a 0.1°/frame idle drift; both stop under reduced motion and off screen.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="d3pr" x-data="{ spin: -28, tilt: 62, exploded: false }">
            <div class="d3pr-stage"
                 x-on:pointerdown="drag = true; px = $event.clientX; py = $event.clientY"
                 x-on:pointermove="if (drag) { spin += ($event.clientX - px) * .4;
                     tilt = Math.min(80, Math.max(30, tilt - ($event.clientY - py) * .3));
                     px = $event.clientX; py = $event.clientY; }"
                 x-on:pointerup="drag = false" x-on:pointercancel="drag = false">
                <div class="d3pr-world" :style="`transform: rotateX(${tilt}deg) rotateZ(${spin}deg)`">
                    <div class="d3pr-slab d3pr-pedestal" style="--i: 0"></div>
                    <div class="d3pr-slab d3pr-mesh" style="--i: 1"></div>
                    <div class="d3pr-slab d3pr-driver" style="--i: 2"></div>
                    <div class="d3pr-slab d3pr-ring" style="--i: 3"></div>
                    <div class="d3pr-slab d3pr-dome" style="--i: 4"></div>
                </div>
            </div>
            <button type="button" x-on:click="exploded = !exploded"
                    x-bind:aria-pressed="exploded ? 'true' : 'false'"
                    x-text="exploded ? 'بازچینش لایه‌ها' : 'نمای انفجاری'"></button>
        </div>

        <style>
        .d3pr-stage { --lift: 13px; inline-size: min(100%, 30rem); aspect-ratio: 4 / 3;
                      display: grid; place-items: center; perspective: 1100px;
                      overflow: clip; cursor: grab; touch-action: none; user-select: none; }
        .d3pr-world { position: relative; inline-size: 11rem; aspect-ratio: 1; transform-style: preserve-3d; }
        .d3pr[data-exploded='true'] .d3pr-stage { --lift: 42px; }
        .d3pr-slab { position: absolute; inset: 0; border-radius: 50%;
                     transform: translateZ(calc((var(--i) - 2) * var(--lift)));
                     transition: transform .8s cubic-bezier(.22, 1, .36, 1); }
        .d3pr-pedestal { background: #3f3a36; }
        .d3pr-mesh { background: radial-gradient(#d6d3d1 1px, transparent 1.4px) 0 0 / 10px 10px, #f5f5f4; }
        .d3pr-driver { background: repeating-radial-gradient(#57534e 0 6px, #78716c 6px 12px); }
        .d3pr-ring { background: transparent; border: 10px solid #4f46e5; }
        .d3pr-dome { background: radial-gradient(circle, #c7d2fe, #e0e7ff 70%); }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="d3pr" x-data="{ spin: -28, tilt: 62, exploded: false }">
            <div class="d3pr-stage"
                 x-on:pointerdown="drag = true; px = $event.clientX; py = $event.clientY"
                 x-on:pointermove="if (drag) { spin += ($event.clientX - px) * .4;
                     tilt = Math.min(80, Math.max(30, tilt - ($event.clientY - py) * .3));
                     px = $event.clientX; py = $event.clientY; }"
                 x-on:pointerup="drag = false" x-on:pointercancel="drag = false">
                <div class="d3pr-world" :style="`transform: rotateX(${tilt}deg) rotateZ(${spin}deg)`">
                    <div class="d3pr-slab d3pr-pedestal" style="--i: 0"></div>
                    <div class="d3pr-slab d3pr-mesh" style="--i: 1"></div>
                    <div class="d3pr-slab d3pr-driver" style="--i: 2"></div>
                    <div class="d3pr-slab d3pr-ring" style="--i: 3"></div>
                    <div class="d3pr-slab d3pr-dome" style="--i: 4"></div>
                </div>
            </div>
            <button type="button" x-on:click="exploded = !exploded"
                    x-bind:aria-pressed="exploded ? 'true' : 'false'"
                    x-text="exploded ? 'Restack layers' : 'Exploded view'"></button>
        </div>

        <style>
        .d3pr-stage { --lift: 13px; inline-size: min(100%, 30rem); aspect-ratio: 4 / 3;
                      display: grid; place-items: center; perspective: 1100px;
                      overflow: clip; cursor: grab; touch-action: none; user-select: none; }
        .d3pr-world { position: relative; inline-size: 11rem; aspect-ratio: 1; transform-style: preserve-3d; }
        .d3pr[data-exploded='true'] .d3pr-stage { --lift: 42px; }
        .d3pr-slab { position: absolute; inset: 0; border-radius: 50%;
                     transform: translateZ(calc((var(--i) - 2) * var(--lift)));
                     transition: transform .8s cubic-bezier(.22, 1, .36, 1); }
        .d3pr-pedestal { background: #3f3a36; }
        .d3pr-mesh { background: radial-gradient(#d6d3d1 1px, transparent 1.4px) 0 0 / 10px 10px, #f5f5f4; }
        .d3pr-driver { background: repeating-radial-gradient(#57534e 0 6px, #78716c 6px 12px); }
        .d3pr-ring { background: transparent; border: 10px solid #4f46e5; }
        .d3pr-dome { background: radial-gradient(circle, #c7d2fe, #e0e7ff 70%); }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/ProductRoom.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import ProductRoom from '@/components/ProductRoom';

        export default function ProductRoomPage() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <ProductRoom />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // ProductRoom.jsx — layered 3D product stage: drag spins, explode separates
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const LAYERS = [
          { kind: 'pedestal', label: 'Display pedestal', spec: 'Oiled walnut' },
          { kind: 'mesh', label: 'Mesh shell', spec: 'Acoustic fabric' },
          { kind: 'driver', label: 'Woofer driver', spec: '40 W · 10 cm' },
          { kind: 'ring', label: 'Control ring', spec: 'Touch + turn' },
          { kind: 'dome', label: 'Light dome', spec: '2700 K glow' },
        ];

        export default function ProductRoom() {
          const [rot, setRot] = useState({ spin: -28, tilt: 62 });
          const [exploded, setExploded] = useState(false);
          const stage = useRef(null);
          const drag = useRef(null);
          const raf = useRef(0);
          const vel = useRef({ x: 0, y: 0 });

          // Soft inertia after release, then a gentle idle drift — parked off screen.
          useEffect(() => {
            const io = new IntersectionObserver(([e]) => {
              cancelAnimationFrame(raf.current);
              if (!e.isIntersecting) return;
              const loop = () => {
                setRot((r) => {
                  const next = { ...r };
                  if (Math.abs(vel.current.x) > 0.02 || Math.abs(vel.current.y) > 0.02) {
                    next.spin += vel.current.x;
                    next.tilt = Math.min(80, Math.max(30, next.tilt + vel.current.y));
                    vel.current.x *= 0.94; vel.current.y *= 0.94;
                  } else if (!drag.current) {
                    next.spin += 0.1;
                  }
                  return next;
                });
                raf.current = requestAnimationFrame(loop);
              };
              raf.current = requestAnimationFrame(loop);
            });
            io.observe(stage.current);
            return () => { io.disconnect(); cancelAnimationFrame(raf.current); };
          }, []);

          const onDown = (e) => {
            stage.current.setPointerCapture(e.pointerId);
            drag.current = { x: e.clientX, y: e.clientY };
            vel.current = { x: 0, y: 0 };
            cancelAnimationFrame(raf.current);
          };
          const onMove = (e) => {
            if (!drag.current) return;
            const dx = e.clientX - drag.current.x, dy = e.clientY - drag.current.y;
            drag.current = { x: e.clientX, y: e.clientY };
            vel.current = { x: dx * 0.4, y: -dy * 0.3 };
            setRot((r) => ({ spin: r.spin + dx * 0.4,
              tilt: Math.min(80, Math.max(30, r.tilt - dy * 0.3)) }));
          };
          const onUp = () => { drag.current = null; stage.current?.dispatchEvent(new Event('tick')); };

          // The label counter-rotates the world so it always faces the camera.
          const tag = (i) => ({
            transform: `rotateZ(${-rot.spin}deg) rotateX(${-rot.tilt}deg)`,
            transitionDelay: `${i * 60}ms`,
          });

          return (
            <>
              <style>{`
                .d3pr-stage { --lift: 13px; inline-size: min(100%, 30rem); aspect-ratio: 4 / 3; display: grid; place-items: center;
                              perspective: 1100px; overflow: clip; cursor: grab; touch-action: none; user-select: none; }
                .d3pr-room[data-exploded='true'] .d3pr-stage { --lift: 42px; }
                .d3pr-world { position: relative; inline-size: 11rem; aspect-ratio: 1; transform-style: preserve-3d; }
                .d3pr-slab { position: absolute; inset: 0; border-radius: 50%; transform-style: preserve-3d;
                             transform: translateZ(calc((var(--i) - 2) * var(--lift)));
                             transition: transform .8s cubic-bezier(.22, 1, .36, 1); }
                .d3pr-slab:nth-child(1) { background: #3f3a36; }
                .d3pr-slab:nth-child(2) { background: radial-gradient(#d6d3d1 1px, transparent 1.4px) 0 0 / 10px 10px, #f5f5f4; }
                .d3pr-slab:nth-child(3) { background: repeating-radial-gradient(#57534e 0 6px, #78716c 6px 12px); }
                .d3pr-slab:nth-child(4) { background: transparent; border: 10px solid #4f46e5; }
                .d3pr-slab:nth-child(5) { background: radial-gradient(circle, #c7d2fe, #e0e7ff 70%); }
                .d3pr-pin { position: absolute; inset-block-start: 50%; inset-inline-start: 68%; }
                .d3pr-tag { position: absolute; translate: -50% -50%; white-space: nowrap; padding: .3rem .6rem; border-radius: .5rem;
                            background: #fff; border: 1px solid #e7e5e4; font: 600 .7rem/1.3 sans-serif; text-align: start;
                            opacity: 0; transition: opacity .3s ease; }
                .d3pr-room[data-exploded='true'] .d3pr-tag { opacity: 1; }
                .d3pr-tag small { display: block; font-weight: 400; color: #78716c; }
              `}</style>
              <div className="d3pr-room" data-exploded={exploded}>
                <div ref={stage} className="d3pr-stage" role="img" aria-label="Exploded product view — drag to spin"
                  onPointerDown={onDown} onPointerMove={onMove} onPointerUp={onUp} onPointerCancel={onUp}>
                  <div className="d3pr-world" style={{ transform: `rotateX(${rot.tilt}deg) rotateZ(${rot.spin}deg)` }}>
                    {LAYERS.map((l, i) => (
                      <div key={l.kind} className="d3pr-slab" style={{ '--i': i }}>
                        <span className="d3pr-pin">
                          <span className="d3pr-tag" style={tag(i)}>
                            {l.label}<small>{l.spec}</small>
                          </span>
                        </span>
                      </div>
                    ))}
                  </div>
                </div>
                <button type="button" aria-pressed={exploded} onClick={() => setExploded(!exploded)}>
                  {exploded ? 'Restack layers' : 'Exploded view'}
                </button>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- ProductRoom.vue — layered 3D product stage: drag spins, explode separates -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const layers = [
          { kind: 'pedestal', label: 'Display pedestal', spec: 'Oiled walnut' },
          { kind: 'mesh', label: 'Mesh shell', spec: 'Acoustic fabric' },
          { kind: 'driver', label: 'Woofer driver', spec: '40 W · 10 cm' },
          { kind: 'ring', label: 'Control ring', spec: 'Touch + turn' },
          { kind: 'dome', label: 'Light dome', spec: '2700 K glow' },
        ];
        const spin = ref(-28);
        const tilt = ref(62);
        const exploded = ref(false);
        const stage = ref<HTMLElement | null>(null);

        let raf = 0;
        let io: IntersectionObserver | null = null;
        let drag: { x: number; y: number } | null = null;
        const vel = { x: 0, y: 0 };

        // Soft inertia after release, then a gentle idle drift — parked off screen.
        const loop = () => {
          if (Math.abs(vel.x) > 0.02 || Math.abs(vel.y) > 0.02) {
            spin.value += vel.x;
            tilt.value = Math.min(80, Math.max(30, tilt.value + vel.y));
            vel.x *= 0.94; vel.y *= 0.94;
          } else if (!drag) {
            spin.value += 0.1;
          }
          raf = requestAnimationFrame(loop);
        };
        const startLoop = () => { cancelAnimationFrame(raf); raf = requestAnimationFrame(loop); };

        const onDown = (e: PointerEvent) => {
          stage.value?.setPointerCapture(e.pointerId);
          drag = { x: e.clientX, y: e.clientY };
          vel.x = 0; vel.y = 0;
          cancelAnimationFrame(raf);
        };
        const onMove = (e: PointerEvent) => {
          if (!drag) return;
          const dx = e.clientX - drag.x, dy = e.clientY - drag.y;
          drag = { x: e.clientX, y: e.clientY };
          spin.value += dx * 0.4;
          tilt.value = Math.min(80, Math.max(30, tilt.value - dy * 0.3));
          vel.x = dx * 0.4; vel.y = -dy * 0.3;
        };
        const onUp = () => { drag = null; startLoop(); };

        onMounted(() => {
          io = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) startLoop();
            else cancelAnimationFrame(raf);
          });
          if (stage.value) io.observe(stage.value);
        });
        onBeforeUnmount(() => {
          io?.disconnect();
          cancelAnimationFrame(raf);
        });
        </script>

        <template>
          <div class="d3pr-room" :data-exploded="exploded">
            <div ref="stage" class="d3pr-stage" role="img" aria-label="Exploded product view — drag to spin"
                 @pointerdown="onDown" @pointermove="onMove" @pointerup="onUp" @pointercancel="onUp">
              <!-- The label counter-rotates the world so it always faces the camera. -->
              <div class="d3pr-world" :style="`transform: rotateX(${tilt}deg) rotateZ(${spin}deg)`">
                <div v-for="(l, i) in layers" :key="l.kind" class="d3pr-slab" :style="{ '--i': i }">
                  <span class="d3pr-pin">
                    <span class="d3pr-tag" :style="`transform: rotateZ(${-spin}deg) rotateX(${-tilt}deg); transition-delay: ${i * 60}ms`">
                      {{ l.label }}<small>{{ l.spec }}</small>
                    </span>
                  </span>
                </div>
              </div>
            </div>
            <button type="button" :aria-pressed="exploded" @click="exploded = !exploded">
              {{ exploded ? 'Restack layers' : 'Exploded view' }}
            </button>
          </div>
        </template>

        <style scoped>
        .d3pr-stage { --lift: 13px; inline-size: min(100%, 30rem); aspect-ratio: 4 / 3; display: grid; place-items: center;
                      perspective: 1100px; overflow: clip; cursor: grab; touch-action: none; user-select: none; }
        .d3pr-room[data-exploded='true'] .d3pr-stage { --lift: 42px; }
        .d3pr-world { position: relative; inline-size: 11rem; aspect-ratio: 1; transform-style: preserve-3d; }
        .d3pr-slab { position: absolute; inset: 0; border-radius: 50%; transform-style: preserve-3d;
                     transform: translateZ(calc((var(--i) - 2) * var(--lift)));
                     transition: transform .8s cubic-bezier(.22, 1, .36, 1); }
        .d3pr-slab:nth-child(1) { background: #3f3a36; }
        .d3pr-slab:nth-child(2) { background: radial-gradient(#d6d3d1 1px, transparent 1.4px) 0 0 / 10px 10px, #f5f5f4; }
        .d3pr-slab:nth-child(3) { background: repeating-radial-gradient(#57534e 0 6px, #78716c 6px 12px); }
        .d3pr-slab:nth-child(4) { background: transparent; border: 10px solid #4f46e5; }
        .d3pr-slab:nth-child(5) { background: radial-gradient(circle, #c7d2fe, #e0e7ff 70%); }
        .d3pr-pin { position: absolute; inset-block-start: 50%; inset-inline-start: 68%; }
        .d3pr-tag { position: absolute; translate: -50% -50%; white-space: nowrap; padding: .3rem .6rem; border-radius: .5rem;
                    background: #fff; border: 1px solid #e7e5e4; font: 600 .7rem/1.3 sans-serif; text-align: start;
                    opacity: 0; transition: opacity .3s ease; }
        .d3pr-room[data-exploded='true'] .d3pr-tag { opacity: 1; }
        .d3pr-tag small { display: block; font-weight: 400; color: #78716c; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- ProductRoom.svelte — layered 3D product stage: drag spins, explode separates -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          const layers = [
            { kind: 'pedestal', label: 'Display pedestal', spec: 'Oiled walnut' },
            { kind: 'mesh', label: 'Mesh shell', spec: 'Acoustic fabric' },
            { kind: 'driver', label: 'Woofer driver', spec: '40 W · 10 cm' },
            { kind: 'ring', label: 'Control ring', spec: 'Touch + turn' },
            { kind: 'dome', label: 'Light dome', spec: '2700 K glow' },
          ];
          let spin = $state(-28);
          let tilt = $state(62);
          let exploded = $state(false);
          let stage: HTMLElement;

          let raf = 0;
          let io: IntersectionObserver | null = null;
          let drag: { x: number; y: number } | null = null;
          const vel = { x: 0, y: 0 };

          // Soft inertia after release, then a gentle idle drift — parked off screen.
          const loop = () => {
            if (Math.abs(vel.x) > 0.02 || Math.abs(vel.y) > 0.02) {
              spin += vel.x;
              tilt = Math.min(80, Math.max(30, tilt + vel.y));
              vel.x *= 0.94; vel.y *= 0.94;
            } else if (!drag) {
              spin += 0.1;
            }
            raf = requestAnimationFrame(loop);
          };
          const startLoop = () => { cancelAnimationFrame(raf); raf = requestAnimationFrame(loop); };

          const onDown = (e: PointerEvent) => {
            stage.setPointerCapture(e.pointerId);
            drag = { x: e.clientX, y: e.clientY };
            vel.x = 0; vel.y = 0;
            cancelAnimationFrame(raf);
          };
          const onMove = (e: PointerEvent) => {
            if (!drag) return;
            const dx = e.clientX - drag.x, dy = e.clientY - drag.y;
            drag = { x: e.clientX, y: e.clientY };
            spin += dx * 0.4;
            tilt = Math.min(80, Math.max(30, tilt - dy * 0.3));
            vel.x = dx * 0.4; vel.y = -dy * 0.3;
          };
          const onUp = () => { drag = null; startLoop(); };

          onMount(() => {
            io = new IntersectionObserver((entries) => {
              if (entries[0].isIntersecting) startLoop();
              else cancelAnimationFrame(raf);
            });
            io.observe(stage);
            return () => { io?.disconnect(); cancelAnimationFrame(raf); };
          });
        </script>

        <div class="d3pr-room" data-exploded={exploded}>
          <div bind:this={stage} class="d3pr-stage" role="img" aria-label="Exploded product view — drag to spin"
               onpointerdown={onDown} onpointermove={onMove} onpointerup={onUp} onpointercancel={onUp}>
            <!-- The label counter-rotates the world so it always faces the camera. -->
            <div class="d3pr-world" style={`transform: rotateX(${tilt}deg) rotateZ(${spin}deg)`}>
              {#each layers as l, i (l.kind)}
                <div class="d3pr-slab" style={`--i: ${i}`}>
                  <span class="d3pr-pin">
                    <span class="d3pr-tag" style={`transform: rotateZ(${-spin}deg) rotateX(${-tilt}deg); transition-delay: ${i * 60}ms`}>
                      {l.label}<small>{l.spec}</small>
                    </span>
                  </span>
                </div>
              {/each}
            </div>
          </div>
          <button type="button" aria-pressed={exploded} onclick={() => (exploded = !exploded)}>
            {exploded ? 'Restack layers' : 'Exploded view'}
          </button>
        </div>

        <style>
        .d3pr-stage { --lift: 13px; inline-size: min(100%, 30rem); aspect-ratio: 4 / 3; display: grid; place-items: center;
                      perspective: 1100px; overflow: clip; cursor: grab; touch-action: none; user-select: none; }
        .d3pr-room[data-exploded='true'] .d3pr-stage { --lift: 42px; }
        .d3pr-world { position: relative; inline-size: 11rem; aspect-ratio: 1; transform-style: preserve-3d; }
        .d3pr-slab { position: absolute; inset: 0; border-radius: 50%; transform-style: preserve-3d;
                     transform: translateZ(calc((var(--i) - 2) * var(--lift)));
                     transition: transform .8s cubic-bezier(.22, 1, .36, 1); }
        .d3pr-slab:nth-child(1) { background: #3f3a36; }
        .d3pr-slab:nth-child(2) { background: radial-gradient(#d6d3d1 1px, transparent 1.4px) 0 0 / 10px 10px, #f5f5f4; }
        .d3pr-slab:nth-child(3) { background: repeating-radial-gradient(#57534e 0 6px, #78716c 6px 12px); }
        .d3pr-slab:nth-child(4) { background: transparent; border: 10px solid #4f46e5; }
        .d3pr-slab:nth-child(5) { background: radial-gradient(circle, #c7d2fe, #e0e7ff 70%); }
        .d3pr-pin { position: absolute; inset-block-start: 50%; inset-inline-start: 68%; }
        .d3pr-tag { position: absolute; translate: -50% -50%; white-space: nowrap; padding: .3rem .6rem; border-radius: .5rem;
                    background: #fff; border: 1px solid #e7e5e4; font: 600 .7rem/1.3 sans-serif; text-align: start;
                    opacity: 0; transition: opacity .3s ease; }
        .d3pr-room[data-exploded='true'] .d3pr-tag { opacity: 1; }
        .d3pr-tag small { display: block; font-weight: 400; color: #78716c; }
        </style>
        SVELTE,
        ],
    ],

    'flip-cube' => [
        'title' => ['fa' => 'مکعب چرخان', 'en' => 'Flip cube'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'مکعب واقعی شش‌وجهه برای ویژگی‌های ساعت Polaris Trail — با CSS سه‌بعدی خالص — که با درگ در دو محور می‌چرخد و تمایل می‌گیرد؛ رها که شود سرعت انگشت را می‌خواند و با فنر روی نزدیک‌ترین زاویهٔ ۹۰ درجه‌ای هر دو محور می‌نشیند، پس هر شش وجه — حتی سقف و کف — در دسترس‌اند؛ وجه روبهٔ دوربین هایلایت می‌شود و دکمه‌ها و کلیدهای جهت‌دار وجه‌به‌وجه می‌چرخانند.',
            'en' => 'A true six-face cube carrying the Polaris Trail Watch features — pure CSS 3D — that spins and tilts on both axes under your drag; on release it reads your finger’s velocity and springs onto the nearest 90° resting angle on each axis, so all six faces, cap faces included, are reachable; the face toward the camera lights up and buttons or arrow keys walk it face by face.',
        ],
        'js' => true,
        'docs' => 'https://scrolltide.co',
        'props' => [
            ['name' => 'faces', 'type' => 'array', 'default' => "'6'", 'note' => [
                'fa' => 'شش کارت ویژگی با برچسب و یک خط مشخصات؛ وجه‌های ۰ تا ۳ دور محور Y می‌ایستند و وجه‌های ۴ و ۵ سقف و کف مکعب را می‌پوشانند.',
                'en' => 'Six feature cards with a label and one spec line; faces 0–3 ring the Y axis and faces 4 and 5 cap the cube’s top and bottom.',
            ]],
            ['name' => 'spin · tilt', 'type' => 'deg', 'default' => "'-24 · -12'", 'note' => [
                'fa' => 'زاویهٔ آغازین مکعب؛ درگ افقی spin را می‌چرخاند و درگ عمودی tilt را در بازهٔ ۹۰− تا ۹۰+ درجه نگه می‌دارد.',
                'en' => 'The cube’s opening angles; horizontal drag spins it and vertical drag clamps tilt between −90° and 90°.',
            ]],
            ['name' => 'snap', 'type' => 'behaviour', 'default' => "'90° spring'", 'note' => [
                'fa' => 'با رها کردن، سرعت درگ تا شش فریم جلو‌تر می‌رود و هر دو محور روی نزدیک‌ترین مضرب ۹۰ درجه گرد می‌شوند؛ فنر ۰٫۵۵ ثانیه‌ای وجه را می‌نشاند و در حرکت کاهش‌یافته چفت فوری است.',
                'en' => 'On release the drag velocity is projected six frames ahead and both axes round to their nearest multiple of 90°; a 0.55 s spring seats the face, and under reduced motion it locks instantly.',
            ]],
            ['name' => 'active', 'type' => 'readout', 'default' => "'face index'", 'note' => [
                'fa' => 'وجه روبهٔ دوربین با قاب رنگی، نقطه‌های پایین و ناحیهٔ وضعیت (role=status) اعلام می‌شود؛ کلیدهای چپ/راست و بالا/پایین ±۹۰ درجه قدم برمی‌دارند و هر نقطه با کوتاه‌ترین مسیر به وجهش می‌پرد.',
                'en' => 'The camera-facing face is announced by a coloured frame, the dots below and a role=status readout; left/right and up/down arrow keys step ±90° and each dot jumps to its face by the shortest path.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="d3fc" x-data="{
            spin: -24, tilt: -12, dragging: false, px: 0, py: 0, vx: 0,
            get style() { return 'transform: rotateX(' + this.tilt + 'deg) rotateY(' + this.spin + 'deg)'; },
            get side() { return ((Math.round(-this.spin / 90) % 4) + 4) % 4; },
            get face() {
                const t = Math.round(this.tilt / 90);
                if (t === -1) return 4;
                if (t === 1) return 5;
                return this.side;
            },
            start(e) { this.dragging = true; this.px = e.clientX; this.py = e.clientY; this.vx = 0; },
            move(e) {
                if (!this.dragging) return;
                const dx = e.clientX - this.px, dy = e.clientY - this.py;
                this.px = e.clientX; this.py = e.clientY;
                this.spin += dx * 0.35; this.vx = dx * 0.35;
                this.tilt = Math.min(90, Math.max(-90, this.tilt - dy * 0.25));
            },
            end() {
                const projected = Math.abs(this.vx) > 2 ? this.spin + this.vx * 6 : this.spin;
                this.dragging = false;
                this.spin = Math.round(projected / 90) * 90;
                this.tilt = Math.min(90, Math.max(-90, Math.round(this.tilt / 90) * 90));
            },
            step(d) { this.dragging = false; this.spin += d * 90; },
            stepTilt(d) { this.dragging = false; this.tilt = Math.min(90, Math.max(-90, this.tilt + d * 90)); },
        }" x-bind:data-dragging="dragging ? 'true' : 'false'">
            <div class="d3fc-stage" tabindex="0" role="group"
                 aria-label="مکعب ویژگی‌ها؛ بکشید یا از کلیدهای جهت‌دار استفاده کنید"
                 x-on:pointerdown="start($event)" x-on:pointermove="move($event)"
                 x-on:pointerup="end()" x-on:pointercancel="end()"
                 x-on:keydown.arrow-right.prevent="step(-1)" x-on:keydown.arrow-left.prevent="step(1)"
                 x-on:keydown.arrow-up.prevent="stepTilt(-1)" x-on:keydown.arrow-down.prevent="stepTilt(1)">
                <div class="d3fc-cube" x-bind:style="style">
                    <div class="d3fc-face" style="--ft: rotateY(0deg)" x-bind:data-on="face === 0">نمایشگر آمولد<small>۲۰۰۰ نیت · همیشه‌روشن</small></div>
                    <div class="d3fc-face" style="--ft: rotateY(90deg)" x-bind:data-on="face === 1">باتری ۲۱ روزه<small>شارژ خورشیدی در کمپ</small></div>
                    <div class="d3fc-face" style="--ft: rotateY(180deg)" x-bind:data-on="face === 2">جی‌پی‌اس دوباند<small>نقشه‌های آفلاین قله‌ها</small></div>
                    <div class="d3fc-face" style="--ft: rotateY(270deg)" x-bind:data-on="face === 3">تیتانیوم درجه ۵<small>وزن ۴۲ گرم</small></div>
                    <div class="d3fc-face" style="--ft: rotateX(90deg)" x-bind:data-on="face === 4">شیشهٔ یاقوت‌کبود<small>ضدخش، حتی روی سنگ</small></div>
                    <div class="d3fc-face" style="--ft: rotateX(-90deg)" x-bind:data-on="face === 5">مقاومت آب ۱۰۰ متر<small>استاندارد ISO 6425</small></div>
                </div>
            </div>
            <div class="d3fc-dock">
                <button type="button" x-on:click="step(1)">وجه قبلی</button>
                <button type="button" x-on:click="step(-1)">وجه بعدی</button>
                <span role="status" x-text="'وجه ' + (face + 1) + ' از ۶'"></span>
            </div>
        </div>

        <style>
        .d3fc-stage { inline-size: min(100%, 26rem); aspect-ratio: 4 / 3; display: grid; place-items: center;
                      perspective: 1000px; overflow: clip; cursor: grab; touch-action: none; user-select: none; }
        .d3fc-cube { --s: min(56vw, 9.5rem); position: relative; inline-size: var(--s); block-size: var(--s);
                     transform-style: preserve-3d; transition: transform .55s cubic-bezier(.22, 1, .36, 1); }
        .d3fc[data-dragging='true'] .d3fc-cube { transition: none; }
        .d3fc-face { position: absolute; inset: 0; display: grid; place-content: center; gap: .3rem; padding: 1rem;
                     text-align: center; border-radius: .9rem; background: #fff; border: 1px solid #e7e5e4;
                     backface-visibility: hidden; transform: var(--ft) translateZ(calc(var(--s) / 2)); }
        .d3fc-face[data-on='true'] { border-color: #4f46e5; box-shadow: 0 0 0 2px #c7d2fe; }
        @media (prefers-reduced-motion: reduce) { .d3fc-cube { transition: none; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="d3fc" x-data="{
            spin: -24, tilt: -12, dragging: false, px: 0, py: 0, vx: 0,
            get style() { return 'transform: rotateX(' + this.tilt + 'deg) rotateY(' + this.spin + 'deg)'; },
            get side() { return ((Math.round(-this.spin / 90) % 4) + 4) % 4; },
            get face() {
                const t = Math.round(this.tilt / 90);
                if (t === -1) return 4;
                if (t === 1) return 5;
                return this.side;
            },
            start(e) { this.dragging = true; this.px = e.clientX; this.py = e.clientY; this.vx = 0; },
            move(e) {
                if (!this.dragging) return;
                const dx = e.clientX - this.px, dy = e.clientY - this.py;
                this.px = e.clientX; this.py = e.clientY;
                this.spin += dx * 0.35; this.vx = dx * 0.35;
                this.tilt = Math.min(90, Math.max(-90, this.tilt - dy * 0.25));
            },
            end() {
                const projected = Math.abs(this.vx) > 2 ? this.spin + this.vx * 6 : this.spin;
                this.dragging = false;
                this.spin = Math.round(projected / 90) * 90;
                this.tilt = Math.min(90, Math.max(-90, Math.round(this.tilt / 90) * 90));
            },
            step(d) { this.dragging = false; this.spin += d * 90; },
            stepTilt(d) { this.dragging = false; this.tilt = Math.min(90, Math.max(-90, this.tilt + d * 90)); },
        }" x-bind:data-dragging="dragging ? 'true' : 'false'">
            <div class="d3fc-stage" tabindex="0" role="group"
                 aria-label="Feature cube — drag it or use the arrow keys"
                 x-on:pointerdown="start($event)" x-on:pointermove="move($event)"
                 x-on:pointerup="end()" x-on:pointercancel="end()"
                 x-on:keydown.arrow-right.prevent="step(-1)" x-on:keydown.arrow-left.prevent="step(1)"
                 x-on:keydown.arrow-up.prevent="stepTilt(-1)" x-on:keydown.arrow-down.prevent="stepTilt(1)">
                <div class="d3fc-cube" x-bind:style="style">
                    <div class="d3fc-face" style="--ft: rotateY(0deg)" x-bind:data-on="face === 0">AMOLED display<small>2,000 nits · always-on</small></div>
                    <div class="d3fc-face" style="--ft: rotateY(90deg)" x-bind:data-on="face === 1">21-day battery<small>Solar top-up on the trail</small></div>
                    <div class="d3fc-face" style="--ft: rotateY(180deg)" x-bind:data-on="face === 2">Dual-band GPS<small>Offline summit maps</small></div>
                    <div class="d3fc-face" style="--ft: rotateY(270deg)" x-bind:data-on="face === 3">Grade-5 titanium<small>42 g on the wrist</small></div>
                    <div class="d3fc-face" style="--ft: rotateX(90deg)" x-bind:data-on="face === 4">Sapphire glass<small>Scratch-proof on rock</small></div>
                    <div class="d3fc-face" style="--ft: rotateX(-90deg)" x-bind:data-on="face === 5">100 m water rating<small>ISO 6425 diver test</small></div>
                </div>
            </div>
            <div class="d3fc-dock">
                <button type="button" x-on:click="step(1)">Previous face</button>
                <button type="button" x-on:click="step(-1)">Next face</button>
                <span role="status" x-text="'Face ' + (face + 1) + ' of 6'"></span>
            </div>
        </div>

        <style>
        .d3fc-stage { inline-size: min(100%, 26rem); aspect-ratio: 4 / 3; display: grid; place-items: center;
                      perspective: 1000px; overflow: clip; cursor: grab; touch-action: none; user-select: none; }
        .d3fc-cube { --s: min(56vw, 9.5rem); position: relative; inline-size: var(--s); block-size: var(--s);
                     transform-style: preserve-3d; transition: transform .55s cubic-bezier(.22, 1, .36, 1); }
        .d3fc[data-dragging='true'] .d3fc-cube { transition: none; }
        .d3fc-face { position: absolute; inset: 0; display: grid; place-content: center; gap: .3rem; padding: 1rem;
                     text-align: center; border-radius: .9rem; background: #fff; border: 1px solid #e7e5e4;
                     backface-visibility: hidden; transform: var(--ft) translateZ(calc(var(--s) / 2)); }
        .d3fc-face[data-on='true'] { border-color: #4f46e5; box-shadow: 0 0 0 2px #c7d2fe; }
        @media (prefers-reduced-motion: reduce) { .d3fc-cube { transition: none; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/FlipCube.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import FlipCube from '@/components/FlipCube';

        export default function FlipCubePage() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <FlipCube />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // FlipCube.jsx — six-face feature cube: drag spins, release snaps, keys step
        import { useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const FACES = [
          { t: 'rotateY(0deg)', label: 'AMOLED display', spec: '2,000 nits · always-on' },
          { t: 'rotateY(90deg)', label: '21-day battery', spec: 'Solar top-up on the trail' },
          { t: 'rotateY(180deg)', label: 'Dual-band GPS', spec: 'Offline summit maps' },
          { t: 'rotateY(270deg)', label: 'Grade-5 titanium', spec: '42 g on the wrist' },
          { t: 'rotateX(90deg)', label: 'Sapphire glass', spec: 'Scratch-proof on rock' },
          { t: 'rotateX(-90deg)', label: '100 m water rating', spec: 'ISO 6425 diver test' },
        ];

        export default function FlipCube() {
          const [rot, setRot] = useState({ spin: -24, tilt: -12 });
          const [dragging, setDragging] = useState(false);
          const stage = useRef(null);
          const drag = useRef(null);
          const vx = useRef(0);

          // The camera-facing face: sides ring Y, faces 4/5 cap top and bottom.
          const side = ((Math.round(-rot.spin / 90) % 4) + 4) % 4;
          const tStep = Math.round(rot.tilt / 90);
          const face = tStep === -1 ? 4 : tStep === 1 ? 5 : side;

          const onDown = (e) => {
            stage.current.setPointerCapture(e.pointerId);
            drag.current = { x: e.clientX, y: e.clientY };
            vx.current = 0;
            setDragging(true);
          };
          const onMove = (e) => {
            if (!drag.current) return;
            const dx = e.clientX - drag.current.x, dy = e.clientY - drag.current.y;
            drag.current = { x: e.clientX, y: e.clientY };
            vx.current = dx * 0.35;
            setRot((r) => ({ spin: r.spin + dx * 0.35,
              tilt: Math.min(90, Math.max(-90, r.tilt - dy * 0.25)) }));
          };
          const onUp = () => {
            if (!drag.current) return;
            // Project the finger's velocity six frames ahead, then seat both axes.
            const projected = Math.abs(vx.current) > 2 ? rot.spin + vx.current * 6 : rot.spin;
            drag.current = null;
            setDragging(false);
            setRot((r) => ({ spin: Math.round(projected / 90) * 90,
              tilt: Math.min(90, Math.max(-90, Math.round(r.tilt / 90) * 90)) }));
          };
          const step = (faces) => {
            drag.current = null;
            setDragging(false);
            setRot((r) => ({ ...r, spin: r.spin + faces * 90 }));
          };
          const stepTilt = (faces) => {
            drag.current = null;
            setDragging(false);
            setRot((r) => ({ ...r, tilt: Math.min(90, Math.max(-90, r.tilt + faces * 90)) }));
          };
          const setTilt = (tilt) => {
            drag.current = null;
            setDragging(false);
            setRot((r) => ({ ...r, tilt }));
          };
          const jump = (i) => {
            if (i === 4) { setTilt(-90); return; }
            if (i === 5) { setTilt(90); return; }
            setTilt(0);
            let d = i - side;
            if (d > 2) d -= 4;
            if (d < -2) d += 4;
            step(d);
          };

          return (
            <>
              <style>{`
                .d3fc-stage { inline-size: min(100%, 26rem); aspect-ratio: 4 / 3; display: grid; place-items: center;
                              perspective: 1000px; overflow: clip; cursor: grab; touch-action: none; user-select: none; }
                .d3fc-cube { --s: min(56vw, 9.5rem); position: relative; inline-size: var(--s); block-size: var(--s);
                             transform-style: preserve-3d; transition: transform .55s cubic-bezier(.22, 1, .36, 1); }
                .d3fc-stage[data-dragging='true'] .d3fc-cube { transition: none; }
                .d3fc-face { position: absolute; inset: 0; display: grid; place-content: center; gap: .3rem; padding: 1rem;
                             text-align: center; border-radius: .9rem; background: #fff; border: 1px solid #e7e5e4; color: #1c1917;
                             font: 600 .85rem/1.3 sans-serif; backface-visibility: hidden;
                             transform: var(--ft) translateZ(calc(var(--s) / 2)); }
                .d3fc-face[data-on='true'] { border-color: #4f46e5; box-shadow: 0 0 0 2px #c7d2fe; }
                .d3fc-face small { font-weight: 400; color: #78716c; }
                @media (prefers-reduced-motion: reduce) { .d3fc-cube { transition: none; } }
              `}</style>
              <div className="d3fc-wrap">
                <div ref={stage} className="d3fc-stage" tabIndex={0} role="group"
                     aria-label="Feature cube — drag it or use the arrow keys" data-dragging={dragging}
                     onPointerDown={onDown} onPointerMove={onMove} onPointerUp={onUp} onPointerCancel={onUp}
                     onKeyDown={(e) => {
                       if (e.key === 'ArrowRight') { e.preventDefault(); step(-1); }
                       if (e.key === 'ArrowLeft') { e.preventDefault(); step(1); }
                       if (e.key === 'ArrowUp') { e.preventDefault(); stepTilt(-1); }
                       if (e.key === 'ArrowDown') { e.preventDefault(); stepTilt(1); }
                     }}>
                  <div className="d3fc-cube" style={{ transform: `rotateX(${rot.tilt}deg) rotateY(${rot.spin}deg)` }}>
                    {FACES.map((f, i) => (
                      <div key={f.label} className="d3fc-face" data-on={face === i} style={{ '--ft': f.t }}>
                        <b>{f.label}</b><small>{f.spec}</small>
                      </div>
                    ))}
                  </div>
                </div>
                <div className="d3fc-dock">
                  <button type="button" onClick={() => step(1)}>‹ Prev</button>
                  {FACES.map((f, i) => (
                    <button type="button" key={f.label} className="d3fc-dot" aria-current={face === i}
                            aria-label={f.label} onClick={() => jump(i)} />
                  ))}
                  <button type="button" onClick={() => step(-1)}>Next ›</button>
                  <span role="status">Face {face + 1} of 6 — {FACES[face].label}</span>
                </div>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- FlipCube.vue — six-face feature cube: drag spins, release snaps, keys step -->
        <script setup lang="ts">
        import { computed, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const faces = [
          { t: 'rotateY(0deg)', label: 'AMOLED display', spec: '2,000 nits · always-on' },
          { t: 'rotateY(90deg)', label: '21-day battery', spec: 'Solar top-up on the trail' },
          { t: 'rotateY(180deg)', label: 'Dual-band GPS', spec: 'Offline summit maps' },
          { t: 'rotateY(270deg)', label: 'Grade-5 titanium', spec: '42 g on the wrist' },
          { t: 'rotateX(90deg)', label: 'Sapphire glass', spec: 'Scratch-proof on rock' },
          { t: 'rotateX(-90deg)', label: '100 m water rating', spec: 'ISO 6425 diver test' },
        ];
        const spin = ref(-24);
        const tilt = ref(-12);
        const dragging = ref(false);
        const stage = ref<HTMLElement | null>(null);
        let drag: { x: number; y: number } | null = null;
        let vx = 0;

        // The camera-facing face: sides ring Y, faces 4/5 cap top and bottom.
        const side = computed(() => ((Math.round(-spin.value / 90) % 4) + 4) % 4);
        const face = computed(() => {
          const t = Math.round(tilt.value / 90);
          if (t === -1) return 4;
          if (t === 1) return 5;
          return side.value;
        });
        const cubeStyle = computed(() => `transform: rotateX(${tilt.value}deg) rotateY(${spin.value}deg)`);

        const onDown = (e: PointerEvent) => {
          stage.value?.setPointerCapture(e.pointerId);
          drag = { x: e.clientX, y: e.clientY };
          vx = 0;
          dragging.value = true;
        };
        const onMove = (e: PointerEvent) => {
          if (!drag) return;
          const dx = e.clientX - drag.x, dy = e.clientY - drag.y;
          drag = { x: e.clientX, y: e.clientY };
          spin.value += dx * 0.35;
          tilt.value = Math.min(90, Math.max(-90, tilt.value - dy * 0.25));
          vx = dx * 0.35;
        };
        const onUp = () => {
          if (!drag) return;
          // Project the finger's velocity six frames ahead, then seat both axes.
          const projected = Math.abs(vx) > 2 ? spin.value + vx * 6 : spin.value;
          drag = null;
          dragging.value = false;
          spin.value = Math.round(projected / 90) * 90;
          tilt.value = Math.min(90, Math.max(-90, Math.round(tilt.value / 90) * 90));
        };
        const step = (n: number) => { drag = null; dragging.value = false; spin.value += n * 90; };
        const stepTilt = (n: number) => {
          drag = null; dragging.value = false;
          tilt.value = Math.min(90, Math.max(-90, tilt.value + n * 90));
        };
        const setTilt = (t: number) => { drag = null; dragging.value = false; tilt.value = t; };
        const jump = (i: number) => {
          if (i === 4) { setTilt(-90); return; }
          if (i === 5) { setTilt(90); return; }
          setTilt(0);
          let d = i - side.value;
          if (d > 2) d -= 4;
          if (d < -2) d += 4;
          step(d);
        };
        </script>

        <template>
          <div class="d3fc-wrap">
            <div ref="stage" class="d3fc-stage" tabindex="0" role="group"
                 aria-label="Feature cube — drag it or use the arrow keys" :data-dragging="dragging"
                 @pointerdown="onDown" @pointermove="onMove" @pointerup="onUp" @pointercancel="onUp"
                 @keydown.arrow-right.prevent="step(-1)" @keydown.arrow-left.prevent="step(1)"
                 @keydown.arrow-up.prevent="stepTilt(-1)" @keydown.arrow-down.prevent="stepTilt(1)">
              <div class="d3fc-cube" :style="cubeStyle">
                <div v-for="(f, i) in faces" :key="f.label" class="d3fc-face" :style="{ '--ft': f.t }" :data-on="face === i">
                  <b>{{ f.label }}</b><small>{{ f.spec }}</small>
                </div>
              </div>
            </div>
            <div class="d3fc-dock">
              <button type="button" @click="step(1)">‹ Prev</button>
              <button v-for="(f, i) in faces" :key="f.label" type="button" class="d3fc-dot"
                      :aria-current="face === i" :aria-label="f.label" @click="jump(i)"></button>
              <button type="button" @click="step(-1)">Next ›</button>
              <span role="status">Face {{ face + 1 }} of 6 — {{ faces[face].label }}</span>
            </div>
          </div>
        </template>

        <style scoped>
        .d3fc-stage { inline-size: min(100%, 26rem); aspect-ratio: 4 / 3; display: grid; place-items: center;
                      perspective: 1000px; overflow: clip; cursor: grab; touch-action: none; user-select: none; }
        .d3fc-cube { --s: min(56vw, 9.5rem); position: relative; inline-size: var(--s); block-size: var(--s);
                     transform-style: preserve-3d; transition: transform .55s cubic-bezier(.22, 1, .36, 1); }
        .d3fc-stage[data-dragging='true'] .d3fc-cube { transition: none; }
        .d3fc-face { position: absolute; inset: 0; display: grid; place-content: center; gap: .3rem; padding: 1rem;
                     text-align: center; border-radius: .9rem; background: #fff; border: 1px solid #e7e5e4; color: #1c1917;
                     font: 600 .85rem/1.3 sans-serif; backface-visibility: hidden;
                     transform: var(--ft) translateZ(calc(var(--s) / 2)); }
        .d3fc-face[data-on='true'] { border-color: #4f46e5; box-shadow: 0 0 0 2px #c7d2fe; }
        .d3fc-face small { font-weight: 400; color: #78716c; }
        @media (prefers-reduced-motion: reduce) { .d3fc-cube { transition: none; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- FlipCube.svelte — six-face feature cube: drag spins, release snaps, keys step -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const faces = [
            { t: 'rotateY(0deg)', label: 'AMOLED display', spec: '2,000 nits · always-on' },
            { t: 'rotateY(90deg)', label: '21-day battery', spec: 'Solar top-up on the trail' },
            { t: 'rotateY(180deg)', label: 'Dual-band GPS', spec: 'Offline summit maps' },
            { t: 'rotateY(270deg)', label: 'Grade-5 titanium', spec: '42 g on the wrist' },
            { t: 'rotateX(90deg)', label: 'Sapphire glass', spec: 'Scratch-proof on rock' },
            { t: 'rotateX(-90deg)', label: '100 m water rating', spec: 'ISO 6425 diver test' },
          ];
          let spin = $state(-24);
          let tilt = $state(-12);
          let dragging = $state(false);
          let stage: HTMLElement;
          let drag: { x: number; y: number } | null = null;
          let vx = 0;

          // The camera-facing face: sides ring Y, faces 4/5 cap top and bottom.
          const side = $derived(((Math.round(-spin / 90) % 4) + 4) % 4);
          const face = $derived.by(() => {
            const t = Math.round(tilt / 90);
            if (t === -1) return 4;
            if (t === 1) return 5;
            return side;
          });
          const cubeStyle = $derived(`transform: rotateX(${tilt}deg) rotateY(${spin}deg)`);

          const onDown = (e: PointerEvent) => {
            stage.setPointerCapture(e.pointerId);
            drag = { x: e.clientX, y: e.clientY };
            vx = 0;
            dragging = true;
          };
          const onMove = (e: PointerEvent) => {
            if (!drag) return;
            const dx = e.clientX - drag.x, dy = e.clientY - drag.y;
            drag = { x: e.clientX, y: e.clientY };
            spin += dx * 0.35;
            tilt = Math.min(90, Math.max(-90, tilt - dy * 0.25));
            vx = dx * 0.35;
          };
          const onUp = () => {
            if (!drag) return;
            // Project the finger's velocity six frames ahead, then seat both axes.
            const projected = Math.abs(vx) > 2 ? spin + vx * 6 : spin;
            drag = null;
            dragging = false;
            spin = Math.round(projected / 90) * 90;
            tilt = Math.min(90, Math.max(-90, Math.round(tilt / 90) * 90));
          };
          const step = (n: number) => { drag = null; dragging = false; spin += n * 90; };
          const stepTilt = (n: number) => {
            drag = null; dragging = false;
            tilt = Math.min(90, Math.max(-90, tilt + n * 90));
          };
          const setTilt = (t: number) => { drag = null; dragging = false; tilt = t; };
          const jump = (i: number) => {
            if (i === 4) { setTilt(-90); return; }
            if (i === 5) { setTilt(90); return; }
            setTilt(0);
            let d = i - side;
            if (d > 2) d -= 4;
            if (d < -2) d += 4;
            step(d);
          };
        </script>

        <div class="d3fc-wrap">
          <div bind:this={stage} class="d3fc-stage" tabindex="0" role="group"
               aria-label="Feature cube — drag it or use the arrow keys" data-dragging={dragging}
               onpointerdown={onDown} onpointermove={onMove} onpointerup={onUp} onpointercancel={onUp}
               onkeydown={(e) => {
                 if (e.key === 'ArrowRight') { e.preventDefault(); step(-1); }
                 if (e.key === 'ArrowLeft') { e.preventDefault(); step(1); }
                 if (e.key === 'ArrowUp') { e.preventDefault(); stepTilt(-1); }
                 if (e.key === 'ArrowDown') { e.preventDefault(); stepTilt(1); }
               }}>
            <div class="d3fc-cube" style={cubeStyle}>
              {#each faces as f, i (f.label)}
                <div class="d3fc-face" style={`--ft: ${f.t}`} data-on={face === i}>
                  <b>{f.label}</b><small>{f.spec}</small>
                </div>
              {/each}
            </div>
          </div>
          <div class="d3fc-dock">
            <button type="button" onclick={() => step(1)}>‹ Prev</button>
            {#each faces as f, i (f.label)}
              <button type="button" class="d3fc-dot" aria-current={face === i} aria-label={f.label}
                      onclick={() => jump(i)}></button>
            {/each}
            <button type="button" onclick={() => step(-1)}>Next ›</button>
            <span role="status">Face {face + 1} of 6 — {faces[face].label}</span>
          </div>
        </div>

        <style>
        .d3fc-stage { inline-size: min(100%, 26rem); aspect-ratio: 4 / 3; display: grid; place-items: center;
                      perspective: 1000px; overflow: clip; cursor: grab; touch-action: none; user-select: none; }
        .d3fc-cube { --s: min(56vw, 9.5rem); position: relative; inline-size: var(--s); block-size: var(--s);
                     transform-style: preserve-3d; transition: transform .55s cubic-bezier(.22, 1, .36, 1); }
        .d3fc-stage[data-dragging='true'] .d3fc-cube { transition: none; }
        .d3fc-face { position: absolute; inset: 0; display: grid; place-content: center; gap: .3rem; padding: 1rem;
                     text-align: center; border-radius: .9rem; background: #fff; border: 1px solid #e7e5e4; color: #1c1917;
                     font: 600 .85rem/1.3 sans-serif; backface-visibility: hidden;
                     transform: var(--ft) translateZ(calc(var(--s) / 2)); }
        .d3fc-face[data-on='true'] { border-color: #4f46e5; box-shadow: 0 0 0 2px #c7d2fe; }
        .d3fc-face small { font-weight: 400; color: #78716c; }
        @media (prefers-reduced-motion: reduce) { .d3fc-cube { transition: none; } }
        </style>
        SVELTE,
        ],
    ],

    'open-box' => [
        'title' => ['fa' => 'جعبهٔ بازشو', 'en' => 'Open box'],
        'icon' => 'sparkles',
        'oneLiner' => [
            'fa' => 'جعبهٔ محصول سه‌بعدی Halo Buds Pro با CSS خالص — درِ لولادار به لبهٔ پشتی با هاور یا کلیک باز می‌شود، هدفون‌ها با فنر از جعبه بیرون می‌جهند و برچسب قیمت دوردو طرف به دور خودش می‌چرخد؛ وضعیت بسته/باز با aria-expanded اعلام می‌شود و اشاره روی صحنه جعبه را نرم متمایل می‌کند.',
            'en' => 'A pure-CSS 3D box for the Halo Buds Pro — the hinged lid swings off the back edge on hover or click, the earbuds spring out of the box, and a two-sided price tag spins on its own axis; closed/open is announced through aria-expanded and the pointer tilts the box softly as it moves.',
        ],
        'js' => true,
        'docs' => 'https://scrolltide.co',
        'props' => [
            ['name' => 'open', 'type' => 'state', 'default' => "'hover · click'", 'note' => [
                'fa' => 'هاور در را باز می‌کند و با رفتن اشاره می‌بندد؛ کلیک حالت باز را سنجاق می‌کند و رها می‌کند — وضعیت روی خودِ صحنه و دکمه با aria-expanded اعلام می‌شود.',
                'en' => 'Hover swings the lid open and leaving closes it; a click pins the open state and a second click releases it — the state is announced with aria-expanded on both the stage and the button.',
            ]],
            ['name' => 'lid', 'type' => 'deg', 'default' => "'110°'", 'note' => [
                'fa' => 'در به لبهٔ پشتی لولا شده و ۱۱۰ درجه باز می‌شود؛ فنر ۰٫۷ ثانیه‌ای با کمی اورشوت، در را بالا و عقب می‌برد.',
                'en' => 'The lid hinges on the back edge and opens 110°; a 0.7 s spring with a touch of overshoot swings it up and back.',
            ]],
            ['name' => 'goods', 'type' => 'spring', 'default' => "'pop'", 'note' => [
                'fa' => 'قابل‌شارژ و گوشی‌ها داخل جعبه پنهان‌اند؛ با باز شدن، ۶۲٪ بالای لبهٔ جعبه می‌جهند و بسته که شود سرِ جایشان می‌نشینند.',
                'en' => 'The charging case and buds hide inside the box; on open they leap 62% above the rim and settle back home when it closes.',
            ]],
            ['name' => 'tag', 'type' => 'animation', 'default' => "'7s'", 'note' => [
                'fa' => 'برچسب قیمت دوردو (متن در هر دو طرف) هر ۷ ثانیه یک دور کامل حول محور Y می‌زند؛ خارج از دید و در حرکت کاهش‌یافته متوقف است.',
                'en' => 'The two-sided price tag (text on both faces) completes a full turn around Y every 7 s; it is parked off screen and under reduced motion.',
            ]],
            ['name' => 'tilt', 'type' => 'parallax', 'default' => "'±10°'", 'note' => [
                'fa' => 'حرکت اشاره روی صحنه، جعبه را تا ۱۰ درجه نرم و با تأخیر کوتاه دنبال می‌کند — اینرسیِ CSS بدون هیچ حلقهٔ جاوااسکریپتی.',
                'en' => 'Moving the pointer across the stage tilts the box by up to 10°, softly and slightly delayed — CSS inertia with no JavaScript loop.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="d3ob" x-data="{
            pinned: false, hovering: false, rx: -14, ry: 32,
            get open() { return this.pinned || this.hovering; },
            get boxStyle() { return 'transform: rotateX(' + this.rx + 'deg) rotateY(' + this.ry + 'deg)'; },
            tilt(e) {
                const r = this.$refs.stage.getBoundingClientRect();
                this.ry = 32 + ((e.clientX - r.left) / r.width - 0.5) * 14;
                this.rx = -14 - ((e.clientY - r.top) / r.height - 0.5) * 10;
            },
            untilt() { this.rx = -14; this.ry = 32; },
        }" x-bind:data-open="open ? 'true' : 'false'">
            <div class="d3ob-stage" x-ref="stage" tabindex="0" role="button"
                 x-bind:aria-expanded="open ? 'true' : 'false'"
                 aria-label="جعبهٔ Halo Buds Pro؛ هاور یا کلیک کنید تا باز شود"
                 x-on:pointerenter="hovering = true" x-on:pointerleave="hovering = false; untilt()"
                 x-on:pointermove="tilt($event)" x-on:click="pinned = !pinned"
                 x-on:keydown.enter.prevent="pinned = !pinned" x-on:keydown.space.prevent="pinned = !pinned">
                <div class="d3ob-tilt" x-bind:style="boxStyle">
                    <div class="d3ob-box">
                        <div class="d3ob-face d3ob-front">HALO<small>BUDS PRO</small></div>
                        <div class="d3ob-face d3ob-back"></div>
                        <div class="d3ob-face d3ob-left"></div>
                        <div class="d3ob-face d3ob-right"></div>
                        <div class="d3ob-floor"></div>
                        <div class="d3ob-goods">Halo Buds Pro</div>
                        <div class="d3ob-lid"></div>
                    </div>
                </div>
            </div>
            <button type="button" x-on:click="pinned = !pinned"
                    x-bind:aria-expanded="open ? 'true' : 'false'"
                    x-text="open ? 'بستن جعبه' : 'باز کردن جعبه'"></button>
        </div>

        <style>
        .d3ob-stage { inline-size: min(100%, 26rem); aspect-ratio: 4 / 3; display: grid; place-items: center;
                      perspective: 950px; overflow: clip; cursor: pointer; user-select: none; }
        .d3ob-tilt { transition: transform .45s ease-out; transform-style: preserve-3d; }
        .d3ob-box { --w: min(46vw, 10rem); --h: calc(var(--w) * .78); --d: calc(var(--w) * .52);
                    position: relative; inline-size: var(--w); block-size: var(--h); transform-style: preserve-3d; }
        .d3ob-face { position: absolute; inset: 0; backface-visibility: hidden; }
        .d3ob-front { background: #4f46e5; border-radius: .5rem; transform: translateZ(calc(var(--d) / 2));
                      color: #fff; display: grid; place-content: center; font: 700 1rem sans-serif; }
        .d3ob-back { background: #3730a3; border-radius: .5rem; transform: rotateY(180deg) translateZ(calc(var(--d) / 2)); }
        .d3ob-left, .d3ob-right { inline-size: var(--d); inset-inline-start: calc(50% - var(--d) / 2); background: #6366f1; }
        .d3ob-left { transform: translateX(calc(var(--w) / -2)) rotateY(-90deg); }
        .d3ob-right { transform: translateX(calc(var(--w) / 2)) rotateY(90deg); }
        .d3ob-floor { position: absolute; inline-size: var(--w); block-size: var(--d); inset-inline: 0;
                      inset-block-start: calc(50% - var(--d) / 2); background: #1e1b4b; backface-visibility: visible;
                      transform: translateY(calc(var(--h) / 2)) rotateX(90deg); }
        .d3ob-lid { position: absolute; inline-size: var(--w); block-size: var(--d); inset-inline: 0; inset-block-start: 0;
                    transform-origin: 50% 0; background: #4338ca; border-radius: .5rem .5rem 0 0;
                    transform: translateZ(calc(var(--d) / -2)) rotateX(90deg);
                    transition: transform .7s cubic-bezier(.34, 1.56, .64, 1); }
        .d3ob[data-open='true'] .d3ob-lid { transform: translateZ(calc(var(--d) / -2)) rotateX(200deg); }
        .d3ob-goods { position: absolute; inset: 0; display: grid; place-items: center; padding: .5rem;
                      background: #fff; border-radius: 1rem; color: #1c1917; font: 600 .7rem sans-serif;
                      transform: translateY(6%); transition: transform .7s cubic-bezier(.34, 1.56, .64, 1); }
        .d3ob[data-open='true'] .d3ob-goods { transform: translateY(-62%); }
        @media (prefers-reduced-motion: reduce) { .d3ob-tilt, .d3ob-lid, .d3ob-goods { transition: none; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="d3ob" x-data="{
            pinned: false, hovering: false, rx: -14, ry: 32,
            get open() { return this.pinned || this.hovering; },
            get boxStyle() { return 'transform: rotateX(' + this.rx + 'deg) rotateY(' + this.ry + 'deg)'; },
            tilt(e) {
                const r = this.$refs.stage.getBoundingClientRect();
                this.ry = 32 + ((e.clientX - r.left) / r.width - 0.5) * 14;
                this.rx = -14 - ((e.clientY - r.top) / r.height - 0.5) * 10;
            },
            untilt() { this.rx = -14; this.ry = 32; },
        }" x-bind:data-open="open ? 'true' : 'false'">
            <div class="d3ob-stage" x-ref="stage" tabindex="0" role="button"
                 x-bind:aria-expanded="open ? 'true' : 'false'"
                 aria-label="Halo Buds Pro box — hover or click to open"
                 x-on:pointerenter="hovering = true" x-on:pointerleave="hovering = false; untilt()"
                 x-on:pointermove="tilt($event)" x-on:click="pinned = !pinned"
                 x-on:keydown.enter.prevent="pinned = !pinned" x-on:keydown.space.prevent="pinned = !pinned">
                <div class="d3ob-tilt" x-bind:style="boxStyle">
                    <div class="d3ob-box">
                        <div class="d3ob-face d3ob-front">HALO<small>BUDS PRO</small></div>
                        <div class="d3ob-face d3ob-back"></div>
                        <div class="d3ob-face d3ob-left"></div>
                        <div class="d3ob-face d3ob-right"></div>
                        <div class="d3ob-floor"></div>
                        <div class="d3ob-goods">Halo Buds Pro</div>
                        <div class="d3ob-lid"></div>
                    </div>
                </div>
            </div>
            <button type="button" x-on:click="pinned = !pinned"
                    x-bind:aria-expanded="open ? 'true' : 'false'"
                    x-text="open ? 'Close the box' : 'Open the box'"></button>
        </div>

        <style>
        .d3ob-stage { inline-size: min(100%, 26rem); aspect-ratio: 4 / 3; display: grid; place-items: center;
                      perspective: 950px; overflow: clip; cursor: pointer; user-select: none; }
        .d3ob-tilt { transition: transform .45s ease-out; transform-style: preserve-3d; }
        .d3ob-box { --w: min(46vw, 10rem); --h: calc(var(--w) * .78); --d: calc(var(--w) * .52);
                    position: relative; inline-size: var(--w); block-size: var(--h); transform-style: preserve-3d; }
        .d3ob-face { position: absolute; inset: 0; backface-visibility: hidden; }
        .d3ob-front { background: #4f46e5; border-radius: .5rem; transform: translateZ(calc(var(--d) / 2));
                      color: #fff; display: grid; place-content: center; font: 700 1rem sans-serif; }
        .d3ob-back { background: #3730a3; border-radius: .5rem; transform: rotateY(180deg) translateZ(calc(var(--d) / 2)); }
        .d3ob-left, .d3ob-right { inline-size: var(--d); inset-inline-start: calc(50% - var(--d) / 2); background: #6366f1; }
        .d3ob-left { transform: translateX(calc(var(--w) / -2)) rotateY(-90deg); }
        .d3ob-right { transform: translateX(calc(var(--w) / 2)) rotateY(90deg); }
        .d3ob-floor { position: absolute; inline-size: var(--w); block-size: var(--d); inset-inline: 0;
                      inset-block-start: calc(50% - var(--d) / 2); background: #1e1b4b; backface-visibility: visible;
                      transform: translateY(calc(var(--h) / 2)) rotateX(90deg); }
        .d3ob-lid { position: absolute; inline-size: var(--w); block-size: var(--d); inset-inline: 0; inset-block-start: 0;
                    transform-origin: 50% 0; background: #4338ca; border-radius: .5rem .5rem 0 0;
                    transform: translateZ(calc(var(--d) / -2)) rotateX(90deg);
                    transition: transform .7s cubic-bezier(.34, 1.56, .64, 1); }
        .d3ob[data-open='true'] .d3ob-lid { transform: translateZ(calc(var(--d) / -2)) rotateX(200deg); }
        .d3ob-goods { position: absolute; inset: 0; display: grid; place-items: center; padding: .5rem;
                      background: #fff; border-radius: 1rem; color: #1c1917; font: 600 .7rem sans-serif;
                      transform: translateY(6%); transition: transform .7s cubic-bezier(.34, 1.56, .64, 1); }
        .d3ob[data-open='true'] .d3ob-goods { transform: translateY(-62%); }
        @media (prefers-reduced-motion: reduce) { .d3ob-tilt, .d3ob-lid, .d3ob-goods { transition: none; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/OpenBox.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import OpenBox from '@/components/OpenBox';

        export default function OpenBoxPage() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <OpenBox />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // OpenBox.jsx — hinged-lid unboxing: hover or click opens, goods pop on a spring
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        export default function OpenBox() {
          const [pinned, setPinned] = useState(false);
          const [hovering, setHovering] = useState(false);
          const [tilt, setTilt] = useState({ rx: -14, ry: 32 });
          const stage = useRef(null);
          const io = useRef(null);

          const open = pinned || hovering;

          // Park the spinning price tag while the box is off screen.
          useEffect(() => {
            io.current = new IntersectionObserver(([e]) => {
              stage.current.dataset.visible = e.isIntersecting ? 'true' : 'false';
            });
            io.current.observe(stage.current);
            return () => io.current.disconnect();
          }, []);

          const onMove = (e) => {
            const r = stage.current.getBoundingClientRect();
            setTilt({
              ry: 32 + ((e.clientX - r.left) / r.width - 0.5) * 14,
              rx: -14 - ((e.clientY - r.top) / r.height - 0.5) * 10,
            });
          };
          const toggle = () => setPinned((p) => !p);

          return (
            <>
              <style>{`
                .d3ob-stage { inline-size: min(100%, 26rem); aspect-ratio: 4 / 3; display: grid; place-items: center;
                              perspective: 950px; overflow: clip; cursor: pointer; user-select: none; }
                .d3ob-tilt { transition: transform .45s ease-out; transform-style: preserve-3d; }
                .d3ob-box { --w: min(46vw, 10rem); --h: calc(var(--w) * .78); --d: calc(var(--w) * .52);
                            position: relative; inline-size: var(--w); block-size: var(--h); transform-style: preserve-3d; }
                .d3ob-face { position: absolute; inset: 0; backface-visibility: hidden; }
                .d3ob-front { background: #4f46e5; border-radius: .5rem; transform: translateZ(calc(var(--d) / 2));
                              color: #fff; display: grid; place-content: center; font: 700 1rem sans-serif; }
                .d3ob-back { background: #3730a3; border-radius: .5rem; transform: rotateY(180deg) translateZ(calc(var(--d) / 2)); }
                .d3ob-left, .d3ob-right { inline-size: var(--d); inset-inline-start: calc(50% - var(--d) / 2); background: #6366f1; }
                .d3ob-left { transform: translateX(calc(var(--w) / -2)) rotateY(-90deg); }
                .d3ob-right { transform: translateX(calc(var(--w) / 2)) rotateY(90deg); }
                .d3ob-floor { position: absolute; inline-size: var(--w); block-size: var(--d); inset-inline: 0;
                              inset-block-start: calc(50% - var(--d) / 2); background: #1e1b4b; backface-visibility: visible;
                              transform: translateY(calc(var(--h) / 2)) rotateX(90deg); }
                .d3ob-lid { position: absolute; inline-size: var(--w); block-size: var(--d); inset-inline: 0; inset-block-start: 0;
                            transform-origin: 50% 0; background: #4338ca; border-radius: .5rem .5rem 0 0;
                            transform: translateZ(calc(var(--d) / -2)) rotateX(90deg);
                            transition: transform .7s cubic-bezier(.34, 1.56, .64, 1); }
                .d3ob-wrap[data-open='true'] .d3ob-lid { transform: translateZ(calc(var(--d) / -2)) rotateX(200deg); }
                .d3ob-goods { position: absolute; inset: 0; display: grid; place-items: center; padding: .5rem;
                              background: #fff; border-radius: 1rem; color: #1c1917; font: 600 .7rem sans-serif;
                              transform: translateY(6%); transition: transform .7s cubic-bezier(.34, 1.56, .64, 1); }
                .d3ob-wrap[data-open='true'] .d3ob-goods { transform: translateY(-62%); }
                .d3ob-tagwrap { position: absolute; inset-inline-end: 9%; inset-block-end: 16%; perspective: 400px; pointer-events: none; }
                .d3ob-tag { position: relative; inline-size: 4.6rem; block-size: 3rem; transform-style: preserve-3d;
                            animation: d3ob-spin 7s linear infinite; }
                .d3ob-tface { position: absolute; inset: 0; display: grid; place-content: center; backface-visibility: hidden;
                              background: #fff; border: 1px solid #e7e5e4; border-radius: .5rem; font: 700 .8rem sans-serif; }
                .d3ob-tface:last-child { transform: rotateY(180deg); }
                .d3ob-stage[data-visible='false'] .d3ob-tag { animation-play-state: paused; }
                @keyframes d3ob-spin { to { transform: rotateY(360deg); } }
                @media (prefers-reduced-motion: reduce) {
                  .d3ob-tilt, .d3ob-lid, .d3ob-goods { transition: none; }
                  .d3ob-tag { animation: none; }
                }
              `}</style>
              <div className="d3ob-wrap" data-open={open}>
                <div ref={stage} className="d3ob-stage" tabIndex={0} role="button"
                     aria-expanded={open} aria-label="Halo Buds Pro box — hover or click to open"
                     onPointerEnter={() => setHovering(true)}
                     onPointerLeave={() => { setHovering(false); setTilt({ rx: -14, ry: 32 }); }}
                     onPointerMove={onMove} onClick={toggle}
                     onKeyDown={(e) => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggle(); } }}>
                  <div className="d3ob-tilt" style={{ transform: `rotateX(${tilt.rx}deg) rotateY(${tilt.ry}deg)` }}>
                    <div className="d3ob-box">
                      <div className="d3ob-face d3ob-front">HALO<small>BUDS PRO</small></div>
                      <div className="d3ob-face d3ob-back" />
                      <div className="d3ob-face d3ob-left" />
                      <div className="d3ob-face d3ob-right" />
                      <div className="d3ob-floor" />
                      <div className="d3ob-goods">Halo Buds Pro</div>
                      <div className="d3ob-lid" />
                    </div>
                  </div>
                  <div className="d3ob-tagwrap">
                    <div className="d3ob-tag">
                      <div className="d3ob-tface">$149</div>
                      <div className="d3ob-tface">$149</div>
                    </div>
                  </div>
                </div>
                <button type="button" aria-expanded={open} onClick={toggle}>
                  {open ? 'Close the box' : 'Open the box'}
                </button>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- OpenBox.vue — hinged-lid unboxing: hover or click opens, goods pop on a spring -->
        <script setup lang="ts">
        import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const pinned = ref(false);
        const hovering = ref(false);
        const rx = ref(-14);
        const ry = ref(32);
        const stage = ref<HTMLElement | null>(null);
        let io: IntersectionObserver | null = null;

        const open = computed(() => pinned.value || hovering.value);
        const boxStyle = computed(() => `transform: rotateX(${rx.value}deg) rotateY(${ry.value}deg)`);

        const onMove = (e: PointerEvent) => {
          if (!stage.value) return;
          const r = stage.value.getBoundingClientRect();
          ry.value = 32 + ((e.clientX - r.left) / r.width - 0.5) * 14;
          rx.value = -14 - ((e.clientY - r.top) / r.height - 0.5) * 10;
        };
        const untilt = () => { rx.value = -14; ry.value = 32; };
        const toggle = () => { pinned.value = !pinned.value; };

        // Park the spinning price tag while the box is off screen.
        onMounted(() => {
          io = new IntersectionObserver((entries) => {
            if (stage.value) stage.value.dataset.visible = entries[0].isIntersecting ? 'true' : 'false';
          });
          if (stage.value) io.observe(stage.value);
        });
        onBeforeUnmount(() => io?.disconnect());
        </script>

        <template>
          <div class="d3ob-wrap" :data-open="open">
            <div ref="stage" class="d3ob-stage" tabindex="0" role="button"
                 :aria-expanded="open" aria-label="Halo Buds Pro box — hover or click to open"
                 @pointerenter="hovering = true" @pointerleave="hovering = false; untilt()"
                 @pointermove="onMove" @click="toggle"
                 @keydown.enter.prevent="toggle" @keydown.space.prevent="toggle">
              <div class="d3ob-tilt" :style="boxStyle">
                <div class="d3ob-box">
                  <div class="d3ob-face d3ob-front">HALO<small>BUDS PRO</small></div>
                  <div class="d3ob-face d3ob-back"></div>
                  <div class="d3ob-face d3ob-left"></div>
                  <div class="d3ob-face d3ob-right"></div>
                  <div class="d3ob-floor"></div>
                  <div class="d3ob-goods">Halo Buds Pro</div>
                  <div class="d3ob-lid"></div>
                </div>
              </div>
              <div class="d3ob-tagwrap">
                <div class="d3ob-tag">
                  <div class="d3ob-tface">$149</div>
                  <div class="d3ob-tface">$149</div>
                </div>
              </div>
            </div>
            <button type="button" :aria-expanded="open" @click="toggle">
              {{ open ? 'Close the box' : 'Open the box' }}
            </button>
          </div>
        </template>

        <style scoped>
        .d3ob-stage { inline-size: min(100%, 26rem); aspect-ratio: 4 / 3; display: grid; place-items: center;
                      perspective: 950px; overflow: clip; cursor: pointer; user-select: none; }
        .d3ob-tilt { transition: transform .45s ease-out; transform-style: preserve-3d; }
        .d3ob-box { --w: min(46vw, 10rem); --h: calc(var(--w) * .78); --d: calc(var(--w) * .52);
                    position: relative; inline-size: var(--w); block-size: var(--h); transform-style: preserve-3d; }
        .d3ob-face { position: absolute; inset: 0; backface-visibility: hidden; }
        .d3ob-front { background: #4f46e5; border-radius: .5rem; transform: translateZ(calc(var(--d) / 2));
                      color: #fff; display: grid; place-content: center; font: 700 1rem sans-serif; }
        .d3ob-back { background: #3730a3; border-radius: .5rem; transform: rotateY(180deg) translateZ(calc(var(--d) / 2)); }
        .d3ob-left, .d3ob-right { inline-size: var(--d); inset-inline-start: calc(50% - var(--d) / 2); background: #6366f1; }
        .d3ob-left { transform: translateX(calc(var(--w) / -2)) rotateY(-90deg); }
        .d3ob-right { transform: translateX(calc(var(--w) / 2)) rotateY(90deg); }
        .d3ob-floor { position: absolute; inline-size: var(--w); block-size: var(--d); inset-inline: 0;
                      inset-block-start: calc(50% - var(--d) / 2); background: #1e1b4b; backface-visibility: visible;
                      transform: translateY(calc(var(--h) / 2)) rotateX(90deg); }
        .d3ob-lid { position: absolute; inline-size: var(--w); block-size: var(--d); inset-inline: 0; inset-block-start: 0;
                    transform-origin: 50% 0; background: #4338ca; border-radius: .5rem .5rem 0 0;
                    transform: translateZ(calc(var(--d) / -2)) rotateX(90deg);
                    transition: transform .7s cubic-bezier(.34, 1.56, .64, 1); }
        .d3ob-wrap[data-open='true'] .d3ob-lid { transform: translateZ(calc(var(--d) / -2)) rotateX(200deg); }
        .d3ob-goods { position: absolute; inset: 0; display: grid; place-items: center; padding: .5rem;
                      background: #fff; border-radius: 1rem; color: #1c1917; font: 600 .7rem sans-serif;
                      transform: translateY(6%); transition: transform .7s cubic-bezier(.34, 1.56, .64, 1); }
        .d3ob-wrap[data-open='true'] .d3ob-goods { transform: translateY(-62%); }
        .d3ob-tagwrap { position: absolute; inset-inline-end: 9%; inset-block-end: 16%; perspective: 400px; pointer-events: none; }
        .d3ob-tag { position: relative; inline-size: 4.6rem; block-size: 3rem; transform-style: preserve-3d;
                    animation: d3ob-spin 7s linear infinite; }
        .d3ob-tface { position: absolute; inset: 0; display: grid; place-content: center; backface-visibility: hidden;
                      background: #fff; border: 1px solid #e7e5e4; border-radius: .5rem; font: 700 .8rem sans-serif; }
        .d3ob-tface:last-child { transform: rotateY(180deg); }
        .d3ob-stage[data-visible='false'] .d3ob-tag { animation-play-state: paused; }
        @keyframes d3ob-spin { to { transform: rotateY(360deg); } }
        @media (prefers-reduced-motion: reduce) {
          .d3ob-tilt, .d3ob-lid, .d3ob-goods { transition: none; }
          .d3ob-tag { animation: none; }
        }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- OpenBox.svelte — hinged-lid unboxing: hover or click opens, goods pop on a spring -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          let pinned = $state(false);
          let hovering = $state(false);
          let rx = $state(-14);
          let ry = $state(32);
          let stage: HTMLElement;
          let io: IntersectionObserver | null = null;

          const open = $derived(pinned || hovering);

          const onMove = (e: PointerEvent) => {
            const r = stage.getBoundingClientRect();
            ry = 32 + ((e.clientX - r.left) / r.width - 0.5) * 14;
            rx = -14 - ((e.clientY - r.top) / r.height - 0.5) * 10;
          };
          const untilt = () => { rx = -14; ry = 32; };
          const toggle = () => { pinned = !pinned; };

          // Park the spinning price tag while the box is off screen.
          onMount(() => {
            io = new IntersectionObserver((entries) => {
              stage.dataset.visible = entries[0].isIntersecting ? 'true' : 'false';
            });
            io.observe(stage);
            return () => io?.disconnect();
          });
        </script>

        <div class="d3ob-wrap" data-open={open}>
          <div bind:this={stage} class="d3ob-stage" tabindex="0" role="button"
               aria-expanded={open} aria-label="Halo Buds Pro box — hover or click to open"
               onpointerenter={() => (hovering = true)}
               onpointerleave={() => { hovering = false; untilt(); }}
               onpointermove={onMove} onclick={toggle}
               onkeydown={(e) => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggle(); } }}>
            <div class="d3ob-tilt" style={`transform: rotateX(${rx}deg) rotateY(${ry}deg)`}>
              <div class="d3ob-box">
                <div class="d3ob-face d3ob-front">HALO<small>BUDS PRO</small></div>
                <div class="d3ob-face d3ob-back"></div>
                <div class="d3ob-face d3ob-left"></div>
                <div class="d3ob-face d3ob-right"></div>
                <div class="d3ob-floor"></div>
                <div class="d3ob-goods">Halo Buds Pro</div>
                <div class="d3ob-lid"></div>
              </div>
            </div>
            <div class="d3ob-tagwrap">
              <div class="d3ob-tag">
                <div class="d3ob-tface">$149</div>
                <div class="d3ob-tface">$149</div>
              </div>
            </div>
          </div>
          <button type="button" aria-expanded={open} onclick={toggle}>
            {open ? 'Close the box' : 'Open the box'}
          </button>
        </div>

        <style>
        .d3ob-stage { inline-size: min(100%, 26rem); aspect-ratio: 4 / 3; display: grid; place-items: center;
                      perspective: 950px; overflow: clip; cursor: pointer; user-select: none; }
        .d3ob-tilt { transition: transform .45s ease-out; transform-style: preserve-3d; }
        .d3ob-box { --w: min(46vw, 10rem); --h: calc(var(--w) * .78); --d: calc(var(--w) * .52);
                    position: relative; inline-size: var(--w); block-size: var(--h); transform-style: preserve-3d; }
        .d3ob-face { position: absolute; inset: 0; backface-visibility: hidden; }
        .d3ob-front { background: #4f46e5; border-radius: .5rem; transform: translateZ(calc(var(--d) / 2));
                      color: #fff; display: grid; place-content: center; font: 700 1rem sans-serif; }
        .d3ob-back { background: #3730a3; border-radius: .5rem; transform: rotateY(180deg) translateZ(calc(var(--d) / 2)); }
        .d3ob-left, .d3ob-right { inline-size: var(--d); inset-inline-start: calc(50% - var(--d) / 2); background: #6366f1; }
        .d3ob-left { transform: translateX(calc(var(--w) / -2)) rotateY(-90deg); }
        .d3ob-right { transform: translateX(calc(var(--w) / 2)) rotateY(90deg); }
        .d3ob-floor { position: absolute; inline-size: var(--w); block-size: var(--d); inset-inline: 0;
                      inset-block-start: calc(50% - var(--d) / 2); background: #1e1b4b; backface-visibility: visible;
                      transform: translateY(calc(var(--h) / 2)) rotateX(90deg); }
        .d3ob-lid { position: absolute; inline-size: var(--w); block-size: var(--d); inset-inline: 0; inset-block-start: 0;
                    transform-origin: 50% 0; background: #4338ca; border-radius: .5rem .5rem 0 0;
                    transform: translateZ(calc(var(--d) / -2)) rotateX(90deg);
                    transition: transform .7s cubic-bezier(.34, 1.56, .64, 1); }
        .d3ob-wrap[data-open='true'] .d3ob-lid { transform: translateZ(calc(var(--d) / -2)) rotateX(200deg); }
        .d3ob-goods { position: absolute; inset: 0; display: grid; place-items: center; padding: .5rem;
                      background: #fff; border-radius: 1rem; color: #1c1917; font: 600 .7rem sans-serif;
                      transform: translateY(6%); transition: transform .7s cubic-bezier(.34, 1.56, .64, 1); }
        .d3ob-wrap[data-open='true'] .d3ob-goods { transform: translateY(-62%); }
        .d3ob-tagwrap { position: absolute; inset-inline-end: 9%; inset-block-end: 16%; perspective: 400px; pointer-events: none; }
        .d3ob-tag { position: relative; inline-size: 4.6rem; block-size: 3rem; transform-style: preserve-3d;
                    animation: d3ob-spin 7s linear infinite; }
        .d3ob-tface { position: absolute; inset: 0; display: grid; place-content: center; backface-visibility: hidden;
                      background: #fff; border: 1px solid #e7e5e4; border-radius: .5rem; font: 700 .8rem sans-serif; }
        .d3ob-tface:last-child { transform: rotateY(180deg); }
        .d3ob-stage[data-visible='false'] .d3ob-tag { animation-play-state: paused; }
        @keyframes d3ob-spin { to { transform: rotateY(360deg); } }
        @media (prefers-reduced-motion: reduce) {
          .d3ob-tilt, .d3ob-lid, .d3ob-goods { transition: none; }
          .d3ob-tag { animation: none; }
        }
        </style>
        SVELTE,
        ],
    ],
];
