<?php

/**
 * Demo manifest of the "charts-plus" group. Scenarios live at
 * resources/views/demos/components/charts-plus/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'نمودارهای پیشرفته', 'en' => 'Charts plus'],

    'stacked-area' => [
        'title' => ['fa' => 'نمودار ناحیه‌ای انباشته', 'en' => 'Stacked area'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'لایه‌ها روی هم انباشته می‌شوند — مطلق، درصدی (۱۰۰٪) یا جریانی دور صفر؛ منحنی نرم، خطی یا پله‌ای، پرشدگی گرادیان، توپُر، هاشور یا دوتُن. دکمه‌های راهنما سری‌ها را خاموش/روشن می‌کنند و انباشته با انیمیشن دوباره چیده می‌شود.',
            'en' => 'Layers piled up — absolute, percent (100%) or a stream centred on zero; smooth, linear or step curves; gradient, solid, hatched or duotone fills. Legend buttons switch series on and off and the stack re-piles with a tween.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'labels / series', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'برچسب‌های محور افقی و سری‌ها: [[\'name\' => …, \'values\' => […]], …]؛ مقدار منفی صفر حساب می‌شود.',
                'en' => 'The x labels and the series: [[\'name\' => …, \'values\' => […]], …]; negatives count as zero.',
            ]],
            ['name' => 'mode', 'type' => 'string', 'default' => 'stacked', 'note' => [
                'fa' => 'stacked (مطلق) · percent (هر ستون ۱۰۰٪) · expanded (جریانی، متقارن دور صفر).',
                'en' => 'stacked (absolute) · percent (each column = 100%) · expanded (a stream, symmetric around zero).',
            ]],
            ['name' => 'curve / fill', 'type' => 'string', 'default' => 'smooth / gradient', 'note' => [
                'fa' => 'منحنی: smooth | linear | step. پرشدگی: gradient | solid | hatched | duotone (الگوی SVG).',
                'en' => 'Curve: smooth | linear | step. Fill: gradient | solid | hatched | duotone (SVG patterns).',
            ]],
            ['name' => 'hidden-series', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'نام سری‌هایی که خاموش شروع می‌شوند.',
                'en' => 'Names of series that start switched off.',
            ]],
            ['name' => 'ping / markers', 'type' => 'bool', 'default' => 'true / false', 'note' => [
                'fa' => 'نقطهٔ تپنده روی آخرین مقدار؛ برچسب بیشینه و کمینهٔ جمع ستون‌ها.',
                'en' => 'A ping on the latest value; high / low markers on the column totals.',
            ]],
            ['name' => 'height / format / locale', 'type' => 'int / array / string', 'default' => '280 / null / app', 'note' => [
                'fa' => 'بلندی نمودار، گزینه‌های Intl.NumberFormat و زبان ارقام.',
                'en' => 'Plot height, Intl.NumberFormat options and the digits’ locale.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::stacked-area title="ترافیک به تفکیک کانال" :labels="$months" :series="[
            ['name' => 'جست‌وجو', 'values' => $search],
            ['name' => 'مستقیم', 'values' => $direct],
            ['name' => 'شبکه‌های اجتماعی', 'values' => $social],
        ]" mode="percent" fill="hatched" markers />
        BLADE,
    ],

    'stacked-bar' => [
        'title' => ['fa' => 'نمودار میله‌ای انباشته', 'en' => 'Stacked bar'],
        'icon' => 'chart',
        'oneLiner' => [
            'fa' => 'میله‌های انباشته، درصدی یا گروهی؛ عمودی یا افقی (افقی‌ها در راست‌به‌چپ از راست رشد می‌کنند). فقط بالاترین قطعه گرد است، جمع هر ستون روی آن می‌نشیند و راهنما سری‌ها را با انیمیشن کم و زیاد می‌کند.',
            'en' => 'Stacked, percent or grouped bars, vertical or horizontal (horizontal ones grow from the right in RTL). Only the top segment is rounded, totals sit on each stack and the legend adds or removes series with a tween.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'labels / series', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'دسته‌ها و سری‌ها مثل بقیهٔ نمودارها.',
                'en' => 'Categories and series, like the other charts.',
            ]],
            ['name' => 'mode', 'type' => 'string', 'default' => 'stacked', 'note' => [
                'fa' => 'stacked · percent (هر میله ۱۰۰٪) · grouped (کنار هم).',
                'en' => 'stacked · percent (each bar = 100%) · grouped (side by side).',
            ]],
            ['name' => 'orientation', 'type' => 'string', 'default' => 'vertical', 'note' => [
                'fa' => 'vertical | horizontal — افقی نام دسته‌ها را در حاشیه می‌گذارد و با جهت صفحه آینه می‌شود.',
                'en' => 'vertical | horizontal — horizontal puts the names in a gutter and mirrors with the page direction.',
            ]],
            ['name' => 'fill / totals', 'type' => 'string / bool', 'default' => 'solid / false', 'note' => [
                'fa' => 'پرشدگی (solid | gradient | hatched | duotone) و برچسب جمع روی هر میله.',
                'en' => 'The fill (solid | gradient | hatched | duotone) and a total label on each stack.',
            ]],
            ['name' => 'hidden-series / height', 'type' => 'array / int', 'default' => '[] / auto', 'note' => [
                'fa' => 'سری‌های خاموش در شروع و بلندی (افقی: به تعداد دسته‌ها).',
                'en' => 'Series off at start, and the height (horizontal: grows with the categories).',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::stacked-bar title="تیکت‌ها به تفکیک تیم" orientation="horizontal" mode="percent" totals
            :labels="$teams" :series="[
                ['name' => 'حل‌شده', 'values' => $solved],
                ['name' => 'در انتظار', 'values' => $pending],
                ['name' => 'ارجاع‌شده', 'values' => $escalated],
            ]" />
        BLADE,
    ],

    'composed-chart' => [
        'title' => ['fa' => 'نمودار ترکیبی', 'en' => 'Composed chart'],
        'icon' => 'chart',
        'oneLiner' => [
            'fa' => 'میله و خط روی یک نمودار با دو محور عمودی: محور میله‌ها سمت آغاز (در راست‌به‌چپ سمت راست) و محور خط روبه‌رویش؛ یک نوار ابزار شیشه‌ای هر دو را با هم می‌خواند.',
            'en' => 'Bars and a line on one plot with two value axes: the bars’ axis at inline-start (the right in RTL) and the line’s facing it; one frosted tooltip reads both.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'labels / bars / lines', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'میله‌ها روی محور اصلی، خط‌ها روی محور دوم؛ رنگ خط‌ها بعد از میله‌ها ادامه می‌یابد.',
                'en' => 'Bars on the primary axis, lines on the secondary; line colours continue after the bars.',
            ]],
            ['name' => 'bar-axis / line-axis', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'عنوان کوچک بالای هر محور.',
                'en' => 'A small title over each axis.',
            ]],
            ['name' => 'format / line-format', 'type' => 'array', 'default' => 'null', 'note' => [
                'fa' => 'Intl.NumberFormat هر محور — مثلاً [\'style\' => \'percent\'] برای نرخ تبدیل.',
                'en' => 'Intl.NumberFormat per axis — e.g. [\'style\' => \'percent\'] for a conversion rate.',
            ]],
            ['name' => 'markers / ping / dir', 'type' => 'bool / bool / string', 'default' => 'false / true / auto', 'note' => [
                'fa' => 'بیشینه و کمینهٔ خط اول، تپش روی آخرین نقطه و جهت اجباری.',
                'en' => 'High / low of the first line, the ping on its last point, and a forced direction.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::composed-chart title="درآمد و نرخ تبدیل" :labels="$months"
            :bars="[['name' => 'درآمد', 'values' => $revenue]]"
            :lines="[['name' => 'نرخ تبدیل', 'values' => $conversion]]"
            bar-axis="میلیون تومان" line-axis="٪" :line-format="['style' => 'percent', 'maximumFractionDigits' => 1]" markers />
        BLADE,
    ],

    'brush-chart' => [
        'title' => ['fa' => 'نمودار با انتخاب بازه', 'en' => 'Brush chart'],
        'icon' => 'sliders',
        'oneLiner' => [
            'fa' => 'نمودار خطی/ناحیه‌ای با نوار انتخاب بازه زیرش: پنجره را بکشید تا جابه‌جا شود، لبه‌ها را بکشید تا اندازه بگیرد (ورودی بومی range، کیبورد کامل)؛ نمودار بالا روی همان بازه زوم می‌کند و رویداد nx-range می‌فرستد.',
            'en' => 'A line / area chart with a range brush under it: drag the window to pan, drag the edges to resize (native range inputs, full keyboard); the main plot zooms to the range and dispatches nx-range.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'labels / series', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'کل داده؛ نمودار بالا فقط بازهٔ انتخاب‌شده را نشان می‌دهد.',
                'en' => 'All the data; the main plot shows only the selected range.',
            ]],
            ['name' => 'range / min-span', 'type' => 'array / int', 'default' => 'last third / 2', 'note' => [
                'fa' => 'بازهٔ آغازین [شروع، پایان] (اندیس) و کمترین پهنای پنجره.',
                'en' => 'The starting [start, end] index range and the narrowest window.',
            ]],
            ['name' => 'variant / curve', 'type' => 'string', 'default' => 'area / smooth', 'note' => [
                'fa' => 'area | line و منحنی smooth | linear | step.',
                'en' => 'area | line, and the curve smooth | linear | step.',
            ]],
            ['name' => 'x-on:nx-range', 'type' => 'event', 'default' => '—', 'note' => [
                'fa' => 'هر تغییر: detail = { range, from, to } — مثلاً $wire.set(\'range\', $event.detail.range).',
                'en' => 'Every change: detail = { range, from, to } — e.g. $wire.set(\'range\', $event.detail.range).',
            ]],
            ['name' => 'height / brush-height / markers', 'type' => 'int / int / bool', 'default' => '240 / 56 / true', 'note' => [
                'fa' => 'بلندی نمودار اصلی و نوار، و برچسب بیشینه/کمینهٔ بازه.',
                'en' => 'The main plot and strip heights, and high / low markers of the range.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::brush-chart title="کاربران فعال روزانه" :labels="$days"
            :series="[['name' => 'کاربر فعال', 'values' => $dau]]"
            :range="[60, 89]" min-span="6"
            x-on:nx-range="$wire.set('range', $event.detail.range)" />
        BLADE,
    ],

    'forecast-line' => [
        'title' => ['fa' => 'نمودار پیش‌بینی', 'en' => 'Forecast line'],
        'icon' => 'trend-up',
        'oneLiner' => [
            'fa' => 'خط واقعی و ادامهٔ خط‌چینِ پیش‌بینی (با خط‌چین متحرک اختیاری)، نوار اطمینان، خط «امروز» با نقطهٔ تپنده روی آخرین مقدار واقعی و برچسب بیشینه و کمینه.',
            'en' => 'The actual line and its dashed forecast (optionally marching), a confidence band, a “today” line with a ping on the last actual value, and high / low markers.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'labels', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'برچسب همهٔ نقطه‌ها: اول واقعی‌ها، بعد پیش‌بینی‌ها.',
                'en' => 'Every point’s label: the actual ones first, then the forecast.',
            ]],
            ['name' => 'actual / forecast', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'مقدارهای واقعی و مقدارهای بعد از آخرین واقعی.',
                'en' => 'The actual values and the ones after the last actual.',
            ]],
            ['name' => 'lower / upper', 'type' => 'array', 'default' => 'null', 'note' => [
                'fa' => 'کرانه‌های نوار اطمینان، هم‌طول forecast.',
                'en' => 'The confidence band’s bounds, as long as forecast.',
            ]],
            ['name' => 'animated / today-label', 'type' => 'bool / string', 'default' => 'false / «امروز»', 'note' => [
                'fa' => 'خط‌چین متحرک (زیر کاهش حرکت می‌ایستد) و برچسب خط امروز ( \'\' پنهانش می‌کند).',
                'en' => 'Marching dashes (still under reduced motion) and the today pill (\'\' hides it).',
            ]],
            ['name' => 'markers / ping', 'type' => 'bool', 'default' => 'true / true', 'note' => [
                'fa' => 'برچسب بیشینه/کمینه و نقطهٔ تپنده.',
                'en' => 'High / low markers and the ping.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::forecast-line title="فروش ماهانه" :labels="$months"
            :actual="$sales" :forecast="$projection"
            :lower="$low" :upper="$high" animated />
        BLADE,
    ],

    'radar-chart' => [
        'title' => ['fa' => 'نمودار راداری', 'en' => 'Radar chart'],
        'icon' => 'globe',
        'oneLiner' => [
            'fa' => 'نمودار عنکبوتی با چند سری روی پره‌های مشترک: حلقه‌های شبکه، شکل‌ها با فنر از مرکز بیرون می‌آیند، کلیدهای جهت‌نما پره‌ها را یکی‌یکی می‌خوانند و در راست‌به‌چپ پره‌ها پادساعت‌گرد می‌چرخند.',
            'en' => 'A spider chart with several series over shared spokes: grid rings, shapes spring out from the centre, arrow keys read the spokes one by one, and in RTL the spokes run counter-clockwise.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'axes / series', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'نام هر پره و سری‌ها (یک مقدار برای هر پره).',
                'en' => 'One name per spoke, and the series (one value per spoke).',
            ]],
            ['name' => 'max / rings', 'type' => 'number / int', 'default' => 'auto / 4', 'note' => [
                'fa' => 'مقدار لبهٔ بیرونی (پیش‌فرض: عدد گرد بالای داده) و تعداد حلقه‌ها.',
                'en' => 'The rim’s value (a round number above the data by default) and the ring count.',
            ]],
            ['name' => 'size / hidden-series / dir', 'type' => 'int / array / string', 'default' => '340 / [] / auto', 'note' => [
                'fa' => 'اندازهٔ ترسیم، سری‌های خاموش و جهت اجباری.',
                'en' => 'Drawing size, series off at start and a forced direction.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::radar-chart title="کارنامهٔ مدل‌ها" :max="100" rings="5"
            :axes="['سرعت', 'دقت', 'هزینه', 'فارسی', 'کدنویسی', 'ایمنی']"
            :series="[
                ['name' => 'نسخهٔ ۲', 'values' => [88, 92, 64, 95, 81, 90]],
                ['name' => 'نسخهٔ ۱', 'values' => [72, 80, 78, 70, 66, 84]],
            ]" />
        BLADE,
    ],

    'radial-bar' => [
        'title' => ['fa' => 'حلقه‌های پیشرفت', 'en' => 'Radial bar'],
        'icon' => 'sun',
        'oneLiner' => [
            'fa' => 'چند حلقهٔ هم‌مرکز پیشرفت که یکی‌یکی جارو می‌شوند، هرکدام سهمی از سقف خودش؛ فهرست کنارش برچسب و مقدار را می‌گوید و مرکز میانگین یا حلقهٔ زیر نشانگر را.',
            'en' => 'Concentric progress rings that sweep in one after another, each a share of its own max; the list beside it names label and value, the centre the average or the hovered ring.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'data', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر مورد: [\'label\' => …, \'value\' => …, \'max\' => …, \'hint\' => …] (max و hint اختیاری).',
                'en' => 'Each item: [\'label\' => …, \'value\' => …, \'max\' => …, \'hint\' => …] (max and hint optional).',
            ]],
            ['name' => 'max / sweep', 'type' => 'number', 'default' => '100 / 270', 'note' => [
                'fa' => 'سقف پیش‌فرض حلقه‌ها و درجهٔ قوس (۳۶۰ حلقهٔ کامل).',
                'en' => 'The rings’ default max and the arc’s degrees (360 closes the ring).',
            ]],
            ['name' => 'center-value / center-label / size', 'type' => 'string', 'default' => 'average / «سهم» / 13rem', 'note' => [
                'fa' => 'متن مرکز و اندازهٔ صفحه.',
                'en' => 'The centre’s reading and the dial’s size.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::radial-bar title="اهداف فصل" :data="[
            ['label' => 'درآمد', 'value' => 82],
            ['label' => 'کاربر تازه', 'value' => 1240, 'max' => 2000, 'hint' => 'هدف: ۲٬۰۰۰'],
            ['label' => 'رضایت', 'value' => 4.6, 'max' => 5],
        ]" />
        BLADE,
    ],

    'chart-loading' => [
        'title' => ['fa' => 'اسکلت بارگذاری نمودار', 'en' => 'Chart loading'],
        'icon' => 'zap',
        'oneLiner' => [
            'fa' => 'اسکلت نمودار در حال بارگذاری — خطی، ناحیه‌ای، میله‌ای یا دونات — با درخششی که به سمت پایان خط می‌رود (زیر کاهش حرکت یک تپش آرام)؛ ناحیهٔ وضعیت مؤدبانه‌ای که «در حال بارگذاری نمودار» را می‌گوید. بدون جاوااسکریپت.',
            'en' => 'A loading skeleton for charts — line, area, bar or donut — with a shimmer sweeping toward inline-end (a slow pulse under reduced motion); a polite status region that says “Loading chart”. No JavaScript.',
        ],
        'js' => false,
        'props' => [
            ['name' => 'variant', 'type' => 'string', 'default' => 'line', 'note' => [
                'fa' => 'line | area | bar | donut.',
                'en' => 'line | area | bar | donut.',
            ]],
            ['name' => 'height / title / legend', 'type' => 'string / string / int', 'default' => '15rem / null / 2', 'note' => [
                'fa' => 'بلندی ناحیهٔ نمودار، عنوان واقعی به‌جای استخوان و تعداد استخوان‌های راهنما.',
                'en' => 'The plot height, a real title instead of a bone, and the legend bones.',
            ]],
            ['name' => 'label', 'type' => 'string', 'default' => '«در حال بارگذاری نمودار»', 'note' => [
                'fa' => 'متنی که صفحه‌خوان می‌خواند.',
                'en' => 'What screen readers announce.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div wire:loading.remove wire:target="period">
            <x-nx::stacked-area … />
        </div>
        <x-nx::chart-loading variant="area" wire:loading wire:target="period" />
        BLADE,
    ],
];
