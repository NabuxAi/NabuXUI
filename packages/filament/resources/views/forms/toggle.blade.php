<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <x-nx::switch wire:model="{{ $getStatePath() }}" :label="$field->getLabel()" {{ $attributes }} />
</x-dynamic-component>
