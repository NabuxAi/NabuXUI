<?php

namespace App\Filament\Pages;

use App\Models\CalendarEvent;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use NabuXUI\Filament\Forms\Components\NxSegmented;
use NabuXUI\Filament\Forms\Components\NxTimePicker;
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
 *
 * Beyond the component itself the page adds two things: the aside's
 * «رویدادهای روز انتخابی» agenda (Feature A — the picked day's records,
 * with an empty-state hint) and the «رویداد تازه» modal action (Feature B —
 * creates a CalendarEvent; see getHeaderActions()). Because the calendar
 * ships its events to Alpine once at init, saving rides the eventsStamp
 * wire:key (calendarData()) to swap the whole grid for a fresh server
 * render instead of trusting the morph to outsmart the client repaint.
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

    /** Record color keys the model allows, with their Persian labels for the segmented field. */
    protected const COLOR_OPTIONS = [
        'blue' => 'آبی',
        'orange' => 'نارنجی',
        'green' => 'سبز',
        'indigo' => 'نیلی',
    ];

    public function mount(): void
    {
        $this->month = now()->format('Y-m');
    }

    /**
     * «رویداد تازه» — a modal form over the CalendarEvent fields, opened from
     * the page header or from the selected-day aside. The date field starts on
     * the day picked in the calendar (today when none), and saving jumps the
     * calendar to the new event's day so the fresh record is immediately in
     * view.
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('createEvent')
                ->label('رویداد تازه')
                ->icon(Heroicon::OutlinedPlus)
                ->modalHeading('رویداد تازه')
                ->modalDescription('تاریخ پیش‌فرض، روز انتخاب‌شده در تقویم است.')
                ->modalSubmitActionLabel('ثبت رویداد')
                ->modalCancelActionLabel('انصراف')
                ->form([
                    TextInput::make('title')
                        ->label('عنوان')
                        ->required()
                        ->maxLength(120)
                        ->columnSpanFull(),
                    NxSegmented::make('color')
                        ->label('رنگ')
                        ->options(self::COLOR_OPTIONS)
                        ->default('blue')
                        ->rule('in:'.implode(',', array_keys(self::COLOR_OPTIONS))),
                    DatePicker::make('date')
                        ->label('تاریخ')
                        ->required()
                        ->default(fn (): string => $this->selectedDay ?? now()->toDateString()),
                    NxTimePicker::make('time')
                        ->label('ساعت')
                        ->minuteStep(5)
                        ->default('09:00')
                        ->rule('date_format:H:i'),
                ])
                ->action(function (array $data): void {
                    $event = CalendarEvent::query()->create([
                        'title' => trim((string) $data['title']),
                        'event_date' => $data['date'],
                        'event_time' => $data['time'],
                        'color' => $data['color'],
                    ]);

                    // The saved day becomes the selected day, so the aside's
                    // «رویدادهای روز انتخابی» opens on the fresh record.
                    $this->selectDay($event->event_date->toDateString());

                    Notification::make()
                        ->title('رویداد ثبت شد')
                        ->body(sprintf('«%s» به تقویم اضافه شد.', $event->title))
                        ->success()
                        ->send();
                }),
        ];
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

        // The selected day's own records, for the aside's agenda section
        // (title, time and the record's color, like the «در راه» rows).
        $dayEvents = $this->selectedDay === null ? [] : $events
            ->filter(fn (CalendarEvent $event): bool => $event->event_date->toDateString() === $this->selectedDay)
            ->map(fn (CalendarEvent $event): array => [
                'title' => $event->title,
                'time' => $this->formatTime($event),
                'cssColor' => $this->colorVar($event->color),
            ])
            ->values()
            ->all();

        return [
            'calendarEvents' => $calendarEvents,
            'upcoming' => $upcoming,
            'dayEvents' => $dayEvents,
            // Grows only when a record is created; the blade puts it in the
            // grid wrapper's wire:key, so a fresh CalendarEvent swaps the
            // whole client calendar for a server-rendered one (the Alpine
            // block would otherwise repaint from its init-time event map and
            // drop the new chip on the next pick).
            'eventsStamp' => (string) CalendarEvent::query()->max('id'),
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

    /**
     * The event's own time, locale digits («۰۹:۳۰») — the side agenda rows
     * carry the same time style the grid's chips imply.
     */
    protected function formatTime(CalendarEvent $event): string
    {
        $at = \DateTimeImmutable::createFromFormat('H:i', $event->event_time, new \DateTimeZone('UTC'));

        if ($at === false) {
            return $event->event_time;
        }

        return (string) (new \IntlDateFormatter(app()->getLocale(), \IntlDateFormatter::NONE, \IntlDateFormatter::NONE, 'UTC', null, 'HH:mm'))->format($at);
    }
}
