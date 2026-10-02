{{--
    The checkbox as it lives in a product: a store's notification channels
    (one stays disabled until a phone number is verified), a checkout where
    accepting the terms unlocks the pay button, and a bulk invoice picker
    whose select-all checkbox rides the indeterminate state.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    // 1) Notification channels — the summary counts the live models only.
    $channels = [
        ['key' => 'email', 'label' => $say('Transaction emails', 'ایمیل تراکنش‌ها'), 'description' => $say('Every payment and refund, the moment it happens.', 'هر پرداخت و بازپرداخت، در همان لحظه.')],
        ['key' => 'push', 'label' => $say('Browser notifications', 'اعلان‌های مرورگر'), 'description' => $say('New orders while the dashboard is open.', 'سفارش‌های تازه وقتی داشبورد باز است.')],
        ['key' => 'report', 'label' => $say('Weekly sales report', 'گزارش هفتگی فروش'), 'description' => $say('Every Thursday morning, before the standup.', 'هر پنجشنبه صبح، قبل از جلسه.')],
    ];
    $channelsOn = 0;
    foreach ($channels as $channel) {
        $channelsOn += (int) ! empty($state['ch'][$channel['key']]);
    }
    $channelsMsg = $say('Notification channels saved', 'کانال‌های اطلاع‌رسانی ذخیره شد');

    // 2) Checkout — the pay button stays locked until the terms are accepted.
    $terms = (bool) ($state['terms'] ?? false);
    $orderTotal = 2480000;
    $payMsg = $say('Paid — thank you for your order', 'پرداخت شد — از خرید شما ممنونیم');

    // 3) Bulk invoice pick — the header checkbox is checked/indeterminate
    //    from this server-rendered truth; wire:key swaps the node whenever
    //    that visual state changes, so Alpine's indeterminate x-init reruns
    //    instead of leaving a stale dash on a morphed input.
    $invoices = [
        ['no' => 1041, 'client' => $say('Nabu Café', 'نابو کافه'), 'amount' => 420000],
        ['no' => 1042, 'client' => $say('Haft Shahr Gallery', 'گالری هفت‌شهر'), 'amount' => 380000],
        ['no' => 1043, 'client' => $say('Studio Ramesh', 'استودیو رامش'), 'amount' => 640000],
        ['no' => 1044, 'client' => $say('Narenj School', 'مدرسهٔ نارنج'), 'amount' => 210000],
    ];
    $picked = [];
    foreach ($invoices as $i => $invoice) {
        $picked[$i] = (bool) ($state['invoicePick'][$i] ?? false);
    }
    $pickedCount = count(array_filter($picked));
    $pickedTotal = 0;
    foreach ($invoices as $i => $invoice) {
        $pickedTotal += $picked[$i] ? $invoice['amount'] : 0;
    }
    $allPicked = $pickedCount === count($invoices);
    $somePicked = $pickedCount > 0 && ! $allPicked;
    // 'some-N' keys the node per count so every state change replaces it.
    $pickPhase = $allPicked ? 'all' : ($pickedCount > 0 ? 'some-'.$pickedCount : 'none');
    // One leaf per $set — the demo host's updated() hook only accepts scalars.
    $pickSets = [];
    foreach (array_keys($picked) as $i) {
        $pickSets[] = "\$set('state.invoicePick.{$i}', ".($allPicked ? 'false' : 'true').')';
    }
    $bulkMsg = NabuXUI::formatNumber($pickedCount).' '.$say('invoices marked as paid', 'فاکتور پرداخت‌شده علامت خورد');
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div class="pg-row" style="justify-content: space-between">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The store’s notification settings', 'تنظیمات اطلاع‌رسانی فروشگاه') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Each channel is a live checkbox — the counter rolls as you click, and the label itself is clickable. SMS stays disabled until a phone number is verified.', 'هر کانال یک چک‌باکس زنده است — شمارنده با هر کلیک می‌غلتد و خودِ برچسب هم کلیک‌پذیر است. پیامک تا تأیید شمارهٔ موبایل ازکار افتاده می‌ماند.') }}
            </p>
        </div>
        <span class="nx-badge" data-tone="accent">{{ NabuXUI::formatNumber($channelsOn).' '.$say('of 3 channels on', 'از ۳ کانال فعال') }}</span>
    </div>
    <div class="pg-grid">
        @foreach ($channels as $channel)
            <x-nx::checkbox :label="$channel['label']" :description="$channel['description']" wire:model.live="state.ch.{{ $channel['key'] }}" />
        @endforeach
        <x-nx::checkbox :label="$say('Transaction SMS', 'پیامک تراکنش‌ها')" :description="$say('Your mobile number is not verified yet.', 'شمارهٔ موبایل شما هنوز تأیید نشده است.')" disabled />
    </div>
    <div class="pg-row" style="justify-content: space-between">
        <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
            {{ $say('Verify a number in profile → security to unlock the fourth channel.', 'برای باز شدن کانال چهارم، در نمایه → امنیت یک شماره تأیید کنید.') }}
        </p>
        <x-nx::button variant="primary" icon="check" wire:click="save('{{ $channelsMsg }}')">{{ $say('Save channels', 'ذخیرهٔ کانال‌ها') }}</x-nx::button>
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Checkout, with the terms gate', 'تسویهٔ حساب، با دروازهٔ قوانین') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The required consent unlocks the pay button — until then it stays disabled. The marketing opt-in beside it is optional and changes nothing.', 'پذیرش الزامی، دکمهٔ پرداخت را باز می‌کند — تا آن لحظه قفل است. گزینهٔ بازاریابی کنارش اختیاری است و چیزی را عوض نمی‌کند.') }}
        </p>
    </div>
    <ul style="list-style: none; margin: 0; padding: 0; display: grid; gap: .5rem">
        @foreach ([
            [$say('NabuConf ticket × 2', 'بلیت همایش نابو × ۲'), 890000],
            [$say('Motion workshop seat', 'صندلی کارگاه حرکت'), 700000],
        ] as [$item, $price])
            <li class="pg-row" style="justify-content: space-between; padding: .6rem .9rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg)">
                <span style="font-weight: 550">{{ $item }}</span>
                <span style="color: var(--nx-text-muted)">{{ NabuXUI::formatNumber($price).' '.$say('Toman', 'تومان') }}</span>
            </li>
        @endforeach
        <li class="pg-row" style="justify-content: space-between; padding: .6rem .9rem; border: 1px solid var(--nx-border-strong); border-radius: var(--nx-radius-lg); background: var(--nx-surface-2)">
            <strong>{{ $say('Order total', 'جمع سفارش') }}</strong>
            <strong style="font-size: var(--nx-text-md)">{{ NabuXUI::formatNumber($orderTotal).' '.$say('Toman', 'تومان') }}</strong>
        </li>
    </ul>
    <div style="display: grid; gap: .75rem; padding: .85rem .9rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg)">
        <x-nx::checkbox :label="$say('I accept the NabuMarket terms', 'قوانین و مقررات نابومارکت را می‌پذیرم')" :description="$say('Including the refund policy and shipping conditions.', 'شامل سیاست بازگشت وجه و شرایط ارسال است.')" wire:model.live="state.terms" />
        <x-nx::checkbox :label="$say('Text me the discount codes', 'کدهای تخفیف را برایم پیامک کنید')" :description="$say('At most twice a month.', 'حداکثر ماهی دو پیام.')" wire:model.live="state.smsOffers" />
    </div>
    <div class="pg-row" style="justify-content: space-between">
        <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
            {{ $terms
                ? $say('Terms accepted — the button is live.', 'قوانین پذیرفته شد — دکمه فعال است.')
                : $say('The pay button unlocks the moment you accept the terms.', 'به محض پذیرش قوانین، دکمهٔ پرداخت باز می‌شود.') }}
        </p>
        <x-nx::button variant="primary" icon="lock" :disabled="$terms ? null : 'disabled'" wire:click="save('{{ $payMsg }}')">
            {{ $say('Pay', 'پرداخت') }} {{ NabuXUI::formatNumber($orderTotal) }}
        </x-nx::button>
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Picking invoices for the next payment batch', 'انتخاب فاکتورها برای دستهٔ پرداخت بعدی') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Tick a few rows and the select-all checkbox settles into its indeterminate dash — check it and everything lights up; check it again and the batch empties. The sum rolls with the picks.', 'چند ردیف را فعال کنید تا چک‌باکسِ «همه» در حالت میانی (خط) بنشیند — او را بزنید تا همه روشن شوند؛ دوباره بزنید تا دسته خالی شود. جمع مبلغ هم با انتخاب‌ها می‌غلتد.') }}
        </p>
    </div>
    <div class="pg-row" style="justify-content: space-between">
        <x-nx::checkbox
            wire:key="pick-{{ $pickPhase }}"
            :label="$say('All invoices in this cycle', 'همهٔ فاکتورهای این دوره')"
            :indeterminate="$somePicked"
            :checked="$allPicked"
            wire:click="{{ implode('; ', $pickSets) }}" />
        <span class="nx-badge" data-tone="{{ $pickedCount > 0 ? 'accent' : 'neutral' }}">
            {{ NabuXUI::formatNumber($pickedCount).' '.$say('invoices · ', 'فاکتور · ').NabuXUI::formatNumber($pickedTotal).' '.$say('Toman', 'تومان') }}
        </span>
    </div>
    <ul style="list-style: none; margin: 0; padding: 0; display: grid; gap: .5rem">
        @foreach ($invoices as $i => $invoice)
            <li>
                <label class="pg-row" style="justify-content: space-between; gap: .9rem; padding: .6rem .9rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg); cursor: pointer">
                    <span class="pg-row" style="gap: .75rem">
                        <x-nx::checkbox wire:model.live="state.invoicePick.{{ $i }}" />
                        <span style="font-weight: 550">{{ $say('Invoice', 'فاکتور').' '.NabuXUI::formatNumber($invoice['no']) }}</span>
                        <span style="color: var(--nx-text-muted)">{{ $invoice['client'] }}</span>
                    </span>
                    <span style="color: var(--nx-text-muted)">{{ NabuXUI::formatNumber($invoice['amount']).' '.$say('Toman', 'تومان') }}</span>
                </label>
            </li>
        @endforeach
    </ul>
    <x-nx::button variant="primary" icon="check" :disabled="$pickedCount === 0 ? 'disabled' : null" wire:click="save('{{ $bulkMsg }}')">
        {{ $say('Mark as paid', 'پرداخت‌شده علامت بزن') }}
    </x-nx::button>
</section>
