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
];
