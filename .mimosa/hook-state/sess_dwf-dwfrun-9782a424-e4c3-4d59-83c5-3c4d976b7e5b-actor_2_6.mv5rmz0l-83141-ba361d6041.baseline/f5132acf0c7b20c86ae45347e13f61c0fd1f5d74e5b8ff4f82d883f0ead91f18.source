{{--
    <x-nx::timeline-feed label="جریان فعالیت" :items="[
        ['id' => 'a1', 'actor' => 'مریم رضایی', 'text' => 'سفارش را تأیید کرد', 'target' => '#۱۲۴۸', 'time' => now()->subMinutes(3), 'tone' => 'success'],
        ['id' => 'a2', 'icon' => 'upload', 'text' => 'پشتیبان‌گیری کامل شد', 'time' => now()->subHours(2), 'tone' => 'info'],
        ['id' => 'a3', 'actor' => 'سامان', 'text' => 'فاکتور را رد کرد', 'target' => '#۹۸۰۱', 'time' => now()->subDays(1), 'tone' => 'warning'],
    ]" />

    A vertical activity stream: rows of icon + text + relative time that reveal
    one after another (x-nx-reveal.group), stitched together by a gradient
    connector. `time` may be a date (shown relative, in the app's language) or a
    label as it is. tone: success | warning | info | danger | neutral (default).
--}}
@props(['items' => [], 'label' => null, 'locale' => null])
@php
    use Illuminate\Support\Carbon;
    $lang = substr($locale ?? app()->getLocale(), 0, 2);
    $defaultIcon = ['success' => 'check-circle', 'warning' => 'alert-triangle', 'info' => 'info', 'danger' => 'alert-circle'];
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
@endphp
<div {{ $attributes->class('nx-timeline-feed') }}>
    <ol class="nx-timeline-feed-list" data-nx-reveal="group" x-nx-reveal.group @if ($label) aria-label="{{ $label }}" @endif>
        @foreach ($items as $i => $item)
            @php
                [$label_, $iso] = $when($item['time'] ?? '');
                $tone = in_array($item['tone'] ?? '', ['success', 'warning', 'info', 'danger'], true) ? $item['tone'] : 'neutral';
            @endphp
            <li class="nx-timeline-feed-item" data-tone="{{ $tone }}" style="--nx-i: {{ $loop->index }}">
                <span class="nx-timeline-feed-node" aria-hidden="true">{{ \NabuXUI\NabuXUI::icon($item['icon'] ?? ($defaultIcon[$tone] ?? 'zap')) }}</span>
                <div class="nx-timeline-feed-body">
                    <p class="nx-timeline-feed-text">
                        @if (! empty($item['actor']))<strong>{{ $item['actor'] }}</strong> @endif
                        {{ $item['text'] ?? '' }}
                        @if (! empty($item['target']))
                            @if (! empty($item['href']))
                                <a href="{{ $item['href'] }}"><strong>{{ $item['target'] }}</strong></a>
                            @else
                                <strong>{{ $item['target'] }}</strong>
                            @endif
                        @endif
                    </p>
                    <time class="nx-timeline-feed-time" @if ($iso) datetime="{{ $iso }}" @endif>{{ $label_ }}</time>
                </div>
            </li>
        @endforeach
    </ol>
</div>
