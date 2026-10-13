<?php

/**
 * Demo manifest of the "primer" group — GitHub Primer rebuilt as faithful,
 * interactive skins: the iconic issue list, state labels, hex-coloured label
 * chips, the PR conversation timeline, emoji reaction bars, the query-syntax
 * filter bar, the merge panel, the underline nav with CounterLabels and the
 * Actions run list. Scenarios live at
 * resources/views/demos/components/primer/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'گیت‌هاب — پرایمر', 'en' => 'GitHub Primer'],

    'issue-list' => [
        'title' => ['fa' => 'فهرست ایشو', 'en' => 'Issue List'],
        'icon' => 'alert-circle',
        'oneLiner' => [
            'fa' => 'ردیف‌های ایشو با آیکن وضعیت دایره‌ای، عنوان پررنگ، متای خاکستری و چیپ‌های لیبل؛ همان صفحهٔ Issues گیت‌هاب که از اولین نگاه سیستم را لو می‌دهد.',
            'en' => 'GitHub\'s iconic Issues page as a component: circular state icons, bold titles, gray meta rows and label chips. The instant "this is GitHub" pattern.',
        ],
        'js' => true,
        'docs' => 'https://primer.style/components/action-list',
        'props' => [
            ['name' => 'state icon', 'type' => 'octicon · 16px', 'default' => "'issue-open'", 'note' => [
                'fa' => 'آیکن دایره‌ای ۱۶ پیکسلی در آغاز ردیف؛ باز سبز، بستهٔ کامل بنفش، برنامه‌ریزی‌نشده خاکستری.',
                'en' => 'The 16px circular icon that opens the row; open green, completed purple, not-planned gray.',
            ]],
            ['name' => 'title', 'type' => 'weight', 'default' => "'400 → hover accent'", 'note' => [
                'fa' => 'عنوان عادی است و فقط هاور به آبی گیت‌هاب می‌رود — بی‌حالت‌های اضافه.',
                'en' => 'The title is plain and only turns GitHub blue on hover — no extra states.',
            ]],
            ['name' => 'meta row', 'type' => '12px muted', 'default' => "'#59636e'", 'note' => [
                'fa' => 'ردیف متای خاکستری ۱۲ پیکسلی: شماره، باز‌شده توسط، زمان نسبی.',
                'en' => 'The 12px gray meta row: number, opened by, relative time.',
            ]],
            ['name' => 'labels', 'type' => 'chips', 'default' => "'end-aligned'", 'note' => [
                'fa' => 'چیپ‌های لیبل در انتهای ردیف می‌نشینند و روی موبایل می‌پیچند.',
                'en' => 'Label chips sit at the end of the row and wrap on mobile.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
            <a class="pri-row" href="#">
                <span class="pri-state" data-state="open">○</span>
                <span class="pri-body">
                    <span class="pri-title">کارت آمار در موبایل سرریز می‌کند</span>
                    <span class="pri-meta">#۴۱۲ باز شد ۳ روز پیش توسط سارا</span>
                </span>
                <span class="pri-label" style="--hue: #d73a4a">باگ</span>
            </a>

            <style>
            .pri-row { display: flex; gap: 8px; padding: 8px 16px; border-block-end: 1px solid #d1d9e0; }
            .pri-state[data-state='open'] { color: #1a7f37; }
            .pri-title { font-size: 16px; }
            .pri-meta { color: #59636e; font-size: 12px; }
            .pri-label { border: 1px solid color-mix(in srgb, var(--hue) 40%, transparent);
                         color: var(--hue); border-radius: 2em; padding: 0 7px; font-size: 12px; }
            </style>
            BLADE,
            'en' => <<<'BLADE'
            <a class="pri-row" href="#">
                <span class="pri-state" data-state="open">○</span>
                <span class="pri-body">
                    <span class="pri-title">Stat cards overflow at 375px</span>
                    <span class="pri-meta">#412 opened 3 days ago by Sara</span>
                </span>
                <span class="pri-label" style="--hue: #d73a4a">bug</span>
            </a>

            <style>
            .pri-row { display: flex; gap: 8px; padding: 8px 16px; border-block-end: 1px solid #d1d9e0; }
            .pri-state[data-state='open'] { color: #1a7f37; }
            .pri-title { font-size: 16px; }
            .pri-meta { color: #59636e; font-size: 12px; }
            .pri-label { border: 1px solid color-mix(in srgb, var(--hue) 40%, transparent);
                         color: var(--hue); border-radius: 2em; padding: 0 7px; font-size: 12px; }
            </style>
            BLADE,
            ],
            'inertia' => <<<'BLADE'
                // resources/js/Pages/Issues/Index.tsx — Inertia passes the issue
                // rows as props; the list itself is the React snippet.
                import { Head } from '@inertiajs/react';
                import IssueList from '@/components/primer/IssueList';

                export default function IssuesIndex() {
                  return (
                    <>
                      <Head title="Issues" />
                      {/* `issues` comes from the backend, tab state stays client-side */}
                      <IssueList />
                    </>
                  );
                }
                BLADE,
            'react' => <<<'BLADE'
                import { useState } from 'react';
                import '@nabuxai/ui-core/css';

                const ISSUES = [
                  { n: 412, state: 'open', title: 'Stat cards overflow at 375px', meta: '#412 opened 3 days ago by Elif', label: 'bug', hue: '#d73a4a' },
                  { n: 407, state: 'open', title: 'Chart tooltips miss the dark theme', meta: '#407 opened 5 days ago by Jonas', label: 'design', hue: '#0e8a16' },
                  { n: 396, state: 'closed', title: 'Queue the heavy renders off-request', meta: '#396 closed yesterday by Mia', label: 'enhancement', hue: '#a2eeef' },
                ];

                export default function IssueList() {
                  const [tab, setTab] = useState<'open' | 'closed'>('open');

                  return (
                    <div className="pri-list">
                      <div className="pri-tabs" role="tablist" aria-label="Filter by state">
                        {(['open', 'closed'] as const).map((s) => (
                          <button key={s} className="pri-tab" aria-pressed={tab === s} onClick={() => setTab(s)}>
                            {s === 'open' ? '○' : '●'} {s === 'open' ? 'Open' : 'Closed'}
                          </button>
                        ))}
                      </div>
                      {ISSUES.filter((i) => i.state === tab).map((i) => (
                        <a className="pri-row" href="#" key={i.n}>
                          <span className="pri-state" data-state={i.state}>○</span>
                          <span className="pri-body">
                            <span className="pri-title">{i.title}</span>
                            <span className="pri-meta">{i.meta}</span>
                          </span>
                          <span className="pri-label" style={{ '--hue': i.hue } as React.CSSProperties}>{i.label}</span>
                        </a>
                      ))}
                      <style>{`
                        .pri-row { display: flex; gap: 8px; padding: 8px 16px; border-block-end: 1px solid #d1d9e0; }
                        .pri-state[data-state='open'] { color: #1a7f37; }
                        .pri-title { font-size: 16px; }
                        .pri-meta { color: #59636e; font-size: 12px; }
                        .pri-label { border: 1px solid color-mix(in srgb, var(--hue) 40%, transparent);
                                     color: var(--hue); border-radius: 2em; padding: 0 7px; font-size: 12px; }
                      `}</style>
                    </div>
                  );
                }
                BLADE,
            'vue' => <<<'BLADE'
                <!-- IssueList.vue -->
                <script setup lang="ts">
                import { ref } from 'vue';
                import '@nabuxai/ui-core/css';

                interface Issue { n: number; state: 'open' | 'closed'; title: string; meta: string; label: string; hue: string }
                const issues: Issue[] = [
                  { n: 412, state: 'open', title: 'Stat cards overflow at 375px', meta: '#412 opened 3 days ago by Elif', label: 'bug', hue: '#d73a4a' },
                  { n: 396, state: 'closed', title: 'Queue the heavy renders off-request', meta: '#396 closed yesterday by Mia', label: 'enhancement', hue: '#a2eeef' },
                ];
                const tab = ref<'open' | 'closed'>('open');
                </script>

                <template>
                  <div class="pri-list">
                    <div class="pri-tabs" role="tablist" aria-label="Filter by state">
                      <button class="pri-tab" :aria-pressed="tab === 'open'" @click="tab = 'open'">○ Open</button>
                      <button class="pri-tab" :aria-pressed="tab === 'closed'" @click="tab = 'closed'">● Closed</button>
                    </div>
                    <a v-for="i in issues.filter((x) => x.state === tab)" :key="i.n" class="pri-row" href="#">
                      <span class="pri-state" :data-state="i.state">○</span>
                      <span class="pri-body">
                        <span class="pri-title">{{ i.title }}</span>
                        <span class="pri-meta">{{ i.meta }}</span>
                      </span>
                      <span class="pri-label" :style="{ '--hue': i.hue }">{{ i.label }}</span>
                    </a>
                  </div>
                </template>

                <style scoped>
                .pri-row { display: flex; gap: 8px; padding: 8px 16px; border-block-end: 1px solid #d1d9e0; }
                .pri-state[data-state='open'] { color: #1a7f37; }
                .pri-title { font-size: 16px; }
                .pri-meta { color: #59636e; font-size: 12px; }
                .pri-label { border: 1px solid color-mix(in srgb, var(--hue) 40%, transparent);
                             color: var(--hue); border-radius: 2em; padding: 0 7px; font-size: 12px; }
                </style>
                BLADE,
            'svelte' => <<<'BLADE'
                <!-- IssueList.svelte -->
                <script lang="ts">
                  import '@nabuxai/ui-core/css';

                  interface Issue { n: number; state: 'open' | 'closed'; title: string; meta: string; label: string; hue: string }
                  const issues: Issue[] = [
                    { n: 412, state: 'open', title: 'Stat cards overflow at 375px', meta: '#412 opened 3 days ago by Elif', label: 'bug', hue: '#d73a4a' },
                    { n: 396, state: 'closed', title: 'Queue the heavy renders off-request', meta: '#396 closed yesterday by Mia', label: 'enhancement', hue: '#a2eeef' },
                  ];
                  let tab = $state<'open' | 'closed'>('open');
                  const rows = $derived(issues.filter((i) => i.state === tab));
                </script>

                <div class="pri-list">
                  <div class="pri-tabs" role="tablist" aria-label="Filter by state">
                    <button class="pri-tab" aria-pressed={tab === 'open'} onclick={() => (tab = 'open')}>○ Open</button>
                    <button class="pri-tab" aria-pressed={tab === 'closed'} onclick={() => (tab = 'closed')}>● Closed</button>
                  </div>
                  {#each rows as i (i.n)}
                    <a class="pri-row" href="#/issues/{i.n}">
                      <span class="pri-state" data-state={i.state}>○</span>
                      <span class="pri-body">
                        <span class="pri-title">{i.title}</span>
                        <span class="pri-meta">{i.meta}</span>
                      </span>
                      <span class="pri-label" style:--hue={i.hue}>{i.label}</span>
                    </a>
                  {/each}
                </div>

                <style>
                .pri-row { display: flex; gap: 8px; padding: 8px 16px; border-block-end: 1px solid #d1d9e0; }
                .pri-state[data-state='open'] { color: #1a7f37; }
                .pri-title { font-size: 16px; }
                .pri-meta { color: #59636e; font-size: 12px; }
                .pri-label { border: 1px solid color-mix(in srgb, var(--hue) 40%, transparent);
                             color: var(--hue); border-radius: 2em; padding: 0 7px; font-size: 12px; }
                </style>
                BLADE,
        ],
    ],

    'state-label' => [
        'title' => ['fa' => 'لیبل وضعیت', 'en' => 'State Label'],
        'icon' => 'check-circle',
        'oneLiner' => [
            'fa' => 'قرص‌های وضعیت با آیکن دایره‌ای: سبز Open (#1A7F37)، بنفش Merged (#8250DF)، قرمز Closed و خاکستری Draft؛ کوچک‌ترین اتمی که بلافاصله می‌گوید گیت‌هاب است.',
            'en' => 'Open, Closed, Merged, Draft pills with circular octicons in Primer\'s success green, done purple and danger red. The smallest atom that screams GitHub.',
        ],
        'js' => true,
        'docs' => 'https://primer.style/components/state-label',
        'props' => [
            ['name' => 'status', 'type' => 'state', 'default' => "'issueOpen'", 'note' => [
                'fa' => 'issueOpen · issueClosed · prOpen · prMerged · prClosed · draft؛ هر آیتم‌شمار و رنگش با هم عوض می‌شوند.',
                'en' => 'issueOpen · issueClosed · prOpen · prMerged · prClosed · draft; the octicon and the colour switch together.',
            ]],
            ['name' => 'icon', 'type' => 'octicon · 16px', 'default' => "'filled circle'", 'note' => [
                'fa' => 'آیکن دایرهٔ توپُر سفید روی زمینهٔ رنگی — امضای StateLabel.',
                'en' => 'A filled white circle glyph on the tinted field — the StateLabel signature.',
            ]],
            ['name' => 'radius', 'type' => 'length', 'default' => "'2em'", 'note' => [
                'fa' => 'قرص تمام‌گرد؛ متن ۱۲ پیکسل با وزن ۵۰۰.',
                'en' => 'The fully rounded pill; 12px text at weight 500.',
            ]],
            ['name' => 'size', 'type' => 'height', 'default' => "'24 · 32'", 'note' => [
                'fa' => 'دو اندازهٔ رسمی: کوچک درون ردیف‌ها، بزرگ در سربرگ ایشو و PR.',
                'en' => 'The two official sizes: small inside rows, large on the issue/PR header.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
            <span class="prs-label" data-status="open">
                <svg viewBox="0 0 16 16">…</svg> Open
            </span>
            <span class="prs-label" data-status="merged">
                <svg viewBox="0 0 16 16">…</svg> Merged
            </span>

            <style>
            .prs-label { display: inline-flex; align-items: center; gap: 4px; block-size: 24px;
                         padding-inline: 10px; border-radius: 2em; font: 500 12px system-ui; }
            .prs-label[data-status='open'] { background: #1A7F37; color: #fff; }
            .prs-label[data-status='merged'] { background: #8250DF; color: #fff; }
            </style>
            BLADE,
            'en' => <<<'BLADE'
            <span class="prs-label" data-status="open">
                <svg viewBox="0 0 16 16">…</svg> Open
            </span>
            <span class="prs-label" data-status="merged">
                <svg viewBox="0 0 16 16">…</svg> Merged
            </span>

            <style>
            .prs-label { display: inline-flex; align-items: center; gap: 4px; block-size: 24px;
                         padding-inline: 10px; border-radius: 2em; font: 500 12px system-ui; }
            .prs-label[data-status='open'] { background: #1A7F37; color: #fff; }
            .prs-label[data-status='merged'] { background: #8250DF; color: #fff; }
            </style>
            BLADE,
            ],
            'inertia' => <<<'BLADE'
                // resources/js/Pages/Pulls/Show.tsx — the PR header mounts the
                // React snippet; the state lifecycle lives entirely client-side.
                import { Head } from '@inertiajs/react';
                import StateLabel from '@/components/primer/StateLabel';

                export default function PullShow() {
                  return (
                    <>
                      <Head title="Pull request #412" />
                      <StateLabel initial="open" />
                    </>
                  );
                }
                BLADE,
            'react' => <<<'BLADE'
                import { useState } from 'react';
                import '@nabuxai/ui-core/css';

                type Status = 'open' | 'merged' | 'closed' | 'draft';
                const PILL: Record<Status, string> = { open: 'Open', merged: 'Merged', closed: 'Closed', draft: 'Draft' };

                export default function StateLabel({ initial = 'open' }: { initial?: Status }) {
                  const [st, setSt] = useState<Status>(initial);

                  return (
                    <div style={{ display: 'grid', gap: 12, justifyItems: 'start' }}>
                      <span className="prs-label" data-status={st}>● {PILL[st]}</span>
                      <div role="group" aria-label="Change state" style={{ display: 'flex', gap: 8 }}>
                        {(Object.keys(PILL) as Status[]).map((s) => (
                          <button key={s} aria-pressed={st === s} onClick={() => setSt(s)}>{PILL[s]}</button>
                        ))}
                      </div>
                      <style>{`
                        .prs-label { display: inline-flex; align-items: center; gap: 4px; block-size: 24px;
                                     padding-inline: 10px; border-radius: 2em; font: 500 12px system-ui; }
                        .prs-label[data-status='open'] { background: #1A7F37; color: #fff; }
                        .prs-label[data-status='merged'] { background: #8250DF; color: #fff; }
                        .prs-label[data-status='closed'] { background: #CF222E; color: #fff; }
                        .prs-label[data-status='draft'] { background: #59636E; color: #fff; }
                      `}</style>
                    </div>
                  );
                }
                BLADE,
            'vue' => <<<'BLADE'
                <!-- StateLabel.vue -->
                <script setup lang="ts">
                import { ref } from 'vue';
                import '@nabuxai/ui-core/css';

                type Status = 'open' | 'merged' | 'closed' | 'draft';
                const PILL: Record<Status, string> = { open: 'Open', merged: 'Merged', closed: 'Closed', draft: 'Draft' };
                const st = ref<Status>('open');
                </script>

                <template>
                  <div style="display: grid; gap: 12px; justify-items: start">
                    <span class="prs-label" :data-status="st">● {{ PILL[st] }}</span>
                    <div role="group" aria-label="Change state" style="display: flex; gap: 8px">
                      <button v-for="(label, s) in PILL" :key="s" :aria-pressed="st === s" @click="st = s">{{ label }}</button>
                    </div>
                  </div>
                </template>

                <style scoped>
                .prs-label { display: inline-flex; align-items: center; gap: 4px; block-size: 24px;
                             padding-inline: 10px; border-radius: 2em; font: 500 12px system-ui; }
                .prs-label[data-status='open'] { background: #1a7f37; color: #fff; }
                .prs-label[data-status='merged'] { background: #8250df; color: #fff; }
                .prs-label[data-status='closed'] { background: #cf222e; color: #fff; }
                .prs-label[data-status='draft'] { background: #59636e; color: #fff; }
                </style>
                BLADE,
            'svelte' => <<<'BLADE'
                <!-- StateLabel.svelte -->
                <script lang="ts">
                  import '@nabuxai/ui-core/css';

                  type Status = 'open' | 'merged' | 'closed' | 'draft';
                  const PILL: Record<Status, string> = { open: 'Open', merged: 'Merged', closed: 'Closed', draft: 'Draft' };
                  let { initial = 'open' }: { initial?: Status } = $props();
                  let st = $state<Status>(initial);
                </script>

                <div style="display: grid; gap: 12px; justify-items: start">
                  <span class="prs-label" data-status={st}>● {PILL[st]}</span>
                  <div role="group" aria-label="Change state" style="display: flex; gap: 8px">
                    {#each Object.keys(PILL) as s (s)}
                      <button aria-pressed={st === s} onclick={() => (st = s as Status)}>{PILL[s as Status]}</button>
                    {/each}
                  </div>
                </div>

                <style>
                .prs-label { display: inline-flex; align-items: center; gap: 4px; block-size: 24px;
                             padding-inline: 10px; border-radius: 2em; font: 500 12px system-ui; }
                .prs-label[data-status='open'] { background: #1a7f37; color: #fff; }
                .prs-label[data-status='merged'] { background: #8250df; color: #fff; }
                .prs-label[data-status='closed'] { background: #cf222e; color: #fff; }
                .prs-label[data-status='draft'] { background: #59636e; color: #fff; }
                </style>
                BLADE,
        ],
    ],

    'label-chip' => [
        'title' => ['fa' => 'چیپ لیبل', 'en' => 'Label Chip'],
        'icon' => 'sparkles',
        'oneLiner' => [
            'fa' => 'لیبل‌های کاملاً گرد با رنگ Hex دلخواه، حاشیهٔ هم‌رنگ و متن اشباع‌شده؛ سامانهٔ برچسب‌گذاری رنگارنگ گیت‌هاب که هر مخزن را در یک نگاه قابل‌تفکیک می‌کند.',
            'en' => 'Fully-rounded hex-colored labels with matching borders and saturated text. GitHub\'s color-coded taxonomy that makes every repo scannable.',
        ],
        'js' => true,
        'docs' => 'https://primer.style/components/label',
        'props' => [
            ['name' => 'hue', 'type' => 'color', 'default' => "'#d73a4a'", 'note' => [
                'fa' => 'رنگ Hex دلخواه؛ متن از همین hue ساخته می‌شود.',
                'en' => 'Any hex colour; the text derives from the same hue.',
            ]],
            ['name' => 'border', 'type' => 'alpha', 'default' => "'≈ 40%'", 'note' => [
                'fa' => 'حاشیه هم‌رنگِ متن با آلفای کم — لیبل‌های سفید هم دیده می‌شوند.',
                'en' => 'A same-hue border at low alpha — even white labels stay visible.',
            ]],
            ['name' => 'radius', 'type' => 'length', 'default' => "'2em'", 'note' => [
                'fa' => 'گردی کامل؛ ارتفاع ۲۰ پیکسل و متن ۱۲ پیکسل.',
                'en' => 'Full rounding; 20px height, 12px text.',
            ]],
            ['name' => 'group', 'type' => 'layout', 'default' => "'inline · 8px gap'", 'note' => [
                'fa' => 'لیبل‌ها در ردیف انعطاف‌پذیر می‌پیچند؛ +۱۰ بیشتر با شمارنده.',
                'en' => 'Labels wrap in a flex row; a +10 more counter trims the rest.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
            <span class="prl-label" style="--hue: #7057ff">good first issue</span>
            <span class="prl-label" style="--hue: #008672">help wanted</span>
            <span class="prl-label" style="--hue: #ffffff">wontfix</span>

            <style>
            .prl-label { display: inline-flex; align-items: center; block-size: 20px;
                         padding-inline: 7px; border-radius: 2em; font: 500 12px system-ui;
                         color: color-mix(in srgb, var(--hue) 70%, black);
                         border: 1px solid color-mix(in srgb, var(--hue) 40%, transparent); }
            html[data-theme="dark"] .prl-label {
                color: color-mix(in srgb, var(--hue) 80%, white); }
            </style>
            BLADE,
            'en' => <<<'BLADE'
            <span class="prl-label" style="--hue: #7057ff">good first issue</span>
            <span class="prl-label" style="--hue: #008672">help wanted</span>
            <span class="prl-label" style="--hue: #ffffff">wontfix</span>

            <style>
            .prl-label { display: inline-flex; align-items: center; block-size: 20px;
                         padding-inline: 7px; border-radius: 2em; font: 500 12px system-ui;
                         color: color-mix(in srgb, var(--hue) 70%, black);
                         border: 1px solid color-mix(in srgb, var(--hue) 40%, transparent); }
            html[data-theme="dark"] .prl-label {
                color: color-mix(in srgb, var(--hue) 80%, white); }
            </style>
            BLADE,
            ],
            'inertia' => <<<'BLADE'
                // resources/js/Pages/Labels/Index.tsx — the labels page mounts the
                // React snippet; Create posts through Inertia and reloads props.
                import { Head, router } from '@inertiajs/react';
                import LabelChips from '@/components/primer/LabelChips';

                export default function LabelsIndex() {
                  return (
                    <>
                      <Head title="Labels" />
                      {/* onCreate={(name, hue) => router.post('/labels', { name, hue })} */}
                      <LabelChips />
                    </>
                  );
                }
                BLADE,
            'react' => <<<'BLADE'
                import { useState } from 'react';
                import '@nabuxai/ui-core/css';

                const PALETTE = ['#d73a4a', '#0e8a16', '#0075ca', '#a2eeef', '#7057ff', '#ffffff'];

                export default function LabelChips() {
                  const [labels, setLabels] = useState([
                    { name: 'bug', hue: '#d73a4a' },
                    { name: 'good first issue', hue: '#7057ff' },
                    { name: 'help wanted', hue: '#008672' },
                  ]);
                  const [draft, setDraft] = useState('');
                  const [hue, setHue] = useState('#d93f0b');

                  return (
                    <div style={{ display: 'grid', gap: 12 }}>
                      <div className="prl-strip">
                        {labels.map((l) => (
                          <span key={l.name} className="prl-label" style={{ '--hue': l.hue } as React.CSSProperties}>{l.name}</span>
                        ))}
                      </div>
                      <form
                        style={{ display: 'flex', gap: 8 }}
                        onSubmit={(e) => {
                          e.preventDefault();
                          if (!draft.trim()) return;
                          setLabels([{ name: draft.trim(), hue }, ...labels]);
                          setDraft('');
                        }}
                      >
                        <input value={draft} onChange={(e) => setDraft(e.target.value)} placeholder="new label name" aria-label="Label name" />
                        {PALETTE.map((h) => (
                          <button
                            key={h} type="button" aria-pressed={hue === h} aria-label={h}
                            style={{ background: h, '--hue': h } as React.CSSProperties}
                            onClick={() => setHue(h)}
                          />
                        ))}
                        <button type="submit">Create label</button>
                      </form>
                      <style>{`
                        .prl-strip { display: flex; gap: 8px; flex-wrap: wrap; }
                        .prl-label { display: inline-flex; align-items: center; block-size: 20px;
                                     padding-inline: 7px; border-radius: 2em; font: 500 12px system-ui;
                                     color: color-mix(in srgb, var(--hue) 70%, black);
                                     border: 1px solid color-mix(in srgb, var(--hue) 40%, transparent); }
                      `}</style>
                    </div>
                  );
                }
                BLADE,
            'vue' => <<<'BLADE'
                <!-- LabelChips.vue -->
                <script setup lang="ts">
                import { ref } from 'vue';
                import '@nabuxai/ui-core/css';

                const PALETTE = ['#d73a4a', '#0e8a16', '#0075ca', '#a2eeef', '#7057ff', '#ffffff'];
                const labels = ref([
                  { name: 'bug', hue: '#d73a4a' },
                  { name: 'good first issue', hue: '#7057ff' },
                ]);
                const draft = ref('');
                const hue = ref('#d93f0b');
                const create = () => {
                  if (!draft.value.trim()) return;
                  labels.value.unshift({ name: draft.value.trim(), hue: hue.value });
                  draft.value = '';
                };
                </script>

                <template>
                  <div style="display: grid; gap: 12px">
                    <div class="prl-strip">
                      <span v-for="l in labels" :key="l.name" class="prl-label" :style="{ '--hue': l.hue }">{{ l.name }}</span>
                    </div>
                    <form style="display: flex; gap: 8px" @submit.prevent="create">
                      <input v-model="draft" placeholder="new label name" aria-label="Label name" />
                      <button
                        v-for="h in PALETTE" :key="h" type="button" :aria-pressed="hue === h" :aria-label="h"
                        :style="{ background: h, '--hue': h }" @click="hue = h"
                      />
                      <button type="submit">Create label</button>
                    </form>
                  </div>
                </template>

                <style scoped>
                .prl-strip { display: flex; gap: 8px; flex-wrap: wrap; }
                .prl-label { display: inline-flex; align-items: center; block-size: 20px;
                             padding-inline: 7px; border-radius: 2em; font: 500 12px system-ui;
                             color: color-mix(in srgb, var(--hue) 70%, black);
                             border: 1px solid color-mix(in srgb, var(--hue) 40%, transparent); }
                </style>
                BLADE,
            'svelte' => <<<'BLADE'
                <!-- LabelChips.svelte -->
                <script lang="ts">
                  import '@nabuxai/ui-core/css';

                  const PALETTE = ['#d73a4a', '#0e8a16', '#0075ca', '#a2eeef', '#7057ff', '#ffffff'];
                  let labels = $state([
                    { name: 'bug', hue: '#d73a4a' },
                    { name: 'good first issue', hue: '#7057ff' },
                  ]);
                  let draft = $state('');
                  let hue = $state('#d93f0b');
                  const create = () => {
                    if (!draft.trim()) return;
                    labels.unshift({ name: draft.trim(), hue });
                    draft = '';
                  };
                </script>

                <div style="display: grid; gap: 12px">
                  <div class="prl-strip">
                    {#each labels as l (l.name)}<span class="prl-label" style:--hue={l.hue}>{l.name}</span>{/each}
                  </div>
                  <form style="display: flex; gap: 8px" onsubmit={(e) => { e.preventDefault(); create(); }}>
                    <input bind:value={draft} placeholder="new label name" aria-label="Label name" />
                    {#each PALETTE as h (h)}
                      <button type="button" aria-pressed={hue === h} aria-label={h} style:background={h} style:--hue={h} onclick={() => (hue = h)}></button>
                    {/each}
                    <button type="submit">Create label</button>
                  </form>
                </div>

                <style>
                .prl-strip { display: flex; gap: 8px; flex-wrap: wrap; }
                .prl-label { display: inline-flex; align-items: center; block-size: 20px;
                             padding-inline: 7px; border-radius: 2em; font: 500 12px system-ui;
                             color: color-mix(in srgb, var(--hue) 70%, black);
                             border: 1px solid color-mix(in srgb, var(--hue) 40%, transparent); }
                </style>
                BLADE,
        ],
    ],

    'pr-timeline' => [
        'title' => ['fa' => 'تایم‌لاین پول‌ریکوئست', 'en' => 'PR Timeline'],
        'icon' => 'message',
        'oneLiner' => [
            'fa' => 'ریل عمودی رویدادها با آیکن‌های اکت‌آیکون داخل حباب‌های دایره‌ای: کامیت، ریویو، کامنت و force-push؛ قلب بصری صفحهٔ Conversation هر پول‌ریکوئست.',
            'en' => 'A vertical event rail with octicons in circular bubbles: commits, reviews, comments, force-pushes. The visual heart of every PR conversation.',
        ],
        'js' => true,
        'docs' => 'https://primer.style/components/timeline',
        'props' => [
            ['name' => 'rail', 'type' => '2px line', 'default' => "'border-muted'", 'note' => [
                'fa' => 'خط عمودی مویی پشت حباب‌ها؛ در RTL هم از سمت آغازین می‌گذرد.',
                'en' => 'The hairline vertical rail behind the bubbles; it hugs the start side in RTL too.',
            ]],
            ['name' => 'badge', 'type' => '32px circle', 'default' => "'octicon 16'", 'note' => [
                'fa' => 'حباب ۳۲ پیکسلی با آیکن رویداد؛ رنگش نوع رویداد را لو می‌دهد.',
                'en' => 'The 32px bubble carrying the event octicon; its colour names the event kind.',
            ]],
            ['name' => 'condense', 'type' => 'state', 'default' => "'false'", 'note' => [
                'fa' => 'حالت فشرده، کامیت‌های حاشیه‌ای را زیر «and ۳ more commits» جمع می‌کند.',
                'en' => 'Condensed mode folds side commits under "and 3 more commits".',
            ]],
            ['name' => 'comment card', 'type' => 'body', 'default' => "'markdown'", 'note' => [
                'fa' => 'کامنت‌ها با سربرگ نویسنده/زمان و بدنهٔ مارک‌داون در بدنهٔ تایم‌لاین می‌نشینند.',
                'en' => 'Comments sit on the rail with an author/time header and a markdown body.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
            <div class="prt-item">
                <span class="prt-badge" data-kind="commit"><svg>…</svg></span>
                <div class="prt-body">
                    <b>سارا کامیت زد</b> <time>۲ ساعت پیش</time>
                    <p>فیلتر تاریخ را اصلاح کرد</p>
                </div>
            </div>

            <style>
            .prt-item { position: relative; display: flex; gap: 12px; padding-block-end: 24px; }
            .prt-item::before { content: ''; position: absolute; inset-block: 0; inset-inline-start: 15px;
                                inline-size: 2px; background: #d1d9e0; }
            .prt-badge { position: relative; z-index: 1; display: grid; place-items: center;
                         inline-size: 32px; aspect-ratio: 1; border-radius: 50%;
                         background: #ddf4ff; color: #0969da; }
            </style>
            BLADE,
            'en' => <<<'BLADE'
            <div class="prt-item">
                <span class="prt-badge" data-kind="commit"><svg>…</svg></span>
                <div class="prt-body">
                    <b>Sara committed</b> <time>2 hours ago</time>
                    <p>tightened the date filter</p>
                </div>
            </div>

            <style>
            .prt-item { position: relative; display: flex; gap: 12px; padding-block-end: 24px; }
            .prt-item::before { content: ''; position: absolute; inset-block: 0; inset-inline-start: 15px;
                                inline-size: 2px; background: #d1d9e0; }
            .prt-badge { position: relative; z-index: 1; display: grid; place-items: center;
                         inline-size: 32px; aspect-ratio: 1; border-radius: 50%;
                         background: #ddf4ff; color: #0969da; }
            </style>
            BLADE,
            ],
            'inertia' => <<<'BLADE'
                // resources/js/Pages/Pulls/Conversation.tsx — Inertia owns the PR
                // payload; new comments POST and the list reloads.
                import { Head, router } from '@inertiajs/react';
                import PrTimeline from '@/components/primer/PrTimeline';

                export default function PullConversation() {
                  return (
                    <>
                      <Head title="Conversation · PR #412" />
                      {/* onComment={(body) => router.post('/pulls/412/comments', { body })} */}
                      <PrTimeline />
                    </>
                  );
                }
                BLADE,
            'react' => <<<'BLADE'
                import { useState } from 'react';
                import '@nabuxai/ui-core/css';

                export default function PrTimeline() {
                  const [comments, setComments] = useState<string[]>([]);
                  const [draft, setDraft] = useState('');
                  const send = () => {
                    if (!draft.trim()) return;
                    setComments([...comments, draft.trim()]);
                    setDraft('');
                  };

                  return (
                    <div className="prt-card">
                      <div className="prt-item">
                        <span className="prt-badge" data-kind="commit">●</span>
                        <div className="prt-body">
                          <b>Elif pushed 3 commits</b> <time>· 2 days ago</time>
                          <p><code>a1b2c3d</code> — pick the PDF renderer</p>
                        </div>
                      </div>
                      {comments.map((c, i) => (
                        <div className="prt-item" key={i}>
                          <span className="prt-badge">💬</span>
                          <div className="prt-body"><div className="prt-comment"><p>{c}</p></div></div>
                        </div>
                      ))}
                      <div className="prt-composer">
                        <textarea
                          value={draft} onChange={(e) => setDraft(e.target.value)}
                          placeholder="Leave a comment — it joins the rail above" aria-label="Write a comment"
                        />
                        <button disabled={!draft.trim()} onClick={send}>Comment</button>
                      </div>
                      <style>{`
                        .prt-item { position: relative; display: flex; gap: 12px; padding-block-end: 24px; }
                        .prt-item::before { content: ''; position: absolute; inset-block: 0; inset-inline-start: 15px;
                                            inline-size: 2px; background: #d1d9e0; }
                        .prt-badge { position: relative; z-index: 1; display: grid; place-items: center; inline-size: 32px;
                                     aspect-ratio: 1; border-radius: 50%; background: #ddf4ff; color: #0969da; }
                      `}</style>
                    </div>
                  );
                }
                BLADE,
            'vue' => <<<'BLADE'
                <!-- PrTimeline.vue -->
                <script setup lang="ts">
                import { ref } from 'vue';
                import '@nabuxai/ui-core/css';

                const comments = ref<string[]>([]);
                const draft = ref('');
                const send = () => {
                  if (!draft.value.trim()) return;
                  comments.value.push(draft.value.trim());
                  draft.value = '';
                };
                </script>

                <template>
                  <div class="prt-card">
                    <div class="prt-item">
                      <span class="prt-badge" data-kind="commit">●</span>
                      <div class="prt-body">
                        <b>Elif pushed 3 commits</b> <time>· 2 days ago</time>
                        <p><code>a1b2c3d</code> — pick the PDF renderer</p>
                      </div>
                    </div>
                    <div v-for="(c, i) in comments" :key="i" class="prt-item">
                      <span class="prt-badge">💬</span>
                      <div class="prt-body"><div class="prt-comment"><p>{{ c }}</p></div></div>
                    </div>
                    <div class="prt-composer">
                      <textarea v-model="draft" placeholder="Leave a comment — it joins the rail above" aria-label="Write a comment" />
                      <button :disabled="!draft.trim()" @click="send">Comment</button>
                    </div>
                  </div>
                </template>

                <style scoped>
                .prt-item { position: relative; display: flex; gap: 12px; padding-block-end: 24px; }
                .prt-item::before { content: ''; position: absolute; inset-block: 0; inset-inline-start: 15px;
                                    inline-size: 2px; background: #d1d9e0; }
                .prt-badge { position: relative; z-index: 1; display: grid; place-items: center; inline-size: 32px;
                             aspect-ratio: 1; border-radius: 50%; background: #ddf4ff; color: #0969da; }
                </style>
                BLADE,
            'svelte' => <<<'BLADE'
                <!-- PrTimeline.svelte -->
                <script lang="ts">
                  import '@nabuxai/ui-core/css';

                  let comments = $state<string[]>([]);
                  let draft = $state('');
                  const send = () => {
                    if (!draft.trim()) return;
                    comments.push(draft.trim());
                    draft = '';
                  };
                </script>

                <div class="prt-card">
                  <div class="prt-item">
                    <span class="prt-badge" data-kind="commit">●</span>
                    <div class="prt-body">
                      <b>Elif pushed 3 commits</b> <time>· 2 days ago</time>
                      <p><code>a1b2c3d</code> — pick the PDF renderer</p>
                    </div>
                  </div>
                  {#each comments as c, i (i)}
                    <div class="prt-item">
                      <span class="prt-badge">💬</span>
                      <div class="prt-body"><div class="prt-comment"><p>{c}</p></div></div>
                    </div>
                  {/each}
                  <div class="prt-composer">
                    <textarea bind:value={draft} placeholder="Leave a comment — it joins the rail above" aria-label="Write a comment"></textarea>
                    <button disabled={!draft.trim()} onclick={send}>Comment</button>
                  </div>
                </div>

                <style>
                .prt-item { position: relative; display: flex; gap: 12px; padding-block-end: 24px; }
                .prt-item::before { content: ''; position: absolute; inset-block: 0; inset-inline-start: 15px;
                                    inline-size: 2px; background: #d1d9e0; }
                .prt-badge { position: relative; z-index: 1; display: grid; place-items: center; inline-size: 32px;
                             aspect-ratio: 1; border-radius: 50%; background: #ddf4ff; color: #0969da; }
                </style>
                BLADE,
        ],
    ],

    'reaction-bar' => [
        'title' => ['fa' => 'نوار واکنش‌ها', 'en' => 'Reaction Bar'],
        'icon' => 'heart',
        'oneLiner' => [
            'fa' => 'دکمه‌های واکنش ایموجی با شمارندهٔ گردی و پاپ‌اور افزودن واکنش؛ همان 😃🚀eyes گیت‌هاب که فیدبک سریع را به زبان ایموجی ترجمه می‌کند.',
            'en' => 'Emoji reaction pills with counters and an add-reaction picker. GitHub\'s 😃🚀👀 shorthand for instant, human feedback.',
        ],
        'js' => true,
        'docs' => 'https://primer.style/components/counter-label',
        'props' => [
            ['name' => 'pill', 'type' => 'button', 'default' => "'emoji + count'", 'note' => [
                'fa' => 'ایموجی و شمارندهٔ گرد در یک دکمه؛ واکنش شما حاشیهٔ آبی می‌گیرد.',
                'en' => 'Emoji and a rounded counter in one button; your own reaction gets the blue ring.',
            ]],
            ['name' => 'counter', 'type' => 'CounterLabel', 'default' => "'min-width 20px'", 'note' => [
                'fa' => 'شمارندهٔ گرد خاکستری؛ صفر مخفی می‌شود و قرص کل حذف می‌شود.',
                'en' => 'The rounded gray counter; at zero the pill drops out entirely.',
            ]],
            ['name' => 'add', 'type' => 'button', 'default' => "'smiley +'", 'note' => [
                'fa' => 'دکمهٔ + با پاپ‌اور ایموجی‌ها؛ واکنش تازه را همان‌جا می‌سازد.',
                'en' => 'The + button with an emoji popover; it mints a fresh reaction pill in place.',
            ]],
            ['name' => 'who', 'type' => 'tooltip', 'default' => "'aria-label'", 'note' => [
                'fa' => 'برچسب دسترس‌پذیری نام واکنش‌دهنده‌ها را می‌گوید.',
                'en' => 'The accessible label names who reacted.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
            <button class="prr-pill" aria-pressed="false" aria-label="۳ نفر 🚀 زدند">
                🚀 <span class="prr-count">۳</span>
            </button>
            <button class="prr-add" aria-label="افزودن واکنش">🙂⁺</button>

            <style>
            .prr-pill { display: inline-flex; align-items: center; gap: 6px; block-size: 28px;
                        padding-inline: 10px; border-radius: 2em; border: 1px solid #d1d9e0;
                        background: transparent; font: 500 13px system-ui; }
            .prr-count { min-inline-size: 20px; padding-inline: 6px; border-radius: 2em;
                         background: #eff2f5; font-size: 12px; }
            .prr-pill[aria-pressed='true'] { border-color: #0969da; }
            .prr-pill[aria-pressed='true'] .prr-count { background: #ddf4ff; color: #0969da; }
            </style>
            BLADE,
            'en' => <<<'BLADE'
            <button class="prr-pill" aria-pressed="false" aria-label="3 people reacted with 🚀">
                🚀 <span class="prr-count">3</span>
            </button>
            <button class="prr-add" aria-label="Add reaction">🙂⁺</button>

            <style>
            .prr-pill { display: inline-flex; align-items: center; gap: 6px; block-size: 28px;
                        padding-inline: 10px; border-radius: 2em; border: 1px solid #d1d9e0;
                        background: transparent; font: 500 13px system-ui; }
            .prr-count { min-inline-size: 20px; padding-inline: 6px; border-radius: 2em;
                         background: #eff2f5; font-size: 12px; }
            .prr-pill[aria-pressed='true'] { border-color: #0969da; }
            .prr-pill[aria-pressed='true'] .prr-count { background: #ddf4ff; color: #0969da; }
            </style>
            BLADE,
            ],
            'inertia' => <<<'BLADE'
                // resources/js/Pages/Comments/Show.tsx — reactions toggle against
                // the API; Inertia revalidates the comment props afterwards.
                import { Head, router } from '@inertiajs/react';
                import ReactionBar from '@/components/primer/ReactionBar';

                export default function CommentShow() {
                  return (
                    <>
                      <Head title="Release notes · 2.4" />
                      {/* onToggle={(emoji) => router.post(`/comments/${id}/reactions`, { emoji })} */}
                      <ReactionBar />
                    </>
                  );
                }
                BLADE,
            'react' => <<<'BLADE'
                import { useState } from 'react';
                import '@nabuxai/ui-core/css';

                type Rx = { e: string; n: number; mine: boolean };
                const TRAY = ['👍', '👎', '😄', '🎉', '❤️', '🚀', '👀'];

                export default function ReactionBar() {
                  const [rx, setRx] = useState<Rx[]>([
                    { e: '🎉', n: 12, mine: true },
                    { e: '🚀', n: 8, mine: false },
                  ]);
                  const [open, setOpen] = useState(false);

                  const toggle = (r: Rx) =>
                    setRx((list) => list.map((x) => (x.e === r.e ? { ...x, mine: !x.mine, n: x.n + (x.mine ? -1 : 1) } : x)));
                  const pick = (e: string) => {
                    setRx((list) =>
                      list.some((x) => x.e === e)
                        ? list.map((x) => (x.e === e ? { ...x, n: x.n + 1, mine: true } : x))
                        : [...list, { e, n: 1, mine: true }],
                    );
                    setOpen(false);
                  };

                  return (
                    <div className="prr-bar">
                      {rx.filter((r) => r.n > 0).map((r) => (
                        <button key={r.e} className="prr-pill" aria-pressed={r.mine} aria-label={`${r.n} reactions with ${r.e}`} onClick={() => toggle(r)}>
                          <span aria-hidden>{r.e}</span><span className="prr-count">{r.n}</span>
                        </button>
                      ))}
                      <div className="prr-add">
                        <button aria-label="Add reaction" aria-expanded={open} onClick={() => setOpen(!open)}>🙂⁺</button>
                        {open && (
                          <div className="prr-picker" role="menu" aria-label="Pick a reaction">
                            {TRAY.map((e) => <button key={e} role="menuitem" aria-label={e} onClick={() => pick(e)}>{e}</button>)}
                          </div>
                        )}
                      </div>
                      <style>{`
                        .prr-bar { display: flex; gap: 8px; }
                        .prr-pill { display: inline-flex; align-items: center; gap: 6px; block-size: 28px; padding-inline: 10px;
                                    border-radius: 2em; border: 1px solid #d1d9e0; background: transparent; font: 500 13px system-ui; }
                        .prr-count { min-inline-size: 20px; padding-inline: 6px; border-radius: 2em; background: #eff2f5; font-size: 12px; }
                        .prr-pill[aria-pressed='true'] { border-color: #0969da; }
                        .prr-pill[aria-pressed='true'] .prr-count { background: #ddf4ff; color: #0969da; }
                      `}</style>
                    </div>
                  );
                }
                BLADE,
            'vue' => <<<'BLADE'
                <!-- ReactionBar.vue -->
                <script setup lang="ts">
                import { ref } from 'vue';
                import '@nabuxai/ui-core/css';

                interface Rx { e: string; n: number; mine: boolean }
                const TRAY = ['👍', '👎', '😄', '🎉', '❤️', '🚀', '👀'];
                const rx = ref<Rx[]>([{ e: '🎉', n: 12, mine: true }, { e: '🚀', n: 8, mine: false }]);
                const open = ref(false);
                const toggle = (r: Rx) => { r.mine = !r.mine; r.n += r.mine ? 1 : -1; };
                const pick = (e: string) => {
                  const hit = rx.value.find((x) => x.e === e);
                  if (hit) { hit.n += 1; hit.mine = true } else { rx.value.push({ e, n: 1, mine: true }) }
                  open.value = false;
                };
                </script>

                <template>
                  <div class="prr-bar">
                    <button
                      v-for="r in rx.filter((x) => x.n > 0)" :key="r.e" class="prr-pill"
                      :aria-pressed="r.mine" :aria-label="`${r.n} reactions with ${r.e}`" @click="toggle(r)"
                    >
                      <span aria-hidden="true">{{ r.e }}</span><span class="prr-count">{{ r.n }}</span>
                    </button>
                    <div class="prr-add">
                      <button aria-label="Add reaction" :aria-expanded="open" @click="open = !open">🙂⁺</button>
                      <div v-if="open" class="prr-picker" role="menu" aria-label="Pick a reaction">
                        <button v-for="e in TRAY" :key="e" role="menuitem" :aria-label="e" @click="pick(e)">{{ e }}</button>
                      </div>
                    </div>
                  </div>
                </template>

                <style scoped>
                .prr-bar { display: flex; gap: 8px; }
                .prr-pill { display: inline-flex; align-items: center; gap: 6px; block-size: 28px; padding-inline: 10px;
                            border-radius: 2em; border: 1px solid #d1d9e0; background: transparent; font: 500 13px system-ui; }
                .prr-count { min-inline-size: 20px; padding-inline: 6px; border-radius: 2em; background: #eff2f5; font-size: 12px; }
                .prr-pill[aria-pressed='true'] { border-color: #0969da; }
                .prr-pill[aria-pressed='true'] .prr-count { background: #ddf4ff; color: #0969da; }
                </style>
                BLADE,
            'svelte' => <<<'BLADE'
                <!-- ReactionBar.svelte -->
                <script lang="ts">
                  import '@nabuxai/ui-core/css';

                  interface Rx { e: string; n: number; mine: boolean }
                  const TRAY = ['👍', '👎', '😄', '🎉', '❤️', '🚀', '👀'];
                  let rx = $state<Rx[]>([{ e: '🎉', n: 12, mine: true }, { e: '🚀', n: 8, mine: false }]);
                  let open = $state(false);
                  const toggle = (r: Rx) => { r.mine = !r.mine; r.n += r.mine ? 1 : -1; };
                  const pick = (e: string) => {
                    const hit = rx.find((x) => x.e === e);
                    if (hit) { hit.n += 1; hit.mine = true } else { rx.push({ e, n: 1, mine: true }) }
                    open = false;
                  };
                </script>

                <div class="prr-bar">
                  {#each rx.filter((x) => x.n > 0) as r (r.e)}
                    <button class="prr-pill" aria-pressed={r.mine} aria-label="{r.n} reactions with {r.e}" onclick={() => toggle(r)}>
                      <span aria-hidden="true">{r.e}</span><span class="prr-count">{r.n}</span>
                    </button>
                  {/each}
                  <div class="prr-add">
                    <button aria-label="Add reaction" aria-expanded={open} onclick={() => (open = !open)}>🙂⁺</button>
                    {#if open}
                      <div class="prr-picker" role="menu" aria-label="Pick a reaction">
                        {#each TRAY as e (e)}<button role="menuitem" aria-label={e} onclick={() => pick(e)}>{e}</button>{/each}
                      </div>
                    {/if}
                  </div>
                </div>

                <style>
                .prr-bar { display: flex; gap: 8px; }
                .prr-pill { display: inline-flex; align-items: center; gap: 6px; block-size: 28px; padding-inline: 10px;
                            border-radius: 2em; border: 1px solid #d1d9e0; background: transparent; font: 500 13px system-ui; }
                .prr-count { min-inline-size: 20px; padding-inline: 6px; border-radius: 2em; background: #eff2f5; font-size: 12px; }
                .prr-pill[aria-pressed='true'] { border-color: #0969da; }
                .prr-pill[aria-pressed='true'] .prr-count { background: #ddf4ff; color: #0969da; }
                </style>
                BLADE,
        ],
    ],

    'filter-bar' => [
        'title' => ['fa' => 'نوار فیلتر', 'en' => 'Filter Bar'],
        'icon' => 'search',
        'oneLiner' => [
            'fa' => 'نوار فیلتر با جست‌وجوی سینتکس‌محور (is:pr is:open label:bug) و دراپ‌داون‌های Label/Sort/State؛ قدرت کوئری گیت‌هاب در یک اینپوت خاکستریِ به‌ظاهر ساده.',
            'en' => 'Query-syntax search (is:pr is:open label:bug) with Label/Sort/State dropdowns. GitHub\'s search power dressed as one quiet gray input.',
        ],
        'js' => true,
        'docs' => 'https://primer.style/components/filtered-search',
        'props' => [
            ['name' => 'query', 'type' => 'input', 'default' => "'monospace-ish'", 'note' => [
                'fa' => 'اینپوت خاکستری با علامت جست‌وجو؛ کوئری به‌صورت متن خام ویرایش می‌شود.',
                'en' => 'The quiet gray input with a search glyph; the query edits as raw text.',
            ]],
            ['name' => 'menu', 'type' => 'dropdown', 'default' => "'Label · Sort · State'", 'note' => [
                'fa' => 'سه دکمهٔ منو که کلیکشان توکن متناظر را به کوئری می‌افزاید یا عوض می‌کند.',
                'en' => 'Three menu buttons; picking an option swaps in its query token.',
            ]],
            ['name' => 'count', 'type' => 'result line', 'default' => "'«n»'", 'note' => [
                'fa' => 'خط نتیجه زیر نوار، تعداد زنده را با اعداد فارسی می‌گوید.',
                'en' => 'The result line under the bar speaks the live count.',
            ]],
            ['name' => 'shortcut', 'type' => 'key', 'default' => "'/'", 'note' => [
                'fa' => 'کلید «/» فوکوس را به جست‌وجو می‌برد — عادت همیشگیِ گیت‌هاب.',
                'en' => "The '/' key jumps focus into search — the GitHub habit.",
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
            <div class="prf-bar">
                <span class="prf-glyph"><svg>…</svg></span>
                <input class="prf-input" value="is:pr is:open label:باگ" spellcheck="false">
                <details class="prf-menu">
                    <summary>Label</summary>
                    <div class="prf-list">…options…</div>
                </details>
            </div>

            <style>
            .prf-bar { display: flex; align-items: center; gap: 8px; block-size: 32px;
                       padding-inline: 8px; border: 1px solid #d1d9e0; border-radius: 6px; }
            .prf-input { flex: 1; min-inline-size: 0; border: 0; background: transparent;
                         font: 400 14px ui-monospace, monospace; color: #1f2328; }
            .prf-menu[open] .prf-list { display: grid; }
            </style>
            BLADE,
            'en' => <<<'BLADE'
            <div class="prf-bar">
                <span class="prf-glyph"><svg>…</svg></span>
                <input class="prf-input" value="is:pr is:open label:bug" spellcheck="false">
                <details class="prf-menu">
                    <summary>Label</summary>
                    <div class="prf-list">…options…</div>
                </details>
            </div>

            <style>
            .prf-bar { display: flex; align-items: center; gap: 8px; block-size: 32px;
                       padding-inline: 8px; border: 1px solid #d1d9e0; border-radius: 6px; }
            .prf-input { flex: 1; min-inline-size: 0; border: 0; background: transparent;
                         font: 400 14px ui-monospace, monospace; color: #1f2328; }
            .prf-menu[open] .prf-list { display: grid; }
            </style>
            BLADE,
            ],
            'inertia' => <<<'BLADE'
                // resources/js/Pages/Pulls/Index.tsx — the query string syncs to
                // URL params so every filtered view is a shareable link.
                import { Head, router, usePage } from '@inertiajs/react';
                import FilterBar from '@/components/primer/FilterBar';

                export default function PullsIndex() {
                  const { q } = usePage().props; // current query from the backend
                  return (
                    <>
                      <Head title="Pull requests" />
                      {/* onQueryChange={(next) => router.get('/pulls', { q: next })} */}
                      <FilterBar initialQuery={q} />
                    </>
                  );
                }
                BLADE,
            'react' => <<<'BLADE'
                import { useState } from 'react';
                import '@nabuxai/ui-core/css';

                const PRS = [
                  { n: 415, state: 'open', title: 'PDF export stalls on 50-page reports', label: 'bug' },
                  { n: 409, state: 'open', title: 'Refresh the report empty-state art', label: 'design' },
                  { n: 402, state: 'merged', title: 'Queue the heavy renders off-request', label: 'enhancement' },
                ];

                export default function FilterBar({ initialQuery = 'is:open label:bug' }: { initialQuery?: string }) {
                  const [q, setQ] = useState(initialQuery);
                  const tokens = {
                    open: /(^|\s)is:open(\s|$)/.test(q),
                    label: q.match(/label:(\S+)/)?.[1] ?? null,
                  };
                  const setToken = (key: string, v: string) =>
                    setQ((prev) =>
                      new RegExp(`${key}:\\S+`).test(prev)
                        ? prev.replace(new RegExp(`${key}:\\S+`), `${key}:${v}`)
                        : `${prev} ${key}:${v}`.trim(),
                    );
                  const rows = PRS.filter(
                    (r) => (!tokens.open || r.state === 'open') && (!tokens.label || r.label === tokens.label),
                  );

                  return (
                    <div className="prf-panel">
                      <div className="prf-line">
                        <span className="prf-glyph" aria-hidden>⌕</span>
                        <input
                          className="prf-input" value={q} onChange={(e) => setQ(e.target.value)}
                          spellCheck={false} aria-label="Search pull requests"
                        />
                        {['bug', 'design', 'enhancement'].map((l) => (
                          <button key={l} onClick={() => setToken('label', l)}>label:{l}</button>
                        ))}
                      </div>
                      <p className="prf-result"><b>{rows.length}</b> pull requests match this query</p>
                      {rows.map((r) => (
                        <a className="prf-row" href="#" key={r.n}><b>{r.title}</b> <small>#{r.n}</small></a>
                      ))}
                      <style>{`
                        .prf-line { display: flex; align-items: center; gap: 8px; block-size: 32px; padding-inline: 8px;
                                    border: 1px solid #d1d9e0; border-radius: 6px; }
                        .prf-input { flex: 1; min-inline-size: 0; border: 0; background: transparent;
                                     font: 400 14px ui-monospace, monospace; color: #1f2328; }
                      `}</style>
                    </div>
                  );
                }
                BLADE,
            'vue' => <<<'BLADE'
                <!-- FilterBar.vue -->
                <script setup lang="ts">
                import { ref, computed } from 'vue';
                import '@nabuxai/ui-core/css';

                const PRS = [
                  { n: 415, state: 'open', title: 'PDF export stalls on 50-page reports', label: 'bug' },
                  { n: 409, state: 'open', title: 'Refresh the report empty-state art', label: 'design' },
                  { n: 402, state: 'merged', title: 'Queue the heavy renders off-request', label: 'enhancement' },
                ];
                const q = ref('is:open label:bug');
                const rows = computed(() => {
                  const open = /(^|\s)is:open(\s|$)/.test(q.value);
                  const label = q.value.match(/label:(\S+)/)?.[1] ?? null;
                  return PRS.filter((r) => (!open || r.state === 'open') && (!label || r.label === label));
                });
                const setToken = (key: string, v: string) => {
                  const re = new RegExp(`${key}:\\S+`);
                  q.value = re.test(q.value) ? q.value.replace(re, `${key}:${v}`) : `${q.value} ${key}:${v}`.trim();
                };
                </script>

                <template>
                  <div class="prf-panel">
                    <div class="prf-line">
                      <span class="prf-glyph" aria-hidden="true">⌕</span>
                      <input v-model="q" class="prf-input" spellcheck="false" aria-label="Search pull requests" />
                      <button v-for="l in ['bug', 'design', 'enhancement']" :key="l" @click="setToken('label', l)">label:{{ l }}</button>
                    </div>
                    <p class="prf-result"><b>{{ rows.length }}</b> pull requests match this query</p>
                    <a v-for="r in rows" :key="r.n" class="prf-row" href="#"><b>{{ r.title }}</b> <small>#{{ r.n }}</small></a>
                  </div>
                </template>

                <style scoped>
                .prf-line { display: flex; align-items: center; gap: 8px; block-size: 32px; padding-inline: 8px;
                            border: 1px solid #d1d9e0; border-radius: 6px; }
                .prf-input { flex: 1; min-inline-size: 0; border: 0; background: transparent;
                             font: 400 14px ui-monospace, monospace; color: #1f2328; }
                </style>
                BLADE,
            'svelte' => <<<'BLADE'
                <!-- FilterBar.svelte -->
                <script lang="ts">
                  import '@nabuxai/ui-core/css';

                  const PRS = [
                    { n: 415, state: 'open', title: 'PDF export stalls on 50-page reports', label: 'bug' },
                    { n: 409, state: 'open', title: 'Refresh the report empty-state art', label: 'design' },
                    { n: 402, state: 'merged', title: 'Queue the heavy renders off-request', label: 'enhancement' },
                  ];
                  let q = $state('is:open label:bug');
                  const rows = $derived.by(() => {
                    const open = /(^|\s)is:open(\s|$)/.test(q);
                    const label = q.match(/label:(\S+)/)?.[1] ?? null;
                    return PRS.filter((r) => (!open || r.state === 'open') && (!label || r.label === label));
                  });
                  const setToken = (key: string, v: string) => {
                    const re = new RegExp(`${key}:\\S+`);
                    q = re.test(q) ? q.replace(re, `${key}:${v}`) : `${q} ${key}:${v}`.trim();
                  };
                </script>

                <div class="prf-panel">
                  <div class="prf-line">
                    <span class="prf-glyph" aria-hidden="true">⌕</span>
                    <input bind:value={q} class="prf-input" spellcheck="false" aria-label="Search pull requests" />
                    {#each ['bug', 'design', 'enhancement'] as l (l)}<button onclick={() => setToken('label', l)}>label:{l}</button>{/each}
                  </div>
                  <p class="prf-result"><b>{rows.length}</b> pull requests match this query</p>
                  {#each rows as r (r.n)}<a class="prf-row" href="#/pulls/{r.n}"><b>{r.title}</b> <small>#{r.n}</small></a>{/each}
                </div>

                <style>
                .prf-line { display: flex; align-items: center; gap: 8px; block-size: 32px; padding-inline: 8px;
                            border: 1px solid #d1d9e0; border-radius: 6px; }
                .prf-input { flex: 1; min-inline-size: 0; border: 0; background: transparent;
                             font: 400 14px ui-monospace, monospace; color: #1f2328; }
                </style>
                BLADE,
        ],
    ],

    'merge-box' => [
        'title' => ['fa' => 'جعبهٔ ادغام', 'en' => 'Merge Box'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'جعبهٔ ادغام با سه حالت Merge/Squash/Rebase، پیام کامیت پیش‌فرض و دکمهٔ سبز Merge pull request؛ لحظهٔ حقیقت هر PR که فقط گیت‌هاب این‌شکلی دارد.',
            'en' => 'The merge panel with Merge/Squash/Rebase options and the green Merge pull request button. The moment of truth of every PR, unmistakably GitHub.',
        ],
        'js' => true,
        'docs' => 'https://primer.style/components/action-menu',
        'props' => [
            ['name' => 'method', 'type' => 'radio', 'default' => "'merge'", 'note' => [
                'fa' => 'merge · squash · rebase؛ هر انتخاب، عنوان و بدنهٔ پیام کامیت را بازنویسی می‌کند.',
                'en' => 'merge · squash · rebase; each rewrite rewrites the commit title and body.',
            ]],
            ['name' => 'status', 'type' => 'state', 'default' => "'clean'", 'note' => [
                'fa' => 'بدون تعارض: تیک سبز و دکمهٔ فعال؛ تعارض: قرمز و دکمهٔ خاکستری.',
                'en' => 'Clean: a green tick and an armed button; conflicts: red and a muted button.',
            ]],
            ['name' => 'action', 'type' => 'button', 'default' => "'success · 6px'", 'note' => [
                'fa' => 'دکمهٔ سبز Merge pull request؛ پس از ادغام کل جعبه به حالت بنفش merged می‌رود.',
                'en' => 'The green Merge pull request button; after merging the whole box flips to the purple merged state.',
            ]],
            ['name' => 'commit message', 'type' => 'textarea', 'default' => "'PR title + body'", 'note' => [
                'fa' => 'پیام کامیت قابل‌ویرایش با پیش‌نمایش شمارهٔ PR (#۴۱۲).',
                'en' => 'The editable commit message with the PR number (#412) previewed.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
            <div class="prm-box">
                <header class="prm-status">
                    <svg>…</svg> This branch has no conflicts with the base branch
                </header>
                <label><input type="radio" name="prm-method" checked> Create a merge commit</label>
                <label><input type="radio" name="prm-method"> Squash and merge</label>
                <label><input type="radio" name="prm-method"> Rebase and merge</label>
                <button class="prm-go">Merge pull request</button>
            </div>

            <style>
            .prm-box { inline-size: min(100%, 30rem); padding: 16px; border: 1px solid #d1d9e0;
                       border-radius: 6px; background: #f6f8fa; }
            .prm-status { color: #1a7f37; font: 500 14px system-ui; }
            .prm-go { block-size: 32px; padding-inline: 12px; border-radius: 6px; border: 0;
                      background: #1f883d; color: #fff; font: 500 14px system-ui; }
            </style>
            BLADE,
            'en' => <<<'BLADE'
            <div class="prm-box">
                <header class="prm-status">
                    <svg>…</svg> This branch has no conflicts with the base branch
                </header>
                <label><input type="radio" name="prm-method" checked> Create a merge commit</label>
                <label><input type="radio" name="prm-method"> Squash and merge</label>
                <label><input type="radio" name="prm-method"> Rebase and merge</label>
                <button class="prm-go">Merge pull request</button>
            </div>

            <style>
            .prm-box { inline-size: min(100%, 30rem); padding: 16px; border: 1px solid #d1d9e0;
                       border-radius: 6px; background: #f6f8fa; }
            .prm-status { color: #1a7f37; font: 500 14px system-ui; }
            .prm-go { block-size: 32px; padding-inline: 12px; border-radius: 6px; border: 0;
                      background: #1f883d; color: #fff; font: 500 14px system-ui; }
            </style>
            BLADE,
            ],
            'inertia' => <<<'BLADE'
                // resources/js/Pages/Pulls/Merge.tsx — the green button PATCHes
                // the pull request; Inertia swaps in the merged state afterwards.
                import { Head, router } from '@inertiajs/react';
                import MergeBox from '@/components/primer/MergeBox';

                export default function PullMerge() {
                  return (
                    <>
                      <Head title="Merge · PR #412" />
                      {/* onMerge={(method) => router.patch('/pulls/412', { method })} */}
                      <MergeBox />
                    </>
                  );
                }
                BLADE,
            'react' => <<<'BLADE'
                import { useState } from 'react';
                import '@nabuxai/ui-core/css';

                type Method = 'merge' | 'squash' | 'rebase';
                const GO: Record<Method, string> = {
                  merge: 'Merge pull request',
                  squash: 'Confirm squash and merge',
                  rebase: 'Confirm rebase and merge',
                };

                export default function MergeBox() {
                  const [method, setMethod] = useState<Method>('merge');
                  const [merged, setMerged] = useState(false);

                  return (
                    <div className="prm-box" data-merged={merged || undefined}>
                      {merged ? (
                        <p className="prm-done"><b>Pull request successfully merged</b> — the branch is closed and deletable.</p>
                      ) : (
                        <>
                          <p className="prm-status" data-kind="clean">✓ This branch has no conflicts with the base branch</p>
                          {(Object.keys(GO) as Method[]).map((m) => (
                            <label key={m}>
                              <input type="radio" name="prm-method" checked={method === m} onChange={() => setMethod(m)} /> {GO[m]}
                            </label>
                          ))}
                          <button className="prm-go" onClick={() => setMerged(true)}>✓ {GO[method]}</button>
                        </>
                      )}
                      <style>{`
                        .prm-box { inline-size: min(100%, 30rem); padding: 16px; border: 1px solid #d1d9e0;
                                   border-radius: 6px; background: #f6f8fa; display: grid; gap: 10px; }
                        .prm-status { color: #1a7f37; font: 500 14px system-ui; margin: 0; }
                        .prm-go { block-size: 32px; padding-inline: 12px; border-radius: 6px; border: 0; justify-self: start;
                                  background: #1f883d; color: #fff; font: 500 14px system-ui; }
                        .prm-box[data-merged] { border-color: #8250DF; background: #FBF8FF; }
                      `}</style>
                    </div>
                  );
                }
                BLADE,
            'vue' => <<<'BLADE'
                <!-- MergeBox.vue -->
                <script setup lang="ts">
                import { ref } from 'vue';
                import '@nabuxai/ui-core/css';

                type Method = 'merge' | 'squash' | 'rebase';
                const GO: Record<Method, string> = {
                  merge: 'Merge pull request',
                  squash: 'Confirm squash and merge',
                  rebase: 'Confirm rebase and merge',
                };
                const method = ref<Method>('merge');
                const merged = ref(false);
                </script>

                <template>
                  <div class="prm-box" :data-merged="merged ? '' : null">
                    <template v-if="!merged">
                      <p class="prm-status" data-kind="clean">✓ This branch has no conflicts with the base branch</p>
                      <label v-for="(label, m) in GO" :key="m">
                        <input v-model="method" type="radio" name="prm-method" :value="m" /> {{ label }}
                      </label>
                      <button class="prm-go" @click="merged = true">✓ {{ GO[method] }}</button>
                    </template>
                    <p v-else class="prm-done"><b>Pull request successfully merged</b> — the branch is closed and deletable.</p>
                  </div>
                </template>

                <style scoped>
                .prm-box { inline-size: min(100%, 30rem); padding: 16px; border: 1px solid #d1d9e0;
                           border-radius: 6px; background: #f6f8fa; display: grid; gap: 10px; }
                .prm-status { color: #1a7f37; font: 500 14px system-ui; margin: 0; }
                .prm-go { block-size: 32px; padding-inline: 12px; border-radius: 6px; border: 0; justify-self: start;
                          background: #1f883d; color: #fff; font: 500 14px system-ui; }
                .prm-box[data-merged] { border-color: #8250df; background: #fbf8ff; }
                </style>
                BLADE,
            'svelte' => <<<'BLADE'
                <!-- MergeBox.svelte -->
                <script lang="ts">
                  import '@nabuxai/ui-core/css';

                  type Method = 'merge' | 'squash' | 'rebase';
                  const GO: Record<Method, string> = {
                    merge: 'Merge pull request',
                    squash: 'Confirm squash and merge',
                    rebase: 'Confirm rebase and merge',
                  };
                  let method = $state<Method>('merge');
                  let merged = $state(false);
                </script>

                <div class="prm-box" data-merged={merged || undefined}>
                  {#if !merged}
                    <p class="prm-status" data-kind="clean">✓ This branch has no conflicts with the base branch</p>
                    {#each Object.keys(GO) as m (m)}
                      <label>
                        <input type="radio" name="prm-method" checked={method === m} onchange={() => (method = m as Method)} /> {GO[m as Method]}
                      </label>
                    {/each}
                    <button class="prm-go" onclick={() => (merged = true)}>✓ {GO[method]}</button>
                  {:else}
                    <p class="prm-done"><b>Pull request successfully merged</b> — the branch is closed and deletable.</p>
                  {/if}
                </div>

                <style>
                .prm-box { inline-size: min(100%, 30rem); padding: 16px; border: 1px solid #d1d9e0;
                           border-radius: 6px; background: #f6f8fa; display: grid; gap: 10px; }
                .prm-status { color: #1a7f37; font: 500 14px system-ui; margin: 0; }
                .prm-go { block-size: 32px; padding-inline: 12px; border-radius: 6px; border: 0; justify-self: start;
                          background: #1f883d; color: #fff; font: 500 14px system-ui; }
                .prm-box[data-merged] { border-color: #8250df; background: #fbf8ff; }
                </style>
                BLADE,
        ],
    ],

    'underline-nav' => [
        'title' => ['fa' => 'ناوبری زیرخط‌دار', 'en' => 'Underline Nav'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'تب‌های زیرخط‌دار Conversation/Commits/Checks/Files changed با شمارنده‌های زندهٔ CounterLabel؛ زیرناوبری امضای گیت‌هاب که نسخهٔ کاملاً سیستمیِ تب معمولی است.',
            'en' => 'Conversation/Commits/Checks/Files changed underlined tabs with live CounterLabels. GitHub\'s signature sub-navigation for issues and PRs.',
        ],
        'js' => true,
        'docs' => 'https://primer.style/components/underline-nav',
        'props' => [
            ['name' => 'selected', 'type' => 'underline', 'default' => "'2px orange?'", 'note' => [
                'fa' => 'زیرخط ۲ پیکسلی روی تب انتخاب‌شده؛ رنگ پیش‌فرض fg-color، در هاور پنجرهٔ گرد خاکستری.',
                'en' => 'The 2px underline on the selected tab; default fg-colour, with a soft gray rounded hover window.',
            ]],
            ['name' => 'counter', 'type' => 'CounterLabel', 'default' => "'rounded 2em'", 'note' => [
                'fa' => 'شمارندهٔ گرد در انتهای هر تب؛ انتخاب‌شده پررنگ‌تر دیده می‌شود.',
                'en' => 'The rounded counter at each tab end; the selected one reads bolder.',
            ]],
            ['name' => 'overflow', 'type' => 'scroll', 'default' => "'inline'", 'note' => [
                'fa' => 'روی موبایل تب‌ها افقی می‌لغزند؛ هیچ‌کس نمی‌شکند.',
                'en' => 'On mobile the tabs glide inline; nothing wraps away.',
            ]],
            ['name' => 'icon', 'type' => 'octicon · 16px', 'default' => "'optional'", 'note' => [
                'fa' => 'هر تب می‌تواند آیکن ۱۶ پیکسلی پیش از برچسب داشته باشد.',
                'en' => 'Each tab may carry a 16px octicon before its label.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
            <nav class="prn-nav" aria-label="صفحهٔ PR">
                <button class="prn-tab" aria-current="page">
                    <svg>…</svg> Conversation <span class="prn-count">۴</span>
                </button>
                <button class="prn-tab">
                    <svg>…</svg> Commits <span class="prn-count">۷</span>
                </button>
            </nav>

            <style>
            .prn-nav { display: flex; gap: 8px; border-block-end: 1px solid #d1d9e0;
                       overflow-x: auto; }
            .prn-tab { display: inline-flex; align-items: center; gap: 8px; padding: 8px;
                       border: 0; border-block-end: 2px solid transparent; background: none;
                       font: 500 14px system-ui; }
            .prn-tab[aria-current='page'] { border-block-end-color: #1f2328; }
            .prn-count { min-inline-size: 20px; padding-inline: 6px; border-radius: 2em;
                         background: #eff2f5; font: 500 12px/20px system-ui; }
            </style>
            BLADE,
            'en' => <<<'BLADE'
            <nav class="prn-nav" aria-label="Pull request page">
                <button class="prn-tab" aria-current="page">
                    <svg>…</svg> Conversation <span class="prn-count">4</span>
                </button>
                <button class="prn-tab">
                    <svg>…</svg> Commits <span class="prn-count">7</span>
                </button>
            </nav>

            <style>
            .prn-nav { display: flex; gap: 8px; border-block-end: 1px solid #d1d9e0;
                       overflow-x: auto; }
            .prn-tab { display: inline-flex; align-items: center; gap: 8px; padding: 8px;
                       border: 0; border-block-end: 2px solid transparent; background: none;
                       font: 500 14px system-ui; }
            .prn-tab[aria-current='page'] { border-block-end-color: #1f2328; }
            .prn-count { min-inline-size: 20px; padding-inline: 6px; border-radius: 2em;
                         background: #eff2f5; font: 500 12px/20px system-ui; }
            </style>
            BLADE,
            ],
            'inertia' => <<<'BLADE'
                // resources/js/Pages/Pulls/Show.tsx — tab changes can sync to the
                // URL hash; the tabbed sub-nav is the React snippet.
                import { Head } from '@inertiajs/react';
                import UnderlineNav from '@/components/primer/UnderlineNav';

                export default function PullShow() {
                  return (
                    <>
                      <Head title="PR #412" />
                      <UnderlineNav />
                    </>
                  );
                }
                BLADE,
            'react' => <<<'BLADE'
                import { useState } from 'react';
                import '@nabuxai/ui-core/css';

                const TABS = [
                  { id: 'conversation', label: 'Conversation', count: 4 },
                  { id: 'commits', label: 'Commits', count: 7 },
                  { id: 'checks', label: 'Checks', count: 3 },
                  { id: 'files', label: 'Files changed', count: 12 },
                ];

                export default function UnderlineNav() {
                  const [tab, setTab] = useState('conversation');
                  const active = TABS.find((t) => t.id === tab)!;

                  return (
                    <div className="prn-page">
                      <nav className="prn-nav" role="tablist" aria-label="Pull request sections">
                        {TABS.map((t) => (
                          <button
                            key={t.id} className="prn-tab" role="tab"
                            aria-current={tab === t.id ? 'page' : undefined} onClick={() => setTab(t.id)}
                          >
                            {t.label} <span className="prn-count">{t.count}</span>
                          </button>
                        ))}
                      </nav>
                      <div className="prn-panel" role="tabpanel">{active.count} items on the {active.label.toLowerCase()} tab.</div>
                      <style>{`
                        .prn-nav { display: flex; gap: 8px; border-block-end: 1px solid #d1d9e0; overflow-x: auto; }
                        .prn-tab { display: inline-flex; align-items: center; gap: 8px; padding: 8px; border: 0;
                                   border-block-end: 2px solid transparent; background: none; font: 500 14px system-ui; }
                        .prn-tab[aria-current='page'] { border-block-end-color: #1f2328; }
                        .prn-count { min-inline-size: 20px; padding-inline: 6px; border-radius: 2em;
                                     background: #eff2f5; font: 500 12px/20px system-ui; }
                      `}</style>
                    </div>
                  );
                }
                BLADE,
            'vue' => <<<'BLADE'
                <!-- UnderlineNav.vue -->
                <script setup lang="ts">
                import { ref } from 'vue';
                import '@nabuxai/ui-core/css';

                const TABS = [
                  { id: 'conversation', label: 'Conversation', count: 4 },
                  { id: 'commits', label: 'Commits', count: 7 },
                  { id: 'checks', label: 'Checks', count: 3 },
                  { id: 'files', label: 'Files changed', count: 12 },
                ];
                const tab = ref('conversation');
                </script>

                <template>
                  <div class="prn-page">
                    <nav class="prn-nav" role="tablist" aria-label="Pull request sections">
                      <button
                        v-for="t in TABS" :key="t.id" class="prn-tab" role="tab"
                        :aria-current="tab === t.id ? 'page' : null" @click="tab = t.id"
                      >
                        {{ t.label }} <span class="prn-count">{{ t.count }}</span>
                      </button>
                    </nav>
                    <div class="prn-panel" role="tabpanel">
                      {{ TABS.find((t) => t.id === tab)?.count }} items on the {{ tab }} tab.
                    </div>
                  </div>
                </template>

                <style scoped>
                .prn-nav { display: flex; gap: 8px; border-block-end: 1px solid #d1d9e0; overflow-x: auto; }
                .prn-tab { display: inline-flex; align-items: center; gap: 8px; padding: 8px; border: 0;
                           border-block-end: 2px solid transparent; background: none; font: 500 14px system-ui; }
                .prn-tab[aria-current='page'] { border-block-end-color: #1f2328; }
                .prn-count { min-inline-size: 20px; padding-inline: 6px; border-radius: 2em;
                             background: #eff2f5; font: 500 12px/20px system-ui; }
                </style>
                BLADE,
            'svelte' => <<<'BLADE'
                <!-- UnderlineNav.svelte -->
                <script lang="ts">
                  import '@nabuxai/ui-core/css';

                  const TABS = [
                    { id: 'conversation', label: 'Conversation', count: 4 },
                    { id: 'commits', label: 'Commits', count: 7 },
                    { id: 'checks', label: 'Checks', count: 3 },
                    { id: 'files', label: 'Files changed', count: 12 },
                  ];
                  let tab = $state('conversation');
                  const active = $derived(TABS.find((t) => t.id === tab)!);
                </script>

                <div class="prn-page">
                  <nav class="prn-nav" role="tablist" aria-label="Pull request sections">
                    {#each TABS as t (t.id)}
                      <button
                        class="prn-tab" role="tab"
                        aria-current={tab === t.id ? 'page' : undefined} onclick={() => (tab = t.id)}
                      >
                        {t.label} <span class="prn-count">{t.count}</span>
                      </button>
                    {/each}
                  </nav>
                  <div class="prn-panel" role="tabpanel">{active.count} items on the {active.label.toLowerCase()} tab.</div>
                </div>

                <style>
                .prn-nav { display: flex; gap: 8px; border-block-end: 1px solid #d1d9e0; overflow-x: auto; }
                .prn-tab { display: inline-flex; align-items: center; gap: 8px; padding: 8px; border: 0;
                           border-block-end: 2px solid transparent; background: none; font: 500 14px system-ui; }
                .prn-tab[aria-current='page'] { border-block-end-color: #1f2328; }
                .prn-count { min-inline-size: 20px; padding-inline: 6px; border-radius: 2em;
                             background: #eff2f5; font: 500 12px/20px system-ui; }
                </style>
                BLADE,
        ],
    ],

    'actions-runs' => [
        'title' => ['fa' => 'ران‌های اکشنز', 'en' => 'Actions Runs'],
        'icon' => 'zap',
        'oneLiner' => [
            'fa' => 'فهرست ران‌های CI با اسپینر زرد، تیک سبز، ضربدر قرمز و متای branch+SHA+زمان؛ صفحهٔ Actions که سلامت کد را در یک نگاه لو می‌دهد.',
            'en' => 'CI run list with yellow spinners, green checks, red X marks and branch+SHA+duration meta. The Actions page that shows repo health at a glance.',
        ],
        'js' => true,
        'docs' => 'https://primer.style/components/action-list',
        'props' => [
            ['name' => 'status icon', 'type' => '16px', 'default' => "'spinner · ✓ · ✕'", 'note' => [
                'fa' => 'اسپینر زرد attention برای در جریان، تیک سبز برای موفق، ضربدر قرمز برای شکست، دایرهٔ خاکستری برای skip.',
                'en' => 'The yellow attention spinner for in-progress, green check for success, red X for failure, gray circle for skipped.',
            ]],
            ['name' => 'title row', 'type' => 'workflow · run', 'default' => "'bold + muted'", 'note' => [
                'fa' => 'نام ورک‌فلو پررنگ و پیام کامیت خاکستری زیر آن.',
                'en' => 'The workflow name bold, the commit message gray beneath.',
            ]],
            ['name' => 'meta', 'type' => 'branch · sha · time', 'default' => "'12px'", 'note' => [
                'fa' => 'متا: شاخه، هفت‌رقمیِ SHA و مدت اجرا با فونت ثابت.',
                'en' => 'Meta: branch, the 7-char SHA and the run duration in mono.',
            ]],
            ['name' => 'filter', 'type' => 'segmented', 'default' => "'All · ✓ · ✕'", 'note' => [
                'fa' => 'فیلتر وضعیت بالای فهرست که ردیف‌ها را زنده می‌کاهد.',
                'en' => 'The status filter above the list that live-trims the rows.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
            <a class="pra-run" href="#">
                <span class="pra-status" data-run="running"><svg class="pra-spin">…</svg></span>
                <span class="pra-body">
                    <span class="pra-name">CI / build و تست</span>
                    <span class="pra-msg">fix: نشت حافظهٔ کش</span>
                    <span class="pra-meta">main · <code>a1b2c3d</code> · ۳ دقیقه</span>
                </span>
            </a>

            <style>
            .pra-run { display: flex; gap: 12px; padding: 12px 16px;
                       border-block-end: 1px solid #d1d9e0; }
            .pra-status[data-run='running'] { color: #9a6700; }
            .pra-status[data-run='success'] { color: #1a7f37; }
            .pra-status[data-run='failure'] { color: #cf222e; }
            .pra-spin { animation: pra-spin 1s linear infinite; }
            @keyframes pra-spin { to { rotate: 360deg; } }
            </style>
            BLADE,
            'en' => <<<'BLADE'
            <a class="pra-run" href="#">
                <span class="pra-status" data-run="running"><svg class="pra-spin">…</svg></span>
                <span class="pra-body">
                    <span class="pra-name">CI / build and test</span>
                    <span class="pra-msg">fix: cache leak in the report queue</span>
                    <span class="pra-meta">main · <code>a1b2c3d</code> · 3 min</span>
                </span>
            </a>

            <style>
            .pra-run { display: flex; gap: 12px; padding: 12px 16px;
                       border-block-end: 1px solid #d1d9e0; }
            .pra-status[data-run='running'] { color: #9a6700; }
            .pra-status[data-run='success'] { color: #1a7f37; }
            .pra-status[data-run='failure'] { color: #cf222e; }
            .pra-spin { animation: pra-spin 1s linear infinite; }
            @keyframes pra-spin { to { rotate: 360deg; } }
            </style>
            BLADE,
            ],
            'inertia' => <<<'BLADE'
                // resources/js/Pages/Actions/Index.tsx — Inertia revalidates the
                // runs list on an interval; the filter is client-side.
                import { Head } from '@inertiajs/react';
                import ActionsRuns from '@/components/primer/ActionsRuns';

                export default function ActionsIndex() {
                  return (
                    <>
                      <Head title="Actions" />
                      <ActionsRuns />
                    </>
                  );
                }
                BLADE,
            'react' => <<<'BLADE'
                import { useState } from 'react';
                import '@nabuxai/ui-core/css';

                type Run = { id: number; state: 'running' | 'success' | 'failure'; flow: string; msg: string; branch: string; sha: string; took: string };
                const RUNS: Run[] = [
                  { id: 812, state: 'running', flow: 'CI / build and test', msg: 'fix: cache leak in the report queue', branch: 'main', sha: 'a1b2c3d', took: '1:12' },
                  { id: 811, state: 'success', flow: 'CI / build and test', msg: 'feat: PDF export behind a flag', branch: 'feature/pdf', sha: '9c8d7e6', took: '3 min 8 sec' },
                  { id: 810, state: 'failure', flow: 'e2e / reports', msg: 'fix: flaky date assertion', branch: 'main', sha: 'e4f5a6b', took: '5 min 2 sec' },
                ];
                const ICON: Record<Run['state'], string> = { running: '◌', success: '✓', failure: '✕' };

                export default function ActionsRuns() {
                  const [filter, setFilter] = useState<'all' | Run['state']>('all');
                  const rows = RUNS.filter((r) => filter === 'all' || r.state === filter);

                  return (
                    <div className="pra-panel">
                      <div className="pra-seg" role="group" aria-label="Filter runs by status">
                        {(['all', 'success', 'failure', 'running'] as const).map((f) => (
                          <button key={f} aria-pressed={filter === f} onClick={() => setFilter(f)}>{f}</button>
                        ))}
                      </div>
                      <div className="pra-list">
                        {rows.map((r) => (
                          <a className="pra-run" href="#" key={r.id}>
                            <span className="pra-status" data-run={r.state}>{ICON[r.state]}</span>
                            <span className="pra-body">
                              <span className="pra-name">{r.flow}</span>
                              <span className="pra-msg">{r.msg}</span>
                              <span className="pra-meta">{r.branch} · <code>{r.sha}</code> · {r.took}</span>
                            </span>
                          </a>
                        ))}
                      </div>
                      <style>{`
                        .pra-seg { display: flex; gap: 8px; padding-block-end: 8px; }
                        .pra-run { display: flex; gap: 12px; padding: 12px 16px; border-block-end: 1px solid #d1d9e0; }
                        .pra-status[data-run='running'] { color: #9a6700; }
                        .pra-status[data-run='success'] { color: #1a7f37; }
                        .pra-status[data-run='failure'] { color: #cf222e; }
                      `}</style>
                    </div>
                  );
                }
                BLADE,
            'vue' => <<<'BLADE'
                <!-- ActionsRuns.vue -->
                <script setup lang="ts">
                import { ref, computed } from 'vue';
                import '@nabuxai/ui-core/css';

                interface Run { id: number; state: 'running' | 'success' | 'failure'; flow: string; msg: string; branch: string; sha: string; took: string }
                const RUNS: Run[] = [
                  { id: 812, state: 'running', flow: 'CI / build and test', msg: 'fix: cache leak in the report queue', branch: 'main', sha: 'a1b2c3d', took: '1:12' },
                  { id: 811, state: 'success', flow: 'CI / build and test', msg: 'feat: PDF export behind a flag', branch: 'feature/pdf', sha: '9c8d7e6', took: '3 min 8 sec' },
                  { id: 810, state: 'failure', flow: 'e2e / reports', msg: 'fix: flaky date assertion', branch: 'main', sha: 'e4f5a6b', took: '5 min 2 sec' },
                ];
                const ICON: Record<Run['state'], string> = { running: '◌', success: '✓', failure: '✕' };
                const filter = ref<'all' | Run['state']>('all');
                const rows = computed(() => RUNS.filter((r) => filter.value === 'all' || r.state === filter.value));
                </script>

                <template>
                  <div class="pra-panel">
                    <div class="pra-seg" role="group" aria-label="Filter runs by status">
                      <button v-for="f in ['all', 'success', 'failure', 'running']" :key="f" :aria-pressed="filter === f" @click="filter = f">{{ f }}</button>
                    </div>
                    <div class="pra-list">
                      <a v-for="r in rows" :key="r.id" class="pra-run" href="#">
                        <span class="pra-status" :data-run="r.state">{{ ICON[r.state] }}</span>
                        <span class="pra-body">
                          <span class="pra-name">{{ r.flow }}</span>
                          <span class="pra-msg">{{ r.msg }}</span>
                          <span class="pra-meta">{{ r.branch }} · <code>{{ r.sha }}</code> · {{ r.took }}</span>
                        </span>
                      </a>
                    </div>
                  </div>
                </template>

                <style scoped>
                .pra-seg { display: flex; gap: 8px; padding-block-end: 8px; }
                .pra-run { display: flex; gap: 12px; padding: 12px 16px; border-block-end: 1px solid #d1d9e0; }
                .pra-status[data-run='running'] { color: #9a6700; }
                .pra-status[data-run='success'] { color: #1a7f37; }
                .pra-status[data-run='failure'] { color: #cf222e; }
                </style>
                BLADE,
            'svelte' => <<<'BLADE'
                <!-- ActionsRuns.svelte -->
                <script lang="ts">
                  import '@nabuxai/ui-core/css';

                  interface Run { id: number; state: 'running' | 'success' | 'failure'; flow: string; msg: string; branch: string; sha: string; took: string }
                  const RUNS: Run[] = [
                    { id: 812, state: 'running', flow: 'CI / build and test', msg: 'fix: cache leak in the report queue', branch: 'main', sha: 'a1b2c3d', took: '1:12' },
                    { id: 811, state: 'success', flow: 'CI / build and test', msg: 'feat: PDF export behind a flag', branch: 'feature/pdf', sha: '9c8d7e6', took: '3 min 8 sec' },
                    { id: 810, state: 'failure', flow: 'e2e / reports', msg: 'fix: flaky date assertion', branch: 'main', sha: 'e4f5a6b', took: '5 min 2 sec' },
                  ];
                  const ICON: Record<Run['state'], string> = { running: '◌', success: '✓', failure: '✕' };
                  let filter = $state<'all' | Run['state']>('all');
                  const rows = $derived(RUNS.filter((r) => filter === 'all' || r.state === filter));
                </script>

                <div class="pra-panel">
                  <div class="pra-seg" role="group" aria-label="Filter runs by status">
                    {#each ['all', 'success', 'failure', 'running'] as f (f)}
                      <button aria-pressed={filter === f} onclick={() => (filter = f as Run['state'] | 'all')}>{f}</button>
                    {/each}
                  </div>
                  <div class="pra-list">
                    {#each rows as r (r.id)}
                      <a class="pra-run" href="#/actions/{r.id}">
                        <span class="pra-status" data-run={r.state}>{ICON[r.state]}</span>
                        <span class="pra-body">
                          <span class="pra-name">{r.flow}</span>
                          <span class="pra-msg">{r.msg}</span>
                          <span class="pra-meta">{r.branch} · <code>{r.sha}</code> · {r.took}</span>
                        </span>
                      </a>
                    {/each}
                  </div>
                </div>

                <style>
                .pra-seg { display: flex; gap: 8px; padding-block-end: 8px; }
                .pra-run { display: flex; gap: 12px; padding: 12px 16px; border-block-end: 1px solid #d1d9e0; }
                .pra-status[data-run='running'] { color: #9a6700; }
                .pra-status[data-run='success'] { color: #1a7f37; }
                .pra-status[data-run='failure'] { color: #cf222e; }
                </style>
                BLADE,
        ],
    ],
];
