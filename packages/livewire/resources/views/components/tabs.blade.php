{{--
    <x-nx::tabs :items="['chat' => 'Chat', 'files' => 'Files']" wire:model.live="tab">
        <x-slot:chat>…</x-slot:chat>
        <x-slot:files>…</x-slot:files>
    </x-nx::tabs>
    variant: pill (default) | underline | enclosed. Items may also be ['key' => ['label' => …, 'icon' => …]].
--}}
@props(['items' => [], 'value' => null, 'variant' => 'pill', 'label' => null])
@php
    $id = \NabuXUI\NabuXUI::id('nx-tabs');
    $model = \NabuXUI\NabuXUI::model($attributes);
    $first = array_key_first($items);
    $value ??= $first;
    $slots = $__laravel_slots ?? [];
@endphp
<div {{ $attributes->whereDoesntStartWith('wire:model')->class('nx-tabs')->merge(['data-variant' => $variant === 'pill' ? null : $variant]) }}
    @if ($model) x-data="nxTabs(@entangle($model))" @else x-data="nxTabs(@js((string) $value))" @endif>
    <div class="nx-tab-list" role="tablist" x-ref="list" @if ($label) aria-label="{{ $label }}" @endif @keydown="onKey($event)">
        <span class="nx-indicator" aria-hidden="true"></span>
        @foreach ($items as $key => $item)
            @php $item = is_array($item) ? $item : ['label' => $item]; @endphp
            <button type="button" role="tab" class="nx-tab" id="{{ $id }}-tab-{{ $key }}" data-value="{{ $key }}" aria-controls="{{ $id }}-panel-{{ $key }}"
                x-bind:aria-selected="value === @js((string) $key) ? 'true' : 'false'" x-bind:tabindex="value === @js((string) $key) ? 0 : -1"
                aria-selected="{{ (string) $key === (string) $value ? 'true' : 'false' }}" tabindex="{{ (string) $key === (string) $value ? 0 : -1 }}"
                @click="select(@js((string) $key))" @disabled($item['disabled'] ?? false)>
                @if (! empty($item['icon'])){{ \NabuXUI\NabuXUI::icon($item['icon']) }}@endif{{ $item['label'] }}@if (! empty($item['badge']))<span class="nx-badge" data-tone="accent">{{ $item['badge'] }}</span>@endif
            </button>
        @endforeach
    </div>
    @foreach ($items as $key => $item)
        <div class="nx-tab-panel" role="tabpanel" id="{{ $id }}-panel-{{ $key }}" aria-labelledby="{{ $id }}-tab-{{ $key }}" tabindex="0"
            x-show="value === @js((string) $key)" @if ((string) $key !== (string) $value) style="display: none" @endif>{{ $slots[$key] ?? '' }}</div>
    @endforeach
</div>
