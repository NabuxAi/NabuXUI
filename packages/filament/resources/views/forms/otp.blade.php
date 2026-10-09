<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <x-nx::otp wire:model="{{ $getStatePath() }}" length="{{ $getLength() }}" :alphanumeric="$isAlphanumeric()" :label="$field->getLabel()" {{ $attributes }} />
</x-dynamic-component>
