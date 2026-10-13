{{--
    <x-nx::chip-filter :options="['all' => 'All', 'design' => 'Design', 'code' => 'Code']"
        :counts="['design' => 12, 'code' => 7]" wire:model.live="category" />

    A single-select row of filter chips — the trending-tags pattern. Every chip
    is a radio under an accent thumb (core indicator) that springs to the
    checked one; the row scrolls horizontally and fades at its edges. An option
    is a plain label or an array: ['label' => 'Design', 'icon' => 'grid',
    'disabled' => true]. Counts are optional, keyed like the options. The
    wire:model works like the segmented control, on the radios themselves.
--}}
@props([
    'options' => [],
    'counts' => [],
    'value' => null,
    'name' => null,
    'label' => null,
    'locale' => null,
])
@php
    use NabuXUI\NabuXUI;
    $lang = substr($locale ?? app()->getLocale(), 0, 2);
    $t = fn (string $en, string $fa, string $ar) => match ($lang) { 'fa' => $fa, 'ar' => $ar, default => $en };
    $label ??= $t('Filter', 'فیلتر', 'تصفية');
    $name ??= NabuXUI::model($attributes) ?? NabuXUI::id('nx-chip-filter');
    $value ??= array_key_first($options);
@endphp
<div {{ $attributes->whereDoesntStartWith('wire:model')->class('nx-chip-filter')->merge(['role' => 'group', 'aria-label' => $label]) }} x-data="nxChipFilter()">
    <div class="nx-chip-filter-row" x-ref="row">
        <span class="nx-indicator nx-chip-filter-thumb" aria-hidden="true"></span>
        @foreach ($options as $key => $option)
            @php $option = is_array($option) ? $option : ['label' => $option]; @endphp
            <label class="nx-chip-filter-chip">
                <input class="nx-chip-filter-input" type="radio" name="{{ $name }}" value="{{ $key }}" {{ $attributes->whereStartsWith('wire:model') }} @checked((string) $key === (string) $value) @disabled($option['disabled'] ?? false)>
                @if (! empty($option['icon'])){{ NabuXUI::icon($option['icon']) }}@endif
                <span>{{ $option['label'] }}</span>
                @if (isset($counts[$key]))<span class="nx-chip-filter-count">{{ $counts[$key] }}</span>@endif
            </label>
        @endforeach
    </div>
</div>
