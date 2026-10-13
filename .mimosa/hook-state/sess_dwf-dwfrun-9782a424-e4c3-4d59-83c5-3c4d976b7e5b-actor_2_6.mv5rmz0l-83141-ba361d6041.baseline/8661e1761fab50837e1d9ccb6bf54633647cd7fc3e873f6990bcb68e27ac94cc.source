{{--
    Scroll progress's real scenarios: the page itself gets the top bar (look at
    the very top of the window), and a long article inside a scrolling box gets
    a sticky bar plus the back-to-top ring.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $paragraphs = $fa
        ? [
            'نوروز برای ما فقط آغاز سال نیست؛ فرصتی است تا هر چیزی را که در طول سال انباشته شده، دوباره ببینیم. این یادداشت دربارهٔ همین بازبینی است — در محصول، در تیم و در شیوهٔ کار.',
            'سه ماه پیش تصمیم گرفتیم هر صفحهٔ پنل را با یک پرسش ساده بسنجیم: آیا کاربر در پنج ثانیهٔ نخست می‌فهمد این صفحه برای چیست؟ پاسخ برای نیمی از صفحه‌ها «نه» بود.',
            'نخستین تغییر، حذف بود. فیلترهایی که کسی به کار نمی‌برد، ستون‌هایی که فقط برای کامل بودن آمده بودند و دکمه‌هایی که سه بار تکرار شده بودند.',
            'تغییر دوم، حرکت بود. حرکت را جایی گذاشتیم که چیزی را توضیح می‌دهد: پنلی که از دکمه‌اش باز می‌شود، فهرستی که جای آیتم حذف‌شده را پر می‌کند.',
            'و تغییر سوم، زبان بود. همه‌چیز را یک‌بار به فارسی و یک‌بار به انگلیسی خواندیم تا مطمئن شویم چیدمان در هر دو جهت درست می‌نشیند.',
            'نتیجه؟ زمان رسیدن به نخستین کار مفید از ۴۰ ثانیه به ۱۲ ثانیه رسید. و مهم‌تر، تیکت‌های «این دکمه کجاست؟» تقریباً ناپدید شد.',
        ]
        : [
            'For us, Nowruz is not only the start of a year; it is a chance to look again at everything that piled up during it. This note is about that review — of the product, the team and the way we work.',
            'Three months ago we decided to judge every panel page by one simple question: does a user understand what this page is for within five seconds? For half of the pages the answer was no.',
            'The first change was removal. Filters nobody used, columns that were there only for completeness, buttons repeated three times.',
            'The second change was motion. We put motion only where it explains something: a panel that opens out of its button, a list that closes the gap a deleted item leaves.',
            'And the third change was language. We read everything once in Persian and once in English to make sure the layout sits right in both directions.',
            'The result? Time to first useful action went from 40 seconds to 12. More importantly, “where is that button?” tickets nearly vanished.',
        ];
@endphp

<x-nx::scroll-progress :label="$say('Page reading progress', 'پیشرفت خواندن صفحه')" />

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Blog post in a reading pane', 'یادداشت وبلاگ در قاب مطالعه') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The page has its own bar at the very top of the window. This box scrolls on its own: its bar sticks to the top of the box, and the ring at the bottom fills as you read — press it to jump back up.', 'خود صفحه نوارش را بالای پنجره دارد. این جعبه جداگانه اسکرول می‌خورد: نوارش به بالای جعبه می‌چسبد و حلقهٔ پایین با خواندن پر می‌شود — با زدنش به بالا برمی‌گردید.') }}
        </p>
    </div>
    <div id="fx-progress-box" tabindex="0" aria-label="{{ $say('Article', 'مقاله') }}" style="position: relative; block-size: 22rem; overflow: auto; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); background: var(--nx-surface)">
        <x-nx::scroll-progress container="#fx-progress-box" position="sticky" :label="$say('Article reading progress', 'پیشرفت خواندن مقاله')" />
        <article style="display: grid; gap: 1rem; padding: 1.5rem; max-inline-size: 40rem; margin-inline: auto">
            <h4 style="margin: 0; font: 700 var(--nx-text-2xl) / 1.3 var(--nx-font-display)">{{ $say('What a spring cleaning taught our product', 'خانه‌تکانی بهاره به محصول ما چه آموخت') }}</h4>
            <p style="margin: 0; color: var(--nx-text-subtle); font-size: var(--nx-text-sm)">{{ $say('6 min read', '۶ دقیقه مطالعه') }}</p>
            @foreach (array_merge($paragraphs, $paragraphs) as $paragraph)
                <p style="margin: 0; line-height: 1.9">{{ $paragraph }}</p>
            @endforeach
        </article>
        <div style="position: sticky; inset-block-end: 0; display: flex; justify-content: flex-end; padding: 0 1rem 1rem; pointer-events: none">
            <x-nx::scroll-progress variant="circle" position="static" container="#fx-progress-box" :label="$say('Back to the top of the article', 'بازگشت به ابتدای مقاله')" style="pointer-events: auto" />
        </div>
    </div>
</section>
