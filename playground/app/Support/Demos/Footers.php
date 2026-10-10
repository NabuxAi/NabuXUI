<?php

/**
 * Demo manifest of the "footers" group — five footer archetypes from the
 * footer.design gallery, rebuilt as living skins: the giant wordmark that
 * fills on hover, the newsletter-driven dark footer, the floating gradient
 * CTA banner, the one-line minimal bar and the dense sitemap. Scenarios live
 * at resources/views/demos/components/footers/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'فوترها', 'en' => 'Footers'],

    'wordmark-footer' => [
        'title' => ['fa' => 'فوتر وردمارک غول', 'en' => 'Giant wordmark footer'],
        'icon' => 'command',
        'oneLiner' => [
            'fa' => 'وردمارک outline بسیار بزرگ که با هاور روی فوتر پر می‌شود و با سواچ‌ها رنگ عوض می‌کند؛ بالایش چهار ستون لینک و سطر پایانی © با دفترهای جهانی — امضای برند که صفحه را می‌بندد.',
            'en' => 'A huge outline wordmark that fills as you hover the footer and recolours from its swatches; four link columns above and a © row with global offices — the brand signature that closes the page.',
        ],
        'js' => true,
        'docs' => 'https://footer.design',
        'props' => [
            ['name' => 'fill', 'type' => 'event', 'default' => "'hover · focus-within'", 'note' => [
                'fa' => 'هاور یا فوکوس روی هر نقطهٔ فوتر، وردمارکِ توخالی را در ۴۵۰ms پر می‌کند؛ با کیبورد هم کار می‌کند.',
                'en' => 'Hovering or focusing anywhere in the footer fills the hollow wordmark over 450ms; keyboard works too.',
            ]],
            ['name' => '--wmk-accent', 'type' => 'color', 'default' => "'#4F46E5'", 'note' => [
                'fa' => 'رنگ خط و پرشدن وردمارک؛ سواچ‌های پایین همین متغیر را عوض می‌کنند (بنفش، نارنجی، سبز).',
                'en' => 'The stroke/fill colour of the wordmark; the swatches below swap this very variable (indigo, ember, fern).',
            ]],
            ['name' => 'stroke', 'type' => 'length', 'default' => "'1.5px'", 'note' => [
                'fa' => 'ضخامت خط دور متن با -webkit-text-stroke؛ رنگ متن شفاف است تا فقط خط دیده شود.',
                'en' => 'The outline weight via -webkit-text-stroke; the text colour stays transparent so only the stroke shows.',
            ]],
            ['name' => 'wordmark', 'type' => 'string', 'default' => "'Meridian'", 'note' => [
                'fa' => 'نام برند همیشه لاتین و تک‌خط می‌ماند و با clamp از ۱۶vw تا ۹rem مقیاس می‌گیرد.',
                'en' => 'The brand name stays Latin on one line, scaling from 16vw up to 9rem via clamp.',
            ]],
            ['name' => 'columns', 'type' => 'array', 'default' => "'4'", 'note' => [
                'fa' => 'محصول، شرکت، منابع و قانونی؛ در عرض ۵۶۰px به دو ستون می‌ریزد.',
                'en' => 'Product, Company, Resources, Legal; collapses to two columns under 560px.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <footer class="wmk-foot" x-data="{ accent: '#4F46E5' }" :style="'--wmk-accent:' + accent">
            <nav class="wmk-cols" aria-label="فوتر سایت">
                <div><h4>محصول</h4><a href="#">فیچر فلگ‌ها</a><a href="#">مشاهده‌پذیری</a><a href="#">یکپارچه‌سازی‌ها</a></div>
                <div><h4>شرکت</h4><a href="#">دربارهٔ ما</a><a href="#">فرصت‌های شغلی</a><a href="#">مشتریان</a></div>
                <div><h4>منابع</h4><a href="#">مستندات</a><a href="#">مرجع API</a><a href="#">وضعیت سرویس</a></div>
                <div><h4>قانونی</h4><a href="#">حریم خصوصی</a><a href="#">شرایط</a><a href="#">امنیت</a></div>
            </nav>
            <p class="wmk-mark" dir="ltr" aria-hidden="true">Meridian</p>
            <div class="wmk-base">
                <small>© ۲۰۲۶ مریدین — فرانکفورت · دوبلین · سنگاپور</small>
                <div class="wmk-swatches">
                    <button type="button" aria-label="بنفش" x-on:click="accent = '#4F46E5'"></button>
                    <button type="button" aria-label="نارنجی" x-on:click="accent = '#EA580C'"></button>
                    <button type="button" aria-label="سبز" x-on:click="accent = '#059669'"></button>
                </div>
            </div>
        </footer>

        <style>
        .wmk-foot { padding: 3rem 1.5rem 1.25rem; background: #FAFAF9; font-family: 'Inter', 'Vazirmatn', sans-serif; }
        .wmk-cols { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; }
        .wmk-cols h4 { margin: 0 0 .75rem; font-size: .75rem; color: #78716C; }
        .wmk-cols a { display: block; padding-block: .2rem; color: #1C1917; text-decoration: none; }
        .wmk-mark { margin: 2.5rem 0 0; text-align: center; white-space: nowrap;
                    font: 800 clamp(2.75rem, 16vw, 9rem)/1 'Inter', sans-serif; letter-spacing: -.04em;
                    color: transparent; -webkit-text-stroke: 1.5px var(--wmk-accent); transition: color .45s ease; }
        .wmk-foot:is(:hover, :focus-within) .wmk-mark { color: var(--wmk-accent); }
        .wmk-base { display: flex; flex-wrap: wrap; gap: 1rem; align-items: center; justify-content: space-between;
                    margin-block-start: 1.5rem; padding-block-start: 1rem; border-block-start: 1px solid #E7E5E4; }
        .wmk-swatches { display: flex; gap: .5rem; }
        .wmk-swatches button { inline-size: 1.25rem; aspect-ratio: 1; border-radius: 50%;
                               border: 2px solid #fff; outline: 1px solid #E7E5E4; cursor: pointer; }
        @media (max-width: 560px) { .wmk-cols { grid-template-columns: repeat(2, 1fr); } }
        @media (prefers-reduced-motion: reduce) { .wmk-mark { transition-duration: .01ms; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <footer class="wmk-foot" x-data="{ accent: '#4F46E5' }" :style="'--wmk-accent:' + accent">
        <nav class="wmk-cols" aria-label="Site footer">
        <div><h4>Product</h4><a href="#">Feature flags</a><a href="#">Observability</a><a href="#">Integrations</a></div>
        <div><h4>Company</h4><a href="#">About</a><a href="#">Careers</a><a href="#">Customers</a></div>
        <div><h4>Resources</h4><a href="#">Docs</a><a href="#">API reference</a><a href="#">Status</a></div>
        <div><h4>Legal</h4><a href="#">Privacy</a><a href="#">Terms</a><a href="#">Security</a></div>
        </nav>
        <p class="wmk-mark" dir="ltr" aria-hidden="true">Meridian</p>
        <div class="wmk-base">
        <small>© 2026 Meridian — Frankfurt · Dublin · Singapore</small>
        <div class="wmk-swatches">
        <button type="button" aria-label="Indigo" x-on:click="accent = '#4F46E5'"></button>
        <button type="button" aria-label="Ember" x-on:click="accent = '#EA580C'"></button>
        <button type="button" aria-label="Fern" x-on:click="accent = '#059669'"></button>
        </div>
        </div>
        </footer>

        <style>
        .wmk-foot { padding: 3rem 1.5rem 1.25rem; background: #FAFAF9; font-family: 'Inter', 'Vazirmatn', sans-serif; }
        .wmk-cols { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; }
        .wmk-cols h4 { margin: 0 0 .75rem; font-size: .75rem; color: #78716C; }
        .wmk-cols a { display: block; padding-block: .2rem; color: #1C1917; text-decoration: none; }
        .wmk-mark { margin: 2.5rem 0 0; text-align: center; white-space: nowrap;
        font: 800 clamp(2.75rem, 16vw, 9rem)/1 'Inter', sans-serif; letter-spacing: -.04em;
        color: transparent; -webkit-text-stroke: 1.5px var(--wmk-accent); transition: color .45s ease; }
        .wmk-foot:is(:hover, :focus-within) .wmk-mark { color: var(--wmk-accent); }
        .wmk-base { display: flex; flex-wrap: wrap; gap: 1rem; align-items: center; justify-content: space-between;
        margin-block-start: 1.5rem; padding-block-start: 1rem; border-block-start: 1px solid #E7E5E4; }
        .wmk-swatches { display: flex; gap: .5rem; }
        .wmk-swatches button { inline-size: 1.25rem; aspect-ratio: 1; border-radius: 50%;
        border: 2px solid #fff; outline: 1px solid #E7E5E4; cursor: pointer; }
        @media (max-width: 560px) { .wmk-cols { grid-template-columns: repeat(2, 1fr); } }
        @media (prefers-reduced-motion: reduce) { .wmk-mark { transition-duration: .01ms; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Home.jsx — Inertia (React) page; renders the React snippet
        // below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import WordmarkFooter from '@/components/WordmarkFooter';

        export default function Home() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <WordmarkFooter />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // WordmarkFooter.jsx — the giant outline wordmark fills on hover
        import { useState } from 'react';
        import '@nabuxai/ui-core/css';

        const COLUMNS = [
          { head: 'Product', links: ['Feature flags', 'Observability', 'Integrations'] },
          { head: 'Company', links: ['About', 'Careers', 'Customers'] },
          { head: 'Resources', links: ['Docs', 'API reference', 'Status'] },
          { head: 'Legal', links: ['Privacy', 'Terms', 'Security'] },
        ];
        const ACCENTS = ['#4F46E5', '#EA580C', '#059669'];

        export default function WordmarkFooter() {
          const [accent, setAccent] = useState(ACCENTS[0]);

          return (
            <>
              <style>{`
                .wmk-foot { padding: 3rem 1.5rem 1.25rem; background: #FAFAF9; font-family: 'Inter', sans-serif; }
                .wmk-cols { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; }
                .wmk-cols h4 { margin: 0 0 .75rem; font-size: .75rem; color: #78716C; }
                .wmk-cols a { display: block; padding-block: .2rem; color: #1C1917; text-decoration: none; }
                .wmk-mark { margin: 2.5rem 0 0; text-align: center; white-space: nowrap;
                            font: 800 clamp(2.75rem, 16vw, 9rem)/1 'Inter', sans-serif; letter-spacing: -.04em;
                            color: transparent; -webkit-text-stroke: 1.5px var(--wmk-accent); transition: color .45s ease; }
                .wmk-foot:is(:hover, :focus-within) .wmk-mark { color: var(--wmk-accent); }
                .wmk-base { display: flex; flex-wrap: wrap; gap: 1rem; align-items: center; justify-content: space-between;
                            margin-block-start: 1.5rem; padding-block-start: 1rem; border-block-start: 1px solid #E7E5E4; }
                .wmk-swatches { display: flex; gap: .5rem; }
                .wmk-swatches button { inline-size: 1.25rem; aspect-ratio: 1; border-radius: 50%;
                            border: 2px solid #fff; outline: 1px solid #E7E5E4; cursor: pointer; }
                @media (max-width: 560px) { .wmk-cols { grid-template-columns: repeat(2, 1fr); } }
                @media (prefers-reduced-motion: reduce) { .wmk-mark { transition-duration: .01ms; } }
              `}</style>
              <footer className="wmk-foot" style={{ '--wmk-accent': accent }}>
                <nav className="wmk-cols" aria-label="Site footer">
                  {COLUMNS.map((c) => (
                    <div key={c.head}>
                      <h4>{c.head}</h4>
                      {c.links.map((l) => <a key={l} href="#">{l}</a>)}
                    </div>
                  ))}
                </nav>
                {/* Hover anywhere (or focus a link) and the hollow mark floods with the accent. */}
                <p className="wmk-mark" dir="ltr" aria-hidden="true">Meridian</p>
                <div className="wmk-base">
                  <small>© 2026 Meridian — Frankfurt · Dublin · Singapore</small>
                  <div className="wmk-swatches">
                    {ACCENTS.map((a) => (
                      <button key={a} type="button" style={{ background: a }}
                              aria-label={`Accent ${a}`} onClick={() => setAccent(a)} />
                    ))}
                  </div>
                </div>
              </footer>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- WordmarkFooter.vue — the giant outline wordmark fills on hover -->
        <script setup lang="ts">
        import { ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const columns = [
          { head: 'Product', links: ['Feature flags', 'Observability', 'Integrations'] },
          { head: 'Company', links: ['About', 'Careers', 'Customers'] },
          { head: 'Resources', links: ['Docs', 'API reference', 'Status'] },
          { head: 'Legal', links: ['Privacy', 'Terms', 'Security'] },
        ];
        const accents = ['#4F46E5', '#EA580C', '#059669'];
        const accent = ref<string>(accents[0]);
        </script>

        <template>
          <footer class="wmk-foot" :style="{ '--wmk-accent': accent }">
            <nav class="wmk-cols" aria-label="Site footer">
              <div v-for="c in columns" :key="c.head">
                <h4>{{ c.head }}</h4>
                <a v-for="l in c.links" :key="l" href="#">{{ l }}</a>
              </div>
            </nav>
            <!-- Hover anywhere (or focus a link) and the hollow mark floods with the accent. -->
            <p class="wmk-mark" dir="ltr" aria-hidden="true">Meridian</p>
            <div class="wmk-base">
              <small>© 2026 Meridian — Frankfurt · Dublin · Singapore</small>
              <div class="wmk-swatches">
                <button v-for="a in accents" :key="a" type="button" :style="{ background: a }"
                        :aria-label="`Accent ${a}`" @click="accent = a"></button>
              </div>
            </div>
          </footer>
        </template>

        <style scoped>
        .wmk-foot { padding: 3rem 1.5rem 1.25rem; background: #FAFAF9; font-family: 'Inter', sans-serif; }
        .wmk-cols { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; }
        .wmk-cols h4 { margin: 0 0 .75rem; font-size: .75rem; color: #78716C; }
        .wmk-cols a { display: block; padding-block: .2rem; color: #1C1917; text-decoration: none; }
        .wmk-mark { margin: 2.5rem 0 0; text-align: center; white-space: nowrap;
                    font: 800 clamp(2.75rem, 16vw, 9rem)/1 'Inter', sans-serif; letter-spacing: -.04em;
                    color: transparent; -webkit-text-stroke: 1.5px var(--wmk-accent); transition: color .45s ease; }
        .wmk-foot:is(:hover, :focus-within) .wmk-mark { color: var(--wmk-accent); }
        .wmk-base { display: flex; flex-wrap: wrap; gap: 1rem; align-items: center; justify-content: space-between;
                    margin-block-start: 1.5rem; padding-block-start: 1rem; border-block-start: 1px solid #E7E5E4; }
        .wmk-swatches { display: flex; gap: .5rem; }
        .wmk-swatches button { inline-size: 1.25rem; aspect-ratio: 1; border-radius: 50%;
                    border: 2px solid #fff; outline: 1px solid #E7E5E4; cursor: pointer; }
        @media (max-width: 560px) { .wmk-cols { grid-template-columns: repeat(2, 1fr); } }
        @media (prefers-reduced-motion: reduce) { .wmk-mark { transition-duration: .01ms; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- WordmarkFooter.svelte — the giant outline wordmark fills on hover -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const columns = [
            { head: 'Product', links: ['Feature flags', 'Observability', 'Integrations'] },
            { head: 'Company', links: ['About', 'Careers', 'Customers'] },
            { head: 'Resources', links: ['Docs', 'API reference', 'Status'] },
            { head: 'Legal', links: ['Privacy', 'Terms', 'Security'] },
          ];
          const accents = ['#4F46E5', '#EA580C', '#059669'];
          let accent = $state(accents[0]);
        </script>

        <footer class="wmk-foot" style="--wmk-accent: {accent}">
          <nav class="wmk-cols" aria-label="Site footer">
            {#each columns as c (c.head)}
              <div>
                <h4>{c.head}</h4>
                {#each c.links as l (l)}
                  <a href="#">{l}</a>
                {/each}
              </div>
            {/each}
          </nav>
          <!-- Hover anywhere (or focus a link) and the hollow mark floods with the accent. -->
          <p class="wmk-mark" dir="ltr" aria-hidden="true">Meridian</p>
          <div class="wmk-base">
            <small>© 2026 Meridian — Frankfurt · Dublin · Singapore</small>
            <div class="wmk-swatches">
              {#each accents as a (a)}
                <button type="button" style="background: {a}" aria-label="Accent {a}"
                        onclick={() => (accent = a)}></button>
              {/each}
            </div>
          </div>
        </footer>

        <style>
        .wmk-foot { padding: 3rem 1.5rem 1.25rem; background: #FAFAF9; font-family: 'Inter', sans-serif; }
        .wmk-cols { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; }
        .wmk-cols h4 { margin: 0 0 .75rem; font-size: .75rem; color: #78716C; }
        .wmk-cols a { display: block; padding-block: .2rem; color: #1C1917; text-decoration: none; }
        .wmk-mark { margin: 2.5rem 0 0; text-align: center; white-space: nowrap;
                    font: 800 clamp(2.75rem, 16vw, 9rem)/1 'Inter', sans-serif; letter-spacing: -.04em;
                    color: transparent; -webkit-text-stroke: 1.5px var(--wmk-accent); transition: color .45s ease; }
        .wmk-foot:is(:hover, :focus-within) .wmk-mark { color: var(--wmk-accent); }
        .wmk-base { display: flex; flex-wrap: wrap; gap: 1rem; align-items: center; justify-content: space-between;
                    margin-block-start: 1.5rem; padding-block-start: 1rem; border-block-start: 1px solid #E7E5E4; }
        .wmk-swatches { display: flex; gap: .5rem; }
        .wmk-swatches button { inline-size: 1.25rem; aspect-ratio: 1; border-radius: 50%;
                    border: 2px solid #fff; outline: 1px solid #E7E5E4; cursor: pointer; }
        @media (max-width: 560px) { .wmk-cols { grid-template-columns: repeat(2, 1fr); } }
        @media (prefers-reduced-motion: reduce) { .wmk-mark { transition-duration: .01ms; } }
        </style>
        SVELTE,
        ],
    ],

    'newsletter-footer' => [
        'title' => ['fa' => 'فوتر خبرنامه', 'en' => 'Newsletter footer'],
        'icon' => 'mail',
        'oneLiner' => [
            'fa' => 'فوتر خبرنامه‌محور روی زمینهٔ تیره با بافت نقطه‌ای: تیتر درشت، فرم ایمیل با اعتبارسنجی زنده و پیام موفقیت که جای فرم را می‌گیرد؛ زیرش ستون‌های کم‌لینک و ردیف سوشال.',
            'en' => 'A newsletter-first footer on a dotted dark ground: a big headline, an email form with live validation and a success note that takes the form’s place — a few sparse columns and the social row below.',
        ],
        'js' => true,
        'docs' => 'https://footer.design',
        'props' => [
            ['name' => 'validate', 'type' => 'rule', 'default' => "'/^[^@\\s]+@[^@\\s]+\\.[a-z]{2,}$/i'", 'note' => [
                'fa' => 'اعتبارسنجی درجا هنگام ثبت؛ خطا زیر فیلد می‌آید و فرم جای خود را به پیام موفقیت می‌دهد.',
                'en' => 'Validates in place on submit; the error lands under the field and the form yields to the success note.',
            ]],
            ['name' => '--nlf-dot', 'type' => 'color', 'default' => "'rgba(255,255,255,.13)'", 'note' => [
                'fa' => 'رنگ نقاط بافت؛ گرید ۲۲px با radial-gradient تکرارشونده روی زمینهٔ #0B1120.',
                'en' => 'The texture dot colour; a repeating 22px radial-gradient grid over the #0B1120 ground.',
            ]],
            ['name' => 'heading', 'type' => 'string', 'default' => "'Ship notes, monthly.'", 'note' => [
                'fa' => 'تیتر درشت با clamp از ۱٫۷۵rem تا ۳rem — قهرمان این فوتر فرم است، نه لینک‌ها.',
                'en' => 'The big heading clamps from 1.75rem to 3rem — the form is this footer’s hero, not the links.',
            ]],
            ['name' => 'columns', 'type' => 'array', 'default' => "'2'", 'note' => [
                'fa' => 'فقط دو ستون کم‌لینک (محصول، شرکت) تا توجه از فرم تک‌نگاهی نپرد.',
                'en' => 'Just two sparse columns (Product, Company) so nothing steals the form’s spotlight.',
            ]],
            ['name' => 'success', 'type' => 'slot', 'default' => "''", 'note' => [
                'fa' => 'پیام موفقیت با آیکون تیک؛ متن پیش‌فرض روزِ رسیدن شمارهٔ بعدی را می‌گوید.',
                'en' => 'The success note with a check icon; the default copy says when the next issue lands.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <footer class="nlf-foot" x-data="{ email: '', error: '', done: false,
                  submit() { this.error = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(this.email) ? '' : 'ایمیل معتبر وارد کنید';
                             if (!this.error) this.done = true } }">
            <h3 class="nlf-title">یادداشت‌های انتشار، ماهانه.</h3>
            <form class="nlf-form" x-show="!done" x-on:submit.prevent="submit()">
                <input type="email" x-model="email" placeholder="you@studio.com" aria-label="نشانی ایمیل">
                <button type="submit">عضویت</button>
                <p class="nlf-error" x-show="error" x-text="error"></p>
            </form>
            <p class="nlf-done" x-show="done" x-cloak>✓ ثبت شد — شمارهٔ بعدی سه‌شنبه می‌رسد.</p>
            <nav class="nlf-cols" aria-label="فوتر">
                <div><h4>محصول</h4><a href="#">فیچر فلگ‌ها</a><a href="#">قیمت‌گذاری</a></div>
                <div><h4>شرکت</h4><a href="#">دربارهٔ ما</a><a href="#">فرصت‌های شغلی</a></div>
            </nav>
        </footer>

        <style>
        .nlf-foot { padding: 4rem 1.5rem 2rem; color: #E2E8F0; background-color: #0B1120;
                    background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,.13) 1px, transparent 1.5px);
                    background-size: 22px 22px; }
        .nlf-title { margin: 0; font: 700 clamp(1.75rem, 4vw, 3rem)/1.15 'Inter', 'Vazirmatn', sans-serif; }
        .nlf-form { display: flex; flex-wrap: wrap; gap: .5rem; margin-block-start: 1.25rem; }
        .nlf-form input { flex: 1 1 14rem; block-size: 3rem; padding-inline: 1rem; color: #fff;
                          background: #131C31; border: 1px solid #26334D; border-radius: .75rem; }
        .nlf-form button { block-size: 3rem; padding-inline: 1.5rem; background: #38BDF8; color: #04121F;
                           font-weight: 700; border: none; border-radius: .75rem; cursor: pointer; }
        .nlf-error { flex-basis: 100%; margin: 0; font-size: .8rem; color: #FCA5A5; }
        .nlf-done { margin: 1.25rem 0 0; color: #6EE7B7; }
        .nlf-cols { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.5rem;
                    margin-block-start: 3rem; padding-block-start: 1.25rem; border-block-start: 1px solid #26334D; }
        .nlf-cols h4 { margin: 0 0 .5rem; font-size: .75rem; color: #7C8DB0; }
        .nlf-cols a { display: block; padding-block: .2rem; color: #B6C2D9; text-decoration: none; }
        .nlf-cols a:hover { color: #fff; }
        [x-cloak] { display: none; }
        @media (prefers-reduced-motion: reduce) { .nlf-foot * { transition: none; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <footer class="nlf-foot" x-data="{ email: '', error: '', done: false,
                  submit() { this.error = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(this.email) ? '' : 'Enter a valid email';
                             if (!this.error) this.done = true } }">
        <h3 class="nlf-title">Ship notes, monthly.</h3>
        <form class="nlf-form" x-show="!done" x-on:submit.prevent="submit()">
        <input type="email" x-model="email" placeholder="you@studio.com" aria-label="Email address">
        <button type="submit">Subscribe</button>
        <p class="nlf-error" x-show="error" x-text="error"></p>
        </form>
        <p class="nlf-done" x-show="done" x-cloak>✓ You’re on the list — next issue lands Tuesday.</p>
        <nav class="nlf-cols" aria-label="Footer">
        <div><h4>Product</h4><a href="#">Feature flags</a><a href="#">Pricing</a></div>
        <div><h4>Company</h4><a href="#">About</a><a href="#">Careers</a></div>
        </nav>
        </footer>

        <style>
        .nlf-foot { padding: 4rem 1.5rem 2rem; color: #E2E8F0; background-color: #0B1120;
        background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,.13) 1px, transparent 1.5px);
        background-size: 22px 22px; }
        .nlf-title { margin: 0; font: 700 clamp(1.75rem, 4vw, 3rem)/1.15 'Inter', sans-serif; }
        .nlf-form { display: flex; flex-wrap: wrap; gap: .5rem; margin-block-start: 1.25rem; }
        .nlf-form input { flex: 1 1 14rem; block-size: 3rem; padding-inline: 1rem; color: #fff;
        background: #131C31; border: 1px solid #26334D; border-radius: .75rem; }
        .nlf-form button { block-size: 3rem; padding-inline: 1.5rem; background: #38BDF8; color: #04121F;
        font-weight: 700; border: none; border-radius: .75rem; cursor: pointer; }
        .nlf-error { flex-basis: 100%; margin: 0; font-size: .8rem; color: #FCA5A5; }
        .nlf-done { margin: 1.25rem 0 0; color: #6EE7B7; }
        .nlf-cols { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.5rem;
        margin-block-start: 3rem; padding-block-start: 1.25rem; border-block-start: 1px solid #26334D; }
        .nlf-cols h4 { margin: 0 0 .5rem; font-size: .75rem; color: #7C8DB0; }
        .nlf-cols a { display: block; padding-block: .2rem; color: #B6C2D9; text-decoration: none; }
        .nlf-cols a:hover { color: #fff; }
        [x-cloak] { display: none; }
        @media (prefers-reduced-motion: reduce) { .nlf-foot * { transition: none; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Home.jsx — Inertia (React) page; renders the React snippet
        // below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import NewsletterFooter from '@/components/NewsletterFooter';

        export default function Home() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <NewsletterFooter />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // NewsletterFooter.jsx — dark dotted footer, validated signup form
        import { useState } from 'react';
        import '@nabuxai/ui-core/css';

        const EMAIL = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

        export default function NewsletterFooter() {
          const [email, setEmail] = useState('');
          const [error, setError] = useState('');
          const [done, setDone] = useState(false);

          const submit = (e) => {
            e.preventDefault();
            if (!EMAIL.test(email)) { setError('Enter a valid email'); return; }
            setError(''); setDone(true);
          };

          return (
            <>
              <style>{`
                .nlf-foot { padding: 4rem 1.5rem 2rem; color: #E2E8F0; background-color: #0B1120;
                            background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,.13) 1px, transparent 1.5px);
                            background-size: 22px 22px; }
                .nlf-title { margin: 0; font: 700 clamp(1.75rem, 4vw, 3rem)/1.15 'Inter', sans-serif; }
                .nlf-form { display: flex; flex-wrap: wrap; gap: .5rem; margin-block-start: 1.25rem; }
                .nlf-form input { flex: 1 1 14rem; block-size: 3rem; padding-inline: 1rem; color: #fff;
                            background: #131C31; border: 1px solid #26334D; border-radius: .75rem; }
                .nlf-form button { block-size: 3rem; padding-inline: 1.5rem; background: #38BDF8; color: #04121F;
                            font-weight: 700; border: none; border-radius: .75rem; cursor: pointer; }
                .nlf-error { flex-basis: 100%; margin: 0; font-size: .8rem; color: #FCA5A5; }
                .nlf-done { margin: 1.25rem 0 0; color: #6EE7B7; }
                .nlf-cols { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.5rem;
                            margin-block-start: 3rem; padding-block-start: 1.25rem; border-block-start: 1px solid #26334D; }
                .nlf-cols h4 { margin: 0 0 .5rem; font-size: .75rem; color: #7C8DB0; }
                .nlf-cols a { display: block; padding-block: .2rem; color: #B6C2D9; text-decoration: none; }
                .nlf-cols a:hover { color: #fff; }
              `}</style>
              <footer className="nlf-foot">
                <h3 className="nlf-title">Ship notes, monthly.</h3>
                {done ? (
                  // The form yields to a single success line with a check.
                  <p className="nlf-done">✓ You’re on the list — next issue lands Tuesday.</p>
                ) : (
                  <form className="nlf-form" onSubmit={submit} noValidate>
                    <input type="email" value={email} onChange={(e) => setEmail(e.target.value)}
                           placeholder="you@studio.com" aria-label="Email address" />
                    <button type="submit">Subscribe</button>
                    {error && <p className="nlf-error" role="alert">{error}</p>}
                  </form>
                )}
                <nav className="nlf-cols" aria-label="Footer">
                  <div><h4>Product</h4><a href="#">Feature flags</a><a href="#">Pricing</a></div>
                  <div><h4>Company</h4><a href="#">About</a><a href="#">Careers</a></div>
                </nav>
              </footer>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- NewsletterFooter.vue — dark dotted footer, validated signup form -->
        <script setup lang="ts">
        import { ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const EMAIL = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
        const email = ref('');
        const error = ref('');
        const done = ref(false);

        function submit() {
          error.value = EMAIL.test(email.value) ? '' : 'Enter a valid email';
          if (!error.value) done.value = true;
        }
        </script>

        <template>
          <footer class="nlf-foot">
            <h3 class="nlf-title">Ship notes, monthly.</h3>
            <!-- The form yields to a single success line with a check. -->
            <form v-if="!done" class="nlf-form" @submit.prevent="submit" novalidate>
              <input v-model="email" type="email" placeholder="you@studio.com" aria-label="Email address" />
              <button type="submit">Subscribe</button>
              <p v-if="error" class="nlf-error" role="alert">{{ error }}</p>
            </form>
            <p v-else class="nlf-done">✓ You’re on the list — next issue lands Tuesday.</p>
            <nav class="nlf-cols" aria-label="Footer">
              <div><h4>Product</h4><a href="#">Feature flags</a><a href="#">Pricing</a></div>
              <div><h4>Company</h4><a href="#">About</a><a href="#">Careers</a></div>
            </nav>
          </footer>
        </template>

        <style scoped>
        .nlf-foot { padding: 4rem 1.5rem 2rem; color: #E2E8F0; background-color: #0B1120;
                    background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,.13) 1px, transparent 1.5px);
                    background-size: 22px 22px; }
        .nlf-title { margin: 0; font: 700 clamp(1.75rem, 4vw, 3rem)/1.15 'Inter', sans-serif; }
        .nlf-form { display: flex; flex-wrap: wrap; gap: .5rem; margin-block-start: 1.25rem; }
        .nlf-form input { flex: 1 1 14rem; block-size: 3rem; padding-inline: 1rem; color: #fff;
                    background: #131C31; border: 1px solid #26334D; border-radius: .75rem; }
        .nlf-form button { block-size: 3rem; padding-inline: 1.5rem; background: #38BDF8; color: #04121F;
                    font-weight: 700; border: none; border-radius: .75rem; cursor: pointer; }
        .nlf-error { flex-basis: 100%; margin: 0; font-size: .8rem; color: #FCA5A5; }
        .nlf-done { margin: 1.25rem 0 0; color: #6EE7B7; }
        .nlf-cols { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.5rem;
                    margin-block-start: 3rem; padding-block-start: 1.25rem; border-block-start: 1px solid #26334D; }
        .nlf-cols h4 { margin: 0 0 .5rem; font-size: .75rem; color: #7C8DB0; }
        .nlf-cols a { display: block; padding-block: .2rem; color: #B6C2D9; text-decoration: none; }
        .nlf-cols a:hover { color: #fff; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- NewsletterFooter.svelte — dark dotted footer, validated signup form -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const EMAIL = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
          let email = $state('');
          let error = $state('');
          let done = $state(false);

          function submit() {
            error = EMAIL.test(email) ? '' : 'Enter a valid email';
            if (!error) done = true;
          }
        </script>

        <footer class="nlf-foot">
          <h3 class="nlf-title">Ship notes, monthly.</h3>
          <!-- The form yields to a single success line with a check. -->
          {#if !done}
            <form class="nlf-form" onsubmit={submit} novalidate>
              <input bind:value={email} type="email" placeholder="you@studio.com" aria-label="Email address" />
              <button type="submit">Subscribe</button>
              {#if error}<p class="nlf-error" role="alert">{error}</p>{/if}
            </form>
          {:else}
            <p class="nlf-done">✓ You’re on the list — next issue lands Tuesday.</p>
          {/if}
          <nav class="nlf-cols" aria-label="Footer">
            <div><h4>Product</h4><a href="#">Feature flags</a><a href="#">Pricing</a></div>
            <div><h4>Company</h4><a href="#">About</a><a href="#">Careers</a></div>
          </nav>
        </footer>

        <style>
        .nlf-foot { padding: 4rem 1.5rem 2rem; color: #E2E8F0; background-color: #0B1120;
                    background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,.13) 1px, transparent 1.5px);
                    background-size: 22px 22px; }
        .nlf-title { margin: 0; font: 700 clamp(1.75rem, 4vw, 3rem)/1.15 'Inter', sans-serif; }
        .nlf-form { display: flex; flex-wrap: wrap; gap: .5rem; margin-block-start: 1.25rem; }
        .nlf-form input { flex: 1 1 14rem; block-size: 3rem; padding-inline: 1rem; color: #fff;
                    background: #131C31; border: 1px solid #26334D; border-radius: .75rem; }
        .nlf-form button { block-size: 3rem; padding-inline: 1.5rem; background: #38BDF8; color: #04121F;
                    font-weight: 700; border: none; border-radius: .75rem; cursor: pointer; }
        .nlf-error { flex-basis: 100%; margin: 0; font-size: .8rem; color: #FCA5A5; }
        .nlf-done { margin: 1.25rem 0 0; color: #6EE7B7; }
        .nlf-cols { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1.5rem;
                    margin-block-start: 3rem; padding-block-start: 1.25rem; border-block-start: 1px solid #26334D; }
        .nlf-cols h4 { margin: 0 0 .5rem; font-size: .75rem; color: #7C8DB0; }
        .nlf-cols a { display: block; padding-block: .2rem; color: #B6C2D9; text-decoration: none; }
        .nlf-cols a:hover { color: #fff; }
        </style>
        SVELTE,
        ],
    ],

    'cta-footer' => [
        'title' => ['fa' => 'فوتر با بنر CTA', 'en' => 'Footer with CTA banner'],
        'icon' => 'zap',
        'oneLiner' => [
            'fa' => 'داخل فوتر یک کارت CTA گرادیانی معلق (تیتر + دو دکمه + بج آزمایش) که با اسکرول و رسیدن به دید ظاهر می‌شود و با دکمهٔ بازپخش دوباره اجرا می‌شود؛ زیرش لینک‌ها و سطر پایانی.',
            'en' => 'A gradient CTA card floats over the footer’s edge (title, two buttons, trial badge), rising in as you scroll it into view — with a replay control — above the link rows and the closing line.',
        ],
        'js' => true,
        'docs' => 'https://footer.design',
        'props' => [
            ['name' => 'reveal', 'type' => 'event', 'default' => "'intersect · threshold .35'", 'note' => [
                'fa' => 'با IntersectionObserver وقتی کارت به دید رسید ظاهر می‌شود؛ یک‌بار اجرا می‌شود و دکمهٔ بازپخش دوباره می‌پرد.',
                'en' => 'An IntersectionObserver raises the card when it enters view; it runs once and the replay button resets it.',
            ]],
            ['name' => '--ctf-grad', 'type' => 'gradient', 'default' => "'135deg · #6366F1 → #8B5CF6 → #EC4899'", 'note' => [
                'fa' => 'گرادیان کارت؛ متن سفید روی کم‌کنتراست‌ترین نقطه هم ≥ ۴٫۵ است و سایهٔ رنگ‌گرفته از بنفش می‌گیرد.',
                'en' => 'The card gradient; white text stays ≥ 4.5 even on the faintest stop, with a violet-tinted shadow.',
            ]],
            ['name' => 'overlap', 'type' => 'length', 'default' => "'-3.5rem'", 'note' => [
                'fa' => 'مارجین منفی که کارت را روی لبهٔ بالای فوتر می‌نشاند؛ حس «معلق بودن» از همین‌جا می‌آید.',
                'en' => 'The negative margin seating the card over the footer’s top edge; the “floating” feel lives here.',
            ]],
            ['name' => 'badge', 'type' => 'string', 'default' => "'14-day trial · no card'", 'note' => [
                'fa' => 'بج شیشه‌ای روی کارت؛ وعدهٔ محصولی که تردید خریدار را قبل از کلیک برمی‌دارد.',
                'en' => 'The glassy badge riding the card; the product promise that kills hesitation before the click.',
            ]],
            ['name' => 'reducedMotion', 'type' => 'media', 'default' => "'instant'", 'note' => [
                'fa' => 'با prefers-reduced-motion ظاهرشدن فوری می‌شود — کارت هیچ‌وقت پنهان نمی‌ماند.',
                'en' => 'Under prefers-reduced-motion the rise is instant — the card never stays hidden.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <footer class="ctf-foot">
            <div class="ctf-card" x-data="{ seen: false }"
                 x-init="obs = new IntersectionObserver(es => es.forEach(e => { if (e.isIntersecting) seen = true }),
                                                        { threshold: .35 }); obs.observe($el)"
                 x-bind:data-seen="seen">
                <span class="ctf-badge">۱۴ روز آزمایش · بدون کارت</span>
                <h3>امروز شروع به انتشار آرام کن</h3>
                <div class="ctf-actions">
                    <a class="ctf-btn" href="#">شروع رایگان</a>
                    <a class="ctf-btn ctf-ghost" href="#">گفت‌وگو با فروش</a>
                </div>
            </div>
            <nav class="ctf-links" aria-label="فوتر">
                <a href="#">فیچر فلگ‌ها</a><a href="#">قیمت‌گذاری</a><a href="#">مستندات</a><a href="#">وضعیت</a>
            </nav>
            <p class="ctf-base">© ۲۰۲۶ مریدین — فرانکفورت · دوبلین · سنگاپور</p>
        </footer>

        <style>
        .ctf-foot { padding: 5.5rem 1.5rem 1.5rem; background: #FAFAF9; }
        .ctf-card { inline-size: min(100%, 44rem); margin-block-start: -3.5rem; margin-inline: auto; text-align: center;
                    padding: 2.5rem 1.5rem; color: #fff; border-radius: 1.25rem;
                    background: linear-gradient(135deg, #6366F1, #8B5CF6 45%, #EC4899);
                    box-shadow: 0 24px 48px -16px rgba(139, 92, 246, .55);
                    opacity: 0; translate: 0 1.5rem; transition: opacity .6s ease, translate .6s ease; }
        .ctf-card[data-seen='true'] { opacity: 1; translate: 0 0; }
        .ctf-badge { font-size: .72rem; font-weight: 600; padding: .3rem .8rem; border-radius: 999px;
                     background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.35); }
        .ctf-card h3 { margin: 1rem 0 1.25rem; font: 800 clamp(1.5rem, 3.5vw, 2.25rem)/1.2 'Inter', 'Vazirmatn', sans-serif; }
        .ctf-actions { display: flex; flex-wrap: wrap; gap: .75rem; justify-content: center; }
        .ctf-btn { block-size: 2.75rem; display: grid; place-items: center; padding-inline: 1.5rem;
                   border-radius: .8rem; background: #fff; color: #4F46E5; font-weight: 700; text-decoration: none; }
        .ctf-btn.ctf-ghost { background: transparent; color: #fff; border: 1px solid rgba(255,255,255,.55); }
        .ctf-links { display: flex; flex-wrap: wrap; gap: .5rem 1.5rem; justify-content: center;
                     margin-block-start: 2.5rem; }
        .ctf-links a { color: #57534E; font-size: .875rem; text-decoration: none; }
        .ctf-links a:hover { color: #4F46E5; text-decoration: underline; }
        .ctf-base { margin: 1.25rem 0 0; text-align: center; font-size: .75rem; color: #78716C; }
        @media (prefers-reduced-motion: reduce) { .ctf-card { transition-duration: .01ms; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <footer class="ctf-foot">
        <div class="ctf-card" x-data="{ seen: false }"
             x-init="obs = new IntersectionObserver(es => es.forEach(e => { if (e.isIntersecting) seen = true }),
                                                    { threshold: .35 }); obs.observe($el)"
             x-bind:data-seen="seen">
        <span class="ctf-badge">14-day trial · no card</span>
        <h3>Start shipping calmly, today</h3>
        <div class="ctf-actions">
        <a class="ctf-btn" href="#">Start free</a>
        <a class="ctf-btn ctf-ghost" href="#">Talk to sales</a>
        </div>
        </div>
        <nav class="ctf-links" aria-label="Footer">
        <a href="#">Feature flags</a><a href="#">Pricing</a><a href="#">Docs</a><a href="#">Status</a>
        </nav>
        <p class="ctf-base">© 2026 Meridian — Frankfurt · Dublin · Singapore</p>
        </footer>

        <style>
        .ctf-foot { padding: 5.5rem 1.5rem 1.5rem; background: #FAFAF9; }
        .ctf-card { inline-size: min(100%, 44rem); margin-block-start: -3.5rem; margin-inline: auto; text-align: center;
        padding: 2.5rem 1.5rem; color: #fff; border-radius: 1.25rem;
        background: linear-gradient(135deg, #6366F1, #8B5CF6 45%, #EC4899);
        box-shadow: 0 24px 48px -16px rgba(139, 92, 246, .55);
        opacity: 0; translate: 0 1.5rem; transition: opacity .6s ease, translate .6s ease; }
        .ctf-card[data-seen='true'] { opacity: 1; translate: 0 0; }
        .ctf-badge { font-size: .72rem; font-weight: 600; padding: .3rem .8rem; border-radius: 999px;
        background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.35); }
        .ctf-card h3 { margin: 1rem 0 1.25rem; font: 800 clamp(1.5rem, 3.5vw, 2.25rem)/1.2 'Inter', sans-serif; }
        .ctf-actions { display: flex; flex-wrap: wrap; gap: .75rem; justify-content: center; }
        .ctf-btn { block-size: 2.75rem; display: grid; place-items: center; padding-inline: 1.5rem;
        border-radius: .8rem; background: #fff; color: #4F46E5; font-weight: 700; text-decoration: none; }
        .ctf-btn.ctf-ghost { background: transparent; color: #fff; border: 1px solid rgba(255,255,255,.55); }
        .ctf-links { display: flex; flex-wrap: wrap; gap: .5rem 1.5rem; justify-content: center;
        margin-block-start: 2.5rem; }
        .ctf-links a { color: #57534E; font-size: .875rem; text-decoration: none; }
        .ctf-links a:hover { color: #4F46E5; text-decoration: underline; }
        .ctf-base { margin: 1.25rem 0 0; text-align: center; font-size: .75rem; color: #78716C; }
        @media (prefers-reduced-motion: reduce) { .ctf-card { transition-duration: .01ms; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Home.jsx — Inertia (React) page; renders the React snippet
        // below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import CtaFooter from '@/components/CtaFooter';

        export default function Home() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <CtaFooter />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // CtaFooter.jsx — gradient CTA card that rises in on scroll
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        export default function CtaFooter() {
          const card = useRef(null);
          const [seen, setSeen] = useState(false);

          useEffect(() => {
            const obs = new IntersectionObserver(
              (entries) => entries.forEach((e) => e.isIntersecting && setSeen(true)),
              { threshold: 0.35 },
            );
            obs.observe(card.current);
            return () => obs.disconnect();
          }, []);

          return (
            <>
              <style>{`
                .ctf-foot { padding: 5.5rem 1.5rem 1.5rem; background: #FAFAF9; }
                .ctf-card { inline-size: min(100%, 44rem); margin-block-start: -3.5rem; margin-inline: auto; text-align: center;
                            padding: 2.5rem 1.5rem; color: #fff; border-radius: 1.25rem;
                            background: linear-gradient(135deg, #6366F1, #8B5CF6 45%, #EC4899);
                            box-shadow: 0 24px 48px -16px rgba(139, 92, 246, .55);
                            opacity: 0; translate: 0 1.5rem; transition: opacity .6s ease, translate .6s ease; }
                .ctf-card[data-seen='true'] { opacity: 1; translate: 0 0; }
                .ctf-badge { font-size: .72rem; font-weight: 600; padding: .3rem .8rem; border-radius: 999px;
                            background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.35); }
                .ctf-card h3 { margin: 1rem 0 1.25rem; font: 800 clamp(1.5rem, 3.5vw, 2.25rem)/1.2 'Inter', sans-serif; }
                .ctf-actions { display: flex; flex-wrap: wrap; gap: .75rem; justify-content: center; }
                .ctf-btn { block-size: 2.75rem; display: grid; place-items: center; padding-inline: 1.5rem;
                            border-radius: .8rem; background: #fff; color: #4F46E5; font-weight: 700; text-decoration: none; }
                .ctf-btn.ctf-ghost { background: transparent; color: #fff; border: 1px solid rgba(255,255,255,.55); }
                .ctf-links { display: flex; flex-wrap: wrap; gap: .5rem 1.5rem; justify-content: center; margin-block-start: 2.5rem; }
                .ctf-links a { color: #57534E; font-size: .875rem; text-decoration: none; }
                .ctf-links a:hover { color: #4F46E5; text-decoration: underline; }
                .ctf-base { margin: 1.25rem 0 0; text-align: center; font-size: .75rem; color: #78716C; }
                @media (prefers-reduced-motion: reduce) { .ctf-card { transition-duration: .01ms; } }
              `}</style>
              <footer className="ctf-foot">
                {/* Rises in once the card is 35% visible; the observer fires on mount if it already is. */}
                <div className="ctf-card" ref={card} data-seen={seen}>
                  <span className="ctf-badge">14-day trial · no card</span>
                  <h3>Start shipping calmly, today</h3>
                  <div className="ctf-actions">
                    <a className="ctf-btn" href="#">Start free</a>
                    <a className="ctf-btn ctf-ghost" href="#">Talk to sales</a>
                  </div>
                </div>
                <nav className="ctf-links" aria-label="Footer">
                  <a href="#">Feature flags</a><a href="#">Pricing</a><a href="#">Docs</a><a href="#">Status</a>
                </nav>
                <p className="ctf-base">© 2026 Meridian — Frankfurt · Dublin · Singapore</p>
              </footer>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- CtaFooter.vue — gradient CTA card that rises in on scroll -->
        <script setup lang="ts">
        import { onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const card = ref<HTMLElement | null>(null);
        const seen = ref(false);

        onMounted(() => {
          const obs = new IntersectionObserver(
            (entries) => entries.forEach((e) => { if (e.isIntersecting) seen.value = true; }),
            { threshold: 0.35 },
          );
          if (card.value) obs.observe(card.value);
        });
        </script>

        <template>
          <footer class="ctf-foot">
            <!-- Rises in once the card is 35% visible; the observer fires on mount if it already is. -->
            <div ref="card" class="ctf-card" :data-seen="seen">
              <span class="ctf-badge">14-day trial · no card</span>
              <h3>Start shipping calmly, today</h3>
              <div class="ctf-actions">
                <a class="ctf-btn" href="#">Start free</a>
                <a class="ctf-btn ctf-ghost" href="#">Talk to sales</a>
              </div>
            </div>
            <nav class="ctf-links" aria-label="Footer">
              <a href="#">Feature flags</a><a href="#">Pricing</a><a href="#">Docs</a><a href="#">Status</a>
            </nav>
            <p class="ctf-base">© 2026 Meridian — Frankfurt · Dublin · Singapore</p>
          </footer>
        </template>

        <style scoped>
        .ctf-foot { padding: 5.5rem 1.5rem 1.5rem; background: #FAFAF9; }
        .ctf-card { inline-size: min(100%, 44rem); margin-block-start: -3.5rem; margin-inline: auto; text-align: center;
                    padding: 2.5rem 1.5rem; color: #fff; border-radius: 1.25rem;
                    background: linear-gradient(135deg, #6366F1, #8B5CF6 45%, #EC4899);
                    box-shadow: 0 24px 48px -16px rgba(139, 92, 246, .55);
                    opacity: 0; translate: 0 1.5rem; transition: opacity .6s ease, translate .6s ease; }
        .ctf-card[data-seen='true'] { opacity: 1; translate: 0 0; }
        .ctf-badge { font-size: .72rem; font-weight: 600; padding: .3rem .8rem; border-radius: 999px;
                    background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.35); }
        .ctf-card h3 { margin: 1rem 0 1.25rem; font: 800 clamp(1.5rem, 3.5vw, 2.25rem)/1.2 'Inter', sans-serif; }
        .ctf-actions { display: flex; flex-wrap: wrap; gap: .75rem; justify-content: center; }
        .ctf-btn { block-size: 2.75rem; display: grid; place-items: center; padding-inline: 1.5rem;
                    border-radius: .8rem; background: #fff; color: #4F46E5; font-weight: 700; text-decoration: none; }
        .ctf-btn.ctf-ghost { background: transparent; color: #fff; border: 1px solid rgba(255,255,255,.55); }
        .ctf-links { display: flex; flex-wrap: wrap; gap: .5rem 1.5rem; justify-content: center; margin-block-start: 2.5rem; }
        .ctf-links a { color: #57534E; font-size: .875rem; text-decoration: none; }
        .ctf-links a:hover { color: #4F46E5; text-decoration: underline; }
        .ctf-base { margin: 1.25rem 0 0; text-align: center; font-size: .75rem; color: #78716C; }
        @media (prefers-reduced-motion: reduce) { .ctf-card { transition-duration: .01ms; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- CtaFooter.svelte — gradient CTA card that rises in on scroll -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          let card: HTMLElement;
          let seen = $state(false);

          onMount(() => {
            const obs = new IntersectionObserver(
              (entries) => entries.forEach((e) => { if (e.isIntersecting) seen = true; }),
              { threshold: 0.35 },
            );
            obs.observe(card);
            return () => obs.disconnect();
          });
        </script>

        <footer class="ctf-foot">
          <!-- Rises in once the card is 35% visible; the observer fires on mount if it already is. -->
          <div bind:this={card} class="ctf-card" data-seen={seen}>
            <span class="ctf-badge">14-day trial · no card</span>
            <h3>Start shipping calmly, today</h3>
            <div class="ctf-actions">
              <a class="ctf-btn" href="#">Start free</a>
              <a class="ctf-btn ctf-ghost" href="#">Talk to sales</a>
            </div>
          </div>
          <nav class="ctf-links" aria-label="Footer">
            <a href="#">Feature flags</a><a href="#">Pricing</a><a href="#">Docs</a><a href="#">Status</a>
          </nav>
          <p class="ctf-base">© 2026 Meridian — Frankfurt · Dublin · Singapore</p>
        </footer>

        <style>
        .ctf-foot { padding: 5.5rem 1.5rem 1.5rem; background: #FAFAF9; }
        .ctf-card { inline-size: min(100%, 44rem); margin-block-start: -3.5rem; margin-inline: auto; text-align: center;
                    padding: 2.5rem 1.5rem; color: #fff; border-radius: 1.25rem;
                    background: linear-gradient(135deg, #6366F1, #8B5CF6 45%, #EC4899);
                    box-shadow: 0 24px 48px -16px rgba(139, 92, 246, .55);
                    opacity: 0; translate: 0 1.5rem; transition: opacity .6s ease, translate .6s ease; }
        .ctf-card[data-seen='true'] { opacity: 1; translate: 0 0; }
        .ctf-badge { font-size: .72rem; font-weight: 600; padding: .3rem .8rem; border-radius: 999px;
                    background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.35); }
        .ctf-card h3 { margin: 1rem 0 1.25rem; font: 800 clamp(1.5rem, 3.5vw, 2.25rem)/1.2 'Inter', sans-serif; }
        .ctf-actions { display: flex; flex-wrap: wrap; gap: .75rem; justify-content: center; }
        .ctf-btn { block-size: 2.75rem; display: grid; place-items: center; padding-inline: 1.5rem;
                    border-radius: .8rem; background: #fff; color: #4F46E5; font-weight: 700; text-decoration: none; }
        .ctf-btn.ctf-ghost { background: transparent; color: #fff; border: 1px solid rgba(255,255,255,.55); }
        .ctf-links { display: flex; flex-wrap: wrap; gap: .5rem 1.5rem; justify-content: center; margin-block-start: 2.5rem; }
        .ctf-links a { color: #57534E; font-size: .875rem; text-decoration: none; }
        .ctf-links a:hover { color: #4F46E5; text-decoration: underline; }
        .ctf-base { margin: 1.25rem 0 0; text-align: center; font-size: .75rem; color: #78716C; }
        @media (prefers-reduced-motion: reduce) { .ctf-card { transition-duration: .01ms; } }
        </style>
        SVELTE,
        ],
    ],

    'minimal-footer' => [
        'title' => ['fa' => 'فوتر مینیمال', 'en' => 'Minimal footer'],
        'icon' => 'minus',
        'oneLiner' => [
            'fa' => 'تک‌سطری و ظریف: © و نام، نقطهٔ سبز تپندهٔ «همهٔ سرویس‌ها سالم»، سه لینک متنی و یک سوییچ تم کوچک که همان لحظه صفحه را تیره/روشن می‌کند — روی یک جداساز مویی.',
            'en' => 'One quiet line: © and the name, a pulsing green “all systems operational” dot, three text links and a small theme switch that flips the page on the spot — all riding a hairline rule.',
        ],
        'js' => true,
        'docs' => 'https://footer.design',
        'props' => [
            ['name' => 'status', 'type' => 'string', 'default' => "'operational'", 'note' => [
                'fa' => 'نقطهٔ سبز با تپش ۲.۲ ثانیه‌ای؛ در حالت کاهش حرکت، تپش خاموش و فقط رنگ می‌ماند.',
                'en' => 'The green dot pulses on a 2.2s loop; under reduced motion the pulse stops and only the colour remains.',
            ]],
            ['name' => 'hairline', 'type' => 'length', 'default' => "'1px'", 'note' => [
                'fa' => 'جداساز مویی بالای سطر؛ هر چه سبک‌تر، فوتر مینیمال تر است.',
                'en' => 'The hairline above the row; the lighter the rule, the more minimal the footer reads.',
            ]],
            ['name' => 'themeSwitch', 'type' => 'boolean', 'default' => 'true', 'note' => [
                'fa' => 'سوییچ کوچک data-theme روی html را بین light و dark برمی‌گرداند و وضعیت فعلی را از همان‌جا می‌خواند.',
                'en' => 'The small switch flips data-theme on html between light and dark, reading its initial state from there too.',
            ]],
            ['name' => 'links', 'type' => 'array', 'default' => "'3'", 'note' => [
                'fa' => 'سه لینک متنی بدون دکمه و بدون آیکون — مستندات، مرجع API، حریم خصوصی.',
                'en' => 'Three plain text links, no buttons, no icons — Docs, API reference, Privacy.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <footer class="mnf-bar">
            <small>© ۲۰۲۶ مریدین</small>
            <a class="mnf-status" href="#status"><i aria-hidden="true"></i>همهٔ سرویس‌ها سالم</a>
            <nav class="mnf-links" aria-label="فوتر">
                <a href="#docs">مستندات</a><a href="#api">مرجع API</a><a href="#privacy">حریم خصوصی</a>
            </nav>
            <button type="button" class="mnf-switch" role="switch" aria-label="تم تیره"
                    x-data="{ on: document.documentElement.dataset.theme === 'dark' }"
                    x-bind:aria-checked="on"
                    x-on:click="on = !on; document.documentElement.dataset.theme = on ? 'dark' : 'light'">
                <span aria-hidden="true"></span>
            </button>
        </footer>

        <style>
        .mnf-bar { display: flex; flex-wrap: wrap; gap: .5rem 1.25rem; align-items: center; justify-content: center;
                   padding: 1rem 1.5rem; background: #FAFAF9; color: #57534E;
                   border-block-start: 1px solid #E7E5E4; font-family: 'Inter', 'Vazirmatn', sans-serif; }
        .mnf-bar a { color: #57534E; font-size: .8rem; text-decoration: none; }
        .mnf-bar a:hover { color: #1C1917; text-decoration: underline; }
        .mnf-status { display: inline-flex; align-items: center; gap: .4rem; font-size: .8rem; }
        .mnf-status i { inline-size: .45rem; aspect-ratio: 1; border-radius: 50%; background: #10B981;
                        animation: mnf-pulse 2.2s ease-in-out infinite; }
        .mnf-links { display: flex; gap: 1rem; }
        .mnf-switch { position: relative; inline-size: 2.1rem; block-size: 1.15rem; cursor: pointer;
                      background: #E7E5E4; border: none; border-radius: 999px; transition: background .2s ease; }
        .mnf-switch span { position: absolute; inset-block-start: .15rem; inset-inline-start: .15rem;
                           inline-size: .85rem; block-size: .85rem; border-radius: 50%; background: #fff;
                           transition: inset-inline-start .2s ease; }
        .mnf-switch[aria-checked='true'] { background: #10B981; }
        .mnf-switch[aria-checked='true'] span { inset-inline-start: 1.1rem; }
        @keyframes mnf-pulse { 0%, 100% { opacity: 1; } 50% { opacity: .35; } }
        @media (prefers-reduced-motion: reduce) {
            .mnf-status i { animation: none; }
            .mnf-switch, .mnf-switch span { transition-duration: .01ms; }
        }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <footer class="mnf-bar">
        <small>© 2026 Meridian</small>
        <a class="mnf-status" href="#status"><i aria-hidden="true"></i>All systems operational</a>
        <nav class="mnf-links" aria-label="Footer">
        <a href="#docs">Docs</a><a href="#api">API reference</a><a href="#privacy">Privacy</a>
        </nav>
        <button type="button" class="mnf-switch" role="switch" aria-label="Dark mode"
                x-data="{ on: document.documentElement.dataset.theme === 'dark' }"
                x-bind:aria-checked="on"
                x-on:click="on = !on; document.documentElement.dataset.theme = on ? 'dark' : 'light'">
        <span aria-hidden="true"></span>
        </button>
        </footer>

        <style>
        .mnf-bar { display: flex; flex-wrap: wrap; gap: .5rem 1.25rem; align-items: center; justify-content: center;
                   padding: 1rem 1.5rem; background: #FAFAF9; color: #57534E;
                   border-block-start: 1px solid #E7E5E4; font-family: 'Inter', sans-serif; }
        .mnf-bar a { color: #57534E; font-size: .8rem; text-decoration: none; }
        .mnf-bar a:hover { color: #1C1917; text-decoration: underline; }
        .mnf-status { display: inline-flex; align-items: center; gap: .4rem; font-size: .8rem; }
        .mnf-status i { inline-size: .45rem; aspect-ratio: 1; border-radius: 50%; background: #10B981;
        animation: mnf-pulse 2.2s ease-in-out infinite; }
        .mnf-links { display: flex; gap: 1rem; }
        .mnf-switch { position: relative; inline-size: 2.1rem; block-size: 1.15rem; cursor: pointer;
        background: #E7E5E4; border: none; border-radius: 999px; transition: background .2s ease; }
        .mnf-switch span { position: absolute; inset-block-start: .15rem; inset-inline-start: .15rem;
        inline-size: .85rem; block-size: .85rem; border-radius: 50%; background: #fff;
        transition: inset-inline-start .2s ease; }
        .mnf-switch[aria-checked='true'] { background: #10B981; }
        .mnf-switch[aria-checked='true'] span { inset-inline-start: 1.1rem; }
        @keyframes mnf-pulse { 0%, 100% { opacity: 1; } 50% { opacity: .35; } }
        @media (prefers-reduced-motion: reduce) {
        .mnf-status i { animation: none; }
        .mnf-switch, .mnf-switch span { transition-duration: .01ms; }
        }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Home.jsx — Inertia (React) page; renders the React snippet
        // below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import MinimalFooter from '@/components/MinimalFooter';

        export default function Home() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <MinimalFooter />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // MinimalFooter.jsx — one quiet line with a live theme switch
        import { useState } from 'react';
        import '@nabuxai/ui-core/css';

        export default function MinimalFooter() {
          const [dark, setDark] = useState(
            () => document.documentElement.dataset.theme === 'dark',
          );

          const toggle = () => {
            const on = !dark;
            setDark(on);
            document.documentElement.dataset.theme = on ? 'dark' : 'light';
          };

          return (
            <>
              <style>{`
                .mnf-bar { display: flex; flex-wrap: wrap; gap: .5rem 1.25rem; align-items: center; justify-content: center;
                            padding: 1rem 1.5rem; background: #FAFAF9; color: #57534E;
                            border-block-start: 1px solid #E7E5E4; font-family: 'Inter', sans-serif; }
                .mnf-bar a { color: #57534E; font-size: .8rem; text-decoration: none; }
                .mnf-bar a:hover { color: #1C1917; text-decoration: underline; }
                .mnf-status { display: inline-flex; align-items: center; gap: .4rem; font-size: .8rem; }
                .mnf-status i { inline-size: .45rem; aspect-ratio: 1; border-radius: 50%; background: #10B981;
                            animation: mnf-pulse 2.2s ease-in-out infinite; }
                .mnf-links { display: flex; gap: 1rem; }
                .mnf-switch { position: relative; inline-size: 2.1rem; block-size: 1.15rem; cursor: pointer;
                            background: #E7E5E4; border: none; border-radius: 999px; transition: background .2s ease; }
                .mnf-switch span { position: absolute; inset-block-start: .15rem; inset-inline-start: .15rem;
                            inline-size: .85rem; block-size: .85rem; border-radius: 50%; background: #fff;
                            transition: inset-inline-start .2s ease; }
                .mnf-switch[aria-checked='true'] { background: #10B981; }
                .mnf-switch[aria-checked='true'] span { inset-inline-start: 1.1rem; }
                @keyframes mnf-pulse { 0%, 100% { opacity: 1; } 50% { opacity: .35; } }
                @media (prefers-reduced-motion: reduce) {
                  .mnf-status i { animation: none; }
                  .mnf-switch, .mnf-switch span { transition-duration: .01ms; }
                }
              `}</style>
              <footer className="mnf-bar">
                <small>© 2026 Meridian</small>
                <a className="mnf-status" href="#status"><i aria-hidden="true"></i>All systems operational</a>
                <nav className="mnf-links" aria-label="Footer">
                  <a href="#docs">Docs</a><a href="#api">API reference</a><a href="#privacy">Privacy</a>
                </nav>
                {/* Flips the real page theme; state is read from html on mount. */}
                <button type="button" className="mnf-switch" role="switch" aria-label="Dark mode"
                        aria-checked={dark} onClick={toggle}>
                  <span aria-hidden="true"></span>
                </button>
              </footer>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- MinimalFooter.vue — one quiet line with a live theme switch -->
        <script setup lang="ts">
        import { ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const dark = ref(document.documentElement.dataset.theme === 'dark');

        function toggle() {
          dark.value = !dark.value;
          document.documentElement.dataset.theme = dark.value ? 'dark' : 'light';
        }
        </script>

        <template>
          <footer class="mnf-bar">
            <small>© 2026 Meridian</small>
            <a class="mnf-status" href="#status"><i aria-hidden="true"></i>All systems operational</a>
            <nav class="mnf-links" aria-label="Footer">
              <a href="#docs">Docs</a><a href="#api">API reference</a><a href="#privacy">Privacy</a>
            </nav>
            <!-- Flips the real page theme; state is read from html on mount. -->
            <button type="button" class="mnf-switch" role="switch" aria-label="Dark mode"
                    :aria-checked="dark" @click="toggle">
              <span aria-hidden="true"></span>
            </button>
          </footer>
        </template>

        <style scoped>
        .mnf-bar { display: flex; flex-wrap: wrap; gap: .5rem 1.25rem; align-items: center; justify-content: center;
                    padding: 1rem 1.5rem; background: #FAFAF9; color: #57534E;
                    border-block-start: 1px solid #E7E5E4; font-family: 'Inter', sans-serif; }
        .mnf-bar a { color: #57534E; font-size: .8rem; text-decoration: none; }
        .mnf-bar a:hover { color: #1C1917; text-decoration: underline; }
        .mnf-status { display: inline-flex; align-items: center; gap: .4rem; font-size: .8rem; }
        .mnf-status i { inline-size: .45rem; aspect-ratio: 1; border-radius: 50%; background: #10B981;
                    animation: mnf-pulse 2.2s ease-in-out infinite; }
        .mnf-links { display: flex; gap: 1rem; }
        .mnf-switch { position: relative; inline-size: 2.1rem; block-size: 1.15rem; cursor: pointer;
                    background: #E7E5E4; border: none; border-radius: 999px; transition: background .2s ease; }
        .mnf-switch span { position: absolute; inset-block-start: .15rem; inset-inline-start: .15rem;
                    inline-size: .85rem; block-size: .85rem; border-radius: 50%; background: #fff;
                    transition: inset-inline-start .2s ease; }
        .mnf-switch[aria-checked='true'] { background: #10B981; }
        .mnf-switch[aria-checked='true'] span { inset-inline-start: 1.1rem; }
        @keyframes mnf-pulse { 0%, 100% { opacity: 1; } 50% { opacity: .35; } }
        @media (prefers-reduced-motion: reduce) {
          .mnf-status i { animation: none; }
          .mnf-switch, .mnf-switch span { transition-duration: .01ms; }
        }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- MinimalFooter.svelte — one quiet line with a live theme switch -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          let dark = $state(document.documentElement.dataset.theme === 'dark');

          function toggle() {
            dark = !dark;
            document.documentElement.dataset.theme = dark ? 'dark' : 'light';
          }
        </script>

        <footer class="mnf-bar">
          <small>© 2026 Meridian</small>
          <a class="mnf-status" href="#status"><i aria-hidden="true"></i>All systems operational</a>
          <nav class="mnf-links" aria-label="Footer">
            <a href="#docs">Docs</a><a href="#api">API reference</a><a href="#privacy">Privacy</a>
          </nav>
          <!-- Flips the real page theme; state is read from html on mount. -->
          <button type="button" class="mnf-switch" role="switch" aria-label="Dark mode"
                  aria-checked={dark} onclick={toggle}>
            <span aria-hidden="true"></span>
          </button>
        </footer>

        <style>
        .mnf-bar { display: flex; flex-wrap: wrap; gap: .5rem 1.25rem; align-items: center; justify-content: center;
                    padding: 1rem 1.5rem; background: #FAFAF9; color: #57534E;
                    border-block-start: 1px solid #E7E5E4; font-family: 'Inter', sans-serif; }
        .mnf-bar a { color: #57534E; font-size: .8rem; text-decoration: none; }
        .mnf-bar a:hover { color: #1C1917; text-decoration: underline; }
        .mnf-status { display: inline-flex; align-items: center; gap: .4rem; font-size: .8rem; }
        .mnf-status i { inline-size: .45rem; aspect-ratio: 1; border-radius: 50%; background: #10B981;
                    animation: mnf-pulse 2.2s ease-in-out infinite; }
        .mnf-links { display: flex; gap: 1rem; }
        .mnf-switch { position: relative; inline-size: 2.1rem; block-size: 1.15rem; cursor: pointer;
                    background: #E7E5E4; border: none; border-radius: 999px; transition: background .2s ease; }
        .mnf-switch span { position: absolute; inset-block-start: .15rem; inset-inline-start: .15rem;
                    inline-size: .85rem; block-size: .85rem; border-radius: 50%; background: #fff;
                    transition: inset-inline-start .2s ease; }
        .mnf-switch[aria-checked='true'] { background: #10B981; }
        .mnf-switch[aria-checked='true'] span { inset-inline-start: 1.1rem; }
        @keyframes mnf-pulse { 0%, 100% { opacity: 1; } 50% { opacity: .35; } }
        @media (prefers-reduced-motion: reduce) {
          .mnf-status i { animation: none; }
          .mnf-switch, .mnf-switch span { transition-duration: .01ms; }
        }
        </style>
        SVELTE,
        ],
    ],

    'sitemap-footer' => [
        'title' => ['fa' => 'فوتر سایت‌مپ', 'en' => 'Sitemap footer'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'سایت‌مپ پرتراکم: پنج ستون دسته‌بندی با سرستون ریز، لینک‌های نشان‌دار (جدید با جرقه، پایدار با تیک، بازنشسته با هشدار)، منوی کوچک زبان با globe و سطر قوانین — کل سایت در یک نگاه.',
            'en' => 'The dense sitemap: five categories under tiny headers, annotated links (new with a spark, stable with a check, sunsetting with a warning), a small globe language menu and the legal row — the whole site at a glance.',
        ],
        'js' => true,
        'docs' => 'https://footer.design',
        'props' => [
            ['name' => 'columns', 'type' => 'array', 'default' => "'5'", 'note' => [
                'fa' => 'محصول، توسعه‌دهندگان، شرکت، منابع و قانونی؛ در موبایل دوستونی و در ۳۷۵px تک‌ستونی می‌شود.',
                'en' => 'Product, Developers, Company, Resources, Legal; two columns on tablet, a single rail at 375px.',
            ]],
            ['name' => 'statusIcons', 'type' => 'array', 'default' => "'new · stable · sunsetting'", 'note' => [
                'fa' => 'جرقه برای تازه‌ها، تیک سبز برای پایدارها، مثلث هشدار برای آن‌ها که می‌روند — وضعیت هر لینک بی‌کلیک معلوم است.',
                'en' => 'A spark for the new, a green check for the stable, a warning triangle for the sunsetting — each link’s state is readable without a click.',
            ]],
            ['name' => 'lang', 'type' => 'string', 'default' => "'en'", 'note' => [
                'fa' => 'منوی کوچک زبان با آیکون globe؛ Escape و کلیک بیرون می‌بندد و انتخاب فقط برچسب را عوض می‌کند.',
                'en' => 'The small globe language menu; Escape and outside clicks close it, and choosing only swaps the label.',
            ]],
            ['name' => 'density', 'type' => 'px', 'default' => "'14px · 1.9'", 'note' => [
                'fa' => 'اندازهٔ فونت و ارتفاع سطر لینک‌ها؛ تراکم سایت‌مپ از همین دو عدد می‌آید.',
                'en' => 'The link font size and line height; the sitemap’s density lives in these two numbers.',
            ]],
            ['name' => 'legalRow', 'type' => 'slot', 'default' => "'© · privacy · terms · cookies'", 'note' => [
                'fa' => 'سطر پایانی قوانین با جداکننده‌های نقطه‌ای، جدا از ستون قانونی تا هضم دوتایی نشود.',
                'en' => 'The dotted legal row at the very bottom, kept apart from the Legal column so it never reads twice.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <footer class="smf-foot" x-data="{ lang: 'en', open: false,
                  langs: { en: 'English', de: 'Deutsch', fr: 'Français', ja: '日本語' } }">
            <nav class="smf-grid" aria-label="نقشهٔ سایت">
                <div><h4>محصول</h4>
                    <a href="#">فیچر فلگ‌ها <i data-status="new"></i></a>
                    <a href="#">مشاهده‌پذیری <i data-status="stable"></i></a>
                    <a href="#">اتوماسیون انتشار</a>
                </div>
                <div><h4>توسعه‌دهندگان</h4>
                    <a href="#">مستندات <i data-status="stable"></i></a>
                    <a href="#">API قدیمی <i data-status="sunsetting"></i></a>
                </div>
                <div><h4>شرکت</h4><a href="#">دربارهٔ ما</a><a href="#">فرصت‌های شغلی <i data-status="new"></i></a></div>
                <div><h4>منابع</h4><a href="#">تغییرات</a><a href="#">وضعیت سرویس <i data-status="stable"></i></a></div>
                <div><h4>قانونی</h4><a href="#">حریم خصوصی</a><a href="#">شرایط</a></div>
            </nav>
            <div class="smf-bottom">
                <small>© ۲۰۲۶ مریدین</small>
                <div class="smf-legal"><a href="#">حریم خصوصی</a> · <a href="#">شرایط</a> · <a href="#">کوکی‌ها</a></div>
                <div class="smf-lang" x-data="{ open: false }">
                    <button type="button" x-on:click="open = !open" aria-haspopup="listbox"
                            x-bind:aria-expanded="open">🌐 <span x-text="langs[lang]"></span> ▾</button>
                    <ul role="listbox" x-show="open" x-on:click.outside="open = false" x-cloak>
                        <template x-for="(label, code) in langs" :key="code">
                            <li role="option" x-bind:aria-selected="lang === code"
                                x-on:click="lang = code; open = false" x-text="label"></li>
                        </template>
                    </ul>
                </div>
            </div>
        </footer>

        <style>
        .smf-foot { padding: 3rem 1.5rem 1.25rem; background: #FFFBF5; color: #292524;
                    font-family: 'Inter', 'Vazirmatn', sans-serif; }
        .smf-grid { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 1.5rem; }
        .smf-grid h4 { margin: 0 0 .6rem; font-size: .68rem; letter-spacing: .09em; text-transform: uppercase; color: #A8A29E; }
        .smf-grid a { display: flex; align-items: center; gap: .35rem; padding-block: .18rem;
                      font-size: .8rem; line-height: 1.9; color: #57534E; text-decoration: none; }
        .smf-grid a:hover { color: #B45309; text-decoration: underline; }
        .smf-grid i[data-status] { inline-size: .8rem; aspect-ratio: 1; border-radius: 50%; flex: none; }
        .smf-grid i[data-status='new'] { background: #F59E0B; clip-path: polygon(50% 0, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%); }
        .smf-grid i[data-status='stable'] { background: #059669; clip-path: polygon(14% 52%, 0 66%, 40% 100%, 100% 22%, 86% 8%, 40% 72%); }
        .smf-grid i[data-status='sunsetting'] { background: #DC2626; clip-path: polygon(50% 0, 100% 100%, 0 100%); }
        .smf-bottom { display: flex; flex-wrap: wrap; gap: .75rem 1.5rem; align-items: center; justify-content: space-between;
                      margin-block-start: 2.5rem; padding-block-start: 1rem; border-block-start: 1px solid #EFE7DA; }
        .smf-legal { display: flex; gap: .5rem; font-size: .75rem; color: #A8A29E; }
        .smf-legal a { color: #78716C; text-decoration: none; }
        .smf-lang { position: relative; }
        .smf-lang button { display: inline-flex; gap: .4rem; align-items: center; padding: .35rem .8rem;
                           font-size: .78rem; color: #57534E; background: #fff; border: 1px solid #EFE7DA;
                           border-radius: .55rem; cursor: pointer; }
        .smf-lang ul { position: absolute; inset-block-end: calc(100% + .4rem); inset-inline-end: 0; margin: 0;
                       padding: .35rem; min-inline-size: 9rem; list-style: none; background: #fff;
                       border: 1px solid #EFE7DA; border-radius: .6rem; box-shadow: 0 10px 30px -12px rgba(0,0,0,.25); }
        .smf-lang li { padding: .4rem .6rem; font-size: .8rem; border-radius: .4rem; cursor: pointer; }
        .smf-lang li:hover { background: #FDF6EC; }
        .smf-lang li[aria-selected='true'] { font-weight: 700; }
        [x-cloak] { display: none; }
        @media (max-width: 720px) { .smf-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (prefers-reduced-motion: reduce) { .smf-foot * { transition: none; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <footer class="smf-foot" x-data="{ lang: 'en', open: false,
                  langs: { en: 'English', de: 'Deutsch', fr: 'Français', ja: '日本語' } }">
        <nav class="smf-grid" aria-label="Site map">
        <div><h4>Product</h4>
        <a href="#">Feature flags <i data-status="new"></i></a>
        <a href="#">Observability <i data-status="stable"></i></a>
        <a href="#">Release automation</a>
        </div>
        <div><h4>Developers</h4>
        <a href="#">Documentation <i data-status="stable"></i></a>
        <a href="#">Legacy API <i data-status="sunsetting"></i></a>
        </div>
        <div><h4>Company</h4><a href="#">About</a><a href="#">Careers <i data-status="new"></i></a></div>
        <div><h4>Resources</h4><a href="#">Changelog</a><a href="#">Status <i data-status="stable"></i></a></div>
        <div><h4>Legal</h4><a href="#">Privacy</a><a href="#">Terms</a></div>
        </nav>
        <div class="smf-bottom">
        <small>© 2026 Meridian</small>
        <div class="smf-legal"><a href="#">Privacy</a> · <a href="#">Terms</a> · <a href="#">Cookies</a></div>
        <div class="smf-lang" x-data="{ open: false }">
        <button type="button" x-on:click="open = !open" aria-haspopup="listbox"
                x-bind:aria-expanded="open">🌐 <span x-text="langs[lang]"></span> ▾</button>
        <ul role="listbox" x-show="open" x-on:click.outside="open = false" x-cloak>
        <template x-for="(label, code) in langs" :key="code">
        <li role="option" x-bind:aria-selected="lang === code"
            x-on:click="lang = code; open = false" x-text="label"></li>
        </template>
        </ul>
        </div>
        </div>
        </footer>

        <style>
        .smf-foot { padding: 3rem 1.5rem 1.25rem; background: #FFFBF5; color: #292524;
        font-family: 'Inter', 'Vazirmatn', sans-serif; }
        .smf-grid { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 1.5rem; }
        .smf-grid h4 { margin: 0 0 .6rem; font-size: .68rem; letter-spacing: .09em; text-transform: uppercase; color: #A8A29E; }
        .smf-grid a { display: flex; align-items: center; gap: .35rem; padding-block: .18rem;
        font-size: .8rem; line-height: 1.9; color: #57534E; text-decoration: none; }
        .smf-grid a:hover { color: #B45309; text-decoration: underline; }
        .smf-grid i[data-status] { inline-size: .8rem; aspect-ratio: 1; border-radius: 50%; flex: none; }
        .smf-grid i[data-status='new'] { background: #F59E0B; clip-path: polygon(50% 0, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%); }
        .smf-grid i[data-status='stable'] { background: #059669; clip-path: polygon(14% 52%, 0 66%, 40% 100%, 100% 22%, 86% 8%, 40% 72%); }
        .smf-grid i[data-status='sunsetting'] { background: #DC2626; clip-path: polygon(50% 0, 100% 100%, 0 100%); }
        .smf-bottom { display: flex; flex-wrap: wrap; gap: .75rem 1.5rem; align-items: center; justify-content: space-between;
        margin-block-start: 2.5rem; padding-block-start: 1rem; border-block-start: 1px solid #EFE7DA; }
        .smf-legal { display: flex; gap: .5rem; font-size: .75rem; color: #A8A29E; }
        .smf-legal a { color: #78716C; text-decoration: none; }
        .smf-lang { position: relative; }
        .smf-lang button { display: inline-flex; gap: .4rem; align-items: center; padding: .35rem .8rem;
        font-size: .78rem; color: #57534E; background: #fff; border: 1px solid #EFE7DA;
        border-radius: .55rem; cursor: pointer; }
        .smf-lang ul { position: absolute; inset-block-end: calc(100% + .4rem); inset-inline-end: 0; margin: 0;
        padding: .35rem; min-inline-size: 9rem; list-style: none; background: #fff;
        border: 1px solid #EFE7DA; border-radius: .6rem; box-shadow: 0 10px 30px -12px rgba(0,0,0,.25); }
        .smf-lang li { padding: .4rem .6rem; font-size: .8rem; border-radius: .4rem; cursor: pointer; }
        .smf-lang li:hover { background: #FDF6EC; }
        .smf-lang li[aria-selected='true'] { font-weight: 700; }
        [x-cloak] { display: none; }
        @media (max-width: 720px) { .smf-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (prefers-reduced-motion: reduce) { .smf-foot * { transition: none; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Home.jsx — Inertia (React) page; renders the React snippet
        // below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import SitemapFooter from '@/components/SitemapFooter';

        export default function Home() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <SitemapFooter />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // SitemapFooter.jsx — dense sitemap, status icons, language menu
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const COLUMNS = [
          { head: 'Product', links: [
            { label: 'Feature flags', status: 'new' },
            { label: 'Observability', status: 'stable' },
            { label: 'Release automation' },
          ]},
          { head: 'Developers', links: [
            { label: 'Documentation', status: 'stable' },
            { label: 'Legacy API', status: 'sunsetting' },
          ]},
          { head: 'Company', links: [{ label: 'About' }, { label: 'Careers', status: 'new' }] },
          { head: 'Resources', links: [{ label: 'Changelog' }, { label: 'Status', status: 'stable' }] },
          { head: 'Legal', links: [{ label: 'Privacy' }, { label: 'Terms' }] },
        ];
        const LANGS = { en: 'English', de: 'Deutsch', fr: 'Français', ja: '日本語' };

        export default function SitemapFooter() {
          const [lang, setLang] = useState('en');
          const [open, setOpen] = useState(false);
          const menu = useRef(null);

          useEffect(() => {
            const close = (e) => { if (!menu.current?.contains(e.target)) setOpen(false); };
            const esc = (e) => { if (e.key === 'Escape') setOpen(false); };
            document.addEventListener('pointerdown', close);
            document.addEventListener('keydown', esc);
            return () => {
              document.removeEventListener('pointerdown', close);
              document.removeEventListener('keydown', esc);
            };
          }, []);

          return (
            <>
              <style>{`
                .smf-foot { padding: 3rem 1.5rem 1.25rem; background: #FFFBF5; color: #292524; font-family: 'Inter', sans-serif; }
                .smf-grid { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 1.5rem; }
                .smf-grid h4 { margin: 0 0 .6rem; font-size: .68rem; letter-spacing: .09em; text-transform: uppercase; color: #A8A29E; }
                .smf-grid a { display: flex; align-items: center; gap: .35rem; padding-block: .18rem;
                              font-size: .8rem; line-height: 1.9; color: #57534E; text-decoration: none; }
                .smf-grid a:hover { color: #B45309; text-decoration: underline; }
                .smf-dot { inline-size: .55rem; aspect-ratio: 1; border-radius: 50%; flex: none; }
                .smf-dot[data-status='new'] { background: #F59E0B; }
                .smf-dot[data-status='stable'] { background: #059669; }
                .smf-dot[data-status='sunsetting'] { background: #DC2626; border-radius: 2px; }
                .smf-bottom { display: flex; flex-wrap: wrap; gap: .75rem 1.5rem; align-items: center; justify-content: space-between;
                              margin-block-start: 2.5rem; padding-block-start: 1rem; border-block-start: 1px solid #EFE7DA; }
                .smf-legal { display: flex; gap: .5rem; font-size: .75rem; color: #A8A29E; }
                .smf-lang { position: relative; }
                .smf-lang button { display: inline-flex; gap: .4rem; align-items: center; padding: .35rem .8rem;
                              font-size: .78rem; color: #57534E; background: #fff; border: 1px solid #EFE7DA;
                              border-radius: .55rem; cursor: pointer; }
                .smf-lang ul { position: absolute; inset-block-end: calc(100% + .4rem); inset-inline-end: 0; margin: 0;
                              padding: .35rem; min-inline-size: 9rem; list-style: none; background: #fff;
                              border: 1px solid #EFE7DA; border-radius: .6rem; box-shadow: 0 10px 30px -12px rgba(0,0,0,.25); }
                .smf-lang li { padding: .4rem .6rem; font-size: .8rem; border-radius: .4rem; cursor: pointer; list-style: none; }
                .smf-lang li:hover { background: #FDF6EC; }
                .smf-lang li[aria-selected='true'] { font-weight: 700; }
                @media (max-width: 720px) { .smf-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
              `}</style>
              <footer className="smf-foot">
                <nav className="smf-grid" aria-label="Site map">
                  {COLUMNS.map((c) => (
                    <div key={c.head}>
                      <h4>{c.head}</h4>
                      {c.links.map((l) => (
                        <a key={l.label} href="#">
                          {l.label}
                          {/* The dot carries the link's state: amber new, green stable, red sunsetting. */}
                          {l.status && <i className="smf-dot" data-status={l.status} aria-label={l.status} />}
                        </a>
                      ))}
                    </div>
                  ))}
                </nav>
                <div className="smf-bottom">
                  <small>© 2026 Meridian</small>
                  <div className="smf-legal">
                    <a href="#">Privacy</a> · <a href="#">Terms</a> · <a href="#">Cookies</a>
                  </div>
                  <div className="smf-lang" ref={menu}>
                    <button type="button" aria-haspopup="listbox" aria-expanded={open}
                            onClick={() => setOpen(!open)}>🌐 {LANGS[lang]} ▾</button>
                    {open && (
                      <ul role="listbox">
                        {Object.entries(LANGS).map(([code, label]) => (
                          <li key={code} role="option" aria-selected={lang === code}
                              onClick={() => { setLang(code); setOpen(false); }}>{label}</li>
                        ))}
                      </ul>
                    )}
                  </div>
                </div>
              </footer>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- SitemapFooter.vue — dense sitemap, status icons, language menu -->
        <script setup lang="ts">
        import { ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const columns = [
          { head: 'Product', links: [
            { label: 'Feature flags', status: 'new' },
            { label: 'Observability', status: 'stable' },
            { label: 'Release automation' },
          ]},
          { head: 'Developers', links: [
            { label: 'Documentation', status: 'stable' },
            { label: 'Legacy API', status: 'sunsetting' },
          ]},
          { head: 'Company', links: [{ label: 'About' }, { label: 'Careers', status: 'new' }] },
          { head: 'Resources', links: [{ label: 'Changelog' }, { label: 'Status', status: 'stable' }] },
          { head: 'Legal', links: [{ label: 'Privacy' }, { label: 'Terms' }] },
        ];
        const langs: Record<string, string> = { en: 'English', de: 'Deutsch', fr: 'Français', ja: '日本語' };
        const lang = ref('en');
        const open = ref(false);
        </script>

        <template>
          <footer class="smf-foot">
            <nav class="smf-grid" aria-label="Site map">
              <div v-for="c in columns" :key="c.head">
                <h4>{{ c.head }}</h4>
                <a v-for="l in c.links" :key="l.label" href="#">
                  {{ l.label }}
                  <!-- The dot carries the link's state: amber new, green stable, red sunsetting. -->
                  <i v-if="l.status" class="smf-dot" :data-status="l.status" :aria-label="l.status"></i>
                </a>
              </div>
            </nav>
            <div class="smf-bottom">
              <small>© 2026 Meridian</small>
              <div class="smf-legal">
                <a href="#">Privacy</a> · <a href="#">Terms</a> · <a href="#">Cookies</a>
              </div>
              <div class="smf-lang" @click.outside="open = false" @keydown.escape="open = false">
                <button type="button" aria-haspopup="listbox" :aria-expanded="open" @click="open = !open">
                  🌐 {{ langs[lang] }} ▾
                </button>
                <ul v-show="open" role="listbox">
                  <li v-for="(label, code) in langs" :key="code" role="option" :aria-selected="lang === code"
                      @click="lang = code; open = false">{{ label }}</li>
                </ul>
              </div>
            </div>
          </footer>
        </template>

        <style scoped>
        .smf-foot { padding: 3rem 1.5rem 1.25rem; background: #FFFBF5; color: #292524; font-family: 'Inter', sans-serif; }
        .smf-grid { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 1.5rem; }
        .smf-grid h4 { margin: 0 0 .6rem; font-size: .68rem; letter-spacing: .09em; text-transform: uppercase; color: #A8A29E; }
        .smf-grid a { display: flex; align-items: center; gap: .35rem; padding-block: .18rem;
                      font-size: .8rem; line-height: 1.9; color: #57534E; text-decoration: none; }
        .smf-grid a:hover { color: #B45309; text-decoration: underline; }
        .smf-dot { inline-size: .55rem; aspect-ratio: 1; border-radius: 50%; flex: none; }
        .smf-dot[data-status='new'] { background: #F59E0B; }
        .smf-dot[data-status='stable'] { background: #059669; }
        .smf-dot[data-status='sunsetting'] { background: #DC2626; border-radius: 2px; }
        .smf-bottom { display: flex; flex-wrap: wrap; gap: .75rem 1.5rem; align-items: center; justify-content: space-between;
                      margin-block-start: 2.5rem; padding-block-start: 1rem; border-block-start: 1px solid #EFE7DA; }
        .smf-legal { display: flex; gap: .5rem; font-size: .75rem; color: #A8A29E; }
        .smf-lang { position: relative; }
        .smf-lang button { display: inline-flex; gap: .4rem; align-items: center; padding: .35rem .8rem;
                      font-size: .78rem; color: #57534E; background: #fff; border: 1px solid #EFE7DA;
                      border-radius: .55rem; cursor: pointer; }
        .smf-lang ul { position: absolute; inset-block-end: calc(100% + .4rem); inset-inline-end: 0; margin: 0;
                      padding: .35rem; min-inline-size: 9rem; list-style: none; background: #fff;
                      border: 1px solid #EFE7DA; border-radius: .6rem; box-shadow: 0 10px 30px -12px rgba(0,0,0,.25); }
        .smf-lang li { padding: .4rem .6rem; font-size: .8rem; border-radius: .4rem; cursor: pointer; }
        .smf-lang li:hover { background: #FDF6EC; }
        .smf-lang li[aria-selected='true'] { font-weight: 700; }
        @media (max-width: 720px) { .smf-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- SitemapFooter.svelte — dense sitemap, status icons, language menu -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const columns = [
            { head: 'Product', links: [
              { label: 'Feature flags', status: 'new' },
              { label: 'Observability', status: 'stable' },
              { label: 'Release automation' },
            ]},
            { head: 'Developers', links: [
              { label: 'Documentation', status: 'stable' },
              { label: 'Legacy API', status: 'sunsetting' },
            ]},
            { head: 'Company', links: [{ label: 'About' }, { label: 'Careers', status: 'new' }] },
            { head: 'Resources', links: [{ label: 'Changelog' }, { label: 'Status', status: 'stable' }] },
            { head: 'Legal', links: [{ label: 'Privacy' }, { label: 'Terms' }] },
          ];
          const langs: Record<string, string> = { en: 'English', de: 'Deutsch', fr: 'Français', ja: '日本語' };
          const entries = Object.entries(langs);
          let lang = $state('en');
          let open = $state(false);
        </script>

        <footer class="smf-foot">
          <nav class="smf-grid" aria-label="Site map">
            {#each columns as c (c.head)}
              <div>
                <h4>{c.head}</h4>
                {#each c.links as l (l.label)}
                  <a href="#">
                    {l.label}
                    <!-- The dot carries the link's state: amber new, green stable, red sunsetting. -->
                    {#if l.status}<i class="smf-dot" data-status={l.status} aria-label={l.status}></i>{/if}
                  </a>
                {/each}
              </div>
            {/each}
          </nav>
          <div class="smf-bottom">
            <small>© 2026 Meridian</small>
            <div class="smf-legal">
              <a href="#">Privacy</a> · <a href="#">Terms</a> · <a href="#">Cookies</a>
            </div>
            <div class="smf-lang">
              <button type="button" aria-haspopup="listbox" aria-expanded={open}
                      onclick={() => (open = !open)}>🌐 {langs[lang]} ▾</button>
              {#if open}
                <ul role="listbox">
                  {#each entries as [code, label] (code)}
                    <li role="option" aria-selected={lang === code}
                        onclick={() => { lang = code; open = false; }}>{label}</li>
                  {/each}
                </ul>
              {/if}
              <svelte:window onkeydown={(e) => e.key === 'Escape' && (open = false)} />
            </div>
          </div>
        </footer>

        <style>
        .smf-foot { padding: 3rem 1.5rem 1.25rem; background: #FFFBF5; color: #292524; font-family: 'Inter', sans-serif; }
        .smf-grid { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 1.5rem; }
        .smf-grid h4 { margin: 0 0 .6rem; font-size: .68rem; letter-spacing: .09em; text-transform: uppercase; color: #A8A29E; }
        .smf-grid a { display: flex; align-items: center; gap: .35rem; padding-block: .18rem;
                      font-size: .8rem; line-height: 1.9; color: #57534E; text-decoration: none; }
        .smf-grid a:hover { color: #B45309; text-decoration: underline; }
        .smf-dot { inline-size: .55rem; aspect-ratio: 1; border-radius: 50%; flex: none; }
        .smf-dot[data-status='new'] { background: #F59E0B; }
        .smf-dot[data-status='stable'] { background: #059669; }
        .smf-dot[data-status='sunsetting'] { background: #DC2626; border-radius: 2px; }
        .smf-bottom { display: flex; flex-wrap: wrap; gap: .75rem 1.5rem; align-items: center; justify-content: space-between;
                      margin-block-start: 2.5rem; padding-block-start: 1rem; border-block-start: 1px solid #EFE7DA; }
        .smf-legal { display: flex; gap: .5rem; font-size: .75rem; color: #A8A29E; }
        .smf-lang { position: relative; }
        .smf-lang button { display: inline-flex; gap: .4rem; align-items: center; padding: .35rem .8rem;
                      font-size: .78rem; color: #57534E; background: #fff; border: 1px solid #EFE7DA;
                      border-radius: .55rem; cursor: pointer; }
        .smf-lang ul { position: absolute; inset-block-end: calc(100% + .4rem); inset-inline-end: 0; margin: 0;
                      padding: .35rem; min-inline-size: 9rem; list-style: none; background: #fff;
                      border: 1px solid #EFE7DA; border-radius: .6rem; box-shadow: 0 10px 30px -12px rgba(0,0,0,.25); }
        .smf-lang li { padding: .4rem .6rem; font-size: .8rem; border-radius: .4rem; cursor: pointer; }
        .smf-lang li:hover { background: #FDF6EC; }
        .smf-lang li[aria-selected='true'] { font-weight: 700; }
        @media (max-width: 720px) { .smf-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        </style>
        SVELTE,
        ],
    ],
];
