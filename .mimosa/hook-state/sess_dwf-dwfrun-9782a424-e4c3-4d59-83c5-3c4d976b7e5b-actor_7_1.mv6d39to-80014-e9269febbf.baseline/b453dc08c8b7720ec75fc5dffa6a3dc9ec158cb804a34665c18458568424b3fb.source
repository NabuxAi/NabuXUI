{{-- Native radios painted as stars; the preview on hover is pure CSS. wire:model binds the value. --}}
@props(['name' => null, 'value' => 0, 'max' => 5, 'label' => null, 'size' => null])
@php $name ??= \NabuXUI\NabuXUI::model($attributes) ?? \NabuXUI\NabuXUI::id('nx-rating'); @endphp
<fieldset {{ $attributes->whereDoesntStartWith('wire:model')->class('nx-rating')->merge(['style' => $size ? "--nx-rating-size: {$size}" : null]) }}>
    <legend class="nx-visually-hidden">{{ $label ?? __('nabuxui::ui.rating') }}</legend>
    @for ($star = 1; $star <= $max; $star++)
        <label class="nx-rating-star">
            <input class="nx-rating-input" type="radio" name="{{ $name }}" value="{{ $star }}" {{ $attributes->whereStartsWith('wire:model') }} @checked((int) $value === $star) aria-label="{{ __('nabuxui::ui.stars', ['count' => $star, 'total' => $max]) }}">
            {{ \NabuXUI\NabuXUI::icon('star') }}
        </label>
    @endfor
</fieldset>
