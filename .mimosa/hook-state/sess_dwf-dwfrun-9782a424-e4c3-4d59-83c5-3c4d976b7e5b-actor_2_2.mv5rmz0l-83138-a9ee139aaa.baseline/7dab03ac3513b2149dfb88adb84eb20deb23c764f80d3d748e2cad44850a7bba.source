{{--
    The radio group as it lives in Persian products: a plan upgrade whose
    live summary re-prices the month as you pick, a shipping row where the
    courier option is off for the customer's city, and storefront display
    settings (digits and currency) previewed with the real formatter.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $plan = (string) ($state['plan'] ?? 'pro');
    $plans = [
        ['value' => 'starter', 'label' => $say('Starter', 'پایه'), 'description' => $say('Up to 100 orders a month, one staff seat', 'تا ۱۰۰ سفارش در ماه، یک کاربر پنل'), 'price' => 190000],
        ['value' => 'pro', 'label' => $say('Pro', 'حرفه‌ای'), 'description' => $say('Unlimited orders, payment gateway, priority support — 82% of stores', 'سفارش نامحدود، درگاه پرداخت و پشتیبانی اولویت‌دار — انتخاب ۸۲٪ فروشگاه‌ها'), 'price' => 490000],
        ['value' => 'business', 'label' => $say('Business', 'کسب‌وکار'), 'description' => $say('Multi-branch inventory, staff roles, API', 'انبار چندشعبه‌ای، نقش‌های کاربری و API'), 'price' => 980000],
    ];
    $currentPlan = collect($plans)->firstWhere('value', $plan) ?? $plans[1];

    $shipping = (string) ($state['shipping'] ?? 'post');
    $arrivals = [
        'post' => $say('about 3–5 working days', 'حدود ۳ تا ۵ روز کاری'),
        'tipax' => $say('2 working days', '۲ روز کاری'),
        'pickup' => $say('today, until 18:00', 'امروز، تا ساعت ۱۸'),
    ];
    $shippingOptions = [
        ['value' => 'post', 'label' => $say('Post — priority mail', 'پست پیشتاز')],
        ['value' => 'tipax', 'label' => $say('Tipax', 'تیپاکس')],
        ['value' => 'pickup', 'label' => $say('Pickup at the warehouse', 'تحویل حضوری در انبار'), 'description' => $say('Istanbul, Kadıköy', 'استانبول، قاضی‌کوی')],
        ['value' => 'courier', 'label' => $say('Bike courier', 'پیک موتوری'), 'description' => $say('Istanbul and Bursa only', 'فعلاً فقط استانبول و بورسا'), 'disabled' => true],
    ];
    $currentShipping = collect($shippingOptions)->firstWhere('value', $shipping) ?? $shippingOptions[0];

    $digits = (string) ($state['digits'] ?? 'fa');
    $currency = (string) ($state['currency'] ?? 'toman');
    $unit = $currency === 'toman' ? $say('tomans', 'تومان') : $say('rials', 'ریال');
    $sample = $currency === 'toman' ? 1250000 : 12500000;
    $preview = NabuXUI::formatNumber($sample, 0, $digits === 'fa' ? 'fa' : 'en').' '.$unit;
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The plan upgrade, priced as you pick', 'ارتقای پلن، همان‌جا که انتخاب می‌کنید قیمت می‌خورد') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('One fieldset, one legend, three plans — each option carries its own description so the choice explains itself. The radio is native, so the arrow keys roam the group and the screen reader announces it under its legend; wire:model.live re-renders the summary with Persian digits straight from the locale.', 'یک fieldset، یک legend، سه پلن — هر گزینه توضیح خودش را دارد تا انتخاب خودش را توضیح دهد. رادیو بومی است، پس فلش‌های کیبورد در گروه می‌گردند و صفحه‌خوان کل گروه را زیر آن legend می‌خواند؛ wire:model.live خلاصه را با ارقام فارسیِ همان locale بازمی‌سازد.') }}
        </p>
    </div>
    <x-nx::radio-group legend="{{ $say('Your subscription plan', 'پلن اشتراک شما') }}" :value="$plan" wire:model.live="state.plan" :options="$plans" />
    <div class="pg-row" style="justify-content: space-between">
        <p style="margin: 0; color: var(--nx-text-muted)" aria-live="polite">
            {{ $say('Selected:', 'انتخاب‌شده:') }}
            <strong style="color: var(--nx-text)">{{ $currentPlan['label'] }}</strong>
            ·
            <strong style="color: var(--nx-text); font-variant-numeric: tabular-nums">{{ NabuXUI::formatNumber($currentPlan['price']).' '.$say('tomans / month', 'تومان در ماه') }}</strong>
        </p>
        <x-nx::button variant="primary" icon="check" wire:click="save('{{ $say('Plan updated', 'پلن به‌روز شد') }}')">{{ $say('Switch plan', 'تغییر پلن') }}</x-nx::button>
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Shipping method — the courier is off in Rome', 'روش ارسال — پیک در رم خاموش است') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The horizontal row for a short list: the cart ships to Rome, so the bike courier arrives disabled with its reason instead of vanishing. The delivery estimate under the group follows the pick live.', 'ردیف افقی برای فهرست کوتاه: سبد به رم می‌رود، پس پیک موتوری ازکارافتاده با دلیلش می‌آید، نه اینکه غیب شود. برآورد تحویل زیر گروه، انتخاب را زنده دنبال می‌کند.') }}
        </p>
    </div>
    <x-nx::radio-group legend="{{ $say('How should we ship order #981?', 'سفارش ۹۸۱ چگونه ارسال شود؟') }}" orientation="horizontal" :value="$shipping" wire:model.live="state.shipping" :options="$shippingOptions" />
    <p style="margin: 0; color: var(--nx-text-muted)" aria-live="polite">
        {{ $say('Arrives', 'تحویل') }}
        <strong style="color: var(--nx-text)">{{ $currentShipping['label'] }}</strong>
        ·
        <strong style="color: var(--nx-text)">{{ $arrivals[$shipping] ?? $arrivals['post'] }}</strong>
    </p>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Storefront display settings', 'تنظیمات نمایش ویترین') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Two small groups of one settings card: digits (Persian or Latin) and the price unit (tomans or rials). The preview line runs the real NabuXUI::formatNumber, so what you read is what the storefront will print.', 'دو گروه کوچک از یک کارت تنظیمات: ارقام (فارسی یا لاتین) و واحد قیمت (تومان یا ریال). خط پیش‌نما همان NabuXUI::formatNumber واقعی را اجرا می‌کند، پس آنچه می‌خوانید همان است که ویترین چاپ می‌کند.') }}
        </p>
    </div>
    <div class="pg-grid">
        <x-nx::radio-group legend="{{ $say('Digits', 'ارقام') }}" :value="$digits" wire:model.live="state.digits" :options="[
            ['value' => 'fa', 'label' => $say('Persian — ۱۲۳', 'فارسی — ۱۲۳')],
            ['value' => 'en', 'label' => $say('Latin — 123', 'لاتین — 123')],
        ]" />
        <x-nx::radio-group legend="{{ $say('Price unit', 'واحد قیمت') }}" :value="$currency" wire:model.live="state.currency" :options="[
            ['value' => 'toman', 'label' => $say('Tomans', 'تومان')],
            ['value' => 'rial', 'label' => $say('Rials', 'ریال')],
        ]" />
    </div>
    <div class="pg-row" style="justify-content: space-between">
        <p style="margin: 0; color: var(--nx-text-muted)" aria-live="polite">
            {{ $say('Preview of a 1,250,000-toman item:', 'پیش‌نمای کالای ۱٬۲۵۰٬۰۰۰ تومانی:') }}
            <strong style="color: var(--nx-text); font-variant-numeric: tabular-nums">{{ $preview }}</strong>
        </p>
        <x-nx::button variant="secondary" wire:click="save('{{ $say('Display settings saved', 'تنظیمات نمایش ذخیره شد') }}')">{{ $say('Save settings', 'ذخیرهٔ تنظیمات') }}</x-nx:button>
    </div>
</section>
