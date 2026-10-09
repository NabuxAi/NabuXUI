<?php

/**
 * Demo manifest of the "agent" group — surfaces for AI agents: reasoning,
 * streamed answers, tool calls, permission, code and data. Scenarios live at
 * resources/views/demos/components/agent/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'رابط عامل هوش مصنوعی', 'en' => 'AI agent'],

    'thinking-trace' => [
        'title' => ['fa' => 'ردِ فکر', 'en' => 'Thinking trace'],
        'icon' => 'sparkles',
        'oneLiner' => [
            'fa' => 'پنل جمع‌شوندهٔ استدلال: سرتیتر «در حال فکر…» با درخشش و زمان سپری‌شده، گام‌هایی که یکی‌یکی می‌آیند و در پایان خودش به «۱۲ ثانیه فکر کرد» جمع می‌شود.',
            'en' => 'A collapsible reasoning panel: a shimmering “Thinking…” header with the elapsed time, steps that arrive one by one, folding itself into “Thought for 12s” when done.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'steps', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر گام: id، label، status (pending · running · done · error) و detail اختیاری.',
                'en' => 'Each step: id, label, status (pending · running · done · error) and an optional detail.',
            ]],
            ['name' => 'active', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'هنوز فکر می‌کند: درخشش و ساعت روشن؛ با false زمان ثابت می‌شود و پنل جمع.',
                'en' => 'Still reasoning: shimmer and clock on; turning false freezes the time and folds the panel.',
            ]],
            ['name' => 'started-at / duration', 'type' => 'int', 'default' => 'null', 'note' => [
                'fa' => 'شروع (میلی‌ثانیه) برای ساعت زنده؛ یا مدت کل (ثانیه) برای ردی که از تاریخچه می‌آید.',
                'en' => 'Start (ms) for the live clock; or the total (seconds) for a trace loaded from history.',
            ]],
            ['name' => 'auto-collapse', 'type' => 'bool', 'default' => 'true', 'note' => [
                'fa' => 'جمع‌شدن خودکار پس از پایان فکر.',
                'en' => 'Fold away once reasoning ends.',
            ]],
            ['name' => 'labels', 'type' => 'array', 'default' => 'English', 'note' => [
                'fa' => 'thinking و thought (با :time) برای ترجمه.',
                'en' => 'thinking and thought (with :time) for translation.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::thinking-trace :active="$thinking" :started-at="$startedAtMs" :steps="$steps"
            :labels="['thinking' => 'در حال فکر…', 'thought' => ':time فکر کرد']" />
        BLADE,
    ],

    'streaming-text' => [
        'title' => ['fa' => 'متن جاری', 'en' => 'Streaming text'],
        'icon' => 'message',
        'oneLiner' => [
            'fa' => 'پاسخی که توکن‌به‌توکن می‌رسد: هر واژه با محوشدگی نرم پدیدار می‌شود، مکان‌نما چشمک می‌زند و ارجاع‌های [۱] منبع را در پاپ‌اوور باز می‌کنند — فارسی بدون شکستن حروف.',
            'en' => 'An answer arriving token by token: each word fades and un-blurs in, a caret blinks, and [1] citations open their source in a popover — Persian without breaking its letters.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'text', 'type' => 'string', 'default' => '""', 'note' => [
                'fa' => 'هرچه تاکنون رسیده؛ با [n] برای ارجاع. از Livewire رشدش دهید.',
                'en' => 'Everything received so far, with [n] for citations. Grow it from Livewire.',
            ]],
            ['name' => 'streaming', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'هنوز می‌رسد: مکان‌نما روشن و ارجاع نیمه‌کاره پنهان.',
                'en' => 'Still arriving: caret on, a half-arrived marker held back.',
            ]],
            ['name' => 'sources', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'منابع به ترتیب [1]، [2]…: title، url، domain، snippet.',
                'en' => 'Sources for [1], [2]…: title, url, domain, snippet.',
            ]],
            ['name' => 'simulate', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'متن کامل را سمت مرورگر پخش می‌کند (دمو، بازپخش با nx-replay).',
                'en' => 'Plays the full text out in the browser (demos; replay with nx-replay).',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::streaming-text :text="$answer" :streaming="$isStreaming" :sources="$sources" />
        BLADE,
    ],

    'tool-call' => [
        'title' => ['fa' => 'فراخوانی ابزار', 'en' => 'Tool call'],
        'icon' => 'zap',
        'oneLiner' => [
            'fa' => 'ردیف فراخوانی ابزار: نام، پیش‌نمایش آرگومان‌ها، وضعیت با جابه‌جایی آیکون (صف، چرخنده، موفق، خطا) و ورودی/خروجی JSON بازشونده.',
            'en' => 'A tool invocation row: name, an arguments preview, status with an icon swap (queued, spinner, success, error) and expandable input/output JSON.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'name / args', 'type' => 'string · mixed', 'default' => '—', 'note' => [
                'fa' => 'نام ابزار و آرگومان‌هایش (پیش‌نمایش یک‌خطی و ورودی کامل).',
                'en' => 'The tool and its arguments (one-line preview and the full input).',
            ]],
            ['name' => 'status', 'type' => 'string', 'default' => 'success', 'note' => [
                'fa' => 'queued · running · success · error — با رندر دوباره یا data-status عوض شود.',
                'en' => 'queued · running · success · error — change it by re-rendering or via data-status.',
            ]],
            ['name' => 'output / duration', 'type' => 'mixed · int', 'default' => 'null', 'note' => [
                'fa' => 'خروجی (JSON مرتب) و مدت اجرا به میلی‌ثانیه.',
                'en' => 'The output (pretty JSON) and how long it took, ms.',
            ]],
            ['name' => 'open', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'از ابتدا باز.',
                'en' => 'Start expanded.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::tool-call name="search_web" :args="['query' => 'Istanbul weather', 'limit' => 5]"
            :status="$status" :output="$results" :duration="840" />
        BLADE,
    ],

    'approval-card' => [
        'title' => ['fa' => 'کارت تأیید', 'en' => 'Approval card'],
        'icon' => 'shield',
        'oneLiner' => [
            'fa' => 'عامل اجازه می‌خواهد: خلاصه، جای جزئیات یا diff، و تأیید / رد / «همیشه مجاز» با میان‌بُرهای Y و N و A؛ سپس با جابه‌جایی آیکون به یک خط جمع می‌شود.',
            'en' => 'The agent asks permission: a summary, a details/diff slot, and Approve / Deny / “Always allow” with Y, N and A shortcuts; then it folds into one line with an icon swap.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'title / summary / tool', 'type' => 'string', 'default' => '—', 'note' => [
                'fa' => 'پرسش، توضیح کوتاه و ابزار یا فرمانی که اجرا می‌شود.',
                'en' => 'The question, a short explanation and the tool or command to run.',
            ]],
            ['name' => 'wire:model / state', 'type' => 'string', 'default' => 'pending', 'note' => [
                'fa' => 'pending · approved · denied · always — با wire:model دوطرفه.',
                'en' => 'pending · approved · denied · always — two-way with wire:model.',
            ]],
            ['name' => 'action', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'متد Livewire که با تصمیم صدا زده می‌شود؛ رویداد nx-approval هم ارسال می‌شود.',
                'en' => 'A Livewire method called with the decision; an nx-approval event is dispatched too.',
            ]],
            ['name' => 'allow-always / shortcuts / undoable', 'type' => 'bool', 'default' => 'true · true · false', 'note' => [
                'fa' => 'دکمهٔ «همیشه مجاز»، میان‌بُرهای صفحه‌کلید و دکمهٔ برگشت.',
                'en' => 'The “Always allow” button, keyboard shortcuts, and an Undo.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::approval-card title="Run the migration?" tool="php artisan migrate --force"
            summary="Adds two columns to orders." wire:model.live="decision" undoable>
            <x-nx::diff-viewer :old-text="$before" :new-text="$after" />
        </x-nx::approval-card>
        BLADE,
    ],

    'thinking-orbs' => [
        'title' => ['fa' => 'گوی‌های فکر', 'en' => 'Thinking orbs'],
        'icon' => 'cpu',
        'oneLiner' => [
            'fa' => 'لودر کوچک خالص CSS برای حالت‌های هوش مصنوعی: بیکار، در حال فکر، در حال صحبت و در حال شنیدن — با کاهش حرکت فقط محو می‌شوند.',
            'en' => 'A tiny pure-CSS loader for AI states: idle, thinking, speaking and listening — under reduced motion they only fade.',
        ],
        'js' => false,
        'docs' => null,
        'props' => [
            ['name' => 'state', 'type' => 'string', 'default' => 'thinking', 'note' => [
                'fa' => 'idle · thinking · speaking · listening.',
                'en' => 'idle · thinking · speaking · listening.',
            ]],
            ['name' => 'size', 'type' => 'string', 'default' => 'md', 'note' => [
                'fa' => 'sm · md · lg.',
                'en' => 'sm · md · lg.',
            ]],
            ['name' => 'label', 'type' => 'string', 'default' => 'state name', 'note' => [
                'fa' => 'متنی که صفحه‌خوان اعلام می‌کند (role=status).',
                'en' => 'What screen readers announce (role=status).',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::thinking-orbs state="speaking" size="sm" label="در حال صحبت" />
        BLADE,
    ],

    'code-block' => [
        'title' => ['fa' => 'بلوک کد', 'en' => 'Code block'],
        'icon' => 'file',
        'oneLiner' => [
            'fa' => 'کد با رنگ‌آمیزی سبک: سرتیتر نام فایل، نشان زبان، شمارهٔ خط، خطوط برجسته، خطوط diff سبز و قرمز، دکمهٔ کپی و شکستن خط — همیشه چپ‌به‌راست.',
            'en' => 'Syntax-light code: a filename header, language badge, line numbers, highlighted lines, green/red diff lines, a copy button and line wrapping — always left-to-right.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'code / language / filename', 'type' => 'string', 'default' => '—', 'note' => [
                'fa' => 'متن کد، زبان (برای رنگ‌آمیزی و نشان) و نام فایل.',
                'en' => 'The source, its language (colouring and badge) and the file name.',
            ]],
            ['name' => 'highlight', 'type' => 'string|array', 'default' => 'null', 'note' => [
                'fa' => 'خطوط برجسته: "4, 9-11" یا [4, [9, 11]].',
                'en' => 'Highlighted lines: "4, 9-11" or [4, [9, 11]].',
            ]],
            ['name' => 'diff', 'type' => 'bool', 'default' => 'auto', 'note' => [
                'fa' => 'خطوط + و − رنگی؛ برای language="diff" خودکار.',
                'en' => '+ and − lines coloured; automatic for language="diff".',
            ]],
            ['name' => 'wrap / line-numbers / max-height', 'type' => 'bool · bool · string', 'default' => 'false · true · null', 'note' => [
                'fa' => 'شکستن خط از ابتدا، نمایش شمارهٔ خط و بیشینهٔ ارتفاع پیش از اسکرول.',
                'en' => 'Wrap from the start, show line numbers, and the height before it scrolls.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::code-block filename="app/Models/Order.php" language="php" highlight="4, 9-11" :code="$source" />
        BLADE,
    ],

    'diff-viewer' => [
        'title' => ['fa' => 'نمایشگر تفاوت', 'en' => 'Diff viewer'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'تفاوت خط‌به‌خط دو متن (الگوریتم Myers) در نمای یکپارچه یا دوستونه، با بخش‌های بدون تغییرِ جمع‌شده و دکمهٔ «باز کردن»، و شمار افزوده‌ها و حذف‌ها.',
            'en' => 'A line diff of two texts (Myers) in unified or split view, with unchanged hunks folded behind “expand” and the additions/deletions counted.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'old-text / new-text', 'type' => 'string', 'default' => '""', 'note' => [
                'fa' => 'نسخهٔ پیشین و نسخهٔ تازه.',
                'en' => 'The previous and the new version.',
            ]],
            ['name' => 'view', 'type' => 'string', 'default' => 'unified', 'note' => [
                'fa' => 'unified یا split؛ کاربر در سرتیتر عوضش می‌کند.',
                'en' => 'unified or split; the reader switches it in the header.',
            ]],
            ['name' => 'context', 'type' => 'int', 'default' => '3', 'note' => [
                'fa' => 'چند خط بدون تغییر کنار هر تغییر بماند.',
                'en' => 'Unchanged lines kept beside each change.',
            ]],
            ['name' => 'filename / max-height', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'نام فایل در سرتیتر و بیشینهٔ ارتفاع.',
                'en' => 'The file name in the header and the maximum height.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::diff-viewer filename="config/cache.php" :old-text="$before" :new-text="$after" view="split" />
        BLADE,
    ],

    'json-viewer' => [
        'title' => ['fa' => 'نمایشگر JSON', 'en' => 'JSON viewer'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'درخت JSON جمع‌شونده با رنگ هر نوع، شمار فرزندان، کپی مسیر و مقدار، باز کردن همه و جست‌وجویی که راه رسیدن به نتیجه را باز و برجسته‌اش می‌کند.',
            'en' => 'A collapsible JSON tree with type colours, child counts, copy path/value, expand-all, and a search that opens the way to each hit and marks it.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'data', 'type' => 'mixed', 'default' => 'null', 'note' => [
                'fa' => 'آرایه/شیء PHP یا رشتهٔ JSON.',
                'en' => 'A PHP array/object or a JSON string.',
            ]],
            ['name' => 'depth', 'type' => 'int', 'default' => '1', 'note' => [
                'fa' => 'چند سطح در ابتدا باز باشد.',
                'en' => 'Levels open on first paint.',
            ]],
            ['name' => 'searchable / max-height', 'type' => 'bool · string', 'default' => 'true · 26rem', 'note' => [
                'fa' => 'جعبهٔ جست‌وجو و بیشینهٔ ارتفاع درخت.',
                'en' => 'The search box and the tree’s maximum height.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::json-viewer :data="$response" :depth="2" max-height="24rem" />
        BLADE,
    ],

    'terminal' => [
        'title' => ['fa' => 'ترمینال', 'en' => 'Terminal'],
        'icon' => 'command',
        'oneLiner' => [
            'fa' => 'سطح لاگ و ترمینال: خطوط جاری با سطح info / warn / error، دنبال‌کردن انتها با دکمهٔ «پرش به آخرین» وقتی بالا رفته‌اید، خط فرمان با مکان‌نمای چشمک‌زن و فیلتر سطح.',
            'en' => 'A log/terminal surface: streaming lines with info / warn / error levels, follow-the-end with “jump to latest” when scrolled up, a prompt with a blinking caret, and level filters.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'lines', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر خط: id، text، level اختیاری (از پیشوند ERROR: و [warn] و ✓ حدس زده می‌شود)، time.',
                'en' => 'Each line: id, text, optional level (guessed from ERROR: / [warn] / ✓), time.',
            ]],
            ['name' => 'name', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'نشانی برای رویداد nx-terminal-push تا از JS خط اضافه شود.',
                'en' => 'An address for the nx-terminal-push event, to append lines from JS.',
            ]],
            ['name' => 'script', 'type' => 'array', 'default' => 'null', 'note' => [
                'fa' => 'خطوطی که با تأخیر پخش می‌شوند (دمو و بازپخش).',
                'en' => 'Lines played out with delays (demos and replays).',
            ]],
            ['name' => 'prompt / command / height', 'type' => 'string', 'default' => '$ · "" · 18rem', 'note' => [
                'fa' => 'نماد فرمان (null برای پنهان)، متن تایپ‌شده و ارتفاع.',
                'en' => 'The prompt symbol (null hides it), the typed text, and the height.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::terminal title="deploy · production" name="deploy" :lines="$lines" height="20rem" />
        BLADE,
    ],
];
