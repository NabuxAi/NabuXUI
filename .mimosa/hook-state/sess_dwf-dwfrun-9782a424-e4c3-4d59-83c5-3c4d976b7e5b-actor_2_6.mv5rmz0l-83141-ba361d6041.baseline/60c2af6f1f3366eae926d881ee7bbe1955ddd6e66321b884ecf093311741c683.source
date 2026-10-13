{{--
    <x-nx::stacked-accordion type="single" :value="['shipping']" :items="[
        ['id' => 'shipping', 'title' => 'Shipping', 'subtitle' => 'Worldwide in 3–5 days', 'icon' => 'globe'],
        ['id' => 'returns', 'title' => 'Returns', 'content' => 'Free for 30 days.'],
    ]">
        <x-slot:shipping>…rich content…</x-slot:shipping>
    </x-nx::stacked-accordion>

    Cards stack like a deck; opening one grows it and fans the deck out.
    type: single | multiple. wire:model binds the open ids (an array).
--}}
@props(['items' => [], 'type' => 'single', 'value' => [], 'collapsible' => true, 'headingLevel' => 3])
@php
    $id = \NabuXUI\NabuXUI::id('nx-sacc');
    $model = \NabuXUI\NabuXUI::model($attributes);
    $slots = $__laravel_slots ?? [];
    $open = array_map('strval', (array) $value);
    $tag = 'h'.max(2, min(6, (int) $headingLevel));
    $key = fn ($v) => preg_replace('/[^\w-]/', '_', (string) $v);
    $content = fn (array $item) => $slots[(string) $item['id']] ?? $slots[\Illuminate\Support\Str::camel((string) $item['id'])] ?? ($item['content'] ?? '');
@endphp
<div {{ $attributes->whereDoesntStartWith('wire:model')->class('nx-stacked-accordion')->merge(['style' => '--_n: '.count($items), 'data-open' => $open ? '' : null]) }}
    @if ($model) x-data="nxStackedAccordion(@entangle($model), @js($type === 'multiple' ? 'multiple' : 'single'), @js((bool) $collapsible))"
    @else x-data="nxStackedAccordion(@js($open), @js($type === 'multiple' ? 'multiple' : 'single'), @js((bool) $collapsible))" @endif
    x-bind:data-open="open.length ? '' : null" x-on:keydown="onKey($event)">
    @foreach ($items as $i => $item)
        @php $k = $key($item['id']); $itemId = (string) $item['id']; $isOpen = in_array($itemId, $open, true); @endphp
        <div class="nx-stacked-accordion-item" style="--_i: {{ $i }}" data-state="{{ $isOpen ? 'open' : 'closed' }}" x-bind:data-state="isOpen(@js($itemId)) ? 'open' : 'closed'">
            <{{ $tag }} class="nx-stacked-accordion-heading">
                <button type="button" class="nx-stacked-accordion-trigger" id="{{ $id }}-trigger-{{ $k }}" aria-controls="{{ $id }}-panel-{{ $k }}"
                    aria-expanded="{{ $isOpen ? 'true' : 'false' }}" x-bind:aria-expanded="isOpen(@js($itemId)) ? 'true' : 'false'"
                    x-on:click="toggle(@js($itemId))" @disabled($item['disabled'] ?? false)>
                    @if (! empty($item['icon']))<span class="nx-stacked-accordion-icon" aria-hidden="true">{{ \NabuXUI\NabuXUI::icon($item['icon']) }}</span>@endif
                    <span class="nx-stacked-accordion-text"><span>{{ $item['title'] }}</span>@if (! empty($item['subtitle']))<span class="nx-stacked-accordion-subtitle">{{ $item['subtitle'] }}</span>@endif</span>
                    <span class="nx-stacked-accordion-chevron" aria-hidden="true">{{ \NabuXUI\NabuXUI::icon('chevron-down') }}</span>
                </button>
            </{{ $tag }}>
            <div class="nx-stacked-accordion-panel" id="{{ $id }}-panel-{{ $k }}" role="region" aria-labelledby="{{ $id }}-trigger-{{ $k }}">
                <div class="nx-stacked-accordion-inner"><div class="nx-stacked-accordion-body">{{ $content($item) }}</div></div>
            </div>
        </div>
    @endforeach
</div>
