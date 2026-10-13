<?php

/**
 * Demo manifest of the "atlassian" group — Atlassian Design System (Atlaskit)
 * rebuilt as faithful, interactive skins: the Jira/Confluence page header with
 * its tabs, the lozenge status chip, section messages, flags, inline messages,
 * the onboarding spotlight tour, the side navigation with its square icon
 * items, the illustrated empty state and the comment thread. Scenarios live at
 * resources/views/demos/components/atlassian/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'اطلس‌آثار (اطلس‌سین)', 'en' => 'Atlassian Design System'],

    'page-header' => [
        'title' => ['fa' => 'هدر صفحهٔ اطلسیان', 'en' => 'Page Header'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'تیتر صفحه، دکمه‌های اکشن و تب‌ها در یک ردیف آشنا — قاب ثابتی که هر صفحهٔ جیرا و کانفلوئنس با آن باز می‌شود.',
            'en' => 'Page title, action buttons and tabs in one familiar row — the fixed frame every Jira and Confluence page opens with.',
        ],
        'js' => true,
        'docs' => 'https://atlassian.design/components/page-header/usage',
        'props' => [
            ['name' => 'breadcrumb', 'type' => 'slot', 'default' => 'null', 'note' => [
                'fa' => 'مسیر بالای تیتر؛ لینک‌ها با فاصلهٔ ۴ پیکسل از هم جدا می‌شوند.',
                'en' => 'The trail above the title; links sit 4px apart.',
            ]],
            ['name' => 'actions', 'type' => 'slot', 'default' => 'null', 'note' => [
                'fa' => 'ردیف دکمه‌ها در انتهای سطر تیتر؛ اصلیِ آبی اول می‌آید.',
                'en' => 'The button row at the end of the title line; the blue primary comes first.',
            ]],
            ['name' => 'tabs', 'type' => 'slot', 'default' => 'null', 'note' => [
                'fa' => 'تب‌های زیر تیتر با زیرخطِ آبیِ دوپیکسلی روی تب فعال.',
                'en' => 'The tabs under the title, with a 2px blue underline on the active one.',
            ]],
            ['name' => 'bottomBar', 'type' => 'slot', 'default' => 'null', 'note' => [
                'fa' => 'نوار پایین هدر؛ برای متادیتا مثل «آخرین به‌روزرسانی».',
                'en' => 'The bar at the header’s bottom; for metadata like the last update.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <header class="atph">
            <nav class="atph-crumbs"><a href="#">پروژه‌ها</a><span>/</span><b>نابو</b></nav>
            <div class="atph-row">
                <h1>طراحی رابط نابو ۲</h1>
                <div class="atph-actions">
                    <button class="atph-btn" data-kind="primary">ایجاد</button>
                    <button class="atph-btn">دعوت همکاران</button>
                </div>
            </div>
            <nav class="atph-tabs"><button data-current>نمای کلی</button><button>کارها</button></nav>
        </header>

        <style>
        .atph { display: grid; gap: 12px; padding-block: 16px; }
        .atph-crumbs { font: 400 12px Inter, system-ui; color: #626F86; }
        .atph-row { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
        .atph-row h1 { font: 500 24px/1.2 "Charlie Display", Inter, system-ui; color: #172B4D; }
        .atph-actions { margin-inline-start: auto; display: flex; gap: 8px; }
        .atph-btn { block-size: 32px; padding-inline: 12px; border-radius: 3px; border: none;
                    background: #091E42; color: #FFFFFF; font: 500 14px Inter, system-ui; }
        .atph-btn[data-kind='primary'] { background: #0C66E4; }
        .atph-tabs { display: flex; gap: 4px; border-block-end: 2px solid #091E421F; }
        .atph-tabs button { padding: 8px 12px; border: none; background: none; color: #44546F;
                            border-block-end: 2px solid transparent; margin-block-end: -2px; }
        .atph-tabs button[data-current] { color: #0C66E4; border-block-end-color: #0C66E4; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <header class="atph">
            <nav class="atph-crumbs"><a href="#">Projects</a><span>/</span><b>Nabu</b></nav>
            <div class="atph-row">
                <h1>Nabu UI redesign</h1>
                <div class="atph-actions">
                    <button class="atph-btn" data-kind="primary">Create</button>
                    <button class="atph-btn">Invite teammates</button>
                </div>
            </div>
            <nav class="atph-tabs"><button data-current>Overview</button><button>Work items</button></nav>
        </header>

        <style>
        .atph { display: grid; gap: 12px; padding-block: 16px; }
        .atph-crumbs { font: 400 12px Inter, system-ui; color: #626F86; }
        .atph-row { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
        .atph-row h1 { font: 500 24px/1.2 "Charlie Display", Inter, system-ui; color: #172B4D; }
        .atph-actions { margin-inline-start: auto; display: flex; gap: 8px; }
        .atph-btn { block-size: 32px; padding-inline: 12px; border-radius: 3px; border: none;
                    background: #091E42; color: #FFFFFF; font: 500 14px Inter, system-ui; }
        .atph-btn[data-kind='primary'] { background: #0C66E4; }
        .atph-tabs { display: flex; gap: 4px; border-block-end: 2px solid #091E421F; }
        .atph-tabs button { padding: 8px 12px; border: none; background: none; color: #44546F;
                            border-block-end: 2px solid transparent; margin-block-end: -2px; }
        .atph-tabs button[data-current] { color: #0C66E4; border-block-end-color: #0C66E4; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Project.jsx — Inertia page; renders the React snippet
        // below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import ProjectHeader from '@/components/ProjectHeader';

        export default function Project() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <ProjectHeader />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // ProjectHeader.jsx — the frame every Jira page opens with
        import { useState } from 'react';
        import '@nabuxai/ui-core/css';

        const TABS = [
          { id: 'overview', label: 'Overview', summary: '3 in progress, 2 in review and 7 backlog issues in this sprint.' },
          { id: 'work', label: 'Work', summary: 'Sprint 24 board — advanced search enters review today.' },
          { id: 'plan', label: 'Plan', summary: 'Phase two closes on Oct 20; 84% of committed work is done.' },
        ];

        export default function ProjectHeader() {
          const [tab, setTab] = useState('overview');
          const current = TABS.find((t) => t.id === tab);

          return (
            <>
              <style>{`
                .atph { display: grid; gap: 12px; padding-block: 16px; font-family: Inter, system-ui, sans-serif; }
                .atph-crumbs { font: 400 12px Inter, system-ui; color: #626F86; }
                .atph-crumbs a { color: #0C66E4; text-decoration: none; }
                .atph-row { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
                .atph-row h1 { margin: 0; font: 500 24px/1.2 'Charlie Display', Inter, system-ui; color: #172B4D; }
                .atph-actions { margin-inline-start: auto; display: flex; gap: 8px; }
                .atph-btn { block-size: 32px; padding-inline: 12px; border-radius: 3px; border: none; cursor: pointer;
                            background: #091E42; color: #fff; font: 500 14px Inter, system-ui; }
                .atph-btn[data-kind='primary'] { background: #0C66E4; }
                .atph-tabs { display: flex; gap: 4px; border-block-end: 2px solid #091E421F; }
                .atph-tabs button { padding: 8px 12px; border: none; background: none; cursor: pointer; color: #44546F;
                                    border-block-end: 2px solid transparent; margin-block-end: -2px;
                                    font: 500 14px Inter, system-ui; }
                .atph-tabs button[aria-current='page'] { color: #0C66E4; border-block-end-color: #0C66E4; }
                .atph-pane { margin: 0; font: 400 13px/1.6 Inter, system-ui; color: #44546F; }
              `}</style>
              <header className="atph">
                <nav className="atph-crumbs"><a href="#">Projects</a><span>/</span><b>Nabu</b></nav>
                <div className="atph-row">
                  <h1>Nabu UI redesign</h1>
                  <div className="atph-actions">
                    <button type="button" className="atph-btn" data-kind="primary">Create</button>
                    <button type="button" className="atph-btn">Invite teammates</button>
                  </div>
                </div>
                {/* The 2px blue underline really switches the pane below. */}
                <nav className="atph-tabs" aria-label="Sections">
                  {TABS.map((t) => (
                    <button type="button" key={t.id} aria-current={tab === t.id ? 'page' : undefined}
                      onClick={() => setTab(t.id)}>{t.label}</button>
                  ))}
                </nav>
                <p className="atph-pane">{current.summary}</p>
              </header>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- ProjectHeader.vue — the frame every Jira page opens with -->
        <script setup lang="ts">
        import { computed, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const tabs = [
          { id: 'overview', label: 'Overview', summary: '3 in progress, 2 in review and 7 backlog issues in this sprint.' },
          { id: 'work', label: 'Work', summary: 'Sprint 24 board — advanced search enters review today.' },
          { id: 'plan', label: 'Plan', summary: 'Phase two closes on Oct 20; 84% of committed work is done.' },
        ];
        const tab = ref('overview');
        const current = computed(() => tabs.find((t) => t.id === tab.value)!);
        </script>

        <template>
          <header class="atph">
            <nav class="atph-crumbs"><a href="#">Projects</a><span>/</span><b>Nabu</b></nav>
            <div class="atph-row">
              <h1>Nabu UI redesign</h1>
              <div class="atph-actions">
                <button type="button" class="atph-btn" data-kind="primary">Create</button>
                <button type="button" class="atph-btn">Invite teammates</button>
              </div>
            </div>
            <!-- The 2px blue underline really switches the pane below. -->
            <nav class="atph-tabs" aria-label="Sections">
              <button v-for="t in tabs" :key="t.id" type="button"
                :aria-current="tab === t.id ? 'page' : undefined" @click="tab = t.id">{{ t.label }}</button>
            </nav>
            <p class="atph-pane">{{ current.summary }}</p>
          </header>
        </template>

        <style scoped>
        .atph { display: grid; gap: 12px; padding-block: 16px; font-family: Inter, system-ui, sans-serif; }
        .atph-crumbs { font: 400 12px Inter, system-ui; color: #626F86; }
        .atph-crumbs a { color: #0C66E4; text-decoration: none; }
        .atph-row { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
        .atph-row h1 { margin: 0; font: 500 24px/1.2 'Charlie Display', Inter, system-ui; color: #172B4D; }
        .atph-actions { margin-inline-start: auto; display: flex; gap: 8px; }
        .atph-btn { block-size: 32px; padding-inline: 12px; border-radius: 3px; border: none; cursor: pointer;
                    background: #091E42; color: #fff; font: 500 14px Inter, system-ui; }
        .atph-btn[data-kind='primary'] { background: #0C66E4; }
        .atph-tabs { display: flex; gap: 4px; border-block-end: 2px solid #091E421F; }
        .atph-tabs button { padding: 8px 12px; border: none; background: none; cursor: pointer; color: #44546F;
                            border-block-end: 2px solid transparent; margin-block-end: -2px;
                            font: 500 14px Inter, system-ui; }
        .atph-tabs button[aria-current='page'] { color: #0C66E4; border-block-end-color: #0C66E4; }
        .atph-pane { margin: 0; font: 400 13px/1.6 Inter, system-ui; color: #44546F; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- ProjectHeader.svelte — the frame every Jira page opens with -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const tabs = [
            { id: 'overview', label: 'Overview', summary: '3 in progress, 2 in review and 7 backlog issues in this sprint.' },
            { id: 'work', label: 'Work', summary: 'Sprint 24 board — advanced search enters review today.' },
            { id: 'plan', label: 'Plan', summary: 'Phase two closes on Oct 20; 84% of committed work is done.' },
          ];
          let tab = $state('overview');
          const current = $derived(tabs.find((t) => t.id === tab)!);
        </script>

        <header class="atph">
          <nav class="atph-crumbs"><a href="#/projects">Projects</a><span>/</span><b>Nabu</b></nav>
          <div class="atph-row">
            <h1>Nabu UI redesign</h1>
            <div class="atph-actions">
              <button type="button" class="atph-btn" data-kind="primary">Create</button>
              <button type="button" class="atph-btn">Invite teammates</button>
            </div>
          </div>
          <!-- The 2px blue underline really switches the pane below. -->
          <nav class="atph-tabs" aria-label="Sections">
            {#each tabs as t (t.id)}
              <button type="button" aria-current={tab === t.id ? 'page' : undefined} onclick={() => (tab = t.id)}>
                {t.label}
              </button>
            {/each}
          </nav>
          <p class="atph-pane">{current.summary}</p>
        </header>

        <style>
        .atph { display: grid; gap: 12px; padding-block: 16px; font-family: Inter, system-ui, sans-serif; }
        .atph-crumbs { font: 400 12px Inter, system-ui; color: #626F86; }
        .atph-crumbs a { color: #0C66E4; text-decoration: none; }
        .atph-row { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
        .atph-row h1 { margin: 0; font: 500 24px/1.2 'Charlie Display', Inter, system-ui; color: #172B4D; }
        .atph-actions { margin-inline-start: auto; display: flex; gap: 8px; }
        .atph-btn { block-size: 32px; padding-inline: 12px; border-radius: 3px; border: none; cursor: pointer;
                    background: #091E42; color: #fff; font: 500 14px Inter, system-ui; }
        .atph-btn[data-kind='primary'] { background: #0C66E4; }
        .atph-tabs { display: flex; gap: 4px; border-block-end: 2px solid #091E421F; }
        .atph-tabs button { padding: 8px 12px; border: none; background: none; cursor: pointer; color: #44546F;
                            border-block-end: 2px solid transparent; margin-block-end: -2px;
                            font: 500 14px Inter, system-ui; }
        .atph-tabs button[aria-current='page'] { color: #0C66E4; border-block-end-color: #0C66E4; }
        .atph-pane { margin: 0; font: 400 13px/1.6 Inter, system-ui; color: #44546F; }
        </style>
        SVELTE,
        ],
    ],

    'lozenge' => [
        'title' => ['fa' => 'لوزِنج (برچسب وضعیت)', 'en' => 'Lozenge'],
        'icon' => 'zap',
        'oneLiner' => [
            'fa' => 'برچسب کوچک نیم‌گرد با رنگ ملایم یا غلیظ — همان چیزی که برچسب «In Progress» جیرا را در نگاه اول لو می‌دهد.',
            'en' => 'The small half-rounded status chip with subtle or bold color — the reason a Jira ' . "'" . 'In Progress' . "'" . ' label is recognizable at first glance.',
        ],
        'js' => true,
        'docs' => 'https://atlassian.design/components/lozenge/usage',
        'props' => [
            ['name' => 'appearance', 'type' => 'string', 'default' => "'default'", 'note' => [
                'fa' => 'default · successful · removed · inprogress · moved · new.',
                'en' => 'default · successful · removed · inprogress · moved · new.',
            ]],
            ['name' => 'isBold', 'type' => 'boolean', 'default' => 'false', 'note' => [
                'fa' => 'ملایم یعنی سطح کم‌رنگ با متن تیره؛ غلیظ یعنی پس‌زمینهٔ توپر با متن سفید.',
                'en' => 'Subtle is a pale surface with dark text; bold is a solid fill with white text.',
            ]],
            ['name' => 'maxWidth', 'type' => 'length', 'default' => "'200px'", 'note' => [
                'fa' => 'برچسب بلندتر از ۲۰۰ پیکسل با سه‌نقطه فشرده می‌شود.',
                'en' => 'A label longer than 200px clamps to an ellipsis.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <span class="atlz" data-tone="inprogress">در جریان</span>
        <span class="atlz" data-tone="successful">انجام شد</span>
        <span class="atlz" data-tone="removed" data-bold>حذف شده</span>

        <style>
        .atlz { display: inline-block; padding: 2px 8px; border-radius: 3px;
                font: 700 11px/1.6 Inter, system-ui; letter-spacing: .2px; }
        .atlz[data-tone='inprogress'] { background: #E9F2FF; color: #0055CC; }
        .atlz[data-tone='successful'] { background: #DCFFF1; color: #1F845A; }
        .atlz[data-bold] { background: #0C66E4; color: #FFFFFF; }
        .atlz[data-tone='removed'][data-bold] { background: #B40000; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <span class="atlz" data-tone="inprogress">In progress</span>
        <span class="atlz" data-tone="successful">Done</span>
        <span class="atlz" data-tone="removed" data-bold>Removed</span>

        <style>
        .atlz { display: inline-block; padding: 2px 8px; border-radius: 3px;
                font: 700 11px/1.6 Inter, system-ui; letter-spacing: .2px; }
        .atlz[data-tone='inprogress'] { background: #E9F2FF; color: #0055CC; }
        .atlz[data-tone='successful'] { background: #DCFFF1; color: #1F845A; }
        .atlz[data-bold] { background: #0C66E4; color: #FFFFFF; }
        .atlz[data-tone='removed'][data-bold] { background: #B40000; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Board.jsx — Inertia page; renders the React snippet
        // below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import StatusLozenges from '@/components/StatusLozenges';

        export default function Board() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <StatusLozenges />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // StatusLozenges.jsx — the Atlassian lozenge: click a chip to cycle its appearance
        import { useState } from 'react';
        import '@nabuxai/ui-core/css';

        const TONES = ['backlog', 'inprogress', 'review', 'done', 'moved', 'removed'];
        const LABELS = {
          backlog: 'Backlog', inprogress: 'In progress', review: 'In review',
          done: 'Done', moved: 'Moved', removed: 'Removed',
        };
        const ISSUES = [
          { key: 'NABU-241', name: 'Payment webhook retries', tone: 'review' },
          { key: 'NABU-247', name: 'Advanced search tokens', tone: 'inprogress' },
          { key: 'NABU-253', name: 'Istanbul launch checklist', tone: 'backlog' },
        ];

        export default function StatusLozenges() {
          const [tones, setTones] = useState(Object.fromEntries(ISSUES.map((i) => [i.key, i.tone])));
          const cycle = (key) =>
            setTones((t) => ({ ...t, [key]: TONES[(TONES.indexOf(t[key]) + 1) % TONES.length] }));

          return (
            <>
              <style>{`
                .atlz-board { max-inline-size: 26rem; border: 1px solid #DFE1E6; border-radius: 6px;
                              overflow: hidden; background: #fff; font-family: Inter, system-ui, sans-serif; }
                .atlz-row { display: flex; align-items: center; gap: .75rem; inline-size: 100%; padding: .7rem .9rem;
                            border: none; background: none; text-align: start; cursor: pointer; font: inherit; }
                .atlz-row + .atlz-row { border-block-start: 1px solid #DFE1E6; }
                .atlz-row:hover { background: #F1F2F4; }
                .atlz-key { font: 600 .75rem Inter, system-ui; color: #0C66E4; }
                .atlz-name { flex: 1; font: 400 .85rem/1.4 Inter, system-ui; color: #172B4D; }
                /* Six subtle appearances; add data-bold for the solid family. */
                .atlz-chip { display: inline-block; padding: 2px 8px; border-radius: 3px; white-space: nowrap;
                             font: 700 .7rem/1.6 Inter, system-ui; letter-spacing: .2px;
                             max-inline-size: 200px; overflow: hidden; text-overflow: ellipsis; }
                .atlz-chip[data-tone='backlog'] { background: #F1F2F4; color: #44546F; }
                .atlz-chip[data-tone='inprogress'] { background: #E9F2FF; color: #0055CC; }
                .atlz-chip[data-tone='review'] { background: #FFF7D6; color: #A54800; }
                .atlz-chip[data-tone='done'] { background: #DCFFF1; color: #1F845A; }
                .atlz-chip[data-tone='moved'] { background: #EAE6FF; color: #5E4DB2; }
                .atlz-chip[data-tone='removed'] { background: #FFEDEB; color: #AE2A19; }
              `}</style>
              <div className="atlz-board">
                {ISSUES.map((issue) => (
                  <button type="button" className="atlz-row" key={issue.key} onClick={() => cycle(issue.key)}>
                    <span className="atlz-key">{issue.key}</span>
                    <span className="atlz-name">{issue.name}</span>
                    {/* Click cycles through the six official appearances. */}
                    <span className="atlz-chip" data-tone={tones[issue.key]}>{LABELS[tones[issue.key]]}</span>
                  </button>
                ))}
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- StatusLozenges.vue — the Atlassian lozenge: click a chip to cycle its appearance -->
        <script setup lang="ts">
        import { reactive } from 'vue';
        import '@nabuxai/ui-core/css';

        const tones = ['backlog', 'inprogress', 'review', 'done', 'moved', 'removed'] as const;
        const labels = {
          backlog: 'Backlog', inprogress: 'In progress', review: 'In review',
          done: 'Done', moved: 'Moved', removed: 'Removed',
        };
        const issues = [
          { key: 'NABU-241', name: 'Payment webhook retries', tone: 'review' },
          { key: 'NABU-247', name: 'Advanced search tokens', tone: 'inprogress' },
          { key: 'NABU-253', name: 'Istanbul launch checklist', tone: 'backlog' },
        ];
        const state = reactive(Object.fromEntries(issues.map((i) => [i.key, i.tone])));
        function cycle(key: string) {
          const at = tones.indexOf(state[key]);
          state[key] = tones[(at + 1) % tones.length];
        }
        </script>

        <template>
          <div class="atlz-board">
            <button v-for="issue in issues" :key="issue.key" type="button" class="atlz-row" @click="cycle(issue.key)">
              <span class="atlz-key">{{ issue.key }}</span>
              <span class="atlz-name">{{ issue.name }}</span>
              <!-- Click cycles through the six official appearances. -->
              <span class="atlz-chip" :data-tone="state[issue.key]">{{ labels[state[issue.key]] }}</span>
            </button>
          </div>
        </template>

        <style scoped>
        .atlz-board { max-inline-size: 26rem; border: 1px solid #DFE1E6; border-radius: 6px;
                      overflow: hidden; background: #fff; font-family: Inter, system-ui, sans-serif; }
        .atlz-row { display: flex; align-items: center; gap: .75rem; inline-size: 100%; padding: .7rem .9rem;
                    border: none; background: none; text-align: start; cursor: pointer; font: inherit; }
        .atlz-row + .atlz-row { border-block-start: 1px solid #DFE1E6; }
        .atlz-row:hover { background: #F1F2F4; }
        .atlz-key { font: 600 .75rem Inter, system-ui; color: #0C66E4; }
        .atlz-name { flex: 1; font: 400 .85rem/1.4 Inter, system-ui; color: #172B4D; }
        /* Six subtle appearances; add data-bold for the solid family. */
        .atlz-chip { display: inline-block; padding: 2px 8px; border-radius: 3px; white-space: nowrap;
                     font: 700 .7rem/1.6 Inter, system-ui; letter-spacing: .2px;
                     max-inline-size: 200px; overflow: hidden; text-overflow: ellipsis; }
        .atlz-chip[data-tone='backlog'] { background: #F1F2F4; color: #44546F; }
        .atlz-chip[data-tone='inprogress'] { background: #E9F2FF; color: #0055CC; }
        .atlz-chip[data-tone='review'] { background: #FFF7D6; color: #A54800; }
        .atlz-chip[data-tone='done'] { background: #DCFFF1; color: #1F845A; }
        .atlz-chip[data-tone='moved'] { background: #EAE6FF; color: #5E4DB2; }
        .atlz-chip[data-tone='removed'] { background: #FFEDEB; color: #AE2A19; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- StatusLozenges.svelte — the Atlassian lozenge: click a chip to cycle its appearance -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const tones = ['backlog', 'inprogress', 'review', 'done', 'moved', 'removed'] as const;
          const labels = {
            backlog: 'Backlog', inprogress: 'In progress', review: 'In review',
            done: 'Done', moved: 'Moved', removed: 'Removed',
          };
          const issues = [
            { key: 'NABU-241', name: 'Payment webhook retries', tone: 'review' },
            { key: 'NABU-247', name: 'Advanced search tokens', tone: 'inprogress' },
            { key: 'NABU-253', name: 'Istanbul launch checklist', tone: 'backlog' },
          ];
          let state = $state(Object.fromEntries(issues.map((i) => [i.key, i.tone])));
          function cycle(key: string) {
            const at = tones.indexOf(state[key]);
            state[key] = tones[(at + 1) % tones.length];
          }
        </script>

        <div class="atlz-board">
          {#each issues as issue (issue.key)}
            <button type="button" class="atlz-row" onclick={() => cycle(issue.key)}>
              <span class="atlz-key">{issue.key}</span>
              <span class="atlz-name">{issue.name}</span>
              <!-- Click cycles through the six official appearances. -->
              <span class="atlz-chip" data-tone={state[issue.key]}>{labels[state[issue.key]]}</span>
            </button>
          {/each}
        </div>

        <style>
        .atlz-board { max-inline-size: 26rem; border: 1px solid #DFE1E6; border-radius: 6px;
                      overflow: hidden; background: #fff; font-family: Inter, system-ui, sans-serif; }
        .atlz-row { display: flex; align-items: center; gap: .75rem; inline-size: 100%; padding: .7rem .9rem;
                    border: none; background: none; text-align: start; cursor: pointer; font: inherit; }
        .atlz-row + .atlz-row { border-block-start: 1px solid #DFE1E6; }
        .atlz-row:hover { background: #F1F2F4; }
        .atlz-key { font: 600 .75rem Inter, system-ui; color: #0C66E4; }
        .atlz-name { flex: 1; font: 400 .85rem/1.4 Inter, system-ui; color: #172B4D; }
        /* Six subtle appearances; add data-bold for the solid family. */
        .atlz-chip { display: inline-block; padding: 2px 8px; border-radius: 3px; white-space: nowrap;
                     font: 700 .7rem/1.6 Inter, system-ui; letter-spacing: .2px;
                     max-inline-size: 200px; overflow: hidden; text-overflow: ellipsis; }
        .atlz-chip[data-tone='backlog'] { background: #F1F2F4; color: #44546F; }
        .atlz-chip[data-tone='inprogress'] { background: #E9F2FF; color: #0055CC; }
        .atlz-chip[data-tone='review'] { background: #FFF7D6; color: #A54800; }
        .atlz-chip[data-tone='done'] { background: #DCFFF1; color: #1F845A; }
        .atlz-chip[data-tone='moved'] { background: #EAE6FF; color: #5E4DB2; }
        .atlz-chip[data-tone='removed'] { background: #FFEDEB; color: #AE2A19; }
        </style>
        SVELTE,
        ],
    ],

    'section-message' => [
        'title' => ['fa' => 'پیام بخش', 'en' => 'Section Message'],
        'icon' => 'info',
        'oneLiner' => [
            'fa' => 'پیام درون‌محتوایی با آیکن، اکشن اختیاری و چهار شدت از اطلاع تا خطا — راه اطلسیان برای هشدار بدون قطع کردن جریان کار.',
            'en' => 'In-content message with icon, optional action and four severities from info to error — Atlassian' . "'" . 's way of warning without stopping the flow.',
        ],
        'js' => true,
        'docs' => 'https://atlassian.design/components/section-message/usage',
        'props' => [
            ['name' => 'appearance', 'type' => 'string', 'default' => "'information'", 'note' => [
                'fa' => 'information · warning · error · success · discovery؛ هرکدام آیکن و سطح خودش را دارد.',
                'en' => 'information · warning · error · success · discovery; each carries its own icon and tint.',
            ]],
            ['name' => 'title', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'سرخط پررنگ یک‌خطی بالای متن پشتیبان.',
                'en' => 'The bold one-line heading above the supporting text.',
            ]],
            ['name' => 'actions', 'type' => 'slot', 'default' => 'null', 'note' => [
                'fa' => 'لینک یا دکمهٔ بعد از متن؛ مثل «مشاهدهٔ مستندات».',
                'en' => 'The link or button after the text; like «Read the docs».',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <section class="atsm" data-tone="warning">
            <span class="atsm-icon" aria-hidden="true">!</span>
            <div>
                <h4>نشانهٔ دسترسی به‌زودی منقضی می‌شود</h4>
                <p>نشانهٔ فعلی تا ۷ روز دیگر اعتبار دارد.</p>
                <a href="#">نشانهٔ تازه بسازید</a>
            </div>
        </section>

        <style>
        .atsm { display: flex; gap: 12px; padding: 16px; border-radius: 3px;
                background: #FFFAE6; color: #172B4D; }
        .atsm[data-tone='error'] { background: #FFEDEB; }
        .atsm-icon { flex: none; display: grid; place-items: center; aspect-ratio: 1; }
        .atsm h4 { margin: 0 0 4px; font: 600 14px Inter, system-ui; }
        .atsm p { margin: 0 0 8px; font: 400 12px/1.6 Inter, system-ui; }
        .atsm a { color: #0C66E4; font: 500 14px Inter, system-ui; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <section class="atsm" data-tone="warning">
            <span class="atsm-icon" aria-hidden="true">!</span>
            <div>
                <h4>Your API token expires in 7 days</h4>
                <p>Anything still using it will start failing after that.</p>
                <a href="#">Rotate the token</a>
            </div>
        </section>

        <style>
        .atsm { display: flex; gap: 12px; padding: 16px; border-radius: 3px;
                background: #FFFAE6; color: #172B4D; }
        .atsm[data-tone='error'] { background: #FFEDEB; }
        .atsm-icon { flex: none; display: grid; place-items: center; aspect-ratio: 1; }
        .atsm h4 { margin: 0 0 4px; font: 600 14px Inter, system-ui; }
        .atsm p { margin: 0 0 8px; font: 400 12px/1.6 Inter, system-ui; }
        .atsm a { color: #0C66E4; font: 500 14px Inter, system-ui; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Token.jsx — Inertia page; renders the React snippet
        // below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import TokenNotice from '@/components/TokenNotice';

        export default function Token() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <TokenNotice />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // TokenNotice.jsx — section message: four severities over a settings card
        import { useState } from 'react';
        import '@nabuxai/ui-core/css';

        const SEVERITIES = {
          info: { tint: '#E9F2FF', ink: '#0055CC', title: 'Your token works with the v2 API',
            body: 'Calls made with this token count against 600 requests per hour.', action: 'Read the rate limits' },
          warning: { tint: '#FFFAE6', ink: '#A54800', title: 'This token expires in 7 days',
            body: 'Anything still using it will start failing after that.', action: 'Rotate the token' },
          error: { tint: '#FFEDEB', ink: '#AE2A19', title: 'The last call was rejected',
            body: 'The webhook returned 401 — the token no longer matches this workspace.', action: 'See the failed calls' },
          success: { tint: '#DCFFF1', ink: '#1F845A', title: 'Token rotated successfully',
            body: 'The new secret was copied to your clipboard; the old one stays valid for 24 hours.', action: 'View audit log' },
        };

        export default function TokenNotice() {
          const [tone, setTone] = useState('warning');
          const msg = SEVERITIES[tone];

          return (
            <>
              <style>{`
                .atsm-panel { display: grid; gap: 1rem; max-inline-size: 26rem; padding: 1.25rem;
                              border: 1px solid #DFE1E6; border-radius: 6px; background: #fff;
                              font-family: Inter, system-ui, sans-serif; }
                .atsm-seg { display: flex; flex-wrap: wrap; gap: .4rem; }
                .atsm-seg button { block-size: 1.75rem; padding-inline: .65rem; border: 1px solid #DFE1E6;
                                   border-radius: 3px; background: none; cursor: pointer;
                                   font: 500 .76rem/1 Inter, system-ui; color: #44546F; }
                .atsm-seg button[aria-pressed='true'] { background: #0C66E4; border-color: #0C66E4; color: #fff; }
                .atsm { display: flex; gap: .75rem; padding: 1rem; border-radius: 3px; align-items: flex-start; }
                .atsm-icon { flex: none; display: grid; place-items: center; inline-size: 1.25rem; aspect-ratio: 1;
                             border-radius: 2px; color: #fff; font: 700 .8rem/1 Inter, system-ui; }
                .atsm h5 { margin: 0 0 .2rem; font: 600 .85rem/1.4 Inter, system-ui; color: #172B4D; }
                .atsm p { margin: 0 0 .5rem; font: 400 .8rem/1.6 Inter, system-ui; color: #172B4D; }
                .atsm a { color: #0C66E4; font: 500 .8rem Inter, system-ui; }
              `}</style>
              <div className="atsm-panel">
                <div className="atsm-seg" role="group" aria-label="Severity">
                  {Object.keys(SEVERITIES).map((k) => (
                    <button type="button" key={k} aria-pressed={tone === k} onClick={() => setTone(k)}>
                      {k}
                    </button>
                  ))}
                </div>
                {/* Icon square and tint both follow the severity. */}
                <section className="atsm" style={{ background: msg.tint }}>
                  <span className="atsm-icon" style={{ background: msg.ink }} aria-hidden="true">!</span>
                  <div>
                    <h5>{msg.title}</h5>
                    <p>{msg.body}</p>
                    <a href="#">{msg.action}</a>
                  </div>
                </section>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- TokenNotice.vue — section message: four severities over a settings card -->
        <script setup lang="ts">
        import { computed, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const severities = {
          info: { tint: '#E9F2FF', ink: '#0055CC', title: 'Your token works with the v2 API',
            body: 'Calls made with this token count against 600 requests per hour.', action: 'Read the rate limits' },
          warning: { tint: '#FFFAE6', ink: '#A54800', title: 'This token expires in 7 days',
            body: 'Anything still using it will start failing after that.', action: 'Rotate the token' },
          error: { tint: '#FFEDEB', ink: '#AE2A19', title: 'The last call was rejected',
            body: 'The webhook returned 401 — the token no longer matches this workspace.', action: 'See the failed calls' },
          success: { tint: '#DCFFF1', ink: '#1F845A', title: 'Token rotated successfully',
            body: 'The new secret was copied to your clipboard; the old one stays valid for 24 hours.', action: 'View audit log' },
        };
        const tone = ref<keyof typeof severities>('warning');
        const msg = computed(() => severities[tone.value]);
        </script>

        <template>
          <div class="atsm-panel">
            <div class="atsm-seg" role="group" aria-label="Severity">
              <button v-for="(v, k) in severities" :key="k" type="button"
                :aria-pressed="tone === k" @click="tone = k">{{ k }}</button>
            </div>
            <!-- Icon square and tint both follow the severity. -->
            <section class="atsm" :style="{ background: msg.tint }">
              <span class="atsm-icon" :style="{ background: msg.ink }" aria-hidden="true">!</span>
              <div>
                <h5>{{ msg.title }}</h5>
                <p>{{ msg.body }}</p>
                <a href="#">{{ msg.action }}</a>
              </div>
            </section>
          </div>
        </template>

        <style scoped>
        .atsm-panel { display: grid; gap: 1rem; max-inline-size: 26rem; padding: 1.25rem;
                      border: 1px solid #DFE1E6; border-radius: 6px; background: #fff;
                      font-family: Inter, system-ui, sans-serif; }
        .atsm-seg { display: flex; flex-wrap: wrap; gap: .4rem; }
        .atsm-seg button { block-size: 1.75rem; padding-inline: .65rem; border: 1px solid #DFE1E6;
                           border-radius: 3px; background: none; cursor: pointer;
                           font: 500 .76rem/1 Inter, system-ui; color: #44546F; }
        .atsm-seg button[aria-pressed='true'] { background: #0C66E4; border-color: #0C66E4; color: #fff; }
        .atsm { display: flex; gap: .75rem; padding: 1rem; border-radius: 3px; align-items: flex-start; }
        .atsm-icon { flex: none; display: grid; place-items: center; inline-size: 1.25rem; aspect-ratio: 1;
                     border-radius: 2px; color: #fff; font: 700 .8rem/1 Inter, system-ui; }
        .atsm h5 { margin: 0 0 .2rem; font: 600 .85rem/1.4 Inter, system-ui; color: #172B4D; }
        .atsm p { margin: 0 0 .5rem; font: 400 .8rem/1.6 Inter, system-ui; color: #172B4D; }
        .atsm a { color: #0C66E4; font: 500 .8rem Inter, system-ui; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- TokenNotice.svelte — section message: four severities over a settings card -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const severities = {
            info: { tint: '#E9F2FF', ink: '#0055CC', title: 'Your token works with the v2 API',
              body: 'Calls made with this token count against 600 requests per hour.', action: 'Read the rate limits' },
            warning: { tint: '#FFFAE6', ink: '#A54800', title: 'This token expires in 7 days',
              body: 'Anything still using it will start failing after that.', action: 'Rotate the token' },
            error: { tint: '#FFEDEB', ink: '#AE2A19', title: 'The last call was rejected',
              body: 'The webhook returned 401 — the token no longer matches this workspace.', action: 'See the failed calls' },
            success: { tint: '#DCFFF1', ink: '#1F845A', title: 'Token rotated successfully',
              body: 'The new secret was copied to your clipboard; the old one stays valid for 24 hours.', action: 'View audit log' },
          };
          type Tone = keyof typeof severities;
          let tone = $state<Tone>('warning');
          const msg = $derived(severities[tone]);
        </script>

        <div class="atsm-panel">
          <div class="atsm-seg" role="group" aria-label="Severity">
            {#each Object.entries(severities) as [k] (k)}
              <button type="button" aria-pressed={tone === k} onclick={() => (tone = k as Tone)}>{k}</button>
            {/each}
          </div>
          <!-- Icon square and tint both follow the severity. -->
          <section class="atsm" style:background={msg.tint}>
            <span class="atsm-icon" style:background={msg.ink} aria-hidden="true">!</span>
            <div>
              <h5>{msg.title}</h5>
              <p>{msg.body}</p>
              <a href="#/help/rate-limits">{msg.action}</a>
            </div>
          </section>
        </div>

        <style>
        .atsm-panel { display: grid; gap: 1rem; max-inline-size: 26rem; padding: 1.25rem;
                      border: 1px solid #DFE1E6; border-radius: 6px; background: #fff;
                      font-family: Inter, system-ui, sans-serif; }
        .atsm-seg { display: flex; flex-wrap: wrap; gap: .4rem; }
        .atsm-seg button { block-size: 1.75rem; padding-inline: .65rem; border: 1px solid #DFE1E6;
                           border-radius: 3px; background: none; cursor: pointer;
                           font: 500 .76rem/1 Inter, system-ui; color: #44546F; }
        .atsm-seg button[aria-pressed='true'] { background: #0C66E4; border-color: #0C66E4; color: #fff; }
        .atsm { display: flex; gap: .75rem; padding: 1rem; border-radius: 3px; align-items: flex-start; }
        .atsm-icon { flex: none; display: grid; place-items: center; inline-size: 1.25rem; aspect-ratio: 1;
                     border-radius: 2px; color: #fff; font: 700 .8rem/1 Inter, system-ui; }
        .atsm h5 { margin: 0 0 .2rem; font: 600 .85rem/1.4 Inter, system-ui; color: #172B4D; }
        .atsm p { margin: 0 0 .5rem; font: 400 .8rem/1.6 Inter, system-ui; color: #172B4D; }
        .atsm a { color: #0C66E4; font: 500 .8rem Inter, system-ui; }
        </style>
        SVELTE,
        ],
    ],

    'flag' => [
        'title' => ['fa' => 'فلگ (توست اطلسیان)', 'en' => 'Flag'],
        'icon' => 'bell',
        'oneLiner' => [
            'fa' => 'توستِ گوشهٔ صفحه با آیکن مربعی رنگی، عنوان، توضیح و دکمهٔ بستن — الگوی بازخورد جیرا که یک نگاه شناسایش می‌کند.',
            'en' => 'Corner toast with a square colored icon, title, body and dismiss — the instantly recognizable Jira feedback pattern.',
        ],
        'js' => true,
        'docs' => 'https://atlassian.design/components/flag/usage',
        'props' => [
            ['name' => 'appearance', 'type' => 'string', 'default' => "'information'", 'note' => [
                'fa' => 'رنگ آیکن مربعی را تعیین می‌کند: آبی، سبز، زرد، قرمز.',
                'en' => 'Sets the square icon’s colour: blue, green, yellow, red.',
            ]],
            ['name' => 'title', 'type' => 'string', 'default' => "''", 'note' => [
                'fa' => 'سرخط نیم‌ضخیم؛ خلاصهٔ آنچه رخ داد.',
                'en' => 'The semibold headline; a summary of what happened.',
            ]],
            ['name' => 'actions', 'type' => 'slot', 'default' => 'null', 'note' => [
                'fa' => 'لینک اکشن مثل «واگرد»، زیر توضیح.',
                'en' => 'The action link, like «Undo», under the body.',
            ]],
            ['name' => 'autoDismiss', 'type' => 'seconds', 'default' => "'8'", 'note' => [
                'fa' => 'پس از ۸ ثانیه خودش می‌رود؛ خطاها منتظر می‌مانند.',
                'en' => 'Leaves by itself after 8s; errors wait.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="atfl" role="status">
            <span class="atfl-icon" data-tone="success" aria-hidden="true">✓</span>
            <div>
                <h4>تغییرات منتشر شد</h4>
                <p>نسخهٔ ۲٫۴ برای همهٔ همکاران قابل مشاهده است.</p>
                <button class="atfl-action">واگرد</button>
            </div>
            <button class="atfl-close" aria-label="بستن">×</button>
        </div>

        <style>
        .atfl { display: flex; gap: 12px; inline-size: 368px; max-inline-size: 100%;
                padding: 12px; border-radius: 4px; background: #FFFFFF;
                box-shadow: 0 8px 12px #091E4229, 0 0 1px #091E4240; }
        .atfl-icon { flex: none; display: grid; place-items: center; inline-size: 20px;
                     aspect-ratio: 1; border-radius: 2px; background: #1F845A; color: #fff; }
        .atfl h4 { margin: 0; font: 600 14px Inter, system-ui; color: #172B4D; }
        .atfl p { margin: 2px 0 4px; font: 400 12px/1.6 Inter, system-ui; color: #44546F; }
        .atfl-close { margin-inline-start: auto; align-self: flex-start; border: none;
                      background: none; color: #626F86; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="atfl" role="status">
            <span class="atfl-icon" data-tone="success" aria-hidden="true">✓</span>
            <div>
                <h4>Changes shipped</h4>
                <p>Version 2.4 is now visible to every teammate.</p>
                <button class="atfl-action">Undo</button>
            </div>
            <button class="atfl-close" aria-label="Dismiss">×</button>
        </div>

        <style>
        .atfl { display: flex; gap: 12px; inline-size: 368px; max-inline-size: 100%;
                padding: 12px; border-radius: 4px; background: #FFFFFF;
                box-shadow: 0 8px 12px #091E4229, 0 0 1px #091E4240; }
        .atfl-icon { flex: none; display: grid; place-items: center; inline-size: 20px;
                     aspect-ratio: 1; border-radius: 2px; background: #1F845A; color: #fff; }
        .atfl h4 { margin: 0; font: 600 14px Inter, system-ui; color: #172B4D; }
        .atfl p { margin: 2px 0 4px; font: 400 12px/1.6 Inter, system-ui; color: #44546F; }
        .atfl-close { margin-inline-start: auto; align-self: flex-start; border: none;
                      background: none; color: #626F86; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Release.jsx — Inertia page; renders the React snippet
        // below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import ReleaseFlag from '@/components/ReleaseFlag';

        export default function Release() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <ReleaseFlag />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // ReleaseFlag.jsx — the Atlassian flag: success auto-dismisses, errors wait
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        export default function ReleaseFlag() {
          const [flag, setFlag] = useState(null); // null | 'success' | 'error'
          const timer = useRef(null);

          // Errors stay until dismissed; the rest leave on their own timer.
          const show = (kind, wait) => {
            clearTimeout(timer.current);
            setFlag(kind);
            if (wait) timer.current = setTimeout(() => setFlag(null), 6000);
          };
          useEffect(() => () => clearTimeout(timer.current), []);

          return (
            <>
              <style>{`
                .atfl-stage { position: relative; display: grid; gap: .9rem; align-content: start;
                              max-inline-size: 26rem; min-block-size: 14rem; padding: 1.25rem;
                              border: 1px solid #DFE1E6; border-radius: 6px; background: #F7F8F9;
                              font-family: Inter, system-ui, sans-serif; }
                .atfl-btn { block-size: 2rem; padding-inline: .8rem; justify-self: start; border: none;
                            border-radius: 3px; cursor: pointer; background: #0C66E4; color: #fff;
                            font: 500 .8rem/1 Inter, system-ui; }
                /* Flags stack at the stage corner, like Atlaskit's flag group. */
                .atfl { position: absolute; inset-inline: 1rem; inset-block-end: 1rem; display: flex; gap: .75rem;
                        padding: .8rem .9rem; border-radius: 4px; background: #fff; text-align: start;
                        box-shadow: 0 8px 12px rgba(9, 30, 66, .16), 0 0 1px rgba(9, 30, 66, .25); }
                .atfl-ic { flex: none; display: grid; place-items: center; inline-size: 1.25rem; aspect-ratio: 1;
                           border-radius: 2px; color: #fff; font: 700 .8rem/1 Inter, system-ui; }
                .atfl-body { flex: 1; }
                .atfl-body h5 { margin: 0; font: 600 .82rem/1.4 Inter, system-ui; color: #172B4D; }
                .atfl-body p { margin: .15rem 0 .35rem; font: 400 .76rem/1.55 Inter, system-ui; color: #44546F; }
                .atfl-body button { border: none; background: none; padding: 0; cursor: pointer;
                                    font: 600 .76rem Inter, system-ui; color: #0C66E4; }
                .atfl-x { border: none; background: none; cursor: pointer; color: #626F86; }
              `}</style>
              <div className="atfl-stage">
                <button type="button" className="atfl-btn" onClick={() => show('success', true)}>
                  Publish release 2.4
                </button>
                <button type="button" className="atfl-btn" style={{ background: '#F1F2F4', color: '#172B4D' }}
                  onClick={() => show('error', false)}>
                  Simulate offline publish
                </button>
                {flag && (
                  <div className="atfl" role="status">
                    <span className="atfl-ic" aria-hidden="true"
                      style={{ background: flag === 'success' ? '#1F845A' : '#B40000' }}>
                      {flag === 'success' ? '✓' : '!'}
                    </span>
                    <div className="atfl-body">
                      <h5>{flag === 'success' ? 'Version 2.4 shipped' : 'Release failed — you are offline'}</h5>
                      <p>{flag === 'success'
                        ? 'Live for every teammate on web and mobile.'
                        : 'The build is safe; we will not half-publish it.'}</p>
                      {flag === 'success'
                        ? <button type="button" onClick={() => setFlag(null)}>Undo</button>
                        : <button type="button" onClick={() => show('success', true)}>Retry</button>}
                    </div>
                    <button type="button" className="atfl-x" aria-label="Dismiss" onClick={() => setFlag(null)}>×</button>
                  </div>
                )}
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- ReleaseFlag.vue — the Atlassian flag: success auto-dismisses, errors wait -->
        <script setup lang="ts">
        import { onBeforeUnmount, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const flag = ref<'success' | 'error' | null>(null);
        let timer: ReturnType<typeof setTimeout> | undefined;

        // Errors stay until dismissed; the rest leave on their own timer.
        function show(kind: 'success' | 'error', wait: boolean) {
          clearTimeout(timer);
          flag.value = kind;
          if (wait) timer = setTimeout(() => (flag.value = null), 6000);
        }
        onBeforeUnmount(() => clearTimeout(timer));
        </script>

        <template>
          <div class="atfl-stage">
            <button type="button" class="atfl-btn" @click="show('success', true)">Publish release 2.4</button>
            <button type="button" class="atfl-btn" style="background: #F1F2F4; color: #172B4D"
              @click="show('error', false)">Simulate offline publish</button>
            <div v-if="flag" class="atfl" role="status">
              <span class="atfl-ic" aria-hidden="true"
                :style="{ background: flag === 'success' ? '#1F845A' : '#B40000' }">
                {{ flag === 'success' ? '✓' : '!' }}
              </span>
              <div class="atfl-body">
                <h5>{{ flag === 'success' ? 'Version 2.4 shipped' : 'Release failed — you are offline' }}</h5>
                <p>{{ flag === 'success'
                  ? 'Live for every teammate on web and mobile.'
                  : 'The build is safe; we will not half-publish it.' }}</p>
                <button v-if="flag === 'success'" type="button" @click="flag = null">Undo</button>
                <button v-else type="button" @click="show('success', true)">Retry</button>
              </div>
              <button type="button" class="atfl-x" aria-label="Dismiss" @click="flag = null">×</button>
            </div>
          </div>
        </template>

        <style scoped>
        .atfl-stage { position: relative; display: grid; gap: .9rem; align-content: start;
                      max-inline-size: 26rem; min-block-size: 14rem; padding: 1.25rem;
                      border: 1px solid #DFE1E6; border-radius: 6px; background: #F7F8F9;
                      font-family: Inter, system-ui, sans-serif; }
        .atfl-btn { block-size: 2rem; padding-inline: .8rem; justify-self: start; border: none;
                    border-radius: 3px; cursor: pointer; background: #0C66E4; color: #fff;
                    font: 500 .8rem/1 Inter, system-ui; }
        /* Flags stack at the stage corner, like Atlaskit's flag group. */
        .atfl { position: absolute; inset-inline: 1rem; inset-block-end: 1rem; display: flex; gap: .75rem;
                padding: .8rem .9rem; border-radius: 4px; background: #fff; text-align: start;
                box-shadow: 0 8px 12px rgba(9, 30, 66, .16), 0 0 1px rgba(9, 30, 66, .25); }
        .atfl-ic { flex: none; display: grid; place-items: center; inline-size: 1.25rem; aspect-ratio: 1;
                   border-radius: 2px; color: #fff; font: 700 .8rem/1 Inter, system-ui; }
        .atfl-body { flex: 1; }
        .atfl-body h5 { margin: 0; font: 600 .82rem/1.4 Inter, system-ui; color: #172B4D; }
        .atfl-body p { margin: .15rem 0 .35rem; font: 400 .76rem/1.55 Inter, system-ui; color: #44546F; }
        .atfl-body button { border: none; background: none; padding: 0; cursor: pointer;
                            font: 600 .76rem Inter, system-ui; color: #0C66E4; }
        .atfl-x { border: none; background: none; cursor: pointer; color: #626F86; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- ReleaseFlag.svelte — the Atlassian flag: success auto-dismisses, errors wait -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          let flag = $state<'success' | 'error' | null>(null);
          let timer: ReturnType<typeof setTimeout> | undefined;

          // Errors stay until dismissed; the rest leave on their own timer.
          function show(kind: 'success' | 'error', wait: boolean) {
            clearTimeout(timer);
            flag = kind;
            if (wait) timer = setTimeout(() => (flag = null), 6000);
          }
          onMount(() => () => clearTimeout(timer));
        </script>

        <div class="atfl-stage">
          <button type="button" class="atfl-btn" onclick={() => show('success', true)}>Publish release 2.4</button>
          <button type="button" class="atfl-btn" style="background: #F1F2F4; color: #172B4D"
            onclick={() => show('error', false)}>Simulate offline publish</button>
          {#if flag}
            <div class="atfl" role="status">
              <span class="atfl-ic" aria-hidden="true"
                style:background={flag === 'success' ? '#1F845A' : '#B40000'}>
                {flag === 'success' ? '✓' : '!'}
              </span>
              <div class="atfl-body">
                <h5>{flag === 'success' ? 'Version 2.4 shipped' : 'Release failed — you are offline'}</h5>
                <p>{flag === 'success'
                  ? 'Live for every teammate on web and mobile.'
                  : 'The build is safe; we will not half-publish it.'}</p>
                {#if flag === 'success'}
                  <button type="button" onclick={() => (flag = null)}>Undo</button>
                {:else}
                  <button type="button" onclick={() => show('success', true)}>Retry</button>
                {/if}
              </div>
              <button type="button" class="atfl-x" aria-label="Dismiss" onclick={() => (flag = null)}>×</button>
            </div>
          {/if}
        </div>

        <style>
        .atfl-stage { position: relative; display: grid; gap: .9rem; align-content: start;
                      max-inline-size: 26rem; min-block-size: 14rem; padding: 1.25rem;
                      border: 1px solid #DFE1E6; border-radius: 6px; background: #F7F8F9;
                      font-family: Inter, system-ui, sans-serif; }
        .atfl-btn { block-size: 2rem; padding-inline: .8rem; justify-self: start; border: none;
                    border-radius: 3px; cursor: pointer; background: #0C66E4; color: #fff;
                    font: 500 .8rem/1 Inter, system-ui; }
        /* Flags stack at the stage corner, like Atlaskit's flag group. */
        .atfl { position: absolute; inset-inline: 1rem; inset-block-end: 1rem; display: flex; gap: .75rem;
                padding: .8rem .9rem; border-radius: 4px; background: #fff; text-align: start;
                box-shadow: 0 8px 12px rgba(9, 30, 66, .16), 0 0 1px rgba(9, 30, 66, .25); }
        .atfl-ic { flex: none; display: grid; place-items: center; inline-size: 1.25rem; aspect-ratio: 1;
                   border-radius: 2px; color: #fff; font: 700 .8rem/1 Inter, system-ui; }
        .atfl-body { flex: 1; }
        .atfl-body h5 { margin: 0; font: 600 .82rem/1.4 Inter, system-ui; color: #172B4D; }
        .atfl-body p { margin: .15rem 0 .35rem; font: 400 .76rem/1.55 Inter, system-ui; color: #44546F; }
        .atfl-body button { border: none; background: none; padding: 0; cursor: pointer;
                            font: 600 .76rem Inter, system-ui; color: #0C66E4; }
        .atfl-x { border: none; background: none; cursor: pointer; color: #626F86; }
        </style>
        SVELTE,
        ],
    ],

    'inline-message' => [
        'title' => ['fa' => 'پیام درون‌خطی', 'en' => 'Inline Message'],
        'icon' => 'alert-circle',
        'oneLiner' => [
            'fa' => 'آیکن رنگی کوچک و متن کوتاه که همان‌جا داخل فرم و محتوا می‌نشیند — راهنما و خطا را دقیقاً سر جای اتفاقش توضیح می‌دهد.',
            'en' => 'A small colored icon plus short text sitting right inside forms and content — help or error explained at the exact spot it happens.',
        ],
        'js' => true,
        'docs' => 'https://atlassian.design/components/inline-message/usage',
        'props' => [
            ['name' => 'appearance', 'type' => 'string', 'default' => "'info'", 'note' => [
                'fa' => 'info · confirmation · warning · error · discovery؛ فقط رنگ آیکن عوض می‌شود.',
                'en' => 'info · confirmation · warning · error · discovery; only the icon colour changes.',
            ]],
            ['name' => 'title', 'type' => 'string', 'default' => "''", 'note' => [
                'fa' => 'متن نیم‌ضخیم کنار آیکن؛ همیشه در یک خط.',
                'en' => 'The semibold text beside the icon; always one line.',
            ]],
            ['name' => 'secondaryText', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'متن کم‌رنگ‌تر زیر سرخط برای توضیح بیشتر.',
                'en' => 'The quieter text under the title for extra detail.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <span class="atim" data-tone="error">
            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16.5v.5"/></svg>
            <span><b>این نشانی قبلاً ثبت شده است</b><small>یک نشانی دیگر امتحان کنید.</small></span>
        </span>

        <style>
        .atim { display: inline-flex; gap: 8px; max-inline-size: 100%; }
        .atim svg { inline-size: 16px; flex: none; margin-block-start: 1px; stroke: #CA3521;
                    fill: none; stroke-width: 2; stroke-linecap: round; }
        .atim b { display: block; font: 600 12px/1.5 Inter, system-ui; color: #172B4D; }
        .atim small { display: block; font: 400 12px/1.5 Inter, system-ui; color: #626F86; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <span class="atim" data-tone="error">
            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16.5v.5"/></svg>
            <span><b>This email is already registered</b><small>Try a different address.</small></span>
        </span>

        <style>
        .atim { display: inline-flex; gap: 8px; max-inline-size: 100%; }
        .atim svg { inline-size: 16px; flex: none; margin-block-start: 1px; stroke: #CA3521;
                    fill: none; stroke-width: 2; stroke-linecap: round; }
        .atim b { display: block; font: 600 12px/1.5 Inter, system-ui; color: #172B4D; }
        .atim small { display: block; font: 400 12px/1.5 Inter, system-ui; color: #626F86; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Invite.jsx — Inertia page; renders the React snippet
        // below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import InviteField from '@/components/InviteField';

        export default function Invite() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <InviteField />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // InviteField.jsx — inline message: help or error at the exact spot it happens
        import { useState } from 'react';
        import '@nabuxai/ui-core/css';

        const EXISTING = ['lena@nabu.example', 'marco@nabu.example'];
        const ICONS = {
          error: 'M12 8v5M12 16.5v.5',
          warning: 'M12 6v7M12 16.5v.5',
          confirmation: 'M5 12l5 5L20 7',
        };
        const INK = { error: '#CA3521', warning: '#A54800', confirmation: '#1F845A' };
        const COPY = {
          error: ['This address is not valid', 'Check for typos and try again.'],
          warning: ['This teammate is already in', 'They will not get a second invite.'],
          confirmation: ['Invite sent', 'They will see the workspace on next sign-in.'],
        };

        export default function InviteField() {
          const [email, setEmail] = useState('');
          const [sent, setSent] = useState(false);

          // Only the icon colour changes between the five appearances.
          const state = !email.trim() ? 'idle'
            : !/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email.trim().toLowerCase()) ? 'error'
            : EXISTING.includes(email.trim().toLowerCase()) ? 'warning' : 'ok';

          const send = () => { if (state === 'ok') { setSent(true); setEmail(''); } };
          const msg = (tone) => (
            <span className="atim" data-tone={tone}>
              <svg viewBox="0 0 24 24" aria-hidden="true" style={{ stroke: INK[tone] }}>
                <circle cx="12" cy="12" r="10" /><path d={ICONS[tone]} />
              </svg>
              <span><b>{COPY[tone][0]}</b><small>{COPY[tone][1]}</small></span>
            </span>
          );

          return (
            <>
              <style>{`
                .atim-panel { display: grid; gap: .5rem; max-inline-size: 22rem; padding: 1.25rem;
                              border: 1px solid #DFE1E6; border-radius: 6px; background: #fff;
                              font-family: Inter, system-ui, sans-serif; }
                .atim-panel label { font: 500 .78rem Inter, system-ui; color: #44546F; }
                .atim-panel input { inline-size: 100%; box-sizing: border-box; block-size: 2.25rem;
                                    padding-inline: .65rem; border: 1px solid #DFE1E6; border-radius: 4px;
                                    font: 400 .85rem Inter, system-ui; }
                .atim-panel input[data-state='error'] { border-color: #CA3521; }
                .atim { display: inline-flex; gap: .5rem; align-items: flex-start; }
                .atim svg { flex: none; inline-size: 1rem; block-size: 1rem; margin-block-start: .1rem;
                            fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
                .atim b { display: block; font: 600 .78rem/1.5 Inter, system-ui; color: #172B4D; }
                .atim small { display: block; font: 400 .74rem/1.5 Inter, system-ui; color: #626F86; }
                .atim-btn { justify-self: start; block-size: 2rem; padding-inline: .8rem; border: none;
                            border-radius: 3px; cursor: pointer; background: #0C66E4; color: #fff;
                            font: 500 .8rem/1 Inter, system-ui; }
                .atim-btn:disabled { cursor: not-allowed; background: #A5ADBA; }
              `}</style>
              <div className="atim-panel">
                <label htmlFor="invite">Invite by email</label>
                <input id="invite" type="email" value={email} placeholder="teammate@company.com"
                  data-state={state === 'error' ? 'error' : undefined}
                  onChange={(e) => { setEmail(e.target.value); setSent(false); }} />
                {/* The message sits exactly where the mistake happens. */}
                {(state === 'error' || state === 'warning') && msg(state)}
                {sent && msg('confirmation')}
                <button type="button" className="atim-btn" disabled={state !== 'ok'} onClick={send}>
                  Send invite
                </button>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- InviteField.vue — inline message: help or error at the exact spot it happens -->
        <script setup lang="ts">
        import { computed, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const existing = ['lena@nabu.example', 'marco@nabu.example'];
        const icons = { error: 'M12 8v5M12 16.5v.5', warning: 'M12 6v7M12 16.5v.5', confirmation: 'M5 12l5 5L20 7' };
        const ink = { error: '#CA3521', warning: '#A54800', confirmation: '#1F845A' };
        const copy = {
          error: ['This address is not valid', 'Check for typos and try again.'],
          warning: ['This teammate is already in', 'They will not get a second invite.'],
          confirmation: ['Invite sent', 'They will see the workspace on next sign-in.'],
        };

        const email = ref('');
        const sent = ref(false);
        // Only the icon colour changes between the five appearances.
        const state = computed(() => {
          const v = email.value.trim().toLowerCase();
          if (!v) return 'idle';
          if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v)) return 'error';
          if (existing.includes(v)) return 'warning';
          return 'ok';
        });
        function send() {
          if (state.value !== 'ok') return;
          sent.value = true;
          email.value = '';
        }
        </script>

        <template>
          <div class="atim-panel">
            <label for="invite">Invite by email</label>
            <input id="invite" v-model="email" type="email" placeholder="teammate@company.com"
              :data-state="state === 'error' ? 'error' : undefined" @input="sent = false" />
            <!-- The message sits exactly where the mistake happens. -->
            <span v-if="state === 'error' || state === 'warning'" class="atim" :data-tone="state">
              <svg viewBox="0 0 24 24" aria-hidden="true" :style="{ stroke: ink[state] }">
                <circle cx="12" cy="12" r="10" /><path :d="icons[state]" />
              </svg>
              <span><b>{{ copy[state][0] }}</b><small>{{ copy[state][1] }}</small></span>
            </span>
            <span v-if="sent" class="atim" data-tone="confirmation">
              <svg viewBox="0 0 24 24" aria-hidden="true" :style="{ stroke: ink.confirmation }">
                <circle cx="12" cy="12" r="10" /><path :d="icons.confirmation" />
              </svg>
              <span><b>{{ copy.confirmation[0] }}</b><small>{{ copy.confirmation[1] }}</small></span>
            </span>
            <button type="button" class="atim-btn" :disabled="state !== 'ok'" @click="send">Send invite</button>
          </div>
        </template>

        <style scoped>
        .atim-panel { display: grid; gap: .5rem; max-inline-size: 22rem; padding: 1.25rem;
                      border: 1px solid #DFE1E6; border-radius: 6px; background: #fff;
                      font-family: Inter, system-ui, sans-serif; }
        .atim-panel label { font: 500 .78rem Inter, system-ui; color: #44546F; }
        .atim-panel input { inline-size: 100%; box-sizing: border-box; block-size: 2.25rem;
                            padding-inline: .65rem; border: 1px solid #DFE1E6; border-radius: 4px;
                            font: 400 .85rem Inter, system-ui; }
        .atim-panel input[data-state='error'] { border-color: #CA3521; }
        .atim { display: inline-flex; gap: .5rem; align-items: flex-start; }
        .atim svg { flex: none; inline-size: 1rem; block-size: 1rem; margin-block-start: .1rem;
                    fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .atim b { display: block; font: 600 .78rem/1.5 Inter, system-ui; color: #172B4D; }
        .atim small { display: block; font: 400 .74rem/1.5 Inter, system-ui; color: #626F86; }
        .atim-btn { justify-self: start; block-size: 2rem; padding-inline: .8rem; border: none;
                    border-radius: 3px; cursor: pointer; background: #0C66E4; color: #fff;
                    font: 500 .8rem/1 Inter, system-ui; }
        .atim-btn:disabled { cursor: not-allowed; background: #A5ADBA; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- InviteField.svelte — inline message: help or error at the exact spot it happens -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const existing = ['lena@nabu.example', 'marco@nabu.example'];
          const icons = { error: 'M12 8v5M12 16.5v.5', warning: 'M12 6v7M12 16.5v.5', confirmation: 'M5 12l5 5L20 7' };
          const ink = { error: '#CA3521', warning: '#A54800', confirmation: '#1F845A' };
          const copy = {
            error: ['This address is not valid', 'Check for typos and try again.'],
            warning: ['This teammate is already in', 'They will not get a second invite.'],
            confirmation: ['Invite sent', 'They will see the workspace on next sign-in.'],
          };

          let email = $state('');
          let sent = $state(false);
          // Only the icon colour changes between the five appearances.
          const state = $derived.by(() => {
            const v = email.trim().toLowerCase();
            if (!v) return 'idle';
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v)) return 'error';
            if (existing.includes(v)) return 'warning';
            return 'ok';
          });

          function send() {
            if (state !== 'ok') return;
            sent = true;
            email = '';
          }
        </script>

        <div class="atim-panel">
          <label for="invite">Invite by email</label>
          <input id="invite" type="email" bind:value={email} placeholder="teammate@company.com"
            data-state={state === 'error' ? 'error' : undefined} oninput={() => (sent = false)} />
          <!-- The message sits exactly where the mistake happens. -->
          {#if state === 'error' || state === 'warning'}
            <span class="atim" data-tone={state}>
              <svg viewBox="0 0 24 24" aria-hidden="true" style:stroke={ink[state]}>
                <circle cx="12" cy="12" r="10" /><path d={icons[state]} />
              </svg>
              <span><b>{copy[state][0]}</b><small>{copy[state][1]}</small></span>
            </span>
          {/if}
          {#if sent}
            <span class="atim" data-tone="confirmation">
              <svg viewBox="0 0 24 24" aria-hidden="true" style:stroke={ink.confirmation}>
                <circle cx="12" cy="12" r="10" /><path d={icons.confirmation} />
              </svg>
              <span><b>{copy.confirmation[0]}</b><small>{copy.confirmation[1]}</small></span>
            </span>
          {/if}
          <button type="button" class="atim-btn" disabled={state !== 'ok'} onclick={send}>Send invite</button>
        </div>

        <style>
        .atim-panel { display: grid; gap: .5rem; max-inline-size: 22rem; padding: 1.25rem;
                      border: 1px solid #DFE1E6; border-radius: 6px; background: #fff;
                      font-family: Inter, system-ui, sans-serif; }
        .atim-panel label { font: 500 .78rem Inter, system-ui; color: #44546F; }
        .atim-panel input { inline-size: 100%; box-sizing: border-box; block-size: 2.25rem;
                            padding-inline: .65rem; border: 1px solid #DFE1E6; border-radius: 4px;
                            font: 400 .85rem Inter, system-ui; }
        .atim-panel input[data-state='error'] { border-color: #CA3521; }
        .atim { display: inline-flex; gap: .5rem; align-items: flex-start; }
        .atim svg { flex: none; inline-size: 1rem; block-size: 1rem; margin-block-start: .1rem;
                    fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .atim b { display: block; font: 600 .78rem/1.5 Inter, system-ui; color: #172B4D; }
        .atim small { display: block; font: 400 .74rem/1.5 Inter, system-ui; color: #626F86; }
        .atim-btn { justify-self: start; block-size: 2rem; padding-inline: .8rem; border: none;
                    border-radius: 3px; cursor: pointer; background: #0C66E4; color: #fff;
                    font: 500 .8rem/1 Inter, system-ui; }
        .atim-btn:disabled { cursor: not-allowed; background: #A5ADBA; }
        </style>
        SVELTE,
        ],
    ],

    'spotlight' => [
        'title' => ['fa' => 'اسپات‌لایت آنبوردینگ', 'en' => 'Spotlight'],
        'icon' => 'wand',
        'oneLiner' => [
            'fa' => 'پردهٔ مودالی که همه‌جا را تاریک می‌کند جز هدف آموزش — تصویر، توضیح و دکمه‌های Skip و Next برای تور معرفی محصول.',
            'en' => 'A modal veil that dims everything but the teaching target — image, copy and Skip/Next actions for classic product onboarding tours.',
        ],
        'js' => true,
        'docs' => 'https://atlassian.design/components/onboarding/usage',
        'props' => [
            ['name' => 'target', 'type' => 'element', 'default' => 'null', 'note' => [
                'fa' => 'عنصری که روشن می‌ماند؛ بقیهٔ صفحه با ۷۷٪ سیاهی پوشیده می‌شود.',
                'en' => 'The element that stays lit; the rest is veiled at 77% black.',
            ]],
            ['name' => 'placement', 'type' => 'string', 'default' => "'bottom'", 'note' => [
                'fa' => 'جعبهٔ گفت‌وگو کنار هدف می‌نشیند: بالا، پایین، چپ، راست.',
                'en' => 'The dialog sits beside the target: top, bottom, left, right.',
            ]],
            ['name' => 'actions', 'type' => 'slot', 'default' => 'null', 'note' => [
                'fa' => 'ردیف Skip و Next؛ آخرین قدم Next می‌شود Finish.',
                'en' => 'The Skip and Next row; the last step’s Next becomes Finish.',
            ]],
            ['name' => 'pulse', 'type' => 'boolean', 'default' => 'true', 'note' => [
                'fa' => 'حلقهٔ تپنده دور هدف تا وقتی تور نرفته تمام.',
                'en' => 'A pulsing ring around the target until the tour is finished.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="atsp" x-data="{ step: 0 }">
            <button class="atsp-target" data-lit>ایجاد</button>
            <div class="atsp-dialog">
                <img src="…" alt="">
                <h4>از این‌جا مساله بسازید</h4>
                <p>دکمهٔ ایجاد، اولین خانهٔ هر کار تازه است.</p>
                <footer>
                    <button>رد کردن</button>
                    <button data-primary>بعدی</button>
                </footer>
            </div>
        </div>

        <style>
        .atsp { position: relative; overflow: clip; }
        .atsp-target[data-lit] { position: relative; z-index: 2; border-radius: 3px;
                                 box-shadow: 0 0 0 200vmax rgba(9, 30, 66, .77); }
        .atsp-dialog { position: absolute; z-index: 3; inline-size: 264px; padding: 16px;
                       border-radius: 4px; background: #FFFFFF; }
        .atsp-dialog h4 { margin: 0 0 4px; font: 600 14px Inter, system-ui; color: #172B4D; }
        .atsp-dialog footer { display: flex; justify-content: flex-end; gap: 8px; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="atsp" x-data="{ step: 0 }">
            <button class="atsp-target" data-lit>Create</button>
            <div class="atsp-dialog">
                <img src="…" alt="">
                <h4>Create your first issue here</h4>
                <p>The Create button is where every new piece of work starts.</p>
                <footer>
                    <button>Skip</button>
                    <button data-primary>Next</button>
                </footer>
            </div>
        </div>

        <style>
        .atsp { position: relative; overflow: clip; }
        .atsp-target[data-lit] { position: relative; z-index: 2; border-radius: 3px;
                                 box-shadow: 0 0 0 200vmax rgba(9, 30, 66, .77); }
        .atsp-dialog { position: absolute; z-index: 3; inline-size: 264px; padding: 16px;
                       border-radius: 4px; background: #FFFFFF; }
        .atsp-dialog h4 { margin: 0 0 4px; font: 600 14px Inter, system-ui; color: #172B4D; }
        .atsp-dialog footer { display: flex; justify-content: flex-end; gap: 8px; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Tour.jsx — Inertia page; renders the React snippet
        // below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import OnboardingTour from '@/components/OnboardingTour';

        export default function Tour() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <OnboardingTour />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // OnboardingTour.jsx — the spotlight: one lit target, everything else dimmed
        import { useLayoutEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const STEPS = [
          { title: 'Create', body: 'Every piece of work starts here — the issue form opens pre-filled.' },
          { title: 'Watch', body: 'Watching this board pages every change straight to you.' },
        ];

        export default function OnboardingTour() {
          const [step, setStep] = useState(-1); // -1 = the tour is off
          const stageRef = useRef(null);
          const targetRef = useRef(null);
          const [hole, setHole] = useState({ x: 0, y: 0, w: 0, h: 0 });

          // Measure the target so the veil's hole and the dialog track it.
          useLayoutEffect(() => {
            if (step < 0) return;
            const stage = stageRef.current?.getBoundingClientRect();
            const target = targetRef.current?.getBoundingClientRect();
            if (!stage || !target) return;
            const pad = 6;
            setHole({
              x: target.left - stage.left - pad, y: target.top - stage.top - pad,
              w: target.width + pad * 2, h: target.height + pad * 2,
            });
          }, [step]);

          const on = step >= 0;

          return (
            <>
              <style>{`
                .atsp-stage { position: relative; isolation: isolate; overflow: clip; max-inline-size: 26rem;
                              padding: .9rem; border: 1px solid #DFE1E6; border-radius: 6px; background: #F7F8F9;
                              display: grid; gap: .75rem; justify-items: start;
                              font-family: Inter, system-ui, sans-serif; }
                .atsp-create { block-size: 1.9rem; padding-inline: .8rem; border: none; border-radius: 3px;
                               cursor: pointer; background: #0C66E4; color: #fff;
                               font: 500 .78rem/1 Inter, system-ui; }
                .atsp-board { display: grid; gap: .6rem; grid-template-columns: repeat(3, 1fr); inline-size: 100%; }
                .atsp-card { border: 1px solid #DFE1E6; border-radius: 3px; padding: .45rem .5rem; background: #fff;
                             font: 400 .72rem/1.4 Inter, system-ui; color: #172B4D; }
                /* The moving hole: a transparent rect over a 200vmax scrim. */
                .atsp-veil { position: absolute; z-index: 2; border-radius: 6px; pointer-events: none;
                             box-shadow: 0 0 0 4px rgba(12, 102, 228, .4), 0 0 0 200vmax rgba(9, 30, 66, .77);
                             transition: all .35s cubic-bezier(.2, 0, 0, 1); }
                .atsp-pop { position: absolute; z-index: 3; inline-size: 15rem; padding: .9rem; border-radius: 4px;
                            background: #fff; box-shadow: 0 8px 12px rgba(9, 30, 66, .16); }
                .atsp-pop h5 { margin: 0 0 .2rem; font: 600 .86rem/1.4 Inter, system-ui; color: #172B4D; }
                .atsp-pop p { margin: 0 0 .7rem; font: 400 .76rem/1.6 Inter, system-ui; color: #44546F; }
                .atsp-foot { display: flex; align-items: center; gap: .5rem; }
                .atsp-skip { margin-inline-start: auto; border: none; background: none; cursor: pointer;
                             font: 500 .76rem Inter, system-ui; color: #626F86; }
                .atsp-next { border: none; border-radius: 3px; block-size: 1.85rem; padding-inline: .8rem;
                             cursor: pointer; background: #0C66E4; color: #fff;
                             font: 500 .76rem/1 Inter, system-ui; }
              `}</style>
              <div className="atsp-stage" ref={stageRef}>
                <button type="button" className="atsp-create" ref={targetRef}>Create</button>
                <div className="atsp-board" aria-hidden="true">
                  <div className="atsp-card">NABU-241 · Webhook retries</div>
                  <div className="atsp-card">NABU-247 · Search tokens</div>
                  <div className="atsp-card">NABU-253 · Istanbul checklist</div>
                </div>
                {on && (
                  <>
                    <div className="atsp-veil" style={{
                      insetBlockStart: hole.y, insetInlineStart: hole.x, inlineSize: hole.w, blockSize: hole.h,
                    }} />
                    <div className="atsp-pop" style={{ insetBlockStart: hole.y + hole.h + 8, insetInlineStart: hole.x }}>
                      <h5>{STEPS[step].title}</h5>
                      <p>{STEPS[step].body}</p>
                      <div className="atsp-foot">
                        <button type="button" className="atsp-skip" onClick={() => setStep(-1)}>Skip</button>
                        {/* The last step's Next becomes Finish. */}
                        <button type="button" className="atsp-next"
                          onClick={() => setStep(step < STEPS.length - 1 ? step + 1 : -1)}>
                          {step < STEPS.length - 1 ? 'Next' : 'Finish'}
                        </button>
                      </div>
                    </div>
                  </>
                )}
                {!on && (
                  <button type="button" className="atsp-create" onClick={() => setStep(0)}>Start the tour</button>
                )}
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- OnboardingTour.vue — the spotlight: one lit target, everything else dimmed -->
        <script setup lang="ts">
        import { nextTick, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const steps = [
          { title: 'Create', body: 'Every piece of work starts here — the issue form opens pre-filled.' },
          { title: 'Watch', body: 'Watching this board pages every change straight to you.' },
        ];
        const step = ref(-1); // -1 = the tour is off
        const stage = ref<HTMLElement | null>(null);
        const target = ref<HTMLElement | null>(null);
        const hole = ref({ x: 0, y: 0, w: 0, h: 0 });

        // Measure the target so the veil's hole and the dialog track it.
        async function place() {
          if (step.value < 0) return;
          await nextTick();
          const sr = stage.value?.getBoundingClientRect();
          const tr = target.value?.getBoundingClientRect();
          if (!sr || !tr) return;
          const pad = 6;
          hole.value = {
            x: tr.left - sr.left - pad, y: tr.top - sr.top - pad,
            w: tr.width + pad * 2, h: tr.height + pad * 2,
          };
        }
        function go(next: number) { step.value = next; place(); }
        </script>

        <template>
          <div ref="stage" class="atsp-stage">
            <button ref="target" type="button" class="atsp-create">Create</button>
            <div class="atsp-board" aria-hidden="true">
              <div class="atsp-card">NABU-241 · Webhook retries</div>
              <div class="atsp-card">NABU-247 · Search tokens</div>
              <div class="atsp-card">NABU-253 · Istanbul checklist</div>
            </div>
            <template v-if="step >= 0">
              <!-- The moving hole: a transparent rect over a 200vmax scrim. -->
              <div class="atsp-veil" :style="{
                insetBlockStart: hole.x ? hole.y + 'px' : '0',
                insetInlineStart: hole.x + 'px', inlineSize: hole.w + 'px', blockSize: hole.h + 'px',
              }" />
              <div class="atsp-pop" :style="{ insetBlockStart: hole.y + hole.h + 8 + 'px', insetInlineStart: hole.x + 'px' }">
                <h5>{{ steps[step].title }}</h5>
                <p>{{ steps[step].body }}</p>
                <div class="atsp-foot">
                  <button type="button" class="atsp-skip" @click="go(-1)">Skip</button>
                  <!-- The last step's Next becomes Finish. -->
                  <button type="button" class="atsp-next" @click="go(step < steps.length - 1 ? step + 1 : -1)">
                    {{ step < steps.length - 1 ? 'Next' : 'Finish' }}
                  </button>
                </div>
              </div>
            </template>
            <button v-if="step < 0" type="button" class="atsp-create" @click="go(0)">Start the tour</button>
          </div>
        </template>

        <style scoped>
        .atsp-stage { position: relative; isolation: isolate; overflow: clip; max-inline-size: 26rem;
                      padding: .9rem; border: 1px solid #DFE1E6; border-radius: 6px; background: #F7F8F9;
                      display: grid; gap: .75rem; justify-items: start;
                      font-family: Inter, system-ui, sans-serif; }
        .atsp-create { block-size: 1.9rem; padding-inline: .8rem; border: none; border-radius: 3px;
                       cursor: pointer; background: #0C66E4; color: #fff;
                       font: 500 .78rem/1 Inter, system-ui; }
        .atsp-board { display: grid; gap: .6rem; grid-template-columns: repeat(3, 1fr); inline-size: 100%; }
        .atsp-card { border: 1px solid #DFE1E6; border-radius: 3px; padding: .45rem .5rem; background: #fff;
                     font: 400 .72rem/1.4 Inter, system-ui; color: #172B4D; }
        .atsp-veil { position: absolute; inset-block-start: 0; inset-inline-start: 0; z-index: 2; border-radius: 6px;
                     pointer-events: none;
                     box-shadow: 0 0 0 4px rgba(12, 102, 228, .4), 0 0 0 200vmax rgba(9, 30, 66, .77);
                     transition: all .35s cubic-bezier(.2, 0, 0, 1); }
        .atsp-pop { position: absolute; z-index: 3; inline-size: 15rem; padding: .9rem; border-radius: 4px;
                    background: #fff; box-shadow: 0 8px 12px rgba(9, 30, 66, .16); }
        .atsp-pop h5 { margin: 0 0 .2rem; font: 600 .86rem/1.4 Inter, system-ui; color: #172B4D; }
        .atsp-pop p { margin: 0 0 .7rem; font: 400 .76rem/1.6 Inter, system-ui; color: #44546F; }
        .atsp-foot { display: flex; align-items: center; gap: .5rem; }
        .atsp-skip { margin-inline-start: auto; border: none; background: none; cursor: pointer;
                     font: 500 .76rem Inter, system-ui; color: #626F86; }
        .atsp-next { border: none; border-radius: 3px; block-size: 1.85rem; padding-inline: .8rem;
                     cursor: pointer; background: #0C66E4; color: #fff;
                     font: 500 .76rem/1 Inter, system-ui; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- OnboardingTour.svelte — the spotlight: one lit target, everything else dimmed -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          const steps = [
            { title: 'Create', body: 'Every piece of work starts here — the issue form opens pre-filled.' },
            { title: 'Watch', body: 'Watching this board pages every change straight to you.' },
          ];
          let step = $state(-1); // -1 = the tour is off
          let stage: HTMLElement;
          let target: HTMLButtonElement;
          let hole = $state({ x: 0, y: 0, w: 0, h: 0 });

          // Measure the target so the veil's hole and the dialog track it.
          function place() {
            if (step < 0) return;
            const sr = stage.getBoundingClientRect();
            const tr = target.getBoundingClientRect();
            const pad = 6;
            hole = {
              x: tr.left - sr.left - pad, y: tr.top - sr.top - pad,
              w: tr.width + pad * 2, h: tr.height + pad * 2,
            };
          }
          function go(next: number) { step = next; place(); }
        </script>

        <div bind:this={stage} class="atsp-stage">
          <button bind:this={target} type="button" class="atsp-create">Create</button>
          <div class="atsp-board" aria-hidden="true">
            <div class="atsp-card">NABU-241 · Webhook retries</div>
            <div class="atsp-card">NABU-247 · Search tokens</div>
            <div class="atsp-card">NABU-253 · Istanbul checklist</div>
          </div>
          {#if step >= 0}
            <!-- The moving hole: a transparent rect over a 200vmax scrim. -->
            <div
              class="atsp-veil"
              style:inset-block-start="{hole.y}px"
              style:inset-inline-start="{hole.x}px"
              style:inline-size="{hole.w}px"
              style:block-size="{hole.h}px"
            ></div>
            <div class="atsp-pop" style:inset-block-start="{hole.y + hole.h + 8}px" style:inset-inline-start="{hole.x}px">
              <h5>{steps[step].title}</h5>
              <p>{steps[step].body}</p>
              <div class="atsp-foot">
                <button type="button" class="atsp-skip" onclick={() => go(-1)}>Skip</button>
                <!-- The last step's Next becomes Finish. -->
                <button type="button" class="atsp-next" onclick={() => go(step < steps.length - 1 ? step + 1 : -1)}>
                  {step < steps.length - 1 ? 'Next' : 'Finish'}
                </button>
              </div>
            </div>
          {:else}
            <button type="button" class="atsp-create" onclick={() => go(0)}>Start the tour</button>
          {/if}
        </div>

        <style>
        .atsp-stage { position: relative; isolation: isolate; overflow: clip; max-inline-size: 26rem;
                      padding: .9rem; border: 1px solid #DFE1E6; border-radius: 6px; background: #F7F8F9;
                      display: grid; gap: .75rem; justify-items: start;
                      font-family: Inter, system-ui, sans-serif; }
        .atsp-create { block-size: 1.9rem; padding-inline: .8rem; border: none; border-radius: 3px;
                       cursor: pointer; background: #0C66E4; color: #fff;
                       font: 500 .78rem/1 Inter, system-ui; }
        .atsp-board { display: grid; gap: .6rem; grid-template-columns: repeat(3, 1fr); inline-size: 100%; }
        .atsp-card { border: 1px solid #DFE1E6; border-radius: 3px; padding: .45rem .5rem; background: #fff;
                     font: 400 .72rem/1.4 Inter, system-ui; color: #172B4D; }
        .atsp-veil { position: absolute; inset-block-start: 0; inset-inline-start: 0; z-index: 2; border-radius: 6px;
                     pointer-events: none;
                     box-shadow: 0 0 0 4px rgba(12, 102, 228, .4), 0 0 0 200vmax rgba(9, 30, 66, .77);
                     transition: all .35s cubic-bezier(.2, 0, 0, 1); }
        .atsp-pop { position: absolute; z-index: 3; inline-size: 15rem; padding: .9rem; border-radius: 4px;
                    background: #fff; box-shadow: 0 8px 12px rgba(9, 30, 66, .16); }
        .atsp-pop h5 { margin: 0 0 .2rem; font: 600 .86rem/1.4 Inter, system-ui; color: #172B4D; }
        .atsp-pop p { margin: 0 0 .7rem; font: 400 .76rem/1.6 Inter, system-ui; color: #44546F; }
        .atsp-foot { display: flex; align-items: center; gap: .5rem; }
        .atsp-skip { margin-inline-start: auto; border: none; background: none; cursor: pointer;
                     font: 500 .76rem Inter, system-ui; color: #626F86; }
        .atsp-next { border: none; border-radius: 3px; block-size: 1.85rem; padding-inline: .8rem;
                     cursor: pointer; background: #0C66E4; color: #fff;
                     font: 500 .76rem/1 Inter, system-ui; }
        </style>
        SVELTE,
        ],
    ],

    'side-navigation' => [
        'title' => ['fa' => 'ناوبری کناری', 'en' => 'Side Navigation'],
        'icon' => 'menu',
        'oneLiner' => [
            'fa' => 'ستون کناری از آیتم‌های آیکن‌مربعی، بخش‌های جمع‌شونده و حالت انتخاب آبی — اسکلت هویتی همهٔ اپ‌های ابری اطلسیان.',
            'en' => 'A left column of square-icon items, collapsible sections and blue selected state — the identity skeleton of every Atlassian cloud app.',
        ],
        'js' => true,
        'docs' => 'https://atlassian.design/components/side-navigation/usage',
        'props' => [
            ['name' => 'items', 'type' => 'tree', 'default' => '[]', 'note' => [
                'fa' => 'درخت آیتم‌ها: سرگروه، لینک، بخش و آیتم تودرتو.',
                'en' => 'The item tree: heading, link, section and nested items.',
            ]],
            ['name' => 'isSelected', 'type' => 'boolean', 'default' => 'false', 'note' => [
                'fa' => 'آیتم انتخاب‌شده سطح آبی روشن و متن آبی می‌گیرد.',
                'en' => 'The selected item gains the blue-tinted surface and blue text.',
            ]],
            ['name' => 'badge', 'type' => 'slot', 'default' => 'null', 'note' => [
                'fa' => 'شمارندهٔ انتهای آیتم؛ مثل «۴ کار باز».',
                'en' => 'The counter at the item’s end; like «4 open tasks».',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <nav class="atsn">
            <p class="atsn-heading">کارها</p>
            <button class="atsn-item" data-selected>
                <i aria-hidden="true">✦</i> پروژهٔ نابو <b>۴</b>
            </button>
            <button class="atsn-item" data-section aria-expanded="true">
                <i aria-hidden="true">▸</i> فیلترها
            </button>
            <button class="atsn-item" data-nested>مخصوص من</button>
        </nav>

        <style>
        .atsn { display: grid; gap: 2px; inline-size: 264px; padding: 8px;
                background: #FFFFFF; }
        .atsn-heading { margin: 8px 8px 4px; font: 700 11px/1.4 Inter, system-ui;
                        letter-spacing: .4px; text-transform: uppercase; color: #626F86; }
        .atsn-item { display: flex; align-items: center; gap: 8px; block-size: 36px;
                     padding-inline: 8px; border: none; border-radius: 4px;
                     background: none; font: 400 14px Inter, system-ui; color: #44546F; }
        .atsn-item i { display: grid; place-items: center; inline-size: 20px; aspect-ratio: 1;
                       border-radius: 4px; background: #E9F2FF; color: #0C66E4;
                       font-style: normal; font-size: 12px; }
        .atsn-item[data-selected] { background: #E9F2FF; color: #0C66E4; font-weight: 600; }
        .atsn-item b { margin-inline-start: auto; font-size: 11px; color: #626F86; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <nav class="atsn">
            <p class="atsn-heading">Work items</p>
            <button class="atsn-item" data-selected>
                <i aria-hidden="true">✦</i> Nabu platform <b>4</b>
            </button>
            <button class="atsn-item" data-section aria-expanded="true">
                <i aria-hidden="true">▸</i> Filters
            </button>
            <button class="atsn-item" data-nested>Only mine</button>
        </nav>

        <style>
        .atsn { display: grid; gap: 2px; inline-size: 264px; padding: 8px;
                background: #FFFFFF; }
        .atsn-heading { margin: 8px 8px 4px; font: 700 11px/1.4 Inter, system-ui;
                        letter-spacing: .4px; text-transform: uppercase; color: #626F86; }
        .atsn-item { display: flex; align-items: center; gap: 8px; block-size: 36px;
                     padding-inline: 8px; border: none; border-radius: 4px;
                     background: none; font: 400 14px Inter, system-ui; color: #44546F; }
        .atsn-item i { display: grid; place-items: center; inline-size: 20px; aspect-ratio: 1;
                       border-radius: 4px; background: #E9F2FF; color: #0C66E4;
                       font-style: normal; font-size: 12px; }
        .atsn-item[data-selected] { background: #E9F2FF; color: #0C66E4; font-weight: 600; }
        .atsn-item b { margin-inline-start: auto; font-size: 11px; color: #626F86; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/AppShell.jsx — Inertia page; renders the React snippet
        // below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import CloudNav from '@/components/CloudNav';

        export default function AppShell() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <CloudNav />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // CloudNav.jsx — the side-navigation skeleton of every Atlassian cloud app
        import { useState } from 'react';
        import '@nabuxai/ui-core/css';

        const YOUR_WORK = [
          { id: 'assigned', label: 'Assigned to me', count: 4 },
          { id: 'starred', label: 'Starred', count: 2 },
          { id: 'recent', label: 'Recently viewed' },
        ];
        const PROJECTS = [
          { id: 'platform', label: 'Nabu platform' },
          { id: 'mobile', label: 'Nabu mobile' },
          { id: 'web', label: 'Nabu web' },
        ];

        export default function CloudNav() {
          const [selected, setSelected] = useState('assigned');
          const [open, setOpen] = useState(true);

          const item = (p) => (
            <button type="button" className="atsn-item" key={p.id}
              data-selected={selected === p.id || undefined} onClick={() => setSelected(p.id)}>
              <i aria-hidden="true">✦</i> {p.label}
              {p.count ? <b>{p.count}</b> : null}
            </button>
          );

          return (
            <>
              <style>{`
                .atsn { display: grid; gap: 2px; inline-size: 16rem; padding: 8px; border: 1px solid #DFE1E6;
                        border-radius: 6px; background: #fff; font-family: Inter, system-ui, sans-serif; }
                .atsn-heading { margin: 8px 8px 4px; font: 700 11px/1.4 Inter, system-ui; letter-spacing: .5px;
                                text-transform: uppercase; color: #626F86; }
                .atsn-item { display: flex; align-items: center; gap: 8px; block-size: 36px; padding-inline: 8px;
                             border: none; border-radius: 4px; background: none; cursor: pointer; text-align: start;
                             font: 500 14px Inter, system-ui; color: #44546F; }
                .atsn-item:hover { background: #F1F2F4; }
                .atsn-item[data-selected] { background: #E9F2FF; color: #0C66E4; font-weight: 600; }
                .atsn-item i { display: grid; place-items: center; inline-size: 20px; aspect-ratio: 1; border-radius: 4px;
                               background: #E9F2FF; color: #0C66E4; font-style: normal; font-size: 12px; }
                .atsn-item[data-selected] i { background: #fff; }
                .atsn-item b { margin-inline-start: auto; font-size: 11px; color: #626F86; }
                .atsn-item svg { margin-inline-start: auto; transition: rotate .2s ease; }
                .atsn-item[aria-expanded='true'] svg { rotate: 90deg; }
              `}</style>
              <nav className="atsn" aria-label="Main navigation">
                <p className="atsn-heading">Your work</p>
                {YOUR_WORK.map(item)}
                {/* Collapsible section: the chevron rotates with aria-expanded. */}
                <button type="button" className="atsn-item" aria-expanded={open} onClick={() => setOpen(!open)}>
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    strokeWidth="2" aria-hidden="true"><path d="M9 6l6 6-6 6" /></svg>
                  Projects
                </button>
                {open && PROJECTS.map(item)}
              </nav>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- CloudNav.vue — the side-navigation skeleton of every Atlassian cloud app -->
        <script setup lang="ts">
        import { ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const yourWork = [
          { id: 'assigned', label: 'Assigned to me', count: 4 },
          { id: 'starred', label: 'Starred', count: 2 },
          { id: 'recent', label: 'Recently viewed' },
        ];
        const projects = [
          { id: 'platform', label: 'Nabu platform' },
          { id: 'mobile', label: 'Nabu mobile' },
          { id: 'web', label: 'Nabu web' },
        ];
        const selected = ref('assigned');
        const open = ref(true);
        </script>

        <template>
          <nav class="atsn" aria-label="Main navigation">
            <p class="atsn-heading">Your work</p>
            <!-- Square icon tiles, counters and the blue selected state. -->
            <button v-for="p in yourWork" :key="p.id" type="button" class="atsn-item"
              :data-selected="selected === p.id || undefined" @click="selected = p.id">
              <i aria-hidden="true">✦</i> {{ p.label }}
              <b v-if="p.count">{{ p.count }}</b>
            </button>
            <!-- Collapsible section: the chevron rotates with aria-expanded. -->
            <button type="button" class="atsn-item" :aria-expanded="open" @click="open = !open">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" aria-hidden="true"><path d="M9 6l6 6-6 6" /></svg>
              Projects
            </button>
            <template v-if="open">
              <button v-for="p in projects" :key="p.id" type="button" class="atsn-item"
                :data-selected="selected === p.id || undefined" @click="selected = p.id">
                <i aria-hidden="true">✦</i> {{ p.label }}
              </button>
            </template>
          </nav>
        </template>

        <style scoped>
        .atsn { display: grid; gap: 2px; inline-size: 16rem; padding: 8px; border: 1px solid #DFE1E6;
                border-radius: 6px; background: #fff; font-family: Inter, system-ui, sans-serif; }
        .atsn-heading { margin: 8px 8px 4px; font: 700 11px/1.4 Inter, system-ui; letter-spacing: .5px;
                        text-transform: uppercase; color: #626F86; }
        .atsn-item { display: flex; align-items: center; gap: 8px; block-size: 36px; padding-inline: 8px;
                     border: none; border-radius: 4px; background: none; cursor: pointer; text-align: start;
                     font: 500 14px Inter, system-ui; color: #44546F; }
        .atsn-item:hover { background: #F1F2F4; }
        .atsn-item[data-selected] { background: #E9F2FF; color: #0C66E4; font-weight: 600; }
        .atsn-item i { display: grid; place-items: center; inline-size: 20px; aspect-ratio: 1; border-radius: 4px;
                       background: #E9F2FF; color: #0C66E4; font-style: normal; font-size: 12px; }
        .atsn-item[data-selected] i { background: #fff; }
        .atsn-item b { margin-inline-start: auto; font-size: 11px; color: #626F86; }
        .atsn-item svg { margin-inline-start: auto; transition: rotate .2s ease; }
        .atsn-item[aria-expanded='true'] svg { rotate: 90deg; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- CloudNav.svelte — the side-navigation skeleton of every Atlassian cloud app -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const yourWork = [
            { id: 'assigned', label: 'Assigned to me', count: 4 },
            { id: 'starred', label: 'Starred', count: 2 },
            { id: 'recent', label: 'Recently viewed' },
          ];
          const projects = [
            { id: 'platform', label: 'Nabu platform' },
            { id: 'mobile', label: 'Nabu mobile' },
            { id: 'web', label: 'Nabu web' },
          ];
          let selected = $state('assigned');
          let open = $state(true);
        </script>

        <nav class="atsn" aria-label="Main navigation">
          <p class="atsn-heading">Your work</p>
          <!-- Square icon tiles, counters and the blue selected state. -->
          {#each yourWork as p (p.id)}
            <button type="button" class="atsn-item" data-selected={selected === p.id || undefined}
              onclick={() => (selected = p.id)}>
              <i aria-hidden="true">✦</i> {p.label}
              {#if p.count}<b>{p.count}</b>{/if}
            </button>
          {/each}
          <!-- Collapsible section: the chevron rotates with aria-expanded. -->
          <button type="button" class="atsn-item" aria-expanded={open} onclick={() => (open = !open)}>
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
              stroke-width="2" aria-hidden="true"><path d="M9 6l6 6-6 6" /></svg>
            Projects
          </button>
          {#if open}
            {#each projects as p (p.id)}
              <button type="button" class="atsn-item" data-selected={selected === p.id || undefined}
                onclick={() => (selected = p.id)}>
                <i aria-hidden="true">✦</i> {p.label}
              </button>
            {/each}
          {/if}
        </nav>

        <style>
        .atsn { display: grid; gap: 2px; inline-size: 16rem; padding: 8px; border: 1px solid #DFE1E6;
                border-radius: 6px; background: #fff; font-family: Inter, system-ui, sans-serif; }
        .atsn-heading { margin: 8px 8px 4px; font: 700 11px/1.4 Inter, system-ui; letter-spacing: .5px;
                        text-transform: uppercase; color: #626F86; }
        .atsn-item { display: flex; align-items: center; gap: 8px; block-size: 36px; padding-inline: 8px;
                     border: none; border-radius: 4px; background: none; cursor: pointer; text-align: start;
                     font: 500 14px Inter, system-ui; color: #44546F; }
        .atsn-item:hover { background: #F1F2F4; }
        .atsn-item[data-selected] { background: #E9F2FF; color: #0C66E4; font-weight: 600; }
        .atsn-item i { display: grid; place-items: center; inline-size: 20px; aspect-ratio: 1; border-radius: 4px;
                       background: #E9F2FF; color: #0C66E4; font-style: normal; font-size: 12px; }
        .atsn-item[data-selected] i { background: #fff; }
        .atsn-item b { margin-inline-start: auto; font-size: 11px; color: #626F86; }
        .atsn-item svg { margin-inline-start: auto; transition: rotate .2s ease; }
        .atsn-item[aria-expanded='true'] svg { rotate: 90deg; }
        </style>
        SVELTE,
        ],
    ],

    'empty-state' => [
        'title' => ['fa' => 'حالت خالی مصور', 'en' => 'Empty State'],
        'icon' => 'folder',
        'oneLiner' => [
            'fa' => 'تصویر خطی دوست‌داشتنی، تیتر، توضیح و فقط یک CTA — صفحهٔ خالی جیرا را به دعوت‌نامه‌ای برای شروع تبدیل می‌کند.',
            'en' => 'Friendly line illustration, heading, description and a single CTA — turning an empty Jira page into an invitation to start.',
        ],
        'js' => true,
        'docs' => 'https://atlassian.design/components/empty-state/usage',
        'props' => [
            ['name' => 'header', 'type' => 'string', 'default' => "''", 'note' => [
                'fa' => 'تیتر Charlie با وزن ۵۰۰ و اندازهٔ ۲۴ پیکسل.',
                'en' => 'The Charlie heading at weight 500 and 24px.',
            ]],
            ['name' => 'description', 'type' => 'string', 'default' => "''", 'note' => [
                'fa' => 'یک جمله؛ بیش از دو خط نشود.',
                'en' => 'One sentence; never more than two lines.',
            ]],
            ['name' => 'primaryAction', 'type' => 'slot', 'default' => 'null', 'note' => [
                'fa' => 'فقط یک CTA آبی؛ اکشن دوم را به لینک متنی بسپارید.',
                'en' => 'A single blue CTA; relegate any second action to a text link.',
            ]],
            ['name' => 'imageUrl', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'تصویر خطی با خطوط ۱٫۵ پیکسلی و گوشه‌های نرم.',
                'en' => 'The line illustration with 1.5px strokes and soft corners.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <section class="ates">
            <svg viewBox="0 0 160 120" aria-hidden="true">
                <rect x="28" y="18" width="104" height="84" rx="8" fill="#FFFFFF" stroke="#8590A2" stroke-width="1.5"/>
                <path d="M48 44h64M48 60h44" stroke="#DFE1E6" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
            <h3>اینجا هنوز چیزی نیست</h3>
            <p>اولین مسالهٔ پروژه را بسازید تا تخته زنده شود.</p>
            <button data-primary>ایجاد مساله</button>
        </section>

        <style>
        .ates { display: grid; justify-items: center; gap: 8px; padding: 40px 24px;
                text-align: center; }
        .ates svg { inline-size: 160px; }
        .ates h3 { margin: 0; font: 500 20px/1.3 "Charlie Display", Inter, system-ui; color: #172B4D; }
        .ates p { margin: 0; max-inline-size: 42ch; font: 400 14px/1.6 Inter, system-ui; color: #626F86; }
        .ates [data-primary] { margin-block-start: 8px; block-size: 32px; padding-inline: 12px;
                               border: none; border-radius: 3px; background: #0C66E4; color: #fff; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <section class="ates">
            <svg viewBox="0 0 160 120" aria-hidden="true">
                <rect x="28" y="18" width="104" height="84" rx="8" fill="#FFFFFF" stroke="#8590A2" stroke-width="1.5"/>
                <path d="M48 44h64M48 60h44" stroke="#DFE1E6" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
            <h3>Nothing lives here yet</h3>
            <p>Create the project's first issue and the board comes alive.</p>
            <button data-primary>Create issue</button>
        </section>

        <style>
        .ates { display: grid; justify-items: center; gap: 8px; padding: 40px 24px;
                text-align: center; }
        .ates svg { inline-size: 160px; }
        .ates h3 { margin: 0; font: 500 20px/1.3 "Charlie Display", Inter, system-ui; color: #172B4D; }
        .ates p { margin: 0; max-inline-size: 42ch; font: 400 14px/1.6 Inter, system-ui; color: #626F86; }
        .ates [data-primary] { margin-block-start: 8px; block-size: 32px; padding-inline: 12px;
                               border: none; border-radius: 3px; background: #0C66E4; color: #fff; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Files.jsx — Inertia page; renders the React snippet
        // below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import FilesEmptyState from '@/components/FilesEmptyState';

        export default function Files() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <FilesEmptyState />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // FilesEmptyState.jsx — the illustrated invitation that seeds the file list
        import { useState } from 'react';
        import '@nabuxai/ui-core/css';

        const FILES = [
          { name: 'user-behaviour-analysis.pdf', meta: '2.4 MB · yesterday', tile: '#B40000', tag: 'PDF' },
          { name: 'istanbul-palette.fig', meta: '812 KB · yesterday', tile: '#6E5DC6', tag: 'FIG' },
          { name: 'sprint-24-metrics.csv', meta: '44 KB · 2 days ago', tile: '#1F845A', tag: 'CSV' },
        ];

        export default function FilesEmptyState() {
          const [filled, setFilled] = useState(false);

          return (
            <>
              <style>{`
                .ates-stage { max-inline-size: 24rem; border: 1px solid #DFE1E6; border-radius: 6px; background: #fff;
                              padding: 1rem; font-family: Inter, system-ui, sans-serif; }
                .ates { display: grid; justify-items: center; gap: .55rem; padding: 1.5rem 1.25rem 2rem; text-align: center; }
                .ates svg.art { inline-size: 9.5rem; }
                .ates h4 { margin: .35rem 0 0; font: 500 1.25rem/1.3 'Charlie Display', Inter, system-ui; color: #172B4D; }
                .ates p { margin: 0; max-inline-size: 40ch; font: 400 .84rem/1.6 Inter, system-ui; color: #626F86; }
                .ates-cta { margin-block-start: .75rem; block-size: 2rem; padding-inline: .9rem; border: none;
                            border-radius: 3px; cursor: pointer; background: #0C66E4; color: #fff;
                            font: 500 .8rem/1 Inter, system-ui; }
                .ates-quiet { margin-block-start: .5rem; border: none; background: none; cursor: pointer;
                              font: 500 .78rem Inter, system-ui; color: #0C66E4; }
                .ates-file { display: flex; align-items: center; gap: .65rem; padding: .55rem .5rem; border-radius: 4px; }
                .ates-file:hover { background: #F1F2F4; }
                .ates-file i { flex: none; display: grid; place-items: center; inline-size: 1.9rem; aspect-ratio: 1;
                               border-radius: 4px; color: #fff; font: 600 .68rem/1 Inter, system-ui; font-style: normal; }
                .ates-file b { display: block; font: 500 .8rem/1.4 Inter, system-ui; color: #172B4D; }
                .ates-file small { font: 400 .7rem/1.4 Inter, system-ui; color: #626F86; }
              `}</style>
              <div className="ates-stage">
                {!filled ? (
                  <section className="ates">
                    {/* Friendly 1.5px line illustration — the Jira signature. */}
                    <svg className="art" viewBox="0 0 160 120" fill="none" aria-hidden="true">
                      <rect x="34" y="16" width="92" height="76" rx="8" stroke="#8590A2" strokeWidth="1.5" fill="#fff" />
                      <path d="M46 34h30" stroke="#8590A2" strokeWidth="1.5" strokeLinecap="round" />
                      <circle cx="118" cy="34" r="10" stroke="#388BFF" strokeWidth="1.5" />
                      <path d="M113.5 34l3 3 5.5-6" stroke="#388BFF" strokeWidth="1.5"
                        strokeLinecap="round" strokeLinejoin="round" />
                      <rect x="46" y="50" width="68" height="9" rx="4.5" fill="#DFE1E6" />
                      <rect x="46" y="66" width="50" height="9" rx="4.5" fill="#DFE1E6" />
                    </svg>
                    <h4>Nothing lives here yet</h4>
                    <p>Files your team uploads to this project land here — the first one starts the pile.</p>
                    {/* A single blue CTA; the second action stays a text link. */}
                    <button type="button" className="ates-cta" onClick={() => setFilled(true)}>Upload files</button>
                  </section>
                ) : (
                  <div>
                    {FILES.map((f) => (
                      <div className="ates-file" key={f.name}>
                        <i style={{ background: f.tile }} aria-hidden="true">{f.tag}</i>
                        <span><b>{f.name}</b><small>{f.meta}</small></span>
                      </div>
                    ))}
                    <button type="button" className="ates-quiet" onClick={() => setFilled(false)}>Reset the list</button>
                  </div>
                )}
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- FilesEmptyState.vue — the illustrated invitation that seeds the file list -->
        <script setup lang="ts">
        import { ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const files = [
          { name: 'user-behaviour-analysis.pdf', meta: '2.4 MB · yesterday', tile: '#B40000', tag: 'PDF' },
          { name: 'istanbul-palette.fig', meta: '812 KB · yesterday', tile: '#6E5DC6', tag: 'FIG' },
          { name: 'sprint-24-metrics.csv', meta: '44 KB · 2 days ago', tile: '#1F845A', tag: 'CSV' },
        ];
        const filled = ref(false);
        </script>

        <template>
          <div class="ates-stage">
            <section v-if="!filled" class="ates">
              <!-- Friendly 1.5px line illustration — the Jira signature. -->
              <svg class="art" viewBox="0 0 160 120" fill="none" aria-hidden="true">
                <rect x="34" y="16" width="92" height="76" rx="8" stroke="#8590A2" stroke-width="1.5" fill="#fff" />
                <path d="M46 34h30" stroke="#8590A2" stroke-width="1.5" stroke-linecap="round" />
                <circle cx="118" cy="34" r="10" stroke="#388BFF" stroke-width="1.5" />
                <path d="M113.5 34l3 3 5.5-6" stroke="#388BFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <rect x="46" y="50" width="68" height="9" rx="4.5" fill="#DFE1E6" />
                <rect x="46" y="66" width="50" height="9" rx="4.5" fill="#DFE1E6" />
              </svg>
              <h4>Nothing lives here yet</h4>
              <p>Files your team uploads to this project land here — the first one starts the pile.</p>
              <!-- A single blue CTA; the second action stays a text link. -->
              <button type="button" class="ates-cta" @click="filled = true">Upload files</button>
            </section>
            <div v-else>
              <div v-for="f in files" :key="f.name" class="ates-file">
                <i :style="{ background: f.tile }" aria-hidden="true">{{ f.tag }}</i>
                <span><b>{{ f.name }}</b><small>{{ f.meta }}</small></span>
              </div>
              <button type="button" class="ates-quiet" @click="filled = false">Reset the list</button>
            </div>
          </div>
        </template>

        <style scoped>
        .ates-stage { max-inline-size: 24rem; border: 1px solid #DFE1E6; border-radius: 6px; background: #fff;
                      padding: 1rem; font-family: Inter, system-ui, sans-serif; }
        .ates { display: grid; justify-items: center; gap: .55rem; padding: 1.5rem 1.25rem 2rem; text-align: center; }
        .ates svg.art { inline-size: 9.5rem; }
        .ates h4 { margin: .35rem 0 0; font: 500 1.25rem/1.3 'Charlie Display', Inter, system-ui; color: #172B4D; }
        .ates p { margin: 0; max-inline-size: 40ch; font: 400 .84rem/1.6 Inter, system-ui; color: #626F86; }
        .ates-cta { margin-block-start: .75rem; block-size: 2rem; padding-inline: .9rem; border: none;
                    border-radius: 3px; cursor: pointer; background: #0C66E4; color: #fff;
                    font: 500 .8rem/1 Inter, system-ui; }
        .ates-quiet { margin-block-start: .5rem; border: none; background: none; cursor: pointer;
                      font: 500 .78rem Inter, system-ui; color: #0C66E4; }
        .ates-file { display: flex; align-items: center; gap: .65rem; padding: .55rem .5rem; border-radius: 4px; }
        .ates-file:hover { background: #F1F2F4; }
        .ates-file i { flex: none; display: grid; place-items: center; inline-size: 1.9rem; aspect-ratio: 1;
                       border-radius: 4px; color: #fff; font: 600 .68rem/1 Inter, system-ui; font-style: normal; }
        .ates-file b { display: block; font: 500 .8rem/1.4 Inter, system-ui; color: #172B4D; }
        .ates-file small { font: 400 .7rem/1.4 Inter, system-ui; color: #626F86; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- FilesEmptyState.svelte — the illustrated invitation that seeds the file list -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const files = [
            { name: 'user-behaviour-analysis.pdf', meta: '2.4 MB · yesterday', tile: '#B40000', tag: 'PDF' },
            { name: 'istanbul-palette.fig', meta: '812 KB · yesterday', tile: '#6E5DC6', tag: 'FIG' },
            { name: 'sprint-24-metrics.csv', meta: '44 KB · 2 days ago', tile: '#1F845A', tag: 'CSV' },
          ];
          let filled = $state(false);
        </script>

        <div class="ates-stage">
          {#if !filled}
            <section class="ates">
              <!-- Friendly 1.5px line illustration — the Jira signature. -->
              <svg class="art" viewBox="0 0 160 120" fill="none" aria-hidden="true">
                <rect x="34" y="16" width="92" height="76" rx="8" stroke="#8590A2" stroke-width="1.5" fill="#fff" />
                <path d="M46 34h30" stroke="#8590A2" stroke-width="1.5" stroke-linecap="round" />
                <circle cx="118" cy="34" r="10" stroke="#388BFF" stroke-width="1.5" />
                <path d="M113.5 34l3 3 5.5-6" stroke="#388BFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <rect x="46" y="50" width="68" height="9" rx="4.5" fill="#DFE1E6" />
                <rect x="46" y="66" width="50" height="9" rx="4.5" fill="#DFE1E6" />
              </svg>
              <h4>Nothing lives here yet</h4>
              <p>Files your team uploads to this project land here — the first one starts the pile.</p>
              <!-- A single blue CTA; the second action stays a text link. -->
              <button type="button" class="ates-cta" onclick={() => (filled = true)}>Upload files</button>
            </section>
          {:else}
            <div>
              {#each files as f (f.name)}
                <div class="ates-file">
                  <i style:background={f.tile} aria-hidden="true">{f.tag}</i>
                  <span><b>{f.name}</b><small>{f.meta}</small></span>
                </div>
              {/each}
              <button type="button" class="ates-quiet" onclick={() => (filled = false)}>Reset the list</button>
            </div>
          {/if}
        </div>

        <style>
        .ates-stage { max-inline-size: 24rem; border: 1px solid #DFE1E6; border-radius: 6px; background: #fff;
                      padding: 1rem; font-family: Inter, system-ui, sans-serif; }
        .ates { display: grid; justify-items: center; gap: .55rem; padding: 1.5rem 1.25rem 2rem; text-align: center; }
        .ates svg.art { inline-size: 9.5rem; }
        .ates h4 { margin: .35rem 0 0; font: 500 1.25rem/1.3 'Charlie Display', Inter, system-ui; color: #172B4D; }
        .ates p { margin: 0; max-inline-size: 40ch; font: 400 .84rem/1.6 Inter, system-ui; color: #626F86; }
        .ates-cta { margin-block-start: .75rem; block-size: 2rem; padding-inline: .9rem; border: none;
                    border-radius: 3px; cursor: pointer; background: #0C66E4; color: #fff;
                    font: 500 .8rem/1 Inter, system-ui; }
        .ates-quiet { margin-block-start: .5rem; border: none; background: none; cursor: pointer;
                      font: 500 .78rem Inter, system-ui; color: #0C66E4; }
        .ates-file { display: flex; align-items: center; gap: .65rem; padding: .55rem .5rem; border-radius: 4px; }
        .ates-file:hover { background: #F1F2F4; }
        .ates-file i { flex: none; display: grid; place-items: center; inline-size: 1.9rem; aspect-ratio: 1;
                       border-radius: 4px; color: #fff; font: 600 .68rem/1 Inter, system-ui; font-style: normal; }
        .ates-file b { display: block; font: 500 .8rem/1.4 Inter, system-ui; color: #172B4D; }
        .ates-file small { font: 400 .7rem/1.4 Inter, system-ui; color: #626F86; }
        </style>
        SVELTE,
        ],
    ],

    'comment' => [
        'title' => ['fa' => 'رشتهٔ دیدگاه', 'en' => 'Comment Thread'],
        'icon' => 'message',
        'oneLiner' => [
            'fa' => 'آواتار گرد، نویسنده و زمان، بدنهٔ قالب‌دار و ردیف اکشن‌های کم‌سروصدا — قلب گفت‌وگوی هر مسالهٔ جیرا و صفحهٔ کانفلوئنس.',
            'en' => 'Round avatar, author and timestamp, rich body and a quiet action row — the heartbeat of every Jira issue and Confluence page conversation.',
        ],
        'js' => true,
        'docs' => 'https://atlassian.design/components/comment/usage',
        'props' => [
            ['name' => 'avatar', 'type' => 'element', 'default' => 'null', 'note' => [
                'fa' => 'آواتار ۲۴ تا ۳۲ پیکسلی؛ حروف نخستین نام هم کافی است.',
                'en' => 'A 24–32px avatar; name initials work just as well.',
            ]],
            ['name' => 'author · time', 'type' => 'slots', 'default' => 'null', 'note' => [
                'fa' => 'نام پررنگ با لینک آبی و زمان کم‌رنگ کنارش.',
                'en' => 'The bold linked name in blue with the muted time beside it.',
            ]],
            ['name' => 'actions', 'type' => 'slot', 'default' => 'null', 'note' => [
                'fa' => 'اکشن‌های متنی کم‌رنگ زیر بدنه؛ مثل واکنش و پاسخ.',
                'en' => 'The quiet text actions under the body; like react and reply.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <article class="atcm">
            <span class="atcm-avatar" aria-hidden="true">م‌ر</span>
            <div>
                <header><a href="#">مریم رضایی</a><time>۲ ساعت پیش</time></header>
                <div class="atcm-body">
                    <p>برچسب «در جریان» را گذاشتم؛ <a href="#">@علی</a> بعد از بازبینی جابه‌جایش کن.</p>
                </div>
                <footer>
                    <button>پاسخ</button><button>واکنش</button>
                </footer>
            </div>
        </article>

        <style>
        .atcm { display: flex; gap: 12px; padding: 12px 0; }
        .atcm-avatar { flex: none; display: grid; place-items: center; inline-size: 32px;
                       aspect-ratio: 1; border-radius: 50%; background: #F1F2F4;
                       font: 600 12px Inter, system-ui; color: #44546F; }
        .atcm header { display: flex; align-items: baseline; gap: 8px; }
        .atcm header a { font: 600 14px Inter, system-ui; color: #0C66E4; }
        .atcm time { font: 400 12px Inter, system-ui; color: #626F86; }
        .atcm-body p { margin: 4px 0; font: 400 14px/1.6 Inter, system-ui; color: #172B4D; }
        .atcm footer { display: flex; gap: 4px; }
        .atcm footer button { border: none; background: none; padding: 4px 8px;
                              border-radius: 3px; font: 600 12px Inter, system-ui; color: #626F86; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <article class="atcm">
            <span class="atcm-avatar" aria-hidden="true">SA</span>
            <div>
                <header><a href="#">Selin Aydin</a><time>2 hours ago</time></header>
                <div class="atcm-body">
                    <p>I set the label to “In progress”; <a href="#">@Milan</a> move it to review once the audit passes.</p>
                </div>
                <footer>
                    <button>Reply</button><button>React</button>
                </footer>
            </div>
        </article>

        <style>
        .atcm { display: flex; gap: 12px; padding: 12px 0; }
        .atcm-avatar { flex: none; display: grid; place-items: center; inline-size: 32px;
                       aspect-ratio: 1; border-radius: 50%; background: #F1F2F4;
                       font: 600 12px Inter, system-ui; color: #44546F; }
        .atcm header { display: flex; align-items: baseline; gap: 8px; }
        .atcm header a { font: 600 14px Inter, system-ui; color: #0C66E4; }
        .atcm time { font: 400 12px Inter, system-ui; color: #626F86; }
        .atcm-body p { margin: 4px 0; font: 400 14px/1.6 Inter, system-ui; color: #172B4D; }
        .atcm footer { display: flex; gap: 4px; }
        .atcm footer button { border: none; background: none; padding: 4px 8px;
                              border-radius: 3px; font: 600 12px Inter, system-ui; color: #626F86; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Issue.jsx — Inertia page; renders the React snippet
        // below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import IssueComments from '@/components/IssueComments';

        export default function Issue() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <IssueComments />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // IssueComments.jsx — the conversation heartbeat of every Jira issue
        import { useState } from 'react';
        import '@nabuxai/ui-core/css';

        export default function IssueComments() {
          const [likes, setLikes] = useState(3);
          const [liked, setLiked] = useState(false);
          const [replying, setReplying] = useState(false);
          const [draft, setDraft] = useState('');
          const [replies, setReplies] = useState([]);

          const send = () => {
            const body = draft.trim();
            if (!body) return;
            setReplies((r) => [...r, { author: 'Milan Novak', body }]);
            setDraft('');
            setReplying(false);
          };

          return (
            <>
              <style>{`
                .atcm-thread { max-inline-size: 26rem; border: 1px solid #DFE1E6; border-radius: 6px; background: #fff;
                               padding: 1rem 1.1rem; font-family: Inter, system-ui, sans-serif; }
                .atcm { display: flex; gap: .65rem; padding-block: .45rem; }
                .atcm-av { flex: none; display: grid; place-items: center; inline-size: 2rem; aspect-ratio: 1;
                           border-radius: 50%; background: #F1F2F4; font: 600 .72rem Inter, system-ui; color: #44546F; }
                .atcm-main { flex: 1; min-inline-size: 0; }
                .atcm-head { display: flex; align-items: baseline; gap: .5rem; }
                .atcm-head a { font: 600 .82rem Inter, system-ui; color: #0C66E4; text-decoration: none; }
                .atcm-head time { font: 400 .7rem Inter, system-ui; color: #626F86; }
                .atcm-body { margin: .2rem 0 .1rem; font: 400 .82rem/1.65 Inter, system-ui; color: #172B4D; }
                .atcm-body a { color: #0C66E4; font-weight: 500; text-decoration: none; }
                .atcm-actions { display: flex; gap: .25rem; }
                .atcm-actions button { display: inline-flex; gap: .3rem; border: none; background: none;
                                       padding: .25rem .45rem; border-radius: 3px; cursor: pointer;
                                       font: 600 .72rem Inter, system-ui; color: #626F86; }
                .atcm-actions button:hover { background: #F1F2F4; color: #0C66E4; }
                .atcm-actions button[aria-pressed='true'] { color: #0C66E4; }
                .atcm-replies { margin-inline-start: 1rem; padding-inline-start: .85rem;
                                border-inline-start: 2px solid #DFE1E6; }
                .atcm-form { display: flex; gap: .65rem; padding-block: .55rem; }
                .atcm-form textarea { flex: 1; min-block-size: 2.5rem; padding: .5rem .6rem; border: 1px solid #DFE1E6;
                                      border-radius: 4px; font: 400 .8rem/1.55 Inter, system-ui; }
                .atcm-send { align-self: flex-start; block-size: 1.85rem; padding-inline: .75rem; border: none;
                             border-radius: 3px; cursor: pointer; background: #0C66E4; color: #fff;
                             font: 500 .76rem/1 Inter, system-ui; }
              `}</style>
              <div className="atcm-thread">
                <article className="atcm">
                  <span className="atcm-av" aria-hidden="true">SA</span>
                  <div className="atcm-main">
                    <header className="atcm-head"><a href="#">Selin Aydin</a><time>2 hours ago</time></header>
                    <div className="atcm-body">
                      I set the label to “In progress”; <a href="#">@Milan</a> move it to review once the audit passes.
                    </div>
                    <div className="atcm-actions">
                      {/* The heart really counts. */}
                      <button type="button" aria-pressed={liked}
                        onClick={() => { setLiked(!liked); setLikes((n) => n + (liked ? -1 : 1)); }}>
                        ♥ {likes}
                      </button>
                      <button type="button" onClick={() => setReplying(!replying)}>Reply</button>
                    </div>
                    {replies.length > 0 && (
                      <div className="atcm-replies">
                        {replies.map((r, i) => (
                          <article className="atcm" key={i}>
                            <span className="atcm-av" aria-hidden="true">MN</span>
                            <div className="atcm-main">
                              <header className="atcm-head"><a href="#">{r.author}</a><time>just now</time></header>
                              <div className="atcm-body">{r.body}</div>
                            </div>
                          </article>
                        ))}
                      </div>
                    )}
                    {replying && (
                      <div className="atcm-form">
                        <textarea value={draft} placeholder="Add a reply…" autoFocus
                          onChange={(e) => setDraft(e.target.value)} />
                        <button type="button" className="atcm-send" onClick={send}>Send</button>
                      </div>
                    )}
                  </div>
                </article>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- IssueComments.vue — the conversation heartbeat of every Jira issue -->
        <script setup lang="ts">
        import { ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const likes = ref(3);
        const liked = ref(false);
        const replying = ref(false);
        const draft = ref('');
        const replies = ref<Array<{ author: string; body: string }>>([]);

        function toggleLike() {
          liked.value = !liked.value;
          likes.value += liked.value ? 1 : -1;
        }
        function send() {
          const body = draft.value.trim();
          if (!body) return;
          replies.value.push({ author: 'Milan Novak', body });
          draft.value = '';
          replying.value = false;
        }
        </script>

        <template>
          <div class="atcm-thread">
            <article class="atcm">
              <span class="atcm-av" aria-hidden="true">SA</span>
              <div class="atcm-main">
                <header class="atcm-head"><a href="#">Selin Aydin</a><time>2 hours ago</time></header>
                <div class="atcm-body">
                  I set the label to “In progress”; <a href="#">@Milan</a> move it to review once the audit passes.
                </div>
                <div class="atcm-actions">
                  <!-- The heart really counts. -->
                  <button type="button" :aria-pressed="liked" @click="toggleLike">♥ {{ likes }}</button>
                  <button type="button" @click="replying = !replying">Reply</button>
                </div>
                <div v-if="replies.length" class="atcm-replies">
                  <article v-for="(r, i) in replies" :key="i" class="atcm">
                    <span class="atcm-av" aria-hidden="true">MN</span>
                    <div class="atcm-main">
                      <header class="atcm-head"><a href="#">{{ r.author }}</a><time>just now</time></header>
                      <div class="atcm-body">{{ r.body }}</div>
                    </div>
                  </article>
                </div>
                <div v-if="replying" class="atcm-form">
                  <textarea v-model="draft" placeholder="Add a reply…" autofocus />
                  <button type="button" class="atcm-send" @click="send">Send</button>
                </div>
              </div>
            </article>
          </div>
        </template>

        <style scoped>
        .atcm-thread { max-inline-size: 26rem; border: 1px solid #DFE1E6; border-radius: 6px; background: #fff;
                       padding: 1rem 1.1rem; font-family: Inter, system-ui, sans-serif; }
        .atcm { display: flex; gap: .65rem; padding-block: .45rem; }
        .atcm-av { flex: none; display: grid; place-items: center; inline-size: 2rem; aspect-ratio: 1;
                   border-radius: 50%; background: #F1F2F4; font: 600 .72rem Inter, system-ui; color: #44546F; }
        .atcm-main { flex: 1; min-inline-size: 0; }
        .atcm-head { display: flex; align-items: baseline; gap: .5rem; }
        .atcm-head a { font: 600 .82rem Inter, system-ui; color: #0C66E4; text-decoration: none; }
        .atcm-head time { font: 400 .7rem Inter, system-ui; color: #626F86; }
        .atcm-body { margin: .2rem 0 .1rem; font: 400 .82rem/1.65 Inter, system-ui; color: #172B4D; }
        .atcm-body a { color: #0C66E4; font-weight: 500; text-decoration: none; }
        .atcm-actions { display: flex; gap: .25rem; }
        .atcm-actions button { display: inline-flex; gap: .3rem; border: none; background: none;
                               padding: .25rem .45rem; border-radius: 3px; cursor: pointer;
                               font: 600 .72rem Inter, system-ui; color: #626F86; }
        .atcm-actions button:hover { background: #F1F2F4; color: #0C66E4; }
        .atcm-actions button[aria-pressed='true'] { color: #0C66E4; }
        .atcm-replies { margin-inline-start: 1rem; padding-inline-start: .85rem;
                        border-inline-start: 2px solid #DFE1E6; }
        .atcm-form { display: flex; gap: .65rem; padding-block: .55rem; }
        .atcm-form textarea { flex: 1; min-block-size: 2.5rem; padding: .5rem .6rem; border: 1px solid #DFE1E6;
                              border-radius: 4px; font: 400 .8rem/1.55 Inter, system-ui; }
        .atcm-send { align-self: flex-start; block-size: 1.85rem; padding-inline: .75rem; border: none;
                     border-radius: 3px; cursor: pointer; background: #0C66E4; color: #fff;
                     font: 500 .76rem/1 Inter, system-ui; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- IssueComments.svelte — the conversation heartbeat of every Jira issue -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          let likes = $state(3);
          let liked = $state(false);
          let replying = $state(false);
          let draft = $state('');
          let replies = $state<Array<{ author: string; body: string }>>([]);

          function toggleLike() {
            liked = !liked;
            likes += liked ? 1 : -1;
          }
          function send() {
            const body = draft.trim();
            if (!body) return;
            replies.push({ author: 'Milan Novak', body });
            draft = '';
            replying = false;
          }
        </script>

        <div class="atcm-thread">
          <article class="atcm">
            <span class="atcm-av" aria-hidden="true">SA</span>
            <div class="atcm-main">
              <header class="atcm-head"><a href="#/profile/selin">Selin Aydin</a><time>2 hours ago</time></header>
              <div class="atcm-body">
                I set the label to “In progress”; <a href="#/profile/milan">@Milan</a> move it to review once the audit passes.
              </div>
              <div class="atcm-actions">
                <!-- The heart really counts. -->
                <button type="button" aria-pressed={liked} onclick={toggleLike}>♥ {likes}</button>
                <button type="button" onclick={() => (replying = !replying)}>Reply</button>
              </div>
              {#if replies.length > 0}
                <div class="atcm-replies">
                  {#each replies as r, i (i)}
                    <article class="atcm">
                      <span class="atcm-av" aria-hidden="true">MN</span>
                      <div class="atcm-main">
                        <header class="atcm-head"><a href="#/profile/milan">{r.author}</a><time>just now</time></header>
                        <div class="atcm-body">{r.body}</div>
                      </div>
                    </article>
                  {/each}
                </div>
              {/if}
              {#if replying}
                <div class="atcm-form">
                  <textarea bind:value={draft} placeholder="Add a reply…"></textarea>
                  <button type="button" class="atcm-send" onclick={send}>Send</button>
                </div>
              {/if}
            </div>
          </article>
        </div>

        <style>
        .atcm-thread { max-inline-size: 26rem; border: 1px solid #DFE1E6; border-radius: 6px; background: #fff;
                       padding: 1rem 1.1rem; font-family: Inter, system-ui, sans-serif; }
        .atcm { display: flex; gap: .65rem; padding-block: .45rem; }
        .atcm-av { flex: none; display: grid; place-items: center; inline-size: 2rem; aspect-ratio: 1;
                   border-radius: 50%; background: #F1F2F4; font: 600 .72rem Inter, system-ui; color: #44546F; }
        .atcm-main { flex: 1; min-inline-size: 0; }
        .atcm-head { display: flex; align-items: baseline; gap: .5rem; }
        .atcm-head a { font: 600 .82rem Inter, system-ui; color: #0C66E4; text-decoration: none; }
        .atcm-head time { font: 400 .7rem Inter, system-ui; color: #626F86; }
        .atcm-body { margin: .2rem 0 .1rem; font: 400 .82rem/1.65 Inter, system-ui; color: #172B4D; }
        .atcm-body a { color: #0C66E4; font-weight: 500; text-decoration: none; }
        .atcm-actions { display: flex; gap: .25rem; }
        .atcm-actions button { display: inline-flex; gap: .3rem; border: none; background: none;
                               padding: .25rem .45rem; border-radius: 3px; cursor: pointer;
                               font: 600 .72rem Inter, system-ui; color: #626F86; }
        .atcm-actions button:hover { background: #F1F2F4; color: #0C66E4; }
        .atcm-actions button[aria-pressed='true'] { color: #0C66E4; }
        .atcm-replies { margin-inline-start: 1rem; padding-inline-start: .85rem;
                        border-inline-start: 2px solid #DFE1E6; }
        .atcm-form { display: flex; gap: .65rem; padding-block: .55rem; }
        .atcm-form textarea { flex: 1; min-block-size: 2.5rem; padding: .5rem .6rem; border: 1px solid #DFE1E6;
                              border-radius: 4px; font: 400 .8rem/1.55 Inter, system-ui; }
        .atcm-send { align-self: flex-start; block-size: 1.85rem; padding-inline: .75rem; border: none;
                     border-radius: 3px; cursor: pointer; background: #0C66E4; color: #fff;
                     font: 500 .76rem/1 Inter, system-ui; }
        </style>
        SVELTE,
        ],
    ],
];
