{{--
    The sparkline's real habitats: a weekly-sales table of a marketplace (one
    tiny line per row, the direction colored, the numbers whispered to screen
    readers), compact server-monitor rows with the height/area dials, and one
    honest churn card — a falling line that is good news, so the judgement
    lives in the words, not the color.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $series = fn (array $values) => implode('، ', array_map(fn ($v) => NabuXUI::formatNumber($v), $values));

    $stores = [
        ['name' => $say('Woodart Gallery', 'گالری چوب‌آرت'), 'kind' => $say('Home decor', 'دکوراسیون'), 'sales' => [12, 18, 14, 22, 19, 26, 31], 'trend' => 'up', 'delta' => 12.4],
        ['name' => $say('Nilgoon Books', 'کتاب‌فروشی نیلگون'), 'kind' => $say('Books & stationery', 'کتاب و لوازم‌تحریر'), 'sales' => [22, 21, 23, 20, 19, 17, 15], 'trend' => 'down', 'delta' => -8.1],
        ['name' => $say('Cafe Dami', 'کافه دمی'), 'kind' => $say('Coffee & beans', 'قهوه و دانه'), 'sales' => [9, 11, 10, 13, 12, 15, 14], 'trend' => 'up', 'delta' => 3.2],
    ];

    $servers = [
        ['name' => $say('App worker', 'کارگزار اپ'), 'metric' => $say('CPU, 7 days', 'پردازنده، ۷ روز'), 'load' => [41, 38, 44, 40, 37, 42, 39], 'trend' => null, 'value' => '39٪'],
        ['name' => $say('Database', 'پایگاه‌داده'), 'metric' => $say('CPU, 7 days', 'پردازنده، ۷ روز'), 'load' => [55, 62, 58, 71, 64, 76, 69], 'trend' => 'up', 'value' => '69٪'],
        ['name' => $say('Cache', 'کش'), 'metric' => $say('Hit rate, 7 days', 'نرخ HIT، ۷ روز'), 'load' => [72, 74, 73, 76, 78, 77, 80], 'trend' => 'up', 'value' => '80٪'],
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div class="pg-row" style="justify-content: space-between; align-items: start">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Marketplace, Monday report', 'بازارگاه، گزارش دوشنبه') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Each store gets one line in its row: green or red says which way the week went, the end dot marks yesterday, and the actual numbers sit beside it visually hidden for screen readers.', 'هر فروشگاه یک خط در ردیف خودش دارد: سبز یا سرخ جهت هفته را می‌گوید، نقطهٔ انتها دیروز را نشانه می‌زند و خود اعداد پنهانِ دیداری کنارش برای صفحه‌خوان‌ها می‌نشینند.') }}
            </p>
        </div>
        <x-nx::button size="sm" variant="ghost" icon="download" wire:click="save('{{ $say('Weekly report is ready', 'گزارش هفتگی آماده شد') }}')">
            {{ $say('Weekly report', 'گزارش هفتگی') }}
        </x-nx::button>
    </div>
    <table style="inline-size: 100%; border-collapse: collapse; font-size: var(--nx-text-sm)">
        <caption class="nx-visually-hidden">{{ $say('Daily sales of the last seven days per store', 'فروش روزانهٔ هفت روز اخیر هر فروشگاه') }}</caption>
        <thead>
            <tr style="color: var(--nx-text-subtle)">
                <th scope="col" style="padding: .5rem .75rem .5rem 0; font-weight: 500; text-align: start">{{ $say('Store', 'فروشگاه') }}</th>
                <th scope="col" style="padding: .5rem .75rem; font-weight: 500; text-align: start">{{ $say('Daily sales — last 7 days', 'فروش روزانه — ۷ روز اخیر') }}</th>
                <th scope="col" style="padding: .5rem .75rem; font-weight: 500; text-align: start">{{ $say('Week total', 'جمع هفته') }}</th>
                <th scope="col" style="padding: .5rem 0 .5rem .75rem; font-weight: 500; text-align: end">{{ $say('Change', 'تغییر') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stores as $store)
                <tr style="border-block: 1px solid var(--nx-border)">
                    <td style="padding: .75rem .75rem .75rem 0">
                        <strong>{{ $store['name'] }}</strong>
                        <span style="display: block; color: var(--nx-text-muted); font-size: var(--nx-text-xs)">{{ $store['kind'] }}</span>
                    </td>
                    <td style="padding: .75rem; inline-size: 11rem">
                        <x-nx::sparkline :data="$store['sales']" :trend="$store['trend']" />
                        <span class="nx-visually-hidden">{{ $say('Daily sales: ', 'فروش روزانه: ') }}{{ $series($store['sales']) }}</span>
                    </td>
                    <td style="padding: .75rem; white-space: nowrap">{{ NabuXUI::formatNumber(array_sum($store['sales'])) }} {{ $say('orders', 'سفارش') }}</td>
                    <td style="padding: .75rem 0 .75rem .75rem; text-align: end">
                        <x-nx::badge :tone="$store['delta'] >= 0 ? 'success' : 'danger'">
                            {{ ($store['delta'] >= 0 ? '+' : '−') . NabuXUI::formatNumber(abs($store['delta']), 1) }}٪
                        </x-nx::badge>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The status page of one cluster', 'صفحهٔ وضعیت یک خوشه') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('Row height wants a shorter line: --nx-sparkline-height squeezes it, and area="false" drops the fill for the rows that would get noisy. The worker stays unjudged — no trend, the quiet neutral — while the same rising line means worry on the database and good news on the cache hit rate; the words decide, not the slope.', 'ارتفاع ردیف خطِ کوتاه‌تر می‌خواهد: --nx-sparkline-height آن را فشرده می‌کند و area="false" پرشدگی را برای ردیف‌های شلوغ برمی‌دارد. کارگزار بی‌داوری مانده — بدون trend، خنثی و آرام — در حالی که همین خطِ بالارونده روی پایگاه‌داده نگرانی و روی نرخ کش خبر خوب است؛ قضاوت با واژه‌هاست، نه با شیب.') }}
        </p>
        <ul style="list-style: none; margin: 0; padding: 0; display: grid; gap: .9rem">
            @foreach ($servers as $server)
                <li class="pg-row" style="gap: 1rem">
                    <span style="inline-size: 8.5rem; flex: none">
                        <strong style="font-size: var(--nx-text-sm)">{{ $server['name'] }}</strong>
                        <span style="display: block; color: var(--nx-text-muted); font-size: var(--nx-text-xs)">{{ $server['metric'] }}</span>
                    </span>
                    <x-nx::sparkline :data="$server['load']" :trend="$server['trend']" :area="$server['trend'] === null" style="--nx-sparkline-height: 1.5rem; flex: 1" />
                    <span class="nx-visually-hidden">{{ $server['metric'] }}: {{ $series($server['load']) }}</span>
                    <strong style="font-size: var(--nx-text-sm); font-family: var(--nx-font-mono)">{{ $server['value'] }}</strong>
                </li>
            @endforeach
        </ul>
    </section>

    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Falling can be good news', 'پایین‌آمدن هم می‌تواند خبر خوب باشد') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('Customer churn, down six weeks straight. trend="down" paints the line red because that is what a falling line looks like here — the badge and the sentence carry the verdict, so color is never the only cue.', 'ریزش مشتری، شش هفتهٔ پیوسته رو به پایین. trend="down" خط را سرخ می‌کند چون افتِ خط همین‌شکلی است — نشان و جمله حکم را می‌گویند، تا رنگ هرگز تنها سرنخ نماند.') }}
        </p>
        <div style="display: grid; gap: .75rem">
            <span class="pg-row" style="gap: .5rem">
                <strong style="font-size: var(--nx-text-lg)">{{ NabuXUI::formatNumber(2.1, 1) }}٪</strong>
                <x-nx::badge tone="success" dot>{{ $say('improving', 'در حال بهبود') }}</x-nx::badge>
            </span>
            <x-nx::sparkline :data="[4, 3.6, 3.4, 3, 2.8, 2.4, 2.1]" trend="down" />
            <span class="nx-visually-hidden">{{ $say('Weekly churn percent: ', 'درصد ریزش هفتگی: ') }}{{ $series([4, 3.6, 3.4, 3, 2.8, 2.4, 2.1]) }}</span>
            <span style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $say('Weekly churn — from 4% six weeks ago to 2.1% this week, after the onboarding rework.', 'ریزش هفتگی — از ۴٪ شش هفته پیش به ۲٫۱٪ این هفته، پس از بازآرایی راه‌اندازی.') }}</span>
        </div>
    </section>
</div>
