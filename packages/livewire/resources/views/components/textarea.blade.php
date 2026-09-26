@props(['label' => null, 'hint' => null, 'error' => null, 'maxRows' => null, 'required' => false])
@php
    use NabuXUI\NabuXUI;
    $id = $attributes->get('id') ?? NabuXUI::id('nx-textarea');
    $error ??= NabuXUI::error(NabuXUI::fieldName($attributes));
    $hintId = $hint ? "{$id}-hint" : null;
    $errorId = $error ? "{$id}-error" : null;
@endphp
<x-nx::field :label="$label" :hint="$hint" :error="$error" :for="$id" :required="$required" :class="$attributes->get('class')">
    <textarea {{ $attributes->except(['class', 'id'])->class('nx-textarea')->merge([
        'id' => $id,
        'aria-describedby' => trim(implode(' ', array_filter([$hintId, $errorId]))) ?: null,
        'aria-invalid' => $error ? 'true' : null,
        'required' => $required ?: null,
        'style' => $maxRows ? "--nx-textarea-max: {$maxRows}lh" : null,
    ]) }} x-data x-nx-autogrow>{{ $slot }}</textarea>
</x-nx::field>
