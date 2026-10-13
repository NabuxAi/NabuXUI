{{--
    The radial bar's real scenarios: a quarter's goals each against its own
    target, and a server's resource usage as a closed 360° dial.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<div class="pg-grid">
    <section class="pg-box" style="grid-column: 1 / -1; gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Quarter goals', 'اهداف فصل') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Four goals with four different targets: each ring is its share of its own max, so revenue in percent, new users against 2,000 and satisfaction against 5 sit on the same dial. The rings sweep in one after another; the centre reads the average, or the ring under the pointer, and the list beside it keeps every value readable without hovering.', 'چهار هدف با چهار سقف متفاوت: هر حلقه سهمی از سقف خودش است، پس درآمد به درصد، کاربر تازه در برابر ۲٬۰۰۰ و رضایت در برابر ۵ روی یک صفحه می‌نشینند. حلقه‌ها یکی‌یکی جارو می‌شوند؛ مرکز میانگین را می‌خواند، یا حلقهٔ زیر نشانگر را، و فهرست کنارش همهٔ مقدارها را بی‌نیاز از هاور خوانا نگه می‌دارد.') }}
            </p>
        </div>
        <x-nx::radial-bar
            :title="$say('Q3 goals', 'اهداف فصل سوم')"
            :subtitle="$say('Progress against target', 'پیشرفت نسبت به هدف')"
            :center-label="$say('On track', 'در مسیر')"
            :data="[
                ['label' => $say('Revenue', 'درآمد'), 'value' => 82, 'hint' => $say('Percent of plan', 'درصد از برنامه')],
                ['label' => $say('New users', 'کاربر تازه'), 'value' => 1240, 'max' => 2000, 'hint' => $say('Target 2,000', 'هدف: ۲٬۰۰۰')],
                ['label' => $say('Satisfaction', 'رضایت'), 'value' => 4.6, 'max' => 5, 'hint' => $say('Out of 5', 'از ۵')],
                ['label' => $say('Docs coverage', 'پوشش مستندات'), 'value' => 57],
            ]" />
    </section>

    <section class="pg-box" style="gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Server resources', 'منابع سرور') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('sweep="360" closes the rings into a full dial; three rings get thicker bars automatically.', 'با sweep="360" حلقه‌ها کامل بسته می‌شوند؛ سه حلقه خودبه‌خود ضخیم‌تر می‌شوند.') }}
            </p>
        </div>
        <x-nx::radial-bar
            :title="$say('app-server-2', 'app-server-2')"
            sweep="360" size="11rem"
            :center-value="NabuXUI::formatNumber(3).' / '.NabuXUI::formatNumber(3)"
            :center-label="$say('Healthy', 'سالم')"
            :data="[
                ['label' => 'CPU', 'value' => 64],
                ['label' => $say('Memory', 'حافظه'), 'value' => 11.2, 'max' => 16, 'hint' => $say('GB of 16', 'گیگابایت از ۱۶')],
                ['label' => $say('Disk', 'دیسک'), 'value' => 38],
            ]" />
    </section>
</div>
