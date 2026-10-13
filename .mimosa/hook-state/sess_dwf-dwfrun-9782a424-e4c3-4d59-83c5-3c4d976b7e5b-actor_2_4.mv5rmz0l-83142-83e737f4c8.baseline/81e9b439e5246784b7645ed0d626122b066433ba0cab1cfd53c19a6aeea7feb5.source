<?php

/**
 * Demo manifest of the "data" group — tables, charts, boards and everything
 * that shows live information. The file name is the group id, every key below
 * is a demo slug and its partial lives at
 * resources/views/demos/components/data/{slug}.blade.php. See
 * App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'داده', 'en' => 'Data'],

    'multi-select' => [
        'title' => ['fa' => 'چندانتخابی', 'en' => 'Multi-select'],
        'icon' => 'search',
        'oneLiner' => [
            'fa' => 'چندانتخابی جست‌وجوپذیر با توضیح هر گزینه و سقف انتخاب؛ چیپ‌ها دستِ Alpine هستند و مقدار آرایه‌ای از طریق x-modelable به Livewire می‌رسد.',
            'en' => 'A searchable multi-select with per-option descriptions and a selection cap; Alpine owns the chips while the array reaches Livewire through x-modelable.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'options', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'به‌شکل [\'en\' => \'English\'] یا فهرستی از [\'value\', \'label\', \'icon\', \'description\', \'disabled\'].',
                'en' => 'Either [\'en\' => \'English\'] or a list of [\'value\', \'label\', \'icon\', \'description\', \'disabled\'].',
            ]],
            ['name' => 'wire:model', 'type' => '—', 'default' => 'null', 'note' => [
                'fa' => 'روی خود کامپوننت می‌نشیند و آرایهٔ مقدارهای انتخابی را مقید می‌کند (Livewire 3 و 4).',
                'en' => 'Sits on the component itself and binds the array of chosen values (Livewire 3 and 4).',
            ]],
            ['name' => 'name', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'در فرم ساده هر مقدار را به‌صورت name[] پست می‌کند.',
                'en' => 'In a plain form it posts every value as name[].',
            ]],
            ['name' => 'max', 'type' => 'int', 'default' => 'null', 'note' => [
                'fa' => 'سقف انتخاب؛ پس از آن گزینه‌های دیگر غیرفعال می‌شوند.',
                'en' => 'The selection cap; beyond it the other options disable.',
            ]],
            ['name' => 'placeholder / searchPlaceholder / emptyText', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'متن‌های جای‌بان، جست‌وجو و نتیجهٔ خالی.',
                'en' => 'The placeholder, search and empty-result texts.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::multi-select label="زبان‌های پاسخگویی" :options="$languages" :max="3"
            wire:model.live="state.networks" />
        BLADE,
    ],

    'status-badge' => [
        'title' => ['fa' => 'نشان وضعیت', 'en' => 'Status badge'],
        'icon' => 'info',
        'oneLiner' => [
            'fa' => 'قرص وضعیت زنده که از data-status تبعیت می‌کند: با re-render لایووایر (یا x-bind خودتان) عرضش با برچسب می‌ایستد و آیکون عوض می‌شود.',
            'en' => 'A live status pill that follows its data-status: on a Livewire re-render (or your own x-bind) the width settles with the label and the icon swaps.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'status', 'type' => 'string', 'default' => 'queued', 'note' => [
                'fa' => 'running | success | failed | queued | canceled.',
                'en' => 'running | success | failed | queued | canceled.',
            ]],
            ['name' => 'live', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'تغییرها را اعلام می‌کند (role="status") — برای وضعیت‌هایی که خودشان به‌روز می‌شوند.',
                'en' => 'Announces changes (role="status") — for states that update on their own.',
            ]],
            ['name' => 'label / labels', 'type' => 'string / array', 'default' => 'null / []', 'note' => [
                'fa' => 'برچسب یک‌تکه یا فرهنگ لغات وضعیت‌ها برای جایگزینی واژه‌های ساخته‌شده.',
                'en' => 'A single label, or a labels map that overrides the built-in words.',
            ]],
            ['name' => 'size', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'اندازهٔ قرص (مثلاً lg).',
                'en' => 'The pill size (lg, for instance).',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::status-badge :status="$job->status" live />

        <x-nx::status-badge status="queued" x-bind:data-status="s" />
        BLADE,
    ],

    'data-table' => [
        'title' => ['fa' => 'جدول داده', 'en' => 'Data table'],
        'icon' => 'menu',
        'oneLiner' => [
            'fa' => 'جدول با مرتب‌سازی و FLIP ردیف‌ها: سرصفحه‌ها که کلیک شوند ردیف‌ها به جای تازه سُر می‌خورند؛ قالب‌های آمادهٔ عدد، پول، درصد، تاریخ و وضعیت دارد.',
            'en' => 'A table with sorting and row FLIP: click a header and the rows glide to their new places; number, currency, percent, date and status formats are built in.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'rows / columns', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'ستون‌ها: [\'key\' => …, \'label\' => …, \'sortable\' => true, \'format\' => "number|compact|percent|currency:EUR|date|datetime|status"].',
                'en' => 'Columns: [\'key\' => …, \'label\' => …, \'sortable\' => true, \'format\' => "number|compact|percent|currency:EUR|date|datetime|status"].',
            ]],
            ['name' => 'sort / sortAction', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'مرتب‌سازی مرورگری پیش‌فرض است؛ sort-action="sortBy" هر سرصفحه را به $wire.sortBy(key, dir) می‌فرستد و sort="name:ascending" وضعیت جاری را برمی‌گرداند.',
                'en' => 'Sorting runs in the browser by default; sort-action="sortBy" sends each header to $wire.sortBy(key, dir) while sort="name:ascending" returns the current one.',
            ]],
            ['name' => 'rowKey', 'type' => 'string', 'default' => 'id', 'note' => [
                'fa' => 'کلید wire:key ردیف‌ها تا پس از morph هم سرِ جایشان بلغزند.',
                'en' => 'The rows’ wire:key so they keep gliding after a morph.',
            ]],
            ['name' => 'caption / captionHidden', 'type' => 'string / bool', 'default' => 'null / false', 'note' => [
                'fa' => 'عنوان جدول برای صفحه‌خوان‌ها (و اختیاریاً دیده‌شودن آن).',
                'en' => 'The table’s caption for screen readers (and optionally visible).',
            ]],
            ['name' => 'maxHeight / density / emptyText', 'type' => 'string / string / string', 'default' => 'null', 'note' => [
                'fa' => 'ارتفاع اسکرول‌شونده، فشردگی ردیف‌ها و متن حالت خالی.',
                'en' => 'The scrollable height, the row density and the empty-state text.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::data-table caption="پروژه‌ها" sort="budget:descending" :rows="$projects" :columns="[
            ['key' => 'name', 'label' => 'پروژه', 'sortable' => true],
            ['key' => 'status', 'label' => 'وضعیت', 'format' => 'status', 'sortable' => true],
            ['key' => 'budget', 'label' => 'بودجه', 'format' => 'currency:USD', 'sortable' => true],
        ]" />
        BLADE,
    ],

    'heatmap' => [
        'title' => ['fa' => 'نقشهٔ حرارتی', 'en' => 'Heatmap'],
        'icon' => 'chart',
        'oneLiner' => [
            'fa' => 'تقویم مشارکت — یا هر ماتریس عددی؛ سلول‌ها یکی‌یکی روشن می‌شوند، برچسب ماه‌ها و تاریخ‌ها از تقویمِ زبان برنامه می‌آیند و همان داده به‌صورت جدول پنهان هم می‌رود.',
            'en' => 'A contribution calendar — or any numeric matrix; the cells light one by one, month and date labels follow the app locale’s calendar, and the same data ships as a hidden table.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'data', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'تقویمی: [[\'date\' => \'2026-09-01\', \'value\' => 4], …] — یا ماتریسی: ردیف‌هایی از عدد خالص.',
                'en' => 'Calendar-style: [[\'date\' => \'2026-09-01\', \'value\' => 4], …] — or a matrix: rows of plain numbers.',
            ]],
            ['name' => 'labels', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'در حالت ماتریسی: [\'rows\' => […], \'columns\' => […]].',
                'en' => 'For the matrix mode: [\'rows\' => […], \'columns\' => […]].',
            ]],
            ['name' => 'weeks / weekStart / end', 'type' => 'int', 'default' => 'null / 0 / null', 'note' => [
                'fa' => 'تعداد هفته‌ها، روز آغاز هفته (۰ یکشنبه، ۱ دوشنبه، ۶ شنبه) و تاریخ پایانی.',
                'en' => 'The week count, the week’s first day (0 Sunday, 1 Monday, 6 Saturday) and the end date.',
            ]],
            ['name' => 'unit / summary', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'واحد هر سلول (برای توضیح دسترس‌پذیر) و جمع‌بندی زیر عنوان.',
                'en' => 'What one cell counts (for the accessible description) and the summary line under the title.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::heatmap title="گفت‌وگوهای پاسخ‌داده‌شده" unit="گفت‌وگو" :weeks="26" :data="$heat"
            summary="۴٬۲۱۸ گفت‌وگو در ۶ ماه" />
        BLADE,
    ],

    'metric-chart' => [
        'title' => ['fa' => 'نمودار متریک', 'en' => 'Metric chart'],
        'icon' => 'trend-up',
        'oneLiner' => [
            'fa' => 'نمودار با تب دوره‌ها: خط به متریک تازه مورف می‌شود و عدد تیتر می‌غلتد؛ دلتاها رنگ درست را می‌گیرند (و برای متریک‌های معکوس برمی‌گردند).',
            'en' => 'A chart with metric tabs: the line morphs into the new series and the headline rolls; deltas take the right colour (and flip for inverse metrics).',
        ],
        'js' => true,
        'props' => [
            ['name' => 'labels / metrics', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر متریک: [\'id\' => …, \'label\' => …, \'values\' => […], \'delta\' => 12.4, \'format\' => [Intl NumberFormat options], \'invertDelta\' => true] — تعداد مقدارها باید با برچسب‌ها یکی باشد.',
                'en' => 'Each metric: [\'id\' => …, \'label\' => …, \'values\' => […], \'delta\' => 12.4, \'format\' => [Intl NumberFormat options], \'invertDelta\' => true] — as many values as labels.',
            ]],
            ['name' => 'active', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'id متریکِ انتخاب‌شده در آغاز.',
                'en' => 'The metric tab that starts selected.',
            ]],
            ['name' => 'title / subtitle / caption', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'عنوان، زیرعنوان و توضیح کنار عدد تیتر.',
                'en' => 'The title, subtitle and the caption beside the headline.',
            ]],
            ['name' => 'height', 'type' => 'int', 'default' => '200', 'note' => [
                'fa' => 'بلندی ناحیهٔ نمودار.',
                'en' => 'The plot height.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::metric-chart title="سلامت ورک‌اسپیس" caption="نسبت به ماه پیش" :labels="$months" :metrics="[
            ['id' => 'revenue', 'label' => 'درآمد', 'values' => $values, 'delta' => 12.4],
        ]" />
        BLADE,
    ],

    'bar-chart' => [
        'title' => ['fa' => 'نمودار میله‌ای', 'en' => 'Bar chart'],
        'icon' => 'chart',
        'oneLiner' => [
            'fa' => 'نمودار میله‌ای تمام‌CSS: میله‌ها با رسیدن به دید پله‌پله بزرگ می‌شوند، نوارِ هر ستون با هاور و تمرکز کیبورد باز می‌شود و محور مقدار، نردبانِ تیک تمیز با ارقام زبان شماست؛ مقدارهای منفی به خط مبنا می‌رسند و همان داده به‌صورت جدول پنهان هم می‌رود.',
            'en' => 'A CSS-only bar chart: bars grow in steps as the chart scrolls into view, each column’s tooltip opens on hover and keyboard focus, and the value axis is a nice-ticks ladder in your locale’s digits; negatives clamp to the baseline and the same data ships as a hidden table.',
        ],
        'js' => false,
        'props' => [
            ['name' => 'data', 'type' => 'array', 'default' => 'null', 'note' => [
                'fa' => 'تک‌سری: [\'شنبه\' => 132, …] — برچسب‌ها از کلیدها می‌آیند.',
                'en' => 'One series: [\'Mon\' => 132, …] — the labels come from the keys.',
            ]],
            ['name' => 'labels / series', 'type' => 'array', 'default' => '[] / null', 'note' => [
                'fa' => 'چند سری: labels => […], series => [[\'name\' => …, \'values\' => […]]]؛ بدون labels برچسب‌های ۱ تا n ساخته می‌شوند.',
                'en' => 'Several series: labels => […], series => [[\'name\' => …, \'values\' => […]]]; without labels 1..n are generated.',
            ]],
            ['name' => 'stacked', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'سری‌ها به‌جای کنار هم روی هم می‌نشینند و جمعِ ستون‌ها محور را می‌سازد؛ جمع در نوار ابزار هم می‌آید.',
                'en' => 'The series stack instead of sitting side by side, column totals drive the axis and the tooltip adds the total row.',
            ]],
            ['name' => 'title / subtitle', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'عنوان و زیرعنوان کارت؛ با بیش از یک سری راهنمای رنگ‌ها کنارشان می‌نشیند.',
                'en' => 'The card’s title and subtitle; with more than one series the colour legend sits beside them.',
            ]],
            ['name' => 'height', 'type' => 'int / string', 'default' => '240', 'note' => [
                'fa' => 'بلندی ناحیهٔ نمودار — عدد (پیکسل) یا هر طول CSS مثل «16rem».',
                'en' => 'The plot height — a number (pixels) or any CSS length such as “16rem”.',
            ]],
            ['name' => 'locale', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'زبان ارقام مقدارها و تیک‌ها (پیش‌فرض: زبان اپ).',
                'en' => 'The locale for value and tick digits (the app’s by default).',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::bar-chart title="گفت‌وگوهای روزانه" subtitle="هفتهٔ جاری" :data="[
            'شنبه' => 132, 'یکشنبه' => 98, 'دوشنبه' => 121, 'سه‌شنبه' => 144,
        ]" />

        {{-- چند سری، روی هم: جمع هر ستون محور را می‌سازد --}}
        <x-nx::bar-chart :labels="$months" stacked :series="[
            ['name' => 'وب', 'values' => $web],
            ['name' => 'اپ', 'values' => $app],
        ]" />
        BLADE,
    ],

    'analytics-card' => [
        'title' => ['fa' => 'کارت آنالیتیکس', 'en' => 'Analytics card'],
        'icon' => 'users',
        'oneLiner' => [
            'fa' => 'کارت آمار با سوییچ دوره: thumb دوره می‌لغزد، عدد تیتر می‌غلتد و میله‌ها یکی‌یکی به دورهٔ تازه بزرگ/کوچک می‌شوند.',
            'en' => 'A stat card with a period switch: the thumb slides, the headline rolls and the bars grow or shrink into the new period one after another.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'periods', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر دوره: [\'id\' => …, \'label\' => …, \'value\' => …, \'delta\' => …, \'values\' => […], \'labels\' => […], \'caption\' => …].',
                'en' => 'Each period: [\'id\' => …, \'label\' => …, \'value\' => …, \'delta\' => …, \'values\' => […], \'labels\' => […], \'caption\' => …].',
            ]],
            ['name' => 'active', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'id دورهٔ آغازین.',
                'en' => 'The starting period id.',
            ]],
            ['name' => 'format / invertDelta', 'type' => 'array / bool', 'default' => 'null / false', 'note' => [
                'fa' => 'گزینه‌های Intl.NumberFormat برای عددها و معکوس‌خواندن دلتا (کمتر بهتر است).',
                'en' => 'Intl.NumberFormat options for the numbers, and reading a falling delta as good.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::analytics-card title="بازدیدها" active="7d" :periods="$periods" />
        BLADE,
    ],

    'dot-matrix-chart' => [
        'title' => ['fa' => 'نمودار نقطه‌ماتریسی', 'en' => 'Dot matrix chart'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'هر ستون پشته‌ای از نقطه‌هاست که تا مقدار روشن می‌شوند؛ سری‌ها با ترتیب ثابت رنگ روی هم می‌نشینند و یک نقطه = واحدی گرد که به بلندترین ستون می‌خورد.',
            'en' => 'Each column is a stack of dots lit up to its total; series stack in fixed colour order, and one dot is a round unit that fits the tallest column.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'data / series + labels', 'type' => 'array', 'default' => 'null / []', 'note' => [
                'fa' => 'تک‌سری: data => [\'دوشنبه\' => 42, …]؛ چندسری: labels => […], series => [[\'name\' => …, \'values\' => […]]].',
                'en' => 'One series: data => [\'Mon\' => 42, …]; several: labels => […], series => [[\'name\' => …, \'values\' => […]]].',
            ]],
            ['name' => 'rows / unit', 'type' => 'int', 'default' => '10 / null', 'note' => [
                'fa' => 'نقطه‌های هر ستون و ارزش هر نقطه (پیش‌فرض: عددی گرد که در بلندترین ستون جا شود).',
                'en' => 'Dots per column and what one dot counts (default: a round number that fits the tallest column).',
            ]],
            ['name' => 'title / subtitle', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'عنوان و زیرعنوان کارت.',
                'en' => 'The card’s title and subtitle.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::dot-matrix-chart title="تیکت‌ها به تفکیک زبان" :labels="$codes" :series="[
            ['name' => 'حل‌شده توسط ایجنت', 'values' => $solved],
            ['name' => 'واگذار به انسان', 'values' => $handed],
        ]" />
        BLADE,
    ],

    'donut-chart' => [
        'title' => ['fa' => 'نمودار دونات', 'en' => 'Donut chart'],
        'icon' => 'chart',
        'oneLiner' => [
            'fa' => 'دونات خالص CSS: بخش‌ها با ورود به دید یکی‌یکی جارو می‌شوند، عدد مرکزی و درصدهای راهنما می‌غلتند و همان داده به‌صورت جدولی پنهان به صفحه‌خوان‌ها می‌رسد.',
            'en' => 'A pure-CSS donut: the slices sweep in one after another as the chart enters view, the centre number and the legend percents roll, and the same data ships as a visually-hidden table for screen readers.',
        ],
        'js' => false,
        'props' => [
            ['name' => 'data', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => '[\'برچسب\' => مقدار, …] — سهم هر بخش از جمع محاسبه می‌شود و رنگ‌ها به ترتیبِ ثابت --nx-chart-1…7 می‌نشینند.',
                'en' => '[\'Label\' => value, …] — each slice’s share is computed from the sum, and colours land in the fixed --nx-chart-1…7 order.',
            ]],
            ['name' => 'title / subtitle', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'عنوان و زیرعنوان کارت؛ عنوان به‌عنوان نام دسترس‌پذیر نمودار هم می‌نشیند.',
                'en' => 'The card’s title and subtitle; the title also names the chart for screen readers.',
            ]],
            ['name' => 'centerValue / centerLabel', 'type' => 'number / string', 'default' => 'null', 'note' => [
                'fa' => 'عدد چاه مرکزی (پیش‌فرض: جمع داده‌ها) و برچسب زیرش (پیش‌فرض «جمع» از i18n هسته؛ رشتهٔ خالی برچسب را پنهان می‌کند).',
                'en' => 'The number in the hole (the data’s sum by default) and its caption (the core i18n word for “Total” by default; an empty string hides it).',
            ]],
            ['name' => 'size / thickness', 'type' => 'string | number', 'default' => 'null (12rem / 14%)', 'note' => [
                'fa' => 'قطر حلقه و ضخامت آن؛ عدد یعنی px برای قطر و ٪ شعاع برای ضخامت، و هر طول CSS دیگری هم می‌شود.',
                'en' => 'The ring’s diameter and thickness; a number means px for the diameter and % of the radius for thickness, and any other CSS length works too.',
            ]],
            ['name' => 'locale', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'زبان ارقام، علامت درصد و واژه‌های ساخته‌شده (پیش‌فرض: زبان اپ).',
                'en' => 'The locale for digits, the percent mark and the built-in words (the app’s by default).',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::donut-chart title="گفت‌وگوها به تفکیک کانال" :data="[
            'واتساپ' => 4700, 'تلگرام' => 3300, 'وب' => 1800, 'ایمیل' => 900,
        ]" />

        {{-- ویجت کوچک با برچسب مرکزی دلخواه --}}
        <x-nx::donut-chart title="فضای ورک‌اسپیس" size="9rem" thickness="20%"
            center-label="گیگابایت" :data="$storage" />

        {{-- عدد و برچسب دلخواه در چاه مرکزی، به‌جای جمع --}}
        <x-nx::donut-chart :center-value="1240" center-label="گفت‌وگو" :data="$outcomes" />
        BLADE,
    ],

    'usage-card' => [
        'title' => ['fa' => 'کارت مصرف', 'en' => 'Usage card'],
        'icon' => 'folder',
        'oneLiner' => [
            'fa' => 'کارت مصرف با نوار چندبخشی که سگمنت‌به‌سگمنت پر می‌شود؛ بعد از ۷۵٪ درصد به هشدار و بعد از ۹۰٪ به خطر می‌رود — دکمهٔ ارتقا هم جای اسلات دارد.',
            'en' => 'A usage card with a segmented bar that fills segment by segment; past 75% the percentage turns warning, past 90% danger — and the upgrade button is slot-friendly.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'limit / used / unit / decimals', 'type' => 'number', 'default' => '100 / null / null / 0', 'note' => [
                'fa' => 'سقف، مصرف (پیش‌فرض جمع دسته‌ها)، واحد و ارقام اعشار.',
                'en' => 'The cap, the used amount (the categories’ sum by default), the unit and the fraction digits.',
            ]],
            ['name' => 'categories', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => '[\'برچسب\' => مقدار] یا فهرستی از [\'label\', \'value\'] — هر دسته یک سهم رنگی از نوار.',
                'en' => '[\'Label\' => value] or a list of [\'label\', \'value\'] — each category a coloured share of the bar.',
            ]],
            ['name' => 'plan / note / action', 'type' => 'string / string / array', 'default' => 'null', 'note' => [
                'fa' => 'نام پلن، یادداشت (مثل زمان ریست) و اکشن [\'label\' => …, \'href\' => …].',
                'en' => 'The plan name, a note (like the reset date) and the action [\'label\' => …, \'href\' => …].',
            ]],
            ['name' => 'slot:actions', 'type' => 'slot', 'default' => '—', 'note' => [
                'fa' => 'جایگزین دکمه — مثلاً دکمهٔ لایووایر با wire:click.',
                'en' => 'Replaces the button — say a Livewire button with wire:click.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::usage-card title="فضای ذخیره" plan="پرو" :limit="10" unit="GB" :decimals="1"
            :categories="['اسناد' => 3.2, 'یادداشت‌های صوتی' => 2.1, 'تصویرها' => 1.4]"
            :action="['label' => 'ارتقای پلن', 'href' => '/billing']" />
        BLADE,
    ],

    'currency-converter' => [
        'title' => ['fa' => 'مبدل ارز', 'en' => 'Currency converter'],
        'icon' => 'globe',
        'oneLiner' => [
            'fa' => 'تبدیل ارز با نرخ‌هایی که از پراپ می‌آیند — هیچ fetchای در کار نیست؛ مبلغ را در هر سیستم عددی می‌خواند («۱۲٬۵۰۰» هم کار می‌کند) و با ارقام زبان شما نشان می‌دهد.',
            'en' => 'Currency conversion with rates that arrive through props — nothing is fetched; amounts are read in any numbering system (“12,500” and “۱۲٬۵۰۰” alike) and shown in your locale’s digits.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'rates', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'نرخ‌ها در برابر هر پایهٔ مشترکی نقل می‌شوند و از بیرون می‌آیند.',
                'en' => 'Rates quoted against any common base, supplied from outside.',
            ]],
            ['name' => 'amount / from / to', 'type' => 'number / string', 'default' => '1000 / null', 'note' => [
                'fa' => 'مبلغ و ارزهای مبدأ/مقصد آغازین.',
                'en' => 'The starting amount and currency pair.',
            ]],
            ['name' => 'wire:model', 'type' => '—', 'default' => 'null', 'note' => [
                'fa' => 'روی خود کامپوننت: [\'amount\' => …, \'from\' => …, \'to\' => …] را از طریق x-modelable مقید می‌کند.',
                'en' => 'On the component itself: binds [\'amount\' => …, \'from\' => …, \'to\' => …] through x-modelable.',
            ]],
            ['name' => 'note / labels', 'type' => 'string / array', 'default' => 'null / []', 'note' => [
                'fa' => 'یادداشت زیر نتیجه (مثل منبع نرخ) و واژه‌های سفارشی.',
                'en' => 'The note under the result (the rate source, say) and custom words.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::currency-converter title="تبدیل" :rates="$rates" :amount="1250" from="USD" to="JPY"
            note="نرخ میانی · نرخ‌های نمایشی" wire:model.live="state.fx" />
        BLADE,
    ],

    'workspace-shell' => [
        'title' => ['fa' => 'پوستهٔ ورک‌اسپیس', 'en' => 'Workspace shell'],
        'icon' => 'home',
        'oneLiner' => [
            'fa' => 'پوستهٔ یک ورک‌اسپیس: نوار کناری با آیتم‌های زنده، هدر، فوتر و جای‌پرامپت؛ آیتم‌های href لینک‌اند و بقیه دکمه‌هایی که خودشان را جاری می‌کنند.',
            'en' => 'A workspace shell: a sidebar with live items, a header, a footer and a prompt slot; href items are links, the rest are buttons that make themselves current.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'items', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر آیتم: [\'id\' => …, \'label\' => …, \'icon\' => …, \'badge\' => 12] و اختیاری \'href\' + \'navigate\' => true.',
                'en' => 'Each item: [\'id\' => …, \'label\' => …, \'icon\' => …, \'badge\' => 12], optionally \'href\' + \'navigate\' => true.',
            ]],
            ['name' => 'active / wire:model', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'id آیتم جاری؛ wire:model روی کامپوننت آن را مقید می‌کند (x-modelable).',
                'en' => 'The current item’s id; wire:model on the component binds it (x-modelable).',
            ]],
            ['name' => 'brand / brandMark / label', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'نام برند، نشانه و برچسب دسترس‌پذیری نوار.',
                'en' => 'The brand name, its mark and the bar’s accessible label.',
            ]],
            ['name' => 'height / collapsed / promptPlaceholder', 'type' => 'string / bool / string', 'default' => 'null', 'note' => [
                'fa' => 'بلندی پوسته، آغاز جمع‌شده و جای‌بان پرامپت (تا وقتی slot:prompt نگذاشته‌اید).',
                'en' => 'The shell height, starting collapsed, and the built-in prompt’s placeholder (until you pass slot:prompt).',
            ]],
            ['name' => 'slot:header / footer / prompt', 'type' => 'slot', 'default' => '—', 'note' => [
                'fa' => 'هدر بالای صفحه، فوتر پای نوار کناری و پرامپت پای صفحه — محتوای اصلی هم اسلات پیش‌فرض است.',
                'en' => 'The page header, the sidebar’s foot and the page-bottom prompt — the page itself is the default slot.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::workspace-shell brand="میز نابو" :active="$state['view'] ?? 'inbox'" wire:model.live="state.view" :items="$items">
            <x-slot:header><strong>صندوق ورودی</strong></x-slot:header>
            … صفحه …
            <x-slot:footer>… کاربر جاری …</x-slot:footer>
            <x-slot:prompt><x-nx::prompt wire:submit="ask" /></x-slot:prompt>
        </x-nx::workspace-shell>
        BLADE,
    ],

    'branch-connector' => [
        'title' => ['fa' => 'اتصال شاخه‌ای', 'en' => 'Branch connector'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'منبع را به هدف‌ها با مسیرهای منحنی وصل می‌کند؛ مسیرها از صفحه اندازه‌گیری می‌شوند و با resize دوباره کشیده می‌شوند — روی مسیرهای فعال پالس جریان دارد.',
            'en' => 'Connects a source to its targets with curved paths measured from the page and redrawn as it resizes — pulses flow along the active ones.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'source / targets', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر گره: [\'label\' => …, \'description\' => …, \'icon\' => …]؛ هدف‌ها state => idle هم می‌پذیرند.',
                'en' => 'Each node: [\'label\' => …, \'description\' => …, \'icon\' => …]; targets also take state => idle.',
            ]],
            ['name' => 'targetsLabel', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'عنوان گروه هدف‌ها.',
                'en' => 'The targets group’s heading.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::branch-connector
            :source="['label' => 'پیام ورودی', 'description' => 'واتساپ · تلگرام · وب', 'icon' => 'message']"
            :targets="[
                ['label' => 'ایجنت نابو', 'description' => 'فارسی · English', 'icon' => 'sparkles'],
                ['label' => 'واگذار به انسان', 'description' => 'صف: مالی', 'icon' => 'users', 'state' => 'idle'],
            ]" />
        BLADE,
    ],

    'curved-timeline' => [
        'title' => ['fa' => 'تایم‌لاین منحنی', 'en' => 'Curved timeline'],
        'icon' => 'arrow-up',
        'oneLiner' => [
            'fa' => 'خطی مارپیچ از میان نقاط عطف که با اسکرول خودش را می‌کشد (انیمیشن اسکرول‌محور CSS جایی که مرورگر دارد) و هر نقطه با رسیدن خط روشن می‌شود؛ کارت‌ها دو طرف یک‌درمیان‌اند.',
            'en' => 'A line snakes through the milestones and draws itself as you scroll (CSS scroll-driven where the browser has it), each dot lighting as the line reaches it; the cards alternate sides.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'items', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر نقطه: [\'date\' => …, \'title\' => …, \'description\' => …].',
                'en' => 'Each milestone: [\'date\' => …, \'title\' => …, \'description\' => …].',
            ]],
            ['name' => 'amplitude', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'دامنهٔ موج خط.',
                'en' => 'The line’s wave amplitude.',
            ]],
            ['name' => 'label', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'نام دسترس‌پذیر تایم‌لاین.',
                'en' => 'The timeline’s accessible name.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::curved-timeline label="نقشهٔ راه" :items="[
            ['date' => 'بهار ۱۴۰۴', 'title' => 'آزمون خصوصی', 'description' => 'چهل تیم پشتیبانی، سه زبان.'],
            ['date' => 'پاییز ۱۴۰۴', 'title' => 'دوازده زبان', 'description' => 'پاسخ بومی در هر زبان.'],
        ]" />
        BLADE,
    ],

    'comparison-table' => [
        'title' => ['fa' => 'جدول مقایسه', 'en' => 'Comparison table'],
        'icon' => 'check',
        'oneLiner' => [
            'fa' => 'جدول مقایسهٔ پلن‌ها با ستون پیشنهادی حلقه‌دار؛ نشانه‌ها ردیف‌به‌ردیف پاپ می‌شوند و در موبایل ستون ویژگی‌ها می‌ماند و پلن‌ها کنار می‌غلتند.',
            'en' => 'A plan comparison with the recommended column ringed in light; the marks pop in row by row, and on a phone the feature column stays put while the plans scroll sideways.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'plans', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر پلن: [\'id\' => …, \'name\' => …, \'price\' => …, \'period\' => …, \'description\' => …, \'action\' => [\'label\' => …, \'href\' => …]].',
                'en' => 'Each plan: [\'id\' => …, \'name\' => …, \'price\' => …, \'period\' => …, \'description\' => …, \'action\' => [\'label\' => …, \'href\' => …]].',
            ]],
            ['name' => 'features', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر ویژگی: [\'group\' => …, \'label\' => …, \'hint\' => …, \'values\' => [\'starter\' => true|false|متن]]; true/false به تیک و خط تبدیل می‌شوند.',
                'en' => 'Each feature: [\'group\' => …, \'label\' => …, \'hint\' => …, \'values\' => [\'starter\' => true|false|text]]; true/false become a check and a dash.',
            ]],
            ['name' => 'recommended / recommendedLabel', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'id پلن پیشنهادی و برچسب آن.',
                'en' => 'The recommended plan’s id and its label.',
            ]],
            ['name' => 'caption / featureLabel / includedLabel / excludedLabel', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'عنوان جدول و واژه‌های ستون ویژگی/داشتن/نداشتن برای صفحه‌خوان‌ها.',
                'en' => 'The table caption and the screen-reader words for the feature column and yes/no marks.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::comparison-table caption="مقایسهٔ پلن‌ها" recommended="growth" :plans="$plans" :features="[
            ['group' => 'ایجنت‌ها', 'label' => 'زبان‌ها', 'values' => ['starter' => '۳', 'growth' => '۱۲', 'scale' => '۴۰+']],
            ['group' => 'امنیت', 'label' => 'SSO / SAML', 'values' => ['starter' => false, 'growth' => false, 'scale' => true]],
        ]" />
        BLADE,
    ],

    'support-agent-card' => [
        'title' => ['fa' => 'کارت ایجنت پشتیبانی', 'en' => 'Support agent card'],
        'icon' => 'message',
        'oneLiner' => [
            'fa' => 'کارت اپراتور پشتیبانی با متریک‌های نوارِ نوبتی، ترند خطی و وضعیت حضور؛ نوارها با رسیدن به دید پر می‌شوند و دکمه با اسلات جایگزین می‌شود.',
            'en' => 'A support operator card with bar metrics that fill in turn, a line trend and a presence status; the bars fill as the card scrolls into view and the button is slot-replaceable.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'name / role / avatar', 'type' => 'string', 'default' => 'required / null / null', 'note' => [
                'fa' => 'نام اپراتور، نقش/تیم و تصویر (وگرنه حرف اول نام).',
                'en' => 'The operator’s name, their role/team and an avatar (initials otherwise).',
            ]],
            ['name' => 'status / statusLabel', 'type' => 'string', 'default' => 'online / null', 'note' => [
                'fa' => 'online | away | busy | offline.',
                'en' => 'online | away | busy | offline.',
            ]],
            ['name' => 'metrics', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر متریک: [\'label\' => …, \'value\' => 342, \'max\' => 400, \'display\' => \'۳۴۲ / ۴۰۰\', \'tone\' => \'success\'].',
                'en' => 'Each metric: [\'label\' => …, \'value\' => 342, \'max\' => 400, \'display\' => \'342 / 400\', \'tone\' => \'success\'].',
            ]],
            ['name' => 'trend / trendLabel / trendValue', 'type' => 'array / string', 'default' => 'null', 'note' => [
                'fa' => 'مقدارهای ترند، برچسب و عدد کنار آن.',
                'en' => 'The trend values, their label and the number beside it.',
            ]],
            ['name' => 'action / slot:actions', 'type' => 'array / slot', 'default' => 'null', 'note' => [
                'fa' => 'دکمهٔ پای کارت: [\'label\' => …, \'icon\' => …, \'href\' => …] یا اسلات با wire:click.',
                'en' => 'The card’s button: [\'label\' => …, \'icon\' => …, \'href\' => …] or a slot with wire:click.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::support-agent-card name="آمارا اوکافور" role="مالی · لاگوس" status="online"
            :metrics="[
                ['label' => 'تیکت حل‌شده', 'value' => 342, 'max' => 400],
                ['label' => 'CSAT', 'value' => 96, 'display' => '۹۶٪', 'tone' => 'success'],
            ]"
            :trend="[12, 18, 14, 22, 26]" trend-label="حل‌شده، ۵ هفته" trend-value="۹۲">
            <x-slot:actions><x-nx::button variant="primary" block icon="arrow-right" wire:click="ping('تخصیص یافت')">تخصیص تیکت</x-nx::button></x-slot:actions>
        </x-nx::support-agent-card>
        BLADE,
    ],

    'kanban' => [
        'title' => ['fa' => 'برد کانبان', 'en' => 'Kanban'],
        'icon' => 'check-circle',
        'oneLiner' => [
            'fa' => 'بردی که کارت‌هایش با درگ‌ودراپ بومی (و از منوی سه‌نقطه، پس با کیبورد هم) جابه‌جا می‌شوند؛ جابه‌جایی FLIP دارد، شمارندهٔ ستون‌ها می‌غلتد و افزودن سریع در جا باز می‌شود.',
            'en' => 'A board whose cards move by native drag & drop (and from each card’s three-dot menu, so the keyboard works too); moves glide on FLIP, the column counts roll and the quick-add grows in place.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'columns', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر ستون: [\'id\' => …, \'title\' => …, \'tone\' => …, \'cards\' => [[\'id\' => …, \'title\' => …, \'meta\' => …, \'tone\' => …, \'assignee\' => [\'name\' => …], \'actions\' => […]]]] — همین ستون‌ها مرجع حقیقت‌اند.',
                'en' => 'Each column: [\'id\' => …, \'title\' => …, \'tone\' => …, \'cards\' => [[\'id\' => …, \'title\' => …, \'meta\' => …, \'tone\' => …, \'assignee\' => [\'name\' => …], \'actions\' => […]]]] — the columns you pass are the truth.',
            ]],
            ['name' => 'moveAction / addAction', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'نام متد لایووایر: moveCard($card, $from, $to, $index) و addCard($column, $title)؛ بدون آن‌ها همه‌چیز سمت مرورگر می‌ماند و رویدادهای nx-move و nx-card-action پخش می‌شود.',
                'en' => 'Livewire method names: moveCard($card, $from, $to, $index) and addCard($column, $title); without them everything stays client-side and nx-move / nx-card-action events fire.',
            ]],
            ['name' => 'quickAdd', 'type' => 'bool', 'default' => 'true', 'note' => [
                'fa' => 'فرم افزودن سریع پای هر ستون.',
                'en' => 'The quick-add form under each column.',
            ]],
            ['name' => 'height / label / locale', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'بلندی برد، نام دسترس‌پذیر و زبان متن‌های ساخته‌شده.',
                'en' => 'The board height, its accessible name and the built-in words’ locale.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::kanban height="30rem" :columns="[
            ['id' => 'todo', 'title' => 'انبار', 'tone' => 'info', 'cards' => [
                ['id' => 'c1', 'title' => 'سیم‌کشی صفحهٔ تنظیمات', 'meta' => 'FR-104 · 2 دیدگاه', 'tone' => 'info'],
            ]],
            ['id' => 'doing', 'title' => 'این هفته', 'tone' => 'warning', 'cards' => []],
        ]" x-on:nx-move="$wire.ping('جابه‌جا شد')" />
        BLADE,
    ],

    'calendar' => [
        'title' => ['fa' => 'تقویم', 'en' => 'Calendar'],
        'icon' => 'bell',
        'oneLiner' => [
            'fa' => 'شبکهٔ ماهانه روی تاریخ‌های سادهٔ "YYYY-MM-DD" (بدون هیچ کتابخانهٔ تاریخ)؛ تعویض ماه سمت مرورگر است و از سمتش سُر می‌خورد، دستور کار روزِ انتخابی زیر شبکه دوباره چیده می‌شود و پیمایش کیبورد روویینگ است.',
            'en' => 'A month grid over plain “YYYY-MM-DD” dates (no date library); switching months is client-side and slides in from its side, the selected day’s agenda restacks under the grid, and keyboard roving walks the days.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'events', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر رویداد: [\'date\' => …, \'label\' => …, \'time\' => …, \'tone\' => accent|success|warning|danger|info|gold, \'href\' => …]؛ روز پر «+N» می‌گیرد.',
                'en' => 'Each event: [\'date\' => …, \'label\' => …, \'time\' => …, \'tone\' => accent|success|warning|danger|info|gold, \'href\' => …]; a full day gets a “+N” tally.',
            ]],
            ['name' => 'value / wire:model', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'روز انتخابی؛ انتخابِ دوبارهٔ همان روز پاکش می‌کند (null).',
                'en' => 'The selected day; picking the selected day clears it (null).',
            ]],
            ['name' => 'month / today / weekStart / maxPerCell', 'type' => 'string / string / int / int', 'default' => 'null / null / 0 / 3', 'note' => [
                'fa' => 'ماه آغازین، «امروز»، روز آغاز هفته (۰ یکشنبه · ۶ شنبه) و بیشترین رویداد هر خانه.',
                'en' => 'The opening month, “today”, the week’s first day (0 Sunday · 6 Saturday) and the events shown per cell.',
            ]],
            ['name' => 'locale / label / emptyText', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'زبان نام روزها/ماه‌ها و ارقام، نام دسترس‌پذیر و متن روز خالی.',
                'en' => 'The locale for day/month names and digits, the accessible name and the empty-day text.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::calendar value="2026-09-14" week-start="6" max-per-cell="3" wire:model="state.day" :events="[
            ['date' => '2026-09-14', 'label' => 'بازبینی طراحی', 'time' => '10:00', 'tone' => 'accent'],
            ['date' => '2026-09-22', 'label' => 'گردشوری تیمی', 'tone' => 'gold'],
        ]" />
        BLADE,
    ],

    'timeline-feed' => [
        'title' => ['fa' => 'جریان فعالیت', 'en' => 'Timeline feed'],
        'icon' => 'zap',
        'oneLiner' => [
            'fa' => 'جریان عمودی فعالیت: ردیف‌های آیکون + متن + زمان نسبی که یکی‌یکی ظاهر می‌شوند و نوار اتصالِ گرادیانی به هم می‌دوزدشان؛ زمان می‌تواند تاریخ (به زمان نسبیِ زبان شما) یا برچسب باشد.',
            'en' => 'A vertical activity stream: rows of icon + text + relative time that reveal one after another, stitched by a gradient connector; time may be a date (relative, in your language) or a plain label.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'items', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر ردیف: [\'id\' => …, \'actor\' => …, \'text\' => …, \'target\' => …, \'time\' => تاریخ|timestamp|برچسب, \'tone\' => success|warning|info|danger|neutral]؛ بدون actor آیکون معنادار بگذارید.',
                'en' => 'Each row: [\'id\' => …, \'actor\' => …, \'text\' => …, \'target\' => …, \'time\' => date|timestamp|label, \'tone\' => success|warning|info|danger|neutral]; without an actor, give it a meaningful icon.',
            ]],
            ['name' => 'label / locale', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'نام دسترس‌پذیر فهرست و زبان زمان‌های نسبی.',
                'en' => 'The list’s accessible name and the relative times’ locale.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::timeline-feed label="جریان فعالیت سفارش" :items="[
            ['id' => 'a1', 'actor' => 'مریم رضایی', 'text' => 'سفارش را تأیید کرد', 'target' => '#۱۲۴۸', 'time' => now()->subMinutes(3), 'tone' => 'success'],
            ['id' => 'a2', 'icon' => 'upload', 'text' => 'پشتیبان‌گیری کامل شد', 'time' => now()->subHours(2), 'tone' => 'info'],
        ]" />
        BLADE,
    ],

    'file-manager' => [
        'title' => ['fa' => 'مدیر فایل', 'en' => 'File manager'],
        'icon' => 'folder',
        'oneLiner' => [
            'fa' => 'مرورگر فایل کامل: پوشه‌ها با نان‌ریز مسیر باز می‌شوند، جست‌وجو همان پوشه را صافی می‌زند، کارت فایل کشوی جزئیات را از کنار می‌آورد و بارگذاری با nx-upload یا wire:model به اپ می‌رسد — اندازه‌ها و تاریخ‌ها با ارقام و تقویم زبان خواننده.',
            'en' => 'A full file browser: folders open along the breadcrumb trail, the search filters the current folder, a file card slides its details drawer in, and uploads reach the app through nx-upload or wire:model — sizes and dates in the reader’s digits and calendar.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'items', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر مدخل: [\'id\' => …, \'name\' => …, \'parent\' => پوشهٔ والد (حذف = ریشه), \'kind\' => folder|image|audio|video|file (یا پسوندِ نام بگوید), \'size\' => بایت, \'items\' => شمار پوشه, \'modified\' => Carbon|ISO|timestamp|برچسب, \'href\' => پیوند «باز کردن» کشو, \'preview\' => نشانی تصویر کشو, \'details\' => [[\'label\' => …, \'value\' => …]]]؛ پوشه‌ها در هر فهرست اول می‌آیند و نام‌ها با ترتیب الفبایی زبان خواننده چیده می‌شوند.',
                'en' => 'Each entry: [\'id\' => …, \'name\' => …, \'parent\' => its folder (omitted = root), \'kind\' => folder|image|audio|video|file (or let the extension say), \'size\' => bytes, \'items\' => folder count, \'modified\' => Carbon|ISO|timestamp|label, \'href\' => the drawer’s Open link, \'preview\' => the drawer image, \'details\' => [[\'label\' => …, \'value\' => …]]]; folders list first and names sort in the reader’s collation.',
            ]],
            ['name' => 'view / current', 'type' => 'string', 'default' => 'grid / null', 'note' => [
                'fa' => 'view کلید سگمنت نوار ابزار است (grid یا list) و current پوشه‌ای که برد همان‌جا باز می‌شود؛ نان‌ریزهای مسیر از همین به بالا ساخته می‌شوند. تعویض نمای سمت مرورگر است و رویداد nx-view می‌دهد.',
                'en' => 'view seeds the toolbar’s segmented (grid or list) and current is the folder the board opens in; the breadcrumb trail builds from it upward. Switching views is client-side and fires nx-view.',
            ]],
            ['name' => 'height / label', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'بلندی صحنهٔ اسکرول‌شونده (مثل ۳۰rem) که به متغیر --nx-fm-height می‌رود و نام دسترس‌پذیری برد؛ همان واژه روی نان‌ریز ریشه هم می‌نشیند.',
                'en' => 'The scrollable stage height (30rem, say), riding --nx-fm-height, and the board’s accessible name; the root crumb wears the same word.',
            ]],
            ['name' => 'searchPlaceholder / emptyText', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'جای‌بان جست‌وجو و متن پوشهٔ خالی — واژه‌های پیش‌فرض از i18n هسته می‌آیند (fa/en/ar)؛ جست‌وجو نویسه‌های ی/ک فارسی و عربی را هم‌ارز می‌خواند.',
                'en' => 'The search placeholder and the empty-folder text — defaults come from the core i18n table (fa/en/ar); the search folds Persian and Arabic letter variants alike.',
            ]],
            ['name' => 'wire:model', 'type' => '—', 'default' => 'null', 'note' => [
                'fa' => 'روی خود کامپوننت به انتخاب‌گر بومی فایل می‌نشیند و بارگذاری را Livewire می‌گیرد (با WithFileUploads)؛ بدون آن رویداد nx-upload با FileList پخش می‌شود. رویدادهای دیگر: nx-navigate (ورود پوشه)، nx-open (کشوی فایل) و nx-view.',
                'en' => 'Sits on the component and lands on the native file picker so Livewire takes the uploads (with WithFileUploads); without it the nx-upload event bubbles with the FileList. Other events: nx-navigate (folder entered), nx-open (a file’s drawer) and nx-view.',
            ]],
            ['name' => 'locale', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'زبان ارقام، تاریخ‌ها و واژه‌های ساخته‌شده (پیش‌فرض: زبان اپ)؛ تاریخ‌ها با IntlDateFormatter قالب می‌خورند و تقویم TRADITIONAL برای فارسی یعنی جلالی.',
                'en' => 'The locale for digits, dates and built-in words (defaults to the app’s); dates format through IntlDateFormatter, and the TRADITIONAL calendar means Jalali for Persian.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::file-manager :items="[
            ['id' => 'design', 'name' => 'طراحی', 'kind' => 'folder', 'items' => 12],
            ['id' => 'logo.svg', 'name' => 'logo.svg', 'parent' => 'design', 'size' => 18432,
                'modified' => now()->subDays(2), 'href' => '#',
                'details' => [['label' => 'کاربرد', 'value' => 'سربرگ و فاکتور']]],
        ]" current="design" height="30rem" wire:model="uploads"
            x-on:nx-navigate="$wire.visit($event.detail.folder)"
            x-on:nx-upload="$wire.ingest($event.detail.files)" />

        {{-- بدون اتصال لایووایر: نمای فهرستی با حالت خالی سفارشی --}}
        <x-nx::file-manager view="list" label="اسناد" :items="$docs"
            empty-text="هنوز سندی بارگذاری نشده" />
        BLADE,
    ],

    'email' => [
        'title' => ['fa' => 'ایمیل', 'en' => 'Mail'],
        'icon' => 'mail',
        'oneLiner' => [
            'fa' => 'کلاینت ایمیل سه‌پنجره‌ای: ریل پوشه‌ها، فهرست پیام‌ها و پنجرهٔ خواندن با پاسخ‌نویس — باز کردن پیام خوانده‌شدنش را هم می‌رساند، ستاره‌ها و شمارنده‌ها زنده‌اند و تاریخ‌ها و ارقام با تقویم و رقم‌های زبان صفحه؛ پاسخ با رویداد nx-reply یا متد لایووایر بیرون می‌رود.',
            'en' => 'A three-pane mail client: the folder rail, the message list and a reading pane with a reply composer — opening a message also marks it read, stars and tallies stay live, and dates and digits follow the page’s locale; replies leave through the nx-reply event or a Livewire method.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'messages', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر پیام: [\'id\' => …, \'from\' => [\'name\' => …, \'email\' => …, \'avatar\' => …], \'to\' => …, \'subject\' => …, \'body\' => … (هر خط یک بند، خط نخست پیش‌نمایش ردیف), \'time\' => Carbon|timestamp|ISO|برچسب, \'unread\' => bool, \'starred\' => bool, \'folder\' => …].',
                'en' => 'Each message: [\'id\' => …, \'from\' => [\'name\' => …, \'email\' => …, \'avatar\' => …], \'to\' => …, \'subject\' => …, \'body\' => … (one paragraph per line, the first line previews the row), \'time\' => Carbon|timestamp|ISO|label, \'unread\' => bool, \'starred\' => bool, \'folder\' => …].',
            ]],
            ['name' => 'folders', 'type' => 'array', 'default' => 'شش پوشهٔ داخلی', 'note' => [
                'fa' => 'پیش‌فرض inbox · starred · sent · drafts · archive · trash («ستاره‌دار» مجازی است و ستاره‌دارهای همهٔ پوشه‌ها را جمع می‌کند)؛ با فهرستی از [\'id\' => …, \'label\' => …, \'icon\' => …] پوشه‌های خودتان جایگزین می‌شوند و شمارندهٔ هر کدام از همان پیام‌ها می‌آید.',
                'en' => 'Defaults to inbox · starred · sent · drafts · archive · trash (“starred” is virtual — it gathers every folder’s starred); a list of [\'id\' => …, \'label\' => …, \'icon\' => …] replaces them, each one’s badge counting the same messages.',
            ]],
            ['name' => 'open / folder', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'شناسهٔ پیام باز (پیش‌فرض: نخستین پیام پوشه) و پوشهٔ آغازین؛ open روی ریشه با wire:model هم مقید می‌شود (x-modelable) تا سرور بداند کدام پیام خوانده می‌شود.',
                'en' => 'The open message’s id (the folder’s first by default) and the starting folder; open also binds through wire:model on the root (x-modelable), so the server knows which message is being read.',
            ]],
            ['name' => 'replyAction', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'نام متد لایووایر؛ پس از فرستادن پاسخ پاسخ‌نویس پاک می‌شود و $wire.action(text, messageId) صدا می‌شود. بدون آن رویدادهای nx-folder / nx-open / nx-star / nx-reply روی خود عنصر حباب می‌کنند.',
                'en' => 'A Livewire method name; once a reply is sent the composer clears and $wire.action(text, messageId) fires. Without it the nx-folder / nx-open / nx-star / nx-reply events bubble on the element itself.',
            ]],
            ['name' => 'height', 'type' => 'string', 'default' => '36rem', 'note' => [
                'fa' => 'بلندی کل قاب (می‌رود روی --nx-email-height).',
                'en' => 'The whole frame’s height (riding --nx-email-height).',
            ]],
            ['name' => 'placeholder / emptyText / labels / locale', 'type' => 'string / string / array / string', 'default' => 'null / null / [] / null', 'note' => [
                'fa' => 'جای‌بان پاسخ‌نویس، متن پوشهٔ خالی، بازنویسی واژه‌های داخلی و زبان تاریخ‌ها و ارقام (پیش‌فرض: زبان اپ)؛ کلیدها از i18n هسته می‌آیند (fa/en/ar).',
                'en' => 'The composer placeholder, the empty-folder text, overrides for the built-in words, and the dates-and-digits locale (the app’s by default); the keys come from the core i18n table (fa/en/ar).',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::email :messages="[
            ['id' => 'm1', 'from' => ['name' => 'سارا رضایی', 'email' => 'sara@nabu.ai'],
                'subject' => 'جلسهٔ دمو ساعت ۱۰ فردا؟', 'body' => "سلام؛\nهمان ساعت ۱۰ می‌ماند؟",
                'time' => now()->subMinutes(18), 'unread' => true, 'starred' => true],
            ['id' => 'm2', 'from' => ['name' => 'GitHub'], 'subject' => '[nabuxui] PR merged',
                'body' => 'PR #421 merged into main.', 'folder' => 'archive'],
        ]" open="m1" height="34rem"
            x-on:nx-reply="$wire.ping('پاسخ فرستاده شد')" />

        {{-- پوشه‌های خودتان + پاسخ به متد لایووایر --}}
        <x-nx::email :messages="$deskMail" :folders="[
            ['id' => 'orders', 'label' => 'سفارش‌ها', 'icon' => 'file'],
            ['id' => 'billing', 'label' => 'مالی', 'icon' => 'chart'],
        ]" folder="orders" reply-action="sendReply" height="30rem" />

        {{-- پیامِ باز روی سرور: wire:model روی ریشه (x-modelable) --}}
        <x-nx::email :messages="$messages" wire:model.live="openMail" />
        BLADE,
    ],

    'todo' => [
        'title' => ['fa' => 'فهرست کارها', 'en' => 'To-do'],
        'icon' => 'check',
        'oneLiner' => [
            'fa' => 'فهرست کارهای گروه‌بندی‌شده: تیک فنری روی چک‌باکس بومی، جابه‌جایی با درگ‌ودراپ (یا Alt+↑/↓ با کیبورد) و قرص فرودی که نفس می‌کشد؛ شمارندهٔ انجام‌شده/کل می‌غلتد، نوار پیشرفت پُر می‌شود و افزودن سریع کار تازه را در گروهی که برمی‌گزینید می‌نشاند.',
            'en' => 'Grouped tasks with a spring tick on a native checkbox, drag & drop (or Alt+↑/↓ from the keyboard) with a breathing drop pill, a rolling done/total counter, a progress bar that fills, and a quick-add that drops the new task in the group you pick.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'groups', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر گروه: [\'id\' => …, \'title\' => …, \'tone\' => accent|success|warning|danger|info|gold, \'tasks\' => [[\'id\' => …, \'title\' => …, \'done\' => true]]] — همین گروه‌ها مرجع حقیقت‌اند.',
                'en' => 'Each group: [\'id\' => …, \'title\' => …, \'tone\' => accent|success|warning|danger|info|gold, \'tasks\' => [[\'id\' => …, \'title\' => …, \'done\' => true]]] — the groups you pass are the truth.',
            ]],
            ['name' => 'toggle-action / remove-action / add-action / move-action', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'نام متدهای لایووایر: toggleTask($task, $done)، removeTask($task)، addTask($group, $title) و moveTask($task, $from, $to, $index) — بعد از تغییر خوش‌بینانه صدا می‌شوند، پس گروه‌ها را دوباره بدهید تا کارها با wire:key از میان morph سُر بخورند. بدون آن‌ها همه‌چیز سمت مرورگر می‌ماند و رویدادهای nx-toggle / nx-remove / nx-add / nx-move از خود کامپوننت پخش می‌شوند.',
                'en' => 'Livewire method names: toggleTask($task, $done), removeTask($task), addTask($group, $title) and moveTask($task, $from, $to, $index) — called after the optimistic change, so hand the groups back and the tasks glide through the morph on their wire:key. Without them everything stays client-side and the nx-toggle / nx-remove / nx-add / nx-move events bubble from the component.',
            ]],
            ['name' => 'quickAdd / progress', 'type' => 'bool', 'default' => 'true / true', 'note' => [
                'fa' => 'فرم افزودن سریع (با چند گروه، خودش انتخابگر گروه می‌گیرد) و شمارندهٔ انجام‌شده/کل با نوار پیشرفت؛ با تمام‌شدن همهٔ کارها برد data-complete می‌گیرد.',
                'en' => 'The quick-add composer (with several groups it grows a group select) and the rolling done/total counter with its bar; when every task is done the board takes data-complete.',
            ]],
            ['name' => 'heading / label', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'عنوان دیده‌شدهٔ فهرست و نام دسترس‌پذیری آن (aria-label).',
                'en' => 'The list’s visible heading and its accessible name (aria-label).',
            ]],
            ['name' => 'locale', 'type' => 'string', 'default' => 'زبان اپ', 'note' => [
                'fa' => 'زبان ارقام و واژه‌های خودِ کامپوننت (جای‌بان افزودن، متن خالی، حذف…) که از i18n هسته می‌آیند — fa/en/ar.',
                'en' => 'The locale for digits and the component’s own words (the add placeholder, the empty text, remove…), which come from the core i18n table — fa/en/ar.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::todo heading="برنامهٔ امروز" :groups="[
            ['id' => 'today', 'title' => 'امروز', 'tone' => 'warning', 'tasks' => [
                ['id' => 't1', 'title' => 'پاسخ به تیکت ۱۲۴۸', 'done' => true],
                ['id' => 't2', 'title' => 'تماس با تأمین‌کننده'],
            ]],
            ['id' => 'later', 'title' => 'بعداً', 'tone' => 'info', 'tasks' => []],
        ]" toggle-action="toggleTask" remove-action="removeTask"
            add-action="addTask" move-action="moveTask" />

        {{-- بدون اتصال لایووایر: رویدادها را خودتان می‌گیرید --}}
        <x-nx::todo :groups="$groups" x-on:nx-toggle="$wire.ping('تیک خورد')" />
        BLADE,
    ],

    'gantt' => [
        'title' => ['fa' => 'نمودار گانت', 'en' => 'Gantt chart'],
        'icon' => 'sliders',
        'oneLiner' => [
            'fa' => 'گانت روی شبکهٔ روز: میله‌ها با درگ جابه‌جا و از دو لبه تغییر اندازه می‌شوند (یا با کلیدهای جهت‌دار — Shift یک هفته قدم می‌زند و Alt پایان را می‌کشد)، آرنج‌های وابستگی و خط «امروز» دارند و بزرگ‌نمایی روز/هفته/ماه کل نمودار را دوباره می‌چیند؛ همان داده به‌صورت جدول پنهان هم می‌رود.',
            'en' => 'A gantt over a day grid: bars drag and resize from either edge (or move with the arrow keys — Shift steps a week, Alt pulls the end), wear dependency elbows and a today line, and the day/week/month zoom reflows the whole chart; the same data ships as a hidden table.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'rows', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر ردیف: [\'id\' => …, \'title\' => …, \'tone\' => accent|success|warning|danger|info|gold, \'tasks\' => [[\'id\' => …, \'title\' => …, \'start\' => \'2026-10-01\', \'end\' => …, \'tone\' => …, \'progress\' => ۰..۱۰۰, \'dependsOn\' => idکارِپیشین]]] — تاریخ‌ها «YYYY-MM-DD» روز ساده‌اند و کارهای هم‌پوشانِ هر ردیف در لِین‌های جدا روی هم می‌نشینند.',
                'en' => 'Each row: [\'id\' => …, \'title\' => …, \'tone\' => accent|success|warning|danger|info|gold, \'tasks\' => [[\'id\' => …, \'title\' => …, \'start\' => \'2026-10-01\', \'end\' => …, \'tone\' => …, \'progress\' => 0..100, \'dependsOn\' => taskId]]] — dates are plain “YYYY-MM-DD” days, and a row’s overlapping tasks stack into lanes.',
            ]],
            ['name' => 'moveAction', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'نام متد لایووایر moveTask($task, $start, $end) که پس از جابه‌جایی خوش‌بینانه صدا می‌شود — ردیف‌ها را با تاریخ‌های تازه برگردانید تا میله‌ها با wire:key از میان morph سُر بخورند. بدون آن همه‌چیز سمت مرورگر می‌ماند و رویداد nx-move (با task/start/end) از خود کامپوننت پخش می‌شود.',
                'en' => 'A Livewire moveTask($task, $start, $end) called after the optimistic move — return the rows with the new dates and the bars glide through the morph on their wire:key. Without it everything stays client-side and the nx-move event (task/start/end) bubbles from the component.',
            ]],
            ['name' => 'zoom', 'type' => 'string', 'default' => 'day', 'note' => [
                'fa' => 'مقیاس آغازین: day | week | month؛ قرص‌های نوار ابزار همان‌جا هستند و تعویضشان — یک --nx-gantt-unit — کل نمودار را دوباره می‌چیند.',
                'en' => 'The starting scale: day | week | month; the toolbar’s pills switch it live and one --nx-gantt-unit reflows the whole chart.',
            ]],
            ['name' => 'height / label / locale', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'بلندی قاب اسکرول‌شونده (می‌رود روی --nx-gantt-height)، نام دسترس‌پذیر نمودار و زبان سرصفحه‌ها، برچسب میله‌ها و ارقام (پیش‌فرض: زبان اپ)؛ دکمهٔ «امروز» خط امروز را به یک‌سوم دید می‌آورد.',
                'en' => 'The scrollable frame height (riding --nx-gantt-height), the chart’s accessible name and the locale for the header bands, bar labels and digits (the app’s by default); the Today button scrolls the today line to a third of the view.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::gantt height="26rem" :rows="[
            ['id' => 'design', 'title' => 'طراحی', 'tone' => 'info', 'tasks' => [
                ['id' => 'wire', 'title' => 'بازطراحی تنظیمات', 'start' => '2026-10-04', 'end' => '2026-10-13', 'tone' => 'info', 'progress' => 60],
            ]],
            ['id' => 'build', 'title' => 'توسعه', 'tone' => 'success', 'tasks' => [
                ['id' => 'api', 'title' => 'API تقویم', 'start' => '2026-10-12', 'end' => '2026-10-22', 'progress' => 35, 'dependsOn' => 'wire'],
            ]],
        ]" x-on:nx-move="$wire.ping($event.detail.task + ': ' + $event.detail.start + ' → ' + $event.detail.end)" />

        {{-- با مرجع حقیقت سمت سرور: move-action نام متد لایووایر است --}}
        <x-nx::gantt :rows="$plan" zoom="week" move-action="moveTask" height="30rem" />
        BLADE,
    ],

    'gauge' => [
        'title' => ['fa' => 'گیج', 'en' => 'Gauge'],
        'icon' => 'zap',
        'oneLiner' => [
            'fa' => 'عددسنج نیم‌دایره‌ای با عقربهٔ فنری و خواندن غلتان: جاروب و عقربه روی پراپرتی‌های سفارشی و ترنزیشن CSS سوارند، پس هر morph لایووایری که مقدار را عوض کند آن‌ها را از همان‌جا که بودند دوباره می‌راند؛ ناحیهٔ فعال رنگ جاروب، نقطهٔ محور و برچسب را می‌گیرد و همان داده به‌صورت جدول پنهان هم می‌رود.',
            'en' => 'A semicircle dial with a spring needle and a rolling reading: the sweep and the needle ride custom properties with CSS transitions, so any Livewire morph that changes the value re-runs them from wherever they stopped; the active zone colours the sweep, the hub dot and the caption, and the same data ships as a hidden table.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'value / min / max', 'type' => 'number', 'default' => '0 / 0 / 100', 'note' => [
                'fa' => 'عدد جاری و دو سر بازه؛ مقدار بیرون از بازه به لبه‌ها بریده می‌شود و min سرِ ابتدای جهت خواندن صفحه می‌نشیند (در RTL صفحه آینه می‌شود).',
                'en' => 'The reading and the range’s ends; a value past the ends clips to them, and min parks at the inline-start end (the dial mirrors in RTL).',
            ]],
            ['name' => 'zones', 'type' => 'array', 'default' => 'سه‌قسمت پیش‌فرض', 'note' => [
                'fa' => 'پیش‌فرض: موفق تا ۶۰٪ بازه، هشدار تا ۸۵٪ و خطر بعد از آن. هر ناحیه [\'upTo\' => …, \'tone\' => success|warning|danger|info|accent, \'label\' => …] می‌گیرد؛ upTo آخر قابل حذف است تا تا سقف برود و بدون label واژهٔ خودِ tone از i18n هسته می‌آید.',
                'en' => 'Default: success to 60% of the span, warning to 85%, danger beyond. Each zone takes [\'upTo\' => …, \'tone\' => success|warning|danger|info|accent, \'label\' => …]; the last upTo may be omitted to run to the cap, and without a label the tone’s own word arrives from the core i18n table.',
            ]],
            ['name' => 'title / subtitle / unit', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'عنوان و زیرعنوان سرصفحهٔ کارت و واحد کوچک کنار عدد.',
                'en' => 'The chart head’s title and subtitle, and the small unit beside the figure.',
            ]],
            ['name' => 'zoneLabel', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'برچسب زیر عدد؛ null یعنی نام ناحیهٔ فعال و رشتهٔ خالی یعنی بدون برچسب.',
                'en' => 'The caption under the figure; null means the active zone’s name, an empty string hides it.',
            ]],
            ['name' => 'size', 'type' => 'string | int', 'default' => 'null (26rem)', 'note' => [
                'fa' => 'پهنای صفحهٔ گیج — می‌رود روی --nx-gauge-size؛ عدد یعنی px و رشته (مثل 15rem) همان‌طور که هست می‌نشیند.',
                'en' => 'The dial’s width — riding --nx-gauge-size; a number means px, a string (15rem, say) lands as written.',
            ]],
            ['name' => 'decimals / locale / labels', 'type' => 'int / string / array', 'default' => 'null / null / []', 'note' => [
                'fa' => 'ارقام اعشار (پیش‌فرض: ۰ برای اعداد صحیح و ۱ برای اعشاری)، زبان ارقام و واژه‌ها (پیش‌فرض: زبان اپ) و بازنویسی واژه‌های داخلی.',
                'en' => 'The fraction digits (0 for whole numbers, 1 for fractional ones by default), the digits-and-words locale (the app’s by default) and overrides for the built-in words.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::gauge :value="$metrics->load" unit="٪" title="بار صف پشتیبانی" subtitle="همهٔ کانال‌ها · شیفت جاری" />

        {{-- ناحیه‌های سفارشی — آخرین ناحیه بدون upTo تا سقف می‌رود --}}
        <x-nx::gauge :value="4.53" :max="5" :decimals="2" unit="M" title="مصرف ماهانهٔ توکن مدل"
            :zones="[
                ['upTo' => 2.5, 'tone' => 'success', 'label' => 'آرام'],
                ['upTo' => 4.2, 'tone' => 'warning', 'label' => 'پرترافیک'],
                ['tone' => 'danger', 'label' => 'نزدیک سقف'],
            ]" />
        BLADE,
    ],

    'tree-view' => [
        'title' => ['fa' => 'نمای درختی', 'en' => 'Tree view'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'درختِ تاشو روی الگوی ARIA treeview: یک توقف تب، فلش‌ها میان ردیف‌های پیداتردیده قدم می‌زنند (→/← باز و بسته می‌کند و در راست‌به‌چپ جابه‌جا می‌شود)، Enter انتخاب و Space تا می‌زند؛ شمار فرزندان پدرها با ارقام زبان شماست و انتخاب با wire:model یا رویداد nx-select بیرون می‌رود.',
            'en' => 'A collapsible tree on the ARIA treeview pattern: one tab stop, arrows walking the visible rows (→/← expand and collapse, swapped in RTL), Enter selects and Space folds; parent rows carry their child count in your locale’s digits, and the selection leaves through wire:model or the nx-select event.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'nodes', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر گره: [\'id\' => …, \'label\' => …, \'meta\' => …, \'icon\' => …, \'tone\' => accent|success|warning|danger|info|gold, \'children\' => […]] — بازگشتی؛ همین درخت مرجع حقیقت است و هرگز خودِ کامپوننت ویرایشش نمی‌کند.',
                'en' => 'Each node: [\'id\' => …, \'label\' => …, \'meta\' => …, \'icon\' => …, \'tone\' => accent|success|warning|danger|info|gold, \'children\' => […]] — recursive; the nodes you pass are the truth and are never edited in place.',
            ]],
            ['name' => 'defaultExpanded', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'شناسهٔ پدرهایی که باز شروع می‌کنند؛ تا بسته‌شدن همیشه سمت مرورگر است و هر تغییر با رویداد nx-expand ({ id, open }) از خود کامپوننت پخش می‌شود.',
                'en' => 'The parents that start open; folding is always client-side and every change bubbles as nx-expand ({ id, open }) from the component.',
            ]],
            ['name' => 'selected / wire:model', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'شناسهٔ گرهٔ انتخاب‌شده در آغاز؛ wire:model (از طریق x-modelable روی ریشه) همان شناسه را مقید می‌کند و هر انتخاب به‌علاوه رویداد nx-select هم می‌دهد.',
                'en' => 'The selected node’s id at the start; wire:model (through x-modelable on the root) binds that id, and every pick also dispatches nx-select.',
            ]],
            ['name' => 'selectable', 'type' => 'bool', 'default' => 'true', 'note' => [
                'fa' => 'با false ردیف‌ها فقط تا می‌خورند: aria-selected هرگز نمی‌نشیند و Enter بی‌اثر است — مثلاً برای چارت سازمانی.',
                'en' => 'With false the rows only fold: aria-selected never lands and Enter does nothing — an org chart, say.',
            ]],
            ['name' => 'label / locale', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'نام دسترس‌پذیر درخت (پیش‌فرض «نمای درختی» از i18n هسته) و زبان ارقام شمار فرزندان و واژه‌های ساخته‌شده — fa/en/ar.',
                'en' => 'The tree’s accessible name (“Tree view” from the core i18n by default) and the locale for the child-count digits and built-in words — fa/en/ar.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::tree-view label="فایل‌های پروژه" :default-expanded="['packages', 'livewire']"
            :selected="$state['file'] ?? 'tree-view.blade.php'" wire:model.live="state.file" :nodes="[
                ['id' => 'packages', 'label' => 'packages', 'icon' => 'folder', 'children' => [
                    ['id' => 'livewire', 'label' => 'livewire', 'children' => [
                        ['id' => 'tree-view.blade.php', 'label' => 'tree-view.blade.php', 'icon' => 'file', 'tone' => 'accent'],
                    ]],
                ]],
                ['id' => 'docs', 'label' => 'docs', 'icon' => 'folder', 'meta' => '۲ رهنما'],
            ]" x-on:nx-expand="$wire.ping($event.detail.id)" />

        {{-- بدون سیم‌کشی: انتخاب‌ها فقط رویداد می‌دهند --}}
        <x-nx::tree-view label="دسته‌بندی راهنما" :default-expanded="['start']" :nodes="$categories"
            x-on:nx-select="$wire.ping($event.detail)" />

        {{-- چارت سازمانی: فقط تا شدن، بدون انتخاب --}}
        <x-nx::tree-view :selectable="false" :nodes="$org" x-on:nx-expand="track($event.detail.id)" />
        BLADE,
    ],
];
