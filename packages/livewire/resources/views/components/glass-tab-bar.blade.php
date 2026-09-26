{{--
    A floating glass tab bar; the active tab sits under a lens.
    <x-nx::glass-tab-bar label="Main" :items="[
        ['value' => 'home', 'label' => 'Home', 'icon' => 'home', 'href' => '/'],
        ['value' => 'saved', 'label' => 'Saved', 'icon' => 'heart', 'href' => '/saved'],
    ]" value="home" minimize-on-scroll />
    Items without href are buttons bound to value (wire:model works too). minimize-on-scroll: true
    watches the window; a CSS selector watches that scrolling container.
--}}
@props(['items' => [], 'value' => null, 'label' => null, 'compact' => false, 'minimizeOnScroll' => false, 'preset' => 'regular'])
@php
    $model = \NabuXUI\NabuXUI::model($attributes);
    $live = collect(array_keys($attributes->getAttributes()))->contains(fn ($key) => str_starts_with($key, 'wire:model') && str_contains($key, '.live'));
    $value ??= $items[0]['value'] ?? null;
    $minimize = is_string($minimizeOnScroll) && $minimizeOnScroll !== '' && $minimizeOnScroll !== '1' ? $minimizeOnScroll : (bool) $minimizeOnScroll;
@endphp
<nav {{ $attributes->whereDoesntStartWith('wire:model')->class('nx-glass nx-glass-tabbar')->merge([
        'aria-label' => $label,
        'data-preset' => $preset === 'regular' ? null : $preset,
        'data-compact' => $compact ? '' : null,
    ]) }}
    @if ($model) x-data="nxGlassTabBar(@entangle($model){{ $live ? '.live' : '' }}, @js($minimize))" @else x-data="nxGlassTabBar(@js((string) $value), @js($minimize))" @endif>
    <ul class="nx-glass-tabbar-items">
        @foreach ($items as $item)
            @php $key = (string) ($item['value'] ?? $item['label']); @endphp
            <li>
                @if (! empty($item['href']))
                    <a href="{{ $item['href'] }}" class="nx-glass-tab" aria-label="{{ $item['label'] }}" @if ($key === (string) $value) aria-current="page" @endif>
                @else
                    <button type="button" class="nx-glass-tab" aria-label="{{ $item['label'] }}" @if ($key === (string) $value) aria-current="page" @endif
                        x-bind:aria-current="value === @js($key) ? 'page' : null" x-on:click="value = @js($key)">
                @endif
                    {{ \NabuXUI\NabuXUI::icon($item['icon']) }}<span aria-hidden="true">{{ $item['label'] }}</span>
                @if (! empty($item['href']))
                    </a>
                @else
                    </button>
                @endif
            </li>
        @endforeach
    </ul>
    <span class="nx-indicator nx-lens nx-glass-tabbar-lens" aria-hidden="true"></span>
</nav>
