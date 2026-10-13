{{--
    Fill button's real scenarios: the footer of a pricing card (the classic
    home of a filling CTA), then a page-bottom navigation row where the fill
    enters from whichever side the pointer does.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The pricing card’s closer', 'پایان‌بند کارت قیمت') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('The Pro plan: the fill floods in from where the pointer entered and the label rolls over to its second line.', 'پلن حرفه‌ای: پرشده از همان سمتی که موس آمد سرریز می‌کند و برچسب به خط دومش می‌غلتد.') }}
        </p>
        <div style="display: grid; gap: .75rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); padding: 1.25rem">
            <div class="pg-row" style="justify-content: space-between">
                <strong style="font: 700 var(--nx-text-lg) / 1 var(--nx-font-display)">{{ $say('Pro', 'حرفه‌ای') }}</strong>
                <span style="font: 700 var(--nx-text-xl) / 1 var(--nx-font-display)">
                    {{ NabuXUI::formatNumber(19) }} <span style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $say('$ / mo', 'دلار / ماه') }}</span>
                </span>
            </div>
            <ul role="list" style="margin: 0; padding: 0; display: grid; gap: .35rem; color: var(--nx-text-muted); font-size: var(--nx-text-sm)">
                <li>{{ $say('Unlimited projects', 'پروژهٔ نامحدود') }}</li>
                <li>{{ $say('All '.NabuXUI::formatNumber(5).' frameworks', 'هر '.NabuXUI::formatNumber(5).' فریم‌ورک') }}</li>
            </ul>
            <x-nx::fill-button variant="gold" block :hover-label="$say('Let’s begin', 'شروع کنیم')" wire:click="save(@js($say('Welcome to Pro', 'به حرفه‌ای خوش آمدی')))">
                {{ $say('Start free trial', 'شروع دورهٔ آزمایشی') }}
            </x-nx::fill-button>
        </div>
    </section>

    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A row of them', 'یک ردیف از آن‌ها') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('variant runs accent · inverse · gold; one of them is a real link — the fill plays on anchors exactly the same way.', 'واریانت‌ها accent · inverse · gold هستند؛ یکی‌شان لینک واقعی است — پرشده روی لنگرها هم دقیقاً همین‌طور کار می‌کند.') }}
        </p>
        <div class="pg-row" style="align-items: start; gap: 1rem">
            <x-nx::fill-button icon-end="arrow-right" :hover-label="$say('Everything included', 'همه‌چیز داخلش است')" wire:click="ping(@js($say('Exploring…', 'در حال کاوش…')))">
                {{ $say('Explore', 'کاوش') }}
            </x-nx::fill-button>
            <x-nx::fill-button variant="inverse" :hover-label="$say(NabuXUI::formatNumber(12000).' builders', NabuXUI::formatNumber(12000).' سازنده')" wire:click="ping(@js($say('Subscribed', 'مشترک شدی')))">
                {{ $say('Subscribe', 'اشتراک') }}
            </x-nx::fill-button>
            <x-nx::fill-button variant="gold" shape="rounded" href="/components" wire:navigate>
                {{ $say('All demos', 'همهٔ دموها') }}
            </x-nx::fill-button>
        </div>
        <p style="margin: 0; color: var(--nx-text-subtle); font-size: var(--nx-text-sm)">
            {{ $say('Enter each one from a different side — the circle always starts where you did.', 'از یک سمت متفاوت وارد هرکدام شوید — دایره همیشه از همان‌جا شروع می‌کند که شما آمدید.') }}
        </p>
    </section>
</div>
