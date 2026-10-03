{{--
    <x-nx::number-field label="Guests" name="guests" :value="2" :min="1" :max="12" wire:model.live="guests" />
    <x-nx::number-field label="Price" :value="49.5" :step="0.5" :format="['style' => 'currency', 'currency' => 'USD']" />

    A number input with − / + steppers (press and hold to repeat, speeding
    up), drag-to-scrub on the label (sideways, toward the reading end
    increases; Shift ×10, Alt slower), min / max / step snapping, Intl
    formatting in the locale's digits, and digits that roll when the value
    changes. Type any numbering system or separator convention ("۱۲٫۵",
    "1.234,5"); ↑/↓ step, Shift or PageUp/PageDown take a large step,
    Home/End jump to min/max. wire:model goes on the component and binds the
    number; `name` posts it. `scrub` false turns the label drag off.
--}}
@props(['value' => null, 'name' => null, 'label' => null, 'hint' => null, 'min' => null, 'max' => null, 'step' => 1, 'largeStep' => null, 'format' => null, 'disabled' => false, 'scrub' => true, 'labels' => [], 'locale' => null])
@php
    use NabuXUI\NabuXUI;

    $locale = str_replace('_', '-', $locale ?? app()->getLocale());
    $lang = substr($locale, 0, 2);
    $intl = ['fa' => 'fa-IR', 'en' => 'en-US', 'ar' => 'ar'][$locale] ?? $locale;
    $words = [
        'en' => ['increase' => 'Increase', 'decrease' => 'Decrease'],
        'fa' => ['increase' => 'افزایش', 'decrease' => 'کاهش'],
        'ar' => ['increase' => 'زيادة', 'decrease' => 'إنقاص'],
    ];
    $labels = array_merge($words[$lang] ?? $words['en'], is_array($labels) ? $labels : []);
    $truthy = fn ($v) => ! in_array(strtolower((string) $v), ['0', 'false', 'no', 'off', ''], true);
    $disabled = $disabled !== false && $truthy($disabled);
    $scrub = $scrub !== false && $truthy($scrub);
    $num = fn ($v) => $v === null || $v === '' ? null : (float) $v;
    $value = $num($value);
    $min = $num($min);
    $max = $num($max);
    $step = (float) ($step ?: 1);
    $decimals = str_contains((string) $step, '.') ? strlen(explode('.', (string) $step)[1]) : 0;
    $id = $attributes->get('id') ?? NabuXUI::id('nx-nf');
    $config = ['value' => $value, 'min' => $min, 'max' => $max, 'step' => $step, 'largeStep' => $num($largeStep), 'locale' => $intl, 'format' => is_array($format) ? $format : null];
    $text = $value === null ? '' : NabuXUI::formatNumber($value, $decimals, $locale);
@endphp
<div {{ $attributes->except('id')->class('nx-numfield')->merge(['data-disabled' => $disabled ? '' : null, 'data-no-scrub' => $scrub ? null : '']) }}
    x-data="nxNumberField(@js($config))" x-modelable="model" wire:ignore>
    @if ($label)
        <label x-ref="label" class="nx-numfield-label" for="{{ $id }}">{{ $label }}</label>
    @endif
    <div class="nx-numfield-group">
        <button x-ref="down" type="button" class="nx-numfield-step" data-dir="down" tabindex="-1" aria-label="{{ $labels['decrease'] }}"
            @disabled($disabled || ($min !== null && $value !== null && $value <= $min)) x-bind:disabled="{{ $disabled ? 'true' : 'atMin' }}">{{ NabuXUI::icon('minus') }}</button>
        <span class="nx-numfield-box">
            <input id="{{ $id }}" class="nx-numfield-input" type="text" role="spinbutton" autocomplete="off"
                inputmode="{{ fmod($step, 1.0) == 0.0 && ($min ?? 0) >= 0 ? 'numeric' : 'decimal' }}"
                @if ($min !== null) aria-valuemin="{{ $min }}" @endif
                @if ($max !== null) aria-valuemax="{{ $max }}" @endif
                @if ($value !== null) aria-valuenow="{{ $value }}" aria-valuetext="{{ $text }}" @endif
                value="{{ $text }}" @disabled($disabled)
                x-bind:value="draft ?? text" x-bind:aria-valuenow="value" x-bind:aria-valuetext="text || null"
                x-on:input="draft = $event.target.value" x-on:focus="$event.target.select()"
                x-on:blur="commit()" x-on:keydown="keydown($event)">
            <span x-ref="display" class="nx-number nx-numfield-display" aria-hidden="true" @if ($value === null) hidden @endif>
                <span class="nx-visually-hidden">{{ $text }}</span>
                <span class="nx-number-roll" aria-hidden="true">@if ($value !== null)@foreach (NabuXUI::numberParts($value, $decimals, $locale) as $i => $part)@if ($part['kind'] === 'digit')<span class="nx-digit" style="--d: {{ $part['value'] }}"><span class="nx-digit-track">@foreach (NabuXUI::digits($locale) as $digit)<span>{{ $digit }}</span>@endforeach</span></span>@else<span class="nx-number-sep">{{ $part['char'] }}</span>@endif @endforeach @endif</span>
            </span>
        </span>
        <button x-ref="up" type="button" class="nx-numfield-step" data-dir="up" tabindex="-1" aria-label="{{ $labels['increase'] }}"
            @disabled($disabled || ($max !== null && $value !== null && $value >= $max)) x-bind:disabled="{{ $disabled ? 'true' : 'atMax' }}">{{ NabuXUI::icon('plus') }}</button>
    </div>
    @if ($hint)<p class="nx-numfield-hint">{{ $hint }}</p>@endif
    @if ($name)
        <input type="hidden" name="{{ $name }}" value="{{ $value }}" x-bind:value="value ?? ''">
    @endif
</div>
