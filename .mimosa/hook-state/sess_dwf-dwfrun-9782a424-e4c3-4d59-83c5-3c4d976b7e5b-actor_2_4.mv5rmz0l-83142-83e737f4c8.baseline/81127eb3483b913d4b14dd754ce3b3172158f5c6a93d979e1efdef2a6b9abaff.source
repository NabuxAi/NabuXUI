<?php

/**
 * Demo manifest of the "carbon" group — IBM Carbon Design System rebuilt as
 * faithful, interactive skins: the structured list with radio selection,
 * clickable and selectable tiles, the expandable tile, the combo button, the
 * overflow menu, the AI label (slug), the contained list, the tearsheet with
 * its side-rail influencer and the data spreadsheet. Scenarios live at
 * resources/views/demos/components/carbon/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'آی‌بی‌ام — کربن', 'en' => 'IBM Carbon'],

    'structured-list' => [
        'title' => ['fa' => 'فهرست ساخت‌یافته', 'en' => 'Structured List'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'ردیف‌های کل-مقدار با حاشیه‌های تختِ ۱پیکسلی و انتخابِ تک‌گزینه‌ای؛ جدول سبکی که هر دادهٔ تشریحی را بی‌پرده سبک کربنی می‌کند.',
            'en' => 'Key-value rows with flat 1px borders and radio-style selection — Carbon’s featherweight alternative to a full data table for descriptive data.',
        ],
        'js' => true,
        'docs' => 'https://react.carbondesignsystem.com/?path=/docs/components-structuredlist--overview',
        'props' => [
            ['name' => 'selection', 'type' => 'boolean', 'default' => 'false', 'note' => [
                'fa' => 'یک رادیو به آغاز هر ردیف می‌نشاند و کل ردیف را کلیک‌پذیر می‌کند؛ انتخابِ ردیف با وارون‌شدن سطح به layer selected رخ می‌دهد.',
                'en' => 'Puts a radio at the start of each row and makes the whole row clickable; selection inverts the row to the selected layer.',
            ]],
            ['name' => 'flush', 'type' => 'boolean', 'default' => 'false', 'note' => [
                'fa' => 'حالت flush پدینگ سلول‌ها را می‌برد تا فهرست به لبهٔ قاب بچسبد — برای نشاندن داخل پنل‌های پُر.',
                'en' => 'Flush mode removes the cell padding so the list hugs its frame — for sitting inside dense panels.',
            ]],
            ['name' => 'size', 'type' => 'string', 'default' => "'xl'", 'note' => [
                'fa' => 'md یا xl؛ xl ردیف را با همان تراز دوستونیِ کلید-مقدار جادارتر می‌کند.',
                'en' => 'md or xl; xl gives the two-column key-value alignment more room to breathe.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="cbsl" role="table" aria-label="محیط استقرار">
            <div class="cbsl-row" role="row" data-head>
                <span role="columnheader">محیط</span>
                <span role="columnheader">ناحیه</span>
            </div>
            <label class="cbsl-row" role="row">
                <input type="radio" name="env">
                <span role="cell">تولید</span>
                <span role="cell">فرانکفورت</span>
            </label>
        </div>

        <style>
        .cbsl { border-block-end: 1px solid #e0e0e0; font: 400 .875rem/1.4 'IBM Plex Sans', sans-serif; }
        .cbsl-row { display: grid; grid-template-columns: 12rem 1fr; gap: 1rem;
                    padding: .75rem 1rem; border-block-start: 1px solid #e0e0e0; }
        .cbsl-row[data-head] { font-size: .75rem; color: #525252; }
        .cbsl-row:has(input:checked) { background: #e0e0e0; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="cbsl" role="table" aria-label="Deployment target">
        <div class="cbsl-row" role="row" data-head>
        <span role="columnheader">Environment</span>
        <span role="columnheader">Region</span>
        </div>
        <label class="cbsl-row" role="row">
        <input type="radio" name="env">
        <span role="cell">Production</span>
        <span role="cell">Berlin</span>
        </label>
        </div>
        
        <style>
        .cbsl { border-block-end: 1px solid #e0e0e0; font: 400 .875rem/1.4 'IBM Plex Sans', sans-serif; }
        .cbsl-row { display: grid; grid-template-columns: 12rem 1fr; gap: 1rem;
        padding: .75rem 1rem; border-block-start: 1px solid #e0e0e0; }
        .cbsl-row[data-head] { font-size: .75rem; color: #525252; }
        .cbsl-row:has(input:checked) { background: #e0e0e0; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Deploy.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import DeploymentList from '@/components/DeploymentList';

        export default function Deploy() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <DeploymentList />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // DeploymentList.jsx — Carbon structured list, selection variant
        import { useState } from 'react';
        import '@nabuxai/ui-core/css';

        const ENVS = [
          { id: 'prod', name: 'Production', region: 'Frankfurt', cores: 16, zone: 'eu-de-2' },
          { id: 'staging', name: 'Staging', region: 'Dublin', cores: 8, zone: 'eu-ie-1' },
          { id: 'dev', name: 'Development', region: 'Istanbul', cores: 4, zone: 'eu-tr-1' },
        ];

        export default function DeploymentList() {
          const [env, setEnv] = useState('prod');
          const current = ENVS.find((e) => e.id === env);

          return (
            <>
              <style>{`
                .cbsl { max-inline-size: 28rem; border-block-end: 1px solid #e0e0e0; background: #f4f4f4;
                        font: 400 .875rem/1.4 'IBM Plex Sans', sans-serif; }
                .cbsl-row { display: grid; grid-template-columns: 2rem 1.1fr 1fr; gap: 1rem; align-items: center;
                            padding: .875rem 1rem; border-block-start: 1px solid #e0e0e0; cursor: pointer; }
                .cbsl-row[data-head] { padding-block: .5rem; font-size: .75rem; color: #525252; cursor: default; }
                .cbsl-row:has(input:checked) { background: #e0e0e0; box-shadow: inset 3px 0 0 #0f62fe; }
                .cbsl input { inline-size: 1.125rem; aspect-ratio: 1; margin: 0; accent-color: #0f62fe; }
                .cbsl small { color: #525252; }
                .cbsl-summary { display: flex; align-items: center; gap: 1rem; padding: .75rem 1rem;
                                border-block-start: 2px solid #0f62fe; }
                .cbsl-go { margin-inline-start: auto; block-size: 2.5rem; padding-inline: 1rem; border: none;
                           background: #0f62fe; color: #fff; cursor: pointer; }
              `}</style>
              <div className="cbsl">
                <div className="cbsl-row" role="row" data-head>
                  <span aria-hidden="true" />
                  <span role="columnheader">Environment</span>
                  <span role="columnheader">Region</span>
                </div>
                {/* Every row is one big radio — the selected layer inverts. */}
                {ENVS.map((e) => (
                  <label className="cbsl-row" role="row" key={e.id}>
                    <input type="radio" name="env" checked={env === e.id} onChange={() => setEnv(e.id)} />
                    <b role="cell">{e.name}</b>
                    <span role="cell">{e.region} <small>· {e.cores} cores</small></span>
                  </label>
                ))}
                <div className="cbsl-summary" role="status">
                  <span>Deploying to <b>{current.name}</b> · {current.zone}</span>
                  <button type="button" className="cbsl-go">Deploy</button>
                </div>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- DeploymentList.vue — Carbon structured list, selection variant -->
        <script setup lang="ts">
        import { computed, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        interface Env { id: string; name: string; region: string; cores: number; zone: string }
        const envs: Env[] = [
          { id: 'prod', name: 'Production', region: 'Frankfurt', cores: 16, zone: 'eu-de-2' },
          { id: 'staging', name: 'Staging', region: 'Dublin', cores: 8, zone: 'eu-ie-1' },
          { id: 'dev', name: 'Development', region: 'Istanbul', cores: 4, zone: 'eu-tr-1' },
        ];
        const env = ref('prod');
        const current = computed(() => envs.find((e) => e.id === env.value)!);
        </script>

        <template>
          <div class="cbsl">
            <div class="cbsl-row" role="row" data-head>
              <span aria-hidden="true"></span>
              <span role="columnheader">Environment</span>
              <span role="columnheader">Region</span>
            </div>
            <!-- Every row is one big radio — the selected layer inverts. -->
            <label v-for="e in envs" :key="e.id" class="cbsl-row" role="row">
              <input v-model="env" type="radio" name="env" :value="e.id" />
              <b role="cell">{{ e.name }}</b>
              <span role="cell">{{ e.region }} <small>· {{ e.cores }} cores</small></span>
            </label>
            <div class="cbsl-summary" role="status">
              <span>Deploying to <b>{{ current.name }}</b> · {{ current.zone }}</span>
              <button type="button" class="cbsl-go">Deploy</button>
            </div>
          </div>
        </template>

        <style scoped>
        .cbsl { max-inline-size: 28rem; border-block-end: 1px solid #e0e0e0; background: #f4f4f4;
                font: 400 .875rem/1.4 'IBM Plex Sans', sans-serif; }
        .cbsl-row { display: grid; grid-template-columns: 2rem 1.1fr 1fr; gap: 1rem; align-items: center;
                    padding: .875rem 1rem; border-block-start: 1px solid #e0e0e0; cursor: pointer; }
        .cbsl-row[data-head] { padding-block: .5rem; font-size: .75rem; color: #525252; cursor: default; }
        .cbsl-row:has(input:checked) { background: #e0e0e0; box-shadow: inset 3px 0 0 #0f62fe; }
        .cbsl input { inline-size: 1.125rem; aspect-ratio: 1; margin: 0; accent-color: #0f62fe; }
        .cbsl small { color: #525252; }
        .cbsl-summary { display: flex; align-items: center; gap: 1rem; padding: .75rem 1rem;
                        border-block-start: 2px solid #0f62fe; }
        .cbsl-go { margin-inline-start: auto; block-size: 2.5rem; padding-inline: 1rem; border: none;
                   background: #0f62fe; color: #fff; cursor: pointer; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- DeploymentList.svelte — Carbon structured list, selection variant -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          interface Env { id: string; name: string; region: string; cores: number; zone: string }
          const envs: Env[] = [
            { id: 'prod', name: 'Production', region: 'Frankfurt', cores: 16, zone: 'eu-de-2' },
            { id: 'staging', name: 'Staging', region: 'Dublin', cores: 8, zone: 'eu-ie-1' },
            { id: 'dev', name: 'Development', region: 'Istanbul', cores: 4, zone: 'eu-tr-1' },
          ];
          let env = $state('prod');
          const current = $derived(envs.find((e) => e.id === env)!);
        </script>

        <div class="cbsl">
          <div class="cbsl-row" role="row" data-head>
            <span aria-hidden="true"></span>
            <span role="columnheader">Environment</span>
            <span role="columnheader">Region</span>
          </div>
          <!-- Every row is one big radio — the selected layer inverts. -->
          {#each envs as e (e.id)}
            <label class="cbsl-row" role="row">
              <input type="radio" name="env" value={e.id} checked={env === e.id} onchange={() => (env = e.id)} />
              <b role="cell">{e.name}</b>
              <span role="cell">{e.region} <small>· {e.cores} cores</small></span>
            </label>
          {/each}
          <div class="cbsl-summary" role="status">
            <span>Deploying to <b>{current.name}</b> · {current.zone}</span>
            <button type="button" class="cbsl-go">Deploy</button>
          </div>
        </div>

        <style>
        .cbsl { max-inline-size: 28rem; border-block-end: 1px solid #e0e0e0; background: #f4f4f4;
                font: 400 .875rem/1.4 'IBM Plex Sans', sans-serif; }
        .cbsl-row { display: grid; grid-template-columns: 2rem 1.1fr 1fr; gap: 1rem; align-items: center;
                    padding: .875rem 1rem; border-block-start: 1px solid #e0e0e0; cursor: pointer; }
        .cbsl-row[data-head] { padding-block: .5rem; font-size: .75rem; color: #525252; cursor: default; }
        .cbsl-row:has(input:checked) { background: #e0e0e0; box-shadow: inset 3px 0 0 #0f62fe; }
        .cbsl input { inline-size: 1.125rem; aspect-ratio: 1; margin: 0; accent-color: #0f62fe; }
        .cbsl small { color: #525252; }
        .cbsl-summary { display: flex; align-items: center; gap: 1rem; padding: .75rem 1rem;
                        border-block-start: 2px solid #0f62fe; }
        .cbsl-go { margin-inline-start: auto; block-size: 2.5rem; padding-inline: 1rem; border: none;
                   background: #0f62fe; color: #fff; cursor: pointer; }
        </style>
        SVELTE,
        ],
    ],

    'selectable-tile' => [
        'title' => ['fa' => 'کاشی انتخابی', 'en' => 'Selectable Tile'],
        'icon' => 'check',
        'oneLiner' => [
            'fa' => 'کاشی تختِ تک‌کلیکی که کل سطحش همان چک‌باکس است؛ با تیکِ گوشه و وارون‌شدن حاشیه به آبی کربن، در حالت تک‌انتخابی یا گروهی.',
            'en' => 'The click-to-select tile: the whole surface is the checkbox — a corner checkmark and carbon-blue border inversion on select, in single- or multi-select groups.',
        ],
        'js' => true,
        'docs' => 'https://react.carbondesignsystem.com/?path=/docs/components-tile--overview',
        'props' => [
            ['name' => 'selected', 'type' => 'boolean', 'default' => 'false', 'note' => [
                'fa' => 'وضعیت انتخاب؛ حاشیهٔ کاشی به #0f62fe می‌رود و تیکِ گوشهٔ بالا-پایان ظاهر می‌شود.',
                'en' => 'The selected state; the tile border flips to #0f62fe and the end-top corner checkmark appears.',
            ]],
            ['name' => 'name', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'نام گروه رادیو؛ کاشی‌های هم‌نام تک‌انتخابی می‌شوند و بی‌نام‌ها چک‌باکس گروهی.',
                'en' => 'The radio group name; same-named tiles become single-select, unnamed ones multi-select.',
            ]],
            ['name' => 'handleTitle', 'type' => 'string', 'default' => "'label'", 'note' => [
                'fa' => 'برچسب دسترس‌پذیریِ ورودیِ پنهان؛ صفحه‌خوان کل کاشی را یک کنترل می‌خواند.',
                'en' => 'The accessible title of the hidden input; screen readers announce the whole tile as one control.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <label class="cbtl" data-selected="false">
            <input type="radio" name="plan">
            <span class="cbtl-check">✓</span>
            <b>پلن رشد</b>
            <small>۱۲ هسته · پشتیبان‌گیری روزانه</small>
        </label>

        <style>
        .cbtl { position: relative; display: grid; gap: .25rem; padding: 1rem;
                border: 1px solid #e0e0e0; background: #fff; cursor: pointer;
                font-family: 'IBM Plex Sans', sans-serif; }
        .cbtl:has(input:checked) { border: 1px solid #0f62fe; background: #edf5ff; }
        .cbtl-check { position: absolute; inset-block-start: 0; inset-inline-end: 0;
                      visibility: hidden; padding: .25rem; background: #0f62fe; color: #fff; }
        .cbtl:has(input:checked) .cbtl-check { visibility: visible; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <label class="cbtl" data-selected="false">
        <input type="radio" name="plan">
        <span class="cbtl-check">✓</span>
        <b>Growth plan</b>
        <small>12 cores · daily backups</small>
        </label>
        
        <style>
        .cbtl { position: relative; display: grid; gap: .25rem; padding: 1rem;
        border: 1px solid #e0e0e0; background: #fff; cursor: pointer;
        font-family: 'IBM Plex Sans', sans-serif; }
        .cbtl:has(input:checked) { border: 1px solid #0f62fe; background: #edf5ff; }
        .cbtl-check { position: absolute; inset-block-start: 0; inset-inline-end: 0;
        visibility: hidden; padding: .25rem; background: #0f62fe; color: #fff; }
        .cbtl:has(input:checked) .cbtl-check { visibility: visible; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Checkout.jsx — Inertia page; renders the React snippet
        // below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import PlanTiles from '@/components/PlanTiles';

        export default function Checkout() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <PlanTiles />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // PlanTiles.jsx — Carbon selectable tiles: the whole surface is the checkbox
        import { useState } from 'react';
        import '@nabuxai/ui-core/css';

        const PLANS = [
          { id: 'starter', name: 'Starter', detail: 'Community support', price: 0 },
          { id: 'growth', name: 'Growth', detail: '4-hour response · monthly report', price: 240 },
          { id: 'scale', name: 'Scale', detail: '1-hour response · dedicated engineer', price: 590 },
        ];
        const ADDONS = [
          { id: 'backup', name: 'Daily backup', price: 60 },
          { id: 'monitor', name: 'Uptime monitor', price: 35 },
        ];

        export default function PlanTiles() {
          const [plan, setPlan] = useState('growth');
          const [addons, setAddons] = useState({ backup: false, monitor: true });
          const total =
            PLANS.find((p) => p.id === plan).price +
            ADDONS.reduce((sum, a) => sum + (addons[a.id] ? a.price : 0), 0);
          const flip = (id) => setAddons((a) => ({ ...a, [id]: !a[id] }));

          const tile = (checked, input) => (
            <>
              {/* Hidden input; the visible chrome reacts through :has(:checked). */}
              {input}
              <span className="cbtl-check" aria-hidden="true">✓</span>
              {checked}
            </>
          );

          return (
            <>
              <style>{`
                .cbtl-grid { display: grid; gap: 1rem; grid-template-columns: repeat(3, minmax(9rem, 1fr));
                             max-inline-size: 30rem; margin-block-end: 1rem; }
                .cbtl { position: relative; display: grid; align-content: start; gap: .25rem; padding: 1rem;
                        border: 1px solid #e0e0e0; background: #f4f4f4; cursor: pointer;
                        font: 400 .875rem/1.4 'IBM Plex Sans', sans-serif; }
                .cbtl:hover { background: #e8e8e8; }
                .cbtl:has(input:checked) { border-color: #0f62fe; box-shadow: inset 0 0 0 1px #0f62fe; }
                .cbtl input { position: absolute; inline-size: 1px; block-size: 1px; margin: -1px; opacity: 0; }
                .cbtl-check { position: absolute; inset-block-start: 0; inset-inline-end: 0; display: grid; place-items: center;
                              inline-size: 1.375rem; block-size: 1.375rem; background: #0f62fe; color: #fff; visibility: hidden; }
                .cbtl:has(input:checked) .cbtl-check { visibility: visible; }
                .cbtl b { font-weight: 600; }
                .cbtl small { font-size: .75rem; color: #525252; }
                .cbtl-price { font-weight: 600; font-size: 1.125rem; }
                .cbtl-total { display: flex; gap: 1rem; align-items: baseline; max-inline-size: 30rem; padding: .75rem 1rem;
                              background: #f4f4f4; border-block-start: 2px solid #0f62fe; }
                .cbtl-total b { font-size: 1.25rem; }
              `}</style>
              <div className="cbtl-grid" role="radiogroup" aria-label="Support plan">
                {PLANS.map((p) => (
                  <label className="cbtl" key={p.id}>
                    {tile(<><b>{p.name}</b><small>{p.detail}</small><span className="cbtl-price">${p.price}</span></>,
                      <input type="radio" name="plan" checked={plan === p.id} onChange={() => setPlan(p.id)} />)}
                  </label>
                ))}
              </div>
              <div className="cbtl-grid" role="group" aria-label="Add-ons">
                {ADDONS.map((a) => (
                  <label className="cbtl" key={a.id}>
                    {tile(<><b>{a.name}</b><span className="cbtl-price">+${a.price}</span></>,
                      <input type="checkbox" checked={addons[a.id]} onChange={() => flip(a.id)} />)}
                  </label>
                ))}
              </div>
              <div className="cbtl-total" role="status">
                <span>Monthly total</span>
                <b>${total}</b>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- PlanTiles.vue — Carbon selectable tiles: the whole surface is the checkbox -->
        <script setup lang="ts">
        import { computed, reactive, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const plans = [
          { id: 'starter', name: 'Starter', detail: 'Community support', price: 0 },
          { id: 'growth', name: 'Growth', detail: '4-hour response · monthly report', price: 240 },
          { id: 'scale', name: 'Scale', detail: '1-hour response · dedicated engineer', price: 590 },
        ];
        const addonsList = [
          { id: 'backup', name: 'Daily backup', price: 60 },
          { id: 'monitor', name: 'Uptime monitor', price: 35 },
        ];
        const plan = ref('growth');
        const addons = reactive({ backup: false, monitor: true });
        const total = computed(
          () =>
            plans.find((p) => p.id === plan.value)!.price +
            addonsList.reduce((sum, a) => sum + (addons[a.id] ? a.price : 0), 0),
        );
        </script>

        <template>
          <div class="cbtl-grid" role="radiogroup" aria-label="Support plan">
            <!-- Hidden input; the visible chrome reacts through :has(:checked). -->
            <label v-for="p in plans" :key="p.id" class="cbtl">
              <input v-model="plan" type="radio" name="plan" :value="p.id" />
              <span class="cbtl-check" aria-hidden="true">✓</span>
              <b>{{ p.name }}</b>
              <small>{{ p.detail }}</small>
              <span class="cbtl-price">${{ p.price }}</span>
            </label>
          </div>
          <div class="cbtl-grid" role="group" aria-label="Add-ons">
            <label v-for="a in addonsList" :key="a.id" class="cbtl">
              <input v-model="addons[a.id]" type="checkbox" />
              <span class="cbtl-check" aria-hidden="true">✓</span>
              <b>{{ a.name }}</b>
              <span class="cbtl-price">+${{ a.price }}</span>
            </label>
          </div>
          <div class="cbtl-total" role="status">
            <span>Monthly total</span>
            <b>${{ total }}</b>
          </div>
        </template>

        <style scoped>
        .cbtl-grid { display: grid; gap: 1rem; grid-template-columns: repeat(3, minmax(9rem, 1fr));
                     max-inline-size: 30rem; margin-block-end: 1rem; }
        .cbtl { position: relative; display: grid; align-content: start; gap: .25rem; padding: 1rem;
                border: 1px solid #e0e0e0; background: #f4f4f4; cursor: pointer;
                font: 400 .875rem/1.4 'IBM Plex Sans', sans-serif; }
        .cbtl:hover { background: #e8e8e8; }
        .cbtl:has(input:checked) { border-color: #0f62fe; box-shadow: inset 0 0 0 1px #0f62fe; }
        .cbtl input { position: absolute; inline-size: 1px; block-size: 1px; margin: -1px; opacity: 0; }
        .cbtl-check { position: absolute; inset-block-start: 0; inset-inline-end: 0; display: grid; place-items: center;
                      inline-size: 1.375rem; block-size: 1.375rem; background: #0f62fe; color: #fff; visibility: hidden; }
        .cbtl:has(input:checked) .cbtl-check { visibility: visible; }
        .cbtl b { font-weight: 600; }
        .cbtl small { font-size: .75rem; color: #525252; }
        .cbtl-price { font-weight: 600; font-size: 1.125rem; }
        .cbtl-total { display: flex; gap: 1rem; align-items: baseline; max-inline-size: 30rem; padding: .75rem 1rem;
                      background: #f4f4f4; border-block-start: 2px solid #0f62fe; }
        .cbtl-total b { font-size: 1.25rem; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- PlanTiles.svelte — Carbon selectable tiles: the whole surface is the checkbox -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const plans = [
            { id: 'starter', name: 'Starter', detail: 'Community support', price: 0 },
            { id: 'growth', name: 'Growth', detail: '4-hour response · monthly report', price: 240 },
            { id: 'scale', name: 'Scale', detail: '1-hour response · dedicated engineer', price: 590 },
          ];
          const addonsList = [
            { id: 'backup', name: 'Daily backup', price: 60 },
            { id: 'monitor', name: 'Uptime monitor', price: 35 },
          ];
          let plan = $state('growth');
          let addons = $state({ backup: false, monitor: true });
          const total = $derived(
            plans.find((p) => p.id === plan)!.price +
              addonsList.reduce((sum, a) => sum + (addons[a.id] ? a.price : 0), 0),
          );
        </script>

        <div class="cbtl-grid" role="radiogroup" aria-label="Support plan">
          <!-- Hidden input; the visible chrome reacts through :has(:checked). -->
          {#each plans as p (p.id)}
            <label class="cbtl">
              <input type="radio" name="plan" value={p.id} checked={plan === p.id} onchange={() => (plan = p.id)} />
              <span class="cbtl-check" aria-hidden="true">✓</span>
              <b>{p.name}</b>
              <small>{p.detail}</small>
              <span class="cbtl-price">${p.price}</span>
            </label>
          {/each}
        </div>
        <div class="cbtl-grid" role="group" aria-label="Add-ons">
          {#each addonsList as a (a.id)}
            <label class="cbtl">
              <input type="checkbox" checked={addons[a.id]} onchange={() => (addons[a.id] = !addons[a.id])} />
              <span class="cbtl-check" aria-hidden="true">✓</span>
              <b>{a.name}</b>
              <span class="cbtl-price">+${a.price}</span>
            </label>
          {/each}
        </div>
        <div class="cbtl-total" role="status">
          <span>Monthly total</span>
          <b>${total}</b>
        </div>

        <style>
        .cbtl-grid { display: grid; gap: 1rem; grid-template-columns: repeat(3, minmax(9rem, 1fr));
                     max-inline-size: 30rem; margin-block-end: 1rem; }
        .cbtl { position: relative; display: grid; align-content: start; gap: .25rem; padding: 1rem;
                border: 1px solid #e0e0e0; background: #f4f4f4; cursor: pointer;
                font: 400 .875rem/1.4 'IBM Plex Sans', sans-serif; }
        .cbtl:hover { background: #e8e8e8; }
        .cbtl:has(input:checked) { border-color: #0f62fe; box-shadow: inset 0 0 0 1px #0f62fe; }
        .cbtl input { position: absolute; inline-size: 1px; block-size: 1px; margin: -1px; opacity: 0; }
        .cbtl-check { position: absolute; inset-block-start: 0; inset-inline-end: 0; display: grid; place-items: center;
                      inline-size: 1.375rem; block-size: 1.375rem; background: #0f62fe; color: #fff; visibility: hidden; }
        .cbtl:has(input:checked) .cbtl-check { visibility: visible; }
        .cbtl b { font-weight: 600; }
        .cbtl small { font-size: .75rem; color: #525252; }
        .cbtl-price { font-weight: 600; font-size: 1.125rem; }
        .cbtl-total { display: flex; gap: 1rem; align-items: baseline; max-inline-size: 30rem; padding: .75rem 1rem;
                      background: #f4f4f4; border-block-start: 2px solid #0f62fe; }
        .cbtl-total b { font-size: 1.25rem; }
        </style>
        SVELTE,
        ],
    ],

    'expandable-tile' => [
        'title' => ['fa' => 'کاشی بازشو', 'en' => 'Expandable Tile'],
        'icon' => 'chevron-down',
        'oneLiner' => [
            'fa' => 'کاشی شبکه‌ای که شِورانش می‌چرخد و محتوا را داخل همان قاب تخت رونده می‌کند؛ آکاردئونی که به‌جای ستون، در گرید زندگی می‌کند.',
            'en' => 'A grid tile whose chevron spins to unfurl content inside the same flat frame — an accordion that lives in the grid, not in a column.',
        ],
        'js' => true,
        'docs' => 'https://react.carbondesignsystem.com/?path=/docs/components-tile--overview',
        'props' => [
            ['name' => 'expanded', 'type' => 'boolean', 'default' => 'false', 'note' => [
                'fa' => 'باز بودن کاشی؛ با کلیک روی هر جای نوار بالا (نه دکمه‌های داخلش) برعکس می‌شود.',
                'en' => 'Whether the tile is open; clicking anywhere above the fold (not inner buttons) flips it.',
            ]],
            ['name' => 'chevron', 'type' => 'rotate', 'default' => "'0 → 180°'", 'note' => [
                'fa' => 'شِوران پایانی با چرخش ۱۸۰ درجهی نرم نقش خود را عوض می‌کند — امضای حرکتی کاشی بازشو.',
                'en' => 'The chevron flips 180° with a soft rotation — the expandable tile’s motion signature.',
            ]],
            ['name' => 'below-the-fold', 'type' => 'slot', 'default' => 'null', 'note' => [
                'fa' => 'محتوای بازشو در گرید ردیف پنهان است و با باز شدن، ارتفاع کاشی را با ترنزیشن grid رشد می‌دهد.',
                'en' => 'The below-the-fold content hides in a grid row and grows the tile with a grid transition when opened.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="cbex" data-open="false">
            <button class="cbex-head" aria-expanded="false">
                <b>ناحیهٔ فرانکفورت</b>
                <span class="cbex-chevron">⌄</span>
            </button>
            <div class="cbex-body">
                <p>۳ ناحیهٔ دسترس‌پذیر · فاصلهٔ ۲۴ms از استانبول</p>
            </div>
        </div>

        <style>
        .cbex { border: 1px solid #e0e0e0; background: #fff;
                display: grid; grid-template-rows: auto 0fr; transition: grid-template-rows .3s; }
        .cbex[data-open='true'] { grid-template-rows: auto 1fr; border-color: #8d8d8d; }
        .cbex-body { overflow: hidden; padding-inline: 1rem; }
        .cbex[data-open='true'] .cbex-body { padding-block-end: 1rem; }
        .cbex[data-open='true'] .cbex-chevron { rotate: 180deg; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="cbex" data-open="false">
        <button class="cbex-head" aria-expanded="false">
        <b>Berlin region</b>
        <span class="cbex-chevron">⌄</span>
        </button>
        <div class="cbex-body">
        <p>3 availability zones · 24ms from Istanbul</p>
        </div>
        </div>
        
        <style>
        .cbex { border: 1px solid #e0e0e0; background: #fff;
        display: grid; grid-template-rows: auto 0fr; transition: grid-template-rows .3s; }
        .cbex[data-open='true'] { grid-template-rows: auto 1fr; border-color: #8d8d8d; }
        .cbex-body { overflow: hidden; padding-inline: 1rem; }
        .cbex[data-open='true'] .cbex-body { padding-block-end: 1rem; }
        .cbex[data-open='true'] .cbex-chevron { rotate: 180deg; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Regions.jsx — Inertia page; renders the React snippet
        // below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import RegionTiles from '@/components/RegionTiles';

        export default function Regions() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <RegionTiles />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // RegionTiles.jsx — Carbon expandable tiles: an accordion that lives in the grid
        import { useState } from 'react';
        import '@nabuxai/ui-core/css';

        const REGIONS = [
          { id: 'fra', city: 'Frankfurt', zone: 'eu-de', latency: '24ms', free: '64 vCPU' },
          { id: 'ist', city: 'Istanbul', zone: 'eu-tr', latency: '41ms', free: '38 vCPU' },
          { id: 'dub', city: 'Dublin', zone: 'eu-ie', latency: '33ms', free: '18 vCPU' },
        ];

        export default function RegionTiles() {
          const [open, setOpen] = useState({ ist: true });
          const flip = (id) => setOpen((o) => ({ ...o, [id]: !o[id] }));

          return (
            <>
              <style>{`
                .cbex-grid { display: grid; gap: 1rem; grid-template-columns: repeat(3, minmax(11rem, 1fr));
                             max-inline-size: 36rem; }
                .cbex { display: grid; grid-template-rows: auto 0fr; border: 1px solid #e0e0e0; background: #f4f4f4;
                        transition: grid-template-rows 240ms cubic-bezier(.2, 0, 0, 1), border-color 240ms cubic-bezier(.2, 0, 0, 1); }
                .cbex[data-open='true'] { grid-template-rows: auto 1fr; border-color: #8d8d8d; }
                .cbex-head { display: flex; align-items: center; gap: .5rem; padding: .875rem 1rem; border: none;
                             background: transparent; font: 600 .875rem/1.3 'IBM Plex Sans', sans-serif;
                             cursor: pointer; text-align: start; }
                .cbex-head:hover { background: #e8e8e8; }
                .cbex-head small { font-weight: 400; color: #525252; }
                .cbex-chevron { margin-inline-start: auto; transition: rotate 240ms cubic-bezier(.2, 0, 0, 1); }
                .cbex[data-open='true'] .cbex-chevron { rotate: 180deg; }
                .cbex-body { overflow: hidden; padding-inline: 1rem; font-size: .75rem; }
                .cbex[data-open='true'] .cbex-body { padding-block-end: 1rem; }
                .cbex-body div { display: flex; justify-content: space-between; gap: 1rem; padding-block: .375rem;
                                 border-block-start: 1px solid #e0e0e0; }
                .cbex-body dt { color: #525252; }
                .cbex-body dd { margin: 0; font-weight: 600; }
              `}</style>
              <div className="cbex-grid">
                {REGIONS.map((r) => (
                  // data-open drives the 0fr → 1fr row transition; no height measuring.
                  <div className="cbex" data-open={!!open[r.id]} key={r.id}>
                    <button type="button" className="cbex-head" aria-expanded={!!open[r.id]} onClick={() => flip(r.id)}>
                      <span>{r.city} <small>{r.zone}</small></span>
                      <span className="cbex-chevron" aria-hidden="true">⌄</span>
                    </button>
                    <div className="cbex-body">
                      <dl>
                        <div><dt>Latency to Berlin</dt><dd>{r.latency}</dd></div>
                        <div><dt>Free capacity</dt><dd>{r.free}</dd></div>
                      </dl>
                    </div>
                  </div>
                ))}
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- RegionTiles.vue — Carbon expandable tiles: an accordion that lives in the grid -->
        <script setup lang="ts">
        import { reactive } from 'vue';
        import '@nabuxai/ui-core/css';

        const regions = [
          { id: 'fra', city: 'Frankfurt', zone: 'eu-de', latency: '24ms', free: '64 vCPU' },
          { id: 'ist', city: 'Istanbul', zone: 'eu-tr', latency: '41ms', free: '38 vCPU' },
          { id: 'dub', city: 'Dublin', zone: 'eu-ie', latency: '33ms', free: '18 vCPU' },
        ];
        const open = reactive({ ist: true });
        const flip = (id: string) => { open[id] = !open[id]; };
        </script>

        <template>
          <div class="cbex-grid">
            <!-- data-open drives the 0fr → 1fr row transition; no height measuring. -->
            <div v-for="r in regions" :key="r.id" class="cbex" :data-open="open[r.id]">
              <button type="button" class="cbex-head" :aria-expanded="open[r.id]" @click="flip(r.id)">
                <span>{{ r.city }} <small>{{ r.zone }}</small></span>
                <span class="cbex-chevron" aria-hidden="true">⌄</span>
              </button>
              <div class="cbex-body">
                <dl>
                  <div><dt>Latency to Berlin</dt><dd>{{ r.latency }}</dd></div>
                  <div><dt>Free capacity</dt><dd>{{ r.free }}</dd></div>
                </dl>
              </div>
            </div>
          </div>
        </template>

        <style scoped>
        .cbex-grid { display: grid; gap: 1rem; grid-template-columns: repeat(3, minmax(11rem, 1fr));
                     max-inline-size: 36rem; }
        .cbex { display: grid; grid-template-rows: auto 0fr; border: 1px solid #e0e0e0; background: #f4f4f4;
                transition: grid-template-rows 240ms cubic-bezier(.2, 0, 0, 1), border-color 240ms cubic-bezier(.2, 0, 0, 1); }
        .cbex[data-open='true'] { grid-template-rows: auto 1fr; border-color: #8d8d8d; }
        .cbex-head { display: flex; align-items: center; gap: .5rem; padding: .875rem 1rem; border: none;
                     background: transparent; font: 600 .875rem/1.3 'IBM Plex Sans', sans-serif;
                     cursor: pointer; text-align: start; }
        .cbex-head:hover { background: #e8e8e8; }
        .cbex-head small { font-weight: 400; color: #525252; }
        .cbex-chevron { margin-inline-start: auto; transition: rotate 240ms cubic-bezier(.2, 0, 0, 1); }
        .cbex[data-open='true'] .cbex-chevron { rotate: 180deg; }
        .cbex-body { overflow: hidden; padding-inline: 1rem; font-size: .75rem; }
        .cbex[data-open='true'] .cbex-body { padding-block-end: 1rem; }
        .cbex-body div { display: flex; justify-content: space-between; gap: 1rem; padding-block: .375rem;
                         border-block-start: 1px solid #e0e0e0; }
        .cbex-body dt { color: #525252; }
        .cbex-body dd { margin: 0; font-weight: 600; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- RegionTiles.svelte — Carbon expandable tiles: an accordion that lives in the grid -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const regions = [
            { id: 'fra', city: 'Frankfurt', zone: 'eu-de', latency: '24ms', free: '64 vCPU' },
            { id: 'ist', city: 'Istanbul', zone: 'eu-tr', latency: '41ms', free: '38 vCPU' },
            { id: 'dub', city: 'Dublin', zone: 'eu-ie', latency: '33ms', free: '18 vCPU' },
          ];
          let open = $state({ ist: true });
          const flip = (id: string) => { open[id] = !open[id]; };
        </script>

        <div class="cbex-grid">
          <!-- data-open drives the 0fr → 1fr row transition; no height measuring. -->
          {#each regions as r (r.id)}
            <div class="cbex" data-open={open[r.id]}>
              <button type="button" class="cbex-head" aria-expanded={open[r.id]} onclick={() => flip(r.id)}>
                <span>{r.city} <small>{r.zone}</small></span>
                <span class="cbex-chevron" aria-hidden="true">⌄</span>
              </button>
              <div class="cbex-body">
                <dl>
                  <div><dt>Latency to Berlin</dt><dd>{r.latency}</dd></div>
                  <div><dt>Free capacity</dt><dd>{r.free}</dd></div>
                </dl>
              </div>
            </div>
          {/each}
        </div>

        <style>
        .cbex-grid { display: grid; gap: 1rem; grid-template-columns: repeat(3, minmax(11rem, 1fr));
                     max-inline-size: 36rem; }
        .cbex { display: grid; grid-template-rows: auto 0fr; border: 1px solid #e0e0e0; background: #f4f4f4;
                transition: grid-template-rows 240ms cubic-bezier(.2, 0, 0, 1), border-color 240ms cubic-bezier(.2, 0, 0, 1); }
        .cbex[data-open='true'] { grid-template-rows: auto 1fr; border-color: #8d8d8d; }
        .cbex-head { display: flex; align-items: center; gap: .5rem; padding: .875rem 1rem; border: none;
                     background: transparent; font: 600 .875rem/1.3 'IBM Plex Sans', sans-serif;
                     cursor: pointer; text-align: start; }
        .cbex-head:hover { background: #e8e8e8; }
        .cbex-head small { font-weight: 400; color: #525252; }
        .cbex-chevron { margin-inline-start: auto; transition: rotate 240ms cubic-bezier(.2, 0, 0, 1); }
        .cbex[data-open='true'] .cbex-chevron { rotate: 180deg; }
        .cbex-body { overflow: hidden; padding-inline: 1rem; font-size: .75rem; }
        .cbex[data-open='true'] .cbex-body { padding-block-end: 1rem; }
        .cbex-body div { display: flex; justify-content: space-between; gap: 1rem; padding-block: .375rem;
                         border-block-start: 1px solid #e0e0e0; }
        .cbex-body dt { color: #525252; }
        .cbex-body dd { margin: 0; font-weight: 600; }
        </style>
        SVELTE,
        ],
    ],

    'combo-button' => [
        'title' => ['fa' => 'دکمهٔ ترکیبی', 'en' => 'Combo Button'],
        'icon' => 'arrow-right',
        'oneLiner' => [
            'fa' => 'اکشن اصلی همیشه دیده می‌شود و بدنهٔ پیکانی‌دار، اکشن‌های جایگزین را زیرش باز می‌کند؛ صرفه‌جویی در فضا به سبک تخت و مربعِ کربن.',
            'en' => 'Primary action always visible while the arrow body unfolds the alternates beneath it — Carbon’s space-saving split button with a flat, squared dropdown.',
        ],
        'js' => true,
        'docs' => 'https://react.carbondesignsystem.com/?path=/docs/components-combobutton--overview',
        'props' => [
            ['name' => 'kind', 'type' => 'string', 'default' => "'primary'", 'note' => [
                'fa' => 'primary، secondary، tertiary یا danger؛ هر دو بدنهٔ دکمه یک رنگ و یک هاور می‌گیرند.',
                'en' => 'primary, secondary, tertiary or danger; both halves share one colour and one hover.',
            ]],
            ['name' => 'direction', 'type' => 'string', 'default' => "'bottom'", 'note' => [
                'fa' => 'جهت باز شدن فهرست؛ top برای نشاندن دکمه نزدیک لبهٔ پایین صفحه.',
                'en' => 'Which way the menu opens; top when the button sits near the bottom edge.',
            ]],
            ['name' => 'primary-action', 'type' => 'click', 'default' => 'required', 'note' => [
                'fa' => 'بدنهٔ برچسب‌دار همیشه اکشن اصلی را اجرا می‌کند؛ فهرست فقط جایگزین‌ها را می‌آورد.',
                'en' => 'The labelled half always runs the primary action; the menu only lists alternates.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="cbcb" data-open="false">
            <button class="cbcb-main">استقرار</button>
            <button class="cbcb-arrow" aria-expanded="false" aria-label="اقدام‌های بیشتر">⌄</button>
            <ul class="cbcb-menu" role="menu">
                <li role="menuitem">استقرار آزمایشی</li>
                <li role="menuitem">اجرای مهاجرت‌ها</li>
            </ul>
        </div>

        <style>
        .cbcb { position: relative; display: inline-flex; font: 400 .875rem/1 'IBM Plex Sans', sans-serif; }
        .cbcb-main, .cbcb-arrow { block-size: 3rem; border: none; background: #0f62fe; color: #fff; cursor: pointer; }
        .cbcb-main { padding-inline: 1rem; }
        .cbcb-arrow { inline-size: 3rem; border-inline-start: 1px solid #ffffff40; }
        .cbcb-menu { position: absolute; inset-block-start: 100%; inset-inline: 0; margin: 0; padding: 0;
                     list-style: none; background: #f4f4f4; box-shadow: 0 2px 6px rgba(0,0,0,.2); }
        .cbcb[data-open='false'] .cbcb-menu { display: none; }
        .cbcb-menu li { padding: .65rem 1rem; border-block-end: 1px solid #e0e0e0; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="cbcb" data-open="false">
        <button class="cbcb-main">Deploy</button>
        <button class="cbcb-arrow" aria-expanded="false" aria-label="More actions">⌄</button>
        <ul class="cbcb-menu" role="menu">
        <li role="menuitem">Deploy to staging</li>
        <li role="menuitem">Run migrations</li>
        </ul>
        </div>
        
        <style>
        .cbcb { position: relative; display: inline-flex; font: 400 .875rem/1 'IBM Plex Sans', sans-serif; }
        .cbcb-main, .cbcb-arrow { block-size: 3rem; border: none; background: #0f62fe; color: #fff; cursor: pointer; }
        .cbcb-main { padding-inline: 1rem; }
        .cbcb-arrow { inline-size: 3rem; border-inline-start: 1px solid #ffffff40; }
        .cbcb-menu { position: absolute; inset-block-start: 100%; inset-inline: 0; margin: 0; padding: 0;
        list-style: none; background: #f4f4f4; box-shadow: 0 2px 6px rgba(0,0,0,.2); }
        .cbcb[data-open='false'] .cbcb-menu { display: none; }
        .cbcb-menu li { padding: .65rem 1rem; border-block-end: 1px solid #e0e0e0; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/DeployToolbar.jsx — Inertia page; renders the React snippet
        // below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import DeployCombo from '@/components/DeployCombo';

        export default function DeployToolbar() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <DeployCombo />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // DeployCombo.jsx — Carbon combo button: the primary is always one click away
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const ACTIONS = [
          { id: 'staging', label: 'Deploy to staging', msg: 'Build 842 staged on staging' },
          { id: 'migrate', label: 'Run migrations', msg: 'Migrations ran in 1.8s' },
          { id: 'cache', label: 'Clear cache', msg: 'Application cache cleared' },
        ];

        export default function DeployCombo() {
          const [open, setOpen] = useState(false);
          const [log, setLog] = useState(null);
          const rootRef = useRef(null);

          // Escape and an outside tap fold the menu back — Carbon's dismiss contract.
          useEffect(() => {
            const onKey = (e) => e.key === 'Escape' && setOpen(false);
            const onTap = (e) => { if (!rootRef.current?.contains(e.target)) setOpen(false); };
            window.addEventListener('keydown', onKey);
            window.addEventListener('pointerdown', onTap);
            return () => {
              window.removeEventListener('keydown', onKey);
              window.removeEventListener('pointerdown', onTap);
            };
          }, []);

          const run = (msg) => { setLog(msg); setOpen(false); };

          return (
            <>
              <style>{`
                .cbcb { position: relative; display: inline-flex; font: 400 .875rem/1 'IBM Plex Sans', sans-serif; }
                .cbcb-main, .cbcb-arrow { block-size: 3rem; border: none; background: #0f62fe; color: #fff; cursor: pointer; }
                .cbcb-main { padding-inline: 1rem; }
                .cbcb-main:hover, .cbcb-arrow:hover { background: #0353e9; }
                .cbcb-arrow { display: grid; place-items: center; inline-size: 3rem; border-inline-start: 1px solid #ffffff40; }
                .cbcb-menu { position: absolute; inset-block-start: calc(100% + 2px); inset-inline: 0; z-index: 5; margin: 0; padding: 0;
                             list-style: none; background: #f4f4f4; box-shadow: 0 2px 6px rgba(0, 0, 0, .2); }
                .cbcb[data-open='false'] .cbcb-menu { display: none; }
                .cbcb-menu button { inline-size: 100%; padding: .65rem 1rem; border: none; border-block-end: 1px solid #e0e0e0;
                                    background: transparent; text-align: start; cursor: pointer;
                                    font: 400 .875rem/1.3 'IBM Plex Sans', sans-serif; }
                .cbcb-menu button:hover { background: #e8e8e8; }
                .cbcb-log { max-inline-size: 24rem; margin-block-start: .75rem; padding: .5rem 1rem; background: #f4f4f4;
                            border-block-start: 2px solid #0f62fe; font-size: .875rem; }
              `}</style>
              <div className="cbcb" data-open={open} ref={rootRef}>
                <button type="button" className="cbcb-main" onClick={() => run('Build 842 deployed to production')}>
                  Deploy
                </button>
                <button type="button" className="cbcb-arrow" aria-expanded={open} aria-haspopup="menu"
                  aria-label="More deploy actions" onClick={() => setOpen(!open)}>⌄</button>
                <ul className="cbcb-menu" role="menu" aria-label="Alternate deploy actions">
                  {ACTIONS.map((a) => (
                    <li role="none" key={a.id}>
                      <button type="button" role="menuitem" onClick={() => run(a.msg)}>{a.label}</button>
                    </li>
                  ))}
                </ul>
              </div>
              <p className="cbcb-log" role="status">{log ?? 'Pick an action — the log lands here.'}</p>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- DeployCombo.vue — Carbon combo button: the primary is always one click away -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const actions = [
          { id: 'staging', label: 'Deploy to staging', msg: 'Build 842 staged on staging' },
          { id: 'migrate', label: 'Run migrations', msg: 'Migrations ran in 1.8s' },
          { id: 'cache', label: 'Clear cache', msg: 'Application cache cleared' },
        ];
        const open = ref(false);
        const log = ref<string | null>(null);
        const root = ref<HTMLElement | null>(null);

        const run = (msg: string) => { log.value = msg; open.value = false; };
        const onKey = (e: KeyboardEvent) => { if (e.key === 'Escape') open.value = false; };
        const onTap = (e: PointerEvent) => { if (!root.value?.contains(e.target as Node)) open.value = false; };

        onMounted(() => {
          window.addEventListener('keydown', onKey);
          window.addEventListener('pointerdown', onTap);
        });
        onBeforeUnmount(() => {
          window.removeEventListener('keydown', onKey);
          window.removeEventListener('pointerdown', onTap);
        });
        </script>

        <template>
          <div ref="root" class="cbcb" :data-open="open">
            <button type="button" class="cbcb-main" @click="run('Build 842 deployed to production')">Deploy</button>
            <button type="button" class="cbcb-arrow" :aria-expanded="open" aria-haspopup="menu"
              aria-label="More deploy actions" @click="open = !open">⌄</button>
            <ul class="cbcb-menu" role="menu" aria-label="Alternate deploy actions">
              <li v-for="a in actions" :key="a.id" role="none">
                <button type="button" role="menuitem" @click="run(a.msg)">{{ a.label }}</button>
              </li>
            </ul>
          </div>
          <p class="cbcb-log" role="status">{{ log ?? 'Pick an action — the log lands here.' }}</p>
        </template>

        <style scoped>
        .cbcb { position: relative; display: inline-flex; font: 400 .875rem/1 'IBM Plex Sans', sans-serif; }
        .cbcb-main, .cbcb-arrow { block-size: 3rem; border: none; background: #0f62fe; color: #fff; cursor: pointer; }
        .cbcb-main { padding-inline: 1rem; }
        .cbcb-main:hover, .cbcb-arrow:hover { background: #0353e9; }
        .cbcb-arrow { display: grid; place-items: center; inline-size: 3rem; border-inline-start: 1px solid #ffffff40; }
        .cbcb-menu { position: absolute; inset-block-start: calc(100% + 2px); inset-inline: 0; z-index: 5; margin: 0; padding: 0;
                     list-style: none; background: #f4f4f4; box-shadow: 0 2px 6px rgba(0, 0, 0, .2); }
        .cbcb[data-open='false'] .cbcb-menu { display: none; }
        .cbcb-menu button { inline-size: 100%; padding: .65rem 1rem; border: none; border-block-end: 1px solid #e0e0e0;
                            background: transparent; text-align: start; cursor: pointer;
                            font: 400 .875rem/1.3 'IBM Plex Sans', sans-serif; }
        .cbcb-menu button:hover { background: #e8e8e8; }
        .cbcb-log { max-inline-size: 24rem; margin-block-start: .75rem; padding: .5rem 1rem; background: #f4f4f4;
                    border-block-start: 2px solid #0f62fe; font-size: .875rem; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- DeployCombo.svelte — Carbon combo button: the primary is always one click away -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          const actions = [
            { id: 'staging', label: 'Deploy to staging', msg: 'Build 842 staged on staging' },
            { id: 'migrate', label: 'Run migrations', msg: 'Migrations ran in 1.8s' },
            { id: 'cache', label: 'Clear cache', msg: 'Application cache cleared' },
          ];
          let open = $state(false);
          let log = $state<string | null>(null);
          let root: HTMLElement;

          const run = (msg: string) => { log = msg; open = false; };

          onMount(() => {
            const onKey = (e: KeyboardEvent) => { if (e.key === 'Escape') open = false; };
            const onTap = (e: PointerEvent) => { if (!root.contains(e.target as Node)) open = false; };
            window.addEventListener('keydown', onKey);
            window.addEventListener('pointerdown', onTap);
            return () => {
              window.removeEventListener('keydown', onKey);
              window.removeEventListener('pointerdown', onTap);
            };
          });
        </script>

        <div bind:this={root} class="cbcb" data-open={open}>
          <button type="button" class="cbcb-main" onclick={() => run('Build 842 deployed to production')}>Deploy</button>
          <button type="button" class="cbcb-arrow" aria-expanded={open} aria-haspopup="menu"
            aria-label="More deploy actions" onclick={() => (open = !open)}>⌄</button>
          <ul class="cbcb-menu" role="menu" aria-label="Alternate deploy actions">
            {#each actions as a (a.id)}
              <li role="none">
                <button type="button" role="menuitem" onclick={() => run(a.msg)}>{a.label}</button>
              </li>
            {/each}
          </ul>
        </div>
        <p class="cbcb-log" role="status">{log ?? 'Pick an action — the log lands here.'}</p>

        <style>
        .cbcb { position: relative; display: inline-flex; font: 400 .875rem/1 'IBM Plex Sans', sans-serif; }
        .cbcb-main, .cbcb-arrow { block-size: 3rem; border: none; background: #0f62fe; color: #fff; cursor: pointer; }
        .cbcb-main { padding-inline: 1rem; }
        .cbcb-main:hover, .cbcb-arrow:hover { background: #0353e9; }
        .cbcb-arrow { display: grid; place-items: center; inline-size: 3rem; border-inline-start: 1px solid #ffffff40; }
        .cbcb-menu { position: absolute; inset-block-start: calc(100% + 2px); inset-inline: 0; z-index: 5; margin: 0; padding: 0;
                     list-style: none; background: #f4f4f4; box-shadow: 0 2px 6px rgba(0, 0, 0, .2); }
        .cbcb[data-open='false'] .cbcb-menu { display: none; }
        .cbcb-menu button { inline-size: 100%; padding: .65rem 1rem; border: none; border-block-end: 1px solid #e0e0e0;
                            background: transparent; text-align: start; cursor: pointer;
                            font: 400 .875rem/1.3 'IBM Plex Sans', sans-serif; }
        .cbcb-menu button:hover { background: #e8e8e8; }
        .cbcb-log { max-inline-size: 24rem; margin-block-start: .75rem; padding: .5rem 1rem; background: #f4f4f4;
                    border-block-start: 2px solid #0f62fe; font-size: .875rem; }
        </style>
        SVELTE,
        ],
    ],

    'overflow-menu' => [
        'title' => ['fa' => 'منوی سرریز', 'en' => 'Overflow Menu'],
        'icon' => 'menu',
        'oneLiner' => [
            'fa' => 'سه‌نقطهٔ همیشه‌حاضر که فهرست اکشن‌های تختِ بی‌حاشیه را زیر خودش باز می‌کند و اکشن مخرب را ته فهرست قرنطینه کرده است.',
            'en' => 'The ever-present kebab: borderless flat items snap open beneath a 3-dot button, with the destructive action quarantined at the bottom.',
        ],
        'js' => true,
        'docs' => 'https://react.carbondesignsystem.com/?path=/docs/components-overflowmenu--overview',
        'props' => [
            ['name' => 'flipped', 'type' => 'boolean', 'default' => 'false', 'note' => [
                'fa' => 'منوی نزدیک لبهٔ صفحه را با flipped به سمت دیگر لنگر کنید تا بیرون نریزد.',
                'en' => 'Anchor the menu to the other side with flipped so it never spills past the edge.',
            ]],
            ['name' => 'danger', 'type' => 'boolean', 'default' => 'false', 'note' => [
                'fa' => 'آیتم مخرب همیشه آخرین ردیف است و با قرمز #da1e28 خوانده می‌شود.',
                'en' => 'The destructive item is always the last row, read in red #da1e28.',
            ]],
            ['name' => 'size', 'type' => 'string', 'default' => "'md'", 'note' => [
                'fa' => 'sm، md یا lg؛ دکمهٔ سه‌نقطه هم‌اندازهٔ فهرستش بزرگ می‌شود.',
                'en' => 'sm, md or lg; the kebab button scales with its menu.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="cbov" data-open="false">
            <button class="cbov-btn" aria-expanded="false" aria-label="گزینه‌ها">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="5.5" r="1.7"/>…</svg>
            </button>
            <ul class="cbov-menu" role="menu">
                <li role="menuitem">تغییر نام</li>
                <li role="menuitem">دانلود</li>
                <li role="menuitem" data-danger>حذف</li>
            </ul>
        </div>

        <style>
        .cbov { position: relative; }
        .cbov-btn { inline-size: 2rem; aspect-ratio: 1; border: none; background: transparent; cursor: pointer; }
        .cbov[data-open='true'] .cbov-btn { background: #e0e0e0; }
        .cbov-menu { position: absolute; inset-block-start: calc(100% + 2px); inset-inline-end: 0; min-inline-size: 10rem;
                     margin: 0; padding: 0; list-style: none; background: #f4f4f4;
                     box-shadow: 0 2px 6px rgba(0,0,0,.2); }
        .cbov[data-open='false'] .cbov-menu { display: none; }
        .cbov-menu li { padding: .55rem 1rem; border-block-end: 1px solid #e0e0e0; font: 400 .875rem/1.3 'IBM Plex Sans'; }
        .cbov-menu [data-danger] { color: #da1e28; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="cbov" data-open="false">
        <button class="cbov-btn" aria-expanded="false" aria-label="Options">
        <svg viewBox="0 0 24 24"><circle cx="12" cy="5.5" r="1.7"/>…</svg>
        </button>
        <ul class="cbov-menu" role="menu">
        <li role="menuitem">Rename</li>
        <li role="menuitem">Download</li>
        <li role="menuitem" data-danger>Delete</li>
        </ul>
        </div>
        
        <style>
        .cbov { position: relative; }
        .cbov-btn { inline-size: 2rem; aspect-ratio: 1; border: none; background: transparent; cursor: pointer; }
        .cbov[data-open='true'] .cbov-btn { background: #e0e0e0; }
        .cbov-menu { position: absolute; inset-block-start: calc(100% + 2px); inset-inline-end: 0; min-inline-size: 10rem;
        margin: 0; padding: 0; list-style: none; background: #f4f4f4;
        box-shadow: 0 2px 6px rgba(0,0,0,.2); }
        .cbov[data-open='false'] .cbov-menu { display: none; }
        .cbov-menu li { padding: .55rem 1rem; border-block-end: 1px solid #e0e0e0; font: 400 .875rem/1.3 'IBM Plex Sans'; }
        .cbov-menu [data-danger] { color: #da1e28; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Files.jsx — Inertia page; renders the React snippet
        // below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import FileKebab from '@/components/FileKebab';

        export default function Files() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <FileKebab />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // FileKebab.jsx — Carbon overflow menu: flat items, destructive row quarantined last
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const INITIAL = [
          { id: 1, name: 'sprint-24-metrics.csv', meta: 'Edited today, 14:20', size: '240KB' },
          { id: 2, name: 'onboarding-guide.pdf', meta: 'Uploaded by Lena, Oct 2', size: '1.2MB' },
          { id: 3, name: 'istanbul-launch.key', meta: 'Edited yesterday, 09:48', size: '18KB' },
        ];

        export default function FileKebab() {
          const [files, setFiles] = useState(INITIAL);
          const [openId, setOpenId] = useState(null);
          const [toast, setToast] = useState(null);
          const rootRef = useRef(null);

          useEffect(() => {
            const onKey = (e) => e.key === 'Escape' && setOpenId(null);
            const onTap = (e) => { if (!rootRef.current?.contains(e.target)) setOpenId(null); };
            window.addEventListener('keydown', onKey);
            window.addEventListener('pointerdown', onTap);
            return () => {
              window.removeEventListener('keydown', onKey);
              window.removeEventListener('pointerdown', onTap);
            };
          }, []);

          const say = (msg) => { setToast(msg); setTimeout(() => setToast(null), 6000); };
          // Delete really removes the row, then reports through the toast.
          const remove = (file) => {
            setOpenId(null);
            setFiles((f) => f.filter((x) => x.id !== file.id));
            say(`Deleted: ${file.name}`);
          };

          return (
            <>
              <style>{`
                .cbov-list { max-inline-size: 26rem; border-block-end: 1px solid #e0e0e0;
                             font: 400 .875rem/1.4 'IBM Plex Sans', sans-serif; }
                .cbov-file { position: relative; display: flex; align-items: center; gap: .875rem; padding: .75rem 1rem;
                             border-block-start: 1px solid #e0e0e0; background: #f4f4f4; }
                .cbov-file b { display: block; font-weight: 600; }
                .cbov-file small { font-size: .75rem; color: #525252; }
                .cbov-size { margin-inline-start: auto; font: 400 .75rem/1 'IBM Plex Mono', Menlo, monospace; color: #525252; }
                .cbov { position: relative; }
                .cbov-btn { display: grid; place-items: center; inline-size: 2rem; aspect-ratio: 1; border: none;
                            background: transparent; cursor: pointer; }
                .cbov[data-open='true'] .cbov-btn { background: #d1d1d1; }
                .cbov-menu { position: absolute; inset-block-start: calc(100% + 2px); inset-inline-end: 0; z-index: 6;
                             min-inline-size: 10rem; margin: 0; padding: 0; list-style: none; background: #f4f4f4;
                             box-shadow: 0 2px 6px rgba(0, 0, 0, .25); }
                .cbov[data-open='false'] .cbov-menu { display: none; }
                .cbov-item { inline-size: 100%; padding: .625rem 1rem; border: none; border-block-end: 1px solid #e0e0e0;
                             background: transparent; text-align: start; cursor: pointer;
                             font: 400 .875rem/1.3 'IBM Plex Sans', sans-serif; }
                .cbov-item:hover { background: #e8e8e8; }
                .cbov-item[data-danger] { color: #da1e28; }
                .cbov-toast { margin: .75rem 0 0; padding: .5rem 1rem; background: #f4f4f4;
                              border-block-start: 2px solid #da1e28; }
              `}</style>
              <div className="cbov-list" ref={rootRef}>
                {files.map((file) => (
                  <div className="cbov-file" key={file.id}>
                    <span><b>{file.name}</b><small>{file.meta}</small></span>
                    <span className="cbov-size">{file.size}</span>
                    <div className="cbov" data-open={openId === file.id}>
                      <button type="button" className="cbov-btn" aria-haspopup="menu" aria-expanded={openId === file.id}
                        aria-label={`Actions for ${file.name}`}
                        onClick={() => setOpenId(openId === file.id ? null : file.id)}>⋮</button>
                      <ul className="cbov-menu" role="menu">
                        <li role="none"><button type="button" role="menuitem" className="cbov-item"
                          onClick={() => { setOpenId(null); say('Download started'); }}>Download</button></li>
                        <li role="none"><button type="button" role="menuitem" className="cbov-item"
                          onClick={() => { setOpenId(null); say('Share link copied'); }}>Share</button></li>
                        {/* The destructive action stays quarantined at the bottom. */}
                        <li role="none"><button type="button" role="menuitem" className="cbov-item" data-danger
                          onClick={() => remove(file)}>Delete</button></li>
                      </ul>
                    </div>
                  </div>
                ))}
                {toast && <p className="cbov-toast" role="status">{toast}</p>}
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- FileKebab.vue — Carbon overflow menu: flat items, destructive row quarantined last -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        interface FileRow { id: number; name: string; meta: string; size: string }
        const files = ref<FileRow[]>([
          { id: 1, name: 'sprint-24-metrics.csv', meta: 'Edited today, 14:20', size: '240KB' },
          { id: 2, name: 'onboarding-guide.pdf', meta: 'Uploaded by Lena, Oct 2', size: '1.2MB' },
          { id: 3, name: 'istanbul-launch.key', meta: 'Edited yesterday, 09:48', size: '18KB' },
        ]);
        const openId = ref<number | null>(null);
        const toast = ref<string | null>(null);
        const root = ref<HTMLElement | null>(null);

        const say = (msg: string) => { toast.value = msg; setTimeout(() => (toast.value = null), 6000); };
        // Delete really removes the row, then reports through the toast.
        const remove = (file: FileRow) => {
          openId.value = null;
          files.value = files.value.filter((f) => f.id !== file.id);
          say(`Deleted: ${file.name}`);
        };
        const onKey = (e: KeyboardEvent) => { if (e.key === 'Escape') openId.value = null; };
        const onTap = (e: PointerEvent) => { if (!root.value?.contains(e.target as Node)) openId.value = null; };

        onMounted(() => {
          window.addEventListener('keydown', onKey);
          window.addEventListener('pointerdown', onTap);
        });
        onBeforeUnmount(() => {
          window.removeEventListener('keydown', onKey);
          window.removeEventListener('pointerdown', onTap);
        });
        </script>

        <template>
          <div ref="root" class="cbov-list">
            <div v-for="file in files" :key="file.id" class="cbov-file">
              <span><b>{{ file.name }}</b><small>{{ file.meta }}</small></span>
              <span class="cbov-size">{{ file.size }}</span>
              <div class="cbov" :data-open="openId === file.id">
                <button type="button" class="cbov-btn" aria-haspopup="menu" :aria-expanded="openId === file.id"
                  :aria-label="`Actions for ${file.name}`" @click="openId = openId === file.id ? null : file.id">⋮</button>
                <ul class="cbov-menu" role="menu">
                  <li role="none"><button type="button" role="menuitem" class="cbov-item"
                    @click="openId = null; say('Download started')">Download</button></li>
                  <li role="none"><button type="button" role="menuitem" class="cbov-item"
                    @click="openId = null; say('Share link copied')">Share</button></li>
                  <!-- The destructive action stays quarantined at the bottom. -->
                  <li role="none"><button type="button" role="menuitem" class="cbov-item" data-danger
                    @click="remove(file)">Delete</button></li>
                </ul>
              </div>
            </div>
            <p v-if="toast" class="cbov-toast" role="status">{{ toast }}</p>
          </div>
        </template>

        <style scoped>
        .cbov-list { max-inline-size: 26rem; border-block-end: 1px solid #e0e0e0;
                     font: 400 .875rem/1.4 'IBM Plex Sans', sans-serif; }
        .cbov-file { position: relative; display: flex; align-items: center; gap: .875rem; padding: .75rem 1rem;
                     border-block-start: 1px solid #e0e0e0; background: #f4f4f4; }
        .cbov-file b { display: block; font-weight: 600; }
        .cbov-file small { font-size: .75rem; color: #525252; }
        .cbov-size { margin-inline-start: auto; font: 400 .75rem/1 'IBM Plex Mono', Menlo, monospace; color: #525252; }
        .cbov { position: relative; }
        .cbov-btn { display: grid; place-items: center; inline-size: 2rem; aspect-ratio: 1; border: none;
                    background: transparent; cursor: pointer; }
        .cbov[data-open='true'] .cbov-btn { background: #d1d1d1; }
        .cbov-menu { position: absolute; inset-block-start: calc(100% + 2px); inset-inline-end: 0; z-index: 6;
                     min-inline-size: 10rem; margin: 0; padding: 0; list-style: none; background: #f4f4f4;
                     box-shadow: 0 2px 6px rgba(0, 0, 0, .25); }
        .cbov[data-open='false'] .cbov-menu { display: none; }
        .cbov-item { inline-size: 100%; padding: .625rem 1rem; border: none; border-block-end: 1px solid #e0e0e0;
                     background: transparent; text-align: start; cursor: pointer;
                     font: 400 .875rem/1.3 'IBM Plex Sans', sans-serif; }
        .cbov-item:hover { background: #e8e8e8; }
        .cbov-item[data-danger] { color: #da1e28; }
        .cbov-toast { margin: .75rem 0 0; padding: .5rem 1rem; background: #f4f4f4;
                      border-block-start: 2px solid #da1e28; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- FileKebab.svelte — Carbon overflow menu: flat items, destructive row quarantined last -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          interface FileRow { id: number; name: string; meta: string; size: string }
          let files = $state<FileRow[]>([
            { id: 1, name: 'sprint-24-metrics.csv', meta: 'Edited today, 14:20', size: '240KB' },
            { id: 2, name: 'onboarding-guide.pdf', meta: 'Uploaded by Lena, Oct 2', size: '1.2MB' },
            { id: 3, name: 'istanbul-launch.key', meta: 'Edited yesterday, 09:48', size: '18KB' },
          ]);
          let openId = $state<number | null>(null);
          let toast = $state<string | null>(null);
          let root: HTMLElement;

          const say = (msg: string) => { toast = msg; setTimeout(() => (toast = null), 6000); };
          // Delete really removes the row, then reports through the toast.
          const remove = (file: FileRow) => {
            openId = null;
            files = files.filter((f) => f.id !== file.id);
            say(`Deleted: ${file.name}`);
          };

          onMount(() => {
            const onKey = (e: KeyboardEvent) => { if (e.key === 'Escape') openId = null; };
            const onTap = (e: PointerEvent) => { if (!root.contains(e.target as Node)) openId = null; };
            window.addEventListener('keydown', onKey);
            window.addEventListener('pointerdown', onTap);
            return () => {
              window.removeEventListener('keydown', onKey);
              window.removeEventListener('pointerdown', onTap);
            };
          });
        </script>

        <div bind:this={root} class="cbov-list">
          {#each files as file (file.id)}
            <div class="cbov-file">
              <span><b>{file.name}</b><small>{file.meta}</small></span>
              <span class="cbov-size">{file.size}</span>
              <div class="cbov" data-open={openId === file.id}>
                <button type="button" class="cbov-btn" aria-haspopup="menu" aria-expanded={openId === file.id}
                  aria-label="Actions for {file.name}"
                  onclick={() => (openId = openId === file.id ? null : file.id)}>⋮</button>
                <ul class="cbov-menu" role="menu">
                  <li role="none"><button type="button" role="menuitem" class="cbov-item"
                    onclick={() => { openId = null; say('Download started'); }}>Download</button></li>
                  <li role="none"><button type="button" role="menuitem" class="cbov-item"
                    onclick={() => { openId = null; say('Share link copied'); }}>Share</button></li>
                  <!-- The destructive action stays quarantined at the bottom. -->
                  <li role="none"><button type="button" role="menuitem" class="cbov-item" data-danger
                    onclick={() => remove(file)}>Delete</button></li>
                </ul>
              </div>
            </div>
          {/each}
          {#if toast}<p class="cbov-toast" role="status">{toast}</p>{/if}
        </div>

        <style>
        .cbov-list { max-inline-size: 26rem; border-block-end: 1px solid #e0e0e0;
                     font: 400 .875rem/1.4 'IBM Plex Sans', sans-serif; }
        .cbov-file { position: relative; display: flex; align-items: center; gap: .875rem; padding: .75rem 1rem;
                     border-block-start: 1px solid #e0e0e0; background: #f4f4f4; }
        .cbov-file b { display: block; font-weight: 600; }
        .cbov-file small { font-size: .75rem; color: #525252; }
        .cbov-size { margin-inline-start: auto; font: 400 .75rem/1 'IBM Plex Mono', Menlo, monospace; color: #525252; }
        .cbov { position: relative; }
        .cbov-btn { display: grid; place-items: center; inline-size: 2rem; aspect-ratio: 1; border: none;
                    background: transparent; cursor: pointer; }
        .cbov[data-open='true'] .cbov-btn { background: #d1d1d1; }
        .cbov-menu { position: absolute; inset-block-start: calc(100% + 2px); inset-inline-end: 0; z-index: 6;
                     min-inline-size: 10rem; margin: 0; padding: 0; list-style: none; background: #f4f4f4;
                     box-shadow: 0 2px 6px rgba(0, 0, 0, .25); }
        .cbov[data-open='false'] .cbov-menu { display: none; }
        .cbov-item { inline-size: 100%; padding: .625rem 1rem; border: none; border-block-end: 1px solid #e0e0e0;
                     background: transparent; text-align: start; cursor: pointer;
                     font: 400 .875rem/1.3 'IBM Plex Sans', sans-serif; }
        .cbov-item:hover { background: #e8e8e8; }
        .cbov-item[data-danger] { color: #da1e28; }
        .cbov-toast { margin: .75rem 0 0; padding: .5rem 1rem; background: #f4f4f4;
                      border-block-start: 2px solid #da1e28; }
        </style>
        SVELTE,
        ],
    ],

    'ai-slug' => [
        'title' => ['fa' => 'لیبل هوش مصنوعی (Slug)', 'en' => 'AI Label (Slug)'],
        'icon' => 'sparkles',
        'oneLiner' => [
            'fa' => 'نشان کوچک هوش مصنوعی که به محتوای تولیدی می‌چسبد و با کلیک، توضیح مدل و اکشن‌ها را پاپ‌آپ می‌کند؛ امضای عصر AI در کربن.',
            'en' => 'Carbon’s AI badge pinned to generated content — click it to pop open which model made this, plus actions; the signature of Carbon’s AI era.',
        ],
        'js' => true,
        'docs' => 'https://react.carbondesignsystem.com/?path=/docs/components-ailabel--overview',
        'props' => [
            ['name' => 'kind', 'type' => 'string', 'default' => "'default'", 'note' => [
                'fa' => 'default یا inline؛ حالت inline همان نشان را درون سرخط‌ها و متن‌ها می‌نشاند.',
                'en' => 'default or inline; the inline kind sits the same badge inside headings and copy.',
            ]],
            ['name' => 'size', 'type' => 'string', 'default' => "'xs · sm · md'", 'note' => [
                'fa' => 'سه اندازهٔ رسمی نشان؛ کوچکش برای جاگیری کنار متن و درشتش برای سطح‌های خالی.',
                'en' => 'The three official badge sizes; small to hug text, large to mark open surfaces.',
            ]],
            ['name' => 'revert', 'type' => 'action', 'default' => 'optional', 'note' => [
                'fa' => 'پاپ‌آپ نشان می‌تواند دکمهٔ «بازگرداندن» داشته باشد؛ کل محتوای تولیدشده یک‌کلیکی به نسخهٔ انسانی برمی‌گردد.',
                'en' => 'The badge popover can carry a revert button; one click rolls the generated content back to the human version.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="cbai">
            <button class="cbai-slug" aria-label="تولیدشده با هوش مصنوعی">AI</button>
            <div class="cbai-pop" role="dialog" data-open="false">
                <b>پاسخ پیشنهادی مدل</b>
                <small>نابو-پاسخ ۳ · اطمینان ۹۲٪</small>
                <footer><button>واگرد</button></footer>
            </div>
        </div>

        <style>
        .cbai { position: relative; display: inline-flex; }
        .cbai-slug { inline-size: 1.5rem; aspect-ratio: 1; border: none; border-radius: 999px;
                     background: linear-gradient(120deg, #8a3ffc, #d02670, #1192e8); color: #fff;
                     font: 600 .65rem/1 'IBM Plex Sans'; cursor: pointer; }
        .cbai-pop { position: absolute; inset-block-start: calc(100% + 8px); inset-inline-end: 0;
                    inline-size: 16rem; padding: 1rem; background: #f4f4f4; }
        .cbai-pop[data-open='false'] { display: none; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="cbai">
        <button class="cbai-slug" aria-label="Generated with AI">AI</button>
        <div class="cbai-pop" role="dialog" data-open="false">
        <b>Suggested reply by the model</b>
        <small>nabu-reply-3 · 92% confidence</small>
        <footer><button>Revert</button></footer>
        </div>
        </div>
        
        <style>
        .cbai { position: relative; display: inline-flex; }
        .cbai-slug { inline-size: 1.5rem; aspect-ratio: 1; border: none; border-radius: 999px;
        background: linear-gradient(120deg, #8a3ffc, #d02670, #1192e8); color: #fff;
        font: 600 .65rem/1 'IBM Plex Sans'; cursor: pointer; }
        .cbai-pop { position: absolute; inset-block-start: calc(100% + 8px); inset-inline-end: 0;
        inline-size: 16rem; padding: 1rem; background: #f4f4f4; }
        .cbai-pop[data-open='false'] { display: none; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Ticket.jsx — Inertia page; renders the React snippet
        // below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import AiDraftBadge from '@/components/AiDraftBadge';

        export default function Ticket() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <AiDraftBadge />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // AiDraftBadge.jsx — Carbon AI label: the gradient badge proves who wrote this
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const DRAFTS = {
          ai: 'Hello Lara, yes — the October sales report was ready by noon. The reporting worker briefly stopped responding, which we have fixed; the CSV export is attached. — Nabu support',
          human: 'You are right — the report did land a little late and it is attached to this ticket now. Thanks for flagging it. — Nabu support',
        };

        export default function AiDraftBadge() {
          const [pop, setPop] = useState(false);
          const [mode, setMode] = useState('ai');
          const boxRef = useRef(null);

          useEffect(() => {
            const onKey = (e) => e.key === 'Escape' && setPop(false);
            const onTap = (e) => { if (!boxRef.current?.contains(e.target)) setPop(false); };
            window.addEventListener('keydown', onKey);
            window.addEventListener('pointerdown', onTap);
            return () => {
              window.removeEventListener('keydown', onKey);
              window.removeEventListener('pointerdown', onTap);
            };
          }, []);

          return (
            <>
              <style>{`
                .cbai-draft { max-inline-size: 30rem; background: #f4f4f4;
                              font: 400 .875rem/1.6 'IBM Plex Sans', sans-serif; }
                .cbai-draft-head { display: flex; align-items: center; gap: .625rem; padding: .75rem 1rem;
                                   border-block-end: 1px solid #e0e0e0; }
                .cbai-draft-head b { font-weight: 600; }
                .cbai-draft-head small { margin-inline-start: auto; font-size: .75rem; color: #525252; }
                .cbai-inline { position: relative; display: inline-flex; }
                .cbai-slug { display: inline-grid; place-items: center; inline-size: 1.5rem; block-size: 1.5rem; border: none;
                             border-radius: 50%; color: #fff; cursor: pointer; font: 600 .625rem/1 'IBM Plex Sans', sans-serif;
                             background: linear-gradient(90deg, #8a3ffc, #d02670, #1192e8); }
                .cbai-pop { position: absolute; inset-block-start: calc(100% + 8px); inset-inline-end: 0; z-index: 7;
                            inline-size: 16rem; padding: 1rem; background: #f4f4f4; box-shadow: 0 2px 6px rgba(0, 0, 0, .25); }
                .cbai-pop[data-open='false'] { display: none; }
                .cbai-pop b { font-weight: 600; }
                .cbai-pop div { display: flex; justify-content: space-between; gap: 1rem; padding-block: .375rem;
                                border-block-end: 1px solid #e0e0e0; font-size: .75rem; }
                .cbai-pop dt { color: #525252; }
                .cbai-pop dd { margin: 0; font-weight: 600; font-family: 'IBM Plex Mono', Menlo, monospace; }
                .cbai-pop-foot { display: flex; gap: .5rem; padding-block-start: .625rem; border-block-end: none; }
                .cbai-act { block-size: 2rem; padding-inline: .875rem; border: none; background: transparent;
                            color: #0f62fe; font: 400 .8125rem/1 'IBM Plex Sans', sans-serif; cursor: pointer; }
                .cbai-draft-body { padding: 1rem; }
                .cbai-draft-body[data-ai='true'] { border-inline-start: 3px solid #d02670; }
                .cbai-draft-foot { display: flex; padding: .625rem 1rem; border-block-start: 1px solid #e0e0e0; }
              `}</style>
              <div className="cbai-draft" ref={boxRef}>
                <div className="cbai-draft-head">
                  <b>Ticket #2841 — draft reply</b>
                  <span className="cbai-inline">
                    <button type="button" className="cbai-slug" aria-label="Generated with AI — details"
                      aria-expanded={pop} onClick={() => setPop(!pop)}>AI</button>
                    <div className="cbai-pop" role="dialog" aria-label="AI details" data-open={pop}>
                      <b>AI-generated content</b>
                      <dl>
                        <div><dt>Model</dt><dd>nabu-reply-3</dd></div>
                        <div><dt>Confidence</dt><dd>92%</dd></div>
                      </dl>
                      <div className="cbai-pop-foot">
                        {/* One click rolls the draft back to the human version. */}
                        <button type="button" className="cbai-act"
                          onClick={() => { setPop(false); setMode('human'); }}>Revert to human draft</button>
                      </div>
                    </div>
                  </span>
                  <small>{mode === 'ai' ? 'suggested by nabu-reply-3' : 'human draft'}</small>
                </div>
                <p className="cbai-draft-body" data-ai={mode === 'ai'}>{DRAFTS[mode]}</p>
                {mode === 'human' && (
                  <div className="cbai-draft-foot">
                    <button type="button" className="cbai-act" onClick={() => setMode('ai')}>Regenerate with AI</button>
                  </div>
                )}
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- AiDraftBadge.vue — Carbon AI label: the gradient badge proves who wrote this -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const drafts = {
          ai: 'Hello Lara, yes — the October sales report was ready by noon. The reporting worker briefly stopped responding, which we have fixed; the CSV export is attached. — Nabu support',
          human: 'You are right — the report did land a little late and it is attached to this ticket now. Thanks for flagging it. — Nabu support',
        };
        const pop = ref(false);
        const mode = ref<'ai' | 'human'>('ai');
        const box = ref<HTMLElement | null>(null);

        const onKey = (e: KeyboardEvent) => { if (e.key === 'Escape') pop.value = false; };
        const onTap = (e: PointerEvent) => { if (!box.value?.contains(e.target as Node)) pop.value = false; };
        onMounted(() => {
          window.addEventListener('keydown', onKey);
          window.addEventListener('pointerdown', onTap);
        });
        onBeforeUnmount(() => {
          window.removeEventListener('keydown', onKey);
          window.removeEventListener('pointerdown', onTap);
        });
        </script>

        <template>
          <div ref="box" class="cbai-draft">
            <div class="cbai-draft-head">
              <b>Ticket #2841 — draft reply</b>
              <span class="cbai-inline">
                <button type="button" class="cbai-slug" aria-label="Generated with AI — details"
                  :aria-expanded="pop" @click="pop = !pop">AI</button>
                <div class="cbai-pop" role="dialog" aria-label="AI details" :data-open="pop">
                  <b>AI-generated content</b>
                  <dl>
                    <div><dt>Model</dt><dd>nabu-reply-3</dd></div>
                    <div><dt>Confidence</dt><dd>92%</dd></div>
                  </dl>
                  <div class="cbai-pop-foot">
                    <!-- One click rolls the draft back to the human version. -->
                    <button type="button" class="cbai-act" @click="pop = false; mode = 'human'">Revert to human draft</button>
                  </div>
                </div>
              </span>
              <small>{{ mode === 'ai' ? 'suggested by nabu-reply-3' : 'human draft' }}</small>
            </div>
            <p class="cbai-draft-body" :data-ai="mode === 'ai'">{{ drafts[mode] }}</p>
            <div v-if="mode === 'human'" class="cbai-draft-foot">
              <button type="button" class="cbai-act" @click="mode = 'ai'">Regenerate with AI</button>
            </div>
          </div>
        </template>

        <style scoped>
        .cbai-draft { max-inline-size: 30rem; background: #f4f4f4;
                      font: 400 .875rem/1.6 'IBM Plex Sans', sans-serif; }
        .cbai-draft-head { display: flex; align-items: center; gap: .625rem; padding: .75rem 1rem;
                           border-block-end: 1px solid #e0e0e0; }
        .cbai-draft-head b { font-weight: 600; }
        .cbai-draft-head small { margin-inline-start: auto; font-size: .75rem; color: #525252; }
        .cbai-inline { position: relative; display: inline-flex; }
        .cbai-slug { display: inline-grid; place-items: center; inline-size: 1.5rem; block-size: 1.5rem; border: none;
                     border-radius: 50%; color: #fff; cursor: pointer; font: 600 .625rem/1 'IBM Plex Sans', sans-serif;
                     background: linear-gradient(90deg, #8a3ffc, #d02670, #1192e8); }
        .cbai-pop { position: absolute; inset-block-start: calc(100% + 8px); inset-inline-end: 0; z-index: 7;
                    inline-size: 16rem; padding: 1rem; background: #f4f4f4; box-shadow: 0 2px 6px rgba(0, 0, 0, .25); }
        .cbai-pop[data-open='false'] { display: none; }
        .cbai-pop b { font-weight: 600; }
        .cbai-pop div { display: flex; justify-content: space-between; gap: 1rem; padding-block: .375rem;
                        border-block-end: 1px solid #e0e0e0; font-size: .75rem; }
        .cbai-pop dt { color: #525252; }
        .cbai-pop dd { margin: 0; font-weight: 600; font-family: 'IBM Plex Mono', Menlo, monospace; }
        .cbai-pop-foot { display: flex; gap: .5rem; padding-block-start: .625rem; border-block-end: none; }
        .cbai-act { block-size: 2rem; padding-inline: .875rem; border: none; background: transparent;
                    color: #0f62fe; font: 400 .8125rem/1 'IBM Plex Sans', sans-serif; cursor: pointer; }
        .cbai-draft-body { padding: 1rem; }
        .cbai-draft-body[data-ai='true'] { border-inline-start: 3px solid #d02670; }
        .cbai-draft-foot { display: flex; padding: .625rem 1rem; border-block-start: 1px solid #e0e0e0; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- AiDraftBadge.svelte — Carbon AI label: the gradient badge proves who wrote this -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          const drafts = {
            ai: 'Hello Lara, yes — the October sales report was ready by noon. The reporting worker briefly stopped responding, which we have fixed; the CSV export is attached. — Nabu support',
            human: 'You are right — the report did land a little late and it is attached to this ticket now. Thanks for flagging it. — Nabu support',
          };
          let pop = $state(false);
          let mode = $state<'ai' | 'human'>('ai');
          let box: HTMLElement;

          onMount(() => {
            const onKey = (e: KeyboardEvent) => { if (e.key === 'Escape') pop = false; };
            const onTap = (e: PointerEvent) => { if (!box.contains(e.target as Node)) pop = false; };
            window.addEventListener('keydown', onKey);
            window.addEventListener('pointerdown', onTap);
            return () => {
              window.removeEventListener('keydown', onKey);
              window.removeEventListener('pointerdown', onTap);
            };
          });
        </script>

        <div bind:this={box} class="cbai-draft">
          <div class="cbai-draft-head">
            <b>Ticket #2841 — draft reply</b>
            <span class="cbai-inline">
              <button type="button" class="cbai-slug" aria-label="Generated with AI — details"
                aria-expanded={pop} onclick={() => (pop = !pop)}>AI</button>
              <div class="cbai-pop" role="dialog" aria-label="AI details" data-open={pop}>
                <b>AI-generated content</b>
                <dl>
                  <div><dt>Model</dt><dd>nabu-reply-3</dd></div>
                  <div><dt>Confidence</dt><dd>92%</dd></div>
                </dl>
                <div class="cbai-pop-foot">
                  <!-- One click rolls the draft back to the human version. -->
                  <button type="button" class="cbai-act" onclick={() => { pop = false; mode = 'human'; }}>Revert to human draft</button>
                </div>
              </div>
            </span>
            <small>{mode === 'ai' ? 'suggested by nabu-reply-3' : 'human draft'}</small>
          </div>
          <p class="cbai-draft-body" data-ai={mode === 'ai'}>{drafts[mode]}</p>
          {#if mode === 'human'}
            <div class="cbai-draft-foot">
              <button type="button" class="cbai-act" onclick={() => (mode = 'ai')}>Regenerate with AI</button>
            </div>
          {/if}
        </div>

        <style>
        .cbai-draft { max-inline-size: 30rem; background: #f4f4f4;
                      font: 400 .875rem/1.6 'IBM Plex Sans', sans-serif; }
        .cbai-draft-head { display: flex; align-items: center; gap: .625rem; padding: .75rem 1rem;
                           border-block-end: 1px solid #e0e0e0; }
        .cbai-draft-head b { font-weight: 600; }
        .cbai-draft-head small { margin-inline-start: auto; font-size: .75rem; color: #525252; }
        .cbai-inline { position: relative; display: inline-flex; }
        .cbai-slug { display: inline-grid; place-items: center; inline-size: 1.5rem; block-size: 1.5rem; border: none;
                     border-radius: 50%; color: #fff; cursor: pointer; font: 600 .625rem/1 'IBM Plex Sans', sans-serif;
                     background: linear-gradient(90deg, #8a3ffc, #d02670, #1192e8); }
        .cbai-pop { position: absolute; inset-block-start: calc(100% + 8px); inset-inline-end: 0; z-index: 7;
                    inline-size: 16rem; padding: 1rem; background: #f4f4f4; box-shadow: 0 2px 6px rgba(0, 0, 0, .25); }
        .cbai-pop[data-open='false'] { display: none; }
        .cbai-pop b { font-weight: 600; }
        .cbai-pop div { display: flex; justify-content: space-between; gap: 1rem; padding-block: .375rem;
                        border-block-end: 1px solid #e0e0e0; font-size: .75rem; }
        .cbai-pop dt { color: #525252; }
        .cbai-pop dd { margin: 0; font-weight: 600; font-family: 'IBM Plex Mono', Menlo, monospace; }
        .cbai-pop-foot { display: flex; gap: .5rem; padding-block-start: .625rem; border-block-end: none; }
        .cbai-act { block-size: 2rem; padding-inline: .875rem; border: none; background: transparent;
                    color: #0f62fe; font: 400 .8125rem/1 'IBM Plex Sans', sans-serif; cursor: pointer; }
        .cbai-draft-body { padding: 1rem; }
        .cbai-draft-body[data-ai='true'] { border-inline-start: 3px solid #d02670; }
        .cbai-draft-foot { display: flex; padding: .625rem 1rem; border-block-start: 1px solid #e0e0e0; }
        </style>
        SVELTE,
        ],
    ],

    'contained-list' => [
        'title' => ['fa' => 'فهرست دربرگیرنده', 'en' => 'Contained List'],
        'icon' => 'users',
        'oneLiner' => [
            'fa' => 'فهرستی که خودش ظرف است: سربرگ، اکشن‌ها و بخش‌بندی داخل یک قاب تخت، با آیتم‌های تعاملی؛ پاسخ کربن به فهرست‌های تنظیمات گروهی.',
            'en' => 'A list that is its own container — header, actions and sections inside one flat frame, row by row interactive; Carbon’s answer to grouped settings lists.',
        ],
        'js' => true,
        'docs' => 'https://react.carbondesignsystem.com/?path=/docs/components-containedlist--overview',
        'props' => [
            ['name' => 'label', 'type' => 'string', 'default' => 'required', 'note' => [
                'fa' => 'سربرگ درون قاب می‌نشیند؛ نه بیرون آن — همان چیزی که «دربرگیرنده» بودنش را می‌سازد.',
                'en' => 'The header sits inside the frame, not above it — exactly what makes it contained.',
            ]],
            ['name' => 'action', 'type' => 'slot', 'default' => 'null', 'note' => [
                'fa' => 'جای یک دکمهٔ آیکنی در سربرگ؛ جست‌وجو، افزودن یا فیلتر.',
                'en' => 'Room for one icon button in the header — search, add or filter.',
            ]],
            ['name' => 'isInset', 'type' => 'boolean', 'default' => 'false', 'note' => [
                'fa' => 'با inset، هاور ردیف‌ها تا لبهٔ قاب می‌رود و کناره‌ها را هم رنگ می‌گیرد.',
                'en' => 'With inset, the row hover reaches the frame edge and paints the gutters too.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="cbcl">
            <header class="cbcl-head">
                <b>اعضای تیم</b>
                <button aria-label="افزودن عضو">+</button>
            </header>
            <ul>
                <li><button>سارا محمدی — مالک</button></li>
                <li><button>امیر رستمی — توسعه‌دهنده</button></li>
            </ul>
        </div>

        <style>
        .cbcl { border: 1px solid #e0e0e0; background: #fff; font: 400 .875rem/1.4 'IBM Plex Sans'; }
        .cbcl-head { display: flex; align-items: center; justify-content: space-between;
                     padding: .75rem 1rem; border-block-end: 1px solid #e0e0e0; }
        .cbcl li + li { border-block-start: 1px solid #e0e0e0; }
        .cbcl li button { inline-size: 100%; padding: .75rem 1rem; border: none;
                          background: transparent; text-align: start; cursor: pointer; }
        .cbcl li button:hover { background: #e8e8e8; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="cbcl">
        <header class="cbcl-head">
        <b>Team members</b>
        <button aria-label="Add member">+</button>
        </header>
        <ul>
        <li><button>Selin Aydin — Owner</button></li>
        <li><button>Milan Weber — Developer</button></li>
        </ul>
        </div>
        
        <style>
        .cbcl { border: 1px solid #e0e0e0; background: #fff; font: 400 .875rem/1.4 'IBM Plex Sans'; }
        .cbcl-head { display: flex; align-items: center; justify-content: space-between;
        padding: .75rem 1rem; border-block-end: 1px solid #e0e0e0; }
        .cbcl li + li { border-block-start: 1px solid #e0e0e0; }
        .cbcl li button { inline-size: 100%; padding: .75rem 1rem; border: none;
        background: transparent; text-align: start; cursor: pointer; }
        .cbcl li button:hover { background: #e8e8e8; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Members.jsx — Inertia page; renders the React snippet
        // below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import MembersList from '@/components/MembersList';

        export default function Members() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <MembersList />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // MembersList.jsx — Carbon contained list: header, action and rows in one frame
        import { useState } from 'react';
        import '@nabuxai/ui-core/css';

        const MEMBERS = [
          { name: 'Lena Fischer', role: 'Owner', tone: 'green', state: 'Owner' },
          { name: 'Marco Ricci', role: 'Developer', tone: 'blue', state: 'Active' },
          { name: 'Aylin Demir', role: 'Support', tone: 'blue', state: 'Active' },
          { name: 'Jonas Weber', role: 'Billing', tone: 'gray', state: 'Invited' },
        ];

        export default function MembersList() {
          const [q, setQ] = useState('');
          // The header search live-filters; the frame itself never moves.
          const filtered = MEMBERS.filter((m) =>
            `${m.name} ${m.role}`.toLowerCase().includes(q.trim().toLowerCase()));

          return (
            <>
              <style>{`
                .cbcl { max-inline-size: 24rem; border: 1px solid #e0e0e0; background: #f4f4f4;
                        font: 400 .875rem/1.4 'IBM Plex Sans', sans-serif; }
                .cbcl-head { display: flex; align-items: center; justify-content: space-between; gap: .5rem;
                             padding: .75rem 1rem; border-block-end: 1px solid #e0e0e0; }
                .cbcl-head b { font-weight: 600; }
                .cbcl-head input { block-size: 2rem; inline-size: 10rem; padding-inline: .625rem; border: none;
                                   border-block-end: 1px solid #8d8d8d; background: transparent; font: inherit; }
                .cbcl-head input:focus-visible { outline: none; border-block-end: 2px solid #0f62fe; }
                .cbcl ul { margin: 0; padding: 0; list-style: none; }
                .cbcl li + li { border-block-start: 1px solid #e0e0e0; }
                .cbcl-row { inline-size: 100%; display: flex; align-items: center; gap: .75rem; padding: .6875rem 1rem;
                            border: none; border-block-end: 1px solid #e0e0e0; background: transparent; text-align: start;
                            cursor: pointer; font: inherit; }
                .cbcl-row:hover { background: #e8e8e8; }
                .cbcl-row small { font-size: .75rem; color: #525252; }
                .cbcl-tag { margin-inline-start: auto; padding: 0 .5rem; block-size: 1.5rem; display: inline-grid;
                            place-items: center; border: 1px solid; font-size: .75rem; }
                .cbcl-tag[data-tone='green'] { border-color: #24a148; color: #0e6027; }
                .cbcl-tag[data-tone='blue'] { border-color: #0f62fe; color: #0043ce; }
                .cbcl-tag[data-tone='gray'] { border-color: #8d8d8d; color: #525252; }
                .cbcl-foot { padding: .625rem 1rem; font-size: .75rem; color: #525252;
                             border-block-start: 2px solid #0f62fe; }
              `}</style>
              <div className="cbcl" role="group" aria-label="Workspace members">
                <header className="cbcl-head">
                  <b>Members</b>
                  <input type="search" value={q} onChange={(e) => setQ(e.target.value)}
                    placeholder="Find a member" aria-label="Find a member" />
                </header>
                <ul>
                  {filtered.map((m) => (
                    <li key={m.name}>
                      <button type="button" className="cbcl-row">
                        <span>{m.name} <small>· {m.role}</small></span>
                        <span className="cbcl-tag" data-tone={m.tone}>{m.state}</span>
                      </button>
                    </li>
                  ))}
                </ul>
                {/* The frame stays up; only the rows leave. */}
                <div className="cbcl-foot" role="status">{filtered.length} of {MEMBERS.length} members</div>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- MembersList.vue — Carbon contained list: header, action and rows in one frame -->
        <script setup lang="ts">
        import { computed, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const members = [
          { name: 'Lena Fischer', role: 'Owner', tone: 'green', state: 'Owner' },
          { name: 'Marco Ricci', role: 'Developer', tone: 'blue', state: 'Active' },
          { name: 'Aylin Demir', role: 'Support', tone: 'blue', state: 'Active' },
          { name: 'Jonas Weber', role: 'Billing', tone: 'gray', state: 'Invited' },
        ];
        const q = ref('');
        // The header search live-filters; the frame itself never moves.
        const filtered = computed(() =>
          members.filter((m) => `${m.name} ${m.role}`.toLowerCase().includes(q.value.trim().toLowerCase())));
        </script>

        <template>
          <div class="cbcl" role="group" aria-label="Workspace members">
            <header class="cbcl-head">
              <b>Members</b>
              <input v-model="q" type="search" placeholder="Find a member" aria-label="Find a member" />
            </header>
            <ul>
              <li v-for="m in filtered" :key="m.name">
                <button type="button" class="cbcl-row">
                  <span>{{ m.name }} <small>· {{ m.role }}</small></span>
                  <span class="cbcl-tag" :data-tone="m.tone">{{ m.state }}</span>
                </button>
              </li>
            </ul>
            <!-- The frame stays up; only the rows leave. -->
            <div class="cbcl-foot" role="status">{{ filtered.length }} of {{ members.length }} members</div>
          </div>
        </template>

        <style scoped>
        .cbcl { max-inline-size: 24rem; border: 1px solid #e0e0e0; background: #f4f4f4;
                font: 400 .875rem/1.4 'IBM Plex Sans', sans-serif; }
        .cbcl-head { display: flex; align-items: center; justify-content: space-between; gap: .5rem;
                     padding: .75rem 1rem; border-block-end: 1px solid #e0e0e0; }
        .cbcl-head b { font-weight: 600; }
        .cbcl-head input { block-size: 2rem; inline-size: 10rem; padding-inline: .625rem; border: none;
                           border-block-end: 1px solid #8d8d8d; background: transparent; font: inherit; }
        .cbcl-head input:focus-visible { outline: none; border-block-end: 2px solid #0f62fe; }
        .cbcl ul { margin: 0; padding: 0; list-style: none; }
        .cbcl li + li { border-block-start: 1px solid #e0e0e0; }
        .cbcl-row { inline-size: 100%; display: flex; align-items: center; gap: .75rem; padding: .6875rem 1rem;
                    border: none; border-block-end: 1px solid #e0e0e0; background: transparent; text-align: start;
                    cursor: pointer; font: inherit; }
        .cbcl-row:hover { background: #e8e8e8; }
        .cbcl-row small { font-size: .75rem; color: #525252; }
        .cbcl-tag { margin-inline-start: auto; padding: 0 .5rem; block-size: 1.5rem; display: inline-grid;
                    place-items: center; border: 1px solid; font-size: .75rem; }
        .cbcl-tag[data-tone='green'] { border-color: #24a148; color: #0e6027; }
        .cbcl-tag[data-tone='blue'] { border-color: #0f62fe; color: #0043ce; }
        .cbcl-tag[data-tone='gray'] { border-color: #8d8d8d; color: #525252; }
        .cbcl-foot { padding: .625rem 1rem; font-size: .75rem; color: #525252;
                     border-block-start: 2px solid #0f62fe; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- MembersList.svelte — Carbon contained list: header, action and rows in one frame -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const members = [
            { name: 'Lena Fischer', role: 'Owner', tone: 'green', state: 'Owner' },
            { name: 'Marco Ricci', role: 'Developer', tone: 'blue', state: 'Active' },
            { name: 'Aylin Demir', role: 'Support', tone: 'blue', state: 'Active' },
            { name: 'Jonas Weber', role: 'Billing', tone: 'gray', state: 'Invited' },
          ];
          let q = $state('');
          // The header search live-filters; the frame itself never moves.
          const filtered = $derived(
            members.filter((m) => `${m.name} ${m.role}`.toLowerCase().includes(q.trim().toLowerCase())),
          );
        </script>

        <div class="cbcl" role="group" aria-label="Workspace members">
          <header class="cbcl-head">
            <b>Members</b>
            <input type="search" bind:value={q} placeholder="Find a member" aria-label="Find a member" />
          </header>
          <ul>
            {#each filtered as m (m.name)}
              <li>
                <button type="button" class="cbcl-row">
                  <span>{m.name} <small>· {m.role}</small></span>
                  <span class="cbcl-tag" data-tone={m.tone}>{m.state}</span>
                </button>
              </li>
            {/each}
          </ul>
          <!-- The frame stays up; only the rows leave. -->
          <div class="cbcl-foot" role="status">{filtered.length} of {members.length} members</div>
        </div>

        <style>
        .cbcl { max-inline-size: 24rem; border: 1px solid #e0e0e0; background: #f4f4f4;
                font: 400 .875rem/1.4 'IBM Plex Sans', sans-serif; }
        .cbcl-head { display: flex; align-items: center; justify-content: space-between; gap: .5rem;
                     padding: .75rem 1rem; border-block-end: 1px solid #e0e0e0; }
        .cbcl-head b { font-weight: 600; }
        .cbcl-head input { block-size: 2rem; inline-size: 10rem; padding-inline: .625rem; border: none;
                           border-block-end: 1px solid #8d8d8d; background: transparent; font: inherit; }
        .cbcl-head input:focus-visible { outline: none; border-block-end: 2px solid #0f62fe; }
        .cbcl ul { margin: 0; padding: 0; list-style: none; }
        .cbcl li + li { border-block-start: 1px solid #e0e0e0; }
        .cbcl-row { inline-size: 100%; display: flex; align-items: center; gap: .75rem; padding: .6875rem 1rem;
                    border: none; border-block-end: 1px solid #e0e0e0; background: transparent; text-align: start;
                    cursor: pointer; font: inherit; }
        .cbcl-row:hover { background: #e8e8e8; }
        .cbcl-row small { font-size: .75rem; color: #525252; }
        .cbcl-tag { margin-inline-start: auto; padding: 0 .5rem; block-size: 1.5rem; display: inline-grid;
                    place-items: center; border: 1px solid; font-size: .75rem; }
        .cbcl-tag[data-tone='green'] { border-color: #24a148; color: #0e6027; }
        .cbcl-tag[data-tone='blue'] { border-color: #0f62fe; color: #0043ce; }
        .cbcl-tag[data-tone='gray'] { border-color: #8d8d8d; color: #525252; }
        .cbcl-foot { padding: .625rem 1rem; font-size: .75rem; color: #525252;
                     border-block-start: 2px solid #0f62fe; }
        </style>
        SVELTE,
        ],
    ],

    'tearsheet' => [
        'title' => ['fa' => 'تیرشیت', 'en' => 'Tearsheet'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'مودال عریضِ دسکتاپی که از لبهٔ پایین بالا می‌آید و با ریل ناوبری کناری، جریان‌های چندمرحله‌ای را روی یک سطح مدیریت می‌کند.',
            'en' => 'The wide desktop modal that tears up from the bottom edge — with a side rail for multi-step flows, it stages whole tasks on one surface.',
        ],
        'js' => true,
        'docs' => 'https://ibm-products.carbondesignsystem.com/?path=/docs/components-tearsheet--overview',
        'props' => [
            ['name' => 'open', 'type' => 'boolean', 'default' => 'false', 'note' => [
                'fa' => 'باز شدن از لبهٔ پایین با ترنزیشن ۲۴۰ms منحنیِ productive؛ Escape و پرده هم می‌بندند.',
                'en' => 'Tears up from the bottom edge on the 240ms productive curve; Escape and the scrim close it too.',
            ]],
            ['name' => 'influencer', 'type' => 'slot', 'default' => 'null', 'note' => [
                'fa' => 'ریل کناریِ پهنای ثابت برای ناوبری مراحل؛ داخلش Progress Indicator کربن می‌نشیند.',
                'en' => 'The fixed-width side rail for step navigation; Carbon’s Progress Indicator lives inside it.',
            ]],
            ['name' => 'actions', 'type' => 'footer', 'default' => "'primary · secondary'", 'note' => [
                'fa' => 'نوار پایه با اکشن اصلی و ثانویه؛ اکشن اصلی در مرحلهٔ آخر «پایان» می‌شود.',
                'en' => 'The base bar with a primary and secondary action; the primary turns into “Finish” on the last step.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="cbts" data-open="false">
            <div class="cbts-scrim"></div>
            <section class="cbts-sheet" role="dialog" aria-label="افزودن فضای ذخیره">
                <aside class="cbts-rail">
                    <ol><li data-current>جزئیات فضای ذخیره</li><li>انتخاب ناحیه</li><li>بازبینی</li></ol>
                </aside>
                <div class="cbts-body"> … </div>
                <footer class="cbts-footer">
                    <button data-primary>بعدی</button>
                    <button>انصراف</button>
                </footer>
            </section>
        </div>

        <style>
        .cbts { position: relative; overflow: clip; }
        .cbts-scrim { position: absolute; inset: 0; background: rgba(22,22,22,.5); }
        .cbts-sheet { position: absolute; inset-inline: 3rem; inset-block-end: 0; block-size: calc(100% - 3rem);
                      background: #f4f4f4; display: grid; grid-template-columns: 16rem 1fr; grid-template-rows: 1fr auto; }
        .cbts[data-open='false'] .cbts-sheet { translate: 0 100%; }
        .cbts-sheet { transition: translate .24s cubic-bezier(.2, 0, 0, 1); }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="cbts" data-open="false">
        <div class="cbts-scrim"></div>
        <section class="cbts-sheet" role="dialog" aria-label="Add storage">
        <aside class="cbts-rail">
        <ol><li data-current>Storage details</li><li>Choose a region</li><li>Review</li></ol>
        </aside>
        <div class="cbts-body"> … </div>
        <footer class="cbts-footer">
        <button data-primary>Next</button>
        <button>Cancel</button>
        </footer>
        </section>
        </div>
        
        <style>
        .cbts { position: relative; overflow: clip; }
        .cbts-scrim { position: absolute; inset: 0; background: rgba(22,22,22,.5); }
        .cbts-sheet { position: absolute; inset-inline: 3rem; inset-block-end: 0; block-size: calc(100% - 3rem);
        background: #f4f4f4; display: grid; grid-template-columns: 16rem 1fr; grid-template-rows: 1fr auto; }
        .cbts[data-open='false'] .cbts-sheet { translate: 0 100%; }
        .cbts-sheet { transition: translate .24s cubic-bezier(.2, 0, 0, 1); }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Storage.jsx — Inertia page; renders the React snippet
        // below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import StorageTearsheet from '@/components/StorageTearsheet';

        export default function Storage() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <StorageTearsheet />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // StorageTearsheet.jsx — Carbon tearsheet: tears up from the bottom, rail on the side
        import { useEffect, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const STEPS = ['Storage details', 'Choose region', 'Review'];

        export default function StorageTearsheet() {
          const [open, setOpen] = useState(false);
          const [step, setStep] = useState(0);

          // Escape closes — like the scrim and Cancel.
          useEffect(() => {
            const onKey = (e) => e.key === 'Escape' && setOpen(false);
            window.addEventListener('keydown', onKey);
            return () => window.removeEventListener('keydown', onKey);
          }, []);

          return (
            <>
              <style>{`
                .cbts { position: relative; overflow: clip; min-block-size: 20rem; padding: 1rem;
                        border: 1px solid #8d8d8d; background: #fff;
                        font: 400 .875rem/1.4 'IBM Plex Sans', sans-serif; }
                .cbts-open { block-size: 2.5rem; padding-inline: 1rem; border: none; background: #0f62fe;
                             color: #fff; cursor: pointer; }
                .cbts-scrim { position: absolute; inset: 0; z-index: 8; background: rgba(22, 22, 22, .5); cursor: pointer; }
                .cbts-sheet { position: absolute; z-index: 9; inset-inline: 2rem; inset-block-end: 0;
                              block-size: calc(100% - 2rem); display: grid; grid-template-columns: 12rem 1fr;
                              grid-template-rows: 1fr auto; background: #f4f4f4;
                              box-shadow: 0 -2px 14px rgba(0, 0, 0, .35);
                              animation: cbts-tear 240ms cubic-bezier(.2, 0, 0, 1); }
                @keyframes cbts-tear { from { translate: 0 100%; } }
                .cbts-rail { grid-row: 1 / 3; padding: 1.5rem 1.25rem; border-inline-end: 1px solid #e0e0e0; }
                .cbts-rail ol { margin: 0; padding: 0; list-style: none; }
                .cbts-rail li { padding-block-end: 1rem; color: #525252; }
                .cbts-rail li[data-state='current'] { color: #161616; font-weight: 600; }
                .cbts-rail li[data-state='complete'] { color: #0f62fe; }
                .cbts-main { padding: 1.5rem 2rem; }
                .cbts-main h2 { margin: 0 0 .5rem; font: 400 1.25rem/1.25 'IBM Plex Sans', sans-serif; }
                .cbts-footer { grid-column: 2; display: flex; gap: .75rem; padding: .875rem 2rem;
                               border-block-start: 1px solid #e0e0e0; }
                .cbts-footer button { block-size: 2.5rem; padding-inline: 1rem; border: none; cursor: pointer; }
                .cbts-footer [data-primary] { background: #0f62fe; color: #fff; }
                .cbts-footer [data-secondary] { background: transparent; color: #161616;
                                                box-shadow: inset 0 0 0 1px #8d8d8d; }
              `}</style>
              <div className="cbts">
                <button type="button" className="cbts-open" onClick={() => { setOpen(true); setStep(0); }}>
                  Add storage
                </button>
                {open && (
                  <>
                    {/* The scrim and the sheet tear up together on the productive curve. */}
                    <div className="cbts-scrim" onClick={() => setOpen(false)} />
                    <section className="cbts-sheet" role="dialog" aria-label="Add storage">
                      <aside className="cbts-rail" aria-label="Steps">
                        <ol>
                          {STEPS.map((label, i) => (
                            <li key={label} data-state={i < step ? 'complete' : i === step ? 'current' : ''}>
                              {i < step ? '✓ ' : ''}{label}
                            </li>
                          ))}
                        </ol>
                      </aside>
                      <div className="cbts-main">
                        <h2>{STEPS[step]}</h2>
                        {step === 0 && <p>Name the volume and pick its performance tier.</p>}
                        {step === 1 && <p>Frankfurt keeps latency to Berlin under 30ms.</p>}
                        {step === 2 && <p>250 GB · bronze tier · region eu-de-2.</p>}
                      </div>
                      <footer className="cbts-footer">
                        <button type="button" data-secondary onClick={() => setOpen(false)}>Cancel</button>
                        {/* The primary action turns into “Finish” on the last step. */}
                        <button type="button" data-primary
                          onClick={() => (step < 2 ? setStep(step + 1) : setOpen(false))}>
                          {step < 2 ? 'Next' : 'Finish'}
                        </button>
                      </footer>
                    </section>
                  </>
                )}
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- StorageTearsheet.vue — Carbon tearsheet: tears up from the bottom, rail on the side -->
        <script setup lang="ts">
        import { onBeforeUnmount, onMounted, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const steps = ['Storage details', 'Choose region', 'Review'];
        const open = ref(false);
        const step = ref(0);

        const onKey = (e: KeyboardEvent) => { if (e.key === 'Escape') open.value = false; };
        onMounted(() => window.addEventListener('keydown', onKey));
        onBeforeUnmount(() => window.removeEventListener('keydown', onKey));
        </script>

        <template>
          <div class="cbts">
            <button type="button" class="cbts-open" @click="open = true; step = 0">Add storage</button>
            <template v-if="open">
              <!-- The scrim and the sheet tear up together on the productive curve. -->
              <div class="cbts-scrim" @click="open = false" />
              <section class="cbts-sheet" role="dialog" aria-label="Add storage">
                <aside class="cbts-rail" aria-label="Steps">
                  <ol>
                    <li v-for="(label, i) in steps" :key="label"
                      :data-state="i < step ? 'complete' : i === step ? 'current' : ''">
                      {{ i < step ? '✓ ' : '' }}{{ label }}
                    </li>
                  </ol>
                </aside>
                <div class="cbts-main">
                  <h2>{{ steps[step] }}</h2>
                  <p v-if="step === 0">Name the volume and pick its performance tier.</p>
                  <p v-else-if="step === 1">Frankfurt keeps latency to Berlin under 30ms.</p>
                  <p v-else>250 GB · bronze tier · region eu-de-2.</p>
                </div>
                <footer class="cbts-footer">
                  <button type="button" data-secondary @click="open = false">Cancel</button>
                  <!-- The primary action turns into “Finish” on the last step. -->
                  <button type="button" data-primary @click="step < 2 ? step++ : (open = false)">
                    {{ step < 2 ? 'Next' : 'Finish' }}
                  </button>
                </footer>
              </section>
            </template>
          </div>
        </template>

        <style scoped>
        .cbts { position: relative; overflow: clip; min-block-size: 20rem; padding: 1rem;
                border: 1px solid #8d8d8d; background: #fff;
                font: 400 .875rem/1.4 'IBM Plex Sans', sans-serif; }
        .cbts-open { block-size: 2.5rem; padding-inline: 1rem; border: none; background: #0f62fe;
                     color: #fff; cursor: pointer; }
        .cbts-scrim { position: absolute; inset: 0; z-index: 8; background: rgba(22, 22, 22, .5); cursor: pointer; }
        .cbts-sheet { position: absolute; z-index: 9; inset-inline: 2rem; inset-block-end: 0;
                      block-size: calc(100% - 2rem); display: grid; grid-template-columns: 12rem 1fr;
                      grid-template-rows: 1fr auto; background: #f4f4f4;
                      box-shadow: 0 -2px 14px rgba(0, 0, 0, .35);
                      animation: cbts-tear 240ms cubic-bezier(.2, 0, 0, 1); }
        @keyframes cbts-tear { from { translate: 0 100%; } }
        .cbts-rail { grid-row: 1 / 3; padding: 1.5rem 1.25rem; border-inline-end: 1px solid #e0e0e0; }
        .cbts-rail ol { margin: 0; padding: 0; list-style: none; }
        .cbts-rail li { padding-block-end: 1rem; color: #525252; }
        .cbts-rail li[data-state='current'] { color: #161616; font-weight: 600; }
        .cbts-rail li[data-state='complete'] { color: #0f62fe; }
        .cbts-main { padding: 1.5rem 2rem; }
        .cbts-main h2 { margin: 0 0 .5rem; font: 400 1.25rem/1.25 'IBM Plex Sans', sans-serif; }
        .cbts-footer { grid-column: 2; display: flex; gap: .75rem; padding: .875rem 2rem;
                       border-block-start: 1px solid #e0e0e0; }
        .cbts-footer button { block-size: 2.5rem; padding-inline: 1rem; border: none; cursor: pointer; }
        .cbts-footer [data-primary] { background: #0f62fe; color: #fff; }
        .cbts-footer [data-secondary] { background: transparent; color: #161616;
                                        box-shadow: inset 0 0 0 1px #8d8d8d; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- StorageTearsheet.svelte — Carbon tearsheet: tears up from the bottom, rail on the side -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          const steps = ['Storage details', 'Choose region', 'Review'];
          let open = $state(false);
          let step = $state(0);

          onMount(() => {
            const onKey = (e: KeyboardEvent) => { if (e.key === 'Escape') open = false; };
            window.addEventListener('keydown', onKey);
            return () => window.removeEventListener('keydown', onKey);
          });
        </script>

        <div class="cbts">
          <button type="button" class="cbts-open" onclick={() => { open = true; step = 0; }}>Add storage</button>
          {#if open}
            <!-- The scrim and the sheet tear up together on the productive curve. -->
            <div class="cbts-scrim" onclick={() => (open = false)} aria-hidden="true"></div>
            <section class="cbts-sheet" role="dialog" aria-label="Add storage">
              <aside class="cbts-rail" aria-label="Steps">
                <ol>
                  {#each steps as label, i (label)}
                    <li data-state={i < step ? 'complete' : i === step ? 'current' : ''}>
                      {i < step ? '✓ ' : ''}{label}
                    </li>
                  {/each}
                </ol>
              </aside>
              <div class="cbts-main">
                <h2>{steps[step]}</h2>
                {#if step === 0}<p>Name the volume and pick its performance tier.</p>{/if}
                {#if step === 1}<p>Frankfurt keeps latency to Berlin under 30ms.</p>{/if}
                {#if step === 2}<p>250 GB · bronze tier · region eu-de-2.</p>{/if}
              </div>
              <footer class="cbts-footer">
                <button type="button" data-secondary onclick={() => (open = false)}>Cancel</button>
                <!-- The primary action turns into “Finish” on the last step. -->
                <button type="button" data-primary onclick={() => (step < 2 ? step++ : (open = false))}>
                  {step < 2 ? 'Next' : 'Finish'}
                </button>
              </footer>
            </section>
          {/if}
        </div>

        <style>
        .cbts { position: relative; overflow: clip; min-block-size: 20rem; padding: 1rem;
                border: 1px solid #8d8d8d; background: #fff;
                font: 400 .875rem/1.4 'IBM Plex Sans', sans-serif; }
        .cbts-open { block-size: 2.5rem; padding-inline: 1rem; border: none; background: #0f62fe;
                     color: #fff; cursor: pointer; }
        .cbts-scrim { position: absolute; inset: 0; z-index: 8; background: rgba(22, 22, 22, .5); cursor: pointer; }
        .cbts-sheet { position: absolute; z-index: 9; inset-inline: 2rem; inset-block-end: 0;
                      block-size: calc(100% - 2rem); display: grid; grid-template-columns: 12rem 1fr;
                      grid-template-rows: 1fr auto; background: #f4f4f4;
                      box-shadow: 0 -2px 14px rgba(0, 0, 0, .35);
                      animation: cbts-tear 240ms cubic-bezier(.2, 0, 0, 1); }
        @keyframes cbts-tear { from { translate: 0 100%; } }
        .cbts-rail { grid-row: 1 / 3; padding: 1.5rem 1.25rem; border-inline-end: 1px solid #e0e0e0; }
        .cbts-rail ol { margin: 0; padding: 0; list-style: none; }
        .cbts-rail li { padding-block-end: 1rem; color: #525252; }
        .cbts-rail li[data-state='current'] { color: #161616; font-weight: 600; }
        .cbts-rail li[data-state='complete'] { color: #0f62fe; }
        .cbts-main { padding: 1.5rem 2rem; }
        .cbts-main h2 { margin: 0 0 .5rem; font: 400 1.25rem/1.25 'IBM Plex Sans', sans-serif; }
        .cbts-footer { grid-column: 2; display: flex; gap: .75rem; padding: .875rem 2rem;
                       border-block-start: 1px solid #e0e0e0; }
        .cbts-footer button { block-size: 2.5rem; padding-inline: 1rem; border: none; cursor: pointer; }
        .cbts-footer [data-primary] { background: #0f62fe; color: #fff; }
        .cbts-footer [data-secondary] { background: transparent; color: #161616;
                                        box-shadow: inset 0 0 0 1px #8d8d8d; }
        </style>
        SVELTE,
        ],
    ],

    'data-spreadsheet' => [
        'title' => ['fa' => 'صفحه‌گستردهٔ داده', 'en' => 'Data Spreadsheet'],
        'icon' => 'chart',
        'oneLiner' => [
            'fa' => 'شبکهٔ سلولی با ستون‌های حرفی و سطرهای عددی اکسلی و انتخابِ سل‌به‌سل؛ جدول نیست، بوم محاسباتی کربن است.',
            'en' => 'An Excel-like cell grid with lettered columns, numbered rows and cell-level selection — not a table, Carbon’s computational canvas.',
        ],
        'js' => true,
        'docs' => 'https://ibm-products.carbondesignsystem.com/?path=/docs/deprecated-dataspreadsheet--overview',
        'props' => [
            ['name' => 'columns', 'type' => 'letters', 'default' => "'A – H'", 'note' => [
                'fa' => 'سربرگ ستون‌ها حرف‌اند نه برچسب؛ A تا H پیش‌فرض و با دیتای ورودی گسترش می‌یابد.',
                'en' => 'Column headers are letters, not labels; A to H by default, growing with the input data.',
            ]],
            ['name' => 'activeCell', 'type' => 'coordinates', 'default' => "'A1'", 'note' => [
                'fa' => 'سل فعال با نوار ۲پیکسلی آبی و مختصاتش در جعبهٔ گوشهٔ بالا-آغازین خوانده می‌شود.',
                'en' => 'The active cell reads through a 2px blue ring and its coordinates in the top-start corner box.',
            ]],
            ['name' => 'spreadsheetData', 'type' => 'sparse 2D', 'default' => 'null', 'note' => [
                'fa' => 'آرایهٔ دوبعدی خلوت؛ سلول‌های خالی هم فضای شبکه را نگه می‌دارند تا بوم جابه‌جا نشود.',
                'en' => 'A sparse 2D array; empty cells still hold their grid slot so the canvas never shifts.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="cbsp" x-data="{ active: 'A1' }">
            <header class="cbsp-ruler">
                <span class="cbsp-ref" x-text="active">A1</span>
                <span class="cbsp-formula" x-text="cell(active)">۱۲٬۵۰۰</span>
            </header>
            <div class="cbsp-grid" role="grid">
                <button role="gridcell" class="cbsp-cell" data-ref="A1">۱۲٬۵۰۰</button>
            </div>
        </div>

        <style>
        .cbsp { border: 1px solid #8d8d8d; font: 400 .75rem/1 'IBM Plex Mono', monospace; }
        .cbsp-ruler { display: flex; border-block-end: 1px solid #8d8d8d; }
        .cbsp-ref { min-inline-size: 4rem; padding: .5rem; border-inline-end: 1px solid #8d8d8d; }
        .cbsp-cell { inline-size: 6rem; block-size: 2rem; border: 1px solid #e0e0e0;
                     background: #fff; cursor: cell; text-align: start; }
        .cbsp-cell[data-active] { outline: 2px solid #0f62fe; outline-offset: -2px; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="cbsp" x-data="{ active: 'A1' }">
        <header class="cbsp-ruler">
        <span class="cbsp-ref" x-text="active">A1</span>
        <span class="cbsp-formula" x-text="cell(active)">12,500</span>
        </header>
        <div class="cbsp-grid" role="grid">
        <button role="gridcell" class="cbsp-cell" data-ref="A1">12,500</button>
        </div>
        </div>
        
        <style>
        .cbsp { border: 1px solid #8d8d8d; font: 400 .75rem/1 'IBM Plex Mono', monospace; }
        .cbsp-ruler { display: flex; border-block-end: 1px solid #8d8d8d; }
        .cbsp-ref { min-inline-size: 4rem; padding: .5rem; border-inline-end: 1px solid #8d8d8d; }
        .cbsp-cell { inline-size: 6rem; block-size: 2rem; border: 1px solid #e0e0e0;
        background: #fff; cursor: cell; text-align: start; }
        .cbsp-cell[data-active] { outline: 2px solid #0f62fe; outline-offset: -2px; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Budget.jsx — Inertia page; renders the React snippet
        // below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import BudgetSheet from '@/components/BudgetSheet';

        export default function Budget() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <BudgetSheet />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // BudgetSheet.jsx — Carbon data spreadsheet: lettered columns, arrow-key selection
        import { Fragment, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const COLS = ['A', 'B', 'C', 'D'];
        const ROWS = [1, 2, 3, 4];
        // Sparse on purpose: empty cells keep their grid slot.
        const DATA = {
          A1: 'Berlin office', B1: 'January', C1: 'February', D1: 'March',
          A2: 'Revenue', B2: '84,000', C2: '91,500', D2: '88,200',
          A3: 'Infrastructure', B3: '22,400', C3: '22,400', D3: '23,900',
          A4: 'Margin', B4: '61,600', C4: '69,100', D4: '64,300',
        };

        export default function BudgetSheet() {
          const [active, setActive] = useState('B2');

          // Arrow keys walk the grid; the ring, ref box and formula bar follow.
          const move = (event) => {
            const delta = { ArrowUp: [0, -1], ArrowDown: [0, 1], ArrowLeft: [-1, 0], ArrowRight: [1, 0] }[event.key];
            if (!delta) return;
            event.preventDefault();
            const c = COLS.indexOf(active[0]);
            const r = Number(active.slice(1));
            const col = COLS[Math.min(COLS.length - 1, Math.max(0, c + delta[0]))];
            const row = Math.min(ROWS.length, Math.max(1, r + delta[1]));
            setActive(col + row);
          };

          return (
            <>
              <style>{`
                .cbsp { max-inline-size: 32rem; border: 1px solid #8d8d8d; background: #f4f4f4;
                        font: 400 .75rem/1 'IBM Plex Mono', Menlo, monospace; }
                .cbsp-ruler { display: flex; border-block-end: 1px solid #8d8d8d; }
                .cbsp-ref { display: grid; place-items: center; min-inline-size: 3.5rem; padding: .5rem;
                            border-inline-end: 1px solid #8d8d8d; font-weight: 600; background: #e0e0e0; }
                .cbsp-formula { flex: 1; display: flex; align-items: center; gap: .5rem; padding: .5rem .625rem; }
                .cbsp-formula b { font-weight: 400; color: #525252; }
                .cbsp-grid { display: grid; grid-template-columns: 3rem repeat(4, minmax(5rem, 1fr)); }
                .cbsp-colhead, .cbsp-rowhead { display: grid; place-items: center; block-size: 2rem; color: #525252;
                                               background: #e0e0e0; border-inline-end: 1px solid #e0e0e0;
                                               border-block-end: 1px solid #e0e0e0; }
                .cbsp-colhead[data-hi], .cbsp-rowhead[data-hi] { background: #bdd3f5; color: #161616; }
                .cbsp-cell { display: flex; align-items: center; min-block-size: 2rem; padding-inline: .625rem;
                             border-inline-end: 1px solid #e0e0e0; border-block-end: 1px solid #e0e0e0;
                             background: #f4f4f4; color: #161616; text-align: start; cursor: cell; font: inherit; }
                .cbsp-cell[data-num] { justify-content: flex-end; }
                .cbsp-cell[data-active='true'] { outline: 2px solid #0f62fe; outline-offset: -2px; background: #e8e8e8; }
              `}</style>
              <div className="cbsp" role="grid" aria-label="Quarterly budget" onKeyDown={move}>
                <div className="cbsp-ruler">
                  <span className="cbsp-ref">{active}</span>
                  <span className="cbsp-formula"><b>ƒx</b><span>{DATA[active] ?? '—'}</span></span>
                </div>
                <div className="cbsp-grid">
                  <span className="cbsp-colhead" aria-hidden="true" />
                  {COLS.map((c) => (
                    <span key={c} className="cbsp-colhead" data-hi={c === active[0]}>{c}</span>
                  ))}
                  {ROWS.map((r) => (
                    <Fragment key={r}>
                      <span className="cbsp-rowhead" data-hi={r === Number(active.slice(1))}>{r}</span>
                      {COLS.map((c) => (
                        <button type="button" role="gridcell" key={c} className="cbsp-cell"
                          data-ref={c + r} data-active={active === c + r}
                          data-num={c !== 'A'} onClick={() => setActive(c + r)}>
                          {DATA[c + r] ?? ''}
                        </button>
                      ))}
                    </Fragment>
                  ))}
                </div>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- BudgetSheet.vue — Carbon data spreadsheet: lettered columns, arrow-key selection -->
        <script setup lang="ts">
        import { ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const cols = ['A', 'B', 'C', 'D'];
        const rows = [1, 2, 3, 4];
        // Sparse on purpose: empty cells keep their grid slot.
        const data: Record<string, string> = {
          A1: 'Berlin office', B1: 'January', C1: 'February', D1: 'March',
          A2: 'Revenue', B2: '84,000', C2: '91,500', D2: '88,200',
          A3: 'Infrastructure', B3: '22,400', C3: '22,400', D3: '23,900',
          A4: 'Margin', B4: '61,600', C4: '69,100', D4: '64,300',
        };
        const active = ref('B2');

        // Arrow keys walk the grid; the ring, ref box and formula bar follow.
        function move(e: KeyboardEvent) {
          const deltas: Record<string, [number, number]> = {
            ArrowUp: [0, -1], ArrowDown: [0, 1], ArrowLeft: [-1, 0], ArrowRight: [1, 0],
          };
          const delta = deltas[e.key];
          if (!delta) return;
          e.preventDefault();
          const c = cols.indexOf(active.value[0]);
          const r = Number(active.value.slice(1));
          const col = cols[Math.min(cols.length - 1, Math.max(0, c + delta[0]))];
          const row = Math.min(rows.length, Math.max(1, r + delta[1]));
          active.value = col + row;
        }
        </script>

        <template>
          <div class="cbsp" role="grid" aria-label="Quarterly budget" @keydown="move">
            <div class="cbsp-ruler">
              <span class="cbsp-ref">{{ active }}</span>
              <span class="cbsp-formula"><b>ƒx</b><span>{{ data[active] ?? '—' }}</span></span>
            </div>
            <div class="cbsp-grid">
              <span class="cbsp-colhead" aria-hidden="true"></span>
              <span v-for="c in cols" :key="c" class="cbsp-colhead" :data-hi="c === active[0]">{{ c }}</span>
              <template v-for="r in rows" :key="r">
                <span class="cbsp-rowhead" :data-hi="r === Number(active.slice(1))">{{ r }}</span>
                <button v-for="c in cols" :key="c" type="button" role="gridcell" class="cbsp-cell"
                  :data-ref="c + r" :data-active="active === c + r" :data-num="c !== 'A'"
                  @click="active = c + r">
                  {{ data[c + r] ?? '' }}
                </button>
              </template>
            </div>
          </div>
        </template>

        <style scoped>
        .cbsp { max-inline-size: 32rem; border: 1px solid #8d8d8d; background: #f4f4f4;
                font: 400 .75rem/1 'IBM Plex Mono', Menlo, monospace; }
        .cbsp-ruler { display: flex; border-block-end: 1px solid #8d8d8d; }
        .cbsp-ref { display: grid; place-items: center; min-inline-size: 3.5rem; padding: .5rem;
                    border-inline-end: 1px solid #8d8d8d; font-weight: 600; background: #e0e0e0; }
        .cbsp-formula { flex: 1; display: flex; align-items: center; gap: .5rem; padding: .5rem .625rem; }
        .cbsp-formula b { font-weight: 400; color: #525252; }
        .cbsp-grid { display: grid; grid-template-columns: 3rem repeat(4, minmax(5rem, 1fr)); }
        .cbsp-colhead, .cbsp-rowhead { display: grid; place-items: center; block-size: 2rem; color: #525252;
                                       background: #e0e0e0; border-inline-end: 1px solid #e0e0e0;
                                       border-block-end: 1px solid #e0e0e0; }
        .cbsp-colhead[data-hi], .cbsp-rowhead[data-hi] { background: #bdd3f5; color: #161616; }
        .cbsp-cell { display: flex; align-items: center; min-block-size: 2rem; padding-inline: .625rem;
                     border-inline-end: 1px solid #e0e0e0; border-block-end: 1px solid #e0e0e0;
                     background: #f4f4f4; color: #161616; text-align: start; cursor: cell; font: inherit; }
        .cbsp-cell[data-num] { justify-content: flex-end; }
        .cbsp-cell[data-active='true'] { outline: 2px solid #0f62fe; outline-offset: -2px; background: #e8e8e8; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- BudgetSheet.svelte — Carbon data spreadsheet: lettered columns, arrow-key selection -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const cols = ['A', 'B', 'C', 'D'];
          const rows = [1, 2, 3, 4];
          // Sparse on purpose: empty cells keep their grid slot.
          const data: Record<string, string> = {
            A1: 'Berlin office', B1: 'January', C1: 'February', D1: 'March',
            A2: 'Revenue', B2: '84,000', C2: '91,500', D2: '88,200',
            A3: 'Infrastructure', B3: '22,400', C3: '22,400', D3: '23,900',
            A4: 'Margin', B4: '61,600', C4: '69,100', D4: '64,300',
          };
          let active = $state('B2');

          // Arrow keys walk the grid; the ring, ref box and formula bar follow.
          function move(e: KeyboardEvent) {
            const deltas: Record<string, [number, number]> = {
              ArrowUp: [0, -1], ArrowDown: [0, 1], ArrowLeft: [-1, 0], ArrowRight: [1, 0],
            };
            const delta = deltas[e.key];
            if (!delta) return;
            e.preventDefault();
            const c = cols.indexOf(active[0]);
            const r = Number(active.slice(1));
            const col = cols[Math.min(cols.length - 1, Math.max(0, c + delta[0]))];
            const row = Math.min(rows.length, Math.max(1, r + delta[1]));
            active = col + row;
          }
        </script>

        <div class="cbsp" role="grid" aria-label="Quarterly budget" onkeydown={move}>
          <div class="cbsp-ruler">
            <span class="cbsp-ref">{active}</span>
            <span class="cbsp-formula"><b>ƒx</b><span>{data[active] ?? '—'}</span></span>
          </div>
          <div class="cbsp-grid">
            <span class="cbsp-colhead" aria-hidden="true"></span>
            {#each cols as c (c)}
              <span class="cbsp-colhead" data-hi={c === active[0]}>{c}</span>
            {/each}
            {#each rows as r (r)}
              <span class="cbsp-rowhead" data-hi={r === Number(active.slice(1))}>{r}</span>
              {#each cols as c (c)}
                <button type="button" role="gridcell" class="cbsp-cell" data-ref={c + r}
                  data-active={active === c + r} data-num={c !== 'A'} onclick={() => (active = c + r)}>
                  {data[c + r] ?? ''}
                </button>
              {/each}
            {/each}
          </div>
        </div>

        <style>
        .cbsp { max-inline-size: 32rem; border: 1px solid #8d8d8d; background: #f4f4f4;
                font: 400 .75rem/1 'IBM Plex Mono', Menlo, monospace; }
        .cbsp-ruler { display: flex; border-block-end: 1px solid #8d8d8d; }
        .cbsp-ref { display: grid; place-items: center; min-inline-size: 3.5rem; padding: .5rem;
                    border-inline-end: 1px solid #8d8d8d; font-weight: 600; background: #e0e0e0; }
        .cbsp-formula { flex: 1; display: flex; align-items: center; gap: .5rem; padding: .5rem .625rem; }
        .cbsp-formula b { font-weight: 400; color: #525252; }
        .cbsp-grid { display: grid; grid-template-columns: 3rem repeat(4, minmax(5rem, 1fr)); }
        .cbsp-colhead, .cbsp-rowhead { display: grid; place-items: center; block-size: 2rem; color: #525252;
                                       background: #e0e0e0; border-inline-end: 1px solid #e0e0e0;
                                       border-block-end: 1px solid #e0e0e0; }
        .cbsp-colhead[data-hi], .cbsp-rowhead[data-hi] { background: #bdd3f5; color: #161616; }
        .cbsp-cell { display: flex; align-items: center; min-block-size: 2rem; padding-inline: .625rem;
                     border-inline-end: 1px solid #e0e0e0; border-block-end: 1px solid #e0e0e0;
                     background: #f4f4f4; color: #161616; text-align: start; cursor: cell; font: inherit; }
        .cbsp-cell[data-num] { justify-content: flex-end; }
        .cbsp-cell[data-active='true'] { outline: 2px solid #0f62fe; outline-offset: -2px; background: #e8e8e8; }
        </style>
        SVELTE,
        ],
    ],
];
