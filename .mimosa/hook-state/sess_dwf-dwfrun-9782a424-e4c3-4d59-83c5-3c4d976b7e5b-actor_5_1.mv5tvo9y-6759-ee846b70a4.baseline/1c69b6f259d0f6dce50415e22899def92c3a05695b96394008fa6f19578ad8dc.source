<?php

/**
 * Demo manifest of the "android" group — Material Design 3 / Material You
 * rebuilt as faithful, interactive skins: the five buttons with their ripple,
 * FABs and the FAB menu, the four chips, selection controls, sliders, cards,
 * the modal bottom sheet, the bottom navigation bar, top app bars, snackbars,
 * dialogs, the search bar that becomes a search view, M3 progress indicators
 * and dynamic color. Scenarios live at
 * resources/views/demos/components/android/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'اندروید — متریال ۳', 'en' => 'Android — Material 3'],

    'buttons' => [
        'title' => ['fa' => 'دکمه‌های متریال', 'en' => 'Material buttons'],
        'icon' => 'zap',
        'oneLiner' => [
            'fa' => 'پنج دکمهٔ اصلی متریال ۳ — filled، tonal، outlined، text و elevated — با موج (ripple) واقعی که از نقطهٔ لمس می‌جوشد، گردی تمام و ارتفاع ۴۰ و حالت غیرفعال با درصدهای رسمی رنگ.',
            'en' => 'The five core Material 3 buttons — filled, tonal, outlined, text and elevated — with a real ripple boiling from the touch point, full rounding, 40px height and the official disabled opacities.',
        ],
        'js' => true,
        'docs' => 'https://m3.material.io/components/buttons/overview',
        'props' => [
            ['name' => '--m3btn-primary', 'type' => 'color', 'default' => "'#6750A4'", 'note' => [
                'fa' => 'رنگ اصلی؛ در تم تیرهٔ متریال به #D0BCFF می‌رسد.',
                'en' => 'The primary colour; on Material’s dark theme it becomes #D0BCFF.',
            ]],
            ['name' => '--m3btn-radius', 'type' => 'length', 'default' => "'999px'", 'note' => [
                'fa' => 'گردی دکمه؛ متریال ۳ دکمه را تمام‌گرد می‌خواهد.',
                'en' => 'Corner radius; M3 wants buttons fully rounded.',
            ]],
            ['name' => 'ripple', 'type' => 'event', 'default' => "'pointerdown'", 'note' => [
                'fa' => 'موج از مختصات لمس می‌جوشد و در ۵۰۰ms محو می‌شود؛ با کیبورد از مرکز.',
                'en' => 'The ripple boils from the pointer coordinates and fades in 500ms; from centre on keyboard.',
            ]],
            ['name' => 'disabled', 'type' => 'opacity', 'default' => "'38% · 12%'", 'note' => [
                'fa' => 'متن با ۳۸٪ و سطح با ۱۲٪ رنگ، دقیقاً طبق توکن‌های متریال.',
                'en' => 'Label at 38% and surface at 12% of colour, exactly per the Material tokens.',
            ]],
        ],
        'code' => <<<'BLADE'
        <button class="m3btn" data-variant="filled" onpointerdown="m3Ripple(event)">ورود</button>
        <button class="m3btn" data-variant="tonal">ذخیره پیش‌نویس</button>
        <button class="m3btn" data-variant="outlined">بعداً</button>
        <button class="m3btn" data-variant="text">رد کردن</button>
        <button class="m3btn" data-variant="elevated">بالا بردن</button>

        <style>
        .m3btn { position: relative; overflow: hidden; block-size: 40px; padding-inline: 24px;
                 border-radius: 999px; font: 500 14px Roboto, system-ui; }
        .m3btn[data-variant='filled'] { background: var(--m3btn-primary); color: #fff; }
        .m3btn .m3btn-ripple { position: absolute; border-radius: 50%; translate: -50% -50%;
                 background: currentColor; opacity: .12; animation: m3btn-ripple .5s ease-out forwards; }
        </style>
        BLADE,
    ],

    'fab' => [
        'title' => ['fa' => 'دکمهٔ شناور و منوی آن', 'en' => 'FAB & FAB menu'],
        'icon' => 'plus',
        'oneLiner' => [
            'fa' => 'دکمهٔ شناور در چهار اندازهٔ رسمی (small، medium، large، extended) و منوی FAB که باز می‌شود: آیتم‌ها با برچسب از پشت دکمه سر در می‌آورند، دکمهٔ + می‌چرخد و پس‌زمینه محو می‌شود.',
            'en' => 'The FAB in its four official sizes — small, medium, large, extended — and the FAB menu opening from it: items peek out with labels, the + rotates and the backdrop dims.',
        ],
        'js' => true,
        'docs' => 'https://m3.material.io/components/floating-action-button/overview',
        'props' => [
            ['name' => 'size', 'type' => 'px', 'default' => "'56 · 96 · 40'", 'note' => [
                'fa' => 'medium، large و small؛ extended با برچسب بلندتر می‌شود.',
                'en' => 'Medium, large and small; extended grows with its label.',
            ]],
            ['name' => '--m3fab-container', 'type' => 'color', 'default' => "'#E8DEF8'", 'note' => [
                'fa' => 'رنگ محفظهٔ FAB؛ سطح اولیه (primary container) متریال.',
                'en' => 'The FAB container colour; Material’s primary-container surface.',
            ]],
            ['name' => 'menu', 'type' => 'state', 'default' => "'closed'", 'note' => [
                'fa' => "با کلاس data-open منو باز می‌شود؛ Escape و لمس بیرون می‌بندد.",
                'en' => "data-open springs the menu; Escape and outside taps close it.",
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="m3fab" data-open="false">
            <div class="m3fab-actions">
                <button class="m3fab-item"><span>عکس</span><i>…</i></button>
                <button class="m3fab-item"><span>فایل</span><i>…</i></button>
            </div>
            <button class="m3fab m3fab-md" aria-expanded="false" aria-label="ساخت">
                <svg class="m3fab-plus">+</svg>
            </button>
        </div>

        <style>
        .m3fab-md { inline-size: 56px; aspect-ratio: 1; border-radius: 16px;
                    background: #E8DEF8; color: #1D192B; box-shadow: 0 4px 8px 3px #00000026; }
        .m3fab[data-open='true'] .m3fab-plus { rotate: 45deg; }
        .m3fab-actions { display: grid; gap: 12px; place-items: end; opacity: 0; translate: 0 8px; }
        .m3fab[data-open='true'] .m3fab-actions { opacity: 1; translate: 0 0; }
        </style>
        BLADE,
    ],

    'chips' => [
        'title' => ['fa' => 'چهار نوع چیپ', 'en' => 'The four chips'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'چیپ‌های متریال ۳ به چهار نقش: assist برای اقدام، filter برای انتخاب چندگانه با تیک انیمیت‌شونده، input برای محتوای هوش مصنوعی با دکمهٔ حذف و suggestion برای پیشنهادها.',
            'en' => 'M3 chips in their four roles: assist for actions, filter for multi-select with an animated checkmark, input for AI-suggested content with a remove button, suggestion for prompts.',
        ],
        'js' => true,
        'docs' => 'https://m3.material.io/components/chips/overview',
        'props' => [
            ['name' => 'variant', 'type' => 'string', 'default' => "'assist'", 'note' => [
                'fa' => 'assist · filter · input · suggestion؛ فقط filter و input انتخاب می‌شوند.',
                'en' => 'assist · filter · input · suggestion; only filter and input toggle.',
            ]],
            ['name' => 'elevation', 'type' => 'shadow', 'default' => "'level1'", 'note' => [
                'fa' => 'چیپ‌های انتخاب‌نشده سایهٔ ظریف سطح ۱ دارند؛ انتخاب‌شده‌ها سطح دوم می‌شوند.',
                'en' => 'Unselected chips carry the subtle level-1 shadow; selected rise to level 2.',
            ]],
            ['name' => 'checkmark', 'type' => 'inline-size', 'default' => "'0 → 18px'", 'note' => [
                'fa' => 'تیک فیلتر با باز شدن عرض جا باز می‌کند و لیبل را هل می‌دهد.',
                'en' => 'The filter’s checkmark opens by width and nudges the label across.',
            ]],
        ],
        'code' => <<<'BLADE'
        <button class="m3chip">کمک: باز کردن نقشه</button>
        <button class="m3chip" data-filter aria-pressed="false">
            <span class="m3chip-check">✓</span> ژانر: علمی‌تخیلی
        </button>
        <span class="m3chip" data-input>پیشنهاد هوش مصنوعی <button aria-label="حذف">×</button></span>
        <button class="m3chip">پیشنهاد: خلاصه کن</button>

        <style>
        .m3chip { display: inline-flex; align-items: center; gap: 8px; block-size: 32px;
                  padding-inline: 16px; border-radius: 8px; border: 1px solid #CAC4D0;
                  box-shadow: 0 1px 2px #0000001f; }
        .m3chip-check { display: inline-grid; inline-size: 0; overflow: hidden; transition: inline-size .2s; }
        .m3chip[aria-pressed='true'] { background: #E8DEF8; border-color: transparent; }
        .m3chip[aria-pressed='true'] .m3chip-check { inline-size: 18px; }
        </style>
        BLADE,
    ],

    'selection' => [
        'title' => ['fa' => 'کنترل‌های انتخاب', 'en' => 'Selection controls'],
        'icon' => 'check',
        'oneLiner' => [
            'fa' => 'سوییچ، چک‌باکس و رادیوی متریال ۳ با انیمیشن‌های رسمی: انگشتشت سوییچ آیکن تیک را در خودش بزرگ می‌کند، چک‌باکس با مسیر SVG تیک می‌خورد و رادیو از مرکز موج می‌گیرد.',
            'en' => 'The M3 switch, checkbox and radio with their official motion: the switch thumb scales its check icon from inside, the checkbox draws its tick as an SVG path, the radio ripples from centre.',
        ],
        'js' => true,
        'docs' => 'https://m3.material.io/components/switch/overview',
        'props' => [
            ['name' => 'switch: track', 'type' => 'size', 'default' => "'52 × 32'", 'note' => [
                'fa' => 'ریل ۵۲×۳۲ و انگشت ۱۶→۲۴ پیکسل؛ خاموش با خط علامت، روشن با تیک.',
                'en' => 'A 52×32 track and a 16→24px thumb; off shows a dash, on shows a tick.',
            ]],
            ['name' => 'state-layer', 'type' => 'overlay', 'default' => "'12%'", 'note' => [
                'fa' => 'لایهٔ حالت روی فشردن؛ لمس طولانی لایه را کامل می‌کند.',
                'en' => 'The state layer on press; a long press fills it in.',
            ]],
            ['name' => 'error', 'type' => 'color', 'default' => "'#B3261E'", 'note' => [
                'fa' => 'رنگ خطای متریال برای حالت نامعتبر هر سه کنترل.',
                'en' => 'Material’s error colour for the invalid state of all three.',
            ]],
        ],
        'code' => <<<'BLADE'
        <label class="m3sel-switch">
            <input type="checkbox" role="switch">
            <span class="m3sel-track"><span class="m3sel-thumb"></span></span>
        </label>

        <label class="m3sel-check">
            <input type="checkbox"><span class="m3sel-box"><svg viewBox="0 0 24 24"><path d="M5 12l5 5 9-10"/></svg></span>
        </label>

        <style>
        .m3sel-switch input:checked + .m3sel-track { background: #6750A4; }
        .m3sel-track { inline-size: 52px; block-size: 32px; border-radius: 999px; background: #E6E0E9; border: 2px solid #79747E; }
        .m3sel-thumb { inline-size: 16px; aspect-ratio: 1; border-radius: 50%; background: #79747E; transition: all .2s; }
        .m3sel-switch input:checked + .m3sel-track .m3sel-thumb { inline-size: 24px; translate: 20px 0; background: #fff; }
        </style>
        BLADE,
    ],

    'slider' => [
        'title' => ['fa' => 'اسلایدر', 'en' => 'Slider'],
        'icon' => 'sliders',
        'oneLiner' => [
            'fa' => 'اسلایدر متریال ۳ با دستگیرهٔ توخالی، برچسب حباب‌مانند مقدار هنگام کشیدن، گام‌های دلخواه و نسخهٔ range با دو دستگیره؛ آیکن‌های دو سر هم در M3 رسمی‌اند.',
            'en' => 'The M3 slider with its hollow handle, a bubble value label while dragging, custom steps, and the two-handle range variant; end icons are official M3 too.',
        ],
        'js' => true,
        'docs' => 'https://m3.material.io/components/slider/overview',
        'props' => [
            ['name' => 'handle', 'type' => 'shape', 'default' => "'gap · 4px'", 'note' => [
                'fa' => 'دستگیره ۴ پیکسل از ریل فاصله دارد — امضای بصری M3.',
                'en' => 'The handle sits 4px off the track — M3’s signature gap.',
            ]],
            ['name' => 'value-label', 'type' => 'bubble', 'default' => "'while-dragging'", 'note' => [
                'fa' => 'برچسب مقدار بالای دستگیره فقط حین کشیدن بالا می‌آید.',
                'en' => 'The value bubble rises above the handle only while dragging.',
            ]],
            ['name' => 'step', 'type' => 'number', 'default' => '1', 'note' => [
                'fa' => 'گام؛ با فلش‌های کیبورد هم جابه‌جا می‌شود.',
                'en' => 'The step; arrow keys move it too.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="m3sld">
            <input type="range" min="0" max="100" value="40" step="5">
            <span class="m3sld-bubble">40</span>
        </div>

        <style>
        .m3sld input { appearance: none; inline-size: 100%; block-size: 16px; }
        .m3sld input::-webkit-slider-runnable-track { block-size: 16px; border-radius: 999px;
            background: linear-gradient(to right, #6750A4 var(--fill), #E6E0E9 var(--fill)); }
        .m3sld input::-webkit-slider-thumb { appearance: none; inline-size: 20px; aspect-ratio: 1;
            border-radius: 50%; border: 2px solid #6750A4; background: #fff; margin-block-start: -2px; }
        .m3sld:focus-within .m3sld-bubble, .m3sld:active .m3sld-bubble { opacity: 1; translate: 0 -6px; }
        </style>
        BLADE,
    ],

    'cards' => [
        'title' => ['fa' => 'کارت‌های متریال', 'en' => 'Material cards'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'سه خانوادهٔ کارت متریال ۳ — elevated با سایه، filled با سطح پر و outlined با خط دور — هر کدام با سرخط، متن پشتیبان و دکمه‌های پایین، دقیقاً با گردی ۱۲ پیکسل.',
            'en' => 'M3’s three card families — elevated with its shadow, filled with its tinted surface and outlined with its stroke — each with headline, supporting text and footer buttons, at the exact 12px radius.',
        ],
        'js' => false,
        'docs' => 'https://m3.material.io/components/cards/overview',
        'props' => [
            ['name' => 'variant', 'type' => 'string', 'default' => "'elevated'", 'note' => [
                'fa' => 'elevated · filled · outlined؛ روی هم نمایش دهید تا انتخاب راحت شود.',
                'en' => 'elevated · filled · outlined; show them side by side to choose.',
            ]],
            ['name' => '--m3card-radius', 'type' => 'length', 'default' => "'12px'", 'note' => [
                'fa' => 'گردی رسمی کارت متریال ۳.',
                'en' => 'The official M3 card radius.',
            ]],
            ['name' => 'elevation', 'type' => 'shadow', 'default' => "'level1'", 'note' => [
                'fa' => 'سایهٔ کارت elevated: 0 1px 2px با آلفای ۳۰٪ و ۱۵٪.',
                'en' => 'The elevated card’s shadow: 0 1px 2px at 30% and 15% alpha.',
            ]],
        ],
        'code' => <<<'BLADE'
        <article class="m3card" data-variant="elevated">
            <h3>آلبوم‌های امسال</h3>
            <p>۲۴ آلبوم تازه در کتابخانهٔ شما.</p>
            <footer><button>گوش دادن</button></footer>
        </article>

        <style>
        .m3card { inline-size: 340px; padding: 16px; border-radius: 12px; background: #F7F2FA; }
        .m3card[data-variant='elevated'] { background: #FFF; box-shadow: 0 1px 2px #0000004d, 0 1px 3px 1px #00000026; }
        .m3card[data-variant='outlined'] { background: #FFF; border: 1px solid #CAC4D0; }
        </style>
        BLADE,
    ],

    'bottom-sheet' => [
        'title' => ['fa' => 'شیت پایین صفحه', 'en' => 'Bottom sheet'],
        'icon' => 'chevron-up',
        'oneLiner' => [
            'fa' => 'شیت مدال متریال با دستگیرهٔ کشیدنی: با کشیدن به پایین یا لمس پشت‌زمینه جمع می‌شود، سرعت انگشت را می‌خواند و با فنر می‌نشیند؛ محتوای داخلش لیست اقدام‌های واقعی است.',
            'en' => 'Material’s modal bottom sheet with a drag handle: flick or tap the scrim to dismiss, it reads your finger’s velocity and settles with a spring; the body carries a real action list.',
        ],
        'js' => true,
        'docs' => 'https://m3.material.io/components/bottom-sheets/overview',
        'props' => [
            ['name' => 'drag-handle', 'type' => 'bar', 'default' => "'32 × 4'", 'note' => [
                'fa' => 'دستگیرهٔ ۳۲×۴ پیکسل بالای شیت؛ خودش هم ناحیهٔ کشیدن است.',
                'en' => 'A 32×4px handle above the sheet; it is a drag target itself.',
            ]],
            ['name' => 'velocity', 'type' => 'px/s', 'default' => "'≈ 500'", 'note' => [
                'fa' => 'پرتاب سریع به پایین شیت را می‌بندد حتی اگر نصفه راه باشد.',
                'en' => 'A fast downward flick closes the sheet even from half way.',
            ]],
            ['name' => 'scrim', 'type' => 'color', 'default' => "'#000 32%'", 'note' => [
                'fa' => 'پردهٔ پشت شیت با ۳۲٪ سیاهی متریال و محو شدن نرم.',
                'en' => 'The sheet’s scrim at Material’s 32% black with a soft fade.',
            ]],
        ],
        'code' => <<<'BLADE'
        <dialog class="m3sheet">
            <span class="m3sheet-handle"></span>
            <button class="m3sheet-action">اشتراک‌گذاری</button>
            <button class="m3sheet-action">دانلود آفلاین</button>
        </dialog>

        <style>
        .m3sheet { margin: auto 0 0; border-radius: 28px 28px 0 0; padding: 12px 24px 24px;
                   background: #F7F2FA; }
        .m3sheet-handle { display: block; inline-size: 32px; block-size: 4px; margin: 0 auto 12px;
                          border-radius: 999px; background: #CAC4D0; }
        .m3sheet[open] { animation: m3sheet-in .35s cubic-bezier(.05,.7,.1,1); }
        </style>
        BLADE,
    ],

    'navigation-bar' => [
        'title' => ['fa' => 'نوار پیمایش پایین', 'en' => 'Navigation bar'],
        'icon' => 'home',
        'oneLiner' => [
            'fa' => 'نوار پیمایش پایین متریال با قرص فعال که بین مقصدها می‌جنبد، برچسب که فقط زیر مقصد فعال پررنگ می‌شود و نشان (badge) روی آیکن؛ حرکت قرص با منحنی رسمی متریال است.',
            'en' => 'Material’s bottom navigation bar: the active pill glides between destinations, labels only fill for the active one, and icons carry badges; the pill moves on Material’s official curve.',
        ],
        'js' => true,
        'docs' => 'https://m3.material.io/components/navigation-bar/overview',
        'props' => [
            ['name' => 'active-indicator', 'type' => 'pill', 'default' => "'64 × 32'", 'note' => [
                'fa' => 'قرص ثانویه‌رنگ ۶۴×۳۲ پشت آیکن فعال.',
                'en' => 'A 64×32 secondary-container pill behind the active icon.',
            ]],
            ['name' => 'destinations', 'type' => 'count', 'default' => "'3–5'", 'note' => [
                'fa' => 'متریال سه تا پنج مقصد را توصیه می‌کند.',
                'en' => 'Material recommends three to five destinations.',
            ]],
            ['name' => 'badge', 'type' => 'dot|count', 'default' => "'dot'", 'note' => [
                'fa' => 'نقطه یا عدد؛ عدد تا ۹+ فشرده می‌شود.',
                'en' => 'A dot or a count; counts clamp to 9+.',
            ]],
        ],
        'code' => <<<'BLADE'
        <nav class="m3nav">
            <button class="m3nav-item" data-current><span class="m3nav-pill">🏠</span><span>خانه</span></button>
            <button class="m3nav-item"><span class="m3nav-pill" data-badge="3">🔍</span><span>جست‌وجو</span></button>
        </nav>

        <style>
        .m3nav { display: flex; block-size: 80px; background: #F3EDF7; }
        .m3nav-pill { display: grid; place-items: center; inline-size: 64px; block-size: 32px;
                      border-radius: 999px; transition: translate .3s cubic-bezier(.2,0,0,1); }
        .m3nav-item[data-current] .m3nav-pill { background: #E8DEF8; }
        .m3nav-item:not([data-current]) { color: #49454F; }
        </style>
        BLADE,
    ],

    'app-bar' => [
        'title' => ['fa' => 'نوار بالای صفحه', 'en' => 'Top app bar'],
        'icon' => 'menu',
        'oneLiner' => [
            'fa' => 'سه ارتفاع رسمی نوار بالا — small، medium و large — در یک قاب گوشی که با اسکرول جمع می‌شود: تیتر بزرگ آرام به خط نوار می‌نشیند و رنگ سطح به‌محض حرکت ظاهر می‌شود.',
            'en' => 'The three official top app bar heights — small, medium and large — in a phone frame that collapses on scroll: the big title settles onto the bar line while the surface colour appears the moment you move.',
        ],
        'js' => true,
        'docs' => 'https://m3.material.io/components/top-app-bar/overview',
        'props' => [
            ['name' => 'height', 'type' => 'px', 'default' => "'64 · 112 · 152'", 'note' => [
                'fa' => 'ارتفاع رسمی سه سایز small، medium و large.',
                'en' => 'The official heights of the small, medium and large bars.',
            ]],
            ['name' => 'scroll', 'type' => 'behaviour', 'default' => "'collapse'", 'note' => [
                'fa' => 'با اسکرول، large به small جمع می‌شود و سایهٔ سطح ۲ می‌گیرد.',
                'en' => 'On scroll the large bar collapses to small and gains the level-2 surface shadow.',
            ]],
            ['name' => 'actions', 'type' => 'count', 'default' => "'≤ 2'", 'note' => [
                'fa' => 'حداکثر دو آیکن اقدام + منوی سرریز.',
                'en' => 'At most two action icons plus the overflow menu.',
            ]],
        ],
        'code' => <<<'BLADE'
        <header class="m3bar" data-size="large">
            <button class="m3bar-icon">☰</button>
            <h1 class="m3bar-title">کتابخانهٔ من</h1>
            <button class="m3bar-icon">🔍</button>
        </header>

        <style>
        .m3bar { display: flex; align-items: center; gap: 8px; padding-inline: 16px;
                 transition: block-size .3s cubic-bezier(.2,0,0,1); }
        .m3bar[data-size='large'] { block-size: 152px; align-items: end; padding-block-end: 28px; }
        .m3bar-title { font: 400 28px/1.2 Roboto; transition: font-size .3s; }
        .m3bar[data-size='small'] { block-size: 64px; background: #F7F2FA; }
        .m3bar[data-size='small'] .m3bar-title { font-size: 22px; }
        </style>
        BLADE,
    ],

    'snackbar' => [
        'title' => ['fa' => 'اسنک‌بار', 'en' => 'Snackbar'],
        'icon' => 'message',
        'oneLiner' => [
            'fa' => 'اسنک‌بار متریال از پایین گوشه می‌آید، اکشنش با نقطه‌چین تا ۴ ثانیه شمرده می‌شود، صف می‌شود اگر دیر بفرستید و از جلوی FAB رد می‌شود نه روی آن.',
            'en' => 'Material’s snackbar slides up from the bottom, its action counts down to four seconds, it queues when you send another, and it clears the FAB instead of covering it.',
        ],
        'js' => true,
        'docs' => 'https://m3.material.io/components/snackbars/overview',
        'props' => [
            ['name' => 'duration', 'type' => 'ms', 'default' => "'4000'", 'note' => [
                'fa' => 'چهار تا ده ثانیه؛ نوار لایه‌ای زمان باقی‌مانده را نشان می‌دهد.',
                'en' => 'Four to ten seconds; a hairline layer counts the time left.',
            ]],
            ['name' => 'action', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'یک اکشن، در سمت مقابل متن، بدون پس‌زمینه.',
                'en' => 'One action, on the far side of the text, with no background.',
            ]],
            ['name' => 'queue', 'type' => 'behaviour', 'default' => "'sequential'", 'note' => [
                'fa' => 'اسنک‌بارها صف می‌شوند، روی هم نمی‌نشینند.',
                'en' => 'Snackbars queue; they never stack.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="m3snk" role="status">
            <span>قالب ذخیره شد</span>
            <button class="m3snk-action">واگرد</button>
        </div>

        <style>
        .m3snk { display: flex; align-items: center; gap: 8px; inline-size: min(100%, 360px);
                 padding: 14px 16px; border-radius: 4px; background: #322F35; color: #F5EFF7;
                 animation: m3snk-in .3s cubic-bezier(.05,.7,.1,1); }
        .m3snk-action { margin-inline-start: auto; color: #D0BCFF; }
        </style>
        BLADE,
    ],

    'dialog' => [
        'title' => ['fa' => 'دیالوگ‌ها', 'en' => 'Dialogs'],
        'icon' => 'alert-circle',
        'oneLiner' => [
            'fa' => 'دیالوگ پایهٔ متریال با آیکن، سرخط و دکمه‌های متنِ راست‌چین — و دیالوگ تمام‌صفحه برای جریان‌های چندمرحله‌ای؛ پرده پشتشان ۳۲٪ سیاه است و ورودشان از مقیاس ۸۰٪ فنر می‌خورد.',
            'en' => 'Material’s basic dialog with icon, headline and trailing text buttons — plus the full-screen dialog for multi-step flows; their scrim is 32% black and they spring in from an 80% scale.',
        ],
        'js' => true,
        'docs' => 'https://m3.material.io/components/dialogs/overview',
        'props' => [
            ['name' => 'variant', 'type' => 'string', 'default' => "'basic'", 'note' => [
                'fa' => 'basic یا full-screen؛ تمام‌صفحه نوار پایین «انصراف/ذخیره» دارد.',
                'en' => 'basic or full-screen; the full-screen one carries a bottom cancel/save bar.',
            ]],
            ['name' => 'radius', 'type' => 'length', 'default' => "'28px'", 'note' => [
                'fa' => 'گردی رسمی دیالوگ متریال ۳.',
                'en' => 'The official M3 dialog radius.',
            ]],
            ['name' => 'buttons', 'type' => 'layout', 'default' => "'text · end'", 'note' => [
                'fa' => 'دکمه‌ها متنِ بدون پس‌زمینه‌اند و در انتهای ردیف می‌نشینند.',
                'en' => 'Buttons are text-only and sit at the end of the row.',
            ]],
        ],
        'code' => <<<'BLADE'
        <dialog class="m3dlg">
            <span class="m3dlg-icon">🗑</span>
            <h2>حذف پوشه؟</h2>
            <p>هر ۲۴ فایل داخلش هم حذف می‌شود.</p>
            <footer>
                <button>انصراف</button>
                <button data-destructive>حذف</button>
            </footer>
        </dialog>

        <style>
        .m3dlg { border-radius: 28px; padding: 24px; inline-size: min(90vw, 360px);
                 background: #ECE6F0; color: #1D1B20; }
        .m3dlg footer { display: flex; justify-content: flex-end; gap: 8px; padding-block-start: 24px; }
        .m3dlg[open] { animation: m3dlg-in .4s cubic-bezier(.05,.7,.1,1); }
        </style>
        BLADE,
    ],

    'search' => [
        'title' => ['fa' => 'نوار و نمای جست‌وجو', 'en' => 'Search bar & view'],
        'icon' => 'search',
        'oneLiner' => [
            'fa' => 'نوار جست‌وجوی متریال ۳ که با لمس تمام صفحه به نمای جست‌وجو باز می‌شود: پیشنهادهای اخیر، فیلتر چیپ‌دار و نتایج زنده؛ برگشت با فلش نوار سیستم.',
            'en' => 'The M3 search bar that grows into a full-screen search view on touch: recent suggestions, filter chips and live results; back exits through the system arrow.',
        ],
        'js' => true,
        'docs' => 'https://m3.material.io/components/search-bar/overview',
        'props' => [
            ['name' => 'resting', 'type' => 'height', 'default' => "'56px'", 'note' => [
                'fa' => 'ارتفاع نوار در حالت آرام، تمام‌گرد، با آوا (avatar) و میکروفن.',
                'en' => 'The resting bar height, fully rounded, with an avatar and a mic.',
            ]],
            ['name' => 'expanded', 'type' => 'state', 'default' => "'full-screen'", 'note' => [
                'fa' => 'نمای باز، کل صفحه را می‌گیرد و فیلدها به ترتیب می‌افتند.',
                'en' => 'The expanded view takes the whole screen; sections cascade in.',
            ]],
            ['name' => 'leading · trailing', 'type' => 'slots', 'default' => "'search · voice'", 'note' => [
                'fa' => 'آیکن آغازین جست‌وجو، آیکن پایانی صدا با رنگ اصلی.',
                'en' => 'A leading search icon, a trailing tinted voice icon.',
            ]],
        ],
        'code' => <<<'BLADE'
        <button class="m3srch-bar" aria-label="جست‌وجو">
            <svg>🔍</svg> <span>چه چیزی می‌خواهید بشنوید؟</span>
            <img class="m3srch-avatar" src="…" alt="">
        </button>

        <style>
        .m3srch-bar { display: flex; align-items: center; gap: 12px; inline-size: 100%;
                      block-size: 56px; padding-inline: 16px; border-radius: 999px;
                      background: #ECE6F0; }
        .m3srch-avatar { margin-inline-start: auto; inline-size: 32px; border-radius: 50%; }
        </style>
        BLADE,
    ],

    'progress' => [
        'title' => ['fa' => 'نشانگرهای پیشرفت', 'en' => 'Progress indicators'],
        'icon' => 'trend-up',
        'oneLiner' => [
            'fa' => 'خانوادهٔ پیشرفت متریال: نوار خطی با نشانگر توقف و گپ، حلقهٔ چرخان نامعین، و موج‌های تازهٔ M3 Expressive که حتی روی مسیر معین موج می‌زنند.',
            'en' => 'Material’s progress family: the linear bar with its stop indicator and gap, the indeterminate ring, and the new M3 Expressive waves that undulate even while determinate.',
        ],
        'js' => true,
        'docs' => 'https://m3.material.io/components/progress-indicators/overview',
        'props' => [
            ['name' => 'variant', 'type' => 'string', 'default' => "'linear'", 'note' => [
                'fa' => 'linear · circular · wavy-linear · wavy-circular.',
                'en' => 'linear · circular · wavy-linear · wavy-circular.',
            ]],
            ['name' => 'value', 'type' => 'number', 'default' => 'null', 'note' => [
                'fa' => '۰ تا ۱۰۰؛ خالی یعنی نامعین و چرخان.',
                'en' => '0–100; empty means indeterminate and spinning.',
            ]],
            ['name' => 'stop-indicator', 'type' => 'gap', 'default' => "'4px'", 'note' => [
                'fa' => 'گپ ۴ پیکسلی و نقطهٔ انتهای ریل — امضای M3 2025.',
                'en' => 'A 4px gap and an end dot on the track — the 2025 M3 signature.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="m3prg" role="progressbar" aria-valuenow="60">
            <span class="m3prg-fill" style="inline-size: 60%"></span>
        </div>
        <svg class="m3prg-ring" viewBox="0 0 48 48"><circle class="m3prg-arc" cx="24" cy="24" r="20"/></svg>

        <style>
        .m3prg { block-size: 4px; border-radius: 999px; background: #E6E0E9; overflow: clip; }
        .m3prg-fill { display: block; block-size: 100%; border-radius: 999px;
                      background: #6750A4; transition: inline-size .3s; }
        .m3prg-ring { inline-size: 48px; }
        .m3prg-arc { fill: none; stroke: #6750A4; stroke-width: 4; stroke-linecap: round;
                     stroke-dasharray: 100.5; animation: m3prg-spin 1.4s linear infinite; }
        </style>
        BLADE,
    ],

    'dynamic-color' => [
        'title' => ['fa' => 'رنگ پویا', 'en' => 'Dynamic color'],
        'icon' => 'wand',
        'oneLiner' => [
            'fa' => 'قلب Material You: از رنگ کاغذدیواری یک پالت تُنال می‌سازد و همه‌چیز — نوار، FAB، چیپ، سوییچ — را با آن رنگ می‌کند. دانه را عوض کنید و کل رابط با یک انیمیشن رنگ عوض شود.',
            'en' => 'The heart of Material You: a wallpaper colour seeds a tonal palette that tints everything — bar, FAB, chips, switch. Change the seed and the whole interface recolours with one animated sweep.',
        ],
        'js' => true,
        'docs' => 'https://m3.material.io/styles/color-system/overview',
        'props' => [
            ['name' => 'seed', 'type' => 'color', 'default' => "'#6750A4'", 'note' => [
                'fa' => 'رنگ دانه؛ شش دانهٔ آماده + انتخاب دلخواه.',
                'en' => 'The seed colour; six presets plus a custom pick.',
            ]],
            ['name' => 'roles', 'type' => 'tokens', 'default' => "'8'", 'note' => [
                'fa' => 'primary، on-primary، container، surface، on-surface-var و… از پالت می‌آیند.',
                'en' => 'primary, on-primary, container, surface, on-surface-variant and friends come from the palette.',
            ]],
            ['name' => 'contrast', 'type' => 'ratio', 'default' => "'≥ 4.5'", 'note' => [
                'fa' => 'جفت‌رنگ‌ها تُن‌های ۴۰/۱۰۰ یا ۹۰/۱۰ هستند تا کنتراست متریال حفظ شود.',
                'en' => 'Pairs use tones 40/100 or 90/10 to keep Material’s contrast.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="m3dyn" style="--seed: #6750A4">
            <!-- every role derives from the seed’s tonal palette -->
            <header class="m3dyn-bar">…</header>
            <button class="m3dyn-fab">+</button>
        </div>

        <style>
        .m3dyn { --m3dyn-primary: var(--seed);
                 --m3dyn-on-primary: color-mix(in oklab, white 88%, var(--seed));
                 --m3dyn-container: color-mix(in oklab, var(--seed) 18%, white);
                 --m3dyn-surface: color-mix(in oklab, var(--seed) 4%, white); }
        .m3dyn-fab { background: var(--m3dyn-container); color: var(--m3dyn-on-primary); }
        </style>
        BLADE,
    ],

    'home-screen' => [
        'title' => ['fa' => 'لانچر: صفحهٔ اصلی', 'en' => 'Launcher: home screen'],
        'icon' => 'home',
        'oneLiner' => [
            'fa' => 'کل شل اندروید در یک قاب: صفحه‌های اصلی با ویجت و نقطه‌های صفحه، داک، کشیدن از بالا برای سایهٔ اعلان با کاشی‌های quick settings، کشیدن از پایین برای اپ‌دراور، نوار ژست و Material You که رنگش از کاغذدیواری می‌آید.',
            'en' => 'The whole Android shell in one frame: home pages with a widget and page dots, the dock, a pull-down notification shade with quick-settings tiles, a pull-up app drawer, the gesture pill, and Material You tinting from the wallpaper.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'shade', 'type' => 'gesture', 'default' => "'pull-down'", 'note' => [
                'fa' => 'کشیدن از نوار وضعیت سایه را با انگشت می‌کشد؛ رها کردن وسط راه به نزدیک‌ترین حالت فنر می‌خورد.',
                'en' => 'Dragging from the status bar pulls the shade with your finger; releasing mid-way springs to the nearest state.',
            ]],
            ['name' => 'drawer', 'type' => 'gesture', 'default' => "'pull-up'", 'note' => [
                'fa' => 'کشیدن از قرص ژست اپ‌دراور را باز می‌کند؛ جست‌وجویش زنده فیلتر می‌کند.',
                'en' => 'Dragging up from the gesture pill opens the drawer; its search live-filters.',
            ]],
            ['name' => 'wallpaper', 'type' => 'seed', 'default' => "'3'", 'note' => [
                'fa' => 'سه کاغذدیواری؛ کاشی‌ها و سوییچ‌ها رنگ اکسنت را از هوای آن می‌گیرند.',
                'en' => 'Three wallpapers; tiles and switches take their accent from its mood.',
            ]],
            ['name' => 'apps', 'type' => 'behaviour', 'default' => "'zoom-open'", 'note' => [
                'fa' => 'هر آیکن با بزرگ‌شدن باز می‌شود و کشیدن قرص به خانه برمی‌گرداند.',
                'en' => 'Icons zoom open; dragging the pill returns home.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="m3home" x-data="{ shade: 0, drawer: false }">
            <div class="m3home-shade" :style="`translate: 0 ${shade}%`">
                <div class="m3home-qs">…tiles + brightness…</div>
                …notifications…
            </div>
            <div class="m3home-pages">…widget + apps…</div>
            <div class="m3home-pill" x-on:pointerdown="dragDrawer($event)"></div>
        </div>

        <style>
        .m3home { position: relative; overflow: clip; border-radius: 2.25rem;
                  background: linear-gradient(160deg, #1b2a4a, #0e1526); }
        .m3home-shade { position: absolute; inset-inline: 0; inset-block-start: 0;
                        translate: 0 -100%; transition: translate .3s cubic-bezier(.2,0,0,1); }
        .m3home-qs { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; padding: 12px; }
        </style>
        BLADE,
    ],
];
