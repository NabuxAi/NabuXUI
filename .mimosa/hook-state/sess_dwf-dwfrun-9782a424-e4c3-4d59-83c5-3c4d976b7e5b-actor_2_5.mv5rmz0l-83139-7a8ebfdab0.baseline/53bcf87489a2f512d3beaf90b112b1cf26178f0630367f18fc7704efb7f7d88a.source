{{--
    <x-nx::calendar :events="[
        ['date' => '2026-09-14', 'label' => 'Design review', 'time' => '10:00', 'tone' => 'accent'],
        ['date' => '2026-09-14', 'label' => 'Ship v2.1', 'time' => '16:30', 'tone' => 'danger', 'href' => '#'],
        ['date' => '2026-09-22', 'label' => 'Team offsite', 'tone' => 'gold'],
    ]" value="2026-09-14" week-start="6" max-per-cell="3" wire:model="selectedDay" />

    A month grid over plain dates ("YYYY-MM-DD", or any DateTimeInterface — no
    date library). The server renders the opening month; switching months is
    client-side (the new month slides in from its side, mirrored in RTL) and
    the selected day's agenda sits under the grid, restacked per day. Names
    and digits follow the app locale; events are chips coloured by tone with a
    "+N" tally when a day is full, and the same data ships as a hidden table.
    week-start defaults per locale: Saturday for fa/ar, Sunday otherwise.

    `wire:model` binds the selected day ("2026-09-14", or null). Keyboard:
    arrows walk the days (roving tab stop), Home/End run to the week's edges,
    Enter/Space picks; picking the selected day clears it.
--}}
@props(['events' => [], 'value' => null, 'month' => null, 'weekStart' => null, 'maxPerCell' => 3, 'locale' => null, 'label' => null, 'emptyText' => null, 'today' => null])
@php
    use NabuXUI\NabuXUI;
    $locale = str_replace('_', '-', $locale ?? app()->getLocale());
    $lang = substr($locale, 0, 2);
    // The calendar's own words live in the core i18n table (resources/lang, generated from it).
    $say = fn (string $key) => __('nabuxui::ui.'.$key, [], $lang);
    $labels = [
        'calendar' => $label ?? $say('calendar'),
        'prevMonth' => $say('calendarPrevMonth'),
        'nextMonth' => $say('calendarNextMonth'),
        'eventsCount' => $say('calendarEventsCount'),
        'emptyDay' => $emptyText ?? $say('calendarEmptyDay'),
    ];
    // The week starts on Saturday in the Persian and Arabic calendars, Sunday
    // elsewhere. Kebab-case numeric props arrive as strings; compare textually.
    $weekStart = in_array((string) $weekStart, ['0', '1', '6'], true) ? (int) $weekStart : ($lang === 'fa' || $lang === 'ar' ? 6 : 0);
    $maxPerCell = max(1, (int) $maxPerCell);
    $toneOf = fn ($tone) => in_array($tone, ['accent', 'success', 'warning', 'danger', 'info', 'gold'], true) ? $tone : null;
    $isoDay = fn ($date) => $date instanceof \DateTimeInterface ? $date->format('Y-m-d') : substr((string) $date, 0, 10);

    // Events grouped by day; every field the client needs goes in the config once.
    $byDay = collect($events)->groupBy(fn ($event) => $isoDay($event['date'] ?? ''))->map(fn ($day) => $day->values()->map(fn ($event) => [
        'date' => $isoDay($event['date'] ?? ''),
        'label' => (string) ($event['label'] ?? ''),
        'time' => isset($event['time']) ? (string) $event['time'] : null,
        'tone' => $toneOf($event['tone'] ?? null),
        'href' => $event['href'] ?? null,
    ])->all())->all();
    $allDays = collect($byDay)->map(fn ($day, $iso) => ['date' => $iso, 'events' => $day])->values()->sortBy('date')->values();

    $todayIso = $today instanceof \DateTimeInterface ? $today->format('Y-m-d') : (is_string($today) && $today !== '' ? substr($today, 0, 10) : (new \DateTimeImmutable('now', new \DateTimeZone('UTC')))->format('Y-m-d'));
    $selected = is_string($value) && $value !== '' ? substr($value, 0, 10) : null;
    $viewing = substr(is_string($month) && $month !== '' ? $month : ($selected ?? $todayIso), 0, 7);
    if (! preg_match('/^\d{4}-\d{2}$/', $viewing)) { $viewing = substr($todayIso, 0, 7); }

    // Month math on UTC days: the grid opens on the week's first day, closes on its last.
    $first = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', "{$viewing}-01 00:00:00", new \DateTimeZone('UTC')) ?: new \DateTimeImmutable("{$viewing}-01", new \DateTimeZone('UTC'));
    $offset = ((int) $first->format('w') - $weekStart + 7) % 7;
    $daysInMonth = (int) $first->modify('last day of this month')->format('j');
    $weeks = (int) ceil(($offset + $daysInMonth) / 7);
    $start = $offset > 0 ? $first->modify("-{$offset} days") : $first;
    $days = [];
    for ($i = 0; $i < $weeks * 7; $i++) { $days[] = $start->modify("+{$i} days"); }

    $fmt = function (string $pattern, \DateTimeInterface $date) use ($locale): string {
        if (class_exists(\IntlDateFormatter::class)) {
            return (string) (new \IntlDateFormatter($locale, \IntlDateFormatter::NONE, \IntlDateFormatter::NONE, 'UTC', null, $pattern))->format($date);
        }
        return $date->format(match ($pattern) { 'EEEE' => 'l', 'EEE' => 'D', 'MMMM y' => 'F Y', default => 'Y-m-d' });
    };
    $weekdayNames = [];
    $reference = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2026-01-04 00:00:00', new \DateTimeZone('UTC')); // a Sunday
    for ($i = 0; $i < 7; $i++) { $weekdayNames[] = $fmt('EEE', $reference->modify('+'.(($weekStart + $i) % 7).' days')); }
    $monthTitle = $fmt('MMMM y', $first);
    $dayEvents = $selected ? ($byDay[$selected] ?? []) : [];
    // The roving tab stop: the selected day when visible, else today, else the first cell.
    $anchorIso = $selected;
    if ($anchorIso === null) {
        $anchorIso = substr($todayIso, 0, 7) === $viewing ? $todayIso : $days[0]->format('Y-m-d');
    }
    $calendarId = NabuXUI::id('nx-cal');
    $listenerAttrs = $attributes->whereStartsWith(['x-on:', '@', 'wire:']);
    $rest = $attributes->whereDoesntStartWith(['x-on:', '@', 'wire:']);
@endphp
<section {{ $rest->class('nx-calendar')->merge(['aria-labelledby' => "{$calendarId}-title", 'data-nx-reveal' => '']) }} {{ $listenerAttrs }}
    x-data="nxCalendar(@js([
        'events' => $allDays,
        'labels' => $labels,
        'locale' => $locale,
        'weekStart' => $weekStart,
        'maxPerCell' => $maxPerCell,
        'month' => $viewing,
        'selected' => $selected,
        'today' => $todayIso,
    ]))" x-modelable="model">
    <header class="nx-calendar-head">
        <h3 class="nx-calendar-title" id="{{ $calendarId }}-title" aria-live="polite"
            x-text="titleText" x-bind:data-month="month">{{ $monthTitle }}</h3>
        <nav class="nx-calendar-nav" aria-label="{{ $labels['calendar'] }}">
            <button type="button" class="nx-calendar-nav-btn" aria-label="{{ $labels['prevMonth'] }}" x-on:click="go(-1)">
                {{ NabuXUI::icon('chevron-left') }}
            </button>
            <button type="button" class="nx-calendar-nav-btn" aria-label="{{ $labels['nextMonth'] }}" x-on:click="go(1)">
                {{ NabuXUI::icon('chevron-right') }}
            </button>
        </nav>
    </header>

    <div class="nx-calendar-weekdays" aria-hidden="true">
        @foreach ($weekdayNames as $name)
            <span class="nx-calendar-weekday">{{ $name }}</span>
        @endforeach
    </div>

    {{-- One grid element; switching months swaps its weeks and replays the directional enter. --}}
    <div class="nx-calendar-grid" role="grid" aria-labelledby="{{ $calendarId }}-title" x-ref="grid" x-on:keydown="gridKey($event)">
        @foreach (array_chunk($days, 7) as $week)
            <div class="nx-calendar-week" role="row">
                @foreach ($week as $day)
                    @php
                        $iso = $day->format('Y-m-d');
                        $list = $byDay[$iso] ?? [];
                        $shown = array_slice($list, 0, $maxPerCell);
                        $more = count($list) - count($shown);
                        $isToday = $iso === $todayIso;
                        $isSelected = $iso === $selected;
                        $anchor = $isSelected || (! $selected && $isToday);
                    @endphp
                    <div class="nx-calendar-cell" role="gridcell" data-date="{{ $iso }}"
                        @if (substr($iso, 0, 7) !== $viewing) data-out @endif
                        @if ($isToday) data-today @endif
                        @if ($isSelected) aria-selected="true" @endif>
                        <button type="button" class="nx-calendar-day" data-date="{{ $iso }}"
                            @if (count($list)) data-count @endif
                            tabindex="{{ $iso === $anchorIso ? '0' : '-1' }}"
                            @if ($isToday) aria-current="date" @endif
                            aria-label="{{ $fmt('EEEE, d MMMM y', $day) }}{{ count($list) ? ', '.str_replace(':count', NabuXUI::formatNumber(count($list), 0, $locale), $labels['eventsCount']) : '' }}"
                            x-on:click="pick(@js($iso))">
                            <span class="nx-calendar-daynum">{{ NabuXUI::formatNumber((int) $day->format('j'), 0, $locale) }}</span>
                            @if ($isToday)<span class="nx-calendar-today-dot" aria-hidden="true"></span>@endif
                            @if (count($shown) || $more > 0)
                                <span class="nx-calendar-day-events" aria-hidden="true">
                                    @foreach ($shown as $event)
                                        <span class="nx-calendar-chip" @if ($event['tone']) data-tone="{{ $event['tone'] }}" @endif></span>
                                    @endforeach
                                    @if ($more > 0)
                                        <span class="nx-calendar-more">+{{ NabuXUI::formatNumber($more, 0, $locale) }}</span>
                                    @endif
                                </span>
                            @endif
                        </button>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

    {{-- The selected day's agenda; rebuilt per day, staggered by --nx-i. --}}
    <div class="nx-calendar-panel" x-ref="panel" aria-live="polite" x-bind:data-open="selected ? '' : null">
        @if ($selected)
            <header class="nx-calendar-panel-head">
                <h4 class="nx-calendar-panel-title">{{ $fmt('EEEE, d MMMM y', new \DateTimeImmutable("{$selected} 00:00:00", new \DateTimeZone('UTC'))) }}</h4>
                <span class="nx-calendar-panel-count">{{ str_replace(':count', NabuXUI::formatNumber(count($dayEvents), 0, $locale), $labels['eventsCount']) }}</span>
            </header>
            @if (count($dayEvents))
                <ul class="nx-calendar-panel-list">
                    @foreach ($dayEvents as $i => $event)
                        <li style="--nx-i: {{ $i }}">
                            @if ($event['href'])
                                <a class="nx-calendar-panel-item" href="{{ $event['href'] }}">
                            @else
                                <div class="nx-calendar-panel-item">
                            @endif
                                <span class="nx-calendar-panel-time">{{ $event['time'] ?? '' }}</span>
                                <span class="nx-calendar-panel-dot" @if ($event['tone']) data-tone="{{ $event['tone'] }}" @endif aria-hidden="true"></span>
                                <span class="nx-calendar-panel-label">{{ $event['label'] }}</span>
                            @if ($event['href'])
                                </a>
                            @else
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="nx-calendar-panel-empty">{{ $labels['emptyDay'] }}</p>
            @endif
        @endif
    </div>

    {{-- The same data as text: what a screen reader reads instead of chips. --}}
    <table class="nx-visually-hidden">
        <caption>{{ $labels['calendar'] }}</caption>
        <tbody>
            @forelse ($allDays as $day)
                @foreach ($day['events'] as $event)
                    <tr>
                        <th scope="row">{{ $fmt('EEEE, d MMMM y', new \DateTimeImmutable($event['date'].' 00:00:00', new \DateTimeZone('UTC'))) }}</th>
                        <td>@if ($event['time']){{ $event['time'] }} — @endif{{ $event['label'] }}</td>
                    </tr>
                @endforeach
            @empty
                <tr><td>{{ $labels['emptyDay'] }}</td></tr>
            @endforelse
        </tbody>
    </table>
</section>
