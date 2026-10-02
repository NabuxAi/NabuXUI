{{--
    The donut chart's real scenarios: a week of conversations split by channel,
    a widget-sized storage donut for a sidebar, and an outcome mix whose centre
    hole carries a KPI of its own.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Conversations by channel', 'گفت‌وگوها به تفکیک کانال') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The week on the support desk, as the dashboard shows it: as the card enters view the slices sweep in one after another, the percents and the centre total roll — all in the page language’s digits and percent mark. Hover a legend row and it brightens while its siblings step back; underneath, a visually-hidden table hands the same data to screen readers.', 'یک هفتهٔ میز پشتیبانی، همان‌طور که داشبورد نشانش می‌دهد: با ورود کارت به دید بخش‌ها یکی‌یکی جارو می‌شوند و درصد‌ها و جمعِ مرکزی می‌غلتند — همه با ارقام و علامت ٪ زبان صفحه. ردیف راهنما را hover کنید تا درخشان شود و هم‌خانواده‌هایش عقب بایستند؛ و زیر همه‌اش، یک جدول پنهان همان داده را به صفحه‌خوان می‌رساند.') }}
        </p>
    </div>
    <x-nx::donut-chart
        :title="$say('Conversations this week', 'گفت‌وگوهای این هفته')"
        :subtitle="$say('Every workspace · last 7 days', 'همهٔ ورک‌اسپیس‌ها · ۷ روز گذشته')"
        :data="[
            $say('WhatsApp', 'واتساپ') => 4700,
            $say('Telegram', 'تلگرام') => 3300,
            $say('Web', 'وب') => 1800,
            $say('Email', 'ایمیل') => 900,
        ]" />
</section>

<section class="pg-box" style="gap: 1.25rem; max-inline-size: 34rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A widget-sized storage donut', 'دونات فضای ذخیره، قدِ ویجت') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('size sets the ring’s diameter and thickness its share of the radius — together they tune the chart down to a sidebar widget. The centre’s caption is yours (center-label); with fractional values the whole settles on one decimal.', 'size قطر حلقه را تعیین می‌کند و thickness سهم حلقه از شعاع — با همین دو، نمودار تا قدِ ویجت نوار کناری کوچک می‌شود. برچسب چاه مرکزی هم دست شماست (center-label)؛ با مقدارهای اعشاری، جمع با یک رقم اعشار می‌نشیند (۱۲٫۳).') }}
        </p>
    </div>
    <x-nx::donut-chart
        :title="$say('Workspace storage', 'فضای ورک‌اسپیس')"
        size="9rem"
        thickness="20%"
        :center-label="$say('gigabytes', 'گیگابایت')"
        :data="[
            $say('Documents', 'اسناد') => 4.6,
            $say('Conversations', 'گفت‌وگوها') => 3.2,
            $say('Media', 'رسانه‌ها') => 2.7,
            $say('Backups', 'پشتیبان‌ها') => 1.8,
        ]" />
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The hole carries its own KPI', 'چاهِ مرکزی، KPI خودش را می‌گذارد') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Percents in the slices and legend, the full count in the hole: center-value seats any number in place of the sum and center-label names it. Left out, the data’s own total lands there with the page language’s word for it — and an empty center-label hides the caption while the number keeps rolling.', 'درصدها در بخش‌ها و راهنما، شمار کامل در چاه مرکزی: center-value هر عددی را جای جمع می‌نشاند و center-label برچسبش را. اگر نگذارید، جمعِ خودِ داده‌ها با واژهٔ زبان صفحه می‌نشیند — و center-label خالی برچسب را پنهان می‌کند، در حالی که عدد همچنان می‌غلتد.') }}
        </p>
    </div>
    <x-nx::donut-chart
        :title="$say('How the week’s conversations ended', 'نتیجهٔ گفت‌وگوهای این هفته')"
        :subtitle="$say('1,240 conversations · last 7 days', '۱٬۲۴۰ گفت‌وگو · ۷ روز گذشته')"
        :center-value="1240"
        :center-label="$say('conversations', 'گفت‌وگو')"
        :data="[
            $say('Auto-answered', 'پاسخ خودکار') => 68,
            $say('Agent-assisted', 'با کمک ایجنت') => 24,
            $say('Handed to a human', 'واگذار به انسان') => 8,
        ]" />
</section>
