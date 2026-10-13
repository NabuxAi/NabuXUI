{{--
    Glass buttons over a night-toned blob field: a floating transport bar
    (shimmer on press, an iridescent favourite, a thick icon-only settings),
    then the sizes and the quiet static variant on a lighter stage.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<style>
    .gbt-stage { position: relative; isolation: isolate; overflow: clip; display: grid; place-items: center; gap: 1.25rem; min-block-size: 18rem; padding: 2.5rem 1.25rem; border-radius: var(--nx-radius-2xl); }
    .gbt-stage[data-tone='night'] { background: var(--nx-ink-950); }
    .gbt-stage[data-tone='day'] { background: var(--nx-bg); }
    .gbt-blobs { position: absolute; inset: 0; z-index: -2; filter: blur(28px) saturate(1.2); }
    .gbt-blobs i { position: absolute; inline-size: 44%; aspect-ratio: 1; border-radius: 50%; opacity: .85; animation: gbt-drift 20s ease-in-out infinite alternate; }
    .gbt-blobs i:nth-child(1) { inset-block-start: -10%; inset-inline-start: -4%; background: var(--nx-lapis-500); }
    .gbt-blobs i:nth-child(2) { inset-block-end: -16%; inset-inline-end: -6%; background: var(--nx-violet-500); animation-delay: -8s; }
    .gbt-blobs i:nth-child(3) { inset-block-start: 12%; inset-inline-end: 18%; inline-size: 26%; background: var(--nx-gold-400); animation-delay: -13s; }
    @keyframes gbt-drift { to { translate: calc(-8% * var(--nx-motion)) calc(6% * var(--nx-motion)); scale: calc(1 + .1 * var(--nx-motion)); } }
    @media (prefers-reduced-motion: reduce) { .gbt-blobs i { animation: none; } }
</style>

<section class="gbt-stage" data-tone="night">
    <div class="gbt-blobs" aria-hidden="true"><i></i><i></i><i></i></div>
    <div style="display: grid; gap: .75rem; justify-items: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl); color: var(--nx-ink-50)">{{ $say('A transport bar over the video', 'نوار پخش روی ویدیو') }}</h3>
        <div class="pg-row">
            <x-nx::glass-button icon="play" shimmer wire:click="ping(@js($say('Resume from 12:04', 'ادامه از ۱۲:۰۴')))">{{ $say('Resume', 'ادامه') }}</x-nx::glass-button>
            <x-nx::glass-button icon="heart" iridescent wire:click="ping(@js($say('Added to your list', 'به فهرست شما اضافه شد')))">{{ $say('My list', 'فهرست من') }}</x-nx:glass-button>
            <x-nx::glass-button icon="settings" preset="thick" aria-label="{{ $say('Playback settings', 'تنظیمات پخش') }}" wire:click="ping(@js($say('Settings sheet opened', 'برگهٔ تنظیمات باز شد')))" />
            <x-nx::glass-button icon="external-link" icon-end="arrow-right" href="/components" wire:navigate>{{ $say('All demos', 'همهٔ دموها') }}</x-nx::glass-button>
        </div>
        <p style="margin: 0; font-size: var(--nx-text-sm); color: color-mix(in oklab, var(--nx-ink-100) 80%, transparent)">
            {{ $say('Press “Resume” and watch the light go around the rim once.', '«ادامه» را فشار دهید و ببینید نور یک دور دور لبه می‌رود.') }}
        </p>
    </div>
</section>

<div class="pg-grid">
    <section class="gbt-stage" data-tone="day">
        <div class="gbt-blobs" aria-hidden="true"><i></i><i></i><i></i></div>
        <div style="display: grid; gap: .75rem; justify-items: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-lg); color: var(--nx-text)">{{ $say('Sizes, sm to lg', 'اندازه‌ها، از sm تا lg') }}</h3>
            <div class="pg-row" style="align-items: end">
                <x-nx::glass-button size="sm" icon="plus">{{ $say('Small', 'کوچک') }}</x-nx::glass-button>
                <x-nx::glass-button icon="plus">{{ $say('Medium', 'متوسط') }}</x-nx::glass-button>
                <x-nx::glass-button size="lg" icon="sparkles">{{ $say('Large', 'بزرگ') }}</x-nx::glass-button>
            </div>
        </div>
    </section>

    <section class="gbt-stage" data-tone="day">
        <div class="gbt-blobs" aria-hidden="true"><i></i><i></i><i></i></div>
        <div style="display: grid; gap: .75rem; justify-items: center">
            <h3 class="pg-title" style="font-size: var(--nx-text-lg); color: var(--nx-text)">{{ $say('static — no press squeeze', 'static — بدون فشردگی') }}</h3>
            <div class="pg-row">
                <x-nx::glass-button icon="copy" wire:click="ping(@js($say('Copied', 'کپی شد')))">{{ $say('Copy link', 'کپی لینک') }}</x-nx::glass-button>
                <x-nx::glass-button icon="check" static wire:click="ping(@js($say('No scale, just a toast', 'بدون مقیاس، فقط یک توست')))">{{ $say('Static', 'ایستا') }}</x-nx:glass-button>
            </div>
            <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
                {{ $say('For places where the squeeze would distract from the content behind.', 'برای جاهایی که فشردگی از محتوای پشت حواس‌پرت‌کن می‌شود.') }}
            </p>
        </div>
    </section>
</div>
