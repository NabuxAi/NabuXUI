{{--
    <x-nx::button x-data x-on:click="$dispatch('nx-open-sheet', 'filters')">Filters</x-nx::button>
    <x-nx::bottom-sheet id="filters" title="Filters" :snaps="[0.4, 0.9]">
        …content…
    </x-nx::bottom-sheet>

    A draggable sheet on a native <dialog> (modal: focus is trapped, the page
    behind is inert). Drag the handle or the header between the snap points
    (fractions of the viewport height); past the top the pull rubber-bands,
    and a release low enough — or a downward flick faster than 0.11 px/ms —
    dismisses it. Escape, the backdrop and × close it too (unless
    `dismissible` is false). The handle is a button: Enter cycles the snaps,
    ↑/↓ step through them. Open it with a window event
    `nx-open-sheet` whose detail is the sheet's id (a button inside closes it
    with $dispatch('nx-close-sheet')), or bind `open` with
    x-model / @entangle (`x-modelable="open"` on the root). `nx-snap` bubbles
    with the resting snap's index.
--}}
@props(['id' => null, 'title' => null, 'snaps' => [0.5, 0.9], 'initial' => 0, 'dismissible' => true, 'open' => false])
@php
    use NabuXUI\NabuXUI;

    $id ??= NabuXUI::id('nx-sheet');
    $lang = substr(app()->getLocale(), 0, 2);
    $words = [
        'en' => ['handle' => 'Resize sheet', 'close' => 'Close'],
        'fa' => ['handle' => 'تغییر اندازهٔ برگه', 'close' => 'بستن'],
        'ar' => ['handle' => 'تغيير حجم اللوحة', 'close' => 'إغلاق'],
    ];
    $word = $words[$lang] ?? $words['en'];
    $truthy = fn ($v) => ! in_array(strtolower((string) $v), ['0', 'false', 'no', 'off', ''], true);
    $dismissible = $dismissible !== false && $truthy($dismissible);
    $open = $open !== false && $truthy($open);
    $snaps = array_values(array_map('floatval', (array) $snaps));
    sort($snaps);
    $config = ['snaps' => $snaps, 'initial' => max(0, min((int) $initial, count($snaps) - 1)), 'dismissible' => $dismissible, 'open' => $open, 'id' => $id];
@endphp
<div x-data="nxBottomSheet(@js($config))" x-modelable="open" {{ $attributes->whereStartsWith(['wire:model', 'x-model']) }}>
    <dialog x-ref="dialog" id="{{ $id }}" {{ $attributes->whereDoesntStartWith(['wire:model', 'x-model'])->class('nx-bsheet') }}
        @if ($title) aria-labelledby="{{ $id }}-title" @endif>
        <header class="nx-bsheet-head">
            <button type="button" class="nx-bsheet-handle" data-sheet-handle aria-label="{{ $word['handle'] }}"
                x-bind:aria-description="percent" x-on:click="cycle()" x-on:keydown="handleKey($event)"></button>
            @if ($title || $dismissible)
                <div class="nx-bsheet-titles">
                    @if ($title)<h2 class="nx-bsheet-title" id="{{ $id }}-title">{{ $title }}</h2>@else<span></span>@endif
                    @if ($dismissible)
                        <button type="button" class="nx-bsheet-close" aria-label="{{ $word['close'] }}" x-on:click="open = false">{{ NabuXUI::icon('x') }}</button>
                    @endif
                </div>
            @endif
        </header>
        <div class="nx-bsheet-body">{{ $slot }}</div>
    </dialog>
</div>
