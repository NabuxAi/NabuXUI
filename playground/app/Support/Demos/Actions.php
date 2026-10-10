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

    'gradient-hover-button' => [
        'title' => ['fa' => 'دکمهٔ گرادیان جاری', 'en' => 'Flowing gradient button'],
        'icon' => 'wand',
        'oneLiner' => [
            'fa' => 'گرادیان با عرض ۲۲۰٪ زیر دکمه رنگ می‌خورد و هاور آن را سُر می‌دهد؛ برچسب هم قدری از مسیرش کنار می‌خزد — سه پالت، سه سایز، حالت لینک و سوییچ «همیشه جاری» برای هیروها.',
            'en' => 'The gradient is painted at 220% under the button and hover slides it across while the label leans out of its way — three palettes, three sizes, a link mode and an always-flowing switch for heroes.',
        ],
        'js' => true,
        'docs' => 'https://gradientbuttons.colorion.co',
        'props' => [
            ['name' => '--ghb-a · --ghb-b · --ghb-c', 'type' => 'color', 'default' => "'#7c3aed · #db2777 · #f59e0b'", 'note' => [
                'fa' => 'سه ایستگاه رنگ گرادیان جاری؛ پالت‌ها (شفق، اقیانوس، چمنزار) فقط همین سه متغیر را عوض می‌کنند.',
                'en' => 'The three stops of the flowing gradient; the palettes (aurora, ocean, meadow) swap exactly these variables.',
            ]],
            ['name' => 'background-size', 'type' => 'technique', 'default' => "'220% 220%'", 'note' => [
                'fa' => 'گرادیان بزرگ‌تر از دکمه نقاشی می‌شود و هاور فقط background-position را جابه‌جا می‌کند — بدون محاسبه و بدون جابه‌جایی لایه‌ها.',
                'en' => 'The gradient is painted larger than the button and hover only moves background-position — no math, no layer juggling.',
            ]],
            ['name' => 'labelOffset', 'type' => 'px', 'default' => "'2 · -1'", 'note' => [
                'fa' => 'برچسب در هاور ۲ پیکسل به جلو و ۱ پیکسل بالا می‌خزد؛ در راست‌به‌چپ به سمت مقابل خم می‌شود.',
                'en' => 'On hover the label steps 2px forward and 1px up; in RTL it leans the other way.',
            ]],
            ['name' => 'size', 'type' => 'string', 'default' => "'md'", 'note' => [
                'fa' => 'اندازه‌ها: sm · md · lg.',
                'en' => 'Sizes: sm · md · lg.',
            ]],
            ['name' => 'flow', 'type' => 'boolean', 'default' => 'false', 'note' => [
                'fa' => 'با data-flow گرادیان بی‌موس و بی‌وقفه جاری می‌ماند؛ برای بنرها و هیروها.',
                'en' => 'With data-flow the gradient keeps drifting with no pointer — for banners and heroes.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="ghb" x-data="{ flow: false, palette: 'aurora' }">
            <div class="ghb-chips" role="group" aria-label="پالت گرادیان">
                <button type="button" class="ghb-chip" x-bind:aria-pressed="palette === 'aurora'" x-on:click="palette = 'aurora'">شفق</button>
                <button type="button" class="ghb-chip" x-bind:aria-pressed="palette === 'ocean'" x-on:click="palette = 'ocean'">اقیانوس</button>
                <button type="button" class="ghb-chip" x-bind:aria-pressed="palette === 'meadow'" x-on:click="palette = 'meadow'">چمنزار</button>
            </div>
            <label class="ghb-switch">
                <input type="checkbox" x-model="flow">
                جریان همیشگی
            </label>
            <button type="button" class="ghb-btn" data-size="lg" x-bind:data-palette="palette" x-bind:data-flow="flow">
                <span class="ghb-label">ارتقا به پلن پرو</span>
            </button>
            <a class="ghb-btn" data-palette="ocean" href="/plans">
                <span class="ghb-label">دیدن همهٔ پلن‌ها</span>
            </a>
        </div>

        <style>
        .ghb { display: grid; gap: 1rem; justify-items: center; }
        .ghb-chips { display: flex; flex-wrap: wrap; gap: .5rem; justify-content: center; }
        .ghb-chip { padding: .4rem .9rem; border: 1px solid #e2e8f0; border-radius: 999px;
                    background: #fff; color: #0f172a; cursor: pointer; font-size: .8rem; }
        .ghb-chip[aria-pressed='true'] { border-color: #7c3aed; color: #7c3aed; }
        .ghb-switch { display: inline-flex; align-items: center; gap: .5rem; font-size: .85rem; color: #475569; }
        .ghb-btn {
            display: inline-flex; align-items: center; justify-content: center;
            padding: .7rem 1.6rem; border: 0; border-radius: 999px; cursor: pointer;
            color: #fff; font-weight: 600; font-size: .95rem; line-height: 1; text-decoration: none;
            --ghb-a: #7c3aed; --ghb-b: #db2777; --ghb-c: #f59e0b;
            background-image: linear-gradient(115deg, var(--ghb-a), var(--ghb-b) 45%, var(--ghb-c));
            background-size: 220% 220%; background-position: 12% 50%;
            transition: background-position .6s ease, box-shadow .35s ease;
        }
        .ghb-btn[data-palette='ocean'] { --ghb-a: #06b6d4; --ghb-b: #3b82f6; --ghb-c: #6366f1; }
        .ghb-btn[data-palette='meadow'] { --ghb-a: #10b981; --ghb-b: #84cc16; --ghb-c: #facc15; }
        .ghb-btn[data-size='lg'] { padding: .95rem 2.1rem; font-size: 1.1rem; }
        .ghb-btn:hover, .ghb-btn:focus-visible { background-position: 88% 50%; box-shadow: 0 12px 32px -12px var(--ghb-b); }
        .ghb-btn:focus-visible { outline: 2px solid var(--ghb-b); outline-offset: 3px; }
        .ghb-label { transition: translate .35s ease; }
        .ghb-btn:hover .ghb-label, .ghb-btn:focus-visible .ghb-label { translate: 2px -1px; }
        html[dir='rtl'] .ghb-btn:hover .ghb-label,
        html[dir='rtl'] .ghb-btn:focus-visible .ghb-label { translate: -2px -1px; }
        .ghb-btn[data-flow='true'] { animation: ghb-flow 3.2s ease-in-out infinite alternate; }
        @keyframes ghb-flow { from { background-position: 12% 50%; } to { background-position: 88% 50%; } }
        @media (prefers-reduced-motion: reduce) {
            .ghb-btn, .ghb-label { transition: none; }
            .ghb-btn[data-flow='true'] { animation: none; }
        }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="ghb" x-data="{ flow: false, palette: 'aurora' }">
            <div class="ghb-chips" role="group" aria-label="Gradient palette">
                <button type="button" class="ghb-chip" x-bind:aria-pressed="palette === 'aurora'" x-on:click="palette = 'aurora'">Aurora</button>
                <button type="button" class="ghb-chip" x-bind:aria-pressed="palette === 'ocean'" x-on:click="palette = 'ocean'">Ocean</button>
                <button type="button" class="ghb-chip" x-bind:aria-pressed="palette === 'meadow'" x-on:click="palette = 'meadow'">Meadow</button>
            </div>
            <label class="ghb-switch">
                <input type="checkbox" x-model="flow">
                Keep it flowing
            </label>
            <button type="button" class="ghb-btn" data-size="lg" x-bind:data-palette="palette" x-bind:data-flow="flow">
                <span class="ghb-label">Upgrade to the Pro plan</span>
            </button>
            <a class="ghb-btn" data-palette="ocean" href="/plans">
                <span class="ghb-label">See all plans</span>
            </a>
        </div>

        <style>
        .ghb { display: grid; gap: 1rem; justify-items: center; }
        .ghb-chips { display: flex; flex-wrap: wrap; gap: .5rem; justify-content: center; }
        .ghb-chip { padding: .4rem .9rem; border: 1px solid #e2e8f0; border-radius: 999px;
                    background: #fff; color: #0f172a; cursor: pointer; font-size: .8rem; }
        .ghb-chip[aria-pressed='true'] { border-color: #7c3aed; color: #7c3aed; }
        .ghb-switch { display: inline-flex; align-items: center; gap: .5rem; font-size: .85rem; color: #475569; }
        .ghb-btn {
            display: inline-flex; align-items: center; justify-content: center;
            padding: .7rem 1.6rem; border: 0; border-radius: 999px; cursor: pointer;
            color: #fff; font-weight: 600; font-size: .95rem; line-height: 1; text-decoration: none;
            --ghb-a: #7c3aed; --ghb-b: #db2777; --ghb-c: #f59e0b;
            background-image: linear-gradient(115deg, var(--ghb-a), var(--ghb-b) 45%, var(--ghb-c));
            background-size: 220% 220%; background-position: 12% 50%;
            transition: background-position .6s ease, box-shadow .35s ease;
        }
        .ghb-btn[data-palette='ocean'] { --ghb-a: #06b6d4; --ghb-b: #3b82f6; --ghb-c: #6366f1; }
        .ghb-btn[data-palette='meadow'] { --ghb-a: #10b981; --ghb-b: #84cc16; --ghb-c: #facc15; }
        .ghb-btn[data-size='lg'] { padding: .95rem 2.1rem; font-size: 1.1rem; }
        .ghb-btn:hover, .ghb-btn:focus-visible { background-position: 88% 50%; box-shadow: 0 12px 32px -12px var(--ghb-b); }
        .ghb-btn:focus-visible { outline: 2px solid var(--ghb-b); outline-offset: 3px; }
        .ghb-label { transition: translate .35s ease; }
        .ghb-btn:hover .ghb-label, .ghb-btn:focus-visible .ghb-label { translate: 2px -1px; }
        html[dir='rtl'] .ghb-btn:hover .ghb-label,
        html[dir='rtl'] .ghb-btn:focus-visible .ghb-label { translate: -2px -1px; }
        .ghb-btn[data-flow='true'] { animation: ghb-flow 3.2s ease-in-out infinite alternate; }
        @keyframes ghb-flow { from { background-position: 12% 50%; } to { background-position: 88% 50%; } }
        @media (prefers-reduced-motion: reduce) {
            .ghb-btn, .ghb-label { transition: none; }
            .ghb-btn[data-flow='true'] { animation: none; }
        }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Pricing.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import UpgradeRow from '@/components/UpgradeRow';

        export default function Pricing() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <UpgradeRow />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // UpgradeRow.jsx — the flowing gradient button: palette chips, sizes, link
        import { useState } from 'react';
        import '@nabuxai/ui-core/css';

        const PALETTES = {
          aurora: ['#7c3aed', '#db2777', '#f59e0b'],
          ocean: ['#06b6d4', '#3b82f6', '#6366f1'],
          meadow: ['#10b981', '#84cc16', '#facc15'],
        };

        export default function UpgradeRow() {
          const [palette, setPalette] = useState('aurora');
          const [flow, setFlow] = useState(false);
          const [a, b, c] = PALETTES[palette];

          return (
            <>
              <style>{`
                .ghb { display: grid; gap: 1rem; justify-items: center; }
                .ghb-btn { display: inline-flex; align-items: center; justify-content: center;
                           padding: .7rem 1.6rem; border: 0; border-radius: 999px; cursor: pointer;
                           color: #fff; font: 600 .95rem/1 system-ui; text-decoration: none;
                           background-image: linear-gradient(115deg, var(--ghb-a), var(--ghb-b) 45%, var(--ghb-c));
                           background-size: 220% 220%; background-position: 12% 50%;
                           transition: background-position .6s ease, box-shadow .35s ease; }
                .ghb-btn[data-size='lg'] { padding: .95rem 2.1rem; font-size: 1.1rem; }
                .ghb-btn:hover, .ghb-btn:focus-visible { background-position: 88% 50%;
                                                         box-shadow: 0 12px 32px -12px var(--ghb-b); }
                .ghb-btn:focus-visible { outline: 2px solid var(--ghb-b); outline-offset: 3px; }
                .ghb-label { transition: translate .35s ease; }
                .ghb-btn:hover .ghb-label, .ghb-btn:focus-visible .ghb-label { translate: 2px -1px; }
                .ghb-btn[data-flow='true'] { animation: ghb-flow 3.2s ease-in-out infinite alternate; }
                @keyframes ghb-flow { from { background-position: 12% 50%; } to { background-position: 88% 50%; } }
                @media (prefers-reduced-motion: reduce) {
                  .ghb-btn, .ghb-label { transition: none; }
                  .ghb-btn[data-flow='true'] { animation: none; }
                }
              `}</style>
              <div className="ghb" style={{ '--ghb-a': a, '--ghb-b': b, '--ghb-c': c }}>
                {Object.keys(PALETTES).map((name) => (
                  <button key={name} type="button" aria-pressed={palette === name} onClick={() => setPalette(name)}>
                    {name}
                  </button>
                ))}
                <label>
                  <input type="checkbox" checked={flow} onChange={(e) => setFlow(e.target.checked)} /> Keep it flowing
                </label>
                <button type="button" className="ghb-btn" data-size="lg" data-flow={flow}>
                  <span className="ghb-label">Upgrade to the Pro plan</span>
                </button>
                <a className="ghb-btn" href="/plans"><span className="ghb-label">See all plans</span></a>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- UpgradeRow.vue — the flowing gradient button: palette chips, sizes, link -->
        <script setup lang="ts">
        import { computed, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const PALETTES: Record<string, string[]> = {
          aurora: ['#7c3aed', '#db2777', '#f59e0b'],
          ocean: ['#06b6d4', '#3b82f6', '#6366f1'],
          meadow: ['#10b981', '#84cc16', '#facc15'],
        };
        const palette = ref<string>('aurora');
        const flow = ref(false);
        const stops = computed(() => PALETTES[palette.value]);
        </script>

        <template>
          <div class="ghb" :style="{ '--ghb-a': stops[0], '--ghb-b': stops[1], '--ghb-c': stops[2] }">
            <button v-for="(s, name) in PALETTES" :key="name" type="button"
                    :aria-pressed="palette === name" @click="palette = String(name)">
              {{ name }}
            </button>
            <label><input v-model="flow" type="checkbox" /> Keep it flowing</label>
            <button type="button" class="ghb-btn" data-size="lg" :data-flow="flow">
              <span class="ghb-label">Upgrade to the Pro plan</span>
            </button>
            <a class="ghb-btn" href="/plans"><span class="ghb-label">See all plans</span></a>
          </div>
        </template>

        <style scoped>
        .ghb { display: grid; gap: 1rem; justify-items: center; }
        .ghb-btn { display: inline-flex; align-items: center; justify-content: center;
                   padding: .7rem 1.6rem; border: 0; border-radius: 999px; cursor: pointer;
                   color: #fff; font: 600 .95rem/1 system-ui; text-decoration: none;
                   background-image: linear-gradient(115deg, var(--ghb-a), var(--ghb-b) 45%, var(--ghb-c));
                   background-size: 220% 220%; background-position: 12% 50%;
                   transition: background-position .6s ease, box-shadow .35s ease; }
        .ghb-btn[data-size='lg'] { padding: .95rem 2.1rem; font-size: 1.1rem; }
        .ghb-btn:hover, .ghb-btn:focus-visible { background-position: 88% 50%;
                                                 box-shadow: 0 12px 32px -12px var(--ghb-b); }
        .ghb-btn:focus-visible { outline: 2px solid var(--ghb-b); outline-offset: 3px; }
        .ghb-label { transition: translate .35s ease; }
        .ghb-btn:hover .ghb-label, .ghb-btn:focus-visible .ghb-label { translate: 2px -1px; }
        .ghb-btn[data-flow='true'] { animation: ghb-flow 3.2s ease-in-out infinite alternate; }
        @keyframes ghb-flow { from { background-position: 12% 50%; } to { background-position: 88% 50%; } }
        @media (prefers-reduced-motion: reduce) {
          .ghb-btn, .ghb-label { transition: none; }
          .ghb-btn[data-flow='true'] { animation: none; }
        }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- UpgradeRow.svelte — the flowing gradient button: palette chips, sizes, link -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const PALETTES: Record<string, string[]> = {
            aurora: ['#7c3aed', '#db2777', '#f59e0b'],
            ocean: ['#06b6d4', '#3b82f6', '#6366f1'],
            meadow: ['#10b981', '#84cc16', '#facc15'],
          };
          let palette = $state('aurora');
          let flow = $state(false);
          const stops = $derived(PALETTES[palette]);
        </script>

        <div class="ghb" style={`--ghb-a:${stops[0]};--ghb-b:${stops[1]};--ghb-c:${stops[2]}`}>
          {#each Object.entries(PALETTES) as [name] (name)}
            <button type="button" aria-pressed={palette === name} onclick={() => (palette = name)}>{name}</button>
          {/each}
          <label><input type="checkbox" bind:checked={flow} /> Keep it flowing</label>
          <button type="button" class="ghb-btn" data-size="lg" data-flow={flow}>
            <span class="ghb-label">Upgrade to the Pro plan</span>
          </button>
          <a class="ghb-btn" href="/plans"><span class="ghb-label">See all plans</span></a>
        </div>

        <style>
          .ghb { display: grid; gap: 1rem; justify-items: center; }
          .ghb-btn { display: inline-flex; align-items: center; justify-content: center;
                     padding: .7rem 1.6rem; border: 0; border-radius: 999px; cursor: pointer;
                     color: #fff; font: 600 .95rem/1 system-ui; text-decoration: none;
                     background-image: linear-gradient(115deg, var(--ghb-a), var(--ghb-b) 45%, var(--ghb-c));
                     background-size: 220% 220%; background-position: 12% 50%;
                     transition: background-position .6s ease, box-shadow .35s ease; }
          .ghb-btn[data-size='lg'] { padding: .95rem 2.1rem; font-size: 1.1rem; }
          .ghb-btn:hover, .ghb-btn:focus-visible { background-position: 88% 50%;
                                                   box-shadow: 0 12px 32px -12px var(--ghb-b); }
          .ghb-btn:focus-visible { outline: 2px solid var(--ghb-b); outline-offset: 3px; }
          .ghb-label { transition: translate .35s ease; }
          .ghb-btn:hover .ghb-label, .ghb-btn:focus-visible .ghb-label { translate: 2px -1px; }
          .ghb-btn[data-flow='true'] { animation: ghb-flow 3.2s ease-in-out infinite alternate; }
          @keyframes ghb-flow { from { background-position: 12% 50%; } to { background-position: 88% 50%; } }
          @media (prefers-reduced-motion: reduce) {
            .ghb-btn, .ghb-label { transition: none; }
            .ghb-btn[data-flow='true'] { animation: none; }
          }
        </style>
        SVELTE,
        ],
    ],

    'gradient-border-button' => [
        'title' => ['fa' => 'دکمهٔ حاشیهٔ گرادیانی', 'en' => 'Gradient border button'],
        'icon' => 'globe',
        'oneLiner' => [
            'fa' => 'حلقهٔ conic-gradient با چرخش لایهٔ زیرین دور دکمه می‌گردد و هاور چند برابر تندترش می‌کند؛ سطح داخلی شیشه‌ای است، ناحیهٔ انتخاب‌شده حلقه‌ای ضخیم‌تر می‌گیرد و یک دستگیرهٔ سرعت چرخش را می‌چیند.',
            'en' => 'A conic-gradient ring circling the button as the layer beneath it rotates — hover spins it several times faster; the inner surface is glass, the picked region earns a thicker ring, and a dial sets the spin speed.',
        ],
        'js' => true,
        'docs' => 'https://gradientbuttons.colorion.co',
        'props' => [
            ['name' => 'ring', 'type' => 'technique', 'default' => "'rotating ::before'", 'note' => [
                'fa' => 'لایهٔ conic با inset منفی زیر سطح شیشه‌ای می‌چرخد؛ چون از هر طرف بزرگ‌تر از دکمه است، گوشه‌ها هیچ‌وقت خالی نمی‌مانند — بدون @property، همه‌جا کار می‌کند.',
                'en' => 'The conic layer spins with a negative inset under the glass surface; oversized on every side, it never bares a corner — no @property, works everywhere.',
            ]],
            ['name' => '--gbb-speed', 'type' => 'duration', 'default' => "'4s'", 'note' => [
                'fa' => 'سرعت پایهٔ چرخش حلقه؛ هاور به ۱ ثانیه می‌شیند و دکمهٔ فعال به نصف.',
                'en' => 'The ring’s base spin; hover drops it to 1s and the pressed button to half.',
            ]],
            ['name' => 'surface', 'type' => 'glass', 'default' => "'blur(12px)'", 'note' => [
                'fa' => 'سطح داخلی نیمه‌شفاف با مات‌کردن پس‌زمینه؛ در تم تیره شیشه تیره‌تر و متن روشن می‌شود.',
                'en' => 'The translucent inner blurring whatever sits behind; dark mode swaps in darker glass and lighter ink.',
            ]],
            ['name' => 'shape', 'type' => 'string', 'default' => "'pill'", 'note' => [
                'fa' => 'pill یا rounded؛ سطح داخلی همیشه هم‌مرکزِ حلقه می‌ماند.',
                'en' => 'pill or rounded; the inner surface stays concentric with the ring.',
            ]],
            ['name' => 'aria-pressed', 'type' => 'state', 'default' => "'false'", 'note' => [
                'fa' => 'دکمهٔ فعال (انتخاب ناحیه) حلقه‌ای ضخیم‌تر می‌گیرد؛ هم برای چشم و هم برای صفحه‌خوان.',
                'en' => 'The active button (the picked region) earns a thicker ring — for the eye and the screen reader alike.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="gbb" x-data="{ speed: 4, region: 'eu-de-2', queued: null }"
            x-bind:style="'--gbb-speed:' + speed + 's'">
            <div class="gbb-dial">
                <label for="gbb-speed-input">سرعت چرخش</label>
                <input id="gbb-speed-input" type="range" min="1" max="6" step="0.5" x-model.number="speed">
                <output x-text="speed.toLocaleString('fa-IR') + ' ثانیه'"></output>
            </div>
            <button type="button" class="gbb-btn" data-size="lg" x-on:click="queued = region">
                <span class="gbb-inner">انتشار اپ لبه</span>
            </button>
            <div class="gbb-regions" role="group" aria-label="ناحیهٔ استقرار">
                <button type="button" class="gbb-btn" x-bind:aria-pressed="region === 'eu-de-2'"
                    x-on:click="region = 'eu-de-2'"><span class="gbb-inner">فرانکفورت <small>· eu-de-2</small></span></button>
                <button type="button" class="gbb-btn" x-bind:aria-pressed="region === 'eu-ie-1'"
                    x-on:click="region = 'eu-ie-1'"><span class="gbb-inner">دوبلین <small>· eu-ie-1</small></span></button>
                <button type="button" class="gbb-btn" x-bind:aria-pressed="region === 'ap-se-1'"
                    x-on:click="region = 'ap-se-1'"><span class="gbb-inner">سنگاپور <small>· ap-se-1</small></span></button>
            </div>
            <p class="gbb-status" role="status" x-show="queued" x-cloak>
                در صف <span x-text="queued"></span> قرار گرفت — تا ۹۰ ثانیه دیگر زنده است.
            </p>
        </div>

        <style>
        .gbb { --gbb-speed: 4s; --gbb-ink: #0f172a; --gbb-glass: rgba(255, 255, 255, .78);
               display: grid; gap: 1rem; justify-items: center; }
        .gbb-btn { position: relative; overflow: hidden; padding: 1.5px; border: 0; border-radius: 999px;
                   background: transparent; cursor: pointer; }
        .gbb-btn::before { content: ''; position: absolute; inset: -60%;
                   background: conic-gradient(from 0deg, #f472b6, #a78bfa, #38bdf8, #34d399, #fbbf24, #f472b6);
                   animation: gbb-spin var(--gbb-speed) linear infinite; }
        .gbb-inner { position: relative; display: inline-flex; align-items: center; gap: .5rem;
                   padding: .65rem 1.4rem; border-radius: inherit; background: var(--gbb-glass);
                   backdrop-filter: blur(12px); color: var(--gbb-ink);
                   font-weight: 600; font-size: .92rem; line-height: 1.4;
                   transition: translate .25s ease; }
        .gbb-inner small { font-weight: 400; opacity: .65; }
        .gbb-btn:hover::before, .gbb-btn:focus-visible::before { animation-duration: 1s; }
        .gbb-btn:hover .gbb-inner { translate: 0 -1px; }
        .gbb-btn[data-size='lg'] .gbb-inner { padding: .9rem 1.9rem; font-size: 1.05rem; }
        .gbb-btn[data-shape='rounded'] { border-radius: 14px; }
        .gbb-btn[aria-pressed='true'] { padding: 2.5px; }
        .gbb-btn:focus-visible .gbb-inner { outline: 2px solid #a78bfa; outline-offset: 2px; }
        .gbb-regions { display: flex; flex-wrap: wrap; gap: .75rem 1rem; justify-content: center; }
        .gbb-dial { display: flex; flex-wrap: wrap; align-items: center; gap: .75rem;
                    font-size: .85rem; color: #475569; }
        .gbb-dial input[type='range'] { accent-color: #a78bfa; inline-size: 11rem; }
        .gbb-status { margin: 0; font-size: .85rem; color: #475569; }
        @keyframes gbb-spin { to { rotate: 360deg; } }
        @media (prefers-reduced-motion: reduce) { .gbb-btn::before { animation: none; } }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="gbb" x-data="{ speed: 4, region: 'eu-de-2', queued: null }"
            x-bind:style="'--gbb-speed:' + speed + 's'">
            <div class="gbb-dial">
                <label for="gbb-speed-input">Ring speed</label>
                <input id="gbb-speed-input" type="range" min="1" max="6" step="0.5" x-model.number="speed">
                <output x-text="speed + ' s'"></output>
            </div>
            <button type="button" class="gbb-btn" data-size="lg" x-on:click="queued = region">
                <span class="gbb-inner">Deploy the edge app</span>
            </button>
            <div class="gbb-regions" role="group" aria-label="Deploy region">
                <button type="button" class="gbb-btn" x-bind:aria-pressed="region === 'eu-de-2'"
                    x-on:click="region = 'eu-de-2'"><span class="gbb-inner">Frankfurt <small>· eu-de-2</small></span></button>
                <button type="button" class="gbb-btn" x-bind:aria-pressed="region === 'eu-ie-1'"
                    x-on:click="region = 'eu-ie-1'"><span class="gbb-inner">Dublin <small>· eu-ie-1</small></span></button>
                <button type="button" class="gbb-btn" x-bind:aria-pressed="region === 'ap-se-1'"
                    x-on:click="region = 'ap-se-1'"><span class="gbb-inner">Singapore <small>· ap-se-1</small></span></button>
            </div>
            <p class="gbb-status" role="status" x-show="queued" x-cloak>
                Queued in <span x-text="queued"></span> — live in about 90 seconds.
            </p>
        </div>

        <style>
        .gbb { --gbb-speed: 4s; --gbb-ink: #0f172a; --gbb-glass: rgba(255, 255, 255, .78);
               display: grid; gap: 1rem; justify-items: center; }
        .gbb-btn { position: relative; overflow: hidden; padding: 1.5px; border: 0; border-radius: 999px;
                   background: transparent; cursor: pointer; }
        .gbb-btn::before { content: ''; position: absolute; inset: -60%;
                   background: conic-gradient(from 0deg, #f472b6, #a78bfa, #38bdf8, #34d399, #fbbf24, #f472b6);
                   animation: gbb-spin var(--gbb-speed) linear infinite; }
        .gbb-inner { position: relative; display: inline-flex; align-items: center; gap: .5rem;
                   padding: .65rem 1.4rem; border-radius: inherit; background: var(--gbb-glass);
                   backdrop-filter: blur(12px); color: var(--gbb-ink);
                   font-weight: 600; font-size: .92rem; line-height: 1.4;
                   transition: translate .25s ease; }
        .gbb-inner small { font-weight: 400; opacity: .65; }
        .gbb-btn:hover::before, .gbb-btn:focus-visible::before { animation-duration: 1s; }
        .gbb-btn:hover .gbb-inner { translate: 0 -1px; }
        .gbb-btn[data-size='lg'] .gbb-inner { padding: .9rem 1.9rem; font-size: 1.05rem; }
        .gbb-btn[data-shape='rounded'] { border-radius: 14px; }
        .gbb-btn[aria-pressed='true'] { padding: 2.5px; }
        .gbb-btn:focus-visible .gbb-inner { outline: 2px solid #a78bfa; outline-offset: 2px; }
        .gbb-regions { display: flex; flex-wrap: wrap; gap: .75rem 1rem; justify-content: center; }
        .gbb-dial { display: flex; flex-wrap: wrap; align-items: center; gap: .75rem;
                    font-size: .85rem; color: #475569; }
        .gbb-dial input[type='range'] { accent-color: #a78bfa; inline-size: 11rem; }
        .gbb-status { margin: 0; font-size: .85rem; color: #475569; }
        @keyframes gbb-spin { to { rotate: 360deg; } }
        @media (prefers-reduced-motion: reduce) { .gbb-btn::before { animation: none; } }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Edge.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import EdgeConsole from '@/components/EdgeConsole';

        export default function Edge() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <EdgeConsole />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // EdgeConsole.jsx — the gradient border button: rotating conic ring,
        // glass surface, region picking and a spin-speed dial
        import { useState } from 'react';
        import '@nabuxai/ui-core/css';

        const REGIONS = [
          { id: 'eu-de-2', city: 'Frankfurt' },
          { id: 'eu-ie-1', city: 'Dublin' },
          { id: 'ap-se-1', city: 'Singapore' },
        ];

        export default function EdgeConsole() {
          const [speed, setSpeed] = useState(4);
          const [region, setRegion] = useState('eu-de-2');
          const [queued, setQueued] = useState(null);

          return (
            <>
              <style>{`
                .gbb { display: grid; gap: 1rem; justify-items: center;
                       --gbb-ink: #0f172a; --gbb-glass: rgba(255, 255, 255, .78); }
                .gbb-btn { position: relative; overflow: hidden; padding: 1.5px; border: 0;
                           border-radius: 999px; background: transparent; cursor: pointer; }
                .gbb-btn::before { content: ''; position: absolute; inset: -60%;
                           background: conic-gradient(from 0deg, #f472b6, #a78bfa, #38bdf8, #34d399, #fbbf24, #f472b6);
                           animation: gbb-spin var(--gbb-speed) linear infinite; }
                .gbb-inner { position: relative; display: inline-flex; align-items: center; gap: .5rem;
                           padding: .65rem 1.4rem; border-radius: inherit; background: var(--gbb-glass);
                           backdrop-filter: blur(12px); color: var(--gbb-ink);
                           font: 600 .92rem/1.4 system-ui; transition: translate .25s ease; }
                .gbb-inner small { font-weight: 400; opacity: .65; }
                .gbb-btn:hover::before, .gbb-btn:focus-visible::before { animation-duration: 1s; }
                .gbb-btn:hover .gbb-inner { translate: 0 -1px; }
                .gbb-btn[data-size='lg'] .gbb-inner { padding: .9rem 1.9rem; font-size: 1.05rem; }
                .gbb-btn[aria-pressed='true'] { padding: 2.5px; }
                @keyframes gbb-spin { to { rotate: 360deg; } }
                @media (prefers-reduced-motion: reduce) { .gbb-btn::before { animation: none; } }
              `}</style>
              <div className="gbb" style={{ '--gbb-speed': `${speed}s` }}>
                <div className="gbb-dial">
                  <label htmlFor="gbb-speed">Ring speed</label>
                  <input id="gbb-speed" type="range" min="1" max="6" step="0.5" value={speed}
                         onChange={(e) => setSpeed(Number(e.target.value))} />
                  <output>{speed} s</output>
                </div>
                <button type="button" className="gbb-btn" data-size="lg" onClick={() => setQueued(region)}>
                  <span className="gbb-inner">Deploy the edge app</span>
                </button>
                <div className="gbb-regions" role="group" aria-label="Deploy region">
                  {REGIONS.map((r) => (
                    <button key={r.id} type="button" className="gbb-btn"
                            aria-pressed={region === r.id} onClick={() => setRegion(r.id)}>
                      <span className="gbb-inner">{r.city} <small>· {r.id}</small></span>
                    </button>
                  ))}
                </div>
                {queued && (
                  <p className="gbb-status" role="status">Queued in {queued} — live in about 90 seconds.</p>
                )}
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- EdgeConsole.vue — the gradient border button: rotating conic ring,
             glass surface, region picking and a spin-speed dial -->
        <script setup lang="ts">
        import { ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const REGIONS = [
          { id: 'eu-de-2', city: 'Frankfurt' },
          { id: 'eu-ie-1', city: 'Dublin' },
          { id: 'ap-se-1', city: 'Singapore' },
        ];
        const speed = ref(4);
        const region = ref('eu-de-2');
        const queued = ref<string | null>(null);
        </script>

        <template>
          <div class="gbb" :style="{ '--gbb-speed': speed + 's' }">
            <div class="gbb-dial">
              <label for="gbb-speed">Ring speed</label>
              <input id="gbb-speed" type="range" min="1" max="6" step="0.5" v-model.number="speed" />
              <output>{{ speed }} s</output>
            </div>
            <button type="button" class="gbb-btn" data-size="lg" @click="queued = region">
              <span class="gbb-inner">Deploy the edge app</span>
            </button>
            <div class="gbb-regions" role="group" aria-label="Deploy region">
              <button v-for="r in REGIONS" :key="r.id" type="button" class="gbb-btn"
                      :aria-pressed="region === r.id" @click="region = r.id">
                <span class="gbb-inner">{{ r.city }} <small>· {{ r.id }}</small></span>
              </button>
            </div>
            <p v-if="queued" class="gbb-status" role="status">
              Queued in {{ queued }} — live in about 90 seconds.
            </p>
          </div>
        </template>

        <style scoped>
        .gbb { display: grid; gap: 1rem; justify-items: center;
               --gbb-ink: #0f172a; --gbb-glass: rgba(255, 255, 255, .78); }
        .gbb-btn { position: relative; overflow: hidden; padding: 1.5px; border: 0;
                   border-radius: 999px; background: transparent; cursor: pointer; }
        .gbb-btn::before { content: ''; position: absolute; inset: -60%;
                   background: conic-gradient(from 0deg, #f472b6, #a78bfa, #38bdf8, #34d399, #fbbf24, #f472b6);
                   animation: gbb-spin var(--gbb-speed) linear infinite; }
        .gbb-inner { position: relative; display: inline-flex; align-items: center; gap: .5rem;
                   padding: .65rem 1.4rem; border-radius: inherit; background: var(--gbb-glass);
                   backdrop-filter: blur(12px); color: var(--gbb-ink);
                   font: 600 .92rem/1.4 system-ui; transition: translate .25s ease; }
        .gbb-inner small { font-weight: 400; opacity: .65; }
        .gbb-btn:hover::before, .gbb-btn:focus-visible::before { animation-duration: 1s; }
        .gbb-btn:hover .gbb-inner { translate: 0 -1px; }
        .gbb-btn[data-size='lg'] .gbb-inner { padding: .9rem 1.9rem; font-size: 1.05rem; }
        .gbb-btn[aria-pressed='true'] { padding: 2.5px; }
        @keyframes gbb-spin { to { rotate: 360deg; } }
        @media (prefers-reduced-motion: reduce) { .gbb-btn::before { animation: none; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- EdgeConsole.svelte — the gradient border button: rotating conic ring,
             glass surface, region picking and a spin-speed dial -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const REGIONS = [
            { id: 'eu-de-2', city: 'Frankfurt' },
            { id: 'eu-ie-1', city: 'Dublin' },
            { id: 'ap-se-1', city: 'Singapore' },
          ];
          let speed = $state(4);
          let region = $state('eu-de-2');
          let queued = $state<string | null>(null);
        </script>

        <div class="gbb" style={`--gbb-speed:${speed}s`}>
          <div class="gbb-dial">
            <label for="gbb-speed">Ring speed</label>
            <input id="gbb-speed" type="range" min="1" max="6" step="0.5" bind:value={speed} />
            <output>{speed} s</output>
          </div>
          <button type="button" class="gbb-btn" data-size="lg" onclick={() => (queued = region)}>
            <span class="gbb-inner">Deploy the edge app</span>
          </button>
          <div class="gbb-regions" role="group" aria-label="Deploy region">
            {#each REGIONS as r (r.id)}
              <button type="button" class="gbb-btn" aria-pressed={region === r.id}
                      onclick={() => (region = r.id)}>
                <span class="gbb-inner">{r.city} <small>· {r.id}</small></span>
              </button>
            {/each}
          </div>
          {#if queued}
            <p class="gbb-status" role="status">Queued in {queued} — live in about 90 seconds.</p>
          {/if}
        </div>

        <style>
          .gbb { display: grid; gap: 1rem; justify-items: center;
                 --gbb-ink: #0f172a; --gbb-glass: rgba(255, 255, 255, .78); }
          .gbb-btn { position: relative; overflow: hidden; padding: 1.5px; border: 0;
                     border-radius: 999px; background: transparent; cursor: pointer; }
          .gbb-btn::before { content: ''; position: absolute; inset: -60%;
                     background: conic-gradient(from 0deg, #f472b6, #a78bfa, #38bdf8, #34d399, #fbbf24, #f472b6);
                     animation: gbb-spin var(--gbb-speed) linear infinite; }
          .gbb-inner { position: relative; display: inline-flex; align-items: center; gap: .5rem;
                     padding: .65rem 1.4rem; border-radius: inherit; background: var(--gbb-glass);
                     backdrop-filter: blur(12px); color: var(--gbb-ink);
                     font: 600 .92rem/1.4 system-ui; transition: translate .25s ease; }
          .gbb-inner small { font-weight: 400; opacity: .65; }
          .gbb-btn:hover::before, .gbb-btn:focus-visible::before { animation-duration: 1s; }
          .gbb-btn:hover .gbb-inner { translate: 0 -1px; }
          .gbb-btn[data-size='lg'] .gbb-inner { padding: .9rem 1.9rem; font-size: 1.05rem; }
          .gbb-btn[aria-pressed='true'] { padding: 2.5px; }
          @keyframes gbb-spin { to { rotate: 360deg; } }
          @media (prefers-reduced-motion: reduce) { .gbb-btn::before { animation: none; } }
        </style>
        SVELTE,
        ],
    ],

    'gradient-glow-button' => [
        'title' => ['fa' => 'دکمهٔ هالهٔ گرادیانی', 'en' => 'Gradient glow button'],
        'icon' => 'zap',
        'oneLiner' => [
            'fa' => 'هالهٔ گرادیانیِ تار دور دکمه نفس می‌کشد، هاور جاروی نوری از سطحش می‌گذراند و فشردن روی فنرِ اورشوت می‌نشیند — با نسخهٔ CTA درشت برای لحظهٔ تصمیم و شمارندهٔ زندهٔ رزرو.',
            'en' => 'A blurred gradient halo breathes around the button, hover sweeps a band of light across its face and the press lands on an overshoot spring — with the big CTA cut for the moment of decision and a live reservation counter.',
        ],
        'js' => true,
        'docs' => 'https://gradientbuttons.colorion.co',
        'props' => [
            ['name' => 'halo', 'type' => 'layer', 'default' => "'blur(20px) · 2.6s'", 'note' => [
                'fa' => 'هاله گرادیان دکمه را به ارث می‌برد و پشت تاری با نبضِ scale و opacity نفس می‌کشد؛ در تم تیره کمی پررنگ‌تر می‌شود.',
                'en' => 'The halo inherits the button’s gradient and breathes behind a blur, pulsing scale and opacity; slightly stronger in dark mode.',
            ]],
            ['name' => 'sweep', 'type' => 'event', 'default' => "'hover · .9s'", 'note' => [
                'fa' => 'نوار نور مورب هنگام هاور یا فوکوس از روی سطح می‌گذرد؛ در راست‌به‌چپ از سوی مقابل.',
                'en' => 'A skewed band of light crosses the face on hover or focus; from the opposite side in RTL.',
            ]],
            ['name' => 'press', 'type' => 'spring', 'default' => "'cubic-bezier(.34,1.56,.64,1)'", 'note' => [
                'fa' => 'فشردن دکمه را به ۰٫۹۳ می‌فشارد و فنرِ اورشوت برمی‌گرداند — حس فیزیکیِ کلیک.',
                'en' => 'Pressing squeezes the button to .93 and the overshoot spring returns it — a physical click.',
            ]],
            ['name' => 'size', 'type' => 'string', 'default' => "'md'", 'note' => [
                'fa' => 'md برای ردیف‌ها و نوار ابزار؛ cta درشتِ هیرو با هالهٔ پهن‌تر.',
                'en' => 'md for rows and toolbars; cta is the big hero cut with a wider halo.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="ggb" x-data="{ count: 0, locale: 'fa-IR' }">
            <span class="ggb-wrap">
                <span class="ggb-halo" aria-hidden="true"></span>
                <button type="button" class="ggb-btn" data-size="cta" x-on:click="count++">
                    <span class="ggb-label">جای خودت را رزرو کن</span>
                </button>
            </span>
            <p class="ggb-count" role="status">
                <span x-show="count === 0">ظرفیت دسته ۲۵۰ نفر است — سنگاپور، ۲۱ ژانویه.</span>
                <span x-show="count > 0" x-cloak>
                    شما نفر <b x-text="count.toLocaleString(locale)"></b>‌ام این دسته هستید.
                </span>
            </p>
            <div class="ggb-row">
                <span class="ggb-wrap">
                    <span class="ggb-halo" aria-hidden="true"></span>
                    <button type="button" class="ggb-btn" x-on:click="count++">شروع رایگان</button>
                </span>
                <span class="ggb-wrap">
                    <span class="ggb-halo" aria-hidden="true"></span>
                    <button type="button" class="ggb-btn" x-on:click="count++">گفت‌وگو با فروش</button>
                </span>
            </div>
        </div>

        <style>
        .ggb { --ggb-a: #6366f1; --ggb-b: #a855f7; --ggb-c: #ec4899; --ggb-halo-op: .45;
               display: grid; gap: 1rem; justify-items: center; }
        .ggb-row { display: flex; flex-wrap: wrap; gap: 1rem 1.5rem; justify-content: center; }
        .ggb-wrap { position: relative; display: inline-block; isolation: isolate; }
        .ggb-halo { position: absolute; inset: -8px -14px; z-index: -1; border-radius: 999px;
               background: linear-gradient(135deg, var(--ggb-a), var(--ggb-b) 50%, var(--ggb-c));
               filter: blur(20px); opacity: var(--ggb-halo-op);
               animation: ggb-pulse 2.6s ease-in-out infinite; }
        .ggb-btn { position: relative; overflow: hidden; border: 0; border-radius: 999px; cursor: pointer;
               padding: .75rem 1.7rem; color: #fff; font-weight: 700; font-size: .95rem; line-height: 1.4;
               background: linear-gradient(135deg, var(--ggb-a), var(--ggb-b) 50%, var(--ggb-c));
               transition: scale .45s cubic-bezier(.34, 1.56, .64, 1); }
        .ggb-btn::after { content: ''; position: absolute; inset: 0; border-radius: inherit; pointer-events: none;
               background: linear-gradient(105deg, transparent 42%, rgba(255, 255, 255, .5) 50%, transparent 58%);
               translate: -130% 0; }
        .ggb-btn:hover::after, .ggb-btn:focus-visible::after { animation: ggb-sweep .9s ease; }
        .ggb-btn:hover, .ggb-btn:focus-visible { scale: 1.03; }
        .ggb-btn:active { scale: .93; }
        .ggb-btn:focus-visible { outline: 2px solid #e9d5ff; outline-offset: 4px; }
        .ggb-btn[data-size='cta'] { padding: 1.1rem 2.6rem; font-size: 1.25rem; }
        .ggb-wrap:has(.ggb-btn[data-size='cta']) .ggb-halo { inset: -12px -20px; filter: blur(26px); }
        html[dir='rtl'] .ggb-btn::after { translate: 130% 0; }
        html[dir='rtl'] .ggb-btn:hover::after,
        html[dir='rtl'] .ggb-btn:focus-visible::after { animation-name: ggb-sweep-rtl; }
        @keyframes ggb-pulse { 0%, 100% { opacity: .32; scale: .97; } 50% { opacity: .62; scale: 1.05; } }
        @keyframes ggb-sweep { to { translate: 130% 0; } }
        @keyframes ggb-sweep-rtl { to { translate: -130% 0; } }
        @media (prefers-reduced-motion: reduce) {
            .ggb-halo { animation: none; }
            .ggb-btn::after { display: none; }
            .ggb-btn { transition: none; }
        }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="ggb" x-data="{ count: 0, locale: 'en-US' }">
            <span class="ggb-wrap">
                <span class="ggb-halo" aria-hidden="true"></span>
                <button type="button" class="ggb-btn" data-size="cta" x-on:click="count++">
                    <span class="ggb-label">Reserve your seat</span>
                </button>
            </span>
            <p class="ggb-count" role="status">
                <span x-show="count === 0">The batch caps at 250 — Singapore, January 21.</span>
                <span x-show="count > 0" x-cloak>
                    You are number <b x-text="count.toLocaleString(locale)"></b> in this batch.
                </span>
            </p>
            <div class="ggb-row">
                <span class="ggb-wrap">
                    <span class="ggb-halo" aria-hidden="true"></span>
                    <button type="button" class="ggb-btn" x-on:click="count++">Start free</button>
                </span>
                <span class="ggb-wrap">
                    <span class="ggb-halo" aria-hidden="true"></span>
                    <button type="button" class="ggb-btn" x-on:click="count++">Talk to sales</button>
                </span>
            </div>
        </div>

        <style>
        .ggb { --ggb-a: #6366f1; --ggb-b: #a855f7; --ggb-c: #ec4899; --ggb-halo-op: .45;
               display: grid; gap: 1rem; justify-items: center; }
        .ggb-row { display: flex; flex-wrap: wrap; gap: 1rem 1.5rem; justify-content: center; }
        .ggb-wrap { position: relative; display: inline-block; isolation: isolate; }
        .ggb-halo { position: absolute; inset: -8px -14px; z-index: -1; border-radius: 999px;
               background: linear-gradient(135deg, var(--ggb-a), var(--ggb-b) 50%, var(--ggb-c));
               filter: blur(20px); opacity: var(--ggb-halo-op);
               animation: ggb-pulse 2.6s ease-in-out infinite; }
        .ggb-btn { position: relative; overflow: hidden; border: 0; border-radius: 999px; cursor: pointer;
               padding: .75rem 1.7rem; color: #fff; font-weight: 700; font-size: .95rem; line-height: 1.4;
               background: linear-gradient(135deg, var(--ggb-a), var(--ggb-b) 50%, var(--ggb-c));
               transition: scale .45s cubic-bezier(.34, 1.56, .64, 1); }
        .ggb-btn::after { content: ''; position: absolute; inset: 0; border-radius: inherit; pointer-events: none;
               background: linear-gradient(105deg, transparent 42%, rgba(255, 255, 255, .5) 50%, transparent 58%);
               translate: -130% 0; }
        .ggb-btn:hover::after, .ggb-btn:focus-visible::after { animation: ggb-sweep .9s ease; }
        .ggb-btn:hover, .ggb-btn:focus-visible { scale: 1.03; }
        .ggb-btn:active { scale: .93; }
        .ggb-btn:focus-visible { outline: 2px solid #e9d5ff; outline-offset: 4px; }
        .ggb-btn[data-size='cta'] { padding: 1.1rem 2.6rem; font-size: 1.25rem; }
        .ggb-wrap:has(.ggb-btn[data-size='cta']) .ggb-halo { inset: -12px -20px; filter: blur(26px); }
        html[dir='rtl'] .ggb-btn::after { translate: 130% 0; }
        html[dir='rtl'] .ggb-btn:hover::after,
        html[dir='rtl'] .ggb-btn:focus-visible::after { animation-name: ggb-sweep-rtl; }
        @keyframes ggb-pulse { 0%, 100% { opacity: .32; scale: .97; } 50% { opacity: .62; scale: 1.05; } }
        @keyframes ggb-sweep { to { translate: 130% 0; } }
        @keyframes ggb-sweep-rtl { to { translate: -130% 0; } }
        @media (prefers-reduced-motion: reduce) {
            .ggb-halo { animation: none; }
            .ggb-btn::after { display: none; }
            .ggb-btn { transition: none; }
        }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Launch.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import ReserveCta from '@/components/ReserveCta';

        export default function Launch() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <ReserveCta />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // ReserveCta.jsx — the gradient glow button: pulsing halo, hover sweep,
        // springy press and a live reservation counter
        import { useState } from 'react';
        import '@nabuxai/ui-core/css';

        export default function ReserveCta() {
          const [count, setCount] = useState(0);

          return (
            <>
              <style>{`
                .ggb { display: grid; gap: 1rem; justify-items: center;
                       --ggb-a: #6366f1; --ggb-b: #a855f7; --ggb-c: #ec4899; --ggb-halo-op: .45; }
                .ggb-row { display: flex; flex-wrap: wrap; gap: 1rem 1.5rem; justify-content: center; }
                .ggb-wrap { position: relative; display: inline-block; isolation: isolate; }
                .ggb-halo { position: absolute; inset: -8px -14px; z-index: -1; border-radius: 999px;
                       background: linear-gradient(135deg, var(--ggb-a), var(--ggb-b) 50%, var(--ggb-c));
                       filter: blur(20px); opacity: var(--ggb-halo-op);
                       animation: ggb-pulse 2.6s ease-in-out infinite; }
                .ggb-btn { position: relative; overflow: hidden; border: 0; border-radius: 999px; cursor: pointer;
                       padding: .75rem 1.7rem; color: #fff; font: 700 .95rem/1.4 system-ui;
                       background: linear-gradient(135deg, var(--ggb-a), var(--ggb-b) 50%, var(--ggb-c));
                       transition: scale .45s cubic-bezier(.34, 1.56, .64, 1); }
                .ggb-btn::after { content: ''; position: absolute; inset: 0; border-radius: inherit; pointer-events: none;
                       background: linear-gradient(105deg, transparent 42%, rgba(255, 255, 255, .5) 50%, transparent 58%);
                       translate: -130% 0; }
                .ggb-btn:hover::after, .ggb-btn:focus-visible::after { animation: ggb-sweep .9s ease; }
                .ggb-btn:hover, .ggb-btn:focus-visible { scale: 1.03; }
                .ggb-btn:active { scale: .93; }
                .ggb-btn[data-size='cta'] { padding: 1.1rem 2.6rem; font-size: 1.25rem; }
                .ggb-wrap:has(.ggb-btn[data-size='cta']) .ggb-halo { inset: -12px -20px; filter: blur(26px); }
                @keyframes ggb-pulse { 0%, 100% { opacity: .32; scale: .97; } 50% { opacity: .62; scale: 1.05; } }
                @keyframes ggb-sweep { to { translate: 130% 0; } }
                @media (prefers-reduced-motion: reduce) {
                  .ggb-halo { animation: none; }
                  .ggb-btn::after { display: none; }
                  .ggb-btn { transition: none; }
                }
              `}</style>
              <div className="ggb">
                <span className="ggb-wrap">
                  <span className="ggb-halo" aria-hidden="true" />
                  <button type="button" className="ggb-btn" data-size="cta" onClick={() => setCount(count + 1)}>
                    <span className="ggb-label">Reserve your seat</span>
                  </button>
                </span>
                <p className="ggb-count" role="status">
                  {count === 0
                    ? 'The batch caps at 250 — Singapore, January 21.'
                    : <>You are number <b>{count.toLocaleString('en-US')}</b> in this batch.</>}
                </p>
                <div className="ggb-row">
                  {['Start free', 'Talk to sales'].map((label) => (
                    <span key={label} className="ggb-wrap">
                      <span className="ggb-halo" aria-hidden="true" />
                      <button type="button" className="ggb-btn" onClick={() => setCount(count + 1)}>{label}</button>
                    </span>
                  ))}
                </div>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- ReserveCta.vue — the gradient glow button: pulsing halo, hover sweep,
             springy press and a live reservation counter -->
        <script setup lang="ts">
        import { ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const count = ref(0);
        </script>

        <template>
          <div class="ggb">
            <span class="ggb-wrap">
              <span class="ggb-halo" aria-hidden="true"></span>
              <button type="button" class="ggb-btn" data-size="cta" @click="count++">
                <span class="ggb-label">Reserve your seat</span>
              </button>
            </span>
            <p class="ggb-count" role="status">
              <template v-if="count === 0">The batch caps at 250 — Singapore, January 21.</template>
              <template v-else>You are number <b>{{ count.toLocaleString('en-US') }}</b> in this batch.</template>
            </p>
            <div class="ggb-row">
              <span v-for="label in ['Start free', 'Talk to sales']" :key="label" class="ggb-wrap">
                <span class="ggb-halo" aria-hidden="true"></span>
                <button type="button" class="ggb-btn" @click="count++">{{ label }}</button>
              </span>
            </div>
          </div>
        </template>

        <style scoped>
        .ggb { display: grid; gap: 1rem; justify-items: center;
               --ggb-a: #6366f1; --ggb-b: #a855f7; --ggb-c: #ec4899; --ggb-halo-op: .45; }
        .ggb-row { display: flex; flex-wrap: wrap; gap: 1rem 1.5rem; justify-content: center; }
        .ggb-wrap { position: relative; display: inline-block; isolation: isolate; }
        .ggb-halo { position: absolute; inset: -8px -14px; z-index: -1; border-radius: 999px;
               background: linear-gradient(135deg, var(--ggb-a), var(--ggb-b) 50%, var(--ggb-c));
               filter: blur(20px); opacity: var(--ggb-halo-op);
               animation: ggb-pulse 2.6s ease-in-out infinite; }
        .ggb-btn { position: relative; overflow: hidden; border: 0; border-radius: 999px; cursor: pointer;
               padding: .75rem 1.7rem; color: #fff; font: 700 .95rem/1.4 system-ui;
               background: linear-gradient(135deg, var(--ggb-a), var(--ggb-b) 50%, var(--ggb-c));
               transition: scale .45s cubic-bezier(.34, 1.56, .64, 1); }
        .ggb-btn::after { content: ''; position: absolute; inset: 0; border-radius: inherit; pointer-events: none;
               background: linear-gradient(105deg, transparent 42%, rgba(255, 255, 255, .5) 50%, transparent 58%);
               translate: -130% 0; }
        .ggb-btn:hover::after, .ggb-btn:focus-visible::after { animation: ggb-sweep .9s ease; }
        .ggb-btn:hover, .ggb-btn:focus-visible { scale: 1.03; }
        .ggb-btn:active { scale: .93; }
        .ggb-btn[data-size='cta'] { padding: 1.1rem 2.6rem; font-size: 1.25rem; }
        .ggb-wrap:has(.ggb-btn[data-size='cta']) .ggb-halo { inset: -12px -20px; filter: blur(26px); }
        @keyframes ggb-pulse { 0%, 100% { opacity: .32; scale: .97; } 50% { opacity: .62; scale: 1.05; } }
        @keyframes ggb-sweep { to { translate: 130% 0; } }
        @media (prefers-reduced-motion: reduce) {
          .ggb-halo { animation: none; }
          .ggb-btn::after { display: none; }
          .ggb-btn { transition: none; }
        }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- ReserveCta.svelte — the gradient glow button: pulsing halo, hover
             sweep, springy press and a live reservation counter -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const labels = ['Start free', 'Talk to sales'];
          let count = $state(0);
        </script>

        <div class="ggb">
          <span class="ggb-wrap">
            <span class="ggb-halo" aria-hidden="true"></span>
            <button type="button" class="ggb-btn" data-size="cta" onclick={() => count++}>
              <span class="ggb-label">Reserve your seat</span>
            </button>
          </span>
          <p class="ggb-count" role="status">
            {#if count === 0}
              The batch caps at 250 — Singapore, January 21.
            {:else}
              You are number <b>{count.toLocaleString('en-US')}</b> in this batch.
            {/if}
          </p>
          <div class="ggb-row">
            {#each labels as label (label)}
              <span class="ggb-wrap">
                <span class="ggb-halo" aria-hidden="true"></span>
                <button type="button" class="ggb-btn" onclick={() => count++}>{label}</button>
              </span>
            {/each}
          </div>
        </div>

        <style>
          .ggb { display: grid; gap: 1rem; justify-items: center;
                 --ggb-a: #6366f1; --ggb-b: #a855f7; --ggb-c: #ec4899; --ggb-halo-op: .45; }
          .ggb-row { display: flex; flex-wrap: wrap; gap: 1rem 1.5rem; justify-content: center; }
          .ggb-wrap { position: relative; display: inline-block; isolation: isolate; }
          .ggb-halo { position: absolute; inset: -8px -14px; z-index: -1; border-radius: 999px;
                 background: linear-gradient(135deg, var(--ggb-a), var(--ggb-b) 50%, var(--ggb-c));
                 filter: blur(20px); opacity: var(--ggb-halo-op);
                 animation: ggb-pulse 2.6s ease-in-out infinite; }
          .ggb-btn { position: relative; overflow: hidden; border: 0; border-radius: 999px; cursor: pointer;
                 padding: .75rem 1.7rem; color: #fff; font: 700 .95rem/1.4 system-ui;
                 background: linear-gradient(135deg, var(--ggb-a), var(--ggb-b) 50%, var(--ggb-c));
                 transition: scale .45s cubic-bezier(.34, 1.56, .64, 1); }
          .ggb-btn::after { content: ''; position: absolute; inset: 0; border-radius: inherit; pointer-events: none;
                 background: linear-gradient(105deg, transparent 42%, rgba(255, 255, 255, .5) 50%, transparent 58%);
                 translate: -130% 0; }
          .ggb-btn:hover::after, .ggb-btn:focus-visible::after { animation: ggb-sweep .9s ease; }
          .ggb-btn:hover, .ggb-btn:focus-visible { scale: 1.03; }
          .ggb-btn:active { scale: .93; }
          .ggb-btn[data-size='cta'] { padding: 1.1rem 2.6rem; font-size: 1.25rem; }
          .ggb-wrap:has(.ggb-btn[data-size='cta']) .ggb-halo { inset: -12px -20px; filter: blur(26px); }
          @keyframes ggb-pulse { 0%, 100% { opacity: .32; scale: .97; } 50% { opacity: .62; scale: 1.05; } }
          @keyframes ggb-sweep { to { translate: 130% 0; } }
          @media (prefers-reduced-motion: reduce) {
            .ggb-halo { animation: none; }
            .ggb-btn::after { display: none; }
            .ggb-btn { transition: none; }
          }
        </style>
        SVELTE,
        ],
    ],
];
