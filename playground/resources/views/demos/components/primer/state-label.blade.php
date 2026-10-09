{{--
    Primer's StateLabel: a PR header whose pill morphs through the whole
    lifecycle (open → merged / closed / draft) on command, then every state
    in both official sizes side by side.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (int|string $n): string => $fa ? strtr((string) $n, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : (string) $n;
    $oct = function (string $name): string {
        $p = [
            'open' => '<path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z"/><path fill-rule="evenodd" d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0ZM1.5 8a6.5 6.5 0 1 0 13 0 6.5 6.5 0 0 0-13 0Z"/>',
            'done' => '<path fill-rule="evenodd" d="M8 16A8 8 0 1 1 8 0a8 8 0 0 1 0 16Zm3.78-9.72a.75.75 0 0 0-1.06-1.06L6.75 9.19 5.28 7.72a.75.75 0 0 0-1.06 1.06l2 2a.75.75 0 0 0 1.06 0l4.5-4.5Z"/>',
            'closed' => '<path fill-rule="evenodd" d="M2.343 13.657A8 8 0 1 1 13.658 2.343 8 8 0 0 1 2.343 13.657ZM6.03 4.97a.751.751 0 0 0-1.042.018.751.751 0 0 0-.018 1.042L6.94 8 4.97 9.97a.749.749 0 0 0 .326 1.275.749.749 0 0 0 .734-.215L8 9.06l1.97 1.97a.749.749 0 0 0 1.275-.326.749.749 0 0 0-.215-.734L9.06 8l1.97-1.97a.749.749 0 0 0-.326-1.275.749.749 0 0 0-.734.215L8 6.94Z"/>',
            'skip' => '<path fill-rule="evenodd" d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0ZM1.5 8a6.5 6.5 0 1 0 13 0 6.5 6.5 0 0 0-13 0Z"/><path d="M4.25 8.75h7.5v-1.5h-7.5Z"/>',
            'pr' => '<path fill-rule="evenodd" d="M1.5 3.25a2.25 2.25 0 1 1 3 2.122v5.256a2.251 2.251 0 1 1-1.5 0V5.372A2.25 2.25 0 0 1 1.5 3.25Zm5.677-.177L9.573.677A.25.25 0 0 1 10 .854V2.5h1A2.5 2.5 0 0 1 13.5 5v5.628a2.251 2.251 0 1 1-1.5 0V5a1 1 0 0 0-1-1h-1v1.646a.25.25 0 0 1-.427.177L7.177 3.427a.25.25 0 0 1 0-.354ZM3.75 2.5a.75.75 0 1 0 0 1.5.75.75 0 0 0 0-1.5Zm0 9.5a.75.75 0 1 0 0 1.5.75.75 0 0 0 0-1.5Zm8.25.75a.75.75 0 1 0 1.5 0 .75.75 0 0 0-1.5 0Z"/>',
            'merge' => '<path fill-rule="evenodd" d="M5.45 5.154A4.25 4.25 0 0 0 9.25 7.5h1.378a2.251 2.251 0 1 1 0 1.5H9.25A5.734 5.734 0 0 1 5 7.123v3.505a2.25 2.25 0 1 1-1.5 0V5.372a2.25 2.25 0 1 1 1.95-.218ZM4.25 13.5a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm8.5-4.5a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM5 3.25a.75.75 0 1 0-1.5 0 .75.75 0 0 0 1.5 0Z"/>',
            'draft' => '<path fill-rule="evenodd" d="M3.25 1A2.25 2.25 0 0 1 4 5.372v5.256a2.251 2.251 0 1 1-1.5 0V5.372A2.25 2.25 0 0 1 3.25 1Zm9.5 5.5a.75.75 0 0 1 .75.75v3.378a2.251 2.251 0 1 1-1.5 0V7.25a.75.75 0 0 1 .75-.75ZM3.25 2.5a.75.75 0 1 0 0 1.5.75.75 0 0 0 0-1.5Zm0 9a.75.75 0 1 0 0 1.5.75.75 0 0 0 0-1.5Zm9.5 0a.75.75 0 1 0 0 1.5.75.75 0 0 0 0-1.5ZM7.466 2.504a.75.75 0 0 1 1.06.04l1.72 1.856 1.72-1.856a.75.75 0 1 1 1.1 1.02l-1.83 1.976 1.83 1.976a.75.75 0 1 1-1.1 1.02l-1.72-1.856-1.72 1.856a.75.75 0 0 1-1.1-1.02l1.83-1.976-1.83-1.976a.75.75 0 0 1 .04-1.06Z"/>',
        ];
        return '<svg aria-hidden="true" viewBox="0 0 16 16" width="16" height="16" fill="currentColor" style="display:block">' . ($p[$name] ?? '') . '</svg>';
    };
@endphp
<style>
    .prs-root {
        --prs-canvas: #ffffff; --prs-subtle: #f6f8fa; --prs-fg: #1f2328; --prs-muted: #59636e;
        --prs-border: #d1d9e0; --prs-accent: #0969da;
        --prs-success: #1a7f37; --prs-done: #8250df; --prs-danger: #cf222e; --prs-neutral: #6e7781;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Noto Sans', Helvetica, Arial, sans-serif;
        color: var(--prs-fg);
    }
    html[data-theme="dark"] .prs-root {
        --prs-canvas: #0d1117; --prs-subtle: #151b23; --prs-fg: #f0f6fc; --prs-muted: #9198a1;
        --prs-border: #3d444d; --prs-accent: #4493f8;
        --prs-success: #3fb950; --prs-done: #ab7df8; --prs-danger: #f85149; --prs-neutral: #6e7681;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .prs-root {
            --prs-canvas: #0d1117; --prs-subtle: #151b23; --prs-fg: #f0f6fc; --prs-muted: #9198a1;
            --prs-border: #3d444d; --prs-accent: #4493f8;
            --prs-success: #3fb950; --prs-done: #ab7df8; --prs-danger: #f85149; --prs-neutral: #6e7681;
        }
    }
    .prs-root :focus-visible { outline: 2px solid var(--prs-accent); outline-offset: 2px; border-radius: 6px; }

    .prs-head { inline-size: min(100%, 40rem); margin-inline: auto; display: grid; gap: .75rem; padding: 1.25rem; border: 1px solid var(--prs-border); border-radius: 6px; background: var(--prs-canvas); }
    .prs-repo { display: flex; flex-wrap: wrap; align-items: center; gap: .4rem; font: 400 .875rem/1.4 inherit; color: var(--prs-muted); }
    .prs-repo b { color: var(--prs-accent); font-weight: 500; }
    .prs-titleline { display: flex; flex-wrap: wrap; align-items: center; gap: .6rem; }
    .prs-titleline h4 { margin: 0; font: 400 1.25rem/1.35 inherit; overflow-wrap: anywhere; }
    .prs-label { display: inline-flex; align-items: center; gap: .3rem; block-size: 1.5rem; padding-inline: .65rem; border-radius: 2em; font: 500 .75rem/1 inherit; color: #fff; white-space: nowrap; transition: background .25s ease; }
    .prs-label[data-lg] { block-size: 2rem; padding-inline: .85rem; font-size: .875rem; }
    .prs-label[data-status="prOpen"], .prs-label[data-status="open"] { background: var(--prs-success); }
    .prs-label[data-status="merged"], .prs-label[data-status="issueClosed"] { background: var(--prs-done); }
    .prs-label[data-status="prClosed"] { background: var(--prs-danger); }
    .prs-label[data-status="draft"], .prs-label[data-status="notPlanned"] { background: var(--prs-neutral); }
    .prs-acts { display: flex; flex-wrap: wrap; gap: .5rem; padding-block-start: .35rem; border-block-start: 1px solid var(--prs-border); }
    .prs-act { flex: 1 1 8rem; block-size: 2rem; display: inline-flex; align-items: center; justify-content: center; gap: .4rem; padding-inline: .75rem; border: 1px solid var(--prs-border); border-radius: 6px; background: var(--prs-subtle); color: var(--prs-fg); font: 500 .75rem/1 inherit; cursor: pointer; white-space: nowrap; }
    .prs-act:hover { background: color-mix(in srgb, var(--prs-border) 40%, var(--prs-subtle)); }
    .prs-act[aria-pressed="true"] { border-color: var(--prs-accent); color: var(--prs-accent); background: color-mix(in srgb, var(--prs-accent) 8%, var(--prs-canvas)); }
    .prs-note { margin: 0; font: 400 .8125rem/1.5 inherit; color: var(--prs-muted); text-align: center; }
    .prs-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(9.5rem, 1fr)); gap: 1rem; inline-size: min(100%, 42rem); margin-inline: auto; }
    .prs-cell { display: grid; gap: .45rem; justify-items: center; padding: .9rem .5rem; border: 1px solid var(--prs-border); border-radius: 6px; background: var(--prs-canvas); }
    .prs-cell small { font: 400 .6875rem/1.4 inherit; color: var(--prs-muted); text-align: center; }
    /* The shared demo template hides the props table's rows until it scrolls
       into view (data-nx-reveal on x-nx::data-table); a capture that never
       scrolls records an empty table. Pin this page's rows visible. */
    html:has(.prs-root) .nx-data-table[data-nx-reveal]:not([data-nx-revealed]) tbody tr { opacity: 1; translate: none; }
    @media (max-width: 480px) {
        .prs-titleline h4 { font-size: 1.05rem; }
        .prs-act { flex-basis: 100%; }
    }
    @media (prefers-reduced-motion: reduce) {
        .prs-label { transition: none; }
    }
</style>

<div class="prs-root" x-data="{ st: 'prOpen' }">
    <section class="pg-box" style="gap: 1.25rem">
        <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('One pill, a whole lifecycle', 'یک قرص، یک چرخهٔ کامل') }}</h3>
            <p style="margin: 0; max-width: 48ch; color: var(--nx-text-muted)">
                {{ $say('Drive the pull request through its life — the StateLabel swaps its octicon and its Primer colour the moment the verdict changes.', 'پول‌ریکوئست را در چرخهٔ زندگی‌اش پیش ببرید — لیبل وضعیت در همان لحظهٔ تغییر حکم، هم اکت‌آیکونش را عوض می‌کند هم رنگ پرایمرش را.') }}
            </p>
        </div>

        <div class="prs-head">
            <div class="prs-repo">
                <span>nabux / dashboard</span><span aria-hidden="true">·</span><b>{{ $say('Pull request', 'پول‌ریکوئست') }}</b><span aria-hidden="true">#{{ $num(412) }}</span>
            </div>
            <div class="prs-titleline">
                <h4>{{ $say('feat: PDF export for monthly reports', 'افزودن خروجی PDF برای گزارش‌های ماهانه') }}</h4>
                <span class="prs-label" data-lg :data-status="st">
                    <span class="prs-ico" x-show="st === 'prOpen'">{!! $oct('pr') !!}</span>
                    <span class="prs-ico" x-show="st === 'merged'" x-cloak>{!! $oct('merge') !!}</span>
                    <span class="prs-ico" x-show="st === 'prClosed'" x-cloak>{!! $oct('closed') !!}</span>
                    <span class="prs-ico" x-show="st === 'draft'" x-cloak>{!! $oct('draft') !!}</span>
                    <span x-show="st === 'prOpen'">{{ $say('Open', 'باز') }}</span>
                    <span x-show="st === 'merged'" x-cloak>{{ $say('Merged', 'ادغام شد') }}</span>
                    <span x-show="st === 'prClosed'" x-cloak>{{ $say('Closed', 'بسته شد') }}</span>
                    <span x-show="st === 'draft'" x-cloak>{{ $say('Draft', 'پیش‌نویس') }}</span>
                </span>
            </div>
            <p class="prs-note">
                {{ $say('Green = open work · purple = merged with done · red = closed without merging · gray = still a draft.', 'سبز = کار باز · بنفش = ادغام‌شده با done · قرمز = بسته بدون ادغام · خاکستری = هنوز پیش‌نویس.') }}
            </p>
            <div class="prs-acts" role="group" aria-label="{{ $say('Change state', 'تغییر وضعیت') }}">
                <button type="button" class="prs-act" x-on:click="st = 'prOpen'" :aria-pressed="st === 'prOpen'">{!! $oct('pr') !!} {{ $say('Reopen', 'بازکردن') }}</button>
                <button type="button" class="prs-act" x-on:click="st = 'merged'" :aria-pressed="st === 'merged'">{!! $oct('merge') !!} {{ $say('Merge', 'ادغام') }}</button>
                <button type="button" class="prs-act" x-on:click="st = 'prClosed'" :aria-pressed="st === 'prClosed'">{!! $oct('closed') !!} {{ $say('Close', 'بستن') }}</button>
                <button type="button" class="prs-act" x-on:click="st = 'draft'" :aria-pressed="st === 'draft'">{!! $oct('draft') !!} {{ $say('Convert to draft', 'تبدیل به پیش‌نویس') }}</button>
            </div>
        </div>
    </section>
</div>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('The full palette of verdicts', 'تمام پالت حکم‌ها') }}</h3>
        <p style="margin: 0; max-width: 50ch; color: var(--nx-text-muted)">
            {{ $say('Issues and PRs share the anatomy but not the verdicts — closed issues go purple when completed, PRs only when merged.', 'ایشو و PR کالبد مشترک دارند اما حکم نه — ایشوی بسته در صورت تکمیل بنفش می‌شود، PR فقط در صورت ادغام.') }}
        </p>
    </div>
    <div class="prs-root prs-grid">
        <div class="prs-cell"><span class="prs-label" data-status="open">{!! $oct('open') !!} {{ $say('Open', 'باز') }}</span><small>{{ $say('issue · open', 'ایشو · باز') }}</small></div>
        <div class="prs-cell"><span class="prs-label" data-status="issueClosed">{!! $oct('done') !!} {{ $say('Closed', 'بسته') }}</span><small>{{ $say('issue · completed', 'ایشو · تکمیل‌شده') }}</small></div>
        <div class="prs-cell"><span class="prs-label" data-status="notPlanned">{!! $oct('skip') !!} {{ $say('Not planned', 'برنامه‌ریزی نشد') }}</span><small>{{ $say('issue · not planned', 'ایشو · برنامه‌ریزی‌نشده') }}</small></div>
        <div class="prs-cell"><span class="prs-label" data-status="prOpen">{!! $oct('pr') !!} {{ $say('Open', 'باز') }}</span><small>{{ $say('PR · open', 'PR · باز') }}</small></div>
        <div class="prs-cell"><span class="prs-label" data-status="merged">{!! $oct('merge') !!} {{ $say('Merged', 'ادغام شد') }}</span><small>{{ $say('PR · merged', 'PR · ادغام‌شده') }}</small></div>
        <div class="prs-cell"><span class="prs-label" data-status="prClosed">{!! $oct('closed') !!} {{ $say('Closed', 'بسته شد') }}</span><small>{{ $say('PR · closed', 'PR · بسته‌شده') }}</small></div>
        <div class="prs-cell"><span class="prs-label" data-status="draft">{!! $oct('draft') !!} {{ $say('Draft', 'پیش‌نویس') }}</span><small>{{ $say('PR · draft', 'PR · پیش‌نویس') }}</small></div>
        <div class="prs-cell"><span class="prs-label" data-lg data-status="merged">{!! $oct('merge') !!} {{ $say('Merged', 'ادغام شد') }}</span><small>{{ $say('large size', 'اندازهٔ بزرگ') }}</small></div>
    </div>
</section>
