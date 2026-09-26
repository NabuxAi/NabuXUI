{{--
    <x-nx::morph-tabs label="Sections" :items="[
        ['value' => 'home', 'label' => 'Home', 'icon' => 'home'],
        ['value' => 'inbox', 'label' => 'Inbox', 'icon' => 'mail'],
    ]" wire:model.live="tab">
        <x-slot:home>…</x-slot:home>   (optional panels, named by value)
    </x-nx::morph-tabs>

    Icon tabs: the active one opens to show its label while the shared pill
    follows it. justify: start | center | end.
--}}
@props(['items' => [], 'value' => null, 'label' => null, 'justify' => 'start'])
@php
    $id = \NabuXUI\NabuXUI::id('nx-mtabs');
    $model = \NabuXUI\NabuXUI::model($attributes);
    $value = (string) ($value ?? ($items[0]['value'] ?? ''));
    $slots = $__laravel_slots ?? [];
    $content = fn (array $item) => $slots[(string) $item['value']] ?? $slots[\Illuminate\Support\Str::camel((string) $item['value'])] ?? ($item['content'] ?? null);
    $panels = collect($items)->contains(fn ($item) => $content($item) !== null);
    $key = fn ($v) => preg_replace('/[^\w-]/', '_', (string) $v);
    $justifyValue = ['start' => 'flex-start', 'center' => 'center', 'end' => 'flex-end'][$justify] ?? 'flex-start';
    $renderIcon = fn ($icon) => is_string($icon) && \NabuXUI\NabuXUI::hasIcon($icon) ? \NabuXUI\NabuXUI::icon($icon) : $icon;
@endphp
<div {{ $attributes->whereDoesntStartWith('wire:model')->class('nx-morph-tabs')->merge(['style' => "--nx-morph-tabs-justify: {$justifyValue}"]) }}
    @if ($model) x-data="nxMorphTabs(@entangle($model))" @else x-data="nxMorphTabs(@js($value))" @endif>
    <div class="nx-morph-tabs-rail" x-ref="rail" wire:ignore.self>
        <span class="nx-indicator nx-morph-tabs-pill" aria-hidden="true"></span>
        <div class="nx-morph-tabs-list" role="tablist" x-ref="list" @if ($label) aria-label="{{ $label }}" @endif x-on:keydown="onKey($event)">
            @foreach ($items as $item)
                @php $v = (string) $item['value']; $selected = $v === $value; @endphp
                <button type="button" role="tab" class="nx-morph-tab" id="{{ $id }}-tab-{{ $key($v) }}" data-value="{{ $v }}"
                    @if ($panels) aria-controls="{{ $id }}-panel-{{ $key($v) }}" @endif
                    aria-selected="{{ $selected ? 'true' : 'false' }}" x-bind:aria-selected="String(value) === @js($v) ? 'true' : 'false'"
                    tabindex="{{ $selected ? 0 : -1 }}" x-bind:tabindex="String(value) === @js($v) ? 0 : -1"
                    x-on:click="select(@js($v))" @disabled($item['disabled'] ?? false)>
                    {{ $renderIcon($item['icon'] ?? 'grid') }}
                    <span class="nx-morph-tab-label" data-nx-tab-label><span>{{ $item['label'] }}</span></span>
                    <span class="nx-morph-tab-tip" aria-hidden="true">{{ $item['label'] }}</span>
                </button>
            @endforeach
        </div>
    </div>
    @if ($panels)
        @foreach ($items as $item)
            @php $v = (string) $item['value']; @endphp
            <div class="nx-morph-tabs-panel" role="tabpanel" id="{{ $id }}-panel-{{ $key($v) }}" aria-labelledby="{{ $id }}-tab-{{ $key($v) }}" tabindex="0"
                x-show="String(value) === @js($v)" @if ($v !== $value) style="display: none" @endif>{{ $content($item) }}</div>
        @endforeach
    @endif
</div>
