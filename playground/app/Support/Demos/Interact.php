<?php

/**
 * Demo manifest of the "interact" group — the gestures and confirmations a
 * product leans on: hold or slide to confirm, edit in place, numbers that
 * roll, undo after the fact, reorder, view, compare, swipe, and choose a
 * password. Scenarios live at
 * resources/views/demos/components/interact/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'تعامل', 'en' => 'Interaction'],

    'hold-to-confirm' => [
        'title' => ['fa' => 'نگه‌دار تا تأیید شود', 'en' => 'Hold to confirm'],
        'icon' => 'trash',
        'oneLiner' => [
            'fa' => 'دکمه‌ای برای کارهای برگشت‌ناپذیر که فقط با نگه‌داشتن تأیید می‌شود: نوار یا حلقه پر می‌شود، رهاکردنِ زودهنگام آن را خالی می‌کند و در پایان آیکون به تیک تبدیل می‌شود — با موس، لمس یا نگه‌داشتن Space/Enter.',
            'en' => 'A button for irreversible actions that only confirms when held: a bar or ring fills, letting go early drains it, and on completion the icon swaps to a check — by mouse, touch or holding Space/Enter.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'label / doneLabel', 'type' => 'string', 'default' => "'' / 'Done'", 'note' => [
                'fa' => 'برچسب کار و برچسبِ بعد از تأیید؛ دومی برای صفحه‌خوان هم اعلام می‌شود.',
                'en' => 'The action’s label and the one shown after confirming; the latter is also announced.',
            ]],
            ['name' => 'duration', 'type' => 'int', 'default' => '1200', 'note' => [
                'fa' => 'چند میلی‌ثانیه باید نگه داشت.',
                'en' => 'How many milliseconds the press must be held.',
            ]],
            ['name' => 'variant', 'type' => 'string', 'default' => "'bar'", 'note' => [
                'fa' => 'bar نوارِ سراسری، ring حلقه‌ای دور آیکون.',
                'en' => 'bar fills across the button, ring runs around the icon.',
            ]],
            ['name' => 'action', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'متد Livewire که پس از تأیید صدا زده می‌شود؛ رویداد nx-confirm هم همیشه ارسال می‌شود.',
                'en' => 'A Livewire method called on confirm; the nx-confirm event always fires too.',
            ]],
            ['name' => 'resetAfter', 'type' => 'int', 'default' => '2400', 'note' => [
                'fa' => 'پس از چند میلی‌ثانیه به حالت اول برگردد؛ 0 یعنی در حالت تأیید بماند.',
                'en' => 'Return to idle after this many ms; 0 keeps the done state.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::hold-to-confirm label="برای حذف نگه دارید" done-label="حذف شد"
            hint="برای حذف، دکمه را نگه دارید" action="deleteProject" />
        BLADE,
    ],

    'slide-to-confirm' => [
        'title' => ['fa' => 'بکش تا تأیید شود', 'en' => 'Slide to confirm'],
        'icon' => 'arrow-right',
        'oneLiner' => [
            'fa' => 'دستگیره‌ای که در مسیرش کشیده می‌شود تا کاری تأیید شود؛ برچسب با پیشروی محو می‌شود، کشیدنِ ناقص با فنر برمی‌گردد و در انتها تیک می‌خورد. با کلیدهای جهت و End هم کار می‌کند و در RTL برعکس می‌رود.',
            'en' => 'A thumb dragged along its track to confirm; the label fades with progress, a short drag springs back and the end turns into a check. Arrow keys and End work too, and it runs the other way in RTL.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'label / doneLabel', 'type' => 'string', 'default' => "'' / 'Done'", 'note' => [
                'fa' => 'متن داخل مسیر و متنِ پس از تأیید.',
                'en' => 'The text inside the track and the one after confirming.',
            ]],
            ['name' => 'threshold', 'type' => 'float', 'default' => '0.9', 'note' => [
                'fa' => 'از چه کسری از مسیر به بعد رهاکردن تأیید است؛ پرتاب سریع از نیمه هم حساب می‌شود.',
                'en' => 'The fraction of the track past which a release confirms; a quick flick past half counts too.',
            ]],
            ['name' => 'tone', 'type' => 'string', 'default' => "'accent'", 'note' => [
                'fa' => 'accent · danger · success.',
                'en' => 'accent · danger · success.',
            ]],
            ['name' => 'action', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'متد Livewire پس از تأیید؛ reset() آن را به ابتدا برمی‌گرداند.',
                'en' => 'A Livewire method called on confirm; reset() rewinds the thumb.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::slide-to-confirm label="بکشید تا ۴۹ دلار پرداخت شود" done-label="پرداخت شد" action="pay" />
        BLADE,
    ],

    'inline-edit' => [
        'title' => ['fa' => 'ویرایش درجا', 'en' => 'Inline edit'],
        'icon' => 'edit',
        'oneLiner' => [
            'fa' => 'متنی ساده که با کلیک یا Enter همان‌جا به ورودی تبدیل می‌شود و عرضش نرم تغییر می‌کند؛ Enter ذخیره، Escape لغو، و حالت‌های «در حال ذخیره» و «خطا» را دارد — با wire:model هماهنگ است.',
            'en' => 'Plain text that turns into an input right where it is on click or Enter, its width morphing smoothly; Enter saves, Escape cancels, with saving and error states — friendly to wire:model.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'label', 'type' => 'string', 'default' => "''", 'note' => [
                'fa' => 'نام فیلد برای صفحه‌خوان («نام پروژه»).',
                'en' => 'The field’s name for assistive tech (“Project name”).',
            ]],
            ['name' => 'wire:model', 'type' => 'string', 'default' => '—', 'note' => [
                'fa' => 'مقدارِ ذخیره‌شده به ویژگی Livewire گره می‌خورد (فقط پس از Enter، نه با هر کلید).',
                'en' => 'The saved value is entangled with the Livewire property (only after Enter, not on every key).',
            ]],
            ['name' => 'action', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'متد Livewire که مقدار جدید را می‌گیرد؛ false یا یک پیام برگرداند تا حالت خطا نمایش داده شود.',
                'en' => 'A Livewire method that receives the new value; return false or a message to show the error state.',
            ]],
            ['name' => 'required / placeholder', 'type' => 'bool / string', 'default' => 'false / null', 'note' => [
                'fa' => 'خالی‌گذاشتن را رد کند؛ متن جایگزین وقتی مقدار ندارد.',
                'en' => 'Refuse an empty value; the text shown when there is none.',
            ]],
            ['name' => 'blur', 'type' => 'string', 'default' => "'save'", 'note' => [
                'fa' => 'خروج از فیلد ذخیره کند (save) یا لغو (cancel).',
                'en' => 'Leaving the field saves (save) or cancels (cancel).',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::inline-edit label="نام پروژه" wire:model="project.name" action="rename" required />
        BLADE,
    ],

    'odometer' => [
        'title' => ['fa' => 'کیلومترشمار', 'en' => 'Odometer'],
        'icon' => 'chart',
        'oneLiner' => [
            'fa' => 'عددی که ارقامش در ستون‌های ماسک‌شده می‌غلتند — هر رقم جدا، در افزایش رو به بالا و در کاهش رو به پایین — با ارقام فارسی، قالب Intl و شروع از لحظهٔ دیده‌شدن.',
            'en' => 'A number whose digits roll in masked columns — each digit on its own, upward when it grows and downward when it shrinks — in Persian digits, Intl-formatted, starting when it is seen.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'value', 'type' => 'number', 'default' => '0', 'note' => [
                'fa' => 'مقدار؛ هر بار Livewire مقدار تازه‌ای رندر کند ارقام می‌غلتند.',
                'en' => 'The value; whenever Livewire renders a new one the digits roll.',
            ]],
            ['name' => 'format', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'گزینه‌های Intl.NumberFormat — ارز، درصد، فشرده و…',
                'en' => 'Intl.NumberFormat options — currency, percent, compact…',
            ]],
            ['name' => 'locale', 'type' => 'string', 'default' => 'app locale', 'note' => [
                'fa' => 'زبان ارقام؛ fa ارقام فارسی و جداکنندهٔ ٬ می‌دهد.',
                'en' => 'The digits’ language; fa gives Persian digits and the ٬ separator.',
            ]],
            ['name' => 'from / reveal', 'type' => 'number / bool', 'default' => '0 / true', 'note' => [
                'fa' => 'از کجا و از کی بغلتد: با reveal وقتی وارد دید شد از from شروع می‌کند.',
                'en' => 'Where and when the first roll starts: with reveal it rolls from `from` once in view.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::odometer :value="$revenue" :format="['style' => 'currency', 'currency' => 'IRR', 'notation' => 'compact']" />
        BLADE,
    ],

    'undo-snackbar' => [
        'title' => ['fa' => 'اسنک‌بار بازگردانی', 'en' => 'Undo snackbar'],
        'icon' => 'bell',
        'oneLiner' => [
            'fa' => 'نوارِ «انجام شد · بازگردانی» که خودش می‌رود: تایمر نازکش خالی می‌شود، با هاور یا فوکوس مکث می‌کند و چندتا روی هم می‌نشینند — جدا از توستر، برای کارهایی که برگشت دارند.',
            'en' => 'A “Done · Undo” bar that leaves on its own: its thin timer drains, hover or focus pauses it, and several stack — separate from the toaster, for actions that can be taken back.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'nx-undo (event)', 'type' => 'array', 'default' => '—', 'note' => [
                'fa' => 'از PHP: $this->dispatch(\'nx-undo\', message: …, undo: \'restore\', params: [$id]) — یا $dispatch در Alpine.',
                'en' => 'From PHP: $this->dispatch(\'nx-undo\', message: …, undo: \'restore\', params: [$id]) — or $dispatch in Alpine.',
            ]],
            ['name' => 'duration', 'type' => 'int', 'default' => '6000', 'note' => [
                'fa' => 'مدت پیش‌فرض؛ هر پیام می‌تواند duration خودش را داشته باشد.',
                'en' => 'The default lifetime; each message may carry its own duration.',
            ]],
            ['name' => 'undoLabel', 'type' => 'string', 'default' => "'Undo'", 'note' => [
                'fa' => 'برچسب دکمهٔ بازگردانی.',
                'en' => 'The Undo button’s label.',
            ]],
            ['name' => 'position', 'type' => 'string', 'default' => "'bottom-center'", 'note' => [
                'fa' => 'bottom-center · bottom-start · bottom-end · inline.',
                'en' => 'bottom-center · bottom-start · bottom-end · inline.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::undo-snackbar undo-label="بازگردانی" />

        {{-- in the component --}}
        $this->dispatch('nx-undo', message: 'فاکتور بایگانی شد', undo: 'restore', params: [$invoice->id]);
        BLADE,
    ],

    'sortable-list' => [
        'title' => ['fa' => 'فهرست مرتب‌شدنی', 'en' => 'Sortable list'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'فهرستی که با کشیدن دستگیره مرتب می‌شود و بقیه با انیمیشن FLIP جا باز می‌کنند؛ با کیبورد هم: Space برداشتن، جهت‌ها جابه‌جایی، Space گذاشتن، Escape لغو — و هر گام برای صفحه‌خوان اعلام می‌شود.',
            'en' => 'A list reordered by dragging its handles while the others FLIP out of the way; from the keyboard too: Space picks up, arrows move, Space drops, Escape cancels — every step announced.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'items', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'سطرها: id، label و اختیاری description، meta، icon.',
                'en' => 'The rows: id, label and optionally description, meta, icon.',
            ]],
            ['name' => 'action', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'متد Livewire که ترتیب تازهٔ idها را می‌گیرد؛ رویداد nx-sort هم ارسال می‌شود.',
                'en' => 'A Livewire method that receives the new order of ids; nx-sort fires too.',
            ]],
            ['name' => 'messages', 'type' => 'array', 'default' => 'English', 'note' => [
                'fa' => 'جمله‌های اعلام: grabbed، moved، dropped، cancelled با {name}، {position}، {total}.',
                'en' => 'The announcements: grabbed, moved, dropped, cancelled with {name}, {position}, {total}.',
            ]],
            ['name' => 'handleLabel / instructions', 'type' => 'string', 'default' => "'Reorder {name}'", 'note' => [
                'fa' => 'نام دستگیره و راهنمای کیبورد که همراهش خوانده می‌شود.',
                'en' => 'The handle’s name and the keyboard instructions read with it.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::sortable-list :items="$steps" action="reorder" label="مراحل انتشار" />
        BLADE,
    ],

    'lightbox' => [
        'title' => ['fa' => 'لایت‌باکس', 'en' => 'Lightbox'],
        'icon' => 'image',
        'oneLiner' => [
            'fa' => 'گالری‌ای که تصویر کوچکش به نمایشگرِ تمام‌صفحه تبدیل می‌شود (View Transitions وقتی باشد، وگرنه FLIP): زوم با چرخ، دو انگشت یا دوبار ضربه، جابه‌جایی، کشیدن برای بعدی/قبلی، کیبورد، زیرنویس و شمارنده — در یک dialog بومی.',
            'en' => 'A gallery whose thumbnail grows into a full-screen viewer (View Transitions when available, a FLIP otherwise): wheel, pinch or double-tap zoom, pan, swipe for next/previous, keyboard, captions and a counter — in a native dialog.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'images', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر تصویر: src، thumb (اختیاری)، alt و caption.',
                'en' => 'Each image: src, thumb (optional), alt and caption.',
            ]],
            ['name' => 'columns / ratio', 'type' => 'int / string', 'default' => "3 / '4 / 3'", 'note' => [
                'fa' => 'تعداد ستون‌ها و نسبت ابعاد تصاویر کوچک.',
                'en' => 'The grid’s columns and the thumbnails’ aspect ratio.',
            ]],
            ['name' => 'label', 'type' => 'string', 'default' => "'Gallery'", 'note' => [
                'fa' => 'نام نمایشگر برای صفحه‌خوان.',
                'en' => 'The viewer’s accessible name.',
            ]],
            ['name' => 'openLabel', 'type' => 'string', 'default' => "'{alt}, image {index} of {total}'", 'note' => [
                'fa' => 'نام دکمهٔ هر تصویر کوچک.',
                'en' => 'Each thumbnail button’s name.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::lightbox :images="$photos" :columns="4" label="عکس‌های سفر" />
        BLADE,
    ],

    'compare-slider' => [
        'title' => ['fa' => 'اسلایدر مقایسه', 'en' => 'Compare slider'],
        'icon' => 'sliders',
        'oneLiner' => [
            'fa' => 'دو تصویرِ قبل و بعد با خط جداکننده‌ای که کشیده می‌شود؛ زیرش یک range واقعی است (کیبورد و صفحه‌خوان رایگان)، با clip-path برش می‌خورد، در RTL درست کار می‌کند و حالت عمودی هم دارد.',
            'en' => 'Before and after images split by a draggable divider; a real range sits underneath (keyboard and screen readers for free), clip-path does the cut, RTL is handled and it can split vertically.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'before / after', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر کدام src و alt.',
                'en' => 'Each takes src and alt.',
            ]],
            ['name' => 'value / wire:model', 'type' => 'float', 'default' => '50', 'note' => [
                'fa' => 'جای خط از ابتدای سطر (یا بالا)، ۰ تا ۱۰۰؛ wire:model روی range می‌نشیند.',
                'en' => 'Where the divider sits from the inline start (or the top), 0–100; wire:model lands on the range.',
            ]],
            ['name' => 'orientation', 'type' => 'string', 'default' => "'horizontal'", 'note' => [
                'fa' => 'horizontal یا vertical.',
                'en' => 'horizontal or vertical.',
            ]],
            ['name' => 'beforeLabel / afterLabel', 'type' => 'string', 'default' => "'Before' / 'After'", 'note' => [
                'fa' => 'برچسب‌های گوشه‌ها.',
                'en' => 'The corner tags.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::compare-slider :before="['src' => $raw, 'alt' => 'خام']" :after="['src' => $graded, 'alt' => 'اصلاح‌رنگ‌شده']"
            before-label="قبل" after-label="بعد" wire:model.live="split" />
        BLADE,
    ],

    'swipe-actions' => [
        'title' => ['fa' => 'کنش‌های کشیدنی', 'en' => 'Swipe actions'],
        'icon' => 'mail',
        'oneLiner' => [
            'fa' => 'سطری که با کشیدن کنار می‌رود تا کنش‌های پشتش پیدا شوند (دو سمت ابتدا و انتها)؛ کشیدنِ کامل کنش اصلی را اجرا می‌کند و هر کنش یک دکمهٔ واقعی است که با Tab هم در دسترس است.',
            'en' => 'A row that slides aside to reveal the actions behind it (start and end sides); a full swipe fires the primary one, and every action is a real button reachable with Tab.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'start / end', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'کنش‌های هر سمت: id، label، icon، tone، primary و click (عبارت wire:click).',
                'en' => 'Each side’s actions: id, label, icon, tone, primary and click (a wire:click expression).',
            ]],
            ['name' => 'fullSwipe', 'type' => 'float|false', 'default' => '0.62', 'note' => [
                'fa' => 'از چه کسری از عرض، کشیدن کنش اصلی را اجرا کند؛ false خاموشش می‌کند.',
                'en' => 'The fraction of the width past which a swipe fires the primary action; false turns it off.',
            ]],
            ['name' => 'nx-action (event)', 'type' => 'string', 'default' => '—', 'note' => [
                'fa' => 'با هر کنش، id آن ارسال می‌شود.',
                'en' => 'Every action dispatches its id.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::swipe-actions
            :start="[['id' => 'read', 'label' => 'خوانده', 'icon' => 'check', 'tone' => 'accent', 'primary' => true, 'click' => 'markRead(4)']]"
            :end="[['id' => 'delete', 'label' => 'حذف', 'icon' => 'trash', 'tone' => 'danger', 'primary' => true, 'click' => 'remove(4)']]">
            …سطر صندوق ورودی…
        </x-nx::swipe-actions>
        BLADE,
    ],

    'password-strength' => [
        'title' => ['fa' => 'قدرت گذرواژه', 'en' => 'Password strength'],
        'icon' => 'lock',
        'oneLiner' => [
            'fa' => 'فیلد گذرواژه با سنجهٔ چندبخشی که رنگ و برچسبش عوض می‌شود، فهرست قواعدی که یکی‌یکی تیک می‌خورند و دکمهٔ نمایش/پنهان؛ امتیازدهی در هسته است و تست دارد.',
            'en' => 'A password field with a segmented meter that changes colour and label, a rule checklist that ticks off one by one and a show/hide toggle; the scoring lives in core, with tests.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'wire:model', 'type' => 'string', 'default' => '—', 'note' => [
                'fa' => 'روی خود input می‌نشیند، همراه بقیهٔ ویژگی‌ها.',
                'en' => 'Lands on the input itself, with every other attribute.',
            ]],
            ['name' => 'minLength', 'type' => 'int', 'default' => '8', 'note' => [
                'fa' => 'حداقل طول؛ کوتاه‌تر از آن حداکثر «ضعیف» می‌گیرد.',
                'en' => 'The minimum length; anything shorter scores “weak” at most.',
            ]],
            ['name' => 'userInputs', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'نام و ایمیل کاربر تا گذرواژهٔ حدس‌پذیر جریمه شود.',
                'en' => 'The user’s name and email, so a guessable password is penalised.',
            ]],
            ['name' => 'labels', 'type' => 'array', 'default' => 'English', 'note' => [
                'fa' => 'ترجمهٔ واژه‌ها: scores، rules، show، hide، strength، met، unmet.',
                'en' => 'The words: scores, rules, show, hide, strength, met, unmet.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::password-strength wire:model.live="password" label="گذرواژه" :user-inputs="[$name, $email]" />
        BLADE,
    ],
];
