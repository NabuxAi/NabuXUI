{{--
    <x-nx::spotlight-tour id="welcome" :steps="[
        ['target' => '#new-project', 'title' => 'Start here', 'body' => 'Create your first project.'],
        ['target' => '#search', 'title' => 'Find anything', 'body' => 'Press / to search.', 'side' => 'bottom'],
        ['title' => 'All set', 'body' => 'You can replay this tour from Help.'],
    ]" />
    <x-nx::button x-data x-on:click="$dispatch('nx-tour-start', 'welcome')">Take the tour</x-nx::button>

    Onboarding coachmarks: dims the page with a cut-out around each step's
    target, scrolls it into view and places the step beside it. Arrows go back
    and forth (reading direction), Escape skips, Tab stays inside, focus returns
    on close. `open` starts it on load. Dispatches nx-tour-step { index },
    nx-tour-finish and nx-tour-close. Labels: next, back, skip, done and step
    (":current of :total").
--}}
@props([
    'steps' => [],
    'id' => null,
    'open' => false,
    'padding' => 8,
    'next' => 'Next',
    'back' => 'Back',
    'skip' => 'Skip tour',
    'done' => 'Done',
    'stepLabel' => 'Step :current of :total',
])
@php
    $steps = array_values(array_map(fn ($s) => [
        'target' => $s['target'] ?? null,
        'title' => (string) ($s['title'] ?? ''),
        'body' => isset($s['body']) ? (string) $s['body'] : null,
        'side' => $s['side'] ?? 'bottom',
    ], $steps));
    $uid = \NabuXUI\NabuXUI::id('nx-tour');
    $options = ['id' => $id, 'open' => (bool) $open, 'padding' => (int) $padding];
@endphp
<div {{ $attributes->class('nx-tour') }} popover="manual" x-data="nxSpotlightTour(@js($steps), @js($options))"
    x-show="open" x-cloak style="display: none"
    x-on:nx-tour-start.window="start(0, typeof $event.detail === 'string' ? $event.detail : null)" wire:ignore>
    <div class="nx-tour-catcher" aria-hidden="true"></div>
    <div class="nx-tour-hole" aria-hidden="true"></div>
    <div class="nx-tour-pop" x-ref="pop" role="dialog" aria-modal="true" aria-labelledby="{{ $uid }}-title" aria-describedby="{{ $uid }}-body" tabindex="-1">
        <p class="nx-tour-step" x-text="@js($stepLabel).replace(':current', info.index + 1).replace(':total', info.count)"></p>
        <h2 class="nx-tour-title" id="{{ $uid }}-title" x-text="step.title"></h2>
        <p class="nx-tour-body" id="{{ $uid }}-body" x-show="step.body" x-text="step.body"></p>
        <div class="nx-tour-foot">
            <span class="nx-tour-dots" aria-hidden="true">
                <template x-for="(s, i) in steps" :key="i"><i x-bind:data-current="i === info.index ? '' : null"></i></template>
            </span>
            <div class="nx-tour-actions">
                <x-nx::button variant="ghost" size="sm" x-show="info.first" x-on:click="close()">{{ $skip }}</x-nx::button>
                <x-nx::button variant="ghost" size="sm" x-show="!info.first" x-on:click="back()">{{ $back }}</x-nx::button>
                <x-nx::button variant="primary" size="sm" x-on:click="next()">
                    <span x-text="info.last ? @js($done) : @js($next)">{{ $next }}</span>
                </x-nx::button>
            </div>
        </div>
    </div>
</div>
