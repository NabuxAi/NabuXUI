{{--
    <x-nx::context-menu :items="[
        ['id' => 'open', 'label' => 'Open', 'icon' => 'external-link', 'shortcut' => '↵'],
        ['id' => 'share', 'label' => 'Share', 'items' => [['id' => 'copy-link', 'label' => 'Copy link']]],
        ['separator' => true],
        ['id' => 'delete', 'label' => 'Delete', 'icon' => 'trash', 'tone' => 'danger'],
    ]" x-on:nx-select="$wire.run($event.detail)">
        …the area (a file card, a table row, a canvas)…
    </x-nx::context-menu>

    Right-click the area (or long-press it on touch, or focus it and press
    Shift+F10 / the ContextMenu key) and the menu opens at the pointer,
    scaling out of it; it flips to stay on screen. Arrow keys, Home/End and
    type-to-select move through the items; one level of submenu opens with
    the inline-end arrow (or a hover) and closes with the other arrow. Item
    keys: id, label, icon, shortcut, disabled, tone ('danger'), separator,
    heading, items (a submenu). Picking an item dispatches `nx-select` with its
    id; listen with x-on:nx-select (Alpine) or forward it to Livewire.
--}}
@props(['items' => [], 'label' => null, 'hint' => null, 'pressDelay' => 500])
@php
    use NabuXUI\NabuXUI;

    $lang = substr(app()->getLocale(), 0, 2);
    $words = [
        'en' => ['hint' => 'Right-click, long-press or press Shift+F10 for actions', 'actions' => 'Actions'],
        'fa' => ['hint' => 'برای کارها راست‌کلیک کنید، انگشت را نگه دارید یا Shift+F10 بزنید', 'actions' => 'کارها'],
        'ar' => ['hint' => 'انقر بالزر الأيمن أو اضغط مطولًا أو Shift+F10 للإجراءات', 'actions' => 'الإجراءات'],
    ];
    $word = $words[$lang] ?? $words['en'];
    $id = NabuXUI::id('nx-ctx');
    $icon = fn ($name) => $name && NabuXUI::hasIcon((string) $name) ? NabuXUI::icon((string) $name) : '';
@endphp
<div {{ $attributes->class('nx-ctxmenu')->merge(['tabindex' => '0', 'aria-describedby' => $id.'-hint']) }}
    x-data="nxContextMenu(@js(['pressDelay' => (int) $pressDelay]))">
    {{ $slot }}
    <span id="{{ $id }}-hint" class="nx-visually-hidden">{{ $hint ?? $word['hint'] }}</span>
    <div x-ref="menu" class="nx-picker-pop nx-ctxmenu-menu" popover="auto" role="menu" tabindex="-1"
        aria-label="{{ $label ?? $word['actions'] }}" x-on:keydown="keydown($event)" x-on:contextmenu.prevent>
        @foreach ($items as $item)
            @if (! empty($item['separator']))
                <hr class="nx-ctxmenu-sep">
            @elseif (! empty($item['heading']))
                <p class="nx-ctxmenu-heading" role="presentation">{{ $item['label'] ?? '' }}</p>
            @elseif (! empty($item['items']))
                <button type="button" role="menuitem" tabindex="-1" class="nx-ctxmenu-item" aria-haspopup="menu" aria-expanded="false"
                    @if (! empty($item['disabled'])) aria-disabled="true" @endif
                    x-on:click="pick($el)" x-on:pointerenter="hoverSub($el)" x-on:pointerleave="leaveSub()">
                    {{ $icon($item['icon'] ?? null) }}<span class="nx-ctxmenu-label">{{ $item['label'] ?? '' }}</span>
                    {{ NabuXUI::icon('chevron-right', 'nx-ctxmenu-sub-arrow') }}
                </button>
                <div class="nx-picker-pop nx-ctxmenu-menu" data-sub popover="auto" role="menu" aria-label="{{ $item['label'] ?? '' }}">
                    @foreach ($item['items'] as $sub)
                        @if (! empty($sub['separator']))
                            <hr class="nx-ctxmenu-sep">
                        @else
                            <button type="button" role="menuitem" tabindex="-1" class="nx-ctxmenu-item" data-id="{{ $sub['id'] ?? '' }}"
                                @if (($sub['tone'] ?? null) === 'danger') data-tone="danger" @endif
                                @if (! empty($sub['disabled'])) aria-disabled="true" @endif
                                x-on:click="pick($el)">
                                {{ $icon($sub['icon'] ?? null) }}<span class="nx-ctxmenu-label">{{ $sub['label'] ?? '' }}</span>
                                @if (! empty($sub['shortcut']))<kbd class="nx-ctxmenu-shortcut" dir="ltr">{{ $sub['shortcut'] }}</kbd>@endif
                            </button>
                        @endif
                    @endforeach
                </div>
            @else
                <button type="button" role="menuitem" tabindex="-1" class="nx-ctxmenu-item" data-id="{{ $item['id'] ?? '' }}"
                    @if (($item['tone'] ?? null) === 'danger') data-tone="danger" @endif
                    @if (! empty($item['disabled'])) aria-disabled="true" @endif
                    x-on:click="pick($el)">
                    {{ $icon($item['icon'] ?? null) }}<span class="nx-ctxmenu-label">{{ $item['label'] ?? '' }}</span>
                    @if (! empty($item['shortcut']))<kbd class="nx-ctxmenu-shortcut" dir="ltr">{{ $item['shortcut'] }}</kbd>@endif
                </button>
            @endif
        @endforeach
    </div>
</div>
