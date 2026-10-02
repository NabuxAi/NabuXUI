{{--
    The card in three real seats: an app-store shelf of destination cards
    (each card one link — the spotlight chases the pointer and the featured
    one leans toward it), a pending order whose action footer rides
    wire:loading next to a small outline add-on, and the plan picker where
    variants carry meaning — gradient for the recommendation, inverse for
    enterprise — under a glass trial banner floating on the aurora.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $n = fn (float|int $value, int $decimals = 0): string => NabuXUI::formatNumber($value, $decimals);

    $apps = [
        ['icon' => 'zap', 'featured' => true, 'title' => $say('NabuGate', 'نابوگیت'),
            'description' => $say('Direct payment gateway with same-day settlement.', 'درگاه پرداخت مستقیم با تسویهٔ روزانه.'),
            'rating' => 4.9, 'installs' => 12840, 'category' => $say('Finance', 'مالی')],
        ['icon' => 'message', 'featured' => false, 'title' => $say('SmsYar', 'پیامک‌یار'),
            'description' => $say('Order-status texts and abandoned-cart nudges.', 'پیام وضعیت سفارش و یادآوری سبد رهاشده.'),
            'rating' => 4.7, 'installs' => 8212, 'category' => $say('Marketing', 'بازاریابی')],
        ['icon' => 'chart', 'featured' => false, 'title' => $say('Tarazoo', 'ترازو'),
            'description' => $say('Daily ledger, taxes and per-product profit.', 'دفتر روزانه، مالیات و سود هر محصول.'),
            'rating' => 4.8, 'installs' => 5907, 'category' => $say('Accounting', 'حسابداری')],
    ];

    $lines = [
        ['name' => $say('White leather sneakers', 'کتانی چرم سفید'), 'qty' => 1, 'price' => 1250000],
        ['name' => $say('Thick wool socks', 'جوراب پشمی ضخیم'), 'qty' => 2, 'price' => 390000],
        ['name' => $say('Express shipping', 'ارسال پیشتاز'), 'qty' => 1, 'price' => 205000],
    ];
    $total = array_sum(array_column($lines, 'price'));

    $plan = (string) ($state['plan'] ?? 'base');
    $plans = [
        ['id' => 'base', 'variant' => null, 'icon' => 'layers', 'spotlight' => false,
            'title' => $say('Base', 'پایه'), 'price' => 290000,
            'description' => $say('For a store finding its feet.', 'برای فروشگاهی که تازه جا افتاده.'),
            'action' => $say('Choose Base', 'انتخاب پایه'),
            'features' => [
                $say('5 teammates', '۵ هم‌تیمی'),
                $say('1,000 orders a month', '۱٬۰۰۰ سفارش در ماه'),
                $say('Email support', 'پشتیبانی ایمیلی'),
            ]],
        ['id' => 'pro', 'variant' => 'gradient', 'icon' => 'sparkles', 'spotlight' => true,
            'title' => $say('Pro', 'حرفه‌ای'), 'price' => 490000,
            'description' => $say('Our recommendation for a growing store.', 'پیشنهاد ما برای فروشگاهی که دارد بزرگ می‌شود.'),
            'action' => $say('Upgrade to Pro', 'ارتقا به حرفه‌ای'),
            'features' => [
                $say('Unlimited teammates', 'هم‌تیمی نامحدود'),
                $say('NabuGate payment gateway', 'درگاه پرداخت نابوگیت'),
                $say('Profit per product', 'سود هر محصول، جدا'),
            ]],
        ['id' => 'enterprise', 'variant' => 'inverse', 'icon' => 'users', 'spotlight' => false,
            'title' => $say('Enterprise', 'سازمانی'), 'price' => null,
            'description' => $say('Multi-branch inventory on a contract.', 'انبار چندشعبه‌ای، با قرارداد.'),
            'action' => $say('Talk to sales', 'صحبت با فروش'),
            'features' => [
                $say('Branch inventory sync', 'هم‌گام‌سازی انبار شعب'),
                $say('Formal contracts and invoices', 'قرارداد و فاکتور رسمی'),
                $say('A dedicated agent', 'کارشناس اختصاصی'),
            ]],
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The app-store shelf', 'قفسهٔ فروشگاه اپ') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Each card is one destination: hover lifts it, the spotlight follows the pointer and the featured card leans toward your cursor. Ratings and install counts run through the locale formatter so the digits stay Persian.', 'هر کارت یک مقصد است: هاور بالا می‌آوردش، نور دنبال اشاره‌گر می‌آید و کارت پیشنهادی به سمت ماوس خم می‌شود. امتیاز و شمار نصب از فرمت‌کنندهٔ locale رد می‌شوند تا ارقام فارسی بمانند.') }}
        </p>
    </div>
    <div class="pg-grid">
        @foreach ($apps as $app)
            <x-nx::card :icon="$app['icon']" :title="$app['title']" :description="$app['description']"
                interactive spotlight :tilt="$app['featured']" href="#">
                <p class="pg-row" style="margin: 0; gap: .5rem; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
                    <span class="pg-row" style="gap: .3rem; color: var(--nx-gold-text)">
                        <x-nx::icon name="star" /> {{ $n($app['rating'], 1) }}
                    </span>
                    <span aria-hidden="true">·</span>
                    <span>{{ $n($app['installs']) }} {{ $say('active installs', 'نصب فعال') }}</span>
                </p>
                <x-slot:footer>
                    <x-nx::badge tone="neutral">{{ $app['category'] }}</x-nx::badge>
                    @if ($app['featured'])
                        <x-nx::badge tone="gold" variant="solid">{{ $say('Nabu’s pick', 'انتخاب نابو') }}</x-nx::badge>
                    @endif
                    <span style="margin-inline-start: auto; color: var(--nx-accent-text)" aria-hidden="true">
                        <x-nx::icon name="arrow-right" />
                    </span>
                </x-slot:footer>
            </x-nx::card>
        @endforeach
    </div>
    <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
        {{ $say('With href the whole card becomes one <a>, so Tab and Enter work like any link; the tilt follows mouse and pen only — touch is ignored on purpose.', 'با href کل کارت به یک <a> تبدیل می‌شود، پس Tab و Enter مثل هر لینکی کار می‌کنند؛ خم‌شدن فقط ماوس و قلم را دنبال می‌کند — لمس عمداً نادیده گرفته می‌شود.') }}
    </p>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The order awaiting payment', 'سفارش در انتظار پرداخت') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('No href here — the card is a container: line items and the total in the body, the actions in the footer. «Send a reminder» sleeps 900ms on the server so the button’s spinner (wire:loading) gets to show itself.', 'اینجا href نیست — کارت فقط ظرف است: اقلام و جمع در بدنه، کنش‌ها در فوتر. «یادآوری پرداخت» ۹۰۰ میلی‌ثانیه سمت سرور می‌خوابد تا چرخش دکمه (wire:loading) دیده شود.') }}
        </p>
    </div>
    <div class="pg-grid">
        <x-nx::card icon="file"
            :title="$say('Order #'.$n(1024).' — awaiting payment', 'سفارش '.$n(1024).' — در انتظار پرداخت')"
            :description="$say('Placed 20 minutes ago; the stock hold lifts at midnight.', '۲۰ دقیقه پیش ثبت شده؛ موجودی تا نیمه‌شب برایش نگه داشته می‌شود.')">
            <ul style="list-style: none; margin: 0; padding: 0; display: grid; gap: .6rem">
                @foreach ($lines as $line)
                    <li class="pg-row" style="justify-content: space-between; gap: 1rem">
                        <span>{{ $line['name'] }} <span style="color: var(--nx-text-muted)">×{{ $n($line['qty']) }}</span></span>
                        <span style="font-variant-numeric: tabular-nums">{{ $n($line['price']) }}</span>
                    </li>
                @endforeach
            </ul>
            <p class="pg-row" style="justify-content: space-between; margin: .9rem 0 0; padding-block-start: .9rem; border-block-start: 1px solid var(--nx-border); font-weight: 650">
                <span>{{ $say('Order total', 'جمع سفارش') }}</span>
                <span style="font-variant-numeric: tabular-nums">{{ $n($total) }} {{ $say('Toman', 'تومان') }}</span>
            </p>
            <x-slot:footer>
                <x-nx::button variant="ghost" icon="external-link" href="#">{{ $say('Open the invoice', 'مشاهدهٔ فاکتور') }}</x-nx::button>
                <x-nx::button variant="primary" icon="bell" wire:click="save('{{ $say('Reminder sent to the customer', 'یادآوری برای مشتری پیامک شد') }}')">
                    {{ $say('Send a payment reminder', 'یادآوری پرداخت') }}
                </x-nx::button>
            </x-slot:footer>
        </x-nx::card>

        <x-nx::card size="sm" variant="outline" icon="shield"
            :title="$say('Shipping insurance', 'بیمهٔ مرسوله')"
            :description="$say('Damage and loss stay covered until the parcel is delivered — an outline card keeps the add-on quiet beside the order.', 'خرابی و گم‌شدن تا لحظهٔ تحویل پوشش دارد — کارت outline کنار سفارش، اضافه‌خدمت را آرام نگه می‌دارد.')">
            <x-slot:footer>
                <x-nx::button size="sm" variant="ghost" icon="plus" wire:click="ping('{{ $say('Shipping insurance added to the order', 'بیمهٔ ارسال به سفارش افزوده شد') }}')">
                    {{ $say('Add for', 'افزودن برای') }} {{ $n(15000) }} {{ $say('Toman', 'تومان') }}
                </x-nx::button>
            </x-slot:footer>
        </x-nx::card>
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Upgrading the plan', 'ارتقای پلن') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Variants carry meaning: Pro — our pick — sits in the gradient frame with the spotlight, Enterprise on the inverse surface; choosing a plan moves the “current plan” badge through $set, and the glass trial card floats on the aurora in both themes.', 'واریانت‌ها معنا حمل می‌کنند: حرفه‌ای — پیشنهاد ما — در قاب گرادیانی با نورافکن می‌نشیند و سازمانی روی سطح وارونه؛ انتخاب پلن نشان «پلن فعلی» را با $set جابه‌جا می‌کند و کارت شیشه‌ای تست در هر دو تم روی اورورا شناور است.') }}
        </p>
    </div>
    <div style="border-radius: var(--nx-radius-2xl); background: var(--nx-gradient-aurora); padding: 1rem">
        <x-nx::card variant="glass" icon="zap"
            :title="$say('Seven days of Pro, on us', '۷ روز حرفه‌ای، مهمان ما')"
            :description="$say('Every feature unlocked, no bank card needed.', 'همهٔ امکانات باز است و کارت بانکی نمی‌خواهد.')">
            <x-slot:footer>
                <x-nx::button variant="primary" size="sm" wire:click="ping('{{ $say('Free trial started', 'تست رایگان آغاز شد') }}')">
                    {{ $say('Start the trial', 'آغاز تست رایگان') }}
                </x-nx::button>
            </x-slot:footer>
        </x-nx::card>
    </div>
    <div class="pg-grid">
        @foreach ($plans as $p)
            <x-nx::card :variant="$p['variant']" :icon="$p['icon']" :title="$p['title']"
                :description="$p['description']" :interactive="$p['spotlight']" :spotlight="$p['spotlight']">
                <p style="margin: 0; font: 700 var(--nx-text-xl) / 1.2 var(--nx-font-display); font-variant-numeric: tabular-nums">
                    @if ($p['price'] !== null)
                        {{ $n($p['price']) }} <span style="font: 500 var(--nx-text-sm) var(--nx-font-sans); color: var(--nx-text-muted)">{{ $say('Toman / month', 'تومان / ماه') }}</span>
                    @else
                        <span style="font: 500 var(--nx-text-lg) var(--nx-font-sans); color: var(--nx-text-muted)">{{ $say('By agreement', 'توافقی') }}</span>
                    @endif
                </p>
                <ul style="list-style: none; margin: 0; padding: 0; display: grid; gap: .5rem; font-size: var(--nx-text-sm)">
                    @foreach ($p['features'] as $feature)
                        <li class="pg-row" style="gap: .5rem">
                            <span style="color: var(--nx-success-text)"><x-nx::icon name="check" /></span>
                            {{ $feature }}
                        </li>
                    @endforeach
                </ul>
                <x-slot:footer>
                    @if ($plan === $p['id'])
                        <x-nx::badge tone="success" dot>{{ $say('Your current plan', 'پلن فعلی شما') }}</x-nx::badge>
                    @else
                        <x-nx::button :variant="$p['spotlight'] ? 'primary' : 'secondary'" wire:click="$set('state.plan', '{{ $p['id'] }}')">
                            {{ $p['action'] }}
                        </x-nx::button>
                    @endif
                </x-slot:footer>
            </x-nx::card>
        @endforeach
    </div>
    <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
        {{ $say('The recommended card gets interactive + spotlight: it lifts on hover and its icon rolls, while the plain cards stay still — the same surface, saying different things.', 'کارت پیشنهادی interactive + spotlight می‌گیرد: هاور بالا می‌آوردش و آیکونش می‌چرخد، کارت‌های ساده ساکت می‌مانند — یک سطح، با حرف‌های متفاوت.') }}
    </p>
</section>
