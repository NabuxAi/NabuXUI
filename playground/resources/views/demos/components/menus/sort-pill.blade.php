{{--
    The sort pill doing real work: the picked label rolls inside the trigger
    while the orders list under it is re-sorted on the server with every pick.
    A second pill, aligned to the end, sorts the same list by status.
--}}
@php
    use Illuminate\Support\Carbon;
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $orders = [
        ['id' => '1042', 'customer' => $say('Ava Karimi', 'آوا کریمی'), 'when' => now()->subMinutes(8), 'total' => 1280000, 'status' => 'success'],
        ['id' => '1041', 'customer' => $say('Soheil Nouri', 'سهیل نوری'), 'when' => now()->subHours(2), 'total' => 490000, 'status' => 'running'],
        ['id' => '1040', 'customer' => $say('Mona Ahmadi', 'مونا احمدی'), 'when' => now()->subHours(6), 'total' => 2350000, 'status' => 'queued'],
        ['id' => '1039', 'customer' => $say('Kian Rajaee', 'کیان رجایی'), 'when' => now()->subDays(2), 'total' => 760000, 'status' => 'failed'],
    ];

    $sort = $state['orderSort'] ?? 'recent';
    usort($orders, function ($a, $b) use ($sort) {
        return match ($sort) {
            'oldest' => $a['when']->timestamp <=> $b['when']->timestamp,
            'amount' => $b['total'] <=> $a['total'],
            default => $b['when']->timestamp <=> $a['when']->timestamp,
        };
    });
@endphp

<section class="pg-box" style="gap: 1rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Sorting today’s orders', 'مرتب‌سازی سفارش‌های امروز') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The panel is a native popover — Escape and outside clicks close it, arrows walk the choices. Each pick lands in Livewire and the rows re-render in their new order.', 'پنل یک popover بومی است — Escape و کلیک بیرون می‌بندندش، فلش‌ها بین گزینه‌ها می‌گردند. هر انتخاب به Livewire می‌رسد و ردیف‌ها به ترتیب تازه دوباره رندر می‌شوند.') }}
        </p>
    </div>
    <div class="pg-row" style="justify-content: space-between">
        <x-nx::sort-pill label="{{ $say('Sort orders', 'ترتیب سفارش‌ها') }}" :value="$sort" wire:model.live="state.orderSort" :options="[
            'recent' => ['label' => $say('Newest first', 'تازه‌ترین'), 'icon' => 'sparkles'],
            'oldest' => ['label' => $say('Oldest first', 'قدیمی‌ترین'), 'icon' => 'globe'],
            'amount' => ['label' => $say('Largest total', 'بیشترین مبلغ'), 'icon' => 'trend-up'],
        ]" />
        <x-nx::badge tone="accent">{{ NabuXUI::formatNumber(count($orders)).' '.$say('orders', 'سفارش') }}</x-nx::badge>
    </div>
    <ul style="list-style: none; margin: 0; padding: 0; display: grid; gap: .5rem">
        @foreach ($orders as $order)
            @php
                $tone = ['success' => 'success', 'running' => 'info', 'queued' => 'neutral', 'failed' => 'danger'][$order['status']];
                $label = ['success' => $say('paid', 'پرداخت‌شده'), 'running' => $say('shipping', 'در ارسال'), 'queued' => $say('queued', 'در صف'), 'failed' => $say('failed', 'ناموفق')][$order['status']];
            @endphp
            <li class="pg-row" style="justify-content: space-between; gap: .75rem; padding: .75rem 1rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg)">
                <span class="pg-row" style="gap: .75rem">
                    <code>#{{ $order['id'] }}</code>
                    <strong>{{ $order['customer'] }}</strong>
                    <span style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $order['when']->locale(app()->getLocale())->diffForHumans() }}</span>
                </span>
                <span class="pg-row" style="gap: .75rem">
                    <strong>{{ NabuXUI::formatNumber($order['total']).' '.$say('tomans', 'تومان') }}</strong>
                    <x-nx::badge :tone="$tone" dot>{{ $label }}</x-nx::badge>
                </span>
            </li>
        @endforeach
    </ul>
</section>
