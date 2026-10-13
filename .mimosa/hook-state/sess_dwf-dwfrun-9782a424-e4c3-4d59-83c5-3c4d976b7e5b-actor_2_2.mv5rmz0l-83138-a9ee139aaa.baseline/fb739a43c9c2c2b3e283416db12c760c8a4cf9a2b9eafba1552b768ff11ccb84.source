{{--
    <x-nx::todo :groups="[
        ['id' => 'today', 'title' => 'Today', 'tone' => 'success', 'tasks' => [
            ['id' => 't1', 'title' => 'Water the plants', 'done' => true],
            ['id' => 't2', 'title' => 'Send the invoice'],
        ]],
        ['id' => 'later', 'title' => 'Later', 'tone' => 'info', 'tasks' => []],
    ]" heading="My day" toggle-action="toggleTask" remove-action="removeTask"
        add-action="addTask" move-action="moveTask" />

    A task list: grouped tasks, a spring tick (native checkbox), drag-to-reorder
    with a breathing drop pill (or Alt+↑/Alt+↓ from the keyboard), a rolling
    done/total counter and a progress bar, and a quick-add composer that puts the
    new task in the group you pick.

    State: the groups you pass are the truth. `toggle-action` / `remove-action` /
    `add-action` / `move-action` name Livewire methods (toggleTask($task, $done) /
    removeTask($task) / addTask($group, $title) / moveTask($task, $from, $to,
    $index)) called after the optimistic change — re-render the groups; tasks keep
    a wire:key so they glide through the morph. Without actions everything stays
    client-side and nx-toggle / nx-remove / nx-add / nx-move events bubble from
    the component for you to handle.
--}}
@props(['groups' => [], 'quickAdd' => true, 'progress' => true, 'heading' => null, 'toggleAction' => null, 'removeAction' => null, 'addAction' => null, 'moveAction' => null, 'label' => null, 'locale' => null])
@php
    use NabuXUI\NabuXUI;
    // Kebab-case boolean props arrive as strings; read them like Blade does.
    $quickAdd = ! in_array(strtolower((string) $quickAdd), ['0', 'false', 'no', 'off', ''], true);
    $progress = ! in_array(strtolower((string) $progress), ['0', 'false', 'no', 'off', ''], true);
    $locale = str_replace('_', '-', $locale ?? app()->getLocale());
    $lang = substr($locale, 0, 2);
    // The list's own words live in the core i18n table (resources/lang, generated from it).
    $say = fn (string $key) => __('nabuxui::ui.'.$key, [], $lang);
    $labels = [
        'list' => $label ?? $say('todoList'),
        'addTask' => $say('todoAddTask'),
        'add' => $say('todoAdd'),
        'empty' => $say('todoEmpty'),
        'group' => $say('todoGroup'),
        'tasks' => $say('todoTasks'),
        'progress' => $say('todoProgress'),
        'remove' => $say('todoRemove'),
        'checked' => $say('todoChecked'),
        'unchecked' => $say('todoUnchecked'),
        'moved' => $say('todoMoved'),
    ];
    $toneOf = fn ($tone) => in_array($tone, ['accent', 'success', 'warning', 'danger', 'info', 'gold'], true) ? $tone : null;
    $groups = array_values(array_map(fn ($group) => [
        'id' => (string) $group['id'],
        'title' => (string) $group['title'],
        'tone' => $toneOf($group['tone'] ?? null),
        'tasks' => array_values(array_map(fn ($task) => [
            'id' => (string) $task['id'],
            'title' => (string) $task['title'],
            'done' => ! empty($task['done']),
        ], $group['tasks'] ?? [])),
    ], $groups));
    $allTasks = array_merge(...array_map(fn ($group) => $group['tasks'], $groups) ?: [[]]);
    $doneCount = count(array_filter($allTasks, fn ($task) => $task['done']));
    $totalCount = count($allTasks);
    $ratio = $totalCount > 0 ? $doneCount / $totalCount : 0;
    $percentSign = in_array($lang, ['fa', 'ar'], true) ? '٪' : '%';
    $percentValue = (int) round($ratio * 100);
    $percentParts = NabuXUI::numberParts($percentValue, 0, $locale);
    $percentDigits = NabuXUI::digits($locale);
    $percentText = NabuXUI::formatNumber($percentValue, 0, $locale).$percentSign;
    $spokenProgress = str_replace(
        [':done', ':total'],
        [NabuXUI::formatNumber($doneCount, 0, $locale), NabuXUI::formatNumber($totalCount, 0, $locale)],
        $labels['progress'],
    );
    $boardId = NabuXUI::id('nx-todo');
    $listenerAttrs = $attributes->whereStartsWith(['x-on:', '@', 'wire:']);
    $rest = $attributes->whereDoesntStartWith(['x-on:', '@', 'wire:']);
@endphp
<section {{ $rest->class('nx-todo')->merge([
    'aria-label' => $labels['list'],
    'data-nx-reveal' => '',
    'data-complete' => $totalCount > 0 && $doneCount === $totalCount ? '' : null,
]) }} {{ $listenerAttrs }} x-data="nxTodo(@js(['groups' => $groups, 'labels' => $labels, 'locale' => $locale, 'quickAdd' => $quickAdd, 'progress' => $progress, 'toggleAction' => $toggleAction, 'removeAction' => $removeAction, 'addAction' => $addAction, 'moveAction' => $moveAction]))">
    @if ($heading || $progress)
        <header class="nx-todo-head">
            @if ($heading)<h3 class="nx-todo-title">{{ $heading }}</h3>@endif
            @if ($progress)
                <span class="nx-todo-count" aria-hidden="true">
                    <x-nx::number :value="$doneCount" :reveal="false" :locale="$locale" wire:ignore />
                    <span class="nx-todo-count-sep">/</span>
                    <x-nx::number :value="$totalCount" :reveal="false" :locale="$locale" wire:ignore />
                </span>
                {{-- The spoken total is server-rendered so it reads without JS; Alpine keeps it honest. --}}
                <span class="nx-visually-hidden" x-text="progressText()">{{ $spokenProgress }}</span>
            @endif
        </header>
    @endif
    @if ($progress)
        <div class="nx-todo-progress" role="progressbar" aria-valuemin="0" aria-valuemax="100"
            aria-valuenow="{{ (int) round($ratio * 100) }}" aria-valuetext="{{ $spokenProgress }}">
            <span class="nx-todo-progress-track">
                <span class="nx-todo-progress-fill" style="--nx-todo-p: {{ round($ratio * 100, 2) }}"></span>
            </span>
            <span class="nx-todo-progress-label" aria-hidden="true">
                {{-- Hand-rolled so the shape matches renderNumber(ratio, {style: percent}): digits, then the sign. --}}
                <span class="nx-number" wire:ignore>
                    <span class="nx-visually-hidden">{{ $percentText }}</span>
                    <span class="nx-number-roll">@foreach ($percentParts as $i => $part)@if ($part['kind'] === 'digit')<span class="nx-digit" style="--d: {{ $part['value'] }}; --nx-p: {{ count($percentParts) - 1 - $i }}"><span class="nx-digit-track">@foreach ($percentDigits as $digit)<span>{{ $digit }}</span>@endforeach</span></span>@else<span class="nx-number-sep">{{ $part['char'] }}</span>@endif @endforeach<span class="nx-number-sep">{{ $percentSign }}</span></span>
                </span>
            </span>
        </div>
    @endif
    @if ($quickAdd)
        <form class="nx-todo-add" x-on:submit.prevent="submitAdd($event)" wire:ignore>
            <input class="nx-todo-add-input" name="title" type="text" autocomplete="off"
                aria-label="{{ $labels['addTask'] }}" placeholder="{{ $labels['addTask'] }}" />
            @if (count($groups) > 1)
                <select class="nx-todo-add-select" aria-label="{{ $labels['group'] }}" x-model="target">
                    @foreach ($groups as $group)
                        <option value="{{ $group['id'] }}" @selected($loop->first)>{{ $group['title'] }}</option>
                    @endforeach
                </select>
            @endif
            <button type="submit" class="nx-todo-add-submit">
                {{ NabuXUI::icon('plus') }}
                <span>{{ $labels['add'] }}</span>
            </button>
        </form>
    @endif
    <ul class="nx-todo-groups" x-on:dragstart="dragStart($event)" x-on:dragend="dragEnd($event)">
        @foreach ($groups as $group)
            @php
                $groupId = $group['id'];
                $groupKey = "{$boardId}-{$groupId}";
                $headingId = "{$groupKey}-title";
            @endphp
            <li class="nx-todo-group" data-group="{{ $groupId }}" @if ($group['tone']) data-tone="{{ $group['tone'] }}" @endif
                wire:key="{{ $groupKey }}" x-bind:data-dropping="dropping === @js($groupId)">
                <header class="nx-todo-group-head">
                    <span class="nx-todo-group-dot" aria-hidden="true"></span>
                    <h4 class="nx-todo-group-title" id="{{ $headingId }}">{{ $group['title'] }}</h4>
                    <span class="nx-todo-group-count" aria-hidden="true">
                        <x-nx::number :value="count($group['tasks'])" :reveal="false" :locale="$locale" wire:ignore />
                    </span>
                    <span class="nx-visually-hidden" x-text="countText(@js($groupId))">{{ str_replace(':count', NabuXUI::formatNumber(count($group['tasks']), 0, $locale), $labels['tasks']) }}</span>
                </header>
                <ul class="nx-todo-list" aria-labelledby="{{ $headingId }}"
                    x-on:dragover="dragOver($event)" x-on:drop="drop($event)" x-on:dragleave="dragLeave($event)">
                    @foreach ($group['tasks'] as $i => $task)
                        @php $itemId = "{$groupKey}-task-{$task['id']}"; @endphp
                        <li class="nx-todo-item" draggable="true" data-key="{{ $task['id'] }}" @if ($task['done']) data-done @endif
                            wire:key="{{ $itemId }}" style="--nx-i: {{ $i }}"
                            x-bind:data-done="done(@js($task['id'])) ? '' : null"
                            x-bind:data-dragging="dragging === @js($task['id']) ? '' : null"
                            x-on:keydown="nudge($event, @js($task['id']))">
                            <span class="nx-todo-grip" aria-hidden="true"></span>
                            <input id="{{ $itemId }}-check" type="checkbox" class="nx-todo-check" @if ($task['done']) checked @endif
                                aria-label="{{ $task['title'] }}" x-on:change="toggle(@js($task['id']), $event)" />
                            <label class="nx-todo-label" for="{{ $itemId }}-check">{{ $task['title'] }}</label>
                            <button type="button" class="nx-todo-remove"
                                aria-label="{{ str_replace(':name', $task['title'], $labels['remove']) }}"
                                x-on:click="remove(@js($task['id']))">
                                {{ NabuXUI::icon('x') }}
                            </button>
                        </li>
                    @endforeach
                    {{-- The drop indicator: one per group, moved to the pointer's index while a drag hovers. --}}
                    <li class="nx-todo-placeholder" wire:key="{{ $groupKey }}-ph" aria-hidden="true" hidden
                        x-bind:hidden="dropping === @js($groupId) ? null : ''">
                        <span class="nx-todo-placeholder-dot"></span>
                        <span class="nx-todo-placeholder-bar"></span>
                    </li>
                    @if (count($group['tasks']) === 0)
                        <li class="nx-todo-empty" wire:key="{{ $groupKey }}-empty" x-show="dropping !== @js($groupId)">{{ $labels['empty'] }}</li>
                    @endif
                </ul>
            </li>
        @endforeach
    </ul>
    <p class="nx-visually-hidden" role="status" x-text="announce"></p>
</section>
