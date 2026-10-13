{{--
    Roll text's real scenarios: a portfolio site's header navigation (links),
    then the share row under a journal article — links plus one real button.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A studio header', 'سربرگ یک استودیو') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The nav of a portfolio page — each item is a real link, and the label rolls over letter by letter as the pointer crosses it.', 'ناوبری صفحهٔ نمونه‌کارها — هر آیتم یک لینک واقعی است و برچسبش حرف‌به‌حرف می‌غلتد وقتی موس از رویش می‌گذرد.') }}
        </p>
    </div>
    <header class="pg-row" style="justify-content: space-between; border-block-end: 1px solid var(--nx-border); padding-block-end: 1rem">
        <strong style="font: 700 var(--nx-text-lg) / 1 var(--nx-font-display)">{{ $say('Studio Nabu', 'استودیو نابو') }}</strong>
        <nav class="pg-row" style="gap: 0.5rem 1.5rem; font-size: var(--nx-text-lg); font-weight: 600" aria-label="{{ $say('Main', 'اصلی') }}">
            <x-nx::roll-text href="#">{{ $say('Work', 'نمونه‌کارها') }}</x-nx::roll-text>
            <x-nx::roll-text href="#">{{ $say('Studio', 'استودیو') }}</x-nx::roll-text>
            <x-nx::roll-text href="#">{{ $say('Journal', 'ژورنال') }}</x-nx::roll-text>
            <x-nx::roll-text href="#">{{ $say('Contact', 'تماس') }}</x-nx::roll-text>
        </nav>
        <x-nx::button variant="secondary" shape="pill" icon-end="arrow-right">
            <x-nx::roll-text>{{ $say('Start a project', 'شروع یک پروژه') }}</x-nx::roll-text>
        </x-nx::button>
    </header>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Share row under an article', 'ردیف اشتراک‌گذاری زیر یک مقاله') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Three quiet links and one button that actually does something — as="button" keeps it keyboard-operable while it rolls like the rest.', 'سه لینک آرام و یک دکمه که واقعاً کاری می‌کند — as="button" آن را با کیبورد کارکُند نگه می‌دارد و مثل بقیه می‌غلتد.') }}
        </p>
    </div>
    <div class="pg-row" style="font-size: var(--nx-text-xl); font-weight: 600">
        <x-nx::roll-text href="#" style="color: var(--nx-text-muted)">{{ $say('Repost', 'بازنشر') }}</x-nx::roll-text>
        <x-nx::roll-text href="#" style="color: var(--nx-text-muted)">{{ $say('Newsletter', 'خبرنامه') }}</x-nx::roll-text>
        <x-nx::roll-text href="#" style="color: var(--nx-text-muted)">RSS</x-nx::roll-text>
        <x-nx::roll-text as="button" wire:click="ping(@js($say('Link copied to the clipboard', 'لینک در کلیپ‌بورد کپی شد')))">
            {{ $say('Copy link', 'کپی لینک') }}
        </x-nx::roll-text>
    </div>
</section>
