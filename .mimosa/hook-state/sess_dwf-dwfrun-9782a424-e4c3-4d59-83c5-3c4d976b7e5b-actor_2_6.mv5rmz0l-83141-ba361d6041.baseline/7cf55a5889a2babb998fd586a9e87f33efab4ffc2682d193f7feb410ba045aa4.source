{{--
    The forecast line's real scenarios: monthly sales with a six-month projection
    and its confidence band (marching dashes on), and a storage-capacity runway
    where the forecast has no band and the today pill is renamed.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $months = $fa
        ? ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند']
        : ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar'];
    $sales = [318, 342, 336, 371, 398, 384];
    $projection = [412, 431, 455, 472, 498, 521];
    $low = [396, 404, 417, 421, 432, 441];
    $high = [428, 458, 493, 523, 564, 601];
@endphp

<div class="pg-grid">
    <section class="pg-box" style="grid-column: 1 / -1; gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Sales, actual and projected', 'فروش، واقعی و پیش‌بینی') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Six months of actuals, then six projected: the forecast continues from the last actual point as a dashed line (animated marches the dashes, still under reduced motion), and the band widens as uncertainty grows. The future is tinted, the today line pings on the latest actual, and the tooltip over the future reads the projection and its low–high range.', 'شش ماه واقعی و شش ماه پیش‌بینی: پیش‌بینی از آخرین نقطهٔ واقعی با خط‌چین ادامه می‌یابد (animated خط‌چین را به حرکت درمی‌آورد و زیر کاهش حرکت می‌ایستد) و نوار اطمینان با رشد عدم‌قطعیت پهن‌تر می‌شود. آینده سایه خورده، خط امروز روی آخرین مقدار واقعی می‌تپد و نوار ابزار در آینده پیش‌بینی و بازهٔ پایین–بالایش را می‌خواند.') }}
            </p>
        </div>
        <x-nx::forecast-line
            :title="$say('Monthly sales', 'فروش ماهانه')"
            :subtitle="$say('Million tomans · 80% confidence band', 'میلیون تومان · نوار اطمینان ۸۰٪')"
            :labels="$months" :actual="$sales" :forecast="$projection" :lower="$low" :upper="$high" animated />
    </section>

    <section class="pg-box" style="grid-column: 1 / -1; gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Storage runway', 'ظرفیت باقی‌ماندهٔ ذخیره‌سازی') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('No band this time, a linear curve and a renamed pill (today-label) — the dashed line shows the disk filling up week by week.', 'این بار بدون نوار اطمینان، با منحنی خطی و برچسبِ تغییرنام‌یافته (today-label) — خط‌چین پرشدن دیسک را هفته به هفته نشان می‌دهد.') }}
            </p>
        </div>
        <x-nx::forecast-line
            :title="$say('Used storage', 'فضای مصرف‌شده')"
            :subtitle="$say('TB · cluster eu-1', 'ترابایت · کلاستر eu-1')"
            height="230" curve="linear" :markers="false"
            :today-label="$say('This week', 'این هفته')"
            :labels="$fa ? ['هفتهٔ ۱', 'هفتهٔ ۲', 'هفتهٔ ۳', 'هفتهٔ ۴', 'هفتهٔ ۵', 'هفتهٔ ۶', 'هفتهٔ ۷', 'هفتهٔ ۸', 'هفتهٔ ۹', 'هفتهٔ ۱۰'] : ['W1', 'W2', 'W3', 'W4', 'W5', 'W6', 'W7', 'W8', 'W9', 'W10']"
            :actual="[41.2, 43.0, 44.1, 46.3, 48.0, 49.6]" :forecast="[51.4, 53.1, 54.9, 56.6]" />
    </section>
</div>
