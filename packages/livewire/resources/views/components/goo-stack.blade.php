{{--
    <x-nx::goo-stack x-ref="stack" label="Notifications" :items="[
        ['id' => 'a', 'label' => 'Invoice #1042 paid', 'icon' => 'check-circle'],
        ['id' => 'b', 'label' => 'Ava commented on Roadmap', 'icon' => 'message'],
    ]" :icons="['bell', 'upload']" />

    Pills that melt into one liquid shape at rest and split apart on hover or
    focus (state="merged" | "split" pins either). Alpine owns the list: call
    add({ label, icon }) / dismiss(id) on it (e.g. from x-on handlers inside the
    same x-data, or dispatch `nx-goo-add` with { label, icon } to the window).
    Each dismissal dispatches `nx-goo-dismiss` with { id }. `icons` lists extra
    icon names that pills added later may use.
--}}
@props([
    'items' => [],
    'state' => 'auto',
    'strength' => 8,
    'label' => null,
    'dismissLabel' => 'Dismiss',
    'icons' => [],
])
@php
    $items = array_values(array_map(fn ($item, $i) => [
        'id' => (string) ($item['id'] ?? 'goo-'.$i),
        'label' => (string) ($item['label'] ?? ''),
        'icon' => $item['icon'] ?? null,
    ], $items, array_keys($items)));
    $iconNames = array_unique(array_filter(array_merge(array_column($items, 'icon'), (array) $icons)));
    $svgs = [];
    foreach ($iconNames as $name) {
        $svgs[$name] = (string) \NabuXUI\NabuXUI::icon($name);
    }
    $filter = \NabuXUI\NabuXUI::id('nx-goo');
@endphp
<div {{ $attributes->class('nx-goo')->merge([
    'data-state' => $state === 'auto' ? null : $state,
    'style' => "--_goo: url(#{$filter})",
]) }} x-data="nxGooStack(@js($items))" x-on:nx-goo-add.window="add($event.detail ?? {})" wire:ignore>
    <svg class="nx-goo-filter" aria-hidden="true" focusable="false">
        <filter id="{{ $filter }}">
            <feGaussianBlur in="SourceGraphic" stdDeviation="{{ (float) $strength }}" result="blur" />
            <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 20 -9" result="goo" />
            <feComposite in="SourceGraphic" in2="goo" operator="atop" />
        </filter>
    </svg>
    <ul class="nx-goo-layer nx-goo-blobs" aria-hidden="true">
        <template x-for="item in items" :key="item.id">
            <li class="nx-goo-pill" x-bind:data-state="item.state">
                <template x-if="item.icon"><span class="nx-goo-icon"></span></template>
                <span class="nx-goo-label" x-text="item.label"></span>
                <span class="nx-goo-dismiss"></span>
            </li>
        </template>
    </ul>
    <ul class="nx-goo-layer nx-goo-items" @if ($label) aria-label="{{ $label }}" @endif aria-live="polite">
        <template x-for="item in items" :key="item.id">
            <li class="nx-goo-pill" x-bind:data-state="item.state">
                <template x-if="item.icon"><span class="nx-goo-icon" aria-hidden="true" x-html="@js($svgs)[item.icon] ?? ''"></span></template>
                <span class="nx-goo-label" x-text="item.label"></span>
                <button type="button" class="nx-goo-dismiss" aria-label="{{ $dismissLabel }}" x-bind:disabled="item.state === 'leave'" x-on:click="dismiss(item.id)">{{ \NabuXUI\NabuXUI::icon('x') }}</button>
            </li>
        </template>
    </ul>
</div>
