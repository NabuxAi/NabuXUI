{{--
    Glitch text's real scenarios: a 404 page whose headline bursts every few
    seconds, and a status page whose links glitch when hovered or focused.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A 404 page', 'صفحهٔ ۴۰۴') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('trigger="loop": idle most of the time, then a short burst. Screen readers hear the headline once.', 'trigger="loop": بیشتر وقت آرام، بعد یک جرقهٔ کوتاه. صفحه‌خوان تیتر را فقط یک‌بار می‌خواند.') }}
        </p>
    </div>
    <div style="display: grid; gap: 1rem; place-items: center; padding: 3rem 1rem; border-radius: var(--nx-radius-xl); background: var(--nx-surface-2); text-align: center">
        <x-nx::glitch-text as="h2" trigger="loop" :text="$say('404 — nothing here', '۴۰۴ — این‌جا چیزی نیست')"
            style="margin: 0; font: 800 var(--nx-text-4xl) / 1.15 var(--nx-font-display)" />
        <p style="margin: 0; color: var(--nx-text-muted)">{{ $say('The page moved, or never existed.', 'صفحه جابه‌جا شده، یا هیچ‌وقت نبوده.') }}</p>
        <x-nx::button variant="primary" href="/components" icon="home">{{ $say('Back home', 'بازگشت به خانه') }}</x-nx::button>
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Status page links', 'لینک‌های صفحهٔ وضعیت') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Hover or tab to a link: the text inside glitches because its link is hovered or focused. The degraded service glitches on its own.', 'روی لینک هاور کنید یا با Tab برسید: متن داخلش گلیچ می‌شود چون لینکش هاور یا فوکوس شده. سرویسِ مختل خودش گلیچ می‌کند.') }}
        </p>
    </div>
    <ul role="list" style="display: grid; gap: .5rem; margin: 0; padding: 0; list-style: none; font-size: var(--nx-text-lg); font-weight: 600">
        <li class="pg-row" style="justify-content: space-between">
            <a href="#" style="color: inherit; text-decoration: none"><x-nx::glitch-text :text="$say('API gateway', 'درگاه API')" /></a>
            <x-nx::badge tone="success" dot>{{ $say('Operational', 'فعال') }}</x-nx::badge>
        </li>
        <li class="pg-row" style="justify-content: space-between">
            <a href="#" style="color: inherit; text-decoration: none"><x-nx::glitch-text :text="$say('Payments', 'پرداخت‌ها')" /></a>
            <x-nx::badge tone="success" dot>{{ $say('Operational', 'فعال') }}</x-nx::badge>
        </li>
        <li class="pg-row" style="justify-content: space-between">
            <a href="#" style="color: inherit; text-decoration: none"><x-nx::glitch-text trigger="loop" :text="$say('Search index', 'نمایهٔ جست‌وجو')" /></a>
            <x-nx::badge tone="warning" dot>{{ $say('Degraded', 'مختل') }}</x-nx::badge>
        </li>
    </ul>
</section>
