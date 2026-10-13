{{--
    Badges as status labels on real things: an invoice ledger where every row
    earns its tone, then a billing header wearing the plan and the live count.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $invoices = [
        ['id' => 1404, 'who' => $say('Nabu Studio', 'استودیو نابو'), 'amount' => 48260000, 'badge' => 'paid', 'tone' => 'success', 'dot' => true],
        ['id' => 1405, 'who' => $say('Aban Clinic', 'کلینیک آبان'), 'amount' => 12750000, 'badge' => $say('Pending review', 'در انتظار بازبینی'), 'tone' => 'warning', 'dot' => true],
        ['id' => 1406, 'who' => $say('Kavir Tech', 'تکنولوژی کویر'), 'amount' => 9800000, 'badge' => $say('Overdue 9 days', '۹ روز معوق'), 'tone' => 'danger', 'dot' => true],
        ['id' => 1407, 'who' => $say('Studio Lume', 'استودیو لومه'), 'amount' => 3400000, 'badge' => $say('Draft', 'پیش‌نویس'), 'tone' => 'neutral', 'dot' => false],
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('An invoice ledger, every row a verdict', 'دفتر فاکتورها، هر ردیف یک حکم') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Soft tones by day; hover the overdue one and press the chase button — the toast is the receipt.', 'رنگ‌های ملایم برای کار روزمره؛ روی معوق رفته و دکمهٔ پیگیری را بزنید — توست رسیدِ کار است.') }}
        </p>
    </div>
    <ul style="list-style: none; margin: 0; padding: 0; display: grid; gap: .5rem">
        @foreach ($invoices as $invoice)
            <li class="pg-row" style="justify-content: space-between; padding: .7rem .9rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg)">
                <span class="pg-row" style="gap: .75rem">
                    <span dir="ltr" style="font: 500 var(--nx-text-sm) var(--nx-font-mono); color: var(--nx-text-muted)">#{{ NabuXUI::formatNumber($invoice['id']) }}</span>
                    <strong style="font-weight: 600">{{ $invoice['who'] }}</strong>
                </span>
                <span class="pg-row" style="gap: .75rem">
                    <span>{{ NabuXUI::formatNumber($invoice['amount']).' '.$say('Toman', 'تومان') }}</span>
                    @if ($invoice['tone'] === 'danger')
                        <x-nx::button size="xs" variant="ghost" icon="bell" wire:click="ping('{{ $say('Reminder sent to Kavir Tech', 'یادآوری به تکنولوژی کویر رفت') }}')">{{ $say('Chase', 'پیگیری') }}</x-nx::button>
                    @endif
                    <x-nx::badge :tone="$invoice['tone']" :dot="$invoice['dot']">{{ $invoice['badge'] === 'paid' ? $say('Paid', 'پرداخت شد') : $invoice['badge'] }}</x-nx::badge>
                </span>
            </li>
        @endforeach
    </ul>
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The billing header', 'سرصفحهٔ صورت‌حساب') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">{{ $say('solid gold announces the plan; the pulse marks a live number, not a cached one.', 'طلاییِ solid پلن را اعلام می‌کند؛ پالس عددِ زنده را نشان می‌دهد، نه کش‌شده را.') }}</p>
        <div class="pg-row" style="gap: .75rem">
            <x-nx::badge tone="gold" variant="solid" size="lg">{{ $say('Pro', 'پرو') }}</x-nx::badge>
            <x-nx::badge tone="accent" pulse>{{ $say('142 shoppers online', '۱۴۲ خریدار آنلاین') }}</x-nx::badge>
            <x-nx::badge tone="info">{{ $say('Renews on 30 Mehr', 'تمدید در ۳۰ مهر') }}</x-nx::badge>
        </div>
        <x-nx::button size="sm" variant="secondary" icon="sparkles" wire:click="save('{{ $say('Welcome to Max', 'به مکس خوش آمدید') }}')">{{ $say('Upgrade to Max', 'ارتقا به مکس') }}</x-nx::button>
    </section>

    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Every tone, one line', 'همهٔ رنگ‌ها، در یک خط') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">{{ $say('neutral · accent · success · warning · danger · info · gold — soft on top, solid below.', 'neutral · accent · success · warning · danger · info · gold — بالا soft، پایین solid.') }}</p>
        <div class="pg-row" style="gap: .5rem">
            <x-nx::badge>neutral</x-nx::badge>
            <x-nx::badge tone="accent">accent</x-nx::badge>
            <x-nx::badge tone="success">success</x-nx::badge>
            <x-nx::badge tone="warning">warning</x-nx::badge>
            <x-nx::badge tone="danger">danger</x-nx::badge>
            <x-nx::badge tone="info">info</x-nx::badge>
            <x-nx::badge tone="gold">gold</x-nx::badge>
        </div>
        <div class="pg-row" style="gap: .5rem">
            <x-nx::badge variant="solid">neutral</x-nx::badge>
            <x-nx::badge tone="accent" variant="solid">accent</x-nx::badge>
            <x-nx::badge tone="success" variant="solid">success</x-nx::badge>
            <x-nx::badge tone="warning" variant="solid">warning</x-nx::badge>
            <x-nx::badge tone="danger" variant="solid">danger</x-nx::badge>
            <x-nx::badge tone="info" variant="solid">info</x-nx::badge>
            <x-nx::badge tone="gold" variant="solid">gold</x-nx::badge>
        </div>
    </section>
</div>
