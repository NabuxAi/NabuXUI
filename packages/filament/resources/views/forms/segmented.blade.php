<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <x-nx::segmented wire:model="{{ $getStatePath() }}" :options="$getOptions()" :label="$field->getLabel()" {{ $attributes }} />
</x-dynamic-component>
