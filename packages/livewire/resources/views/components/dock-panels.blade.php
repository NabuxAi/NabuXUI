{{--
    <x-nx::dock-panels label="Quick panels" :items="[
        ['id' => 'music', 'label' => 'Now playing', 'icon' => 'play'],
        ['id' => 'inbox', 'label' => 'Inbox', 'icon' => 'mail'],
    ]">
        <x-slot:music>…</x-slot:music>
        <x-slot:inbox>…</x-slot:inbox>
    </x-nx::dock-panels>

    Activating an item grows the dock itself into that item's panel (the slot
    named after its id, or its `content`); Escape or a press outside folds it
    back. wire:model binds the open item's id (null when closed).
--}}
@props(['items' => [], 'value' => null, 'label' => null])
@php
    $id = \NabuXUI\NabuXUI::id('nx-dockp');
    $model = \NabuXUI\NabuXUI::model($attributes);
    $slots = $__laravel_slots ?? [];
    $lang = substr(app()->getLocale(), 0, 2);
    $label ??= match ($lang) { 'fa' => 'داک', 'ar' => 'الشريط', default => 'Dock' };
    $key = fn ($value) => preg_replace('/[^\w-]/', '_', (string) $value);
    $content = fn (array $item) => $slots[(string) $item['id']] ?? $slots[\Illuminate\Support\Str::camel((string) $item['id'])] ?? ($item['content'] ?? '');
    $renderIcon = fn ($icon) => is_string($icon) && \NabuXUI\NabuXUI::hasIcon($icon) ? \NabuXUI\NabuXUI::icon($icon) : $icon;
@endphp
<div {{ $attributes->whereDoesntStartWith('wire:model')->class('nx-dock-panels')->merge(['style' => '--_n: '.max(1, count($items))]) }}
    @if ($model) x-data="nxDockPanels(@entangle($model))" @else x-data="nxDockPanels(@js($value))" @endif
    data-state="closed" x-bind:data-state="active ? 'open' : 'closed'">
    <div class="nx-dock-panels-bar" role="toolbar" aria-label="{{ $label }}" x-ref="bar" x-on:keydown="onKey($event)">
        @foreach ($items as $i => $item)
            @php $k = $key($item['id']); $itemId = (string) $item['id']; @endphp
            <button type="button" class="nx-dock-panels-item" id="{{ $id }}-item-{{ $k }}" data-item="{{ $itemId }}" data-index="{{ $i }}"
                aria-label="{{ $item['label'] }}" aria-controls="{{ $id }}-panel-{{ $k }}"
                aria-expanded="false" x-bind:aria-expanded="active === @js($itemId) ? 'true' : 'false'"
                tabindex="{{ $i === 0 ? 0 : -1 }}" x-bind:tabindex="tabStop({{ $i }}, @js($itemId))"
                x-on:focus="stop = {{ $i }}" x-on:click="toggle(@js($itemId))">
                {{ $renderIcon($item['icon'] ?? 'grid') }}
                <span class="nx-dock-panels-tip" aria-hidden="true">{{ $item['label'] }}</span>
            </button>
        @endforeach
    </div>
    <div class="nx-dock-panels-shell" x-ref="shell" wire:ignore.self>
        <div class="nx-dock-panels-measure" x-ref="measure">
            <div class="nx-dock-panels-view">
                @foreach ($items as $item)
                    @php $k = $key($item['id']); $itemId = (string) $item['id']; @endphp
                    <section class="nx-dock-panels-content" id="{{ $id }}-panel-{{ $k }}" role="region" aria-labelledby="{{ $id }}-item-{{ $k }}"
                        aria-hidden="true" x-bind:aria-hidden="active === @js($itemId) ? null : 'true'"
                        x-bind:data-active="active === @js($itemId) ? '' : null">{{ $content($item) }}</section>
                @endforeach
            </div>
            <div class="nx-dock-panels-spacer"></div>
        </div>
    </div>
</div>
