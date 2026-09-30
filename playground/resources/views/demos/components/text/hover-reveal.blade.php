{{--
    Hover reveal's real scenarios: the services index of an agency site (the
    classic "what we do" page), then a slimmer docs index — same component,
    quieter content.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A services index', 'فهرست خدمات') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The "what we do" page: one long link per discipline, its one-line promise underneath. Tab through — focus reveals the same glow the pointer does.', 'صفحهٔ «چه کار می‌کنیم»: یک لینک بلند برای هر تخصص و وعدهٔ یک‌خطی‌اش زیرش. با تب جابه‌جا شوید — فوکوس همان درخشش موس را می‌آورد.') }}
        </p>
    </div>
    <x-nx::hover-reveal :items="[
        ['label' => $say('Product design', 'طراحی محصول'), 'href' => '#', 'description' => $say('Interfaces with a sense of motion', 'رابط‌هایی که حس حرکت دارند')],
        ['label' => $say('Motion & interaction', 'حرکت و تعامل'), 'href' => '#', 'description' => $say('Springs, not durations', 'فنر، نه مدت‌زمان')],
        ['label' => $say('Front-end build', 'ساخت فرانت‌اند'), 'href' => '#'],
        ['label' => $say('Design systems', 'سیستم طراحی'), 'href' => '#', 'description' => $say('One core, five frameworks', 'یک هسته، پنج فریم‌ورک')],
    ]" />
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A shorter docs index', 'فهرست کوتاه مستندات') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('Without descriptions the rows breathe more — this is the whole component, no props beyond items.', 'بدون توضیح، ردیف‌ها هوای بیشتری دارند — این کل کامپوننت است، بدون پراپی جز items.') }}
        </p>
        <x-nx::hover-reveal :items="[
            ['label' => $say('Getting started', 'شروع سریع'), 'href' => '#'],
            ['label' => $say('Theming & tokens', 'تم و توکن‌ها'), 'href' => '#'],
            ['label' => $say('Right-to-left', 'راست‌به‌چپ'), 'href' => '#'],
        ]" />
    </section>

    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Keyboard-first', 'کیبورد-اول') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('Every row is one focus stop; the resting rows step aside exactly as they do for a hover.', 'هر ردیف یک ایستگاه فوکوس است؛ ردیف‌های دیگر دقیقاً مثل هاور کنار می‌روند.') }}
        </p>
        <p style="margin: 0; color: var(--nx-text-subtle); font-size: var(--nx-text-sm)">
            {{ $say('Try: click the first link, then press Tab twice.', 'امتحان کنید: روی نخستین لینک کلیک کنید و دو بار Tab بزنید.') }}
        </p>
        <x-nx::hover-reveal :items="[
            ['label' => $say('Support inbox', 'صندوق پشتیبانی'), 'href' => '#', 'description' => $say('Median first reply: '.NabuXUI::formatNumber(11).' minutes', 'میانهٔ نخستین پاسخ: '.NabuXUI::formatNumber(11).' دقیقه')],
            ['label' => $say('Status page', 'صفحهٔ وضعیت'), 'href' => '#'],
        ]" />
    </section>
</div>
