{{--
    <x-nx::empty-state title="هنوز پروژه‌ای نیست" description="نخستین پروژهٔ خودتان را بسازید یا نمونه‌ها را ببینید."
        icon="folder" action="ساخت پروژه" action-href="/projects/new" secondary-action="مرور نمونه‌ها" />

    The friendly "nothing here yet" for panel pages: a floating plate with the
    page's icon, a slow dashed orbit with two sparks and a breathing halo —
    all pure CSS, both themes from the tokens, resting under
    prefers-reduced-motion. The title defaults to the package's "no results"
    word. Give one primary (`action` + `action-href`) and one quieter
    (`secondary-action` + `secondary-href`) action, or drop anything into the
    `actions` slot for a row of your own.
--}}
@props([
    'title' => null,
    'description' => null,
    'icon' => 'folder',
    'size' => null,
    'action' => null,
    'actionHref' => null,
    'actionIcon' => null,
    'secondaryAction' => null,
    'secondaryHref' => null,
    'secondaryIcon' => null,
])
@php
    use NabuXUI\NabuXUI;
@endphp
<div {{ $attributes->class('nx-empty-state')->merge([
    'data-nx-reveal' => '',
    'data-size' => in_array($size, ['sm', 'lg'], true) ? $size : null,
]) }} x-data x-nx-reveal>
    <span class="nx-empty-state-art" aria-hidden="true">
        <span class="nx-empty-state-halo"></span>
        <span class="nx-empty-state-orbit">
            <i class="nx-empty-state-spark"></i>
            <i class="nx-empty-state-spark"></i>
        </span>
        <span class="nx-empty-state-plate">{{ NabuXUI::icon($icon) }}</span>
    </span>
    <p class="nx-empty-state-title">{{ $title ?? __('nabuxui::ui.noResults') }}</p>
    @if ($description)<p class="nx-empty-state-description">{{ $description }}</p>@endif
    @if (isset($actions) || $action || $secondaryAction)
        <div class="nx-empty-state-actions">
            @if ($action)
                <x-nx::button variant="primary" :size="($size === 'sm' ? 'sm' : 'md')" :icon="$actionIcon" :href="$actionHref">{{ $action }}</x-nx::button>
            @endif
            @if ($secondaryAction)
                <x-nx::button variant="secondary" :size="($size === 'sm' ? 'sm' : 'md')" :icon="$secondaryIcon" :href="$secondaryHref">{{ $secondaryAction }}</x-nx::button>
            @endif
            @isset($actions){{ $actions }}@endisset
        </div>
    @endif
</div>
