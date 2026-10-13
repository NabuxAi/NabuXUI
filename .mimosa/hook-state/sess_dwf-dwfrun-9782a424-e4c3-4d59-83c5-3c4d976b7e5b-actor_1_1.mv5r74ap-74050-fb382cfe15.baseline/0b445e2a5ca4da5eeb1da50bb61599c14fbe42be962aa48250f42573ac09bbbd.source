@props(['removable' => false])
<span {{ $attributes->whereDoesntStartWith('wire:click')->class('nx-chip') }}>
    <span>{{ $slot }}</span>
    @if ($removable)
        <button type="button" class="nx-chip-remove" aria-label="{{ __('nabuxui::ui.remove', ['name' => trim(strip_tags((string) $slot))]) }}" {{ $attributes->whereStartsWith('wire:click') }}>{{ \NabuXUI\NabuXUI::icon('x') }}</button>
    @endif
</span>
