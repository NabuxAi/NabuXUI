{{-- items: [['label' => …, 'icon' => …, 'href' => …, 'current' => bool]] or buttons with 'click' => Alpine expression. --}}
@props(['items' => [], 'variant' => 'glass', 'size' => null, 'label' => null])
<nav {{ $attributes->class('nx-dock')->merge(['data-variant' => $variant === 'metal' ? 'metal' : null, 'aria-label' => $label, 'style' => $size ? "--nx-dock-size: {$size}" : null]) }} x-data x-nx-dock>
    @foreach ($items as $item)
        @if (! empty($item['href']))
            <a class="nx-dock-item" href="{{ $item['href'] }}" aria-label="{{ $item['label'] }}" @if (! empty($item['current'])) aria-current="page" @endif>
        @else
            <button type="button" class="nx-dock-item" aria-label="{{ $item['label'] }}" @if (! empty($item['click'])) x-on:click="{{ $item['click'] }}" @endif>
        @endif
            {{ \NabuXUI\NabuXUI::icon($item['icon']) }}<span class="nx-dock-label" aria-hidden="true">{{ $item['label'] }}</span>
        @if (! empty($item['href']))</a>@else</button>@endif
    @endforeach
</nav>
