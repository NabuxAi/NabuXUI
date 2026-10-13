{{--
    The activity dropdown as a project’s notification bell: unread dots,
    relative times in the page language, one row linking to a real page, and
    “mark all as read” which both dims the dots and pings the server. Next to
    it, the same bell with nothing left to read.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $activity = [
        ['id' => 'a1', 'actor' => ['name' => $say('Ava Karimi', 'آوا کریمی')], 'text' => $say('commented on', 'روی نظر داد'), 'target' => $say('the Q3 roadmap', 'نقشهٔ راه تابستان'), 'time' => now()->subMinutes(3), 'unread' => true, 'href' => '/components'],
        ['id' => 'a2', 'actor' => ['name' => $say('Kenji Sato', 'کنجی ساتو')], 'text' => $say('mentioned you in', 'شما را ذکر کرد در'), 'target' => $say('the design review', 'بازبینی طراحی'), 'time' => now()->subMinutes(42), 'unread' => true],
        ['id' => 'a3', 'actor' => ['name' => $say('María López', 'ماریا لوپز')], 'text' => $say('shared', 'هم‌رسانی کرد'), 'target' => $say('the sales report', 'گزارش فروش'), 'time' => now()->subHours(5), 'unread' => true],
        ['id' => 'a4', 'actor' => ['name' => $say('Omar Haddad', 'عمر حداد')], 'text' => $say('approved', 'تأیید کرد'), 'target' => $say('the launch plan', 'برنامهٔ انتشار'), 'time' => now()->subDay()],
        ['id' => 'a5', 'actor' => ['name' => $say('Lena Fischer', 'لنا فیشر')], 'text' => $say('joined the workspace', 'به ورک‌اسپیس پیوست'), 'time' => $say('Last week', 'هفتهٔ پیش')],
    ];
@endphp

<section class="pg-box" style="gap: 1rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Two inboxes, two moods', 'دو صندوق، دو حال‌وهوا') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The left bell has 3 unread — the count rolls when it changes, times read as “3 minutes ago” in the page language, and the first row links to the roadmap. The right one has nothing left: the badge and its slot fade away.', 'زنگ چپ ۳ خوانده‌نشده دارد — شمارنده با تغییر می‌غلتد، زمان‌ها به زبان صفحه «۳ دقیقه پیش» خوانده می‌شوند و ردیف اول به نقشهٔ راه لینک است. زنگ راست چیزی نمانده: نشان و جای آن محو می‌شوند.') }}
        </p>
    </div>
    <div class="pg-row">
        <x-nx::activity-dropdown :items="$activity" x-on:nx-mark-all-read="save(@js($say('All caught up', 'همه خوانده شد')))" />

        <x-nx::activity-dropdown :title="$say('Team inbox', 'صندوق تیم')" :items="[]" :empty-text="$say('The team is all caught up', 'تیم همه را خوانده')" />
    </div>
    <p style="margin: 0; color: var(--nx-text-muted)">
        {{ $say('Marking all read is a client-side dim plus a server ping — the count rolls down through NabuXUI::formatNumber’s Persian digits as well.', '«همه خوانده شد» هم خاموش‌کردن سمت کلاینت است هم اعلان سرور — شمارنده هم با ارقام فارسی می‌غلتد.') }}
        {{ NabuXUI::formatNumber(3).' '.$say('unread at first render', 'خوانده‌نشده در نخستین رندر') }}.
    </p>
</section>
