{{--
    Lightbox's real scenarios: a travel journal's photo grid (captions,
    counter, zoom, swipe), a product's detail shots in a tight four-column
    strip, and the RTL gallery where next is to the left.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    // Scenes as inline SVG data URIs — no external assets.
    $scene = fn (string $body, int $w = 1200, int $h = 900) => 'data:image/svg+xml,' . rawurlencode(
        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 '.$w.' '.$h.'">' . $body . '</svg>'
    );
    $photos = [
        [
            'src' => $scene('<defs><linearGradient id="s" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#ffb37a"/><stop offset=".6" stop-color="#ff7e6b"/><stop offset="1" stop-color="#5b4b8a"/></linearGradient></defs><rect width="1200" height="900" fill="url(#s)"/><circle cx="820" cy="520" r="110" fill="#ffe2a8"/><rect y="600" width="1200" height="300" fill="#2b3a67"/><path d="M0 640 Q300 600 600 640 T1200 640 V900 H0Z" fill="#1d2a52"/><rect x="180" y="470" width="24" height="170" fill="#1b1b2f"/><path d="M120 470 h140 l-70 -60z" fill="#1b1b2f"/>'),
            'alt' => $say('Sunset over the Persian Gulf with a lighthouse', 'غروب روی خلیج فارس با یک فانوس دریایی'),
            'caption' => $say('Bandar Abbas pier, 19:42 — the lighthouse comes on as the sun touches the water.', 'اسکلهٔ بندرعباس، ۱۹:۴۲ — فانوس وقتی خورشید به آب می‌رسد روشن می‌شود.'),
        ],
        [
            'src' => $scene('<rect width="1200" height="900" fill="#cfe8ff"/><path d="M0 620 L260 300 L470 560 L700 220 L1000 600 L1200 420 V900 H0Z" fill="#6b7fa8"/><path d="M640 300 L700 220 L760 300 L720 290 L700 310 L680 288Z" fill="#fff"/><path d="M0 720 Q600 640 1200 720 V900 H0Z" fill="#3f7d4e"/><circle cx="980" cy="180" r="60" fill="#fff6c9"/>'),
            'alt' => $say('Snow-capped Damavand above green foothills', 'دماوند برف‌پوش بالای دامنه‌های سبز'),
            'caption' => $say('Damavand from the Haraz road. Pinch or double-tap to see the snow line.', 'دماوند از جادهٔ هراز. دو انگشت یا دوبار ضربه بزنید تا خط برف را ببینید.'),
        ],
        [
            'src' => $scene('<rect width="1200" height="900" fill="#1e2a4a"/><g fill="#2ec4b6"><rect x="200" y="260" width="800" height="520" rx="12"/></g><g fill="#0b7a75"><path d="M200 260 Q600 40 1000 260Z"/></g><g fill="#f4d35e">'.collect(range(0, 7))->map(fn ($i) => '<circle cx="'.(260 + $i * 98).'" cy="420" r="26"/>')->join('').'</g><rect x="520" y="560" width="160" height="220" rx="80" fill="#13213f"/>'),
            'alt' => $say('A turquoise-tiled mosque façade at night', 'نمای کاشی فیروزه‌ای یک مسجد در شب'),
            'caption' => $say('Isfahan, Naqsh-e Jahan square after dark.', 'اصفهان، میدان نقش جهان پس از تاریکی.'),
        ],
        [
            'src' => $scene('<rect width="1200" height="900" fill="#f6e7c8"/>'.collect(range(0, 5))->map(fn ($i) => '<rect x="'.(80 + $i * 180).'" y="'.(220 + ($i % 2) * 60).'" width="140" height="'.(420 - ($i % 3) * 50).'" rx="70" fill="'.['#d1495b', '#edae49', '#00798c', '#30638e', '#8f2d56', '#66a182'][$i].'"/>')->join('').'<rect y="760" width="1200" height="140" fill="#c9a66b"/>'),
            'alt' => $say('Rows of spice sacks in a bazaar', 'ردیف کیسه‌های ادویه در بازار'),
            'caption' => $say('Tajrish bazaar: saffron, sumac, turmeric, dried lime.', 'بازار تجریش: زعفران، سماق، زردچوبه، لیمو عمانی.'),
        ],
        [
            'src' => $scene('<rect width="1200" height="900" fill="#0f1b2d"/>'.collect(range(0, 40))->map(fn ($i) => '<circle cx="'.(($i * 137) % 1200).'" cy="'.(($i * 89) % 520).'" r="'.(1 + $i % 3).'" fill="#fff"/>')->join('').'<path d="M0 700 Q300 560 600 680 T1200 640 V900 H0Z" fill="#d9a066"/><path d="M0 780 Q400 700 800 770 T1200 760 V900 H0Z" fill="#b5793f"/>'),
            'alt' => $say('Stars over desert dunes', 'ستاره‌ها بالای تپه‌های شنی'),
            'caption' => $say('Mesr desert, 2 a.m. No moon, no wind.', 'کویر مصر، ساعت ۲ بامداد. نه ماه، نه باد.'),
        ],
        [
            'src' => $scene('<rect width="1200" height="900" fill="#e8f1e4"/><rect x="0" y="560" width="1200" height="340" fill="#7aa95c"/>'.collect(range(0, 9))->map(fn ($i) => '<path d="M'.(40 + $i * 120).' 600 q 40 -'.(140 + ($i % 3) * 40).' 80 0z" fill="#3e7d3a"/>')->join('').'<path d="M0 560 Q300 500 600 540 T1200 520 V560 H0Z" fill="#9cc58a"/>'),
            'alt' => $say('Terraced tea fields in the north', 'مزارع پلکانی چای در شمال'),
            'caption' => $say('Lahijan tea gardens in late spring.', 'باغ‌های چای لاهیجان در اواخر بهار.'),
        ],
    ];

    $labels = [
        'label' => $say('Travel journal', 'سفرنامه'),
        'zoom-in-label' => $say('Zoom in', 'بزرگ‌نمایی'),
        'zoom-out-label' => $say('Zoom out', 'کوچک‌نمایی'),
        'open-label' => $say('{alt}, image {index} of {total}', '{alt}، تصویر {index} از {total}'),
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A travel journal', 'یک سفرنامه') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Open a photo: the thumbnail itself grows into the viewer (a View Transition in Chromium and Safari, a FLIP elsewhere). Scroll or pinch to zoom around the pointer, double-tap to toggle, drag to pan; at 1× swipe sideways for the next one or down to close. Keyboard: arrows, +, −, 0, Escape.', 'عکسی را باز کنید: خودِ تصویر کوچک به نمایشگر تبدیل می‌شود (View Transition در کرومیوم و سافاری، FLIP جاهای دیگر). با چرخ یا دو انگشت دور نشانگر زوم کنید، دوبار ضربه برای رفت‌وبرگشت، کشیدن برای جابه‌جایی؛ در اندازهٔ ۱× به پهلو بکشید برای بعدی یا به پایین برای بستن. کیبورد: جهت‌ها، +، −، ۰، Escape.') }}
        </p>
    </div>
    <x-nx::lightbox :images="$photos" :columns="3" :label="$labels['label']" :zoom-in-label="$labels['zoom-in-label']" :zoom-out-label="$labels['zoom-out-label']" :open-label="$labels['open-label']" />
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Product detail shots', 'عکس‌های جزئیات محصول') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('A tight square strip under a product title — the same viewer, four columns.', 'نوار مربعی جمع‌وجور زیر عنوان محصول — همان نمایشگر، چهار ستون.') }}
        </p>
        <x-nx::lightbox :images="array_slice($photos, 2, 4)" :columns="4" ratio="1" :label="$say('Product photos', 'عکس‌های محصول')" :zoom-in-label="$labels['zoom-in-label']" :zoom-out-label="$labels['zoom-out-label']" :open-label="$labels['open-label']" />
    </section>

    <section class="pg-box" dir="rtl" lang="fa" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Right to left', 'راست‌به‌چپ') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('Here “next” is to the left: the arrow keys and the swipe both follow the reading direction, and the counter speaks Persian.', 'این‌جا «بعدی» در سمت چپ است: کلیدهای جهت و کشیدن هر دو از جهت خواندن پیروی می‌کنند و شمارنده فارسی است.') }}
        </p>
        <x-nx::lightbox :images="array_slice($photos, 0, 3)" :columns="3" label="گالری" zoom-in-label="بزرگ‌نمایی" zoom-out-label="کوچک‌نمایی" open-label="{alt}، تصویر {index} از {total}" />
    </section>
</div>
