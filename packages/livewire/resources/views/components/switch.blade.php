@props(['label' => null, 'description' => null, 'size' => 'md'])
@if ($label || $description)
<label @class(['nx-choice', $attributes->get('class')])>
    <input type="checkbox" role="switch" {{ $attributes->except('class')->class('nx-switch')->merge(['data-size' => $size === 'md' ? null : $size]) }}>
    <span class="nx-choice-text">
        @if ($label)<span class="nx-choice-label">{{ $label }}</span>@endif
        @if ($description)<span class="nx-choice-description">{{ $description }}</span>@endif
    </span>
</label>
@else
<input type="checkbox" role="switch" {{ $attributes->class('nx-switch')->merge(['data-size' => $size === 'md' ? null : $size]) }}>
@endif
