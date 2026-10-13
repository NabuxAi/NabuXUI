{{--
    The to-do list's real scenarios: a support lead's day — three groups, the
    spring tick, drag & drop (or Alt+↑/↓) and toasts riding the events; the
    2.5 release checklist with quick-add off, one tick from data-complete; and
    the shop's Norooz buying list with the bar gone, feeding its empty group
    through the quick-add's select.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $day = [
        ['id' => 'today', 'title' => $say('Today', 'امروز'), 'tone' => 'warning', 'tasks' => [
            ['id' => 'd1', 'title' => $say('Read the night shift’s handover', 'خواندن گزارش تحویل شیفت شب'), 'done' => true],
            ['id' => 'd2', 'title' => $say('Ticket 1248 — the stuck refund', 'تیکت ۱۲۴۸ — بازپرداخت گیرکرده')],
            ['id' => 'd3', 'title' => $say('Call the host about the SSL renewal', 'تماس با هاست دربارهٔ تمدید SSL')],
        ]],
        ['id' => 'week', 'title' => $say('This week', 'این هفته'), 'tone' => 'info', 'tasks' => [
            ['id' => 'w1', 'title' => $say('Review the billing canned replies', 'بازبینی پاسخ‌های آمادهٔ بخش مالی')],
            ['id' => 'w2', 'title' => $say('Update the installation guide', 'به‌روزرسانی راهنمای نصب'), 'done' => true],
        ]],
        ['id' => 'later', 'title' => $say('Later', 'بعداً'), 'tone' => 'accent', 'tasks' => []],
    ];

    $release = [
        ['id' => 'ship', 'title' => $say('The release', 'انتشار'), 'tone' => 'success', 'tasks' => [
            ['id' => 'r1', 'title' => $say('Regression pass on the target browsers', 'تست رگرسیون روی مرورگرهای هدف'), 'done' => true],
            ['id' => 'r2', 'title' => $say('Sweep the staging error logs', 'پالایش لاگ‌های خطای staging'), 'done' => true],
            ['id' => 'r3', 'title' => $say('Write the release notes', 'نوشتن یادداشت انتشار'), 'done' => true],
            ['id' => 'r4', 'title' => $say('Line up the launch post with the content team', 'هماهنگی پست انتشار با تیم محتوا')],
            ['id' => 'r5', 'title' => $say('Tag v2.5 and build the final bundle', 'زدن برچسب v2.5 و ساختن بیلد نهایی')],
        ]],
    ];

    $buying = [
        ['id' => 'now', 'title' => $say('This week', 'همین هفته'), 'tone' => 'warning', 'tasks' => [
            ['id' => 'b1', 'title' => $say('Gift cards — print 500', 'کارت هدیه — چاپ ۵۰۰ نسخه'), 'done' => true],
            ['id' => 'b2', 'title' => $say('Boxes with the pomegranate print', 'جعبه‌های بسته‌بندی با طرح انار')],
            ['id' => 'b3', 'title' => $say('Brand stickers for the parcels', 'استیکر برند برای بسته‌ها')],
        ]],
        ['id' => 'sale', 'title' => $say('Until the Norooz sale', 'تا تخفیف نوروز'), 'tone' => 'info', 'tasks' => []],
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The support lead’s day', 'برنامهٔ روز سرپرست پشتیبانی') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Tick a task — the checkbox is native, so the space bar works and the tick springs; the counter and the bar roll as it lands. Drag a task into another group (the pill breathes where it will drop) or walk it with Alt+↑/↓ straight from the keyboard. «بعداً» starts empty on purpose: it is a drop target, and the quick-add’s select puts the new task in whichever group you name. Both moves report back through a toast — the nx-toggle and nx-move events riding the list.', 'کاری را تیک بزنید — چک‌باکس بومی است، پس کلید Space هم کار می‌کند و تیک فنری می‌خورد؛ شمارنده و نوار پیشرفت با همان می‌غلتند. کاری را به گروه دیگر بکشید (قرصِ فرود همان‌جا نفس می‌کشد) یا با Alt+↑/↓ مستقیم از کیبورد جابه‌جایش کنید. «بعداً» عمداً خالی شروع می‌شود: هم جای فرود است، هم انتخابگرِ افزودن سریع کار تازه را در هر گروهی که نام ببرید می‌نشاند. هر دو حرکت با یک توست خبر می‌دهند — رویدادهای nx-toggle و nx-move روی خود فهرست.') }}
        </p>
    </div>
    <x-nx::todo
        :heading="$say('My day', 'برنامهٔ روزم')"
        :label="$say('The day’s tasks', 'کارهای روز')"
        :groups="$day"
        x-on:nx-toggle="$wire.ping(($event.detail.done ? '{{ $say('Done', 'انجام شد') }}' : '{{ $say('Reopened', 'باز شد') }}') + ': ' + $event.detail.task)"
        x-on:nx-move="$wire.ping($event.detail.task + ' → ' + $event.detail.to)" />
    <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
        {{ $say('Without the wire actions the list owns its state client-side and tells you through events; pass toggle-action="toggleTask" and friends and the groups you hand in become the single truth, re-rendered after every optimistic change.', 'بدون اکشن‌های لایووایر فهرست state خودش را سمت مرورگر نگه می‌دارد و با رویدادها خبر می‌دهد؛ toggle-action="toggleTask" و بقیه را بدهید تا همان گروه‌هایی که دادید مرجع حقیقت شوند و پس از هر تغییر خوش‌بینانه دوباره رندر شوند.') }}
    </p>
</section>

<section class="pg-box" style="gap: 1.25rem; max-inline-size: 40rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The 2.5 release checklist', 'چک‌لیست انتشار نسخهٔ ۲٫۵') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('A checklist that is not supposed to grow mid-flight: quick-add is off. ۳ of ۵ are done — tick the last two and when the bar fills the whole board takes data-complete, a cue beyond the numbers alone. Every tick also announces itself to screen readers from inside the component, so nothing here depends on the toasts.', 'چک‌لیستی که نباید وسط راه بزرگ شود: افزودن سریع خاموش است. ۳ از ۵ انجام شده — دو تای آخر را تیک بزنید؛ نوار که پُر شود کل برد data-complete می‌گیرد، نشانه‌ای فراتر از فقط عددها. هر تیک از درون خود کامپوننت برای صفحه‌خوان‌ها هم اعلام می‌شود، پس چیزی به توست‌ها وابسته نیست.') }}
        </p>
    </div>
    <x-nx::todo
        :heading="$say('Shipping v2.5', 'انتشار نسخهٔ ۲٫۵')"
        :label="$say('Release checklist', 'چک‌لیست انتشار')"
        :quick-add="false"
        :groups="$release" />
</section>

<section class="pg-box" style="gap: 1.25rem; max-inline-size: 40rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The shop’s Norooz buying list', 'خرید نوروزی فروشگاه') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Progress is off — only the heading stays, no counter, no bar; a buying list does not need percentages. The «Until the Norooz sale» group starts empty: pick it in the quick-add’s select, type, press Enter, and the new task lands there — the toast rides the nx-add event. Removing a task ripples the rest of the list up with the same glide.', 'نوار پیشرفت خاموش است — فقط عنوان می‌ماند، نه شمارنده، نه نوار؛ فهرست خرید به درصد نیاز ندارد. گروه «تا تخفیف نوروز» خالی شروع می‌شود: در انتخابگرِ افزودن سریع برگردینش، بنویسید و Enter بزنید تا کار تازه همان‌جا بنشیند — توست سوار رویداد nx-add است. حذف یک کار، بقیهٔ فهرست را با همان لغزش به بالا می‌راند.') }}
        </p>
    </div>
    <x-nx::todo
        :heading="$say('Norooz buying', 'خرید نوروزی')"
        :label="$say('The buying list', 'فهرست خرید')"
        :progress="false"
        :groups="$buying"
        x-on:nx-add="$wire.ping($event.detail.title + ' → ' + $event.detail.group)" />
</section>
