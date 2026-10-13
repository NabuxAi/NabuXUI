{{--
    The stacked area's real scenarios: site traffic by channel over a year
    (absolute, then the same data as each channel's share), and an API
    gateway's requests by region as a stream with step curves and hatched fills.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $months = $fa
        ? ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند']
        : ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar'];
    // Thousands of visits.
    $search = [42, 46, 51, 49, 55, 61, 66, 64, 70, 74, 79, 86];
    $direct = [28, 27, 30, 33, 31, 34, 36, 39, 38, 41, 43, 44];
    $social = [12, 15, 19, 26, 31, 28, 25, 29, 34, 38, 36, 41];
    $email = [8, 9, 9, 10, 12, 11, 13, 14, 13, 15, 17, 18];
    $traffic = [
        ['name' => $say('Search', 'جست‌وجو'), 'values' => $search],
        ['name' => $say('Direct', 'مستقیم'), 'values' => $direct],
        ['name' => $say('Social', 'شبکه‌های اجتماعی'), 'values' => $social],
        ['name' => $say('Email', 'ایمیل'), 'values' => $email],
    ];
    $yearTotal = array_sum($search) + array_sum($direct) + array_sum($social) + array_sum($email);

    // API requests per hour (millions), by region.
    $hours = $fa ? ['۰۰', '۰۳', '۰۶', '۰۹', '۱۲', '۱۵', '۱۸', '۲۱'] : ['00', '03', '06', '09', '12', '15', '18', '21'];
@endphp

<div class="pg-grid">
    <section class="pg-box" style="grid-column: 1 / -1; gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Traffic by channel, piled up', 'ترافیک به تفکیک کانال، روی هم') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Four channels stacked from zero, so the top edge is the whole site. Switch a channel off in the legend — the stack re-piles with a tween and the axis rescales under it. The ping sits on the latest value; the markers name the busiest and the quietest month.', 'چهار کانال از صفر روی هم چیده شده‌اند، پس لبهٔ بالا کل سایت است. یک کانال را در راهنما خاموش کنید — انباشته با انیمیشن دوباره چیده می‌شود و محور زیرش مقیاس تازه می‌گیرد. نقطهٔ تپنده روی آخرین مقدار است و برچسب‌ها پرترافیک‌ترین و کم‌ترافیک‌ترین ماه را می‌گویند.') }}
            </p>
        </div>
        <x-nx::stacked-area
            :title="$say('Visits by channel', 'بازدید به تفکیک کانال')"
            :subtitle="$say('Thousands · last 12 months', 'هزار بازدید · ۱۲ ماه گذشته')"
            :labels="$months" :series="$traffic" markers />
        <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
            {{ $say('Year total:', 'جمع سال:') }} {{ NabuXUI::formatNumber($yearTotal) }} {{ $say('thousand visits', 'هزار بازدید') }}
        </p>
    </section>

    <section class="pg-box" style="gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The same year as shares', 'همان سال، به‌صورت سهم') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('mode="percent" normalises every month to 100%, so the mix reads at a glance: social keeps eating into direct. The tooltip shows each share and the raw number behind it. Duotone fills split each layer into two tones of its own colour.', 'با mode="percent" هر ماه به ۱۰۰٪ می‌رسد و ترکیب در یک نگاه خوانده می‌شود: شبکه‌های اجتماعی کم‌کم از سهم مستقیم می‌خورند. نوار ابزار سهم هر کانال و عدد خامِ پشتش را نشان می‌دهد. پرشدگی دوتُن هر لایه را به دو تُنِ رنگ خودش می‌شکند.') }}
            </p>
        </div>
        <x-nx::stacked-area
            :title="$say('Channel mix', 'ترکیب کانال‌ها')"
            :subtitle="$say('Share of monthly visits', 'سهم از بازدید ماهانه')"
            height="240" mode="percent" fill="duotone" :ping="false"
            :labels="$months" :series="$traffic" />
    </section>

    <section class="pg-box" style="gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Gateway load as a stream', 'بار درگاه API به‌صورت جریان') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('mode="expanded" centres the pile on zero — a streamgraph whose silhouette breathes with the day. Step curves show the hourly buckets honestly, and hatched fills (an SVG pattern per series) keep the layers apart in print and in high contrast. Europe starts switched off.', 'با mode="expanded" انباشته دور صفر متمرکز می‌شود — نمودار جریانی که شبحش با روز نفس می‌کشد. منحنی پله‌ای سطل‌های ساعتی را صادقانه نشان می‌دهد و هاشور (یک الگوی SVG برای هر سری) لایه‌ها را در چاپ و کنتراست بالا جدا نگه می‌دارد. اروپا خاموش شروع می‌شود.') }}
            </p>
        </div>
        <x-nx::stacked-area
            :title="$say('Requests per hour', 'درخواست در ساعت')"
            :subtitle="$say('Millions · by region', 'میلیون · به تفکیک منطقه')"
            height="240" mode="expanded" curve="step" fill="hatched"
            :hidden-series="[$say('Europe', 'اروپا')]"
            :labels="$hours" :series="[
                ['name' => $say('Middle East', 'خاورمیانه'), 'values' => [3.2, 2.1, 2.8, 6.4, 8.1, 7.6, 9.2, 6.8]],
                ['name' => $say('Europe', 'اروپا'), 'values' => [2.4, 1.6, 1.8, 3.9, 5.6, 6.1, 5.2, 3.8]],
                ['name' => $say('Asia', 'آسیا'), 'values' => [4.1, 4.8, 5.6, 5.1, 3.4, 2.6, 2.9, 3.6]],
            ]" />
    </section>
</div>
