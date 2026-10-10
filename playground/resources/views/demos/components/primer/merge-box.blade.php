{{--
    The moment of truth: merge method radios rewrite the commit message,
    the green button confirms, and the whole box flips to the purple merged
    verdict — with the clean / conflicts / merged variants beside it.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (int|string $n): string => $fa ? strtr((string) $n, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : (string) $n;
    $oct = function (string $name): string {
        $p = [
            'check' => '<path d="M13.78 4.22a.75.75 0 0 1 0 1.06l-7.25 7.25a.75.75 0 0 1-1.06 0L2.22 9.28a.751.751 0 0 1 .018-1.042.751.751 0 0 1 1.042-.018L6 10.94l6.72-6.72a.75.75 0 0 1 1.06 0Z"/>',
            'x' => '<path d="M3.72 3.72a.75.75 0 0 1 1.06 0L8 6.94l3.22-3.22a.749.749 0 0 1 1.275.326.749.749 0 0 1-.215.734L9.06 8l3.22 3.22a.749.749 0 0 1-.326 1.275.749.749 0 0 1-.734-.215L8 9.06l-3.22 3.22a.751.751 0 0 1-1.042-.018.751.751 0 0 1-.018-1.042L6.94 8 3.72 4.78a.75.75 0 0 1 0-1.06Z"/>',
            'merge' => '<path fill-rule="evenodd" d="M5.45 5.154A4.25 4.25 0 0 0 9.25 7.5h1.378a2.251 2.251 0 1 1 0 1.5H9.25A5.734 5.734 0 0 1 5 7.123v3.505a2.25 2.25 0 1 1-1.5 0V5.372a2.25 2.25 0 1 1 1.95-.218ZM4.25 13.5a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm8.5-4.5a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM5 3.25a.75.75 0 1 0-1.5 0 .75.75 0 0 0 1.5 0Z"/>',
            'alert' => '<path d="M6.457 1.047c.659-1.234 2.427-1.234 3.086 0l6.082 11.146c.425.78-.058 1.701-.844 1.701H4.42a1.193 1.193 0 0 1-.844-1.701Zm.682 3.453a.75.75 0 0 1 1.5 0v3.5a.75.75 0 0 1-1.5 0ZM9 11a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"/>',
        ];
        return '<svg aria-hidden="true" viewBox="0 0 16 16" width="16" height="16" fill="currentColor" style="display:block">' . ($p[$name] ?? '') . '</svg>';
    };
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap');
    .prm-root {
        --prm-canvas: #ffffff; --prm-subtle: #f6f8fa; --prm-fg: #1f2328; --prm-muted: #59636e;
        --prm-border: #d1d9e0; --prm-accent: #0969da;
        --prm-success: #1a7f37; --prm-success-btn: #1f883d; --prm-done: #8250df; --prm-danger: #cf222e;
        font-family: 'Figtree', 'Inter', 'Vazirmatn', sans-serif;
        color: var(--prm-fg);
    }
    html[data-theme="dark"] .prm-root {
        --prm-canvas: #0d1117; --prm-subtle: #151b23; --prm-fg: #f0f6fc; --prm-muted: #9198a1;
        --prm-border: #3d444d; --prm-accent: #4493f8;
        --prm-success: #3fb950; --prm-success-btn: #238636; --prm-done: #ab7df8; --prm-danger: #f85149;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .prm-root {
            --prm-canvas: #0d1117; --prm-subtle: #151b23; --prm-fg: #f0f6fc; --prm-muted: #9198a1;
            --prm-border: #3d444d; --prm-accent: #4493f8;
            --prm-success: #3fb950; --prm-success-btn: #238636; --prm-done: #ab7df8; --prm-danger: #f85149;
        }
    }
    .prm-root :focus-visible { outline: 2px solid var(--prm-accent); outline-offset: 2px; border-radius: 6px; }

    .prm-box { inline-size: min(100%, 32rem); margin-inline: auto; padding: 1rem; border: 1px solid var(--prm-border); border-radius: 6px; background: var(--prm-subtle); display: grid; gap: .75rem; }
    .prm-status { display: flex; align-items: center; gap: .5rem; padding-bottom: .25rem; font: 500 .8125rem/1.5 inherit; }
    .prm-status[data-kind="clean"] { color: var(--prm-success); }
    .prm-status[data-kind="conflict"] { color: var(--prm-danger); }
    .prm-status time { color: var(--prm-muted); font-weight: 400; }
    .prm-method { display: grid; gap: .45rem; }
    .prm-opt { display: grid; grid-template-columns: auto 1fr; gap: .1rem .55rem; align-items: baseline; padding: .45rem .6rem; border: 1px solid var(--prm-border); border-radius: 6px; background: var(--prm-canvas); cursor: pointer; }
    .prm-opt:hover { border-color: color-mix(in srgb, var(--prm-border) 60%, var(--prm-fg)); }
    .prm-opt:has(input:checked) { border-color: var(--prm-accent); }
    .prm-opt input { accent-color: var(--prm-accent); translate: 0 2px; }
    .prm-opt b { font: 600 .8125rem/1.4 inherit; }
    .prm-opt small { grid-column: 2; font: 400 .71875rem/1.45 inherit; color: var(--prm-muted); }
    .prm-msg { display: grid; gap: .35rem; }
    .prm-msg input, .prm-msg textarea { inline-size: 100%; padding: .45rem .6rem; border: 1px solid var(--prm-border); border-radius: 6px; background: var(--prm-canvas); color: var(--prm-fg); font: 400 .8125rem/1.55 inherit; }
    .prm-msg textarea { min-block-size: 4.5rem; resize: vertical; }
    .prm-msg small { font: 400 .6875rem/1.4 inherit; color: var(--prm-muted); }
    .prm-actions { display: flex; flex-wrap: wrap; gap: .5rem; align-items: center; justify-content: space-between; }
    .prm-go { display: inline-flex; align-items: center; gap: .45rem; block-size: 2rem; padding-inline: .9rem; border: 0; border-radius: 6px; background: var(--prm-success-btn); color: #fff; font: 500 .8125rem/1 inherit; cursor: pointer; white-space: nowrap; }
    .prm-go:hover { filter: brightness(1.06); }
    .prm-go:disabled { opacity: .55; cursor: not-allowed; }
    .prm-ghost { display: inline-flex; align-items: center; gap: .35rem; block-size: 2rem; padding-inline: .8rem; border: 1px solid var(--prm-border); border-radius: 6px; background: none; color: var(--prm-fg); font: 500 .78rem/1 inherit; cursor: pointer; }
    .prm-ghost:hover { background: color-mix(in srgb, var(--prm-border) 35%, transparent); }
    .prm-done { display: flex; flex-wrap: wrap; align-items: center; gap: .55rem; padding: .65rem .75rem; border: 1px solid color-mix(in srgb, var(--prm-done) 40%, transparent); border-radius: 6px; background: color-mix(in srgb, var(--prm-done) 9%, var(--prm-canvas)); }
    .prm-done .prm-ico { display: grid; place-items: center; inline-size: 1.75rem; aspect-ratio: 1; border-radius: 50%; background: var(--prm-done); color: #fff; flex: none; }
    .prm-done p { margin: 0; font: 400 .8125rem/1.5 inherit; }
    .prm-done b { font-weight: 600; }
    .prm-strip { display: grid; grid-template-columns: repeat(auto-fit, minmax(15rem, 1fr)); gap: 1rem; inline-size: min(100%, 46rem); margin-inline: auto; }
    .prm-cell { display: grid; gap: .6rem; align-content: start; padding: 1rem; border: 1px solid var(--prm-border); border-radius: 6px; background: var(--prm-subtle); }
    .prm-cell > small { font: 500 .6875rem/1.4 inherit; color: var(--nx-text-muted); }
    .prm-cell .prm-opt { cursor: default; }
    /* The shared demo template hides the props table's rows until it scrolls
       into view (data-nx-reveal on x-nx::data-table); a capture that never
       scrolls records an empty table. Pin this page's rows visible. */
    html:has(.prm-root) .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr { opacity: 1; translate: none; }
    @media (max-width: 480px) {
        .prm-actions .prm-go { flex: 1 1 100%; }
    }
    @media (prefers-reduced-motion: reduce) {
        .prm-root * { transition-duration: .01ms !important; }
    }
</style>

<div class="prm-root" x-data="{
        rtl: document.documentElement.dir === 'rtl',
        method: 'merge',
        merged: false,
        num: {{ $fa ? 'true' : 'false' }},
        texts: {
            merge: {
                go: { en: 'Merge pull request', fa: 'ادغام پول‌ریکوئست' },
                title: { en: 'feat: PDF export for monthly reports (#412)', fa: 'قابلیت: خروجی PDF برای گزارش‌های ماهانه (#۴۱۲)' },
                body: { en: 'Merge branch \'main\' into feature/pdf\n\nAdds the queued PDF renderer and wires the download button.', fa: 'ادغام شاخهٔ main در feature/pdf\n\nموتور PDF صف‌شده اضافه شد و دکمهٔ دانلود وصل شد.' },
            },
            squash: {
                go: { en: 'Confirm squash and merge', fa: 'تأیید فشرده‌سازی و ادغام' },
                title: { en: 'feat: PDF export for monthly reports (#412)', fa: 'قابلیت: خروجی PDF برای گزارش‌های ماهانه (#۴۱۲)' },
                body: { en: '* pick the PDF renderer\n* stream large reports instead of buffering\n* wire the download button to the job queue', fa: '* انتخاب موتور رندر PDF\n* استریم گزارش‌های بزرگ به‌جای بافر\n* اتصال دکمهٔ دانلود به صف کارها' },
            },
            rebase: {
                go: { en: 'Confirm rebase and merge', fa: 'تأیید ری‌بیس و ادغام' },
                title: { en: 'feat: PDF export for monthly reports', fa: 'قابلیت: خروجی PDF برای گزارش‌های ماهانه' },
                body: { en: '', fa: '' },
            },
        },
        title: '', body: '',
        init() { this.pick('merge') },
        pick(m) {
            this.method = m;
            const t = this.texts[m];
            this.title = this.rtl ? t.title.fa : t.title.en;
            this.body = this.rtl ? t.body.fa : t.body.en;
        },
        goText() { const t = this.texts[this.method].go; return this.rtl ? t.fa : t.en },
        numify(s) { return this.num ? s.replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]) : s },
    }">
    <section class="pg-box" style="gap: 1.25rem">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Green means go', 'سبز یعنی برو') }}</h3>
            <p style="margin: 0; max-width: 48ch; color: var(--nx-text-muted)">
                {{ $say('Pick a method — the commit message rewrites itself the GitHub way. Then press the green button and watch the box settle into its purple, final verdict.', 'روش را انتخاب کنید — پیام کامیت همان‌طور که گیت‌هاب می‌خواهد بازنویسی می‌شود. بعد دکمهٔ سبز را بزنید و ببینید جعبه در حکم بنفش و نهایی‌اش می‌نشیند.') }}
            </p>
        </div>

        <div class="prm-box">
            <template x-if="! merged">
                <div style="display: grid; gap: .75rem">
                    <p class="prm-status" data-kind="clean" style="margin: 0">
                        {!! $oct('check') !!}
                        <span>{{ $say('This branch has no conflicts with the base branch', 'این شاخه هیچ تعارضی با شاخهٔ پایه ندارد') }}</span>
                    </p>

                    <div class="prm-method" role="radiogroup" aria-label="{{ $say('Merge method', 'روش ادغام') }}">
                        <label class="prm-opt">
                            <input type="radio" name="prm-method" value="merge" :checked="method === 'merge'" x-on:click="pick('merge')">
                            <b>{{ $say('Create a merge commit', 'ساخت کامیت ادغام') }}</b>
                            <small>{{ $say('All commits from feature/pdf plus one merge commit — history stays verbatim.', 'همهٔ کامیت‌های feature/pdf به‌هم با یک کامیت ادغام — تاریخچه سر به سر می‌ماند.') }}</small>
                        </label>
                        <label class="prm-opt">
                            <input type="radio" name="prm-method" value="squash" :checked="method === 'squash'" x-on:click="pick('squash')">
                            <b>{{ $say('Squash and merge', 'فشرده‌سازی و ادغام') }}</b>
                            <small>{{ $say('The 3 commits collapse into one tidy commit on main.', '۳ کامیت در یک کامیت تمیز روی main فرو می‌ریزند.') }}</small>
                        </label>
                        <label class="prm-opt">
                            <input type="radio" name="prm-method" value="rebase" :checked="method === 'rebase'" x-on:click="pick('rebase')">
                            <b>{{ $say('Rebase and merge', 'ری‌بیس و ادغام') }}</b>
                            <small>{{ $say('Each commit replays on top of main — no merge commit at all.', 'هر کامیت روی main بازاجرا می‌شود — اصلاً کامیت ادغام نداریم.') }}</small>
                        </label>
                    </div>

                    <div class="prm-msg">
                        <input type="text" x-model="title" aria-label="{{ $say('Commit title', 'عنوان کامیت') }}">
                        <textarea x-model="body" aria-label="{{ $say('Commit description', 'توضیح کامیت') }}" placeholder="{{ $say('optional description', 'توضیح دلخواه') }}"></textarea>
                        <small x-text="numify('#412') + (rtl ? ' به‌عنوان شمارهٔ PR در پیام می‌ماند.' : ' stays in the message as the PR number.')"></small>
                    </div>

                    <div class="prm-actions">
                        <button type="button" class="prm-ghost" x-on:click="merged = true; pick(method)">{!! $oct('merge') !!} {{ $say('Merge without confirming', 'ادغام بدون تأیید') }}</button>
                        <button type="button" class="prm-go" x-on:click="merged = true">{!! $oct('check') !!} <span x-text="goText()"></span></button>
                    </div>
                </div>
            </template>

            <template x-if="merged">
                <div style="display: grid; gap: .75rem">
                    <div class="prm-done">
                        <span class="prm-ico">{!! $oct('merge') !!}</span>
                        <p><b>{{ $say('Pull request successfully merged', 'پول‌ریکوئست با موفقیت ادغام شد') }}</b> — {{ $say('the branch is closed and deletable.', 'شاخه بسته شد و قابل حذف است.') }}</p>
                    </div>
                    <div class="prm-actions">
                        <p style="margin: 0; font: 400 .71875rem/1.5 inherit; color: var(--prm-muted)">
                            {{ $say('Method:', 'روش:') }} <code style="font: 500 .75rem ui-monospace, monospace" x-text="method"></code>
                        </p>
                        <button type="button" class="prm-ghost" x-on:click="merged = false; pick('merge')">{{ $say('Replay the moment', 'بازپخش لحظه') }}</button>
                    </div>
                </div>
            </template>
        </div>
    </section>
</div>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The three verdicts of the box', 'سه حکمِ جعبه') }}</h3>
        <p style="margin: 0; max-width: 50ch; color: var(--nx-text-muted)">
            {{ $say('Clean arms the green button, conflicts mute it behind a fix-first link, and the merged state closes the story in done purple.', 'حالت تمیز دکمهٔ سبز را مسلح می‌کند، تعارض آن را پشت لینکِ «اول درستش کن» خاکستری می‌کند، و حالت ادغام‌شده قصه را با بنفشِ done می‌بندد.') }}
        </p>
    </div>
    <div class="prm-root prm-strip">
        <div class="prm-cell">
            <p class="prm-status" data-kind="clean" style="margin: 0">{!! $oct('check') !!} <span>{{ $say('No conflicts with the base branch', 'بدون تعارض با شاخهٔ پایه') }}</span></p>
            <span class="prm-opt" style="border-color: var(--prm-accent)"><input type="radio" tabindex="-1" aria-hidden="true"><b>{{ $say('Create a merge commit', 'ساخت کامیت ادغام') }}</b></span>
            <button type="button" class="prm-go" style="align-self: start">{!! $oct('check') !!} {{ $say('Merge pull request', 'ادغام پول‌ریکوئست') }}</button>
            <small>clean</small>
        </div>
        <div class="prm-cell">
            <p class="prm-status" data-kind="conflict" style="margin: 0">{!! $oct('alert') !!} <span>{{ $say('This branch has conflicts that must be resolved', 'این شاخه تعارض‌هایی دارد که باید حل شوند') }}</span></p>
            <span class="prm-opt"><input type="radio" tabindex="-1" aria-hidden="true"><b>{{ $say('Squash and merge', 'فشرده‌سازی و ادغام') }}</b></span>
            <button type="button" class="prm-go" disabled style="align-self: start" aria-disabled="true">{!! $oct('merge') !!} {{ $say('Squash and merge', 'فشرده‌سازی و ادغام') }}</button>
            <small>conflicts</small>
        </div>
        <div class="prm-cell">
            <div class="prm-done">
                <span class="prm-ico">{!! $oct('merge') !!}</span>
                <p><b>{{ $say('Merged', 'ادغام شد') }}</b> — {{ $say('2 days ago', '۲ روز پیش') }}</p>
            </div>
            <small>merged · {{ $say('done purple', 'بنفشِ done') }}</small>
        </div>
    </div>
</section>
