{{--
    <x-nx::swipe-actions
        :start="[['id' => 'read', 'label' => 'Read', 'icon' => 'check', 'tone' => 'accent', 'primary' => true, 'click' => 'markRead(4)']]"
        :end="[['id' => 'archive', 'label' => 'Archive', 'icon' => 'folder'], ['id' => 'delete', 'label' => 'Delete', 'icon' => 'trash', 'tone' => 'danger', 'primary' => true, 'click' => 'remove(4)']]">
        …the row…
    </x-nx::swipe-actions>

    Swipe the row toward the inline end to reveal the `start` actions, toward
    the inline start for the `end` ones; a long swipe fires the side's primary
    action. Every action is a real button: Tab reaches it (the row opens to
    show it) and Escape closes. Each press runs its `click` (a wire:click
    expression) and dispatches `nx-action` with the action's id.
--}}
@props([
    'start' => [],
    'end' => [],
    'fullSwipe' => 0.62,
])
@php
    use NabuXUI\NabuXUI;
    $sides = ['start' => (array) $start, 'end' => (array) $end];
@endphp
<div {{ $attributes->class('nx-swipe-row') }} x-data="nxSwipeRow(@js(['fullSwipe' => $fullSwipe === false ? false : (float) $fullSwipe]))" wire:ignore.self>
    @foreach ($sides as $side => $actions)
        @if (count($actions))
            <div class="nx-swipe-actions" data-side="{{ $side }}">
                @foreach ($actions as $action)
                    <button type="button" class="nx-swipe-action"
                        @if (($action['tone'] ?? 'neutral') !== 'neutral') data-tone="{{ $action['tone'] }}" @endif
                        @if (! empty($action['primary'])) data-primary @endif
                        @if (! empty($action['click'])) wire:click="{{ $action['click'] }}" @endif
                        x-on:click="$dispatch('nx-action', @js($action['id'] ?? $action['label'] ?? ''))">
                        @if (! empty($action['icon'])){{ NabuXUI::icon($action['icon']) }}@endif
                        <span>{{ $action['label'] ?? '' }}</span>
                    </button>
                @endforeach
            </div>
        @endif
    @endforeach
    <div class="nx-swipe-content">{{ $slot }}</div>
</div>
