<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\AdminPanel;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * /admin/invoice — a stack of sample invoices on the side, the chosen one's
 * document beside it, and a print button that turns the page into the plain
 * paper document (the invoice block carries its own @media print rules; the
 * page's view hides the shell around it). All amounts are computed from the
 * lines by the block itself; the list's totals are the same math here.
 */
#[Layout('layouts.admin')]
class Invoice extends Component
{
    use AdminPanel;

    public string $active = '';

    public function mount(): void
    {
        $this->rememberLocale();
    }

    /** The list's "pick this invoice" row. */
    public function select(string $id): void
    {
        $this->active = $id;
    }

    /** The header's "new invoice" CTA — a toast in this demo. */
    public function createInvoice(): void
    {
        $this->toast(__('admin.invoice_new_toast'), tone: 'success');
    }

    public function render()
    {
        $invoices = $this->invoices();
        $current = collect($invoices)->firstWhere('id', $this->active) ?? $invoices[0];

        return view('livewire.admin.invoice', [
            'invoices' => $invoices,
            'current' => $current,
            'statusTones' => [
                'paid' => 'success',
                'unpaid' => 'warning',
                'overdue' => 'danger',
                'draft' => 'neutral',
            ],
            'statusLabels' => [
                'paid' => __('admin.invoice_status_paid'),
                'unpaid' => __('admin.invoice_status'),
                'overdue' => __('admin.invoice_status_overdue'),
                'draft' => __('admin.invoice_status_draft'),
            ],
        ]);
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
     * Four sample documents around the language file's invoice: the canonical
     * one awaiting payment, a paid one, an overdue one and a draft. Numbers
     * walk backwards from the language key's own number.
     *
     * @return array<int, array<string, mixed>>
     */
    private function invoices(): array
    {
        $base = __('admin.invoice_number');
        preg_match('/(\d+)\s*$/', $base, $match);
        $pad = strlen($match[1] ?? '082');
        $start = (int) ($match[1] ?? 82);

        $from = [
            'name' => __('admin.invoice_from_name'),
            'lines' => [__('admin.invoice_from_line_1'), __('admin.invoice_from_line_2')],
        ];
        $to = fn (string $name) => [
            'name' => $name,
            'lines' => [__('admin.invoice_to_line_1'), __('admin.invoice_to_line_2')],
        ];

        $design = [
            'title' => __('admin.invoice_line_1'),
            'description' => __('admin.invoice_line_1_desc'),
            'quantity' => 1,
            'unitPrice' => 480_000_000,
        ];
        $support = fn (float $quantity) => [
            'title' => __('admin.invoice_line_2'),
            'quantity' => $quantity,
            'unitPrice' => 25_000_000,
        ];
        $training = fn (float $quantity) => [
            'title' => __('admin.invoice_line_3'),
            'quantity' => $quantity,
            'unitPrice' => 18_000_000,
        ];

        /** @var array<int, array<string, mixed>> $samples */
        $samples = [
            [
                'status' => 'unpaid',
                'to' => $to(__('admin.invoice_to_name')),
                'issue' => now()->startOfDay(),
                'due' => now()->addDays(20),
                'lines' => [$design, $support(1.0), $training(1.0)],
            ],
            [
                'status' => 'paid',
                'to' => $to(__('admin.users_sample_1')),
                'issue' => now()->subDays(24)->startOfDay(),
                'due' => now()->subDays(6)->startOfDay(),
                'lines' => [$support(3.0)],
            ],
            [
                'status' => 'overdue',
                'to' => $to(__('admin.users_sample_2')),
                'issue' => now()->subDays(40)->startOfDay(),
                'due' => now()->subDays(10)->startOfDay(),
                'lines' => [$training(2.0)],
            ],
            [
                'status' => 'draft',
                'to' => $to(__('admin.users_sample_3')),
                'issue' => now()->startOfDay(),
                'due' => now()->addDays(14),
                'lines' => [$design],
            ],
        ];

        return array_map(function (array $sample, int $i) use ($base, $pad, $start, $from) {
            $subtotal = 0.0;
            foreach ($sample['lines'] as $line) {
                $subtotal += $line['amount'] ?? ($line['quantity'] ?? 1) * ($line['unitPrice'] ?? 0);
            }

            return $sample + [
                'id' => 'inv-'.sprintf('%0'.$pad.'d', $start - $i),
                'number' => preg_replace('/\d+\s*$/', sprintf('%0'.$pad.'d', $start - $i), $base),
                'from' => $from,
                'issueLabel' => $this->dateLabel($sample['issue']),
                'dueLabel' => $this->dateLabel($sample['due']),
                'total' => $subtotal * 1.09, // the 9% VAT step the document renders
            ];
        }, $samples, array_keys($samples));
    }
}
