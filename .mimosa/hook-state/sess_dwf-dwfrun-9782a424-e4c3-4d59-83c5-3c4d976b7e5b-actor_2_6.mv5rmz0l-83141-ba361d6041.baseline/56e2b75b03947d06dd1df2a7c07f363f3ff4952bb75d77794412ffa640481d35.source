<?php

/**
 * Demo manifest of the "text-fx" group — text effects as pure CSS with a
 * pinch of Alpine micro-state: a gradient that rivers through the letters,
 * Latin letters leaping under the cursor (words for Persian so the joining
 * never breaks), outline titles filling from the bottom, 3D-extruded
 * headlines that tilt with the pointer, and display type masking animated
 * media patterns. Scenarios live at
 * resources/views/demos/components/text-fx/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'جادوی متن', 'en' => 'Text FX'],

    'gradient-flow' => [
        'title' => ['fa' => 'متن گرادیانی جاری', 'en' => 'Flowing gradient text'],
        'icon' => 'sparkles',
        'oneLiner' => [
            'fa' => 'تیتری که رنگ درون حروفش رودخانه می‌شود: گرادیان متقارن با background-clip: text زیر جوهر جاری است، سرعت جریان را با اسلایدر نرم می‌کنید و پالت را با یک سواچ برمی‌گردانید — نسخهٔ دوخطی و زیرتیتر هم‌خانواده هم کنارش ایستاده‌اند.',
            'en' => 'A headline whose colour rivers through the letters: a symmetric gradient sits under background-clip: text ink, a slider bends the flow speed and one swatch flips the palette — with the two-line and subtitle siblings standing right beside it.',
        ],
        'js' => true,
        'docs' => 'https://text-effects.colorion.co',
        'props' => [
            ['name' => 'background-clip', 'type' => 'technique', 'default' => "'text'", 'note' => [
                'fa' => 'گرادیان روی خود متن بریده می‌شود (با پیشوند -webkit-) و color شفاف می‌شود؛ پشتیبانی از همهٔ مرورگرهای امروزی.',
                'en' => 'The gradient is clipped onto the glyphs themselves (with the -webkit- prefix) while color turns transparent; supported by every modern browser.',
            ]],
            ['name' => '--tgf-dur', 'type' => 'time', 'default' => "'8s'", 'note' => [
                'fa' => 'طول یک دور کامل جریان؛ اسلایدر ۰٫۵ تا ۳ برابر سرعت این عدد را تقسیم می‌کند (۴ ثانیه تا ۱۶ ثانیه).',
                'en' => 'One full lap of the flow; the 0.5–3× speed slider divides it (4s down to 16s).',
            ]],
            ['name' => 'palette', 'type' => 'attribute', 'default' => "'aurora'", 'note' => [
                'fa' => 'چهار پالت آماده — شفق، غروب، اقیانوس، نئون — که با data-pal روی ریشهٔ دمو عوض می‌شوند و سه رنگشان به گرادیان می‌ریزد.',
                'en' => 'Four presets — aurora, sunset, ocean, neon — swapped through data-pal on the root, feeding their three colours into the gradient.',
            ]],
            ['name' => 'seamless loop', 'type' => 'technique', 'default' => "'200%'", 'note' => [
                'fa' => 'گرادیان متقارن است (رنگ‌ها از آخر به اول برمی‌گردند) و پس‌زمینه ۲۰۰٪ عرض دارد، پس شیفت -۲۰۰٪ دقیقاً یک کاشی است و حلقه بی‌درز می‌چرخد.',
                'en' => 'The gradient is symmetric (stops mirror back to the start) over a 200%-wide background, so the -200% shift is exactly one tile and the loop is seamless.',
            ]],
            ['name' => 'reduced-motion', 'type' => 'media', 'default' => "'static'", 'note' => [
                'fa' => 'با prefers-reduced-motion انیمیشن خاموش می‌شود و گرادیان به‌صورت ثابت و کامل دیده می‌شود.',
                'en' => 'Under prefers-reduced-motion the animation stops and the gradient rests, fully visible and static.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="tgf" x-data="{ speed: 1, pal: 'aurora' }" x-bind:data-pal="pal"
             :style="`--tgf-dur: ${(8 / speed).toFixed(2)}s`">
            <p class="tgf-kicker">مریدین پی · انتشار عمومی</p>
            <h2 class="tgf-flow">پرداخت‌هایی که جاری‌اند</h2>
            <p class="tgf-flow tgf-sub">یک داشبورد · فرانکفورت، دوبلین، سنگاپور</p>

            <label class="tgf-ctl">
                <span>سرعت جریان</span>
                <input type="range" min="0.5" max="3" step="0.25" x-model.number="speed">
            </label>
            <div class="tgf-swatches" role="group" aria-label="پالت رنگ">
                <button type="button" class="tgf-sw" data-p="aurora" x-on:click="pal = 'aurora'">شفق</button>
                <button type="button" class="tgf-sw" data-p="sunset" x-on:click="pal = 'sunset'">غروب</button>
                <button type="button" class="tgf-sw" data-p="neon" x-on:click="pal = 'neon'">نئون</button>
            </div>
        </div>

        <style>
        .tgf { display: grid; gap: .9rem; justify-items: center; text-align: center; }
        .tgf[data-pal='aurora'] { --tgf-c1: #22d3ee; --tgf-c2: #a78bfa; --tgf-c3: #f472b6; }
        .tgf[data-pal='sunset'] { --tgf-c1: #fbbf24; --tgf-c2: #fb7185; --tgf-c3: #e879f9; }
        .tgf[data-pal='neon']   { --tgf-c1: #4ade80; --tgf-c2: #22d3ee; --tgf-c3: #818cf8; }
        .tgf-flow {
            margin: 0; font-weight: 800; font-size: clamp(2rem, 7vw, 3.4rem); line-height: 1.15;
            background: linear-gradient(100deg, var(--tgf-c1), var(--tgf-c2), var(--tgf-c3), var(--tgf-c2), var(--tgf-c1));
            background-size: 200% auto;
            -webkit-background-clip: text; background-clip: text; color: transparent;
            animation: tgf-move var(--tgf-dur, 8s) linear infinite;
        }
        .tgf-sub { font-size: .95rem; }
        @keyframes tgf-move { to { background-position: -200% center; } }
        @media (prefers-reduced-motion: reduce) { .tgf-flow { animation: none; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="tgf" x-data="{ speed: 1, pal: 'aurora' }" x-bind:data-pal="pal"
             :style="`--tgf-dur: ${(8 / speed).toFixed(2)}s`">
            <p class="tgf-kicker">Meridian Pay · general availability</p>
            <h2 class="tgf-flow">Payments that flow</h2>
            <p class="tgf-flow tgf-sub">One dashboard · Frankfurt, Dublin, Singapore</p>

            <label class="tgf-ctl">
                <span>Flow speed</span>
                <input type="range" min="0.5" max="3" step="0.25" x-model.number="speed">
            </label>
            <div class="tgf-swatches" role="group" aria-label="Colour palette">
                <button type="button" class="tgf-sw" data-p="aurora" x-on:click="pal = 'aurora'">Aurora</button>
                <button type="button" class="tgf-sw" data-p="sunset" x-on:click="pal = 'sunset'">Sunset</button>
                <button type="button" class="tgf-sw" data-p="neon" x-on:click="pal = 'neon'">Neon</button>
            </div>
        </div>

        <style>
        .tgf { display: grid; gap: .9rem; justify-items: center; text-align: center; }
        .tgf[data-pal='aurora'] { --tgf-c1: #22d3ee; --tgf-c2: #a78bfa; --tgf-c3: #f472b6; }
        .tgf[data-pal='sunset'] { --tgf-c1: #fbbf24; --tgf-c2: #fb7185; --tgf-c3: #e879f9; }
        .tgf[data-pal='neon']   { --tgf-c1: #4ade80; --tgf-c2: #22d3ee; --tgf-c3: #818cf8; }
        .tgf-flow {
            margin: 0; font-weight: 800; font-size: clamp(2rem, 7vw, 3.4rem); line-height: 1.15;
            background: linear-gradient(100deg, var(--tgf-c1), var(--tgf-c2), var(--tgf-c3), var(--tgf-c2), var(--tgf-c1));
            background-size: 200% auto;
            -webkit-background-clip: text; background-clip: text; color: transparent;
            animation: tgf-move var(--tgf-dur, 8s) linear infinite;
        }
        .tgf-sub { font-size: .95rem; }
        @keyframes tgf-move { to { background-position: -200% center; } }
        @media (prefers-reduced-motion: reduce) { .tgf-flow { animation: none; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Launch.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import GradientFlow from '@/components/GradientFlow';

        export default function Launch() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <GradientFlow />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // GradientFlow.jsx — a gradient that rivers through the letters
        import { useState } from 'react';
        import '@nabuxai/ui-core/css';

        const PALETTES = {
          aurora: ['#22d3ee', '#a78bfa', '#f472b6'],
          sunset: ['#fbbf24', '#fb7185', '#e879f9'],
          neon: ['#4ade80', '#22d3ee', '#818cf8'],
        };

        export default function GradientFlow() {
          const [speed, setSpeed] = useState(1);
          const [pal, setPal] = useState('aurora');
          const [c1, c2, c3] = PALETTES[pal];

          return (
            <>
              <style>{`
                .tgf { display: grid; gap: .9rem; justify-items: center; text-align: center; }
                .tgf-flow {
                  margin: 0; font-weight: 800; font-size: clamp(2rem, 7vw, 3.4rem); line-height: 1.15;
                  background: linear-gradient(100deg, ${c1}, ${c2}, ${c3}, ${c2}, ${c1});
                  background-size: 200% auto;
                  -webkit-background-clip: text; background-clip: text; color: transparent;
                  animation: tgf-move ${(8 / speed).toFixed(2)}s linear infinite;
                }
                .tgf-sub { font-size: .95rem; }
                .tgf-ctl { display: inline-flex; gap: .6rem; align-items: center; font-size: .85rem; }
                .tgf-sw { padding: .35rem .9rem; border: 1px solid #e7e5e4; border-radius: 999px;
                          background: transparent; cursor: pointer; font-size: .8rem; }
                .tgf-sw[aria-pressed='true'] { border-color: ${c2}; color: ${c2}; }
                @keyframes tgf-move { to { background-position: -200% center; } }
                @media (prefers-reduced-motion: reduce) { .tgf-flow { animation: none; } }
              `}</style>
              <div className="tgf">
                <p className="tgf-kicker">Meridian Pay · general availability</p>
                <h2 className="tgf-flow">Payments that flow</h2>
                <p className="tgf-flow tgf-sub">One dashboard · Frankfurt, Dublin, Singapore</p>
                <label className="tgf-ctl">
                  <span>Flow speed</span>
                  <input type="range" min="0.5" max="3" step="0.25" value={speed}
                         onChange={(e) => setSpeed(Number(e.target.value))} />
                </label>
                <div className="tgf-swatches" role="group" aria-label="Colour palette">
                  {Object.keys(PALETTES).map((p) => (
                    <button type="button" key={p} className="tgf-sw" aria-pressed={pal === p}
                            onClick={() => setPal(p)}>{p}</button>
                  ))}
                </div>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- GradientFlow.vue — a gradient that rivers through the letters -->
        <script setup lang="ts">
        import { computed, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const PALETTES: Record<string, string[]> = {
          aurora: ['#22d3ee', '#a78bfa', '#f472b6'],
          sunset: ['#fbbf24', '#fb7185', '#e879f9'],
          neon: ['#4ade80', '#22d3ee', '#818cf8'],
        };
        const speed = ref(1);
        const pal = ref('aurora');
        const [c1, c2, c3] = computed(() => PALETTES[pal.value]).value;
        const dur = computed(() => `${(8 / speed.value).toFixed(2)}s`);
        const gradient = computed(
          () => `linear-gradient(100deg, ${c1}, ${c2}, ${c3}, ${c2}, ${c1})`,
        );
        </script>

        <template>
          <div class="tgf">
            <p class="tgf-kicker">Meridian Pay · general availability</p>
            <h2 class="tgf-flow" :style="{ background: gradient, animationDuration: dur }">Payments that flow</h2>
            <p class="tgf-flow tgf-sub" :style="{ background: gradient, animationDuration: dur }">
              One dashboard · Frankfurt, Dublin, Singapore
            </p>
            <label class="tgf-ctl">
              <span>Flow speed</span>
              <input v-model.number="speed" type="range" min="0.5" max="3" step="0.25" />
            </label>
            <div class="tgf-swatches" role="group" aria-label="Colour palette">
              <button v-for="(colors, name) in PALETTES" :key="name" type="button" class="tgf-sw"
                      :aria-pressed="pal === name" @click="pal = name">{{ name }}</button>
            </div>
          </div>
        </template>

        <style scoped>
        .tgf { display: grid; gap: .9rem; justify-items: center; text-align: center; }
        .tgf-flow {
          margin: 0; font-weight: 800; font-size: clamp(2rem, 7vw, 3.4rem); line-height: 1.15;
          background-size: 200% auto;
          -webkit-background-clip: text; background-clip: text; color: transparent;
          animation: tgf-move 8s linear infinite;
        }
        .tgf-sub { font-size: .95rem; }
        .tgf-ctl { display: inline-flex; gap: .6rem; align-items: center; font-size: .85rem; }
        .tgf-sw { padding: .35rem .9rem; border: 1px solid #e7e5e4; border-radius: 999px;
                  background: transparent; cursor: pointer; font-size: .8rem; }
        .tgf-sw[aria-pressed='true'] { border-color: #a78bfa; color: #a78bfa; }
        @keyframes tgf-move { to { background-position: -200% center; } }
        @media (prefers-reduced-motion: reduce) { .tgf-flow { animation: none; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- GradientFlow.svelte — a gradient that rivers through the letters -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const PALETTES: Record<string, string[]> = {
            aurora: ['#22d3ee', '#a78bfa', '#f472b6'],
            sunset: ['#fbbf24', '#fb7185', '#e879f9'],
            neon: ['#4ade80', '#22d3ee', '#818cf8'],
          };
          let speed = $state(1);
          let pal = $state('aurora');
          const [c1, c2, c3] = $derived(PALETTES[pal]);
          const dur = $derived(`${(8 / speed).toFixed(2)}s`);
          const gradient = $derived(`linear-gradient(100deg, ${c1}, ${c2}, ${c3}, ${c2}, ${c1})`);
        </script>

        <div class="tgf">
          <p class="tgf-kicker">Meridian Pay · general availability</p>
          <h2 class="tgf-flow" style={`background: ${gradient}; animation-duration: ${dur}`}>Payments that flow</h2>
          <p class="tgf-flow tgf-sub" style={`background: ${gradient}; animation-duration: ${dur}`}>
            One dashboard · Frankfurt, Dublin, Singapore
          </p>
          <label class="tgf-ctl">
            <span>Flow speed</span>
            <input type="range" min="0.5" max="3" step="0.25" bind:value={speed} oninput={(e) => (speed = Number(e.currentTarget.value))} />
          </label>
          <div class="tgf-swatches" role="group" aria-label="Colour palette">
            {#each Object.entries(PALETTES) as [name] (name)}
              <button type="button" class="tgf-sw" aria-pressed={pal === name} onclick={() => (pal = name)}>{name}</button>
            {/each}
          </div>
        </div>

        <style>
        .tgf { display: grid; gap: .9rem; justify-items: center; text-align: center; }
        .tgf-flow {
          margin: 0; font-weight: 800; font-size: clamp(2rem, 7vw, 3.4rem); line-height: 1.15;
          background-size: 200% auto;
          -webkit-background-clip: text; background-clip: text; color: transparent;
          animation: tgf-move 8s linear infinite;
        }
        .tgf-sub { font-size: .95rem; }
        .tgf-ctl { display: inline-flex; gap: .6rem; align-items: center; font-size: .85rem; }
        .tgf-sw { padding: .35rem .9rem; border: 1px solid #e7e5e4; border-radius: 999px;
                  background: transparent; cursor: pointer; font-size: .8rem; }
        .tgf-sw[aria-pressed='true'] { border-color: #a78bfa; color: #a78bfa; }
        @keyframes tgf-move { to { background-position: -200% center; } }
        @media (prefers-reduced-motion: reduce) { .tgf-flow { animation: none; } }
        </style>
        SVELTE,
        ],
    ],

    'split-hover' => [
        'title' => ['fa' => 'حروف پرشی هاور', 'en' => 'Split hover letters'],
        'icon' => 'zap',
        'oneLiner' => [
            'fa' => 'متن لاتین حرف‌به‌حرف باز می‌شود و حرفِ زیر موس می‌پرد، می‌چرخد یا پُر می‌شود — سه رفتار با یک سواچ عوض می‌شود و حروف همسایه هم کمی هل می‌خورند. فارسی هرگز حرف‌به‌حرف نمی‌شود: کلمه‌به‌کلمه جابه‌جا می‌شود تا اتصال حروف نشکند.',
            'en' => 'Latin text splits letter by letter and the glyph under your cursor leaps, spins or fills — three behaviours on one switch, with the neighbouring letters nudging along. Persian never splits by letter: it moves word by word so the joining never tears.',
        ],
        'js' => true,
        'docs' => 'https://text-effects.colorion.co',
        'props' => [
            ['name' => 'split', 'type' => 'granularity', 'default' => "'letter · latin'", 'note' => [
                'fa' => 'جداسازی حرف‌به‌حرف فقط روی متن لاتین؛ متن فارسی روی مرز واژه می‌شکند (preg_split با فاصله) تا کشیدگی و اتصال حروف سالم بماند.',
                'en' => 'Letter splitting only for Latin text; Persian breaks at word boundaries (preg_split on spaces) so the joins and stretches stay whole.',
            ]],
            ['name' => 'behaviour', 'type' => 'attribute', 'default' => "'jump'", 'note' => [
                'fa' => 'سه رفتار با data-mode روی ریشه: jump (پرش با منحنی فنری)، spin (چرخش کامل ۳۶۰ درجه) و fill (پُرشدن با رنگ و قرص پس‌زمینه).',
                'en' => 'Three behaviours via data-mode on the root: jump (a spring-eased hop), spin (a full 360° turn) and fill (a colour pill flooding the glyph).',
            ]],
            ['name' => 'neighbours', 'type' => 'selector', 'default' => "'±1'", 'note' => [
                'fa' => 'حروف کنارِ حرف هاورشده با ترکیب :has و انتخابگر خواهر/برادر مجاور نیم‌قدم بالا می‌آیند — موس که رد می‌شود موج می‌گیرد.',
                'en' => 'The letters flanking the hovered one rise half a step through :has and the adjacent-sibling combinator — a wave trails the cursor.',
            ]],
            ['name' => 'aria-label', 'type' => 'a11y', 'default' => "'whole line'", 'note' => [
                'fa' => 'هر حرف برای صفحه‌خوان پنهان است (aria-hidden) و برچسب روی خط، جملهٔ کامل را می‌خواند.',
                'en' => 'Every glyph is hidden from screen readers (aria-hidden) while the line’s label carries the full sentence.',
            ]],
            ['name' => 'reduced-motion', 'type' => 'media', 'default' => "'static'", 'note' => [
                'fa' => 'با prefers-reduced-motion ترنزیشن‌ها خاموش می‌شوند؛ حروف فقط رنگ می‌گیرند و بی‌حرکت می‌مانند.',
                'en' => 'Under prefers-reduced-motion transitions drop out; letters only take colour and stay still.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="spl" x-data="{ mode: 'jump' }" x-bind:data-mode="mode">
            <div class="spl-modes" role="group" aria-label="رفتار حروف">
                <button type="button" x-bind:aria-pressed="mode === 'jump' ? 'true' : 'false'" x-on:click="mode = 'jump'">پرش</button>
                <button type="button" x-bind:aria-pressed="mode === 'spin' ? 'true' : 'false'" x-on:click="mode = 'spin'">چرخش</button>
                <button type="button" x-bind:aria-pressed="mode === 'fill' ? 'true' : 'false'" x-on:click="mode = 'fill'">پُرشدن</button>
            </div>

            {{-- لاتین: حرف‌به‌حرف --}}
            <h2 class="spl-line" dir="ltr" aria-label="SUMMER DROP">
                @foreach (str_split('SUMMER DROP') as $i => $ch)
                    @if ($ch === ' ')
                        <span class="spl-gap" aria-hidden="true"></span>
                    @else
                        <span class="spl-l" style="--i: {{ $i }}" aria-hidden="true">{{ $ch }}</span>
                    @endif
                @endforeach
            </h2>

            {{-- فارسی: واژه‌به‌واژه — اتصال حروف هرگز نمی‌شکند --}}
            <p class="spl-line" aria-label="کالکشن تابستان از راه رسید">
                @foreach (preg_split('/ /u', 'کالکشن تابستان از راه رسید') as $i => $w)
                    <span class="spl-w" style="--i: {{ $i }}" aria-hidden="true">{{ $w }}</span>
                @endforeach
            </p>
        </div>

        <style>
        .spl { display: grid; gap: 1.25rem; justify-items: center; text-align: center; }
        .spl-line { margin: 0; font-weight: 800; font-size: clamp(2rem, 8vw, 3.75rem); line-height: 1.25; }
        .spl-l, .spl-w {
            display: inline-block; transition: translate .28s cubic-bezier(.3, 1.6, .4, 1),
            rotate .5s cubic-bezier(.3, .7, .2, 1), scale .25s ease, background-color .25s ease, color .25s ease;
        }
        .spl-gap { display: inline-block; inline-size: .38em; }
        .spl[data-mode='jump'] .spl-l:hover, .spl[data-mode='jump'] .spl-w:hover { translate: 0 -.45em; color: #7c3aed; }
        .spl[data-mode='spin'] .spl-l:hover, .spl[data-mode='spin'] .spl-w:hover { rotate: 360deg; color: #0891b2; }
        .spl[data-mode='fill'] .spl-l:hover, .spl[data-mode='fill'] .spl-w:hover {
            background: #7c3aed; color: #fff; border-radius: .18em; padding-inline: .08em;
        }
        .spl[data-mode='jump'] .spl-l:hover + .spl-l,
        .spl[data-mode='jump'] .spl-l:has(+ .spl-l:hover) { translate: 0 -.16em; }
        @media (prefers-reduced-motion: reduce) {
            .spl-l, .spl-w { transition: color .25s ease, background-color .25s ease; }
        }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="spl" x-data="{ mode: 'jump' }" x-bind:data-mode="mode">
            <div class="spl-modes" role="group" aria-label="Letter behaviour">
                <button type="button" x-bind:aria-pressed="mode === 'jump' ? 'true' : 'false'" x-on:click="mode = 'jump'">Jump</button>
                <button type="button" x-bind:aria-pressed="mode === 'spin' ? 'true' : 'false'" x-on:click="mode = 'spin'">Spin</button>
                <button type="button" x-bind:aria-pressed="mode === 'fill' ? 'true' : 'false'" x-on:click="mode = 'fill'">Fill</button>
            </div>

            <h2 class="spl-line" aria-label="SUMMER DROP">
                @foreach (str_split('SUMMER DROP') as $i => $ch)
                    @if ($ch === ' ')
                        <span class="spl-gap" aria-hidden="true"></span>
                    @else
                        <span class="spl-l" style="--i: {{ $i }}" aria-hidden="true">{{ $ch }}</span>
                    @endif
                @endforeach
            </h2>
        </div>

        <style>
        .spl { display: grid; gap: 1.25rem; justify-items: center; text-align: center; }
        .spl-line { margin: 0; font-weight: 800; font-size: clamp(2rem, 8vw, 3.75rem); line-height: 1.25; }
        .spl-l {
            display: inline-block; transition: translate .28s cubic-bezier(.3, 1.6, .4, 1),
            rotate .5s cubic-bezier(.3, .7, .2, 1), background-color .25s ease, color .25s ease;
        }
        .spl-gap { display: inline-block; inline-size: .38em; }
        .spl[data-mode='jump'] .spl-l:hover { translate: 0 -.45em; color: #7c3aed; }
        .spl[data-mode='spin'] .spl-l:hover { rotate: 360deg; color: #0891b2; }
        .spl[data-mode='fill'] .spl-l:hover { background: #7c3aed; color: #fff; border-radius: .18em; padding-inline: .08em; }
        .spl[data-mode='jump'] .spl-l:hover + .spl-l,
        .spl[data-mode='jump'] .spl-l:has(+ .spl-l:hover) { translate: 0 -.16em; }
        @media (prefers-reduced-motion: reduce) {
            .spl-l { transition: color .25s ease, background-color .25s ease; }
        }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Drop.jsx — Inertia (React) page; renders the React snippet
        // below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import SplitHover from '@/components/SplitHover';

        export default function Drop() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <SplitHover />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // SplitHover.jsx — Latin letters leap under the cursor, words stay whole
        import { useState } from 'react';
        import '@nabuxai/ui-core/css';

        const LINE = 'SUMMER DROP';
        const MODES = ['jump', 'spin', 'fill'] as const;

        export default function SplitHover() {
          const [mode, setMode] = useState<'jump' | 'spin' | 'fill'>('jump');

          return (
            <>
              <style>{`
                .spl { display: grid; gap: 1.25rem; justify-items: center; text-align: center; }
                .spl-line { margin: 0; font-weight: 800; font-size: clamp(2rem, 8vw, 3.75rem); line-height: 1.25; }
                .spl-l { display: inline-block; transition: translate .28s cubic-bezier(.3, 1.6, .4, 1),
                         rotate .5s cubic-bezier(.3, .7, .2, 1), background-color .25s ease, color .25s ease; }
                .spl-gap { display: inline-block; inline-size: .38em; }
                .spl[data-mode='jump'] .spl-l:hover { translate: 0 -.45em; color: #7c3aed; }
                .spl[data-mode='spin'] .spl-l:hover { rotate: 360deg; color: #0891b2; }
                .spl[data-mode='fill'] .spl-l:hover { background: #7c3aed; color: #fff; border-radius: .18em; padding-inline: .08em; }
                .spl[data-mode='jump'] .spl-l:hover + .spl-l,
                .spl[data-mode='jump'] .spl-l:has(+ .spl-l:hover) { translate: 0 -.16em; }
                @media (prefers-reduced-motion: reduce) { .spl-l { transition: color .25s ease, background-color .25s ease; } }
              `}</style>
              <div className="spl" data-mode={mode}>
                <div className="spl-modes" role="group" aria-label="Letter behaviour">
                  {MODES.map((m) => (
                    <button type="button" key={m} aria-pressed={mode === m} onClick={() => setMode(m)}>{m}</button>
                  ))}
                </div>
                <h2 className="spl-line" aria-label={LINE}>
                  {LINE.split('').map((ch, i) =>
                    ch === ' ' ? <span className="spl-gap" key={i} aria-hidden="true" />
                               : <span className="spl-l" key={i} aria-hidden="true">{ch}</span>,
                  )}
                </h2>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- SplitHover.vue — Latin letters leap under the cursor, words stay whole -->
        <script setup lang="ts">
        import { ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const LINE = 'SUMMER DROP';
        const modes = ['jump', 'spin', 'fill'] as const;
        const mode = ref<'jump' | 'spin' | 'fill'>('jump');
        const letters = LINE.split('');
        </script>

        <template>
          <div class="spl" :data-mode="mode">
            <div class="spl-modes" role="group" aria-label="Letter behaviour">
              <button v-for="m in modes" :key="m" type="button" :aria-pressed="mode === m" @click="mode = m">{{ m }}</button>
            </div>
            <h2 class="spl-line" :aria-label="LINE">
              <template v-for="(ch, i) in letters" :key="i">
                <span v-if="ch === ' '" class="spl-gap" aria-hidden="true" />
                <span v-else class="spl-l" aria-hidden="true">{{ ch }}</span>
              </template>
            </h2>
          </div>
        </template>

        <style scoped>
        .spl { display: grid; gap: 1.25rem; justify-items: center; text-align: center; }
        .spl-line { margin: 0; font-weight: 800; font-size: clamp(2rem, 8vw, 3.75rem); line-height: 1.25; }
        .spl-l { display: inline-block; transition: translate .28s cubic-bezier(.3, 1.6, .4, 1),
                 rotate .5s cubic-bezier(.3, .7, .2, 1), background-color .25s ease, color .25s ease; }
        .spl-gap { display: inline-block; inline-size: .38em; }
        .spl[data-mode='jump'] .spl-l:hover { translate: 0 -.45em; color: #7c3aed; }
        .spl[data-mode='spin'] .spl-l:hover { rotate: 360deg; color: #0891b2; }
        .spl[data-mode='fill'] .spl-l:hover { background: #7c3aed; color: #fff; border-radius: .18em; padding-inline: .08em; }
        .spl[data-mode='jump'] .spl-l:hover + .spl-l,
        .spl[data-mode='jump'] .spl-l:has(+ .spl-l:hover) { translate: 0 -.16em; }
        @media (prefers-reduced-motion: reduce) { .spl-l { transition: color .25s ease, background-color .25s ease; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- SplitHover.svelte — Latin letters leap under the cursor, words stay whole -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const LINE = 'SUMMER DROP';
          const modes = ['jump', 'spin', 'fill'] as const;
          let mode = $state<'jump' | 'spin' | 'fill'>('jump');
          const letters = LINE.split('');
        </script>

        <div class="spl" data-mode={mode}>
          <div class="spl-modes" role="group" aria-label="Letter behaviour">
            {#each modes as m (m)}
              <button type="button" aria-pressed={mode === m} onclick={() => (mode = m)}>{m}</button>
            {/each}
          </div>
          <h2 class="spl-line" aria-label={LINE}>
            {#each letters as ch, i (i)}
              {#if ch === ' '}
                <span class="spl-gap" aria-hidden="true"></span>
              {:else}
                <span class="spl-l" aria-hidden="true">{ch}</span>
              {/if}
            {/each}
          </h2>
        </div>

        <style>
        .spl { display: grid; gap: 1.25rem; justify-items: center; text-align: center; }
        .spl-line { margin: 0; font-weight: 800; font-size: clamp(2rem, 8vw, 3.75rem); line-height: 1.25; }
        .spl-l { display: inline-block; transition: translate .28s cubic-bezier(.3, 1.6, .4, 1),
                 rotate .5s cubic-bezier(.3, .7, .2, 1), background-color .25s ease, color .25s ease; }
        .spl-gap { display: inline-block; inline-size: .38em; }
        .spl[data-mode='jump'] .spl-l:hover { translate: 0 -.45em; color: #7c3aed; }
        .spl[data-mode='spin'] .spl-l:hover { rotate: 360deg; color: #0891b2; }
        .spl[data-mode='fill'] .spl-l:hover { background: #7c3aed; color: #fff; border-radius: .18em; padding-inline: .08em; }
        .spl[data-mode='jump'] .spl-l:hover + .spl-l,
        .spl[data-mode='jump'] .spl-l:has(+ .spl-l:hover) { translate: 0 -.16em; }
        @media (prefers-reduced-motion: reduce) { .spl-l { transition: color .25s ease, background-color .25s ease; } }
        </style>
        SVELTE,
        ],
    ],

    'outline-fill' => [
        'title' => ['fa' => 'دورق و پُر', 'en' => 'Outline to fill'],
        'icon' => 'edit',
        'oneLiner' => [
            'fa' => 'تیتر دورقینی که با هاور یا ورود به دید از پایین پُر می‌شود: لایهٔ زیرین با text-stroke خط می‌کشد، لایهٔ رویی با clip-path از کف بالا می‌آید — یک‌بار با گرادیان، یک‌بار با الگوی شطرنجی؛ سوییچ دوتمی وسط کار عوضشان می‌کند.',
            'en' => 'An outline title that fills from the bottom on hover or on entering view: the under-layer strokes with text-stroke while the top layer climbs up through clip-path — once as a gradient, once as a checkered pattern; a two-dot switch swaps them mid-scene.',
        ],
        'js' => true,
        'docs' => 'https://text-effects.colorion.co',
        'props' => [
            ['name' => '-webkit-text-stroke', 'type' => 'length', 'default' => "'1.5px'", 'note' => [
                'fa' => 'دورقِ لایهٔ زمینه؛ رنگ خط با رنگ متن شفاف جفت می‌شود تا فقط خطِ حروف بماند.',
                'en' => 'The under-layer’s stroke; its colour pairs with transparent text so only the glyph outlines stay.',
            ]],
            ['name' => 'clip-path', 'type' => 'inset', 'default' => "'100% 0 0 0 → 0'", 'note' => [
                'fa' => 'پُرشدن از پایین: لایهٔ پُرشده روی لایهٔ دورقین نشسته و با گذر clip-path از inset(100% 0 0 0) به inset(0) در ۰٫۷ ثانیه از کف بالا می‌آید.',
                'en' => 'Filling from the bottom: the filled layer sits over the outline and climbs from inset(100% 0 0 0) to inset(0) in .7s.',
            ]],
            ['name' => 'trigger', 'type' => 'behaviour', 'default' => "'hover · in-view'", 'note' => [
                'fa' => 'هاور فوری پُر می‌کند؛ ورود به دید هم با IntersectionObserver (آستانهٔ ۳۵٪، فقط یک‌بار) پُر می‌کند و observer خودش را قطع می‌کند — پاک‌سازی با $cleanup.',
                'en' => 'Hover fills instantly; entering view fills too, through an IntersectionObserver (35% threshold, once) that disconnects itself — cleaned up with $cleanup.',
            ]],
            ['name' => 'fill', 'type' => 'attribute', 'default' => "'gradient'", 'note' => [
                'fa' => 'دو مایهٔ پُرشدن با data-fill روی ریشه: گرادیانِ دو رنگ، یا الگوی شطرنجیِ ساخته‌شده با repeating-conic-gradient — هر دو به شکل حروف بریده می‌شوند.',
                'en' => 'Two fill bodies via data-fill on the root: a two-colour gradient, or a checkerboard built from repeating-conic-gradient — both clipped to the glyphs.',
            ]],
            ['name' => 'reduced-motion', 'type' => 'media', 'default' => "'instant'", 'note' => [
                'fa' => 'با prefers-reduced-motion گذرِ clip-path حذف می‌شود و پُر شدن در همان لحظه کامل می‌شود.',
                'en' => 'Under prefers-reduced-motion the clip-path transition drops out and the fill lands complete in the same instant.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="otl" x-data="{ fill: 'gradient' }" x-bind:data-fill="fill"
             x-init="
                 const io = new IntersectionObserver((entries) => entries.forEach((entry) => {
                     if (entry.isIntersecting) { entry.target.dataset.in = 'true'; io.unobserve(entry.target); }
                 }), { threshold: 0.35 });
                 $el.querySelectorAll('.otl-word').forEach((w) => io.observe(w));
                 $cleanup(() => io.disconnect());
             ">
            <div class="otl-switch" role="group" aria-label="مایهٔ پُرشدن">
                <button type="button" x-bind:aria-pressed="fill === 'gradient' ? 'true' : 'false'" x-on:click="fill = 'gradient'">گرادیان</button>
                <button type="button" x-bind:aria-pressed="fill === 'pattern' ? 'true' : 'false'" x-on:click="fill = 'pattern'">الگو</button>
            </div>

            @foreach ([['سرعت', 'پاسخ زیر ۵۰ میلی‌ثانیه'], ['مقیاس', 'تا ۲۴۸ هسته در هر ناحیه'], ['اعتماد', 'رمزگذاری سرتاسری پیش‌فرض']] as [$word, $copy])
                <div class="otl-card">
                    <span class="otl-word"><span class="otl-ghost">{{ $word }}</span>
                        <span class="otl-fill" aria-hidden="true">{{ $word }}</span></span>
                    <p>{{ $copy }}</p>
                </div>
            @endforeach
        </div>

        <style>
        .otl { display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(14rem, 1fr)); }
        .otl-word { position: relative; display: inline-block; font-weight: 900;
                    font-size: clamp(2.2rem, 7vw, 3.4rem); line-height: 1.1; }
        .otl-ghost { color: transparent; -webkit-text-stroke: 1.5px #a8a29e; }
        .otl-fill { position: absolute; inset-block-start: 0; inset-inline-start: 0;
                    background-clip: text; -webkit-background-clip: text; color: transparent;
                    clip-path: inset(100% 0 0 0); transition: clip-path .7s cubic-bezier(.4, 0, .2, 1); }
        .otl[data-fill='gradient'] .otl-fill { background: linear-gradient(15deg, #7c3aed, #22d3ee); }
        .otl[data-fill='pattern'] .otl-fill {
            background: repeating-conic-gradient(#7c3aed 0 25%, #ede9fe 0 50%) 0 0 / 14px 14px; }
        .otl-word:hover .otl-fill, .otl-word[data-in='true'] .otl-fill { clip-path: inset(0); }
        @media (prefers-reduced-motion: reduce) { .otl-fill { transition: none; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="otl" x-data="{ fill: 'gradient' }" x-bind:data-fill="fill"
             x-init="
                 const io = new IntersectionObserver((entries) => entries.forEach((entry) => {
                     if (entry.isIntersecting) { entry.target.dataset.in = 'true'; io.unobserve(entry.target); }
                 }), { threshold: 0.35 });
                 $el.querySelectorAll('.otl-word').forEach((w) => io.observe(w));
                 $cleanup(() => io.disconnect());
             ">
            <div class="otl-switch" role="group" aria-label="Fill body">
                <button type="button" x-bind:aria-pressed="fill === 'gradient' ? 'true' : 'false'" x-on:click="fill = 'gradient'">Gradient</button>
                <button type="button" x-bind:aria-pressed="fill === 'pattern' ? 'true' : 'false'" x-on:click="fill = 'pattern'">Pattern</button>
            </div>

            @foreach ([['SPEED', 'Sub-50ms responses'], ['SCALE', 'Up to 248 cores a region'], ['TRUST', 'Encryption on by default']] as [$word, $copy])
                <div class="otl-card">
                    <span class="otl-word"><span class="otl-ghost">{{ $word }}</span>
                        <span class="otl-fill" aria-hidden="true">{{ $word }}</span></span>
                    <p>{{ $copy }}</p>
                </div>
            @endforeach
        </div>

        <style>
        .otl { display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(14rem, 1fr)); }
        .otl-word { position: relative; display: inline-block; font-weight: 900;
                    font-size: clamp(2.2rem, 7vw, 3.4rem); line-height: 1.1; }
        .otl-ghost { color: transparent; -webkit-text-stroke: 1.5px #a8a29e; }
        .otl-fill { position: absolute; inset-block-start: 0; inset-inline-start: 0;
                    background-clip: text; -webkit-background-clip: text; color: transparent;
                    clip-path: inset(100% 0 0 0); transition: clip-path .7s cubic-bezier(.4, 0, .2, 1); }
        .otl[data-fill='gradient'] .otl-fill { background: linear-gradient(15deg, #7c3aed, #22d3ee); }
        .otl[data-fill='pattern'] .otl-fill {
            background: repeating-conic-gradient(#7c3aed 0 25%, #ede9fe 0 50%) 0 0 / 14px 14px; }
        .otl-word:hover .otl-fill, .otl-word[data-in='true'] .otl-fill { clip-path: inset(0); }
        @media (prefers-reduced-motion: reduce) { .otl-fill { transition: none; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Platforms.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import OutlineFill from '@/components/OutlineFill';

        export default function Platforms() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <OutlineFill />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // OutlineFill.jsx — an outline title that fills from the bottom
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const WORDS = [
          { id: 'speed', word: 'SPEED', copy: 'Sub-50ms responses' },
          { id: 'scale', word: 'SCALE', copy: 'Up to 248 cores a region' },
          { id: 'trust', word: 'TRUST', copy: 'Encryption on by default' },
        ];

        export default function OutlineFill() {
          const [fill, setFill] = useState<'gradient' | 'pattern'>('gradient');
          const rootRef = useRef<HTMLDivElement>(null);

          // Words fill themselves once, the first time 35% of them is visible.
          useEffect(() => {
            const io = new IntersectionObserver(
              (entries) => entries.forEach((e) => {
                if (e.isIntersecting) { (e.target as HTMLElement).dataset.in = 'true'; io.unobserve(e.target); }
              }),
              { threshold: 0.35 },
            );
            rootRef.current?.querySelectorAll('.otl-word').forEach((w) => io.observe(w));
            return () => io.disconnect();
          }, []);

          return (
            <>
              <style>{`
                .otl { display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(14rem, 1fr)); }
                .otl-switch { display: inline-flex; gap: .4rem; }
                .otl-switch button { padding: .35rem .9rem; border: 1px solid #e7e5e4; border-radius: 999px;
                                      background: transparent; cursor: pointer; font-size: .8rem; }
                .otl-switch button[aria-pressed='true'] { border-color: #7c3aed; color: #7c3aed; }
                .otl-word { position: relative; display: inline-block; font-weight: 900;
                            font-size: clamp(2.2rem, 7vw, 3.4rem); line-height: 1.1; }
                .otl-ghost { color: transparent; -webkit-text-stroke: 1.5px #a8a29e; }
                .otl-fill { position: absolute; inset-block-start: 0; inset-inline-start: 0;
                            background-clip: text; -webkit-background-clip: text; color: transparent;
                            clip-path: inset(100% 0 0 0); transition: clip-path .7s cubic-bezier(.4, 0, .2, 1); }
                .otl[data-fill='gradient'] .otl-fill { background: linear-gradient(15deg, #7c3aed, #22d3ee); }
                .otl[data-fill='pattern'] .otl-fill {
                  background: repeating-conic-gradient(#7c3aed 0 25%, #ede9fe 0 50%) 0 0 / 14px 14px; }
                .otl-word:hover .otl-fill, .otl-word[data-in='true'] .otl-fill { clip-path: inset(0); }
                @media (prefers-reduced-motion: reduce) { .otl-fill { transition: none; } }
              `}</style>
              <div className="otl" data-fill={fill} ref={rootRef}>
                <div className="otl-switch" role="group" aria-label="Fill body">
                  <button type="button" aria-pressed={fill === 'gradient'} onClick={() => setFill('gradient')}>Gradient</button>
                  <button type="button" aria-pressed={fill === 'pattern'} onClick={() => setFill('pattern')}>Pattern</button>
                </div>
                {WORDS.map((w) => (
                  <div className="otl-card" key={w.id}>
                    <span className="otl-word">
                      <span className="otl-ghost">{w.word}</span>
                      <span className="otl-fill" aria-hidden="true">{w.word}</span>
                    </span>
                    <p>{w.copy}</p>
                  </div>
                ))}
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- OutlineFill.vue — an outline title that fills from the bottom -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const words = [
          { id: 'speed', word: 'SPEED', copy: 'Sub-50ms responses' },
          { id: 'scale', word: 'SCALE', copy: 'Up to 248 cores a region' },
          { id: 'trust', word: 'TRUST', copy: 'Encryption on by default' },
        ];
        const fill = ref<'gradient' | 'pattern'>('gradient');
        const root = ref<HTMLElement | null>(null);
        let io: IntersectionObserver | null = null;

        onMounted(() => {
          io = new IntersectionObserver(
            (entries) => entries.forEach((e) => {
              if (e.isIntersecting) { (e.target as HTMLElement).dataset.in = 'true'; io!.unobserve(e.target); }
            }),
            { threshold: 0.35 },
          );
          root.value?.querySelectorAll('.otl-word').forEach((w) => io!.observe(w));
        });
        onBeforeUnmount(() => io?.disconnect());
        </script>

        <template>
          <div ref="root" class="otl" :data-fill="fill">
            <div class="otl-switch" role="group" aria-label="Fill body">
              <button type="button" :aria-pressed="fill === 'gradient'" @click="fill = 'gradient'">Gradient</button>
              <button type="button" :aria-pressed="fill === 'pattern'" @click="fill = 'pattern'">Pattern</button>
            </div>
            <div v-for="w in words" :key="w.id" class="otl-card">
              <span class="otl-word">
                <span class="otl-ghost">{{ w.word }}</span>
                <span class="otl-fill" aria-hidden="true">{{ w.word }}</span>
              </span>
              <p>{{ w.copy }}</p>
            </div>
          </div>
        </template>

        <style scoped>
        .otl { display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(14rem, 1fr)); }
        .otl-switch { display: inline-flex; gap: .4rem; grid-column: 1 / -1; }
        .otl-switch button { padding: .35rem .9rem; border: 1px solid #e7e5e4; border-radius: 999px;
                              background: transparent; cursor: pointer; font-size: .8rem; }
        .otl-switch button[aria-pressed='true'] { border-color: #7c3aed; color: #7c3aed; }
        .otl-word { position: relative; display: inline-block; font-weight: 900;
                    font-size: clamp(2.2rem, 7vw, 3.4rem); line-height: 1.1; }
        .otl-ghost { color: transparent; -webkit-text-stroke: 1.5px #a8a29e; }
        .otl-fill { position: absolute; inset-block-start: 0; inset-inline-start: 0;
                    background-clip: text; -webkit-background-clip: text; color: transparent;
                    clip-path: inset(100% 0 0 0); transition: clip-path .7s cubic-bezier(.4, 0, .2, 1); }
        .otl[data-fill='gradient'] .otl-fill { background: linear-gradient(15deg, #7c3aed, #22d3ee); }
        .otl[data-fill='pattern'] .otl-fill {
          background: repeating-conic-gradient(#7c3aed 0 25%, #ede9fe 0 50%) 0 0 / 14px 14px; }
        .otl-word:hover .otl-fill, .otl-word[data-in='true'] .otl-fill { clip-path: inset(0); }
        @media (prefers-reduced-motion: reduce) { .otl-fill { transition: none; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- OutlineFill.svelte — an outline title that fills from the bottom -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          const words = [
            { id: 'speed', word: 'SPEED', copy: 'Sub-50ms responses' },
            { id: 'scale', word: 'SCALE', copy: 'Up to 248 cores a region' },
            { id: 'trust', word: 'TRUST', copy: 'Encryption on by default' },
          ];
          let fill = $state<'gradient' | 'pattern'>('gradient');
          let root: HTMLElement;

          onMount(() => {
            const io = new IntersectionObserver(
              (entries) => entries.forEach((e) => {
                if (e.isIntersecting) { (e.target as HTMLElement).dataset.in = 'true'; io.unobserve(e.target); }
              }),
              { threshold: 0.35 },
            );
            root.querySelectorAll('.otl-word').forEach((w) => io.observe(w));
            return () => io.disconnect();
          });
        </script>

        <div bind:this={root} class="otl" data-fill={fill}>
          <div class="otl-switch" role="group" aria-label="Fill body">
            <button type="button" aria-pressed={fill === 'gradient'} onclick={() => (fill = 'gradient')}>Gradient</button>
            <button type="button" aria-pressed={fill === 'pattern'} onclick={() => (fill = 'pattern')}>Pattern</button>
          </div>
          {#each words as w (w.id)}
            <div class="otl-card">
              <span class="otl-word">
                <span class="otl-ghost">{w.word}</span>
                <span class="otl-fill" aria-hidden="true">{w.word}</span>
              </span>
              <p>{w.copy}</p>
            </div>
          {/each}
        </div>

        <style>
        .otl { display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(14rem, 1fr)); }
        .otl-switch { display: inline-flex; gap: .4rem; grid-column: 1 / -1; }
        .otl-switch button { padding: .35rem .9rem; border: 1px solid #e7e5e4; border-radius: 999px;
                              background: transparent; cursor: pointer; font-size: .8rem; }
        .otl-switch button[aria-pressed='true'] { border-color: #7c3aed; color: #7c3aed; }
        .otl-word { position: relative; display: inline-block; font-weight: 900;
                    font-size: clamp(2.2rem, 7vw, 3.4rem); line-height: 1.1; }
        .otl-ghost { color: transparent; -webkit-text-stroke: 1.5px #a8a29e; }
        .otl-fill { position: absolute; inset-block-start: 0; inset-inline-start: 0;
                    background-clip: text; -webkit-background-clip: text; color: transparent;
                    clip-path: inset(100% 0 0 0); transition: clip-path .7s cubic-bezier(.4, 0, .2, 1); }
        .otl[data-fill='gradient'] .otl-fill { background: linear-gradient(15deg, #7c3aed, #22d3ee); }
        .otl[data-fill='pattern'] .otl-fill {
          background: repeating-conic-gradient(#7c3aed 0 25%, #ede9fe 0 50%) 0 0 / 14px 14px; }
        .otl-word:hover .otl-fill, .otl-word[data-in='true'] .otl-fill { clip-path: inset(0); }
        @media (prefers-reduced-motion: reduce) { .otl-fill { transition: none; } }
        </style>
        SVELTE,
        ],
    ],

    'extrude-3d' => [
        'title' => ['fa' => 'متن برجستهٔ سه‌بعدی', 'en' => '3D extruded text'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'تیتری که از صفحه بیرون می‌زند: لایه‌های سایهٔ افست، هر پیکسل یک پله، عمق می‌سازند و صحنه با perspective زیر موس تیلت می‌خورد؛ اسلایدر عمق را ۳ تا ۱۲ پله می‌کشد و در تیره، لبه‌ها نئونی می‌شوند.',
            'en' => 'A headline that climbs out of the page: stacked offset shadows, one step per pixel, build the depth while the stage tilts under the pointer through perspective; a slider pulls the depth from 3 to 12 steps, and in the dark the edges go neon.',
        ],
        'js' => true,
        'docs' => 'https://text-effects.colorion.co',
        'props' => [
            ['name' => 'text-shadow', 'type' => 'stack', 'default' => "'1px … 6px'", 'note' => [
                'fa' => 'عمق با پشتهٔ سایه ساخته می‌شود: لایهٔ n اُم در n پیکسل افست است و همه به var(--xtd-shade) اشاره می‌کنند تا تم تیره بتواندشان رنگ عوض کند.',
                'en' => 'The depth is a shadow stack: the nth layer sits n pixels off, all pointing at var(--xtd-shade) so the dark theme can recolour them.',
            ]],
            ['name' => 'depth', 'type' => 'number', 'default' => "'6'", 'note' => [
                'fa' => 'اسلایدر ۳ تا ۱۲؛ رشتهٔ سایه در هر تغییر دوباره ساخته می‌شود و پلهٔ آخر یک سایهٔ محو ۱۶ پیکسلی برای فرود است.',
                'en' => 'A 3–12 slider; the shadow string rebuilds on every change and the last step adds a soft 16px blur for the landing.',
            ]],
            ['name' => 'tilt', 'type' => 'pointer', 'default' => "'±12° · ±16°'", 'note' => [
                'fa' => 'صحنه perspective: 900px دارد و نشانگر rotateX تا ۱۲ و rotateY تا ۱۶ درجه را می‌راند؛ با فلش‌های کیبورد هم می‌چرخد و با خروج موس به صفر برمی‌گردد.',
                'en' => 'The stage carries perspective: 900px and the pointer drives rotateX up to 12° and rotateY up to 16°; arrow keys rotate too, and leaving resets to zero.',
            ]],
            ['name' => '--xtd-shade', 'type' => 'color', 'default' => "'#78716c'", 'note' => [
                'fa' => 'رنگ پله‌ها؛ در آینهٔ تیره به بنفش روشن می‌رود و --xtd-glow هالهٔ نئونی پایانی را روشن می‌کند.',
                'en' => 'The steps’ colour; the dark mirror shifts it to a bright violet while --xtd-glow lights the final neon halo.',
            ]],
            ['name' => 'reduced-motion', 'type' => 'media', 'default' => "'no tilt'", 'note' => [
                'fa' => 'با prefers-reduced-motion تیلت غیرفعال می‌شود (چک matchMedia در شنونده) و متن با سایهٔ ثابتش می‌ایستد.',
                'en' => 'Under prefers-reduced-motion the tilt switches off (a matchMedia check inside the listener) and the text stands on its static shadows.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="xtd" x-data="{
                depth: 6, rx: 0, ry: 0,
                reduced: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
                get shadow() {
                    const layers = Array.from({ length: this.depth }, (_, i) => (i + 1) + 'px ' + (i + 1) + 'px 0 var(--xtd-shade)');
                    layers.push(this.depth + 'px ' + this.depth + 'px 16px var(--xtd-glow)');
                    return layers.join(', ');
                },
                move(e) {
                    if (this.reduced) return;
                    const r = e.currentTarget.getBoundingClientRect();
                    this.ry = ((e.clientX - r.left) / r.width - .5) * 32;
                    this.rx = -((e.clientY - r.top) / r.height - .5) * 24;
                },
                reset() { this.rx = 0; this.ry = 0; },
            }">
            <div class="xtd-stage" x-on:pointermove="move($event)" x-on:pointerleave="reset()"
                 tabindex="0" role="img" aria-label="نکسوس ۲۰۲۶، کنفرانس توسعه‌دهندگان">
                <h2 class="xtd-title" x-bind:style="`
                    text-shadow: ${shadow};
                    transform: perspective(900px) rotateX(${rx}deg) rotateY(${ry}deg);`">نکسوس ۲۰۲۶</h2>
            </div>
            <label class="xtd-depth">
                عمق: <output x-text="depth"></output>
                <input type="range" min="3" max="12" step="1" x-model.number="depth">
            </label>
        </div>

        <style>
        .xtd { display: grid; gap: 1rem; justify-items: center; text-align: center; }
        .xtd-stage { display: grid; place-items: center; padding: 2.5rem 1.5rem; border-radius: 1rem;
                     background: #fafaf9; touch-action: none; }
        .xtd-title { margin: 0; font-weight: 900; font-size: clamp(2.2rem, 9vw, 4.2rem);
                     color: #7c3aed; transition: transform .12s ease-out; }
        .xtd-depth { display: inline-grid; gap: .35rem; font-size: .8rem; }
        @media (prefers-reduced-motion: reduce) { .xtd-title { transition: none; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="xtd" x-data="{
                depth: 6, rx: 0, ry: 0,
                reduced: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
                get shadow() {
                    const layers = Array.from({ length: this.depth }, (_, i) => (i + 1) + 'px ' + (i + 1) + 'px 0 var(--xtd-shade)');
                    layers.push(this.depth + 'px ' + this.depth + 'px 16px var(--xtd-glow)');
                    return layers.join(', ');
                },
                move(e) {
                    if (this.reduced) return;
                    const r = e.currentTarget.getBoundingClientRect();
                    this.ry = ((e.clientX - r.left) / r.width - .5) * 32;
                    this.rx = -((e.clientY - r.top) / r.height - .5) * 24;
                },
                reset() { this.rx = 0; this.ry = 0; },
            }">
            <div class="xtd-stage" x-on:pointermove="move($event)" x-on:pointerleave="reset()"
                 tabindex="0" role="img" aria-label="Nexus 2026, the developer conference">
                <h2 class="xtd-title" x-bind:style="`
                    text-shadow: ${shadow};
                    transform: perspective(900px) rotateX(${rx}deg) rotateY(${ry}deg);`">NEXUS 2026</h2>
            </div>
            <label class="xtd-depth">
                Depth: <output x-text="depth"></output>
                <input type="range" min="3" max="12" step="1" x-model.number="depth">
            </label>
        </div>

        <style>
        .xtd { display: grid; gap: 1rem; justify-items: center; text-align: center; }
        .xtd-stage { display: grid; place-items: center; padding: 2.5rem 1.5rem; border-radius: 1rem;
                     background: #fafaf9; touch-action: none; }
        .xtd-title { margin: 0; font-weight: 900; font-size: clamp(2.2rem, 9vw, 4.2rem);
                     color: #7c3aed; transition: transform .12s ease-out; }
        .xtd-depth { display: inline-grid; gap: .35rem; font-size: .8rem; }
        @media (prefers-reduced-motion: reduce) { .xtd-title { transition: none; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Conference.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import Extrude3D from '@/components/Extrude3D';

        export default function Conference() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <Extrude3D />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // Extrude3D.jsx — offset shadow stack + pointer tilt
        import { useMemo, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const reduced = () => window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        export default function Extrude3D() {
          const [depth, setDepth] = useState(6);
          const [tilt, setTilt] = useState({ rx: 0, ry: 0 });

          // One shadow layer per depth step, then a soft landing blur.
          const shadow = useMemo(() => {
            const layers = Array.from({ length: depth }, (_, i) => `${i + 1}px ${i + 1}px 0 var(--xtd-shade)`);
            layers.push(`${depth}px ${depth}px 16px var(--xtd-glow)`);
            return layers.join(', ');
          }, [depth]);

          const move = (e: React.PointerEvent<HTMLDivElement>) => {
            if (reduced()) return;
            const r = e.currentTarget.getBoundingClientRect();
            setTilt({
              ry: ((e.clientX - r.left) / r.width - 0.5) * 32,
              rx: -((e.clientY - r.top) / r.height - 0.5) * 24,
            });
          };

          return (
            <>
              <style>{`
                .xtd { display: grid; gap: 1rem; justify-items: center; text-align: center; }
                .xtd-stage { display: grid; place-items: center; padding: 2.5rem 1.5rem; border-radius: 1rem;
                             background: #fafaf9; touch-action: none; }
                .xtd-title { margin: 0; font-weight: 900; font-size: clamp(2.2rem, 9vw, 4.2rem);
                             color: #7c3aed; transition: transform .12s ease-out; }
                .xtd-depth { display: inline-grid; gap: .35rem; font-size: .8rem; }
                @media (prefers-reduced-motion: reduce) { .xtd-title { transition: none; } }
              `}</style>
              <div className="xtd">
                <div className="xtd-stage" onPointerMove={move} onPointerLeave={() => setTilt({ rx: 0, ry: 0 })}
                     tabIndex={0} role="img" aria-label="Nexus 2026, the developer conference">
                  <h2 className="xtd-title" style={{
                    textShadow: shadow,
                    transform: `perspective(900px) rotateX(${tilt.rx}deg) rotateY(${tilt.ry}deg)`,
                  }}>NEXUS 2026</h2>
                </div>
                <label className="xtd-depth">
                  Depth: <output>{depth}</output>
                  <input type="range" min={3} max={12} step={1} value={depth}
                         onChange={(e) => setDepth(Number(e.target.value))} />
                </label>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- Extrude3D.vue — offset shadow stack + pointer tilt -->
        <script setup lang="ts">
        import { computed, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const depth = ref(6);
        const rx = ref(0);
        const ry = ref(0);
        const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // One shadow layer per depth step, then a soft landing blur.
        const shadow = computed(() => {
          const layers = Array.from({ length: depth.value }, (_, i) => `${i + 1}px ${i + 1}px 0 var(--xtd-shade)`);
          layers.push(`${depth.value}px ${depth.value}px 16px var(--xtd-glow)`);
          return layers.join(', ');
        });
        const transform = computed(() => `perspective(900px) rotateX(${rx.value}deg) rotateY(${ry.value}deg)`);

        const move = (e: PointerEvent) => {
          if (reduced) return;
          const r = (e.currentTarget as HTMLElement).getBoundingClientRect();
          ry.value = ((e.clientX - r.left) / r.width - 0.5) * 32;
          rx.value = -((e.clientY - r.top) / r.height - 0.5) * 24;
        };
        const reset = () => { rx.value = 0; ry.value = 0; };
        </script>

        <template>
          <div class="xtd">
            <div class="xtd-stage" @pointermove="move" @pointerleave="reset"
                 tabindex="0" role="img" aria-label="Nexus 2026, the developer conference">
              <h2 class="xtd-title" :style="{ textShadow: shadow, transform }">NEXUS 2026</h2>
            </div>
            <label class="xtd-depth">
              Depth: <output>{{ depth }}</output>
              <input v-model.number="depth" type="range" min="3" max="12" step="1" />
            </label>
          </div>
        </template>

        <style scoped>
        .xtd { display: grid; gap: 1rem; justify-items: center; text-align: center; }
        .xtd-stage { display: grid; place-items: center; padding: 2.5rem 1.5rem; border-radius: 1rem;
                     background: #fafaf9; touch-action: none; }
        .xtd-title { margin: 0; font-weight: 900; font-size: clamp(2.2rem, 9vw, 4.2rem);
                     color: #7c3aed; transition: transform .12s ease-out; }
        .xtd-depth { display: inline-grid; gap: .35rem; font-size: .8rem; }
        @media (prefers-reduced-motion: reduce) { .xtd-title { transition: none; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- Extrude3D.svelte — offset shadow stack + pointer tilt -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          let depth = $state(6);
          let rx = $state(0);
          let ry = $state(0);
          const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

          // One shadow layer per depth step, then a soft landing blur.
          const shadow = $derived(
            Array.from({ length: depth }, (_, i) => `${i + 1}px ${i + 1}px 0 var(--xtd-shade)`)
              .concat([`${depth}px ${depth}px 16px var(--xtd-glow)`])
              .join(', '),
          );
          const transform = $derived(`perspective(900px) rotateX(${rx}deg) rotateY(${ry}deg)`);

          function move(e: PointerEvent) {
            if (reduced) return;
            const r = (e.currentTarget as HTMLElement).getBoundingClientRect();
            ry = ((e.clientX - r.left) / r.width - 0.5) * 32;
            rx = -((e.clientY - r.top) / r.height - 0.5) * 24;
          }
        </script>

        <div class="xtd">
          <div class="xtd-stage" onpointermove={move} onpointerleave={() => { rx = 0; ry = 0; }}
               tabindex="0" role="img" aria-label="Nexus 2026, the developer conference">
            <h2 class="xtd-title" style={`text-shadow: ${shadow}; transform: ${transform}`}>NEXUS 2026</h2>
          </div>
          <label class="xtd-depth">
            Depth: <output>{depth}</output>
            <input type="range" min="3" max="12" step="1" bind:value={depth}
                   oninput={(e) => (depth = Number(e.currentTarget.value))} />
          </label>
        </div>

        <style>
        .xtd { display: grid; gap: 1rem; justify-items: center; text-align: center; }
        .xtd-stage { display: grid; place-items: center; padding: 2.5rem 1.5rem; border-radius: 1rem;
                     background: #fafaf9; touch-action: none; }
        .xtd-title { margin: 0; font-weight: 900; font-size: clamp(2.2rem, 9vw, 4.2rem);
                     color: #7c3aed; transition: transform .12s ease-out; }
        .xtd-depth { display: inline-grid; gap: .35rem; font-size: .8rem; }
        @media (prefers-reduced-motion: reduce) { .xtd-title { transition: none; } }
        </style>
        SVELTE,
        ],
    ],

    'mask-media' => [
        'title' => ['fa' => 'ماسک رسانه در متن', 'en' => 'Media-masked text'],
        'icon' => 'image',
        'oneLiner' => [
            'fa' => 'درون حروف نمایشگاهی، رسانه جاری است: سه الگوی متحرک — نقاط، موج فلس‌ماهی، شطرنجی — با یک سواچ عوض می‌شوند و زیر حروف با گامِ دقیقِ یک کاشی رژه می‌روند؛ خطِ هم‌خانواده گرادیان صحنه‌ای غروب بندر را می‌چرخاند.',
            'en' => 'Media runs inside exhibition-size glyphs: three animated patterns — dots, fish-scale waves, checkerboard — swap on one switch and march beneath the letters by exactly one tile a step; the sibling line loops a harbour-dusk scene gradient.',
        ],
        'js' => true,
        'docs' => 'https://text-effects.colorion.co',
        'props' => [
            ['name' => 'background-clip', 'type' => 'technique', 'default' => "'text'", 'note' => [
                'fa' => 'الگو روی خود حروف بریده می‌شود و رنگ متن شفاف است؛ تایل‌های الگو با background-size تنظیم می‌شوند.',
                'en' => 'The pattern clips onto the glyphs themselves with transparent ink; the tiles size through background-size.',
            ]],
            ['name' => 'pattern', 'type' => 'attribute', 'default' => "'dots'", 'note' => [
                'fa' => 'سه الگو با data-pat روی ریشه: نقاط (radial-gradient)، موج فلس‌ماهی (دو radial جابه‌جاشده) و شطرنجی (repeating-conic-gradient).',
                'en' => 'Three patterns via data-pat on the root: dots (radial-gradient), fish-scale waves (two offset radials) and a checkerboard (repeating-conic-gradient).',
            ]],
            ['name' => 'march', 'type' => 'keyframes', 'default' => "'1 tile / step'", 'note' => [
                'fa' => 'رژهٔ بدون درز: انیمیشن background-position را دقیقاً به اندازهٔ یک تایلِ همان الگو جلو می‌برد، پس حلقه هرگز نقطهٔ درز نشان نمی‌دهد.',
                'en' => 'A seamless march: the background-position animation advances by exactly that pattern’s tile, so the loop never shows a seam.',
            ]],
            ['name' => 'scene', 'type' => 'gradient', 'default' => "'dusk'", 'note' => [
                'fa' => 'خط هم‌خانواده به‌جای الگو، گرادیان چندایستهٔ غروب بندر را با پس‌زمینهٔ ۲۰۰٪ به‌آرامی می‌چرخاند.',
                'en' => 'The sibling line trades the pattern for a multi-stop harbour-dusk gradient, drifting slowly over a 200% background.',
            ]],
            ['name' => 'reduced-motion', 'type' => 'media', 'default' => "'frozen'", 'note' => [
                'fa' => 'با prefers-reduced-motion رژه و چرخش می‌ایستد و الگو/گرادیان ثابت و کامل دیده می‌شود.',
                'en' => 'Under prefers-reduced-motion the march and drift stop; the pattern or gradient rests, fully visible.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="mmx" x-data="{ pat: 'dots' }" x-bind:data-pat="pat">
            <div class="mmx-chips" role="group" aria-label="الگوی درون حروف">
                <button type="button" x-bind:aria-pressed="pat === 'dots' ? 'true' : 'false'" x-on:click="pat = 'dots'">نقاط</button>
                <button type="button" x-bind:aria-pressed="pat === 'waves' ? 'true' : 'false'" x-on:click="pat = 'waves'">موج</button>
                <button type="button" x-bind:aria-pressed="pat === 'checks' ? 'true' : 'false'" x-on:click="pat = 'checks'">شطرنجی</button>
            </div>

            <h2 class="mmx-word" aria-label="جریان‌ها">جریان‌ها</h2>
            <p class="mmx-scene">جزر و مد · روتردام · ۱۴ تا ۱۸ اکتبر</p>
        </div>

        <style>
        .mmx { display: grid; gap: 1.25rem; justify-items: center; text-align: center; }
        .mmx-word, .mmx-scene {
            margin: 0; color: transparent;
            -webkit-background-clip: text; background-clip: text;
        }
        .mmx-word { font-weight: 900; font-size: clamp(2.8rem, 13vw, 6rem); line-height: 1.1;
                    animation: mmx-march 3.2s linear infinite; }
        .mmx[data-pat='dots'] .mmx-word {
            background-image: radial-gradient(circle, #0f766e 28%, transparent 30%); background-size: 18px 18px; }
        .mmx[data-pat='waves'] .mmx-word {
            background-image: radial-gradient(circle at 50% 110%, transparent 8px, #0f766e 9px 11px, transparent 12px),
                              radial-gradient(circle at 50% -10%, transparent 8px, #14b8a6 9px 11px, transparent 12px);
            background-size: 24px 24px; }
        .mmx[data-pat='checks'] .mmx-word {
            background: repeating-conic-gradient(#0f766e 0 25%, #99f6e4 0 50%) 0 0 / 24px 24px; }
        .mmx-scene { font-weight: 800; font-size: 1rem;
                     background: linear-gradient(100deg, #1e1b4b, #f43f5e, #f59e0b, #1e1b4b);
                     background-size: 200% auto; animation: mmx-drift 9s linear infinite; }
        @keyframes mmx-march { to { background-position: 24px 24px; } }
        @keyframes mmx-drift { to { background-position: -200% center; } }
        @media (prefers-reduced-motion: reduce) { .mmx-word, .mmx-scene { animation: none; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="mmx" x-data="{ pat: 'dots' }" x-bind:data-pat="pat">
            <div class="mmx-chips" role="group" aria-label="Pattern inside the letters">
                <button type="button" x-bind:aria-pressed="pat === 'dots' ? 'true' : 'false'" x-on:click="pat = 'dots'">Dots</button>
                <button type="button" x-bind:aria-pressed="pat === 'waves' ? 'true' : 'false'" x-on:click="pat = 'waves'">Waves</button>
                <button type="button" x-bind:aria-pressed="pat === 'checks' ? 'true' : 'false'" x-on:click="pat = 'checks'">Checker</button>
            </div>

            <h2 class="mmx-word" aria-label="CURRENTS">CURRENTS</h2>
            <p class="mmx-scene">Tide &amp; Stone · Rotterdam · Oct 14–18</p>
        </div>

        <style>
        .mmx { display: grid; gap: 1.25rem; justify-items: center; text-align: center; }
        .mmx-word, .mmx-scene {
            margin: 0; color: transparent;
            -webkit-background-clip: text; background-clip: text;
        }
        .mmx-word { font-weight: 900; font-size: clamp(2.8rem, 13vw, 6rem); line-height: 1.1;
                    animation: mmx-march 3.2s linear infinite; }
        .mmx[data-pat='dots'] .mmx-word {
            background-image: radial-gradient(circle, #0f766e 28%, transparent 30%); background-size: 18px 18px; }
        .mmx[data-pat='waves'] .mmx-word {
            background-image: radial-gradient(circle at 50% 110%, transparent 8px, #0f766e 9px 11px, transparent 12px),
                              radial-gradient(circle at 50% -10%, transparent 8px, #14b8a6 9px 11px, transparent 12px);
            background-size: 24px 24px; }
        .mmx[data-pat='checks'] .mmx-word {
            background: repeating-conic-gradient(#0f766e 0 25%, #99f6e4 0 50%) 0 0 / 24px 24px; }
        .mmx-scene { font-weight: 800; font-size: 1rem;
                     background: linear-gradient(100deg, #1e1b4b, #f43f5e, #f59e0b, #1e1b4b);
                     background-size: 200% auto; animation: mmx-drift 9s linear infinite; }
        @keyframes mmx-march { to { background-position: 24px 24px; } }
        @keyframes mmx-drift { to { background-position: -200% center; } }
        @media (prefers-reduced-motion: reduce) { .mmx-word, .mmx-scene { animation: none; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Exhibition.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import MaskMedia from '@/components/MaskMedia';

        export default function Exhibition() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <MaskMedia />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // MaskMedia.jsx — animated media patterns running inside the glyphs
        import { useState } from 'react';
        import '@nabuxai/ui-core/css';

        const PATTERNS = ['dots', 'waves', 'checks'] as const;

        export default function MaskMedia() {
          const [pat, setPat] = useState<'dots' | 'waves' | 'checks'>('dots');

          return (
            <>
              <style>{`
                .mmx { display: grid; gap: 1.25rem; justify-items: center; text-align: center; }
                .mmx-chips { display: inline-flex; gap: .5rem; }
                .mmx-chips button { padding: .35rem .9rem; border: 1px solid #e7e5e4; border-radius: 999px;
                                     background: transparent; cursor: pointer; font-size: .8rem; }
                .mmx-chips button[aria-pressed='true'] { border-color: #0f766e; color: #0f766e; }
                .mmx-word, .mmx-scene { margin: 0; color: transparent;
                                         -webkit-background-clip: text; background-clip: text; }
                .mmx-word { font-weight: 900; font-size: clamp(2.8rem, 13vw, 6rem); line-height: 1.1;
                            animation: mmx-march 3.2s linear infinite; }
                .mmx[data-pat='dots'] .mmx-word {
                  background-image: radial-gradient(circle, #0f766e 28%, transparent 30%); background-size: 18px 18px; }
                .mmx[data-pat='waves'] .mmx-word {
                  background-image: radial-gradient(circle at 50% 110%, transparent 8px, #0f766e 9px 11px, transparent 12px),
                                    radial-gradient(circle at 50% -10%, transparent 8px, #14b8a6 9px 11px, transparent 12px);
                  background-size: 24px 24px; }
                .mmx[data-pat='checks'] .mmx-word {
                  background: repeating-conic-gradient(#0f766e 0 25%, #99f6e4 0 50%) 0 0 / 24px 24px; }
                .mmx-scene { font-weight: 800; font-size: 1rem;
                             background: linear-gradient(100deg, #1e1b4b, #f43f5e, #f59e0b, #1e1b4b);
                             background-size: 200% auto; animation: mmx-drift 9s linear infinite; }
                @keyframes mmx-march { to { background-position: 24px 24px; } }
                @keyframes mmx-drift { to { background-position: -200% center; } }
                @media (prefers-reduced-motion: reduce) { .mmx-word, .mmx-scene { animation: none; } }
              `}</style>
              <div className="mmx" data-pat={pat}>
                <div className="mmx-chips" role="group" aria-label="Pattern inside the letters">
                  {PATTERNS.map((p) => (
                    <button type="button" key={p} aria-pressed={pat === p} onClick={() => setPat(p)}>{p}</button>
                  ))}
                </div>
                <h2 className="mmx-word" aria-label="CURRENTS">CURRENTS</h2>
                <p className="mmx-scene">Tide &amp; Stone · Rotterdam · Oct 14–18</p>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- MaskMedia.vue — animated media patterns running inside the glyphs -->
        <script setup lang="ts">
        import { ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const patterns = ['dots', 'waves', 'checks'] as const;
        const pat = ref<'dots' | 'waves' | 'checks'>('dots');
        </script>

        <template>
          <div class="mmx" :data-pat="pat">
            <div class="mmx-chips" role="group" aria-label="Pattern inside the letters">
              <button v-for="p in patterns" :key="p" type="button" :aria-pressed="pat === p" @click="pat = p">{{ p }}</button>
            </div>
            <h2 class="mmx-word" aria-label="CURRENTS">CURRENTS</h2>
            <p class="mmx-scene">Tide &amp; Stone · Rotterdam · Oct 14–18</p>
          </div>
        </template>

        <style scoped>
        .mmx { display: grid; gap: 1.25rem; justify-items: center; text-align: center; }
        .mmx-chips { display: inline-flex; gap: .5rem; }
        .mmx-chips button { padding: .35rem .9rem; border: 1px solid #e7e5e4; border-radius: 999px;
                             background: transparent; cursor: pointer; font-size: .8rem; }
        .mmx-chips button[aria-pressed='true'] { border-color: #0f766e; color: #0f766e; }
        .mmx-word, .mmx-scene { margin: 0; color: transparent;
                                 -webkit-background-clip: text; background-clip: text; }
        .mmx-word { font-weight: 900; font-size: clamp(2.8rem, 13vw, 6rem); line-height: 1.1;
                    animation: mmx-march 3.2s linear infinite; }
        .mmx[data-pat='dots'] .mmx-word {
          background-image: radial-gradient(circle, #0f766e 28%, transparent 30%); background-size: 18px 18px; }
        .mmx[data-pat='waves'] .mmx-word {
          background-image: radial-gradient(circle at 50% 110%, transparent 8px, #0f766e 9px 11px, transparent 12px),
                            radial-gradient(circle at 50% -10%, transparent 8px, #14b8a6 9px 11px, transparent 12px);
          background-size: 24px 24px; }
        .mmx[data-pat='checks'] .mmx-word {
          background: repeating-conic-gradient(#0f766e 0 25%, #99f6e4 0 50%) 0 0 / 24px 24px; }
        .mmx-scene { font-weight: 800; font-size: 1rem;
                     background: linear-gradient(100deg, #1e1b4b, #f43f5e, #f59e0b, #1e1b4b);
                     background-size: 200% auto; animation: mmx-drift 9s linear infinite; }
        @keyframes mmx-march { to { background-position: 24px 24px; } }
        @keyframes mmx-drift { to { background-position: -200% center; } }
        @media (prefers-reduced-motion: reduce) { .mmx-word, .mmx-scene { animation: none; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- MaskMedia.svelte — animated media patterns running inside the glyphs -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const patterns = ['dots', 'waves', 'checks'] as const;
          let pat = $state<'dots' | 'waves' | 'checks'>('dots');
        </script>

        <div class="mmx" data-pat={pat}>
          <div class="mmx-chips" role="group" aria-label="Pattern inside the letters">
            {#each patterns as p (p)}
              <button type="button" aria-pressed={pat === p} onclick={() => (pat = p)}>{p}</button>
            {/each}
          </div>
          <h2 class="mmx-word" aria-label="CURRENTS">CURRENTS</h2>
          <p class="mmx-scene">Tide &amp; Stone · Rotterdam · Oct 14–18</p>
        </div>

        <style>
        .mmx { display: grid; gap: 1.25rem; justify-items: center; text-align: center; }
        .mmx-chips { display: inline-flex; gap: .5rem; }
        .mmx-chips button { padding: .35rem .9rem; border: 1px solid #e7e5e4; border-radius: 999px;
                             background: transparent; cursor: pointer; font-size: .8rem; }
        .mmx-chips button[aria-pressed='true'] { border-color: #0f766e; color: #0f766e; }
        .mmx-word, .mmx-scene { margin: 0; color: transparent;
                                 -webkit-background-clip: text; background-clip: text; }
        .mmx-word { font-weight: 900; font-size: clamp(2.8rem, 13vw, 6rem); line-height: 1.1;
                    animation: mmx-march 3.2s linear infinite; }
        .mmx[data-pat='dots'] .mmx-word {
          background-image: radial-gradient(circle, #0f766e 28%, transparent 30%); background-size: 18px 18px; }
        .mmx[data-pat='waves'] .mmx-word {
          background-image: radial-gradient(circle at 50% 110%, transparent 8px, #0f766e 9px 11px, transparent 12px),
                            radial-gradient(circle at 50% -10%, transparent 8px, #14b8a6 9px 11px, transparent 12px);
          background-size: 24px 24px; }
        .mmx[data-pat='checks'] .mmx-word {
          background: repeating-conic-gradient(#0f766e 0 25%, #99f6e4 0 50%) 0 0 / 24px 24px; }
        .mmx-scene { font-weight: 800; font-size: 1rem;
                     background: linear-gradient(100deg, #1e1b4b, #f43f5e, #f59e0b, #1e1b4b);
                     background-size: 200% auto; animation: mmx-drift 9s linear infinite; }
        @keyframes mmx-march { to { background-position: 24px 24px; } }
        @keyframes mmx-drift { to { background-position: -200% center; } }
        @media (prefers-reduced-motion: reduce) { .mmx-word, .mmx-scene { animation: none; } }
        </style>
        SVELTE,
        ],
    ],
];
