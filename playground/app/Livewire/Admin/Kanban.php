<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\AdminPanel;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * /admin/kanban — the sprint board over the kanban block with Livewire as
 * the single truth: every drag / three-dot move reports back through
 * moveCard and the quick-add composer through addCard, so the board and its
 * FLIP glides survive reloads. Columns, cards and assignees all come from
 * the panel's admin.* words.
 */
#[Layout('layouts.admin')]
class Kanban extends Component
{
    use AdminPanel;

    /** The board's columns and cards; the kanban block mutates through moveCard/addCard. */
    public array $board = [];

    /**
     * The shared trait's updated() hook types its value ?string, which the
     * board mutations crash against — same behaviour for the locale switch,
     * but through a wider door. (Class methods win over the trait's.)
     */
    public function updated(string $name, mixed $value): void
    {
        if ($name === 'locale' && is_string($value) && in_array($value, ['fa', 'en'], true)) {
            session(['locale' => $value]);
            $this->redirect($this->path);
        }
    }

    public function mount(): void
    {
        $this->rememberLocale();

        $this->board = [
            [
                'id' => 'todo', 'title' => __('admin.kanban_column_todo'), 'tone' => 'info', 'cards' => [
                    ['id' => 'k1', 'title' => __('admin.kanban_card_1'), 'meta' => 'FR-101', 'tone' => 'info', 'assignee' => ['name' => __('admin.person_1')]],
                    ['id' => 'k2', 'title' => __('admin.kanban_card_2'), 'meta' => 'FR-102', 'assignee' => ['name' => __('admin.person_3')]],
                ],
            ],
            [
                'id' => 'doing', 'title' => __('admin.kanban_column_doing'), 'tone' => 'warning', 'cards' => [
                    ['id' => 'k3', 'title' => __('admin.kanban_card_4'), 'meta' => 'FR-103 · v2.1', 'tone' => 'warning', 'assignee' => ['name' => __('admin.users_sample_4')]],
                ],
            ],
            [
                'id' => 'review', 'title' => __('admin.kanban_column_review'), 'tone' => 'accent', 'cards' => [
                    ['id' => 'k4', 'title' => __('admin.kanban_card_3'), 'meta' => 'A11y', 'tone' => 'info', 'assignee' => ['name' => __('admin.person_2')]],
                ],
            ],
            ['id' => 'done', 'title' => __('admin.kanban_column_done'), 'tone' => 'success', 'cards' => []],
        ];
    }

    /** The block's move-action: drop $card from $from into $to at $index (optimistic move already played). */
    public function moveCard(string $card, string $from, string $to, int $index): void
    {
        $moving = null;
        foreach ($this->board as &$column) {
            if ($column['id'] !== $from) {
                continue;
            }
            foreach ($column['cards'] as $i => $item) {
                if ($item['id'] === $card) {
                    $moving = $item;
                    unset($column['cards'][$i]);
                    $column['cards'] = array_values($column['cards']);
                    break;
                }
            }
        }
        unset($column);

        if ($moving === null) {
            return;
        }

        foreach ($this->board as &$column) {
            if ($column['id'] !== $to) {
                continue;
            }
            $cards = array_values($column['cards']);
            array_splice($cards, max(0, min($index, count($cards))), 0, [$moving]);
            $column['cards'] = $cards;
        }
        unset($column);
    }

    /** The block's add-action: the quick-add composer grows a card in place; the signer-in picks it up. */
    public function addCard(string $column, string $title): void
    {
        $title = trim($title);
        if ($title === '') {
            return;
        }

        foreach ($this->board as &$columnRef) {
            if ($columnRef['id'] !== $column) {
                continue;
            }
            $columnRef['cards'][] = ['id' => 'k'.Str::random(6), 'title' => $title, 'assignee' => ['name' => __('admin.user_name')]];
        }
        unset($columnRef);
    }

    public function render()
    {
        return view('livewire.admin.kanban');
    }
}
