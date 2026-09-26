{{--
    Big cards that stick over one another as the page scrolls; the covered ones shrink, lean back and dim.

    <x-nx::stacked-scroll-cards :items="[
        'plan' => ['title' => 'Plan', 'description' => '…', 'tone' => 'lapis', 'cover' => 'linear-gradient(…)'],
        'ship' => ['title' => 'Ship', 'description' => '…', 'tone' => 'gold', 'image' => '/img/ship.jpg', 'alt' => ''],
    ]">
        <x-slot:plan>…artwork for the "plan" card…</x-slot:plan>
    </x-nx::stacked-scroll-cards>

    Item keys: title, description, eyebrow (the card's number by default), tone (lapis | violet | cyan | gold or any
    colour), cover (a CSS background), image, alt. A slot named after an item's key fills its artwork panel.
    top / offset / height: where the first card sticks, how much lower each next one sticks, and the card height.
--}}
@props(['items' => [], 'top' => null, 'offset' => null, 'height' => null, 'titleAs' => 'h3'])
@php
    $slots = $__laravel_slots ?? [];
    $slotFor = fn ($key) => $slots[$key] ?? $slots[\Illuminate\Support\Str::camel((string) $key)] ?? null;
    $named = ['lapis', 'violet', 'cyan', 'gold'];
    $digits = array_combine(range(0, 9), \NabuXUI\NabuXUI::digits());
    $vars = '--nx-count: '.count($items).';'
        .($top ? " --nx-stacked-top: {$top};" : '')
        .($offset ? " --nx-stacked-offset: {$offset};" : '')
        .($height ? " --nx-stacked-height: {$height};" : '');
@endphp
<section {{ $attributes->class('nx-stacked')->merge(['style' => $vars]) }} x-data="nxStackedScroll()">
    <ol class="nx-stacked-list">
        @foreach ($items as $key => $item)
            @php
                $tone = $item['tone'] ?? null;
                $media = $slotFor($key);
            @endphp
            <li class="nx-stacked-item" style="--nx-i: {{ $loop->index }}">
                <article class="nx-stacked-card" @if (in_array($tone, $named, true)) data-tone="{{ $tone }}" @elseif ($tone) style="--nx-tone: {{ $tone }}" @endif>
                    <div class="nx-stacked-body">
                        <div>
                            <span class="nx-stacked-eyebrow">{{ $item['eyebrow'] ?? strtr(str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT), $digits) }}</span>
                            <{{ $titleAs }} class="nx-stacked-title">{{ $item['title'] ?? '' }}</{{ $titleAs }}>
                        </div>
                        @if (! empty($item['description']))<p class="nx-stacked-description">{{ $item['description'] }}</p>@endif
                    </div>
                    <div class="nx-stacked-media" @if (! empty($item['cover'])) style="background: {{ $item['cover'] }}" @endif>
                        @if ($media){{ $media }}@elseif (! empty($item['image']))<img src="{{ $item['image'] }}" alt="{{ $item['alt'] ?? '' }}" loading="lazy">@endif
                    </div>
                </article>
            </li>
        @endforeach
    </ol>
</section>
