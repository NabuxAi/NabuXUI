{{--
    <x-nx::invoice number="INV-1404-082" status="unpaid" currency="تومان" brand="استودیو نابو" brand-tagline="مهندسی طراحی"
        issue-date="۱۴۰۴/۰۷/۰۸" due-date="۱۴۰۴/۰۷/۳۰"
        :from="['name' => 'استودیو نابو', 'lines' => ['تهران، ایران', 'hello@nabu.studio', 'شناسه ملی ۱۴۰۰…']]"
        :to="['name' => 'شرکت آدم', 'lines' => ['تهران، ایران', 'accounts@acme.ir']]"
        :lines="[
            ['title' => 'سیستم طراحی', 'description' => 'توکن‌ها، کامپوننت‌ها، مستندات', 'quantity' => 1, 'unitPrice' => 480000000],
            ['title' => 'پشتیبانی ماهانه', 'quantity' => 3, 'unitPrice' => 25000000],
        ]"
        :extraTotals="[['label' => 'مالیات بر ارزش افزوده (۹٪)', 'percent' => 0.09]]"
        note="پرداخت تا موعد اعلام‌شده؛ پس از آن سرسید گذشته محاسبه می‌شود." />

    Amounts are computed from the lines (quantity × unitPrice, or `amount` when you give it); steps
    between the subtotal and the total are an `amount` or a `percent` of the subtotal. Sections rise
    one after another, rows one at a time, totals roll their digits; @media print turns it into the
    plain paper document. wire:click anywhere else on the page keeps all of it through the morph.
--}}
@props([
    'brand' => null,
    'brandTagline' => null,
    'brandMark' => null,
    'number' => null,
    'status' => null,
    'statusLabel' => null,
    'issueDate' => null,
    'dueDate' => null,
    'from' => [],
    'to' => [],
    'lines' => [],
    'extraTotals' => [],
    'currency' => null,
    'decimals' => 0,
    'note' => null,
    'footer' => null,
    'labels' => [],
])
@php
    use NabuXUI\NabuXUI;

    $locale = str_replace('_', '-', app()->getLocale());
    $lang = substr($locale, 0, 2);
    // The invoice's own words live in the core i18n table (resources/lang, generated from it).
    $words = [
        'title' => __('nabuxui::ui.invoiceTitle', [], $lang),
        'number' => __('nabuxui::ui.invoiceNumber', [], $lang),
        'issueDate' => __('nabuxui::ui.invoiceIssueDate', [], $lang),
        'dueDate' => __('nabuxui::ui.invoiceDueDate', [], $lang),
        'from' => __('nabuxui::ui.invoiceFrom', [], $lang),
        'to' => __('nabuxui::ui.invoiceTo', [], $lang),
        'item' => __('nabuxui::ui.invoiceItem', [], $lang),
        'quantity' => __('nabuxui::ui.invoiceQuantity', [], $lang),
        'unitPrice' => __('nabuxui::ui.invoiceUnitPrice', [], $lang),
        'amount' => __('nabuxui::ui.invoiceAmount', [], $lang),
        'subtotal' => __('nabuxui::ui.invoiceSubtotal', [], $lang),
        'total' => __('nabuxui::ui.invoiceTotal', [], $lang),
        'paid' => __('nabuxui::ui.invoicePaid', [], $lang),
        'unpaid' => __('nabuxui::ui.invoiceUnpaid', [], $lang),
        'overdue' => __('nabuxui::ui.invoiceOverdue', [], $lang),
        'draft' => __('nabuxui::ui.invoiceDraft', [], $lang),
        'caption' => __('nabuxui::ui.invoiceCaption', [], $lang),
    ];
    $words = array_merge($words, is_array($labels) ? $labels : []);
    $say = fn (string $key) => (string) $words[$key];

    $statuses = ['paid', 'unpaid', 'overdue', 'draft'];
    $status = in_array($status, $statuses, true) ? $status : null;
    // The badge speaks in job statuses; an invoice maps onto them.
    $badgeOf = ['paid' => 'success', 'unpaid' => 'queued', 'overdue' => 'failed', 'draft' => 'canceled'];

    $party = function ($party) {
        $party = (array) $party;

        return [
            'name' => (string) ($party['name'] ?? ''),
            'lines' => array_values(array_map(fn ($line) => (string) $line, (array) ($party['lines'] ?? []))),
        ];
    };
    $from = $party($from);
    $to = $party($to);

    $lines = array_values(array_map(fn ($line) => [
        'id' => (string) ($line['id'] ?? uniqid('l')),
        'title' => (string) ($line['title'] ?? ''),
        'description' => isset($line['description']) ? (string) $line['description'] : null,
        'quantity' => isset($line['quantity']) ? (float) $line['quantity'] : null,
        'unitPrice' => isset($line['unitPrice']) ? (float) $line['unitPrice'] : null,
        'amount' => isset($line['amount']) ? (float) $line['amount'] : null,
    ], (array) $lines));

    $subtotal = 0.0;
    foreach ($lines as $line) {
        $subtotal += $line['amount'] ?? ($line['quantity'] ?? 1) * ($line['unitPrice'] ?? 0);
    }
    $steps = [];
    foreach ((array) $extraTotals as $step) {
        $step = (array) $step;
        $amount = isset($step['amount'])
            ? (float) $step['amount']
            : $subtotal * (float) ($step['percent'] ?? 0);
        $steps[] = ['label' => (string) ($step['label'] ?? ''), 'amount' => $amount];
    }
    $total = $subtotal + array_sum(array_column($steps, 'amount'));
    $decimals = max(0, min(6, (int) $decimals));

    $id = NabuXUI::id('nx-invoice');
    $mark = $brandMark ?? (is_string($brand) && $brand !== '' ? mb_substr($brand, 0, 1) : 'N');
@endphp
<article {{ $attributes->class('nx-invoice')->merge(['data-status' => $status, 'data-nx-reveal' => 'group']) }}
    x-data x-nx-reveal.group aria-labelledby="{{ $id }}-title">
    <header class="nx-invoice-section nx-invoice-head">
        <div class="nx-invoice-brand">
            <div class="nx-invoice-brand-row">
                <span class="nx-invoice-mark" aria-hidden="true">{{ $mark }}</span>
                @if ($brand)<span class="nx-invoice-brand-name">{{ $brand }}</span>@endif
            </div>
            @if ($brandTagline)<span class="nx-invoice-brand-tagline">{{ $brandTagline }}</span>@endif
        </div>
        <div class="nx-invoice-meta">
            <h2 class="nx-invoice-title" id="{{ $id }}-title">{{ $say('title') }}</h2>
            <dl class="nx-invoice-facts">
                @if ($number)
                    <div><dt>{{ $say('number') }}</dt><dd>{{ $number }}</dd></div>
                @endif
                @if ($issueDate)
                    <div><dt>{{ $say('issueDate') }}</dt><dd>{{ $issueDate }}</dd></div>
                @endif
                @if ($dueDate)
                    <div><dt>{{ $say('dueDate') }}</dt><dd>{{ $dueDate }}</dd></div>
                @endif
            </dl>
            @if ($status)
                <x-nx::status-badge :status="$badgeOf[$status]" :label="$statusLabel" :labels="[
                    'success' => $say('paid'),
                    'queued' => $say('unpaid'),
                    'failed' => $say('overdue'),
                    'canceled' => $say('draft'),
                ]" />
            @endif
        </div>
    </header>

    <div class="nx-invoice-section nx-invoice-parties">
        @foreach ([['from', $from, $say('from')], ['to', $to, $say('to')]] as [$kind, $party, $label])
            <div class="nx-invoice-party" data-kind="{{ $kind }}">
                <span class="nx-invoice-party-label">{{ $label }}</span>
                @if ($party['name'] !== '')<p class="nx-invoice-party-name">{{ $party['name'] }}</p>@endif
                @if (count($party['lines']) > 0)
                    <div class="nx-invoice-party-lines">
                        @foreach ($party['lines'] as $line)<span>{{ $line }}</span>@endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <table class="nx-invoice-section nx-invoice-lines">
        <caption class="nx-visually-hidden">{{ $say('title') }} {{ $number }}</caption>
        <thead>
            <tr>
                <th scope="col" aria-label="#"></th>
                <th scope="col">{{ $say('item') }}</th>
                <th scope="col" data-align="end">{{ $say('quantity') }}</th>
                <th scope="col" data-align="end">{{ $say('unitPrice') }}</th>
                <th scope="col" data-align="end">{{ $say('amount') }}</th>
            </tr>
        </thead>
        <tbody data-nx-reveal="group" x-data x-nx-reveal.group>
            @forelse (array_values($lines) as $i => $line)
                @php
                    $sum = $line['amount'] ?? ($line['quantity'] ?? 1) * ($line['unitPrice'] ?? 0);
                @endphp
                <tr class="nx-invoice-line" style="--nx-i: {{ $i }}" wire:key="{{ $id }}-line-{{ $line['id'] }}">
                    <td class="nx-invoice-index">{{ NabuXUI::formatNumber($i + 1, 0, $locale) }}</td>
                    <td class="nx-invoice-item">
                        <span class="nx-invoice-item-title">{{ $line['title'] }}</span>
                        @if ($line['description'])<span class="nx-invoice-item-description">{{ $line['description'] }}</span>@endif
                    </td>
                    <td data-align="end">{{ $line['quantity'] !== null ? NabuXUI::formatNumber($line['quantity'], floor($line['quantity']) == $line['quantity'] ? 0 : 2, $locale) : '—' }}</td>
                    <td data-align="end">{{ $line['unitPrice'] !== null ? NabuXUI::formatNumber($line['unitPrice'], $decimals, $locale) : '—' }}</td>
                    <td data-align="end">
                        <span class="nx-invoice-amount">
                            <x-nx::number :value="$sum" :decimals="$decimals" :locale="$locale" :reveal="false" />
                            @if ($currency)<span class="nx-invoice-currency">{{ $currency }}</span>@endif
                        </span>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="nx-invoice-item-description">{{ __('nabuxui::ui.noResults') }}</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="nx-invoice-section nx-invoice-totals">
        <div class="nx-invoice-row">
            <span>{{ $say('subtotal') }}</span>
            <span class="nx-invoice-amount">
                <x-nx::number :value="$subtotal" :decimals="$decimals" :locale="$locale" />
                @if ($currency)<span class="nx-invoice-currency">{{ $currency }}</span>@endif
            </span>
        </div>
        @foreach ($steps as $step)
            <div class="nx-invoice-row">
                <span>{{ $step['label'] }}</span>
                <span class="nx-invoice-amount">
                    <x-nx::number :value="$step['amount']" :decimals="$decimals" :locale="$locale" />
                    @if ($currency)<span class="nx-invoice-currency">{{ $currency }}</span>@endif
                </span>
            </div>
        @endforeach
        <div class="nx-invoice-total">
            <span class="nx-invoice-total-label">{{ $say('total') }}</span>
            <span class="nx-invoice-amount">
                <x-nx::number :value="$total" :decimals="$decimals" :locale="$locale" />
                @if ($currency)<span class="nx-invoice-currency">{{ $currency }}</span>@endif
            </span>
        </div>
        @if ($note)<p class="nx-invoice-note">{{ $note }}</p>@endif
    </div>

    <footer class="nx-invoice-section nx-invoice-foot">
        <span>{{ $footer ?? $say('caption') }}</span>
        @if ($brand)<span>{{ $brand }}</span>@endif
    </footer>
</article>
