<?php

namespace App\Filament\Pages;

use App\Models\CalendarEvent;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use NabuXUI\NabuXUI;
use UnitEnum;

/**
 * صفحهٔ «تقویم» (path/calendar/) on the NabuXUI admin panel: the package's
 * <x-nx::calendar> month grid over the CalendarEvent records, plus a «در راه»
 * aside of upcoming events dotting each record's own color.
 *
 * Wiring, per the component's source (packages/livewire/resources/views/
 * components/calendar.blade.php): the events array is passed as a plain prop —
 * the component groups it by day and ships it to Alpine itself via @js, so the
 * client-side month switcher already has every month's chips. The two events
 * the component dispatches come back here as Livewire actions: nx-select →
 * selectDay() (a day was picked or cleared, mirrored through wire:model on
 * selectedDay) and nx-month → trackMonth() (the grid slid to another month).
 * The aside's buttons call selectDay() in the other direction, so the grid
 * jumps to that day via the same wire:model binding.
 *
 * The calendar's chip tones are a fixed palette (accent, success, warning,
 * danger, info, gold — anything else is dropped, see $toneOf in the blade
 * component), so record colors map onto it: blue → info, orange → warning,
 * green → success, indigo → accent. The «در راه» dots carry the same mapping
 * expressed as the design system's semantic color variables.
 */
class Calendar extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $navigationLabel = 'تقویم';

    protected static ?string $title = 'تقویم';

    protected static string|UnitEnum|null $navigationGroup = 'ابزارها';

    /** The day the calendar's agenda is open on ("YYYY-MM-DD"), or null. */
    public ?string $selectedDay = null;

    /** The month the client grid is showing ("YYYY-MM"), reported by nx-month. */
    public ?string $month = null;

    public function mount(): void
    {
        $this->month = now()->format('Y-m');
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            View::make('filament.pages.calendar')
                ->viewData(fn (): array => $this->calendarData()),
        ]);
    }

    /**
     * nx-select → here: the calendar picked a day (or cleared the pick with
     * null). The value is validated before it is stored — it round-trips
     * through the browser into a query-less re-render.
     */
    public function selectDay(?string $day): void
    {
        $this->selectedDay = $day !== null && preg_match('/^\d{4}-\d{2}-\d{2}$/', $day) === 1
            ? $day
            : null;
    }

    /** nx-month → here: the client grid slid to another month. */
    public function trackMonth(?string $month): void
    {
        if ($month !== null && preg_match('/^\d{4}-\d{2}$/', $month) === 1) {
            $this->month = $month;
        }
    }

    /**
     * Everything the blade needs, queried fresh on every render: all events
     * for the grid (so month switching never goes dark), the upcoming ones
     * for the «در راه» list, and this week's tally.
     *
     * @return array<string, mixed>
     */
    protected function calendarData(): array
    {
        $events = CalendarEvent::query()
            ->orderBy('event_date')
            ->orderBy('event_time')
            ->get();

        $today = now()->startOfDay();
        $weekStart = $today->copy()->startOfWeek();
        $weekEnd = $today->copy()->endOfWeek();

        $calendarEvents = $events->map(fn (CalendarEvent $event): array => [
            'date' => $event->event_date->toDateString(),
            'label' => $event->title,
            'time' => $event->event_time,
            'tone' => $this->toneOfColor($event->color),
        ])->all();

        $upcoming = $events
            ->filter(fn (CalendarEvent $event): bool => $event->event_date->gte($today))
            ->take(6)
            ->map(fn (CalendarEvent $event): array => [
                'date' => $event->event_date->toDateString(),
                'title' => $event->title,
                'when' => $this->formatWhen($event),
                'color' => $event->color,
                'cssColor' => $this->colorVar($event->color),
                'isThisWeek' => $event->event_date->between($weekStart, $weekEnd),
            ])
            ->values()
            ->all();

        $weekCount = $events
            ->filter(fn (CalendarEvent $event): bool => $event->event_date->between($weekStart, $weekEnd))
            ->count();

        return [
            'calendarEvents' => $calendarEvents,
            'upcoming' => $upcoming,
            'weekCount' => $weekCount,
            'weekCountLabel' => sprintf('%s رویداد این هفته', NabuXUI::formatNumber($weekCount)),
            'today' => $today->toDateString(),
            'subheading' => 'رویدادهای تقویم تیم؛ روی یک روز بزنید تا دستور کارش باز شود.',
            // The selection state rides the viewData so the blade can pass it
            // into the calendar and mark the active aside row.
            'selectedDay' => $this->selectedDay,
            'month' => $this->month,
        ];
    }

    /**
     * Record color → the calendar chip tone. The component keeps only its own
     * six tones, so unknown record colors fall back to the plain chip.
     */
    protected function toneOfColor(string $color): ?string
    {
        return match ($color) {
            'blue' => 'info',
            'orange' => 'warning',
            'green' => 'success',
            'indigo' => 'accent',
            default => null,
        };
    }

    /** Record color → the semantic design token of the same family, for the aside's dots. */
    protected function colorVar(string $color): string
    {
        return match ($color) {
            'blue' => 'var(--nx-info)',
            'orange' => 'var(--nx-warning)',
            'green' => 'var(--nx-success)',
            'indigo' => 'var(--nx-accent)',
            default => 'var(--nx-accent)',
        };
    }

    /**
     * «دوشنبه ۱۴ اکتبر · ۰۹:۳۰» — the same Intl names and digits the
     * calendar itself paints, so the aside and the grid agree (fa resolves
     * to the Persian calendar through Intl, like the component's own
     * $fmt closure at packages/livewire/.../calendar.blade.php:65).
     */
    protected function formatWhen(CalendarEvent $event): string
    {
        $locale = app()->getLocale();
        $at = \DateTimeImmutable::createFromFormat(
            'Y-m-d H:i',
            $event->event_date->toDateString().' '.$event->event_time,
            new \DateTimeZone('UTC'),
        );

        if ($at === false) {
            return $event->event_time;
        }

        $day = (string) (new \IntlDateFormatter($locale, \IntlDateFormatter::NONE, \IntlDateFormatter::NONE, 'UTC', null, 'EEEE d MMMM'))->format($at);
        $time = (string) (new \IntlDateFormatter($locale, \IntlDateFormatter::NONE, \IntlDateFormatter::NONE, 'UTC', null, 'HH:mm'))->format($at);

        return $day.' · '.$time;
    }
}
