{{--
    <x-nx::gantt :rows="[
        ['id' => 'design', 'title' => 'Design', 'tone' => 'info', 'tasks' => [
            ['id' => 'wireframe', 'title' => 'Wireframes', 'start' => '2026-10-01', 'end' => '2026-10-06', 'tone' => 'info', 'progress' => 80],
            ['id' => 'polish', 'title' => 'Polish pass', 'start' => '2026-10-12', 'end' => '2026-10-16', 'dependsOn' => 'wireframe'],
        ]],
        ['id' => 'build', 'title' => 'Build', 'tone' => 'success', 'tasks' => [
            ['id' => 'api', 'title' => 'API', 'start' => '2026-10-05', 'end' => '2026-10-14', 'tone' => 'success', 'progress' => 45],
        ]],
    ]" zoom="day" height="30rem" move-action="moveTask" />

    A Gantt chart: rows of bars over a day grid. Bars drag with the pointer
    (the edge handles resize), or move with the arrow keys (Shift steps a week,
    Alt resizes the end); Escape cancels a drag in flight. The today line, the
    dependency elbows with their landing dots, and the day/week/month zoom are
    all one --nx-gantt-unit apart, so zooming reflows the whole chart. The
    same data ships as a visually-hidden table.

    State: the rows you pass are the truth. `move-action` names a Livewire
    method (moveTask($task, $start, $end)) called after the optimistic move —
    re-render the rows with the new dates; bars keep a wire:key so they glide
    through the morph. Without it everything stays client-side and nx-move
    events bubble from the component for you to handle.
--}}
@props(['rows' => [], 'zoom' => 'day', 'moveAction' => null, 'height' => null, 'label' => null, 'locale' => null])
@php
    use NabuXUI\NabuXUI;

    $locale = str_replace('_', '-', $locale ?? app()->getLocale());
    $lang = substr($locale, 0, 2);
    // The chart's own words live in the core i18n table (resources/lang, generated from it).
    $say = fn (string $key) => __('nabuxui::ui.'.$key, [], $lang);
    $labels = [
        'chart' => $label ?? $say('ganttChart'),
        'zoom' => $say('ganttZoom'),
        'day' => $say('ganttZoomDay'),
        'week' => $say('ganttZoomWeek'),
        'month' => $say('ganttZoomMonth'),
        'today' => $say('ganttToday'),
        'task' => $say('ganttTask'),
        'start' => $say('ganttStart'),
        'end' => $say('ganttEnd'),
        'duration' => $say('ganttDuration'),
        'progress' => $say('ganttProgress'),
        'depends' => $say('ganttDepends'),
        'dependsOn' => $say('ganttDependsOn'),
        'days' => $say('ganttDays'),
        'hint' => $say('ganttHint'),
        'moved' => $say('ganttMoved'),
        'resized' => $say('ganttResized'),
        'empty' => $say('ganttEmpty'),
    ];
    $zoom = in_array($zoom, ['day', 'week', 'month'], true) ? $zoom : 'day';
    $toneOf = fn ($tone) => in_array($tone, ['accent', 'success', 'warning', 'danger', 'info', 'gold'], true) ? $tone : null;
    $isoDay = fn ($date) => $date instanceof \DateTimeInterface ? $date->format('Y-m-d') : substr((string) $date, 0, 10);

    // Dates are UTC day numbers, so no timezone ever shifts a bar.
    $dayIdx = function (?string $iso): ?int {
        if (! $iso || ! preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $iso)) {
            return null;
        }
        $date = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', "{$iso} 00:00:00", new \DateTimeZone('UTC'));

        return $date ? intdiv($date->getTimestamp(), 86400) : null;
    };
    $dateOf = fn (int $day) => new \DateTimeImmutable('@'.($day * 86400));
    $fmt = function (string $pattern, \DateTimeInterface $date) use ($locale): string {
        if (class_exists(\IntlDateFormatter::class)) {
            return (string) (new \IntlDateFormatter($locale, \IntlDateFormatter::NONE, \IntlDateFormatter::NONE, 'UTC', null, $pattern))->format($date);
        }

        return $date->format(match ($pattern) { 'd' => 'j', 'd MMM' => 'j n', 'MMMM y' => 'F Y', 'd MMMM y' => 'j F Y', default => 'Y-m-d' });
    };
    $percentSign = in_array($lang, ['fa', 'ar'], true) ? '٪' : '%';
    $weekStart = in_array($lang, ['fa', 'ar'], true) ? 6 : 0;
    $weekend = in_array($lang, ['fa', 'ar'], true) ? [5] : [0, 6];

    // Normalise the rows; tasks with unparseable dates simply don't chart.
    $rows = array_values(array_map(fn ($row) => [
        'id' => (string) $row['id'],
        'title' => (string) $row['title'],
        'tone' => $toneOf($row['tone'] ?? null),
        'tasks' => array_values(array_filter(array_map(fn ($task) => [
            'id' => (string) $task['id'],
            'title' => (string) $task['title'],
            'start' => $isoDay($task['start'] ?? ''),
            'end' => $isoDay($task['end'] ?? ''),
            'tone' => $toneOf($task['tone'] ?? null),
            'progress' => isset($task['progress']) ? max(0, min(100, (int) round((float) $task['progress']))) : null,
            'dependsOn' => isset($task['dependsOn']) ? (string) $task['dependsOn'] : null,
        ], $row['tasks'] ?? []), fn ($task) => $dayIdx($task['start']) !== null && $dayIdx($task['end']) !== null)),
    ], $rows));

    // Place the bars: greedy lane packing per row, plus the timeline's span.
    $today = intdiv(time(), 86400);
    $placed = [];
    $barsByRow = [];
    $rowLanes = [];
    $minS = null;
    $maxE = null;
    $seen = 0;
    foreach ($rows as $ri => $row) {
        $items = [];
        foreach ($row['tasks'] as $task) {
            $si = $dayIdx($task['start']);
            $ei = $dayIdx($task['end']);
            $s0 = min($si, $ei);
            $items[] = ['task' => $task, 's0' => $s0, 'n' => abs($ei - $si) + 1, 'l' => 0, 'i' => $seen++];
            $minS = $minS === null ? $s0 : min($minS, $s0);
            $maxE = $maxE === null ? $s0 + abs($ei - $si) + 1 : max($maxE, $s0 + abs($ei - $si) + 1);
        }
        usort($items, fn ($a, $b) => $a['s0'] <=> $b['s0']);
        $laneEnds = [];
        foreach ($items as $key => $item) {
            $lane = null;
            foreach ($laneEnds as $li => $end) {
                if ($end <= $item['s0']) {
                    $lane = $li;
                    break;
                }
            }
            if ($lane === null) {
                $laneEnds[] = 0;
                $lane = count($laneEnds) - 1;
            }
            $laneEnds[$lane] = $item['s0'] + $item['n'];
            $items[$key]['l'] = $lane;
        }
        $rowLanes[$ri] = max(1, count($laneEnds));
        $barsByRow[$ri] = $items;
    }

    $origin = $minS !== null ? $minS - 3 : $today - 7;
    $horizon = $maxE !== null ? $maxE + 3 : $today + 21;
    $totalDays = $horizon - $origin;
    $todayD = $today >= $origin && $today < $horizon ? $today - $origin : null;

    // Offsets from the origin, once — the bar display data and the links speak in these.
    foreach ($barsByRow as $ri => $items) {
        foreach ($items as $key => $item) {
            $s = $item['s0'] - $origin;
            $barsByRow[$ri][$key]['s'] = $s;
            $placed[$item['task']['id']] = [
                'row' => $ri,
                'l' => $item['l'],
                's' => $s,
                'n' => $item['n'],
                'title' => $item['task']['title'],
                'tone' => $item['task']['tone'],
            ];
        }
    }

    // The header bands: months always, day numbers and week starts per zoom.
    $months = [];
    for ($d = 0; $d < $totalDays;) {
        $date = $dateOf($origin + $d);
        $firstNext = intdiv(gmmktime(0, 0, 0, (int) $date->format('n') + 1, 1, (int) $date->format('Y')), 86400);
        $until = min($firstNext - $origin, $totalDays);
        $months[] = ['s' => $d, 'n' => $until - $d, 'label' => $fmt('MMMM y', $date)];
        $d = $until;
    }
    $days = [];
    $weeks = [];
    for ($d = 0; $d < $totalDays; $d++) {
        $date = $dateOf($origin + $d);
        $weekday = (int) $date->format('w');
        $days[] = [
            'd' => $d,
            'label' => $fmt('d', $date),
            'weekend' => in_array($weekday, $weekend, true),
            'weekStart' => $weekday === $weekStart,
            'monthStart' => (int) $date->format('j') === 1,
        ];
        if ($weekday === $weekStart) {
            $weeks[] = ['d' => $d, 'label' => $fmt('d', $date)];
        }
    }

    // Dependency elbows: from the predecessor's end to the successor's start, in day and lane units.
    $rowOffset = [];
    $acc = 0;
    foreach ($rowLanes as $lanes) {
        $rowOffset[] = $acc;
        $acc += $lanes;
    }
    $links = [];
    foreach ($barsByRow as $ri => $items) {
        foreach ($items as $item) {
            $dep = $item['task']['dependsOn'] ?? null;
            if (! $dep || $dep === $item['task']['id'] || ! isset($placed[$dep])) {
                continue;
            }
            $after = $placed[$dep];
            $x1 = $after['s'] + $after['n'];
            $y1 = $rowOffset[$after['row']] + $after['l'] + 0.5;
            $x2 = $item['s'];
            $y2 = $rowOffset[$ri] + $item['l'] + 0.5;
            $elbow = max($x1, $x2 - 0.6);
            $links[] = [
                'tone' => $item['task']['tone'],
                'h1' => $elbow - $x1 > 0.01 ? ['x' => min($x1, $elbow), 'y' => $y1, 'w' => abs($elbow - $x1)] : null,
                'v' => abs($y2 - $y1) > 0.01 ? ['x' => $elbow, 'y' => min($y1, $y2), 'h' => abs($y2 - $y1)] : null,
                'h2' => $x2 - $elbow > 0.01 ? ['x' => min($elbow, $x2), 'y' => $y2, 'w' => abs($x2 - $elbow)] : null,
                'from' => ['x' => $x1, 'y' => $y1],
                'to' => ['x' => $x2, 'y' => $y2],
            ];
        }
    }

    $hasTasks = count($placed) > 0;
    $gid = NabuXUI::id('nx-gantt');
    $listenerAttrs = $attributes->whereStartsWith(['x-on:', '@', 'wire:']);
    $rest = $attributes->whereDoesntStartWith(['x-on:', '@', 'wire:']);
    $rootStyle = "--nx-gantt-days: {$totalDays}";
    if ($height) {
        $rootStyle .= "; --nx-gantt-height: {$height}";
    }
@endphp
<section {{ $rest->class('nx-gantt')->merge([
    'aria-label' => $labels['chart'],
    'data-nx-reveal' => '',
    'data-zoom' => $zoom,
    'style' => $rootStyle,
]) }} {{ $listenerAttrs }}
    x-data="nxGantt(@js(['zoom' => $zoom, 'labels' => ['moved' => $labels['moved'], 'resized' => $labels['resized'], 'days' => $labels['days']], 'locale' => $locale, 'moveAction' => $moveAction, 'origin' => $origin, 'days' => $totalDays]))"
    x-bind:data-zoom="zoom">
    <div class="nx-gantt-toolbar" wire:key="{{ $gid }}-tools">
        @if ($todayD !== null)
            <button type="button" class="nx-gantt-today-button" x-on:click="goToday()">{{ $labels['today'] }}</button>
        @endif
        {{-- Alpine owns the pills; Livewire never needs to re-render them. --}}
        <fieldset class="nx-gantt-zoom" wire:ignore x-on:change="zoomChange($event)">
            <legend class="nx-visually-hidden">{{ $labels['zoom'] }}</legend>
            @foreach (['day', 'week', 'month'] as $level)
                <label class="nx-gantt-zoom-choice">
                    <input type="radio" class="nx-gantt-zoom-input" name="{{ $gid }}-zoom" value="{{ $level }}" @checked($zoom === $level) />
                    <span class="nx-gantt-zoom-label">{{ $labels[$level] }}</span>
                </label>
            @endforeach
        </fieldset>
    </div>
    <div class="nx-gantt-frame" wire:key="{{ $gid }}-frame">
        @if ($hasTasks)
            <div class="nx-gantt-inner">
                <div class="nx-gantt-corner" aria-hidden="true"></div>
                <div class="nx-gantt-head">
                    <div class="nx-gantt-months">
                        @foreach ($months as $m)
                            <span class="nx-gantt-month" style="--s: {{ $m['s'] }}; --n: {{ $m['n'] }}">{{ $m['label'] }}</span>
                        @endforeach
                        @if ($todayD !== null)
                            <span class="nx-gantt-today-flag" style="--d: {{ $todayD }}">{{ $labels['today'] }}</span>
                        @endif
                    </div>
                    <div class="nx-gantt-days">
                        @foreach ($days as $day)
                            <span class="nx-gantt-day" @if ($day['weekend']) data-weekend @endif style="--d: {{ $day['d'] }}">{{ $day['label'] }}</span>
                        @endforeach
                    </div>
                    <div class="nx-gantt-weeks">
                        @foreach ($weeks as $w)
                            <span class="nx-gantt-week" style="--d: {{ $w['d'] }}">{{ $w['label'] }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="nx-gantt-side">
                    @foreach ($rows as $ri => $row)
                        <div class="nx-gantt-label" style="--lanes: {{ $rowLanes[$ri] }}">
                            @if ($row['tone'])
                                <span class="nx-gantt-label-dot" data-tone="{{ $row['tone'] }}" aria-hidden="true"></span>
                            @endif
                            <span>{{ $row['title'] }}</span>
                        </div>
                    @endforeach
                </div>
                {{-- One delegation point for every bar's pointer and keyboard life. --}}
                <div class="nx-gantt-lanes"
                    x-on:pointerdown="barDown($event)" x-on:pointermove="barMove($event)" x-on:pointerup="barUp($event)"
                    x-on:pointercancel="barCancel($event)" x-on:keydown="barKey($event)">
                    <div class="nx-gantt-grid" aria-hidden="true">
                        @foreach ($days as $day)
                            <span class="nx-gantt-tick" @if ($day['weekStart']) data-week @endif @if ($day['monthStart']) data-month @endif style="--d: {{ $day['d'] }}"></span>
                        @endforeach
                        @foreach ($days as $day)
                            @if ($day['weekend'])<span class="nx-gantt-weekend" style="--d: {{ $day['d'] }}"></span>@endif
                        @endforeach
                    </div>
                    @foreach ($links as $li => $link)
                        <div class="nx-gantt-link" @if ($link['tone']) data-tone="{{ $link['tone'] }}" @endif aria-hidden="true">
                            @if ($link['h1'])<span class="nx-gantt-link-h" style="--x: {{ round($link['h1']['x'], 3) }}; --y: {{ round($link['h1']['y'], 3) }}; --w: {{ round($link['h1']['w'], 3) }}"></span>@endif
                            @if ($link['v'])<span class="nx-gantt-link-v" style="--x: {{ round($link['v']['x'], 3) }}; --y: {{ round($link['v']['y'], 3) }}; --h: {{ round($link['v']['h'], 3) }}"></span>@endif
                            @if ($link['h2'])<span class="nx-gantt-link-h" style="--x: {{ round($link['h2']['x'], 3) }}; --y: {{ round($link['h2']['y'], 3) }}; --w: {{ round($link['h2']['w'], 3) }}"></span>@endif
                            <span class="nx-gantt-link-dot" style="--x: {{ round($link['from']['x'], 3) }}; --y: {{ round($link['from']['y'], 3) }}"></span>
                            <span class="nx-gantt-link-dot" style="--x: {{ round($link['to']['x'], 3) }}; --y: {{ round($link['to']['y'], 3) }}"></span>
                        </div>
                    @endforeach
                    @if ($todayD !== null)
                        <div class="nx-gantt-today" style="--d: {{ $todayD }}" aria-hidden="true">
                            <span class="nx-gantt-today-dot"></span>
                        </div>
                    @endif
                    @foreach ($barsByRow as $ri => $bars)
                        <div class="nx-gantt-track" style="--lanes: {{ $rowLanes[$ri] }}">
                            @foreach ($bars as $bar)
                                @php
                                    $barStart = $origin + $bar['s'];
                                    $barEnd = $origin + $bar['s'] + $bar['n'] - 1;
                                    $barStyle = "--s: {$bar['s']}; --n: {$bar['n']}; --l: {$bar['l']}; --nx-i: {$bar['i']}";
                                    if ($bar['task']['progress'] !== null) {
                                        $barStyle .= "; --p: {$bar['task']['progress']}";
                                    }
                                    $barAria = $bar['task']['title'].', '.$fmt('d MMM', $dateOf($barStart)).' – '.$fmt('d MMM', $dateOf($barEnd));
                                    if ($bar['task']['progress'] !== null) {
                                        $barAria .= ', '.NabuXUI::formatNumber($bar['task']['progress'], 0, $locale).$percentSign;
                                    }
                                @endphp
                                <button type="button" class="nx-gantt-bar" data-task="{{ $bar['task']['id'] }}" data-title="{{ $bar['task']['title'] }}"
                                    @if ($bar['task']['tone']) data-tone="{{ $bar['task']['tone'] }}" @endif
                                    draggable="false" aria-label="{{ $barAria }}" aria-describedby="{{ $gid }}-hint"
                                    wire:key="{{ $gid }}-bar-{{ $bar['task']['id'] }}" style="{{ $barStyle }}">
                                    @if ($bar['task']['progress'] !== null)<span class="nx-gantt-bar-fill" aria-hidden="true"></span>@endif
                                    <span class="nx-gantt-bar-title">{{ $bar['task']['title'] }}</span>
                                    <span class="nx-gantt-bar-handle" data-handle="start" aria-hidden="true"></span>
                                    <span class="nx-gantt-bar-handle" data-handle="end" aria-hidden="true"></span>
                                </button>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <p class="nx-gantt-empty">{{ $labels['empty'] }}</p>
        @endif
    </div>
    <p class="nx-visually-hidden" id="{{ $gid }}-hint">{{ $labels['hint'] }}</p>
    {{-- The move or resize, spoken once it lands. --}}
    <p class="nx-visually-hidden" role="status" x-text="announce"></p>
    @if ($hasTasks)
        <table class="nx-visually-hidden">
            <caption>{{ $labels['chart'] }}</caption>
            <thead>
                <tr>
                    <th scope="col">{{ $labels['task'] }}</th>
                    <th scope="col">{{ $labels['start'] }}</th>
                    <th scope="col">{{ $labels['end'] }}</th>
                    <th scope="col">{{ $labels['duration'] }}</th>
                    <th scope="col">{{ $labels['progress'] }}</th>
                    <th scope="col">{{ $labels['depends'] }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barsByRow as $ri => $bars)
                    @foreach ($bars as $bar)
                        <tr data-task="{{ $bar['task']['id'] }}">
                            <th scope="row">{{ $rows[$ri]['title'] }} — {{ $bar['task']['title'] }}</th>
                            <td data-field="start">{{ $fmt('d MMMM y', $dateOf($origin + $bar['s'])) }}</td>
                            <td data-field="end">{{ $fmt('d MMMM y', $dateOf($origin + $bar['s'] + $bar['n'] - 1)) }}</td>
                            <td data-field="duration">{{ str_replace(':count', NabuXUI::formatNumber($bar['n'], 0, $locale), $labels['days']) }}</td>
                            <td>@if ($bar['task']['progress'] !== null){{ NabuXUI::formatNumber($bar['task']['progress'], 0, $locale).$percentSign }}@else — @endif</td>
                            <td>@if (($dep = $bar['task']['dependsOn'] ?? null) !== null && isset($placed[$dep])){{ str_replace(':name', $placed[$dep]['title'], $labels['dependsOn']) }}@endif</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @endif
</section>
