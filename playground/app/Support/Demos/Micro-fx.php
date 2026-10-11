<?php

/**
 * Demo manifest of the "micro-fx" group — physical micro-interactions with
 * no library: particle confetti celebrating a signup, the advanced ripple
 * that answers pointer and keyboard alike, the draggable token with real
 * spring physics and a drop target that grips it, the reaction bar with its
 * particle pop and vertical odometer counter, and the live price ticker
 * whose digits roll. Scenarios live at
 * resources/views/demos/components/micro-fx/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'ریزتعامل‌ها', 'en' => 'Micro FX'],

    'confetti-burst' => [
        'title' => ['fa' => 'انفجار کانفتی', 'en' => 'Confetti burst'],
        'icon' => 'sparkles',
        'oneLiner' => [
            'fa' => 'کانفتی ذره‌ای روی بوم دوبعدی که موفقیت را جشن می‌گیرد: فرم کوچک عضویت بتا را بفرستید تا از خود دکمه، ذراتی با جاذبه، پسای هوا، چرخش و پنج رنگ تم بپرد؛ اسلایدر تراکم (۴۰ تا ۲۴۰ ذره) و دکمهٔ «دوباره» جشن را بی‌پایان می‌کنند.',
            'en' => 'Particle confetti on a 2D canvas that celebrates the win: submit the tiny beta form and pieces leap from the button itself — gravity, air drag, spin and the theme’s five colours; a density slider (40–240 pieces) and an “again” button keep the party going.',
        ],
        'js' => true,
        'docs' => 'https://motion-primitives.com',
        'props' => [
            ['name' => 'particles', 'type' => 'number', 'default' => "'120'", 'note' => [
                'fa' => 'تراکم هر انفجار؛ اسلایدر بین ۴۰ تا ۲۴۰ ذره با گام ۲۰.',
                'en' => 'The density of each blast; the slider spans 40–240 pieces in steps of 20.',
            ]],
            ['name' => 'gravity', 'type' => 'px/s²', 'default' => "'1500'", 'note' => [
                'fa' => 'شتاب سقوط ذرات؛ پسای نمایی هوا سرعت افقی را آرام می‌خشکاند تا پرتاب واقعی شود.',
                'en' => 'The fall acceleration; an exponential air drag dries the horizontal speed so the throw feels real.',
            ]],
            ['name' => 'palette', 'type' => 'css vars', 'default' => "'--mcfb-c1 … c5'", 'note' => [
                'fa' => 'پنج رنگ ذرات به‌صورت متغیر CSS؛ هنگام شلیک خوانده می‌شوند و در آینهٔ تیره همگام می‌مانند.',
                'en' => 'The five piece colours as CSS variables; read at fire time so they stay in sync with the dark mirror.',
            ]],
            ['name' => 'prefers-reduced-motion', 'type' => 'behaviour', 'default' => "'skip'", 'note' => [
                'fa' => 'بدون انفجار؛ فقط پیام موفقیت می‌ماند و بوم خالی است.',
                'en' => 'No blast; only the success message stays and the canvas stays empty.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        {{-- confettiBurst(): canvas 2D + RAF; particles carry gravity, drag,
             spin and life; the loop parks off screen and dies when empty. --}}
        <div class="mcfb" x-data="confettiBurst()">
            <div class="mcfb-stage">
                <canvas class="mcfb-cv" x-ref="cv" aria-hidden="true"></canvas>
                <form class="mcfb-card" x-show="!done" @submit.prevent="submit($event)">
                    <label class="mcfb-label" for="mcfb-mail">ایمیل کاری</label>
                    <input id="mcfb-mail" class="mcfb-input" type="email" x-model="email" placeholder="you@studio.com">
                    <p class="mcfb-err" x-show="error" role="alert">نشانی ایمیل کامل نیست.</p>
                    <button type="submit" class="mcfb-go" x-ref="go">عضویت در بتا</button>
                </form>
                <div class="mcfb-card mcfb-win" x-show="done" x-cloak>
                    <h4>در فهرست هستید</h4>
                    <p>اولین نسخهٔ نبولاس میل برای شما می‌آید.</p>
                </div>
            </div>
            <div class="mcfb-foot">
                <label class="mcfb-density">تراکم
                    <input type="range" min="40" max="240" step="20" x-model.number="density">
                    <span class="mcfb-num" x-text="faNum(density)"></span>
                </label>
                <button type="button" class="mcfb-again" x-on:click="again()">دوباره جشن بگیر</button>
            </div>
        </div>

        <style>
        .mcfb-stage { position: relative; display: grid; place-items: center; min-block-size: 20rem;
                      overflow: clip; border-radius: 1.1rem; background: #fafaf9; }
        .mcfb-cv { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; pointer-events: none; }
        .mcfb-card { position: relative; z-index: 1; display: grid; gap: .8rem; inline-size: min(100%, 22rem);
                     padding: 1.4rem; border-radius: 1rem; background: #fff; }
        .mcfb-go { block-size: 44px; border: none; border-radius: .7rem; background: #4f46e5; color: #fff;
                   font: 600 .9rem inherit; cursor: pointer; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        {{-- confettiBurst(): canvas 2D + RAF; particles carry gravity, drag,
             spin and life; the loop parks off screen and dies when empty. --}}
        <div class="mcfb" x-data="confettiBurst()">
        <div class="mcfb-stage">
        <canvas class="mcfb-cv" x-ref="cv" aria-hidden="true"></canvas>
        <form class="mcfb-card" x-show="!done" @submit.prevent="submit($event)">
        <label class="mcfb-label" for="mcfb-mail">Work email</label>
        <input id="mcfb-mail" class="mcfb-input" type="email" x-model="email" placeholder="you@studio.com">
        <p class="mcfb-err" x-show="error" role="alert">That address looks incomplete.</p>
        <button type="submit" class="mcfb-go" x-ref="go">Join the beta</button>
        </form>
        <div class="mcfb-card mcfb-win" x-show="done" x-cloak>
        <h4>You are on the list</h4>
        <p>The first Nimbus Mail build lands in your inbox.</p>
        </div>
        </div>
        <div class="mcfb-foot">
        <label class="mcfb-density">Density
        <input type="range" min="40" max="240" step="20" x-model.number="density">
        <span class="mcfb-num" x-text="faNum(density)"></span>
        </label>
        <button type="button" class="mcfb-again" x-on:click="again()">Celebrate again</button>
        </div>
        </div>

        <style>
        .mcfb-stage { position: relative; display: grid; place-items: center; min-block-size: 20rem;
                      overflow: clip; border-radius: 1.1rem; background: #fafaf9; }
        .mcfb-cv { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; pointer-events: none; }
        .mcfb-card { position: relative; z-index: 1; display: grid; gap: .8rem; inline-size: min(100%, 22rem);
                     padding: 1.4rem; border-radius: 1rem; background: #fff; }
        .mcfb-go { block-size: 44px; border: none; border-radius: .7rem; background: #4f46e5; color: #fff;
                   font: 600 .9rem inherit; cursor: pointer; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Beta.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import ConfettiForm from '@/components/ConfettiForm';

        export default function Beta() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <ConfettiForm />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // ConfettiForm.jsx — canvas confetti that celebrates the signup
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const COLORS = ['#f43f5e', '#f59e0b', '#10b981', '#3b82f6', '#a855f7'];

        export default function ConfettiForm() {
          const cvRef = useRef(null);
          const parts = useRef([]);
          const raf = useRef(0);
          const last = useRef(0);
          const [density, setDensity] = useState(120);
          const [email, setEmail] = useState('');
          const [done, setDone] = useState(false);

          // Keep the canvas sized to its box (device pixels behind, CSS px drawing).
          useEffect(() => {
            const cv = cvRef.current;
            const size = () => {
              const dpr = Math.min(2, window.devicePixelRatio || 1);
              cv.width = Math.round(cv.clientWidth * dpr);
              cv.height = Math.round(cv.clientHeight * dpr);
              cv.getContext('2d').setTransform(dpr, 0, 0, dpr, 0, 0);
            };
            const ro = new ResizeObserver(size);
            ro.observe(cv);
            size();
            return () => ro.disconnect();
          }, []);
          useEffect(() => () => cancelAnimationFrame(raf.current), []);

          const loop = (now) => {
            const cv = cvRef.current, ctx = cv.getContext('2d');
            const dt = Math.min(0.032, (now - last.current) / 1000) || 0.016;
            last.current = now;
            ctx.clearRect(0, 0, cv.clientWidth, cv.clientHeight);
            parts.current = parts.current.filter((p) => {
              p.t += dt; p.vy += 1500 * dt; p.vx *= Math.exp(-dt * 1.1);
              p.x += p.vx * dt; p.y += p.vy * dt; p.rot += p.vr * dt;
              if (p.t > p.life || p.y > cv.clientHeight + 40) return false;
              ctx.save();
              ctx.globalAlpha = Math.max(0, 1 - p.t / p.life);
              ctx.translate(p.x, p.y); ctx.rotate(p.rot); ctx.fillStyle = p.c;
              ctx.fillRect(-p.r, -p.r * 0.6, p.r * 2, p.r * 1.2);
              ctx.restore();
              return true;
            });
            raf.current = parts.current.length ? requestAnimationFrame(loop) : 0;
          };

          const burst = (x, y) => {
            if (matchMedia('(prefers-reduced-motion: reduce)').matches) return;
            for (let i = 0; i < density; i++) {
              const a = -Math.PI / 2 + (Math.random() - 0.5) * Math.PI * 0.9;
              const sp = 320 + Math.random() * 520;
              parts.current.push({ x, y, vx: Math.cos(a) * sp, vy: Math.sin(a) * sp,
                r: 3 + Math.random() * 4, rot: Math.random() * 6.28, vr: (Math.random() - 0.5) * 14,
                c: COLORS[i % COLORS.length], life: 1.6 + Math.random() * 0.9, t: 0 });
            }
            raf.current ||= requestAnimationFrame(loop);
          };

          const submit = (e) => {
            e.preventDefault();
            if (!/.+@.+\..+/.test(email)) return;
            const cv = cvRef.current, r = e.target.querySelector('button').getBoundingClientRect();
            const b = cv.getBoundingClientRect();
            setDone(true);
            burst(r.left + r.width / 2 - b.left, r.top - b.top);
          };

          return (
            <>
              <style>{`
                .mcfb-stage { position: relative; display: grid; place-items: center; min-block-size: 20rem;
                              overflow: clip; border-radius: 1.1rem; background: #fafaf9; }
                .mcfb-cv { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; pointer-events: none; }
                .mcfb-card { position: relative; z-index: 1; display: grid; gap: .8rem; inline-size: min(100%, 22rem);
                             padding: 1.4rem; border-radius: 1rem; background: #fff; font: 400 .9rem system-ui; }
                .mcfb-input { block-size: 44px; padding-inline: .9rem; border-radius: .7rem;
                              border: 1px solid #e7e5e4; background: #f5f5f4; font: inherit; }
                .mcfb-go { block-size: 44px; border: none; border-radius: .7rem; background: #4f46e5;
                           color: #fff; font: 600 .9rem inherit; cursor: pointer; }
                .mcfb-density { display: inline-flex; align-items: center; gap: .6rem; font-size: .8rem; color: #78716c; }
                .mcfb-num { font-variant-numeric: tabular-nums; font-weight: 600; color: #1c1917; }
              `}</style>
              <div className="mcfb-stage">
                <canvas ref={cvRef} className="mcfb-cv" aria-hidden="true" />
                {done ? (
                  <div className="mcfb-card"><h4>You are on the list</h4>
                    <p>The first Nimbus Mail build lands in your inbox.</p></div>
                ) : (
                  <form className="mcfb-card" onSubmit={submit}>
                    <label htmlFor="mail">Work email</label>
                    <input id="mail" value={email} onChange={(e) => setEmail(e.target.value)} placeholder="you@studio.com" />
                    <button type="submit" className="mcfb-go">Join the beta</button>
                  </form>
                )}
              </div>
              <label className="mcfb-density">Density
                <input type="range" min="40" max="240" step="20" value={density}
                  onChange={(e) => setDensity(+e.target.value)} />
                <span className="mcfb-num">{density}</span>
              </label>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- ConfettiForm.vue — canvas confetti that celebrates the signup -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const COLORS = ['#f43f5e', '#f59e0b', '#10b981', '#3b82f6', '#a855f7'];
        const cv = ref<HTMLCanvasElement | null>(null);
        const density = ref(120);
        const email = ref('');
        const done = ref(false);
        let parts: Array<{x:number,y:number,vx:number,vy:number,r:number,rot:number,vr:number,c:string,life:number,t:number}> = [];
        let raf = 0, last = 0, ro: ResizeObserver | null = null;

        const loop = (now: number) => {
          const c = cv.value!, ctx = c.getContext('2d')!;
          const dt = Math.min(0.032, (now - last) / 1000) || 0.016;
          last = now;
          ctx.clearRect(0, 0, c.clientWidth, c.clientHeight);
          parts = parts.filter((p) => {
            p.t += dt; p.vy += 1500 * dt; p.vx *= Math.exp(-dt * 1.1);
            p.x += p.vx * dt; p.y += p.vy * dt; p.rot += p.vr * dt;
            if (p.t > p.life || p.y > c.clientHeight + 40) return false;
            ctx.save();
            ctx.globalAlpha = Math.max(0, 1 - p.t / p.life);
            ctx.translate(p.x, p.y); ctx.rotate(p.rot); ctx.fillStyle = p.c;
            ctx.fillRect(-p.r, -p.r * 0.6, p.r * 2, p.r * 1.2);
            ctx.restore();
            return true;
          });
          raf = parts.length ? requestAnimationFrame(loop) : 0;
        };

        const burst = (x: number, y: number) => {
          if (matchMedia('(prefers-reduced-motion: reduce)').matches) return;
          for (let i = 0; i < density.value; i++) {
            const a = -Math.PI / 2 + (Math.random() - 0.5) * Math.PI * 0.9;
            const sp = 320 + Math.random() * 520;
            parts.push({ x, y, vx: Math.cos(a) * sp, vy: Math.sin(a) * sp,
              r: 3 + Math.random() * 4, rot: Math.random() * 6.28, vr: (Math.random() - 0.5) * 14,
              c: COLORS[i % COLORS.length], life: 1.6 + Math.random() * 0.9, t: 0 });
          }
          if (!raf) raf = requestAnimationFrame(loop);
        };

        const submit = (e: Event) => {
          e.preventDefault();
          if (!/.+@.+\..+/.test(email.value)) return;
          const btn = (e.target as HTMLElement).querySelector('button')!;
          const r = btn.getBoundingClientRect(), b = cv.value!.getBoundingClientRect();
          done.value = true;
          burst(r.left + r.width / 2 - b.left, r.top - b.top);
        };

        onMounted(() => {
          const size = () => {
            const c = cv.value!, dpr = Math.min(2, devicePixelRatio || 1);
            c.width = Math.round(c.clientWidth * dpr);
            c.height = Math.round(c.clientHeight * dpr);
            c.getContext('2d')!.setTransform(dpr, 0, 0, dpr, 0, 0);
          };
          ro = new ResizeObserver(size);
          ro.observe(cv.value!);
          size();
        });
        onBeforeUnmount(() => {
          cancelAnimationFrame(raf);
          ro?.disconnect();
        });
        </script>

        <template>
          <div class="mcfb-stage">
            <canvas ref="cv" class="mcfb-cv" aria-hidden="true"></canvas>
            <div v-if="done" class="mcfb-card"><h4>You are on the list</h4>
              <p>The first Nimbus Mail build lands in your inbox.</p></div>
            <form v-else class="mcfb-card" @submit="submit">
              <label for="mail">Work email</label>
              <input id="mail" v-model="email" type="email" placeholder="you@studio.com" />
              <button type="submit" class="mcfb-go">Join the beta</button>
            </form>
          </div>
          <label class="mcfb-density">Density
            <input v-model.number="density" type="range" min="40" max="240" step="20" />
            <span class="mcfb-num">{{ density }}</span>
          </label>
        </template>

        <style scoped>
        .mcfb-stage { position: relative; display: grid; place-items: center; min-block-size: 20rem;
                      overflow: clip; border-radius: 1.1rem; background: #fafaf9; }
        .mcfb-cv { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; pointer-events: none; }
        .mcfb-card { position: relative; z-index: 1; display: grid; gap: .8rem; inline-size: min(100%, 22rem);
                     padding: 1.4rem; border-radius: 1rem; background: #fff; font: 400 .9rem system-ui; }
        .mcfb-input { block-size: 44px; padding-inline: .9rem; border-radius: .7rem;
                      border: 1px solid #e7e5e4; background: #f5f5f4; font: inherit; }
        .mcfb-go { block-size: 44px; border: none; border-radius: .7rem; background: #4f46e5;
                   color: #fff; font: 600 .9rem inherit; cursor: pointer; }
        .mcfb-density { display: inline-flex; align-items: center; gap: .6rem; font-size: .8rem; color: #78716c; }
        .mcfb-num { font-variant-numeric: tabular-nums; font-weight: 600; color: #1c1917; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- ConfettiForm.svelte — canvas confetti that celebrates the signup -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          const COLORS = ['#f43f5e', '#f59e0b', '#10b981', '#3b82f6', '#a855f7'];
          let cv: HTMLCanvasElement;
          let density = $state(120);
          let email = $state('');
          let done = $state(false);
          let parts: Array<{x:number,y:number,vx:number,vy:number,r:number,rot:number,vr:number,c:string,life:number,t:number}> = [];
          let raf = 0, last = 0;

          const loop = (now: number) => {
            const ctx = cv.getContext('2d')!;
            const dt = Math.min(0.032, (now - last) / 1000) || 0.016;
            last = now;
            ctx.clearRect(0, 0, cv.clientWidth, cv.clientHeight);
            parts = parts.filter((p) => {
              p.t += dt; p.vy += 1500 * dt; p.vx *= Math.exp(-dt * 1.1);
              p.x += p.vx * dt; p.y += p.vy * dt; p.rot += p.vr * dt;
              if (p.t > p.life || p.y > cv.clientHeight + 40) return false;
              ctx.save();
              ctx.globalAlpha = Math.max(0, 1 - p.t / p.life);
              ctx.translate(p.x, p.y); ctx.rotate(p.rot); ctx.fillStyle = p.c;
              ctx.fillRect(-p.r, -p.r * 0.6, p.r * 2, p.r * 1.2);
              ctx.restore();
              return true;
            });
            raf = parts.length ? requestAnimationFrame(loop) : 0;
          };

          const burst = (x: number, y: number) => {
            if (matchMedia('(prefers-reduced-motion: reduce)').matches) return;
            for (let i = 0; i < density; i++) {
              const a = -Math.PI / 2 + (Math.random() - 0.5) * Math.PI * 0.9;
              const sp = 320 + Math.random() * 520;
              parts.push({ x, y, vx: Math.cos(a) * sp, vy: Math.sin(a) * sp,
                r: 3 + Math.random() * 4, rot: Math.random() * 6.28, vr: (Math.random() - 0.5) * 14,
                c: COLORS[i % COLORS.length], life: 1.6 + Math.random() * 0.9, t: 0 });
            }
            if (!raf) raf = requestAnimationFrame(loop);
          };

          const submit = (e: SubmitEvent) => {
            e.preventDefault();
            if (!/.+@.+\..+/.test(email)) return;
            const btn = (e.target as HTMLElement).querySelector('button')!;
            const r = btn.getBoundingClientRect(), b = cv.getBoundingClientRect();
            done = true;
            burst(r.left + r.width / 2 - b.left, r.top - b.top);
          };

          onMount(() => {
            const size = () => {
              const dpr = Math.min(2, devicePixelRatio || 1);
              cv.width = Math.round(cv.clientWidth * dpr);
              cv.height = Math.round(cv.clientHeight * dpr);
              cv.getContext('2d')!.setTransform(dpr, 0, 0, dpr, 0, 0);
            };
            const ro = new ResizeObserver(size);
            ro.observe(cv);
            size();
            return () => { cancelAnimationFrame(raf); ro.disconnect(); };
          });
        </script>

        <div class="mcfb-stage">
          <canvas bind:this={cv} class="mcfb-cv" aria-hidden="true"></canvas>
          {#if done}
            <div class="mcfb-card"><h4>You are on the list</h4>
              <p>The first Nimbus Mail build lands in your inbox.</p></div>
          {:else}
            <form class="mcfb-card" onsubmit={submit}>
              <label for="mail">Work email</label>
              <input id="mail" bind:value={email} type="email" placeholder="you@studio.com" />
              <button type="submit" class="mcfb-go">Join the beta</button>
            </form>
          {/if}
        </div>
        <label class="mcfb-density">Density
          <input bind:value={density} type="range" min="40" max="240" step="20" />
          <span class="mcfb-num">{density}</span>
        </label>

        <style>
        .mcfb-stage { position: relative; display: grid; place-items: center; min-block-size: 20rem;
                      overflow: clip; border-radius: 1.1rem; background: #fafaf9; }
        .mcfb-cv { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; pointer-events: none; }
        .mcfb-card { position: relative; z-index: 1; display: grid; gap: .8rem; inline-size: min(100%, 22rem);
                     padding: 1.4rem; border-radius: 1rem; background: #fff; font: 400 .9rem system-ui; }
        .mcfb-input { block-size: 44px; padding-inline: .9rem; border-radius: .7rem;
                      border: 1px solid #e7e5e4; background: #f5f5f4; font: inherit; }
        .mcfb-go { block-size: 44px; border: none; border-radius: .7rem; background: #4f46e5;
                   color: #fff; font: 600 .9rem inherit; cursor: pointer; }
        .mcfb-density { display: inline-flex; align-items: center; gap: .6rem; font-size: .8rem; color: #78716c; }
        .mcfb-num { font-variant-numeric: tabular-nums; font-weight: 600; color: #1c1917; }
        </style>
        SVELTE,
        ],
    ],

    'ripple-click' => [
        'title' => ['fa' => 'موج کلیک', 'en' => 'Ripple click'],
        'icon' => 'zap',
        'oneLiner' => [
            'fa' => 'موج متریال پیشرفته: دایره‌ای از نقطهٔ دقیق فشار با رنگ تم که تا دورترین گوشه می‌بَرد و محو می‌شود — روی دکمه، کارت و تب؛ هم pointerdown و هم کیبورد (Enter/Space از مرکز) و شعاع از هندسهٔ واقعی هر سطح حساب می‌شود.',
            'en' => 'The advanced Material ripple: a circle born at the exact press point in the theme colour, sized to the farthest corner, then grown and faded — on a button, a card and tabs; it answers pointerdown and keyboard alike (Enter/Space from centre) with the radius measured from each surface’s real geometry.',
        ],
        'js' => true,
        'docs' => 'https://motion-primitives.com',
        'props' => [
            ['name' => 'origin', 'type' => 'event', 'default' => "'pointer · center'", 'note' => [
                'fa' => 'موج از مختصات دقیق pointerdown می‌جوشد؛ کیبورد (Enter/Space) آن را از مرکز سطح می‌اندازد.',
                'en' => 'The ripple boils from the exact pointerdown coordinates; the keyboard (Enter/Space) drops it from the surface’s centre.',
            ]],
            ['name' => 'radius', 'type' => 'geometry', 'default' => "'2 × farthest corner + 8px'", 'note' => [
                'fa' => 'شعاع از دورترین گوشهٔ سطح نسبت به نقطهٔ فشار حساب می‌شود؛ هیچ موجی نصفه نمی‌ماند.',
                'en' => 'The radius is the farthest corner from the press point, doubled — no ripple ever dies half-way.',
            ]],
            ['name' => 'ink', 'type' => 'color', 'default' => "'currentColor'", 'note' => [
                'fa' => 'جوهر موج رنگ متنِ سطح است؛ روی دکمهٔ پر روشن و روی تب‌ها رنگ تم.',
                'en' => 'The ripple ink is the surface’s text colour; light on the filled button, theme colour on the tabs.',
            ]],
            ['name' => 'duration', 'type' => 'ms', 'default' => "'550'", 'note' => [
                'fa' => 'با منحنی رسمی متریال (cubic-bezier(.2,0,0,1)) بزرگ می‌شود و آرام محو.',
                'en' => 'It grows on Material’s official curve (cubic-bezier(.2,0,0,1)) and fades away.',
            ]],
            ['name' => 'prefers-reduced-motion', 'type' => 'behaviour', 'default' => "'skip'", 'note' => [
                'fa' => 'موج ساخته نمی‌شود؛ حالت‌های انتخاب و تب‌ها بی‌درنگ عوض می‌شوند.',
                'en' => 'No ripple is spawned; selection and tab states still switch instantly.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        {{-- rippleClick(): هر [data-ripple] سطح است؛ موج از نقطهٔ دقیق فشار
             می‌جوشد، شعاعش دورترین گوشه است و کیبورد از مرکز می‌اندازد. --}}
        <div class="mfrp" x-data="rippleClick()">
            <article class="mfrp-card mfrp-surf" data-ripple>
                <h3>صورت‌حساب اطلس استودیو</h3>
                <div class="mfrp-tabs" role="tablist">
                    <button role="tab" data-ripple x-on:click="tab = 'overview'">خلاصه</button>
                    <button role="tab" data-ripple x-on:click="tab = 'activity'">فعالیت</button>
                    <button role="tab" data-ripple x-on:click="tab = 'billing'">صورتحساب</button>
                </div>
                <p x-show="tab === 'overview'">۳ پروژه · ۱۸ فاکتور · مجموع ۴٬۲۰۰ یورو</p>
                <footer>
                    <button class="mfrp-primary" data-ripple>دانلود PDF</button>
                    <button class="mfrp-ghost" data-ripple>اشتراک‌گذاری</button>
                </footer>
            </article>
            <p class="mfrp-hint" x-text="last"></p>
        </div>

        <style>
        .mfrp-surf { position: relative; overflow: clip; }
        .mfrp-ink { position: absolute; border-radius: 50%; background: currentColor;
                    translate: -50% -50%; pointer-events: none;
                    animation: mfrp-grow .55s cubic-bezier(.2, 0, 0, 1) forwards; }
        @keyframes mfrp-grow { from { scale: 0; opacity: .3; } to { scale: 1; opacity: 0; } }
        .mfrp-primary { block-size: 40px; padding-inline: 18px; border: none; border-radius: 999px;
                        background: #4f46e5; color: #fff; cursor: pointer; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        {{-- rippleClick(): every [data-ripple] is a surface; the ripple boils
             from the exact press point, sized to the farthest corner. --}}
        <div class="mfrp" x-data="rippleClick()">
        <article class="mfrp-card mfrp-surf" data-ripple>
        <h3>Atlas Studio invoice</h3>
        <div class="mfrp-tabs" role="tablist">
        <button role="tab" data-ripple x-on:click="tab = 'overview'">Overview</button>
        <button role="tab" data-ripple x-on:click="tab = 'activity'">Activity</button>
        <button role="tab" data-ripple x-on:click="tab = 'billing'">Billing</button>
        </div>
        <p x-show="tab === 'overview'">3 projects · 18 invoices · €4,200 total</p>
        <footer>
        <button class="mfrp-primary" data-ripple>Download PDF</button>
        <button class="mfrp-ghost" data-ripple>Share</button>
        </footer>
        </article>
        <p class="mfrp-hint" x-text="last"></p>
        </div>

        <style>
        .mfrp-surf { position: relative; overflow: clip; }
        .mfrp-ink { position: absolute; border-radius: 50%; background: currentColor;
                    translate: -50% -50%; pointer-events: none;
                    animation: mfrp-grow .55s cubic-bezier(.2, 0, 0, 1) forwards; }
        @keyframes mfrp-grow { from { scale: 0; opacity: .3; } to { scale: 1; opacity: 0; } }
        .mfrp-primary { block-size: 40px; padding-inline: 18px; border: none; border-radius: 999px;
                        background: #4f46e5; color: #fff; cursor: pointer; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Invoices.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import RippleCard from '@/components/RippleCard';

        export default function Invoices() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <RippleCard />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // RippleCard.jsx — the ripple that answers pointer and keyboard alike
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const TABS = [
          { id: 'overview', label: 'Overview', body: '3 projects · 18 invoices · €4,200 total' },
          { id: 'activity', label: 'Activity', body: 'PDF export run by Lena, 4 min ago' },
          { id: 'billing', label: 'Billing', body: 'Atlas Studio · Prinsengracht 1, Amsterdam' },
        ];

        export default function RippleCard() {
          const rootRef = useRef(null);
          const [tab, setTab] = useState('overview');

          // One AbortController wires — and later unwires — every surface.
          useEffect(() => {
            const root = rootRef.current;
            const ac = new AbortController();
            const mq = matchMedia('(prefers-reduced-motion: reduce)');

            const spawn = (el, cx, cy) => {
              if (mq.matches) return;
              const r = el.getBoundingClientRect();
              const x = cx - r.left, y = cy - r.top;
              const far = Math.max(
                Math.hypot(x, y), Math.hypot(r.width - x, y),
                Math.hypot(x, r.height - y), Math.hypot(r.width - x, r.height - y));
              const size = Math.ceil(far * 2 + 8);
              const ink = document.createElement('span');
              ink.className = 'mfrp-ink';
              ink.style.width = ink.style.height = size + 'px';
              ink.style.left = x + 'px';
              ink.style.top = y + 'px';
              ink.addEventListener('animationend', () => ink.remove(), { signal: ac.signal });
              el.appendChild(ink);
            };

            root.querySelectorAll('[data-ripple]').forEach((el) => {
              el.addEventListener('pointerdown', (e) => spawn(el, e.clientX, e.clientY), { signal: ac.signal });
              el.addEventListener('keydown', (e) => {
                if (e.key !== 'Enter' && e.key !== ' ') return;
                const r = el.getBoundingClientRect();
                spawn(el, r.left + r.width / 2, r.top + r.height / 2);
              }, { signal: ac.signal });
            });
            return () => ac.abort();
          }, []);

          return (
            <>
              <style>{`
                .mfrp-card { position: relative; overflow: clip; max-inline-size: 26rem; padding: 1.4rem;
                             border-radius: 1rem; border: 1px solid #e7e5e4; background: #fff; }
                .mfrp-surf { position: relative; overflow: clip; }
                .mfrp-ink { position: absolute; border-radius: 50%; background: currentColor;
                            translate: -50% -50%; pointer-events: none;
                            animation: mfrp-grow .55s cubic-bezier(.2, 0, 0, 1) forwards; }
                @keyframes mfrp-grow { from { scale: 0; opacity: .3; } to { scale: 1; opacity: 0; } }
                .mfrp-tabs { display: inline-flex; gap: .25rem; margin-block: 1rem; }
                .mfrp-tabs button { position: relative; overflow: clip; padding: .5rem .9rem; border: none;
                                    border-radius: 999px; background: transparent; cursor: pointer; }
                .mfrp-tabs button[aria-selected='true'] { background: #4f46e5; color: #fff; }
                .mfrp-primary { block-size: 40px; padding-inline: 18px; border: none; border-radius: 999px;
                                background: #4f46e5; color: #fff; cursor: pointer; }
                .mfrp-ghost { block-size: 40px; padding-inline: 18px; border-radius: 999px; border: 1px solid #e7e5e4;
                              background: transparent; cursor: pointer; }
              `}</style>
              <article ref={rootRef} className="mfrp-card mfrp-surf" data-ripple>
                <h3>Atlas Studio invoice</h3>
                <div className="mfrp-tabs" role="tablist">
                  {TABS.map((t) => (
                    <button key={t.id} role="tab" data-ripple aria-selected={tab === t.id}
                      onClick={() => setTab(t.id)}>{t.label}</button>
                  ))}
                </div>
                <p>{TABS.find((t) => t.id === tab).body}</p>
                <footer>
                  <button className="mfrp-primary" data-ripple>Download PDF</button>{' '}
                  <button className="mfrp-ghost" data-ripple>Share</button>
                </footer>
              </article>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- RippleCard.vue — the ripple that answers pointer and keyboard alike -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const TABS = [
          { id: 'overview', label: 'Overview', body: '3 projects · 18 invoices · €4,200 total' },
          { id: 'activity', label: 'Activity', body: 'PDF export run by Lena, 4 min ago' },
          { id: 'billing', label: 'Billing', body: 'Atlas Studio · Prinsengracht 1, Amsterdam' },
        ];
        const root = ref<HTMLElement | null>(null);
        const tab = ref('overview');
        let ac: AbortController | null = null;

        onMounted(() => {
          ac = new AbortController();
          const mq = matchMedia('(prefers-reduced-motion: reduce)');
          const spawn = (el: Element, cx: number, cy: number) => {
            if (mq.matches) return;
            const r = el.getBoundingClientRect();
            const x = cx - r.left, y = cy - r.top;
            const far = Math.max(
              Math.hypot(x, y), Math.hypot(r.width - x, y),
              Math.hypot(x, r.height - y), Math.hypot(r.width - x, r.height - y));
            const size = Math.ceil(far * 2 + 8);
            const ink = document.createElement('span');
            ink.className = 'mfrp-ink';
            (ink.style as any).width = (ink.style as any).height = size + 'px';
            ink.style.left = x + 'px';
            ink.style.top = y + 'px';
            ink.addEventListener('animationend', () => ink.remove(), { signal: ac!.signal });
            el.appendChild(ink);
          };
          root.value!.querySelectorAll('[data-ripple]').forEach((el) => {
            el.addEventListener('pointerdown', (e: PointerEvent) => spawn(el, e.clientX, e.clientY), { signal: ac!.signal });
            el.addEventListener('keydown', (e: KeyboardEvent) => {
              if (e.key !== 'Enter' && e.key !== ' ') return;
              const r = el.getBoundingClientRect();
              spawn(el, r.left + r.width / 2, r.top + r.height / 2);
            }, { signal: ac!.signal });
          });
        });
        onBeforeUnmount(() => ac?.abort());
        </script>

        <template>
          <article ref="root" class="mfrp-card mfrp-surf" data-ripple>
            <h3>Atlas Studio invoice</h3>
            <div class="mfrp-tabs" role="tablist">
              <button v-for="t in TABS" :key="t.id" role="tab" data-ripple
                :aria-selected="tab === t.id" @click="tab = t.id">{{ t.label }}</button>
            </div>
            <p>{{ TABS.find((t) => t.id === tab)!.body }}</p>
            <footer>
              <button class="mfrp-primary" data-ripple>Download PDF</button>
              <button class="mfrp-ghost" data-ripple>Share</button>
            </footer>
          </article>
        </template>

        <style scoped>
        .mfrp-card { position: relative; overflow: clip; max-inline-size: 26rem; padding: 1.4rem;
                     border-radius: 1rem; border: 1px solid #e7e5e4; background: #fff; }
        .mfrp-surf { position: relative; overflow: clip; }
        .mfrp-ink { position: absolute; border-radius: 50%; background: currentColor;
                    translate: -50% -50%; pointer-events: none;
                    animation: mfrp-grow .55s cubic-bezier(.2, 0, 0, 1) forwards; }
        @keyframes mfrp-grow { from { scale: 0; opacity: .3; } to { scale: 1; opacity: 0; } }
        .mfrp-tabs { display: inline-flex; gap: .25rem; margin-block: 1rem; }
        .mfrp-tabs button { position: relative; overflow: clip; padding: .5rem .9rem; border: none;
                            border-radius: 999px; background: transparent; cursor: pointer; }
        .mfrp-tabs button[aria-selected='true'] { background: #4f46e5; color: #fff; }
        .mfrp-primary { block-size: 40px; padding-inline: 18px; border: none; border-radius: 999px;
                        background: #4f46e5; color: #fff; cursor: pointer; }
        .mfrp-ghost { block-size: 40px; padding-inline: 18px; border-radius: 999px; border: 1px solid #e7e5e4;
                      background: transparent; cursor: pointer; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- RippleCard.svelte — the ripple that answers pointer and keyboard alike -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          const TABS = [
            { id: 'overview', label: 'Overview', body: '3 projects · 18 invoices · €4,200 total' },
            { id: 'activity', label: 'Activity', body: 'PDF export run by Lena, 4 min ago' },
            { id: 'billing', label: 'Billing', body: 'Atlas Studio · Prinsengracht 1, Amsterdam' },
          ];
          let root: HTMLElement;
          let tab = $state('overview');

          onMount(() => {
            const ac = new AbortController();
            const mq = matchMedia('(prefers-reduced-motion: reduce)');
            const spawn = (el: Element, cx: number, cy: number) => {
              if (mq.matches) return;
              const r = el.getBoundingClientRect();
              const x = cx - r.left, y = cy - r.top;
              const far = Math.max(
                Math.hypot(x, y), Math.hypot(r.width - x, y),
                Math.hypot(x, r.height - y), Math.hypot(r.width - x, r.height - y));
              const size = Math.ceil(far * 2 + 8);
              const ink = document.createElement('span');
              ink.className = 'mfrp-ink';
              ink.style.width = ink.style.height = size + 'px';
              ink.style.left = x + 'px';
              ink.style.top = y + 'px';
              ink.addEventListener('animationend', () => ink.remove(), { signal: ac.signal });
              el.appendChild(ink);
            };
            root.querySelectorAll('[data-ripple]').forEach((el) => {
              el.addEventListener('pointerdown', (e: PointerEvent) => spawn(el, e.clientX, e.clientY), { signal: ac.signal });
              el.addEventListener('keydown', (e: KeyboardEvent) => {
                if (e.key !== 'Enter' && e.key !== ' ') return;
                const r = el.getBoundingClientRect();
                spawn(el, r.left + r.width / 2, r.top + r.height / 2);
              }, { signal: ac.signal });
            });
            return () => ac.abort();
          });
        </script>

        <article bind:this={root} class="mfrp-card mfrp-surf" data-ripple>
          <h3>Atlas Studio invoice</h3>
          <div class="mfrp-tabs" role="tablist">
            {#each TABS as t (t.id)}
              <button role="tab" data-ripple aria-selected={tab === t.id} onclick={() => (tab = t.id)}>
                {t.label}
              </button>
            {/each}
          </div>
          <p>{TABS.find((t) => t.id === tab)!.body}</p>
          <footer>
            <button class="mfrp-primary" data-ripple>Download PDF</button>
            <button class="mfrp-ghost" data-ripple>Share</button>
          </footer>
        </article>

        <style>
        .mfrp-card { position: relative; overflow: clip; max-inline-size: 26rem; padding: 1.4rem;
                     border-radius: 1rem; border: 1px solid #e7e5e4; background: #fff; }
        .mfrp-surf { position: relative; overflow: clip; }
        .mfrp-ink { position: absolute; border-radius: 50%; background: currentColor;
                    translate: -50% -50%; pointer-events: none;
                    animation: mfrp-grow .55s cubic-bezier(.2, 0, 0, 1) forwards; }
        @keyframes mfrp-grow { from { scale: 0; opacity: .3; } to { scale: 1; opacity: 0; } }
        .mfrp-tabs { display: inline-flex; gap: .25rem; margin-block: 1rem; }
        .mfrp-tabs button { position: relative; overflow: clip; padding: .5rem .9rem; border: none;
                            border-radius: 999px; background: transparent; cursor: pointer; }
        .mfrp-tabs button[aria-selected='true'] { background: #4f46e5; color: #fff; }
        .mfrp-primary { block-size: 40px; padding-inline: 18px; border: none; border-radius: 999px;
                        background: #4f46e5; color: #fff; cursor: pointer; }
        .mfrp-ghost { block-size: 40px; padding-inline: 18px; border-radius: 999px; border: 1px solid #e7e5e4;
                      background: transparent; cursor: pointer; }
        </style>
        SVELTE,
        ],
    ],
];
