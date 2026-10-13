{{--
    The feature card's real scenarios: the four pillars of a landing page,
    then one linked card with extra content in the default slot.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The landing page pillars', 'ستون‌های صفحهٔ فرود') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Four features, four CSS-only illustrations — mail sorting itself, threads folding into a summary, a bolt at work, people taking their seats. Under reduced motion the loops simply rest.', 'چهار ویژگی، چهار تصویرسازیِ خالص-CSS — نامه‌ها که خودشان مرتب می‌شوند، رشته‌ها که در یک خلاصه جمع می‌شوند، رعدی که کار می‌کند، آدم‌هایی که در جای خود می‌نشینند. با حرکت کم حلقه‌ها فقط می‌خوابند.') }}
        </p>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 15rem), 1fr)); gap: var(--nx-space-4)">
        <x-nx::feature-card visual="inbox" tone="cyan"
            :title="$say('A smart inbox', 'صندوق ورودی هوشمند')"
            :description="$say('New mail lands on top and sorts itself before you look.', 'نامهٔ تازه بالا می‌نشیند و پیش از نگاه شما خودش را مرتب می‌کند.')" />
        <x-nx::feature-card visual="summary" tone="violet"
            :title="$say('AI summaries', 'خلاصهٔ هوشمند')"
            :description="$say('Long threads read themselves and hand you three lines.', 'رشته‌های بلند خودشان را می‌خوانند و سه سطر به شما می‌دهند.')" />
        <x-nx::feature-card visual="processing" tone="gold"
            :title="$say('Instant processing', 'پردازش بی‌درنگ')"
            :description="$say('Thousands of documents, a heartbeat later done.', 'هزاران سند، یک ضربان قلب بعد تمام.')" />
        <x-nx::feature-card visual="team" tone="lapis"
            :title="$say('Auto-assignment', 'تخصیص خودکار')"
            :description="$say('Tickets find the right person, not the free one.', 'تیکت آدمِ درست را پیدا می‌کند، نه آدمِ بیکار را.')" />
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem; max-inline-size: 26rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('One card that links out', 'یک کارت که لینک می‌شود') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('href turns the whole card into one anchor, and the default slot adds a quiet line under the description — here, the pilot numbers.', 'href کل کارت را به یک لنگر تبدیل می‌کند و اسلات پیش‌فرض یک خط آرام زیر توضیح می‌گذارد — اینجا اعداد پایلوت.') }}
        </p>
    </div>
    <x-nx::feature-card visual="processing" tone="cyan" href="#"
        :title="$say('Go behind the numbers', 'پشت عددها بروید')"
        :description="$say('The processing pipeline, end to end.', 'خط لولهٔ پردازش، از ابتدا تا انتها.')">
        <p style="margin: .5rem 0 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
            {{ $say('Pilot: 42 teams · 1.9M documents · median 240 ms', 'پایلوت: ۴۲ تیم · ۱٫۹ میلیون سند · میانه ۲۴۰ میلی‌ثانیه') }}
        </p>
    </x-nx::feature-card>
</section>
