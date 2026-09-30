{{--
    Scroll scramble's real scenarios: a product page that shows off the scripts
    it supports, then a privacy page whose headline "encrypts" — the English
    one scrambles with cuneiform glyphs, the Persian with its own letters.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $scripts = [
        $say('English', 'انگلیسی'), 'Español', 'Français', 'Deutsch', '日本語', '中文',
        $say('Arabic', 'عربی'), 'فارسی', 'हिन्दी', '한국어', 'Türkçe', 'Português',
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The scripts we speak', 'خط‌هایی که می‌شناسیم') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The pinned section of a language-product page: the headline decodes in step with the scroll and the '.count($scripts).' supported scripts fly out of a pile into a row.', 'بخش پین‌شدهٔ صفحهٔ یک محصول زبانی: تیتر هم‌گام با اسکرول رمزگشایی می‌شود و '.NabuXUI::formatNumber(count($scripts)).' خطِ پشتیبانی‌شده از یک پشته به ردیف می‌پرند.') }}
        </p>
    </div>
</section>

<section class="pg-box" style="padding: 0; overflow: clip">
    <x-nx::scroll-scramble
        :title="$say('Decoding every script', 'رمزگشایی هر خط')"
        :items="$scripts"
        height="180vh" />
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A privacy headline', 'تیتر حریم خصوصی') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('Scrambling suits encryption stories: this one decodes with cuneiform glyphs, the Persian below with its own letters — charset picks the alphabet.', 'رمزگشایی برای داستان‌های رمزنگاری ساخته شده: این یکی با میخ‌خط رمز می‌شود و فارسیِ زیر با حروف خودش — charset الفبا را انتخاب می‌کند.') }}
        </p>
    </section>
</div>

<section class="pg-box" style="padding: 0; overflow: clip">
    <x-nx::scroll-scramble
        :title="$say('Privacy, letter by letter', 'حریم خصوصی، حرف‌به‌حرف')"
        :charset="$fa ? null : 'cuneiform'"
        :items="[$say('Encryption', 'رمزنگاری سرتاسری'), $say('Compliance', 'انطباق'), $say('Transparency', 'شفافیت')]"
        height="150vh" />
</section>
