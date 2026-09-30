{{--
    Pulse button's real scenarios: the showreel stage of a video landing (the
    classic use — a round "play" that rings), then an onboarding pager where
    only the discs remain, and a strip of tones and sizes.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $step = (int) ($state['step'] ?? 1);
@endphp

<section class="pg-box" style="position: relative; isolation: isolate; overflow: clip; place-content: center; justify-items: center; text-align: center; min-block-size: 24rem; gap: 1rem">
    <x-nx::backdrop variant="stars" />
    <p style="position: relative; margin: 0; color: var(--nx-text-subtle); font-size: var(--nx-text-xs); font-weight: 600">
        {{ $say('Showreel · '.NabuXUI::formatNumber(2).':'.NabuXUI::formatNumber(40), 'معرفی · '.NabuXUI::formatNumber(2).':'.NabuXUI::formatNumber(40)) }}
    </p>
    <h3 class="pg-title" style="position: relative; margin: 0; font-size: var(--nx-text-3xl); max-inline-size: 22ch">
        {{ $say('See NabuXUI in motion', 'نابوای‌یو‌آی را در حرکت ببینید') }}
    </h3>
    <x-nx::pulse-button icon="play" tone="inverse" size="lg" wire:click="save(@js($say('Playing the showreel', 'در حال پخش معرفی')))" style="position: relative">
        {{ $say('Play the showreel', 'پخش معرفی') }}
    </x-nx::pulse-button>
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('An onboarding pager', 'صفحه‌بندِ راه‌اندازی') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('Icon-only discs move a three-step wizard; the label moves into aria-label and the server keeps the step in $state.', 'دیسک‌های فقط-آیکونی یک ویزارد سه‌مرحله‌ای را جابه‌جا می‌کنند؛ برچسب به aria-label می‌رود و سرور مرحله را در $state نگه می‌دارد.') }}
        </p>
        <div class="pg-row" style="gap: 1.5rem">
            <x-nx::pulse-button icon="arrow-left" size="sm" aria-label="{{ $say('Previous step', 'مرحلهٔ قبل') }}" wire:click="ping(@js($say('You are on step '.$step, 'در مرحلهٔ '.NabuXUI::formatNumber($step).' هستید')))" />
            <strong style="font: 700 var(--nx-text-xl) / 1 var(--nx-font-display)">
                {{ NabuXUI::formatNumber($step) }} / {{ NabuXUI::formatNumber(3) }}
            </strong>
            <x-nx::pulse-button icon="arrow-right" size="sm" tone="gold" aria-label="{{ $say('Next step', 'مرحلهٔ بعد') }}" wire:click="ping(@js($say('Step '.$step.' of 3', 'مرحلهٔ '.NabuXUI::formatNumber($step).' از '.NabuXUI::formatNumber(3))))" />
        </div>
    </section>

    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Tones and sizes', 'رنگ‌ها و اندازه‌ها') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('accent, gold and inverse in sm · md · lg — the disc leans toward the pointer and the rings keep pulsing underneath.', 'رنگ‌های accent و gold و inverse در اندازه‌های sm · md · lg — دیسک به سمت موس خم می‌شود و حلقه‌ها زیرش می‌تپند.') }}
        </p>
        <div class="pg-row" style="align-items: start; gap: 2rem">
            <x-nx::pulse-button wire:click="ping(@js($say('Default tone', 'رنگ پیش‌فرض')))">{{ $say('Accent', 'اکسنت') }}</x-nx::pulse-button>
            <x-nx::pulse-button icon="sparkles" tone="gold" size="sm">{{ $say('Gold', 'طلایی') }}</x-nx::pulse-button>
            <x-nx::pulse-button icon="arrow-right" tone="inverse" size="lg" href="/components" wire:navigate>{{ $say('All demos', 'همهٔ دموها') }}</x-nx::pulse-button>
        </div>
    </section>
</div>
