<?php

namespace Database\Factories;

use App\Models\CalendarEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CalendarEvent>
 */
class CalendarEventFactory extends Factory
{
    /**
     * Plausible calendar titles so seed data reads like a real week
     * (deterministic across re-seeds, cycling with modulo).
     *
     * @var list<string>
     */
    public const TITLES = [
        'Sprint planning',
        'Design critique',
        '1:1 with product',
        'Customer onboarding call',
        'Roadmap review',
        'Team retro',
        'Release cut & QA pass',
        'Interview: backend role',
        'Budget sync with finance',
        'All-hands meeting',
    ];

    protected static int $cursor = 0;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $index = self::$cursor % count(self::TITLES);
        self::$cursor++;

        return [
            'title' => self::TITLES[$index],
            'event_date' => now()->startOfWeek()->addDays($index % 7)->toDateString(),
            'event_time' => sprintf('%02d:%02d', 9 + $index % 8, $index % 2 === 0 ? 0 : 30),
            'color' => CalendarEvent::COLORS[$index % count(CalendarEvent::COLORS)],
        ];
    }
}
