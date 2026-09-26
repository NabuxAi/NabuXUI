{{--
    A fanned pile of cards that flies out into a grid (FLIP on the gentle spring) and back.

    <x-nx::expandable-stack :items="[
        'brief' => ['title' => 'Brief', 'description' => '…', 'cover' => 'linear-gradient(…)'],
        'draft' => ['title' => 'Draft', 'description' => '…', 'image' => '/img/draft.jpg'],
    ]" expand-label="Show all" collapse-label="Stack them" />

    Item keys: title, description, cover (a CSS background), image, alt, tone. A slot named after an item's key
    fills its artwork. `expanded` opens it on arrival; wire:model="open" keeps it in step with a Livewire property.
--}}
@props(['items' => [], 'expanded' => false, 'expandLabel' => 'Show all', 'collapseLabel' => 'Stack them', 'titleAs' => 'h3', 'cardWidth' => null, 'cardHeight' => null])
@php
    $slots = $__laravel_slots ?? [];
    $slotFor = fn ($key) => $slots[$key] ?? $slots[\Illuminate\Support\Str::camel((string) $key)] ?? null;
    $model = \NabuXUI\NabuXUI::model($attributes);
    $named = ['lapis', 'violet', 'cyan', 'gold'];
    $listId = \NabuXUI\NabuXUI::id('nx-xstack');
    $open = (bool) $expanded;
    $vars = '--nx-count: '.count($items).';'
        .($cardWidth ? " --nx-xstack-card-width: {$cardWidth};" : '')
        .($cardHeight ? " --nx-xstack-card-height: {$cardHeight};" : '');
@endphp
<div {{ $attributes->whereDoesntStartWith('wire:model')->class('nx-xstack')->merge(['style' => $vars, 'data-expanded' => $open ? '' : null]) }}
    @if ($model) x-data="nxExpandableStack(@entangle($model))" @else x-data="nxExpandableStack(@js($open))" @endif
    x-bind:data-expanded="shown ? '' : null">
    <ul class="nx-xstack-list" id="{{ $listId }}" x-ref="list" x-on:click="shown || (open = true)">
        @foreach ($items as $key => $item)
            @php
                $tone = $item['tone'] ?? null;
                $media = $slotFor($key);
                $tucked = ! $open && $loop->index > 0;
            @endphp
            <li class="nx-xstack-item" style="--nx-i: {{ $loop->index }}" @if ($tucked) inert aria-hidden="true" @endif
                x-bind:inert="! shown && {{ $loop->index }} > 0" x-bind:aria-hidden="! shown && {{ $loop->index }} > 0 ? 'true' : null">
                <article class="nx-xstack-card" @if (in_array($tone, $named, true)) data-tone="{{ $tone }}" @elseif ($tone) style="--nx-tone: {{ $tone }}" @endif>
                    <div class="nx-xstack-media" @if (! empty($item['cover'])) style="background: {{ $item['cover'] }}" @endif>
                        @if ($media){{ $media }}@elseif (! empty($item['image']))<img src="{{ $item['image'] }}" alt="{{ $item['alt'] ?? '' }}" loading="lazy">@endif
                    </div>
                    <div class="nx-xstack-text">
                        <{{ $titleAs }} class="nx-xstack-title">{{ $item['title'] ?? '' }}</{{ $titleAs }}>
                        @if (! empty($item['description']))<p class="nx-xstack-description">{{ $item['description'] }}</p>@endif
                    </div>
                </article>
            </li>
        @endforeach
    </ul>
    <x-nx::button class="nx-xstack-toggle" variant="secondary" shape="pill" icon-end="chevron-down" aria-controls="{{ $listId }}" aria-expanded="{{ $open ? 'true' : 'false' }}"
        x-bind:aria-expanded="open ? 'true' : 'false'" x-on:click="toggle()"><span x-text="open ? @js($collapseLabel) : @js($expandLabel)">{{ $open ? $collapseLabel : $expandLabel }}</span></x-nx::button>
</div>
