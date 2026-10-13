<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\AdminPanel;
use App\Support\Locales;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use NabuXUI\NabuXUI;

/**
 * /admin/todo — the personal task list on the nx-todo block with Livewire as
 * the single truth: every tick, remove, drag (or Alt+arrow nudge) and
 * quick-add reports back through toggleTask / removeTask / addTask /
 * moveTask, so the list survives reloads. The chips filter what the block
 * renders (all / open / done) and the drop index a filtered view hands over
 * is mapped back onto the full list, so tasks land where they look. Words
 * come from the admin.todo_* keys; the block's own words (add, empty,
 * progress) live in the core i18n table.
 */
#[Layout('layouts.admin')]
class Todo extends Component
{
    use AdminPanel;

    /** The chips' value: 'all' | 'open' | 'done'. */
    public string $filter = 'all';

    /** The board's groups (today / tomorrow); the todo block mutates them through the actions below. */
    public array $groups = [];

    /**
     * The shared trait's updated() hook types its value ?string, which the
     * groups array crashes against — same behaviour for the locale switch,
     * but through a wider door. (Class methods win over the trait's.)
     */
    public function updated(string $name, mixed $value): void
    {
        if ($name === 'locale' && is_string($value) && in_array($value, Locales::codes(), true)) {
            session(['locale' => $value]);
            $this->redirect($this->path);
        }
    }

    public function mount(): void
    {
        $this->rememberLocale();

        $this->groups = [
            ['id' => 'today', 'title' => __('admin.todo_today'), 'tone' => 'success', 'tasks' => [
                ['id' => 't1', 'title' => __('admin.todo_task_1'), 'done' => true],
                ['id' => 't2', 'title' => __('admin.todo_task_2'), 'done' => false],
                ['id' => 't3', 'title' => __('admin.todo_task_3'), 'done' => false],
            ]],
            ['id' => 'tomorrow', 'title' => __('admin.todo_tomorrow'), 'tone' => 'info', 'tasks' => [
                ['id' => 't4', 'title' => __('admin.todo_task_4'), 'done' => false],
                ['id' => 't5', 'title' => __('admin.todo_task_5'), 'done' => true],
                ['id' => 't6', 'title' => __('admin.todo_task_6'), 'done' => false],
            ]],
        ];
    }

    /** The block's toggle-action: the spring tick lands on the server. */
    public function toggleTask(string $task, bool $done): void
    {
        foreach ($this->groups as &$group) {
            foreach ($group['tasks'] as &$item) {
                if ($item['id'] === $task) {
                    $item['done'] = $done;
                }
            }
            unset($item);
        }
        unset($group);
    }

    /** The block's remove-action: the row leaves the list. */
    public function removeTask(string $task): void
    {
        foreach ($this->groups as &$group) {
            $group['tasks'] = array_values(array_filter(
                $group['tasks'],
                fn (array $item) => $item['id'] !== $task,
            ));
        }
        unset($group);
    }

    /** The block's add-action: the quick-add composer grows the picked group. */
    public function addTask(string $group, string $title): void
    {
        $title = trim($title);
        if ($title === '') {
            return;
        }

        foreach ($this->groups as &$groupRef) {
            if ($groupRef['id'] !== $group) {
                continue;
            }
            $groupRef['tasks'][] = ['id' => 't'.Str::random(6), 'title' => $title, 'done' => false];
        }
        unset($groupRef);

        $this->toast(__('admin.todo_added_toast'), tone: 'success');
    }

    /**
     * The block's move-action: drop $task from $from into $to at $index.
     * The index counts the tasks the block can see under the active filter —
     * map it back onto the full list so drops land where they look.
     */
    public function moveTask(string $task, string $from, string $to, int $index): void
    {
        $moving = null;
        foreach ($this->groups as &$group) {
            if ($group['id'] !== $from) {
                continue;
            }
            foreach ($group['tasks'] as $i => $item) {
                if ($item['id'] === $task) {
                    $moving = $item;
                    unset($group['tasks'][$i]);
                    $group['tasks'] = array_values($group['tasks']);
                    break;
                }
            }
        }
        unset($group);

        if ($moving === null) {
            return;
        }

        foreach ($this->groups as &$group) {
            if ($group['id'] !== $to) {
                continue;
            }

            $tasks = array_values($group['tasks']);
            $visible = array_values(array_filter($tasks, fn (array $item) => $this->taskVisible($item)));

            // Where the drop looked: before the $index-th visible task, or at
            // the end when it fell past the last one.
            $real = count($tasks);
            if ($index >= 0 && $index < count($visible)) {
                $real = (int) array_search($visible[$index]['id'], array_column($tasks, 'id'), true);
            }

            array_splice($tasks, max(0, min($real, count($tasks))), 0, [$moving]);
            $group['tasks'] = $tasks;
        }
        unset($group);
    }

    /** The toolbar's “clear completed”: the done rows leave, the toast says so. */
    public function clearDone(): void
    {
        $gone = 0;
        foreach ($this->groups as &$group) {
            $kept = array_values(array_filter($group['tasks'], fn (array $item) => ! $item['done']));
            $gone += count($group['tasks']) - count($kept);
            $group['tasks'] = $kept;
        }
        unset($group);

        if ($gone > 0) {
            $this->toast(__('admin.todo_cleared_toast'), tone: 'success');
        }
    }

    public function render()
    {
        $locale = str_replace('_', '-', app()->getLocale());
        $fmt = fn ($value) => NabuXUI::formatNumber((float) $value, 0, $locale);

        $tasks = array_merge(...array_map(fn (array $group) => $group['tasks'], $this->groups) ?: [[]]);
        $done = count(array_filter($tasks, fn (array $task) => $task['done']));
        $open = count($tasks) - $done;

        // The chips see everything; the block renders what the filter leaves.
        $shown = array_map(function (array $group) {
            $group['tasks'] = array_values(array_filter($group['tasks'], fn (array $task) => $this->taskVisible($task)));

            return $group;
        }, $this->groups);

        return view('livewire.admin.todo', [
            'shown' => $shown,
            'chips' => [
                'all' => __('admin.todo_filter_all'),
                'open' => __('admin.todo_filter_open'),
                'done' => __('admin.todo_filter_done'),
            ],
            'counts' => ['all' => $fmt(count($tasks)), 'open' => $fmt($open), 'done' => $fmt($done)],
            'leftText' => str_replace(':count', $fmt($open), __('admin.todo_left')),
            'total' => count($tasks),
            'doneCount' => $done,
            'locale' => $locale,
        ]);
    }

    /** Whether a task shows under the active filter. */
    private function taskVisible(array $task): bool
    {
        return match ($this->filter) {
            'open' => ! $task['done'],
            'done' => (bool) $task['done'],
            default => true,
        };
    }
}
