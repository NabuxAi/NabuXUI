{{--
    <x-nx::sortable-list :items="$tasks" action="reorder" label="Release checklist" />
        $tasks = [['id' => 'a', 'label' => 'Design review', 'description' => 'Figma · 3 comments', 'meta' => 'Tue'], …]

    Drag the handles (the others step aside, everything springs into place on
    drop) or reorder from the keyboard: Space picks up, the arrows move, Space
    drops, Escape cancels — each step announced. The new order of ids goes to
    the Livewire method `action` and out as `nx-sort` (detail: the ids).
--}}
@props([
    'items' => [],
    'action' => null,
    'label' => null,
    'handleLabel' => 'Reorder {name}',
    'instructions' => 'Press Space to pick up, the arrow keys to move, Space again to drop, Escape to cancel.',
    'messages' => [],
])
@php
    use NabuXUI\NabuXUI;
    $id = NabuXUI::id('nx-sortable');
    $options = ['action' => $action, 'messages' => (object) $messages, 'locale' => app()->getLocale()];
@endphp
<div {{ $attributes->class('nx-sortable') }} x-data="nxSortable(@js($options))" wire:ignore>
    <ol class="nx-sortable-list" @if ($label) aria-label="{{ $label }}" @endif>
        @foreach ($items as $item)
            @php
                $key = (string) ($item['id'] ?? $loop->index);
                $name = (string) ($item['label'] ?? $key);
            @endphp
            <li class="nx-sortable-item" data-nx-sort-key="{{ $key }}" data-nx-sort-label="{{ $name }}" wire:key="{{ $id }}-{{ $key }}">
                <button type="button" class="nx-sortable-handle" data-nx-sort-handle aria-pressed="false"
                    aria-label="{{ str_replace('{name}', $name, $handleLabel) }}" aria-describedby="{{ $id }}-hint">
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="9" cy="6" r="1.6"/><circle cx="15" cy="6" r="1.6"/><circle cx="9" cy="12" r="1.6"/><circle cx="15" cy="12" r="1.6"/><circle cx="9" cy="18" r="1.6"/><circle cx="15" cy="18" r="1.6"/></svg>
                </button>
                <div class="nx-sortable-body" style="display: flex; align-items: center; gap: var(--nx-space-3)">
                    @if (! empty($item['icon']))<span style="display: inline-flex; color: var(--nx-text-muted)">{{ NabuXUI::icon($item['icon']) }}</span>@endif
                    <span style="display: grid; gap: 0.1rem; flex: 1; min-inline-size: 0">
                        <span style="font-weight: 600">{{ $name }}</span>
                        @if (! empty($item['description']))<span style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $item['description'] }}</span>@endif
                    </span>
                    @if (! empty($item['meta']))<span style="color: var(--nx-text-subtle); font-size: var(--nx-text-sm); white-space: nowrap">{{ $item['meta'] }}</span>@endif
                </div>
            </li>
        @endforeach
    </ol>
    <p class="nx-visually-hidden" id="{{ $id }}-hint">{{ $instructions }}</p>
    <span class="nx-visually-hidden" data-nx-sort-live aria-live="assertive"></span>
</div>
