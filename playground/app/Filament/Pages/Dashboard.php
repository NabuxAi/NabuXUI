<?php

namespace App\Filament\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\Task;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use NabuXUI\NabuXUI;

/**
 * The panel's home (/filament) in the Nabu admin mood: an aurora hero that
 * greets by the hour of day, over four stat cards of live database numbers
 * (orders by status, paid revenue, active products, open tasks). Under the
 * cards sit two live widgets: the six newest orders as a link-to-the-row
 * table, and the open tasks on an nx-todo board whose checkbox ticks write
 * straight to the database through toggleTask(). The sparklines are
 * NabuXUI's server-rendered paths and every figure goes through
 * NabuXUI::formatNumber, so digits follow the panel locale. The panel's own
 * widgets (account, info) still render below, via the inherited widget grid.
 */
class Dashboard extends BaseDashboard
{
    public function content(Schema $schema): Schema
    {
        return $schema->components([
            View::make('filament.pages.dashboard-overview')
                ->viewData(fn (): array => $this->overviewData()),
            $this->getWidgetsContentComponent(),
        ]);
    }

    /**
     * The todo board's tick: the checkbox writes the task's status to the
     * database, so the strike-through survives reloads. Unchecking a done
     * task sends it back to the todo pile. The page re-renders afterwards,
     * so the stat card's open count follows along.
     */
    public function toggleTask(string $task, bool $done): void
    {
        Task::query()->whereKey((int) $task)->update([
            'status' => $done ? 'done' : 'todo',
        ]);
    }

    /**
     * Everything the overview blade needs, queried fresh on every render so
     * the numbers are never stale.
     *
     * @return array<string, mixed>
     */
    protected function overviewData(): array
    {
        $ordersByStatus = Order::query()
            ->selectRaw('status, count(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $paidOrders = Order::query()->where('status', 'paid')->get();
        $paidTotal = (float) $paidOrders->sum('total');

        $productsByCategory = Product::query()
            ->selectRaw('category, count(*) as aggregate')
            ->groupBy('category')
            ->orderByDesc('aggregate')
            ->pluck('aggregate', 'category');

        $openTasks = Task::query()->where('status', '!=', 'done')->get();
        $openByStatus = $openTasks->groupBy('status')->map->count();

        $format = fn (float|int $value): string => NabuXUI::formatNumber($value);

        // The live widgets: the six newest orders as rows (each one links
        // into its resource page), and the whole task board split into an
        // open pile and a struck-through done pile. The done pile sorts by
        // updated_at, so the task ticked a moment ago is always on top.
        $recentOrders = Order::query()
            ->orderByDesc('ordered_at')
            ->orderByDesc('id')
            ->limit(6)
            ->get(['id', 'number', 'customer_name', 'status', 'total'])
            ->map(fn (Order $order): array => [
                'id' => $order->getKey(),
                'number' => $order->number,
                'customer' => $order->customer_name,
                'url' => OrderResource::getUrl('view', ['record' => $order->getKey()]),
                'badge' => match ($order->status) {
                    'paid' => 'success',
                    'shipped' => 'running',
                    'cancelled' => 'canceled',
                    default => 'queued',
                },
                'statusLabel' => match ($order->status) {
                    'pending' => 'در انتظار پرداخت',
                    'paid' => 'پرداخت‌شده',
                    'shipped' => 'ارسال‌شده',
                    'cancelled' => 'لغوشده',
                    default => $order->status,
                },
                'total' => (float) $order->total,
            ])
            ->all();

        $taskGroups = [
            [
                'id' => 'open',
                'title' => 'باز',
                'tone' => 'info',
                'tasks' => Task::query()
                    ->whereIn('status', ['todo', 'doing'])
                    ->orderBy('due_at')
                    ->orderBy('id')
                    ->get(['id', 'title'])
                    ->map(fn (Task $task): array => ['id' => (string) $task->getKey(), 'title' => $task->title, 'done' => false])
                    ->all(),
            ],
            [
                'id' => 'done',
                'title' => 'انجام‌شده',
                'tone' => 'success',
                'tasks' => Task::query()
                    ->where('status', 'done')
                    ->orderByDesc('updated_at')
                    ->get(['id', 'title'])
                    ->map(fn (Task $task): array => ['id' => (string) $task->getKey(), 'title' => $task->title, 'done' => true])
                    ->all(),
            ],
        ];

        return [
            'greeting' => $this->greeting(),
            'tagline' => 'نمای کلی سفارش‌ها، درآمد و کارهای باز فروشگاه در یک نگاه.',
            'statsLabel' => 'آمار فروشگاه',
            'cards' => [
                [
                    'label' => 'سفارش‌ها',
                    'icon' => 'layers',
                    'value' => (int) $ordersByStatus->sum(),
                    'trend' => $this->dayTrend(Order::query(), 'ordered_at'),
                    'caption' => sprintf(
                        'در انتظار %s · پرداخت‌شده %s · ارسال‌شده %s · لغو %s',
                        $format($ordersByStatus->get('pending', 0)),
                        $format($ordersByStatus->get('paid', 0)),
                        $format($ordersByStatus->get('shipped', 0)),
                        $format($ordersByStatus->get('cancelled', 0)),
                    ),
                ],
                [
                    'label' => 'درآمد پرداخت‌شده',
                    'icon' => 'chart',
                    'value' => $paidTotal,
                    'trend' => $this->dayTrend(
                        Order::query()->where('status', 'paid'),
                        'ordered_at',
                        'total',
                    ),
                    'caption' => $format($paidOrders->count()).' سفارش پرداخت‌شده',
                ],
                [
                    'label' => 'محصولات فعال',
                    'icon' => 'grid',
                    'value' => Product::query()->where('status', 'active')->count(),
                    // The sparkline here is the shape of the catalog: product
                    // count per category, largest first.
                    'trend' => $productsByCategory->values()->all(),
                    'caption' => sprintf(
                        'از %s محصول در %s دسته',
                        $format(Product::query()->count()),
                        $format($productsByCategory->count()),
                    ),
                ],
                [
                    'label' => 'تسک‌های باز',
                    'icon' => 'check-circle',
                    'value' => $openTasks->count(),
                    'trend' => $this->dayTrend(Task::query(), 'due_at', start: now()->startOfDay()),
                    'caption' => sprintf(
                        'در انتظار %s · در حال انجام %s · سررسید هفتهٔ پیش‌رو',
                        $format($openByStatus->get('todo', 0)),
                        $format($openByStatus->get('doing', 0)),
                    ),
                ],
            ],
            'recentOrders' => $recentOrders,
            'recentOrdersLabel' => 'سفارش‌های اخیر',
            'recentOrdersCaption' => '۶ سفارش آخر، مرتب بر اساس زمان سفارش',
            'allOrdersLabel' => 'همهٔ سفارش‌ها',
            'taskGroups' => $taskGroups,
            'tasksLabel' => 'تسک‌های باز',
            'tasksCaption' => sprintf(
                'باز %s · انجام‌شده %s — تیک هر تسک وضعیتش را ذخیره می‌کند',
                $format($openTasks->count()),
                $format(Task::query()->where('status', 'done')->count()),
            ),
        ];
    }

    /** The hello follows the clock of the panel's timezone. */
    protected function greeting(): string
    {
        $hour = now()->hour;

        return match (true) {
            $hour >= 5 && $hour < 12 => 'صبح بخیر',
            $hour >= 12 && $hour < 17 => 'ظهر بخیر',
            $hour >= 17 && $hour < 20 => 'عصر بخیر',
            default => 'شب بخیر',
        };
    }

    /**
     * Seven sparkline slots over a one-week window: the six days back through
     * today by default, or the week ahead when a $start is given. Each slot
     * sums $valueColumn for rows on that day (e.g. the paid revenue), or
     * simply counts the rows when no value column is given. The bucketing
     * happens in PHP over the fetched rows, so the numbers come out the
     * same on every DB driver.
     *
     * @param  Builder<\Illuminate\Database\Eloquent\Model>  $query
     * @return list<float|int>
     */
    protected function dayTrend(Builder $query, string $dateColumn, ?string $valueColumn = null, ?Carbon $start = null): array
    {
        $start ??= now()->subDays(6)->startOfDay();

        $rows = (clone $query)
            ->where($dateColumn, '>=', $start->copy()->startOfDay())
            ->get();

        $buckets = [];
        foreach ($rows as $row) {
            $day = Carbon::parse($row->{$dateColumn})->toDateString();
            $buckets[$day] = ($buckets[$day] ?? 0) + ($valueColumn !== null ? (float) $row->{$valueColumn} : 1);
        }

        $trend = [];
        $cursor = $start->copy();
        for ($i = 0; $i < 7; $i++) {
            $trend[] = $buckets[$cursor->toDateString()] ?? 0;
            $cursor->addDay();
        }

        return $trend;
    }
}
