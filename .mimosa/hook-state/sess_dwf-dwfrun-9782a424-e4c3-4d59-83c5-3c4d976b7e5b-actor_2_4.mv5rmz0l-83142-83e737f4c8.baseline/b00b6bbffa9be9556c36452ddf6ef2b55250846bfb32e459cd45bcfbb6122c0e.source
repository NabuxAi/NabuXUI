{{--
    The analytics card's real scenarios: site visitors with three switchable
    periods, then a download counter where a falling delta would be bad.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $wave = fn (int $i, float $base, float $swing, float $noise = 0.35) => max(0, round($base + $swing * sin($i / 2.7) + $swing * $noise * sin($i * 12.9898) * cos($i * 4.1414)));

    $days = $fa ? ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'] : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

    $periods = [
        ['id' => '7d', 'label' => $fa ? '۷روز' : '7d', 'value' => 18420, 'delta' => 12.5, 'caption' => $say('vs previous 7 days', 'نسبت به ۷ روز پیش'), 'labels' => $days, 'values' => array_map(fn ($i) => $wave($i, 2500, 700), range(0, 6))],
        ['id' => '30d', 'label' => $fa ? '۳۰روز' : '30d', 'value' => 76100, 'delta' => 4.2, 'caption' => $say('vs previous 30 days', 'نسبت به ۳۰ روز پیش'), 'labels' => array_map(fn ($i) => $say('Day', 'روز').' '.($i + 1), range(0, 29)), 'values' => array_map(fn ($i) => $wave($i, 2500, 900), range(0, 29))],
        ['id' => '90d', 'label' => $fa ? '۹۰روز' : '90d', 'value' => 214300, 'delta' => -2.1, 'caption' => $say('vs previous 90 days', 'نسبت به ۹۰ روز پیش'), 'labels' => array_map(fn ($i) => $say('Week', 'هفته').' '.($i + 1), range(0, 12)), 'values' => array_map(fn ($i) => $wave($i + 4, 16000, 3500), range(0, 12))],
    ];

    $downloads = [
        ['id' => 'week', 'label' => $say('This week', 'این هفته'), 'value' => 5310, 'delta' => 8.8, 'caption' => $say('vs last week', 'نسبت به هفتهٔ پیش'), 'labels' => $days, 'values' => array_map(fn ($i) => $wave($i, 740, 220), range(0, 6))],
        ['id' => 'month', 'label' => $say('This month', 'این ماه'), 'value' => 21480, 'delta' => -3.4, 'caption' => $say('vs last month', 'نسبت به ماه پیش'), 'labels' => array_map(fn ($i) => $say('Week', 'هفته').' '.($i + 1), range(0, 4)), 'values' => array_map(fn ($i) => $wave($i + 2, 5300, 900), range(0, 4))],
    ];
@endphp

<div class="pg-grid">
    <section class="pg-box" style="gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Visitors, on the marketing wall', 'بازدیدها، روی دیوار مارکتینگ') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Switch the period: the thumb slides to it, the headline rolls and the bars grow or shrink one after another. The 90-day delta is negative — and honestly shown.', 'دوره را عوض کنید: thumb به آن می‌لغزد، عدد تیتر می‌غلتد و میله‌ها یکی‌یکی بزرگ یا کوچک می‌شوند. دلتای ۹۰ روزه منفی است — و صادقانه نشان داده می‌شود.') }}
            </p>
        </div>
        <x-nx::analytics-card title="{{ $say('Visitors', 'بازدیدها') }}" active="7d" :periods="$periods" />
    </section>

    <section class="pg-box" style="gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Downloads in a product card', 'دانلودها در کارت محصول') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Two quieter periods and format shaping the numbers — the same card inside a release announcement.', 'دو دورهٔ آرام‌تر و format که شکل عددها را می‌سازد — همان کارت داخل خبرِ انتشار نسخه.') }}
            </p>
        </div>
        <x-nx::analytics-card :title="$say('CLI downloads', 'دانلودهای CLI')" active="week" :format="['notation' => 'compact']" :periods="$downloads" />
    </section>
</div>
