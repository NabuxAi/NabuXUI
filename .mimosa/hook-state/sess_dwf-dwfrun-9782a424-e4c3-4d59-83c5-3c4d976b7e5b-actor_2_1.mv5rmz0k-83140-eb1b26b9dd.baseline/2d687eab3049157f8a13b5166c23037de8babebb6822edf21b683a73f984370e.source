{{--
    A vertical deck: drag the top card down, click it or press the button, and it tucks in behind the others.

    <x-nx::cycle-stack :items="[
        'deploy' => ['icon' => 'zap', 'title' => 'Deploy finished', 'description' => '…', 'meta' => '2 min ago', 'tone' => 'cyan'],
        'review' => ['title' => 'Review'],
    ]">
        <x-slot:review>…any content replaces the built-in face…</x-slot:review>
    </x-nx::cycle-stack>

    Item keys: icon, title, description, meta, tone. wire:model="card" keeps the front card's index in a Livewire property.
--}}
@props(['items' => [], 'index' => 0, 'nextLabel' => null])
@php
    $slots = $__laravel_slots ?? [];
    $slotFor = fn ($key) => $slots[$key] ?? $slots[\Illuminate\Support\Str::camel((string) $key)] ?? null;
    $model = \NabuXUI\NabuXUI::model($attributes);
    $named = ['lapis', 'violet', 'cyan', 'gold'];
    $count = max(count($items), 1);
    $start = (((int) $index % $count) + $count) % $count;
    $locale = str_replace('_', '-', app()->getLocale());
    $num = fn ($n) => \NabuXUI\NabuXUI::formatNumber($n);
@endphp
<div {{ $attributes->whereDoesntStartWith('wire:model')->class('nx-cycle') }}
    @if ($model) x-data="nxCycleStack(@entangle($model), {{ $count }}, @js($locale))" @else x-data="nxCycleStack({{ $start }}, {{ $count }}, @js($locale))" @endif>
    <div class="nx-cycle-deck">
        @foreach ($items as $key => $item)
            @php
                $i = $loop->index;
                $depth = (($i - $start) % $count + $count) % $count;
                $card = is_array($item) ? $item : ['title' => (string) $item];
                $tone = $card['tone'] ?? null;
                $content = $slotFor($key);
            @endphp
            <article class="nx-cycle-card" data-card="{{ $i }}" style="--_d: {{ $depth }}" @if ($depth === 0) data-state="top" @else aria-hidden="true" inert @endif
                x-bind:data-state="state({{ $i }})" x-bind:style="{ '--_d': depth({{ $i }}) }" x-bind:aria-hidden="isTop({{ $i }}) ? null : 'true'" x-bind:inert="! isTop({{ $i }})">
                @if ($content)
                    {{ $content }}
                @else
                    <div class="nx-cycle-face" @if (in_array($tone, $named, true)) data-tone="{{ $tone }}" @elseif ($tone) style="--nx-tone: {{ $tone }}" @endif>
                        @if (! empty($card['icon']))<span class="nx-cycle-icon">{{ \NabuXUI\NabuXUI::icon($card['icon']) }}</span>@endif
                        <h3 class="nx-cycle-title">{{ $card['title'] ?? '' }}</h3>
                        @if (! empty($card['description']))<p class="nx-cycle-description">{{ $card['description'] }}</p>@endif
                        @if (! empty($card['meta']))<span class="nx-cycle-meta">{{ $card['meta'] }}</span>@endif
                    </div>
                @endif
            </article>
        @endforeach
    </div>
    <div class="nx-cycle-controls">
        <span class="nx-cycle-count" aria-live="polite"><span x-text="num(front() + 1)">{{ $num($start + 1) }}</span> / {{ $num($count) }}</span>
        <x-nx::button variant="secondary" shape="pill" icon="layers" :disabled="count($items) < 2" x-on:click="cycle()">{{ $nextLabel ?? __('nabuxui::ui.next') }}</x-nx::button>
    </div>
</div>
