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

    'mask-lines' => [
        'title' => ['fa' => 'خط‌به‌خط ماسکی', 'en' => 'Masked line entrance'],
        'icon' => 'sparkles',
        'oneLiner' => [
            'fa' => 'هر خط تیتر در ماسک overflow-clip خودش از ۱۱۲٪ پایین با فنر gentle بالا می‌آید — هر خط ۱۳۰ms بعد از قبلی — و چیک، زیرتیتر و دکمه‌ها با محو-بلور نرم دنبال می‌کنند؛ ری‌پلی کل ورود و هاور هر خط برای بازپخش انفرادی؛ فارسی سطح واژه می‌ماند، هرگز حرف‌به‌حرف نمی‌شکند.',
            'en' => 'Every headline line rises out of its own overflow-clip mask from 112% below on the gentle spring — the next line 130ms after the last — then the eyebrow, sub and buttons follow with a softer blur-rise; replay the whole entrance or hover one line for a solo; Persian stays word-level, never letter-split.',
        ],
        'js' => true,
        'docs' => 'https://www.awwwards.com',
        'props' => [
            ['name' => 'from', 'type' => 'translate', 'default' => "'112% below'", 'note' => [
                'fa' => 'عمق شروع خط داخل ماسک؛ دقیقاً بیرونِ clip تا هیچ حرفی زودتر دیده نشود.',
                'en' => 'How deep the line starts inside the mask; just past the clip so no glyph peeks early.',
            ]],
            ['name' => 'curve', 'type' => 'easing', 'default' => "'--nx-spring-gentle'", 'note' => [
                'fa' => 'فنر استاندارد ریپو برای بالاآمدن ۸۵۰ms هر خط و دنبال‌کننده‌ها.',
                'en' => 'The repo’s standard spring behind the 850ms line rise and its followers.',
            ]],
            ['name' => 'lineStep', 'type' => 'ms', 'default' => '130', 'note' => [
                'fa' => 'فاصلهٔ بین خطوط؛ خط اول با ۱۲۰ms تأخیر شروع می‌کند.',
                'en' => 'The gap between lines; the first one starts 120ms in.',
            ]],
            ['name' => 'follow', 'type' => 'ms', 'default' => "'520 + 120·i'", 'note' => [
                'fa' => 'چیک، زیرتیتر و دکمه‌ها بعد از خطوط با بلور نرم‌تر می‌رسند.',
                'en' => 'The eyebrow, sub and buttons arrive after the lines with a softer blur.',
            ]],
            ['name' => 'solo', 'type' => 'event', 'default' => "'pointerenter'", 'note' => [
                'fa' => 'هاور روی هر خط فقط همان خط را دوباره اجرا می‌کند.',
                'en' => 'Hovering a line replays just that line, not the whole headline.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="ml-hero" x-data="{
                live: false, io: null,
                init() {
                    this.io = new IntersectionObserver((e) => {
                        if (e[0].isIntersecting) { this.play(); this.io.disconnect(); }
                    }, { threshold: 0.3 });
                    this.io.observe(this.$refs.hero);
                },
                destroy() { this.io?.disconnect(); },
                play() {
                    this.live = false;
                    requestAnimationFrame(() => requestAnimationFrame(() => (this.live = true)));
                },
            }" x-ref="hero" :data-live="live">
            <h2 class="ml-h1">
                <span class="ml-mask"><span class="ml-line" style="--l: 0">نرم‌افزارِ <i>آرام</i></span></span>
                <span class="ml-mask"><span class="ml-line" style="--l: 1">برای تیم‌های <i>پرصدا</i>.</span></span>
            </h2>
            <p class="ml-sub">تقویمی که جلسات را می‌فهمد و تمرکز را محافظت می‌کند.</p>
            <div class="ml-cta"><button type="button">دمو رزرو کن</button></div>
            <button type="button" class="ml-again" x-on:click="play()">دوباره بالا بیا</button>
        </div>

        <style>
        .ml-hero { display: grid; gap: 1rem; padding: 2rem; border-radius: 1.25rem; background: #fafafa; }
        .ml-mask { display: block; overflow: clip; padding-block-end: .14em; margin-block-end: -.14em; }
        .ml-line { display: inline-block; font-size: 2rem; font-weight: 800; translate: 0 112%;
                   transition: translate .85s var(--nx-spring-gentle);
                   transition-delay: calc(120ms + var(--l) * 130ms); }
        .ml-hero[data-live='true'] .ml-line { translate: 0 0; }
        .ml-line i { font-style: italic; color: #4338ca; }
        .ml-sub, .ml-cta { opacity: 0; translate: 0 14px; filter: blur(5px);
            transition: opacity .6s var(--nx-spring-gentle), translate .6s var(--nx-spring-gentle),
                        filter .6s var(--nx-spring-gentle); transition-delay: 520ms; }
        .ml-hero[data-live='true'] .ml-sub, .ml-hero[data-live='true'] .ml-cta { opacity: 1; translate: 0 0; filter: blur(0); }
        @media (prefers-reduced-motion: reduce) {
            .ml-line, .ml-sub, .ml-cta { transition: none; translate: 0 0; opacity: 1; filter: none; }
        }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="ml-hero" x-data="{
                live: false, io: null,
                init() {
                    this.io = new IntersectionObserver((e) => {
                        if (e[0].isIntersecting) { this.play(); this.io.disconnect(); }
                    }, { threshold: 0.3 });
                    this.io.observe(this.$refs.hero);
                },
                destroy() { this.io?.disconnect(); },
                play() {
                    this.live = false;
                    requestAnimationFrame(() => requestAnimationFrame(() => (this.live = true)));
                },
            }" x-ref="hero" :data-live="live">
            <h2 class="ml-h1">
                <span class="ml-mask"><span class="ml-line" style="--l: 0">We make <i>quiet</i> software</span></span>
                <span class="ml-mask"><span class="ml-line" style="--l: 1">for loud teams.</span></span>
            </h2>
            <p class="ml-sub">A calendar that understands meetings and defends focus.</p>
            <div class="ml-cta"><button type="button">Book a demo</button></div>
            <button type="button" class="ml-again" x-on:click="play()">Rise again</button>
        </div>

        <style>
        .ml-hero { display: grid; gap: 1rem; padding: 2rem; border-radius: 1.25rem; background: #fafafa; }
        .ml-mask { display: block; overflow: clip; padding-block-end: .14em; margin-block-end: -.14em; }
        .ml-line { display: inline-block; font-size: 2rem; font-weight: 800; translate: 0 112%;
                   transition: translate .85s var(--nx-spring-gentle);
                   transition-delay: calc(120ms + var(--l) * 130ms); }
        .ml-hero[data-live='true'] .ml-line { translate: 0 0; }
        .ml-line i { font-style: italic; color: #4338ca; }
        .ml-sub, .ml-cta { opacity: 0; translate: 0 14px; filter: blur(5px);
            transition: opacity .6s var(--nx-spring-gentle), translate .6s var(--nx-spring-gentle),
                        filter .6s var(--nx-spring-gentle); transition-delay: 520ms; }
        .ml-hero[data-live='true'] .ml-sub, .ml-hero[data-live='true'] .ml-cta { opacity: 1; translate: 0 0; filter: blur(0); }
        @media (prefers-reduced-motion: reduce) {
            .ml-line, .ml-sub, .ml-cta { transition: none; translate: 0 0; opacity: 1; filter: none; }
        }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Hero.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import MaskedLines from '@/components/MaskedLines';

        export default function Hero() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <MaskedLines
                  lines={[
                    { pre: 'We make ', accent: 'quiet', post: ' software' },
                    { pre: 'for loud teams.', accent: '', post: '' },
                  ]}
                />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // MaskedLines.jsx — every headline line rises out of its own overflow-clip mask
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        export default function MaskedLines({ lines = [] }) {
          const [live, setLive] = useState(false);
          const heroRef = useRef(null);

          // Once per mount: when the hero is 30% in view, send the lines up.
          useEffect(() => {
            let done = false;
            const io = new IntersectionObserver(([e]) => {
              if (!e.isIntersecting || done) return;
              done = true;
              io.disconnect();
              requestAnimationFrame(() => requestAnimationFrame(() => setLive(true)));
            }, { threshold: 0.3 });
            io.observe(heroRef.current);
            return () => io.disconnect();
          }, []);

          const play = () => {
            setLive(false);
            requestAnimationFrame(() => requestAnimationFrame(() => setLive(true)));
          };

          return (
            <>
              <style>{`
                .mlh-hero { display: grid; gap: 1rem; padding: 2rem; border-radius: 1.25rem; background: #fafafa; }
                .mlh-mask { display: block; overflow: clip; padding-block-end: .14em; margin-block-end: -.14em; }
                .mlh-line { display: inline-block; font-size: 2rem; font-weight: 800; translate: 0 112%;
                            transition: translate .85s var(--nx-spring-gentle);
                            transition-delay: calc(120ms + var(--l) * 130ms); }
                .mlh-hero[data-live='true'] .mlh-line { translate: 0 0; }
                .mlh-line em { font-style: italic; color: #4338ca; }
                .mlh-sub, .mlh-cta { opacity: 0; translate: 0 14px; filter: blur(5px);
                  transition: opacity .6s var(--nx-spring-gentle), translate .6s var(--nx-spring-gentle),
                              filter .6s var(--nx-spring-gentle); transition-delay: 520ms; }
                .mlh-hero[data-live='true'] .mlh-sub, .mlh-hero[data-live='true'] .mlh-cta { opacity: 1; translate: 0 0; filter: blur(0); }
                @media (prefers-reduced-motion: reduce) {
                  .mlh-line, .mlh-sub, .mlh-cta { transition: none; translate: 0 0; opacity: 1; filter: none; }
                }
              `}</style>
              <div ref={heroRef} className="mlh-hero" data-live={live}>
                <h2 className="mlh-h1" style={{ margin: 0 }}>
                  {lines.map((l, i) => (
                    <span key={i} className="mlh-mask">
                      <span className="mlh-line" style={{ '--l': i }}>
                        {l.pre}<em>{l.accent}</em>{l.post}
                      </span>
                    </span>
                  ))}
                </h2>
                <p className="mlh-sub" style={{ margin: 0 }}>
                  A calendar that understands meetings and defends focus.
                </p>
                <div className="mlh-cta"><button type="button">Book a demo</button></div>
                <button type="button" onClick={play}>Rise again</button>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- MaskedLines.vue — every headline line rises out of its own overflow-clip mask -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const props = defineProps<{ lines?: Array<{ pre: string; accent: string; post: string }> }>();
        const live = ref(false);
        const hero = ref<HTMLElement | null>(null);
        let io: IntersectionObserver | null = null;

        onMounted(() => {
          io = new IntersectionObserver(([e]) => {
            if (!e.isIntersecting || live.value) return;
            io?.disconnect();
            requestAnimationFrame(() => requestAnimationFrame(() => (live.value = true)));
          }, { threshold: 0.3 });
          io.observe(hero.value!);
        });
        onBeforeUnmount(() => io?.disconnect());

        function play() {
          live.value = false;
          requestAnimationFrame(() => requestAnimationFrame(() => (live.value = true)));
        }
        </script>

        <template>
          <div ref="hero" class="mlh-hero" :data-live="live">
            <h2 class="mlh-h1">
              <span v-for="(l, i) in lines" :key="i" class="mlh-mask">
                <span class="mlh-line" :style="{ '--l': i }">{{ l.pre }}<em>{{ l.accent }}</em>{{ l.post }}</span>
              </span>
            </h2>
            <p class="mlh-sub">A calendar that understands meetings and defends focus.</p>
            <div class="mlh-cta"><button type="button">Book a demo</button></div>
            <button type="button" @click="play">Rise again</button>
          </div>
        </template>

        <style scoped>
        .mlh-hero { display: grid; gap: 1rem; padding: 2rem; border-radius: 1.25rem; background: #fafafa; }
        .mlh-mask { display: block; overflow: clip; padding-block-end: .14em; margin-block-end: -.14em; }
        .mlh-line { display: inline-block; font-size: 2rem; font-weight: 800; translate: 0 112%;
                    transition: translate .85s var(--nx-spring-gentle);
                    transition-delay: calc(120ms + var(--l) * 130ms); }
        .mlh-hero[data-live='true'] .mlh-line { translate: 0 0; }
        .mlh-line :deep(em) { font-style: italic; color: #4338ca; }
        .mlh-sub, .mlh-cta { opacity: 0; translate: 0 14px; filter: blur(5px);
          transition: opacity .6s var(--nx-spring-gentle), translate .6s var(--nx-spring-gentle),
                      filter .6s var(--nx-spring-gentle); transition-delay: 520ms; }
        .mlh-hero[data-live='true'] .mlh-sub, .mlh-hero[data-live='true'] .mlh-cta { opacity: 1; translate: 0 0; filter: blur(0); }
        @media (prefers-reduced-motion: reduce) {
          .mlh-line, .mlh-sub, .mlh-cta { transition: none; translate: 0 0; opacity: 1; filter: none; }
        }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- MaskedLines.svelte — every headline line rises out of its own overflow-clip mask -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          let { lines = [] } = $props();
          let live = $state(false);
          let hero: HTMLElement;
          let io: IntersectionObserver | null = null;

          onMount(() => {
            io = new IntersectionObserver(([e]) => {
              if (!e.isIntersecting || live) return;
              io?.disconnect();
              requestAnimationFrame(() => requestAnimationFrame(() => (live = true)));
            }, { threshold: 0.3 });
            io.observe(hero);
            return () => io?.disconnect();
          });

          function play() {
            live = false;
            requestAnimationFrame(() => requestAnimationFrame(() => (live = true)));
          }
        </script>

        <div bind:this={hero} class="mlh-hero" data-live={live}>
          <h2 class="mlh-h1">
            {#each lines as l, i}
              <span class="mlh-mask">
                <span class="mlh-line" style="--l: {i}">{l.pre}<em>{l.accent}</em>{l.post}</span>
              </span>
            {/each}
          </h2>
          <p class="mlh-sub">A calendar that understands meetings and defends focus.</p>
          <div class="mlh-cta"><button type="button">Book a demo</button></div>
          <button type="button" onclick={play}>Rise again</button>
        </div>

        <style>
        .mlh-hero { display: grid; gap: 1rem; padding: 2rem; border-radius: 1.25rem; background: #fafafa; }
        .mlh-mask { display: block; overflow: clip; padding-block-end: .14em; margin-block-end: -.14em; }
        .mlh-line { display: inline-block; font-size: 2rem; font-weight: 800; translate: 0 112%;
                    transition: translate .85s var(--nx-spring-gentle);
                    transition-delay: calc(120ms + var(--l) * 130ms); }
        .mlh-hero[data-live='true'] .mlh-line { translate: 0 0; }
        .mlh-line :global(em) { font-style: italic; color: #4338ca; }
        .mlh-sub, .mlh-cta { opacity: 0; translate: 0 14px; filter: blur(5px);
          transition: opacity .6s var(--nx-spring-gentle), translate .6s var(--nx-spring-gentle),
                      filter .6s var(--nx-spring-gentle); transition-delay: 520ms; }
        .mlh-hero[data-live='true'] .mlh-sub, .mlh-hero[data-live='true'] .mlh-cta { opacity: 1; translate: 0 0; filter: blur(0); }
        @media (prefers-reduced-motion: reduce) {
          .mlh-line, .mlh-sub, .mlh-cta { transition: none; translate: 0 0; opacity: 1; filter: none; }
        }
        </style>
        SVELTE,
        ],
    ],

    'scale-in-stage' => [
        'title' => ['fa' => 'ورود سینمایی', 'en' => 'Scale-in stage'],
        'icon' => 'play',
        'oneLiner' => [
            'fa' => 'روایت سه‌پرده‌ای روی استیج لترباکس: هر پرده مثل شاتِ از دور وارد فوکوس می‌شود — scale ۰٫۹۲→۱ با محو و بلورِ سوختنی روی فنر soft، به‌محض ۳۵٪ واردشدن به دید؛ ری‌پلی کل ریل را با پلهٔ ۱۲۰ms دوباره اجرا می‌کند و پرده‌های بیرون دید منتظر اسکرول می‌مانند.',
            'en' => 'A three-act story on a letterboxed stage: each act enters like a shot pulled into focus — scale .92 → 1 with an opacity fade and a blur burning off on the soft spring, the moment it scrolls 35% into view; replay runs the whole reel again at a 120ms stagger while off-screen acts keep waiting for their scroll cue.',
        ],
        'js' => true,
        'docs' => 'https://www.awwwards.com',
        'props' => [
            ['name' => 'from', 'type' => 'transform', 'default' => "'scale .92 · blur(9px)'", 'note' => [
                'fa' => 'حالت شروع هر پرده: کمی دور، محو و خارج از فوکوس.',
                'en' => 'Each act’s starting pose: a touch far, faded and out of focus.',
            ]],
            ['name' => 'curve', 'type' => 'easing', 'default' => "'--nx-spring-soft'", 'note' => [
                'fa' => 'فنر استاندارد ریپو برای ورود ۹۰۰ms هر پرده.',
                'en' => 'The repo’s standard spring behind each act’s 900ms entrance.',
            ]],
            ['name' => 'stagger', 'type' => 'ms', 'default' => '120', 'note' => [
                'fa' => 'فاصلهٔ ری‌پلی پرده‌های روی صفحه؛ پرده j با تأخیر ۱۲۰×j.',
                'en' => 'The replay gap between on-screen acts; act j waits 120 × j.',
            ]],
            ['name' => 'threshold', 'type' => 'ratio', 'default' => '0.35', 'note' => [
                'fa' => 'هر پرده خودش مشاهده می‌شود؛ با ۳۵٪ حضور به دید، وارد می‌شود.',
                'en' => 'Every act is observed on its own; it enters at 35% visibility.',
            ]],
            ['name' => 'acts', 'type' => 'count', 'default' => '3', 'note' => [
                'fa' => 'پرده‌ها محتوای واقعی داستان محصول‌اند، نه جان‌پانل.',
                'en' => 'The acts carry the real product story, not filler panels.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="sis-stage" x-data="{
                shown: [false, false, false], io: null,
                init() {
                    this.io = new IntersectionObserver((entries) => {
                        entries.forEach((e) => {
                            if (e.isIntersecting) this.shown[Number(e.target.dataset.act)] = true;
                        });
                    }, { threshold: 0.35 });
                    this.$refs.acts.querySelectorAll('[data-act]').forEach((el) => this.io.observe(el));
                },
                destroy() { this.io?.disconnect(); },
                replay() {
                    this.shown = [false, false, false];
                    requestAnimationFrame(() => requestAnimationFrame(() => {
                        this.$refs.acts.querySelectorAll('[data-act]').forEach((el) => {
                            const r = el.getBoundingClientRect();
                            if (r.top < innerHeight * 0.7 && r.bottom > innerHeight * 0.3) {
                                this.shown[Number(el.dataset.act)] = true;
                            }
                        });
                    }));
                },
            }">
            <div class="sis-acts" x-ref="acts">
                <article class="sis-act" data-act="0" x-bind:data-in="shown[0] ? 'true' : 'false'" style="--j: 0">
                    <h4>اتصالِ ازدست‌رفته</h4>
                    <p>ایستگاه مرکزی زوریخ، ۰۳:۱۲ — بنیان‌گذار ما قطار شب میلان را از دست داد.</p>
                </article>
                <article class="sis-act" data-act="1" x-bind:data-in="shown[1] ? 'true' : 'false'" style="--j: 1">
                    <h4>همهٔ جدول‌های زمانی، یک نقشه</h4>
                    <p>فید زندهٔ ۳۱ اپراتور ریلی اروپا در یک گراف اتصال.</p>
                </article>
                <article class="sis-act" data-act="2" x-bind:data-in="shown[2] ? 'true' : 'false'" style="--j: 2">
                    <h4>رزروی که سفر را تمام می‌کند</h4>
                    <p>کل مسیر روی یک بلیت — در تا در، با بازمسیری زنده.</p>
                </article>
            </div>
            <button type="button" x-on:click="replay()">ریل را دوباره اجرا کن</button>
        </div>

        <style>
        .sis-stage { display: grid; gap: 1rem; padding: 1.5rem; border-radius: 1.25rem; background: #fffdf9; }
        .sis-acts { display: grid; gap: 1rem; }
        .sis-act { padding: 1rem; border: 1px solid #e7e5e4; border-radius: 1rem; background: #fff;
                   opacity: 0; scale: .92; filter: blur(9px);
                   transition: opacity .9s var(--nx-spring-soft), scale .9s var(--nx-spring-soft),
                               filter .9s var(--nx-spring-soft);
                   transition-delay: calc(var(--j) * 120ms); }
        .sis-act[data-in='true'] { opacity: 1; scale: 1; filter: blur(0); }
        .sis-act:not([data-in='true']) { transition: none; }
        @media (prefers-reduced-motion: reduce) {
            .sis-act { opacity: 1; scale: 1; filter: none; transition: none; }
        }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="sis-stage" x-data="{
                shown: [false, false, false], io: null,
                init() {
                    this.io = new IntersectionObserver((entries) => {
                        entries.forEach((e) => {
                            if (e.isIntersecting) this.shown[Number(e.target.dataset.act)] = true;
                        });
                    }, { threshold: 0.35 });
                    this.$refs.acts.querySelectorAll('[data-act]').forEach((el) => this.io.observe(el));
                },
                destroy() { this.io?.disconnect(); },
                replay() {
                    this.shown = [false, false, false];
                    requestAnimationFrame(() => requestAnimationFrame(() => {
                        this.$refs.acts.querySelectorAll('[data-act]').forEach((el) => {
                            const r = el.getBoundingClientRect();
                            if (r.top < innerHeight * 0.7 && r.bottom > innerHeight * 0.3) {
                                this.shown[Number(el.dataset.act)] = true;
                            }
                        });
                    }));
                },
            }">
            <div class="sis-acts" x-ref="acts">
                <article class="sis-act" data-act="0" x-bind:data-in="shown[0] ? 'true' : 'false'" style="--j: 0">
                    <h4>The missed connection</h4>
                    <p>Zürich HB, 03:12 — our founder missed the night train to Milan.</p>
                </article>
                <article class="sis-act" data-act="1" x-bind:data-in="shown[1] ? 'true' : 'false'" style="--j: 1">
                    <h4>Every timetable, one map</h4>
                    <p>Live feeds from 31 European rail operators in a single graph.</p>
                </article>
                <article class="sis-act" data-act="2" x-bind:data-in="shown[2] ? 'true' : 'false'" style="--j: 2">
                    <h4>Booking that finishes the trip</h4>
                    <p>The whole journey on one ticket — door to door, re-planned live.</p>
                </article>
            </div>
            <button type="button" x-on:click="replay()">Run the reel again</button>
        </div>

        <style>
        .sis-stage { display: grid; gap: 1rem; padding: 1.5rem; border-radius: 1.25rem; background: #fffdf9; }
        .sis-acts { display: grid; gap: 1rem; }
        .sis-act { padding: 1rem; border: 1px solid #e7e5e4; border-radius: 1rem; background: #fff;
                   opacity: 0; scale: .92; filter: blur(9px);
                   transition: opacity .9s var(--nx-spring-soft), scale .9s var(--nx-spring-soft),
                               filter .9s var(--nx-spring-soft);
                   transition-delay: calc(var(--j) * 120ms); }
        .sis-act[data-in='true'] { opacity: 1; scale: 1; filter: blur(0); }
        .sis-act:not([data-in='true']) { transition: none; }
        @media (prefers-reduced-motion: reduce) {
            .sis-act { opacity: 1; scale: 1; filter: none; transition: none; }
        }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Story.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import ScaleInStage from '@/components/ScaleInStage';

        export default function Story() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <ScaleInStage
                  acts={[
                    { title: 'The missed connection', body: 'Zürich HB, 03:12 — our founder missed the night train to Milan.' },
                    { title: 'Every timetable, one map', body: 'Live feeds from 31 European rail operators in a single graph.' },
                    { title: 'Booking that finishes the trip', body: 'The whole journey on one ticket — door to door, re-planned live.' },
                  ]}
                />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // ScaleInStage.jsx — acts that pull into focus as each scrolls into view
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        export default function ScaleInStage({ acts = [] }) {
          const [inView, setInView] = useState(() => acts.map(() => false));
          const actsRef = useRef(null);

          // One observer, many acts: each element flips its own index once
          // it crosses 35% visibility; cleanup on unmount.
          useEffect(() => {
            const io = new IntersectionObserver((entries) => {
              entries.forEach((e) => {
                if (!e.isIntersecting) return;
                const i = Number(e.target.dataset.act);
                setInView((prev) => (prev[i] ? prev : prev.map((v, k) => (k === i ? true : v))));
                io.unobserve(e.target);
              });
            }, { threshold: 0.35 });
            actsRef.current.querySelectorAll('[data-act]').forEach((el) => io.observe(el));
            return () => io.disconnect();
          }, []);

          const replay = () => {
            setInView(acts.map(() => false));
            requestAnimationFrame(() => requestAnimationFrame(() => {
              actsRef.current.querySelectorAll('[data-act]').forEach((el) => {
                const r = el.getBoundingClientRect();
                if (r.top < innerHeight * 0.7 && r.bottom > innerHeight * 0.3) {
                  const i = Number(el.dataset.act);
                  setInView((prev) => prev.map((v, k) => (k === i ? true : v)));
                }
              });
            }));
          };

          return (
            <>
              <style>{`
                .sis-stage { display: grid; gap: 1rem; padding: 1.5rem; border-radius: 1.25rem; background: #fffdf9; }
                .sis-acts { display: grid; gap: 1rem; }
                .sis-act { padding: 1rem; border: 1px solid #e7e5e4; border-radius: 1rem; background: #fff;
                           opacity: 0; scale: .92; filter: blur(9px);
                           transition: opacity .9s var(--nx-spring-soft), scale .9s var(--nx-spring-soft),
                                       filter .9s var(--nx-spring-soft);
                           transition-delay: calc(var(--j) * 120ms); }
                .sis-act[data-in='true'] { opacity: 1; scale: 1; filter: blur(0); }
                @media (prefers-reduced-motion: reduce) {
                  .sis-act { opacity: 1; scale: 1; filter: none; transition: none; }
                }
              `}</style>
              <div className="sis-stage">
                <div className="sis-acts" ref={actsRef}>
                  {acts.map((a, i) => (
                    <article key={i} className="sis-act" data-act={i} data-in={inView[i]} style={{ '--j': i }}>
                      <h4 style={{ margin: '0 0 .35rem' }}>{a.title}</h4>
                      <p style={{ margin: 0, color: '#78716c' }}>{a.body}</p>
                    </article>
                  ))}
                </div>
                <button type="button" onClick={replay}>Run the reel again</button>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- ScaleInStage.vue — acts that pull into focus as each scrolls into view -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const props = defineProps<{ acts?: Array<{ title: string; body: string }> }>();
        const inView = ref<boolean[]>([]);
        const actsEl = ref<HTMLElement | null>(null);
        let io: IntersectionObserver | null = null;

        onMounted(() => {
          inView.value = (props.acts ?? []).map(() => false);
          io = new IntersectionObserver((entries) => {
            entries.forEach((e) => {
              if (!e.isIntersecting) return;
              const i = Number((e.target as HTMLElement).dataset.act);
              inView.value[i] = true;
              io?.unobserve(e.target);
            });
          }, { threshold: 0.35 });
          actsEl.value!.querySelectorAll('[data-act]').forEach((el) => io!.observe(el));
        });
        onBeforeUnmount(() => io?.disconnect());

        function replay() {
          inView.value = (props.acts ?? []).map(() => false);
          requestAnimationFrame(() => requestAnimationFrame(() => {
            actsEl.value!.querySelectorAll('[data-act]').forEach((el) => {
              const r = (el as HTMLElement).getBoundingClientRect();
              if (r.top < innerHeight * 0.7 && r.bottom > innerHeight * 0.3) {
                inView.value[Number((el as HTMLElement).dataset.act)] = true;
              }
            });
          }));
        }
        </script>

        <template>
          <div class="sis-stage">
            <div class="sis-acts" ref="actsEl">
              <article v-for="(a, i) in acts" :key="i" class="sis-act" :data-act="i"
                       :data-in="inView[i] ? 'true' : 'false'" :style="{ '--j': i }">
                <h4>{{ a.title }}</h4>
                <p>{{ a.body }}</p>
              </article>
            </div>
            <button type="button" @click="replay">Run the reel again</button>
          </div>
        </template>

        <style scoped>
        .sis-stage { display: grid; gap: 1rem; padding: 1.5rem; border-radius: 1.25rem; background: #fffdf9; }
        .sis-acts { display: grid; gap: 1rem; }
        .sis-act { padding: 1rem; border: 1px solid #e7e5e4; border-radius: 1rem; background: #fff;
                   opacity: 0; scale: .92; filter: blur(9px);
                   transition: opacity .9s var(--nx-spring-soft), scale .9s var(--nx-spring-soft),
                               filter .9s var(--nx-spring-soft);
                   transition-delay: calc(var(--j) * 120ms); }
        .sis-act[data-in='true'] { opacity: 1; scale: 1; filter: blur(0); }
        @media (prefers-reduced-motion: reduce) {
          .sis-act { opacity: 1; scale: 1; filter: none; transition: none; }
        }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- ScaleInStage.svelte — acts that pull into focus as each scrolls into view -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          let { acts = [] } = $props();
          let inView = $state<boolean[]>(acts.map(() => false));
          let actsEl: HTMLElement;
          let io: IntersectionObserver | null = null;

          onMount(() => {
            io = new IntersectionObserver((entries) => {
              entries.forEach((e) => {
                if (!e.isIntersecting) return;
                const i = Number((e.target as HTMLElement).dataset.act);
                inView[i] = true;
                io?.unobserve(e.target);
              });
            }, { threshold: 0.35 });
            actsEl.querySelectorAll('[data-act]').forEach((el) => io!.observe(el));
            return () => io?.disconnect();
          });

          function replay() {
            inView = acts.map(() => false);
            requestAnimationFrame(() => requestAnimationFrame(() => {
              actsEl.querySelectorAll('[data-act]').forEach((el) => {
                const r = (el as HTMLElement).getBoundingClientRect();
                if (r.top < innerHeight * 0.7 && r.bottom > innerHeight * 0.3) {
                  inView[Number((el as HTMLElement).dataset.act)] = true;
                }
              });
            }));
          }
        </script>

        <div class="sis-stage">
          <div class="sis-acts" bind:this={actsEl}>
            {#each acts as a, i}
              <article class="sis-act" data-act={i} data-in={inView[i]} style="--j: {i}">
                <h4>{a.title}</h4>
                <p>{a.body}</p>
              </article>
            {/each}
          </div>
          <button type="button" onclick={replay}>Run the reel again</button>
        </div>

        <style>
        .sis-stage { display: grid; gap: 1rem; padding: 1.5rem; border-radius: 1.25rem; background: #fffdf9; }
        .sis-acts { display: grid; gap: 1rem; }
        .sis-act { padding: 1rem; border: 1px solid #e7e5e4; border-radius: 1rem; background: #fff;
                   opacity: 0; scale: .92; filter: blur(9px);
                   transition: opacity .9s var(--nx-spring-soft), scale .9s var(--nx-spring-soft),
                               filter .9s var(--nx-spring-soft);
                   transition-delay: calc(var(--j) * 120ms); }
        .sis-act[data-in='true'] { opacity: 1; scale: 1; filter: blur(0); }
        @media (prefers-reduced-motion: reduce) {
          .sis-act { opacity: 1; scale: 1; filter: none; transition: none; }
        }
        </style>
        SVELTE,
        ],
    ],

    'split-flap' => [
        'title' => ['fa' => 'تابلوی اسپلیت‌فلپ', 'en' => 'Split-flap board'],
        'icon' => 'globe',
        'oneLiner' => [
            'fa' => 'تابلوی حرکت به سبک سولاری: هر حرف دو نیم‌کرهٔ بالا/پایین و دو برگِ افتان روی یک لولا دارد — برگ بالا ۱۳۰ms پایین می‌چرخد و برگ پایین ۱۰۵ms صاف می‌نشیند؛ صدایی نه، فقط easing مکانیکی. با تایپ، تابلو آبشاری دوباره هجی می‌شود و ساعت زنده فقط رقم‌های عوض‌شده را می‌چرخاند؛ بیرون دید ساعت پارک می‌شود و الفبای لاتینِ سخت‌افزار یعنی فارسی هرگز حرف‌به‌حرف نمی‌شکند.',
            'en' => 'A Solari-style departure board: every character is a top and a bottom half with two falling leaves on one hinge — the top leaf swings down in 130ms and the bottom settles flat 105ms later; no sound, just mechanical easing. Typing re-spells the board in a cascade and the live clock flips only the digits that changed; off-screen the clock parks and the Latin hardware charset keeps Persian from ever letter-splitting.',
        ],
        'js' => true,
        'docs' => 'https://www.awwwards.com',
        'props' => [
            ['name' => 'cells', 'type' => 'count', 'default' => '10 + 8', 'note' => [
                'fa' => 'ردیف پیام ۱۰ خانه و ردیف ساعت ۸ خانه؛ خانه‌ها شاره‌اند و در ۳۷۵px هم سرریز نمی‌دهند.',
                'en' => 'Ten cells for the message row, eight for the clock; cells are fluid and never overflow at 375px.',
            ]],
            ['name' => 'flip', 'type' => 'ms', 'default' => "'130 + 105'", 'note' => [
                'fa' => 'دو نیم‌چرخش rotateX روی یک لولا؛ لبهٔ تیز بیرون‌رفتن و نشستن نرم، بدون هیچ صدا.',
                'en' => 'Two rotateX half-turns on one hinge; a sharp break-out and a soft settle, no sound at all.',
            ]],
            ['name' => 'cascade', 'type' => 'ms', 'default' => "'55 · i'", 'note' => [
                'fa' => 'خانهٔ iام با تأخیر ۵۵×i شروع می‌کند؛ ساعت با گام ریزتر ۲۸ms می‌چرخد.',
                'en' => 'Cell i starts 55 × i late; the clock ticks on a finer 28ms step.',
            ]],
            ['name' => 'clock', 'type' => 'poll', 'default' => "'250ms · IO-gated'", 'note' => [
                'fa' => 'ساعت هر ۲۵۰ms دیده می‌شود ولی بیرون از دید به‌کلی پارک می‌شود؛ فقط رقم تغییرکرده ورودی دارد.',
                'en' => 'The clock polls every 250ms but parks entirely off-screen; only changed digits get enqueued.',
            ]],
            ['name' => 'charset', 'type' => 'set', 'default' => "'A–Z 0–9 : . - · +'", 'note' => [
                'fa' => 'مثل سخت‌افزار واقعی فقط لاتین بزرگ؛ بقیهٔ نویسه‌ها فاصله می‌شوند تا خط فارسی ننشکند.',
                'en' => 'Uppercase Latin only, like the real hardware; anything else becomes a space so no script ever breaks.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="flp-board" x-data="{
                text: 'BON VOYAGE', clock: '00:00:00', visible: true,
                io: null, timer: 0, timers: [],
                init() {
                    this.io = new IntersectionObserver((e) => { this.visible = e[0].isIntersecting; }, { threshold: 0.2 });
                    this.io.observe(this.$refs.board);
                    this.timer = setInterval(() => this.tick(), 250);
                },
                destroy() {
                    this.io?.disconnect();
                    clearInterval(this.timer);
                    this.timers.forEach(clearTimeout); this.timers = [];
                },
                tick() {
                    if (!this.visible) return;
                    const s = new Date().toLocaleTimeString('en-GB', { hour12: false });
                    if (s === this.clock) return;
                    this.clock = s;
                    [...this.$refs.clock.children].forEach((el, i) => this.setCell(el, s[i], 28 * i));
                },
                retype(v) {
                    const t = String(v ?? '').toUpperCase().replace(/[^A-Z0-9 :.\-·+]/g, ' ').slice(0, 10).padEnd(10, ' ');
                    this.text = t;
                    this.timers.forEach(clearTimeout); this.timers = [];
                    [...this.$refs.msg.children].forEach((el, i) => this.setCell(el, t[i], 55 * i));
                },
                setCell(el, ch, delay) {
                    this.timers.push(setTimeout(() => this.flip(el, ch), delay));
                },
                flip(el, ch) {
                    if (el.dataset.ch === ch) return;
                    el.dataset.ch = ch;
                    const g = ch === ' ' ? '\u00a0' : ch;
                    if (matchMedia('(prefers-reduced-motion: reduce)').matches) {
                        el.querySelector('.flp-top .flp-g').textContent = g;
                        el.querySelector('.flp-bot .flp-g').textContent = g;
                        return;
                    }
                    const top = el.querySelector('.flp-leaf-t');
                    const bot = el.querySelector('.flp-leaf-b');
                    top.firstElementChild.textContent = g;
                    bot.firstElementChild.textContent = g;
                    top.style.display = 'block';
                    const a1 = top.animate([{ transform: 'rotateX(0deg)' }, { transform: 'rotateX(-90deg)' }],
                        { duration: 130, easing: 'cubic-bezier(.5,0,.9,.45)' });
                    a1.onfinish = () => {
                        el.querySelector('.flp-top .flp-g').textContent = g;
                        top.style.display = 'none';
                        bot.style.display = 'block';
                        const a2 = bot.animate([{ transform: 'rotateX(90deg)' }, { transform: 'rotateX(0deg)' }],
                            { duration: 105, easing: 'cubic-bezier(.15,.65,.3,1)' });
                        a2.onfinish = () => {
                            el.querySelector('.flp-bot .flp-g').textContent = g;
                            bot.style.display = 'none';
                        };
                    };
                },
            }">
            <div class="flp-row" x-ref="msg" role="img" x-bind:aria-label="'message board: ' + text.trim()">
                <template x-for="(ch, i) in text" :key="'m' + i">
                    <span class="flp-cell" x-bind:data-ch="ch">
                        <span class="flp-half flp-top"><span class="flp-g" x-text="ch === ' ' ? '\u00a0' : ch"></span></span>
                        <span class="flp-half flp-bot"><span class="flp-g" x-text="ch === ' ' ? '\u00a0' : ch"></span></span>
                        <span class="flp-leaf flp-leaf-t" aria-hidden="true"><span class="flp-g"></span></span>
                        <span class="flp-leaf flp-leaf-b" aria-hidden="true"><span class="flp-g"></span></span>
                    </span>
                </template>
            </div>
            <div class="flp-clockrow">
                <span class="flp-label">ساعت محلی</span>
                <div class="flp-row" x-ref="clock" role="img" x-bind:aria-label="'local time: ' + clock">
                    <template x-for="(ch, i) in clock" :key="'c' + i">
                        <span class="flp-cell" x-bind:data-ch="ch">
                            <span class="flp-half flp-top"><span class="flp-g" x-text="ch"></span></span>
                            <span class="flp-half flp-bot"><span class="flp-g" x-text="ch"></span></span>
                            <span class="flp-leaf flp-leaf-t" aria-hidden="true"><span class="flp-g"></span></span>
                            <span class="flp-leaf flp-leaf-b" aria-hidden="true"><span class="flp-g"></span></span>
                        </span>
                    </template>
                </div>
            </div>
            <input type="text" maxlength="10" placeholder="تابلو را دوباره بنویسید"
                   x-on:input="retype($event.target.value)">
        </div>

        <style>
        .flp-board { direction: ltr; display: grid; gap: .8rem; padding: 1rem; border-radius: 1rem;
                     background: #171411; border: 1px solid #3a3126; }
        .flp-row { display: flex; gap: .22rem; }
        .flp-clockrow { display: flex; align-items: center; gap: .6rem; }
        .flp-label { font: 700 .6rem/1 sans-serif; letter-spacing: .2em; color: #8a7d64; }
        .flp-cell { position: relative; inline-size: 1.35rem; aspect-ratio: 1 / 1.72; perspective: 300px; }
        .flp-half, .flp-leaf { position: absolute; inset-inline: 0; block-size: 50%; overflow: clip; }
        .flp-half.flp-top, .flp-leaf.flp-leaf-t { inset-block-start: 0; background: #2d2a24; }
        .flp-half.flp-bot, .flp-leaf.flp-leaf-b { inset-block-end: 0; background: #211e19; }
        .flp-leaf-t { transform-origin: 50% 100%; transform: rotateX(0deg); display: none; }
        .flp-leaf-b { transform-origin: 50% 0%; transform: rotateX(90deg); display: none; }
        .flp-g { position: absolute; inset-inline: 0; block-size: 200%; display: flex; align-items: center;
                 justify-content: center; font: 700 1.2rem/1 ui-monospace, Menlo, monospace; color: #f3ead8; }
        .flp-top .flp-g, .flp-leaf-t .flp-g { inset-block-start: 0; }
        .flp-bot .flp-g, .flp-leaf-b .flp-g { inset-block-start: -100%; }
        .flp-cell::after { content: ''; position: absolute; inset-block-start: 50%; inset-inline: 0;
                           block-size: 1px; background: rgba(0, 0, 0, .6); }
        input { padding: .5rem .8rem; border-radius: .7rem; border: 1px solid #3a3126;
                background: #211e19; color: #f3ead8; font: 700 .85rem/1 ui-monospace, monospace;
                letter-spacing: .12em; text-transform: uppercase; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="flp-board" x-data="{
                text: 'BON VOYAGE', clock: '00:00:00', visible: true,
                io: null, timer: 0, timers: [],
                init() {
                    this.io = new IntersectionObserver((e) => { this.visible = e[0].isIntersecting; }, { threshold: 0.2 });
                    this.io.observe(this.$refs.board);
                    this.timer = setInterval(() => this.tick(), 250);
                },
                destroy() {
                    this.io?.disconnect();
                    clearInterval(this.timer);
                    this.timers.forEach(clearTimeout); this.timers = [];
                },
                tick() {
                    if (!this.visible) return;
                    const s = new Date().toLocaleTimeString('en-GB', { hour12: false });
                    if (s === this.clock) return;
                    this.clock = s;
                    [...this.$refs.clock.children].forEach((el, i) => this.setCell(el, s[i], 28 * i));
                },
                retype(v) {
                    const t = String(v ?? '').toUpperCase().replace(/[^A-Z0-9 :.\-·+]/g, ' ').slice(0, 10).padEnd(10, ' ');
                    this.text = t;
                    this.timers.forEach(clearTimeout); this.timers = [];
                    [...this.$refs.msg.children].forEach((el, i) => this.setCell(el, t[i], 55 * i));
                },
                setCell(el, ch, delay) {
                    this.timers.push(setTimeout(() => this.flip(el, ch), delay));
                },
                flip(el, ch) {
                    if (el.dataset.ch === ch) return;
                    el.dataset.ch = ch;
                    const g = ch === ' ' ? '\u00a0' : ch;
                    if (matchMedia('(prefers-reduced-motion: reduce)').matches) {
                        el.querySelector('.flp-top .flp-g').textContent = g;
                        el.querySelector('.flp-bot .flp-g').textContent = g;
                        return;
                    }
                    const top = el.querySelector('.flp-leaf-t');
                    const bot = el.querySelector('.flp-leaf-b');
                    top.firstElementChild.textContent = g;
                    bot.firstElementChild.textContent = g;
                    top.style.display = 'block';
                    const a1 = top.animate([{ transform: 'rotateX(0deg)' }, { transform: 'rotateX(-90deg)' }],
                        { duration: 130, easing: 'cubic-bezier(.5,0,.9,.45)' });
                    a1.onfinish = () => {
                        el.querySelector('.flp-top .flp-g').textContent = g;
                        top.style.display = 'none';
                        bot.style.display = 'block';
                        const a2 = bot.animate([{ transform: 'rotateX(90deg)' }, { transform: 'rotateX(0deg)' }],
                            { duration: 105, easing: 'cubic-bezier(.15,.65,.3,1)' });
                        a2.onfinish = () => {
                            el.querySelector('.flp-bot .flp-g').textContent = g;
                            bot.style.display = 'none';
                        };
                    };
                },
            }">
            <div class="flp-row" x-ref="msg" role="img" x-bind:aria-label="'message board: ' + text.trim()">
                <template x-for="(ch, i) in text" :key="'m' + i">
                    <span class="flp-cell" x-bind:data-ch="ch">
                        <span class="flp-half flp-top"><span class="flp-g" x-text="ch === ' ' ? '\u00a0' : ch"></span></span>
                        <span class="flp-half flp-bot"><span class="flp-g" x-text="ch === ' ' ? '\u00a0' : ch"></span></span>
                        <span class="flp-leaf flp-leaf-t" aria-hidden="true"><span class="flp-g"></span></span>
                        <span class="flp-leaf flp-leaf-b" aria-hidden="true"><span class="flp-g"></span></span>
                    </span>
                </template>
            </div>
            <div class="flp-clockrow">
                <span class="flp-label">LOCAL TIME</span>
                <div class="flp-row" x-ref="clock" role="img" x-bind:aria-label="'local time: ' + clock">
                    <template x-for="(ch, i) in clock" :key="'c' + i">
                        <span class="flp-cell" x-bind:data-ch="ch">
                            <span class="flp-half flp-top"><span class="flp-g" x-text="ch"></span></span>
                            <span class="flp-half flp-bot"><span class="flp-g" x-text="ch"></span></span>
                            <span class="flp-leaf flp-leaf-t" aria-hidden="true"><span class="flp-g"></span></span>
                            <span class="flp-leaf flp-leaf-b" aria-hidden="true"><span class="flp-g"></span></span>
                        </span>
                    </template>
                </div>
            </div>
            <input type="text" maxlength="10" placeholder="Retype the board"
                   x-on:input="retype($event.target.value)">
        </div>

        <style>
        .flp-board { direction: ltr; display: grid; gap: .8rem; padding: 1rem; border-radius: 1rem;
                     background: #171411; border: 1px solid #3a3126; }
        .flp-row { display: flex; gap: .22rem; }
        .flp-clockrow { display: flex; align-items: center; gap: .6rem; }
        .flp-label { font: 700 .6rem/1 sans-serif; letter-spacing: .2em; color: #8a7d64; }
        .flp-cell { position: relative; inline-size: 1.35rem; aspect-ratio: 1 / 1.72; perspective: 300px; }
        .flp-half, .flp-leaf { position: absolute; inset-inline: 0; block-size: 50%; overflow: clip; }
        .flp-half.flp-top, .flp-leaf.flp-leaf-t { inset-block-start: 0; background: #2d2a24; }
        .flp-half.flp-bot, .flp-leaf.flp-leaf-b { inset-block-end: 0; background: #211e19; }
        .flp-leaf-t { transform-origin: 50% 100%; transform: rotateX(0deg); display: none; }
        .flp-leaf-b { transform-origin: 50% 0%; transform: rotateX(90deg); display: none; }
        .flp-g { position: absolute; inset-inline: 0; block-size: 200%; display: flex; align-items: center;
                 justify-content: center; font: 700 1.2rem/1 ui-monospace, Menlo, monospace; color: #f3ead8; }
        .flp-top .flp-g, .flp-leaf-t .flp-g { inset-block-start: 0; }
        .flp-bot .flp-g, .flp-leaf-b .flp-g { inset-block-start: -100%; }
        .flp-cell::after { content: ''; position: absolute; inset-block-start: 50%; inset-inline: 0;
                           block-size: 1px; background: rgba(0, 0, 0, .6); }
        input { padding: .5rem .8rem; border-radius: .7rem; border: 1px solid #3a3126;
                background: #211e19; color: #f3ead8; font: 700 .85rem/1 ui-monospace, monospace;
                letter-spacing: .12em; text-transform: uppercase; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Departures.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import SplitFlapBoard from '@/components/SplitFlapBoard';

        export default function Departures() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <SplitFlapBoard initial="BON VOYAGE" />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // SplitFlapBoard.jsx — Solari board: every char flips on two leaves, no sound
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const CELLS = 10;
        const norm = (v) =>
          String(v ?? '').toUpperCase().replace(/[^A-Z0-9 :.\-·+]/g, ' ').slice(0, CELLS);
        const glyph = (ch) => (ch === ' ' ? '\u00a0' : ch);

        // The mechanical heart: top leaf breaks out and falls, bottom leaf settles.
        function flipTo(cell, ch) {
          const g = glyph(ch);
          const topG = cell.querySelector('.flp-top .flp-g');
          const botG = cell.querySelector('.flp-bot .flp-g');
          const top = cell.querySelector('.flp-leaf-t');
          const bot = cell.querySelector('.flp-leaf-b');
          if (matchMedia('(prefers-reduced-motion: reduce)').matches) {
            topG.textContent = g; botG.textContent = g; return;
          }
          top.firstElementChild.textContent = g;
          bot.firstElementChild.textContent = g;
          top.style.display = 'block';
          const a1 = top.animate(
            [{ transform: 'rotateX(0deg)' }, { transform: 'rotateX(-90deg)' }],
            { duration: 130, easing: 'cubic-bezier(.5,0,.9,.45)' });
          a1.onfinish = () => {
            topG.textContent = g; top.style.display = 'none'; bot.style.display = 'block';
            const a2 = bot.animate(
              [{ transform: 'rotateX(90deg)' }, { transform: 'rotateX(0deg)' }],
              { duration: 105, easing: 'cubic-bezier(.15,.65,.3,1)' });
            a2.onfinish = () => { botG.textContent = g; bot.style.display = 'none'; };
          };
        }
        const flipCell = (cell, ch) => {
          if (cell.dataset.ch === ch) return;
          cell.dataset.ch = ch;
          flipTo(cell, ch);
        };

        export default function SplitFlapBoard({ initial = 'BON VOYAGE' }) {
          const [text, setText] = useState(() => norm(initial).padEnd(CELLS, ' '));
          const [draft, setDraft] = useState(initial);
          const boardRef = useRef(null);
          const msgRef = useRef(null);
          const clockRef = useRef(null);
          const visibleRef = useRef(true);
          const lastClock = useRef('');
          const prevText = useRef(null);

          // The clock parks off-screen; everything tears down on unmount.
          useEffect(() => {
            const io = new IntersectionObserver(([e]) => { visibleRef.current = e.isIntersecting; }, { threshold: 0.2 });
            io.observe(boardRef.current);
            const timer = setInterval(() => {
              if (!visibleRef.current) return;
              const s = new Date().toLocaleTimeString('en-GB', { hour12: false });
              if (s === lastClock.current) return;
              lastClock.current = s;
              [...clockRef.current.children].forEach((el, i) =>
                setTimeout(() => flipCell(el, s[i]), 28 * i));
            }, 250);
            return () => { io.disconnect(); clearInterval(timer); };
          }, []);

          // Retype: cascade the flips across the row (skipping on first paint).
          useEffect(() => {
            if (prevText.current === text) return;
            prevText.current = text;
            const timers = [...msgRef.current.children].map((el, i) =>
              setTimeout(() => flipCell(el, text[i]), 55 * i));
            return () => timers.forEach(clearTimeout);
          }, [text]);

          const cell = (ch, key) => (
            <span key={key} className="flp-cell" data-ch={ch}>
              <span className="flp-half flp-top"><span className="flp-g">{glyph(ch)}</span></span>
              <span className="flp-half flp-bot"><span className="flp-g">{glyph(ch)}</span></span>
              <span className="flp-leaf flp-leaf-t" aria-hidden="true"><span className="flp-g" /></span>
              <span className="flp-leaf flp-leaf-b" aria-hidden="true"><span className="flp-g" /></span>
            </span>
          );

          return (
            <>
              <style>{`
                .flp-board { direction: ltr; display: grid; gap: .8rem; padding: 1rem; border-radius: 1rem;
                            background: #171411; border: 1px solid #3a3126; }
                .flp-row { display: flex; gap: .22rem; }
                .flp-clockrow { display: flex; align-items: center; gap: .6rem; }
                .flp-label { font: 700 .6rem/1 sans-serif; letter-spacing: .2em; color: #8a7d64; }
                .flp-cell { position: relative; inline-size: 1.35rem; aspect-ratio: 1 / 1.72; perspective: 300px; }
                .flp-half, .flp-leaf { position: absolute; inset-inline: 0; block-size: 50%; overflow: clip; }
                .flp-half.flp-top, .flp-leaf.flp-leaf-t { inset-block-start: 0; background: #2d2a24; }
                .flp-half.flp-bot, .flp-leaf.flp-leaf-b { inset-block-end: 0; background: #211e19; }
                .flp-leaf-t { transform-origin: 50% 100%; transform: rotateX(0deg); }
                .flp-leaf-b { transform-origin: 50% 0%; transform: rotateX(90deg); }
                .flp-g { position: absolute; inset-inline: 0; block-size: 200%; display: flex; align-items: center;
                         justify-content: center; font: 700 1.2rem/1 ui-monospace, Menlo, monospace; color: #f3ead8; }
                .flp-top .flp-g, .flp-leaf-t .flp-g { inset-block-start: 0; }
                .flp-bot .flp-g, .flp-leaf-b .flp-g { inset-block-start: -100%; }
                .flp-cell::after { content: ''; position: absolute; inset-block-start: 50%; inset-inline: 0;
                                   block-size: 1px; background: rgba(0, 0, 0, .6); }
              `}</style>
              <div className="flp-board" ref={boardRef}>
                <div className="flp-row" ref={msgRef} role="img" aria-label={'message board: ' + text.trim()}>
                  {[...text].map((ch, i) => cell(ch, 'm' + i))}
                </div>
                <div className="flp-clockrow">
                  <span className="flp-label">LOCAL TIME</span>
                  <div className="flp-row" ref={clockRef} role="img" aria-label={'local time: ' + lastClock.current}>
                    {Array.from({ length: 8 }, (_, i) => cell('0', 'c' + i))}
                  </div>
                </div>
                <input type="text" maxLength={10} placeholder="Retype the board" value={draft}
                  onChange={(e) => {
                    setDraft(e.target.value);
                    setText(norm(e.target.value).padEnd(CELLS, ' '));
                  }} />
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- SplitFlapBoard.vue — Solari board: every char flips on two leaves, no sound -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const CELLS = 10;
        const norm = (v: string) =>
          String(v ?? '').toUpperCase().replace(/[^A-Z0-9 :.\-·+]/g, ' ').slice(0, CELLS);
        const glyph = (ch: string) => (ch === ' ' ? '\u00a0' : ch);

        function flipTo(cell: HTMLElement, ch: string) {
          const g = glyph(ch);
          const topG = cell.querySelector('.flp-top .flp-g')!;
          const botG = cell.querySelector('.flp-bot .flp-g')!;
          const top = cell.querySelector('.flp-leaf-t') as HTMLElement;
          const bot = cell.querySelector('.flp-leaf-b') as HTMLElement;
          if (matchMedia('(prefers-reduced-motion: reduce)').matches) {
            topG.textContent = g; botG.textContent = g; return;
          }
          top.firstElementChild!.textContent = g;
          bot.firstElementChild!.textContent = g;
          top.style.display = 'block';
          const a1 = top.animate(
            [{ transform: 'rotateX(0deg)' }, { transform: 'rotateX(-90deg)' }],
            { duration: 130, easing: 'cubic-bezier(.5,0,.9,.45)' });
          a1.onfinish = () => {
            topG.textContent = g; top.style.display = 'none'; bot.style.display = 'block';
            const a2 = bot.animate(
              [{ transform: 'rotateX(90deg)' }, { transform: 'rotateX(0deg)' }],
              { duration: 105, easing: 'cubic-bezier(.15,.65,.3,1)' });
            a2.onfinish = () => { botG.textContent = g; bot.style.display = 'none'; };
          };
        }
        function flipCell(cell: HTMLElement, ch: string) {
          if (cell.dataset.ch === ch) return;
          cell.dataset.ch = ch;
          flipTo(cell, ch);
        }

        const draft = ref('BON VOYAGE');
        const text = ref(norm('BON VOYAGE').padEnd(CELLS, ' '));
        const clock = ref('00:00:00');
        const board = ref<HTMLElement | null>(null);
        const msgRow = ref<HTMLElement | null>(null);
        const clockRow = ref<HTMLElement | null>(null);
        let io: IntersectionObserver | null = null;
        let timer = 0;
        let visible = true;

        onMounted(() => {
          io = new IntersectionObserver(([e]) => { visible = e.isIntersecting; }, { threshold: 0.2 });
          io.observe(board.value!);
          timer = window.setInterval(() => {
            if (!visible) return;
            const s = new Date().toLocaleTimeString('en-GB', { hour12: false });
            if (s === clock.value) return;
            clock.value = s;
            [...clockRow.value!.children].forEach((el, i) =>
              window.setTimeout(() => flipCell(el as HTMLElement, s[i]), 28 * i));
          }, 250);
        });
        onBeforeUnmount(() => {
          io?.disconnect();
          clearInterval(timer);
        });

        function retype(e: Event) {
          draft.value = (e.target as HTMLInputElement).value;
          text.value = norm(draft.value).padEnd(CELLS, ' ');
          [...msgRow.value!.children].forEach((el, i) =>
            window.setTimeout(() => flipCell(el as HTMLElement, text.value[i]), 55 * i));
        }
        </script>

        <template>
          <div ref="board" class="flp-board">
            <div ref="msgRow" class="flp-row" role="img" :aria-label="'message board: ' + text.trim()">
              <span v-for="(ch, i) in text" :key="'m' + i" class="flp-cell" :data-ch="ch">
                <span class="flp-half flp-top"><span class="flp-g">{{ ch === ' ' ? '\u00a0' : ch }}</span></span>
                <span class="flp-half flp-bot"><span class="flp-g">{{ ch === ' ' ? '\u00a0' : ch }}</span></span>
                <span class="flp-leaf flp-leaf-t" aria-hidden="true"><span class="flp-g"></span></span>
                <span class="flp-leaf flp-leaf-b" aria-hidden="true"><span class="flp-g"></span></span>
              </span>
            </div>
            <div class="flp-clockrow">
              <span class="flp-label">LOCAL TIME</span>
              <div ref="clockRow" class="flp-row" role="img" :aria-label="'local time: ' + clock">
                <span v-for="(ch, i) in clock" :key="'c' + i" class="flp-cell" :data-ch="ch">
                  <span class="flp-half flp-top"><span class="flp-g">{{ ch }}</span></span>
                  <span class="flp-half flp-bot"><span class="flp-g">{{ ch }}</span></span>
                  <span class="flp-leaf flp-leaf-t" aria-hidden="true"><span class="flp-g"></span></span>
                  <span class="flp-leaf flp-leaf-b" aria-hidden="true"><span class="flp-g"></span></span>
                </span>
              </div>
            </div>
            <input type="text" maxlength="10" placeholder="Retype the board"
                   :value="draft" @input="retype" />
          </div>
        </template>

        <style scoped>
        .flp-board { direction: ltr; display: grid; gap: .8rem; padding: 1rem; border-radius: 1rem;
                     background: #171411; border: 1px solid #3a3126; }
        .flp-row { display: flex; gap: .22rem; }
        .flp-clockrow { display: flex; align-items: center; gap: .6rem; }
        .flp-label { font: 700 .6rem/1 sans-serif; letter-spacing: .2em; color: #8a7d64; }
        .flp-cell { position: relative; inline-size: 1.35rem; aspect-ratio: 1 / 1.72; perspective: 300px; }
        .flp-half, .flp-leaf { position: absolute; inset-inline: 0; block-size: 50%; overflow: clip; }
        .flp-half.flp-top, .flp-leaf.flp-leaf-t { inset-block-start: 0; background: #2d2a24; }
        .flp-half.flp-bot, .flp-leaf.flp-leaf-b { inset-block-end: 0; background: #211e19; }
        .flp-leaf-t { transform-origin: 50% 100%; transform: rotateX(0deg); }
        .flp-leaf-b { transform-origin: 50% 0%; transform: rotateX(90deg); }
        .flp-g { position: absolute; inset-inline: 0; block-size: 200%; display: flex; align-items: center;
                 justify-content: center; font: 700 1.2rem/1 ui-monospace, Menlo, monospace; color: #f3ead8; }
        .flp-top .flp-g, .flp-leaf-t .flp-g { inset-block-start: 0; }
        .flp-bot .flp-g, .flp-leaf-b .flp-g { inset-block-start: -100%; }
        .flp-cell::after { content: ''; position: absolute; inset-block-start: 50%; inset-inline: 0;
                           block-size: 1px; background: rgba(0, 0, 0, .6); }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- SplitFlapBoard.svelte — Solari board: every char flips on two leaves, no sound -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          const CELLS = 10;
          const norm = (v: string) =>
            String(v ?? '').toUpperCase().replace(/[^A-Z0-9 :.\-·+]/g, ' ').slice(0, CELLS);
          const glyph = (ch: string) => (ch === ' ' ? '\u00a0' : ch);

          function flipTo(cell: HTMLElement, ch: string) {
            const g = glyph(ch);
            const topG = cell.querySelector('.flp-top .flp-g')!;
            const botG = cell.querySelector('.flp-bot .flp-g')!;
            const top = cell.querySelector('.flp-leaf-t') as HTMLElement;
            const bot = cell.querySelector('.flp-leaf-b') as HTMLElement;
            if (matchMedia('(prefers-reduced-motion: reduce)').matches) {
              topG.textContent = g; botG.textContent = g; return;
            }
            top.firstElementChild!.textContent = g;
            bot.firstElementChild!.textContent = g;
            top.style.display = 'block';
            const a1 = top.animate(
              [{ transform: 'rotateX(0deg)' }, { transform: 'rotateX(-90deg)' }],
              { duration: 130, easing: 'cubic-bezier(.5,0,.9,.45)' });
            a1.onfinish = () => {
              topG.textContent = g; top.style.display = 'none'; bot.style.display = 'block';
              const a2 = bot.animate(
                [{ transform: 'rotateX(90deg)' }, { transform: 'rotateX(0deg)' }],
                { duration: 105, easing: 'cubic-bezier(.15,.65,.3,1)' });
              a2.onfinish = () => { botG.textContent = g; bot.style.display = 'none'; };
            };
          }
          function flipCell(cell: HTMLElement, ch: string) {
            if (cell.dataset.ch === ch) return;
            cell.dataset.ch = ch;
            flipTo(cell, ch);
          }

          let draft = $state('BON VOYAGE');
          let text = $state(norm('BON VOYAGE').padEnd(CELLS, ' '));
          let clock = $state('00:00:00');
          let board: HTMLElement;
          let msgRow: HTMLElement;
          let clockRow: HTMLElement;
          let visible = true;

          onMount(() => {
            const io = new IntersectionObserver(([e]) => { visible = e.isIntersecting; }, { threshold: 0.2 });
            io.observe(board);
            const timer = window.setInterval(() => {
              if (!visible) return;
              const s = new Date().toLocaleTimeString('en-GB', { hour12: false });
              if (s === clock) return;
              clock = s;
              [...clockRow.children].forEach((el, i) =>
                window.setTimeout(() => flipCell(el as HTMLElement, s[i]), 28 * i));
            }, 250);
            return () => { io.disconnect(); clearInterval(timer); };
          });

          function retype(e: Event) {
            draft = (e.target as HTMLInputElement).value;
            text = norm(draft).padEnd(CELLS, ' ');
            [...msgRow.children].forEach((el, i) =>
              window.setTimeout(() => flipCell(el as HTMLElement, text[i]), 55 * i));
          }
        </script>

        <div class="flp-board" bind:this={board}>
          <div class="flp-row" bind:this={msgRow} role="img" aria-label="message board: {text.trim()}">
            {#each [...text] as ch, i}
              <span class="flp-cell" data-ch={ch}>
                <span class="flp-half flp-top"><span class="flp-g">{ch === ' ' ? '\u00a0' : ch}</span></span>
                <span class="flp-half flp-bot"><span class="flp-g">{ch === ' ' ? '\u00a0' : ch}</span></span>
                <span class="flp-leaf flp-leaf-t" aria-hidden="true"><span class="flp-g"></span></span>
                <span class="flp-leaf flp-leaf-b" aria-hidden="true"><span class="flp-g"></span></span>
              </span>
            {/each}
          </div>
          <div class="flp-clockrow">
            <span class="flp-label">LOCAL TIME</span>
            <div class="flp-row" bind:this={clockRow} role="img" aria-label="local time: {clock}">
              {#each [...clock] as ch, i}
                <span class="flp-cell" data-ch={ch}>
                  <span class="flp-half flp-top"><span class="flp-g">{ch}</span></span>
                  <span class="flp-half flp-bot"><span class="flp-g">{ch}</span></span>
                  <span class="flp-leaf flp-leaf-t" aria-hidden="true"><span class="flp-g"></span></span>
                  <span class="flp-leaf flp-leaf-b" aria-hidden="true"><span class="flp-g"></span></span>
                </span>
              {/each}
            </div>
          </div>
          <input type="text" maxlength="12" placeholder="Retype the board" value={draft} oninput={retype} />
        </div>

        <style>
        .flp-board { direction: ltr; display: grid; gap: .8rem; padding: 1rem; border-radius: 1rem;
                     background: #171411; border: 1px solid #3a3126; }
        .flp-row { display: flex; gap: .22rem; }
        .flp-clockrow { display: flex; align-items: center; gap: .6rem; }
        .flp-label { font: 700 .6rem/1 sans-serif; letter-spacing: .2em; color: #8a7d64; }
        .flp-cell { position: relative; inline-size: 1.35rem; aspect-ratio: 1 / 1.72; perspective: 300px; }
        .flp-half, .flp-leaf { position: absolute; inset-inline: 0; block-size: 50%; overflow: clip; }
        .flp-half.flp-top, .flp-leaf.flp-leaf-t { inset-block-start: 0; background: #2d2a24; }
        .flp-half.flp-bot, .flp-leaf.flp-leaf-b { inset-block-end: 0; background: #211e19; }
        .flp-leaf-t { transform-origin: 50% 100%; transform: rotateX(0deg); }
        .flp-leaf-b { transform-origin: 50% 0%; transform: rotateX(90deg); }
        .flp-g { position: absolute; inset-inline: 0; block-size: 200%; display: flex; align-items: center;
                 justify-content: center; font: 700 1.2rem/1 ui-monospace, Menlo, monospace; color: #f3ead8; }
        .flp-top .flp-g, .flp-leaf-t .flp-g { inset-block-start: 0; }
        .flp-bot .flp-g, .flp-leaf-b .flp-g { inset-block-start: -100%; }
        .flp-cell::after { content: ''; position: absolute; inset-block-start: 50%; inset-inline: 0;
                           block-size: 1px; background: rgba(0, 0, 0, .6); }
        </style>
        SVELTE,
        ],
    ],
];
