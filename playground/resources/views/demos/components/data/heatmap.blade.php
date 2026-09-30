{{--
    The heatmap's real scenarios: a support team's contribution calendar over
    26 weeks, then the same block as an hour-by-hour load matrix.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    // Deterministic "random" numbers so every render (and every Livewire round trip) matches.
    $wave = fn (int $i, float $base, float $swing, float $noise = 0.35) => max(0, round($base + $swing * sin($i / 2.7) + $swing * $noise * sin($i * 12.9898) * cos($i * 4.1414)));

    $end = new DateTimeImmutable('2026-09-26', new DateTimeZone('UTC'));
    $heat = [];
    $total = 0;
    for ($d = 0; $d < 26 * 7; $d++) {
        $day = $end->modify("-{$d} days");
        $weekend = in_array((int) $day->format('N'), [6, 7], true);
        $v = $wave($d, $weekend ? 2 : 7, $weekend ? 2 : 6, 0.9);
        $total += ($d % 11 === 3) ? 0 : $v;
        $heat[] = ['date' => $day->format('Y-m-d'), 'value' => ($d % 11 === 3) ? 0 : $v];
    }

    $days = $fa
        ? ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنجشنبه', 'جمعه']
        : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    $hours = $fa
        ? ['۰۹', '۱۱', '۱۳', '۱۵', '۱۷', '۱۹', '۲۱']
        : ['09', '11', '13', '15', '17', '19', '21'];
    $load = [];
    for ($r = 0; $r < 7; $r++) {
        $row = [];
        for ($c = 0; $c < 7; $c++) {
            $row[] = (int) $wave($r * 7 + $c, $r > 4 ? 3 : 8, 4, 0.8);
        }
        $load[] = $row;
    }
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Conversations answered, half a year', 'گفت‌وگوهای پاسخ‌داده‌شده، نیم‌سال') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Twenty-six weeks of three teams — quiet weekends, one dark week of holidays, and the summary counted on the server with locale digits.', 'بیست‌وشش هفته از سه تیم — آخر هفته‌های خالی، یک هفتهٔ تاریکِ تعطیلات، و جمعی که روی سرور با ارقام زبان شما حساب شده.') }}
        </p>
    </div>
    <x-nx::heatmap
        :title="$say('Conversations answered', 'گفت‌وگوهای پاسخ‌داده‌شده')"
        :subtitle="$say('Last 26 weeks · Tokyo, Berlin and São Paulo teams', '۲۶ هفتهٔ اخیر · تیم‌های توکیو، برلین و سائوپائولو')"
        :weeks="26" :data="$heat"
        :unit="$say('conversations', 'گفت‌وگو')"
        :summary="NabuXUI::formatNumber($total).' '.$say('conversations in 6 months', 'گفت‌وگو در ۶ ماه')" />
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The same block, as a load matrix', 'همان بلوک، به‌شکل ماتریس بار') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Rows are days, columns are opening hours — any numeric matrix works; the row and column names come in through labels.', 'ردیف‌ها روزها و ستون‌ها ساعات کاری‌اند — هر ماتریس عددی کار می‌کند؛ نام ردیف و ستون از labels می‌آید.') }}
        </p>
    </div>
    <x-nx::heatmap
        :title="$say('Queue load by hour', 'بار صف به تفکیک ساعت')"
        :data="$load"
        :labels="['rows' => $days, 'columns' => $hours]"
        :unit="$say('waiting chats', 'گفت‌وگوی در انتظار')" />
</section>
