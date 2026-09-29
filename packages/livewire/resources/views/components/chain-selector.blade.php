{{--
    <x-nx::chain-selector name="chain" :value="ethereum" wire:model.live="state.chain" :chains="[
        ['id' => 'ethereum', 'name' => 'Ethereum', 'symbol' => 'ETH', 'tag' => 'L1', 'icon' => 'zap', 'tone' => 'lapis'],
        ['id' => 'polygon', 'name' => 'Polygon', 'symbol' => 'POL', 'tag' => 'L2', 'icon' => 'layers', 'tone' => 'violet'],
    ]" />

    A network picker. Every chain's glyph is stacked inside the trigger and the
    chosen one morphs in (speck → icon) while the panel folds away. The panel is
    a searchable list; the picked chain posts as `name` and syncs the wire:model.
--}}
@props([
    'chains' => [],
    'value' => null,
    'name' => 'chain',
    'label' => null,
    'placeholder' => null,
    'emptyText' => null,
    'locale' => null,
])
@php
    use NabuXUI\NabuXUI;
    $id = NabuXUI::id('nx-chains');
    $model = NabuXUI::model($attributes);
    $wire = $attributes->whereStartsWith('wire:model');
    $lang = substr($locale ?? app()->getLocale(), 0, 2);
    $t = fn (string $en, string $fa, string $ar) => match ($lang) { 'fa' => $fa, 'ar' => $ar, default => $en };
    $label ??= $t('Network', 'شبکه', 'الشبكة');
    $chains = array_values(array_map(fn ($chain) => [
        'id' => (string) $chain['id'],
        'name' => (string) $chain['name'],
        'symbol' => $chain['symbol'] ?? null,
        'tag' => $chain['tag'] ?? null,
        'icon' => $chain['icon'] ?? 'globe',
        'tone' => in_array($chain['tone'] ?? null, ['lapis', 'violet', 'cyan', 'gold'], true) ? $chain['tone'] : 'lapis',
    ], $chains));
    $current = collect($chains)->firstWhere('id', (string) $value) ?? $chains[0] ?? null;
    $search = $t('Search networks…', 'جست‌وجوی شبکه‌ها…', 'ابحث عن الشبكات…');
    $empty = $emptyText ?? __('nabuxui::ui.noResults');
@endphp
<div style="display: contents" x-data="nxChainSelector(@js($chains), @js($current['id'] ?? null), @js($model))">
    <button type="button" {{ $attributes->whereDoesntStartWith('wire:model')->class('nx-chain-selector-trigger') }} x-ref="trigger"
        popovertarget="{{ $id }}" aria-controls="{{ $id }}" aria-haspopup="listbox" aria-expanded="false"
        x-bind:aria-expanded="open ? 'true' : 'false'" aria-label="{{ $label }}: {{ $current['name'] ?? '' }}">
        <span class="nx-chain-selector-glyph" x-bind:data-tone="current?.tone ?? 'lapis'" aria-hidden="true">
            @foreach ($chains as $chain)
                <span class="nx-chain-selector-morph" x-bind:data-current="selected === @js($chain['id']) ? '' : null">
                    {{ NabuXUI::icon($chain['icon']) }}
                </span>
            @endforeach
        </span>
        <span class="nx-chain-selector-who">
            <span class="nx-chain-selector-name" x-text="current?.name ?? ''">{{ $current['name'] ?? '—' }}</span>
            <span class="nx-chain-selector-tag" x-text="[current?.tag, current?.symbol].filter(Boolean).join(' · ')">
                {{ trim(($current['tag'] ?? '').' · '.($current['symbol'] ?? ''), ' ·') }}
            </span>
        </span>
    </button>
    <div class="nx-chain-selector" id="{{ $id }}" x-ref="panel" popover="auto" wire:ignore.self role="listbox" aria-label="{{ $label }}">
        <div class="nx-chain-selector-search">
            {{ NabuXUI::icon('search') }}
            <input type="search" class="nx-chain-selector-input" x-ref="search" x-model="query" aria-label="{{ __('nabuxui::ui.search') }}"
                aria-controls="{{ $id }}-list" placeholder="{{ $placeholder ?? $search }}">
        </div>
        <ul class="nx-chain-selector-list" id="{{ $id }}-list" aria-label="{{ $label }}">
            @foreach ($chains as $chain)
                <li class="nx-chain-selector-row" @if (($current['id'] ?? null) === $chain['id']) data-selected @endif
                    x-bind:data-selected="selected === @js($chain['id']) ? '' : null"
                    x-show="matches(@js($chain['name'].' '.($chain['symbol'] ?? '').' '.($chain['tag'] ?? '')))">
                    <button type="button" class="nx-chain-selector-choice" role="option" aria-selected="{{ ($current['id'] ?? null) === $chain['id'] ? 'true' : 'false' }}"
                        x-bind:aria-selected="selected === @js($chain['id']) ? 'true' : 'false'" x-on:click="choose(@js($chain['id']))">
                        <span class="nx-chain-selector-glyph" data-tone="{{ $chain['tone'] }}" aria-hidden="true">
                            <span class="nx-chain-selector-morph" data-current>{{ NabuXUI::icon($chain['icon']) }}</span>
                        </span>
                        <span class="nx-chain-selector-what">
                            <span class="nx-chain-selector-name">{{ $chain['name'] }}</span>
                            @if ($chain['tag'] || $chain['symbol'])
                                <span class="nx-chain-selector-tag">{{ trim(($chain['tag'] ?? '').' · '.($chain['symbol'] ?? ''), ' ·') }}</span>
                            @endif
                        </span>
                        <span class="nx-chain-selector-mark">{{ NabuXUI::icon('check') }}</span>
                    </button>
                </li>
            @endforeach
        </ul>
        <p class="nx-chain-selector-empty" x-show="empty" style="display: none">{{ $empty }}</p>
        <input type="hidden" name="{{ $name }}" value="{{ $current['id'] ?? '' }}" x-bind:value="selected" {{ $wire }}>
    </div>
</div>
