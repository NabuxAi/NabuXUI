{{--
    The metric chart's real scenarios: a workspace-health chart with three
    switchable metrics — money, people, and a latency metric where down is
    good.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $wave = fn (int $i, float $base, float $swing, float $noise = 0.35) => max(0, round($base + $swing * sin($i / 2.7) + $swing * $noise * sin($i * 12.9898) * cos($i * 4.1414)));

    $months = $fa
        ? ['مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند', 'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور']
        : ['Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'];

    $metrics = [
        ['id' => 'revenue', 'label' => $say('Revenue', 'درآمد'), 'values' => array_map(fn ($i) => 32000 + $i * 1850 + $wave($i, 0, 2600), range(0, 11)), 'format' => ['style' => 'currency', 'currency' => 'USD', 'maximumFractionDigits' => 0], 'delta' => 12.4],
        ['id' => 'users', 'label' => $say('Active users', 'کاربران فعال'), 'values' => array_map(fn ($i) => 8200 + $i * 410 + $wave($i + 3, 0, 900), range(0, 11)), 'delta' => 4.1],
        ['id' => 'latency', 'label' => $say('Latency', 'تأخیر'), 'values' => array_map(fn ($i) => 240 - $i * 6 + $wave($i + 7, 0, 22), range(0, 11)), 'format' => ['style' => 'unit', 'unit' => 'millisecond', 'maximumFractionDigits' => 0], 'delta' => -8.2, 'invertDelta' => true],
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Workspace health', 'سلامت ورک‌اسپیس') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Twelve months, three metrics on tabs: the line morphs into the new series and the headline rolls. Watch latency — a falling line is good there, so invertDelta flips the delta’s colour.', 'دوازده ماه، سه متریک روی تب: خط به سری تازه مورف می‌شود و عدد تیتر می‌غلتد. تأخیر را ببینید — خط نزولی آن‌جا خوب است، پس invertDelta رنگ دلتا را برمی‌گرداند.') }}
        </p>
    </div>
    <x-nx::metric-chart
        :title="$say('Workspace health', 'سلامت ورک‌اسپیس')"
        :caption="$say('vs last month', 'نسبت به ماه پیش')"
        :labels="$months"
        :metrics="$metrics" />
</section>

<section class="pg-box" style="gap: 1.25rem; max-inline-size: 44rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('One metric, opening on it', 'یک متریک، با همان شروع') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('active picks the starting tab; height tunes the plot — a half-height card for a sidebar widget.', 'active تب آغازین را برمی‌گزیند؛ height بلندی نمودار را تنظیم می‌کند — کارتی نیم‌قد برای ویجت نوار کناری.') }}
        </p>
    </div>
    <x-nx::metric-chart
        :title="$say('Tickets resolved', 'تیکت‌های حل‌شده')"
        :active="'users'"
        :height="140"
        :labels="$months"
        :metrics="[
            ['id' => 'revenue', 'label' => $say('Opened', 'باز‌شده'), 'values' => array_map(fn ($i) => 900 + $i * 42 + $wave($i + 1, 0, 120), range(0, 11)), 'delta' => 3.3],
            ['id' => 'users', 'label' => $say('Resolved', 'حل‌شده'), 'values' => array_map(fn ($i) => 860 + $i * 41 + $wave($i + 2, 0, 110), range(0, 11)), 'delta' => 6.9],
        ]" />
</section>
