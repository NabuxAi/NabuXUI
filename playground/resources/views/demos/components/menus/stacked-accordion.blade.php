{{--
    The stacked accordion twice: checkout shipping (single, one card at a time,
    rich slot content) and a support FAQ (multiple, several cards together).
    Both bind their open ids to Livewire.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Pick a shipping method', 'انتخاب روش ارسال') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('type="single": opening a card fans the deck out and grows the chosen one; its slot carries the real details. The open id travels to Livewire.', 'با type="single" باز کردن یک کارت دسته را باز می‌پراکند و همان کارت بزرگ می‌شود؛ اسلاتش جزئیات واقعی را می‌گیرد. شناسهٔ باز به Livewire می‌رود.') }}
            </p>
        </div>
        <x-nx::stacked-accordion type="single" :value="$state['ship'] ?? ['express']" wire:model.live="state.ship" :items="[
            ['id' => 'express', 'title' => $say('Same-hour courier', 'پیک فوری'), 'subtitle' => $say('Tehran, under 2 hours', 'تهران، زیر ۲ ساعت'), 'icon' => 'zap'],
            ['id' => 'post', 'title' => $say('Priority post', 'پست پیشتاز'), 'subtitle' => $say('Nationwide, 2–4 days', 'سراسر کشور، ۲ تا ۴ روز'), 'icon' => 'globe'],
            ['id' => 'pickup', 'title' => $say('Store pickup', 'دریافت از فروشگاه'), 'subtitle' => $say('Ready in an hour', 'تا یک ساعت آماده'), 'icon' => 'home'],
        ]">
            <x-slot:express>
                <p style="margin: 0 0 .5rem">{{ $say('Riders nearby right now:', 'پیک‌های اطراف همین حالا:') }} <strong>{{ NabuXUI::formatNumber(14) }}</strong></p>
                <p style="margin: 0 0 .75rem; color: var(--nx-text-muted)">{{ $say('Price is calculated per kilometer; the parcel is insured up to 5 million tomans.', 'قیمت بر اساس کیلومتر حساب می‌شود؛ مرسوله تا ۵ میلیون تومان بیمه است.') }}</p>
                <x-nx::button size="sm" variant="primary" icon="check" wire:click="save(@js($say('Courier booked', 'پیک رزرو شد')))">{{ $say('Book the courier', 'رزرو پیک') }}</x-nx::button>
            </x-slot:express>
            <x-slot:post>
                <p style="margin: 0 0 .75rem">{{ $say('Free on orders above one million tomans — otherwise', 'روی سفارش‌های بالای یک میلیون تومان رایگان؛ وگرنه') }} <strong>{{ NabuXUI::formatNumber(38000).' '.$say('tomans', 'تومان') }}</strong></p>
                <x-nx::button size="sm" variant="secondary" icon="chart" wire:click="ping(@js($say('Tracking will be texted to you', 'کد رهگیری پیامک می‌شود')) )">{{ $say('Track my parcel', 'رهگیری مرسوله') }}</x-nx::button>
            </x-slot:post>
            <x-slot:pickup>
                <p style="margin: 0 0 .75rem">{{ $say('Two stores in Tehran hold your basket for an hour:', 'دو شعبه در تهران سبد شما را تا یک ساعت نگه می‌دارند:') }}</p>
                <div class="pg-row">
                    <x-nx::badge tone="accent">{{ $say('Vanak · open now', 'ونک · باز است') }}</x-nx::badge>
                    <x-nx::badge>{{ $say('Tajrish · closes 21:00', 'تجریش · تا ۲۱:۰۰') }}</x-nx::badge>
                </div>
            </x-slot:pickup>
        </x-nx::stacked-accordion>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('Chosen on the server:', 'انتخاب‌شده روی سرور:') }}
            <code>{{ json_encode($state['ship'] ?? ['express']) }}</code>
        </p>
    </section>

    <section class="pg-box" style="gap: 1rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The support FAQ, several at once', 'پرسش‌های پرتکرار پشتیبانی، چندتا با هم') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('type="multiple" keeps every open card open; the ids land in Livewire as an array.', 'با type="multiple" همهٔ کارت‌های باز باز می‌مانند؛ شناسه‌ها به‌شکل آرایه به Livewire می‌روند.') }}
            </p>
        </div>
        <x-nx::stacked-accordion type="multiple" :value="$state['faq'] ?? ['refund']" wire:model.live="state.faq" :items="[
            ['id' => 'refund', 'title' => $say('Refunds', 'بازپرداخت'), 'subtitle' => $say('30 days, no questions', '۳۰ روز، بدون پرسش'), 'icon' => 'heart'],
            ['id' => 'invoice', 'title' => $say('Invoices', 'فاکتور رسمی'), 'subtitle' => $say('Sent on the 1st', 'اول هر ماه فرستاده می‌شود'), 'icon' => 'file'],
            ['id' => 'sms', 'title' => $say('Delivery texts', 'پیامک مرسوله'), 'subtitle' => $say('Two per shipment', 'دو پیام برای هر مرسوله'), 'icon' => 'message'],
            ['id' => 'api', 'title' => $say('API access', 'دسترسی API'), 'subtitle' => $say('On the Pro plan', 'در طرح پرو'), 'icon' => 'cpu', 'disabled' => true],
        ]" />
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('Open cards:', 'کارت‌های باز:') }}
            <code>{{ json_encode($state['faq'] ?? ['refund']) }}</code>
        </p>
    </section>
</div>
