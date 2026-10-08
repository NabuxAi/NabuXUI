<?php

/**
 * Demo manifest of the "ios" group — Apple’s Human Interface Guidelines on a
 * web canvas: the Liquid Glass material of iOS 26, the floating tab bar, the
 * large-title navigation bar, sheets with detents, alerts and action sheets,
 * context menus, the wheel picker, home-screen widgets, progress and pull-to-
 * refresh, and the classic controls. Scenarios live at
 * resources/views/demos/components/ios/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'آی‌او‌اس — شیشهٔ مایع', 'en' => 'iOS — Liquid Glass'],

    'controls' => [
        'title' => ['fa' => 'کنترل‌های کلاسیک', 'en' => 'Classic controls'],
        'icon' => 'sliders',
        'oneLiner' => [
            'fa' => 'سوییچ، اسلایدر، استپر، سگمنت و پیج‌کنترل آی‌او‌اس با فیزیک واقعی: سوییچ با فنر می‌لغزد، اسلایدر هنگام کشیدن ضخیم می‌شود و سگمنت گلیف انتخاب‌شده را با فنر جابه‌جا می‌کند.',
            'en' => 'iOS switch, slider, stepper, segmented control and page control with real physics: the switch springs, the slider thickens while dragged and the segment glides its glyph on a spring.',
        ],
        'js' => true,
        'docs' => 'https://developer.apple.com/design/human-interface-guidelines/switches',
        'props' => [
            ['name' => 'switch', 'type' => 'size', 'default' => "'51 × 31'", 'note' => [
                'fa' => 'ابعاد رسمی UISwitch؛ رنگ روشن #34C759 و تیره #30D158.',
                'en' => 'The official UISwitch size; on-tint #34C759, dark #30D158.',
            ]],
            ['name' => 'slider', 'type' => 'track', 'default' => "'4 → 10px'", 'note' => [
                'fa' => 'ریل هنگام لمس از ۴ به ۱۰ پیکسل ضخیم می‌شود.',
                'en' => 'The track fattens from 4 to 10px under the finger.',
            ]],
            ['name' => 'segmented', 'type' => 'behaviour', 'default' => "'sliding thumb'", 'note' => [
                'fa' => 'انگشت انتخاب مثل iOS 26 زیر گلیف می‌لغزد، نه کل سگمنت.',
                'en' => 'The selection thumb slides beneath the glyph, iOS 26 style.',
            ]],
        ],
        'code' => <<<'BLADE'
        <label class="ioctl-switch">
            <input type="checkbox" checked>
            <span class="ioctl-knob"></span>
        </label>

        <style>
        .ioctl-switch { position: relative; inline-size: 51px; block-size: 31px;
                        border-radius: 999px; background: #7878802e; transition: background .25s; }
        .ioctl-switch input:checked + .ioctl-knob { translate: 20px 0; }
        .ioctl-switch:has(input:checked) { background: #34C759; }
        .ioctl-knob { position: absolute; inset-block-start: 2px; inset-inline-start: 2px;
                      inline-size: 27px; aspect-ratio: 1; border-radius: 50%; background: #fff;
                      box-shadow: 0 3px 8px #00000024, 0 3px 1px #0000000f;
                      transition: translate .25s cubic-bezier(.3,.9,.4,1.1); }
        </style>
        BLADE,
    ],

    'tab-bar' => [
        'title' => ['fa' => 'تب‌بار شیشه‌ای شناور', 'en' => 'Floating glass tab bar'],
        'icon' => 'home',
        'oneLiner' => [
            'fa' => 'تب‌بار آی‌او‌اس ۲۶: قرص شیشهٔ مایع شناور روی محتوا، با هایلایت لبه، شکست نور و انتخابی که مثل قطره از تب به تب می‌غلتد؛ تب فعال دوباره‌لمس اسکرول‌به‌بالا می‌شود.',
            'en' => 'iOS 26’s tab bar: a floating liquid-glass pill over the content, with rim highlight, refracted light and a selection that rolls between tabs like a droplet; tapping the current tab scrolls to top.',
        ],
        'js' => true,
        'docs' => 'https://developer.apple.com/design/human-interface-guidelines/tab-bars',
        'props' => [
            ['name' => 'material', 'type' => 'variant', 'default' => "'regular'", 'note' => [
                'fa' => 'regular شیشهٔ معمولی، clear نسخهٔ کاملاً شفاف.',
                'en' => 'regular is the standard glass, clear the fully translucent one.',
            ]],
            ['name' => 'selection', 'type' => 'motion', 'default' => "'spring'", 'note' => [
                'fa' => 'قطرهٔ انتخاب با فنر می‌غلتد و در لبه‌ها کمی می‌غلتد.',
                'en' => 'The selection droplet rolls on a spring and squashes at the edges.',
            ]],
            ['name' => 'badge', 'type' => 'count', 'default' => 'null', 'note' => [
                'fa' => 'نشان قرمز سیستم روی تب‌هایی مثل Mail.',
                'en' => 'The system’s red badge on tabs like Mail.',
            ]],
        ],
        'code' => <<<'BLADE'
        <nav class="iotab">
            <button class="iotab-item" data-current>🏠<span class="iotab-label">خانه</span></button>
            <button class="iotab-item" data-badge="3">✉️</button>
        </nav>

        <style>
        .iotab { display: inline-flex; gap: 4px; padding: 6px; border-radius: 999px;
                 background: #ffffffd9; backdrop-filter: blur(24px) saturate(1.8);
                 box-shadow: 0 8px 32px #00000033, inset 0 0 0 .5px #ffffffb3; }
        .iotab-item[data-current] { background: #007aff1f; }
        .iotab-item { display: grid; place-items: center; gap: 2px; inline-size: 56px;
                      block-size: 44px; border-radius: 999px; color: #8E8E93; }
        .iotab-item[data-current] { color: #007AFF; }
        </style>
        BLADE,
    ],

    'nav-bar' => [
        'title' => ['fa' => 'نوار پیمایش با تیتر بزرگ', 'en' => 'Large-title nav bar'],
        'icon' => 'menu',
        'oneLiner' => [
            'fa' => 'الگوی UINavigationBar: تیتر ۳۴ پیکسلی که با اسکرول جمع و هم‌زمان به مرکز نوار منتقل می‌شود، خط جداکنندهٔ مو و پشت‌زمینهٔ شیشه‌ای فقط بعد از عبور محتوا.',
            'en' => 'The UINavigationBar pattern: a 34px title that shrinks and simultaneously slides to the bar’s centre on scroll, a hairline divider, and the frosted background only once content passes under.',
        ],
        'js' => true,
        'docs' => 'https://developer.apple.com/design/human-interface-guidelines/navigation-bars',
        'props' => [
            ['name' => 'large-title', 'type' => 'font', 'default' => "'34px / bold'", 'note' => [
                'fa' => 'تیتر بزرگ؛ هم‌زمان با جمع‌شدن به ۱۷ پیکسل نیمه‌پررنگ می‌رسد.',
                'en' => 'The large title; it settles at 17px as it collapses.',
            ]],
            ['name' => 'collapse', 'type' => 'trigger', 'default' => "'scroll-edge'", 'note' => [
                'fa' => 'آستانهٔ جمع‌شدن همان‌جاست که محتوا زیر نوار می‌رود.',
                'en' => 'The collapse threshold is where content passes under the bar.',
            ]],
            ['name' => 'back', 'type' => 'button', 'default' => "'‹'", 'note' => [
                'fa' => 'شورون بازگشت با نام صفحهٔ قبلی، در سمت شروع.',
                'en' => 'The back chevron with the previous screen’s name, on the leading side.',
            ]],
        ],
        'code' => <<<'BLADE'
        <header class="ionav" data-large>
            <button class="ionav-back">‹ تنظیمات</button>
            <span class="ionav-title">صدا</span>
        </header>

        <style>
        .ionav { position: sticky; inset-block-start: 0; display: grid; align-items: end;
                 block-size: 96px; padding: 8px 16px; background: #f9f9f900; }
        .ionav[data-large] .ionav-title { position: absolute; inset-inline: 16px; inset-block-end: 8px;
                 font: 700 34px/-apple-system; }
        .ionav:not([data-large]) { block-size: 44px; background: #f9f9f9d9;
                 backdrop-filter: blur(20px); border-block-end: .5px solid #00000029; }
        .ionav:not([data-large]) .ionav-title { font: 600 17px/-apple-system; text-align: center; }
        </style>
        BLADE,
    ],

    'search-field' => [
        'title' => ['fa' => 'فیلد جست‌وجو', 'en' => 'Search field'],
        'icon' => 'search',
        'oneLiner' => [
            'fa' => 'فیلد جست‌وجوی آی‌او‌اس: خاکستری گرد با ذره‌بین، که با فوکوس به متنِ کامل باز می‌شود، دکمهٔ لغو از سمت مقابل می‌آید و نتیجه‌های زنده زیرش می‌افتند.',
            'en' => 'The iOS search field: a rounded grey pill with a magnifier that expands on focus, the Cancel button slides in from the far side, and live results drop beneath.',
        ],
        'js' => true,
        'docs' => 'https://developer.apple.com/design/human-interface-guidelines/search-fields',
        'props' => [
            ['name' => 'resting', 'type' => 'style', 'default' => "'#7676801f'", 'note' => [
                'fa' => 'پرکنندهٔ خاکستری سیستمی با شعاع ۱۰ پیکسل.',
                'en' => 'The system grey fill at a 10px radius.',
            ]],
            ['name' => 'cancel', 'type' => 'button', 'default' => "'slide-in'", 'note' => [
                'fa' => 'دکمهٔ لغو فقط هنگام فوکوس ظاهر و فیلد را جمع می‌کند.',
                'en' => 'Cancel appears only on focus and squeezes the field.',
            ]],
            ['name' => 'clear', 'type' => 'button', 'default' => "'✕'", 'note' => [
                'fa' => 'دکمهٔ پاک‌کردن گرد فقط وقتی متن هست.',
                'en' => 'The round clear button, only while there is text.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="iosrch">
            <div class="iosrch-field">
                <svg>🔍</svg><input placeholder="جست‌وجو">
                <button class="iosrch-clear">✕</button>
            </div>
            <button class="iosrch-cancel">لغو</button>
        </div>

        <style>
        .iosrch { display: flex; gap: 8px; }
        .iosrch-field { flex: 1; display: flex; align-items: center; gap: 6px; block-size: 36px;
                        padding-inline: 8px; border-radius: 10px; background: #7676801f; }
        .iosrch:focus-within .iosrch-cancel { opacity: 1; inline-size: auto; }
        .iosrch-cancel { opacity: 0; inline-size: 0; overflow: hidden; color: #007AFF; }
        </style>
        BLADE,
    ],

    'sheet' => [
        'title' => ['fa' => 'شیت با توقف‌گاه‌ها', 'en' => 'Sheet with detents'],
        'icon' => 'chevron-up',
        'oneLiner' => [
            'fa' => 'شیت آی‌او‌اس با detentهای medium و large: بین دو ارتفاع می‌ایستد، زیرش صفحهٔ پدر کوچک و پله‌ای می‌شود، دستگیره بالا کشیدن را ممکن می‌کند و رهاکردن وسط راه به نزدیک‌ترین توقف‌گاه فنر می‌خورد.',
            'en' => 'The iOS sheet with medium and large detents: it parks between two heights, the parent page steps back and scales, the grabber drags, and letting go mid-way springs to the nearest detent.',
        ],
        'js' => true,
        'docs' => 'https://developer.apple.com/design/human-interface-guidelines/sheets',
        'props' => [
            ['name' => 'detents', 'type' => 'array', 'default' => "'[medium, large]'", 'note' => [
                'fa' => 'توقف‌گاه‌ها: medium نیمه، large کامل؛ ترکیب دلخواه هم می‌شود.',
                'en' => 'Detents: medium half-height, large full; mix them freely.',
            ]],
            ['name' => 'parent-scale', 'type' => 'transform', 'default' => "'0.92'", 'note' => [
                'fa' => 'صفحهٔ پدر عقب می‌رود، گرد می‌شود و ۸٪ کوچک می‌شود.',
                'en' => 'The parent page recedes, rounds and shrinks 8%.',
            ]],
            ['name' => 'grabber', 'type' => 'bar', 'default' => "'36 × 5'", 'note' => [
                'fa' => 'دستگیرهٔ ۳۶×۵ پیکسل با گرادیان خاکستری.',
                'en' => 'A 36×5px grabber with a grey gradient.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="iosheet" data-detent="medium">
            <span class="iosheet-grabber"></span>
            …content…
        </div>

        <style>
        .iosheet { position: fixed; inset-inline: 0; inset-block-end: 0; border-radius: 12px 12px 0 0;
                   background: #fff; box-shadow: 0 -8px 40px #00000033; }
        .iosheet[data-detent='medium'] { block-size: 50dvh; }
        .iosheet[data-detent='large'] { block-size: 97dvh; }
        .iosheet-grabber { display: block; inline-size: 36px; block-size: 5px; margin: 6px auto 0;
                          border-radius: 999px; background: linear-gradient(#a1a1a8, #68686e); }
        </style>
        BLADE,
    ],

    'alert' => [
        'title' => ['fa' => 'آلرت و اکشن‌شیت', 'en' => 'Alert & action sheet'],
        'icon' => 'alert-circle',
        'oneLiner' => [
            'fa' => 'دو گفت‌وگوی سیستمی آی‌او‌اس: آلرت شیشه‌ای با بلور پس‌زمینه و دکمه‌های تقسیم‌شده با خط مو — و اکشن‌شیت که از پایین با فهرست اقدام‌های عمودی و دکمهٔ جدا قرمز بالا می‌آید.',
            'en' => 'iOS’s two system dialogs: the frosted alert with divided buttons separated by hairlines — and the action sheet rising from the bottom with a vertical list and a separated red destructive action.',
        ],
        'js' => true,
        'docs' => 'https://developer.apple.com/design/human-interface-guidelines/alerts',
        'props' => [
            ['name' => 'alert', 'type' => 'width', 'default' => "'270px'", 'note' => [
                'fa' => 'عرض رسمی آلرت؛ دکمه‌ها نصف‌نصف یا عمودی اگر متن بلند باشد.',
                'en' => 'The official alert width; buttons split evenly, or stack for long text.',
            ]],
            ['name' => 'material', 'type' => 'blur', 'default' => "'40px'", 'note' => [
                'fa' => 'بلور سیستم روی پس‌زمینه — رنگ روشن و تیره خودکار.',
                'en' => 'The system blur over the backdrop — light and dark for free.',
            ]],
            ['name' => 'destructive', 'type' => 'color', 'default' => "'#FF3B30'", 'note' => [
                'fa' => 'اقدام مخرب قرمز سیستم؛ در تیره #FF453A.',
                'en' => 'The system red for destructive actions; #FF453A in dark.',
            ]],
        ],
        'code' => <<<'BLADE'
        <dialog class="ioalert">
            <div class="ioalert-body"><h2>حذف یادداشت؟</h2><p>این کار برگشتی ندارد.</p></div>
            <div class="ioalert-actions">
                <button>لغو</button><button data-destructive>حذف</button>
            </div>
        </dialog>

        <style>
        .ioalert { inline-size: 270px; border: none; border-radius: 14px; overflow: clip;
                   padding: 0; background: #f2f2f2e6; backdrop-filter: blur(40px) saturate(1.6); }
        .ioalert-actions { display: flex; border-block-start: .5px solid #3c3c432e; }
        .ioalert-actions button { flex: 1; block-size: 44px; color: #007AFF; }
        .ioalert-actions button + button { border-inline-start: .5px solid #3c3c432e; }
        [data-destructive] { color: #FF3B30; }
        </style>
        BLADE,
    ],

    'context-menu' => [
        'title' => ['fa' => 'منوی زمینه', 'en' => 'Context menu'],
        'icon' => 'command',
        'oneLiner' => [
            'fa' => 'لمس طولانی یا راست‌کلیک: کارت بزرگ می‌شود و شفاف، پس‌زمینه بلور مایع می‌گیرد و منوی گزینه‌ها با آیکن‌های رنگی بالا می‌آید؛ رهاکردن بیرون منو یعنی انصراف.',
            'en' => 'Long-press or right-click: the card swells and fades, the backdrop takes a liquid blur and the menu of tinted-icon actions rises; releasing outside the menu cancels.',
        ],
        'js' => true,
        'docs' => 'https://developer.apple.com/design/human-interface-guidelines/context-menus',
        'props' => [
            ['name' => 'preview', 'type' => 'style', 'default' => "'lift + blur'", 'note' => [
                'fa' => 'هدف بالا می‌آید، کمی بزرگ و تار؛ پس‌زمینه اشباع می‌گیرد.',
                'en' => 'The target lifts, scales slightly and blurs; the backdrop saturates.',
            ]],
            ['name' => 'trigger', 'type' => 'events', 'default' => "'press · right-click'", 'note' => [
                'fa' => 'لمس طولانی ۵۰۰ms یا کلیک راست؛ هر دو با پیش‌نمایش.',
                'en' => 'A 500ms long-press or right-click; both show the preview.',
            ]],
            ['name' => 'items', 'type' => 'rows', 'default' => "'icon + label'", 'note' => [
                'fa' => 'هر ردیف آیکن رنگ‌دار + برچسب؛ مخرب قرمز و جدا.',
                'en' => 'Each row a tinted icon + label; destructive in red, separated.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="ioctx" oncontextmenu="ioctxOpen(event)">
            <img src="card.png" alt="">
            <menu class="ioctx-menu">
                <button><span style="--tint:#34C759">⬆︎</span> اشتراک‌گذاری</button>
                <button data-destructive>حذف</button>
            </menu>
        </div>

        <style>
        .ioctx[data-open] { scale: 1.05; filter: blur(2px); opacity: .9; }
        .ioctx-menu { position: absolute; inset-block-start: calc(100% + 8px); inline-size: 250px;
                      padding: 6px; border-radius: 13px; background: #f0f0f0e6;
                      backdrop-filter: blur(40px); box-shadow: 0 10px 40px #0000003d; }
        .ioctx-menu button { display: flex; gap: 10px; align-items: center; block-size: 44px; }
        </style>
        BLADE,
    ],

    'picker' => [
        'title' => ['fa' => 'چرخ‌فلک انتخاب', 'en' => 'Wheel picker'],
        'icon' => 'settings',
        'oneLiner' => [
            'fa' => ' UIPickerView به‌شکل وب: دو ستون چرخان با اسکرول‌snap، ردیف‌های کناری کوچک و محو، خطوط انتخاب مو، و بزرگ‌نمایی ردیف وسط که واقعاً از فاصله محاسبه می‌شود.',
            'en' => 'UIPickerView on the web: two snapping wheel columns, side rows shrinking and fading, hairline selection lines, and a centre-row magnification genuinely computed from distance.',
        ],
        'js' => true,
        'docs' => 'https://developer.apple.com/design/human-interface-guidelines/pickers',
        'props' => [
            ['name' => 'columns', 'type' => 'count', 'default' => "'1–3'", 'note' => [
                'fa' => 'ستون‌های هم‌عرض یا خودکار؛ ساعت و دقیقه کنار هم.',
                'en' => 'Equal or auto columns; hour next to minute.',
            ]],
            ['name' => 'row-height', 'type' => 'px', 'default' => "'40'", 'note' => [
                'fa' => 'ارتفاع رسمی ردیف؛ شش ردیف در ارتفاع ۲۴۰ پیکسلی دیده می‌شود.',
                'en' => 'The official row height; six rows visible at 240px tall.',
            ]],
            ['name' => 'perspective', 'type' => 'effect', 'default' => "'scale + fade'", 'note' => [
                'fa' => 'ردیف‌های دور کوچک‌تر و کم‌رنگ‌تر می‌شوند.',
                'en' => 'Distant rows shrink and fade.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="iopick">
            <div class="iopick-wheel" style="block-size: 200px">
                <div class="iopick-row">۰۱</div> …
            </div>
            <span class="iopick-line"></span><span class="iopick-line"></span>
        </div>

        <style>
        .iopick { position: relative; display: flex; gap: 16px; }
        .iopick-wheel { overflow-y: auto; scroll-snap-type: y mandatory; inline-size: 88px; text-align: center; }
        .iopick-row { display: grid; place-items: center; block-size: 40px; scroll-snap-align: center;
                      font: 400 22px/-apple-system; transition: scale .15s, opacity .15s; }
        .iopick-line { position: absolute; inset-inline: 0; block-size: .5px; background: #3c3c431f; }
        </style>
        BLADE,
    ],

    'widgets' => [
        'title' => ['fa' => 'ویجت‌های صفحهٔ اصلی', 'en' => 'Home-screen widgets'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'ویجت‌های iOS در سه اندازهٔ گرید ۴×۲: آب‌وهوای چندساعته، تقویم امروز، باتری‌ها و پیشنهادهای کوتاه؛ با استندبایِ شیشه‌ای و گوشه‌های پیوستهٔ صفحه.',
            'en' => 'iOS widgets in the 4×2 grid sizes: an hourly weather, today’s calendar, batteries and short suggestions; glassy standby corners and the home screen’s continuous radius.',
        ],
        'js' => true,
        'docs' => 'https://developer.apple.com/design/human-interface-guidelines/widgets',
        'props' => [
            ['name' => 'sizes', 'type' => 'grid', 'default' => "'2×2 · 4×2 · 4×4'", 'note' => [
                'fa' => 'small، medium و large روی گرید آی‌او‌اس.',
                'en' => 'Small, medium and large on the iOS grid.',
            ]],
            ['name' => 'radius', 'type' => 'length', 'default' => "'22px'", 'note' => [
                'fa' => 'گردی رسمی ویجت که با گوشهٔ صفحه یکی است.',
                'en' => 'The official widget radius matching the screen’s corners.',
            ]],
            ['name' => 'backgrounds', 'type' => 'style', 'default' => "'tinted · photo'", 'note' => [
                'fa' => 'پس‌زمینهٔ روشن، تیره یا تصویری؛ آیکن‌ها همیشه SF-مانند.',
                'en' => 'Light, dark or photo backgrounds; icons always SF-like.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="iowdg" data-size="medium">
            <header><span>تهران</span><b>۱۸°</b></header>
            <div class="iowdg-hours">…hourly strip…</div>
        </div>

        <style>
        .iowdg { border-radius: 22px; padding: 14px; background: #fff;
                 box-shadow: 0 1px 3px #0000001a; font-family: -apple-system, system-ui; }
        .iowdg[data-size='small'] { inline-size: 158px; aspect-ratio: 1; }
        .iowdg[data-size='medium'] { inline-size: 334px; block-size: 158px; }
        </style>
        BLADE,
    ],

    'progress' => [
        'title' => ['fa' => 'پیشرفت و کشیدن برای تازه‌سازی', 'en' => 'Progress & pull-to-refresh'],
        'icon' => 'trend-up',
        'oneLiner' => [
            'fa' => 'شاخص فعالیت (چرخ iOS با ۱۲ پره)، نوار پیشرفت نازک، حلقه با درصد — و کشیدن برای تازه‌سازی واقعی: اسپینر از پشت لبه بالا می‌آید، کش می‌آید و رها که کنی می‌چرخد.',
            'en' => 'The activity indicator (iOS’s 12-blade spinner), the hairline progress bar, a ringed percent — and real pull-to-refresh: the spinner rises from behind the edge, stretches, and spins when released.',
        ],
        'js' => true,
        'docs' => 'https://developer.apple.com/design/human-interface-guidelines/progress-indicators',
        'props' => [
            ['name' => 'spinner', 'type' => 'blades', 'default' => "'12'", 'note' => [
                'fa' => '۱۲ پره با تاخیر پلکانی؛ هر پره محو می‌شود نه می‌چرخد.',
                'en' => '12 blades with staggered delay; each fades, none rotates.',
            ]],
            ['name' => 'pull', 'type' => 'threshold', 'default' => "'80px'", 'note' => [
                'fa' => 'آستانهٔ رها کردن؛ زیر آستانه فنر برمی‌گردد.',
                'en' => 'The release threshold; under it the spring pulls back.',
            ]],
            ['name' => 'bar', 'type' => 'height', 'default' => "'2px'", 'note' => [
                'fa' => 'نوار پیشرفت مو‌نازک با گردی کامل.',
                'en' => 'A hairline 2px progress bar, fully rounded.',
            ]],
        ],
        'code' => <<<'BLADE'
        <span class="ioprg-spinner" role="status" aria-label="در حال بارگذاری">
            <i></i><i></i>…12 blades…
        </span>

        <style>
        .ioprg-spinner { position: relative; display: inline-block; inline-size: 20px; aspect-ratio: 1; }
        .ioprg-spinner i { position: absolute; inset: 0; border-radius: 50%;
                           animation: ioprg-fade 1s linear infinite; }
        .ioprg-spinner i::before { content: ''; display: block; margin: 1px auto; inline-size: 2px;
                                   block-size: 5px; border-radius: 999px; background: #8E8E93; }
        .ioprg-spinner i:nth-child(2) { rotate: 30deg; animation-delay: .08s; }
        </style>
        BLADE,
    ],

    'liquid-glass' => [
        'title' => ['fa' => 'متریال شیشهٔ مایع', 'en' => 'Liquid Glass material'],
        'icon' => 'sparkles',
        'oneLiner' => [
            'fa' => 'خودِ متریال iOS 26 به‌تنهایی: دو نسخهٔ regular و clear روی پس‌زمینهٔ رنگی زنده، با هایلایت لبهٔ نوری، شکست و درخششی که با اشاره‌گر می‌چرخد — برای اینکه ببینید هر کی به چی می‌خورد.',
            'en' => 'The iOS 26 material on its own: regular and clear variants over a live colour field, with a lit rim, refraction and a gleam that follows the pointer — so you can judge each on its own.',
        ],
        'js' => true,
        'docs' => 'https://developer.apple.com/documentation/technologyoverviews/liquid-glass',
        'props' => [
            ['name' => 'regular', 'type' => 'recipe', 'default' => "'blur 24 + tint'", 'note' => [
                'fa' => 'بلور ۲۴ + رنگ‌مایهٔ سفید ۷۰٪ + سایهٔ نرم؛ خوانا روی هرچیز.',
                'en' => '24px blur + a 70% white tint + soft shadow; legible on anything.',
            ]],
            ['name' => 'clear', 'type' => 'recipe', 'default' => "'blur 6'", 'note' => [
                'fa' => 'تقریباً شفاف؛ فقط برای سطوح بزرگ و پرتحرک.',
                'en' => 'Nearly transparent; only for large, lively surfaces.',
            ]],
            ['name' => 'rim', 'type' => 'highlight', 'default' => "'inset 1px'", 'note' => [
                'fa' => 'لبهٔ نورانی داخلی که جهت نور می‌گیرد.',
                'en' => 'An inner lit rim that catches the light’s direction.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="ioglass" data-kind="regular" onpointermove="ioGleam(event)">
            <b>Regular</b>
        </div>

        <style>
        .ioglass { display: grid; place-items: center; inline-size: 200px; aspect-ratio: 1.6;
                   border-radius: 24px; background: #ffffffa6;
                   backdrop-filter: blur(24px) saturate(1.8);
                   box-shadow: 0 8px 32px #00000026, inset 0 0 0 .5px #ffffffcc; }
        .ioglass[data-kind='clear'] { background: #ffffff33; backdrop-filter: blur(6px) saturate(1.4); }
        </style>
        BLADE,
    ],

    'home-screen' => [
        'title' => ['fa' => 'صفحهٔ اصلی و کنترل سنتر', 'en' => 'Home screen & Control Centre'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'شل کامل iOS در یک گوشی: دو صفحهٔ آیکن با داک شیشه‌ای و نقطه‌های صفحه، باز شدن اپ با زوم، برگشت با کشیدن نوار خانه، حالت لرزش برای جابه‌جایی آیکن‌ها، و کنترل سنتر که از بالا کشیده می‌شود و روشنایی صفحه را واقعاً کم می‌کند.',
            'en' => 'The full iOS shell in one phone: two icon pages with a glass dock and page dots, apps zooming open, home-bar swipe to return, jiggle edit mode for the icons, and a Control Centre that pulls down and really dims the wallpaper.',
        ],
        'js' => true,
        'docs' => 'https://developer.apple.com/design/human-interface-guidelines/home-screen',
        'props' => [
            ['name' => 'pages', 'type' => 'count', 'default' => "'2'", 'note' => [
                'fa' => 'کشیدن افقی بین صفحه‌ها با فنر و لاستیک‌بند لبه‌ها؛ نقطه‌ها هم‌گام‌اند.',
                'en' => 'Drag horizontally between pages with a spring and edge rubber-band; dots stay in sync.',
            ]],
            ['name' => 'app-open', 'type' => 'transition', 'default' => "'zoom'", 'note' => [
                'fa' => 'اپ با مقیاس‌شدن از جای آیکن باز می‌شود؛ نوار خانه برمی‌گرداند.',
                'en' => 'Apps scale open from their icon; the home bar brings you back.',
            ]],
            ['name' => 'jiggle', 'type' => 'mode', 'default' => "'long-press'", 'note' => [
                'fa' => 'لمس طولانی آیکن‌ها را می‌لرزاند و ضربدرهای حذف می‌آورد.',
                'en' => 'A long-press makes icons jiggle and brings the delete ✕ badges.',
            ]],
            ['name' => 'control-centre', 'type' => 'gesture', 'default' => "'pull-down'", 'note' => [
                'fa' => 'کشیدن از گوشهٔ بالایی پنل شیشه‌ای را می‌آورد؛ روشنایی پس‌زمینه را کم می‌کند.',
                'en' => 'Dragging from the top corner brings the glass panel; brightness dims the wallpaper.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="iohome" x-data="{ page: 0, app: null, jiggle: false, cc: false }">
            <div class="iohome-grid" :style="`translate: ${-page * 100}%`">
                …two pages of icons…
            </div>
            <div class="iohome-dock">…4 glass apps…</div>
            <div class="iohome-bar" x-on:pointerdown="swipeHome($event)"></div>
            <div class="iohome-cc" x-show="cc">…toggles + sliders…</div>
        </div>

        <style>
        .iohome { position: relative; overflow: clip; border-radius: 2.75rem;
                  background: linear-gradient(165deg, #38425e, #131722); }
        .iohome-grid { display: flex; transition: translate .4s cubic-bezier(.3,.9,.3,1); }
        .iohome[dims]::after { content: ''; position: absolute; inset: 0;
                               background: #000; opacity: var(--dims, 0); pointer-events: none; }
        </style>
        BLADE,
    ],
];
