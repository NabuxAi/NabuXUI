{{--
    /admin/calendar — the month grid over the calendar block (fa weeks start
    Saturday, en Sundays), wire:model carrying the picked day back to the
    component. Beside it: what's ahead, tone-dotted, with the picked day
    echoed from the server. Event words and the box's headings come from
    admin.* keys.
--}}
<x-admin.page active="calendar" :title="__('admin.calendar_title')" :subtitle="__('admin.calendar_subtitle')">
    <style>
        /* The upcoming list's own pieces (tokens only, both themes). */
        .cal-list { list-style: none; margin: 0; padding: 0; display: grid; gap: var(--nx-space-2); }
        .cal-item { display: flex; align-items: center; gap: var(--nx-space-3); padding: var(--nx-space-2) var(--nx-space-3); border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg); background: var(--nx-surface-2); }
        .cal-dot { flex: none; inline-size: 0.55rem; block-size: 0.55rem; border-radius: var(--nx-radius-full); background: var(--nx-accent); }
        .cal-dot[data-tone="success"] { background: var(--nx-success); }
        .cal-dot[data-tone="warning"] { background: var(--nx-warning); }
        .cal-dot[data-tone="danger"] { background: var(--nx-danger); }
        .cal-dot[data-tone="info"] { background: var(--nx-info); }
        .cal-dot[data-tone="gold"] { background: var(--nx-gold); }
        .cal-body { display: grid; gap: 0.05rem; text-align: start; }
        .cal-label { color: var(--nx-text); font-weight: 600; }
        .cal-when { font-size: var(--nx-text-xs); color: var(--nx-text-subtle); }
        .cal-time { margin-inline-start: auto; font-size: var(--nx-text-sm); color: var(--nx-text-muted); font-variant-numeric: tabular-nums; }
        .cal-picked { margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted); }
        .cal-picked strong { color: var(--nx-text); }
    </style>
    <div class="ap-grid">
        <div class="ap-duo">
            <x-nx::calendar :events="$events" :week-start="$weekStart" :value="$selectedDay"
                wire:model="selectedDay" :label="__('admin.calendar_title')" :max-per-cell="3" />

            <section class="ap-box">
                <h3 class="ap-box-title">{{ __('admin.calendar_upcoming') }}</h3>
                <ul class="cal-list">
                    @forelse ($upcoming as $i => $event)
                        <li class="cal-item" style="--nx-i: {{ $i }}">
                            <span class="cal-dot" @if ($event['tone']) data-tone="{{ $event['tone'] }}" @endif aria-hidden="true"></span>
                            <span class="cal-body">
                                <span class="cal-label">{{ $event['label'] }}</span>
                                <span class="cal-when">{{ $event['when'] }}</span>
                            </span>
                            @if ($event['time'])
                                <span class="cal-time">{{ $event['time'] }}</span>
                            @endif
                            @if ($event['today'])
                                <x-nx::badge tone="accent">{{ __('admin.calendar_today') }}</x-nx::badge>
                            @endif
                        </li>
                    @empty
                        <li class="cal-item"><span class="cal-when">{{ __('admin.calendar_upcoming_empty') }}</span></li>
                    @endforelse
                </ul>
                <p class="cal-picked">
                    {{ __('admin.calendar_picked') }}
                    <strong>{{ $picked ?? '—' }}</strong>
                </p>
            </section>
        </div>
    </div>
</x-admin.page>
