{{--
    The button's real scenarios: how the everyday product screens actually use
    it — a settings-card footer, an icon toolbar, then the variant/size/effect
    strips. Everything is bilingual with the page locale and the numbers roll
    into Persian digits in fa.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $saved = $say('Saved', 'ذخیره شد').' · '.NabuXUI::formatNumber(3).' '.$say('changes', 'تغییر');
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A settings card, closing cleanly', 'کارت تنظیمات، با پایانی تمیز') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The primary action commits (watch the label become a spinner at the same width), the quiet one backs out, the dangerous one stands apart.', 'کنش اصلی ذخیره می‌کند (برچسب با همان عرض به اسپینر بدل می‌شود)، کنش آرام برمی‌گرداند و کنش خطرناک جدا می‌ایستد.') }}
        </p>
    </div>
    <div class="pg-row" style="justify-content: space-between">
        <div class="pg-row">
            <x-nx::button variant="primary" icon="check" wire:click="save(@js($saved))">{{ $say('Save changes', 'ذخیرهٔ تغییرات') }}</x-nx::button>
            <x-nx::button variant="ghost" wire:click="ping(@js($say('Nothing was changed', 'چیزی تغییر نکرد')))">{{ $say('Discard', 'دور انداختن') }}</x-nx::button>
        </div>
        <x-nx::button variant="danger" icon="trash" wire:click="ping(@js($say('Workspace deleted', 'ورک‌اسپیس حذف شد')))">{{ $say('Delete workspace', 'حذف ورک‌اسپیس') }}</x-nx::button>
    </div>
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Icon-only toolbar', 'نوار ابزار آیکونی') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">{{ $say('Same button, one glyph each — the label moves to aria-label.', 'همان دکمه، هرکدام یک نشانه — برچسب به aria-label می‌رود.') }}</p>
        <div class="pg-row">
            <x-nx::button variant="secondary" icon="plus" icon-only aria-label="{{ $say('New rule', 'قاعدهٔ تازه') }}" wire:click="ping(@js($say('A blank rule opened', 'یک قاعدهٔ خالی باز شد')))" />
            <x-nx::button variant="ghost" icon="edit" icon-only aria-label="{{ $say('Rename', 'تغییر نام') }}" wire:click="ping(@js($say('Renamed', 'نام تغییر کرد')))" />
            <x-nx::button variant="ghost" icon="copy" icon-only aria-label="{{ $say('Duplicate', 'تکثیر') }}" wire:click="ping(@js($say('Duplicated', 'تکثیر شد')))" />
            <x-nx::button variant="ghost" icon="trash" icon-only aria-label="{{ $say('Remove', 'حذف') }}" wire:click="save(@js($say('Removed', 'حذف شد')))" />
        </div>
    </section>

    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Variants', 'واریانت‌ها') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">{{ $say('From loud to quiet: primary, secondary, outline, ghost, danger, gold, inverse, link and glow.', 'از پرصداتاکوه: primary، secondary، outline، ghost، danger، gold، inverse، link و glow.') }}</p>
        <div class="pg-row">
            <x-nx::button variant="primary">{{ $say('Primary', 'اصلی') }}</x-nx::button>
            <x-nx::button variant="secondary">{{ $say('Secondary', 'ثانویه') }}</x-nx::button>
            <x-nx::button variant="outline">{{ $say('Outline', 'قاب‌دار') }}</x-nx::button>
            <x-nx::button variant="ghost">{{ $say('Ghost', 'شبح') }}</x-nx::button>
            <x-nx::button variant="danger">{{ $say('Danger', 'خطر') }}</x-nx::button>
            <x-nx::button variant="gold">{{ $say('Gold', 'طلایی') }}</x-nx::button>
            <x-nx::button variant="inverse">{{ $say('Inverse', 'وارونه') }}</x-nx::button>
            <x-nx::button variant="link">{{ $say('Link', 'لینک') }}</x-nx::button>
        </div>
    </section>
</div>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Sizes, one line', 'اندازه‌ها، در یک خط') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">{{ $say('xs to xl; press any of them — everything pressable scales down 4% while it is held.', 'از xs تا xl؛ فشار دهید — هر چیزی فشاردنی هنگام نگه‌داشتن ۴٪ کوچک می‌شود.') }}</p>
        <div class="pg-row" style="align-items: end">
            <x-nx::button size="xs">{{ $say('Tiny', 'ریزه') }}</x-nx::button>
            <x-nx::button size="sm">{{ $say('Small', 'کوچک') }}</x-nx::button>
            <x-nx::button>{{ $say('Medium', 'متوسط') }}</x-nx::button>
            <x-nx::button size="lg">{{ $say('Large', 'بزرگ') }}</x-nx::button>
            <x-nx::button size="xl" shape="pill">{{ $say('Hero', 'قهرمان') }}</x-nx::button>
        </div>
    </section>

    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Links & effects', 'لینک‌ها و افکت‌ها') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">{{ $say('href renders a real anchor; the effects are a passing gleam and a rolling label.', 'با href یک لنگر واقعی رندر می‌شود؛ افکت‌ها برقِ عبوری و برچسبِ غلتان هستند.') }}</p>
        <div class="pg-row" style="align-items: end">
            <x-nx::button variant="secondary" icon="grid" icon-end="arrow-right" href="/components" wire:navigate>{{ $say('Back to the catalog', 'بازگشت به کاتالوگ') }}</x-nx::button>
            <x-nx::button variant="primary" effect="shine">{{ $say('Shine', 'برق') }}</x-nx::button>
            <x-nx::button variant="secondary" effect="slide">{{ $say('Slide', 'سُرش') }}</x-nx::button>
        </div>
    </section>
</div>

<section class="pg-box" style="gap: 1rem">
    <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A full-width closer', 'پایان‌بند تمام‌عرض') }}</h3>
    <p style="margin: 0; color: var(--nx-text-muted)">{{ $say('block stretches to the container — the upgrade CTA at the end of a billing page.', 'با block دکمه کل ظرف را می‌گیرد — CTA ارتقا در انتهای صفحهٔ صورت‌حساب.') }}</p>
    <x-nx::button variant="gold" block size="lg" icon="sparkles" wire:click="ping(@js($say('Welcome to Pro', 'به پرو خوش آمدید')))">
        {{ $say('Upgrade to Pro · $۱۹/mo', 'ارتقا به پرو · ۱۹ دلار در ماه') }}
    </x-nx::button>
</section>
