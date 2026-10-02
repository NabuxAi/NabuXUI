{{--
    The mail client's real scenarios: a support inbox riding the built-in six
    folders (replies and stars report back through the nx-* events), a shop's
    correspondence desk on its own folders with reply-action reaching a Livewire
    method, and the message being read entangled with the server itself.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    // ── 1 — the support inbox, on the built-in folders ────────────────────────
    $supportMail = [
        ['id' => 'm1', 'from' => ['name' => 'سارا رضایی', 'email' => 'sara@nabu.ai'],
            'subject' => $say('Demo walkthrough at 10 tomorrow?', 'جلسهٔ دمو ساعت ۱۰ فردا؟'),
            'body' => $say("Hi;\nDoes 10 o'clock still work for the walkthrough?\nI'll bring the Persian-digits report with me.", "سلام؛\nجلسهٔ دمو همان ساعت ۱۰ فردا می‌ماند؟\nگزارش ارقام فارسی را هم همراهم می‌آورم."),
            'time' => now()->subMinutes(18), 'unread' => true, 'starred' => true],
        ['id' => 'm2', 'from' => ['name' => 'عمر حداد', 'email' => 'omar@nabu.ai'],
            'subject' => $say('The December invoices are ready for review', 'فاکتورهای دی‌ماه آمادهٔ بازبینی‌اند'),
            'body' => $say("Hello;\nAll 214 invoices of December are exported.\nTwo of them want a second look at their VAT.", "درود;\nهر ۲۱۴ فاکتور دی‌ماه برون‌بری شد.\nدو تایشان مالیات بر ارزش افزوده را بازبینی می‌خواهد."),
            'time' => now()->subMinutes(52), 'unread' => true],
        ['id' => 'm3', 'from' => ['name' => 'GitHub', 'email' => 'notifications@github.com'],
            'subject' => '[nabuxui] PR #421 merged',
            'body' => "kenji-sato merged 3 commits into main.\n2 checks passed.",
            'time' => now()->subHours(3), 'folder' => 'archive'],
        ['id' => 'm4', 'from' => ['name' => 'مریم رضایی'], 'to' => 'سارا رضایی',
            'subject' => $say('Contract signed', 'قرارداد امضا شد'),
            'body' => $say('Sent from the legal desk — the countersigned copy is yours.', 'از میز حقوقی فرستاده شد — نسخهٔ امضاشده از آنِ شماست.'),
            'time' => now()->subDay(), 'folder' => 'sent'],
        ['id' => 'm5', 'from' => ['name' => 'بانک ملت', 'email' => 'no-reply@bmi.ir'],
            'subject' => $say('One-time password', 'رمز یک‌بارمصرف'),
            'body' => $say('Your code is 4821 — never share it.', 'رمز شما ۴۸۲۱ است — آن را با کسی در میان نگذارید.'),
            'time' => now()->subHours(8), 'folder' => 'trash'],
    ];
    $replyPing = $say('Reply shipped', 'پاسخ فرستاده شد');
    $starPing = $say('Starred', 'ستاره‌دار شد');

    // ── 2 — the shop's correspondence desk, on custom folders ─────────────────
    $deskFolders = [
        ['id' => 'orders', 'label' => $say('Orders', 'سفارش‌ها'), 'icon' => 'file'],
        ['id' => 'billing', 'label' => $say('Billing', 'مالی'), 'icon' => 'chart'],
        ['id' => 'shipping', 'label' => $say('Shipping', 'ارسال'), 'icon' => 'zap'],
        ['id' => 'legal', 'label' => $say('Contracts', 'قراردادها'), 'icon' => 'shield'],
    ];
    $deskMail = [
        ['id' => 's1', 'from' => ['name' => 'لیلا حداد', 'email' => 'leila@shop.example'],
            'subject' => $say('When does order 1248 arrive?', 'سفارش ۱۲۴۸ کی می‌رسد؟'),
            'body' => $say("Hi;\nThe order was placed on Tuesday — any news yet?\nThe customer is asking again.", "سلام؛\nسفارش سه‌شنبه ثبت شد — هنوز خبری نیست؟\nمشتری برای بار دوم پیگیر است."),
            'time' => now()->subMinutes(26), 'unread' => true, 'folder' => 'orders'],
        ['id' => 's2', 'from' => ['name' => $say('Payment gateway', 'درگاه پرداخت'), 'email' => 'billing@nabu.shop'],
            'subject' => $say('Transaction 9812 settled', 'تراکنش ۹۸۱۲ تسویه شد'),
            'body' => $say('The amount reaches your account within two working days.', 'مبلغ تا دو روز کاری به حساب شما می‌رسد.'),
            'time' => now()->subHours(2), 'folder' => 'billing', 'starred' => true],
        ['id' => 's3', 'from' => ['name' => $say('Central warehouse', 'انبار مرکزی')],
            'subject' => $say('Parcel 1248 handed to the courier', 'مرسولهٔ ۱۲۴۸ تحویل پیک شد'),
            'body' => $say('It left the Tabriz hub at 6:40.', 'ساعت ۶:۴۰ از مرکز تبریز به راه افتاد.'),
            'time' => now()->subHours(5), 'folder' => 'shipping'],
        ['id' => 's4', 'from' => ['name' => 'Kenji Sato', 'email' => 'kenji@example.com'],
            'subject' => $say('Reseller agreement, version 2', 'قرارداد نمایندگی، نسخهٔ ۲'),
            'body' => $say('Two clauses changed; the redlines are attached.', 'دو بند تغییر کرده؛ اصلاحات پیوست است.'),
            'time' => now()->subDays(2), 'folder' => 'legal'],
    ];

    // ── 3 — the message being read, bound back to the server ──────────────────
    $handoverMail = [
        ['id' => 'r1', 'from' => ['name' => 'آمارا اوکافور'],
            'subject' => $say('Evening-shift handover notes', 'یادداشت تحویل شیفت عصر'),
            'body' => $say("Three conversations stay open;\n9812 is still waiting for billing.", "سه گفت‌وگو باز مانده است؛\n۹۸۱۲ هنوز منتظر تأیید مالی است."),
            'time' => now()->subMinutes(40), 'unread' => true, 'starred' => true],
        ['id' => 'r2', 'from' => ['name' => 'نیلوفر احمدی'],
            'subject' => $say('Feedback on the Arabic tone', 'بازخورد لحن عربی'),
            'body' => $say('The formal register reads better now; keep it.', 'لحن رسمی حالا بهتر خوانده می‌شود؛ همین بماند.'),
            'time' => now()->subHours(30), 'starred' => true, 'folder' => 'archive'],
        ['id' => 'r3', 'from' => ['name' => 'ایجنت نابو'],
            'subject' => $say('Weekly report', 'گزارش هفتگی'),
            'body' => $say('412 conversations, 96% answered within the hour.', '۴۱۲ گفت‌وگو، ۹۶٪ در همان ساعت پاسخ گرفتند.'),
            'time' => now()->subDays(1)],
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The support inbox', 'صندوق ورودی پشتیبانی') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The six built-in folders with unread tallies in the page’s digits; opening a message folds its unread bar away, the star works from both the list and the reading pane, and the arrow keys (Home and End too) walk the rows. Send a reply — Enter sends, Shift+Enter breaks a line — and the nx-reply event reports back through a toast.', 'شش پوشهٔ داخلی با شمارندهٔ خوانده‌نشده به ارقام همان زبان؛ باز کردن پیام نوار «خوانده‌نشده»اش را جمع می‌کند، ستاره از هر دو سمت فهرست و پنجرهٔ خواندن کار می‌کند و فلش‌های کیبورد (و Home/End) میان ردیف‌ها می‌گردند. پاسخ بفرستید — Enter می‌فرستد و Shift+Enter خط می‌شکند — تا رویداد nx-reply با یک توست خبر دهد.') }}
        </p>
    </div>
    <x-nx::email :messages="$supportMail" open="m1" height="34rem"
        :label="$say('Support inbox', 'صندوق ورودی پشتیبانی')"
        x-on:nx-reply="$wire.ping(@js($replyPing))"
        x-on:nx-star="$event.detail.starred && $wire.ping(@js($starPing))" />
    <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
        {{ $say('The “starred” folder is virtual — it gathers the starred of every folder. Without reply-action everything stays in the browser and nx-folder / nx-open / nx-star / nx-reply bubble for you to handle.', 'پوشهٔ «ستاره‌دار» مجازی است — ستاره‌دارهای همهٔ پوشه‌ها را یک‌جا جمع می‌کند. بدون reply-action همه‌چیز سمت مرورگر می‌ماند و رویدادهای nx-folder / nx-open / nx-star / nx-reply حباب می‌کنند تا خودتان بگیریدشان.') }}
    </p>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The shop’s correspondence desk', 'میز مکاتبات فروشگاه') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The built-ins step aside for the shop’s own folders — orders, billing, shipping, contracts — with icons from the same core set. Replies now ride reply-action: the composer clears and $wire.save(text, messageId) is called — this demo’s slow action (0.9s), which toasts the reply text back.', 'پوشه‌های داخلی کنار می‌روند و پوشه‌های خودِ فروشگاه می‌نشینند — سفارش‌ها، مالی، ارسال، قراردادها — با آیکون‌های همان مجموعهٔ هسته. پاسخ‌ها این‌بار با reply-action می‌روند: پاسخ‌نویس پاک می‌شود و $wire.save(text, messageId) صدا می‌شود — همان اکشن کند دمو (۰٫۹ ثانیه) که متن پاسخ را با توست برمی‌گرداند.') }}
        </p>
    </div>
    <x-nx::email :messages="$deskMail" :folders="$deskFolders" folder="orders" height="30rem"
        :label="$say('Correspondence desk', 'میز مکاتبات')"
        reply-action="save" />
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The open message, server-side', 'پیامِ باز، سمت سرور') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The virtual starred folder opens the scene, and the message being read rides wire:model.live back to the server (the root exposes x-modelable="open") — exactly what a server-side unread counter would need.', 'پوشهٔ مجازی «ستاره‌دار» صحنه را باز می‌کند و پیامی که در حال خواندنش هستید با wire:model.live به سرور می‌رود (ریشه از طریق x-modelable="open" خودش را مقید می‌کند) — همان چیزی که یک شمارندهٔ «خوانده‌نشدهٔ» سمت سرور لازم دارد.') }}
        </p>
    </div>
    <x-nx::email :messages="$handoverMail" folder="starred" height="26rem"
        :label="$say('Shift handover', 'تحویل شیفت')"
        :open="$state['openMail'] ?? null"
        wire:model.live="state.openMail" />
    <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
        {{ $say('On the server (wire:model.live):', 'روی سرور (wire:model.live):') }}
        <code>{{ $state['openMail'] ?? 'null' }}</code>
        {{ $say('— it stays null until you open a message.', '— تا پیامی باز نکنید همان null می‌ماند.') }}
    </p>
</section>
