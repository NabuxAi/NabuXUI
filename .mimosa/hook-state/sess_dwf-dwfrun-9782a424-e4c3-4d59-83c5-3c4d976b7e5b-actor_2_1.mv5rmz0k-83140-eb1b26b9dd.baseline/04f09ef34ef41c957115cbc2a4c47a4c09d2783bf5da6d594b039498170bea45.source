{{--
    <x-nx::stack-menu title="Settings" :items="[
        ['id' => 'appearance', 'label' => 'Appearance', 'icon' => 'sun', 'children' => [
            ['id' => 'theme', 'label' => 'Theme', 'children' => [['id' => 'dark', 'label' => 'Dark']]],
        ]],
        ['id' => 'help', 'label' => 'Help', 'icon' => 'info', 'href' => '/help'],
    ]" x-on:nx-select="$wire.ping($event.detail.label)">
        <x-slot:trigger><x-nx::button icon="sliders">Settings</x-nx::button></x-slot:trigger>
    </x-nx::stack-menu>

    Items with `children` push a new level (Escape, ←, or Back pops it); the
    search finds items at every depth. Choosing any other item dispatches
    `nx-select` ({ id, label, params }), then follows its `href` or dispatches
    its Livewire `event`. Items: id, label, icon, shortcut, description, tone,
    disabled, keywords, href, event, params, children.
--}}
@props(['items' => [], 'title' => null, 'side' => 'bottom', 'align' => 'start', 'placeholder' => null, 'emptyText' => null, 'label' => null])
@php
    use NabuXUI\NabuXUI;
    $id = NabuXUI::id('nx-stack');
    $title ??= __('nabuxui::ui.menu');
    $back = __('nabuxui::ui.back');
    $clean = function (array $list) use (&$clean) {
        return array_values(array_map(function ($item) use ($clean) {
            $item['id'] = (string) $item['id'];
            if (! empty($item['children'])) {
                $item['children'] = $clean($item['children']);
            }

            return $item;
        }, $list));
    };
@endphp
<div style="display: contents" x-data="nxStackMenu(@js($clean($items)), @js($title), @js($side === 'top' ? 'top' : 'bottom'), @js($align))">
    <span x-ref="trigger" style="display: contents" wire:ignore>{{ $trigger }}</span>
    <div x-ref="panel" id="{{ $id }}" wire:ignore.self {{ $attributes->class('nx-stack-menu')->merge(['role' => 'dialog', 'aria-label' => $label ?? $title, 'popover' => 'auto']) }} x-on:keydown="onKey($event)">
        <div class="nx-stack-menu-search">
            {{ NabuXUI::icon('search') }}
            <input type="search" class="nx-stack-menu-input" x-ref="input" x-model="query" aria-label="{{ __('nabuxui::ui.search') }}" placeholder="{{ $placeholder ?? __('nabuxui::ui.search').'…' }}">
        </div>
        <div class="nx-stack-menu-viewport" x-ref="viewport" wire:ignore.self>
            <div class="nx-stack-menu-measure" x-ref="measure">
                <template x-if="searching">
                    <div class="nx-stack-menu-results" role="menu" aria-label="{{ __('nabuxui::ui.search') }}" data-view>
                        <template x-for="(result, i) in results" :key="result.item.id + '@' + i">
                            <button type="button" role="menuitem" class="nx-stack-menu-item" x-bind:data-item="result.item.id" x-bind:data-tone="result.item.tone || null"
                                x-bind:tabindex="i === 0 ? 0 : -1" x-bind:aria-haspopup="result.item.children && result.item.children.length ? 'menu' : null"
                                x-bind:aria-disabled="result.item.disabled ? 'true' : null" x-on:click="select(result.item, result.trail)">
                                <span style="display: contents" x-html="icon(result.item.icon)"></span>
                                <span class="nx-stack-menu-label">
                                    <span x-text="result.item.label"></span>
                                    <span class="nx-stack-menu-trail" x-show="result.trail.length"><template x-for="node in result.trail" :key="node.id"><span x-text="node.label"></span></template></span>
                                </span>
                                <template x-if="result.item.shortcut"><kbd class="nx-kbd" x-text="result.item.shortcut"></kbd></template>
                                <template x-if="result.item.children && result.item.children.length"><span class="nx-stack-menu-more" aria-hidden="true">{{ NabuXUI::icon('chevron-right') }}</span></template>
                            </button>
                        </template>
                        <p class="nx-stack-menu-empty" x-show="! results.length">{{ $emptyText ?? __('nabuxui::ui.noResults') }}</p>
                    </div>
                </template>
                <template x-for="level in levels" :key="level.key">
                    <section class="nx-stack-menu-level" x-show="! searching" x-bind:data-state="level.state" x-bind:data-root="level.depth === 0 ? '' : null" x-bind:data-view="level.state === 'active' && ! searching ? '' : null"
                        x-bind:aria-hidden="level.state === 'active' ? null : 'true'" x-bind:style="{ '--_depth': level.depth }" x-on:transitionend.self="ended($event, level)">
                        <template x-if="level.depth > 0">
                            <button type="button" class="nx-stack-menu-back" x-bind:tabindex="level.state === 'active' ? 0 : -1" x-bind:aria-label="@js($back) + ', ' + level.title" x-on:click="pop()">
                                {{ NabuXUI::icon('chevron-left') }}<span x-text="level.title"></span>
                            </button>
                        </template>
                        <div class="nx-stack-menu-list" role="menu" x-bind:aria-label="level.title">
                            <template x-for="(item, i) in level.items" :key="item.id">
                                <button type="button" role="menuitem" class="nx-stack-menu-item" x-bind:data-item="item.id" x-bind:data-tone="item.tone || null"
                                    x-bind:tabindex="level.state === 'active' && i === 0 ? 0 : -1" x-bind:aria-haspopup="item.children && item.children.length ? 'menu' : null"
                                    x-bind:aria-disabled="item.disabled ? 'true' : null" x-on:click="select(item)">
                                    <span style="display: contents" x-html="icon(item.icon)"></span>
                                    <span class="nx-stack-menu-label">
                                        <span x-text="item.label"></span>
                                        <template x-if="item.description"><span class="nx-stack-menu-hint" x-text="item.description"></span></template>
                                    </span>
                                    <template x-if="item.shortcut"><kbd class="nx-kbd" x-text="item.shortcut"></kbd></template>
                                    <template x-if="item.children && item.children.length"><span class="nx-stack-menu-more" aria-hidden="true">{{ NabuXUI::icon('chevron-right') }}</span></template>
                                </button>
                            </template>
                        </div>
                    </section>
                </template>
            </div>
        </div>
    </div>
</div>
