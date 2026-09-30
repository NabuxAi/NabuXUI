{{--
    The dot matrix chart's real scenarios: tickets by language as a stacked
    two-series matrix, then a single-series week strip where each dot is ten.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $codes = ['FA', 'EN', 'AR', 'JA', 'ZH', 'HI', 'ES', 'FR', 'DE', 'TR', 'KO', 'PT'];
    $solved = [86, 64, 41, 38, 52, 47, 33, 29, 44, 36, 31, 27];
    $handed = [12, 9, 6, 7, 5, 8, 6, 4, 9, 5, 4, 6];

    $week = $fa ? ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'] : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    $downtime = [30, 20, 45, 10, 25, 0, 5];
@endphp

<div class="pg-grid">
    <section class="pg-box" style="grid-column: 1 / -1; gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Tickets by language, who closed them', 'تیکت‌ها به تفکیک زبان و حل‌کنندهٔ آن') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Twelve languages, two series stacked in a fixed colour order — the agent’s share sits under the human handoff. One dot is ten tickets; a full column means a busy language.', 'دوازده زبان، دو سری روی هم با ترتیب رنگ ثابت — سهم ایجنت زیر واگذاری به انسان می‌نشیند. هر نقطه ده تیکت است؛ ستونِ پر یعنی زبانِ پرکار.') }}
            </p>
        </div>
        <x-nx::dot-matrix-chart
            :title="$say('Tickets by language', 'تیکت‌ها به تفکیک زبان')"
            :subtitle="$say('This week', 'این هفته')"
            :labels="$codes"
            :series="[
                ['name' => $say('Solved by the agent', 'حل‌شده توسط ایجنت'), 'values' => $solved],
                ['name' => $say('Handed to a human', 'واگذار به انسان'), 'values' => $handed],
            ]" />
        <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
            {{ NabuXUI::formatNumber(array_sum($solved)) }} {{ $say('agent-solved ·', 'حل‌شده با ایجنت ·') }}
            {{ NabuXUI::formatNumber(array_sum($handed)) }} {{ $say('handed over', 'واگذار‌شده به انسان') }}
        </p>
    </section>

    <section class="pg-box" style="gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A single series, one dot = ten minutes', 'تک‌سری، هر نقطه = ده دقیقه') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('data instead of series+labels, and unit pinning what a dot means — the week’s maintenance windows at a glance.', 'data به‌جای series+labels و unit که می‌گوید هر نقطه چیست — پنجره‌های نگهداری هفته در یک نگاه.') }}
            </p>
        </div>
        <x-nx::dot-matrix-chart
            :title="$say('Maintenance downtime', 'قطعیِ نگهداری')"
            :data="array_combine($week, $downtime)"
            :unit="10" />
    </section>
</div>
