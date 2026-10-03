{{--
    The radar's real scenarios: two releases of the support model scored on six
    skills, and a candidate scorecard from three interviewers on a 5-point scale.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<div class="pg-grid">
    <section class="pg-box" style="gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Model scorecard', 'کارنامهٔ مدل') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Six skills, two releases, a fixed rim of 100 and five rings. The shapes spring out from the centre one after another; hover a spoke (or focus the chart and use the arrow keys) to read every release on that skill. On a Persian page the spokes run counter-clockwise.', 'شش مهارت، دو نسخه، لبهٔ ثابت ۱۰۰ و پنج حلقه. شکل‌ها یکی پس از دیگری با فنر از مرکز بیرون می‌آیند؛ روی یک پره بروید (یا نمودار را فوکوس کنید و با کلیدهای جهت‌نما) تا امتیاز هر نسخه را در آن مهارت بخوانید. در صفحهٔ فارسی پره‌ها پادساعت‌گرد می‌چرخند.') }}
            </p>
        </div>
        <x-nx::radar-chart
            :title="$say('Support model v2 vs v1', 'مدل پشتیبانی نسخهٔ ۲ در برابر ۱')"
            :max="100" rings="5"
            :axes="$fa ? ['سرعت', 'دقت', 'هزینه', 'فارسی', 'کدنویسی', 'ایمنی'] : ['Speed', 'Accuracy', 'Cost', 'Persian', 'Coding', 'Safety']"
            :series="[
                ['name' => $say('v2', 'نسخهٔ ۲'), 'values' => [88, 92, 64, 95, 81, 90]],
                ['name' => $say('v1', 'نسخهٔ ۱'), 'values' => [72, 80, 78, 70, 66, 84]],
            ]" />
    </section>

    <section class="pg-box" style="gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Interview panel', 'پنل مصاحبه') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Three interviewers on a 5-point scale. Switch an interviewer off in the legend to compare the other two; the hidden shape fades and shrinks rather than vanishing.', 'سه مصاحبه‌گر با مقیاس ۵ امتیازی. مصاحبه‌گری را در راهنما خاموش کنید تا دو نفر دیگر را مقایسه کنید؛ شکلِ پنهان‌شده کم‌رنگ و کوچک می‌شود، نه این‌که ناگهان ناپدید شود.') }}
            </p>
        </div>
        <x-nx::radar-chart
            :title="$say('Candidate: senior frontend', 'نامزد: فرانت‌اند ارشد')"
            :max="5" rings="5" size="320"
            :axes="$fa ? ['معماری', 'دسترس‌پذیری', 'کار تیمی', 'کیفیت کد', 'ارتباط'] : ['Architecture', 'Accessibility', 'Teamwork', 'Code quality', 'Communication']"
            :series="[
                ['name' => $say('Sara', 'سارا'), 'values' => [4, 5, 4, 4, 3]],
                ['name' => $say('Reza', 'رضا'), 'values' => [5, 3, 4, 5, 4]],
                ['name' => $say('Mina', 'مینا'), 'values' => [3, 4, 5, 4, 5]],
            ]" />
    </section>
</div>
