{{--
    The stat card row of a shop dashboard: numbers that roll up from zero,
    deltas that know which way is good, and one inverted metric (churn — down
    is the good news) to prove invertDelta earns its keep.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Monday morning, one glance', 'صبح دوشنبه، یک نگاه') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Each number counts itself up when it scrolls in, the sparkline draws its path, and the caption answers “compared to what?”.', 'هر عدد هنگام ورود به دید خودش را می‌شمارد، اسپارک‌لاین مسیرش را می‌کشد و زیرنویس به «نسبت به چی؟» جواب می‌دهد.') }}
        </p>
    </div>
    <div class="pg-grid">
        <x-nx::stat-card :label="$say('Revenue, 30 days', 'درآمد ۳۰ روز')" :value="482600000" :decimals="0"
            :prefix="null" suffix="{{ $fa ? ' تومان' : ' Toman' }}" :delta="12.4"
            :trend="[12, 18, 14, 22, 19, 26, 24, 31]"
            :caption="$say('vs the 30 days before', 'نسبت به ۳۰ روز قبل')" />
        <x-nx::stat-card :label="$say('Active stores', 'فروشگاه فعال')" :value="1284"
            :delta="3.8" :trend="[9, 11, 10, 13, 12, 15]"
            :caption="$say('137 opened, 86 closed', '۱۳۷ باز شد، ۸۶ بسته شد')" />
        <x-nx::stat-card :label="$say('Churn', 'ریزش')" :value="2.4" :decimals="1" suffix="٪"
            :delta="-0.6" :invertDelta="true" :trend="[4, 3.6, 3.4, 3, 2.8, 2.4]"
            :caption="$say('down is the good news', 'پایین‌آمدن خبر خوب است')" />
        <x-nx::stat-card :label="$say('Uptime', 'آپ‌تایم')" :value="99.98" :decimals="2" suffix="٪"
            :delta="0.01" :trend="[99.9, 99.95, 100, 99.97, 100, 99.98]"
            :caption="$say('6 minutes down, all planned', '۶ دقیقه قطعی، همهٔ برنامه‌ریزی‌شده')" />
    </div>
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A delta can be bad news too', 'دلتا می‌تواند خبر بد هم باشد') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">{{ $say('Same component, honest trend: support wait time climbing after the launch week.', 'همان کامپوننت، روند صادق: زمان انتظار پشتیبانی بعد از هفتهٔ عرضه بالا می‌رود.') }}</p>
        <x-nx::stat-card :label="$say('Median first reply', 'میانهٔ نخستین پاسخ')" :value="41" suffix="{{ $fa ? ' دقیقه' : ' min' }}"
            :delta="18.2" :trend="[22, 26, 24, 30, 36, 41]"
            :caption="$say('two agents on vacation', 'دو اپراتور در مرخصی')" />
    </section>

    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Bare, without a trend line', 'ساده، بدون خط روند') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">{{ $say('Drop trend and delta — the counting number alone still earns its seat in a table header or footer.', 'trend و delta را بردارید — عددِ شمرده به‌تنهایی هم در سربرگ یا فوتر جدول جا می‌شود.') }}</p>
        <x-nx::stat-card :label="$say('Open tickets', 'تیکت باز')" :value="214" :caption="$say('right now', 'همین حالا')" />
    </section>
</div>
