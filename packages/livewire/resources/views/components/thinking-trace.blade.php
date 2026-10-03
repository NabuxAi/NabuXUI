{{--
    <x-nx::thinking-trace :active="$thinking" :started-at="$startedAtMs" :steps="[
        ['id' => 'read', 'label' => 'Reading the schema', 'status' => 'done'],
        ['id' => 'plan', 'label' => 'Planning the migration', 'status' => 'running', 'detail' => '3 tables'],
        ['id' => 'test', 'label' => 'Writing tests', 'status' => 'pending'],
    ]" :labels="['thinking' => 'در حال فکر…', 'thought' => ':time فکر کرد']" />

    status: pending | running | done | error. While `active` the header shimmers and the
    clock runs; when it turns false the trace freezes the time ("Thought for 12s") and
    folds away (auto-collapse="false" keeps it open). Livewire re-renders update
    data-active / data-steps and the Alpine part follows them, so new steps animate in.
--}}
@props(['steps' => [], 'active' => false, 'startedAt' => null, 'duration' => null, 'open' => null, 'autoCollapse' => true, 'labels' => []])
@php
    use NabuXUI\NabuXUI;

    $id = NabuXUI::id('nx-think');
    $words = array_merge(['thinking' => 'Thinking…', 'thought' => 'Thought for :time'], is_array($labels) ? $labels : []);
    $statuses = ['pending', 'running', 'done', 'error'];
    $steps = array_values(array_map(fn ($step) => [
        'id' => (string) ($step['id'] ?? uniqid('s')),
        'label' => (string) ($step['label'] ?? ''),
        'status' => in_array($step['status'] ?? 'done', $statuses, true) ? ($step['status'] ?? 'done') : 'done',
        'detail' => isset($step['detail']) ? (string) $step['detail'] : null,
    ], (array) $steps));
    $started = $startedAt instanceof \DateTimeInterface ? $startedAt->getTimestamp() * 1000 : ($startedAt !== null ? (int) $startedAt : null);
    $config = [
        'active' => (bool) $active,
        'startedAt' => $started,
        'duration' => $duration !== null ? (float) $duration : null,
        'open' => $open === null ? null : (bool) $open,
        'autoCollapse' => (bool) $autoCollapse,
        'locale' => str_replace('_', '-', app()->getLocale()),
        'thinking' => $words['thinking'],
        'thought' => $words['thought'],
    ];
    $isOpen = $open === null ? (bool) $active : (bool) $open;
@endphp
<div {{ $attributes->class('nx-thinking')->merge([
        'data-active' => $active ? '1' : '0',
        'data-steps' => json_encode($steps, JSON_UNESCAPED_UNICODE),
        'data-started-at' => $started,
        'data-state' => $active ? 'active' : 'done',
    ]) }}
    x-data="nxThinkingTrace(@js($config))" x-bind:data-state="active ? 'active' : 'done'">
    <button type="button" class="nx-thinking-head" aria-controls="{{ $id }}-body"
        aria-expanded="{{ $isOpen ? 'true' : 'false' }}" x-bind:aria-expanded="open ? 'true' : 'false'" x-on:click="open = ! open">
        <span class="nx-agent-swap" aria-hidden="true">
            <span @if ($active) data-on @endif x-bind:data-on="active ? '' : null">{{ NabuXUI::icon('sparkles') }}</span>
            <span @if (! $active) data-on @endif x-bind:data-on="active ? null : ''">{{ NabuXUI::icon('check-circle') }}</span>
        </span>
        <span class="nx-thinking-label" x-text="title">{{ $active ? $words['thinking'] : str_replace(':time', '…', $words['thought']) }}</span>
        <span class="nx-thinking-time" x-show="active" x-text="time" @if (! $active) x-cloak @endif></span>
        {{ NabuXUI::icon('chevron-down', 'nx-thinking-chevron') }}
    </button>
    <span class="nx-visually-hidden" aria-live="polite" x-text="active ? '' : title"></span>
    <div id="{{ $id }}-body" class="nx-agent-collapse" @if ($isOpen) data-open @endif x-bind:data-open="open ? '' : null">
        <div>
            <ol class="nx-thinking-steps" x-show="steps.length" @if (count($steps) === 0) x-cloak @endif>
                <template x-for="(step, i) in steps" :key="step.id">
                    <li class="nx-thinking-step" x-bind:data-status="step.status || 'done'" x-bind:style="'--nx-i: ' + delay(step.id, i)">
                        <span class="nx-thinking-mark">
                            <span class="nx-spinner" data-size="sm" aria-hidden="true" x-show="step.status === 'running'"></span>
                            <span x-show="(step.status || 'done') === 'done'">{{ NabuXUI::icon('check') }}</span>
                            <span x-show="step.status === 'error'">{{ NabuXUI::icon('x') }}</span>
                            <span class="nx-thinking-dot" aria-hidden="true" x-show="step.status === 'pending'"></span>
                        </span>
                        <span class="nx-thinking-step-label" x-text="step.label"></span>
                        <template x-if="step.detail">
                            <p class="nx-thinking-step-detail" x-text="step.detail"></p>
                        </template>
                    </li>
                </template>
            </ol>
            {{ $slot }}
        </div>
    </div>
</div>
