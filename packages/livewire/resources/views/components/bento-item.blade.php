{{-- Slots: media (artwork on top) and the default slot (extra content). --}}
@props(['colSpan' => 1, 'rowSpan' => 1, 'title' => null, 'description' => null, 'href' => null, 'spotlight' => true])
@php $tag = $href ? 'a' : 'article'; @endphp
<{{ $tag }} {{ $attributes->class(['nx-bento-item', 'nx-spotlight' => $spotlight])->merge(['href' => $href, 'style' => "--nx-col-span: {$colSpan}; --nx-row-span: {$rowSpan}"]) }} @if ($spotlight) x-data x-nx-spotlight @endif>
    @isset($media)<div class="nx-bento-media">{{ $media }}</div>@endisset
    @if ($title)<h3 class="nx-bento-title">{{ $title }}</h3>@endif
    @if ($description)<p class="nx-bento-description">{{ $description }}</p>@endif
    {{ $slot }}
</{{ $tag }}>
