@props(['tone' => 'info', 'variant' => null, 'title' => null, 'icon' => null, 'dismissible' => false])
@php $icons = ['neutral' => 'bell', 'accent' => 'sparkles', 'success' => 'check-circle', 'warning' => 'alert-triangle', 'danger' => 'alert-circle', 'info' => 'info']; @endphp
<div {{ $attributes->class('nx-alert')->merge(['data-tone' => $tone, 'data-variant' => $variant && $variant !== 'soft' ? $variant : null]) }}
    @if ($dismissible) x-data="nxAlert()" x-show="! gone" @endif>
    @if ($icon !== false)<span class="nx-alert-icon">{{ \NabuXUI\NabuXUI::icon($icon ?: ($icons[$tone] ?? 'info')) }}</span>@else<span></span>@endif
    <div class="nx-alert-content">
        @if ($title)<p class="nx-alert-title">{{ $title }}</p>@endif
        @if (trim((string) $slot) !== '')<div class="nx-alert-description">{{ $slot }}</div>@endif
        @isset($actions)<div class="nx-alert-actions">{{ $actions }}</div>@endisset
    </div>
    @if ($dismissible)
        <button type="button" class="nx-alert-close" aria-label="{{ __('nabuxui::ui.dismiss') }}" @click="dismiss()">{{ \NabuXUI\NabuXUI::icon('x') }}</button>
    @endif
</div>
