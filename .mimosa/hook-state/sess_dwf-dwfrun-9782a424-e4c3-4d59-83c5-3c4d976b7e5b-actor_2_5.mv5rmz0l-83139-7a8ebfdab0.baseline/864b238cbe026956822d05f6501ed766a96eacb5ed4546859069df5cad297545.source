{{-- items: [['href' => …, 'label' => …, 'current' => bool]]; add wire:navigate to route through Livewire. --}}
@props(['items' => [], 'label' => null, 'navigate' => false])
<nav {{ $attributes->class('nx-navmenu')->merge(['aria-label' => $label]) }} x-data="nxNavIndicator()">
    <ul>
        @foreach ($items as $item)
            <li><a class="nx-navmenu-link" href="{{ $item['href'] }}" @if (! empty($item['current'])) aria-current="page" @endif @if ($navigate) wire:navigate @endif>{{ $item['label'] }}</a></li>
        @endforeach
    </ul>
    <span class="nx-indicator" aria-hidden="true"></span>
</nav>
