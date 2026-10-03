{{--
    <x-nx::odometer :value="$revenue" />                          (rolls whenever Livewire re-renders a new value)
    <x-nx::odometer :value="1284" :format="['style' => 'currency', 'currency' => 'USD']" locale="en" />

    A number whose digits roll in masked columns — up the wheel when it grows,
    down when it shrinks — in the locale's own digits (Persian under fa). It
    starts rolling when scrolled into view. set(n) changes it from Alpine.
--}}
@props([
    'value' => 0,
    'from' => 0,
    'locale' => null,
    'format' => [],
    'decimals' => 0,
    'reveal' => true,
])
@php
    use NabuXUI\NabuXUI;
    $locale ??= app()->getLocale();
    $format = (array) $format;
    if ($decimals > 0) {
        $format += ['minimumFractionDigits' => (int) $decimals, 'maximumFractionDigits' => (int) $decimals];
    }
    $start = $reveal ? $from : $value;
    $parts = NabuXUI::numberParts($start, (int) $decimals, $locale);
@endphp
<span {{ $attributes->class('nx-odometer')->merge(['data-value' => $value]) }}
    x-data="nxOdometer(@js(['from' => $from, 'locale' => $locale, 'format' => (object) $format, 'reveal' => (bool) $reveal]))">
    <span class="nx-odometer-sr nx-visually-hidden" wire:ignore>{{ NabuXUI::formatNumber($value, (int) $decimals, $locale) }}</span>
    <span class="nx-odometer-roll" aria-hidden="true" wire:ignore>@foreach ($parts as $part)<span class="nx-odometer-col" data-kind="{{ $part['kind'] }}"><span class="nx-odometer-cell">{{ $part['char'] }}</span></span>@endforeach</span>
</span>
