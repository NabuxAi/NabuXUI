{{--
    The marquee wall's real scenario: the testimonial band of a product page —
    three rows at unsynchronised speeds and directions, each row pausing on
    hover/focus, and one button flipping every row’s direction at once. The
    second half of every track is an aria-hidden copy that closes the seam.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (string $s): string => $fa ? strtr($s, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']) : $s;

    $row1 = [
        ['ini' => 'LK', 'fa' => 'لنا — برلین',        'en' => 'Lena · Berlin',        'faLine' => 'داشبورد پیش از قهوه‌ام بالا می‌آید.', 'enLine' => 'The dashboard loads before my coffee does.'],
        ['ini' => 'RO', 'fa' => 'رای — سنگاپور',       'en' => 'Ravi · Singapore',     'faLine' => 'همگام‌سازی انبار داده هر '.$num('15').' دقیقه، بی‌حرف.', 'enLine' => 'Warehouse sync just works, every fifteen minutes.'],
        ['ini' => 'AM', 'fa' => 'ایفی — دوبلین',       'en' => 'Aoife · Dublin',       'faLine' => 'بازپخش نشست، باگ را در همان یک تماشا پیدا کرد.', 'enLine' => 'Session replay found the bug in a single watch.'],
        ['ini' => 'JT', 'fa' => 'یوناس — تورنتو',      'en' => 'Jonas · Toronto',      'faLine' => 'ریل‌های نگهبان بودجهٔ سه‌ماهه را نجات دادند.', 'enLine' => 'Guardrails saved the quarterly budget.'],
        ['ini' => 'MS', 'fa' => 'مِی — اوساکا',         'en' => 'Mei · Osaka',          'faLine' => 'تأخیر از اینجا؟ فقط '.$num('26').' میلی‌ثانیه.', 'enLine' => 'Latency from here? Twenty-six milliseconds.'],
        ['ini' => 'CR', 'fa' => 'کریستینا — لیسبون',   'en' => 'Cristina · Lisbon',    'faLine' => 'حالا همهٔ تیم هر روز صبحش را می‌خواند.', 'enLine' => 'The whole team reads it every morning now.'],
    ];
    $row2 = [
        ['ini' => 'PH', 'fa' => 'پدرو — هلسینکی',      'en' => 'Pedro · Helsinki',     'faLine' => 'راه‌اندازی SSO یک بعدازظهر تمام شد.', 'enLine' => 'SSO was done in one afternoon.'],
        ['ini' => 'SK', 'fa' => 'سورا — سئول',         'en' => 'Sora · Seoul',         'faLine' => 'کوئری‌های زنده حتی روی دادهٔ امروز سریع‌اند.', 'enLine' => 'Live queries stay fast even on today’s data.'],
        ['ini' => 'NV', 'fa' => 'ناتالی — آمستردام',   'en' => 'Nathalie · Amsterdam', 'faLine' => 'هشدارها دقیقاً همان‌جا می‌رسند که باید.', 'enLine' => 'Alerts land exactly where they should.'],
        ['ini' => 'DM', 'fa' => 'دومنیکو — میلان',     'en' => 'Domenico · Milan',     'faLine' => 'از پنج فریم‌ورک استفاده کردیم؛ API یکی بود.', 'enLine' => 'We used all five SDKs; one API.'],
        ['ini' => 'AB', 'fa' => 'آیرین — بارسلونا',    'en' => 'Irene · Barcelona',    'faLine' => 'صفحهٔ وضعیت دیگر کسی را نگران نمی‌کند.', 'enLine' => 'The status page quieted every worry.'],
        ['ini' => 'FW', 'fa' => 'فین — زوریخ',         'en' => 'Finn · Zurich',        'faLine' => 'صورت‌حساب شفاف، ریل‌های نگهبان دقیق.', 'enLine' => 'A transparent bill and precise guardrails.'],
    ];
    $row3 = [
        ['ini' => 'HG', 'fa' => 'هانا — کپنهاگ',       'en' => 'Hanna · Copenhagen',   'faLine' => 'مهاجرت از ابزار قبلی یک بعدازظهر طول کشید.', 'enLine' => 'Migrating off our old tool took one afternoon.'],
        ['ini' => 'LB', 'fa' => 'لویی — مونترال',      'en' => 'Louis · Montreal',     'faLine' => 'کارت مصرف هر سرویس را جدا می‌بیند.', 'enLine' => 'Every service’s spend shows on its own card.'],
        ['ini' => 'TA', 'fa' => 'تارو — فرانکفورت',    'en' => 'Taro · Frankfurt',     'faLine' => 'لبهٔ تحویل اروپا واقعاً حس می‌شود.', 'enLine' => 'You can feel the European edge.'],
        ['ini' => 'KS', 'fa' => 'کلارا — وین',         'en' => 'Klara · Vienna',       'faLine' => 'گزارش هفتگی را دیگر هیچ‌کس نمی‌نویسد.', 'enLine' => 'Nobody writes the weekly report anymore.'],
        ['ini' => 'OM', 'fa' => 'عمر — دبی',           'en' => 'Omar · Dubai',         'faLine' => 'پشتیبانی در کمتر از یک ساعت جواب می‌دهد.', 'enLine' => 'Support answers in under an hour.'],
        ['ini' => 'SL', 'fa' => 'سیلویا — سائوپائولو', 'en' => 'Sílvia · São Paulo',   'faLine' => 'تیم داده‌مان بالاخره یک ابزار مشترک دارد.', 'enLine' => 'Our data team finally shares one tool.'],
    ];
    $rows = [
        ['id' => 'slow', 'dur' => '38s', 'base' => 'normal', 'quotes' => $row1],
        ['id' => 'fast', 'dur' => '26s', 'base' => 'reverse', 'quotes' => $row2],
        ['id' => 'mid',  'dur' => '32s', 'base' => 'normal', 'quotes' => $row3],
    ];
@endphp

<style>
    .mfxmq-root {
        --mfxmq-ink: #16181d; --mfxmq-ink-soft: #5c6270;
        --mfxmq-line: #d9dde4; --mfxmq-surface: #ffffff; --mfxmq-floor: #f7f8fa;
        --mfxmq-accent: #4f46e5; --mfxmq-accent-2: #22d3ee;
        display: grid; gap: 1.25rem; justify-items: center;
        font-family: var(--nx-font-display, system-ui);
    }
    html[data-theme="dark"] .mfxmq-root {
        --mfxmq-ink: #f2f4f8; --mfxmq-ink-soft: #9aa1b0;
        --mfxmq-line: #2e3243; --mfxmq-surface: #1a1d2b; --mfxmq-floor: #12141d;
        --mfxmq-accent: #8b83ff; --mfxmq-accent-2: #22d3ee;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .mfxmq-root {
            --mfxmq-ink: #f2f4f8; --mfxmq-ink-soft: #9aa1b0;
            --mfxmq-line: #2e3243; --mfxmq-surface: #1a1d2b; --mfxmq-floor: #12141d;
            --mfxmq-accent: #8b83ff; --mfxmq-accent-2: #22d3ee;
        }
    }
    .mfxmq-head { display: grid; gap: .4rem; text-align: center; justify-items: center; }
    .mfxmq-head p { margin: 0; max-inline-size: 56ch; color: var(--nx-text-muted); }
    .mfxmq-toolbar { display: flex; flex-wrap: wrap; gap: .75rem 1.25rem; align-items: center; justify-content: center; }
    .mfxmq-flip {
        display: inline-flex; align-items: center; gap: .5rem; border: 1px solid var(--mfxmq-line);
        border-radius: 999px; padding: .6rem 1.2rem; background: var(--mfxmq-surface);
        color: var(--mfxmq-ink); font: 600 .82rem / 1 var(--nx-font-display, system-ui); cursor: pointer;
        transition: border-color .2s ease, color .2s ease;
    }
    .mfxmq-flip:hover { border-color: var(--mfxmq-accent); color: var(--mfxmq-accent); }
    .mfxmq-flip:focus-visible { outline: 2px solid var(--mfxmq-accent-2); outline-offset: 2px; }
    .mfxmq-flip svg { inline-size: .95rem; block-size: .95rem; transition: rotate .35s cubic-bezier(.34, 1.56, .64, 1); }
    .mfxmq-root[data-rev="yes"] .mfxmq-flip svg { rotate: 180deg; }
    .mfxmq-state { font-size: .78rem; color: var(--mfxmq-ink-soft); }
    .mfxmq-wall {
        display: grid; gap: 1rem; inline-size: 100%;
        border-block: 1px solid var(--mfxmq-line); padding-block: 1.25rem;
        mask-image: linear-gradient(to right, transparent, #000 8%, #000 92%, transparent);
    }
    .mfxmq-row { overflow: clip; }
    .mfxmq-row[data-speed="slow"] { --mfxmq-dur: 38s; }
    .mfxmq-row[data-speed="fast"] { --mfxmq-dur: 26s; }
    .mfxmq-row[data-speed="mid"]  { --mfxmq-dur: 32s; }
    .mfxmq-track {
        display: flex; inline-size: max-content;
        animation: mfxmq-run var(--mfxmq-dur) linear infinite;
    }
    .mfxmq-row:hover .mfxmq-track,
    .mfxmq-row:focus-within .mfxmq-track { animation-play-state: paused; }
    .mfxmq-half { display: flex; gap: 1rem; padding-inline-end: 1rem; }
    .mfxmq-card {
        display: grid; grid-template-columns: auto 1fr; column-gap: .8rem; align-items: center;
        inline-size: min(21rem, 82vw); margin: 0; padding: .85rem 1rem;
        border: 1px solid var(--mfxmq-line); border-radius: 1rem; background: var(--mfxmq-surface);
    }
    .mfxmq-avatar {
        display: grid; place-items: center; inline-size: 2.4rem; aspect-ratio: 1;
        border-radius: 50%; font: 700 .8rem / 1 var(--nx-font-display, system-ui);
        color: #fff; background: linear-gradient(135deg, var(--mfxmq-accent), var(--mfxmq-accent-2));
    }
    .mfxmq-quote { display: grid; gap: .15rem; min-inline-size: 0; }
    .mfxmq-quote blockquote { margin: 0; font-size: .85rem; line-height: 1.7; color: var(--mfxmq-ink); }
    .mfxmq-quote figcaption { font-size: .72rem; color: var(--mfxmq-ink-soft); }
    @keyframes mfxmq-run { to { translate: -50% 0; } }
    @media (prefers-reduced-motion: reduce) {
        .mfxmq-track { animation: none; }
        .mfxmq-flip svg { transition: none; }
    }
</style>

<div class="mfxmq-root" x-data="{ rev: false, dir(base) { return (base === 'reverse') !== this.rev ? 'reverse' : 'normal' } }"
    x-bind:data-rev="rev ? 'yes' : 'no'">
    <section class="pg-box" style="padding: 0; overflow: clip; gap: 1rem">
        <div class="mfxmq-head" style="padding: 1.5rem 1.5rem 0">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('What eighteen teams say about Nova Metrics', 'هجده تیم چه می‌گویند دربارهٔ نوا متریکس') }}</h3>
            <p>{{ $say('Three rows, three speeds, two directions — hover a row to freeze it, press the button and every row turns around on the spot.', 'سه ردیف، سه سرعت، دو جهت — روی هر ردیف بایستید تا همان‌جا ثابت شود و دکمه را بزنید تا هر سه ردیف درجا برگردند.') }}</p>
        </div>

        <div class="mfxmq-toolbar" style="padding-inline: 1.5rem">
            <button type="button" class="mfxmq-flip" x-on:click="rev = !rev">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M4 12h14" /><path d="M13 6l6 6-6 6" />
                </svg>
                {{ $say('Flip every direction', 'تعویض جهت همه') }}
            </button>
            <span class="mfxmq-state" x-text="rev
                ? @js($say('Every row now runs the other way.', 'هر سه ردیف حالا در جهت مخالف می‌روند.'))
                : @js($say('Base directions — press to reverse.', 'جهت‌های پایه — برای برگرداندن بزنید.'))"></span>
        </div>

        <div class="mfxmq-wall" style="margin-inline: 0">
            @foreach ($rows as $row)
                <div class="mfxmq-row" data-speed="{{ $row['id'] }}" data-base="{{ $row['base'] }}">
                    <div class="mfxmq-track" x-bind:style="'animation-direction: ' + dir('{{ $row['base'] }}')">
                        <div class="mfxmq-half">
                            @foreach ($row['quotes'] as $q)
                                <figure class="mfxmq-card">
                                    <span class="mfxmq-avatar" aria-hidden="true">{{ $q['ini'] }}</span>
                                    <div class="mfxmq-quote">
                                        <blockquote>{{ $say($q['enLine'], $q['faLine']) }}</blockquote>
                                        <figcaption>{{ $say($q['en'], $q['fa']) }}</figcaption>
                                    </div>
                                </figure>
                            @endforeach
                        </div>
                        <div class="mfxmq-half" aria-hidden="true">
                            @foreach ($row['quotes'] as $q)
                                <figure class="mfxmq-card" tabindex="-1">
                                    <span class="mfxmq-avatar">{{ $q['ini'] }}</span>
                                    <div class="mfxmq-quote">
                                        <blockquote>{{ $say($q['enLine'], $q['faLine']) }}</blockquote>
                                        <figcaption>{{ $say($q['en'], $q['fa']) }}</figcaption>
                                    </div>
                                </figure>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <p style="margin: 0; padding: 0 1.5rem 1.5rem; text-align: center; font-size: .78rem; color: var(--mfxmq-ink-soft)">
            {{ $say('Edges of the wall fade out so the cards read as passing through, not ending.', 'لبه‌های دیوار محو می‌شوند تا کارت‌ها «در حال عبور» خوانده شوند، نه «تمام‌شده».') }}
        </p>
    </section>
</div>
