<?php

/**
 * Demo manifest of the "ambient" group — living backgrounds that sit under
 * real sections: the aurora of soft blurred blobs, the synthwave perspective
 * grid, the canvas starfield flying at the viewer, topographic contour lines
 * and gooey metaballs. Each scenario is a section with a headline, not a
 * blank page. Scenarios live at
 * resources/views/demos/components/ambient/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'پس‌زمینه‌های محیطی', 'en' => 'Ambient'],

    'aurora-bg' => [
        'title' => ['fa' => 'شفق قطبی', 'en' => 'Aurora background'],
        'icon' => 'sparkles',
        'oneLiner' => [
            'fa' => 'چهار بلاب نرم رنگی با blur سنگین و blend با نام نورافشانی، آرام جابه‌جا می‌شوند و نفس می‌کشند؛ روی بخش تیرهٔ محصول با سرتیتر روشن می‌نشیند و پالتش با یک لمس عوض می‌شود — شفق، غروب یا اقیانوس.',
            'en' => 'Four soft colour blobs, heavily blurred and screen-blended, drift and breathe under a dark product section with a bright headline; one tap swaps the palette — borealis, sunset or ocean.',
        ],
        'js' => true,
        'docs' => 'https://scrolltide.co',
        'props' => [
            ['name' => 'data-pal', 'type' => 'string', 'default' => "'borealis'", 'note' => [
                'fa' => 'پالت: borealis · sunset · ocean؛ سه رنگ بلاب را با متغیرهای CSS عوض می‌کند.',
                'en' => 'Palette: borealis · sunset · ocean; swaps the three blob colours through CSS variables.',
            ]],
            ['name' => '--amba-blur', 'type' => 'length', 'default' => "'64px'", 'note' => [
                'fa' => 'میزان محوی بلاب‌ها؛ بیشتر = نرم‌تر و کم‌رنگ‌تر، کمتر = لبه‌های رنگی.',
                'en' => 'How soft the blobs are; more = smoother and fainter, less = colour edges.',
            ]],
            ['name' => 'blob', 'type' => 'keyframes', 'default' => "'22–34s'", 'note' => [
                'fa' => 'هر بلاب مسیر و دورهٔ خودش را دارد؛ فقط transform انیمیت می‌شود تا روی GPU بماند.',
                'en' => 'Each blob owns its path and period; only transform animates so it stays on the GPU.',
            ]],
            ['name' => 'pause', 'type' => 'state', 'default' => "'off-screen'", 'note' => [
                'fa' => 'با IntersectionObserver بیرون از دید، انیمیشن pause می‌شود؛ در حرکت کاهش‌یافته کاملاً می‌ایستد.',
                'en' => 'An IntersectionObserver pauses the drift off screen; under reduced motion it stops entirely.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <section class="amba" data-pal="borealis" data-running="true">
            <div class="amba-sky" aria-hidden="true">
                <i class="amba-blob a"></i><i class="amba-blob b"></i>
                <i class="amba-blob c"></i><i class="amba-blob d"></i>
            </div>
            <h2>زیر نور شفق، استقرار سرد است</h2>
            <p>رایانش لبه در ۳۱ شهر — تأخیر زیر ۵۰ میلی‌ثانیه.</p>
        </section>

        <style>
        .amba { position: relative; overflow: clip; border-radius: 1.25rem;
                min-block-size: 22rem; display: grid; place-content: center; text-align: center;
                background: #0a1120; color: #f1f5f9; }
        .amba-sky { position: absolute; inset: 0; }
        .amba-blob { position: absolute; inline-size: 26rem; aspect-ratio: 1; border-radius: 50%;
                     filter: blur(64px); mix-blend-mode: screen; opacity: .55; }
        .amba[data-pal='sunset'] { --amba-c1: #f97316; --amba-c2: #ec4899; --amba-c3: #8b5cf6; }
        .amba-blob.a { background: var(--amba-c1, #22d3ee); animation: amba-drift-a 26s ease-in-out infinite alternate; }
        @keyframes amba-drift-a { from { translate: -20% -30%; scale: 1; }
                                  to { translate: 15% 10%; scale: 1.25; } }
        .amba[data-running='false'] .amba-blob { animation-play-state: paused; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <section class="amba" data-pal="borealis" data-running="true">
        <div class="amba-sky" aria-hidden="true">
        <i class="amba-blob a"></i><i class="amba-blob b"></i>
        <i class="amba-blob c"></i><i class="amba-blob d"></i>
        </div>
        <h2>Cold deploys under the aurora</h2>
        <p>Edge compute in 31 cities — sub-50ms latency.</p>
        </section>

        <style>
        .amba { position: relative; overflow: clip; border-radius: 1.25rem;
        min-block-size: 22rem; display: grid; place-content: center; text-align: center;
        background: #0a1120; color: #f1f5f9; }
        .amba-sky { position: absolute; inset: 0; }
        .amba-blob { position: absolute; inline-size: 26rem; aspect-ratio: 1; border-radius: 50%;
        filter: blur(64px); mix-blend-mode: screen; opacity: .55; }
        .amba[data-pal='sunset'] { --amba-c1: #f97316; --amba-c2: #ec4899; --amba-c3: #8b5cf6; }
        .amba-blob.a { background: var(--amba-c1, #22d3ee); animation: amba-drift-a 26s ease-in-out infinite alternate; }
        @keyframes amba-drift-a { from { translate: -20% -30%; scale: 1; }
                                  to { translate: 15% 10%; scale: 1.25; } }
        .amba[data-running='false'] .amba-blob { animation-play-state: paused; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Aurora.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import AuroraHero from '@/components/AuroraHero';

        export default function Aurora() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <AuroraHero />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // AuroraHero.jsx — aurora background: blurred blobs drifting under a dark section
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const PALETTES = {
          borealis: ['#22d3ee', '#34d399', '#818cf8'],
          sunset: ['#f97316', '#ec4899', '#8b5cf6'],
          ocean: ['#38bdf8', '#6366f1', '#0ea5e9'],
        };
        const BLOBS = [
          { cls: 'a', size: '26rem' }, { cls: 'b', size: '32rem' },
          { cls: 'c', size: '22rem' }, { cls: 'd', size: '28rem' },
        ];

        export default function AuroraHero() {
          const [pal, setPal] = useState('borealis');
          const [running, setRunning] = useState(true);
          const sectionRef = useRef(null);

          // Park the drift off screen; reduced-motion users get a still sky.
          useEffect(() => {
            const io = new IntersectionObserver(
              ([e]) => setRunning(e.isIntersecting),
              { threshold: 0.15 },
            );
            io.observe(sectionRef.current);
            return () => io.disconnect();
          }, []);

          return (
            <>
              <style>{`
                .amba { position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 22rem;
                        display: grid; place-content: center; text-align: center; background: #0a1120; color: #f1f5f9; }
                .amba-sky { position: absolute; inset: 0; }
                .amba-blob { position: absolute; border-radius: 50%; filter: blur(64px);
                             mix-blend-mode: screen; opacity: .55; }
                .amba-blob.a { animation: amba-drift-a 26s ease-in-out infinite alternate; }
                .amba-blob.b { animation: amba-drift-b 32s ease-in-out infinite alternate; }
                .amba-blob.c { animation: amba-drift-c 24s ease-in-out infinite alternate; }
                .amba-blob.d { animation: amba-drift-d 34s ease-in-out infinite alternate; }
                @keyframes amba-drift-a { from { translate: -20% -30%; scale: 1; } to { translate: 15% 10%; scale: 1.25; } }
                @keyframes amba-drift-b { from { translate: 60% 20%; scale: 1.2; } to { translate: 25% -25%; scale: .95; } }
                @keyframes amba-drift-c { from { translate: 30% 70%; scale: .9; } to { translate: 55% 40%; scale: 1.3; } }
                @keyframes amba-drift-d { from { translate: -35% 45%; scale: 1.1; } to { translate: -5% 60%; scale: .9; } }
                .amba[data-running='false'] .amba-blob { animation-play-state: paused; }
                .amba-copy { position: relative; padding: 2rem; }
                .amba-dots { display: flex; gap: .5rem; justify-content: center; margin-block-start: 1.25rem; }
                .amba-dot { inline-size: 2rem; block-size: 2rem; border-radius: 50%; border: 2px solid #f1f5f940; cursor: pointer; }
                .amba-dot[aria-pressed='true'] { border-color: #f8fafc; }
                @media (prefers-reduced-motion: reduce) { .amba-blob { animation: none; } }
              `}</style>
              <section className="amba" data-running={running} ref={sectionRef}
                style={{ ['--amba-c1']: PALETTES[pal][0], ['--amba-c2']: PALETTES[pal][1], ['--amba-c3']: PALETTES[pal][2] }}>
                <div className="amba-sky" aria-hidden="true">
                  {BLOBS.map((b, i) => (
                    <i key={b.cls} className={`amba-blob ${b.cls}`} style={{
                      inlineSize: b.size, blockSize: b.size,
                      insetInlineStart: `${8 + i * 22}%`,
                      insetBlockStart: `${(i % 2) * 30}%`,
                      background: PALETTES[pal][i % 3],
                    }} />
                  ))}
                </div>
                <div className="amba-copy">
                  <h2>Cold deploys under the aurora</h2>
                  <p>Edge compute in 31 cities — sub-50ms latency, zero config.</p>
                  <div className="amba-dots" role="group" aria-label="Aurora palette">
                    {Object.entries(PALETTES).map(([id, [c]]) => (
                      <button key={id} type="button" className="amba-dot" aria-pressed={pal === id}
                        aria-label={id} style={{ background: c }} onClick={() => setPal(id)} />
                    ))}
                  </div>
                </div>
              </section>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- AuroraHero.vue — aurora background: blurred blobs drifting under a dark section -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const palettes: Record<string, string[]> = {
          borealis: ['#22d3ee', '#34d399', '#818cf8'],
          sunset: ['#f97316', '#ec4899', '#8b5cf6'],
          ocean: ['#38bdf8', '#6366f1', '#0ea5e9'],
        };
        const blobs = [
          { cls: 'a', size: '26rem' }, { cls: 'b', size: '32rem' },
          { cls: 'c', size: '22rem' }, { cls: 'd', size: '28rem' },
        ];
        const pal = ref('borealis');
        const running = ref(true);
        const section = ref<HTMLElement | null>(null);
        let io: IntersectionObserver | null = null;

        // Park the drift off screen; reduced-motion users get a still sky.
        onMounted(() => {
          io = new IntersectionObserver(([e]) => { running.value = e.isIntersecting; }, { threshold: 0.15 });
          if (section.value) io.observe(section.value);
        });
        onBeforeUnmount(() => io?.disconnect());
        </script>

        <template>
          <section ref="section" class="amba" :data-running="running"
            :style="{ '--amba-c1': palettes[pal][0], '--amba-c2': palettes[pal][1], '--amba-c3': palettes[pal][2] }">
            <div class="amba-sky" aria-hidden="true">
              <i v-for="(b, i) in blobs" :key="b.cls" :class="['amba-blob', b.cls]"
                :style="{ inlineSize: b.size, blockSize: b.size,
                          insetInlineStart: `${8 + i * 22}%`, insetBlockStart: `${(i % 2) * 30}%`,
                          background: palettes[pal][i % 3] }" />
            </div>
            <div class="amba-copy">
              <h2>Cold deploys under the aurora</h2>
              <p>Edge compute in 31 cities — sub-50ms latency, zero config.</p>
              <div class="amba-dots" role="group" aria-label="Aurora palette">
                <button v-for="(colors, id) in palettes" :key="id" type="button" class="amba-dot"
                  :aria-pressed="pal === id" :aria-label="id" :style="{ background: colors[0] }"
                  @click="pal = id" />
              </div>
            </div>
          </section>
        </template>

        <style scoped>
        .amba { position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 22rem;
                display: grid; place-content: center; text-align: center; background: #0a1120; color: #f1f5f9; }
        .amba-sky { position: absolute; inset: 0; }
        .amba-blob { position: absolute; border-radius: 50%; filter: blur(64px); mix-blend-mode: screen; opacity: .55; }
        .amba-blob.a { animation: amba-drift-a 26s ease-in-out infinite alternate; }
        .amba-blob.b { animation: amba-drift-b 32s ease-in-out infinite alternate; }
        .amba-blob.c { animation: amba-drift-c 24s ease-in-out infinite alternate; }
        .amba-blob.d { animation: amba-drift-d 34s ease-in-out infinite alternate; }
        @keyframes amba-drift-a { from { translate: -20% -30%; scale: 1; } to { translate: 15% 10%; scale: 1.25; } }
        @keyframes amba-drift-b { from { translate: 60% 20%; scale: 1.2; } to { translate: 25% -25%; scale: .95; } }
        @keyframes amba-drift-c { from { translate: 30% 70%; scale: .9; } to { translate: 55% 40%; scale: 1.3; } }
        @keyframes amba-drift-d { from { translate: -35% 45%; scale: 1.1; } to { translate: -5% 60%; scale: .9; } }
        .amba[data-running='false'] .amba-blob { animation-play-state: paused; }
        .amba-copy { position: relative; padding: 2rem; }
        .amba-dots { display: flex; gap: .5rem; justify-content: center; margin-block-start: 1.25rem; }
        .amba-dot { inline-size: 2rem; block-size: 2rem; border-radius: 50%; border: 2px solid #f1f5f940; cursor: pointer; }
        .amba-dot[aria-pressed='true'] { border-color: #f8fafc; }
        @media (prefers-reduced-motion: reduce) { .amba-blob { animation: none; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- AuroraHero.svelte — aurora background: blurred blobs drifting under a dark section -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          const palettes: Record<string, string[]> = {
            borealis: ['#22d3ee', '#34d399', '#818cf8'],
            sunset: ['#f97316', '#ec4899', '#8b5cf6'],
            ocean: ['#38bdf8', '#6366f1', '#0ea5e9'],
          };
          const blobs = [
            { cls: 'a', size: '26rem' }, { cls: 'b', size: '32rem' },
            { cls: 'c', size: '22rem' }, { cls: 'd', size: '28rem' },
          ];
          let pal = $state('borealis');
          let running = $state(true);
          let section: HTMLElement;

          // Park the drift off screen; reduced-motion users get a still sky.
          onMount(() => {
            const io = new IntersectionObserver(([e]) => { running = e.isIntersecting; }, { threshold: 0.15 });
            io.observe(section);
            return () => io.disconnect();
          });
        </script>

        <section bind:this={section} class="amba" data-running={running}
          style={`--amba-c1:${palettes[pal][0]};--amba-c2:${palettes[pal][1]};--amba-c3:${palettes[pal][2]}`}>
          <div class="amba-sky" aria-hidden="true">
            {#each blobs as b, i (b.cls)}
              <i class={`amba-blob ${b.cls}`} style={`inline-size:${b.size};block-size:${b.size};
                inset-inline-start:${8 + i * 22}%;inset-block-start:${(i % 2) * 30}%;
                background:${palettes[pal][i % 3]}`}></i>
            {/each}
          </div>
          <div class="amba-copy">
            <h2>Cold deploys under the aurora</h2>
            <p>Edge compute in 31 cities — sub-50ms latency, zero config.</p>
            <div class="amba-dots" role="group" aria-label="Aurora palette">
              {#each Object.entries(palettes) as [id, colors] (id)}
                <button type="button" class="amba-dot" aria-pressed={pal === id} aria-label={id}
                  style={`background:${colors[0]}`} onclick={() => (pal = id)}></button>
              {/each}
            </div>
          </div>
        </section>

        <style>
        .amba { position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 22rem;
                display: grid; place-content: center; text-align: center; background: #0a1120; color: #f1f5f9; }
        .amba-sky { position: absolute; inset: 0; }
        .amba-blob { position: absolute; border-radius: 50%; filter: blur(64px); mix-blend-mode: screen; opacity: .55; }
        .amba-blob.a { animation: amba-drift-a 26s ease-in-out infinite alternate; }
        .amba-blob.b { animation: amba-drift-b 32s ease-in-out infinite alternate; }
        .amba-blob.c { animation: amba-drift-c 24s ease-in-out infinite alternate; }
        .amba-blob.d { animation: amba-drift-d 34s ease-in-out infinite alternate; }
        @keyframes amba-drift-a { from { translate: -20% -30%; scale: 1; } to { translate: 15% 10%; scale: 1.25; } }
        @keyframes amba-drift-b { from { translate: 60% 20%; scale: 1.2; } to { translate: 25% -25%; scale: .95; } }
        @keyframes amba-drift-c { from { translate: 30% 70%; scale: .9; } to { translate: 55% 40%; scale: 1.3; } }
        @keyframes amba-drift-d { from { translate: -35% 45%; scale: 1.1; } to { translate: -5% 60%; scale: .9; } }
        .amba[data-running='false'] .amba-blob { animation-play-state: paused; }
        .amba-copy { position: relative; padding: 2rem; }
        .amba-dots { display: flex; gap: .5rem; justify-content: center; margin-block-start: 1.25rem; }
        .amba-dot { inline-size: 2rem; block-size: 2rem; border-radius: 50%; border: 2px solid #f1f5f940; cursor: pointer; }
        .amba-dot[aria-pressed='true'] { border-color: #f8fafc; }
        @media (prefers-reduced-motion: reduce) { .amba-blob { animation: none; } }
        </style>
        SVELTE,
        ],
    ],

    'perspective-grid' => [
        'title' => ['fa' => 'شبکهٔ پرسپکتیو', 'en' => 'Perspective grid'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'کف شبکه‌ای سینت‌ویو: صفحهٔ چرخیده در perspective که خطوطش به سمت افق می‌روند و با یک حلقهٔ transform به سمت بیننده می‌آیند؛ مهِ افق لبه را می‌بلعد، سرعت با اسلایدر و درخشش خطوط در تم تیره.',
            'en' => 'A synthwave grid floor: a perspective-rotated plane whose lines run to the horizon and stream toward you on a transform loop; horizon fog swallows the edge, a slider sets the speed and the lines glow in the dark.',
        ],
        'js' => true,
        'docs' => 'https://scrolltide.co',
        'props' => [
            ['name' => '--ambg-tile', 'type' => 'length', 'default' => "'64px'", 'note' => [
                'fa' => 'اندازهٔ خانهٔ شبکه؛ حرکت حلقه دقیقاً یک خانه است تا درز بی‌درز بماند.',
                'en' => 'The grid cell; the loop travels exactly one cell so the seam never shows.',
            ]],
            ['name' => 'speed', 'type' => 'seconds', 'default' => "'1.6s / cell'", 'note' => [
                'fa' => 'اسلایدر دورهٔ انیمیشن را از ۳ ثانیه (آرام) تا ۰٫۶ ثانیه (تند) می‌کشد.',
                'en' => 'The slider pulls the period from 3s (calm) down to .6s (fast).',
            ]],
            ['name' => 'fog', 'type' => 'gradient', 'default' => "'55%'", 'note' => [
                'fa' => 'نوار مه بالای صحنه افق را می‌پوشاند و شروع شبکه را در دوردست محو می‌کند.',
                'en' => 'The fog band above the floor hides the horizon and fades the grid’s far start.',
            ]],
            ['name' => 'glow', 'type' => 'color', 'default' => "'#f0abfc'", 'note' => [
                'fa' => 'در تم تیره خطوط مه‌بنفش با box-shadow ملایم می‌درخشند؛ در روشن تخت و خاکستری‌اند.',
                'en' => 'In the dark theme the lines glow fuchsia through a soft shadow; in light they sit flat and grey.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <section class="ambg" style="--ambg-dur: 1.6s" data-running="true">
            <div class="ambg-floor" aria-hidden="true"><i class="ambg-lines"></i></div>
            <div class="ambg-fog" aria-hidden="true"></div>
            <h2>شب‌گرد، ایستگاه رترو</h2>
            <p>سی‌دیِ ۳۲ سال، فقط با یک سرعت‌گیر جابه‌جا می‌شود.</p>
        </section>

        <style>
        .ambg { position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 22rem;
                display: grid; place-content: center; text-align: center;
                background: #171228; color: #fdf4ff; perspective: 420px; }
        .ambg-floor { position: absolute; inset-inline: -60%; inset-block-start: 52%; block-size: 150%;
                      transform: rotateX(58deg); transform-origin: 50% 0; }
        .ambg-lines { position: absolute; inset-inline: 0; inset-block: -100% 0;
                      background-image: repeating-linear-gradient(to right, #f0abfc33 0 2px, transparent 2px 64px),
                                        repeating-linear-gradient(to bottom, #f0abfc33 0 2px, transparent 2px 64px);
                      animation: ambg-run var(--ambg-dur) linear infinite; }
        @keyframes ambg-run { to { translate: 0 64px; } }
        .ambg-fog { position: absolute; inset-inline: 0; inset-block-start: 0; block-size: 55%;
                    background: linear-gradient(#171228 12%, transparent); }
        .ambg[data-running='false'] .ambg-lines { animation-play-state: paused; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <section class="ambg" style="--ambg-dur: 1.6s" data-running="true">
        <div class="ambg-floor" aria-hidden="true"><i class="ambg-lines"></i></div>
        <div class="ambg-fog" aria-hidden="true"></div>
        <h2>Nightdrive, the retro station</h2>
        <p>32 years of synth, one speed slider away.</p>
        </section>

        <style>
        .ambg { position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 22rem;
        display: grid; place-content: center; text-align: center;
        background: #171228; color: #fdf4ff; perspective: 420px; }
        .ambg-floor { position: absolute; inset-inline: -60%; inset-block-start: 52%; block-size: 150%;
        transform: rotateX(58deg); transform-origin: 50% 0; }
        .ambg-lines { position: absolute; inset-inline: 0; inset-block: -100% 0;
        background-image: repeating-linear-gradient(to right, #f0abfc33 0 2px, transparent 2px 64px),
                          repeating-linear-gradient(to bottom, #f0abfc33 0 2px, transparent 2px 64px);
        animation: ambg-run var(--ambg-dur) linear infinite; }
        @keyframes ambg-run { to { translate: 0 64px; } }
        .ambg-fog { position: absolute; inset-inline: 0; inset-block-start: 0; block-size: 55%;
        background: linear-gradient(#171228 12%, transparent); }
        .ambg[data-running='false'] .ambg-lines { animation-play-state: paused; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Nightdrive.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import NightdriveHero from '@/components/NightdriveHero';

        export default function Nightdrive() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <NightdriveHero />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // NightdriveHero.jsx — synthwave perspective grid with a speed slider
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        export default function NightdriveHero() {
          const [dur, setDur] = useState(1.6); // seconds per 64px cell
          const [running, setRunning] = useState(true);
          const sectionRef = useRef(null);

          // Park the stream off screen; reduced motion freezes the floor.
          useEffect(() => {
            const io = new IntersectionObserver(
              ([e]) => setRunning(e.isIntersecting),
              { threshold: 0.15 },
            );
            io.observe(sectionRef.current);
            return () => io.disconnect();
          }, []);

          return (
            <>
              <style>{`
                .ambg { position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 22rem;
                        display: grid; place-content: center; text-align: center; background: #171228;
                        color: #fdf4ff; perspective: 420px; }
                .ambg-floor { position: absolute; inset-inline: -60%; inset-block-start: 52%; block-size: 150%;
                              transform: rotateX(58deg); transform-origin: 50% 0; }
                .ambg-lines { position: absolute; inset-inline: 0; inset-block: -100% 0;
                              background-image: repeating-linear-gradient(to right, #f0abfc4d 0 2px, transparent 2px 64px),
                                                repeating-linear-gradient(to bottom, #f0abfc4d 0 2px, transparent 2px 64px);
                              animation: ambg-run var(--ambg-dur) linear infinite; }
                @keyframes ambg-run { to { translate: 0 64px; } }
                .ambg-fog { position: absolute; inset-inline: 0; inset-block-start: 0; block-size: 55%;
                            background: linear-gradient(#171228 12%, transparent); }
                .ambg[data-running='false'] .ambg-lines { animation-play-state: paused; }
                .ambg-copy { position: relative; padding: 2rem; }
                .ambg-slider { display: flex; align-items: center; gap: .75rem; justify-content: center;
                               margin-block-start: 1.25rem; font-size: .8rem; color: #d8b4fe; }
                .ambg-slider input { accent-color: #f0abfc; inline-size: min(14rem, 60vw); }
                @media (prefers-reduced-motion: reduce) { .ambg-lines { animation: none; } }
              `}</style>
              <section className="ambg" data-running={running} ref={sectionRef}
                style={{ ['--ambg-dur']: `${dur}s` }}>
                <div className="ambg-floor" aria-hidden="true"><i className="ambg-lines" /></div>
                <div className="ambg-fog" aria-hidden="true" />
                <div className="ambg-copy">
                  <h2>Nightdrive, the retro station</h2>
                  <p>32 years of synth, streamed from Tokyo to Toronto.</p>
                  <label className="ambg-slider">
                    <span aria-hidden="true">🐢</span>
                    <input type="range" min="0.6" max="3" step="0.1" value={dur}
                      aria-label="Grid speed" onChange={(e) => setDur(+e.target.value)} />
                    <span aria-hidden="true">🚀</span>
                  </label>
                </div>
              </section>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- NightdriveHero.vue — synthwave perspective grid with a speed slider -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const dur = ref(1.6); // seconds per 64px cell
        const running = ref(true);
        const section = ref<HTMLElement | null>(null);
        let io: IntersectionObserver | null = null;

        // Park the stream off screen; reduced motion freezes the floor.
        onMounted(() => {
          io = new IntersectionObserver(([e]) => { running.value = e.isIntersecting; }, { threshold: 0.15 });
          if (section.value) io.observe(section.value);
        });
        onBeforeUnmount(() => io?.disconnect());
        </script>

        <template>
          <section ref="section" class="ambg" :data-running="running" :style="{ '--ambg-dur': `${dur}s` }">
            <div class="ambg-floor" aria-hidden="true"><i class="ambg-lines" /></div>
            <div class="ambg-fog" aria-hidden="true" />
            <div class="ambg-copy">
              <h2>Nightdrive, the retro station</h2>
              <p>32 years of synth, streamed from Tokyo to Toronto.</p>
              <label class="ambg-slider">
                <span aria-hidden="true">🐢</span>
                <input v-model.number="dur" type="range" min="0.6" max="3" step="0.1" aria-label="Grid speed" />
                <span aria-hidden="true">🚀</span>
              </label>
            </div>
          </section>
        </template>

        <style scoped>
        .ambg { position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 22rem;
                display: grid; place-content: center; text-align: center; background: #171228;
                color: #fdf4ff; perspective: 420px; }
        .ambg-floor { position: absolute; inset-inline: -60%; inset-block-start: 52%; block-size: 150%;
                      transform: rotateX(58deg); transform-origin: 50% 0; }
        .ambg-lines { position: absolute; inset-inline: 0; inset-block: -100% 0;
                      background-image: repeating-linear-gradient(to right, #f0abfc4d 0 2px, transparent 2px 64px),
                                        repeating-linear-gradient(to bottom, #f0abfc4d 0 2px, transparent 2px 64px);
                      animation: ambg-run var(--ambg-dur) linear infinite; }
        @keyframes ambg-run { to { translate: 0 64px; } }
        .ambg-fog { position: absolute; inset-inline: 0; inset-block-start: 0; block-size: 55%;
                    background: linear-gradient(#171228 12%, transparent); }
        .ambg[data-running='false'] .ambg-lines { animation-play-state: paused; }
        .ambg-copy { position: relative; padding: 2rem; }
        .ambg-slider { display: flex; align-items: center; gap: .75rem; justify-content: center;
                       margin-block-start: 1.25rem; font-size: .8rem; color: #d8b4fe; }
        .ambg-slider input { accent-color: #f0abfc; inline-size: min(14rem, 60vw); }
        @media (prefers-reduced-motion: reduce) { .ambg-lines { animation: none; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- NightdriveHero.svelte — synthwave perspective grid with a speed slider -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          let dur = $state(1.6); // seconds per 64px cell
          let running = $state(true);
          let section: HTMLElement;

          // Park the stream off screen; reduced motion freezes the floor.
          onMount(() => {
            const io = new IntersectionObserver(([e]) => { running = e.isIntersecting; }, { threshold: 0.15 });
            io.observe(section);
            return () => io.disconnect();
          });
        </script>

        <section bind:this={section} class="ambg" data-running={running} style={`--ambg-dur:${dur}s`}>
          <div class="ambg-floor" aria-hidden="true"><i class="ambg-lines"></i></div>
          <div class="ambg-fog" aria-hidden="true"></div>
          <div class="ambg-copy">
            <h2>Nightdrive, the retro station</h2>
            <p>32 years of synth, streamed from Tokyo to Toronto.</p>
            <label class="ambg-slider">
              <span aria-hidden="true">🐢</span>
              <input type="range" min="0.6" max="3" step="0.1" aria-label="Grid speed"
                bind:value={dur} oninput={(e) => (dur = +e.currentTarget.value)} />
              <span aria-hidden="true">🚀</span>
            </label>
          </div>
        </section>

        <style>
        .ambg { position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 22rem;
                display: grid; place-content: center; text-align: center; background: #171228;
                color: #fdf4ff; perspective: 420px; }
        .ambg-floor { position: absolute; inset-inline: -60%; inset-block-start: 52%; block-size: 150%;
                      transform: rotateX(58deg); transform-origin: 50% 0; }
        .ambg-lines { position: absolute; inset-inline: 0; inset-block: -100% 0;
                      background-image: repeating-linear-gradient(to right, #f0abfc4d 0 2px, transparent 2px 64px),
                                        repeating-linear-gradient(to bottom, #f0abfc4d 0 2px, transparent 2px 64px);
                      animation: ambg-run var(--ambg-dur) linear infinite; }
        @keyframes ambg-run { to { translate: 0 64px; } }
        .ambg-fog { position: absolute; inset-inline: 0; inset-block-start: 0; block-size: 55%;
                    background: linear-gradient(#171228 12%, transparent); }
        .ambg[data-running='false'] .ambg-lines { animation-play-state: paused; }
        .ambg-copy { position: relative; padding: 2rem; }
        .ambg-slider { display: flex; align-items: center; gap: .75rem; justify-content: center;
                       margin-block-start: 1.25rem; font-size: .8rem; color: #d8b4fe; }
        .ambg-slider input { accent-color: #f0abfc; inline-size: min(14rem, 60vw); }
        @media (prefers-reduced-motion: reduce) { .ambg-lines { animation: none; } }
        </style>
        SVELTE,
        ],
    ],

    'starfield' => [
        'title' => ['fa' => 'میدان ستاره', 'en' => 'Starfield'],
        'icon' => 'globe',
        'oneLiner' => [
            'fa' => 'بوم دوبعدی پر از ستاره که هرکدام عمق z خودشان را دارند و به سمت بیننده می‌آیند و بزرگ می‌شوند؛ موس میدان را با پارالکس ملایم خم می‌کند و سرتیتر سفر روی آن می‌نشیند. حلقهٔ RAF بیرون از دید پارک و در حرکت کاهش‌یافته به آسمان ساکن می‌رسد.',
            'en' => 'A 2D canvas of stars, each carrying its own z depth, flying at the viewer and growing; the mouse bends the field with a gentle parallax while a travel headline rides on top. The RAF loop parks off screen and settles into a still sky under reduced motion.',
        ],
        'js' => true,
        'docs' => 'https://scrolltide.co',
        'props' => [
            ['name' => 'stars', 'type' => 'count', 'default' => "'180'", 'note' => [
                'fa' => 'تعداد ستاره‌ها؛ تراکم با مساحت بوم مقیاس می‌شود تا موبایل شلوغ نشود.',
                'en' => 'The star count; density scales with canvas area so mobile stays calm.',
            ]],
            ['name' => 'z-speed', 'type' => 'px/frame', 'default' => "'0.9'", 'note' => [
                'fa' => 'سرعت نزدیک‌شدن ستاره‌ها؛ کسری از عمق در هر فریم که با dt نرمال شده است.',
                'en' => 'How fast stars approach; a fraction of depth per frame, dt-normalised.',
            ]],
            ['name' => 'parallax', 'type' => 'px', 'default' => "'± 26'", 'note' => [
                'fa' => 'جابه‌جایی نرم مرکز تصویر با موس؛ روی لمس و کیبورد صفر می‌ماند.',
                'en' => 'The eased vanishing-point shift with the mouse; stays zero for touch and keyboard.',
            ]],
            ['name' => 'size', 'type' => 'formula', 'default' => "'r ∝ 1/z'", 'note' => [
                'fa' => 'شعاع و روشنایی هر ستاره از عمق می‌آید؛ نزدیک‌ها درشت و درخشان‌اند.',
                'en' => 'Each star’s radius and brightness derive from depth; the near ones are fat and bright.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <section class="ambs" data-running="true">
            <canvas class="ambs-cv"></canvas>
            <h2>هیچ‌جا دور نیست</h2>
            <p>پروازهای شب‌ران به ۴۲ مقصد، از ۹۹ دلار.</p>
        </section>

        <style>
        .ambs { position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 22rem;
                display: grid; place-content: center; text-align: center;
                background: #05070f; color: #e2e8f0; }
        .ambs-cv { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
        </style>

        <script>
        // ستاره‌ها با عمق z به بیننده می‌آیند؛ فقط همین بخش جاوااسکریپت دارد.
        new Starfield(document.querySelector('.ambs'), { stars: 180, speed: .9 });
        </script>
        BLADE,
                'en' => <<<'BLADE'
        <section class="ambs" data-running="true">
        <canvas class="ambs-cv"></canvas>
        <h2>Nowhere is far anymore</h2>
        <p>Red-eye flights to 42 destinations, from $99.</p>
        </section>

        <style>
        .ambs { position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 22rem;
        display: grid; place-content: center; text-align: center;
        background: #05070f; color: #e2e8f0; }
        .ambs-cv { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
        </style>

        <script>
        // Stars fly at the viewer on a z depth; this is the only JS part.
        new Starfield(document.querySelector('.ambs'), { stars: 180, speed: .9 });
        </script>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Starfield.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import StarfieldHero from '@/components/StarfieldHero';

        export default function Starfield() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <StarfieldHero />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // StarfieldHero.jsx — canvas starfield: z-depth stars flying at the viewer
        import { useEffect, useRef } from 'react';
        import '@nabuxai/ui-core/css';

        const STAR_COUNT = 180;

        export default function StarfieldHero() {
          const canvasRef = useRef(null);

          useEffect(() => {
            const canvas = canvasRef.current;
            const ctx = canvas.getContext('2d');
            if (!ctx) return;
            const dpr = Math.min(devicePixelRatio || 1, 2);
            let raf = 0, visible = true, w = 0, h = 0, last = performance.now();
            const par = { tx: 0, x: 0, ty: 0, y: 0 };
            let stars = [];

            const resize = () => {
              w = canvas.clientWidth; h = canvas.clientHeight;
              canvas.width = w * dpr; canvas.height = h * dpr;
              ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
              stars = Array.from({ length: STAR_COUNT }, () => ({
                x: (Math.random() - 0.5) * w * 2,
                y: (Math.random() - 0.5) * h * 2,
                z: Math.random() * w,
              }));
            };
            const onMove = (e) => {
              const r = canvas.getBoundingClientRect();
              par.tx = ((e.clientX - r.left) / r.width - 0.5) * 26;
              par.ty = ((e.clientY - r.top) / r.height - 0.5) * 26;
            };
            const draw = (now) => {
              const dt = Math.min((now - last) / 16.6, 3); last = now;
              par.x += (par.tx - par.x) * 0.06 * dt;
              par.y += (par.ty - par.y) * 0.06 * dt;
              ctx.fillStyle = '#05070f';
              ctx.fillRect(0, 0, w, h);
              const cx = w / 2 + par.x, cy = h / 2 + par.y;
              for (const s of stars) {
                s.z -= 0.9 * dt;
                if (s.z <= 1) { s.z = w; s.x = (Math.random() - 0.5) * w * 2; s.y = (Math.random() - 0.5) * h * 2; }
                const k = 0.5 / s.z, sx = cx + s.x * k * w, sy = cy + s.y * k * h;
                const r = Math.max(0.4, (1 - s.z / w) * 2.2), a = Math.min(1, (1 - s.z / w) * 1.4 + .12);
                ctx.beginPath(); ctx.arc(sx, sy, r, 0, 6.2832);
                ctx.fillStyle = `rgba(226,232,240,${a})`; ctx.fill();
              }
              raf = requestAnimationFrame(draw);
            };
            const reduced = matchMedia('(prefers-reduced-motion: reduce)');
            const io = new IntersectionObserver(([e]) => {
              visible = e.isIntersecting;
              cancelAnimationFrame(raf);
              if (visible && !reduced.matches) raf = requestAnimationFrame((t) => { last = t; draw(t); });
            }, { threshold: 0.15 });

            resize();
            io.observe(canvas);
            canvas.addEventListener('pointermove', onMove);
            if (reduced.matches) draw(performance.now()); // one still frame, loop parks
            else raf = requestAnimationFrame((t) => { last = t; draw(t); });
            const ro = new ResizeObserver(resize); ro.observe(canvas);

            return () => {
              cancelAnimationFrame(raf); io.disconnect(); ro.disconnect();
              canvas.removeEventListener('pointermove', onMove);
            };
          }, []);

          return (
            <>
              <style>{`
                .ambs { position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 22rem;
                        display: grid; place-content: center; text-align: center; background: #05070f; color: #e2e8f0; }
                .ambs-cv { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
                .ambs-copy { position: relative; padding: 2rem; }
              `}</style>
              <section className="ambs">
                <canvas ref={canvasRef} className="ambs-cv" aria-hidden="true" />
                <div className="ambs-copy">
                  <h2>Nowhere is far anymore</h2>
                  <p>Red-eye flights to 42 destinations, from $99.</p>
                </div>
              </section>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- StarfieldHero.vue — canvas starfield: z-depth stars flying at the viewer -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const STAR_COUNT = 180;
        const canvas = ref<HTMLCanvasElement | null>(null);
        let cleanup: (() => void) | null = null;

        onMounted(() => {
          const cv = canvas.value!;
          const ctx = cv.getContext('2d');
          if (!ctx) return;
          const dpr = Math.min(devicePixelRatio || 1, 2);
          let raf = 0, visible = true, w = 0, h = 0, last = performance.now();
          const par = { tx: 0, x: 0, ty: 0, y: 0 };
          let stars: { x: number; y: number; z: number }[] = [];

          const resize = () => {
            w = cv.clientWidth; h = cv.clientHeight;
            cv.width = w * dpr; cv.height = h * dpr;
            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
            stars = Array.from({ length: STAR_COUNT }, () => ({
              x: (Math.random() - 0.5) * w * 2, y: (Math.random() - 0.5) * h * 2, z: Math.random() * w,
            }));
          };
          const onMove = (e: PointerEvent) => {
            const r = cv.getBoundingClientRect();
            par.tx = ((e.clientX - r.left) / r.width - 0.5) * 26;
            par.ty = ((e.clientY - r.top) / r.height - 0.5) * 26;
          };
          const draw = (now: number) => {
            const dt = Math.min((now - last) / 16.6, 3); last = now;
            par.x += (par.tx - par.x) * 0.06 * dt; par.y += (par.ty - par.y) * 0.06 * dt;
            ctx.fillStyle = '#05070f'; ctx.fillRect(0, 0, w, h);
            const cx = w / 2 + par.x, cy = h / 2 + par.y;
            for (const s of stars) {
              s.z -= 0.9 * dt;
              if (s.z <= 1) { s.z = w; s.x = (Math.random() - 0.5) * w * 2; s.y = (Math.random() - 0.5) * h * 2; }
              const k = 0.5 / s.z, sx = cx + s.x * k * w, sy = cy + s.y * k * h;
              const r = Math.max(0.4, (1 - s.z / w) * 2.2), a = Math.min(1, (1 - s.z / w) * 1.4 + .12);
              ctx.beginPath(); ctx.arc(sx, sy, r, 0, 6.2832);
              ctx.fillStyle = `rgba(226,232,240,${a})`; ctx.fill();
            }
            raf = requestAnimationFrame(draw);
          };
          const reduced = matchMedia('(prefers-reduced-motion: reduce)');
          const start = (t: number) => { last = t; draw(t); };
          const io = new IntersectionObserver(([e]) => {
            visible = e.isIntersecting;
            cancelAnimationFrame(raf);
            if (visible && !reduced.matches) raf = requestAnimationFrame(start);
          }, { threshold: 0.15 });

          resize(); io.observe(cv); cv.addEventListener('pointermove', onMove);
          if (reduced.matches) draw(performance.now()); // one still frame
          else raf = requestAnimationFrame(start);
          const ro = new ResizeObserver(resize); ro.observe(cv);

          cleanup = () => {
            cancelAnimationFrame(raf); io.disconnect(); ro.disconnect();
            cv.removeEventListener('pointermove', onMove);
          };
        });
        onBeforeUnmount(() => cleanup?.());
        </script>

        <template>
          <section class="ambs">
            <canvas ref="canvas" class="ambs-cv" aria-hidden="true"></canvas>
            <div class="ambs-copy">
              <h2>Nowhere is far anymore</h2>
              <p>Red-eye flights to 42 destinations, from $99.</p>
            </div>
          </section>
        </template>

        <style scoped>
        .ambs { position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 22rem;
                display: grid; place-content: center; text-align: center; background: #05070f; color: #e2e8f0; }
        .ambs-cv { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
        .ambs-copy { position: relative; padding: 2rem; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- StarfieldHero.svelte — canvas starfield: z-depth stars flying at the viewer -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          const STAR_COUNT = 180;
          let canvas: HTMLCanvasElement;

          onMount(() => {
            const ctx = canvas.getContext('2d');
            if (!ctx) return;
            const dpr = Math.min(devicePixelRatio || 1, 2);
            let raf = 0, visible = true, w = 0, h = 0, last = performance.now();
            const par = { tx: 0, x: 0, ty: 0, y: 0 };
            let stars: { x: number; y: number; z: number }[] = [];

            const resize = () => {
              w = canvas.clientWidth; h = canvas.clientHeight;
              canvas.width = w * dpr; canvas.height = h * dpr;
              ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
              stars = Array.from({ length: STAR_COUNT }, () => ({
                x: (Math.random() - 0.5) * w * 2, y: (Math.random() - 0.5) * h * 2, z: Math.random() * w,
              }));
            };
            const onMove = (e: PointerEvent) => {
              const r = canvas.getBoundingClientRect();
              par.tx = ((e.clientX - r.left) / r.width - 0.5) * 26;
              par.ty = ((e.clientY - r.top) / r.height - 0.5) * 26;
            };
            const draw = (now: number) => {
              const dt = Math.min((now - last) / 16.6, 3); last = now;
              par.x += (par.tx - par.x) * 0.06 * dt; par.y += (par.ty - par.y) * 0.06 * dt;
              ctx.fillStyle = '#05070f'; ctx.fillRect(0, 0, w, h);
              const cx = w / 2 + par.x, cy = h / 2 + par.y;
              for (const s of stars) {
                s.z -= 0.9 * dt;
                if (s.z <= 1) { s.z = w; s.x = (Math.random() - 0.5) * w * 2; s.y = (Math.random() - 0.5) * h * 2; }
                const k = 0.5 / s.z, sx = cx + s.x * k * w, sy = cy + s.y * k * h;
                const r = Math.max(0.4, (1 - s.z / w) * 2.2), a = Math.min(1, (1 - s.z / w) * 1.4 + .12);
                ctx.beginPath(); ctx.arc(sx, sy, r, 0, 6.2832);
                ctx.fillStyle = `rgba(226,232,240,${a})`; ctx.fill();
              }
              raf = requestAnimationFrame(draw);
            };
            const reduced = matchMedia('(prefers-reduced-motion: reduce)');
            const start = (t: number) => { last = t; draw(t); };
            const io = new IntersectionObserver(([e]) => {
              visible = e.isIntersecting;
              cancelAnimationFrame(raf);
              if (visible && !reduced.matches) raf = requestAnimationFrame(start);
            }, { threshold: 0.15 });

            resize(); io.observe(canvas); canvas.addEventListener('pointermove', onMove);
            if (reduced.matches) draw(performance.now()); // one still frame
            else raf = requestAnimationFrame(start);
            const ro = new ResizeObserver(resize); ro.observe(canvas);

            return () => {
              cancelAnimationFrame(raf); io.disconnect(); ro.disconnect();
              canvas.removeEventListener('pointermove', onMove);
            };
          });
        </script>

        <section class="ambs">
          <canvas bind:this={canvas} class="ambs-cv" aria-hidden="true"></canvas>
          <div class="ambs-copy">
            <h2>Nowhere is far anymore</h2>
            <p>Red-eye flights to 42 destinations, from $99.</p>
          </div>
        </section>

        <style>
        .ambs { position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 22rem;
                display: grid; place-content: center; text-align: center; background: #05070f; color: #e2e8f0; }
        .ambs-cv { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
        .ambs-copy { position: relative; padding: 2rem; }
        </style>
        SVELTE,
        ],
    ],

    'contour-lines' => [
        'title' => ['fa' => 'خطوط توپوگرافی', 'en' => 'Contour lines'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'هشت نوار تراز SVG که هرکدام ترکیب دو سینوس با طول‌موج و سرعت خودشان‌اند و مثل نقشهٔ کوهستان جابه‌جا و فاز می‌گیرند؛ هاور یا فوکوس روی نقطهٔ قله، نوارش را به رنگ ارتفاع می‌برد و کارت گوشه قله را می‌خواند.',
            'en' => 'Eight SVG contour bands, each a mix of two sines with its own wavelength and speed, drift and phase like a mountain survey; hover or focus a summit dot and its band retunes to the elevation colour while the corner card reads the peak out.',
        ],
        'js' => true,
        'docs' => 'https://scrolltide.co',
        'props' => [
            ['name' => 'bands', 'type' => 'count', 'default' => "'8'", 'note' => [
                'fa' => 'تعداد نوارهای تراز؛ فاصلهٔ عمودی، دامنه و ضخامت خط هر نوار خودکار از شاخصش درمی‌آید.',
                'en' => 'The contour count; each band’s spacing, amplitude and stroke width derive from its index.',
            ]],
            ['name' => 'mix', 'type' => 'sines', 'default' => "'2 per band'", 'note' => [
                'fa' => 'هر نوار دو سینوس با طول‌موج و سرعت فاز جدا دارد تا نقشه هرگز تکرار نشود.',
                'en' => 'Every band mixes two sines with separate wavelengths and phase speeds so the map never repeats.',
            ]],
            ['name' => 'hover', 'type' => 'elevation', 'default' => "'amber → red'", 'note' => [
                'fa' => 'هاور/فوکوس نقطهٔ قله نوارش را با رنگ ارتفاع روشن می‌کند و نام و ارتفاع را در کارت می‌خواند.',
                'en' => 'Hovering or focusing a summit dot lights its band with the elevation colour and reads name and metres in the card.',
            ]],
            ['name' => 'pause', 'type' => 'state', 'default' => "'off-screen'", 'note' => [
                'fa' => 'حلقهٔ بازکشیدن با IntersectionObserver بیرون از دید پارک می‌شود؛ حرکت کاهش‌یافته یک نقشهٔ ساکن می‌گیرد.',
                'en' => 'An IntersectionObserver parks the redraw off screen; reduced motion gets one still map.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <section class="ambc">
            <svg class="ambc-map" viewBox="0 0 1200 480" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
                <g class="ambc-g"></g>
            </svg>
            <h2>کوه را مثل نقشه بخوان</h2>
            <p>۴۲ مسیر راهنما در آلپ، آند و آلاسکا.</p>
        </section>

        <style>
        .ambc { position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 22rem;
        display: grid; place-content: center; text-align: center; background: #f8fafc; color: #0f172a; }
        .ambc-map { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
        .ambc-g path { fill: none; stroke: #0f766e; stroke-linecap: round; }
        .ambc-g path.hot { stroke: var(--ambc-hot, #d97706); }
        .ambc-dot { fill: #0f172a; cursor: pointer; }
        .ambc-dot:hover { fill: var(--ambc-hot, #d97706); }
        </style>

        <script>
        // هشت نوار تراز، هر یک ترکیب دو سینوس؛ نقطه‌های قله رنگ ارتفاع می‌دهند.
        (() => {
        const sec = document.querySelector('.ambc'), g = sec.querySelector('.ambc-g');
        const NS = 'http://www.w3.org/2000/svg';
        const bands = Array.from({ length: 8 }, (_, i) => ({
        y: 405 - i * 32, amp: 26 - i * 2.2,
        k1: 2 * Math.PI / (300 - i * 12), k2: 2 * Math.PI / (96 + i * 7),
        s1: .0011 + i * 7e-5, s2: .0019 - i * 6e-5, o2: i * 1.7 }));
        const yAt = (b, x, t) => b.y + b.amp * Math.sin(b.k1 * x + t * b.s1)
        + b.amp * .35 * Math.sin(b.k2 * x + t * b.s2 + b.o2);
        const paths = bands.map((b, i) => {
        const p = document.createElementNS(NS, 'path');
        p.setAttribute('stroke-width', 1 + i * .35); g.appendChild(p); return p; });
        const peaks = [{ band: 5, x: 320, c: '#d97706' }, { band: 6, x: 660, c: '#ea580c' }, { band: 7, x: 950, c: '#dc2626' }];
        const dots = peaks.map((pk) => {
        const d = document.createElementNS(NS, 'circle');
        d.setAttribute('r', '6'); d.setAttribute('class', 'ambc-dot');
        d.addEventListener('pointerenter', () => { sec.style.setProperty('--ambc-hot', pk.c);
        paths[pk.band].classList.add('hot'); });
        d.addEventListener('pointerleave', () => { sec.style.removeProperty('--ambc-hot');
        paths[pk.band].classList.remove('hot'); });
        g.appendChild(d); return { pk, d }; });
        let t = 0, raf = 0, last = performance.now();
        const draw = () => {
        paths.forEach((p, i) => { const b = bands[i]; let dd = '';
        for (let x = -60; x <= 1260; x += 24) dd += (x < 0 ? 'M' : 'L') + x + ' ' + yAt(b, x, t).toFixed(1);
        p.setAttribute('d', dd); });
        dots.forEach((o) => { o.d.setAttribute('cx', o.pk.x);
        o.d.setAttribute('cy', yAt(bands[o.pk.band], o.pk.x, t).toFixed(1)); });
        };
        const reduced = matchMedia('(prefers-reduced-motion: reduce)');
        const loop = (now) => { t += Math.min(now - last, 48); last = now;
        draw(); raf = requestAnimationFrame(loop); };
        const io = new IntersectionObserver(([e]) => { cancelAnimationFrame(raf); last = performance.now();
        if (e.isIntersecting && !reduced.matches) raf = requestAnimationFrame(loop); }, { threshold: .15 });
        draw(); io.observe(sec);
        })();
        </script>
        BLADE,
                'en' => <<<'BLADE'
        <section class="ambc">
        <svg class="ambc-map" viewBox="0 0 1200 480" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
        <g class="ambc-g"></g>
        </svg>
        <h2>Read the mountain like a map</h2>
        <p>42 guided routes across the Alps, the Andes and Alaska.</p>
        </section>

        <style>
        .ambc { position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 22rem;
        display: grid; place-content: center; text-align: center; background: #f8fafc; color: #0f172a; }
        .ambc-map { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
        .ambc-g path { fill: none; stroke: #0f766e; stroke-linecap: round; }
        .ambc-g path.hot { stroke: var(--ambc-hot, #d97706); }
        .ambc-dot { fill: #0f172a; cursor: pointer; }
        .ambc-dot:hover { fill: var(--ambc-hot, #d97706); }
        </style>

        <script>
        // Eight contour bands, two sines each; summit dots hand out elevation colour.
        (() => {
        const sec = document.querySelector('.ambc'), g = sec.querySelector('.ambc-g');
        const NS = 'http://www.w3.org/2000/svg';
        const bands = Array.from({ length: 8 }, (_, i) => ({
        y: 405 - i * 32, amp: 26 - i * 2.2,
        k1: 2 * Math.PI / (300 - i * 12), k2: 2 * Math.PI / (96 + i * 7),
        s1: .0011 + i * 7e-5, s2: .0019 - i * 6e-5, o2: i * 1.7 }));
        const yAt = (b, x, t) => b.y + b.amp * Math.sin(b.k1 * x + t * b.s1)
        + b.amp * .35 * Math.sin(b.k2 * x + t * b.s2 + b.o2);
        const paths = bands.map((b, i) => {
        const p = document.createElementNS(NS, 'path');
        p.setAttribute('stroke-width', 1 + i * .35); g.appendChild(p); return p; });
        const peaks = [{ band: 5, x: 320, c: '#d97706' }, { band: 6, x: 660, c: '#ea580c' }, { band: 7, x: 950, c: '#dc2626' }];
        const dots = peaks.map((pk) => {
        const d = document.createElementNS(NS, 'circle');
        d.setAttribute('r', '6'); d.setAttribute('class', 'ambc-dot');
        d.addEventListener('pointerenter', () => { sec.style.setProperty('--ambc-hot', pk.c);
        paths[pk.band].classList.add('hot'); });
        d.addEventListener('pointerleave', () => { sec.style.removeProperty('--ambc-hot');
        paths[pk.band].classList.remove('hot'); });
        g.appendChild(d); return { pk, d }; });
        let t = 0, raf = 0, last = performance.now();
        const draw = () => {
        paths.forEach((p, i) => { const b = bands[i]; let dd = '';
        for (let x = -60; x <= 1260; x += 24) dd += (x < 0 ? 'M' : 'L') + x + ' ' + yAt(b, x, t).toFixed(1);
        p.setAttribute('d', dd); });
        dots.forEach((o) => { o.d.setAttribute('cx', o.pk.x);
        o.d.setAttribute('cy', yAt(bands[o.pk.band], o.pk.x, t).toFixed(1)); });
        };
        const reduced = matchMedia('(prefers-reduced-motion: reduce)');
        const loop = (now) => { t += Math.min(now - last, 48); last = now;
        draw(); raf = requestAnimationFrame(loop); };
        const io = new IntersectionObserver(([e]) => { cancelAnimationFrame(raf); last = performance.now();
        if (e.isIntersecting && !reduced.matches) raf = requestAnimationFrame(loop); }, { threshold: .15 });
        draw(); io.observe(sec);
        })();
        </script>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Contour.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import ContourHero from '@/components/ContourHero';

        export default function Contour() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <ContourHero />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // ContourHero.jsx — topographic contour lines drifting under the section
        import { useEffect, useRef } from 'react';
        import '@nabuxai/ui-core/css';

        const NS = 'http://www.w3.org/2000/svg';
        const BANDS = Array.from({ length: 8 }, (_, i) => ({
          y: 405 - i * 32, amp: 26 - i * 2.2,
          k1: (2 * Math.PI) / (300 - i * 12), k2: (2 * Math.PI) / (96 + i * 7),
          s1: 0.0011 + i * 7e-5, s2: 0.0019 - i * 6e-5, o2: i * 1.7,
        }));
        const PEAKS = [
          { band: 5, x: 320, c: '#d97706' }, { band: 6, x: 660, c: '#ea580c' }, { band: 7, x: 950, c: '#dc2626' },
        ];
        const yAt = (b, x, t) =>
          b.y + b.amp * Math.sin(b.k1 * x + t * b.s1) + b.amp * 0.35 * Math.sin(b.k2 * x + t * b.s2 + b.o2);

        export default function ContourHero() {
          const gRef = useRef(null);

          // Two sines per band redraw at ~30fps; hover lights a band with elevation colour.
          useEffect(() => {
            const g = gRef.current;
            const paths = BANDS.map((_, i) => {
              const p = document.createElementNS(NS, 'path');
              p.setAttribute('stroke-width', (1 + i * 0.35).toFixed(2));
              g.appendChild(p); return p;
            });
            const dots = PEAKS.map((pk) => {
              const d = document.createElementNS(NS, 'circle');
              d.setAttribute('r', '6'); d.setAttribute('class', 'ambc-dot');
              d.addEventListener('pointerenter', () => {
                g.style.setProperty('--ambc-hot', pk.c); paths[pk.band].classList.add('hot');
              });
              d.addEventListener('pointerleave', () => {
                g.style.removeProperty('--ambc-hot'); paths[pk.band].classList.remove('hot');
              });
              g.appendChild(d); return { pk, d };
            });
            let t = 0, raf = 0, last = performance.now();
            const draw = () => {
              paths.forEach((p, i) => {
                const b = BANDS[i]; let dd = '';
                for (let x = -60; x <= 1260; x += 24) dd += (x < 0 ? 'M' : 'L') + x + ' ' + yAt(b, x, t).toFixed(1);
                p.setAttribute('d', dd);
              });
              dots.forEach(({ pk, d }) => {
                d.setAttribute('cx', pk.x);
                d.setAttribute('cy', yAt(BANDS[pk.band], pk.x, t).toFixed(1));
              });
            };
            const reduced = matchMedia('(prefers-reduced-motion: reduce)');
            const loop = (now) => { t += Math.min(now - last, 48); last = now; draw(); raf = requestAnimationFrame(loop); };
            const io = new IntersectionObserver(([e]) => {
              cancelAnimationFrame(raf); last = performance.now();
              if (e.isIntersecting && !reduced.matches) raf = requestAnimationFrame(loop);
            }, { threshold: 0.15 });
            draw(); io.observe(g);
            return () => { cancelAnimationFrame(raf); io.disconnect(); g.replaceChildren(); };
          }, []);

          return (
            <>
              <style>{`
                .ambc { position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 22rem;
                        display: grid; place-content: center; text-align: center; background: #f8fafc; color: #0f172a; }
                .ambc-map { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
                .ambc-g path { fill: none; stroke: #0f766e; stroke-linecap: round; }
                .ambc-g path.hot { stroke: var(--ambc-hot, #d97706); }
                .ambc-dot { fill: #0f172a; cursor: pointer; }
                .ambc-dot:hover { fill: var(--ambc-hot, #d97706); }
              `}</style>
              <section className="ambc">
                <svg className="ambc-map" viewBox="0 0 1200 480" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
                  <g className="ambc-g" ref={gRef} />
                </svg>
                <h2>Read the mountain like a map</h2>
                <p>42 guided routes across the Alps, the Andes and Alaska.</p>
              </section>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- ContourHero.vue — topographic contour lines drifting under the section -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const NS = 'http://www.w3.org/2000/svg';
        const BANDS = Array.from({ length: 8 }, (_, i) => ({
          y: 405 - i * 32, amp: 26 - i * 2.2,
          k1: (2 * Math.PI) / (300 - i * 12), k2: (2 * Math.PI) / (96 + i * 7),
          s1: 0.0011 + i * 7e-5, s2: 0.0019 - i * 6e-5, o2: i * 1.7,
        }));
        const PEAKS = [
          { band: 5, x: 320, c: '#d97706' }, { band: 6, x: 660, c: '#ea580c' }, { band: 7, x: 950, c: '#dc2626' },
        ];
        const yAt = (b: any, x: number, t: number) =>
          b.y + b.amp * Math.sin(b.k1 * x + t * b.s1) + b.amp * 0.35 * Math.sin(b.k2 * x + t * b.s2 + b.o2);
        const g = ref<SVGGElement | null>(null);
        let cleanup: (() => void) | null = null;

        // Two sines per band redraw at ~30fps; hover lights a band with elevation colour.
        onMounted(() => {
          const el = g.value!;
          const paths = BANDS.map((_: any, i: number) => {
            const p = document.createElementNS(NS, 'path');
            p.setAttribute('stroke-width', (1 + i * 0.35).toFixed(2));
            el.appendChild(p); return p;
          });
          const dots = PEAKS.map((pk) => {
            const d = document.createElementNS(NS, 'circle');
            d.setAttribute('r', '6'); d.setAttribute('class', 'ambc-dot');
            d.addEventListener('pointerenter', () => {
              el.style.setProperty('--ambc-hot', pk.c); paths[pk.band].classList.add('hot');
            });
            d.addEventListener('pointerleave', () => {
              el.style.removeProperty('--ambc-hot'); paths[pk.band].classList.remove('hot');
            });
            el.appendChild(d); return { pk, d };
          });
          let t = 0, raf = 0, last = performance.now();
          const draw = () => {
            paths.forEach((p, i) => {
              const b = BANDS[i]; let dd = '';
              for (let x = -60; x <= 1260; x += 24) dd += (x < 0 ? 'M' : 'L') + x + ' ' + yAt(b, x, t).toFixed(1);
              p.setAttribute('d', dd);
            });
            dots.forEach(({ pk, d }: any) => {
              d.setAttribute('cx', pk.x);
              d.setAttribute('cy', yAt(BANDS[pk.band], pk.x, t).toFixed(1));
            });
          };
          const reduced = matchMedia('(prefers-reduced-motion: reduce)');
          const loop = (now: number) => { t += Math.min(now - last, 48); last = now; draw(); raf = requestAnimationFrame(loop); };
          const io = new IntersectionObserver(([e]) => {
            cancelAnimationFrame(raf); last = performance.now();
            if (e.isIntersecting && !reduced.matches) raf = requestAnimationFrame(loop);
          }, { threshold: 0.15 });
          draw(); io.observe(el);
          cleanup = () => { cancelAnimationFrame(raf); io.disconnect(); el.replaceChildren(); };
        });
        onBeforeUnmount(() => cleanup?.());
        </script>

        <template>
          <section class="ambc">
            <svg class="ambc-map" viewBox="0 0 1200 480" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
              <g ref="g" class="ambc-g"></g>
            </svg>
            <h2>Read the mountain like a map</h2>
            <p>42 guided routes across the Alps, the Andes and Alaska.</p>
          </section>
        </template>

        <style scoped>
        .ambc { position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 22rem;
        display: grid; place-content: center; text-align: center; background: #f8fafc; color: #0f172a; }
        .ambc-map { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
        .ambc-g path { fill: none; stroke: #0f766e; stroke-linecap: round; }
        .ambc-g path.hot { stroke: var(--ambc-hot, #d97706); }
        .ambc-dot { fill: #0f172a; cursor: pointer; }
        .ambc-dot:hover { fill: var(--ambc-hot, #d97706); }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- ContourHero.svelte — topographic contour lines drifting under the section -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          const NS = 'http://www.w3.org/2000/svg';
          const BANDS = Array.from({ length: 8 }, (_, i) => ({
            y: 405 - i * 32, amp: 26 - i * 2.2,
            k1: (2 * Math.PI) / (300 - i * 12), k2: (2 * Math.PI) / (96 + i * 7),
            s1: 0.0011 + i * 7e-5, s2: 0.0019 - i * 6e-5, o2: i * 1.7,
          }));
          const PEAKS = [
            { band: 5, x: 320, c: '#d97706' }, { band: 6, x: 660, c: '#ea580c' }, { band: 7, x: 950, c: '#dc2626' },
          ];
          const yAt = (b: any, x: number, t: number) =>
            b.y + b.amp * Math.sin(b.k1 * x + t * b.s1) + b.amp * 0.35 * Math.sin(b.k2 * x + t * b.s2 + b.o2);
          let g: SVGGElement;

          // Two sines per band redraw at ~30fps; hover lights a band with elevation colour.
          onMount(() => {
            const paths = BANDS.map((_, i) => {
              const p = document.createElementNS(NS, 'path');
              p.setAttribute('stroke-width', (1 + i * 0.35).toFixed(2));
              g.appendChild(p); return p;
            });
            const dots = PEAKS.map((pk) => {
              const d = document.createElementNS(NS, 'circle');
              d.setAttribute('r', '6'); d.setAttribute('class', 'ambc-dot');
              d.addEventListener('pointerenter', () => {
                g.style.setProperty('--ambc-hot', pk.c); paths[pk.band].classList.add('hot');
              });
              d.addEventListener('pointerleave', () => {
                g.style.removeProperty('--ambc-hot'); paths[pk.band].classList.remove('hot');
              });
              g.appendChild(d); return { pk, d };
            });
            let t = 0, raf = 0, last = performance.now();
            const draw = () => {
              paths.forEach((p, i) => {
                const b = BANDS[i]; let dd = '';
                for (let x = -60; x <= 1260; x += 24) dd += (x < 0 ? 'M' : 'L') + x + ' ' + yAt(b, x, t).toFixed(1);
                p.setAttribute('d', dd);
              });
              dots.forEach(({ pk, d }: any) => {
                d.setAttribute('cx', pk.x);
                d.setAttribute('cy', yAt(BANDS[pk.band], pk.x, t).toFixed(1));
              });
            };
            const reduced = matchMedia('(prefers-reduced-motion: reduce)');
            const loop = (now: number) => { t += Math.min(now - last, 48); last = now; draw(); raf = requestAnimationFrame(loop); };
            const io = new IntersectionObserver(([e]) => {
              cancelAnimationFrame(raf); last = performance.now();
              if (e.isIntersecting && !reduced.matches) raf = requestAnimationFrame(loop);
            }, { threshold: 0.15 });
            draw(); io.observe(g);
            return () => { cancelAnimationFrame(raf); io.disconnect(); g.replaceChildren(); };
          });
        </script>

        <section class="ambc">
          <svg class="ambc-map" viewBox="0 0 1200 480" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
            <g bind:this={g} class="ambc-g"></g>
          </svg>
          <h2>Read the mountain like a map</h2>
          <p>42 guided routes across the Alps, the Andes and Alaska.</p>
        </section>

        <style>
        .ambc { position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 22rem;
        display: grid; place-content: center; text-align: center; background: #f8fafc; color: #0f172a; }
        .ambc-map { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
        .ambc-g path { fill: none; stroke: #0f766e; stroke-linecap: round; }
        .ambc-g path.hot { stroke: var(--ambc-hot, #d97706); }
        .ambc-dot { fill: #0f172a; cursor: pointer; }
        .ambc-dot:hover { fill: var(--ambc-hot, #d97706); }
        </style>
        SVELTE,
        ],
    ],

    'metaballs' => [
        'title' => ['fa' => 'متابال‌ها', 'en' => 'Metaballs'],
        'icon' => 'wand',
        'oneLiner' => [
            'fa' => 'پنج گوی چسبنده که در هم ذوب می‌شوند و از هم می‌کشند — یک گروه SVG با feGaussianBlur و آستانهٔ آلفای feColorMatrix آن‌ها را مایع می‌کند و حرکتشان فقط transform است؛ با کلید، دو نسخهٔ روشن و تیرهٔ بخش تحویل می‌شود.',
            'en' => 'Five gooey balls melt into each other and pull apart — one SVG group runs feGaussianBlur plus an feColorMatrix alpha threshold to liquidise them while their wander stays pure transform; the toggle ships both the light and the dark section versions.',
        ],
        'js' => true,
        'docs' => 'https://scrolltide.co',
        'props' => [
            ['name' => 'balls', 'type' => 'count', 'default' => "'5'", 'note' => [
                'fa' => 'تعداد گوی‌ها؛ هرکدام شعاع، جهت و دورهٔ keyframe خودشان را دارند.',
                'en' => 'The ball count; each owns its radius, direction and keyframe period.',
            ]],
            ['name' => '--ambm-blur', 'type' => 'length', 'default' => "'16'", 'note' => [
                'fa' => 'انحراف معیار feGaussianBlur؛ بیشتر = گوی‌های نرم‌تر و چسبنده‌تر.',
                'en' => 'The feGaussianBlur stdDeviation; more = softer, stickier blobs.',
            ]],
            ['name' => 'goo', 'type' => 'matrix', 'default' => "'α × 24 − 12'", 'note' => [
                'fa' => 'آستانهٔ آلفای feColorMatrix که تصمیم می‌گیرد دو گوی از چه نزدیکی در هم بروند.',
                'en' => 'The feColorMatrix alpha threshold that decides how close two balls must lean before they merge.',
            ]],
            ['name' => 'variant', 'type' => 'enum', 'default' => "'light | dark'", 'note' => [
                'fa' => 'دو نسخهٔ بخش: کاغذی روشن با گوی‌های نیلی و شبانه با گوی‌های نورانی در blend صفحه‌نمایش.',
                'en' => 'Two section versions: paper-light with indigo blobs and night-dark with luminous screen-blended ones.',
            ]],
            ['name' => 'pause', 'type' => 'state', 'default' => "'off-screen'", 'note' => [
                'fa' => 'با IntersectionObserver بیرون از دید پارک و در حرکت کاهش‌یافته به شکل نهایی جمود می‌کند.',
                'en' => 'An IntersectionObserver parks the goo off screen; reduced motion freezes it into its final shape.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <section class="ambm" data-variant="light">
            <svg class="ambm-map" viewBox="0 0 1200 480" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
                <defs>
                    <filter id="ambm-goo" x="-20%" y="-20%" width="140%" height="140%">
                        <feGaussianBlur in="SourceGraphic" stdDeviation="16" result="ambm-blur" />
                        <feColorMatrix in="ambm-blur"
                            values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 24 -12" />
                    </filter>
                </defs>
                <g filter="url(#ambm-goo)">
                    <circle class="ambm-ball b1" cx="330" cy="300" r="86" />
                    <circle class="ambm-ball b2" cx="620" cy="190" r="104" />
                    <circle class="ambm-ball b3" cx="900" cy="300" r="92" />
                </g>
            </svg>
            <h2>جایی که کارها در هم می‌آمیزند</h2>
            <p>Meld سندها، گفتگوها و کارها را در یک سطح زنده جمع می‌کند.</p>
        </section>

        <style>
        .ambm { position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 22rem;
        display: grid; place-content: center; text-align: center; background: #f6f7fb; color: #1e1b4b; }
        .ambm[data-variant='dark'] { background: #0b1020; color: #e0e7ff; }
        .ambm-map { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
        .ambm-ball { transform-box: fill-box; transform-origin: center; }
        .ambm-ball.b1 { fill: #6366f1; animation: ambm-w1 24s ease-in-out infinite alternate; }
        .ambm-ball.b2 { fill: #8b5cf6; animation: ambm-w2 19s ease-in-out infinite alternate; }
        .ambm-ball.b3 { fill: #a855f7; animation: ambm-w3 28s ease-in-out infinite alternate; }
        @keyframes ambm-w1 { from { translate: -120px 30px; scale: 1; } to { translate: 210px -60px; scale: 1.3; } }
        @keyframes ambm-w2 { from { translate: 160px -40px; scale: 1.15; } to { translate: -230px 70px; scale: .9; } }
        @keyframes ambm-w3 { from { translate: -90px -70px; scale: .95; } to { translate: 140px 90px; scale: 1.25; } }
        .ambm[data-running='false'] .ambm-ball { animation-play-state: paused; }
        @media (prefers-reduced-motion: reduce) { .ambm-ball { animation: none; } }
        </style>

        <script>
        // گوی‌ها فقط با transform می‌گردند؛ بیرون از دید پارک می‌شوند.
        (() => {
        const sec = document.querySelector('.ambm');
        const io = new IntersectionObserver(([e]) => {
        sec.dataset.running = e.isIntersecting ? 'true' : 'false';
        }, { threshold: .15 });
        io.observe(sec);
        })();
        </script>
        BLADE,
                'en' => <<<'BLADE'
        <section class="ambm" data-variant="light">
        <svg class="ambm-map" viewBox="0 0 1200 480" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
        <defs>
        <filter id="ambm-goo" x="-20%" y="-20%" width="140%" height="140%">
        <feGaussianBlur in="SourceGraphic" stdDeviation="16" result="ambm-blur" />
        <feColorMatrix in="ambm-blur"
        values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 24 -12" />
        </filter>
        </defs>
        <g filter="url(#ambm-goo)">
        <circle class="ambm-ball b1" cx="330" cy="300" r="86" />
        <circle class="ambm-ball b2" cx="620" cy="190" r="104" />
        <circle class="ambm-ball b3" cx="900" cy="300" r="92" />
        </g>
        </svg>
        <h2>Where work melds together</h2>
        <p>Meld pools your docs, threads and tasks into one living surface.</p>
        </section>

        <style>
        .ambm { position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 22rem;
        display: grid; place-content: center; text-align: center; background: #f6f7fb; color: #1e1b4b; }
        .ambm[data-variant='dark'] { background: #0b1020; color: #e0e7ff; }
        .ambm-map { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
        .ambm-ball { transform-box: fill-box; transform-origin: center; }
        .ambm-ball.b1 { fill: #6366f1; animation: ambm-w1 24s ease-in-out infinite alternate; }
        .ambm-ball.b2 { fill: #8b5cf6; animation: ambm-w2 19s ease-in-out infinite alternate; }
        .ambm-ball.b3 { fill: #a855f7; animation: ambm-w3 28s ease-in-out infinite alternate; }
        @keyframes ambm-w1 { from { translate: -120px 30px; scale: 1; } to { translate: 210px -60px; scale: 1.3; } }
        @keyframes ambm-w2 { from { translate: 160px -40px; scale: 1.15; } to { translate: -230px 70px; scale: .9; } }
        @keyframes ambm-w3 { from { translate: -90px -70px; scale: .95; } to { translate: 140px 90px; scale: 1.25; } }
        .ambm[data-running='false'] .ambm-ball { animation-play-state: paused; }
        @media (prefers-reduced-motion: reduce) { .ambm-ball { animation: none; } }
        </style>

        <script>
        // Balls wander on transform only; the goo parks off screen.
        (() => {
        const sec = document.querySelector('.ambm');
        const io = new IntersectionObserver(([e]) => {
        sec.dataset.running = e.isIntersecting ? 'true' : 'false';
        }, { threshold: .15 });
        io.observe(sec);
        })();
        </script>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Meld.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import MeldHero from '@/components/MeldHero';

        export default function Meld() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <MeldHero />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // MeldHero.jsx — gooey metaballs behind the section headline, light & dark
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        export default function MeldHero() {
          const [dark, setDark] = useState(false);
          const [running, setRunning] = useState(true);
          const sectionRef = useRef(null);

          // Park the goo off screen; reduced motion freezes it via CSS.
          useEffect(() => {
            const io = new IntersectionObserver(
              ([e]) => setRunning(e.isIntersecting),
              { threshold: 0.15 },
            );
            io.observe(sectionRef.current);
            return () => io.disconnect();
          }, []);

          return (
            <>
              <style>{`
                .ambm { position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 22rem;
                        display: grid; place-content: center; text-align: center; background: #f6f7fb;
                        color: #1e1b4b; transition: background-color .35s ease, color .35s ease; }
                .ambm[data-variant='dark'] { background: #0b1020; color: #e0e7ff; }
                .ambm-map { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
                .ambm-ball { transform-box: fill-box; transform-origin: center; will-change: transform; }
                .ambm-ball.b1 { fill: #6366f1; animation: ambm-w1 24s ease-in-out infinite alternate; }
                .ambm-ball.b2 { fill: #8b5cf6; animation: ambm-w2 19s ease-in-out infinite alternate; }
                .ambm-ball.b3 { fill: #a855f7; animation: ambm-w3 28s ease-in-out infinite alternate; }
                @keyframes ambm-w1 { from { translate: -120px 30px; scale: 1; } to { translate: 210px -60px; scale: 1.3; } }
                @keyframes ambm-w2 { from { translate: 160px -40px; scale: 1.15; } to { translate: -230px 70px; scale: .9; } }
                @keyframes ambm-w3 { from { translate: -90px -70px; scale: .95; } to { translate: 140px 90px; scale: 1.25; } }
                .ambm[data-running='false'] .ambm-ball { animation-play-state: paused; }
                .ambm-copy { position: relative; padding: 2rem; }
                .ambm-toggle { display: inline-flex; gap: .3rem; padding: .3rem; border-radius: 999px;
                               background: rgba(30, 27, 75, .08); margin-block-start: 1rem; }
                .ambm-toggle button { padding: .45rem .95rem; border-radius: 999px; border: 0; cursor: pointer;
                                       background: none; font: 600 .78rem system-ui; color: #4c4a6e; }
                .ambm-toggle button[aria-pressed='true'] { background: #4f46e5; color: #fff; }
                @media (prefers-reduced-motion: reduce) { .ambm-ball { animation: none; } }
              `}</style>
              <section className="ambm" ref={sectionRef}
                data-variant={dark ? 'dark' : 'light'} data-running={running}>
                <svg className="ambm-map" viewBox="0 0 1200 480" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
                  <defs>
                    <filter id="ambm-goo" x="-20%" y="-20%" width="140%" height="140%">
                      <feGaussianBlur in="SourceGraphic" stdDeviation="16" result="ambm-blur" />
                      <feColorMatrix in="ambm-blur"
                        values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 24 -12" />
                    </filter>
                  </defs>
                  <g filter="url(#ambm-goo)">
                    <circle className="ambm-ball b1" cx="330" cy="300" r="86" />
                    <circle className="ambm-ball b2" cx="620" cy="190" r="104" />
                    <circle className="ambm-ball b3" cx="900" cy="300" r="92" />
                  </g>
                </svg>
                <div className="ambm-copy">
                  <h2>Where work melds together</h2>
                  <p>Meld pools your docs, threads and tasks into one living surface.</p>
                  <div className="ambm-toggle" role="group" aria-label="Section version">
                    <button type="button" aria-pressed={!dark} onClick={() => setDark(false)}>Light</button>
                    <button type="button" aria-pressed={dark} onClick={() => setDark(true)}>Dark</button>
                  </div>
                </div>
              </section>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- MeldHero.vue — gooey metaballs behind the section headline, light & dark -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const dark = ref(false);
        const running = ref(true);
        const section = ref<HTMLElement | null>(null);
        let io: IntersectionObserver | null = null;

        // Park the goo off screen; reduced motion freezes it via CSS.
        onMounted(() => {
          io = new IntersectionObserver(([e]) => { running.value = e.isIntersecting; }, { threshold: 0.15 });
          if (section.value) io.observe(section.value);
        });
        onBeforeUnmount(() => io?.disconnect());
        </script>

        <template>
          <section ref="section" class="ambm" :data-variant="dark ? 'dark' : 'light'"
            :data-running="running ? 'true' : 'false'">
            <svg class="ambm-map" viewBox="0 0 1200 480" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
              <defs>
                <filter id="ambm-goo" x="-20%" y="-20%" width="140%" height="140%">
                  <feGaussianBlur in="SourceGraphic" stdDeviation="16" result="ambm-blur" />
                  <feColorMatrix in="ambm-blur"
                    values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 24 -12" />
                </filter>
              </defs>
              <g filter="url(#ambm-goo)">
                <circle class="ambm-ball b1" cx="330" cy="300" r="86" />
                <circle class="ambm-ball b2" cx="620" cy="190" r="104" />
                <circle class="ambm-ball b3" cx="900" cy="300" r="92" />
              </g>
            </svg>
            <div class="ambm-copy">
              <h2>Where work melds together</h2>
              <p>Meld pools your docs, threads and tasks into one living surface.</p>
              <div class="ambm-toggle" role="group" aria-label="Section version">
                <button type="button" :aria-pressed="(!dark).toString()" @click="dark = false">Light</button>
                <button type="button" :aria-pressed="dark.toString()" @click="dark = true">Dark</button>
              </div>
            </div>
          </section>
        </template>

        <style scoped>
        .ambm { position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 22rem;
        display: grid; place-content: center; text-align: center; background: #f6f7fb; color: #1e1b4b;
        transition: background-color .35s ease, color .35s ease; }
        .ambm[data-variant='dark'] { background: #0b1020; color: #e0e7ff; }
        .ambm-map { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
        .ambm-ball { transform-box: fill-box; transform-origin: center; will-change: transform; }
        .ambm-ball.b1 { fill: #6366f1; animation: ambm-w1 24s ease-in-out infinite alternate; }
        .ambm-ball.b2 { fill: #8b5cf6; animation: ambm-w2 19s ease-in-out infinite alternate; }
        .ambm-ball.b3 { fill: #a855f7; animation: ambm-w3 28s ease-in-out infinite alternate; }
        @keyframes ambm-w1 { from { translate: -120px 30px; scale: 1; } to { translate: 210px -60px; scale: 1.3; } }
        @keyframes ambm-w2 { from { translate: 160px -40px; scale: 1.15; } to { translate: -230px 70px; scale: .9; } }
        @keyframes ambm-w3 { from { translate: -90px -70px; scale: .95; } to { translate: 140px 90px; scale: 1.25; } }
        .ambm[data-running='false'] .ambm-ball { animation-play-state: paused; }
        .ambm-copy { position: relative; padding: 2rem; }
        .ambm-toggle { display: inline-flex; gap: .3rem; padding: .3rem; border-radius: 999px;
        background: rgba(30, 27, 75, .08); margin-block-start: 1rem; }
        .ambm-toggle button { padding: .45rem .95rem; border-radius: 999px; border: 0; cursor: pointer;
        background: none; font: 600 .78rem system-ui; color: #4c4a6e; }
        .ambm-toggle button[aria-pressed='true'] { background: #4f46e5; color: #fff; }
        @media (prefers-reduced-motion: reduce) { .ambm-ball { animation: none; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- MeldHero.svelte — gooey metaballs behind the section headline, light & dark -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          let dark = $state(false);
          let running = $state(true);
          let section: HTMLElement;

          // Park the goo off screen; reduced motion freezes it via CSS.
          onMount(() => {
            const io = new IntersectionObserver(([e]) => { running = e.isIntersecting; }, { threshold: 0.15 });
            io.observe(section);
            return () => io.disconnect();
          });
        </script>

        <section bind:this={section} class="ambm" data-variant={dark ? 'dark' : 'light'}
          data-running={running ? 'true' : 'false'}>
          <svg class="ambm-map" viewBox="0 0 1200 480" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
            <defs>
              <filter id="ambm-goo" x="-20%" y="-20%" width="140%" height="140%">
                <feGaussianBlur in="SourceGraphic" stdDeviation="16" result="ambm-blur" />
                <feColorMatrix in="ambm-blur"
                  values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 24 -12" />
              </filter>
            </defs>
            <g filter="url(#ambm-goo)">
              <circle class="ambm-ball b1" cx="330" cy="300" r="86"></circle>
              <circle class="ambm-ball b2" cx="620" cy="190" r="104"></circle>
              <circle class="ambm-ball b3" cx="900" cy="300" r="92"></circle>
            </g>
          </svg>
          <div class="ambm-copy">
            <h2>Where work melds together</h2>
            <p>Meld pools your docs, threads and tasks into one living surface.</p>
            <div class="ambm-toggle" role="group" aria-label="Section version">
              <button type="button" aria-pressed={!dark} onclick={() => (dark = false)}>Light</button>
              <button type="button" aria-pressed={dark} onclick={() => (dark = true)}>Dark</button>
            </div>
          </div>
        </section>

        <style>
        .ambm { position: relative; overflow: clip; border-radius: 1.25rem; min-block-size: 22rem;
        display: grid; place-content: center; text-align: center; background: #f6f7fb; color: #1e1b4b;
        transition: background-color .35s ease, color .35s ease; }
        .ambm[data-variant='dark'] { background: #0b1020; color: #e0e7ff; }
        .ambm-map { position: absolute; inset: 0; inline-size: 100%; block-size: 100%; }
        .ambm-ball { transform-box: fill-box; transform-origin: center; will-change: transform; }
        .ambm-ball.b1 { fill: #6366f1; animation: ambm-w1 24s ease-in-out infinite alternate; }
        .ambm-ball.b2 { fill: #8b5cf6; animation: ambm-w2 19s ease-in-out infinite alternate; }
        .ambm-ball.b3 { fill: #a855f7; animation: ambm-w3 28s ease-in-out infinite alternate; }
        @keyframes ambm-w1 { from { translate: -120px 30px; scale: 1; } to { translate: 210px -60px; scale: 1.3; } }
        @keyframes ambm-w2 { from { translate: 160px -40px; scale: 1.15; } to { translate: -230px 70px; scale: .9; } }
        @keyframes ambm-w3 { from { translate: -90px -70px; scale: .95; } to { translate: 140px 90px; scale: 1.25; } }
        .ambm[data-running='false'] .ambm-ball { animation-play-state: paused; }
        .ambm-copy { position: relative; padding: 2rem; }
        .ambm-toggle { display: inline-flex; gap: .3rem; padding: .3rem; border-radius: 999px;
        background: rgba(30, 27, 75, .08); margin-block-start: 1rem; }
        .ambm-toggle button { padding: .45rem .95rem; border-radius: 999px; border: 0; cursor: pointer;
        background: none; font: 600 .78rem system-ui; color: #4c4a6e; }
        .ambm-toggle button[aria-pressed='true'] { background: #4f46e5; color: #fff; }
        @media (prefers-reduced-motion: reduce) { .ambm-ball { animation: none; } }
        </style>
        SVELTE,
        ],
    ],
];
