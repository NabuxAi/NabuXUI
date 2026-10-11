<?php

/**
 * Demo manifest of the "hover-fx" group — award-site hover effects: a chain
 * of image cards that chases the cursor, an eight-member link-hover family,
 * a glare card with layered parallax, a magnetic control set with live
 * strength/radius dials and an image accordion that answers hover, focus
 * and arrow keys. Scenarios live at
 * resources/views/demos/components/hover-fx/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'هاورها', 'en' => 'Hover FX'],

    'image-trail' => [
        'title' => ['fa' => 'دنبالهٔ تصویر', 'en' => 'Image trail'],
        'icon' => 'image',
        'oneLiner' => [
            'fa' => 'فهرست پروژه‌های یک استودیوی جوایزگیر: موس را روی نام‌ها یا نقطه‌های نشانه ببرید تا نه کارت تصویری به‌صورت زنجیره دنبال دست بیایند — هر کارت با lerp خودش عقب می‌ماند، زنجیره با حرکت باز می‌شود و با ایست دست، پشت سر هم محو می‌شوند؛ لمس و فوکوس زنجیره را دور لینک باز می‌کند.',
            'en' => 'An award-studio project index: sweep the pointer over the names or the marked hotspots and nine image cards give chase as a chain — each card lags one link behind its predecessor, the chain fans open while the hand moves and fades card by card when it stops; touch and focus fan it around the link.',
        ],
        'js' => true,
        'docs' => 'https://uiverse.io',
        'props' => [
            ['name' => 'lerp chain', 'type' => 'factor', 'default' => "'.30 − i × .022'", 'note' => [
                'fa' => 'هر کارت به کارتِ قبلی می‌چسبد نه به موس؛ ضرایب نزولی زنجیره را کش می‌آورند تا دنباله واقعی به نظر برسد.',
                'en' => 'Each card follows the previous card, never the pointer; the falling factors stretch the chain so it reads as a real trail.',
            ]],
            ['name' => 'pool', 'type' => 'count', 'default' => "'9'", 'note' => [
                'fa' => 'استخر ثابت ۹ کارتی بدون ساخت و تخریب DOM؛ صحنهٔ هر کارت از پروژهٔ فعال به بعد می‌چرخد.',
                'en' => 'A fixed pool of nine cards — no DOM churn; each card’s scene cycles on from the armed project.',
            ]],
            ['name' => 'idle', 'type' => 'ms', 'default' => "'140'", 'note' => [
                'fa' => 'بعد از ۱۴۰ میلی‌ثانیه بی‌حرکتی، کارت‌ها با تأخیر پلکانی ۵۵ms پشت سر هم خاموش می‌شوند.',
                'en' => 'After 140ms of stillness the cards fade one after another on a 55ms stair.',
            ]],
            ['name' => 'in-view', 'type' => 'observer', 'default' => "'IntersectionObserver'", 'note' => [
                'fa' => 'حلقهٔ RAF با IntersectionObserver خارج از دید پارک می‌شود و در destroy کامل بسته می‌شود.',
                'en' => 'The RAF loop parks off screen through an IntersectionObserver and is torn down on destroy.',
            ]],
            ['name' => 'reduced-motion', 'type' => 'media', 'default' => "'single static card'", 'note' => [
                'fa' => 'با حرکت کاهش‌یافته زنجیره حذف می‌شود؛ فوکوس یا لمس فقط یک کارت ثابت کنار لینک می‌نشیند.',
                'en' => 'Under reduced motion the chain is dropped; focus or touch parks one static card beside the link.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="hvt" x-data="{
                px: 0, py: 0, live: false, liveT: 0, raf: 0, io: null,
                mq: matchMedia('(prefers-reduced-motion: reduce)'),
                cards: Array.from({ length: 9 }, () => ({ x: 0, y: 0 })),
                move(e) {
                    const r = this.$refs.stage.getBoundingClientRect();
                    this.px = e.clientX - r.left; this.py = e.clientY - r.top;
                    this.live = true; this.liveT = performance.now(); this.run();
                },
                run() {
                    cancelAnimationFrame(this.raf);
                    if (this.mq.matches || !this.live) return;
                    const els = [...this.$refs.stage.querySelectorAll('.hvt-card')];
                    const loop = (now) => {
                        let tx = this.px, ty = this.py;
                        this.cards.forEach((c, i) => {
                            c.x += (tx - c.x) * (.3 - i * .022);
                            c.y += (ty - c.y) * (.3 - i * .022);
                            tx = c.x; ty = c.y;
                            const rot = Math.max(-16, Math.min(16, (this.px - c.x) * .9));
                            els[i].style.transform =
                                `translate3d(${c.x - 84}px, ${c.y - 56}px, 0) rotate(${rot}deg)`;
                        });
                        if (now - this.liveT > 140) { this.live = false; return; }
                        this.raf = requestAnimationFrame(loop);
                    };
                    this.raf = requestAnimationFrame(loop);
                },
                init() {
                    this.io = new IntersectionObserver((es) => { if (!es[0].isIntersecting) { this.live = false; cancelAnimationFrame(this.raf); } });
                    this.io.observe(this.$refs.stage);
                },
                destroy() { cancelAnimationFrame(this.raf); this.io?.disconnect(); },
            }" x-bind:data-live="live ? 'true' : 'false'">
            <div class="hvt-stage" x-ref="stage" x-on:pointermove="move($event)" x-on:pointerleave="live = false">
                <a class="hvt-link" href="#aurora" x-on:click.prevent>آرورا استودیو</a>
                @foreach (range(0, 8) as $i)
                    <div class="hvt-card" style="--i: {{ $i }}; opacity: 0">…svg scene…</div>
                @endforeach
            </div>
        </div>

        <style>
        .hvt-stage { position: relative; overflow: clip; block-size: 24rem; cursor: crosshair; }
        .hvt-card { position: absolute; inset-block-start: 0; inset-inline-start: 0;
                    inline-size: 10.5rem; aspect-ratio: 3 / 2; pointer-events: none;
                    opacity: 0; transition: opacity .4s ease; transition-delay: calc(var(--i) * 55ms); }
        .hvt[data-live='true'] .hvt-card { opacity: 1; }
        @media (prefers-reduced-motion: reduce) { .hvt-card { display: none; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="hvt" x-data="{
                px: 0, py: 0, live: false, liveT: 0, raf: 0, io: null,
                mq: matchMedia('(prefers-reduced-motion: reduce)'),
                cards: Array.from({ length: 9 }, () => ({ x: 0, y: 0 })),
                move(e) {
                    const r = this.$refs.stage.getBoundingClientRect();
                    this.px = e.clientX - r.left; this.py = e.clientY - r.top;
                    this.live = true; this.liveT = performance.now(); this.run();
                },
                run() {
                    cancelAnimationFrame(this.raf);
                    if (this.mq.matches || !this.live) return;
                    const els = [...this.$refs.stage.querySelectorAll('.hvt-card')];
                    const loop = (now) => {
                        let tx = this.px, ty = this.py;
                        this.cards.forEach((c, i) => {
                            c.x += (tx - c.x) * (.3 - i * .022);
                            c.y += (ty - c.y) * (.3 - i * .022);
                            tx = c.x; ty = c.y;
                            const rot = Math.max(-16, Math.min(16, (this.px - c.x) * .9));
                            els[i].style.transform =
                                `translate3d(${c.x - 84}px, ${c.y - 56}px, 0) rotate(${rot}deg)`;
                        });
                        if (now - this.liveT > 140) { this.live = false; return; }
                        this.raf = requestAnimationFrame(loop);
                    };
                    this.raf = requestAnimationFrame(loop);
                },
                init() {
                    this.io = new IntersectionObserver((es) => { if (!es[0].isIntersecting) { this.live = false; cancelAnimationFrame(this.raf); } });
                    this.io.observe(this.$refs.stage);
                },
                destroy() { cancelAnimationFrame(this.raf); this.io?.disconnect(); },
            }" x-bind:data-live="live ? 'true' : 'false'">
            <div class="hvt-stage" x-ref="stage" x-on:pointermove="move($event)" x-on:pointerleave="live = false">
                <a class="hvt-link" href="#aurora" x-on:click.prevent>Aurora Studio</a>
                @foreach (range(0, 8) as $i)
                    <div class="hvt-card" style="--i: {{ $i }}; opacity: 0">…svg scene…</div>
                @endforeach
            </div>
        </div>

        <style>
        .hvt-stage { position: relative; overflow: clip; block-size: 24rem; cursor: crosshair; }
        .hvt-card { position: absolute; inset-block-start: 0; inset-inline-start: 0;
                    inline-size: 10.5rem; aspect-ratio: 3 / 2; pointer-events: none;
                    opacity: 0; transition: opacity .4s ease; transition-delay: calc(var(--i) * 55ms); }
        .hvt[data-live='true'] .hvt-card { opacity: 1; }
        @media (prefers-reduced-motion: reduce) { .hvt-card { display: none; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/WorkIndex.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import ImageTrail from '@/components/ImageTrail';

        export default function WorkIndex() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <ImageTrail />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // ImageTrail.jsx — a chain of image cards chasing the cursor
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const POOL = 9;
        const PROJECTS = ['Aurora Studio', 'Nordic Atlas', 'Meridian Films', 'Drift Type', 'Solstice Bay'];

        export default function ImageTrail() {
          const stageRef = useRef(null);
          const cardRefs = useRef([]);
          const state = useRef({ px: 0, py: 0, cards: [], live: false, liveT: 0, raf: 0 });
          const [armed, setArmed] = useState(0);

          useEffect(() => {
            const s = state.current;
            s.cards = Array.from({ length: POOL }, () => ({ x: s.px, y: s.py }));
            const mq = matchMedia('(prefers-reduced-motion: reduce)');

            const apply = () => {
              let tx = s.px, ty = s.py;
              s.cards.forEach((c, i) => {
                c.x += (tx - c.x) * (.3 - i * .022);
                c.y += (ty - c.y) * (.3 - i * .022);
                tx = c.x; ty = c.y;
                const rot = Math.max(-16, Math.min(16, (s.px - c.x) * .9));
                const el = cardRefs.current[i];
                if (el) el.style.transform = `translate3d(${c.x - 84}px, ${c.y - 56}px, 0) rotate(${rot}deg)`;
              });
            };
            const loop = (now) => {
              apply();
              if (now - s.liveT > 140) { s.live = false; stageRef.current?.setAttribute('data-live', 'false'); return; }
              s.raf = requestAnimationFrame(loop);
            };
            const move = (e) => {
              const r = stageRef.current.getBoundingClientRect();
              s.px = e.clientX - r.left; s.py = e.clientY - r.top;
              if (!mq.matches && !s.live) { s.live = true; stageRef.current.setAttribute('data-live', 'true'); }
              s.liveT = performance.now();
              cancelAnimationFrame(s.raf); s.raf = requestAnimationFrame(loop);
            };
            const leave = () => { s.live = false; stageRef.current?.setAttribute('data-live', 'false'); cancelAnimationFrame(s.raf); };

            // Park the loop off screen — the observer only ever pauses it.
            const io = new IntersectionObserver(([entry]) => { if (!entry.isIntersecting) leave(); });
            io.observe(stageRef.current);
            stageRef.current.addEventListener('pointermove', move);
            stageRef.current.addEventListener('pointerleave', leave);
            return () => {
              io.disconnect(); cancelAnimationFrame(s.raf);
              stageRef.current?.removeEventListener('pointermove', move);
              stageRef.current?.removeEventListener('pointerleave', leave);
            };
          }, []);

          return (
            <>
              <style>{`
                .hvt-stage { position: relative; overflow: clip; min-block-size: 24rem; cursor: crosshair; }
                .hvt-card { position: absolute; inset-block-start: 0; inset-inline-start: 0; inline-size: 10.5rem;
                            aspect-ratio: 3 / 2; pointer-events: none; opacity: 0;
                            transition: opacity .4s ease; transition-delay: calc(var(--i) * 55ms); }
                .hvt-stage[data-live='true'] .hvt-card { opacity: 1; }
                @media (prefers-reduced-motion: reduce) { .hvt-card { display: none; } }
              `}</style>
              <div className="hvt-stage" ref={stageRef} data-live="false">
                {PROJECTS.map((p, i) => (
                  <a key={p} href={`#${p}`} onClick={(e) => e.preventDefault()}
                     onPointerEnter={() => setArmed(i)} onFocus={() => setArmed(i)}>{p}</a>
                ))}
                {Array.from({ length: POOL }, (_, i) => (
                  <div key={i} className="hvt-card" ref={(el) => { cardRefs.current[i] = el; }}
                       style={{ '--i': i }} data-scene={(armed + i) % PROJECTS.length} />
                ))}
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- ImageTrail.vue — a chain of image cards chasing the cursor -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const POOL = 9;
        const projects = ['Aurora Studio', 'Nordic Atlas', 'Meridian Films', 'Drift Type', 'Solstice Bay'];
        const stage = ref<HTMLElement | null>(null);
        const cardEls = ref<HTMLElement[]>([]);
        const armed = ref(0);
        const s = { px: 0, py: 0, cards: [] as { x: number; y: number }[], live: false, liveT: 0, raf: 0 };
        let io: IntersectionObserver | null = null;

        const apply = () => {
          let tx = s.px, ty = s.py;
          s.cards.forEach((c, i) => {
            c.x += (tx - c.x) * (.3 - i * .022);
            c.y += (ty - c.y) * (.3 - i * .022);
            tx = c.x; ty = c.y;
            const rot = Math.max(-16, Math.min(16, (s.px - c.x) * .9));
            cardEls.value[i] &&
              (cardEls.value[i].style.transform = `translate3d(${c.x - 84}px, ${c.y - 56}px, 0) rotate(${rot}deg)`);
          });
        };

        const setLive = (v: boolean) => { s.live = v; stage.value?.setAttribute('data-live', String(v)); };

        onMounted(() => {
          s.cards = Array.from({ length: POOL }, () => ({ x: s.px, y: s.py }));
          const mq = matchMedia('(prefers-reduced-motion: reduce)');
          const loop = (now: number) => {
            apply();
            if (now - s.liveT > 140) { setLive(false); return; }
            s.raf = requestAnimationFrame(loop);
          };
          const move = (e: PointerEvent) => {
            const r = stage.value!.getBoundingClientRect();
            s.px = e.clientX - r.left; s.py = e.clientY - r.top;
            if (!mq.matches && !s.live) setLive(true);
            s.liveT = performance.now();
            cancelAnimationFrame(s.raf); s.raf = requestAnimationFrame(loop);
          };
          const leave = () => { setLive(false); cancelAnimationFrame(s.raf); };
          io = new IntersectionObserver(([entry]) => { if (!entry.isIntersecting) leave(); });
          io.observe(stage.value!);
          stage.value!.addEventListener('pointermove', move);
          stage.value!.addEventListener('pointerleave', leave);
          onBeforeUnmount(() => {
            io?.disconnect(); cancelAnimationFrame(s.raf);
            stage.value?.removeEventListener('pointermove', move);
            stage.value?.removeEventListener('pointerleave', leave);
          });
        });
        </script>

        <template>
          <div ref="stage" class="hvt-stage" data-live="false">
            <a v-for="(p, i) in projects" :key="p" :href="`#${p}`" @click.prevent
               @pointerenter="armed = i" @focus="armed = i">{{ p }}</a>
            <div v-for="i in POOL" :key="i" class="hvt-card" :ref="(el) => { if (el) cardEls[i - 1] = el as HTMLElement; }"
                 :style="{ '--i': i - 1 }" :data-scene="(armed + i - 1) % projects.length" />
          </div>
        </template>

        <style scoped>
        .hvt-stage { position: relative; overflow: clip; min-block-size: 24rem; cursor: crosshair; }
        .hvt-card { position: absolute; inset-block-start: 0; inset-inline-start: 0; inline-size: 10.5rem;
                    aspect-ratio: 3 / 2; pointer-events: none; opacity: 0;
                    transition: opacity .4s ease; transition-delay: calc(var(--i) * 55ms); }
        .hvt-stage[data-live='true'] .hvt-card { opacity: 1; }
        @media (prefers-reduced-motion: reduce) { .hvt-card { display: none; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- ImageTrail.svelte — a chain of image cards chasing the cursor -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          const POOL = 9;
          const projects = ['Aurora Studio', 'Nordic Atlas', 'Meridian Films', 'Drift Type', 'Solstice Bay'];
          let stage: HTMLElement;
          let cardEls: HTMLElement[] = [];
          let armed = $state(0);
          const s = { px: 0, py: 0, cards: [] as { x: number; y: number }[], live: false, liveT: 0, raf: 0 };

          const apply = () => {
            let tx = s.px, ty = s.py;
            s.cards.forEach((c, i) => {
              c.x += (tx - c.x) * (.3 - i * .022);
              c.y += (ty - c.y) * (.3 - i * .022);
              tx = c.x; ty = c.y;
              const rot = Math.max(-16, Math.min(16, (s.px - c.x) * .9));
              if (cardEls[i]) cardEls[i].style.transform = `translate3d(${c.x - 84}px, ${c.y - 56}px, 0) rotate(${rot}deg)`;
            });
          };
          const setLive = (v: boolean) => { s.live = v; stage.setAttribute('data-live', String(v)); };

          onMount(() => {
            s.cards = Array.from({ length: POOL }, () => ({ x: s.px, y: s.py }));
            const mq = matchMedia('(prefers-reduced-motion: reduce)');
            const loop = (now: number) => {
              apply();
              if (now - s.liveT > 140) { setLive(false); return; }
              s.raf = requestAnimationFrame(loop);
            };
            const move = (e: PointerEvent) => {
              const r = stage.getBoundingClientRect();
              s.px = e.clientX - r.left; s.py = e.clientY - r.top;
              if (!mq.matches && !s.live) setLive(true);
              s.liveT = performance.now();
              cancelAnimationFrame(s.raf); s.raf = requestAnimationFrame(loop);
            };
            const leave = () => { setLive(false); cancelAnimationFrame(s.raf); };
            const io = new IntersectionObserver(([entry]) => { if (!entry.isIntersecting) leave(); });
            io.observe(stage);
            stage.addEventListener('pointermove', move);
            stage.addEventListener('pointerleave', leave);
            return () => {
              io.disconnect(); cancelAnimationFrame(s.raf);
              stage.removeEventListener('pointermove', move);
              stage.removeEventListener('pointerleave', leave);
            };
          });
        </script>

        <div bind:this={stage} class="hvt-stage" data-live="false">
          {#each projects as p, i (p)}
            <a href={`#${p}`} onclick={(e) => e.preventDefault()}
               onpointerenter={() => (armed = i)} onfocus={() => (armed = i)}>{p}</a>
          {/each}
          {#each Array(POOL) as _, i (i)}
            <div class="hvt-card" bind:this={cardEls[i]} style="--i: {i}"
                 data-scene={(armed + i) % projects.length} />
          {/each}
        </div>

        <style>
        .hvt-stage { position: relative; overflow: clip; min-block-size: 24rem; cursor: crosshair; }
        .hvt-card { position: absolute; inset-block-start: 0; inset-inline-start: 0; inline-size: 10.5rem;
                    aspect-ratio: 3 / 2; pointer-events: none; opacity: 0;
                    transition: opacity .4s ease; transition-delay: calc(var(--i) * 55ms); }
        .hvt-stage[data-live='true'] .hvt-card { opacity: 1; }
        @media (prefers-reduced-motion: reduce) { .hvt-card { display: none; } }
        </style>
        SVELTE,
        ],
    ],
];
