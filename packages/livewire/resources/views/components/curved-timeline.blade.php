{{--
    <x-nx::curved-timeline label="Roadmap" :items="[
        ['date' => 'Q1 2025', 'title' => 'Private beta in Berlin', 'description' => '40 teams, three languages.'],
        ['date' => 'Q3 2025', 'title' => 'Twelve languages', 'description' => 'Español, 日本語, العربية…'],
    ]" />

    A line snakes through the milestones and draws itself as you scroll (CSS scroll-driven
    animations where the browser has them, a small script elsewhere); each dot lights up as the
    line reaches it. Cards alternate sides, mirrored in right-to-left pages, one column when narrow.
--}}
@props(['items' => [], 'amplitude' => null, 'label' => null])
<div {{ $attributes->class('nx-curved-timeline') }} x-data="nxCurvedTimeline(@js($amplitude ? (float) $amplitude : null))">
    <svg class="nx-curved-timeline-svg" data-nx-curve-svg aria-hidden="true">
        <path class="nx-curved-timeline-track" data-nx-curve-path/>
        <path class="nx-curved-timeline-draw" data-nx-curve-path pathLength="1"/>
    </svg>
    <ol class="nx-curved-timeline-list" @if ($label) aria-label="{{ $label }}" @endif>
        @foreach ($items as $i => $item)
            <li class="nx-curved-timeline-item" data-nx-curve-item data-nx-reveal data-side="{{ $i % 2 === 0 ? 'start' : 'end' }}" style="--nx-i: {{ $i }}">
                <span class="nx-curved-timeline-dot" data-nx-curve-dot aria-hidden="true"></span>
                <div class="nx-curved-timeline-card">
                    @if (! empty($item['date']))<p class="nx-curved-timeline-date">{{ $item['date'] }}</p>@endif
                    <h3 class="nx-curved-timeline-title">{{ $item['title'] ?? '' }}</h3>
                    @if (! empty($item['description']))<p class="nx-curved-timeline-text">{{ $item['description'] }}</p>@endif
                </div>
            </li>
        @endforeach
    </ol>
</div>
