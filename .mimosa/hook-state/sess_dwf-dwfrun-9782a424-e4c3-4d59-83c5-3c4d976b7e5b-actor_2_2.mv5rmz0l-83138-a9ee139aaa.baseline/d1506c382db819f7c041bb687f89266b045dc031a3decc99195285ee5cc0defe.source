{{--
    The tooltip where it earns its keep: the invoice row's icon-only
    actions (named by aria-label, described by the tip), the checkout
    summary's fee terms — where an info button answers "what is this?"
    and a clipped customer pill reads in full — and a locked delete
    that keeps its reason one hover away.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $actions = [
        ['icon' => 'copy', 'label' => $say('Copy share link', 'کپی پیوند اشتراک'), 'tip' => $say('A link that opens the invoice without a login for 72 hours.', 'پیوندی که ۷۲ ساعت بدون ورود، فاکتور را باز می‌کند.'), 'ping' => $say('Share link copied', 'پیوند اشتراک کپی شد')],
        ['icon' => 'file', 'label' => $say('Download the PDF', 'دریافت PDF'), 'tip' => $say('Stamped with the digital seal, ready for the accountant.', 'با مهر دیجیتال، آمادهٔ دست حسابدار.'), 'ping' => $say('The PDF is being prepared', 'PDF آماده می‌شود')],
        ['icon' => 'external-link', 'label' => $say('Open the public page', 'باز کردن صفحهٔ عمومی'), 'tip' => $say('The buyer-facing page, exactly as the customer sees it.', 'همان صفحه‌ای که مشتری می‌بیند، بدون رفت‌وبرگشت.'), 'ping' => $say('Public page opened', 'صفحهٔ عمومی باز شد')],
    ];

    $fees = [
        [
            'label' => $say('Payment gateway fee', 'کارمزد درگاه پرداخت'),
            'amount' => NabuXUI::formatNumber(18400).' '.$say('Toman', 'تومان'),
            'tip' => $say('1% of each transaction, capped at '.NabuXUI::formatNumber(20000).' tomans.', '۱٪ مبلغ هر تراکنش، حداکثر '.NabuXUI::formatNumber(20000).' تومان.'),
        ],
        [
            'label' => $say('Value-added tax', 'مالی بر ارزش افزوده'),
            'amount' => NabuXUI::formatNumber(1656).' '.$say('Toman', 'تومان'),
            'tip' => $say('9% on the service fee only — never on the goods.', '۹٪ فقط روی کارمزد خدمات — هرگز بهای کالا.'),
        ],
    ];

    $customer = $say('Amara Okafor — order #981, Esfand 1403', 'آمارا اوکافور — سفارش ۹۸۱، اسفند ۱۴۰۳');
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Icon-only actions on the invoice row', 'کنش‌های فقط-آیکونی روی سطر فاکتور') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Hover a button and after the default 350 ms the tip settles below the row, clear of the row above; Tab brings it up at once and Escape closes it. The tip describes (aria-describedby) while the name still comes from aria-label — an icon-only button keeps both.', 'نشانگر را روی دکمه‌ای ببرید تا پس از تاخیر پیش‌فرض ۳۵۰ میلی‌ثانیه، توضیح زیر ردیف بنشیند و ردیف بالا خوانا بماند؛ با Tab بی‌درنگ می‌آید و با Escape بسته می‌شود. تول‌تیپ توضیح می‌دهد (aria-describedby) و نام همچنان از aria-label می‌آید — دکمهٔ فقط-آیکونی هر دو را نگه می‌دارد.') }}
        </p>
    </div>
    <div class="pg-row" style="justify-content: space-between; padding: .75rem 1rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg)">
        <span class="pg-row" style="gap: .6rem">
            <strong style="font-weight: 600">{{ $say('Invoice', 'فاکتور').' '.NabuXUI::formatNumber(1042) }}</strong>
            <x-nx::badge tone="success" dot>{{ $say('Paid', 'پرداخت شد') }}</x-nx::badge>
        </span>
        <span class="pg-row" style="gap: .25rem">
            @foreach ($actions as $action)
                <x-nx::tooltip side="bottom" :text="$action['tip']">
                    <x-nx::button size="sm" variant="ghost" :icon="$action['icon']" icon-only
                        :aria-label="$action['label']" wire:click="ping('{{ $action['ping'] }}')" />
                </x-nx::tooltip>
            @endforeach
        </span>
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Fee terms and a clipped name in the checkout summary', 'اصطلاح‌های کارمزد و نام بریده‌شده در خلاصهٔ پرداخت') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The default side lands the tip above its trigger, away from the amounts underneath. The info button answers “what is this fee?”, and the buyer’s pill — clipped by the narrow column — reads in full through its own tip; it is focusable with tabindex, so the keyboard sees it too.', 'سمت پیش‌فرض توضیح را بالای ماشه می‌نشاند، دور از مبالغ زیرش. دکمهٔ info به «این کارمزد چیست؟» جواب می‌دهد و برچسب گرد مشتری — که ستون باریک بریده‌اش کرده — کامل در تول‌تیپ خودش خوانده می‌شود؛ با tabindex فوکوس‌پذیر شده تا کیبورد هم ببیندش.') }}
        </p>
    </div>
    <ul style="list-style: none; margin: 0; padding: 0; display: grid; gap: .5rem">
        @foreach ($fees as $fee)
            <li class="pg-row" style="justify-content: space-between; padding: .6rem .9rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg)">
                <span class="pg-row" style="gap: .5rem">
                    <x-nx::tooltip :text="$fee['tip']">
                        <x-nx::button size="sm" variant="ghost" icon="info" icon-only
                            aria-label="{{ $say('What is this?', 'این چیست؟') }} {{ $fee['label'] }}" />
                    </x-nx::tooltip>
                    <strong style="font-weight: 600">{{ $fee['label'] }}</strong>
                </span>
                <span style="color: var(--nx-text-muted)">{{ $fee['amount'] }}</span>
            </li>
        @endforeach
        <li class="pg-row" style="justify-content: space-between; padding: .6rem .9rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg)">
            <span class="pg-row" style="gap: .5rem">
                <strong style="font-weight: 600">{{ $say('Buyer', 'خریدار') }}</strong>
            </span>
            <x-nx::tooltip :text="$customer">
                <span tabindex="0" style="max-inline-size: 11rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; padding: .3rem .8rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-full); color: var(--nx-text-muted)">{{ $customer }}</span>
            </x-nx::tooltip>
        </li>
    </ul>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The locked delete says why', 'حذف قفل‌شده، دلیلش را می‌گوید') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('A truly disabled button never sees the pointer, so its reason stays hidden. The sound pattern is a live button carrying aria-disabled: the tip settles on its inline-end side with a shorter 150 ms delay, and the click answers with a warning toast instead of pretending nothing happened.', 'دکمهٔ واقعاً disabled رویداد اشاره‌گر را نمی‌گیرد و دلیلش پنهان می‌ماند. الگوی درست، دکمهٔ زنده‌ای است که aria-disabled دارد: توضیح با تاخیر کوتاه‌تر ۱۵۰ میلی‌ثانیه سمت انتهای خودش می‌نشیند و کلیک به‌جای بی‌اعتنایی، با یک توست هشدار جواب می‌دهد.') }}
        </p>
    </div>
    <div class="pg-row" style="justify-content: space-between; padding: .75rem 1rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg)">
        <span style="display: grid; gap: .15rem">
            <strong style="font-weight: 600">{{ $say('Delete the workspace', 'حذف ورک‌اسپیس') }}</strong>
            <span style="color: var(--nx-text-muted)">{{ $say('One unsettled payout is still open.', 'یک تسویهٔ باز مانده است.') }}</span>
        </span>
        <x-nx::tooltip side="end" :delay="150"
            :text="$say('Locked while a payout is unsettled — support can force it.', 'تا وقتی تسویه‌ای باز است قفل است — پشتیبانی می‌تواند اجبار کند.')">
            <x-nx::button size="sm" variant="ghost" icon="trash" icon-only aria-disabled="true"
                aria-label="{{ $say('Delete the workspace', 'حذف ورک‌اسپیس') }}"
                @click="$nxToast.warning('{{ $say('Locked: one payout is still unsettled.', 'قفل است: یک تسویه هنوز باز است.') }}')" />
        </x-nx::tooltip>
    </div>
</section>
