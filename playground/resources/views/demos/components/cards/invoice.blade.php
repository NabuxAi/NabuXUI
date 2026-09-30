{{--
    The invoice's real scenarios: a full studio invoice whose paid/unpaid
    state is flipped by a Livewire action (watch the badge morph), plus a
    minimal draft for internal work.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $paid = (bool) ($state['paid'] ?? false);
    $lines = [
        ['title' => $say('Design system', 'سیستم طراحی'), 'description' => $say('Tokens, components, documentation', 'توکن‌ها، کامپوننت‌ها، مستندات'), 'quantity' => 1, 'unitPrice' => 480000000],
        ['title' => $say('Monthly support', 'پشتیبانی ماهانه'), 'description' => $say('Two dedicated days', 'دو روز اختصاصی در ماه'), 'quantity' => 3, 'unitPrice' => 25000000],
        ['title' => $say('Motion workshop', 'کارگاه حرکت'), 'description' => $say('Half a day, whole team', 'نیم‌روز، کل تیم'), 'quantity' => 2, 'unitPrice' => 18000000],
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div class="pg-row" style="justify-content: space-between">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The March retainer, as the client prints it', 'قرارداد ماهانهٔ مارس، همان‌طور که مشتری چاپش می‌کند') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Rows rise one after another, the totals roll their digits, and @media print turns it into the plain paper document. Mark it paid and watch the badge morph through the Livewire round trip.', 'ردیف‌ها یکی‌یکی بالا می‌آیند، جمع‌ها ارقامشان را می‌غلتانند و @media print از آن سند کاغذی ساده می‌سازد. پرداخت‌شده علامت بزنید و مورف نشان را در رفت‌وبرگشت Livewire ببینید.') }}
            </p>
        </div>
        <x-nx::button variant="primary" icon="check" wire:click="$set('state.paid', true); ping(@js($say('Invoice marked as paid', 'فاکتور پرداخت‌شده علامت خورد')))" :disabled="$paid">
            {{ $paid ? $say('Paid · thank you', 'پرداخت شد · ممنون') : $say('Mark as paid', 'پرداخت‌شده علامت بزن') }}
        </x-nx::button>
    </div>
    <x-nx::invoice
        number="INV-1404-082"
        :status="$paid ? 'paid' : 'unpaid'"
        :status-label="$paid ? null : $say('Awaiting payment', 'در انتظار پرداخت')"
        currency="تومان"
        :brand="$say('Nabu Studio', 'استودیو نابو')"
        :brand-tagline="$say('Design engineering', 'مهندسی طراحی')"
        issue-date="۱۴۰۴/۰۷/۰۸"
        due-date="۱۴۰۴/۰۷/۳۰"
        :from="['name' => $say('Nabu Studio', 'استودیو نابو'), 'lines' => [$say('Tehran, Iran', 'تهران، ایران'), 'hello@nabu.studio', $say('Tax id 1400…', 'شناسه ملی ۱۴۰۰…')]]"
        :to="['name' => $say('Acme Corp', 'شرکت آدم'), 'lines' => [$say('Tehran, Iran', 'تهران، ایران'), 'accounts@acme.ir']]"
        :lines="$lines"
        :extra-totals="[['label' => $say('VAT (9%)', 'مالیات بر ارزش افزوده (۹٪)'), 'percent' => 0.09]]"
        :note="$say('Payable by the due date; afterwards a late fee of 2% per month applies.', 'پرداخت تا موعد اعلام‌شده؛ پس از آن دیرکرد ۲٪ در ماه حساب می‌شود.')"
        :footer="$say('Nabu Studio · hello@nabu.studio · Thank you for building with us.', 'استودیو نابو · hello@nabu.studio · سپاس از اینکه با ما ساختید.')" />
</section>

<section class="pg-box" style="gap: 1.25rem; max-inline-size: 40rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A quiet internal draft', 'پیش‌نویس داخلیِ آرام') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The same block with status draft, no tax steps, no note — internal hours before they become a real invoice. decimals drops the fraction digits.', 'همان بلوک با وضعیت پیش‌نویس، بدون پلهٔ مالیات و بدون یادداشت — ساعات داخلی پیش از آن‌که فاکتور واقعی شوند. decimals ارقام اعشار را کنار می‌گذارد.') }}
        </p>
    </div>
    <x-nx::invoice
        number="DRAFT-31"
        status="draft"
        :brand="$say('Nabu Studio', 'استودیو نابو')"
        :from="['name' => $say('Nabu Studio', 'استودیو نابو')]"
        :to="['name' => $say('Internal · design review', 'داخلی · بازبینی طراحی')]"
        :lines="[
            ['title' => $say('Accessibility audit', 'بازبینی دسترس‌پذیری'), 'quantity' => 6, 'unitPrice' => 9000000],
            ['title' => $say('RTL sweep', 'پیمایش راست‌به‌چپ'), 'quantity' => 4, 'unitPrice' => 7500000],
        ]"
        :footer="$say('Not billable — for the Tuesday review only.', 'قابل پرداخت نیست — فقط برای بازبینی سه‌شنبه.')" />
    <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
        {{ $say('Print either one straight from the browser — the block ships its own print stylesheet.', 'هرکدام را مستقیم از مرورگر چاپ کنید — بلوک شیوه‌نامهٔ چاپ خودش را همراه دارد.') }}
    </p>
</section>
