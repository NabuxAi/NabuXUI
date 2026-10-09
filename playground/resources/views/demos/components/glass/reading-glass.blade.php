{{--
    The reading glass over dense, real content: a terms passage with the
    default circle lens, then a pill lens parked over an order’s fine print.
    The lens is draggable, throwable, and arrow-key drivable; the content
    under it stays selectable and clickable.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1rem; padding: 0; overflow: clip">
    <x-nx::reading-glass :x="0.5" :y="0.4" :size="152" :label="$say('Terms lens', 'عدسی شرایط')">
        <div style="display: grid; gap: 1rem; padding: 2rem; max-inline-size: 62ch">
            <div>
                <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Subscription terms, the honest version', 'شرایط اشتراک، نسخهٔ رک') }}</h3>
                <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                    {{ $say('Drop the lens on the paragraph and read the small parts magnified — this sentence stays selectable under the glass.', 'عدسی را روی بند بگذارید و ریزه‌کاری‌ها را بزرگ بخوانید — همین جمله زیر شیشه هم انتخاب‌پذیر می‌ماند.') }}
                </p>
            </div>
            <p style="margin: 0; line-height: 2; font-size: var(--nx-text-sm)">
                {{ $fa
                    ? 'اشتراک ماهانه است و تا بیست‌وچهار ساعت پیش از تمدید خودکار قابل لغو؛ وجه به‌طور کامل و بدون پرسش بازگردانده می‌شود. نرخ‌ها با ارقام زبان شما نمایش داده می‌شوند و مالیات بر ارزش افزوده جداگانه محاسبه می‌شود. اگر سرویسی بیش از نود‌و‌شش ساعت قطع بماند، آن ماه به‌صورت خودکار اعتبار می‌شود.'
                    : 'Billing recurs monthly and cancels any time up to twenty-four hours before renewal; refunds are full and unquestioned. Figures render in your locale’s digits, with value-added tax computed separately. Should any service stay down longer than ninety-six hours, that month is credited automatically.' }}
            </p>
            <div class="pg-row">
                <x-nx::button size="sm" variant="secondary" icon="check" wire:click="ping(@js($say('Terms accepted (thanks for reading!)', 'شرایط پذیرفته شد (ممنون از خواندن!)')))">{{ $say('I accept', 'می‌پذیرم') }}</x-nx::button>
                <x-nx::badge tone="info">{{ $say('clickable under the lens', 'زیر عدسی هم کلیک‌پذیر') }}</x-nx::badge>
            </div>
        </div>
    </x-nx::reading-glass>
</section>

<section class="pg-box" style="gap: 1rem; padding: 0; overflow: clip">
    <x-nx::reading-glass shape="pill" :size="180" :x="0.55" :y="0.5" :label="$say('Fine-print lens', 'عدسی ریزنویس')">
        <div style="display: grid; gap: 1rem; padding: 2rem">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The invoice’s fine print', 'ریزنویسی فاکتور') }}</h3>
            <div style="display: grid; gap: .375rem; font-size: var(--nx-text-xs); color: var(--nx-text-muted); line-height: 1.9">
                <span>{{ $say('Order', 'سفارش') }} #1042 · {{ $say('paid in 3 instalments', 'پرداخت در ۳ قسط') }} · {{ NabuXUI::formatNumber(426666).' '.$say('tomans each', 'تومان در هر قسط') }}</span>
                <span>{{ $say('Processing fee', 'کارمزد پردازش').': '.NabuXUI::formatNumber(9600).' '.$say('tomans', 'تومان') }} · {{ $say('insurance included', 'بیمه همراه است') }}</span>
                <span>{{ $say('Ships from', 'ارسال از').' '.($fa ? 'انبار استانبول' : 'the Istanbul depot').' · '.$say('weight', 'وزن').' '.NabuXUI::formatNumber(1.2, 1).'kg' }}</span>
                <span>{{ $say('Returns accepted until', 'بازگشت تا').' '.($fa ? '۱۴ آبان' : 'Nov 5').' · '.$say('no questions asked', 'بدون پرسش') }}</span>
            </div>
            <div class="pg-row">
                <x-nx::button size="sm" variant="ghost" icon="copy" wire:click="ping(@js($say('Fine print copied', 'ریزنویس کپی شد')))">{{ $say('Copy the print', 'کپی ریزنویس') }}</x-nx::button>
            </div>
        </div>
    </x-nx::reading-glass>
</section>
