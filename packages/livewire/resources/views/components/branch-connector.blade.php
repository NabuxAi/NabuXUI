{{--
    <x-nx::branch-connector
        :source="['label' => 'Inbound message', 'description' => 'WhatsApp · Telegram · Web', 'icon' => 'message']"
        :targets="[
            ['label' => 'Nabu agent', 'description' => 'English', 'icon' => 'sparkles'],
            ['label' => 'Agente Nabu', 'description' => 'Español', 'icon' => 'globe'],
            ['label' => 'Human handoff', 'description' => 'Queue: billing', 'icon' => 'users', 'state' => 'idle'],
        ]" />

    The links are measured from the page and redrawn as it resizes; pulses flow along the active
    ones. Right-to-left pages mirror it, and a narrow container stacks it (source above, targets below).
--}}
@props(['source' => [], 'targets' => [], 'targetsLabel' => null])
@php
    use NabuXUI\NabuXUI;
    $lang = substr(app()->getLocale(), 0, 2);
    $targetsLabel ??= ['fa' => 'متصل به', 'ar' => 'متصل بـ'][$lang] ?? 'Connected to';
    $icon = fn ($name) => $name && NabuXUI::hasIcon($name) ? NabuXUI::icon($name) : '';
@endphp
<div {{ $attributes->class('nx-branch')->merge(['data-nx-reveal' => '']) }} x-data="nxBranchConnector()">
    <div class="nx-branch-layout">
        <svg class="nx-branch-links" data-nx-links aria-hidden="true">
            @foreach ($targets as $i => $target)
                <g class="nx-branch-link" data-nx-link @if (($target['state'] ?? 'active') === 'idle') data-state="idle" @endif style="--nx-i: {{ $i }}">
                    <path class="nx-branch-track" pathLength="1"/>
                    <path class="nx-branch-pulse" pathLength="1"/>
                </g>
            @endforeach
        </svg>
        <div class="nx-branch-node nx-branch-source" data-nx-node="source">
            @if (! empty($source['icon']))<span class="nx-branch-icon">{{ $icon($source['icon']) }}</span>@endif
            <span class="nx-branch-text">
                <span class="nx-branch-label">{{ $source['label'] ?? '' }}</span>
                @if (! empty($source['description']))<span class="nx-branch-description">{{ $source['description'] }}</span>@endif
            </span>
        </div>
        <ul class="nx-branch-targets" aria-label="{{ $targetsLabel }}">
            @foreach ($targets as $i => $target)
                <li class="nx-branch-node" data-nx-node="target" data-state="{{ ($target['state'] ?? 'active') === 'idle' ? 'idle' : 'active' }}" style="--nx-i: {{ $i + 1 }}">
                    @if (! empty($target['icon']))<span class="nx-branch-icon">{{ $icon($target['icon']) }}</span>@endif
                    <span class="nx-branch-text">
                        <span class="nx-branch-label">{{ $target['label'] ?? '' }}</span>
                        @if (! empty($target['description']))<span class="nx-branch-description">{{ $target['description'] }}</span>@endif
                    </span>
                </li>
            @endforeach
        </ul>
    </div>
</div>
