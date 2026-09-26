{{-- One real input under decorative slots: paste, autofill and wire:model work as usual. Fires nx-complete. --}}
@props(['length' => 6, 'separatorAfter' => null, 'alphanumeric' => false, 'label' => null])
@php $invalid = \NabuXUI\NabuXUI::error(\NabuXUI\NabuXUI::fieldName($attributes)); @endphp
<div class="nx-otp" x-data="nxOtp({{ (int) $length }}, @js((bool) $alphanumeric))" @if ($invalid) data-invalid @endif>
    <input x-ref="input" {{ $attributes->class('nx-otp-input')->merge([
        'inputmode' => $alphanumeric ? 'text' : 'numeric',
        'autocomplete' => 'one-time-code',
        'maxlength' => $length,
        'spellcheck' => 'false',
        'aria-label' => $label ?? __('nabuxui::ui.code'),
        'aria-invalid' => $invalid ? 'true' : null,
    ]) }} @input="read()" @focus="focused = true" @blur="focused = false">
    <div class="nx-otp-slots" aria-hidden="true">
        @for ($i = 0; $i < $length; $i++)
            <span class="nx-otp-slot" x-bind:data-filled="char({{ $i }}) ? '' : null" x-bind:data-active="active({{ $i }}) ? '' : null"><span x-text="char({{ $i }})"></span></span>
            @if ($separatorAfter && $i === $separatorAfter - 1 && $i < $length - 1)<span class="nx-otp-sep"></span>@endif
        @endfor
    </div>
</div>
