<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <x-nx::checkbox wire:model="{{ $getStatePath() }}" :label="$field->getLabel()" {{ $attributes }} />
</x-dynamic-component>
