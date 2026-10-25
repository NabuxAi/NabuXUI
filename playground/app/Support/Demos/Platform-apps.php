<?php

/**
 * Demo manifest of the "platform-apps" group — one <x-nx::app-window>, four
 * platform skins. Every page puts a whole little app (notes, a music player,
 * a chat, settings) in four windows side by side — windows, macos, android
 * and ios — with the shared DOM skinning itself through data-platform; the
 * switcher above the grid can reskin all four live. Scenarios live at
 * resources/views/demos/components/platform-apps/{slug}.blade.php, each
 * framing the shared app body in apps/{slug}-body.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'اپ‌ها در چهار پلتفرم', 'en' => 'Apps across four platforms'],

    'notebook' => [
        'title' => ['fa' => 'یادداشت‌ها', 'en' => 'Notes'],
        'icon' => 'edit',
        'oneLiner' => [
            'fa' => 'اپ یادداشت در چهار پوستهٔ زنده: همین فهرست و ویرایشگر در قاب ویندوز، مک، اندروید و iOS — سایدبار و caption دسکتاپ، tabbar و FAB موبایل، و سوییچر بالای صفحه که هر چهار پنجره را همان‌جا بازپوسته می‌کند.',
            'en' => 'The notes app in four live skins: the same list + editor inside the Windows, macOS, Android and iOS shells — sidebar and caption on desktop, tab bar and FAB on mobile — with a switcher that reskins all four windows in place.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'platform', 'type' => 'string', 'default' => "'windows'", 'note' => [
                'fa' => 'windows · macos · android · ios — پوسته از data-platform می‌آید و با سوییچر دمو زنده عوض می‌شود (رویداد nx-platform-change).',
                'en' => 'windows · macos · android · ios — the skin comes from data-platform and swaps live from the demo switcher (nx-platform-change event).',
            ]],
            ['name' => 'nav / tabs', 'type' => 'array', 'default' => '[] / nav', 'note' => [
                'fa' => 'آیتم‌های سایدبار دسکتاپ؛ tabs پیش‌فرض همان nav است و tabbar موبایل را می‌سازد (id/label/icon/badge/href مانند workspace-shell).',
                'en' => 'The desktop sidebar items; tabs defaults to nav and builds the mobile tab bar (id/label/icon/badge/href like workspace-shell).',
            ]],
            ['name' => 'snap', 'type' => 'bool', 'default' => 'true', 'note' => [
                'fa' => 'در ویندوز، نگه‌داشتن ماکزیمایز منوی snap layouts را باز می‌کند؛ انتخاب خانه data-shape را لحظه‌ای روی پنجره می‌گذارد.',
                'en' => 'On Windows, holding maximize opens the snap-layouts menu; picking a zone flashes data-shape on the window.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::app-window platform="windows" icon="edit" title="یادداشت‌ها" brand="Nabu"
            :nav="[['id' => 'notes', 'label' => 'یادداشت‌ها', 'icon' => 'edit'], ['id' => 'archive', 'label' => 'بایگانی', 'icon' => 'folder']]"
            active="notes" height="34rem" statusbar="خودکار ذخیره شد">
            … فهرست یادداشت‌ها + ویرایشگر …
        </x-nx::app-window>
        BLADE,
    ],

    'music' => [
        'title' => ['fa' => 'پخش‌کنندهٔ موسیقی', 'en' => 'Music player'],
        'icon' => 'music',
        'oneLiner' => [
            'fa' => 'گرید آلبوم و نوار پخش در چهار پوسته: متریک‌های بالای محتوا (dl.nx-app-metrics) فقط با توکن‌ها رنگ می‌گیرند و نوار پخش در هر پلتفرم جای خود را پیدا می‌کند — دیتای نمونهٔ ثابت، بدون هیچ backend.',
            'en' => 'The album grid and playback bar in four skins: the metrics strip (dl.nx-app-metrics) paints from tokens alone and the play bar finds its per-platform seat — sample data only, no backend.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'metrics', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'value/label/delta/trend — به dl.nx-app-metrics بالای محتوا می‌شود؛ delta با data-trend=up|down رنگ می‌گیرد.',
                'en' => 'value/label/delta/trend — becomes the dl.nx-app-metrics strip above the content; delta colours by data-trend=up|down.',
            ]],
            ['name' => 'fab', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'برچسب دکمهٔ شناور؛ فقط در پوستهٔ اندروید دیده می‌شود.',
                'en' => 'The floating button’s label; visible only in the Android skin.',
            ]],
            ['name' => 'state', 'type' => 'string', 'default' => "'rest'", 'note' => [
                'fa' => 'rest · minimized · maximized — ماکزیمایز بین rest و maximized سوییچ می‌کند و aria-label دکمه restore/maximize می‌شود.',
                'en' => 'rest · minimized · maximized — maximize toggles rest ⇄ maximized and swaps the button’s aria-label to restore.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::app-window platform="macos" icon="music" title="موسیقی" subtitle="در حال پخش"
            :metrics="[['value' => '۱۲۸', 'label' => 'آهنگ'], ['value' => '۱۲', 'label' => 'آلبوم', 'delta' => '+۲', 'trend' => 'up']]"
            height="34rem" fab="پخش">
            … گرید آلبوم + نوار پخش …
        </x-nx::app-window>
        BLADE,
    ],

    'chat' => [
        'title' => ['fa' => 'گفت‌وگو', 'en' => 'Chat'],
        'icon' => 'message',
        'oneLiner' => [
            'fa' => 'فهرست گفت‌وگوها و رشتهٔ پیام در چهار پوسته — نسخهٔ سبک و ایستا، بدون وابستگی به nx-chat: در موبایل tabbar جای سایدبار را می‌گیرد و نشان‌های خوانده‌نشده (nx-app-badge) همان‌جا می‌مانند.',
            'en' => 'The conversation list and message thread in four skins — a light, static take with no nx-chat dependency: on mobile the tab bar takes over from the sidebar while the unread badges stay put.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'brand / brandMark', 'type' => 'string', 'default' => "null / 'N'", 'note' => [
                'fa' => 'نام و مونوگرام بالای سایدبار (nx-app-brand)؛ mark پیش‌فرض حرف اول brand است.',
                'en' => 'The sidebar’s name and monogram (nx-app-brand); the mark defaults to brand’s first letter.',
            ]],
            ['name' => 'statusbar', 'type' => 'string|slot', 'default' => 'null', 'note' => [
                'fa' => 'متن یا slot پایین پنجره — footer.nx-app-statusbar فقط وقتی مقدار دارد رندر می‌شود.',
                'en' => 'Text or slot for the window’s foot — footer.nx-app-statusbar renders only when set.',
            ]],
            ['name' => 'active', 'type' => 'string', 'default' => 'nav[0]', 'note' => [
                'fa' => 'id آیتم جاری؛ wire:model روی ریشه همین را می‌بندد (تنها x-modelable بلوک).',
                'en' => 'The current item’s id; wire:model on the root binds exactly this (the block’s only x-modelable).',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::app-window platform="ios" icon="message" title="گفت‌وگو" brand="Nabu"
            :nav="[['id' => 'chats', 'label' => 'گفت‌وگوها', 'icon' => 'message', 'badge' => 3], ['id' => 'people', 'label' => 'افراد', 'icon' => 'users']]"
            active="chats" height="36rem" statusbar="۳ گفت‌وگوی خوانده‌نشده">
            … فهرست گفت‌وگو + رشتهٔ پیام …
        </x-nx::app-window>
        BLADE,
    ],

    'settings' => [
        'title' => ['fa' => 'تنظیمات', 'en' => 'Settings'],
        'icon' => 'settings',
        'oneLiner' => [
            'fa' => 'ناوبری تنظیمات و ردیف کلیدها در چهار پوسته: سوییچ‌ها checkbox بومی‌اند و در هر پلتفرم همان‌جا کار می‌کنند؛ در اندروید FAB «افزودن» روشن است تا دکمهٔ شناور هم دیده شود.',
            'en' => 'The settings navigation and toggle rows in four skins: the switches are native checkboxes that keep working in every shell; the Android window turns its FAB on so the floating button shows too.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'snap', 'type' => 'bool', 'default' => 'true', 'note' => [
                'fa' => 'false باشد منوی snap layouts اصلاً رندر نمی‌شود.',
                'en' => 'With false the snap-layouts menu is not rendered at all.',
            ]],
            ['name' => 'sidebar / titlebar / toolbar', 'type' => 'slot', 'default' => '—', 'note' => [
                'fa' => 'slotهای همنام، بخش ساخته‌شده از propها را کامل جایگزین می‌کنند.',
                'en' => 'The same-named slots replace the matching built-ins wholesale.',
            ]],
            ['name' => 'height / radius', 'type' => 'length', 'default' => 'null', 'note' => [
                'fa' => 'ارتفاع از --nx-app-height و radius مستقیم روی --_radius پوسته می‌نشیند.',
                'en' => 'Height lands on --nx-app-height; radius overrides the skin’s --_radius directly.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::app-window platform="android" icon="settings" title="تنظیمات" brand="Nabu"
            :nav="[['id' => 'general', 'label' => 'عمومی', 'icon' => 'settings'], ['id' => 'alerts', 'label' => 'اعلان‌ها', 'icon' => 'bell']]"
            active="general" height="32rem" fab="افزودن">
            … ردیف سوییچ‌ها و انتخاب‌ها …
        </x-nx::app-window>
        BLADE,
    ],
];
