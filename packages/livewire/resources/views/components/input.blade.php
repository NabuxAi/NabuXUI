{{--
    <x-nx::input label="Email" type="email" icon="mail" wire:model.blur="email" />

    The validation error comes from $errors by the field's name (or its wire:model).
    Everything except class/style lands on the <input> itself.
--}}
@props(['label' => null, 'hint' => null, 'error' => null, 'size' => 'md', 'icon' => null, 'iconEnd' => null, 'prefix' => null, 'suffix' => null, 'required' => false])
@php
    use NabuXUI\NabuXUI;
    $id = $attributes->get('id') ?? NabuXUI::id('nx-input');
    $error ??= NabuXUI::error(NabuXUI::fieldName($attributes));
    $hintId = $hint ? "{$id}-hint" : null;
    $errorId = $error ? "{$id}-error" : null;
    $described = trim(implode(' ', array_filter([$attributes->get('aria-describedby'), $hintId, $errorId]))) ?: null;
    $start = $icon ? NabuXUI::icon($icon) : $prefix;
    $end = $iconEnd ? NabuXUI::icon($iconEnd) : $suffix;
    $wrapped = $label || $hint || $error;
    $control = $attributes->except(['class', 'style', 'id', 'aria-describedby'])->merge([
        'id' => $id,
        'aria-describedby' => $described,
        'aria-invalid' => $error ? 'true' : null,
        'required' => $required ?: null,
    ]);
    $sizeAttr = $size === 'md' ? null : $size;
@endphp
@if ($wrapped)
<div {{ $attributes->only(['class', 'style'])->class('nx-field')->merge(['data-invalid' => $error ? '' : null]) }}>
    @if ($label)<label class="nx-label" for="{{ $id }}">{{ $label }}@if ($required)<span class="nx-label-required" aria-hidden="true">*</span>@endif</label>@endif
    @if ($hint)<p class="nx-hint" id="{{ $hintId }}">{{ $hint }}</p>@endif
@endif
@if ($start || $end)
    <div @class(['nx-input-group', $wrapped ? null : $attributes->get('class')]) @if ($sizeAttr) data-size="{{ $sizeAttr }}" @endif>
        @if ($start)<span class="nx-input-addon">{{ $start }}</span>@endif
        <input {{ $control->class('nx-input') }}>
        @if ($end)<span class="nx-input-addon">{{ $end }}</span>@endif
    </div>
@else
    <input {{ $control->class(['nx-input', $wrapped ? null : $attributes->get('class')])->merge(['data-size' => $sizeAttr, 'style' => $wrapped ? null : $attributes->get('style')]) }}>
@endif
@if ($wrapped)
    @if ($error)<p class="nx-error" id="{{ $errorId }}">{{ NabuXUI::icon('alert-circle') }}{{ $error }}</p>@endif
</div>
@endif
