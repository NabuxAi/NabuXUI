{{-- tables: a compact data table with a status column. --}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $e, string $f) => $fa ? $f : $e;
    $rows = [
        ['id' => '1', 'name' => $say('Aster desk lamp', 'چراغ رومیزی آستر'), 'status' => $say('Paid', 'پرداخت‌شده'), 'total' => $say('€89', '۸۹ یورو')],
        ['id' => '2', 'name' => $say('Nimbus K2 keyboard', 'کیبورد نیمبوس K2'), 'status' => $say('Shipping', 'در حال ارسال'), 'total' => $say('€149', '۱۴۹ یورو')],
        ['id' => '3', 'name' => $say('Vela laptop stand', 'پایهٔ لپ‌تاپ ولا'), 'status' => $say('Refunded', 'برگشتی'), 'total' => $say('€59', '۵۹ یورو')],
    ];
@endphp
<div style="max-inline-size: 40rem; margin-inline: auto">
    <x-nx::data-table :caption="$say('Recent orders', 'سفارش‌های اخیر')" :rows="$rows" :columns="[
        ['key' => 'name', 'label' => $say('Item', 'کالا')],
        ['key' => 'status', 'label' => $say('Status', 'وضعیت')],
        ['key' => 'total', 'label' => $say('Total', 'جمع')],
    ]" />
    <div class="aist-recipe-row" style="justify-content: space-between; margin-block-start: .9rem">
        <span style="font-size: var(--nx-text-xs); color: var(--nx-text-muted)">{{ $say('3 of 248 rows', '۳ از ۲۴۸ ردیف') }}</span>
        <x-nx::pagination :total="248" :per-page="10" :current="1" />
    </div>
</div>
