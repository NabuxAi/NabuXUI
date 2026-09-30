<?php

/**
 * Demo manifest of the "glass" group — the liquid-glass family. The file name
 * is the group id, every key below is a demo slug and its scenarios live at
 * resources/views/demos/components/glass/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'شیشه', 'en' => 'Glass'],

    'glass-panel' => [
        'title' => ['fa' => 'پنل شیشه‌ای', 'en' => 'Glass panel'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'سطح شیشه با tint و rim نورگیر؛ refract پشتوانه را در لبه می‌خمَد و follow-light درخشش را دنبال موس می‌برد.',
            'en' => 'A glass surface with a tint and a light-catching rim; refract bends the backdrop at the edge and follow-light carries the gleam after the pointer.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'preset', 'type' => 'string', 'default' => 'regular', 'note' => [
                'fa' => 'ضخامت و تیرگی شیشه: regular · clear · frost · thick.',
                'en' => 'The glass’ body: regular · clear · frost · thick.',
            ]],
            ['name' => 'refract', 'type' => 'bool', 'default' => 'true', 'note' => [
                'fa' => 'خم‌کردن پشتوانه در لبه (رفتار glassPane؛ در Chromium دقیق‌تر).',
                'en' => 'Bends the backdrop at the rim (the glassPane behaviour; finest in Chromium).',
            ]],
            ['name' => 'iridescent', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'رنگین‌کمیِ لبه در جهت نور.',
                'en' => 'A rainbow shimmer along the rim.',
            ]],
            ['name' => 'follow-light', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'درخشش لبه دنبال اشاره‌گر می‌رود.',
                'en' => 'The rim’s gleam follows the pointer.',
            ]],
            ['name' => 'as', 'type' => 'string', 'default' => 'div', 'note' => [
                'fa' => 'تگ ریشه (مثلاً article یا section).',
                'en' => 'The root tag (e.g. article or section).',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::glass-panel preset="frost" iridescent follow-light class="player-card">
            …محتوای کارت…
        </x-nx::glass-panel>
        BLADE,
    ],

    'glass-button' => [
        'title' => ['fa' => 'دکمهٔ شیشه‌ای', 'en' => 'Glass button'],
        'icon' => 'zap',
        'oneLiner' => [
            'fa' => 'دکمهٔ پنل شیشه‌ای با فشرده‌شدن فنری؛ shimmer هنگام فشردن یک‌بار نور را دور rim می‌گرداند.',
            'en' => 'A pressable glass pane with springy press feedback; shimmer sends the light once around the rim when pressed.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'icon / icon-end', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'آیکون ابتدا یا انتهای برچسب؛ بدون متن، دکمهٔ فقط-آیکونی می‌شود (برچسب را aria-label بدهید).',
                'en' => 'An icon leading or trailing the label; with no text the button is icon-only (give it an aria-label).',
            ]],
            ['name' => 'size / preset', 'type' => 'string', 'default' => 'md · regular', 'note' => [
                'fa' => 'اندازه (sm · md · lg) و جنس شیشه (regular · clear · frost · thick).',
                'en' => 'Size (sm · md · lg) and the glass body (regular · clear · frost · thick).',
            ]],
            ['name' => 'shimmer', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'با هر فشار، نور یک دور کامل دور rim می‌رود.',
                'en' => 'Every press sends the light around the rim once.',
            ]],
            ['name' => 'iridescent / static', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'iridescent: لبهٔ رنگین‌کمون؛ static: کوچک‌شدن هنگام فشار خاموش.',
                'en' => 'iridescent: rainbow rim; static: no press scale.',
            ]],
            ['name' => 'href', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'با href دکمه به لینک تبدیل می‌شود.',
                'en' => 'With href the button becomes a link.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::glass-button icon="play" shimmer wire:click="play">پخش</x-nx::glass-button>
        <x-nx::glass-button icon="heart" iridescent>ذخیره</x-nx::glass-button>
        <x-nx::glass-button icon="settings" preset="thick" aria-label="تنظیمات" />
        BLADE,
    ],

    'glass-segmented' => [
        'title' => ['fa' => 'سگمنت شیشه‌ای', 'en' => 'Glass segmented'],
        'icon' => 'check',
        'oneLiner' => [
            'fa' => 'سگمنت انتخاب‌تکی که انتخابش یک عدسی قابل‌درگ است؛ روی رادیوهای بومی سوار است و با wire:model سینک می‌شود.',
            'en' => 'A single-choice segmented control whose selection is a lens you can drag; it rides native radios and syncs through wire:model.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'options', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'کلید → برچسب، یا آرایه‌ای با کلیدهای «label»، «icon» و «disabled».',
                'en' => 'Key → a label, or an array with «label», «icon» and «disabled».',
            ]],
            ['name' => 'value / wire:model', 'type' => 'string', 'default' => 'کلید اول', 'note' => [
                'fa' => 'گزینهٔ فعال؛ عدسی با فنر یا درگ به آن می‌رود.',
                'en' => 'The active option; the lens springs or is dragged to it.',
            ]],
            ['name' => 'preset / label', 'type' => 'string', 'default' => 'regular · null', 'note' => [
                'fa' => 'جنس شیشه و برچسب دسترس‌پذیری گروه.',
                'en' => 'The glass body and the group’s accessible label.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::glass-segmented label="دوره" :value="$state['period'] ?? 'week'" wire:model.live="state.period"
            :options="['day' => 'روز', 'week' => 'هفته', 'month' => 'ماه']" />
        BLADE,
    ],

    'glass-dock' => [
        'title' => ['fa' => 'داک شیشه‌ای', 'en' => 'Glass dock'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'داک شیشه‌ای که ذره‌بینش به آیتم زیر موس یا فوکوس می‌لغزد و راهنمای نام کنارش می‌آید.',
            'en' => 'A glass dock whose magnifier glides to the item under the pointer or focus, with the name tooltip alongside.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'items', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر آیتم: label، icon، tone (lapis · violet · cyan · gold · ink · rose · green)، href یا click (اکشن Livewire) و current.',
                'en' => 'Each item: label, icon, tone (lapis · violet · cyan · gold · ink · rose · green), href or click (a Livewire action), and current.',
            ]],
            ['name' => 'label / preset', 'type' => 'string', 'default' => 'null · regular', 'note' => [
                'fa' => 'برچسب دسترس‌پذیری و جنس شیشه.',
                'en' => 'The accessible label and the glass body.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::glass-dock label="برنامه‌ها" :items="[
            ['label' => 'فایل‌ها', 'icon' => 'folder', 'tone' => 'lapis', 'current' => true],
            ['label' => 'ایمیل', 'icon' => 'mail', 'tone' => 'cyan', 'click' => 'ping(\'ایمیل باز شد\')'],
            ['label' => 'موسیقی', 'icon' => 'music', 'tone' => 'rose'],
        ]" />
        BLADE,
    ],

    'glass-tab-bar' => [
        'title' => ['fa' => 'تب‌بار شیشه‌ای', 'en' => 'Glass tab bar'],
        'icon' => 'home',
        'oneLiner' => [
            'fa' => 'تب‌بار شیشه‌ای شناور؛ تب فعال زیر عدسی می‌نشیند و با اسکرولِ محتوا خودش را جمع می‌کند.',
            'en' => 'A floating glass tab bar; the active tab sits under the lens and it shrinks itself as the content scrolls.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'items', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر تب: value، label و icon؛ با href لینک می‌شود و بدون آن دکمه‌ای به value (و wire:model) بسته است.',
                'en' => 'Each tab: value, label and icon; with href it links, without it the button binds to value (and wire:model).',
            ]],
            ['name' => 'value / wire:model', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'تب فعال.',
                'en' => 'The active tab.',
            ]],
            ['name' => 'minimize-on-scroll', 'type' => 'bool · selector', 'default' => 'false', 'note' => [
                'fa' => 'true یعنی پنجره؛ یک سلکتور CSS یعنی همان ظرفِ اسکرول‌شونده.',
                'en' => 'true watches the window; a CSS selector watches that scrolling container.',
            ]],
            ['name' => 'compact / preset', 'type' => 'bool · string', 'default' => 'false · regular', 'note' => [
                'fa' => 'شروعِ جمع‌شده و جنس شیشه.',
                'en' => 'Start shrunk, and the glass body.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::glass-tab-bar label="اصلی" :value="$state['tab'] ?? 'home'" wire:model.live="state.tab"
            minimize-on-scroll=".feed-scroll" :items="[
                ['value' => 'home', 'label' => 'خانه', 'icon' => 'home'],
                ['value' => 'saved', 'label' => 'ذخیره‌ها', 'icon' => 'heart'],
            ]" />
        BLADE,
    ],

    'glass-switch' => [
        'title' => ['fa' => 'سوییچ شیشه‌ای', 'en' => 'Glass switch'],
        'icon' => 'settings',
        'oneLiner' => [
            'fa' => 'سوییچ روی checkbox بومی؛ knob هنگام گرفتن به شیشه بدل می‌شود و بقیهٔ ویژگی‌ها (wire:model، disabled) مستقیم روی ورودی می‌نشینند.',
            'en' => 'A switch on a native checkbox; the knob clears to glass while held and every other attribute (wire:model, disabled) lands on the input itself.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'label', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'برچسب متنِ کنار سوییچ (داخل label، پس کلیک روی متن هم کار می‌کند).',
                'en' => 'The text beside the switch (inside the label, so clicking the text works too).',
            ]],
            ['name' => 'wire:model / native', 'type' => '—', 'default' => '—', 'note' => [
                'fa' => 'همهٔ ویژگی‌های بومی checkbox پذیرفته می‌شود: checked، disabled، name و wire:model.',
                'en' => 'Every native checkbox attribute is accepted: checked, disabled, name and wire:model.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::glass-switch label="خبرنامهٔ هفتگی" wire:model.live="state.newsletter" />
        BLADE,
    ],

    'glass-slider' => [
        'title' => ['fa' => 'اسلایدر شیشه‌ای', 'en' => 'Glass slider'],
        'icon' => 'sliders',
        'oneLiner' => [
            'fa' => 'رِنج بومی که thumbش هنگام کشیدن ذره‌بین می‌شود؛ تیک‌ها زیر پر می‌شوند و عدد کنارش زنده می‌غلتد.',
            'en' => 'A native range whose thumb becomes a magnifier while dragged; the ticks fill under it and the figure beside it rolls live.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'value / wire:model', 'type' => 'number', 'default' => 'وسط بازه', 'note' => [
                'fa' => 'مقدار فعلی؛ با wire:model به سرور بسته می‌شود.',
                'en' => 'The current value; binds to the server through wire:model.',
            ]],
            ['name' => 'min / max / step', 'type' => 'number', 'default' => '0 · 100 · 1', 'note' => [
                'fa' => 'بازه و گام — همان ورودی range بومی.',
                'en' => 'The range and step — the native range input as is.',
            ]],
            ['name' => 'ticks / show-value / suffix', 'type' => 'int · bool · string', 'default' => "11 · true · ''", 'note' => [
                'fa' => 'شمار تیک‌ها، نمایش عدد و پسوند آن (مثل ٪).',
                'en' => 'Tick count, whether the figure shows, and its suffix (e.g. %).',
            ]],
            ['name' => 'label', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'برچسب دسترس‌پذیری ورودی.',
                'en' => 'The input’s accessible label.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::glass-slider label="بلندی صدا" :value="$state['volume'] ?? 40" wire:model.live="state.volume" suffix="٪" :ticks="9" />
        BLADE,
    ],

    'reading-glass' => [
        'title' => ['fa' => 'عدسی مطالعه', 'en' => 'Reading glass'],
        'icon' => 'search',
        'oneLiner' => [
            'fa' => 'عدسی روی محتوای زنده: بکشیدش، پرتش کنید یا با فلش‌های کیبورد حرکتش دهید — متنِ زیرش انتخاب‌پذیر و دکمه‌ها کلیک‌پذیر می‌مانند.',
            'en' => 'A lens over live content: drag it, throw it or move it with the arrow keys — the text under it stays selectable and the buttons clickable.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'size / shape', 'type' => 'int · string', 'default' => '144 · circle', 'note' => [
                'fa' => 'قطر عدسی به پیکسل و شکلش: circle · pill · rounded.',
                'en' => 'The lens diameter in px and its shape: circle · pill · rounded.',
            ]],
            ['name' => 'x / y', 'type' => 'float', 'default' => '0.5 · 0.5', 'note' => [
                'fa' => 'جای اولیهٔ عدسی به نسبت ظرف (۰ تا ۱).',
                'en' => 'The lens’ starting spot as a fraction of the container (0 to 1).',
            ]],
            ['name' => 'label', 'type' => 'string', 'default' => '«ذره‌بین»', 'note' => [
                'fa' => 'برچسب دسترس‌پذیری دکمهٔ عدسی.',
                'en' => 'The lens button’s accessible label.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::reading-glass :x="0.5" :y="0.35" size="160" shape="pill">
            …متن درشت یا جزئیات تصویر…
        </x-nx::reading-glass>
        BLADE,
    ],

    'liquid-ripple' => [
        'title' => ['fa' => 'موج مایع', 'en' => 'Liquid ripple'],
        'icon' => 'image',
        'oneLiner' => [
            'fa' => 'هر فشار، موجی در محتوای زندهٔ داخل می‌فرستد؛ قدرت و زمان موج قابل تنظیم است و محتوا مثل همیشه کار می‌کند.',
            'en' => 'Every press sends a ripple through the live content inside; strength and timing are tunable and the content keeps working as ever.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'strength', 'type' => 'number', 'default' => '18', 'note' => [
                'fa' => 'شدت موج (جابه‌جایی پیکسلی محتوا).',
                'en' => 'The ripple’s force (the content’s pixel travel).',
            ]],
            ['name' => 'duration', 'type' => 'number', 'default' => '1100', 'note' => [
                'fa' => 'زمان نشستن موج به میلی‌ثانیه.',
                'en' => 'How long the ripple settles, in milliseconds.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::liquid-ripple :strength="22">
            …هر محتوای زنده — گالری، فهرست، فرم…
        </x-nx::liquid-ripple>
        BLADE,
    ],
];
