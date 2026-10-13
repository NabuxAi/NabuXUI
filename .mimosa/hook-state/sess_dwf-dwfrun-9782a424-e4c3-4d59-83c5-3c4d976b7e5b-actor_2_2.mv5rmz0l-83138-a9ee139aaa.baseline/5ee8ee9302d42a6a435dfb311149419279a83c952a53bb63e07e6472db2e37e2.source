{{--
    <x-nx::interests-picker label="Pick a few" wire:model.live="interests" :value="$interests" :options="[
        ['value' => 'design', 'label' => 'Design · Diseño', 'emoji' => '🎨'],
        ['value' => 'food', 'label' => 'Food · 料理', 'emoji' => '🍜'],
    ]" />

    Rows of emoji chips that drag sideways (touch scrolls natively, with snap).
    Every chip is a native checkbox carrying the wire:model, so Livewire binds the
    picks as an array (initialise the property as an array). Picking throws the
    chip's emoji out; Clear shakes the picks and bursts them one after another.
    Options may also be ['value' => 'Label'].
--}}
@props([
    'options' => [],
    'value' => [],
    'name' => null,
    'label' => null,
    'rows' => 3,
    'clearLabel' => 'Clear',
])
@php
    $model = \NabuXUI\NabuXUI::model($attributes);
    $wire = $attributes->whereStartsWith('wire:model');
    $name ??= $model ? null : 'interests[]';
    $selected = array_map('strval', (array) $value);
    $items = [];
    foreach ($options as $key => $option) {
        $items[] = is_array($option)
            ? ['value' => (string) ($option['value'] ?? $key), 'label' => $option['label'] ?? '', 'emoji' => $option['emoji'] ?? '', 'disabled' => $option['disabled'] ?? false]
            : ['value' => (string) $key, 'label' => $option, 'emoji' => '', 'disabled' => false];
    }
    $perRow = max(1, (int) ceil(count($items) / max(1, (int) $rows)));
    $lines = $items ? array_chunk($items, $perRow) : [];
    $count = count(array_filter($items, fn ($item) => in_array($item['value'], $selected, true)));
@endphp
<fieldset {{ $attributes->whereDoesntStartWith('wire:model')->class('nx-interests') }} x-data="nxInterests" x-on:change="changed($event)">
    @if ($label)<legend class="nx-interests-legend">{{ $label }}</legend>@endif
    <div class="nx-interests-rows">
        @foreach ($lines as $line)
            <div class="nx-interests-row" wire:ignore.self>
                <div class="nx-interests-track" wire:ignore.self>
                    @foreach ($line as $item)
                        <label class="nx-interest" data-emoji="{{ $item['emoji'] }}" wire:ignore.self>
                            <input type="checkbox" class="nx-interest-input" value="{{ $item['value'] }}" @if ($name) name="{{ $name }}" @endif {{ $wire }} @checked(in_array($item['value'], $selected, true)) @disabled($item['disabled'])>
                            @if ($item['emoji'] !== '')<span class="nx-interest-emoji" aria-hidden="true">{{ $item['emoji'] }}</span>@endif
                            <span class="nx-interest-label">{{ $item['label'] }}</span>
                            <span class="nx-interest-mark" aria-hidden="true">{{ \NabuXUI\NabuXUI::icon('plus', 'nx-interest-off') }}{{ \NabuXUI\NabuXUI::icon('check', 'nx-interest-on') }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
    <div class="nx-interests-footer">
        <x-nx::button size="sm" variant="ghost" icon="x" x-on:click="clear()" x-bind:aria-disabled="count === 0 ? 'true' : null" :aria-disabled="$count === 0 ? 'true' : null">
            {{ $clearLabel }}<span class="nx-badge" data-tone="accent" x-show="count > 0" x-text="count" @if ($count === 0) style="display: none" @endif>{{ $count }}</span>
        </x-nx::button>
    </div>
</fieldset>
