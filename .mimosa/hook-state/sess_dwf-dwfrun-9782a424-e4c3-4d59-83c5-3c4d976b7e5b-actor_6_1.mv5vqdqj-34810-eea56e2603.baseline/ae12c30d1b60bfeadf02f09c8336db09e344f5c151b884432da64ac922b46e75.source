{{--
    Cards around a 3D ring: drag it (with inertia; it snaps to the nearest card), scroll sideways, or use the
    arrow keys and the buttons. The front card is lit and its title morphs into the next through a goo filter.

    <x-nx::ring-carousel label="Projects" :items="[
        ['title' => 'Aurora', 'subtitle' => 'Brand system', 'cover' => 'linear-gradient(…)', 'href' => '/work/aurora'],
        …
    ]" />

    Item keys: title, subtitle, cover (a CSS background), image, alt, href, tone. The front card's index can follow
    a Livewire property: wire:model="slide".
--}}
@props(['items' => [], 'index' => 0, 'label' => null, 'cardWidth' => null, 'cardHeight' => null])
@php
    $items = array_values($items);
    $count = max(count($items), 1);
    $model = \NabuXUI\NabuXUI::model($attributes);
    $start = (((int) $index % $count) + $count) % $count;
    $goo = \NabuXUI\NabuXUI::id('nx-goo');
    $named = ['lapis', 'violet', 'cyan', 'gold'];
    $locale = str_replace('_', '-', app()->getLocale());
    $num = fn ($n) => \NabuXUI\NabuXUI::formatNumber($n);
    $titles = array_map(fn ($item) => (string) ($item['title'] ?? ''), $items);
    $subtitles = array_map(fn ($item) => (string) ($item['subtitle'] ?? ''), $items);
    $first = $items[$start] ?? [];
    $vars = "--nx-count: {$count};"
        .($cardWidth ? " --nx-ring-card-width: {$cardWidth};" : '')
        .($cardHeight ? " --nx-ring-card-height: {$cardHeight};" : '');
@endphp
<section {{ $attributes->whereDoesntStartWith('wire:model')->class('nx-ring')->merge(['style' => $vars, 'aria-roledescription' => 'carousel', 'aria-label' => $label]) }}
    @if ($model) x-data="nxRingCarousel(@entangle($model), {{ $count }}, @js($titles), @js($locale))" @else x-data="nxRingCarousel({{ $start }}, {{ $count }}, @js($titles), @js($locale))" @endif
    wire:ignore.self>
    <div class="nx-ring-stage">
        <div class="nx-ring-track">
            @foreach ($items as $i => $item)
                @php
                    $tone = $item['tone'] ?? null;
                    $toneAttr = in_array($tone, $named, true) ? $tone : null;
                    $toneStyle = $tone && ! $toneAttr ? "--nx-tone: {$tone}" : null;
                    $active = $i === $start;
                    $href = $item['href'] ?? null;
                    $faceTag = $href ? 'a' : 'div';
                @endphp
                <div class="nx-ring-card" style="--i: {{ $i }}" role="group" aria-roledescription="slide" aria-label="{{ $num($i + 1) }} / {{ $num($count) }}"
                    @if ($active) data-active @else aria-hidden="true" @endif
                    x-bind:data-active="current() === {{ $i }} ? '' : null" x-bind:aria-hidden="current() === {{ $i }} ? null : 'true'" x-on:click="pick({{ $i }})">
                    <{{ $faceTag }} class="nx-ring-face" @if ($toneAttr) data-tone="{{ $toneAttr }}" @endif @if ($toneStyle) style="{{ $toneStyle }}" @endif
                        @if ($href) href="{{ $href }}" draggable="false" @if (! $active) tabindex="-1" @endif x-bind:tabindex="current() === {{ $i }} ? null : '-1'" @endif>
                        <div class="nx-ring-cover" @if (! empty($item['cover'])) style="background: {{ $item['cover'] }}" @endif>@if (! empty($item['image']))<img src="{{ $item['image'] }}" alt="{{ $item['alt'] ?? '' }}" draggable="false" loading="lazy">@endif</div>
                        <span class="nx-ring-caption"><span>{{ $item['title'] ?? '' }}</span>@if (! empty($item['subtitle']))<small>{{ $item['subtitle'] }}</small>@endif</span>
                    </{{ $faceTag }}>
                </div>
            @endforeach
        </div>
    </div>

    <div class="nx-ring-label" style="--_goo: url(#{{ $goo }})" aria-hidden="true" x-bind:data-morphing="morphing ? '' : null">
        <svg class="nx-ring-goo" focusable="false" aria-hidden="true"><filter id="{{ $goo }}" color-interpolation-filters="sRGB"><feColorMatrix in="SourceGraphic" type="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 20 -8" /></filter></svg>
        <div class="nx-ring-titles">
            <span class="nx-ring-title" data-state="in" dir="{{ \NabuXUI\NabuXUI::direction($first['title'] ?? '') }}" x-bind:data-state="front === 0 ? 'in' : 'out'" x-bind:dir="dir(layers[0])" x-text="layers[0]">{{ $first['title'] ?? '' }}</span>
            <span class="nx-ring-title" data-state="out" x-bind:data-state="front === 1 ? 'in' : 'out'" x-bind:dir="dir(layers[1])" x-text="layers[1]"></span>
        </div>
        <p class="nx-ring-subtitle" x-text="@js($subtitles)[current()] ?? ''">{{ $first['subtitle'] ?? '' }}</p>
    </div>
    <p class="nx-visually-hidden" aria-live="polite" x-text="[@js($titles)[current()], @js($subtitles)[current()]].filter(Boolean).join(' — ')">{{ $first['title'] ?? '' }}</p>

    <div class="nx-ring-controls">
        <x-nx::button variant="secondary" shape="pill" icon="chevron-left" icon-only :aria-label="__('nabuxui::ui.previous')" x-on:click="prev()">{{ __('nabuxui::ui.previous') }}</x-nx::button>
        <span class="nx-ring-count" aria-hidden="true"><span x-text="num(current() + 1)">{{ $num($start + 1) }}</span> / {{ $num($count) }}</span>
        <x-nx::button variant="secondary" shape="pill" icon="chevron-right" icon-only :aria-label="__('nabuxui::ui.next')" x-on:click="next()">{{ __('nabuxui::ui.next') }}</x-nx::button>
    </div>
</section>
