@props(['label' => null, 'description' => null, 'indeterminate' => false])
@php $invalid = \NabuXUI\NabuXUI::error(\NabuXUI\NabuXUI::fieldName($attributes)); @endphp
@if ($label || $description)
<label @class(['nx-choice', $attributes->get('class')])>
    <input type="checkbox" {{ $attributes->except('class')->class('nx-checkbox')->merge(['aria-invalid' => $invalid ? 'true' : null]) }} @if ($indeterminate) x-data x-init="$el.indeterminate = true" @endif>
    <span class="nx-choice-text">
        @if ($label)<span class="nx-choice-label">{{ $label }}</span>@endif
        @if ($description)<span class="nx-choice-description">{{ $description }}</span>@endif
    </span>
</label>
@else
<input type="checkbox" {{ $attributes->class('nx-checkbox')->merge(['aria-invalid' => $invalid ? 'true' : null]) }} @if ($indeterminate) x-data x-init="$el.indeterminate = true" @endif>
@endif
