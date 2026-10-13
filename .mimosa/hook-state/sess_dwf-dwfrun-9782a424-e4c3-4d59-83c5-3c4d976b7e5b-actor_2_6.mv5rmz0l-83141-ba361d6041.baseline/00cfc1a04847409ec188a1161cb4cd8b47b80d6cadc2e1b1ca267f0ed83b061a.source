{{--
    Alerts as the morning of a real dashboard: a quota warning you can act on
    and dismiss, a maintenance notice that waits, and a paid bill that will
    leave on its own when you reload.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The top of the billing page', 'بالای صفحهٔ صورت‌حساب') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Loudest first, each with its own job: warn with a way out, inform with a date, reassure with a receipt.', 'بلندترین اول، هرکدام با کار خودش: هشدار با راه خروج، خبر با تاریخ، اطمینان با رسید.') }}
        </p>
    </div>

    <x-nx::alert tone="warning" :title="$say('This workspace is at 91% of its storage', 'این ورک‌اسپیس در ۹۱٪ فضای ذخیره است')" dismissible>
        {{ $say('2.7 GB left of 30 GB. Old teasers alone eat 9 GB.', '۲٫۷ گیگابایت از ۳۰ مانده. تیزرهای قدیمی به‌تنهایی ۹ گیگ می‌خورند.') }}
        <x-slot:actions>
            <x-nx::button size="sm" variant="secondary" wire:click="save('{{ $say('Old media moved to cold storage', 'رسانه‌های قدیدی به انبار سرد رفت') }}')">{{ $say('Archive old media', 'بایگانی رسانه‌های قدیمی') }}</x-nx::button>
            <x-nx::button size="sm" variant="ghost" href="/components" wire:navigate>{{ $say('See plans', 'دیدن پلن‌ها') }}</x-nx::button>
        </x-slot:actions>
    </x-nx::alert>

    <x-nx::alert tone="info" :title="$say('Maintenance this Thursday, 02:00–02:20', 'سرویس این پنجشنبه، ۰۲:۰۰ تا ۰۲:۲۰')">
        {{ $say('API stays up; deploys queue and run after.', 'API روشن می‌ماند؛ دپلویها صف می‌شوند و بعدش اجرا می‌شوند.') }}
    </x-nx::alert>

    <x-nx::alert tone="success" :title="$say('Invoice 1404 was paid', 'فاکتور ۱۴۰۴ پرداخت شد')" dismissible>
        {{ NabuXUI::formatNumber(48260000).' '.$say('Toman charged to the card ending 1404.', 'تومان از کارت منتهی به ۱۴۰۴ برداشت شد.') }}
    </x-nx::alert>
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Tones and silences', 'رنگ‌ها و سکوت‌ها') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">{{ $say('Every tone picks its own icon; icon=false removes it when the words are enough.', 'هر رنگ آیکون خودش را برمی‌دارد؛ با icon=false وقتی کلمات کافی‌اند حذفش کنید.') }}</p>
        <x-nx::alert tone="neutral">{{ $say('Draft autosaved.', 'پیش‌نویس خودکار ذخیره شد.') }}</x-nx::alert>
        <x-nx::alert tone="accent" :icon="false">{{ $say('Nabu 5 shipped — 34 fixes.', 'نابو ۵ منتشر شد — ۳۴ اصلاح.') }}</x-nx::alert>
        <x-nx::alert tone="danger" :title="$say('The crawler lost its token', 'خزنده توکنش را گم کرد')">{{ $say('Reconnect it from Settings → Integrations.', 'از تنظیمات → اتصال‌ها دوباره وصلش کنید.') }}</x-nx::alert>
    </section>

    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Solid variant for the win wall', 'واریانت solid برای دیوار پیروزی') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">{{ $say('variant="solid" for moments that must not be missed — the trial’s last day.', 'با variant="solid" برای لحظه‌هایی که نباید از دست بروند — آخرین روز آزمایشی.') }}</p>
        <x-nx::alert tone="accent" variant="solid" :title="$say('Last day of your Pro trial', 'آخرین روز آزمایشی پرو')">
            {{ $say('Tonight the gold features pause — the shop stays open.', 'امشب امکانات طلایی می‌ایستند — فروشگاه باز می‌ماند.') }}
            <x-slot:actions>
                <x-nx::button size="sm" variant="primary" wire:click="save('{{ $say('Pro continued', 'پرو ادامه یافت') }}')">{{ $say('Keep Pro', 'پرو را نگه دار') }}</x-nx::button>
            </x-slot:actions>
        </x-nx::alert>
    </section>
</div>
