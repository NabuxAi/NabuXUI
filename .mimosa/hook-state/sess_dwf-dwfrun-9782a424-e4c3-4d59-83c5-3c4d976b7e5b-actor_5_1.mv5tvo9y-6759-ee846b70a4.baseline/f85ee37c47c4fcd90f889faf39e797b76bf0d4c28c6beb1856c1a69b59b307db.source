<?php

/**
 * Demo manifest of the "icon-motion" group — animated icons as the backbone,
 * not the garnish: Lucide-style icons that ink themselves stroke by stroke on
 * entry, Lordicon-style state morphs on real buttons, looping working icons
 * inside live controls, icon micro-controls on native inputs, a self-drawing
 * SVG scene, a searchable icon browser and a showcase that gathers the whole
 * vocabulary. Scenarios live at
 * resources/views/demos/components/icon-motion/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'آیکون‌های متحرک', 'en' => 'Animated icons'],

    'draw-entrance' => [
        'title' => ['fa' => 'ورود ترسیمی', 'en' => 'Draw-in entrance'],
        'icon' => 'wand',
        'oneLiner' => [
            'fa' => 'مجموعهٔ آیکون‌های خطی به سبک لوسید که با ورود به دید، خط‌به‌خط خودشان را می‌کشند — قلم با تناوب پله‌ای از مسیری به مسیر بعد می‌جهد، هاور هر آیکون فقط همان را دوباره می‌کشد و کل مجموعه با یک سوییچ از نو جوهر می‌گیرد.',
            'en' => 'A set of Lucide-style line icons that ink themselves stroke by stroke as they enter view — the pen hops from path to path with a stepped cadence, hovering replays a single icon, and one switch re-inks the whole set.',
        ],
        'js' => true,
        'docs' => 'https://lucide-animated.com',
        'props' => [
            ['name' => 'pathLength', 'type' => 'technique', 'default' => "'1'", 'note' => [
                'fa' => 'هر مسیر به طول ۱ نرمال می‌شود تا stroke-dasharray: 1 همان یک خط را بکشد؛ بدون محاسبهٔ طول واقعی.',
                'en' => 'Every path is normalised to length 1 so stroke-dasharray: 1 draws exactly that stroke — no measuring real lengths.',
            ]],
            ['name' => 'cadence', 'type' => 'ms', 'default' => "'130'", 'note' => [
                'fa' => 'فاصلهٔ پله‌ای بین خط‌های هر آیکون؛ خود کاشی‌ها هم ۶۰ms از هم عقب می‌افتند تا موج ترسیم روی گرید بساط داده شود.',
                'en' => 'The stepped gap between an icon’s strokes; tiles themselves stagger 60ms apart so a draw wave sweeps the grid.',
            ]],
            ['name' => 'draw', 'type' => 'duration', 'default' => "'550ms'", 'note' => [
                'fa' => 'زمان کشیدن هر خط با easing نرم؛ پاک‌شدن آنی است تا ری‌پلی تمیز شروع شود.',
                'en' => 'Each stroke’s inking time on a soft ease; erasing is instant so replays start clean.',
            ]],
            ['name' => 'trigger', 'type' => 'behaviour', 'default' => "'in-view · once'", 'note' => [
                'fa' => 'IntersectionObserver با آستانهٔ ۲۰٪ فقط یک‌بار شلیک می‌کند و خودش را قطع می‌کند؛ پاک‌سازی در destroy.',
                'en' => 'An IntersectionObserver at a 20% threshold fires once and disconnects itself; cleaned up on destroy.',
            ]],
            ['name' => 'reduced-motion', 'type' => 'media', 'default' => "'static'", 'note' => [
                'fa' => 'با prefers-reduced-motion همهٔ مسیرها از همان ابتدا کامل و ثابت دیده می‌شوند.',
                'en' => 'Under prefers-reduced-motion every path sits fully drawn and static from the start.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="imd" x-data="{
            redraw() {
                window.dispatchEvent(new CustomEvent('imd-erase'));
                requestAnimationFrame(() => requestAnimationFrame(
                    () => window.dispatchEvent(new CustomEvent('imd-draw'))));
            },
        }" x-init="
            const io = new IntersectionObserver((entries) => entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    window.dispatchEvent(new CustomEvent('imd-draw'));
                    io.disconnect();
                }
            }), { threshold: 0.2 });
            io.observe($refs.grid);
            $cleanup(() => io.disconnect());
        ">
            <ul class="imd-grid" x-ref="grid">
                @foreach ($icons as $i => $icon)
                    <li class="imd-tile"
                        x-data="{ d: false, replay() {
                            if (!this.d) return;
                            this.d = false;
                            requestAnimationFrame(() => requestAnimationFrame(() => (this.d = true)));
                        } }"
                        x-bind:data-drawn="d"
                        @imd-draw.window="d = true"
                        @imd-erase.window="d = false"
                        @pointerenter="replay()">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            @foreach ($icon['paths'] as $p => $d)
                                <path pathLength="1" d="{{ $d }}" style="--d: {{ $i * 60 + $p * 130 }}ms" />
                            @endforeach
                        </svg>
                        <span>{{ $icon['fa'] }}</span>
                    </li>
                @endforeach
            </ul>

            <button type="button" class="imd-again" x-on:click="redraw()">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>
                همه را دوباره بکش
            </button>
        </div>

        <style>
        .imd-grid { display: grid; gap: .75rem; margin: 0; padding: 0; list-style: none;
                    grid-template-columns: repeat(auto-fill, minmax(7.25rem, 1fr)); }
        .imd-tile { display: grid; gap: .55rem; justify-items: center; padding: 1rem .5rem;
                    border: 1px solid #e7e5e4; border-radius: .9rem; background: #fff; color: #1c1917; }
        .imd-tile svg { inline-size: 2.4rem; }
        .imd-tile path { fill: none; stroke: currentColor; stroke-width: 2;
                         stroke-linecap: round; stroke-linejoin: round;
                         stroke-dasharray: 1; stroke-dashoffset: 1;
                         transition: stroke-dashoffset .55s ease var(--d, 0s); }
        .imd-tile[data-drawn='true'] path { stroke-dashoffset: 0; }
        .imd-tile:not([data-drawn='true']) path { transition: none; }
        @media (prefers-reduced-motion: reduce) {
            .imd-tile path { transition: none; stroke-dashoffset: 0; }
        }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="imd" x-data="{
            redraw() {
                window.dispatchEvent(new CustomEvent('imd-erase'));
                requestAnimationFrame(() => requestAnimationFrame(
                    () => window.dispatchEvent(new CustomEvent('imd-draw'))));
            },
        }" x-init="
            const io = new IntersectionObserver((entries) => entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    window.dispatchEvent(new CustomEvent('imd-draw'));
                    io.disconnect();
                }
            }), { threshold: 0.2 });
            io.observe($refs.grid);
            $cleanup(() => io.disconnect());
        ">
            <ul class="imd-grid" x-ref="grid">
                @foreach ($icons as $i => $icon)
                    <li class="imd-tile"
                        x-data="{ d: false, replay() {
                            if (!this.d) return;
                            this.d = false;
                            requestAnimationFrame(() => requestAnimationFrame(() => (this.d = true)));
                        } }"
                        x-bind:data-drawn="d"
                        @imd-draw.window="d = true"
                        @imd-erase.window="d = false"
                        @pointerenter="replay()">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            @foreach ($icon['paths'] as $p => $d)
                                <path pathLength="1" d="{{ $d }}" style="--d: {{ $i * 60 + $p * 130 }}ms" />
                            @endforeach
                        </svg>
                        <span>{{ $icon['en'] }}</span>
                    </li>
                @endforeach
            </ul>

            <button type="button" class="imd-again" x-on:click="redraw()">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>
                Draw them all again
            </button>
        </div>

        <style>
        .imd-grid { display: grid; gap: .75rem; margin: 0; padding: 0; list-style: none;
                    grid-template-columns: repeat(auto-fill, minmax(7.25rem, 1fr)); }
        .imd-tile { display: grid; gap: .55rem; justify-items: center; padding: 1rem .5rem;
                    border: 1px solid #e7e5e4; border-radius: .9rem; background: #fff; color: #1c1917; }
        .imd-tile svg { inline-size: 2.4rem; }
        .imd-tile path { fill: none; stroke: currentColor; stroke-width: 2;
                         stroke-linecap: round; stroke-linejoin: round;
                         stroke-dasharray: 1; stroke-dashoffset: 1;
                         transition: stroke-dashoffset .55s ease var(--d, 0s); }
        .imd-tile[data-drawn='true'] path { stroke-dashoffset: 0; }
        .imd-tile:not([data-drawn='true']) path { transition: none; }
        @media (prefers-reduced-motion: reduce) {
            .imd-tile path { transition: none; stroke-dashoffset: 0; }
        }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/IconMotion.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import DrawEntrance from '@/components/DrawEntrance';

        export default function IconMotion() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <DrawEntrance />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // DrawEntrance.jsx — Lucide-style icons inking themselves on entry
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const ICONS = [
          { id: 'heart', name: 'Heart', paths: ['M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3.5c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z'] },
          { id: 'bell', name: 'Bell', paths: ['M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9', 'M10.3 21a1.94 1.94 0 0 0 3.4 0'] },
          { id: 'globe', name: 'Globe', paths: ['M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z', 'M3 12h18', 'M12 3a13.5 13.5 0 0 1 3.5 9A13.5 13.5 0 0 1 12 21a13.5 13.5 0 0 1-3.5-9A13.5 13.5 0 0 1 12 3Z'] },
          { id: 'lock', name: 'Lock', paths: ['M5 11a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2Z', 'M8 11V7a4 4 0 0 1 8 0v4'] },
          // …the rest of the set follows the same shape.
        ];

        export default function DrawEntrance() {
          const gridRef = useRef(null);
          const [drawn, setDrawn] = useState(() => Object.fromEntries(ICONS.map((i) => [i.id, false])));

          const setAll = (value) => setDrawn(Object.fromEntries(ICONS.map((i) => [i.id, value])));
          // A double rAF lets the erase state paint before the draw transition restarts.
          const replay = (id) => {
            if (!drawn[id]) return;
            setDrawn((d) => ({ ...d, [id]: false }));
            requestAnimationFrame(() => requestAnimationFrame(() =>
              setDrawn((d) => ({ ...d, [id]: true }))));
          };
          const redrawAll = () => {
            setAll(false);
            requestAnimationFrame(() => requestAnimationFrame(() => setAll(true)));
          };

          useEffect(() => {
            const io = new IntersectionObserver((entries) => entries.forEach((entry) => {
              if (entry.isIntersecting) { setAll(true); io.disconnect(); }
            }), { threshold: 0.2 });
            io.observe(gridRef.current);
            return () => io.disconnect();
          }, []);

          return (
            <>
              <style>{`
                .imd-grid { display: grid; gap: .75rem; grid-template-columns: repeat(auto-fill, minmax(7.25rem, 1fr)); }
                .imd-tile { display: grid; gap: .55rem; justify-items: center; padding: 1rem .5rem;
                            border: 1px solid #e7e5e4; border-radius: .9rem; background: #fff; color: #1c1917;
                            cursor: default; }
                .imd-tile svg { inline-size: 2.4rem; }
                .imd-tile path { fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round;
                                 stroke-linejoin: round; stroke-dasharray: 1; stroke-dashoffset: 1;
                                 transition: stroke-dashoffset .55s ease var(--d, 0s); }
                .imd-tile[data-drawn='true'] path { stroke-dashoffset: 0; }
                .imd-tile:not([data-drawn='true']) path { transition: none; }
                .imd-again { display: inline-flex; align-items: center; gap: .45rem; margin-block-start: 1rem;
                             padding: .5rem 1rem; border: 1px solid #e7e5e4; border-radius: 999px; background: #fff;
                             font: 600 .8rem/1 'Inter', sans-serif; cursor: pointer; }
                @media (prefers-reduced-motion: reduce) { .imd-tile path { transition: none; stroke-dashoffset: 0; } }
              `}</style>
              <ul className="imd-grid" ref={gridRef}>
                {ICONS.map((icon, i) => (
                  <li key={icon.id} className="imd-tile" data-drawn={!!drawn[icon.id]}
                      onPointerEnter={() => replay(icon.id)}>
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                      {icon.paths.map((d, p) => (
                        <path key={p} pathLength="1" d={d} style={{ '--d': `${i * 60 + p * 130}ms` }} />
                      ))}
                    </svg>
                    <span>{icon.name}</span>
                  </li>
                ))}
              </ul>
              <button type="button" className="imd-again" onClick={redrawAll}>
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8" /><path d="M3 3v5h5" /></svg>
                Draw them all again
              </button>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- DrawEntrance.vue — Lucide-style icons inking themselves on entry -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, reactive } from 'vue';
        import '@nabuxai/ui-core/css';

        const icons = [
          { id: 'heart', name: 'Heart', paths: ['M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3.5c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z'] },
          { id: 'bell', name: 'Bell', paths: ['M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9', 'M10.3 21a1.94 1.94 0 0 0 3.4 0'] },
          { id: 'globe', name: 'Globe', paths: ['M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z', 'M3 12h18', 'M12 3a13.5 13.5 0 0 1 3.5 9A13.5 13.5 0 0 1 12 21a13.5 13.5 0 0 1-3.5-9A13.5 13.5 0 0 1 12 3Z'] },
          { id: 'lock', name: 'Lock', paths: ['M5 11a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2Z', 'M8 11V7a4 4 0 0 1 8 0v4'] },
          // …the rest of the set follows the same shape.
        ];
        const drawn = reactive(Object.fromEntries(icons.map((i) => [i.id, false])));
        let grid = $ref<HTMLElement | null>(null);
        let io: IntersectionObserver | null = null;

        const setAll = (value: boolean) => icons.forEach((i) => { drawn[i.id] = value; });
        // A double rAF lets the erase state paint before the draw transition restarts.
        const replay = (id: string) => {
          if (!drawn[id]) return;
          drawn[id] = false;
          requestAnimationFrame(() => requestAnimationFrame(() => { drawn[id] = true; }));
        };
        const redrawAll = () => {
          setAll(false);
          requestAnimationFrame(() => requestAnimationFrame(() => setAll(true)));
        };

        onMounted(() => {
          io = new IntersectionObserver((entries) => entries.forEach((entry) => {
            if (entry.isIntersecting) { setAll(true); io!.disconnect(); }
          }), { threshold: 0.2 });
          io.observe(grid!);
        });
        onBeforeUnmount(() => io?.disconnect());
        </script>

        <template>
          <ul class="imd-grid" :ref="(el) => (grid = el as HTMLElement)">
            <li v-for="(icon, i) in icons" :key="icon.id" class="imd-tile" :data-drawn="drawn[icon.id]"
                @pointerenter="replay(icon.id)">
              <svg viewBox="0 0 24 24" aria-hidden="true">
                <path v-for="(d, p) in icon.paths" :key="p" pathLength="1" :d="d" :style="{ '--d': `${i * 60 + p * 130}ms` }" />
              </svg>
              <span>{{ icon.name }}</span>
            </li>
          </ul>
          <button type="button" class="imd-again" @click="redrawAll">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8" /><path d="M3 3v5h5" /></svg>
            Draw them all again
          </button>
        </template>

        <style scoped>
        .imd-grid { display: grid; gap: .75rem; grid-template-columns: repeat(auto-fill, minmax(7.25rem, 1fr)); }
        .imd-tile { display: grid; gap: .55rem; justify-items: center; padding: 1rem .5rem;
                    border: 1px solid #e7e5e4; border-radius: .9rem; background: #fff; color: #1c1917; }
        .imd-tile svg { inline-size: 2.4rem; }
        .imd-tile path { fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round;
                         stroke-linejoin: round; stroke-dasharray: 1; stroke-dashoffset: 1;
                         transition: stroke-dashoffset .55s ease var(--d, 0s); }
        .imd-tile[data-drawn='true'] path { stroke-dashoffset: 0; }
        .imd-tile:not([data-drawn='true']) path { transition: none; }
        .imd-again { display: inline-flex; align-items: center; gap: .45rem; margin-block-start: 1rem;
                     padding: .5rem 1rem; border: 1px solid #e7e5e4; border-radius: 999px; background: #fff;
                     font: 600 .8rem/1 'Inter', sans-serif; cursor: pointer; }
        @media (prefers-reduced-motion: reduce) { .imd-tile path { transition: none; stroke-dashoffset: 0; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- DrawEntrance.svelte — Lucide-style icons inking themselves on entry -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          const icons = [
            { id: 'heart', name: 'Heart', paths: ['M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3.5c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z'] },
            { id: 'bell', name: 'Bell', paths: ['M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9', 'M10.3 21a1.94 1.94 0 0 0 3.4 0'] },
            { id: 'globe', name: 'Globe', paths: ['M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z', 'M3 12h18', 'M12 3a13.5 13.5 0 0 1 3.5 9A13.5 13.5 0 0 1 12 21a13.5 13.5 0 0 1-3.5-9A13.5 13.5 0 0 1 12 3Z'] },
            { id: 'lock', name: 'Lock', paths: ['M5 11a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2Z', 'M8 11V7a4 4 0 0 1 8 0v4'] },
            // …the rest of the set follows the same shape.
          ];
          let drawn = $state(Object.fromEntries(icons.map((i) => [i.id, false])));
          let grid: HTMLElement;

          const setAll = (value: boolean) => { drawn = Object.fromEntries(icons.map((i) => [i.id, value])); };
          // A double rAF lets the erase state paint before the draw transition restarts.
          const replay = (id: string) => {
            if (!drawn[id]) return;
            drawn = { ...drawn, [id]: false };
            requestAnimationFrame(() => requestAnimationFrame(() => (drawn = { ...drawn, [id]: true })));
          };
          const redrawAll = () => {
            setAll(false);
            requestAnimationFrame(() => requestAnimationFrame(() => setAll(true)));
          };

          onMount(() => {
            const io = new IntersectionObserver((entries) => entries.forEach((entry) => {
              if (entry.isIntersecting) { setAll(true); io.disconnect(); }
            }), { threshold: 0.2 });
            io.observe(grid);
            return () => io.disconnect();
          });
        </script>

        <ul class="imd-grid" bind:this={grid}>
          {#each icons as icon, i (icon.id)}
            <li class="imd-tile" data-drawn={drawn[icon.id]} onpointerenter={() => replay(icon.id)}>
              <svg viewBox="0 0 24 24" aria-hidden="true">
                {#each icon.paths as d, p}
                  <path pathLength="1" {d} style="--d: {i * 60 + p * 130}ms" />
                {/each}
              </svg>
              <span>{icon.name}</span>
            </li>
          {/each}
        </ul>
        <button type="button" class="imd-again" onclick={redrawAll}>
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7L3 8" /><path d="M3 3v5h5" /></svg>
          Draw them all again
        </button>

        <style>
        .imd-grid { display: grid; gap: .75rem; grid-template-columns: repeat(auto-fill, minmax(7.25rem, 1fr)); }
        .imd-tile { display: grid; gap: .55rem; justify-items: center; padding: 1rem .5rem;
                    border: 1px solid #e7e5e4; border-radius: .9rem; background: #fff; color: #1c1917; }
        .imd-tile svg { inline-size: 2.4rem; }
        .imd-tile path { fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round;
                         stroke-linejoin: round; stroke-dasharray: 1; stroke-dashoffset: 1;
                         transition: stroke-dashoffset .55s ease var(--d, 0s); }
        .imd-tile[data-drawn='true'] path { stroke-dashoffset: 0; }
        .imd-tile:not([data-drawn='true']) path { transition: none; }
        .imd-again { display: inline-flex; align-items: center; gap: .45rem; margin-block-start: 1rem;
                     padding: .5rem 1rem; border: 1px solid #e7e5e4; border-radius: 999px; background: #fff;
                     font: 600 .8rem/1 'Inter', sans-serif; cursor: pointer; }
        @media (prefers-reduced-motion: reduce) { .imd-tile path { transition: none; stroke-dashoffset: 0; } }
        </style>
        SVELTE,
        ],
    ],

    'state-morph' => [
        'title' => ['fa' => 'مورف حالت‌ها', 'en' => 'State morph icons'],
        'icon' => 'heart',
        'oneLiner' => [
            'fa' => 'آیکون‌های دکمه‌ای به سبک لردیکون که با کلیک بین دو حالت مورف می‌شوند: قلب با انفجار ذره‌ها پر می‌شود، زنگ با تکان فنری می‌لرزد و بجش می‌شمارد، پخش و توقف در پنجرهٔ لغزان در هم می‌لغزند و بوکمارک پر می‌شود و ذره می‌پاشد — همه با aria-pressed سینک.',
            'en' => 'Lordicon-style button icons morphing between two states on click: the heart fills with a particle burst, the bell swings a springy shake while its badge counts, play and pause glide through a sliding window, the bookmark fills and scatters — all mirrored into aria-pressed.',
        ],
        'js' => true,
        'docs' => 'https://lordicon.com',
        'props' => [
            ['name' => 'aria-pressed', 'type' => 'state', 'default' => "true | false", 'note' => [
                'fa' => 'هر دکمه یک تاگل واقعی است؛ حالت روی aria-pressed و کارت هم با data-on هم‌رنگ می‌شود تا صفحه‌خوان و چشم یک چیز ببینند.',
                'en' => 'Each button is a real toggle; the state rides aria-pressed while the card mirrors it through data-on, so screen reader and eye see the same thing.',
            ]],
            ['name' => 'burst', 'type' => 'particles', 'default' => "'8 · 24–36px'", 'note' => [
                'fa' => 'هشت ذره با زوایای مساوی و کمی رندم بیرون می‌پرند و در ۷۰۰ms محو می‌شوند؛ تایمرها در destroy پاک می‌شوند.',
                'en' => 'Eight dots fly out on even-but-jittered angles and fade over 700ms; their timers are cleared on destroy.',
            ]],
            ['name' => 'pop', 'type' => 'keyframe', 'default' => "'scale 0 → 1.28 → 1'", 'note' => [
                'fa' => 'پرشدن قلب، بوکمارک و بج با اورشوت ۲۸٪ فنر می‌خورد؛ با transform-box: fill-box از مرکز خودِ شکل.',
                'en' => 'Fills for heart, bookmark and badge spring with a 28% overshoot; transform-box: fill-box pivots each shape at its own centre.',
            ]],
            ['name' => 'ring', 'type' => 'keyframe', 'default' => "'0 → 15 → −12 → 8 → −5 → 0°'", 'note' => [
                'fa' => 'زنگ با چرخش فنری از لنگر بالای دسته تکان می‌خورد؛ منحنی cubic-bezier(.36,.07,.19,.97) ضربه‌ها را آرام می‌کند.',
                'en' => 'The bell shakes on a springy rotation anchored at its handle; cubic-bezier(.36,.07,.19,.97) damps the swings.',
            ]],
            ['name' => 'reduced-motion', 'type' => 'media', 'default' => "'static'", 'note' => [
                'fa' => 'بدون حرکت، حالت نهایی (پر/خالی) بلافاصله نشان داده می‌شود و ذره‌ها حذف می‌شوند.',
                'en' => 'With motion off, the final state (filled or hollow) lands instantly and the particles are dropped.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="ims" x-data="{
            dots: [], t: null,
            burst() {
                this.dots = Array.from({ length: 8 }, (_, i) => {
                    const a = (Math.PI * 2 * i) / 8 + Math.random() * .6;
                    const r = 24 + Math.random() * 12;
                    return { k: Date.now() + '-' + i, dx: Math.round(Math.cos(a) * r), dy: Math.round(Math.sin(a) * r) };
                });
                clearTimeout(this.t);
                this.t = setTimeout(() => (this.dots = []), 700);
            },
        }" x-init="$cleanup(() => clearTimeout(t))">
            {{-- قلب: مورف پرشدن + انفجار ذره‌ها --}}
            <button type="button" class="ims-btn" x-data="{ on: false, count: 2481 }"
                    x-bind:aria-pressed="on ? 'true' : 'false'"
                    x-on:click="on = !on; if (on) { count++; $data.burst(); }">
                <span class="ims-ico">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path class="ims-line" d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3.5c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                        <path class="ims-fill" d="…همان مسیر قلب…"/>
                    </svg>
                    <template x-for="dot in dots" :key="dot.k">
                        <span class="ims-dot" :style="`--dx: ${dot.dx}px; --dy: ${dot.dy}px`"></span>
                    </template>
                </span>
                <span x-text="on ? 'پسندیدی' : 'پسند'"></span>
            </button>

            {{-- پخش/توقف: پنجرهٔ لغزان --}}
            <button type="button" class="ims-btn" x-data="{ on: false }"
                    x-bind:aria-pressed="on ? 'true' : 'false'" x-on:click="on = !on">
                <span class="ims-window" dir="ltr">
                    <span class="ims-track" x-bind:data-on="on ? 'true' : 'false'">
                        <svg class="ims-half" viewBox="0 0 24 24"><path class="ims-line" d="m7 4 13 8-13 8Z"/></svg>
                        <svg class="ims-half" viewBox="0 0 24 24"><path class="ims-line" d="M7 4h3v16H7zM14 4h3v16h-3z"/></svg>
                    </span>
                </span>
                <span x-text="on ? 'در حال پخش' : 'پخش ویدیو'"></span>
            </button>
        </div>

        <style>
        .ims-btn { display: inline-grid; justify-items: center; gap: .55rem; padding: .8rem 1.4rem;
                   border: 1px solid #e7e5e4; border-radius: 1rem; background: #fff; cursor: pointer; }
        .ims-ico { position: relative; display: grid; place-items: center; }
        .ims-line { fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .ims-fill { fill: currentColor; scale: 0; transform-box: fill-box; transform-origin: center; }
        .ims-btn[aria-pressed='true'] .ims-fill { scale: 1; animation: ims-pop .5s cubic-bezier(.2,.9,.3,1.35); }
        .ims-dot { position: absolute; inline-size: 4px; block-size: 4px; border-radius: 50%; background: currentColor;
                   animation: ims-burst .65s ease-out forwards; }
        .ims-window { inline-size: 1.7rem; overflow: clip; }
        .ims-track { display: flex; inline-size: 3.4rem; transition: translate .35s cubic-bezier(.3,.7,.2,1); }
        .ims-track[data-on='true'] { translate: -1.7rem 0; }
        .ims-half { flex: none; inline-size: 1.7rem; }
        @keyframes ims-pop { 0% { scale: 0; } 62% { scale: 1.28; } 100% { scale: 1; } }
        @keyframes ims-burst { from { opacity: 1; translate: 0 0; } to { opacity: 0; translate: var(--dx) var(--dy); scale: .2; } }
        @media (prefers-reduced-motion: reduce) {
            .ims-fill { animation: none; } .ims-track { transition: none; } .ims-dot { display: none; }
        }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="ims" x-data="{
            dots: [], t: null,
            burst() {
                this.dots = Array.from({ length: 8 }, (_, i) => {
                    const a = (Math.PI * 2 * i) / 8 + Math.random() * .6;
                    const r = 24 + Math.random() * 12;
                    return { k: Date.now() + '-' + i, dx: Math.round(Math.cos(a) * r), dy: Math.round(Math.sin(a) * r) };
                });
                clearTimeout(this.t);
                this.t = setTimeout(() => (this.dots = []), 700);
            },
        }" x-init="$cleanup(() => clearTimeout(t))">
            {{-- heart: fill morph + particle burst --}}
            <button type="button" class="ims-btn" x-data="{ on: false, count: 2481 }"
                    x-bind:aria-pressed="on ? 'true' : 'false'"
                    x-on:click="on = !on; if (on) { count++; $data.burst(); }">
                <span class="ims-ico">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path class="ims-line" d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3.5c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                        <path class="ims-fill" d="…the same heart path…"/>
                    </svg>
                    <template x-for="dot in dots" :key="dot.k">
                        <span class="ims-dot" :style="`--dx: ${dot.dx}px; --dy: ${dot.dy}px`"></span>
                    </template>
                </span>
                <span x-text="on ? 'Liked' : 'Like'"></span>
            </button>

            {{-- play/pause: the sliding window --}}
            <button type="button" class="ims-btn" x-data="{ on: false }"
                    x-bind:aria-pressed="on ? 'true' : 'false'" x-on:click="on = !on">
                <span class="ims-window" dir="ltr">
                    <span class="ims-track" x-bind:data-on="on ? 'true' : 'false'">
                        <svg class="ims-half" viewBox="0 0 24 24"><path class="ims-line" d="m7 4 13 8-13 8Z"/></svg>
                        <svg class="ims-half" viewBox="0 0 24 24"><path class="ims-line" d="M7 4h3v16H7zM14 4h3v16h-3z"/></svg>
                    </span>
                </span>
                <span x-text="on ? 'Playing' : 'Play video'"></span>
            </button>
        </div>

        <style>
        .ims-btn { display: inline-grid; justify-items: center; gap: .55rem; padding: .8rem 1.4rem;
                   border: 1px solid #e7e5e4; border-radius: 1rem; background: #fff; cursor: pointer; }
        .ims-ico { position: relative; display: grid; place-items: center; }
        .ims-line { fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .ims-fill { fill: currentColor; scale: 0; transform-box: fill-box; transform-origin: center; }
        .ims-btn[aria-pressed='true'] .ims-fill { scale: 1; animation: ims-pop .5s cubic-bezier(.2,.9,.3,1.35); }
        .ims-dot { position: absolute; inline-size: 4px; block-size: 4px; border-radius: 50%; background: currentColor;
                   animation: ims-burst .65s ease-out forwards; }
        .ims-window { inline-size: 1.7rem; overflow: clip; }
        .ims-track { display: flex; inline-size: 3.4rem; transition: translate .35s cubic-bezier(.3,.7,.2,1); }
        .ims-track[data-on='true'] { translate: -1.7rem 0; }
        .ims-half { flex: none; inline-size: 1.7rem; }
        @keyframes ims-pop { 0% { scale: 0; } 62% { scale: 1.28; } 100% { scale: 1; } }
        @keyframes ims-burst { from { opacity: 1; translate: 0 0; } to { opacity: 0; translate: var(--dx) var(--dy); scale: .2; } }
        @media (prefers-reduced-motion: reduce) {
            .ims-fill { animation: none; } .ims-track { transition: none; } .ims-dot { display: none; }
        }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/IconMotion.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import StateMorphs from '@/components/StateMorphs';

        export default function IconMotion() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <StateMorphs />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // StateMorphs.jsx — Lordicon-style state morphs on real toggle buttons
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const HEART = 'M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3.5c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z';

        function useBurst() {
          const [dots, setDots] = useState([]);
          const timers = useRef([]);
          useEffect(() => () => timers.current.forEach(clearTimeout), []);
          const burst = () => {
            const nonce = Date.now();
            setDots(Array.from({ length: 8 }, (_, i) => {
              const a = (Math.PI * 2 * i) / 8 + Math.random() * 0.6;
              const r = 24 + Math.random() * 12;
              return { k: `${nonce}-${i}`, dx: Math.round(Math.cos(a) * r), dy: Math.round(Math.sin(a) * r) };
            }));
            timers.current.push(setTimeout(() => setDots([]), 700));
          };
          return { dots, burst };
        }

        export default function StateMorphs() {
          const [liked, setLiked] = useState(false);
          const [count, setCount] = useState(2481);
          const [playing, setPlaying] = useState(false);
          const like = useBurst();

          return (
            <>
              <style>{`
                .ims-btn { display: inline-grid; justify-items: center; gap: .55rem; padding: .8rem 1.4rem;
                           border: 1px solid #e7e5e4; border-radius: 1rem; background: #fff; color: #1c1917; cursor: pointer; }
                .ims-ico { position: relative; display: grid; place-items: center; }
                .ims-line { fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
                .ims-fill { fill: currentColor; scale: 0; transform-box: fill-box; transform-origin: center; }
                .ims-btn[aria-pressed='true'] .ims-fill { scale: 1; animation: ims-pop .5s cubic-bezier(.2,.9,.3,1.35); }
                .ims-dot { position: absolute; inline-size: 4px; block-size: 4px; border-radius: 50%; background: currentColor;
                           animation: ims-burst .65s ease-out forwards; }
                .ims-window { inline-size: 1.7rem; overflow: clip; }
                .ims-track { display: flex; inline-size: 3.4rem; transition: translate .35s cubic-bezier(.3,.7,.2,1); }
                .ims-track[data-on='true'] { translate: -1.7rem 0; }
                .ims-half { flex: none; inline-size: 1.7rem; }
                @keyframes ims-pop { 0% { scale: 0; } 62% { scale: 1.28; } 100% { scale: 1; } }
                @keyframes ims-burst { from { opacity: 1; translate: 0 0; } to { opacity: 0; translate: var(--dx) var(--dy); scale: .2; } }
                @media (prefers-reduced-motion: reduce) {
                  .ims-fill { animation: none; } .ims-track { transition: none; } .ims-dot { display: none; }
                }
              `}</style>
              {/* Heart — fills with a particle burst; aria-pressed carries the state. */}
              <button type="button" className="ims-btn" aria-pressed={liked}
                onClick={() => { setLiked(!liked); if (!liked) { setCount((c) => c + 1); like.burst(); } }}>
                <span className="ims-ico">
                  <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path className="ims-line" d={HEART} />
                    <path className="ims-fill" d={HEART} />
                  </svg>
                  {like.dots.map((dot) => (
                    <span key={dot.k} className="ims-dot" style={{ '--dx': `${dot.dx}px`, '--dy': `${dot.dy}px` }} />
                  ))}
                </span>
                <span>{liked ? 'Liked' : 'Like'}</span>
              </button>
              <p>{count} people liked this</p>

              {/* Play/pause — the two icons glide through a sliding window. */}
              <button type="button" className="ims-btn" aria-pressed={playing} onClick={() => setPlaying(!playing)}>
                <span className="ims-window" dir="ltr">
                  <span className="ims-track" data-on={playing}>
                    <svg className="ims-half" viewBox="0 0 24 24" aria-hidden="true"><path className="ims-line" d="m7 4 13 8-13 8Z" /></svg>
                    <svg className="ims-half" viewBox="0 0 24 24" aria-hidden="true"><path className="ims-line" d="M7 4h3v16H7zM14 4h3v16h-3z" /></svg>
                  </span>
                </span>
                <span>{playing ? 'Playing' : 'Play video'}</span>
              </button>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- StateMorphs.vue — Lordicon-style state morphs on real toggle buttons -->
        <script setup lang="ts">
        import { onBeforeUnmount, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const HEART = 'M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3.5c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z';
        const liked = ref(false);
        const count = ref(2481);
        const playing = ref(false);
        const dots = ref<{ k: string; dx: number; dy: number }[]>([]);
        const timers: number[] = [];

        // Eight particles on even-but-jittered angles; swept away after 700ms.
        const burst = () => {
          const nonce = Date.now();
          dots.value = Array.from({ length: 8 }, (_, i) => {
            const a = (Math.PI * 2 * i) / 8 + Math.random() * 0.6;
            const r = 24 + Math.random() * 12;
            return { k: `${nonce}-${i}`, dx: Math.round(Math.cos(a) * r), dy: Math.round(Math.sin(a) * r) };
          });
          timers.push(window.setTimeout(() => (dots.value = []), 700));
        };
        const toggleLike = () => {
          liked.value = !liked.value;
          if (liked.value) { count.value++; burst(); }
        };
        onBeforeUnmount(() => timers.forEach(clearTimeout));
        </script>

        <template>
          <!-- Heart — fills with a particle burst; aria-pressed carries the state. -->
          <button type="button" class="ims-btn" :aria-pressed="liked" @click="toggleLike">
            <span class="ims-ico">
              <svg viewBox="0 0 24 24" aria-hidden="true">
                <path class="ims-line" :d="HEART" />
                <path class="ims-fill" :d="HEART" />
              </svg>
              <span v-for="dot in dots" :key="dot.k" class="ims-dot" :style="{ '--dx': dot.dx + 'px', '--dy': dot.dy + 'px' }" />
            </span>
            <span>{{ liked ? 'Liked' : 'Like' }}</span>
          </button>
          <p>{{ count }} people liked this</p>

          <!-- Play/pause — the two icons glide through a sliding window. -->
          <button type="button" class="ims-btn" :aria-pressed="playing" @click="playing = !playing">
            <span class="ims-window" dir="ltr">
              <span class="ims-track" :data-on="playing">
                <svg class="ims-half" viewBox="0 0 24 24" aria-hidden="true"><path class="ims-line" d="m7 4 13 8-13 8Z" /></svg>
                <svg class="ims-half" viewBox="0 0 24 24" aria-hidden="true"><path class="ims-line" d="M7 4h3v16H7zM14 4h3v16h-3z" /></svg>
              </span>
            </span>
            <span>{{ playing ? 'Playing' : 'Play video' }}</span>
          </button>
        </template>

        <style scoped>
        .ims-btn { display: inline-grid; justify-items: center; gap: .55rem; padding: .8rem 1.4rem;
                   border: 1px solid #e7e5e4; border-radius: 1rem; background: #fff; color: #1c1917; cursor: pointer; }
        .ims-ico { position: relative; display: grid; place-items: center; }
        .ims-line { fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .ims-fill { fill: currentColor; scale: 0; transform-box: fill-box; transform-origin: center; }
        .ims-btn[aria-pressed='true'] .ims-fill { scale: 1; animation: ims-pop .5s cubic-bezier(.2,.9,.3,1.35); }
        .ims-dot { position: absolute; inline-size: 4px; block-size: 4px; border-radius: 50%; background: currentColor;
                   animation: ims-burst .65s ease-out forwards; }
        .ims-window { inline-size: 1.7rem; overflow: clip; }
        .ims-track { display: flex; inline-size: 3.4rem; transition: translate .35s cubic-bezier(.3,.7,.2,1); }
        .ims-track[data-on='true'] { translate: -1.7rem 0; }
        .ims-half { flex: none; inline-size: 1.7rem; }
        @keyframes ims-pop { 0% { scale: 0; } 62% { scale: 1.28; } 100% { scale: 1; } }
        @keyframes ims-burst { from { opacity: 1; translate: 0 0; } to { opacity: 0; translate: var(--dx) var(--dy); scale: .2; } }
        @media (prefers-reduced-motion: reduce) {
          .ims-fill { animation: none; } .ims-track { transition: none; } .ims-dot { display: none; }
        }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- StateMorphs.svelte — Lordicon-style state morphs on real toggle buttons -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          const HEART = 'M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3.5c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z';
          let liked = $state(false);
          let count = $state(2481);
          let playing = $state(false);
          let dots = $state<{ k: string; dx: number; dy: number }[]>([]);
          const timers: number[] = [];

          // Eight particles on even-but-jittered angles; swept away after 700ms.
          const burst = () => {
            const nonce = Date.now();
            dots = Array.from({ length: 8 }, (_, i) => {
              const a = (Math.PI * 2 * i) / 8 + Math.random() * 0.6;
              const r = 24 + Math.random() * 12;
              return { k: `${nonce}-${i}`, dx: Math.round(Math.cos(a) * r), dy: Math.round(Math.sin(a) * r) };
            });
            timers.push(window.setTimeout(() => (dots = []), 700));
          };
          const toggleLike = () => {
            liked = !liked;
            if (liked) { count++; burst(); }
          };
          onMount(() => () => timers.forEach(clearTimeout));
        </script>

        <!-- Heart — fills with a particle burst; aria-pressed carries the state. -->
        <button type="button" class="ims-btn" aria-pressed={liked} onclick={toggleLike}>
          <span class="ims-ico">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path class="ims-line" d={HEART} />
              <path class="ims-fill" d={HEART} />
            </svg>
            {#each dots as dot (dot.k)}
              <span class="ims-dot" style="--dx: {dot.dx}px; --dy: {dot.dy}px"></span>
            {/each}
          </span>
          <span>{liked ? 'Liked' : 'Like'}</span>
        </button>
        <p>{count} people liked this</p>

        <!-- Play/pause — the two icons glide through a sliding window. -->
        <button type="button" class="ims-btn" aria-pressed={playing} onclick={() => (playing = !playing)}>
          <span class="ims-window" dir="ltr">
            <span class="ims-track" data-on={playing}>
              <svg class="ims-half" viewBox="0 0 24 24" aria-hidden="true"><path class="ims-line" d="m7 4 13 8-13 8Z" /></svg>
              <svg class="ims-half" viewBox="0 0 24 24" aria-hidden="true"><path class="ims-line" d="M7 4h3v16H7zM14 4h3v16h-3z" /></svg>
            </span>
          </span>
          <span>{playing ? 'Playing' : 'Play video'}</span>
        </button>

        <style>
        .ims-btn { display: inline-grid; justify-items: center; gap: .55rem; padding: .8rem 1.4rem;
                   border: 1px solid #e7e5e4; border-radius: 1rem; background: #fff; color: #1c1917; cursor: pointer; }
        .ims-ico { position: relative; display: grid; place-items: center; }
        .ims-line { fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .ims-fill { fill: currentColor; scale: 0; transform-box: fill-box; transform-origin: center; }
        .ims-btn[aria-pressed='true'] .ims-fill { scale: 1; animation: ims-pop .5s cubic-bezier(.2,.9,.3,1.35); }
        .ims-dot { position: absolute; inline-size: 4px; block-size: 4px; border-radius: 50%; background: currentColor;
                   animation: ims-burst .65s ease-out forwards; }
        .ims-window { inline-size: 1.7rem; overflow: clip; }
        .ims-track { display: flex; inline-size: 3.4rem; transition: translate .35s cubic-bezier(.3,.7,.2,1); }
        .ims-track[data-on='true'] { translate: -1.7rem 0; }
        .ims-half { flex: none; inline-size: 1.7rem; }
        @keyframes ims-pop { 0% { scale: 0; } 62% { scale: 1.28; } 100% { scale: 1; } }
        @keyframes ims-burst { from { opacity: 1; translate: 0 0; } to { opacity: 0; translate: var(--dx) var(--dy); scale: .2; } }
        @media (prefers-reduced-motion: reduce) {
          .ims-fill { animation: none; } .ims-track { transition: none; } .ims-dot { display: none; }
        }
        </style>
        SVELTE,
        ],
    ],

    'loop-set' => [
        'title' => ['fa' => 'حلقه‌های بی‌پایان', 'en' => 'Looping icons'],
        'icon' => 'cpu',
        'oneLiner' => [
            'fa' => 'آیکون‌های درحال‌کارِ پیوسته داخل کنترل‌های واقعی یک کنسول همگام‌سازی: سه‌نقطه و اسپینر کمانی، فلش‌های همگام‌سازی که خلاف هم می‌چرخند، ذره‌بین نبض‌دار در فیلد جست‌وجو، فلش آپلود که پرشدنش نوار پیشرفت است و فلش دانلود جهنده — هرکدام در کارت وضعیت با متن و متای زمان.',
            'en' => 'Continuously working icons inside a sync console’s real controls: a dot trio and an arc spinner, counter-rotating sync arrows, a magnifier pulsing in a live search field, an upload arrow whose fill is the progress bar, and a bouncing download arrow — each in a status card with text and a time meta.',
        ],
        'js' => true,
        'docs' => 'https://useanimations.com',
        'props' => [
            ['name' => 'loop', 'type' => 'technique', 'default' => "'@keyframes'", 'note' => [
                'fa' => 'همهٔ حلقه‌ها CSS خالص‌اند — بدون تایمر برای چرخش؛ فقط پیشرفت آپلود و دبونس جست‌وجو با Alpine چرخیده می‌شوند و در destroy پاک می‌شوند.',
                'en' => 'Every loop is pure CSS — no timers for the spins; only the upload progress and the search debounce run on Alpine, both cleaned up on destroy.',
            ]],
            ['name' => 'upload-fill', 'type' => 'clip-path', 'default' => "'inset((100 − p)% 0 0 0)'", 'note' => [
                'fa' => 'پرشدن فلش آپلود همان نوار پیشرفت است: کپی پررنگ مسیرها با clip-path از پایین بالا می‌آید و درصدها با ارقام فارسی کنارش می‌شمارند.',
                'en' => 'The upload arrow’s fill is the progress bar: a filled copy of its paths rises via clip-path while the Persian-digit percentage counts beside it.',
            ]],
            ['name' => 'sync', 'type' => 'keyframe', 'default' => "'1.6s · reverse'", 'note' => [
                'fa' => 'دو کمان فلش‌دار حول مرکز آیکون خلاف هم می‌چرخند — حس «در حال هماهنگ‌کردن دو طرف».',
                'en' => 'Two arrowed arcs rotate against each other around the icon’s centre — the feel of two sides being reconciled.',
            ]],
            ['name' => 'search-debounce', 'type' => 'ms', 'default' => "'900'", 'note' => [
                'fa' => 'ذره‌بین تا ۹۰۰ms بعد از آخرین حرف نبض می‌زند؛ تایمر دبونس با هر کلید ریست و در destroy پاک می‌شود.',
                'en' => 'The magnifier pulses until 900ms after the last keystroke; the debounce timer resets per key and is cleared on destroy.',
            ]],
            ['name' => 'reduced-motion', 'type' => 'media', 'default' => "'frozen'", 'note' => [
                'fa' => 'همهٔ حلقه‌ها یک‌جا می‌ایستند؛ آیکون‌ها به شکل ساکن و خوانا می‌مانند و پیشرفت آپلود همچنان از متن خوانده می‌شود.',
                'en' => 'All loops freeze in place; the icons stay legible and static while the upload progress remains readable from the text.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="iml">
            {{-- اسپینر کمانی --}}
            <span class="iml-ico" role="img" aria-label="در حال بارگذاری">
                <svg class="iml-arc" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9" pathLength="100"/></svg>
            </span>

            {{-- فلش‌های همگام‌سازی خلاف‌چرخ --}}
            <span class="iml-ico" role="img" aria-label="در حال همگام‌سازی">
                <svg class="iml-sync" viewBox="0 0 24 24" aria-hidden="true">
                    <g class="iml-cw"><path class="iml-line" d="M19.5 13.5A8 8 0 0 1 5.6 16.4"/><path class="iml-line" d="M4.5 13.5v3.8h3.8"/></g>
                    <g class="iml-ccw"><path class="iml-line" d="M4.5 10.5A8 8 0 0 1 18.4 7.6"/><path class="iml-line" d="M19.5 10.5V6.7h-3.8"/></g>
                </svg>
            </span>

            {{-- آپلود با نوار پیشرفت داخل فلش --}}
            <span class="iml-ico" x-data="{ p: 0 }"
                  x-init="up = setInterval(() => (p = p < 100 ? p + 2 : 0), 90); $cleanup(() => clearInterval(up))">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path class="iml-line" d="M12 15.5V4.5"/>
                    <path class="iml-line" d="m6.5 9.5 5.5-5 5.5 5"/>
                    <path class="iml-line" d="M5 20h14"/>
                    <g :style="`clip-path: inset(${100 - p}% 0 0 0)`">
                        <path class="iml-upfill" d="M12 15.5V4.5"/>
                        <path class="iml-upfill" d="m6.5 9.5 5.5-5 5.5 5Z"/>
                    </g>
                </svg>
            </span>

            {{-- ذره‌بین نبض‌دار در فیلد جست‌وجو --}}
            <label class="iml-field" x-data="{ busy: false, t: null }" x-init="$cleanup(() => clearTimeout(t))">
                <span class="iml-magwrap" x-bind:data-busy="busy ? 'true' : 'false'">
                    <svg class="iml-mag" viewBox="0 0 24 24" aria-hidden="true">
                        <path class="iml-line" d="M17.5 10.5a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                        <path class="iml-line" d="m16 16 4.5 4.5"/>
                    </svg>
                </span>
                <input type="search" placeholder="جست‌وجو در اسناد تیم…"
                       x-on:input="busy = true; clearTimeout(t); t = setTimeout(() => (busy = false), 900)">
            </label>
        </div>

        <style>
        .iml-ico { display: grid; place-items: center; inline-size: 2.6rem; block-size: 2.6rem;
                   border-radius: .8rem; background: #eef2ff; color: #4f46e5; }
        .iml-line { fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .iml-arc { animation: iml-spin 1.3s linear infinite; }
        .iml-arc circle { fill: none; stroke: currentColor; stroke-width: 2.4; stroke-linecap: round;
                          stroke-dasharray: 30 70; transform-origin: center; }
        .iml-sync g { transform-origin: 12px 12px; }
        .iml-sync .iml-cw { animation: iml-spin 1.6s linear infinite; }
        .iml-sync .iml-ccw { animation: iml-spin 1.6s linear infinite reverse; }
        .iml-upfill { fill: currentColor; stroke: none; }
        .iml-magwrap { position: relative; }
        .iml-magwrap[data-busy='true'] .iml-mag { animation: iml-pulse .9s ease-in-out infinite; }
        .iml-magwrap[data-busy='true']::after { content: ''; position: absolute; inset: 0; border-radius: inherit;
                                                border: 2px solid currentColor; animation: iml-halo 1.1s ease-out infinite; }
        .iml-field { display: flex; align-items: center; gap: .6rem; padding: .6rem .85rem;
                     border: 1px solid #e7e5e4; border-radius: .8rem; }
        @keyframes iml-spin { to { rotate: 360deg; } }
        @keyframes iml-pulse { 0%, 100% { scale: 1; } 50% { scale: 1.18; } }
        @keyframes iml-halo { from { opacity: .7; scale: .8; } to { opacity: 0; scale: 1.25; } }
        @media (prefers-reduced-motion: reduce) {
            .iml-arc, .iml-sync .iml-cw, .iml-sync .iml-ccw,
            .iml-magwrap[data-busy='true'] .iml-mag, .iml-magwrap[data-busy='true']::after { animation: none; }
        }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="iml">
            {{-- arc spinner --}}
            <span class="iml-ico" role="img" aria-label="loading">
                <svg class="iml-arc" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9" pathLength="100"/></svg>
            </span>

            {{-- counter-rotating sync arrows --}}
            <span class="iml-ico" role="img" aria-label="syncing">
                <svg class="iml-sync" viewBox="0 0 24 24" aria-hidden="true">
                    <g class="iml-cw"><path class="iml-line" d="M19.5 13.5A8 8 0 0 1 5.6 16.4"/><path class="iml-line" d="M4.5 13.5v3.8h3.8"/></g>
                    <g class="iml-ccw"><path class="iml-line" d="M4.5 10.5A8 8 0 0 1 18.4 7.6"/><path class="iml-line" d="M19.5 10.5V6.7h-3.8"/></g>
                </svg>
            </span>

            {{-- upload whose arrow fill is the progress bar --}}
            <span class="iml-ico" x-data="{ p: 0 }"
                  x-init="up = setInterval(() => (p = p < 100 ? p + 2 : 0), 90); $cleanup(() => clearInterval(up))">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path class="iml-line" d="M12 15.5V4.5"/>
                    <path class="iml-line" d="m6.5 9.5 5.5-5 5.5 5"/>
                    <path class="iml-line" d="M5 20h14"/>
                    <g :style="`clip-path: inset(${100 - p}% 0 0 0)`">
                        <path class="iml-upfill" d="M12 15.5V4.5"/>
                        <path class="iml-upfill" d="m6.5 9.5 5.5-5 5.5 5Z"/>
                    </g>
                </svg>
            </span>

            {{-- pulsing magnifier in a live search field --}}
            <label class="iml-field" x-data="{ busy: false, t: null }" x-init="$cleanup(() => clearTimeout(t))">
                <span class="iml-magwrap" x-bind:data-busy="busy ? 'true' : 'false'">
                    <svg class="iml-mag" viewBox="0 0 24 24" aria-hidden="true">
                        <path class="iml-line" d="M17.5 10.5a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                        <path class="iml-line" d="m16 16 4.5 4.5"/>
                    </svg>
                </span>
                <input type="search" placeholder="Search the team docs…"
                       x-on:input="busy = true; clearTimeout(t); t = setTimeout(() => (busy = false), 900)">
            </label>
        </div>

        <style>
        .iml-ico { display: grid; place-items: center; inline-size: 2.6rem; block-size: 2.6rem;
                   border-radius: .8rem; background: #eef2ff; color: #4f46e5; }
        .iml-line { fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .iml-arc { animation: iml-spin 1.3s linear infinite; }
        .iml-arc circle { fill: none; stroke: currentColor; stroke-width: 2.4; stroke-linecap: round;
                          stroke-dasharray: 30 70; transform-origin: center; }
        .iml-sync g { transform-origin: 12px 12px; }
        .iml-sync .iml-cw { animation: iml-spin 1.6s linear infinite; }
        .iml-sync .iml-ccw { animation: iml-spin 1.6s linear infinite reverse; }
        .iml-upfill { fill: currentColor; stroke: none; }
        .iml-magwrap { position: relative; }
        .iml-magwrap[data-busy='true'] .iml-mag { animation: iml-pulse .9s ease-in-out infinite; }
        .iml-magwrap[data-busy='true']::after { content: ''; position: absolute; inset: 0; border-radius: inherit;
                                                border: 2px solid currentColor; animation: iml-halo 1.1s ease-out infinite; }
        .iml-field { display: flex; align-items: center; gap: .6rem; padding: .6rem .85rem;
                     border: 1px solid #e7e5e4; border-radius: .8rem; }
        @keyframes iml-spin { to { rotate: 360deg; } }
        @keyframes iml-pulse { 0%, 100% { scale: 1; } 50% { scale: 1.18; } }
        @keyframes iml-halo { from { opacity: .7; scale: .8; } to { opacity: 0; scale: 1.25; } }
        @media (prefers-reduced-motion: reduce) {
            .iml-arc, .iml-sync .iml-cw, .iml-sync .iml-ccw,
            .iml-magwrap[data-busy='true'] .iml-mag, .iml-magwrap[data-busy='true']::after { animation: none; }
        }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/IconMotion.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import LoopSet from '@/components/LoopSet';

        export default function IconMotion() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <LoopSet />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // LoopSet.jsx — looping icons inside a sync console's real controls
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        export default function LoopSet() {
          const [p, setP] = useState(0);
          const [q, setQ] = useState('');
          const [busy, setBusy] = useState(false);
          const debounce = useRef(null);

          // The upload climbs 2% a tick and restarts after a pause at 100%.
          useEffect(() => {
            let resting = false;
            const id = setInterval(() => {
              setP((v) => {
                if (v < 100) return Math.min(100, v + 2);
                if (!resting) { resting = true; setTimeout(() => { setP(0); resting = false; }, 1400); }
                return v;
              });
            }, 90);
            return () => clearInterval(id);
          }, []);

          // The magnifier pulses until 900ms after the last keystroke.
          const onSearch = (value) => {
            setQ(value);
            setBusy(true);
            clearTimeout(debounce.current);
            debounce.current = setTimeout(() => setBusy(false), 900);
          };
          useEffect(() => () => clearTimeout(debounce.current), []);

          return (
            <>
              <style>{`
                .iml-ico { display: grid; place-items: center; inline-size: 2.6rem; block-size: 2.6rem;
                           border-radius: .8rem; background: #eef2ff; color: #4f46e5; }
                .iml-line { fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
                .iml-arc { animation: iml-spin 1.3s linear infinite; }
                .iml-arc circle { fill: none; stroke: currentColor; stroke-width: 2.4; stroke-linecap: round;
                                  stroke-dasharray: 30 70; transform-origin: center; }
                .iml-sync g { transform-origin: 12px 12px; }
                .iml-sync .iml-cw { animation: iml-spin 1.6s linear infinite; }
                .iml-sync .iml-ccw { animation: iml-spin 1.6s linear infinite reverse; }
                .iml-upfill { fill: currentColor; stroke: none; }
                .iml-pct { font: 600 .72rem/1 ui-monospace, monospace; color: #4f46e5; margin-inline-start: auto; }
                .iml-magwrap { position: relative; }
                .iml-magwrap[data-busy='true'] .iml-mag { animation: iml-pulse .9s ease-in-out infinite; }
                .iml-magwrap[data-busy='true']::after { content: ''; position: absolute; inset: 0; border-radius: inherit;
                                                        border: 2px solid currentColor; animation: iml-halo 1.1s ease-out infinite; }
                .iml-field { display: flex; align-items: center; gap: .6rem; padding: .6rem .85rem;
                             border: 1px solid #e7e5e4; border-radius: .8rem; }
                @keyframes iml-spin { to { rotate: 360deg; } }
                @keyframes iml-pulse { 0%, 100% { scale: 1; } 50% { scale: 1.18; } }
                @keyframes iml-halo { from { opacity: .7; scale: .8; } to { opacity: 0; scale: 1.25; } }
                @media (prefers-reduced-motion: reduce) {
                  .iml-arc, .iml-sync .iml-cw, .iml-sync .iml-ccw,
                  .iml-magwrap[data-busy='true'] .iml-mag, .iml-magwrap[data-busy='true']::after { animation: none; }
                }
              `}</style>
              <div className="iml-card">
                <span className="iml-ico" role="img" aria-label="syncing">
                  <svg className="iml-sync" viewBox="0 0 24 24" aria-hidden="true">
                    <g className="iml-cw"><path className="iml-line" d="M19.5 13.5A8 8 0 0 1 5.6 16.4" /><path className="iml-line" d="M4.5 13.5v3.8h3.8" /></g>
                    <g className="iml-ccw"><path className="iml-line" d="M4.5 10.5A8 8 0 0 1 18.4 7.6" /><path className="iml-line" d="M19.5 10.5V6.7h-3.8" /></g>
                  </svg>
                </span>
                <b>Syncing workspace</b>
                <span className="iml-pct">Frankfurt · 214 files</span>
              </div>
              <div className="iml-card">
                <span className="iml-ico" role="img" aria-label="uploading">
                  <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path className="iml-line" d="M12 15.5V4.5" />
                    <path className="iml-line" d="m6.5 9.5 5.5-5 5.5 5" />
                    <path className="iml-line" d="M5 20h14" />
                    {/* The filled copy of the arrow IS the progress bar. */}
                    <g style={{ clipPath: `inset(${100 - p}% 0 0 0)` }}>
                      <path className="iml-upfill" d="M12 15.5V4.5" />
                      <path className="iml-upfill" d="m6.5 9.5 5.5-5 5.5 5Z" />
                    </g>
                  </svg>
                </span>
                <b>Uploading release cut</b>
                <span className="iml-pct" role="status">{p}%</span>
              </div>
              <label className="iml-field">
                <span className="iml-ico iml-magwrap" data-busy={busy} role="img" aria-label={busy ? 'filtering' : 'search'}>
                  <svg className="iml-mag" viewBox="0 0 24 24" aria-hidden="true">
                    <path className="iml-line" d="M17.5 10.5a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                    <path className="iml-line" d="m16 16 4.5 4.5" />
                  </svg>
                </span>
                <input type="search" value={q} placeholder="Search the team docs…"
                  onChange={(e) => onSearch(e.target.value)} aria-label="Search docs" />
              </label>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- LoopSet.vue — looping icons inside a sync console's real controls -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const p = ref(0);
        const q = ref('');
        const busy = ref(false);
        let up: number | undefined;
        let debounce: number | undefined;

        onMounted(() => {
          let resting = false;
          // The upload climbs 2% a tick and restarts after a pause at 100%.
          up = window.setInterval(() => {
            if (p.value < 100) p.value = Math.min(100, p.value + 2);
            else if (!resting) { resting = true; setTimeout(() => { p.value = 0; resting = false; }, 1400); }
          }, 90);
        });
        // The magnifier pulses until 900ms after the last keystroke.
        const onSearch = () => {
          busy.value = true;
          clearTimeout(debounce);
          debounce = window.setTimeout(() => (busy.value = false), 900);
        };
        onBeforeUnmount(() => { clearInterval(up); clearTimeout(debounce); });
        </script>

        <template>
          <div class="iml-card">
            <span class="iml-ico" role="img" aria-label="syncing">
              <svg class="iml-sync" viewBox="0 0 24 24" aria-hidden="true">
                <g class="iml-cw"><path class="iml-line" d="M19.5 13.5A8 8 0 0 1 5.6 16.4" /><path class="iml-line" d="M4.5 13.5v3.8h3.8" /></g>
                <g class="iml-ccw"><path class="iml-line" d="M4.5 10.5A8 8 0 0 1 18.4 7.6" /><path class="iml-line" d="M19.5 10.5V6.7h-3.8" /></g>
              </svg>
            </span>
            <b>Syncing workspace</b>
            <span class="iml-pct">Frankfurt · 214 files</span>
          </div>
          <div class="iml-card">
            <span class="iml-ico" role="img" aria-label="uploading">
              <svg viewBox="0 0 24 24" aria-hidden="true">
                <path class="iml-line" d="M12 15.5V4.5" />
                <path class="iml-line" d="m6.5 9.5 5.5-5 5.5 5" />
                <path class="iml-line" d="M5 20h14" />
                <!-- The filled copy of the arrow IS the progress bar. -->
                <g :style="{ clipPath: `inset(${100 - p}% 0 0 0)` }">
                  <path class="iml-upfill" d="M12 15.5V4.5" />
                  <path class="iml-upfill" d="m6.5 9.5 5.5-5 5.5 5Z" />
                </g>
              </svg>
            </span>
            <b>Uploading release cut</b>
            <span class="iml-pct" role="status">{{ p }}%</span>
          </div>
          <label class="iml-field">
            <span class="iml-ico iml-magwrap" :data-busy="busy" role="img" :aria-label="busy ? 'filtering' : 'search'">
              <svg class="iml-mag" viewBox="0 0 24 24" aria-hidden="true">
                <path class="iml-line" d="M17.5 10.5a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                <path class="iml-line" d="m16 16 4.5 4.5" />
              </svg>
            </span>
            <input type="search" v-model="q" placeholder="Search the team docs…" aria-label="Search docs" @input="onSearch" />
          </label>
        </template>

        <style scoped>
        .iml-ico { display: grid; place-items: center; inline-size: 2.6rem; block-size: 2.6rem;
                   border-radius: .8rem; background: #eef2ff; color: #4f46e5; }
        .iml-line { fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .iml-sync g { transform-origin: 12px 12px; }
        .iml-sync .iml-cw { animation: iml-spin 1.6s linear infinite; }
        .iml-sync .iml-ccw { animation: iml-spin 1.6s linear infinite reverse; }
        .iml-upfill { fill: currentColor; stroke: none; }
        .iml-pct { font: 600 .72rem/1 ui-monospace, monospace; color: #4f46e5; margin-inline-start: auto; }
        .iml-magwrap { position: relative; }
        .iml-magwrap[data-busy='true'] .iml-mag { animation: iml-pulse .9s ease-in-out infinite; }
        .iml-magwrap[data-busy='true']::after { content: ''; position: absolute; inset: 0; border-radius: inherit;
                                                border: 2px solid currentColor; animation: iml-halo 1.1s ease-out infinite; }
        .iml-field { display: flex; align-items: center; gap: .6rem; padding: .6rem .85rem;
                     border: 1px solid #e7e5e4; border-radius: .8rem; }
        @keyframes iml-spin { to { rotate: 360deg; } }
        @keyframes iml-pulse { 0%, 100% { scale: 1; } 50% { scale: 1.18; } }
        @keyframes iml-halo { from { opacity: .7; scale: .8; } to { opacity: 0; scale: 1.25; } }
        @media (prefers-reduced-motion: reduce) {
          .iml-sync .iml-cw, .iml-sync .iml-ccw,
          .iml-magwrap[data-busy='true'] .iml-mag, .iml-magwrap[data-busy='true']::after { animation: none; }
        }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- LoopSet.svelte — looping icons inside a sync console's real controls -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          let p = $state(0);
          let q = $state('');
          let busy = $state(false);
          let up: number | undefined;
          let debounce: number | undefined;

          onMount(() => {
            let resting = false;
            // The upload climbs 2% a tick and restarts after a pause at 100%.
            up = window.setInterval(() => {
              if (p < 100) p = Math.min(100, p + 2);
              else if (!resting) { resting = true; setTimeout(() => { p = 0; resting = false; }, 1400); }
            }, 90);
            return () => { clearInterval(up); clearTimeout(debounce); };
          });

          // The magnifier pulses until 900ms after the last keystroke.
          const onSearch = () => {
            busy = true;
            clearTimeout(debounce);
            debounce = window.setTimeout(() => (busy = false), 900);
          };
        </script>

        <div class="iml-card">
          <span class="iml-ico" role="img" aria-label="syncing">
            <svg class="iml-sync" viewBox="0 0 24 24" aria-hidden="true">
              <g class="iml-cw"><path class="iml-line" d="M19.5 13.5A8 8 0 0 1 5.6 16.4" /><path class="iml-line" d="M4.5 13.5v3.8h3.8" /></g>
              <g class="iml-ccw"><path class="iml-line" d="M4.5 10.5A8 8 0 0 1 18.4 7.6" /><path class="iml-line" d="M19.5 10.5V6.7h-3.8" /></g>
            </svg>
          </span>
          <b>Syncing workspace</b>
          <span class="iml-pct">Frankfurt · 214 files</span>
        </div>
        <div class="iml-card">
          <span class="iml-ico" role="img" aria-label="uploading">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path class="iml-line" d="M12 15.5V4.5" />
              <path class="iml-line" d="m6.5 9.5 5.5-5 5.5 5" />
              <path class="iml-line" d="M5 20h14" />
              <!-- The filled copy of the arrow IS the progress bar. -->
              <g style="clip-path: inset({100 - p}% 0 0 0)">
                <path class="iml-upfill" d="M12 15.5V4.5" />
                <path class="iml-upfill" d="m6.5 9.5 5.5-5 5.5 5Z" />
              </g>
            </svg>
          </span>
          <b>Uploading release cut</b>
          <span class="iml-pct" role="status">{p}%</span>
        </div>
        <label class="iml-field">
          <span class="iml-ico iml-magwrap" data-busy={busy} role="img" aria-label={busy ? 'filtering' : 'search'}>
            <svg class="iml-mag" viewBox="0 0 24 24" aria-hidden="true">
              <path class="iml-line" d="M17.5 10.5a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
              <path class="iml-line" d="m16 16 4.5 4.5" />
            </svg>
          </span>
          <input type="search" bind:value={q} oninput={onSearch} placeholder="Search the team docs…" aria-label="Search docs" />
        </label>

        <style>
        .iml-ico { display: grid; place-items: center; inline-size: 2.6rem; block-size: 2.6rem;
                   border-radius: .8rem; background: #eef2ff; color: #4f46e5; }
        .iml-line { fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .iml-sync g { transform-origin: 12px 12px; }
        .iml-sync .iml-cw { animation: iml-spin 1.6s linear infinite; }
        .iml-sync .iml-ccw { animation: iml-spin 1.6s linear infinite reverse; }
        .iml-upfill { fill: currentColor; stroke: none; }
        .iml-pct { font: 600 .72rem/1 ui-monospace, monospace; color: #4f46e5; margin-inline-start: auto; }
        .iml-magwrap { position: relative; }
        .iml-magwrap[data-busy='true'] .iml-mag { animation: iml-pulse .9s ease-in-out infinite; }
        .iml-magwrap[data-busy='true']::after { content: ''; position: absolute; inset: 0; border-radius: inherit;
                                                border: 2px solid currentColor; animation: iml-halo 1.1s ease-out infinite; }
        .iml-field { display: flex; align-items: center; gap: .6rem; padding: .6rem .85rem;
                     border: 1px solid #e7e5e4; border-radius: .8rem; }
        @keyframes iml-spin { to { rotate: 360deg; } }
        @keyframes iml-pulse { 0%, 100% { scale: 1; } 50% { scale: 1.18; } }
        @keyframes iml-halo { from { opacity: .7; scale: .8; } to { opacity: 0; scale: 1.25; } }
        @media (prefers-reduced-motion: reduce) {
          .iml-arc, .iml-sync .iml-cw, .iml-sync .iml-ccw,
          .iml-magwrap[data-busy='true'] .iml-mag, .iml-magwrap[data-busy='true']::after { animation: none; }
        }
        </style>
        SVELTE,
        ],
    ],

    'micro-controls' => [
        'title' => ['fa' => 'ریزکنترل‌های آیکونی', 'en' => 'Icon micro-controls'],
        'icon' => 'menu',
        'oneLiner' => [
            'fa' => 'پنل تنظیمات ورتکس استودیو که همه‌چیزش روی کنترل‌های بومی سوار است: همبرگری که خطوطش چرخیده ضربدر می‌شود و منوش باز می‌شود، چک‌باکسی که تیکش را می‌کشد و پاپ می‌کند، سوییچ خورشید↔ماه که پرتوها جمع می‌شوند، چشم رمز که پلک می‌زند و آکاردئونی با شوران چرخان — Space و Enter و Tab مجانی کار می‌کنند.',
            'en' => 'Vertex Studio’s preferences panel, everything riding native inputs: a hamburger whose lines rotate into an X while its menu unfurls, a checkbox whose tick draws itself then pops, a sun/moon switch whose rays fold away, a password eye that blinks, and accordions with a spinning chevron — Space, Enter and Tab keep working for free.',
        ],
        'js' => true,
        'docs' => 'https://useanimations.com',
        'props' => [
            ['name' => 'burger ⇄ x', 'type' => 'rotate', 'default' => "'45° · −45°'", 'note' => [
                'fa' => 'خط بالا ۸px پایین می‌آید و ۴۵ درجه، خط پایین برعکس و خط میانی محو می‌شود؛ منو با ترنزیشن grid-template-rows باز می‌شود.',
                'en' => 'The top line drops 8px to 45°, the bottom mirrors it and the middle fades; the menu unfurls on a grid-template-rows transition.',
            ]],
            ['name' => 'tick', 'type' => 'draw', 'default' => "'dash 1 → 0 + pop'", 'note' => [
                'fa' => 'تیک با همان تکنیک pathLength ترسیم و جعبه با یک پاپ فنری تأیید می‌شود؛ ورودی واقعی و از دید صفحه‌خوان سرِ جای خودش است.',
                'en' => 'The tick draws with the same pathLength trick and the box pops a springy confirmation; the real input stays in place for screen readers.',
            ]],
            ['name' => 'rays', 'type' => 'scale', 'default' => "'1 → 0'", 'note' => [
                'fa' => 'پرتوهای خورشید تک‌تک جمع می‌شوند، قرص می‌چرخد بیرون می‌رود و ماه با چرخش معکوس سر می‌آید؛ دستگیره با inset-inline سوی منطقی می‌لغزد.',
                'en' => 'The sun’s rays fold one by one, its disc rotates out and the moon rises counter-rotating; the knob slides on the logical inset-inline.',
            ]],
            ['name' => 'lid', 'type' => 'transform', 'default' => "'scale 1 0 → 1 1'", 'note' => [
                'fa' => 'پلک از بالای چشم پایین می‌آید و خطِ خطر خودش را می‌کشد؛ نوع اینپوت بین password و text واقعاً عوض می‌شود.',
                'en' => 'The lid drops from the eye’s top while the slash draws itself; the input’s type genuinely flips between password and text.',
            ]],
            ['name' => 'reduced-motion', 'type' => 'media', 'default' => "'instant'", 'note' => [
                'fa' => 'همهٔ ترنزیشن‌ها به .01ms فرو می‌روند؛ حالت‌ها بی‌درنگ عوض می‌شوند و چیزی از کار نمی‌افتد.',
                'en' => 'Every transition collapses to .01ms; states still flip instantly and nothing breaks.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="imm" x-data="{ menu: false, shown: false }">
            {{-- همبرگر ⇄ ضربدر --}}
            <button type="button" class="imm-burger" x-bind:aria-expanded="menu ? 'true' : 'false'"
                    x-on:click="menu = !menu">
                <span></span><span></span><span></span>
            </button>
            <div class="imm-menu" x-bind:data-open="menu ? 'true' : 'false'">
                <div class="imm-menuin"><ul><li><a href="#files">فایل‌ها</a></li></ul></div>
            </div>

            {{-- چک‌باکس با تیک ترسیمی: ورودی بومی + :checked --}}
            <label class="imm-check">
                <input type="checkbox">
                <span class="imm-box"><svg viewBox="0 0 24 24"><path pathLength="1" d="M5 12.5l4.5 4.5L19 7.5"/></svg></span>
                <span>تأیید می‌کنم که بیلد همهٔ بررسی‌ها را گذرانده</span>
            </label>

            {{-- سوییچ خورشید ⇄ ماه: ورودی بومی + :checked ~ --}}
            <label class="imm-switch">
                <input type="checkbox" role="switch">
                <span class="imm-track"><span class="imm-knob">
                    <svg class="imm-sun" viewBox="0 0 24 24"><g class="imm-rays"><line x1="12" y1="2.5" x2="12" y2="4.5"/><line x1="12" y1="19.5" x2="12" y2="21.5"/><line x1="2.5" y1="12" x2="4.5" y2="12"/><line x1="19.5" y1="12" x2="21.5" y2="12"/></g><circle cx="12" cy="12" r="4" fill="currentColor"/></svg>
                    <svg class="imm-moon" viewBox="0 0 24 24"><path fill="currentColor" d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
                </span></span>
                <span>حالت شب</span>
            </label>

            {{-- چشم رمز که پلک می‌زند --}}
            <div class="imm-pass">
                <input :type="shown ? 'text' : 'password'" placeholder="حداقل ۸ کاراکتر">
                <button type="button" class="imm-eye" x-bind:data-shown="shown ? 'true' : 'false'"
                        x-on:click="shown = !shown" :aria-label="shown ? 'پنهان کردن رمز' : 'نمایش رمز'">
                    <svg viewBox="0 0 24 24">
                        <path class="imm-line" d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12Z"/>
                        <circle cx="12" cy="12" r="3" fill="currentColor"/>
                        <path class="imm-line imm-lid" d="M4.5 7.5C3 9 2.5 10.5 2.5 12"/>
                        <path class="imm-line imm-slash" pathLength="1" d="M4 4l16 16"/>
                    </svg>
                </button>
            </div>
        </div>

        <style>
        .imm-burger { display: inline-grid; gap: 6px; align-content: center; inline-size: 2.6rem; block-size: 2.6rem;
                      border: 1px solid #e7e5e4; border-radius: .7rem; background: #fff; cursor: pointer; }
        .imm-burger span { display: block; inline-size: 1.15rem; block-size: 2px; margin-inline: auto; background: currentColor;
                           transition: translate .3s, rotate .3s, opacity .2s, scale .3s; }
        .imm-burger[aria-expanded='true'] span:nth-child(1) { translate: 0 8px; rotate: 45deg; }
        .imm-burger[aria-expanded='true'] span:nth-child(2) { opacity: 0; scale: .2 1; }
        .imm-burger[aria-expanded='true'] span:nth-child(3) { translate: 0 -8px; rotate: -45deg; }
        .imm-menu { display: grid; grid-template-rows: 0fr; transition: grid-template-rows .35s; }
        .imm-menu[data-open='true'] { grid-template-rows: 1fr; }
        .imm-menuin { overflow: hidden; }
        .imm-check input, .imm-switch input { position: absolute; inline-size: 1px; block-size: 1px; opacity: 0; }
        .imm-box { display: grid; place-items: center; inline-size: 1.35rem; block-size: 1.35rem;
                   border: 2px solid #e7e5e4; border-radius: .45rem; }
        .imm-box path { fill: none; stroke: #fff; stroke-width: 3; stroke-linecap: round; stroke-linejoin: round;
                        stroke-dasharray: 1; stroke-dashoffset: 1; transition: stroke-dashoffset .3s ease .05s; }
        .imm-check input:checked + .imm-box { background: #4f46e5; border-color: #4f46e5; animation: imm-pop .3s; }
        .imm-check input:checked + .imm-box path { stroke-dashoffset: 0; }
        .imm-track { position: relative; display: block; inline-size: 3.4rem; block-size: 1.9rem; border-radius: 999px; background: #e7e5e4; }
        .imm-knob { position: absolute; inset-block-start: .25rem; inset-inline-start: .25rem; display: grid; place-items: center;
                    inline-size: 1.4rem; block-size: 1.4rem; border-radius: 50%; background: #fff;
                    transition: inset-inline-start .35s; }
        .imm-knob svg { position: absolute; inline-size: .9rem; transition: rotate .45s, opacity .3s, scale .3s; }
        .imm-rays line { transition: scale .3s; transform-box: fill-box; transform-origin: center; }
        .imm-switch input:checked ~ .imm-track { background: #312e81; }
        .imm-switch input:checked ~ .imm-track .imm-knob { inset-inline-start: calc(100% - 1.65rem); }
        .imm-switch input:checked ~ .imm-track .imm-sun { rotate: -100deg; opacity: 0; scale: .4; }
        .imm-switch input:checked ~ .imm-track .imm-rays line { scale: 0; }
        .imm-moon { opacity: 0; scale: .4; rotate: 90deg; }
        .imm-switch input:checked ~ .imm-track .imm-moon { rotate: 0deg; opacity: 1; scale: 1; }
        .imm-pass { display: flex; align-items: center; gap: .5rem; padding: .5rem; border: 1px solid #e7e5e4; border-radius: .75rem; }
        .imm-lid { transform-box: fill-box; transform-origin: 50% 0%; scale: 1 0; transition: scale .22s ease; }
        .imm-eye[data-shown='false'] .imm-lid { scale: 1 1; }
        .imm-slash { stroke-dasharray: 1; stroke-dashoffset: 1; transition: stroke-dashoffset .22s ease .06s; }
        .imm-eye[data-shown='false'] .imm-slash { stroke-dashoffset: 0; }
        @keyframes imm-pop { 0% { scale: .8; } 55% { scale: 1.12; } 100% { scale: 1; } }
        @media (prefers-reduced-motion: reduce) {
            .imm-burger span, .imm-menu, .imm-box path, .imm-knob, .imm-knob svg,
            .imm-rays line, .imm-lid, .imm-slash { transition-duration: .01ms; }
        }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="imm" x-data="{ menu: false, shown: false }">
            {{-- hamburger ⇄ x --}}
            <button type="button" class="imm-burger" x-bind:aria-expanded="menu ? 'true' : 'false'"
                    x-on:click="menu = !menu">
                <span></span><span></span><span></span>
            </button>
            <div class="imm-menu" x-bind:data-open="menu ? 'true' : 'false'">
                <div class="imm-menuin"><ul><li><a href="#files">Files</a></li></ul></div>
            </div>

            {{-- checkbox with a drawing tick: native input + :checked --}}
            <label class="imm-check">
                <input type="checkbox">
                <span class="imm-box"><svg viewBox="0 0 24 24"><path pathLength="1" d="M5 12.5l4.5 4.5L19 7.5"/></svg></span>
                <span>I confirm the build passed all checks</span>
            </label>

            {{-- sun ⇄ moon switch: native input + :checked ~ --}}
            <label class="imm-switch">
                <input type="checkbox" role="switch">
                <span class="imm-track"><span class="imm-knob">
                    <svg class="imm-sun" viewBox="0 0 24 24"><g class="imm-rays"><line x1="12" y1="2.5" x2="12" y2="4.5"/><line x1="12" y1="19.5" x2="12" y2="21.5"/><line x1="2.5" y1="12" x2="4.5" y2="12"/><line x1="19.5" y1="12" x2="21.5" y2="12"/></g><circle cx="12" cy="12" r="4" fill="currentColor"/></svg>
                    <svg class="imm-moon" viewBox="0 0 24 24"><path fill="currentColor" d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
                </span></span>
                <span>Night mode</span>
            </label>

            {{-- the password eye that blinks --}}
            <div class="imm-pass">
                <input :type="shown ? 'text' : 'password'" placeholder="At least 8 characters">
                <button type="button" class="imm-eye" x-bind:data-shown="shown ? 'true' : 'false'"
                        x-on:click="shown = !shown" :aria-label="shown ? 'Hide password' : 'Show password'">
                    <svg viewBox="0 0 24 24">
                        <path class="imm-line" d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12Z"/>
                        <circle cx="12" cy="12" r="3" fill="currentColor"/>
                        <path class="imm-line imm-lid" d="M4.5 7.5C3 9 2.5 10.5 2.5 12"/>
                        <path class="imm-line imm-slash" pathLength="1" d="M4 4l16 16"/>
                    </svg>
                </button>
            </div>
        </div>

        <style>
        .imm-burger { display: inline-grid; gap: 6px; align-content: center; inline-size: 2.6rem; block-size: 2.6rem;
                      border: 1px solid #e7e5e4; border-radius: .7rem; background: #fff; cursor: pointer; }
        .imm-burger span { display: block; inline-size: 1.15rem; block-size: 2px; margin-inline: auto; background: currentColor;
                           transition: translate .3s, rotate .3s, opacity .2s, scale .3s; }
        .imm-burger[aria-expanded='true'] span:nth-child(1) { translate: 0 8px; rotate: 45deg; }
        .imm-burger[aria-expanded='true'] span:nth-child(2) { opacity: 0; scale: .2 1; }
        .imm-burger[aria-expanded='true'] span:nth-child(3) { translate: 0 -8px; rotate: -45deg; }
        .imm-menu { display: grid; grid-template-rows: 0fr; transition: grid-template-rows .35s; }
        .imm-menu[data-open='true'] { grid-template-rows: 1fr; }
        .imm-menuin { overflow: hidden; }
        .imm-check input, .imm-switch input { position: absolute; inline-size: 1px; block-size: 1px; opacity: 0; }
        .imm-box { display: grid; place-items: center; inline-size: 1.35rem; block-size: 1.35rem;
                   border: 2px solid #e7e5e4; border-radius: .45rem; }
        .imm-box path { fill: none; stroke: #fff; stroke-width: 3; stroke-linecap: round; stroke-linejoin: round;
                        stroke-dasharray: 1; stroke-dashoffset: 1; transition: stroke-dashoffset .3s ease .05s; }
        .imm-check input:checked + .imm-box { background: #4f46e5; border-color: #4f46e5; animation: imm-pop .3s; }
        .imm-check input:checked + .imm-box path { stroke-dashoffset: 0; }
        .imm-track { position: relative; display: block; inline-size: 3.4rem; block-size: 1.9rem; border-radius: 999px; background: #e7e5e4; }
        .imm-knob { position: absolute; inset-block-start: .25rem; inset-inline-start: .25rem; display: grid; place-items: center;
                    inline-size: 1.4rem; block-size: 1.4rem; border-radius: 50%; background: #fff;
                    transition: inset-inline-start .35s; }
        .imm-knob svg { position: absolute; inline-size: .9rem; transition: rotate .45s, opacity .3s, scale .3s; }
        .imm-rays line { transition: scale .3s; transform-box: fill-box; transform-origin: center; }
        .imm-switch input:checked ~ .imm-track { background: #312e81; }
        .imm-switch input:checked ~ .imm-track .imm-knob { inset-inline-start: calc(100% - 1.65rem); }
        .imm-switch input:checked ~ .imm-track .imm-sun { rotate: -100deg; opacity: 0; scale: .4; }
        .imm-switch input:checked ~ .imm-track .imm-rays line { scale: 0; }
        .imm-moon { opacity: 0; scale: .4; rotate: 90deg; }
        .imm-switch input:checked ~ .imm-track .imm-moon { rotate: 0deg; opacity: 1; scale: 1; }
        .imm-pass { display: flex; align-items: center; gap: .5rem; padding: .5rem; border: 1px solid #e7e5e4; border-radius: .75rem; }
        .imm-lid { transform-box: fill-box; transform-origin: 50% 0%; scale: 1 0; transition: scale .22s ease; }
        .imm-eye[data-shown='false'] .imm-lid { scale: 1 1; }
        .imm-slash { stroke-dasharray: 1; stroke-dashoffset: 1; transition: stroke-dashoffset .22s ease .06s; }
        .imm-eye[data-shown='false'] .imm-slash { stroke-dashoffset: 0; }
        @keyframes imm-pop { 0% { scale: .8; } 55% { scale: 1.12; } 100% { scale: 1; } }
        @media (prefers-reduced-motion: reduce) {
            .imm-burger span, .imm-menu, .imm-box path, .imm-knob, .imm-knob svg,
            .imm-rays line, .imm-lid, .imm-slash { transition-duration: .01ms; }
        }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/IconMotion.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import MicroControls from '@/components/MicroControls';

        export default function IconMotion() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <MicroControls />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // MicroControls.jsx — icon micro-controls riding native inputs
        import { useState } from 'react';
        import '@nabuxai/ui-core/css';

        export default function MicroControls() {
          const [menu, setMenu] = useState(false);
          const [shown, setShown] = useState(false);

          return (
            <>
              <style>{`
                .imm-burger { display: inline-grid; gap: 6px; align-content: center; inline-size: 2.6rem; block-size: 2.6rem;
                              border: 1px solid #e7e5e4; border-radius: .7rem; background: #fff; cursor: pointer; }
                .imm-burger span { display: block; inline-size: 1.15rem; block-size: 2px; margin-inline: auto; background: currentColor;
                                   transition: translate .3s, rotate .3s, opacity .2s, scale .3s; }
                .imm-burger[aria-expanded='true'] span:nth-child(1) { translate: 0 8px; rotate: 45deg; }
                .imm-burger[aria-expanded='true'] span:nth-child(2) { opacity: 0; scale: .2 1; }
                .imm-burger[aria-expanded='true'] span:nth-child(3) { translate: 0 -8px; rotate: -45deg; }
                .imm-menu { display: grid; grid-template-rows: 0fr; transition: grid-template-rows .35s; }
                .imm-menu[data-open='true'] { grid-template-rows: 1fr; }
                .imm-menuin { overflow: hidden; }
                .imm-check input, .imm-switch input { position: absolute; inline-size: 1px; block-size: 1px; opacity: 0; }
                .imm-box { display: grid; place-items: center; inline-size: 1.35rem; block-size: 1.35rem;
                           border: 2px solid #e7e5e4; border-radius: .45rem; }
                .imm-box path { fill: none; stroke: #fff; stroke-width: 3; stroke-linecap: round; stroke-linejoin: round;
                                stroke-dasharray: 1; stroke-dashoffset: 1; transition: stroke-dashoffset .3s ease .05s; }
                .imm-check input:checked + .imm-box { background: #4f46e5; border-color: #4f46e5; animation: imm-pop .3s; }
                .imm-check input:checked + .imm-box path { stroke-dashoffset: 0; }
                .imm-track { position: relative; display: block; inline-size: 3.4rem; block-size: 1.9rem; border-radius: 999px; background: #e7e5e4; }
                .imm-knob { position: absolute; inset-block-start: .25rem; inset-inline-start: .25rem; display: grid; place-items: center;
                            inline-size: 1.4rem; block-size: 1.4rem; border-radius: 50%; background: #fff; transition: inset-inline-start .35s; }
                .imm-knob svg { position: absolute; inline-size: .9rem; transition: rotate .45s, opacity .3s, scale .3s; }
                .imm-rays line { transition: scale .3s; transform-box: fill-box; transform-origin: center; }
                .imm-switch input:checked ~ .imm-track { background: #312e81; }
                .imm-switch input:checked ~ .imm-track .imm-knob { inset-inline-start: calc(100% - 1.65rem); }
                .imm-switch input:checked ~ .imm-track .imm-sun { rotate: -100deg; opacity: 0; scale: .4; }
                .imm-switch input:checked ~ .imm-track .imm-rays line { scale: 0; }
                .imm-moon { opacity: 0; scale: .4; rotate: 90deg; }
                .imm-switch input:checked ~ .imm-track .imm-moon { rotate: 0deg; opacity: 1; scale: 1; }
                .imm-pass { display: flex; align-items: center; gap: .5rem; padding: .5rem; border: 1px solid #e7e5e4; border-radius: .75rem; }
                .imm-lid { transform-box: fill-box; transform-origin: 50% 0%; scale: 1 0; transition: scale .22s ease; }
                .imm-eye[data-shown='false'] .imm-lid { scale: 1 1; }
                .imm-slash { stroke-dasharray: 1; stroke-dashoffset: 1; transition: stroke-dashoffset .22s ease .06s; }
                .imm-eye[data-shown='false'] .imm-slash { stroke-dashoffset: 0; }
                @keyframes imm-pop { 0% { scale: .8; } 55% { scale: 1.12; } 100% { scale: 1; } }
                @media (prefers-reduced-motion: reduce) {
                  .imm-burger span, .imm-menu, .imm-box path, .imm-knob, .imm-knob svg,
                  .imm-rays line, .imm-lid, .imm-slash { transition-duration: .01ms; }
                }
              `}</style>
              {/* Hamburger ⇄ X — aria-expanded carries the state. */}
              <button type="button" className="imm-burger" aria-expanded={menu} onClick={() => setMenu(!menu)}>
                <span aria-hidden="true" /><span aria-hidden="true" /><span aria-hidden="true" />
                <span className="sr-only">{menu ? 'Close menu' : 'Open menu'}</span>
              </button>
              <div className="imm-menu" data-open={menu}>
                <div className="imm-menuin">
                  <ul><li><a href="#files">Files</a></li></ul>
                </div>
              </div>

              {/* Checkbox — the native input drives the drawn tick through :checked. */}
              <label className="imm-check">
                <input type="checkbox" />
                <span className="imm-box">
                  <svg viewBox="0 0 24 24" aria-hidden="true"><path pathLength="1" d="M5 12.5l4.5 4.5L19 7.5" /></svg>
                </span>
                <span>I confirm the build passed all checks</span>
              </label>

              {/* Sun ⇄ moon — rays fold, the disc rotates out, the moon rises. */}
              <label className="imm-switch">
                <input type="checkbox" role="switch" />
                <span className="imm-track">
                  <span className="imm-knob">
                    <svg className="imm-sun" viewBox="0 0 24 24" aria-hidden="true">
                      <g className="imm-rays">
                        <line x1="12" y1="2.5" x2="12" y2="4.5" /><line x1="12" y1="19.5" x2="12" y2="21.5" />
                        <line x1="2.5" y1="12" x2="4.5" y2="12" /><line x1="19.5" y1="12" x2="21.5" y2="12" />
                      </g>
                      <circle cx="12" cy="12" r="4" fill="currentColor" />
                    </svg>
                    <svg className="imm-moon" viewBox="0 0 24 24" aria-hidden="true">
                      <path fill="currentColor" d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" />
                    </svg>
                  </span>
                </span>
                <span>Night mode</span>
              </label>

              {/* Password eye — the input type really flips. */}
              <div className="imm-pass">
                <input type={shown ? 'text' : 'password'} placeholder="At least 8 characters" aria-label="Account password" />
                <button type="button" className="imm-eye" data-shown={shown} aria-pressed={shown}
                  aria-label={shown ? 'Hide password' : 'Show password'} onClick={() => setShown(!shown)}>
                  <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12Z" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
                    <circle cx="12" cy="12" r="3" fill="currentColor" />
                    <path className="imm-lid" d="M4.5 7.5C3 9 2.5 10.5 2.5 12" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" />
                    <path className="imm-slash" pathLength="1" d="M4 4l16 16" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" />
                  </svg>
                </button>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- MicroControls.vue — icon micro-controls riding native inputs -->
        <script setup lang="ts">
        import { ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const menu = ref(false);
        const shown = ref(false);
        </script>

        <template>
          <!-- Hamburger ⇄ X — aria-expanded carries the state. -->
          <button type="button" class="imm-burger" :aria-expanded="menu" @click="menu = !menu">
            <span aria-hidden="true" /><span aria-hidden="true" /><span aria-hidden="true" />
          </button>
          <div class="imm-menu" :data-open="menu">
            <div class="imm-menuin"><ul><li><a href="#files">Files</a></li></ul></div>
          </div>

          <!-- Checkbox — the native input drives the drawn tick through :checked. -->
          <label class="imm-check">
            <input type="checkbox" />
            <span class="imm-box">
              <svg viewBox="0 0 24 24" aria-hidden="true"><path pathLength="1" d="M5 12.5l4.5 4.5L19 7.5" /></svg>
            </span>
            <span>I confirm the build passed all checks</span>
          </label>

          <!-- Sun ⇄ moon — rays fold, the disc rotates out, the moon rises. -->
          <label class="imm-switch">
            <input type="checkbox" role="switch" />
            <span class="imm-track">
              <span class="imm-knob">
                <svg class="imm-sun" viewBox="0 0 24 24" aria-hidden="true">
                  <g class="imm-rays">
                    <line x1="12" y1="2.5" x2="12" y2="4.5" /><line x1="12" y1="19.5" x2="12" y2="21.5" />
                    <line x1="2.5" y1="12" x2="4.5" y2="12" /><line x1="19.5" y1="12" x2="21.5" y2="12" />
                  </g>
                  <circle cx="12" cy="12" r="4" fill="currentColor" />
                </svg>
                <svg class="imm-moon" viewBox="0 0 24 24" aria-hidden="true">
                  <path fill="currentColor" d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" />
                </svg>
              </span>
            </span>
            <span>Night mode</span>
          </label>

          <!-- Password eye — the input type really flips. -->
          <div class="imm-pass">
            <input :type="shown ? 'text' : 'password'" placeholder="At least 8 characters" aria-label="Account password" />
            <button type="button" class="imm-eye" :data-shown="shown" :aria-pressed="shown"
              :aria-label="shown ? 'Hide password' : 'Show password'" @click="shown = !shown">
              <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12Z" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
                <circle cx="12" cy="12" r="3" fill="currentColor" />
                <path class="imm-lid" d="M4.5 7.5C3 9 2.5 10.5 2.5 12" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" />
                <path class="imm-slash" pathLength="1" d="M4 4l16 16" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" />
              </svg>
            </button>
          </div>
        </template>

        <style scoped>
        .imm-burger { display: inline-grid; gap: 6px; align-content: center; inline-size: 2.6rem; block-size: 2.6rem;
                      border: 1px solid #e7e5e4; border-radius: .7rem; background: #fff; cursor: pointer; }
        .imm-burger span { display: block; inline-size: 1.15rem; block-size: 2px; margin-inline: auto; background: currentColor;
                           transition: translate .3s, rotate .3s, opacity .2s, scale .3s; }
        .imm-burger[aria-expanded='true'] span:nth-child(1) { translate: 0 8px; rotate: 45deg; }
        .imm-burger[aria-expanded='true'] span:nth-child(2) { opacity: 0; scale: .2 1; }
        .imm-burger[aria-expanded='true'] span:nth-child(3) { translate: 0 -8px; rotate: -45deg; }
        .imm-menu { display: grid; grid-template-rows: 0fr; transition: grid-template-rows .35s; }
        .imm-menu[data-open='true'] { grid-template-rows: 1fr; }
        .imm-menuin { overflow: hidden; }
        .imm-check input, .imm-switch input { position: absolute; inline-size: 1px; block-size: 1px; opacity: 0; }
        .imm-box { display: grid; place-items: center; inline-size: 1.35rem; block-size: 1.35rem;
                   border: 2px solid #e7e5e4; border-radius: .45rem; }
        .imm-box path { fill: none; stroke: #fff; stroke-width: 3; stroke-linecap: round; stroke-linejoin: round;
                        stroke-dasharray: 1; stroke-dashoffset: 1; transition: stroke-dashoffset .3s ease .05s; }
        .imm-check input:checked + .imm-box { background: #4f46e5; border-color: #4f46e5; animation: imm-pop .3s; }
        .imm-check input:checked + .imm-box path { stroke-dashoffset: 0; }
        .imm-track { position: relative; display: block; inline-size: 3.4rem; block-size: 1.9rem; border-radius: 999px; background: #e7e5e4; }
        .imm-knob { position: absolute; inset-block-start: .25rem; inset-inline-start: .25rem; display: grid; place-items: center;
                    inline-size: 1.4rem; block-size: 1.4rem; border-radius: 50%; background: #fff; transition: inset-inline-start .35s; }
        .imm-knob svg { position: absolute; inline-size: .9rem; transition: rotate .45s, opacity .3s, scale .3s; }
        .imm-rays line { transition: scale .3s; transform-box: fill-box; transform-origin: center; }
        .imm-switch input:checked ~ .imm-track { background: #312e81; }
        .imm-switch input:checked ~ .imm-track .imm-knob { inset-inline-start: calc(100% - 1.65rem); }
        .imm-switch input:checked ~ .imm-track .imm-sun { rotate: -100deg; opacity: 0; scale: .4; }
        .imm-switch input:checked ~ .imm-track .imm-rays line { scale: 0; }
        .imm-moon { opacity: 0; scale: .4; rotate: 90deg; }
        .imm-switch input:checked ~ .imm-track .imm-moon { rotate: 0deg; opacity: 1; scale: 1; }
        .imm-pass { display: flex; align-items: center; gap: .5rem; padding: .5rem; border: 1px solid #e7e5e4; border-radius: .75rem; }
        .imm-lid { transform-box: fill-box; transform-origin: 50% 0%; scale: 1 0; transition: scale .22s ease; }
        .imm-eye[data-shown='false'] .imm-lid { scale: 1 1; }
        .imm-slash { stroke-dasharray: 1; stroke-dashoffset: 1; transition: stroke-dashoffset .22s ease .06s; }
        .imm-eye[data-shown='false'] .imm-slash { stroke-dashoffset: 0; }
        @keyframes imm-pop { 0% { scale: .8; } 55% { scale: 1.12; } 100% { scale: 1; } }
        @media (prefers-reduced-motion: reduce) {
          .imm-burger span, .imm-menu, .imm-box path, .imm-knob, .imm-knob svg,
          .imm-rays line, .imm-lid, .imm-slash { transition-duration: .01ms; }
        }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- MicroControls.svelte — icon micro-controls riding native inputs -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';
          let menu = $state(false);
          let shown = $state(false);
        </script>

        <!-- Hamburger ⇄ X — aria-expanded carries the state. -->
        <button type="button" class="imm-burger" aria-expanded={menu} onclick={() => (menu = !menu)}>
          <span aria-hidden="true" /><span aria-hidden="true" /><span aria-hidden="true" />
        </button>
        <div class="imm-menu" data-open={menu}>
          <div class="imm-menuin"><ul><li><a href="#files">Files</a></li></ul></div>
        </div>

        <!-- Checkbox — the native input drives the drawn tick through :checked. -->
        <label class="imm-check">
          <input type="checkbox" />
          <span class="imm-box">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path pathLength="1" d="M5 12.5l4.5 4.5L19 7.5" /></svg>
          </span>
          <span>I confirm the build passed all checks</span>
        </label>

        <!-- Sun ⇄ moon — rays fold, the disc rotates out, the moon rises. -->
        <label class="imm-switch">
          <input type="checkbox" role="switch" />
          <span class="imm-track">
            <span class="imm-knob">
              <svg class="imm-sun" viewBox="0 0 24 24" aria-hidden="true">
                <g class="imm-rays">
                  <line x1="12" y1="2.5" x2="12" y2="4.5" /><line x1="12" y1="19.5" x2="12" y2="21.5" />
                  <line x1="2.5" y1="12" x2="4.5" y2="12" /><line x1="19.5" y1="12" x2="21.5" y2="12" />
                </g>
                <circle cx="12" cy="12" r="4" fill="currentColor" />
              </svg>
              <svg class="imm-moon" viewBox="0 0 24 24" aria-hidden="true">
                <path fill="currentColor" d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" />
              </svg>
            </span>
          </span>
          <span>Night mode</span>
        </label>

        <!-- Password eye — the input type really flips. -->
        <div class="imm-pass">
          <input type={shown ? 'text' : 'password'} placeholder="At least 8 characters" aria-label="Account password" />
          <button type="button" class="imm-eye" data-shown={shown} aria-pressed={shown}
            aria-label={shown ? 'Hide password' : 'Show password'} onclick={() => (shown = !shown)}>
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12Z" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
              <circle cx="12" cy="12" r="3" fill="currentColor" />
              <path class="imm-lid" d="M4.5 7.5C3 9 2.5 10.5 2.5 12" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" />
              <path class="imm-slash" pathLength="1" d="M4 4l16 16" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" />
            </svg>
          </button>
        </div>

        <style>
        .imm-burger { display: inline-grid; gap: 6px; align-content: center; inline-size: 2.6rem; block-size: 2.6rem;
                      border: 1px solid #e7e5e4; border-radius: .7rem; background: #fff; cursor: pointer; }
        .imm-burger span { display: block; inline-size: 1.15rem; block-size: 2px; margin-inline: auto; background: currentColor;
                           transition: translate .3s, rotate .3s, opacity .2s, scale .3s; }
        .imm-burger[aria-expanded='true'] span:nth-child(1) { translate: 0 8px; rotate: 45deg; }
        .imm-burger[aria-expanded='true'] span:nth-child(2) { opacity: 0; scale: .2 1; }
        .imm-burger[aria-expanded='true'] span:nth-child(3) { translate: 0 -8px; rotate: -45deg; }
        .imm-menu { display: grid; grid-template-rows: 0fr; transition: grid-template-rows .35s; }
        .imm-menu[data-open='true'] { grid-template-rows: 1fr; }
        .imm-menuin { overflow: hidden; }
        .imm-check input, .imm-switch input { position: absolute; inline-size: 1px; block-size: 1px; opacity: 0; }
        .imm-box { display: grid; place-items: center; inline-size: 1.35rem; block-size: 1.35rem;
                   border: 2px solid #e7e5e4; border-radius: .45rem; }
        .imm-box path { fill: none; stroke: #fff; stroke-width: 3; stroke-linecap: round; stroke-linejoin: round;
                        stroke-dasharray: 1; stroke-dashoffset: 1; transition: stroke-dashoffset .3s ease .05s; }
        .imm-check input:checked + .imm-box { background: #4f46e5; border-color: #4f46e5; animation: imm-pop .3s; }
        .imm-check input:checked + .imm-box path { stroke-dashoffset: 0; }
        .imm-track { position: relative; display: block; inline-size: 3.4rem; block-size: 1.9rem; border-radius: 999px; background: #e7e5e4; }
        .imm-knob { position: absolute; inset-block-start: .25rem; inset-inline-start: .25rem; display: grid; place-items: center;
                    inline-size: 1.4rem; block-size: 1.4rem; border-radius: 50%; background: #fff; transition: inset-inline-start .35s; }
        .imm-knob svg { position: absolute; inline-size: .9rem; transition: rotate .45s, opacity .3s, scale .3s; }
        .imm-rays line { transition: scale .3s; transform-box: fill-box; transform-origin: center; }
        .imm-switch input:checked ~ .imm-track { background: #312e81; }
        .imm-switch input:checked ~ .imm-track .imm-knob { inset-inline-start: calc(100% - 1.65rem); }
        .imm-switch input:checked ~ .imm-track .imm-sun { rotate: -100deg; opacity: 0; scale: .4; }
        .imm-switch input:checked ~ .imm-track .imm-rays line { scale: 0; }
        .imm-moon { opacity: 0; scale: .4; rotate: 90deg; }
        .imm-switch input:checked ~ .imm-track .imm-moon { rotate: 0deg; opacity: 1; scale: 1; }
        .imm-pass { display: flex; align-items: center; gap: .5rem; padding: .5rem; border: 1px solid #e7e5e4; border-radius: .75rem; }
        .imm-lid { transform-box: fill-box; transform-origin: 50% 0%; scale: 1 0; transition: scale .22s ease; }
        .imm-eye[data-shown='false'] .imm-lid { scale: 1 1; }
        .imm-slash { stroke-dasharray: 1; stroke-dashoffset: 1; transition: stroke-dashoffset .22s ease .06s; }
        .imm-eye[data-shown='false'] .imm-slash { stroke-dashoffset: 0; }
        @keyframes imm-pop { 0% { scale: .8; } 55% { scale: 1.12; } 100% { scale: 1; } }
        @media (prefers-reduced-motion: reduce) {
          .imm-burger span, .imm-menu, .imm-box path, .imm-knob, .imm-knob svg,
          .imm-rays line, .imm-lid, .imm-slash { transition-duration: .01ms; }
        }
        </style>
        SVELTE,
        ],
    ],

    'self-draw' => [
        'title' => ['fa' => 'تصویرسازی خودترسیم', 'en' => 'Self-drawing scene'],
        'icon' => 'edit',
        'oneLiner' => [
            'fa' => 'صحنهٔ خطی SVG — پنجره، تپه‌ها، لپ‌تاپ، فنجان با بخارش و گلدان — که با اسکرول داخل استیج یا دکمهٔ «بکش» لایه‌به‌لایه و به‌ترتیب خودش را می‌کشد و آخر سر رنگ‌ها روی خطوط می‌نشینند؛ پاک‌کردن، ری‌پلی و شمارندهٔ مسیرهای ترسیم‌شده.',
            'en' => 'A linear SVG scene — window, hills, laptop, steaming mug and plant — that inks itself layer by layer, in order, as you scroll inside the stage or press “draw”; the colours settle over the lines at the end, with erase, replay and a drawn-paths counter.',
        ],
        'js' => true,
        'docs' => 'https://svgator.com',
        'props' => [
            ['name' => 'paths', 'type' => 'count', 'default' => "'17'", 'note' => [
                'fa' => 'هفده مسیر به ترتیب لایه: خط میز، قاب و شیشه‌های پنجره، تپه‌ها، صفحه و قاعدهٔ لپ‌تاپ، کیبورد، فنجان و دسته‌اش، دو بخار و گلدان با سه برگ.',
                'en' => 'Seventeen paths in layer order: the desk line, the window frame and mullions, the hills, the laptop screen and base, the keyboard, the mug and its handle, two steam curls, the plant with three leaves.',
            ]],
            ['name' => 'progress', 'type' => 'scroll', 'default' => "'scrollTop / max'", 'note' => [
                'fa' => 'موقعیت اسکرول استیج به تعداد مسیرها نگاشت می‌شود؛ صحنه استیکی می‌ایستد و فاصله‌ساز زیرش طول سفر را می‌دهد. استیج فوکوس‌پذیر است تا فلش‌های کیبورد هم بکشند.',
                'en' => 'The stage’s scroll position maps onto the path count; the scene stays sticky while a spacer below it buys the travel. The stage is focusable so arrow keys draw too.',
            ]],
            ['name' => 'colors', 'type' => 'settle', 'default' => "'p ≥ 0.97'", 'note' => [
                'fa' => 'لایهٔ رنگ پشت خطوط است و وقتی همهٔ مسیرها تمام شد با یک محوِ ۹۰۰ میلی‌ثانیه‌ای روی صحنه می‌نشیند.',
                'en' => 'The colour layer sits behind the ink and settles over the scene with a 900ms fade once every path is in.',
            ]],
            ['name' => 'draw()', 'type' => 'interval', 'default' => "'16ms · 7px'", 'note' => [
                'fa' => 'دکمهٔ بکش همان اسکرول را برنامه‌ای جلو می‌برد؛ تایمر در destroy و در erase پاک می‌شود.',
                'en' => 'The draw button advances that same scroll programmatically; the timer is cleared on destroy and on erase.',
            ]],
            ['name' => 'reduced-motion', 'type' => 'media', 'default' => "'instant'", 'note' => [
                'fa' => 'ترنزیشن‌ها حذف می‌شوند؛ هر مسیر بی‌درنگ با رسیدن نوبتش کامل دیده می‌شود و رنگ‌ها هم بی‌وقفه می‌نشینند.',
                'en' => 'Transitions drop out; each path shows complete the moment its turn arrives and the colours land without the fade.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="imw" x-data="{
            total: 17, drawn: 0, colored: false, t: null,
            onScroll() {
                const el = this.$refs.stage;
                const max = el.scrollHeight - el.clientHeight;
                const p = max > 0 ? el.scrollTop / max : 1;
                this.drawn = Math.round(p * this.total);
                this.colored = p >= 0.97;
            },
            erase() {
                clearInterval(this.t);
                this.$refs.stage.scrollTop = 0;
                this.drawn = 0;
                this.colored = false;
            },
            draw() {
                this.erase();
                this.t = setInterval(() => {
                    const el = this.$refs.stage;
                    const max = el.scrollHeight - el.clientHeight;
                    if (el.scrollTop >= max - 1) { clearInterval(this.t); this.onScroll(); }
                    else { el.scrollTop = Math.min(max, el.scrollTop + 7); this.onScroll(); }
                }, 16);
            },
        }" x-init="$cleanup(() => clearInterval(t))">
            <div class="imw-stage" x-ref="stage" x-on:scroll="onScroll()" tabindex="0" role="img"
                 aria-label="تصویرسازی خطی ورک‌اسپیس که با اسکرول ترسیم می‌شود">
                <svg class="imw-scene" viewBox="0 0 320 200" aria-hidden="true">
                    {{-- لایهٔ رنگ پشت خطوط، آخر کار می‌نشیند --}}
                    <g class="imw-colors" x-bind:data-on="colored ? 'true' : 'false'">
                        <rect x="30" y="36" width="78" height="104" fill="#bfdbfe" opacity=".55"/>
                        <rect x="152" y="84" width="92" height="58" fill="#c7d2fe" opacity=".45"/>
                    </g>
                    @foreach ($paths as $i => $d)
                        <path class="imw-ink" pathLength="1" d="{{ $d }}" x-bind:data-drawn="drawn > {{ $i }}" />
                    @endforeach
                </svg>
                <div class="imw-spacer" aria-hidden="true"></div>
            </div>

            <div class="imw-toolbar">
                <button type="button" class="imw-btn" x-on:click="draw()">
                    <svg viewBox="0 0 24 24"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>
                    بکش
                </button>
                <button type="button" class="imw-btn" x-on:click="erase()">
                    <svg viewBox="0 0 24 24"><path d="M4 19h16"/><path d="m6 15 8-8 4 4-8 8H6z"/></svg>
                    پاک‌کردن
                </button>
                <span class="imw-count" role="status"
                      x-text="String(drawn).replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]) + ' / ' + String(total).replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]) + ' مسیر'"></span>
            </div>
        </div>

        <style>
        .imw-stage { block-size: 15.5rem; overflow-y: auto; border: 1px solid #e7e5e4;
                     border-radius: 1.1rem; background: #fff; }
        .imw-scene { position: sticky; inset-block-start: 0; display: block; inline-size: 100%;
                     block-size: 15.5rem; background: #fff; }
        .imw-spacer { block-size: 34rem; }
        .imw-colors { opacity: 0; transition: opacity .9s ease; }
        .imw-colors[data-on='true'] { opacity: 1; }
        .imw-ink { fill: none; stroke: #1c1917; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round;
                   stroke-dasharray: 1; stroke-dashoffset: 1; transition: stroke-dashoffset .3s ease; }
        .imw-ink[data-drawn='true'] { stroke-dashoffset: 0; }
        @media (prefers-reduced-motion: reduce) {
            .imw-ink, .imw-colors { transition: none; }
        }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="imw" x-data="{
            total: 17, drawn: 0, colored: false, t: null,
            onScroll() {
                const el = this.$refs.stage;
                const max = el.scrollHeight - el.clientHeight;
                const p = max > 0 ? el.scrollTop / max : 1;
                this.drawn = Math.round(p * this.total);
                this.colored = p >= 0.97;
            },
            erase() {
                clearInterval(this.t);
                this.$refs.stage.scrollTop = 0;
                this.drawn = 0;
                this.colored = false;
            },
            draw() {
                this.erase();
                this.t = setInterval(() => {
                    const el = this.$refs.stage;
                    const max = el.scrollHeight - el.clientHeight;
                    if (el.scrollTop >= max - 1) { clearInterval(this.t); this.onScroll(); }
                    else { el.scrollTop = Math.min(max, el.scrollTop + 7); this.onScroll(); }
                }, 16);
            },
        }" x-init="$cleanup(() => clearInterval(t))">
            <div class="imw-stage" x-ref="stage" x-on:scroll="onScroll()" tabindex="0" role="img"
                 aria-label="Line illustration of a workspace, drawn by scrolling">
                <svg class="imw-scene" viewBox="0 0 320 200" aria-hidden="true">
                    {{-- the colour layer sits behind the ink and settles at the end --}}
                    <g class="imw-colors" x-bind:data-on="colored ? 'true' : 'false'">
                        <rect x="30" y="36" width="78" height="104" fill="#bfdbfe" opacity=".55" />
                        <rect x="152" y="84" width="92" height="58" fill="#c7d2fe" opacity=".45" />
                    </g>
                    @foreach ($paths as $i => $d)
                        <path class="imw-ink" pathLength="1" d="{{ $d }}" x-bind:data-drawn="drawn > {{ $i }}" />
                    @endforeach
                </svg>
                <div class="imw-spacer" aria-hidden="true"></div>
            </div>

            <div class="imw-toolbar">
                <button type="button" class="imw-btn" x-on:click="draw()">
                    <svg viewBox="0 0 24 24"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>
                    Draw
                </button>
                <button type="button" class="imw-btn" x-on:click="erase()">
                    <svg viewBox="0 0 24 24"><path d="M4 19h16"/><path d="m6 15 8-8 4 4-8 8H6z"/></svg>
                    Erase
                </button>
                <span class="imw-count" role="status" x-text="drawn + ' / ' + total + ' paths'"></span>
            </div>
        </div>

        <style>
        .imw-stage { block-size: 15.5rem; overflow-y: auto; border: 1px solid #e7e5e4;
                     border-radius: 1.1rem; background: #fff; }
        .imw-scene { position: sticky; inset-block-start: 0; display: block; inline-size: 100%;
                     block-size: 15.5rem; background: #fff; }
        .imw-spacer { block-size: 34rem; }
        .imw-colors { opacity: 0; transition: opacity .9s ease; }
        .imw-colors[data-on='true'] { opacity: 1; }
        .imw-ink { fill: none; stroke: #1c1917; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round;
                   stroke-dasharray: 1; stroke-dashoffset: 1; transition: stroke-dashoffset .3s ease; }
        .imw-ink[data-drawn='true'] { stroke-dashoffset: 0; }
        @media (prefers-reduced-motion: reduce) {
            .imw-ink, .imw-colors { transition: none; }
        }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/IconMotion.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import SelfDraw from '@/components/SelfDraw';

        export default function IconMotion() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <SelfDraw />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // SelfDraw.jsx — a scene that inks itself layer by layer on scroll
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        // Desk, window, hills, laptop, mug, steam, plant — in draw order.
        const PATHS = [
          'M14 170h292', 'M28 34h82v108H28z', 'M69 34v108', 'M28 88h82',
          'M32 134q16-22 32 0', 'M62 134q12-16 24 0',
          'M150 82h96v62h-96z', 'M138 144h136l10 16H128z', 'M156 156h92',
          'M266 118h26v34a8 8 0 0 1-8 8h-10a8 8 0 0 1-8-8z', 'M292 126a9 9 0 0 1 0 18',
          'M272 106q-4-6 0-12', 'M284 106q-4-6 0-12',
          'M98 150h28l-4 20h-20z', 'M112 150c-2-14-12-24-24-26', 'M112 150c2-16 12-26 24-28', 'M112 150v-28',
        ];

        export default function SelfDraw() {
          const stageRef = useRef(null);
          const timerRef = useRef(null);
          const [drawn, setDrawn] = useState(0);
          const [colored, setColored] = useState(false);

          useEffect(() => () => clearInterval(timerRef.current), []);

          // The scroll position maps onto the path count; colours settle at the end.
          const onScroll = () => {
            const el = stageRef.current;
            const max = el.scrollHeight - el.clientHeight;
            const p = max > 0 ? el.scrollTop / max : 1;
            setDrawn(Math.round(p * PATHS.length));
            setColored(p >= 0.97);
          };
          const erase = () => {
            clearInterval(timerRef.current);
            stageRef.current.scrollTop = 0;
            setDrawn(0);
            setColored(false);
          };
          const draw = () => {
            erase();
            timerRef.current = setInterval(() => {
              const el = stageRef.current;
              const max = el.scrollHeight - el.clientHeight;
              if (el.scrollTop >= max - 1) { clearInterval(timerRef.current); onScroll(); }
              else { el.scrollTop = Math.min(max, el.scrollTop + 7); onScroll(); }
            }, 16);
          };

          return (
            <>
              <style>{`
                .imw-stage { block-size: 15.5rem; overflow-y: auto; border: 1px solid #e7e5e4;
                             border-radius: 1.1rem; background: #fff; }
                .imw-scene { position: sticky; inset-block-start: 0; display: block; inline-size: 100%;
                             block-size: 15.5rem; background: #fff; }
                .imw-spacer { block-size: 34rem; }
                .imw-colors { opacity: 0; transition: opacity .9s ease; }
                .imw-colors[data-on='true'] { opacity: 1; }
                .imw-ink { fill: none; stroke: #1c1917; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round;
                           stroke-dasharray: 1; stroke-dashoffset: 1; transition: stroke-dashoffset .3s ease; }
                .imw-ink[data-drawn='true'] { stroke-dashoffset: 0; }
                .imw-count { font: 700 .74rem/1.4 ui-monospace, monospace; color: #4f46e5; }
                @media (prefers-reduced-motion: reduce) { .imw-ink, .imw-colors { transition: none; } }
              `}</style>
              <div className="imw-stage" ref={stageRef} onScroll={onScroll} tabIndex={0} role="img"
                aria-label="Line illustration of a workspace, drawn by scrolling">
                <svg className="imw-scene" viewBox="0 0 320 200" aria-hidden="true">
                  <g className="imw-colors" data-on={colored}>
                    <rect x="30" y="36" width="78" height="104" fill="#bfdbfe" opacity=".55" />
                    <rect x="152" y="84" width="92" height="58" fill="#c7d2fe" opacity=".45" />
                  </g>
                  {PATHS.map((d, i) => (
                    <path key={i} className="imw-ink" pathLength="1" d={d} data-drawn={drawn > i} />
                  ))}
                </svg>
                <div className="imw-spacer" aria-hidden="true" />
              </div>
              <button type="button" onClick={draw}>Draw</button>
              <button type="button" onClick={erase}>Erase</button>
              <span className="imw-count" role="status">{drawn} / {PATHS.length} paths</span>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- SelfDraw.vue — a scene that inks itself layer by layer on scroll -->
        <script setup lang="ts">
        import { onBeforeUnmount, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        // Desk, window, hills, laptop, mug, steam, plant — in draw order.
        const paths = [
          'M14 170h292', 'M28 34h82v108H28z', 'M69 34v108', 'M28 88h82',
          'M32 134q16-22 32 0', 'M62 134q12-16 24 0',
          'M150 82h96v62h-96z', 'M138 144h136l10 16H128z', 'M156 156h92',
          'M266 118h26v34a8 8 0 0 1-8 8h-10a8 8 0 0 1-8-8z', 'M292 126a9 9 0 0 1 0 18',
          'M272 106q-4-6 0-12', 'M284 106q-4-6 0-12',
          'M98 150h28l-4 20h-20z', 'M112 150c-2-14-12-24-24-26', 'M112 150c2-16 12-26 24-28', 'M112 150v-28',
        ];
        const stage = ref<HTMLElement | null>(null);
        const drawn = ref(0);
        const colored = ref(false);
        let timer: number | undefined;

        // The scroll position maps onto the path count; colours settle at the end.
        const onScroll = () => {
          const el = stage.value!;
          const max = el.scrollHeight - el.clientHeight;
          const p = max > 0 ? el.scrollTop / max : 1;
          drawn.value = Math.round(p * paths.length);
          colored.value = p >= 0.97;
        };
        const erase = () => {
          clearInterval(timer);
          stage.value!.scrollTop = 0;
          drawn.value = 0;
          colored.value = false;
        };
        const draw = () => {
          erase();
          timer = window.setInterval(() => {
            const el = stage.value!;
            const max = el.scrollHeight - el.clientHeight;
            if (el.scrollTop >= max - 1) { clearInterval(timer); onScroll(); }
            else { el.scrollTop = Math.min(max, el.scrollTop + 7); onScroll(); }
          }, 16);
        };
        onBeforeUnmount(() => clearInterval(timer));
        </script>

        <template>
          <div ref="stage" class="imw-stage" tabindex="0" role="img" @scroll="onScroll"
            aria-label="Line illustration of a workspace, drawn by scrolling">
            <svg class="imw-scene" viewBox="0 0 320 200" aria-hidden="true">
              <g class="imw-colors" :data-on="colored">
                <rect x="30" y="36" width="78" height="104" fill="#bfdbfe" opacity=".55" />
                <rect x="152" y="84" width="92" height="58" fill="#c7d2fe" opacity=".45" />
              </g>
              <path v-for="(d, i) in paths" :key="i" class="imw-ink" pathLength="1" :d="d" :data-drawn="drawn > i" />
            </svg>
            <div class="imw-spacer" aria-hidden="true"></div>
          </div>
          <button type="button" @click="draw">Draw</button>
          <button type="button" @click="erase">Erase</button>
          <span class="imw-count" role="status">{{ drawn }} / {{ paths.length }} paths</span>
        </template>

        <style scoped>
        .imw-stage { block-size: 15.5rem; overflow-y: auto; border: 1px solid #e7e5e4;
                     border-radius: 1.1rem; background: #fff; }
        .imw-scene { position: sticky; inset-block-start: 0; display: block; inline-size: 100%;
                     block-size: 15.5rem; background: #fff; }
        .imw-spacer { block-size: 34rem; }
        .imw-colors { opacity: 0; transition: opacity .9s ease; }
        .imw-colors[data-on='true'] { opacity: 1; }
        .imw-ink { fill: none; stroke: #1c1917; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round;
                   stroke-dasharray: 1; stroke-dashoffset: 1; transition: stroke-dashoffset .3s ease; }
        .imw-ink[data-drawn='true'] { stroke-dashoffset: 0; }
        .imw-count { font: 700 .74rem/1.4 ui-monospace, monospace; color: #4f46e5; }
        @media (prefers-reduced-motion: reduce) { .imw-ink, .imw-colors { transition: none; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- SelfDraw.svelte — a scene that inks itself layer by layer on scroll -->
        <script lang="ts">
          import { onDestroy } from 'svelte';
          import '@nabuxai/ui-core/css';

          // Desk, window, hills, laptop, mug, steam, plant — in draw order.
          const paths = [
            'M14 170h292', 'M28 34h82v108H28z', 'M69 34v108', 'M28 88h82',
            'M32 134q16-22 32 0', 'M62 134q12-16 24 0',
            'M150 82h96v62h-96z', 'M138 144h136l10 16H128z', 'M156 156h92',
            'M266 118h26v34a8 8 0 0 1-8 8h-10a8 8 0 0 1-8-8z', 'M292 126a9 9 0 0 1 0 18',
            'M272 106q-4-6 0-12', 'M284 106q-4-6 0-12',
            'M98 150h28l-4 20h-20z', 'M112 150c-2-14-12-24-24-26', 'M112 150c2-16 12-26 24-28', 'M112 150v-28',
          ];
          let stage: HTMLElement;
          let drawn = $state(0);
          let colored = $state(false);
          let timer: number | undefined;

          // The scroll position maps onto the path count; colours settle at the end.
          const onScroll = () => {
            const max = stage.scrollHeight - stage.clientHeight;
            const p = max > 0 ? stage.scrollTop / max : 1;
            drawn = Math.round(p * paths.length);
            colored = p >= 0.97;
          };
          const erase = () => {
            clearInterval(timer);
            stage.scrollTop = 0;
            drawn = 0;
            colored = false;
          };
          const draw = () => {
            erase();
            timer = window.setInterval(() => {
              const max = stage.scrollHeight - stage.clientHeight;
              if (stage.scrollTop >= max - 1) { clearInterval(timer); onScroll(); }
              else { stage.scrollTop = Math.min(max, stage.scrollTop + 7); onScroll(); }
            }, 16);
          };
          onDestroy(() => clearInterval(timer));
        </script>

        <div bind:this={stage} class="imw-stage" tabindex="0" role="img" onscroll={onScroll}
          aria-label="Line illustration of a workspace, drawn by scrolling">
          <svg class="imw-scene" viewBox="0 0 320 200" aria-hidden="true">
            <g class="imw-colors" data-on={colored}>
              <rect x="30" y="36" width="78" height="104" fill="#bfdbfe" opacity=".55" />
              <rect x="152" y="84" width="92" height="58" fill="#c7d2fe" opacity=".45" />
            </g>
            {#each paths as d, i}
              <path class="imw-ink" pathLength="1" {d} data-drawn={drawn > i} />
            {/each}
          </svg>
          <div class="imw-spacer" aria-hidden="true"></div>
        </div>
        <button type="button" onclick={draw}>Draw</button>
        <button type="button" onclick={erase}>Erase</button>
        <span class="imw-count" role="status">{drawn} / {paths.length} paths</span>

        <style>
        .imw-stage { block-size: 15.5rem; overflow-y: auto; border: 1px solid #e7e5e4;
                     border-radius: 1.1rem; background: #fff; }
        .imw-scene { position: sticky; inset-block-start: 0; display: block; inline-size: 100%;
                     block-size: 15.5rem; background: #fff; }
        .imw-spacer { block-size: 34rem; }
        .imw-colors { opacity: 0; transition: opacity .9s ease; }
        .imw-colors[data-on='true'] { opacity: 1; }
        .imw-ink { fill: none; stroke: #1c1917; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round;
                   stroke-dasharray: 1; stroke-dashoffset: 1; transition: stroke-dashoffset .3s ease; }
        .imw-ink[data-drawn='true'] { stroke-dashoffset: 0; }
        .imw-count { font: 700 .74rem/1.4 ui-monospace, monospace; color: #4f46e5; }
        @media (prefers-reduced-motion: reduce) { .imw-ink, .imw-colors { transition: none; } }
        </style>
        SVELTE,
        ],
    ],

    'icon-browser' => [
        'title' => ['fa' => 'مرورگر آیکون', 'en' => 'Icon browser'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'کتابخانهٔ آیکون داخل خود دمو: ۲۸ آیکون خطی به سبک لوسید با جست‌وجوی زندهٔ فارسی/انگلیسی روی نام‌ها، کلیک = کپی SVG با تُست تأیید، اسلایدر اندازه و ضخامت خط، سواچ رنگ، شمارندهٔ نتیجه و حالت خالی صادق.',
            'en' => 'An icon library inside the demo itself: 28 Lucide-style line icons with live Persian/English search over names, click-to-copy the exact SVG with a confirming toast, size and stroke sliders, colour swatches, a result counter and an honest empty state.',
        ],
        'js' => true,
        'docs' => 'https://allsvgicons.com',
        'props' => [
            ['name' => 'icons', 'type' => 'count', 'default' => "'28'", 'note' => [
                'fa' => 'بیست‌وهشت آیکون دست‌ساز ۲۴×۲۴ — همهٔ شکل‌ها path خالص تا کپی و نمایش از یک منبع واحد بیایند.',
                'en' => 'Twenty-eight hand-drawn 24×24 icons — every shape a plain path so the copy and the preview come from one source.',
            ]],
            ['name' => 'search', 'type' => 'filter', 'default' => "'fa ∪ en'", 'note' => [
                'fa' => 'جست‌وجو هم روی نام انگلیسی (case-insensitive) و هم فارسی می‌گردد؛ «قلب» و «heart» به یک کاشی می‌رسند.',
                'en' => 'The search matches the English name (case-insensitive) and the Persian one — “heart” and «قلب» land on the same tile.',
            ]],
            ['name' => 'copy', 'type' => 'clipboard', 'default' => "'svg'", 'note' => [
                'fa' => 'رشتهٔ SVG با همین اندازه/ضخامت/رنگ فعلی ساخته و کپی می‌شود؛ تُست نام آیکون را می‌گوید و در ۱.۸ ثانیه محو می‌شود (با fallback برای مرورگرهای سخت‌گیر).',
                'en' => 'The SVG string is built with the current size/stroke/colour and copied; the toast names the icon and fades in 1.8s (with a fallback for stubborn browsers).',
            ]],
            ['name' => 'size · stroke · color', 'type' => 'controls', 'default' => "'24px · 2 · #4f46e5'", 'note' => [
                'fa' => 'اسلایدرها روی کل گرید اعمال می‌شوند — هر کاشی همان مقدار زنده را می‌گیرد و خروجی کپی هم دقیقاً همین تنظیمات را می‌برد.',
                'en' => 'The sliders apply to the whole grid — every tile takes the live value and the copied output carries exactly these settings.',
            ]],
            ['name' => 'empty', 'type' => 'state', 'default' => "'honest'", 'note' => [
                'fa' => 'بدون نتیجه، پیام روشن با پیشنهاد نام درست و دکمهٔ پاک‌کردن جست‌وجو — نه خانه‌های خالی، نه اسکرول بی‌انتها.',
                'en' => 'With no hits, a plain message suggesting real names plus a clear-search button — no blank cells, no endless scroll.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="imb" x-data="{
            all: @js($icons), q: '', size: 24, sw: 2, color: '#4f46e5', toast: null, t: null,
            get hits() {
                const n = this.q.trim().toLowerCase();
                return n ? this.all.filter((i) => i.en.includes(n) || i.fa.includes(this.q.trim())) : this.all;
            },
            svgOf(icon) {
                const body = icon.paths.map((d) => '  <path d=\"' + d + '\"/>').join('\n');
                return '<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" width=\"' + this.size
                    + '\" height=\"' + this.size + '\" fill=\"none\" stroke=\"' + this.color
                    + '\" stroke-width=\"' + this.sw + '\" stroke-linecap=\"round\" stroke-linejoin=\"round\">\n'
                    + body + '\n</svg>';
            },
            async copy(icon) {
                try { await navigator.clipboard.writeText(this.svgOf(icon)); } catch (e) {}
                this.toast = 'SVG «' + icon.fa + '» کپی شد';
                clearTimeout(this.t);
                this.t = setTimeout(() => (this.toast = null), 1800);
            },
        }" x-init="$cleanup(() => clearTimeout(t))">
            {{-- جست‌وجوی زنده --}}
            <label class="imb-search">
                <svg viewBox="0 0 24 24"><path d="M18 11a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/><path d="m16.65 16.65 4.35 4.35"/></svg>
                <input type="search" x-model="q" placeholder="جست‌وجوی نام آیکون — فارسی یا English…">
            </label>

            {{-- کنترل اندازه/ضخامت/رنگ --}}
            <input type="range" min="16" max="44" step="2" x-model.number="size">
            <input type="range" min="1" max="3" step="0.25" x-model.number="sw">
            <button type="button" class="imb-swatch" style="background: #4f46e5" x-on:click="color = '#4f46e5'"></button>

            {{-- گرید + کپی --}}
            <div class="imb-grid">
                <template x-for="icon in hits" :key="icon.en">
                    <button type="button" class="imb-tile" x-on:click="copy(icon)" :aria-label="'کپی SVG ' + icon.fa">
                        <svg viewBox="0 0 24 24" :stroke="color" :stroke-width="sw"
                             :style="`inline-size: ${size}px; block-size: ${size}px`">
                            <template x-for="d in icon.paths" :key="d"><path :d="d"></path></template>
                        </svg>
                        <span class="imb-name" x-text="icon.fa"></span>
                    </button>
                </template>
            </div>

            {{-- شمارنده و حالت خالی --}}
            <p class="imb-count" x-text="hits.length + ' نتیجه از ' + all.length + ' آیکون'"></p>
            <div class="imb-empty" x-show="hits.length === 0" x-cloak>
                <p x-text="'چیزی برای «' + q + '» پیدا نشد — heart یا قلب را امتحان کن.'"></p>
                <button type="button" class="imb-clear" x-on:click="q = ''">پاک کردن جست‌وجو</button>
            </div>

            <div class="imb-toast" x-show="toast" x-cloak role="status" x-text="toast"></div>
        </div>

        <style>
        .imb-search { display: flex; align-items: center; gap: .6rem; padding: .65rem .9rem;
                      border: 1px solid #e7e5e4; border-radius: .85rem; }
        .imb-search svg { inline-size: 1.05rem; fill: none; stroke: #78716c; stroke-width: 2; }
        .imb-grid { display: grid; gap: .6rem; grid-template-columns: repeat(auto-fill, minmax(6.4rem, 1fr)); }
        .imb-tile { display: grid; gap: .6rem; justify-items: center; padding: .9rem .4rem .75rem;
                    border: 1px solid #e7e5e4; border-radius: .8rem; background: #fff; cursor: copy; }
        .imb-tile:hover { border-color: #4f46e5; }
        .imb-tile path { fill: none; stroke-linecap: round; stroke-linejoin: round; }
        .imb-swatch { inline-size: 1.35rem; aspect-ratio: 1; border-radius: 50%; border: none; cursor: pointer; }
        .imb-toast { position: absolute; inset-block-end: 1rem; padding: .55rem 1.1rem; border-radius: 999px;
                     background: #1c1917; color: #fff; animation: imb-rise .25s ease-out; }
        @keyframes imb-rise { from { opacity: 0; translate: 0 8px; } to { opacity: 1; translate: 0 0; } }
        @media (prefers-reduced-motion: reduce) { .imb-toast { animation: none; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="imb" x-data="{
            all: @js($icons), q: '', size: 24, sw: 2, color: '#4f46e5', toast: null, t: null,
            get hits() {
                const n = this.q.trim().toLowerCase();
                return n ? this.all.filter((i) => i.en.includes(n) || i.fa.includes(this.q.trim())) : this.all;
            },
            svgOf(icon) {
                const body = icon.paths.map((d) => '  <path d=\"' + d + '\"/>').join('\n');
                return '<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" width=\"' + this.size
                    + '\" height=\"' + this.size + '\" fill=\"none\" stroke=\"' + this.color
                    + '\" stroke-width=\"' + this.sw + '\" stroke-linecap=\"round\" stroke-linejoin=\"round\">\n'
                    + body + '\n</svg>';
            },
            async copy(icon) {
                try { await navigator.clipboard.writeText(this.svgOf(icon)); } catch (e) {}
                this.toast = 'Copied the ' + icon.en + ' SVG';
                clearTimeout(this.t);
                this.t = setTimeout(() => (this.toast = null), 1800);
            },
        }" x-init="$cleanup(() => clearTimeout(t))">
            {{-- live search --}}
            <label class="imb-search">
                <svg viewBox="0 0 24 24"><path d="M18 11a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/><path d="m16.65 16.65 4.35 4.35"/></svg>
                <input type="search" x-model="q" placeholder="Search icon names — English or Persian…">
            </label>

            {{-- size / stroke / colour controls --}}
            <input type="range" min="16" max="44" step="2" x-model.number="size">
            <input type="range" min="1" max="3" step="0.25" x-model.number="sw">
            <button type="button" class="imb-swatch" style="background: #4f46e5" x-on:click="color = '#4f46e5'"></button>

            {{-- the grid + copy --}}
            <div class="imb-grid">
                <template x-for="icon in hits" :key="icon.en">
                    <button type="button" class="imb-tile" x-on:click="copy(icon)" :aria-label="'Copy the ' + icon.en + ' SVG'">
                        <svg viewBox="0 0 24 24" :stroke="color" :stroke-width="sw"
                             :style="`inline-size: ${size}px; block-size: ${size}px`">
                            <template x-for="d in icon.paths" :key="d"><path :d="d"></path></template>
                        </svg>
                        <span class="imb-name" x-text="icon.en"></span>
                    </button>
                </template>
            </div>

            {{-- counter and empty state --}}
            <p class="imb-count" x-text="hits.length + ' of ' + all.length + ' icons'"></p>
            <div class="imb-empty" x-show="hits.length === 0" x-cloak>
                <p x-text="'Nothing matched ' + q + ' — try heart or قلب.'"></p>
                <button type="button" class="imb-clear" x-on:click="q = ''">Clear the search</button>
            </div>

            <div class="imb-toast" x-show="toast" x-cloak role="status" x-text="toast"></div>
        </div>

        <style>
        .imb-search { display: flex; align-items: center; gap: .6rem; padding: .65rem .9rem;
                      border: 1px solid #e7e5e4; border-radius: .85rem; }
        .imb-search svg { inline-size: 1.05rem; fill: none; stroke: #78716c; stroke-width: 2; }
        .imb-grid { display: grid; gap: .6rem; grid-template-columns: repeat(auto-fill, minmax(6.4rem, 1fr)); }
        .imb-tile { display: grid; gap: .6rem; justify-items: center; padding: .9rem .4rem .75rem;
                    border: 1px solid #e7e5e4; border-radius: .8rem; background: #fff; cursor: copy; }
        .imb-tile:hover { border-color: #4f46e5; }
        .imb-tile path { fill: none; stroke-linecap: round; stroke-linejoin: round; }
        .imb-swatch { inline-size: 1.35rem; aspect-ratio: 1; border-radius: 50%; border: none; cursor: pointer; }
        .imb-toast { position: absolute; inset-block-end: 1rem; padding: .55rem 1.1rem; border-radius: 999px;
                     background: #1c1917; color: #fff; animation: imb-rise .25s ease-out; }
        @keyframes imb-rise { from { opacity: 0; translate: 0 8px; } to { opacity: 1; translate: 0 0; } }
        @media (prefers-reduced-motion: reduce) { .imb-toast { animation: none; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/IconMotion.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import IconBrowser from '@/components/IconBrowser';

        export default function IconMotion() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <IconBrowser />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // IconBrowser.jsx — a searchable, copyable Lucide-style icon library
        import { useEffect, useMemo, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const ICONS = [
          { en: 'heart', fa: 'قلب', paths: ['M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3.5c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z'] },
          { en: 'moon', fa: 'ماه', paths: ['M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z'] },
          { en: 'search', fa: 'جست‌وجو', paths: ['M18 11a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z', 'm16.65 16.65 4.35 4.35'] },
          { en: 'globe', fa: 'کره', paths: ['M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z', 'M3 12h18', 'M12 3a13.5 13.5 0 0 1 3.5 9A13.5 13.5 0 0 1 12 21a13.5 13.5 0 0 1-3.5-9A13.5 13.5 0 0 1 12 3Z'] },
          { en: 'lock', fa: 'قفل', paths: ['M5 11a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2Z', 'M8 11V7a4 4 0 0 1 8 0v4'] },
          { en: 'command', fa: 'فرمان', paths: ['M15 6v12a3 3 0 1 0 3-3H6a3 3 0 1 0 3 3V6a3 3 0 1 0-3 3h12a3 3 0 1 0-3-3'] },
          // …22 more in the same shape.
        ];

        export default function IconBrowser() {
          const [q, setQ] = useState('');
          const [size, setSize] = useState(24);
          const [sw, setSw] = useState(2);
          const [color, setColor] = useState('#4f46e5');
          const [toast, setToast] = useState(null);
          const timer = useRef(null);
          useEffect(() => () => clearTimeout(timer.current), []);

          // Search matches the English name and the Persian one.
          const hits = useMemo(() => {
            const n = q.trim().toLowerCase();
            return n ? ICONS.filter((i) => i.en.includes(n) || i.fa.includes(q.trim())) : ICONS;
          }, [q]);

          // The copied string carries the live size/stroke/colour.
          const svgOf = (icon) =>
            `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="${size}" height="${size}" fill="none" stroke="${color}" stroke-width="${sw}" stroke-linecap="round" stroke-linejoin="round">\n`
            + icon.paths.map((d) => `  <path d="${d}"/>`).join('\n') + '\n</svg>';
          const copy = async (icon) => {
            try { await navigator.clipboard.writeText(svgOf(icon)); } catch { /* older browsers */ }
            setToast(`Copied the ${icon.en} SVG`);
            clearTimeout(timer.current);
            timer.current = setTimeout(() => setToast(null), 1800);
          };

          return (
            <>
              <style>{`
                .imb-grid { display: grid; gap: .6rem; grid-template-columns: repeat(auto-fill, minmax(6.4rem, 1fr)); }
                .imb-tile { display: grid; gap: .6rem; justify-items: center; padding: .9rem .4rem .75rem;
                            border: 1px solid #e7e5e4; border-radius: .8rem; background: #fff; cursor: copy; }
                .imb-tile:hover { border-color: #4f46e5; }
                .imb-tile path { fill: none; stroke-linecap: round; stroke-linejoin: round; }
                .imb-toast { position: absolute; inset-block-end: 1rem; padding: .55rem 1.1rem; border-radius: 999px;
                             background: #1c1917; color: #fff; animation: imb-rise .25s ease-out; }
                @keyframes imb-rise { from { opacity: 0; translate: 0 8px; } to { opacity: 1; translate: 0 0; } }
                @media (prefers-reduced-motion: reduce) { .imb-toast { animation: none; } }
              `}</style>
              <input type="search" value={q} onChange={(e) => setQ(e.target.value)}
                placeholder="Search icon names — English or Persian…" aria-label="Search icons" />
              <input type="range" min="16" max="44" step="2" value={size} onChange={(e) => setSize(+e.target.value)} aria-label="Icon size" />
              <input type="range" min="1" max="3" step="0.25" value={sw} onChange={(e) => setSw(+e.target.value)} aria-label="Stroke width" />
              {['#4f46e5', '#0ea5e9', '#ec4899'].map((c) => (
                <button key={c} type="button" aria-label={c} aria-pressed={color === c}
                  onClick={() => setColor(c)} style={{ background: c, inlineSize: '1.35rem', blockSize: '1.35rem', borderRadius: '50%' }} />
              ))}
              <p role="status">{hits.length} of {ICONS.length} icons</p>
              <div className="imb-grid">
                {hits.map((icon) => (
                  <button key={icon.en} type="button" className="imb-tile" onClick={() => copy(icon)}
                    aria-label={`Copy the ${icon.en} SVG`}>
                    <svg viewBox="0 0 24 24" aria-hidden="true" stroke={color} strokeWidth={sw}
                      style={{ inlineSize: size, blockSize: size }}>
                      {icon.paths.map((d, i) => <path key={i} d={d} />)}
                    </svg>
                    <span>{icon.en}</span>
                  </button>
                ))}
              </div>
              {hits.length === 0 && <p>Nothing matched — try heart or قلب.</p>}
              {toast && <div className="imb-toast" role="status">{toast}</div>}
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- IconBrowser.vue — a searchable, copyable Lucide-style icon library -->
        <script setup lang="ts">
        import { computed, onBeforeUnmount, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const icons = [
          { en: 'heart', fa: 'قلب', paths: ['M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3.5c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z'] },
          { en: 'moon', fa: 'ماه', paths: ['M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z'] },
          { en: 'search', fa: 'جست‌وجو', paths: ['M18 11a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z', 'm16.65 16.65 4.35 4.35'] },
          { en: 'globe', fa: 'کره', paths: ['M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z', 'M3 12h18', 'M12 3a13.5 13.5 0 0 1 3.5 9A13.5 13.5 0 0 1 12 21a13.5 13.5 0 0 1-3.5-9A13.5 13.5 0 0 1 12 3Z'] },
          { en: 'lock', fa: 'قفل', paths: ['M5 11a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2Z', 'M8 11V7a4 4 0 0 1 8 0v4'] },
          { en: 'command', fa: 'فرمان', paths: ['M15 6v12a3 3 0 1 0 3-3H6a3 3 0 1 0 3 3V6a3 3 0 1 0-3 3h12a3 3 0 1 0-3-3'] },
          // …22 more in the same shape.
        ];
        const q = ref('');
        const size = ref(24);
        const sw = ref(2);
        const color = ref('#4f46e5');
        const toast = ref<string | null>(null);
        let timer: number | undefined;

        // Search matches the English name and the Persian one.
        const hits = computed(() => {
          const n = q.value.trim().toLowerCase();
          return n ? icons.filter((i) => i.en.includes(n) || i.fa.includes(q.value.trim())) : icons;
        });

        // The copied string carries the live size/stroke/colour.
        const svgOf = (icon: typeof icons[number]) =>
          `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="${size.value}" height="${size.value}" fill="none" stroke="${color.value}" stroke-width="${sw.value}" stroke-linecap="round" stroke-linejoin="round">\n`
          + icon.paths.map((d) => `  <path d="${d}"/>`).join('\n') + '\n</svg>';
        const copy = async (icon: typeof icons[number]) => {
          try { await navigator.clipboard.writeText(svgOf(icon)); } catch { /* older browsers */ }
          toast.value = `Copied the ${icon.en} SVG`;
          clearTimeout(timer);
          timer = window.setTimeout(() => (toast.value = null), 1800);
        };
        onBeforeUnmount(() => clearTimeout(timer));
        </script>

        <template>
          <input type="search" v-model="q" placeholder="Search icon names — English or Persian…" aria-label="Search icons" />
          <input type="range" min="16" max="44" step="2" v-model.number="size" aria-label="Icon size" />
          <input type="range" min="1" max="3" step="0.25" v-model.number="sw" aria-label="Stroke width" />
          <p role="status">{{ hits.length }} of {{ icons.length }} icons</p>
          <div class="imb-grid">
            <button v-for="icon in hits" :key="icon.en" type="button" class="imb-tile" @click="copy(icon)"
              :aria-label="`Copy the ${icon.en} SVG`">
              <svg viewBox="0 0 24 24" aria-hidden="true" :stroke="color" :stroke-width="sw"
                :style="{ inlineSize: size + 'px', blockSize: size + 'px' }">
                <path v-for="d in icon.paths" :key="d" :d="d" />
              </svg>
              <span>{{ icon.en }}</span>
            </button>
          </div>
          <p v-if="hits.length === 0">Nothing matched — try heart or قلب.</p>
          <div v-if="toast" class="imb-toast" role="status">{{ toast }}</div>
        </template>

        <style scoped>
        .imb-grid { display: grid; gap: .6rem; grid-template-columns: repeat(auto-fill, minmax(6.4rem, 1fr)); }
        .imb-tile { display: grid; gap: .6rem; justify-items: center; padding: .9rem .4rem .75rem;
                    border: 1px solid #e7e5e4; border-radius: .8rem; background: #fff; cursor: copy; }
        .imb-tile:hover { border-color: #4f46e5; }
        .imb-tile path { fill: none; stroke-linecap: round; stroke-linejoin: round; }
        .imb-toast { position: absolute; inset-block-end: 1rem; padding: .55rem 1.1rem; border-radius: 999px;
                     background: #1c1917; color: #fff; animation: imb-rise .25s ease-out; }
        @keyframes imb-rise { from { opacity: 0; translate: 0 8px; } to { opacity: 1; translate: 0 0; } }
        @media (prefers-reduced-motion: reduce) { .imb-toast { animation: none; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- IconBrowser.svelte — a searchable, copyable Lucide-style icon library -->
        <script lang="ts">
          import { onDestroy } from 'svelte';
          import '@nabuxai/ui-core/css';

          const icons = [
            { en: 'heart', fa: 'قلب', paths: ['M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3.5c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z'] },
            { en: 'moon', fa: 'ماه', paths: ['M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z'] },
            { en: 'search', fa: 'جست‌وجو', paths: ['M18 11a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z', 'm16.65 16.65 4.35 4.35'] },
            { en: 'globe', fa: 'کره', paths: ['M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z', 'M3 12h18', 'M12 3a13.5 13.5 0 0 1 3.5 9A13.5 13.5 0 0 1 12 21a13.5 13.5 0 0 1-3.5-9A13.5 13.5 0 0 1 12 3Z'] },
            { en: 'lock', fa: 'قفل', paths: ['M5 11a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2Z', 'M8 11V7a4 4 0 0 1 8 0v4'] },
            { en: 'command', fa: 'فرمان', paths: ['M15 6v12a3 3 0 1 0 3-3H6a3 3 0 1 0 3 3V6a3 3 0 1 0-3 3h12a3 3 0 1 0-3-3'] },
            // …22 more in the same shape.
          ];
          let q = $state('');
          let size = $state(24);
          let sw = $state(2);
          let color = $state('#4f46e5');
          let toast = $state<string | null>(null);
          let timer: number | undefined;
          onDestroy(() => clearTimeout(timer));

          // Search matches the English name and the Persian one.
          const hits = $derived.by(() => {
            const n = q.trim().toLowerCase();
            return n ? icons.filter((i) => i.en.includes(n) || i.fa.includes(q.trim())) : icons;
          });

          // The copied string carries the live size/stroke/colour.
          const svgOf = (icon: typeof icons[number]) =>
            `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="${size}" height="${size}" fill="none" stroke="${color}" stroke-width="${sw}" stroke-linecap="round" stroke-linejoin="round">\n`
            + icon.paths.map((d) => `  <path d="${d}"/>`).join('\n') + '\n</svg>';
          const copy = async (icon: typeof icons[number]) => {
            try { await navigator.clipboard.writeText(svgOf(icon)); } catch { /* older browsers */ }
            toast = `Copied the ${icon.en} SVG`;
            clearTimeout(timer);
            timer = window.setTimeout(() => (toast = null), 1800);
          };
        </script>

        <input type="search" bind:value={q} placeholder="Search icon names — English or Persian…" aria-label="Search icons" />
        <input type="range" min="16" max="44" step="2" bind:value={size} aria-label="Icon size" />
        <input type="range" min="1" max="3" step="0.25" bind:value={sw} aria-label="Stroke width" />
        <p role="status">{hits.length} of {icons.length} icons</p>
        <div class="imb-grid">
          {#each hits as icon (icon.en)}
            <button type="button" class="imb-tile" onclick={() => copy(icon)} aria-label="Copy the {icon.en} SVG">
              <svg viewBox="0 0 24 24" aria-hidden="true" stroke={color} stroke-width={sw}
                style="inline-size: {size}px; block-size: {size}px">
                {#each icon.paths as d (d)}
                  <path {d} />
                {/each}
              </svg>
              <span>{icon.en}</span>
            </button>
          {/each}
        </div>
        {#if hits.length === 0}
          <p>Nothing matched — try heart or قلب.</p>
        {/if}
        {#if toast}
          <div class="imb-toast" role="status">{toast}</div>
        {/if}

        <style>
        .imb-grid { display: grid; gap: .6rem; grid-template-columns: repeat(auto-fill, minmax(6.4rem, 1fr)); }
        .imb-tile { display: grid; gap: .6rem; justify-items: center; padding: .9rem .4rem .75rem;
                    border: 1px solid #e7e5e4; border-radius: .8rem; background: #fff; cursor: copy; }
        .imb-tile:hover { border-color: #4f46e5; }
        .imb-tile path { fill: none; stroke-linecap: round; stroke-linejoin: round; }
        .imb-toast { position: absolute; inset-block-end: 1rem; padding: .55rem 1.1rem; border-radius: 999px;
                     background: #1c1917; color: #fff; animation: imb-rise .25s ease-out; }
        @keyframes imb-rise { from { opacity: 0; translate: 0 8px; } to { opacity: 1; translate: 0 0; } }
        @media (prefers-reduced-motion: reduce) { .imb-toast { animation: none; } }
        </style>
        SVELTE,
        ],
    ],

    'icon-showcase' => [
        'title' => ['fa' => 'صحنهٔ جمع‌بندی', 'en' => 'Icon showcase scene'],
        'icon' => 'sparkles',
        'oneLiner' => [
            'fa' => 'بخش لانچ ورتکس استودیو که کل زبان این موج را کنار هم می‌آورد: تصویرسازی خطی خودترسیم پشت سرتیتر نفس می‌کشد، ردیف قابلیت‌ها آیکون‌هایش را با ورود به دید می‌کشد، دکمه‌های CTA تاگل‌های حالت‌دار قلب و بوکمارک دارند و کارت‌های آمار اسپینر در حال کار، نقطهٔ نبض‌دار و فلش جهنده را می‌چرخانند.',
            'en' => 'Vertex Studio’s launch section gathering the whole wave’s vocabulary in one place: a self-drawing line illustration breathing behind the headline, a feature row inking its icons in on entry, CTAs with stateful heart/bookmark toggles, and stat cards running a working spinner, a pulsing dot and a bouncing trend arrow.',
        ],
        'js' => true,
        'docs' => 'https://lordicon.com',
        'props' => [
            ['name' => 'seen', 'type' => 'trigger', 'default' => "'in-view · once'", 'note' => [
                'fa' => 'یک IntersectionObserver با آستانهٔ ۲۵٪ کل صحنه را روشن می‌کند: اول تصویرسازی پس‌زمینه (هشت مسیر پشت سر هم) و بعد آیکون‌های ردیف قابلیت با تناوب پله‌ای.',
                'en' => 'One IntersectionObserver at a 25% threshold lights the whole scene: first the background illustration (eight paths back to back), then the feature-row icons on their stepped cadence.',
            ]],
            ['name' => 'cta-toggles', 'type' => 'state', 'default' => "'aria-pressed'", 'note' => [
                'fa' => 'قلب و بوکمارکِ کنار CTA اصلی تاگل‌های واقعی‌اند — همان مورف پرشدن با اورشوت فنری از دموی مورف حالت‌ها، این‌بار در مقیاس محصول.',
                'en' => 'The heart and bookmark beside the primary CTA are real toggles — the same springy fill morph from the state-morph demo, now at product scale.',
            ]],
            ['name' => 'stats', 'type' => 'loop', 'default' => "'dots · pulse · bounce'", 'note' => [
                'fa' => 'هر عدد با آیکون درحال‌کار خودش جفت شده: ۱۲۸ پروژه با سه‌نقطه، ۹۹٫۹۸٪ آپ‌تایم با نقطهٔ نبض‌دار و ۴۲ms تأخیر با فلش جهنده رو به رشد.',
                'en' => 'Each number pairs with its working icon: 128 projects with the dot trio, 99.98% uptime with the pulsing dot, 42ms latency with the bouncing trend arrow.',
            ]],
            ['name' => 'background', 'type' => 'layer', 'default' => "'absolute · 35%'", 'note' => [
                'fa' => 'تصویرسازی پس‌زمینه absolute و کم‌رنگ (۳۵٪) پشت محتواست و خودِ محتوا را جابه‌جا نمی‌کند؛ ترسیمش ۱۴۰ms به ازای هر مسیر طول می‌کشد.',
                'en' => 'The background illustration is absolutely positioned and dimmed to 35% behind the content, never displacing it; its inking paces 140ms per path.',
            ]],
            ['name' => 'reduced-motion', 'type' => 'media', 'default' => "'final'", 'note' => [
                'fa' => 'همه‌چیز به حالت نهایی می‌نشیند: خطوط کامل ترسیم‌شده، پرها پرشده، حلقه‌ها ایستاده.',
                'en' => 'Everything settles into its final state: lines fully drawn, fills filled, loops frozen.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="imc" x-data="{ seen: false, liked: false, saved: false }"
             x-init="
                 const io = new IntersectionObserver((entries) => entries.forEach((entry) => {
                     if (entry.isIntersecting) { seen = true; io.disconnect(); }
                 }), { threshold: 0.25 });
                 io.observe($el);
                 $cleanup(() => io.disconnect());
             " x-bind:data-seen="seen ? 'true' : 'false'">
            {{-- سرتیتر با تصویرسازی خودترسیم پس‌زمینه --}}
            <div class="imc-hero" x-bind:data-seen="seen ? 'true' : 'false'">
                <svg class="imc-bgart" viewBox="0 0 360 440" aria-hidden="true">
                    @foreach ($bgPaths as $i => $d)
                        <path pathLength="1" d="{{ $d }}" style="--d: {{ $i * 140 }}ms" />
                    @endforeach
                </svg>
                <h4 class="imc-title">کار را منتشر کن. جنبشش را ببین.</h4>
                <div class="imc-ctas">
                    <a class="imc-primary" href="#start">شروع رایگان</a>
                    {{-- تاگل قلب حالت‌دار --}}
                    <button type="button" class="imc-iconbtn" x-bind:aria-pressed="liked ? 'true' : 'false'"
                            x-on:click="liked = !liked" aria-label="پسندن صفحه">
                        <svg viewBox="0 0 24 24">
                            <path class="imc-stroke" d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3.5c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                            <path class="imc-fill" d="…همان مسیر قلب…"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- کارت آمار با اسپینر در حال کار --}}
            <div class="imc-stats">
                <div class="imc-stat">
                    <span class="imc-ico"><span class="imc-dots" aria-hidden="true"><i></i><i></i><i></i></span></span>
                    <div><div class="imc-num">۱۲۸</div><p class="imc-cap">پروژهٔ در حال انتشار این هفته</p></div>
                </div>
            </div>

            {{-- ردیف قابلیت: آیکون‌ها با ورود به دید ترسیم می‌شوند --}}
            <div class="imc-feats">
                @foreach ($features as $fi => $feature)
                    <div class="imc-feat">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            @foreach ($feature['paths'] as $p => $d)
                                <path pathLength="1" d="{{ $d }}" style="--d: {{ $fi * 160 + $p * 130 }}ms" />
                            @endforeach
                        </svg>
                        <h4>{{ $feature['fa'] }}</h4>
                    </div>
                @endforeach
            </div>
        </div>

        <style>
        .imc-hero { position: relative; display: grid; justify-items: center; gap: 1.15rem; padding: 3rem 1.25rem;
                    border: 1px solid #e7e5e4; border-radius: 1.4rem; overflow: clip; text-align: center; }
        .imc-bgart { position: absolute; inset: 0; color: #4f46e5; opacity: .35; pointer-events: none; }
        .imc-bgart path { fill: none; stroke: currentColor; stroke-width: 1.6; stroke-linecap: round;
                          stroke-dasharray: 1; stroke-dashoffset: 1; transition: stroke-dashoffset 1s ease var(--d, 0s); }
        .imc-hero[data-seen='true'] .imc-bgart path { stroke-dashoffset: 0; }
        .imc-hero > *:not(.imc-bgart) { position: relative; }
        .imc-iconbtn { display: inline-grid; place-items: center; block-size: 2.7rem; aspect-ratio: 1;
                       border: 1px solid #e7e5e4; border-radius: .85rem; background: #fff; cursor: pointer; }
        .imc-iconbtn[aria-pressed='true'] { color: #ec4899; border-color: #ec4899; }
        .imc-stroke { fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .imc-fill { fill: currentColor; scale: 0; transform-box: fill-box; transform-origin: center; }
        .imc-iconbtn[aria-pressed='true'] .imc-fill { scale: 1; animation: imc-pop .45s cubic-bezier(.2,.9,.3,1.35); }
        .imc-dots { display: flex; gap: .22rem; }
        .imc-dots i { inline-size: .3rem; block-size: .3rem; border-radius: 50%; background: currentColor;
                      animation: imc-dot .9s ease-in-out infinite; }
        .imc-dots i:nth-child(2) { animation-delay: .15s; }
        .imc-dots i:nth-child(3) { animation-delay: .3s; }
        .imc-feat path { fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
                         stroke-dasharray: 1; stroke-dashoffset: 1; transition: stroke-dashoffset .55s ease var(--d, 0s); }
        .imc[data-seen='true'] .imc-feat path { stroke-dashoffset: 0; }
        @keyframes imc-pop { 0% { scale: 0; } 62% { scale: 1.28; } 100% { scale: 1; } }
        @keyframes imc-dot { 0%, 100% { translate: 0 0; opacity: .45; } 40% { translate: 0 -.28rem; opacity: 1; } }
        @media (prefers-reduced-motion: reduce) {
            .imc-bgart path, .imc-feat path { transition: none; stroke-dashoffset: 0; }
            .imc-iconbtn[aria-pressed='true'] .imc-fill { animation: none; }
            .imc-dots i { animation: none; }
        }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="imc" x-data="{ seen: false, liked: false, saved: false }"
             x-init="
                 const io = new IntersectionObserver((entries) => entries.forEach((entry) => {
                     if (entry.isIntersecting) { seen = true; io.disconnect(); }
                 }), { threshold: 0.25 });
                 io.observe($el);
                 $cleanup(() => io.disconnect());
             " x-bind:data-seen="seen ? 'true' : 'false'">
            {{-- headline with the self-drawing background illustration --}}
            <div class="imc-hero" x-bind:data-seen="seen ? 'true' : 'false'">
                <svg class="imc-bgart" viewBox="0 0 360 440" aria-hidden="true">
                    @foreach ($bgPaths as $i => $d)
                        <path pathLength="1" d="{{ $d }}" style="--d: {{ $i * 140 }}ms" />
                    @endforeach
                </svg>
                <h4 class="imc-title">Ship the work. Watch it move.</h4>
                <div class="imc-ctas">
                    <a class="imc-primary" href="#start">Start free</a>
                    {{-- the stateful heart toggle --}}
                    <button type="button" class="imc-iconbtn" x-bind:aria-pressed="liked ? 'true' : 'false'"
                            x-on:click="liked = !liked" aria-label="Like this page">
                        <svg viewBox="0 0 24 24">
                            <path class="imc-stroke" d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3.5c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                            <path class="imc-fill" d="…the same heart path…"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- a stat card with a working spinner --}}
            <div class="imc-stats">
                <div class="imc-stat">
                    <span class="imc-ico"><span class="imc-dots" aria-hidden="true"><i></i><i></i><i></i></span></span>
                    <div><div class="imc-num">128</div><p class="imc-cap">projects shipping this week</p></div>
                </div>
            </div>

            {{-- the feature row: icons inking themselves in on entry --}}
            <div class="imc-feats">
                @foreach ($features as $fi => $feature)
                    <div class="imc-feat">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            @foreach ($feature['paths'] as $p => $d)
                                <path pathLength="1" d="{{ $d }}" style="--d: {{ $fi * 160 + $p * 130 }}ms" />
                            @endforeach
                        </svg>
                        <h4>{{ $feature['en'] }}</h4>
                    </div>
                @endforeach
            </div>
        </div>

        <style>
        .imc-hero { position: relative; display: grid; justify-items: center; gap: 1.15rem; padding: 3rem 1.25rem;
                    border: 1px solid #e7e5e4; border-radius: 1.4rem; overflow: clip; text-align: center; }
        .imc-bgart { position: absolute; inset: 0; color: #4f46e5; opacity: .35; pointer-events: none; }
        .imc-bgart path { fill: none; stroke: currentColor; stroke-width: 1.6; stroke-linecap: round;
                          stroke-dasharray: 1; stroke-dashoffset: 1; transition: stroke-dashoffset 1s ease var(--d, 0s); }
        .imc-hero[data-seen='true'] .imc-bgart path { stroke-dashoffset: 0; }
        .imc-hero > *:not(.imc-bgart) { position: relative; }
        .imc-iconbtn { display: inline-grid; place-items: center; block-size: 2.7rem; aspect-ratio: 1;
                       border: 1px solid #e7e5e4; border-radius: .85rem; background: #fff; cursor: pointer; }
        .imc-iconbtn[aria-pressed='true'] { color: #ec4899; border-color: #ec4899; }
        .imc-stroke { fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .imc-fill { fill: currentColor; scale: 0; transform-box: fill-box; transform-origin: center; }
        .imc-iconbtn[aria-pressed='true'] .imc-fill { scale: 1; animation: imc-pop .45s cubic-bezier(.2,.9,.3,1.35); }
        .imc-dots { display: flex; gap: .22rem; }
        .imc-dots i { inline-size: .3rem; block-size: .3rem; border-radius: 50%; background: currentColor;
                      animation: imc-dot .9s ease-in-out infinite; }
        .imc-dots i:nth-child(2) { animation-delay: .15s; }
        .imc-dots i:nth-child(3) { animation-delay: .3s; }
        .imc-feat path { fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
                         stroke-dasharray: 1; stroke-dashoffset: 1; transition: stroke-dashoffset .55s ease var(--d, 0s); }
        .imc[data-seen='true'] .imc-feat path { stroke-dashoffset: 0; }
        @keyframes imc-pop { 0% { scale: 0; } 62% { scale: 1.28; } 100% { scale: 1; } }
        @keyframes imc-dot { 0%, 100% { translate: 0 0; opacity: .45; } 40% { translate: 0 -.28rem; opacity: 1; } }
        @media (prefers-reduced-motion: reduce) {
            .imc-bgart path, .imc-feat path { transition: none; stroke-dashoffset: 0; }
            .imc-iconbtn[aria-pressed='true'] .imc-fill { animation: none; }
            .imc-dots i { animation: none; }
        }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/IconMotion.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import IconShowcase from '@/components/IconShowcase';

        export default function IconMotion() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <IconShowcase />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // IconShowcase.jsx — the launch section speaking the whole vocabulary
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const HEART = 'M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3.5c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z';
        const BG = [
          'M60 220a120 120 0 1 1 240 0', 'M300 220a120 120 0 1 1 -240 0',
          'M100 220a80 80 0 1 1 160 0', 'M260 220a80 80 0 1 1 -160 0',
          'M180 220h40', 'm224 204 24-24', 'm256 168 28 8', 'M288 128a56 56 0 0 1 56 56',
        ];
        const FEATURES = [
          { name: 'Guarded rollouts', paths: ['M12 22s8-3.5 8-9.5V5l-8-3-8 3v7.5C4 18.5 12 22 12 22Z', 'm9 12 2 2 4-4'] },
          { name: 'Live sync', paths: ['M19.5 13.5A8 8 0 0 1 5.6 16.4', 'M4.5 13.5v3.8h3.8', 'M4.5 10.5A8 8 0 0 1 18.4 7.6', 'M19.5 10.5V6.7h-3.8'] },
          { name: 'Full observability', paths: ['M3 3v18h18', 'M7 15v3M12 10v8M17 6v12'] },
        ];

        export default function IconShowcase() {
          const rootRef = useRef(null);
          const [seen, setSeen] = useState(false);
          const [liked, setLiked] = useState(false);

          useEffect(() => {
            const io = new IntersectionObserver((entries) => entries.forEach((entry) => {
              if (entry.isIntersecting) { setSeen(true); io.disconnect(); }
            }), { threshold: 0.25 });
            io.observe(rootRef.current);
            return () => io.disconnect();
          }, []);

          return (
            <>
              <style>{`
                .imc-hero { position: relative; display: grid; justify-items: center; gap: 1.15rem; padding: 3rem 1.25rem;
                            border: 1px solid #e7e5e4; border-radius: 1.4rem; overflow: clip; text-align: center; }
                .imc-bgart { position: absolute; inset: 0; color: #4f46e5; opacity: .35; pointer-events: none; }
                .imc-bgart path { fill: none; stroke: currentColor; stroke-width: 1.6; stroke-linecap: round;
                                  stroke-dasharray: 1; stroke-dashoffset: 1; transition: stroke-dashoffset 1s ease var(--d, 0s); }
                .imc-hero[data-seen='true'] .imc-bgart path { stroke-dashoffset: 0; }
                .imc-hero > *:not(.imc-bgart) { position: relative; }
                .imc-iconbtn { display: inline-grid; place-items: center; block-size: 2.7rem; aspect-ratio: 1;
                               border: 1px solid #e7e5e4; border-radius: .85rem; background: #fff; cursor: pointer; }
                .imc-iconbtn[aria-pressed='true'] { color: #ec4899; border-color: #ec4899; }
                .imc-stroke { fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
                .imc-fill { fill: currentColor; scale: 0; transform-box: fill-box; transform-origin: center; }
                .imc-iconbtn[aria-pressed='true'] .imc-fill { scale: 1; animation: imc-pop .45s cubic-bezier(.2,.9,.3,1.35); }
                .imc-dots { display: flex; gap: .22rem; }
                .imc-dots i { inline-size: .3rem; block-size: .3rem; border-radius: 50%; background: currentColor;
                              animation: imc-dot .9s ease-in-out infinite; }
                .imc-dots i:nth-child(2) { animation-delay: .15s; }
                .imc-dots i:nth-child(3) { animation-delay: .3s; }
                .imc-feat path { fill: none; stroke: #4f46e5; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
                                 stroke-dasharray: 1; stroke-dashoffset: 1; transition: stroke-dashoffset .55s ease var(--d, 0s); }
                .imc[data-seen='true'] .imc-feat path { stroke-dashoffset: 0; }
                @keyframes imc-pop { 0% { scale: 0; } 62% { scale: 1.28; } 100% { scale: 1; } }
                @keyframes imc-dot { 0%, 100% { translate: 0 0; opacity: .45; } 40% { translate: 0 -.28rem; opacity: 1; } }
                @media (prefers-reduced-motion: reduce) {
                  .imc-bgart path, .imc-feat path { transition: none; stroke-dashoffset: 0; }
                  .imc-iconbtn[aria-pressed='true'] .imc-fill { animation: none; }
                  .imc-dots i { animation: none; }
                }
              `}</style>
              <div className="imc" ref={rootRef} data-seen={seen}>
                <div className="imc-hero" data-seen={seen}>
                  {/* The self-drawing background breathes behind the headline. */}
                  <svg className="imc-bgart" viewBox="0 0 360 440" aria-hidden="true">
                    {BG.map((d, i) => <path key={i} pathLength="1" d={d} style={{ '--d': `${i * 140}ms` }} />)}
                  </svg>
                  <h4>Ship the work. Watch it move.</h4>
                  <div className="imc-ctas">
                    <a className="imc-primary" href="#start">Start free</a>
                    {/* The stateful heart toggle beside the CTA. */}
                    <button type="button" className="imc-iconbtn" aria-pressed={liked} aria-label="Like this page"
                      onClick={() => setLiked(!liked)}>
                      <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path className="imc-stroke" d={HEART} />
                        <path className="imc-fill" d={HEART} />
                      </svg>
                    </button>
                  </div>
                </div>

                {/* A stat card with its working dot trio. */}
                <div className="imc-stat">
                  <span className="imc-ico" role="img" aria-label="working">
                    <span className="imc-dots" aria-hidden="true"><i /><i /><i /></span>
                  </span>
                  <div><b>128</b><p>projects shipping this week</p></div>
                </div>

                {/* The feature row inks itself in on entry. */}
                <div className="imc-feats">
                  {FEATURES.map((f, fi) => (
                    <div key={f.name} className="imc-feat">
                      <svg viewBox="0 0 24 24" aria-hidden="true">
                        {f.paths.map((d, p) => <path key={p} pathLength="1" d={d} style={{ '--d': `${fi * 160 + p * 130}ms` }} />)}
                      </svg>
                      <h4>{f.name}</h4>
                    </div>
                  ))}
                </div>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- IconShowcase.vue — the launch section speaking the whole vocabulary -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const HEART = 'M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3.5c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z';
        const bg = [
          'M60 220a120 120 0 1 1 240 0', 'M300 220a120 120 0 1 1 -240 0',
          'M100 220a80 80 0 1 1 160 0', 'M260 220a80 80 0 1 1 -160 0',
          'M180 220h40', 'm224 204 24-24', 'm256 168 28 8', 'M288 128a56 56 0 0 1 56 56',
        ];
        const features = [
          { name: 'Guarded rollouts', paths: ['M12 22s8-3.5 8-9.5V5l-8-3-8 3v7.5C4 18.5 12 22 12 22Z', 'm9 12 2 2 4-4'] },
          { name: 'Live sync', paths: ['M19.5 13.5A8 8 0 0 1 5.6 16.4', 'M4.5 13.5v3.8h3.8', 'M4.5 10.5A8 8 0 0 1 18.4 7.6', 'M19.5 10.5V6.7h-3.8'] },
          { name: 'Full observability', paths: ['M3 3v18h18', 'M7 15v3M12 10v8M17 6v12'] },
        ];
        const seen = ref(false);
        const liked = ref(false);
        const root = ref<HTMLElement | null>(null);
        let io: IntersectionObserver | null = null;

        onMounted(() => {
          io = new IntersectionObserver((entries) => entries.forEach((entry) => {
            if (entry.isIntersecting) { seen.value = true; io!.disconnect(); }
          }), { threshold: 0.25 });
          io.observe(root.value!);
        });
        onBeforeUnmount(() => io?.disconnect());
        </script>

        <template>
          <div ref="root" class="imc" :data-seen="seen">
            <div class="imc-hero" :data-seen="seen">
              <!-- The self-drawing background breathes behind the headline. -->
              <svg class="imc-bgart" viewBox="0 0 360 440" aria-hidden="true">
                <path v-for="(d, i) in bg" :key="i" pathLength="1" :d="d" :style="{ '--d': `${i * 140}ms` }" />
              </svg>
              <h4>Ship the work. Watch it move.</h4>
              <div class="imc-ctas">
                <a class="imc-primary" href="#start">Start free</a>
                <!-- The stateful heart toggle beside the CTA. -->
                <button type="button" class="imc-iconbtn" :aria-pressed="liked" aria-label="Like this page" @click="liked = !liked">
                  <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path class="imc-stroke" :d="HEART" />
                    <path class="imc-fill" :d="HEART" />
                  </svg>
                </button>
              </div>
            </div>

            <!-- A stat card with its working dot trio. -->
            <div class="imc-stat">
              <span class="imc-ico" role="img" aria-label="working">
                <span class="imc-dots" aria-hidden="true"><i /><i /><i /></span>
              </span>
              <div><b>128</b><p>projects shipping this week</p></div>
            </div>

            <!-- The feature row inks itself in on entry. -->
            <div class="imc-feats">
              <div v-for="(f, fi) in features" :key="f.name" class="imc-feat">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                  <path v-for="(d, p) in f.paths" :key="p" pathLength="1" :d="d" :style="{ '--d': `${fi * 160 + p * 130}ms` }" />
                </svg>
                <h4>{{ f.name }}</h4>
              </div>
            </div>
          </div>
        </template>

        <style scoped>
        .imc-hero { position: relative; display: grid; justify-items: center; gap: 1.15rem; padding: 3rem 1.25rem;
                    border: 1px solid #e7e5e4; border-radius: 1.4rem; overflow: clip; text-align: center; }
        .imc-bgart { position: absolute; inset: 0; color: #4f46e5; opacity: .35; pointer-events: none; }
        .imc-bgart path { fill: none; stroke: currentColor; stroke-width: 1.6; stroke-linecap: round;
                          stroke-dasharray: 1; stroke-dashoffset: 1; transition: stroke-dashoffset 1s ease var(--d, 0s); }
        .imc-hero[data-seen='true'] .imc-bgart path { stroke-dashoffset: 0; }
        .imc-hero > *:not(.imc-bgart) { position: relative; }
        .imc-iconbtn { display: inline-grid; place-items: center; block-size: 2.7rem; aspect-ratio: 1;
                       border: 1px solid #e7e5e4; border-radius: .85rem; background: #fff; cursor: pointer; }
        .imc-iconbtn[aria-pressed='true'] { color: #ec4899; border-color: #ec4899; }
        .imc-stroke { fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .imc-fill { fill: currentColor; scale: 0; transform-box: fill-box; transform-origin: center; }
        .imc-iconbtn[aria-pressed='true'] .imc-fill { scale: 1; animation: imc-pop .45s cubic-bezier(.2,.9,.3,1.35); }
        .imc-dots { display: flex; gap: .22rem; }
        .imc-dots i { inline-size: .3rem; block-size: .3rem; border-radius: 50%; background: currentColor;
                      animation: imc-dot .9s ease-in-out infinite; }
        .imc-dots i:nth-child(2) { animation-delay: .15s; }
        .imc-dots i:nth-child(3) { animation-delay: .3s; }
        .imc-feat path { fill: none; stroke: #4f46e5; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
                         stroke-dasharray: 1; stroke-dashoffset: 1; transition: stroke-dashoffset .55s ease var(--d, 0s); }
        .imc[data-seen='true'] .imc-feat path { stroke-dashoffset: 0; }
        @keyframes imc-pop { 0% { scale: 0; } 62% { scale: 1.28; } 100% { scale: 1; } }
        @keyframes imc-dot { 0%, 100% { translate: 0 0; opacity: .45; } 40% { translate: 0 -.28rem; opacity: 1; } }
        @media (prefers-reduced-motion: reduce) {
          .imc-bgart path, .imc-feat path { transition: none; stroke-dashoffset: 0; }
          .imc-iconbtn[aria-pressed='true'] .imc-fill { animation: none; }
          .imc-dots i { animation: none; }
        }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- IconShowcase.svelte — the launch section speaking the whole vocabulary -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          const HEART = 'M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3.5c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z';
          const bg = [
            'M60 220a120 120 0 1 1 240 0', 'M300 220a120 120 0 1 1 -240 0',
            'M100 220a80 80 0 1 1 160 0', 'M260 220a80 80 0 1 1 -160 0',
            'M180 220h40', 'm224 204 24-24', 'm256 168 28 8', 'M288 128a56 56 0 0 1 56 56',
          ];
          const features = [
            { name: 'Guarded rollouts', paths: ['M12 22s8-3.5 8-9.5V5l-8-3-8 3v7.5C4 18.5 12 22 12 22Z', 'm9 12 2 2 4-4'] },
            { name: 'Live sync', paths: ['M19.5 13.5A8 8 0 0 1 5.6 16.4', 'M4.5 13.5v3.8h3.8', 'M4.5 10.5A8 8 0 0 1 18.4 7.6', 'M19.5 10.5V6.7h-3.8'] },
            { name: 'Full observability', paths: ['M3 3v18h18', 'M7 15v3M12 10v8M17 6v12'] },
          ];
          let seen = $state(false);
          let liked = $state(false);
          let root: HTMLElement;

          onMount(() => {
            const io = new IntersectionObserver((entries) => entries.forEach((entry) => {
              if (entry.isIntersecting) { seen = true; io.disconnect(); }
            }), { threshold: 0.25 });
            io.observe(root);
            return () => io.disconnect();
          });
        </script>

        <div bind:this={root} class="imc" data-seen={seen}>
          <div class="imc-hero" data-seen={seen}>
            <!-- The self-drawing background breathes behind the headline. -->
            <svg class="imc-bgart" viewBox="0 0 360 440" aria-hidden="true">
              {#each bg as d, i (d)}
                <path pathLength="1" {d} style="--d: {i * 140}ms" />
              {/each}
            </svg>
            <h4>Ship the work. Watch it move.</h4>
            <div class="imc-ctas">
              <a class="imc-primary" href="#start">Start free</a>
              <!-- The stateful heart toggle beside the CTA. -->
              <button type="button" class="imc-iconbtn" aria-pressed={liked} aria-label="Like this page" onclick={() => (liked = !liked)}>
                <svg viewBox="0 0 24 24" aria-hidden="true">
                  <path class="imc-stroke" d={HEART} />
                  <path class="imc-fill" d={HEART} />
                </svg>
              </button>
            </div>
          </div>

          <!-- A stat card with its working dot trio. -->
          <div class="imc-stat">
            <span class="imc-ico" role="img" aria-label="working">
              <span class="imc-dots" aria-hidden="true"><i /><i /><i /></span>
            </span>
            <div><b>128</b><p>projects shipping this week</p></div>
          </div>

          <!-- The feature row inks itself in on entry. -->
          <div class="imc-feats">
            {#each features as f, fi (f.name)}
              <div class="imc-feat">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                  {#each f.paths as d, p (d)}
                    <path pathLength="1" {d} style="--d: {fi * 160 + p * 130}ms" />
                  {/each}
                </svg>
                <h4>{f.name}</h4>
              </div>
            {/each}
          </div>
        </div>

        <style>
        .imc-hero { position: relative; display: grid; justify-items: center; gap: 1.15rem; padding: 3rem 1.25rem;
                    border: 1px solid #e7e5e4; border-radius: 1.4rem; overflow: clip; text-align: center; }
        .imc-bgart { position: absolute; inset: 0; color: #4f46e5; opacity: .35; pointer-events: none; }
        .imc-bgart path { fill: none; stroke: currentColor; stroke-width: 1.6; stroke-linecap: round;
                          stroke-dasharray: 1; stroke-dashoffset: 1; transition: stroke-dashoffset 1s ease var(--d, 0s); }
        .imc-hero[data-seen='true'] .imc-bgart path { stroke-dashoffset: 0; }
        .imc-hero > *:not(.imc-bgart) { position: relative; }
        .imc-iconbtn { display: inline-grid; place-items: center; block-size: 2.7rem; aspect-ratio: 1;
                       border: 1px solid #e7e5e4; border-radius: .85rem; background: #fff; cursor: pointer; }
        .imc-iconbtn[aria-pressed='true'] { color: #ec4899; border-color: #ec4899; }
        .imc-stroke { fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .imc-fill { fill: currentColor; scale: 0; transform-box: fill-box; transform-origin: center; }
        .imc-iconbtn[aria-pressed='true'] .imc-fill { scale: 1; animation: imc-pop .45s cubic-bezier(.2,.9,.3,1.35); }
        .imc-dots { display: flex; gap: .22rem; }
        .imc-dots i { inline-size: .3rem; block-size: .3rem; border-radius: 50%; background: currentColor;
                      animation: imc-dot .9s ease-in-out infinite; }
        .imc-dots i:nth-child(2) { animation-delay: .15s; }
        .imc-dots i:nth-child(3) { animation-delay: .3s; }
        .imc-feat path { fill: none; stroke: #4f46e5; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
                         stroke-dasharray: 1; stroke-dashoffset: 1; transition: stroke-dashoffset .55s ease var(--d, 0s); }
        .imc[data-seen='true'] .imc-feat path { stroke-dashoffset: 0; }
        @keyframes imc-pop { 0% { scale: 0; } 62% { scale: 1.28; } 100% { scale: 1; } }
        @keyframes imc-dot { 0%, 100% { translate: 0 0; opacity: .45; } 40% { translate: 0 -.28rem; opacity: 1; } }
        @media (prefers-reduced-motion: reduce) {
          .imc-bgart path, .imc-feat path { transition: none; stroke-dashoffset: 0; }
          .imc-iconbtn[aria-pressed='true'] .imc-fill { animation: none; }
          .imc-dots i { animation: none; }
        }
        </style>
        SVELTE,
        ],
    ],
];
