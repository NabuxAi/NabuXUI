<?php

/**
 * Demo manifest of the "loaders" group — waiting states that tell the truth:
 * orbit dots chasing inside a save button that really saves, a ring that
 * morphs while its percent climbs, the iOS-style segmented spin in three
 * status cards, a percent loader with live stage labels and a cancel that
 * cancels, and a tank that fills with liquid up to its ready label.
 * Scenarios live at resources/views/demos/components/loaders/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'بارگذارها', 'en' => 'Loaders'],

    'orbit-dots' => [
        'title' => ['fa' => 'نقطه‌های مداری', 'en' => 'Orbit dots'],
        'icon' => 'globe',
        'oneLiner' => [
            'fa' => 'چهار نقطه روی مدار بیضوی می‌دوند و با تعلیق نرم، دنبالهٔ دنب‌دار می‌سازند؛ داخل دکمهٔ «ذخیره» که واقعاً ۲ ثانیه کار می‌کند و بعد تیک می‌زند — و در تراشهٔ همگام‌سازی که اگر سرور نپاسخد، سرخ می‌ایستد و «تلاش دوباره» راه می‌افتد.',
            'en' => 'Four dots racing round an elliptical orbit, their soft stagger reading as a comet tail; inside a save button that really works for two seconds and then ticks — and in a sync chip that halts dead in red when the server won’t answer, until the retry runs.',
        ],
        'js' => true,
        'docs' => 'https://circleloaders.dominikakissi.com',
        'props' => [
            ['name' => 'dots', 'type' => 'count', 'default' => "'4'", 'note' => [
                'fa' => 'سه تا پنج نقطه؛ نقطه‌های عقب‌تر کوچک‌تر و کم‌رنگ‌ترند تا چشم دنباله را بخواند.',
                'en' => 'Three to five dots; the stragglers shrink and fade so the eye reads a tail.',
            ]],
            ['name' => 'duration', 'type' => 's', 'default' => "'1.15'", 'note' => [
                'fa' => 'زمان هر دور؛ منحنی cubic-bezier نقاط را در پیچ‌های بیضی جمع و پخش می‌کند — همان حس تعقیب کشسان.',
                'en' => 'Seconds per lap; the cubic-bezier bunches and releases the dots through the ellipse’s bends — the elastic chase itself.',
            ]],
            ['name' => 'orbit', 'type' => 'px', 'default' => "'22 × 14'", 'note' => [
                'fa' => 'قاب بیضی مدار با offset-path رسم می‌شود؛ نسبت ۲۲×۱۴ دور را پهن و آرام نگه می‌دارد.',
                'en' => 'The ellipse drawn with offset-path; the 22×14 ratio keeps the lap wide and calm.',
            ]],
            ['name' => 'work', 'type' => 'ms', 'default' => "'2000'", 'note' => [
                'fa' => 'مدت کار واقعی دکمه پیش از تیک — همان‌قدری که کاربر واقعاً صبر می‌کند، نه بیشتر.',
                'en' => 'How long the button really works before the tick — exactly the wait your user sits through, not a frame more.',
            ]],
            ['name' => 'error', 'type' => 'state', 'default' => "'504'", 'note' => [
                'fa' => 'در خطا نقاط می‌ایستند و رنگ سرخ می‌شود؛ «تلاش دوباره» همان مسیر را از نو می‌رود.',
                'en' => 'On error the dots halt and turn red; Retry walks the very same path again.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <button type="button" class="ldo-save" data-state="working">
            <span class="ldo-orbit" aria-hidden="true"><i></i><i></i><i></i><i></i></span>
            <span>در حال ذخیره…</span>
        </button>

        <style>
        .ldo-save { display: inline-flex; align-items: center; justify-content: center; gap: .6rem;
                    min-inline-size: 11rem; block-size: 2.8rem; padding-inline: 1.3rem; border: none;
                    border-radius: 999px; background: #4f46e5; color: #fff;
                    font: 600 .88rem/1 'Inter', 'Vazirmatn', sans-serif; cursor: pointer; }
        .ldo-save[data-state='done'] { background: #059669; }
        .ldo-orbit { position: relative; inline-size: 22px; block-size: 14px; flex: none; }
        .ldo-orbit i { position: absolute; inset: 0;
                       offset-path: path('M2 7 A9 5.5 0 1 1 20 7 A9 5.5 0 1 1 2 7');
                       animation: ldo-lap 1.15s cubic-bezier(.62,.05,.37,.95) infinite; }
        .ldo-orbit i::after { content: ''; position: absolute; inset-block-start: 50%; inset-inline-start: 50%;
                              inline-size: 5px; aspect-ratio: 1; border-radius: 50%; background: currentColor;
                              translate: -50% -50%; }
        .ldo-orbit i:nth-child(2) { animation-delay: -.16s; } .ldo-orbit i:nth-child(2)::after { opacity: .7; scale: .8; }
        .ldo-orbit i:nth-child(3) { animation-delay: -.32s; } .ldo-orbit i:nth-child(3)::after { opacity: .5; scale: .6; }
        .ldo-orbit i:nth-child(4) { animation-delay: -.48s; } .ldo-orbit i:nth-child(4)::after { opacity: .35; scale: .45; }
        @keyframes ldo-lap { from { offset-distance: 0%; } to { offset-distance: 100%; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <button type="button" class="ldo-save" data-state="working">
        <span class="ldo-orbit" aria-hidden="true"><i></i><i></i><i></i><i></i></span>
        <span>Saving…</span>
        </button>

        <style>
        .ldo-save { display: inline-flex; align-items: center; justify-content: center; gap: .6rem;
        min-inline-size: 11rem; block-size: 2.8rem; padding-inline: 1.3rem; border: none;
        border-radius: 999px; background: #4f46e5; color: #fff;
        font: 600 .88rem/1 'Inter', 'Vazirmatn', sans-serif; cursor: pointer; }
        .ldo-save[data-state='done'] { background: #059669; }
        .ldo-orbit { position: relative; inline-size: 22px; block-size: 14px; flex: none; }
        .ldo-orbit i { position: absolute; inset: 0;
        offset-path: path('M2 7 A9 5.5 0 1 1 20 7 A9 5.5 0 1 1 2 7');
        animation: ldo-lap 1.15s cubic-bezier(.62,.05,.37,.95) infinite; }
        .ldo-orbit i::after { content: ''; position: absolute; inset-block-start: 50%; inset-inline-start: 50%;
        inline-size: 5px; aspect-ratio: 1; border-radius: 50%; background: currentColor;
        translate: -50% -50%; }
        .ldo-orbit i:nth-child(2) { animation-delay: -.16s; } .ldo-orbit i:nth-child(2)::after { opacity: .7; scale: .8; }
        .ldo-orbit i:nth-child(3) { animation-delay: -.32s; } .ldo-orbit i:nth-child(3)::after { opacity: .5; scale: .6; }
        .ldo-orbit i:nth-child(4) { animation-delay: -.48s; } .ldo-orbit i:nth-child(4)::after { opacity: .35; scale: .45; }
        @keyframes ldo-lap { from { offset-distance: 0%; } to { offset-distance: 100%; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/WorkspaceSettings.jsx — Inertia (React) page; renders the
        // React snippet below unchanged, with Inertia's Link in the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import SaveButton from '@/components/SaveButton';

        export default function WorkspaceSettings() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <SaveButton workMs={2000} />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // SaveButton.jsx — orbit dots chasing inside a button that really saves
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        export default function SaveButton({ workMs = 2000 }) {
          const [state, setState] = useState('idle'); // idle | working | done
          const timers = useRef<number[]>([]);

          // Every timeout is tracked so unmounting never ticks a dead button.
          useEffect(() => () => timers.current.forEach(clearTimeout), []);

          const later = (fn: () => void, ms: number) => {
            timers.current.push(window.setTimeout(fn, ms));
          };

          const save = () => {
            if (state === 'working') return;
            setState('working');
            later(() => {
              setState('done');
              later(() => setState('idle'), 1800);
            }, workMs);
          };

          const label = { idle: 'Save changes', working: 'Saving…', done: 'Saved' }[state];

          return (
            <>
              <style>{`
                .ldo-save { display: inline-flex; align-items: center; justify-content: center; gap: .6rem;
                            min-inline-size: 11rem; block-size: 2.8rem; padding-inline: 1.3rem; border: none;
                            border-radius: 999px; background: #4f46e5; color: #fff; cursor: pointer;
                            font: 600 .88rem/1 'Inter', sans-serif; transition: background-color .25s ease; }
                .ldo-save:hover:not(:disabled) { background: #4338ca; }
                .ldo-save[data-state='done'] { background: #059669; }
                .ldo-save:disabled { cursor: default; }
                .ldo-orbit { position: relative; inline-size: 22px; block-size: 14px; flex: none; }
                .ldo-orbit i { position: absolute; inset: 0;
                               offset-path: path('M2 7 A9 5.5 0 1 1 20 7 A9 5.5 0 1 1 2 7');
                               animation: ldo-lap 1.15s cubic-bezier(.62,.05,.37,.95) infinite; }
                .ldo-orbit i::after { content: ''; position: absolute; inset-block-start: 50%; inset-inline-start: 50%;
                                      inline-size: 5px; aspect-ratio: 1; border-radius: 50%; background: currentColor;
                                      translate: -50% -50%; }
                .ldo-orbit i:nth-child(2) { animation-delay: -.16s; } .ldo-orbit i:nth-child(2)::after { opacity: .7; scale: .8; }
                .ldo-orbit i:nth-child(3) { animation-delay: -.32s; } .ldo-orbit i:nth-child(3)::after { opacity: .5; scale: .6; }
                .ldo-orbit i:nth-child(4) { animation-delay: -.48s; } .ldo-orbit i:nth-child(4)::after { opacity: .35; scale: .45; }
                .ldo-check { inline-size: 1.15rem; flex: none; }
                .ldo-check path { fill: none; stroke: currentColor; stroke-width: 2.4; stroke-linecap: round;
                                  stroke-linejoin: round; stroke-dasharray: 24; stroke-dashoffset: 24;
                                  animation: ldo-draw .45s ease-out forwards; }
                @keyframes ldo-lap { from { offset-distance: 0%; } to { offset-distance: 100%; } }
                @keyframes ldo-draw { to { stroke-dashoffset: 0; } }
                @media (prefers-reduced-motion: reduce) {
                  .ldo-orbit i { animation: none; }
                  .ldo-orbit i:nth-child(1) { offset-distance: 0%; }  .ldo-orbit i:nth-child(2) { offset-distance: 25%; }
                  .ldo-orbit i:nth-child(3) { offset-distance: 50%; } .ldo-orbit i:nth-child(4) { offset-distance: 75%; }
                  .ldo-check path { animation: none; stroke-dashoffset: 0; }
                }
              `}</style>
              <button type="button" className="ldo-save" data-state={state}
                      onClick={save} disabled={state === 'working'}>
                {state === 'working' && (
                  <span className="ldo-orbit" aria-hidden="true"><i /><i /><i /><i /></span>
                )}
                {state === 'done' && (
                  <svg className="ldo-check" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M4.5 12.5l5 5 10-11" />
                  </svg>
                )}
                <span aria-live="polite">{label}</span>
              </button>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- SaveButton.vue — orbit dots chasing inside a button that really saves -->
        <script setup lang="ts">
        import { onBeforeUnmount, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const props = defineProps<{ workMs?: number }>();
        const state = ref<'idle' | 'working' | 'done'>('idle');
        const timers: number[] = [];

        const later = (fn: () => void, ms: number) => { timers.push(window.setTimeout(fn, ms)); };

        const save = () => {
          if (state.value === 'working') return;
          state.value = 'working';
          later(() => {
            state.value = 'done';
            later(() => { state.value = 'idle'; }, 1800);
          }, props.workMs ?? 2000);
        };

        // Unmounting clears every pending tick — the button never lies.
        onBeforeUnmount(() => timers.forEach(clearTimeout));
        </script>

        <template>
          <button type="button" class="ldo-save" :data-state="state" :disabled="state === 'working'" @click="save">
            <span v-if="state === 'working'" class="ldo-orbit" aria-hidden="true"><i /><i /><i /><i /></span>
            <svg v-if="state === 'done'" class="ldo-check" viewBox="0 0 24 24" aria-hidden="true">
              <path d="M4.5 12.5l5 5 10-11" />
            </svg>
            <span aria-live="polite">
              {{ state === 'working' ? 'Saving…' : state === 'done' ? 'Saved' : 'Save changes' }}
            </span>
          </button>
        </template>

        <style scoped>
        .ldo-save { display: inline-flex; align-items: center; justify-content: center; gap: .6rem;
                    min-inline-size: 11rem; block-size: 2.8rem; padding-inline: 1.3rem; border: none;
                    border-radius: 999px; background: #4f46e5; color: #fff; cursor: pointer;
                    font: 600 .88rem/1 'Inter', sans-serif; transition: background-color .25s ease; }
        .ldo-save:hover:not(:disabled) { background: #4338ca; }
        .ldo-save[data-state='done'] { background: #059669; }
        .ldo-save:disabled { cursor: default; }
        .ldo-orbit { position: relative; inline-size: 22px; block-size: 14px; flex: none; }
        .ldo-orbit i { position: absolute; inset: 0;
                       offset-path: path('M2 7 A9 5.5 0 1 1 20 7 A9 5.5 0 1 1 2 7');
                       animation: ldo-lap 1.15s cubic-bezier(.62,.05,.37,.95) infinite; }
        .ldo-orbit i::after { content: ''; position: absolute; inset-block-start: 50%; inset-inline-start: 50%;
                              inline-size: 5px; aspect-ratio: 1; border-radius: 50%; background: currentColor;
                              translate: -50% -50%; }
        .ldo-orbit i:nth-child(2) { animation-delay: -.16s; } .ldo-orbit i:nth-child(2)::after { opacity: .7; scale: .8; }
        .ldo-orbit i:nth-child(3) { animation-delay: -.32s; } .ldo-orbit i:nth-child(3)::after { opacity: .5; scale: .6; }
        .ldo-orbit i:nth-child(4) { animation-delay: -.48s; } .ldo-orbit i:nth-child(4)::after { opacity: .35; scale: .45; }
        .ldo-check { inline-size: 1.15rem; flex: none; }
        .ldo-check path { fill: none; stroke: currentColor; stroke-width: 2.4; stroke-linecap: round;
                          stroke-linejoin: round; stroke-dasharray: 24; stroke-dashoffset: 24;
                          animation: ldo-draw .45s ease-out forwards; }
        @keyframes ldo-lap { from { offset-distance: 0%; } to { offset-distance: 100%; } }
        @keyframes ldo-draw { to { stroke-dashoffset: 0; } }
        @media (prefers-reduced-motion: reduce) {
          .ldo-orbit i { animation: none; }
          .ldo-orbit i:nth-child(1) { offset-distance: 0%; }  .ldo-orbit i:nth-child(2) { offset-distance: 25%; }
          .ldo-orbit i:nth-child(3) { offset-distance: 50%; } .ldo-orbit i:nth-child(4) { offset-distance: 75%; }
          .ldo-check path { animation: none; stroke-dashoffset: 0; }
        }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- SaveButton.svelte — orbit dots chasing inside a button that really saves -->
        <script lang="ts">
          import { onDestroy } from 'svelte';
          import '@nabuxai/ui-core/css';

          let { workMs = 2000 } = $props();
          let state = $state<'idle' | 'working' | 'done'>('idle');
          const timers: number[] = [];

          const later = (fn: () => void, ms: number) => { timers.push(window.setTimeout(fn, ms)); };

          const save = () => {
            if (state === 'working') return;
            state = 'working';
            later(() => {
              state = 'done';
              later(() => { state = 'idle'; }, 1800);
            }, workMs);
          };

          // Unmounting clears every pending tick — the button never lies.
          onDestroy(() => timers.forEach(clearTimeout));
        </script>

        <button type="button" class="ldo-save" data-state={state} disabled={state === 'working'} onclick={save}>
          {#if state === 'working'}
            <span class="ldo-orbit" aria-hidden="true"><i /><i /><i /><i /></span>
          {/if}
          {#if state === 'done'}
            <svg class="ldo-check" viewBox="0 0 24 24" aria-hidden="true"><path d="M4.5 12.5l5 5 10-11" /></svg>
          {/if}
          <span aria-live="polite">
            {state === 'working' ? 'Saving…' : state === 'done' ? 'Saved' : 'Save changes'}
          </span>
        </button>

        <style>
          .ldo-save { display: inline-flex; align-items: center; justify-content: center; gap: .6rem;
                      min-inline-size: 11rem; block-size: 2.8rem; padding-inline: 1.3rem; border: none;
                      border-radius: 999px; background: #4f46e5; color: #fff; cursor: pointer;
                      font: 600 .88rem/1 'Inter', sans-serif; transition: background-color .25s ease; }
          .ldo-save:hover:not(:disabled) { background: #4338ca; }
          .ldo-save[data-state='done'] { background: #059669; }
          .ldo-save:disabled { cursor: default; }
          .ldo-orbit { position: relative; inline-size: 22px; block-size: 14px; flex: none; }
          .ldo-orbit i { position: absolute; inset: 0;
                         offset-path: path('M2 7 A9 5.5 0 1 1 20 7 A9 5.5 0 1 1 2 7');
                         animation: ldo-lap 1.15s cubic-bezier(.62,.05,.37,.95) infinite; }
          .ldo-orbit i::after { content: ''; position: absolute; inset-block-start: 50%; inset-inline-start: 50%;
                                inline-size: 5px; aspect-ratio: 1; border-radius: 50%; background: currentColor;
                                translate: -50% -50%; }
          .ldo-orbit i:nth-child(2) { animation-delay: -.16s; } .ldo-orbit i:nth-child(2)::after { opacity: .7; scale: .8; }
          .ldo-orbit i:nth-child(3) { animation-delay: -.32s; } .ldo-orbit i:nth-child(3)::after { opacity: .5; scale: .6; }
          .ldo-orbit i:nth-child(4) { animation-delay: -.48s; } .ldo-orbit i:nth-child(4)::after { opacity: .35; scale: .45; }
          .ldo-check { inline-size: 1.15rem; flex: none; }
          .ldo-check path { fill: none; stroke: currentColor; stroke-width: 2.4; stroke-linecap: round;
                            stroke-linejoin: round; stroke-dasharray: 24; stroke-dashoffset: 24;
                            animation: ldo-draw .45s ease-out forwards; }
          @keyframes ldo-lap { from { offset-distance: 0%; } to { offset-distance: 100%; } }
          @keyframes ldo-draw { to { stroke-dashoffset: 0; } }
          @media (prefers-reduced-motion: reduce) {
            .ldo-orbit i { animation: none; }
            .ldo-orbit i:nth-child(1) { offset-distance: 0%; }  .ldo-orbit i:nth-child(2) { offset-distance: 25%; }
            .ldo-orbit i:nth-child(3) { offset-distance: 50%; } .ldo-orbit i:nth-child(4) { offset-distance: 75%; }
            .ldo-check path { animation: none; stroke-dashoffset: 0; }
          }
        </style>
        SVELTE,
        ],
    ],

    'morph-ring' => [
        'title' => ['fa' => 'حلقهٔ مورف‌شونده', 'en' => 'Morphing ring'],
        'icon' => 'wand',
        'oneLiner' => [
            'fa' => 'حلقه‌ای که ضخامت، شکست و پهنایش با keyframe دمد می‌شود و هم‌زمان می‌چرخد؛ درصد داخلش بالا می‌شمارد تا تیک نهایی. در کارت بازپرداخت کامل می‌شود و در شارژ کیف پول، وسط راه سرخ می‌ایستد و می‌سزد — «تلاش دوباره» همان حلقه را از نو به کار می‌اندازد.',
            'en' => 'A ring whose weight, break and breadth morph through keyframes while it turns; the percent inside counts up to its final tick. It completes in the refund card, and freezes red mid-way in the wallet top-up — Retry sets the very same ring turning again.',
        ],
        'js' => true,
        'docs' => 'https://circleloaders.dominikakissi.com',
        'props' => [
            ['name' => 'morph', 'type' => 'keyframes', 'default' => "'2.6s ease-in-out'", 'note' => [
                'fa' => 'چهار کلید: ضخامت میان ۳ تا ۱۲ و شکست dasharray میان کمان بلند و ترک‌های ریز — حلقه هر لحظه فرم دیگری دارد.',
                'en' => 'Four keys: weight swings 3→12 while the dasharray trades a long arc for tiny cracks — the ring never holds one shape.',
            ]],
            ['name' => 'spin', 'type' => 's', 'default' => "'1.8'", 'note' => [
                'fa' => 'چرخش کل گروه؛ مورف روی خودش می‌چرخد تا جهت شکست هم مدام عوض شود.',
                'en' => 'The whole group’s turn; the morph rotates with it so even the cracks keep changing direction.',
            ]],
            ['name' => 'progress', 'type' => '%', 'default' => "'0 → 100'", 'note' => [
                'fa' => 'کمان نازک درونی درصد را دنبال می‌کند و عدد وسط با آن می‌شمارد؛ خوانش دقیق زیر حس بی‌دقتیِ مورف.',
                'en' => 'A thin inner arc follows the percent while the number counts with it — exact reading under the morph’s deliberate chaos.',
            ]],
            ['name' => 'error', 'type' => '%', 'default' => "'58'", 'note' => [
                'fa' => 'در خطا رنگ به قرمز می‌رود، انیمیشن paused می‌شود و درصد همان‌جا می‌ماند تا تلاش دوباره.',
                'en' => 'On error the colour turns red, the animation pauses and the percent stays put until the retry.',
            ]],
            ['name' => 'track', 'type' => 'r', 'default' => "'50'", 'note' => [
                'fa' => 'مسیر خاکستری زیر هر دو کمان؛ با pathLength=100 محاسبات درصد بدون دردسر است.',
                'en' => 'The grey path under both arcs; pathLength=100 keeps the percent maths painless.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="ldm-wrap" data-state="working" role="progressbar" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100">
            <svg viewBox="0 0 120 120" aria-hidden="true">
                <circle class="ldm-track" cx="60" cy="60" r="50" pathLength="100"/>
                <circle class="ldm-morph" cx="60" cy="60" r="50" pathLength="100"/>
                <circle class="ldm-fill" cx="60" cy="60" r="50" pathLength="100" style="stroke-dashoffset: 38"/>
            </svg>
            <b>۶۲٪</b>
        </div>

        <style>
        .ldm-wrap { position: relative; display: grid; place-items: center; inline-size: 7.5rem; aspect-ratio: 1;
                    color: #7c3aed; font: 700 1.3rem/1 'Inter', 'Vazirmatn', sans-serif; }
        .ldm-wrap svg { position: absolute; inset: 0; }
        .ldm-wrap b { position: relative; }
        .ldm-track, .ldm-morph, .ldm-fill { fill: none; }
        .ldm-track { stroke: #ede9fe; stroke-width: 3; }
        .ldm-fill { stroke: color-mix(in oklab, currentColor 35%, transparent); stroke-width: 3;
                    stroke-dasharray: 100; transform: rotate(-90deg); transform-origin: 50% 50%; }
        .ldm-morph { stroke: currentColor; stroke-linecap: round;
                     animation: ldm-morph 2.6s ease-in-out infinite, ldm-spin 1.8s linear infinite; }
        .ldm-wrap[data-state='error'] .ldm-morph { animation-play-state: paused; }
        @keyframes ldm-morph {
            0%, 100% { stroke-width: 3;  stroke-dasharray: 58 42; }
            28%      { stroke-width: 10; stroke-dasharray: 22 30; }
            52%      { stroke-width: 5;  stroke-dasharray: 84 16; }
            76%      { stroke-width: 12; stroke-dasharray: 10 26; }
        }
        @keyframes ldm-spin { to { rotate: 360deg; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="ldm-wrap" data-state="working" role="progressbar" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100">
        <svg viewBox="0 0 120 120" aria-hidden="true">
        <circle class="ldm-track" cx="60" cy="60" r="50" pathLength="100"/>
        <circle class="ldm-morph" cx="60" cy="60" r="50" pathLength="100"/>
        <circle class="ldm-fill" cx="60" cy="60" r="50" pathLength="100" style="stroke-dashoffset: 38"/>
        </svg>
        <b>62%</b>
        </div>

        <style>
        .ldm-wrap { position: relative; display: grid; place-items: center; inline-size: 7.5rem; aspect-ratio: 1;
                    color: #7c3aed; font: 700 1.3rem/1 'Inter', 'Vazirmatn', sans-serif; }
        .ldm-wrap svg { position: absolute; inset: 0; }
        .ldm-wrap b { position: relative; }
        .ldm-track, .ldm-morph, .ldm-fill { fill: none; }
        .ldm-track { stroke: #ede9fe; stroke-width: 3; }
        .ldm-fill { stroke: color-mix(in oklab, currentColor 35%, transparent); stroke-width: 3;
                    stroke-dasharray: 100; transform: rotate(-90deg); transform-origin: 50% 50%; }
        .ldm-morph { stroke: currentColor; stroke-linecap: round;
                     animation: ldm-morph 2.6s ease-in-out infinite, ldm-spin 1.8s linear infinite; }
        .ldm-wrap[data-state='error'] .ldm-morph { animation-play-state: paused; }
        @keyframes ldm-morph {
        0%, 100% { stroke-width: 3;  stroke-dasharray: 58 42; }
        28%      { stroke-width: 10; stroke-dasharray: 22 30; }
        52%      { stroke-width: 5;  stroke-dasharray: 84 16; }
        76%      { stroke-width: 12; stroke-dasharray: 10 26; }
        }
        @keyframes ldm-spin { to { rotate: 360deg; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Wallet.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link in the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import TopUpRing from '@/components/TopUpRing';

        export default function Wallet() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <TopUpRing amount={50} failAt={58} />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // TopUpRing.jsx — a morphing ring that counts up, freezes red on gateway error
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        export default function TopUpRing({ amount = 50, failAt = 58, workMs = 4200 }) {
          const [state, setState] = useState('idle'); // idle | working | error | done
          const [p, setP] = useState(0);
          const retried = useRef(false);
          const ticks = useRef<number[]>([]);

          // Unmount clears every interval — no ring spins on behind the page.
          useEffect(() => () => ticks.current.forEach(clearInterval), []);

          const run = () => {
            if (state === 'working') return;
            setState('working');
            setP(0);
            const step = 100 / (workMs / 55);
            const t = window.setInterval(() => {
              setP((prev) => {
                const next = Math.min(100, prev + step * (0.7 + Math.random() * 0.6));
                if (!retried.current && next >= failAt) {
                  clearInterval(t);
                  retried.current = true;
                  setState('error');
                  return failAt;
                }
                if (next >= 100) { clearInterval(t); setState('done'); }
                return next;
              });
            }, 55);
            ticks.current.push(t);
          };

          return (
            <>
              <style>{`
                .ldm-card { display: grid; justify-items: center; gap: 1rem; padding: 1.4rem;
                            border: 1px solid #e7e5e4; border-radius: 1rem; font-family: 'Inter', sans-serif;
                            max-inline-size: 22rem; }
                .ldm-wrap { position: relative; display: grid; place-items: center; inline-size: 7.5rem; aspect-ratio: 1;
                            color: #7c3aed; font: 700 1.3rem/1 inherit; }
                .ldm-wrap[data-state='error'] { color: #dc2626; }
                .ldm-wrap[data-state='done'] { color: #059669; }
                .ldm-wrap svg { position: absolute; inset: 0; }
                .ldm-track, .ldm-morph, .ldm-fill { fill: none; }
                .ldm-track { stroke: #ede9fe; stroke-width: 3; }
                .ldm-fill { stroke: color-mix(in oklab, currentColor 35%, transparent); stroke-width: 3;
                            stroke-dasharray: 100; transform: rotate(-90deg); transform-origin: 50% 50%; }
                .ldm-morph { stroke: currentColor; stroke-linecap: round;
                             animation: ldm-morph 2.6s ease-in-out infinite, ldm-spin 1.8s linear infinite; }
                .ldm-wrap[data-state='done'] .ldm-morph { display: none; }
                .ldm-wrap[data-state='error'] .ldm-morph { animation-play-state: paused; }
                @keyframes ldm-morph {
                  0%, 100% { stroke-width: 3;  stroke-dasharray: 58 42; }
                  28%      { stroke-width: 10; stroke-dasharray: 22 30; }
                  52%      { stroke-width: 5;  stroke-dasharray: 84 16; }
                  76%      { stroke-width: 12; stroke-dasharray: 10 26; }
                }
                @keyframes ldm-spin { to { rotate: 360deg; } }
                .ldm-btn { block-size: 2.6rem; padding-inline: 1.2rem; border: none; border-radius: 999px;
                           background: #7c3aed; color: #fff; font: 600 .88rem/1 inherit; cursor: pointer; }
                .ldm-btn:disabled { cursor: default; }
                .ldm-err { color: #dc2626; font-size: .8rem; }
                @media (prefers-reduced-motion: reduce) { .ldm-morph { animation: none; stroke-width: 6; stroke-dasharray: 58 42; } }
              `}</style>
              <div className="ldm-card">
                <div className="ldm-wrap" data-state={state} role="progressbar"
                     aria-valuenow={Math.round(p)} aria-valuemin={0} aria-valuemax={100}>
                  <svg viewBox="0 0 120 120" aria-hidden="true">
                    <circle className="ldm-track" cx="60" cy="60" r="50" pathLength="100" />
                    <circle className="ldm-morph" cx="60" cy="60" r="50" pathLength="100" />
                    <circle className="ldm-fill" cx="60" cy="60" r="50" pathLength="100"
                            style={{ strokeDashoffset: 100 - Math.round(p) }} />
                  </svg>
                  <b>{Math.round(p)}%</b>
                </div>
                <button type="button" className="ldm-btn" onClick={run} disabled={state === 'working'}>
                  {state === 'error' ? 'Retry' : `Top up $${amount}`}
                </button>
                <p className="ldm-err" role="status">
                  {state === 'error' ? 'Gateway did not answer (502).' : state === 'done' ? 'Balance updated.' : ''}
                </p>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- TopUpRing.vue — a morphing ring that counts up, freezes red on gateway error -->
        <script setup lang="ts">
        import { onBeforeUnmount, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const props = defineProps<{ amount?: number; failAt?: number; workMs?: number }>();
        const state = ref<'idle' | 'working' | 'error' | 'done'>('idle');
        const p = ref(0);
        let retried = false;
        const ticks: number[] = [];

        const run = () => {
          if (state.value === 'working') return;
          state.value = 'working';
          p.value = 0;
          const step = 100 / ((props.workMs ?? 4200) / 55);
          const t = window.setInterval(() => {
            p.value = Math.min(100, p.value + step * (0.7 + Math.random() * 0.6));
            if (!retried && p.value >= (props.failAt ?? 58)) {
              clearInterval(t); retried = true; state.value = 'error'; p.value = props.failAt ?? 58;
            } else if (p.value >= 100) {
              clearInterval(t); state.value = 'done';
            }
          }, 55);
          ticks.push(t);
        };

        // Unmount clears every interval — no ring spins on behind the page.
        onBeforeUnmount(() => ticks.forEach(clearInterval));
        </script>

        <template>
          <div class="ldm-card">
            <div class="ldm-wrap" :data-state="state" role="progressbar"
                 :aria-valuenow="Math.round(p)" aria-valuemin="0" aria-valuemax="100">
              <svg viewBox="0 0 120 120" aria-hidden="true">
                <circle class="ldm-track" cx="60" cy="60" r="50" pathLength="100" />
                <circle class="ldm-morph" cx="60" cy="60" r="50" pathLength="100" />
                <circle class="ldm-fill" cx="60" cy="60" r="50" pathLength="100"
                        :style="{ strokeDashoffset: 100 - Math.round(p) }" />
              </svg>
              <b>{{ Math.round(p) }}%</b>
            </div>
            <button type="button" class="ldm-btn" :disabled="state === 'working'" @click="run">
              {{ state === 'error' ? 'Retry' : `Top up $${props.amount ?? 50}` }}
            </button>
            <p class="ldm-err" role="status">
              {{ state === 'error' ? 'Gateway did not answer (502).' : state === 'done' ? 'Balance updated.' : '' }}
            </p>
          </div>
        </template>

        <style scoped>
        .ldm-card { display: grid; justify-items: center; gap: 1rem; padding: 1.4rem;
                    border: 1px solid #e7e5e4; border-radius: 1rem; font-family: 'Inter', sans-serif;
                    max-inline-size: 22rem; }
        .ldm-wrap { position: relative; display: grid; place-items: center; inline-size: 7.5rem; aspect-ratio: 1;
                    color: #7c3aed; font: 700 1.3rem/1 inherit; }
        .ldm-wrap[data-state='error'] { color: #dc2626; }
        .ldm-wrap[data-state='done'] { color: #059669; }
        .ldm-wrap svg { position: absolute; inset: 0; }
        .ldm-track, .ldm-morph, .ldm-fill { fill: none; }
        .ldm-track { stroke: #ede9fe; stroke-width: 3; }
        .ldm-fill { stroke: color-mix(in oklab, currentColor 35%, transparent); stroke-width: 3;
                    stroke-dasharray: 100; transform: rotate(-90deg); transform-origin: 50% 50%; }
        .ldm-morph { stroke: currentColor; stroke-linecap: round;
                     animation: ldm-morph 2.6s ease-in-out infinite, ldm-spin 1.8s linear infinite; }
        .ldm-wrap[data-state='done'] .ldm-morph { display: none; }
        .ldm-wrap[data-state='error'] .ldm-morph { animation-play-state: paused; }
        @keyframes ldm-morph {
          0%, 100% { stroke-width: 3;  stroke-dasharray: 58 42; }
          28%      { stroke-width: 10; stroke-dasharray: 22 30; }
          52%      { stroke-width: 5;  stroke-dasharray: 84 16; }
          76%      { stroke-width: 12; stroke-dasharray: 10 26; }
        }
        @keyframes ldm-spin { to { rotate: 360deg; } }
        .ldm-btn { block-size: 2.6rem; padding-inline: 1.2rem; border: none; border-radius: 999px;
                   background: #7c3aed; color: #fff; font: 600 .88rem/1 inherit; cursor: pointer; }
        .ldm-btn:disabled { cursor: default; }
        .ldm-err { color: #dc2626; font-size: .8rem; }
        @media (prefers-reduced-motion: reduce) { .ldm-morph { animation: none; stroke-width: 6; stroke-dasharray: 58 42; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- TopUpRing.svelte — a morphing ring that counts up, freezes red on gateway error -->
        <script lang="ts">
          import { onDestroy } from 'svelte';
          import '@nabuxai/ui-core/css';

          let { amount = 50, failAt = 58, workMs = 4200 } = $props();
          let state = $state<'idle' | 'working' | 'error' | 'done'>('idle');
          let p = $state(0);
          let retried = false;
          const ticks: number[] = [];

          const run = () => {
            if (state === 'working') return;
            state = 'working';
            p = 0;
            const step = 100 / (workMs / 55);
            const t = window.setInterval(() => {
              p = Math.min(100, p + step * (0.7 + Math.random() * 0.6));
              if (!retried && p >= failAt) {
                clearInterval(t); retried = true; state = 'error'; p = failAt;
              } else if (p >= 100) {
                clearInterval(t); state = 'done';
              }
            }, 55);
            ticks.push(t);
          };

          // Unmount clears every interval — no ring spins on behind the page.
          onDestroy(() => ticks.forEach(clearInterval));
        </script>

        <div class="ldm-card">
          <div class="ldm-wrap" data-state={state} role="progressbar"
               aria-valuenow={Math.round(p)} aria-valuemin="0" aria-valuemax="100">
            <svg viewBox="0 0 120 120" aria-hidden="true">
              <circle class="ldm-track" cx="60" cy="60" r="50" pathLength="100" />
              <circle class="ldm-morph" cx="60" cy="60" r="50" pathLength="100" />
              <circle class="ldm-fill" cx="60" cy="60" r="50" pathLength="100"
                      style={`stroke-dashoffset: ${100 - Math.round(p)}`} />
            </svg>
            <b>{Math.round(p)}%</b>
          </div>
          <button type="button" class="ldm-btn" disabled={state === 'working'} onclick={run}>
            {state === 'error' ? 'Retry' : `Top up $${amount}`}
          </button>
          <p class="ldm-err" role="status">
            {state === 'error' ? 'Gateway did not answer (502).' : state === 'done' ? 'Balance updated.' : ''}
          </p>
        </div>

        <style>
          .ldm-card { display: grid; justify-items: center; gap: 1rem; padding: 1.4rem;
                      border: 1px solid #e7e5e4; border-radius: 1rem; font-family: 'Inter', sans-serif;
                      max-inline-size: 22rem; }
          .ldm-wrap { position: relative; display: grid; place-items: center; inline-size: 7.5rem; aspect-ratio: 1;
                      color: #7c3aed; font: 700 1.3rem/1 inherit; }
          .ldm-wrap[data-state='error'] { color: #dc2626; }
          .ldm-wrap[data-state='done'] { color: #059669; }
          .ldm-wrap svg { position: absolute; inset: 0; }
          .ldm-track, .ldm-morph, .ldm-fill { fill: none; }
          .ldm-track { stroke: #ede9fe; stroke-width: 3; }
          .ldm-fill { stroke: color-mix(in oklab, currentColor 35%, transparent); stroke-width: 3;
                      stroke-dasharray: 100; transform: rotate(-90deg); transform-origin: 50% 50%; }
          .ldm-morph { stroke: currentColor; stroke-linecap: round;
                       animation: ldm-morph 2.6s ease-in-out infinite, ldm-spin 1.8s linear infinite; }
          .ldm-wrap[data-state='done'] .ldm-morph { display: none; }
          .ldm-wrap[data-state='error'] .ldm-morph { animation-play-state: paused; }
          @keyframes ldm-morph {
            0%, 100% { stroke-width: 3;  stroke-dasharray: 58 42; }
            28%      { stroke-width: 10; stroke-dasharray: 22 30; }
            52%      { stroke-width: 5;  stroke-dasharray: 84 16; }
            76%      { stroke-width: 12; stroke-dasharray: 10 26; }
          }
          @keyframes ldm-spin { to { rotate: 360deg; } }
          .ldm-btn { block-size: 2.6rem; padding-inline: 1.2rem; border: none; border-radius: 999px;
                     background: #7c3aed; color: #fff; font: 600 .88rem/1 inherit; cursor: pointer; }
          .ldm-btn:disabled { cursor: default; }
          .ldm-err { color: #dc2626; font-size: .8rem; }
          @media (prefers-reduced-motion: reduce) { .ldm-morph { animation: none; stroke-width: 6; stroke-dasharray: 58 42; } }
        </style>
        SVELTE,
        ],
    ],

    'segment-spin' => [
        'title' => ['fa' => 'کمان‌های پله‌ای', 'en' => 'Segmented spin'],
        'icon' => 'zap',
        'oneLiner' => [
            'fa' => 'بارگذار پله‌ای به سبک iOS: هشت کمان که پشت‌سرهم محو و روشن می‌شوند و چشم را در مدار نگه می‌دارند — بی‌درصد، بی‌ادعا، به‌سبک. در سه کارت وضعیت واقعی و سه اندازه: همگام‌سازی مخاطبین، بارگذاری ویدیو که کامل می‌شود و جست‌وجوی پرواز که سرورش نمی‌پاسد تا تلاش دوباره سبز شود.',
            'en' => 'The iOS-style stepped spin: eight arcs fading in sequence, holding the eye in orbit — no percent, no promises, featherweight. Three real status cards at three sizes: a contacts sync, a video upload that completes, and a flight search whose server goes quiet until the retry turns green.',
        ],
        'js' => true,
        'docs' => 'https://circleloaders.dominikakissi.com',
        'props' => [
            ['name' => 'segments', 'type' => 'count', 'default' => "'8'", 'note' => [
                'fa' => 'هشت کمان با pathLength=100؛ هر کمان ۱۰٫۵ واحد دیده می‌شود و بقیه‌اش با gap می‌پرد.',
                'en' => 'Eight arcs on pathLength=100; each arc shows 10.5 units and gaps over the rest.',
            ]],
            ['name' => 'fade', 'type' => 's', 'default' => "'0.96'", 'note' => [
                'fa' => 'چرخهٔ محوشدن؛ تأخیر پله‌ای هر کمان ۱۲۰ms- است تا تعقیب، پله‌پله و نرم دیده شود.',
                'en' => 'The fade cycle; each arc carries a −120ms stepped delay so the chase reads both ticked and smooth.',
            ]],
            ['name' => 'sizes', 'type' => 'px', 'default' => "'18 · 26 · 38'", 'note' => [
                'fa' => 'سه اندازهٔ هم‌خانواده؛ کوچک برای سطرهای فهرست، بزرگ برای صفحه‌های انتظار تمام‌عرض.',
                'en' => 'One family, three sizes; small for list rows, large for full-page waits.',
            ]],
            ['name' => 'states', 'type' => 'attr', 'default' => "'working · done · error'", 'note' => [
                'fa' => 'کمان‌ها در موفقیت جای‌شان را به تیک و در خطا به هشدار می‌دهند — خانهٔ نشانگر همیشه هم‌قد است.',
                'en' => 'On success the arcs yield to a tick, on error to an alert — the indicator slot keeps one size.',
            ]],
            ['name' => 'weight', 'type' => 'stroke', 'default' => "'2.4'", 'note' => [
                'fa' => 'ضخامت کمان در واحد viewBox؛ با بزرگ‌شدن نشانگر خودش مقیاس می‌شود، بدون بازطراحی.',
                'en' => 'The arc weight in viewBox units; it scales as the indicator grows, no redraw needed.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <span class="lds-spin" style="--lds-size: 26px" role="status" aria-label="در حال همگام‌سازی">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <circle cx="12" cy="12" r="8.5" pathLength="100" transform="rotate(0 12 12)" style="--lds-i: 0"/>
                <circle cx="12" cy="12" r="8.5" pathLength="100" transform="rotate(45 12 12)" style="--lds-i: 1"/>
                <circle cx="12" cy="12" r="8.5" pathLength="100" transform="rotate(90 12 12)" style="--lds-i: 2"/>
                <circle cx="12" cy="12" r="8.5" pathLength="100" transform="rotate(135 12 12)" style="--lds-i: 3"/>
            </svg>
        </span>

        <style>
        .lds-spin { display: inline-grid; place-items: center; inline-size: var(--lds-size); block-size: var(--lds-size);
                    color: #0a84ff; }
        .lds-spin svg { inline-size: 100%; block-size: 100%; }
        .lds-spin circle {
            fill: none; stroke: currentColor; stroke-width: 2.4; stroke-linecap: round;
            stroke-dasharray: 10.5 89.5; stroke-dashoffset: -6;
            animation: lds-fade .96s linear infinite;
            animation-delay: calc(var(--lds-i) * -.12s);
        }
        @keyframes lds-fade { 0%, 100% { opacity: .12; } 42% { opacity: 1; } }
        @media (prefers-reduced-motion: reduce) { .lds-spin circle { animation: none; opacity: .85; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <span class="lds-spin" style="--lds-size: 26px" role="status" aria-label="Syncing">
        <svg viewBox="0 0 24 24" aria-hidden="true">
        <circle cx="12" cy="12" r="8.5" pathLength="100" transform="rotate(0 12 12)" style="--lds-i: 0"/>
        <circle cx="12" cy="12" r="8.5" pathLength="100" transform="rotate(45 12 12)" style="--lds-i: 1"/>
        <circle cx="12" cy="12" r="8.5" pathLength="100" transform="rotate(90 12 12)" style="--lds-i: 2"/>
        <circle cx="12" cy="12" r="8.5" pathLength="100" transform="rotate(135 12 12)" style="--lds-i: 3"/>
        </svg>
        </span>

        <style>
        .lds-spin { display: inline-grid; place-items: center; inline-size: var(--lds-size); block-size: var(--lds-size);
                    color: #0a84ff; }
        .lds-spin svg { inline-size: 100%; block-size: 100%; }
        .lds-spin circle {
        fill: none; stroke: currentColor; stroke-width: 2.4; stroke-linecap: round;
        stroke-dasharray: 10.5 89.5; stroke-dashoffset: -6;
        animation: lds-fade .96s linear infinite;
        animation-delay: calc(var(--lds-i) * -.12s);
        }
        @keyframes lds-fade { 0%, 100% { opacity: .12; } 42% { opacity: 1; } }
        @media (prefers-reduced-motion: reduce) { .lds-spin circle { animation: none; opacity: .85; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Sync.jsx — Inertia (React) page; renders the React snippet
        // below unchanged, with Inertia's Link in the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import ContactsSync from '@/components/ContactsSync';

        export default function Sync() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <ContactsSync total={512} startFrom={384} />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // ContactsSync.jsx — the segmented spin counting a real sync
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        export default function ContactsSync({ total = 512, startFrom = 384 }) {
          const [state, setState] = useState('working'); // working | done
          const [count, setCount] = useState(startFrom);
          const ticks = useRef<number[]>([]);

          // Unmount clears every interval — no spinner spins behind the page.
          useEffect(() => () => ticks.current.forEach(clearInterval), []);

          useEffect(() => {
            const t = window.setInterval(() => {
              setCount((c) => {
                const next = Math.min(total, c + 3 + Math.floor(Math.random() * 4));
                if (next >= total) { clearInterval(t); setState('done'); }
                return next;
              });
            }, 240);
            ticks.current.push(t);
          }, []);

          const replay = () => {
            if (state === 'working') return;
            setState('working');
            setCount(0);
            const t = window.setInterval(() => {
              setCount((c) => {
                const next = Math.min(total, c + 6 + Math.floor(Math.random() * 9));
                if (next >= total) { clearInterval(t); setState('done'); }
                return next;
              });
            }, 110);
            ticks.current.push(t);
          };

          return (
            <>
              <style>{`
                .lds-card { display: grid; gap: .8rem; justify-items: start; padding: 1.2rem;
                            border: 1px solid #e7e5e4; border-radius: 1rem;
                            font-family: 'Inter', sans-serif; max-inline-size: 20rem; }
                .lds-head { display: flex; align-items: center; gap: .8rem; }
                .lds-spin { display: inline-grid; place-items: center; flex: none;
                            inline-size: var(--lds-size); block-size: var(--lds-size); color: #0a84ff; }
                .lds-spin svg { inline-size: 100%; block-size: 100%; }
                .lds-spin circle { fill: none; stroke: currentColor; stroke-width: 2.4; stroke-linecap: round;
                                   stroke-dasharray: 10.5 89.5; stroke-dashoffset: -6;
                                   animation: lds-fade .96s linear infinite;
                                   animation-delay: calc(var(--lds-i) * -.12s); }
                @keyframes lds-fade { 0%, 100% { opacity: .12; } 42% { opacity: 1; } }
                .lds-card[data-state='done'] .lds-spin { color: #059669; }
                .lds-card h4 { margin: 0; font-size: .9rem; }
                .lds-meta { margin: 0; font-size: .78rem; color: #78716c; }
                .lds-replay { margin-block-start: .2rem; border: none; background: none; color: #0a84ff;
                              font: 600 .78rem/1 inherit; cursor: pointer; text-decoration: underline;
                              text-underline-offset: .2em; }
                @media (prefers-reduced-motion: reduce) { .lds-spin circle { animation: none; opacity: .85; } }
              `}</style>
              <div className="lds-card" data-state={state}>
                <div className="lds-head">
                  <span className="lds-spin" style={{ ['--lds-size' as string]: '26px' }} role="status" aria-label="Syncing">
                    {state === 'working' ? (
                      <svg viewBox="0 0 24 24" aria-hidden="true">
                        {Array.from({ length: 8 }, (_, i) => (
                          <circle key={i} cx="12" cy="12" r="8.5" pathLength="100"
                                  transform={`rotate(${i * 45} 12 12)`} style={{ ['--lds-i' as string]: i }} />
                        ))}
                      </svg>
                    ) : (
                      <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path fill="none" stroke="currentColor" strokeWidth="2.6" strokeLinecap="round"
                              strokeLinejoin="round" d="M4.5 12.5l5 5 10-11" />
                      </svg>
                    )}
                  </span>
                  <h4>Contacts · Outlook sync</h4>
                </div>
                <p className="lds-meta" aria-live="polite">
                  {state === 'done' ? `Complete · ${total} contacts` : `${count} of ${total} contacts…`}
                </p>
                {state === 'done' && (
                  <button type="button" className="lds-replay" onClick={replay}>Run it again</button>
                )}
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- ContactsSync.vue — the segmented spin counting a real sync -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const props = defineProps<{ total?: number; startFrom?: number }>();
        const state = ref<'working' | 'done'>('working');
        const count = ref(props.startFrom ?? 384);
        const ticks: number[] = [];

        const watch = (step: () => number, ms: number) => {
          const t = window.setInterval(() => {
            count.value = Math.min(props.total ?? 512, step());
            if (count.value >= (props.total ?? 512)) { clearInterval(t); state.value = 'done'; }
          }, ms);
          ticks.push(t);
        };

        onMounted(() => watch(() => count.value + 3 + Math.floor(Math.random() * 4), 240));

        const replay = () => {
          if (state.value === 'working') return;
          state.value = 'working';
          count.value = 0;
          watch(() => count.value + 6 + Math.floor(Math.random() * 9), 110);
        };

        // Unmount clears every interval — no spinner spins behind the page.
        onBeforeUnmount(() => ticks.forEach(clearInterval));
        </script>

        <template>
          <div class="lds-card" :data-state="state">
            <div class="lds-head">
              <span class="lds-spin" style="--lds-size: 26px" role="status" aria-label="Syncing">
                <svg v-if="state === 'working'" viewBox="0 0 24 24" aria-hidden="true">
                  <circle v-for="i in 8" :key="i" cx="12" cy="12" r="8.5" pathLength="100"
                          :transform="`rotate(${(i - 1) * 45} 12 12)`" :style="{ '--lds-i': i - 1 }" />
                </svg>
                <svg v-else viewBox="0 0 24 24" aria-hidden="true">
                  <path fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"
                        stroke-linejoin="round" d="M4.5 12.5l5 5 10-11" />
                </svg>
              </span>
              <h4>Contacts · Outlook sync</h4>
            </div>
            <p class="lds-meta" aria-live="polite">
              {{ state === 'done' ? `Complete · ${total ?? 512} contacts` : `${count} of ${total ?? 512} contacts…` }}
            </p>
            <button v-if="state === 'done'" type="button" class="lds-replay" @click="replay">Run it again</button>
          </div>
        </template>

        <style scoped>
        .lds-card { display: grid; gap: .8rem; justify-items: start; padding: 1.2rem;
                    border: 1px solid #e7e5e4; border-radius: 1rem;
                    font-family: 'Inter', sans-serif; max-inline-size: 20rem; }
        .lds-head { display: flex; align-items: center; gap: .8rem; }
        .lds-spin { display: inline-grid; place-items: center; flex: none;
                    inline-size: var(--lds-size); block-size: var(--lds-size); color: #0a84ff; }
        .lds-spin svg { inline-size: 100%; block-size: 100%; }
        .lds-spin circle { fill: none; stroke: currentColor; stroke-width: 2.4; stroke-linecap: round;
                           stroke-dasharray: 10.5 89.5; stroke-dashoffset: -6;
                           animation: lds-fade .96s linear infinite;
                           animation-delay: calc(var(--lds-i) * -.12s); }
        @keyframes lds-fade { 0%, 100% { opacity: .12; } 42% { opacity: 1; } }
        .lds-card[data-state='done'] .lds-spin { color: #059669; }
        .lds-card h4 { margin: 0; font-size: .9rem; }
        .lds-meta { margin: 0; font-size: .78rem; color: #78716c; }
        .lds-replay { margin-block-start: .2rem; border: none; background: none; color: #0a84ff;
                      font: 600 .78rem/1 inherit; cursor: pointer; text-decoration: underline;
                      text-underline-offset: .2em; }
        @media (prefers-reduced-motion: reduce) { .lds-spin circle { animation: none; opacity: .85; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- ContactsSync.svelte — the segmented spin counting a real sync -->
        <script lang="ts">
          import { onDestroy, onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          let { total = 512, startFrom = 384 } = $props();
          let state = $state<'working' | 'done'>('working');
          let count = $state(startFrom);
          const ticks: number[] = [];

          const watch = (step: () => number, ms: number) => {
            const t = window.setInterval(() => {
              count = Math.min(total, step());
              if (count >= total) { clearInterval(t); state = 'done'; }
            }, ms);
            ticks.push(t);
          };

          onMount(() => watch(() => count + 3 + Math.floor(Math.random() * 4), 240));

          const replay = () => {
            if (state === 'working') return;
            state = 'working';
            count = 0;
            watch(() => count + 6 + Math.floor(Math.random() * 9), 110);
          };

          // Unmount clears every interval — no spinner spins behind the page.
          onDestroy(() => ticks.forEach(clearInterval));
        </script>

        <div class="lds-card" data-state={state}>
          <div class="lds-head">
            <span class="lds-spin" style="--lds-size: 26px" role="status" aria-label="Syncing">
              {#if state === 'working'}
                <svg viewBox="0 0 24 24" aria-hidden="true">
                  {#each Array(8) as _, i}
                    <circle cx="12" cy="12" r="8.5" pathLength="100"
                            transform={`rotate(${i * 45} 12 12)`} style={`--lds-i: ${i}`} />
                  {/each}
                </svg>
              {:else}
                <svg viewBox="0 0 24 24" aria-hidden="true">
                  <path fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"
                        stroke-linejoin="round" d="M4.5 12.5l5 5 10-11" />
                </svg>
              {/if}
            </span>
            <h4>Contacts · Outlook sync</h4>
          </div>
          <p class="lds-meta" aria-live="polite">
            {state === 'done' ? `Complete · ${total} contacts` : `${count} of ${total} contacts…`}
          </p>
          {#if state === 'done'}
            <button type="button" class="lds-replay" onclick={replay}>Run it again</button>
          {/if}
        </div>

        <style>
          .lds-card { display: grid; gap: .8rem; justify-items: start; padding: 1.2rem;
                      border: 1px solid #e7e5e4; border-radius: 1rem;
                      font-family: 'Inter', sans-serif; max-inline-size: 20rem; }
          .lds-head { display: flex; align-items: center; gap: .8rem; }
          .lds-spin { display: inline-grid; place-items: center; flex: none;
                      inline-size: var(--lds-size); block-size: var(--lds-size); color: #0a84ff; }
          .lds-spin svg { inline-size: 100%; block-size: 100%; }
          .lds-spin circle { fill: none; stroke: currentColor; stroke-width: 2.4; stroke-linecap: round;
                             stroke-dasharray: 10.5 89.5; stroke-dashoffset: -6;
                             animation: lds-fade .96s linear infinite;
                             animation-delay: calc(var(--lds-i) * -.12s); }
          @keyframes lds-fade { 0%, 100% { opacity: .12; } 42% { opacity: 1; } }
          .lds-card[data-state='done'] .lds-spin { color: #059669; }
          .lds-card h4 { margin: 0; font-size: .9rem; }
          .lds-meta { margin: 0; font-size: .78rem; color: #78716c; }
          .lds-replay { margin-block-start: .2rem; border: none; background: none; color: #0a84ff;
                        font: 600 .78rem/1 inherit; cursor: pointer; text-decoration: underline;
                        text-underline-offset: .2em; }
          @media (prefers-reduced-motion: reduce) { .lds-spin circle { animation: none; opacity: .85; } }
        </style>
        SVELTE,
        ],
    ],

    'counter-load' => [
        'title' => ['fa' => 'بارگذار درصدی', 'en' => 'Percent loader'],
        'icon' => 'trend-up',
        'oneLiner' => [
            'fa' => 'دایرهٔ پیشرفت با عددی که وسطش نرم می‌شمارد و لیبل مرحله که با آستانه عوض می‌شود — جمع‌آوری داده، فشرده‌سازی، بارگذاری. دکمهٔ لغو واقعاً لغو می‌کند: حلقه همان‌جا که بود می‌ماند، خاکستری می‌شود و «شروع دوباره» پیشنهاد می‌دهد؛ در ۱۰۰٪ فایل آماده و دکمهٔ دانلود بیدار می‌شود.',
            'en' => 'A progress ring with a number counting smoothly at its heart and a stage label trading on thresholds — gathering, compressing, uploading. The cancel button genuinely cancels: the ring stays exactly where it stopped, greys out and offers a restart; at 100% the file is ready and the download button wakes.',
        ],
        'js' => true,
        'docs' => 'https://circleloaders.dominikakissi.com',
        'props' => [
            ['name' => 'progress', 'type' => '%', 'default' => "'0 → 100'", 'note' => [
                'fa' => 'شمارش با ease-in-out روی requestAnimationFrame؛ عدد نرم می‌رود بالا، نه پرش‌پرش.',
                'en' => 'The count eases in-out on requestAnimationFrame; the number glides instead of stuttering.',
            ]],
            ['name' => 'stages', 'type' => 'array', 'default' => "'[{at: 0}, {at: 35}, {at: 75}]'", 'note' => [
                'fa' => 'لیبل مرحله با آستانهٔ درصد عوض می‌شود و فهرست مراحل همان لحظه تیک می‌خورد.',
                'en' => 'The stage label swaps on percent thresholds and the checklist ticks over in the same breath.',
            ]],
            ['name' => 'duration', 'type' => 'ms', 'default' => "'6400'", 'note' => [
                'fa' => 'مدت کل خروجی؛ طولانی‌تر از یک اسپینر کلاسیک چون کار واقعی پشتش هست.',
                'en' => 'The whole export’s length; longer than a classic spinner because real work sits behind it.',
            ]],
            ['name' => 'cancel', 'type' => 'action', 'default' => "'abort'", 'note' => [
                'fa' => 'لغو شمارش را همان‌جا قطع می‌کند؛ حلقه خاکستری سر جایش می‌ماند تا دوباره شروع کنید.',
                'en' => 'Cancel aborts the count mid-flight; the ring greys out in place until you start over.',
            ]],
            ['name' => 'ring', 'type' => 'geometry', 'default' => "'r 54 · dash 339.3'", 'note' => [
                'fa' => 'هندسهٔ حلقه؛ کمان با stroke-dashoffset درصد را دنبال می‌کند و سرِ آن گرد است.',
                'en' => 'The ring’s geometry; the arc follows the percent through stroke-dashoffset, round-capped.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="ldc-wrap" data-state="working" role="progressbar" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100">
            <svg viewBox="0 0 128 128" aria-hidden="true">
                <circle class="ldc-track" cx="64" cy="64" r="54"/>
                <circle class="ldc-arc" cx="64" cy="64" r="54" style="stroke-dashoffset: 128.9"/>
            </svg>
            <div class="ldc-center">
                <b>۶۲٪</b>
                <small>در حال فشرده‌سازی…</small>
            </div>
        </div>
        <button type="button" class="ldc-cancel">لغو</button>

        <style>
        .ldc-wrap { position: relative; display: grid; place-items: center; inline-size: 9rem; aspect-ratio: 1;
                    font: 600 1.5rem/1 'Inter', 'Vazirmatn', sans-serif; }
        .ldc-wrap svg { position: absolute; inset: 0; }
        .ldc-center { position: relative; display: grid; gap: .3rem; justify-items: center; }
        .ldc-center small { font-size: .62rem; color: #78716c; }
        .ldc-track, .ldc-arc { fill: none; }
        .ldc-track { stroke: #e7e5e4; stroke-width: 7; }
        .ldc-arc { stroke: #0f766e; stroke-width: 7; stroke-linecap: round;
                   stroke-dasharray: 339.3; transform: rotate(-90deg); transform-origin: 50% 50%; }
        .ldc-wrap[data-state='cancelled'] .ldc-arc { stroke: #a8a29e; }
        .ldc-cancel { margin-block-start: 1rem; padding: .55rem 1.2rem; border: 1px solid #e7e5e4;
                      border-radius: 999px; background: none; font: 600 .8rem/1 inherit; cursor: pointer; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="ldc-wrap" data-state="working" role="progressbar" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100">
        <svg viewBox="0 0 128 128" aria-hidden="true">
        <circle class="ldc-track" cx="64" cy="64" r="54"/>
        <circle class="ldc-arc" cx="64" cy="64" r="54" style="stroke-dashoffset: 128.9"/>
        </svg>
        <div class="ldc-center">
        <b>62%</b>
        <small>Compressing…</small>
        </div>
        </div>
        <button type="button" class="ldc-cancel">Cancel</button>

        <style>
        .ldc-wrap { position: relative; display: grid; place-items: center; inline-size: 9rem; aspect-ratio: 1;
                    font: 600 1.5rem/1 'Inter', 'Vazirmatn', sans-serif; }
        .ldc-wrap svg { position: absolute; inset: 0; }
        .ldc-center { position: relative; display: grid; gap: .3rem; justify-items: center; }
        .ldc-center small { font-size: .62rem; color: #78716c; }
        .ldc-track, .ldc-arc { fill: none; }
        .ldc-track { stroke: #e7e5e4; stroke-width: 7; }
        .ldc-arc { stroke: #0f766e; stroke-width: 7; stroke-linecap: round;
                   stroke-dasharray: 339.3; transform: rotate(-90deg); transform-origin: 50% 50%; }
        .ldc-wrap[data-state='cancelled'] .ldc-arc { stroke: #a8a29e; }
        .ldc-cancel { margin-block-start: 1rem; padding: .55rem 1.2rem; border: 1px solid #e7e5e4;
                      border-radius: 999px; background: none; font: 600 .8rem/1 inherit; cursor: pointer; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Analytics.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link in the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import ExportRing from '@/components/ExportRing';

        export default function Analytics() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <ExportRing workMs={6400} />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // ExportRing.jsx — percent loader with live stages and a cancel that cancels
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const C = 339.3; // 2π × r54
        const STAGES = [
          { at: 0, label: 'Gathering data' },
          { at: 35, label: 'Compressing' },
          { at: 75, label: 'Uploading' },
        ];

        export default function ExportRing({ workMs = 6400 }) {
          const [state, setState] = useState('idle'); // idle | working | cancelled | done
          const [p, setP] = useState(0);
          const raf = useRef(0);

          // Unmount aborts the frame loop — nothing counts on behind the page.
          useEffect(() => () => cancelAnimationFrame(raf.current), []);

          const run = () => {
            if (state === 'working') return;
            setState('working');
            setP(0);
            const t0 = performance.now();
            const tick = (now) => {
              const t = Math.min(1, (now - t0) / workMs);
              const eased = t < 0.5 ? 2 * t * t : 1 - Math.pow(-2 * t + 2, 2) / 2;
              setP(eased * 100);
              if (t >= 1) { setState('done'); return; }
              raf.current = requestAnimationFrame(tick);
            };
            raf.current = requestAnimationFrame(tick);
          };

          const cancel = () => {
            if (state !== 'working') return;
            cancelAnimationFrame(raf.current);
            setState('cancelled');
          };

          const stageIdx = Math.max(0, STAGES.findLastIndex((s) => p >= s.at));

          return (
            <>
              <style>{`
                .ldc-card { display: grid; gap: 1.2rem; justify-items: center; padding: 1.4rem;
                            border: 1px solid #e7e5e4; border-radius: 1rem; font-family: 'Inter', sans-serif;
                            max-inline-size: 21rem; }
                .ldc-wrap { position: relative; display: grid; place-items: center; inline-size: 9rem; aspect-ratio: 1;
                            font: 600 1.5rem/1 inherit; }
                .ldc-wrap svg { position: absolute; inset: 0; }
                .ldc-center { position: relative; display: grid; gap: .3rem; justify-items: center; }
                .ldc-center small { font-size: .62rem; color: #78716c; }
                .ldc-track, .ldc-arc { fill: none; }
                .ldc-track { stroke: #e7e5e4; stroke-width: 7; }
                .ldc-arc { stroke: #0f766e; stroke-width: 7; stroke-linecap: round; stroke-dasharray: 339.3;
                           transform: rotate(-90deg); transform-origin: 50% 50%; }
                .ldc-wrap[data-state='cancelled'] .ldc-arc { stroke: #a8a29e; }
                .ldc-stages { display: grid; gap: .4rem; justify-items: start; font-size: .8rem; }
                .ldc-stage { display: flex; gap: .5rem; align-items: center; color: #78716c; }
                .ldc-stage[data-s='done'] { color: #0f766e; }
                .ldc-stage[data-s='active'] { color: #1c1917; font-weight: 600; }
                .ldc-stage i { inline-size: .55rem; aspect-ratio: 1; border-radius: 50%; background: currentColor; }
                .ldc-foot { display: flex; gap: .7rem; }
                .ldc-btn { block-size: 2.4rem; padding-inline: 1.15rem; border: none; border-radius: 999px;
                           font: 600 .84rem/1 inherit; cursor: pointer; }
                .ldc-btn[data-kind='cancel'] { border: 1px solid #e7e5e4; background: none; color: #1c1917; }
                .ldc-btn[data-kind='go'] { background: #0f766e; color: #fff; }
                .ldc-btn[data-kind='idle'] { background: #0f766e; color: #fff; }
              `}</style>
              <div className="ldc-card">
                <div className="ldc-wrap" data-state={state} role="progressbar"
                     aria-valuenow={Math.round(p)} aria-valuemin={0} aria-valuemax={100">
                  <svg viewBox="0 0 128 128" aria-hidden="true">
                    <circle className="ldc-track" cx="64" cy="64" r="54" />
                    <circle className="ldc-arc" cx="64" cy="64" r="54"
                            style={{ strokeDashoffset: C * (1 - p / 100) }} />
                  </svg>
                  <div className="ldc-center">
                    <b>{Math.round(p)}%</b>
                    <small aria-live="polite">
                      {state === 'done' ? 'Ready' : state === 'cancelled' ? 'Cancelled' : `${STAGES[stageIdx].label}…`}
                    </small>
                  </div>
                </div>
                <div className="ldc-stages" aria-hidden="true">
                  {STAGES.map((s, i) => (
                    <span key={s.at} className="ldc-stage"
                          data-s={state === 'done' || i < stageIdx ? 'done' : i === stageIdx && state === 'working' ? 'active' : ''}>
                      <i />{s.label}
                    </span>
                  ))}
                </div>
                <div className="ldc-foot">
                  {state === 'working' && (
                    <button type="button" className="ldc-btn" data-kind="cancel" onClick={cancel}>Cancel</button>
                  )}
                  {(state === 'idle' || state === 'cancelled') && (
                    <button type="button" className="ldc-btn" data-kind="idle" onClick={run}>Start export</button>
                  )}
                  {state === 'cancelled' && (
                    <span style={{ alignSelf: 'center', fontSize: '.75rem', color: '#78716c' }}>
                      Stopped at {Math.round(p)}%
                    </span>
                  )}
                  {state === 'done' && (
                    <>
                      <button type="button" className="ldc-btn" data-kind="go">Download PDF</button>
                      <button type="button" className="ldc-btn" data-kind="cancel" onClick={run}>Export again</button>
                    </>
                  )}
                </div>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- ExportRing.vue — percent loader with live stages and a cancel that cancels -->
        <script setup lang="ts">
        import { computed, onBeforeUnmount, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const props = defineProps<{ workMs?: number }>();
        const C = 339.3; // 2π × r54
        const stages = [
          { at: 0, label: 'Gathering data' },
          { at: 35, label: 'Compressing' },
          { at: 75, label: 'Uploading' },
        ];

        const state = ref<'idle' | 'working' | 'cancelled' | 'done'>('idle');
        const p = ref(0);
        let raf = 0;

        const stageIdx = computed(() => Math.max(0, stages.reduce((acc, s, i) => (p.value >= s.at ? i : acc), 0)));
        const stage = (i: number) =>
          state.value === 'done' || i < stageIdx.value ? 'done'
          : i === stageIdx.value && state.value === 'working' ? 'active' : '';

        const run = () => {
          if (state.value === 'working') return;
          state.value = 'working';
          p.value = 0;
          const t0 = performance.now();
          const tick = (now: number) => {
            const t = Math.min(1, (now - t0) / (props.workMs ?? 6400));
            const eased = t < 0.5 ? 2 * t * t : 1 - Math.pow(-2 * t + 2, 2) / 2;
            p.value = eased * 100;
            if (t >= 1) { state.value = 'done'; return; }
            raf = requestAnimationFrame(tick);
          };
          raf = requestAnimationFrame(tick);
        };

        const cancel = () => {
          if (state.value !== 'working') return;
          cancelAnimationFrame(raf);
          state.value = 'cancelled';
        };

        // Unmount aborts the frame loop — nothing counts on behind the page.
        onBeforeUnmount(() => cancelAnimationFrame(raf));
        </script>

        <template>
          <div class="ldc-card">
            <div class="ldc-wrap" :data-state="state" role="progressbar"
                 :aria-valuenow="Math.round(p)" aria-valuemin="0" aria-valuemax="100">
              <svg viewBox="0 0 128 128" aria-hidden="true">
                <circle class="ldc-track" cx="64" cy="64" r="54" />
                <circle class="ldc-arc" cx="64" cy="64" r="54" :style="{ strokeDashoffset: C * (1 - p / 100) }" />
              </svg>
              <div class="ldc-center">
                <b>{{ Math.round(p) }}%</b>
                <small aria-live="polite">
                  {{ state === 'done' ? 'Ready' : state === 'cancelled' ? 'Cancelled' : `${stages[stageIdx].label}…` }}
                </small>
              </div>
            </div>
            <div class="ldc-stages" aria-hidden="true">
              <span v-for="(s, i) in stages" :key="s.at" class="ldc-stage" :data-s="stage(i)"><i />{{ s.label }}</span>
            </div>
            <div class="ldc-foot">
              <button v-if="state === 'working'" type="button" class="ldc-btn" data-kind="cancel" @click="cancel">Cancel</button>
              <button v-if="state === 'idle' || state === 'cancelled'" type="button" class="ldc-btn" data-kind="idle" @click="run">
                Start export
              </button>
              <span v-if="state === 'cancelled'" class="ldc-stopped">Stopped at {{ Math.round(p) }}%</span>
              <template v-if="state === 'done'">
                <button type="button" class="ldc-btn" data-kind="go">Download PDF</button>
                <button type="button" class="ldc-btn" data-kind="cancel" @click="run">Export again</button>
              </template>
            </div>
          </div>
        </template>

        <style scoped>
        .ldc-card { display: grid; gap: 1.2rem; justify-items: center; padding: 1.4rem;
                    border: 1px solid #e7e5e4; border-radius: 1rem; font-family: 'Inter', sans-serif;
                    max-inline-size: 21rem; }
        .ldc-wrap { position: relative; display: grid; place-items: center; inline-size: 9rem; aspect-ratio: 1;
                    font: 600 1.5rem/1 inherit; }
        .ldc-wrap svg { position: absolute; inset: 0; }
        .ldc-center { position: relative; display: grid; gap: .3rem; justify-items: center; }
        .ldc-center small { font-size: .62rem; color: #78716c; }
        .ldc-track, .ldc-arc { fill: none; }
        .ldc-track { stroke: #e7e5e4; stroke-width: 7; }
        .ldc-arc { stroke: #0f766e; stroke-width: 7; stroke-linecap: round; stroke-dasharray: 339.3;
                   transform: rotate(-90deg); transform-origin: 50% 50%; }
        .ldc-wrap[data-state='cancelled'] .ldc-arc { stroke: #a8a29e; }
        .ldc-stages { display: grid; gap: .4rem; justify-items: start; font-size: .8rem; }
        .ldc-stage { display: flex; gap: .5rem; align-items: center; color: #78716c; }
        .ldc-stage[data-s='done'] { color: #0f766e; }
        .ldc-stage[data-s='active'] { color: #1c1917; font-weight: 600; }
        .ldc-stage i { inline-size: .55rem; aspect-ratio: 1; border-radius: 50%; background: currentColor; }
        .ldc-foot { display: flex; gap: .7rem; }
        .ldc-stopped { align-self: center; font-size: .75rem; color: #78716c; }
        .ldc-btn { block-size: 2.4rem; padding-inline: 1.15rem; border: none; border-radius: 999px;
                   font: 600 .84rem/1 inherit; cursor: pointer; }
        .ldc-btn[data-kind='cancel'] { border: 1px solid #e7e5e4; background: none; color: #1c1917; }
        .ldc-btn[data-kind='go'], .ldc-btn[data-kind='idle'] { background: #0f766e; color: #fff; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- ExportRing.svelte — percent loader with live stages and a cancel that cancels -->
        <script lang="ts">
          import { onDestroy } from 'svelte';
          import '@nabuxai/ui-core/css';

          let { workMs = 6400 } = $props();
          const C = 339.3; // 2π × r54
          const stages = [
            { at: 0, label: 'Gathering data' },
            { at: 35, label: 'Compressing' },
            { at: 75, label: 'Uploading' },
          ];

          let state = $state<'idle' | 'working' | 'cancelled' | 'done'>('idle');
          let p = $state(0);
          let raf = 0;

          const stageIdx = $derived(Math.max(0, stages.reduce((acc, s, i) => (p >= s.at ? i : acc), 0)));
          const stage = (i: number) =>
            state === 'done' || i < stageIdx ? 'done'
            : i === stageIdx && state === 'working' ? 'active' : '';

          const run = () => {
            if (state === 'working') return;
            state = 'working';
            p = 0;
            const t0 = performance.now();
            const tick = (now: number) => {
              const t = Math.min(1, (now - t0) / workMs);
              const eased = t < 0.5 ? 2 * t * t : 1 - Math.pow(-2 * t + 2, 2) / 2;
              p = eased * 100;
              if (t >= 1) { state = 'done'; return; }
              raf = requestAnimationFrame(tick);
            };
            raf = requestAnimationFrame(tick);
          };

          const cancel = () => {
            if (state !== 'working') return;
            cancelAnimationFrame(raf);
            state = 'cancelled';
          };

          // Unmount aborts the frame loop — nothing counts on behind the page.
          onDestroy(() => cancelAnimationFrame(raf));
        </script>

        <div class="ldc-card">
          <div class="ldc-wrap" data-state={state} role="progressbar"
               aria-valuenow={Math.round(p)} aria-valuemin="0" aria-valuemax="100">
            <svg viewBox="0 0 128 128" aria-hidden="true">
              <circle class="ldc-track" cx="64" cy="64" r="54" />
              <circle class="ldc-arc" cx="64" cy="64" r="54" style={`stroke-dashoffset: ${C * (1 - p / 100)}`} />
            </svg>
            <div class="ldc-center">
              <b>{Math.round(p)}%</b>
              <small aria-live="polite">
                {state === 'done' ? 'Ready' : state === 'cancelled' ? 'Cancelled' : `${stages[stageIdx].label}…`}
              </small>
            </div>
          </div>
          <div class="ldc-stages" aria-hidden="true">
            {#each stages as s, i (s.at)}
              <span class="ldc-stage" data-s={stage(i)}><i />{s.label}</span>
            {/each}
          </div>
          <div class="ldc-foot">
            {#if state === 'working'}
              <button type="button" class="ldc-btn" data-kind="cancel" onclick={cancel}>Cancel</button>
            {/if}
            {#if state === 'idle' || state === 'cancelled'}
              <button type="button" class="ldc-btn" data-kind="idle" onclick={run}>Start export</button>
            {/if}
            {#if state === 'cancelled'}
              <span class="ldc-stopped">Stopped at {Math.round(p)}%</span>
            {/if}
            {#if state === 'done'}
              <button type="button" class="ldc-btn" data-kind="go">Download PDF</button>
              <button type="button" class="ldc-btn" data-kind="cancel" onclick={run}>Export again</button>
            {/if}
          </div>
        </div>

        <style>
          .ldc-card { display: grid; gap: 1.2rem; justify-items: center; padding: 1.4rem;
                      border: 1px solid #e7e5e4; border-radius: 1rem; font-family: 'Inter', sans-serif;
                      max-inline-size: 21rem; }
          .ldc-wrap { position: relative; display: grid; place-items: center; inline-size: 9rem; aspect-ratio: 1;
                      font: 600 1.5rem/1 inherit; }
          .ldc-wrap svg { position: absolute; inset: 0; }
          .ldc-center { position: relative; display: grid; gap: .3rem; justify-items: center; }
          .ldc-center small { font-size: .62rem; color: #78716c; }
          .ldc-track, .ldc-arc { fill: none; }
          .ldc-track { stroke: #e7e5e4; stroke-width: 7; }
          .ldc-arc { stroke: #0f766e; stroke-width: 7; stroke-linecap: round; stroke-dasharray: 339.3;
                     transform: rotate(-90deg); transform-origin: 50% 50%; }
          .ldc-wrap[data-state='cancelled'] .ldc-arc { stroke: #a8a29e; }
          .ldc-stages { display: grid; gap: .4rem; justify-items: start; font-size: .8rem; }
          .ldc-stage { display: flex; gap: .5rem; align-items: center; color: #78716c; }
          .ldc-stage[data-s='done'] { color: #0f766e; }
          .ldc-stage[data-s='active'] { color: #1c1917; font-weight: 600; }
          .ldc-stage i { inline-size: .55rem; aspect-ratio: 1; border-radius: 50%; background: currentColor; }
          .ldc-foot { display: flex; gap: .7rem; flex-wrap: wrap; justify-content: center; }
          .ldc-stopped { align-self: center; font-size: .75rem; color: #78716c; }
          .ldc-btn { block-size: 2.4rem; padding-inline: 1.15rem; border: none; border-radius: 999px;
                     font: 600 .84rem/1 inherit; cursor: pointer; }
          .ldc-btn[data-kind='cancel'] { border: 1px solid #e7e5e4; background: none; color: #1c1917; }
          .ldc-btn[data-kind='go'], .ldc-btn[data-kind='idle'] { background: #0f766e; color: #fff; }
        </style>
        SVELTE,
        ],
    ],

    'liquid-fill' => [
        'title' => ['fa' => 'پُرشدن مایع', 'en' => 'Liquid fill'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'مخزن شیشه‌ای که مایع موج‌دار داخلش بالا می‌آید تا سقف درصد؛ موج با دو لایهٔ هم‌فرکانسِ بی‌ساز می‌تپد — لایهٔ عقب کم‌رنگ‌تر و نصف‌موج جابه‌جا. عدد وسط همراه مایع می‌شمارد و در انتها برچسب «آماده شد» از دل مخزن بیرون می‌آید؛ خواهر کوچکش خطای «فضای مقصد پر شد» را منجمد نشان می‌دهد.',
            'en' => 'A glass tank filling with rippling liquid up to its percent cap; the wave beats on two same-frequency layers out of phase — the back one paler, shifted half a wavelength. The number counts with the liquid and at the end a ready label rises out of the tank; its little sister shows the disk-full failure frozen mid-fill.',
        ],
        'js' => true,
        'docs' => 'https://circleloaders.dominikakissi.com',
        'props' => [
            ['name' => 'fill', 'type' => '%', 'default' => "'0 → 100'", 'note' => [
                'fa' => 'سطح مایع با یک translate عمودی بالا می‌رود؛ clipPath دایره‌ای مایع را داخل مخزن نگه می‌دارد.',
                'en' => 'The liquid level rises through one vertical translate; a circular clipPath keeps it inside the tank.',
            ]],
            ['name' => 'waves', 'type' => 'layers', 'default' => "'2'", 'note' => [
                'fa' => 'دو لایهٔ هم‌فرکانس با فاز نصف‌موج؛ عقب کم‌رنگ و کم‌دامنه، جلو با گرادیان — عمق بدون هزینه.',
                'en' => 'Two same-frequency layers half a wavelength out of phase; the back one pale and shallow, the front gradient-filled — depth for free.',
            ]],
            ['name' => 'drift', 'type' => 's', 'default' => "'3.2'", 'note' => [
                'fa' => 'جابه‌جایی افقی بی‌درز به اندازهٔ یک طول موج؛ مسیر موج ۴۰۰ واحدی است تا هیچ درزی دیده نشود.',
                'en' => 'A seamless horizontal drift of exactly one wavelength; the path spans 400 units so no seam ever shows.',
            ]],
            ['name' => 'amplitude', 'type' => 'px', 'default' => "'9 · 6'", 'note' => [
                'fa' => 'دامنهٔ موج جلو و عقب؛ عدد وسط وقتی مایع به آن می‌رسد رنگش را عوض می‌کند تا خوانا بماند.',
                'en' => 'Front and back wave amplitudes; the centre number recolours the moment the liquid reaches it, so it stays readable.',
            ]],
            ['name' => 'ready', 'type' => 'state', 'default' => "'label'", 'note' => [
                'fa' => 'در ۱۰۰٪ عدد جای‌اش را به تیک و برچسب «آماده شد» می‌دهد؛ در خطا موج می‌ایستد و مایع خاکستری می‌شود.',
                'en' => 'At 100% the number yields to a tick and the ready label; on error the wave stops and the liquid turns grey.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="ldl-tank" style="--ldl-level: 118px">
            <svg viewBox="0 0 200 200" aria-hidden="true">
                <defs>
                    <clipPath id="ldl-clip"><circle cx="100" cy="100" r="88"/></clipPath>
                </defs>
                <circle class="ldl-glass" cx="100" cy="100" r="88"/>
                <g clip-path="url(#ldl-clip)">
                    <g class="ldl-level">
                        <path class="ldl-wave ldl-wave-b" d="M0 0 Q25 -6 50 0 T100 0 T150 0 T200 0 T250 0 T300 0 T350 0 T400 0 V220 H0 Z"/>
                        <path class="ldl-wave ldl-wave-f" d="M0 0 Q25 -9 50 0 T100 0 T150 0 T200 0 T250 0 T300 0 T350 0 T400 0 V220 H0 Z"/>
                    </g>
                </g>
                <circle class="ldl-rim" cx="100" cy="100" r="88"/>
            </svg>
            <b>۴۲٪</b>
        </div>

        <style>
        .ldl-tank { position: relative; display: grid; place-items: center; inline-size: 10rem; aspect-ratio: 1;
                    font: 700 1.5rem/1 'Inter', 'Vazirmatn', sans-serif; }
        .ldl-tank svg { position: absolute; inset: 0; }
        .ldl-glass, .ldl-rim { fill: none; }
        .ldl-glass { stroke: #e7e5e4; stroke-width: 3; }
        .ldl-rim { stroke: #d6d3d1; stroke-width: 1.5; }
        .ldl-level { translate: 0 var(--ldl-level); }
        .ldl-wave { animation: ldl-drift 3.2s linear infinite; }
        .ldl-wave-b { fill: #99f6e4; opacity: .55; animation-delay: -1.6s; }
        .ldl-wave-f { fill: #0d9488; }
        @keyframes ldl-drift { to { translate: -100px 0; } }
        @media (prefers-reduced-motion: reduce) { .ldl-wave { animation: none; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="ldl-tank" style="--ldl-level: 118px">
        <svg viewBox="0 0 200 200" aria-hidden="true">
        <defs>
        <clipPath id="ldl-clip"><circle cx="100" cy="100" r="88"/></clipPath>
        </defs>
        <circle class="ldl-glass" cx="100" cy="100" r="88"/>
        <g clip-path="url(#ldl-clip)">
        <g class="ldl-level">
        <path class="ldl-wave ldl-wave-b" d="M0 0 Q25 -6 50 0 T100 0 T150 0 T200 0 T250 0 T300 0 T350 0 T400 0 V220 H0 Z"/>
        <path class="ldl-wave ldl-wave-f" d="M0 0 Q25 -9 50 0 T100 0 T150 0 T200 0 T250 0 T300 0 T350 0 T400 0 V220 H0 Z"/>
        </g>
        </g>
        <circle class="ldl-rim" cx="100" cy="100" r="88"/>
        </svg>
        <b>42%</b>
        </div>

        <style>
        .ldl-tank { position: relative; display: grid; place-items: center; inline-size: 10rem; aspect-ratio: 1;
                    font: 700 1.5rem/1 'Inter', 'Vazirmatn', sans-serif; }
        .ldl-tank svg { position: absolute; inset: 0; }
        .ldl-glass, .ldl-rim { fill: none; }
        .ldl-glass { stroke: #e7e5e4; stroke-width: 3; }
        .ldl-rim { stroke: #d6d3d1; stroke-width: 1.5; }
        .ldl-level { translate: 0 var(--ldl-level); }
        .ldl-wave { animation: ldl-drift 3.2s linear infinite; }
        .ldl-wave-b { fill: #99f6e4; opacity: .55; animation-delay: -1.6s; }
        .ldl-wave-f { fill: #0d9488; }
        @keyframes ldl-drift { to { translate: -100px 0; } }
        @media (prefers-reduced-motion: reduce) { .ldl-wave { animation: none; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Migration.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link in the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import MigrationTank from '@/components/MigrationTank';

        export default function Migration() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <MigrationTank workMs={5200} />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // MigrationTank.jsx — a liquid tank that fills to its ready label
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const WAVE_B = 'M0 0 Q25 -6 50 0 T100 0 T150 0 T200 0 T250 0 T300 0 T350 0 T400 0 V220 H0 Z';
        const WAVE_F = 'M0 0 Q25 -9 50 0 T100 0 T150 0 T200 0 T250 0 T300 0 T350 0 T400 0 V220 H0 Z';

        export default function MigrationTank({ workMs = 5200 }) {
          const [state, setState] = useState('working'); // working | done
          const [p, setP] = useState(0);
          const raf = useRef(0);

          // Unmount aborts the frame loop — no tank fills behind the page.
          useEffect(() => () => cancelAnimationFrame(raf.current), []);

          const run = () => {
            if (state === 'working') return;
            setState('working');
            setP(0);
            const t0 = performance.now();
            const tick = (now) => {
              const t = Math.min(1, (now - t0) / workMs);
              setP((1 - Math.pow(1 - t, 3)) * 100);
              if (t >= 1) { setState('done'); return; }
              raf.current = requestAnimationFrame(tick);
            };
            raf.current = requestAnimationFrame(tick);
          };

          const level = 200 - (p / 100) * 188;

          return (
            <>
              <style>{`
                .ldl-card { display: grid; gap: 1.1rem; justify-items: center; padding: 1.4rem;
                            border: 1px solid #e7e5e4; border-radius: 1rem; font-family: 'Inter', sans-serif;
                            max-inline-size: 20rem; }
                .ldl-tank { position: relative; display: grid; place-items: center; inline-size: 10rem; aspect-ratio: 1;
                            font: 700 1.5rem/1 inherit; }
                .ldl-tank svg { position: absolute; inset: 0; }
                .ldl-read { position: relative; display: grid; gap: .3rem; justify-items: center; }
                .ldl-read small { font-size: .62rem; color: #78716c; }
                .ldl-read[data-wet='true'] { color: #fff; text-shadow: 0 1px 4px rgb(0 0 0 / .35); }
                .ldl-read[data-wet='true'] small { color: #fff; }
                .ldl-glass, .ldl-rim { fill: none; }
                .ldl-glass { stroke: #e7e5e4; stroke-width: 3; }
                .ldl-rim { stroke: #d6d3d1; stroke-width: 1.5; }
                .ldl-level { translate: 0 var(--ldl-level); }
                .ldl-wave { animation: ldl-drift 3.2s linear infinite; }
                .ldl-wave-b { fill: #99f6e4; opacity: .55; animation-delay: -1.6s; }
                .ldl-wave-f { fill: #0d9488; }
                @keyframes ldl-drift { to { translate: -100px 0; } }
                .ldl-replay { border: none; background: none; color: #0d9488; font: 600 .78rem/1 inherit;
                              cursor: pointer; text-decoration: underline; text-underline-offset: .2em; }
                @media (prefers-reduced-motion: reduce) { .ldl-wave { animation: none; } }
              `}</style>
              <div className="ldl-card">
                <div className="ldl-tank" style={{ ['--ldl-level' as string]: `${level}px` }}
                     role="progressbar" aria-valuenow={Math.round(p)} aria-valuemin={0} aria-valuemax={100}
                     aria-label="Migration progress">
                  <svg viewBox="0 0 200 200" aria-hidden="true">
                    <defs>
                      <clipPath id="ldl-clip"><circle cx="100" cy="100" r="88" /></clipPath>
                    </defs>
                    <circle className="ldl-glass" cx="100" cy="100" r="88" />
                    <g clipPath="url(#ldl-clip)">
                      <g className="ldl-level">
                        <path className="ldl-wave ldl-wave-b" d={WAVE_B} />
                        <path className="ldl-wave ldl-wave-f" d={WAVE_F} />
                      </g>
                    </g>
                    <circle className="ldl-rim" cx="100" cy="100" r="88" />
                  </svg>
                  <div className="ldl-read" data-wet={p > 53}>
                    <b>{Math.round(p)}%</b>
                    <small aria-live="polite">{state === 'done' ? 'Ready' : 'Migrating…'}</small>
                  </div>
                </div>
                {state === 'done' && (
                  <button type="button" className="ldl-replay" onClick={run}>Run it again</button>
                )}
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- MigrationTank.vue — a liquid tank that fills to its ready label -->
        <script setup lang="ts">
        import { computed, onBeforeUnmount, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const props = defineProps<{ workMs?: number }>();
        const waveB = 'M0 0 Q25 -6 50 0 T100 0 T150 0 T200 0 T250 0 T300 0 T350 0 T400 0 V220 H0 Z';
        const waveF = 'M0 0 Q25 -9 50 0 T100 0 T150 0 T200 0 T250 0 T300 0 T350 0 T400 0 V220 H0 Z';

        const state = ref<'working' | 'done'>('working');
        const p = ref(0);
        let raf = 0;

        const level = computed(() => 200 - (p.value / 100) * 188);

        const run = () => {
          if (state.value === 'working') return;
          state.value = 'working';
          p.value = 0;
          const t0 = performance.now();
          const tick = (now: number) => {
            const t = Math.min(1, (now - t0) / (props.workMs ?? 5200));
            p.value = (1 - Math.pow(1 - t, 3)) * 100;
            if (t >= 1) { state.value = 'done'; return; }
            raf = requestAnimationFrame(tick);
          };
          raf = requestAnimationFrame(tick);
        };

        // Unmount aborts the frame loop — no tank fills behind the page.
        onBeforeUnmount(() => cancelAnimationFrame(raf));
        </script>

        <template>
          <div class="ldl-card">
            <div class="ldl-tank" :style="{ '--ldl-level': `${level}px` }"
                 role="progressbar" :aria-valuenow="Math.round(p)" aria-valuemin="0" aria-valuemax="100"
                 aria-label="Migration progress">
              <svg viewBox="0 0 200 200" aria-hidden="true">
                <defs>
                  <clipPath id="ldl-clip"><circle cx="100" cy="100" r="88" /></clipPath>
                </defs>
                <circle class="ldl-glass" cx="100" cy="100" r="88" />
                <g clip-path="url(#ldl-clip)">
                  <g class="ldl-level">
                    <path class="ldl-wave ldl-wave-b" :d="waveB" />
                    <path class="ldl-wave ldl-wave-f" :d="waveF" />
                  </g>
                </g>
                <circle class="ldl-rim" cx="100" cy="100" r="88" />
              </svg>
              <div class="ldl-read" :data-wet="p > 53">
                <b>{{ Math.round(p) }}%</b>
                <small aria-live="polite">{{ state === 'done' ? 'Ready' : 'Migrating…' }}</small>
              </div>
            </div>
            <button v-if="state === 'done'" type="button" class="ldl-replay" @click="run">Run it again</button>
          </div>
        </template>

        <style scoped>
        .ldl-card { display: grid; gap: 1.1rem; justify-items: center; padding: 1.4rem;
                    border: 1px solid #e7e5e4; border-radius: 1rem; font-family: 'Inter', sans-serif;
                    max-inline-size: 20rem; }
        .ldl-tank { position: relative; display: grid; place-items: center; inline-size: 10rem; aspect-ratio: 1;
                    font: 700 1.5rem/1 inherit; }
        .ldl-tank svg { position: absolute; inset: 0; }
        .ldl-read { position: relative; display: grid; gap: .3rem; justify-items: center; }
        .ldl-read small { font-size: .62rem; color: #78716c; }
        .ldl-read[data-wet='true'] { color: #fff; text-shadow: 0 1px 4px rgb(0 0 0 / .35); }
        .ldl-read[data-wet='true'] small { color: #fff; }
        .ldl-glass, .ldl-rim { fill: none; }
        .ldl-glass { stroke: #e7e5e4; stroke-width: 3; }
        .ldl-rim { stroke: #d6d3d1; stroke-width: 1.5; }
        .ldl-level { translate: 0 var(--ldl-level); }
        .ldl-wave { animation: ldl-drift 3.2s linear infinite; }
        .ldl-wave-b { fill: #99f6e4; opacity: .55; animation-delay: -1.6s; }
        .ldl-wave-f { fill: #0d9488; }
        @keyframes ldl-drift { to { translate: -100px 0; } }
        .ldl-replay { border: none; background: none; color: #0d9488; font: 600 .78rem/1 inherit;
                      cursor: pointer; text-decoration: underline; text-underline-offset: .2em; }
        @media (prefers-reduced-motion: reduce) { .ldl-wave { animation: none; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- MigrationTank.svelte — a liquid tank that fills to its ready label -->
        <script lang="ts">
          import { onDestroy } from 'svelte';
          import '@nabuxai/ui-core/css';

          let { workMs = 5200 } = $props();
          const waveB = 'M0 0 Q25 -6 50 0 T100 0 T150 0 T200 0 T250 0 T300 0 T350 0 T400 0 V220 H0 Z';
          const waveF = 'M0 0 Q25 -9 50 0 T100 0 T150 0 T200 0 T250 0 T300 0 T350 0 T400 0 V220 H0 Z';

          let state = $state<'working' | 'done'>('working');
          let p = $state(0);
          let raf = 0;

          const level = $derived(200 - (p / 100) * 188);

          const run = () => {
            if (state === 'working') return;
            state = 'working';
            p = 0;
            const t0 = performance.now();
            const tick = (now: number) => {
              const t = Math.min(1, (now - t0) / workMs);
              p = (1 - Math.pow(1 - t, 3)) * 100;
              if (t >= 1) { state = 'done'; return; }
              raf = requestAnimationFrame(tick);
            };
            raf = requestAnimationFrame(tick);
          };

          // Unmount aborts the frame loop — no tank fills behind the page.
          onDestroy(() => cancelAnimationFrame(raf));
        </script>

        <div class="ldl-card">
          <div class="ldl-tank" style={`--ldl-level: ${level}px`}
               role="progressbar" aria-valuenow={Math.round(p)} aria-valuemin="0" aria-valuemax="100"
               aria-label="Migration progress">
            <svg viewBox="0 0 200 200" aria-hidden="true">
              <defs>
                <clipPath id="ldl-clip"><circle cx="100" cy="100" r="88" /></clipPath>
              </defs>
              <circle class="ldl-glass" cx="100" cy="100" r="88" />
              <g clip-path="url(#ldl-clip)">
                <g class="ldl-level">
                  <path class="ldl-wave ldl-wave-b" d={waveB} />
                  <path class="ldl-wave ldl-wave-f" d={waveF} />
                </g>
              </g>
              <circle class="ldl-rim" cx="100" cy="100" r="88" />
            </svg>
            <div class="ldl-read" data-wet={p > 53}>
              <b>{Math.round(p)}%</b>
              <small aria-live="polite">{state === 'done' ? 'Ready' : 'Migrating…'}</small>
            </div>
          </div>
          {#if state === 'done'}
            <button type="button" class="ldl-replay" onclick={run}>Run it again</button>
          {/if}
        </div>

        <style>
          .ldl-card { display: grid; gap: 1.1rem; justify-items: center; padding: 1.4rem;
                      border: 1px solid #e7e5e4; border-radius: 1rem; font-family: 'Inter', sans-serif;
                      max-inline-size: 20rem; }
          .ldl-tank { position: relative; display: grid; place-items: center; inline-size: 10rem; aspect-ratio: 1;
                      font: 700 1.5rem/1 inherit; }
          .ldl-tank svg { position: absolute; inset: 0; }
          .ldl-read { position: relative; display: grid; gap: .3rem; justify-items: center; }
          .ldl-read small { font-size: .62rem; color: #78716c; }
          .ldl-read[data-wet='true'] { color: #fff; text-shadow: 0 1px 4px rgb(0 0 0 / .35); }
          .ldl-read[data-wet='true'] small { color: #fff; }
          .ldl-glass, .ldl-rim { fill: none; }
          .ldl-glass { stroke: #e7e5e4; stroke-width: 3; }
          .ldl-rim { stroke: #d6d3d1; stroke-width: 1.5; }
          .ldl-level { translate: 0 var(--ldl-level); }
          .ldl-wave { animation: ldl-drift 3.2s linear infinite; }
          .ldl-wave-b { fill: #99f6e4; opacity: .55; animation-delay: -1.6s; }
          .ldl-wave-f { fill: #0d9488; }
          @keyframes ldl-drift { to { translate: -100px 0; } }
          .ldl-replay { border: none; background: none; color: #0d9488; font: 600 .78rem/1 inherit;
                        cursor: pointer; text-decoration: underline; text-underline-offset: .2em; }
          @media (prefers-reduced-motion: reduce) { .ldl-wave { animation: none; } }
        </style>
        SVELTE,
        ],
    ],
];
