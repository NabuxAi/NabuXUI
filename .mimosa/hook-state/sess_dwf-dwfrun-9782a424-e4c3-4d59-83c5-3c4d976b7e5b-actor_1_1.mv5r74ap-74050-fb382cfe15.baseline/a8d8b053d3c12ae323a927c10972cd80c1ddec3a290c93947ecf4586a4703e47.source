<?php

/**
 * Demo manifest of the "kinetic" group — kinetic typography: text that moves
 * with the reader. A narrative paragraph whose words light up as scroll
 * advances, a headline keyword flipping through a 3D rotateX cycle, a Latin
 * title whose letters bob like a wave under the pointer, scroll-velocity
 * marquee bands and letters that stretch on hover and wobble back on a real
 * spring. Scenarios live at
 * resources/views/demos/components/kinetic/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'تایپوگرافی جنبشی', 'en' => 'Kinetic type'],

    'word-fill-scroll' => [
        'title' => ['fa' => 'پُرشدن واژه‌ها با اسکرول', 'en' => 'Word fill on scroll'],
        'icon' => 'wand',
        'oneLiner' => [
            'fa' => 'پاراگراف روایت که واژه‌هایش با پیشرفت خواندن یکی‌یکی از خاکستری خاموش به جوهر کامل رنگ می‌گیرند؛ نشانگر درصد و ریل عمودی کنار متن می‌ماند و پیشرفتِ خواندن را زنده گزارش می‌کند.',
            'en' => 'A narrative paragraph whose words turn on one by one — from dim grey to full ink — as reading advances; a sticky percentage marker and a hairline rail report the progress live beside the text.',
        ],
        'js' => true,
        'docs' => 'https://text-effects.colorion.co',
        'props' => [
            ['name' => '--lit', 'type' => '0 → 1', 'default' => "'per word'", 'note' => [
                'fa' => 'سهم هر واژه از پیشرفت؛ رنگ بین خاکستری خاموش و جوهر کامل میان‌یابی می‌شود و واژهٔ مرزی لحظه‌ای نیم‌روزن است.',
                'en' => 'Each word’s share of the progress; colour interpolates between dim grey and full ink, so the frontier word lands half-lit.',
            ]],
            ['name' => 'progress', 'type' => '0–100%', 'default' => "'scrollTop ÷ (scrollHeight − clientHeight)'", 'note' => [
                'fa' => 'پیشرفت خواندن از موقعیت اسکرول خودِ متن؛ همان عدد روی ریل و نشانگر درصد می‌نشیند.',
                'en' => 'Reading progress straight from the text’s own scroll position; the same number drives the rail and the marker.',
            ]],
            ['name' => 'marker', 'type' => 'sticky', 'default' => "'rail'", 'note' => [
                'fa' => 'نشانگر درصد و ریل عمودی با اسکرولِ صفحه همراه می‌مانند تا خواندنِ نیمه‌کاره همیشه قاب باشد.',
                'en' => 'The percentage marker and its vertical rail stay put while the page scrolls, so half-finished reading is always framed.',
            ]],
            ['name' => 'reduced-motion', 'type' => 'behaviour', 'default' => "'instant'", 'note' => [
                'fa' => 'بدون ترنزیشن؛ روشنایی واژه‌ها مستقیم و بی‌تأخیر به موقعیت اسکرول می‌چسبد.',
                'en' => 'No transition; word lighting snaps straight to the scroll position with no smoothing.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        {{-- resources/views/demos/components/kinetic/word-fill-scroll.blade.php --}}
        <div class="wfs" x-data="{
                pct: 0, raf: 0,
                measure() {
                    const el = this.$refs.page;
                    const p = el.scrollTop / Math.max(1, el.scrollHeight - el.clientHeight);
                    this.pct = Math.round(p * 100);
                    const lit = p * this.$refs.text.querySelectorAll('.wfs-w').length;
                    this.$refs.text.querySelectorAll('.wfs-w').forEach((w, i) =>
                        w.style.setProperty('--lit', Math.max(0, Math.min(1, lit - i)).toFixed(3)));
                },
                onScroll() {
                    cancelAnimationFrame(this.raf);
                    this.raf = requestAnimationFrame(() => this.measure());
                },
                init() { this.measure(); },
                destroy() { cancelAnimationFrame(this.raf); },
            }">
            <aside class="wfs-rail">
                <b class="wfs-pct" x-text="pct + '٪'"></b>
                <i class="wfs-track"><b class="wfs-fill" x-bind:style="`block-size:${pct}%`"></b></i>
            </aside>
            <div class="wfs-page" x-ref="page" x-on:scroll.passive="onScroll">
                <p class="wfs-text" x-ref="text">
                    @foreach (['استودیو', 'لومن', 'داستان', 'محصولش', 'را', 'این‌طور', 'روایت', 'می‌کند'] as $w)
                        <span class="wfs-w">{{ $w }}</span>
                    @endforeach
                </p>
            </div>
        </div>

        <style>
        .wfs { display: grid; grid-template-columns: 3rem 1fr; gap: 1rem; }
        .wfs-page { block-size: 16rem; overflow-y: auto; border: 1px solid #e7e5e4;
                    border-radius: 1rem; padding: 1.25rem; line-height: 2; }
        .wfs-w { color: color-mix(in oklab, #1c1917 calc(var(--lit, 0) * 100%), #a8a29e);
                 transition: color .18s linear; }
        .wfs-rail { position: sticky; inset-block-start: 1rem; }
        .wfs-track { display: block; inline-size: 3px; block-size: 8rem; background: #e7e5e4; }
        .wfs-fill { display: block; inline-size: 100%; background: #7c3aed; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        {{-- resources/views/demos/components/kinetic/word-fill-scroll.blade.php --}}
        <div class="wfs" x-data="{
                pct: 0, raf: 0,
                measure() {
                    const el = this.$refs.page;
                    const p = el.scrollTop / Math.max(1, el.scrollHeight - el.clientHeight);
                    this.pct = Math.round(p * 100);
                    const lit = p * this.$refs.text.querySelectorAll('.wfs-w').length;
                    this.$refs.text.querySelectorAll('.wfs-w').forEach((w, i) =>
                        w.style.setProperty('--lit', Math.max(0, Math.min(1, lit - i)).toFixed(3)));
                },
                onScroll() {
                    cancelAnimationFrame(this.raf);
                    this.raf = requestAnimationFrame(() => this.measure());
                },
                init() { this.measure(); },
                destroy() { cancelAnimationFrame(this.raf); },
            }">
            <aside class="wfs-rail">
                <b class="wfs-pct" x-text="pct + '%'"></b>
                <i class="wfs-track"><b class="wfs-fill" x-bind:style="`block-size:${pct}%`"></b></i>
            </aside>
            <div class="wfs-page" x-ref="page" x-on:scroll.passive="onScroll">
                <p class="wfs-text" x-ref="text">
                    @foreach (['Lumen', 'Studio', 'tells', 'its', 'product', 'story', 'like', 'this'] as $w)
                        <span class="wfs-w">{{ $w }}</span>
                    @endforeach
                </p>
            </div>
        </div>

        <style>
        .wfs { display: grid; grid-template-columns: 3rem 1fr; gap: 1rem; }
        .wfs-page { block-size: 16rem; overflow-y: auto; border: 1px solid #e7e5e4;
                    border-radius: 1rem; padding: 1.25rem; line-height: 1.9; }
        .wfs-w { color: color-mix(in oklab, #1c1917 calc(var(--lit, 0) * 100%), #a8a29e);
                 transition: color .18s linear; }
        .wfs-rail { position: sticky; inset-block-start: 1rem; }
        .wfs-track { display: block; inline-size: 3px; block-size: 8rem; background: #e7e5e4; }
        .wfs-fill { display: block; inline-size: 100%; background: #7c3aed; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/ReleaseNotes.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import WordFillScroll from '@/components/WordFillScroll';

        export default function ReleaseNotes() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <WordFillScroll />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // WordFillScroll.jsx — words light up as the reader scrolls
        import { useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const STORY = 'Lumen Studio tells its product story like this: the idea was sketched in Copenhagen, ' +
          'the first prototype was printed in Osaka, and feedback from forty thousand users polished every edge. ' +
          'Today the release ships worldwide.';

        export default function WordFillScroll() {
          const page = useRef(null);
          const [pct, setPct] = useState(0);
          const words = STORY.split(' ');

          const onScroll = () => {
            const el = page.current;
            const p = el.scrollTop / Math.max(1, el.scrollHeight - el.clientHeight);
            setPct(Math.round(p * 100));
            const lit = p * words.length; // words ahead of the reader stay dim
            el.querySelectorAll('.wfs-w').forEach((w, i) =>
              w.style.setProperty('--lit', Math.max(0, Math.min(1, lit - i)).toFixed(3)));
          };

          return (
            <>
              <style>{`
                .wfs { display: grid; grid-template-columns: 3rem 1fr; gap: 1rem; max-inline-size: 44rem; }
                .wfs-page { block-size: 16rem; overflow-y: auto; padding: 1.25rem; border: 1px solid #e7e5e4;
                            border-radius: 1rem; font: 400 1rem/1.9 'IBM Plex Sans', sans-serif; }
                .wfs-w { color: color-mix(in oklab, #1c1917 calc(var(--lit, 0) * 100%), #a8a29e);
                         transition: color .18s linear; }
                .wfs-rail { position: sticky; inset-block-start: 1rem; display: grid; gap: .5rem; justify-items: center; }
                .wfs-pct { font-variant-numeric: tabular-nums; }
                .wfs-track { display: block; inline-size: 3px; block-size: 8rem; background: #e7e5e4; }
                .wfs-fill { display: block; inline-size: 100%; background: #7c3aed; }
              `}</style>
              <div className="wfs">
                <aside className="wfs-rail">
                  <b className="wfs-pct">{pct}%</b>
                  <i className="wfs-track"><b className="wfs-fill" style={{ blockSize: pct + '%' }} /></i>
                </aside>
                <div className="wfs-page" ref={page} onScroll={onScroll}>
                  <p style={{ margin: 0 }}>
                    {words.map((w, i) => <span className="wfs-w" key={i}>{w}{' '}</span>)}
                  </p>
                </div>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- WordFillScroll.vue — words light up as the reader scrolls -->
        <script setup>
        import { ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const STORY = 'Lumen Studio tells its product story like this: the idea was sketched in Copenhagen, ' +
          'the first prototype was printed in Osaka, and feedback from forty thousand users polished every edge. ' +
          'Today the release ships worldwide.';
        const words = STORY.split(' ');
        const page = ref(null);
        const pct = ref(0);

        function onScroll() {
          const el = page.value;
          const p = el.scrollTop / Math.max(1, el.scrollHeight - el.clientHeight);
          pct.value = Math.round(p * 100);
          const lit = p * words.length; // words ahead of the reader stay dim
          el.querySelectorAll('.wfs-w').forEach((w, i) =>
            w.style.setProperty('--lit', Math.max(0, Math.min(1, lit - i)).toFixed(3)));
        }
        </script>

        <template>
          <div class="wfs">
            <aside class="wfs-rail">
              <b class="wfs-pct">{{ pct }}%</b>
              <i class="wfs-track"><b class="wfs-fill" :style="{ blockSize: pct + '%' }"></b></i>
            </aside>
            <div class="wfs-page" ref="page" @scroll.passive="onScroll">
              <p class="wfs-text" style="margin: 0">
                <span v-for="(w, i) in words" :key="i" class="wfs-w">{{ w }} </span>
              </p>
            </div>
          </div>
        </template>

        <style scoped>
        .wfs { display: grid; grid-template-columns: 3rem 1fr; gap: 1rem; max-inline-size: 44rem; }
        .wfs-page { block-size: 16rem; overflow-y: auto; padding: 1.25rem; border: 1px solid #e7e5e4;
                    border-radius: 1rem; font: 400 1rem/1.9 'IBM Plex Sans', sans-serif; }
        .wfs-w { color: color-mix(in oklab, #1c1917 calc(var(--lit, 0) * 100%), #a8a29e);
                 transition: color .18s linear; }
        .wfs-rail { position: sticky; inset-block-start: 1rem; display: grid; gap: .5rem; justify-items: center; }
        .wfs-pct { font-variant-numeric: tabular-nums; }
        .wfs-track { display: block; inline-size: 3px; block-size: 8rem; background: #e7e5e4; }
        .wfs-fill { display: block; inline-size: 100%; background: #7c3aed; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- WordFillScroll.svelte — words light up as the reader scrolls -->
        <script>
          import '@nabuxai/ui-core/css';
          const STORY = 'Lumen Studio tells its product story like this: the idea was sketched in Copenhagen, ' +
            'the first prototype was printed in Osaka, and feedback from forty thousand users polished every edge. ' +
            'Today the release ships worldwide.';
          const words = STORY.split(' ');
          let page;
          let pct = $state(0);

          function onScroll() {
            const p = page.scrollTop / Math.max(1, page.scrollHeight - page.clientHeight);
            pct = Math.round(p * 100);
            const lit = p * words.length; // words ahead of the reader stay dim
            page.querySelectorAll('.wfs-w').forEach((w, i) =>
              w.style.setProperty('--lit', Math.max(0, Math.min(1, lit - i)).toFixed(3)));
          }
        </script>

        <div class="wfs">
          <aside class="wfs-rail">
            <b class="wfs-pct">{pct}%</b>
            <i class="wfs-track"><b class="wfs-fill" style={`block-size:${pct}%`}></b></i>
          </aside>
          <div class="wfs-page" bind:this={page} onscroll={onScroll}>
            <p class="wfs-text" style="margin: 0">
              {#each words as w, i (w + i)}<span class="wfs-w">{w} </span>{/each}
            </p>
          </div>
        </div>

        <style>
          .wfs { display: grid; grid-template-columns: 3rem 1fr; gap: 1rem; max-inline-size: 44rem; }
          .wfs-page { block-size: 16rem; overflow-y: auto; padding: 1.25rem; border: 1px solid #e7e5e4;
                      border-radius: 1rem; font: 400 1rem/1.9 'IBM Plex Sans', sans-serif; }
          .wfs-w { color: color-mix(in oklab, #1c1917 calc(var(--lit, 0) * 100%), #a8a29e);
                   transition: color .18s linear; }
          .wfs-rail { position: sticky; inset-block-start: 1rem; display: grid; gap: .5rem; justify-items: center; }
          .wfs-pct { font-variant-numeric: tabular-nums; }
          .wfs-track { display: block; inline-size: 3px; block-size: 8rem; background: #e7e5e4; }
          .wfs-fill { display: block; inline-size: 100%; background: #7c3aed; }
        </style>
        SVELTE,
        ],
    ],

    'rotate-cycle-3d' => [
        'title' => ['fa' => 'چرخهٔ واژهٔ سه‌بعدی', 'en' => '3D word cycle'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'واژهٔ کلیدی تیتر روی محور X می‌چرخد و بین چهار واژه می‌گردد؛ عرض کانتینر با هر واژه نرم جابه‌جا می‌شود، هاور چرخه را نگه می‌دارد و دکمهٔ ری‌پلی از اول می‌چرخاند.',
            'en' => 'The headline’s key word flips on its X axis through a four-word loop; the container eases its width to each newcomer, hover holds the cycle and the replay button spins it from the top.',
        ],
        'js' => true,
        'docs' => 'https://text-effects.colorion.co',
        'props' => [
            ['name' => 'words', 'type' => 'string[]', 'default' => "'4 items'", 'note' => [
                'fa' => 'چهار واژهٔ چرخه؛ هر واژه پیش از نمایش با یک spanِ پنهان هم‌قلم اندازه‌گیری می‌شود.',
                'en' => 'The four loop words; each is pre-measured with a hidden same-font span before it shows.',
            ]],
            ['name' => 'perspective', 'type' => 'px', 'default' => "'600'", 'note' => [
                'fa' => 'عمق صحنهٔ سه‌بعدی روی والد؛ چرخش rotateX بدون آن صاف می‌شود.',
                'en' => 'The 3D depth on the parent; without it the rotateX flip flattens out.',
            ]],
            ['name' => 'cadence', 'type' => 'ms', 'default' => "'2400'", 'note' => [
                'fa' => 'گام چرخه؛ هاور روی تیتر تایمر را نگه می‌دارد و با رفتنِ نشانگر از سر گرفته می‌شود.',
                'en' => 'The loop step; hovering the headline holds the timer and it resumes when the pointer leaves.',
            ]],
            ['name' => 'width-ease', 'type' => 'transition', 'default' => "'.42s cubic-bezier(.3,.7,.2,1)'", 'note' => [
                'fa' => 'عرض کانتینر با هر واژه جابه‌جا می‌شود تا جمله نلرزد؛ زیر حرکت کاهش‌یافته بی‌درز می‌شکند.',
                'en' => 'The container width travels with each word so the sentence never jumps; it snaps seamlessly under reduced motion.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        {{-- resources/views/demos/components/kinetic/rotate-cycle-3d.blade.php --}}
        <h2 class="rc3" x-data="{
                words: {{ json_encode(['سریع‌تر', 'باهم', 'همه‌جا', 'ساده‌تر'], JSON_UNESCAPED_UNICODE) }},
                i: 0, t: null, t2: null, io: null,
                swap() {
                    const word = this.$refs.word;
                    word.dataset.state = 'out';
                    this.t2 = setTimeout(() => {
                        this.i = (this.i + 1) % this.words.length;
                        word.dataset.state = 'in';
                        requestAnimationFrame(() => requestAnimationFrame(() => (word.dataset.state = 'show')));
                        this.fit();
                    }, 260);
                },
                fit() {
                    this.$refs.ghost.textContent = this.words[this.i];
                    this.$refs.slot.style.inlineSize = Math.ceil(this.$refs.ghost.getBoundingClientRect().width) + 'px';
                },
                start() { this.stop(); this.t = setInterval(() => this.swap(), 2400); },
                stop() { clearInterval(this.t); clearTimeout(this.t2); },
                init() {
                    this.fit();
                    if (!matchMedia('(prefers-reduced-motion: reduce)').matches) {
                        this.io = new IntersectionObserver((e) => (e[0].isIntersecting ? this.start() : this.stop()));
                        this.io.observe(this.$el);
                    }
                },
                destroy() { this.stop(); if (this.io) this.io.disconnect(); },
            }"
            x-on:pointerenter="stop()" x-on:pointerleave="start()">
            سیستم طراحی‌تان را بسازید —
            <span class="rc3-stage" x-ref="slot"><span class="rc3-word" x-ref="word" x-text="words[i]"></span></span>
            <span class="rc3-ghost" x-ref="ghost" aria-hidden="true"></span>
        </h2>
        <button type="button" x-on:click="i = 0; fit()">ری‌پلی</button>

        <style>
        .rc3-stage { display: inline-block; perspective: 600px; transition: inline-size .42s cubic-bezier(.3,.7,.2,1); }
        .rc3-word { display: inline-block; transform-origin: 50% 100%; transition: transform .26s ease, opacity .26s ease; }
        .rc3-word[data-state='out'] { transform: rotateX(90deg); opacity: 0; }
        .rc3-word[data-state='in'] { transform: rotateX(-90deg); opacity: 0; transition: none; }
        .rc3-ghost { position: absolute; visibility: hidden; white-space: nowrap; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        {{-- resources/views/demos/components/kinetic/rotate-cycle-3d.blade.php --}}
        <h2 class="rc3" x-data="{
                words: {{ json_encode(['faster', 'together', 'anywhere', 'wiser'], JSON_UNESCAPED_UNICODE) }},
                i: 0, t: null, t2: null, io: null,
                swap() {
                    const word = this.$refs.word;
                    word.dataset.state = 'out';
                    this.t2 = setTimeout(() => {
                        this.i = (this.i + 1) % this.words.length;
                        word.dataset.state = 'in';
                        requestAnimationFrame(() => requestAnimationFrame(() => (word.dataset.state = 'show')));
                        this.fit();
                    }, 260);
                },
                fit() {
                    this.$refs.ghost.textContent = this.words[this.i];
                    this.$refs.slot.style.inlineSize = Math.ceil(this.$refs.ghost.getBoundingClientRect().width) + 'px';
                },
                start() { this.stop(); this.t = setInterval(() => this.swap(), 2400); },
                stop() { clearInterval(this.t); clearTimeout(this.t2); },
                init() {
                    this.fit();
                    if (!matchMedia('(prefers-reduced-motion: reduce)').matches) {
                        this.io = new IntersectionObserver((e) => (e[0].isIntersecting ? this.start() : this.stop()));
                        this.io.observe(this.$el);
                    }
                },
                destroy() { this.stop(); if (this.io) this.io.disconnect(); },
            }"
            x-on:pointerenter="stop()" x-on:pointerleave="start()">
            Ship your design system
            <span class="rc3-stage" x-ref="slot"><span class="rc3-word" x-ref="word" x-text="words[i]"></span></span>
            <span class="rc3-ghost" x-ref="ghost" aria-hidden="true"></span>
        </h2>
        <button type="button" x-on:click="i = 0; fit()">Replay</button>

        <style>
        .rc3-stage { display: inline-block; perspective: 600px; transition: inline-size .42s cubic-bezier(.3,.7,.2,1); }
        .rc3-word { display: inline-block; transform-origin: 50% 100%; transition: transform .26s ease, opacity .26s ease; }
        .rc3-word[data-state='out'] { transform: rotateX(90deg); opacity: 0; }
        .rc3-word[data-state='in'] { transform: rotateX(-90deg); opacity: 0; transition: none; }
        .rc3-ghost { position: absolute; visibility: hidden; white-space: nowrap; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Landing.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import HeroCycle from '@/components/HeroCycle';

        export default function Landing() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <HeroCycle />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // HeroCycle.jsx — the headline keyword flipping through a 3D word loop
        import { useEffect, useLayoutEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const WORDS = ['faster', 'together', 'anywhere', 'wiser'];

        export default function HeroCycle() {
          const [i, setI] = useState(0);
          const [state, setState] = useState('show');
          const [width, setWidth] = useState(0);
          const ghost = useRef(null);
          const word = useRef(null);

          // Measure the incoming word with a hidden same-font span.
          useLayoutEffect(() => {
            if (ghost.current) setWidth(Math.ceil(ghost.current.getBoundingClientRect().width));
          }, [i]);

          // The loop: out on X, swap under the fold, back in — off screen it rests.
          useEffect(() => {
            if (matchMedia('(prefers-reduced-motion: reduce)').matches) return;
            const tick = setInterval(() => {
              setState('out');
              setTimeout(() => { setI((n) => (n + 1) % WORDS.length); setState('in');
                requestAnimationFrame(() => requestAnimationFrame(() => setState('show'))); }, 260);
            }, 2400);
            const io = new IntersectionObserver(([e]) => e.isIntersecting ? null : clearInterval(tick));
            io.observe(word.current);
            return () => { clearInterval(tick); io.disconnect(); };
          }, []);

          return (
            <>
              <style>{`
                .rc3 { margin: 0; font: 800 clamp(1.8rem, 6vw, 3.4rem)/1.25 'IBM Plex Sans', sans-serif; }
                .rc3-stage { display: inline-block; perspective: 600px; white-space: nowrap;
                             transition: inline-size .42s cubic-bezier(.3,.7,.2,1); }
                .rc3-word { display: inline-block; transform-origin: 50% 100%;
                            transition: transform .26s ease, opacity .26s ease; }
                .rc3-word[data-state='out'] { transform: rotateX(90deg); opacity: 0; }
                .rc3-word[data-state='in'] { transform: rotateX(-90deg); opacity: 0; transition: none; }
                .rc3-ghost { position: absolute; visibility: hidden; white-space: nowrap; }
              `}</style>
              <h2 className="rc3" onPointerEnter={() => clearInterval(undefined)}>
                Ship your design system{' '}
                <span className="rc3-stage" ref={word} style={{ inlineSize: width ? width + 'px' : undefined }}>
                  <span className="rc3-word" data-state={state} aria-live="polite">{WORDS[i]}</span>
                </span>
                <span className="rc3-ghost" ref={ghost} aria-hidden="true">{WORDS[i]}</span>
              </h2>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- HeroCycle.vue — the headline keyword flipping through a 3D word loop -->
        <script setup>
        import { nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue';
        import '@nabuxai/ui-core/css';

        const WORDS = ['faster', 'together', 'anywhere', 'wiser'];
        const i = ref(0);
        const state = ref('show');
        const width = ref(0);
        const ghost = ref(null);
        let t = null, t2 = null, io = null;

        async function fit() {
          await nextTick();
          width.value = Math.ceil(ghost.value.getBoundingClientRect().width);
        }
        function swap() {
          state.value = 'out';
          t2 = setTimeout(async () => {
            i.value = (i.value + 1) % WORDS.length;
            state.value = 'in';
            await fit();
            requestAnimationFrame(() => requestAnimationFrame(() => (state.value = 'show')));
          }, 260);
        }
        function start() { stop(); t = setInterval(swap, 2400); }
        function stop() { clearInterval(t); clearTimeout(t2); }

        onMounted(() => {
          fit();
          if (!matchMedia('(prefers-reduced-motion: reduce)').matches) {
            io = new IntersectionObserver(([e]) => (e.isIntersecting ? start() : stop()));
            io.observe(ghost.value.parentElement);
          }
        });
        onBeforeUnmount(() => { stop(); io?.disconnect(); });
        </script>

        <template>
          <h2 class="rc3" @pointerenter="stop" @pointerleave="start">
            Ship your design system
            <span class="rc3-stage" :style="{ inlineSize: width ? width + 'px' : undefined }">
              <span class="rc3-word" :data-state="state" aria-live="polite">{{ WORDS[i] }}</span>
            </span>
            <span class="rc3-ghost" ref="ghost" aria-hidden="true">{{ WORDS[i] }}</span>
          </h2>
        </template>

        <style scoped>
        .rc3 { margin: 0; font: 800 clamp(1.8rem, 6vw, 3.4rem)/1.25 'IBM Plex Sans', sans-serif; }
        .rc3-stage { display: inline-block; perspective: 600px; white-space: nowrap;
                     transition: inline-size .42s cubic-bezier(.3,.7,.2,1); }
        .rc3-word { display: inline-block; transform-origin: 50% 100%;
                    transition: transform .26s ease, opacity .26s ease; }
        .rc3-word[data-state='out'] { transform: rotateX(90deg); opacity: 0; }
        .rc3-word[data-state='in'] { transform: rotateX(-90deg); opacity: 0; transition: none; }
        .rc3-ghost { position: absolute; visibility: hidden; white-space: nowrap; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- HeroCycle.svelte — the headline keyword flipping through a 3D word loop -->
        <script>
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          const WORDS = ['faster', 'together', 'anywhere', 'wiser'];
          let i = $state(0);
          let state = $state('show');
          let width = $state(0);
          let ghost;
          let t = null, t2 = null, io = null;

          function fit() { width = Math.ceil(ghost.getBoundingClientRect().width); }
          function swap() {
            state = 'out';
            t2 = setTimeout(() => {
              i = (i + 1) % WORDS.length;
              state = 'in';
              fit();
              requestAnimationFrame(() => requestAnimationFrame(() => (state = 'show')));
            }, 260);
          }
          function start() { stop(); t = setInterval(swap, 2400); }
          function stop() { clearInterval(t); clearTimeout(t2); }

          onMount(() => {
            fit();
            if (!matchMedia('(prefers-reduced-motion: reduce)').matches) {
              io = new IntersectionObserver(([e]) => (e.isIntersecting ? start() : stop()));
              io.observe(ghost.parentElement);
            }
            return () => { stop(); io?.disconnect(); };
          });
        </script>

        <h2 class="rc3" onpointerenter={stop} onpointerleave={start}>
          Ship your design system
          <span class="rc3-stage" style={`inline-size:${width ? width + 'px' : 'auto'}`}>
            <span class="rc3-word" data-state={state} aria-live="polite">{WORDS[i]}</span>
          </span>
          <span class="rc3-ghost" bind:this={ghost} aria-hidden="true">{WORDS[i]}</span>
        </h2>

        <style>
          .rc3 { margin: 0; font: 800 clamp(1.8rem, 6vw, 3.4rem)/1.25 'IBM Plex Sans', sans-serif; }
          .rc3-stage { display: inline-block; perspective: 600px; white-space: nowrap;
                       transition: inline-size .42s cubic-bezier(.3,.7,.2,1); }
          .rc3-word { display: inline-block; transform-origin: 50% 100%;
                      transition: transform .26s ease, opacity .26s ease; }
          .rc3-word[data-state='out'] { transform: rotateX(90deg); opacity: 0; }
          .rc3-word[data-state='in'] { transform: rotateX(-90deg); opacity: 0; transition: none; }
          .rc3-ghost { position: absolute; visibility: hidden; white-space: nowrap; }
        </style>
        SVELTE,
        ],
    ],

    'letter-wave' => [
        'title' => ['fa' => 'موج حروف', 'en' => 'Letter wave'],
        'icon' => 'trend-up',
        'oneLiner' => [
            'fa' => 'تیتری که واحد‌هایش — در لاتین حرف، در فارسی واژه — مثل موج بالا و پایین می‌روند و کمی می‌چرخند؛ نزدیک‌ترین واحد به موس بلندتر می‌خیزد و اسلایدر شدت موج را کم و زیاد می‌کند.',
            'en' => 'A title whose units — letters for Latin, whole words for Persian — bob and tilt like a wave; the unit nearest the pointer leaps higher, and a slider turns the swell down or up.',
        ],
        'js' => true,
        'docs' => 'https://text-effects.colorion.co',
        'props' => [
            ['name' => 'amplitude', 'type' => 'px', 'default' => "'10'", 'note' => [
                'fa' => 'دامنهٔ موج پایه؛ اسلایدر آن را از صفر تا دو برابر می‌کشد.',
                'en' => 'The base swell; the slider drags it from zero up to twice the default.',
            ]],
            ['name' => 'phase', 'type' => 'rad/unit', 'default' => "'.55'", 'note' => [
                'fa' => 'اختلاف فاز بین حروف/واژه‌ها؛ همان چیزی که حرکت را به موج می‌خواند نه بالا-پایینِ هم‌زمان.',
                'en' => 'The phase offset between units — what reads as a wave instead of a simultaneous bounce.',
            ]],
            ['name' => 'pointer-boost', 'type' => 'gaussian', 'default' => "'σ ≈ 70px'", 'note' => [
                'fa' => 'تقویت گاوسیِ نزدیک موس؛ با دور شدن نشانگر میرا می‌شود و با ترک تیتر محو.',
                'en' => 'A gaussian boost near the pointer; it decays with distance and fades out when the pointer leaves.',
            ]],
            ['name' => 'reduced-motion', 'type' => 'behaviour', 'default' => "'still'", 'note' => [
                'fa' => 'موج می‌ایستد و تیتر صاف می‌نشیند؛ بیرون از دید هم حلقهٔ rAF پارک می‌شود.',
                'en' => 'The wave stops and the title settles flat; the rAF loop is parked off screen as well.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        {{-- resources/views/demos/components/kinetic/letter-wave.blade.php --}}
        {{-- فارسی: سطح واژه — حروف هرگز شکسته نمی‌شوند --}}
        <div class="ltwv" x-data="{
                units: [], raf: 0, t0: 0, io: null, mq: null, onMq: null,
                mx: 0, boost: 0, amp: 1, visible: true,
                frame(now) {
                    const t = (now - this.t0) / 1000;
                    this.units.forEach((u, i) => {
                        const near = Math.exp(-((u.x - this.mx) ** 2) / 9800) * this.boost;
                        const y = Math.sin(t * 2.2 + i * 0.55) * 10 * this.amp * (1 + 2 * near);
                        const r = Math.sin(t * 2.2 + i * 0.55 + 0.9) * 3 * this.amp * (1 + near);
                        u.el.style.transform = `translateY(${y.toFixed(2)}px) rotate(${r.toFixed(2)}deg)`;
                    });
                    this.raf = requestAnimationFrame((n) => this.frame(n));
                },
                run() {
                    cancelAnimationFrame(this.raf);
                    if (this.mq.matches || !this.visible) return;
                    this.raf = requestAnimationFrame((n) => this.frame(n));
                },
                init() {
                    this.t0 = performance.now();
                    this.units = [...this.$refs.title.querySelectorAll('.ltwv-u')].map((el) => ({ el, x: 0 }));
                    const place = () => this.units.forEach((u) => (u.x = u.el.getBoundingClientRect().x + u.el.offsetWidth / 2));
                    place(); addEventListener('resize', place);
                    this.mq = matchMedia('(prefers-reduced-motion: reduce)');
                    this.onMq = () => this.run();
                    this.mq.addEventListener('change', this.onMq);
                    this.io = new IntersectionObserver((e) => { this.visible = e[0].isIntersecting; this.run(); });
                    this.io.observe(this.$refs.title);
                    this.run();
                },
                destroy() {
                    cancelAnimationFrame(this.raf);
                    if (this.io) this.io.disconnect();
                    if (this.mq && this.onMq) this.mq.removeEventListener('change', this.onMq);
                },
            }"
            x-on:pointermove="(mx = $event.offsetX) && (boost = 1)"
            x-on:pointerleave="boost = 0">
            <h2 class="ltwv-title" x-ref="title">
                @foreach (['تایپوگرافی', 'موج', 'می‌زند'] as $u)
                    <span class="ltwv-u">{{ $u }}</span>
                @endforeach
            </h2>
            <label class="ltwv-amp">شدت <input type="range" min="0" max="2" step=".05" value="1" x-model.number="amp"></label>
        </div>

        <style>
        .ltwv-title { display: flex; flex-wrap: wrap; gap: .35em; font-weight: 900;
                      font-size: clamp(2rem, 8vw, 4rem); perspective: 500px; }
        .ltwv-u { display: inline-block; will-change: transform; }
        .ltwv-amp { display: flex; gap: .6rem; align-items: center; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        {{-- resources/views/demos/components/kinetic/letter-wave.blade.php --}}
        {{-- Latin: letter level --}}
        <div class="ltwv" x-data="{
                units: [], raf: 0, t0: 0, io: null, mq: null, onMq: null,
                mx: 0, boost: 0, amp: 1, visible: true,
                frame(now) {
                    const t = (now - this.t0) / 1000;
                    this.units.forEach((u, i) => {
                        const near = Math.exp(-((u.x - this.mx) ** 2) / 9800) * this.boost;
                        const y = Math.sin(t * 2.2 + i * 0.55) * 10 * this.amp * (1 + 2 * near);
                        const r = Math.sin(t * 2.2 + i * 0.55 + 0.9) * 3 * this.amp * (1 + near);
                        u.el.style.transform = `translateY(${y.toFixed(2)}px) rotate(${r.toFixed(2)}deg)`;
                    });
                    this.raf = requestAnimationFrame((n) => this.frame(n));
                },
                run() {
                    cancelAnimationFrame(this.raf);
                    if (this.mq.matches || !this.visible) return;
                    this.raf = requestAnimationFrame((n) => this.frame(n));
                },
                init() {
                    this.t0 = performance.now();
                    this.units = [...this.$refs.title.querySelectorAll('.ltwv-u')].map((el) => ({ el, x: 0 }));
                    const place = () => this.units.forEach((u) => (u.x = u.el.getBoundingClientRect().x + u.el.offsetWidth / 2));
                    place(); addEventListener('resize', place);
                    this.mq = matchMedia('(prefers-reduced-motion: reduce)');
                    this.onMq = () => this.run();
                    this.mq.addEventListener('change', this.onMq);
                    this.io = new IntersectionObserver((e) => { this.visible = e[0].isIntersecting; this.run(); });
                    this.io.observe(this.$refs.title);
                    this.run();
                },
                destroy() {
                    cancelAnimationFrame(this.raf);
                    if (this.io) this.io.disconnect();
                    if (this.mq && this.onMq) this.mq.removeEventListener('change', this.onMq);
                },
            }"
            x-on:pointermove="(mx = $event.offsetX) && (boost = 1)"
            x-on:pointerleave="boost = 0">
            <h2 class="ltwv-title" x-ref="title">
                @foreach (str_split('MAKING WAVES') as $u)
                    <span class="ltwv-u">{{ $u === ' ' ? ' ' : $u }}</span>
                @endforeach
            </h2>
            <label class="ltwv-amp">Swell <input type="range" min="0" max="2" step=".05" value="1" x-model.number="amp"></label>
        </div>

        <style>
        .ltwv-title { display: flex; flex-wrap: wrap; font-weight: 900;
                      font-size: clamp(2rem, 8vw, 4rem); perspective: 500px; }
        .ltwv-u { display: inline-block; will-change: transform; }
        .ltwv-amp { display: flex; gap: .6rem; align-items: center; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/TypeLab.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import LetterWave from '@/components/LetterWave';

        export default function TypeLab() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <LetterWave />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // LetterWave.jsx — letters bob like a wave; the pointer's neighbours leap
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const TITLE = 'MAKING WAVES';

        export default function LetterWave() {
          const [amp, setAmp] = useState(1);
          const title = useRef(null);
          const ampRef = useRef(amp);
          ampRef.current = amp;
          const units = useRef([]);

          useEffect(() => {
            const el = title.current;
            const mq = matchMedia('(prefers-reduced-motion: reduce)');
            const t0 = performance.now();
            let mx = 0, boost = 0, raf = 0, visible = true;

            const measure = () => {
              units.current = [...el.querySelectorAll('.ltwv-u')].map((u) => ({
                el: u, x: u.getBoundingClientRect().x + u.offsetWidth / 2,
              }));
            };
            const frame = (now) => {
              const t = (now - t0) / 1000;
              units.current.forEach((u, i) => {
                const near = Math.exp(-((u.x - mx) ** 2) / 9800) * boost;
                const y = Math.sin(t * 2.2 + i * 0.55) * 10 * ampRef.current * (1 + 2 * near);
                const r = Math.sin(t * 2.2 + i * 0.55 + 0.9) * 3 * ampRef.current * (1 + near);
                u.el.style.transform = `translateY(${y.toFixed(2)}px) rotate(${r.toFixed(2)}deg)`;
              });
              raf = requestAnimationFrame(frame);
            };
            const run = () => { cancelAnimationFrame(raf); if (!mq.matches && visible) raf = requestAnimationFrame(frame); };
            const move = (e) => { mx = e.clientX; boost = 1; };
            const leave = () => { boost = 0; };

            measure();
            addEventListener('resize', measure);
            el.addEventListener('pointermove', move);
            el.addEventListener('pointerleave', leave);
            mq.addEventListener('change', run);
            const io = new IntersectionObserver(([e]) => { visible = e.isIntersecting; run(); });
            io.observe(el);
            run();
            return () => {
              cancelAnimationFrame(raf); io.disconnect(); mq.removeEventListener('change', run);
              removeEventListener('resize', measure);
              el.removeEventListener('pointermove', move);
              el.removeEventListener('pointerleave', leave);
            };
          }, []);

          return (
            <>
              <style>{`
                .ltwv-title { display: flex; flex-wrap: wrap; margin: 0; font: 900 clamp(2rem, 8vw, 4rem)/1.1 'IBM Plex Sans', sans-serif;
                             perspective: 500px; cursor: default; }
                .ltwv-u { display: inline-block; will-change: transform; }
                .ltwv-amp { display: flex; gap: .6rem; align-items: center; margin-block-start: 1rem; }
              `}</style>
              <h2 className="ltwv-title" ref={title} aria-label={TITLE}>
                {TITLE.split('').map((ch, i) => (
                  <span className="ltwv-u" key={i} aria-hidden="true">{ch === ' ' ? '\u00A0' : ch}</span>
                ))}
              </h2>
              <label className="ltwv-amp">
                Swell
                <input type="range" min="0" max="2" step="0.05" value={amp}
                       onChange={(e) => setAmp(Number(e.target.value))} />
              </label>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- LetterWave.vue — letters bob like a wave; the pointer's neighbours leap -->
        <script setup>
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const TITLE = 'MAKING WAVES';
        const title = ref(null);
        const amp = ref(1);
        let units = [], raf = 0, io = null, t0 = 0, mx = 0, boost = 0;
        const mq = matchMedia('(prefers-reduced-motion: reduce)');

        function measure() {
          units = [...title.value.querySelectorAll('.ltwv-u')].map((u) => ({
            el: u, x: u.getBoundingClientRect().x + u.offsetWidth / 2,
          }));
        }
        function frame(now) {
          const t = (now - t0) / 1000;
          units.forEach((u, i) => {
            const near = Math.exp(-((u.x - mx) ** 2) / 9800) * boost;
            const y = Math.sin(t * 2.2 + i * 0.55) * 10 * amp.value * (1 + 2 * near);
            const r = Math.sin(t * 2.2 + i * 0.55 + 0.9) * 3 * amp.value * (1 + near);
            u.el.style.transform = `translateY(${y.toFixed(2)}px) rotate(${r.toFixed(2)}deg)`;
          });
          raf = requestAnimationFrame(frame);
        }
        function run() { cancelAnimationFrame(raf); if (!mq.matches) raf = requestAnimationFrame(frame); }

        onMounted(() => {
          t0 = performance.now();
          measure();
          addEventListener('resize', measure);
          mq.addEventListener('change', run);
          io = new IntersectionObserver(([e]) => (e.isIntersecting ? run() : cancelAnimationFrame(raf)));
          io.observe(title.value);
          run();
        });
        onBeforeUnmount(() => {
          cancelAnimationFrame(raf); io?.disconnect(); mq.removeEventListener('change', run);
          removeEventListener('resize', measure);
        });
        </script>

        <template>
          <h2 class="ltwv-title" ref="title" aria-label="TITLE"
              @pointermove="(mx = $event.clientX) && (boost = 1)" @pointerleave="boost = 0">
            <span v-for="(ch, i) in TITLE.split('')" :key="i" class="ltwv-u" aria-hidden="true">{{ ch === ' ' ? '\u00A0' : ch }}</span>
          </h2>
          <label class="ltwv-amp">
            Swell
            <input type="range" min="0" max="2" step="0.05" v-model.number="amp" />
          </label>
        </template>

        <style scoped>
        .ltwv-title { display: flex; flex-wrap: wrap; margin: 0; font: 900 clamp(2rem, 8vw, 4rem)/1.1 'IBM Plex Sans', sans-serif;
                     perspective: 500px; cursor: default; }
        .ltwv-u { display: inline-block; will-change: transform; }
        .ltwv-amp { display: flex; gap: .6rem; align-items: center; margin-block-start: 1rem; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- LetterWave.svelte — letters bob like a wave; the pointer's neighbours leap -->
        <script>
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          const TITLE = 'MAKING WAVES';
          let title;
          let amp = $state(1);
          let units = [], raf = 0, io = null, t0 = 0, mx = 0, boost = 0;
          const mq = matchMedia('(prefers-reduced-motion: reduce)');

          function measure() {
            units = [...title.querySelectorAll('.ltwv-u')].map((u) => ({
              el: u, x: u.getBoundingClientRect().x + u.offsetWidth / 2,
            }));
          }
          function frame(now) {
            const t = (now - t0) / 1000;
            units.forEach((u, i) => {
              const near = Math.exp(-((u.x - mx) ** 2) / 9800) * boost;
              const y = Math.sin(t * 2.2 + i * 0.55) * 10 * amp * (1 + 2 * near);
              const r = Math.sin(t * 2.2 + i * 0.55 + 0.9) * 3 * amp * (1 + near);
              u.el.style.transform = `translateY(${y.toFixed(2)}px) rotate(${r.toFixed(2)}deg)`;
            });
            raf = requestAnimationFrame(frame);
          }
          function run() { cancelAnimationFrame(raf); if (!mq.matches) raf = requestAnimationFrame(frame); }

          onMount(() => {
            t0 = performance.now();
            measure();
            addEventListener('resize', measure);
            mq.addEventListener('change', run);
            io = new IntersectionObserver(([e]) => (e.isIntersecting ? run() : cancelAnimationFrame(raf)));
            io.observe(title);
            run();
            return () => {
              cancelAnimationFrame(raf); io?.disconnect(); mq.removeEventListener('change', run);
              removeEventListener('resize', measure);
            };
          });
        </script>

        <h2 class="ltwv-title" bind:this={title} aria-label={TITLE}
            onpointermove={(e) => { mx = e.clientX; boost = 1; }} onpointerleave={() => (boost = 0)}>
          {#each TITLE.split('') as ch, i}
            <span class="ltwv-u" aria-hidden="true">{ch === ' ' ? '\u00A0' : ch}</span>
          {/each}
        </h2>
        <label class="ltwv-amp">
          Swell
          <input type="range" min="0" max="2" step="0.05" bind:value={amp} oninput={(e) => (amp = Number(e.target.value))} />
        </label>

        <style>
          .ltwv-title { display: flex; flex-wrap: wrap; margin: 0; font: 900 clamp(2rem, 8vw, 4rem)/1.1 'IBM Plex Sans', sans-serif;
                       perspective: 500px; cursor: default; }
          .ltwv-u { display: inline-block; will-change: transform; }
          .ltwv-amp { display: flex; gap: .6rem; align-items: center; margin-block-start: 1rem; }
        </style>
        SVELTE,
        ],
    ],

    'velocity-band' => [
        'title' => ['fa' => 'نوار سرعت‌خور', 'en' => 'Velocity band'],
        'icon' => 'zap',
        'oneLiner' => [
            'fa' => 'دو نوار متن بزرگ که سرعت و جهتشان را از شدت اسکرول می‌گیرند: جهشِ سریع نوارها را می‌پراند، مکث آرام‌شان می‌کند و اسکرول به بالا معکوس می‌رانند؛ دو نوار خلاف هم می‌دوند و در RTL هم جهت درست است.',
            'en' => 'Two oversized text bands that take speed and direction from your scroll: a flick sends them racing, a pause calms them and scrolling up drives them in reverse; the bands run against each other and keep the right direction under RTL.',
        ],
        'js' => true,
        'docs' => 'https://text-effects.colorion.co',
        'props' => [
            ['name' => 'velocity', 'type' => 'px/frame', 'default' => "'Δscroll × .35'", 'note' => [
                'fa' => 'شتاب اسکرول مستقیم به سرعت نوار می‌نشیند؛ علامت منفی (اسکرول بالا) جهت را برمی‌گرداند.',
                'en' => 'Scroll acceleration lands straight on the band speed; the negative sign (scrolling up) reverses the direction.',
            ]],
            ['name' => 'damping', 'type' => 'per frame', 'default' => "'.92'", 'note' => [
                'fa' => 'با مکث، سرعت به سمت پایهٔ آروم میرا می‌شود؛ جهش‌ها هم همین‌جا خنثی می‌شوند.',
                'en' => 'When you pause, the speed decays back to its calm base; flicks bleed off here too.',
            ]],
            ['name' => 'direction', 'type' => '±1', 'default' => "'opposite pair'", 'note' => [
                'fa' => 'دو نوار خلاف هم می‌دوند؛ با ریاضیات پیکسلی و مدولو، جهت در RTL هم مثل LTR درست می‌ماند.',
                'en' => 'The two bands run against each other; pixel math and modulo keep the direction right in RTL exactly as in LTR.',
            ]],
            ['name' => 'wrap', 'type' => 'modulo', 'default' => "'half track'", 'note' => [
                'fa' => 'مسیر دو نیم‌تکرار چسبانده شده و بی‌درز می‌پیچد؛ بی‌نهایت بدون DOM بی‌نهایت.',
                'en' => 'The track is two glued halves that wrap seamlessly — infinity without an infinite DOM.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        {{-- resources/views/demos/components/kinetic/velocity-band.blade.php --}}
        <div class="vbnd" x-data="{
                pos: [0, 0], vel: [0, 0], last: null, raf: 0, io: null, mq: null, onMq: null, visible: true,
                get tracks() { return [this.$refs.trackA, this.$refs.trackB]; },
                onScroll(e) {
                    const y = e.target.scrollTop;
                    const dv = this.last === null ? 0 : y - this.last;
                    this.last = y;
                    this.vel[0] += dv * 0.35;   // flick = jump, pause = calm
                    this.vel[1] -= dv * 0.35;   // the second band mirrors it
                },
                frame() {
                    this.tracks.forEach((t, k) => {
                        this.vel[k] = this.vel[k] * 0.92 + (k ? -0.6 : 0.6); // decay to base
                        const half = t.scrollWidth / 2;
                        this.pos[k] = ((this.pos[k] + this.vel[k]) % half + half) % half; // seamless wrap
                        t.style.transform = `translateX(${(-this.pos[k]).toFixed(1)}px)`;
                    });
                    this.raf = requestAnimationFrame(() => this.frame());
                },
                run() {
                    cancelAnimationFrame(this.raf);
                    if (this.mq.matches || !this.visible) return;
                    this.raf = requestAnimationFrame(() => this.frame());
                },
                init() {
                    this.mq = matchMedia('(prefers-reduced-motion: reduce)');
                    this.onMq = () => this.run();
                    this.mq.addEventListener('change', this.onMq);
                    this.io = new IntersectionObserver((e) => { this.visible = e[0].isIntersecting; this.run(); });
                    this.io.observe(this.$el);
                    this.run();
                },
                destroy() {
                    cancelAnimationFrame(this.raf);
                    if (this.io) this.io.disconnect();
                    if (this.mq && this.onMq) this.mq.removeEventListener('change', this.onMq);
                },
            }">
            <div class="vbnd-scroll" x-ref="scroller" x-on:scroll.passive="onScroll">
                <p class="vbnd-story">تغییرنامهٔ نسخهٔ ۴٫۲ — …</p>
            </div>
            <div class="vbnd-band"><div class="vbnd-track" x-ref="trackA">
                <span>نبوکس‌یوآی · تایپوگرافی جنبشی · سرعت را حس کن · </span><span aria-hidden="true">نبوکس‌یوآی · تایپوگرافی جنبشی · سرعت را حس کن · </span>
            </div></div>
            <div class="vbnd-band" data-rev><div class="vbnd-track" x-ref="trackB">
                <span>اسکرول تندتر · نوار تندتر · </span><span aria-hidden="true">اسکرول تندتر · نوار تندتر · </span>
            </div></div>
        </div>

        <style>
        .vbnd-scroll { block-size: 11rem; overflow-y: auto; border: 1px solid #e7e5e4; border-radius: 1rem; }
        .vbnd-band { overflow: clip; border-block: 1px solid #e7e5e4;
                     font: 900 clamp(1.6rem, 5vw, 3rem)/1.2 'Vazirmatn', sans-serif; }
        .vbnd-track { display: flex; inline-size: max-content; will-change: transform; }
        .vbnd-track span { white-space: nowrap; padding-inline: .2em; }
        .vbnd-band[data-rev] { color: #7c3aed; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        {{-- resources/views/demos/components/kinetic/velocity-band.blade.php --}}
        <div class="vbnd" x-data="{
                pos: [0, 0], vel: [0, 0], last: null, raf: 0, io: null, mq: null, onMq: null, visible: true,
                get tracks() { return [this.$refs.trackA, this.$refs.trackB]; },
                onScroll(e) {
                    const y = e.target.scrollTop;
                    const dv = this.last === null ? 0 : y - this.last;
                    this.last = y;
                    this.vel[0] += dv * 0.35;   // flick = jump, pause = calm
                    this.vel[1] -= dv * 0.35;   // the second band mirrors it
                },
                frame() {
                    this.tracks.forEach((t, k) => {
                        this.vel[k] = this.vel[k] * 0.92 + (k ? -0.6 : 0.6); // decay to base
                        const half = t.scrollWidth / 2;
                        this.pos[k] = ((this.pos[k] + this.vel[k]) % half + half) % half; // seamless wrap
                        t.style.transform = `translateX(${(-this.pos[k]).toFixed(1)}px)`;
                    });
                    this.raf = requestAnimationFrame(() => this.frame());
                },
                run() {
                    cancelAnimationFrame(this.raf);
                    if (this.mq.matches || !this.visible) return;
                    this.raf = requestAnimationFrame(() => this.frame());
                },
                init() {
                    this.mq = matchMedia('(prefers-reduced-motion: reduce)');
                    this.onMq = () => this.run();
                    this.mq.addEventListener('change', this.onMq);
                    this.io = new IntersectionObserver((e) => { this.visible = e[0].isIntersecting; this.run(); });
                    this.io.observe(this.$el);
                    this.run();
                },
                destroy() {
                    cancelAnimationFrame(this.raf);
                    if (this.io) this.io.disconnect();
                    if (this.mq && this.onMq) this.mq.removeEventListener('change', this.onMq);
                },
            }">
            <div class="vbnd-scroll" x-ref="scroller" x-on:scroll.passive="onScroll">
                <p class="vbnd-story">Release notes 4.2 — …</p>
            </div>
            <div class="vbnd-band"><div class="vbnd-track" x-ref="trackA">
                <span>NABUXUI · KINETIC TYPE · FEEL THE VELOCITY · </span><span aria-hidden="true">NABUXUI · KINETIC TYPE · FEEL THE VELOCITY · </span>
            </div></div>
            <div class="vbnd-band" data-rev><div class="vbnd-track" x-ref="trackB">
                <span>SCROLL HARDER · BAND FASTER · </span><span aria-hidden="true">SCROLL HARDER · BAND FASTER · </span>
            </div></div>
        </div>

        <style>
        .vbnd-scroll { block-size: 11rem; overflow-y: auto; border: 1px solid #e7e5e4; border-radius: 1rem; }
        .vbnd-band { overflow: clip; border-block: 1px solid #e7e5e4;
                     font: 900 clamp(1.6rem, 5vw, 3rem)/1.2 'IBM Plex Sans', sans-serif; }
        .vbnd-track { display: flex; inline-size: max-content; will-change: transform; }
        .vbnd-track span { white-space: nowrap; padding-inline: .2em; }
        .vbnd-band[data-rev] { color: #7c3aed; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Changelog.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import VelocityBand from '@/components/VelocityBand';

        export default function Changelog() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <VelocityBand />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // VelocityBand.jsx — marquee bands driven by the reader's scroll velocity
        import { useEffect, useRef } from 'react';
        import '@nabuxai/ui-core/css';

        const A = 'NABUXUI · KINETIC TYPE · FEEL THE VELOCITY · ';
        const B = 'SCROLL HARDER · BAND FASTER · ';

        export default function VelocityBand() {
          const scroller = useRef(null);
          const tracks = useRef([]);

          useEffect(() => {
            const pos = [0, 0], vel = [0, 0];
            let last = null, raf = 0;
            const mq = matchMedia('(prefers-reduced-motion: reduce)');

            const onScroll = () => {
              const dv = last === null ? 0 : scroller.current.scrollTop - last;
              last = scroller.current.scrollTop;
              vel[0] += dv * 0.35; // flick = jump
              vel[1] -= dv * 0.35; // the mirror band
            };
            const frame = () => {
              tracks.current.forEach((t, k) => {
                if (!t) return;
                vel[k] = vel[k] * 0.92 + (k ? -0.6 : 0.6);          // decay to calm base
                const half = t.scrollWidth / 2;
                pos[k] = ((pos[k] + vel[k]) % half + half) % half;   // seamless wrap
                t.style.transform = `translateX(${(-pos[k]).toFixed(1)}px)`;
              });
              raf = requestAnimationFrame(frame);
            };
            const run = () => { cancelAnimationFrame(raf); if (!mq.matches) raf = requestAnimationFrame(frame); };

            scroller.current.addEventListener('scroll', onScroll, { passive: true });
            mq.addEventListener('change', run);
            const io = new IntersectionObserver(([e]) => (e.isIntersecting ? run() : cancelAnimationFrame(raf)));
            io.observe(scroller.current);
            run();
            return () => {
              scroller.current?.removeEventListener('scroll', onScroll);
              mq.removeEventListener('change', run);
              io.disconnect(); cancelAnimationFrame(raf);
            };
          }, []);

          const track = (html, k) => (
            <div className="vbnd-track" ref={(el) => (tracks.current[k] = el)}>
              <span>{html}</span><span aria-hidden="true">{html}</span>
            </div>
          );

          return (
            <>
              <style>{`
                .vbnd-scroll { block-size: 11rem; overflow-y: auto; padding: 1rem;
                              border: 1px solid #e7e5e4; border-radius: 1rem; }
                .vbnd-band { overflow: clip; border-block: 1px solid #e7e5e4;
                             font: 900 clamp(1.6rem, 5vw, 3rem)/1.2 'IBM Plex Sans', sans-serif; }
                .vbnd-track { display: flex; inline-size: max-content; will-change: transform; }
                .vbnd-track span { white-space: nowrap; padding-inline: .2em; }
                .vbnd-band[data-rev] { color: #7c3aed; }
              `}</style>
              <div className="vbnd-scroll" ref={scroller}>
                <p>Release notes 4.2 — the motion tokens ship, the type scale tightens…</p>
              </div>
              <div className="vbnd-band">{track(A, 0)}</div>
              <div className="vbnd-band" data-rev>{track(B, 1)}</div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- VelocityBand.vue — marquee bands driven by the reader's scroll velocity -->
        <script setup>
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const scroller = ref(null);
        const tracks = ref([]);
        const pos = [0, 0], vel = [0, 0];
        let last = null, raf = 0, io = null;
        const mq = matchMedia('(prefers-reduced-motion: reduce)');

        function onScroll() {
          const dv = last === null ? 0 : scroller.value.scrollTop - last;
          last = scroller.value.scrollTop;
          vel[0] += dv * 0.35; // flick = jump
          vel[1] -= dv * 0.35; // the mirror band
        }
        function frame() {
          tracks.value.forEach((t, k) => {
            vel[k] = vel[k] * 0.92 + (k ? -0.6 : 0.6);          // decay to calm base
            const half = t.scrollWidth / 2;
            pos[k] = ((pos[k] + vel[k]) % half + half) % half;   // seamless wrap
            t.style.transform = `translateX(${(-pos[k]).toFixed(1)}px)`;
          });
          raf = requestAnimationFrame(frame);
        }
        function run() { cancelAnimationFrame(raf); if (!mq.matches) raf = requestAnimationFrame(frame); }

        onMounted(() => {
          scroller.value.addEventListener('scroll', onScroll, { passive: true });
          mq.addEventListener('change', run);
          io = new IntersectionObserver(([e]) => (e.isIntersecting ? run() : cancelAnimationFrame(raf)));
          io.observe(scroller.value);
          run();
        });
        onBeforeUnmount(() => {
          scroller.value?.removeEventListener('scroll', onScroll);
          mq.removeEventListener('change', run);
          io?.disconnect(); cancelAnimationFrame(raf);
        });
        </script>

        <template>
          <div class="vbnd-scroll" ref="scroller">
            <p>Release notes 4.2 — the motion tokens ship, the type scale tightens…</p>
          </div>
          <div class="vbnd-band">
            <div class="vbnd-track" ref="(el) => (tracks[0] = el)">
              <span>NABUXUI · KINETIC TYPE · FEEL THE VELOCITY · </span><span aria-hidden="true">NABUXUI · KINETIC TYPE · FEEL THE VELOCITY · </span>
            </div>
          </div>
          <div class="vbnd-band" data-rev>
            <div class="vbnd-track" ref="(el) => (tracks[1] = el)">
              <span>SCROLL HARDER · BAND FASTER · </span><span aria-hidden="true">SCROLL HARDER · BAND FASTER · </span>
            </div>
          </div>
        </template>

        <style scoped>
        .vbnd-scroll { block-size: 11rem; overflow-y: auto; padding: 1rem;
                      border: 1px solid #e7e5e4; border-radius: 1rem; }
        .vbnd-band { overflow: clip; border-block: 1px solid #e7e5e4;
                     font: 900 clamp(1.6rem, 5vw, 3rem)/1.2 'IBM Plex Sans', sans-serif; }
        .vbnd-track { display: flex; inline-size: max-content; will-change: transform; }
        .vbnd-track span { white-space: nowrap; padding-inline: .2em; }
        .vbnd-band[data-rev] { color: #7c3aed; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- VelocityBand.svelte — marquee bands driven by the reader's scroll velocity -->
        <script>
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          let scroller;
          let tracks = [];
          const pos = [0, 0], vel = [0, 0];
          let last = null, raf = 0, io = null;
          const mq = matchMedia('(prefers-reduced-motion: reduce)');

          function onScroll() {
            const dv = last === null ? 0 : scroller.scrollTop - last;
            last = scroller.scrollTop;
            vel[0] += dv * 0.35; // flick = jump
            vel[1] -= dv * 0.35; // the mirror band
          }
          function frame() {
            tracks.forEach((t, k) => {
              vel[k] = vel[k] * 0.92 + (k ? -0.6 : 0.6);          // decay to calm base
              const half = t.scrollWidth / 2;
              pos[k] = ((pos[k] + vel[k]) % half + half) % half;   // seamless wrap
              t.style.transform = `translateX(${(-pos[k]).toFixed(1)}px)`;
            });
            raf = requestAnimationFrame(frame);
          }
          function run() { cancelAnimationFrame(raf); if (!mq.matches) raf = requestAnimationFrame(frame); }

          onMount(() => {
            scroller.addEventListener('scroll', onScroll, { passive: true });
            mq.addEventListener('change', run);
            io = new IntersectionObserver(([e]) => (e.isIntersecting ? run() : cancelAnimationFrame(raf)));
            io.observe(scroller);
            run();
            return () => {
              scroller?.removeEventListener('scroll', onScroll);
              mq.removeEventListener('change', run);
              io?.disconnect(); cancelAnimationFrame(raf);
            };
          });
        </script>

        <div class="vbnd-scroll" bind:this={scroller}>
          <p>Release notes 4.2 — the motion tokens ship, the type scale tightens…</p>
        </div>
        <div class="vbnd-band">
          <div class="vbnd-track" bind:this={tracks[0]}>
            <span>NABUXUI · KINETIC TYPE · FEEL THE VELOCITY · </span><span aria-hidden="true">NABUXUI · KINETIC TYPE · FEEL THE VELOCITY · </span>
          </div>
        </div>
        <div class="vbnd-band" data-rev>
          <div class="vbnd-track" bind:this={tracks[1]}>
            <span>SCROLL HARDER · BAND FASTER · </span><span aria-hidden="true">SCROLL HARDER · BAND FASTER · </span>
          </div>
        </div>

        <style>
          .vbnd-scroll { block-size: 11rem; overflow-y: auto; padding: 1rem;
                         border: 1px solid #e7e5e4; border-radius: 1rem; }
          .vbnd-band { overflow: clip; border-block: 1px solid #e7e5e4;
                       font: 900 clamp(1.6rem, 5vw, 3rem)/1.2 'IBM Plex Sans', sans-serif; }
          .vbnd-track { display: flex; inline-size: max-content; will-change: transform; }
          .vbnd-track span { white-space: nowrap; padding-inline: .2em; }
          .vbnd-band[data-rev] { color: #7c3aed; }
        </style>
        SVELTE,
        ],
    ],

    'elastic-letters' => [
        'title' => ['fa' => 'حروف فنری', 'en' => 'Elastic letters'],
        'icon' => 'sparkles',
        'oneLiner' => [
            'fa' => 'تیتری که واحدهایش با هاور کشیده می‌شوند و با رها کردن، فنرِ میرا می‌لرزند و سرِ جایشان می‌نشینند: کشش با اسکیل، لرزش از یک انتگرال‌گیر فنری واقعی؛ لاتین در سطح حرف، فارسی در سطح واژه.',
            'en' => 'A headline whose units stretch under the pointer and, once released, wobble back on a real damped spring: the pull is a scale pair, the shimmy an actual spring integrator; Latin works per letter, Persian per whole word.',
        ],
        'js' => true,
        'docs' => 'https://text-effects.colorion.co',
        'props' => [
            ['name' => 'stiffness', 'type' => 'k', 'default' => "'170'", 'note' => [
                'fa' => 'سختی فنر؛ بالاتر یعنی کشش تندتر برمی‌گردد و لرزش کوتاه‌تر؛ با میرایی کم زیرمیرا می‌ماند و فنری می‌لرزد.',
                'en' => 'Spring stiffness; higher snaps the release back faster with a shorter wobble — kept underdamped against the damping so it still shimmies.',
            ]],
            ['name' => 'damping', 'type' => 'c', 'default' => "'10'", 'note' => [
                'fa' => 'میرایی کمتر از ۲√k فنر را زیرمیرا نگه می‌دارد؛ همین جا است که لرزش فنری از آن می‌آید.',
                'en' => 'Damping below 2√k keeps the spring underdamped; this is exactly where the elastic shimmy comes from.',
            ]],
            ['name' => 'stretch', 'type' => 'scale pair', 'default' => "'scaleX 1.5 · scaleY .76'", 'note' => [
                'fa' => 'شکل کشیده: پهنا باز و ارتفاع جمع می‌شود؛ مثل لاستیکی که با انگشت کشیده شود، نه متنِ بزرگ‌شده.',
                'en' => 'The pulled shape: width opens and height gives — a rubber band under a finger, not a bigger font.',
            ]],
            ['name' => 'units', 'type' => 'letters | words', 'default' => "'script-aware'", 'note' => [
                'fa' => 'لاتین حرف‌به‌حرف کش می‌آید؛ فارسی روی واژهٔ کامل فنری می‌شود تا اتصال حروف هرگز نشکند.',
                'en' => 'Latin stretches letter by letter; Persian springs as whole words so the joined script never breaks.',
            ]],
            ['name' => 'idle-park', 'type' => 'rAF', 'default' => "'energy ε'", 'note' => [
                'fa' => 'وقتی همهٔ فنرها بنشینند حلقهٔ فریمی خودش پارک می‌شود و بیرون از دید هم می‌خوابد؛ در حرکت کاهش‌یافته کشش بی‌لرزش و لحظه‌ای است.',
                'en' => 'Once every spring has settled the frame loop parks itself, and off screen it sleeps; under reduced motion the stretch lands instantly with no wobble.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        {{-- resources/views/demos/components/kinetic/elastic-letters.blade.php --}}
        <div class="eltr" x-data="{
                units: [], raf: 0, io: null, mq: null, onMq: null, visible: true,
                shape(e) { return 'scaleX(' + (1 + .5 * e).toFixed(3) + ') scaleY(' + (1 - .24 * e).toFixed(3) + ')'; },
                enter(i) {
                    if (this.mq.matches) { this.units[i].el.style.transform = this.shape(1); return; }
                    this.units[i].over = true; this.run();
                },
                leave(i) {
                    if (this.mq.matches) { this.units[i].el.style.transform = ''; return; }
                    this.units[i].over = false; this.run();
                },
                frame() {
                    let live = false;
                    this.units.forEach((u) => {
                        const target = u.over ? 1 : 0;
                        u.v += (170 * (target - u.e) - 10 * u.v) / 60;   // damped spring
                        u.e += u.v / 60;
                        if (Math.abs(u.v) < .002 && Math.abs(target - u.e) < .002) { u.v = 0; u.e = target; }
                        else live = true;
                        u.el.style.transform = u.e === 0 ? '' : this.shape(u.e);
                    });
                    if (!live) { this.raf = 0; return; }   // every spring asleep → park the loop
                    this.raf = requestAnimationFrame(() => this.frame());
                },
                run() {
                    cancelAnimationFrame(this.raf); this.raf = 0;
                    if (this.mq.matches || !this.visible) return;
                    this.raf = requestAnimationFrame(() => this.frame());
                },
                init() {
                    this.mq = matchMedia('(prefers-reduced-motion: reduce)');
                    this.onMq = () => {
                        if (this.mq.matches) this.units.forEach((u) => { u.el.style.transform = ''; u.over = false; });
                        this.run();
                    };
                    this.mq.addEventListener('change', this.onMq);
                    this.io = new IntersectionObserver((e) => { this.visible = e[0].isIntersecting; this.run(); });
                    this.io.observe(this.$el);
                    this.units = [...this.$refs.title.querySelectorAll('.eltr-u')].map((el) => ({ el, e: 0, v: 0, over: false }));
                },
                destroy() {
                    cancelAnimationFrame(this.raf);
                    if (this.io) this.io.disconnect();
                    if (this.mq && this.onMq) this.mq.removeEventListener('change', this.onMq);
                },
            }">
            <h4 class="eltr-title" x-ref="title">
                @foreach (['قلم', 'فنری', 'اسپرینگ‌تایپ'] as $i => $w)
                    <span class="eltr-u" x-on:pointerenter="enter({{ $i }})" x-on:pointerleave="leave({{ $i }})">{{ $w }}</span>{{ ' ' }}
                @endforeach
            </h4>
        </div>

        <style>
        .eltr-title { display: flex; flex-wrap: wrap; margin: 0;
                      font: 900 clamp(2rem, 8vw, 4rem)/1.2 'Vazirmatn', sans-serif; }
        .eltr-u { display: inline-block; will-change: transform; cursor: default; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        {{-- resources/views/demos/components/kinetic/elastic-letters.blade.php --}}
        <div class="eltr" x-data="{
                units: [], raf: 0, io: null, mq: null, onMq: null, visible: true,
                shape(e) { return 'scaleX(' + (1 + .5 * e).toFixed(3) + ') scaleY(' + (1 - .24 * e).toFixed(3) + ')'; },
                enter(i) {
                    if (this.mq.matches) { this.units[i].el.style.transform = this.shape(1); return; }
                    this.units[i].over = true; this.run();
                },
                leave(i) {
                    if (this.mq.matches) { this.units[i].el.style.transform = ''; return; }
                    this.units[i].over = false; this.run();
                },
                frame() {
                    let live = false;
                    this.units.forEach((u) => {
                        const target = u.over ? 1 : 0;
                        u.v += (170 * (target - u.e) - 10 * u.v) / 60;   // damped spring
                        u.e += u.v / 60;
                        if (Math.abs(u.v) < .002 && Math.abs(target - u.e) < .002) { u.v = 0; u.e = target; }
                        else live = true;
                        u.el.style.transform = u.e === 0 ? '' : this.shape(u.e);
                    });
                    if (!live) { this.raf = 0; return; }   // every spring asleep → park the loop
                    this.raf = requestAnimationFrame(() => this.frame());
                },
                run() {
                    cancelAnimationFrame(this.raf); this.raf = 0;
                    if (this.mq.matches || !this.visible) return;
                    this.raf = requestAnimationFrame(() => this.frame());
                },
                init() {
                    this.mq = matchMedia('(prefers-reduced-motion: reduce)');
                    this.onMq = () => {
                        if (this.mq.matches) this.units.forEach((u) => { u.el.style.transform = ''; u.over = false; });
                        this.run();
                    };
                    this.mq.addEventListener('change', this.onMq);
                    this.io = new IntersectionObserver((e) => { this.visible = e[0].isIntersecting; this.run(); });
                    this.io.observe(this.$el);
                    this.units = [...this.$refs.title.querySelectorAll('.eltr-u')].map((el) => ({ el, e: 0, v: 0, over: false }));
                },
                destroy() {
                    cancelAnimationFrame(this.raf);
                    if (this.io) this.io.disconnect();
                    if (this.mq && this.onMq) this.mq.removeEventListener('change', this.onMq);
                },
            }">
            <h4 class="eltr-title" x-ref="title">
                @foreach (str_split('SPRINGTYPE') as $i => $chr)
                    <span class="eltr-u" x-on:pointerenter="enter({{ $i }})" x-on:pointerleave="leave({{ $i }})">{{ $chr }}</span>
                @endforeach
            </h4>
        </div>

        <style>
        .eltr-title { display: flex; flex-wrap: wrap; margin: 0;
                      font: 900 clamp(2rem, 8vw, 4rem)/1.2 'IBM Plex Sans', sans-serif; letter-spacing: .02em; }
        .eltr-u { display: inline-block; will-change: transform; cursor: default; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Foundry.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import ElasticLetters from '@/components/ElasticLetters';

        export default function Foundry() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <ElasticLetters word="SPRINGTYPE" />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // ElasticLetters.jsx — units stretch on hover, released on a damped spring
        import { useEffect, useRef } from 'react';
        import '@nabuxai/ui-core/css';

        const shape = (e) => `scaleX(${(1 + 0.5 * e).toFixed(3)}) scaleY(${(1 - 0.24 * e).toFixed(3)})`;

        export default function ElasticLetters({ word = 'SPRINGTYPE' }) {
          const title = useRef(null);

          useEffect(() => {
            const mq = matchMedia('(prefers-reduced-motion: reduce)');
            const units = [...title.current.querySelectorAll('.eltr-u')].map((el) => ({ el, e: 0, v: 0, over: false }));
            let raf = 0, visible = true;

            const frame = () => {
              let live = false;
              units.forEach((u) => {
                const target = u.over ? 1 : 0;
                u.v += (170 * (target - u.e) - 10 * u.v) / 60;   // damped spring
                u.e += u.v / 60;
                if (Math.abs(u.v) < 0.002 && Math.abs(target - u.e) < 0.002) { u.v = 0; u.e = target; }
                else live = true;
                u.el.style.transform = u.e === 0 ? '' : shape(u.e);
              });
              if (!live) { raf = 0; return; }   // every spring asleep → park
              raf = requestAnimationFrame(frame);
            };
            const run = () => { cancelAnimationFrame(raf); raf = 0; if (!mq.matches && visible) raf = requestAnimationFrame(frame); };

            const enter = (u) => () => { if (mq.matches) { u.el.style.transform = shape(1); return; } u.over = true; run(); };
            const leave = (u) => () => { if (mq.matches) { u.el.style.transform = ''; return; } u.over = false; run(); };
            const offs = units.map((u) => {
              const on = enter(u), off = leave(u);
              u.el.addEventListener('pointerenter', on);
              u.el.addEventListener('pointerleave', off);
              return () => {
                u.el.removeEventListener('pointerenter', on);
                u.el.removeEventListener('pointerleave', off);
              };
            });

            const io = new IntersectionObserver(([e]) => { visible = e.isIntersecting; run(); });
            io.observe(title.current);
            return () => {
              io.disconnect(); cancelAnimationFrame(raf); offs.forEach((off) => off());
            };
          }, []);

          return (
            <>
              <style>{`
                .eltr-title { display: flex; flex-wrap: wrap; margin: 0;
                              font: 900 clamp(2rem, 8vw, 4rem)/1.2 'IBM Plex Sans', sans-serif; letter-spacing: .02em; }
                .eltr-u { display: inline-block; will-change: transform; cursor: default; }
              `}</style>
              <h4 className="eltr-title" ref={title}>
                {[...word].map((chr, i) => <span className="eltr-u" key={i}>{chr}</span>)}
              </h4>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- ElasticLetters.vue — units stretch on hover, released on a damped spring -->
        <script setup>
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const props = defineProps({ word: { type: String, default: 'SPRINGTYPE' } });
        const title = ref(null);
        const shape = (e) => `scaleX(${(1 + 0.5 * e).toFixed(3)}) scaleY(${(1 - 0.24 * e).toFixed(3)})`;

        let units = [], raf = 0, io = null;
        const mq = matchMedia('(prefers-reduced-motion: reduce)');

        function frame() {
          let live = false;
          units.forEach((u) => {
            const target = u.over ? 1 : 0;
            u.v += (170 * (target - u.e) - 10 * u.v) / 60;   // damped spring
            u.e += u.v / 60;
            if (Math.abs(u.v) < 0.002 && Math.abs(target - u.e) < 0.002) { u.v = 0; u.e = target; }
            else live = true;
            u.el.style.transform = u.e === 0 ? '' : shape(u.e);
          });
          if (!live) { raf = 0; return; }   // every spring asleep → park
          raf = requestAnimationFrame(frame);
        }
        function run() { cancelAnimationFrame(raf); raf = 0; if (!mq.matches) raf = requestAnimationFrame(frame); }
        function enter(i) {
          if (mq.matches) { units[i].el.style.transform = shape(1); return; }
          units[i].over = true; run();
        }
        function leave(i) {
          if (mq.matches) { units[i].el.style.transform = ''; return; }
          units[i].over = false; run();
        }

        onMounted(() => {
          units = [...title.value.querySelectorAll('.eltr-u')].map((el) => ({ el, e: 0, v: 0, over: false }));
          io = new IntersectionObserver(([e]) => (e.isIntersecting ? run() : cancelAnimationFrame(raf)));
          io.observe(title.value);
        });
        onBeforeUnmount(() => { io?.disconnect(); cancelAnimationFrame(raf); });
        </script>

        <template>
          <h4 class="eltr-title" ref="title">
            <span v-for="(chr, i) in [...word]" :key="i" class="eltr-u"
                  @pointerenter="enter(i)" @pointerleave="leave(i)">{{ chr }}</span>
          </h4>
        </template>

        <style scoped>
        .eltr-title { display: flex; flex-wrap: wrap; margin: 0;
                      font: 900 clamp(2rem, 8vw, 4rem)/1.2 'IBM Plex Sans', sans-serif; letter-spacing: .02em; }
        .eltr-u { display: inline-block; will-change: transform; cursor: default; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- ElasticLetters.svelte — units stretch on hover, released on a damped spring -->
        <script>
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          export let word = 'SPRINGTYPE';
          let title;
          const shape = (e) => `scaleX(${(1 + 0.5 * e).toFixed(3)}) scaleY(${(1 - 0.24 * e).toFixed(3)})`;

          let units = [], raf = 0, io = null;
          const mq = matchMedia('(prefers-reduced-motion: reduce)');

          function frame() {
            let live = false;
            units.forEach((u) => {
              const target = u.over ? 1 : 0;
              u.v += (170 * (target - u.e) - 10 * u.v) / 60;   // damped spring
              u.e += u.v / 60;
              if (Math.abs(u.v) < 0.002 && Math.abs(target - u.e) < 0.002) { u.v = 0; u.e = target; }
              else live = true;
              u.el.style.transform = u.e === 0 ? '' : shape(u.e);
            });
            if (!live) { raf = 0; return; }   // every spring asleep → park
            raf = requestAnimationFrame(frame);
          }
          function run() { cancelAnimationFrame(raf); raf = 0; if (!mq.matches) raf = requestAnimationFrame(frame); }
          function enter(i) {
            if (mq.matches) { units[i].el.style.transform = shape(1); return; }
            units[i].over = true; run();
          }
          function leave(i) {
            if (mq.matches) { units[i].el.style.transform = ''; return; }
            units[i].over = false; run();
          }

          onMount(() => {
            units = [...title.querySelectorAll('.eltr-u')].map((el) => ({ el, e: 0, v: 0, over: false }));
            io = new IntersectionObserver(([e]) => (e.isIntersecting ? run() : cancelAnimationFrame(raf)));
            io.observe(title);
            return () => { io?.disconnect(); cancelAnimationFrame(raf); };
          });
        </script>

        <h4 class="eltr-title" bind:this={title}>
          {#each [...word] as chr, i}
            <span class="eltr-u" on:pointerenter={() => enter(i)} on:pointerleave={() => leave(i)}>{chr}</span>
          {/each}
        </h4>

        <style>
          .eltr-title { display: flex; flex-wrap: wrap; margin: 0;
                        font: 900 clamp(2rem, 8vw, 4rem)/1.2 'IBM Plex Sans', sans-serif; letter-spacing: .02em; }
          .eltr-u { display: inline-block; will-change: transform; cursor: default; }
        </style>
        SVELTE,
        ],
    ],
];
