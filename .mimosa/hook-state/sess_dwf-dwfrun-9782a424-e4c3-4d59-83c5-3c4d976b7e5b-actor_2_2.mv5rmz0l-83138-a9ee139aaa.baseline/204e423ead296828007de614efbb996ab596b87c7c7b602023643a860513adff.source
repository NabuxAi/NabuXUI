{{--
    Spotlight tour's real scenarios: first-run onboarding over a small project
    dashboard — the "new project" button, search, the usage card, then a
    centred closing step.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('First-run onboarding', 'آشنایی در نخستین ورود') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Start the tour: the page dims with a cut-out around each target. Arrow keys move between steps, Escape skips, focus returns to the button when it ends.', 'تور را شروع کنید: صفحه تیره می‌شود و دور هر هدف بریده می‌شود. کلیدهای جهت بین گام‌ها جابه‌جا می‌کنند، Escape رد می‌کند و در پایان فوکوس به دکمه برمی‌گردد.') }}
        </p>
    </div>

    <div style="display: grid; gap: 1rem; padding: 1rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); background: var(--nx-surface-2)">
        <div class="pg-row" style="justify-content: space-between">
            <strong style="font: 700 var(--nx-text-lg) / 1 var(--nx-font-display)">{{ $say('Projects', 'پروژه‌ها') }}</strong>
            <div class="pg-row">
                <div id="fx-tour-search" style="min-inline-size: 12rem">
                    <x-nx::input type="search" :placeholder="$say('Search projects', 'جست‌وجوی پروژه‌ها')" :aria-label="$say('Search projects', 'جست‌وجوی پروژه‌ها')" />
                </div>
                <x-nx::button id="fx-tour-new" variant="primary" icon="plus">{{ $say('New project', 'پروژهٔ تازه') }}</x-nx::button>
            </div>
        </div>
        <div style="display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(12rem, 1fr))">
            <div style="padding: 1rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg); background: var(--nx-surface)">
                <strong>{{ $say('Spring campaign', 'کمپین بهار') }}</strong>
                <p style="margin: .25rem 0 0; color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $say('Updated 2 hours ago', '۲ ساعت پیش به‌روز شد') }}</p>
            </div>
            <div style="padding: 1rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg); background: var(--nx-surface)">
                <strong>{{ $say('Mobile app v2', 'اپ موبایل نسخهٔ ۲') }}</strong>
                <p style="margin: .25rem 0 0; color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $say('Updated yesterday', 'دیروز به‌روز شد') }}</p>
            </div>
            <div id="fx-tour-usage" style="padding: 1rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg); background: var(--nx-surface)">
                <strong>{{ $say('Usage this month', 'مصرف این ماه') }}</strong>
                <p style="margin: .25rem 0 .5rem; color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $say('7.2 of 10 GB', '۷٫۲ از ۱۰ گیگابایت') }}</p>
                <x-nx::progress :value="72" :label="$say('Storage used', 'فضای مصرف‌شده')" />
            </div>
        </div>
    </div>

    <div class="pg-row">
        <x-nx::button variant="primary" icon="star" x-data x-on:click="$dispatch('nx-tour-start', 'fx-welcome')">{{ $say('Take the tour', 'شروع تور') }}</x-nx::button>
        <span x-data="{ last: '' }" x-on:nx-tour-finish.window="last = @js($say('Tour finished — welcome aboard!', 'تور تمام شد — خوش آمدید!'))" x-on:nx-tour-close.window="last = last || @js($say('Tour closed.', 'تور بسته شد.'))" x-on:nx-tour-start.window="last = ''" role="status" style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)" x-text="last"></span>
    </div>

    <x-nx::spotlight-tour id="fx-welcome"
        :next="$say('Next', 'بعدی')" :back="$say('Back', 'قبلی')" :skip="$say('Skip tour', 'رد کردن تور')" :done="$say('Done', 'تمام')"
        :step-label="$say('Step :current of :total', 'گام :current از :total')"
        :steps="[
            ['target' => '#fx-tour-new', 'title' => $say('Start here', 'از این‌جا شروع کنید'), 'body' => $say('Create a project for each client or product. You can invite your team later.', 'برای هر مشتری یا محصول یک پروژه بسازید. اعضای تیم را بعداً دعوت می‌کنید.')],
            ['target' => '#fx-tour-search', 'title' => $say('Find anything', 'همه‌چیز را پیدا کنید'), 'body' => $say('Search projects, files and people. Press / from anywhere to jump here.', 'پروژه‌ها، فایل‌ها و افراد را جست‌وجو کنید. از هر جا کلید / را بزنید تا به این‌جا برسید.')],
            ['target' => '#fx-tour-usage', 'title' => $say('Keep an eye on usage', 'مصرف را زیر نظر داشته باشید'), 'body' => $say('We email you at 80% — no surprises at the end of the month.', 'در ۸۰٪ برایتان ایمیل می‌فرستیم — آخر ماه غافلگیر نمی‌شوید.'), 'side' => 'top'],
            ['title' => $say('You are all set', 'همه‌چیز آماده است'), 'body' => $say('Replay this tour any time from Help → Getting started.', 'این تور را هر وقت خواستید از راهنما ← شروع کار دوباره ببینید.')],
        ]" />
</section>
