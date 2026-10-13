{{--
    <x-nx::order-tracking number="۱۴۰۴-۰۸۲۱۵" status="running" carrier="پست ایران" eta="۳ روز دیگر"
        label="رهگیری سفارش"
        :steps="[
            ['id' => 'placed',  'title' => 'ثبت سفارش',  'time' => now()->subDays(2), 'icon' => 'file'],
            ['id' => 'packed',  'title' => 'بسته‌بندی',   'time' => now()->subHours(20), 'icon' => 'layers'],
            ['id' => 'transit', 'title' => 'در مسیر',     'description' => 'مرکز توزیع تهران'],
            ['id' => 'out',     'title' => 'تحویل به پیک'],
            ['id' => 'done',    'title' => 'تحویل شد',    'icon' => 'home'],
        ]"
        current="transit"
        :events="[
            ['id' => 'e1', 'title' => 'بسته از مرکز توزیع خارج شد', 'place' => 'تهران', 'time' => now()->subHours(2), 'tone' => 'success'],
            ['id' => 'e2', 'title' => 'در صف بررسی', 'time' => now()->subHours(6), 'tone' => 'info'],
        ]" />

    Where an order is, and how it got there: a horizontal route of steps whose
    fill draws itself up to the current step (a pinging marker while it travels),
    an event log beneath it stitched by tone, and the live status on a
    status-badge. Sections rise one after another; event rows slide in from the
    reading side.

    `current` is the id (or the index) of the current step — steps before it are
    read as complete, after it as upcoming; a step's own `state`
    (complete|current|upcoming) wins over both. `time` may be a date (shown
    relative, in the app's language; Alpine keeps it drifting) or a label as it
    is. status speaks in the badge's job statuses: running | success | failed |
    queued | canceled. Add `wire:poll.30s="checkOrder"` (or any wire:/x-on:
    attribute) on the component to re-render it live — the fill, the badge and
    the events morph into their new state.
--}}
@props([
    'number' => null,
    'status' => null,
    'statusLabel' => null,
    'carrier' => null,
    'eta' => null,
    'steps' => [],
    'current' => null,
    'events' => [],
    'label' => null,
    'labels' => [],
    'live' => true,
    'locale' => null,
])
@php
    use NabuXUI\NabuXUI;
    use Illuminate\Support\Carbon;

    // Kebab-case boolean props arrive as strings; read them like Blade does.
    $live = ! in_array(strtolower((string) $live), ['0', 'false', 'no', 'off', ''], true);
    $locale = str_replace('_', '-', $locale ?? app()->getLocale());
    $lang = substr($locale, 0, 2);
    // The block's own words live in the core i18n table (resources/lang, generated from it).
    $words = [
        'tracking' => __('nabuxui::ui.orderTracking', [], $lang),
        'number' => __('nabuxui::ui.orderNumber', [], $lang),
        'carrier' => __('nabuxui::ui.orderCarrier', [], $lang),
        'eta' => __('nabuxui::ui.orderEta', [], $lang),
        'events' => __('nabuxui::ui.orderEvents', [], $lang),
        'noEvents' => __('nabuxui::ui.orderNoEvents', [], $lang),
    ];
    $words = array_merge($words, is_array($labels) ? $labels : []);
    $say = fn (string $key) => (string) $words[$key];

    $status = in_array($status, ['running', 'success', 'failed', 'queued', 'canceled'], true) ? $status : null;

    // A date becomes a relative label plus its machine-readable twin; a plain
    // string stays the label the app wrote.
    $when = function ($time) use ($lang): array {
        if ($time instanceof \DateTimeInterface) {
            $date = Carbon::instance($time);
        } elseif (is_int($time) || is_float($time)) {
            $date = Carbon::createFromTimestampMs((int) $time);
        } elseif (is_string($time) && preg_match('/\d{4}-\d{2}-\d{2}/', $time)) {
            $date = Carbon::parse($time);
        } else {
            return [(string) $time, null];
        }

        return [$date->locale($lang)->diffForHumans(), $date->toIso8601String()];
    };

    // Where the route stands: an explicit state wins; otherwise everything
    // before `current` is complete, everything after it upcoming. `current`
    // names a step by id, or by its index ("2" after no id matches).
    $states = ['complete', 'current', 'upcoming'];
    $currentIndex = 0;
    $found = false;
    if (is_string($current) && $current !== '') {
        foreach ($steps as $i => $step) {
            if ((string) ($step['id'] ?? '') === $current) {
                $currentIndex = $i;
                $found = true;
                break;
            }
        }
    } elseif (is_numeric($current)) {
        $currentIndex = max(0, (int) $current);
        $found = true;
    }
    if (! $found && is_numeric($current)) {
        $currentIndex = max(0, (int) $current);
    }

    $normalizeStep = function ($step, $i) use ($currentIndex, $when, $states) {
        $step = (array) $step;
        [$label, $iso] = $when($step['time'] ?? '');

        return [
            'id' => (string) ($step['id'] ?? "s{$i}"),
            'title' => (string) ($step['title'] ?? ''),
            'description' => isset($step['description']) ? (string) $step['description'] : null,
            'icon' => isset($step['icon']) && NabuXUI::hasIcon($step['icon']) ? (string) $step['icon'] : null,
            'state' => isset($step['state']) && in_array($step['state'], $states, true)
                ? (string) $step['state']
                : ($i < $currentIndex ? 'complete' : ($i === $currentIndex ? 'current' : 'upcoming')),
            'timeLabel' => $label,
            'timeIso' => $iso,
        ];
    };
    $steps = array_values(array_map($normalizeStep, (array) $steps, array_keys((array) $steps)));

    $tones = ['success', 'warning', 'info', 'danger'];
    $toneIcon = ['success' => 'check-circle', 'warning' => 'alert-triangle', 'info' => 'info', 'danger' => 'alert-circle'];
    $normalizeEvent = function ($event, $i) use ($when, $tones) {
        $event = (array) $event;
        [$label, $iso] = $when($event['time'] ?? '');

        return [
            'id' => (string) ($event['id'] ?? "e{$i}"),
            'title' => (string) ($event['title'] ?? ''),
            'description' => isset($event['description']) ? (string) $event['description'] : null,
            'place' => isset($event['place']) ? (string) $event['place'] : null,
            'tone' => in_array($event['tone'] ?? '', $tones, true) ? (string) $event['tone'] : null,
            'icon' => isset($event['icon']) && NabuXUI::hasIcon($event['icon']) ? (string) $event['icon'] : null,
            'timeLabel' => $label,
            'timeIso' => $iso,
        ];
    };
    $events = array_values(array_map($normalizeEvent, (array) $events, array_keys((array) $events)));

    $id = NabuXUI::id('nx-order-tracking');
    $shownNumber = is_numeric($number) ? NabuXUI::formatNumber((float) $number, 0, $locale) : (string) $number;
    $listenerAttrs = $attributes->whereStartsWith(['x-on:', '@', 'wire:']);
    $rest = $attributes->whereDoesntStartWith(['x-on:', '@', 'wire:']);
@endphp
<section {{ $rest->class('nx-order-tracking')->merge(['aria-label' => $label ?? $say('tracking'), 'data-nx-reveal' => 'group']) }} {{ $listenerAttrs }}
    x-data="nxOrderTracking(@js(['locale' => $locale]))">
    <header class="nx-order-tracking-head">
        <div class="nx-order-tracking-order">
            <span class="nx-order-tracking-kicker">{{ $say('tracking') }}</span>
            @if ($shownNumber !== '')
                <p class="nx-order-tracking-number">
                    <span class="nx-visually-hidden">{{ $say('number') }}: </span>{{ $shownNumber }}
                </p>
            @endif
        </div>
        @if ($carrier || $eta)
            <dl class="nx-order-tracking-facts">
                @if ($carrier)<div class="nx-order-tracking-fact"><dt>{{ $say('carrier') }}</dt><dd>{{ $carrier }}</dd></div>@endif
                @if ($eta)<div class="nx-order-tracking-fact"><dt>{{ $say('eta') }}</dt><dd>{{ $eta }}</dd></div>@endif
            </dl>
        @endif
        @if ($status)
            <span class="nx-order-tracking-status">
                <x-nx::status-badge :status="$status" :label="$statusLabel" :live="$live" />
            </span>
        @endif
    </header>

    <ol class="nx-order-tracking-steps">
        @foreach ($steps as $i => $step)
            <li class="nx-order-tracking-step" data-state="{{ $step['state'] }}" wire:key="{{ $id }}-step-{{ $step['id'] }}"
                style="--nx-i: {{ $i }}" @if ($step['state'] === 'current') aria-current="step" @endif>
                <span class="nx-order-tracking-marker" aria-hidden="true">
                    @if ($step['state'] === 'complete' && ! $step['icon'])
                        {{ NabuXUI::icon('check') }}
                    @elseif ($step['icon'])
                        {{ NabuXUI::icon($step['icon']) }}
                    @else
                        {{ NabuXUI::formatNumber($i + 1, 0, $locale) }}
                    @endif
                </span>
                <div class="nx-order-tracking-step-text">
                    <p class="nx-order-tracking-step-title">{{ $step['title'] }}</p>
                    @if ($step['description'])<p class="nx-order-tracking-step-desc">{{ $step['description'] }}</p>@endif
                    @if ($step['timeLabel'] !== null && $step['timeLabel'] !== '')
                        <time class="nx-order-tracking-step-time" @if ($step['timeIso']) datetime="{{ $step['timeIso'] }}" @endif>{{ $step['timeLabel'] }}</time>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>

    <div class="nx-order-tracking-events">
        <h3 class="nx-order-tracking-events-title" id="{{ $id }}-events">{{ $say('events') }}</h3>
        <ol class="nx-order-tracking-events-list" data-nx-reveal="group" aria-labelledby="{{ $id }}-events">
            @forelse ($events as $i => $event)
                <li class="nx-order-tracking-event" @if ($event['tone']) data-tone="{{ $event['tone'] }}" @endif
                    wire:key="{{ $id }}-event-{{ $event['id'] }}" style="--nx-i: {{ $i }}">
                    <span class="nx-order-tracking-event-node" aria-hidden="true">
                        {{ NabuXUI::icon($event['icon'] ?? ($toneIcon[$event['tone']] ?? 'zap')) }}
                    </span>
                    <div class="nx-order-tracking-event-body">
                        <p class="nx-order-tracking-event-title">{{ $event['title'] }}</p>
                        @if ($event['description'])<p class="nx-order-tracking-event-desc">{{ $event['description'] }}</p>@endif
                        @if ($event['timeLabel'] !== null && $event['timeLabel'] !== '' || $event['place'])
                            <p class="nx-order-tracking-event-meta">
                                @if ($event['timeLabel'] !== null && $event['timeLabel'] !== '')
                                    <time class="nx-order-tracking-event-time" @if ($event['timeIso']) datetime="{{ $event['timeIso'] }}" @endif>{{ $event['timeLabel'] }}</time>
                                @endif
                                @if ($event['place'])
                                    @if ($event['timeLabel'] !== null && $event['timeLabel'] !== '')<span aria-hidden="true">·</span>@endif
                                    <span class="nx-order-tracking-event-place">{{ $event['place'] }}</span>
                                @endif
                            </p>
                        @endif
                    </div>
                </li>
            @empty
                <li class="nx-order-tracking-empty" wire:key="{{ $id }}-no-events">{{ $say('noEvents') }}</li>
            @endforelse
        </ol>
    </div>
</section>
