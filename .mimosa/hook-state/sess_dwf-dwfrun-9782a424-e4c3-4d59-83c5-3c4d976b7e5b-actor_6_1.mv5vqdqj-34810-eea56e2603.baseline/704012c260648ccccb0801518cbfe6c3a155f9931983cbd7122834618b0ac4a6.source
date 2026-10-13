{{--
    The drawer as commerce actually uses it: a cart sliding in from the
    inline-end with a paying footer, and a filters panel from the inline-start.
    In RTL the sides swap by themselves — the code does not.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $cart = [
        ['name' => $say('Lapis notebook', 'دفتر لاجوردی'), 'qty' => 2, 'price' => 180000],
        ['name' => $say('Gold-foil pen', 'خودکار طلایی'), 'qty' => 1, 'price' => 95000],
        ['name' => $say('Linen tote', 'کیف کتان'), 'qty' => 1, 'price' => 240000],
    ];
    $total = array_sum(array_map(fn ($item) => $item['qty'] * $item['price'], $cart));
    $items = count($cart);
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The cart drawer', 'کشوی سبد خرید') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Opens from the inline-end, sums in the footer, and pays with the same slow action so the button can show its spinner.', 'از انتهای خط باز می‌شود، جمع را در فوتر می‌نویسد و با همان اکشن کند پرداخت می‌کند تا دکمه اسپینرش را نشان دهد.') }}
        </p>
    </div>
    <div class="pg-row">
        <x-nx::button variant="primary" icon="grid" wire:click="$set('state.cartOpen', true)">
            {{ $say('Cart', 'سبد خرید') }} · {{ NabuXUI::formatNumber($items) }}
        </x-nx::button>
        <span style="color: var(--nx-text-muted)">{{ $say('Total so far:', 'جمع فعلی:') }} <strong style="color: var(--nx-text)">{{ NabuXUI::formatNumber($total) }}</strong> {{ $say('Toman', 'تومان') }}</span>
    </div>
    <x-nx::drawer side="end" wire:model="state.cartOpen" :title="$say('Your cart', 'سبد شما')" :description="$say('Free shipping over 500,000.', 'ارسال رایگان بالای ۵۰۰٬۰۰۰.')">
        <ul style="list-style: none; margin: 0; padding: 0; display: grid; gap: .75rem">
            @foreach ($cart as $item)
                <li class="pg-row" style="justify-content: space-between; padding-block: .6rem; border-bottom: 1px solid var(--nx-border)">
                    <span class="pg-row" style="gap: .6rem">
                        <x-nx::skeleton shape="block" width="2.5rem" height="2.5rem" />
                        <strong style="font-weight: 600">{{ $item['name'] }}</strong>
                    </span>
                    <span class="pg-row" style="gap: .5rem">
                        <x-nx::badge tone="neutral">×{{ NabuXUI::formatNumber($item['qty']) }}</x-nx::badge>
                        <span>{{ NabuXUI::formatNumber($item['qty'] * $item['price']) }}</span>
                    </span>
                </li>
            @endforeach
        </ul>
        <x-slot:footer>
            <div class="pg-row" style="justify-content: space-between; margin-block-end: .75rem">
                <span style="color: var(--nx-text-muted)">{{ $say('Total', 'جمع') }} ({{ NabuXUI::formatNumber($items) }} {{ $say('items', 'قلم') }})</span>
                <strong style="font: 700 var(--nx-text-lg) var(--nx-font-display)">{{ NabuXUI::formatNumber($total) }}</strong>
            </div>
            <div class="pg-row">
                <x-nx::button variant="primary" block wire:click="save('{{ $say('Paid — receipt sent', 'پرداخت شد — رسید رفت') }}')">{{ $say('Pay now', 'پرداخت') }}</x-nx::button>
            </div>
        </x-slot:footer>
    </x-nx::drawer>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The filters panel, from the other side', 'پنل فیلترها، از سوی دیگر') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('side="start" puts it beside the reading order; the switches bind straight into Livewire state and the apply button reports the count.', 'با side="start" کنار ترتیب خواندن می‌نشیند؛ سوییچ‌ها مستقیم به وضعیت Livewire وصل‌اند و دکمهٔ اعمال شمارش را گزارش می‌کند.') }}
        </p>
    </div>
    <x-nx::button variant="secondary" icon="sliders" wire:click="$set('state.filtersOpen', true)">{{ $say('Filters', 'فیلترها') }}</x-nx::button>
    <x-nx::drawer side="start" wire:model="state.filtersOpen" :title="$say('Filter the shop', 'فیلتر فروشگاه')">
        <div style="display: grid; gap: 1.25rem">
            <x-nx::switch :label="$say('In stock only', 'فقط موجود')" :description="$say('Hides 3-week preorders.', 'پیش‌سفارش‌های سه‌هفته‌ای را پنهان می‌کند.')" wire:model="state.f.inStock" />
            <x-nx::switch :label="$say('Free shipping', 'ارسال رایگان')" wire:model="state.f.freeShip" />
            <x-nx::switch :label="$say('New arrivals', 'تازه‌ها')" :description="$say('Last 14 days.', '۱۴ روز گذشته.')" wire:model="state.f.fresh" />
            <x-nx::divider>{{ $say('or browse', 'یا مرور کنید') }}</x-nx::divider>
            <x-nx::select :label="$say('Sort', 'ترتیب')" :options="[
                'new' => $say('Newest first', 'تازه‌ترین'),
                'cheap' => $say('Price: low to high', 'ارزان‌ترین'),
                'popular' => $say('Most popular', 'محبوب‌ترین'),
            ]" wire:model="state.f.sort" />
        </div>
        <x-slot:footer>
            <div class="pg-row">
                <x-nx::button variant="ghost" wire:click="ping('{{ $say('Filters cleared', 'فیلترها پاک شد') }}')">{{ $say('Clear all', 'پاک‌کردن همه') }}</x-nx::button>
                <x-nx::button variant="primary" icon="check" wire:click="save('{{ $say('Filters applied', 'فیلترها اعمال شد') }}')">{{ $say('Apply filters', 'اعمال فیلترها') }}</x-nx::button>
            </div>
        </x-slot:footer>
    </x-nx::drawer>
</section>
