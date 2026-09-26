{{--
    Label, hint and error around a custom control. The built-in controls render
    this themselves when given `label`/`hint`/`error`; use it for your own
    control and point `for` at its id.
--}}
@props(['label' => null, 'hint' => null, 'error' => null, 'for' => null, 'required' => false])
@php $errorId = $error && $for ? "{$for}-error" : null; @endphp
<div {{ $attributes->class('nx-field')->merge(['data-invalid' => $error ? '' : null]) }}>
    @if ($label)
        <label class="nx-label" @if ($for) for="{{ $for }}" @endif>{{ $label }}@if ($required)<span class="nx-label-required" aria-hidden="true">*</span>@endif</label>
    @endif
    @if ($hint)<p class="nx-hint" @if ($for) id="{{ $for }}-hint" @endif>{{ $hint }}</p>@endif
    {{ $slot }}
    @if ($error)<p class="nx-error" @if ($errorId) id="{{ $errorId }}" @endif>{{ \NabuXUI\NabuXUI::icon('alert-circle') }}{{ $error }}</p>@endif
</div>
