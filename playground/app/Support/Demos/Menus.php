<?php

/**
 * Demo manifest of the "menus" group — navigation, morphing panels and the
 * admin frame. The file name is the group id, every key below is a demo slug
 * and its scenarios live at resources/views/demos/components/menus/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'منو و ناوبری', 'en' => 'Menus & navigation'],

    'admin-shell' => [
        'title' => ['fa' => 'پوستهٔ پنل مدیریت', 'en' => 'Admin shell'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'قاب کامل یک صفحهٔ پنل: نوار کناریِ تاشو با هایلایت فنری، نوار بالایی با جست‌وجو و منوی کاربر؛ زیر ۴۸rem نوار کناری کشو می‌شود.',
            'en' => 'The frame a whole admin page lives in: a collapsible sidebar with a springy highlight, a topbar with search and a user menu; under 48rem the sidebar becomes a drawer.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'groups', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'گروه‌های ناوبری: هر گروه label و items دارد؛ آیتم‌ها id، label، icon، badge، href و disabled می‌گیرند. آیتم بدون href دکمه است و با wire:model روی ریشه سینک می‌شود.',
                'en' => 'Nav groups: each with label and items; an item takes id, label, icon, badge, href and disabled. Without href it is a button synced through wire:model on the root.',
            ]],
            ['name' => 'active', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'شناسهٔ آیتم فعال؛ با wire:model="state.page" روی ریشه به Livewire وصل می‌شود (x-modelable) و هایلایت فنری دنبالش می‌رود.',
                'en' => 'The active item id; wire:model="state.page" on the root binds it to Livewire (x-modelable) and the spring highlight follows it.',
            ]],
            ['name' => 'collapsed', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'شروعِ جمع‌شده؛ دکمهٔ پایین نوار کناری وضعیت را برمی‌گرداند (عرض با فنر تغییر می‌کند).',
                'en' => 'Start collapsed; the toggle at the bottom of the sidebar flips it (the width springs).',
            ]],
            ['name' => 'title / subtitle', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'عنوان و زیرعنوان نوار بالایی؛ با اسلات heading جایگزین می‌شوند.',
                'en' => 'Topbar heading and subheading; replaceable via the heading slot.',
            ]],
            ['name' => 'user', 'type' => 'array', 'default' => 'null', 'note' => [
                'fa' => 'name، role، avatar و menu برای منوی کاربر؛ با اسلات userMenu ساختار دلخواه بگذارید.',
                'en' => 'name, role, avatar and menu for the user menu; the userMenu slot takes arbitrary markup.',
            ]],
            ['name' => 'height / min-height', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'ارتفاع قاب را با CSS (مثل 34rem) تنظیم می‌کند؛ پیش‌فرض کل ارتفاع صفحه.',
                'en' => 'Sets the frame height with CSS (e.g. 34rem); defaults to the full viewport.',
            ]],
            ['name' => 'slots', 'type' => '—', 'default' => '—', 'note' => [
                'fa' => 'sidebar، topbar، search، actions، title، heading و userMenu — هر کدام نسخهٔ ساخته‌شده از پراپ‌ها را جایگزین می‌کنند.',
                'en' => 'sidebar, topbar, search, actions, title, heading and userMenu — each replaces the built-in built from the props.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::admin-shell brand="فروشگاه نابو" :active="$state['page'] ?? 'dashboard'" wire:model="state.page"
            title="داشبورد" subtitle="خلاصهٔ امروز" :groups="$nav" :user="$user" height="34rem">
            <x-slot:actions>
                <x-nx::theme-toggle />
                <x-nx::activity-dropdown :items="$activity" />
            </x-slot:actions>
            … محتوای پنل …
        </x-nx::admin-shell>
        BLADE,
    ],

    'admin-sidebar' => [
        'title' => ['fa' => 'نوار کناری پنل', 'en' => 'Admin sidebar'],
        'icon' => 'menu',
        'oneLiner' => [
            'fa' => 'ناوبری گروه‌بندی‌شده با برند، نشان شمارنده و هایلایت فنری زیر آیتم فعال؛ داخل پوسته سینک می‌شود و بیرون آن server-state است.',
            'en' => 'Grouped nav with a brand row, count badges and a spring highlight under the current item; wired inside the shell, server-state outside it.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'groups', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر گروه label و items دارد؛ آیتم: id، label، icon، badge (عدد یا متن — عددها فارسی می‌شوند)، href، navigate، disabled.',
                'en' => 'Each group has label and items; an item: id, label, icon, badge (number or text — numbers get Persian digits), href, navigate, disabled.',
            ]],
            ['name' => 'active', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'شناسهٔ آیتم فعال؛ aria-current="page" و جای هایلایت فنری را تعیین می‌کند.',
                'en' => 'The active item id; drives aria-current="page" and where the spring highlight sits.',
            ]],
            ['name' => 'brand / brand-mark', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'نام برند و نشان آن؛ بدون brand-mark حرف اول برند در مربع نشان داده می‌شود.',
                'en' => 'Brand name and mark; without brand-mark the brand’s first letter becomes the mark.',
            ]],
            ['name' => 'wired', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'داخل admin-shell خودکار روشن می‌شود: آیتم‌ها وضعیت Alpine پوسته را دنبال می‌کنند و دکمهٔ جمع‌کردن ظاهر می‌شود.',
                'en' => 'Turned on automatically inside admin-shell: items follow the shell’s Alpine state and the collapse toggle appears.',
            ]],
            ['name' => 'footer', 'type' => 'slot', 'default' => '—', 'note' => [
                'fa' => 'پایین نوار: کارت کاربر، نسخهٔ محصول یا هر چیز دیگر.',
                'en' => 'The bottom of the bar: a user chip, a product version, anything.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::admin-sidebar brand="مستندات نابو" active="components" label="راهبری مستندات" :groups="[
            ['label' => 'شروع', 'items' => [
                ['id' => 'intro', 'label' => 'مقدمه', 'icon' => 'file', 'href' => '/components'],
                ['id' => 'install', 'label' => 'نصب', 'icon' => 'upload', 'href' => '/components'],
            ]],
            ['label' => 'کامپوننت‌ها', 'items' => [
                ['id' => 'components', 'label' => 'کاتالوگ دموها', 'icon' => 'grid', 'badge' => 31, 'href' => '/components'],
            ]],
        ]">
            <x-slot:footer>…</x-slot:footer>
        </x-nx::admin-sidebar>
        BLADE,
    ],

    'admin-topbar' => [
        'title' => ['fa' => 'نوار بالایی پنل', 'en' => 'Admin topbar'],
        'icon' => 'search',
        'oneLiner' => [
            'fa' => 'ردیف بالای پنل: عنوان و زیرعنوان، جای جست‌وجوی فرمان با کلید میان‌بر، اکشن‌ها و منوی کاربر روی popover بومی.',
            'en' => 'The topbar row: heading and subheading, a command-search seat with a shortcut hint, actions and a user menu on a native popover.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'title / subtitle', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'عنوان و زیرعنوان صفحه؛ با اسلات heading ساختار دلخواه (مسیر، نشان‌ها) بگذارید.',
                'en' => 'Page title and subtitle; the heading slot takes any structure (breadcrumbs, badges).',
            ]],
            ['name' => 'drawer', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'شناسهٔ popover کشوی موبایل — داخل admin-shell خودکار داده می‌شود؛ دکمهٔ منو فقط با آن رندر می‌شود.',
                'en' => 'The mobile drawer popover id — passed automatically inside admin-shell; the menu button renders only with it.',
            ]],
            ['name' => 'search-placeholder / search-hint', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'متن جای جست‌وجو و کلید میان‌بر آن (مثل ⌘K)؛ با اسلات search یک input واقعی بگذارید. کلیک روی دکمهٔ ساخته‌شده رویداد nx-search می‌فرستد.',
                'en' => 'The search seat text and its shortcut (e.g. ⌘K); the search slot takes a real input. The built-in button dispatches nx-search on click.',
            ]],
            ['name' => 'user', 'type' => 'array', 'default' => 'null', 'note' => [
                'fa' => 'name، role، avatar و menu برای منوی کاربر؛ مدخل‌های menu می‌توانند href یا divider باشند.',
                'en' => 'name, role, avatar and menu for the user menu; menu entries may carry href or be a divider.',
            ]],
            ['name' => 'slots', 'type' => '—', 'default' => '—', 'note' => [
                'fa' => 'actions (دکمه‌ها و دراپ‌داون‌ها)، heading، search و userMenu.',
                'en' => 'actions (buttons and dropdowns), heading, search and userMenu.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::admin-topbar title="سفارش‌ها" subtitle="۲۴ سفارش باز" search-placeholder="جست‌وجوی فرمان…" search-hint="⌘K"
            :user="['name' => 'نگار رستمی', 'role' => 'مدیر فروش', 'menu' => [
                ['label' => 'پروفایل', 'icon' => 'user', 'href' => '/components'],
                ['divider' => true],
                ['label' => 'خروج', 'icon' => 'lock'],
            ]]">
            <x-slot:actions>
                <x-nx::theme-toggle />
                <x-nx::activity-dropdown :items="$activity" />
            </x-slot:actions>
        </x-nx::admin-topbar>
        BLADE,
    ],

    'fold-menu' => [
        'title' => ['fa' => 'منوی تاشو', 'en' => 'Fold menu'],
        'icon' => 'folder',
        'oneLiner' => [
            'fa' => 'هر بخش یک تایِ کاغذی است که از لبهٔ بخش قبلی آویزان می‌شود؛ سه‌چهار بخش بهتر خوانده می‌شود و Escape همه را جمع می‌کند.',
            'en' => 'Each section unfolds like a sheet of paper hanging from the one before; three or four read best and Escape folds them all back.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'sections', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر بخش: title و links؛ هر لینک: label، href یا click (عبارت Alpine)، icon، description و current.',
                'en' => 'Each section: title and links; a link: label, href or click (an Alpine expression), icon, description and current.',
            ]],
            ['name' => 'label / nav-label', 'type' => 'string', 'default' => '«منو»', 'note' => [
                'fa' => 'متن تریگر و برچسب دسترس‌پذیری پنل.',
                'en' => 'The trigger text and the panel’s accessible label.',
            ]],
            ['name' => 'align', 'type' => 'string', 'default' => 'start', 'note' => [
                'fa' => 'جهت باز شدن پنل: start یا end نسبت به تریگر.',
                'en' => 'Which side of the trigger the panel opens: start or end.',
            ]],
            ['name' => 'navigate', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'به لینک‌ها wire:navigate می‌دهد تا پیمایش بدون بارگذاری کامل انجام شود.',
                'en' => 'Adds wire:navigate to links so navigation happens without a full reload.',
            ]],
            ['name' => 'footer', 'type' => 'slot', 'default' => '—', 'note' => [
                'fa' => 'یک تایِ آخر برای CTA یا سوییچ زبان.',
                'en' => 'One last fold for a CTA or a language switch.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::fold-menu label="محصول" :sections="[
            ['title' => 'کشف', 'links' => [
                ['label' => 'داشبورد', 'icon' => 'grid', 'current' => true],
                ['label' => 'تیکت تازه', 'icon' => 'message', 'description' => 'پاسخ زیر ۲ ساعت'],
            ]],
            ['title' => 'حساب', 'links' => [
                ['label' => 'خروج', 'icon' => 'lock', 'click' => '$wire.ping(\'خروج انجام شد\')'],
            ]],
        ]">
            <x-slot:footer>
                <x-nx::button variant="primary" size="sm" block>ارتقا به پرو</x-nx::button>
            </x-slot:footer>
        </x-nx::fold-menu>
        BLADE,
    ],

    'mega-menu' => [
        'title' => ['fa' => 'منوی مگا', 'en' => 'Mega menu'],
        'icon' => 'home',
        'oneLiner' => [
            'fa' => 'منوی بزرگ هدر با ستون‌ها و توضیح هر آیتم؛ جابه‌جایی بین تریگرها پنل تازه را از سمت ورودتان می‌لغزاند.',
            'en' => 'A big header menu with columns and per-item descriptions; moving between triggers slides the new panel in from the side you came from.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'items', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'آیتم ساده: label + href (+ current)؛ آیتم پنل‌دار: children با label، href، icon و description و columns برای تعداد ستون.',
                'en' => 'A plain item: label + href (+ current); a panel item: children with label, href, icon and description, plus columns.',
            ]],
            ['name' => 'label', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'برچسب دسترس‌پذیری عنصر nav.',
                'en' => 'The nav element’s accessible label.',
            ]],
            ['name' => 'navigate', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'لینک‌ها با wire:navigate پیمایش می‌شوند.',
                'en' => 'Links navigate through wire:navigate.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::mega-menu label="راهبری اصلی" :items="[
            ['label' => 'محصول', 'columns' => 3, 'children' => [
                ['label' => 'مانیتورینگ', 'icon' => 'chart', 'href' => '/components', 'description' => 'متریک‌های زندهٔ سرویس‌ها'],
                ['label' => 'هشدارها', 'icon' => 'bell', 'href' => '/components', 'description' => 'قواعد و канал‌های اطلاع'],
            ]],
            ['label' => 'قیمت‌گذاری', 'href' => '/components', 'current' => true],
        ]" />
        BLADE,
    ],

    'stack-menu' => [
        'title' => ['fa' => 'منوی چندسطحی', 'en' => 'Stack menu'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'منوی چندسطحی با push/pop بین لایه‌ها و جست‌وجویی که همهٔ عمق‌ها را می‌گردد؛ نتیجه‌ها ردِ مسیر خودشان را نشان می‌دهند.',
            'en' => 'A multi-level menu with push/pop between levels and a search that spans every depth; results carry their own trail.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'items', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر آیتم: id، label، icon، description، shortcut، tone، disabled، keywords، href، event، params و children برای لایهٔ بعد.',
                'en' => 'Each item: id, label, icon, description, shortcut, tone, disabled, keywords, href, event, params and children for the next level.',
            ]],
            ['name' => 'title', 'type' => 'string', 'default' => '«منو»', 'note' => [
                'fa' => 'عنوان لایهٔ ریشه؛ لایه‌های بعدی همان عنوان آیتم والد را می‌گیرند.',
                'en' => 'The root level’s title; deeper levels take their parent item’s label.',
            ]],
            ['name' => 'side / align', 'type' => 'string', 'default' => 'bottom · start', 'note' => [
                'fa' => 'جهت و تراز پنل نسبت به تریگر (side: bottom یا top).',
                'en' => 'The panel’s side and alignment against the trigger (side: bottom or top).',
            ]],
            ['name' => 'nx-select', 'type' => 'event', 'default' => '—', 'note' => [
                'fa' => 'انتخاب هر آیتم بدون children این رویداد را با id و label می‌فرستد؛ بعد href یا event آیتم اجرا می‌شود.',
                'en' => 'Choosing an item without children fires this event with id and label; the item’s href or event follows.',
            ]],
            ['name' => 'trigger', 'type' => 'slot', 'default' => '—', 'note' => [
                'fa' => 'دکمهٔ دلخواه شما به‌جای دکمهٔ پیش‌فرض.',
                'en' => 'Your own button instead of the default one.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::stack-menu title="تنظیمات" x-on:nx-select="$wire.ping('انتخاب شد: ' + $event.detail.label)" :items="[
            ['id' => 'appearance', 'label' => 'نمایش', 'icon' => 'sun', 'children' => [
                ['id' => 'theme', 'label' => 'پوسته', 'icon' => 'moon', 'children' => [
                    ['id' => 'light', 'label' => 'روشن'], ['id' => 'dark', 'label' => 'تیره'],
                ]],
            ]],
            ['id' => 'signout', 'label' => 'خروج', 'icon' => 'lock', 'tone' => 'danger'],
        ]">
            <x-slot:trigger><x-nx::button icon="sliders">تنظیمات</x-nx::button></x-slot:trigger>
        </x-nx::stack-menu>
        BLADE,
    ],

    'morph-menu' => [
        'title' => ['fa' => 'منوی مورف', 'en' => 'Morph menu'],
        'icon' => 'sliders',
        'oneLiner' => [
            'fa' => 'دکمهٔ فیلتری که ظرف خودش به فهرست چندانتخابی تبدیل می‌شود؛ شمارندهٔ انتخاب با ارقام زبان صفحه رول می‌کند و «پاک کردن» همه را برمی‌گرداند.',
            'en' => 'A filter button that grows into its own multi-select list; the selection count rolls in the page’s digits and “clear” resets it.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'options', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر گزینه: value، label، icon و disabled؛ چک‌باکس‌ها بومی‌اند و به‌صورت name[] پست می‌شوند.',
                'en' => 'Each option: value, label, icon and disabled; the checkboxes are native and post as name[].',
            ]],
            ['name' => 'value / wire:model', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'مقدارهای انتخاب‌شده؛ wire:model مستقیم روی چک‌باکس‌ها می‌نشیند و پس از morph درست می‌ماند.',
                'en' => 'The chosen values; wire:model sits on the checkboxes themselves and survives the morph.',
            ]],
            ['name' => 'label / icon', 'type' => 'string', 'default' => '«فیلتر» · sliders', 'note' => [
                'fa' => 'متن و آیکون تریگر.',
                'en' => 'The trigger’s text and icon.',
            ]],
            ['name' => 'side', 'type' => 'string', 'default' => 'bottom', 'note' => [
                'fa' => 'جهت رشد: bottom یا top.',
                'en' => 'Which way it grows: bottom or top.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::morph-menu label="دپارتمان" name="teams" :value="$state['teams'] ?? []"
            wire:model.live="state.teams" :options="[
                ['value' => 'design', 'label' => 'طراحی', 'icon' => 'edit'],
                ['value' => 'backend', 'label' => 'بک‌اند', 'icon' => 'cpu'],
                ['value' => 'support', 'label' => 'پشتیبانی', 'icon' => 'message'],
            ]" />
        BLADE,
    ],

    'stacked-accordion' => [
        'title' => ['fa' => 'آکاردئون دسته‌ورق', 'en' => 'Stacked accordion'],
        'icon' => 'chevron-down',
        'oneLiner' => [
            'fa' => 'کارت‌ها مثل دسته ورق روی هم می‌نشینند؛ باز کردن یکی آن را بزرگ می‌کند و دسته را با فنر باز می‌پراکند.',
            'en' => 'Cards stack like a deck; opening one grows it and fans the deck out with a spring.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'items', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر آیتم: id، title، subtitle، icon، content و disabled؛ محتوای غنی‌تر با اسلات هم‌نام id.',
                'en' => 'Each item: id, title, subtitle, icon, content and disabled; richer content through the slot named after the id.',
            ]],
            ['name' => 'type', 'type' => 'string', 'default' => 'single', 'note' => [
                'fa' => 'single (هر بار یک کارت) یا multiple (چند کارت با هم).',
                'en' => 'single (one card at a time) or multiple (several together).',
            ]],
            ['name' => 'value / wire:model', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'شناسه‌های باز؛ wire:model آرایهٔ باز‌ها را می‌بندد.',
                'en' => 'The open ids; wire:model binds the array of open ids.',
            ]],
            ['name' => 'collapsible', 'type' => 'bool', 'default' => 'true', 'note' => [
                'fa' => 'اجازهٔ بستن کارتِ تنها باز.',
                'en' => 'Allows closing the only open card.',
            ]],
            ['name' => 'heading-level', 'type' => 'int', 'default' => '3', 'note' => [
                'fa' => 'سطح تیترها برای سلسله‌مراتب درست صفحه (۲ تا ۶).',
                'en' => 'The headings’ level for a correct page outline (2 to 6).',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::stacked-accordion type="multiple" :value="$state['shipping'] ?? ['express']"
            wire:model.live="state.shipping" :items="[
                ['id' => 'express', 'title' => 'پیک فوری', 'subtitle' => 'استانبول، زیر ۲ ساعت', 'icon' => 'zap',
                 'content' => 'ارسال همان روز با پیک؛ هزینه ۴۹٬۰۰۰ تومان.'],
                ['id' => 'post', 'title' => 'پست پیشتاز', 'subtitle' => 'سراسر کشور، ۲ تا ۴ روز', 'icon' => 'globe',
                 'content' => 'مطمئن و ارزان — رایگان روی سفارش‌های بالای یک میلیون.'],
            ]" />
        BLADE,
    ],

    'morph-tabs' => [
        'title' => ['fa' => 'تب‌های مورف', 'en' => 'Morph tabs'],
        'icon' => 'command',
        'oneLiner' => [
            'fa' => 'تب‌های آیکونی؛ تب فعال باز می‌شود و برچسبش را نشان می‌دهد و pill مشترک با فنر دنبالش می‌آید.',
            'en' => 'Icon tabs; the active one opens to show its label while the shared pill springs after it.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'items', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر تب: value، label، icon، content و disabled؛ پنل هر تب با اسلات هم‌نام value.',
                'en' => 'Each tab: value, label, icon, content and disabled; a tab’s panel comes from the slot named after its value.',
            ]],
            ['name' => 'value / wire:model', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'تب فعال؛ wire:model مقدار را می‌بندد و پنل‌ها با morph عوض می‌شوند.',
                'en' => 'The active tab; wire:model binds it and the panels swap through the morph.',
            ]],
            ['name' => 'justify', 'type' => 'string', 'default' => 'start', 'note' => [
                'fa' => 'چینش ردیف تب‌ها: start، center یا end.',
                'en' => 'The tab row’s alignment: start, center or end.',
            ]],
            ['name' => 'label', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'برچسب دسترس‌پذیری tablist.',
                'en' => 'The tablist’s accessible label.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::morph-tabs label="بخش‌ها" :value="$state['tab'] ?? 'home'" wire:model.live="state.tab" :items="[
            ['value' => 'home', 'label' => 'خانه', 'icon' => 'home'],
            ['value' => 'inbox', 'label' => 'صندوق', 'icon' => 'mail'],
        ]">
            <x-slot:home>…محتوای خانه…</x-slot:home>
            <x-slot:inbox>…محتوای صندوق…</x-slot:inbox>
        </x-nx::morph-tabs>
        BLADE,
    ],

    'dock-panels' => [
        'title' => ['fa' => 'داک پنل‌ها', 'en' => 'Dock panels'],
        'icon' => 'zap',
        'oneLiner' => [
            'fa' => 'داکی که با فعال کردن هر آیتم خودش به پنل همان آیتم بزرگ می‌شود؛ Escape یا فشار بیرون جمعش می‌کند.',
            'en' => 'A dock that grows itself into the activated item’s panel; Escape or a press outside folds it back.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'items', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر آیتم: id، label و icon؛ محتوا از اسلات هم‌نام id یا کلید content می‌آید.',
                'en' => 'Each item: id, label and icon; the content comes from the slot named after the id or its content key.',
            ]],
            ['name' => 'value / wire:model', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'شناسهٔ پنل باز؛ بسته بودن یعنی null — wire:model می‌بنددش.',
                'en' => 'The open panel’s id; null when closed — bindable with wire:model.',
            ]],
            ['name' => 'label', 'type' => 'string', 'default' => '«داک»', 'note' => [
                'fa' => 'برچسب دسترس‌پذیری نوار ابزار.',
                'en' => 'The toolbar’s accessible label.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::dock-panels label="پنل‌های سریع" :value="$state['dock'] ?? null" wire:model.live="state.dock" :items="[
            ['id' => 'player', 'label' => 'در حال پخش', 'icon' => 'play'],
            ['id' => 'inbox', 'label' => 'صندوق', 'icon' => 'mail'],
        ]">
            <x-slot:player>…</x-slot:player>
            <x-slot:inbox>…</x-slot:inbox>
        </x-nx::dock-panels>
        BLADE,
    ],

    'activity-dropdown' => [
        'title' => ['fa' => 'دراپ‌داون فعالیت', 'en' => 'Activity dropdown'],
        'icon' => 'bell',
        'oneLiner' => [
            'fa' => 'زنگ اعلان‌ها با نشان خوانده‌نشده، زمان نسبی به زبان صفحه و «همه خوانده شد» که نقطه‌ها را یک‌جا خاموش می‌کند.',
            'en' => 'A notification bell with an unread badge, relative times in the page’s language and a “mark all as read” that dims the dots at once.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'items', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر ردیف: id، actor.name (+avatar)، text، target، time (تاریخ یا متن)، unread و href.',
                'en' => 'Each row: id, actor.name (+avatar), text, target, time (a date or a label), unread and href.',
            ]],
            ['name' => 'title / empty-text', 'type' => 'string', 'default' => '«اعلان‌ها»', 'note' => [
                'fa' => 'عنوان پنل و متن حالت خالی (پیش‌فرض «چیز تازه‌ای نمانده»).',
                'en' => 'The panel title and the empty state text (defaults to “all caught up”).',
            ]],
            ['name' => 'nx-mark-all-read', 'type' => 'event', 'default' => '—', 'note' => [
                'fa' => 'با کلیک «همه خوانده شد» فرستاده می‌شود تا سرور خوانده‌شدن را ذخیره کند.',
                'en' => 'Fired when “mark all as read” is pressed, so the server can persist it.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::activity-dropdown x-on:nx-mark-all-read="$wire.save('همه خوانده شد')" :items="[
            ['id' => 'a1', 'actor' => ['name' => 'آوا کریمی'], 'text' => 'در جریان گذاشت', 'target' => 'طرح سه‌ماهه',
             'time' => now()->subMinutes(4), 'unread' => true],
            ['id' => 'a2', 'actor' => ['name' => 'سهیل نوری'], 'text' => 'تأیید کرد', 'target' => 'خرید دامنه',
             'time' => now()->subDay()],
        ]" />
        BLADE,
    ],

    'member-selector' => [
        'title' => ['fa' => 'انتخاب اعضا', 'en' => 'Member selector'],
        'icon' => 'users',
        'oneLiner' => [
            'fa' => 'چندانتخاب عضو با جست‌وجو و نقش هر نفر؛ استک آواتار روی تریگر با هر انتخاب به‌روز می‌شود و «+n» با ارقام فارسی می‌غلتد.',
            'en' => 'A searchable multi-select of people with per-person roles; the trigger’s avatar stack updates on every pick and the “+n” rolls in Persian digits.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'members', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر عضو: id، name، email و avatar (اختیاری).',
                'en' => 'Each member: id, name, email and optional avatar.',
            ]],
            ['name' => 'value / wire:model', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'شناسه‌های انتخاب‌شده؛ چک‌باکس‌ها name[] پست می‌کنند.',
                'en' => 'The chosen ids; the checkboxes post as name[].',
            ]],
            ['name' => 'roles-model / roles', 'type' => 'string · array', 'default' => 'null · []', 'note' => [
                'fa' => 'مسیر wire:model نقش‌ها و مقدارهای فعلی؛ select هر ردیف به rolesModel.{id} بسته می‌شود.',
                'en' => 'The wire:model path for roles and their current values; each row’s select binds rolesModel.{id}.',
            ]],
            ['name' => 'role-options / default-role', 'type' => 'array · string', 'default' => 'بیننده/ویرایشگر/مدیر', 'note' => [
                'fa' => 'گزینه‌های نقش و نقش پیش‌فرض ردیف‌های تازه.',
                'en' => 'The role choices and the default for freshly picked rows.',
            ]],
            ['name' => 'max', 'type' => 'int', 'default' => '4', 'note' => [
                'fa' => 'بیشترین آواتارِ نمایش‌داده‌شده روی تریگر؛ بقیه «+n» می‌شوند.',
                'en' => 'Avatars shown on the trigger before the rest fold into “+n”.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::member-selector name="team" :members="$people" :value="$state['team'] ?? []"
            wire:model.live="state.team" roles-model="state.teamRoles" :roles="$state['teamRoles'] ?? []" max="5"
            :role-options="[['value' => 'viewer', 'label' => 'بیننده'], ['value' => 'editor', 'label' => 'ویرایشگر']]" />
        BLADE,
    ],

    'chain-selector' => [
        'title' => ['fa' => 'انتخاب شبکه', 'en' => 'Chain selector'],
        'icon' => 'cpu',
        'oneLiner' => [
            'fa' => 'انتخاب شبکه: گلیف تریگر به زنجیرهٔ انتخابی morph می‌شود و پنل، فهرست جست‌وجوپذیر شبکه‌هاست.',
            'en' => 'A network picker: the trigger’s glyph morphs into the chosen chain and the panel is a searchable list of networks.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'chains', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر شبکه: id، name، symbol، tag، icon و tone (lapis · violet · cyan · gold).',
                'en' => 'Each chain: id, name, symbol, tag, icon and tone (lapis · violet · cyan · gold).',
            ]],
            ['name' => 'value / wire:model', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'شبکهٔ انتخاب‌شده؛ به‌صورت input مخفی هم پست می‌شود.',
                'en' => 'The picked chain; also posts through a hidden input.',
            ]],
            ['name' => 'label / placeholder', 'type' => 'string', 'default' => '«شبکه»', 'note' => [
                'fa' => 'برچسب تریگر و متن جای جست‌وجو.',
                'en' => 'The trigger label and the search placeholder.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::chain-selector name="network" :value="$state['network'] ?? 'ethereum'"
            wire:model.live="state.network" :chains="[
                ['id' => 'ethereum', 'name' => 'Ethereum', 'symbol' => 'ETH', 'tag' => 'L1', 'icon' => 'zap', 'tone' => 'lapis'],
                ['id' => 'polygon', 'name' => 'Polygon', 'symbol' => 'POL', 'tag' => 'L2', 'icon' => 'layers', 'tone' => 'violet'],
            ]" />
        BLADE,
    ],

    'language-menu' => [
        'title' => ['fa' => 'منوی زبان', 'en' => 'Language menu'],
        'icon' => 'globe',
        'oneLiner' => [
            'fa' => 'سوییچ زبان: کد زبان داخل تریگر می‌چرخد و انتخاب به‌عنوان input مخفی پست می‌شود.',
            'en' => 'A locale switcher: the language code rolls inside the trigger and the pick posts through a hidden input.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'languages', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر زبان: id، name و short (کد نمایش‌داده‌شده در تریگر).',
                'en' => 'Each language: id, name and short (the code shown in the trigger).',
            ]],
            ['name' => 'value / wire:model', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'زبان انتخاب‌شده.',
                'en' => 'The picked language.',
            ]],
            ['name' => 'name / label', 'type' => 'string', 'default' => 'language · «زبان»', 'note' => [
                'fa' => 'نام input مخفی و برچسب دسترس‌پذیری.',
                'en' => 'The hidden input’s name and the accessible label.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::language-menu name="lang" :value="$state['lang'] ?? 'fa'" wire:model.live="state.lang" :languages="[
            ['id' => 'fa', 'name' => 'فارسی', 'short' => 'FA'],
            ['id' => 'en', 'name' => 'English', 'short' => 'EN'],
        ]" />
        BLADE,
    ],

    'theme-switch' => [
        'title' => ['fa' => 'سوییچ تم', 'en' => 'Theme switch'],
        'icon' => 'sun',
        'oneLiner' => [
            'fa' => 'سه‌حالتهٔ روشن/سیستم/تیره روی مخزن تم هسته؛ thumb فنری بین گزینه‌ها می‌پرد و تغییر از هر جای دیگر (سربرگ، تب دیگر) را دنبال می‌کند.',
            'en' => 'Light/system/dark on the core theme store; the thumb springs between them and it follows changes made elsewhere (the header toggle, another tab).',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'value', 'type' => 'string', 'default' => 'system', 'note' => [
                'fa' => 'light · system · dark — مقدار اولیه؛ پس از آن مخزن تم مبنا است.',
                'en' => 'light · system · dark — the starting value; afterwards the theme store leads.',
            ]],
            ['name' => 'name', 'type' => 'string', 'default' => 'nx-theme', 'note' => [
                'fa' => 'نام رادیوها برای پست شدن داخل فرم.',
                'en' => 'The radios’ name for in-form posting.',
            ]],
            ['name' => 'label', 'type' => 'string', 'default' => '«پوسته»', 'note' => [
                'fa' => 'برچسب گروه رادیو.',
                'en' => 'The radio group’s label.',
            ]],
            ['name' => 'nx-change', 'type' => 'event', 'default' => '—', 'note' => [
                'fa' => 'با هر انتخاب با preference فرستاده می‌شود.',
                'en' => 'Fired with preference on every pick.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::theme-switch label="پوسته" x-on:nx-change="$wire.ping('پوسته: ' + $event.detail.preference)" />
        BLADE,
    ],

    'promo-bar' => [
        'title' => ['fa' => 'نوار اطلاعیه', 'en' => 'Promo bar'],
        'icon' => 'sparkles',
        'oneLiner' => [
            'fa' => 'نوار اعلامیه با نشان چرخیده و لینک فلش؛ بستنش قاب را با انیمیشن grid جمع می‌کند و به‌طور پیش‌فرض تا پایان نشست به‌یاد می‌ماند.',
            'en' => 'An announcement strip with a rotated sticker badge and an arrow link; dismissing folds it away (grid 1fr → 0fr) and is remembered for the session by default.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'id', 'type' => 'string', 'default' => 'promo', 'note' => [
                'fa' => 'شناسهٔ یکتا برای به‌یادماندن بسته‌شدن (کلید sessionStorage).',
                'en' => 'A unique id for remembering the dismissal (the sessionStorage key).',
            ]],
            ['name' => 'badge', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'استیکر چرخیدهٔ کنار پیام؛ false آن را حذف می‌کند.',
                'en' => 'The rotated sticker beside the message; false removes it.',
            ]],
            ['name' => 'href / link-label', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'لینک فلش‌دار انتهای پیام و متنش.',
                'en' => 'The arrow link at the end of the message and its text.',
            ]],
            ['name' => 'persist', 'type' => 'bool', 'default' => 'true', 'note' => [
                'fa' => 'false یعنی با هر بارگذاری دوباره ظاهر شود.',
                'en' => 'false brings it back on every reload.',
            ]],
            ['name' => 'nx-dismiss', 'type' => 'event', 'default' => '—', 'note' => [
                'fa' => 'با بستن فرستاده می‌شود تا سرور بداند.',
                'en' => 'Fired on dismiss so the server knows.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::promo-bar id="shop-launch" badge="جدید" href="/components" link-label="دیدن تخفیف‌ها"
            x-on:nx-dismiss="$wire.ping('بسته شد')">حراج پاییزه — تا ۷۰٪ روی همهٔ قالب‌ها</x-nx::promo-bar>
        BLADE,
    ],

    'chip-filter' => [
        'title' => ['fa' => 'فیلتر چیپی', 'en' => 'Chip filter'],
        'icon' => 'check',
        'oneLiner' => [
            'fa' => 'ردیف انتخاب‌تکی چیپ‌ها؛ اَکسنت زیر گزینهٔ تیک‌خورده فنری حرکت می‌کند و لبه‌های ردیف هنگام لغزش محو می‌شوند.',
            'en' => 'A single-select row of chips; the accent springs under the checked one and the row’s edges fade while it scrolls.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'options', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'کلید → برچسب ساده، یا آرایه‌ای با کلیدهای «label»، «icon» و «disabled».',
                'en' => 'Key → a plain label, or an array with «label», «icon» and «disabled».',
            ]],
            ['name' => 'counts', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'شمارندهٔ هر کلید (خودکار با ارقام فارسی قالب‌بندی نمی‌شود — مقدار خام را بدهید).',
                'en' => 'A count per key (rendered as given — pass the raw value).',
            ]],
            ['name' => 'value / wire:model', 'type' => 'string', 'default' => 'کلید اول', 'note' => [
                'fa' => 'گزینهٔ فعال؛ wire:model روی خود رادیوها می‌نشیند و پس از morph درست می‌ماند.',
                'en' => 'The active option; wire:model sits on the radios and survives the morph.',
            ]],
            ['name' => 'label', 'type' => 'string', 'default' => '«فیلتر»', 'note' => [
                'fa' => 'برچسب دسترس‌پذیری گروه.',
                'en' => 'The group’s accessible label.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::chip-filter label="دسته‌بندی" :value="$state['cat'] ?? 'all'" wire:model.live="state.cat"
            :options="[
                'all' => 'همه',
                'clothes' => ['label' => 'پوشاک', 'icon' => 'heart'],
                'books' => ['label' => 'کتاب', 'icon' => 'file'],
            ]" :counts="['clothes' => 18, 'books' => 7]" />
        BLADE,
    ],

    'sort-pill' => [
        'title' => ['fa' => 'قرص مرتب‌سازی', 'en' => 'Sort pill'],
        'icon' => 'trend-up',
        'oneLiner' => [
            'fa' => 'قرص مرتب‌سازی: برچسب گزینهٔ انتخابی داخل تریگر می‌چرخد و پنلش یک popover بومی است — جاگذاری و بستن از هسته.',
            'en' => 'A quick sort pill: the chosen label rolls inside the trigger and its panel is a native popover — placing and dismissing come from the core.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'options', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'کلید → برچسب، یا آرایه‌ای با کلیدهای «label» و «icon».',
                'en' => 'Key → a label, or an array with «label» and «icon».',
            ]],
            ['name' => 'value / wire:model', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'گزینهٔ فعال؛ به‌صورت input مخفی هم پست می‌شود.',
                'en' => 'The active option; also posts through a hidden input.',
            ]],
            ['name' => 'label / align', 'type' => 'string', 'default' => '«مرتب‌سازی» · start', 'note' => [
                'fa' => 'برچسب تریگر و تراز پنل (start · center · end).',
                'en' => 'The trigger label and the panel alignment (start · center · end).',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::sort-pill label="ترتیب" :value="$state['sort'] ?? 'recent'" wire:model.live="state.sort" :options="[
            'recent' => ['label' => 'تازه‌ترین', 'icon' => 'sparkles'],
            'oldest' => 'قدیمی‌ترین',
            'amount' => ['label' => 'بیشترین مبلغ', 'icon' => 'trend-up'],
        ]" />
        BLADE,
    ],

    'stat-strip' => [
        'title' => ['fa' => 'نوار آمار', 'en' => 'Stat strip'],
        'icon' => 'chart',
        'oneLiner' => [
            'fa' => 'نوار آمار فشرده؛ هر عدد تا رسیدن به دید صفر می‌ماند و یک‌بار با ارقام زبان مخاطب می‌غلتد — مقدار تازهٔ سرور بعد از morph هم می‌غلتد.',
            'en' => 'A compact stat band; each figure holds at zero until seen, then rolls once in the reader’s digits — a fresh server value rolls again after the morph.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'stats', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر آمار: label، value، icon و caption (زیرنویس متنی).',
                'en' => 'Each stat: label, value, icon and caption (a text footnote).',
            ]],
            ['name' => 'label', 'type' => 'string', 'default' => '«آمارها»', 'note' => [
                'fa' => 'برچسب دسترس‌پذیری فهرست توصیفی.',
                'en' => 'The description list’s accessible label.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::stat-strip label="نگاه یک‌روزه" :stats="[
            ['label' => 'سفارش امروز', 'value' => 128, 'icon' => 'layers', 'caption' => '+۱۲ نسبت به دیروز'],
            ['label' => 'بازدید', 'value' => 9412, 'icon' => 'globe'],
            ['label' => 'نرخ تبدیل', 'value' => 3, 'icon' => 'trend-up', 'caption' => '٪'],
        ]" />
        BLADE,
    ],

    'audio-room' => [
        'title' => ['fa' => 'اتاق صوتی', 'en' => 'Audio room'],
        'icon' => 'mic',
        'oneLiner' => [
            'fa' => 'قرص زنده‌ای که مثل dynamic island باز می‌شود؛ گوینده‌ها و شمار شنونده از رندر می‌آیند تا Livewire با morph تازه‌شان کند.',
            'en' => 'A live pill that opens like a dynamic island; speakers and listener count come from the render, so Livewire refreshes them through the morph.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'title', 'type' => 'string', 'default' => '—', 'note' => [
                'fa' => 'نام اتاق — روی قرص و پنل.',
                'en' => 'The room’s name — on the pill and the panel.',
            ]],
            ['name' => 'members', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر عضو: name، role، speaking، muted و avatar.',
                'en' => 'Each member: name, role, speaking, muted and avatar.',
            ]],
            ['name' => 'listeners', 'type' => 'int', 'default' => '0', 'note' => [
                'fa' => 'شمار شنونده‌ها؛ با ارقام زبان صفحه رول می‌کند.',
                'en' => 'The listener count; rolls in the page’s digits.',
            ]],
            ['name' => 'events', 'type' => '—', 'default' => '—', 'note' => [
                'fa' => 'nx-room-mute، nx-room-hand و nx-room-leave از دکمه‌های پنل فرستاده می‌شوند.',
                'en' => 'nx-room-mute, nx-room-hand and nx-room-leave fire from the panel’s buttons.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::audio-room title="پشتیبانی زنده" :listeners="1284" x-on:nx-room-leave="$wire.ping('از اتاق خارج شدید')" :members="[
            ['name' => 'آوا کریمی', 'role' => 'میزبان', 'speaking' => true],
            ['name' => 'سهیل نوری', 'muted' => true],
        ]" />
        BLADE,
    ],

    'voice-recorder' => [
        'title' => ['fa' => 'ضبط صدا', 'en' => 'Voice recorder'],
        'icon' => 'play',
        'oneLiner' => [
            'fa' => 'میکروفونی که قرص ضبط و سپس پخش می‌شود؛ هرگز به میکروفن دست نمی‌زند — نوارها سطح‌های برنامه را دنبال می‌کنند.',
            'en' => 'A mic that becomes a recording pill and then a playback chip; it never touches the microphone — the bars follow levels the app feeds.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'max-duration', 'type' => 'number', 'default' => '0', 'note' => [
                'fa' => 'بیشترین ثانیهٔ ضبط (۰ = بی‌نهایت)؛ ساعت mm:ss با ارقام زبان صفحه می‌غلتد.',
                'en' => 'The max seconds to record (0 = unbounded); the mm:ss clock rolls in the page’s digits.',
            ]],
            ['name' => 'align', 'type' => 'string', 'default' => 'start', 'note' => [
                'fa' => 'جهت گشایش قرص نسبت به دکمه.',
                'en' => 'Which way the pill grows from the button.',
            ]],
            ['name' => 'bars / take-bars', 'type' => 'int', 'default' => '26 · 30', 'note' => [
                'fa' => 'شمار نوارهای ضبط زنده و تیغ‌های موجِ پخش.',
                'en' => 'Live recording bars and playback waveform blades.',
            ]],
            ['name' => 'events', 'type' => '—', 'default' => '—', 'note' => [
                'fa' => 'nx-record-start · pause · resume · stop { duration, levels } · cancel · discard.',
                'en' => 'nx-record-start · pause · resume · stop { duration, levels } · cancel · discard.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::voice-recorder max-duration="90" align="end"
            x-on:nx-record-stop="$wire.save('یادداشت صوتی: ' + $event.detail.duration + ' ثانیه')" />
        BLADE,
    ],

    'registration-card' => [
        'title' => ['fa' => 'کارت ثبت‌نام', 'en' => 'Registration card'],
        'icon' => 'star',
        'oneLiner' => [
            'fa' => 'فرم سه‌مرحله‌ای رویداد: مشخصات، بلیت‌ها با شمارنده و جمعِ رولینگ، بعد تأیید — در پایان تیک می‌کشد و کاغذرنگ می‌بارد.',
            'en' => 'A three-step event form: details, tickets with steppers and a rolling total, then confirm — it ends with a self-drawing check and confetti.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'event', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'title، date، location و badge سربرگ کارت.',
                'en' => 'The card header’s title, date, location and badge.',
            ]],
            ['name' => 'tickets', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر بلیت: id، label، price، description و max؛ قیمت‌ها با NumberFormatter زبان صفحه قالب‌بندی می‌شوند.',
                'en' => 'Each ticket: id, label, price, description and max; prices format through the page-locale NumberFormatter.',
            ]],
            ['name' => 'currency', 'type' => 'string', 'default' => 'USD', 'note' => [
                'fa' => 'کد ارز برای قالب‌بندی قیمت‌ها (مثل IRR).',
                'en' => 'The currency code for price formatting (e.g. IRR).',
            ]],
            ['name' => 'model / wire:submit', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'با model فیلدها به model.name/email/tickets.{id} بسته می‌شوند؛ wire:submit فقط در آخرین مرحله ارسال می‌کند.',
                'en' => 'With model the fields bind to model.name/email/tickets.{id}; wire:submit only submits on the last step.',
            ]],
            ['name' => 'success', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'پس از موفقیتِ سرور true کنید تا انیمیشن پایان اجرا شود.',
                'en' => 'Set true once the server succeeded to play the finish animation.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::registration-card model="state.reg" wire:submit="$set('state.done', true)"
            :success="! empty($state['done'])" currency="IRR"
            :event="['title' => 'کارگاه سیستم طراحی', 'date' => '۲۴ آبان', 'location' => 'برخط', 'badge' => 'ظرفیت محدود']"
            :tickets="[
                ['id' => 'online', 'label' => 'حضوری', 'price' => 2800000, 'description' => 'با ناهار و کارگاه عملی', 'max' => 3],
                ['id' => 'student', 'label' => 'دانشجویی', 'price' => 0, 'max' => 1],
            ]" />
        BLADE,
    ],
];
