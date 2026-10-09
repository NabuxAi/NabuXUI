{{--
    The bar chart's real scenarios: a week of support conversations as a plain
    data map, monthly revenue split by channel (grouped), and tickets by
    language with the two owners stacked on one another.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    // A week of support conversations, one bar a day (the week starts Saturday).
    $week = $fa ? ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'] : ['Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
    $chats = [132, 98, 121, 144, 167, 89, 74];

    // Revenue by channel, in millions of tomans.
    $months = $fa ? ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور'] : ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'];
    $web = [42, 48, 61, 55, 72, 80];
    $app = [31, 35, 44, 52, 58, 66];

    // This week’s tickets by language: solved by the agent vs handed to a human.
    $langs = $fa ? ['فارسی', 'انگلیسی', 'عربی', 'ترکی', 'اسپانیایی'] : ['Persian', 'English', 'Arabic', 'Turkish', 'Spanish'];
    $solved = [486, 362, 214, 168, 122];
    $handed = [88, 61, 44, 27, 19];
@endphp

<div class="pg-grid">
    <section class="pg-box" style="grid-column: 1 / -1; gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A week of support conversations', 'یک هفته گفت‌وگوی پشتیبانی') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('The single-series shape: a plain label → value map through data, no series plumbing. The bars grow in steps as the chart scrolls into view, the value axis is a nice-ticks ladder in the reader’s digits, and Tab lands on each column — its tooltip opens on focus just as it does on hover.', 'شکل تک‌سری: یک نقشهٔ سادهٔ برچسب ← مقدار از طریق data، بدون هیچ لوله‌کشیِ سری. میله‌ها با رسیدن نمودار به دید پله‌پله بزرگ می‌شوند، محور مقدار نردبانِ تیک تمیز با ارقام زبان خواننده است و Tab روی هر ستون می‌ایستد — نوارش با تمرکز کیبورد هم باز می‌شود، همان‌طور که با هاور.') }}
            </p>
        </div>
        <x-nx::bar-chart
            :title="$say('Conversations per day', 'گفت‌وگو در روز')"
            :subtitle="$say('Last week · all channels', 'هفتهٔ گذشته · همهٔ کانال‌ها')"
            :data="array_combine($week, $chats)" />
        <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
            {{ $say('Week total:', 'جمع هفته:') }}
            {{ NabuXUI::formatNumber(array_sum($chats)) }}
            {{ $say('conversations — the same numbers ship as a visually-hidden table for screen readers.', 'گفت‌وگو — همین عددها برای صفحه‌خوان‌ها به‌صورت جدول پنهان هم می‌روند.') }}
        </p>
    </section>

    <section class="pg-box" style="gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Revenue by channel, side by side', 'درآمد به تفکیک کانال، کنار هم') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Two series through labels + series sit next to each other in the fixed chart-colour order, and the legend names them. The tallest single bar decides the axis — ۸۰ here lifts the ladder to ۱۰۰.', 'دو سری از طریق labels + series با ترتیب ثابت رنگ‌های نمودار کنار هم می‌نشینند و راهنما نامشان را می‌گوید. بلندترین میلهٔ تنها محور را می‌سازد — همین ۸۰ نردبان را تا ۱۰۰ می‌برد.') }}
            </p>
        </div>
        <x-nx::bar-chart
            :title="$say('Monthly revenue', 'درآمد ماهانه')"
            :subtitle="$say('Million tomans', 'میلیون تومان')"
            height="200"
            :labels="$months"
            :series="[
                ['name' => $say('Web', 'وب'), 'values' => $web],
                ['name' => $say('App', 'اپ'), 'values' => $app],
            ]" />
    </section>

    <section class="pg-box" style="gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Tickets by language, stacked', 'تیکت‌ها به تفکیک زبان، روی هم') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('With stacked the series pile on one another and each column’s total drives the axis; the tooltip adds the total row on top of the two owners.', 'با stacked سری‌ها روی هم می‌نشینند و جمع هر ستون محور را می‌سازد؛ نوار ابزار ردیفِ جمع را هم بالای دو سهم می‌آورد.') }}
            </p>
        </div>
        <x-nx::bar-chart
            :title="$say('Tickets by language', 'تیکت‌ها به تفکیک زبان')"
            :subtitle="$say('This week', 'این هفته')"
            height="200"
            stacked
            :labels="$langs"
            :series="[
                ['name' => $say('Solved by the agent', 'حل‌شده توسط ایجنت'), 'values' => $solved],
                ['name' => $say('Handed to a human', 'واگذار به انسان'), 'values' => $handed],
            ]" />
        <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
            {{ NabuXUI::formatNumber(array_sum($solved)) }} {{ $say('agent-solved ·', 'حل‌شده با ایجنت ·') }}
            {{ NabuXUI::formatNumber(array_sum($handed)) }} {{ $say('handed over', 'واگذارشده به انسان') }}
        </p>
    </section>
</div>
