<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <x-nx::slider wire:model="{{ $getStatePath() }}" min="{{ $getMinValue() }}" max="{{ $getMaxValue() }}" step="{{ $getStep() }}" show-value :label="$field->getLabel()" {{ $attributes }} />
</x-dynamic-component>
