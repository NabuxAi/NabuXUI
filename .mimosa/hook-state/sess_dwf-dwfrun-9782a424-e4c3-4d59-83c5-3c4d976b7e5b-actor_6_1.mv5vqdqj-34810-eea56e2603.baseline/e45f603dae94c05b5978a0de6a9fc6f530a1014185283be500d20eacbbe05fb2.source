{{--
    <x-nx::time-picker name="pickup" label="Pickup time" value="18:30" :minute-step="5" wire:model.live="pickup" />

    Hour and minute drums that snap as they scroll (wheel, drag on touch, or
    the keyboard: focus a drum and use ↑/↓, PageUp/PageDown, Home/End). The
    face is 12- or 24-hour (`hour-cycle`, defaulting to 12 for English and 24
    elsewhere); digits and the AM/PM words follow the locale. The value is
    always "HH:MM" in 24 hours. wire:model goes on the component and binds
    it; `name` posts it in a plain form.
--}}
@props(['value' => null, 'name' => null, 'label' => null, 'hourCycle' => null, 'minuteStep' => 1, 'labels' => [], 'locale' => null])
@php
    use NabuXUI\NabuXUI;

    $locale = str_replace('_', '-', $locale ?? app()->getLocale());
    $lang = substr($locale, 0, 2);
    $intl = ['fa' => 'fa-IR', 'en' => 'en-US', 'ar' => 'ar'][$locale] ?? $locale;
    $words = [
        'en' => ['hour' => 'Hour', 'minute' => 'Minute', 'period' => 'AM or PM', 'am' => 'AM', 'pm' => 'PM'],
        'fa' => ['hour' => 'ساعت', 'minute' => 'دقیقه', 'period' => 'قبل یا بعد از ظهر', 'am' => 'ق.ظ.', 'pm' => 'ب.ظ.'],
        'ar' => ['hour' => 'الساعة', 'minute' => 'الدقيقة', 'period' => 'صباحًا أو مساءً', 'am' => 'ص', 'pm' => 'م'],
    ];
    $labels = array_merge($words[$lang] ?? $words['en'], is_array($labels) ? $labels : []);
    $twelve = $hourCycle !== null ? (int) $hourCycle === 12 : $lang === 'en';
    $step = max(1, min(30, (int) $minuteStep));
    $minutes = range(0, 59, $step);
    $digits = NabuXUI::digits($locale);
    $num = fn (int $n, int $pad = 2) => strtr(str_pad((string) $n, $pad, '0', STR_PAD_LEFT), array_combine(range(0, 9), $digits));

    $hour = 9;
    $minute = 0;
    if (is_string($value) && preg_match('/^(\d{1,2}):(\d{2})/', $value, $m) && (int) $m[1] < 24 && (int) $m[2] < 60) {
        [$hour, $minute] = [(int) $m[1], (int) $m[2]];
    }
    $clean = is_string($value) && $value !== '' ? sprintf('%02d:%02d', $hour, $minute) : null;
    $nearest = array_reduce($minutes, fn ($best, $s) => abs($s - $minute) < abs($best - $minute) ? $s : $best, 0);
    $hourIndex = $twelve ? (($hour % 12 === 0 ? 12 : $hour % 12) - 1) : $hour;
    $minuteIndex = array_search($nearest, $minutes, true) ?: 0;
    $pm = $hour >= 12;
    $id = NabuXUI::id('nx-tp');
    $config = ['value' => $clean, 'twelve' => $twelve, 'minutes' => $minutes, 'locale' => $intl];
    $wheels = [
        'hour' => ['label' => $labels['hour'], 'items' => $twelve ? array_map(fn ($h) => $num($h, 1), range(1, 12)) : array_map(fn ($h) => $num($h), range(0, 23)), 'at' => $hourIndex],
        'minute' => ['label' => $labels['minute'], 'items' => array_map(fn ($m) => $num($m), $minutes), 'at' => $minuteIndex],
    ];
    if ($twelve) {
        $wheels['period'] = ['label' => $labels['period'], 'items' => [$labels['am'], $labels['pm']], 'at' => $pm ? 1 : 0];
    }
@endphp
<div {{ $attributes->class('nx-timepicker')->merge(['role' => 'group', 'aria-label' => $label]) }}
    x-data="nxTimePicker(@js($config))" x-modelable="model" wire:ignore>
    <div class="nx-timepicker-wheels">
        <span class="nx-timepicker-band" aria-hidden="true"></span>
        @foreach ($wheels as $key => $wheel)
            @if ($key === 'minute')<span class="nx-timepicker-sep" aria-hidden="true">:</span>@endif
            @php
                $expr = $key === 'hour' ? 'hourIndex' : ($key === 'minute' ? 'minuteIndex' : 'periodIndex');
            @endphp
            <ul x-ref="{{ $key }}" class="nx-timepicker-wheel" role="listbox" tabindex="0" aria-label="{{ $wheel['label'] }}"
                @if ($key === 'period') data-period @endif
                aria-activedescendant="{{ $id }}-{{ $key }}-{{ $wheel['at'] }}"
                x-bind:aria-activedescendant="'{{ $id }}-{{ $key }}-' + {{ $expr }}"
                x-on:keydown="key('{{ $key }}', $event)">
                @foreach ($wheel['items'] as $i => $text)
                    <li id="{{ $id }}-{{ $key }}-{{ $i }}" class="nx-timepicker-option" role="option"
                        aria-selected="{{ $i === $wheel['at'] ? 'true' : 'false' }}"
                        x-bind:aria-selected="{{ $expr }} === {{ $i }} ? 'true' : 'false'"
                        x-on:click="pick('{{ $key }}', {{ $i }})">{{ $text }}</li>
                @endforeach
            </ul>
        @endforeach
    </div>
    <div class="nx-timepicker-foot">
        <p class="nx-timepicker-value" aria-live="polite" x-text="timeText">{{ $clean ? $num($hour).':'.$num($minute) : '—' }}</p>
    </div>
    @if ($name)
        <input type="hidden" name="{{ $name }}" value="{{ $clean }}" x-bind:value="value ?? ''">
    @endif
</div>
