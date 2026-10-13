<?php

namespace App\Filament\Pages;

use App\Models\Task;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Carbon;
use NabuXUI\NabuXUI;
use UnitEnum;

/**
 * The live task board: <x-nx::kanban> wired to the real Task model. The
 * columns the page passes are the truth (the component's own docblock) —
 * every render rebuilds them from the database, so counts and cards are never
 * stale. Dropping a card between columns (or picking a «انتقال به …» menu
 * item) reaches moveCard() through the component's move-action wire call and
 * flips the record's status; the quick-add composer reaches addCard() and
 * creates a Task. Cards keep stable ids so the component's wire:keys glide
 * through the morph instead of tearing the board down.
 */
class TaskBoard extends Page
{
    protected string $view = 'filament.pages.task-board';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedViewColumns;

    protected static string|UnitEnum|null $navigationGroup = 'ابزارها';

    protected static ?string $navigationLabel = 'برد تسک‌ها';

    protected static ?string $title = 'برد تسک‌ها';

    protected Width|string|null $maxContentWidth = Width::Full;

    /** The board is the three real statuses of the Task model, in order. */
    protected const COLUMN_TITLES = [
        'todo' => 'در انتظار',
        'doing' => 'در حال انجام',
        'done' => 'انجام‌شده',
    ];

    protected const COLUMN_TONES = [
        'todo' => 'info',
        'doing' => 'warning',
        'done' => 'success',
    ];

    protected const PRIORITY_LABELS = [
        'low' => 'کم',
        'medium' => 'متوسط',
        'high' => 'بالا',
    ];

    protected const PRIORITY_WEIGHTS = [
        'low' => 1,
        'medium' => 2,
        'high' => 3,
    ];

    /**
     * Priority rides the card's own tone: the coloured dot a reader scans
     * first (the nx card schema has no badge slot — kanban.blade.php builds
     * cards from title/meta/tone/assignee/actions only).
     */
    protected const PRIORITY_TONES = [
        'low' => 'info',
        'medium' => 'warning',
        'high' => 'danger',
    ];

    /**
     * The columns for <x-nx::kanban>, re-queried on every render so a move is
     * immediately the server's truth again after the optimistic DOM move.
     *
     * @return array<int, array{id: string, title: string, tone: string, cards: array<int, array<string, mixed>>}>
     */
    public function boardColumns(): array
    {
        $tasks = Task::query()->get();

        return array_map(function (string $status) use ($tasks): array {
            return [
                'id' => $status,
                'title' => self::COLUMN_TITLES[$status] ?? $status,
                'tone' => self::COLUMN_TONES[$status] ?? null,
                'cards' => $tasks
                    ->filter(fn (Task $task): bool => $task->status === $status)
                    ->sort($this->compareTasks(...))
                    ->values()
                    ->map(fn (Task $task): array => $this->taskToCard($task))
                    ->all(),
            ];
        }, Task::STATUSES);
    }

    /**
     * The kanban's move-action. nxKanban calls it after its optimistic move
     * with (card, from, to, index) — and the "from" it passes is read from
     * the DOM after the card has already been appended to the target column
     * (alpine/blocks/kanban.ts computes it too late), so it equals "to".
     * The destination is the only column that matters here: it is what the
     * record's status becomes.
     */
    public function moveCard(string $cardId = '', ?string $fromColumn = null, ?string $toColumn = null, ?int $index = null): void
    {
        $to = $toColumn ?? '';
        $id = (int) $cardId;

        if ($id < 1 || ! in_array($to, Task::STATUSES, true)) {
            return;
        }

        $task = Task::query()->find($id);

        if ($task !== null && $task->status !== $to) {
            $task->status = $to;
            $task->save();
        }
    }

    /**
     * The kanban's add-action: addCard($column, $title) from the quick-add
     * composer. The server owns the cards, so the new Task arrives with the
     * morph right after this re-renders the board.
     */
    public function addCard(string $column = '', string $title = ''): void
    {
        $title = trim($title);

        if ($title === '' || ! in_array($column, Task::STATUSES, true)) {
            return;
        }

        Task::query()->create([
            'title' => $title,
            'status' => $column,
            'priority' => 'medium',
        ]);
    }

    /** Earliest due first, then highest priority, then title — a stable order for the morph. */
    protected function compareTasks(Task $a, Task $b): int
    {
        return [$a->due_at?->getTimestamp() ?? PHP_INT_MAX, -self::priorityWeight($a), $a->title]
            <=> [$b->due_at?->getTimestamp() ?? PHP_INT_MAX, -self::priorityWeight($b), $b->title];
    }

    protected static function priorityWeight(Task $task): int
    {
        return self::PRIORITY_WEIGHTS[$task->priority] ?? 0;
    }

    /**
     * One nx card: stable id (the wire:key rides on it), the title, the
     * priority in words plus the due day in meta, the priority's tone dot,
     * and the assignee's initials avatar when the task has one.
     *
     * @return array<string, mixed>
     */
    protected function taskToCard(Task $task): array
    {
        return [
            'id' => (string) $task->id,
            'title' => $task->title,
            'meta' => implode(' · ', array_filter([
                self::PRIORITY_LABELS[$task->priority] ?? null,
                $this->dueLabel($task->due_at),
            ])),
            'tone' => self::PRIORITY_TONES[$task->priority] ?? null,
            'assignee' => filled($task->assignee_name) ? ['name' => $task->assignee_name] : null,
        ];
    }

    /**
     * The due day as the board reads it: امروز، فردا، دو روز دیگر، and the
     * wider windows (and the overdue past) counted in Persian digits.
     */
    protected function dueLabel(?Carbon $due): ?string
    {
        if ($due === null) {
            return null;
        }

        $days = (int) now()->startOfDay()->diffInDays($due->copy()->startOfDay(), false);

        return match (true) {
            $days === 0 => 'امروز',
            $days === 1 => 'فردا',
            $days === 2 => 'دو روز دیگر',
            $days > 2 => NabuXUI::formatNumber($days, 0, 'fa').' روز دیگر',
            $days === -1 => 'دیروز',
            default => NabuXUI::formatNumber(abs($days), 0, 'fa').' روز گذشته',
        };
    }
}
