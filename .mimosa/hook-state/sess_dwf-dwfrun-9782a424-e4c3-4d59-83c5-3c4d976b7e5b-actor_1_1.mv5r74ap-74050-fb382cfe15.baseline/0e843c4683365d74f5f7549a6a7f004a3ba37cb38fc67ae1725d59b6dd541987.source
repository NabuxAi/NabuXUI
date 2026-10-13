{{--
    A segmented control whose selection is a lens you can drag.
    <x-nx::glass-segmented label="Period" :options="['day' => 'Day', 'week' => 'Week']" wire:model.live="period" />
    Options may also be ['key' => ['label' => …, 'icon' => …, 'disabled' => true]].
--}}
@props(['options' => [], 'value' => null, 'name' => null, 'preset' => 'regular', 'label' => null])
@php
    $model = \NabuXUI\NabuXUI::model($attributes);
    $live = collect(array_keys($attributes->getAttributes()))->contains(fn ($key) => str_starts_with($key, 'wire:model') && str_contains($key, '.live'));
    $name ??= \NabuXUI\NabuXUI::id('nx-gseg');
    $value ??= array_key_first($options);
@endphp
<div {{ $attributes->whereDoesntStartWith('wire:model')->class('nx-glass nx-glass-seg')->merge([
        'role' => 'radiogroup',
        'aria-label' => $label,
        'data-preset' => $preset === 'regular' ? null : $preset,
    ]) }}
    @if ($model) x-data="nxGlassSegmented(@entangle($model){{ $live ? '.live' : '' }})" @else x-data="nxGlassSegmented(@js((string) $value))" @endif x-nx-glass>
    <div class="nx-glass-seg-track">
        @foreach ($options as $key => $option)
            @php $option = is_array($option) ? $option : ['label' => $option]; @endphp
            <label class="nx-glass-seg-option">
                <input type="radio" name="{{ $name }}" value="{{ $key }}" @checked((string) $key === (string) $value) @disabled($option['disabled'] ?? false)
                    x-bind:checked="value === @js((string) $key)" x-on:change="value = $event.target.value">
                @if (! empty($option['icon'])){{ \NabuXUI\NabuXUI::icon($option['icon']) }}@endif
                <span>{{ $option['label'] }}</span>
            </label>
        @endforeach
    </div>
    <span class="nx-indicator nx-lens nx-glass-seg-lens" aria-hidden="true"></span>
</div>
