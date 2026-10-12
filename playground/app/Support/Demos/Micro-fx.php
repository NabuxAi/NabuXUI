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

    'drag-spring' => [
        'title' => ['fa' => 'کشیدن فنری', 'en' => 'Drag spring'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'توکن «هنر وکتور» را از سینی مهارت‌های استودیو ولا بکشید و در خانهٔ «مهارت‌های اصلی» رها کنید: یک انتگرال‌گیر فنرِ نیمه‌ضمنی موقع کشیدن با ثابت‌های سفت چسبیده به انگشت است و بعد از رها شدن با k=170 و c=12 دو-سه بار لرزشی می‌نشیند؛ خانهٔ رهاسازی در شعاع ۷۲px توکن را می‌گیرد و با تیک تأیید می‌زند — pointer capture لمس را زنده نگه می‌دارد و Enter/Space هم بدون ماوس انتصاب می‌دهد.',
            'en' => 'Drag the “Vector art” token from Vela Studio’s tray into the “Core skills” slot: one semi-implicit spring integrator chases the pointer with stiff constants while you hold it, then settles with k=170, c=12 — two–three visible overshoots; the slot grips within a 72px capture radius and confirms with a check. Pointer capture keeps touch alive, and Enter/Space assigns with no mouse at all.',
        ],
        'js' => true,
        'docs' => 'https://motion-primitives.com',
        'props' => [
            ['name' => 'stiffness', 'type' => 's⁻²', 'default' => "'170'", 'note' => [
                'fa' => 'ثابت فنر پس از رها شدن؛ ω≈۱۳ راد بر ثانیه یعنی نشستن در حدود نیم ثانیه.',
                'en' => 'The release spring constant; ω≈13 rad/s lands the token in about half a second.',
            ]],
            ['name' => 'damping', 'type' => 'coefficient', 'default' => "'12 (ζ≈0.46)'", 'note' => [
                'fa' => 'زیرمیرایی عمدی؛ حدود دو تا سه فراجهش قابل‌دید قبل از توقف.',
                'en' => 'Deliberately underdamped; two to three visible overshoots before it rests.',
            ]],
            ['name' => 'dragSpring', 'type' => 'mode', 'default' => "'k 600 · c 40'", 'note' => [
                'fa' => 'ثابت‌های سفتِ حین کشیدن؛ توکن مثل قطرهٔ چسبناک با فاصلهٔ کوتاهی دنبال انگشت می‌آید.',
                'en' => 'The stiff constants while dragging; the token trails the finger like a sticky bead.',
            ]],
            ['name' => 'gripRadius', 'type' => 'px', 'default' => "'72'", 'note' => [
                'fa' => 'شعاع گیرش خانهٔ رهاسازی؛ مرکز توکن داخل این دایره بیاید، خانه هایلایت و رهاسازی می‌گیرد.',
                'en' => 'The drop slot’s capture radius; once the token centre enters, the slot highlights and grips the release.',
            ]],
            ['name' => 'prefers-reduced-motion', 'type' => 'behaviour', 'default' => "'teleport'", 'note' => [
                'fa' => 'بدون فنر و لرزش؛ توکن مستقیم در مقصد می‌نشیند و تأیید همان‌جا ظاهر می‌شود.',
                'en' => 'No spring, no wobble; the token teleports to its destination and the confirmation appears in place.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        {{-- dragSpring(): one semi-implicit spring integrator; stiff while
             dragging (k 600 · c 40), bouncy after release (k 170 · c 12);
             pointer capture keeps touch alive; Enter/Space assigns too. --}}
        <div class="mfds" x-data="dragSpring()">
          <div class="mfds-stage">
            <div class="mfds-home" x-ref="home">
              <span class="mfds-pad-label">سینی مهارت‌ها</span>
              <button type="button" class="mfds-token" x-ref="token"
                      x-bind:data-dragging="dragging" x-bind:data-locked="held"
                      @pointerdown="grab($event)" @pointermove="drag($event)"
                      @pointerup="drop($event)" @pointercancel="drop($event)"
                      @keydown.enter.prevent="toggle()" @keydown.space.prevent="toggle()">هنر وکتور</button>
            </div>
            <div class="mfds-slot" x-ref="slot" x-bind:data-near="near" x-bind:data-held="held">
              <span class="mfds-pad-label">مهارت‌های اصلی</span>
              <svg class="mfds-check" x-show="settled" viewBox="0 0 24 24"><path d="m5 12.5 4.5 4.5L19 7.5"/></svg>
            </div>
          </div>
          <button type="button" class="mfds-reset" x-on:click="unassign()" x-show="held" x-cloak>بازنشانی</button>
        </div>

        <style>
        .mfds-stage { display: flex; flex-wrap: wrap; align-items: center; justify-content: center;
                      gap: 2.5rem 3rem; padding: 1.6rem 1.2rem; border-radius: 1.1rem; overflow: clip;
                      background: #fafaf9; }
        .mfds-home, .mfds-slot { display: grid; place-items: center; gap: .5rem; min-block-size: 7rem;
                                 min-inline-size: 9rem; padding: .9rem; border-radius: 1rem;
                                 border: 1.5px dashed #d6d3d1; }
        .mfds-slot[data-near='true'] { border-color: #4f46e5; }
        .mfds-slot[data-held='true'] { border-style: solid; border-color: #4f46e5; background: #eef2ff; }
        .mfds-token { position: relative; z-index: 2; padding: .6rem 1.1rem; border-radius: 999px;
                      border: 1px solid #e7e5e4; background: #fff; cursor: grab;
                      touch-action: none; user-select: none; will-change: transform; }
        .mfds-token[data-dragging='true'] { cursor: grabbing; }
        .mfds-check { inline-size: 1.6rem; block-size: 1.6rem; stroke: #4f46e5; stroke-width: 2.4;
                      fill: none; stroke-linecap: round; stroke-linejoin: round; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        {{-- dragSpring(): one semi-implicit spring integrator; stiff while
             dragging (k 600 · c 40), bouncy after release (k 170 · c 12);
             pointer capture keeps touch alive; Enter/Space assigns too. --}}
        <div class="mfds" x-data="dragSpring()">
          <div class="mfds-stage">
            <div class="mfds-home" x-ref="home">
              <span class="mfds-pad-label">Skills tray</span>
              <button type="button" class="mfds-token" x-ref="token"
                      x-bind:data-dragging="dragging" x-bind:data-locked="held"
                      @pointerdown="grab($event)" @pointermove="drag($event)"
                      @pointerup="drop($event)" @pointercancel="drop($event)"
                      @keydown.enter.prevent="toggle()" @keydown.space.prevent="toggle()">Vector art</button>
            </div>
            <div class="mfds-slot" x-ref="slot" x-bind:data-near="near" x-bind:data-held="held">
              <span class="mfds-pad-label">Core skills</span>
              <svg class="mfds-check" x-show="settled" viewBox="0 0 24 24"><path d="m5 12.5 4.5 4.5L19 7.5"/></svg>
            </div>
          </div>
          <button type="button" class="mfds-reset" x-on:click="unassign()" x-show="held" x-cloak>Reset</button>
        </div>

        <style>
        .mfds-stage { display: flex; flex-wrap: wrap; align-items: center; justify-content: center;
                      gap: 2.5rem 3rem; padding: 1.6rem 1.2rem; border-radius: 1.1rem; overflow: clip;
                      background: #fafaf9; }
        .mfds-home, .mfds-slot { display: grid; place-items: center; gap: .5rem; min-block-size: 7rem;
                                 min-inline-size: 9rem; padding: .9rem; border-radius: 1rem;
                                 border: 1.5px dashed #d6d3d1; }
        .mfds-slot[data-near='true'] { border-color: #4f46e5; }
        .mfds-slot[data-held='true'] { border-style: solid; border-color: #4f46e5; background: #eef2ff; }
        .mfds-token { position: relative; z-index: 2; padding: .6rem 1.1rem; border-radius: 999px;
                      border: 1px solid #e7e5e4; background: #fff; cursor: grab;
                      touch-action: none; user-select: none; will-change: transform; }
        .mfds-token[data-dragging='true'] { cursor: grabbing; }
        .mfds-check { inline-size: 1.6rem; block-size: 1.6rem; stroke: #4f46e5; stroke-width: 2.4;
                      fill: none; stroke-linecap: round; stroke-linejoin: round; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Onboarding.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import SkillToken from '@/components/SkillToken';

        export default function Onboarding() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <SkillToken />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // SkillToken.jsx — drag with a real spring, drop target that grips
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        export default function SkillToken() {
          const tokenRef = useRef(null);
          const slotRef = useRef(null);
          const st = useRef({ x: 0, y: 0, vx: 0, vy: 0, tx: 0, ty: 0, sx: 0, sy: 0,
                              dragging: false, held: false, raf: 0, last: 0 });
          const [held, setHeld] = useState(false);

          useEffect(() => {
            const tk = tokenRef.current, s = st.current;
            const rm = matchMedia('(prefers-reduced-motion: reduce)');
            const paint = () => { tk.style.transform = `translate(${s.x}px, ${s.y}px)`; };
            const slotTarget = () => {
              const r = tk.getBoundingClientRect(), q = slotRef.current.getBoundingClientRect();
              return { x: q.left + q.width / 2 - (r.left + r.width / 2) + s.x,
                       y: q.top + q.height / 2 - (r.top + r.height / 2) + s.y };
            };
            const step = (now) => {
              const dt = Math.min(0.032, (now - s.last) / 1000) || 0.016; s.last = now;
              const k = s.dragging ? 600 : 170, c = s.dragging ? 40 : 12;
              s.vx += (-k * (s.x - s.tx) - c * s.vx) * dt;
              s.vy += (-k * (s.y - s.ty) - c * s.vy) * dt;
              s.x += s.vx * dt; s.y += s.vy * dt; paint();
              const rest = Math.hypot(s.x - s.tx, s.y - s.ty) < 0.6 && Math.hypot(s.vx, s.vy) < 8;
              if (!s.dragging && rest) {
                s.x = s.tx; s.y = s.ty; s.vx = s.vy = 0; paint(); s.raf = 0;
                if (s.held) setHeld(true);
                return;
              }
              s.raf = requestAnimationFrame(step);
            };
            const run = () => { if (!s.raf) { s.last = 0; s.raf = requestAnimationFrame(step); } };
            const near = () => {
              const r = tk.getBoundingClientRect(), q = slotRef.current.getBoundingClientRect();
              return Math.hypot(r.left + r.width / 2 - (q.left + q.width / 2),
                                r.top + r.height / 2 - (q.top + q.height / 2)) < 72;
            };
            const down = (e) => {
              if (s.held || (e.pointerType === 'mouse' && e.button !== 0)) return;
              s.dragging = true; s.sx = e.clientX; s.sy = e.clientY;
              tk.setPointerCapture(e.pointerId); run();
            };
            const move = (e) => {
              if (!s.dragging) return;
              s.tx = e.clientX - s.sx; s.ty = e.clientY - s.sy;
              if (rm.matches) { s.x = s.tx; s.y = s.ty; paint(); }
            };
            const up = (e) => {
              if (!s.dragging) return;
              s.dragging = false; tk.releasePointerCapture(e.pointerId);
              if (near()) { s.held = true; const t = slotTarget(); s.tx = t.x; s.ty = t.y; }
              else { s.held = false; s.tx = 0; s.ty = 0; setHeld(false); }
              if (rm.matches) { s.x = s.tx; s.y = s.ty; paint(); return; }
              run();
            };
            const key = (e) => {
              if (e.key !== 'Enter' && e.key !== ' ') return;
              e.preventDefault();
              if (s.held) { s.held = false; s.tx = 0; s.ty = 0; setHeld(false); }
              else { s.held = true; const t = slotTarget(); s.tx = t.x; s.ty = t.y; }
              if (rm.matches) { s.x = s.tx; s.y = s.ty; paint(); if (s.held) setHeld(true); return; }
              run();
            };
            tk.addEventListener('pointerdown', down);
            tk.addEventListener('pointermove', move);
            tk.addEventListener('pointerup', up);
            tk.addEventListener('pointercancel', up);
            tk.addEventListener('keydown', key);
            return () => {
              cancelAnimationFrame(s.raf);
              tk.removeEventListener('pointerdown', down);
              tk.removeEventListener('pointermove', move);
              tk.removeEventListener('pointerup', up);
              tk.removeEventListener('pointercancel', up);
              tk.removeEventListener('keydown', key);
            };
          }, []);

          return (
            <>
              <style>{`
                .mfds-stage { display: flex; flex-wrap: wrap; align-items: center; justify-content: center;
                              gap: 2.5rem 3rem; padding: 1.6rem 1.2rem; border-radius: 1.1rem; overflow: clip;
                              background: #fafaf9; }
                .mfds-home, .mfds-slot { display: grid; place-items: center; gap: .5rem; min-block-size: 7rem;
                                         min-inline-size: 9rem; padding: .9rem; border-radius: 1rem;
                                         border: 1.5px dashed #d6d3d1; font: 400 .9rem system-ui; }
                .mfds-slot[data-held='true'] { border-style: solid; border-color: #4f46e5; background: #eef2ff; }
                .mfds-token { position: relative; z-index: 2; padding: .6rem 1.1rem; border-radius: 999px;
                              border: 1px solid #e7e5e4; background: #fff; cursor: grab;
                              touch-action: none; user-select: none; will-change: transform; }
                .mfds-check { inline-size: 1.6rem; block-size: 1.6rem; stroke: #4f46e5; stroke-width: 2.4;
                              fill: none; stroke-linecap: round; stroke-linejoin: round; }
              `}</style>
              <div className="mfds-stage">
                <div className="mfds-home"><span>Skills tray</span>
                  <button ref={tokenRef} type="button" className="mfds-token" aria-pressed={held}>Vector art</button>
                </div>
                <div className="mfds-slot" data-held={held ? 'true' : 'false'}><span>Core skills</span>
                  {held && <svg className="mfds-check" viewBox="0 0 24 24"><path d="m5 12.5 4.5 4.5L19 7.5"/></svg>}
                </div>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- SkillToken.vue — drag with a real spring, drop target that grips -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const token = ref<HTMLElement | null>(null);
        const slot = ref<HTMLElement | null>(null);
        const held = ref(false);
        const s = { x: 0, y: 0, vx: 0, vy: 0, tx: 0, ty: 0, sx: 0, sy: 0, dragging: false, toSlot: false, raf: 0, last: 0 };

        const paint = () => { token.value!.style.transform = `translate(${s.x}px, ${s.y}px)`; };
        const slotTarget = () => {
          const r = token.value!.getBoundingClientRect(), q = slot.value!.getBoundingClientRect();
          return { x: q.left + q.width / 2 - (r.left + r.width / 2) + s.x,
                   y: q.top + q.height / 2 - (r.top + r.height / 2) + s.y };
        };
        const step = (now: number) => {
          const dt = Math.min(0.032, (now - s.last) / 1000) || 0.016; s.last = now;
          const k = s.dragging ? 600 : 170, c = s.dragging ? 40 : 12;
          s.vx += (-k * (s.x - s.tx) - c * s.vx) * dt;
          s.vy += (-k * (s.y - s.ty) - c * s.vy) * dt;
          s.x += s.vx * dt; s.y += s.vy * dt; paint();
          const rest = Math.hypot(s.x - s.tx, s.y - s.ty) < 0.6 && Math.hypot(s.vx, s.vy) < 8;
          if (!s.dragging && rest) {
            s.x = s.tx; s.y = s.ty; s.vx = s.vy = 0; paint(); s.raf = 0;
            held.value = s.toSlot;
            return;
          }
          s.raf = requestAnimationFrame(step);
        };
        const run = () => { if (!s.raf) { s.last = 0; s.raf = requestAnimationFrame(step); } };

        const down = (e: PointerEvent) => {
          if (e.pointerType === 'mouse' && e.button !== 0) return;
          s.dragging = true; s.sx = e.clientX; s.sy = e.clientY;
          token.value!.setPointerCapture(e.pointerId); run();
        };
        const move = (e: PointerEvent) => {
          if (!s.dragging) return;
          s.tx = e.clientX - s.sx; s.ty = e.clientY - s.sy;
        };
        const up = (e: PointerEvent) => {
          if (!s.dragging) return;
          s.dragging = false; token.value!.releasePointerCapture(e.pointerId);
          const r = token.value!.getBoundingClientRect(), q = slot.value!.getBoundingClientRect();
          const near = Math.hypot(r.left + r.width / 2 - (q.left + q.width / 2),
                                  r.top + r.height / 2 - (q.top + q.height / 2)) < 72;
          if (near) { const t = slotTarget(); s.tx = t.x; s.ty = t.y; s.toSlot = true; }
          else { s.tx = 0; s.ty = 0; s.toSlot = false; }
          run();
        };

        onMounted(() => {
          token.value!.addEventListener('pointerdown', down);
          token.value!.addEventListener('pointermove', move);
          token.value!.addEventListener('pointerup', up);
          token.value!.addEventListener('pointercancel', up);
        });
        onBeforeUnmount(() => {
          cancelAnimationFrame(s.raf);
          token.value!.removeEventListener('pointerdown', down);
          token.value!.removeEventListener('pointermove', move);
          token.value!.removeEventListener('pointerup', up);
          token.value!.removeEventListener('pointercancel', up);
        });
        </script>

        <template>
          <div class="mfds-stage">
            <div class="mfds-home"><span>Skills tray</span>
              <button ref="token" type="button" class="mfds-token" :aria-pressed="held">Vector art</button>
            </div>
            <div ref="slot" class="mfds-slot" :data-held="held"><span>Core skills</span>
              <svg v-if="held" class="mfds-check" viewBox="0 0 24 24"><path d="m5 12.5 4.5 4.5L19 7.5"/></svg>
            </div>
          </div>
        </template>

        <style scoped>
        .mfds-stage { display: flex; flex-wrap: wrap; align-items: center; justify-content: center;
                      gap: 2.5rem 3rem; padding: 1.6rem 1.2rem; border-radius: 1.1rem; overflow: clip;
                      background: #fafaf9; }
        .mfds-home, .mfds-slot { display: grid; place-items: center; gap: .5rem; min-block-size: 7rem;
                                 min-inline-size: 9rem; padding: .9rem; border-radius: 1rem;
                                 border: 1.5px dashed #d6d3d1; }
        .mfds-slot[data-held='true'] { border-style: solid; border-color: #4f46e5; background: #eef2ff; }
        .mfds-token { position: relative; z-index: 2; padding: .6rem 1.1rem; border-radius: 999px;
                      border: 1px solid #e7e5e4; background: #fff; cursor: grab;
                      touch-action: none; user-select: none; will-change: transform; }
        .mfds-check { inline-size: 1.6rem; block-size: 1.6rem; stroke: #4f46e5; stroke-width: 2.4;
                      fill: none; stroke-linecap: round; stroke-linejoin: round; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- SkillToken.svelte — drag with a real spring, drop target that grips -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          let token: HTMLElement, slot: HTMLElement;
          let held = $state(false);
          const s = { x: 0, y: 0, vx: 0, vy: 0, tx: 0, ty: 0, sx: 0, sy: 0, dragging: false, toSlot: false, raf: 0, last: 0 };

          const paint = () => { token.style.transform = `translate(${s.x}px, ${s.y}px)`; };
          const slotTarget = () => {
            const r = token.getBoundingClientRect(), q = slot.getBoundingClientRect();
            return { x: q.left + q.width / 2 - (r.left + r.width / 2) + s.x,
                     y: q.top + q.height / 2 - (r.top + r.height / 2) + s.y };
          };
          const step = (now: number) => {
            const dt = Math.min(0.032, (now - s.last) / 1000) || 0.016; s.last = now;
            const k = s.dragging ? 600 : 170, c = s.dragging ? 40 : 12;
            s.vx += (-k * (s.x - s.tx) - c * s.vx) * dt;
            s.vy += (-k * (s.y - s.ty) - c * s.vy) * dt;
            s.x += s.vx * dt; s.y += s.vy * dt; paint();
            const rest = Math.hypot(s.x - s.tx, s.y - s.ty) < 0.6 && Math.hypot(s.vx, s.vy) < 8;
            if (!s.dragging && rest) {
              s.x = s.tx; s.y = s.ty; s.vx = s.vy = 0; paint(); s.raf = 0;
              held = s.toSlot;
              return;
            }
            s.raf = requestAnimationFrame(step);
          };
          const run = () => { if (!s.raf) { s.last = 0; s.raf = requestAnimationFrame(step); } };

          const down = (e: PointerEvent) => {
            if (e.pointerType === 'mouse' && e.button !== 0) return;
            s.dragging = true; s.sx = e.clientX; s.sy = e.clientY;
            token.setPointerCapture(e.pointerId); run();
          };
          const move = (e: PointerEvent) => {
            if (!s.dragging) return;
            s.tx = e.clientX - s.sx; s.ty = e.clientY - s.sy;
          };
          const up = (e: PointerEvent) => {
            if (!s.dragging) return;
            s.dragging = false; token.releasePointerCapture(e.pointerId);
            const r = token.getBoundingClientRect(), q = slot.getBoundingClientRect();
            const near = Math.hypot(r.left + r.width / 2 - (q.left + q.width / 2),
                                    r.top + r.height / 2 - (q.top + q.height / 2)) < 72;
            if (near) { const t = slotTarget(); s.tx = t.x; s.ty = t.y; s.toSlot = true; }
            else { s.tx = 0; s.ty = 0; s.toSlot = false; }
            run();
          };

          onMount(() => {
            token.addEventListener('pointerdown', down);
            token.addEventListener('pointermove', move);
            token.addEventListener('pointerup', up);
            token.addEventListener('pointercancel', up);
            return () => {
              cancelAnimationFrame(s.raf);
              token.removeEventListener('pointerdown', down);
              token.removeEventListener('pointermove', move);
              token.removeEventListener('pointerup', up);
              token.removeEventListener('pointercancel', up);
            };
          });
        </script>

        <div class="mfds-stage">
          <div class="mfds-home"><span>Skills tray</span>
            <button bind:this={token} type="button" class="mfds-token" aria-pressed={held}>Vector art</button>
          </div>
          <div bind:this={slot} class="mfds-slot" data-held={held}><span>Core skills</span>
            {#if held}<svg class="mfds-check" viewBox="0 0 24 24"><path d="m5 12.5 4.5 4.5L19 7.5"/></svg>{/if}
          </div>
        </div>

        <style>
        .mfds-stage { display: flex; flex-wrap: wrap; align-items: center; justify-content: center;
                      gap: 2.5rem 3rem; padding: 1.6rem 1.2rem; border-radius: 1.1rem; overflow: clip;
                      background: #fafaf9; }
        .mfds-home, .mfds-slot { display: grid; place-items: center; gap: .5rem; min-block-size: 7rem;
                                 min-inline-size: 9rem; padding: .9rem; border-radius: 1rem;
                                 border: 1.5px dashed #d6d3d1; }
        .mfds-slot[data-held='true'] { border-style: solid; border-color: #4f46e5; background: #eef2ff; }
        .mfds-token { position: relative; z-index: 2; padding: .6rem 1.1rem; border-radius: 999px;
                      border: 1px solid #e7e5e4; background: #fff; cursor: grab;
                      touch-action: none; user-select: none; will-change: transform; }
        .mfds-check { inline-size: 1.6rem; block-size: 1.6rem; stroke: #4f46e5; stroke-width: 2.4;
                      fill: none; stroke-linecap: round; stroke-linejoin: round; }
        </style>
        SVELTE,
        ],
    ],

    'reaction-bar' => [
        'title' => ['fa' => 'نوار ری‌اکشن', 'en' => 'Reaction bar'],
        'icon' => 'heart',
        'oneLiner' => [
            'fa' => 'نوار ری‌اکشن زیر اعلام انتشار لومن کنواس: قلب، ستاره، درخشش و شعلهٔ انتزاعی، تک‌انتخابی با امکان برداشتن. با هر انتخاب شانزده پارتیکل از سر دکمه به هوا می‌پرد و شمارندهٔ هر دکمه مثل اسطوانهٔ شمارش با چرخش عمودی می‌غلتد؛ دوباره زدن همان دکمه ری‌اکشن را برمی‌دارد و شمارنده برمی‌گردد.',
            'en' => 'The reaction bar under Lumen Canvas’s release note: heart, star, sparkles and an abstract flame — single-select, toggleable off. Each pick throws sixteen particles from the top of the button while its counter rolls like an odometer on a vertical strip; pressing the same button again takes the reaction back and the count rolls home.',
        ],
        'js' => true,
        'docs' => 'https://motion-primitives.com',
        'props' => [
            ['name' => 'reactions', 'type' => 'list', 'default' => "'heart · star · sparkles · flame'", 'note' => [
                'fa' => 'چهار ری‌اکشن با رنگ مؤکد خودشان؛ دکمهٔ فعال رنگ خودش را می‌گیرد و بقیه خاموش می‌مانند.',
                'en' => 'Four reactions, each with its own accent; the active button takes its colour while the rest stay dim.',
            ]],
            ['name' => 'particles', 'type' => 'number', 'default' => "'16'", 'note' => [
                'fa' => 'ذرات هر انتخاب؛ دایره و مربع و مثلث کوچک با جاذبهٔ ملایم که حدود ۰٫۹ ثانیه عمر دارند.',
                'en' => 'Pieces per pick; small circles, squares and triangles under a gentle gravity, living about 0.9s.',
            ]],
            ['name' => 'roll', 'type' => 'motion', 'default' => "'translateY · 480ms'", 'note' => [
                'fa' => 'چرخش عمودی شمارنده: هر رقم نوار ۰ تا ۹ خودش را دارد و با انتقال دamped می‌غلتد، فارسی با ارقام ۰ تا ۹ فارسی.',
                'en' => 'The counter’s vertical roll: every digit owns a 0–9 strip and glides with a damped transition — Persian locales roll ۰–۹ strips.',
            ]],
            ['name' => 'selection', 'type' => 'behaviour', 'default' => "'single · toggle-off'", 'note' => [
                'fa' => 'فقط یک ری‌اکشن همزمان؛ انتخاب دکمهٔ دیگر قبلی را بی‌پارتیکل جابه‌جا می‌کند و دوباره زدن همان دکمه آن را برمی‌دارد.',
                'en' => 'Only one reaction at a time; picking another moves it without particles and pressing the same one removes it.',
            ]],
            ['name' => 'prefers-reduced-motion', 'type' => 'behaviour', 'default' => "'calm'", 'note' => [
                'fa' => 'بدون پارتیکل و بدون غلتش؛ ارقام فوری در مقدار نهایی می‌ایستند.',
                'en' => 'No particles, no roll; digits land in their final value instantly.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        {{-- reactionBar(): canvas particles on pick + odometer counters;
             single-select, press again to take it back. --}}
        <div class="mfrb" x-data="reactionBar()">
          <div class="mfrb-card" x-ref="card">
            <canvas class="mfrb-cv" x-ref="cv" aria-hidden="true"></canvas>
            <p class="mfrb-msg">نسخهٔ ۳٫۲ لومن کنواس منتشر شد: حالت تیره، جست‌وجوی ۴۰٪ سریع‌تر و مکان‌نمای مشترک روی بوم.</p>
            <div class="mfrb-bar" role="group" aria-label="ری‌اکشن‌ها">
              <template x-for="r in list" :key="r.id">
                <button type="button" class="mfrb-btn" x-bind:style="'--mfrb-tint:' + r.tint"
                        x-bind:aria-pressed="sel === r.id ? 'true' : 'false'"
                        x-bind:aria-label="r.fa + '، ' + faNum(r.n)"
                        @pointerdown="pick(r.id)" @keydown.enter.prevent="pick(r.id)" @keydown.space.prevent="pick(r.id)">
                  <span class="mfrb-ico" x-html="r.icon"></span>
                  <span class="mfrb-odo" dir="ltr">
                    <template x-for="(ch, i) in Array.from(String(r.n))" :key="i">
                      <span class="mfrb-col"><span class="mfrb-strip" x-bind:style="'translate: 0 ' + (-ch * 10) + '%'">
                        <template x-for="d in 10" :key="d"><span class="mfrb-cell" x-text="faDigit(d - 1)"></span></template>
                      </span></span>
                    </template>
                  </span>
                </button>
              </template>
            </div>
          </div>
        </div>

        <style>
        .mfrb-card { position: relative; overflow: clip; max-inline-size: 30rem; padding: 1.2rem 1.4rem;
                     border: 1px solid #e7e5e4; border-radius: 1rem; background: #fff; }
        .mfrb-cv { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; pointer-events: none; }
        .mfrb-bar { display: flex; flex-wrap: wrap; gap: .45rem; margin-block-start: .9rem; }
        .mfrb-btn { display: inline-flex; align-items: center; gap: .45rem; padding: .5rem .85rem;
                    border-radius: 999px; border: 1px solid #e7e5e4; background: #fafaf9; cursor: pointer; }
        .mfrb-btn[aria-pressed='true'] { border-color: var(--mfrb-tint); color: var(--mfrb-tint); }
        .mfrb-ico svg { inline-size: 1rem; block-size: 1rem; stroke: currentColor; stroke-width: 2;
                        fill: none; stroke-linecap: round; stroke-linejoin: round; }
        .mfrb-odo { display: inline-flex; font-variant-numeric: tabular-nums; font-size: .78rem; font-weight: 600; }
        .mfrb-col { block-size: 1.2em; overflow: clip; }
        .mfrb-strip { display: grid; transition: translate .48s cubic-bezier(.2, .7, .2, 1); }
        .mfrb-cell { block-size: 1.2em; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        {{-- reactionBar(): canvas particles on pick + odometer counters;
             single-select, press again to take it back. --}}
        <div class="mfrb" x-data="reactionBar()">
          <div class="mfrb-card" x-ref="card">
            <canvas class="mfrb-cv" x-ref="cv" aria-hidden="true"></canvas>
            <p class="mfrb-msg">Lumen Canvas 3.2 is live: dark mode, 40% faster search and shared cursors on the board.</p>
            <div class="mfrb-bar" role="group" aria-label="Reactions">
              <template x-for="r in list" :key="r.id">
                <button type="button" class="mfrb-btn" x-bind:style="'--mfrb-tint:' + r.tint"
                        x-bind:aria-pressed="sel === r.id ? 'true' : 'false'"
                        x-bind:aria-label="r.en + ', ' + r.n"
                        @pointerdown="pick(r.id)" @keydown.enter.prevent="pick(r.id)" @keydown.space.prevent="pick(r.id)">
                  <span class="mfrb-ico" x-html="r.icon"></span>
                  <span class="mfrb-odo" dir="ltr">
                    <template x-for="(ch, i) in Array.from(String(r.n))" :key="i">
                      <span class="mfrb-col"><span class="mfrb-strip" x-bind:style="'translate: 0 ' + (-ch * 10) + '%'">
                        <template x-for="d in 10" :key="d"><span class="mfrb-cell" x-text="d - 1"></span></template>
                      </span></span>
                    </template>
                  </span>
                </button>
              </template>
            </div>
          </div>
        </div>

        <style>
        .mfrb-card { position: relative; overflow: clip; max-inline-size: 30rem; padding: 1.2rem 1.4rem;
                     border: 1px solid #e7e5e4; border-radius: 1rem; background: #fff; }
        .mfrb-cv { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; pointer-events: none; }
        .mfrb-bar { display: flex; flex-wrap: wrap; gap: .45rem; margin-block-start: .9rem; }
        .mfrb-btn { display: inline-flex; align-items: center; gap: .45rem; padding: .5rem .85rem;
                    border-radius: 999px; border: 1px solid #e7e5e4; background: #fafaf9; cursor: pointer; }
        .mfrb-btn[aria-pressed='true'] { border-color: var(--mfrb-tint); color: var(--mfrb-tint); }
        .mfrb-ico svg { inline-size: 1rem; block-size: 1rem; stroke: currentColor; stroke-width: 2;
                        fill: none; stroke-linecap: round; stroke-linejoin: round; }
        .mfrb-odo { display: inline-flex; font-variant-numeric: tabular-nums; font-size: .78rem; font-weight: 600; }
        .mfrb-col { block-size: 1.2em; overflow: clip; }
        .mfrb-strip { display: grid; transition: translate .48s cubic-bezier(.2, .7, .2, 1); }
        .mfrb-cell { block-size: 1.2em; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Release.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import ReactionBar from '@/components/ReactionBar';

        export default function Release() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <ReactionBar />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // ReactionBar.jsx — particles on pick + odometer counters
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const REACTIONS = [
          { id: 'heart', label: 'Love', tint: '#e11d48',
            icon: 'M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z' },
          { id: 'star', label: 'Useful', tint: '#f59e0b',
            icon: 'm12 3 2.7 5.6 6.1.9-4.4 4.3 1 6.1-5.4-2.9-5.4 2.9 1-6.1L3.2 9.5l6.1-.9Z' },
          { id: 'sparkles', label: 'Inspiring', tint: '#8b5cf6',
            icon: 'M12 3l1.9 5.1L19 10l-5.1 1.9L12 17l-1.9-5.1L5 10l5.1-1.9Z' },
          { id: 'flame', label: 'On fire', tint: '#ea580c',
            icon: 'M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.07-2.14-.22-4.05 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.15.43-2.29 1-3a2.5 2.5 0 0 0 2.5 2.5Z' },
        ];

        const Odo = ({ value }) => (
          <span className="mfrb-odo" dir="ltr" aria-hidden="true">
            {String(value).split('').map((ch, i) => (
              <span key={i} className="mfrb-col">
                <span className="mfrb-strip" style={{ transform: `translateY(-${+ch}0%)` }}>
                  {Array.from({ length: 10 }, (_, d) => <span key={d} className="mfrb-cell">{d}</span>)}
                </span>
              </span>
            ))}
          </span>
        );

        export default function ReactionBar() {
          const cvRef = useRef(null);
          const parts = useRef([]);
          const raf = useRef(0);
          const last = useRef(0);
          const [counts, setCounts] = useState({ heart: 128, star: 96, sparkles: 41, flame: 203 });
          const [sel, setSel] = useState(null);

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
            return () => { ro.disconnect(); cancelAnimationFrame(raf.current); };
          }, []);

          const loop = (now) => {
            const ctx = cvRef.current.getContext('2d');
            const dt = Math.min(0.032, (now - last.current) / 1000) || 0.016;
            last.current = now;
            ctx.clearRect(0, 0, cvRef.current.clientWidth, cvRef.current.clientHeight);
            parts.current = parts.current.filter((p) => {
              p.t += dt; p.vy += 900 * dt; p.x += p.vx * dt; p.y += p.vy * dt; p.rot += p.vr * dt;
              if (p.t > p.life) return false;
              ctx.save();
              ctx.globalAlpha = 1 - p.t / p.life;
              ctx.translate(p.x, p.y); ctx.rotate(p.rot); ctx.fillStyle = p.c;
              ctx.beginPath(); ctx.arc(0, 0, p.r, 0, 6.283); ctx.fill();
              ctx.restore();
              return true;
            });
            raf.current = parts.current.length ? requestAnimationFrame(loop) : 0;
          };

          const burst = (tint, cx) => {
            if (matchMedia('(prefers-reduced-motion: reduce)').matches) return;
            const cv = cvRef.current, b = cv.getBoundingClientRect();
            const x = cx - b.left, y = 4;
            for (let i = 0; i < 16; i++) {
              const a = -Math.PI / 2 + (Math.random() - 0.5) * 1.4;
              const sp = 180 + Math.random() * 260;
              parts.current.push({ x, y, vx: Math.cos(a) * sp, vy: Math.sin(a) * sp,
                r: 1.6 + Math.random() * 2.2, rot: Math.random() * 6.28, vr: (Math.random() - .5) * 10,
                c: i % 4 === 0 ? '#ffffff' : tint, life: 0.65 + Math.random() * 0.4, t: 0 });
            }
            raf.current ||= requestAnimationFrame(loop);
          };

          const pick = (id, e) => {
            const moving = sel && sel !== id;
            setCounts((c) => {
              const next = { ...c };
              if (moving) next[sel] = c[sel] - 1;
              next[id] = c[id] + (sel === id ? -1 : 1);
              return next;
            });
            if (sel !== id && e) burst(REACTIONS.find((r) => r.id === id).tint, e.clientX);
            setSel(sel === id ? null : id);
          };

          return (
            <>
              <style>{`
                .mfrb-card { position: relative; overflow: clip; max-inline-size: 30rem; padding: 1.2rem 1.4rem;
                             border: 1px solid #e7e5e4; border-radius: 1rem; background: #fff; font: 400 .9rem system-ui; }
                .mfrb-cv { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; pointer-events: none; }
                .mfrb-bar { display: flex; flex-wrap: wrap; gap: .45rem; margin-block-start: .9rem; }
                .mfrb-btn { display: inline-flex; align-items: center; gap: .45rem; padding: .5rem .85rem;
                            border-radius: 999px; border: 1px solid #e7e5e4; background: #fafaf9; cursor: pointer; }
                .mfrb-btn[aria-pressed='true'] { border-color: var(--mfrb-tint); color: var(--mfrb-tint); }
                .mfrb-ico svg { inline-size: 1rem; block-size: 1rem; stroke: currentColor; stroke-width: 2;
                                fill: none; stroke-linecap: round; stroke-linejoin: round; }
                .mfrb-odo { display: inline-flex; font-variant-numeric: tabular-nums; font-size: .78rem; font-weight: 600; }
                .mfrb-col { block-size: 1.2em; overflow: clip; }
                .mfrb-strip { display: grid; transition: transform .48s cubic-bezier(.2, .7, .2, 1); }
                .mfrb-cell { block-size: 1.2em; }
              `}</style>
              <div className="mfrb-card">
                <canvas ref={cvRef} className="mfrb-cv" aria-hidden="true" />
                <p>Lumen Canvas 3.2 is live: dark mode, 40% faster search and shared cursors on the board.</p>
                <div className="mfrb-bar" role="group" aria-label="Reactions">
                  {REACTIONS.map((r) => (
                    <button key={r.id} type="button" className="mfrb-btn" style={{ '--mfrb-tint': r.tint }}
                      aria-pressed={sel === r.id} aria-label={`${r.label}, ${counts[r.id]}`}
                      onPointerDown={(e) => pick(r.id, e)}
                      onKeyDown={(e) => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); pick(r.id); } }}>
                      <svg className="mfrb-ico" viewBox="0 0 24 24" aria-hidden="true"><path d={r.icon} /></svg>
                      <Odo value={counts[r.id]} />
                    </button>
                  ))}
                </div>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- ReactionBar.vue — particles on pick + odometer counters -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const REACTIONS = [
          { id: 'heart', label: 'Love', tint: '#e11d48' },
          { id: 'star', label: 'Useful', tint: '#f59e0b' },
          { id: 'sparkles', label: 'Inspiring', tint: '#8b5cf6' },
          { id: 'flame', label: 'On fire', tint: '#ea580c' },
        ];
        const cv = ref<HTMLCanvasElement | null>(null);
        const sel = ref<string | null>(null);
        const counts = ref<Record<string, number>>({ heart: 128, star: 96, sparkles: 41, flame: 203 });
        let parts: Array<{x:number,y:number,vx:number,vy:number,r:number,rot:number,vr:number,c:string,life:number,t:number}> = [];
        let raf = 0, last = 0, ro: ResizeObserver | null = null;

        const loop = (now: number) => {
          const c = cv.value!, ctx = c.getContext('2d')!;
          const dt = Math.min(0.032, (now - last) / 1000) || 0.016;
          last = now;
          ctx.clearRect(0, 0, c.clientWidth, c.clientHeight);
          parts = parts.filter((p) => {
            p.t += dt; p.vy += 900 * dt; p.x += p.vx * dt; p.y += p.vy * dt; p.rot += p.vr * dt;
            if (p.t > p.life) return false;
            ctx.save();
            ctx.globalAlpha = 1 - p.t / p.life;
            ctx.translate(p.x, p.y); ctx.rotate(p.rot); ctx.fillStyle = p.c;
            ctx.beginPath(); ctx.arc(0, 0, p.r, 0, 6.283); ctx.fill();
            ctx.restore();
            return true;
          });
          raf = parts.length ? requestAnimationFrame(loop) : 0;
        };

        const pick = (id: string, e?: PointerEvent | KeyboardEvent) => {
          if (sel.value && sel.value !== id) counts.value[sel.value] -= 1;
          counts.value[id] += sel.value === id ? -1 : 1;
          if (sel.value !== id && e && !matchMedia('(prefers-reduced-motion: reduce)').matches) {
            const c = cv.value!, b = c.getBoundingClientRect();
            const x = e.clientX - b.left;
            const tint = REACTIONS.find((r) => r.id === id)!.tint;
            for (let i = 0; i < 16; i++) {
              const a = -Math.PI / 2 + (Math.random() - 0.5) * 1.4;
              const sp = 180 + Math.random() * 260;
              parts.push({ x, y: 4, vx: Math.cos(a) * sp, vy: Math.sin(a) * sp,
                r: 1.6 + Math.random() * 2.2, rot: Math.random() * 6.28, vr: (Math.random() - .5) * 10,
                c: i % 4 === 0 ? '#ffffff' : tint, life: 0.65 + Math.random() * 0.4, t: 0 });
            }
            if (!raf) { last = 0; raf = requestAnimationFrame(loop); }
          }
          sel.value = sel.value === id ? null : id;
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
          <div class="mfrb-card">
            <canvas ref="cv" class="mfrb-cv" aria-hidden="true"></canvas>
            <p>Lumen Canvas 3.2 is live: dark mode, 40% faster search and shared cursors on the board.</p>
            <div class="mfrb-bar" role="group" aria-label="Reactions">
              <button v-for="r in REACTIONS" :key="r.id" type="button" class="mfrb-btn"
                :style="{ '--mfrb-tint': r.tint }" :aria-pressed="sel === r.id"
                :aria-label="`${r.label}, ${counts[r.id]}`"
                @pointerdown="pick(r.id, $event)" @keydown.enter.prevent="pick(r.id)" @keydown.space.prevent="pick(r.id)">
                <svg class="mfrb-ico" viewBox="0 0 24 24" aria-hidden="true">
                  <path v-if="r.id === 'heart'" d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z" />
                  <path v-else-if="r.id === 'star'" d="m12 3 2.7 5.6 6.1.9-4.4 4.3 1 6.1-5.4-2.9-5.4 2.9 1-6.1L3.2 9.5l6.1-.9Z" />
                  <path v-else-if="r.id === 'sparkles'" d="M12 3l1.9 5.1L19 10l-5.1 1.9L12 17l-1.9-5.1L5 10l5.1-1.9Z" />
                  <path v-else d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.07-2.14-.22-4.05 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.15.43-2.29 1-3a2.5 2.5 0 0 0 2.5 2.5Z" />
                </svg>
                <span class="mfrb-odo" dir="ltr">
                  <span v-for="(ch, i) in String(counts[r.id])" :key="i" class="mfrb-col">
                    <span class="mfrb-strip" :style="{ transform: `translateY(-${+ch}0%)` }">
                      <span v-for="d in 10" :key="d" class="mfrb-cell">{{ d - 1 }}</span>
                    </span>
                  </span>
                </span>
              </button>
            </div>
          </div>
        </template>

        <style scoped>
        .mfrb-card { position: relative; overflow: clip; max-inline-size: 30rem; padding: 1.2rem 1.4rem;
                     border: 1px solid #e7e5e4; border-radius: 1rem; background: #fff; }
        .mfrb-cv { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; pointer-events: none; }
        .mfrb-bar { display: flex; flex-wrap: wrap; gap: .45rem; margin-block-start: .9rem; }
        .mfrb-btn { display: inline-flex; align-items: center; gap: .45rem; padding: .5rem .85rem;
                    border-radius: 999px; border: 1px solid #e7e5e4; background: #fafaf9; cursor: pointer; }
        .mfrb-btn[aria-pressed='true'] { border-color: var(--mfrb-tint); color: var(--mfrb-tint); }
        .mfrb-ico { inline-size: 1rem; block-size: 1rem; stroke: currentColor; stroke-width: 2;
                    fill: none; stroke-linecap: round; stroke-linejoin: round; }
        .mfrb-odo { display: inline-flex; font-variant-numeric: tabular-nums; font-size: .78rem; font-weight: 600; }
        .mfrb-col { block-size: 1.2em; overflow: clip; }
        .mfrb-strip { display: grid; transition: transform .48s cubic-bezier(.2, .7, .2, 1); }
        .mfrb-cell { block-size: 1.2em; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- ReactionBar.svelte — particles on pick + odometer counters -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          const REACTIONS = [
            { id: 'heart', label: 'Love', tint: '#e11d48' },
            { id: 'star', label: 'Useful', tint: '#f59e0b' },
            { id: 'sparkles', label: 'Inspiring', tint: '#8b5cf6' },
            { id: 'flame', label: 'On fire', tint: '#ea580c' },
          ];
          let cv: HTMLCanvasElement;
          let sel = $state<string | null>(null);
          let counts = $state<Record<string, number>>({ heart: 128, star: 96, sparkles: 41, flame: 203 });
          let parts: Array<{x:number,y:number,vx:number,vy:number,r:number,rot:number,vr:number,c:string,life:number,t:number}> = [];
          let raf = 0, last = 0;

          const loop = (now: number) => {
            const ctx = cv.getContext('2d')!;
            const dt = Math.min(0.032, (now - last) / 1000) || 0.016;
            last = now;
            ctx.clearRect(0, 0, cv.clientWidth, cv.clientHeight);
            parts = parts.filter((p) => {
              p.t += dt; p.vy += 900 * dt; p.x += p.vx * dt; p.y += p.vy * dt; p.rot += p.vr * dt;
              if (p.t > p.life) return false;
              ctx.save();
              ctx.globalAlpha = 1 - p.t / p.life;
              ctx.translate(p.x, p.y); ctx.rotate(p.rot); ctx.fillStyle = p.c;
              ctx.beginPath(); ctx.arc(0, 0, p.r, 0, 6.283); ctx.fill();
              ctx.restore();
              return true;
            });
            raf = parts.length ? requestAnimationFrame(loop) : 0;
          };

          const pick = (id: string, e?: PointerEvent | KeyboardEvent) => {
            if (sel && sel !== id) counts[sel] -= 1;
            counts[id] += sel === id ? -1 : 1;
            if (sel !== id && e && !matchMedia('(prefers-reduced-motion: reduce)').matches) {
              const b = cv.getBoundingClientRect();
              const x = e.clientX - b.left;
              const tint = REACTIONS.find((r) => r.id === id)!.tint;
              for (let i = 0; i < 16; i++) {
                const a = -Math.PI / 2 + (Math.random() - 0.5) * 1.4;
                const sp = 180 + Math.random() * 260;
                parts.push({ x, y: 4, vx: Math.cos(a) * sp, vy: Math.sin(a) * sp,
                  r: 1.6 + Math.random() * 2.2, rot: Math.random() * 6.28, vr: (Math.random() - .5) * 10,
                  c: i % 4 === 0 ? '#ffffff' : tint, life: 0.65 + Math.random() * 0.4, t: 0 });
              }
              if (!raf) { last = 0; raf = requestAnimationFrame(loop); }
            }
            sel = sel === id ? null : id;
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

        <div class="mfrb-card">
          <canvas bind:this={cv} class="mfrb-cv" aria-hidden="true"></canvas>
          <p>Lumen Canvas 3.2 is live: dark mode, 40% faster search and shared cursors on the board.</p>
          <div class="mfrb-bar" role="group" aria-label="Reactions">
            {#each REACTIONS as r (r.id)}
              <button type="button" class="mfrb-btn" style="--mfrb-tint: {r.tint}"
                aria-pressed={sel === r.id} aria-label="{r.label}, {counts[r.id]}"
                onpointerdown={(e) => pick(r.id, e)}
                onkeydown={(e) => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); pick(r.id); } }}>
                <svg class="mfrb-ico" viewBox="0 0 24 24" aria-hidden="true">
                  {#if r.id === 'heart'}<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z" />
                  {:else if r.id === 'star'}<path d="m12 3 2.7 5.6 6.1.9-4.4 4.3 1 6.1-5.4-2.9-5.4 2.9 1-6.1L3.2 9.5l6.1-.9Z" />
                  {:else if r.id === 'sparkles'}<path d="M12 3l1.9 5.1L19 10l-5.1 1.9L12 17l-1.9-5.1L5 10l5.1-1.9Z" />
                  {:else}<path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.07-2.14-.22-4.05 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.15.43-2.29 1-3a2.5 2.5 0 0 0 2.5 2.5Z" />{/if}
                </svg>
                <span class="mfrb-odo" dir="ltr">
                  {#each String(counts[r.id]).split('') as ch, i (i)}
                    <span class="mfrb-col">
                      <span class="mfrb-strip" style="transform: translateY(-{+ch}0%)">
                        {#each Array.from({ length: 10 }, (_, d) => d) as d (d)}
                          <span class="mfrb-cell">{d}</span>
                        {/each}
                      </span>
                    </span>
                  {/each}
                </span>
              </button>
            {/each}
          </div>
        </div>

        <style>
        .mfrb-card { position: relative; overflow: clip; max-inline-size: 30rem; padding: 1.2rem 1.4rem;
                     border: 1px solid #e7e5e4; border-radius: 1rem; background: #fff; }
        .mfrb-cv { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; pointer-events: none; }
        .mfrb-bar { display: flex; flex-wrap: wrap; gap: .45rem; margin-block-start: .9rem; }
        .mfrb-btn { display: inline-flex; align-items: center; gap: .45rem; padding: .5rem .85rem;
                    border-radius: 999px; border: 1px solid #e7e5e4; background: #fafaf9; cursor: pointer; }
        .mfrb-btn[aria-pressed='true'] { border-color: var(--mfrb-tint); color: var(--mfrb-tint); }
        .mfrb-ico { inline-size: 1rem; block-size: 1rem; stroke: currentColor; stroke-width: 2;
                    fill: none; stroke-linecap: round; stroke-linejoin: round; }
        .mfrb-odo { display: inline-flex; font-variant-numeric: tabular-nums; font-size: .78rem; font-weight: 600; }
        .mfrb-col { block-size: 1.2em; overflow: clip; }
        .mfrb-strip { display: grid; transition: transform .48s cubic-bezier(.2, .7, .2, 1); }
        .mfrb-cell { block-size: 1.2em; }
        </style>
        SVELTE,
        ],
    ],

    'live-ticker' => [
        'title' => ['fa' => 'تیکر زنده', 'en' => 'Live ticker'],
        'icon' => 'trend-up',
        'oneLiner' => [
            'fa' => 'نوار قیمت زندهٔ دیده‌بان مریدین: پنج دارایی خیالی (AUR، HEL، TID، VEL و CAS) هر ۱٫۶ ثانیه یک گام تصادفی برمی‌دارند؛ ارقام هر قیمت روی نوار عمودی خودشان می‌غلتند، فلش و درصدِ تغییر نسبت به شروع نشست سبز و قرمز می‌شوند و هاور روی نوار جریان را نگه می‌دارد — سهم‌ها با ایزولهٔ دوجهته در RTL و LTR درست می‌خوانند.',
            'en' => 'Meridian Watchlist’s live price strip: five fictional assets (AUR, HEL, TID, VEL, CAS) take a random step every 1.6 seconds; each price’s digits roll on their vertical strips, the arrow and the change-from-open percent flip green and red, and hovering the bar holds the flow — quotes are bidi-isolated so they read correctly in RTL and LTR alike.',
        ],
        'js' => true,
        'docs' => 'https://motion-primitives.com',
        'props' => [
            ['name' => 'interval', 'type' => 'ms', 'default' => "'1600'", 'note' => [
                'fa' => 'گام تیک؛ تایمر فقط وقتی تیک می‌زند که نوار در دید باشد، و هاور یا فوکوس آن را نگه می‌دارد.',
                'en' => 'The tick step; the timer only fires while the bar is on screen, and hover or focus holds it still.',
            ]],
            ['name' => 'walk', 'type' => 'random', 'default' => "'±0.3% per tick'", 'note' => [
                'fa' => 'گام تصادفی ضربدر قیمت؛ کوچک نگه داشته شده تا روند نسبت به شروع نشست معنا بدهد.',
                'en' => 'The random step multiplied into the price; kept small so the trend versus session open stays meaningful.',
            ]],
            ['name' => 'baseline', 'type' => 'reference', 'default' => "'session open'", 'note' => [
                'fa' => 'درصد تغییر از اولین قیمت نشست محاسبه می‌شود؛ رنگ و فلش هر دو از همین علامت می‌آیند.',
                'en' => 'The percent change is measured from the session’s first price; both the colour and the arrow come from that sign.',
            ]],
            ['name' => 'pause', 'type' => 'behaviour', 'default' => "'hover · focus'", 'note' => [
                'fa' => 'هاور یا فوکوسِ کیبورد روی نوار جریان را متوقف می‌کند تا خواندن آسان شود؛ وضعیت در سطر پایین اعلام می‌شود.',
                'en' => 'Hovering — or keyboard focus on — the bar freezes the flow for easy reading; the state is announced in the line below.',
            ]],
            ['name' => 'prefers-reduced-motion', 'type' => 'behaviour', 'default' => "'snap'", 'note' => [
                'fa' => 'بدون غلتش؛ ارقام فوری در مقدار تازه می‌ایستند ولی رنگ و فلش و به‌روزرسانی می‌مانند.',
                'en' => 'No rolling; digits land on the fresh value instantly while colour, arrow and updates carry on.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        {{-- liveTicker(): interval walk + per-digit vertical rolls; hover or
             focus pauses; quotes are bidi-isolated so RTL reads correctly. --}}
        <div class="mflt" x-data="liveTicker()">
          <div class="mflt-bar" x-ref="bar" role="group" aria-label="قیمت‌های زنده"
               @pointerenter="paused = true" @pointerleave="paused = false"
               @focusin="paused = true" @focusout="paused = false">
            <template x-for="a in list" :key="a.sym">
              <div class="mflt-cell" x-bind:data-dir="pct(a) >= 0 ? 'up' : 'down'">
                <span class="mflt-sym"><b x-text="a.sym"></b><span x-text="a.fa"></span></span>
                <span class="mflt-quote" dir="ltr">
                  <span class="mflt-price">
                    <template x-for="(ch, i) in Array.from(fmt(a.p))" :key="i">
                      <span class="mflt-col">
                        <template x-if="/[0-9]/.test(ch)">
                          <span class="mflt-strip" x-bind:style="'transform: translateY(-' + (ch * 10) + '%)'">
                            <template x-for="d in 10" :key="d"><span class="mflt-cell-d" x-text="faDigit(d - 1)"></span></template>
                          </span>
                        </template>
                        <template x-if="/[^0-9]/.test(ch)"><span class="mflt-lit" x-text="ch"></span></template>
                      </span>
                    </template>
                  </span>
                  <span class="mflt-chg">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                      <path x-bind:d="pct(a) >= 0 ? 'M22 7l-8.5 8.5-5-5L2 17' : 'M22 17l-8.5-8.5-5 5L2 7'"></path>
                      <path x-bind:d="pct(a) >= 0 ? 'M16 7h6v6' : 'M16 17h6v-6'"></path>
                    </svg>
                    <span x-text="(pct(a) >= 0 ? '+' : '') + pct(a).toFixed(2) + '%'"></span>
                  </span>
                </span>
              </div>
            </template>
          </div>
          <p class="mflt-note" role="status" x-text="paused ? 'نوار نگه داشته شد' : 'زنده — ' + faNum(ticks) + ' تیک'"></p>
        </div>

        <style>
        .mflt-bar { display: flex; flex-wrap: wrap; gap: .6rem; padding: .8rem; border-radius: 1.1rem;
                    border: 1px solid #e7e5e4; background: #fafaf9; }
        .mflt-cell { display: grid; gap: .15rem; padding: .6rem .9rem; border-radius: .9rem;
                     border: 1px solid #e7e5e4; background: #fff; min-inline-size: 8.5rem; }
        .mflt-cell[data-dir='up'] .mflt-chg { color: #059669; }
        .mflt-cell[data-dir='down'] .mflt-chg { color: #e11d48; }
        .mflt-quote { display: inline-flex; align-items: baseline; gap: .5rem; direction: ltr;
                      unicode-bidi: isolate; font-variant-numeric: tabular-nums; }
        .mflt-price { display: inline-flex; font-weight: 700; }
        .mflt-col { block-size: 1.25em; overflow: clip; }
        .mflt-strip { display: grid; transition: transform .5s cubic-bezier(.2, .7, .2, 1); }
        .mflt-cell-d { block-size: 1.25em; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        {{-- liveTicker(): interval walk + per-digit vertical rolls; hover or
             focus pauses; quotes are bidi-isolated so RTL reads correctly. --}}
        <div class="mflt" x-data="liveTicker()">
          <div class="mflt-bar" x-ref="bar" role="group" aria-label="Live prices"
               @pointerenter="paused = true" @pointerleave="paused = false"
               @focusin="paused = true" @focusout="paused = false">
            <template x-for="a in list" :key="a.sym">
              <div class="mflt-cell" x-bind:data-dir="pct(a) >= 0 ? 'up' : 'down'">
                <span class="mflt-sym"><b x-text="a.sym"></b><span x-text="a.en"></span></span>
                <span class="mflt-quote" dir="ltr">
                  <span class="mflt-price">
                    <template x-for="(ch, i) in Array.from(fmt(a.p))" :key="i">
                      <span class="mflt-col">
                        <template x-if="/[0-9]/.test(ch)">
                          <span class="mflt-strip" x-bind:style="'transform: translateY(-' + (ch * 10) + '%)'">
                            <template x-for="d in 10" :key="d"><span class="mflt-cell-d" x-text="d - 1"></span></template>
                          </span>
                        </template>
                        <template x-if="/[^0-9]/.test(ch)"><span class="mflt-lit" x-text="ch"></span></template>
                      </span>
                    </template>
                  </span>
                  <span class="mflt-chg">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                      <path x-bind:d="pct(a) >= 0 ? 'M22 7l-8.5 8.5-5-5L2 17' : 'M22 17l-8.5-8.5-5 5L2 7'"></path>
                      <path x-bind:d="pct(a) >= 0 ? 'M16 7h6v6' : 'M16 17h6v-6'"></path>
                    </svg>
                    <span x-text="(pct(a) >= 0 ? '+' : '') + pct(a).toFixed(2) + '%'"></span>
                  </span>
                </span>
              </div>
            </template>
          </div>
          <p class="mflt-note" role="status" x-text="paused ? 'Bar held' : 'Live — ' + ticks + ' ticks'"></p>
        </div>

        <style>
        .mflt-bar { display: flex; flex-wrap: wrap; gap: .6rem; padding: .8rem; border-radius: 1.1rem;
                    border: 1px solid #e7e5e4; background: #fafaf9; }
        .mflt-cell { display: grid; gap: .15rem; padding: .6rem .9rem; border-radius: .9rem;
                     border: 1px solid #e7e5e4; background: #fff; min-inline-size: 8.5rem; }
        .mflt-cell[data-dir='up'] .mflt-chg { color: #059669; }
        .mflt-cell[data-dir='down'] .mflt-chg { color: #e11d48; }
        .mflt-quote { display: inline-flex; align-items: baseline; gap: .5rem; direction: ltr;
                      unicode-bidi: isolate; font-variant-numeric: tabular-nums; }
        .mflt-price { display: inline-flex; font-weight: 700; }
        .mflt-col { block-size: 1.25em; overflow: clip; }
        .mflt-strip { display: grid; transition: transform .5s cubic-bezier(.2, .7, .2, 1); }
        .mflt-cell-d { block-size: 1.25em; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Watchlist.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import LiveTicker from '@/components/LiveTicker';

        export default function Watchlist() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <LiveTicker />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // LiveTicker.jsx — random-walk prices with rolling digits
        import { useEffect, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const ASSETS = [
          { sym: 'AUR', en: 'Aurum', p: 1284.52 },
          { sym: 'HEL', en: 'Helios', p: 87.19 },
          { sym: 'TID', en: 'Tidal', p: 12.84 },
          { sym: 'VEL', en: 'Vela', p: 341.07 },
          { sym: 'CAS', en: 'Cascade', p: 56.40 },
        ].map((a) => ({ ...a, open: a.p }));

        const fmt = (p) => p.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        const Roll = ({ text }) => (
          <span className="mflt-price" aria-hidden="true">
            {Array.from(fmt(text)).map((ch, i) => /[0-9]/.test(ch) ? (
              <span key={i} className="mflt-col">
                <span className="mflt-strip" style={{ transform: `translateY(-${+ch}0%)` }}>
                  {Array.from({ length: 10 }, (_, d) => <span key={d} className="mflt-cell-d">{d}</span>)}
                </span>
              </span>
            ) : (
              <span key={i} className="mflt-lit">{ch}</span>
            ))}
          </span>
        );

        export default function LiveTicker() {
          const [assets, setAssets] = useState(ASSETS);
          const [ticks, setTicks] = useState(0);
          const [paused, setPaused] = useState(false);

          useEffect(() => {
            const id = setInterval(() => {
              setAssets((rows) => rows.map((a) => ({ ...a, p: Math.max(0.01, a.p * (1 + (Math.random() - 0.5) * 0.006)) })));
              setTicks((t) => t + 1);
            }, 1600);
            return () => clearInterval(id);
          }, []);

          const pct = (a) => (a.p / a.open - 1) * 100;

          return (
            <>
              <style>{`
                .mflt-bar { display: flex; flex-wrap: wrap; gap: .6rem; padding: .8rem; border-radius: 1.1rem;
                            border: 1px solid #e7e5e4; background: #fafaf9; font: 400 .9rem system-ui; }
                .mflt-cell { display: grid; gap: .15rem; padding: .6rem .9rem; border-radius: .9rem;
                             border: 1px solid #e7e5e4; background: #fff; min-inline-size: 8.5rem; }
                .mflt-cell[data-dir='up'] .mflt-chg { color: #059669; }
                .mflt-cell[data-dir='down'] .mflt-chg { color: #e11d48; }
                .mflt-quote { display: inline-flex; align-items: baseline; gap: .5rem; direction: ltr;
                              unicode-bidi: isolate; font-variant-numeric: tabular-nums; }
                .mflt-price { display: inline-flex; font-weight: 700; }
                .mflt-col { block-size: 1.25em; overflow: clip; }
                .mflt-strip { display: grid; transition: transform .5s cubic-bezier(.2, .7, .2, 1); }
                .mflt-cell-d { block-size: 1.25em; }
                .mflt-sym { display: flex; align-items: baseline; gap: .4rem; font-size: .72rem; color: #78716c; }
                .mflt-sym b { font-size: .8rem; color: #1c1917; }
                .mflt-chg { display: inline-flex; align-items: center; gap: .2rem; font-size: .76rem; font-weight: 600; }
                .mflt-chg svg { inline-size: .9rem; block-size: .9rem; stroke: currentColor; stroke-width: 2;
                                fill: none; stroke-linecap: round; stroke-linejoin: round; }
                .mflt-lit { line-height: 1.25; }
              `}</style>
              <div className="mflt-bar" role="group" aria-label="Live prices"
                   onPointerEnter={() => setPaused(true)} onPointerLeave={() => setPaused(false)}
                   onFocus={() => setPaused(true)} onBlur={() => setPaused(false)}>
                {assets.map((a) => {
                  const up = pct(a) >= 0;
                  return (
                    <div key={a.sym} className="mflt-cell" data-dir={up ? 'up' : 'down'}>
                      <span className="mflt-sym"><b>{a.sym}</b><span>{a.en}</span></span>
                      <span className="mflt-quote" dir="ltr">
                        <Roll text={a.p} />
                        <span className="mflt-chg">
                          <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d={up ? 'M22 7l-8.5 8.5-5-5L2 17' : 'M22 17l-8.5-8.5-5 5L2 7'} />
                            <path d={up ? 'M16 7h6v6' : 'M16 17h6v-6'} />
                          </svg>
                          <span>{(up ? '+' : '') + pct(a).toFixed(2) + '%'}</span>
                        </span>
                      </span>
                    </div>
                  );
                })}
              </div>
              <p role="status">{paused ? 'Bar held' : `Live — ${ticks} ticks`}</p>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- LiveTicker.vue — random-walk prices with rolling digits -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const rows = ref([
          { sym: 'AUR', en: 'Aurum', p: 1284.52, open: 1284.52 },
          { sym: 'HEL', en: 'Helios', p: 87.19, open: 87.19 },
          { sym: 'TID', en: 'Tidal', p: 12.84, open: 12.84 },
          { sym: 'VEL', en: 'Vela', p: 341.07, open: 341.07 },
          { sym: 'CAS', en: 'Cascade', p: 56.40, open: 56.40 },
        ]);
        const paused = ref(false);
        const ticks = ref(0);
        let timer: number | undefined;

        const fmt = (p: number) => p.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        const chars = (p: number) => Array.from(fmt(p));
        const pct = (a: { p: number; open: number }) => (a.p / a.open - 1) * 100;

        onMounted(() => {
          timer = window.setInterval(() => {
            if (paused.value || document.hidden) return;
            for (const a of rows.value) a.p = Math.max(0.01, a.p * (1 + (Math.random() - 0.5) * 0.006));
            ticks.value += 1;
          }, 1600);
        });
        onBeforeUnmount(() => clearInterval(timer));
        </script>

        <template>
          <div class="mflt-bar" role="group" aria-label="Live prices" tabindex="0"
               @pointerenter="paused = true" @pointerleave="paused = false"
               @focusin="paused = true" @focusout="paused = false">
            <div v-for="a in rows" :key="a.sym" class="mflt-cell" :data-dir="pct(a) >= 0 ? 'up' : 'down'">
              <span class="mflt-sym"><b>{{ a.sym }}</b><span>{{ a.en }}</span></span>
              <span class="mflt-quote" dir="ltr">
                <span class="mflt-price" aria-hidden="true">
                  <span v-for="(ch, i) in chars(a.p)" :key="i" class="mflt-col">
                    <span v-if="/[0-9]/.test(ch)" class="mflt-strip" :style="{ transform: `translateY(-${+ch}0%)` }">
                      <span v-for="d in 10" :key="d" class="mflt-cell-d">{{ d - 1 }}</span>
                    </span>
                    <span v-else class="mflt-lit">{{ ch }}</span>
                  </span>
                </span>
                <span class="mflt-chg">
                  <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path :d="pct(a) >= 0 ? 'M22 7l-8.5 8.5-5-5L2 17' : 'M22 17l-8.5-8.5-5 5L2 7'" />
                    <path :d="pct(a) >= 0 ? 'M16 7h6v6' : 'M16 17h6v-6'" />
                  </svg>
                  <span>{{ (pct(a) >= 0 ? '+' : '') + pct(a).toFixed(2) + '%' }}</span>
                </span>
              </span>
            </div>
          </div>
          <p role="status">{{ paused ? 'Bar held' : `Live — ${ticks} ticks` }}</p>
        </template>

        <style scoped>
        .mflt-bar { display: flex; flex-wrap: wrap; gap: .6rem; padding: .8rem; border-radius: 1.1rem;
                    border: 1px solid #e7e5e4; background: #fafaf9; }
        .mflt-cell { display: grid; gap: .15rem; padding: .6rem .9rem; border-radius: .9rem;
                     border: 1px solid #e7e5e4; background: #fff; min-inline-size: 8.5rem; }
        .mflt-cell[data-dir='up'] .mflt-chg { color: #059669; }
        .mflt-cell[data-dir='down'] .mflt-chg { color: #e11d48; }
        .mflt-quote { display: inline-flex; align-items: baseline; gap: .5rem; direction: ltr;
                      unicode-bidi: isolate; font-variant-numeric: tabular-nums; }
        .mflt-price { display: inline-flex; font-weight: 700; }
        .mflt-col { block-size: 1.25em; overflow: clip; }
        .mflt-strip { display: grid; transition: transform .5s cubic-bezier(.2, .7, .2, 1); }
        .mflt-cell-d { block-size: 1.25em; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- LiveTicker.svelte — random-walk prices with rolling digits -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          let rows = $state([
            { sym: 'AUR', en: 'Aurum', p: 1284.52, open: 1284.52 },
            { sym: 'HEL', en: 'Helios', p: 87.19, open: 87.19 },
            { sym: 'TID', en: 'Tidal', p: 12.84, open: 12.84 },
            { sym: 'VEL', en: 'Vela', p: 341.07, open: 341.07 },
            { sym: 'CAS', en: 'Cascade', p: 56.40, open: 56.40 },
          ]);
          let paused = $state(false);
          let ticks = $state(0);
          let timer: number | undefined;

          const fmt = (p: number) => p.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
          const chars = (p: number) => Array.from(fmt(p));
          const pct = (a: { p: number; open: number }) => (a.p / a.open - 1) * 100;

          onMount(() => {
            timer = window.setInterval(() => {
              if (paused || document.hidden) return;
              for (const a of rows) a.p = Math.max(0.01, a.p * (1 + (Math.random() - 0.5) * 0.006));
              ticks += 1;
            }, 1600);
            return () => clearInterval(timer);
          });
        </script>

        <div class="mflt-bar" role="group" aria-label="Live prices" tabindex="0"
             onpointerenter={() => (paused = true)} onpointerleave={() => (paused = false)}
             onfocusin={() => (paused = true)} onfocusout={() => (paused = false)}>
          {#each rows as a (a.sym)}
            <div class="mflt-cell" data-dir={pct(a) >= 0 ? 'up' : 'down'}>
              <span class="mflt-sym"><b>{a.sym}</b><span>{a.en}</span></span>
              <span class="mflt-quote" dir="ltr">
                <span class="mflt-price" aria-hidden="true">
                  {#each chars(a.p) as ch, i (i)}
                    <span class="mflt-col">
                      {#if /[0-9]/.test(ch)}
                        <span class="mflt-strip" style="transform: translateY(-{+ch}0%)">
                          {#each Array.from({ length: 10 }, (_, d) => d) as d (d)}
                            <span class="mflt-cell-d">{d}</span>
                          {/each}
                        </span>
                      {:else}
                        <span class="mflt-lit">{ch}</span>
                      {/if}
                    </span>
                  {/each}
                </span>
                <span class="mflt-chg">
                  <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d={pct(a) >= 0 ? 'M22 7l-8.5 8.5-5-5L2 17' : 'M22 17l-8.5-8.5-5 5L2 7'} />
                    <path d={pct(a) >= 0 ? 'M16 7h6v6' : 'M16 17h6v-6'} />
                  </svg>
                  <span>{(pct(a) >= 0 ? '+' : '') + pct(a).toFixed(2) + '%'}</span>
                </span>
              </span>
            </div>
          {/each}
        </div>
        <p role="status">{paused ? 'Bar held' : `Live — ${ticks} ticks`}</p>

        <style>
        .mflt-bar { display: flex; flex-wrap: wrap; gap: .6rem; padding: .8rem; border-radius: 1.1rem;
                    border: 1px solid #e7e5e4; background: #fafaf9; }
        .mflt-cell { display: grid; gap: .15rem; padding: .6rem .9rem; border-radius: .9rem;
                     border: 1px solid #e7e5e4; background: #fff; min-inline-size: 8.5rem; }
        .mflt-cell[data-dir='up'] .mflt-chg { color: #059669; }
        .mflt-cell[data-dir='down'] .mflt-chg { color: #e11d48; }
        .mflt-quote { display: inline-flex; align-items: baseline; gap: .5rem; direction: ltr;
                      unicode-bidi: isolate; font-variant-numeric: tabular-nums; }
        .mflt-price { display: inline-flex; font-weight: 700; }
        .mflt-col { block-size: 1.25em; overflow: clip; }
        .mflt-strip { display: grid; transition: transform .5s cubic-bezier(.2, .7, .2, 1); }
        .mflt-cell-d { block-size: 1.25em; }
        </style>
        SVELTE,
        ],
    ],
];
