{{--
    Transaction button's real scenarios: a checkout card where the button pays
    through the server (wire:loading drives it), the local state machine that
    declines every other press, and a server-driven row for real gateways.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $labels = [
        'idle' => $say('Pay $'.NabuXUI::formatNumber(49), 'پرداخت '.NabuXUI::formatNumber(49).' دلار'),
        'loading' => $say('Processing…', 'در حال پردازش…'),
        'success' => $say('Paid', 'پرداخت شد'),
        'error' => $say('Declined', 'رد شد'),
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A checkout card', 'کارت تسویه') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The wire:click one runs the real request: the label becomes "Processing", the pixels ripple and the width springs to fit the words — then the toast lands. The one beside it plays the whole machine locally and declines every other press.', 'آنکه wire:click دارد درخواست واقعی را اجرا می‌کند: برچسب «در حال پردازش» می‌شود، پیکسل‌ها موج می‌زنند و عرض با کلمات فنری می‌شود — بعد توست می‌آید. کناری کل ماشین‌حالت را محلی اجرا می‌کند و هر بار دیگری رد می‌شود.') }}
        </p>
    </div>
    <div style="display: grid; gap: 1rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); padding: 1.25rem; max-inline-size: 26rem">
        <div class="pg-row" style="justify-content: space-between">
            <span style="color: var(--nx-text-muted)">{{ $say('NabuXUI Pro · yearly', 'نابوای‌یو‌آی حرفه‌ای · سالانه') }}</span>
            <strong style="font: 700 var(--nx-text-lg) / 1 var(--nx-font-display)">{{ NabuXUI::formatNumber(49) }} $</strong>
        </div>
        <div class="pg-row" x-data="{ fail: false }">
            <x-nx::transaction-button :labels="$labels" wire:click="save(@js($say('Receipt sent to your inbox', 'رسید به صندوق‌تان آمد')))" />
            <div>
                <x-nx::transaction-button :labels="$labels" wire:ignore.self
                    x-on:click="if (current !== 'idle') return; set('loading'); setTimeout(() => { set(fail ? 'error' : 'success'); fail = ! fail }, 1800); setTimeout(() => set('idle'), 4200)" />
            </div>
        </div>
        <p style="margin: 0; color: var(--nx-text-subtle); font-size: var(--nx-text-sm)">
            {{ $say('Left: the server round-trip. Right: the local machine.', 'چپ: رفت‌وبرگشت سرور. راست: ماشین محلی.') }}
        </p>
    </div>
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Driven by a real gateway', 'هدایت با درگاه واقعی') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('When your gateway answers asynchronously, set the status from the server and the same animation plays.', 'وقتی درگاه‌تان بی‌هم‌زمان جواب می‌دهد، وضعیت را از سرور بدهید تا همان انیمیشن اجرا شود.') }}
        </p>
        <div class="pg-row">
            <x-nx::transaction-button size="sm" :status="$state['pay'] ?? null" :labels="[
                'idle' => $say('Checkout', 'تسویه'),
                'loading' => $say('Verifying…', 'در حال تأیید…'),
                'success' => $say('Order placed', 'سفارش ثبت شد'),
                'error' => $say('Card declined', 'کارت رد شد'),
            ]" />
        </div>
        <div class="pg-row">
            <x-nx::button size="sm" variant="ghost" wire:click="$set('state.pay', 'loading')">{{ $say('Verifying', 'در حال تأیید') }}</x-nx::button>
            <x-nx::button size="sm" variant="ghost" wire:click="$set('state.pay', 'success')">{{ $say('Paid', 'پرداخت شد') }}</x-nx::button>
            <x-nx::button size="sm" variant="ghost" wire:click="$set('state.pay', 'error')">{{ $say('Declined', 'رد شد') }}</x-nx:button>
            <x-nx::button size="sm" variant="ghost" wire:click="$set('state.pay', null)">{{ $say('Reset', 'بازنشانی') }}</x-nx::button>
        </div>
    </section>

    <section class="pg-box" dir="rtl" lang="fa" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Right to left', 'راست‌به‌چپ') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('The lock, the ripple and the rolling label all behave the same in RTL — the label direction follows the text.', 'قفل، موج و برچسب غلتان در RTL همان‌طور رفتار می‌کنند — جهت برچسب از متن پیروی می‌کند.') }}
        </p>
        <div class="pg-row">
            <div x-data>
                <x-nx::transaction-button :labels="['idle' => 'پرداخت ۴۹ دلار', 'loading' => 'در حال پردازش…', 'success' => 'پرداخت شد', 'error' => 'رد شد']" wire:ignore.self
                    x-on:click="if (current !== 'idle') return; set('loading'); setTimeout(() => set('success'), 1800); setTimeout(() => set('idle'), 4000)" />
            </div>
        </div>
    </section>
</div>
