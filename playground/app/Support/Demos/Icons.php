<?php

/**
 * Demo manifest of the "icons" group — hand-built 3D icons in the spirit of
 * 3dicons.co: fully inline SVG with soft two-to-three-stop gradients, white
 * edge highlights and an elliptical ground shadow under every object. The set
 * itself (twelve icons, hover tilt, three switchable colour themes) plus three
 * real product scenes — empty state, feature row, pricing — where the icons
 * are the backbone of the scene, not decoration. Scenarios live at
 * resources/views/demos/components/icons/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'آیکون‌های سه‌بعدی', 'en' => '3D icons'],

    'icon-set' => [
        'title' => ['fa' => 'مجموعهٔ آیکون سه‌بعدی', 'en' => '3D icon set'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'دوازده آیکون تمام‌SVG — موشک، نمودار، سپر، ابر و… — با گرادیان‌های نرم دو-سه‌رنگ، هایلایت لبهٔ سفید و سایهٔ بیضی زیر هر شیء؛ هاور با اشاره‌گر کج می‌شود و یک سگمنت کل مجموعه را از بنفش به کهربایی و نعنایی برمی‌گرداند.',
            'en' => 'Twelve fully-inline-SVG icons — rocket, chart, shield, cloud and friends — with soft two-to-three-stop gradients, a white edge highlight and an elliptical ground shadow under every object; hover tilts with your pointer and one segment recolours the whole set from violet to amber to mint.',
        ],
        'js' => true,
        'docs' => 'https://3dicons.co',
        'props' => [
            ['name' => 'theme', 'type' => 'segment', 'default' => "'violet'", 'note' => [
                'fa' => 'سه تم: violet · amber · mint؛ سگمنت فقط چهار متغیر رنگ را عوض می‌کند و هر دو گرادیان مشترک یکجا رنگ می‌گیرند.',
                'en' => 'Three themes: violet · amber · mint; the segment flips four colour variables and both shared gradients recolour at once.',
            ]],
            ['name' => 'tilt', 'type' => 'deg', 'default' => "'±12°'", 'note' => [
                'fa' => 'تیلت موقعیت اشاره‌گر را می‌خواند و روی rotateX/rotateY می‌نشیند؛ رها کردن فنری به حالت صفر برمی‌گردد.',
                'en' => 'The tilt reads the pointer position onto rotateX/rotateY; letting go springs back to zero.',
            ]],
            ['name' => 'stops', 'type' => 'count', 'default' => "'2–3'", 'note' => [
                'fa' => 'هر سطح دو تا سه ایستگاه رنگ نرم دارد — بدنه، رنگ تأکیدی و یک گرادیان براق مشترک برای هایلایت لبه.',
                'en' => 'Every surface carries two to three soft stops — body, accent, and one shared gloss gradient for the edge highlight.',
            ]],
            ['name' => 'ground-shadow', 'type' => 'shape', 'default' => "'ellipse'", 'note' => [
                'fa' => 'سایهٔ بیضیِ محو زیر هر شیء عمق را می‌سازد؛ بدون فیلتر سنگین، فقط یک گرادیان شعاعی مشترک.',
                'en' => 'A soft elliptical shadow under each object builds the depth — no heavy filters, just one shared radial gradient.',
            ]],
            ['name' => 'viewBox', 'type' => 'grid', 'default' => "'64 × 64'", 'note' => [
                'fa' => 'همهٔ آیکون‌ها روی شبکهٔ ۶۴×۶۴ کشیده شده‌اند و تا اندازهٔ نمایش ۱۱۲ پیکسل تیز می‌مانند.',
                'en' => 'Every icon is drawn on a 64×64 grid and stays crisp up to a 112px display size.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        {{-- یک بار برای کل مجموعه: دو گرادیان مشترک + سایهٔ شعاعی --}}
        <svg width="0" height="0" aria-hidden="true"><defs>
            <linearGradient id="i3ds-body" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0" class="i3ds-s1"/><stop offset="1" class="i3ds-s2"/>
            </linearGradient>
            <linearGradient id="i3ds-accent" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0" class="i3ds-s3"/><stop offset="1" class="i3ds-s4"/>
            </linearGradient>
            <radialGradient id="i3ds-shadow">
                <stop offset="0" stop-color="#0f172a" stop-opacity=".26"/>
                <stop offset="1" stop-color="#0f172a" stop-opacity="0"/>
            </radialGradient>
        </defs></svg>

        <div class="i3ds" x-data="{
                theme: 'violet',
                tilt(e) {
                    const el = e.currentTarget, r = el.getBoundingClientRect();
                    el.style.setProperty('--i3ds-rx', ((0.5 - (e.clientY - r.top) / r.height) * 12) + 'deg');
                    el.style.setProperty('--i3ds-ry', (((e.clientX - r.left) / r.width - 0.5) * 14) + 'deg');
                },
                reset(e) {
                    e.currentTarget.style.removeProperty('--i3ds-rx');
                    e.currentTarget.style.removeProperty('--i3ds-ry');
                },
            }" :data-theme="theme">
            <div class="i3ds-seg" role="radiogroup" aria-label="تم رنگی">
                <button type="button" role="radio" :aria-checked="theme === 'violet'"
                        x-on:click="theme = 'violet'">بنفش</button>
                <button type="button" role="radio" :aria-checked="theme === 'amber'"
                        x-on:click="theme = 'amber'">کهربایی</button>
                <button type="button" role="radio" :aria-checked="theme === 'mint'"
                        x-on:click="theme = 'mint'">نعنایی</button>
            </div>

            <div class="i3ds-grid">
                <div class="i3ds-cell" x-on:pointermove="tilt($event)" x-on:pointerleave="reset($event)">
                    <svg viewBox="0 0 64 64" role="img" aria-label="موشک">
                        <ellipse class="i3ds-sh" cx="32" cy="55" rx="15" ry="4.5"/>
                        <path d="M27.5 45h9c.3 3.5-1.7 6.5-4.5 9.5-2.8-3-4.8-6-4.5-9.5Z" fill="url(#i3ds-accent)"/>
                        <path d="M23 33c-5.5 3-8.5 8.5-8.5 15l8.5-5.5Z" fill="url(#i3ds-accent)"/>
                        <path d="M41 33c5.5 3 8.5 8.5 8.5 15L41 42.5Z" fill="url(#i3ds-accent)"/>
                        <path d="M32 7c6.5 4.5 9.5 12.5 9.5 20.5V41a4 4 0 0 1-4 4h-11a4 4 0 0 1-4-4V27.5C22.5 19.5 25.5 11.5 32 7Z" fill="url(#i3ds-body)"/>
                        <circle cx="32" cy="26" r="5.5" fill="url(#i3ds-accent)"/>
                        <ellipse cx="27.5" cy="21" rx="2.4" ry="6" transform="rotate(14 27.5 21)" fill="url(#i3ds-gloss)"/>
                    </svg>
                    <span>موشک</span>
                </div>
                <!-- ۱۱ آیکون دیگر با همین زبان: نمودار، سپر، ابر، پیام، چرخ‌دنده،
                     قفل، دوربین، باتری، جست‌وجو، پوشه، ستاره -->
            </div>
        </div>

        <style>
        .i3ds { --i3ds-c1: #c7b8ff; --i3ds-c2: #6c4cf1; --i3ds-c3: #8df0ff; --i3ds-c4: #0ea5e9; }
        .i3ds[data-theme='amber'] { --i3ds-c1: #ffe9a8; --i3ds-c2: #f59e0b; }
        .i3ds[data-theme='mint']  { --i3ds-c1: #b7f5dc; --i3ds-c2: #10b981; }
        .i3ds .i3ds-s1 { stop-color: var(--i3ds-c1); }
        .i3ds .i3ds-s2 { stop-color: var(--i3ds-c2); }
        .i3ds .i3ds-sh { fill: url(#i3ds-shadow); }
        .i3ds-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(6.25rem, 1fr)); gap: .75rem; }
        .i3ds-cell { display: grid; justify-items: center; padding: 1rem .5rem; border-radius: 1rem;
                     transform: perspective(38rem) rotateX(var(--i3ds-rx, 0deg)) rotateY(var(--i3ds-ry, 0deg));
                     transition: transform .18s ease; }
        .i3ds-cell:hover { translate: 0 -2px; }
        @media (prefers-reduced-motion: reduce) { .i3ds-cell { transform: none !important; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <!-- Once for the whole set: two shared gradients + the radial shadow -->
        <svg width="0" height="0" aria-hidden="true"><defs>
            <linearGradient id="i3ds-body" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0" class="i3ds-s1"/><stop offset="1" class="i3ds-s2"/>
            </linearGradient>
            <linearGradient id="i3ds-accent" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0" class="i3ds-s3"/><stop offset="1" class="i3ds-s4"/>
            </linearGradient>
            <radialGradient id="i3ds-shadow">
                <stop offset="0" stop-color="#0f172a" stop-opacity=".26"/>
                <stop offset="1" stop-color="#0f172a" stop-opacity="0"/>
            </radialGradient>
        </defs></svg>

        <div class="i3ds" x-data="{
                theme: 'violet',
                tilt(e) {
                    const el = e.currentTarget, r = el.getBoundingClientRect();
                    el.style.setProperty('--i3ds-rx', ((0.5 - (e.clientY - r.top) / r.height) * 12) + 'deg');
                    el.style.setProperty('--i3ds-ry', (((e.clientX - r.left) / r.width - 0.5) * 14) + 'deg');
                },
                reset(e) {
                    e.currentTarget.style.removeProperty('--i3ds-rx');
                    e.currentTarget.style.removeProperty('--i3ds-ry');
                },
            }" :data-theme="theme">
            <div class="i3ds-seg" role="radiogroup" aria-label="Colour theme">
                <button type="button" role="radio" :aria-checked="theme === 'violet'"
                        x-on:click="theme = 'violet'">Violet</button>
                <button type="button" role="radio" :aria-checked="theme === 'amber'"
                        x-on:click="theme = 'amber'">Amber</button>
                <button type="button" role="radio" :aria-checked="theme === 'mint'"
                        x-on:click="theme = 'mint'">Mint</button>
            </div>

            <div class="i3ds-grid">
                <div class="i3ds-cell" x-on:pointermove="tilt($event)" x-on:pointerleave="reset($event)">
                    <svg viewBox="0 0 64 64" role="img" aria-label="Rocket">
                        <ellipse class="i3ds-sh" cx="32" cy="55" rx="15" ry="4.5"/>
                        <path d="M27.5 45h9c.3 3.5-1.7 6.5-4.5 9.5-2.8-3-4.8-6-4.5-9.5Z" fill="url(#i3ds-accent)"/>
                        <path d="M23 33c-5.5 3-8.5 8.5-8.5 15l8.5-5.5Z" fill="url(#i3ds-accent)"/>
                        <path d="M41 33c5.5 3 8.5 8.5 8.5 15L41 42.5Z" fill="url(#i3ds-accent)"/>
                        <path d="M32 7c6.5 4.5 9.5 12.5 9.5 20.5V41a4 4 0 0 1-4 4h-11a4 4 0 0 1-4-4V27.5C22.5 19.5 25.5 11.5 32 7Z" fill="url(#i3ds-body)"/>
                        <circle cx="32" cy="26" r="5.5" fill="url(#i3ds-accent)"/>
                        <ellipse cx="27.5" cy="21" rx="2.4" ry="6" transform="rotate(14 27.5 21)" fill="url(#i3ds-gloss)"/>
                    </svg>
                    <span>Rocket</span>
                </div>
                <!-- 11 more icons in the same language: chart, shield, cloud, message,
                     gear, lock, camera, battery, search, folder, star -->
            </div>
        </div>

        <style>
        .i3ds { --i3ds-c1: #c7b8ff; --i3ds-c2: #6c4cf1; --i3ds-c3: #8df0ff; --i3ds-c4: #0ea5e9; }
        .i3ds[data-theme='amber'] { --i3ds-c1: #ffe9a8; --i3ds-c2: #f59e0b; }
        .i3ds[data-theme='mint']  { --i3ds-c1: #b7f5dc; --i3ds-c2: #10b981; }
        .i3ds .i3ds-s1 { stop-color: var(--i3ds-c1); }
        .i3ds .i3ds-s2 { stop-color: var(--i3ds-c2); }
        .i3ds .i3ds-sh { fill: url(#i3ds-shadow); }
        .i3ds-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(6.25rem, 1fr)); gap: .75rem; }
        .i3ds-cell { display: grid; justify-items: center; padding: 1rem .5rem; border-radius: 1rem;
                     transform: perspective(38rem) rotateX(var(--i3ds-rx, 0deg)) rotateY(var(--i3ds-ry, 0deg));
                     transition: transform .18s ease; }
        .i3ds-cell:hover { translate: 0 -2px; }
        @media (prefers-reduced-motion: reduce) { .i3ds-cell { transform: none !important; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/IconLibrary.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import IconGrid from '@/components/IconGrid';

        export default function IconLibrary() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <IconGrid />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // IconGrid.jsx — the 3D icon set: shared gradient defs, pointer tilt,
        // a segment that recolours every icon at once. No external assets.
        import { useState } from 'react';
        import '@nabuxai/ui-core/css';

        const THEMES = {
          violet: { c1: '#c7b8ff', c2: '#6c4cf1', c3: '#8df0ff', c4: '#0ea5e9' },
          amber: { c1: '#ffe9a8', c2: '#f59e0b', c3: '#c9f2ff', c4: '#38bdf8' },
          mint: { c1: '#b7f5dc', c2: '#10b981', c3: '#e3e0ff', c4: '#8b5cf6' },
        };
        const ICONS = [
          {
            id: 'rocket', label: 'Rocket',
            art: (<>
              <ellipse fill="url(#ic3-shadow)" cx="32" cy="55" rx="15" ry="4.5" />
              <path fill="url(#ic3-accent)" d="M27.5 45h9c.3 3.5-1.7 6.5-4.5 9.5-2.8-3-4.8-6-4.5-9.5Z" />
              <path fill="url(#ic3-accent)" d="M23 33c-5.5 3-8.5 8.5-8.5 15l8.5-5.5Z" />
              <path fill="url(#ic3-accent)" d="M41 33c5.5 3 8.5 8.5 8.5 15L41 42.5Z" />
              <path fill="url(#ic3-body)" d="M32 7c6.5 4.5 9.5 12.5 9.5 20.5V41a4 4 0 0 1-4 4h-11a4 4 0 0 1-4-4V27.5C22.5 19.5 25.5 11.5 32 7Z" />
              <circle fill="url(#ic3-accent)" cx="32" cy="26" r="5.5" />
            </>),
          },
          {
            id: 'chart', label: 'Chart',
            art: (<>
              <ellipse fill="url(#ic3-shadow)" cx="34" cy="55" rx="17" ry="4" />
              <polygon fill="url(#ic3-deep)" points="23,38 27,34 27,48 23,52" />
              <polygon fill="url(#ic3-accent)" points="13,38 17,34 27,34 23,38" />
              <rect fill="url(#ic3-body)" x="13" y="38" width="10" height="14" rx="2" />
              <polygon fill="url(#ic3-deep)" points="37,28 41,24 41,48 37,52" />
              <polygon fill="url(#ic3-accent)" points="27,28 31,24 41,24 37,28" />
              <rect fill="url(#ic3-body)" x="27" y="28" width="10" height="24" rx="2" />
              <polygon fill="url(#ic3-deep)" points="51,18 55,14 55,48 51,52" />
              <polygon fill="url(#ic3-accent)" points="41,18 45,14 55,14 51,18" />
              <rect fill="url(#ic3-body)" x="41" y="18" width="10" height="34" rx="2" />
            </>),
          },
          {
            id: 'shield', label: 'Shield',
            art: (<>
              <ellipse fill="url(#ic3-shadow)" cx="32" cy="54" rx="16" ry="4" />
              <path fill="url(#ic3-body)" d="M32 8l18 6.5V29c0 11.5-7.4 19.9-18 25.2C23.4 48.9 14 40.5 14 29V14.5Z" />
              <path fill="none" stroke="#fff" strokeOpacity=".55" strokeWidth="4.5" strokeLinecap="round" strokeLinejoin="round" d="M25 29l5.2 5.2L40 24" />
            </>),
          },
          // …9 more icons in the same language, same shared gradients.
        ];

        export default function IconGrid() {
          const [theme, setTheme] = useState('violet');
          const t = THEMES[theme];

          // The tilt rides CSS custom properties — React never re-renders on pointermove.
          const tilt = (e) => {
            const el = e.currentTarget;
            const r = el.getBoundingClientRect();
            el.style.setProperty('--ic3-rx', `${(0.5 - (e.clientY - r.top) / r.height) * 12}deg`);
            el.style.setProperty('--ic3-ry', `${((e.clientX - r.left) / r.width - 0.5) * 14}deg`);
          };
          const untilt = (e) => {
            e.currentTarget.style.removeProperty('--ic3-rx');
            e.currentTarget.style.removeProperty('--ic3-ry');
          };

          return (
            <>
              <style>{`
                .ic3-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(6.25rem, 1fr)); gap: .75rem;
                           max-inline-size: 46rem; }
                .ic3-cell { display: grid; justify-items: center; gap: .5rem; padding: 1rem .5rem; border-radius: 1rem;
                            background: color-mix(in oklab, ${t.c1} 14%, transparent);
                            transform: perspective(38rem) rotateX(var(--ic3-rx, 0deg)) rotateY(var(--ic3-ry, 0deg));
                            transition: transform .18s ease, background-color .18s ease; }
                .ic3-cell:hover { background: color-mix(in oklab, ${t.c1} 26%, transparent); }
                .ic3-cell svg { inline-size: 4rem; transition: translate .18s ease; }
                .ic3-cell:hover svg { translate: 0 -4px; }
                .ic3-seg { display: inline-flex; gap: .25rem; padding: .25rem; border-radius: 999px; margin-block-end: 1rem; }
                .ic3-seg button { border: none; border-radius: 999px; padding: .4rem 1rem; cursor: pointer; }
                .ic3-seg button[aria-checked='true'] { background: linear-gradient(135deg, ${t.c1}, ${t.c2}); color: #fff; }
                @media (prefers-reduced-motion: reduce) { .ic3-cell, .ic3-cell svg { transition: none; transform: none; translate: none; } }
              `}</style>
              {/* One hidden defs block colours every icon; the segment just swaps the stops. */}
              <svg width="0" height="0" aria-hidden="true"><defs>
                <linearGradient id="ic3-body" x1="0" y1="0" x2="1" y2="1">
                  <stop offset="0" stopColor={t.c1} /><stop offset="1" stopColor={t.c2} />
                </linearGradient>
                <linearGradient id="ic3-accent" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="0" stopColor={t.c3} /><stop offset="1" stopColor={t.c4} />
                </linearGradient>
                <linearGradient id="ic3-gloss" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="0" stopColor="#fff" stopOpacity=".85" /><stop offset="1" stopColor="#fff" stopOpacity="0" />
                </linearGradient>
                <radialGradient id="ic3-shadow">
                  <stop offset="0" stopColor="#0f172a" stopOpacity=".26" />
                  <stop offset="1" stopColor="#0f172a" stopOpacity="0" />
                </radialGradient>
              </defs></svg>
              <div className="ic3-seg" role="radiogroup" aria-label="Colour theme">
                {Object.keys(THEMES).map((k) => (
                  <button type="button" key={k} role="radio" aria-checked={theme === k} onClick={() => setTheme(k)}>{k}</button>
                ))}
              </div>
              <div className="ic3-grid">
                {ICONS.map((i) => (
                  <div className="ic3-cell" key={i.id} onPointerMove={tilt} onPointerLeave={untilt}>
                    <svg viewBox="0 0 64 64" role="img" aria-label={i.label}>{i.art}</svg>
                    <span>{i.label}</span>
                  </div>
                ))}
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- IconGrid.vue — the 3D icon set: shared gradient defs, pointer tilt, themed segment -->
        <script setup lang="ts">
        import { computed, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const themes = {
          violet: { c1: '#c7b8ff', c2: '#6c4cf1', c3: '#8df0ff', c4: '#0ea5e9' },
          amber: { c1: '#ffe9a8', c2: '#f59e0b', c3: '#c9f2ff', c4: '#38bdf8' },
          mint: { c1: '#b7f5dc', c2: '#10b981', c3: '#e3e0ff', c4: '#8b5cf6' },
        };
        const icons = [
          {
            id: 'rocket', label: 'Rocket',
            art: `<ellipse fill="url(#ic3-shadow)" cx="32" cy="55" rx="15" ry="4.5"/>
                  <path fill="url(#ic3-accent)" d="M27.5 45h9c.3 3.5-1.7 6.5-4.5 9.5-2.8-3-4.8-6-4.5-9.5Z"/>
                  <path fill="url(#ic3-accent)" d="M23 33c-5.5 3-8.5 8.5-8.5 15l8.5-5.5Z"/>
                  <path fill="url(#ic3-accent)" d="M41 33c5.5 3 8.5 8.5 8.5 15L41 42.5Z"/>
                  <path fill="url(#ic3-body)" d="M32 7c6.5 4.5 9.5 12.5 9.5 20.5V41a4 4 0 0 1-4 4h-11a4 4 0 0 1-4-4V27.5C22.5 19.5 25.5 11.5 32 7Z"/>
                  <circle fill="url(#ic3-accent)" cx="32" cy="26" r="5.5"/>`,
          },
          {
            id: 'shield', label: 'Shield',
            art: `<ellipse fill="url(#ic3-shadow)" cx="32" cy="54" rx="16" ry="4"/>
                  <path fill="url(#ic3-body)" d="M32 8l18 6.5V29c0 11.5-7.4 19.9-18 25.2C23.4 48.9 14 40.5 14 29V14.5Z"/>
                  <path fill="none" stroke="#fff" stroke-opacity=".55" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round" d="M25 29l5.2 5.2L40 24"/>`,
          },
          // …10 more icons in the same language, same shared gradients.
        ];
        const theme = ref<'violet' | 'amber' | 'mint'>('violet');
        const t = computed(() => themes[theme.value]);

        // The tilt rides CSS custom properties — no re-render per pointermove.
        function tilt(e: PointerEvent) {
          const el = e.currentTarget as HTMLElement;
          const r = el.getBoundingClientRect();
          el.style.setProperty('--ic3-rx', `${(0.5 - (e.clientY - r.top) / r.height) * 12}deg`);
          el.style.setProperty('--ic3-ry', `${((e.clientX - r.left) / r.width - 0.5) * 14}deg`);
        }
        function untilt(e: PointerEvent) {
          (e.currentTarget as HTMLElement).style.removeProperty('--ic3-rx');
          (e.currentTarget as HTMLElement).style.removeProperty('--ic3-ry');
        }
        </script>

        <template>
          <!-- One hidden defs block colours every icon; the segment just swaps the stops. -->
          <svg width="0" height="0" aria-hidden="true"><defs>
            <linearGradient id="ic3-body" x1="0" y1="0" x2="1" y2="1">
              <stop offset="0" :stop-color="t.c1" /><stop offset="1" :stop-color="t.c2" />
            </linearGradient>
            <linearGradient id="ic3-accent" x1="0" y1="0" x2="0" y2="1">
              <stop offset="0" :stop-color="t.c3" /><stop offset="1" :stop-color="t.c4" />
            </linearGradient>
            <radialGradient id="ic3-shadow">
              <stop offset="0" stop-color="#0f172a" stop-opacity=".26" />
              <stop offset="1" stop-color="#0f172a" stop-opacity="0" />
            </radialGradient>
          </defs></svg>
          <div class="ic3-seg" role="radiogroup" aria-label="Colour theme">
            <button v-for="(c, k) in themes" :key="k" type="button" role="radio"
                    :aria-checked="theme === k" @click="theme = k as any">{{ k }}</button>
          </div>
          <div class="ic3-grid">
            <div v-for="i in icons" :key="i.id" class="ic3-cell" @pointermove="tilt" @pointerleave="untilt">
              <svg viewBox="0 0 64 64" role="img" :aria-label="i.label" v-html="i.art" />
              <span>{{ i.label }}</span>
            </div>
          </div>
        </template>

        <style scoped>
        .ic3-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(6.25rem, 1fr)); gap: .75rem;
                    max-inline-size: 46rem; }
        .ic3-cell { display: grid; justify-items: center; gap: .5rem; padding: 1rem .5rem; border-radius: 1rem;
                    background: color-mix(in oklab, v-bind('t.c1') 14%, transparent);
                    transform: perspective(38rem) rotateX(var(--ic3-rx, 0deg)) rotateY(var(--ic3-ry, 0deg));
                    transition: transform .18s ease, background-color .18s ease; }
        .ic3-cell:hover { background: color-mix(in oklab, v-bind('t.c1') 26%, transparent); }
        .ic3-cell svg { inline-size: 4rem; transition: translate .18s ease; }
        .ic3-cell:hover svg { translate: 0 -4px; }
        .ic3-seg { display: inline-flex; gap: .25rem; padding: .25rem; border-radius: 999px; margin-block-end: 1rem; }
        .ic3-seg button { border: none; border-radius: 999px; padding: .4rem 1rem; cursor: pointer; }
        .ic3-seg button[aria-checked='true'] { background: linear-gradient(135deg, v-bind('t.c1'), v-bind('t.c2')); color: #fff; }
        @media (prefers-reduced-motion: reduce) { :deep(.ic3-cell), .ic3-cell svg { transition: none; transform: none; translate: none; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- IconGrid.svelte — the 3D icon set: shared gradient defs, pointer tilt, themed segment -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const themes = {
            violet: { c1: '#c7b8ff', c2: '#6c4cf1', c3: '#8df0ff', c4: '#0ea5e9' },
            amber: { c1: '#ffe9a8', c2: '#f59e0b', c3: '#c9f2ff', c4: '#38bdf8' },
            mint: { c1: '#b7f5dc', c2: '#10b981', c3: '#e3e0ff', c4: '#8b5cf6' },
          };
          const icons = [
            {
              id: 'rocket', label: 'Rocket',
              art: `<ellipse fill="url(#ic3-shadow)" cx="32" cy="55" rx="15" ry="4.5"/>
                    <path fill="url(#ic3-accent)" d="M27.5 45h9c.3 3.5-1.7 6.5-4.5 9.5-2.8-3-4.8-6-4.5-9.5Z"/>
                    <path fill="url(#ic3-accent)" d="M23 33c-5.5 3-8.5 8.5-8.5 15l8.5-5.5Z"/>
                    <path fill="url(#ic3-accent)" d="M41 33c5.5 3 8.5 8.5 8.5 15L41 42.5Z"/>
                    <path fill="url(#ic3-body)" d="M32 7c6.5 4.5 9.5 12.5 9.5 20.5V41a4 4 0 0 1-4 4h-11a4 4 0 0 1-4-4V27.5C22.5 19.5 25.5 11.5 32 7Z"/>
                    <circle fill="url(#ic3-accent)" cx="32" cy="26" r="5.5"/>`,
            },
            {
              id: 'shield', label: 'Shield',
              art: `<ellipse fill="url(#ic3-shadow)" cx="32" cy="54" rx="16" ry="4"/>
                    <path fill="url(#ic3-body)" d="M32 8l18 6.5V29c0 11.5-7.4 19.9-18 25.2C23.4 48.9 14 40.5 14 29V14.5Z"/>
                    <path fill="none" stroke="#fff" stroke-opacity=".55" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round" d="M25 29l5.2 5.2L40 24"/>`,
            },
            // …10 more icons in the same language, same shared gradients.
          ];
          let theme = $state<'violet' | 'amber' | 'mint'>('violet');
          const t = $derived(themes[theme]);

          // The tilt rides CSS custom properties — no re-render per pointermove.
          function tilt(e: PointerEvent) {
            const el = e.currentTarget as HTMLElement;
            const r = el.getBoundingClientRect();
            el.style.setProperty('--ic3-rx', `${(0.5 - (e.clientY - r.top) / r.height) * 12}deg`);
            el.style.setProperty('--ic3-ry', `${((e.clientX - r.left) / r.width - 0.5) * 14}deg`);
          }
          function untilt(e: PointerEvent) {
            (e.currentTarget as HTMLElement).style.removeProperty('--ic3-rx');
            (e.currentTarget as HTMLElement).style.removeProperty('--ic3-ry');
          }
        </script>

        <!-- One hidden defs block colours every icon; the segment just swaps the stops. -->
        <svg width="0" height="0" aria-hidden="true"><defs>
          <linearGradient id="ic3-body" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0" stop-color={t.c1} /><stop offset="1" stop-color={t.c2} />
          </linearGradient>
          <linearGradient id="ic3-accent" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stop-color={t.c3} /><stop offset="1" stop-color={t.c4} />
          </linearGradient>
          <radialGradient id="ic3-shadow">
            <stop offset="0" stop-color="#0f172a" stop-opacity=".26" />
            <stop offset="1" stop-color="#0f172a" stop-opacity="0" />
          </radialGradient>
        </defs></svg>
        <div class="ic3-seg" role="radiogroup" aria-label="Colour theme">
          {#each Object.entries(themes) as [k] (k)}
            <button type="button" role="radio" aria-checked={theme === k} onclick={() => (theme = k as any)}>{k}</button>
          {/each}
        </div>
        <div class="ic3-grid">
          {#each icons as i (i.id)}
            <div class="ic3-cell" onpointermove={tilt} onpointerleave={untilt}>
              <svg viewBox="0 0 64 64" role="img" aria-label={i.label}>{@html i.art}</svg>
              <span>{i.label}</span>
            </div>
          {/each}
        </div>

        <style>
        .ic3-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(6.25rem, 1fr)); gap: .75rem;
                    max-inline-size: 46rem; }
        .ic3-cell { display: grid; justify-items: center; gap: .5rem; padding: 1rem .5rem; border-radius: 1rem;
                    background: color-mix(in oklab, v-bind(t.c1) 14%, transparent);
                    transform: perspective(38rem) rotateX(var(--ic3-rx, 0deg)) rotateY(var(--ic3-ry, 0deg));
                    transition: transform .18s ease, background-color .18s ease; }
        .ic3-cell:hover { background: color-mix(in oklab, v-bind(t.c1) 26%, transparent); }
        .ic3-cell svg { inline-size: 4rem; transition: translate .18s ease; }
        .ic3-cell:hover svg { translate: 0 -4px; }
        .ic3-seg { display: inline-flex; gap: .25rem; padding: .25rem; border-radius: 999px; margin-block-end: 1rem; }
        .ic3-seg button { border: none; border-radius: 999px; padding: .4rem 1rem; cursor: pointer; }
        .ic3-seg button[aria-checked='true'] { background: linear-gradient(135deg, v-bind(t.c1), v-bind(t.c2)); color: #fff; }
        @media (prefers-reduced-motion: reduce) { .ic3-cell, .ic3-cell svg { transition: none; transform: none; translate: none; } }
        </style>
        SVELTE,
        ],
    ],

    'icon-scenes' => [
        'title' => ['fa' => 'صحنه‌های آیکون سه‌بعدی', 'en' => '3D icon scenes'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'همان زبان سه‌بعدی در سه صحنهٔ واقعی Northlight: حالت خالی که آیکون جعبهٔ بازش داستان را روایت می‌کند، ردیف قابلیت‌هایی که آیکون‌ها ستون فقراتشان‌اند و پلن‌های قیمتی که با سوییچ ماهانه/سالانه زنده بازمحاسبه می‌شوند — اینجا آیکون تزئین نیست، اسکلت صحنه است.',
            'en' => 'The same 3D language in three real Northlight scenes: an empty state whose open-box icon tells the story, a feature row where the icons are the backbone, and pricing plans that recompute live with the monthly/annual switch — here the icon is not decoration, it is the skeleton of the scene.',
        ],
        'js' => true,
        'docs' => 'https://3dicons.co',
        'props' => [
            ['name' => 'scene', 'type' => 'string', 'default' => "'empty · features · pricing'", 'note' => [
                'fa' => 'سه صحنهٔ واقعی؛ در هر سه، آیکون اول می‌نشیند و متن دورش چیده می‌شود — نه برعکس.',
                'en' => 'Three real scenes; in all of them the icon lands first and the copy is composed around it — not the other way round.',
            ]],
            ['name' => 'billing', 'type' => 'segment', 'default' => "'monthly | annual'", 'note' => [
                'fa' => 'سوییچ صورتحساب قیمت هر سه پلن را همان‌جا بازمحاسبه می‌کند؛ سالانه حدود ۲۰٪ کمتر است.',
                'en' => 'The billing switch recomputes all three plan prices in place; annual saves roughly 20%.',
            ]],
            ['name' => 'empty-action', 'type' => 'state', 'default' => "'inline'", 'note' => [
                'fa' => 'دکمهٔ اصلی حالت خالی نتیجه را همان‌جا زیر دکمه‌ها می‌گوید؛ بدون پاپ‌آپ و بدون صفحه‌ای تازه.',
                'en' => 'The empty state’s primary button reports its result inline, right under the actions — no popup, no new page.',
            ]],
            ['name' => 'icon-size', 'type' => 'px', 'default' => "'52 · 56 · 104'", 'note' => [
                'fa' => 'قابلیت ۵۲، پلن ۵۶ و حالت خالی ۱۰۴ پیکسل — اندازهٔ آیکون با وزن صحنه سنگین‌تر می‌شود.',
                'en' => 'Features 52, plans 56 and the empty state 104px — the icon grows with the weight of the scene.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        {{-- یک بار برای هر سه صحنه: دو گرادیان مشترک + سایهٔ شعاعی --}}
        <svg width="0" height="0" aria-hidden="true"><defs>
            <linearGradient id="i3sc-body" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0" class="i3sc-s1"/><stop offset="1" class="i3sc-s2"/>
            </linearGradient>
            <linearGradient id="i3sc-accent" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0" class="i3sc-s3"/><stop offset="1" class="i3sc-s4"/>
            </linearGradient>
            <radialGradient id="i3sc-shadow">
                <stop offset="0" stop-color="#0f172a" stop-opacity=".26"/>
                <stop offset="1" stop-color="#0f172a" stop-opacity="0"/>
            </radialGradient>
        </defs></svg>

        {{-- صحنهٔ ۱: حالت خالی — آیکون جعبهٔ باز داستان را می‌گوید --}}
        <div class="i3sc-empty" x-data="{ created: false }">
            <svg class="i3sc-empty-icon" viewBox="0 0 64 64" role="img" aria-label="جعبهٔ خالی">
                <ellipse class="i3sc-sh" cx="32" cy="53" rx="17" ry="4.5"/>
                <path d="M14 30h36v14a5 5 0 0 1-5 5H19a5 5 0 0 1-5-5Z" fill="url(#i3sc-body)"/>
                <path d="M14 30 6 24v9l8 5Z" fill="url(#i3sc-accent)"/>
                <path d="M50 30l8-6v9l-8 5Z" fill="url(#i3sc-accent)"/>
                <path d="M41 8l1.9 4.3L47 14l-4.1 1.7L41 20l-1.9-4.3L35 14l4.1-1.7Z" fill="url(#i3sc-accent)"/>
            </svg>
            <h4>فضای کاری خالی است</h4>
            <p>اولین پروژهٔ Northlight را بسازید یا با یک قالب آماده شروع کنید؛ راه‌اندازی کمتر از دو دقیقه است.</p>
            <button type="button" class="i3sc-btn" x-on:click="created = true">ساخت اولین پروژه</button>
            <p class="i3sc-note" x-show="created" x-cloak>در حال آماده‌سازی مخزن در فرانکفورت… چند ثانیه بیشتر نیست.</p>
        </div>

        {{-- صحنهٔ ۳: پلن‌ها — قیمت با سوییچ ماهانه/سالانه زنده عوض می‌شود --}}
        <div class="i3sc-plans" x-data="{
                cycle: 'annual',
                plans: { launch: { m: '۱۲$', a: '۹$' }, growth: { m: '۲۹$', a: '۲۳$' }, scale: { m: '۹۹$', a: '۷۹$' } },
                labelM: 'در هر ماه', labelA: 'در هر ماه، صورتحساب سالانه',
            }">
            <div class="i3sc-seg" role="radiogroup" aria-label="چرخهٔ صورتحساب">
                <button type="button" role="radio" :aria-checked="cycle === 'monthly'"
                        x-on:click="cycle = 'monthly'">ماهانه</button>
                <button type="button" role="radio" :aria-checked="cycle === 'annual'"
                        x-on:click="cycle = 'annual'">سالانه</button>
            </div>
            <article class="i3sc-plan" data-featured>
                <svg viewBox="0 0 64 64" aria-hidden="true"><!-- آیکون لایه‌ها --></svg>
                <h4>رشد</h4>
                <p><b x-text="cycle === 'monthly' ? plans.growth.m : plans.growth.a"></b>
                    <small x-text="cycle === 'monthly' ? labelM : labelA"></small></p>
            </article>
        </div>

        <style>
        .i3sc .i3sc-s1 { stop-color: #c7b8ff; } .i3sc .i3sc-s2 { stop-color: #6c4cf1; }
        .i3sc .i3sc-s3 { stop-color: #8df0ff; } .i3sc .i3sc-s4 { stop-color: #0ea5e9; }
        .i3sc .i3sc-sh { fill: url(#i3sc-shadow); }
        .i3sc-empty { display: grid; justify-items: center; text-align: center; gap: .75rem; }
        .i3sc-empty-icon { inline-size: 6.5rem; }
        .i3sc-plans { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 15rem), 1fr)); gap: 1rem; }
        .i3sc-plan { display: grid; gap: .6rem; padding: 1.5rem; border-radius: 1.25rem; border: 1px solid #e6e2ff; }
        .i3sc-plan[data-featured] { border-color: #6c4cf1; box-shadow: 0 12px 30px #6c4cf12e; }
        .i3sc-btn { border: none; border-radius: 999px; padding: .65rem 1.25rem; cursor: pointer;
                    background: linear-gradient(135deg, #c7b8ff, #6c4cf1); color: #fff; }
        @media (prefers-reduced-motion: reduce) { .i3sc * { transition: none !important; animation: none !important; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <!-- Once for all three scenes: two shared gradients + the radial shadow -->
        <svg width="0" height="0" aria-hidden="true"><defs>
            <linearGradient id="i3sc-body" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0" class="i3sc-s1"/><stop offset="1" class="i3sc-s2"/>
            </linearGradient>
            <linearGradient id="i3sc-accent" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0" class="i3sc-s3"/><stop offset="1" class="i3sc-s4"/>
            </linearGradient>
            <radialGradient id="i3sc-shadow">
                <stop offset="0" stop-color="#0f172a" stop-opacity=".26"/>
                <stop offset="1" stop-color="#0f172a" stop-opacity="0"/>
            </radialGradient>
        </defs></svg>

        <!-- Scene 1: empty state — the open-box icon tells the story -->
        <div class="i3sc-empty" x-data="{ created: false }">
            <svg class="i3sc-empty-icon" viewBox="0 0 64 64" role="img" aria-label="Empty box">
                <ellipse class="i3sc-sh" cx="32" cy="53" rx="17" ry="4.5"/>
                <path d="M14 30h36v14a5 5 0 0 1-5 5H19a5 5 0 0 1-5-5Z" fill="url(#i3sc-body)"/>
                <path d="M14 30 6 24v9l8 5Z" fill="url(#i3sc-accent)"/>
                <path d="M50 30l8-6v9l-8 5Z" fill="url(#i3sc-accent)"/>
                <path d="M41 8l1.9 4.3L47 14l-4.1 1.7L41 20l-1.9-4.3L35 14l4.1-1.7Z" fill="url(#i3sc-accent)"/>
            </svg>
            <h4>Your workspace is empty</h4>
            <p>Create your first Northlight project or start from a ready template — setup takes under two minutes.</p>
            <button type="button" class="i3sc-btn" x-on:click="created = true">Create first project</button>
            <p class="i3sc-note" x-show="created" x-cloak>Preparing your repository in Frankfurt… just a few seconds.</p>
        </div>

        <!-- Scene 3: plans — prices recompute live with the monthly/annual switch -->
        <div class="i3sc-plans" x-data="{
                cycle: 'annual',
                plans: { launch: { m: '$12', a: '$9' }, growth: { m: '$29', a: '$23' }, scale: { m: '$99', a: '$79' } },
                labelM: 'per month', labelA: 'per month, billed yearly',
            }">
            <div class="i3sc-seg" role="radiogroup" aria-label="Billing cycle">
                <button type="button" role="radio" :aria-checked="cycle === 'monthly'"
                        x-on:click="cycle = 'monthly'">Monthly</button>
                <button type="button" role="radio" :aria-checked="cycle === 'annual'"
                        x-on:click="cycle = 'annual'">Annual</button>
            </div>
            <article class="i3sc-plan" data-featured>
                <svg viewBox="0 0 64 64" aria-hidden="true"><!-- the layers icon --></svg>
                <h4>Growth</h4>
                <p><b x-text="cycle === 'monthly' ? plans.growth.m : plans.growth.a"></b>
                    <small x-text="cycle === 'monthly' ? labelM : labelA"></small></p>
            </article>
        </div>

        <style>
        .i3sc .i3sc-s1 { stop-color: #c7b8ff; } .i3sc .i3sc-s2 { stop-color: #6c4cf1; }
        .i3sc .i3sc-s3 { stop-color: #8df0ff; } .i3sc .i3sc-s4 { stop-color: #0ea5e9; }
        .i3sc .i3sc-sh { fill: url(#i3sc-shadow); }
        .i3sc-empty { display: grid; justify-items: center; text-align: center; gap: .75rem; }
        .i3sc-empty-icon { inline-size: 6.5rem; }
        .i3sc-plans { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 15rem), 1fr)); gap: 1rem; }
        .i3sc-plan { display: grid; gap: .6rem; padding: 1.5rem; border-radius: 1.25rem; border: 1px solid #e6e2ff; }
        .i3sc-plan[data-featured] { border-color: #6c4cf1; box-shadow: 0 12px 30px #6c4cf12e; }
        .i3sc-btn { border: none; border-radius: 999px; padding: .65rem 1.25rem; cursor: pointer;
                    background: linear-gradient(135deg, #c7b8ff, #6c4cf1); color: #fff; }
        @media (prefers-reduced-motion: reduce) { .i3sc * { transition: none !important; animation: none !important; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Pricing.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import PricingScenes from '@/components/PricingScenes';

        export default function Pricing() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <PricingScenes />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // PricingScenes.jsx — 3D icons as the backbone of real scenes: the
        // empty state and pricing plans, one hidden defs block for every icon.
        import { useState } from 'react';
        import '@nabuxai/ui-core/css';

        const PLANS = [
          { id: 'launch', name: 'Launch', m: 12, a: 9, icon: 'rocket', perks: ['3 projects', 'GitHub deploys', 'Community support'] },
          { id: 'growth', name: 'Growth', m: 29, a: 23, icon: 'layers', perks: ['Unlimited projects', 'Preview environments', '4-hour support'], featured: true },
          { id: 'scale', name: 'Scale', m: 99, a: 79, icon: 'crown', perks: ['Dedicated regions', 'Audit log', '1-hour support'] },
        ];
        const ART = {
          rocket: <>
            <ellipse fill="url(#sc3-shadow)" cx="32" cy="55" rx="15" ry="4.5" />
            <path fill="url(#sc3-accent)" d="M27.5 45h9c.3 3.5-1.7 6.5-4.5 9.5-2.8-3-4.8-6-4.5-9.5Z" />
            <path fill="url(#sc3-accent)" d="M23 33c-5.5 3-8.5 8.5-8.5 15l8.5-5.5Z" />
            <path fill="url(#sc3-accent)" d="M41 33c5.5 3 8.5 8.5 8.5 15L41 42.5Z" />
            <path fill="url(#sc3-body)" d="M32 7c6.5 4.5 9.5 12.5 9.5 20.5V41a4 4 0 0 1-4 4h-11a4 4 0 0 1-4-4V27.5C22.5 19.5 25.5 11.5 32 7Z" />
            <circle fill="url(#sc3-accent)" cx="32" cy="26" r="5.5" />
          </>,
          layers: <>
            <ellipse fill="url(#sc3-shadow)" cx="32" cy="54" rx="18" ry="4" />
            <path fill="url(#sc3-deep)" d="M13 29.5 32 39l19-9.5v8L32 47.5 13 37.5Z" />
            <path fill="url(#sc3-body)" d="M13 21.5 32 31l19-9.5v8L32 39 13 29.5Z" />
            <path fill="url(#sc3-accent)" d="M32 12l19 9.5L32 31l-19-9.5Z" />
          </>,
          crown: <>
            <ellipse fill="url(#sc3-shadow)" cx="32" cy="54" rx="16" ry="4" />
            <path fill="url(#sc3-body)" d="M13 42 9 22l12.5 8L32 15l10.5 15L55 22l-4 20Z" />
            <path fill="url(#sc3-accent)" d="M13 42h38v6a3 3 0 0 1-3 3H16a3 3 0 0 1-3-3Z" />
            <circle fill="url(#sc3-accent)" cx="32" cy="13" r="2.5" />
          </>,
        };

        export default function PricingScenes() {
          const [cycle, setCycle] = useState('annual');
          const [created, setCreated] = useState(false);

          return (
            <>
              <style>{`
                .sc3-empty { display: grid; justify-items: center; text-align: center; gap: .75rem; padding: 2.5rem 1rem; }
                .sc3-empty svg { inline-size: 6.5rem; }
                .sc3-plans { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 15rem), 1fr)); gap: 1rem; }
                .sc3-plan { display: grid; align-content: start; gap: .6rem; padding: 1.5rem; border-radius: 1.25rem;
                            border: 1px solid #e6e2ff; background: #fff; }
                .sc3-plan[data-featured='true'] { border-color: #6c4cf1; box-shadow: 0 12px 30px #6c4cf12e; }
                .sc3-plan svg { inline-size: 3.5rem; }
                .sc3-price { font-size: 1.75rem; font-weight: 700; }
                .sc3-seg { display: inline-flex; gap: .25rem; padding: .25rem; border-radius: 999px; background: #f1eeff; }
                .sc3-seg button { border: none; border-radius: 999px; padding: .4rem 1rem; cursor: pointer; }
                .sc3-seg button[aria-checked='true'] { background: linear-gradient(135deg, #c7b8ff, #6c4cf1); color: #fff; }
                .sc3-btn { justify-self: center; border: none; border-radius: 999px; padding: .65rem 1.25rem; cursor: pointer;
                           background: linear-gradient(135deg, #c7b8ff, #6c4cf1); color: #fff; }
              `}</style>
              {/* One hidden defs block colours every icon in every scene. */}
              <svg width="0" height="0" aria-hidden="true"><defs>
                <linearGradient id="sc3-body" x1="0" y1="0" x2="1" y2="1">
                  <stop offset="0" stopColor="#c7b8ff" /><stop offset="1" stopColor="#6c4cf1" />
                </linearGradient>
                <linearGradient id="sc3-accent" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="0" stopColor="#8df0ff" /><stop offset="1" stopColor="#0ea5e9" />
                </linearGradient>
                <radialGradient id="sc3-shadow">
                  <stop offset="0" stopColor="#0f172a" stopOpacity=".26" />
                  <stop offset="1" stopColor="#0f172a" stopOpacity="0" />
                </radialGradient>
              </defs></svg>

              {/* Scene 1 — the empty state; the open-box icon leads, the copy follows. */}
              <div className="sc3-empty">
                <svg viewBox="0 0 64 64" role="img" aria-label="Empty box">
                  <ellipse fill="url(#sc3-shadow)" cx="32" cy="53" rx="17" ry="4.5" />
                  <path fill="url(#sc3-body)" d="M14 30h36v14a5 5 0 0 1-5 5H19a5 5 0 0 1-5-5Z" />
                  <path fill="url(#sc3-accent)" d="M14 30 6 24v9l8 5Z" />
                  <path fill="url(#sc3-accent)" d="M50 30l8-6v9l-8 5Z" />
                  <path fill="url(#sc3-accent)" d="M41 8l1.9 4.3L47 14l-4.1 1.7L41 20l-1.9-4.3L35 14l4.1-1.7Z" />
                </svg>
                <h4>Your workspace is empty</h4>
                <p>Create your first Northlight project or start from a ready template — setup takes under two minutes.</p>
                <button type="button" className="sc3-btn" onClick={() => setCreated(true)}>Create first project</button>
                {created && <p role="status">Preparing your repository in Frankfurt… just a few seconds.</p>}
              </div>

              {/* Scene 3 — pricing; the monthly/annual switch recomputes every card. */}
              <div className="sc3-seg" role="radiogroup" aria-label="Billing cycle">
                <button type="button" role="radio" aria-checked={cycle === 'monthly'} onClick={() => setCycle('monthly')}>Monthly</button>
                <button type="button" role="radio" aria-checked={cycle === 'annual'} onClick={() => setCycle('annual')}>Annual</button>
              </div>
              <div className="sc3-plans">
                {PLANS.map((p) => (
                  <article className="sc3-plan" data-featured={p.featured} key={p.id}>
                    <svg viewBox="0 0 64 64" role="img" aria-label={p.name}>{ART[p.icon]}</svg>
                    <h4>{p.name}</h4>
                    <p className="sc3-price">${cycle === 'monthly' ? p.m : p.a}
                      <small> per month{cycle === 'annual' ? ', billed yearly' : ''}</small>
                    </p>
                    <ul>
                      {p.perks.map((perk) => <li key={perk}>{perk}</li>)}
                    </ul>
                    <button type="button" className="sc3-btn">Choose {p.name}</button>
                  </article>
                ))}
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- PricingScenes.vue — 3D icons as the backbone of real scenes -->
        <script setup lang="ts">
        import { ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const plans = [
          { id: 'launch', name: 'Launch', m: 12, a: 9, icon: 'rocket', perks: ['3 projects', 'GitHub deploys', 'Community support'], featured: false },
          { id: 'growth', name: 'Growth', m: 29, a: 23, icon: 'layers', perks: ['Unlimited projects', 'Preview environments', '4-hour support'], featured: true },
          { id: 'scale', name: 'Scale', m: 99, a: 79, icon: 'crown', perks: ['Dedicated regions', 'Audit log', '1-hour support'], featured: false },
        ];
        const art: Record<string, string> = {
          rocket: `<ellipse fill="url(#sc3-shadow)" cx="32" cy="55" rx="15" ry="4.5"/>
                   <path fill="url(#sc3-accent)" d="M27.5 45h9c.3 3.5-1.7 6.5-4.5 9.5-2.8-3-4.8-6-4.5-9.5Z"/>
                   <path fill="url(#sc3-accent)" d="M23 33c-5.5 3-8.5 8.5-8.5 15l8.5-5.5Z"/>
                   <path fill="url(#sc3-accent)" d="M41 33c5.5 3 8.5 8.5 8.5 15L41 42.5Z"/>
                   <path fill="url(#sc3-body)" d="M32 7c6.5 4.5 9.5 12.5 9.5 20.5V41a4 4 0 0 1-4 4h-11a4 4 0 0 1-4-4V27.5C22.5 19.5 25.5 11.5 32 7Z"/>
                   <circle fill="url(#sc3-accent)" cx="32" cy="26" r="5.5"/>`,
          layers: `<ellipse fill="url(#sc3-shadow)" cx="32" cy="54" rx="18" ry="4"/>
                   <path fill="url(#sc3-deep)" d="M13 29.5 32 39l19-9.5v8L32 47.5 13 37.5Z"/>
                   <path fill="url(#sc3-body)" d="M13 21.5 32 31l19-9.5v8L32 39 13 29.5Z"/>
                   <path fill="url(#sc3-accent)" d="M32 12l19 9.5L32 31l-19-9.5Z"/>`,
          crown: `<ellipse fill="url(#sc3-shadow)" cx="32" cy="54" rx="16" ry="4"/>
                  <path fill="url(#sc3-body)" d="M13 42 9 22l12.5 8L32 15l10.5 15L55 22l-4 20Z"/>
                  <path fill="url(#sc3-accent)" d="M13 42h38v6a3 3 0 0 1-3 3H16a3 3 0 0 1-3-3Z"/>
                  <circle fill="url(#sc3-accent)" cx="32" cy="13" r="2.5"/>`,
        };
        const cycle = ref<'monthly' | 'annual'>('annual');
        const created = ref(false);
        </script>

        <template>
          <!-- One hidden defs block colours every icon in every scene. -->
          <svg width="0" height="0" aria-hidden="true"><defs>
            <linearGradient id="sc3-body" x1="0" y1="0" x2="1" y2="1">
              <stop offset="0" stop-color="#c7b8ff" /><stop offset="1" stop-color="#6c4cf1" />
            </linearGradient>
            <linearGradient id="sc3-accent" x1="0" y1="0" x2="0" y2="1">
              <stop offset="0" stop-color="#8df0ff" /><stop offset="1" stop-color="#0ea5e9" />
            </linearGradient>
            <radialGradient id="sc3-shadow">
              <stop offset="0" stop-color="#0f172a" stop-opacity=".26" />
              <stop offset="1" stop-color="#0f172a" stop-opacity="0" />
            </radialGradient>
          </defs></svg>

          <!-- Scene 1 — the empty state; the open-box icon leads, the copy follows. -->
          <div class="sc3-empty">
            <svg viewBox="0 0 64 64" role="img" aria-label="Empty box">
              <ellipse fill="url(#sc3-shadow)" cx="32" cy="53" rx="17" ry="4.5" />
              <path fill="url(#sc3-body)" d="M14 30h36v14a5 5 0 0 1-5 5H19a5 5 0 0 1-5-5Z" />
              <path fill="url(#sc3-accent)" d="M14 30 6 24v9l8 5Z" />
              <path fill="url(#sc3-accent)" d="M50 30l8-6v9l-8 5Z" />
              <path fill="url(#sc3-accent)" d="M41 8l1.9 4.3L47 14l-4.1 1.7L41 20l-1.9-4.3L35 14l4.1-1.7Z" />
            </svg>
            <h4>Your workspace is empty</h4>
            <p>Create your first Northlight project or start from a ready template — setup takes under two minutes.</p>
            <button type="button" class="sc3-btn" @click="created = true">Create first project</button>
            <p v-if="created" role="status">Preparing your repository in Frankfurt… just a few seconds.</p>
          </div>

          <!-- Scene 3 — pricing; the monthly/annual switch recomputes every card. -->
          <div class="sc3-seg" role="radiogroup" aria-label="Billing cycle">
            <button type="button" role="radio" :aria-checked="cycle === 'monthly'" @click="cycle = 'monthly'">Monthly</button>
            <button type="button" role="radio" :aria-checked="cycle === 'annual'" @click="cycle = 'annual'">Annual</button>
          </div>
          <div class="sc3-plans">
            <article v-for="p in plans" :key="p.id" class="sc3-plan" :data-featured="p.featured">
              <svg viewBox="0 0 64 64" role="img" :aria-label="p.name" v-html="art[p.icon]" />
              <h4>{{ p.name }}</h4>
              <p class="sc3-price">${{ cycle === 'monthly' ? p.m : p.a }}
                <small> per month<template v-if="cycle === 'annual'">, billed yearly</template></small>
              </p>
              <ul>
                <li v-for="perk in p.perks" :key="perk">{{ perk }}</li>
              </ul>
              <button type="button" class="sc3-btn">Choose {{ p.name }}</button>
            </article>
          </div>
        </template>

        <style scoped>
        .sc3-empty { display: grid; justify-items: center; text-align: center; gap: .75rem; padding: 2.5rem 1rem; }
        .sc3-empty svg { inline-size: 6.5rem; }
        .sc3-plans { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 15rem), 1fr)); gap: 1rem; }
        .sc3-plan { display: grid; align-content: start; gap: .6rem; padding: 1.5rem; border-radius: 1.25rem;
                    border: 1px solid #e6e2ff; background: #fff; }
        .sc3-plan[data-featured='true'] { border-color: #6c4cf1; box-shadow: 0 12px 30px #6c4cf12e; }
        .sc3-plan svg { inline-size: 3.5rem; }
        .sc3-price { font-size: 1.75rem; font-weight: 700; }
        .sc3-seg { display: inline-flex; gap: .25rem; padding: .25rem; border-radius: 999px; background: #f1eeff; }
        .sc3-seg button { border: none; border-radius: 999px; padding: .4rem 1rem; cursor: pointer; }
        .sc3-seg button[aria-checked='true'] { background: linear-gradient(135deg, #c7b8ff, #6c4cf1); color: #fff; }
        .sc3-btn { justify-self: center; border: none; border-radius: 999px; padding: .65rem 1.25rem; cursor: pointer;
                   background: linear-gradient(135deg, #c7b8ff, #6c4cf1); color: #fff; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- PricingScenes.svelte — 3D icons as the backbone of real scenes -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const plans = [
            { id: 'launch', name: 'Launch', m: 12, a: 9, icon: 'rocket', perks: ['3 projects', 'GitHub deploys', 'Community support'], featured: false },
            { id: 'growth', name: 'Growth', m: 29, a: 23, icon: 'layers', perks: ['Unlimited projects', 'Preview environments', '4-hour support'], featured: true },
            { id: 'scale', name: 'Scale', m: 99, a: 79, icon: 'crown', perks: ['Dedicated regions', 'Audit log', '1-hour support'], featured: false },
          ];
          const art: Record<string, string> = {
            rocket: `<ellipse fill="url(#sc3-shadow)" cx="32" cy="55" rx="15" ry="4.5"/>
                     <path fill="url(#sc3-accent)" d="M27.5 45h9c.3 3.5-1.7 6.5-4.5 9.5-2.8-3-4.8-6-4.5-9.5Z"/>
                     <path fill="url(#sc3-accent)" d="M23 33c-5.5 3-8.5 8.5-8.5 15l8.5-5.5Z"/>
                     <path fill="url(#sc3-accent)" d="M41 33c5.5 3 8.5 8.5 8.5 15L41 42.5Z"/>
                     <path fill="url(#sc3-body)" d="M32 7c6.5 4.5 9.5 12.5 9.5 20.5V41a4 4 0 0 1-4 4h-11a4 4 0 0 1-4-4V27.5C22.5 19.5 25.5 11.5 32 7Z"/>
                     <circle fill="url(#sc3-accent)" cx="32" cy="26" r="5.5"/>`,
            layers: `<ellipse fill="url(#sc3-shadow)" cx="32" cy="54" rx="18" ry="4"/>
                     <path fill="url(#sc3-deep)" d="M13 29.5 32 39l19-9.5v8L32 47.5 13 37.5Z"/>
                     <path fill="url(#sc3-body)" d="M13 21.5 32 31l19-9.5v8L32 39 13 29.5Z"/>
                     <path fill="url(#sc3-accent)" d="M32 12l19 9.5L32 31l-19-9.5Z"/>`,
            crown: `<ellipse fill="url(#sc3-shadow)" cx="32" cy="54" rx="16" ry="4"/>
                    <path fill="url(#sc3-body)" d="M13 42 9 22l12.5 8L32 15l10.5 15L55 22l-4 20Z"/>
                    <path fill="url(#sc3-accent)" d="M13 42h38v6a3 3 0 0 1-3 3H16a3 3 0 0 1-3-3Z"/>
                    <circle fill="url(#sc3-accent)" cx="32" cy="13" r="2.5"/>`,
          };
          let cycle = $state<'monthly' | 'annual'>('annual');
          let created = $state(false);
        </script>

        <!-- One hidden defs block colours every icon in every scene. -->
        <svg width="0" height="0" aria-hidden="true"><defs>
          <linearGradient id="sc3-body" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0" stop-color="#c7b8ff" /><stop offset="1" stop-color="#6c4cf1" />
          </linearGradient>
          <linearGradient id="sc3-accent" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stop-color="#8df0ff" /><stop offset="1" stop-color="#0ea5e9" />
          </linearGradient>
          <radialGradient id="sc3-shadow">
            <stop offset="0" stop-color="#0f172a" stop-opacity=".26" />
            <stop offset="1" stop-color="#0f172a" stop-opacity="0" />
          </radialGradient>
        </defs></svg>

        <!-- Scene 1 — the empty state; the open-box icon leads, the copy follows. -->
        <div class="sc3-empty">
          <svg viewBox="0 0 64 64" role="img" aria-label="Empty box">
            <ellipse fill="url(#sc3-shadow)" cx="32" cy="53" rx="17" ry="4.5" />
            <path fill="url(#sc3-body)" d="M14 30h36v14a5 5 0 0 1-5 5H19a5 5 0 0 1-5-5Z" />
            <path fill="url(#sc3-accent)" d="M14 30 6 24v9l8 5Z" />
            <path fill="url(#sc3-accent)" d="M50 30l8-6v9l-8 5Z" />
            <path fill="url(#sc3-accent)" d="M41 8l1.9 4.3L47 14l-4.1 1.7L41 20l-1.9-4.3L35 14l4.1-1.7Z" />
          </svg>
          <h4>Your workspace is empty</h4>
          <p>Create your first Northlight project or start from a ready template — setup takes under two minutes.</p>
          <button type="button" class="sc3-btn" onclick={() => (created = true)}>Create first project</button>
          {#if created}
            <p role="status">Preparing your repository in Frankfurt… just a few seconds.</p>
          {/if}
        </div>

        <!-- Scene 3 — pricing; the monthly/annual switch recomputes every card. -->
        <div class="sc3-seg" role="radiogroup" aria-label="Billing cycle">
          <button type="button" role="radio" aria-checked={cycle === 'monthly'} onclick={() => (cycle = 'monthly')}>Monthly</button>
          <button type="button" role="radio" aria-checked={cycle === 'annual'} onclick={() => (cycle = 'annual')}>Annual</button>
        </div>
        <div class="sc3-plans">
          {#each plans as p (p.id)}
            <article class="sc3-plan" data-featured={p.featured}>
              <svg viewBox="0 0 64 64" role="img" aria-label={p.name}>{@html art[p.icon]}</svg>
              <h4>{p.name}</h4>
              <p class="sc3-price">${cycle === 'monthly' ? p.m : p.a}
                <small> per month{cycle === 'annual' ? ', billed yearly' : ''}</small>
              </p>
              <ul>
                {#each p.perks as perk (perk)}
                  <li>{perk}</li>
                {/each}
              </ul>
              <button type="button" class="sc3-btn">Choose {p.name}</button>
            </article>
          {/each}
        </div>

        <style>
        .sc3-empty { display: grid; justify-items: center; text-align: center; gap: .75rem; padding: 2.5rem 1rem; }
        .sc3-empty svg { inline-size: 6.5rem; }
        .sc3-plans { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 15rem), 1fr)); gap: 1rem; }
        .sc3-plan { display: grid; align-content: start; gap: .6rem; padding: 1.5rem; border-radius: 1.25rem;
                    border: 1px solid #e6e2ff; background: #fff; }
        .sc3-plan[data-featured='true'] { border-color: #6c4cf1; box-shadow: 0 12px 30px #6c4cf12e; }
        .sc3-plan svg { inline-size: 3.5rem; }
        .sc3-price { font-size: 1.75rem; font-weight: 700; }
        .sc3-seg { display: inline-flex; gap: .25rem; padding: .25rem; border-radius: 999px; background: #f1eeff; }
        .sc3-seg button { border: none; border-radius: 999px; padding: .4rem 1rem; cursor: pointer; }
        .sc3-seg button[aria-checked='true'] { background: linear-gradient(135deg, #c7b8ff, #6c4cf1); color: #fff; }
        .sc3-btn { justify-self: center; border: none; border-radius: 999px; padding: .65rem 1.25rem; cursor: pointer;
                   background: linear-gradient(135deg, #c7b8ff, #6c4cf1); color: #fff; }
        </style>
        SVELTE,
        ],
    ],
];
