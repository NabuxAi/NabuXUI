{{--
    <x-nx::member-selector name="members" :members="[
        ['id' => 'kenji', 'name' => 'Kenji Sato', 'email' => 'kenji@example.jp'],
        ['id' => 'maria', 'name' => 'María López', 'email' => 'maria@example.es'],
    ]" :value="['kenji']" wire:model.live="members" roles-model="roles" />

    An avatar-stack button and a searchable member list. The checkboxes post as
    name[] and carry the wire:model; each selected row's role <select> posts as
    name_roles[id] and binds to `roles-model`.id when given.
--}}
@props([
    'members' => [],
    'value' => [],
    'name' => 'members',
    'roles' => [],
    'roleOptions' => null,
    'defaultRole' => null,
    'rolesModel' => null,
    'max' => 4,
    'label' => null,
    'placeholder' => null,
    'emptyText' => null,
    'locale' => null,
])
@php
    use NabuXUI\NabuXUI;
    $id = NabuXUI::id('nx-members');
    $model = NabuXUI::model($attributes);
    $wire = $attributes->whereStartsWith('wire:model');
    $lang = substr($locale ?? app()->getLocale(), 0, 2);
    $t = fn (string $en, string $fa, string $ar) => match ($lang) { 'fa' => $fa, 'ar' => $ar, default => $en };
    $label ??= $t('Members', 'اعضا', 'الأعضاء');
    $options = $roleOptions ?? [
        ['value' => 'viewer', 'label' => $t('Viewer', 'بیننده', 'مشاهد')],
        ['value' => 'editor', 'label' => $t('Editor', 'ویرایشگر', 'محرر')],
        ['value' => 'admin', 'label' => $t('Admin', 'مدیر', 'مسؤول')],
    ];
    $defaultRole ??= $options[0]['value'] ?? 'viewer';
    $members = array_values(array_map(fn ($m) => ['id' => (string) $m['id'], 'name' => $m['name'], 'email' => $m['email'] ?? null, 'avatar' => $m['avatar'] ?? null], $members));
    $selected = array_map('strval', (array) $value);
    $chosen = array_values(array_filter(array_map(fn ($key) => collect($members)->firstWhere('id', $key), $selected)));
    $shown = count($chosen) > $max ? array_slice($chosen, 0, $max - 1) : $chosen;
    $restCount = count($chosen) - count($shown);
    $roleFor = $t('Role for :name', 'نقش :name', 'دور :name');
    $more = __('nabuxui::ui.more');
    $addPeople = $t('Add people', 'افزودن افراد', 'إضافة أشخاص');
    $summary = count($chosen) ? $label.': '.implode(', ', array_map(fn ($m) => $m['name'], array_slice($chosen, 0, 3))).(count($chosen) > 3 ? ', '.str_replace(':count', (string) (count($chosen) - 3), $more) : '') : $addPeople;
@endphp
<div style="display: contents" x-data="nxMemberSelector(@js($members), @js($selected), @js($model), {{ (int) $max }}, @js($locale))">
    <button type="button" {{ $attributes->whereDoesntStartWith('wire:model')->class('nx-member-selector-trigger') }} x-ref="trigger" popovertarget="{{ $id }}" aria-controls="{{ $id }}"
        aria-expanded="false" x-bind:aria-expanded="open ? 'true' : 'false'" aria-label="{{ $summary }}" x-bind:aria-label="summary(@js($label), @js($more), @js($addPeople))">
        <span class="nx-member-selector-stack" x-ref="stack" aria-hidden="true">
            <template x-for="member in shown" :key="member.id">
                <span class="nx-avatar" x-bind:data-key="member.id" x-bind:data-new="member.id === fresh ? '' : null" x-on:animationend="fresh = fresh === member.id ? null : fresh">
                    <template x-if="member.avatar"><img class="nx-avatar-image" x-bind:src="member.avatar" alt=""></template>
                    <template x-if="! member.avatar"><span x-text="initials(member.name)"></span></template>
                </span>
            </template>
            <span class="nx-avatar nx-avatar-more" data-key="__more" x-show="rest > 0" @if (! $restCount) style="display: none" @endif>+<x-nx::number x-ref="more" :value="$restCount" :locale="$locale" :reveal="false" wire:ignore /></span>
            <span class="nx-member-selector-add" data-key="__add">{{ NabuXUI::icon('plus') }}</span>
        </span>
    </button>
    <div class="nx-member-selector" id="{{ $id }}" x-ref="panel" popover="auto" wire:ignore.self role="dialog" aria-label="{{ $label }}">
        <div class="nx-member-selector-search">
            {{ NabuXUI::icon('search') }}
            <input type="search" class="nx-member-selector-input" x-ref="search" x-model="query" aria-label="{{ __('nabuxui::ui.search') }}" aria-controls="{{ $id }}-list"
                placeholder="{{ $placeholder ?? $t('Search people…', 'جست‌وجوی افراد…', 'ابحث عن أشخاص…') }}">
        </div>
        <ul class="nx-member-selector-list" id="{{ $id }}-list" aria-label="{{ $label }}">
            @foreach ($members as $member)
                @php $checked = in_array($member['id'], $selected, true); $role = $roles[$member['id']] ?? $defaultRole; @endphp
                <li class="nx-member-selector-row" @if ($checked) data-selected @endif x-bind:data-selected="isSelected(@js($member['id'])) ? '' : null"
                    x-show="matches(@js($member['name'].' '.($member['email'] ?? '')))">
                    <label class="nx-member-selector-choice">
                        <input type="checkbox" class="nx-checkbox" name="{{ $name }}[]" value="{{ $member['id'] }}" {{ $wire }} @checked($checked)
                            x-on:change="changed(@js($member['id']), $event.target.checked)">
                        <x-nx::avatar :name="$member['name']" :src="$member['avatar']" aria-hidden="true" />
                        <span class="nx-member-selector-who">
                            <span class="nx-member-selector-name">{{ $member['name'] }}</span>
                            @if ($member['email'])<span class="nx-member-selector-email">{{ $member['email'] }}</span>@endif
                        </span>
                    </label>
                    <select class="nx-select nx-member-selector-role" data-size="sm" name="{{ $name }}_roles[{{ $member['id'] }}]" aria-label="{{ str_replace(':name', $member['name'], $roleFor) }}"
                        @if ($rolesModel) wire:model="{{ $rolesModel }}.{{ $member['id'] }}" @endif
                        @disabled(! $checked) x-bind:disabled="! isSelected(@js($member['id']))" x-bind:tabindex="isSelected(@js($member['id'])) ? 0 : -1">
                        @foreach ($options as $option)
                            <option value="{{ $option['value'] }}" @selected((string) $role === (string) $option['value'])>{{ $option['label'] }}</option>
                        @endforeach
                    </select>
                </li>
            @endforeach
        </ul>
        <p class="nx-member-selector-empty" x-show="empty" style="display: none">{{ $emptyText ?? __('nabuxui::ui.noResults') }}</p>
    </div>
</div>
