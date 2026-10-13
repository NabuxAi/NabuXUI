{{--
    <x-nx::approval-card title="Run database migration?" tool="php artisan migrate"
        summary="Adds 2 columns to orders. Reversible." action="resolve" wire:model="decision">
        <x-nx::diff-viewer :old-text="$before" :new-text="$after" />
        <x-slot:meta><span class="nx-approval-chip">production</span></x-slot:meta>
    </x-nx::approval-card>

    The agent asks; the reader answers with the buttons or Y / N / A while focus is in
    the card. The card folds into one line with the badge icon swapped (shield → check
    or cross). x-modelable="state" (wire:model binds it); an nx-approval {state} event is
    dispatched, and with `action` $wire.action(state) is called.
--}}
@props(['title', 'summary' => null, 'tool' => null, 'state' => 'pending', 'allowAlways' => true, 'shortcuts' => true, 'action' => null, 'undoable' => false, 'autofocus' => false, 'labels' => [], 'meta' => null])
@php
    use NabuXUI\NabuXUI;

    $id = NabuXUI::id('nx-approval');
    $words = array_merge([
        'approve' => 'Approve', 'deny' => 'Deny', 'always' => 'Always allow',
        'approved' => 'Approved', 'denied' => 'Denied', 'alwaysAllowed' => 'Always allowed', 'undo' => 'Undo',
    ], is_array($labels) ? $labels : []);
    $state = in_array($state, ['pending', 'approved', 'denied', 'always'], true) ? $state : 'pending';
    $results = ['pending' => '', 'approved' => $words['approved'], 'denied' => $words['denied'], 'always' => $words['alwaysAllowed']];
    $pending = $state === 'pending';
    $details = trim((string) $slot) !== '';
@endphp
<section {{ $attributes->class('nx-approval')->merge(['data-state' => $state, 'tabindex' => '-1', 'aria-labelledby' => $id.'-title']) }}
    x-data="nxApprovalCard(@js(['state' => $state, 'allowAlways' => (bool) $allowAlways, 'shortcuts' => (bool) $shortcuts, 'action' => $action, 'autofocus' => (bool) $autofocus]))"
    x-modelable="state" x-bind:data-state="current" x-on:keydown="key($event)">
    <div class="nx-approval-head">
        <span class="nx-approval-badge nx-agent-swap" aria-hidden="true">
            <span @if ($pending) data-on @endif x-bind:data-on="pending ? '' : null">{{ NabuXUI::icon('shield') }}</span>
            <span @if (in_array($state, ['approved', 'always'], true)) data-on @endif x-bind:data-on="current === 'approved' || current === 'always' ? '' : null">{{ NabuXUI::icon('check') }}</span>
            <span @if ($state === 'denied') data-on @endif x-bind:data-on="current === 'denied' ? '' : null">{{ NabuXUI::icon('x') }}</span>
        </span>
        <div class="nx-approval-copy">
            <h3 id="{{ $id }}-title" class="nx-approval-title">{{ $title }}</h3>
            @if ($summary)
                <p class="nx-approval-summary" x-show="pending" @if (! $pending) x-cloak @endif>{{ $summary }}</p>
            @endif
            @if ($tool || $meta)
                <div class="nx-approval-meta" x-show="pending" @if (! $pending) x-cloak @endif>
                    @if ($tool)
                        <span class="nx-approval-chip" dir="ltr">{{ NabuXUI::icon('zap') }}{{ $tool }}</span>
                    @endif
                    {{ $meta }}
                </div>
            @endif
        </div>
        <span class="nx-approval-result" x-show="! pending" x-text="@js($results)[current]" @if ($pending) x-cloak @endif>{{ $results[$state] }}</span>
        @if ($undoable)
            <button type="button" class="nx-approval-undo" x-show="! pending" x-on:click="decide('pending')" @if ($pending) x-cloak @endif>{{ $words['undo'] }}</button>
        @endif
    </div>
    <span class="nx-visually-hidden" aria-live="polite" x-text="@js($results)[current]"></span>
    <div class="nx-agent-collapse" @if ($pending) data-open @endif x-bind:data-open="pending ? '' : null">
        <div>
            <div class="nx-approval-body">
                @if ($details)
                    <div class="nx-approval-details">{{ $slot }}</div>
                @endif
                <div class="nx-approval-actions">
                    <x-nx::button variant="primary" size="sm" icon="check" x-on:click="decide('approved')" :aria-keyshortcuts="$shortcuts ? 'Y' : null">
                        {{ $words['approve'] }}@if ($shortcuts)<kbd class="nx-kbd" aria-hidden="true">Y</kbd>@endif
                    </x-nx::button>
                    <x-nx::button variant="secondary" size="sm" icon="x" x-on:click="decide('denied')" :aria-keyshortcuts="$shortcuts ? 'N' : null">
                        {{ $words['deny'] }}@if ($shortcuts)<kbd class="nx-kbd" aria-hidden="true">N</kbd>@endif
                    </x-nx::button>
                    <span class="nx-approval-spacer"></span>
                    @if ($allowAlways)
                        <x-nx::button variant="ghost" size="sm" x-on:click="decide('always')" :aria-keyshortcuts="$shortcuts ? 'A' : null">
                            {{ $words['always'] }}@if ($shortcuts)<kbd class="nx-kbd" aria-hidden="true">A</kbd>@endif
                        </x-nx::button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
