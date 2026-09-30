{{--
    The kanban board's real scenarios: a design team's sprint board — cards
    drag between columns (or move from the three-dot menu, so the keyboard
    works too) and every move reports back through a toast.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $columns = [
        ['id' => 'backlog', 'title' => $say('Backlog', 'انبار'), 'tone' => 'info', 'cards' => [
            ['id' => 'kb1', 'title' => $say('Wire the settings page', 'سیم‌کشی صفحهٔ تنظیمات'), 'meta' => $say('FR-104 · 2 comments', 'FR-104 · ۲ دیدگاه'), 'tone' => 'info', 'assignee' => ['name' => 'نیلوفر احمدی']],
            ['id' => 'kb2', 'title' => $say('Dark-theme audit of invoices', 'بازبینی فاکتورها در تم تیره'), 'meta' => $say('Chore · 1 comment', 'کار جزئی · ۱ دیدگاه'), 'assignee' => ['name' => 'عمر حداد']],
        ]],
        ['id' => 'week', 'title' => $say('This week', 'این هفته'), 'tone' => 'warning', 'cards' => [
            ['id' => 'kb3', 'title' => $say('Kanban block for the design system', 'بلوک کانبان برای سیستم طراحی'), 'meta' => $say('Block · in review', 'بلوک · در بازبینی'), 'tone' => 'warning', 'assignee' => ['name' => 'Kenji Sato']],
            ['id' => 'kb4', 'title' => $say('Persian digits in the analytics card', 'ارقام فارسی در کارت آنالیتیکس'), 'meta' => $say('FR-231', 'FR-231'), 'tone' => 'danger', 'assignee' => ['name' => 'María López']],
        ]],
        ['id' => 'done', 'title' => $say('Shipped this month', 'منتشرشده در این ماه'), 'tone' => 'success', 'cards' => [
            ['id' => 'kb5', 'title' => $say('Print stylesheet for the invoice', 'شیوه‌نامهٔ چاپ فاکتور'), 'meta' => $say('v2.4 · shipped', 'نسخهٔ ۲٫۴ · منتشر شد'), 'tone' => 'success', 'assignee' => ['name' => 'لیلا حداد']],
            ['id' => 'kb6', 'title' => $say('Roving tab stops in the calendar', 'توقف‌های roam در تقویم'), 'meta' => $say('A11y', 'دسترس‌پذیری'), 'tone' => 'success'],
        ]],
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The design team’s sprint', 'اسپرینت تیم طراحی') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Drag a card into another column — it glides on FLIP, the placeholder breathes where it will land and the column counts roll. No keyboard? Every move also lives in the card’s three-dot menu, and each move reports back through a toast (the nx-move event riding the board).', 'کارتی را به ستون دیگر بکشید — با FLIP می‌لغزد، جای‌نگهدار جایی که فرود می‌آید نفس می‌کشد و شمارندهٔ ستون‌ها می‌غلتد. کیبورد دارید؟ هر جابه‌جایی در منوی سه‌نقطهٔ کارت هم هست و هر حرکت با یک توست خبر می‌دهد (رویداد nx-move روی خود برد).') }}
        </p>
    </div>
    <x-nx::kanban
        :columns="$columns"
        :label="$say('Sprint board', 'برد اسپرینت')"
        height="30rem"
        x-on:nx-move="$wire.ping($event.detail.card + ' → ' + $event.detail.to)"
        x-on:nx-card-action="$event.detail.action !== 'move' && $wire.ping($event.detail.action + ': ' + $event.detail.card)" />
    <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
        {{ $say('Without move-action the board owns its state client-side and tells you through events; pass move-action="moveCard" and the columns you hand in become the single truth.', 'بدون move-action برد state خودش را سمت مرورگر نگه می‌دارد و با رویدادها خبر می‌دهد؛ move-action="moveCard" بدهید تا همان ستون‌هایی که دادید، مرجع حقیقت شوند.') }}
    </p>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The support intake lane', 'خط ورودی پشتیبانی') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('quick-add grows a card in place — type and press Enter. The three-dot menu also carries each card’s own actions (open, duplicate, delete).', 'افزودن سریع، کارت را در جا می‌رویاند — بنویسید و Enter بزنید. منوی سه‌نقطه کنش‌های خودِ کارت (باز کردن، تکثیر، حذف) را هم دارد.') }}
        </p>
    </div>
    <x-nx::kanban
        :label="$say('Support intake', 'ورودی پشتیبانی')"
        height="24rem"
        :columns="[
            ['id' => 'new', 'title' => $say('New', 'تازه'), 'tone' => 'info', 'cards' => [
                ['id' => 'sv1', 'title' => $say('«The bot answers in the wrong register»', '«ربات با لحن اشتباه جواب می‌دهد»'), 'meta' => $say('WhatsApp · فارسی', 'واتساپ · فارسی'), 'tone' => 'warning', 'actions' => [
                    ['label' => $say('Open', 'باز کردن'), 'icon' => 'edit', 'href' => '#'],
                    ['label' => $say('Duplicate', 'تکثیر'), 'icon' => 'copy'],
                    ['label' => $say('Delete', 'حذف'), 'icon' => 'trash', 'danger' => true],
                ]],
            ]],
            ['id' => 'triaged', 'title' => $say('Triaged', 'سرت‌بندی‌شده'), 'tone' => 'accent', 'cards' => [
                ['id' => 'sv2', 'title' => $say('Refund stuck for order #981', 'بازپرداخت سفارش ۹۸۱ گیر کرده'), 'meta' => $say('Escalated · billing', 'تشدیدشده · مالی'), 'tone' => 'danger', 'assignee' => ['name' => 'آمارا اوکافور'], 'actions' => [
                    ['label' => $say('Reply', 'پاسخ'), 'icon' => 'message'],
                ]],
            ]],
            ['id' => 'waiting', 'title' => $say('Waiting on the customer', 'در انتظار مشتری'), 'tone' => 'neutral', 'cards' => []],
        ]"
        x-on:nx-card-action="$wire.ping($event.detail.action + ': ' + $event.detail.card)" />
</section>
