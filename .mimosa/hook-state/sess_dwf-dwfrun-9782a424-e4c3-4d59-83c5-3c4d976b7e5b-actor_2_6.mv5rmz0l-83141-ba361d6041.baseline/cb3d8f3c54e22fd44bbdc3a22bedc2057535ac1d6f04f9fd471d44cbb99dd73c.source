{{--
    A dock whose magnifier glides to the item under the pointer or focus.
    <x-nx::glass-dock label="Apps" :items="[
        ['label' => 'Mail', 'icon' => 'mail', 'tone' => 'cyan', 'href' => '/mail'],
        ['label' => 'Music', 'icon' => 'music', 'tone' => 'rose', 'click' => 'play'],
    ]" />
    tone: lapis | violet | cyan | gold | ink | rose | green. click: a Livewire action (wire:click).
--}}
@props(['items' => [], 'label' => null, 'preset' => 'regular'])
<nav {{ $attributes->class('nx-glass nx-glass-dock')->merge(['aria-label' => $label, 'data-preset' => $preset === 'regular' ? null : $preset]) }} x-data x-nx-glass-dock>
    <ul class="nx-glass-dock-items">
        @foreach ($items as $item)
            <li>
                @if (! empty($item['href']))
                    <a href="{{ $item['href'] }}" class="nx-glass-dock-item" aria-label="{{ $item['label'] }}" @if (! empty($item['tone'])) data-tone="{{ $item['tone'] }}" @endif @if (! empty($item['current'])) aria-current="page" @endif>{{ \NabuXUI\NabuXUI::icon($item['icon']) }}</a>
                @else
                    <button type="button" class="nx-glass-dock-item" aria-label="{{ $item['label'] }}" @if (! empty($item['tone'])) data-tone="{{ $item['tone'] }}" @endif @if (! empty($item['current'])) aria-current="page" @endif @if (! empty($item['click'])) wire:click="{{ $item['click'] }}" @endif>{{ \NabuXUI\NabuXUI::icon($item['icon']) }}</button>
                @endif
            </li>
        @endforeach
    </ul>
    <span class="nx-indicator nx-lens nx-glass-dock-lens" aria-hidden="true"></span>
    <span class="nx-glass-dock-tip" aria-hidden="true"></span>
</nav>
