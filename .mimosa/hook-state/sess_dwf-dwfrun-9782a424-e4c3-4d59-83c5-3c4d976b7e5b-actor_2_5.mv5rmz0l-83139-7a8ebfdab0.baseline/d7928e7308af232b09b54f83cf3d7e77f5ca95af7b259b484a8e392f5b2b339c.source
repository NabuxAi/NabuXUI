<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\AdminPanel;
use App\Support\Panel;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use NabuXUI\NabuXUI;

/**
 * /admin/analytics — growth at a glance: four stat cards with sparklines,
 * the metric chart over the last six months (Jalali labels for fa via
 * Panel::monthLabel), a traffic analytics-card over three periods, a plan
 * comparison table and the usage card. Every number rolls with the locale's
 * digits; words come from admin.* keys, the few gaps from $say(en, fa).
 */
#[Layout('layouts.admin')]
class Analytics extends Component
{
    use AdminPanel;

    public function mount(): void
    {
        $this->rememberLocale();
    }

    public function render()
    {
        $locale = str_replace('_', '-', app()->getLocale());
        $fa = app()->getLocale() === 'fa';
        $say = fn (string $en, string $faText) => $fa ? $faText : $en;
        $fmt = fn ($value, int $digits = 0) => NabuXUI::formatNumber((float) $value, $digits, $locale);

        // The traffic card's bar labels: weekday names for the 7-day window,
        // day-of-month for 30 days, "d MMM" week starts for the 90-day one.
        $pattern = fn (string $p) => new \IntlDateFormatter($locale, \IntlDateFormatter::NONE, \IntlDateFormatter::NONE, 'UTC', null, $p);
        $weekday = fn (Carbon $day) => (string) $pattern('EEE')->format($day->getTimestamp());
        $short = fn (Carbon $day) => (string) $pattern('d MMM')->format($day->getTimestamp());

        $days = fn (int $count) => array_map(fn (int $i) => now()->utc()->subDays($count - 1 - $i), range(0, $count - 1));

        $week = [4820, 5110, 4960, 5340, 5210, 5560, 7416];
        $month = [5180, 4920, 5310, 5460, 5240, 3980, 3510, 5290, 5130, 5420, 5580, 5320, 4010, 3620, 5210, 5380, 5490, 5260, 5330, 4080, 3720, 5470, 5610, 5540, 5680, 5720, 4310, 3960, 5840, 7416];
        $quarter = array_map(fn (int $i) => [11840, 12420, 12110, 12980, 12640, 13310, 12480, 13860, 13520, 14240, 13980, 14810, 16492][$i], range(0, 12));

        $traffic = [
            [
                'id' => '7d', 'label' => $say('7d', '۷ روز'), 'delta' => 6.8, 'values' => $week,
                'labels' => array_map($weekday, $days(7)), 'caption' => $say('vs the previous 7 days', 'نسبت به ۷ روز قبل'),
            ],
            [
                'id' => '30d', 'label' => $say('30d', '۳۰ روز'), 'delta' => 4.2, 'values' => $month,
                'labels' => array_map(fn (Carbon $day) => $fmt((int) $day->format('j')), $days(30)), 'caption' => $say('vs the previous 30 days', 'نسبت به ۳۰ روز قبل'),
            ],
            [
                'id' => '90d', 'label' => $say('90d', '۹۰ روز'), 'delta' => -1.9, 'values' => $quarter,
                'labels' => array_map($short, array_map(fn (int $i) => now()->utc()->subWeeks(12 - $i)->startOfWeek(), range(0, 12))), 'caption' => $say('vs the previous quarter', 'نسبت به فصل قبل'),
            ],
        ];
        foreach ($traffic as &$period) {
            $period['value'] = array_sum($period['values']);
        }
        unset($period);

        return view('livewire.admin.analytics', [
            'stats' => [
                ['label' => __('admin.analytics_label_revenue'), 'value' => 8240000, 'decimals' => 0, 'suffix' => null, 'delta' => 12.4, 'trend' => [588, 604, 592, 641, 668, 655, 701, 712], 'caption' => __('admin.stat_revenue_caption')],
                ['label' => __('admin.analytics_label_users'), 'value' => 12480, 'decimals' => 0, 'suffix' => null, 'delta' => 8.2, 'trend' => [918, 972, 948, 1016, 1064, 1042, 1112, 1148], 'caption' => __('admin.stat_users_caption')],
                ['label' => __('admin.analytics_label_retention'), 'value' => 86.2, 'decimals' => 1, 'suffix' => '%', 'delta' => 2.3, 'trend' => [82, 83, 81, 84, 85, 84, 85.5, 86.2], 'caption' => __('admin.chart_caption')],
                ['label' => __('admin.analytics_label_traffic'), 'value' => array_sum($month), 'decimals' => 0, 'suffix' => null, 'delta' => 4.2, 'trend' => [5120, 4980, 5310, 5480, 5260, 5560, 5720, 7416], 'caption' => __('admin.chart_caption')],
            ],
            'months' => array_map(fn (Carbon $m) => Panel::monthLabel($m), [
                now()->subMonths(5), now()->subMonths(4), now()->subMonths(3), now()->subMonths(2), now()->subMonths(1), now(),
            ]),
            'metrics' => [
                ['id' => 'revenue', 'label' => __('admin.metric_revenue').' ('.__('admin.metric_revenue_unit').')', 'values' => [498, 534, 517, 588, 641, 712], 'format' => ['maximumFractionDigits' => 0], 'delta' => 11.1],
                ['id' => 'users', 'label' => __('admin.metric_users'), 'values' => [712, 798, 776, 892, 1016, 1148], 'format' => ['maximumFractionDigits' => 0], 'delta' => 13.0],
                ['id' => 'conversion', 'label' => __('admin.metric_conversion'), 'values' => [0.033, 0.037, 0.036, 0.041, 0.044, 0.048], 'format' => ['style' => 'percent', 'maximumFractionDigits' => 1], 'delta' => 9.1],
            ],
            'traffic' => $traffic,
            'plans' => [
                ['id' => 'starter', 'name' => 'Starter', 'price' => '$'.$fmt(0), 'period' => $say('/mo', '/ماه')],
                ['id' => 'growth', 'name' => 'Growth', 'price' => '$'.$fmt(49), 'period' => $say('/mo', '/ماه'), 'action' => ['label' => __('admin.usage_upgrade'), 'href' => route('admin.settings')]],
                ['id' => 'scale', 'name' => 'Scale', 'price' => '$'.$fmt(199), 'period' => $say('/mo', '/ماه'), 'action' => ['label' => __('admin.usage_upgrade'), 'href' => route('admin.settings')]],
            ],
            'features' => [
                ['group' => __('admin.settings_section_workspace'), 'label' => __('admin.usage_cat_api'), 'values' => ['starter' => $fmt(1).'M', 'growth' => $fmt(10).'M', 'scale' => $fmt(50).'M']],
                ['label' => __('admin.usage_cat_assets'), 'values' => ['starter' => false, 'growth' => true, 'scale' => true]],
                ['label' => __('admin.usage_cat_embeddings'), 'values' => ['starter' => false, 'growth' => false, 'scale' => true]],
                ['label' => __('admin.usage_cat_logs'), 'values' => ['starter' => true, 'growth' => true, 'scale' => true]],
            ],
            'usage' => [
                'limit' => 100,
                'categories' => [
                    __('admin.usage_cat_api') => 58.4,
                    __('admin.usage_cat_assets') => 12.6,
                    __('admin.usage_cat_embeddings') => 8.9,
                    __('admin.usage_cat_logs') => 2.1,
                ],
            ],
        ]);
    }
}
