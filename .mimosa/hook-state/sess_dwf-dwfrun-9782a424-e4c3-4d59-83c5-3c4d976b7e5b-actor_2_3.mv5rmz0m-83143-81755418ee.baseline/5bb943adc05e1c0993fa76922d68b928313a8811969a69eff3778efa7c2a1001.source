{{--
    <x-nx::combobox :options="$cities" placeholder="Search a city" label="Destination"
        wire:model.live="destination" name="destination" />

    A typeahead single-select: type to filter (the match is underlined in
    each option), arrows / Home / End move, Enter picks, Escape closes (and,
    once more, clears). options: ['thr' => 'Tehran', …] or a list of
    ['value', 'label', 'description', 'icon', 'keywords', 'disabled'].
    wire:model goes on the component (x-modelable) and binds the chosen value;
    `name` posts it in a plain form. For server search give `search` the name
    of a Livewire method that takes the typed text and returns options in the
    same shape — the component calls it (debounced) and shows a spinner while
    it runs. An `nx-change` event bubbles with the new value.
--}}
@props(['options' => [], 'value' => null, 'placeholder' => null, 'label' => null, 'name' => null, 'disabled' => false, 'clearable' => true, 'filter' => true, 'search' => null, 'emptyText' => null, 'labels' => []])
@php
    use NabuXUI\NabuXUI;

    $id = $attributes->get('id') ?? NabuXUI::id('nx-cb');
    $lang = substr(app()->getLocale(), 0, 2);
    $words = [
        'en' => ['noMatches' => 'No matches', 'loading' => 'Searching…', 'clear' => 'Clear', 'showOptions' => 'Show options'],
        'fa' => ['noMatches' => 'موردی پیدا نشد', 'loading' => 'در حال جست‌وجو…', 'clear' => 'پاک کردن', 'showOptions' => 'نمایش گزینه‌ها'],
        'ar' => ['noMatches' => 'لا نتائج', 'loading' => 'جارٍ البحث…', 'clear' => 'مسح', 'showOptions' => 'عرض الخيارات'],
    ];
    $labels = array_merge($words[$lang] ?? $words['en'], is_array($labels) ? $labels : []);
    $truthy = fn ($v) => ! in_array(strtolower((string) $v), ['0', 'false', 'no', 'off', ''], true);
    $clearable = $truthy($clearable);
    $filter = $truthy($filter);
    $disabled = $disabled !== false && $truthy($disabled);

    $list = [];
    foreach ($options as $key => $option) {
        $option = is_array($option) ? $option + ['value' => $key] : ['value' => $key, 'label' => $option];
        $icon = $option['icon'] ?? null;
        $list[] = [
            'value' => (string) $option['value'],
            'label' => (string) ($option['label'] ?? $option['value']),
            'description' => isset($option['description']) ? (string) $option['description'] : null,
            'keywords' => array_values(array_map('strval', (array) ($option['keywords'] ?? []))),
            'disabled' => (bool) ($option['disabled'] ?? false),
            'iconSvg' => $icon && NabuXUI::hasIcon($icon) ? (string) NabuXUI::icon($icon) : null,
        ];
    }
    $value = $value === null || $value === '' ? null : (string) $value;
    $currentLabel = collect($list)->firstWhere('value', $value)['label'] ?? '';
    $config = ['options' => $list, 'value' => $value, 'clearable' => $clearable, 'filter' => $filter, 'search' => $search, 'labels' => $labels];
@endphp
<div {{ $attributes->except('id')->class('nx-combobox')->merge(['id' => $id, 'data-disabled' => $disabled ? '' : null]) }}
    x-data="nxCombobox(@js($config))" x-modelable="model"
    x-bind:data-open="open ? '' : null" wire:ignore>
    <div class="nx-combobox-field" x-ref="field" x-on:click="$refs.input.focus()">
        <input x-ref="input" id="{{ $id }}-input" class="nx-combobox-input" type="text" role="combobox"
            autocomplete="off" spellcheck="false" aria-autocomplete="list" aria-controls="{{ $id }}-list" aria-expanded="false"
            @if ($label) aria-label="{{ $label }}" @endif
            @if ($placeholder) placeholder="{{ $placeholder }}" @endif
            value="{{ $currentLabel }}" @disabled($disabled)
            x-bind:value="query" x-bind:aria-expanded="open ? 'true' : 'false'"
            x-bind:aria-activedescendant="open && active >= 0 ? optionId(active) : null"
            x-bind:aria-busy="loading ? 'true' : null"
            x-on:input="input($event.target.value)" x-on:keydown="keydown($event)"
            x-on:blur="blur($event)" x-on:click="show()">
        @if ($clearable)
            <button type="button" class="nx-combobox-clear" aria-label="{{ $labels['clear'] }}" @disabled($disabled)
                @if ($currentLabel === '') data-hidden tabindex="-1" @endif
                x-bind:data-hidden="query ? null : ''" x-bind:tabindex="query ? null : -1"
                x-on:click.stop="clear()">{{ NabuXUI::icon('x') }}</button>
        @endif
        <button type="button" class="nx-combobox-toggle" tabindex="-1" aria-label="{{ $labels['showOptions'] }}" aria-controls="{{ $id }}-list" @disabled($disabled)
            x-bind:aria-expanded="open ? 'true' : 'false'"
            x-on:mousedown.prevent x-on:click.stop="toggle()">{{ NabuXUI::icon('chevron-down') }}</button>
    </div>
    @if ($name)
        <input type="hidden" name="{{ $name }}" value="{{ $value }}" x-bind:value="value ?? ''">
    @endif
    <div x-ref="pop" class="nx-picker-pop nx-combobox-popover" popover="manual" x-on:mousedown.prevent>
        <ul x-ref="list" id="{{ $id }}-list" class="nx-combobox-list" role="listbox" @if ($label ?? $placeholder) aria-label="{{ $label ?? $placeholder }}" @endif>
                    <template x-for="(i, n) in (open ? visible : [])" :key="options[i].value">
                        <li class="nx-combobox-option" role="option" x-bind:id="optionId(i)" x-bind:data-index="i"
                            x-bind:style="'--nx-i: ' + n"
                            x-bind:aria-selected="options[i].value === value ? 'true' : 'false'"
                            x-bind:aria-disabled="options[i].disabled ? 'true' : null"
                            x-bind:data-active="i === active ? '' : null"
                            x-on:pointermove="if (!options[i].disabled && active !== i) active = i"
                            x-on:click="choose(i)">
                            <span x-show="options[i].iconSvg" x-html="options[i].iconSvg" style="display: contents"></span>
                            <span class="nx-combobox-option-text">
                                <span class="nx-combobox-option-label" x-html="labelHtml(i)"></span>
                                <span class="nx-combobox-option-desc" x-show="options[i].description" x-text="options[i].description"></span>
                            </span>
                            <span class="nx-combobox-check" aria-hidden="true">{{ NabuXUI::icon('check') }}</span>
                        </li>
                    </template>
        </ul>
        <p class="nx-combobox-empty" role="status" hidden x-bind:hidden="!(open && loading)">
            <span class="nx-combobox-spinner" aria-hidden="true"></span>{{ $labels['loading'] }}
        </p>
        <p class="nx-combobox-empty" role="status" hidden x-bind:hidden="!(open && !loading && visible.length === 0)">{{ $emptyText ?? $labels['noMatches'] }}</p>
    </div>
</div>
