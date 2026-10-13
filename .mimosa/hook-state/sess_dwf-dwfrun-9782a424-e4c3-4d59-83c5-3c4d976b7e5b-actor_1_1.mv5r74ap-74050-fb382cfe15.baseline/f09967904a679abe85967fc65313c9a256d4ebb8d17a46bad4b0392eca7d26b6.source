{{--
    <x-nx::inline-edit label="Project name" wire:model="name" action="rename" />
    <x-nx::inline-edit label="Title" value="Q3 roadmap" x-on:nx-change="…" />

    Text that becomes an input in place: click (or Enter) edits, Enter saves,
    Escape cancels, blur saves. With wire:model the saved value is entangled
    to the property; with `action` the save goes through that Livewire method
    first (return false or a message from PHP to show the error state). A
    listener can refuse a value in the browser:
    x-on:nx-validate="$event.detail.error = 'No spaces'". A successful save
    dispatches `nx-change`; `name` adds a hidden input for plain form posts.
--}}
@props([
    'value' => '',
    'label' => '',
    'placeholder' => null,
    'editLabel' => 'Edit {label}',
    'savingLabel' => 'Saving…',
    'errorLabel' => 'Could not save',
    'requiredLabel' => 'Required',
    'required' => false,
    'maxlength' => null,
    'size' => 'md',
    'action' => null,
    'blur' => 'save',
    'name' => null,
    'disabled' => false,
])
@php
    use NabuXUI\NabuXUI;
    $model = NabuXUI::model($attributes);
    $live = collect(array_keys($attributes->getAttributes()))->contains(fn ($key) => str_starts_with($key, 'wire:model') && str_contains($key, '.live'));
    $id = NabuXUI::id('nx-inline-edit');
    $options = [
        'action' => $action,
        'required' => (bool) $required,
        'blur' => $blur,
        'savingLabel' => $savingLabel,
        'errorLabel' => $errorLabel,
        'requiredLabel' => $requiredLabel,
    ];
    $editName = str_replace('{label}', $label, $editLabel);
    $shown = (string) ($value !== '' && $value !== null ? $value : ($placeholder ?? ''));
@endphp
<div {{ $attributes->whereDoesntStartWith('wire:model')->class('nx-inline-edit')->merge([
        'data-state' => 'idle',
        'data-size' => $size === 'md' ? null : $size,
        'data-disabled' => $disabled ? '' : null,
    ]) }}
    @if ($model) x-data="nxInlineEdit(@entangle($model){{ $live ? '.live' : '' }}, @js($options))" @else x-data="nxInlineEdit(@js((string) $value), @js($options))" @endif
    x-bind:data-state="state" wire:ignore>
    <button type="button" class="nx-inline-edit-display" x-ref="display" x-show="state === 'idle'" x-on:click="start()" @disabled($disabled)
        aria-label="{{ $editName }}: {{ $shown }}" x-bind:aria-label="@js($editName) + ': ' + (value || @js($placeholder ?? ''))">
        <span class="nx-inline-edit-text" @if ($value === '' || $value === null) data-placeholder @endif
            x-text="value || @js($placeholder ?? '')" x-bind:data-placeholder="value ? null : ''">{{ $shown }}</span>
        <span class="nx-inline-edit-pen" aria-hidden="true">{{ NabuXUI::icon('edit') }}</span>
    </button>
    <span class="nx-inline-edit-field" x-show="state !== 'idle'" x-cloak>
        <span class="nx-inline-edit-sizer" aria-hidden="true" x-text="draft || @js($placeholder ?? ' ')"></span>
        <input class="nx-inline-edit-input" id="{{ $id }}" x-ref="input" type="text" aria-label="{{ $label }}" placeholder="{{ $placeholder }}"
            @if ($maxlength) maxlength="{{ (int) $maxlength }}" @endif aria-describedby="{{ $id }}-status"
            x-model="draft" x-bind:readonly="state === 'saving'" x-bind:aria-invalid="state === 'error' ? 'true' : null"
            x-on:keydown="key($event)" x-on:blur="blurred()" x-on:input="typed()">
    </span>
    <span class="nx-inline-edit-status" id="{{ $id }}-status" aria-live="polite"><template x-if="state === 'saving'"><span class="nx-spinner" aria-hidden="true"></span></template><span x-text="state === 'idle' ? '' : message"></span></span>
    @if ($name)<input type="hidden" name="{{ $name }}" x-bind:value="value" value="{{ $value }}">@endif
</div>
