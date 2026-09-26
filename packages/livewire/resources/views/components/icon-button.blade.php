@props(['icon', 'label', 'variant' => 'ghost', 'size' => 'md'])
<x-nx::button :variant="$variant" :size="$size" :icon="$icon" icon-only :aria-label="$label" :title="$label" {{ $attributes }}>{{ $label }}</x-nx::button>
