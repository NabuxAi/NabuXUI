{{--
    <x-nx::currency-converter title="Convert" :amount="1000" from="USD" to="EUR" note="Mid-market · demo rates"
        :rates="['USD' => 1, 'EUR' => 0.92, 'JPY' => 149.8, 'INR' => 83.2]" wire:model.live="state.fx" />

    Rates are quoted against any common base and come in through props: nothing is fetched.
    wire:model goes on the component: it binds ['amount' => …, 'from' => …, 'to' => …] through
    x-modelable. Amounts are read in any numbering system ("۱۲٬۵۰۰" works) and shown in the locale's.
--}}
@props(['rates' => [], 'currencies' => null, 'amount' => 1000, 'from' => null, 'to' => null, 'title' => null, 'note' => null, 'labels' => []])
@php
    use NabuXUI\NabuXUI;
    $locale = str_replace('_', '-', app()->getLocale());
    $lang = substr($locale, 0, 2);
    $words = [
        'fa' => ['from' => 'از', 'to' => 'به', 'swap' => "جابه\u{200C}جایی ارزها", 'currency' => 'ارز', 'rate' => '۱ :from = :rate :to'],
        'ar' => ['from' => 'من', 'to' => 'إلى', 'swap' => 'تبديل العملات', 'currency' => 'العملة', 'rate' => '١ :from = :rate :to'],
    ][$lang] ?? ['from' => 'From', 'to' => 'To', 'swap' => 'Swap currencies', 'currency' => 'Currency', 'rate' => '1 :from = :rate :to'];
    $words = array_merge($words, $labels);
    $codes = array_values($currencies ?? array_keys($rates));
    $from ??= $codes[0] ?? 'USD';
    $to ??= $codes[1] ?? $from;
    $id = NabuXUI::id('nx-fx');
    $rate = ($rates[$from] ?? 0) > 0 && ($rates[$to] ?? 0) > 0 ? $rates[$to] / $rates[$from] : null;
    $result = $rate ? (float) $amount * $rate : 0;
    $money = function (float $value, string $code) use ($locale) {
        if (class_exists(\NumberFormatter::class)) {
            return (string) (new \NumberFormatter($locale, \NumberFormatter::CURRENCY))->formatCurrency($value, $code);
        }
        return NabuXUI::formatNumber($value, 2, $locale).' '.$code;
    };
    $rateText = str_replace([':from', ':rate', ':to'], [$from, $rate ? NabuXUI::formatNumber($rate, 4, $locale) : '—', $to], $words['rate']);
    $config = ['rates' => $rates, 'amount' => (float) $amount, 'from' => $from, 'to' => $to, 'locale' => $locale, 'rate' => $words['rate']];
@endphp
<section {{ $attributes->class('nx-currency-converter')->merge(['data-nx-reveal' => '', 'aria-labelledby' => $title ? "{$id}-title" : null]) }}
    x-data="nxCurrencyConverter(@js($config))" x-modelable="model" wire:ignore>
    @if ($title)
        <header class="nx-currency-converter-head"><p class="nx-currency-converter-title" id="{{ $id }}-title">{{ $title }}</p></header>
    @endif
    <div class="nx-currency-converter-rows">
        <div class="nx-currency-converter-row" data-row="from" x-ref="from">
            <label class="nx-currency-converter-label" for="{{ $id }}-amount">{{ $words['from'] }}</label>
            <input id="{{ $id }}-amount" class="nx-currency-converter-amount" inputmode="decimal" autocomplete="off" spellcheck="false"
                value="{{ NabuXUI::formatNumber((float) $amount, floor((float) $amount) == (float) $amount ? 0 : 2, $locale) }}" x-model="text">
            <select class="nx-select nx-currency-converter-select" data-size="sm" aria-label="{{ $words['from'] }} · {{ $words['currency'] }}" x-model="from">
                @foreach ($codes as $code)<option value="{{ $code }}" @selected($code === $from)>{{ $code }}</option>@endforeach
            </select>
        </div>
        <button type="button" class="nx-currency-converter-swap" aria-label="{{ $words['swap'] }}" x-bind:style="{ '--_turns': turns }" x-on:click="swap()">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 4v16M3.5 7.5 7 4l3.5 3.5M17 20V4m3.5 12.5L17 20l-3.5-3.5"/></svg>
        </button>
        <div class="nx-currency-converter-row" data-row="to" x-ref="to">
            <span class="nx-currency-converter-label" id="{{ $id }}-to">{{ $words['to'] }}</span>
            <output class="nx-currency-converter-result" for="{{ $id }}-amount" aria-labelledby="{{ $id }}-to">
                <span class="nx-number" x-ref="result"><span class="nx-visually-hidden">{{ $money($result, $to) }}</span><span class="nx-number-roll" aria-hidden="true">{{ $money($result, $to) }}</span></span>
            </output>
            <select class="nx-select nx-currency-converter-select" data-size="sm" aria-label="{{ $words['to'] }} · {{ $words['currency'] }}" x-model="to">
                @foreach ($codes as $code)<option value="{{ $code }}" @selected($code === $to)>{{ $code }}</option>@endforeach
            </select>
        </div>
    </div>
    <p class="nx-currency-converter-rate">
        <span><strong x-ref="rate">{{ $rateText }}</strong></span>
        @if ($note)<span>{{ $note }}</span>@endif
    </p>
</section>
