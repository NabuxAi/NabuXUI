{{-- A number whose digits roll. Bind it: <x-nx::number :value="$total" /> (re-rendered by Livewire) or live with x-model. --}}
@props(['value' => 0, 'decimals' => 0, 'locale' => null, 'reveal' => true])
@php $parts = \NabuXUI\NabuXUI::numberParts($value, (int) $decimals, $locale); @endphp
@php $digits = \NabuXUI\NabuXUI::digits($locale); @endphp
<span {{ $attributes->class('nx-number')->merge(['data-nx-reveal' => $reveal ? '' : null]) }} @if ($reveal) x-data x-nx-reveal @endif>
    <span class="nx-visually-hidden">{{ \NabuXUI\NabuXUI::formatNumber($value, (int) $decimals, $locale) }}</span>
    <span class="nx-number-roll" aria-hidden="true">@foreach ($parts as $i => $part)@if ($part['kind'] === 'digit')<span class="nx-digit" style="--d: {{ $part['value'] }}; --nx-p: {{ count($parts) - 1 - $i }}"><span class="nx-digit-track">@foreach ($digits as $digit)<span>{{ $digit }}</span>@endforeach</span></span>@else<span class="nx-number-sep">{{ $part['char'] }}</span>@endif @endforeach</span>
</span>
