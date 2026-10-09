<?php

/**
 * Demo manifest of the "polaris" group — Shopify Polaris, the admin design
 * system, rebuilt as faithful, interactive skins: the merchant-data Index
 * Table with its bulk-actions bar, the card-row Resource List, the chip-based
 * Filters bar, status Banners, Setting Toggle cards, the Page Header frame,
 * the Contextual Save Bar, the two-column Annotated Layout and the Color
 * Picker. Scenarios live at
 * resources/views/demos/components/polaris/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'شاپیفای — پولاریس', 'en' => 'Shopify Polaris'],

    'index-table' => [
        'title' => ['fa' => 'جدول شاخص با انتخاب گروهی', 'en' => 'Index Table'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'جدول داده‌های ادمین شاپیفای: چک‌باکس هر ردیف، نوار عملیات گروهی که با اولین انتخاب بالا می‌آید و صفحه‌بندی چسبان پایین؛ مدیریت انبوه سفارش‌ها و محصولات همین‌جا شکل گرفت.',
            'en' => 'Shopify admin\'s merchant-data table: per-row checkboxes, a bulk-actions bar that appears on first selection, and pinned pagination — the birthplace of at-scale order and product management.',
        ],
        'js' => true,
        'docs' => 'https://polaris.shopify.com/components/index-table',
        'props' => [
            ['name' => 'selection', 'type' => 'state', 'default' => "'[]'", 'note' => [
                'fa' => 'آرایهٔ ردیف‌های انتخاب‌شده؛ با اولین عضو، نوار عملیات گروهی از پایین بالا می‌آید.',
                'en' => 'The selected-rows array; its first member raises the bulk-actions bar.',
            ]],
            ['name' => '--plit-green', 'type' => 'color', 'default' => "'#008060'", 'note' => [
                'fa' => 'سبز برند شاپیفای برای اکشن اصلی و نوار انتخاب گروهی؛ در تم تیره به #00A97F می‌رسد.',
                'en' => 'Shopify\'s brand green for the primary action and bulk bar; on dark it becomes #00A97F.',
            ]],
            ['name' => 'pagination', 'type' => 'behaviour', 'default' => "'pinned'", 'note' => [
                'fa' => 'صفحه‌بندی پایین جدول می‌نشیند و با اسکرول محتوا جابه‌جا نمی‌شود.',
                'en' => 'Pagination pins to the table footer and never scrolls away with the rows.',
            ]],
            ['name' => 'status', 'type' => 'badge', 'default' => "'paid · pending'", 'note' => [
                'fa' => 'نشان وضعیت هر ردیف با زمینهٔ ملایم همان تُن: پرداخت‌شده سبز، در انتظار کهربایی، مرجوع سرخ.',
                'en' => 'Per-row status badges on subdued tints of their own tone: green, amber, red.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="plit" x-data="{ sel: [] }">
            <label><input type="checkbox" x-on:click="toggleAll()"> تمام</label>
            <table>
                <tr><td><input type="checkbox" x-on:click="sel.push(1001)"></td><td>#۱۰۰۱</td>…</tr>
            </table>
            <div class="plit-bulk" x-show="sel.length">
                <span x-text="sel.length + ' انتخاب شد'"></span>
                <button>بسته‌بندی</button>
            </div>
        </div>

        <style>
        .plit { border-radius: 12px; background: #FFF; box-shadow: 0 1px 4px #00000012; }
        .plit-bulk { position: absolute; inset-inline: 12px; inset-block-end: 52px;
                     display: flex; gap: 8px; padding: 10px 14px; border-radius: 8px;
                     background: #1A3B34; color: #F1F7F4; animation: plit-rise .2s ease-out; }
        @keyframes plit-rise { from { translate: 0 100%; opacity: 0; } }
        </style>
        BLADE,
    ],

    'resource-list' => [
        'title' => ['fa' => 'فهرست منابع کارتی', 'en' => 'Resource List'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'هر منبع یک ردیف کارتی با تامبنیل، عنوان، متادیتا و منوی اکشن؛ لیست‌کردن محصول و مشتری به زبان مادری ادمین شاپیفای.',
            'en' => 'Every resource is a card row with thumbnail, title, attributes and an actions menu — listing products and customers in the native tongue of Shopify admin.',
        ],
        'js' => true,
        'docs' => 'https://polaris.shopify.com/components/resource-list',
        'props' => [
            ['name' => 'media', 'type' => 'thumbnail', 'default' => "'48px'", 'note' => [
                'fa' => 'تامبنیل گرد ۴۸ پیکسل در آغاز هر ردیف؛ حذفش فهرست را فشرده می‌کند.',
                'en' => 'A rounded 48px thumbnail opening each row; dropping it densifies the list.',
            ]],
            ['name' => 'actions', 'type' => 'menu', 'default' => "'kebab'", 'note' => [
                'fa' => 'منوی سه‌نقطه در انتهای ردیف: ویرایش، تکثیر، حذف — بیرونش با لمس بیرون بسته می‌شود.',
                'en' => 'The row-end kebab menu: edit, duplicate, delete — outside taps close it.',
            ]],
            ['name' => 'badge', 'type' => 'tone', 'default' => "'success'", 'note' => [
                'fa' => 'نشان وضعیت منبع: فعال با تُن سبز، پیش‌نویس خاکستری.',
                'en' => 'The resource\'s status badge: green for active, gray for draft.',
            ]],
        ],
        'code' => <<<'BLADE'
        <ul class="plrl" x-data="{ open: null }">
            <li>
                <img class="plrl-media" src="…" alt="">
                <div><b>شمع سویا مینیمال</b><small>SKU: CS-220 · ۲۴۰٬۰۰۰ تومان</small></div>
                <span class="plrl-badge">فعال</span>
                <button class="plrl-kebab" x-on:click="open = open ? null : 1">⋯</button>
                <div class="plrl-menu" x-show="open === 1" x-on:click.outside="open = null">
                    <button>تکثیر</button><button>حذف</button>
                </div>
            </li>
        </ul>

        <style>
        .plrl { border-radius: 12px; background: #FFF; box-shadow: 0 1px 4px #00000012; }
        .plrl > li { display: flex; align-items: center; gap: 12px; padding: 12px 16px;
                     border-block-start: 1px solid #E3E3E3; }
        .plrl-media { inline-size: 48px; block-size: 48px; border-radius: 8px; }
        .plrl-kebab + .plrl-menu { position: absolute; inset-inline-end: 16px; border-radius: 8px;
                                   background: #FFF; box-shadow: 0 4px 16px #00000022; }
        </style>
        BLADE,
    ],

    'filters' => [
        'title' => ['fa' => 'نوار فیلترها با چیپ', 'en' => 'Filters Bar'],
        'icon' => 'sliders',
        'oneLiner' => [
            'fa' => 'فیلترها بالای لیست به چیپ‌های قابل‌حذف تبدیل می‌شوند و «Clear all filters» همه را یک‌جا می‌زداید؛ همان نوار فیلتر گرید شاپیفای با سرچ، سیوویو و سورت یکپارچه.',
            'en' => 'Applied filters become removable chips above the list with a one-click \'Clear all filters\' — Shopify\'s grid toolbar with search, saved views and sorting in one bar.',
        ],
        'js' => true,
        'docs' => 'https://polaris.shopify.com/components/filters',
        'props' => [
            ['name' => 'query', 'type' => 'search', 'default' => "''", 'note' => [
                'fa' => 'جست‌وجوی زندهٔ نوار؛ با تایپ، فهرست همان لحظه فیلتر می‌شود.',
                'en' => 'The bar\'s live search; the list filters as you type.',
            ]],
            ['name' => 'chip', 'type' => 'removable', 'default' => "'×'", 'note' => [
                'fa' => 'هر فیلتر اعمال‌شده یک چیپ با ضربدر است؛ حذفش فیلتر را همان لحظه برمی‌دارد.',
                'en' => 'Every applied filter is a chip with an ×; removing it lifts the filter at once.',
            ]],
            ['name' => 'clear-all', 'type' => 'action', 'default' => "'one-click'", 'note' => [
                'fa' => '«پاک‌کردن همهٔ فیلترها» فقط وقتی چیپی هست ظاهر می‌شود و همه را یک‌جا می‌زداید.',
                'en' => '\'Clear all filters\' only appears while chips exist and sweeps them in one click.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="plfl" x-data="{ q: '', chips: [] }">
            <div class="plfl-bar">
                <input x-model="q" placeholder="جست‌وجوی سفارش">
                <button x-on:click="panel = true">فیلتر</button>
            </div>
            <div class="plfl-chips">
                <template x-for="c in chips"><span class="plfl-chip" x-text="c" x-on:click="drop(c)">×</span></template>
                <button x-show="chips.length" x-on:click="chips = []">پاک‌کردن همهٔ فیلترها</button>
            </div>
        </div>

        <style>
        .plfl-bar { display: flex; gap: 8px; }
        .plfl-bar input { block-size: 36px; padding-inline: 12px; border: 1px solid #8A8A8A;
                          border-radius: 8px; background: #FFF; }
        .plfl-chip { display: inline-flex; align-items: center; gap: 6px; block-size: 28px;
                     padding-inline: 10px; border-radius: 8px; border: 1px solid #E3E3E3;
                     background: #FFF; }
        </style>
        BLADE,
    ],

    'banner' => [
        'title' => ['fa' => 'بنر وضعیت', 'en' => 'Banner'],
        'icon' => 'info',
        'oneLiner' => [
            'fa' => 'پیام‌های اطلاع‌رسانی، موفق و بحرانی با رنگ برند سبز شاپیفای، آیکون وضعیت، عنوان و امکان بستن؛ زبان رسمی شاپیفای برای خبر خوب و بد.',
            'en' => 'Informational, success and critical messages in Shopify green with a status icon, heading and dismiss — the official Polaris voice for good news and bad.',
        ],
        'js' => true,
        'docs' => 'https://polaris.shopify.com/components/banner',
        'props' => [
            ['name' => 'tone', 'type' => 'string', 'default' => "'info'", 'note' => [
                'fa' => 'info · success · warning · critical؛ هر تُن آیکن و زمینهٔ ملایم خودش را دارد.',
                'en' => 'info · success · warning · critical; each tone brings its icon and subdued tint.',
            ]],
            ['name' => 'dismiss', 'type' => 'button', 'default' => "'×'", 'note' => [
                'fa' => 'ضربدر بستن در انتهای بنر؛ بنر بسته‌شده با «بازگردانی» برمی‌گردد.',
                'en' => 'The banner-end close ×; a restore link brings dismissed banners back.',
            ]],
            ['name' => 'action', 'type' => 'link', 'default' => 'null', 'note' => [
                'fa' => 'یک کنش متنی زیر بدنه، مثل «اتصال مجدد درگاه».',
                'en' => 'One text action under the body, like «reconnect gateway».',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="plbn" data-tone="success" x-data>
            <svg class="plbn-icon">✓</svg>
            <div>
                <b>قالب ذخیره شد</b>
                <p>تغییرات از ۹:۴۱ روی فروشگاه اعمال است.</p>
            </div>
            <button class="plbn-x" aria-label="بستن">×</button>
        </div>

        <style>
        .plbn { display: flex; gap: 12px; padding: 14px 16px; border-radius: 12px;
                border: 1px solid #E3E3E3; background: #FFF; }
        .plbn[data-tone='success'] { color: #008060; background: #E3F1ED;
                                     border-color: color-mix(in srgb, #008060 20%, #FFF); }
        .plbn-x { margin-inline-start: auto; border: none; background: none; cursor: pointer; }
        </style>
        BLADE,
    ],

    'setting-toggle' => [
        'title' => ['fa' => 'کارت کلید تنظیمات', 'en' => 'Setting Toggle'],
        'icon' => 'settings',
        'oneLiner' => [
            'fa' => 'هر تنظیم یک کارت کامل است: توضیح وضعیت فعلی، متن راهنما و یک دکمه/کلید روشن‌وخاموش که متن کارت را هم عوض می‌کند؛ فرم تنظیمات شاپیفای همین‌طور نفس می‌کشد.',
            'en' => 'Every setting is a whole card: a description of the current state, helper text, and one toggle that rewrites the card copy as it flips — how Shopify settings pages breathe.',
        ],
        'js' => true,
        'docs' => 'https://polaris.shopify.com/components/setting-toggle',
        'props' => [
            ['name' => 'enabled', 'type' => 'boolean', 'default' => "'false'", 'note' => [
                'fa' => 'وضعیت تنظیم؛ برخورداندازی، توضیح و متن راهنمای کارت را هم بازنویسی می‌کند.',
                'en' => 'The setting\'s state; flipping it rewrites the card\'s copy as well as the toggle.',
            ]],
            ['name' => 'control', 'type' => 'switch|link', 'default' => "'switch'", 'note' => [
                'fa' => 'سوییچ نوین پولاریس یا دکمهٔ متنی کلاسیک «روشن/خاموش» در انتهای کارت.',
                'en' => 'Polaris\'s modern switch or the classic text button «on/off» at the card end.',
            ]],
            ['name' => 'disabled', 'type' => 'state', 'default' => "'false'", 'note' => [
                'fa' => 'کارت قفل‌شده با کلید کم‌رنگ و یادداشت «در دسترس نیست».',
                'en' => 'A locked card with a faded control and an "unavailable" note.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="plst" x-data="{ on: false }">
            <div>
                <b>پیگیری سفارش با پیامک</b>
                <p x-text="on ? 'فعال — مشتریان پیامک وضعیت می‌گیرند.' : 'غیرفعال'"></p>
            </div>
            <button class="plst-switch" x-on:click="on = !on"
                    x-bind:data-on="on" role="switch" x-bind:aria-checked="on"><i></i></button>
        </div>

        <style>
        .plst { display: flex; align-items: center; gap: 16px; padding: 16px;
                border-radius: 12px; background: #FFF; box-shadow: 0 1px 4px #00000012; }
        .plst-switch { inline-size: 40px; block-size: 24px; border-radius: 999px;
                       background: #8A8A8A; border: none; cursor: pointer; }
        .plst-switch[data-on='true'] { background: #008060; }
        .plst-switch i { display: block; inline-size: 20px; aspect-ratio: 1; border-radius: 50%;
                         background: #FFF; translate: 0 0; transition: translate .15s; }
        .plst-switch[data-on='true'] i { translate: 16px 0; }
        </style>
        BLADE,
    ],

    'page-header' => [
        'title' => ['fa' => 'سربرگ صفحهٔ ادمین', 'en' => 'Page Header'],
        'icon' => 'home',
        'oneLiner' => [
            'fa' => 'تایتل درشت با اکشن اولیه سبز، اکشن‌های ثانویه و breadcrumb بالای سر؛ هر صفحه در ادمین شاپیفای با همین قاب آغاز می‌شود.',
            'en' => 'An oversized title with a green primary action, secondary actions and breadcrumbs up top — the standard opening frame of every Shopify admin page.',
        ],
        'js' => true,
        'docs' => 'https://polaris.shopify.com/components/page-header',
        'props' => [
            ['name' => 'breadcrumbs', 'type' => 'trail', 'default' => "'[]'", 'note' => [
                'fa' => 'ردپای بالای عنوان؛ نسخهٔ عمق‌دار با فلش بازگشت جایگزین می‌شود.',
                'en' => 'The trail above the title; a back-arrow variant replaces it on deep pages.',
            ]],
            ['name' => 'primary-action', 'type' => 'button', 'default' => "'green'", 'note' => [
                'fa' => 'اکشن اولیه با سبز برند #008060 در انتهای ردیف عنوان.',
                'en' => 'The primary action in brand green #008060 at the title row\'s end.',
            ]],
            ['name' => 'pagination', 'type' => 'inline', 'default' => 'null', 'note' => [
                'fa' => 'صفحه‌بندی درون‌سربرگی: «۳۲ از ۱۲۸» با دو دکمهٔ فلش، برای رفت‌وآمد میان رکوردها.',
                'en' => 'In-header pagination: «32 of 128» with two arrow buttons for stepping records.',
            ]],
        ],
        'code' => <<<'BLADE'
        <header class="plph">
            <nav class="plph-crumbs"><a>خانه</a><a>فروشگاه</a><span>سفارش‌ها</span></nav>
            <div class="plph-row">
                <h1>سفارش‌ها</h1>
                <div class="plph-actions">
                    <button data-secondary>صادرات</button>
                    <button data-primary>ایجاد سفارش</button>
                </div>
            </div>
        </header>

        <style>
        .plph { display: grid; gap: 8px; padding: 16px; }
        .plph h1 { margin: 0; font: 600 24px/1.2 Inter, system-ui; color: #303030; }
        .plph-actions { display: flex; gap: 8px; margin-inline-start: auto; }
        .plph [data-primary] { block-size: 36px; padding-inline: 16px; border: none;
                               border-radius: 8px; background: #008060; color: #FFF; }
        .plph [data-secondary] { background: #FFF; border: 1px solid #8A8A8A; border-radius: 8px; }
        </style>
        BLADE,
    ],

    'contextual-save-bar' => [
        'title' => ['fa' => 'نوار ذخیرهٔ زمینه‌ای', 'en' => 'Contextual Save Bar'],
        'icon' => 'check-circle',
        'oneLiner' => [
            'fa' => 'با اولین ویرایش فرم، نوار تیره‌ای با Discard و Save از بالای صفحه بالا می‌آید و وضعیت ذخیره‌نشده را لو می‌دهد؛ معروف‌ترین امضای فرم‌های شاپیفای.',
            'en' => 'The moment a form is edited, a dark bar slides in from the top with Discard and Save plus dirty-state status — Shopify\'s most famous form mechanic.',
        ],
        'js' => true,
        'docs' => 'https://polaris.shopify.com/components/contextual-save-bar',
        'props' => [
            ['name' => 'dirty', 'type' => 'state', 'default' => "'false'", 'note' => [
                'fa' => 'اولین کلید تایپ، فرم را «کثیف» می‌کند و نوار تیره را از لبهٔ بالا می‌کشد بیرون.',
                'en' => 'The first keystroke makes the form dirty and drags the dark bar out of the top edge.',
            ]],
            ['name' => 'save', 'type' => 'action', 'default' => "'primary'", 'note' => [
                'fa' => 'ذخیره با سه حالت: آماده، «در حال ذخیره…» با اسپینر، و تیک سبز موفقیت.',
                'en' => 'Save has three states: ready, «saving…» with a spinner, and a green success tick.',
            ]],
            ['name' => 'discard', 'type' => 'action', 'default' => "'plain'", 'note' => [
                'fa' => 'دور انداختن، فیلدها را به مقدار نخستین برمی‌گرداند و نوار را جمع می‌کند.',
                'en' => 'Discard reverts the fields to their originals and collapses the bar.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="plsb" x-data="{ dirty: false, saving: false }">
            <div class="plsb-bar" x-show="dirty" x-transition>
                <span x-text="saving ? 'در حال ذخیره…' : 'تغییرات ذخیره‌نشده'"></span>
                <button x-on:click="discard()">دور انداختن</button>
                <button data-primary x-on:click="save()">ذخیره</button>
            </div>
            <input x-on:input="dirty = true">
        </div>

        <style>
        .plsb { position: relative; }
        .plsb-bar { position: absolute; inset-inline: 0; inset-block-start: 0; z-index: 5;
                    display: flex; align-items: center; gap: 12px; padding: 10px 16px;
                    background: #1A1A1A; color: #F1F1F1; border-radius: 12px 12px 0 0; }
        .plsb-bar [data-primary] { background: #008060; color: #FFF; border-radius: 8px; }
        </style>
        BLADE,
    ],

    'annotated-layout' => [
        'title' => ['fa' => 'چیدمان دوسطری تنظیمات', 'en' => 'Annotated Layout'],
        'icon' => 'file',
        'oneLiner' => [
            'fa' => 'صفحات تنظیمات دوستونه: یک ستون برای عنوان، توضیح و لینک راهنما، ستون دیگر کارت‌های بخش‌بندی‌شدهٔ فیلدها؛ الگوی رسمی ستینگ‌پیج‌های شاپیفای.',
            'en' => 'Two-column settings pages: one column for the title, explanation and help link, the other for sectioned field cards — the official Shopify settings-page pattern.',
        ],
        'js' => true,
        'docs' => 'https://polaris.shopify.com/components/annotated-layout',
        'props' => [
            ['name' => 'annotation', 'type' => 'column', 'default' => "'1fr · 2fr'", 'note' => [
                'fa' => 'نسبت ستون‌ها یک به دو؛ زیر ۷۶۸px روی هم می‌افتند و توضیح بالا می‌ماند.',
                'en' => 'The columns split 1:2; under 768px they stack with the annotation on top.',
            ]],
            ['name' => 'sectioned', 'type' => 'card', 'default' => "'true'", 'note' => [
                'fa' => 'کارت بخش‌بندی‌شده: هر بخش فیلدها جدا با خط زیر خودش.',
                'en' => 'A sectioned card: each field group separated by its own rule.',
            ]],
            ['name' => 'help-link', 'type' => 'link', 'default' => 'null', 'note' => [
                'fa' => 'لینک «بیشتر بدانید» با آیکن بیرونی، پایان ستون توضیح.',
                'en' => 'A «learn more» link with an external icon closing the annotation column.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="plal">
            <div class="plal-note">
                <h2>اطلاع‌رسانی‌ها</h2>
                <p>تصمیم بدهید مشتری پس از هر سفارش چه خبری بگیرد.</p>
                <a>بیشتر بدانید ↗</a>
            </div>
            <div class="plal-card">
                <label><input type="checkbox"> ایمیل تأیید سفارش</label>
                <hr>
                <label>فرستنده <select>…</select></label>
            </div>
        </div>

        <style>
        .plal { display: grid; grid-template-columns: 1fr 2fr; gap: 24px; }
        .plal-note h2 { margin: 0; font: 600 16px/1.3 Inter, system-ui; color: #303030; }
        .plal-card { border-radius: 12px; background: #FFF;
                     box-shadow: 0 1px 4px #00000012; padding: 16px; }
        @media (max-width: 768px) { .plal { grid-template-columns: 1fr; } }
        </style>
        BLADE,
    ],

    'color-picker' => [
        'title' => ['fa' => 'انتخابگر رنگ شاپیفای', 'en' => 'Color Picker'],
        'icon' => 'wand',
        'oneLiner' => [
            'fa' => 'مستطیل اشباع، اسلایدر Hue و نوار آلفا در یک پنل کامپکت با هگز قابل‌ویرایش؛ انتخاب رنگ واریانت محصول دقیقاً به سبک خود شاپیفای.',
            'en' => 'A saturation rectangle, hue slider and alpha bar in one compact panel with an editable hex field — picking product variant colors the Shopify-native way.',
        ],
        'js' => true,
        'docs' => 'https://polaris.shopify.com/components/color-picker',
        'props' => [
            ['name' => 'color', 'type' => 'hsv', 'default' => "'{h:120,s:60,v:80}'", 'note' => [
                'fa' => 'مدل رنگ پولاریس HSV است؛ مستطیل اشباع، s و v را می‌کشد و اسلایدر h را.',
                'en' => 'Polaris models colour as HSV; the rectangle drags s and v, the slider h.',
            ]],
            ['name' => 'alpha', 'type' => 'slider', 'default' => "'0–1'", 'note' => [
                'fa' => 'نوار آلفا روی شطرنجی شفافیت می‌رود؛ حذفش پنل را فشرده‌تر می‌کند.',
                'en' => 'The alpha bar slides over a transparency checkerboard; dropping it densifies the panel.',
            ]],
            ['name' => 'hex', 'type' => 'input', 'default' => "'#008060'", 'note' => [
                'fa' => 'فیلد هگز قابل‌ویرایش؛ تایپ یا چسپاندن، همان لحظه همهٔ کنترل‌ها را جابه‌جا می‌کند.',
                'en' => 'The editable hex field; typing or pasting moves every control at once.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="plcp" x-data="{ h: 160, s: 100, v: 50, a: 1 }">
            <div class="plcp-area" x-on:pointerdown="pick($event)">
                <span class="plcp-thumb" x-bind:style="`left:${s}%;top:${100 - v}%`"></span>
            </div>
            <input type="range" min="0" max="360" x-model.number="h">
            <input class="plcp-hex" x-model="hex">
        </div>

        <style>
        .plcp { display: grid; gap: 12px; padding: 12px; border-radius: 12px;
                background: #FFF; box-shadow: 0 1px 4px #00000012; }
        .plcp-area { aspect-ratio: 16/9; border-radius: 8px; touch-action: none;
                     background: linear-gradient(to top, #000, transparent),
                                 linear-gradient(to right, #FFF, hsl(var(--hue) 100% 50%)); }
        .plcp-thumb { position: absolute; inline-size: 18px; aspect-ratio: 1;
                      border-radius: 50%; border: 2px solid #FFF; translate: -50% -50%; }
        </style>
        BLADE,
    ],
];
