<?php

/**
 * Demo manifest of the "maps" group — hand-drawn, fully-SVG city maps with
 * real interaction and zero external tiles or SDKs: a marker map whose pins
 * swell on hover and pop detail popovers, a route map that draws its dashed
 * line between numbered stops with a journey sidebar and an alternate route,
 * and a store locator with live search, city filter, smooth marker panning
 * and an empty state. Scenarios live at
 * resources/views/demos/components/maps/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'نقشه', 'en' => 'Maps'],

    'marker-map' => [
        'title' => ['fa' => 'نقشهٔ مارکردار', 'en' => 'Marker map'],
        'icon' => 'globe',
        'oneLiner' => [
            'fa' => 'شهری بندری و تمام‌SVG با ۶ مارکر زنده: هاور پین را متورم و لیبلش را پیدا می‌کند، کلیک پاپ‌اور جزئیات را باز می‌کند — عکس‌بندانگشتی گرادیانی، امتیاز و ساعت پایانی — و پنج ناحیهٔ شهر زیر اشاره‌گر روشن می‌شوند؛ بدون یک تایل یا SDK خارجی.',
            'en' => 'An all-SVG harbour city with six live markers: hover swells the pin and finds its label, click opens the details popover — gradient thumbnail, rating, closing hour — and five districts light up under the pointer; not a single external tile or SDK.',
        ],
        'js' => true,
        'docs' => 'https://mapcn.dev',
        'props' => [
            ['name' => 'markers', 'type' => 'count', 'default' => "'6'", 'note' => [
                'fa' => 'هر مارکر یک دکمهٔ واقعی است؛ با Tab می‌رسد، با Enter پاپ‌اورش را باز می‌کند و هم‌زمان فقط یک پاپ‌اور باز است.',
                'en' => 'Every marker is a real button — reachable with Tab, opened with Enter, and only one popover at a time.',
            ]],
            ['name' => 'popover', 'type' => 'state', 'default' => "'click · one-open'", 'note' => [
                'fa' => 'کلیک روی مارکر دیگر پاپ‌اور قبلی را می‌بندد؛ Escape و دکمهٔ × هم می‌بندند. پاپ‌اور مارکرهای نزدیک لبهٔ بالا به زیر پین می‌چرخد.',
                'en' => 'Clicking another marker folds the previous popover; Escape and the × button close it too. Pins near the top edge flip their popover below.',
            ]],
            ['name' => 'districts', 'type' => 'hover', 'default' => "'5 areas'", 'note' => [
                'fa' => 'پنج ناحیهٔ نیمه‌شفاف — بندر قدیمی، شهر قدیمی، خیابان موزه‌ها… — که با هاور روی خودشان یا روی چیپِ راهنما پررنگ می‌شوند.',
                'en' => 'Five translucent areas — Old Harbor, Old Town, Museum Mile… — that fill in on hover, on the map itself or via their legend chip.',
            ]],
            ['name' => 'thumb', 'type' => 'gradient', 'default' => "'per-poi'", 'note' => [
                'fa' => 'عکس‌بندانگشتی پاپ‌اور یک گرادیان دوتایی است که ته‌رنگش از هر مکان می‌آید — بدون هیچ درخواست تصویر.',
                'en' => 'The popover’s thumbnail is a two-stop gradient seeded per POI — zero image requests.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="mpm-root" x-data="{ open: null }" x-on:keydown.escape.window="open = null">
            <div class="mpm-stage" dir="ltr">
                <svg class="mpm-city" viewBox="0 0 640 400" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
                    <path class="mpm-river" d="M-10 214 C130 192 210 252 330 232 S520 188 650 218"/>
                    <g class="mpm-blocks">
                        <rect x="40" y="44" width="110" height="78" rx="10"/>
                        <rect x="286" y="150" width="110" height="64" rx="10"/>
                        <rect x="468" y="240" width="122" height="72" rx="10"/>
                    </g>
                    <polygon class="mpm-area" points="0,96 340,120 470,230 430,300 190,330 0,290"/>
                </svg>

                <button type="button" class="mpm-pin" style="inset-inline-start: 24%; inset-block-start: 62%"
                        x-bind:aria-expanded="open === 'anchor'"
                        x-on:click="open = open === 'anchor' ? null : 'anchor'">
                    <span class="mpm-bead" aria-hidden="true"></span>
                    <span class="mpm-label">لنگر · کافه</span>
                </button>
                <div class="mpm-pop" x-cloak x-show="open === 'anchor'" style="inset-inline-start: 24%; inset-block-start: 62%">
                    <span class="mpm-thumb" style="background: linear-gradient(135deg, #38bdf8, #6366f1)"></span>
                    <b>لنگر</b>
                    <small>بندر قدیمی · ★ ۴٫۸ · تا ۲۲:۰۰</small>
                </div>
            </div>
        </div>

        <style>
        .mpm-root { --mpm-bg: #f6f7f9; --mpm-block: #e8eaef; --mpm-water: #cfe3f7; --mpm-line: #d4d8e0;
                    --mpm-accent: #4f46e5; --mpm-text: #1c1917;
                    font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif; }
        .mpm-stage { position: relative; inline-size: min(100%, 40rem); aspect-ratio: 16 / 10;
                     overflow: clip; border: 1px solid var(--mpm-line); border-radius: 1rem; background: var(--mpm-bg); }
        .mpm-city { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
        .mpm-river { fill: none; stroke: var(--mpm-water); stroke-width: 26; stroke-linecap: round; }
        .mpm-blocks rect { fill: var(--mpm-block); }
        .mpm-area { fill: color-mix(in oklab, var(--mpm-accent) 8%, transparent); stroke: none; transition: fill .2s; }
        .mpm-area:hover { fill: color-mix(in oklab, var(--mpm-accent) 20%, transparent); }
        .mpm-pin { position: absolute; translate: -50% -50%; inline-size: 1.6rem; aspect-ratio: 1;
                   border: none; padding: 0; background: transparent; cursor: pointer; }
        .mpm-bead { display: block; inline-size: 100%; aspect-ratio: 1; border-radius: 50%;
                    border: 3px solid #fff; background: var(--mpm-accent);
                    box-shadow: 0 2px 8px rgba(0, 0, 0, .18); transition: scale .2s; }
        .mpm-pin:hover .mpm-bead, .mpm-pin:focus-visible .mpm-bead { scale: 1.25; }
        .mpm-label { position: absolute; inset-block-end: calc(100% + .45rem); inset-inline-start: 50%;
                     translate: -50%; white-space: nowrap; padding: .2rem .6rem; border-radius: .6rem;
                     background: #fff; color: var(--mpm-text); font-size: .72rem; opacity: 0; transition: opacity .2s; }
        .mpm-pin:hover .mpm-label, .mpm-pin:focus-visible .mpm-label { opacity: 1; }
        .mpm-pop { position: absolute; translate: -50% calc(-100% - 1rem); inline-size: 11rem; display: grid; gap: .3rem;
                   padding: .6rem; border: 1px solid var(--mpm-line); border-radius: .8rem; background: #fff;
                   color: var(--mpm-text); box-shadow: 0 10px 30px rgba(0, 0, 0, .12); }
        .mpm-thumb { block-size: 3.2rem; border-radius: .5rem; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="mpm-root" x-data="{ open: null }" x-on:keydown.escape.window="open = null">
        <div class="mpm-stage" dir="ltr">
        <svg class="mpm-city" viewBox="0 0 640 400" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
        <path class="mpm-river" d="M-10 214 C130 192 210 252 330 232 S520 188 650 218"/>
        <g class="mpm-blocks">
        <rect x="40" y="44" width="110" height="78" rx="10"/>
        <rect x="286" y="150" width="110" height="64" rx="10"/>
        <rect x="468" y="240" width="122" height="72" rx="10"/>
        </g>
        <polygon class="mpm-area" points="0,96 340,120 470,230 430,300 190,330 0,290"/>
        </svg>

        <button type="button" class="mpm-pin" style="inset-inline-start: 24%; inset-block-start: 62%"
        x-bind:aria-expanded="open === 'anchor'"
        x-on:click="open = open === 'anchor' ? null : 'anchor'">
        <span class="mpm-bead" aria-hidden="true"></span>
        <span class="mpm-label">The Anchor · Café</span>
        </button>
        <div class="mpm-pop" x-cloak x-show="open === 'anchor'" style="inset-inline-start: 24%; inset-block-start: 62%">
        <span class="mpm-thumb" style="background: linear-gradient(135deg, #38bdf8, #6366f1)"></span>
        <b>The Anchor</b>
        <small>Old Harbor · ★ 4.8 · until 22:00</small>
        </div>
        </div>
        </div>

        <style>
        .mpm-root { --mpm-bg: #f6f7f9; --mpm-block: #e8eaef; --mpm-water: #cfe3f7; --mpm-line: #d4d8e0;
        --mpm-accent: #4f46e5; --mpm-text: #1c1917;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif; }
        .mpm-stage { position: relative; inline-size: min(100%, 40rem); aspect-ratio: 16 / 10;
        overflow: clip; border: 1px solid var(--mpm-line); border-radius: 1rem; background: var(--mpm-bg); }
        .mpm-city { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
        .mpm-river { fill: none; stroke: var(--mpm-water); stroke-width: 26; stroke-linecap: round; }
        .mpm-blocks rect { fill: var(--mpm-block); }
        .mpm-area { fill: color-mix(in oklab, var(--mpm-accent) 8%, transparent); stroke: none; transition: fill .2s; }
        .mpm-area:hover { fill: color-mix(in oklab, var(--mpm-accent) 20%, transparent); }
        .mpm-pin { position: absolute; translate: -50% -50%; inline-size: 1.6rem; aspect-ratio: 1;
        border: none; padding: 0; background: transparent; cursor: pointer; }
        .mpm-bead { display: block; inline-size: 100%; aspect-ratio: 1; border-radius: 50%;
        border: 3px solid #fff; background: var(--mpm-accent);
        box-shadow: 0 2px 8px rgba(0, 0, 0, .18); transition: scale .2s; }
        .mpm-pin:hover .mpm-bead, .mpm-pin:focus-visible .mpm-bead { scale: 1.25; }
        .mpm-label { position: absolute; inset-block-end: calc(100% + .45rem); inset-inline-start: 50%;
        translate: -50%; white-space: nowrap; padding: .2rem .6rem; border-radius: .6rem;
        background: #fff; color: var(--mpm-text); font-size: .72rem; opacity: 0; transition: opacity .2s; }
        .mpm-pin:hover .mpm-label, .mpm-pin:focus-visible .mpm-label { opacity: 1; }
        .mpm-pop { position: absolute; translate: -50% calc(-100% - 1rem); inline-size: 11rem; display: grid; gap: .3rem;
        padding: .6rem; border: 1px solid var(--mpm-line); border-radius: .8rem; background: #fff;
        color: var(--mpm-text); box-shadow: 0 10px 30px rgba(0, 0, 0, .12); }
        .mpm-thumb { block-size: 3.2rem; border-radius: .5rem; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/CityGuide.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import CityPois from '@/components/CityPois';

        export default function CityGuide() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <CityPois />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // CityPois.jsx — marker map: hover swells the pin, click opens its popover
        import { useState } from 'react';
        import '@nabuxai/ui-core/css';

        const POIS = [
          { id: 'anchor', name: 'The Anchor', kind: 'Café', district: 'Old Harbor', rating: 4.8, until: '22:00', x: 24, y: 62, tint: ['#38bdf8', '#6366f1'] },
          { id: 'northlight', name: 'North Light Gallery', kind: 'Gallery', district: 'Museum Mile', rating: 4.9, until: '18:00', x: 58, y: 40, tint: ['#f59e0b', '#ef4444'] },
          { id: 'kessel', name: 'Kessel Records', kind: 'Vinyl', district: 'Old Town', rating: 4.6, until: '21:00', x: 44, y: 52, tint: ['#34d399', '#0ea5e9'] },
        ];
        const BLOCKS = [[40, 44, 110, 78], [286, 150, 110, 64], [468, 240, 122, 72], [150, 146, 96, 86]];

        export default function CityPois() {
          const [open, setOpen] = useState(null);

          return (
            <>
              <style>{`
                .mpm-root { --mpm-bg: #f6f7f9; --mpm-block: #e8eaef; --mpm-water: #cfe3f7; --mpm-line: #d4d8e0;
                            --mpm-accent: #4f46e5; --mpm-text: #1c1917; font-family: 'Inter', ui-sans-serif, sans-serif; }
                .mpm-stage { position: relative; inline-size: min(100%, 40rem); aspect-ratio: 16 / 10; overflow: clip;
                             border: 1px solid var(--mpm-line); border-radius: 1rem; background: var(--mpm-bg); }
                .mpm-city { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
                .mpm-river { fill: none; stroke: var(--mpm-water); stroke-width: 26; stroke-linecap: round; }
                .mpm-block { fill: var(--mpm-block); }
                .mpm-area { fill: color-mix(in oklab, var(--mpm-accent) 8%, transparent); transition: fill .2s; }
                .mpm-area:hover { fill: color-mix(in oklab, var(--mpm-accent) 20%, transparent); }
                .mpm-pin { position: absolute; translate: -50% -50%; inline-size: 1.6rem; aspect-ratio: 1; border: none;
                           padding: 0; background: transparent; cursor: pointer; }
                .mpm-bead { display: block; inline-size: 100%; aspect-ratio: 1; border-radius: 50%; border: 3px solid #fff;
                            background: var(--mpm-accent); box-shadow: 0 2px 8px rgba(0, 0, 0, .18); transition: scale .2s; }
                .mpm-pin:hover .mpm-bead, .mpm-pin:focus-visible .mpm-bead { scale: 1.25; }
                .mpm-label { position: absolute; inset-block-end: calc(100% + .45rem); inset-inline-start: 50%; translate: -50%;
                             white-space: nowrap; padding: .2rem .6rem; border-radius: .6rem; background: #fff;
                             color: var(--mpm-text); font-size: .72rem; opacity: 0; transition: opacity .2s; }
                .mpm-pin:hover .mpm-label, .mpm-pin:focus-visible .mpm-label { opacity: 1; }
                .mpm-pop { position: absolute; translate: -50% calc(-100% - 1rem); inline-size: 11rem; display: grid; gap: .3rem;
                           padding: .6rem; border: 1px solid var(--mpm-line); border-radius: .8rem; background: #fff;
                           color: var(--mpm-text); box-shadow: 0 10px 30px rgba(0, 0, 0, .12); }
                .mpm-thumb { block-size: 3.2rem; border-radius: .5rem; }
                .mpm-pin:focus-visible { outline: none; }
              `}</style>
              <div className="mpm-root">
                <div className="mpm-stage" dir="ltr" role="img" aria-label="Stylised city map with points of interest">
                  <svg className="mpm-city" viewBox="0 0 640 400" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
                    <path className="mpm-river" d="M-10 214 C130 192 210 252 330 232 S520 188 650 218" />
                    {BLOCKS.map(([x, y, w, h], i) => (
                      <rect key={i} className="mpm-block" x={x} y={y} width={w} height={h} rx="10" />
                    ))}
                    <polygon className="mpm-area" points="0,96 340,120 470,230 430,300 190,330 0,290" />
                  </svg>
                  {/* One popover at a time — clicking a pin folds the previous one. */}
                  {POIS.map((p) => (
                    <div key={p.id} style={{ position: 'absolute', inset: 0, pointerEvents: 'none' }}>
                      <button type="button" className="mpm-pin" style={{ insetInlineStart: p.x + '%', insetBlockStart: p.y + '%', pointerEvents: 'auto' }}
                        aria-expanded={open === p.id} onClick={() => setOpen(open === p.id ? null : p.id)}>
                        <span className="mpm-bead" aria-hidden="true" />
                        <span className="mpm-label">{p.name} · {p.kind}</span>
                      </button>
                      {open === p.id && (
                        <div className="mpm-pop" role="dialog" aria-label={p.name}
                          style={{ insetInlineStart: p.x + '%', insetBlockStart: p.y + '%', pointerEvents: 'auto' }}>
                          <span className="mpm-thumb" style={{ background: `linear-gradient(135deg, ${p.tint[0]}, ${p.tint[1]})` }} />
                          <b>{p.name}</b>
                          <small>{p.district} · ★ {p.rating} · until {p.until}</small>
                        </div>
                      )}
                    </div>
                  ))}
                </div>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- CityPois.vue — marker map: hover swells the pin, click opens its popover -->
        <script setup lang="ts">
        import { ref } from 'vue';
        import '@nabuxai/ui-core/css';

        interface Poi { id: string; name: string; kind: string; district: string; rating: number; until: string; x: number; y: number; tint: [string, string] }
        const pois: Poi[] = [
          { id: 'anchor', name: 'The Anchor', kind: 'Café', district: 'Old Harbor', rating: 4.8, until: '22:00', x: 24, y: 62, tint: ['#38bdf8', '#6366f1'] },
          { id: 'northlight', name: 'North Light Gallery', kind: 'Gallery', district: 'Museum Mile', rating: 4.9, until: '18:00', x: 58, y: 40, tint: ['#f59e0b', '#ef4444'] },
          { id: 'kessel', name: 'Kessel Records', kind: 'Vinyl', district: 'Old Town', rating: 4.6, until: '21:00', x: 44, y: 52, tint: ['#34d399', '#0ea5e9'] },
        ];
        const blocks = [[40, 44, 110, 78], [286, 150, 110, 64], [468, 240, 122, 72], [150, 146, 96, 86]];
        const open = ref<string | null>(null);
        const flip = (id: string) => { open.value = open.value === id ? null : id; };
        </script>

        <template>
          <div class="mpm-root">
            <div class="mpm-stage" dir="ltr" role="img" aria-label="Stylised city map with points of interest">
              <svg class="mpm-city" viewBox="0 0 640 400" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
                <path class="mpm-river" d="M-10 214 C130 192 210 252 330 232 S520 188 650 218" />
                <rect v-for="([x, y, w, h], i) in blocks" :key="i" class="mpm-block" :x="x" :y="y" :width="w" :height="h" rx="10" />
                <polygon class="mpm-area" points="0,96 340,120 470,230 430,300 190,330 0,290" />
              </svg>
              <!-- One popover at a time — clicking a pin folds the previous one. -->
              <template v-for="p in pois" :key="p.id">
                <button type="button" class="mpm-pin" :style="{ insetInlineStart: p.x + '%', insetBlockStart: p.y + '%' }"
                  :aria-expanded="open === p.id" @click="flip(p.id)">
                  <span class="mpm-bead" aria-hidden="true"></span>
                  <span class="mpm-label">{{ p.name }} · {{ p.kind }}</span>
                </button>
                <div v-if="open === p.id" class="mpm-pop" role="dialog" :aria-label="p.name"
                  :style="{ insetInlineStart: p.x + '%', insetBlockStart: p.y + '%' }">
                  <span class="mpm-thumb" :style="{ background: `linear-gradient(135deg, ${p.tint[0]}, ${p.tint[1]})` }"></span>
                  <b>{{ p.name }}</b>
                  <small>{{ p.district }} · ★ {{ p.rating }} · until {{ p.until }}</small>
                </div>
              </template>
            </div>
          </div>
        </template>

        <style scoped>
        .mpm-root { --mpm-bg: #f6f7f9; --mpm-block: #e8eaef; --mpm-water: #cfe3f7; --mpm-line: #d4d8e0;
                    --mpm-accent: #4f46e5; --mpm-text: #1c1917; font-family: 'Inter', ui-sans-serif, sans-serif; }
        .mpm-stage { position: relative; inline-size: min(100%, 40rem); aspect-ratio: 16 / 10; overflow: clip;
                     border: 1px solid var(--mpm-line); border-radius: 1rem; background: var(--mpm-bg); }
        .mpm-city { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
        .mpm-river { fill: none; stroke: var(--mpm-water); stroke-width: 26; stroke-linecap: round; }
        .mpm-block { fill: var(--mpm-block); }
        .mpm-area { fill: color-mix(in oklab, var(--mpm-accent) 8%, transparent); transition: fill .2s; }
        .mpm-area:hover { fill: color-mix(in oklab, var(--mpm-accent) 20%, transparent); }
        .mpm-pin { position: absolute; translate: -50% -50%; inline-size: 1.6rem; aspect-ratio: 1; border: none;
                   padding: 0; background: transparent; cursor: pointer; }
        .mpm-bead { display: block; inline-size: 100%; aspect-ratio: 1; border-radius: 50%; border: 3px solid #fff;
                    background: var(--mpm-accent); box-shadow: 0 2px 8px rgba(0, 0, 0, .18); transition: scale .2s; }
        .mpm-pin:hover .mpm-bead, .mpm-pin:focus-visible .mpm-bead { scale: 1.25; }
        .mpm-label { position: absolute; inset-block-end: calc(100% + .45rem); inset-inline-start: 50%; translate: -50%;
                     white-space: nowrap; padding: .2rem .6rem; border-radius: .6rem; background: #fff;
                     color: var(--mpm-text); font-size: .72rem; opacity: 0; transition: opacity .2s; }
        .mpm-pin:hover .mpm-label, .mpm-pin:focus-visible .mpm-label { opacity: 1; }
        .mpm-pop { position: absolute; translate: -50% calc(-100% - 1rem); inline-size: 11rem; display: grid; gap: .3rem;
                   padding: .6rem; border: 1px solid var(--mpm-line); border-radius: .8rem; background: #fff;
                   color: var(--mpm-text); box-shadow: 0 10px 30px rgba(0, 0, 0, .12); }
        .mpm-thumb { block-size: 3.2rem; border-radius: .5rem; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- CityPois.svelte — marker map: hover swells the pin, click opens its popover -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          interface Poi { id: string; name: string; kind: string; district: string; rating: number; until: string; x: number; y: number; tint: [string, string] }
          const pois: Poi[] = [
            { id: 'anchor', name: 'The Anchor', kind: 'Café', district: 'Old Harbor', rating: 4.8, until: '22:00', x: 24, y: 62, tint: ['#38bdf8', '#6366f1'] },
            { id: 'northlight', name: 'North Light Gallery', kind: 'Gallery', district: 'Museum Mile', rating: 4.9, until: '18:00', x: 58, y: 40, tint: ['#f59e0b', '#ef4444'] },
            { id: 'kessel', name: 'Kessel Records', kind: 'Vinyl', district: 'Old Town', rating: 4.6, until: '21:00', x: 44, y: 52, tint: ['#34d399', '#0ea5e9'] },
          ];
          const blocks = [[40, 44, 110, 78], [286, 150, 110, 64], [468, 240, 122, 72], [150, 146, 96, 86]];
          let open = $state<string | null>(null);
          const flip = (id: string) => { open = open === id ? null : id; };
        </script>

        <div class="mpm-root">
          <div class="mpm-stage" dir="ltr" role="img" aria-label="Stylised city map with points of interest">
            <svg class="mpm-city" viewBox="0 0 640 400" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
              <path class="mpm-river" d="M-10 214 C130 192 210 252 330 232 S520 188 650 218" />
              {#each blocks as [x, y, w, h] (`${x}-${y}`)}
                <rect class="mpm-block" {x} {y} width={w} height={h} rx="10" />
              {/each}
              <polygon class="mpm-area" points="0,96 340,120 470,230 430,300 190,330 0,290" />
            </svg>
            <!-- One popover at a time — clicking a pin folds the previous one. -->
            {#each pois as p (p.id)}
              <button type="button" class="mpm-pin" style={`inset-inline-start: ${p.x}%; inset-block-start: ${p.y}%`}
                aria-expanded={open === p.id} onclick={() => flip(p.id)}>
                <span class="mpm-bead" aria-hidden="true"></span>
                <span class="mpm-label">{p.name} · {p.kind}</span>
              </button>
              {#if open === p.id}
                <div class="mpm-pop" role="dialog" aria-label={p.name}
                  style={`inset-inline-start: ${p.x}%; inset-block-start: ${p.y}%`}>
                  <span class="mpm-thumb" style={`background: linear-gradient(135deg, ${p.tint[0]}, ${p.tint[1]})`}></span>
                  <b>{p.name}</b>
                  <small>{p.district} · ★ {p.rating} · until {p.until}</small>
                </div>
              {/if}
            {/each}
          </div>
        </div>

        <style>
        .mpm-root { --mpm-bg: #f6f7f9; --mpm-block: #e8eaef; --mpm-water: #cfe3f7; --mpm-line: #d4d8e0;
                    --mpm-accent: #4f46e5; --mpm-text: #1c1917; font-family: 'Inter', ui-sans-serif, sans-serif; }
        .mpm-stage { position: relative; inline-size: min(100%, 40rem); aspect-ratio: 16 / 10; overflow: clip;
                     border: 1px solid var(--mpm-line); border-radius: 1rem; background: var(--mpm-bg); }
        .mpm-city { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
        .mpm-river { fill: none; stroke: var(--mpm-water); stroke-width: 26; stroke-linecap: round; }
        .mpm-block { fill: var(--mpm-block); }
        .mpm-area { fill: color-mix(in oklab, var(--mpm-accent) 8%, transparent); transition: fill .2s; }
        .mpm-area:hover { fill: color-mix(in oklab, var(--mpm-accent) 20%, transparent); }
        .mpm-pin { position: absolute; translate: -50% -50%; inline-size: 1.6rem; aspect-ratio: 1; border: none;
                   padding: 0; background: transparent; cursor: pointer; }
        .mpm-bead { display: block; inline-size: 100%; aspect-ratio: 1; border-radius: 50%; border: 3px solid #fff;
                    background: var(--mpm-accent); box-shadow: 0 2px 8px rgba(0, 0, 0, .18); transition: scale .2s; }
        .mpm-pin:hover .mpm-bead, .mpm-pin:focus-visible .mpm-bead { scale: 1.25; }
        .mpm-label { position: absolute; inset-block-end: calc(100% + .45rem); inset-inline-start: 50%; translate: -50%;
                     white-space: nowrap; padding: .2rem .6rem; border-radius: .6rem; background: #fff;
                     color: var(--mpm-text); font-size: .72rem; opacity: 0; transition: opacity .2s; }
        .mpm-pin:hover .mpm-label, .mpm-pin:focus-visible .mpm-label { opacity: 1; }
        .mpm-pop { position: absolute; translate: -50% calc(-100% - 1rem); inline-size: 11rem; display: grid; gap: .3rem;
                   padding: .6rem; border: 1px solid var(--mpm-line); border-radius: .8rem; background: #fff;
                   color: var(--mpm-text); box-shadow: 0 10px 30px rgba(0, 0, 0, .12); }
        .mpm-thumb { block-size: 3.2rem; border-radius: .5rem; }
        </style>
        SVELTE,
        ],
    ],

    'route-map' => [
        'title' => ['fa' => 'نقشهٔ مسیر', 'en' => 'Route map'],
        'icon' => 'arrow-right',
        'oneLiner' => [
            'fa' => 'خط اکسپرس از ایستگاه مرکزی تا فرودگاه روی نقشهٔ تمام‌SVG: مسیر نقطه‌چین خودش را از مبدأ تا مقصد ترسیم می‌کند، ایستگاه‌های میانی شماره می‌خورند و پشت سر هم می‌نشینند، سایدبار مراحل سفر را با ETA و فاصلهٔ هر بازو می‌شمارد و دکمهٔ «مسیر دیگر» خط رودخانه را از نو می‌کشد.',
            'en' => 'The express line from Central Station to the airport on an all-SVG map: the dotted route draws itself end to end, the intermediate stops take their numbers and settle in one by one, the sidebar counts the journey legs with ETA and distance, and the “other route” button re-draws the riverside line.',
        ],
        'js' => true,
        'docs' => 'https://mapcn.dev',
        'props' => [
            ['name' => 'draw', 'type' => 'duration', 'default' => "'1.8s'", 'note' => [
                'fa' => 'ترسیم با یک ماسک SVG انجام می‌شود که خودش از مبدأ تا مقصد پاک می‌شود؛ مسیر نقطه‌چین زیرش جان می‌گیرد.',
                'en' => 'The draw runs through an SVG mask that wipes itself from origin to destination; the dotted route beneath comes alive.',
            ]],
            ['name' => 'dash', 'type' => 'pattern', 'default' => "'.012 .03'", 'note' => [
                'fa' => 'نقطه‌چین با pathLength نرمال‌شده ساخته می‌شود — بدون محاسبهٔ طول واقعی مسیر، هر خطی یکنواخت دانه می‌افتد.',
                'en' => 'The dots come from a normalised pathLength — no measuring real lengths, every line beads evenly.',
            ]],
            ['name' => 'stops', 'type' => 'count', 'default' => "'5 · 5'", 'note' => [
                'fa' => 'هر مسیر ۵ ایستگاه دارد؛ شماره‌ها پشت سر هم با تأخیر پله‌ای، هم‌گام با نوک قلم سر می‌نشینند.',
                'en' => 'Each route carries five stops; the numbers settle in with a stepped delay, in step with the pen’s tip.',
            ]],
            ['name' => 'alt-route', 'type' => 'toggle', 'default' => "'a ⇄ b'", 'note' => [
                'fa' => 'دکمهٔ «مسیر دیگر» بین اکسسپرس بندر (۳۸ دقیقه · ۲۱٫۴ کیلومتر) و خط رودخانه (۴۶ دقیقه · ۲۴٫۸ کیلومتر) عوض می‌کند و ترسیم از نو اجرا می‌شود.',
                'en' => 'The “other route” button swaps the Harbor Express (38 min · 21.4 km) for the Riverside Line (46 min · 24.8 km) and the draw replays.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="mrt-root" x-data="{ alt: 'a' }">
            <div class="mrt-layer" x-bind:data-alt="alt" dir="ltr">
                <svg class="mrt-city" viewBox="0 0 640 400" aria-hidden="true">
                    <path class="mrt-river" d="M-10 366 C120 350 250 330 360 314 S540 268 650 224"/>
                    <rect class="mrt-block" x="60" y="60" width="110" height="70" rx="10"/>
                    <rect class="mrt-block" x="240" y="46" width="96" height="60" rx="10"/>
                    <rect class="mrt-block" x="330" y="220" width="110" height="60" rx="10"/>
                    <path class="mrt-ghost" d="M70 330 C130 310 140 280 170 255 C205 227 262 208 300 180 C340 150 400 168 450 150 C500 132 545 105 580 80"/>
                    <path class="mrt-ghost" d="M70 330 C110 345 150 352 190 345 C240 336 285 315 330 295 C385 271 430 252 470 230 C520 203 555 135 580 80"/>
                    <defs>
                        <mask id="mrt-wipe-a" maskUnits="userSpaceOnUse" x="0" y="0" width="640" height="400">
                            <path class="mrt-wipe mrt-wipe-a" pathLength="1" d="M70 330 C130 310 140 280 170 255 C205 227 262 208 300 180 C340 150 400 168 450 150 C500 132 545 105 580 80"/>
                        </mask>
                        <mask id="mrt-wipe-b" maskUnits="userSpaceOnUse" x="0" y="0" width="640" height="400">
                            <path class="mrt-wipe mrt-wipe-b" pathLength="1" d="M70 330 C110 345 150 352 190 345 C240 336 285 315 330 295 C385 271 430 252 470 230 C520 203 555 135 580 80"/>
                        </mask>
                    </defs>
                    <path class="mrt-line" pathLength="1" mask="url(#mrt-wipe-a)" d="M70 330 C130 310 140 280 170 255 C205 227 262 208 300 180 C340 150 400 168 450 150 C500 132 545 105 580 80"/>
                    <path class="mrt-line" pathLength="1" mask="url(#mrt-wipe-b)" d="M70 330 C110 345 150 352 190 345 C240 336 285 315 330 295 C385 271 430 252 470 230 C520 203 555 135 580 80"/>
                    <g class="mrt-stops mrt-stops-a">
                        <g class="mrt-stop" style="--i: 0"><circle cx="70" cy="330" r="8"/><text x="70" y="333">۱</text></g>
                        <g class="mrt-stop" style="--i: 1"><circle cx="170" cy="255" r="7"/><text x="170" y="258">۲</text></g>
                        <g class="mrt-stop" style="--i: 2"><circle cx="300" cy="180" r="7"/><text x="300" y="183">۳</text></g>
                        <g class="mrt-stop" style="--i: 3"><circle cx="450" cy="150" r="7"/><text x="450" y="153">۴</text></g>
                        <g class="mrt-stop" style="--i: 4"><circle cx="580" cy="80" r="8"/><text x="580" y="83">۵</text></g>
                    </g>
                    <g class="mrt-stops mrt-stops-b">
                        <g class="mrt-stop" style="--i: 0"><circle cx="70" cy="330" r="8"/><text x="70" y="333">۱</text></g>
                        <g class="mrt-stop" style="--i: 1"><circle cx="190" cy="345" r="7"/><text x="190" y="348">۲</text></g>
                        <g class="mrt-stop" style="--i: 2"><circle cx="330" cy="295" r="7"/><text x="330" y="298">۳</text></g>
                        <g class="mrt-stop" style="--i: 3"><circle cx="470" cy="230" r="7"/><text x="470" y="233">۴</text></g>
                        <g class="mrt-stop" style="--i: 4"><circle cx="580" cy="80" r="8"/><text x="580" y="83">۵</text></g>
                    </g>
                </svg>
            </div>
            <button type="button" class="mrt-swap" x-on:click="alt = alt === 'a' ? 'b' : 'a'">مسیر دیگر</button>
        </div>

        <style>
        .mrt-root { --mrt-bg: #f6f7f9; --mrt-block: #e8eaef; --mrt-water: #cfe3f7; --mrt-line: #d4d8e0;
                    --mrt-accent: #e11d48; --mrt-text: #1c1917;
                    font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif; }
        .mrt-layer { position: relative; inline-size: min(100%, 40rem); aspect-ratio: 16 / 10; overflow: clip;
                     border: 1px solid var(--mrt-line); border-radius: 1rem; background: var(--mrt-bg); }
        .mrt-city { inline-size: 100%; block-size: 100%; }
        .mrt-river { fill: none; stroke: var(--mrt-water); stroke-width: 22; stroke-linecap: round; }
        .mrt-block { fill: var(--mrt-block); }
        .mrt-ghost { fill: none; stroke: var(--mrt-line); stroke-width: 3; stroke-dasharray: 2 7; stroke-linecap: round; }
        .mrt-line { fill: none; stroke: var(--mrt-accent); stroke-width: 4.5; stroke-linecap: round; stroke-dasharray: .012 .03; }
        .mrt-wipe { fill: none; stroke: #fff; stroke-width: 18; stroke-dasharray: 1; stroke-dashoffset: 1; }
        .mrt-layer[data-alt='a'] .mrt-wipe-a { animation: mrt-draw 1.8s cubic-bezier(.4, .1, .2, 1) .2s forwards; }
        .mrt-layer[data-alt='b'] .mrt-wipe-b { animation: mrt-draw 1.8s cubic-bezier(.4, .1, .2, 1) .2s forwards; }
        @keyframes mrt-draw { to { stroke-dashoffset: 0; } }
        .mrt-stops { display: none; }
        .mrt-layer[data-alt='a'] .mrt-stops-a, .mrt-layer[data-alt='b'] .mrt-stops-b { display: block; }
        .mrt-stop { opacity: 0; scale: .5; transform-box: fill-box; transform-origin: center; }
        .mrt-layer[data-alt='a'] .mrt-stops-a .mrt-stop,
        .mrt-layer[data-alt='b'] .mrt-stops-b .mrt-stop { animation: mrt-stop-in .3s ease both; animation-delay: calc(.3s + var(--i) * .38s); }
        @keyframes mrt-stop-in { to { opacity: 1; scale: 1; } }
        .mrt-stop circle { fill: #fff; stroke: var(--mrt-accent); stroke-width: 3; }
        .mrt-stop text { fill: var(--mrt-accent); font: 700 9px sans-serif; text-anchor: middle; }
        .mrt-swap { margin-block-start: .8rem; padding: .55rem 1.1rem; border: none; border-radius: 999px;
                    background: var(--mrt-accent); color: #fff; font: 600 .82rem/1 inherit; cursor: pointer; }
        @media (prefers-reduced-motion: reduce) {
            .mrt-wipe { stroke-dashoffset: 0; animation: none; }
            .mrt-stop { animation: none; opacity: 1; scale: 1; }
        }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="mrt-root" x-data="{ alt: 'a' }">
        <div class="mrt-layer" x-bind:data-alt="alt" dir="ltr">
        <svg class="mrt-city" viewBox="0 0 640 400" aria-hidden="true">
        <path class="mrt-river" d="M-10 366 C120 350 250 330 360 314 S540 268 650 224"/>
        <rect class="mrt-block" x="60" y="60" width="110" height="70" rx="10"/>
        <rect class="mrt-block" x="240" y="46" width="96" height="60" rx="10"/>
        <rect class="mrt-block" x="330" y="220" width="110" height="60" rx="10"/>
        <path class="mrt-ghost" d="M70 330 C130 310 140 280 170 255 C205 227 262 208 300 180 C340 150 400 168 450 150 C500 132 545 105 580 80"/>
        <path class="mrt-ghost" d="M70 330 C110 345 150 352 190 345 C240 336 285 315 330 295 C385 271 430 252 470 230 C520 203 555 135 580 80"/>
        <defs>
        <mask id="mrt-wipe-a" maskUnits="userSpaceOnUse" x="0" y="0" width="640" height="400">
        <path class="mrt-wipe mrt-wipe-a" pathLength="1" d="M70 330 C130 310 140 280 170 255 C205 227 262 208 300 180 C340 150 400 168 450 150 C500 132 545 105 580 80"/>
        </mask>
        <mask id="mrt-wipe-b" maskUnits="userSpaceOnUse" x="0" y="0" width="640" height="400">
        <path class="mrt-wipe mrt-wipe-b" pathLength="1" d="M70 330 C110 345 150 352 190 345 C240 336 285 315 330 295 C385 271 430 252 470 230 C520 203 555 135 580 80"/>
        </mask>
        </defs>
        <path class="mrt-line" pathLength="1" mask="url(#mrt-wipe-a)" d="M70 330 C130 310 140 280 170 255 C205 227 262 208 300 180 C340 150 400 168 450 150 C500 132 545 105 580 80"/>
        <path class="mrt-line" pathLength="1" mask="url(#mrt-wipe-b)" d="M70 330 C110 345 150 352 190 345 C240 336 285 315 330 295 C385 271 430 252 470 230 C520 203 555 135 580 80"/>
        <g class="mrt-stops mrt-stops-a">
        <g class="mrt-stop" style="--i: 0"><circle cx="70" cy="330" r="8"/><text x="70" y="333">1</text></g>
        <g class="mrt-stop" style="--i: 1"><circle cx="170" cy="255" r="7"/><text x="170" y="258">2</text></g>
        <g class="mrt-stop" style="--i: 2"><circle cx="300" cy="180" r="7"/><text x="300" y="183">3</text></g>
        <g class="mrt-stop" style="--i: 3"><circle cx="450" cy="150" r="7"/><text x="450" y="153">4</text></g>
        <g class="mrt-stop" style="--i: 4"><circle cx="580" cy="80" r="8"/><text x="580" y="83">5</text></g>
        </g>
        <g class="mrt-stops mrt-stops-b">
        <g class="mrt-stop" style="--i: 0"><circle cx="70" cy="330" r="8"/><text x="70" y="333">1</text></g>
        <g class="mrt-stop" style="--i: 1"><circle cx="190" cy="345" r="7"/><text x="190" y="348">2</text></g>
        <g class="mrt-stop" style="--i: 2"><circle cx="330" cy="295" r="7"/><text x="330" y="298">3</text></g>
        <g class="mrt-stop" style="--i: 3"><circle cx="470" cy="230" r="7"/><text x="470" y="233">4</text></g>
        <g class="mrt-stop" style="--i: 4"><circle cx="580" cy="80" r="8"/><text x="580" y="83">5</text></g>
        </g>
        </svg>
        </div>
        <button type="button" class="mrt-swap" x-on:click="alt = alt === 'a' ? 'b' : 'a'">Other route</button>
        </div>

        <style>
        .mrt-root { --mrt-bg: #f6f7f9; --mrt-block: #e8eaef; --mrt-water: #cfe3f7; --mrt-line: #d4d8e0;
        --mrt-accent: #e11d48; --mrt-text: #1c1917;
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif; }
        .mrt-layer { position: relative; inline-size: min(100%, 40rem); aspect-ratio: 16 / 10; overflow: clip;
        border: 1px solid var(--mrt-line); border-radius: 1rem; background: var(--mrt-bg); }
        .mrt-city { inline-size: 100%; block-size: 100%; }
        .mrt-river { fill: none; stroke: var(--mrt-water); stroke-width: 22; stroke-linecap: round; }
        .mrt-block { fill: var(--mrt-block); }
        .mrt-ghost { fill: none; stroke: var(--mrt-line); stroke-width: 3; stroke-dasharray: 2 7; stroke-linecap: round; }
        .mrt-line { fill: none; stroke: var(--mrt-accent); stroke-width: 4.5; stroke-linecap: round; stroke-dasharray: .012 .03; }
        .mrt-wipe { fill: none; stroke: #fff; stroke-width: 18; stroke-dasharray: 1; stroke-dashoffset: 1; }
        .mrt-layer[data-alt='a'] .mrt-wipe-a { animation: mrt-draw 1.8s cubic-bezier(.4, .1, .2, 1) .2s forwards; }
        .mrt-layer[data-alt='b'] .mrt-wipe-b { animation: mrt-draw 1.8s cubic-bezier(.4, .1, .2, 1) .2s forwards; }
        @keyframes mrt-draw { to { stroke-dashoffset: 0; } }
        .mrt-stops { display: none; }
        .mrt-layer[data-alt='a'] .mrt-stops-a, .mrt-layer[data-alt='b'] .mrt-stops-b { display: block; }
        .mrt-stop { opacity: 0; scale: .5; transform-box: fill-box; transform-origin: center; }
        .mrt-layer[data-alt='a'] .mrt-stops-a .mrt-stop,
        .mrt-layer[data-alt='b'] .mrt-stops-b .mrt-stop { animation: mrt-stop-in .3s ease both; animation-delay: calc(.3s + var(--i) * .38s); }
        @keyframes mrt-stop-in { to { opacity: 1; scale: 1; } }
        .mrt-stop circle { fill: #fff; stroke: var(--mrt-accent); stroke-width: 3; }
        .mrt-stop text { fill: var(--mrt-accent); font: 700 9px sans-serif; text-anchor: middle; }
        .mrt-swap { margin-block-start: .8rem; padding: .55rem 1.1rem; border: none; border-radius: 999px;
        background: var(--mrt-accent); color: #fff; font: 600 .82rem/1 inherit; cursor: pointer; }
        @media (prefers-reduced-motion: reduce) {
        .mrt-wipe { stroke-dashoffset: 0; animation: none; }
        .mrt-stop { animation: none; opacity: 1; scale: 1; }
        }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/HarborRoute.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import RoutePlanner from '@/components/RoutePlanner';

        export default function HarborRoute() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <RoutePlanner />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // RoutePlanner.jsx — the dashed route draws itself; the swap replays it
        import { useState } from 'react';
        import '@nabuxai/ui-core/css';

        const ROUTES = {
          a: {
            name: 'Harbor Express', eta: '38 min', dist: '21.4 km',
            d: 'M70 330 C130 310 140 280 170 255 C205 227 262 208 300 180 C340 150 400 168 450 150 C500 132 545 105 580 80',
            stops: [
              { n: 1, x: 70, y: 330, name: 'Central Station', seg: 'origin' },
              { n: 2, x: 170, y: 255, name: 'Old Harbor', seg: '7 min · 4.1 km' },
              { n: 3, x: 300, y: 180, name: 'Museum Mile', seg: '9 min · 5.2 km' },
              { n: 4, x: 450, y: 150, name: 'Botanic Ring', seg: '11 min · 6.3 km' },
              { n: 5, x: 580, y: 80, name: 'Airport T3', seg: '11 min · 5.8 km' },
            ],
          },
          b: {
            name: 'Riverside Line', eta: '46 min', dist: '24.8 km',
            d: 'M70 330 C110 345 150 352 190 345 C240 336 285 315 330 295 C385 271 430 252 470 230 C520 203 555 135 580 80',
            stops: [
              { n: 1, x: 70, y: 330, name: 'Central Station', seg: 'origin' },
              { n: 2, x: 190, y: 345, name: 'Kessel Bridge', seg: '8 min · 4.4 km' },
              { n: 3, x: 330, y: 295, name: 'Signal Wharf', seg: '10 min · 5.6 km' },
              { n: 4, x: 470, y: 230, name: 'Freight Bypass', seg: '13 min · 6.7 km' },
              { n: 5, x: 580, y: 80, name: 'Airport T3', seg: '15 min · 8.1 km' },
            ],
          },
        };

        export default function RoutePlanner() {
          const [alt, setAlt] = useState('a');
          const route = ROUTES[alt];
          const other = ROUTES[alt === 'a' ? 'b' : 'a'];

          return (
            <>
              <style>{`
                .mrt-root { --mrt-bg: #f6f7f9; --mrt-block: #e8eaef; --mrt-water: #cfe3f7; --mrt-line: #d4d8e0;
                            --mrt-accent: #e11d48; --mrt-text: #1c1917; font-family: 'Inter', ui-sans-serif, sans-serif;
                            display: grid; gap: 1rem; grid-template-columns: minmax(0, 1.5fr) minmax(14rem, 1fr); }
                .mrt-layer { position: relative; aspect-ratio: 16 / 10; overflow: clip;
                             border: 1px solid var(--mrt-line); border-radius: 1rem; background: var(--mrt-bg); }
                .mrt-city { inline-size: 100%; block-size: 100%; }
                .mrt-river { fill: none; stroke: var(--mrt-water); stroke-width: 22; stroke-linecap: round; }
                .mrt-block { fill: var(--mrt-block); }
                .mrt-ghost { fill: none; stroke: var(--mrt-line); stroke-width: 3; stroke-dasharray: 2 7; stroke-linecap: round; }
                .mrt-line { fill: none; stroke: var(--mrt-accent); stroke-width: 4.5; stroke-linecap: round; stroke-dasharray: .012 .03; }
                .mrt-wipe { fill: none; stroke: #fff; stroke-width: 18; stroke-dasharray: 1; stroke-dashoffset: 1;
                            animation: mrt-draw 1.8s cubic-bezier(.4, .1, .2, 1) .2s forwards; }
                @keyframes mrt-draw { to { stroke-dashoffset: 0; } }
                .mrt-stop { opacity: 0; scale: .5; transform-box: fill-box; transform-origin: center;
                            animation: mrt-stop-in .3s ease both; animation-delay: calc(.3s + var(--i) * .38s); }
                @keyframes mrt-stop-in { to { opacity: 1; scale: 1; } }
                .mrt-stop circle { fill: #fff; stroke: var(--mrt-accent); stroke-width: 3; }
                .mrt-stop text { fill: var(--mrt-accent); font: 700 9px sans-serif; text-anchor: middle; }
                .mrt-stop .mrt-name { font: 600 11px sans-serif; fill: var(--mrt-text); }
                .mrt-panel { display: grid; gap: .75rem; align-content: start; padding: 1rem;
                             border: 1px solid var(--mrt-line); border-radius: 1rem; color: var(--mrt-text); }
                .mrt-badges { margin: 0; color: var(--mrt-accent); font-weight: 700; font-size: .85rem; }
                .mrt-steps { display: grid; gap: .55rem; margin: 0; padding: 0; list-style: none; }
                .mrt-steps li { display: grid; grid-template-columns: 1.5rem 1fr; gap: .25rem .6rem; align-items: baseline; }
                .mrt-steps i { display: grid; place-items: center; aspect-ratio: 1; border-radius: 50%;
                               border: 2px solid var(--mrt-accent); font: 700 .7rem/1 sans-serif; font-style: normal; }
                .mrt-steps small { grid-column: 2; color: var(--mrt-line); }
                .mrt-swap { padding: .6rem 1rem; border: none; border-radius: 999px; background: var(--mrt-accent);
                            color: #fff; font: 600 .82rem/1 inherit; cursor: pointer; }
                @media (prefers-reduced-motion: reduce) {
                  .mrt-wipe { stroke-dashoffset: 0; animation: none; }
                  .mrt-stop { animation: none; opacity: 1; scale: 1; }
                }
              `}</style>
              <div className="mrt-root">
                <div className="mrt-layer" dir="ltr">
                  <svg className="mrt-city" viewBox="0 0 640 400" role="img" aria-label={`Route of the ${route.name}`}>
                    <path className="mrt-river" d="M-10 366 C120 350 250 330 360 314 S540 268 650 224" />
                    <rect className="mrt-block" x="60" y="60" width="110" height="70" rx="10" />
                    <rect className="mrt-block" x="240" y="46" width="96" height="60" rx="10" />
                    <rect className="mrt-block" x="330" y="220" width="110" height="60" rx="10" />
                    {Object.values(ROUTES).map((r) => <path key={r.name} className="mrt-ghost" d={r.d} />)}
                    {/* Keying the group remounts it on swap — the CSS draw replays. */}
                    <g key={alt}>
                      <defs>
                        <mask id="mrt-wipe" maskUnits="userSpaceOnUse" x="0" y="0" width="640" height="400">
                          <path className="mrt-wipe" pathLength="1" d={route.d} />
                        </mask>
                      </defs>
                      <path className="mrt-line" pathLength="1" mask="url(#mrt-wipe)" d={route.d} />
                      {route.stops.map((s) => (
                        <g key={s.n} className="mrt-stop" style={{ '--i': s.n - 1 }}>
                          <circle cx={s.x} cy={s.y} r={s.n === 1 || s.n === 5 ? 8 : 7} />
                          <text x={s.x} y={s.y + 3.5}>{s.n}</text>
                          <text className="mrt-name" x={s.x} y={s.y - 14}>{s.name}</text>
                        </g>
                      ))}
                    </g>
                  </svg>
                </div>
                <aside className="mrt-panel">
                  <h4 style={{ margin: 0 }}>{route.name}</h4>
                  <p className="mrt-badges">{route.eta} · {route.dist}</p>
                  <ol className="mrt-steps">
                    {route.stops.map((s) => (
                      <li key={s.n}><i>{s.n}</i><b>{s.name}</b><small>{s.seg}</small></li>
                    ))}
                  </ol>
                  <button type="button" className="mrt-swap" onClick={() => setAlt(alt === 'a' ? 'b' : 'a')}>
                    Other route: {other.name}
                  </button>
                </aside>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- RoutePlanner.vue — the dashed route draws itself; the swap replays it -->
        <script setup lang="ts">
        import { computed, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        interface Stop { n: number; x: number; y: number; name: string; seg: string }
        interface Route { name: string; eta: string; dist: string; d: string; stops: Stop[] }
        const routes: Record<'a' | 'b', Route> = {
          a: {
            name: 'Harbor Express', eta: '38 min', dist: '21.4 km',
            d: 'M70 330 C130 310 140 280 170 255 C205 227 262 208 300 180 C340 150 400 168 450 150 C500 132 545 105 580 80',
            stops: [
              { n: 1, x: 70, y: 330, name: 'Central Station', seg: 'origin' },
              { n: 2, x: 170, y: 255, name: 'Old Harbor', seg: '7 min · 4.1 km' },
              { n: 3, x: 300, y: 180, name: 'Museum Mile', seg: '9 min · 5.2 km' },
              { n: 4, x: 450, y: 150, name: 'Botanic Ring', seg: '11 min · 6.3 km' },
              { n: 5, x: 580, y: 80, name: 'Airport T3', seg: '11 min · 5.8 km' },
            ],
          },
          b: {
            name: 'Riverside Line', eta: '46 min', dist: '24.8 km',
            d: 'M70 330 C110 345 150 352 190 345 C240 336 285 315 330 295 C385 271 430 252 470 230 C520 203 555 135 580 80',
            stops: [
              { n: 1, x: 70, y: 330, name: 'Central Station', seg: 'origin' },
              { n: 2, x: 190, y: 345, name: 'Kessel Bridge', seg: '8 min · 4.4 km' },
              { n: 3, x: 330, y: 295, name: 'Signal Wharf', seg: '10 min · 5.6 km' },
              { n: 4, x: 470, y: 230, name: 'Freight Bypass', seg: '13 min · 6.7 km' },
              { n: 5, x: 580, y: 80, name: 'Airport T3', seg: '15 min · 8.1 km' },
            ],
          },
        };
        const alt = ref<'a' | 'b'>('a');
        const route = computed(() => routes[alt.value]);
        const other = computed(() => routes[alt.value === 'a' ? 'b' : 'a']);
        const swap = () => { alt.value = alt.value === 'a' ? 'b' : 'a'; };
        </script>

        <template>
          <div class="mrt-root">
            <div class="mrt-layer" dir="ltr">
              <svg class="mrt-city" viewBox="0 0 640 400" role="img" :aria-label="`Route of the ${route.name}`">
                <path class="mrt-river" d="M-10 366 C120 350 250 330 360 314 S540 268 650 224" />
                <rect class="mrt-block" x="60" y="60" width="110" height="70" rx="10" />
                <rect class="mrt-block" x="240" y="46" width="96" height="60" rx="10" />
                <rect class="mrt-block" x="330" y="220" width="110" height="60" rx="10" />
                <path v-for="r in routes" :key="r.name" class="mrt-ghost" :d="r.d" />
                <!-- Keying the group remounts it on swap — the CSS draw replays. -->
                <g :key="alt">
                  <defs>
                    <mask id="mrt-wipe" maskUnits="userSpaceOnUse" x="0" y="0" width="640" height="400">
                      <path class="mrt-wipe" pathLength="1" :d="route.d" />
                    </mask>
                  </defs>
                  <path class="mrt-line" pathLength="1" mask="url(#mrt-wipe)" :d="route.d" />
                  <g v-for="s in route.stops" :key="s.n" class="mrt-stop" :style="{ '--i': s.n - 1 }">
                    <circle :cx="s.x" :cy="s.y" :r="s.n === 1 || s.n === 5 ? 8 : 7" />
                    <text :x="s.x" :y="s.y + 3.5">{{ s.n }}</text>
                    <text class="mrt-name" :x="s.x" :y="s.y - 14">{{ s.name }}</text>
                  </g>
                </g>
              </svg>
            </div>
            <aside class="mrt-panel">
              <h4 style="margin: 0">{{ route.name }}</h4>
              <p class="mrt-badges">{{ route.eta }} · {{ route.dist }}</p>
              <ol class="mrt-steps">
                <li v-for="s in route.stops" :key="s.n"><i>{{ s.n }}</i><b>{{ s.name }}</b><small>{{ s.seg }}</small></li>
              </ol>
              <button type="button" class="mrt-swap" @click="swap">Other route: {{ other.name }}</button>
            </aside>
          </div>
        </template>

        <style scoped>
        .mrt-root { --mrt-bg: #f6f7f9; --mrt-block: #e8eaef; --mrt-water: #cfe3f7; --mrt-line: #d4d8e0;
                    --mrt-accent: #e11d48; --mrt-text: #1c1917; font-family: 'Inter', ui-sans-serif, sans-serif;
                    display: grid; gap: 1rem; grid-template-columns: minmax(0, 1.5fr) minmax(14rem, 1fr); }
        .mrt-layer { position: relative; aspect-ratio: 16 / 10; overflow: clip;
                     border: 1px solid var(--mrt-line); border-radius: 1rem; background: var(--mrt-bg); }
        .mrt-city { inline-size: 100%; block-size: 100%; }
        .mrt-river { fill: none; stroke: var(--mrt-water); stroke-width: 22; stroke-linecap: round; }
        .mrt-block { fill: var(--mrt-block); }
        .mrt-ghost { fill: none; stroke: var(--mrt-line); stroke-width: 3; stroke-dasharray: 2 7; stroke-linecap: round; }
        .mrt-line { fill: none; stroke: var(--mrt-accent); stroke-width: 4.5; stroke-linecap: round; stroke-dasharray: .012 .03; }
        .mrt-wipe { fill: none; stroke: #fff; stroke-width: 18; stroke-dasharray: 1; stroke-dashoffset: 1;
                    animation: mrt-draw 1.8s cubic-bezier(.4, .1, .2, 1) .2s forwards; }
        @keyframes mrt-draw { to { stroke-dashoffset: 0; } }
        .mrt-stop { opacity: 0; scale: .5; transform-box: fill-box; transform-origin: center;
                    animation: mrt-stop-in .3s ease both; animation-delay: calc(.3s + var(--i) * .38s); }
        @keyframes mrt-stop-in { to { opacity: 1; scale: 1; } }
        .mrt-stop circle { fill: #fff; stroke: var(--mrt-accent); stroke-width: 3; }
        .mrt-stop text { fill: var(--mrt-accent); font: 700 9px sans-serif; text-anchor: middle; }
        .mrt-stop .mrt-name { font: 600 11px sans-serif; fill: var(--mrt-text); }
        .mrt-panel { display: grid; gap: .75rem; align-content: start; padding: 1rem;
                     border: 1px solid var(--mrt-line); border-radius: 1rem; color: var(--mrt-text); }
        .mrt-badges { margin: 0; color: var(--mrt-accent); font-weight: 700; font-size: .85rem; }
        .mrt-steps { display: grid; gap: .55rem; margin: 0; padding: 0; list-style: none; }
        .mrt-steps li { display: grid; grid-template-columns: 1.5rem 1fr; gap: .25rem .6rem; align-items: baseline; }
        .mrt-steps i { display: grid; place-items: center; aspect-ratio: 1; border-radius: 50%;
                       border: 2px solid var(--mrt-accent); font: 700 .7rem/1 sans-serif; font-style: normal; }
        .mrt-steps small { grid-column: 2; color: var(--mrt-line); }
        .mrt-swap { padding: .6rem 1rem; border: none; border-radius: 999px; background: var(--mrt-accent);
                    color: #fff; font: 600 .82rem/1 inherit; cursor: pointer; }
        @media (prefers-reduced-motion: reduce) {
          .mrt-wipe { stroke-dashoffset: 0; animation: none; }
          .mrt-stop { animation: none; opacity: 1; scale: 1; }
        }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- RoutePlanner.svelte — the dashed route draws itself; the swap replays it -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          interface Stop { n: number; x: number; y: number; name: string; seg: string }
          interface Route { name: string; eta: string; dist: string; d: string; stops: Stop[] }
          const routes: Record<'a' | 'b', Route> = {
            a: {
              name: 'Harbor Express', eta: '38 min', dist: '21.4 km',
              d: 'M70 330 C130 310 140 280 170 255 C205 227 262 208 300 180 C340 150 400 168 450 150 C500 132 545 105 580 80',
              stops: [
                { n: 1, x: 70, y: 330, name: 'Central Station', seg: 'origin' },
                { n: 2, x: 170, y: 255, name: 'Old Harbor', seg: '7 min · 4.1 km' },
                { n: 3, x: 300, y: 180, name: 'Museum Mile', seg: '9 min · 5.2 km' },
                { n: 4, x: 450, y: 150, name: 'Botanic Ring', seg: '11 min · 6.3 km' },
                { n: 5, x: 580, y: 80, name: 'Airport T3', seg: '11 min · 5.8 km' },
              ],
            },
            b: {
              name: 'Riverside Line', eta: '46 min', dist: '24.8 km',
              d: 'M70 330 C110 345 150 352 190 345 C240 336 285 315 330 295 C385 271 430 252 470 230 C520 203 555 135 580 80',
              stops: [
                { n: 1, x: 70, y: 330, name: 'Central Station', seg: 'origin' },
                { n: 2, x: 190, y: 345, name: 'Kessel Bridge', seg: '8 min · 4.4 km' },
                { n: 3, x: 330, y: 295, name: 'Signal Wharf', seg: '10 min · 5.6 km' },
                { n: 4, x: 470, y: 230, name: 'Freight Bypass', seg: '13 min · 6.7 km' },
                { n: 5, x: 580, y: 80, name: 'Airport T3', seg: '15 min · 8.1 km' },
              ],
            },
          };
          let alt = $state<'a' | 'b'>('a');
          const route = $derived(routes[alt]);
          const other = $derived(routes[alt === 'a' ? 'b' : 'a']);
          const swap = () => { alt = alt === 'a' ? 'b' : 'a'; };
        </script>

        <div class="mrt-root">
          <div class="mrt-layer" dir="ltr">
            <svg class="mrt-city" viewBox="0 0 640 400" role="img" aria-label={`Route of the ${route.name}`}>
              <path class="mrt-river" d="M-10 366 C120 350 250 330 360 314 S540 268 650 224" />
              <rect class="mrt-block" x="60" y="60" width="110" height="70" rx="10" />
              <rect class="mrt-block" x="240" y="46" width="96" height="60" rx="10" />
              <rect class="mrt-block" x="330" y="220" width="110" height="60" rx="10" />
              {#each Object.values(routes) as r (r.name)}
                <path class="mrt-ghost" d={r.d} />
              {/each}
              <!-- Keying the group remounts it on swap — the CSS draw replays. -->
              {#key alt}
                <g>
                  <defs>
                    <mask id="mrt-wipe" maskUnits="userSpaceOnUse" x="0" y="0" width="640" height="400">
                      <path class="mrt-wipe" pathLength="1" d={route.d} />
                    </mask>
                  </defs>
                  <path class="mrt-line" pathLength="1" mask="url(#mrt-wipe)" d={route.d} />
                  {#each route.stops as s (s.n)}
                    <g class="mrt-stop" style={`--i: ${s.n - 1}`}>
                      <circle cx={s.x} cy={s.y} r={s.n === 1 || s.n === 5 ? 8 : 7} />
                      <text x={s.x} y={s.y + 3.5}>{s.n}</text>
                      <text class="mrt-name" x={s.x} y={s.y - 14}>{s.name}</text>
                    </g>
                  {/each}
                </g>
              {/key}
            </svg>
          </div>
          <aside class="mrt-panel">
            <h4 style="margin: 0">{route.name}</h4>
            <p class="mrt-badges">{route.eta} · {route.dist}</p>
            <ol class="mrt-steps">
              {#each route.stops as s (s.n)}
                <li><i>{s.n}</i><b>{s.name}</b><small>{s.seg}</small></li>
              {/each}
            </ol>
            <button type="button" class="mrt-swap" onclick={swap}>Other route: {other.name}</button>
          </aside>
        </div>

        <style>
        .mrt-root { --mrt-bg: #f6f7f9; --mrt-block: #e8eaef; --mrt-water: #cfe3f7; --mrt-line: #d4d8e0;
                    --mrt-accent: #e11d48; --mrt-text: #1c1917; font-family: 'Inter', ui-sans-serif, sans-serif;
                    display: grid; gap: 1rem; grid-template-columns: minmax(0, 1.5fr) minmax(14rem, 1fr); }
        .mrt-layer { position: relative; aspect-ratio: 16 / 10; overflow: clip;
                     border: 1px solid var(--mrt-line); border-radius: 1rem; background: var(--mrt-bg); }
        .mrt-city { inline-size: 100%; block-size: 100%; }
        .mrt-river { fill: none; stroke: var(--mrt-water); stroke-width: 22; stroke-linecap: round; }
        .mrt-block { fill: var(--mrt-block); }
        .mrt-ghost { fill: none; stroke: var(--mrt-line); stroke-width: 3; stroke-dasharray: 2 7; stroke-linecap: round; }
        .mrt-line { fill: none; stroke: var(--mrt-accent); stroke-width: 4.5; stroke-linecap: round; stroke-dasharray: .012 .03; }
        .mrt-wipe { fill: none; stroke: #fff; stroke-width: 18; stroke-dasharray: 1; stroke-dashoffset: 1;
                    animation: mrt-draw 1.8s cubic-bezier(.4, .1, .2, 1) .2s forwards; }
        @keyframes mrt-draw { to { stroke-dashoffset: 0; } }
        .mrt-stop { opacity: 0; scale: .5; transform-box: fill-box; transform-origin: center;
                    animation: mrt-stop-in .3s ease both; animation-delay: calc(.3s + var(--i) * .38s); }
        @keyframes mrt-stop-in { to { opacity: 1; scale: 1; } }
        .mrt-stop circle { fill: #fff; stroke: var(--mrt-accent); stroke-width: 3; }
        .mrt-stop text { fill: var(--mrt-accent); font: 700 9px sans-serif; text-anchor: middle; }
        .mrt-stop .mrt-name { font: 600 11px sans-serif; fill: var(--mrt-text); }
        .mrt-panel { display: grid; gap: .75rem; align-content: start; padding: 1rem;
                     border: 1px solid var(--mrt-line); border-radius: 1rem; color: var(--mrt-text); }
        .mrt-badges { margin: 0; color: var(--mrt-accent); font-weight: 700; font-size: .85rem; }
        .mrt-steps { display: grid; gap: .55rem; margin: 0; padding: 0; list-style: none; }
        .mrt-steps li { display: grid; grid-template-columns: 1.5rem 1fr; gap: .25rem .6rem; align-items: baseline; }
        .mrt-steps i { display: grid; place-items: center; aspect-ratio: 1; border-radius: 50%;
                       border: 2px solid var(--mrt-accent); font: 700 .7rem/1 sans-serif; font-style: normal; }
        .mrt-steps small { grid-column: 2; color: var(--mrt-line); }
        .mrt-swap { padding: .6rem 1rem; border: none; border-radius: 999px; background: var(--mrt-accent);
                    color: #fff; font: 600 .82rem/1 inherit; cursor: pointer; }
        @media (prefers-reduced-motion: reduce) {
          .mrt-wipe { stroke-dashoffset: 0; animation: none; }
          .mrt-stop { animation: none; opacity: 1; scale: 1; }
        }
        </style>
        SVELTE,
        ],
    ],

    'store-locator' => [
        'title' => ['fa' => 'یابندهٔ فروشگاه', 'en' => 'Store locator'],
        'icon' => 'search',
        'oneLiner' => [
            'fa' => 'یک برند قهوهٔ جهانی با ۷ شعبه در فرانکفورت، دوبلین و سنگاپور: جست‌وجوی زنده و فیلتر شهر، انتخاب هر آیتم مارکرش را با پرشی نرم به مرکز نقشه می‌آورد و پاپ‌اورش را باز می‌کند، شمار نتایج همیشه جلو است و جست‌وجوی بی‌نتیجه حالت خالی می‌آورد — همه‌چیز SVG و بدون تایل.',
            'en' => 'A global coffee brand with seven branches across Frankfurt, Dublin and Singapore: live search and a city filter, picking an item glides its marker to the centre of the map and opens its popover, the result count always leads, and a dead search falls into the empty state — all SVG, no tiles.',
        ],
        'js' => true,
        'docs' => 'https://mapcn.dev',
        'props' => [
            ['name' => 'search', 'type' => 'live', 'default' => "''", 'note' => [
                'fa' => 'جست‌وجو روی نام و خیابان اعمال می‌شود؛ تا وقتی خالی است، فهرست همان شهر فعال را نشان می‌دهد.',
                'en' => 'The search matches name and street; while empty the list simply shows the active city’s branches.',
            ]],
            ['name' => 'city-filter', 'type' => 'chips', 'default' => "'3 cities'", 'note' => [
                'fa' => 'چیپ شهر هم فیلتر فهرست است هم نقشه را به همان شهر می‌برد؛ انتخاب نتیجه‌ای از شهر دیگر، نقشه را هم عوض می‌کند.',
                'en' => 'A city chip filters the list and moves the map to that city; picking a result from another city moves the map too.',
            ]],
            ['name' => 'pan', 'type' => 'translate', 'default' => "'650ms'", 'note' => [
                'fa' => 'جهانِ نقشه ۵۵٪ بزرگ‌تر از قاب است؛ انتخاب فروشگاه یعنی یک translate نرم تا مارکرش دقیقاً وسط بنشیند.',
                'en' => 'The map’s world runs 55% larger than its frame; picking a store translates it smoothly until the marker sits dead centre.',
            ]],
            ['name' => 'count', 'type' => 'status', 'default' => "'live'", 'note' => [
                'fa' => 'شمار نتایج با aria-live بالای فهرست به‌روز می‌شود؛ صفر یعنی کارت حالت خالی با دکمهٔ پاک‌کردن جست‌وجو.',
                'en' => 'The count leads the list with aria-live; zero brings the empty card with its clear-search button.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="msl-root" x-data="{
                city: 'frankfurt', query: '', active: null,
                stores: [
                    { id: 'fw1', city: 'frankfurt', name: 'Hauptwache Corner', street: 'Kaiserstraße ۱۲', x: 36, y: 36 },
                    { id: 'fw3', city: 'frankfurt', name: 'River Side', street: 'Schaumainkai ۴۱', x: 34, y: 66 },
                    { id: 'db1', city: 'dublin', name: 'Grafton Arch', street: 'Grafton Street ۲۲', x: 42, y: 34 },
                ],
                fmt(n) { return String(n).replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]); },
                get cityStores() { return this.stores.filter((s) => s.city === this.city); },
                get results() {
                    const q = this.query.trim().toLowerCase();
                    return q ? this.stores.filter((s) => (s.name + ' ' + s.street).toLowerCase().includes(q))
                             : this.cityStores;
                },
                get activeStore() { return this.stores.find((s) => s.id === this.active) || null; },
                get pan() {
                    const s = this.activeStore;
                    return s ? 'translate: ' + (32.26 - s.x).toFixed(2) + '% ' + (32.26 - s.y).toFixed(2) + '%'
                             : 'translate: 0% 0%';
                },
                pick(s) { this.city = s.city; this.active = s.id; },
            }" x-on:keydown.escape.window="active = null">
            <div class="msl-side">
                <input class="msl-search" type="search" x-model="query" placeholder="نام شعبه یا خیابان…">
                <p class="msl-count" role="status"><span x-text="fmt(results.length)"></span> فروشگاه پیدا شد</p>
                <ul class="msl-list">
                    <template x-for="s in results" :key="s.id">
                        <li>
                            <button type="button" class="msl-row" :class="active === s.id && 'is-active'" x-on:click="pick(s)">
                                <b x-text="s.name"></b>
                                <small x-text="s.street"></small>
                            </button>
                        </li>
                    </template>
                </ul>
                <div class="msl-empty" x-cloak x-show="results.length === 0">
                    <p>فروشگاهی پیدا نشد — جست‌وجوی دیگری امتحان کنید.</p>
                </div>
            </div>
            <div class="msl-stage" dir="ltr">
                <div class="msl-world" :style="pan">
                    <svg class="msl-city" viewBox="0 0 640 400" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
                        <rect class="msl-base" width="640" height="400"/>
                        <path class="msl-river" d="M-10 300 C140 280 260 330 400 306 S560 258 650 280"/>
                        <rect class="msl-block" x="150" y="80" width="130" height="80" rx="10"/>
                        <rect class="msl-block" x="330" y="140" width="150" height="70" rx="10"/>
                        <rect class="msl-block" x="180" y="210" width="120" height="76" rx="10"/>
                    </svg>
                    <template x-for="s in cityStores" :key="s.id">
                        <button type="button" class="msl-pin" :class="active === s.id && 'is-active'"
                                :style="`inset-inline-start: ${s.x}%; inset-block-start: ${s.y}%`" x-on:click="pick(s)">
                            <span class="msl-bead"></span>
                            <span class="msl-pinlabel" x-text="s.name"></span>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        <style>
        .msl-root { --msl-bg: #f6f7f9; --msl-block: #e8eaef; --msl-water: #cfe3f7; --msl-line: #d4d8e0;
                    --msl-accent: #0f766e; --msl-text: #1c1917; --msl-card: #fff;
                    display: grid; gap: 1rem; grid-template-columns: minmax(14rem, 1fr) minmax(0, 1.45fr);
                    font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif; }
        .msl-search { inline-size: 100%; padding: .6rem .9rem; border: 1px solid var(--msl-line);
                      border-radius: .8rem; font: 400 .85rem/1.4 inherit; }
        .msl-count { margin: 0; font-size: .74rem; color: var(--msl-accent); font-weight: 700; }
        .msl-list { display: grid; gap: .5rem; margin: 0; padding: 0; list-style: none; }
        .msl-row { display: grid; gap: .15rem; inline-size: 100%; padding: .65rem .8rem; text-align: start;
                   border: 1px solid var(--msl-line); border-radius: .8rem; background: var(--msl-card);
                   color: var(--msl-text); cursor: pointer; }
        .msl-row.is-active { border-color: var(--msl-accent); box-shadow: inset 2px 0 0 var(--msl-accent); }
        .msl-empty { padding: 1rem; border: 1px dashed var(--msl-line); border-radius: .8rem;
                     color: var(--msl-text); text-align: center; }
        .msl-stage { position: relative; aspect-ratio: 16 / 10; overflow: clip;
                     border: 1px solid var(--msl-line); border-radius: 1rem; background: var(--msl-bg); }
        .msl-world { position: absolute; inset: 0; inline-size: 155%; block-size: 155%;
                     transition: translate .65s cubic-bezier(.3, .7, .2, 1); }
        .msl-city { inline-size: 100%; block-size: 100%; }
        .msl-base { fill: var(--msl-bg); }
        .msl-river { fill: none; stroke: var(--msl-water); stroke-width: 24; stroke-linecap: round; }
        .msl-block { fill: var(--msl-block); }
        .msl-pin { position: absolute; translate: -50% -50%; inline-size: 1.55rem; aspect-ratio: 1;
                   border: none; padding: 0; background: transparent; cursor: pointer; z-index: 2; }
        .msl-bead { display: block; inline-size: 100%; aspect-ratio: 1; border-radius: 50%;
                    border: 3px solid #fff; background: var(--msl-accent);
                    box-shadow: 0 2px 8px rgba(15, 23, 42, .28); transition: scale .2s; }
        .msl-pin:hover .msl-bead, .msl-pin.is-active .msl-bead { scale: 1.25; }
        .msl-pinlabel { position: absolute; inset-block-end: calc(100% + .4rem); inset-inline-start: 50%;
                        translate: -50%; white-space: nowrap; padding: .18rem .55rem; border-radius: .55rem;
                        background: #fff; color: var(--msl-text); font-size: .68rem; font-weight: 600; }
        @media (prefers-reduced-motion: reduce) { .msl-world { transition: none; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="msl-root" x-data="{
        city: 'frankfurt', query: '', active: null,
        stores: [
        { id: 'fw1', city: 'frankfurt', name: 'Hauptwache Corner', street: 'Kaiserstraße 12', x: 36, y: 36 },
        { id: 'fw3', city: 'frankfurt', name: 'River Side', street: 'Schaumainkai 41', x: 34, y: 66 },
        { id: 'db1', city: 'dublin', name: 'Grafton Arch', street: 'Grafton Street 22', x: 42, y: 34 },
        ],
        get cityStores() { return this.stores.filter((s) => s.city === this.city); },
        get results() {
        const q = this.query.trim().toLowerCase();
        return q ? this.stores.filter((s) => (s.name + ' ' + s.street).toLowerCase().includes(q))
        : this.cityStores;
        },
        get activeStore() { return this.stores.find((s) => s.id === this.active) || null; },
        get pan() {
        const s = this.activeStore;
        return s ? 'translate: ' + (32.26 - s.x).toFixed(2) + '% ' + (32.26 - s.y).toFixed(2) + '%'
        : 'translate: 0% 0%';
        },
        pick(s) { this.city = s.city; this.active = s.id; },
        }" x-on:keydown.escape.window="active = null">
        <div class="msl-side">
        <input class="msl-search" type="search" x-model="query" placeholder="Branch or street…">
        <p class="msl-count" role="status"><span x-text="results.length"></span> stores found</p>
        <ul class="msl-list">
        <template x-for="s in results" :key="s.id">
        <li>
        <button type="button" class="msl-row" :class="active === s.id && 'is-active'" x-on:click="pick(s)">
        <b x-text="s.name"></b>
        <small x-text="s.street"></small>
        </button>
        </li>
        </template>
        </ul>
        <div class="msl-empty" x-cloak x-show="results.length === 0">
        <p>No stores matched — try another search.</p>
        </div>
        </div>
        <div class="msl-stage" dir="ltr">
        <div class="msl-world" :style="pan">
        <svg class="msl-city" viewBox="0 0 640 400" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
        <rect class="msl-base" width="640" height="400"/>
        <path class="msl-river" d="M-10 300 C140 280 260 330 400 306 S560 258 650 280"/>
        <rect class="msl-block" x="150" y="80" width="130" height="80" rx="10"/>
        <rect class="msl-block" x="330" y="140" width="150" height="70" rx="10"/>
        <rect class="msl-block" x="180" y="210" width="120" height="76" rx="10"/>
        </svg>
        <template x-for="s in cityStores" :key="s.id">
        <button type="button" class="msl-pin" :class="active === s.id && 'is-active'"
        :style="`inset-inline-start: ${s.x}%; inset-block-start: ${s.y}%`" x-on:click="pick(s)">
        <span class="msl-bead"></span>
        <span class="msl-pinlabel" x-text="s.name"></span>
        </button>
        </template>
        </div>
        </div>
        </div>

        <style>
        .msl-root { --msl-bg: #f6f7f9; --msl-block: #e8eaef; --msl-water: #cfe3f7; --msl-line: #d4d8e0;
        --msl-accent: #0f766e; --msl-text: #1c1917; --msl-card: #fff;
        display: grid; gap: 1rem; grid-template-columns: minmax(14rem, 1fr) minmax(0, 1.45fr);
        font-family: 'Inter', 'Vazirmatn', ui-sans-serif, sans-serif; }
        .msl-search { inline-size: 100%; padding: .6rem .9rem; border: 1px solid var(--msl-line);
        border-radius: .8rem; font: 400 .85rem/1.4 inherit; }
        .msl-count { margin: 0; font-size: .74rem; color: var(--msl-accent); font-weight: 700; }
        .msl-list { display: grid; gap: .5rem; margin: 0; padding: 0; list-style: none; }
        .msl-row { display: grid; gap: .15rem; inline-size: 100%; padding: .65rem .8rem; text-align: start;
        border: 1px solid var(--msl-line); border-radius: .8rem; background: var(--msl-card);
        color: var(--msl-text); cursor: pointer; }
        .msl-row.is-active { border-color: var(--msl-accent); box-shadow: inset 2px 0 0 var(--msl-accent); }
        .msl-empty { padding: 1rem; border: 1px dashed var(--msl-line); border-radius: .8rem;
        color: var(--msl-text); text-align: center; }
        .msl-stage { position: relative; aspect-ratio: 16 / 10; overflow: clip;
        border: 1px solid var(--msl-line); border-radius: 1rem; background: var(--msl-bg); }
        .msl-world { position: absolute; inset: 0; inline-size: 155%; block-size: 155%;
        transition: translate .65s cubic-bezier(.3, .7, .2, 1); }
        .msl-city { inline-size: 100%; block-size: 100%; }
        .msl-base { fill: var(--msl-bg); }
        .msl-river { fill: none; stroke: var(--msl-water); stroke-width: 24; stroke-linecap: round; }
        .msl-block { fill: var(--msl-block); }
        .msl-pin { position: absolute; translate: -50% -50%; inline-size: 1.55rem; aspect-ratio: 1;
        border: none; padding: 0; background: transparent; cursor: pointer; z-index: 2; }
        .msl-bead { display: block; inline-size: 100%; aspect-ratio: 1; border-radius: 50%;
        border: 3px solid #fff; background: var(--msl-accent);
        box-shadow: 0 2px 8px rgba(15, 23, 42, .28); transition: scale .2s; }
        .msl-pin:hover .msl-bead, .msl-pin.is-active .msl-bead { scale: 1.25; }
        .msl-pinlabel { position: absolute; inset-block-end: calc(100% + .4rem); inset-inline-start: 50%;
        translate: -50%; white-space: nowrap; padding: .18rem .55rem; border-radius: .55rem;
        background: #fff; color: var(--msl-text); font-size: .68rem; font-weight: 600; }
        @media (prefers-reduced-motion: reduce) { .msl-world { transition: none; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Stores.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import StoreLocator from '@/components/StoreLocator';

        export default function Stores() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <StoreLocator />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // StoreLocator.jsx — search, city chips, a map that pans to the pick
        import { useMemo, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const CITIES = [
          { id: 'frankfurt', label: 'Frankfurt', tint: '#f59e0b' },
          { id: 'dublin', label: 'Dublin', tint: '#34d399' },
        ];
        const STORES = [
          { id: 'fw1', city: 'frankfurt', name: 'Hauptwache Corner', street: 'Kaiserstraße 12', hours: '07:30 – 20:00', x: 36, y: 36 },
          { id: 'fw2', city: 'frankfurt', name: 'Zeil Windows', street: 'Zeil 106', hours: '09:00 – 21:00', x: 58, y: 55 },
          { id: 'db1', city: 'dublin', name: 'Grafton Arch', street: 'Grafton Street 22', hours: '08:00 – 20:00', x: 42, y: 34 },
          { id: 'db2', city: 'dublin', name: 'Docklands Ferry', street: 'North Wall Quay 8', hours: '07:00 – 18:30', x: 66, y: 60 },
        ];
        const WORLD = 1.55; // the world runs 55% larger than its frame

        export default function StoreLocator() {
          const [city, setCity] = useState('frankfurt');
          const [query, setQuery] = useState('');
          const [active, setActive] = useState(null);

          const cityStores = useMemo(() => STORES.filter((s) => s.city === city), [city]);
          const results = useMemo(() => {
            const q = query.trim().toLowerCase();
            return q ? STORES.filter((s) => (s.name + ' ' + s.street).toLowerCase().includes(q)) : cityStores;
          }, [query, cityStores]);
          const activeStore = STORES.find((s) => s.id === active) || null;
          const tint = (id) => CITIES.find((c) => c.id === id)?.tint;
          // translate % resolve against the world's own box — 50 / WORLD lands the marker dead centre.
          const pan = activeStore
            ? { x: (50 / WORLD - activeStore.x).toFixed(2), y: (50 / WORLD - activeStore.y).toFixed(2) }
            : { x: 0, y: 0 };
          const pick = (s) => { setCity(s.city); setActive(s.id); };

          return (
            <>
              <style>{`
                .msl-root { --msl-bg: #f6f7f9; --msl-block: #e8eaef; --msl-water: #cfe3f7; --msl-line: #d4d8e0;
                            --msl-accent: #0f766e; --msl-text: #1c1917; --msl-card: #fff; font-family: 'Inter', ui-sans-serif, sans-serif;
                            display: grid; gap: 1rem; grid-template-columns: minmax(14rem, 1fr) minmax(0, 1.45fr); }
                .msl-search { inline-size: 100%; padding: .6rem .9rem; border: 1px solid var(--msl-line);
                              border-radius: .8rem; font: 400 .85rem/1.4 inherit; }
                .msl-chips { display: flex; flex-wrap: wrap; gap: .4rem; }
                .msl-chip { padding: .3rem .75rem; border: 1px solid var(--msl-line); border-radius: 999px;
                            background: var(--msl-card); font: 600 .74rem/1 inherit; cursor: pointer; }
                .msl-chip[aria-pressed='true'] { border-color: var(--msl-accent); color: var(--msl-accent); }
                .msl-count { margin: 0; font-size: .74rem; color: var(--msl-accent); font-weight: 700; }
                .msl-list { display: grid; gap: .5rem; margin: 0; padding: 0; list-style: none; }
                .msl-row { display: grid; gap: .15rem; inline-size: 100%; padding: .65rem .8rem; text-align: start;
                           border: 1px solid var(--msl-line); border-radius: .8rem; background: var(--msl-card);
                           color: var(--msl-text); cursor: pointer; }
                .msl-row[aria-current='true'] { border-color: var(--msl-accent); box-shadow: inset 2px 0 0 var(--msl-accent); }
                .msl-empty { padding: 1rem; border: 1px dashed var(--msl-line); border-radius: .8rem;
                             color: var(--msl-text); text-align: center; }
                .msl-stage { position: relative; aspect-ratio: 16 / 10; overflow: clip;
                             border: 1px solid var(--msl-line); border-radius: 1rem; background: var(--msl-bg); }
                .msl-world { position: absolute; inset: 0; inline-size: 155%; block-size: 155%;
                             transition: translate .65s cubic-bezier(.3, .7, .2, 1); }
                .msl-city { inline-size: 100%; block-size: 100%; }
                .msl-base { fill: var(--msl-bg); }
                .msl-river { fill: none; stroke: var(--msl-water); stroke-width: 24; stroke-linecap: round; }
                .msl-block { fill: color-mix(in oklab, var(--msl-tint) 5%, var(--msl-block)); }
                .msl-pin { position: absolute; translate: -50% -50%; inline-size: 1.55rem; aspect-ratio: 1; border: none;
                           padding: 0; background: transparent; cursor: pointer; z-index: 2; }
                .msl-bead { display: block; inline-size: 100%; aspect-ratio: 1; border-radius: 50%; border: 3px solid #fff;
                            box-shadow: 0 2px 8px rgba(15, 23, 42, .28); transition: scale .2s; }
                .msl-pin:hover .msl-bead, .msl-pin[aria-pressed='true'] .msl-bead { scale: 1.25; }
                .msl-pop { position: absolute; translate: 1.2rem -50%; z-index: 3; inline-size: 9.5rem; display: grid; gap: .25rem;
                           padding: .6rem .7rem; border: 1px solid var(--msl-line); border-radius: .8rem; background: var(--msl-card);
                           color: var(--msl-text); box-shadow: 0 12px 30px rgba(15, 23, 42, .18); }
                @media (prefers-reduced-motion: reduce) { .msl-world { transition: none; } }
              `}</style>
              <div className="msl-root">
                <div className="msl-side">
                  <input className="msl-search" type="search" value={query}
                    onChange={(e) => setQuery(e.target.value)} placeholder="Branch or street…" aria-label="Search stores" />
                  <div className="msl-chips" role="group" aria-label="City filter">
                    {CITIES.map((c) => (
                      <button key={c.id} type="button" className="msl-chip" aria-pressed={city === c.id}
                        onClick={() => { setCity(c.id); setActive(null); }}>{c.label}</button>
                    ))}
                  </div>
                  <p className="msl-count" role="status">{results.length} stores found</p>
                  <ul className="msl-list">
                    {results.map((s) => (
                      <li key={s.id}>
                        <button type="button" className="msl-row" aria-current={active === s.id} onClick={() => pick(s)}>
                          <b>{s.name}</b>
                          <small>{s.street} · {s.hours}</small>
                        </button>
                      </li>
                    ))}
                  </ul>
                  {results.length === 0 && (
                    <div className="msl-empty">
                      <p>No stores matched — try another search.</p>
                      <button type="button" onClick={() => setQuery('')}>Clear search</button>
                    </div>
                  )}
                </div>
                <div className="msl-stage" dir="ltr" style={{ '--msl-tint': tint(city) }}>
                  <div className="msl-world" style={{ translate: `${pan.x}% ${pan.y}%` }}>
                    <svg className="msl-city" viewBox="0 0 640 400" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
                      <rect className="msl-base" width="640" height="400" />
                      <path className="msl-river" d="M-10 300 C140 280 260 330 400 306 S560 258 650 280" />
                      <rect className="msl-block" x="150" y="80" width="130" height="80" rx="10" />
                      <rect className="msl-block" x="330" y="140" width="150" height="70" rx="10" />
                      <rect className="msl-block" x="180" y="210" width="120" height="76" rx="10" />
                    </svg>
                    {cityStores.map((s) => (
                      <div key={s.id} style={{ position: 'absolute', inset: 0, pointerEvents: 'none' }}>
                        <button type="button" className="msl-pin" aria-pressed={active === s.id}
                          style={{ insetInlineStart: s.x + '%', insetBlockStart: s.y + '%', pointerEvents: 'auto' }}
                          onClick={() => pick(s)}>
                          <span className="msl-bead" style={{ background: tint(s.city) }} aria-hidden="true" />
                        </button>
                        {active === s.id && (
                          <div className="msl-pop" role="dialog" aria-label={s.name}
                            style={{ insetInlineStart: s.x + '%', insetBlockStart: s.y + '%', pointerEvents: 'auto' }}>
                            <b>{s.name}</b>
                            <small>{s.street}</small>
                            <small>{s.hours}</small>
                          </div>
                        )}
                      </div>
                    ))}
                  </div>
                </div>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- StoreLocator.vue — search, city chips, a map that pans to the pick -->
        <script setup lang="ts">
        import { computed, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const cities = [
          { id: 'frankfurt', label: 'Frankfurt', tint: '#f59e0b' },
          { id: 'dublin', label: 'Dublin', tint: '#34d399' },
        ];
        const stores = [
          { id: 'fw1', city: 'frankfurt', name: 'Hauptwache Corner', street: 'Kaiserstraße 12', hours: '07:30 – 20:00', x: 36, y: 36 },
          { id: 'fw2', city: 'frankfurt', name: 'Zeil Windows', street: 'Zeil 106', hours: '09:00 – 21:00', x: 58, y: 55 },
          { id: 'db1', city: 'dublin', name: 'Grafton Arch', street: 'Grafton Street 22', hours: '08:00 – 20:00', x: 42, y: 34 },
          { id: 'db2', city: 'dublin', name: 'Docklands Ferry', street: 'North Wall Quay 8', hours: '07:00 – 18:30', x: 66, y: 60 },
        ];
        const WORLD = 1.55;
        const city = ref('frankfurt');
        const query = ref('');
        const active = ref<string | null>(null);

        const cityStores = computed(() => stores.filter((s) => s.city === city.value));
        const results = computed(() => {
          const q = query.value.trim().toLowerCase();
          return q ? stores.filter((s) => (s.name + ' ' + s.street).toLowerCase().includes(q)) : cityStores.value;
        });
        const activeStore = computed(() => stores.find((s) => s.id === active.value) || null);
        // translate % resolve against the world's own box — 50 / WORLD lands the marker dead centre.
        const pan = computed(() => activeStore.value
          ? `${(50 / WORLD - activeStore.value.x).toFixed(2)}% ${(50 / WORLD - activeStore.value.y).toFixed(2)}%`
          : '0% 0%');
        const tintOf = (id: string) => cities.find((c) => c.id === id)?.tint ?? '#0f766e';
        const pick = (s: typeof stores[number]) => { city.value = s.city; active.value = s.id; };
        </script>

        <template>
          <div class="msl-root">
            <div class="msl-side">
              <input class="msl-search" type="search" v-model="query" placeholder="Branch or street…" aria-label="Search stores" />
              <div class="msl-chips" role="group" aria-label="City filter">
                <button v-for="c in cities" :key="c.id" type="button" class="msl-chip" :aria-pressed="city === c.id"
                  @click="city = c.id; active = null">{{ c.label }}</button>
              </div>
              <p class="msl-count" role="status">{{ results.length }} stores found</p>
              <ul class="msl-list">
                <li v-for="s in results" :key="s.id">
                  <button type="button" class="msl-row" :aria-current="active === s.id" @click="pick(s)">
                    <b>{{ s.name }}</b>
                    <small>{{ s.street }} · {{ s.hours }}</small>
                  </button>
                </li>
              </ul>
              <div v-if="results.length === 0" class="msl-empty">
                <p>No stores matched — try another search.</p>
                <button type="button" @click="query = ''">Clear search</button>
              </div>
            </div>
            <div class="msl-stage" dir="ltr" :style="{ '--msl-tint': tintOf(city) }">
              <div class="msl-world" :style="{ translate: pan }">
                <svg class="msl-city" viewBox="0 0 640 400" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
                  <rect class="msl-base" width="640" height="400" />
                  <path class="msl-river" d="M-10 300 C140 280 260 330 400 306 S560 258 650 280" />
                  <rect class="msl-block" x="150" y="80" width="130" height="80" rx="10" />
                  <rect class="msl-block" x="330" y="140" width="150" height="70" rx="10" />
                  <rect class="msl-block" x="180" y="210" width="120" height="76" rx="10" />
                </svg>
                <template v-for="s in cityStores" :key="s.id">
                  <button type="button" class="msl-pin" :aria-pressed="active === s.id"
                    :style="{ insetInlineStart: s.x + '%', insetBlockStart: s.y + '%' }" @click="pick(s)">
                    <span class="msl-bead" :style="{ background: tintOf(s.city) }" aria-hidden="true"></span>
                  </button>
                  <div v-if="active === s.id" class="msl-pop" role="dialog" :aria-label="s.name"
                    :style="{ insetInlineStart: s.x + '%', insetBlockStart: s.y + '%' }">
                    <b>{{ s.name }}</b>
                    <small>{{ s.street }}</small>
                    <small>{{ s.hours }}</small>
                  </div>
                </template>
              </div>
            </div>
          </div>
        </template>

        <style scoped>
        .msl-root { --msl-bg: #f6f7f9; --msl-block: #e8eaef; --msl-water: #cfe3f7; --msl-line: #d4d8e0;
                    --msl-accent: #0f766e; --msl-text: #1c1917; --msl-card: #fff; font-family: 'Inter', ui-sans-serif, sans-serif;
                    display: grid; gap: 1rem; grid-template-columns: minmax(14rem, 1fr) minmax(0, 1.45fr); }
        .msl-search { inline-size: 100%; padding: .6rem .9rem; border: 1px solid var(--msl-line);
                      border-radius: .8rem; font: 400 .85rem/1.4 inherit; }
        .msl-chips { display: flex; flex-wrap: wrap; gap: .4rem; }
        .msl-chip { padding: .3rem .75rem; border: 1px solid var(--msl-line); border-radius: 999px;
                    background: var(--msl-card); font: 600 .74rem/1 inherit; cursor: pointer; }
        .msl-chip[aria-pressed='true'] { border-color: var(--msl-accent); color: var(--msl-accent); }
        .msl-count { margin: 0; font-size: .74rem; color: var(--msl-accent); font-weight: 700; }
        .msl-list { display: grid; gap: .5rem; margin: 0; padding: 0; list-style: none; }
        .msl-row { display: grid; gap: .15rem; inline-size: 100%; padding: .65rem .8rem; text-align: start;
                   border: 1px solid var(--msl-line); border-radius: .8rem; background: var(--msl-card);
                   color: var(--msl-text); cursor: pointer; }
        .msl-row[aria-current='true'] { border-color: var(--msl-accent); box-shadow: inset 2px 0 0 var(--msl-accent); }
        .msl-empty { padding: 1rem; border: 1px dashed var(--msl-line); border-radius: .8rem;
                     color: var(--msl-text); text-align: center; }
        .msl-stage { position: relative; aspect-ratio: 16 / 10; overflow: clip;
                     border: 1px solid var(--msl-line); border-radius: 1rem; background: var(--msl-bg); }
        .msl-world { position: absolute; inset: 0; inline-size: 155%; block-size: 155%;
                     transition: translate .65s cubic-bezier(.3, .7, .2, 1); }
        .msl-city { inline-size: 100%; block-size: 100%; }
        .msl-base { fill: var(--msl-bg); }
        .msl-river { fill: none; stroke: var(--msl-water); stroke-width: 24; stroke-linecap: round; }
        .msl-block { fill: color-mix(in oklab, var(--msl-tint) 5%, var(--msl-block)); }
        .msl-pin { position: absolute; translate: -50% -50%; inline-size: 1.55rem; aspect-ratio: 1; border: none;
                   padding: 0; background: transparent; cursor: pointer; z-index: 2; }
        .msl-bead { display: block; inline-size: 100%; aspect-ratio: 1; border-radius: 50%; border: 3px solid #fff;
                    box-shadow: 0 2px 8px rgba(15, 23, 42, .28); transition: scale .2s; }
        .msl-pin:hover .msl-bead, .msl-pin[aria-pressed='true'] .msl-bead { scale: 1.25; }
        .msl-pop { position: absolute; translate: 1.2rem -50%; z-index: 3; inline-size: 9.5rem; display: grid; gap: .25rem;
                   padding: .6rem .7rem; border: 1px solid var(--msl-line); border-radius: .8rem; background: var(--msl-card);
                   color: var(--msl-text); box-shadow: 0 12px 30px rgba(15, 23, 42, .18); }
        @media (prefers-reduced-motion: reduce) { .msl-world { transition: none; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- StoreLocator.svelte — search, city chips, a map that pans to the pick -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const cities = [
            { id: 'frankfurt', label: 'Frankfurt', tint: '#f59e0b' },
            { id: 'dublin', label: 'Dublin', tint: '#34d399' },
          ];
          const stores = [
            { id: 'fw1', city: 'frankfurt', name: 'Hauptwache Corner', street: 'Kaiserstraße 12', hours: '07:30 – 20:00', x: 36, y: 36 },
            { id: 'fw2', city: 'frankfurt', name: 'Zeil Windows', street: 'Zeil 106', hours: '09:00 – 21:00', x: 58, y: 55 },
            { id: 'db1', city: 'dublin', name: 'Grafton Arch', street: 'Grafton Street 22', hours: '08:00 – 20:00', x: 42, y: 34 },
            { id: 'db2', city: 'dublin', name: 'Docklands Ferry', street: 'North Wall Quay 8', hours: '07:00 – 18:30', x: 66, y: 60 },
          ];
          const WORLD = 1.55;
          let city = $state('frankfurt');
          let query = $state('');
          let active = $state<string | null>(null);

          const cityStores = $derived(stores.filter((s) => s.city === city));
          const results = $derived(
            query.trim()
              ? stores.filter((s) => (s.name + ' ' + s.street).toLowerCase().includes(query.trim().toLowerCase()))
              : cityStores,
          );
          const activeStore = $derived(stores.find((s) => s.id === active) ?? null);
          // translate % resolve against the world's own box — 50 / WORLD lands the marker dead centre.
          const pan = $derived(activeStore
            ? `${(50 / WORLD - activeStore.x).toFixed(2)}% ${(50 / WORLD - activeStore.y).toFixed(2)}%`
            : '0% 0%');
          const tintOf = (id: string) => cities.find((c) => c.id === id)?.tint ?? '#0f766e';
          const pick = (s: (typeof stores)[number]) => { city = s.city; active = s.id; };
        </script>

        <div class="msl-root">
          <div class="msl-side">
            <input class="msl-search" type="search" bind:value={query}
              oninput={(e) => (query = e.currentTarget.value)} placeholder="Branch or street…" aria-label="Search stores" />
            <div class="msl-chips" role="group" aria-label="City filter">
              {#each cities as c (c.id)}
                <button type="button" class="msl-chip" aria-pressed={city === c.id}
                  onclick={() => { city = c.id; active = null; }}>{c.label}</button>
              {/each}
            </div>
            <p class="msl-count" role="status">{results.length} stores found</p>
            <ul class="msl-list">
              {#each results as s (s.id)}
                <li>
                  <button type="button" class="msl-row" aria-current={active === s.id} onclick={() => pick(s)}>
                    <b>{s.name}</b>
                    <small>{s.street} · {s.hours}</small>
                  </button>
                </li>
              {/each}
            </ul>
            {#if results.length === 0}
              <div class="msl-empty">
                <p>No stores matched — try another search.</p>
                <button type="button" onclick={() => (query = '')}>Clear search</button>
              </div>
            {/if}
          </div>
          <div class="msl-stage" dir="ltr" style={`--msl-tint: ${tintOf(city)}`}>
            <div class="msl-world" style={`translate: ${pan}`}>
              <svg class="msl-city" viewBox="0 0 640 400" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
                <rect class="msl-base" width="640" height="400" />
                <path class="msl-river" d="M-10 300 C140 280 260 330 400 306 S560 258 650 280" />
                <rect class="msl-block" x="150" y="80" width="130" height="80" rx="10" />
                <rect class="msl-block" x="330" y="140" width="150" height="70" rx="10" />
                <rect class="msl-block" x="180" y="210" width="120" height="76" rx="10" />
              </svg>
              {#each cityStores as s (s.id)}
                <button type="button" class="msl-pin" aria-pressed={active === s.id}
                  style={`inset-inline-start: ${s.x}%; inset-block-start: ${s.y}%`} onclick={() => pick(s)}>
                  <span class="msl-bead" style={`background: ${tintOf(s.city)}`} aria-hidden="true"></span>
                </button>
                {#if active === s.id}
                  <div class="msl-pop" role="dialog" aria-label={s.name}
                    style={`inset-inline-start: ${s.x}%; inset-block-start: ${s.y}%`}>
                    <b>{s.name}</b>
                    <small>{s.street}</small>
                    <small>{s.hours}</small>
                  </div>
                {/if}
              {/each}
            </div>
          </div>
        </div>

        <style>
        .msl-root { --msl-bg: #f6f7f9; --msl-block: #e8eaef; --msl-water: #cfe3f7; --msl-line: #d4d8e0;
                    --msl-accent: #0f766e; --msl-text: #1c1917; --msl-card: #fff; font-family: 'Inter', ui-sans-serif, sans-serif;
                    display: grid; gap: 1rem; grid-template-columns: minmax(14rem, 1fr) minmax(0, 1.45fr); }
        .msl-search { inline-size: 100%; padding: .6rem .9rem; border: 1px solid var(--msl-line);
                      border-radius: .8rem; font: 400 .85rem/1.4 inherit; }
        .msl-chips { display: flex; flex-wrap: wrap; gap: .4rem; }
        .msl-chip { padding: .3rem .75rem; border: 1px solid var(--msl-line); border-radius: 999px;
                    background: var(--msl-card); font: 600 .74rem/1 inherit; cursor: pointer; }
        .msl-chip[aria-pressed='true'] { border-color: var(--msl-accent); color: var(--msl-accent); }
        .msl-count { margin: 0; font-size: .74rem; color: var(--msl-accent); font-weight: 700; }
        .msl-list { display: grid; gap: .5rem; margin: 0; padding: 0; list-style: none; }
        .msl-row { display: grid; gap: .15rem; inline-size: 100%; padding: .65rem .8rem; text-align: start;
                   border: 1px solid var(--msl-line); border-radius: .8rem; background: var(--msl-card);
                   color: var(--msl-text); cursor: pointer; }
        .msl-row[aria-current='true'] { border-color: var(--msl-accent); box-shadow: inset 2px 0 0 var(--msl-accent); }
        .msl-empty { padding: 1rem; border: 1px dashed var(--msl-line); border-radius: .8rem;
                     color: var(--msl-text); text-align: center; }
        .msl-stage { position: relative; aspect-ratio: 16 / 10; overflow: clip;
                     border: 1px solid var(--msl-line); border-radius: 1rem; background: var(--msl-bg); }
        .msl-world { position: absolute; inset: 0; inline-size: 155%; block-size: 155%;
                     transition: translate .65s cubic-bezier(.3, .7, .2, 1); }
        .msl-city { inline-size: 100%; block-size: 100%; }
        .msl-base { fill: var(--msl-bg); }
        .msl-river { fill: none; stroke: var(--msl-water); stroke-width: 24; stroke-linecap: round; }
        .msl-block { fill: color-mix(in oklab, var(--msl-tint) 5%, var(--msl-block)); }
        .msl-pin { position: absolute; translate: -50% -50%; inline-size: 1.55rem; aspect-ratio: 1; border: none;
                   padding: 0; background: transparent; cursor: pointer; z-index: 2; }
        .msl-bead { display: block; inline-size: 100%; aspect-ratio: 1; border-radius: 50%; border: 3px solid #fff;
                    box-shadow: 0 2px 8px rgba(15, 23, 42, .28); transition: scale .2s; }
        .msl-pin:hover .msl-bead, .msl-pin[aria-pressed='true'] .msl-bead { scale: 1.25; }
        .msl-pop { position: absolute; translate: 1.2rem -50%; z-index: 3; inline-size: 9.5rem; display: grid; gap: .25rem;
                   padding: .6rem .7rem; border: 1px solid var(--msl-line); border-radius: .8rem; background: var(--msl-card);
                   color: var(--msl-text); box-shadow: 0 12px 30px rgba(15, 23, 42, .18); }
        @media (prefers-reduced-motion: reduce) { .msl-world { transition: none; } }
        </style>
        SVELTE,
        ],
    ],
];
