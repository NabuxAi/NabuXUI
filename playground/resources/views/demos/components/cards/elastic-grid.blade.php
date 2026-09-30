{{--
    The elastic grid's real scenarios: a travel destinations gallery, then a
    two-column product wall with taller cells.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $art = [
        'lapis' => 'radial-gradient(120% 90% at 12% 0%, var(--nx-lapis-300), transparent 58%), linear-gradient(140deg, var(--nx-lapis-600), var(--nx-violet-700))',
        'violet' => 'radial-gradient(110% 80% at 90% 10%, var(--nx-violet-300), transparent 55%), linear-gradient(160deg, var(--nx-violet-600), var(--nx-lapis-950))',
        'cyan' => 'radial-gradient(100% 80% at 15% 15%, var(--nx-cyan-300), transparent 58%), linear-gradient(200deg, var(--nx-cyan-600), var(--nx-lapis-800))',
        'gold' => 'radial-gradient(100% 90% at 80% 100%, var(--nx-gold-300), transparent 60%), linear-gradient(160deg, var(--nx-gold-500), var(--nx-violet-700))',
        'green' => 'radial-gradient(90% 80% at 80% 20%, color-mix(in oklab, var(--nx-chart-7) 55%, var(--nx-ink-50)), transparent 60%), linear-gradient(170deg, var(--nx-chart-7), var(--nx-cyan-700))',
        'rose' => 'radial-gradient(100% 80% at 20% 0%, color-mix(in oklab, var(--nx-chart-2) 60%, var(--nx-ink-50)), transparent 60%), linear-gradient(150deg, var(--nx-chart-2), var(--nx-violet-700))',
        'ink' => 'radial-gradient(90% 70% at 50% 0%, var(--nx-lapis-700), transparent 70%), linear-gradient(180deg, var(--nx-ink-800), var(--nx-ink-950))',
    ];

    $cities = [
        ['cover' => $art['lapis'], 'title' => $say('Tehran', 'تهران'), 'caption' => $say('Teheran', 'دربست البرز'), 'href' => '#'],
        ['cover' => $art['gold'], 'title' => $say('Marrakech', 'مراکش'), 'caption' => $say('مراكش', 'سرای سُقّاها')],
        ['cover' => $art['cyan'], 'title' => $say('Reykjavík', 'ریکیاویک')],
        ['cover' => $art['rose'], 'title' => $say('Ciudad de México', 'مکزیکوسیتی'), 'caption' => 'CDMX'],
        ['cover' => $art['violet'], 'title' => $say('Seoul', 'سئول'), 'caption' => '서울'],
        ['cover' => $art['green'], 'title' => $say('Nairobi', 'نایروبی')],
        ['cover' => $art['ink'], 'title' => $say('Berlin', 'برلین'), 'caption' => 'Kreuzberg'],
        ['cover' => $art['lapis'], 'title' => $say('Kyoto', 'کیوتو'), 'caption' => '京都'],
        ['cover' => $art['gold'], 'title' => $say('São Paulo', 'سائوپائولو')],
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Where our readers went', 'آن‌جا که خوانندگان‌مان رفتند') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Nine destinations, three columns — scroll and watch each column trail the page on its own spring, stretch, and settle. Cards with href are real links.', 'نه مقصد، سه ستون — اسکرول کنید و ببینید هر ستون با فنر خودش دنبال صفحه می‌آید، کشیده می‌شود و جا می‌افتد. کارت‌های دارای href لینک واقعی‌اند.') }}
        </p>
    </div>
    <x-nx::elastic-grid :columns="3" :items="$cities" />
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A product wall, taller cells', 'دیوار محصول، خانه‌های بلندتر') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Two columns with a heavier lag and portrait ratio — the same block feels like a catalogue instead of a gallery.', 'دو ستون با تأخیر بیشتر و نسبت عمودی — همان بلوک به‌جای گالری، حس کاتالوگ می‌دهد.') }}
        </p>
    </div>
    <x-nx::elastic-grid :columns="2" :lag="140" :parallax="60" :items="[
        ['cover' => $art['cyan'], 'title' => $say('Field jacket', 'کاپشن میدانی'), 'caption' => $say('3-season · waxed', 'سه‌فصل · واکس‌خورده'), 'ratio' => '4 / 5', 'href' => '#'],
        ['cover' => $art['rose'], 'title' => $say('Canvas tote', 'کیف بوم', ), 'caption' => $say('18 oz · grows old well', '۸۵۰ گرم · با سن خوش می‌شود'), 'ratio' => '4 / 5', 'href' => '#'],
        ['cover' => $art['gold'], 'title' => $say('Wool beanie', 'کلاه پشمی'), 'caption' => $say('Merino · one size', 'مرینوس · یک سایز'), 'ratio' => '4 / 5'],
        ['cover' => $art['lapis'], 'title' => $say('Notebook', 'دفترچه'), 'caption' => $say('Dot grid · 192 pages', 'نقطه‌چین · ۱۹۲ صفحه'), 'ratio' => '4 / 5'],
    ]" />
</section>
