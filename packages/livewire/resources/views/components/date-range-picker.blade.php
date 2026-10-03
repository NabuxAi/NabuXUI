{{--
    <x-nx::date-range-picker name="stay" label="Stay" :value="['start' => '2026-10-05', 'end' => '2026-10-09']"
        wire:model.live="stay" min="2026-10-03" />

    Two months of days in a popover: click a start, then an end (in either
    order) — the range previews under the pointer (or the focused day) until
    the second click — or pick a preset (Today, Yesterday, Last 7 days, Last
    30 days, This month, Last month). The calendar follows the locale: the
    Solar Hijri (Jalali) calendar for fa, Gregorian elsewhere — force one with
    calendar="persian|gregory"; weeks start on Saturday for fa/ar. Values are
    always ISO days ("YYYY-MM-DD"), so the server stores the same thing in
    both calendars. wire:model goes on the component and binds
    ['start' => …, 'end' => …]; `name` posts name[start] and name[end].
    presets: built-in ids (today, yesterday, last7, last30, thisMonth,
    lastMonth) or ['id', 'label', 'start', 'end'] of your own; [] hides them.
    Arrow keys walk the days, PageUp/PageDown the months, Home/End the week.
--}}
@props(['value' => null, 'name' => null, 'label' => null, 'placeholder' => null, 'calendar' => null, 'weekStart' => null, 'presets' => null, 'min' => null, 'max' => null, 'months' => 2, 'today' => null, 'labels' => [], 'locale' => null])
@php
    use NabuXUI\NabuXUI;

    $locale = str_replace('_', '-', $locale ?? app()->getLocale());
    $lang = substr($locale, 0, 2);
    $intl = ['fa' => 'fa-IR', 'en' => 'en-US', 'ar' => 'ar'][$locale] ?? $locale;
    $words = [
        'en' => ['pickRange' => 'Pick a date range', 'presets' => 'Presets', 'today' => 'Today', 'yesterday' => 'Yesterday', 'last7' => 'Last 7 days', 'last30' => 'Last 30 days', 'thisMonth' => 'This month', 'lastMonth' => 'Last month', 'nights' => '{count} days', 'selectEnd' => 'Now pick the end date', 'clear' => 'Clear', 'prev' => 'Previous month', 'next' => 'Next month'],
        'fa' => ['pickRange' => 'بازهٔ تاریخ را انتخاب کنید', 'presets' => 'بازه‌های آماده', 'today' => 'امروز', 'yesterday' => 'دیروز', 'last7' => '۷ روز گذشته', 'last30' => '۳۰ روز گذشته', 'thisMonth' => 'این ماه', 'lastMonth' => 'ماه گذشته', 'nights' => '{count} روز', 'selectEnd' => 'حالا تاریخ پایان را انتخاب کنید', 'clear' => 'پاک کردن', 'prev' => 'ماه قبل', 'next' => 'ماه بعد'],
        'ar' => ['pickRange' => 'اختر نطاقًا زمنيًا', 'presets' => 'نطاقات جاهزة', 'today' => 'اليوم', 'yesterday' => 'أمس', 'last7' => 'آخر 7 أيام', 'last30' => 'آخر 30 يومًا', 'thisMonth' => 'هذا الشهر', 'lastMonth' => 'الشهر الماضي', 'nights' => '{count} أيام', 'selectEnd' => 'اختر الآن تاريخ الانتهاء', 'clear' => 'مسح', 'prev' => 'الشهر السابق', 'next' => 'الشهر التالي'],
    ];
    $labels = array_merge($words[$lang] ?? $words['en'], is_array($labels) ? $labels : []);
    $calendar = in_array($calendar, ['persian', 'gregory'], true) ? $calendar : ($lang === 'fa' ? 'persian' : 'gregory');
    $weekStart = $weekStart !== null ? (int) $weekStart : (in_array($lang, ['fa', 'ar'], true) ? 6 : 0);
    $presets = $presets === null ? ['today', 'yesterday', 'last7', 'last30', 'thisMonth', 'lastMonth'] : array_values((array) $presets);
    $value = is_array($value) ? ['start' => $value['start'] ?? null, 'end' => $value['end'] ?? null] : ['start' => null, 'end' => null];
    $id = $attributes->get('id') ?? NabuXUI::id('nx-dr');
    $config = [
        'value' => $value, 'calendar' => $calendar, 'weekStart' => $weekStart, 'locale' => $intl, 'presets' => $presets,
        'min' => $min, 'max' => $max, 'months' => max(1, min(2, (int) $months)), 'today' => $today, 'labels' => $labels,
    ];
    // First paint of the trigger, before Alpine formats it in the reader's calendar.
    $initialText = $value['start'] && $value['end'] ? $value['start'].' – '.$value['end'] : null;
@endphp
<div {{ $attributes->except('id')->class('nx-daterange')->merge(['id' => $id]) }}
    x-data="nxDateRange(@js($config))" x-modelable="model" wire:ignore>
    <button x-ref="trigger" type="button" class="nx-daterange-trigger" aria-haspopup="dialog" aria-controls="{{ $id }}-pop" aria-expanded="false"
        x-bind:aria-expanded="open ? 'true' : 'false'"
        @if ($label) aria-label="{{ $label }}: {{ $initialText ?? $placeholder ?? $labels['pickRange'] }}" x-bind:aria-label="@js($label) + ': ' + (valueText || @js($placeholder ?? $labels['pickRange']))" @endif
        x-on:click="triggerClick()">
        {{ NabuXUI::icon('grid') }}
        <span class="nx-daterange-value" @if (! $initialText) data-empty @endif x-bind:data-empty="valueText ? null : ''"
            x-text="valueText || @js($placeholder ?? $labels['pickRange'])">{{ $initialText ?? $placeholder ?? $labels['pickRange'] }}</span>
        {{ NabuXUI::icon('chevron-down') }}
    </button>
    @if ($name)
        <input type="hidden" name="{{ $name }}[start]" value="{{ $value['start'] }}" x-bind:value="value.start ?? ''">
        <input type="hidden" name="{{ $name }}[end]" value="{{ $value['end'] }}" x-bind:value="value.end ?? ''">
    @endif
    <div x-ref="pop" id="{{ $id }}-pop" class="nx-picker-pop nx-daterange-popover" popover="auto" role="dialog" aria-label="{{ $label ?? $labels['pickRange'] }}"
        @if (count($presets) === 0) style="grid-template-columns: 1fr" @endif>
        @if (count($presets) > 0)
            <ul class="nx-daterange-presets" aria-label="{{ $labels['presets'] }}">
                <template x-for="p in presets" :key="p.id">
                    <li><button type="button" class="nx-daterange-preset" x-bind:aria-pressed="presetActive(p) ? 'true' : 'false'" x-on:click="preset(p)" x-text="p.label"></button></li>
                </template>
            </ul>
        @endif
        <div class="nx-daterange-main">
            <div class="nx-daterange-months" style="--nx-daterange-months: {{ $config['months'] }}" x-bind:data-enter="enter">
                <template x-for="(m, mi) in (open ? monthsData : [])" :key="m.start">
                    <section class="nx-daterange-month">
                        <header class="nx-daterange-head">
                            <button type="button" class="nx-daterange-nav" data-at="prev" aria-label="{{ $labels['prev'] }}" x-on:click="go(-1)">{{ NabuXUI::icon('chevron-left') }}</button>
                            <h3 x-bind:id="'{{ $id }}-m' + mi" aria-live="polite" x-text="m.title"></h3>
                            <button type="button" class="nx-daterange-nav" data-at="next" aria-label="{{ $labels['next'] }}" x-on:click="go(1)">{{ NabuXUI::icon('chevron-right') }}</button>
                        </header>
                        <div class="nx-daterange-grid" role="grid" x-bind:aria-labelledby="'{{ $id }}-m' + mi" x-on:keydown="gridKey($event)">
                            <div class="nx-daterange-row" role="row">
                                <template x-for="(w, wi) in weekdays" :key="wi">
                                    <span class="nx-daterange-weekday" role="columnheader" x-bind:aria-label="longWeekdays[wi]" x-text="w"></span>
                                </template>
                            </div>
                            <template x-for="(row, ri) in m.weeks" :key="ri">
                                <div class="nx-daterange-row" role="row">
                                    <template x-for="(cell, ci) in row" :key="ci">
                                        <span class="nx-daterange-cell" role="gridcell"
                                            x-bind:data-mark="cell ? mark(cell.iso) : null"
                                            x-bind:data-preview="cell && previewing(cell.iso) ? '' : null"
                                            x-bind:aria-selected="cell && mark(cell.iso) ? 'true' : null">
                                            <template x-if="cell">
                                                <button type="button" class="nx-daterange-day" x-bind:data-date="cell.iso"
                                                    x-bind:data-today="cell.iso === today ? '' : null"
                                                    x-bind:aria-current="cell.iso === today ? 'date' : null"
                                                    x-bind:aria-label="cell.label" x-bind:tabindex="cell.iso === focusDay ? 0 : -1"
                                                    x-bind:disabled="blocked(cell.iso)"
                                                    x-on:click="click(cell.iso)" x-on:pointerenter="hoverDay(cell.iso)" x-on:focus="hoverDay(cell.iso)"
                                                    x-text="cell.day"></button>
                                            </template>
                                        </span>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </section>
                </template>
            </div>
            <footer class="nx-daterange-foot">
                <p class="nx-daterange-summary" role="status" aria-live="polite" x-text="summary"></p>
                <div class="nx-daterange-actions">
                    <button type="button" class="nx-daterange-preset" x-on:click="clearRange()">{{ $labels['clear'] }}</button>
                </div>
            </footer>
        </div>
    </div>
</div>
