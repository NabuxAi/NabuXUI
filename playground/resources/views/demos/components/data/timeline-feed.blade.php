{{--
    The timeline feed's real scenarios: an order's activity trail with
    relative times in the page language, then a changelog-style feed where
    time is a plain label.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<div class="pg-grid">
    <section class="pg-box" style="gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Order #1248, everything that happened', 'سفارش ۱۲۴۸، هرچه گذشت') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Rows reveal one after another, stitched by the gradient connector. The times are real Carbon dates rendered relative, in the page’s language — refresh tomorrow and “3 hours ago” will have aged.', 'ردیف‌ها یکی‌یکی ظاهر می‌شوند و نوار گرادیانی به هم می‌دوزدشان. زمان‌ها تاریخ واقعی‌اند و نسبی، به زبان صفحه — فردا رفرش کنید تا «۳ ساعت پیش» پیر شده باشد.') }}
            </p>
        </div>
        <x-nx::timeline-feed :label="$say('Order activity', 'فعالیت سفارش')" :items="[
            ['id' => 'o1', 'actor' => 'مریم رضایی', 'text' => $say('confirmed the order', 'سفارش را تأیید کرد'), 'target' => $fa ? '#۱۲۴۸' : '#1248', 'time' => now()->subMinutes(3), 'tone' => 'success'],
            ['id' => 'o2', 'icon' => 'upload', 'text' => $say('Invoice PDF generated', 'فاکتور PDF ساخته شد'), 'time' => now()->subMinutes(12), 'tone' => 'info'],
            ['id' => 'o3', 'actor' => 'نیلوفر احمدی', 'text' => $say('picked the shipment route', 'مسیر ارسال را برگزید'), 'target' => $say('Tehran → Tabriz', 'تهران ← تبریز'), 'time' => now()->subHours(1), 'tone' => 'neutral'],
            ['id' => 'o4', 'actor' => 'انبار ۲', 'text' => $say('handed the parcel to the courier', 'بسته را به پیک سپرد'), 'time' => now()->subHours(3), 'tone' => 'info'],
            ['id' => 'o5', 'actor' => 'سامان', 'text' => $say('rejected the invoice', 'فاکتور را رد کرد'), 'target' => $fa ? '#۹۸۰۱' : '#9801', 'time' => now()->subDays(1), 'tone' => 'warning'],
            ['id' => 'o6', 'actor' => 'مریم رضایی', 'text' => $say('resolved the rejection', 'رد شدن را حل کرد'), 'time' => now()->subDays(1)->addMinutes(40), 'tone' => 'success'],
            ['id' => 'o7', 'icon' => 'zap', 'text' => $say('Fraud check passed', 'کنترل تقلب پاس شد'), 'time' => now()->subDays(2), 'tone' => 'neutral'],
        ]" />
        <x-nx::button size="sm" variant="ghost" icon="mail" wire:click="ping(@js($say('Activity digest sent to the customer', 'خلاصهٔ فعالیت برای مشتری فرستاده شد')))">
            {{ $say('Email this trail to the customer', 'فرستادن همین جریان برای مشتری') }}
        </x-nx::button>
    </section>

    <section class="pg-box" style="gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A release feed, time as a label', 'جریان انتشار، زمان به‌شکل برچسب') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('time may stay a plain string — version numbers here instead of dates, tones marking the mood of each entry.', 'time می‌تواند یک رشتهٔ ساده بماند — اینجا شمارهٔ نسخه به‌جای تاریخ، و tone‌ها حال‌وهوای هر مدخل را می‌گذارند.') }}
            </p>
        </div>
        <x-nx::timeline-feed :label="$say('Releases', 'انتشارها')" :items="[
            ['id' => 'r1', 'icon' => 'sparkles', 'text' => $say('Kanban, calendar and chat join the block library', 'کانبان، تقویم و گفت‌وگو به کتابخانهٔ بلوک‌ها پیوستند'), 'time' => 'v2.5.0', 'tone' => 'success'],
            ['id' => 'r2', 'icon' => 'image', 'text' => $say('Invoice gains a print stylesheet', 'فاکتور شیوه‌نامهٔ چاپ گرفت'), 'time' => 'v2.4.0', 'tone' => 'info'],
            ['id' => 'r3', 'icon' => 'alert-triangle', 'text' => $say('Fixed Persian digits in the analytics headline', 'ارقام فارسی در تیتر آنالیتیکس درست شد'), 'time' => 'v2.3.1', 'tone' => 'warning'],
            ['id' => 'r4', 'icon' => 'lock', 'text' => $say('Two security hardenings', 'دو استحکام‌بخشی امنیتی'), 'time' => 'v2.3.0', 'tone' => 'danger'],
        ]" />
    </section>
</div>
