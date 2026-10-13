{{--
    <x-nx::card spotlight icon="sparkles" title="NabuGate" description="…" href="/gate" />
    Slots: default (body), footer. `tilt` leans toward the pointer; `interactive` lifts on hover.
--}}
@props(['variant' => null, 'size' => null, 'interactive' => false, 'spotlight' => false, 'tilt' => false, 'href' => null, 'icon' => null, 'title' => null, 'description' => null, 'titleAs' => 'h3'])
@php
    $tag = $href ? 'a' : 'article';
    $data = [
        'data-variant' => $variant && $variant !== 'default' ? $variant : null,
        'data-size' => $size && $size !== 'md' ? $size : null,
        'data-interactive' => $interactive ? '' : null,
        'data-spotlight' => $spotlight ? '' : null,
        'href' => $href,
    ];
@endphp
<{{ $tag }} {{ $attributes->class('nx-card')->merge($data) }} @if ($spotlight || $tilt) x-data @endif @if ($spotlight) x-nx-spotlight @endif @if ($tilt) x-nx-tilt @endif>
    @if ($icon || $title || $description)
        <div class="nx-card-header">
            @if ($icon)<span class="nx-card-icon">@if (\NabuXUI\NabuXUI::hasIcon($icon)){{ \NabuXUI\NabuXUI::icon($icon) }}@else{{ $icon }}@endif</span>@endif
            @if ($title)<{{ $titleAs }} class="nx-card-title">{{ $title }}</{{ $titleAs }}>@endif
            @if ($description)<p class="nx-card-description">{{ $description }}</p>@endif
        </div>
    @endif
    @if (trim((string) $slot) !== '')<div class="nx-card-body">{{ $slot }}</div>@endif
    @isset($footer)<div class="nx-card-footer">{{ $footer }}</div>@endisset
</{{ $tag }}>
