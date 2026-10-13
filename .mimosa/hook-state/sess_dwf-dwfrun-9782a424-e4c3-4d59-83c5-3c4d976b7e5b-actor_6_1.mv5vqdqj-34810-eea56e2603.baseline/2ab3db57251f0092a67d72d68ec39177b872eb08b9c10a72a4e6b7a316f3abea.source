{{--
    <x-nx::label-creator :labels="[['name' => 'Bug', 'color' => 'pink']]" name="labels" />
    <x-nx::label-creator wire:model.live="labels" x-on:nx-label-created="$wire.ping('Created ' + $event.detail.name)" />

    Chips in a field (Notion-style). Typing a new name opens "Create “name”" with a
    colour picker (native radios); creating plays a tiny pixel loader, then the chip
    pops in. Chips are removable. Alpine owns the list: it dispatches
    `nx-label-created` ({name, color}); with wire:model it follows the Livewire
    property (a list of ['name' => …, 'color' => …]); with `name` it posts JSON.
    create-text marks the typed name with :name.
--}}
@props([
    'labels' => [],
    'colors' => null,
    'placeholder' => 'Add a label…',
    'label' => 'Labels',
    'colorLabel' => 'Colour',
    'createText' => 'Create “:name”',
    'name' => null,
])
@php
    $model = \NabuXUI\NabuXUI::model($attributes);
    $live = collect(array_keys($attributes->getAttributes()))->contains(fn ($key) => str_starts_with($key, 'wire:model') && str_contains($key, '.live'));
    $colors = array_values($colors ?? [
        ['value' => 'indigo', 'label' => 'Indigo', 'color' => 'var(--nx-chart-1)'],
        ['value' => 'pink', 'label' => 'Pink', 'color' => 'var(--nx-chart-2)'],
        ['value' => 'ochre', 'label' => 'Ochre', 'color' => 'var(--nx-chart-3)'],
        ['value' => 'teal', 'label' => 'Teal', 'color' => 'var(--nx-chart-4)'],
        ['value' => 'orange', 'label' => 'Orange', 'color' => 'var(--nx-chart-5)'],
        ['value' => 'violet', 'label' => 'Violet', 'color' => 'var(--nx-chart-6)'],
        ['value' => 'green', 'label' => 'Green', 'color' => 'var(--nx-chart-7)'],
    ]);
    $initial = [];
    foreach ((array) $labels as $item) {
        $initial[] = ['name' => (string) ($item['name'] ?? ''), 'color' => (string) ($item['color'] ?? '')];
    }
    $colorOf = function (string $value) use ($colors): string {
        foreach ($colors as $swatch) {
            if (($swatch['value'] ?? null) === $value) {
                return $swatch['color'];
            }
        }

        return $value;
    };
    $id = \NabuXUI\NabuXUI::id('nx-labels');
    [$before, $after] = array_pad(explode(':name', $createText, 2), 2, '');
    $texts = ['remove' => __('nabuxui::ui.remove', ['name' => ':name'])];
@endphp
<div {{ $attributes->whereDoesntStartWith('wire:model')->class('nx-label-creator') }} wire:ignore
    @if ($model) x-data="nxLabelCreator(@entangle($model){{ $live ? '.live' : '' }}, @js($colors), @js($texts))" @else x-data="nxLabelCreator(@js($initial), @js($colors), @js($texts))" @endif>
    <div class="nx-label-creator-field" x-on:click="if ($event.target === $el) $refs.input.focus()">
        <ul class="nx-label-creator-chips" aria-label="{{ $label }}" x-show="list().length > 0" @if (! $initial) style="display: none" @endif>
            @foreach ($initial as $item)
                <li class="nx-label-chip" data-ssr style="--_c: {{ $colorOf($item['color']) }}">
                    <span class="nx-label-chip-dot" aria-hidden="true"></span>
                    <span class="nx-label-chip-name">{{ $item['name'] }}</span>
                    <button type="button" class="nx-label-chip-remove" aria-label="{{ __('nabuxui::ui.remove', ['name' => $item['name']]) }}">{{ \NabuXUI\NabuXUI::icon('x') }}</button>
                </li>
            @endforeach
            <template x-for="item in list()" x-bind:key="item.name">
                <li class="nx-label-chip" x-bind:style="`--_c: ${colorOf(item.color)}`" x-bind:data-state="fresh === item.name ? 'enter' : null" x-bind:data-flash="flash === item.name ? '' : null">
                    <span class="nx-label-chip-dot" aria-hidden="true"></span>
                    <span class="nx-label-chip-name" x-text="item.name"></span>
                    <button type="button" class="nx-label-chip-remove" x-bind:aria-label="removeText(item.name)" x-on:click="remove(item, $el)">{{ \NabuXUI\NabuXUI::icon('x') }}</button>
                </li>
            </template>
        </ul>
        <input x-ref="input" class="nx-label-creator-input" type="text" autocomplete="off" placeholder="{{ $placeholder }}" aria-label="{{ $label }}" x-model="query"
            x-on:keydown.enter="if (! $event.isComposing) { $event.preventDefault(); create() }"
            x-on:keydown.escape="if (query) { $event.preventDefault(); query = '' }">
    </div>
    @if ($name)
        <input type="hidden" name="{{ $name }}" x-bind:value="JSON.stringify(list())" value="{{ json_encode($initial) }}">
    @endif
    <div class="nx-label-creator-panel" x-bind:data-open="open() ? '' : null" x-bind:style="`--_c: ${colorOf(color())}`">
        <button type="button" class="nx-label-creator-create" x-bind:data-state="creating ? 'creating' : null" x-bind:aria-busy="creating ? 'true' : null" x-on:click="create()">
            <span class="nx-label-creator-create-icon" aria-hidden="true">
                {{ \NabuXUI\NabuXUI::icon('plus') }}
                <x-nx::pixel-loader :rows="3" :cols="3" size="xs" decorative />
            </span>
            <span>{{ $before }}<strong x-text="typed()"></strong>{{ $after }}</span>
        </button>
        <fieldset class="nx-label-creator-colors">
            <legend class="nx-label-creator-colors-legend">{{ $colorLabel }}</legend>
            @foreach ($colors as $swatch)
                <label class="nx-label-color" style="--_c: {{ $swatch['color'] }}">
                    <input type="radio" class="nx-label-color-input" name="{{ $id }}-color" value="{{ $swatch['value'] }}" aria-label="{{ $swatch['label'] }}"
                        x-bind:checked="color() === @js($swatch['value'])" x-on:change="picked = @js($swatch['value'])">
                    <span class="nx-label-color-dot" aria-hidden="true"></span>
                </label>
            @endforeach
        </fieldset>
    </div>
</div>
