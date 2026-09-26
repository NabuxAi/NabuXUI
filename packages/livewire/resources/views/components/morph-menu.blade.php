{{--
    <x-nx::morph-menu label="Filter" name="filters" :options="[
        ['value' => 'design', 'label' => 'Design', 'icon' => 'edit'],
        ['value' => 'es', 'label' => 'Español'],
    ]" wire:model.live="filters" />

    The trigger's own container grows into the list. The checkboxes are native
    (they post as name[]) and carry the wire:model themselves. Options may also
    be ['value' => 'Label']. side="top" grows upward.
--}}
@props(['options' => [], 'value' => [], 'name' => 'filters', 'label' => null, 'icon' => 'sliders', 'side' => 'bottom', 'locale' => null])
@php
    use NabuXUI\NabuXUI;
    $id = NabuXUI::id('nx-morph');
    $model = NabuXUI::model($attributes);
    $wire = $attributes->whereStartsWith('wire:model');
    $lang = substr($locale ?? app()->getLocale(), 0, 2);
    $t = fn (string $en, string $fa, string $ar) => match ($lang) { 'fa' => $fa, 'ar' => $ar, default => $en };
    $label ??= $t('Filter', 'فیلتر', 'تصفية');
    $list = array_is_list($options) ? $options : array_map(fn ($v, $l) => is_array($l) ? ['value' => $v] + $l : ['value' => $v, 'label' => $l], array_keys($options), $options);
    $selected = array_map('strval', (array) $value);
    $count = count($selected);
    $top = $side === 'top';
@endphp
<div {{ $attributes->whereDoesntStartWith('wire:model')->class('nx-morph-menu')->merge(['data-side' => $top ? 'top' : null]) }}
    x-data="nxMorphMenu(@js($model), @js($top ? 'top' : 'bottom'), @js($locale))" wire:ignore.self
    data-state="closed" x-bind:data-state="open ? 'open' : 'closed'">
    <div class="nx-morph-menu-shell" x-ref="shell" wire:ignore.self>
        <div class="nx-morph-menu-measure" x-ref="measure">
            <button type="button" class="nx-morph-menu-trigger" x-ref="trigger" aria-controls="{{ $id }}-list"
                aria-expanded="false" x-bind:aria-expanded="open ? 'true' : 'false'"
                x-on:click="open = ! open" x-on:keydown="keyTrigger($event)">
                {{ NabuXUI::icon($icon) }}
                <span>{{ $label }}</span>
                <span class="nx-morph-menu-count" @if ($count) data-active @else aria-hidden="true" @endif
                    x-bind:data-active="count ? '' : null" x-bind:aria-hidden="count ? null : 'true'">
                    <span><span class="nx-morph-menu-badge"><x-nx::number x-ref="count" :value="$count" :locale="$locale" :reveal="false" wire:ignore /></span></span>
                </span>
                <span class="nx-morph-menu-chevron" aria-hidden="true">{{ NabuXUI::icon('chevron-down') }}</span>
            </button>
            <div class="nx-morph-menu-list" id="{{ $id }}-list" x-ref="list" role="group" aria-label="{{ $label }}" x-on:keydown="keyList($event)">
                @foreach ($list as $i => $option)
                    <label class="nx-morph-menu-option" style="--nx-i: {{ $i }}">
                        <input class="nx-morph-menu-input" type="checkbox" name="{{ $name }}[]" value="{{ $option['value'] }}" {{ $wire }}
                            @checked(in_array((string) $option['value'], $selected, true)) @disabled($option['disabled'] ?? false)>
                        @if (! empty($option['icon'])){{ NabuXUI::icon($option['icon']) }}@endif
                        <span>{{ $option['label'] }}</span>
                        <span class="nx-morph-menu-check" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12.5l4.5 4.5L19 7.5" pathLength="1"/></svg></span>
                    </label>
                @endforeach
            </div>
            <div class="nx-morph-menu-footer">
                <span aria-live="polite" x-text="count ? @js($t(':count selected', ':count مورد انتخاب شد', 'تم تحديد :count')).replace(':count', new Intl.NumberFormat(@js($lang)).format(count)) : ''"></span>
                <button type="button" class="nx-morph-menu-clear" @disabled(! $count) x-bind:disabled="! count" x-on:click="clear()">{{ $t('Clear', 'پاک کردن', 'مسح') }}</button>
            </div>
        </div>
    </div>
</div>
