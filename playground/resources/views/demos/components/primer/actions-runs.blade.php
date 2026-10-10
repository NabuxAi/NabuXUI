{{--
    The Actions page: a live status filter (all / success / failure / running)
    over a run list with yellow attention spinners, green checks, red X marks
    and branch+SHA+duration meta — plus every status in one strip.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (int|string $n): string => $fa ? strtr((string) $n, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : (string) $n;
    $oct = function (string $name): string {
        $p = [
            'spinner' => '<path d="M8 0a8 8 0 0 1 8 8 1.25 1.25 0 0 1-2.5 0A5.5 5.5 0 0 0 8 2.5 1.25 1.25 0 0 1 8 0Z"/>',
            'check' => '<path fill-rule="evenodd" d="M8 16A8 8 0 1 1 8 0a8 8 0 0 1 0 16Zm3.78-9.72a.75.75 0 0 0-1.06-1.06L6.75 9.19 5.28 7.72a.75.75 0 0 0-1.06 1.06l2 2a.75.75 0 0 0 1.06 0l4.5-4.5Z"/>',
            'x' => '<path fill-rule="evenodd" d="M2.343 13.657A8 8 0 1 1 13.658 2.343 8 8 0 0 1 2.343 13.657ZM6.03 4.97a.751.751 0 0 0-1.042.018.751.751 0 0 0-.018 1.042L6.94 8 4.97 9.97a.749.749 0 0 0 .326 1.275.749.749 0 0 0 .734-.215L8 9.06l1.97 1.97a.749.749 0 0 0 1.275-.326.749.749 0 0 0-.215-.734L9.06 8l1.97-1.97a.749.749 0 0 0-.326-1.275.749.749 0 0 0-.734.215L8 6.94Z"/>',
            'skip' => '<path fill-rule="evenodd" d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0ZM1.5 8a6.5 6.5 0 1 0 13 0 6.5 6.5 0 0 0-13 0Z"/><path d="M4.25 8.75h7.5v-1.5h-7.5Z"/>',
            'branch' => '<path fill-rule="evenodd" d="M9.5 3.25a2.25 2.25 0 1 1 3 2.122V6A2.5 2.5 0 0 1 10 8.5H6a1 1 0 0 0-1 1v1.128a2.251 2.251 0 1 1-1.5 0V5.372a2.25 2.25 0 1 1 1.5 0v1.836A2.493 2.493 0 0 1 6 7h4a1 1 0 0 0 1-1v-.628A2.25 2.25 0 0 1 9.5 3.25Zm-6 0a.75.75 0 1 0 1.5 0 .75.75 0 0 0-1.5 0Zm8.25-.75a.75.75 0 1 0 0 1.5.75.75 0 0 0 0-1.5ZM4.25 12a.75.75 0 1 0 0 1.5.75.75 0 0 0 0-1.5Z"/>',
            'clock' => '<path fill-rule="evenodd" d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0ZM1.5 8a6.5 6.5 0 1 0 13 0 6.5 6.5 0 0 0-13 0Zm7-3.25v2.992l2.028.812a.75.75 0 0 1-.557 1.392l-2.5-1A.751.751 0 0 1 7 8.25v-3.5a.75.75 0 0 1 1.5 0Z"/>',
        ];
        return '<svg aria-hidden="true" viewBox="0 0 16 16" width="16" height="16" fill="currentColor" style="display:block">' . ($p[$name] ?? '') . '</svg>';
    };
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap');
    .pra-root {
        --pra-canvas: #ffffff; --pra-subtle: #f6f8fa; --pra-fg: #1f2328; --pra-muted: #59636e;
        --pra-border: #d1d9e0; --pra-accent: #0969da;
        --pra-success: #1a7f37; --pra-danger: #cf222e; --pra-attention: #9a6700; --pra-neutral: #59636e;
        font-family: 'Figtree', 'Inter', 'Vazirmatn', sans-serif;
        color: var(--pra-fg);
    }
    html[data-theme="dark"] .pra-root {
        --pra-canvas: #0d1117; --pra-subtle: #151b23; --pra-fg: #f0f6fc; --pra-muted: #9198a1;
        --pra-border: #3d444d; --pra-accent: #4493f8;
        --pra-success: #3fb950; --pra-danger: #f85149; --pra-attention: #d29922; --pra-neutral: #9198a1;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .pra-root {
            --pra-canvas: #0d1117; --pra-subtle: #151b23; --pra-fg: #f0f6fc; --pra-muted: #9198a1;
            --pra-border: #3d444d; --pra-accent: #4493f8;
            --pra-success: #3fb950; --pra-danger: #f85149; --pra-attention: #d29922; --pra-neutral: #9198a1;
        }
    }
    .pra-root :focus-visible { outline: 2px solid var(--pra-accent); outline-offset: 2px; border-radius: 6px; }

    .pra-panel { inline-size: min(100%, 46rem); margin-inline: auto; }
    .pra-seg { display: inline-flex; flex-wrap: wrap; gap: .25rem; padding: .2rem; margin-block-end: .75rem; border: 1px solid var(--pra-border); border-radius: 6px; background: var(--pra-subtle); }
    .pra-seg button { display: inline-flex; align-items: center; gap: .35rem; block-size: 1.6rem; padding-inline: .7rem; border: 0; border-radius: 4px; background: none; color: var(--pra-muted); font: 500 .75rem/1 inherit; cursor: pointer; white-space: nowrap; }
    .pra-seg button:hover { color: var(--pra-fg); }
    .pra-seg button[aria-pressed="true"] { background: var(--pra-canvas); color: var(--pra-fg); box-shadow: 0 1px 2px rgba(31, 35, 40, .08); border: 1px solid var(--pra-border); padding-inline: calc(.7rem - 1px); }
    .pra-seg svg { flex: none; }
    .pra-seg .ok { color: var(--pra-success); } .pra-seg .bad { color: var(--pra-danger); } .pra-seg .run { color: var(--pra-attention); }
    .pra-list { border: 1px solid var(--pra-border); border-radius: 6px; background: var(--pra-canvas); }
    .pra-run { display: flex; flex-wrap: wrap; gap: .35rem .75rem; align-items: flex-start; padding: .75rem 1rem; border-block-end: 1px solid var(--pra-border); text-decoration: none; color: inherit; }
    .pra-run:last-child { border-block-end: 0; border-end-end-radius: 6px; border-end-start-radius: 6px; }
    .pra-run:hover { background: var(--pra-subtle); }
    .pra-status { flex: none; margin-block-start: 1px; }
    .pra-status[data-run="success"] { color: var(--pra-success); }
    .pra-status[data-run="failure"] { color: var(--pra-danger); }
    .pra-status[data-run="running"] { color: var(--pra-attention); }
    .pra-status[data-run="skipped"] { color: var(--pra-neutral); }
    .pra-spin { animation: pra-spin 1s linear infinite; }
    @keyframes pra-spin { to { rotate: 360deg; } }
    .pra-main { flex: 1 1 16rem; min-inline-size: 0; display: grid; gap: .15rem; }
    .pra-name { font: 400 .875rem/1.45 inherit; }
    .pra-run:hover .pra-name { color: var(--pra-accent); }
    .pra-msg { font: 400 .78rem/1.5 inherit; color: var(--pra-muted); overflow-wrap: anywhere; }
    .pra-meta { display: flex; flex-wrap: wrap; gap: .15rem .7rem; font: 400 .71875rem/1.5 inherit; color: var(--pra-muted); }
    .pra-meta span { display: inline-flex; align-items: center; gap: .25rem; }
    .pra-meta code { font: 500 .6875rem/1.4 ui-monospace, SFMono-Regular, Menlo, monospace; }
    .pra-when { margin-inline-start: auto; flex: none; font: 400 .71875rem/1.5 inherit; color: var(--pra-muted); white-space: nowrap; }
    .pra-empty { margin: 0; padding: 1.25rem 1rem; font: 400 .8125rem/1.5 inherit; color: var(--pra-muted); text-align: center; }
    .pra-strip { display: grid; grid-template-columns: repeat(auto-fit, minmax(9rem, 1fr)); gap: .75rem; inline-size: min(100%, 40rem); margin-inline: auto; }
    .pra-cell { display: grid; gap: .45rem; justify-items: center; padding: .85rem .5rem; border: 1px solid var(--pra-border); border-radius: 6px; }
    .pra-cell small { font: 400 .6875rem/1.4 inherit; color: var(--pra-muted); text-align: center; }
    /* The shared demo template hides the props table's rows until it scrolls
       into view (data-nx-reveal on x-nx::data-table); a capture that never
       scrolls records an empty table. Pin this page's rows visible. */
    html:has(.pra-root) .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr { opacity: 1; translate: none; }
    @media (max-width: 480px) {
        .pra-when { margin-inline-start: 0; }
        .pra-run { align-items: baseline; }
    }
    @media (prefers-reduced-motion: reduce) {
        .pra-spin { animation: none; }
        .pra-root * { transition-duration: .01ms !important; }
    }
</style>

<div class="pra-root" x-data="{
        rtl: document.documentElement.dir === 'rtl',
        filter: 'all',
        runs: [
            { id: 812, state: 'running', flow: { en: 'CI / build and test', fa: 'CI / ساخت و تست' }, msg: { en: 'fix: cache leak in the report queue', fa: 'اصلاح: نشت حافظهٔ کش در صف گزارش‌ها' }, branch: 'main', sha: 'a1b2c3d', took: { en: '1:12 and counting', fa: '۱:۱۲ و همچنان در جریان' }, ago: { en: 'just now', fa: 'همین حالا' } },
            { id: 811, state: 'success', flow: { en: 'CI / build and test', fa: 'CI / ساخت و تست' }, msg: { en: 'feat: PDF export behind a flag', fa: 'قابلیت: خروجی PDF پشت پرچم' }, branch: 'feature/pdf', sha: '9c8d7e6', took: { en: '3 min 8 sec', fa: '۳ دقیقه و ۸ ثانیه' }, ago: { en: '2 hours ago', fa: '۲ ساعت پیش' } },
            { id: 810, state: 'failure', flow: { en: 'e2e / reports', fa: 'e2e / گزارش‌ها' }, msg: { en: 'fix: flaky date assertion, retry once', fa: 'اصلاح: ادعای تاریخِ بی‌ثبات، یک بار تلاش مجدد' }, branch: 'main', sha: 'e4f5a6b', took: { en: '5 min 2 sec', fa: '۵ دقیقه و ۲ ثانیه' }, ago: { en: 'yesterday', fa: 'دیروز' } },
            { id: 809, state: 'success', flow: { en: 'lint', fa: 'لینت' }, msg: { en: 'chore: bump the monorepo deps', fa: 'نگه‌داری: ارتقای وابستگی‌های مونوریپو' }, branch: 'main', sha: 'b7a6c5d', took: { en: '41 sec', fa: '۴۱ ثانیه' }, ago: { en: '2 days ago', fa: '۲ روز پیش' } },
            { id: 808, state: 'skipped', flow: { en: 'docs deploy', fa: 'انتشار مستندات' }, msg: { en: 'chore: bump the monorepo deps', fa: 'نگه‌داری: ارتقای وابستگی‌های مونوریپو' }, branch: 'main', sha: 'b7a6c5d', took: { en: '—', fa: '—' }, ago: { en: '2 days ago', fa: '۲ روز پیش' } },
        ],
        get matches() {
            if (this.filter === 'all') return this.runs;
            return this.runs.filter((r) => r.state === this.filter);
        },
        num(n) { return this.rtl ? String(n).replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]) : String(n) },
        flow(r) { return this.rtl ? r.flow.fa : r.flow.en },
        msg(r) { return this.rtl ? r.msg.fa : r.msg.en },
        took(r) { return this.rtl ? r.took.fa : r.took.en },
        ago(r) { return this.rtl ? r.ago.fa : r.ago.en },
    }">
    <section class="pg-box" style="gap: 1.25rem">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Repo health at a glance', 'سلامت مخزن در یک نگاه') }}</h3>
            <p style="margin: 0; max-width: 48ch; color: var(--nx-text-muted)">
                {{ $say('The yellow spinner means work is still in motion — cut it a filter and the list obeys. Green checks and red X marks never trade places.', 'اسپینر زرد یعنی کار هنوز در جریان است — یک فیلتر جلویش بگیرید و فهرست اطاعت می‌کند. تیک‌های سبز و ضربدرهای قرمز هرگز جابه‌جا نمی‌شوند.') }}
            </p>
        </div>

        <div class="pra-panel">
            <div class="pra-seg" role="group" aria-label="{{ $say('Filter runs by status', 'فیلتر ران‌ها با وضعیت') }}">
                <button type="button" x-on:click="filter = 'all'" :aria-pressed="filter === 'all'">{{ $say('All', 'همه') }} <span x-text="'(' + num(matches.length === runs.length ? runs.length : matches.length) + ')'"></span></button>
                <button type="button" x-on:click="filter = 'success'" :aria-pressed="filter === 'success'"><span class="ok">{!! $oct('check') !!}</span> {{ $say('Success', 'موفق') }}</button>
                <button type="button" x-on:click="filter = 'failure'" :aria-pressed="filter === 'failure'"><span class="bad">{!! $oct('x') !!}</span> {{ $say('Failure', 'شکست') }}</button>
                <button type="button" x-on:click="filter = 'running'" :aria-pressed="filter === 'running'"><span class="run">{!! $oct('spinner') !!}</span> {{ $say('In progress', 'در جریان') }}</button>
            </div>

            <div class="pra-list" aria-live="polite">
                <template x-for="r in matches" :key="r.id">
                    <a class="pra-run" href="#" x-on:click.prevent>
                        <span class="pra-status" :data-run="r.state">
                            <span x-show="r.state === 'success'">{!! $oct('check') !!}</span>
                            <span x-show="r.state === 'failure'" x-cloak>{!! $oct('x') !!}</span>
                            <span class="pra-spin" x-show="r.state === 'running'" x-cloak style="display:inline-block">{!! $oct('spinner') !!}</span>
                            <span x-show="r.state === 'skipped'" x-cloak>{!! $oct('skip') !!}</span>
                        </span>
                        <span class="pra-main">
                            <span class="pra-name" x-text="flow(r)"></span>
                            <span class="pra-msg" x-text="msg(r)"></span>
                            <span class="pra-meta">
                                <span>{!! $oct('branch') !!} <span x-text="r.branch"></span></span>
                                <span><code x-text="r.sha"></code></span>
                                <span>{!! $oct('clock') !!} <span x-text="took(r)"></span></span>
                            </span>
                        </span>
                        <span class="pra-when" x-text="'#' + num(r.id) + ' · ' + ago(r)"></span>
                    </a>
                </template>
                <p class="pra-empty" x-show="matches.length === 0" x-cloak>
                    {{ $say('No runs in this state — the repo is calmer than the filter.', 'در این وضعیت رانی نیست — مخزن از فیلتر آرام‌تر است.') }}
                </p>
            </div>
        </div>
    </section>
</div>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('Four verdicts, four colours', 'چهار حکم، چهار رنگ') }}</h3>
        <p style="margin: 0; max-width: 50ch; color: var(--nx-text-muted)">
            {{ $say('In-progress takes the attention yellow, success the green, failure the red, and skipped settles for quiet gray — the whole Actions language in one strip.', 'در جریان زردِ توجه را می‌گیرد، موفق سبز را، شکست قرمز را، و ردشده به خاکستریِ آرام بسنده می‌کند — تمام زبان Actions در یک نوار.') }}
        </p>
    </div>
    <div class="pra-root pra-strip">
        <div class="pra-cell"><span class="pra-status" data-run="running"><span class="pra-spin" style="display:inline-block">{!! $oct('spinner') !!}</span></span><small>{{ $say('in progress', 'در جریان') }}</small></div>
        <div class="pra-cell"><span class="pra-status" data-run="success">{!! $oct('check') !!}</span><small>success</small></div>
        <div class="pra-cell"><span class="pra-status" data-run="failure">{!! $oct('x') !!}</span><small>failure</small></div>
        <div class="pra-cell"><span class="pra-status" data-run="skipped">{!! $oct('skip') !!}</span><small>skipped</small></div>
    </div>
</section>
