<?php

/**
 * Demo manifest of the "windows" group — Fluent 2, the design language of
 * Windows 11: the rounded window with caption buttons and snap layouts, the
 * mica and acrylic materials, the control set, the command bar, the
 * NavigationView, TabView, dialogs and info bars, progress, and the expander.
 * Scenarios live at
 * resources/views/demos/components/windows/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'ویندوز — فلوئنت', 'en' => 'Windows — Fluent'],

    'window-chrome' => [
        'title' => ['fa' => 'قاب پنجرهٔ ویندوز ۱۱', 'en' => 'Windows 11 window chrome'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'قاب پنجرهٔ وین ۱۱: گوشه‌های ۸ پیکسلی، دکمه‌های caption با ناحیهٔ ۴۶×۳۲ و قرمز بستن، منوی snap layouts هنگام نگه‌داشتن ماکزیمایز و پس‌زمینهٔ میکا که با کاغذدیواری رنگ می‌گیرد.',
            'en' => 'The Win11 window frame: 8px corners, caption buttons at 46×32 with the red close, the snap-layouts menu on maximize hover, and a mica background tinted by the wallpaper.',
        ],
        'js' => true,
        'docs' => 'https://learn.microsoft.com/windows/apps/design/basics/designing-for-windows-11',
        'props' => [
            ['name' => 'caption', 'type' => 'size', 'default' => "'46 × 32'", 'note' => [
                'fa' => 'ناحیهٔ رسمی دکمه‌ها؛ بستن hover قرمز #C42B1C می‌شود.',
                'en' => 'The official button hit area; close turns #C42B1C on hover.',
            ]],
            ['name' => 'radius', 'type' => 'length', 'default' => "'8px'", 'note' => [
                'fa' => 'گردی گوشهٔ پنجره از ویندوز ۱۱ به بعد.',
                'en' => 'The window corner radius since Windows 11.',
            ]],
            ['name' => 'snap', 'type' => 'flyout', 'default' => "'6 layouts'", 'note' => [
                'fa' => 'شش چیدمان snap در فای‌اوت؛ هر خانه قابل انتخاب است.',
                'en' => 'Six snap layouts in the flyout; each zone is pickable.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="flwin">
            <header class="flwin-title">
                <span>Notepad — یادداشت‌ها</span>
                <div class="flwin-caption">
                    <button aria-label="کوچک">▁</button>
                    <button aria-label="بزرگ" data-snap>□</button>
                    <button aria-label="بستن" data-close>✕</button>
                </div>
            </header>
        </div>

        <style>
        .flwin { border-radius: 8px; overflow: clip; box-shadow: 0 32px 64px #0000004d,
                 0 0 0 1px #00000026; background: #f3f3f3; }
        .flwin-caption { display: flex; margin-inline-start: auto; }
        .flwin-caption button { inline-size: 46px; block-size: 32px; }
        .flwin-caption [data-close]:hover { background: #C42B1C; color: #fff; }
        </style>
        BLADE,
    ],

    'acrylic-mica' => [
        'title' => ['fa' => 'متریال آکریلیک و میکا', 'en' => 'Acrylic & mica materials'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'دو متریال امضای فلوئنت روی یک کاغذدیواری زنده: میکا رنگ دسکتاپ را فقط ته‌مایه می‌گیرد و برای پنجره‌هاست، آکریلیک پشتِ خودش را واقعاً بلور می‌کند و برای فای‌اوت‌ها.',
            'en' => 'Fluent’s two signature materials over a live wallpaper: mica only tints the desktop beneath (for windows), acrylic truly blurs what is behind it (for flyouts).',
        ],
        'js' => true,
        'docs' => 'https://learn.microsoft.com/windows/apps/design/signature-experiences/materials',
        'props' => [
            ['name' => 'mica', 'type' => 'recipe', 'default' => "'tint 50%'", 'note' => [
                'fa' => 'تینت ۵۰٪ رنگ دسکتاپ + سطح خاکستری؛ بلور ندارد.',
                'en' => 'A 50% desktop tint over a neutral; no blur.',
            ]],
            ['name' => 'acrylic', 'type' => 'recipe', 'default' => "'blur 30 + noise'", 'note' => [
                'fa' => 'بلور ۳۰ + اشباع ۱۲۵٪ + نویز ظریف برای جلوگیری از banding.',
                'en' => '30px blur + 125% saturation + fine noise to fight banding.',
            ]],
            ['name' => 'fallback', 'type' => 'behavior', 'default' => "'opaque'", 'note' => [
                'fa' => 'هرجا backdrop-filter نبود، رنگ ثابت جایگزین می‌شود.',
                'en' => 'Where backdrop-filter is missing, a solid colour stands in.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="flmat" data-kind="mica">میکا — برای بدنهٔ پنجره</div>
        <div class="flmat" data-kind="acrylic">آکریلیک — برای فای‌اوت</div>

        <style>
        .flmat { padding: 20px; border-radius: 8px; border: 1px solid #ffffff6b; }
        .flmat[data-kind='mica'] { background: #f3f3f3d9; }
        .flmat[data-kind='acrylic'] { background: #f9f9f9cc;
             backdrop-filter: blur(30px) saturate(125%); }
        </style>
        BLADE,
    ],

    'buttons' => [
        'title' => ['fa' => 'دکمه‌های فلوئنت', 'en' => 'Fluent buttons'],
        'icon' => 'zap',
        'oneLiner' => [
            'fa' => 'خانوادهٔ دکمهٔ وین ۱۱: استاندارد با خط دور و گردی ۴ پیکسل، accent آبی، subtle بدون پس‌زمینه، toggle دو حالته، split با فلش جدا و hyperlink؛ همه با لایهٔ حالت ۴٪.',
            'en' => 'Win11’s button family: standard with its 4px-radius stroke, the blue accent, borderless subtle, the two-state toggle, the split with its divided chevron and the hyperlink; all with a 4% state layer.',
        ],
        'js' => true,
        'docs' => 'https://learn.microsoft.com/windows/apps/design/controls/buttons',
        'props' => [
            ['name' => 'variant', 'type' => 'string', 'default' => "'standard'", 'note' => [
                'fa' => 'standard · accent · subtle · toggle · split · hyperlink.',
                'en' => 'standard · accent · subtle · toggle · split · hyperlink.',
            ]],
            ['name' => 'radius', 'type' => 'length', 'default' => "'4px'", 'note' => [
                'fa' => 'گردی کنترل‌های وین ۱۱؛ کمتر از همه شبیه وب.',
                'en' => 'The Win11 control radius; deliberately modest.',
            ]],
            ['name' => 'accent', 'type' => 'color', 'default' => "'#005FB8'", 'note' => [
                'fa' => 'accent روشن #005FB8 و تیره #4CC2FF — رنگ سیستمی ویندوز.',
                'en' => 'Light accent #005FB8, dark #4CC2FF — Windows’ system colour.',
            ]],
        ],
        'code' => <<<'BLADE'
        <button class="flbtn">استاندارد</button>
        <button class="flbtn" data-accent>ذخیره</button>
        <button class="flbtn" data-subtle>بی‌پس‌زمینه</button>
        <button class="flbtn" data-split>ادامه <span>▾</span></button>

        <style>
        .flbtn { min-inline-size: 80px; block-size: 32px; border-radius: 4px;
                 border: 1px solid #0000001f; background: #fdfdfdfd; }
        .flbtn:hover { background: #f6f6f6; }
        .flbtn:active { background: #f5f5f5; color: #616161; }
        .flbtn[data-accent] { background: #005FB8; border-color: #005FB8; color: #fff; }
        .flbtn[data-split] span { margin-inline-start: 12px; padding-inline-start: 12px;
                                  border-inline-start: 1px solid #00000026; }
        </style>
        BLADE,
    ],

    'selection' => [
        'title' => ['fa' => 'کنترل‌های انتخاب', 'en' => 'Selection controls'],
        'icon' => 'check',
        'oneLiner' => [
            'fa' => 'چک‌باکس، رادیو، سوییچ، اسلایدر، ریتینگ ستاره‌ای و Number Box به استایل فلوئنت ۲: لبه‌های در حال آشکار شدن، سوییچ ۴۰×۲۰ و ستاره‌هایی که با کیبورد هم ستاره‌به‌ستاره می‌روند.',
            'en' => 'Checkbox, radio, switch, slider, star rating and the number box in Fluent 2 style: revealing borders, the 40×20 switch, and stars you can walk with the keyboard.',
        ],
        'js' => true,
        'docs' => 'https://learn.microsoft.com/windows/apps/design/controls/checkbox',
        'props' => [
            ['name' => 'reveal', 'type' => 'border', 'default' => "'hover 1px'", 'note' => [
                'fa' => 'لبهٔ کنترل در hover از خاکستری به تیره می‌رود و زیر فوکوس دوخط آبی می‌شود.',
                'en' => 'The border darkens on hover and doubles blue on focus.',
            ]],
            ['name' => 'switch', 'type' => 'size', 'default' => "'40 × 20'", 'note' => [
                'fa' => 'سوییچ فلوئنت کشیده و لاغر؛ پر کردن با انیمیشن عرض.',
                'en' => 'Fluent’s long slim switch; the fill wipes across.',
            ]],
            ['name' => 'number-box', 'type' => 'spin', 'default' => "'up/down'", 'note' => [
                'fa' => 'دکمه‌های بالا/پایین داخل فیلد، با نگه‌داشتن تکرار می‌شوند.',
                'en' => 'Up/down buttons inside the field; hold to repeat.',
            ]],
        ],
        'code' => <<<'BLADE'
        <label class="flsel-check"><input type="checkbox"><span>✓</span> حالت شب</label>
        <label class="flsel-switch"><input type="checkbox"><span class="flsel-track"></span></label>

        <style>
        .flsel-check span { display: grid; place-items: center; inline-size: 20px; block-size: 20px;
                            border-radius: 4px; border: 1px solid #8a8a8a; background: #ffffffb3;
                            color: transparent; }
        .flsel-check input:checked + span { background: #005FB8; border-color: #005FB8; color: #fff; }
        .flsel-track { inline-size: 40px; block-size: 20px; border-radius: 999px;
                       border: 1px solid #8a8a8a; background: transparent; }
        .flsel-switch input:checked + .flsel-track { background: #005FB8; }
        </style>
        BLADE,
    ],

    'command-bar' => [
        'title' => ['fa' => 'نوار فرمان', 'en' => 'Command bar'],
        'icon' => 'menu',
        'oneLiner' => [
            'fa' => 'CommandBar اکسپلورر: دکمه‌های آیکن‌دار با متن که در جا تنگ زیرهم می‌شوند، دکمهٔ سرریز «⋯» با منوی شیشه‌ای، جداکننده‌های عمودی و حالت نوار منوی کلاسیک File/Edit/View.',
            'en' => 'Explorer’s command bar: icon+label buttons that stack vertically when squeezed, a «⋯» overflow with an acrylic menu, vertical separators, and the classic File/Edit/View menu bar mode.',
        ],
        'js' => true,
        'docs' => 'https://learn.microsoft.com/windows/apps/design/controls/command-bar',
        'props' => [
            ['name' => 'overflow', 'type' => 'menu', 'default' => "'auto'", 'note' => [
                'fa' => 'هرچه جا نشود در «⋯» می‌افتد؛ با تغییر اندازه زنده جابه‌جا می‌شود.',
                'en' => 'What does not fit lands in «⋯»; it repacks live on resize.',
            ]],
            ['name' => 'labels', 'type' => 'layout', 'default' => "'side · below'", 'note' => [
                'fa' => 'لیبل کنار آیکن (پیش‌فرض) یا زیر آن در حالت فشرده.',
                'en' => 'Labels beside the icon (default) or under it, compact.',
            ]],
            ['name' => 'menu-bar', 'type' => 'mode', 'default' => "'optional'", 'note' => [
                'fa' => 'ردیف File/Edit/View کلاسیک با زیرمنوهای شیشه‌ای.',
                'en' => 'The classic File/Edit/View row with acrylic submenus.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="flcmd">
            <button class="flcmd-btn"><span>✂︎</span> برش</button>
            <button class="flcmd-btn"><span>⧉</span> کپی</button>
            <span class="flcmd-sep"></span>
            <button class="flcmd-more" aria-label="بیشتر">⋯</button>
        </div>

        <style>
        .flcmd { display: flex; align-items: center; gap: 4px; padding: 4px; }
        .flcmd-btn { display: inline-flex; align-items: center; gap: 8px; block-size: 36px;
                     padding-inline: 12px; border-radius: 4px; }
        .flcmd-btn:hover { background: #0000000a; }
        .flcmd-sep { inline-size: 1px; block-size: 24px; margin-inline: 4px; background: #00000029; }
        </style>
        BLADE,
    ],

    'navigation-view' => [
        'title' => ['fa' => 'NavigationView', 'en' => 'NavigationView'],
        'icon' => 'home',
        'oneLiner' => [
            'fa' => 'ستون فقرات اپ‌های وین ۱۱: همبرگر که پنل را جمع و باز می‌کند، آیتم‌های انتخاب با نشانگر چسبیده به لبه، بخش تنظیمات پایین، و Tooltip آیکن‌ها در حالت جمع.',
            'en' => 'The backbone of Win11 apps: a hamburger that collapses the pane, selection markers glued to the edge, settings pinned at the bottom, and icon tooltips while collapsed.',
        ],
        'js' => true,
        'docs' => 'https://learn.microsoft.com/windows/apps/design/controls/navigationview',
        'props' => [
            ['name' => 'display-mode', 'type' => 'state', 'default' => "'expanded · compact'", 'note' => [
                'fa' => 'expanded پنل باز با لیبل؛ compact فقط ۴۸ پیکسل آیکن.',
                'en' => 'expanded keeps labels; compact is a 48px icon rail.',
            ]],
            ['name' => 'selection-indicator', 'type' => 'pill', 'default' => "'3×16'", 'note' => [
                'fa' => 'قرص ۳×۱۶ چسبیده به لبهٔ شروع آیتم فعال.',
                'en' => 'A 3×16 pill glued to the start edge of the active item.',
            ]],
            ['name' => 'footer', 'type' => 'area', 'default' => "'settings'", 'note' => [
                'fa' => 'پایین پنل همیشه تنظیمات و آیتم‌های ثابت.',
                'en' => 'Settings and pinned items live at the pane’s foot.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="flnav">
            <aside class="flnav-pane">
                <button class="flnav-item" data-current><span class="flnav-pill"></span>🏠 خانه</button>
                <button class="flnav-item">📁 فایل‌ها</button>
            </aside>
            <main>…</main>
        </div>

        <style>
        .flnav-pane { inline-size: 256px; padding: 4px 12px; }
        .flnav-item { position: relative; display: flex; gap: 12px; align-items: center;
                      block-size: 36px; padding-inline: 12px; border-radius: 4px; }
        .flnav-pill { position: absolute; inset-inline-start: 0; inline-size: 3px; block-size: 16px;
                      border-radius: 999px; background: transparent; }
        .flnav-item[data-current] { background: #0000000f; }
        .flnav-item[data-current] .flnav-pill { background: #005FB8; }
        </style>
        BLADE,
    ],

    'tab-view' => [
        'title' => ['fa' => 'TabView', 'en' => 'TabView'],
        'icon' => 'file',
        'oneLiner' => [
            'fa' => 'تب‌های وین ۱۱ (کارت پنجره‌ها): تب‌های گرد با آیکن سند، دکمهٔ بستن فقط زیر اشاره‌گر، دکمهٔ «+» برای تب جدید، و نمایشگر پیش‌نمایش تب هنگام نگه‌داشتن.',
            'en' => 'Win11 tabs (the window cards): rounded tabs with document icons, a close button only under the pointer, a «+» for new tabs, and a preview peek while you hold.',
        ],
        'js' => true,
        'docs' => 'https://learn.microsoft.com/windows/apps/design/controls/tab-view',
        'props' => [
            ['name' => 'tab', 'type' => 'shape', 'default' => "'4px'", 'note' => [
                'fa' => 'گردی ۴ پیکسل و ارتفاع ۳۲؛ آیکن + عنوان + بستن.',
                'en' => 'A 4px radius, 32px tall; icon + title + close.',
            ]],
            ['name' => 'close', 'type' => 'visibility', 'default' => "'hover'", 'note' => [
                'fa' => 'دکمهٔ بستن فقط روی تب فعال یا hover دیده می‌شود.',
                'en' => 'The close button shows on hover or for the active tab.',
            ]],
            ['name' => 'new-tab', 'type' => 'button', 'default' => "'+'", 'note' => [
                'fa' => 'دکمهٔ + در انتهای نوار؛ Ctrl+T هم کار می‌کند.',
                'en' => 'The + at the strip’s end; Ctrl+T works too.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="fltab" role="tablist">
            <button class="fltab-tab" data-current role="tab">📄 گزارش <span class="fltab-x">×</span></button>
            <button class="fltab-add" aria-label="تب جدید">+</button>
        </div>

        <style>
        .fltab { display: flex; align-items: center; gap: 4px; padding: 4px; background: #f3f3f3; }
        .fltab-tab { display: inline-flex; gap: 8px; align-items: center; block-size: 32px;
                     padding-inline: 10px; border-radius: 4px; }
        .fltab-tab[data-current] { background: #fff; box-shadow: 0 1px 2px #00000014; }
        .fltab-x { opacity: 0; border-radius: 3px; }
        .fltab-tab:hover .fltab-x { opacity: 1; }
        </style>
        BLADE,
    ],

    'dialogs' => [
        'title' => ['fa' => 'دیالوگ، اطلاع‌بار و راهنما', 'en' => 'Dialog, info bar & tip'],
        'icon' => 'message',
        'oneLiner' => [
            'fa' => 'سه پیام‌رسان فلوئنت: ContentDialog با دکمه‌های Primary/Secondary/Close، InfoBar با سه شدت و دکمهٔ بستن، و TeachingTip با دمِ جهت‌دار که هدفش را نشان می‌دهد.',
            'en' => 'Fluent’s three messengers: the ContentDialog with Primary/Secondary/Close buttons, the InfoBar in three severities with its close, and the TeachingTip with its directional tail pointing at its target.',
        ],
        'js' => true,
        'docs' => 'https://learn.microsoft.com/windows/apps/design/controls/dialogs-and-flyouts',
        'props' => [
            ['name' => 'content-dialog', 'type' => 'buttons', 'default' => "'3'", 'note' => [
                'fa' => 'حداکثر سه دکمه: Primary و Secondary شفاف، Close پررنگ‌تر.',
                'en' => 'At most three buttons: transparent primary/secondary, a stronger close.',
            ]],
            ['name' => 'info-bar', 'type' => 'severity', 'default' => "'info · success · error'", 'note' => [
                'fa' => 'سه شدت با نوار رنگی کمرنگ و آیکن سیستم.',
                'en' => 'Three severities with a faint colour bar and a system icon.',
            ]],
            ['name' => 'teaching-tip', 'type' => 'tail', 'default' => "'directional'", 'note' => [
                'fa' => 'دم به سمت هدف؛ بستن با آیکن ✕ یا کلیک بیرون.',
                'en' => 'The tail aims at the target; ✕ or an outside click dismisses.',
            ]],
        ],
        'code' => <<<'BLADE'
        <dialog class="fldlg">
            <h2>ذخیره تغییرات؟</h2>
            <footer>
                <button data-primary>ذخیره</button>
                <button data-secondary>ذخیره‌نکردن</button>
                <button data-close>لغو</button>
            </footer>
        </dialog>

        <style>
        .fldlg { border-radius: 8px; padding: 24px; inline-size: min(90vw, 448px);
                 box-shadow: 0 32px 64px #00000040; background: #f3f3f3; }
        .fldlg footer { display: flex; justify-content: flex-end; gap: 8px; margin-block-start: 24px; }
        [data-primary] { background: #fdfdfdfd; border: 1px solid #0000003d; border-block-end-color: #0000003d; }
        </style>
        BLADE,
    ],

    'progress' => [
        'title' => ['fa' => 'پیشرفت و نشان', 'en' => 'Progress & badges'],
        'icon' => 'trend-up',
        'oneLiner' => [
            'fa' => 'پیشرفت فلوئنت: نوار خطی، حلقهٔ نامعین با پنج‌نقطهٔ چرخان معروف، InfoBadge عددی روی دکمه‌ها — و حالت «معین» که درصد واقعی را نشان می‌دهد.',
            'en' => 'Fluent progress: the linear bar, the famous indeterminate ring with its five spinning dots, numeric InfoBadges on buttons — and the determinate mode showing real percent.',
        ],
        'js' => true,
        'docs' => 'https://learn.microsoft.com/windows/apps/design/controls/progress-controls',
        'props' => [
            ['name' => 'progress-bar', 'type' => 'height', 'default' => "'4px'", 'note' => [
                'fa' => 'نوار ۴ پیکسلی؛ نامعین با پنج انیمیشن جابه‌جایی.',
                'en' => 'A 4px bar; indeterminate runs five shuffled slides.',
            ]],
            ['name' => 'progress-ring', 'type' => 'dots', 'default' => "'5'", 'note' => [
                'fa' => 'حلقهٔ نامعین: پنج نقطه با فاصله‌های فنری روی مدار.',
                'en' => 'The indeterminate ring: five dots spring-spaced on an orbit.',
            ]],
            ['name' => 'info-badge', 'type' => 'value', 'default' => "'dot|n'", 'note' => [
                'fa' => 'نقطه یا عدد روی گوشهٔ دکمه/آیکن.',
                'en' => 'A dot or a count on a button/icon corner.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="flprg-bar"><span style="inline-size: 62%"></span></div>
        <svg class="flprg-ring" viewBox="0 0 24 24">
            <circle cx="12" cy="2.5" r="2" style="animation-delay: 0s"/>
            <circle cx="19" cy="6" r="2" style="animation-delay: .12s"/>
        </svg>

        <style>
        .flprg-bar { block-size: 4px; border-radius: 999px; background: #0000001f; overflow: clip; }
        .flprg-bar span { display: block; block-size: 100%; border-radius: 999px; background: #005FB8; }
        .flprg-ring circle { fill: #005FB8; animation: flprg-orbit 1.6s cubic-bezier(.4,0,.6,1) infinite; }
        </style>
        BLADE,
    ],

    'expander' => [
        'title' => ['fa' => 'اکسپندر', 'en' => 'Expander'],
        'icon' => 'chevron-down',
        'oneLiner' => [
            'fa' => 'اکسپندر تنظیمات وین ۱۱: سربرگ با chevron که می‌چرخد، محتوایی که با انیمیشن ارتفاع باز می‌شود، حالت‌های بالا/پایینِ محتوا و لبه‌ای که فقط هنگام باز بودن دیده می‌شود.',
            'en' => 'Win11 settings’ expander: a header with a rotating chevron, content unfolding with a height animation, content-above/below modes, and a border that only shows while open.',
        ],
        'js' => true,
        'docs' => 'https://learn.microsoft.com/windows/apps/design/controls/expander',
        'props' => [
            ['name' => 'direction', 'type' => 'string', 'default' => "'down'", 'note' => [
                'fa' => 'محتوا زیر سربرگ باز می‌شود یا بالای آن (up).',
                'en' => 'Content opens below the header, or above it (up).',
            ]],
            ['name' => 'expand', 'type' => 'trigger', 'default' => "'click · Enter'", 'note' => [
                'fa' => 'کلیک، Enter یا Space؛ کل سربرگ هدف لمس است.',
                'en' => 'Click, Enter or Space; the whole header is the target.',
            ]],
            ['name' => 'chevron', 'type' => 'rotation', 'default' => "'180°'", 'note' => [
                'fa' => 'چرخش نیم‌دور با منحنی فنری فلوئنت.',
                'en' => 'A half turn on Fluent’s spring curve.',
            ]],
        ],
        'code' => <<<'BLADE'
        <details class="flexp">
            <summary><span>کیفیت صدا</span><i class="flexp-chev">▾</i></summary>
            <div class="flexp-body">…settings rows…</div>
        </details>

        <style>
        .flexp { border-radius: 4px; border: 1px solid #00000014; background: #fdfdfdfd; }
        .flexp summary { display: flex; justify-content: space-between; align-items: center;
                         block-size: 48px; padding-inline: 16px; }
        .flexp-chev { transition: rotate .2s ease; }
        .flexp[open] .flexp-chev { rotate: 180deg; }
        .flexp-body { padding: 4px 16px 16px; }
        </style>
        BLADE,
    ],

    'desktop' => [
        'title' => ['fa' => 'میزکار: پنجره‌ها و شروع مترو', 'en' => 'Desktop: windows & Metro start'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'یک ویندوز کامل روی صفحه: پنجره‌های واقعی که با نوار عنوان جابه‌جا می‌شوند، می‌چینند و می‌بستند، جذب به لبه‌ها، نوار وظیفه با ساعت زنده، و منوی شروع با کاشی‌های زندهٔ مترو که خودشان خبر تازه می‌آورند.',
            'en' => 'A whole Windows on one screen: real windows that drag by their title bar, minimise, maximise and close, edge snapping, a taskbar with a live clock, and a start menu of live Metro tiles that keep bringing fresh news.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'windows', 'type' => 'manager', 'default' => "'z-order'", 'note' => [
                'fa' => 'کلیک روی هر پنجره بالای بقیه می‌بردش؛ فقط فعال سایهٔ کامل دارد.',
                'en' => 'Clicking a window raises it above the rest; only the active one keeps its full shadow.',
            ]],
            ['name' => 'snap', 'type' => 'zones', 'default' => "'left · right'", 'note' => [
                'fa' => 'کشیدن به لبه‌ها پیش‌نمایش نیمهٔ صفحه را نشان می‌دهد و رها کردن می‌چسباند.',
                'en' => 'Dragging to the edges previews the screen half and release snaps it.',
            ]],
            ['name' => 'start-tiles', 'type' => 'metro', 'default' => "'live'", 'note' => [
                'fa' => 'کاشی‌های کوچک/عریض/بزرگ مترو با محتوای زنده: دما، تعداد ایمیل، تاریخ، عکس‌ها.',
                'en' => 'Metro’s small/wide/large tiles with live content: temperature, mail count, date, photos.',
            ]],
            ['name' => 'taskbar', 'type' => 'layout', 'default' => "'centred'", 'note' => [
                'fa' => 'آیکن‌های سنجاق‌شده وسط، نشانگر اجرا زیرشان، سینی و ساعت در انتها.',
                'en' => 'Pinned icons centred, running pills beneath, tray and clock at the end.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="fldesk" x-data="{ z: 10, windows: ['explorer', 'mail'] }">
            <div class="fldesk-win" v-for… x-show="open.explorer"
                 x-on:pointerdown="z++" style="z-index: bind">
                <div class="fldesk-title" x-on:pointerdown="drag($event)">…caption…</div>
            </div>
            <div class="fldesk-taskbar">
                <button class="fldesk-start" x-on:click="start = !start">⊞</button>
                …pinned + clock…
            </div>
            <div class="fldesk-startmenu" x-show="start">…metro tiles…</div>
        </div>

        <style>
        .fldesk { position: relative; overflow: clip; block-size: 32rem; border-radius: 8px;
                  background: radial-gradient(120% 120% at 70% 20%, #2c4a8a, #0f1c38 60%, #0a1226); }
        .fldesk-win { position: absolute; min-inline-size: 18rem; background: #f3f3f3;
                      border-radius: 8px; box-shadow: 0 16px 48px #00000059; }
        .fldesk-title { block-size: 32px; touch-action: none; cursor: grab; }
        </style>
        BLADE,
    ],
];
