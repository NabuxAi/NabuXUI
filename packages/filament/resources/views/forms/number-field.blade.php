<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <x-nx::number-field wire:model="{{ $getStatePath() }}" min="{{ $getMinValue() }}" max="{{ $getMaxValue() }}" step="{{ $getStep() }}" :label="$field->getLabel()" {{ $attributes }} />
</x-dynamic-component>
