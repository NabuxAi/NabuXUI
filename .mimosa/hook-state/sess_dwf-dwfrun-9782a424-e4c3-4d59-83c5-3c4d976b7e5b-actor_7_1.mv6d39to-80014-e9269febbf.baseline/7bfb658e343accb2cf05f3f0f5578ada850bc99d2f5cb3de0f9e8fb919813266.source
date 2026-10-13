{{--
    Project screens orbiting a centre and always facing you. Hovering pauses; focusing or clicking a screen
    brings it to the front. With reduced motion the ring holds still.

    <x-nx::orbit-showcase :items="[
        ['title' => 'Atlas', 'subtitle' => 'Maps for field teams', 'cover' => 'linear-gradient(…)', 'href' => '/work/atlas'],
        …
    ]">
        <x-slot:center>…replaces the glowing orb…</x-slot:center>
    </x-nx::orbit-showcase>

    Item keys: title, subtitle, cover (a CSS background), image, alt, href, tone. `period`: seconds per turn.
--}}
@props(['items' => [], 'period' => 40, 'controls' => true, 'height' => null, 'radius' => null])
@php
    $items = array_values($items);
    $count = max(count($items), 1);
    $named = ['lapis', 'violet', 'cyan', 'gold'];
    $titles = array_map(fn ($item) => (string) ($item['title'] ?? ''), $items);
    $subtitles = array_map(fn ($item) => (string) ($item['subtitle'] ?? ''), $items);
    $vars = "--nx-count: {$count};"
        .($height ? " --nx-orbit-height: {$height};" : '')
        .($radius ? " --nx-orbit-radius: {$radius};" : '');
@endphp
<section {{ $attributes->class('nx-orbit')->merge(['style' => $vars]) }} x-data="nxOrbitShowcase({{ $count }}, {{ (float) $period }})" wire:ignore.self>
    <div class="nx-orbit-stage">
        <div class="nx-orbit-scene">
            <span class="nx-orbit-path" aria-hidden="true"></span>
            <div class="nx-orbit-center">@isset($center){{ $center }}@else<span class="nx-orbit-orb" aria-hidden="true"></span>@endisset</div>
            @foreach ($items as $i => $item)
                @php
                    $tone = $item['tone'] ?? null;
                    $toneAttr = in_array($tone, $named, true) ? $tone : null;
                    $toneStyle = $tone && ! $toneAttr ? "--nx-tone: {$tone}" : null;
                    $title = (string) ($item['title'] ?? '');
                    $href = $item['href'] ?? null;
                    $screenTag = $href ? 'a' : 'button';
                @endphp
                <div class="nx-orbit-item" style="--i: {{ $i }}" @if ($i === 0) data-front @endif x-bind:data-front="front === {{ $i }} ? '' : null">
                    <{{ $screenTag }} class="nx-orbit-screen" @if ($href) href="{{ $href }}" draggable="false" @else type="button" @endif @if ($toneAttr) data-tone="{{ $toneAttr }}" @endif @if ($toneStyle) style="{{ $toneStyle }}" @endif>
                        <span class="nx-orbit-bar" aria-hidden="true"><i></i><i></i><i></i><span>{{ $title }}</span></span>
                        <span class="nx-orbit-cover" @if (! empty($item['cover'])) style="background: {{ $item['cover'] }}" @endif>@if (! empty($item['image']))<img src="{{ $item['image'] }}" alt="{{ $item['alt'] ?? '' }}" draggable="false" loading="lazy">@endif</span>
                        <span class="nx-visually-hidden">{{ $title }}@if (! empty($item['subtitle'])) — {{ $item['subtitle'] }}@endif</span>
                    </{{ $screenTag }}>
                </div>
            @endforeach
        </div>
        @if ($controls && count($items) > 1)
            <button type="button" class="nx-button nx-orbit-toggle" data-variant="secondary" data-size="sm" data-shape="pill" data-icon-only
                aria-pressed="false" aria-label="{{ __('nabuxui::ui.pause') }}"
                x-bind:aria-pressed="paused ? 'true' : 'false'" x-bind:aria-label="paused ? @js(__('nabuxui::ui.play')) : @js(__('nabuxui::ui.pause'))" x-on:click="toggle()">
                <span class="nx-button-label"><span x-show="! paused">{{ \NabuXUI\NabuXUI::icon('pause') }}</span><span x-show="paused" x-cloak>{{ \NabuXUI\NabuXUI::icon('play') }}</span></span>
            </button>
        @endif
    </div>
    <div class="nx-orbit-caption" aria-hidden="true">
        <strong x-text="@js($titles)[front] ?? ''">{{ $titles[0] ?? '' }}</strong>
        <span x-text="@js($subtitles)[front] ?? ''">{{ $subtitles[0] ?? '' }}</span>
    </div>
</section>
