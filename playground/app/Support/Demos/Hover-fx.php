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

    'link-underline' => [
        'title' => ['fa' => 'خانوادهٔ هاور لینک', 'en' => 'Link hover family'],
        'icon' => 'external-link',
        'oneLiner' => [
            'fa' => 'هشت زبان هاور برای لینک‌های متنی، هرکدام با نام روی کاشی‌اش: زیرخط دوسویه از مرکز، براکت‌های کشیده‌شده، فلشی که جا باز می‌کند، غلتش دوسطحی متن، هایلایت مدادی، دایرهٔ فنری پشت متن، موج حروف (لاتین حرف‌به‌حرف، فارسی واژه‌به‌واژه) و آیکونی که از پنجره‌اش بیرون می‌جهد — همه با CSS خالص و فوکوس کیبورد دقیقاً هم‌ارزش هاور.',
            'en' => 'Eight hover languages for text links, each named on its tile: a centre-out underline, drawn brackets, an arrow that makes room, a two-layer text roll, a marker highlight, a springing circle, a letter wave (letter by letter for Latin, word by word for Persian) and an icon leaping through its clipped window — all pure CSS, with keyboard focus treated exactly like hover.',
        ],
        'js' => false,
        'docs' => 'https://uiverse.io',
        'props' => [
            ['name' => 'variants', 'type' => 'list', 'default' => "'8'", 'note' => [
                'fa' => 'زیرخط دوسویه، براکت، فلش لغزنده، تعویض متن، هایلایت مدادی، دایرهٔ پرشونده، موج حروف، آیکون جابه‌جا — یک دمو، هشت رفتار.',
                'en' => 'Centre underline, brackets, sliding arrow, text swap, marker highlight, bouncing circle, letter wave, shifting icon — one demo, eight behaviours.',
            ]],
            ['name' => ':focus-visible', 'type' => 'selector', 'default' => "'= :hover'", 'note' => [
                'fa' => 'هر قاعدهٔ هاور هم‌زمان روی فوکوس کیبورد می‌نشیند؛ در لمس، تپ معادل حالت نهایی است.',
                'en' => 'Every hover rule lands on keyboard focus at the same time; on touch, a tap is the equivalent final state.',
            ]],
            ['name' => 'RTL', 'type' => 'logical', 'default' => "'inset-inline · translate'", 'note' => [
                'fa' => 'همهٔ جهت‌ها روی ویژگی‌های منطقی سوارند؛ فقط چند مورد پرشی RTL با html[dir=rtl] تصحیح می‌شود.',
                'en' => 'All directions ride logical properties; only a few translate jumps are corrected for html[dir=rtl].',
            ]],
            ['name' => 'wave granularity', 'type' => 'script', 'default' => "'Latin letter · fa word'", 'note' => [
                'fa' => 'شکستن متن برای موج، لاتین را حرف‌به‌حرف و فارسی را واژه‌به‌واژه می‌شکند تا خط پیوستهٔ فارسی نشکند؛ تاخیر هر گام ۳۲ms.',
                'en' => 'The wave splits Latin letter by letter but Persian word by word so the joined script never breaks; each step is delayed 32ms.',
            ]],
            ['name' => 'reduced-motion', 'type' => 'media', 'default' => "'still states'", 'note' => [
                'fa' => 'با حرکت کاهش‌یافته همهٔ انتقال‌ها خاموش و هر هشت افکت مستقیم به حالت نهایی می‌نشیند.',
                'en' => 'Under reduced motion every transition is off and all eight effects sit directly in their final state.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        {{-- خانوادهٔ هاور لینک — CSS خالص؛ فوکوس هم‌ارزش هاور --}}
        <nav class="hlu" aria-label="فهرست سرویس">
            <a class="hlu-a hlu-center" href="#pricing">قیمت‌گذاری</a>
            <a class="hlu-a hlu-swap" href="#careers">
                <span class="hlu-swap-t" data-text="فرصت‌های شغلی">
                    <span class="hlu-swap-a">فرصت‌های شغلی</span>
                </span>
            </a>
            {{-- موج: فارسی در سطح واژه تا خط پیوسته نشکند --}}
            <a class="hlu-a hlu-wave" href="#support" aria-label="پشتیبانی">
                @foreach (explode(' ', 'پشتیبانی') as $i => $word)
                    <span class="hlu-w" style="--i: {{ $i }}">{{ $word }}</span>
                @endforeach
            </a>
        </nav>

        <style>
        .hlu-a { position: relative; display: inline-flex; color: inherit; text-decoration: none;
                 font-weight: 600; transition: color .25s ease; outline: none; }
        .hlu-a:hover, .hlu-a:focus-visible { color: #4f46e5; }
        .hlu-center::after { content: ''; position: absolute; inset-inline: 0; inset-block-end: -.34em;
                 block-size: 2px; background: currentColor; transform: scaleX(0);
                 transition: transform .28s cubic-bezier(.22, .9, .3, 1); }
        .hlu-center:hover::after, .hlu-center:focus-visible::after { transform: scaleX(1); }
        .hlu-swap-t { position: relative; display: block; overflow: clip; block-size: 1.35em; }
        .hlu-swap-a { display: block; transition: translate .32s cubic-bezier(.3, .8, .3, 1); }
        .hlu-swap-t::after { content: attr(data-text); position: absolute; inset: 0;
                 translate: 0 105%; transition: translate .32s cubic-bezier(.3, .8, .3, 1); }
        .hlu-a:hover .hlu-swap-a { translate: 0 -105%; }
        .hlu-a:hover .hlu-swap-t::after { translate: 0 0; }
        .hlu-w { display: inline-block; transition: translate .32s cubic-bezier(.3, 1.4, .5, 1);
                 transition-delay: calc(var(--i) * 32ms); }
        .hlu-a:hover .hlu-w { translate: 0 -.3em; }
        @media (prefers-reduced-motion: reduce) {
            .hlu-a *, .hlu-a::before, .hlu-a::after { transition: none !important;
                 translate: 0 0 !important; transform: none !important; }
        }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        {{-- Link hover family — pure CSS; focus mirrors hover --}}
        <nav class="hlu" aria-label="Service menu">
            <a class="hlu-a hlu-center" href="#pricing">Pricing</a>
            <a class="hlu-a hlu-swap" href="#careers">
                <span class="hlu-swap-t" data-text="Careers">
                    <span class="hlu-swap-a">Careers</span>
                </span>
            </a>
            {{-- wave: Latin splits letter by letter --}}
            <a class="hlu-a hlu-wave" href="#support" aria-label="Support">
                @foreach (str_split('Support') as $i => $letter)
                    <span class="hlu-w" style="--i: {{ $i }}">{{ $letter }}</span>
                @endforeach
            </a>
        </nav>

        <style>
        .hlu-a { position: relative; display: inline-flex; color: inherit; text-decoration: none;
                 font-weight: 600; transition: color .25s ease; outline: none; }
        .hlu-a:hover, .hlu-a:focus-visible { color: #4f46e5; }
        .hlu-center::after { content: ''; position: absolute; inset-inline: 0; inset-block-end: -.34em;
                 block-size: 2px; background: currentColor; transform: scaleX(0);
                 transition: transform .28s cubic-bezier(.22, .9, .3, 1); }
        .hlu-center:hover::after, .hlu-center:focus-visible::after { transform: scaleX(1); }
        .hlu-swap-t { position: relative; display: block; overflow: clip; block-size: 1.35em; }
        .hlu-swap-a { display: block; transition: translate .32s cubic-bezier(.3, .8, .3, 1); }
        .hlu-swap-t::after { content: attr(data-text); position: absolute; inset: 0;
                 translate: 0 105%; transition: translate .32s cubic-bezier(.3, .8, .3, 1); }
        .hlu-a:hover .hlu-swap-a { translate: 0 -105%; }
        .hlu-a:hover .hlu-swap-t::after { translate: 0 0; }
        .hlu-w { display: inline-block; transition: translate .32s cubic-bezier(.3, 1.4, .5, 1);
                 transition-delay: calc(var(--i) * 32ms); }
        .hlu-a:hover .hlu-w { translate: 0 -.3em; }
        @media (prefers-reduced-motion: reduce) {
            .hlu-a *, .hlu-a::before, .hlu-a::after { transition: none !important;
                 translate: 0 0 !important; transform: none !important; }
        }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/FooterNav.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import LinkFamily from '@/components/LinkFamily';

        export default function FooterNav() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <footer className="nx-footer">
                <LinkFamily />
              </footer>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // LinkFamily.jsx — eight link-hover languages, pure CSS + one split
        import '@nabuxai/ui-core/css';

        const LINKS = ['Pricing', 'Docs', 'Changelog', 'Careers', 'Blog', 'Community', 'Support', 'Status'];

        // Letter split for Latin only — a joined script must stay word-level.
        const Wave = ({ text }) => (
          <span>
            {text.split(' ').map((word, wi) =>
              word.split('').map((ch, li) => (
                <span key={`${wi}-${li}`} className="lf-w" style={{ '--i': wi * 4 + li }}>{ch}</span>
              )),
            )}
          </span>
        );

        export default function LinkFamily() {
          return (
            <>
              <style>{`
                .lf-a { position: relative; display: inline-flex; color: inherit; text-decoration: none;
                        font-weight: 600; transition: color .25s ease; outline: none; }
                .lf-a:hover, .lf-a:focus-visible { color: #4f46e5; }
                .lf-a.center::after { content: ''; position: absolute; inset-inline: 0; inset-block-end: -.34em;
                        block-size: 2px; background: currentColor; transform: scaleX(0);
                        transition: transform .28s cubic-bezier(.22, .9, .3, 1); }
                .lf-a.center:hover::after, .lf-a.center:focus-visible::after { transform: scaleX(1); }
                .lf-w { display: inline-block; transition: translate .32s cubic-bezier(.3, 1.4, .5, 1);
                        transition-delay: calc(var(--i) * 32ms); }
                .lf-a:hover .lf-w, .lf-a:focus-visible .lf-w { translate: 0 -.3em; }
                @media (prefers-reduced-motion: reduce) {
                  .lf-a *, .lf-a::after { transition: none !important; translate: 0 0 !important; transform: none !important; }
                }
              `}</style>
              <nav className="lf" aria-label="Service menu">
                <a className="lf-a center" href="#pricing">{LINKS[0]}</a>
                <a className="lf-a" href="#support" aria-label={LINKS[6]}><Wave text={LINKS[6]} /></a>
              </nav>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- LinkFamily.vue — eight link-hover languages, pure CSS + one split -->
        <script setup lang="ts">
        import '@nabuxai/ui-core/css';
        const links = ['Pricing', 'Docs', 'Changelog', 'Careers', 'Blog', 'Community', 'Support', 'Status'];
        const chars = (word: string) => word.split('');
        </script>

        <template>
          <nav class="lf" aria-label="Service menu">
            <a class="lf-a center" href="#pricing">{{ links[0] }}</a>
            <!-- letter split for Latin only — a joined script stays word-level -->
            <a class="lf-a" href="#support" aria-label="Support">
              <span v-for="(ch, i) in chars(links[6])" :key="i" class="lf-w" :style="{ '--i': i }">{{ ch }}</span>
            </a>
          </nav>
        </template>

        <style scoped>
        .lf-a { position: relative; display: inline-flex; color: inherit; text-decoration: none;
                font-weight: 600; transition: color .25s ease; outline: none; }
        .lf-a:hover, .lf-a:focus-visible { color: #4f46e5; }
        .lf-a.center::after { content: ''; position: absolute; inset-inline: 0; inset-block-end: -.34em;
                block-size: 2px; background: currentColor; transform: scaleX(0);
                transition: transform .28s cubic-bezier(.22, .9, .3, 1); }
        .lf-a.center:hover::after, .lf-a.center:focus-visible::after { transform: scaleX(1); }
        .lf-w { display: inline-block; transition: translate .32s cubic-bezier(.3, 1.4, .5, 1);
                transition-delay: calc(var(--i) * 32ms); }
        .lf-a:hover .lf-w, .lf-a:focus-visible .lf-w { translate: 0 -.3em; }
        @media (prefers-reduced-motion: reduce) {
          .lf-a *, .lf-a::after { transition: none !important; translate: 0 0 !important; transform: none !important; }
        }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- LinkFamily.svelte — eight link-hover languages, pure CSS + one split -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';
          const links = ['Pricing', 'Docs', 'Changelog', 'Careers', 'Blog', 'Community', 'Support', 'Status'];
          const chars = (word: string) => word.split('');
        </script>

        <nav class="lf" aria-label="Service menu">
          <a class="lf-a center" href="#pricing">{links[0]}</a>
          <!-- letter split for Latin only — a joined script stays word-level -->
          <a class="lf-a" href="#support" aria-label="Support">
            {#each chars(links[6]) as ch, i (i)}
              <span class="lf-w" style="--i: {i}">{ch}</span>
            {/each}
          </a>
        </nav>

        <style>
        .lf-a { position: relative; display: inline-flex; color: inherit; text-decoration: none;
                font-weight: 600; transition: color .25s ease; outline: none; }
        .lf-a:hover, .lf-a:focus-visible { color: #4f46e5; }
        .lf-a.center::after { content: ''; position: absolute; inset-inline: 0; inset-block-end: -.34em;
                block-size: 2px; background: currentColor; transform: scaleX(0);
                transition: transform .28s cubic-bezier(.22, .9, .3, 1); }
        .lf-a.center:hover::after, .lf-a.center:focus-visible::after { transform: scaleX(1); }
        .lf-w { display: inline-block; transition: translate .32s cubic-bezier(.3, 1.4, .5, 1);
                transition-delay: calc(var(--i) * 32ms); }
        .lf-a:hover .lf-w, .lf-a:focus-visible .lf-w { translate: 0 -.3em; }
        @media (prefers-reduced-motion: reduce) {
          .lf-a *, .lf-a::after { transition: none !important; translate: 0 0 !important; transform: none !important; }
        }
        </style>
        SVELTE,
        ],
    ],

    'card-glare' => [
        'title' => ['fa' => 'کارت با گلیر', 'en' => 'Glare card'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'اسلب کارت اعتباری/بلیط با هالهٔ glare که زاویه‌اش از atan2 موقعیت موس حول مرکز می‌آید و سه لایهٔ عمق — صحنهٔ چاپی، برچسب حک‌شده و دکمه — هرکدام به پارالکس متفاوتی به سمت دست می‌لغزند؛ حلقهٔ lerp فقط روی کارت یا هنگام بازگشت می‌چرخد، فوکوس کارت را در زاویهٔ نشسته می‌کند و تپ، گلیر را یک بار جارو می‌کند.',
            'en' => 'A credit-card/ticket slab whose glare halo takes its angle from atan2 of the pointer around the centre, while three depth layers — the printed scene, the embossed label and the button — each drift a different parallax toward the hand; the lerp loop only spins over the card or while settling back, focus tilts the card into a posed angle and a tap sweeps the glare across once.',
        ],
        'js' => true,
        'docs' => 'https://uiverse.io',
        'props' => [
            ['name' => 'glare angle', 'type' => 'deg', 'default' => "'atan2(px, py) + 90'", 'note' => [
                'fa' => 'نوار نور با زاویهٔ لحظه‌ای موس حول مرکز کارت می‌چرخد؛ هالهٔ مرکزی هم به درصد مکان موس قفل است.',
                'en' => 'The light streak rotates with the pointer’s live angle around the card centre; the central halo is pinned to the pointer position as a percentage.',
            ]],
            ['name' => 'depth layers', 'type' => 'px', 'default' => "'10 · 22 · 34'", 'note' => [
                'fa' => 'صحنه ۱۰px، برچسب ۲۲px و دکمه ۳۴px به سمت موس می‌لغزند؛ اختلاف فاصله‌ها حس عمق می‌سازد.',
                'en' => 'Scene 10px, label 22px and button 34px drift toward the pointer; the distance gap is what sells the depth.',
            ]],
            ['name' => 'tilt', 'type' => 'deg', 'default' => "'10° × 12° @ 950px'", 'note' => [
                'fa' => 'شیب کارت از موقعیت نرمال‌شدهٔ موس؛ پرسپکتیو ۹۵۰px حس فیزیکی اسلب را می‌دهد.',
                'en' => 'The slab tilt comes from the normalised pointer position; the 950px perspective gives it physical presence.',
            ]],
            ['name' => 'loop', 'type' => 'raf', 'default' => "'lerp .16'", 'note' => [
                'fa' => 'حلقهٔ RAF فقط وقتی موس روی کارت است یا مقادیر در حال بازگشت‌اند اجرا می‌شود؛ خارج از دید با IntersectionObserver پارک می‌شود.',
                'en' => 'The RAF loop runs only while the pointer is over the card or values are settling; it parks off screen through an IntersectionObserver.',
            ]],
            ['name' => 'reduced-motion', 'type' => 'media', 'default' => "'fixed sheen'", 'note' => [
                'fa' => 'با حرکت کاهش‌یافته شیب و پارالکس خاموش و یک برق نرم ثابت روی کارت می‌نشیند.',
                'en' => 'Under reduced motion tilt and parallax go off and one fixed soft sheen sits on the card.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        {{-- کارت با گلیر — زاویه از atan2 موس، سه لایهٔ پارالکس --}}
        <div class="cgl" x-data="{
                raf: 0, live: false, tx: .5, ty: .5, cx: .5, cy: .5,
                move(e) {
                    const r = this.$refs.card.getBoundingClientRect();
                    this.tx = (e.clientX - r.left) / r.width;
                    this.ty = (e.clientY - r.top) / r.height;
                    this.live = true; this.run();
                },
                leave() { this.tx = .5; this.ty = .5; this.live = false; this.run(); },
                run() {
                    cancelAnimationFrame(this.raf);
                    const loop = () => {
                        this.cx += (this.tx - this.cx) * .16; this.cy += (this.ty - this.cy) * .16;
                        this.apply();
                        if (!this.live && Math.abs(this.tx - this.cx) + Math.abs(this.ty - this.cy) < .002) return;
                        this.raf = requestAnimationFrame(loop);
                    };
                    this.raf = requestAnimationFrame(loop);
                },
                apply() {
                    const el = this.$refs.card, px = this.cx - .5, py = this.cy - .5;
                    el.style.setProperty('--cgl-rx', py * -10 + 'deg');
                    el.style.setProperty('--cgl-ry', px * 12 + 'deg');
                    el.style.setProperty('--cgl-px', px); el.style.setProperty('--cgl-py', py);
                    el.style.setProperty('--cgl-mx', this.cx * 100 + '%');
                    el.style.setProperty('--cgl-my', this.cy * 100 + '%');
                    el.style.setProperty('--cgl-rot', Math.atan2(py, px) * 57.3 + 90 + 'deg');
                },
                destroy() { cancelAnimationFrame(this.raf); },
            }">
            <div class="cgl-card" x-ref="card" tabindex="0"
                 x-on:pointermove="move($event)" x-on:pointerleave="leave()"
                 x-on:focus="tx = .68; ty = .32; live = true; run()" x-on:blur="leave()">
                <div class="cgl-art">…صحنه…</div>
                <div class="cgl-glare" aria-hidden="true"></div>
                <div class="cgl-meta">بانک مریدین · هورایزن</div>
                <button type="button" class="cgl-btn">انجماد</button>
            </div>
        </div>

        <style>
        .cgl-card { position: relative; aspect-ratio: 1.62; border-radius: 1.25rem; overflow: clip;
                transform: perspective(950px) rotateX(var(--cgl-rx, 0deg)) rotateY(var(--cgl-ry, 0deg)); }
        .cgl-glare { position: absolute; inset: 0; opacity: var(--cgl-g, 0); mix-blend-mode: soft-light;
                background: radial-gradient(46% 62% at var(--cgl-mx, 50%) var(--cgl-my, 42%),
                rgba(255,255,255,.38), transparent 72%); }
        .cgl-glare::before { content: ''; position: absolute; inset: -45%; filter: blur(2px);
                background: linear-gradient(90deg, transparent 26%, rgba(255,255,255,.5) 48%, transparent 78%);
                transform: rotate(var(--cgl-rot, 105deg)); }
        .cgl-meta { transform: translate3d(calc(var(--cgl-px, 0) * 22px), calc(var(--cgl-py, 0) * 22px), 0); }
        .cgl-btn { transform: translate3d(calc(var(--cgl-px, 0) * 34px), calc(var(--cgl-py, 0) * 34px), 0); }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        {{-- Glare card — angle from atan2 of the pointer, three parallax layers --}}
        <div class="cgl" x-data="{
                raf: 0, live: false, tx: .5, ty: .5, cx: .5, cy: .5,
                move(e) {
                    const r = this.$refs.card.getBoundingClientRect();
                    this.tx = (e.clientX - r.left) / r.width;
                    this.ty = (e.clientY - r.top) / r.height;
                    this.live = true; this.run();
                },
                leave() { this.tx = .5; this.ty = .5; this.live = false; this.run(); },
                run() {
                    cancelAnimationFrame(this.raf);
                    const loop = () => {
                        this.cx += (this.tx - this.cx) * .16; this.cy += (this.ty - this.cy) * .16;
                        this.apply();
                        if (!this.live && Math.abs(this.tx - this.cx) + Math.abs(this.ty - this.cy) < .002) return;
                        this.raf = requestAnimationFrame(loop);
                    };
                    this.raf = requestAnimationFrame(loop);
                },
                apply() {
                    const el = this.$refs.card, px = this.cx - .5, py = this.cy - .5;
                    el.style.setProperty('--cgl-rx', py * -10 + 'deg');
                    el.style.setProperty('--cgl-ry', px * 12 + 'deg');
                    el.style.setProperty('--cgl-px', px); el.style.setProperty('--cgl-py', py);
                    el.style.setProperty('--cgl-mx', this.cx * 100 + '%');
                    el.style.setProperty('--cgl-my', this.cy * 100 + '%');
                    el.style.setProperty('--cgl-rot', Math.atan2(py, px) * 57.3 + 90 + 'deg');
                },
                destroy() { cancelAnimationFrame(this.raf); },
            }">
            <div class="cgl-card" x-ref="card" tabindex="0"
                 x-on:pointermove="move($event)" x-on:pointerleave="leave()"
                 x-on:focus="tx = .68; ty = .32; live = true; run()" x-on:blur="leave()">
                <div class="cgl-art">…scene…</div>
                <div class="cgl-glare" aria-hidden="true"></div>
                <div class="cgl-meta">Meridian Bank · Horizon</div>
                <button type="button" class="cgl-btn">Freeze</button>
            </div>
        </div>

        <style>
        .cgl-card { position: relative; aspect-ratio: 1.62; border-radius: 1.25rem; overflow: clip;
                transform: perspective(950px) rotateX(var(--cgl-rx, 0deg)) rotateY(var(--cgl-ry, 0deg)); }
        .cgl-glare { position: absolute; inset: 0; opacity: var(--cgl-g, 0); mix-blend-mode: soft-light;
                background: radial-gradient(46% 62% at var(--cgl-mx, 50%) var(--cgl-my, 42%),
                rgba(255,255,255,.38), transparent 72%); }
        .cgl-glare::before { content: ''; position: absolute; inset: -45%; filter: blur(2px);
                background: linear-gradient(90deg, transparent 26%, rgba(255,255,255,.5) 48%, transparent 78%);
                transform: rotate(var(--cgl-rot, 105deg)); }
        .cgl-meta { transform: translate3d(calc(var(--cgl-px, 0) * 22px), calc(var(--cgl-py, 0) * 22px), 0); }
        .cgl-btn { transform: translate3d(calc(var(--cgl-px, 0) * 34px), calc(var(--cgl-py, 0) * 34px), 0); }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/CardSpotlight.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import GlareCard from '@/components/GlareCard';

        export default function CardSpotlight() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <GlareCard />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // GlareCard.jsx — glare angle from atan2, three parallax depth layers
        import { useEffect, useRef } from 'react';
        import '@nabuxai/ui-core/css';

        export default function GlareCard() {
          const cardRef = useRef(null);
          const s = useRef({ tx: .5, ty: .5, cx: .5, cy: .5, live: false, raf: 0 });

          useEffect(() => {
            const apply = () => {
              const el = cardRef.current; if (!el) return;
              const { cx, cy } = s.current, px = cx - .5, py = cy - .5;
              el.style.setProperty('--cgl-rx', py * -10 + 'deg');
              el.style.setProperty('--cgl-ry', px * 12 + 'deg');
              el.style.setProperty('--cgl-px', px); el.style.setProperty('--cgl-py', py);
              el.style.setProperty('--cgl-mx', cx * 100 + '%'); el.style.setProperty('--cgl-my', cy * 100 + '%');
              el.style.setProperty('--cgl-rot', Math.atan2(py, px) * 57.3 + 90 + 'deg');
            };
            const loop = () => {
              const st = s.current;
              st.cx += (st.tx - st.cx) * .16; st.cy += (st.ty - st.cy) * .16;
              apply();
              const settling = Math.abs(st.tx - st.cx) + Math.abs(st.ty - st.cy) > .002;
              if (!st.live && !settling) return;
              st.raf = requestAnimationFrame(loop);
            };
            const kick = () => { cancelAnimationFrame(s.current.raf); s.current.raf = requestAnimationFrame(loop); };
            const move = (e) => {
              const r = cardRef.current.getBoundingClientRect();
              const st = s.current;
              st.tx = (e.clientX - r.left) / r.width; st.ty = (e.clientY - r.top) / r.height;
              st.live = true; kick();
            };
            const leave = () => { const st = s.current; st.tx = .5; st.ty = .5; st.live = false; kick(); };
            const el = cardRef.current;
            el.addEventListener('pointermove', move);
            el.addEventListener('pointerleave', leave);
            return () => {
              cancelAnimationFrame(s.current.raf);
              el.removeEventListener('pointermove', move);
              el.removeEventListener('pointerleave', leave);
            };
          }, []);

          return (
            <>
              <style>{`
                .cgl-card { position: relative; aspect-ratio: 1.62; border-radius: 1.25rem; overflow: clip;
                        transform: perspective(950px) rotateX(var(--cgl-rx, 0deg)) rotateY(var(--cgl-ry, 0deg)); }
                .cgl-glare { position: absolute; inset: 0; opacity: var(--cgl-g, 0); mix-blend-mode: soft-light;
                        background: radial-gradient(46% 62% at var(--cgl-mx, 50%) var(--cgl-my, 42%),
                        rgba(255,255,255,.38), transparent 72%); }
                .cgl-glare::before { content: ''; position: absolute; inset: -45%; filter: blur(2px);
                        background: linear-gradient(90deg, transparent 26%, rgba(255,255,255,.5) 48%, transparent 78%);
                        transform: rotate(var(--cgl-rot, 105deg)); }
                .cgl-meta { transform: translate3d(calc(var(--cgl-px, 0) * 22px), calc(var(--cgl-py, 0) * 22px), 0); }
                .cgl-btn { transform: translate3d(calc(var(--cgl-px, 0) * 34px), calc(var(--cgl-py, 0) * 34px), 0); }
              `}</style>
              <div className="cgl-card" ref={cardRef} tabIndex={0}
                   onPointerMove={move} onPointerLeave={leave}>
                <div className="cgl-glare" aria-hidden="true" />
                <div className="cgl-meta">Meridian Bank · Horizon</div>
                <button type="button" className="cgl-btn">Freeze</button>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- GlareCard.vue — glare angle from atan2, three parallax depth layers -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const card = ref<HTMLElement | null>(null);
        const s = { tx: .5, ty: .5, cx: .5, cy: .5, live: false, raf: 0 };

        const apply = () => {
          const el = card.value; if (!el) return;
          const px = s.cx - .5, py = s.cy - .5;
          el.style.setProperty('--cgl-rx', py * -10 + 'deg');
          el.style.setProperty('--cgl-ry', px * 12 + 'deg');
          el.style.setProperty('--cgl-px', String(px)); el.style.setProperty('--cgl-py', String(py));
          el.style.setProperty('--cgl-mx', s.cx * 100 + '%'); el.style.setProperty('--cgl-my', s.cy * 100 + '%');
          el.style.setProperty('--cgl-rot', Math.atan2(py, px) * 57.3 + 90 + 'deg');
        };
        const loop = () => {
          s.cx += (s.tx - s.cx) * .16; s.cy += (s.ty - s.cy) * .16;
          apply();
          const settling = Math.abs(s.tx - s.cx) + Math.abs(s.ty - s.cy) > .002;
          if (!s.live && !settling) return;
          s.raf = requestAnimationFrame(loop);
        };
        const kick = () => { cancelAnimationFrame(s.raf); s.raf = requestAnimationFrame(loop); };
        const move = (e: PointerEvent) => {
          const r = card.value!.getBoundingClientRect();
          s.tx = (e.clientX - r.left) / r.width; s.ty = (e.clientY - r.top) / r.height;
          s.live = true; kick();
        };
        const leave = () => { s.tx = .5; s.ty = .5; s.live = false; kick(); };
        let el: HTMLElement | null = null;

        onMounted(() => {
          el = card.value;
          el!.addEventListener('pointermove', move);
          el!.addEventListener('pointerleave', leave);
        });
        onBeforeUnmount(() => {
          cancelAnimationFrame(s.raf);
          el?.removeEventListener('pointermove', move);
          el?.removeEventListener('pointerleave', leave);
        });
        </script>

        <template>
          <div ref="card" class="cgl-card" tabindex="0" @pointermove="move" @pointerleave="leave">
            <div class="cgl-glare" aria-hidden="true" />
            <div class="cgl-meta">Meridian Bank · Horizon</div>
            <button type="button" class="cgl-btn">Freeze</button>
          </div>
        </template>

        <style scoped>
        .cgl-card { position: relative; aspect-ratio: 1.62; border-radius: 1.25rem; overflow: clip;
                transform: perspective(950px) rotateX(var(--cgl-rx, 0deg)) rotateY(var(--cgl-ry, 0deg)); }
        .cgl-glare { position: absolute; inset: 0; opacity: var(--cgl-g, 0); mix-blend-mode: soft-light;
                background: radial-gradient(46% 62% at var(--cgl-mx, 50%) var(--cgl-my, 42%),
                rgba(255,255,255,.38), transparent 72%); }
        .cgl-glare::before { content: ''; position: absolute; inset: -45%; filter: blur(2px);
                background: linear-gradient(90deg, transparent 26%, rgba(255,255,255,.5) 48%, transparent 78%);
                transform: rotate(var(--cgl-rot, 105deg)); }
        .cgl-meta { transform: translate3d(calc(var(--cgl-px, 0) * 22px), calc(var(--cgl-py, 0) * 22px), 0); }
        .cgl-btn { transform: translate3d(calc(var(--cgl-px, 0) * 34px), calc(var(--cgl-py, 0) * 34px), 0); }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- GlareCard.svelte — glare angle from atan2, three parallax depth layers -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          let card: HTMLElement;
          const s = { tx: .5, ty: .5, cx: .5, cy: .5, live: false, raf: 0 };

          const apply = () => {
            const px = s.cx - .5, py = s.cy - .5;
            card.style.setProperty('--cgl-rx', py * -10 + 'deg');
            card.style.setProperty('--cgl-ry', px * 12 + 'deg');
            card.style.setProperty('--cgl-px', String(px)); card.style.setProperty('--cgl-py', String(py));
            card.style.setProperty('--cgl-mx', s.cx * 100 + '%'); card.style.setProperty('--cgl-my', s.cy * 100 + '%');
            card.style.setProperty('--cgl-rot', Math.atan2(py, px) * 57.3 + 90 + 'deg');
          };
          const loop = () => {
            s.cx += (s.tx - s.cx) * .16; s.cy += (s.ty - s.cy) * .16;
            apply();
            const settling = Math.abs(s.tx - s.cx) + Math.abs(s.ty - s.cy) > .002;
            if (!s.live && !settling) return;
            s.raf = requestAnimationFrame(loop);
          };
          const kick = () => { cancelAnimationFrame(s.raf); s.raf = requestAnimationFrame(loop); };
          const move = (e: PointerEvent) => {
            const r = card.getBoundingClientRect();
            s.tx = (e.clientX - r.left) / r.width; s.ty = (e.clientY - r.top) / r.height;
            s.live = true; kick();
          };
          const leave = () => { s.tx = .5; s.ty = .5; s.live = false; kick(); };

          onMount(() => {
            card.addEventListener('pointermove', move);
            card.addEventListener('pointerleave', leave);
            return () => {
              cancelAnimationFrame(s.raf);
              card.removeEventListener('pointermove', move);
              card.removeEventListener('pointerleave', leave);
            };
          });
        </script>

        <div bind:this={card} class="cgl-card" tabindex="0"
             onpointermove={move} onpointerleave={leave}>
          <div class="cgl-glare" aria-hidden="true" />
          <div class="cgl-meta">Meridian Bank · Horizon</div>
          <button type="button" class="cgl-btn">Freeze</button>
        </div>

        <style>
        .cgl-card { position: relative; aspect-ratio: 1.62; border-radius: 1.25rem; overflow: clip;
                transform: perspective(950px) rotateX(var(--cgl-rx, 0deg)) rotateY(var(--cgl-ry, 0deg)); }
        .cgl-glare { position: absolute; inset: 0; opacity: var(--cgl-g, 0); mix-blend-mode: soft-light;
                background: radial-gradient(46% 62% at var(--cgl-mx, 50%) var(--cgl-my, 42%),
                rgba(255,255,255,.38), transparent 72%); }
        .cgl-glare::before { content: ''; position: absolute; inset: -45%; filter: blur(2px);
                background: linear-gradient(90deg, transparent 26%, rgba(255,255,255,.5) 48%, transparent 78%);
                transform: rotate(var(--cgl-rot, 105deg)); }
        .cgl-meta { transform: translate3d(calc(var(--cgl-px, 0) * 22px), calc(var(--cgl-py, 0) * 22px), 0); }
        .cgl-btn { transform: translate3d(calc(var(--cgl-px, 0) * 34px), calc(var(--cgl-py, 0) * 34px), 0); }
        </style>
        SVELTE,
        ],
    ],

    'magnetic-set' => [
        'title' => ['fa' => 'مجموعهٔ مغناطیسی', 'en' => 'Magnetic set'],
        'icon' => 'wand',
        'oneLiner' => [
            'fa' => 'دکمه، آیکون و سوییچِ مغناطیسی در یک میدان: هر کنترل تا وقتی نشانگر داخل شعاع تنظیمی است با میرایی فاصله‌ای به سمت دست کشیده می‌شود و با رفتن دست، فنرِ سختی/میرایی آن را با کمی بیش‌پرش خانه برمی‌گرداند؛ دو اسلایدر شدت و شعاع را زنده می‌چرخند و ردیف چیپ‌های مجاور با کشش کمتری متمایل می‌شوند — فوکوس ژست می‌دهد، تپ معادل لمس است.',
            'en' => 'A button, an icon and a switch living in one magnetic field: each control is pulled toward the hand with distance falloff while the pointer stays inside the dialled radius, and when the hand leaves, a stiffness/damping spring carries it home with a little overshoot; two sliders tune strength and radius live and the adjacent chip row leans in with a shallower pull — focus poses a control, a tap is the touch equivalent.',
        ],
        'js' => true,
        'docs' => 'https://uiverse.io',
        'props' => [
            ['name' => 'field radius', 'type' => 'px', 'default' => "'60 – 220'", 'note' => [
                'fa' => 'شعاع میدان با اسلایدر زنده تنظیم می‌شود؛ بیرون شعاع هیچ کششی نیست.',
                'en' => 'The field radius is tuned live by the slider; outside the radius there is no pull at all.',
            ]],
            ['name' => 'pull strength', 'type' => 'factor', 'default' => "'.10 – .95'", 'note' => [
                'fa' => 'شدت کشش با میرایی فاصله‌ای (۱ − فاصله/شعاع) ضرب می‌شود؛ نتیجه به سقف هر کنترل گیر می‌کند.',
                'en' => 'The pull strength is multiplied by the distance falloff (1 − dist/radius); the result is clamped to each control’s cap.',
            ]],
            ['name' => 'pull caps', 'type' => 'px', 'default' => "'30 · 22 · 16 · 11'", 'note' => [
                'fa' => 'دکمه ۳۰، آیکون ۲۲، سوییچ ۱۶ و چیپ‌های مجاور ۱۱ پیکسل — اهمیت کنترل از سقف کششش پیدا می‌شود.',
                'en' => 'Pill 30, icon 22, switch 16 and the neighbouring chips 11 pixels — a control’s importance is read from its pull cap.',
            ]],
            ['name' => 'spring', 'type' => 'physics', 'default' => "'k .13 · damping .8'", 'note' => [
                'fa' => 'ادغام فنر در حلقهٔ RAF با بیش‌پرش کوچک؛ حلقه فقط تا آرام‌شدن همهٔ فنرها می‌چرخد و خارج از دید پارک می‌شود.',
                'en' => 'Spring integration in the RAF loop with a small overshoot; the loop only spins until every spring settles and parks off screen.',
            ]],
            ['name' => 'keyboard & touch', 'type' => 'a11y', 'default' => "'pose · tap-yank'", 'note' => [
                'fa' => 'فوکوس، کنترل را در ژست کوچکی می‌نشاند و تار شدن آن رها می‌کند؛ تپ، کنترل را به سمت نقطهٔ لمس می‌کشد و فنر جواب می‌دهد؛ در حرکت کاهش‌یافته میدان خاموش است.',
                'en' => 'Focus poses the control into a small offset and blur releases it; a tap yanks it toward the touch point and lets the spring answer; under reduced motion the field is off.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        {{-- مجموعهٔ مغناطیسی — کشش با میرایی فاصله‌ای، بازگشت فنری --}}
        <div class="mgs" x-data="{
                raf: 0, live: false, px: -9999, py: -9999, strength: .5, radius: 130, items: [],
                init() {
                    this.items = Array.from(this.$refs.field.querySelectorAll('.mgs-item')).map((el) => ({
                        el: el, x: 0, y: 0, vx: 0, vy: 0, max: parseFloat(el.dataset.max || '26'),
                    }));
                },
                move(e) {
                    const r = this.$refs.field.getBoundingClientRect();
                    this.px = e.clientX - r.left; this.py = e.clientY - r.top;
                    this.live = true; this.run();
                },
                leave() { this.px = -9999; this.py = -9999; this.live = false; this.run(); },
                run() {
                    cancelAnimationFrame(this.raf);
                    const loop = () => {
                        let energy = 0;
                        const f = this.$refs.field.getBoundingClientRect();
                        for (const it of this.items) {
                            const r = it.el.getBoundingClientRect();
                            const dx = this.px - (r.left + r.width / 2 - f.left);
                            const dy = this.py - (r.top + r.height / 2 - f.top);
                            const dist = Math.hypot(dx, dy);
                            let tx = 0, ty = 0;
                            if (dist < this.radius && dist > 0) {
                                const pull = Math.min(it.max, dist * this.strength * .9) * (1 - dist / this.radius);
                                tx = dx / dist * pull; ty = dy / dist * pull;
                            }
                            it.vx = (it.vx + (tx - it.x) * .13) * .8; it.vy = (it.vy + (ty - it.y) * .13) * .8;
                            it.x += it.vx; it.y += it.vy;
                            energy += Math.abs(it.vx) + Math.abs(it.vy);
                            it.el.style.transform = 'translate3d(' + it.x + 'px,' + it.y + 'px,0)';
                        }
                        if (!this.live && energy < .35) return;
                        this.raf = requestAnimationFrame(loop);
                    };
                    this.raf = requestAnimationFrame(loop);
                },
                destroy() { cancelAnimationFrame(this.raf); },
            }">
            <div class="mgs-field" x-ref="field" x-on:pointermove="move($event)" x-on:pointerleave="leave()">
                <button type="button" class="mgs-pill mgs-item" data-max="30">استقرار روی اج</button>
                <button type="button" class="mgs-ico mgs-item" data-max="22" aria-label="پسندیدن">♥</button>
            </div>
        </div>

        <style>
        .mgs-field { display: grid; gap: 1.9rem; justify-items: center; padding: 2.5rem;
                     border: 1px dashed #e7e5e4; border-radius: 1.25rem; }
        .mgs-item { will-change: transform; }
        .mgs-pill { padding: .78rem 1.5rem; border-radius: 999px; border: 0; cursor: pointer;
                    background: #4f46e5; color: #fff; font: 650 .95rem/1 'Inter', sans-serif; }
        .mgs-ico { inline-size: 2.9rem; aspect-ratio: 1; border-radius: 50%; border: 1px solid #e7e5e4; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        {{-- Magnetic set — distance-falloff pull, spring return --}}
        <div class="mgs" x-data="{
                raf: 0, live: false, px: -9999, py: -9999, strength: .5, radius: 130, items: [],
                init() {
                    this.items = Array.from(this.$refs.field.querySelectorAll('.mgs-item')).map((el) => ({
                        el: el, x: 0, y: 0, vx: 0, vy: 0, max: parseFloat(el.dataset.max || '26'),
                    }));
                },
                move(e) {
                    const r = this.$refs.field.getBoundingClientRect();
                    this.px = e.clientX - r.left; this.py = e.clientY - r.top;
                    this.live = true; this.run();
                },
                leave() { this.px = -9999; this.py = -9999; this.live = false; this.run(); },
                run() {
                    cancelAnimationFrame(this.raf);
                    const loop = () => {
                        let energy = 0;
                        const f = this.$refs.field.getBoundingClientRect();
                        for (const it of this.items) {
                            const r = it.el.getBoundingClientRect();
                            const dx = this.px - (r.left + r.width / 2 - f.left);
                            const dy = this.py - (r.top + r.height / 2 - f.top);
                            const dist = Math.hypot(dx, dy);
                            let tx = 0, ty = 0;
                            if (dist < this.radius && dist > 0) {
                                const pull = Math.min(it.max, dist * this.strength * .9) * (1 - dist / this.radius);
                                tx = dx / dist * pull; ty = dy / dist * pull;
                            }
                            it.vx = (it.vx + (tx - it.x) * .13) * .8; it.vy = (it.vy + (ty - it.y) * .13) * .8;
                            it.x += it.vx; it.y += it.vy;
                            energy += Math.abs(it.vx) + Math.abs(it.vy);
                            it.el.style.transform = 'translate3d(' + it.x + 'px,' + it.y + 'px,0)';
                        }
                        if (!this.live && energy < .35) return;
                        this.raf = requestAnimationFrame(loop);
                    };
                    this.raf = requestAnimationFrame(loop);
                },
                destroy() { cancelAnimationFrame(this.raf); },
            }">
            <div class="mgs-field" x-ref="field" x-on:pointermove="move($event)" x-on:pointerleave="leave()">
                <button type="button" class="mgs-pill mgs-item" data-max="30">Deploy to edge</button>
                <button type="button" class="mgs-ico mgs-item" data-max="22" aria-label="Like">♥</button>
            </div>
        </div>

        <style>
        .mgs-field { display: grid; gap: 1.9rem; justify-items: center; padding: 2.5rem;
                     border: 1px dashed #e7e5e4; border-radius: 1.25rem; }
        .mgs-item { will-change: transform; }
        .mgs-pill { padding: .78rem 1.5rem; border-radius: 999px; border: 0; cursor: pointer;
                    background: #4f46e5; color: #fff; font: 650 .95rem/1 'Inter', sans-serif; }
        .mgs-ico { inline-size: 2.9rem; aspect-ratio: 1; border-radius: 50%; border: 1px solid #e7e5e4; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/QuickActions.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import MagneticDock from '@/components/MagneticDock';

        export default function QuickActions() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <MagneticDock />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // MagneticDock.jsx — magnetic pull with distance falloff, spring return
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        export default function MagneticDock() {
          const fieldRef = useRef(null);
          const [strength, setStrength] = useState(0.5);
          const [radius, setRadius] = useState(130);
          const s = useRef({ px: -9999, py: -9999, live: false, raf: 0, items: [] });
          s.current.cfg = { strength, radius };

          useEffect(() => {
            const st = s.current;
            st.items = Array.from(fieldRef.current.querySelectorAll('[data-max]')).map((el) => ({
              el, x: 0, y: 0, vx: 0, vy: 0, max: parseFloat(el.dataset.max),
            }));
            const loop = () => {
              const { strength, radius } = st.cfg;
              const f = fieldRef.current.getBoundingClientRect();
              let energy = 0;
              for (const it of st.items) {
                const r = it.el.getBoundingClientRect();
                const dx = st.px - (r.left + r.width / 2 - f.left);
                const dy = st.py - (r.top + r.height / 2 - f.top);
                const dist = Math.hypot(dx, dy);
                let tx = 0, ty = 0;
                if (dist < radius && dist > 0) {
                  const pull = Math.min(it.max, dist * strength * .9) * (1 - dist / radius);
                  tx = (dx / dist) * pull; ty = (dy / dist) * pull;
                }
                it.vx = (it.vx + (tx - it.x) * .13) * .8; it.vy = (it.vy + (ty - it.y) * .13) * .8;
                it.x += it.vx; it.y += it.vy;
                energy += Math.abs(it.vx) + Math.abs(it.vy);
                it.el.style.transform = `translate3d(${it.x}px,${it.y}px,0)`;
              }
              if (!st.live && energy < .35) return;
              st.raf = requestAnimationFrame(loop);
            };
            const kick = () => { cancelAnimationFrame(st.raf); st.raf = requestAnimationFrame(loop); };
            const move = (e) => {
              const r = fieldRef.current.getBoundingClientRect();
              st.px = e.clientX - r.left; st.py = e.clientY - r.top; st.live = true; kick();
            };
            const leave = () => { st.px = -9999; st.py = -9999; st.live = false; kick(); };
            fieldRef.current.addEventListener('pointermove', move);
            fieldRef.current.addEventListener('pointerleave', leave);
            return () => {
              cancelAnimationFrame(st.raf);
              fieldRef.current?.removeEventListener('pointermove', move);
              fieldRef.current?.removeEventListener('pointerleave', leave);
            };
          }, []);

          return (
            <>
              <style>{`
                .mgs-field { display: grid; gap: 1.9rem; justify-items: center; padding: 2.5rem;
                             border: 1px dashed #e7e5e4; border-radius: 1.25rem; }
                [data-max] { will-change: transform; }
                .mgs-pill { padding: .78rem 1.5rem; border-radius: 999px; border: 0; cursor: pointer;
                            background: #4f46e5; color: #fff; font: 650 .95rem/1 'Inter', sans-serif; }
              `}</style>
              <div className="mgs-field" ref={fieldRef} onPointerMove={move} onPointerLeave={leave}>
                <button type="button" className="mgs-pill" data-max="30">Deploy to edge</button>
                <button type="button" className="mgs-ico" data-max="22" aria-label="Like">♥</button>
              </div>
              <label>
                radius
                <input type="range" min="60" max="220" value={radius}
                       onChange={(e) => setRadius(+e.target.value)} />
              </label>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- MagneticDock.vue — magnetic pull with distance falloff, spring return -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const field = ref<HTMLElement | null>(null);
        const strength = ref(0.5);
        const radius = ref(130);
        const st = { px: -9999, py: -9999, live: false, raf: 0, items: [] as {
          el: HTMLElement; x: number; y: number; vx: number; vy: number; max: number }[] };

        const loop = () => {
          const f = field.value!.getBoundingClientRect();
          let energy = 0;
          for (const it of st.items) {
            const r = it.el.getBoundingClientRect();
            const dx = st.px - (r.left + r.width / 2 - f.left);
            const dy = st.py - (r.top + r.height / 2 - f.top);
            const dist = Math.hypot(dx, dy);
            let tx = 0, ty = 0;
            if (dist < radius.value && dist > 0) {
              const pull = Math.min(it.max, dist * strength.value * .9) * (1 - dist / radius.value);
              tx = (dx / dist) * pull; ty = (dy / dist) * pull;
            }
            it.vx = (it.vx + (tx - it.x) * .13) * .8; it.vy = (it.vy + (ty - it.y) * .13) * .8;
            it.x += it.vx; it.y += it.vy;
            energy += Math.abs(it.vx) + Math.abs(it.vy);
            it.el.style.transform = `translate3d(${it.x}px,${it.y}px,0)`;
          }
          if (!st.live && energy < .35) return;
          st.raf = requestAnimationFrame(loop);
        };
        const kick = () => { cancelAnimationFrame(st.raf); st.raf = requestAnimationFrame(loop); };
        const move = (e: PointerEvent) => {
          const r = field.value!.getBoundingClientRect();
          st.px = e.clientX - r.left; st.py = e.clientY - r.top; st.live = true; kick();
        };
        const leave = () => { st.px = -9999; st.py = -9999; st.live = false; kick(); };

        onMounted(() => {
          st.items = Array.from(field.value!.querySelectorAll('[data-max]')).map((el) => ({
            el: el as HTMLElement, x: 0, y: 0, vx: 0, vy: 0, max: parseFloat((el as HTMLElement).dataset.max!),
          }));
          field.value!.addEventListener('pointermove', move);
          field.value!.addEventListener('pointerleave', leave);
        });
        onBeforeUnmount(() => {
          cancelAnimationFrame(st.raf);
          field.value?.removeEventListener('pointermove', move);
          field.value?.removeEventListener('pointerleave', leave);
        });
        </script>

        <template>
          <div ref="field" class="mgs-field" @pointermove="move" @pointerleave="leave">
            <button type="button" class="mgs-pill" data-max="30">Deploy to edge</button>
            <button type="button" class="mgs-ico" data-max="22" aria-label="Like">♥</button>
          </div>
          <label>
            radius
            <input v-model.number="radius" type="range" min="60" max="220" @input="kick" />
          </label>
        </template>

        <style scoped>
        .mgs-field { display: grid; gap: 1.9rem; justify-items: center; padding: 2.5rem;
                     border: 1px dashed #e7e5e4; border-radius: 1.25rem; }
        [data-max] { will-change: transform; }
        .mgs-pill { padding: .78rem 1.5rem; border-radius: 999px; border: 0; cursor: pointer;
                    background: #4f46e5; color: #fff; font: 650 .95rem/1 'Inter', sans-serif; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- MagneticDock.svelte — magnetic pull with distance falloff, spring return -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          let field: HTMLElement;
          let strength = $state(0.5);
          let radius = $state(130);
          const st = { px: -9999, py: -9999, live: false, raf: 0, items: [] as {
            el: HTMLElement; x: number; y: number; vx: number; vy: number; max: number }[] };

          const loop = () => {
            const f = field.getBoundingClientRect();
            let energy = 0;
            for (const it of st.items) {
              const r = it.el.getBoundingClientRect();
              const dx = st.px - (r.left + r.width / 2 - f.left);
              const dy = st.py - (r.top + r.height / 2 - f.top);
              const dist = Math.hypot(dx, dy);
              let tx = 0, ty = 0;
              if (dist < radius && dist > 0) {
                const pull = Math.min(it.max, dist * strength * .9) * (1 - dist / radius);
                tx = (dx / dist) * pull; ty = (dy / dist) * pull;
              }
              it.vx = (it.vx + (tx - it.x) * .13) * .8; it.vy = (it.vy + (ty - it.y) * .13) * .8;
              it.x += it.vx; it.y += it.vy;
              energy += Math.abs(it.vx) + Math.abs(it.vy);
              it.el.style.transform = `translate3d(${it.x}px,${it.y}px,0)`;
            }
            if (!st.live && energy < .35) return;
            st.raf = requestAnimationFrame(loop);
          };
          const kick = () => { cancelAnimationFrame(st.raf); st.raf = requestAnimationFrame(loop); };
          const move = (e: PointerEvent) => {
            const r = field.getBoundingClientRect();
            st.px = e.clientX - r.left; st.py = e.clientY - r.top; st.live = true; kick();
          };
          const leave = () => { st.px = -9999; st.py = -9999; st.live = false; kick(); };

          onMount(() => {
            st.items = Array.from(field.querySelectorAll('[data-max]')).map((el) => ({
              el: el as HTMLElement, x: 0, y: 0, vx: 0, vy: 0, max: parseFloat((el as HTMLElement).dataset.max!),
            }));
            field.addEventListener('pointermove', move);
            field.addEventListener('pointerleave', leave);
            return () => {
              cancelAnimationFrame(st.raf);
              field.removeEventListener('pointermove', move);
              field.removeEventListener('pointerleave', leave);
            };
          });
        </script>

        <div bind:this={field} class="mgs-field" onpointermove={move} onpointerleave={leave}>
          <button type="button" class="mgs-pill" data-max="30">Deploy to edge</button>
          <button type="button" class="mgs-ico" data-max="22" aria-label="Like">♥</button>
        </div>
        <label>
          radius
          <input type="range" min="60" max="220" bind:value={radius} oninput={kick} />
        </label>

        <style>
        .mgs-field { display: grid; gap: 1.9rem; justify-items: center; padding: 2.5rem;
                     border: 1px dashed #e7e5e4; border-radius: 1.25rem; }
        [data-max] { will-change: transform; }
        .mgs-pill { padding: .78rem 1.5rem; border-radius: 999px; border: 0; cursor: pointer;
                    background: #4f46e5; color: #fff; font: 650 .95rem/1 'Inter', sans-serif; }
        </style>
        SVELTE,
        ],
    ],

    'image-accordion' => [
        'title' => ['fa' => 'آکاردئون تصویری', 'en' => 'Image accordion'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'نوار افقی پنل‌های تصویری (صحنه‌های SVG) مثل ژورنال سفر: پنلِ هاور/فوکوس/لمس‌شده روی منحنی flex-grow باز می‌شود و شماره و عنوانش از تاخیه بالا می‌آیند، بقیه جمع می‌شوند و شمارهٔ عمودی‌شان می‌ماند؛ کلیدهای جهت نوار را می‌پیمایند (در راست‌به‌چپ وارونه) و Home/End به دو سر می‌پرند.',
            'en' => 'A horizontal strip of image panels (SVG scenes) like a travel journal: the hovered, focused or tapped panel opens on a flex-grow curve with its number and title rising in from the fold while the rest collapse to their vertical running numbers; arrow keys walk the strip (flipped for RTL) and Home/End jump to the ends.',
        ],
        'js' => true,
        'docs' => 'https://uiverse.io',
        'props' => [
            ['name' => 'open share', 'type' => 'flex', 'default' => "'grow 4.6 : 1'", 'note' => [
                'fa' => 'پنل باز flex-grow برابر ۴٫۶ و پنل‌های جمع‌شده ۱ می‌گیرند؛ انتقال با منحنی ۰٫۵۵ ثانیه‌ای نرم باز می‌شود.',
                'en' => 'The open panel takes flex-grow 4.6 against 1 for the collapsed ones; the .55s curve unfolds it softly.',
            ]],
            ['name' => 'min panel', 'type' => 'px', 'default' => "'3.1rem'", 'note' => [
                'fa' => 'پنل جمع‌شده هرگز از ۳٫۱rem باریک‌تر نمی‌شود تا شمارهٔ عمودی‌اش خوانا بماند؛ در موبایل ۲٫۵rem.',
                'en' => 'A collapsed panel never gets thinner than 3.1rem so its vertical number stays legible; 2.5rem on mobile.',
            ]],
            ['name' => 'keyboard', 'type' => 'keys', 'default' => "'← → · Home/End'", 'note' => [
                'fa' => 'کلیدهای جهت فوکوس و باز بودن را با هم جابه‌جا می‌کنند و جهتشان در راست‌به‌چپ وارونه می‌شود؛ هر پنل دکمهٔ واقعی با aria-expanded است.',
                'en' => 'Arrow keys move focus and open state together, flipped for RTL; every panel is a real button carrying aria-expanded.',
            ]],
            ['name' => 'touch', 'type' => 'event', 'default' => "'tap = open'", 'note' => [
                'fa' => 'در لمس، تپ معادل هاور است و همان پنل را باز می‌کند؛ نوار اجازهٔ اسکرول عمودی صفحه را می‌دهد.',
                'en' => 'On touch a tap is the hover equivalent and opens that panel; the strip leaves vertical page scrolling free.',
            ]],
            ['name' => 'reduced-motion', 'type' => 'media', 'default' => "'instant'", 'note' => [
                'fa' => 'با حرکت کاهش‌یافته سهم‌ها بی‌انتقال و لحظه‌ای عوض می‌شوند؛ حالت نهایی همان می‌ماند.',
                'en' => 'Under reduced motion the shares swap instantly with no transition; the end state is unchanged.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        {{-- آکاردئون تصویری — بازشدن با flex-grow، فلش‌های جهت‌دار --}}
        <div class="iac" x-data="{
                open: 1, panels: [], rtl: false,
                init() {
                    this.rtl = getComputedStyle(document.documentElement).direction === 'rtl';
                    this.panels = Array.from(this.$refs.strip.querySelectorAll('.iac-panel'));
                },
                openTo(i) { this.open = i; },
                onKey(e, i) {
                    const dflt = this.rtl ? -1 : 1;
                    if (e.key === 'ArrowRight') { e.preventDefault(); this.step(i, dflt); }
                    else if (e.key === 'ArrowLeft') { e.preventDefault(); this.step(i, -dflt); }
                    else if (e.key === 'Home') { e.preventDefault(); this.step(i, -i); }
                    else if (e.key === 'End') { e.preventDefault(); this.step(i, this.panels.length - 1 - i); }
                },
                step(from, delta) {
                    const t = Math.min(this.panels.length - 1, Math.max(0, from + delta));
                    this.open = t; this.panels[t].focus();
                },
            }">
            <div class="iac-strip" x-ref="strip">
                @foreach (['کیوتو', 'پاتاگونیا', 'ساحل آمالفی'] as $i => $name)
                    <button type="button" class="iac-panel"
                        x-bind:data-open="open === {{ $i }} ? 'true' : 'false'"
                        x-bind:aria-expanded="open === {{ $i }} ? 'true' : 'false'"
                        x-on:pointerenter="openTo({{ $i }})" x-on:click="openTo({{ $i }})"
                        x-on:focus="openTo({{ $i }})" x-on:keydown="onKey($event, {{ $i }})">
                        <span class="iac-scene">…صحنه…</span>
                        <span class="iac-fold">
                            <span class="iac-title-wrap">
                                <span class="iac-no">۰{{ $i + ۱ }}</span>
                                <span class="iac-title">{{ $name }}</span>
                            </span>
                        </span>
                    </button>
                @endforeach
            </div>
        </div>

        <style>
        .iac-strip { display: flex; gap: .45rem; min-block-size: 22rem; padding: .45rem;
                     border-radius: 1.25rem; overflow: clip; }
        .iac-panel { position: relative; flex: 1 1 0; min-inline-size: 3.1rem; border: 0; cursor: pointer;
                     border-radius: .95rem; overflow: clip; display: grid; align-content: end;
                     transition: flex-grow .55s cubic-bezier(.3, .8, .25, 1); }
        .iac-panel[data-open='true'] { flex-grow: 4.6; }
        .iac-fold { position: relative; z-index: 1; padding: 1rem 1.15rem 1.1rem; color: #f8fafc; }
        .iac-title-wrap { display: grid; gap: .1rem; overflow: clip; opacity: 0; translate: 0 .9rem;
                     transition: opacity .38s ease .12s, translate .38s cubic-bezier(.3,.8,.3,1) .12s; }
        .iac-panel[data-open='true'] .iac-title-wrap { opacity: 1; translate: 0 0; }
        @media (prefers-reduced-motion: reduce) { .iac-panel, .iac-title-wrap { transition: none; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        {{-- Image accordion — flex-grow opening, arrow-key walking --}}
        <div class="iac" x-data="{
                open: 1, panels: [], rtl: false,
                init() {
                    this.rtl = getComputedStyle(document.documentElement).direction === 'rtl';
                    this.panels = Array.from(this.$refs.strip.querySelectorAll('.iac-panel'));
                },
                openTo(i) { this.open = i; },
                onKey(e, i) {
                    const dflt = this.rtl ? -1 : 1;
                    if (e.key === 'ArrowRight') { e.preventDefault(); this.step(i, dflt); }
                    else if (e.key === 'ArrowLeft') { e.preventDefault(); this.step(i, -dflt); }
                    else if (e.key === 'Home') { e.preventDefault(); this.step(i, -i); }
                    else if (e.key === 'End') { e.preventDefault(); this.step(i, this.panels.length - 1 - i); }
                },
                step(from, delta) {
                    const t = Math.min(this.panels.length - 1, Math.max(0, from + delta));
                    this.open = t; this.panels[t].focus();
                },
            }">
            <div class="iac-strip" x-ref="strip">
                @foreach (['Kyoto', 'Patagonia', 'Amalfi Coast'] as $i => $name)
                    <button type="button" class="iac-panel"
                        x-bind:data-open="open === {{ $i }} ? 'true' : 'false'"
                        x-bind:aria-expanded="open === {{ $i }} ? 'true' : 'false'"
                        x-on:pointerenter="openTo({{ $i }})" x-on:click="openTo({{ $i }})"
                        x-on:focus="openTo({{ $i }})" x-on:keydown="onKey($event, {{ $i }})">
                        <span class="iac-scene">…scene…</span>
                        <span class="iac-fold">
                            <span class="iac-title-wrap">
                                <span class="iac-no">0{{ $i + 1 }}</span>
                                <span class="iac-title">{{ $name }}</span>
                            </span>
                        </span>
                    </button>
                @endforeach
            </div>
        </div>

        <style>
        .iac-strip { display: flex; gap: .45rem; min-block-size: 22rem; padding: .45rem;
                     border-radius: 1.25rem; overflow: clip; }
        .iac-panel { position: relative; flex: 1 1 0; min-inline-size: 3.1rem; border: 0; cursor: pointer;
                     border-radius: .95rem; overflow: clip; display: grid; align-content: end;
                     transition: flex-grow .55s cubic-bezier(.3, .8, .25, 1); }
        .iac-panel[data-open='true'] { flex-grow: 4.6; }
        .iac-fold { position: relative; z-index: 1; padding: 1rem 1.15rem 1.1rem; color: #f8fafc; }
        .iac-title-wrap { display: grid; gap: .1rem; overflow: clip; opacity: 0; translate: 0 .9rem;
                     transition: opacity .38s ease .12s, translate .38s cubic-bezier(.3,.8,.3,1) .12s; }
        .iac-panel[data-open='true'] .iac-title-wrap { opacity: 1; translate: 0 0; }
        @media (prefers-reduced-motion: reduce) { .iac-panel, .iac-title-wrap { transition: none; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Destinations.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import DestinationStrip from '@/components/DestinationStrip';

        export default function Destinations() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <DestinationStrip />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // DestinationStrip.jsx — image accordion on flex-grow, arrow-key walk
        import { useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const PLACES = ['Kyoto', 'Patagonia', 'Amalfi Coast', 'Lapland', 'Big Sur'];

        export default function DestinationStrip() {
          const [open, setOpen] = useState(1);
          const btns = useRef([]);

          const step = (from, delta) => {
            const t = Math.min(PLACES.length - 1, Math.max(0, from + delta));
            setOpen(t);
            btns.current[t]?.focus();
          };
          const onKey = (e, i) => {
            const dflt = document.documentElement.dir === 'rtl' ? -1 : 1;
            if (e.key === 'ArrowRight') { e.preventDefault(); step(i, dflt); }
            else if (e.key === 'ArrowLeft') { e.preventDefault(); step(i, -dflt); }
            else if (e.key === 'Home') { e.preventDefault(); step(i, -i); }
            else if (e.key === 'End') { e.preventDefault(); step(i, PLACES.length - 1 - i); }
          };

          return (
            <>
              <style>{`
                .iac-strip { display: flex; gap: .45rem; min-block-size: 22rem; padding: .45rem;
                             border-radius: 1.25rem; overflow: clip; }
                .iac-panel { position: relative; flex: 1 1 0; min-inline-size: 3.1rem; border: 0; cursor: pointer;
                             border-radius: .95rem; overflow: clip; display: grid; align-content: end;
                             transition: flex-grow .55s cubic-bezier(.3, .8, .25, 1); }
                .iac-panel[data-open='true'] { flex-grow: 4.6; }
                .iac-fold { position: relative; z-index: 1; padding: 1rem 1.15rem 1.1rem; color: #f8fafc; }
                .iac-title-wrap { display: grid; gap: .1rem; overflow: clip; opacity: 0; translate: 0 .9rem;
                             transition: opacity .38s ease .12s, translate .38s cubic-bezier(.3,.8,.3,1) .12s; }
                .iac-panel[data-open='true'] .iac-title-wrap { opacity: 1; translate: 0 0; }
                @media (prefers-reduced-motion: reduce) { .iac-panel, .iac-title-wrap { transition: none; } }
              `}</style>
              <div className="iac-strip" role="group" aria-label="Destinations">
                {PLACES.map((name, i) => (
                  <button key={name} type="button" className="iac-panel" ref={(el) => { btns.current[i] = el; }}
                          data-open={open === i} aria-expanded={open === i}
                          onPointerEnter={() => setOpen(i)} onFocus={() => setOpen(i)}
                          onKeyDown={(e) => onKey(e, i)}>
                    <span className="iac-scene" aria-hidden="true">…scene {i + 1}…</span>
                    <span className="iac-fold">
                      <span className="iac-title-wrap">
                        <span className="iac-no">0{i + 1} · Meridian Atlas</span>
                        <span className="iac-title">{name}</span>
                      </span>
                    </span>
                  </button>
                ))}
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- DestinationStrip.vue — image accordion on flex-grow, arrow-key walk -->
        <script setup lang="ts">
        import { ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const places = ['Kyoto', 'Patagonia', 'Amalfi Coast', 'Lapland', 'Big Sur'];
        const open = ref(1);
        const btns = ref<HTMLElement[]>([]);

        const step = (from: number, delta: number) => {
          const t = Math.min(places.length - 1, Math.max(0, from + delta));
          open.value = t;
          btns.value[t]?.focus();
        };
        const onKey = (e: KeyboardEvent, i: number) => {
          const dflt = document.documentElement.dir === 'rtl' ? -1 : 1;
          if (e.key === 'ArrowRight') { e.preventDefault(); step(i, dflt); }
          else if (e.key === 'ArrowLeft') { e.preventDefault(); step(i, -dflt); }
          else if (e.key === 'Home') { e.preventDefault(); step(i, -i); }
          else if (e.key === 'End') { e.preventDefault(); step(i, places.length - 1 - i); }
        };
        </script>

        <template>
          <div class="iac-strip" role="group" aria-label="Destinations">
            <button v-for="(name, i) in places" :key="name" type="button" class="iac-panel"
                    :ref="(el) => { if (el) btns[i] = el as HTMLElement; }"
                    :data-open="open === i" :aria-expanded="open === i"
                    @pointerenter="open = i" @focus="open = i" @keydown="onKey($event, i)">
              <span class="iac-scene" aria-hidden="true">…scene {{ i + 1 }}…</span>
              <span class="iac-fold">
                <span class="iac-title-wrap">
                  <span class="iac-no">0{{ i + 1 }} · Meridian Atlas</span>
                  <span class="iac-title">{{ name }}</span>
                </span>
              </span>
            </button>
          </div>
        </template>

        <style scoped>
        .iac-strip { display: flex; gap: .45rem; min-block-size: 22rem; padding: .45rem;
                     border-radius: 1.25rem; overflow: clip; }
        .iac-panel { position: relative; flex: 1 1 0; min-inline-size: 3.1rem; border: 0; cursor: pointer;
                     border-radius: .95rem; overflow: clip; display: grid; align-content: end;
                     transition: flex-grow .55s cubic-bezier(.3, .8, .25, 1); }
        .iac-panel[data-open='true'] { flex-grow: 4.6; }
        .iac-fold { position: relative; z-index: 1; padding: 1rem 1.15rem 1.1rem; color: #f8fafc; }
        .iac-title-wrap { display: grid; gap: .1rem; overflow: clip; opacity: 0; translate: 0 .9rem;
                     transition: opacity .38s ease .12s, translate .38s cubic-bezier(.3,.8,.3,1) .12s; }
        .iac-panel[data-open='true'] .iac-title-wrap { opacity: 1; translate: 0 0; }
        @media (prefers-reduced-motion: reduce) { .iac-panel, .iac-title-wrap { transition: none; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- DestinationStrip.svelte — image accordion on flex-grow, arrow-key walk -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const places = ['Kyoto', 'Patagonia', 'Amalfi Coast', 'Lapland', 'Big Sur'];
          let open = $state(1);
          let btns: HTMLElement[] = [];

          const step = (from: number, delta: number) => {
            const t = Math.min(places.length - 1, Math.max(0, from + delta));
            open = t;
            btns[t]?.focus();
          };
          const onKey = (e: KeyboardEvent, i: number) => {
            const dflt = document.documentElement.dir === 'rtl' ? -1 : 1;
            if (e.key === 'ArrowRight') { e.preventDefault(); step(i, dflt); }
            else if (e.key === 'ArrowLeft') { e.preventDefault(); step(i, -dflt); }
            else if (e.key === 'Home') { e.preventDefault(); step(i, -i); }
            else if (e.key === 'End') { e.preventDefault(); step(i, places.length - 1 - i); }
          };
        </script>

        <div class="iac-strip" role="group" aria-label="Destinations">
          {#each places as name, i (name)}
            <button type="button" class="iac-panel"
                    bind:this={btns[i]}
                    data-open={open === i} aria-expanded={open === i}
                    onpointerenter={() => (open = i)} onfocus={() => (open = i)}
                    onkeydown={(e) => onKey(e, i)}>
              <span class="iac-scene" aria-hidden="true">…scene {i + 1}…</span>
              <span class="iac-fold">
                <span class="iac-title-wrap">
                  <span class="iac-no">0{i + 1} · Meridian Atlas</span>
                  <span class="iac-title">{name}</span>
                </span>
              </span>
            </button>
          {/each}
        </div>

        <style>
        .iac-strip { display: flex; gap: .45rem; min-block-size: 22rem; padding: .45rem;
                     border-radius: 1.25rem; overflow: clip; }
        .iac-panel { position: relative; flex: 1 1 0; min-inline-size: 3.1rem; border: 0; cursor: pointer;
                     border-radius: .95rem; overflow: clip; display: grid; align-content: end;
                     transition: flex-grow .55s cubic-bezier(.3, .8, .25, 1); }
        .iac-panel[data-open='true'] { flex-grow: 4.6; }
        .iac-fold { position: relative; z-index: 1; padding: 1rem 1.15rem 1.1rem; color: #f8fafc; }
        .iac-title-wrap { display: grid; gap: .1rem; overflow: clip; opacity: 0; translate: 0 .9rem;
                     transition: opacity .38s ease .12s, translate .38s cubic-bezier(.3,.8,.3,1) .12s; }
        .iac-panel[data-open='true'] .iac-title-wrap { opacity: 1; translate: 0 0; }
        @media (prefers-reduced-motion: reduce) { .iac-panel, .iac-title-wrap { transition: none; } }
        </style>
        SVELTE,
        ],
    ],
];
