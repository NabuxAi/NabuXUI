{{--
    <x-nx::language-menu name="locale" :value="'en'" wire:model.live="state.locale" :languages="[
        ['id' => 'en', 'name' => 'English', 'short' => 'EN'],
        ['id' => 'fa', 'name' => 'فارسی', 'short' => 'FA'],
    ]" x-on:nx-change="…" />

    A locale switcher. Every language's code is stacked inside the trigger and
    the chosen one rolls in while the panel folds away. The picked id posts as
    `name` and syncs the wire:model.
--}}
@props([
    'languages' => [],
    'value' => null,
    'name' => 'language',
    'label' => null,
    'locale' => null,
])
@php
    use NabuXUI\NabuXUI;
    $id = NabuXUI::id('nx-language');
    $model = NabuXUI::model($attributes);
    $wire = $attributes->whereStartsWith('wire:model');
    $lang = substr($locale ?? app()->getLocale(), 0, 2);
    $t = fn (string $en, string $fa, string $ar) => match ($lang) { 'fa' => $fa, 'ar' => $ar, default => $en };
    $label ??= $t('Language', 'زبان', 'اللغة');
    $languages = array_values(array_map(fn ($language) => [
        'id' => (string) $language['id'],
        'name' => (string) $language['name'],
        'short' => (string) ($language['short'] ?? strtoupper((string) $language['id'])),
    ], $languages));
    $current = collect($languages)->firstWhere('id', (string) $value) ?? $languages[0] ?? null;
@endphp
<div style="display: contents" x-data="nxLanguageMenu(@js($languages), @js($current['id'] ?? null), @js($model))">
    <button type="button" {{ $attributes->whereDoesntStartWith('wire:model')->class('nx-language-trigger') }} x-ref="trigger"
        popovertarget="{{ $id }}" aria-controls="{{ $id }}" aria-haspopup="listbox" aria-expanded="false"
        x-bind:aria-expanded="open ? 'true' : 'false'" aria-label="{{ $label }}: {{ $current['name'] ?? '' }}">
        <span class="nx-language-globe" aria-hidden="true">{{ NabuXUI::icon('globe') }}</span>
        <span class="nx-language-codes" aria-hidden="true">
            @foreach ($languages as $language)
                <span class="nx-language-code" @if (($current['id'] ?? null) === $language['id']) data-current @endif
                    x-bind:data-current="selected === @js($language['id']) ? '' : null">{{ $language['short'] }}</span>
            @endforeach
        </span>
        <span class="nx-language-chevron" aria-hidden="true">{{ NabuXUI::icon('chevron-down') }}</span>
    </button>
    <div class="nx-language" id="{{ $id }}" x-ref="panel" popover="auto" wire:ignore.self role="listbox" aria-label="{{ $label }}">
        <ul class="nx-language-list">
            @foreach ($languages as $language)
                <li class="nx-language-row" @if (($current['id'] ?? null) === $language['id']) data-selected @endif
                    x-bind:data-selected="selected === @js($language['id']) ? '' : null">
                    <button type="button" class="nx-language-choice" role="option"
                        aria-selected="{{ ($current['id'] ?? null) === $language['id'] ? 'true' : 'false' }}"
                        x-bind:aria-selected="selected === @js($language['id']) ? 'true' : 'false'"
                        x-on:click="choose(@js($language['id']))">
                        {{ $language['name'] }}
                        <span class="nx-language-short">{{ $language['short'] }}</span>
                        <span class="nx-language-mark">{{ NabuXUI::icon('check') }}</span>
                    </button>
                </li>
            @endforeach
        </ul>
        <input type="hidden" name="{{ $name }}" value="{{ $current['id'] ?? '' }}" x-bind:value="selected" {{ $wire }}>
    </div>
</div>
