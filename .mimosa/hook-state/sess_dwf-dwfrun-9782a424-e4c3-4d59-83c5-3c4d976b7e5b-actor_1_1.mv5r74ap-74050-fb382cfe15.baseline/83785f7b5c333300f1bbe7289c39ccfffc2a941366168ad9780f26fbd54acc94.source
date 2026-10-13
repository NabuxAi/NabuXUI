<?php

/**
 * Demo manifest of the "macos" group — the Mac’s own language: the window
 * with traffic lights and a unified toolbar over a vibrant sidebar, the menu
 * bar with its dropdowns and Control Centre, the magnifying dock, the classic
 * controls, the source-list sidebar, sheets and alerts, popovers, desktop
 * widgets, Spotlight and corner notifications. Scenarios live at
 * resources/views/demos/components/macos/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'مک — مک‌اواس', 'en' => 'macOS'],

    'window' => [
        'title' => ['fa' => 'پنجرهٔ مک', 'en' => 'The Mac window'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'پنجرهٔ مک‌اواس سونوما: چراغ‌های راهنما که با hover آیکن‌هایشان ظاهر می‌شود، toolbar یکپارچه با نوار کنار شفاف، جداکنندهٔ مو و سایهٔ بزرگ پنجره‌های واقعی.',
            'en' => 'A Sonoma window: traffic lights whose glyphs appear on hover, a unified toolbar over a vibrant sidebar, a hairline separator, and the big soft shadow real windows cast.',
        ],
        'js' => true,
        'docs' => 'https://developer.apple.com/design/human-interface-guidelines/designing-for-macos',
        'props' => [
            ['name' => 'traffic-lights', 'type' => 'buttons', 'default' => "'12px'", 'note' => [
                'fa' => 'سه دایرهٔ ۱۲ پیکسلی؛ گلیف‌ها فقط در hover یا فوکوس گروه.',
                'en' => 'Three 12px circles; glyphs appear on hover or group focus.',
            ]],
            ['name' => 'toolbar', 'type' => 'height', 'default' => "'52px'", 'note' => [
                'fa' => 'toolbar یکپارچه: عنوان وسط، ابزارها دو طرف.',
                'en' => 'The unified toolbar: title centred, tools on the sides.',
            ]],
            ['name' => 'vibrancy', 'type' => 'material', 'default' => "'sidebar'", 'note' => [
                'fa' => 'نوار کنار رنگ پشت پنجره را با اشباع بالا می‌گیرد.',
                'en' => 'The sidebar picks up what is behind the window, saturated.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="mcwin">
            <header class="mcwin-bar">
                <div class="mcwin-lights">
                    <button data-close></button><button data-min></button><button data-zoom></button>
                </div>
                <span class="mcwin-title">پروژه‌ها</span>
            </header>
            <div class="mcwin-body">
                <aside class="mcwin-side">…</aside>
                <main>…</main>
            </div>
        </div>

        <style>
        .mcwin { border-radius: 10px; overflow: clip; background: #ececec;
                 box-shadow: 0 22px 70px 4px #00000040, 0 0 0 .5px #00000026; }
        .mcwin-lights { display: flex; gap: 8px; }
        .mcwin-lights button { inline-size: 12px; aspect-ratio: 1; border-radius: 50%; }
        [data-close] { background: #FF5F57; } [data-min] { background: #FEBC2E; } [data-zoom] { background: #28C840; }
        </style>
        BLADE,
    ],

    'menu-bar' => [
        'title' => ['fa' => 'نوار منو و مرکز کنترل', 'en' => 'Menu bar & Control Centre'],
        'icon' => 'menu',
        'oneLiner' => [
            'fa' => 'منوبار مک: منوهای Apple و File با میان‌برهای ⌘، جداکننده‌ها و تیک‌ها، آیتم‌های وضعیت سمت راست و popover مرکز کنترل با اسلایدرهای روشنایی و Wi-Fi.',
            'en' => 'The Mac menu bar: Apple and File menus with ⌘ shortcuts, separators and ticks, status items on the right, and a Control Centre popover with brightness and Wi-Fi sliders.',
        ],
        'js' => true,
        'docs' => 'https://developer.apple.com/design/human-interface-guidelines/the-menu-bar',
        'props' => [
            ['name' => 'menu', 'type' => 'panel', 'default' => "'translucent'", 'note' => [
                'fa' => 'منوی باز، پنل شیشه‌ای با سایهٔ نرم و گوشهٔ ۵–۶ پیکسلی.',
                'en' => 'The open menu: a translucent panel, soft shadow, 5–6px corners.',
            ]],
            ['name' => 'shortcut', 'type' => 'column', 'default' => "'⌘…'", 'note' => [
                'fa' => 'میان‌برها در ستون انتهایی، جدا از برچسب.',
                'en' => 'Shortcuts in a trailing column, apart from labels.',
            ]],
            ['name' => 'status-items', 'type' => 'icons', 'default' => "'right side'", 'note' => [
                'fa' => 'آیتم‌های وضعیت همیشه سمت انتهایی منوبار.',
                'en' => 'Status items always sit on the menu bar’s trailing side.',
            ]],
        ],
        'code' => <<<'BLADE'
        <nav class="mcmenu">
            <button class="mcmenu-item" data-open>🍎</button>
            <button class="mcmenu-item">File</button>
            <span class="mcmenu-spacer"></span>
            <button class="mcmenu-item" data-cc>🎚</button>
        </nav>
        <div class="mcmenu-panel">…items with ⌘ shortcuts…</div>

        <style>
        .mcmenu { display: flex; align-items: center; block-size: 24px; padding-inline: 8px;
                  background: #ffffff80; backdrop-filter: blur(20px) saturate(1.8); }
        .mcmenu-item { padding-inline: 10px; border-radius: 4px; font: 500 13px system-ui; }
        .mcmenu-item[data-open] { background: #00000014; }
        .mcmenu-panel { inline-size: 220px; padding: 4px; border-radius: 6px;
                        background: #ffffffd9; backdrop-filter: blur(40px);
                        box-shadow: 0 10px 40px #0000002e; }
        </style>
        BLADE,
    ],

    'dock' => [
        'title' => ['fa' => 'داک', 'en' => 'The Dock'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'داک با بزرگ‌نمایی واقعی: آیکن‌ها بر اساس فاصله از اشاره‌گر بزرگ می‌شوند و همسایه‌ها را هل می‌دهند، نقطهٔ اجرا زیر آیکن‌های باز، جداکنندهٔ میانه و سطل زباله در انتها.',
            'en' => 'The Dock with real magnification: icons grow by pointer distance and shove their neighbours, running dots under open apps, the middle separator and the trash at the end.',
        ],
        'js' => true,
        'docs' => 'https://developer.apple.com/design/human-interface-guidelines/the-dock',
        'props' => [
            ['name' => 'magnification', 'type' => 'curve', 'default' => "'gaussian'", 'note' => [
                'fa' => 'بزرگ‌نمایی گاوسی حول اشاره‌گر؛ همسایه‌ها هم سهم می‌برند.',
                'en' => 'Gaussian magnification around the pointer; neighbours share in.',
            ]],
            ['name' => 'running-dot', 'type' => 'marker', 'default' => "'4px'", 'note' => [
                'fa' => 'نقطهٔ ۴ پیکسلی زیر آیکن‌های برنامه‌های باز.',
                'en' => 'A 4px dot under open apps.',
            ]],
            ['name' => 'tooltip', 'type' => 'label', 'default' => "'hover'", 'note' => [
                'fa' => 'نام برنامه در بادکنک بالای آیکن با تاخیر کوتاه.',
                'en' => 'The app name in a bubble after a short hover.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="mcdock" onpointermove="mcdockMagnify(event)">
            <img class="mcdock-icon" src="finder.png" data-name="Finder">
            <img class="mcdock-icon" src="mail.png" data-name="Mail">
            <span class="mcdock-sep"></span>
            <img class="mcdock-icon" src="trash.png" data-name="Trash">
        </div>

        <style>
        .mcdock { display: flex; align-items: flex-end; gap: 8px; padding: 6px;
                  border-radius: 20px; background: #ffffff59;
                  backdrop-filter: blur(24px) saturate(1.6);
                  box-shadow: inset 0 0 0 .5px #ffffffa6; }
        .mcdock-icon { inline-size: 52px; transition: inline-size .12s ease-out; }
        .mcdock-icon:hover { inline-size: 76px; }
        </style>
        BLADE,
    ],

    'controls' => [
        'title' => ['fa' => 'کنترل‌های کلاسیک', 'en' => 'Classic controls'],
        'icon' => 'sliders',
        'oneLiner' => [
            'fa' => 'کنترل‌های آشنا‌ی مک: دکمهٔ push با لبهٔ ظریف و آبی پیش‌فرض، چک‌باکس و رادیو گرد، دکمهٔ pop-up با فلش دوطرفه، اسلایدر با حفرهٔ خطی و استپر گرد دو بخشی.',
            'en' => 'The Mac’s familiar controls: the push button with its hairline bezel and blue default, the round checkbox and radio, the pop-up button with its double chevron, the linear-well slider and the two-lobe round stepper.',
        ],
        'js' => true,
        'docs' => 'https://developer.apple.com/design/human-interface-guidelines/buttons',
        'props' => [
            ['name' => 'default-button', 'type' => 'accent', 'default' => "'blue'", 'note' => [
                'fa' => 'دکمهٔ پیش‌فرض آبی است و Enter آن را می‌زند؛ رنگ سیستم #007AFF.',
                'en' => 'The default button is blue and answers to Enter; system blue #007AFF.',
            ]],
            ['name' => 'popup-button', 'type' => 'chevron', 'default' => "'up+down'", 'note' => [
                'fa' => 'فلش دوتایی آبی داخل کادر در سمت انتهایی.',
                'en' => 'A blue double chevron in the field’s trailing side.',
            ]],
            ['name' => 'stepper', 'type' => 'shape', 'default' => "'round'", 'note' => [
                'fa' => 'استپر دو بخشی گرد؛ بالا و پایین با جداکنندهٔ مو.',
                'en' => 'The round two-lobe stepper; up/down with a hairline between.',
            ]],
        ],
        'code' => <<<'BLADE'
        <button class="mcctl-push" data-default>ادامه</button>
        <label class="mcctl-check"><input type="checkbox"><i></i> به‌خاطرسپاری</label>
        <div class="mcctl-popup">صحنهٔ نمایش <b>⌄⌃</b></div>
        <div class="mcctl-stepper"><button>▲</button><button>▼</button></div>

        <style>
        .mcctl-push { block-size: 28px; padding-inline: 14px; border-radius: 6px;
                      background: #fff; box-shadow: 0 .5px 1.5px #00000033, 0 0 0 .5px #00000026; }
        .mcctl-push[data-default] { background: #007AFF; color: #fff; }
        .mcctl-check i { inline-size: 14px; aspect-ratio: 1; border-radius: 50%;
                         box-shadow: 0 1px 2px #00000026, inset 0 0 0 .5px #00000040; background: #fff; }
        .mcctl-check input:checked + i { background: #007AFF center/9px no-repeat url(check.svg); }
        </style>
        BLADE,
    ],

    'sidebar' => [
        'title' => ['fa' => 'نوار کنارِ فهرست', 'en' => 'Source-list sidebar'],
        'icon' => 'folder',
        'oneLiner' => [
            'fa' => 'source list فایندر: بخش‌های «موردعلاقه‌ها» و «iCloud» با سربرگ‌های خاکستری کوچک، ردیف‌های آیکن‌دار با شمارنده، ردیف انتخابی آبی و فیلد فیلتر پایین.',
            'en' => 'Finder’s source list: «Favourites» and «iCloud» sections with small grey headers, icon rows with counts, the blue selected row, and the filter field at the foot.',
        ],
        'js' => true,
        'docs' => 'https://developer.apple.com/design/human-interface-guidelines/sidebars',
        'props' => [
            ['name' => 'row', 'type' => 'height', 'default' => "'28px'", 'note' => [
                'fa' => 'ردیف‌های فشردهٔ ۲۸ پیکسلی با آیکن ۱۶ پیکسلی رنگی.',
                'en' => 'Tight 28px rows with 16px colour icons.',
            ]],
            ['name' => 'selection', 'type' => 'row', 'default' => "'system blue'", 'note' => [
                'fa' => 'انتخاب: ردیف آبی سیستم با متن سفید.',
                'en' => 'Selection: a system-blue row with white text.',
            ]],
            ['name' => 'vibrancy', 'type' => 'material', 'default' => "'on'", 'note' => [
                'fa' => 'پس‌زمینهٔ نیمه‌شفاف که رنگ پشت پنجره را می‌گیرد.',
                'en' => 'A translucent backdrop that picks up what is behind the window.',
            ]],
        ],
        'code' => <<<'BLADE'
        <aside class="mcside">
            <h4>موردعلاقه‌ها</h4>
            <button class="mcside-row" data-current>📂 پروژه‌ها <b>۲۴</b></button>
            <button class="mcside-row">📄 اسناد</button>
        </aside>

        <style>
        .mcside { inline-size: 220px; padding: 8px; background: #ffffff66;
                  backdrop-filter: blur(20px) saturate(1.6); }
        .mcside h4 { font: 700 11px system-ui; color: #7d7d80; padding-inline: 8px; }
        .mcside-row { display: flex; gap: 6px; align-items: center; block-size: 28px;
                      padding-inline: 8px; border-radius: 5px; }
        .mcside-row[data-current] { background: #007AFF; color: #fff; }
        .mcside-row b { margin-inline-start: auto; font: 500 11px system-ui; }
        </style>
        BLADE,
    ],

    'sheet-alert' => [
        'title' => ['fa' => 'شیت و آلرت', 'en' => 'Sheet & alert'],
        'icon' => 'alert-circle',
        'oneLiner' => [
            'fa' => 'دو مودال مک: شیت که از زیر toolbar پنجره پایین می‌آید و پدرش را تار می‌کند، و آلرت مرکزی با دکمهٔ پیش‌فرضِ پُر و دکمهٔ رد که Enter و Escape را می‌فهمند.',
            'en' => 'The Mac’s two modals: the sheet sliding from under the toolbar and dimming its parent, and the centred alert with a filled default button — Enter and Escape do the right thing.',
        ],
        'js' => true,
        'docs' => 'https://developer.apple.com/design/human-interface-guidelines/sheets',
        'props' => [
            ['name' => 'sheet', 'type' => 'position', 'default' => "'under toolbar'", 'note' => [
                'fa' => 'شیت از لبهٔ بالاییِ بدنه بیرون می‌آید، نه از وسط صفحه.',
                'en' => 'The sheet emerges from the body’s top edge, not screen centre.',
            ]],
            ['name' => 'alert', 'type' => 'buttons', 'default' => "'default + cancel'", 'note' => [
                'fa' => 'دکمهٔ پیش‌فرض پررنگ؛ رد کنارش بی‌پس‌زمینه.',
                'en' => 'The default button is filled; Cancel beside it is plain.',
            ]],
            ['name' => 'keys', 'type' => 'behaviour', 'default' => "'Enter · Esc'", 'note' => [
                'fa' => 'Enter پیش‌فرض، Escape رد؛ فوکوس قفلِ مودال است.',
                'en' => 'Enter accepts, Escape cancels; focus is trapped.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="mcsheet">…form…</div>
        <dialog class="mcalert">
            <p>خروج ذخیره شود؟</p>
            <footer><button>لغو</button><button data-default>ذخیره</button></footer>
        </dialog>

        <style>
        .mcsheet { position: absolute; inset-inline: 0; inset-block-start: 52px; padding: 20px;
                   border-end-end-radius: 10px; border-end-start-radius: 10px;
                   background: #ececec; box-shadow: 0 12px 40px #00000038;
                   animation: mcsheet-in .3s cubic-bezier(.2,.9,.3,1); }
        .mcalert { inline-size: 260px; border-radius: 12px; padding: 16px 20px;
                   background: #e8e8e8f2; backdrop-filter: blur(30px); }
        [data-default] { background: #007AFF; color: #fff; }
        </style>
        BLADE,
    ],

    'popover' => [
        'title' => ['fa' => 'پاپ‌اور', 'en' => 'Popover'],
        'icon' => 'message',
        'oneLiner' => [
            'fa' => 'پاپ‌اور مک با دمِ جهت‌دار به دکمه‌اش: شیشه‌ایِ تار، سایهٔ بزرگ، بسته‌شدن با کلیک بیرون یا Escape، و چند جایگاه (بالا/پایین/طرفین) که خودکار انتخاب می‌شوند.',
            'en' => 'A macOS popover with its tail aimed at its button: frosted glass, a big soft shadow, closing on outside click or Escape, and auto-picked placements above/below/either side.',
        ],
        'js' => true,
        'docs' => 'https://developer.apple.com/design/human-interface-guidelines/popovers',
        'props' => [
            ['name' => 'tail', 'type' => 'arrow', 'default' => "'9px'", 'note' => [
                'fa' => 'دم ۹ پیکسلی که همیشه به سمت لنگرش می‌چرخد.',
                'en' => 'A 9px tail that always rotates toward its anchor.',
            ]],
            ['name' => 'dismiss', 'type' => 'behaviour', 'default' => "'transient'", 'note' => [
                'fa' => 'کلیک بیرون، Escape یا تعویض تب می‌بنددش.',
                'en' => 'Outside click, Escape or a tab switch closes it.',
            ]],
            ['name' => 'placement', 'type' => 'side', 'default' => "'auto'", 'note' => [
                'fa' => 'جایی که جا دارد: بالا، پایین یا طرفین لنگر.',
                'en' => 'Wherever it fits: above, below or beside the anchor.',
            ]],
        ],
        'code' => <<<'BLADE'
        <button class="mcpop-anchor" aria-expanded="false">گزینه‌ها</button>
        <div class="mcpop" data-side="bottom">
            <span class="mcpop-tail"></span>
            …content…
        </div>

        <style>
        .mcpop { position: absolute; inset-block-start: calc(100% + 9px); inline-size: 260px;
                 padding: 14px; border-radius: 10px; background: #f5f5f5eb;
                 backdrop-filter: blur(40px) saturate(1.6); box-shadow: 0 12px 48px #00000033; }
        .mcpop-tail { position: absolute; inset-block-start: -8px; inset-inline-start: 24px;
                      inline-size: 16px; block-size: 16px; rotate: 45deg; background: inherit; }
        </style>
        BLADE,
    ],

    'widgets' => [
        'title' => ['fa' => 'ویجت‌های رومیزی', 'en' => 'Desktop widgets'],
        'icon' => 'star',
        'oneLiner' => [
            'fa' => 'ویجت‌های دسکتاپ سونوما: ساعت آنالوگ، هواشناسی، تقویم و پخش‌کنندهٔ موسیقی — همه با گرادیان‌های نرم، شیشهٔ ظریف و حالت tintColor که با کاغذدیواری هماهنگ می‌شود.',
            'en' => 'Sonoma’s desktop widgets: an analog clock, weather, calendar and a music player — soft gradients, subtle glass, and a tinted mode that matches the wallpaper.',
        ],
        'js' => true,
        'docs' => 'https://developer.apple.com/design/human-interface-guidelines/widgets',
        'props' => [
            ['name' => 'sizes', 'type' => 'grid', 'default' => "'S · M · L'", 'note' => [
                'fa' => 'سه اندازه با گرید مشترک؛ گوشه‌های نرم ۲۲–۲۴ پیکسلی.',
                'en' => 'Three sizes on one grid; soft 22–24px corners.',
            ]],
            ['name' => 'material', 'type' => 'style', 'default' => "'tinted · glass'", 'note' => [
                'fa' => 'شیشهٔ ظریف با tint رنگ سیستم یا حالت تمام‌رنگ.',
                'en' => 'Subtle glass with a system tint, or full colour.',
            ]],
            ['name' => 'live', 'type' => 'behaviour', 'default' => "'ticking'", 'note' => [
                'fa' => 'ساعت واقعاً تیک می‌زند و آب‌وهوا نمونهٔ زنده دارد.',
                'en' => 'The clock really ticks; the weather has live samples.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="mcwdg" data-size="medium">
            <span class="mcwdg-clock">۱۴:۰۵</span>
            <div class="mcwdg-forecast">☀️ ۲۸° …</div>
        </div>

        <style>
        .mcwdg { border-radius: 22px; padding: 16px; color: #fff;
                 background: linear-gradient(145deg, #2e3c58, #141b2b);
                 box-shadow: 0 10px 30px #00000033; font-family: system-ui; }
        .mcwdg[data-size='small'] { inline-size: 148px; aspect-ratio: 1; }
        .mcwdg[data-size='medium'] { inline-size: 312px; block-size: 148px; }
        </style>
        BLADE,
    ],

    'spotlight' => [
        'title' => ['fa' => 'Spotlight', 'en' => 'Spotlight'],
        'icon' => 'search',
        'oneLiner' => [
            'fa' => 'Spotlight مک: پنل مرکزی شیشه‌ای، نتایج گروه‌بندی‌شده با آیکن اپ، انتخاب با فلش‌ها، پنل پیش‌نمایش در سمت دیگر و میان‌بر ⌘K برای باز کردنش.',
            'en' => 'macOS Spotlight: a centred glass panel, grouped results with app icons, arrow-key selection, a preview pane on the side, and ⌘K to summon it.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'panel', 'type' => 'width', 'default' => "'680px'", 'note' => [
                'fa' => 'پنل بزرگ مرکزی با فیلد بزرگ و نتایج زیر آن.',
                'en' => 'One wide centred panel: a big field, results beneath.',
            ]],
            ['name' => 'preview', 'type' => 'pane', 'default' => "'on-select'", 'note' => [
                'fa' => 'با انتخاب هر نتیجه، پیش‌نمایشش کنار باز می‌شود.',
                'en' => 'Selecting a result opens its preview alongside.',
            ]],
            ['name' => 'groups', 'type' => 'list', 'default' => "'apps · files · web'", 'note' => [
                'fa' => 'نتایج به گروه‌های سربرگ‌دار تقسیم می‌شوند.',
                'en' => 'Results split under headed groups.',
            ]],
        ],
        'code' => <<<'BLADE'
        <dialog class="mcspot">
            <div class="mcspot-field"><span>🔍</span><input placeholder="جست‌وجوی Spotlight"></div>
            <ul class="mcspot-results" role="listbox">
                <li data-current>🧮 Calculator — Top hit</li>
            </ul>
        </dialog>

        <style>
        .mcspot { inline-size: min(90vw, 680px); border: none; border-radius: 12px; padding: 10px;
                  background: #f2f2f2e0; backdrop-filter: blur(40px) saturate(1.5);
                  box-shadow: 0 24px 80px #00000045; }
        .mcspot-field { display: flex; gap: 10px; align-items: center; block-size: 44px; }
        .mcspot-results li[data-current] { background: #007AFF; color: #fff; border-radius: 6px; }
        </style>
        BLADE,
    ],

    'notifications' => [
        'title' => ['fa' => 'اعلان‌های گوشه', 'en' => 'Corner notifications'],
        'icon' => 'bell',
        'oneLiner' => [
            'fa' => 'بنرهای اعلان مک در گوشهٔ بالا: کارت شیشه‌ای با آیکن اپ، عنوان و متن دو خطی که با hover باز می‌شود و دکمه‌های اقدام می‌آیند؛ چندتایی پشت هم پله پله می‌شوند.',
            'en' => 'The Mac’s corner banners: a glass card with the app icon, title and two-line body that expands on hover to reveal action buttons; more banners stack behind in steps.',
        ],
        'js' => true,
        'docs' => 'https://developer.apple.com/design/human-interface-guidelines/notifications',
        'props' => [
            ['name' => 'banner', 'type' => 'size', 'default' => "'380px'", 'note' => [
                'fa' => 'پهنای رسمی بنر با آیکن ۳۸–۴۰ پیکسلی.',
                'en' => 'The official banner width with a 38–40px icon.',
            ]],
            ['name' => 'expand', 'type' => 'behaviour', 'default' => "'hover'", 'note' => [
                'fa' => 'هاور متن کامل و دکمه‌ها را باز می‌کند.',
                'en' => 'Hover reveals the full text and the actions.',
            ]],
            ['name' => 'stack', 'type' => 'layout', 'default' => "'3 deep'", 'note' => [
                'fa' => 'حداکثر سه بنر پله‌پله پشت سر هم.',
                'en' => 'Up to three banners step behind each other.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="mcnotif" data-expanded="false">
            <img class="mcnotif-icon" src="mail.png" alt="">
            <div>
                <b>ایمیل</b>
                <p>سارا: جلسه فردا…‏</p>
            </div>
        </div>

        <style>
        .mcnotif { display: flex; gap: 10px; inline-size: 380px; padding: 12px;
                   border-radius: 14px; background: #f2f2f2e6;
                   backdrop-filter: blur(30px) saturate(1.5);
                   box-shadow: 0 8px 32px #0000002b; }
        .mcnotif-icon { inline-size: 38px; aspect-ratio: 1; border-radius: 9px; }
        .mcnotif p { margin: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: clip; }
        </style>
        BLADE,
    ],

    'desktop' => [
        'title' => ['fa' => 'میزکار کامل مک', 'en' => 'The full Mac desktop'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'یک مک کامل: منوبار زنده با ساعت فارسی، داک با بزرگ‌نمایی و پرش هنگام باز شدن، پنجره‌های واقعی (فایندر، ایمیل، یادداشت) که جابه‌جا می‌شوند، کوچک‌به‌داک می‌روند و سایه‌شان با فوکوس نفس می‌کشد.',
            'en' => 'A whole Mac: a live menu bar with a Persian clock, a magnifying dock that bounces on launch, real windows — Finder, Mail, Notes — that drag around, minimise into the dock, and whose shadows breathe with focus.',
        ],
        'js' => true,
        'docs' => 'https://developer.apple.com/design/human-interface-guidelines/designing-for-macos',
        'props' => [
            ['name' => 'windows', 'type' => 'manager', 'default' => "'z-order'", 'note' => [
                'fa' => 'پنجرهٔ فعال سایهٔ بزرگ و تیتر پررنگ دارد؛ بقیه کمرنگ می‌شوند.',
                'en' => 'The active window gets the big shadow and a strong title; the rest dim.',
            ]],
            ['name' => 'minimise', 'type' => 'animation', 'default' => "'into dock'", 'note' => [
                'fa' => 'زررد کوچک می‌شود و سمت داک می‌رود؛ از همان‌جا دوباره بالا می‌آید.',
                'en' => 'The yellow light shrinks the window toward the dock; click its dock icon to bring it back.',
            ]],
            ['name' => 'launch', 'type' => 'animation', 'default' => "'dock bounce'", 'note' => [
                'fa' => 'آیکن تازه تا باز شدن پنجره می‌پرد.',
                'en' => 'A freshly launched icon bounces until its window opens.',
            ]],
            ['name' => 'menu-bar', 'type' => 'behaviour', 'default' => "'live'", 'note' => [
                'fa' => 'نام اپ فعال در منوبار عوض می‌شود و ساعت واقعی تیک می‌زند.',
                'en' => 'The menu bar renames itself to the active app and the clock really ticks.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="mcdesk" x-data="{ z: 10, active: null, open: {}, minimised: {} }">
            <nav class="mcdesk-menubar"> + live clock + status </nav>
            <div class="mcdesk-win" x-show="open.finder"
                 :style="`z-index: ${winZ('finder')}`" x-on:pointerdown="focus('finder')">
                <div class="mcdesk-lights" x-on:pointerdown.stop="dragless($event)">…</div>
                …finder body…
            </div>
            <div class="mcdesk-dock" x-on:click="launch('mail')">…magnifying icons…</div>
        </div>

        <style>
        .mcdesk { position: relative; overflow: clip; block-size: 32rem; border-radius: 12px;
                  background: radial-gradient(130% 110% at 30% 10%, #4a5d8a, #16203a 55%, #0b1224); }
        .mcdesk-win { position: absolute; inline-size: min(100%, 24rem); background: var(--mcwin-bg);
                      border-radius: 10px; overflow: clip;
                      box-shadow: 0 22px 70px 4px #00000040, 0 0 0 .5px #00000026; }
        .mcdesk-win[data-dim] { filter: saturate(.9); }
        </style>
        BLADE,
    ],
];
