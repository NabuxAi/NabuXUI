<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Plausible task titles so seed data reads like a real board
     * (deterministic across re-seeds, cycling with modulo).
     *
     * @var list<string>
     */
    public const TITLES = [
        'Write API documentation for orders',
        'Fix login redirect loop on Safari',
        'Review Q3 vendor invoices',
        'Migrate staging database to Postgres 17',
        'Design empty state for task board',
        'Set up nightly backup verification',
        'Refactor pricing rules into a policy class',
        'Add rate limiting to public endpoints',
        'Draft onboarding email sequence',
        'Fix flaky checkout E2E test',
        'Update dependency audit report',
        'Prototype bulk import for products',
        'Write migration guide for v3',
        'Instrument checkout funnel with events',
        'Rotate stale access tokens',
        'Ship dark mode toggle in settings',
        'Clean up unused translation keys',
        'Benchmark image pipeline after cache change',
        'Interview candidates for backend role',
        'Add keyboard shortcuts to task board',
        'Verify invoices against bank feed',
        'Split monolith queue worker config',
        'Draft changelog for October release',
        'Archive deprecated webhooks',
    ];

    /**
     * @var list<string>
     */
    protected const ASSIGNEES = [
        'Sara Ahmadi', 'Daniel Wu', 'Mina Park',
        'Omar Reyes', 'Lena Fischer', 'Tom Novak',
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
            'status' => Task::STATUSES[$index % 3],
            'priority' => Task::PRIORITIES[($index + intdiv($index, 3)) % 3],
            'assignee_name' => $index % 4 === 3 ? null : self::ASSIGNEES[$index % count(self::ASSIGNEES)],
            'due_at' => $index % 6 === 5 ? null : now()->addDays($index - 5)->toDateString(),
        ];
    }
}
