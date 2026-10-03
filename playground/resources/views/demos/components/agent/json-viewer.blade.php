{{--
    Inspecting what a tool returned: an order lookup's response (search "Isfahan" or
    "refund" to see hits opened and marked; copy a path like $.items[1].sku), and a
    webhook payload loaded from a JSON string, two levels open.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $labels = [
        'search' => $say('Search keys and values', 'جست‌وجو در کلیدها و مقدارها'),
        'expandAll' => $say('Expand all', 'باز کردن همه'), 'collapseAll' => $say('Collapse all', 'بستن همه'),
        'copyPath' => $say('Copy path', 'کپی مسیر'), 'copyValue' => $say('Copy value', 'کپی مقدار'),
        'items' => $say(':count items', ':count مورد'), 'keys' => $say(':count keys', ':count کلید'),
        'matches' => $say(':count found', ':count یافته'), 'expand' => $say('Expand', 'باز کردن'), 'collapse' => $say('Collapse', 'بستن'),
    ];
    $order = [
        'id' => 'ord_8F2K19',
        'status' => 'shipped',
        'paid' => true,
        'total' => 1840000,
        'currency' => 'IRR',
        'customer' => ['name' => 'سارا رضایی', 'email' => 'sara@example.com', 'city' => 'Isfahan', 'vip' => false],
        'items' => [
            ['sku' => 'TEA-SAF-250', 'title' => 'Saffron tea 250g', 'qty' => 2, 'price' => 420000],
            ['sku' => 'NAB-PIS-500', 'title' => 'Pistachio nabat', 'qty' => 1, 'price' => 1000000],
        ],
        'shipment' => ['carrier' => 'Post', 'tracking' => '1290 4471 2210', 'eta' => '2026-10-06'],
        'refund' => null,
        'notes' => [],
    ];
    $webhook = '{"event":"invoice.paid","created":1759480000,"livemode":false,"data":{"object":{"id":"in_1Q2","amount_paid":4900,"lines":[{"description":"Pro plan × 1","period":{"start":1759480000,"end":1762158400}}],"metadata":{"workspace":"nabux","seats":"5"}}}}';
@endphp
<div style="display: grid; gap: 1.25rem; max-inline-size: 46rem">
    <x-nx::json-viewer :data="$order" :depth="1" :labels="$labels" />
    <x-nx::json-viewer :data="$webhook" :depth="3" :labels="$labels" max-height="20rem" />
</div>
