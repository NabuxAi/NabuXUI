{{--
    <x-nx::multi-select name="languages" placeholder="Reply languages" :options="[
        ['value' => 'en', 'label' => 'English', 'icon' => 'globe', 'description' => 'Default'],
        ['value' => 'es', 'label' => 'Español'],
    ]" :value="['en']" wire:model.live="state.languages" />

    options: ['en' => 'English', …] or a list of ['value', 'label', 'icon', 'description', 'disabled'].
    wire:model goes on the component itself: it binds the array of values through
    x-modelable (Livewire 3 and 4). `name` also posts each value as name[] in a plain form.
    The component is wire:ignore'd (Alpine owns its chips); give it a new wire:key to swap its options.
--}}
@props(['options' => [], 'value' => [], 'placeholder' => null, 'searchPlaceholder' => null, 'emptyText' => null, 'max' => null, 'name' => null, 'disabled' => false, 'label' => null])
@php
    use NabuXUI\NabuXUI;
    $id = $attributes->get('id') ?? NabuXUI::id('nx-ms');
    $list = [];
    foreach ($options as $key => $option) {
        $option = is_array($option) ? $option + ['value' => $key] : ['value' => $key, 'label' => $option];
        $list[] = [
            'value' => (string) $option['value'],
            'label' => (string) ($option['label'] ?? $option['value']),
            'icon' => $option['icon'] ?? null,
            'description' => $option['description'] ?? null,
            'disabled' => (bool) ($option['disabled'] ?? false),
        ];
    }
    $byValue = array_column($list, null, 'value');
    $selected = array_values(array_map('strval', (array) $value));
    $lang = substr(app()->getLocale(), 0, 2);
    $selectedLabel = ['fa' => "انتخاب\u{200C}شده\u{200C}ها", 'ar' => 'العناصر المحددة'][$lang] ?? 'Selected';
    $accessible = $label ?? $placeholder;
    $search = $searchPlaceholder ?? __('nabuxui::ui.search');
@endphp
<div {{ $attributes->except('id')->class('nx-multiselect')->merge(['data-disabled' => $disabled ? '' : null]) }}
    x-data="nxMultiSelect(@js($list), @js($selected), @js($max !== null ? (int) $max : null), @js(__('nabuxui::ui.remove')))"
    x-modelable="model" x-bind:data-open="open ? '' : null" wire:ignore>
    <div class="nx-multiselect-field" x-ref="field" x-on:click="fieldClick($event)">
        <ul class="nx-multiselect-chips" aria-label="{{ $selectedLabel }}">
            {{-- The chosen values on first paint; Alpine's chips replace them. --}}
            @foreach ($selected as $key)
                <li class="nx-multiselect-chip" data-state="idle" x-init="$el.remove()">
                    <span class="nx-multiselect-chip-body"><span class="nx-multiselect-chip-pill">
                        @if (! empty($byValue[$key]['icon']) && NabuXUI::hasIcon($byValue[$key]['icon'])){{ NabuXUI::icon($byValue[$key]['icon']) }}@endif<span>{{ $byValue[$key]['label'] ?? $key }}</span>
                    </span></span>
                </li>
            @endforeach
            <template x-for="chip in chips" :key="chip.key">
                <li class="nx-multiselect-chip" x-bind:data-state="chip.state" x-on:transitionend="settle($event, chip.key)">
                    <span class="nx-multiselect-chip-body"><span class="nx-multiselect-chip-pill">
                        <span x-html="iconOf(chip.key)" style="display: contents"></span>
                        <span x-text="labelOf(chip.key)"></span>
                        @unless ($disabled)
                            <button type="button" class="nx-multiselect-chip-remove" x-bind:aria-label="removeText(chip.key)" x-bind:tabindex="chip.state === 'exit' ? -1 : null"
                                x-on:click.stop="remove(chip.key, $event.currentTarget)">{{ NabuXUI::icon('x') }}</button>
                        @endunless
                    </span></span>
                </li>
            </template>
        </ul>
        <button type="button" x-ref="trigger" class="nx-multiselect-trigger" aria-haspopup="listbox" aria-controls="{{ $id }}-list" aria-expanded="false"
            x-bind:aria-expanded="open ? 'true' : 'false'"
            @if ($accessible) aria-label="{{ $accessible }}" x-bind:aria-label="@js($accessible) + (value.length ? ' (' + value.length + ')' : '')" @endif
            x-on:keydown="triggerKey($event)" x-on:click="click()" @disabled($disabled)>
            <span class="nx-multiselect-placeholder" x-text="value.length ? '' : @js((string) $placeholder)">{{ count($selected) ? '' : $placeholder }}</span>
            {{ NabuXUI::icon('chevron-down', 'nx-multiselect-chevron') }}
        </button>
    </div>

    <div x-ref="popover" id="{{ $id }}-list" class="nx-multiselect-popover" popover="auto">
        <div class="nx-multiselect-search">
            {{ NabuXUI::icon('search') }}
            <input x-ref="input" class="nx-multiselect-input" role="combobox" aria-expanded="true" aria-autocomplete="list" aria-controls="{{ $id }}-listbox"
                x-bind:aria-activedescendant="active >= 0 ? '{{ $id }}-opt-' + active : null"
                aria-label="{{ $search }}" placeholder="{{ $search }}" x-model="query" x-on:keydown="inputKey($event)">
        </div>
        <ul x-ref="list" id="{{ $id }}-listbox" class="nx-multiselect-list" role="listbox" aria-multiselectable="true" @if ($accessible) aria-label="{{ $accessible }}" @endif>
            @foreach ($list as $i => $option)
                <li id="{{ $id }}-opt-{{ $i }}" data-index="{{ $i }}" class="nx-multiselect-option" role="option" style="--nx-i: {{ $i }}"
                    aria-selected="{{ in_array($option['value'], $selected, true) ? 'true' : 'false' }}"
                    x-bind:aria-selected="picked({{ $i }}) ? 'true' : 'false'"
                    x-bind:aria-disabled="blocked({{ $i }}) ? 'true' : null"
                    x-bind:data-active="active === {{ $i }} ? '' : null"
                    x-bind:hidden="! matches({{ $i }})"
                    x-on:pointermove="if (active !== {{ $i }} && ! blocked({{ $i }})) active = {{ $i }}"
                    x-on:mousedown.prevent x-on:click="toggle({{ $i }})">
                    <span class="nx-multiselect-check" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M4.5 12.5l5 5L19.5 6.5" pathLength="1"/></svg></span>
                    @if ($option['icon'] && NabuXUI::hasIcon($option['icon']))<span class="nx-multiselect-option-icon">{{ NabuXUI::icon($option['icon']) }}</span>@endif
                    <span class="nx-multiselect-option-text">
                        <span class="nx-multiselect-option-label">{{ $option['label'] }}</span>
                        @if ($option['description'])<span class="nx-multiselect-option-description">{{ $option['description'] }}</span>@endif
                    </span>
                </li>
            @endforeach
        </ul>
        <p class="nx-multiselect-empty" x-show="! visible.length" x-cloak>{{ $emptyText ?? __('nabuxui::ui.noResults') }}</p>
    </div>

    @if ($name)
        @foreach ($selected as $v)<input type="hidden" name="{{ $name }}[]" value="{{ $v }}" x-init="$el.remove()">@endforeach
        <template x-for="v in value" :key="v"><input type="hidden" name="{{ $name }}[]" x-bind:value="v"></template>
    @endif
</div>
