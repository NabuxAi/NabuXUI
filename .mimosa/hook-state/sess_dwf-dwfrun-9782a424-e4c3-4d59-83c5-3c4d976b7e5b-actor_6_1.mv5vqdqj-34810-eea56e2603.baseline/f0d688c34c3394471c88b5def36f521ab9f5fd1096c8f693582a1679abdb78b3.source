{{--
    <x-nx::hero backdrop="grid" eyebrow="NabuXUI 0.1" eyebrow-badge="New" eyebrow-href="/changelog" title="One motion language" subtitle="…">
        <x-slot:actions> <x-nx::button variant="primary">Start</x-nx::button> </x-slot:actions>
        <x-slot:media> <img …> </x-slot:media>
    </x-nx::hero>
    A plain-string title reveals word by word; pass a `heading` slot for your own markup.
--}}
@props(['backdrop' => 'aurora', 'eyebrow' => null, 'eyebrowBadge' => null, 'eyebrowHref' => null, 'title' => null, 'subtitle' => null, 'align' => 'center'])
<section {{ $attributes->class('nx-hero')->merge(['data-align' => $align === 'start' ? 'start' : null]) }} @if ($backdrop === 'grid') x-data x-nx-spotlight @endif>
    @if ($backdrop)<x-nx::backdrop :variant="$backdrop" />@endif
    <div class="nx-hero-content" data-nx-reveal="group" x-data x-nx-reveal.group>
        @if ($eyebrow)
            @php $eyebrowTag = $eyebrowHref ? 'a' : 'p'; @endphp
            <{{ $eyebrowTag }} class="nx-hero-eyebrow" @if ($eyebrowHref) href="{{ $eyebrowHref }}" @endif>
                @if ($eyebrowBadge)<span class="nx-badge" data-tone="accent" data-variant="solid">{{ $eyebrowBadge }}</span>@endif
                <span>{{ $eyebrow }}</span>
                @if ($eyebrowHref){{ \NabuXUI\NabuXUI::icon('arrow-right') }}@endif
            </{{ $eyebrowTag }}>
        @endif
        @isset($heading)
            <h1 class="nx-hero-title">{{ $heading }}</h1>
        @elseif ($title)
            <x-nx::text-reveal as="h1" class="nx-hero-title" delay="120">{{ $title }}</x-nx::text-reveal>
        @endisset
        @if ($subtitle)<p class="nx-hero-subtitle">{{ $subtitle }}</p>@endif
        @isset($actions)<div class="nx-hero-actions">{{ $actions }}</div>@endisset
        {{ $slot }}
    </div>
    @isset($media)
        <div class="nx-hero-media" data-nx-reveal x-data x-nx-reveal><div>{{ $media }}</div></div>
    @endisset
</section>
