{{--
    The «تقویم» page body, wired in through App\Filament\Pages\Calendar::
    content(Schema). Left (start): the package's <x-nx::calendar> month grid
    over the CalendarEvent records — the events go in as a plain blade prop
    and the component ships them to Alpine itself via @js
    (calendar.blade.php:86), so no manual @json here. The two events the
    component dispatches are connected to the page's Livewire actions on the
    component tag (they land on the root section via $listenerAttrs):
    nx-select → selectDay(), nx-month → trackMonth(). wire:model binds
    selectedDay so the aside's buttons can drive the grid in reverse — a
    server-side value lands in the Alpine `model` and the grid slides over.
    Right (end): the «در راه» list of upcoming events, each dot carrying the
    record's own color through the design system's semantic variables.
--}}
<section class="nx-calpage" aria-label="تقویم">
    <header class="nx-calpage-head" data-nx-reveal x-data x-nx-reveal>
        <p class="nx-calpage-sub">{{ $subheading }}</p>
        <p class="nx-calpage-week" role="status">
            {{ \NabuXUI\NabuXUI::icon('sparkles') }}
            <span>{{ $weekCountLabel }}</span>
        </p>
    </header>

    <div class="nx-calpage-body">
        <div class="nx-calpage-grid" data-nx-reveal x-data x-nx-reveal>
            <x-nx::calendar
                :events="$calendarEvents"
                :value="$selectedDay"
                :month="$month"
                :today="$today"
                wire:model="selectedDay"
                x-on:nx-select="$wire.call('selectDay', $event.detail)"
                x-on:nx-month="$wire.call('trackMonth', $event.detail)"
            />
        </div>

        <aside class="nx-calpage-side" data-nx-reveal x-data x-nx-reveal aria-labelledby="nx-calpage-side-title">
            <h3 class="nx-calpage-side-title" id="nx-calpage-side-title">
                {{ \NabuXUI\NabuXUI::icon('arrow-right') }}
                <span>در راه</span>
            </h3>
            <ul class="nx-calpage-events">
                @forelse ($upcoming as $event)
                    <li>
                        <button type="button" class="nx-calpage-event"
                            wire:click="selectDay('{{ $event['date'] }}')"
                            @if ($event['date'] === $selectedDay) data-active @endif>
                            <span class="nx-calpage-dot" style="background: {{ $event['cssColor'] }}" aria-hidden="true"></span>
                            <span class="nx-calpage-event-body">
                                <span class="nx-calpage-event-title">{{ $event['title'] }}</span>
                                <span class="nx-calpage-event-meta">{{ $event['when'] }}</span>
                            </span>
                            @if ($event['isThisWeek'])
                                <span class="nx-calpage-tag">این هفته</span>
                            @endif
                        </button>
                    </li>
                @empty
                    <li><p class="nx-calpage-empty">رویدادی در راه نیست.</p></li>
                @endforelse
            </ul>
        </aside>
    </div>
</section>

<style>
    /* The calendar band: grid and «در راه» aside side by side, stacked on narrow screens. */
    .nx-calpage { display: grid; gap: var(--nx-space-4); }
    .nx-calpage-head { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: var(--nx-space-3); }
    .nx-calpage-sub { margin: 0; color: var(--nx-text-muted); }
    .nx-calpage-week { display: inline-flex; align-items: center; gap: var(--nx-space-2); margin: 0; padding: var(--nx-space-2) var(--nx-space-3); border: 1px solid var(--nx-border); border-radius: var(--nx-radius-md); background: var(--nx-surface-2); font-weight: 600; }
    .nx-calpage-week .nx-icon { color: var(--nx-accent); }

    .nx-calpage-body { display: grid; grid-template-columns: minmax(0, 2.2fr) minmax(16rem, 1fr); gap: var(--nx-space-4); align-items: start; }

    .nx-calpage-grid { border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg); background: var(--nx-surface); padding: var(--nx-space-4); box-shadow: var(--nx-shadow-xs); }

    .nx-calpage-side { border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg); background: var(--nx-surface); padding: var(--nx-space-4); box-shadow: var(--nx-shadow-xs); }
    .nx-calpage-side-title { display: flex; align-items: center; gap: var(--nx-space-2); margin: 0 0 var(--nx-space-3); font: 700 var(--nx-text-md) / 1.3 var(--nx-font-display); color: var(--nx-text); }
    .nx-calpage-side-title .nx-icon { color: var(--nx-accent); transform: scaleX(-1); }

    .nx-calpage-events { display: grid; gap: var(--nx-space-2); margin: 0; padding: 0; list-style: none; }
    .nx-calpage-event { display: flex; align-items: center; gap: var(--nx-space-3); inline-size: 100%; padding: var(--nx-space-3); border: 1px solid var(--nx-border); border-radius: var(--nx-radius-md); background: var(--nx-surface-2); text-align: start; cursor: pointer; transition: border-color 0.2s ease, background-color 0.2s ease; }
    .nx-calpage-event:hover { border-color: var(--nx-border-strong); }
    .nx-calpage-event[data-active] { border-color: var(--nx-accent); background: var(--nx-accent-soft); }
    .nx-calpage-dot { flex: none; inline-size: 0.65rem; block-size: 0.65rem; border-radius: 999px; }
    .nx-calpage-event-body { display: grid; gap: 2px; min-inline-size: 0; }
    .nx-calpage-event-title { font-weight: 600; color: var(--nx-text); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .nx-calpage-event-meta { color: var(--nx-text-muted); font-size: var(--nx-text-xs); }
    .nx-calpage-tag { flex: none; padding: 2px var(--nx-space-2); border-radius: 999px; background: var(--nx-accent-soft); color: var(--nx-accent); font-size: var(--nx-text-xs); font-weight: 600; }
    .nx-calpage-empty { margin: 0; color: var(--nx-text-muted); }

    @media (max-width: 56rem) {
        .nx-calpage-body { grid-template-columns: 1fr; }
    }
</style>
