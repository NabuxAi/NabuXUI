<?php

/**
 * Demo manifest of the "navbars" group — five navigation bars inspired by the
 * navbar.gallery wave, rebuilt as live scenarios inside simulated page stages
 * (no fixed positioning, the scroll and hover live inside the stage): the
 * floating pill that condenses on scroll, the hover mega menu with keyboard
 * support, the glass navbar over a colourful hero, the macOS-style magnifying
 * dock and the center-logo navbar with its focus-trapped mobile drawer.
 * Scenarios live at resources/views/demos/components/navbars/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'نوبارها', 'en' => 'Navbars'],

    'floating-pill' => [
        'title' => ['fa' => 'نوبار قرصی شناور', 'en' => 'Floating pill navbar'],
        'icon' => 'star',
        'oneLiner' => [
            'fa' => 'قرصی شناور که بالای صفحه شنا می‌شود: نشانگرِ لینک فعال با فنر می‌لغزد، و به‌محض اسکرول، دکمه‌ها فشرده و وردمارک جمع می‌شود تا جا به محتوا بدهد — همه داخل یک استیج با اسکرول داخلی واقعی.',
            'en' => 'A pill that floats above the page: the active-link indicator springs across, and the moment you scroll the buttons tighten and the wordmark folds away to hand the room to the content — all inside a stage with real inner scrolling.',
        ],
        'js' => true,
        'docs' => 'https://navbar.gallery',
        'props' => [
            ['name' => 'sticky-offset', 'type' => 'length', 'default' => "'12px'", 'note' => [
                'fa' => 'فاصلهٔ قرص از لبهٔ بالای استیج هنگام چسبیدن؛ sticky مجاز، fixed هرگز.',
                'en' => 'How far the pill rides from the stage’s top edge when stuck; sticky is allowed, fixed never.',
            ]],
            ['name' => 'indicator', 'type' => 'motion', 'default' => "'slide · 260ms'", 'note' => [
                'fa' => 'قرصِ نشانگر با اندازه‌گیری واقعی جای لینک فعال می‌لغزد؛ در هر دو جهت درست است.',
                'en' => 'The indicator pill glides by measuring the real active link; correct in both directions.',
            ]],
            ['name' => 'condensed-at', 'type' => 'px', 'default' => "'24'", 'note' => [
                'fa' => 'آستانهٔ اسکرول داخلی (پیکسل) که قرص را جمع‌وجور می‌کند: پدینگ، فاصله‌ها و سایه.',
                'en' => 'The inner-scroll threshold in px that condenses the pill: padding, gaps and shadow.',
            ]],
            ['name' => 'radius', 'type' => 'length', 'default' => "'999px'", 'note' => [
                'fa' => 'گردی تمام‌قرص؛ همان امضای نوبارهای شناور مدرن.',
                'en' => 'The full-pill rounding; the signature of modern floating navbars.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        {{-- resources/views/components/floating-pill.blade.php — قرص شناور روی هر صفحه --}}
        <div class="fpn" x-data="{
            tab: 'product', shrunk: false,
            place(el) {
                this.$refs.links.style.setProperty('--fpn-x', el.offsetLeft + 'px');
                this.$refs.links.style.setProperty('--fpn-w', el.offsetWidth + 'px');
            },
        }">
            <div class="fpn-scroll" x-on:scroll.passive="shrunk = $event.target.scrollTop > 24">
                <nav class="fpn-nav" x-bind:data-shrunk="shrunk" aria-label="اصلی">
                    <b class="fpn-brand"><i aria-hidden="true"></i><span class="fpn-word">مریدین</span></b>
                    <div class="fpn-links" x-ref="links"
                         x-init="$nextTick(() => place($refs.links.querySelector('[aria-current]')))"
                         x-on:resize.window="place($refs.links.querySelector('[aria-current]'))">
                        <span class="fpn-ind" aria-hidden="true"></span>
                        <button type="button" x-bind:aria-current="tab === 'product' ? 'page' : null"
                                x-on:click="tab = 'product'; place($el)">محصول</button>
                        <button type="button" x-bind:aria-current="tab === 'solutions' ? 'page' : null"
                                x-on:click="tab = 'solutions'; place($el)">راه‌حل‌ها</button>
                        <button type="button" x-bind:aria-current="tab === 'pricing' ? 'page' : null"
                                x-on:click="tab = 'pricing'; place($el)">قیمت</button>
                    </div>
                    <a class="fpn-cta" href="#start">شروع رایگان</a>
                </nav>
                <header class="fpn-hero">…محتوای صفحه زیر قرص…</header>
            </div>
        </div>

        <style>
        .fpn-scroll { block-size: 30rem; overflow-y: auto; }
        .fpn-nav { position: sticky; inset-block-start: .75rem; z-index: 5; display: flex; align-items: center;
                   gap: .5rem; margin-inline: auto; inline-size: fit-content; padding: .5rem .5rem .5rem 1rem;
                   border-radius: 999px; background: #fff; box-shadow: 0 10px 30px #1118271a;
                   transition: padding .25s, box-shadow .25s; }
        .fpn-nav[data-shrunk='true'] { padding-block: .25rem; box-shadow: 0 6px 16px #11182726; }
        .fpn-links { position: relative; display: flex; gap: .25rem; }
        /* جابه‌جایی فیزیکی است چون از offsetLeft اندازه‌گیری می‌شود — در هر دو جهت درست است */
        .fpn-ind { position: absolute; inset-block: 0; left: var(--fpn-x, 0); inline-size: var(--fpn-w, 0);
                   border-radius: 999px; background: #eef2ff; transition: left .26s cubic-bezier(.2,0,0,1), inline-size .26s; }
        .fpn-links button { position: relative; padding: .45rem .9rem; border: 0; background: none; cursor: pointer; }
        .fpn-word { max-inline-size: 6rem; transition: max-inline-size .3s; overflow: clip; white-space: nowrap; }
        .fpn-nav[data-shrunk='true'] .fpn-word { max-inline-size: 0; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        {{-- resources/views/components/floating-pill.blade.php — the pill over any page --}}
        <div class="fpn" x-data="{
            tab: 'product', shrunk: false,
            place(el) {
                this.$refs.links.style.setProperty('--fpn-x', el.offsetLeft + 'px');
                this.$refs.links.style.setProperty('--fpn-w', el.offsetWidth + 'px');
            },
        }">
            <div class="fpn-scroll" x-on:scroll.passive="shrunk = $event.target.scrollTop > 24">
                <nav class="fpn-nav" x-bind:data-shrunk="shrunk" aria-label="Primary">
                    <b class="fpn-brand"><i aria-hidden="true"></i><span class="fpn-word">Meridian</span></b>
                    <div class="fpn-links" x-ref="links"
                         x-init="$nextTick(() => place($refs.links.querySelector('[aria-current]')))"
                         x-on:resize.window="place($refs.links.querySelector('[aria-current]'))">
                        <span class="fpn-ind" aria-hidden="true"></span>
                        <button type="button" x-bind:aria-current="tab === 'product' ? 'page' : null"
                                x-on:click="tab = 'product'; place($el)">Product</button>
                        <button type="button" x-bind:aria-current="tab === 'solutions' ? 'page' : null"
                                x-on:click="tab = 'solutions'; place($el)">Solutions</button>
                        <button type="button" x-bind:aria-current="tab === 'pricing' ? 'page' : null"
                                x-on:click="tab = 'pricing'; place($el)">Pricing</button>
                    </div>
                    <a class="fpn-cta" href="#start">Start free</a>
                </nav>
                <header class="fpn-hero">…page content under the pill…</header>
            </div>
        </div>

        <style>
        .fpn-scroll { block-size: 30rem; overflow-y: auto; }
        .fpn-nav { position: sticky; inset-block-start: .75rem; z-index: 5; display: flex; align-items: center;
                   gap: .5rem; margin-inline: auto; inline-size: fit-content; padding: .5rem .5rem .5rem 1rem;
                   border-radius: 999px; background: #fff; box-shadow: 0 10px 30px #1118271a;
                   transition: padding .25s, box-shadow .25s; }
        .fpn-nav[data-shrunk='true'] { padding-block: .25rem; box-shadow: 0 6px 16px #11182726; }
        .fpn-links { position: relative; display: flex; gap: .25rem; }
        /* physical on purpose: it pairs with the offsetLeft measurement, right in both directions */
        .fpn-ind { position: absolute; inset-block: 0; left: var(--fpn-x, 0); inline-size: var(--fpn-w, 0);
                   border-radius: 999px; background: #eef2ff; transition: left .26s cubic-bezier(.2,0,0,1), inline-size .26s; }
        .fpn-links button { position: relative; padding: .45rem .9rem; border: 0; background: none; cursor: pointer; }
        .fpn-word { max-inline-size: 6rem; transition: max-inline-size .3s; overflow: clip; white-space: nowrap; }
        .fpn-nav[data-shrunk='true'] .fpn-word { max-inline-size: 0; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Marketing.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import FloatingPillNav from '@/components/FloatingPillNav';

        export default function Marketing() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <FloatingPillNav />
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // FloatingPillNav.jsx — the pill that floats over the page and condenses on scroll
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const LINKS = [
          { id: 'product', label: 'Product' },
          { id: 'solutions', label: 'Solutions' },
          { id: 'pricing', label: 'Pricing' },
          { id: 'docs', label: 'Docs' },
        ];

        export default function FloatingPillNav() {
          const [tab, setTab] = useState('product');
          const [shrunk, setShrunk] = useState(false);
          const [ind, setInd] = useState({ x: 0, w: 0 });
          const scrollRef = useRef(null);
          const linkRefs = useRef({});

          // Measure the active link — physical left/width, correct in LTR and RTL alike.
          const place = (id) => {
            const el = linkRefs.current[id];
            if (el) setInd({ x: el.offsetLeft, w: el.offsetWidth });
          };
          useEffect(() => { place(tab); }, [tab]);
          useEffect(() => {
            const onResize = () => place(tab);
            window.addEventListener('resize', onResize);
            return () => window.removeEventListener('resize', onResize);
          }, [tab]);

          return (
            <>
              <style>{`
                .fpn-scroll { block-size: 30rem; overflow-y: auto; border-radius: 1rem;
                              background: linear-gradient(180deg, #eef2ff, #f8fafc 60%); }
                .fpn-nav { position: sticky; inset-block-start: .75rem; z-index: 5; display: flex; align-items: center;
                           gap: .5rem; margin-inline: auto; inline-size: fit-content; padding: .5rem .5rem .5rem 1rem;
                           border-radius: 999px; background: #fff; box-shadow: 0 10px 30px #1118271a;
                           transition: padding .25s, box-shadow .25s; font: 500 14px/1 Inter, sans-serif; }
                .fpn-nav[data-shrunk='true'] { padding-block: .25rem; box-shadow: 0 6px 16px #11182726; }
                .fpn-brand { display: flex; align-items: center; gap: .5rem; }
                .fpn-brand i { inline-size: 1.25rem; aspect-ratio: 1; border-radius: 50%;
                               background: linear-gradient(135deg, #6366f1, #d946ef); }
                .fpn-word { max-inline-size: 6rem; transition: max-inline-size .3s; overflow: clip; white-space: nowrap; }
                .fpn-nav[data-shrunk='true'] .fpn-word { max-inline-size: 0; }
                .fpn-links { position: relative; display: flex; gap: .25rem; }
                .fpn-ind { position: absolute; inset-block: 0; left: var(--fpn-x); inline-size: var(--fpn-w);
                           border-radius: 999px; background: #eef2ff;
                           transition: left .26s cubic-bezier(.2,0,0,1), inline-size .26s; }
                .fpn-links button { position: relative; padding: .45rem .9rem; border: 0; background: none; cursor: pointer; }
                .fpn-cta { padding: .5rem 1rem; border-radius: 999px; background: #4f46e5; color: #fff; text-decoration: none; }
              `}</style>
              <div
                className="fpn-scroll"
                ref={scrollRef}
                onScroll={(e) => setShrunk(e.currentTarget.scrollTop > 24)}
              >
                <nav className="fpn-nav" data-shrunk={shrunk} aria-label="Primary">
                  <b className="fpn-brand"><i aria-hidden="true"></i><span className="fpn-word">Meridian</span></b>
                  <div className="fpn-links" style={{ '--fpn-x': `${ind.x}px`, '--fpn-w': `${ind.w}px` }}>
                    <span className="fpn-ind" aria-hidden="true" />
                    {LINKS.map((l) => (
                      <button
                        key={l.id}
                        type="button"
                        aria-current={tab === l.id ? 'page' : null}
                        ref={(el) => { linkRefs.current[l.id] = el; }}
                        onClick={() => setTab(l.id)}
                      >
                        {l.label}
                      </button>
                    ))}
                  </div>
                  <a className="fpn-cta" href="#start">Start free</a>
                </nav>
                <header className="fpn-hero" style={{ padding: '9rem 1.5rem 4rem', textAlign: 'center' }}>
                  <h1>Release intelligence for global teams</h1>
                  <p>Traces, releases and alerts — one workspace from Frankfurt to Singapore.</p>
                </header>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- FloatingPillNav.vue — the pill that floats over the page and condenses on scroll -->
        <script setup lang="ts">
        import { nextTick, onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const LINKS = [
          { id: 'product', label: 'Product' },
          { id: 'solutions', label: 'Solutions' },
          { id: 'pricing', label: 'Pricing' },
          { id: 'docs', label: 'Docs' },
        ];
        const tab = ref('product');
        const shrunk = ref(false);
        const linksEl = ref<HTMLElement | null>(null);
        const ind = ref({ x: 0, w: 0 });

        // Measure the active link — physical left/width, correct in LTR and RTL alike.
        function place(id: string) {
          const el = linksEl.value?.querySelector<HTMLElement>(`[data-id="${id}"]`);
          if (el) ind.value = { x: el.offsetLeft, w: el.offsetWidth };
        }
        function pick(id: string) { tab.value = id; nextTick(() => place(id)); }
        function onScroll(e: Event) { shrunk.value = (e.target as HTMLElement).scrollTop > 24; }
        const onResize = () => place(tab.value);
        onMounted(() => { nextTick(() => place(tab.value)); window.addEventListener('resize', onResize); });
        onBeforeUnmount(() => window.removeEventListener('resize', onResize));
        </script>

        <template>
          <div class="fpn-scroll" @scroll.passive="onScroll">
            <nav class="fpn-nav" :data-shrunk="shrunk" aria-label="Primary">
              <b class="fpn-brand"><i aria-hidden="true"></i><span class="fpn-word">Meridian</span></b>
              <div class="fpn-links" ref="linksEl" :style="{ '--fpn-x': ind.x + 'px', '--fpn-w': ind.w + 'px' }">
                <span class="fpn-ind" aria-hidden="true"></span>
                <button v-for="l in LINKS" :key="l.id" type="button" :data-id="l.id"
                        :aria-current="tab === l.id ? 'page' : null" @click="pick(l.id)">
                  {{ l.label }}
                </button>
              </div>
              <a class="fpn-cta" href="#start">Start free</a>
            </nav>
            <header class="fpn-hero">
              <h1>Release intelligence for global teams</h1>
              <p>Traces, releases and alerts — one workspace from Frankfurt to Singapore.</p>
            </header>
          </div>
        </template>

        <style scoped>
        .fpn-scroll { block-size: 30rem; overflow-y: auto; border-radius: 1rem;
                      background: linear-gradient(180deg, #eef2ff, #f8fafc 60%); }
        .fpn-nav { position: sticky; inset-block-start: .75rem; z-index: 5; display: flex; align-items: center;
                   gap: .5rem; margin-inline: auto; inline-size: fit-content; padding: .5rem .5rem .5rem 1rem;
                   border-radius: 999px; background: #fff; box-shadow: 0 10px 30px #1118271a;
                   transition: padding .25s, box-shadow .25s; font: 500 14px/1 Inter, sans-serif; }
        .fpn-nav[data-shrunk='true'] { padding-block: .25rem; box-shadow: 0 6px 16px #11182726; }
        .fpn-brand { display: flex; align-items: center; gap: .5rem; }
        .fpn-brand i { inline-size: 1.25rem; aspect-ratio: 1; border-radius: 50%;
                       background: linear-gradient(135deg, #6366f1, #d946ef); }
        .fpn-word { max-inline-size: 6rem; transition: max-inline-size .3s; overflow: clip; white-space: nowrap; }
        .fpn-nav[data-shrunk='true'] .fpn-word { max-inline-size: 0; }
        .fpn-links { position: relative; display: flex; gap: .25rem; }
        .fpn-ind { position: absolute; inset-block: 0; left: var(--fpn-x); inline-size: var(--fpn-w);
                   border-radius: 999px; background: #eef2ff;
                   transition: left .26s cubic-bezier(.2,0,0,1), inline-size .26s; }
        .fpn-links button { position: relative; padding: .45rem .9rem; border: 0; background: none; cursor: pointer; }
        .fpn-cta { padding: .5rem 1rem; border-radius: 999px; background: #4f46e5; color: #fff; text-decoration: none; }
        .fpn-hero { padding: 9rem 1.5rem 4rem; text-align: center; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- FloatingPillNav.svelte — the pill that floats over the page and condenses on scroll -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const LINKS = [
            { id: 'product', label: 'Product' },
            { id: 'solutions', label: 'Solutions' },
            { id: 'pricing', label: 'Pricing' },
            { id: 'docs', label: 'Docs' },
          ];
          let tab = $state('product');
          let shrunk = $state(false);
          let linksEl = $state<HTMLElement | null>(null);
          let ind = $state({ x: 0, w: 0 });

          // Measure the active link — physical left/width, correct in LTR and RTL alike.
          function place(id: string) {
            const el = linksEl?.querySelector<HTMLElement>(`[data-id="${id}"]`);
            if (el) ind = { x: el.offsetLeft, w: el.offsetWidth };
          }
          function pick(id: string) { tab = id; requestAnimationFrame(() => place(id)); }
          function onScroll(e: Event) { shrunk = (e.target as HTMLElement).scrollTop > 24; }
          $effect(() => { linksEl; requestAnimationFrame(() => place(tab)); });
        </script>

        <div class="fpn-scroll" @scroll.passive={onScroll}>
          <nav class="fpn-nav" data-shrunk={shrunk} aria-label="Primary">
            <b class="fpn-brand"><i aria-hidden="true"></i><span class="fpn-word">Meridian</span></b>
            <div class="fpn-links" bind:this={linksEl} style={`--fpn-x: ${ind.x}px; --fpn-w: ${ind.w}px`}>
              <span class="fpn-ind" aria-hidden="true"></span>
              {#each LINKS as l (l.id)}
                <button type="button" data-id={l.id} aria-current={tab === l.id ? 'page' : null}
                        onclick={() => pick(l.id)}>
                  {l.label}
                </button>
              {/each}
            </div>
            <a class="fpn-cta" href="#start">Start free</a>
          </nav>
          <header class="fpn-hero">
            <h1>Release intelligence for global teams</h1>
            <p>Traces, releases and alerts — one workspace from Frankfurt to Singapore.</p>
          </header>
        </div>

        <style>
        .fpn-scroll { block-size: 30rem; overflow-y: auto; border-radius: 1rem;
                      background: linear-gradient(180deg, #eef2ff, #f8fafc 60%); }
        .fpn-nav { position: sticky; inset-block-start: .75rem; z-index: 5; display: flex; align-items: center;
                   gap: .5rem; margin-inline: auto; inline-size: fit-content; padding: .5rem .5rem .5rem 1rem;
                   border-radius: 999px; background: #fff; box-shadow: 0 10px 30px #1118271a;
                   transition: padding .25s, box-shadow .25s; font: 500 14px/1 Inter, sans-serif; }
        .fpn-nav[data-shrunk='true'] { padding-block: .25rem; box-shadow: 0 6px 16px #11182726; }
        .fpn-brand { display: flex; align-items: center; gap: .5rem; }
        .fpn-brand i { inline-size: 1.25rem; aspect-ratio: 1; border-radius: 50%;
                       background: linear-gradient(135deg, #6366f1, #d946ef); }
        .fpn-word { max-inline-size: 6rem; transition: max-inline-size .3s; overflow: clip; white-space: nowrap; }
        .fpn-nav[data-shrunk='true'] .fpn-word { max-inline-size: 0; }
        .fpn-links { position: relative; display: flex; gap: .25rem; }
        .fpn-ind { position: absolute; inset-block: 0; left: var(--fpn-x); inline-size: var(--fpn-w);
                   border-radius: 999px; background: #eef2ff;
                   transition: left .26s cubic-bezier(.2,0,0,1), inline-size .26s; }
        .fpn-links button { position: relative; padding: .45rem .9rem; border: 0; background: none; cursor: pointer; }
        .fpn-cta { padding: .5rem 1rem; border-radius: 999px; background: #4f46e5; color: #fff; text-decoration: none; }
        .fpn-hero { padding: 9rem 1.5rem 4rem; text-align: center; }
        </style>
        SVELTE,
        ],
    ],

    'mega-navbar' => [
        'title' => ['fa' => 'نوبار مگامنو', 'en' => 'Mega menu navbar'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'نوباری که با هاور باز می‌شود و غرق می‌کند: پنل چندستونه با سرستون‌ها، لینک‌های آیکون‌دار و کارت تخفیف گرادیانی — با کلیک و کیبورد هم باز می‌شود، تأخیر مهربانِ ۱۴۰ms دارد و Escape می‌بندد.',
            'en' => 'A navbar that opens on hover and means it: a multi-column panel with column heads, icon links and a gradient discount card — clickable and keyboard-friendly too, with a 140ms grace before closing and Escape to snap it shut.',
        ],
        'js' => true,
        'docs' => 'https://navbar.gallery',
        'props' => [
            ['name' => 'open-on', 'type' => 'string', 'default' => "'hover · click · focus'", 'note' => [
                'fa' => 'هاور روی ناحیه باز می‌کند، کلیک روی دکمهٔ وضعیت را برمی‌گرداند و فوکوسِ کیبورد هم نگه می‌دارد.',
                'en' => 'Hover over the zone opens it, click on the trigger toggles it, keyboard focus keeps it alive.',
            ]],
            ['name' => 'close-grace', 'type' => 'ms', 'default' => "'140'", 'note' => [
                'fa' => 'مهلت قبل از بستن؛ عبور موس از شکاف بین نوار و پنل منو را نمی‌بندد.',
                'en' => 'The delay before closing; sweeping the mouse across the bar-panel gap never dismisses the menu.',
            ]],
            ['name' => 'columns', 'type' => 'count', 'default' => "'2'", 'note' => [
                'fa' => 'ستون‌های لینک؛ در موبایل تک‌ستونه و اسکرول‌پذیر می‌شوند.',
                'en' => 'Link columns; on mobile they stack to one scrollable column.',
            ]],
            ['name' => 'promo-card', 'type' => 'node', 'default' => 'null', 'note' => [
                'fa' => 'شیار کارت گرادیانیِ انتهای پنل — جای پیشنهاد ویژه یا اعلام انتشار.',
                'en' => 'The gradient card slot at the panel’s end — for the special offer or the launch announcement.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        {{-- resources/views/components/mega-navbar.blade.php — مگامنوی هاوری --}}
        <div class="mgn" x-data="{
            mega: false, closer: null,
            open() { clearTimeout(this.closer); this.mega = true },
            later() { clearTimeout(this.closer); this.closer = setTimeout(() => this.mega = false, 140) },
        }" x-on:keydown.escape.window="mega = false">
            <header class="mgn-bar">
                <b class="mgn-brand"><i aria-hidden="true"></i>مریدین</b>
                <nav aria-label="اصلی">
                    <button type="button" x-bind:aria-expanded="mega" aria-haspopup="true"
                            x-on:click="mega = ! mega" x-on:mouseenter="open()"
                            x-on:keydown.arrow-down.prevent="open(); $nextTick(() => $refs.panel.querySelector('a').focus())">
                        محصول <i class="mgn-chev" aria-hidden="true">⌄</i>
                    </button>
                    <a href="#pricing">قیمت</a>
                    <a href="#docs">مستندات</a>
                </nav>
                <a class="mgn-cta" href="#demo">رزرو دمو</a>
            </header>

            <div class="mgn-panel" x-ref="panel" x-show="mega" x-cloak
                 x-on:mouseenter="open()" x-on:mouseleave="later()"
                 x-transition:enter="mgn-in" role="region" aria-label="منوی محصول">
                <div class="mgn-cols">
                    <section>
                        <h5>پلتفرم</h5>
                        <a href="#dash"><i>▤</i>داشبوردها<span>معماری زنده</span></a>
                        <a href="#alerts"><i>◔</i>مسیریابی هشدار<span>خورشیدگرد</span></a>
                    </section>
                    <section>
                        <h5>برای تیم‌ها</h5>
                        <a href="#analytics"><i>↗</i>تحلیل محصول<span>ریزش آزاد</span></a>
                        <a href="#sso"><i>⛨</i>SSO و SCIM<span>در سازمان</span></a>
                    </section>
                </div>
                <aside class="mgn-promo">
                    <b>مریدین پرو</b>
                    <span>۳۰٪ تخفیف پلن سالانه تا ۳۱ اکتبر</span>
                    <a href="#claim">دریافت اعتبار</a>
                </aside>
            </div>
        </div>

        <style>
        .mgn { position: relative; }
        .mgn-bar { position: absolute; inset-block-start: 0; inset-inline: 0; z-index: 10;
                   display: flex; align-items: center; gap: 1.25rem; padding: .8rem 1.25rem; }
        .mgn-panel { position: absolute; inset-block-start: 100%; inset-inline: 1rem; z-index: 9;
                     display: grid; grid-template-columns: 1fr 1fr 15rem; gap: 1.25rem; padding: 1.25rem;
                     border-radius: 1rem; background: #fff; box-shadow: 0 24px 60px #0f172a26; }
        .mgn-chev { transition: rotate .2s; }
        [aria-expanded='true'] .mgn-chev { rotate: 180deg; }
        .mgn-cols a { display: grid; grid-template-columns: auto 1fr; gap: .2rem .6rem; padding: .5rem;
                      border-radius: .6rem; text-decoration: none; }
        .mgn-cols a:hover { background: #eef2ff; }
        .mgn-promo { border-radius: .9rem; padding: 1rem; color: #fff;
                     background: linear-gradient(160deg, #4f46e5, #d946ef); }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        {{-- resources/views/components/mega-navbar.blade.php — the hover mega menu --}}
        <div class="mgn" x-data="{
            mega: false, closer: null,
            open() { clearTimeout(this.closer); this.mega = true },
            later() { clearTimeout(this.closer); this.closer = setTimeout(() => this.mega = false, 140) },
        }" x-on:keydown.escape.window="mega = false">
            <header class="mgn-bar">
                <b class="mgn-brand"><i aria-hidden="true"></i>Meridian</b>
                <nav aria-label="Primary">
                    <button type="button" x-bind:aria-expanded="mega" aria-haspopup="true"
                            x-on:click="mega = ! mega" x-on:mouseenter="open()"
                            x-on:keydown.arrow-down.prevent="open(); $nextTick(() => $refs.panel.querySelector('a').focus())">
                        Product <i class="mgn-chev" aria-hidden="true">⌄</i>
                    </button>
                    <a href="#pricing">Pricing</a>
                    <a href="#docs">Docs</a>
                </nav>
                <a class="mgn-cta" href="#demo">Book a demo</a>
            </header>

            <div class="mgn-panel" x-ref="panel" x-show="mega" x-cloak
                 x-on:mouseenter="open()" x-on:mouseleave="later()"
                 x-transition:enter="mgn-in" role="region" aria-label="Product menu">
                <div class="mgn-cols">
                    <section>
                        <h5>Platform</h5>
                        <a href="#dash"><i>▤</i>Dashboards<span>Live architecture</span></a>
                        <a href="#alerts"><i>◔</i>Alert routing<span>Follow-the-sun</span></a>
                    </section>
                    <section>
                        <h5>For teams</h5>
                        <a href="#analytics"><i>↗</i>Product analytics<span>Funnel-free</span></a>
                        <a href="#sso"><i>⛨</i>SSO &amp; SCIM<span>Enterprise ready</span></a>
                    </section>
                </div>
                <aside class="mgn-promo">
                    <b>Meridian Pro</b>
                    <span>30% off annual plans until Oct 31</span>
                    <a href="#claim">Claim credit</a>
                </aside>
            </div>
        </div>

        <style>
        .mgn { position: relative; }
        .mgn-bar { position: absolute; inset-block-start: 0; inset-inline: 0; z-index: 10;
                   display: flex; align-items: center; gap: 1.25rem; padding: .8rem 1.25rem; }
        .mgn-panel { position: absolute; inset-block-start: 100%; inset-inline: 1rem; z-index: 9;
                     display: grid; grid-template-columns: 1fr 1fr 15rem; gap: 1.25rem; padding: 1.25rem;
                     border-radius: 1rem; background: #fff; box-shadow: 0 24px 60px #0f172a26; }
        .mgn-chev { transition: rotate .2s; }
        [aria-expanded='true'] .mgn-chev { rotate: 180deg; }
        .mgn-cols a { display: grid; grid-template-columns: auto 1fr; gap: .2rem .6rem; padding: .5rem;
                      border-radius: .6rem; text-decoration: none; }
        .mgn-cols a:hover { background: #eef2ff; }
        .mgn-promo { border-radius: .9rem; padding: 1rem; color: #fff;
                     background: linear-gradient(160deg, #4f46e5, #d946ef); }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Overview.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import MegaNavbar from '@/components/MegaNavbar';

        export default function Overview() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <MegaNavbar />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // MegaNavbar.jsx — hover mega menu with click, keyboard and Escape
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const COLUMNS = [
          { head: 'Platform', links: [
            { icon: '▤', label: 'Dashboards', hint: 'Live architecture' },
            { icon: '◔', label: 'Alert routing', hint: 'Follow-the-sun' },
            { icon: '⧉', label: 'Integrations', hint: '120+ connectors' },
          ]},
          { head: 'For teams', links: [
            { icon: '↗', label: 'Product analytics', hint: 'No funnels required' },
            { icon: '⛨', label: 'SSO & SCIM', hint: 'Enterprise ready' },
            { icon: '⟳', label: 'Release notes', hint: 'Auto-drafted' },
          ]},
        ];

        export default function MegaNavbar() {
          const [mega, setMega] = useState(false);
          const closer = useRef(null);
          const open = () => { clearTimeout(closer.current); setMega(true); };
          const later = () => { clearTimeout(closer.current); closer.current = setTimeout(() => setMega(false), 140); };
          useEffect(() => () => clearTimeout(closer.current), []);
          // Escape snaps the panel shut from anywhere on the page.
          useEffect(() => {
            const onKey = (e) => { if (e.key === 'Escape') setMega(false); };
            window.addEventListener('keydown', onKey);
            return () => window.removeEventListener('keydown', onKey);
          }, []);

          return (
            <>
              <style>{`
                .mgn-zone { position: relative; font: 500 14px/1 Inter, sans-serif; color: #0f172a; }
                .mgn-bar { display: flex; align-items: center; gap: 1.25rem; padding: .8rem 1.25rem; }
                .mgn-bar nav { display: flex; gap: .25rem; margin-inline-start: 1rem; }
                .mgn-trigger { display: inline-flex; align-items: center; gap: .35rem; padding: .5rem .75rem;
                               border: 0; background: none; font: inherit; cursor: pointer; }
                .mgn-trigger[aria-expanded='true'] { background: #eef2ff; border-radius: 999px; }
                .mgn-chev { transition: rotate .2s; }
                .mgn-trigger[aria-expanded='true'] .mgn-chev { rotate: 180deg; }
                .mgn-panel { position: absolute; inset-inline: 1rem; inset-block-start: 100%; z-index: 9;
                             display: grid; grid-template-columns: 1fr 1fr 15rem; gap: 1.25rem; padding: 1.25rem;
                             border-radius: 1rem; background: #fff; box-shadow: 0 24px 60px #0f172a26; }
                .mgn-panel[data-open='false'] { display: none; }
                .mgn-cols h5 { margin: 0 0 .5rem; font-size: .72rem; letter-spacing: .08em;
                               text-transform: uppercase; color: #64748b; }
                .mgn-cols a { display: grid; grid-template-columns: auto 1fr; gap: .2rem .6rem; padding: .5rem;
                              border-radius: .6rem; text-decoration: none; color: inherit; }
                .mgn-cols a:hover { background: #eef2ff; }
                .mgn-cols span { grid-column: 2; color: #64748b; font-size: .76rem; }
                .mgn-promo { border-radius: .9rem; padding: 1rem; color: #fff;
                             background: linear-gradient(160deg, #4f46e5, #d946ef); }
                .mgn-promo a { color: #fff; font-weight: 600; }
              `}</style>
              <div className="mgn-zone" onMouseEnter={open} onMouseLeave={later}>
                <header className="mgn-bar">
                  <b className="mgn-brand"><i aria-hidden="true">◉</i> Meridian</b>
                  <nav aria-label="Primary">
                    <button type="button" className="mgn-trigger" aria-haspopup="true"
                            aria-expanded={mega} onClick={() => setMega((m) => !m)}>
                      Product <i className="mgn-chev" aria-hidden="true">⌄</i>
                    </button>
                    <a href="#pricing">Pricing</a>
                    <a href="#docs">Docs</a>
                  </nav>
                  <a className="mgn-cta" href="#demo">Book a demo</a>
                </header>
                <div className="mgn-panel" data-open={mega} role="region" aria-label="Product menu">
                  {COLUMNS.map((col) => (
                    <section key={col.head}>
                      <h5>{col.head}</h5>
                      {col.links.map((l) => (
                        <a key={l.label} href={`#${l.label}`}>
                          <i aria-hidden="true">{l.icon}</i>{l.label}<span>{l.hint}</span>
                        </a>
                      ))}
                    </section>
                  ))}
                  <aside className="mgn-promo">
                    <b>Meridian Pro</b>
                    <p>30% off annual plans until Oct 31.</p>
                    <a href="#claim">Claim credit</a>
                  </aside>
                </div>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- MegaNavbar.vue — hover mega menu with click, keyboard and Escape -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const COLUMNS = [
          { head: 'Platform', links: [
            { icon: '▤', label: 'Dashboards', hint: 'Live architecture' },
            { icon: '◔', label: 'Alert routing', hint: 'Follow-the-sun' },
            { icon: '⧉', label: 'Integrations', hint: '120+ connectors' },
          ]},
          { head: 'For teams', links: [
            { icon: '↗', label: 'Product analytics', hint: 'No funnels required' },
            { icon: '⛨', label: 'SSO & SCIM', hint: 'Enterprise ready' },
            { icon: '⟳', label: 'Release notes', hint: 'Auto-drafted' },
          ]},
        ];
        const mega = ref(false);
        let closer: ReturnType<typeof setTimeout> | undefined;
        const open = () => { clearTimeout(closer); mega.value = true; };
        const later = () => { clearTimeout(closer); closer = setTimeout(() => (mega.value = false), 140); };
        // Escape snaps the panel shut from anywhere on the page.
        const onKey = (e: KeyboardEvent) => { if (e.key === 'Escape') mega.value = false; };
        onMounted(() => window.addEventListener('keydown', onKey));
        onBeforeUnmount(() => { window.removeEventListener('keydown', onKey); clearTimeout(closer); });
        </script>

        <template>
          <div class="mgn-zone" @mouseenter="open" @mouseleave="later">
            <header class="mgn-bar">
              <b class="mgn-brand"><i aria-hidden="true">◉</i> Meridian</b>
              <nav aria-label="Primary">
                <button type="button" class="mgn-trigger" aria-haspopup="true"
                        :aria-expanded="mega" @click="mega = !mega">
                  Product <i class="mgn-chev" aria-hidden="true">⌄</i>
                </button>
                <a href="#pricing">Pricing</a>
                <a href="#docs">Docs</a>
              </nav>
              <a class="mgn-cta" href="#demo">Book a demo</a>
            </header>
            <div class="mgn-panel" :data-open="mega" role="region" aria-label="Product menu">
              <section v-for="col in COLUMNS" :key="col.head">
                <h5>{{ col.head }}</h5>
                <a v-for="l in col.links" :key="l.label" :href="'#' + l.label">
                  <i aria-hidden="true">{{ l.icon }}</i>{{ l.label }}<span>{{ l.hint }}</span>
                </a>
              </section>
              <aside class="mgn-promo">
                <b>Meridian Pro</b>
                <p>30% off annual plans until Oct 31.</p>
                <a href="#claim">Claim credit</a>
              </aside>
            </div>
          </div>
        </template>

        <style scoped>
        .mgn-zone { position: relative; font: 500 14px/1 Inter, sans-serif; color: #0f172a; }
        .mgn-bar { display: flex; align-items: center; gap: 1.25rem; padding: .8rem 1.25rem; }
        .mgn-bar nav { display: flex; gap: .25rem; margin-inline-start: 1rem; }
        .mgn-trigger { display: inline-flex; align-items: center; gap: .35rem; padding: .5rem .75rem;
                       border: 0; background: none; font: inherit; cursor: pointer; }
        .mgn-trigger[aria-expanded='true'] { background: #eef2ff; border-radius: 999px; }
        .mgn-chev { transition: rotate .2s; }
        .mgn-trigger[aria-expanded='true'] .mgn-chev { rotate: 180deg; }
        .mgn-panel { position: absolute; inset-inline: 1rem; inset-block-start: 100%; z-index: 9;
                     display: grid; grid-template-columns: 1fr 1fr 15rem; gap: 1.25rem; padding: 1.25rem;
                     border-radius: 1rem; background: #fff; box-shadow: 0 24px 60px #0f172a26; }
        .mgn-panel[data-open='false'] { display: none; }
        .mgn-cols h5 { margin: 0 0 .5rem; font-size: .72rem; letter-spacing: .08em;
                       text-transform: uppercase; color: #64748b; }
        .mgn-cols a { display: grid; grid-template-columns: auto 1fr; gap: .2rem .6rem; padding: .5rem;
                      border-radius: .6rem; text-decoration: none; color: inherit; }
        .mgn-cols a:hover { background: #eef2ff; }
        .mgn-cols span { grid-column: 2; color: #64748b; font-size: .76rem; }
        .mgn-promo { border-radius: .9rem; padding: 1rem; color: #fff;
                     background: linear-gradient(160deg, #4f46e5, #d946ef); }
        .mgn-promo a { color: #fff; font-weight: 600; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- MegaNavbar.svelte — hover mega menu with click, keyboard and Escape -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const COLUMNS = [
            { head: 'Platform', links: [
              { icon: '▤', label: 'Dashboards', hint: 'Live architecture' },
              { icon: '◔', label: 'Alert routing', hint: 'Follow-the-sun' },
              { icon: '⧉', label: 'Integrations', hint: '120+ connectors' },
            ]},
            { head: 'For teams', links: [
              { icon: '↗', label: 'Product analytics', hint: 'No funnels required' },
              { icon: '⛨', label: 'SSO & SCIM', hint: 'Enterprise ready' },
              { icon: '⟳', label: 'Release notes', hint: 'Auto-drafted' },
            ]},
          ];
          let mega = $state(false);
          let closer: ReturnType<typeof setTimeout> | undefined;
          const open = () => { clearTimeout(closer); mega = true; };
          const later = () => { clearTimeout(closer); closer = setTimeout(() => (mega = false), 140); };
          // Escape snaps the panel shut from anywhere on the page.
          $effect(() => {
            const onKey = (e: KeyboardEvent) => { if (e.key === 'Escape') mega = false; };
            window.addEventListener('keydown', onKey);
            return () => { window.removeEventListener('keydown', onKey); clearTimeout(closer); };
          });
        </script>

        <div class="mgn-zone" onmouseenter={open} onmouseleave={later}>
          <header class="mgn-bar">
            <b class="mgn-brand"><i aria-hidden="true">◉</i> Meridian</b>
            <nav aria-label="Primary">
              <button type="button" class="mgn-trigger" aria-haspopup="true"
                      aria-expanded={mega} onclick={() => (mega = !mega)}>
                Product <i class="mgn-chev" aria-hidden="true">⌄</i>
              </button>
              <a href="#pricing">Pricing</a>
              <a href="#docs">Docs</a>
            </nav>
            <a class="mgn-cta" href="#demo">Book a demo</a>
          </header>
          <div class="mgn-panel" data-open={mega} role="region" aria-label="Product menu">
            {#each COLUMNS as col (col.head)}
              <section>
                <h5>{col.head}</h5>
                {#each col.links as l (l.label)}
                  <a href={'#' + l.label}>
                    <i aria-hidden="true">{l.icon}</i>{l.label}<span>{l.hint}</span>
                  </a>
                {/each}
              </section>
            {/each}
            <aside class="mgn-promo">
              <b>Meridian Pro</b>
              <p>30% off annual plans until Oct 31.</p>
              <a href="#claim">Claim credit</a>
            </aside>
          </div>
        </div>

        <style>
        .mgn-zone { position: relative; font: 500 14px/1 Inter, sans-serif; color: #0f172a; }
        .mgn-bar { display: flex; align-items: center; gap: 1.25rem; padding: .8rem 1.25rem; }
        .mgn-bar nav { display: flex; gap: .25rem; margin-inline-start: 1rem; }
        .mgn-trigger { display: inline-flex; align-items: center; gap: .35rem; padding: .5rem .75rem;
                       border: 0; background: none; font: inherit; cursor: pointer; }
        .mgn-trigger[aria-expanded='true'] { background: #eef2ff; border-radius: 999px; }
        .mgn-chev { transition: rotate .2s; }
        .mgn-trigger[aria-expanded='true'] .mgn-chev { rotate: 180deg; }
        .mgn-panel { position: absolute; inset-inline: 1rem; inset-block-start: 100%; z-index: 9;
                     display: grid; grid-template-columns: 1fr 1fr 15rem; gap: 1.25rem; padding: 1.25rem;
                     border-radius: 1rem; background: #fff; box-shadow: 0 24px 60px #0f172a26; }
        .mgn-panel[data-open='false'] { display: none; }
        .mgn-cols h5 { margin: 0 0 .5rem; font-size: .72rem; letter-spacing: .08em;
                       text-transform: uppercase; color: #64748b; }
        .mgn-cols a { display: grid; grid-template-columns: auto 1fr; gap: .2rem .6rem; padding: .5rem;
                      border-radius: .6rem; text-decoration: none; color: inherit; }
        .mgn-cols a:hover { background: #eef2ff; }
        .mgn-cols span { grid-column: 2; color: #64748b; font-size: .76rem; }
        .mgn-promo { border-radius: .9rem; padding: 1rem; color: #fff;
                     background: linear-gradient(160deg, #4f46e5, #d946ef); }
        .mgn-promo a { color: #fff; font-weight: 600; }
        </style>
        SVELTE,
        ],
    ],

    'glass-navbar' => [
        'title' => ['fa' => 'نوبار شیشه‌ای', 'en' => 'Glass navbar'],
        'icon' => 'sparkles',
        'oneLiner' => [
            'fa' => 'شیشهٔ واقعی روی هیروی رنگی: بلور ۱۴ پیکسلی با اشباع بالا و حاشیهٔ نورگیرِ یک‌پیکسلی؛ لینک‌ها با هاور پیل شیشه‌ای می‌گیرند و سمت مقابل، دکمهٔ تمِ روز/شب و آواتار با حلقهٔ گرادیانی منتظرند.',
            'en' => 'Real glass over a colourful hero: 14px of blur with high saturation and a one-pixel light-catching rim; links grow a glass pill on hover while the far side carries the day/night mood button and a gradient-ringed avatar.',
        ],
        'js' => true,
        'docs' => 'https://navbar.gallery',
        'props' => [
            ['name' => 'blur', 'type' => 'length', 'default' => "'14px'", 'note' => [
                'fa' => 'قدرت backdrop-filter؛ هر چیز زیر نوبار نرم و شیشه‌ای دیده می‌شود.',
                'en' => 'The backdrop-filter strength; everything under the bar shows through soft and glassy.',
            ]],
            ['name' => 'saturate', 'type' => 'percent', 'default' => "'140%'", 'note' => [
                'fa' => 'اشباع رنگ پس‌زمینه از دل شیشه — همان کاری که شیشهٔ واقعی با نور می‌کند.',
                'en' => 'Colours saturate through the glass — what real glass does with light.',
            ]],
            ['name' => 'light-rim', 'type' => 'shadow', 'default' => "'inset 0 1px 0'", 'note' => [
                'fa' => 'حاشیهٔ نورگیرِ یک‌پیکسلی بالای نوبار و پیل‌های هاور؛ لبهٔ شیشه را می‌سازد.',
                'en' => 'The one-pixel light-catching rim on the bar’s top edge and the hover pills; it draws the glass edge.',
            ]],
            ['name' => 'hover-pill', 'type' => 'state', 'default' => "'on hover · focus'", 'note' => [
                'fa' => 'هر لینک با هاور و فوکوس پیل شیشه‌ای خودش را می‌گیرد — گردی تمام و بی‌مرز.',
                'en' => 'Each link grows its own glass pill on hover and focus — fully rounded, borderless.',
            ]],
            ['name' => 'mood', 'type' => 'string', 'default' => "'day'", 'note' => [
                'fa' => 'دکمهٔ تم، هیروی استیج را بین روز و شب عوض می‌کند و شیشه با آن هم‌رنگ می‌شود.',
                'en' => 'The mood button flips the stage hero between day and night, and the glass follows it.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        {{-- resources/views/components/glass-navbar.blade.php — نوبار شیشه‌ای روی هیرو --}}
        <div class="gln" x-data="{ mood: 'day' }">
            <section class="gln-stage" x-bind:data-mood="mood">
                <header class="gln-bar">
                    <b class="gln-brand"><i aria-hidden="true"></i>مریدین</b>
                    <nav aria-label="اصلی">
                        <a href="#product" aria-current="page">محصول</a>
                        <a href="#pricing">قیمت</a>
                        <a href="#docs">مستندات</a>
                    </nav>
                    <span class="gln-end">
                        <button type="button" class="gln-mood" x-bind:aria-label="mood === 'day' ? 'حالت شب' : 'حالت روز'"
                                x-on:click="mood = mood === 'day' ? 'night' : 'day'">
                            <span x-show="mood === 'day'">☾</span>
                            <span x-show="mood === 'night'" x-cloak>☀</span>
                        </button>
                        <span class="gln-ava" aria-label="لنا کواچ">LK</span>
                    </span>
                </header>
                <div class="gln-hero">
                    <h2>تأخیری که حس می‌شود، داشبوردی که خوانده می‌شود</h2>
                    <p>از فرانکفورت تا سنگاپور، در یک نگاه.</p>
                </div>
            </section>
        </div>

        <style>
        .gln-stage { position: relative; min-block-size: 26rem; overflow: clip; border-radius: 1rem;
                     background: linear-gradient(140deg, #22d3ee, #818cf8 55%, #f472b6); }
        .gln-bar { position: absolute; inset-block-start: 0; inset-inline: 0; z-index: 5;
                   display: flex; align-items: center; gap: 1.25rem; padding: 1rem 1.5rem; color: #fff;
                   background: rgba(255, 255, 255, .12); border-block-end: 1px solid rgba(255, 255, 255, .18);
                   backdrop-filter: blur(14px) saturate(140%);
                   box-shadow: inset 0 1px 0 rgba(255, 255, 255, .35); }
        .gln-stage[data-mood='night'] { background: linear-gradient(140deg, #0f172a, #3730a3 60%, #7e22ce); }
        .gln-bar nav { display: flex; gap: .25rem; }
        .gln-bar nav a { padding: .5rem .9rem; border-radius: 999px; color: #fff; text-decoration: none;
                         transition: background-color .18s, box-shadow .18s; }
        .gln-bar nav a:hover, .gln-bar nav a:focus-visible { background: rgba(255, 255, 255, .16);
                         box-shadow: inset 0 1px 0 rgba(255, 255, 255, .3); }
        .gln-ava { display: grid; place-items: center; inline-size: 2.2rem; aspect-ratio: 1; border-radius: 50%;
                   background: rgba(255, 255, 255, .18); box-shadow: 0 0 0 2px rgba(255, 255, 255, .55); font-size: .72rem; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        {{-- resources/views/components/glass-navbar.blade.php — glass over the hero --}}
        <div class="gln" x-data="{ mood: 'day' }">
            <section class="gln-stage" x-bind:data-mood="mood">
                <header class="gln-bar">
                    <b class="gln-brand"><i aria-hidden="true"></i>Meridian</b>
                    <nav aria-label="Primary">
                        <a href="#product" aria-current="page">Product</a>
                        <a href="#pricing">Pricing</a>
                        <a href="#docs">Docs</a>
                    </nav>
                    <span class="gln-end">
                        <button type="button" class="gln-mood" x-bind:aria-label="mood === 'day' ? 'Night mood' : 'Day mood'"
                                x-on:click="mood = mood === 'day' ? 'night' : 'day'">
                            <span x-show="mood === 'day'">☾</span>
                            <span x-show="mood === 'night'" x-cloak>☀</span>
                        </button>
                        <span class="gln-ava" aria-label="Lena Kovacs">LK</span>
                    </span>
                </header>
                <div class="gln-hero">
                    <h2>Latency you can feel, dashboards you can read</h2>
                    <p>Frankfurt to Singapore, in one glance.</p>
                </div>
            </section>
        </div>

        <style>
        .gln-stage { position: relative; min-block-size: 26rem; overflow: clip; border-radius: 1rem;
                     background: linear-gradient(140deg, #22d3ee, #818cf8 55%, #f472b6); }
        .gln-bar { position: absolute; inset-block-start: 0; inset-inline: 0; z-index: 5;
                   display: flex; align-items: center; gap: 1.25rem; padding: 1rem 1.5rem; color: #fff;
                   background: rgba(255, 255, 255, .12); border-block-end: 1px solid rgba(255, 255, 255, .18);
                   backdrop-filter: blur(14px) saturate(140%);
                   box-shadow: inset 0 1px 0 rgba(255, 255, 255, .35); }
        .gln-stage[data-mood='night'] { background: linear-gradient(140deg, #0f172a, #3730a3 60%, #7e22ce); }
        .gln-bar nav { display: flex; gap: .25rem; }
        .gln-bar nav a { padding: .5rem .9rem; border-radius: 999px; color: #fff; text-decoration: none;
                         transition: background-color .18s, box-shadow .18s; }
        .gln-bar nav a:hover, .gln-bar nav a:focus-visible { background: rgba(255, 255, 255, .16);
                         box-shadow: inset 0 1px 0 rgba(255, 255, 255, .3); }
        .gln-ava { display: grid; place-items: center; inline-size: 2.2rem; aspect-ratio: 1; border-radius: 50%;
                   background: rgba(255, 255, 255, .18); box-shadow: 0 0 0 2px rgba(255, 255, 255, .55); font-size: .72rem; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Landing.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import GlassNavbar from '@/components/GlassNavbar';

        export default function Landing() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <GlassNavbar />
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // GlassNavbar.jsx — blur + saturate + light rim over a colourful hero
        import { useState } from 'react';
        import '@nabuxai/ui-core/css';

        const LINKS = ['Product', 'Pricing', 'Docs'];

        export default function GlassNavbar() {
          const [mood, setMood] = useState('day');
          const night = mood === 'night';

          return (
            <>
              <style>{`
                .gln-stage { position: relative; min-block-size: 26rem; overflow: clip; border-radius: 1rem;
                             background: linear-gradient(140deg, #22d3ee, #818cf8 55%, #f472b6);
                             font: 500 14px/1 Inter, sans-serif; color: #fff; }
                .gln-stage[data-mood='night'] { background: linear-gradient(140deg, #0f172a, #3730a3 60%, #7e22ce); }
                .gln-orb { position: absolute; border-radius: 50%; filter: blur(2px); opacity: .5;
                           pointer-events: none; }
                .gln-bar { position: absolute; inset-block-start: 0; inset-inline: 0; z-index: 5;
                           display: flex; align-items: center; gap: 1.25rem; padding: 1rem 1.5rem;
                           background: rgba(255, 255, 255, .12); border-block-end: 1px solid rgba(255, 255, 255, .18);
                           backdrop-filter: blur(14px) saturate(140%);
                           box-shadow: inset 0 1px 0 rgba(255, 255, 255, .35); }
                .gln-brand { display: inline-flex; align-items: center; gap: .5rem; font-weight: 700; }
                .gln-brand i { inline-size: 1.15rem; aspect-ratio: 1; border-radius: .35rem;
                               background: rgba(255, 255, 255, .3); }
                .gln-bar nav { display: flex; gap: .25rem; }
                .gln-bar nav a { padding: .5rem .9rem; border-radius: 999px; color: #fff; text-decoration: none;
                                 transition: background-color .18s, box-shadow .18s; }
                .gln-bar nav a:hover, .gln-bar nav a:focus-visible { background: rgba(255, 255, 255, .16);
                                 box-shadow: inset 0 1px 0 rgba(255, 255, 255, .3); }
                .gln-bar nav a[aria-current='page'] { background: rgba(255, 255, 255, .16); font-weight: 600; }
                .gln-end { margin-inline-start: auto; display: inline-flex; align-items: center; gap: .75rem; }
                .gln-mood { display: grid; place-items: center; inline-size: 2.2rem; aspect-ratio: 1;
                            border: 0; border-radius: 50%; background: rgba(255, 255, 255, .16); color: #fff;
                            cursor: pointer; box-shadow: inset 0 1px 0 rgba(255, 255, 255, .3); }
                .gln-ava { display: grid; place-items: center; inline-size: 2.2rem; aspect-ratio: 1; border-radius: 50%;
                           background: rgba(255, 255, 255, .18); box-shadow: 0 0 0 2px rgba(255, 255, 255, .55);
                           font-size: .72rem; }
                .gln-hero { position: relative; z-index: 1; padding: 11rem 1.5rem 2.5rem; text-align: center; }
              `}</style>
              <section className="gln-stage" data-mood={mood}>
                <span className="gln-orb" style={{ insetBlockStart: '5rem', insetInlineStart: '12%', inlineSize: '7rem', aspectRatio: 1, background: 'rgba(255,255,255,.35)' }} />
                <span className="gln-orb" style={{ insetBlockEnd: '3rem', insetInlineEnd: '10%', inlineSize: '5rem', aspectRatio: 1, background: 'rgba(255,255,255,.25)' }} />
                <header className="gln-bar">
                  <b className="gln-brand"><i aria-hidden="true"></i>Meridian</b>
                  <nav aria-label="Primary">
                    {LINKS.map((l, i) => (
                      <a key={l} href={`#${l.toLowerCase()}`} aria-current={i === 0 ? 'page' : null}>{l}</a>
                    ))}
                  </nav>
                  <span className="gln-end">
                    <button type="button" className="gln-mood" aria-label={night ? 'Day mood' : 'Night mood'}
                            onClick={() => setMood(night ? 'day' : 'night')}>
                      {night ? '☀' : '☾'}
                    </button>
                    <span className="gln-ava" aria-label="Lena Kovacs">LK</span>
                  </span>
                </header>
                <div className="gln-hero">
                  <h2>Latency you can feel, dashboards you can read</h2>
                  <p>Frankfurt to Singapore, in one glance.</p>
                </div>
              </section>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- GlassNavbar.vue — blur + saturate + light rim over a colourful hero -->
        <script setup lang="ts">
        import { ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const LINKS = ['Product', 'Pricing', 'Docs'];
        const mood = ref<'day' | 'night'>('day');
        </script>

        <template>
          <section class="gln-stage" :data-mood="mood">
            <header class="gln-bar">
              <b class="gln-brand"><i aria-hidden="true"></i>Meridian</b>
              <nav aria-label="Primary">
                <a v-for="(l, i) in LINKS" :key="l" :href="'#' + l.toLowerCase()"
                   :aria-current="i === 0 ? 'page' : null">{{ l }}</a>
              </nav>
              <span class="gln-end">
                <button type="button" class="gln-mood"
                        :aria-label="mood === 'day' ? 'Night mood' : 'Day mood'"
                        @click="mood = mood === 'day' ? 'night' : 'day'">
                  {{ mood === 'night' ? '☀' : '☾' }}
                </button>
                <span class="gln-ava" aria-label="Lena Kovacs">LK</span>
              </span>
            </header>
            <div class="gln-hero">
              <h2>Latency you can feel, dashboards you can read</h2>
              <p>Frankfurt to Singapore, in one glance.</p>
            </div>
          </section>
        </template>

        <style scoped>
        .gln-stage { position: relative; min-block-size: 26rem; overflow: clip; border-radius: 1rem;
                     background: linear-gradient(140deg, #22d3ee, #818cf8 55%, #f472b6);
                     font: 500 14px/1 Inter, sans-serif; color: #fff; }
        .gln-stage[data-mood='night'] { background: linear-gradient(140deg, #0f172a, #3730a3 60%, #7e22ce); }
        .gln-bar { position: absolute; inset-block-start: 0; inset-inline: 0; z-index: 5;
                   display: flex; align-items: center; gap: 1.25rem; padding: 1rem 1.5rem;
                   background: rgba(255, 255, 255, .12); border-block-end: 1px solid rgba(255, 255, 255, .18);
                   backdrop-filter: blur(14px) saturate(140%);
                   box-shadow: inset 0 1px 0 rgba(255, 255, 255, .35); }
        .gln-brand { display: inline-flex; align-items: center; gap: .5rem; font-weight: 700; }
        .gln-brand i { inline-size: 1.15rem; aspect-ratio: 1; border-radius: .35rem;
                       background: rgba(255, 255, 255, .3); }
        .gln-bar nav { display: flex; gap: .25rem; }
        .gln-bar nav a { padding: .5rem .9rem; border-radius: 999px; color: #fff; text-decoration: none;
                         transition: background-color .18s, box-shadow .18s; }
        .gln-bar nav a:hover, .gln-bar nav a:focus-visible { background: rgba(255, 255, 255, .16);
                         box-shadow: inset 0 1px 0 rgba(255, 255, 255, .3); }
        .gln-bar nav a[aria-current='page'] { background: rgba(255, 255, 255, .16); font-weight: 600; }
        .gln-end { margin-inline-start: auto; display: inline-flex; align-items: center; gap: .75rem; }
        .gln-mood { display: grid; place-items: center; inline-size: 2.2rem; aspect-ratio: 1;
                    border: 0; border-radius: 50%; background: rgba(255, 255, 255, .16); color: #fff;
                    cursor: pointer; box-shadow: inset 0 1px 0 rgba(255, 255, 255, .3); }
        .gln-ava { display: grid; place-items: center; inline-size: 2.2rem; aspect-ratio: 1; border-radius: 50%;
                   background: rgba(255, 255, 255, .18); box-shadow: 0 0 0 2px rgba(255, 255, 255, .55);
                   font-size: .72rem; }
        .gln-hero { position: relative; z-index: 1; padding: 11rem 1.5rem 2.5rem; text-align: center; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- GlassNavbar.svelte — blur + saturate + light rim over a colourful hero -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const LINKS = ['Product', 'Pricing', 'Docs'];
          let mood = $state<'day' | 'night'>('day');
        </script>

        <section class="gln-stage" data-mood={mood}>
          <header class="gln-bar">
            <b class="gln-brand"><i aria-hidden="true"></i>Meridian</b>
            <nav aria-label="Primary">
              {#each LINKS as l, i (l)}
                <a href={'#' + l.toLowerCase()} aria-current={i === 0 ? 'page' : null}>{l}</a>
              {/each}
            </nav>
            <span class="gln-end">
              <button type="button" class="gln-mood"
                      aria-label={mood === 'day' ? 'Night mood' : 'Day mood'}
                      onclick={() => (mood = mood === 'day' ? 'night' : 'day')}>
                {mood === 'night' ? '☀' : '☾'}
              </button>
              <span class="gln-ava" aria-label="Lena Kovacs">LK</span>
            </span>
          </header>
          <div class="gln-hero">
            <h2>Latency you can feel, dashboards you can read</h2>
            <p>Frankfurt to Singapore, in one glance.</p>
          </div>
        </section>

        <style>
        .gln-stage { position: relative; min-block-size: 26rem; overflow: clip; border-radius: 1rem;
                     background: linear-gradient(140deg, #22d3ee, #818cf8 55%, #f472b6);
                     font: 500 14px/1 Inter, sans-serif; color: #fff; }
        .gln-stage[data-mood='night'] { background: linear-gradient(140deg, #0f172a, #3730a3 60%, #7e22ce); }
        .gln-bar { position: absolute; inset-block-start: 0; inset-inline: 0; z-index: 5;
                   display: flex; align-items: center; gap: 1.25rem; padding: 1rem 1.5rem;
                   background: rgba(255, 255, 255, .12); border-block-end: 1px solid rgba(255, 255, 255, .18);
                   backdrop-filter: blur(14px) saturate(140%);
                   box-shadow: inset 0 1px 0 rgba(255, 255, 255, .35); }
        .gln-brand { display: inline-flex; align-items: center; gap: .5rem; font-weight: 700; }
        .gln-brand i { inline-size: 1.15rem; aspect-ratio: 1; border-radius: .35rem;
                       background: rgba(255, 255, 255, .3); }
        .gln-bar nav { display: flex; gap: .25rem; }
        .gln-bar nav a { padding: .5rem .9rem; border-radius: 999px; color: #fff; text-decoration: none;
                         transition: background-color .18s, box-shadow .18s; }
        .gln-bar nav a:hover, .gln-bar nav a:focus-visible { background: rgba(255, 255, 255, .16);
                         box-shadow: inset 0 1px 0 rgba(255, 255, 255, .3); }
        .gln-bar nav a[aria-current='page'] { background: rgba(255, 255, 255, .16); font-weight: 600; }
        .gln-end { margin-inline-start: auto; display: inline-flex; align-items: center; gap: .75rem; }
        .gln-mood { display: grid; place-items: center; inline-size: 2.2rem; aspect-ratio: 1;
                    border: 0; border-radius: 50%; background: rgba(255, 255, 255, .16); color: #fff;
                    cursor: pointer; box-shadow: inset 0 1px 0 rgba(255, 255, 255, .3); }
        .gln-ava { display: grid; place-items: center; inline-size: 2.2rem; aspect-ratio: 1; border-radius: 50%;
                   background: rgba(255, 255, 255, .18); box-shadow: 0 0 0 2px rgba(255, 255, 255, .55);
                   font-size: .72rem; }
        .gln-hero { position: relative; z-index: 1; padding: 11rem 1.5rem 2.5rem; text-align: center; }
        </style>
        SVELTE,
        ],
    ],

    'dock-navbar' => [
        'title' => ['fa' => 'داک ناوبری', 'en' => 'Dock navbar'],
        'icon' => 'command',
        'oneLiner' => [
            'fa' => 'داک ماک‌آنگار پایین استیج: با نزدیک‌شدن موس، آیتم‌ها با منحنی گاوسی بزرگ می‌شوند و همسایه‌ها جابه‌جا؛ تولتیپ بالای آیتم می‌آید، نقطهٔ نشانگر زیر آیتم فعال می‌نشیند و کلیک، مقصد را عوض می‌کند.',
            'en' => 'The macOS-style dock at the bottom of the stage: items magnify on a gaussian curve as the mouse approaches and nudge their neighbours, a tooltip rises above, the dot indicator parks under the active item, and a click changes destination.',
        ],
        'js' => true,
        'docs' => 'https://navbar.gallery',
        'props' => [
            ['name' => 'magnification', 'type' => 'scale', 'default' => "'1 → 1.55×'", 'note' => [
                'fa' => 'بیشینهٔ بزرگ‌شدگی زیر موس؛ با فاصله، نمایی کوچک می‌شود.',
                'en' => 'The peak magnification under the cursor; it decays exponentially with distance.',
            ]],
            ['name' => 'spread', 'type' => 'px', 'default' => "'64'", 'note' => [
                'fa' => 'شعاع اثر گاوسی؛ همسایه‌های نزدیک هم کمی بزرگ می‌شوند.',
                'en' => 'The gaussian radius; near neighbours swell a little too.',
            ]],
            ['name' => 'tooltip', 'type' => 'placement', 'default' => "'above · hover & focus'", 'note' => [
                'fa' => 'برچسب بالای آیتم با هاور و فوکوس کیبورد بالا می‌آید.',
                'en' => 'The label rises above the item on hover and keyboard focus.',
            ]],
            ['name' => 'indicator', 'type' => 'string', 'default' => "'dot'", 'note' => [
                'fa' => 'نقطهٔ کوچک زیر آیتم فعال — همان قرارداد داک میزبان.',
                'en' => 'The small dot under the running item — the host dock convention.',
            ]],
            ['name' => 'badge', 'type' => 'dot|count', 'default' => "'count'", 'note' => [
                'fa' => 'شمارندهٔ گوشهٔ آیتم صندوق؛ عدد خوانده‌نشده.',
                'en' => 'The corner counter on the inbox item; unread count.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        {{-- resources/views/components/dock-navbar.blade.php — داک ماک‌آنگار --}}
        <div class="dkn" x-data="{
            active: 'overview',
            move(e) {
                this.$refs.dock.querySelectorAll('.dkn-item').forEach(el => {
                    const r = el.getBoundingClientRect();
                    const d = Math.abs(e.clientX - (r.left + r.width / 2));
                    const s = 1 + .55 * Math.exp(-.5 * (d / 64) ** 2);
                    el.style.setProperty('--dkn-s', s.toFixed(3));
                });
            },
            reset() {
                this.$refs.dock.querySelectorAll('.dkn-item').forEach(el => el.style.setProperty('--dkn-s', 1));
            },
        }">
            <nav class="dkn-bar" x-ref="dock" aria-label="ناوبری برنامه"
                 x-on:pointermove="move($event)" x-on:pointerleave="reset()">
                <button type="button" class="dkn-item" data-tip="نمای کلی" x-bind:data-active="active === 'overview'"
                        x-on:click="active = 'overview'"><span class="dkn-box"><svg>…</svg></span></button>
                <button type="button" class="dkn-item" data-tip="تحلیل" x-bind:data-active="active === 'analytics'"
                        x-on:click="active = 'analytics'"><span class="dkn-box"><svg>…</svg></span></button>
                <button type="button" class="dkn-item" data-tip="صندوق، ۳ ناخوانده" x-bind:data-active="active === 'inbox'"
                        x-on:click="active = 'inbox'">
                    <span class="dkn-box"><svg>…</svg><i class="dkn-badge">۳</i></span>
                </button>
            </nav>
        </div>

        <style>
        .dkn-bar { position: absolute; inset-block-end: .8rem; inset-inline: 0; z-index: 6;
                   display: flex; justify-content: center; align-items: flex-end; gap: .5rem; }
        .dkn-item { position: relative; border: 0; background: none; padding: 0; cursor: pointer; }
        .dkn-box { display: grid; place-items: center; inline-size: calc(2.5rem * var(--dkn-s, 1));
                   block-size: calc(2.5rem * var(--dkn-s, 1)); border-radius: .65rem;
                   background: linear-gradient(160deg, #6366f1, #8b5cf6); color: #fff;
                   transition: inline-size .12s ease-out, block-size .12s ease-out; }
        .dkn-item::before { content: attr(data-tip); position: absolute; inset-block-end: calc(100% + .45rem);
                   left: 50%; translate: -50% 0; padding: .3rem .55rem; border-radius: .45rem;
                   background: #0f172a; color: #fff; font-size: .7rem; white-space: nowrap;
                   opacity: 0; visibility: hidden; transition: opacity .15s, inset-block-end .15s; }
        .dkn-item:hover::before, .dkn-item:focus-visible::before { opacity: 1; visibility: visible; inset-block-end: calc(100% + .6rem); }
        .dkn-item::after { content: ''; position: absolute; inset-block-end: -.5rem; left: 50%; translate: -50% 0;
                   inline-size: .3rem; block-size: .3rem; border-radius: 50%; background: currentColor;
                   opacity: 0; }
        .dkn-item[data-active='true']::after { opacity: 1; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        {{-- resources/views/components/dock-navbar.blade.php — the macOS-style dock --}}
        <div class="dkn" x-data="{
            active: 'overview',
            move(e) {
                this.$refs.dock.querySelectorAll('.dkn-item').forEach(el => {
                    const r = el.getBoundingClientRect();
                    const d = Math.abs(e.clientX - (r.left + r.width / 2));
                    const s = 1 + .55 * Math.exp(-.5 * (d / 64) ** 2);
                    el.style.setProperty('--dkn-s', s.toFixed(3));
                });
            },
            reset() {
                this.$refs.dock.querySelectorAll('.dkn-item').forEach(el => el.style.setProperty('--dkn-s', 1));
            },
        }">
            <nav class="dkn-bar" x-ref="dock" aria-label="App navigation"
                 x-on:pointermove="move($event)" x-on:pointerleave="reset()">
                <button type="button" class="dkn-item" data-tip="Overview" x-bind:data-active="active === 'overview'"
                        x-on:click="active = 'overview'"><span class="dkn-box"><svg>…</svg></span></button>
                <button type="button" class="dkn-item" data-tip="Analytics" x-bind:data-active="active === 'analytics'"
                        x-on:click="active = 'analytics'"><span class="dkn-box"><svg>…</svg></span></button>
                <button type="button" class="dkn-item" data-tip="Inbox, 3 unread" x-bind:data-active="active === 'inbox'"
                        x-on:click="active = 'inbox'">
                    <span class="dkn-box"><svg>…</svg><i class="dkn-badge">3</i></span>
                </button>
            </nav>
        </div>

        <style>
        .dkn-bar { position: absolute; inset-block-end: .8rem; inset-inline: 0; z-index: 6;
                   display: flex; justify-content: center; align-items: flex-end; gap: .5rem; }
        .dkn-item { position: relative; border: 0; background: none; padding: 0; cursor: pointer; }
        .dkn-box { display: grid; place-items: center; inline-size: calc(2.5rem * var(--dkn-s, 1));
                   block-size: calc(2.5rem * var(--dkn-s, 1)); border-radius: .65rem;
                   background: linear-gradient(160deg, #6366f1, #8b5cf6); color: #fff;
                   transition: inline-size .12s ease-out, block-size .12s ease-out; }
        .dkn-item::before { content: attr(data-tip); position: absolute; inset-block-end: calc(100% + .45rem);
                   left: 50%; translate: -50% 0; padding: .3rem .55rem; border-radius: .45rem;
                   background: #0f172a; color: #fff; font-size: .7rem; white-space: nowrap;
                   opacity: 0; visibility: hidden; transition: opacity .15s, inset-block-end .15s; }
        .dkn-item:hover::before, .dkn-item:focus-visible::before { opacity: 1; visibility: visible; inset-block-end: calc(100% + .6rem); }
        .dkn-item::after { content: ''; position: absolute; inset-block-end: -.5rem; left: 50%; translate: -50% 0;
                   inline-size: .3rem; block-size: .3rem; border-radius: 50%; background: currentColor;
                   opacity: 0; }
        .dkn-item[data-active='true']::after { opacity: 1; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Console.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import DockNavbar from '@/components/DockNavbar';

        export default function Console() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <DockNavbar />
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // DockNavbar.jsx — gaussian magnification, tooltips and the active dot
        import { useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const ITEMS = [
          { id: 'overview', label: 'Overview', glyph: '⌂' },
          { id: 'analytics', label: 'Analytics', glyph: '▤' },
          { id: 'team', label: 'Team', glyph: '☺' },
          { id: 'inbox', label: 'Inbox · 3 unread', glyph: '✉' },
          { id: 'settings', label: 'Settings', glyph: '⚙' },
        ];

        export default function DockNavbar() {
          const [active, setActive] = useState('overview');
          const itemRefs = useRef({});
          const [scales, setScales] = useState({});

          // Gaussian falloff from the cursor — the closer, the bigger.
          const move = (e) => {
            const next = {};
            for (const it of ITEMS) {
              const el = itemRefs.current[it.id];
              if (!el) continue;
              const r = el.getBoundingClientRect();
              const d = Math.abs(e.clientX - (r.left + r.width / 2));
              next[it.id] = 1 + 0.55 * Math.exp(-0.5 * (d / 64) ** 2);
            }
            setScales(next);
          };
          const reset = () => setScales({});

          return (
            <>
              <style>{`
                .dkn-bar { position: absolute; inset-block-end: .8rem; inset-inline: 0; z-index: 6;
                           display: flex; justify-content: center; align-items: flex-end; gap: .5rem;
                           font: 500 14px/1 Inter, sans-serif; }
                .dkn-item { position: relative; border: 0; background: none; padding: 0; cursor: pointer; color: #334155; }
                .dkn-box { display: grid; place-items: center; inline-size: calc(2.5rem * var(--dkn-s, 1));
                           block-size: calc(2.5rem * var(--dkn-s, 1)); border-radius: .65rem; color: #fff;
                           background: linear-gradient(160deg, #6366f1, #8b5cf6);
                           transition: inline-size .12s ease-out, block-size .12s ease-out; }
                .dkn-item[data-active='true'] .dkn-box { background: linear-gradient(160deg, #0ea5e9, #6366f1); }
                .dkn-item::before { content: attr(data-tip); position: absolute; inset-block-end: calc(100% + .45rem);
                           left: 50%; translate: -50% 0; padding: .3rem .55rem; border-radius: .45rem;
                           background: #0f172a; color: #fff; font-size: .7rem; white-space: nowrap;
                           opacity: 0; visibility: hidden; transition: opacity .15s, inset-block-end .15s; }
                .dkn-item:hover::before, .dkn-item:focus-visible::before { opacity: 1; visibility: visible; }
                .dkn-item::after { content: ''; position: absolute; inset-block-end: -.5rem; left: 50%;
                           translate: -50% 0; inline-size: .3rem; block-size: .3rem; border-radius: 50%;
                           background: currentColor; opacity: 0; }
                .dkn-item[data-active='true']::after { opacity: 1; }
              `}</style>
              <nav className="dkn-bar" aria-label="App navigation" onPointerMove={move} onPointerLeave={reset}>
                {ITEMS.map((it) => (
                  <button key={it.id} type="button" className="dkn-item" data-tip={it.label}
                          data-active={active === it.id} aria-pressed={active === it.id}
                          ref={(el) => { itemRefs.current[it.id] = el; }}
                          style={{ '--dkn-s': scales[it.id] ?? 1 }}
                          onClick={() => setActive(it.id)}>
                    <span className="dkn-box" aria-hidden="true">{it.glyph}</span>
                    <span className="dkn-sr">{it.label}</span>
                  </button>
                ))}
              </nav>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- DockNavbar.vue — gaussian magnification, tooltips and the active dot -->
        <script setup lang="ts">
        import { ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const ITEMS = [
          { id: 'overview', label: 'Overview', glyph: '⌂' },
          { id: 'analytics', label: 'Analytics', glyph: '▤' },
          { id: 'team', label: 'Team', glyph: '☺' },
          { id: 'inbox', label: 'Inbox · 3 unread', glyph: '✉' },
          { id: 'settings', label: 'Settings', glyph: '⚙' },
        ];
        const active = ref('overview');
        const itemEls = ref<Record<string, HTMLElement>>({});
        const scales = ref<Record<string, number>>({});

        // Gaussian falloff from the cursor — the closer, the bigger.
        function move(e: PointerEvent) {
          const next: Record<string, number> = {};
          for (const it of ITEMS) {
            const el = itemEls.value[it.id];
            if (!el) continue;
            const r = el.getBoundingClientRect();
            const d = Math.abs(e.clientX - (r.left + r.width / 2));
            next[it.id] = 1 + 0.55 * Math.exp(-0.5 * (d / 64) ** 2);
          }
          scales.value = next;
        }
        function reset() { scales.value = {}; }
        </script>

        <template>
          <nav class="dkn-bar" aria-label="App navigation" @pointermove="move" @pointerleave="reset">
            <button v-for="it in ITEMS" :key="it.id" type="button" class="dkn-item" :data-tip="it.label"
                    :data-active="active === it.id" :aria-pressed="active === it.id"
                    :ref="(el: any) => { if (el) itemEls[it.id] = el }"
                    :style="{ '--dkn-s': scales[it.id] ?? 1 }"
                    @click="active = it.id">
              <span class="dkn-box" aria-hidden="true">{{ it.glyph }}</span>
              <span class="dkn-sr">{{ it.label }}</span>
            </button>
          </nav>
        </template>

        <style scoped>
        .dkn-bar { position: absolute; inset-block-end: .8rem; inset-inline: 0; z-index: 6;
                   display: flex; justify-content: center; align-items: flex-end; gap: .5rem;
                   font: 500 14px/1 Inter, sans-serif; }
        .dkn-item { position: relative; border: 0; background: none; padding: 0; cursor: pointer; color: #334155; }
        .dkn-box { display: grid; place-items: center; inline-size: calc(2.5rem * var(--dkn-s, 1));
                   block-size: calc(2.5rem * var(--dkn-s, 1)); border-radius: .65rem; color: #fff;
                   background: linear-gradient(160deg, #6366f1, #8b5cf6);
                   transition: inline-size .12s ease-out, block-size .12s ease-out; }
        .dkn-item[data-active='true'] .dkn-box { background: linear-gradient(160deg, #0ea5e9, #6366f1); }
        .dkn-item::before { content: attr(data-tip); position: absolute; inset-block-end: calc(100% + .45rem);
                   left: 50%; translate: -50% 0; padding: .3rem .55rem; border-radius: .45rem;
                   background: #0f172a; color: #fff; font-size: .7rem; white-space: nowrap;
                   opacity: 0; visibility: hidden; transition: opacity .15s, inset-block-end .15s; }
        .dkn-item:hover::before, .dkn-item:focus-visible::before { opacity: 1; visibility: visible; }
        .dkn-item::after { content: ''; position: absolute; inset-block-end: -.5rem; left: 50%;
                   translate: -50% 0; inline-size: .3rem; block-size: .3rem; border-radius: 50%;
                   background: currentColor; opacity: 0; }
        .dkn-item[data-active='true']::after { opacity: 1; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- DockNavbar.svelte — gaussian magnification, tooltips and the active dot -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const ITEMS = [
            { id: 'overview', label: 'Overview', glyph: '⌂' },
            { id: 'analytics', label: 'Analytics', glyph: '▤' },
            { id: 'team', label: 'Team', glyph: '☺' },
            { id: 'inbox', label: 'Inbox · 3 unread', glyph: '✉' },
            { id: 'settings', label: 'Settings', glyph: '⚙' },
          ];
          let active = $state('overview');
          let itemEls = $state<Record<string, HTMLElement>>({});
          let scales = $state<Record<string, number>>({});

          // Gaussian falloff from the cursor — the closer, the bigger.
          function move(e: PointerEvent) {
            const next: Record<string, number> = {};
            for (const it of ITEMS) {
              const el = itemEls[it.id];
              if (!el) continue;
              const r = el.getBoundingClientRect();
              const d = Math.abs(e.clientX - (r.left + r.width / 2));
              next[it.id] = 1 + 0.55 * Math.exp(-0.5 * (d / 64) ** 2);
            }
            scales = next;
          }
          function reset() { scales = {}; }
        </script>

        <nav class="dkn-bar" aria-label="App navigation" onpointermove={move} onpointerleave={reset}>
          {#each ITEMS as it (it.id)}
            <button type="button" class="dkn-item" data-tip={it.label}
                    data-active={active === it.id} aria-pressed={active === it.id}
                    bind:this={itemEls[it.id]}
                    style={`--dkn-s: ${scales[it.id] ?? 1}`}
                    onclick={() => (active = it.id)}>
              <span class="dkn-box" aria-hidden="true">{it.glyph}</span>
              <span class="dkn-sr">{it.label}</span>
            </button>
          {/each}
        </nav>

        <style>
        .dkn-bar { position: absolute; inset-block-end: .8rem; inset-inline: 0; z-index: 6;
                   display: flex; justify-content: center; align-items: flex-end; gap: .5rem;
                   font: 500 14px/1 Inter, sans-serif; }
        .dkn-item { position: relative; border: 0; background: none; padding: 0; cursor: pointer; color: #334155; }
        .dkn-box { display: grid; place-items: center; inline-size: calc(2.5rem * var(--dkn-s, 1));
                   block-size: calc(2.5rem * var(--dkn-s, 1)); border-radius: .65rem; color: #fff;
                   background: linear-gradient(160deg, #6366f1, #8b5cf6);
                   transition: inline-size .12s ease-out, block-size .12s ease-out; }
        .dkn-item[data-active='true'] .dkn-box { background: linear-gradient(160deg, #0ea5e9, #6366f1); }
        .dkn-item::before { content: attr(data-tip); position: absolute; inset-block-end: calc(100% + .45rem);
                   left: 50%; translate: -50% 0; padding: .3rem .55rem; border-radius: .45rem;
                   background: #0f172a; color: #fff; font-size: .7rem; white-space: nowrap;
                   opacity: 0; visibility: hidden; transition: opacity .15s, inset-block-end .15s; }
        .dkn-item:hover::before, .dkn-item:focus-visible::before { opacity: 1; visibility: visible; }
        .dkn-item::after { content: ''; position: absolute; inset-block-end: -.5rem; left: 50%;
                   translate: -50% 0; inline-size: .3rem; block-size: .3rem; border-radius: 50%;
                   background: currentColor; opacity: 0; }
        .dkn-item[data-active='true']::after { opacity: 1; }
        </style>
        SVELTE,
        ],
    ],

    'split-navbar' => [
        'title' => ['fa' => 'نوبار لوگوی مرکزی', 'en' => 'Center logo navbar'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'لوگو دقیقاً وسط، لینک‌ها دو طرفش — و در قاب موبایل، همبرگری که دراور را از کنار باز می‌کند: اورلی تیره، بستن با Escape و کلیک بیرون، و فوکوسی که تا بسته‌شدن در دراور به دام می‌افتد.',
            'en' => 'The logo dead centre with links flanking it — and in the phone frame, a hamburger that pulls the drawer in from the side: dark overlay, Escape and outside-click to close, and focus that stays trapped inside until you do.',
        ],
        'js' => true,
        'docs' => 'https://navbar.gallery',
        'props' => [
            ['name' => 'layout', 'type' => 'grid', 'default' => "'1fr auto 1fr'", 'note' => [
                'fa' => 'شبکهٔ سه‌ستونی؛ لینک‌ها دو طرف لوگو تراز می‌شوند و لوگو همیشه مرکز می‌ماند.',
                'en' => 'The three-column grid; links flank the logo and the logo stays mathematically centred.',
            ]],
            ['name' => 'breakpoint', 'type' => 'px', 'default' => "'640'", 'note' => [
                'fa' => 'زیر این عرض، لینک‌ها زیر لوگو می‌نشینند و در موبایل جای خود را به همبرگر می‌دهند.',
                'en' => 'Below this width the links fold under the logo, and on mobile they yield to the hamburger.',
            ]],
            ['name' => 'drawer-side', 'type' => 'string', 'default' => "'start'", 'note' => [
                'fa' => 'دراور از لبهٔ آغازین می‌آید — در راست‌به‌چپ خودکار برعکس.',
                'en' => 'The drawer slides from the inline-start edge — it flips by itself in RTL.',
            ]],
            ['name' => 'focus-trap', 'type' => 'boolean', 'default' => 'true', 'note' => [
                'fa' => 'تاب در دراور می‌چرخد و با بستن، فوکوس به دکمهٔ همبرگر برمی‌گردد.',
                'en' => 'Tab cycles inside the drawer, and on close the focus lands back on the hamburger.',
            ]],
            ['name' => 'dismiss', 'type' => 'string', 'default' => "'Escape · overlay · after nav'", 'note' => [
                'fa' => 'Escape، کلیک روی اورلی یا رفتن به مقصد، دراور را می‌بندد.',
                'en' => 'Escape, an overlay click or navigating away closes the drawer.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        {{-- resources/views/components/split-navbar.blade.php — لوگوی مرکزی + دراور موبایل --}}
        <div class="spn" x-data="{
            drawer: false,
            openDrawer() {
                this.drawer = true;
                this.$nextTick(() => { const f = this.$refs.drawer.querySelector('a[href], button'); f && f.focus(); });
            },
            closeDrawer() {
                if (! this.drawer) return;
                this.drawer = false;
                this.$refs.burger && this.$refs.burger.focus();
            },
            trap(e) {
                if (e.key !== 'Tab') return;
                const f = this.$refs.drawer.querySelectorAll('a[href], button:not([disabled])');
                if (! f.length) return;
                const first = f[0], last = f[f.length - 1];
                if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
                else if (! e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
            },
        }" x-on:keydown.escape.window="closeDrawer()">
            {{-- دسکتاپ: لوگو وسط، لینک‌ها دو طرف --}}
            <header class="spn-nav">
                <nav class="spn-side" aria-label="اصلی">
                    <a href="#product">محصول</a> <a href="#solutions">راه‌حل‌ها</a>
                </nav>
                <b class="spn-brand"><i aria-hidden="true"></i>مریدین</b>
                <span class="spn-side spn-end">
                    <a href="#pricing">قیمت</a> <a href="#docs">مستندات</a>
                    <a class="spn-cta" href="#start">شروع رایگان</a>
                </span>
            </header>

            {{-- موبایل: همبرگر + دراور تاشو با اورلی و تلهٔ فوکوس --}}
            <button type="button" class="spn-burger" x-ref="burger" x-bind:aria-expanded="drawer"
                    aria-controls="spn-drawer" x-on:click="drawer ? closeDrawer() : openDrawer()">
                <span aria-hidden="true">☰</span> <span class="spn-sr">منو</span>
            </button>
            <div class="spn-overlay" x-bind:data-open="drawer" x-on:click="closeDrawer()"></div>
            <aside class="spn-drawer" id="spn-drawer" x-ref="drawer" x-bind:data-open="drawer"
                   role="dialog" aria-modal="true" aria-label="منوی موبایل" x-on:keydown="trap($event)">
                <nav>
                    <a href="#product" x-on:click="closeDrawer()">محصول</a>
                    <a href="#pricing" x-on:click="closeDrawer()">قیمت</a>
                    <a href="#docs" x-on:click="closeDrawer()">مستندات</a>
                </nav>
                <a class="spn-cta" href="#start" x-on:click="closeDrawer()">شروع رایگان</a>
            </aside>
        </div>

        <style>
        .spn-nav { display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; gap: 1rem; padding: 1rem 1.5rem; }
        .spn-brand { display: inline-flex; align-items: center; gap: .5rem; font-size: 1.05rem; }
        .spn-brand i { inline-size: 1.4rem; block-size: 1.4rem; border-radius: .45rem;
                       background: conic-gradient(from 210deg, #6366f1, #d946ef, #06b6d4, #6366f1); }
        .spn-side { display: flex; gap: 1rem; }
        .spn-end { justify-self: end; align-items: center; }
        .spn-nav a { color: #334155; text-decoration: none; font-weight: 500; }
        .spn-cta { padding: .55rem 1.05rem; border-radius: 999px; background: #4f46e5; color: #fff !important; }
        .spn-overlay { position: absolute; inset: 0; background: #0f172ab3; opacity: 0; visibility: hidden;
                       transition: opacity .25s, visibility 0s .25s; }
        .spn-overlay[data-open='true'] { opacity: 1; visibility: visible; transition: opacity .25s; }
        .spn-drawer { position: absolute; inset-block: 0; inset-inline-start: 0; inline-size: min(78%, 17rem);
                      padding: 1.25rem; background: #fff; translate: -101% 0; visibility: hidden;
                      transition: translate .3s cubic-bezier(.2,0,0,1), visibility 0s .3s; }
        html[dir='rtl'] .spn-drawer { translate: 101% 0; }
        .spn-drawer[data-open='true'] { translate: 0 0; visibility: visible;
                      transition: translate .3s cubic-bezier(.2,0,0,1); }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        {{-- resources/views/components/split-navbar.blade.php — center logo + mobile drawer --}}
        <div class="spn" x-data="{
            drawer: false,
            openDrawer() {
                this.drawer = true;
                this.$nextTick(() => { const f = this.$refs.drawer.querySelector('a[href], button'); f && f.focus(); });
            },
            closeDrawer() {
                if (! this.drawer) return;
                this.drawer = false;
                this.$refs.burger && this.$refs.burger.focus();
            },
            trap(e) {
                if (e.key !== 'Tab') return;
                const f = this.$refs.drawer.querySelectorAll('a[href], button:not([disabled])');
                if (! f.length) return;
                const first = f[0], last = f[f.length - 1];
                if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
                else if (! e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
            },
        }" x-on:keydown.escape.window="closeDrawer()">
            {{-- desktop: logo centred, links flanking --}}
            <header class="spn-nav">
                <nav class="spn-side" aria-label="Primary">
                    <a href="#product">Product</a> <a href="#solutions">Solutions</a>
                </nav>
                <b class="spn-brand"><i aria-hidden="true"></i>Meridian</b>
                <span class="spn-side spn-end">
                    <a href="#pricing">Pricing</a> <a href="#docs">Docs</a>
                    <a class="spn-cta" href="#start">Start free</a>
                </span>
            </header>

            {{-- mobile: hamburger + the folding drawer with overlay and focus trap --}}
            <button type="button" class="spn-burger" x-ref="burger" x-bind:aria-expanded="drawer"
                    aria-controls="spn-drawer" x-on:click="drawer ? closeDrawer() : openDrawer()">
                <span aria-hidden="true">☰</span> <span class="spn-sr">Menu</span>
            </button>
            <div class="spn-overlay" x-bind:data-open="drawer" x-on:click="closeDrawer()"></div>
            <aside class="spn-drawer" id="spn-drawer" x-ref="drawer" x-bind:data-open="drawer"
                   role="dialog" aria-modal="true" aria-label="Mobile menu" x-on:keydown="trap($event)">
                <nav>
                    <a href="#product" x-on:click="closeDrawer()">Product</a>
                    <a href="#pricing" x-on:click="closeDrawer()">Pricing</a>
                    <a href="#docs" x-on:click="closeDrawer()">Docs</a>
                </nav>
                <a class="spn-cta" href="#start" x-on:click="closeDrawer()">Start free</a>
            </aside>
        </div>

        <style>
        .spn-nav { display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; gap: 1rem; padding: 1rem 1.5rem; }
        .spn-brand { display: inline-flex; align-items: center; gap: .5rem; font-size: 1.05rem; }
        .spn-brand i { inline-size: 1.4rem; block-size: 1.4rem; border-radius: .45rem;
                       background: conic-gradient(from 210deg, #6366f1, #d946ef, #06b6d4, #6366f1); }
        .spn-side { display: flex; gap: 1rem; }
        .spn-end { justify-self: end; align-items: center; }
        .spn-nav a { color: #334155; text-decoration: none; font-weight: 500; }
        .spn-cta { padding: .55rem 1.05rem; border-radius: 999px; background: #4f46e5; color: #fff !important; }
        .spn-overlay { position: absolute; inset: 0; background: #0f172ab3; opacity: 0; visibility: hidden;
                       transition: opacity .25s, visibility 0s .25s; }
        .spn-overlay[data-open='true'] { opacity: 1; visibility: visible; transition: opacity .25s; }
        .spn-drawer { position: absolute; inset-block: 0; inset-inline-start: 0; inline-size: min(78%, 17rem);
                      padding: 1.25rem; background: #fff; translate: -101% 0; visibility: hidden;
                      transition: translate .3s cubic-bezier(.2,0,0,1), visibility 0s .3s; }
        html[dir='rtl'] .spn-drawer { translate: 101% 0; }
        .spn-drawer[data-open='true'] { translate: 0 0; visibility: visible;
                      transition: translate .3s cubic-bezier(.2,0,0,1); }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Brand.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import SplitNavbar from '@/components/SplitNavbar';

        export default function Brand() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <SplitNavbar />
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // SplitNavbar.jsx — centred logo, and a mobile drawer that traps focus
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const LEFT = ['Product', 'Solutions'];
        const RIGHT = ['Pricing', 'Docs'];

        export default function SplitNavbar() {
          const [drawer, setDrawer] = useState(false);
          const burgerRef = useRef(null);
          const drawerRef = useRef(null);

          // Escape closes from anywhere; focus returns to the hamburger.
          useEffect(() => {
            const onKey = (e) => { if (e.key === 'Escape') closeDrawer(); };
            window.addEventListener('keydown', onKey);
            return () => window.removeEventListener('keydown', onKey);
          }, []);
          const closeDrawer = () => {
            setDrawer(false);
            burgerRef.current?.focus();
          };
          const openDrawer = () => {
            setDrawer(true);
            requestAnimationFrame(() => {
              drawerRef.current?.querySelector('a[href], button')?.focus();
            });
          };
          // The trap: Tab cycles inside the drawer while it is open.
          const trap = (e) => {
            if (e.key !== 'Tab') return;
            const f = drawerRef.current?.querySelectorAll('a[href], button:not([disabled])');
            if (!f?.length) return;
            const first = f[0], last = f[f.length - 1];
            if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
            else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
          };

          return (
            <>
              <style>{`
                .spn-nav { display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; gap: 1rem;
                           padding: 1rem 1.5rem; font: 500 14px/1 Inter, sans-serif; }
                .spn-side { display: flex; gap: 1rem; }
                .spn-end { justify-self: end; align-items: center; }
                .spn-nav a { color: #334155; text-decoration: none; }
                .spn-brand { display: inline-flex; align-items: center; gap: .5rem; font-size: 1.05rem; }
                .spn-brand i { inline-size: 1.4rem; aspect-ratio: 1; border-radius: .45rem;
                               background: conic-gradient(from 210deg, #6366f1, #d946ef, #06b6d4, #6366f1); }
                .spn-cta { padding: .55rem 1.05rem; border-radius: 999px; background: #4f46e5; color: #fff !important; }
                .spn-frame { position: relative; overflow: clip; }
                .spn-overlay { position: absolute; inset: 0; background: #0f172ab3; opacity: 0;
                               visibility: hidden; transition: opacity .25s, visibility 0s .25s; }
                .spn-overlay[data-open='true'] { opacity: 1; visibility: visible; transition: opacity .25s; }
                .spn-drawer { position: absolute; inset-block: 0; inset-inline-start: 0; inline-size: min(78%, 17rem);
                              padding: 1.25rem; background: #fff; translate: -101% 0; visibility: hidden;
                              display: grid; gap: 1rem; align-content: start;
                              transition: translate .3s cubic-bezier(.2,0,0,1), visibility 0s .3s; }
                [dir='rtl'] .spn-drawer { translate: 101% 0; }
                .spn-drawer[data-open='true'] { translate: 0 0; visibility: visible;
                              transition: translate .3s cubic-bezier(.2,0,0,1); }
                .spn-drawer nav { display: grid; gap: .35rem; }
                .spn-drawer nav a { padding: .6rem .5rem; border-radius: .5rem; color: #0f172a; text-decoration: none; }
                .spn-drawer nav a:hover { background: #eef2ff; }
                .spn-burger { border: 0; background: none; font-size: 1.2rem; cursor: pointer; }
              `}</style>
              <header className="spn-nav">
                <nav className="spn-side" aria-label="Primary">
                  {LEFT.map((l) => <a key={l} href={`#${l.toLowerCase()}`}>{l}</a>)}
                </nav>
                <b className="spn-brand"><i aria-hidden="true"></i>Meridian</b>
                <span className="spn-side spn-end">
                  {RIGHT.map((l) => <a key={l} href={`#${l.toLowerCase()}`}>{l}</a>)}
                  <a className="spn-cta" href="#start">Start free</a>
                </span>
              </header>

              <div className="spn-frame">
                <button type="button" className="spn-burger" ref={burgerRef}
                        aria-expanded={drawer} aria-controls="spn-drawer"
                        onClick={() => (drawer ? closeDrawer() : openDrawer())}>
                  ☰ Menu
                </button>
                <div className="spn-overlay" data-open={drawer} onClick={closeDrawer} />
                <aside className="spn-drawer" id="spn-drawer" ref={drawerRef} data-open={drawer}
                       role="dialog" aria-modal="true" aria-label="Mobile menu" onKeyDown={trap}>
                  <nav>
                    {[...LEFT, ...RIGHT, 'Changelog'].map((l) => (
                      <a key={l} href={`#${l.toLowerCase()}`} onClick={closeDrawer}>{l}</a>
                    ))}
                  </nav>
                  <a className="spn-cta" href="#start" onClick={closeDrawer}>Start free</a>
                </aside>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- SplitNavbar.vue — centred logo, and a mobile drawer that traps focus -->
        <script setup lang="ts">
        import { nextTick, onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const LEFT = ['Product', 'Solutions'];
        const RIGHT = ['Pricing', 'Docs'];
        const drawer = ref(false);
        const burger = ref<HTMLButtonElement | null>(null);
        const drawerEl = ref<HTMLElement | null>(null);

        function openDrawer() {
          drawer.value = true;
          nextTick(() => drawerEl.value?.querySelector<HTMLElement>('a[href], button')?.focus());
        }
        function closeDrawer() {
          if (!drawer.value) return;
          drawer.value = false;
          burger.value?.focus();
        }
        // The trap: Tab cycles inside the drawer while it is open.
        function trap(e: KeyboardEvent) {
          if (e.key !== 'Tab') return;
          const f = drawerEl.value?.querySelectorAll<HTMLElement>('a[href], button:not([disabled])');
          if (!f?.length) return;
          const first = f[0], last = f[f.length - 1];
          if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
          else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
        }
        const onKey = (e: KeyboardEvent) => { if (e.key === 'Escape') closeDrawer(); };
        onMounted(() => window.addEventListener('keydown', onKey));
        onBeforeUnmount(() => window.removeEventListener('keydown', onKey));
        </script>

        <template>
          <header class="spn-nav">
            <nav class="spn-side" aria-label="Primary">
              <a v-for="l in LEFT" :key="l" :href="'#' + l.toLowerCase()">{{ l }}</a>
            </nav>
            <b class="spn-brand"><i aria-hidden="true"></i>Meridian</b>
            <span class="spn-side spn-end">
              <a v-for="l in RIGHT" :key="l" :href="'#' + l.toLowerCase()">{{ l }}</a>
              <a class="spn-cta" href="#start">Start free</a>
            </span>
          </header>

          <div class="spn-frame">
            <button type="button" class="spn-burger" ref="burger" :aria-expanded="drawer"
                    aria-controls="spn-drawer" @click="drawer ? closeDrawer() : openDrawer()">
              ☰ Menu
            </button>
            <div class="spn-overlay" :data-open="drawer" @click="closeDrawer"></div>
            <aside class="spn-drawer" id="spn-drawer" ref="drawerEl" :data-open="drawer"
                   role="dialog" aria-modal="true" aria-label="Mobile menu" @keydown="trap">
              <nav>
                <a v-for="l in [...LEFT, ...RIGHT, 'Changelog']" :key="l"
                   :href="'#' + l.toLowerCase()" @click="closeDrawer">{{ l }}</a>
              </nav>
              <a class="spn-cta" href="#start" @click="closeDrawer">Start free</a>
            </aside>
          </div>
        </template>

        <style scoped>
        .spn-nav { display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; gap: 1rem;
                   padding: 1rem 1.5rem; font: 500 14px/1 Inter, sans-serif; }
        .spn-side { display: flex; gap: 1rem; }
        .spn-end { justify-self: end; align-items: center; }
        .spn-nav a { color: #334155; text-decoration: none; }
        .spn-brand { display: inline-flex; align-items: center; gap: .5rem; font-size: 1.05rem; }
        .spn-brand i { inline-size: 1.4rem; aspect-ratio: 1; border-radius: .45rem;
                       background: conic-gradient(from 210deg, #6366f1, #d946ef, #06b6d4, #6366f1); }
        .spn-cta { padding: .55rem 1.05rem; border-radius: 999px; background: #4f46e5; color: #fff !important; }
        .spn-frame { position: relative; overflow: clip; }
        .spn-overlay { position: absolute; inset: 0; background: #0f172ab3; opacity: 0;
                       visibility: hidden; transition: opacity .25s, visibility 0s .25s; }
        .spn-overlay[data-open='true'] { opacity: 1; visibility: visible; transition: opacity .25s; }
        .spn-drawer { position: absolute; inset-block: 0; inset-inline-start: 0; inline-size: min(78%, 17rem);
                      padding: 1.25rem; background: #fff; translate: -101% 0; visibility: hidden;
                      display: grid; gap: 1rem; align-content: start;
                      transition: translate .3s cubic-bezier(.2,0,0,1), visibility 0s .3s; }
        :global([dir='rtl']) .spn-drawer { translate: 101% 0; }
        .spn-drawer[data-open='true'] { translate: 0 0; visibility: visible;
                      transition: translate .3s cubic-bezier(.2,0,0,1); }
        .spn-drawer nav { display: grid; gap: .35rem; }
        .spn-drawer nav a { padding: .6rem .5rem; border-radius: .5rem; color: #0f172a; text-decoration: none; }
        .spn-drawer nav a:hover { background: #eef2ff; }
        .spn-burger { border: 0; background: none; font-size: 1.2rem; cursor: pointer; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- SplitNavbar.svelte — centred logo, and a mobile drawer that traps focus -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const LEFT = ['Product', 'Solutions'];
          const RIGHT = ['Pricing', 'Docs'];
          let drawer = $state(false);
          let burger = $state<HTMLButtonElement | null>(null);
          let drawerEl = $state<HTMLElement | null>(null);

          function openDrawer() {
            drawer = true;
            requestAnimationFrame(() => drawerEl?.querySelector<HTMLElement>('a[href], button')?.focus());
          }
          function closeDrawer() {
            if (!drawer) return;
            drawer = false;
            burger?.focus();
          }
          // The trap: Tab cycles inside the drawer while it is open.
          function trap(e: KeyboardEvent) {
            if (e.key !== 'Tab') return;
            const f = drawerEl?.querySelectorAll<HTMLElement>('a[href], button:not([disabled])');
            if (!f?.length) return;
            const first = f[0], last = f[f.length - 1];
            if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
            else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
          }
          // Escape closes from anywhere; focus returns to the hamburger.
          $effect(() => {
            const onKey = (e: KeyboardEvent) => { if (e.key === 'Escape') closeDrawer(); };
            window.addEventListener('keydown', onKey);
            return () => window.removeEventListener('keydown', onKey);
          });
        </script>

        <header class="spn-nav">
          <nav class="spn-side" aria-label="Primary">
            {#each LEFT as l (l)}<a href={'#' + l.toLowerCase()}>{l}</a>{/each}
          </nav>
          <b class="spn-brand"><i aria-hidden="true"></i>Meridian</b>
          <span class="spn-side spn-end">
            {#each RIGHT as l (l)}<a href={'#' + l.toLowerCase()}>{l}</a>{/each}
            <a class="spn-cta" href="#start">Start free</a>
          </span>
        </header>

        <div class="spn-frame">
          <button type="button" class="spn-burger" bind:this={burger} aria-expanded={drawer}
                  aria-controls="spn-drawer" onclick={() => (drawer ? closeDrawer() : openDrawer())}>
            ☰ Menu
          </button>
          <div class="spn-overlay" data-open={drawer} onclick={closeDrawer}></div>
          <aside class="spn-drawer" id="spn-drawer" bind:this={drawerEl} data-open={drawer}
                 role="dialog" aria-modal="true" aria-label="Mobile menu" onkeydown={trap}>
            <nav>
              {#each [...LEFT, ...RIGHT, 'Changelog'] as l (l)}
                <a href={'#' + l.toLowerCase()} onclick={closeDrawer}>{l}</a>
              {/each}
            </nav>
            <a class="spn-cta" href="#start" onclick={closeDrawer}>Start free</a>
          </aside>
        </div>

        <style>
        .spn-nav { display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; gap: 1rem;
                   padding: 1rem 1.5rem; font: 500 14px/1 Inter, sans-serif; }
        .spn-side { display: flex; gap: 1rem; }
        .spn-end { justify-self: end; align-items: center; }
        .spn-nav a { color: #334155; text-decoration: none; }
        .spn-brand { display: inline-flex; align-items: center; gap: .5rem; font-size: 1.05rem; }
        .spn-brand i { inline-size: 1.4rem; aspect-ratio: 1; border-radius: .45rem;
                       background: conic-gradient(from 210deg, #6366f1, #d946ef, #06b6d4, #6366f1); }
        .spn-cta { padding: .55rem 1.05rem; border-radius: 999px; background: #4f46e5; color: #fff !important; }
        .spn-frame { position: relative; overflow: clip; }
        .spn-overlay { position: absolute; inset: 0; background: #0f172ab3; opacity: 0;
                       visibility: hidden; transition: opacity .25s, visibility 0s .25s; }
        .spn-overlay[data-open='true'] { opacity: 1; visibility: visible; transition: opacity .25s; }
        .spn-drawer { position: absolute; inset-block: 0; inset-inline-start: 0; inline-size: min(78%, 17rem);
                      padding: 1.25rem; background: #fff; translate: -101% 0; visibility: hidden;
                      display: grid; gap: 1rem; align-content: start;
                      transition: translate .3s cubic-bezier(.2,0,0,1), visibility 0s .3s; }
        :global([dir='rtl']) .spn-drawer { translate: 101% 0; }
        .spn-drawer[data-open='true'] { translate: 0 0; visibility: visible;
                      transition: translate .3s cubic-bezier(.2,0,0,1); }
        .spn-drawer nav { display: grid; gap: .35rem; }
        .spn-drawer nav a { padding: .6rem .5rem; border-radius: .5rem; color: #0f172a; text-decoration: none; }
        .spn-drawer nav a:hover { background: #eef2ff; }
        .spn-burger { border: 0; background: none; font-size: 1.2rem; cursor: pointer; }
        </style>
        SVELTE,
        ],
    ],
];
