{{-- options: ['value' => 'Label'] or a list of ['value' => …, 'label' => …]. --}}
@props(['label' => null, 'hint' => null, 'error' => null, 'options' => [], 'placeholder' => null, 'size' => 'md', 'required' => false])
@php
    use NabuXUI\NabuXUI;
    $id = $attributes->get('id') ?? NabuXUI::id('nx-select');
    $error ??= NabuXUI::error(NabuXUI::fieldName($attributes));
    $list = array_is_list($options) ? $options : array_map(fn ($v, $l) => ['value' => $v, 'label' => $l], array_keys($options), $options);
@endphp
<x-nx::field :label="$label" :hint="$hint" :error="$error" :for="$id" :required="$required" :class="$attributes->get('class')">
    <select {{ $attributes->except(['class', 'id'])->class('nx-select')->merge([
        'id' => $id,
        'data-size' => $size === 'md' ? null : $size,
        'aria-describedby' => trim(implode(' ', array_filter([$hint ? "{$id}-hint" : null, $error ? "{$id}-error" : null]))) ?: null,
        'aria-invalid' => $error ? 'true' : null,
        'required' => $required ?: null,
    ]) }}>
        @if ($placeholder)<option value="" disabled @selected(! $attributes->has('value'))>{{ $placeholder }}</option>@endif
        @foreach ($list as $option)
            <option value="{{ $option['value'] }}" @disabled($option['disabled'] ?? false)>{{ $option['label'] }}</option>
        @endforeach
        {{ $slot }}
    </select>
</x-nx::field>
