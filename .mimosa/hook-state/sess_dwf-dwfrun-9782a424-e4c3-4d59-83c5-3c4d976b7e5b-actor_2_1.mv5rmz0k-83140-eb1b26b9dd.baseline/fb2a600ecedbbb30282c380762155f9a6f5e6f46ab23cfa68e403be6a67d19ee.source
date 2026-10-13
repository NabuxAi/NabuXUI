{{--
    A semicircle dial with a spring needle and a rolling reading:
        <x-nx::gauge :value="62" unit="٪" title="مصرف ماهانه" subtitle="بازهٔ ۱ تا ۱۰۰" />
    Custom bands (each takes `upTo`, `label`, `tone` — success|warning|danger|info|accent):
        <x-nx::gauge :value="91" :zones="[
            ['upTo' => 50, 'tone' => 'info', 'label' => 'آرام'],
            ['upTo' => 80, 'tone' => 'success'],
            ['tone' => 'danger'],
        ]" zone-label="" />

    No JavaScript of its own: the sweep and the needle ride custom properties
    (--_sweep, --_angle) with CSS transitions, so a Livewire morph that changes
    the value re-runs them from wherever they left off, and the digits re-roll
    through <x-nx::number>. The zone the value sits in colours the sweep, the
    hub dot and the caption. The same data ships as a visually-hidden table.
--}}
@props([
    'value' => 0,
    'min' => 0,
    'max' => 100,
    'unit' => null,
    'title' => null,
    'subtitle' => null,
    'zones' => null,
    'decimals' => null,
    'size' => null,
    'zoneLabel' => null,
    'locale' => null,
    'labels' => [],
])
@php
    use NabuXUI\NabuXUI;

    $locale = str_replace('_', '-', $locale ?? app()->getLocale());
    $lang = substr($locale, 0, 2);
    // The gauge's own words live in the core i18n table (resources/lang, generated from it).
    $words = [
        'value' => __('nabuxui::ui.gaugeValue', [], $lang),
        'zone' => __('nabuxui::ui.gaugeZone', [], $lang),
        'range' => __('nabuxui::ui.gaugeRange', [], $lang),
        'safe' => __('nabuxui::ui.gaugeZoneSafe', [], $lang),
        'caution' => __('nabuxui::ui.gaugeZoneCaution', [], $lang),
        'critical' => __('nabuxui::ui.gaugeZoneCritical', [], $lang),
    ];
    $words = array_merge($words, is_array($labels) ? $labels : []);
    $say = fn (string $key) => (string) $words[$key];

    $id = NabuXUI::id('nx-gauge');

    $lo = min((float) $min, (float) $max);
    $hi = max((float) $min, (float) $max);
    $span = ($hi - $lo) ?: 1.0;
    $shown = min($hi, max($lo, (float) $value));
    $fraction = ($shown - $lo) / $span;
    $angle = round($fraction * 180 - 90, 2);
    $sweep = round($fraction * 100, 2);

    // One decimal only when the data asks for it (heatmap rule).
    $digits = (int) ($decimals ?? ((floor($shown) == $shown && floor($lo) == $lo && floor($hi) == $hi) ? 0 : 1));
    $fmt = fn ($v) => NabuXUI::formatNumber($v, $digits, $locale);

    // Zones: the dial's thirds by default, each with its own tone.
    $raw = $zones !== null ? array_values((array) $zones) : [
        ['upTo' => $lo + $span * 0.6, 'tone' => 'success'],
        ['upTo' => $lo + $span * 0.85, 'tone' => 'warning'],
        ['tone' => 'danger'],
    ];
    $tones = ['success', 'warning', 'danger', 'info', 'accent'];
    $toneWords = ['success' => 'safe', 'warning' => 'caution', 'danger' => 'critical'];
    $edge = $lo;
    $list = [];
    foreach ($raw as $i => $zone) {
        $zone = (array) $zone;
        $from = $edge;
        $upTo = $zone['upTo'] ?? null;
        $to = $upTo !== null ? (float) $upTo : ($i === count($raw) - 1 ? $hi : $from);
        $to = min($hi, max($from, $to));
        $edge = $to;
        $list[] = [
            'from' => $from,
            'to' => $to,
            'tone' => isset($zone['tone']) && in_array($zone['tone'], $tones, true) ? (string) $zone['tone'] : 'accent',
            'label' => array_key_exists('label', $zone) ? $zone['label'] : null,
        ];
    }
    $list = array_values(array_filter($list, fn ($zone) => $zone['to'] > $zone['from']));

    // The zones are dashes on the same arc (pathLength 100) — the core's donutSegments maths.
    $gap = count($list) > 1 ? 1.6 : 0.0;
    $cursor = 0.0;
    $segments = [];
    foreach ($list as $zone) {
        $share = ($zone['to'] - $zone['from']) / $span * 100;
        $segments[] = ['length' => round(max(0, $share - $gap), 2), 'offset' => round(-$cursor, 2)];
        $cursor += $share;
    }

    // The zone the value sits in; its tone colours the sweep, hub dot and caption.
    $active = max(0, count($list) - 1);
    foreach ($list as $i => $zone) {
        if ($shown <= $zone['to']) { $active = $i; break; }
    }
    $tone = $list[$active]['tone'] ?? 'accent';

    // A zone's name: the prop, else the tone's word, else "Zone n".
    $nameOf = function (array $zone, int $i) use ($say, $toneWords, $locale): string {
        if ($zone['label'] !== null && $zone['label'] !== '') return (string) $zone['label'];
        $key = $toneWords[$zone['tone']] ?? null;
        return $key !== null ? $say($key) : $say('zone').' '.NabuXUI::formatNumber($i + 1, 0, $locale);
    };
    $caption = $zoneLabel;
    if ($caption === null && isset($list[$active])) {
        $caption = $nameOf($list[$active], $active);
    }
    $reading = __('nabuxui::ui.gaugeReading', ['value' => $fmt($shown), 'max' => $fmt($hi)], $lang);

    // Eleven ticks around the arc, every fifth one major.
    $ticks = [];
    foreach (range(0, 10) as $i) {
        $rad = deg2rad($i / 10 * 180 - 90);
        $r1 = $i % 5 === 0 ? 30.5 : 33.5;
        $ticks[] = [
            'x1' => round(50 + $r1 * sin($rad), 2), 'y1' => round(50 - $r1 * cos($rad), 2),
            'x2' => round(50 + 36 * sin($rad), 2), 'y2' => round(50 - 36 * cos($rad), 2),
            'major' => $i % 5 === 0,
        ];
    }

    $style = "--_angle: {$angle}; --_sweep: {$sweep}";
    if ($size !== null) {
        $style .= is_numeric($size) ? "; --nx-gauge-size: {$size}px" : "; --nx-gauge-size: {$size}";
    }
@endphp
<figure {{ $attributes->class('nx-gauge')->merge([
    'data-tone' => $tone,
    'data-nx-reveal' => '',
    'aria-labelledby' => $title ? "{$id}-title" : null,
    'style' => $style,
]) }} x-data x-nx-reveal>
    @if ($title || $subtitle)
        <div class="nx-chart-head">
            <div>
                @if ($title)<p class="nx-chart-title" id="{{ $id }}-title">{{ $title }}</p>@endif
                @if ($subtitle)<p class="nx-chart-subtitle">{{ $subtitle }}</p>@endif
            </div>
        </div>
    @endif

    <div class="nx-gauge-dial">
        <div class="nx-gauge-face">
            <svg class="nx-gauge-svg" viewBox="0 0 100 58" aria-hidden="true">
                <path class="nx-gauge-track" d="M8 50 A42 42 0 0 1 92 50" pathLength="100" />
                @foreach ($list as $i => $zone)
                    <path class="nx-gauge-zone" d="M8 50 A42 42 0 0 1 92 50" pathLength="100" data-tone="{{ $zone['tone'] }}"
                        style="--_len: {{ $segments[$i]['length'] }}; --_off: {{ $segments[$i]['offset'] }}; --nx-i: {{ $i }}" />
                @endforeach
                <g class="nx-gauge-ticks">
                    @foreach ($ticks as $tick)
                        <line class="nx-gauge-tick" @if ($tick['major']) data-major @endif
                            x1="{{ $tick['x1'] }}" y1="{{ $tick['y1'] }}" x2="{{ $tick['x2'] }}" y2="{{ $tick['y2'] }}" />
                    @endforeach
                </g>
                <path class="nx-gauge-value" d="M8 50 A42 42 0 0 1 92 50" pathLength="100" />
                <g class="nx-gauge-needle">
                    <path class="nx-gauge-hand" d="M50 17 L46.6 47.6 L50 50.8 L53.4 47.6 Z" />
                </g>
                <circle class="nx-gauge-hub" cx="50" cy="50" r="5.2" />
                <circle class="nx-gauge-hub-dot" cx="50" cy="50" r="2.1" />
            </svg>
            <span class="nx-gauge-end" data-end="min" aria-hidden="true">{{ $fmt($lo) }}</span>
            <span class="nx-gauge-end" data-end="max" aria-hidden="true">{{ $fmt($hi) }}</span>
        </div>

        <p class="nx-gauge-readout">
            <span class="nx-gauge-figure">
                <x-nx::number :value="$shown" :decimals="$digits" :locale="$locale" />
                @if ($unit)<span class="nx-gauge-unit">{{ $unit }}</span>@endif
            </span>
            @if ($caption !== null && $caption !== '')<span class="nx-gauge-zone-label">{{ $caption }}</span>@endif
        </p>
    </div>

    <table class="nx-visually-hidden">
        @if ($title)<caption>{{ $title }}</caption>@endif
        <thead>
            <tr><th scope="col">{{ $say('zone') }}</th><th scope="col">{{ $say('range') }}</th></tr>
        </thead>
        <tbody>
            @foreach ($list as $i => $zone)
                <tr>
                    <th scope="row">{{ $nameOf($zone, $i) }}</th>
                    <td>{{ $fmt($zone['from']) }} – {{ $fmt($zone['to']) }}</td>
                </tr>
            @endforeach
            <tr><th scope="row">{{ $say('value') }}</th><td>{{ $reading }}</td></tr>
        </tbody>
    </table>
</figure>
