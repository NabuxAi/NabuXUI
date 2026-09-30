{{--
    The workflow card's real scenarios: an automations dashboard — a nightly
    sync that is running live, a weekly digest that is paused, and a failed
    one awaiting a retry.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<div class="pg-grid">
    <section class="pg-box" style="gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The automations board', 'تابلوی خودکارسازی‌ها') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Hover, focus or tap a card: the details grow in and the actions slide in one by one. The running one breathes.', 'روی کارت مکث کنید یا لمسش کنید: جزئیات رشد می‌کنند و کنش‌ها یکی‌یکی سُر می‌خورند. کارتِ در حال اجرا نفس می‌کشد.') }}
            </p>
        </div>
        <x-nx::workflow-card
            :title="$say('Nightly inventory sync', 'همگام‌سازی شبانهٔ انبار')"
            :description="$say('Pull orders, update stock, ping #ops.', 'سفارش‌ها را بکش، موجودی را به‌روز کن، به #ops خبر بده.')"
            :status="$say('Running', 'در حال اجرا')" live icon="cpu"
            :members-label="$say('Watched by', 'ناظران')"
            :members="[['name' => 'نیلوفر احمدی'], ['name' => 'ماریا لوپز'], ['name' => 'پریا نایر']]"
            :meta="[
                $say('Trigger', 'راه‌انداز') => $fa ? 'هر روز · ۰۲:۰۰' : 'Every day · 02:00',
                $say('Last run', 'آخرین اجرا') => $say('3 min ago', '۳ دقیقه پیش'),
                $say('Success rate', 'نرخ موفقیت') => '۹۹٫۲٪',
            ]">
            <x-slot:actions>
                <x-nx::icon-button icon="play" :label="$say('Run now', 'همین حالا اجرا کن')" size="sm" wire:click="save(@js($say('Nightly sync started', 'همگام‌سازی شبانه شروع شد')))" />
                <x-nx::icon-button icon="edit" :label="$say('Edit', 'ویرایش')" size="sm" wire:click="ping(@js($say('Editor opened', 'ویرایشگر باز شد')))" />
                <x-nx::icon-button icon="copy" :label="$say('Duplicate', 'تکثیر')" size="sm" wire:click="ping(@js($say('Workflow duplicated', 'گردش‌کار تکثیر شد')))" />
            </x-slot:actions>
        </x-nx::workflow-card>
        <x-nx::workflow-card
            :title="$say('Weekly digest', 'گزارش هفتگی')"
            :description="$say('An AI summary to every team lead, Friday 09:00.', 'خلاصهٔ هوشمند برای هر رهبر تیم، جمعه‌ها ۰۹:۰۰.')"
            :status="$say('Paused', 'متوقف')" status-tone="warning" icon="mail"
            :members="[['name' => 'عمر حداد'], ['name' => 'لنا فیشر']]"
            :meta="[$say('Next send', 'ارسال بعدی') => $say('Friday · 09:00', 'جمعه · ۰۹:۰۰')]">
            <x-slot:actions>
                <x-nx::icon-button icon="play" :label="$say('Resume', 'واگیری')" size="sm" wire:click="save(@js($say('Digest resumed', 'گزارش واگیره شد')))" />
            </x-slot:actions>
        </x-nx::workflow-card>
    </section>

    <section class="pg-box" style="gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('One that needs a human', 'یکی که به آدم نیاز دارد') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('expanded keeps a card open for good — the failed run stays laid out with its retry one click away.', 'expanded کارت را برای همیشه باز نگه می‌دارد — اجرای ناموفق با دکمهٔ تلاش دوباره در یک کلیکی، همان‌جا باز می‌ماند.') }}
            </p>
        </div>
        <x-nx::workflow-card expanded
            :title="$say('Charge the cards at month end', 'شارژ کارت‌ها در پایان ماه')"
            :description="$say('The gateway rejected the last batch — 214 cards untouched.', 'دروازهٔ پرداخت دستهٔ آخر را رد کرد — ۲۱۴ کارت دست‌نخورده.')"
            :status="$say('Failed', 'ناموفق')" status-tone="danger" icon="alert-triangle"
            :meta="[
                $say('Failing since', 'ناموفق از') => $say('Sep 29 · 23:41', '۸ مهر · ۲۳:۴۱'),
                $say('Error', 'خطا') => $say('HTTP 402 · insufficient_scope', 'HTTP 402 · insufficient_scope'),
            ]">
            <x-slot:actions>
                <x-nx::button size="sm" variant="danger" icon="play" wire:click="save(@js($say('Retrying 214 cards…', 'تلاش دوباره برای ۲۱۴ کارت…')))">{{ $say('Retry batch', 'تلاش دوباره') }}</x-nx:button>
            </x-slot:actions>
        </x-nx::workflow-card>
    </section>
</div>
