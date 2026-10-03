<?php

/**
 * Demo manifest of the "pickers" group — typeahead, tokens, menus at the
 * pointer, previews, sheets, ranges, drums and steppers. Scenarios live at
 * resources/views/demos/components/pickers/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'انتخابگرها و ورودی‌ها', 'en' => 'Pickers & inputs'],

    'combobox' => [
        'title' => ['fa' => 'کمبوباکس', 'en' => 'Combobox'],
        'icon' => 'search',
        'oneLiner' => [
            'fa' => 'انتخاب تکی با تایپ: فهرست با هر حرف فیلتر می‌شود و بخش هم‌خوان زیرخط می‌خورد؛ فلش‌ها، Home/End، Enter و Escape، و جست‌وجوی سمت سرور با یک متد Livewire.',
            'en' => 'A typeahead single-select: the list filters with every letter and the match is underlined; arrows, Home/End, Enter and Escape, and server search through a Livewire method.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'options', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => "['thr' => 'تهران', …] یا فهرست ['value', 'label', 'description', 'icon', 'keywords', 'disabled'].",
                'en' => "['thr' => 'Tehran', …] or a list of ['value', 'label', 'description', 'icon', 'keywords', 'disabled'].",
            ]],
            ['name' => 'wire:model', 'type' => 'string', 'default' => '—', 'note' => [
                'fa' => 'روی خود کامپوننت؛ مقدار انتخاب‌شده را بایند می‌کند (x-modelable).',
                'en' => 'On the component itself; binds the chosen value (x-modelable).',
            ]],
            ['name' => 'search', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'نام متد Livewire که متن تایپ‌شده را می‌گیرد و گزینه‌ها را برمی‌گرداند (با تأخیر و اسپینر).',
                'en' => 'A Livewire method that takes the typed text and returns options (debounced, with a spinner).',
            ]],
            ['name' => 'clearable / filter', 'type' => 'bool', 'default' => 'true', 'note' => [
                'fa' => 'دکمهٔ پاک‌کردن؛ فیلتر محلی (برای نتایج سرور خاموشش کنید).',
                'en' => 'The clear button; local filtering (turn off when the server already filters).',
            ]],
            ['name' => 'name / label / placeholder', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'نام فیلد فرم، نام دسترس‌پذیر و متن راهنما.',
                'en' => 'The form field name, the accessible name and the placeholder.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::combobox :options="$cities" label="Destination" placeholder="Search a city"
            wire:model.live="destination" name="destination" />
        BLADE,
    ],

    'tag-input' => [
        'title' => ['fa' => 'ورودی برچسب', 'en' => 'Tag input'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'تایپ کنید و Enter یا ویرگول بزنید تا برچسب با فنر بپرد؛ Backspace روی ورودی خالی آخری را برمی‌دارد، تکراری چشمک می‌زند و سقف تعداد دارد.',
            'en' => 'Type and press Enter or a comma and the chip springs in; Backspace on an empty input removes the last one, duplicates flash and there is a cap.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'value / wire:model', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'برچسب‌های اولیه؛ wire:model آرایه را بایند می‌کند.',
                'en' => 'The starting tags; wire:model binds the array.',
            ]],
            ['name' => 'suggestions', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'پیشنهادها هنگام تایپ (برچسب‌های اضافه‌شده کنار می‌روند).',
                'en' => 'Offered while typing (the ones already added are left out).',
            ]],
            ['name' => 'max', 'type' => 'int', 'default' => 'null', 'note' => [
                'fa' => 'بیشترین تعداد؛ شمارنده «۳ / ۶» زیر فیلد.',
                'en' => 'The cap; a "3 / 6" counter under the field.',
            ]],
            ['name' => 'name', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'هر برچسب را به‌صورت name[] در فرم می‌فرستد.',
                'en' => 'Posts each tag as name[] in a plain form.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::tag-input name="skills" :max="6" :suggestions="['Laravel', 'Livewire', 'Alpine']"
            wire:model.live="skills" />
        BLADE,
    ],

    'context-menu' => [
        'title' => ['fa' => 'منوی راست‌کلیک', 'en' => 'Context menu'],
        'icon' => 'menu',
        'oneLiner' => [
            'fa' => 'راست‌کلیک، نگه‌داشتن انگشت یا Shift+F10: منو درست زیر اشاره‌گر باز می‌شود و از همان نقطه بزرگ می‌شود؛ جداکننده، غیرفعال و زیرمنو.',
            'en' => 'Right-click, long-press or Shift+F10: the menu opens right at the pointer and grows out of it; separators, disabled items and a submenu.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'items', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => "کلیدها: id، label، icon، shortcut، disabled، tone ('danger')، separator، heading، items (زیرمنو).",
                'en' => "Keys: id, label, icon, shortcut, disabled, tone ('danger'), separator, heading, items (a submenu).",
            ]],
            ['name' => 'x-on:nx-select', 'type' => 'event', 'default' => '—', 'note' => [
                'fa' => 'با انتخاب هر گزینه، شناسه‌اش در $event.detail می‌آید.',
                'en' => 'Fires with the picked item’s id in $event.detail.',
            ]],
            ['name' => 'press-delay', 'type' => 'int', 'default' => '500', 'note' => [
                'fa' => 'زمان نگه‌داشتن لمسی پیش از باز شدن (میلی‌ثانیه).',
                'en' => 'How long a touch must rest before it opens (ms).',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::context-menu :items="$fileActions" x-on:nx-select="$wire.run($event.detail)">
            …the file card…
        </x-nx::context-menu>
        BLADE,
    ],

    'hover-card' => [
        'title' => ['fa' => 'کارت پیش‌نمایش', 'en' => 'Hover card'],
        'icon' => 'user',
        'oneLiner' => [
            'fa' => 'پیش‌نمایش غنی با مکث روی لینک یا فوکوس کیبورد؛ تأخیر باز و بسته شدن، و تا وقتی اشاره‌گر داخل کارت است باز می‌ماند.',
            'en' => 'A rich preview on hovering a link or keyboard focus; open and close delays, and it stays open while the pointer is inside the card.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'trigger (slot)', 'type' => 'slot', 'default' => '—', 'note' => [
                'fa' => 'چیزی که رویش مکث می‌کنید — معمولاً یک لینک که خودش هم کار کند (لمس کارت را باز نمی‌کند).',
                'en' => 'What you hover — usually a link that works on its own (touch never opens the card).',
            ]],
            ['name' => 'open-delay / close-delay', 'type' => 'int', 'default' => '500 · 250', 'note' => [
                'fa' => 'تأخیر باز شدن و بسته شدن (میلی‌ثانیه).',
                'en' => 'Open and close delays (ms).',
            ]],
            ['name' => 'side / align', 'type' => 'string', 'default' => 'bottom · center', 'note' => [
                'fa' => 'سمت و هم‌ترازی؛ اگر جا نباشد خودش برمی‌گردد.',
                'en' => 'Side and alignment; it flips when there is no room.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::hover-card>
            <x-slot:trigger><a href="/u/sara">@sara</a></x-slot:trigger>
            …avatar, bio, stats…
        </x-nx::hover-card>
        BLADE,
    ],

    'bottom-sheet' => [
        'title' => ['fa' => 'برگهٔ پایینی', 'en' => 'Bottom sheet'],
        'icon' => 'sliders',
        'oneLiner' => [
            'fa' => 'برگهٔ کشیدنی روی dialog بومی با نقاط توقف؛ فراتر از حد کش می‌آید، یک ضربهٔ تند رو به پایین می‌بندد و Escape و پس‌زمینه هم.',
            'en' => 'A draggable sheet on a native dialog with snap points; it rubber-bands past the top, a quick downward flick dismisses it, and so do Escape and the backdrop.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'snaps', 'type' => 'array', 'default' => '[0.5, 0.9]', 'note' => [
                'fa' => 'نقاط توقف به‌صورت کسری از ارتفاع صفحه.',
                'en' => 'Snap points as fractions of the viewport height.',
            ]],
            ['name' => 'id + nx-open-sheet', 'type' => 'event', 'default' => '—', 'note' => [
                'fa' => "باز کردن: \$dispatch('nx-open-sheet', 'id') از هر جای صفحه؛ یا open را با wire:model بایند کنید.",
                'en' => "Open it with \$dispatch('nx-open-sheet', 'id') from anywhere, or bind open with wire:model.",
            ]],
            ['name' => 'initial / dismissible', 'type' => 'int · bool', 'default' => '0 · true', 'note' => [
                'fa' => 'نقطهٔ شروع؛ امکان بستن با کشیدن، Escape و پس‌زمینه.',
                'en' => 'The opening snap; whether drag, Escape and the backdrop may close it.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::button x-data x-on:click="$dispatch('nx-open-sheet', 'filters')">Filters</x-nx::button>
        <x-nx::bottom-sheet id="filters" title="Filters" :snaps="[0.4, 0.9]">…</x-nx::bottom-sheet>
        BLADE,
    ],

    'date-range-picker' => [
        'title' => ['fa' => 'انتخاب بازهٔ تاریخ', 'en' => 'Date-range picker'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'دو ماه کنار هم: شروع و پایان را بزنید و بازه زیر اشاره‌گر پیش‌نمایش می‌شود؛ بازه‌های آماده، و تقویم شمسی برای فارسی و میلادی برای بقیه.',
            'en' => 'Two months side by side: click a start and an end and the range previews under the pointer; presets, and the Jalali calendar for Persian, Gregorian elsewhere.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'value / wire:model', 'type' => 'array', 'default' => '[start, end]', 'note' => [
                'fa' => "['start' => 'YYYY-MM-DD', 'end' => …] — همیشه تاریخ ISO، در هر تقویمی که نمایش داده شود.",
                'en' => "['start' => 'YYYY-MM-DD', 'end' => …] — always ISO days, whichever calendar shows them.",
            ]],
            ['name' => 'calendar', 'type' => 'string', 'default' => 'locale', 'note' => [
                'fa' => 'persian (شمسی) یا gregory؛ پیش‌فرض بر اساس زبان.',
                'en' => 'persian (Jalali) or gregory; follows the locale by default.',
            ]],
            ['name' => 'presets', 'type' => 'array', 'default' => 'all six', 'note' => [
                'fa' => 'today، yesterday، last7، last30، thisMonth، lastMonth یا بازه‌های خودتان؛ [] پنهانشان می‌کند.',
                'en' => 'today, yesterday, last7, last30, thisMonth, lastMonth or your own ranges; [] hides them.',
            ]],
            ['name' => 'min / max / months', 'type' => 'string · int', 'default' => 'null · 2', 'note' => [
                'fa' => 'محدودهٔ مجاز و تعداد ماه‌های کنار هم (۱ یا ۲).',
                'en' => 'The pickable bounds and how many months show (1 or 2).',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::date-range-picker name="stay" label="Stay" min="2026-10-03"
            wire:model.live="stay" />
        BLADE,
    ],

    'time-picker' => [
        'title' => ['fa' => 'انتخاب ساعت', 'en' => 'Time picker'],
        'icon' => 'sliders',
        'oneLiner' => [
            'fa' => 'چرخ‌های ساعت و دقیقه که با اسکرول جا می‌افتند؛ ۱۲ یا ۲۴ ساعته، کیبورد کامل و ارقام بومی.',
            'en' => 'Hour and minute drums that snap as they scroll; 12- or 24-hour, full keyboard and the locale’s digits.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'value / wire:model', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => '"HH:MM" به‌صورت ۲۴ ساعته، هر چه روی صفحه باشد.',
                'en' => '"HH:MM" in 24 hours, whatever the face shows.',
            ]],
            ['name' => 'hour-cycle', 'type' => 'int', 'default' => 'locale', 'note' => [
                'fa' => '۱۲ یا ۲۴؛ پیش‌فرض ۱۲ برای انگلیسی و ۲۴ برای بقیه.',
                'en' => '12 or 24; defaults to 12 for English, 24 elsewhere.',
            ]],
            ['name' => 'minute-step', 'type' => 'int', 'default' => '1', 'note' => [
                'fa' => 'فاصلهٔ دقیقه‌ها روی چرخ (۵، ۱۵…).',
                'en' => 'Minutes between stops on the wheel (5, 15…).',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::time-picker name="pickup" label="Pickup" value="18:30" :minute-step="5"
            wire:model.live="pickup" />
        BLADE,
    ],

    'number-field' => [
        'title' => ['fa' => 'فیلد عددی', 'en' => 'Number field'],
        'icon' => 'plus',
        'oneLiner' => [
            'fa' => 'دکمه‌های کم و زیاد با تکرار هنگام نگه‌داشتن، کشیدن روی برچسب برای تغییر، حداقل/حداکثر/گام، قالب Intl با ارقام بومی و ارقامی که می‌غلتند.',
            'en' => 'Steppers that repeat while held, drag-to-scrub on the label, min/max/step, Intl formatting in the locale’s digits and rolling digits.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'min / max / step', 'type' => 'number', 'default' => 'null · null · 1', 'note' => [
                'fa' => 'محدوده و گام؛ مقدار روی شبکهٔ گام جا می‌افتد.',
                'en' => 'Bounds and step; the value snaps onto the step grid.',
            ]],
            ['name' => 'format', 'type' => 'array', 'default' => 'null', 'note' => [
                'fa' => "گزینه‌های Intl.NumberFormat، مثلاً ['style' => 'currency', 'currency' => 'USD'].",
                'en' => "Intl.NumberFormat options, e.g. ['style' => 'currency', 'currency' => 'USD'].",
            ]],
            ['name' => 'scrub', 'type' => 'bool', 'default' => 'true', 'note' => [
                'fa' => 'کشیدن افقی روی برچسب مقدار را تغییر می‌دهد (Shift ده‌برابر).',
                'en' => 'Dragging the label sideways changes the value (Shift ×10).',
            ]],
            ['name' => 'wire:model / name', 'type' => 'string', 'default' => '—', 'note' => [
                'fa' => 'روی کامپوننت؛ عدد خام را بایند می‌کند یا در فرم می‌فرستد.',
                'en' => 'On the component; binds or posts the raw number.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::number-field label="Guests" name="guests" :value="2" :min="1" :max="12"
            wire:model.live="guests" />
        BLADE,
    ],
];
