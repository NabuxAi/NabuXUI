<?php

/**
 * Demo manifest of the "lightning" group — Salesforce Lightning Design System
 * rebuilt as faithful, interactive skins: the sales Path with its marketing
 * colours and Mark-Stage-Complete CTA, the Record Home header with its
 * Highlights panel, Related Lists, the Docked Utility Bar with in-place
 * panels, the Welcome Mat onboarding checklist, Scoped Tabs, the App
 * Launcher waffle, the Chatter feed and the Dueling Picklist. Scenarios live
 * at resources/views/demos/components/lightning/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'سیلزفورس — لایتنینگ', 'en' => 'Salesforce Lightning'],

    'path' => [
        'title' => ['fa' => 'مسیر فروش (Path)', 'en' => 'Path'],
        'icon' => 'trend-up',
        'oneLiner' => [
            'fa' => 'نوار افقی مراحل فروش با رنگ‌های مارکتینگ و دکمهٔ «تکمیل مرحله»؛ رکورد را از دید معامله‌گر سلس‌فورس لو می‌دهد.',
            'en' => 'The horizontal stage bar with marketing-colored chevrons and a Mark-Stage-Complete CTA — instantly reads as an opportunity on the move.',
        ],
        'js' => true,
        'docs' => 'https://www.lightningdesignsystem.com/components/path/',
        'props' => [
            ['name' => 'stage-status', 'type' => 'enum', 'default' => "'current'", 'note' => [
                'fa' => 'complete با تیک، current سفید روی رنگ مرحله، incomplete خاکستری و lost قرمز پالت مارکتینگ.',
                'en' => 'complete shows a tick, current sits white on the stage colour, incomplete is grey and lost wears the palette’s red.',
            ]],
            ['name' => 'stage-color', 'type' => 'color', 'default' => "'#0176D3'", 'note' => [
                'fa' => 'رنگ هر مرحله از پالت مارکتینگ سلس‌فورس می‌آید: آبی، سبزآبی، بنفش، نارنجی، صورتی، زرد، قرمز و سبز.',
                'en' => 'Each stage takes a Marketing Cloud colour: blue, teal, purple, orange, pink, yellow, red and green.',
            ]],
            ['name' => 'mark-complete', 'type' => 'event', 'default' => "'click'", 'note' => [
                'fa' => 'دکمهٔ همیشه‌نمایان «تکمیل مرحله» پایین نوار، مرحلهٔ جاری را کامل و نوار را یک قدم جلو می‌برد.',
                'en' => 'The always-visible Mark-Stage-Complete button closes the current stage and nudges the path one step forward.',
            ]],
            ['name' => 'coaching', 'type' => 'field', 'default' => 'null', 'note' => [
                'fa' => 'زیر مرحلهٔ جاری، فیلد راهنمای معامله («گام بعدی») می‌نشیند؛ همان coaching path معروف.',
                'en' => 'A coaching field — the next step — sits under the current stage: the famous coaching path.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="sflx-path" data-current="4">
            <ol>
                <li class="sflx-step" data-status="complete" style="--stage: #0176D3">Prospecting</li>
                <li class="sflx-step" data-status="complete" style="--stage: #0B827C">Qualification</li>
                <li class="sflx-step" data-status="current" style="--stage: #6739B7">Needs Analysis</li>
                <li class="sflx-step" data-status="incomplete" style="--stage: #FE9339">Value Proposition</li>
            </ol>
            <button class="sflx-mark">Mark Stage as Complete</button>
        </div>

        <style>
        .sflx-path ol { display: flex; }
        .sflx-step { flex: 1; clip-path: polygon(0 0, calc(100% - 12px) 0, 100% 50%,
                    calc(100% - 12px) 100%, 0 100%, 12px 50%); padding: .5rem 1.5rem;
                    background: #E8E8E8; }
        .sflx-step[data-status='complete'] { background: var(--stage); color: #fff; }
        .sflx-step[data-status='current'] { background: var(--stage); color: #fff; font-weight: 700; }
        .sflx-mark { margin: .5rem 0 0 auto; background: var(--stage); color: #fff; border-radius: .25rem; }
        </style>
        BLADE,
    ],

    'record-home' => [
        'title' => ['fa' => 'خانهٔ رکورد (Record Home)', 'en' => 'Record Home'],
        'icon' => 'home',
        'oneLiner' => [
            'fa' => 'سربرگ رکورد با آیکن تکی، نوار اکشن‌ها و پنل هایلایت؛ قاب استانداردی که هر آبجکت لایتنینگ در آن جا می‌گیرد.',
            'en' => 'Icon tile, action rail and the Highlights panel in one header — the frame every Lightning record lives inside.',
        ],
        'js' => true,
        'docs' => 'https://v1.lightningdesignsystem.com/components/page-headers/',
        'props' => [
            ['name' => 'icon-tile', 'type' => 'slot', 'default' => "'letter'", 'note' => [
                'fa' => 'کاشی آیکن با پس‌زمینهٔ رنگ آبجکت و حرف اول رکورد؛ اندازهٔ بزرگ در سربرگ.',
                'en' => 'An icon tile in the object colour with the record’s initial; oversized in the header.',
            ]],
            ['name' => 'highlights', 'type' => 'panel', 'default' => "'open'", 'note' => [
                'fa' => 'پنل هایلایت زیر عنوان، فیلدهای کلیدی (مبلغ، تاریخ، مرحله) را با برچسب کوچک ردیف می‌کند.',
                'en' => 'The Highlights panel lays the key fields — amount, date, stage — under the title with small labels.',
            ]],
            ['name' => 'action-rail', 'type' => 'buttons', 'default' => "'edit · …'", 'note' => [
                'fa' => 'اکشن اصلی دکمهٔ پررنگ برند است و بقیه به منوی سرریز با آیکن … می‌روند.',
                'en' => 'The primary action is the brand button; the rest fold into the ⋯ overflow menu.',
            ]],
            ['name' => 'follow', 'type' => 'toggle', 'default' => 'false', 'note' => [
                'fa' => 'ستارهٔ کنار عنوان رکورد را دنبال می‌کند و پر شدنش خبر می‌دهد.',
                'en' => 'The star beside the title follows the record; filling it announces the change.',
            ]],
        ],
        'code' => <<<'BLADE'
        <header class="sflx-record">
            <span class="sflx-tile" data-object="opportunity">ع</span>
            <div>
                <p class="sflx-crumbs">فرصت‌ها / قرارداد سالانه نابو</p>
                <h1>قرارداد سالانه نابو
                    <button class="sflx-follow" aria-pressed="false" aria-label="دنبال کردن">★</button>
                </h1>
            </div>
            <div class="sflx-actions">
                <button class="sflx-btn" data-variant="brand">ویرایش</button>
                <button class="sflx-btn" data-variant="neutral">حذف</button>
            </div>
        </header>
        <dl class="sflx-highlights">
            <div><dt>مبلغ</dt><dd>۲٬۴۰۰٬۰۰۰٬۰۰۰ ریال</dd></div>
            <div><dt>مرحله</dt><dd>مذاکره</dd></div>
        </dl>

        <style>
        .sflx-record { display: flex; gap: 1rem; align-items: center; padding: 1rem; background: #fff; }
        .sflx-tile { display: grid; place-items: center; inline-size: 3rem; aspect-ratio: 1;
                     border-radius: .25rem; background: #0B5CAB; color: #fff; font-weight: 700; }
        .sflx-highlights { display: flex; gap: 2rem; border-block-start: 1px solid #DDDBDA; padding: .75rem 1rem; }
        .sflx-highlights dt { font-size: .75rem; color: #706E6B; }
        </style>
        BLADE,
    ],

    'related-list' => [
        'title' => ['fa' => 'لیست مرتبط (Related List)', 'en' => 'Related List'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'کارت‌های «موارد مرتبط» با سربرگ آیکن‌دار و دکمهٔ View All؛ رابطهٔ آبجکت‌ها را بی‌هیچ توضیحی لو می‌دهد.',
            'en' => 'Icon-headed cards of child records with a View All footer — object relationships at a glance.',
        ],
        'js' => true,
        'docs' => 'https://www.lightningdesignsystem.com/components/cards/',
        'props' => [
            ['name' => 'icon-header', 'type' => 'slot', 'default' => "'object'", 'note' => [
                'fa' => 'آیکن کوچک آبجکت کنار عنوان کارت می‌نشیند و آبجکتِ مرتبط را در نگاه اول می‌گوید.',
                'en' => 'The object’s small icon sits beside the card title and names the related object at a glance.',
            ]],
            ['name' => 'count', 'type' => 'number', 'default' => "'title'", 'note' => [
                'fa' => 'شمار رکوردها داخل پرانتز کنار عنوان: «مخاطب‌ها (۵)».',
                'en' => 'The record count rides in parentheses on the title: «Contacts (5)».',
            ]],
            ['name' => 'view-all', 'type' => 'footer', 'default' => "'link'", 'note' => [
                'fa' => 'پانویس کارت با لینک View All به نمای کامل لیست می‌رود.',
                'en' => 'The card footer’s View All link opens the list’s full view.',
            ]],
            ['name' => 'row-actions', 'type' => 'menu', 'default' => "'⋯'", 'note' => [
                'fa' => 'هر ردیف منوی سرریز خودش را دارد: ویرایش، حذف، بازکردن.',
                'en' => 'Every row carries its own overflow menu: edit, delete, open.',
            ]],
        ],
        'code' => <<<'BLADE'
        <article class="sflx-related">
            <header>
                <span class="sflx-rl-icon" data-object="contact">م</span>
                <h2>مخاطب‌ها (۳)</h2>
                <button class="sflx-btn" data-variant="neutral">جدید</button>
            </header>
            <ul>
                <li><b>سارا احمدی</b><span>sara@acme.example</span></li>
                <li><b>رضا کاظمی</b><span>reza@acme.example</span></li>
            </ul>
            <footer><a href="#">مشاهدهٔ همه</a></footer>
        </article>

        <style>
        .sflx-related { border: 1px solid #DDDBDA; border-radius: .25rem; background: #fff; }
        .sflx-related header { display: flex; align-items: center; gap: .5rem; padding: .75rem 1rem; }
        .sflx-rl-icon { inline-size: 1.5rem; aspect-ratio: 1; display: grid; place-items: center;
                        border-radius: .25rem; background: #0B5CAB; color: #fff; font-size: .7rem; }
        .sflx-related ul li { display: grid; padding: .5rem 1rem; border-block-start: 1px solid #DDDBDA; }
        </style>
        BLADE,
    ],

    'docked-utility-bar' => [
        'title' => ['fa' => 'نوار ابزار چسبیده (Docked Utility Bar)', 'en' => 'Docked Utility Bar'],
        'icon' => 'menu',
        'oneLiner' => [
            'fa' => 'نوار آیکنی چسبیده به پایین صفحه که با هر کلیک پنل کاربردی باز می‌کند؛ ابزار همیشگی کارشناس در لایتنینگ.',
            'en' => 'A footer of utility icons that pop open panels in place — the agent’s always-on toolbox docked to the page bottom.',
        ],
        'js' => true,
        'docs' => 'https://v1.lightningdesignsystem.com/components/docked-utility-bar/',
        'props' => [
            ['name' => 'panel', 'type' => 'popover', 'default' => "'above'", 'note' => [
                'fa' => 'پنل بالای آیکن باز می‌شود، فقط یکی در هر لحظه و لمس بیرون یا همان آیکن می‌بنددش.',
                'en' => 'The panel opens above its icon, one at a time; an outside tap or the same icon closes it.',
            ]],
            ['name' => 'badge', 'type' => 'count', 'default' => 'null', 'note' => [
                'fa' => 'آیکن‌ها نشان شمار می‌گیرند؛ اعداد بالای ۹ فشرده به ۹+.',
                'en' => 'Icons carry count badges; anything past 9 clamps to 9+.',
            ]],
            ['name' => 'active', 'type' => 'state', 'default' => "'panel-open'", 'note' => [
                'fa' => 'آیکنِ پنل باز، نوار آبی برند زیرش می‌گیرد و تا بسته شدن روشن می‌ماند.',
                'en' => 'The open panel’s icon gains a brand-blue bar beneath and stays lit until closed.',
            ]],
            ['name' => 'items', 'type' => 'count', 'default' => "'4–8'", 'note' => [
                'fa' => 'چهار تا هشت ابزار؛ متجاوز در منوی بیشتر می‌نشیند.',
                'en' => 'Four to eight utilities; the overflow folds into a More menu.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="sflx-util">
            <div class="sflx-util-panel" role="dialog" aria-label="گفت‌وگو">…</div>
            <nav class="sflx-util-bar">
                <button aria-expanded="true" aria-label="گفت‌وگو"><x-nx::icon name="message" /><b>۳</b></button>
                <button aria-expanded="false" aria-label="اعلان‌ها"><x-nx::icon name="bell" /><b>۹+</b></button>
            </nav>
        </div>

        <style>
        .sflx-util { position: relative; }
        .sflx-util-bar { display: flex; background: #0B5CAB; color: #fff; }
        .sflx-util-bar button { inline-size: 3rem; block-size: 2.75rem; position: relative; }
        .sflx-util-bar button[aria-expanded='true'] { box-shadow: inset 0 -3px 0 #fff; }
        .sflx-util-panel { position: absolute; inset-inline: 0; inset-block-end: 2.75rem;
                           block-size: 16rem; background: #fff; border: 1px solid #DDDBDA; }
        </style>
        BLADE,
    ],

    'welcome-mat' => [
        'title' => ['fa' => 'مات خوش‌آمد (Welcome Mat)', 'en' => 'Welcome Mat'],
        'icon' => 'sparkles',
        'oneLiner' => [
            'fa' => 'آنبوردینگ چک‌لیستی با تصویر راهنما و تیک‌های پیشرفت؛ اولین برخورد کاربر با یک اپ تازه‌نصب لایتنینگ.',
            'en' => 'A checklist-onboarding card with an illustration and progress ticks — the first hello of every new Lightning app.',
        ],
        'js' => true,
        'docs' => 'https://v1.lightningdesignsystem.com/components/welcome-mat/',
        'props' => [
            ['name' => 'split', 'type' => 'layout', 'default' => "'info · steps'", 'note' => [
                'fa' => 'ستون تصویر و معرفی یک‌سو، ستون چک‌لیست وظایف سوی دیگر؛ در موبایل زیر هم.',
                'en' => 'An intro/illustration column beside a column of checklist steps; stacked on mobile.',
            ]],
            ['name' => 'progress', 'type' => 'counter', 'default' => "'n of m'", 'note' => [
                'fa' => 'تیک هر وظیفه شمارنده را جلو می‌برد: «۲ از ۴ تکمیل شد».',
                'en' => 'Each tick advances the counter: «2 of 4 completed».',
            ]],
            ['name' => 'complete', 'type' => 'state', 'default' => "'done'", 'note' => [
                'fa' => 'با تکمیل همه، مات حالت سبز «همه‌چیز آماده» می‌گیرد.',
                'en' => 'Complete every step and the mat flips to the green all-set state.',
            ]],
            ['name' => 'dismiss', 'type' => 'event', 'default' => "'x'", 'note' => [
                'fa' => 'دکمهٔ ضربدر بالای کارت مات را برای همیشه می‌بندد.',
                'en' => 'The ✕ on the card dismisses the mat for good.',
            ]],
        ],
        'code' => <<<'BLADE'
        <section class="sflx-mat">
            <button class="sflx-mat-x" aria-label="بستن">✕</button>
            <figure class="sflx-mat-info">
                <span class="sflx-mat-art"></span>
                <figcaption>به «میز فروش نابو» خوش آمدید</figcaption>
            </figure>
            <ol class="sflx-mat-steps">
                <li><button aria-pressed="true">✓ پروفایل را کامل کنید</button></li>
                <li><button aria-pressed="false">اولین فرصت را بسازید</button></li>
            </ol>
        </section>

        <style>
        .sflx-mat { position: relative; display: grid; grid-template-columns: 1fr 1fr;
                    border: 1px solid #DDDBDA; border-radius: .25rem; background: #fff; }
        .sflx-mat-info { background: linear-gradient(135deg, #0B5CAB, #0176D3); color: #fff; }
        .sflx-mat-steps li button { inline-size: 100%; text-align: start; padding: .75rem 1rem; }
        .sflx-mat-steps li[aria-pressed='true'] button { color: #04844B; }
        </style>
        BLADE,
    ],

    'scoped-tabs' => [
        'title' => ['fa' => 'تب‌های اسکوپ‌دار (Scoped Tabs)', 'en' => 'Scoped Tabs'],
        'icon' => 'folder',
        'oneLiner' => [
            'fa' => 'تب‌های هم‌رنگ برند که محتوای زیر خود را قاب می‌کنند؛ نسخهٔ رکوردیِ تب که ناوبری لایتنینگ را از هر تب دیگری جدا می‌کند.',
            'en' => 'Brand-colored tabs that frame their own content area — navigation that only a record app wears.',
        ],
        'js' => true,
        'docs' => 'https://www.lightningdesignsystem.com/components/tabs/',
        'props' => [
            ['name' => 'scope', 'type' => 'color', 'default' => "'per-tab'", 'note' => [
                'fa' => 'هر تب رنگ خودش را از پالت مارکتینگ می‌گیرد و با انتخاب، نوارِ تب‌ها به همان رنگ درمی‌آید.',
                'en' => 'Each tab owns a Marketing Cloud colour; selecting one repaints the whole tab bar with it.',
            ]],
            ['name' => 'frame', 'type' => 'border', 'default' => "'1px'", 'note' => [
                'fa' => 'قاب یک‌پیکسلی دور ناحیهٔ محتوا، رنگش همان رنگ تب فعال است.',
                'en' => 'A 1px frame wraps the content area, painted in the active tab’s colour.',
            ]],
            ['name' => 'panel', 'type' => 'slot', 'default' => "'record'", 'note' => [
                'fa' => 'هر پنل محتوای رکوردی خودش را دارد: جزئیات، مرتبط‌ها، فعالیت.',
                'en' => 'Every panel carries its own record content: details, related, activity.',
            ]],
            ['name' => 'overflow', 'type' => 'behaviour', 'default' => "'scroll'", 'note' => [
                'fa' => 'تب‌های بیش از عرض، افقی داخل خودِ نوار اسکرول می‌شوند نه کل صفحه.',
                'en' => 'Tabs beyond the width scroll horizontally inside the bar, never the page.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="sflx-stabs" style="--scope: #0176D3" data-active="details">
            <div role="tablist">
                <button role="tab" aria-selected="true">جزئیات</button>
                <button role="tab" aria-selected="false" style="--scope: #04844B">مرتبط</button>
            </div>
            <div role="tabpanel">…محتوای جزئیات…</div>
        </div>

        <style>
        .sflx-stabs { border: 1px solid #DDDBDA; border-radius: .25rem; background: #fff; }
        .sflx-stabs [role='tablist'] { display: flex; background: var(--scope); padding: 0 .25rem; }
        .sflx-stabs [role='tab'] { padding: .75rem 1.25rem; color: #181818; }
        .sflx-stabs [role='tab'][aria-selected='true'] { background: #fff; font-weight: 700;
            box-shadow: inset 0 3px 0 var(--scope); }
        .sflx-stabs [role='tabpanel'] { padding: 1rem; border-block-start: 1px solid var(--scope); }
        </style>
        BLADE,
    ],

    'app-launcher' => [
        'title' => ['fa' => 'لانچر اپ‌ها (App Launcher)', 'en' => 'App Launcher'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'دکمهٔ وافل معروف که شبکهٔ کاشی‌های رنگی «همهٔ اپ‌ها» را باز می‌کند؛ امضای ناوبری سلس‌فورس از همان نگاه اول.',
            'en' => 'The famous waffle button opening a tile grid of all apps — the switcher every Salesforce screen starts with.',
        ],
        'js' => true,
        'docs' => 'https://v1.lightningdesignsystem.com/components/app-launcher/',
        'props' => [
            ['name' => 'waffle', 'type' => 'button', 'default' => "'3×3'", 'note' => [
                'fa' => 'دکمهٔ ۹نقطه‌ای سربرگ؛ با کلیک پنل اپ‌ها باز می‌شود و Escape می‌بندد.',
                'en' => 'The header’s 3×3 dot button; it springs the app panel open and Escape closes it.',
            ]],
            ['name' => 'search', 'type' => 'input', 'default' => "'live'", 'note' => [
                'fa' => 'جست‌وجوی بالای پنل، همان لحظه کاشی‌ها را فیلتر می‌کند.',
                'en' => 'The search on top of the panel live-filters the tiles as you type.',
            ]],
            ['name' => 'tile', 'type' => 'grid', 'default' => "'3-col'", 'note' => [
                'fa' => 'کاشی‌های رنگی با حرف اول اپ؛ هر بخش اپ‌های خودش را دارد.',
                'en' => 'Colour tiles with the app’s initial; sections group their own apps.',
            ]],
            ['name' => 'all-apps', 'type' => 'link', 'default' => "'footer'", 'note' => [
                'fa' => 'پانویس با لینک «همهٔ اپ‌ها» به نمای تمام‌صفحه می‌رود.',
                'en' => 'The footer’s «All Apps» link opens the full-page view.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="sflx-launcher">
            <button class="sflx-waffle" aria-expanded="false" aria-label="همهٔ اپ‌ها">
                <i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i>
            </button>
            <div class="sflx-apps" role="dialog" aria-label="اپ‌ها">
                <input type="search" placeholder="جست‌وجوی اپ‌ها…">
                <section>
                    <h3>فروش</h3>
                    <a class="sflx-tile" style="--tile: #0B5CAB" href="#"><b>ف</b>فروش</a>
                </section>
            </div>
        </div>

        <style>
        .sflx-launcher { position: relative; }
        .sflx-waffle { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2px; padding: .4rem; }
        .sflx-waffle i { inline-size: 3px; aspect-ratio: 1; border-radius: 50%; background: currentColor; }
        .sflx-apps { position: absolute; inset-block-start: 100%; inline-size: 20rem;
                     background: #fff; border: 1px solid #DDDBDA; border-radius: .25rem; }
        .sflx-tile b { display: grid; place-items: center; inline-size: 2rem; aspect-ratio: 1;
                       border-radius: .25rem; background: var(--tile); color: #fff; }
        </style>
        BLADE,
    ],

    'chatter-feed' => [
        'title' => ['fa' => 'فید چَتِر (Chatter Feed)', 'en' => 'Chatter Feed'],
        'icon' => 'message',
        'oneLiner' => [
            'fa' => 'جعبهٔ «اشتراک به‌روزرسانی» بالای فیدی از پست، لایک و کامنت؛ لایهٔ اجتماعیِ همکاری که امضای سلس‌فورس است.',
            'en' => 'A share-an-update publisher above posts, likes and threaded comments — social collaboration that screams Salesforce.',
        ],
        'js' => true,
        'docs' => 'https://v1.lightningdesignsystem.com/components/feeds/',
        'props' => [
            ['name' => 'publisher', 'type' => 'composer', 'default' => "'collapsed'", 'note' => [
                'fa' => 'جعبهٔ اشتراک با «اشتراک به‌روزرسانی…» بسته است؛ تمرکز، ابزار پیوست و دکمهٔ اشتراک را باز می‌کند.',
                'en' => 'The publisher rests collapsed on «Share an update…»; focus opens the attach tools and the Share button.',
            ]],
            ['name' => 'like', 'type' => 'toggle', 'default' => "'count'", 'note' => [
                'fa' => 'قلبِ لایک شمارنده دارد و لمس دوباره برمی‌گرداند؛ اعداد فارسی.',
                'en' => 'The like heart carries a counter and untoggles on a second tap.',
            ]],
            ['name' => 'comment', 'type' => 'thread', 'default' => "'nested'", 'note' => [
                'fa' => 'کامنت‌ها زیر پست با ورودی «نظر بنویسید…» رشته می‌شوند.',
                'en' => 'Comments thread beneath the post above a «Write a comment…» input.',
            ]],
            ['name' => 'avatar', 'type' => 'initials', 'default' => "'2-letter'", 'note' => [
                'fa' => 'آواتار دایره‌ای با دو حرف اول نام کاربر؛ هر کاربر رنگ خودش.',
                'en' => 'A circular avatar of the user’s two initials; every user owns a colour.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="sflx-feed">
            <form class="sflx-publisher">
                <span class="sflx-avatar" data-user="me">من</span>
                <input type="text" placeholder="اشتراک به‌روزرسانی…">
                <button type="submit" class="sflx-btn" data-variant="brand">اشتراک</button>
            </form>
            <article class="sflx-post">
                <header><span class="sflx-avatar" data-user="sara">سا</span><b>سارا احمدی</b><time>۲ ساعت پیش</time></header>
                <p>پیش‌فاکتور را برای اکیوم فرستادم ✅</p>
                <footer>
                    <button class="sflx-like" aria-pressed="false">♥ لایک ۳</button>
                    <button>نظر ۲</button>
                </footer>
            </article>
        </div>

        <style>
        .sflx-publisher { display: flex; gap: .5rem; padding: .75rem 1rem; background: #fff;
                          border: 1px solid #DDDBDA; border-radius: .25rem; }
        .sflx-avatar { display: grid; place-items: center; inline-size: 2rem; aspect-ratio: 1;
                       border-radius: 50%; background: #0B5CAB; color: #fff; font-size: .7rem; }
        .sflx-post { margin-block-start: .75rem; padding: .75rem 1rem; background: #fff;
                     border: 1px solid #DDDBDA; border-radius: .25rem; }
        </style>
        BLADE,
    ],

    'dueling-picklist' => [
        'title' => ['fa' => 'دوگانه‌انتخاب (Dueling Picklist)', 'en' => 'Dueling Picklist'],
        'icon' => 'sliders',
        'oneLiner' => [
            'fa' => 'دو لیست «در دسترس» و «انتخاب‌شده» با فلش‌های انتقال؛ کلاسیک صفحه‌های پیکربندی ادمین سلس‌فورس.',
            'en' => 'Two listboxes with move arrows between them — the admin-configuration classic, straight from Lightning setup screens.',
        ],
        'js' => true,
        'docs' => 'https://v1.lightningdesignsystem.com/components/dueling-picklist/',
        'props' => [
            ['name' => 'move', 'type' => 'buttons', 'default' => "'‹ ›'", 'note' => [
                'fa' => 'ستون فلش‌ها بین دو لیست: بردن به انتخاب‌شده، برگرداندن، و نسخهٔ دوتایی برای همه.',
                'en' => 'An arrow rail between the lists: move to chosen, move back, plus double arrows for all.',
            ]],
            ['name' => 'multi-select', 'type' => 'behaviour', 'default' => "'click'", 'note' => [
                'fa' => 'کلیک با Ctrl/⌘ چند گزینه را انتخاب نگه می‌دارد؛ فلش‌ها تا انتخاب غیرفعال‌اند.',
                'en' => 'Click with Ctrl/⌘ keeps several options selected; arrows stay disabled until then.',
            ]],
            ['name' => 'reorder', 'type' => 'buttons', 'default' => "'↑ ↓'", 'note' => [
                'fa' => 'فلش‌های بالا/پایین کنار لیست انتخاب‌شده، ترتیب را می‌چینند.',
                'en' => 'Up/down arrows beside the chosen list set the order.',
            ]],
            ['name' => 'option-meta', 'type' => 'label', 'default' => "'secondary'", 'note' => [
                'fa' => 'زیر هر گزینه، توضیح دوم خط با رنگ کم‌رنگ می‌آید.',
                'en' => 'A muted second line of meta rides under each option.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="sflx-duel">
            <fieldset>
                <legend>در دسترس (۳)</legend>
                <ul role="listbox" aria-multiselectable="true">
                    <li role="option" aria-selected="false">فرصت‌ها</li>
                    <li role="option" aria-selected="true">مخاطب‌ها</li>
                </ul>
            </fieldset>
            <div class="sflx-duel-rail">
                <button aria-label="بردن به انتخاب‌شده">›</button>
                <button aria-label="برگرداندن به در دسترس">‹</button>
            </div>
            <fieldset>
                <legend>انتخاب‌شده (۱)</legend>
                <ul role="listbox"></ul>
            </fieldset>
        </div>

        <style>
        .sflx-duel { display: grid; grid-template-columns: 1fr auto 1fr; gap: 1rem; }
        .sflx-duel fieldset { border: 1px solid #DDDBDA; border-radius: .25rem; background: #fff; }
        .sflx-duel li[aria-selected='true'] { background: #EAF5FE; box-shadow: inset 2px 0 0 #0176D3; }
        .sflx-duel-rail { display: grid; align-content: center; gap: .25rem; }
        .sflx-duel-rail button { inline-size: 2rem; block-size: 2rem; border: 1px solid #DDDBDA;
                                 border-radius: .25rem; }
        </style>
        BLADE,
    ],
];
