{{--
    Parametric loader's real scenarios: the "thinking" state of an assistant
    card (three curves, three personalities), then a toolbar of small ones —
    the way a sync row uses them while each endpoint answers.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('An assistant, thinking', 'دستیاری در حال فکر') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('While the model streams, the card is not dead: a rose, a spirograph and a Lissajous take turns drawing themselves with a glowing head.', 'تا وقتی مدل جریان دارد، کارت مرده نیست: یک رز، یک اسپیروگراف و یک لیساژو نوبتی خودشان را با سرِ درخشان می‌کشند.') }}
        </p>
    </div>
    <div class="pg-row" style="justify-content: space-between; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); padding: 1.25rem">
        <div style="display: grid; gap: .5rem; max-inline-size: 32ch">
            <strong style="font: 700 var(--nx-text-lg) / 1.3 var(--nx-font-display)">{{ $say('Drafting your answer…', 'پیش‌نویس جواب‌ات…') }}</strong>
            <span style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">
                {{ $say('Usually under '.NabuXUI::formatNumber(3).' seconds.', 'معمولاً کمتر از '.NabuXUI::formatNumber(3).' ثانیه.') }}
            </span>
        </div>
        <div class="pg-row" style="gap: 2rem">
            <x-nx::parametric-loader size="lg" :label="$say('Thinking', 'در حال فکر')" />
            <x-nx::parametric-loader kind="spiro" size="lg" :label="$say('Thinking', 'در حال فکر')" />
            <x-nx::parametric-loader kind="lissajous" size="lg" :label="$say('Thinking', 'در حال فکر')" />
        </div>
    </div>
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A sync row, one curve each', 'ردیف همگام‌سازی، برای هرکدام یک منحنی') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('Custom options shape the path — a seven-petal rose, a tight spiro, a 5×4 Lissajous — and duration slows a lap down.', 'گزینه‌های سفارشی مسیر را شکل می‌دهند — رزِ هفت‌گلبرگ، اسپیروی تنگ و لیساژوی ۵×۴ — و duration یک دور را آرام می‌کند.') }}
        </p>
        <div class="pg-row" style="gap: 2rem; align-items: center">
            <x-nx::parametric-loader kind="rose" :options="['n' => 7, 'd' => 3]" :label="$say('Syncing calendars', 'همگام‌سازی تقویم‌ها')" />
            <x-nx::parametric-loader kind="spiro" :options="['R' => 6, 'r' => 1, 'offset' => 3]" :label="$say('Syncing files', 'همگام‌سازی فایل‌ها')" />
            <x-nx::parametric-loader kind="lissajous" :options="['a' => 5, 'b' => 4]" :duration="4200" :label="$say('Syncing mail', 'همگام‌سازی ایمیل')" />
            <x-nx::parametric-loader size="sm" :label="$say('Almost there', 'نزدیک است')" />
        </div>
    </section>

    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Why a drawn path', 'چرا مسیرِ کشیده‌شده') }}</h3>
        <ul role="list" style="margin: 0; padding-inline-start: 1.25rem; display: grid; gap: .5rem; color: var(--nx-text-muted)">
            <li>{{ $say('The faint stroke is the whole route; the bright head and its fading trail only travel it — you can see progress, not just activity.', 'خطِ کم‌رنگ کل مسیر است؛ سرِ روشن و دنبالهٔ محوش فقط روی آن سفر می‌کنند — پیشرفت را می‌بینید، نه فقط فعالیت را.') }}</li>
            <li>{{ $say('role="status" with the hidden label ships by default; under reduced motion the trail still glides on transforms only.', 'role="status" با برچسب پنهان از ابتدا هست؛ در حرکت کم، دنباله فقط روی ترنسفورم‌ها می‌لغزد.') }}</li>
        </ul>
    </section>
</div>
