<?php

/**
 * Demo manifest of the "base" group — the everyday building blocks. The file
 * name is the group id, every key below is a demo slug and its partial lives
 * at resources/views/demos/components/base/{slug}.blade.php. See
 * App\Support\DemoCatalog for the entry shape.
 *
 * Entries with a partial (scenarios) carry full props + code; the rest are
 * listed so the catalog knows them — their partials can land later.
 */
return [
    '__group' => ['fa' => 'پایه', 'en' => 'Base'],

    'button' => [
        'title' => ['fa' => 'دکمه', 'en' => 'Button'],
        'icon' => 'zap',
        'oneLiner' => [
            'fa' => 'دکمهٔ همه‌کارهٔ نابو؛ واریانت‌ها، اندازه‌ها، آیکون، حالت لودینگ و لینک — با فیدبک فشردن فنری و کارکرد کامل با کیبورد.',
            'en' => 'Nabu’s workhorse button: variants, sizes, icons, a loading state and links — springy press feedback, fully keyboard operable.',
        ],
        'js' => false,
        'docs' => null,
        'props' => [
            ['name' => 'variant', 'type' => 'string', 'default' => 'secondary', 'note' => [
                'fa' => 'یکى از secondary · primary · outline · ghost · danger · gold · inverse · link · glow.',
                'en' => 'One of secondary · primary · outline · ghost · danger · gold · inverse · link · glow.',
            ]],
            ['name' => 'size', 'type' => 'string', 'default' => 'md', 'note' => [
                'fa' => 'اندازه‌ها: xs · sm · md · lg · xl؛ شکل قالب هم با shape روی pill یا square می‌شود.',
                'en' => 'Sizes: xs · sm · md · lg · xl; shape sets pill or square corners.',
            ]],
            ['name' => 'icon / icon-end', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'نام آیکون (یا SVG خودتان) ابتدا یا انتهای برچسب؛ آیکون‌های جهت‌دار در RTL خودشان برمی‌گردند.',
                'en' => 'An icon name (or your own SVG) leading or trailing the label; directional icons mirror in RTL.',
            ]],
            ['name' => 'icon-only', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'فقط آیکون نشان می‌دهد؛ متن به‌عنوان برچسب دسترس‌پذیری می‌ماند.',
                'en' => 'Shows the icon alone; the slot text stays as the accessible label.',
            ]],
            ['name' => 'status', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'حالت دستی loading · success · error؛ با wire:click به‌طور خودکار از wire:loading پیروی می‌کند.',
                'en' => 'Manual loading · success · error state; with wire:click it follows wire:loading automatically.',
            ]],
            ['name' => 'href', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'با href دکمه به <a> تبدیل می‌شود؛ نوع پیش‌فرض type هم کنار می‌رود.',
                'en' => 'With href the button renders an <a>; the button type is dropped.',
            ]],
            ['name' => 'effect', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'افکت hover: shine (برق عبوری) یا slide (ورود برچسب دوم).',
                'en' => 'Hover effect: shine (a passing gleam) or slide (the label rolls over).',
            ]],
            ['name' => 'block', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'کل عرض ظرف را می‌گیرد — برای CTAهای تمام‌عرض.',
                'en' => 'Stretches to the container’s width — for full-width CTAs.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::button variant="primary" icon="check" wire:click="save">
            ذخیرهٔ تغییرات
        </x-nx::button>

        <x-nx::button variant="ghost" icon="trash" icon-only aria-label="حذف" />

        <x-nx::button variant="secondary" href="/components" icon-end="arrow-right">
            بازگشت به کاتالوگ
        </x-nx::button>
        BLADE,
    ],

    'input' => [
        'title' => ['fa' => 'ورودی', 'en' => 'Input'],
        'icon' => 'edit',
        'oneLiner' => [
            'fa' => 'فیلد متنی با برچسب، راهنما و خطا؛ آیکون و پیشوند/پسوند، اعتبارسنجی زندهٔ Livewire و ارقام فارسی.',
            'en' => 'The text field with label, hint and error wiring; icons, prefixes/suffixes, live Livewire validation and Persian digits.',
        ],
        'js' => false,
        'docs' => null,
        'props' => [
            ['name' => 'label', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'برچسب بالای فیلد؛ با label، hint یا error دور فیلد قاب nx-field کشیده می‌شود.',
                'en' => 'The field label; any of label/hint/error wraps the field in the nx-field frame.',
            ]],
            ['name' => 'hint', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'متن کمکی زیر برچسب — با aria-describedby به فیلد وصل می‌شود.',
                'en' => 'Helper text under the label — wired to the field via aria-describedby.',
            ]],
            ['name' => 'error', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'پیام خطا؛ اگر ندهید از $errors با نام فیلد (یا wire:model) خوانده می‌شود.',
                'en' => 'Error message; when omitted it is read from $errors by the field name (or wire:model).',
            ]],
            ['name' => 'icon / iconEnd', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'آیکون ابتدا یا انتهای فیلد، داخل قاب گرد.',
                'en' => 'An icon at the start or end of the field, inside the frame.',
            ]],
            ['name' => 'prefix / suffix', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'متن چسبیده به فیلد (مثل https:// یا تومان) به‌جای آیکون.',
                'en' => 'Glued text (like https:// or a currency) instead of an icon.',
            ]],
            ['name' => 'size', 'type' => 'string', 'default' => 'md', 'note' => [
                'fa' => 'اندازهٔ فیلد: sm · md · lg.',
                'en' => 'Field size: sm · md · lg.',
            ]],
            ['name' => 'required', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'ستارهٔ لازم را به برچسب می‌افزاید و required را روی خود input می‌گذارد.',
                'en' => 'Adds the required star to the label and required on the input itself.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::input label="نام نمایشی" hint="این نام برای هم‌تیمی‌ها دیده می‌شود"
            icon="user" wire:model.blur="state.name" required />

        <x-nx::input label="آدرس فروشگاه" prefix="https://" suffix=".nabu.shop"
            wire:model.live="state.subdomain" />

        <x-nx::input label="ایمیل" type="email" icon="mail" error="این ایمیل معتبر نیست"
            wire:model.blur="state.email" />
        BLADE,
    ],

    'textarea' => [
        'title' => ['fa' => 'متن‌بلوک', 'en' => 'Textarea'],
        'icon' => 'message',
        'oneLiner' => [
            'fa' => 'فیلد چندخطی با رشد خودکار تا maxRows و همان چارچوب برچسب/راهنما/خطای ورودی.',
            'en' => 'The multi-line field that grows with its content up to maxRows, sharing the input’s label/hint/error frame.',
        ],
        'js' => false,
        'docs' => null,
        'props' => [
            ['name' => 'maxRows', 'type' => 'int', 'default' => 'null', 'note' => [
                'fa' => 'بیشترین تعداد ردیف‌ها قبل از آنکه اسکرول درون‌خطی ظاهر شود.',
                'en' => 'Row ceiling before the field scrolls instead of growing.',
            ]],
            ['name' => 'label / hint / error', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'مثل input: قاب فیلد با برچسب، راهنما و خطا.',
                'en' => 'Same as input: the label/hint/error field frame.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::textarea label="یادداشت سفارش" hint="برای انبار‌دار"
            :maxRows="6" wire:model.blur="state.note" />
        BLADE,
    ],

    'select' => [
        'title' => ['fa' => 'انتخاب', 'en' => 'Select'],
        'icon' => 'chevron-down',
        'oneLiner' => [
            'fa' => 'انتخاب بومیِ کامل با قاب فیلد؛ گزینهٔ جای‌نگه‌دار، گزینه‌های ازکارافتاده و کشویی‌های وابسته مثل کشور→شهر.',
            'en' => 'The fully native select in the field frame: placeholder, disabled options and dependent pickers like country → city.',
        ],
        'js' => false,
        'docs' => null,
        'props' => [
            ['name' => 'options', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => "['value' => 'برچسب'] یا فهرستی از ['value' => …, 'label' => …]؛ با کلید disabled می‌توان گزینه را ازکار انداخت.",
                'en' => "['value' => 'Label'] or a list of ['value' => …, 'label' => …]; an option may carry disabled.",
            ]],
            ['name' => 'placeholder', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'گزینهٔ اولِ ازکارافتاده وقتی هنوز چیزی انتخاب نشده.',
                'en' => 'The disabled first option shown while nothing is chosen.',
            ]],
            ['name' => 'label / hint / error', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'قاب فیلد با برچسب، راهنما و خطا — مثل input.',
                'en' => 'The label/hint/error field frame — same as input.',
            ]],
            ['name' => 'size', 'type' => 'string', 'default' => 'md', 'note' => [
                'fa' => 'اندازه: sm · md · lg.',
                'en' => 'Size: sm · md · lg.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::select label="کشور" placeholder="انتخاب کنید"
            :options="['ir' => 'ایران', 'de' => 'آلمان', 'jp' => 'ژاپن']"
            wire:model.live="state.country" />

        <x-nx::select label="شهر" :options="$cities" wire:model="state.city" />
        BLADE,
    ],

    'checkbox' => [
        'title' => ['fa' => 'چک‌باکس', 'en' => 'Checkbox'],
        'icon' => 'check',
        'oneLiner' => [
            'fa' => 'چک‌باکس بومی با برچسب و توضیح، حالت سه‌وضعیتی (indeterminate) و خطای اعتبارسنجی.',
            'en' => 'The native checkbox with label and description, an indeterminate state and validation errors.',
        ],
        'js' => false,
        'docs' => null,
        'props' => [
            ['name' => 'label / description', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'برچسب کلیک‌پذیر و توضیح دوم؛ بدون آن‌ها چک‌باکس تنها رندر می‌شود.',
                'en' => 'The clickable label and its description; without them the bare checkbox renders.',
            ]],
            ['name' => 'indeterminate', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'حالت «برخی انتخاب‌شده» — مثل انتخاب همه در جدول.',
                'en' => 'The “some selected” state — like a table’s select-all.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::checkbox label="خبرنامهٔ هفتگی" description="هر پنجشنبه، فقط محصول"
            wire:model="state.newsletter" />
        BLADE,
    ],

    'switch' => [
        'title' => ['fa' => 'سوییچ', 'en' => 'Switch'],
        'icon' => 'play',
        'oneLiner' => [
            'fa' => 'کلید روشن/خاموش روی checkbox بومی با نقش switch؛ با برچسب، توضیح و دو اندازه.',
            'en' => 'The on/off toggle over a native checkbox with the switch role; label, description and two sizes.',
        ],
        'js' => false,
        'docs' => null,
        'props' => [
            ['name' => 'label / description', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'برچسب و توضیح کنار کلید.',
                'en' => 'The label and description beside the toggle.',
            ]],
            ['name' => 'size', 'type' => 'string', 'default' => 'md', 'note' => [
                'fa' => 'اندازهٔ کلید: sm · md.',
                'en' => 'Toggle size: sm · md.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::switch label="احراز هویت دو مرحله‌ای" description="با پیامک"
            wire:model="state.mfa" />
        BLADE,
    ],

    'radio-group' => [
        'title' => ['fa' => 'گروه رادیو', 'en' => 'Radio group'],
        'icon' => 'globe',
        'oneLiner' => [
            'fa' => 'fieldset از رادیوها با legend و چیدمان عمودی/افقی — مثل انتخاب پلن صورت‌حساب.',
            'en' => 'A fieldset of radios with a legend and vertical/horizontal layout — like picking a billing plan.',
        ],
        'js' => false,
        'docs' => null,
        'props' => [
            ['name' => 'options', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => "['monthly' => 'ماهانه'] یا فهرست ['value' => …, 'label' => …].",
                'en' => "['monthly' => 'Monthly'] or a list of ['value' => …, 'label' => …].",
            ]],
            ['name' => 'legend', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'عنوان گروه داخل fieldset.',
                'en' => 'The group’s legend inside the fieldset.',
            ]],
            ['name' => 'orientation', 'type' => 'string', 'default' => 'vertical', 'note' => [
                'fa' => 'چیدمان عمودی یا افقی.',
                'en' => 'Vertical or horizontal layout.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::radio-group legend="دورهٔ پرداخت" name="cycle"
            :options="['monthly' => 'ماهانه', 'yearly' => 'سالانه']"
            wire:model.live="state.cycle" />
        BLADE,
    ],

    'slider' => [
        'title' => ['fa' => 'اسلایدر', 'en' => 'Slider'],
        'icon' => 'sliders',
        'oneLiner' => [
            'fa' => 'رِنج بومی با عددی که روی دسته می‌سوارد؛ برچسب‌های دو سر و ارقام محلی (فارسی) هنگام کشیدن.',
            'en' => 'A native range with a value that rides the thumb; end labels and locale digits (Persian) while dragging.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'showValue', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'عددِ همراه دسته را نشان می‌دهد.',
                'en' => 'Shows the value riding the thumb.',
            ]],
            ['name' => 'startLabel / endLabel', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'برچسب دو سر مسیر (مثل «آرام» و «تند»).',
                'en' => 'Labels at both ends of the track (like Calm and Fast).',
            ]],
            ['name' => 'decimals', 'type' => 'int', 'default' => 'null', 'note' => [
                'fa' => 'تعداد رقم اعشار عدد نمایشی.',
                'en' => 'Decimal places of the shown value.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::slider label="سقف مصرف" showValue start-label="۱۰" end-label="۵۰۰"
            min="10" max="500" step="10" wire:model.live="state.budget" />
        BLADE,
    ],

    'field' => [
        'title' => ['fa' => 'قاب فیلد', 'en' => 'Field'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'برچسب، راهنما و خطا دور هر کنترل دلخواه — همان چیزی که input و select از درون استفاده می‌کنند.',
            'en' => 'Label, hint and error around any custom control — the frame input and select use internally.',
        ],
        'js' => false,
        'docs' => null,
        'props' => [
            ['name' => 'for', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'id کنترل دلخواه شما؛ for برچسب و aria-describedby خطا/راهنما را وصل می‌کند.',
                'en' => 'Your control’s id; wires the label’s for and the error/hint aria-describedby.',
            ]],
            ['name' => 'label / hint / error', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'برچسب، راهنما و پیام خطا.',
                'en' => 'The label, helper text and error message.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::field label="رنگ برند" hint="در همهٔ تم‌ها بررسی شود" for="brand-color">
            <input type="color" id="brand-color" class="nx-input" />
        </x-nx::field>
        BLADE,
    ],

    'icon' => [
        'title' => ['fa' => 'آیکون', 'en' => 'Icon'],
        'icon' => 'star',
        'oneLiner' => [
            'fa' => 'آیکون‌های خطی نابو از هسته؛ تزئینی به‌طور پیش‌فرض و با label دسترس‌پذیر.',
            'en' => 'Nabu’s line icons from the core; decorative by default, accessible with a label.',
        ],
        'js' => false,
        'docs' => null,
        'props' => [
            ['name' => 'name', 'type' => 'string', 'default' => '—', 'note' => [
                'fa' => 'نام آیکون از بستهٔ هسته (packages/core/src/js/icons.ts).',
                'en' => 'An icon name from the core set (packages/core/src/js/icons.ts).',
            ]],
            ['name' => 'label', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'با label آیکون نقش img می‌گیرد؛ بدون آن aria-hidden است.',
                'en' => 'With a label the icon becomes an img; without it it is aria-hidden.',
            ]],
        ],
        'code' => <<<'BLADE'
        {{ \NabuXUI\NabuXUI::icon('check') }}

        <x-nx::icon name="trend-up" label="روند صعودی" />
        BLADE,
    ],

    'card' => [
        'title' => ['fa' => 'کارت', 'en' => 'Card'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'سطح پایهٔ محتوا با اسلات بدنه/فوتر؛ حالت‌های تعاملی، نورافکن و tilt برای کارت‌های مقصددار.',
            'en' => 'The base content surface with body/footer slots; interactive, spotlight and tilt modes for destination cards.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'interactive / spotlight / tilt', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'بالا آمدن hover، نور دنبال موس و خم‌شدن به سمت اشاره‌گر.',
                'en' => 'Hover lift, a light following the pointer, and a lean toward the pointer.',
            ]],
            ['name' => 'href', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'با href کل کارت یک لینک می‌شود.',
                'en' => 'With href the whole card becomes a link.',
            ]],
            ['name' => 'title / description / icon', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'سرصفحهٔ آمادهٔ کارت؛ بدنه در اسلات پیش‌فرض و پایانی در اسلات footer.',
                'en' => 'The ready-made card head; body in the default slot, footer in the footer slot.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::card interactive spotlight icon="zap" title="نابوگیت"
            description="دروازهٔ پرداخت" href="/gate">
            <x-slot:footer>راه‌اندازی در ۵ دقیقه</x-slot:footer>
        </x-nx::card>
        BLADE,
    ],

    'dialog' => [
        'title' => ['fa' => 'دیالوگ', 'en' => 'Dialog'],
        'icon' => 'lock',
        'oneLiner' => [
            'fa' => 'مودال بومی <dialog> با entangle به Livewire؛ تایید حذف با تایپِ نام، دعوت هم‌تیمی و فوتر کنش.',
            'en' => 'A native <dialog> modal entangled with Livewire; type-to-confirm deletion, teammate invites and action footers.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'wire:model', 'type' => 'bool', 'default' => '—', 'note' => [
                'fa' => 'باز/بسته با یک ویژگی Livewire — از هر دوی سمت سرور و کلاینت.',
                'en' => 'Open/close bound to a Livewire property — from both the server and the client.',
            ]],
            ['name' => 'title / description', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'سرصفحهٔ دیالوگ؛ aria-labelledby و aria-describedby خودکار وصل می‌شوند.',
                'en' => 'The dialog head; aria-labelledby/describedby are wired automatically.',
            ]],
            ['name' => 'size', 'type' => 'string', 'default' => 'md', 'note' => [
                'fa' => 'پهنای دیالوگ: sm · md · lg · xl · full.',
                'en' => 'Dialog width: sm · md · lg · xl · full.',
            ]],
            ['name' => 'closeOnBackdrop', 'type' => 'bool', 'default' => 'true', 'note' => [
                'fa' => 'کلیک روی پس‌زمینه می‌بندد؛ برای فرم‌های خطرناک خاموشش کنید.',
                'en' => 'Clicking the backdrop closes; turn it off for dangerous forms.',
            ]],
            ['name' => 'footer', 'type' => 'slot', 'default' => '—', 'note' => [
                'fa' => 'اسلات پایانی برای ردیف دکمه‌ها.',
                'en' => 'The closing slot for the button row.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::button variant="danger" wire:click="$set('state.confirmOpen', true)">
            حذف ورک‌اسپیس
        </x-nx::button>

        <x-nx::dialog wire:model="state.confirmOpen" title="حذف ورک‌اسپیس"
            description="این کار برگشت‌پذیر نیست" size="sm" :close-on-backdrop="false">
            <p>برای تایید، نام ورک‌اسپیس را بنویسید.</p>
            <x-slot:footer>
                <x-nx::button variant="ghost" wire:click="$set('state.confirmOpen', false)">انصراف</x-nx::button>
                <x-nx::button variant="danger" wire:click="save('حذف شد')">حذف کن</x-nx::button>
            </x-slot:footer>
        </x-nx::dialog>
        BLADE,
    ],

    'drawer' => [
        'title' => ['fa' => 'کشو', 'en' => 'Drawer'],
        'icon' => 'chevron-right',
        'oneLiner' => [
            'fa' => 'همان دیالوگ در حالت کشویی؛ سبد خرید از انتهای صفحه و پنل فیلترها از ابتدای آن.',
            'en' => 'The same dialog in drawer mode; a cart sliding from the inline-end and a filters panel from the inline-start.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'side', 'type' => 'string', 'default' => 'end', 'note' => [
                'fa' => 'سمت ورود: end (پیش‌فرض) یا start — با جهت متن می‌چرخد.',
                'en' => 'Entering side: end (default) or start — it follows the text direction.',
            ]],
            ['name' => 'title / footer', 'type' => 'string|slot', 'default' => 'null', 'note' => [
                'fa' => 'سرصفحهٔ کشو و اسلات فوتر برای جمع‌بندی/پرداخت.',
                'en' => 'The drawer head and the footer slot for the summary/checkout.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::drawer side="end" wire:model="state.cartOpen" title="سبد خرید">
            {{-- آیتم‌ها --}}
            <x-slot:footer>
                <x-nx::button variant="primary" block wire:click="save('پرداخت شد')">پرداخت</x-nx::button>
            </x-slot:footer>
        </x-nx::drawer>
        BLADE,
    ],

    'popover' => [
        'title' => ['fa' => 'پاپ‌اور', 'en' => 'Popover'],
        'icon' => 'external-link',
        'oneLiner' => [
            'fa' => 'پنل سبک روی Popover API بومی با جانمایی side/align؛ برای کارت جزئیات و راهنمای غنی.',
            'en' => 'A light panel on the native Popover API with side/align placement; for detail cards and rich hints.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'side / align', 'type' => 'string', 'default' => 'bottom / center', 'note' => [
                'fa' => 'جانمایی پنل نسبت به تریگر؛ فرورفتن از سمت تریگر انیمیت می‌شود.',
                'en' => 'Panel placement around the trigger; it scales in from the trigger side.',
            ]],
            ['name' => 'label', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'نام دسترس‌پذیری پنل (role=dialog).',
                'en' => 'The panel’s accessible name (role=dialog).',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::popover side="bottom" label="جزئیات دامنه">
            <x-slot:trigger><x-nx::button variant="ghost" icon="info" icon-only aria-label="جزئیات" /></x-slot:trigger>
            <strong>api.nabu.shop</strong> — ۹۹٫۹۸٪ آپ‌تایم این ماه
        </x-nx::popover>
        BLADE,
    ],

    'tooltip' => [
        'title' => ['fa' => 'تول‌تیپ', 'en' => 'Tooltip'],
        'icon' => 'info',
        'oneLiner' => [
            'fa' => 'توضیح کوتاه برای یک عنصر فوکوس‌پذیر؛ با تاخیر ورود و جانمایی چهارجهته.',
            'en' => 'A short note for one focusable element; entry delay and four-side placement.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'text', 'type' => 'string', 'default' => '—', 'note' => [
                'fa' => 'متن تول‌تیپ (role=tooltip).',
                'en' => 'The tooltip text (role=tooltip).',
            ]],
            ['name' => 'side', 'type' => 'string', 'default' => 'top', 'note' => [
                'fa' => 'جهت نمایش: top · bottom · start · end.',
                'en' => 'Placement: top · bottom · start · end.',
            ]],
            ['name' => 'delay', 'type' => 'int', 'default' => '350', 'note' => [
                'fa' => 'تاخیر ورود بر حسب میلی‌ثانیه.',
                'en' => 'Entry delay in milliseconds.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::tooltip text="کپی لینک دعوت">
            <x-nx::button icon="copy" icon-only aria-label="کپی لینک دعوت" />
        </x-nx::tooltip>
        BLADE,
    ],

    'tabs' => [
        'title' => ['fa' => 'تب', 'en' => 'Tabs'],
        'icon' => 'folder',
        'oneLiner' => [
            'fa' => 'تب‌های ARIA کامل با pill زیرِ فنری، کیبورد (فلش/خانه) و سه واریانت؛ محتوا از اسلات‌های نام‌دار.',
            'en' => 'Full ARIA tabs with a springy pill, arrow/home keyboard roving and three variants; panels come from named slots.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'items', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => "['profile' => 'پروفایل'] یا ['key' => ['label' => …, 'icon' => …, 'badge' => …, 'disabled' => …]].",
                'en' => "['profile' => 'Profile'] or ['key' => ['label' => …, 'icon' => …, 'badge' => …, 'disabled' => …]].",
            ]],
            ['name' => 'variant', 'type' => 'string', 'default' => 'pill', 'note' => [
                'fa' => ' pill · underline · enclosed.',
                'en' => ' pill · underline · enclosed.',
            ]],
            ['name' => 'wire:model', 'type' => 'string', 'default' => '—', 'note' => [
                'fa' => 'تب فعال به یک ویژگی Livewire entangle می‌شود.',
                'en' => 'The active tab entangles to a Livewire property.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::tabs :items="['profile' => 'پروفایل', 'security' => 'امنیت']"
            wire:model="state.settingsTab">
            <x-slot:profile>…</x-slot:profile>
            <x-slot:security>…</x-slot:security>
        </x-nx::tabs>
        BLADE,
    ],

    'accordion' => [
        'title' => ['fa' => 'آکاردئون', 'en' => 'Accordion'],
        'icon' => 'file',
        'oneLiner' => [
            'fa' => 'روی <details> بومی؛ باز/بسته با انیمیشن ارتفاع و حالت تک‌انتخابی گروه‌شده.',
            'en' => 'Over native <details>; animated open/close height with a grouped single-open mode.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'single', 'type' => 'bool', 'default' => 'true', 'note' => [
                'fa' => 'فقط یک آیتم باز؛ با name یکسان روی details میسر می‌شود.',
                'en' => 'Only one item open; achieved via a shared name on the details.',
            ]],
            ['name' => 'variant', 'type' => 'string', 'default' => 'joined', 'note' => [
                'fa' => 'joined (چسبیده) یا separated (جدا).',
                'en' => 'joined or separated.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::accordion single>
            <x-nx::accordion-item title="ارسال به کجاست؟" open>…</x-nx::accordion-item>
            <x-nx::accordion-item title="امکان مرجوعی هست؟">…</x-nx::accordion-item>
        </x-nx::accordion>
        BLADE,
    ],

    'avatar' => [
        'title' => ['fa' => 'آواتار', 'en' => 'Avatar'],
        'icon' => 'user',
        'oneLiner' => [
            'fa' => 'آواتار با حروف اول (fallback تصویر)، حلقهٔ وضعیت آنلاین/غایب/مشغول و چهار اندازه.',
            'en' => 'The avatar with initials (the image fallback), an online/away/busy status ring and four sizes.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'name', 'type' => 'string', 'default' => '—', 'note' => [
                'fa' => 'نام کامل؛ حروف اولش هم محتوای آواتار می‌شود هم برچسب aria.',
                'en' => 'The full name; its initials become both the content and the aria label.',
            ]],
            ['name' => 'src', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'عکس؛ اگر لود نشد بدون رفرش به حروف اول برمی‌گردد.',
                'en' => 'Photo; if it fails to load it falls back to the initials without a refresh.',
            ]],
            ['name' => 'status', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'online · away · busy · offline — حلقهٔ رنگی پایین آواتار.',
                'en' => 'online · away · busy · offline — the colored ring at the avatar’s foot.',
            ]],
            ['name' => 'size / shape', 'type' => 'string', 'default' => 'md / circle', 'note' => [
                'fa' => 'اندازه‌های xs · sm · md · lg · xl و شکل square برای گرید اپ‌ها.',
                'en' => 'Sizes xs · sm · md · lg · xl and a square shape for app grids.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::avatar name="نگار رستمی" status="online" size="lg" />

        <x-nx::avatar name="سامان دهقان" shape="square" />
        BLADE,
    ],

    'avatar-group' => [
        'title' => ['fa' => 'گروه آواتار', 'en' => 'Avatar group'],
        'icon' => 'users',
        'oneLiner' => [
            'fa' => 'ردیف هم‌پوشان هم‌تیمی‌ها با شمارندهٔ +n و تول‌تیپ نام روی هر آواتار.',
            'en' => 'An overlapping row of teammates with a +n counter and a name tooltip on each avatar.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'people', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => "فهرست [['name' => …, 'src' => …], …].",
                'en' => "A list of [['name' => …, 'src' => …], …].",
            ]],
            ['name' => 'max', 'type' => 'int', 'default' => '5', 'note' => [
                'fa' => 'بیشینهٔ نمایش؛ بقیه داخل +n.',
                'en' => 'Shown ceiling; the rest folds into +n.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::avatar-group :people="$team" :max="4" label="تیم محصول" />
        BLADE,
    ],

    'badge' => [
        'title' => ['fa' => 'نشان', 'en' => 'Badge'],
        'icon' => 'heart',
        'oneLiner' => [
            'fa' => 'برچسب وضعیت کوتاه با هفت رنگ، نقطه، پالس زنده و حالت solid — روی فاکتور، دپلوی و پلن.',
            'en' => 'The short status label in seven tones with a dot, a live pulse and a solid variant — on invoices, deploys and plans.',
        ],
        'js' => false,
        'docs' => null,
        'props' => [
            ['name' => 'tone', 'type' => 'string', 'default' => 'neutral', 'note' => [
                'fa' => 'neutral · accent · success · warning · danger · info · gold.',
                'en' => 'neutral · accent · success · warning · danger · info · gold.',
            ]],
            ['name' => 'variant', 'type' => 'string', 'default' => 'soft', 'note' => [
                'fa' => 'soft (پس‌زمینهٔ ملایم) یا solid (پُر).',
                'en' => 'soft (tinted) or solid.',
            ]],
            ['name' => 'dot / pulse', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'نقطهٔ کنار متن؛ pulse آن را زنده تپش می‌دهد.',
                'en' => 'A dot beside the text; pulse makes it breathe live.',
            ]],
            ['name' => 'size', 'type' => 'string', 'default' => 'md', 'note' => [
                'fa' => 'md یا lg.',
                'en' => 'md or lg.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::badge tone="success" dot>پرداخت شد</x-nx::badge>
        <x-nx::badge tone="danger" pulse>زنده</x-nx:badge>
        <x-nx::badge tone="gold" variant="solid" size="lg">پرو</x-nx:badge>
        BLADE,
    ],

    'chip' => [
        'title' => ['fa' => 'چیپ', 'en' => 'Chip'],
        'icon' => 'sparkles',
        'oneLiner' => [
            'fa' => 'برچسب کوچک با دکمهٔ حذف برای فیلترهای فعال و برچسب‌های قابل‌حذف — فیلتر فروشگاه واقعی.',
            'en' => 'A small label with a remove button for active filters and removable tags — a real shop filter bar.',
        ],
        'js' => false,
        'docs' => null,
        'props' => [
            ['name' => 'removable', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'دکمهٔ x نشان می‌دهد؛ wire:click از بیرون به آن می‌رسد.',
                'en' => 'Shows the x button; a wire:click from outside reaches it.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::chip removable wire:click="removeTag('urgent')">فوری</x-nx::chip>
        BLADE,
    ],

    'status-badge' => [
        'title' => ['fa' => 'نشان وضعیت', 'en' => 'Status badge'],
        'icon' => 'check-circle',
        'oneLiner' => [
            'fa' => 'پنج وضعیت دپلوی (اجرا/موفق/ناموفق/صف/لغو) با تعویض نرم آیکون و عرضی که دنبال برچسب می‌آید.',
            'en' => 'The five deploy states (running/success/failed/queued/canceled) with a soft icon swap and a width that follows the label.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'status', 'type' => 'string', 'default' => 'queued', 'note' => [
                'fa' => 'running · success · failed · queued · canceled.',
                'en' => 'running · success · failed · queued · canceled.',
            ]],
            ['name' => 'labels', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => "بازنویسی برچسب‌ها: ['running' => 'در حال دپلوی'].",
                'en' => "Label overrides: ['running' => 'Deploying'].",
            ]],
            ['name' => 'live', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'نقش status می‌گیرد تا تغییرات با صفحه‌خوان اعلام شوند.',
                'en' => 'Takes the status role so changes are announced by screen readers.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::status-badge :status="$deploy->status" live
            :labels="['running' => 'در حال دپلوی']" />
        BLADE,
    ],

    'progress' => [
        'title' => ['fa' => 'نوار پیشرفت', 'en' => 'Progress'],
        'icon' => 'upload',
        'oneLiner' => [
            'fa' => 'نوار <progress> بومی؛ مقدار نامعین برای «آماده‌سازی»، رنگ‌های هشدار/خطر و برچسب دسترس‌پذیری.',
            'en' => 'The native <progress> bar; indeterminate for “preparing”, warning/danger tones and an accessible label.',
        ],
        'js' => false,
        'docs' => null,
        'props' => [
            ['name' => 'value', 'type' => 'number', 'default' => 'null', 'note' => [
                'fa' => '۰ تا max؛ بدون value نوار نامعین می‌شود.',
                'en' => '0 to max; without a value the bar goes indeterminate.',
            ]],
            ['name' => 'tone', 'type' => 'string', 'default' => 'accent', 'note' => [
                'fa' => 'accent · success · warning · danger · brand.',
                'en' => 'accent · success · warning · danger · brand.',
            ]],
            ['name' => 'label', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'برچسب aria برای صفحه‌خوان‌ها.',
                'en' => 'The aria label for screen readers.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::progress :value="67" label="آپلود ویدئو" />
        <x-nx::progress tone="danger" :value="34" label="بارگذاری ناموفق" />
        <x-nx::progress label="در حال آماده‌سازی" />
        BLADE,
    ],

    'progress-ring' => [
        'title' => ['fa' => 'حلقهٔ پیشرفت', 'en' => 'Progress ring'],
        'icon' => 'chart',
        'oneLiner' => [
            'fa' => 'حلقهٔ پیشرفت با عدد فارسی وسط (یا اسلات دلخواه)؛ اندازه و ضخامت قابل تنظیم — داشبورد مصرف.',
            'en' => 'The progress ring with a Persian number in the middle (or any slot); size and thickness dials — a usage dashboard.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'value', 'type' => 'number', 'default' => '0', 'note' => [
                'fa' => 'درصد ۰ تا ۱۰۰.',
                'en' => 'Percent 0 to 100.',
            ]],
            ['name' => 'size / thickness', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'قطر و ضخامت حلقه از متغیرهای CSS.',
                'en' => 'Ring diameter and stroke width via CSS variables.',
            ]],
            ['name' => 'tone', 'type' => 'string', 'default' => 'accent', 'note' => [
                'fa' => 'همان رنگ‌های progress.',
                'en' => 'Same tones as progress.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::progress-ring :value="68" label="فضای ابری" />

        <x-nx::progress-ring :value="91" tone="warning" size="4.5rem">
            ۹۱٪
        </x-nx::progress-ring>
        BLADE,
    ],

    'spinner' => [
        'title' => ['fa' => 'اسپینر', 'en' => 'Spinner'],
        'icon' => 'cpu',
        'oneLiner' => [
            'fa' => 'انتظار کوتاه؛ داخل دکمه، کنار متن ردیف و به‌شکل مستقل — با اندازه و رنگ.',
            'en' => 'Short waits; inside buttons, beside row text and standalone — with size and tone dials.',
        ],
        'js' => false,
        'docs' => null,
        'props' => [
            ['name' => 'size', 'type' => 'string', 'default' => 'md', 'note' => [
                'fa' => 'sm ( هم‌اندازهٔ متن) · md · lg.',
                'en' => 'sm (text-sized) · md · lg.',
            ]],
            ['name' => 'label', 'type' => 'string', 'default' => 'در حال بارگذاری', 'note' => [
                'fa' => 'برچسب aria پیش‌فرض بومی‌سازی شده.',
                'en' => 'The localized default aria label.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::button variant="primary" wire:click="save">
            <x-nx::spinner size="sm" wire:loading /> ذخیره
        </x-nx::button>
        BLADE,
    ],

    'skeleton' => [
        'title' => ['fa' => 'اسکلتون', 'en' => 'Skeleton'],
        'icon' => 'image',
        'oneLiner' => [
            'fa' => 'جای‌نگهدار براق هنگام بارگذاری؛ متن چندخطی، بلوک و دایره — با wire:loading عوض می‌شود.',
            'en' => 'The shimmering placeholder while loading; multi-line text, block and circle — swapped in with wire:loading.',
        ],
        'js' => false,
        'docs' => null,
        'props' => [
            ['name' => 'shape', 'type' => 'string', 'default' => 'text', 'note' => [
                'fa' => 'text · block · circle.',
                'en' => 'text · block · circle.',
            ]],
            ['name' => 'lines', 'type' => 'int', 'default' => '1', 'note' => [
                'fa' => 'تعداد خطوط متن (فقط با shape=text).',
                'en' => 'Number of text lines (shape=text only).',
            ]],
            ['name' => 'width / height', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'اندازهٔ صریح مثل 6rem یا 100%.',
                'en' => 'Explicit size like 6rem or 100%.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div class="pg-row">
            <x-nx::skeleton shape="circle" width="3rem" />
            <x-nx::skeleton :lines="2" />
        </div>
        BLADE,
    ],

    'toaster' => [
        'title' => ['fa' => 'توستر', 'en' => 'Toaster'],
        'icon' => 'bell',
        'oneLiner' => [
            'fa' => 'اعلان‌های اعلان پایین صفحه؛ شش رنگ، توضیح و اکشن، توقف زمان با hover، رد کردن با سوایپ.',
            'en' => 'The bottom-corner toast stack; six tones, descriptions and actions, hover-to-pause timers, swipe to dismiss.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'position', 'type' => 'string', 'default' => 'bottom-end', 'note' => [
                'fa' => 'گوشهٔ نمایش — یک بار در layout بگذارید.',
                'en' => 'The corner it sits in — place it once in the layout.',
            ]],
            ['name' => 'toast()', 'type' => 'method', 'default' => '—', 'note' => [
                'fa' => "از Livewire: \$this->toast('ذخیره شد', tone: 'success')؛ از مرورگر: \$nxToast() یا NabuXUI.toast().",
                'en' => "From Livewire: \$this->toast('Saved', tone: 'success'); from the browser: \$nxToast() or NabuXUI.toast().",
            ]],
        ],
        'code' => <<<'BLADE'
        {{-- Livewire — داخل کامپوننت --}}
        $this->toast('پرداخت شد', 'فاکتور ارسال شد', tone: 'success');

        {{-- Blade — یک بار در layout --}}
        <x-nx::toaster />
        BLADE,
    ],

    'alert' => [
        'title' => ['fa' => 'هشدار', 'en' => 'Alert'],
        'icon' => 'alert-triangle',
        'oneLiner' => [
            'fa' => 'پیام سطح صفحه با آیکون، عنوان، توضیح، اسلات اکشن و بستن — از سقف مصرف تا تعمیر برنامه.',
            'en' => 'The page-level message with icon, title, description, an actions slot and a dismiss — from quota warnings to maintenance notices.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'tone', 'type' => 'string', 'default' => 'info', 'note' => [
                'fa' => 'neutral · accent · success · warning · danger · info.',
                'en' => 'neutral · accent · success · warning · danger · info.',
            ]],
            ['name' => 'title', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'تیتر درشت؛ متن بدنه در اسلات.',
                'en' => 'The bold lead; body copy goes in the slot.',
            ]],
            ['name' => 'actions', 'type' => 'slot', 'default' => '—', 'note' => [
                'fa' => 'ردیف کنش‌ها انتهای هشدار.',
                'en' => 'The action row at the alert’s end.',
            ]],
            ['name' => 'dismissible', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'دکمهٔ بستن با انیمیشن خروج.',
                'en' => 'A close button with an exit animation.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::alert tone="warning" title="سقف مصرف نزدیک است" dismissible>
            ۸۰٪ از ۵ گیگابایت مصرف شده است.
            <x-slot:actions>
                <x-nx::button size="sm" variant="secondary">ارتقا</x-nx::button>
            </x-slot:actions>
        </x-nx::alert>
        BLADE,
    ],

    'breadcrumbs' => [
        'title' => ['fa' => 'بردکرامب', 'en' => 'Breadcrumbs'],
        'icon' => 'home',
        'oneLiner' => [
            'fa' => 'مسیر صفحه با جداشندهٔ خودگردان RTL/LTR و aria-current — مثل مسیر فایل در مدیریت اسناد.',
            'en' => 'The page trail with a direction-aware separator and aria-current — like a file path in a docs manager.',
        ],
        'js' => false,
        'docs' => null,
        'props' => [
            ['name' => 'items', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => "[['label' => …, 'href' => …], …]؛ آختری مسیر فعلی است.",
                'en' => "A list of ['label' => …, 'href' => …]; the last one is the current page.",
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::breadcrumbs :items="[
            ['label' => 'اسناد', 'href' => '/docs'],
            ['label' => 'قراردادها', 'href' => '/docs/contracts'],
            ['label' => 'نابو — ۱۴۰۴'],
        ]" />
        BLADE,
    ],

    'pagination' => [
        'title' => ['fa' => 'صفحه‌بندی', 'en' => 'Pagination'],
        'icon' => 'chevron-left',
        'oneLiner' => [
            'fa' => 'صفحه‌بندی با شماره‌های فارسی، گپ «…»، pill زیر صفحهٔ فعال و حالت Livewire یا لینک ساده.',
            'en' => 'Paging with locale digits, the “…” gap, a pill under the active page and Livewire or plain-link modes.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'paginator', 'type' => 'LengthAwarePaginator', 'default' => 'null', 'note' => [
                'fa' => 'پیسجینیتور لاراول؛ صفحه و لینک‌ها خودشان درمی‌آیند.',
                'en' => "Laravel’s paginator; page and links come out of it.",
            ]],
            ['name' => 'livewire', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'با gotoPage(n) به‌جای رفرش صفحه — با trait WithPagination.',
                'en' => 'Uses gotoPage(n) instead of a page reload — with the WithPagination trait.',
            ]],
            ['name' => 'siblings', 'type' => 'int', 'default' => '1', 'note' => [
                'fa' => 'تعداد شماره‌های دو طرف صفحهٔ فعال.',
                'en' => 'Page numbers shown beside the active one.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::pagination :paginator="$invoices" livewire />
        {{-- یا صریح: --}}
        <x-nx::pagination :page="2" :pages="9" :url="fn ($p) => '/invoices?page='.$p" />
        BLADE,
    ],

    'steps' => [
        'title' => ['fa' => 'مرحله‌ها', 'en' => 'Steps'],
        'icon' => 'trend-up',
        'oneLiner' => [
            'fa' => 'نشانگر پیشرفت چندمرحله‌ای با تیک مرحله‌های تمام‌شده، افقی برای ویزارد و عمودی برای رهگیری سفارش.',
            'en' => 'The multi-step progress marker with checks on finished steps — horizontal for wizards, vertical for order tracking.',
        ],
        'js' => false,
        'docs' => null,
        'props' => [
            ['name' => 'steps', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => "فهرست [['title' => …, 'description' => …], …].",
                'en' => "A list of [['title' => …, 'description' => …], …].",
            ]],
            ['name' => 'current', 'type' => 'int', 'default' => '0', 'note' => [
                'fa' => 'اندیس مرحلهٔ جاری؛ قبلش کامل، بعدش در راه.',
                'en' => 'Index of the current step; earlier ones complete, later ones upcoming.',
            ]],
            ['name' => 'orientation', 'type' => 'string', 'default' => 'horizontal', 'note' => [
                'fa' => 'horizontal یا vertical.',
                'en' => 'horizontal or vertical.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::steps :current="1" :steps="[
            ['title' => 'سبد خرید'],
            ['title' => 'ارسال'],
            ['title' => 'پرداخت'],
        ]" />
        BLADE,
    ],

    'kbd' => [
        'title' => ['fa' => 'کلید', 'en' => 'Kbd'],
        'icon' => 'command',
        'oneLiner' => [
            'fa' => 'نمایش کلید میانبر با فونت مونو و لبهٔ سه‌بعدی — پنل راهنمای فرمان‌ها و ارجاع داخل متن.',
            'en' => 'The keyboard-key look with a mono face and a 3D edge — shortcut help panels and inline references.',
        ],
        'js' => false,
        'docs' => null,
        'props' => [
            ['name' => 'slot', 'type' => 'string', 'default' => '—', 'note' => [
                'fa' => 'نشان کلید؛ برای ترکیب‌ها چند kbd پشت‌سرهم بگذارید.',
                'en' => 'The key glyph; stack several kbds for combos.',
            ]],
        ],
        'code' => <<<'BLADE'
        <p>برای جست‌وجو <x-nx::kbd>⌘</x-nx::kbd> <x-nx::kbd>K</x-nx::kbd> را بزنید.</p>
        BLADE,
    ],

    'divider' => [
        'title' => ['fa' => 'جداکننده', 'en' => 'Divider'],
        'icon' => 'minus',
        'oneLiner' => [
            'fa' => 'خط جداکنندهٔ بخش‌ها با role=separator؛ اسلاتی برای برچسب وسط دارد.',
            'en' => 'The rule between sections with role=separator; a slot for a centered label.',
        ],
        'js' => false,
        'docs' => null,
        'props' => [
            ['name' => 'slot', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'متن وسط خط، مثل «یا» بین دکمهٔ گوگل و فرم.',
                'en' => 'Text in the middle of the rule, like “or” between the Google button and the form.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::button block>ورود با گوگل</x-nx::button>
        <x-nx::divider>یا</x-nx::divider>
        BLADE,
    ],

    'header' => [
        'title' => ['fa' => 'هدر', 'en' => 'Header'],
        'icon' => 'menu',
        'oneLiner' => [
            'fa' => 'هدر سایت با برند، مگامنو، اسلات اکشن و حالت‌های شناور/مخفی‌شونده هنگام اسکرول.',
            'en' => 'The site header with brand, mega menu, actions slot and floating / hide-on-scroll modes.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'variant', 'type' => 'string', 'default' => 'bar', 'note' => [
                'fa' => 'bar یا floating.',
                'en' => 'bar or floating.',
            ]],
            ['name' => 'hideOnScroll', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'پایین‌رفتن با اسکرول به پایین و بازگشت با اسکرول به بالا.',
                'en' => 'Hides when scrolling down, returns when scrolling up.',
            ]],
            ['name' => 'items / navigate', 'type' => 'array|bool', 'default' => '[] / false', 'note' => [
                'fa' => 'آیتم‌های مگامنو و عبور لینک‌ها از wire:navigate.',
                'en' => 'Mega-menu items and routing links through wire:navigate.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::header variant="floating" hide-on-scroll :items="$nav" navigate>
            <x-slot:brand>نابو</x-slot:brand>
            <x-slot:actions><x-nx::theme-toggle /></x-slot:actions>
        </x-nx::header>
        BLADE,
    ],

    'nav-menu' => [
        'title' => ['fa' => 'منوی ناوبری', 'en' => 'Nav menu'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'ناوبری افقی با نشانگر خطی که با فنر به لینک فعال/هاور می‌سرد و aria-current.',
            'en' => 'Horizontal nav with a line indicator that springs to the active/hovered link and aria-current.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'items', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => "فهرست [['href' => …, 'label' => …, 'current' => bool], …].",
                'en' => "A list of ['href' => …, 'label' => …, 'current' => bool].",
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::nav-menu :items="[
            ['href' => '/analytics', 'label' => 'تحلیل'],
            ['href' => '/members', 'label' => 'اعضا', 'current' => true],
        ]" />
        BLADE,
    ],

    'mobile-menu' => [
        'title' => ['fa' => 'منوی موبایل', 'en' => 'Mobile menu'],
        'icon' => 'menu',
        'oneLiner' => [
            'fa' => 'همان آیتم‌های مگامنو به‌شکل دریل‌داون چندسطحی — برای کشوی موبایل.',
            'en' => 'The same mega-menu items as a multi-level drill-down — for the mobile drawer.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'items', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'ساختار children دار مثل مگامنو.',
                'en' => 'The same children-bearing structure as the mega menu.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::drawer side="start" wire:model="state.menuOpen">
            <x-nx::mobile-menu :items="$nav" navigate />
        </x-nx::drawer>
        BLADE,
    ],

    'segmented' => [
        'title' => ['fa' => 'سگمنت', 'en' => 'Segmented'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'انتخاب دو-سه‌گزینه‌ای روی radiogroup با thumb فنری — مثل دورهٔ پرداخت ماهانه/سالانه.',
            'en' => 'A two-or-three-way pick over a radiogroup with a springy thumb — like the monthly/yearly billing cycle.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'options', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => "['month' => 'ماهانه'] یا ['key' => ['label' => …, 'icon' => …, 'disabled' => …]].",
                'en' => "['month' => 'Monthly'] or ['key' => ['label' => …, 'icon' => …, 'disabled' => …]].",
            ]],
            ['name' => 'wire:model', 'type' => 'string', 'default' => '—', 'note' => [
                'fa' => 'گزینهٔ فعال به Livewire وصل می‌شود.',
                'en' => 'The active option binds to Livewire.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::segmented label="دورهٔ پرداخت"
            :options="['monthly' => 'ماهانه', 'yearly' => 'سالانه']"
            wire:model.live="state.cycle" />
        BLADE,
    ],

    'sparkline' => [
        'title' => ['fa' => 'اسپارک‌لاین', 'en' => 'Sparkline'],
        'icon' => 'chart',
        'oneLiner' => [
            'fa' => 'نمودار خطی کوچک با مساحت و نقطهٔ انتها؛ رنگ روند و کشیده‌شدن هنگام reveal.',
            'en' => 'The tiny line chart with an area and an end dot; trend coloring and a draw-in on reveal.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'data', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'دنبالهٔ اعداد؛ مسیرها در PHP ساخته می‌شوند.',
                'en' => 'The number series; paths are built in PHP.',
            ]],
            ['name' => 'trend', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'up یا down برای رنگ‌بندی روند.',
                'en' => 'up or down to color the trend.',
            ]],
            ['name' => 'area', 'type' => 'bool', 'default' => 'true', 'note' => [
                'fa' => 'پرشدن زیر خط.',
                'en' => 'Fill under the line.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::sparkline :data="[12, 18, 14, 22, 19, 26]" trend="up" />
        BLADE,
    ],

    'stat-card' => [
        'title' => ['fa' => 'کارت آمار', 'en' => 'Stat card'],
        'icon' => 'chart',
        'oneLiner' => [
            'fa' => 'KPI داشبورد با عددی که از صفر می‌غلتد، دلتای روند، اسپارک‌لاین و پیشوند/پسوند.',
            'en' => 'The dashboard KPI with a number that rolls up from zero, a trend delta, a sparkline and prefix/suffix.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'value / decimals', 'type' => 'number', 'default' => '0 / 0', 'note' => [
                'fa' => 'عدد اصلی و رقم اعشار؛ ارقام با locale فارسی می‌شوند.',
                'en' => 'The main figure and its decimals; digits localize to Persian.',
            ]],
            ['name' => 'delta / invertDelta', 'type' => 'number|bool', 'default' => 'null / false', 'note' => [
                'fa' => 'درصد تغییر؛ برای متریک‌های برعکس (مثل ریزش) خوب/بد را وارونه کنید.',
                'en' => 'Percent change; invert what counts as good for metrics like churn.',
            ]],
            ['name' => 'prefix / suffix / caption', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'واحد پول، پسوند و زیرنویس مقایسه.',
                'en' => 'Currency unit, suffix and the comparison caption.',
            ]],
            ['name' => 'trend', 'type' => 'array', 'default' => 'null', 'note' => [
                'fa' => 'دادهٔ اسپارک‌لاین داخل کارت.',
                'en' => 'The sparkline series inside the card.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::stat-card label="درآمد ماه" :value="482600000" suffix=" تومان"
            :delta="12.4" :trend="[12, 18, 14, 22, 19, 26]" caption="نسبت به ماه گذشته" />
        BLADE,
    ],

    'empty-state' => [
        'title' => ['fa' => 'حالت خالی', 'en' => 'Empty state'],
        'icon' => 'wand',
        'oneLiner' => [
            'fa' => '«هنوز چیزی نیست»ِ دوستانه با بشقاب شناور، مدار نقطه‌چین و کنش‌های اول/دوم — خالص CSS.',
            'en' => 'The friendly “nothing here yet” with a floating plate, a dashed orbit and primary/secondary actions — pure CSS.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'title / description', 'type' => 'string', 'default' => 'بدون نتیجه', 'note' => [
                'fa' => 'تیتر و توضیح؛ عنوان پیش‌فرض از بسته می‌آید.',
                'en' => 'Headline and copy; the title defaults to the package’s “no results”.',
            ]],
            ['name' => 'icon', 'type' => 'string', 'default' => 'folder', 'note' => [
                'fa' => 'آیکون روی بشقاب مرکزی.',
                'en' => 'The icon on the central plate.',
            ]],
            ['name' => 'action / actionHref', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'کنش اصلی؛ کنش دوم با secondaryAction/secondaryHref.',
                'en' => 'Primary action; the quiet one via secondaryAction/secondaryHref.',
            ]],
            ['name' => 'actions', 'type' => 'slot', 'default' => '—', 'note' => [
                'fa' => 'ردیف کنش دلخواه به‌جای دکمه‌های آماده.',
                'en' => 'Your own action row instead of the ready buttons.',
            ]],
            ['name' => 'size', 'type' => 'string', 'default' => 'md', 'note' => [
                'fa' => 'sm برای داخل پنل، md و lg برای صفحه.',
                'en' => 'sm inside panels, md and lg for pages.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::empty-state icon="folder" title="هنوز پروژه‌ای نیست"
            description="نخستین پروژهٔ خودتان را بسازید یا نمونه‌ها را ببینید."
            action="ساخت پروژه" action-href="/projects/new"
            secondary-action="مرور نمونه‌ها" secondary-href="/examples" />
        BLADE,
    ],
];
