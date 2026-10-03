{{--
    Resizable panels' real scenarios: a code editor (sidebar | editor over a
    collapsible terminal, nested), remembered in localStorage, and a mail
    client split top/bottom.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $files = ['app/', 'Http/Controllers/InvoiceController.php', 'Models/Invoice.php', 'resources/views/', 'invoices/show.blade.php', 'routes/web.php'];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Code editor layout', 'چیدمان یک ویرایشگر کد') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Drag the handles, or tab to one and use the arrows (Shift for big steps), Home and End. Double-click or press Enter to fold the sidebar or the terminal. Sizes survive a reload.', 'دستگیره‌ها را بکشید، یا با Tab رویشان بروید و از فلش‌ها (با Shift قدم بزرگ)، Home و End استفاده کنید. دوبار کلیک یا Enter نوار کناری یا ترمینال را جمع می‌کند. اندازه‌ها بعد از بارگذاری دوباره می‌مانند.') }}
        </p>
    </div>
    <div style="block-size: 26rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); overflow: hidden; background: var(--nx-surface)">
        <x-nx::resizable-panels storage-key="nx-demo-editor">
            <x-nx::resizable-pane :size="24" :min="14" :max="40" collapsible style="background: var(--nx-surface-2)">
                <nav aria-label="{{ $say('Files', 'فایل‌ها') }}" style="padding: .75rem; font-size: var(--nx-text-sm)">
                    <p style="margin: 0 0 .5rem; font-weight: 700; color: var(--nx-text-muted)">{{ $say('Explorer', 'فایل‌ها') }}</p>
                    <ul role="list" dir="ltr" style="display: grid; gap: .25rem; margin: 0; padding: 0; list-style: none; font-family: var(--nx-font-mono); white-space: nowrap">
                        @foreach ($files as $file)
                            <li style="padding-inline-start: {{ str_ends_with($file, '/') ? 0 : 1 }}rem; color: {{ str_contains($file, 'show') ? 'var(--nx-accent-text)' : 'inherit' }}">{{ $file }}</li>
                        @endforeach
                    </ul>
                </nav>
            </x-nx::resizable-pane>
            <x-nx::resizable-handle :label="$say('Resize sidebar', 'تغییر اندازهٔ نوار کناری')" />
            <x-nx::resizable-pane>
                <x-nx::resizable-panels orientation="vertical">
                    <x-nx::resizable-pane :size="68" :min="30">
<pre dir="ltr" style="margin: 0; padding: 1rem; font: 500 var(--nx-text-sm) / 1.7 var(--nx-font-mono); color: var(--nx-text-muted)"><code>&lt;x-nx::card&gt;
    &lt;h1&gt;{{ '{' }}{{ '{' }} $invoice-&gt;number }}&lt;/h1&gt;
    &lt;x-nx::invoice :invoice="$invoice" /&gt;
&lt;/x-nx::card&gt;</code></pre>
                    </x-nx::resizable-pane>
                    <x-nx::resizable-handle :label="$say('Resize terminal', 'تغییر اندازهٔ ترمینال')" />
                    <x-nx::resizable-pane :min="12" collapsible style="background: var(--nx-surface-2)">
<pre dir="ltr" style="margin: 0; padding: .75rem 1rem; font: 500 var(--nx-text-xs) / 1.7 var(--nx-font-mono)"><code>$ php artisan test --filter=Invoice
  PASS  Tests\Feature\InvoiceTest
  ✓ it renders an invoice      0.21s
  Tests:  1 passed</code></pre>
                    </x-nx::resizable-pane>
                </x-nx::resizable-panels>
            </x-nx::resizable-pane>
        </x-nx::resizable-panels>
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Mail client, inbox over reading pane', 'کلاینت ایمیل، صندوق بالای متن') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('A vertical group: the list cannot shrink below 25%, the message never below 35%.', 'یک گروه عمودی: فهرست از ۲۵٪ و متن پیام از ۳۵٪ کوچک‌تر نمی‌شود.') }}
        </p>
    </div>
    <div style="block-size: 22rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); overflow: hidden; background: var(--nx-surface)">
        <x-nx::resizable-panels orientation="vertical">
            <x-nx::resizable-pane :size="40" :min="25">
                <ul role="list" style="display: grid; margin: 0; padding: 0; list-style: none">
                    @foreach ([
                        [$say('Sara Ahmadi', 'سارا احمدی'), $say('Contract for the spring campaign', 'قرارداد کمپین بهار')],
                        [$say('Billing', 'صورت‌حساب'), $say('Your invoice for September', 'فاکتور شهریور شما')],
                        [$say('Ali Rezaei', 'علی رضایی'), $say('Re: launch checklist', 'پاسخ: چک‌لیست انتشار')],
                    ] as [$from, $subject])
                        <li style="display: grid; gap: .125rem; padding: .75rem 1rem; border-block-end: 1px solid var(--nx-border)">
                            <strong style="font-size: var(--nx-text-sm)">{{ $from }}</strong>
                            <span style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $subject }}</span>
                        </li>
                    @endforeach
                </ul>
            </x-nx::resizable-pane>
            <x-nx::resizable-handle :label="$say('Resize reading pane', 'تغییر اندازهٔ متن پیام')" />
            <x-nx::resizable-pane :min="35">
                <article style="display: grid; gap: .5rem; padding: 1rem 1.25rem">
                    <h4 style="margin: 0">{{ $say('Contract for the spring campaign', 'قرارداد کمپین بهار') }}</h4>
                    <p style="margin: 0; color: var(--nx-text-muted)">{{ $say('Hi! The signed contract is attached. Could you confirm the delivery dates for the three videos by Thursday?', 'سلام! قرارداد امضاشده پیوست است. می‌شود تاریخ تحویل سه ویدیو را تا پنجشنبه تأیید کنید؟') }}</p>
                </article>
            </x-nx::resizable-pane>
        </x-nx::resizable-panels>
    </div>
</section>
