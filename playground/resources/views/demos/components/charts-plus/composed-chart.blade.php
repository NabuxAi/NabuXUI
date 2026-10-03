{{--
    The composed chart's real scenarios: monthly revenue (bars, million tomans)
    against the checkout conversion rate (line, percent) on two axes, and a
    support desk's ticket volume against median first-response time.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $months = $fa
        ? ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان']
        : ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov'];
    $revenue = [412, 456, 498, 471, 532, 588, 610, 664];
    $conversion = [0.021, 0.024, 0.026, 0.023, 0.029, 0.031, 0.030, 0.034];
@endphp

<div class="pg-grid">
    <section class="pg-box" style="grid-column: 1 / -1; gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Revenue against conversion', 'درآمد در برابر نرخ تبدیل') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Two quantities in different units share one plot: the bars read on the start axis (million tomans), the line on the end axis (percent, via line-format). On a Persian page the bars’ axis moves to the right and the months run right to left. One frosted tooltip reads both; the markers name conversion’s best and worst month.', 'دو کمیت با یکای متفاوت یک نمودار را شریک می‌شوند: میله‌ها روی محور آغاز (میلیون تومان) و خط روی محور پایان (درصد، با line-format) خوانده می‌شوند. در صفحهٔ فارسی محور میله‌ها به راست می‌رود و ماه‌ها از راست به چپ می‌آیند. یک نوار ابزار شیشه‌ای هر دو را می‌خواند؛ برچسب‌ها بهترین و بدترین ماهِ نرخ تبدیل را می‌گویند.') }}
            </p>
        </div>
        <x-nx::composed-chart
            :title="$say('Revenue & checkout conversion', 'درآمد و نرخ تبدیل پرداخت')"
            :subtitle="$say('Online store · 8 months', 'فروشگاه آنلاین · ۸ ماه')"
            :labels="$months"
            :bars="[['name' => $say('Revenue', 'درآمد'), 'values' => $revenue]]"
            :lines="[['name' => $say('Conversion', 'نرخ تبدیل'), 'values' => $conversion]]"
            :bar-axis="$say('M toman', 'میلیون تومان')"
            :line-axis="$say('Rate', 'نرخ')"
            :line-format="['style' => 'percent', 'maximumFractionDigits' => 1]"
            markers />
    </section>

    <section class="pg-box" style="grid-column: 1 / -1; gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Ticket volume and response time', 'حجم تیکت و زمان پاسخ') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Two bar series (new vs reopened tickets) sit side by side on the start axis while the median first response, in minutes, rides the end axis — the busy Monday is where the line peaks.', 'دو سری میله (تیکت تازه و بازگشایی‌شده) روی محور آغاز کنار هم‌اند و میانهٔ اولین پاسخ، به دقیقه، روی محور پایان — دوشنبهٔ شلوغ همان‌جاست که خط اوج می‌گیرد.') }}
            </p>
        </div>
        <x-nx::composed-chart
            :title="$say('Support desk', 'میز پشتیبانی')"
            height="260"
            :labels="$fa ? ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'] : ['Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri']"
            :bars="[
                ['name' => $say('New', 'تازه'), 'values' => [132, 148, 196, 171, 158, 120, 84]],
                ['name' => $say('Reopened', 'بازگشایی‌شده'), 'values' => [18, 22, 31, 26, 19, 15, 9]],
            ]"
            :lines="[['name' => $say('First response (min)', 'اولین پاسخ (دقیقه)'), 'values' => [6.2, 7.1, 11.4, 8.9, 7.6, 5.8, 4.9]]]"
            :bar-axis="$say('Tickets', 'تیکت')"
            :line-axis="$say('Minutes', 'دقیقه')"
            curve="linear" />
    </section>
</div>
