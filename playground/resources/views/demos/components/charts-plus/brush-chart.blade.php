{{--
    The brush chart's real scenarios: 90 days of daily active users zoomed to
    the last month by default, and an hourly CPU line where the window is
    narrow and the keyboard does the panning.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    // 90 days of daily active users: a weekly rhythm on a slow climb, with a launch spike.
    $days = [];
    $dau = [];
    for ($i = 0; $i < 90; $i++) {
        $days[] = $say('Day ', 'روز ').NabuXUI::formatNumber($i + 1);
        $weekly = [0.92, 1.0, 1.06, 1.08, 1.04, 0.86, 0.78][$i % 7];
        $launch = $i >= 61 && $i <= 66 ? 1 + (6 - abs(63 - $i)) * 0.07 : 1;
        $dau[] = (int) round((4200 + $i * 38) * $weekly * $launch);
    }

    $hours = [];
    $cpu = [];
    for ($h = 0; $h < 48; $h++) {
        $hours[] = NabuXUI::formatNumber($h % 24).':۰۰';
        $cpu[] = (int) round(34 + 22 * sin(($h - 8) / 24 * 2 * M_PI) + ($h % 5) * 2);
    }
    if (! $fa) {
        $hours = array_map(fn ($h) => sprintf('%02d:00', $h % 24), range(0, 47));
    }
@endphp

<div class="pg-grid">
    <section class="pg-box" style="grid-column: 1 / -1; gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Daily active users, last 90 days', 'کاربران فعال روزانه، ۹۰ روز گذشته') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('The strip under the chart holds all 90 days; the window picks the slice the main plot zooms to. Drag the window to pan, drag either edge to resize — the edges are native range inputs, so Tab lands on them and the arrow keys work as everywhere. With the window focused, arrows pan by a day and Shift+arrow by a whole window. The launch week spike is marked as the range’s high.', 'نوار زیر نمودار هر ۹۰ روز را دارد و پنجره برشی را انتخاب می‌کند که نمودار اصلی رویش زوم می‌کند. پنجره را بکشید تا جابه‌جا شود و هر لبه را بکشید تا اندازه بگیرد — لبه‌ها ورودی بومی range هستند، پس Tab رویشان می‌ایستد و کلیدهای جهت‌نما مثل همه‌جا کار می‌کنند. وقتی پنجره فوکوس دارد، جهت‌نما یک روز و Shift+جهت‌نما یک پنجرهٔ کامل جابه‌جا می‌کند. اوجِ هفتهٔ عرضه به‌عنوان بیشینهٔ بازه علامت می‌خورد.') }}
            </p>
        </div>
        <x-nx::brush-chart
            :title="$say('Daily active users', 'کاربران فعال روزانه')"
            :subtitle="$say('Web + app', 'وب + اپ')"
            :labels="$days"
            :series="[['name' => $say('Active users', 'کاربر فعال'), 'values' => $dau]]"
            :range="[56, 89]" min-span="6" />
    </section>

    <section class="pg-box" style="grid-column: 1 / -1; gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Two days of CPU, a narrow window', 'دو روز CPU، پنجرهٔ باریک') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('variant="line" without the wash, and a six-hour window. Every move dispatches nx-range with { range, from, to } — the readout below listens to it with plain Alpine.', 'variant="line" بدون سایهٔ زیر خط و پنجرهٔ شش‌ساعته. هر جابه‌جایی رویداد nx-range را با { range, from, to } می‌فرستد — خوانشِ زیر با Alpine ساده به آن گوش می‌دهد.') }}
            </p>
        </div>
        <div x-data="{ from: '', to: '' }" style="display: grid; gap: .75rem">
            <x-nx::brush-chart
                :title="$say('CPU usage', 'مصرف CPU')"
                :subtitle="$say('Percent · app-server-2', 'درصد · app-server-2')"
                variant="line" height="200" brush-height="44" :markers="false"
                :labels="$hours"
                :series="[['name' => 'CPU', 'values' => $cpu]]"
                :range="[30, 36]" min-span="3"
                x-on:nx-range="from = $event.detail.from; to = $event.detail.to" />
            <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)" x-show="from">
                {{ $say('Last event:', 'آخرین رویداد:') }} <strong x-text="from"></strong> → <strong x-text="to"></strong>
            </p>
        </div>
    </section>
</div>
