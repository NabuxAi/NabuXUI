<?php

namespace App\Filament\Pages;

use App\Models\Order;
use App\Models\Product;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Carbon;
use NabuXUI\NabuXUI;
use UnitEnum;

/**
 * تحلیل‌ها — three views of the shop's live Order/Product data on one panel
 * page: the order-status donut, the last-seven-days sales bars, and the
 * paid-revenue total card with its sparkline. The numbers are queried fresh
 * on every render (the same contract the Dashboard overview follows); the
 * charts are the package's own nx components fed server-side, and every
 * figure the page writes itself goes through NabuXUI::formatNumber so the
 * digits follow the panel locale.
 */
class Analytics extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $navigationLabel = 'تحلیل‌ها';

    protected static ?string $title = 'تحلیل‌ها';

    protected static string|UnitEnum|null $navigationGroup = 'ابزارها';

    /** Order statuses in ring order, with their Persian legend labels. */
    protected const STATUS_LABELS = [
        'pending' => 'در انتظار',
        'paid' => 'پرداخت‌شده',
        'shipped' => 'ارسال‌شده',
        'cancelled' => 'لغو',
    ];

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            View::make('filament.pages.analytics')
                ->viewData(fn (): array => $this->analyticsData()),
        ]);
    }

    /**
     * Everything the overview blade needs, queried fresh on every render so
     * the numbers are never stale.
     *
     * @return array<string, mixed>
     */
    protected function analyticsData(): array
    {
        $statusCounts = Order::query()
            ->selectRaw('status, count(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        // Every status gets a legend row even when its count is zero, so the
        // donut's share column never reshuffles between renders.
        $donutData = [];
        foreach (Order::STATUSES as $status) {
            $donutData[self::STATUS_LABELS[$status] ?? $status] = (int) $statusCounts->get($status, 0);
        }

        $paidOrders = Order::query()->where('status', 'paid')->get();
        $paidTotal = (float) $paidOrders->sum('total');
        $week = $this->weekRevenue();

        $format = fn (float|int $value): string => NabuXUI::formatNumber($value);

        return [
            'totalCard' => [
                'label' => 'جمع فروش پرداخت‌شده',
                'value' => $format($paidTotal),
                'trend' => $week['values'],
                'trendUp' => end($week['values']) >= $week['values'][0],
                'caption' => sprintf(
                    '%s سفارش پرداخت‌شده · %s محصول فعال',
                    $format($paidOrders->count()),
                    $format(Product::query()->where('status', 'active')->count()),
                ),
            ],
            'donut' => [
                'title' => 'وضعیت سفارش‌ها',
                'subtitle' => 'سهم هر وضعیت از کل سفارش‌های فروشگاه',
                'data' => $donutData,
                'centerLabel' => 'سفارش',
            ],
            'bars' => [
                'title' => 'فروش ۷ روز اخیر',
                'subtitle' => 'جمع روزانهٔ سفارش‌های پرداخت‌شده',
                'data' => $week['series'],
            ],
        ];
    }

    /**
     * The paid revenue of the seven days back through today, as the bar
     * chart's label => value series (day names in the panel's locale) and
     * the plain value list behind the total card's sparkline. Bucketing
     * happens in PHP over the fetched rows, so the numbers come out the
     * same on every DB driver.
     *
     * @return array{series: array<string, float>, values: list<float>}
     */
    protected function weekRevenue(): array
    {
        $start = now()->subDays(6)->startOfDay();

        $rows = Order::query()
            ->where('status', 'paid')
            ->where('ordered_at', '>=', $start)
            ->get(['total', 'ordered_at']);

        $buckets = [];
        foreach ($rows as $row) {
            $day = Carbon::parse($row->ordered_at)->toDateString();
            $buckets[$day] = ($buckets[$day] ?? 0) + (float) $row->total;
        }

        $series = [];
        $cursor = $start->copy();
        for ($i = 0; $i < 7; $i++) {
            $series[$cursor->copy()->locale(app()->getLocale())->translatedFormat('l')] =
                $buckets[$cursor->toDateString()] ?? 0.0;
            $cursor->addDay();
        }

        return ['series' => $series, 'values' => array_values($series)];
    }
}
