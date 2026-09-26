{{-- <x-nx::segmented :options="['month' => 'Monthly', 'year' => 'Yearly']" wire:model.live="billing" /> --}}
@props(['options' => [], 'value' => null, 'name' => null, 'size' => null, 'tone' => null, 'label' => null])
@php $name ??= \NabuXUI\NabuXUI::model($attributes) ?? \NabuXUI\NabuXUI::id('nx-seg'); @endphp
@php $value ??= array_key_first($options); @endphp
<div {{ $attributes->whereDoesntStartWith('wire:model')->class('nx-segmented')->merge(['role' => 'radiogroup', 'aria-label' => $label, 'data-size' => $size === 'sm' ? 'sm' : null, 'data-tone' => $tone === 'accent' ? 'accent' : null]) }} x-data="nxSegmented()">
    <span class="nx-indicator" aria-hidden="true"></span>
    @foreach ($options as $key => $option)
        @php $option = is_array($option) ? $option : ['label' => $option]; @endphp
        <label class="nx-segment">
            <input class="nx-segment-input" type="radio" name="{{ $name }}" value="{{ $key }}" {{ $attributes->whereStartsWith('wire:model') }} @checked((string) $key === (string) $value) @disabled($option['disabled'] ?? false)>
            @if (! empty($option['icon'])){{ \NabuXUI\NabuXUI::icon($option['icon']) }}@endif{{ $option['label'] }}
        </label>
    @endforeach
</div>
