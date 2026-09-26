{{--
    <x-nx::hover-reveal :items="[
        ['label' => 'Design · Diseño · デザイン', 'href' => '/design', 'description' => 'Interfaces with a sense of motion'],
        ['label' => 'Motion · Mouvement', 'href' => '/motion'],
    ]" />

    Large links. The hovered (or focused) one glows where the pointer is and its arrow
    turns; the others fade, blur a little and drift away. `static` drops the scaling.
--}}
@props(['items' => [], 'static' => false])
<ul {{ $attributes->class('nx-hover-reveal')->merge(['role' => 'list', 'data-static' => $static ? '' : null]) }} x-data="nxHoverReveal">
    @foreach (array_values($items) as $item)
        <li class="nx-hover-reveal-item">
            <a class="nx-hover-reveal-link" href="{{ $item['href'] ?? '#' }}">
                <span class="nx-hover-reveal-label" dir="auto" wire:ignore.self>{{ $item['label'] ?? '' }}</span>
                @if (! empty($item['description']))<span class="nx-hover-reveal-description">{{ $item['description'] }}</span>@endif
                <span class="nx-hover-reveal-icon" aria-hidden="true">{{ \NabuXUI\NabuXUI::icon('trend-up', 'nx-hover-reveal-rest') }}{{ \NabuXUI\NabuXUI::icon('arrow-right', 'nx-hover-reveal-active') }}</span>
            </a>
        </li>
    @endforeach
</ul>
