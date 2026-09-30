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
];
