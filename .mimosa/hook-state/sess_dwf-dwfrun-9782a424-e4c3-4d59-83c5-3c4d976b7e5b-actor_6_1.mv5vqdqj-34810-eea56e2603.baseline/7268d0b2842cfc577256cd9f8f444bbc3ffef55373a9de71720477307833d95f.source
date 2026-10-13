{{--
    A CSS donut chart — the segments sweep in when the chart scrolls into view:
        <x-nx::donut-chart title="Traffic" :data="['Web' => 42, 'Mobile' => 26, 'API' => 12]" />
    The centre rolls the total (or your own `centerValue`, labelled by `centerLabel` —
    "Total" by default, '' hides it); each legend row shows its value and rolls its
    share of the whole, in the locale's digits. No JavaScript: every segment is a
    conic-gradient stop scaled by the registered --nx-donut-sweep property, which
    transitions when [data-nx-revealed] lands — one segment after another. The same
    data ships as a visually-hidden table. `size` and `thickness` are any CSS lengths
    (numbers mean px and % of the radius).
--}}
@props(['data' => [], 'title' => null, 'subtitle' => null, 'centerValue' => null, 'centerLabel' => null, 'size' => null, 'thickness' => null, 'locale' => null])
@php
    use NabuXUI\NabuXUI;

    $locale = str_replace('_', '-', $locale ?? app()->getLocale());
    $lang = substr($locale, 0, 2);
    // The chart's own words live in the core i18n table (resources/lang, generated from it).
    $say = fn (string $key) => __('nabuxui::ui.'.$key, [], $lang);
    $chartId = NabuXUI::id('nx-donut-chart');

    $items = [];
    foreach (is_array($data) ? $data : [] as $label => $value) {
        $items[] = ['label' => (string) $label, 'value' => max(0.0, (float) $value)];
    }

    // Ring segments on the shared 0–100 turn scale — the same maths the core's
    // donutSegments runs for the SVG donut, including the surface gap.
    $segments = NabuXUI::donut(array_column($items, 'value'));
    $total = array_sum(array_column($items, 'value'));

    // One decimal only when a value asks for it (heatmap rule).
    $decimals = 0;
    foreach ($items as $item) {
        if (floor($item['value']) != $item['value']) { $decimals = 1; break; }
    }
    $fmt = fn (float $v) => NabuXUI::formatNumber($v, $decimals, $locale);

    // The locale's percent mark (٪ for fa/ar), for the shares.
    $percentSign = '%';
    if (class_exists(\NumberFormatter::class)) {
        $percentSign = (new \NumberFormatter($locale, \NumberFormatter::PERCENT))->getSymbol(\NumberFormatter::PERCENT_SYMBOL) ?: '%';
    }

    // Whole percents, as rolling digit columns plus the mark.
    $shareParts = fn (float $share) => NabuXUI::numberParts(round($share * 100), 0, $locale);
    $shareText = fn (float $share) => NabuXUI::formatNumber(round($share * 100), 0, $locale).$percentSign;
    $digits = NabuXUI::digits($locale);

    $center = $centerValue !== null ? (float) $centerValue : $total;
    $caption = $centerLabel ?? $say('donutChartTotal');
    $centerParts = NabuXUI::numberParts($center, $decimals, $locale);

    $styles = [];
    if ($size !== null) $styles[] = '--nx-donut-chart-size: '.(is_numeric($size) ? "{$size}px" : (string) $size);
    if ($thickness !== null) $styles[] = '--nx-donut-chart-thickness: '.(is_numeric($thickness) ? "{$thickness}%" : (string) $thickness);
@endphp
<figure {{ $attributes->class('nx-chart nx-donut-chart')->merge([
    'aria-labelledby' => $title ? "{$chartId}-title" : null,
    'data-nx-reveal' => '',
    'style' => $styles === [] ? null : implode('; ', $styles),
]) }} x-data x-nx-reveal>
    @if ($title || $subtitle)
        <div class="nx-chart-head">
            <div>
                @if ($title)<p class="nx-chart-title" id="{{ $chartId }}-title">{{ $title }}</p>@endif
                @if ($subtitle)<p class="nx-chart-subtitle">{{ $subtitle }}</p>@endif
            </div>
        </div>
    @endif
    <div class="nx-donut-chart-frame">
        <div class="nx-donut-chart-ring" aria-hidden="true">
            <span class="nx-donut-chart-track"></span>
            @foreach ($segments as $i => $segment)
                <span class="nx-donut-chart-segment"
                    style="--_start: {{ -$segment['offset'] }}; --_len: {{ $segment['length'] }}; --nx-i: {{ $i }}; --nx-series: var(--nx-chart-{{ $i % 7 + 1 }})"></span>
            @endforeach
            <div class="nx-donut-chart-center">
                <span class="nx-number nx-donut-chart-value" data-nx-reveal x-data x-nx-reveal>
                    <span class="nx-visually-hidden">{{ NabuXUI::formatNumber($center, $decimals, $locale) }}</span>
                    <span class="nx-number-roll" aria-hidden="true">@foreach ($centerParts as $j => $part)@if ($part['kind'] === 'digit')<span class="nx-digit" style="--d: {{ $part['value'] }}; --nx-p: {{ count($centerParts) - 1 - $j }}"><span class="nx-digit-track">@foreach ($digits as $digit)<span>{{ $digit }}</span>@endforeach</span></span>@else<span class="nx-number-sep">{{ $part['char'] }}</span>@endif @endforeach</span>
                </span>
                @if ($caption !== '')<span class="nx-donut-chart-caption">{{ $caption }}</span>@endif
            </div>
        </div>
        <ul class="nx-chart-legend nx-donut-chart-legend">
            @foreach ($items as $i => $item)
                @php $share = $segments[$i]['share']; @endphp
                <li class="nx-legend-item nx-donut-chart-row" style="--nx-i: {{ $i }}">
                    <span class="nx-legend-swatch" style="--nx-series: var(--nx-chart-{{ $i % 7 + 1 }})"></span>
                    <span class="nx-donut-chart-row-label">{{ $item['label'] }}</span>
                    <span class="nx-donut-chart-row-value">{{ $fmt($item['value']) }}</span>
                    <span class="nx-number nx-donut-chart-row-share" data-nx-reveal x-data x-nx-reveal>
                        <span class="nx-visually-hidden">{{ $shareText($share) }}</span>
                        <span class="nx-number-roll" aria-hidden="true">@foreach ($shareParts($share) as $j => $part)@if ($part['kind'] === 'digit')<span class="nx-digit" style="--d: {{ $part['value'] }}; --nx-p: {{ count($shareParts($share)) - 1 - $j }}"><span class="nx-digit-track">@foreach ($digits as $digit)<span>{{ $digit }}</span>@endforeach</span></span>@else<span class="nx-number-sep">{{ $part['char'] }}</span>@endif @endforeach<span class="nx-number-sep">{{ $percentSign }}</span></span>
                    </span>
                </li>
            @endforeach
        </ul>
    </div>
    <table class="nx-visually-hidden">
        @if ($title)<caption>{{ $title }}</caption>@endif
        <thead><tr><td></td><th scope="col">{{ $say('donutChartValue') }}</th><th scope="col">{{ $say('donutChartShare') }}</th></tr></thead>
        <tbody>
            @foreach ($items as $i => $item)
                <tr><th scope="row">{{ $item['label'] }}</th><td>{{ $fmt($item['value']) }}</td><td>{{ $shareText($segments[$i]['share']) }}</td></tr>
            @endforeach
        </tbody>
    </table>
</figure>
