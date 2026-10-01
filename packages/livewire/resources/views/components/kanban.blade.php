{{--
    <x-nx::kanban :columns="[
        ['id' => 'todo', 'title' => 'Backlog', 'tone' => 'info', 'cards' => [
            ['id' => 'c1', 'title' => 'Wire the settings page', 'meta' => 'FR-104 · 2 comments', 'tone' => 'info',
             'actions' => [['label' => 'Open', 'icon' => 'edit', 'href' => '#'], ['label' => 'Delete', 'icon' => 'trash', 'danger' => true]]],
        ]],
        ['id' => 'doing', 'title' => 'This week', 'tone' => 'warning', 'cards' => []],
    ]" move-action="moveCard" add-action="addCard" height="34rem" />

    Cards move by native HTML5 drag & drop (FLIP glide, breathing placeholder)
    or from the "Move to …" items of each card's three-dot menu, so reordering
    also works from the keyboard. Column counts roll as digits. The quick-add
    composer grows in place.

    State: the columns you pass are the truth. `move-action` / `add-action`
    name Livewire methods (moveCard($card, $from, $to, $index) /
    addCard($column, $title)) called after the optimistic move — re-render the
    board sorted; cards keep a wire:key so they glide through the morph. Without
    actions everything stays client-side and nx-move / nx-add / nx-card-action
    events bubble from the component for you to handle.
--}}
@props(['columns' => [], 'quickAdd' => true, 'moveAction' => null, 'addAction' => null, 'height' => null, 'label' => null, 'locale' => null])
@php
    use NabuXUI\NabuXUI;
    // Kebab-case boolean props arrive as strings; read them like Blade does.
    $quickAdd = ! in_array(strtolower((string) $quickAdd), ['0', 'false', 'no', 'off', ''], true);
    $locale = str_replace('_', '-', $locale ?? app()->getLocale());
    $lang = substr($locale, 0, 2);
    // The board's own words live in the core i18n table (resources/lang, generated from it).
    $say = fn (string $key) => __('nabuxui::ui.'.$key, [], $lang);
    $labels = [
        'board' => $label ?? $say('kanbanBoard'),
        'addCard' => $say('kanbanAddCard'),
        'add' => $say('kanbanAdd'),
        'empty' => $say('kanbanEmpty'),
        'moveTo' => $say('kanbanMoveTo'),
        'menuFor' => $say('kanbanCardMenu'),
        'cards' => $say('kanbanCards'),
        'movedTo' => $say('kanbanMovedTo'),
    ];
    $toneOf = fn ($tone) => in_array($tone, ['accent', 'success', 'warning', 'danger', 'info', 'gold'], true) ? $tone : null;
    $columns = array_values(array_map(fn ($column) => [
        'id' => (string) $column['id'],
        'title' => (string) $column['title'],
        'tone' => $toneOf($column['tone'] ?? null),
        'cards' => array_values(array_map(fn ($card) => [
            'id' => (string) $card['id'],
            'title' => (string) $card['title'],
            'meta' => isset($card['meta']) ? (string) $card['meta'] : null,
            'tone' => $toneOf($card['tone'] ?? null),
            'assignee' => isset($card['assignee']['name']) ? ['name' => (string) $card['assignee']['name'], 'src' => $card['assignee']['src'] ?? null] : null,
            'actions' => array_values(array_map(fn ($action) => [
                'label' => (string) $action['label'],
                'icon' => isset($action['icon']) && NabuXUI::hasIcon($action['icon']) ? $action['icon'] : null,
                'href' => $action['href'] ?? null,
                'danger' => ! empty($action['danger']),
            ], $card['actions'] ?? [])),
        ], $column['cards'] ?? [])),
    ], $columns));
    $boardId = NabuXUI::id('nx-kanban');
    $listenerAttrs = $attributes->whereStartsWith(['x-on:', '@', 'wire:']);
    $rest = $attributes->whereDoesntStartWith(['x-on:', '@', 'wire:']);
@endphp
<section {{ $rest->class('nx-kanban')->merge([
    'aria-label' => $labels['board'],
    'data-nx-reveal' => '',
    'style' => $height ? "--nx-kanban-height: {$height}" : null,
]) }} {{ $listenerAttrs }} x-data="nxKanban(@js(['columns' => $columns, 'labels' => $labels, 'locale' => $locale, 'moveAction' => $moveAction, 'addAction' => $addAction, 'quickAdd' => $quickAdd]))">
    <div class="nx-kanban-board" x-on:dragstart="dragStart($event)" x-on:dragend="dragEnd($event)">
        @foreach ($columns as $column)
            @php
                $columnId = $column['id'];
                $colKey = "{$boardId}-{$columnId}";
            @endphp
            <div class="nx-kanban-column" data-column="{{ $columnId }}" @if ($column['tone']) data-tone="{{ $column['tone'] }}" @endif
                wire:key="{{ $colKey }}" x-bind:data-dropping="dropping === @js($columnId)">
                <header class="nx-kanban-column-head">
                    <span class="nx-kanban-column-dot" aria-hidden="true"></span>
                    <h3 class="nx-kanban-column-title" id="{{ $colKey }}-title">{{ $column['title'] }}</h3>
                    <span class="nx-kanban-column-count" aria-hidden="true">
                        <x-nx::number :value="count($column['cards'])" :reveal="false" :locale="$locale" wire:ignore />
                    </span>
                    {{-- The spoken count is server-rendered so it reads without JS; Alpine keeps it honest. --}}
                    <span class="nx-visually-hidden" x-text="countText(@js($columnId))">{{ str_replace(':count', NabuXUI::formatNumber(count($column['cards']), 0, $locale), $labels['cards']) }}</span>
                </header>
                <ul class="nx-kanban-list" aria-labelledby="{{ $colKey }}-title"
                    x-on:dragover="dragOver($event)" x-on:drop="drop($event)" x-on:dragleave="dragLeave($event)">
                    @foreach ($column['cards'] as $i => $card)
                        @php
                            $cardId = $card['id'];
                            $menuId = "{$colKey}-menu-{$cardId}";
                            $moveTargets = array_filter($columns, fn ($c) => $c['id'] !== $columnId);
                        @endphp
                        <li class="nx-kanban-card" draggable="true" data-key="{{ $cardId }}" @if ($card['tone']) data-tone="{{ $card['tone'] }}" @endif
                            wire:key="{{ $menuId }}" style="--nx-i: {{ $i }}"
                            x-bind:data-dragging="dragging === @js($cardId)">
                            @if ($card['tone'])<span class="nx-kanban-card-dot" aria-hidden="true"></span>@endif
                            <div class="nx-kanban-card-main">
                                <p class="nx-kanban-card-title">{{ $card['title'] }}</p>
                                @if ($card['meta'])<p class="nx-kanban-card-meta">{{ $card['meta'] }}</p>@endif
                            </div>
                            @if ($card['assignee'])
                                <x-nx::avatar :name="$card['assignee']['name']" :src="$card['assignee']['src']" size="sm" />
                            @endif
                            @if (count($card['actions']) || count($moveTargets))
                                <button type="button" class="nx-kanban-card-menu" data-menu-for="{{ $cardId }}" popovertarget="{{ $menuId }}"
                                    aria-controls="{{ $menuId }}" aria-haspopup="menu" aria-expanded="false"
                                    x-bind:aria-expanded="menu === @js($cardId) ? 'true' : 'false'"
                                    x-on:click="menuClick(@js($cardId))"
                                    aria-label="{{ str_replace(':name', $card['title'], $labels['menuFor']) }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75" stroke-linecap="round" aria-hidden="true"><path d="M12 5.25h.01M12 12h.01M12 18.75h.01"/></svg>
                                </button>
                                <div class="nx-kanban-menu" id="{{ $menuId }}" role="menu" wire:ignore.self
                                    x-on:toggle="menuToggle(@js($cardId), $event)" x-on:keydown="menuKey($event)">
                                    @foreach ($card['actions'] as $action)
                                        <button type="button" class="nx-kanban-menu-choice" role="menuitem" @if ($action['danger']) data-danger @endif
                                            x-on:click="cardAction(@js($cardId), @js((string) $action['label']), $event)">
                                            @if ($action['icon']){{ NabuXUI::icon($action['icon']) }}@endif
                                            <span>{{ $action['label'] }}</span>
                                        </button>
                                    @endforeach
                                    @if (count($card['actions']) && count($moveTargets))
                                        <hr class="nx-kanban-menu-sep" role="separator" />
                                    @endif
                                    @foreach ($moveTargets as $target)
                                        <button type="button" class="nx-kanban-menu-choice" role="menuitem"
                                            x-on:click="menuMove(@js($cardId), @js($columnId), @js($target['id']), $event)">
                                            {{ NabuXUI::icon('chevron-right') }}
                                            <span>{{ str_replace(':column', $target['title'], $labels['moveTo']) }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </li>
                    @endforeach
                    {{-- The drop indicator: one per column, moved to the pointer's index while a drag hovers. --}}
                    <li class="nx-kanban-placeholder" wire:key="{{ $colKey }}-ph" aria-hidden="true" hidden
                        x-bind:hidden="dropping === @js($columnId) ? null : ''">
                        <span class="nx-kanban-placeholder-dot"></span>
                        <span class="nx-kanban-placeholder-bar"></span>
                    </li>
                    @if (count($column['cards']) === 0)
                        <li class="nx-kanban-empty" wire:key="{{ $colKey }}-empty" x-show="dropping !== @js($columnId)">{{ $labels['empty'] }}</li>
                    @endif
                </ul>
                @if ($quickAdd)
                    <div class="nx-kanban-add" wire:key="{{ $colKey }}-add">
                        <button type="button" class="nx-kanban-add-open" aria-expanded="false"
                            aria-controls="{{ $colKey }}-form" x-bind:aria-expanded="adding === @js($columnId) ? 'true' : 'false'"
                            x-on:click="toggleAdd(@js($columnId))">
                            {{ NabuXUI::icon('plus') }}
                            <span>{{ $labels['addCard'] }}</span>
                        </button>
                        <div class="nx-kanban-add-form" id="{{ $colKey }}-form" wire:ignore
                            x-bind:data-open="adding === @js($columnId) ? '' : null">
                            <form class="nx-kanban-add-body" x-on:submit.prevent="submitAdd($event, @js($columnId))">
                                <input class="nx-kanban-add-input" name="title" type="text" autocomplete="off"
                                    aria-label="{{ $labels['addCard'] }}" placeholder="{{ $labels['addCard'] }}" />
                                <button type="submit" class="nx-kanban-add-submit">{{ $labels['add'] }}</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
    <p class="nx-visually-hidden" role="status" x-text="announce"></p>
</section>
