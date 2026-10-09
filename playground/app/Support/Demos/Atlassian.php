<?php

/**
 * Demo manifest of the "atlassian" group — Atlassian Design System (Atlaskit)
 * rebuilt as faithful, interactive skins: the Jira/Confluence page header with
 * its tabs, the lozenge status chip, section messages, flags, inline messages,
 * the onboarding spotlight tour, the side navigation with its square icon
 * items, the illustrated empty state and the comment thread. Scenarios live at
 * resources/views/demos/components/atlassian/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'اطلس‌آثار (اطلس‌سین)', 'en' => 'Atlassian Design System'],

    'page-header' => [
        'title' => ['fa' => 'هدر صفحهٔ اطلسیان', 'en' => 'Page Header'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'تیتر صفحه، دکمه‌های اکشن و تب‌ها در یک ردیف آشنا — قاب ثابتی که هر صفحهٔ جیرا و کانفلوئنس با آن باز می‌شود.',
            'en' => 'Page title, action buttons and tabs in one familiar row — the fixed frame every Jira and Confluence page opens with.',
        ],
        'js' => true,
        'docs' => 'https://atlassian.design/components/page-header/usage',
        'props' => [
            ['name' => 'breadcrumb', 'type' => 'slot', 'default' => 'null', 'note' => [
                'fa' => 'مسیر بالای تیتر؛ لینک‌ها با فاصلهٔ ۴ پیکسل از هم جدا می‌شوند.',
                'en' => 'The trail above the title; links sit 4px apart.',
            ]],
            ['name' => 'actions', 'type' => 'slot', 'default' => 'null', 'note' => [
                'fa' => 'ردیف دکمه‌ها در انتهای سطر تیتر؛ اصلیِ آبی اول می‌آید.',
                'en' => 'The button row at the end of the title line; the blue primary comes first.',
            ]],
            ['name' => 'tabs', 'type' => 'slot', 'default' => 'null', 'note' => [
                'fa' => 'تب‌های زیر تیتر با زیرخطِ آبیِ دوپیکسلی روی تب فعال.',
                'en' => 'The tabs under the title, with a 2px blue underline on the active one.',
            ]],
            ['name' => 'bottomBar', 'type' => 'slot', 'default' => 'null', 'note' => [
                'fa' => 'نوار پایین هدر؛ برای متادیتا مثل «آخرین به‌روزرسانی».',
                'en' => 'The bar at the header’s bottom; for metadata like the last update.',
            ]],
        ],
        'code' => <<<'BLADE'
        <header class="atph">
            <nav class="atph-crumbs"><a href="#">پروژه‌ها</a><span>/</span><b>نابو</b></nav>
            <div class="atph-row">
                <h1>طراحی رابط نابو ۲</h1>
                <div class="atph-actions">
                    <button class="atph-btn" data-kind="primary">ایجاد</button>
                    <button class="atph-btn">دعوت همکاران</button>
                </div>
            </div>
            <nav class="atph-tabs"><button data-current>نمای کلی</button><button>کارها</button></nav>
        </header>

        <style>
        .atph { display: grid; gap: 12px; padding-block: 16px; }
        .atph-crumbs { font: 400 12px Inter, system-ui; color: #626F86; }
        .atph-row { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
        .atph-row h1 { font: 500 24px/1.2 "Charlie Display", Inter, system-ui; color: #172B4D; }
        .atph-actions { margin-inline-start: auto; display: flex; gap: 8px; }
        .atph-btn { block-size: 32px; padding-inline: 12px; border-radius: 3px; border: none;
                    background: #091E42; color: #FFFFFF; font: 500 14px Inter, system-ui; }
        .atph-btn[data-kind='primary'] { background: #0C66E4; }
        .atph-tabs { display: flex; gap: 4px; border-block-end: 2px solid #091E421F; }
        .atph-tabs button { padding: 8px 12px; border: none; background: none; color: #44546F;
                            border-block-end: 2px solid transparent; margin-block-end: -2px; }
        .atph-tabs button[data-current] { color: #0C66E4; border-block-end-color: #0C66E4; }
        </style>
        BLADE,
    ],

    'lozenge' => [
        'title' => ['fa' => 'لوزِنج (برچسب وضعیت)', 'en' => 'Lozenge'],
        'icon' => 'zap',
        'oneLiner' => [
            'fa' => 'برچسب کوچک نیم‌گرد با رنگ ملایم یا غلیظ — همان چیزی که برچسب «In Progress» جیرا را در نگاه اول لو می‌دهد.',
            'en' => 'The small half-rounded status chip with subtle or bold color — the reason a Jira ' . "'" . 'In Progress' . "'" . ' label is recognizable at first glance.',
        ],
        'js' => true,
        'docs' => 'https://atlassian.design/components/lozenge/usage',
        'props' => [
            ['name' => 'appearance', 'type' => 'string', 'default' => "'default'", 'note' => [
                'fa' => 'default · successful · removed · inprogress · moved · new.',
                'en' => 'default · successful · removed · inprogress · moved · new.',
            ]],
            ['name' => 'isBold', 'type' => 'boolean', 'default' => 'false', 'note' => [
                'fa' => 'ملایم یعنی سطح کم‌رنگ با متن تیره؛ غلیظ یعنی پس‌زمینهٔ توپر با متن سفید.',
                'en' => 'Subtle is a pale surface with dark text; bold is a solid fill with white text.',
            ]],
            ['name' => 'maxWidth', 'type' => 'length', 'default' => "'200px'", 'note' => [
                'fa' => 'برچسب بلندتر از ۲۰۰ پیکسل با سه‌نقطه فشرده می‌شود.',
                'en' => 'A label longer than 200px clamps to an ellipsis.',
            ]],
        ],
        'code' => <<<'BLADE'
        <span class="atlz" data-tone="inprogress">در جریان</span>
        <span class="atlz" data-tone="successful">انجام شد</span>
        <span class="atlz" data-tone="removed" data-bold>حذف شده</span>

        <style>
        .atlz { display: inline-block; padding: 2px 8px; border-radius: 3px;
                font: 700 11px/1.6 Inter, system-ui; letter-spacing: .2px; }
        .atlz[data-tone='inprogress'] { background: #E9F2FF; color: #0055CC; }
        .atlz[data-tone='successful'] { background: #DCFFF1; color: #1F845A; }
        .atlz[data-bold] { background: #0C66E4; color: #FFFFFF; }
        .atlz[data-tone='removed'][data-bold] { background: #B40000; }
        </style>
        BLADE,
    ],

    'section-message' => [
        'title' => ['fa' => 'پیام بخش', 'en' => 'Section Message'],
        'icon' => 'info',
        'oneLiner' => [
            'fa' => 'پیام درون‌محتوایی با آیکن، اکشن اختیاری و چهار شدت از اطلاع تا خطا — راه اطلسیان برای هشدار بدون قطع کردن جریان کار.',
            'en' => 'In-content message with icon, optional action and four severities from info to error — Atlassian' . "'" . 's way of warning without stopping the flow.',
        ],
        'js' => true,
        'docs' => 'https://atlassian.design/components/section-message/usage',
        'props' => [
            ['name' => 'appearance', 'type' => 'string', 'default' => "'information'", 'note' => [
                'fa' => 'information · warning · error · success · discovery؛ هرکدام آیکن و سطح خودش را دارد.',
                'en' => 'information · warning · error · success · discovery; each carries its own icon and tint.',
            ]],
            ['name' => 'title', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'سرخط پررنگ یک‌خطی بالای متن پشتیبان.',
                'en' => 'The bold one-line heading above the supporting text.',
            ]],
            ['name' => 'actions', 'type' => 'slot', 'default' => 'null', 'note' => [
                'fa' => 'لینک یا دکمهٔ بعد از متن؛ مثل «مشاهدهٔ مستندات».',
                'en' => 'The link or button after the text; like «Read the docs».',
            ]],
        ],
        'code' => <<<'BLADE'
        <section class="atsm" data-tone="warning">
            <span class="atsm-icon" aria-hidden="true">!</span>
            <div>
                <h4>نشانهٔ دسترسی به‌زودی منقضی می‌شود</h4>
                <p>نشانهٔ فعلی تا ۷ روز دیگر اعتبار دارد.</p>
                <a href="#">نشانهٔ تازه بسازید</a>
            </div>
        </section>

        <style>
        .atsm { display: flex; gap: 12px; padding: 16px; border-radius: 3px;
                background: #FFFAE6; color: #172B4D; }
        .atsm[data-tone='error'] { background: #FFEDEB; }
        .atsm-icon { flex: none; display: grid; place-items: center; aspect-ratio: 1; }
        .atsm h4 { margin: 0 0 4px; font: 600 14px Inter, system-ui; }
        .atsm p { margin: 0 0 8px; font: 400 12px/1.6 Inter, system-ui; }
        .atsm a { color: #0C66E4; font: 500 14px Inter, system-ui; }
        </style>
        BLADE,
    ],

    'flag' => [
        'title' => ['fa' => 'فلگ (توست اطلسیان)', 'en' => 'Flag'],
        'icon' => 'bell',
        'oneLiner' => [
            'fa' => 'توستِ گوشهٔ صفحه با آیکن مربعی رنگی، عنوان، توضیح و دکمهٔ بستن — الگوی بازخورد جیرا که یک نگاه شناسایش می‌کند.',
            'en' => 'Corner toast with a square colored icon, title, body and dismiss — the instantly recognizable Jira feedback pattern.',
        ],
        'js' => true,
        'docs' => 'https://atlassian.design/components/flag/usage',
        'props' => [
            ['name' => 'appearance', 'type' => 'string', 'default' => "'information'", 'note' => [
                'fa' => 'رنگ آیکن مربعی را تعیین می‌کند: آبی، سبز، زرد، قرمز.',
                'en' => 'Sets the square icon’s colour: blue, green, yellow, red.',
            ]],
            ['name' => 'title', 'type' => 'string', 'default' => "''", 'note' => [
                'fa' => 'سرخط نیم‌ضخیم؛ خلاصهٔ آنچه رخ داد.',
                'en' => 'The semibold headline; a summary of what happened.',
            ]],
            ['name' => 'actions', 'type' => 'slot', 'default' => 'null', 'note' => [
                'fa' => 'لینک اکشن مثل «واگرد»، زیر توضیح.',
                'en' => 'The action link, like «Undo», under the body.',
            ]],
            ['name' => 'autoDismiss', 'type' => 'seconds', 'default' => "'8'", 'note' => [
                'fa' => 'پس از ۸ ثانیه خودش می‌رود؛ خطاها منتظر می‌مانند.',
                'en' => 'Leaves by itself after 8s; errors wait.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="atfl" role="status">
            <span class="atfl-icon" data-tone="success" aria-hidden="true">✓</span>
            <div>
                <h4>تغییرات منتشر شد</h4>
                <p>نسخهٔ ۲٫۴ برای همهٔ همکاران قابل مشاهده است.</p>
                <button class="atfl-action">واگرد</button>
            </div>
            <button class="atfl-close" aria-label="بستن">×</button>
        </div>

        <style>
        .atfl { display: flex; gap: 12px; inline-size: 368px; max-inline-size: 100%;
                padding: 12px; border-radius: 4px; background: #FFFFFF;
                box-shadow: 0 8px 12px #091E4229, 0 0 1px #091E4240; }
        .atfl-icon { flex: none; display: grid; place-items: center; inline-size: 20px;
                     aspect-ratio: 1; border-radius: 2px; background: #1F845A; color: #fff; }
        .atfl h4 { margin: 0; font: 600 14px Inter, system-ui; color: #172B4D; }
        .atfl p { margin: 2px 0 4px; font: 400 12px/1.6 Inter, system-ui; color: #44546F; }
        .atfl-close { margin-inline-start: auto; align-self: flex-start; border: none;
                      background: none; color: #626F86; }
        </style>
        BLADE,
    ],

    'inline-message' => [
        'title' => ['fa' => 'پیام درون‌خطی', 'en' => 'Inline Message'],
        'icon' => 'alert-circle',
        'oneLiner' => [
            'fa' => 'آیکن رنگی کوچک و متن کوتاه که همان‌جا داخل فرم و محتوا می‌نشیند — راهنما و خطا را دقیقاً سر جای اتفاقش توضیح می‌دهد.',
            'en' => 'A small colored icon plus short text sitting right inside forms and content — help or error explained at the exact spot it happens.',
        ],
        'js' => true,
        'docs' => 'https://atlassian.design/components/inline-message/usage',
        'props' => [
            ['name' => 'appearance', 'type' => 'string', 'default' => "'info'", 'note' => [
                'fa' => 'info · confirmation · warning · error · discovery؛ فقط رنگ آیکن عوض می‌شود.',
                'en' => 'info · confirmation · warning · error · discovery; only the icon colour changes.',
            ]],
            ['name' => 'title', 'type' => 'string', 'default' => "''", 'note' => [
                'fa' => 'متن نیم‌ضخیم کنار آیکن؛ همیشه در یک خط.',
                'en' => 'The semibold text beside the icon; always one line.',
            ]],
            ['name' => 'secondaryText', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'متن کم‌رنگ‌تر زیر سرخط برای توضیح بیشتر.',
                'en' => 'The quieter text under the title for extra detail.',
            ]],
        ],
        'code' => <<<'BLADE'
        <span class="atim" data-tone="error">
            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16.5v.5"/></svg>
            <span><b>این نشانی قبلاً ثبت شده است</b><small>یک نشانی دیگر امتحان کنید.</small></span>
        </span>

        <style>
        .atim { display: inline-flex; gap: 8px; max-inline-size: 100%; }
        .atim svg { inline-size: 16px; flex: none; margin-block-start: 1px; stroke: #CA3521;
                    fill: none; stroke-width: 2; stroke-linecap: round; }
        .atim b { display: block; font: 600 12px/1.5 Inter, system-ui; color: #172B4D; }
        .atim small { display: block; font: 400 12px/1.5 Inter, system-ui; color: #626F86; }
        </style>
        BLADE,
    ],

    'spotlight' => [
        'title' => ['fa' => 'اسپات‌لایت آنبوردینگ', 'en' => 'Spotlight'],
        'icon' => 'wand',
        'oneLiner' => [
            'fa' => 'پردهٔ مودالی که همه‌جا را تاریک می‌کند جز هدف آموزش — تصویر، توضیح و دکمه‌های Skip و Next برای تور معرفی محصول.',
            'en' => 'A modal veil that dims everything but the teaching target — image, copy and Skip/Next actions for classic product onboarding tours.',
        ],
        'js' => true,
        'docs' => 'https://atlassian.design/components/onboarding/usage',
        'props' => [
            ['name' => 'target', 'type' => 'element', 'default' => 'null', 'note' => [
                'fa' => 'عنصری که روشن می‌ماند؛ بقیهٔ صفحه با ۷۷٪ سیاهی پوشیده می‌شود.',
                'en' => 'The element that stays lit; the rest is veiled at 77% black.',
            ]],
            ['name' => 'placement', 'type' => 'string', 'default' => "'bottom'", 'note' => [
                'fa' => 'جعبهٔ گفت‌وگو کنار هدف می‌نشیند: بالا، پایین، چپ، راست.',
                'en' => 'The dialog sits beside the target: top, bottom, left, right.',
            ]],
            ['name' => 'actions', 'type' => 'slot', 'default' => 'null', 'note' => [
                'fa' => 'ردیف Skip و Next؛ آخرین قدم Next می‌شود Finish.',
                'en' => 'The Skip and Next row; the last step’s Next becomes Finish.',
            ]],
            ['name' => 'pulse', 'type' => 'boolean', 'default' => 'true', 'note' => [
                'fa' => 'حلقهٔ تپنده دور هدف تا وقتی تور نرفته تمام.',
                'en' => 'A pulsing ring around the target until the tour is finished.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="atsp" x-data="{ step: 0 }">
            <button class="atsp-target" data-lit>ایجاد</button>
            <div class="atsp-dialog">
                <img src="…" alt="">
                <h4>از این‌جا مساله بسازید</h4>
                <p>دکمهٔ ایجاد، اولین خانهٔ هر کار تازه است.</p>
                <footer>
                    <button>رد کردن</button>
                    <button data-primary>بعدی</button>
                </footer>
            </div>
        </div>

        <style>
        .atsp { position: relative; overflow: clip; }
        .atsp-target[data-lit] { position: relative; z-index: 2; border-radius: 3px;
                                 box-shadow: 0 0 0 200vmax rgba(9, 30, 66, .77); }
        .atsp-dialog { position: absolute; z-index: 3; inline-size: 264px; padding: 16px;
                       border-radius: 4px; background: #FFFFFF; }
        .atsp-dialog h4 { margin: 0 0 4px; font: 600 14px Inter, system-ui; color: #172B4D; }
        .atsp-dialog footer { display: flex; justify-content: flex-end; gap: 8px; }
        </style>
        BLADE,
    ],

    'side-navigation' => [
        'title' => ['fa' => 'ناوبری کناری', 'en' => 'Side Navigation'],
        'icon' => 'menu',
        'oneLiner' => [
            'fa' => 'ستون کناری از آیتم‌های آیکن‌مربعی، بخش‌های جمع‌شونده و حالت انتخاب آبی — اسکلت هویتی همهٔ اپ‌های ابری اطلسیان.',
            'en' => 'A left column of square-icon items, collapsible sections and blue selected state — the identity skeleton of every Atlassian cloud app.',
        ],
        'js' => true,
        'docs' => 'https://atlassian.design/components/side-navigation/usage',
        'props' => [
            ['name' => 'items', 'type' => 'tree', 'default' => '[]', 'note' => [
                'fa' => 'درخت آیتم‌ها: سرگروه، لینک، بخش و آیتم تودرتو.',
                'en' => 'The item tree: heading, link, section and nested items.',
            ]],
            ['name' => 'isSelected', 'type' => 'boolean', 'default' => 'false', 'note' => [
                'fa' => 'آیتم انتخاب‌شده سطح آبی روشن و متن آبی می‌گیرد.',
                'en' => 'The selected item gains the blue-tinted surface and blue text.',
            ]],
            ['name' => 'badge', 'type' => 'slot', 'default' => 'null', 'note' => [
                'fa' => 'شمارندهٔ انتهای آیتم؛ مثل «۴ کار باز».',
                'en' => 'The counter at the item’s end; like «4 open tasks».',
            ]],
        ],
        'code' => <<<'BLADE'
        <nav class="atsn">
            <p class="atsn-heading">کارها</p>
            <button class="atsn-item" data-selected>
                <i aria-hidden="true">✦</i> پروژهٔ نابو <b>۴</b>
            </button>
            <button class="atsn-item" data-section aria-expanded="true">
                <i aria-hidden="true">▸</i> فیلترها
            </button>
            <button class="atsn-item" data-nested>مخصوص من</button>
        </nav>

        <style>
        .atsn { display: grid; gap: 2px; inline-size: 264px; padding: 8px;
                background: #FFFFFF; }
        .atsn-heading { margin: 8px 8px 4px; font: 700 11px/1.4 Inter, system-ui;
                        letter-spacing: .4px; text-transform: uppercase; color: #626F86; }
        .atsn-item { display: flex; align-items: center; gap: 8px; block-size: 36px;
                     padding-inline: 8px; border: none; border-radius: 4px;
                     background: none; font: 400 14px Inter, system-ui; color: #44546F; }
        .atsn-item i { display: grid; place-items: center; inline-size: 20px; aspect-ratio: 1;
                       border-radius: 4px; background: #E9F2FF; color: #0C66E4;
                       font-style: normal; font-size: 12px; }
        .atsn-item[data-selected] { background: #E9F2FF; color: #0C66E4; font-weight: 600; }
        .atsn-item b { margin-inline-start: auto; font-size: 11px; color: #626F86; }
        </style>
        BLADE,
    ],

    'empty-state' => [
        'title' => ['fa' => 'حالت خالی مصور', 'en' => 'Empty State'],
        'icon' => 'folder',
        'oneLiner' => [
            'fa' => 'تصویر خطی دوست‌داشتنی، تیتر، توضیح و فقط یک CTA — صفحهٔ خالی جیرا را به دعوت‌نامه‌ای برای شروع تبدیل می‌کند.',
            'en' => 'Friendly line illustration, heading, description and a single CTA — turning an empty Jira page into an invitation to start.',
        ],
        'js' => true,
        'docs' => 'https://atlassian.design/components/empty-state/usage',
        'props' => [
            ['name' => 'header', 'type' => 'string', 'default' => "''", 'note' => [
                'fa' => 'تیتر Charlie با وزن ۵۰۰ و اندازهٔ ۲۴ پیکسل.',
                'en' => 'The Charlie heading at weight 500 and 24px.',
            ]],
            ['name' => 'description', 'type' => 'string', 'default' => "''", 'note' => [
                'fa' => 'یک جمله؛ بیش از دو خط نشود.',
                'en' => 'One sentence; never more than two lines.',
            ]],
            ['name' => 'primaryAction', 'type' => 'slot', 'default' => 'null', 'note' => [
                'fa' => 'فقط یک CTA آبی؛ اکشن دوم را به لینک متنی بسپارید.',
                'en' => 'A single blue CTA; relegate any second action to a text link.',
            ]],
            ['name' => 'imageUrl', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'تصویر خطی با خطوط ۱٫۵ پیکسلی و گوشه‌های نرم.',
                'en' => 'The line illustration with 1.5px strokes and soft corners.',
            ]],
        ],
        'code' => <<<'BLADE'
        <section class="ates">
            <svg viewBox="0 0 160 120" aria-hidden="true">
                <rect x="28" y="18" width="104" height="84" rx="8" fill="#FFFFFF" stroke="#8590A2" stroke-width="1.5"/>
                <path d="M48 44h64M48 60h44" stroke="#DFE1E6" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
            <h3>اینجا هنوز چیزی نیست</h3>
            <p>اولین مسالهٔ پروژه را بسازید تا تخته زنده شود.</p>
            <button data-primary>ایجاد مساله</button>
        </section>

        <style>
        .ates { display: grid; justify-items: center; gap: 8px; padding: 40px 24px;
                text-align: center; }
        .ates svg { inline-size: 160px; }
        .ates h3 { margin: 0; font: 500 20px/1.3 "Charlie Display", Inter, system-ui; color: #172B4D; }
        .ates p { margin: 0; max-inline-size: 42ch; font: 400 14px/1.6 Inter, system-ui; color: #626F86; }
        .ates [data-primary] { margin-block-start: 8px; block-size: 32px; padding-inline: 12px;
                               border: none; border-radius: 3px; background: #0C66E4; color: #fff; }
        </style>
        BLADE,
    ],

    'comment' => [
        'title' => ['fa' => 'رشتهٔ دیدگاه', 'en' => 'Comment Thread'],
        'icon' => 'message',
        'oneLiner' => [
            'fa' => 'آواتار گرد، نویسنده و زمان، بدنهٔ قالب‌دار و ردیف اکشن‌های کم‌سروصدا — قلب گفت‌وگوی هر مسالهٔ جیرا و صفحهٔ کانفلوئنس.',
            'en' => 'Round avatar, author and timestamp, rich body and a quiet action row — the heartbeat of every Jira issue and Confluence page conversation.',
        ],
        'js' => true,
        'docs' => 'https://atlassian.design/components/comment/usage',
        'props' => [
            ['name' => 'avatar', 'type' => 'element', 'default' => 'null', 'note' => [
                'fa' => 'آواتار ۲۴ تا ۳۲ پیکسلی؛ حروف نخستین نام هم کافی است.',
                'en' => 'A 24–32px avatar; name initials work just as well.',
            ]],
            ['name' => 'author · time', 'type' => 'slots', 'default' => 'null', 'note' => [
                'fa' => 'نام پررنگ با لینک آبی و زمان کم‌رنگ کنارش.',
                'en' => 'The bold linked name in blue with the muted time beside it.',
            ]],
            ['name' => 'actions', 'type' => 'slot', 'default' => 'null', 'note' => [
                'fa' => 'اکشن‌های متنی کم‌رنگ زیر بدنه؛ مثل واکنش و پاسخ.',
                'en' => 'The quiet text actions under the body; like react and reply.',
            ]],
        ],
        'code' => <<<'BLADE'
        <article class="atcm">
            <span class="atcm-avatar" aria-hidden="true">م‌ر</span>
            <div>
                <header><a href="#">مریم رضایی</a><time>۲ ساعت پیش</time></header>
                <div class="atcm-body">
                    <p>برچسب «در جریان» را گذاشتم؛ <a href="#">@علی</a> بعد از بازبینی جابه‌جایش کن.</p>
                </div>
                <footer>
                    <button>پاسخ</button><button>واکنش</button>
                </footer>
            </div>
        </article>

        <style>
        .atcm { display: flex; gap: 12px; padding: 12px 0; }
        .atcm-avatar { flex: none; display: grid; place-items: center; inline-size: 32px;
                       aspect-ratio: 1; border-radius: 50%; background: #F1F2F4;
                       font: 600 12px Inter, system-ui; color: #44546F; }
        .atcm header { display: flex; align-items: baseline; gap: 8px; }
        .atcm header a { font: 600 14px Inter, system-ui; color: #0C66E4; }
        .atcm time { font: 400 12px Inter, system-ui; color: #626F86; }
        .atcm-body p { margin: 4px 0; font: 400 14px/1.6 Inter, system-ui; color: #172B4D; }
        .atcm footer { display: flex; gap: 4px; }
        .atcm footer button { border: none; background: none; padding: 4px 8px;
                              border-radius: 3px; font: 600 12px Inter, system-ui; color: #626F86; }
        </style>
        BLADE,
    ],
];
