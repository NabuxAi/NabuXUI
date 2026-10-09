<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\AdminPanel;
use App\Support\Locales;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use NabuXUI\NabuXUI;

/**
 * /admin/orders — the shop's order desk: the data-table block's markup grown
 * a status-badge column and a track button (search + status chips, sorting
 * server-side through sortBy so rows glide after the morph). Each row's
 * button opens the order-tracking dialog: the shipment's four steps (state
 * derived from the order's status), the event log stitched from the steps
 * that already happened, and the facts — items, recipient, the Jalali placed
 * date and the ETA. Words come from the admin.orders_* keys.
 */
#[Layout('layouts.admin')]
class Orders extends Component
{
    use AdminPanel;

    /** Free-text search over number, customer, items and status (live, debounced in the view). */
    public string $search = '';

    /** The status chips' value: 'all' | pending | paid | processing | shipped | delivered | canceled. */
    public string $status = 'all';

    /** The table's sort state; its headers call sortBy(key, direction). */
    public array $sort = ['key' => 'placed', 'direction' => 'descending'];

    /** The order being tracked (its id); empty when the dialog is closed. */
    public string $tracking = '';

    /** The tracking dialog (x-nx::dialog wire:model). */
    public bool $trackingOpen = false;

    /**
     * The shared trait's updated() hook types its value ?string, which our
     * array props (sort) crash against — same behaviour for the locale
     * switch, but through a wider door. (Class methods win over the trait's.)
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
    }

    /** The data-table block's headers land here (server-side sorting, FLIP after the morph). */
    public function sortBy(string $key, string $direction): void
    {
        if (! in_array($key, ['number', 'customer', 'items', 'total', 'status', 'placed'], true)) {
            return;
        }

        $this->sort = ['key' => $key, 'direction' => $direction === 'descending' ? 'descending' : 'ascending'];
    }

    /** A row's track button: remember the order and raise the dialog. */
    public function track(string $id): void
    {
        if ($this->idOf($id) === null) {
            return;
        }

        $this->tracking = $id;
        $this->trackingOpen = true;
    }

    public function render()
    {
        $locale = str_replace('_', '-', app()->getLocale());
        $fmt = fn ($value) => NabuXUI::formatNumber((float) $value, 0, $locale);
        // Order numbers are identifiers, not quantities — map the digits
        // without the group separators formatNumber would add.
        $digits = array_combine(range(0, 9), NabuXUI::digits($locale));
        $num = fn (string $id) => strtr($id, $digits);

        $labels = $this->statusLabels();
        $tones = ['pending' => 'warning', 'paid' => 'gold', 'processing' => 'accent', 'shipped' => 'info', 'delivered' => 'success', 'canceled' => 'danger'];
        $weights = ['pending' => 0, 'paid' => 1, 'processing' => 2, 'shipped' => 3, 'delivered' => 4, 'canceled' => 5];

        $all = $this->orders();

        // The chips' counts: how many orders sit in each status.
        $counts = ['all' => count($all)];
        foreach (array_keys($labels) as $id) {
            $counts[$id] = count(array_filter($all, fn (array $order) => $order['status'] === $id));
        }

        // Search + chips, then the sort the headers asked for.
        $q = mb_strtolower(trim($this->search));
        $rows = array_values(array_filter($all, function (array $order) use ($q, $labels) {
            if ($this->status !== 'all' && $order['status'] !== $this->status) {
                return false;
            }
            if ($q === '') {
                return true;
            }

            $haystack = mb_strtolower(implode(' ', [
                $order['id'], $order['customer'], $order['itemsText'], $labels[$order['status']],
            ]));

            return str_contains($haystack, $q);
        }));

        $key = in_array($this->sort['key'], ['number', 'customer', 'items', 'total', 'status', 'placed'], true) ? $this->sort['key'] : 'placed';
        $down = $this->sort['direction'] === 'descending';
        usort($rows, function (array $a, array $b) use ($key, $down, $weights) {
            $value = match ($key) {
                'number' => fn (array $o) => (int) $o['no'],
                'customer' => fn (array $o) => mb_strtolower($o['customer']),
                'items' => fn (array $o) => (int) $o['itemsCount'],
                'total' => fn (array $o) => (int) $o['total'],
                'status' => fn (array $o) => $weights[$o['status']],
                default => fn (array $o) => (int) $o['placed']->getTimestamp(),
            };

            return ($value($a) <=> $value($b)) * ($down ? -1 : 1);
        });

        $display = [];
        foreach (array_values($rows) as $i => $order) {
            $display[] = [
                'id' => $order['id'],
                'number' => $num($order['id']),
                'numberSort' => (int) $order['no'],
                'customer' => $order['customer'],
                'items' => str_replace(':count', $fmt($order['itemsCount']), __('admin.orders_track_items')),
                'itemsCount' => (int) $order['itemsCount'],
                'itemsText' => $order['itemsText'],
                'total' => $fmt($order['total']),
                'totalSort' => (int) $order['total'],
                'status' => $labels[$order['status']],
                'statusSort' => $weights[$order['status']],
                'statusTone' => $tones[$order['status']],
                'date' => $this->dateLabel($order['placed']),
                'dateSort' => $order['placed']->getTimestamp(),
                'index' => $i,
            ];
        }

        return view('livewire.admin.orders', [
            'rows' => $display,
            'columns' => [
                ['key' => 'number', 'label' => __('admin.orders_column_number'), 'sortable' => true],
                ['key' => 'customer', 'label' => __('admin.orders_column_customer'), 'sortable' => true],
                ['key' => 'items', 'label' => __('admin.orders_column_items'), 'sortable' => true],
                ['key' => 'total', 'label' => __('admin.orders_column_total'), 'sortable' => true, 'align' => 'end', 'numeric' => true],
                ['key' => 'status', 'label' => __('admin.orders_column_status'), 'sortable' => true],
                ['key' => 'placed', 'label' => __('admin.orders_column_date'), 'sortable' => true],
            ],
            'sort' => ['key' => $key, 'direction' => $down ? 'descending' : 'ascending'],
            'chips' => ['all' => __('admin.orders_filter_all')] + $labels,
            'counts' => array_map(fn ($count) => $fmt($count), $counts),
            'countText' => str_replace(':count', $fmt(count($rows)), __('admin.orders_count')),
            'track' => $this->tracking !== '' ? $this->trackingPayload($this->tracking, $labels, $num) : null,
            'locale' => $locale,
        ]);
    }

    /** @return array<string, string> status id → label, in weight order. */
    private function statusLabels(): array
    {
        return [
            'pending' => __('admin.orders_status_pending'),
            'paid' => __('admin.orders_status_paid'),
            'processing' => __('admin.orders_status_processing'),
            'shipped' => __('admin.orders_status_shipped'),
            'delivered' => __('admin.orders_status_delivered'),
            'canceled' => __('admin.orders_status_canceled'),
        ];
    }

    /** The order with this id, or null. */
    private function idOf(string $id): ?array
    {
        foreach ($this->orders() as $order) {
            if ($order['id'] === $id) {
                return $order;
            }
        }

        return null;
    }

    /**
     * The dialog's payload for one order: the badge's job status, the four
     * shipment steps with their states (times only where they happened), the
     * event log stitched from the completed steps and the facts row.
     *
     * @param  array<string, string>  $labels
     * @param  callable(string): string  $num
     * @return array<string, mixed>
     */
    private function trackingPayload(string $id, array $labels, callable $num): array
    {
        $order = $this->idOf($id);
        if ($order === null) {
            return [];
        }

        $status = $order['status'];
        // The badge speaks in job statuses: queued while it waits, running on
        // the road, success when it landed, canceled speaks for itself.
        $badges = ['pending' => 'queued', 'paid' => 'queued', 'processing' => 'running', 'shipped' => 'running', 'delivered' => 'success', 'canceled' => 'canceled'];
        // How far the shipment is: confirmed → packed → shipped → delivered.
        $stage = ['pending' => 0, 'paid' => 1, 'processing' => 1, 'shipped' => 2, 'delivered' => 3, 'canceled' => 0][$status];
        // Each step's moment, counted from the order's own placed time.
        $moments = [
            $order['placed'],
            $order['placed']->copy()->addHours(6),
            $order['placed']->copy()->addDay(),
            $order['placed']->copy()->addDays(2),
        ];

        $titles = [
            __('admin.orders_track_step_confirmed'),
            __('admin.orders_track_step_packed'),
            __('admin.orders_track_step_shipped'),
            __('admin.orders_track_step_delivered'),
        ];
        $icons = ['file', 'layers', 'zap', 'home'];

        $steps = [];
        $events = [];
        foreach ($titles as $i => $title) {
            $state = 'upcoming';
            if ($status === 'canceled') {
                // A canceled order got confirmed, then stopped.
                $state = $i === 0 ? 'complete' : 'upcoming';
            } elseif ($i < $stage) {
                $state = 'complete';
            } elseif ($i === $stage) {
                $state = 'current';
            }

            $step = ['id' => 's'.$i, 'title' => $title, 'icon' => $icons[$i], 'state' => $state];
            if ($state === 'complete') {
                // Only what already happened carries a time; the rest are still waiting.
                $step['time'] = $moments[$i];
                $events[] = ['id' => 'e'.$i, 'title' => $title, 'time' => $moments[$i], 'tone' => 'success'];
            }
            $steps[] = $step;
        }

        // The ETA: the landing date while it travels, the real one once it landed.
        $eta = match ($status) {
            'delivered' => $this->dateLabel($moments[3]),
            'canceled' => null,
            default => $this->dateLabel($order['placed']->copy()->addDays(3)),
        };

        return [
            'id' => $order['id'],
            'number' => $num($order['id']),
            'title' => str_replace(':number', $num($order['id']), __('admin.orders_track_title')),
            'statusLabel' => $labels[$status],
            'badge' => $badges[$status],
            'steps' => $steps,
            'events' => $events,
            'itemsLabel' => str_replace(':count', NabuXUI::formatNumber($order['itemsCount'], 0, str_replace('_', '-', app()->getLocale())), __('admin.orders_track_items')),
            'itemsText' => $order['itemsText'],
            'recipient' => $order['customer'],
            'placed' => $this->dateLabel($order['placed']),
            'eta' => $eta,
        ];
    }

    /**
     * A date the way the panel's calendar shows it — Jalali for fa, Gregorian
     * otherwise (the same trick as Panel::monthLabel, one day-pattern up).
     */
    private function dateLabel(Carbon $date): string
    {
        $locale = app()->getLocale();

        if (class_exists(\IntlDateFormatter::class)) {
            $calendar = null;
            if ($locale === 'fa' && class_exists(\IntlCalendar::class)) {
                $calendar = \IntlCalendar::createInstance('UTC', 'fa@calendar=persian');
            }

            $pattern = $locale === 'fa' ? 'y/MM/dd' : 'MMM d, y';
            $format = new \IntlDateFormatter($locale, \IntlDateFormatter::NONE, \IntlDateFormatter::NONE, 'UTC', $calendar, $pattern);

            return (string) $format->format($date->getTimestamp());
        }

        return $date->locale($locale)->isoFormat('MMM D, Y');
    }

    /**
     * Six sample orders walking the whole status line — newest first, numbers
     * counting back from the language file's own order, customers and item
     * lines from the orders_customer_* / orders_items_* keys.
     *
     * @return array<int, array<string, mixed>>
     */
    private function orders(): array
    {
        $customer = fn (int $i) => __("admin.orders_customer_{$i}");

        return [
            ['id' => '1404-1029', 'no' => 1029, 'customer' => $customer(1), 'itemsKey' => 2, 'itemsCount' => 1, 'itemsText' => __('admin.orders_items_2'),
                'total' => 2190000, 'status' => 'pending', 'placed' => now()->subHours(3)],
            ['id' => '1404-1027', 'no' => 1027, 'customer' => $customer(4), 'itemsKey' => 4, 'itemsCount' => 2, 'itemsText' => __('admin.orders_items_4'),
                'total' => 3870000, 'status' => 'paid', 'placed' => now()->subDay()],
            ['id' => '1404-1024', 'no' => 1024, 'customer' => $customer(1), 'itemsKey' => 1, 'itemsCount' => 2, 'itemsText' => __('admin.orders_items_1'),
                'total' => 4120000, 'status' => 'processing', 'placed' => now()->subDays(2)],
            ['id' => '1404-1025', 'no' => 1025, 'customer' => $customer(2), 'itemsKey' => 2, 'itemsCount' => 1, 'itemsText' => __('admin.orders_items_2'),
                'total' => 2190000, 'status' => 'shipped', 'placed' => now()->subDays(3)],
            ['id' => '1404-1028', 'no' => 1028, 'customer' => $customer(2), 'itemsKey' => 1, 'itemsCount' => 2, 'itemsText' => __('admin.orders_items_1'),
                'total' => 4120000, 'status' => 'canceled', 'placed' => now()->subDays(5)],
            ['id' => '1404-1026', 'no' => 1026, 'customer' => $customer(3), 'itemsKey' => 3, 'itemsCount' => 2, 'itemsText' => __('admin.orders_items_3'),
                'total' => 1540000, 'status' => 'delivered', 'placed' => now()->subDays(6)],
        ];
    }
}
