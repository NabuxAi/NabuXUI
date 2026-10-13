{{--
    Dither backdrop's real scenarios: a support hero painted over the field
    (with cell/matrix tuned), then a download portal panel tuned for battery —
    fewer frames, coarser cells — which is exactly what the props are for.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $scene = 'position: relative; isolation: isolate; overflow: clip; place-content: center; justify-items: center; text-align: center';
@endphp

<section class="pg-box" style="{{ $scene }}; min-block-size: 24rem; gap: 1rem">
    <x-nx::dither-backdrop :cell="3" :matrix="8" />
    <x-nx::badge tone="accent" dot style="position: relative">{{ $say('Now in '.NabuXUI::formatNumber(12).' languages', 'اکنون به '.NabuXUI::formatNumber(12).' زبان') }}</x-nx::badge>
    <h3 class="pg-title" style="position: relative; margin: 0; font-size: var(--nx-text-3xl); max-inline-size: 20ch">
        {{ $say('Support that speaks your customer’s language', 'پشتیبانی که به زبان مشتری‌ات حرف می‌زند') }}
    </h3>
    <p style="position: relative; margin: 0; color: var(--nx-text-muted)">
        {{ $say('Agents answering in Español, 日本語 and فارسی — in seconds.', 'ایجنت‌هایی که در چند ثانیه به اسپانیایی، ژاپنی و فارسی جواب می‌دهند.') }}
    </p>
    <div class="pg-row" style="justify-content: center; position: relative">
        <x-nx::button variant="primary" shape="pill" wire:click="save(@js($say('Trial started', 'دورهٔ آزمایشی شروع شد')))">{{ $say('Start free', 'شروع رایگان') }}</x-nx::button>
        <x-nx::button shape="pill" wire:click="ping(@js($say('We will call you', 'با شما تماس می‌گیریم')))">{{ $say('Book a demo', 'رزرو دمو') }}</x-nx::button>
    </div>
</section>

<div class="pg-grid">
    <section class="pg-box" style="{{ $scene }}; min-block-size: 16rem; gap: .75rem">
        <x-nx::dither-backdrop :cell="5" :matrix="4" :fps="24" />
        <h3 class="pg-title" style="position: relative; margin: 0; font-size: var(--nx-text-xl)">{{ $say('The download portal', 'پرتال دانلود') }}</h3>
        <p style="position: relative; margin: 0; color: var(--nx-text-muted); max-inline-size: 40ch">
            {{ $say('cell=5, matrix=4, fps=24 — coarser cells and fewer frames for a panel people stare at.', 'رنگ cell=5 و matrix=4 و fps=24 — سلول‌های درشت‌تر و فریم‌های کمتر برای پنلی که آدم‌ها به آن خیره می‌شوند.') }}
        </p>
        <x-nx::button variant="secondary" size="sm" icon="arrow-up" wire:click="save(@js($say(NabuXUI::formatNumber(3).' files queued', NabuXUI::formatNumber(3).' فایل در صف رفت')))" style="position: relative">
            {{ $say('Download the toolkit', 'دانلود جعبه‌ابزار') }}
        </x-nx::button>
    </section>

    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Where it pauses', 'کجا می‌ایستد') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('The canvas stops painting off-screen and in hidden tabs, and under prefers-reduced-motion it holds one still frame — the rest of the page keeps behaving normally.', 'بوم بیرون از دید و در تب‌های مخفی نقاشی را متوقف می‌کند و در prefers-reduced-motion یک فریم ساکن نگه می‌دارد — بقیهٔ صفحه عادی کار می‌کند.') }}
        </p>
        <ul role="list" style="margin: 0; padding-inline-start: 1.25rem; display: grid; gap: .5rem; color: var(--nx-text-muted)">
            <li>{{ $say('Put it first inside a positioned, clipped section — everything after it stacks above.', 'آن را اولِ یک بخشِ position‌دار و کلیپ‌شده بگذارید — هرچه بعدش بیاید رویش می‌نشیند.') }}</li>
            <li>{{ $say('aria-hidden="true" already sits on the field; screen readers never see the noise.', 'روی میدان از قبل aria-hidden="true" هست؛ صفحه‌خوان‌ها هیچ‌وقت نویز را نمی‌بینند.') }}</li>
        </ul>
    </section>
</div>
