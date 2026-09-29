{{--
    <x-nx::stat-strip :stats="[
        ['label' => 'Designs', 'value' => 2400, 'icon' => 'layers', 'caption' => '+120 this month'],
        ['label' => 'Designers', 'value' => 1400, 'icon' => 'users'],
        ['label' => 'Categories', 'value' => 40, 'icon' => 'grid'],
        ['label' => 'Platforms', 'value' => 5, 'icon' => 'globe'],
    ]" />

    A compact band of stats that counts up when it scrolls into view. The
    figures are server-rendered with the current locale's digits; the
    nxStatStrip behaviour holds each one at zero until it is seen, then rolls
    it up once. Change a stat's `value` on the server and the digits roll to
    the new number after the morph.
--}}
@props([
    'stats' => [],
    'label' => null,
])
@php
    use NabuXUI\NabuXUI;
    $locale = str_replace('_', '-', app()->getLocale());
    $lang = substr($locale, 0, 2);
    $t = fn (string $en, string $fa, string $ar) => match ($lang) { 'fa' => $fa, 'ar' => $ar, default => $en };
    $label ??= $t('Stats', 'آمارها', 'الإحصائيات');
    $stats = array_values(array_map(fn ($stat) => [
        'label' => (string) $stat['label'],
        'value' => (float) ($stat['value'] ?? 0),
        'icon' => isset($stat['icon']) && NabuXUI::hasIcon($stat['icon']) ? $stat['icon'] : null,
        'caption' => isset($stat['caption']) ? (string) $stat['caption'] : null,
    ], $stats));
@endphp
<dl {{ $attributes->class('nx-stat-strip')->merge(['aria-label' => $label, 'data-nx-reveal' => 'group']) }} x-data="nxStatStrip(@js($locale))">
    @foreach ($stats as $i => $stat)
        @php $parts = NabuXUI::numberParts($stat['value'], 0, $locale); @endphp
        <div class="nx-stat-strip-item" style="--nx-i: {{ $i }}">
            @if ($stat['icon'])
                <span class="nx-stat-strip-icon" aria-hidden="true">{{ NabuXUI::icon($stat['icon']) }}</span>
            @endif
            <div class="nx-stat-strip-what">
                <dt class="nx-stat-strip-label">{{ $stat['label'] }}</dt>
                <dd class="nx-stat-strip-value">
                    <span class="nx-number" data-nx-reveal data-value="{{ $stat['value'] }}">
                        <span class="nx-visually-hidden">{{ NabuXUI::formatNumber($stat['value'], 0, $locale) }}</span>
                        <span class="nx-number-roll" aria-hidden="true">@foreach ($parts as $j => $part)@if ($part['kind'] === 'digit')<span class="nx-digit" style="--d: {{ $part['value'] }}; --nx-p: {{ count($parts) - 1 - $j }}"><span class="nx-digit-track">@foreach (NabuXUI::digits($locale) as $digit)<span>{{ $digit }}</span>@endforeach</span></span>@else<span class="nx-number-sep">{{ $part['char'] }}</span>@endif @endforeach</span>
                    </span>
                    @if ($stat['caption'])
                        <span class="nx-stat-strip-caption">{{ $stat['caption'] }}</span>
                    @endif
                </dd>
            </div>
        </div>
    @endforeach
</dl>
