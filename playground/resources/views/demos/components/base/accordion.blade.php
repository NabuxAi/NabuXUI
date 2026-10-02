{{--
    The accordion as it lives in real products: a shop's support FAQ (the
    canonical single-open list over native details), the payment settings
    (separated cards, several sections open at once, real forms living inside
    the slots) and the recent-orders list (rich slot content with Persian
    amounts and a toastable action per order).
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $money = fn (int $tomans) => NabuXUI::formatNumber($tomans) . ' ' . $say('tomans', 'تومان');

    $orders = [
        ['id' => 981, 'date' => $say('Oct 6', '۱۴ مهر'), 'tone' => 'success', 'status' => $say('Delivered', 'تحویل شد'), 'items' => [
            ['name' => $say('Hand-stitched leather bag', 'کیف چرمی دست‌دوز'), 'qty' => 1, 'price' => 890000],
            ['name' => $say('Desktop charger', 'شارژر رومیزی'), 'qty' => 1, 'price' => 390000],
        ]],
        ['id' => 967, 'date' => $say('Oct 1', '۹ مهر'), 'tone' => 'warning', 'status' => $say('In transit', 'در حال ارسال'), 'items' => [
            ['name' => $say('Hand-painted ceramic mug', 'ماگ سرامیکی نقاشی‌شده'), 'qty' => 2, 'price' => 225000],
        ]],
        ['id' => 954, 'date' => $say('Sep 24', '۲ مهر'), 'tone' => 'neutral', 'status' => $say('Processing', 'در حال پردازش'), 'items' => [
            ['name' => $say('Linen notebook', 'دفتر کتانی'), 'qty' => 3, 'price' => 240000],
            ['name' => $say('Brass bookmark set', 'ست نشان‌برگ برنجی'), 'qty' => 1, 'price' => 320000],
        ]],
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The shop’s support FAQ', 'پرسش‌های پرتکرار پشتیبانی فروشگاه') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The default list: single-open and joined, so opening a question folds the previous one. Every item is a native details element, so the answers sit in the page from the first paint — find-in-page and Tab navigation work before any JavaScript does, and the single-open rule itself is just a shared name on those details.', 'فهرست پیش‌فرض: تک‌انتخابی و چسبیده — با باز شدن هر پرسش، قبلی جمع می‌شود. هر آیتم یک details بومی است، پس پاسخ‌ها از همان رنگ‌آمیزی اول در صفحه‌اند — «جست‌وجو در صفحه» و پیمایش با Tab بی‌هیچ جاوااسکریپتی کار می‌کند و خودِ قاعدهٔ تک‌انتخابی هم فقط یک name مشترک روی همان detailsهاست.') }}
        </p>
    </div>
    <x-nx::accordion single>
        <x-nx::accordion-item :title="$say('When will my order arrive?', 'سفارشم کی می‌رسد؟')" open>
            {{ $say('Orders are processed within 24 business hours; delivery takes 2–4 business days by post and is same-day by courier in Tehran. The tracking code arrives by SMS as soon as the parcel is handed over.', 'سفارش‌ها تا ۲۴ ساعت کاری پردازش می‌شوند؛ ارسال با پست ۲ تا ۴ روز کاری است و در تهران با پیک، همان‌روز. کد رهگیری همین‌که بسته تحویل پست شود، پیامک می‌شود.') }}
        </x-nx::accordion-item>
        <x-nx::accordion-item :title="$say('Can I return an item?', 'کالا را می‌توانم برگردانم؟')">
            {{ $say('Up to 7 days after delivery, as long as the seal and the packaging are intact. Opened hygiene and food items cannot be returned.', 'تا ۷ روز پس از تحویل، به‌شرط سالم بودن پلمب و بسته‌بندی. کالاهای بهداشتی و خوراکیِ باز‌شده قابل برگشت نیستند.') }}
        </x-nx::accordion-item>
        <x-nx::accordion-item :title="$say('Do you offer cash on delivery?', 'پرداخت در محل دارید؟')">
            {{ $say('Yes, for orders under two million tomans in courier-covered cities. Larger orders are paid online or by card at the door.', 'بله، برای سفارش‌های زیر دو میلیون تومان در شهرهای پوشش پیک. سفارش‌های بزرگ‌تر آنلاین یا کارت‌به‌کارت پرداخت می‌شوند.') }}
        </x-nx::accordion-item>
        <x-nx::accordion-item :title="$say('How do I get an official invoice?', 'فاکتور رسمی چطور صادر می‌شود؟')">
            {{ $say('Tick “official invoice” at checkout and enter the company’s national ID; the invoice reaches your finance email together with the shipment.', 'در مرحلهٔ پرداخت گزینهٔ «فاکتور رسمی» را بزنید و شناسهٔ ملی شرکت را وارد کنید؛ فاکتور هم‌زمان با ارسال، به ایمیل مالی شما می‌رسد.') }}
        </x-nx::accordion-item>
    </x-nx::accordion>
    <div class="pg-row" style="justify-content: space-between">
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('The chevron turning over is only the extra cue — the row’s background and text weight change with it too.', 'چرخش فلش فقط نشانهٔ اضافی است — پس‌زمینه و وزن متن ردیف هم همراهش عوض می‌شوند.') }}
        </p>
        <x-nx::button variant="secondary" icon="message" wire:click="ping('{{ $say('A support chat was opened for you', 'گفتگوی پشتیبانی برای شما باز شد') }}')">
            {{ $say('Talk to support', 'گفتگو با پشتیبانی') }}
        </x-nx::button>
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The payment settings, section by section', 'تنظیمات پرداخت، بخش به بخش') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('With variant="separated" and :single="false" every section becomes its own card and stays open on its own — while you edit the payout account the gateway form you filled does not fold away. The slot holds real controls: the switch posts live (wire:model.live) and the fields ride along with the save button.', 'با variant="separated" و :single="false" هر بخش کارت جداگانهٔ خودش می‌شود و مستقل باز می‌ماند — وقتی حساب تسویه را ویرایش می‌کنید، فرم درگاهی که پر کرده‌اید جمع نمی‌شود. اسلات فرم‌های واقعی را نگه می‌دارد: سوییچ همان لحظهٔ چرخیدن می‌رود (wire:model.live) و فیلدها همراه دکمهٔ ذخیره.') }}
        </p>
    </div>
    <x-nx::accordion :single="false" variant="separated">
        <x-nx::accordion-item :title="$say('Payment gateway', 'درگاه پرداخت')" open>
            <div style="display: grid; gap: 1rem">
                <x-nx::switch :label="$say('Online payments', 'پرداخت آنلاین')" :description="$say('Shatab cards, on the bank’s own page.', 'کارت‌های عضو شتاب، در صفحهٔ خود بانک.')" wire:model.live="state.pay.online" />
                <x-nx::input :label="$say('Merchant ID', 'شناسهٔ پذیرنده')" :hint="$say('From your bank’s payment panel.', 'از پنل پرداخت بانک شما.')" wire:model.blur="state.pay.merchant" />
            </div>
        </x-nx::accordion-item>
        <x-nx::accordion-item :title="$say('Official invoice', 'فاکتور رسمی')">
            <div style="display: grid; gap: 1rem">
                <x-nx::input :label="$say('Company name', 'نام شرکت')" wire:model.blur="state.pay.company" />
                <x-nx::input :label="$say('National ID', 'شناسهٔ ملی')" :hint="$say('16 digits, without spaces.', '۱۶ رقم، بدون فاصله.')" wire:model.blur="state.pay.nationalId" />
            </div>
        </x-nx::accordion-item>
        <x-nx::accordion-item :title="$say('Payout account', 'حساب تسویه')" open>
            <div style="display: grid; gap: 1rem">
                <x-nx::input :label="$say('Sheba number', 'شمارهٔ شبا')" prefix="IR" wire:model.blur="state.pay.sheba" />
                <p style="margin: 0; color: var(--nx-text-subtle); font-size: var(--nx-text-sm)">
                    {{ $say('Payouts land on this account every Thursday; a new number applies after two cycles.', 'تسویه هر پنجشنبه به این حساب می‌ریزد؛ شمارهٔ تازه بعد از دو چرخه اعمال می‌شود.') }}
                </p>
            </div>
        </x-nx::accordion-item>
    </x-nx::accordion>
    @if (($state['pay']['online'] ?? false))
        <x-nx::alert tone="success" :title="$say('Online checkout is live', 'پرداخت آنلاین روشن است')">
            {{ $say('Guests pay by card on the bank’s page and the order is confirmed at once.', 'میهمان‌ها با کارت در صفحهٔ بانک پرداخت می‌کنند و سفارش همان‌جا قطعی می‌شود.') }}
        </x-nx::alert>
    @else
        <x-nx::alert tone="warning" :title="$say('Only cash on delivery, for now', 'فعلاً فقط پرداخت در محل')">
            {{ $say('Flip the gateway switch above and the online checkout opens on the spot.', 'سوییچ درگاه را در بالا برگردانید تا پرداخت آنلاین همان جا باز شود.') }}
        </x-nx::alert>
    @endif
    <div class="pg-row" style="justify-content: space-between">
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('Nothing is committed until the save — the fields only bind to the component’s state.', 'تا لحظهٔ ذخیره چیزی ثبت نمی‌شود — فیلدها فقط به state خود کامپوننت وصل‌اند.') }}
        </p>
        <x-nx::button variant="primary" icon="check" wire:click="save('{{ $say('Payment settings saved', 'تنظیمات پرداخت ذخیره شد') }}')">
            {{ $say('Save settings', 'ذخیرهٔ تنظیمات') }}
        </x-nx::button>
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The recent orders, details unfolding in place', 'سفارش‌های اخیر، با جزئیاتی که همان‌جا باز می‌شوند') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The slot is not limited to a paragraph of text: each order opens to its line items, its total and its own action. :single="false" lets you keep two orders open side by side and compare them, and every amount runs through NabuXUI::formatNumber so it turns into Persian digits under the Persian locale.', 'اسلات به یک پاراگراف متن محدود نیست: هر سفارش به قلم‌هایش، جمعش و کنش خودش باز می‌شود. :single="false" اجازه می‌دهد دو سفارش را کنار هم باز نگه دارید و مقایسه کنید، و همهٔ مبالغ از NabuXUI::formatNumber رد می‌شوند تا زیر زبان فارسی، ارقام فارسی شوند.') }}
        </p>
    </div>
    <x-nx::accordion :single="false">
        @foreach ($orders as $order)
            @php
                $units = array_sum(array_map(fn ($item) => $item['qty'], $order['items']));
                $total = array_sum(array_map(fn ($item) => $item['qty'] * $item['price'], $order['items']));
                $title = $say('Order #', 'سفارش ') . NabuXUI::formatNumber($order['id']) . ' · ' . $order['date'];
                $resent = $say('Invoice for order ' . NabuXUI::formatNumber($order['id']) . ' re-sent', 'فاکتور سفارش ' . NabuXUI::formatNumber($order['id']) . ' دوباره ایمیل شد');
            @endphp
            <x-nx::accordion-item :title="$title" :open="$loop->first">
                <div style="display: grid; gap: .75rem">
                    <div class="pg-row" style="gap: .6rem">
                        <x-nx::badge tone="{{ $order['tone'] }}">{{ $order['status'] }}</x-nx::badge>
                        <span style="color: var(--nx-text-subtle)">
                            {{ NabuXUI::formatNumber($units) . ' ' . $say('items in this order', 'قلم کالا در این سفارش') }}
                        </span>
                    </div>
                    <ul style="list-style: none; margin: 0; padding: 0; display: grid; gap: .4rem">
                        @foreach ($order['items'] as $item)
                            <li class="pg-row" style="justify-content: space-between; gap: .75rem">
                                <span>{{ $item['name'] }}<span style="color: var(--nx-text-subtle)"> × {{ NabuXUI::formatNumber($item['qty']) }}</span></span>
                                <span>{{ $money($item['qty'] * $item['price']) }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <div class="pg-row" style="justify-content: space-between; padding-block-start: .5rem; border-block-start: 1px solid var(--nx-border)">
                        <strong style="font-weight: 600">{{ $say('Total', 'جمع کل') }}: {{ $money($total) }}</strong>
                        <x-nx::button size="sm" variant="secondary" icon="mail" wire:click="ping('{{ $resent }}')">
                            {{ $say('Resend invoice', 'ارسال دوبارهٔ فاکتور') }}
                        </x-nx::button>
                    </div>
                </div>
            </x-nx::accordion-item>
        @endforeach
    </x-nx::accordion>
    <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
        {{ $say('The first order arrives open (the open prop) so the stage never shows an empty accordion.', 'نخستین سفارش باز می‌رسد (پراپ open) تا استیج هرگز آکاردئونِ بسته نشان ندهد.') }}
    </p>
</section>
