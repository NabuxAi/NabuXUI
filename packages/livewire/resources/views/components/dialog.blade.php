{{--
    Bound to a Livewire property:  <x-nx::dialog wire:model="showInvite" title="Invite">…</x-nx::dialog>
    Or by name from anywhere:      <x-nx::dialog name="invite">…</x-nx::dialog>  +  $dispatch('nx-open', 'invite')
    variant: modal (default) | drawer | sheet · side (drawer): end | start · slot: footer
--}}
@props(['name' => null, 'title' => null, 'description' => null, 'size' => null, 'variant' => 'modal', 'side' => null, 'closeOnBackdrop' => true, 'hideClose' => false, 'open' => false])
@php
    $id = \NabuXUI\NabuXUI::id('nx-dialog');
    $model = \NabuXUI\NabuXUI::model($attributes);
@endphp
<div style="display: contents"
    @if ($model) x-data="nxDialog(@entangle($model))" @else x-data="nxDialog(@js((bool) $open), @js($name))" @endif
    @nx-open.window="openNamed($event.detail)" @nx-close.window="closeNamed($event.detail)">
    <dialog x-ref="dialog" {{ $attributes->whereDoesntStartWith('wire:model')->class('nx-dialog')->merge([
        'data-size' => $size && $size !== 'md' ? $size : null,
        'data-variant' => $variant === 'modal' ? null : $variant,
        'data-side' => $side === 'start' ? 'start' : null,
        'aria-labelledby' => $title ? "{$id}-title" : null,
        'aria-describedby' => $description ? "{$id}-description" : null,
    ]) }} @if ($closeOnBackdrop) @click="backdrop($event)" @endif>
        @if ($title || $description)
            <header class="nx-dialog-header">
                @if ($title)<h2 class="nx-dialog-title" id="{{ $id }}-title">{{ $title }}</h2>@endif
                @if ($description)<p class="nx-dialog-description" id="{{ $id }}-description">{{ $description }}</p>@endif
            </header>
        @endif
        <div class="nx-dialog-body">{{ $slot }}</div>
        @isset($footer)<footer class="nx-dialog-footer">{{ $footer }}</footer>@endisset
        @unless ($hideClose)
            <button type="button" class="nx-dialog-close" aria-label="{{ __('nabuxui::ui.close') }}" @click="open = false">{{ \NabuXUI\NabuXUI::icon('x') }}</button>
        @endunless
    </dialog>
</div>
