{{--
    The curved timeline's real scenarios: an eighteen-month product roadmap,
    then a personal-project changelog on a gentler wave.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The roadmap, as told to customers', 'نقشهٔ راه، همان‌طور که به مشتری گفته می‌شود') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Scroll and the line draws itself; each dot lights as the line reaches it and the cards take turns on either side. In RTL the whole snake mirrors.', 'اسکرول کنید تا خط خودش را بکشد؛ هر نقطه با رسیدن خط روشن می‌شود و کارت‌ها یک‌درمیان دو طرف می‌نشینند. در راست‌به‌چپ کل مارپیچ آینه می‌شود.') }}
        </p>
    </div>
    <x-nx::curved-timeline :label="$say('Product roadmap', 'نقشهٔ راه محصول')" :items="[
        ['date' => $fa ? 'بهار ۱۴۰۴' : 'Spring 2025', 'title' => $say('Private beta in Berlin', 'آزمون خصوصی در برلین'), 'description' => $say('Forty support teams, three languages, one inbox.', 'چهل تیم پشتیبانی، سه زبان، یک صندوق ورودی.')],
        ['date' => $fa ? 'پاییز ۱۴۰۴' : 'Fall 2025', 'title' => $say('Twelve languages', 'دوازده زبان'), 'description' => $say('Español, Français, 日本語, العربية — answered natively, machine-translated never.', 'اسپانیایی، فرانسوی، ژاپنی، عربی — پاسخ بومی، نه ترجمهٔ ماشینی.')],
        ['date' => $fa ? 'زمستان ۱۴۰۴' : 'Winter 2026', 'title' => $say('Voice in Tokyo', 'صدا در توکیو'), 'description' => $say('音声での応答: agents that listen and speak on the phone.', 'پاسخ صوتی: ایجنت‌هایی که در تلفن می‌شنوند و حرف می‌زنند.')],
        ['date' => $fa ? 'بهار ۱۴۰۵' : 'Spring 2026', 'title' => $say('São Paulo office', 'دفتر سائوپائولو'), 'description' => $say('Atendimento em português, 24 horas por dia.', 'پشتیبانی پرتغالی، شبانه‌روزی.')],
        ['date' => $fa ? 'پاییز ۱۴۰۵' : 'Fall 2026', 'title' => $say('Right-to-left everywhere', 'راست‌به‌چپ در همه‌جا'), 'description' => $say('فارسی و العربية with mirrored layouts across every surface.', 'فارسی و عربی با چیدمان آینه‌شده در همهٔ سطوح.')],
    ]" />
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A changelog on a gentler wave', 'تغییرنامه روی موجی ملایم‌تر') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('amplitude flattens the snake for shorter pages — four releases of a small open-source tool.', 'amplitude مار را برای صفحه‌های کوتاه‌تر صاف می‌کند — چهار انتشارِ یک ابزار آزادِ کوچک.') }}
        </p>
    </div>
    <x-nx::curved-timeline :label="$say('Changelog', 'تغییرنامه')" amplitude="26px" :items="[
        ['date' => 'v0.3', 'title' => $say('Persian digits everywhere', 'ارقام فارسی در همه‌جا'), 'description' => $say('Numbers roll, in the reader’s own script.', 'اعداد با خط خواننده‌شان می‌غلتند.')],
        ['date' => 'v0.4', 'title' => $say('Print stylesheets', 'شیوه‌نامهٔ چاپ'), 'description' => $say('Because paper did not die.', 'چون کاغذ نمرد.')],
        ['date' => 'v0.5', 'title' => $say('Keyboard everywhere', 'کیبورد در همه‌جا'), 'description' => $say('Roving tab stops, no traps.', 'توقف‌های roam‌دار، بدون دام.')],
        ['date' => 'v0.6', 'title' => $say('Reduced motion, first-class', 'حرکت کم، در درجهٔ اول'), 'description' => $say('Every loop rests when asked.', 'هر حلقه وقتی خواسته شود می‌خوابد.')],
    ]" />
</section>
