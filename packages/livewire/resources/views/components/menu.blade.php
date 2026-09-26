{{--
    <x-nx::menu :items="[
        ['label' => 'Rename', 'icon' => 'edit', 'shortcut' => 'R', 'click' => '$wire.rename()'],
        ['type' => 'separator'],
        ['label' => 'Delete', 'icon' => 'trash', 'tone' => 'danger', 'click' => '$wire.delete()'],
    ]">
        <x-slot:trigger><x-nx::button icon-end="chevron-down">Actions</x-nx::button></x-slot:trigger>
    </x-nx::menu>
    Items take `click` (an Alpine expression) or `href`.
--}}
@props(['items' => [], 'side' => 'bottom', 'align' => 'start', 'label' => null])
@php $id = \NabuXUI\NabuXUI::id('nx-menu'); @endphp
<div style="display: contents" x-data="nxMenu(@js($side), @js($align))">
    <span x-ref="trigger" style="display: contents">{{ $trigger }}</span>
    <div x-ref="menu" id="{{ $id }}" {{ $attributes->class('nx-menu')->merge(['role' => 'menu', 'aria-label' => $label, 'popover' => 'auto']) }} @keydown="onKey($event)">
        @foreach ($items as $i => $item)
            @if (($item['type'] ?? 'item') === 'separator')
                <div class="nx-menu-separator" role="separator"></div>
            @elseif (($item['type'] ?? 'item') === 'label')
                <div class="nx-menu-label" role="presentation">{{ $item['label'] }}</div>
            @else
                @php $role = array_key_exists('checked', $item) ? 'menuitemcheckbox' : 'menuitem'; @endphp
                @if (! empty($item['href']))
                    <a class="nx-menu-item" role="{{ $role }}" href="{{ $item['href'] }}" tabindex="-1" style="--nx-i: {{ $i }}" @if (! empty($item['tone'])) data-tone="{{ $item['tone'] }}" @endif @click="hide(false)">
                @else
                    <button type="button" class="nx-menu-item" role="{{ $role }}" tabindex="-1" style="--nx-i: {{ $i }}"
                        @if (array_key_exists('checked', $item)) aria-checked="{{ $item['checked'] ? 'true' : 'false' }}" @endif
                        @if (! empty($item['disabled'])) aria-disabled="true" @endif @if (! empty($item['tone'])) data-tone="{{ $item['tone'] }}" @endif
                        @if (empty($item['disabled'])) @click="{{ trim(($item['click'] ?? '').'; hide()', '; ') }}" @endif>
                @endif
                    @if (! empty($item['icon'])){{ \NabuXUI\NabuXUI::icon($item['icon']) }}@endif<span>{{ $item['label'] }}</span>@if (! empty($item['shortcut']))<kbd class="nx-kbd">{{ $item['shortcut'] }}</kbd>@endif
                @if (! empty($item['href']))</a>@else</button>@endif
            @endif
        @endforeach
    </div>
</div>
