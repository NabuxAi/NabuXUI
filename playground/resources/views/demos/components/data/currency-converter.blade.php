{{--
    The currency converter's real scenarios: paying an overseas contractor
    (amount typed in Persian digits, state on the server), and a second
    converter on a different base for a travel budget.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $rates = ['USD' => 1, 'EUR' => 0.92, 'JPY' => 149.8, 'INR' => 83.2, 'BRL' => 5.02, 'KRW' => 1335, 'TRY' => 32.4, 'AED' => 3.6725, 'GBP' => 0.79, 'CNY' => 7.24];
@endphp

<div class="pg-grid">
    <section class="pg-box" style="gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Paying the Berlin illustrator', 'پرداخت به تصویرگر برلینی') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('The amount reads any numbering system — type «۱٬۲۵۰» and it lands as 1,250 — and every keystroke, currency swap and flip rides one Livewire property.', 'مبلغ را با هر سیستم عددی می‌خواند — «۱٬۲۵۰» بنویسید تا 1,250 بفهمد — و هر کلید، هر تعویض ارز و هر فلیپ روی یک پراپرتی Livewire می‌رود.') }}
            </p>
        </div>
        <x-nx::currency-converter
            :title="$say('Convert', 'تبدیل')"
            :rates="$rates" :amount="1250" from="USD" to="EUR"
            :note="$say('Mid-market · demo rates', 'نرخ میانی · نرخ‌های نمایشی')"
            wire:model.live="state.fx" />
        <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
            {{ $say('On the server (wire:model.live):', 'روی سرور (wire:model.live):') }}
            <code>{{ json_encode($state['fx'] ?? null, JSON_UNESCAPED_UNICODE) }}</code>
        </p>
        <x-nx::button size="sm" variant="secondary" icon="check" wire:click="save(@js($say('Payment scheduled for Tuesday', 'پرداخت برای سه‌شنبه زمان‌بندی شد')))">{{ $say('Schedule this payout', 'زمان‌بندی این پرداخت') }}</x-nx::button>
    </section>

    <section class="pg-box" style="gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The Kyoto travel budget', 'بودجهٔ سفر کیوتو') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('A second, independent converter quoted against JPY — the block never fetches anything; the numbers are yours.', 'یک مبدل مستقل دوم با پایهٔ ین — بلوک هیچ چیزی را نمی‌گیرد؛ عددها از خودتان‌اند.') }}
            </p>
        </div>
        <x-nx::currency-converter
            :rates="$rates" :amount="86000" from="JPY" to="USD"
            :title="$say('Daily budget', 'بودجهٔ روزانه')"
            :note="$say('Cash & cards together', 'پول نقد و کارت با هم')" />
    </section>
</div>
