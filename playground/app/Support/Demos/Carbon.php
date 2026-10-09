<?php

/**
 * Demo manifest of the "carbon" group — IBM Carbon Design System rebuilt as
 * faithful, interactive skins: the structured list with radio selection,
 * clickable and selectable tiles, the expandable tile, the combo button, the
 * overflow menu, the AI label (slug), the contained list, the tearsheet with
 * its side-rail influencer and the data spreadsheet. Scenarios live at
 * resources/views/demos/components/carbon/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'آی‌بی‌ام — کربن', 'en' => 'IBM Carbon'],

    'structured-list' => [
        'title' => ['fa' => 'فهرست ساخت‌یافته', 'en' => 'Structured List'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'ردیف‌های کل-مقدار با حاشیه‌های تختِ ۱پیکسلی و انتخابِ تک‌گزینه‌ای؛ جدول سبکی که هر دادهٔ تشریحی را بی‌پرده سبک کربنی می‌کند.',
            'en' => 'Key-value rows with flat 1px borders and radio-style selection — Carbon’s featherweight alternative to a full data table for descriptive data.',
        ],
        'js' => true,
        'docs' => 'https://react.carbondesignsystem.com/?path=/docs/components-structuredlist--overview',
        'props' => [
            ['name' => 'selection', 'type' => 'boolean', 'default' => 'false', 'note' => [
                'fa' => 'یک رادیو به آغاز هر ردیف می‌نشاند و کل ردیف را کلیک‌پذیر می‌کند؛ انتخابِ ردیف با وارون‌شدن سطح به layer selected رخ می‌دهد.',
                'en' => 'Puts a radio at the start of each row and makes the whole row clickable; selection inverts the row to the selected layer.',
            ]],
            ['name' => 'flush', 'type' => 'boolean', 'default' => 'false', 'note' => [
                'fa' => 'حالت flush پدینگ سلول‌ها را می‌برد تا فهرست به لبهٔ قاب بچسبد — برای نشاندن داخل پنل‌های پُر.',
                'en' => 'Flush mode removes the cell padding so the list hugs its frame — for sitting inside dense panels.',
            ]],
            ['name' => 'size', 'type' => 'string', 'default' => "'xl'", 'note' => [
                'fa' => 'md یا xl؛ xl ردیف را با همان تراز دوستونیِ کلید-مقدار جادارتر می‌کند.',
                'en' => 'md or xl; xl gives the two-column key-value alignment more room to breathe.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="cbsl" role="table" aria-label="محیط استقرار">
            <div class="cbsl-row" role="row" data-head>
                <span role="columnheader">محیط</span>
                <span role="columnheader">ناحیه</span>
            </div>
            <label class="cbsl-row" role="row">
                <input type="radio" name="env">
                <span role="cell">تولید</span>
                <span role="cell">فرانکفورت</span>
            </label>
        </div>

        <style>
        .cbsl { border-block-end: 1px solid #e0e0e0; font: 400 .875rem/1.4 'IBM Plex Sans', sans-serif; }
        .cbsl-row { display: grid; grid-template-columns: 12rem 1fr; gap: 1rem;
                    padding: .75rem 1rem; border-block-start: 1px solid #e0e0e0; }
        .cbsl-row[data-head] { font-size: .75rem; color: #525252; }
        .cbsl-row:has(input:checked) { background: #e0e0e0; }
        </style>
        BLADE,
    ],

    'selectable-tile' => [
        'title' => ['fa' => 'کاشی انتخابی', 'en' => 'Selectable Tile'],
        'icon' => 'check',
        'oneLiner' => [
            'fa' => 'کاشی تختِ تک‌کلیکی که کل سطحش همان چک‌باکس است؛ با تیکِ گوشه و وارون‌شدن حاشیه به آبی کربن، در حالت تک‌انتخابی یا گروهی.',
            'en' => 'The click-to-select tile: the whole surface is the checkbox — a corner checkmark and carbon-blue border inversion on select, in single- or multi-select groups.',
        ],
        'js' => true,
        'docs' => 'https://react.carbondesignsystem.com/?path=/docs/components-tile--overview',
        'props' => [
            ['name' => 'selected', 'type' => 'boolean', 'default' => 'false', 'note' => [
                'fa' => 'وضعیت انتخاب؛ حاشیهٔ کاشی به #0f62fe می‌رود و تیکِ گوشهٔ بالا-پایان ظاهر می‌شود.',
                'en' => 'The selected state; the tile border flips to #0f62fe and the end-top corner checkmark appears.',
            ]],
            ['name' => 'name', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'نام گروه رادیو؛ کاشی‌های هم‌نام تک‌انتخابی می‌شوند و بی‌نام‌ها چک‌باکس گروهی.',
                'en' => 'The radio group name; same-named tiles become single-select, unnamed ones multi-select.',
            ]],
            ['name' => 'handleTitle', 'type' => 'string', 'default' => "'label'", 'note' => [
                'fa' => 'برچسب دسترس‌پذیریِ ورودیِ پنهان؛ صفحه‌خوان کل کاشی را یک کنترل می‌خواند.',
                'en' => 'The accessible title of the hidden input; screen readers announce the whole tile as one control.',
            ]],
        ],
        'code' => <<<'BLADE'
        <label class="cbtl" data-selected="false">
            <input type="radio" name="plan">
            <span class="cbtl-check">✓</span>
            <b>پلن رشد</b>
            <small>۱۲ هسته · پشتیبان‌گیری روزانه</small>
        </label>

        <style>
        .cbtl { position: relative; display: grid; gap: .25rem; padding: 1rem;
                border: 1px solid #e0e0e0; background: #fff; cursor: pointer;
                font-family: 'IBM Plex Sans', sans-serif; }
        .cbtl:has(input:checked) { border: 1px solid #0f62fe; background: #edf5ff; }
        .cbtl-check { position: absolute; inset-block-start: 0; inset-inline-end: 0;
                      visibility: hidden; padding: .25rem; background: #0f62fe; color: #fff; }
        .cbtl:has(input:checked) .cbtl-check { visibility: visible; }
        </style>
        BLADE,
    ],

    'expandable-tile' => [
        'title' => ['fa' => 'کاشی بازشو', 'en' => 'Expandable Tile'],
        'icon' => 'chevron-down',
        'oneLiner' => [
            'fa' => 'کاشی شبکه‌ای که شِورانش می‌چرخد و محتوا را داخل همان قاب تخت رونده می‌کند؛ آکاردئونی که به‌جای ستون، در گرید زندگی می‌کند.',
            'en' => 'A grid tile whose chevron spins to unfurl content inside the same flat frame — an accordion that lives in the grid, not in a column.',
        ],
        'js' => true,
        'docs' => 'https://react.carbondesignsystem.com/?path=/docs/components-tile--overview',
        'props' => [
            ['name' => 'expanded', 'type' => 'boolean', 'default' => 'false', 'note' => [
                'fa' => 'باز بودن کاشی؛ با کلیک روی هر جای نوار بالا (نه دکمه‌های داخلش) برعکس می‌شود.',
                'en' => 'Whether the tile is open; clicking anywhere above the fold (not inner buttons) flips it.',
            ]],
            ['name' => 'chevron', 'type' => 'rotate', 'default' => "'0 → 180°'", 'note' => [
                'fa' => 'شِوران پایانی با چرخش ۱۸۰ درجهی نرم نقش خود را عوض می‌کند — امضای حرکتی کاشی بازشو.',
                'en' => 'The chevron flips 180° with a soft rotation — the expandable tile’s motion signature.',
            ]],
            ['name' => 'below-the-fold', 'type' => 'slot', 'default' => 'null', 'note' => [
                'fa' => 'محتوای بازشو در گرید ردیف پنهان است و با باز شدن، ارتفاع کاشی را با ترنزیشن grid رشد می‌دهد.',
                'en' => 'The below-the-fold content hides in a grid row and grows the tile with a grid transition when opened.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="cbex" data-open="false">
            <button class="cbex-head" aria-expanded="false">
                <b>ناحیهٔ فرانکفورت</b>
                <span class="cbex-chevron">⌄</span>
            </button>
            <div class="cbex-body">
                <p>۳ ناحیهٔ دسترس‌پذیر · فاصلهٔ ۲۴ms از تهران</p>
            </div>
        </div>

        <style>
        .cbex { border: 1px solid #e0e0e0; background: #fff;
                display: grid; grid-template-rows: auto 0fr; transition: grid-template-rows .3s; }
        .cbex[data-open='true'] { grid-template-rows: auto 1fr; border-color: #8d8d8d; }
        .cbex-body { overflow: hidden; padding-inline: 1rem; }
        .cbex[data-open='true'] .cbex-body { padding-block-end: 1rem; }
        .cbex[data-open='true'] .cbex-chevron { rotate: 180deg; }
        </style>
        BLADE,
    ],

    'combo-button' => [
        'title' => ['fa' => 'دکمهٔ ترکیبی', 'en' => 'Combo Button'],
        'icon' => 'arrow-right',
        'oneLiner' => [
            'fa' => 'اکشن اصلی همیشه دیده می‌شود و بدنهٔ پیکانی‌دار، اکشن‌های جایگزین را زیرش باز می‌کند؛ صرفه‌جویی در فضا به سبک تخت و مربعِ کربن.',
            'en' => 'Primary action always visible while the arrow body unfolds the alternates beneath it — Carbon’s space-saving split button with a flat, squared dropdown.',
        ],
        'js' => true,
        'docs' => 'https://react.carbondesignsystem.com/?path=/docs/components-combobutton--overview',
        'props' => [
            ['name' => 'kind', 'type' => 'string', 'default' => "'primary'", 'note' => [
                'fa' => 'primary، secondary، tertiary یا danger؛ هر دو بدنهٔ دکمه یک رنگ و یک هاور می‌گیرند.',
                'en' => 'primary, secondary, tertiary or danger; both halves share one colour and one hover.',
            ]],
            ['name' => 'direction', 'type' => 'string', 'default' => "'bottom'", 'note' => [
                'fa' => 'جهت باز شدن فهرست؛ top برای نشاندن دکمه نزدیک لبهٔ پایین صفحه.',
                'en' => 'Which way the menu opens; top when the button sits near the bottom edge.',
            ]],
            ['name' => 'primary-action', 'type' => 'click', 'default' => 'required', 'note' => [
                'fa' => 'بدنهٔ برچسب‌دار همیشه اکشن اصلی را اجرا می‌کند؛ فهرست فقط جایگزین‌ها را می‌آورد.',
                'en' => 'The labelled half always runs the primary action; the menu only lists alternates.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="cbcb" data-open="false">
            <button class="cbcb-main">استقرار</button>
            <button class="cbcb-arrow" aria-expanded="false" aria-label="اقدام‌های بیشتر">⌄</button>
            <ul class="cbcb-menu" role="menu">
                <li role="menuitem">استقرار آزمایشی</li>
                <li role="menuitem">اجرای مهاجرت‌ها</li>
            </ul>
        </div>

        <style>
        .cbcb { position: relative; display: inline-flex; font: 400 .875rem/1 'IBM Plex Sans', sans-serif; }
        .cbcb-main, .cbcb-arrow { block-size: 3rem; border: none; background: #0f62fe; color: #fff; cursor: pointer; }
        .cbcb-main { padding-inline: 1rem; }
        .cbcb-arrow { inline-size: 3rem; border-inline-start: 1px solid #ffffff40; }
        .cbcb-menu { position: absolute; inset-block-start: 100%; inset-inline: 0; margin: 0; padding: 0;
                     list-style: none; background: #f4f4f4; box-shadow: 0 2px 6px rgba(0,0,0,.2); }
        .cbcb[data-open='false'] .cbcb-menu { display: none; }
        .cbcb-menu li { padding: .65rem 1rem; border-block-end: 1px solid #e0e0e0; }
        </style>
        BLADE,
    ],

    'overflow-menu' => [
        'title' => ['fa' => 'منوی سرریز', 'en' => 'Overflow Menu'],
        'icon' => 'menu',
        'oneLiner' => [
            'fa' => 'سه‌نقطهٔ همیشه‌حاضر که فهرست اکشن‌های تختِ بی‌حاشیه را زیر خودش باز می‌کند و اکشن مخرب را ته فهرست قرنطینه کرده است.',
            'en' => 'The ever-present kebab: borderless flat items snap open beneath a 3-dot button, with the destructive action quarantined at the bottom.',
        ],
        'js' => true,
        'docs' => 'https://react.carbondesignsystem.com/?path=/docs/components-overflowmenu--overview',
        'props' => [
            ['name' => 'flipped', 'type' => 'boolean', 'default' => 'false', 'note' => [
                'fa' => 'منوی نزدیک لبهٔ صفحه را با flipped به سمت دیگر لنگر کنید تا بیرون نریزد.',
                'en' => 'Anchor the menu to the other side with flipped so it never spills past the edge.',
            ]],
            ['name' => 'danger', 'type' => 'boolean', 'default' => 'false', 'note' => [
                'fa' => 'آیتم مخرب همیشه آخرین ردیف است و با قرمز #da1e28 خوانده می‌شود.',
                'en' => 'The destructive item is always the last row, read in red #da1e28.',
            ]],
            ['name' => 'size', 'type' => 'string', 'default' => "'md'", 'note' => [
                'fa' => 'sm، md یا lg؛ دکمهٔ سه‌نقطه هم‌اندازهٔ فهرستش بزرگ می‌شود.',
                'en' => 'sm, md or lg; the kebab button scales with its menu.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="cbov" data-open="false">
            <button class="cbov-btn" aria-expanded="false" aria-label="گزینه‌ها">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="5.5" r="1.7"/>…</svg>
            </button>
            <ul class="cbov-menu" role="menu">
                <li role="menuitem">تغییر نام</li>
                <li role="menuitem">دانلود</li>
                <li role="menuitem" data-danger>حذف</li>
            </ul>
        </div>

        <style>
        .cbov { position: relative; }
        .cbov-btn { inline-size: 2rem; aspect-ratio: 1; border: none; background: transparent; cursor: pointer; }
        .cbov[data-open='true'] .cbov-btn { background: #e0e0e0; }
        .cbov-menu { position: absolute; inset-block-start: calc(100% + 2px); inset-inline-end: 0; min-inline-size: 10rem;
                     margin: 0; padding: 0; list-style: none; background: #f4f4f4;
                     box-shadow: 0 2px 6px rgba(0,0,0,.2); }
        .cbov[data-open='false'] .cbov-menu { display: none; }
        .cbov-menu li { padding: .55rem 1rem; border-block-end: 1px solid #e0e0e0; font: 400 .875rem/1.3 'IBM Plex Sans'; }
        .cbov-menu [data-danger] { color: #da1e28; }
        </style>
        BLADE,
    ],

    'ai-slug' => [
        'title' => ['fa' => 'لیبل هوش مصنوعی (Slug)', 'en' => 'AI Label (Slug)'],
        'icon' => 'sparkles',
        'oneLiner' => [
            'fa' => 'نشان کوچک هوش مصنوعی که به محتوای تولیدی می‌چسبد و با کلیک، توضیح مدل و اکشن‌ها را پاپ‌آپ می‌کند؛ امضای عصر AI در کربن.',
            'en' => 'Carbon’s AI badge pinned to generated content — click it to pop open which model made this, plus actions; the signature of Carbon’s AI era.',
        ],
        'js' => true,
        'docs' => 'https://react.carbondesignsystem.com/?path=/docs/components-ailabel--overview',
        'props' => [
            ['name' => 'kind', 'type' => 'string', 'default' => "'default'", 'note' => [
                'fa' => 'default یا inline؛ حالت inline همان نشان را درون سرخط‌ها و متن‌ها می‌نشاند.',
                'en' => 'default or inline; the inline kind sits the same badge inside headings and copy.',
            ]],
            ['name' => 'size', 'type' => 'string', 'default' => "'xs · sm · md'", 'note' => [
                'fa' => 'سه اندازهٔ رسمی نشان؛ کوچکش برای جاگیری کنار متن و درشتش برای سطح‌های خالی.',
                'en' => 'The three official badge sizes; small to hug text, large to mark open surfaces.',
            ]],
            ['name' => 'revert', 'type' => 'action', 'default' => 'optional', 'note' => [
                'fa' => 'پاپ‌آپ نشان می‌تواند دکمهٔ «بازگرداندن» داشته باشد؛ کل محتوای تولیدشده یک‌کلیکی به نسخهٔ انسانی برمی‌گردد.',
                'en' => 'The badge popover can carry a revert button; one click rolls the generated content back to the human version.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="cbai">
            <button class="cbai-slug" aria-label="تولیدشده با هوش مصنوعی">AI</button>
            <div class="cbai-pop" role="dialog" data-open="false">
                <b>پاسخ پیشنهادی مدل</b>
                <small>نابو-پاسخ ۳ · اطمینان ۹۲٪</small>
                <footer><button>واگرد</button></footer>
            </div>
        </div>

        <style>
        .cbai { position: relative; display: inline-flex; }
        .cbai-slug { inline-size: 1.5rem; aspect-ratio: 1; border: none; border-radius: 999px;
                     background: linear-gradient(120deg, #8a3ffc, #d02670, #1192e8); color: #fff;
                     font: 600 .65rem/1 'IBM Plex Sans'; cursor: pointer; }
        .cbai-pop { position: absolute; inset-block-start: calc(100% + 8px); inset-inline-end: 0;
                    inline-size: 16rem; padding: 1rem; background: #f4f4f4; }
        .cbai-pop[data-open='false'] { display: none; }
        </style>
        BLADE,
    ],

    'contained-list' => [
        'title' => ['fa' => 'فهرست دربرگیرنده', 'en' => 'Contained List'],
        'icon' => 'users',
        'oneLiner' => [
            'fa' => 'فهرستی که خودش ظرف است: سربرگ، اکشن‌ها و بخش‌بندی داخل یک قاب تخت، با آیتم‌های تعاملی؛ پاسخ کربن به فهرست‌های تنظیمات گروهی.',
            'en' => 'A list that is its own container — header, actions and sections inside one flat frame, row by row interactive; Carbon’s answer to grouped settings lists.',
        ],
        'js' => true,
        'docs' => 'https://react.carbondesignsystem.com/?path=/docs/components-containedlist--overview',
        'props' => [
            ['name' => 'label', 'type' => 'string', 'default' => 'required', 'note' => [
                'fa' => 'سربرگ درون قاب می‌نشیند؛ نه بیرون آن — همان چیزی که «دربرگیرنده» بودنش را می‌سازد.',
                'en' => 'The header sits inside the frame, not above it — exactly what makes it contained.',
            ]],
            ['name' => 'action', 'type' => 'slot', 'default' => 'null', 'note' => [
                'fa' => 'جای یک دکمهٔ آیکنی در سربرگ؛ جست‌وجو، افزودن یا فیلتر.',
                'en' => 'Room for one icon button in the header — search, add or filter.',
            ]],
            ['name' => 'isInset', 'type' => 'boolean', 'default' => 'false', 'note' => [
                'fa' => 'با inset، هاور ردیف‌ها تا لبهٔ قاب می‌رود و کناره‌ها را هم رنگ می‌گیرد.',
                'en' => 'With inset, the row hover reaches the frame edge and paints the gutters too.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="cbcl">
            <header class="cbcl-head">
                <b>اعضای تیم</b>
                <button aria-label="افزودن عضو">+</button>
            </header>
            <ul>
                <li><button>سارا محمدی — مالک</button></li>
                <li><button>امیر رستمی — توسعه‌دهنده</button></li>
            </ul>
        </div>

        <style>
        .cbcl { border: 1px solid #e0e0e0; background: #fff; font: 400 .875rem/1.4 'IBM Plex Sans'; }
        .cbcl-head { display: flex; align-items: center; justify-content: space-between;
                     padding: .75rem 1rem; border-block-end: 1px solid #e0e0e0; }
        .cbcl li + li { border-block-start: 1px solid #e0e0e0; }
        .cbcl li button { inline-size: 100%; padding: .75rem 1rem; border: none;
                          background: transparent; text-align: start; cursor: pointer; }
        .cbcl li button:hover { background: #e8e8e8; }
        </style>
        BLADE,
    ],

    'tearsheet' => [
        'title' => ['fa' => 'تیرشیت', 'en' => 'Tearsheet'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'مودال عریضِ دسکتاپی که از لبهٔ پایین بالا می‌آید و با ریل ناوبری کناری، جریان‌های چندمرحله‌ای را روی یک سطح مدیریت می‌کند.',
            'en' => 'The wide desktop modal that tears up from the bottom edge — with a side rail for multi-step flows, it stages whole tasks on one surface.',
        ],
        'js' => true,
        'docs' => 'https://ibm-products.carbondesignsystem.com/?path=/docs/components-tearsheet--overview',
        'props' => [
            ['name' => 'open', 'type' => 'boolean', 'default' => 'false', 'note' => [
                'fa' => 'باز شدن از لبهٔ پایین با ترنزیشن ۲۴۰ms منحنیِ productive؛ Escape و پرده هم می‌بندند.',
                'en' => 'Tears up from the bottom edge on the 240ms productive curve; Escape and the scrim close it too.',
            ]],
            ['name' => 'influencer', 'type' => 'slot', 'default' => 'null', 'note' => [
                'fa' => 'ریل کناریِ پهنای ثابت برای ناوبری مراحل؛ داخلش Progress Indicator کربن می‌نشیند.',
                'en' => 'The fixed-width side rail for step navigation; Carbon’s Progress Indicator lives inside it.',
            ]],
            ['name' => 'actions', 'type' => 'footer', 'default' => "'primary · secondary'", 'note' => [
                'fa' => 'نوار پایه با اکشن اصلی و ثانویه؛ اکشن اصلی در مرحلهٔ آخر «پایان» می‌شود.',
                'en' => 'The base bar with a primary and secondary action; the primary turns into “Finish” on the last step.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="cbts" data-open="false">
            <div class="cbts-scrim"></div>
            <section class="cbts-sheet" role="dialog" aria-label="افزودن فضای ذخیره">
                <aside class="cbts-rail">
                    <ol><li data-current>جزئیات فضای ذخیره</li><li>انتخاب ناحیه</li><li>بازبینی</li></ol>
                </aside>
                <div class="cbts-body"> … </div>
                <footer class="cbts-footer">
                    <button data-primary>بعدی</button>
                    <button>انصراف</button>
                </footer>
            </section>
        </div>

        <style>
        .cbts { position: relative; overflow: clip; }
        .cbts-scrim { position: absolute; inset: 0; background: rgba(22,22,22,.5); }
        .cbts-sheet { position: absolute; inset-inline: 3rem; inset-block-end: 0; block-size: calc(100% - 3rem);
                      background: #f4f4f4; display: grid; grid-template-columns: 16rem 1fr; grid-template-rows: 1fr auto; }
        .cbts[data-open='false'] .cbts-sheet { translate: 0 100%; }
        .cbts-sheet { transition: translate .24s cubic-bezier(.2, 0, 0, 1); }
        </style>
        BLADE,
    ],

    'data-spreadsheet' => [
        'title' => ['fa' => 'صفحه‌گستردهٔ داده', 'en' => 'Data Spreadsheet'],
        'icon' => 'chart',
        'oneLiner' => [
            'fa' => 'شبکهٔ سلولی با ستون‌های حرفی و سطرهای عددی اکسلی و انتخابِ سل‌به‌سل؛ جدول نیست، بوم محاسباتی کربن است.',
            'en' => 'An Excel-like cell grid with lettered columns, numbered rows and cell-level selection — not a table, Carbon’s computational canvas.',
        ],
        'js' => true,
        'docs' => 'https://ibm-products.carbondesignsystem.com/?path=/docs/deprecated-dataspreadsheet--overview',
        'props' => [
            ['name' => 'columns', 'type' => 'letters', 'default' => "'A – H'", 'note' => [
                'fa' => 'سربرگ ستون‌ها حرف‌اند نه برچسب؛ A تا H پیش‌فرض و با دیتای ورودی گسترش می‌یابد.',
                'en' => 'Column headers are letters, not labels; A to H by default, growing with the input data.',
            ]],
            ['name' => 'activeCell', 'type' => 'coordinates', 'default' => "'A1'", 'note' => [
                'fa' => 'سل فعال با نوار ۲پیکسلی آبی و مختصاتش در جعبهٔ گوشهٔ بالا-آغازین خوانده می‌شود.',
                'en' => 'The active cell reads through a 2px blue ring and its coordinates in the top-start corner box.',
            ]],
            ['name' => 'spreadsheetData', 'type' => 'sparse 2D', 'default' => 'null', 'note' => [
                'fa' => 'آرایهٔ دوبعدی خلوت؛ سلول‌های خالی هم فضای شبکه را نگه می‌دارند تا بوم جابه‌جا نشود.',
                'en' => 'A sparse 2D array; empty cells still hold their grid slot so the canvas never shifts.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="cbsp" x-data="{ active: 'A1' }">
            <header class="cbsp-ruler">
                <span class="cbsp-ref" x-text="active">A1</span>
                <span class="cbsp-formula" x-text="cell(active)">۱۲٬۵۰۰</span>
            </header>
            <div class="cbsp-grid" role="grid">
                <button role="gridcell" class="cbsp-cell" data-ref="A1">۱۲٬۵۰۰</button>
            </div>
        </div>

        <style>
        .cbsp { border: 1px solid #8d8d8d; font: 400 .75rem/1 'IBM Plex Mono', monospace; }
        .cbsp-ruler { display: flex; border-block-end: 1px solid #8d8d8d; }
        .cbsp-ref { min-inline-size: 4rem; padding: .5rem; border-inline-end: 1px solid #8d8d8d; }
        .cbsp-cell { inline-size: 6rem; block-size: 2rem; border: 1px solid #e0e0e0;
                     background: #fff; cursor: cell; text-align: start; }
        .cbsp-cell[data-active] { outline: 2px solid #0f62fe; outline-offset: -2px; }
        </style>
        BLADE,
    ],
];
