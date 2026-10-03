{{--
    The stacked bar's real scenarios: weekly orders by status (stacked, with
    totals), support tickets by team as horizontal 100% bars, and a plan
    comparison grouped side by side with gradient fills.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $week = $fa ? ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنجشنبه', 'جمعه'] : ['Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
    $delivered = [182, 164, 198, 211, 236, 274, 158];
    $shipping = [44, 52, 47, 58, 61, 72, 39];
    $returned = [9, 7, 12, 8, 11, 14, 6];

    $teams = $fa ? ['فروش', 'فنی', 'مالی', 'حساب کاربری', 'سفارش‌ها'] : ['Sales', 'Technical', 'Billing', 'Accounts', 'Orders'];
@endphp

<div class="pg-grid">
    <section class="pg-box" style="grid-column: 1 / -1; gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Orders by status this week', 'سفارش‌های این هفته به تفکیک وضعیت') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Each day is one column: delivered at the base, then in transit, then returned. Only the top segment is rounded, a 1px surface seam separates the rest, and totals sits each day’s sum on its stack. Columns grow in on a spring, one after another; switch a status off and the stacks retween.', 'هر روز یک ستون است: تحویل‌شده در پایه، بعد در راه و بعد مرجوعی. فقط قطعهٔ بالا گرد است، یک درز یک‌پیکسلی هم‌رنگ سطح بقیه را جدا می‌کند و totals جمع هر روز را روی ستونش می‌گذارد. ستون‌ها یکی‌یکی با فنر بالا می‌آیند؛ وضعیتی را خاموش کنید تا ستون‌ها دوباره چیده شوند.') }}
            </p>
        </div>
        <x-nx::stacked-bar
            :title="$say('Orders', 'سفارش‌ها')"
            :subtitle="$say('This week · all warehouses', 'این هفته · همهٔ انبارها')"
            totals :labels="$week" :series="[
                ['name' => $say('Delivered', 'تحویل‌شده'), 'values' => $delivered],
                ['name' => $say('In transit', 'در راه'), 'values' => $shipping],
                ['name' => $say('Returned', 'مرجوعی'), 'values' => $returned],
            ]" />
        <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
            {{ NabuXUI::formatNumber(array_sum($delivered) + array_sum($shipping) + array_sum($returned)) }} {{ $say('orders ·', 'سفارش ·') }}
            {{ NabuXUI::formatNumber(array_sum($returned)) }} {{ $say('returned', 'مرجوعی') }}
        </p>
    </section>

    <section class="pg-box" style="gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Ticket outcome by team, 100%', 'نتیجهٔ تیکت‌ها به تفکیک تیم، ۱۰۰٪') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Horizontal percent bars compare teams of very different sizes. On a Persian page the bars grow from the right and the names sit in the right gutter; arrow keys (up/down too) walk the teams.', 'میله‌های افقی درصدی تیم‌هایی با اندازه‌های خیلی متفاوت را مقایسه می‌کنند. در صفحهٔ فارسی میله‌ها از راست رشد می‌کنند و نام‌ها در حاشیهٔ راست می‌نشینند؛ کلیدهای جهت‌نما (بالا/پایین هم) تیم‌ها را می‌پیمایند.') }}
            </p>
        </div>
        <x-nx::stacked-bar
            :title="$say('Ticket outcomes', 'نتیجهٔ تیکت‌ها')"
            orientation="horizontal" mode="percent" totals
            :labels="$teams" :series="[
                ['name' => $say('Solved', 'حل‌شده'), 'values' => [412, 286, 158, 121, 344]],
                ['name' => $say('Waiting', 'در انتظار'), 'values' => [61, 94, 22, 18, 47]],
                ['name' => $say('Escalated', 'ارجاع‌شده'), 'values' => [12, 48, 9, 5, 21]],
            ]" />
    </section>

    <section class="pg-box" style="gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Signups by plan, grouped', 'ثبت‌نام به تفکیک پلن، گروهی') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('mode="grouped" sets the plans side by side within each quarter, every bar rounded at its data end. Gradient fills deepen toward the value.', 'با mode="grouped" پلن‌ها در هر فصل کنار هم می‌نشینند و هر میله در سرِ داده‌اش گرد است. پرشدگی گرادیان به سمت مقدار پررنگ‌تر می‌شود.') }}
            </p>
        </div>
        <x-nx::stacked-bar
            :title="$say('New subscriptions', 'اشتراک‌های تازه')"
            :subtitle="$say('By quarter', 'به تفکیک فصل')"
            mode="grouped" fill="gradient" height="240"
            :labels="$fa ? ['بهار', 'تابستان', 'پاییز', 'زمستان'] : ['Q1', 'Q2', 'Q3', 'Q4']" :series="[
                ['name' => $say('Starter', 'پایه'), 'values' => [320, 410, 380, 460]],
                ['name' => $say('Growth', 'رشد'), 'values' => [140, 190, 230, 260]],
                ['name' => $say('Business', 'سازمانی'), 'values' => [36, 48, 61, 72]],
            ]" />
    </section>
</div>
