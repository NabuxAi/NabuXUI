{{--
    <x-nx::sort-pill :options="['featured' => 'منتخب', 'recent' => 'تازه‌ها']" wire:model.live="state.sort" />

    A quick single-choice sort pill ("Featured ∨"). Every option's label is
    stacked inside the trigger and the chosen one rolls in while the panel
    folds away; the picked row shows the springy check. The picked value posts
    as `name` and syncs the wire:model. An option may also be an array with
    `label` and `icon` keys.
--}}
@props([
    'options' => [],
    'value' => null,
    'name' => 'sort',
    'label' => null,
    'align' => 'start',
    'locale' => null,
])
@php
    use NabuXUI\NabuXUI;
    $id = NabuXUI::id('nx-sort');
    $model = NabuXUI::model($attributes);
    $wire = $attributes->whereStartsWith('wire:model');
    $lang = substr($locale ?? app()->getLocale(), 0, 2);
    $t = fn (string $en, string $fa, string $ar) => match ($lang) { 'fa' => $fa, 'ar' => $ar, default => $en };
    $label ??= $t('Sort', 'مرتب‌سازی', 'الترتيب');
    $align = in_array($align, ['start', 'center', 'end'], true) ? $align : 'start';
    $options = array_values(array_map(fn ($option, $key) => [
        'value' => (string) $key,
        'label' => (string) (is_array($option) ? ($option['label'] ?? $key) : $option),
        'icon' => is_array($option) ? ($option['icon'] ?? null) : null,
    ], $options, array_keys($options)));
    $current = collect($options)->firstWhere('value', (string) $value) ?? $options[0] ?? null;
@endphp
<div style="display: contents" x-data="nxSortPill(@js($options), @js($current['value'] ?? null), @js($model), @js($align))">
    <button type="button" {{ $attributes->whereDoesntStartWith('wire:model')->class('nx-sort-pill-trigger') }} x-ref="trigger"
        popovertarget="{{ $id }}" aria-controls="{{ $id }}" aria-haspopup="listbox" aria-expanded="false"
        x-bind:aria-expanded="open ? 'true' : 'false'" aria-label="{{ $label }}: {{ $current['label'] ?? '' }}">
        <span class="nx-sort-pill-labels" aria-hidden="true">
            @foreach ($options as $option)
                <span class="nx-sort-pill-label" @if (($current['value'] ?? null) === $option['value']) data-current @endif
                    x-bind:data-current="selected === @js($option['value']) ? '' : null">{{ $option['label'] }}</span>
            @endforeach
        </span>
        <span class="nx-sort-pill-chevron" aria-hidden="true">{{ NabuXUI::icon('chevron-down') }}</span>
    </button>
    <div class="nx-sort-pill" id="{{ $id }}" x-ref="panel" popover="auto" wire:ignore.self role="listbox" aria-label="{{ $label }}"
        x-on:keydown="keyList($event)">
        <ul class="nx-sort-pill-list" x-ref="list">
            @foreach ($options as $option)
                <li class="nx-sort-pill-option" @if (($current['value'] ?? null) === $option['value']) data-selected @endif
                    x-bind:data-selected="selected === @js($option['value']) ? '' : null">
                    <button type="button" class="nx-sort-pill-choice" role="option"
                        aria-selected="{{ ($current['value'] ?? null) === $option['value'] ? 'true' : 'false' }}"
                        x-bind:aria-selected="selected === @js($option['value']) ? 'true' : 'false'"
                        x-on:click="choose(@js($option['value']))">
                        @if ($option['icon']){{ NabuXUI::icon($option['icon']) }}@endif
                        <span>{{ $option['label'] }}</span>
                        <span class="nx-sort-pill-mark">{{ NabuXUI::icon('check') }}</span>
                    </button>
                </li>
            @endforeach
        </ul>
        <input type="hidden" name="{{ $name }}" value="{{ $current['value'] ?? '' }}" x-bind:value="selected" {{ $wire }}>
    </div>
</div>
