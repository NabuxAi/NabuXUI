<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <x-nx::select wire:model="{{ $getStatePath() }}" :options="$getOptions()" :placeholder="$getPlaceholder()" :label="$field->getLabel()" {{ $attributes }} />
</x-dynamic-component>
