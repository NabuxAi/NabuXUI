<?php

/**
 * Demo manifest of the "motion-fx" group — interactive motion in the shared
 * language of aceternity, magicui, motion-primitives, uiverse and anime.js:
 * the trailing custom cursor with its states, the spotlight grid that lights
 * the card under your hand, animated connection beams between service nodes,
 * the marquee wall of testimonials, and the timeline scene that plays a
 * staggered product intro. Scenarios live at
 * resources/views/demos/components/motion-fx/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'حرکت‌های تعاملی', 'en' => 'Motion FX'],

    'cursor-fx' => [
        'title' => ['fa' => 'کرسر سفارشی', 'en' => 'Custom cursor'],
        'icon' => 'wand',
        'oneLiner' => [
            'fa' => 'حلقه‌ای که با تأخیر نرم دنبال موس می‌آید و حالت می‌گیرد: روی لینک بزرگ و نرم می‌شود، روی متن به میلهٔ باریک لو می‌رود و روی دکمه برچسب می‌گیرد؛ نقطهٔ مرکزی بی‌تأخیر روی موس می‌نشیند و با لمس یا بیرون رفتن موس همه‌چیز محو می‌شود.',
            'en' => 'A ring that trails the pointer with soft lag and changes character: it grows soft over links, narrows to a bar over text and picks up a label over buttons; a centre dot sits on the pointer with zero lag, and on touch — or when the mouse leaves — everything fades away.',
        ],
        'js' => true,
        'docs' => 'https://kinetics.colorion.co',
        'props' => [
            ['name' => 'data-cursor', 'type' => 'link · text · play', 'default' => "''", 'note' => [
                'fa' => 'حالت حلقه را المان زیر موس تعیین می‌کند: link بزرگ و پررنگ، text میلهٔ باریک، play برچسب‌دار؛ بی‌هر دو، حلقهٔ خنثی.',
                'en' => 'The element under the pointer picks the ring’s state: link grows it, text thins it to a bar, play labels it; with neither you get the neutral ring.',
            ]],
            ['name' => '--mfxcs-lag', 'type' => 'number', 'default' => '0.18', 'note' => [
                'fa' => 'ضریب lerp حلقه؛ ۰٫۱۸ یعنی دنباله‌ای که با هر فریم ۱۸٪ به موس نزدیک می‌شود — کمتر، سست‌تر.',
                'en' => 'The ring’s lerp factor; 0.18 closes 18% of the gap each frame — lower means looser.',
            ]],
            ['name' => 'label', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'برچسب data-label دکمه داخل حلقه می‌نشیند؛ فقط برای حالت play.',
                'en' => 'The button’s data-label sits inside the ring; only for the play state.',
            ]],
            ['name' => 'pointer: coarse', 'type' => 'media', 'default' => "'hide'", 'note' => [
                'fa' => 'روی لمس حلقه حذف و کرسر بومی برمی‌گردد؛ خروج موس از صحنه همه‌چیز را محو می‌کند.',
                'en' => 'On touch the ring is removed and the native cursor returns; leaving the stage fades everything out.',
            ]],
            ['name' => 'prefers-reduced-motion', 'type' => 'behaviour', 'default' => "'instant'", 'note' => [
                'fa' => 'بدون حرکت کاهش‌یافته، دنباله حذف و پیگیری لحظه‌ای می‌شود.',
                'en' => 'With reduced motion the lag is dropped and the follow is instant.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="mfxcs" x-data="cursorFx()">
            <div class="mfxcs-stage" x-ref="stage">
                <a class="mfxcs-link" data-cursor="link" href="#drops">کاوش مجموعهٔ پاییز</a>
                <p class="mfxcs-copy" data-cursor="text">حلقه دنبال موس می‌آید؛ نقطهٔ مرکزی بی‌تأخیر.</p>
                <button class="mfxcs-btn" data-cursor="play" data-label="پخش">پخش تریلر</button>
                <span class="mfxcs-pos" x-ref="pos"><span class="mfxcs-ring"><span x-text="label"></span></span></span>
                <span class="mfxcs-pos mfxcs-dot" x-ref="dot"></span>
            </div>
        </div>

        <style>
        .mfxcs-stage { position: relative; overflow: clip; cursor: none; }
        .mfxcs-pos { position: absolute; inset-block-start: 0; inset-inline-start: 0; pointer-events: none; }
        .mfxcs-ring { display: grid; place-items: center; translate: -50% -50%; inline-size: 18px; aspect-ratio: 1;
                      border: 1.5px solid #111; border-radius: 999px; transition: inline-size .18s, background-color .18s; }
        .mfxcs[data-mode='link'] .mfxcs-ring { inline-size: 56px; background: #1111; }
        .mfxcs[data-mode='text'] .mfxcs-ring { inline-size: 4px; block-size: 26px; border-radius: 2px; background: #111; }
        .mfxcs[data-mode='play'] .mfxcs-ring { inline-size: 64px; background: #111; color: #fff; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="mfxcs" x-data="cursorFx()">
        <div class="mfxcs-stage" x-ref="stage">
        <a class="mfxcs-link" data-cursor="link" href="#drops">Explore the autumn drop</a>
        <p class="mfxcs-copy" data-cursor="text">The ring trails the mouse; the dot does not.</p>
        <button class="mfxcs-btn" data-cursor="play" data-label="Play">Play the trailer</button>
        <span class="mfxcs-pos" x-ref="pos"><span class="mfxcs-ring"><span x-text="label"></span></span></span>
        <span class="mfxcs-pos mfxcs-dot" x-ref="dot"></span>
        </div>
        </div>

        <style>
        .mfxcs-stage { position: relative; overflow: clip; cursor: none; }
        .mfxcs-pos { position: absolute; inset-block-start: 0; inset-inline-start: 0; pointer-events: none; }
        .mfxcs-ring { display: grid; place-items: center; translate: -50% -50%; inline-size: 18px; aspect-ratio: 1;
                      border: 1.5px solid #111; border-radius: 999px; transition: inline-size .18s, background-color .18s; }
        .mfxcs[data-mode='link'] .mfxcs-ring { inline-size: 56px; background: #1111; }
        .mfxcs[data-mode='text'] .mfxcs-ring { inline-size: 4px; block-size: 26px; border-radius: 2px; background: #111; }
        .mfxcs[data-mode='play'] .mfxcs-ring { inline-size: 64px; background: #111; color: #fff; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Showcase.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import CursorStage from '@/components/CursorStage';

        export default function Showcase() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <CursorStage />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // CursorStage.jsx — the trailing cursor with states (link / text / play)
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        export default function CursorStage() {
          const stageRef = useRef(null);
          const ringRef = useRef(null);
          const dotRef = useRef(null);
          const [label, setLabel] = useState('');

          useEffect(() => {
            const stage = stageRef.current, ring = ringRef.current, dot = dotRef.current;
            const reduced = matchMedia('(prefers-reduced-motion: reduce)').matches;
            let raf = 0, tx = 0, ty = 0, rx = 0, ry = 0, rtl = false;

            const place = (el, x, y) => { el.style.translate = `${rtl ? -x : x}px ${y}px`; };
            const tick = () => {
              const k = reduced ? 1 : 0.18;               // the lag, dropped under reduced motion
              rx += (tx - rx) * k; ry += (ty - ry) * k;
              place(ring, rx, ry); place(dot, tx, ty);    // the dot never lags
              raf = requestAnimationFrame(tick);
            };
            const onMove = (e) => {
              const r = stage.getBoundingClientRect();
              const px = e.clientX - r.left, py = e.clientY - r.top;
              rtl = getComputedStyle(stage).direction === 'rtl';
              tx = rtl ? r.width - px : px; ty = py;      // measure from the inline-start edge
              const hit = e.target.closest('[data-cursor]');
              stage.dataset.mode = hit?.dataset.cursor ?? '';
              setLabel(hit?.dataset.label ?? '');
              stage.dataset.on = 'yes';
            };
            const onLeave = () => { stage.dataset.on = 'no'; };

            stage.addEventListener('pointermove', onMove);
            stage.addEventListener('pointerover', onMove);
            stage.addEventListener('pointerleave', onLeave);
            raf = requestAnimationFrame(tick);
            return () => {
              cancelAnimationFrame(raf);
              stage.removeEventListener('pointermove', onMove);
              stage.removeEventListener('pointerover', onMove);
              stage.removeEventListener('pointerleave', onLeave);
            };
          }, []);

          return (
            <>
              <style>{`
                .mfxcs-stage { position: relative; overflow: clip; cursor: none; min-block-size: 18rem;
                              display: grid; gap: 1rem; place-content: center; text-align: center;
                              border-radius: 1rem; background: #fafafa; }
                .mfxcs-pos { position: absolute; inset-block-start: 0; inset-inline-start: 0; pointer-events: none;
                             opacity: 0; transition: opacity .2s; }
                .mfxcs-stage[data-on='yes'] .mfxcs-pos { opacity: 1; }
                .mfxcs-ring { display: grid; place-items: center; translate: -50% -50%; inline-size: 18px; aspect-ratio: 1;
                              border: 1.5px solid #111; border-radius: 999px; font: 500 .7rem/1 system-ui; color: #111;
                              transition: inline-size .18s, background-color .18s, color .18s; }
                .mfxcs-dot { translate: -50% -50%; inline-size: 5px; aspect-ratio: 1; border-radius: 50%; background: #111; }
                .mfxcs-stage[data-mode='link'] .mfxcs-ring { inline-size: 56px; background: #11100018; }
                .mfxcs-stage[data-mode='text'] .mfxcs-ring { inline-size: 4px; block-size: 26px; border-radius: 2px; background: #111; }
                .mfxcs-stage[data-mode='play'] .mfxcs-ring { inline-size: 64px; background: #111; color: #fff; }
                @media (pointer: coarse) { .mfxcs-stage { cursor: auto; } .mfxcs-pos { display: none; } }
              `}</style>
              <div className="mfxcs-stage" ref={stageRef} data-on="no" data-mode="">
                <a className="mfxcs-hit" data-cursor="link" href="#drops">Explore the autumn drop</a>
                <p data-cursor="text">The ring trails the mouse; the dot does not.</p>
                <button type="button" data-cursor="play" data-label="Play">Play the trailer</button>
                <span className="mfxcs-pos" ref={ringRef}>
                  <span className="mfxcs-ring">{label}</span>
                </span>
                <span className="mfxcs-pos mfxcs-dot" ref={dotRef} />
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- CursorStage.vue — the trailing cursor with states (link / text / play) -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const stage = ref<HTMLElement | null>(null);
        const ring = ref<HTMLElement | null>(null);
        const dot = ref<HTMLElement | null>(null);
        const label = ref('');

        let raf = 0, tx = 0, ty = 0, rx = 0, ry = 0, rtl = false, reduced = false;
        const place = (el: HTMLElement, x: number, y: number) => { el.style.translate = `${rtl ? -x : x}px ${y}px`; };
        const tick = () => {
          const k = reduced ? 1 : 0.18;                 // the lag, dropped under reduced motion
          rx += (tx - rx) * k; ry += (ty - ry) * k;
          place(ring.value!, rx, ry); place(dot.value!, tx, ty);  // the dot never lags
          raf = requestAnimationFrame(tick);
        };
        const onMove = (e: PointerEvent) => {
          const el = stage.value!, r = el.getBoundingClientRect();
          const px = e.clientX - r.left, py = e.clientY - r.top;
          rtl = getComputedStyle(el).direction === 'rtl';
          tx = rtl ? r.width - px : px; ty = py;        // measure from the inline-start edge
          const hit = (e.target as Element).closest('[data-cursor]');
          el.dataset.mode = hit?.getAttribute('data-cursor') ?? '';
          label.value = hit?.getAttribute('data-label') ?? '';
          el.dataset.on = 'yes';
        };
        const onLeave = () => { stage.value!.dataset.on = 'no'; };

        onMounted(() => {
          reduced = matchMedia('(prefers-reduced-motion: reduce)').matches;
          stage.value!.addEventListener('pointermove', onMove);
          stage.value!.addEventListener('pointerover', onMove);
          stage.value!.addEventListener('pointerleave', onLeave);
          raf = requestAnimationFrame(tick);
        });
        onBeforeUnmount(() => {
          cancelAnimationFrame(raf);
          stage.value?.removeEventListener('pointermove', onMove);
          stage.value?.removeEventListener('pointerover', onMove);
          stage.value?.removeEventListener('pointerleave', onLeave);
        });
        </script>

        <template>
          <div ref="stage" class="mfxcs-stage" data-on="no" data-mode="">
            <a data-cursor="link" href="#drops">Explore the autumn drop</a>
            <p data-cursor="text">The ring trails the mouse; the dot does not.</p>
            <button type="button" data-cursor="play" data-label="Play">Play the trailer</button>
            <span ref="ring" class="mfxcs-pos"><span class="mfxcs-ring">{{ label }}</span></span>
            <span ref="dot" class="mfxcs-pos mfxcs-dot"></span>
          </div>
        </template>

        <style scoped>
        .mfxcs-stage { position: relative; overflow: clip; cursor: none; min-block-size: 18rem;
                       display: grid; gap: 1rem; place-content: center; text-align: center;
                       border-radius: 1rem; background: #fafafa; }
        .mfxcs-pos { position: absolute; inset-block-start: 0; inset-inline-start: 0; pointer-events: none;
                     opacity: 0; transition: opacity .2s; }
        .mfxcs-stage[data-on='yes'] :deep(.mfxcs-pos) { opacity: 1; }
        .mfxcs-ring { display: grid; place-items: center; translate: -50% -50%; inline-size: 18px; aspect-ratio: 1;
                      border: 1.5px solid #111; border-radius: 999px; font: 500 .7rem/1 system-ui; color: #111;
                      transition: inline-size .18s, background-color .18s, color .18s; }
        .mfxcs-dot { translate: -50% -50%; inline-size: 5px; aspect-ratio: 1; border-radius: 50%; background: #111; }
        .mfxcs-stage[data-mode='link'] :deep(.mfxcs-ring) { inline-size: 56px; background: #11100018; }
        .mfxcs-stage[data-mode='text'] :deep(.mfxcs-ring) { inline-size: 4px; block-size: 26px; border-radius: 2px; background: #111; }
        .mfxcs-stage[data-mode='play'] :deep(.mfxcs-ring) { inline-size: 64px; background: #111; color: #fff; }
        @media (pointer: coarse) { .mfxcs-stage { cursor: auto; } .mfxcs-pos { display: none; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- CursorStage.svelte — the trailing cursor with states (link / text / play) -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          let stage: HTMLElement, ring: HTMLElement, dot: HTMLElement;
          let label = $state('');

          let raf = 0, tx = 0, ty = 0, rx = 0, ry = 0, rtl = false, reduced = false;
          const place = (el: HTMLElement, x: number, y: number) => { el.style.translate = `${rtl ? -x : x}px ${y}px`; };
          const tick = () => {
            const k = reduced ? 1 : 0.18;               // the lag, dropped under reduced motion
            rx += (tx - rx) * k; ry += (ty - ry) * k;
            place(ring, rx, ry); place(dot, tx, ty);    // the dot never lags
            raf = requestAnimationFrame(tick);
          };
          const onMove = (e: PointerEvent) => {
            const r = stage.getBoundingClientRect();
            const px = e.clientX - r.left, py = e.clientY - r.top;
            rtl = getComputedStyle(stage).direction === 'rtl';
            tx = rtl ? r.width - px : px; ty = py;      // measure from the inline-start edge
            const hit = (e.target as Element).closest('[data-cursor]');
            stage.dataset.mode = hit?.getAttribute('data-cursor') ?? '';
            label = hit?.getAttribute('data-label') ?? '';
            stage.dataset.on = 'yes';
          };
          const onLeave = () => { stage.dataset.on = 'no'; };

          onMount(() => {
            reduced = matchMedia('(prefers-reduced-motion: reduce)').matches;
            stage.addEventListener('pointermove', onMove);
            stage.addEventListener('pointerover', onMove);
            stage.addEventListener('pointerleave', onLeave);
            raf = requestAnimationFrame(tick);
            return () => {
              cancelAnimationFrame(raf);
              stage.removeEventListener('pointermove', onMove);
              stage.removeEventListener('pointerover', onMove);
              stage.removeEventListener('pointerleave', onLeave);
            };
          });
        </script>

        <div bind:this={stage} class="mfxcs-stage" data-on="no" data-mode="">
          <a data-cursor="link" href="#drops">Explore the autumn drop</a>
          <p data-cursor="text">The ring trails the mouse; the dot does not.</p>
          <button type="button" data-cursor="play" data-label="Play">Play the trailer</button>
          <span bind:this={ring} class="mfxcs-pos"><span class="mfxcs-ring">{label}</span></span>
          <span bind:this={dot} class="mfxcs-pos mfxcs-dot"></span>
        </div>

        <style>
          .mfxcs-stage { position: relative; overflow: clip; cursor: none; min-block-size: 18rem;
                         display: grid; gap: 1rem; place-content: center; text-align: center;
                         border-radius: 1rem; background: #fafafa; }
          .mfxcs-pos { position: absolute; inset-block-start: 0; inset-inline-start: 0; pointer-events: none;
                       opacity: 0; transition: opacity .2s; }
          .mfxcs-stage[data-on='yes'] .mfxcs-pos { opacity: 1; }
          .mfxcs-ring { display: grid; place-items: center; translate: -50% -50%; inline-size: 18px; aspect-ratio: 1;
                        border: 1.5px solid #111; border-radius: 999px; font: 500 .7rem/1 system-ui; color: #111;
                        transition: inline-size .18s, background-color .18s, color .18s; }
          .mfxcs-dot { translate: -50% -50%; inline-size: 5px; aspect-ratio: 1; border-radius: 50%; background: #111; }
          .mfxcs-stage[data-mode='link'] .mfxcs-ring { inline-size: 56px; background: #11100018; }
          .mfxcs-stage[data-mode='text'] .mfxcs-ring { inline-size: 4px; block-size: 26px; border-radius: 2px; background: #111; }
          .mfxcs-stage[data-mode='play'] .mfxcs-ring { inline-size: 64px; background: #111; color: #fff; }
          @media (pointer: coarse) { .mfxcs-stage { cursor: auto; } .mfxcs-pos { display: none; } }
        </style>
        SVELTE,
        ],
    ],

    'spotlight-grid' => [
        'title' => ['fa' => 'گرید نورافکن', 'en' => 'Spotlight grid'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'دیوار کارت‌های حاشیه‌دار که یک نورافکن موس، کل گرید را روشن می‌کند: هالهٔ نور از گوشه‌ای که دست شماست می‌تابد، حاشیهٔ همان کارت که زیر دست است می‌درخشد و درونش نرم روشن می‌شود — بدون آن‌که حتی یک کارت بلند شود.',
            'en' => 'A wall of bordered cards lit by one mouse spotlight: the halo beams from wherever your hand is, the very card under it gets a glowing border and a soft inner wash — none of the cards ever lifts.',
        ],
        'js' => true,
        'docs' => 'https://kinetics.colorion.co',
        'props' => [
            ['name' => 'radius', 'type' => 'length', 'default' => "'260px'", 'note' => [
                'fa' => 'شعاع هالهٔ نورافکن روی کل گرید؛ بزرگ‌تر یعنی نور پخش‌تر و محوتر.',
                'en' => 'The halo radius across the grid; larger means a softer, hazier beam.',
            ]],
            ['name' => '--mfxsg-glow', 'type' => 'color', 'default' => "'#4f46e5'", 'note' => [
                'fa' => 'رنگ نور؛ روی حاشیهٔ کارت‌ها و هالهٔ گرید یکسان می‌نشیند.',
                'en' => 'The light colour; it rides the card borders and the grid halo alike.',
            ]],
            ['name' => 'data-hot', 'type' => 'state', 'default' => "''", 'note' => [
                'fa' => 'کارت زیر دست با data-hot روشن می‌شود: حاشیه از نور محلی می‌گیرد و متنش پررنگ؛ فوکوس کیبورد هم همان حالت را می‌سازد.',
                'en' => 'The card under the hand turns data-hot: its border feeds off the local light and its text sharpens; keyboard focus builds the same state.',
            ]],
            ['name' => 'border-mask', 'type' => 'mask', 'default' => "'1px ring'", 'note' => [
                'fa' => 'درخشش حاشیه با ماسک exclude فقط روی حلقهٔ ۱پیکسلی می‌نشیند — قلب ظاهر aceternity.',
                'en' => 'The border glow is masked down to the 1px ring with an exclude mask — the aceternity look’s heart.',
            ]],
            ['name' => 'prefers-reduced-motion', 'type' => 'behaviour', 'default' => "'static'", 'note' => [
                'fa' => 'نورافکن دنبال‌کننده خاموش و هاله‌ای ثابت در مرکز گرید می‌نشیند.',
                'en' => 'The following spotlight is dropped for a static halo centred on the grid.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="mfxsg" x-data="spotlightGrid()">
            <div class="mfxsg-grid" x-ref="grid" x-bind:data-on="on ? 'yes' : 'no'">
                @foreach ($features as $f)
                    <article class="mfxsg-card">
                        <h3>{{ $f['title'] }}</h3>
                        <p>{{ $f['note'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>

        <style>
        .mfxsg-grid { position: relative; display: grid; gap: 1rem;
                      grid-template-columns: repeat(auto-fit, minmax(15rem, 1fr)); }
        .mfxsg-grid::before { content: ''; position: absolute; inset: 0; pointer-events: none; z-index: 1;
                              background: radial-gradient(260px circle at var(--mx, 50%) var(--my, 0%), rgba(79,70,229,.16), transparent 70%); }
        .mfxsg-card { position: relative; border: 1px solid #d9dde4; border-radius: 1rem; background: #fff; }
        .mfxsg-card::before { content: ''; position: absolute; inset: 0; border-radius: inherit; padding: 1px;
                              background: radial-gradient(220px circle at var(--cx, 50%) var(--cy, 50%), #4f46e5, transparent 70%);
                              mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0); mask-composite: exclude; }
        .mfxsg-card[data-hot] { background: radial-gradient(220px circle at var(--cx) var(--cy), rgba(79,70,229,.09), transparent 65%), #fff; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="mfxsg" x-data="spotlightGrid()">
        <div class="mfxsg-grid" x-ref="grid" x-bind:data-on="on ? 'yes' : 'no'">
        @foreach ($features as $f)
        <article class="mfxsg-card">
        <h3>{{ $f['title'] }}</h3>
        <p>{{ $f['note'] }}</p>
        </article>
        @endforeach
        </div>
        </div>

        <style>
        .mfxsg-grid { position: relative; display: grid; gap: 1rem;
                      grid-template-columns: repeat(auto-fit, minmax(15rem, 1fr)); }
        .mfxsg-grid::before { content: ''; position: absolute; inset: 0; pointer-events: none; z-index: 1;
                              background: radial-gradient(260px circle at var(--mx, 50%) var(--my, 0%), rgba(79,70,229,.16), transparent 70%); }
        .mfxsg-card { position: relative; border: 1px solid #d9dde4; border-radius: 1rem; background: #fff; }
        .mfxsg-card::before { content: ''; position: absolute; inset: 0; border-radius: inherit; padding: 1px;
                              background: radial-gradient(220px circle at var(--cx, 50%) var(--cy, 50%), #4f46e5, transparent 70%);
                              mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0); mask-composite: exclude; }
        .mfxsg-card[data-hot] { background: radial-gradient(220px circle at var(--cx) var(--cy), rgba(79,70,229,.09), transparent 65%), #fff; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Features.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import SpotlightGrid from '@/components/SpotlightGrid';

        export default function Features() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <SpotlightGrid />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // SpotlightGrid.jsx — one mouse spotlight over a wall of bordered cards
        import { useEffect, useRef } from 'react';
        import '@nabuxai/ui-core/css';

        const FEATURES = [
          { id: 'edge', title: 'Edge delivery', note: '28 regions, Frankfurt to Singapore' },
          { id: 'query', title: 'Live queries', note: 'p95 under 40ms' },
          { id: 'replay', title: 'Session replay', note: 'Watch the exact click' },
          { id: 'guards', title: 'Guardrails', note: 'Budget alerts before the bill' },
          { id: 'sync', title: 'Warehouse sync', note: 'BigQuery and Snowflake' },
          { id: 'sso', title: 'Enterprise SSO', note: 'SAML and SCIM, one toggle' },
        ];

        export default function SpotlightGrid() {
          const gridRef = useRef(null);

          useEffect(() => {
            const grid = gridRef.current;
            const cards = () => Array.from(grid.querySelectorAll('.mfxsg-card'));

            const onMove = (e) => {
              const r = grid.getBoundingClientRect();
              // gradient positions are physical: measure from the left, always
              grid.style.setProperty('--mx', e.clientX - r.left + 'px');
              grid.style.setProperty('--my', e.clientY - r.top + 'px');
              cards().forEach((card) => {
                const c = card.getBoundingClientRect();
                const cx = e.clientX - c.left, cy = e.clientY - c.top;
                card.style.setProperty('--cx', cx + 'px');
                card.style.setProperty('--cy', cy + 'px');
                card.toggleAttribute('data-hot', cx >= 0 && cy >= 0 && cx <= c.width && cy <= c.height);
              });
              grid.dataset.on = 'yes';
            };
            const onLeave = () => {
              grid.dataset.on = 'no';
              cards().forEach((card) => card.removeAttribute('data-hot'));
            };

            grid.addEventListener('pointermove', onMove);
            grid.addEventListener('pointerleave', onLeave);
            return () => {
              grid.removeEventListener('pointermove', onMove);
              grid.removeEventListener('pointerleave', onLeave);
            };
          }, []);

          return (
            <>
              <style>{`
                .mfxsg-grid { position: relative; display: grid; gap: 1rem; padding: 1rem;
                              grid-template-columns: repeat(auto-fit, minmax(15rem, 1fr)); }
                .mfxsg-grid::before { content: ''; position: absolute; inset: 0; pointer-events: none; z-index: 1;
                                      background: radial-gradient(260px circle at var(--mx, 50%) var(--my, 0%), rgba(79,70,229,.16), transparent 70%);
                                      opacity: 0; transition: opacity .25s; }
                .mfxsg-grid[data-on='yes']::before { opacity: 1; }
                .mfxsg-card { position: relative; border: 1px solid #d9dde4; border-radius: 1rem; background: #fff;
                              padding: 1.25rem; font: 400 .875rem/1.5 system-ui; }
                .mfxsg-card::before { content: ''; position: absolute; inset: 0; border-radius: inherit; padding: 1px;
                                      background: radial-gradient(220px circle at var(--cx, 50%) var(--cy, 50%), #4f46e5, transparent 70%);
                                      mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0); mask-composite: exclude; }
                .mfxsg-card[data-hot] { background:
                  radial-gradient(220px circle at var(--cx) var(--cy), rgba(79,70,229,.09), transparent 65%), #fff; }
                .mfxsg-card[data-hot] h3 { color: #4f46e5; }
                @media (prefers-reduced-motion: reduce) {
                  .mfxsg-grid::before { background: radial-gradient(300px circle at 50% 30%, rgba(79,70,229,.16), transparent 70%); opacity: 1; }
                }
              `}</style>
              <div className="mfxsg-grid" ref={gridRef} data-on="no">
                {FEATURES.map((f) => (
                  <article className="mfxsg-card" key={f.id}>
                    <h3>{f.title}</h3>
                    <p>{f.note}</p>
                  </article>
                ))}
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- SpotlightGrid.vue — one mouse spotlight over a wall of bordered cards -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const features = [
          { id: 'edge', title: 'Edge delivery', note: '28 regions, Frankfurt to Singapore' },
          { id: 'query', title: 'Live queries', note: 'p95 under 40ms' },
          { id: 'replay', title: 'Session replay', note: 'Watch the exact click' },
          { id: 'guards', title: 'Guardrails', note: 'Budget alerts before the bill' },
          { id: 'sync', title: 'Warehouse sync', note: 'BigQuery and Snowflake' },
          { id: 'sso', title: 'Enterprise SSO', note: 'SAML and SCIM, one toggle' },
        ];
        const grid = ref<HTMLElement | null>(null);

        const onMove = (e: PointerEvent) => {
          const el = grid.value!, r = el.getBoundingClientRect();
          // gradient positions are physical: measure from the left, always
          el.style.setProperty('--mx', e.clientX - r.left + 'px');
          el.style.setProperty('--my', e.clientY - r.top + 'px');
          el.querySelectorAll<HTMLElement>('.mfxsg-card').forEach((card) => {
            const c = card.getBoundingClientRect();
            const cx = e.clientX - c.left, cy = e.clientY - c.top;
            card.style.setProperty('--cx', cx + 'px');
            card.style.setProperty('--cy', cy + 'px');
            if (cx >= 0 && cy >= 0 && cx <= c.width && cy <= c.height) card.setAttribute('data-hot', '');
            else card.removeAttribute('data-hot');
          });
          el.dataset.on = 'yes';
        };
        const onLeave = () => {
          grid.value!.dataset.on = 'no';
          grid.value!.querySelectorAll('.mfxsg-card').forEach((card) => card.removeAttribute('data-hot'));
        };

        onMounted(() => {
          grid.value!.addEventListener('pointermove', onMove);
          grid.value!.addEventListener('pointerleave', onLeave);
        });
        onBeforeUnmount(() => {
          grid.value?.removeEventListener('pointermove', onMove);
          grid.value?.removeEventListener('pointerleave', onLeave);
        });
        </script>

        <template>
          <div ref="grid" class="mfxsg-grid" data-on="no">
            <article v-for="f in features" :key="f.id" class="mfxsg-card">
              <h3>{{ f.title }}</h3>
              <p>{{ f.note }}</p>
            </article>
          </div>
        </template>

        <style scoped>
        .mfxsg-grid { position: relative; display: grid; gap: 1rem; padding: 1rem;
                      grid-template-columns: repeat(auto-fit, minmax(15rem, 1fr)); }
        .mfxsg-grid::before { content: ''; position: absolute; inset: 0; pointer-events: none; z-index: 1;
                              background: radial-gradient(260px circle at var(--mx, 50%) var(--my, 0%), rgba(79,70,229,.16), transparent 70%);
                              opacity: 0; transition: opacity .25s; }
        .mfxsg-grid[data-on='yes']::before { opacity: 1; }
        .mfxsg-card { position: relative; border: 1px solid #d9dde4; border-radius: 1rem; background: #fff;
                      padding: 1.25rem; font: 400 .875rem/1.5 system-ui; }
        .mfxsg-card::before { content: ''; position: absolute; inset: 0; border-radius: inherit; padding: 1px;
                              background: radial-gradient(220px circle at var(--cx, 50%) var(--cy, 50%), #4f46e5, transparent 70%);
                              mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0); mask-composite: exclude; }
        .mfxsg-card[data-hot] { background:
          radial-gradient(220px circle at var(--cx) var(--cy), rgba(79,70,229,.09), transparent 65%), #fff; }
        .mfxsg-card[data-hot] h3 { color: #4f46e5; }
        @media (prefers-reduced-motion: reduce) {
          .mfxsg-grid::before { background: radial-gradient(300px circle at 50% 30%, rgba(79,70,229,.16), transparent 70%); opacity: 1; }
        }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- SpotlightGrid.svelte — one mouse spotlight over a wall of bordered cards -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          const features = [
            { id: 'edge', title: 'Edge delivery', note: '28 regions, Frankfurt to Singapore' },
            { id: 'query', title: 'Live queries', note: 'p95 under 40ms' },
            { id: 'replay', title: 'Session replay', note: 'Watch the exact click' },
            { id: 'guards', title: 'Guardrails', note: 'Budget alerts before the bill' },
            { id: 'sync', title: 'Warehouse sync', note: 'BigQuery and Snowflake' },
            { id: 'sso', title: 'Enterprise SSO', note: 'SAML and SCIM, one toggle' },
          ];

          let grid: HTMLElement;

          const onMove = (e: PointerEvent) => {
            const r = grid.getBoundingClientRect();
            // gradient positions are physical: measure from the left, always
            grid.style.setProperty('--mx', e.clientX - r.left + 'px');
            grid.style.setProperty('--my', e.clientY - r.top + 'px');
            grid.querySelectorAll<HTMLElement>('.mfxsg-card').forEach((card) => {
              const c = card.getBoundingClientRect();
              const cx = e.clientX - c.left, cy = e.clientY - c.top;
              card.style.setProperty('--cx', cx + 'px');
              card.style.setProperty('--cy', cy + 'px');
              if (cx >= 0 && cy >= 0 && cx <= c.width && cy <= c.height) card.setAttribute('data-hot', '');
              else card.removeAttribute('data-hot');
            });
            grid.dataset.on = 'yes';
          };
          const onLeave = () => {
            grid.dataset.on = 'no';
            grid.querySelectorAll('.mfxsg-card').forEach((card) => card.removeAttribute('data-hot'));
          };

          onMount(() => {
            grid.addEventListener('pointermove', onMove);
            grid.addEventListener('pointerleave', onLeave);
            return () => {
              grid.removeEventListener('pointermove', onMove);
              grid.removeEventListener('pointerleave', onLeave);
            };
          });
        </script>

        <div bind:this={grid} class="mfxsg-grid" data-on="no">
          {#each features as f (f.id)}
            <article class="mfxsg-card">
              <h3>{f.title}</h3>
              <p>{f.note}</p>
            </article>
          {/each}
        </div>

        <style>
          .mfxsg-grid { position: relative; display: grid; gap: 1rem; padding: 1rem;
                        grid-template-columns: repeat(auto-fit, minmax(15rem, 1fr)); }
          .mfxsg-grid::before { content: ''; position: absolute; inset: 0; pointer-events: none; z-index: 1;
                                background: radial-gradient(260px circle at var(--mx, 50%) var(--my, 0%), rgba(79,70,229,.16), transparent 70%);
                                opacity: 0; transition: opacity .25s; }
          .mfxsg-grid[data-on='yes']::before { opacity: 1; }
          .mfxsg-card { position: relative; border: 1px solid #d9dde4; border-radius: 1rem; background: #fff;
                        padding: 1.25rem; font: 400 .875rem/1.5 system-ui; }
          .mfxsg-card::before { content: ''; position: absolute; inset: 0; border-radius: inherit; padding: 1px;
                                background: radial-gradient(220px circle at var(--cx, 50%) var(--cy, 50%), #4f46e5, transparent 70%);
                                mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0); mask-composite: exclude; }
          .mfxsg-card[data-hot] { background:
            radial-gradient(220px circle at var(--cx) var(--cy), rgba(79,70,229,.09), transparent 65%), #fff; }
          .mfxsg-card[data-hot] h3 { color: #4f46e5; }
          @media (prefers-reduced-motion: reduce) {
            .mfxsg-grid::before { background: radial-gradient(300px circle at 50% 30%, rgba(79,70,229,.16), transparent 70%); opacity: 1; }
          }
        </style>
        SVELTE,
        ],
    ],

    'animated-beams' => [
        'title' => ['fa' => 'پرتوهای اتصال', 'en' => 'Animated beams'],
        'icon' => 'zap',
        'oneLiner' => [
            'fa' => 'نقشهٔ معماری زنده: گره‌های سرویس روی بوم SVG نشسته‌اند و بینشان خطوط نورانی با stroke-dash پالس می‌فرستند — نوری که می‌جهد و می‌رود؛ هاور هر گره پرتوهای همان گره را روشن‌تر و تندتر می‌کند تا ببینید چه چیزی به چه چیزی وصل است.',
            'en' => 'A living architecture map: service nodes on an SVG canvas with beam-like lines pulsing between them through animated stroke-dash — light that travels and fades; hovering a node brightens and quickens just its beams, so the wiring explains itself.',
        ],
        'js' => true,
        'docs' => 'https://kinetics.colorion.co',
        'props' => [
            ['name' => 'stroke-dasharray', 'type' => 'length', 'default' => "'16 210'", 'note' => [
                'fa' => 'طول قطعهٔ نورانی و فاصله‌اش تا تکرار بعدی؛ کوچک‌تر یعنی دنباله‌های کوتاه‌تر و پراکنده‌تر.',
                'en' => 'The light segment’s length and the gap before it repeats; smaller means shorter, sparser comets.',
            ]],
            ['name' => '--mfxab-duration', 'type' => 'time', 'default' => "'3.2s'", 'note' => [
                'fa' => 'زمان یک پالس کامل از مبدأ تا مقصد؛ پرتوهای روشن‌شده با هاور تندتر می‌شوند.',
                'en' => 'One pulse’s travel time end to end; hovered beams run quicker.',
            ]],
            ['name' => '--mfxab-beam', 'type' => 'color', 'default' => "'#6366f1'", 'note' => [
                'fa' => 'رنگ پرتو؛ گرادیان مخصوص هر دمو با id پیشونددار تعریف می‌شود تا با دموی دیگری تصادم نکند.',
                'en' => 'The beam colour; each demo defines its gradient under a prefixed id so it never collides with another demo.',
            ]],
            ['name' => 'data-hot', 'type' => 'state', 'default' => "''", 'note' => [
                'fa' => 'هاور یا فوکوس گره، پرتوهای متصل به آن گره را روشن می‌کند و حلقهٔ خود گره می‌درخشد؛ چیپ‌های زیر بوم همان حالت را برای لمس می‌سازند.',
                'en' => 'Hovering or focusing a node lights the beams wired to it and glows its ring; the chips under the canvas build the same state for touch.',
            ]],
            ['name' => 'prefers-reduced-motion', 'type' => 'behaviour', 'default' => "'freeze'", 'note' => [
                'fa' => 'پالس‌ها می‌ایستند و شبکه در حالتی نیمه‌روشن ثابت می‌ماند؛ هاور همچنان روشنی را عوض می‌کند.',
                'en' => 'The pulses freeze and the mesh settles half-lit; hover still changes brightness.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="mfxab" x-data="{ hot: '' }">
            <svg class="mfxab-map" viewBox="0 0 640 300" role="img" aria-label="نقشهٔ سرویس‌ها">
                <defs>
                    <linearGradient id="mfxab-glow" x1="0" x2="1">
                        <stop offset="0" stop-color="#6366f1" stop-opacity="0" />
                        <stop offset="1" stop-color="#22d3ee" />
                    </linearGradient>
                </defs>
                <path class="mfxab-wire" d="M320 150 Q 200 90 110 80" />
                <path class="mfxab-beam" stroke="url(#mfxab-glow)" d="M320 150 Q 200 90 110 80"
                      x-bind:data-hot="hot === 'web' || hot === 'hub'" />
                <g class="mfxab-node" x-bind:data-hot="hot === 'web'" @pointerenter="hot = 'web'" @pointerleave="hot = ''">
                    <circle cx="110" cy="80" r="30" /><text y="130">رابط وب</text>
                </g>
            </svg>
        </div>

        <style>
        .mfxab-wire { fill: none; stroke: #cbd5e1; stroke-width: 1.5; }
        .mfxab-beam { fill: none; stroke-width: 2; stroke-linecap: round; stroke-dasharray: 16 210;
                      animation: mfxab-run 3.2s linear infinite; opacity: .75; }
        .mfxab-beam[data-hot] { opacity: 1; stroke-width: 3; animation-duration: 1.6s; }
        @keyframes mfxab-run { to { stroke-dashoffset: -226; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="mfxab" x-data="{ hot: '' }">
        <svg class="mfxab-map" viewBox="0 0 640 300" role="img" aria-label="Service map">
        <defs>
        <linearGradient id="mfxab-glow" x1="0" x2="1">
        <stop offset="0" stop-color="#6366f1" stop-opacity="0" />
        <stop offset="1" stop-color="#22d3ee" />
        </linearGradient>
        </defs>
        <path class="mfxab-wire" d="M320 150 Q 200 90 110 80" />
        <path class="mfxab-beam" stroke="url(#mfxab-glow)" d="M320 150 Q 200 90 110 80"
        x-bind:data-hot="hot === 'web' || hot === 'hub'" />
        <g class="mfxab-node" x-bind:data-hot="hot === 'web'" @pointerenter="hot = 'web'" @pointerleave="hot = ''">
        <circle cx="110" cy="80" r="30" /><text y="130">Web app</text>
        </g>
        </svg>
        </div>

        <style>
        .mfxab-wire { fill: none; stroke: #cbd5e1; stroke-width: 1.5; }
        .mfxab-beam { fill: none; stroke-width: 2; stroke-linecap: round; stroke-dasharray: 16 210;
                      animation: mfxab-run 3.2s linear infinite; opacity: .75; }
        .mfxab-beam[data-hot] { opacity: 1; stroke-width: 3; }
        @keyframes mfxab-run { to { stroke-dashoffset: -226; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Architecture.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import BeamMap from '@/components/BeamMap';

        export default function Architecture() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <BeamMap />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // BeamMap.jsx — service nodes wired by pulsing stroke-dash beams
        import { useState } from 'react';
        import '@nabuxai/ui-core/css';

        const HUB = { id: 'hub', label: 'Orchestrator', x: 320, y: 150 };
        const NODES = [
          { id: 'web', label: 'Web app', city: 'Dublin', x: 110, y: 70 },
          { id: 'auth', label: 'Auth', city: 'Frankfurt', x: 320, y: 40 },
          { id: 'search', label: 'Search', city: 'Singapore', x: 530, y: 70 },
          { id: 'media', label: 'Media', city: 'Osaka', x: 530, y: 240 },
          { id: 'queue', label: 'Queue', city: 'Toronto', x: 110, y: 240 },
        ];
        const beam = (a, b) => `M${a.x} ${a.y} Q ${(a.x + b.x) / 2} ${(a.y + b.y) / 2 - 60} ${b.x} ${b.y}`;

        export default function BeamMap() {
          const [hot, setHot] = useState('');
          const lit = (id) => hot === id || hot === 'hub';

          return (
            <>
              <style>{`
                .mfxab-map { inline-size: 100%; max-inline-size: 40rem; }
                .mfxab-wire { fill: none; stroke: #cbd5e1; stroke-width: 1.5; }
                .mfxab-beam { fill: none; stroke-width: 2; stroke-linecap: round; stroke-dasharray: 16 210;
                              animation: mfxab-run 3.2s linear infinite; opacity: .7;
                              transition: opacity .2s, stroke-width .2s; }
                .mfxab-beam[data-hot] { opacity: 1; stroke-width: 3.5; animation-duration: 1.6s; }
                .mfxab-node circle { fill: #fff; stroke: #cbd5e1; stroke-width: 1.5; transition: stroke .2s; }
                .mfxab-node[data-hot] circle { stroke: #6366f1; stroke-width: 3; }
                .mfxab-node text { text-anchor: middle; font: 600 15px system-ui; fill: #16181d; }
                .mfxab-node[data-hot] text { fill: #6366f1; }
                @keyframes mfxab-run { to { stroke-dashoffset: -226; } }
                @media (prefers-reduced-motion: reduce) { .mfxab-beam { animation: none; opacity: .5; } }
              `}</style>
              <svg className="mfxab-map" viewBox="0 0 640 300" role="img" aria-label="Service map">
                <defs>
                  <linearGradient id="mfxab-glow" x1="0" x2="1">
                    <stop offset="0" stopColor="#6366f1" stopOpacity="0" />
                    <stop offset="1" stopColor="#22d3ee" />
                  </linearGradient>
                </defs>
                {NODES.map((n) => (
                  <g key={n.id}>
                    <path className="mfxab-wire" d={beam(HUB, n)} />
                    <path className="mfxab-beam" stroke="url(#mfxab-glow)" d={beam(HUB, n)}
                      data-hot={lit(n.id) || undefined} />
                  </g>
                ))}
                <g className="mfxab-node" data-hot={hot === 'hub' || undefined}
                   onPointerEnter={() => setHot('hub')} onPointerLeave={() => setHot('')}>
                  <circle cx={HUB.x} cy={HUB.y} r="34" /><text x={HUB.x} y={HUB.y + 5}>Hub</text>
                </g>
                {NODES.map((n) => (
                  <g key={n.id} className="mfxab-node" data-hot={hot === n.id || undefined}
                     onPointerEnter={() => setHot(n.id)} onPointerLeave={() => setHot('')}>
                    <circle cx={n.x} cy={n.y} r="28" /><text x={n.x} y={n.y + 5}>{n.label}</text>
                  </g>
                ))}
              </svg>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- BeamMap.vue — service nodes wired by pulsing stroke-dash beams -->
        <script setup lang="ts">
        import { ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const hub = { id: 'hub', label: 'Orchestrator', x: 320, y: 150 };
        const nodes = [
          { id: 'web', label: 'Web app', city: 'Dublin', x: 110, y: 70 },
          { id: 'auth', label: 'Auth', city: 'Frankfurt', x: 320, y: 40 },
          { id: 'search', label: 'Search', city: 'Singapore', x: 530, y: 70 },
          { id: 'media', label: 'Media', city: 'Osaka', x: 530, y: 240 },
          { id: 'queue', label: 'Queue', city: 'Toronto', x: 110, y: 240 },
        ];
        const hot = ref('');
        const lit = (id: string) => hot.value === id || hot.value === 'hub';
        const beam = (a: { x: number; y: number }, b: { x: number; y: number }) =>
          `M${a.x} ${a.y} Q ${(a.x + b.x) / 2} ${(a.y + b.y) / 2 - 60} ${b.x} ${b.y}`;
        </script>

        <template>
          <svg class="mfxab-map" viewBox="0 0 640 300" role="img" aria-label="Service map">
            <defs>
              <linearGradient id="mfxab-glow" x1="0" x2="1">
                <stop offset="0" stop-color="#6366f1" stop-opacity="0" />
                <stop offset="1" stop-color="#22d3ee" />
              </linearGradient>
            </defs>
            <g v-for="n in nodes" :key="n.id">
              <path class="mfxab-wire" :d="beam(hub, n)" />
              <path class="mfxab-beam" stroke="url(#mfxab-glow)" :d="beam(hub, n)" :data-hot="lit(n.id) || undefined" />
            </g>
            <g class="mfxab-node" :data-hot="hot === 'hub' || undefined"
               @pointerenter="hot = 'hub'" @pointerleave="hot = ''">
              <circle :cx="hub.x" :cy="hub.y" r="34" /><text :x="hub.x" :y="hub.y + 5">Hub</text>
            </g>
            <g v-for="n in nodes" :key="n.id" class="mfxab-node" :data-hot="hot === n.id || undefined"
               @pointerenter="hot = n.id" @pointerleave="hot = ''">
              <circle :cx="n.x" :cy="n.y" r="28" /><text :x="n.x" :y="n.y + 5">{{ n.label }}</text>
            </g>
          </svg>
        </template>

        <style scoped>
        .mfxab-map { inline-size: 100%; max-inline-size: 40rem; }
        .mfxab-wire { fill: none; stroke: #cbd5e1; stroke-width: 1.5; }
        .mfxab-beam { fill: none; stroke-width: 2; stroke-linecap: round; stroke-dasharray: 16 210;
                      animation: mfxab-run 3.2s linear infinite; opacity: .7;
                      transition: opacity .2s, stroke-width .2s; }
        .mfxab-beam[data-hot] { opacity: 1; stroke-width: 3.5; animation-duration: 1.6s; }
        .mfxab-node :deep(circle) { fill: #fff; stroke: #cbd5e1; stroke-width: 1.5; transition: stroke .2s; }
        .mfxab-node[data-hot] :deep(circle) { stroke: #6366f1; stroke-width: 3; }
        .mfxab-node :deep(text) { text-anchor: middle; font: 600 15px system-ui; fill: #16181d; }
        .mfxab-node[data-hot] :deep(text) { fill: #6366f1; }
        @keyframes mfxab-run { to { stroke-dashoffset: -226; } }
        @media (prefers-reduced-motion: reduce) { .mfxab-beam { animation: none; opacity: .5; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- BeamMap.svelte — service nodes wired by pulsing stroke-dash beams -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const hub = { id: 'hub', label: 'Orchestrator', x: 320, y: 150 };
          const nodes = [
            { id: 'web', label: 'Web app', city: 'Dublin', x: 110, y: 70 },
            { id: 'auth', label: 'Auth', city: 'Frankfurt', x: 320, y: 40 },
            { id: 'search', label: 'Search', city: 'Singapore', x: 530, y: 70 },
            { id: 'media', label: 'Media', city: 'Osaka', x: 530, y: 240 },
            { id: 'queue', label: 'Queue', city: 'Toronto', x: 110, y: 240 },
          ];
          let hot = $state('');
          const lit = (id: string) => hot === id || hot === 'hub';
          const beam = (a: { x: number; y: number }, b: { x: number; y: number }) =>
            `M${a.x} ${a.y} Q ${(a.x + b.x) / 2} ${(a.y + b.y) / 2 - 60} ${b.x} ${b.y}`;
        </script>

        <svg class="mfxab-map" viewBox="0 0 640 300" role="img" aria-label="Service map">
          <defs>
            <linearGradient id="mfxab-glow" x1="0" x2="1">
              <stop offset="0" stop-color="#6366f1" stop-opacity="0" />
              <stop offset="1" stop-color="#22d3ee" />
            </linearGradient>
          </defs>
          {#each nodes as n (n.id)}
            <path class="mfxab-wire" d={beam(hub, n)} />
            <path class="mfxab-beam" stroke="url(#mfxab-glow)" d={beam(hub, n)} data-hot={lit(n.id) || undefined} />
          {/each}
          <g class="mfxab-node" data-hot={hot === 'hub' || undefined}
             onpointerenter={() => (hot = 'hub')} onpointerleave={() => (hot = '')}>
            <circle cx={hub.x} cy={hub.y} r="34" /><text x={hub.x} y={hub.y + 5}>Hub</text>
          </g>
          {#each nodes as n (n.id)}
            <g class="mfxab-node" data-hot={hot === n.id || undefined}
               onpointerenter={() => (hot = n.id)} onpointerleave={() => (hot = '')}>
              <circle cx={n.x} cy={n.y} r="28" /><text x={n.x} y={n.y + 5}>{n.label}</text>
            </g>
          {/each}
        </svg>

        <style>
          .mfxab-map { inline-size: 100%; max-inline-size: 40rem; }
          .mfxab-wire { fill: none; stroke: #cbd5e1; stroke-width: 1.5; }
          .mfxab-beam { fill: none; stroke-width: 2; stroke-linecap: round; stroke-dasharray: 16 210;
                        animation: mfxab-run 3.2s linear infinite; opacity: .7;
                        transition: opacity .2s, stroke-width .2s; }
          .mfxab-beam[data-hot] { opacity: 1; stroke-width: 3.5; animation-duration: 1.6s; }
          .mfxab-node circle { fill: #fff; stroke: #cbd5e1; stroke-width: 1.5; transition: stroke .2s; }
          .mfxab-node[data-hot] circle { stroke: #6366f1; stroke-width: 3; }
          .mfxab-node text { text-anchor: middle; font: 600 15px system-ui; fill: #16181d; }
          .mfxab-node[data-hot] text { fill: #6366f1; }
          @keyframes mfxab-run { to { stroke-dashoffset: -226; } }
          @media (prefers-reduced-motion: reduce) { .mfxab-beam { animation: none; opacity: .5; } }
        </style>
        SVELTE,
        ],
    ],

    'marquee-wall' => [
        'title' => ['fa' => 'دیوار مارکی', 'en' => 'Marquee wall'],
        'icon' => 'users',
        'oneLiner' => [
            'fa' => 'سه ردیف مارکی با سرعت و جهت‌های متفاوت که کارت‌های تعریفِ کوچک — آواتار، نام و یک جمله — را بی‌وقفه رد می‌کنند؛ هاور هر ردیف همان‌جا می‌ایستد تا بخوانید و دکمهٔ یک‌ضرب جهت هر سه ردیف را برعکس می‌کند.',
            'en' => 'Three marquee rows at different speeds and directions, endlessly passing small testimonial cards — avatar, name, one line; hover a row and it stops right there for reading, and one button flips the direction of all three at once.',
        ],
        'js' => true,
        'docs' => 'https://kinetics.colorion.co',
        'props' => [
            ['name' => '--mfxmq-dur', 'type' => 'time', 'default' => "'38s · 26s · 32s'", 'note' => [
                'fa' => 'زمان یک دور کامل هر ردیف؛ ردیف‌ها عمداً ناهم‌سرعت‌اند تا دیوار یکنواخت نشود.',
                'en' => 'One full lap per row; the rows are deliberately unsynchronised so the wall never looks uniform.',
            ]],
            ['name' => 'base-direction', 'type' => 'normal · reverse', 'default' => "'mixed'", 'note' => [
                'fa' => 'جهت پایهٔ هر ردیف؛ دکمهٔ تعویض، جهت هر سه را با هم برعکس می‌کند.',
                'en' => 'Each row’s base direction; the flip button reverses all three together.',
            ]],
            ['name' => 'hover', 'type' => 'state', 'default' => "'paused'", 'note' => [
                'fa' => 'هاور یا فوکوس روی ردیف، مارکی را همان‌جا نگه می‌دارد تا جمله خوانده شود.',
                'en' => 'Hover or focus on a row holds the marquee in place so the line can be read.',
            ]],
            ['name' => 'seamless-half', 'type' => 'layout', 'default' => "'duplicate ×2'", 'note' => [
                'fa' => 'نیمهٔ دوم با aria-hidden تکرار می‌شود و پدینگ انتهایی هر نیمه به اندازهٔ همان گپ، درز را می‌بندد.',
                'en' => 'The second half repeats with aria-hidden and each half’s end padding matches the gap, closing the seam.',
            ]],
            ['name' => 'prefers-reduced-motion', 'type' => 'behaviour', 'default' => "'freeze'", 'note' => [
                'fa' => 'ردیف‌ها می‌ایستند و کارت‌ها به‌صورت ثابت و خوانا می‌مانند.',
                'en' => 'The rows stop and the cards stay put, readable.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="mfxmq" x-data="{ rev: false }" x-bind:data-rev="rev ? 'yes' : 'no'">
            @foreach ([['slow', 'normal'], ['fast', 'reverse'], ['mid', 'normal']] as [$speed, $base])
                <div class="mfxmq-row" data-speed="{{ $speed }}" data-base="{{ $base }}">
                    <div class="mfxmq-track">
                        <div class="mfxmq-half">
                            @foreach ($quotes as $q)
                                <figure class="mfxmq-card">
                                    <span class="mfxmq-avatar">{{ $q['initials'] }}</span>
                                    <blockquote>{{ $q['line'] }}</blockquote>
                                    <figcaption>{{ $q['name'] }}</figcaption>
                                </figure>
                            @endforeach
                        </div>
                        <div class="mfxmq-half" aria-hidden="true"> … همان کارت‌ها … </div>
                    </div>
                </div>
            @endforeach
            <button class="mfxmq-flip" x-on:click="rev = !rev">تعویض جهت</button>
        </div>

        <style>
        .mfxmq-row { overflow: clip; }
        .mfxmq-track { display: flex; inline-size: max-content;
                       animation: mfxmq-run var(--mfxmq-dur) linear infinite; }
        .mfxmq-half { display: flex; gap: 1rem; padding-inline-end: 1rem; }
        .mfxmq-row:hover .mfxmq-track { animation-play-state: paused; }
        @keyframes mfxmq-run { to { translate: -50% 0; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="mfxmq" x-data="{ rev: false }" x-bind:data-rev="rev ? 'yes' : 'no'">
        @foreach ([['slow', 'normal'], ['fast', 'reverse'], ['mid', 'normal']] as [$speed, $base])
        <div class="mfxmq-row" data-speed="{{ $speed }}" data-base="{{ $base }}">
        <div class="mfxmq-track">
        <div class="mfxmq-half">
        @foreach ($quotes as $q)
        <figure class="mfxmq-card">
        <span class="mfxmq-avatar">{{ $q['initials'] }}</span>
        <blockquote>{{ $q['line'] }}</blockquote>
        <figcaption>{{ $q['name'] }}</figcaption>
        </figure>
        @endforeach
        </div>
        <div class="mfxmq-half" aria-hidden="true"> … the same cards … </div>
        </div>
        </div>
        @endforeach
        <button class="mfxmq-flip" x-on:click="rev = !rev">Flip direction</button>
        </div>

        <style>
        .mfxmq-row { overflow: clip; }
        .mfxmq-track { display: flex; inline-size: max-content;
                       animation: mfxmq-run var(--mfxmq-dur) linear infinite; }
        .mfxmq-half { display: flex; gap: 1rem; padding-inline-end: 1rem; }
        .mfxmq-row:hover .mfxmq-track { animation-play-state: paused; }
        @keyframes mfxmq-run { to { translate: -50% 0; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Wall.jsx — Inertia (React) page; renders the React snippet
        // below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import MarqueeWall from '@/components/MarqueeWall';

        export default function Wall() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <MarqueeWall />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // MarqueeWall.jsx — three testimonial rows, hover-paused, one flip button
        import { useState, type CSSProperties } from 'react';
        import '@nabuxai/ui-core/css';

        const QUOTES = [
          { id: 1, initials: 'LK', name: 'Lena · Berlin', line: 'The dashboard loads before my coffee.' },
          { id: 2, initials: 'RO', name: 'Ravi · Singapore', line: 'Warehouse sync just works, every 15 minutes.' },
          { id: 3, initials: 'AM', name: 'Aoife · Dublin', line: 'Replay found the bug in one watch.' },
          { id: 4, initials: 'JT', name: 'Jonas · Toronto', line: 'Guardrails saved the Q3 budget.' },
          { id: 5, initials: 'MS', name: 'Mei · Osaka', line: 'Latency from here? Twenty-six ms.' },
          { id: 6, initials: 'CR', name: 'Cristina · Lisbon', line: 'Our whole team reads it daily now.' },
        ];
        const ROWS = [
          { id: 'slow', dur: '38s', base: 'normal' },
          { id: 'fast', dur: '26s', base: 'reverse' },
          { id: 'mid', dur: '32s', base: 'normal' },
        ];

        export default function MarqueeWall() {
          const [rev, setRev] = useState(false);
          const half = (hidden: boolean) => (
            <div className="mfxmq-half" aria-hidden={hidden || undefined}>
              {QUOTES.map((q) => (
                <figure className="mfxmq-card" key={q.id + (hidden ? 'b' : 'a')}>
                  <span className="mfxmq-avatar">{q.initials}</span>
                  <blockquote>{q.line}</blockquote>
                  <figcaption>{q.name}</figcaption>
                </figure>
              ))}
            </div>
          );

          return (
            <>
              <style>{`
                .mfxmq-row { overflow: clip; }
                .mfxmq-track { display: flex; inline-size: max-content;
                               animation: mfxmq-run var(--mfxmq-dur) linear infinite; }
                .mfxmq-half { display: flex; gap: 1rem; padding-inline-end: 1rem; }
                .mfxmq-row:hover .mfxmq-track, .mfxmq-row:focus-within .mfxmq-track { animation-play-state: paused; }
                .mfxmq-card { display: grid; gap: .35rem; inline-size: 18rem; margin: 0; padding: 1rem;
                              border: 1px solid #d9dde4; border-radius: 1rem; background: #fff;
                              font: 400 .85rem/1.5 system-ui; }
                .mfxmq-avatar { display: grid; place-items: center; inline-size: 2.25rem; aspect-ratio: 1;
                                border-radius: 50%; background: #4f46e5; color: #fff; font-weight: 700; }
                .mfxmq-card figcaption { color: #5c6270; font-size: .75rem; }
                .mfxmq-flip { border: 1px solid #d9dde4; border-radius: 999px; padding: .6rem 1.2rem;
                              background: #fff; cursor: pointer; }
                @keyframes mfxmq-run { to { translate: -50% 0; } }
                @media (prefers-reduced-motion: reduce) { .mfxmq-track { animation: none; } }
              `}</style>
              {ROWS.map((row) => (
                // the flip XORs each row’s base direction
                <div className="mfxmq-row" key={row.id}
                  style={{ '--mfxmq-dur': row.dur,
                           animationDirection: (row.base === 'reverse') !== rev ? 'reverse' : 'normal' } as CSSProperties}>
                  <div className="mfxmq-track">
                    {half(false)}
                    {half(true)}
                  </div>
                </div>
              ))}
              <button type="button" className="mfxmq-flip" onClick={() => setRev(!rev)}>Flip direction</button>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- MarqueeWall.vue — three testimonial rows, hover-paused, one flip button -->
        <script setup lang="ts">
        import { ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const quotes = [
          { id: 1, initials: 'LK', name: 'Lena · Berlin', line: 'The dashboard loads before my coffee.' },
          { id: 2, initials: 'RO', name: 'Ravi · Singapore', line: 'Warehouse sync just works, every 15 minutes.' },
          { id: 3, initials: 'AM', name: 'Aoife · Dublin', line: 'Replay found the bug in one watch.' },
          { id: 4, initials: 'JT', name: 'Jonas · Toronto', line: 'Guardrails saved the Q3 budget.' },
          { id: 5, initials: 'MS', name: 'Mei · Osaka', line: 'Latency from here? Twenty-six ms.' },
          { id: 6, initials: 'CR', name: 'Cristina · Lisbon', line: 'Our whole team reads it daily now.' },
        ];
        const rows = [
          { id: 'slow', dur: '38s', base: 'normal' },
          { id: 'fast', dur: '26s', base: 'reverse' },
          { id: 'mid', dur: '32s', base: 'normal' },
        ];
        const rev = ref(false);
        // the flip XORs each row’s base direction
        const dir = (base: string) => (base === 'reverse') !== rev.value ? 'reverse' : 'normal';
        const rowStyle = (row: { dur: string; base: string }) =>
          ({ '--mfxmq-dur': row.dur, animationDirection: dir(row.base) }) as Record<string, string>;
        </script>

        <template>
          <div v-for="row in rows" :key="row.id" class="mfxmq-row" :style="rowStyle(row)">
            <div class="mfxmq-track">
              <div class="mfxmq-half">
                <figure v-for="q in quotes" :key="q.id" class="mfxmq-card">
                  <span class="mfxmq-avatar">{{ q.initials }}</span>
                  <blockquote>{{ q.line }}</blockquote>
                  <figcaption>{{ q.name }}</figcaption>
                </figure>
              </div>
              <div class="mfxmq-half" aria-hidden="true">
                <figure v-for="q in quotes" :key="'b' + q.id" class="mfxmq-card">
                  <span class="mfxmq-avatar">{{ q.initials }}</span>
                  <blockquote>{{ q.line }}</blockquote>
                  <figcaption>{{ q.name }}</figcaption>
                </figure>
              </div>
            </div>
          </div>
          <button type="button" class="mfxmq-flip" @click="rev = !rev">Flip direction</button>
        </template>

        <style scoped>
        .mfxmq-row { overflow: clip; }
        .mfxmq-track { display: flex; inline-size: max-content;
                       animation: mfxmq-run var(--mfxmq-dur) linear infinite; }
        .mfxmq-half { display: flex; gap: 1rem; padding-inline-end: 1rem; }
        .mfxmq-row:hover :deep(.mfxmq-track), .mfxmq-row:focus-within :deep(.mfxmq-track) { animation-play-state: paused; }
        .mfxmq-card { display: grid; gap: .35rem; inline-size: 18rem; margin: 0; padding: 1rem;
                      border: 1px solid #d9dde4; border-radius: 1rem; background: #fff;
                      font: 400 .85rem/1.5 system-ui; }
        .mfxmq-avatar { display: grid; place-items: center; inline-size: 2.25rem; aspect-ratio: 1;
                        border-radius: 50%; background: #4f46e5; color: #fff; font-weight: 700; }
        .mfxmq-card :deep(figcaption) { color: #5c6270; font-size: .75rem; }
        .mfxmq-flip { border: 1px solid #d9dde4; border-radius: 999px; padding: .6rem 1.2rem;
                      background: #fff; cursor: pointer; }
        @keyframes mfxmq-run { to { translate: -50% 0; } }
        @media (prefers-reduced-motion: reduce) { .mfxmq-track { animation: none; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- MarqueeWall.svelte — three testimonial rows, hover-paused, one flip button -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const quotes = [
            { id: 1, initials: 'LK', name: 'Lena · Berlin', line: 'The dashboard loads before my coffee.' },
            { id: 2, initials: 'RO', name: 'Ravi · Singapore', line: 'Warehouse sync just works, every 15 minutes.' },
            { id: 3, initials: 'AM', name: 'Aoife · Dublin', line: 'Replay found the bug in one watch.' },
            { id: 4, initials: 'JT', name: 'Jonas · Toronto', line: 'Guardrails saved the Q3 budget.' },
            { id: 5, initials: 'MS', name: 'Mei · Osaka', line: 'Latency from here? Twenty-six ms.' },
            { id: 6, initials: 'CR', name: 'Cristina · Lisbon', line: 'Our whole team reads it daily now.' },
          ];
          const rows = [
            { id: 'slow', dur: '38s', base: 'normal' },
            { id: 'fast', dur: '26s', base: 'reverse' },
            { id: 'mid', dur: '32s', base: 'normal' },
          ];
          let rev = $state(false);
          // the flip XORs each row’s base direction
          const dir = (base: string) => ((base === 'reverse') !== rev ? 'reverse' : 'normal');
        </script>

        {#each rows as row (row.id)}
          <div class="mfxmq-row" style={`--mfxmq-dur: ${row.dur}; animation-direction: ${dir(row.base)};`}>
            <div class="mfxmq-track">
              <div class="mfxmq-half">
                {#each quotes as q (q.id)}
                  <figure class="mfxmq-card">
                    <span class="mfxmq-avatar">{q.initials}</span>
                    <blockquote>{q.line}</blockquote>
                    <figcaption>{q.name}</figcaption>
                  </figure>
                {/each}
              </div>
              <div class="mfxmq-half" aria-hidden="true">
                {#each quotes as q ('b' + q.id)}
                  <figure class="mfxmq-card">
                    <span class="mfxmq-avatar">{q.initials}</span>
                    <blockquote>{q.line}</blockquote>
                    <figcaption>{q.name}</figcaption>
                  </figure>
                {/each}
              </div>
            </div>
          </div>
        {/each}
        <button type="button" class="mfxmq-flip" onclick={() => (rev = !rev)}>Flip direction</button>

        <style>
          .mfxmq-row { overflow: clip; }
          .mfxmq-track { display: flex; inline-size: max-content;
                         animation: mfxmq-run var(--mfxmq-dur) linear infinite; }
          .mfxmq-half { display: flex; gap: 1rem; padding-inline-end: 1rem; }
          .mfxmq-row:hover .mfxmq-track, .mfxmq-row:focus-within .mfxmq-track { animation-play-state: paused; }
          .mfxmq-card { display: grid; gap: .35rem; inline-size: 18rem; margin: 0; padding: 1rem;
                        border: 1px solid #d9dde4; border-radius: 1rem; background: #fff;
                        font: 400 .85rem/1.5 system-ui; }
          .mfxmq-avatar { display: grid; place-items: center; inline-size: 2.25rem; aspect-ratio: 1;
                          border-radius: 50%; background: #4f46e5; color: #fff; font-weight: 700; }
          .mfxmq-card figcaption { color: #5c6270; font-size: .75rem; }
          .mfxmq-flip { border: 1px solid #d9dde4; border-radius: 999px; padding: .6rem 1.2rem;
                        background: #fff; cursor: pointer; }
          @keyframes mfxmq-run { to { translate: -50% 0; } }
          @media (prefers-reduced-motion: reduce) { .mfxmq-track { animation: none; } }
        </style>
        SVELTE,
        ],
    ],

    'timeline-scene' => [
        'title' => ['fa' => 'صحنهٔ زمان‌بندی‌شده', 'en' => 'Timeline scene'],
        'icon' => 'play',
        'oneLiner' => [
            'fa' => 'یک دکمهٔ «پخش» و کل روایت محصول پله‌پله جان می‌گیرد: ابرو می‌آید، تیتر از پایین با فنر می‌نشیند، آیتم‌ها با stagger جهش می‌کنند، خط زیر تیتر خودش را رسم می‌کند و دکمهٔ اقدام آخر از همه با یک پاپ فنری می‌نشیند — ری‌پلی هر چند بار که بخواهید.',
            'en' => 'One “play” button and the whole product story comes alive step by step: the eyebrow drops in, the title settles on a spring, items jump in with stagger, the line under the title draws itself and the action button pops in last on a springy overshoot — replay as many times as you like.',
        ],
        'js' => true,
        'docs' => 'https://kinetics.colorion.co',
        'props' => [
            ['name' => 'steps', 'type' => 'timeline', 'default' => "'4 · 0.05 → 1.05s'", 'note' => [
                'fa' => 'چهار پله با تأخیرهای چیده‌شده: ورود تیتر، جهش آیتم‌ها، رسم خط و پاپ دکمه؛ ریل پایین صحنه همین ترتیب را نشان می‌دهد.',
                'en' => 'Four steps with choreographed delays: title entrance, item hops, the line draw and the button pop; the rail under the scene mirrors that same order.',
            ]],
            ['name' => 'ease', 'type' => 'cubic-bezier', 'default' => "'(.34, 1.56, .64, 1)'", 'note' => [
                'fa' => 'منحنی فنری anime.js-وار که کمی از هدف می‌گذرد و برمی‌گردد — روح این صحنه.',
                'en' => 'The anime.js-style spring that overshoots and settles back — this scene’s soul.',
            ]],
            ['name' => 'pathLength', 'type' => 'number', 'default' => "'100'", 'note' => [
                'fa' => 'خط SVG با pathLength=۱۰۰ نرمال شده و رسمش فقط جابه‌جایی stroke-dashoffset از ۱۰۰ به صفر است.',
                'en' => 'The SVG line is normalised with pathLength=100, so drawing it is just stroke-dashoffset sliding from 100 to 0.',
            ]],
            ['name' => 'replay', 'type' => 'action', 'default' => "'reset + rerun'", 'note' => [
                'fa' => 'دکمهٔ ری‌پلی صحنه را به حالت صفر برمی‌گرداند، یک فریم می‌ایستد تا استایل بنشیند و دوباره از همان پلهٔ اول پخش می‌کند.',
                'en' => 'Replay drops the scene back to zero, waits one frame for styles to settle and plays it again from the very first step.',
            ]],
            ['name' => 'prefers-reduced-motion', 'type' => 'behaviour', 'default' => "'final'", 'note' => [
                'fa' => 'بدون حرکت، صحنه بی‌درنگ در حالت نهایی و کامل دیده می‌شود.',
                'en' => 'With reduced motion the scene shows its complete final state immediately.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="mfxs" x-data="timelineScene()" x-bind:data-phase="phase">
            <div class="mfxs-stage">
                <p class="mfxs-eyebrow">یادداشت انتشار · ۳.۰</p>
                <h3 class="mfxs-title">Orbit 3.0</h3>
                <svg class="mfxs-line" viewBox="0 0 320 24"><path pathLength="100" d="M4 16 C 80 4 240 4 316 14" /></svg>
                <ul class="mfxs-items">
                    <li>۴۲٪ سریع‌تر</li><li>همگام‌سازی آفلاین</li><li>۴ زبان تازه</li>
                </ul>
                <button class="mfxs-cta">شروع Orbit 3.0</button>
            </div>
            <button class="mfxs-play" x-on:click="play()" x-text="phase === 'idle' ? 'پخش' : 'ری‌پلی'"></button>
        </div>

        <style>
        .mfxs-eyebrow, .mfxs-title, .mfxs-items li, .mfxs-cta { opacity: 0; translate: 0 18px; scale: .92;
            transition: all .6s cubic-bezier(.34, 1.56, .64, 1); }
        .mfxs[data-phase='run'] .mfxs-eyebrow { opacity: 1; translate: none; scale: 1; transition-delay: .05s; }
        .mfxs[data-phase='run'] .mfxs-title { opacity: 1; translate: none; scale: 1; transition-delay: .18s; }
        .mfxs[data-phase='run'] .mfxs-items li { opacity: 1; translate: none; scale: 1; }
        .mfxs[data-phase='run'] .mfxs-items li:nth-child(1) { transition-delay: .42s; }
        .mfxs[data-phase='run'] .mfxs-items li:nth-child(2) { transition-delay: .54s; }
        .mfxs[data-phase='run'] .mfxs-items li:nth-child(3) { transition-delay: .66s; }
        .mfxs[data-phase='run'] .mfxs-cta { opacity: 1; translate: none; scale: 1; transition-delay: 1.05s; }
        .mfxs-line path { fill: none; stroke: #6366f1; stroke-width: 2.5; stroke-linecap: round;
                          stroke-dasharray: 100; stroke-dashoffset: 100; transition: stroke-dashoffset .9s ease-out .8s; }
        .mfxs[data-phase='run'] .mfxs-line path { stroke-dashoffset: 0; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="mfxs" x-data="timelineScene()" x-bind:data-phase="phase">
        <div class="mfxs-stage">
        <p class="mfxs-eyebrow">Release notes · 3.0</p>
        <h3 class="mfxs-title">Orbit 3.0</h3>
        <svg class="mfxs-line" viewBox="0 0 320 24"><path pathLength="100" d="M4 16 C 80 4 240 4 316 14" /></svg>
        <ul class="mfxs-items">
        <li>42% faster</li><li>Offline sync</li><li>4 new languages</li>
        </ul>
        <button class="mfxs-cta">Start Orbit 3.0</button>
        </div>
        <button class="mfxs-play" x-on:click="play()" x-text="phase === 'idle' ? 'Play' : 'Replay'"></button>
        </div>

        <style>
        .mfxs-eyebrow, .mfxs-title, .mfxs-items li, .mfxs-cta { opacity: 0; translate: 0 18px; scale: .92;
            transition: all .6s cubic-bezier(.34, 1.56, .64, 1); }
        .mfxs[data-phase='run'] .mfxs-eyebrow { opacity: 1; translate: none; scale: 1; transition-delay: .05s; }
        .mfxs[data-phase='run'] .mfxs-title { opacity: 1; translate: none; scale: 1; transition-delay: .18s; }
        .mfxs[data-phase='run'] .mfxs-items li { opacity: 1; translate: none; scale: 1; }
        .mfxs[data-phase='run'] .mfxs-items li:nth-child(1) { transition-delay: .42s; }
        .mfxs[data-phase='run'] .mfxs-items li:nth-child(2) { transition-delay: .54s; }
        .mfxs[data-phase='run'] .mfxs-items li:nth-child(3) { transition-delay: .66s; }
        .mfxs[data-phase='run'] .mfxs-cta { opacity: 1; translate: none; scale: 1; transition-delay: 1.05s; }
        .mfxs-line path { fill: none; stroke: #6366f1; stroke-width: 2.5; stroke-linecap: round;
                          stroke-dasharray: 100; stroke-dashoffset: 100; transition: stroke-dashoffset .9s ease-out .8s; }
        .mfxs[data-phase='run'] .mfxs-line path { stroke-dashoffset: 0; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Launch.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import LaunchScene from '@/components/LaunchScene';

        export default function Launch() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <LaunchScene />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // LaunchScene.jsx — a staged product intro with spring easing and stagger
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const ITEMS = ['42% faster', 'Offline sync', '4 new languages'];
        const SPRING = 'cubic-bezier(.34, 1.56, .64, 1)';
        const STEP_AT = [180, 420, 800, 1050];   // when each rail dot lights up

        export default function LaunchScene() {
          const [phase, setPhase] = useState('idle');   // idle → run → done
          const [step, setStep] = useState(0);
          const timers = useRef<number[]>([]);

          useEffect(() => () => timers.current.forEach(clearTimeout), []);  // cleanup

          const play = () => {
            timers.current.forEach(clearTimeout);
            timers.current = [];
            setStep(0);
            setPhase('idle');
            // one frame at zero so the styles settle, then run the timeline
            requestAnimationFrame(() => requestAnimationFrame(() => {
              setPhase('run');
              STEP_AT.forEach((ms, i) => {
                timers.current.push(window.setTimeout(() => setStep(i + 1), ms));
              });
              timers.current.push(window.setTimeout(() => setPhase('done'), 1900));
            }));
          };

          return (
            <>
              <style>{`
                .mfxs-stage { display: grid; gap: .9rem; justify-items: center; text-align: center;
                              padding: 2.5rem 1.5rem; border: 1px solid #d9dde4; border-radius: 1.25rem;
                              background: #fafafa; max-inline-size: 34rem; }
                .mfxs-eyebrow { margin: 0; font: 600 .72rem/1 system-ui; letter-spacing: .14em;
                                text-transform: uppercase; color: #5c6270; }
                .mfxs-title { margin: 0; font: 800 clamp(2rem, 6vw, 3rem)/1.1 system-ui; color: #16181d; }
                .mfxs-piece { opacity: 0; translate: 0 18px; scale: .92;
                              transition: all .6s ${SPRING}; }
                .mfxs-stage[data-phase='run'] .mfxs-piece, .mfxs-stage[data-phase='done'] .mfxs-piece {
                  opacity: 1; translate: none; scale: 1; }
                .mfxs-stage[data-phase='run'] .mfxs-eyebrow, .mfxs-stage[data-phase='done'] .mfxs-eyebrow { transition-delay: .05s; }
                .mfxs-stage[data-phase='run'] .mfxs-title, .mfxs-stage[data-phase='done'] .mfxs-title { transition-delay: .18s; }
                .mfxs-items { list-style: none; display: flex; flex-wrap: wrap; gap: .5rem; margin: 0; padding: 0; }
                .mfxs-items li { padding: .45rem .9rem; border: 1px solid #d9dde4; border-radius: 999px;
                                 background: #fff; font: 600 .8rem/1 system-ui; }
                .mfxs-stage[data-phase='run'] .mfxs-items li:nth-child(1), .mfxs-stage[data-phase='done'] .mfxs-items li:nth-child(1) { transition-delay: .42s; }
                .mfxs-stage[data-phase='run'] .mfxs-items li:nth-child(2), .mfxs-stage[data-phase='done'] .mfxs-items li:nth-child(2) { transition-delay: .54s; }
                .mfxs-stage[data-phase='run'] .mfxs-items li:nth-child(3), .mfxs-stage[data-phase='done'] .mfxs-items li:nth-child(3) { transition-delay: .66s; }
                .mfxs-line { inline-size: min(18rem, 70vw); }
                .mfxs-line path { fill: none; stroke: #6366f1; stroke-width: 2.5; stroke-linecap: round;
                                  stroke-dasharray: 100; stroke-dashoffset: 100;
                                  transition: stroke-dashoffset .9s ease-out .8s; }
                .mfxs-stage[data-phase='run'] .mfxs-line path, .mfxs-stage[data-phase='done'] .mfxs-line path { stroke-dashoffset: 0; }
                .mfxs-cta { border: 0; border-radius: 999px; padding: .85rem 1.6rem; background: #16181d; color: #fff;
                            font: 700 .9rem/1 system-ui; cursor: pointer; }
                .mfxs-stage[data-phase='run'] .mfxs-cta, .mfxs-stage[data-phase='done'] .mfxs-cta { transition-delay: 1.05s; }
                .mfxs-rail { display: flex; gap: .9rem; margin-block-start: 1rem; }
                .mfxs-dot { inline-size: .6rem; aspect-ratio: 1; border-radius: 50%; background: #d9dde4;
                            transition: background-color .2s, scale .3s ${SPRING}; }
                .mfxs-dot[data-on] { background: #6366f1; scale: 1.35; }
                .mfxs-play { margin-block-start: 1rem; border: 1px solid #6366f1; border-radius: 999px;
                             padding: .6rem 1.4rem; background: #fff; color: #6366f1; cursor: pointer; }
                @media (prefers-reduced-motion: reduce) {
                  .mfxs-piece, .mfxs-line path { transition: none; opacity: 1; translate: none; scale: 1; stroke-dashoffset: 0; }
                }
              `}</style>
              <div className="mfxs-stage" data-phase={phase}>
                <p className="mfxs-piece mfxs-eyebrow">Release notes · 3.0</p>
                <h3 className="mfxs-piece mfxs-title">Orbit 3.0</h3>
                <svg className="mfxs-line" viewBox="0 0 320 24" aria-hidden="true">
                  <path pathLength={100} d="M4 16 C 80 4 240 4 316 14" />
                </svg>
                <ul className="mfxs-items">
                  {ITEMS.map((t) => <li className="mfxs-piece" key={t}>{t}</li>)}
                </ul>
                <button type="button" className="mfxs-piece mfxs-cta">Start Orbit 3.0</button>
                <div className="mfxs-rail" aria-hidden="true">
                  {[0, 1, 2, 3].map((i) => <span className="mfxs-dot" key={i} data-on={step > i || undefined} />)}
                </div>
              </div>
              <button type="button" className="mfxs-play" onClick={play}>
                {phase === 'idle' ? 'Play' : 'Replay'}
              </button>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- LaunchScene.vue — a staged product intro with spring easing and stagger -->
        <script setup lang="ts">
        import { onBeforeUnmount, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const items = ['42% faster', 'Offline sync', '4 new languages'];
        const stepAt = [180, 420, 800, 1050];   // when each rail dot lights up
        const phase = ref('idle');               // idle → run → done
        const step = ref(0);
        let timers: number[] = [];

        const play = () => {
          timers.forEach(clearTimeout);
          timers = [];
          step.value = 0;
          phase.value = 'idle'
          // one frame at zero so the styles settle, then run the timeline
          requestAnimationFrame(() => requestAnimationFrame(() => {
            phase.value = 'run'
            stepAt.forEach((ms, i) => { timers.push(window.setTimeout(() => (step.value = i + 1), ms)) })
            timers.push(window.setTimeout(() => (phase.value = 'done'), 1900))
          }))
        };
        onBeforeUnmount(() => timers.forEach(clearTimeout));   // cleanup
        </script>

        <template>
          <div class="mfxs-stage" :data-phase="phase">
            <p class="mfxs-piece mfxs-eyebrow">Release notes · 3.0</p>
            <h3 class="mfxs-piece mfxs-title">Orbit 3.0</h3>
            <svg class="mfxs-line" viewBox="0 0 320 24" aria-hidden="true">
              <path pathLength="100" d="M4 16 C 80 4 240 4 316 14" />
            </svg>
            <ul class="mfxs-items">
              <li v-for="t in items" :key="t" class="mfxs-piece">{{ t }}</li>
            </ul>
            <button type="button" class="mfxs-piece mfxs-cta">Start Orbit 3.0</button>
            <div class="mfxs-rail" aria-hidden="true">
              <span v-for="i in 4" :key="i" class="mfxs-dot" :data-on="step >= i || undefined"></span>
            </div>
          </div>
          <button type="button" class="mfxs-play" @click="play">
            {{ phase === 'idle' ? 'Play' : 'Replay' }}
          </button>
        </template>

        <style scoped>
        .mfxs-stage { display: grid; gap: .9rem; justify-items: center; text-align: center;
                      padding: 2.5rem 1.5rem; border: 1px solid #d9dde4; border-radius: 1.25rem;
                      background: #fafafa; max-inline-size: 34rem; }
        .mfxs-eyebrow { margin: 0; font: 600 .72rem/1 system-ui; letter-spacing: .14em;
                        text-transform: uppercase; color: #5c6270; }
        .mfxs-title { margin: 0; font: 800 clamp(2rem, 6vw, 3rem)/1.1 system-ui; color: #16181d; }
        .mfxs-piece { opacity: 0; translate: 0 18px; scale: .92;
                      transition: all .6s cubic-bezier(.34, 1.56, .64, 1); }
        .mfxs-stage:is([data-phase='run'], [data-phase='done']) :deep(.mfxs-piece) { opacity: 1; translate: none; scale: 1; }
        .mfxs-stage:is([data-phase='run'], [data-phase='done']) :deep(.mfxs-eyebrow) { transition-delay: .05s; }
        .mfxs-stage:is([data-phase='run'], [data-phase='done']) :deep(.mfxs-title) { transition-delay: .18s; }
        .mfxs-items { list-style: none; display: flex; flex-wrap: wrap; gap: .5rem; margin: 0; padding: 0; }
        .mfxs-items :deep(li) { padding: .45rem .9rem; border: 1px solid #d9dde4; border-radius: 999px;
                                background: #fff; font: 600 .8rem/1 system-ui; }
        .mfxs-stage:is([data-phase='run'], [data-phase='done']) .mfxs-items :deep(li:nth-child(1)) { transition-delay: .42s; }
        .mfxs-stage:is([data-phase='run'], [data-phase='done']) .mfxs-items :deep(li:nth-child(2)) { transition-delay: .54s; }
        .mfxs-stage:is([data-phase='run'], [data-phase='done']) .mfxs-items :deep(li:nth-child(3)) { transition-delay: .66s; }
        .mfxs-line { inline-size: min(18rem, 70vw); }
        .mfxs-line :deep(path) { fill: none; stroke: #6366f1; stroke-width: 2.5; stroke-linecap: round;
                                 stroke-dasharray: 100; stroke-dashoffset: 100;
                                 transition: stroke-dashoffset .9s ease-out .8s; }
        .mfxs-stage:is([data-phase='run'], [data-phase='done']) .mfxs-line :deep(path) { stroke-dashoffset: 0; }
        .mfxs-cta { border: 0; border-radius: 999px; padding: .85rem 1.6rem; background: #16181d; color: #fff;
                    font: 700 .9rem/1 system-ui; cursor: pointer; }
        .mfxs-stage:is([data-phase='run'], [data-phase='done']) :deep(.mfxs-cta) { transition-delay: 1.05s; }
        .mfxs-rail { display: flex; gap: .9rem; margin-block-start: 1rem; }
        .mfxs-dot { inline-size: .6rem; aspect-ratio: 1; border-radius: 50%; background: #d9dde4;
                    transition: background-color .2s, scale .3s cubic-bezier(.34, 1.56, .64, 1); }
        .mfxs-dot[data-on] { background: #6366f1; scale: 1.35; }
        .mfxs-play { margin-block-start: 1rem; border: 1px solid #6366f1; border-radius: 999px;
                     padding: .6rem 1.4rem; background: #fff; color: #6366f1; cursor: pointer; }
        @media (prefers-reduced-motion: reduce) {
          .mfxs-piece, .mfxs-line :deep(path) { transition: none; opacity: 1; translate: none; scale: 1; stroke-dashoffset: 0; }
        }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- LaunchScene.svelte — a staged product intro with spring easing and stagger -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          const items = ['42% faster', 'Offline sync', '4 new languages'];
          const stepAt = [180, 420, 800, 1050];   // when each rail dot lights up
          let phase = $state('idle');               // idle → run → done
          let step = $state(0);
          let timers: number[] = [];

          const play = () => {
            timers.forEach(clearTimeout);
            timers = [];
            step = 0;
            phase = 'idle';
            // one frame at zero so the styles settle, then run the timeline
            requestAnimationFrame(() => requestAnimationFrame(() => {
              phase = 'run';
              stepAt.forEach((ms, i) => { timers.push(window.setTimeout(() => (step = i + 1), ms)); });
              timers.push(window.setTimeout(() => (phase = 'done'), 1900));
            }));
          };

          onMount(() => () => timers.forEach(clearTimeout));   // cleanup
        </script>

        <div class="mfxs-stage" data-phase={phase}>
          <p class="mfxs-piece mfxs-eyebrow">Release notes · 3.0</p>
          <h3 class="mfxs-piece mfxs-title">Orbit 3.0</h3>
          <svg class="mfxs-line" viewBox="0 0 320 24" aria-hidden="true">
            <path pathLength={100} d="M4 16 C 80 4 240 4 316 14" />
          </svg>
          <ul class="mfxs-items">
            {#each items as t (t)}
              <li class="mfxs-piece">{t}</li>
            {/each}
          </ul>
          <button type="button" class="mfxs-piece mfxs-cta">Start Orbit 3.0</button>
          <div class="mfxs-rail" aria-hidden="true">
            {#each [1, 2, 3, 4] as i (i)}
              <span class="mfxs-dot" data-on={step >= i || undefined}></span>
            {/each}
          </div>
        </div>
        <button type="button" class="mfxs-play" onclick={play}>
          {phase === 'idle' ? 'Play' : 'Replay'}
        </button>

        <style>
          .mfxs-stage { display: grid; gap: .9rem; justify-items: center; text-align: center;
                        padding: 2.5rem 1.5rem; border: 1px solid #d9dde4; border-radius: 1.25rem;
                        background: #fafafa; max-inline-size: 34rem; }
          .mfxs-eyebrow { margin: 0; font: 600 .72rem/1 system-ui; letter-spacing: .14em;
                          text-transform: uppercase; color: #5c6270; }
          .mfxs-title { margin: 0; font: 800 clamp(2rem, 6vw, 3rem)/1.1 system-ui; color: #16181d; }
          .mfxs-piece { opacity: 0; translate: 0 18px; scale: .92;
                        transition: all .6s cubic-bezier(.34, 1.56, .64, 1); }
          .mfxs-stage:is([data-phase='run'], [data-phase='done']) .mfxs-piece { opacity: 1; translate: none; scale: 1; }
          .mfxs-stage:is([data-phase='run'], [data-phase='done']) .mfxs-eyebrow { transition-delay: .05s; }
          .mfxs-stage:is([data-phase='run'], [data-phase='done']) .mfxs-title { transition-delay: .18s; }
          .mfxs-items { list-style: none; display: flex; flex-wrap: wrap; gap: .5rem; margin: 0; padding: 0; }
          .mfxs-items li { padding: .45rem .9rem; border: 1px solid #d9dde4; border-radius: 999px;
                           background: #fff; font: 600 .8rem/1 system-ui; }
          .mfxs-stage:is([data-phase='run'], [data-phase='done']) .mfxs-items li:nth-child(1) { transition-delay: .42s; }
          .mfxs-stage:is([data-phase='run'], [data-phase='done']) .mfxs-items li:nth-child(2) { transition-delay: .54s; }
          .mfxs-stage:is([data-phase='run'], [data-phase='done']) .mfxs-items li:nth-child(3) { transition-delay: .66s; }
          .mfxs-line { inline-size: min(18rem, 70vw); }
          .mfxs-line path { fill: none; stroke: #6366f1; stroke-width: 2.5; stroke-linecap: round;
                            stroke-dasharray: 100; stroke-dashoffset: 100;
                            transition: stroke-dashoffset .9s ease-out .8s; }
          .mfxs-stage:is([data-phase='run'], [data-phase='done']) .mfxs-line path { stroke-dashoffset: 0; }
          .mfxs-cta { border: 0; border-radius: 999px; padding: .85rem 1.6rem; background: #16181d; color: #fff;
                      font: 700 .9rem/1 system-ui; cursor: pointer; }
          .mfxs-stage:is([data-phase='run'], [data-phase='done']) .mfxs-cta { transition-delay: 1.05s; }
          .mfxs-rail { display: flex; gap: .9rem; margin-block-start: 1rem; }
          .mfxs-dot { inline-size: .6rem; aspect-ratio: 1; border-radius: 50%; background: #d9dde4;
                      transition: background-color .2s, scale .3s cubic-bezier(.34, 1.56, .64, 1); }
          .mfxs-dot[data-on] { background: #6366f1; scale: 1.35; }
          .mfxs-play { margin-block-start: 1rem; border: 1px solid #6366f1; border-radius: 999px;
                       padding: .6rem 1.4rem; background: #fff; color: #6366f1; cursor: pointer; }
          @media (prefers-reduced-motion: reduce) {
            .mfxs-piece, .mfxs-line path { transition: none; opacity: 1; translate: none; scale: 1; stroke-dashoffset: 0; }
          }
        </style>
        SVELTE,
        ],
    ],
];
