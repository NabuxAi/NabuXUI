{{--
    <x-nx::command :groups="[
        ['label' => 'Pages', 'items' => [['id' => 'home', 'label' => 'Home', 'icon' => 'home', 'href' => '/']]],
        ['label' => 'Actions', 'items' => [['id' => 'new', 'label' => 'New agent', 'icon' => 'plus', 'event' => 'create-agent']]],
    ]" />
    Items navigate (with wire:navigate when Livewire is there) or dispatch a Livewire event. ⌘K / Ctrl+K opens it.
--}}
@props(['groups' => [], 'hotkey' => 'mod+k', 'placeholder' => null, 'emptyText' => null])
@php $id = \NabuXUI\NabuXUI::id('nx-cmd'); @endphp
<div style="display: contents" x-data="nxCommand(@js($groups), @js($hotkey))" @nx-command.window="open = ! open">
    <dialog x-ref="dialog" {{ $attributes->class('nx-dialog nx-command') }} @click="if ($event.target === $el) open = false">
        <div class="nx-command-search">
            {{ \NabuXUI\NabuXUI::icon('search') }}
            <input x-ref="input" class="nx-command-input" role="combobox" aria-expanded="true" aria-autocomplete="list" aria-controls="{{ $id }}-list"
                x-bind:aria-activedescendant="flat.length ? '{{ $id }}-opt-' + active : null" aria-label="{{ __('nabuxui::ui.search') }}"
                placeholder="{{ $placeholder ?? __('nabuxui::ui.searchPlaceholder') }}" x-model="query"
                @keydown.arrow-down.prevent="move(1)" @keydown.arrow-up.prevent="move(-1)" @keydown.enter.prevent="choose()">
            <kbd class="nx-kbd">Esc</kbd>
        </div>
        <div class="nx-command-list" x-ref="list" id="{{ $id }}-list" role="listbox" aria-label="{{ __('nabuxui::ui.search') }}">
            <span class="nx-indicator" aria-hidden="true"></span>
            <p class="nx-command-empty" x-show="! flat.length" x-cloak>{{ $emptyText ?? __('nabuxui::ui.noResults') }}</p>
            <template x-for="(group, g) in filtered" :key="group.label">
                <div class="nx-command-group" role="group" x-bind:aria-label="group.label">
                    <div class="nx-command-group-label" x-text="group.label"></div>
                    <template x-for="item in group.items" :key="item.id">
                        <div class="nx-command-item" role="option" x-bind:id="'{{ $id }}-opt-' + indexOf(item)" x-bind:data-index="indexOf(item)"
                            x-bind:aria-selected="indexOf(item) === active ? 'true' : 'false'" x-bind:aria-disabled="item.disabled ? 'true' : null"
                            x-bind:style="`--nx-i: ${indexOf(item)}`" @pointermove="active = indexOf(item)" @click="choose(item)">
                            <span x-html="icon(item.icon)" style="display: contents"></span>
                            <span x-text="item.label"></span>
                            <template x-if="item.hint"><span class="nx-command-item-hint" x-text="item.hint"></span></template>
                            <template x-if="item.shortcut"><kbd class="nx-kbd" x-text="item.shortcut"></kbd></template>
                        </div>
                    </template>
                </div>
            </template>
        </div>
        <footer class="nx-command-footer">
            <span><kbd class="nx-kbd">↑</kbd><kbd class="nx-kbd">↓</kbd> {{ __('nabuxui::ui.navigate') }}</span>
            <span><kbd class="nx-kbd">↵</kbd> {{ __('nabuxui::ui.select') }}</span>
            <span><kbd class="nx-kbd">Esc</kbd> {{ __('nabuxui::ui.toClose') }}</span>
        </footer>
    </dialog>
</div>
