<?php

/**
 * Demo manifest of the "actions" group — the buttons with a story to tell
 * (marching borders, filling circles, liquid metal, transactions), the pickers
 * that collect choices, and the two loaders. The file name is the group id,
 * every key below is a demo slug and its partial lives at
 * resources/views/demos/components/actions/{slug}.blade.php.
 */
return [
    '__group' => ['fa' => 'کنش', 'en' => 'Actions'],

    'border-button' => [
        'title' => ['fa' => 'دکمهٔ قاب‌دار', 'en' => 'Border button'],
        'icon' => 'check-circle',
        'oneLiner' => [
            'fa' => 'دکمه‌ای که قابش نقطه‌چین و در حال رژه است؛ زیر لود می‌چرخد، در موفقیت حلقه‌اش بسته و تیک می‌شود و در خطا می‌لرزد.',
            'en' => 'A button whose dashed frame marches; it spins under load, closes into a ring with a drawn check on success and shakes on error.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'status', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'حالت دستی loading · success · error؛ با wire:click دکمه خودش از wire:loading پیروی می‌کند و نقطه‌چین‌ها می‌چرخند.',
                'en' => 'Manual loading · success · error; with wire:click it follows wire:loading automatically and the dashes spin.',
            ]],
            ['name' => 'successLabel / errorLabel', 'type' => 'string', 'default' => "'Done' / __('nabuxui::ui.failed')", 'note' => [
                'fa' => 'برچسبی که در هر حالت جای برچسب اصلی می‌نشیند؛ برای صفحه‌خوان‌ها هم در aria-live پخش می‌شود.',
                'en' => 'The label that replaces the default one in each state; it is also announced through the aria-live copy.',
            ]],
            ['name' => 'icon', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'آیکون کنار برچسبِ حالت عادی.',
                'en' => 'An icon beside the idle label.',
            ]],
            ['name' => 'size', 'type' => 'string', 'default' => "'md'", 'note' => [
                'fa' => 'اندازه‌ها: xs · sm · md · lg.',
                'en' => 'Sizes: xs · sm · md · lg.',
            ]],
            ['name' => 'target', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'وقتی از wire:click استفاده نمی‌کنید، wire:target را دستی بدهید تا wire:loading دکمه را بگیرد.',
                'en' => 'When not using wire:click, give wire:target by hand so wire:loading picks the button up.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::border-button icon="file" wire:click="save" success-label="ذخیره شد">
            ذخیرهٔ پیش‌نویس
        </x-nx::border-button>
        BLADE,
    ],

    'fill-button' => [
        'title' => ['fa' => 'دکمهٔ پرشونده', 'en' => 'Fill button'],
        'icon' => 'plus',
        'oneLiner' => [
            'fa' => 'دایره‌ای از همان سمتی که موس وارد شد بزرگ می‌شود تا کل دکمه را پر کند و برچسب برای کپی‌اش بالا می‌خزد؛ در خروج هم به سمت خروج موس عقب می‌نشیند.',
            'en' => 'A circle grows from the side the pointer entered until it fills the button while the label slides up for its copy; on leave it retreats toward the exit.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'variant', 'type' => 'string', 'default' => "'accent'", 'note' => [
                'fa' => 'رنگ پرشده: accent · inverse · gold.',
                'en' => 'The fill colour: accent · inverse · gold.',
            ]],
            ['name' => 'hoverLabel', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'برچسب دوم که با ورود موس بالا می‌آید؛ بدون آن همان برچسب تکرار می‌شود.',
                'en' => 'The second label that rolls in on hover; without it the idle label repeats.',
            ]],
            ['name' => 'icon / iconEnd', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'آیکون ابتدا یا انتهای برچسب — در هر دو کپی.',
                'en' => 'An icon leading or trailing the label — in both copies.',
            ]],
            ['name' => 'shape', 'type' => 'string', 'default' => "'pill'", 'note' => [
                'fa' => 'pill یا rounded؛ گوشه‌ها با فنر با پرشده هم‌راستا می‌شوند.',
                'en' => 'pill or rounded; the corners stay concentric with the fill.',
            ]],
            ['name' => 'href', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'با href دکمه به لینک تبدیل می‌شود.',
                'en' => 'With href the button becomes a link.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::fill-button variant="gold" hover-label="رایگان شروع کن" wire:click="save">
            شروع رایگان
        </x-nx::fill-button>
        BLADE,
    ],

    'flip-button' => [
        'title' => ['fa' => 'دکمهٔ چرخان', 'en' => 'Flip button'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'هاور یا فوکوس، هر پارهٔ دکمه را مثل وجه یک مکعب یک‌چهارم دور می‌دهد تا برچسب و آیکون دوم پیدا شود — کاملاً CSS و با کیبورد کار می‌کند.',
            'en' => 'Hover or focus rolls each part of the button a quarter turn like the face of a cube to reveal the second label and icon — pure CSS, fully keyboard operable.',
        ],
        'js' => false,
        'docs' => null,
        'props' => [
            ['name' => 'label', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'برچسب حالت عادی (یا از اسلات بدهید).',
                'en' => 'The idle label (or pass it through the slot).',
            ]],
            ['name' => 'hoverLabel', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'برچسبِ روی وجه دوم مکعب.',
                'en' => 'The label on the cube’s second face.',
            ]],
            ['name' => 'icon / hoverIcon', 'type' => 'string', 'default' => "'arrow-right' / 'sparkles'", 'note' => [
                'fa' => 'آیکون وجه اول و دوم؛ آیکون‌های جهت‌دار در RTL آینه می‌شوند.',
                'en' => 'The first and second face’s icons; directional ones mirror in RTL.',
            ]],
            ['name' => 'variant', 'type' => 'string', 'default' => "'primary'", 'note' => [
                'fa' => 'primary · secondary · inverse.',
                'en' => 'primary · secondary · inverse.',
            ]],
            ['name' => 'size', 'type' => 'string', 'default' => "'md'", 'note' => [
                'fa' => 'اندازه‌ها: sm · md · lg.',
                'en' => 'Sizes: sm · md · lg.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::flip-button label="ادامهٔ راه‌اندازی" hover-label="بزن بریم" icon="arrow-right" hover-icon="zap"
            wire:click="save" />
        BLADE,
    ],

    'metal-button' => [
        'title' => ['fa' => 'دکمهٔ فلزی', 'en' => 'Metal button'],
        'icon' => 'mic',
        'oneLiner' => [
            'fa' => 'تاگلِ فلزِ مایع (aria-pressed): در حالت فشرده rim رنگین‌کمانی می‌شود و آیکون به active-icon (پیش‌فرض: میله‌های موج زنده) بدل می‌شود.',
            'en' => 'A liquid-metal toggle (aria-pressed): pressed, the rim turns iridescent and the icon swaps for active-icon (live wave bars by default).',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'label', 'type' => 'string', 'default' => "''", 'note' => [
                'fa' => 'برچسب دکمه؛ در حالت round عنوان/تول‌تیپ است و در pill کنار آیکون می‌نشیند.',
                'en' => 'The button’s label; its title/tooltip when round, and inline text when pill.',
            ]],
            ['name' => 'icon / activeIcon', 'type' => 'string', 'default' => "'mic' / null", 'note' => [
                'fa' => 'آیکون حالت خاموش و روشن؛ active-icon ندارید، میله‌های موج زنده می‌آیند.',
                'en' => 'The off and on icons; without active-icon the live wave bars show.',
            ]],
            ['name' => 'shape', 'type' => 'string', 'default' => "'round'", 'note' => [
                'fa' => 'round (فقط آیکون) یا pill (آیکون + برچسب).',
                'en' => 'round (icon alone) or pill (icon + label).',
            ]],
            ['name' => 'pressed', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'حالت اولیه؛ با wire:model دکمه دنبال پراپرتی Livewire می‌رود و رویداد nx-change هم پخش می‌شود.',
                'en' => 'The initial state; with wire:model it follows the Livewire property and also dispatches nx-change.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::metal-button label="ورودی صدا" wire:model.live="state.mic" />
        BLADE,
    ],

    'blob-button' => [
        'title' => ['fa' => 'دکمهٔ blob', 'en' => 'Blob button'],
        'icon' => 'sparkles',
        'oneLiner' => [
            'fa' => 'قرص تیره با blobهای لاجوردی، بنفش و طلایی که درونش رها می‌شوند و زیر موس دور نشانگر جمع می‌شوند.',
            'en' => 'A dark pill with lapis, violet and gold blobs drifting inside that gather around the pointer.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'icon / iconEnd', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'آیکون ابتدا یا انتهای برچسب.',
                'en' => 'An icon leading or trailing the label.',
            ]],
            ['name' => 'size', 'type' => 'string', 'default' => "'md'", 'note' => [
                'fa' => 'اندازه‌ها: sm · md · lg.',
                'en' => 'Sizes: sm · md · lg.',
            ]],
            ['name' => 'href', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'با href دکمه به لینک تبدیل می‌شود.',
                'en' => 'With href the button becomes a link.',
            ]],
            ['name' => 'static', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'دنبال‌کردن موس را خاموش می‌کند؛ blobها همان‌طور رها می‌شوند.',
                'en' => 'Turns off the pointer-following; the blobs keep drifting.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::blob-button icon="sparkles" size="lg" wire:click="save">
            گفت‌وگو با نابو
        </x-nx::blob-button>
        BLADE,
    ],

    'transaction-button' => [
        'title' => ['fa' => 'دکمهٔ تراکنش', 'en' => 'Transaction button'],
        'icon' => 'lock',
        'oneLiner' => [
            'fa' => 'کل جریان پرداخت در یک دکمه: پرداخت ← در حال پردازش (پیکسل‌ها موج می‌زنند و عرض با کلمات فنری می‌شود) ← پرداخت شد؛ خطا هم قفل را به ضربدر می‌شکند.',
            'en' => 'The whole payment flow in one button: Pay → Processing (pixels ripple, the width springs to fit the words) → Paid; an error breaks the lock into a cross.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'labels', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'نقشهٔ idle · loading · success · error؛ هر که ندهید از پیش‌فرض‌های دوزبانهٔ پکیج می‌آید.',
                'en' => 'A map of idle · loading · success · error; missing keys fall back to the package’s built-in words.',
            ]],
            ['name' => 'status', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'حالت دستی برای هدایت از سرور؛ با wire:click دکمه خودش را با wire:loading هم‌گام می‌کند.',
                'en' => 'Manual state for server-driven flows; with wire:click it also stays in sync with wire:loading.',
            ]],
            ['name' => 'size', 'type' => 'string', 'default' => "'md'", 'note' => [
                'fa' => 'اندازه‌ها: sm · md · lg.',
                'en' => 'Sizes: sm · md · lg.',
            ]],
            ['name' => 'target', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'wire:target دستی وقتی wire:click ندارید.',
                'en' => 'A manual wire:target when there is no wire:click.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::transaction-button wire:click="save"
            :labels="['idle' => 'پرداخت ۴۹ دلار', 'loading' => 'در حال پردازش…', 'success' => 'پرداخت شد', 'error' => 'رد شد']" />
        BLADE,
    ],

    'file-drop' => [
        'title' => ['fa' => 'رهاکردن فایل', 'en' => 'File drop'],
        'icon' => 'upload',
        'oneLiner' => [
            'fa' => 'دراپ‌زون که فایل‌های رهاشده را به input تغذیه می‌کند؛ با wire:model و WithFileUploads لیست زیرش پیشرفت هر فایل را نشان می‌دهد.',
            'en' => 'A dropzone that feeds dropped files into its input; with wire:model and WithFileUploads the list underneath tracks each file’s progress.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'title', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'متن دعوت؛ پیش‌فرض دوزبانهٔ خودش («فایل‌ها را رها کنید یا :browse» — واژهٔ browse زیرخط‌دار است).',
                'en' => 'The invitation line; the built-in bilingual default is “Drop files or :browse” with the browse word underlined.',
            ]],
            ['name' => 'hint', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'خط کوچک راهنما زیر دعوت — قالب و سقف حجم.',
                'en' => 'The small helper line under the invitation — formats and size limits.',
            ]],
            ['name' => '(ویژگی‌های input)', 'type' => 'attrs', 'default' => '—', 'note' => [
                'fa' => 'هر attributeای (wire:model، multiple، accept، name…) مستقیم روی input فایل می‌نشیند.',
                'en' => 'Any attribute (wire:model, multiple, accept, name…) lands on the file input itself.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::file-drop wire:model="attachments" multiple hint="PDF تا ۲۰ مگابایت" />
        BLADE,
    ],

    'interests-picker' => [
        'title' => ['fa' => 'انتخاب‌گر علایق', 'en' => 'Interests picker'],
        'icon' => 'heart',
        'oneLiner' => [
            'fa' => 'چیپ‌های چندانتخابیِ ایموجی‌دار در ردیف‌هایی که با انگشت می‌لغزند؛ تیک‌زدن ایموجی را به هوا می‌پراند و «پاک‌کردن» آنها را یکی‌یکی می‌لرزاند.',
            'en' => 'Multi-select emoji chips in rows that drag sideways on touch; picking throws the emoji into the air and Clear shakes them off one after another.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'options', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر گزینه: value · label · emoji · disabled (یا به‌شکل نقشهٔ value => label).',
                'en' => 'Each option: value · label · emoji · disabled (or as a value => label map).',
            ]],
            ['name' => 'value', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'انتخاب‌شده‌ها؛ با wire:model آرایه به پراپرتی Livewire می‌چسبد (پراپرتی را آرایه شروع کنید).',
                'en' => 'The picked values; with wire:model the array binds to a Livewire property (initialise it as an array).',
            ]],
            ['name' => 'label', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'عنوان fieldset (legend) بالای چیپ‌ها.',
                'en' => 'The fieldset legend above the chips.',
            ]],
            ['name' => 'rows', 'type' => 'int', 'default' => '3', 'note' => [
                'fa' => 'چند ردیف چیپ داشته باشد؛ گزینه‌ها خودکار بین ردیف‌ها تقسیم می‌شوند.',
                'en' => 'How many rows of chips; the options spread across them automatically.',
            ]],
            ['name' => 'clearLabel', 'type' => 'string', 'default' => "'Clear'", 'note' => [
                'fa' => 'برچسب دکمهٔ پاک‌کردن، با شمارندهٔ انتخاب‌ها کنارش.',
                'en' => 'The Clear button’s label, with the pick counter beside it.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::interests-picker label="به چی علاقه داری؟" wire:model.live="state.interests"
            :value="$state['interests'] ?? ['design', 'food']"
            :options="[
                ['value' => 'design', 'label' => 'طراحی', 'emoji' => '🎨'],
                ['value' => 'food', 'label' => 'غذا', 'emoji' => '🍜'],
            ]" />
        BLADE,
    ],

    'label-creator' => [
        'title' => ['fa' => 'سازندهٔ برچسب', 'en' => 'Label creator'],
        'icon' => 'edit',
        'oneLiner' => [
            'fa' => 'چیپ‌در-فیلد به سبک Notion: نام تازه را بنویسید، رنگش را بگیرید و Enter بزنید؛ لودر پیکسلی کوچکی می‌چرخد و چیپ با فنر می‌نشیند.',
            'en' => 'Notion-style chips-in-a-field: type a new name, pick its colour, press Enter — a tiny pixel loader spins and the chip pops in.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'labels', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'برچسب‌های اولیه: هرکدام name و color.',
                'en' => 'The initial labels: each with name and color.',
            ]],
            ['name' => 'colors', 'type' => 'array', 'default' => 'null', 'note' => [
                'fa' => 'پیکر رنگ‌ها (value · label · color)؛ پیش‌فرض هفت رنگ نموداری است.',
                'en' => 'The colour swatches (value · label · color); defaults to the seven chart colours.',
            ]],
            ['name' => 'placeholder / label / colorLabel', 'type' => 'string', 'default' => "'Add a label…' / 'Labels' / 'Colour'", 'note' => [
                'fa' => 'متن‌های ظاهری فیلد؛ همه دوزبانه قابل‌دادن‌اند.',
                'en' => 'The field’s visible words; all bilingual overridable.',
            ]],
            ['name' => 'createText', 'type' => 'string', 'default' => "'Create “:name”'", 'note' => [
                'fa' => 'متن دکمهٔ ساخت؛ :name جای نام تایپ‌شده است.',
                'en' => 'The create button’s text; :name marks the typed name.',
            ]],
            ['name' => 'name', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'بدون wire:model: با name، لیست به‌شکل JSON در یک input مخفی پست می‌شود.',
                'en' => 'Without wire:model: with name, the list posts as JSON through a hidden input.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::label-creator wire:model.live="state.labels" placeholder="برچسب تازه…"
            create-text="ساخت «:name»" color-label="رنگ"
            x-on:nx-label-created="$wire.ping('برچسب ساخته شد: ' + $event.detail.name)" />
        BLADE,
    ],

    'pixel-loader' => [
        'title' => ['fa' => 'لودر پیکسلی', 'en' => 'Pixel loader'],
        'icon' => 'cpu',
        'oneLiner' => [
            'fa' => 'شبکهٔ پیکسل‌هایی که موج می‌روند، از مرکز موج می‌گیرند یا هرپهلو می‌جنبند — کاملاً CSS، با role=status و برچسب پنهان.',
            'en' => 'A grid of pixels that wave, ripple from the centre or twitch at random — pure CSS, with role=status and a hidden label.',
        ],
        'js' => false,
        'docs' => null,
        'props' => [
            ['name' => 'variant', 'type' => 'string', 'default' => "'wave'", 'note' => [
                'fa' => 'wave (موج مورب) · center (چنگک از مرکز) · chaos (هر سلک با نویز خودش).',
                'en' => 'wave (a diagonal sweep) · center (a ripple from the middle) · chaos (each cell on its own noise).',
            ]],
            ['name' => 'rows / cols', 'type' => 'int', 'default' => '5 / 5', 'note' => [
                'fa' => 'اندازهٔ شبکه؛ ردیف‌های پهن (مثلاً ۳×۲۴) به‌عنوان اسکلتون جدول عالی‌اند.',
                'en' => 'The grid size; wide rows (say 3×24) make a great table skeleton.',
            ]],
            ['name' => 'size', 'type' => 'string', 'default' => "'md'", 'note' => [
                'fa' => 'اندازه‌ها: xs · sm · md · lg.',
                'en' => 'Sizes: xs · sm · md · lg.',
            ]],
            ['name' => 'label', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'متن پنهان برای صفحه‌خوان‌ها؛ با decorative دکوراتیو می‌شود و role را می‌اندازد.',
                'en' => 'The visually-hidden text for screen readers; decorative drops the status role.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::pixel-loader variant="chaos" :rows="3" :cols="24" size="sm" label="در حال بارگیری سفارش‌ها" />
        BLADE,
    ],

    'parametric-loader' => [
        'title' => ['fa' => 'لودر پارامتری', 'en' => 'Parametric loader'],
        'icon' => 'chart',
        'oneLiner' => [
            'fa' => 'مسیرِ رسم‌شده از معادلات پارامتری — گلبرگ رز، اسپیروگراف یا لیساژو — با سرِ درخشان و دنباله‌ای که روی آن می‌دود.',
            'en' => 'A path drawn from parametric equations — a rose, a spirograph or a Lissajous — with a glowing head and a trail running along it.',
        ],
        'js' => false,
        'docs' => null,
        'props' => [
            ['name' => 'kind', 'type' => 'string', 'default' => "'rose'", 'note' => [
                'fa' => 'rose · spiro · lissajous.',
                'en' => 'rose · spiro · lissajous.',
            ]],
            ['name' => 'options', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'rose با n و d، spiro با R و r و offset، lissajous با a و b و phase؛ samples و padding هم مشترک‌اند.',
                'en' => 'rose takes n and d, spiro R, r and offset, lissajous a, b and phase; samples and padding are shared.',
            ]],
            ['name' => 'duration', 'type' => 'int', 'default' => 'null', 'note' => [
                'fa' => 'مدت یک دور کامل به میلی‌ثانیه — مثلاً ۴۲۰۰ برای آرام‌تر.',
                'en' => 'One full lap in milliseconds — 4200 for a calmer loop.',
            ]],
            ['name' => 'size', 'type' => 'string', 'default' => "'md'", 'note' => [
                'fa' => 'اندازه‌ها: sm · md · lg.',
                'en' => 'Sizes: sm · md · lg.',
            ]],
            ['name' => 'label', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'متن پنهان نقش status.',
                'en' => 'The hidden text behind the status role.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::parametric-loader kind="lissajous" size="lg" :options="['a' => 5, 'b' => 4]" label="در حال فکر کردن" />
        BLADE,
    ],
];
