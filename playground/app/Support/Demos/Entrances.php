<?php

/**
 * Demo manifest of the "entrances" group — cinematic entrances: the loading
 * curtain that parts on a percentage counter, lists that stagger in from
 * below with blur and skew, headlines that rise line by line out of their
 * masks, a three-act stage that scales in from afar and a split-flap
 * departure board. Every demo is a real entrance mechanism with a replay.
 * Scenarios live at resources/views/demos/components/entrances/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'ورودها', 'en' => 'Entrances'],

    'curtain-reveal' => [
        'title' => ['fa' => 'پردهٔ ورود', 'en' => 'Curtain reveal'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'پردهٔ بارگذاری دوپنلی با نشان برند و شمارندهٔ درصد؛ با رسیدن به ۱۰۰ پرده‌ها به دو طرف باز می‌شوند و محتوای صفحه با stagger پله‌ای می‌رسد — با دکمهٔ ری‌پلی و اسلایدر سرعت، خارج از دید پارک و در حرکت کاهش‌یافته بی‌درنگ باز.',
            'en' => 'A two-panel loading curtain with a wordmark and a percentage counter; at one hundred the panels part sideways and the page content staggers in — replay and a speed slider included, parked off screen, instant open under reduced motion.',
        ],
        'js' => true,
        'docs' => 'https://www.awwwards.com',
        'props' => [
            ['name' => 'duration', 'type' => 'ms', 'default' => '2400', 'note' => [
                'fa' => 'کل زمان شمارش؛ اسلایدر ۰٫۸ تا ۵ ثانیه، حتی وسط کار هم دوباره تنظیم می‌شود.',
                'en' => 'The whole count; a slider from 0.8 to 5 seconds, retunable even mid-run.',
            ]],
            ['name' => 'curve', 'type' => 'easing', 'default' => "'--nx-spring-gentle'", 'note' => [
                'fa' => 'فنر استاندارد ریپو برای باز شدن پرده‌ها (۹۰۰ms) و ورود آیتم‌ها (۵۵۰ms).',
                'en' => 'The repo’s standard spring for the parting panels (900ms) and the item entrances (550ms).',
            ]],
            ['name' => 'stagger', 'type' => 'ms', 'default' => '95', 'note' => [
                'fa' => 'فاصلهٔ ورود هر آیتم محتوا پس از باز شدن پرده؛ شروع با ۳۶۰ms تأخیر.',
                'en' => 'The step between content items once the curtain opens; the first waits 360ms.',
            ]],
            ['name' => 'threshold', 'type' => 'ratio', 'default' => '0.25', 'note' => [
                'fa' => 'آستانهٔ IntersectionObserver؛ شمارش وقتی ردیف به دید برسد شروع می‌شود.',
                'en' => 'The IntersectionObserver threshold; the count starts once the stage is in view.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="curt-stage" x-data="{
                open: false, pct: 0, speed: 2.4, raf: 0, io: null,
                init() {
                    this.io = new IntersectionObserver((e) => { if (e[0].isIntersecting) this.tick(); });
                    this.io.observe(this.$el);
                },
                destroy() { this.io?.disconnect(); cancelAnimationFrame(this.raf); },
                tick() {
                    const t0 = performance.now(), dur = this.speed * 1000;
                    const loop = (n) => {
                        this.pct = Math.round(Math.min(1, (n - t0) / dur) * 100);
                        if (this.pct < 100) this.raf = requestAnimationFrame(loop);
                        else { this.open = true; }
                    };
                    this.raf = requestAnimationFrame(loop);
                },
            }" :data-open="open">
            <nav class="curt-in" style="--i: 0"><b>NORTHLIGHT</b></nav>
            <h2 class="curt-in" style="--i: 2">پرده برای رابط بعدی شما بالا می‌رود</h2>
            <div class="curt-in" style="--i: 4"><button>شروع ساخت</button></div>
            <div class="curt-mark">NORTHLIGHT <b><span x-text="pct"></span>%</b></div>
            <i class="curt-panel curt-l"></i><i class="curt-panel curt-r"></i>
        </div>

        <style>
        .curt-stage { position: relative; overflow: clip; border-radius: 1.25rem; display: grid; gap: 1rem;
                      align-content: center; padding: 2rem; min-block-size: 20rem; background: #fffdf9; }
        .curt-in { opacity: 0; translate: 0 18px; filter: blur(7px);
                   transition: opacity .55s var(--nx-spring-gentle), translate .55s var(--nx-spring-gentle),
                               filter .55s var(--nx-spring-gentle); }
        .curt-stage[data-open='true'] .curt-in { opacity: 1; translate: 0 0; filter: none;
                   transition-delay: calc(360ms + var(--i) * 95ms); }
        .curt-panel { position: absolute; inset-block: 0; inline-size: 50.5%; background: #1c1917;
                      transition: translate .9s var(--nx-spring-gentle); }
        .curt-l { inset-inline-start: 0; } .curt-r { inset-inline-end: 0; }
        .curt-stage[data-open='true'] .curt-l { translate: -102% 0; }
        .curt-stage[data-open='true'] .curt-r { translate: 102% 0; }
        .curt-mark { position: absolute; inset: 0; z-index: 2; display: grid; place-content: center;
                     justify-items: center; gap: .6rem; color: #f5f0e8; transition: opacity .4s, scale .4s; }
        .curt-stage[data-open='true'] .curt-mark { opacity: 0; scale: .92; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="curt-stage" x-data="{
                open: false, pct: 0, speed: 2.4, raf: 0, io: null,
                init() {
                    this.io = new IntersectionObserver((e) => { if (e[0].isIntersecting) this.tick(); });
                    this.io.observe(this.$el);
                },
                destroy() { this.io?.disconnect(); cancelAnimationFrame(this.raf); },
                tick() {
                    const t0 = performance.now(), dur = this.speed * 1000;
                    const loop = (n) => {
                        this.pct = Math.round(Math.min(1, (n - t0) / dur) * 100);
                        if (this.pct < 100) this.raf = requestAnimationFrame(loop);
                        else { this.open = true; }
                    };
                    this.raf = requestAnimationFrame(loop);
                },
            }" :data-open="open">
            <nav class="curt-in" style="--i: 0"><b>NORTHLIGHT</b></nav>
            <h2 class="curt-in" style="--i: 2">The curtain rises on your next interface</h2>
            <div class="curt-in" style="--i: 4"><button>Start building</button></div>
            <div class="curt-mark">NORTHLIGHT <b><span x-text="pct"></span>%</b></div>
            <i class="curt-panel curt-l"></i><i class="curt-panel curt-r"></i>
        </div>

        <style>
        .curt-stage { position: relative; overflow: clip; border-radius: 1.25rem; display: grid; gap: 1rem;
                      align-content: center; padding: 2rem; min-block-size: 20rem; background: #fffdf9; }
        .curt-in { opacity: 0; translate: 0 18px; filter: blur(7px);
                   transition: opacity .55s var(--nx-spring-gentle), translate .55s var(--nx-spring-gentle),
                               filter .55s var(--nx-spring-gentle); }
        .curt-stage[data-open='true'] .curt-in { opacity: 1; translate: 0 0; filter: none;
                   transition-delay: calc(360ms + var(--i) * 95ms); }
        .curt-panel { position: absolute; inset-block: 0; inline-size: 50.5%; background: #1c1917;
                      transition: translate .9s var(--nx-spring-gentle); }
        .curt-l { inset-inline-start: 0; } .curt-r { inset-inline-end: 0; }
        .curt-stage[data-open='true'] .curt-l { translate: -102% 0; }
        .curt-stage[data-open='true'] .curt-r { translate: 102% 0; }
        .curt-mark { position: absolute; inset: 0; z-index: 2; display: grid; place-content: center;
                     justify-items: center; gap: .6rem; color: #f5f0e8; transition: opacity .4s, scale .4s; }
        .curt-stage[data-open='true'] .curt-mark { opacity: 0; scale: .92; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Launch.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import CurtainReveal from '@/components/CurtainReveal';

        export default function Launch() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <CurtainReveal speed={2.4} />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // CurtainReveal.jsx — a two-panel loading curtain with a percentage counter
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        export default function CurtainReveal({ speed = 2.4 }) {
          const [open, setOpen] = useState(false);
          const [pct, setPct] = useState(0);
          const stageRef = useRef(null);
          const rafRef = useRef(0);

          // One count per mount: starts when the stage is 25% in view,
          // opens the house at one hundred, cleans itself up on unmount.
          useEffect(() => {
            let done = false;
            const io = new IntersectionObserver(([e]) => {
              if (!e.isIntersecting || done) return;
              done = true;
              io.disconnect();
              const t0 = performance.now();
              const loop = (n) => {
                const p = Math.min(1, (n - t0) / (speed * 1000));
                setPct(Math.round((1 - Math.pow(1 - p, 3)) * 100));
                if (p < 1) rafRef.current = requestAnimationFrame(loop);
                else setOpen(true);
              };
              rafRef.current = requestAnimationFrame(loop);
            }, { threshold: 0.25 });
            io.observe(stageRef.current);
            return () => { io.disconnect(); cancelAnimationFrame(rafRef.current); };
          }, [speed]);

          return (
            <>
              <style>{`
                .curt-stage { position: relative; overflow: clip; border-radius: 1.25rem; display: grid; gap: 1rem;
                              align-content: center; padding: 2rem; min-block-size: 20rem; background: #fffdf9; }
                .curt-in { opacity: 0; translate: 0 18px; filter: blur(7px);
                           transition: opacity .55s var(--nx-spring-gentle), translate .55s var(--nx-spring-gentle),
                                       filter .55s var(--nx-spring-gentle); }
                .curt-stage[data-open='true'] .curt-in { opacity: 1; translate: 0 0; filter: none;
                           transition-delay: calc(360ms + var(--i) * 95ms); }
                .curt-panel { position: absolute; inset-block: 0; inline-size: 50.5%; background: #1c1917;
                              transition: translate .9s var(--nx-spring-gentle); }
                .curt-l { inset-inline-start: 0; } .curt-r { inset-inline-end: 0; }
                .curt-stage[data-open='true'] .curt-l { translate: -102% 0; }
                .curt-stage[data-open='true'] .curt-r { translate: 102% 0; }
                .curt-mark { position: absolute; inset: 0; z-index: 2; display: grid; place-content: center;
                             justify-items: center; gap: .6rem; color: #f5f0e8; transition: opacity .4s, scale .4s; }
                .curt-stage[data-open='true'] .curt-mark { opacity: 0; scale: .92; }
              `}</style>
              <div className="curt-stage" data-open={open} ref={stageRef}>
                <nav className="curt-in" style={{ '--i': 0 }}><b>NORTHLIGHT</b></nav>
                <h2 className="curt-in" style={{ '--i': 2 }}>The curtain rises on your next interface</h2>
                <div className="curt-in" style={{ '--i': 4 }}><button type="button">Start building</button></div>
                <div className="curt-mark" aria-hidden="true">NORTHLIGHT <b>{pct}%</b></div>
                <i className="curt-panel curt-l" aria-hidden="true" />
                <i className="curt-panel curt-r" aria-hidden="true" />
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- CurtainReveal.vue — a two-panel loading curtain with a percentage counter -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const props = defineProps<{ speed?: number }>();
        const open = ref(false);
        const pct = ref(0);
        let io: IntersectionObserver | null = null;
        let raf = 0;

        onMounted(() => {
          const el = stage.value;
          io = new IntersectionObserver(([e]) => {
            if (!e.isIntersecting || open.value) return;
            io?.disconnect();
            const t0 = performance.now();
            const loop = (n: number) => {
              const p = Math.min(1, (n - t0) / ((props.speed ?? 2.4) * 1000));
              pct.value = Math.round((1 - Math.pow(1 - p, 3)) * 100);
              if (p < 1) raf = requestAnimationFrame(loop);
              else open.value = true;
            };
            raf = requestAnimationFrame(loop);
          }, { threshold: 0.25 });
          io.observe(el);
        });
        onBeforeUnmount(() => { io?.disconnect(); cancelAnimationFrame(raf); });
        const stage = ref<HTMLElement | null>(null);
        </script>

        <template>
          <div ref="stage" class="curt-stage" :data-open="open">
            <nav class="curt-in" style="--i: 0"><b>NORTHLIGHT</b></nav>
            <h2 class="curt-in" style="--i: 2">The curtain rises on your next interface</h2>
            <div class="curt-in" style="--i: 4"><button type="button">Start building</button></div>
            <div class="curt-mark" aria-hidden="true">NORTHLIGHT <b>{{ pct }}%</b></div>
            <i class="curt-panel curt-l" aria-hidden="true"></i>
            <i class="curt-panel curt-r" aria-hidden="true"></i>
          </div>
        </template>

        <style scoped>
        .curt-stage { position: relative; overflow: clip; border-radius: 1.25rem; display: grid; gap: 1rem;
                      align-content: center; padding: 2rem; min-block-size: 20rem; background: #fffdf9; }
        .curt-in { opacity: 0; translate: 0 18px; filter: blur(7px);
                   transition: opacity .55s var(--nx-spring-gentle), translate .55s var(--nx-spring-gentle),
                               filter .55s var(--nx-spring-gentle); }
        .curt-stage[data-open='true'] .curt-in { opacity: 1; translate: 0 0; filter: none;
                   transition-delay: calc(360ms + var(--i) * 95ms); }
        .curt-panel { position: absolute; inset-block: 0; inline-size: 50.5%; background: #1c1917;
                      transition: translate .9s var(--nx-spring-gentle); }
        .curt-l { inset-inline-start: 0; } .curt-r { inset-inline-end: 0; }
        .curt-stage[data-open='true'] .curt-l { translate: -102% 0; }
        .curt-stage[data-open='true'] .curt-r { translate: 102% 0; }
        .curt-mark { position: absolute; inset: 0; z-index: 2; display: grid; place-content: center;
                     justify-items: center; gap: .6rem; color: #f5f0e8; transition: opacity .4s, scale .4s; }
        .curt-stage[data-open='true'] .curt-mark { opacity: 0; scale: .92; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- CurtainReveal.svelte — a two-panel loading curtain with a percentage counter -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          let { speed = 2.4 } = $props();
          let open = $state(false);
          let pct = $state(0);
          let stage: HTMLElement;
          let io: IntersectionObserver | null = null;
          let raf = 0;

          onMount(() => {
            io = new IntersectionObserver(([e]) => {
              if (!e.isIntersecting || open) return;
              io?.disconnect();
              const t0 = performance.now();
              const loop = (n: number) => {
                const p = Math.min(1, (n - t0) / (speed * 1000));
                pct = Math.round((1 - Math.pow(1 - p, 3)) * 100);
                if (p < 1) raf = requestAnimationFrame(loop);
                else open = true;
              };
              raf = requestAnimationFrame(loop);
            }, { threshold: 0.25 });
            io.observe(stage);
            return () => { io?.disconnect(); cancelAnimationFrame(raf); };
          });
        </script>

        <div bind:this={stage} class="curt-stage" data-open={open}>
          <nav class="curt-in" style="--i: 0"><b>NORTHLIGHT</b></nav>
          <h2 class="curt-in" style="--i: 2">The curtain rises on your next interface</h2>
          <div class="curt-in" style="--i: 4"><button type="button">Start building</button></div>
          <div class="curt-mark" aria-hidden="true">NORTHLIGHT <b>{pct}%</b></div>
          <i class="curt-panel curt-l" aria-hidden="true"></i>
          <i class="curt-panel curt-r" aria-hidden="true"></i>
        </div>

        <style>
        .curt-stage { position: relative; overflow: clip; border-radius: 1.25rem; display: grid; gap: 1rem;
                      align-content: center; padding: 2rem; min-block-size: 20rem; background: #fffdf9; }
        .curt-in { opacity: 0; translate: 0 18px; filter: blur(7px);
                   transition: opacity .55s var(--nx-spring-gentle), translate .55s var(--nx-spring-gentle),
                               filter .55s var(--nx-spring-gentle); }
        .curt-stage[data-open='true'] .curt-in { opacity: 1; translate: 0 0; filter: none;
                   transition-delay: calc(360ms + var(--i) * 95ms); }
        .curt-panel { position: absolute; inset-block: 0; inline-size: 50.5%; background: #1c1917;
                      transition: translate .9s var(--nx-spring-gentle); }
        .curt-l { inset-inline-start: 0; } .curt-r { inset-inline-end: 0; }
        .curt-stage[data-open='true'] .curt-l { translate: -102% 0; }
        .curt-stage[data-open='true'] .curt-r { translate: 102% 0; }
        .curt-mark { position: absolute; inset: 0; z-index: 2; display: grid; place-content: center;
                     justify-items: center; gap: .6rem; color: #f5f0e8; transition: opacity .4s, scale .4s; }
        .curt-stage[data-open='true'] .curt-mark { opacity: 0; scale: .92; }
        </style>
        SVELTE,
        ],
    ],

    'stagger-list' => [
        'title' => ['fa' => 'ورود پله‌ای', 'en' => 'Stagger list'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'فهرست یا گریدی که آیتم‌هایش با تأخیر پله‌ای از پایین می‌رسند — ۲۲ پیکسل بالا آمدن، بلور نرم و کجی دو درجه روی فنر soft؛ با IntersectionObserver آغاز می‌شود، اسلایدر گام (۳۰ تا ۲۲۰ms) ضرب‌آهنگ را زنده عوض می‌کند و ری‌پلی موج را دوباره می‌فرستد.',
            'en' => 'A list or grid whose items arrive from below on a stepped delay — a 22px rise, a soft blur and a two-degree skew on the soft spring; IntersectionObserver starts it, the step slider (30–220ms) retunes the cadence live and replay sends the wave again.',
        ],
        'js' => true,
        'docs' => 'https://www.awwwards.com',
        'props' => [
            ['name' => 'step', 'type' => 'ms', 'default' => '80', 'note' => [
                'fa' => 'فاصلهٔ پله بین آیتم‌ها؛ اسلایدر ۳۰ تا ۲۲۰ms و اثرش بلافاصله در ری‌پلی دیده می‌شود.',
                'en' => 'The delay step between items; a 30–220ms slider whose effect shows on the very next replay.',
            ]],
            ['name' => 'from', 'type' => 'transform', 'default' => "'22px · blur(6px) · -2deg'", 'note' => [
                'fa' => 'حالت شروع هر آیتم: از پایین، کمی بلور و یک کجی ظریف — حس سرعتِ ورود.',
                'en' => 'Each item’s starting pose: below, slightly blurred, a hair skewed — the feel of arrival speed.',
            ]],
            ['name' => 'easing', 'type' => 'spring', 'default' => "'--nx-spring-soft'", 'note' => [
                'fa' => 'فنر استاندارد ریپو با مدت ۷۰۰ms؛ فرود نرم بدون جهش.',
                'en' => 'The repo’s standard soft spring at 700ms; a smooth landing without bounce.',
            ]],
            ['name' => 'trigger', 'type' => 'observer', 'default' => "'IO 0.2 · once'", 'note' => [
                'fa' => 'با دیده‌شدن ۲۰٪ فهرست شروع می‌شود و برای هر دید فقط یک‌بار؛ بیرون از دید انیمیشن رخ نمی‌دهد.',
                'en' => 'Starts when 20% of the list is visible and only once per view; nothing animates off screen.',
            ]],
            ['name' => 'replay', 'type' => 'method', 'default' => '()', 'note' => [
                'fa' => 'کلاس ورود خاموش و بعد دو فریم بعد روشن می‌شود تا موج بدون رفرش تکرار شود.',
                'en' => 'Toggles the entrance class off and back on two frames later, replaying the wave without a reload.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <ul class="wv-list" x-data="{
                on: false, io: null,
                init() {
                    this.io = new IntersectionObserver((e) => {
                        if (e[0].isIntersecting) { this.on = true; this.io.disconnect(); }
                    }, { threshold: 0.2 });
                    this.io.observe(this.$refs.list);
                },
                destroy() { this.io?.disconnect(); },
                wave() {
                    this.on = false;
                    requestAnimationFrame(() => requestAnimationFrame(() => (this.on = true)));
                },
            }" x-ref="list" :data-in="on" :style="'--wv-step: 80ms'">
            <li class="wv-item" style="--i: 0"><b>نشانگرهای لحظه‌ای</b><p>نشانگر و انتخاب هر هم‌تیمی، زنده.</p></li>
            <li class="wv-item" style="--i: 1"><b>پیش‌نویس آفلاین</b><p>در پرواز بنویسید، فرود ادغام کنید.</p></li>
            <li class="wv-item" style="--i: 2"><b>پالت فرمان ۲</b><p>جست‌وجوی فازی زیر ۴۰ms.</p></li>
            <button type="button" x-on:click="wave()">موج را دوباره بفرست</button>
        </ul>

        <style>
        .wv-list { display: grid; gap: .6rem; list-style: none; margin: 0; padding: 0; }
        .wv-item { display: grid; gap: .2rem; padding: .95rem 1rem; border: 1px solid #e7e5e4;
                   border-radius: .95rem; background: #fff;
                   opacity: 0; translate: 0 22px; filter: blur(6px); transform: skewY(-2deg);
                   transition: opacity .7s var(--nx-spring-soft), translate .7s var(--nx-spring-soft),
                               filter .7s var(--nx-spring-soft), transform .7s var(--nx-spring-soft); }
        .wv-list[data-in='true'] .wv-item { opacity: 1; translate: 0 0; filter: blur(0); transform: skewY(0);
                   transition-delay: calc(var(--i) * var(--wv-step)); }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <ul class="wv-list" x-data="{
                on: false, io: null,
                init() {
                    this.io = new IntersectionObserver((e) => {
                        if (e[0].isIntersecting) { this.on = true; this.io.disconnect(); }
                    }, { threshold: 0.2 });
                    this.io.observe(this.$refs.list);
                },
                destroy() { this.io?.disconnect(); },
                wave() {
                    this.on = false;
                    requestAnimationFrame(() => requestAnimationFrame(() => (this.on = true)));
                },
            }" x-ref="list" :data-in="on" :style="'--wv-step: 80ms'">
            <li class="wv-item" style="--i: 0"><b>Realtime cursors</b><p>Every teammate’s pointer, live.</p></li>
            <li class="wv-item" style="--i: 1"><b>Offline drafts</b><p>Write on the plane, merge on landing.</p></li>
            <li class="wv-item" style="--i: 2"><b>Command palette v2</b><p>Fuzzy search under 40ms.</p></li>
            <button type="button" x-on:click="wave()">Send the wave again</button>
        </ul>

        <style>
        .wv-list { display: grid; gap: .6rem; list-style: none; margin: 0; padding: 0; }
        .wv-item { display: grid; gap: .2rem; padding: .95rem 1rem; border: 1px solid #e7e5e4;
                   border-radius: .95rem; background: #fff;
                   opacity: 0; translate: 0 22px; filter: blur(6px); transform: skewY(-2deg);
                   transition: opacity .7s var(--nx-spring-soft), translate .7s var(--nx-spring-soft),
                               filter .7s var(--nx-spring-soft), transform .7s var(--nx-spring-soft); }
        .wv-list[data-in='true'] .wv-item { opacity: 1; translate: 0 0; filter: blur(0); transform: skewY(0);
                   transition-delay: calc(var(--i) * var(--wv-step)); }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/ReleaseNotes.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import ReleaseList from '@/components/ReleaseList';

        export default function ReleaseNotes() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <ReleaseList step={80} />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // ReleaseList.jsx — release notes that arrive in waves
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const ITEMS = [
          { id: 'cursors', title: 'Realtime cursors', desc: 'Every teammate’s pointer, live at 60fps.' },
          { id: 'drafts', title: 'Offline drafts', desc: 'Write on the plane; merge cleanly on landing.' },
          { id: 'palette', title: 'Command palette v2', desc: 'Fuzzy search across everything, under 40ms.' },
          { id: 'timeline', title: 'Version timeline', desc: 'Scrub any frame back through its history.' },
        ];

        export default function ReleaseList({ step = 80 }) {
          const [on, setOn] = useState(false);
          const listRef = useRef(null);

          // Two frames off → on: the pure-CSS wave replays without a remount.
          const wave = () => {
            setOn(false);
            requestAnimationFrame(() => requestAnimationFrame(() => setOn(true)));
          };

          useEffect(() => {
            const io = new IntersectionObserver(([e]) => {
              if (e.isIntersecting) { setOn(true); io.disconnect(); }
            }, { threshold: 0.2 });
            io.observe(listRef.current);
            return () => io.disconnect();
          }, []);

          return (
            <>
              <style>{`
                .wv-list { --wv-step: ${step}ms; display: grid; gap: .6rem; list-style: none; margin: 0; padding: 0;
                           grid-template-columns: repeat(2, minmax(0, 1fr)); }
                .wv-item { display: grid; gap: .2rem; padding: .95rem 1rem; border: 1px solid #e7e5e4;
                           border-radius: .95rem; background: #fff;
                           opacity: 0; translate: 0 22px; filter: blur(6px); transform: skewY(-2deg);
                           transition: opacity .7s var(--nx-spring-soft), translate .7s var(--nx-spring-soft),
                                       filter .7s var(--nx-spring-soft), transform .7s var(--nx-spring-soft); }
                .wv-list[data-in='true'] .wv-item { opacity: 1; translate: 0 0; filter: blur(0); transform: skewY(0);
                           transition-delay: calc(var(--i) * var(--wv-step)); }
                .wv-btn { justify-self: start; padding: .55rem 1.1rem; border-radius: 999px; cursor: pointer;
                          border: 1px solid #e7e5e4; background: #fff; font: 700 .8rem/1 inherit; }
                @media (prefers-reduced-motion: reduce) {
                  .wv-item { opacity: 1; translate: 0 0; filter: none; transform: none; transition: none; }
                }
              `}</style>
              <ul className="wv-list" data-in={on} ref={listRef}>
                {ITEMS.map((it, i) => (
                  <li className="wv-item" style={{ '--i': i }} key={it.id}>
                    <b>{it.title}</b>
                    <p>{it.desc}</p>
                  </li>
                ))}
              </ul>
              <button type="button" className="wv-btn" onClick={wave}>Send the wave again</button>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- ReleaseList.vue — release notes that arrive in waves -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const items = [
          { id: 'cursors', title: 'Realtime cursors', desc: 'Every teammate’s pointer, live at 60fps.' },
          { id: 'drafts', title: 'Offline drafts', desc: 'Write on the plane; merge cleanly on landing.' },
          { id: 'palette', title: 'Command palette v2', desc: 'Fuzzy search across everything, under 40ms.' },
          { id: 'timeline', title: 'Version timeline', desc: 'Scrub any frame back through its history.' },
        ];
        const on = ref(false);
        const list = ref<HTMLElement | null>(null);
        let io: IntersectionObserver | null = null;

        // Two frames off → on: the pure-CSS wave replays without a remount.
        const wave = () => {
          on.value = false;
          requestAnimationFrame(() => requestAnimationFrame(() => (on.value = true)));
        };

        onMounted(() => {
          io = new IntersectionObserver(([e]) => {
            if (e.isIntersecting) { on.value = true; io?.disconnect(); }
          }, { threshold: 0.2 });
          io.observe(list.value!);
        });
        onBeforeUnmount(() => io?.disconnect());
        </script>

        <template>
          <ul ref="list" class="wv-list" :data-in="on" :style="'--wv-step: 80ms'">
            <li v-for="(it, i) in items" :key="it.id" class="wv-item" :style="`--i: ${i}`">
              <b>{{ it.title }}</b>
              <p>{{ it.desc }}</p>
            </li>
          </ul>
          <button type="button" class="wv-btn" @click="wave">Send the wave again</button>
        </template>

        <style scoped>
        .wv-list { display: grid; gap: .6rem; list-style: none; margin: 0; padding: 0;
                   grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .wv-item { display: grid; gap: .2rem; padding: .95rem 1rem; border: 1px solid #e7e5e4;
                   border-radius: .95rem; background: #fff;
                   opacity: 0; translate: 0 22px; filter: blur(6px); transform: skewY(-2deg);
                   transition: opacity .7s var(--nx-spring-soft), translate .7s var(--nx-spring-soft),
                               filter .7s var(--nx-spring-soft), transform .7s var(--nx-spring-soft); }
        .wv-list[data-in='true'] .wv-item { opacity: 1; translate: 0 0; filter: blur(0); transform: skewY(0);
                   transition-delay: calc(var(--i) * var(--wv-step)); }
        .wv-btn { justify-self: start; padding: .55rem 1.1rem; border-radius: 999px; cursor: pointer;
                  border: 1px solid #e7e5e4; background: #fff; font: 700 .8rem/1 inherit; }
        @media (prefers-reduced-motion: reduce) {
          .wv-item { opacity: 1; translate: 0 0; filter: none; transform: none; transition: none; }
        }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- ReleaseList.svelte — release notes that arrive in waves -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          let { step = 80 } = $props();
          const items = [
            { id: 'cursors', title: 'Realtime cursors', desc: 'Every teammate’s pointer, live at 60fps.' },
            { id: 'drafts', title: 'Offline drafts', desc: 'Write on the plane; merge cleanly on landing.' },
            { id: 'palette', title: 'Command palette v2', desc: 'Fuzzy search across everything, under 40ms.' },
            { id: 'timeline', title: 'Version timeline', desc: 'Scrub any frame back through its history.' },
          ];
          let on = $state(false);
          let list: HTMLElement;
          let io: IntersectionObserver | null = null;

          // Two frames off → on: the pure-CSS wave replays without a remount.
          const wave = () => {
            on = false;
            requestAnimationFrame(() => requestAnimationFrame(() => (on = true)));
          };

          onMount(() => {
            io = new IntersectionObserver(([e]) => {
              if (e.isIntersecting) { on = true; io?.disconnect(); }
            }, { threshold: 0.2 });
            io.observe(list);
            return () => io?.disconnect();
          });
        </script>

        <ul bind:this={list} class="wv-list" data-in={on} style="--wv-step: {step}ms">
          {#each items as it, i (it.id)}
            <li class="wv-item" style="--i: {i}">
              <b>{it.title}</b>
              <p>{it.desc}</p>
            </li>
          {/each}
        </ul>
        <button type="button" class="wv-btn" onclick={wave}>Send the wave again</button>

        <style>
        .wv-list { display: grid; gap: .6rem; list-style: none; margin: 0; padding: 0;
                   grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .wv-item { display: grid; gap: .2rem; padding: .95rem 1rem; border: 1px solid #e7e5e4;
                   border-radius: .95rem; background: #fff;
                   opacity: 0; translate: 0 22px; filter: blur(6px); transform: skewY(-2deg);
                   transition: opacity .7s var(--nx-spring-soft), translate .7s var(--nx-spring-soft),
                               filter .7s var(--nx-spring-soft), transform .7s var(--nx-spring-soft); }
        .wv-list[data-in='true'] .wv-item { opacity: 1; translate: 0 0; filter: blur(0); transform: skewY(0);
                   transition-delay: calc(var(--i) * var(--wv-step)); }
        .wv-btn { justify-self: start; padding: .55rem 1.1rem; border-radius: 999px; cursor: pointer;
                  border: 1px solid #e7e5e4; background: #fff; font: 700 .8rem/1 inherit; }
        @media (prefers-reduced-motion: reduce) {
          .wv-item { opacity: 1; translate: 0 0; filter: none; transform: none; transition: none; }
        }
        </style>
        SVELTE,
        ],
    ],
];
