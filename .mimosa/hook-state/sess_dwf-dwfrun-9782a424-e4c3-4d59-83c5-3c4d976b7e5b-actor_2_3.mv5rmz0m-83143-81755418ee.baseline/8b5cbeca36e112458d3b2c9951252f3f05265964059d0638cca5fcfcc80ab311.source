{{--
    The gantt chart's real scenarios: a product release sprint, a seasonal
    campaign and a live event — bars drag (or move with the arrow keys), the
    dependency elbows bend across rows, and every landed move reports back
    through a toast riding the nx-move event.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    // Scenario 1 — the ۲٫۵ release sprint: dependencies, progress, today line.
    $release = [
        ['id' => 'design', 'title' => $say('Design', 'طراحی'), 'tone' => 'info', 'tasks' => [
            ['id' => 'settings', 'title' => $say('Settings page redesign', 'بازطراحی صفحهٔ تنظیمات'), 'start' => '2026-09-28', 'end' => '2026-10-03', 'tone' => 'info', 'progress' => 60],
            ['id' => 'dark', 'title' => $say('Dark-mode polish', 'پاسخ نهایی حالت تیره'), 'start' => '2026-10-05', 'end' => '2026-10-09', 'tone' => 'info', 'dependsOn' => 'settings'],
        ]],
        ['id' => 'build', 'title' => $say('Build', 'توسعه'), 'tone' => 'success', 'tasks' => [
            ['id' => 'api', 'title' => $say('Calendar API rewrite', 'بازنویسی API تقویم'), 'start' => '2026-09-30', 'end' => '2026-10-10', 'tone' => 'success', 'progress' => 35],
            ['id' => 'storage', 'title' => $say('Storage migration', 'مهاجرت ذخیره‌سازی'), 'start' => '2026-10-12', 'end' => '2026-10-22', 'tone' => 'success', 'dependsOn' => 'api'],
        ]],
        ['id' => 'content', 'title' => $say('Content', 'محتوا'), 'tone' => 'gold', 'tasks' => [
            ['id' => 'guide', 'title' => $say('Rewrite the Persian guide', 'بازنویسی راهنمای فارسی'), 'start' => '2026-10-06', 'end' => '2026-10-16', 'tone' => 'gold', 'progress' => 10],
            ['id' => 'arabic', 'title' => $say('Arabic translation', 'ترجمهٔ عربی راهنما'), 'start' => '2026-10-19', 'end' => '2026-10-29', 'tone' => 'gold', 'dependsOn' => 'guide'],
        ]],
    ];

    // Scenario 2 — the Yalda sale campaign: a three-month horizon at week scale.
    $campaign = [
        ['id' => 'marketing', 'title' => $say('Marketing', 'مارکتینگ'), 'tone' => 'accent', 'tasks' => [
            ['id' => 'teasers', 'title' => $say('Cut the video teasers', 'تدوین تیزرهای ویدیویی'), 'start' => '2026-10-12', 'end' => '2026-11-06', 'tone' => 'accent', 'progress' => 15],
            ['id' => 'newsletter', 'title' => $say('Newsletter run', 'ایمیل‌های خبری'), 'start' => '2026-11-16', 'end' => '2026-12-11', 'tone' => 'accent'],
            ['id' => 'countdown', 'title' => $say('Last-night countdown', 'پیام شمارش معکوس شب یلدا'), 'start' => '2026-12-16', 'end' => '2026-12-20', 'tone' => 'danger', 'dependsOn' => 'newsletter'],
        ]],
        ['id' => 'warehouse', 'title' => $say('Warehouse', 'انبار'), 'tone' => 'warning', 'tasks' => [
            ['id' => 'supply', 'title' => $say('Order from suppliers', 'سفارش کالا از تأمین‌کننده'), 'start' => '2026-10-05', 'end' => '2026-10-30', 'tone' => 'warning'],
            ['id' => 'packaging', 'title' => $say('Gift packaging', 'بسته‌بندی هدیهٔ یلدا'), 'start' => '2026-12-07', 'end' => '2026-12-18', 'tone' => 'warning', 'dependsOn' => 'supply'],
        ]],
        ['id' => 'support', 'title' => $say('Support', 'پشتیبانی'), 'tone' => 'info', 'tasks' => [
            ['id' => 'training', 'title' => $say('Train the reply crew', 'آموزش تیم پاسخگویی'), 'start' => '2026-12-01', 'end' => '2026-12-12', 'tone' => 'info'],
            ['id' => 'shift', 'title' => $say('Yalda-night shift', 'شیفت شب یلدا'), 'start' => '2026-12-20', 'end' => '2026-12-21', 'tone' => 'danger', 'dependsOn' => 'training'],
        ]],
    ];

    // Scenario 3 — the event: a whole quarter at month scale; the two overlapping
    // planning tasks stack into lanes and the live stream stays a single day wide.
    $event = [
        ['id' => 'venue', 'title' => $say('Venue & stage', 'مکان و صحنه'), 'tone' => 'gold', 'tasks' => [
            ['id' => 'book', 'title' => $say('Book the hall', 'رزرو سالن'), 'start' => '2026-09-14', 'end' => '2026-09-25', 'tone' => 'gold', 'progress' => 100],
            ['id' => 'stage', 'title' => $say('Stage the room', 'چیدمان صحنه'), 'start' => '2026-12-07', 'end' => '2026-12-11', 'tone' => 'gold', 'dependsOn' => 'book'],
        ]],
        ['id' => 'speakers', 'title' => $say('Speakers', 'سخنرانان'), 'tone' => 'info', 'tasks' => [
            ['id' => 'invite', 'title' => $say('Invite and confirm speakers', 'دعوت و تأیید سخنرانان'), 'start' => '2026-09-07', 'end' => '2026-10-16', 'tone' => 'info', 'progress' => 70],
            ['id' => 'rehearsal', 'title' => $say('Talk rehearsal', 'جلسهٔ تمرین ارائه'), 'start' => '2026-11-23', 'end' => '2026-12-04', 'tone' => 'info', 'dependsOn' => 'invite'],
        ]],
        ['id' => 'planning', 'title' => $say('Planning', 'برنامه‌ریزی'), 'tone' => 'accent', 'tasks' => [
            ['id' => 'signup', 'title' => $say('Attendee sign-up', 'ثبت‌نام شرکت‌کنندگان'), 'start' => '2026-11-02', 'end' => '2026-12-15', 'tone' => 'accent'],
            ['id' => 'catering', 'title' => $say('Catering plan', 'برنامهٔ پذیرایی'), 'start' => '2026-11-09', 'end' => '2026-11-27', 'tone' => 'accent'],
            ['id' => 'stream', 'title' => $say('Live stream', 'پخش زنده'), 'start' => '2026-12-14', 'end' => '2026-12-14', 'tone' => 'danger', 'dependsOn' => 'signup'],
        ]],
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The 2.5 release sprint', 'اسپرینت انتشار نسخهٔ ۲٫۵') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Drag a bar — the edges resize it, and past the timeline it rubber-bands back. No pointer? Focus a bar and use the arrows: Shift steps a week, Alt pulls the end, Escape cancels a drag in flight. The elbows tie each task to the one it waits on, the today line marks the day, and every landed move reports back through a toast (the nx-move event riding the chart).', 'میله را بکشید — لبه‌ها اندازه‌اش را عوض می‌کنند و بیرون از تایم‌لاین، کشِ لاستیکی برش می‌گرداند. کیبورد دارید؟ روی میله تمرکز کنید و کلیدهای جهت‌دار را بزنید: Shift یک هفته قدم می‌زند، Alt پایان را می‌کشد و Escape جابه‌جایی در جریان را لغو می‌کند. آرنج‌ها هر کار را به آنچه منتظرش است گره می‌زنند، خط «امروز» روزِ جاری را نشان می‌دهد و هر فرود با یک توست خبر می‌دهد (رویداد nx-move روی خود نمودار).') }}
        </p>
    </div>
    <x-nx::gantt
        :rows="$release"
        :label="$say('Release sprint', 'اسپرینت انتشار')"
        height="26rem"
        x-on:nx-move="$wire.ping($event.detail.task + ': ' + $event.detail.start + ' → ' + $event.detail.end)" />
    <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
        {{ $say('The rows stay client-side here; pass move-action="moveTask" and your Livewire moveTask($task, $start, $end) becomes the truth — the optimistic move already sits on the page and the morph keeps the bars gliding.', 'اینجا ردیف‌ها سمت مرورگر می‌مانند؛ move-action="moveTask" بدهید تا متد لایووایرِ moveTask($task, $start, $end) مرجع حقیقت شود — جابه‌جایی خوش‌بینانه از قبل روی صفحه نشسته و morph میله‌ها را سُرخورده نگه می‌دارد.') }}
    </p>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The Yalda-sale campaign', 'کمپین فروش یلدا') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('A three-month horizon opened at week scale — the pills reflow the whole chart at day or month width, and the Today button scrolls the today flag to a third of the view. Dependencies reach across rows here: the countdown waits on the newsletter run, and the night shift on the crew’s training.', 'افقی سه‌ماهه که در مقیاس هفته باز شده — قرص‌ها کل نمودار را در پهنای روز یا ماه دوباره می‌چینند و دکمهٔ «امروز» پرچم امروز را به یک‌سوم دید می‌آورد. این‌جا وابستگی‌ها از ردیفی به ردیف دیگر می‌رسند: شمارش معکوس منتظر دورهٔ ایمیل‌های خبری است و شیفت شب، منتظر آموزش تیم.') }}
        </p>
    </div>
    <x-nx::gantt
        :rows="$campaign"
        zoom="week"
        :label="$say('Yalda campaign', 'کمپین یلدا')"
        height="24rem" />
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Producing the NabuConf event', 'برگزاری رویداد نابوکنف') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('A whole quarter of event production at month scale. The planning row’s two overlapping tasks — sign-up and the catering plan — stack into two lanes instead of covering each other, and the live stream stays exactly one day wide. Persian pages read the chart right-to-left and the weekend bands land on Thursdays and Fridays.', 'یک فصل کاملِ برگزاری رویداد در مقیاس ماه. دو کارِ هم‌پوشانِ ردیف برنامه‌ریزی — ثبت‌نام و برنامهٔ پذیرایی — به‌جای هم‌پوشانی در دو لِین روی هم می‌نشینند و پخش زنده دقیقاً به پهنای یک روز می‌ماند. صفحه‌های فارسی نمودار را راست‌به‌چپ می‌خوانند و نوارهای آخر هفته روی پنجشنبه و جمعه می‌افتند.') }}
        </p>
    </div>
    <x-nx::gantt
        :rows="$event"
        zoom="month"
        :label="$say('NabuConf production', 'برگزاری نابوکنف')"
        height="22rem" />
</section>
