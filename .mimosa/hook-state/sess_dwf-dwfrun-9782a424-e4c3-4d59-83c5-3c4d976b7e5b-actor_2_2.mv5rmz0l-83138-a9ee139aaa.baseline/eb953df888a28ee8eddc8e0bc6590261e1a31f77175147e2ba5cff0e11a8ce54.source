<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\AdminPanel;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * /admin/kanban's neighbour — /admin/calendar: the month grid over the
 * calendar block with the panel's sample events spread around today (so the
 * opening month always carries chips), the fa week starting Saturday. The
 * picked day binds back through wire:model; the side box lists what's ahead.
 */
#[Layout('layouts.admin')]
class Calendar extends Component
{
    use AdminPanel;

    /** The calendar block's wire:model — the picked day ("YYYY-MM-DD") or null. */
    public ?string $selectedDay = null;

    public function mount(): void
    {
        $this->rememberLocale();

        // Today carries the ship event, so the agenda opens populated.
        $this->selectedDay = now()->toDateString();
    }

    public function render()
    {
        $locale = str_replace('_', '-', app()->getLocale());
        $fa = app()->getLocale() === 'fa';
        $say = fn (string $en, string $faText) => $fa ? $faText : $en;

        $day = fn (int $offset) => now()->addDays($offset);
        $events = [
            ['date' => $day(-7), 'label' => __('admin.calendar_event_support'), 'time' => '09:00', 'tone' => 'info'],
            ['date' => $day(-4), 'label' => __('admin.calendar_event_review'), 'time' => '10:00', 'tone' => 'accent'],
            ['date' => $day(0), 'label' => __('admin.calendar_event_ship'), 'time' => '16:30', 'tone' => 'danger'],
            ['date' => $day(3), 'label' => __('admin.calendar_event_offsite'), 'tone' => 'gold'],
            ['date' => $day(3), 'label' => __('admin.calendar_event_support'), 'time' => '09:00', 'tone' => 'info'],
            ['date' => $day(8), 'label' => __('admin.calendar_event_review'), 'time' => '10:00', 'tone' => 'accent'],
            ['date' => $day(11), 'label' => __('admin.calendar_event_support'), 'tone' => 'info'],
        ];

        $long = new \IntlDateFormatter($locale, \IntlDateFormatter::NONE, \IntlDateFormatter::NONE, 'UTC', null, 'EEEE, d MMMM');
        $upcoming = [];
        foreach ($events as $i => $event) {
            /** @var \Illuminate\Support\Carbon $when */
            $when = $event['date'];
            if ($when->isPast() && ! $when->isToday()) {
                continue;
            }
            $upcoming[] = [
                'id' => 'e'.$i,
                'label' => $event['label'],
                'time' => $event['time'] ?? null,
                'tone' => $event['tone'],
                'when' => (string) $long->format($when->getTimestamp()),
                'today' => $when->isToday(),
            ];
        }

        $picked = is_string($this->selectedDay) && $this->selectedDay !== ''
            ? (string) $long->format((new \DateTimeImmutable($this->selectedDay.' 00:00:00', new \DateTimeZone('UTC')))->getTimestamp())
            : null;

        return view('livewire.admin.calendar', [
            'events' => $events,
            'weekStart' => $fa ? 6 : 0,
            'upcoming' => $upcoming,
            'picked' => $picked,
        ]);
    }
}
