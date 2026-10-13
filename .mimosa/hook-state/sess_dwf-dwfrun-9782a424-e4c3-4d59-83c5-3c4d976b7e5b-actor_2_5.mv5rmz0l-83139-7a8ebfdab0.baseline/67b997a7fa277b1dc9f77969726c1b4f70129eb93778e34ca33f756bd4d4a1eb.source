{{--
    A contribution calendar:
        <x-nx::heatmap title="Conversations" unit="conversations" :weeks="26" :data="[['date' => '2026-09-01', 'value' => 4], …]" />
    Or any matrix (rows of numbers) with names:
        <x-nx::heatmap title="Load by hour" :data="[[1, 4, 9], [2, 0, 5]]" :labels="['rows' => ['Mon', 'Tue'], 'columns' => ['09', '10', '11']]" />

    week-start: 0 Sunday (default), 1 Monday, 6 Saturday. Month labels and dates follow the
    app locale's calendar when the intl extension is there.
--}}
@props(['data' => [], 'labels' => [], 'title' => null, 'subtitle' => null, 'weeks' => null, 'weekStart' => 0, 'end' => null, 'unit' => null, 'summary' => null])
@php
    use NabuXUI\NabuXUI;
    $locale = str_replace('_', '-', app()->getLocale());
    $lang = substr($locale, 0, 2);
    $words = ['fa' => ['less' => 'کمتر', 'more' => 'بیشتر', 'week' => 'هفتهٔ :date'], 'ar' => ['less' => 'أقل', 'more' => 'أكثر', 'week' => 'أسبوع :date']][$lang] ?? ['less' => 'Less', 'more' => 'More', 'week' => 'Week of :date'];
    $chartId = NabuXUI::id('nx-heatmap');
    $level = fn ($v, $max) => ($v <= 0 || $max <= 0) ? 0 : (int) min(4, max(1, ceil($v / $max * 4)));
    $fmt = fn ($v) => NabuXUI::formatNumber($v, floor((float) $v) == (float) $v ? 0 : 1);
    $intl = class_exists(\IntlDateFormatter::class);
    $dateFormat = function (string $skeleton, string $fallback) use ($intl, $locale) {
        if (! $intl) {
            return fn (\DateTimeImmutable $d) => $d->format($fallback);
        }
        $pattern = class_exists(\IntlDatePatternGenerator::class) ? ((new \IntlDatePatternGenerator($locale))->getBestPattern($skeleton) ?: $skeleton) : $skeleton;
        $formatter = new \IntlDateFormatter($locale, \IntlDateFormatter::NONE, \IntlDateFormatter::NONE, 'UTC', null, $pattern);

        return fn (\DateTimeImmutable $d) => (string) $formatter->format($d);
    };

    $matrix = isset($data[0]) && is_array($data[0]) && ! array_key_exists('date', $data[0]);
    $cells = [];
    $columnLabels = [];
    $rowLabels = [];
    $head = [];
    $body = [];

    if ($matrix) {
        $grid = array_map('array_values', array_values($data));
        $cols = max(array_map('count', $grid) ?: [0]);
        $rows = count($grid);
        $max = max(array_merge([0], ...$grid));
        $rowNames = array_values($labels['rows'] ?? range(1, max(1, $rows)));
        $colNames = array_values($labels['columns'] ?? range(1, max(1, $cols)));
        foreach ($grid as $r => $line) {
            foreach ($line as $c => $v) {
                $cells[] = ['col' => $c, 'row' => $r, 'value' => $v, 'level' => $level($v, $max), 'title' => ($rowNames[$r] ?? '').' · '.($colNames[$c] ?? '')];
            }
            $body[] = ['label' => $rowNames[$r] ?? '', 'cells' => array_map(fn ($c) => isset($line[$c]) ? $fmt($line[$c]) : '', range(0, max(0, $cols - 1)))];
        }
        foreach ($colNames as $c => $name) $columnLabels[] = ['col' => $c, 'text' => $name, 'span' => 1];
        foreach ($rowNames as $r => $name) $rowLabels[] = ['row' => $r, 'text' => $name];
        $head = $colNames;
    } else {
        $utc = new \DateTimeZone('UTC');
        $values = [];
        foreach ($data as $datum) {
            $day = substr((string) $datum['date'], 0, 10);
            $values[$day] = ($values[$day] ?? 0) + (float) $datum['value'];
        }
        ksort($values);
        $weekStart = (int) $weekStart;
        $dow = fn (\DateTimeImmutable $d) => ((int) $d->format('w') - $weekStart + 7) % 7;
        $last = new \DateTimeImmutable($end ?? (array_key_last($values) ?? 'today'), $utc);
        $last = $last->setTime(0, 0);
        $lastWeek = $last->modify('-'.$dow($last).' days');
        $first = $weeks ? $lastWeek->modify('-'.(((int) $weeks - 1) * 7).' days') : (new \DateTimeImmutable(array_key_first($values) ?? $last->format('Y-m-d'), $utc))->setTime(0, 0);
        $firstWeek = $first->modify('-'.$dow($first).' days');
        $cols = intdiv((int) $firstWeek->diff($lastWeek)->days, 7) + 1;
        $rows = 7;
        $max = 0;
        for ($day = $first; $day <= $last; $day = $day->modify('+1 day')) $max = max($max, $values[$day->format('Y-m-d')] ?? 0);

        $dayTitle = $dateFormat('EEEMMMdy', 'D, M j, Y');
        $monthName = $dateFormat('LLL', 'M');
        $weekdayName = $dateFormat('EEE', 'D');
        $shortDate = $dateFormat('MMMd', 'M j');
        // The day of the month in the locale's own calendar (a Persian month starts mid-way through a Gregorian one).
        $dayOfMonth = $intl ? new \IntlDateFormatter($locale.'@numbers=latn', \IntlDateFormatter::NONE, \IntlDateFormatter::NONE, 'UTC', null, 'd') : null;
        $monthStart = fn (\DateTimeImmutable $d) => $dayOfMonth ? (int) $dayOfMonth->format($d) === 1 : $d->format('j') === '1';

        $head = array_map(fn ($r) => $weekdayName($firstWeek->modify("+{$r} days")), range(0, 6));
        for ($c = 0; $c < $cols; $c++) {
            $row = [];
            $label = '';
            for ($r = 0; $r < 7; $r++) {
                $day = $firstWeek->modify('+'.($c * 7 + $r).' days');
                if ($day < $first || $day > $last) {
                    $row[] = '';
                    continue;
                }
                $v = $values[$day->format('Y-m-d')] ?? 0;
                $cells[] = ['col' => $c, 'row' => $r, 'value' => $v, 'level' => $level($v, $max), 'title' => $dayTitle($day)];
                $row[] = $fmt($v);
                $label = $label ?: str_replace(':date', $shortDate($day), $words['week']);
                if ($monthStart($day)) $columnLabels[] = ['col' => $c, 'text' => $monthName($day), 'span' => 3];
            }
            $body[] = ['label' => $label, 'cells' => $row];
        }
        // The month the range opens with, when there is room before the next label.
        if ($cells && ($columnLabels[0]['col'] ?? PHP_INT_MAX) >= 3) array_unshift($columnLabels, ['col' => 0, 'text' => $monthName($first), 'span' => 3]);
        foreach ([1, 3, 5] as $r) $rowLabels[] = ['row' => $r, 'text' => $head[$r]];
    }
@endphp
<figure {{ $attributes->class('nx-chart nx-heatmap')->merge(['data-nx-reveal' => '', 'aria-labelledby' => $title ? "{$chartId}-title" : null]) }}
    x-data="nxHeatmap(@js(['locale' => $locale, 'unit' => $unit]))">
    @if ($title || $subtitle)
        <div class="nx-chart-head">
            <div>
                @if ($title)<p class="nx-chart-title" id="{{ $chartId }}-title">{{ $title }}</p>@endif
                @if ($subtitle)<p class="nx-chart-subtitle">{{ $subtitle }}</p>@endif
            </div>
        </div>
    @endif
    <div class="nx-heatmap-frame" x-ref="frame">
        <div class="nx-heatmap-scroll">
            <div class="nx-heatmap-plot" tabindex="0" aria-label="{{ $title ? $title.'. ' : '' }}{{ __('nabuxui::ui.chartHint') }}"
                style="--nx-cols: {{ $cols }}; --nx-rows: {{ $rows }};{{ $matrix ? ' --_label: 4.5rem;' : '' }}"
                x-on:keydown="key($event)" x-on:blur="clear()">
                <div class="nx-heatmap-columns" aria-hidden="true">
                    @foreach ($columnLabels as $label)<span class="nx-heatmap-column" style="grid-column: {{ $label['col'] + 1 }} / span {{ $label['span'] }}">{{ $label['text'] }}</span>@endforeach
                </div>
                <div class="nx-heatmap-rows" aria-hidden="true">
                    @foreach ($rowLabels as $label)<span class="nx-heatmap-row" style="grid-row: {{ $label['row'] + 1 }}">{{ $label['text'] }}</span>@endforeach
                </div>
                <div class="nx-heatmap-grid" aria-hidden="true" x-ref="grid" x-on:pointerover="pick($event)" x-on:pointerleave="clear()">
                    @foreach ($cells as $cell)<span class="nx-heatmap-cell" data-level="{{ $cell['level'] }}" data-col="{{ $cell['col'] }}" data-row="{{ $cell['row'] }}" data-value="{{ $cell['value'] }}" data-title="{{ $cell['title'] }}" style="--nx-d: {{ $cell['col'] + $cell['row'] }}; grid-column: {{ $cell['col'] + 1 }}; grid-row: {{ $cell['row'] + 1 }}"></span>@endforeach
                </div>
            </div>
        </div>
        <div class="nx-chart-tooltip" x-ref="tip" aria-hidden="true" wire:ignore></div>
    </div>
    <div class="nx-heatmap-foot">
        @if ($summary)<span>{{ $summary }}</span>@endif
        <div class="nx-heatmap-legend" aria-hidden="true">
            <span>{{ $words['less'] }}</span>
            @foreach (range(0, 4) as $l)<span class="nx-heatmap-cell" data-level="{{ $l }}"></span>@endforeach
            <span>{{ $words['more'] }}</span>
        </div>
    </div>
    <table class="nx-visually-hidden">
        @if ($title)<caption>{{ $title }}</caption>@endif
        <thead><tr><td></td>@foreach ($head as $h)<th scope="col">{{ $h }}</th>@endforeach</tr></thead>
        <tbody>
            @foreach ($body as $row)
                <tr><th scope="row">{{ $row['label'] }}</th>@foreach ($row['cells'] as $v)<td>{{ $v }}</td>@endforeach</tr>
            @endforeach
        </tbody>
    </table>
</figure>
