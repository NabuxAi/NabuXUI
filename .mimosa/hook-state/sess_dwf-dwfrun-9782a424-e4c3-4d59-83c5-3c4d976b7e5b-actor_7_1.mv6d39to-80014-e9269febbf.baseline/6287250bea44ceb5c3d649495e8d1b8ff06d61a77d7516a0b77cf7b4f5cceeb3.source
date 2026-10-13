<?php

/**
 * Demo manifest of the "heroes" group — landing heroes borrowed from the
 * supahero.io gallery and rebuilt as real, interactive skins: the spotlight
 * hero whose halo follows the pointer, the split hero with a tilting product
 * mockup, the logo marquee, the liquid-glass sign-in panel, the word-reveal
 * headline with its rotating word, the asymmetric bento grid and the stats
 * hero with odometer counters. Scenarios live at
 * resources/views/demos/components/heroes/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'هیروها', 'en' => 'Heroes'],

    'spotlight-hero' => [
        'title' => ['fa' => 'هیرو نورافکن', 'en' => 'Spotlight hero'],
        'icon' => 'sparkles',
        'oneLiner' => [
            'fa' => 'هیرو مرکزی روی آسمان تیرهٔ مرکب: سه لکهٔ گرادیانی آرام می‌چرخند و هالهٔ نور با موس جابه‌جا می‌شود — بج بالای تیتر، دو CTA و ردیف اعتماد زیر دکمه‌ها؛ همان اول صفحه، صحنه را روشن می‌کند.',
            'en' => 'A centred hero on an ink-dark sky: three gradient blobs drift calmly while the halo of light travels with your pointer — badge over the title, two CTAs and a trust row under the buttons; the first screen lights the stage.',
        ],
        'js' => true,
        'docs' => 'https://supahero.io',
        'props' => [
            ['name' => '--hrsp-mx · --hrsp-my', 'type' => 'percentage', 'default' => "'50 · 26'", 'note' => [
                'fa' => 'محل نورافکن بر حسب درصد؛ با pointermove روی هیرو به‌روز می‌شود و روی لمس در مرکز می‌ماند.',
                'en' => 'The spotlight position in percent; pointermove over the hero updates it, and on touch it rests at centre.',
            ]],
            ['name' => 'radius', 'type' => 'length', 'default' => "'34rem'", 'note' => [
                'fa' => 'شعاع هاله؛ بزرگ‌تر یعنی نور پخش‌تر و محوتر، کوچک‌تر یعنی پرتو متمرکز.',
                'en' => 'The halo radius; larger spreads a softer beam, smaller focuses the ray.',
            ]],
            ['name' => 'mesh', 'type' => 'layers', 'default' => "'3'", 'note' => [
                'fa' => 'تعداد لکه‌های گرادیانی پس‌زمینه؛ هرکدام با بیضی محو و چرخهٔ ۲۶ تا ۳۸ ثانیه‌ای.',
                'en' => 'The count of background blobs; each a blurred ellipse on a 26–38s cycle.',
            ]],
            ['name' => 'reduced-motion', 'type' => 'media', 'default' => "'pause'", 'note' => [
                'fa' => 'با prefers-reduced-motion لکه‌ها می‌ایستند؛ نور همچنان موس را دنبال می‌کند چون دست کاربر است.',
                'en' => 'Under prefers-reduced-motion the blobs freeze; the light still follows the pointer because it is the user’s hand.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <section class="hrsp" x-data="{
                mx: 50, my: 26,
                track(e) {
                    const r = this.$el.getBoundingClientRect();
                    this.mx = (e.clientX - r.left) / r.width * 100;
                    this.my = (e.clientY - r.top) / r.height * 100;
                },
            }"
            x-on:pointermove="track($event)"
            :style="`--hrsp-mx:${mx}%; --hrsp-my:${my}%`">
            <i class="hrsp-blob" aria-hidden="true"></i>
            <i class="hrsp-blob" aria-hidden="true"></i>
            <div class="hrsp-light" aria-hidden="true"></div>

            <span class="hrsp-badge"><i>✦</i> استرلی ۳٫۰ اکنون عمومی است</span>
            <h1 class="hrsp-title">با روشنایی منتشر کن</h1>
            <p class="hrsp-sub">استرلی هر استقرار را در فرانکفورت، دوبلین و سنگاپور زیر نور می‌گیرد.</p>
            <div class="hrsp-ctas">
                <button class="hrsp-btn">شروع رایگان</button>
                <button class="hrsp-btn hrsp-btn-ghost">تور چهاردقیقه‌ای</button>
            </div>
            <p class="hrsp-trust">SOC 2 · آپ‌تایم ۹۹٫۹۹٪ · بیش از ۴٬۲۰۰ تیم</p>
        </section>

        <style>
        .hrsp { position: relative; overflow: clip; padding: 6rem 1.5rem; text-align: center;
                background: #0b1020; color: #eef1ff; }
        .hrsp-light { position: absolute; inset: 0; pointer-events: none;
                background: radial-gradient(34rem circle at var(--hrsp-mx, 50%) var(--hrsp-my, 26%),
                #8ea2ff33, #8ea2ff0d 45%, transparent 70%); }
        .hrsp-blob { position: absolute; inline-size: 30rem; aspect-ratio: 1; filter: blur(90px);
                border-radius: 50%; background: #4f46e5; opacity: .35;
                animation: hrsp-drift 30s ease-in-out infinite alternate; }
        @keyframes hrsp-drift { to { translate: 8rem -4rem; scale: 1.15; } }
        @media (prefers-reduced-motion: reduce) { .hrsp-blob { animation: none; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <section class="hrsp" x-data="{
                mx: 50, my: 26,
                track(e) {
                    const r = this.$el.getBoundingClientRect();
                    this.mx = (e.clientX - r.left) / r.width * 100;
                    this.my = (e.clientY - r.top) / r.height * 100;
                },
            }"
            x-on:pointermove="track($event)"
            :style="`--hrsp-mx:${mx}%; --hrsp-my:${my}%`">
            <i class="hrsp-blob" aria-hidden="true"></i>
            <i class="hrsp-blob" aria-hidden="true"></i>
            <div class="hrsp-light" aria-hidden="true"></div>

            <span class="hrsp-badge"><i>✦</i> Asterly 3.0 is generally available</span>
            <h1 class="hrsp-title">Ship with the lights on</h1>
            <p class="hrsp-sub">Asterly puts every deploy in Frankfurt, Dublin and Singapore under the beam.</p>
            <div class="hrsp-ctas">
                <button class="hrsp-btn">Start free</button>
                <button class="hrsp-btn hrsp-btn-ghost">Watch the 4-min tour</button>
            </div>
            <p class="hrsp-trust">SOC 2 · 99.99% uptime · 4,200+ teams</p>
        </section>

        <style>
        .hrsp { position: relative; overflow: clip; padding: 6rem 1.5rem; text-align: center;
                background: #0b1020; color: #eef1ff; }
        .hrsp-light { position: absolute; inset: 0; pointer-events: none;
                background: radial-gradient(34rem circle at var(--hrsp-mx, 50%) var(--hrsp-my, 26%),
                #8ea2ff33, #8ea2ff0d 45%, transparent 70%); }
        .hrsp-blob { position: absolute; inline-size: 30rem; aspect-ratio: 1; filter: blur(90px);
                border-radius: 50%; background: #4f46e5; opacity: .35;
                animation: hrsp-drift 30s ease-in-out infinite alternate; }
        @keyframes hrsp-drift { to { translate: 8rem -4rem; scale: 1.15; } }
        @media (prefers-reduced-motion: reduce) { .hrsp-blob { animation: none; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // resources/js/Pages/Landing.jsx — the Inertia (React) page; renders the
        // React snippet below unchanged inside the app’s provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import SpotlightHero from '@/components/heroes/SpotlightHero';

        export default function Landing() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <SpotlightHero />
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // SpotlightHero.jsx — centred hero, halo follows the pointer
        import { useRef, useState } from 'react';

        export default function SpotlightHero() {
          const stage = useRef(null);
          const [beam, setBeam] = useState({ x: 50, y: 26 });

          const track = (e) => {
            const r = stage.current.getBoundingClientRect();
            setBeam({ x: ((e.clientX - r.left) / r.width) * 100, y: ((e.clientY - r.top) / r.height) * 100 });
          };

          return (
            <>
              <style>{`
                .hrsp { position: relative; overflow: clip; padding: 6rem 1.5rem; text-align: center;
                        background: #0b1020; color: #eef1ff; }
                .hrsp-light { position: absolute; inset: 0; pointer-events: none;
                        background: radial-gradient(34rem circle at ${beam.x}% ${beam.y}%,
                        #8ea2ff33, #8ea2ff0d 45%, transparent 70%); }
                .hrsp-blob { position: absolute; inline-size: 30rem; aspect-ratio: 1; filter: blur(90px);
                        border-radius: 50%; background: #4f46e5; opacity: .35;
                        animation: hrsp-drift 30s ease-in-out infinite alternate; }
                @keyframes hrsp-drift { to { translate: 8rem -4rem; scale: 1.15; } }
                .hrsp-title { font: 700 clamp(2.25rem, 6vw, 4.5rem)/1.05 system-ui; margin: 1rem 0; }
                .hrsp-ctas { display: flex; flex-wrap: wrap; gap: .75rem; justify-content: center; }
                @media (prefers-reduced-motion: reduce) { .hrsp-blob { animation: none; } }
              `}</style>
              <section className="hrsp" ref={stage} onPointerMove={track}>
                <i className="hrsp-blob" aria-hidden="true" />
                <i className="hrsp-blob" aria-hidden="true" />
                <div className="hrsp-light" aria-hidden="true" />
                <span className="hrsp-badge">✦ Asterly 3.0 is generally available</span>
                <h1 className="hrsp-title">Ship with the lights on</h1>
                <p className="hrsp-sub">Every deploy in Frankfurt, Dublin and Singapore under the beam.</p>
                <div className="hrsp-ctas">
                  <button className="hrsp-btn">Start free</button>
                  <button className="hrsp-btn hrsp-btn-ghost">Watch the 4-min tour</button>
                </div>
              </section>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- SpotlightHero.vue — centred hero, halo follows the pointer -->
        <script setup>
        import { ref } from 'vue';

        const stage = ref(null);
        const beam = ref({ x: 50, y: 26 });

        function track(e) {
          const r = stage.value.getBoundingClientRect();
          beam.value = { x: ((e.clientX - r.left) / r.width) * 100, y: ((e.clientY - r.top) / r.height) * 100 };
        }
        </script>

        <template>
          <section class="hrsp" ref="stage" @pointermove="track"
                   :style="{ '--hrsp-mx': beam.x + '%', '--hrsp-my': beam.y + '%' }">
            <i class="hrsp-blob" aria-hidden="true"></i>
            <i class="hrsp-blob" aria-hidden="true"></i>
            <div class="hrsp-light" aria-hidden="true"></div>
            <span class="hrsp-badge">✦ Asterly 3.0 is generally available</span>
            <h1 class="hrsp-title">Ship with the lights on</h1>
            <p class="hrsp-sub">Every deploy in Frankfurt, Dublin and Singapore under the beam.</p>
            <div class="hrsp-ctas">
              <button class="hrsp-btn">Start free</button>
              <button class="hrsp-btn hrsp-btn-ghost">Watch the 4-min tour</button>
            </div>
          </section>
        </template>

        <style scoped>
        .hrsp { position: relative; overflow: clip; padding: 6rem 1.5rem; text-align: center;
                background: #0b1020; color: #eef1ff; }
        .hrsp-light { position: absolute; inset: 0; pointer-events: none;
                background: radial-gradient(34rem circle at var(--hrsp-mx, 50%) var(--hrsp-my, 26%),
                #8ea2ff33, #8ea2ff0d 45%, transparent 70%); }
        .hrsp-blob { position: absolute; inline-size: 30rem; aspect-ratio: 1; filter: blur(90px);
                border-radius: 50%; background: #4f46e5; opacity: .35;
                animation: hrsp-drift 30s ease-in-out infinite alternate; }
        @keyframes hrsp-drift { to { translate: 8rem -4rem; scale: 1.15; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- SpotlightHero.svelte — centred hero, halo follows the pointer -->
        <script>
          let stage;
          let beam = $state({ x: 50, y: 26 });

          function track(e) {
            const r = stage.getBoundingClientRect();
            beam = { x: ((e.clientX - r.left) / r.width) * 100, y: ((e.clientY - r.top) / r.height) * 100 };
          }
        </script>

        <section class="hrsp" bind:this={stage} onpointermove={track}
                 style={`--hrsp-mx:${beam.x}%; --hrsp-my:${beam.y}%`}>
          <i class="hrsp-blob" aria-hidden="true"></i>
          <i class="hrsp-blob" aria-hidden="true"></i>
          <div class="hrsp-light" aria-hidden="true"></div>
          <span class="hrsp-badge">✦ Asterly 3.0 is generally available</span>
          <h1 class="hrsp-title">Ship with the lights on</h1>
          <p class="hrsp-sub">Every deploy in Frankfurt, Dublin and Singapore under the beam.</p>
          <div class="hrsp-ctas">
            <button class="hrsp-btn">Start free</button>
            <button class="hrsp-btn hrsp-btn-ghost">Watch the 4-min tour</button>
          </div>
        </section>

        <style>
        .hrsp { position: relative; overflow: clip; padding: 6rem 1.5rem; text-align: center;
                background: #0b1020; color: #eef1ff; }
        .hrsp-light { position: absolute; inset: 0; pointer-events: none;
                background: radial-gradient(34rem circle at var(--hrsp-mx, 50%) var(--hrsp-my, 26%),
                #8ea2ff33, #8ea2ff0d 45%, transparent 70%); }
        .hrsp-blob { position: absolute; inline-size: 30rem; aspect-ratio: 1; filter: blur(90px);
                border-radius: 50%; background: #4f46e5; opacity: .35;
                animation: hrsp-drift 30s ease-in-out infinite alternate; }
        @keyframes hrsp-drift { to { translate: 8rem -4rem; scale: 1.15; } }
        </style>
        SVELTE,
        ],
    ],

    'split-hero' => [
        'title' => ['fa' => 'هیرو دوبخشی', 'en' => 'Split hero'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'متن و CTA یک سو، ماکاپ محصول سوی دیگر — کج‌شده با perspective و در آغوش هالهٔ نرم؛ چیپ‌های قابلیت روی ماکاپ شناورند و با موس تیلت می‌خورند تا محصول، همان اول، دست‌خوردنی به‌نظر برسد.',
            'en' => 'Copy and CTA on one side, the product mockup on the other — tilted in perspective, wrapped in a soft halo; capability chips float over the mockup and tilt with the pointer so the product feels touchable from the first screen.',
        ],
        'js' => true,
        'docs' => 'https://supahero.io',
        'props' => [
            ['name' => 'perspective', 'type' => 'length', 'default' => "'1200px'", 'note' => [
                'fa' => 'عمق صحنه روی قاب ماکاپ؛ کمتر یعنی کجی نمایشی‌تر، بیشتر یعنی ظریف‌تر.',
                'en' => 'The scene depth on the mockup frame; less reads theatrical, more reads subtle.',
            ]],
            ['name' => 'tilt', 'type' => 'degrees', 'default' => "'±4°'", 'note' => [
                'fa' => 'دامنهٔ چرخش ماکاپ با موس روی هر محور؛ چیپ‌ها با ضریب ۱٫۶ برابر بیشتر جابه‌جا می‌شوند (پارالاکس).',
                'en' => 'The mockup’s per-axis pointer rotation; chips travel 1.6× further for parallax.',
            ]],
            ['name' => 'chips', 'type' => 'count', 'default' => "'3'", 'note' => [
                'fa' => 'چیپ‌های قابلیت شناور روی ماکاپ؛ هرکدام با شناوری آرام و تأخیر متفاوت.',
                'en' => 'The capability chips floating over the mockup; each bobs calmly on its own delay.',
            ]],
            ['name' => 'stack', 'type' => 'breakpoint', 'default' => "'< 56.25rem'", 'note' => [
                'fa' => 'زیر ۹۰۰ پیکسل ستون‌ها روی هم می‌نشینند، ماکاپ زیر متن می‌آید و کجی نصف می‌شود.',
                'en' => 'Under 900px the columns stack, the mockup drops below the copy and the tilt halves.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <section class="hrsl" x-data="{
                rx: 0, ry: 0,
                track(e) {
                    const r = this.$el.getBoundingClientRect();
                    this.rx = ((e.clientY - r.top) / r.height - .5) * -8;
                    this.ry = ((e.clientX - r.left) / r.width - .5) * 8;
                },
            }"
            x-on:pointermove="track($event)" x-on:pointerleave="rx = 0; ry = 0"
            :style="`--hrsl-rx:${rx}deg; --hrsl-ry:${ry}deg`">
            <div class="hrsl-copy">
                <h1 class="hrsl-title">کنسولِ تو برای هر انتشار</h1>
                <p class="hrsl-sub">استرلی بین پایپ‌لاین و کاربر می‌نشیند — انتشار تدریجی، تریس و واگرد در یک صفحه.</p>
                <button class="hrsl-btn">شروع ساخت</button>
            </div>
            <div class="hrsl-stage">
                <i class="hrsl-halo" aria-hidden="true"></i>
                <div class="hrsl-mock">
                    <span class="hrsl-bar"><i></i><i></i><i></i><b>app.asterly.io</b></span>
                    <div class="hrsl-row"><i class="hrsl-dot ok"></i> api · v2.14.0 <small>سالم</small></div>
                </div>
                <span class="hrsl-chip">تریس در ۱۲ms</span>
                <span class="hrsl-chip">انتشار بدون داون‌تایم</span>
            </div>
        </section>

        <style>
        .hrsl { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center;
                padding: 4.5rem 1.5rem; }
        .hrsl-stage { perspective: 1200px; }
        .hrsl-mock { transform: rotateY(calc(var(--hrsl-ry, 0deg) - 10deg)) rotateX(var(--hrsl-rx, 0deg));
                     border-radius: 1rem; background: #fff; box-shadow: 0 30px 60px #0b102033;
                     transition: transform .25s ease-out; }
        .hrsl-chip { position: absolute; translate: calc(var(--hrsl-ry, 0deg) * 1.6) calc(var(--hrsl-rx, 0deg) * -1.6);
                     padding: .5rem .875rem; border-radius: 999px; background: #fff;
                     box-shadow: 0 8px 24px #0b102024; }
        @media (prefers-reduced-motion: reduce) { .hrsl-mock { transition: none; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <section class="hrsl" x-data="{
                rx: 0, ry: 0,
                track(e) {
                    const r = this.$el.getBoundingClientRect();
                    this.rx = ((e.clientY - r.top) / r.height - .5) * -8;
                    this.ry = ((e.clientX - r.left) / r.width - .5) * 8;
                },
            }"
            x-on:pointermove="track($event)" x-on:pointerleave="rx = 0; ry = 0"
            :style="`--hrsl-rx:${rx}deg; --hrsl-ry:${ry}deg`">
            <div class="hrsl-copy">
                <h1 class="hrsl-title">Your console for every rollout</h1>
                <p class="hrsl-sub">Asterly sits between your pipeline and your users — progressive delivery, traces and rollback on one plane.</p>
                <button class="hrsl-btn">Start building</button>
            </div>
            <div class="hrsl-stage">
                <i class="hrsl-halo" aria-hidden="true"></i>
                <div class="hrsl-mock">
                    <span class="hrsl-bar"><i></i><i></i><i></i><b>app.asterly.io</b></span>
                    <div class="hrsl-row"><i class="hrsl-dot ok"></i> api · v2.14.0 <small>Healthy</small></div>
                </div>
                <span class="hrsl-chip">Traces in 12ms</span>
                <span class="hrsl-chip">Zero-downtime rollouts</span>
            </div>
        </section>

        <style>
        .hrsl { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center;
                padding: 4.5rem 1.5rem; }
        .hrsl-stage { perspective: 1200px; }
        .hrsl-mock { transform: rotateY(calc(var(--hrsl-ry, 0deg) - 10deg)) rotateX(var(--hrsl-rx, 0deg));
                     border-radius: 1rem; background: #fff; box-shadow: 0 30px 60px #0b102033;
                     transition: transform .25s ease-out; }
        .hrsl-chip { position: absolute; translate: calc(var(--hrsl-ry, 0deg) * 1.6) calc(var(--hrsl-rx, 0deg) * -1.6);
                     padding: .5rem .875rem; border-radius: 999px; background: #fff;
                     box-shadow: 0 8px 24px #0b102024; }
        @media (prefers-reduced-motion: reduce) { .hrsl-mock { transition: none; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // resources/js/Pages/Landing.jsx — the Inertia (React) page; renders the
        // React snippet below unchanged inside the app’s provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import SplitHero from '@/components/heroes/SplitHero';

        export default function Landing() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <SplitHero />
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // SplitHero.jsx — copy one side, tilting mockup + floating chips the other
        import { useRef, useState } from 'react';

        export default function SplitHero() {
          const stage = useRef(null);
          const [tilt, setTilt] = useState({ rx: 0, ry: 0 });

          const track = (e) => {
            const r = stage.current.getBoundingClientRect();
            setTilt({
              rx: ((e.clientY - r.top) / r.height - 0.5) * -8,
              ry: ((e.clientX - r.left) / r.width - 0.5) * 8,
            });
          };

          return (
            <>
              <style>{`
                .hrsl { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center;
                        padding: 4.5rem 1.5rem; }
                .hrsl-stage { position: relative; perspective: 1200px; }
                .hrsl-mock { transform: rotateY(${tilt.ry - 10}deg) rotateX(${tilt.rx}deg);
                        border-radius: 1rem; background: #fff; box-shadow: 0 30px 60px #0b102033;
                        transition: transform .25s ease-out; }
                .hrsl-chip { position: absolute; translate: ${tilt.ry * 1.6}px ${tilt.rx * -1.6}px;
                        padding: .5rem .875rem; border-radius: 999px; background: #fff;
                        box-shadow: 0 8px 24px #0b102024; }
                .hrsl-halo { position: absolute; inset: -10% -15%; border-radius: 50%;
                        background: radial-gradient(#6d7cff40, transparent 65%); filter: blur(40px); }
                @media (prefers-reduced-motion: reduce) { .hrsl-mock { transition: none; } }
              `}</style>
              <section className="hrsl" onPointerMove={track} onPointerLeave={() => setTilt({ rx: 0, ry: 0 })}>
                <div className="hrsl-copy">
                  <h1 className="hrsl-title">Your console for every rollout</h1>
                  <p className="hrsl-sub">Progressive delivery, traces and rollback on one plane.</p>
                  <button className="hrsl-btn">Start building</button>
                </div>
                <div className="hrsl-stage" ref={stage}>
                  <i className="hrsl-halo" aria-hidden="true" />
                  <div className="hrsl-mock">
                    <span className="hrsl-bar"><i /><i /><i /><b>app.asterly.io</b></span>
                    <div className="hrsl-row"><i className="hrsl-dot ok" /> api · v2.14.0 <small>Healthy</small></div>
                  </div>
                  <span className="hrsl-chip">Traces in 12ms</span>
                  <span className="hrsl-chip">Zero-downtime rollouts</span>
                </div>
              </section>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- SplitHero.vue — copy one side, tilting mockup + floating chips the other -->
        <script setup>
        import { ref } from 'vue';

        const stage = ref(null);
        const tilt = ref({ rx: 0, ry: 0 });

        function track(e) {
          const r = stage.value.getBoundingClientRect();
          tilt.value = {
            rx: ((e.clientY - r.top) / r.height - 0.5) * -8,
            ry: ((e.clientX - r.left) / r.width - 0.5) * 8,
          };
        }
        </script>

        <template>
          <section class="hrsl" @pointermove="track" @pointerleave="tilt = { rx: 0, ry: 0 }">
            <div class="hrsl-copy">
              <h1 class="hrsl-title">Your console for every rollout</h1>
              <p class="hrsl-sub">Progressive delivery, traces and rollback on one plane.</p>
              <button class="hrsl-btn">Start building</button>
            </div>
            <div class="hrsl-stage" ref="stage">
              <i class="hrsl-halo" aria-hidden="true"></i>
              <div class="hrsl-mock" :style="{
                transform: `rotateY(${tilt.ry - 10}deg) rotateX(${tilt.rx}deg)`,
                '--hrsl-ry': tilt.ry + 'deg', '--hrsl-rx': tilt.rx + 'deg',
              }">
                <span class="hrsl-bar"><i></i><i></i><i></i><b>app.asterly.io</b></span>
                <div class="hrsl-row"><i class="hrsl-dot ok"></i> api · v2.14.0 <small>Healthy</small></div>
              </div>
              <span class="hrsl-chip">Traces in 12ms</span>
              <span class="hrsl-chip">Zero-downtime rollouts</span>
            </div>
          </section>
        </template>

        <style scoped>
        .hrsl { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center;
                padding: 4.5rem 1.5rem; }
        .hrsl-stage { position: relative; perspective: 1200px; }
        .hrsl-mock { border-radius: 1rem; background: #fff; box-shadow: 0 30px 60px #0b102033;
                transition: transform .25s ease-out; }
        .hrsl-chip { position: absolute; translate: calc(var(--hrsl-ry, 0deg) * 1.6) calc(var(--hrsl-rx, 0deg) * -1.6);
                padding: .5rem .875rem; border-radius: 999px; background: #fff;
                box-shadow: 0 8px 24px #0b102024; }
        .hrsl-halo { position: absolute; inset: -10% -15%; border-radius: 50%;
                background: radial-gradient(#6d7cff40, transparent 65%); filter: blur(40px); }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- SplitHero.svelte — copy one side, tilting mockup + floating chips the other -->
        <script>
          let stage;
          let tilt = $state({ rx: 0, ry: 0 });

          function track(e) {
            const r = stage.getBoundingClientRect();
            tilt = {
              rx: ((e.clientY - r.top) / r.height - 0.5) * -8,
              ry: ((e.clientX - r.left) / r.width - 0.5) * 8,
            };
          }
        </script>

        <section class="hrsl" onpointermove={track} onpointerleave={() => (tilt = { rx: 0, ry: 0 })}
                 style={`--hrsl-rx:${tilt.rx}deg; --hrsl-ry:${tilt.ry}deg`}>
          <div class="hrsl-copy">
            <h1 class="hrsl-title">Your console for every rollout</h1>
            <p class="hrsl-sub">Progressive delivery, traces and rollback on one plane.</p>
            <button class="hrsl-btn">Start building</button>
          </div>
          <div class="hrsl-stage" bind:this={stage}>
            <i class="hrsl-halo" aria-hidden="true"></i>
            <div class="hrsl-mock">
              <span class="hrsl-bar"><i></i><i></i><i></i><b>app.asterly.io</b></span>
              <div class="hrsl-row"><i class="hrsl-dot ok"></i> api · v2.14.0 <small>Healthy</small></div>
            </div>
            <span class="hrsl-chip">Traces in 12ms</span>
            <span class="hrsl-chip">Zero-downtime rollouts</span>
          </div>
        </section>

        <style>
        .hrsl { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center;
                padding: 4.5rem 1.5rem; }
        .hrsl-stage { position: relative; perspective: 1200px; }
        .hrsl-mock { transform: rotateY(calc(var(--hrsl-ry, 0deg) - 10deg)) rotateX(var(--hrsl-rx, 0deg));
                border-radius: 1rem; background: #fff; box-shadow: 0 30px 60px #0b102033;
                transition: transform .25s ease-out; }
        .hrsl-chip { position: absolute; translate: calc(var(--hrsl-ry, 0deg) * 1.6) calc(var(--hrsl-rx, 0deg) * -1.6);
                padding: .5rem .875rem; border-radius: 999px; background: #fff;
                box-shadow: 0 8px 24px #0b102024; }
        .hrsl-halo { position: absolute; inset: -10% -15%; border-radius: 50%;
                background: radial-gradient(#6d7cff40, transparent 65%); filter: blur(40px); }
        </style>
        SVELTE,
        ],
    ],

    'marquee-hero' => [
        'title' => ['fa' => 'هیرو با نوار لوگو', 'en' => 'Logo marquee hero'],
        'icon' => 'globe',
        'oneLiner' => [
            'fa' => 'تیتر درشت و CTA بالا، و زیرشان نوار بی‌نهایتِ لوگوهای سادهٔ SVG که آرام می‌گذرند، با هاور می‌ایستند و دو طرفشان در گرادیان ماسک محو می‌شوند — اعتماد، بی‌ادعا و همیشه در حرکت.',
            'en' => 'A big title and CTA up top, and beneath them the infinite strip of simple SVG logos drifting by — pausing on hover and fading into a gradient mask at both ends; social proof, unboastful and always moving.',
        ],
        'js' => false,
        'docs' => 'https://supahero.io',
        'props' => [
            ['name' => 'duration', 'type' => 'seconds', 'default' => "'32s'", 'note' => [
                'fa' => 'زمان یک دور کامل نوار؛ کند و پیوسته، نه نمایشی و عجولانه.',
                'en' => 'One full pass of the strip; slow and continuous, never showy or hurried.',
            ]],
            ['name' => 'mask', 'type' => 'gradient', 'default' => "'12% · 88%'", 'note' => [
                'fa' => 'ماسک گرادیانی دو طرف نوار؛ لوگوها در لبه‌ها محو می‌شوند تا ورود و خروج بی‌درز باشد.',
                'en' => 'The gradient mask at both ends; logos dissolve at the edges so entries and exits stay seamless.',
            ]],
            ['name' => 'pause', 'type' => 'state', 'default' => "':hover · :focus-within'", 'note' => [
                'fa' => 'نوار با هاور — و با فوکوس کیبورد روی لوگوها — می‌ایستد؛ خالص CSS، بدون جاوااسکریپت.',
                'en' => 'The strip halts on hover — and on keyboard focus within — pure CSS, no JavaScript.',
            ]],
            ['name' => 'loop', 'type' => 'technique', 'default' => "'2 × 50%'", 'note' => [
                'fa' => 'دو گروه هم‌عرض با پدینگ انتهایی برابر؛ translate: -50% حلقه را بی‌درز می‌بندد.',
                'en' => 'Two equal-width groups with matching trailing padding; translate: -50% closes a seamless loop.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <section class="hrm">
            <h1 class="hrm-title">جایی که تیم‌های سریع منتشر می‌کنند</h1>
            <p class="hrm-sub">استرلی لایهٔ انتشارِ تیم‌های مهندسی است — از استارت‌آپ تا فرانچیز جهانی.</p>
            <button class="hrm-btn">شروع رایگان</button>

            <p class="hrm-label">مورد اعتمادِ تیم‌های ارسال در</p>
            <div class="hrm-belt">
                <div class="hrm-track">
                    <div class="hrm-group">… لوگوهای SVG …</div>
                    <div class="hrm-group" aria-hidden="true">… همان لوگوها …</div>
                </div>
            </div>
        </section>

        <style>
        .hrm-belt { overflow: clip; direction: ltr;
                mask-image: linear-gradient(to right, transparent, #000 12%, #000 88%, transparent); }
        .hrm-track { display: flex; inline-size: max-content;
                animation: hrm-scroll 32s linear infinite; }
        .hrm-group { display: flex; gap: 3.5rem; padding-inline-end: 3.5rem; }
        .hrm-belt:hover .hrm-track { animation-play-state: paused; }
        @keyframes hrm-scroll { to { translate: -50% 0; } }
        @media (prefers-reduced-motion: reduce) { .hrm-track { animation: none; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <section class="hrm">
            <h1 class="hrm-title">Where fast teams ship</h1>
            <p class="hrm-sub">Asterly is the rollout layer for engineering teams — from seed-stage to global franchise.</p>
            <button class="hrm-btn">Start free</button>

            <p class="hrm-label">Trusted by shipping teams at</p>
            <div class="hrm-belt">
                <div class="hrm-track">
                    <div class="hrm-group">… SVG logos …</div>
                    <div class="hrm-group" aria-hidden="true">… the same logos …</div>
                </div>
            </div>
        </section>

        <style>
        .hrm-belt { overflow: clip; direction: ltr;
                mask-image: linear-gradient(to right, transparent, #000 12%, #000 88%, transparent); }
        .hrm-track { display: flex; inline-size: max-content;
                animation: hrm-scroll 32s linear infinite; }
        .hrm-group { display: flex; gap: 3.5rem; padding-inline-end: 3.5rem; }
        .hrm-belt:hover .hrm-track { animation-play-state: paused; }
        @keyframes hrm-scroll { to { translate: -50% 0; } }
        @media (prefers-reduced-motion: reduce) { .hrm-track { animation: none; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // resources/js/Pages/Landing.jsx — the Inertia (React) page; renders the
        // React snippet below unchanged inside the app’s provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import MarqueeHero from '@/components/heroes/MarqueeHero';

        export default function Landing() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <MarqueeHero />
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // MarqueeHero.jsx — title + CTA, then the CSS-only logo marquee
        const BRANDS = ['Nordhaus', 'Kitework', 'Fjordline', 'Bluepeak', 'Halden', 'Montra', 'Vektor', 'Auralis'];

        function LogoMark({ name }) {
          return (
            <span className="hrm-logo">
              <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M4 20 12 5l8 15H4Z" fill="none" stroke="currentColor" strokeWidth="2.4" strokeLinejoin="round" />
              </svg>
              <b>{name}</b>
            </span>
          );
        }

        export default function MarqueeHero() {
          return (
            <>
              <style>{`
                .hrm-belt { overflow: clip; direction: ltr;
                        mask-image: linear-gradient(to right, transparent, #000 12%, #000 88%, transparent); }
                .hrm-track { display: flex; inline-size: max-content;
                        animation: hrm-scroll 32s linear infinite; }
                .hrm-group { display: flex; gap: 3.5rem; padding-inline-end: 3.5rem; }
                .hrm-belt:hover .hrm-track { animation-play-state: paused; }
                .hrm-logo { display: inline-flex; align-items: center; gap: .6rem;
                        color: #575d85; font: 600 1.05rem/1 system-ui; }
                .hrm-logo svg { inline-size: 1.5rem; }
                @keyframes hrm-scroll { to { translate: -50% 0; } }
                @media (prefers-reduced-motion: reduce) { .hrm-track { animation: none; } }
              `}</style>
              <section className="hrm">
                <h1 className="hrm-title">Where fast teams ship</h1>
                <button className="hrm-btn">Start free</button>
                <p className="hrm-label">Trusted by shipping teams at</p>
                <div className="hrm-belt">
                  <div className="hrm-track">
                    {/* The second group is the loop’s invisible seam — aria-hidden. */}
                    <div className="hrm-group">{BRANDS.map((b) => <LogoMark key={b} name={b} />)}</div>
                    <div className="hrm-group" aria-hidden="true">{BRANDS.map((b) => <LogoMark key={b} name={b} />)}</div>
                  </div>
                </div>
              </section>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- MarqueeHero.vue — title + CTA, then the CSS-only logo marquee -->
        <script setup>
        const brands = ['Nordhaus', 'Kitework', 'Fjordline', 'Bluepeak', 'Halden', 'Montra', 'Vektor', 'Auralis'];
        </script>

        <template>
          <section class="hrm">
            <h1 class="hrm-title">Where fast teams ship</h1>
            <button class="hrm-btn">Start free</button>
            <p class="hrm-label">Trusted by shipping teams at</p>
            <div class="hrm-belt">
              <div class="hrm-track">
                <!-- The second group is the loop’s invisible seam — aria-hidden. -->
                <div class="hrm-group">
                  <span v-for="b in brands" :key="b" class="hrm-logo">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 20 12 5l8 15H4Z" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linejoin="round" /></svg>
                    <b>{{ b }}</b>
                  </span>
                </div>
                <div class="hrm-group" aria-hidden="true">
                  <span v-for="b in brands" :key="b" class="hrm-logo">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 20 12 5l8 15H4Z" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linejoin="round" /></svg>
                    <b>{{ b }}</b>
                  </span>
                </div>
              </div>
            </div>
          </section>
        </template>

        <style scoped>
        .hrm-belt { overflow: clip; direction: ltr;
                mask-image: linear-gradient(to right, transparent, #000 12%, #000 88%, transparent); }
        .hrm-track { display: flex; inline-size: max-content;
                animation: hrm-scroll 32s linear infinite; }
        .hrm-group { display: flex; gap: 3.5rem; padding-inline-end: 3.5rem; }
        .hrm-belt:hover .hrm-track { animation-play-state: paused; }
        .hrm-logo { display: inline-flex; align-items: center; gap: .6rem;
                color: #575d85; font: 600 1.05rem/1 system-ui; }
        .hrm-logo svg { inline-size: 1.5rem; }
        @keyframes hrm-scroll { to { translate: -50% 0; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- MarqueeHero.svelte — title + CTA, then the CSS-only logo marquee -->
        <script>
          const brands = ['Nordhaus', 'Kitework', 'Fjordline', 'Bluepeak', 'Halden', 'Montra', 'Vektor', 'Auralis'];
        </script>

        <section class="hrm">
          <h1 class="hrm-title">Where fast teams ship</h1>
          <button class="hrm-btn">Start free</button>
          <p class="hrm-label">Trusted by shipping teams at</p>
          <div class="hrm-belt">
            <div class="hrm-track">
              <!-- The second group is the loop’s invisible seam — aria-hidden. -->
              {#each [false, true] as mirror (mirror)}
                <div class="hrm-group" aria-hidden={mirror || undefined}>
                  {#each brands as b (b)}
                    <span class="hrm-logo">
                      <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 20 12 5l8 15H4Z" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linejoin="round" /></svg>
                      <b>{b}</b>
                    </span>
                  {/each}
                </div>
              {/each}
            </div>
          </div>
        </section>

        <style>
        .hrm-belt { overflow: clip; direction: ltr;
                mask-image: linear-gradient(to right, transparent, #000 12%, #000 88%, transparent); }
        .hrm-track { display: flex; inline-size: max-content;
                animation: hrm-scroll 32s linear infinite; }
        .hrm-group { display: flex; gap: 3.5rem; padding-inline-end: 3.5rem; }
        .hrm-belt:hover .hrm-track { animation-play-state: paused; }
        .hrm-logo { display: inline-flex; align-items: center; gap: .6rem;
                color: #575d85; font: 600 1.05rem/1 system-ui; }
        .hrm-logo svg { inline-size: 1.5rem; }
        @keyframes hrm-scroll { to { translate: -50% 0; } }
        </style>
        SVELTE,
        ],
    ],

    'glass-hero' => [
        'title' => ['fa' => 'هیرو شیشه‌ای', 'en' => 'Glass hero'],
        'icon' => 'lock',
        'oneLiner' => [
            'fa' => 'پنل شیشه‌ایِ مایع شناور روی بلاب‌های گرادیانیِ آرام — بلور و سچوریشن پس‌زمینه را می‌نوشد و نور را در لبه‌هایش می‌گیرد؛ داخلش فرم کوتاه ورود با اعتبارسنجی زنده و متن ریز ضمانت.',
            'en' => 'A liquid-glass panel floating over calmly drifting gradient blobs — it drinks the background’s blur and saturation and catches light on its edges; inside, a short sign-in form with live validation and fine-print guarantees.',
        ],
        'js' => true,
        'docs' => 'https://supahero.io',
        'props' => [
            ['name' => 'backdrop-filter', 'type' => 'blur', 'default' => "'blur(22px) saturate(160%)'", 'note' => [
                'fa' => 'قلب ظاهر liquid-glass؛ شیشه پس‌زمینه را مات و اشباع‌تر می‌کند تا بلاب‌ها مثل رنگ از پشت شیشه بدرخشند.',
                'en' => 'The liquid-glass heart; the glass frosts and saturates the background so the blobs glow through like colour behind frosted panes.',
            ]],
            ['name' => 'edge-light', 'type' => 'border', 'default' => "'1px · inset 1px'", 'note' => [
                'fa' => 'حاشیهٔ نیمه‌شفاف و هایلایت داخلی بالای پنل؛ همان لبهٔ نور که شیشهٔ واقعی را می‌سازد.',
                'en' => 'A translucent border plus the inner highlight along the panel’s top — the lit edge real glass has.',
            ]],
            ['name' => 'blobs', 'type' => 'layers', 'default' => "'3'", 'note' => [
                'fa' => 'سه بلاب گرادیانی با چرخهٔ ۲۰ تا ۳۰ ثانیه‌ای؛ با prefers-reduced-motion می‌ایستند.',
                'en' => 'Three gradient blobs on 20–30s cycles; they freeze under prefers-reduced-motion.',
            ]],
            ['name' => 'validation', 'type' => 'event', 'default' => "'submit'", 'note' => [
                'fa' => 'با ارسال فرم ایمیل بررسی می‌شود؛ خطا ورودی را می‌لرزاند و پیام می‌آورد، موفقیت پنل را به تأیید جادویی تبدیل می‌کند.',
                'en' => 'On submit the email is checked; an error shakes the input with a message, success swaps the panel to the magic-link confirmation.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <section class="hrg">
            <i class="hrg-blob" aria-hidden="true"></i>
            <i class="hrg-blob" aria-hidden="true"></i>

            <form class="hrg-panel" x-data="{ email: '', sent: false, shake: false,
                    get bad() { return this.email && !this.email.includes('@') } }"
                x-on:submit.prevent="email.includes('@') ? sent = true : (shake = true, setTimeout(() => shake = false, 400))">
                <h1 class="hrg-title">ورک‌اسپیس‌ات را بساز</h1>
                <input class="hrg-input" type="email" x-model="email" placeholder="you@team.com">
                <button class="hrg-btn">ادامه با لینک جادویی</button>
                <small class="hrg-fine">۱۴ روز رایگان · بدون کارت · SOC 2</small>
                <p x-show="sent">لینک به ایمیلت رفت ✓</p>
            </form>
        </section>

        <style>
        .hrg { position: relative; overflow: clip; display: grid; place-items: center;
               padding: 5rem 1.25rem; background: #0f122e; }
        .hrg-blob { position: absolute; inline-size: 26rem; aspect-ratio: 1; filter: blur(70px);
               border-radius: 50%; background: #6d7cff; opacity: .55;
               animation: hrg-drift 24s ease-in-out infinite alternate; }
        .hrg-panel { inline-size: min(100%, 24rem); padding: 2rem; border-radius: 1.75rem;
               background: #ffffff1a; border: 1px solid #ffffff33; color: #eef1ff;
               backdrop-filter: blur(22px) saturate(160%);
               box-shadow: inset 0 1px 0 #ffffff40, 0 24px 60px #0b102066; }
        .hrg-input { inline-size: 100%; padding: .8rem 1rem; border-radius: .9rem;
               border: 1px solid #ffffff33; background: #ffffff14; color: inherit; }
        @keyframes hrg-drift { to { translate: 6rem -4rem; } }
        @media (prefers-reduced-motion: reduce) { .hrg-blob { animation: none; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <section class="hrg">
            <i class="hrg-blob" aria-hidden="true"></i>
            <i class="hrg-blob" aria-hidden="true"></i>

            <form class="hrg-panel" x-data="{ email: '', sent: false, shake: false,
                    get bad() { return this.email && !this.email.includes('@') } }"
                x-on:submit.prevent="email.includes('@') ? sent = true : (shake = true, setTimeout(() => shake = false, 400))">
                <h1 class="hrg-title">Create your workspace</h1>
                <input class="hrg-input" type="email" x-model="email" placeholder="you@team.com">
                <button class="hrg-btn">Continue with a magic link</button>
                <small class="hrg-fine">14-day trial · no card · SOC 2</small>
                <p x-show="sent">Check your inbox ✓</p>
            </form>
        </section>

        <style>
        .hrg { position: relative; overflow: clip; display: grid; place-items: center;
               padding: 5rem 1.25rem; background: #0f122e; }
        .hrg-blob { position: absolute; inline-size: 26rem; aspect-ratio: 1; filter: blur(70px);
               border-radius: 50%; background: #6d7cff; opacity: .55;
               animation: hrg-drift 24s ease-in-out infinite alternate; }
        .hrg-panel { inline-size: min(100%, 24rem); padding: 2rem; border-radius: 1.75rem;
               background: #ffffff1a; border: 1px solid #ffffff33; color: #eef1ff;
               backdrop-filter: blur(22px) saturate(160%);
               box-shadow: inset 0 1px 0 #ffffff40, 0 24px 60px #0b102066; }
        .hrg-input { inline-size: 100%; padding: .8rem 1rem; border-radius: .9rem;
               border: 1px solid #ffffff33; background: #ffffff14; color: inherit; }
        @keyframes hrg-drift { to { translate: 6rem -4rem; } }
        @media (prefers-reduced-motion: reduce) { .hrg-blob { animation: none; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // resources/js/Pages/Landing.jsx — the Inertia (React) page; renders the
        // React snippet below unchanged inside the app’s provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import GlassHero from '@/components/heroes/GlassHero';

        export default function Landing() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <GlassHero />
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // GlassHero.jsx — liquid-glass sign-in panel over drifting blobs
        import { useState } from 'react';

        export default function GlassHero() {
          const [email, setEmail] = useState('');
          const [sent, setSent] = useState(false);
          const [shake, setShake] = useState(false);

          const submit = (e) => {
            e.preventDefault();
            if (email.includes('@')) setSent(true);
            else {
              setShake(true);
              setTimeout(() => setShake(false), 400);
            }
          };

          return (
            <>
              <style>{`
                .hrg { position: relative; overflow: clip; display: grid; place-items: center;
                        padding: 5rem 1.25rem; background: #0f122e; }
                .hrg-blob { position: absolute; inline-size: 26rem; aspect-ratio: 1; filter: blur(70px);
                        border-radius: 50%; background: #6d7cff; opacity: .55;
                        animation: hrg-drift 24s ease-in-out infinite alternate; }
                .hrg-panel { position: relative; inline-size: min(100%, 24rem); padding: 2rem;
                        border-radius: 1.75rem; background: #ffffff1a; border: 1px solid #ffffff33;
                        color: #eef1ff; backdrop-filter: blur(22px) saturate(160%);
                        box-shadow: inset 0 1px 0 #ffffff40, 0 24px 60px #0b102066; display: grid; gap: 1rem; }
                .hrg-input { inline-size: 100%; padding: .8rem 1rem; border-radius: .9rem;
                        border: 1px solid #ffffff33; background: #ffffff14; color: inherit; }
                .hrg-btn { block-size: 2.9rem; border: none; border-radius: .9rem;
                        background: #eef1ff; color: #0f122e; font-weight: 600; cursor: pointer; }
                .hrg-shake { animation: hrg-shake .4s; }
                @keyframes hrg-drift { to { translate: 6rem -4rem; } }
                @keyframes hrg-shake { 25% { translate: -.4rem 0; } 75% { translate: .4rem 0; } }
                @media (prefers-reduced-motion: reduce) { .hrg-blob { animation: none; } }
              `}</style>
              <section className="hrg">
                <i className="hrg-blob" aria-hidden="true" />
                <i className="hrg-blob" aria-hidden="true" />
                {sent ? (
                  <div className="hrg-panel">
                    <h1 className="hrg-title">Check your inbox</h1>
                    <p>We sent a magic link to <b>{email}</b>. It lives for 15 minutes.</p>
                  </div>
                ) : (
                  <form className="hrg-panel" onSubmit={submit}>
                    <h1 className="hrg-title">Create your workspace</h1>
                    <input className={`hrg-input ${shake ? 'hrg-shake' : ''}`} type="email" value={email}
                           onChange={(e) => setEmail(e.target.value)} placeholder="you@team.com" />
                    <button className="hrg-btn" type="submit">Continue with a magic link</button>
                    <small className="hrg-fine">14-day trial · no card · SOC 2</small>
                  </form>
                )}
              </section>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- GlassHero.vue — liquid-glass sign-in panel over drifting blobs -->
        <script setup>
        import { ref } from 'vue';

        const email = ref('');
        const sent = ref(false);
        const shake = ref(false);

        function submit() {
          if (email.value.includes('@')) sent.value = true;
          else {
            shake.value = true;
            setTimeout(() => (shake.value = false), 400);
          }
        }
        </script>

        <template>
          <section class="hrg">
            <i class="hrg-blob" aria-hidden="true"></i>
            <i class="hrg-blob" aria-hidden="true"></i>
            <div v-if="sent" class="hrg-panel">
              <h1 class="hrg-title">Check your inbox</h1>
              <p>We sent a magic link to <b>{{ email }}</b>. It lives for 15 minutes.</p>
            </div>
            <form v-else class="hrg-panel" @submit.prevent="submit">
              <h1 class="hrg-title">Create your workspace</h1>
              <input class="hrg-input" :class="{ 'hrg-shake': shake }" type="email"
                     v-model="email" placeholder="you@team.com" />
              <button class="hrg-btn" type="submit">Continue with a magic link</button>
              <small class="hrg-fine">14-day trial · no card · SOC 2</small>
            </form>
          </section>
        </template>

        <style scoped>
        .hrg { position: relative; overflow: clip; display: grid; place-items: center;
               padding: 5rem 1.25rem; background: #0f122e; }
        .hrg-blob { position: absolute; inline-size: 26rem; aspect-ratio: 1; filter: blur(70px);
               border-radius: 50%; background: #6d7cff; opacity: .55;
               animation: hrg-drift 24s ease-in-out infinite alternate; }
        .hrg-panel { position: relative; inline-size: min(100%, 24rem); padding: 2rem;
               border-radius: 1.75rem; background: #ffffff1a; border: 1px solid #ffffff33;
               color: #eef1ff; backdrop-filter: blur(22px) saturate(160%);
               box-shadow: inset 0 1px 0 #ffffff40, 0 24px 60px #0b102066; display: grid; gap: 1rem; }
        .hrg-input { inline-size: 100%; padding: .8rem 1rem; border-radius: .9rem;
               border: 1px solid #ffffff33; background: #ffffff14; color: inherit; }
        .hrg-btn { block-size: 2.9rem; border: none; border-radius: .9rem;
               background: #eef1ff; color: #0f122e; font-weight: 600; cursor: pointer; }
        .hrg-shake { animation: hrg-shake .4s; }
        @keyframes hrg-drift { to { translate: 6rem -4rem; } }
        @keyframes hrg-shake { 25% { translate: -.4rem 0; } 75% { translate: .4rem 0; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- GlassHero.svelte — liquid-glass sign-in panel over drifting blobs -->
        <script>
          let email = $state('');
          let sent = $state(false);
          let shake = $state(false);

          function submit(e) {
            e.preventDefault();
            if (email.includes('@')) sent = true;
            else {
              shake = true;
              setTimeout(() => (shake = false), 400);
            }
          }
        </script>

        <section class="hrg">
          <i class="hrg-blob" aria-hidden="true"></i>
          <i class="hrg-blob" aria-hidden="true"></i>
          {#if sent}
            <div class="hrg-panel">
              <h1 class="hrg-title">Check your inbox</h1>
              <p>We sent a magic link to <b>{email}</b>. It lives for 15 minutes.</p>
            </div>
          {:else}
            <form class="hrg-panel" onsubmit={submit}>
              <h1 class="hrg-title">Create your workspace</h1>
              <input class="hrg-input" class:hrg-shake={shake} type="email"
                     bind:value={email} placeholder="you@team.com" />
              <button class="hrg-btn" type="submit">Continue with a magic link</button>
              <small class="hrg-fine">14-day trial · no card · SOC 2</small>
            </form>
          {/if}
        </section>

        <style>
        .hrg { position: relative; overflow: clip; display: grid; place-items: center;
               padding: 5rem 1.25rem; background: #0f122e; }
        .hrg-blob { position: absolute; inline-size: 26rem; aspect-ratio: 1; filter: blur(70px);
               border-radius: 50%; background: #6d7cff; opacity: .55;
               animation: hrg-drift 24s ease-in-out infinite alternate; }
        .hrg-panel { position: relative; inline-size: min(100%, 24rem); padding: 2rem;
               border-radius: 1.75rem; background: #ffffff1a; border: 1px solid #ffffff33;
               color: #eef1ff; backdrop-filter: blur(22px) saturate(160%);
               box-shadow: inset 0 1px 0 #ffffff40, 0 24px 60px #0b102066; display: grid; gap: 1rem; }
        .hrg-input { inline-size: 100%; padding: .8rem 1rem; border-radius: .9rem;
               border: 1px solid #ffffff33; background: #ffffff14; color: inherit; }
        .hrg-btn { block-size: 2.9rem; border: none; border-radius: .9rem;
               background: #eef1ff; color: #0f122e; font-weight: 600; cursor: pointer; }
        .hrg-shake { animation: hrg-shake .4s; }
        @keyframes hrg-drift { to { translate: 6rem -4rem; } }
        @keyframes hrg-shake { 25% { translate: -.4rem 0; } 75% { translate: .4rem 0; } }
        </style>
        SVELTE,
        ],
    ],

    'reveal-hero' => [
        'title' => ['fa' => 'هیرو ظهور کلمه‌به‌کلمه', 'en' => 'Word reveal hero'],
        'icon' => 'wand',
        'oneLiner' => [
            'fa' => 'تیتر کلمه‌به‌کلمه از زیر ماسک بالا می‌آید — هر واژه با تأخیر خودش — و یک واژهٔ چرخان هر چند ثانیه بین سه واژه جابه‌جا می‌شود؛ زیرتیتر و CTA با تأخیر می‌رسند تا صفحه مثل یک تیزر باز شود.',
            'en' => 'The title climbs out from under its mask word by word — each on its own delay — while one rotating word swaps between three every few seconds; subtitle and CTA arrive late so the page opens like a teaser.',
        ],
        'js' => true,
        'docs' => 'https://supahero.io',
        'props' => [
            ['name' => 'stagger', 'type' => 'ms', 'default' => "'90'", 'note' => [
                'fa' => 'فاصلهٔ ظهور واژه‌ها؛ هر واژه با کسرِ ۹۰ms دفعهٔ بعد می‌رسد.',
                'en' => 'The gap between word entrances; each word lands 90ms after its neighbour.',
            ]],
            ['name' => 'rotate', 'type' => 'interval', 'default' => "'2400ms'", 'note' => [
                'fa' => 'چرخهٔ واژهٔ چرخان؛ واژهٔ قبلی به بالا می‌رود و واژهٔ تازه از پایین می‌آید.',
                'en' => 'The rotating word’s cycle; the old word lifts away while the fresh one rises in.',
            ]],
            ['name' => 'mask', 'type' => 'overflow', 'default' => "'clip · 110%'", 'note' => [
                'fa' => 'هر واژه در جعبهٔ سرریز‌گیر می‌نشیند و از ۱۱۰٪ پایین‌تر شروع می‌کند.',
                'en' => 'Each word sits in an overflow-clip box and starts 110% below its resting place.',
            ]],
            ['name' => 'late', 'type' => 'delay', 'default' => "'0.9 · 1.15s'", 'note' => [
                'fa' => 'زیرتیتر و CTA بعد از تیتر می‌رسند؛ با prefers-reduced-motion همه فوری می‌شوند.',
                'en' => 'Subtitle and CTA land after the title; under prefers-reduced-motion everything turns instant.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <section class="hrr" x-data="{ i: 0, prev: 2, run: true,
                words: ['سریع‌تر', 'آرام‌تر', 'با هم'] }"
            x-init="setInterval(() => { prev = i; i = (i + 1) % 3 }, 2400)">
            <h1 class="hrr-title" :data-run="run">
                <span class="hrr-w" style="--i:0"><span class="hrr-wi">منتشر کن</span></span>
                <span class="hrr-flip" aria-live="polite">
                    <template x-for="(w, k) in words" :key="k">
                        <span class="hrr-word" :data-state="k === i ? 'in' : k === prev ? 'out' : 'wait'" x-text="w"></span>
                    </template>
                </span>
            </h1>
            <p class="hrr-sub">استرلی هر قنری را زیر نظر دارد تا تو نداری.</p>
            <button class="hrr-btn">شروع انتشار</button>
        </section>

        <style>
        .hrr-title { display: flex; flex-wrap: wrap; gap: 0 .5ch; }
        .hrr-w { overflow: clip; }
        .hrr-wi { display: inline-block; translate: 0 110%;
               animation: hrr-rise .7s cubic-bezier(.2,.7,.2,1) forwards;
               animation-delay: calc(var(--i) * 90ms); }
        .hrr-flip { display: inline-grid; overflow: clip; }
        .hrr-word { grid-area: 1 / 1; transition: translate .55s, opacity .55s; }
        .hrr-word[data-state='in'] { translate: 0; }
        .hrr-word[data-state='out'] { translate: 0 -110%; opacity: 0; }
        .hrr-word[data-state='wait'] { translate: 0 110%; opacity: 0; }
        @keyframes hrr-rise { to { translate: 0; } }
        @media (prefers-reduced-motion: reduce) {
            .hrr-wi { animation-duration: .01ms; }
            .hrr-word { transition-duration: .01ms; }
        }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <section class="hrr" x-data="{ i: 0, prev: 2, run: true,
                words: ['faster', 'calmer', 'together'] }"
            x-init="setInterval(() => { prev = i; i = (i + 1) % 3 }, 2400)">
            <h1 class="hrr-title" :data-run="run">
                <span class="hrr-w" style="--i:0"><span class="hrr-wi">Ship</span></span>
                <span class="hrr-flip" aria-live="polite">
                    <template x-for="(w, k) in words" :key="k">
                        <span class="hrr-word" :data-state="k === i ? 'in' : k === prev ? 'out' : 'wait'" x-text="w"></span>
                    </template>
                </span>
            </h1>
            <p class="hrr-sub">Asterly watches every canary so you don’t have to.</p>
            <button class="hrr-btn">Start releasing</button>
        </section>

        <style>
        .hrr-title { display: flex; flex-wrap: wrap; gap: 0 .5ch; }
        .hrr-w { overflow: clip; }
        .hrr-wi { display: inline-block; translate: 0 110%;
               animation: hrr-rise .7s cubic-bezier(.2,.7,.2,1) forwards;
               animation-delay: calc(var(--i) * 90ms); }
        .hrr-flip { display: inline-grid; overflow: clip; }
        .hrr-word { grid-area: 1 / 1; transition: translate .55s, opacity .55s; }
        .hrr-word[data-state='in'] { translate: 0; }
        .hrr-word[data-state='out'] { translate: 0 -110%; opacity: 0; }
        .hrr-word[data-state='wait'] { translate: 0 110%; opacity: 0; }
        @keyframes hrr-rise { to { translate: 0; } }
        @media (prefers-reduced-motion: reduce) {
            .hrr-wi { animation-duration: .01ms; }
            .hrr-word { transition-duration: .01ms; }
        }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // resources/js/Pages/Landing.jsx — the Inertia (React) page; renders the
        // React snippet below unchanged inside the app’s provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import RevealHero from '@/components/heroes/RevealHero';

        export default function Landing() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <RevealHero />
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // RevealHero.jsx — words rise from under their masks, one word rotates
        import { useEffect, useState } from 'react';

        const WORDS = ['faster', 'calmer', 'together'];

        export default function RevealHero() {
          const [i, setI] = useState(0);

          useEffect(() => {
            const t = setInterval(() => setI((n) => (n + 1) % WORDS.length), 2400);
            return () => clearInterval(t);
          }, []);

          return (
            <>
              <style>{`
                .hrr-title { display: flex; flex-wrap: wrap; gap: 0 .5ch; margin: 0;
                        font: 800 clamp(2.6rem, 8vw, 5.5rem)/1.05 system-ui; }
                .hrr-w { overflow: clip; }
                .hrr-wi { display: inline-block; translate: 0 110%;
                        animation: hrr-rise .7s cubic-bezier(.2,.7,.2,1) forwards;
                        animation-delay: calc(var(--i) * 90ms); }
                .hrr-flip { display: inline-grid; overflow: clip; }
                .hrr-word { grid-area: 1 / 1; transition: translate .55s, opacity .55s; }
                .hrr-word[data-state='in'] { translate: 0; color: #6d7cff; }
                .hrr-word[data-state='out'] { translate: 0 -110%; opacity: 0; }
                .hrr-word[data-state='wait'] { translate: 0 110%; opacity: 0; }
                @keyframes hrr-rise { to { translate: 0; } }
                @media (prefers-reduced-motion: reduce) {
                  .hrr-wi { animation-duration: .01ms; }
                  .hrr-word { transition-duration: .01ms; }
                }
              `}</style>
              <section className="hrr">
                <h1 className="hrr-title">
                  {['Ship', WORDS, 'than'].map((part, k) =>
                    Array.isArray(part) ? (
                      <span className="hrr-flip" key={k} aria-live="polite">
                        {part.map((w, n) => (
                          <span key={w} className="hrr-word"
                                data-state={n === i ? 'in' : n === (i + part.length - 1) % part.length ? 'out' : 'wait'}>{w}</span>
                        ))}
                      </span>
                    ) : (
                      <span className="hrr-w" key={k} style={{ '--i': k }}>
                        <span className="hrr-wi">{part}</span>
                      </span>
                    )
                  )}
                </h1>
                <p className="hrr-sub">Asterly watches every canary so you don’t have to.</p>
                <button className="hrr-btn">Start releasing</button>
              </section>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- RevealHero.vue — words rise from under their masks, one word rotates -->
        <script setup>
        import { onMounted, onUnmounted, ref } from 'vue';

        const words = ['faster', 'calmer', 'together'];
        const i = ref(0);
        const prev = ref(words.length - 1);
        let timer;

        onMounted(() => {
          timer = setInterval(() => {
            prev.value = i.value;
            i.value = (i.value + 1) % words.length;
          }, 2400);
        });
        onUnmounted(() => clearInterval(timer));
        </script>

        <template>
          <section class="hrr">
            <h1 class="hrr-title">
              <span v-for="(w, k) in ['Ship']" :key="w" class="hrr-w" :style="{ '--i': k }">
                <span class="hrr-wi">{{ w }}</span>
              </span>
              <span class="hrr-flip" aria-live="polite">
                <span v-for="(w, k) in words" :key="w" class="hrr-word"
                      :data-state="k === i ? 'in' : k === prev ? 'out' : 'wait'">{{ w }}</span>
              </span>
            </h1>
            <p class="hrr-sub">Asterly watches every canary so you don’t have to.</p>
            <button class="hrr-btn">Start releasing</button>
          </section>
        </template>

        <style scoped>
        .hrr-title { display: flex; flex-wrap: wrap; gap: 0 .5ch; margin: 0;
                font: 800 clamp(2.6rem, 8vw, 5.5rem)/1.05 system-ui; }
        .hrr-w { overflow: clip; }
        .hrr-wi { display: inline-block; translate: 0 110%;
                animation: hrr-rise .7s cubic-bezier(.2,.7,.2,1) forwards;
                animation-delay: calc(var(--i) * 90ms); }
        .hrr-flip { display: inline-grid; overflow: clip; }
        .hrr-word { grid-area: 1 / 1; transition: translate .55s, opacity .55s; }
        .hrr-word[data-state='in'] { translate: 0; color: #6d7cff; }
        .hrr-word[data-state='out'] { translate: 0 -110%; opacity: 0; }
        .hrr-word[data-state='wait'] { translate: 0 110%; opacity: 0; }
        @keyframes hrr-rise { to { translate: 0; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- RevealHero.svelte — words rise from under their masks, one word rotates -->
        <script>
          const words = ['faster', 'calmer', 'together'];
          let i = $state(0);
          let prev = $state(words.length - 1);

          $effect(() => {
            const t = setInterval(() => {
              prev = i;
              i = (i + 1) % words.length;
            }, 2400);
            return () => clearInterval(t);
          });
        </script>

        <section class="hrr">
          <h1 class="hrr-title">
            <span class="hrr-w" style="--i: 0"><span class="hrr-wi">Ship</span></span>
            <span class="hrr-flip" aria-live="polite">
              {#each words as w, k (w)}
                <span class="hrr-word"
                      data-state={k === i ? 'in' : k === prev ? 'out' : 'wait'}>{w}</span>
              {/each}
            </span>
          </h1>
          <p class="hrr-sub">Asterly watches every canary so you don’t have to.</p>
          <button class="hrr-btn">Start releasing</button>
        </section>

        <style>
        .hrr-title { display: flex; flex-wrap: wrap; gap: 0 .5ch; margin: 0;
                font: 800 clamp(2.6rem, 8vw, 5.5rem)/1.05 system-ui; }
        .hrr-w { overflow: clip; }
        .hrr-wi { display: inline-block; translate: 0 110%;
                animation: hrr-rise .7s cubic-bezier(.2,.7,.2,1) forwards;
                animation-delay: calc(var(--i) * 90ms); }
        .hrr-flip { display: inline-grid; overflow: clip; }
        .hrr-word { grid-area: 1 / 1; transition: translate .55s, opacity .55s; }
        .hrr-word[data-state='in'] { translate: 0; color: #6d7cff; }
        .hrr-word[data-state='out'] { translate: 0 -110%; opacity: 0; }
        .hrr-word[data-state='wait'] { translate: 0 110%; opacity: 0; }
        @keyframes hrr-rise { to { translate: 0; } }
        </style>
        SVELTE,
        ],
    ],

    'bento-hero' => [
        'title' => ['fa' => 'هیرو بنتو', 'en' => 'Bento hero'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'تیتر و CTA کنار گرید بنتوی نامتقارن: کاشی نمودار خطی که دوباره می‌چرخد، کاشی ادغام‌ها با آواتارها و «بیشتر»، کاشی پخش‌کننده‌های تیک‌دار و کاشی آمار شمارنده — هر کاشی یک ریزتعامل زنده.',
            'en' => 'Title and CTA beside an asymmetric bento grid: the line-chart tile that re-rolls, the mergers tile with avatars and its “load more”, the tickable publishers tile and the counting stat tile — every tile one live micro-interaction.',
        ],
        'js' => true,
        'docs' => 'https://supahero.io',
        'props' => [
            ['name' => 'grid', 'type' => 'areas', 'default' => "'3 × 2 asymmetric'", 'note' => [
                'fa' => 'نمودار دو ستون، ادغام‌ها دو ردیف (کاشی بلند)، آمار و پخش‌کننده‌ها تک‌خانه — زیر ۹۰۰ پیکسل همه تک‌ستون می‌شوند.',
                'en' => 'Chart spans two columns, mergers two rows (the tall tile), stat and publishers single cells — under 900px everything drops to one column.',
            ]],
            ['name' => 'chart', 'type' => 'svg', 'default' => "'polyline · 8 points'", 'note' => [
                'fa' => 'نمودار خطی با رسم مونتاژی و نقطهٔ زندهٔ انتها؛ دکمهٔ «دادهٔ تازه» هشت نقطهٔ نو می‌سازد.',
                'en' => 'A polyline that draws itself on mount with a live end dot; the “fresh data” button rolls eight new points.',
            ]],
            ['name' => 'checks', 'type' => 'state', 'default' => "'aria-pressed'", 'note' => [
                'fa' => 'هر پخش‌کننده با یک کلیک تیک می‌خورد/برمی‌گردد و شمارندهٔ سرصفحه همراهش می‌رود.',
                'en' => 'Each publisher ticks and unticks on click, and the header count follows along.',
            ]],
            ['name' => 'counter', 'type' => 'rAF', 'default' => "'1.1s · ease-out'", 'note' => [
                'fa' => 'عدد بزرگ کاشی آمار با requestAnimationFrame تا مقدار می‌شمارد؛ کلیک دوباره می‌شمارد.',
                'en' => 'The stat tile’s big number counts up with requestAnimationFrame; a click counts it again.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <section class="hrb" x-data="{
                points: [22, 40, 30, 55, 48, 70, 62, 84], merged: 3,
                pubs: [{ name: 'npm', ok: true }, { name: 'Docker', ok: true }, { name: 'Helm', ok: false }],
                reroll() { this.points = this.points.map(() => 15 + Math.round(Math.random() * 75)) },
            }">
            <div class="hrb-copy">
                <h1 class="hrb-title">یک صفحهٔ انتشار، همهٔ سیگنال‌ها</h1>
                <button class="hrb-btn">شروع رایگان</button>
            </div>
            <div class="hrb-grid">
                <figure class="hrb-tile hrb-chart">
                    <svg viewBox="0 0 200 80"><polyline class="hrb-line"
                        :points="points.map((p, i) => `${i * 200 / 7},${80 - p * .8}`).join(' ')" /></svg>
                    <button x-on:click="reroll()">دادهٔ تازه</button>
                </figure>
                <ul class="hrb-tile hrb-merges">
                    <template x-for="m in merged"><li><i class="hrb-ava"></i> PR ادغام شد</li></template>
                    <button x-on:click="merged = Math.min(6, merged + 1)">بیشتر</button>
                </ul>
                <ul class="hrb-tile hrb-pubs">
                    <template x-for="(p, k) in pubs" :key="k">
                        <li><button :aria-pressed="p.ok" x-on:click="p.ok = !p.ok">✓ {{ '{{' }}p.name{{ '}}' }}</button></li>
                    </template>
                </ul>
                <div class="hrb-tile hrb-stat"><b>42ms</b><span>p95</span></div>
            </div>
        </section>

        <style>
        .hrb { display: grid; grid-template-columns: 1fr 1.3fr; gap: 3rem; padding: 4.5rem 1.5rem; }
        .hrb-grid { display: grid; grid-template-columns: repeat(3, 1fr); grid-template-rows: auto auto;
                gap: .875rem; }
        .hrb-chart { grid-column: 1 / 3; }
        .hrb-merges { grid-row: 1 / 3; grid-column: 3; }
        .hrb-tile { margin: 0; padding: 1rem; border-radius: 1.25rem; background: #fff;
                border: 1px solid #e3e6f5; box-shadow: 0 10px 30px #0b10200f; list-style: none; }
        .hrb-line { fill: none; stroke: #5b5bd6; stroke-width: 3; stroke-linecap: round;
                transition: points .4s; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <section class="hrb" x-data="{
                points: [22, 40, 30, 55, 48, 70, 62, 84], merged: 3,
                pubs: [{ name: 'npm', ok: true }, { name: 'Docker', ok: true }, { name: 'Helm', ok: false }],
                reroll() { this.points = this.points.map(() => 15 + Math.round(Math.random() * 75)) },
            }">
            <div class="hrb-copy">
                <h1 class="hrb-title">One release plane, every signal</h1>
                <button class="hrb-btn">Start free</button>
            </div>
            <div class="hrb-grid">
                <figure class="hrb-tile hrb-chart">
                    <svg viewBox="0 0 200 80"><polyline class="hrb-line"
                        :points="points.map((p, i) => `${i * 200 / 7},${80 - p * .8}`).join(' ')" /></svg>
                    <button x-on:click="reroll()">Fresh data</button>
                </figure>
                <ul class="hrb-tile hrb-merges">
                    <template x-for="m in merged"><li><i class="hrb-ava"></i> PR merged</li></template>
                    <button x-on:click="merged = Math.min(6, merged + 1)">Load more</button>
                </ul>
                <ul class="hrb-tile hrb-pubs">
                    <template x-for="(p, k) in pubs" :key="k">
                        <li><button :aria-pressed="p.ok" x-on:click="p.ok = !p.ok">✓ {{ '{{' }}p.name{{ '}}' }}</button></li>
                    </template>
                </ul>
                <div class="hrb-tile hrb-stat"><b>42ms</b><span>p95</span></div>
            </div>
        </section>

        <style>
        .hrb { display: grid; grid-template-columns: 1fr 1.3fr; gap: 3rem; padding: 4.5rem 1.5rem; }
        .hrb-grid { display: grid; grid-template-columns: repeat(3, 1fr); grid-template-rows: auto auto;
                gap: .875rem; }
        .hrb-chart { grid-column: 1 / 3; }
        .hrb-merges { grid-row: 1 / 3; grid-column: 3; }
        .hrb-tile { margin: 0; padding: 1rem; border-radius: 1.25rem; background: #fff;
                border: 1px solid #e3e6f5; box-shadow: 0 10px 30px #0b10200f; list-style: none; }
        .hrb-line { fill: none; stroke: #5b5bd6; stroke-width: 3; stroke-linecap: round;
                transition: points .4s; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // resources/js/Pages/Landing.jsx — the Inertia (React) page; renders the
        // React snippet below unchanged inside the app’s provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import BentoHero from '@/components/heroes/BentoHero';

        export default function Landing() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <BentoHero />
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // BentoHero.jsx — asymmetric bento grid, one micro-interaction per tile
        import { useEffect, useState } from 'react';

        const MERGES = [
          ['LH', 'lena · feat: canary weights', '#6d7cff'],
          ['MK', 'marco · fix: fifo queue', '#22d3ee'],
          ['PR', 'priya · chore: deps bump', '#f59e0b'],
          ['TA', 'tomás · feat: dr detector', '#a855f7'],
          ['YS', 'yuki · docs: runbook', '#34d399'],
        ];
        const PUBS = ['npm', 'Docker', 'Helm'];

        export default function BentoHero() {
          const [points, setPoints] = useState([22, 40, 30, 55, 48, 70, 62, 84]);
          const [merged, setMerged] = useState(3);
          const [pubs, setPubs] = useState([true, true, false]);
          const [p95, setP95] = useState(0);

          useEffect(() => { // the stat tile counts up on mount
            let raf; const t0 = performance.now();
            const step = (t) => {
              setP95(Math.round(42 * Math.min(1, (t - t0) / 1100)));
              if (t - t0 < 1100) raf = requestAnimationFrame(step);
            };
            raf = requestAnimationFrame(step);
            return () => cancelAnimationFrame(raf);
          }, []);

          const path = points.map((p, i) => `${(i * 200) / 7},${80 - p * 0.8}`).join(' ');

          return (
            <>
              <style>{`
                .hrb { display: grid; grid-template-columns: 1fr 1.3fr; gap: 3rem; padding: 4.5rem 1.5rem; }
                .hrb-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: .875rem; }
                .hrb-chart { grid-column: 1 / 3; } .hrb-merges { grid-column: 3; grid-row: 1 / 3; }
                .hrb-tile { margin: 0; padding: 1rem; border-radius: 1.25rem; background: #fff;
                        border: 1px solid #e3e6f5; box-shadow: 0 10px 30px #0b10200f; list-style: none;
                        display: grid; gap: .6rem; align-content: start; }
                .hrb-line { fill: none; stroke: #5b5bd6; stroke-width: 3; stroke-linecap: round;
                        stroke-dasharray: 260; animation: hrb-draw 1.2s ease-out forwards; }
                .hrb-ava { inline-size: 1.5rem; aspect-ratio: 1; border-radius: 50%; display: inline-block; }
                .hrb-pub[aria-pressed='true'] { color: #178a50; }
                @keyframes hrb-draw { from { stroke-dashoffset: 260; } }
              `}</style>
              <section className="hrb">
                <div className="hrb-copy">
                  <h1 className="hrb-title">One release plane, every signal</h1>
                  <button className="hrb-btn">Start free</button>
                </div>
                <div className="hrb-grid">
                  <figure className="hrb-tile hrb-chart">
                    <svg viewBox="0 0 200 80" aria-hidden="true"><polyline className="hrb-line" points={path} /></svg>
                    <button onClick={() => setPoints(points.map(() => 15 + Math.round(Math.random() * 75)))}>
                      Fresh data
                    </button>
                  </figure>
                  <ul className="hrb-tile hrb-merges">
                    {MERGES.slice(0, merged).map(([ini, label, color]) => (
                      <li key={ini}><i className="hrb-ava" style={{ background: color }} />{label}</li>
                    ))}
                    <button onClick={() => setMerged(Math.min(MERGES.length, merged + 1))}>Load more</button>
                  </ul>
                  <ul className="hrb-tile hrb-pubs">
                    {PUBS.map((p, k) => (
                      <li key={p}>
                        <button className="hrb-pub" aria-pressed={pubs[k]}
                                onClick={() => setPubs(pubs.map((v, n) => (n === k ? !v : v)))}>✓ {p}</button>
                      </li>
                    ))}
                  </ul>
                  <div className="hrb-tile hrb-stat"><b>{p95}ms</b><span>p95</span></div>
                </div>
              </section>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- BentoHero.vue — asymmetric bento grid, one micro-interaction per tile -->
        <script setup>
        import { onMounted, ref } from 'vue';

        const merges = [
          ['LH', 'lena · feat: canary weights', '#6d7cff'],
          ['MK', 'marco · fix: fifo queue', '#22d3ee'],
          ['PR', 'priya · chore: deps bump', '#f59e0b'],
          ['TA', 'tomás · feat: dr detector', '#a855f7'],
          ['YS', 'yuki · docs: runbook', '#34d399'],
        ];
        const points = ref([22, 40, 30, 55, 48, 70, 62, 84]);
        const merged = ref(3);
        const pubs = ref([{ name: 'npm', ok: true }, { name: 'Docker', ok: true }, { name: 'Helm', ok: false }]);
        const p95 = ref(0);

        function reroll() {
          points.value = points.value.map(() => 15 + Math.round(Math.random() * 75));
        }

        onMounted(() => { // the stat tile counts up
          const t0 = performance.now();
          const step = (t) => {
            p95.value = Math.round(42 * Math.min(1, (t - t0) / 1100));
            if (t - t0 < 1100) requestAnimationFrame(step);
          };
          requestAnimationFrame(step);
        });
        </script>

        <template>
          <section class="hrb">
            <div class="hrb-copy">
              <h1 class="hrb-title">One release plane, every signal</h1>
              <button class="hrb-btn">Start free</button>
            </div>
            <div class="hrb-grid">
              <figure class="hrb-tile hrb-chart">
                <svg viewBox="0 0 200 80" aria-hidden="true">
                  <polyline class="hrb-line"
                    :points="points.map((p, i) => `${(i * 200) / 7},${80 - p * 0.8}`).join(' ')" />
                </svg>
                <button @click="reroll">Fresh data</button>
              </figure>
              <ul class="hrb-tile hrb-merges">
                <li v-for="([ini, label, color]) in merges.slice(0, merged)" :key="ini">
                  <i class="hrb-ava" :style="{ background: color }"></i>{{ label }}
                </li>
                <button @click="merged = Math.min(merges.length, merged + 1)">Load more</button>
              </ul>
              <ul class="hrb-tile hrb-pubs">
                <li v-for="p in pubs" :key="p.name">
                  <button class="hrb-pub" :aria-pressed="p.ok" @click="p.ok = !p.ok">✓ {{ p.name }}</button>
                </li>
              </ul>
              <div class="hrb-tile hrb-stat"><b>{{ p95 }}ms</b><span>p95</span></div>
            </div>
          </section>
        </template>

        <style scoped>
        .hrb { display: grid; grid-template-columns: 1fr 1.3fr; gap: 3rem; padding: 4.5rem 1.5rem; }
        .hrb-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: .875rem; }
        .hrb-chart { grid-column: 1 / 3; } .hrb-merges { grid-column: 3; grid-row: 1 / 3; }
        .hrb-tile { margin: 0; padding: 1rem; border-radius: 1.25rem; background: #fff;
                border: 1px solid #e3e6f5; box-shadow: 0 10px 30px #0b10200f; list-style: none;
                display: grid; gap: .6rem; align-content: start; }
        .hrb-line { fill: none; stroke: #5b5bd6; stroke-width: 3; stroke-linecap: round;
                stroke-dasharray: 260; animation: hrb-draw 1.2s ease-out forwards; }
        .hrb-ava { inline-size: 1.5rem; aspect-ratio: 1; border-radius: 50%; display: inline-block; }
        .hrb-pub[aria-pressed='true'] { color: #178a50; }
        @keyframes hrb-draw { from { stroke-dashoffset: 260; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- BentoHero.svelte — asymmetric bento grid, one micro-interaction per tile -->
        <script>
          const merges = [
            ['LH', 'lena · feat: canary weights', '#6d7cff'],
            ['MK', 'marco · fix: fifo queue', '#22d3ee'],
            ['PR', 'priya · chore: deps bump', '#f59e0b'],
            ['TA', 'tomás · feat: dr detector', '#a855f7'],
            ['YS', 'yuki · docs: runbook', '#34d399'],
          ];
          let points = $state([22, 40, 30, 55, 48, 70, 62, 84]);
          let merged = $state(3);
          let pubs = $state([{ name: 'npm', ok: true }, { name: 'Docker', ok: true }, { name: 'Helm', ok: false }]);
          let p95 = $state(0);

          function reroll() {
            points = points.map(() => 15 + Math.round(Math.random() * 75));
          }

          $effect(() => { // the stat tile counts up
            const t0 = performance.now();
            const step = (t) => {
              p95 = Math.round(42 * Math.min(1, (t - t0) / 1100));
              if (t - t0 < 1100) requestAnimationFrame(step);
            };
            requestAnimationFrame(step);
          });
        </script>

        <section class="hrb">
          <div class="hrb-copy">
            <h1 class="hrb-title">One release plane, every signal</h1>
            <button class="hrb-btn">Start free</button>
          </div>
          <div class="hrb-grid">
            <figure class="hrb-tile hrb-chart">
              <svg viewBox="0 0 200 80" aria-hidden="true">
                <polyline class="hrb-line"
                  points={points.map((p, i) => `${(i * 200) / 7},${80 - p * 0.8}`).join(' ')} />
              </svg>
              <button onclick={reroll}>Fresh data</button>
            </figure>
            <ul class="hrb-tile hrb-merges">
              {#each merges.slice(0, merged) as [ini, label, color] (ini)}
                <li><i class="hrb-ava" style={`background:${color}`}></i>{label}</li>
              {/each}
              <button onclick={() => (merged = Math.min(merges.length, merged + 1))}>Load more</button>
            </ul>
            <ul class="hrb-tile hrb-pubs">
              {#each pubs as p (p.name)}
                <li><button class="hrb-pub" aria-pressed={p.ok} onclick={() => (p.ok = !p.ok)}>✓ {p.name}</button></li>
              {/each}
            </ul>
            <div class="hrb-tile hrb-stat"><b>{p95}ms</b><span>p95</span></div>
          </div>
        </section>

        <style>
        .hrb { display: grid; grid-template-columns: 1fr 1.3fr; gap: 3rem; padding: 4.5rem 1.5rem; }
        .hrb-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: .875rem; }
        .hrb-chart { grid-column: 1 / 3; } .hrb-merges { grid-column: 3; grid-row: 1 / 3; }
        .hrb-tile { margin: 0; padding: 1rem; border-radius: 1.25rem; background: #fff;
                border: 1px solid #e3e6f5; box-shadow: 0 10px 30px #0b10200f; list-style: none;
                display: grid; gap: .6rem; align-content: start; }
        .hrb-line { fill: none; stroke: #5b5bd6; stroke-width: 3; stroke-linecap: round;
                stroke-dasharray: 260; animation: hrb-draw 1.2s ease-out forwards; }
        .hrb-ava { inline-size: 1.5rem; aspect-ratio: 1; border-radius: 50%; display: inline-block; }
        .hrb-pub[aria-pressed='true'] { color: #178a50; }
        @keyframes hrb-draw { from { stroke-dashoffset: 260; } }
        </style>
        SVELTE,
        ],
    ],

    'stats-hero' => [
        'title' => ['fa' => 'هیرو آماری', 'en' => 'Stats hero'],
        'icon' => 'trend-up',
        'oneLiner' => [
            'fa' => 'تیتر و فرم ایمیل inline با اعتبارسنجی و دکمه در یک قرص؛ زیرش سه شمارندهٔ بزرگ اودومتری که تا مقدار می‌شمارند + بج تأیید — همه روی پس‌زمینهٔ نقطه‌ای آرام که عدد را جدی جلوه می‌دهد.',
            'en' => 'Title and an inline email form with validation and a button in one pill; beneath, three big odometer counters rolling to their values plus a verified badge — all on a calm dotted background that makes the numbers feel sworn.',
        ],
        'js' => true,
        'docs' => 'https://supahero.io',
        'props' => [
            ['name' => 'reel', 'type' => 'translate', 'default' => "'-1em × digit'", 'note' => [
                'fa' => 'هر رقم یک قرصل عمودی از ده گلیف است که با translate به رقم می‌رسد؛ جداکننده‌ها ثابت می‌مانند.',
                'en' => 'Each digit is a vertical reel of ten glyphs reaching its digit by translate; separators stay static.',
            ]],
            ['name' => 'stagger', 'type' => 'ms', 'default' => "'120'", 'note' => [
                'fa' => 'هر قرصل با ۱۲۰ms تأخیر می‌چرخد تا حس شمارش واقعی بدهد؛ دکمهٔ بازپخش دوباره می‌شمارد.',
                'en' => 'Each reel turns 120ms after its neighbour for a real counting feel; the replay button counts again.',
            ]],
            ['name' => 'validation', 'type' => 'event', 'default' => "'submit · live'", 'note' => [
                'fa' => 'ایمیل با ورود زنده بررسی می‌شود؛ خطا قرص را می‌لرزاند و پیام می‌آورد، موفقیت فرم را به تأیید می‌برد.',
                'en' => 'The email is checked as you type; an error shakes the pill with a message, success swaps the form to its confirmation.',
            ]],
            ['name' => 'dots', 'type' => 'background', 'default' => "'1px · 1.4rem grid'", 'note' => [
                'fa' => 'شبکهٔ نقطه‌ای آرام با هایلایت شعاعی ملایم؛ بدون حرکت تا عدد حرف اول را بزند.',
                'en' => 'A calm dot grid with a soft radial highlight; motionless so the number does the talking.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <section class="hrst" x-data="{
                run: false, digits: [[9, 9, 9, 9], [4, 2], [2, 4]],
                start() { this.run = true },
            }" x-init="setTimeout(start, 200)">
            <h1 class="hrst-title">عددهای پشتِ انتشارهای آرام</h1>

            <form class="hrst-pill" x-on:submit.prevent="email.includes('@') ? done = true : shake()">
                <input type="email" x-model="email" placeholder="you@team.com">
                <button>شروع رایگان</button>
            </form>

            <div class="hrst-stats">
                <template x-for="(group, g) in digits" :key="g">
                    <div class="hrst-stat">
                        <b class="hrst-num" dir="ltr">
                            <template x-for="(d, i) in group" :key="i">
                                <span class="hrst-reel" :style="`translate: 0 ${-(run ? d : 0)}em; transition-delay: ${i * 120}ms`">
                                    <i>0</i><i>1</i><i>2</i><i>3</i><i>4</i><i>5</i><i>6</i><i>7</i><i>8</i><i>9</i>
                                </span>
                            </template>
                        </b>
                        <small>برچسب</small>
                    </div>
                </template>
            </div>
        </section>

        <style>
        .hrst { display: grid; gap: 1.5rem; justify-items: center; text-align: center; padding: 5rem 1.25rem;
                background-image: radial-gradient(#12152b1f 1px, transparent 1.4px);
                background-size: 1.4rem 1.4rem; }
        .hrst-pill { display: flex; gap: .5rem; padding: .4rem; border-radius: 999px;
                background: #fff; border: 1px solid #e3e6f5; box-shadow: 0 12px 30px #0b10201a; }
        .hrst-num { display: inline-flex; font-size: clamp(2.5rem, 6vw, 4rem); line-height: 1; }
        .hrst-reel { display: inline-block; overflow: clip; block-size: 1em;
                transition: translate 1.2s cubic-bezier(.2,.7,.2,1); }
        .hrst-reel i { display: block; block-size: 1em; font-style: normal; }
        @media (prefers-reduced-motion: reduce) { .hrst-reel { transition-duration: .01ms; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <section class="hrst" x-data="{
                run: false, digits: [[9, 9, 9, 9], [4, 2], [2, 4]],
                start() { this.run = true },
            }" x-init="setTimeout(start, 200)">
            <h1 class="hrst-title">The numbers behind calm releases</h1>

            <form class="hrst-pill" x-on:submit.prevent="email.includes('@') ? done = true : shake()">
                <input type="email" x-model="email" placeholder="you@team.com">
                <button>Start free</button>
            </form>

            <div class="hrst-stats">
                <template x-for="(group, g) in digits" :key="g">
                    <div class="hrst-stat">
                        <b class="hrst-num" dir="ltr">
                            <template x-for="(d, i) in group" :key="i">
                                <span class="hrst-reel" :style="`translate: 0 ${-(run ? d : 0)}em; transition-delay: ${i * 120}ms`">
                                    <i>0</i><i>1</i><i>2</i><i>3</i><i>4</i><i>5</i><i>6</i><i>7</i><i>8</i><i>9</i>
                                </span>
                            </template>
                        </b>
                        <small>label</small>
                    </div>
                </template>
            </div>
        </section>

        <style>
        .hrst { display: grid; gap: 1.5rem; justify-items: center; text-align: center; padding: 5rem 1.25rem;
                background-image: radial-gradient(#12152b1f 1px, transparent 1.4px);
                background-size: 1.4rem 1.4rem; }
        .hrst-pill { display: flex; gap: .5rem; padding: .4rem; border-radius: 999px;
                background: #fff; border: 1px solid #e3e6f5; box-shadow: 0 12px 30px #0b10201a; }
        .hrst-num { display: inline-flex; font-size: clamp(2.5rem, 6vw, 4rem); line-height: 1; }
        .hrst-reel { display: inline-block; overflow: clip; block-size: 1em;
                transition: translate 1.2s cubic-bezier(.2,.7,.2,1); }
        .hrst-reel i { display: block; block-size: 1em; font-style: normal; }
        @media (prefers-reduced-motion: reduce) { .hrst-reel { transition-duration: .01ms; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // resources/js/Pages/Landing.jsx — the Inertia (React) page; renders the
        // React snippet below unchanged inside the app’s provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import StatsHero from '@/components/heroes/StatsHero';

        export default function Landing() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <StatsHero />
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // StatsHero.jsx — inline email form + odometer counters
        import { useEffect, useState } from 'react';

        const GLYPHS = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        function Reel({ digit, delay, run }) {
          return (
            <span className="hrst-reel" style={{ translate: `0 ${-(run ? digit : 0)}em`, transitionDelay: `${delay}ms` }}>
              {GLYPHS.map((g) => <i key={g}>{g}</i>)}
            </span>
          );
        }

        export default function StatsHero() {
          const [email, setEmail] = useState('');
          const [done, setDone] = useState(false);
          const [shake, setShake] = useState(false);
          const [run, setRun] = useState(false);

          useEffect(() => { const t = setTimeout(() => setRun(true), 200); return () => clearTimeout(t); }, []);

          const submit = (e) => {
            e.preventDefault();
            if (email.includes('@')) setDone(true);
            else { setShake(true); setTimeout(() => setShake(false), 400); }
          };

          const stats = [
            { digits: [9, 9, 9, 9], glue: '.', suffix: '%', label: 'uptime SLA' },
            { digits: [4, 2], glue: '', suffix: 'ms', label: 'p50 edge latency' },
            { digits: [2, 4], glue: '', suffix: 'k', label: 'rollouts each day' },
          ];

          return (
            <>
              <style>{`
                .hrst { display: grid; gap: 1.5rem; justify-items: center; text-align: center; padding: 5rem 1.25rem;
                        background-image: radial-gradient(#12152b1f 1px, transparent 1.4px); background-size: 1.4rem 1.4rem; }
                .hrst-pill { display: flex; gap: .5rem; padding: .4rem; border-radius: 999px; background: #fff;
                        border: 1px solid #e3e6f5; box-shadow: 0 12px 30px #0b10201a; }
                .hrst-pill input { border: none; outline: none; padding: 0 .75rem; inline-size: min(58vw, 16rem); background: none; }
                .hrst-stats { display: flex; flex-wrap: wrap; gap: 2.5rem 4rem; justify-content: center; }
                .hrst-num { display: inline-flex; font: 800 clamp(2.5rem, 6vw, 4rem)/1 system-ui;
                        font-variant-numeric: tabular-nums; }
                .hrst-reel { display: inline-block; overflow: clip; block-size: 1em;
                        transition: translate 1.2s cubic-bezier(.2,.7,.2,1); }
                .hrst-reel i { display: block; block-size: 1em; font-style: normal; }
                .hrst-shake { animation: hrst-shake .4s; }
                @keyframes hrst-shake { 25% { translate: -.4rem 0; } 75% { translate: .4rem 0; } }
                @media (prefers-reduced-motion: reduce) { .hrst-reel { transition-duration: .01ms; } }
              `}</style>
              <section className="hrst">
                <h1 className="hrst-title">The numbers behind calm releases</h1>
                {done ? (
                  <p role="status">You’re on the list — see you in the inbox, <b>{email}</b>.</p>
                ) : (
                  <form className={`hrst-pill ${shake ? 'hrst-shake' : ''}`} onSubmit={submit}>
                    <input type="email" value={email} onChange={(e) => setEmail(e.target.value)} placeholder="you@team.com" />
                    <button type="submit">Start free</button>
                  </form>
                )}
                <div className="hrst-stats">
                  {stats.map((s) => (
                    <div className="hrst-stat" key={s.label}>
                      <b className="hrst-num">
                        {s.digits.map((d, i) => (
                          <span key={`${s.label}-${i}`} style={{ display: 'inline-flex' }}>
                            <Reel digit={d} delay={i * 120} run={run} />
                            {i === 0 && s.glue && <span>{s.glue}</span>}
                          </span>
                        ))}
                        <small>{s.suffix}</small>
                      </b>
                      <small className="hrst-label">{s.label}</small>
                    </div>
                  ))}
                </div>
              </section>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- StatsHero.vue — inline email form + odometer counters -->
        <script setup>
        import { onMounted, ref } from 'vue';

        const email = ref('');
        const done = ref(false);
        const shake = ref(false);
        const run = ref(false);
        const glyphs = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        const stats = [
          { digits: [9, 9, 9, 9], glue: '.', suffix: '%', label: 'uptime SLA' },
          { digits: [4, 2], glue: '', suffix: 'ms', label: 'p50 edge latency' },
          { digits: [2, 4], glue: '', suffix: 'k', label: 'rollouts each day' },
        ];

        onMounted(() => setTimeout(() => (run.value = true), 200));

        function submit() {
          if (email.value.includes('@')) done.value = true;
          else { shake.value = true; setTimeout(() => (shake.value = false), 400); }
        }
        </script>

        <template>
          <section class="hrst">
            <h1 class="hrst-title">The numbers behind calm releases</h1>
            <p v-if="done" role="status">You’re on the list — see you in the inbox, <b>{{ email }}</b>.</p>
            <form v-else class="hrst-pill" :class="{ 'hrst-shake': shake }" @submit.prevent="submit">
              <input type="email" v-model="email" placeholder="you@team.com" />
              <button type="submit">Start free</button>
            </form>
            <div class="hrst-stats">
              <div v-for="s in stats" :key="s.label" class="hrst-stat">
                <b class="hrst-num">
                  <template v-for="(d, i) in s.digits" :key="i">
                    <span class="hrst-reel" :style="{ translate: `0 ${-(run ? d : 0)}em`, transitionDelay: `${i * 120}ms` }">
                      <i v-for="g in glyphs" :key="g">{{ g }}</i>
                    </span>
                    <span v-if="i === 0 && s.glue">{{ s.glue }}</span>
                  </template>
                  <small>{{ s.suffix }}</small>
                </b>
                <small class="hrst-label">{{ s.label }}</small>
              </div>
            </div>
          </section>
        </template>

        <style scoped>
        .hrst { display: grid; gap: 1.5rem; justify-items: center; text-align: center; padding: 5rem 1.25rem;
                background-image: radial-gradient(#12152b1f 1px, transparent 1.4px); background-size: 1.4rem 1.4rem; }
        .hrst-pill { display: flex; gap: .5rem; padding: .4rem; border-radius: 999px; background: #fff;
                border: 1px solid #e3e6f5; box-shadow: 0 12px 30px #0b10201a; }
        .hrst-pill input { border: none; outline: none; padding: 0 .75rem; inline-size: min(58vw, 16rem); background: none; }
        .hrst-stats { display: flex; flex-wrap: wrap; gap: 2.5rem 4rem; justify-content: center; }
        .hrst-num { display: inline-flex; font: 800 clamp(2.5rem, 6vw, 4rem)/1 system-ui;
                font-variant-numeric: tabular-nums; }
        .hrst-reel { display: inline-block; overflow: clip; block-size: 1em;
                transition: translate 1.2s cubic-bezier(.2,.7,.2,1); }
        .hrst-reel i { display: block; block-size: 1em; font-style: normal; }
        .hrst-shake { animation: hrst-shake .4s; }
        @keyframes hrst-shake { 25% { translate: -.4rem 0; } 75% { translate: .4rem 0; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- StatsHero.svelte — inline email form + odometer counters -->
        <script>
          const glyphs = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
          const stats = [
            { digits: [9, 9, 9, 9], glue: '.', suffix: '%', label: 'uptime SLA' },
            { digits: [4, 2], glue: '', suffix: 'ms', label: 'p50 edge latency' },
            { digits: [2, 4], glue: '', suffix: 'k', label: 'rollouts each day' },
          ];
          let email = $state('');
          let done = $state(false);
          let shake = $state(false);
          let run = $state(false);

          $effect(() => { const t = setTimeout(() => (run = true), 200); return () => clearTimeout(t); });

          function submit(e) {
            e.preventDefault();
            if (email.includes('@')) done = true;
            else { shake = true; setTimeout(() => (shake = false), 400); }
          }
        </script>

        <section class="hrst">
          <h1 class="hrst-title">The numbers behind calm releases</h1>
          {#if done}
            <p role="status">You’re on the list — see you in the inbox, <b>{email}</b>.</p>
          {:else}
            <form class="hrst-pill" class:hrst-shake={shake} onsubmit={submit}>
              <input type="email" bind:value={email} placeholder="you@team.com" />
              <button type="submit">Start free</button>
            </form>
          {/if}
          <div class="hrst-stats">
            {#each stats as s (s.label)}
              <div class="hrst-stat">
                <b class="hrst-num">
                  {#each s.digits as d, i (i)}
                    <span class="hrst-reel" style={`translate: 0 ${-(run ? d : 0)}em; transition-delay: ${i * 120}ms`}>
                      {#each glyphs as g (g)}<i>{g}</i>{/each}
                    </span>
                    {#if i === 0 && s.glue}<span>{s.glue}</span>{/if}
                  {/each}
                  <small>{s.suffix}</small>
                </b>
                <small class="hrst-label">{s.label}</small>
              </div>
            {/each}
          </div>
        </section>

        <style>
        .hrst { display: grid; gap: 1.5rem; justify-items: center; text-align: center; padding: 5rem 1.25rem;
                background-image: radial-gradient(#12152b1f 1px, transparent 1.4px); background-size: 1.4rem 1.4rem; }
        .hrst-pill { display: flex; gap: .5rem; padding: .4rem; border-radius: 999px; background: #fff;
                border: 1px solid #e3e6f5; box-shadow: 0 12px 30px #0b10201a; }
        .hrst-pill input { border: none; outline: none; padding: 0 .75rem; inline-size: min(58vw, 16rem); background: none; }
        .hrst-stats { display: flex; flex-wrap: wrap; gap: 2.5rem 4rem; justify-content: center; }
        .hrst-num { display: inline-flex; font: 800 clamp(2.5rem, 6vw, 4rem)/1 system-ui;
                font-variant-numeric: tabular-nums; }
        .hrst-reel { display: inline-block; overflow: clip; block-size: 1em;
                transition: translate 1.2s cubic-bezier(.2,.7,.2,1); }
        .hrst-reel i { display: block; block-size: 1em; font-style: normal; }
        .hrst-shake { animation: hrst-shake .4s; }
        @keyframes hrst-shake { 25% { translate: -.4rem 0; } 75% { translate: .4rem 0; } }
        </style>
        SVELTE,
        ],
    ],
];
