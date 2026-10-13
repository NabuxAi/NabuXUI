{{--
    Goo stack's real scenarios: a notification tray that melts at rest and opens
    on hover (with new notifications budding off the top), and an upload queue
    pinned open.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $incoming = [
        ['label' => $say('Ava invited you to “Q3 roadmap”', 'سارا شما را به «نقشهٔ راه فصل سوم» دعوت کرد'), 'icon' => 'users'],
        ['label' => $say('Deploy #318 finished', 'استقرار ۳۱۸ تمام شد'), 'icon' => 'check-circle'],
        ['label' => $say('Weekly report is ready', 'گزارش هفتگی آماده است'), 'icon' => 'chart'],
        ['label' => $say('Reminder: design review at 15:00', 'یادآوری: بازبینی طراحی ساعت ۱۵'), 'icon' => 'bell'],
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Notification tray', 'سینی اعلان‌ها') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('At rest the pills melt into one shape; hover or tab into it and they split apart. New notifications bud off the top, dismissed ones pinch away.', 'در حالت آرام قرص‌ها در یک شکل ذوب می‌شوند؛ با هاور یا Tab از هم جدا می‌شوند. اعلان تازه از بالا جوانه می‌زند و اعلانِ بسته‌شده جدا می‌شود و می‌رود.') }}
        </p>
    </div>
    <div x-data="{ i: 0, next() { const all = @js($incoming); this.$dispatch('nx-goo-add', all[this.i++ % all.length]) } }" style="display: grid; gap: 1.25rem; justify-items: center">
        <x-nx::goo-stack :label="$say('Notifications', 'اعلان‌ها')" :dismiss-label="$say('Dismiss', 'بستن')" :icons="['users', 'chart', 'bell']" :items="[
            ['id' => 'n1', 'label' => $say('Invoice #1042 was paid', 'فاکتور ۱۰۴۲ پرداخت شد'), 'icon' => 'check-circle'],
            ['id' => 'n2', 'label' => $say('Sam commented on Pricing page', 'علی روی صفحهٔ قیمت‌ها نظر داد'), 'icon' => 'message'],
            ['id' => 'n3', 'label' => $say('3 new sign-ups today', '۳ ثبت‌نام تازه امروز'), 'icon' => 'user'],
        ]" />
        <x-nx::button variant="secondary" icon="plus" x-on:click="next()">{{ $say('Simulate a notification', 'شبیه‌سازی یک اعلان') }}</x-nx::button>
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Upload queue, pinned open', 'صف آپلود، همیشه باز') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('state="split" keeps the pills apart; dismiss one and watch its neighbours flow together through the gap.', 'state="split" قرص‌ها را جدا نگه می‌دارد؛ یکی را ببندید و ببینید همسایه‌هایش چطور از میان شکاف به هم می‌رسند.') }}
        </p>
    </div>
    <x-nx::goo-stack state="split" :strength="10" :label="$say('Uploads', 'آپلودها')" :dismiss-label="$say('Cancel upload', 'لغو آپلود')" :items="[
        ['id' => 'u1', 'label' => 'hero-noruz.jpg — 2.4 MB', 'icon' => 'image'],
        ['id' => 'u2', 'label' => $say('contract-signed.pdf — 380 KB', 'قرارداد-امضاشده.pdf — ۳۸۰ کیلوبایت'), 'icon' => 'file'],
        ['id' => 'u3', 'label' => 'brand-kit.zip — 18 MB', 'icon' => 'folder'],
    ]" />
</section>
