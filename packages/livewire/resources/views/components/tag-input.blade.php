{{--
    <x-nx::tag-input name="skills" :value="['Laravel', 'Alpine']" :max="6"
        :suggestions="['Livewire', 'Tailwind', 'Vue']" wire:model.live="skills" />

    A tokenizer: type and press Enter (or a comma) to add a chip — it pops in
    on the bouncy spring — × or Backspace on an empty input removes one, a
    duplicate flashes the chip that is already there, and pasting a
    comma-separated list adds them all. `max` caps the count (shown as
    "3 / 6"); `suggestions` are offered while typing. wire:model goes on the
    component and binds the array (x-modelable); `name` posts each tag as
    name[]. Every word can be overridden with `labels` (keys: remove, tags,
    addTag, duplicate, limit, added, removed — {name} / {max} placeholders).
--}}
@props(['value' => [], 'suggestions' => [], 'max' => null, 'placeholder' => null, 'label' => null, 'name' => null, 'disabled' => false, 'labels' => [], 'locale' => null])
@php
    use NabuXUI\NabuXUI;

    $id = $attributes->get('id') ?? NabuXUI::id('nx-tag');
    $locale = str_replace('_', '-', $locale ?? app()->getLocale());
    $lang = substr($locale, 0, 2);
    $words = [
        'en' => ['remove' => 'Remove {name}', 'tags' => 'Tags', 'addTag' => 'Add a tag…', 'duplicate' => '{name} is already added', 'limit' => 'Up to {max}', 'added' => '{name} added', 'removed' => '{name} removed'],
        'fa' => ['remove' => 'حذف {name}', 'tags' => 'برچسب‌ها', 'addTag' => 'یک برچسب بنویسید…', 'duplicate' => '«{name}» از قبل اضافه شده', 'limit' => 'حداکثر {max}', 'added' => '«{name}» اضافه شد', 'removed' => '«{name}» حذف شد'],
        'ar' => ['remove' => 'إزالة {name}', 'tags' => 'الوسوم', 'addTag' => 'أضف وسمًا…', 'duplicate' => '«{name}» مضاف بالفعل', 'limit' => 'حتى {max}', 'added' => 'أُضيف «{name}»', 'removed' => 'أُزيل «{name}»'],
    ];
    $labels = array_merge($words[$lang] ?? $words['en'], is_array($labels) ? $labels : []);
    $disabled = $disabled !== false && ! in_array(strtolower((string) $disabled), ['0', 'false', 'no', 'off', ''], true);
    $tags = array_values(array_map('strval', (array) $value));
    $max = $max !== null && $max !== '' ? (int) $max : null;
    $full = $max !== null && count($tags) >= $max;
    $accessible = $label ?? $labels['tags'];
    $suggestions = array_values(array_map('strval', (array) $suggestions));
    $config = ['value' => $tags, 'suggestions' => $suggestions, 'max' => $max, 'locale' => $locale, 'labels' => $labels];
    $count = $max !== null ? NabuXUI::formatNumber(count($tags), 0, $locale).' / '.NabuXUI::formatNumber($max, 0, $locale) : null;
@endphp
<div {{ $attributes->except('id')->class('nx-taginput')->merge(['id' => $id, 'data-full' => $full ? '' : null]) }}
    x-data="nxTagInput(@js($config))" x-modelable="model" x-bind:data-full="full ? '' : null" wire:ignore>
    <div class="nx-taginput-field" x-ref="field" x-on:click="if ($event.target === $event.currentTarget) $refs.input.focus()">
        <ul class="nx-taginput-chips" aria-label="{{ $accessible }}">
            @foreach ($tags as $tag)
                <li class="nx-taginput-chip" data-state="idle" x-init="$el.remove()"><span>{{ $tag }}</span></li>
            @endforeach
            <template x-for="chip in chips" :key="chip.key">
                <li class="nx-taginput-chip" x-bind:data-state="chip.state" x-bind:data-flash="flash === chip.key ? '' : null">
                    <span x-text="chip.tag"></span>
                    @unless ($disabled)
                        <button type="button" class="nx-taginput-chip-remove" x-bind:aria-label="removeText(chip.tag)"
                            x-bind:tabindex="chip.state === 'exit' ? -1 : null"
                            x-on:click="remove(chip.tag, $event.currentTarget)">{{ NabuXUI::icon('x') }}</button>
                    @endunless
                </li>
            </template>
        </ul>
        <input x-ref="input" id="{{ $id }}-input" class="nx-taginput-input" type="text" autocomplete="off" enterkeyhint="enter"
            aria-label="{{ $accessible }}" @disabled($disabled)
            placeholder="{{ $full ? '' : ($placeholder ?? $labels['addTag']) }}"
            x-bind:placeholder="full ? '' : @js($placeholder ?? $labels['addTag'])" x-bind:readonly="full"
            @if (count($suggestions))
                role="combobox" aria-autocomplete="list" aria-controls="{{ $id }}-list" aria-expanded="false"
                x-bind:aria-expanded="open ? 'true' : 'false'"
                x-bind:aria-activedescendant="open && active >= 0 ? '{{ $id }}-opt-' + active : null"
            @endif
            x-bind:value="draft" x-on:input="typing($event.target.value)" x-on:paste="paste($event)"
            x-on:keydown="keydown($event)" x-on:focus="focused = true" x-on:blur="blur($event)">
    </div>
    @if ($name)
        <template x-for="tag in tags" :key="tag"><input type="hidden" name="{{ $name }}[]" x-bind:value="tag"></template>
        @foreach ($tags as $tag)
            <input type="hidden" name="{{ $name }}[]" value="{{ $tag }}" x-init="$el.remove()">
        @endforeach
    @endif
    @if ($count !== null)
        <p class="nx-taginput-meta">
            <span x-text="full ? @js(str_replace('{max}', NabuXUI::formatNumber($max, 0, $locale), $labels['limit'])) : ''">{{ $full ? str_replace('{max}', NabuXUI::formatNumber($max, 0, $locale), $labels['limit']) : '' }}</span>
            <span class="nx-taginput-count" x-text="countText">{{ $count }}</span>
        </p>
    @endif
    <p class="nx-visually-hidden" role="status" aria-live="polite" x-text="announce"></p>
    @if (count($suggestions))
        <div x-ref="pop" class="nx-picker-pop nx-taginput-popover" popover="manual" x-on:mousedown.prevent>
            <ul id="{{ $id }}-list" class="nx-combobox-list" role="listbox" aria-label="{{ $accessible }}">
                <template x-for="(s, i) in offered" :key="s">
                    <li class="nx-combobox-option" role="option" aria-selected="false" x-bind:id="'{{ $id }}-opt-' + i"
                        x-bind:style="'--nx-i: ' + i" x-bind:data-active="i === active ? '' : null"
                        x-on:pointermove="active = i" x-on:click="add(s, true); $refs.input.focus()">
                        {{ NabuXUI::icon('plus') }}
                        <span class="nx-combobox-option-label" x-html="markHtml(s)"></span>
                    </li>
                </template>
            </ul>
        </div>
    @endif
</div>
