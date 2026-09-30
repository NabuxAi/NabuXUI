<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\AdminPanel;
use App\Support\Panel;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use NabuXUI\NabuXUI;

/**
 * /admin — the panel's home: a welcome card over a decorative backdrop, stat
 * cards with rolling numbers, a metric chart, a dot-matrix chart, the activity
 * stream and a usage card. All numbers roll with the locale's digits.
 */
#[Layout('layouts.admin')]
class Dashboard extends Component
{
    use AdminPanel;

    public function mount(): void
    {
        $this->rememberLocale();
    }

    /** The welcome card's secondary CTA. */
    public function inviteTeammates(): void
    {
        $this->toast(__('admin.welcome_invite_toast'), tone: 'success');
    }

    public function render()
    {
        $fmt = fn (int $n) => NabuXUI::formatNumber($n, 0, app()->getLocale());
        $say = fn (string $key, array $replace = []) => __($key, $replace);

        return view('livewire.admin.dashboard', [
            'stats' => [
                ['label' => __('admin.stat_users'), 'value' => 12480, 'delta' => 8.2, 'trend' => [42, 48, 45, 53, 58, 61, 67, 72], 'caption' => __('admin.stat_users_caption')],
                ['label' => __('admin.stat_orders'), 'value' => 486, 'delta' => -3.1, 'trend' => [38, 35, 41, 33, 30, 34, 29, 27], 'caption' => __('admin.stat_orders_caption')],
                ['label' => __('admin.stat_revenue'), 'value' => 612000000, 'delta' => 12.4, 'trend' => [21, 26, 24, 31, 36, 34, 44, 52], 'caption' => __('admin.stat_revenue_caption')],
                ['label' => __('admin.stat_tickets'), 'value' => 37, 'delta' => -14.8, 'trend' => [61, 55, 52, 44, 41, 39, 38, 37], 'caption' => __('admin.stat_tickets_caption')],
            ],
            'monthLabels' => array_map(fn (Carbon $m) => Panel::monthLabel($m), [
                now()->subMonths(5), now()->subMonths(4), now()->subMonths(3), now()->subMonths(2), now()->subMonths(1), now(),
            ]),
            'metrics' => [
                ['id' => 'revenue', 'label' => __('admin.metric_revenue').' ('.__('admin.metric_revenue_unit').')', 'values' => [412, 448, 431, 502, 548, 612], 'format' => ['maximumFractionDigits' => 0], 'delta' => 12.4],
                ['id' => 'users', 'label' => __('admin.metric_users'), 'values' => [640, 712, 690, 804, 918, 1032], 'format' => ['maximumFractionDigits' => 0], 'delta' => 9.7],
                ['id' => 'conversion', 'label' => __('admin.metric_conversion'), 'values' => [0.031, 0.036, 0.033, 0.038, 0.042, 0.045], 'format' => ['style' => 'percent', 'maximumFractionDigits' => 1], 'delta' => 7.1],
            ],
            'matrix' => [
                'labels' => ['EN', 'ES', 'FA', 'AR', 'DE'],
                'series' => [
                    ['name' => __('admin.matrix_solved'), 'values' => [640, 420, 760, 310, 280]],
                    ['name' => __('admin.matrix_human'), 'values' => [140, 90, 210, 70, 60]],
                ],
            ],
            'timeline' => [
                ['id' => 't1', 'actor' => __('admin.person_1'), 'text' => $say('admin.activity_confirmed', ['target' => '#'.$fmt(1248)]), 'time' => now()->subMinutes(3), 'tone' => 'success'],
                ['id' => 't2', 'icon' => 'upload', 'text' => __('admin.activity_backup'), 'time' => now()->subHours(2), 'tone' => 'info'],
                ['id' => 't3', 'actor' => __('admin.person_3'), 'text' => $say('admin.activity_rejected', ['target' => '#'.$fmt(9801)]), 'time' => now()->subHours(6), 'tone' => 'warning'],
                ['id' => 't4', 'icon' => 'user', 'actor' => __('admin.person_3'), 'text' => __('admin.activity_joined'), 'time' => now()->subDays(1), 'tone' => 'success'],
                ['id' => 't5', 'actor' => __('admin.person_2'), 'text' => $say('admin.activity_shipped', ['target' => $fmt(201)]), 'time' => now()->subDays(2), 'tone' => 'info'],
            ],
            'usage' => [
                'limit' => 100,
                'categories' => [
                    __('admin.usage_cat_api') => 46.2,
                    __('admin.usage_cat_assets') => 18.4,
                    __('admin.usage_cat_embeddings') => 9.1,
                    __('admin.usage_cat_logs') => 4.5,
                ],
            ],
            'activity' => Panel::activity(),
        ]);
    }
}
