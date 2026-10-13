{{-- <x-nx::radio-group name="plan" legend="Billing" :options="['monthly' => 'Monthly', 'yearly' => 'Yearly']" wire:model.live="plan" /> --}}
@props(['name' => null, 'legend' => null, 'options' => [], 'value' => null, 'orientation' => 'vertical'])
@php
    $list = array_is_list($options) ? $options : array_map(fn ($v, $l) => ['value' => $v, 'label' => $l], array_keys($options), $options);
    $name ??= \NabuXUI\NabuXUI::model($attributes) ?? \NabuXUI\NabuXUI::id('nx-radio');
    $wire = $attributes->whereStartsWith('wire:model');
@endphp
<fieldset {{ $attributes->whereDoesntStartWith('wire:model')->class('nx-field')->merge(['style' => 'border: 0; margin: 0; padding: 0']) }}>
    @if ($legend)<legend class="nx-label">{{ $legend }}</legend>@endif
    <div style="display: flex; flex-direction: {{ $orientation === 'vertical' ? 'column' : 'row' }}; flex-wrap: wrap; gap: var(--nx-space-3) var(--nx-space-6)">
        @foreach ($list as $option)
            <label class="nx-choice">
                <input type="radio" class="nx-radio" name="{{ $name }}" value="{{ $option['value'] }}" {{ $wire }} @checked((string) $value === (string) $option['value']) @disabled($option['disabled'] ?? false)>
                <span class="nx-choice-text">
                    <span class="nx-choice-label">{{ $option['label'] }}</span>
                    @if (! empty($option['description']))<span class="nx-choice-description">{{ $option['description'] }}</span>@endif
                </span>
            </label>
        @endforeach
    </div>
</fieldset>
