<?php

/**
 * Demo manifest of the "cards" group — card decks, carousels and sliders. The
 * file name is the group id, every key below is a demo slug and its partial
 * lives at resources/views/demos/components/cards/{slug}.blade.php. See
 * App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'کارت', 'en' => 'Cards'],

    'stacked-scroll-cards' => [
        'title' => ['fa' => 'کارت‌های سنجاق‌شونده', 'en' => 'Stacked scroll cards'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'کارت‌های بزرگی که با اسکرول روی هم سنجاق می‌شوند؛ کارت‌های زیرین کوچک‌تر می‌شوند، به عقب خم می‌شوند و کم‌رنگ می‌شوند — برای روایت گام‌به‌گام.',
            'en' => 'Big cards that stick over one another as the page scrolls; the covered ones shrink, lean back and dim — made for step-by-step storytelling.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'items', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'کلیدهای هر آیتم: title، description، eyebrow (شمارهٔ کارت به‌طور پیش‌فرض)، tone (lapis | violet | cyan | gold یا هر رنگ)، cover (پس‌زمینهٔ CSS)، image و alt.',
                'en' => 'Item keys: title, description, eyebrow (the card number by default), tone (lapis | violet | cyan | gold or any colour), cover (a CSS background), image, alt.',
            ]],
            ['name' => 'top / offset / height', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'این‌که کارت اول کجا بچسبد، هر کارت بعدی چقدر پایین‌تر بچسبد و بلندی کارت‌ها — هر مقدار معتبر CSS فاصله.',
                'en' => 'Where the first card sticks, how much lower each next one sticks, and the card height — any CSS length.',
            ]],
            ['name' => 'slot‌های هم‌نام آیتم', 'type' => 'slot', 'default' => '—', 'note' => [
                'fa' => 'اسلاتی به نام کلید هر آیتم، پنل تصویرِ همان کارت را با محتوای دلخواه پر می‌کند.',
                'en' => 'A slot named after an item key fills that card’s artwork panel with any content.',
            ]],
            ['name' => 'titleAs', 'type' => 'string', 'default' => 'h3', 'note' => [
                'fa' => 'تگ عنوان کارت‌ها — برای سلسله‌مراتب درست در صفحهٔ خودتان.',
                'en' => 'The heading tag of card titles — for the right outline on your page.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::stacked-scroll-cards top="1.5rem" height="min(24rem, 64svh)" :items="[
            'invite' => ['title' => 'تیم را دعوت کنید', 'description' => '…', 'tone' => 'lapis', 'cover' => $art['lapis']],
            'train' => ['title' => 'ایجنت را آموزش دهید', 'description' => '…', 'tone' => 'gold', 'cover' => $art['gold']],
        ]">
            <x-slot:live><div>…محتوای دلخواه پنل تصویر…</div></x-slot:live>
        </x-nx::stacked-scroll-cards>
        BLADE,
    ],

    'ring-carousel' => [
        'title' => ['fa' => 'کاروسل حلقه‌ای', 'en' => 'Ring carousel'],
        'icon' => 'star',
        'oneLiner' => [
            'fa' => 'کارت‌ها دور یک حلقهٔ سه‌بعدی می‌چرخند؛ درگ با اینرسی و توقف روی نزدیک‌ترین کارت، فلش‌های کیبورد و دکمه‌ها — عنوان کارت جلو با فیلتر goo به بعدی مورف می‌شود.',
            'en' => 'Cards ride a 3D ring: drag with inertia and a snap to the nearest card, arrow keys and buttons — the front title morphs into the next through a goo filter.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'items', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'کلیدها: title، subtitle، cover (پس‌زمینهٔ CSS)، image، alt، href و tone؛ کارت جلو روشن‌تر است.',
                'en' => 'Keys: title, subtitle, cover (a CSS background), image, alt, href, tone; the front card is the lit one.',
            ]],
            ['name' => 'index / wire:model', 'type' => 'int', 'default' => '0', 'note' => [
                'fa' => 'اندیس کارت جلو؛ با wire:model در گام با یک پراپرتی Livewire می‌ماند و با درخواست بعدی فرستاده می‌شود.',
                'en' => 'The front card’s index; wire:model keeps it in step with a Livewire property and sends it with the next request.',
            ]],
            ['name' => 'label', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'برچسب دسترس‌پذیری کل کاروسل (aria-label).',
                'en' => 'The carousel’s accessible name (aria-label).',
            ]],
            ['name' => 'cardWidth / cardHeight', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'اندازهٔ کارت‌ها را با هر مقدار CSS بازتنظیم می‌کند.',
                'en' => 'Resizes the cards with any CSS length.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::ring-carousel label="نمونه‌کارها" :items="$projects" wire:model="state.ring" />
        BLADE,
    ],

    'expandable-stack' => [
        'title' => ['fa' => 'دستهٔ بازشو', 'en' => 'Expandable stack'],
        'icon' => 'folder',
        'oneLiner' => [
            'fa' => 'دسته‌ای از کارت‌ها که به‌شکل بادبزنی روی هم نشسته و با فنر ملایم به یک گرید می‌شکافد (FLIP) و برمی‌گردد — برای گالری‌های فشرده.',
            'en' => 'A fanned pile of cards that flies out into a grid (FLIP on the gentle spring) and back — for compact galleries.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'items', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'کلیدها: title، description، cover، image، alt و tone؛ اسلاتی هم‌نام کلید هر آیتم پنل تصویرش را پر می‌کند.',
                'en' => 'Keys: title, description, cover, image, alt, tone; a slot named after an item key fills its artwork.',
            ]],
            ['name' => 'expanded', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'دسته را از همان ورود باز می‌کند.',
                'en' => 'Opens the stack on arrival.',
            ]],
            ['name' => 'wire:model', 'type' => '—', 'default' => 'null', 'note' => [
                'fa' => 'باز/بسته بودن را در گام با یک پراپرتی Livewire نگه می‌دارد.',
                'en' => 'Keeps the open/closed state in step with a Livewire property.',
            ]],
            ['name' => 'expandLabel / collapseLabel', 'type' => 'string', 'default' => 'Show all / Stack them', 'note' => [
                'fa' => 'متن دکمهٔ باز و بسته کردن.',
                'en' => 'The expand and collapse button labels.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::expandable-stack wire:model="state.open" expand-label="نمایش همه" collapse-label="بستن" :items="[
            'logo' => ['title' => 'لوگو', 'description' => '…', 'cover' => $art['lapis']],
            'type' => ['title' => 'تایپ', 'description' => '…', 'cover' => $art['gold']],
        ]" />
        BLADE,
    ],

    'orbit-showcase' => [
        'title' => ['fa' => 'ویترین مداری', 'en' => 'Orbit showcase'],
        'icon' => 'globe',
        'oneLiner' => [
            'fa' => 'صفحه‌های محصول دور یک مرکز می‌چرخند و همیشه رو به شما هستند؛ hover مکث می‌دهد و کلیک/فوکوس همان را جلو می‌آورد. با حرکت کم خودش ساکن می‌ماند.',
            'en' => 'Product screens orbit a centre, always facing you; hover pauses, click or focus brings one to the front. With reduced motion the ring holds still.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'items', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'کلیدها: title، subtitle، cover، image، alt، href و tone.',
                'en' => 'Keys: title, subtitle, cover, image, alt, href, tone.',
            ]],
            ['name' => 'period', 'type' => 'int', 'default' => '40', 'note' => [
                'fa' => 'ثانیه‌های هر دور کامل.',
                'en' => 'Seconds per full turn.',
            ]],
            ['name' => 'controls', 'type' => 'bool', 'default' => 'true', 'note' => [
                'fa' => 'دکمه‌های توقف/چرخش و جهت.',
                'en' => 'The pause and direction buttons.',
            ]],
            ['name' => 'height / radius', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'بلندی صحنه و شعاع مدار — با هر مقدار CSS.',
                'en' => 'The scene height and orbit radius — any CSS length.',
            ]],
            ['name' => 'slot:center', 'type' => 'slot', 'default' => '—', 'note' => [
                'fa' => 'گوی نورانی مرکز را با محتوای خودتان عوض می‌کند — مثلاً لوگوی برند.',
                'en' => 'Replaces the glowing orb at the centre — your logo, for instance.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::orbit-showcase :period="32" :items="$apps">
            <x-slot:center><img src="/logo.svg" alt="نابو" /></x-slot:center>
        </x-nx::orbit-showcase>
        BLADE,
    ],

    'team-cards' => [
        'title' => ['fa' => 'کارت‌های تیم', 'en' => 'Team cards'],
        'icon' => 'users',
        'oneLiner' => [
            'fa' => 'کارت‌های بلند تیره کنار هم؛ کارتِ hover/فوکوس/لمس‌شده بالا می‌آید و رنگش از پایین سرازیر می‌شود — خالص CSS.',
            'en' => 'Tall dark cards side by side; the hovered, focused or tapped one rises and its colour climbs from the bottom — pure CSS.',
        ],
        'js' => false,
        'props' => [
            ['name' => 'members', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'کلیدها: name، role، avatar (آدرس تصویر؛ وگرنه حرف اول نام)، color (lapis | violet | cyan | gold یا هر رنگ CSS) و href.',
                'en' => 'Keys: name, role, avatar (an image URL; initials otherwise), color (lapis | violet | cyan | gold or any CSS colour), href.',
            ]],
            ['name' => 'label', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'نام دسترس‌پذیر گروه کارت‌ها.',
                'en' => 'The group’s accessible name.',
            ]],
            ['name' => 'nameAs / cardWidth', 'type' => 'string', 'default' => 'h3 / null', 'note' => [
                'fa' => 'تگ نام اعضا و عرض کارت‌ها.',
                'en' => 'The members’ heading tag and the card width.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::team-cards label="تیم ما" :members="[
            ['name' => 'کنجی ساتو', 'role' => 'رهبر طراحی', 'color' => 'violet'],
            ['name' => 'ماریا لوپز', 'role' => 'مهندسی داده', 'color' => 'cyan'],
        ]" />
        BLADE,
    ],

    'depth-carousel' => [
        'title' => ['fa' => 'کاروسل عمقی', 'en' => 'Depth carousel'],
        'icon' => 'image',
        'oneLiner' => [
            'fa' => 'کارت فعال در مرکز تمام‌قد است؛ همسایه‌ها کوچک‌تر، عقب‌تر و نرم‌تر. درگ، دکمه‌ها، نقطه‌ها و فلش‌های کیبورد — «بعدی» سمت انتهای خط است (در RTL چپ).',
            'en' => 'The active card centred and full size, its neighbours smaller, pushed back and softened. Drag, buttons, dots, arrow keys — “next” sits toward the inline end (left in RTL).',
        ],
        'js' => true,
        'props' => [
            ['name' => 'items', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'کلیدها: title، description، cover، image، alt، href و tone؛ اسلاتی هم‌نام کلید آیتم، اسلاید ساخته‌شده را جایگزین می‌کند.',
                'en' => 'Keys: title, description, cover, image, alt, href, tone; a slot named after an item key replaces the built-in slide.',
            ]],
            ['name' => 'index / wire:model', 'type' => 'int', 'default' => '0', 'note' => [
                'fa' => 'اسلاید جاری؛ wire:model.live اندیس را روی سرور به‌روز می‌کند.',
                'en' => 'The current slide; wire:model.live updates the index on the server.',
            ]],
            ['name' => 'loop', 'type' => 'bool', 'default' => 'true', 'note' => [
                'fa' => 'پس از آخرین اسلاید دور می‌زند.',
                'en' => 'Wraps around past the last slide.',
            ]],
            ['name' => 'dots / label', 'type' => 'bool / string', 'default' => 'true / null', 'note' => [
                'fa' => 'نقطه‌های ناوبری و برچسب دسترس‌پذیری.',
                'en' => 'The navigation dots and the accessible label.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::depth-carousel label="قصه‌های مشتریان" :items="$stories" wire:model.live="state.slide" />
        BLADE,
    ],

    'cycle-stack' => [
        'title' => ['fa' => 'دستهٔ چرخشی', 'en' => 'Cycle stack'],
        'icon' => 'bell',
        'oneLiner' => [
            'fa' => 'دستهٔ عمودی کارت‌ها: کارت رویی را پایین بکشید، کلیک کنید یا دکمه را بزنید تا با یک چرخش پشت دسته پنهان شود — مرکز اعلان‌ها در حجم یک کارت.',
            'en' => 'A vertical deck: drag the top card down, click it or press the button and it tucks in behind the others — a notification centre in the footprint of one card.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'items', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'کلیدها: icon، title، description، meta و tone؛ اسلاتی هم‌نام کلید، رویِ ساخته‌شده را جایگزین می‌کند.',
                'en' => 'Keys: icon, title, description, meta, tone; a slot named after a key replaces the built-in face.',
            ]],
            ['name' => 'index / wire:model', 'type' => 'int', 'default' => '0', 'note' => [
                'fa' => 'اندیس کارت جلو؛ با wire:model در گام با سرور.',
                'en' => 'The front card’s index; wire:model keeps it on the server.',
            ]],
            ['name' => 'nextLabel', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'متن دکمهٔ «کارت بعدی».',
                'en' => 'The “next card” button label.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::cycle-stack next-label="بعدی" wire:model="state.card" :items="[
            'deploy' => ['icon' => 'zap', 'tone' => 'cyan', 'title' => 'استقرار تمام شد', 'description' => 'نسخهٔ ۲٫۴ در همهٔ منطقه‌ها زنده است.', 'meta' => '۲ دقیقه پیش'],
        ]" />
        BLADE,
    ],

    'elastic-grid' => [
        'title' => ['fa' => 'گرید کشسان', 'en' => 'Elastic grid'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'گریدی که ستون‌هایش با فنرهای جداگانه دنبال اسکرول می‌آیند — عقب می‌مانند، کشیده می‌شوند و جا می‌گیرند. با حرکت کم ساکن است.',
            'en' => 'A grid whose columns follow the scroll on springs of their own: they trail, stretch and settle. With reduced motion it stays still.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'items', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'کلیدها: cover، image، alt، title، caption، ratio (مثلاً "4 / 5") و href؛ آیتم می‌تواند Htmlable یا اسلاتی هم‌نام کلیدش باشد.',
                'en' => 'Keys: cover, image, alt, title, caption, ratio (say "4 / 5"), href; an item may also be any Htmlable or a slot named after its key.',
            ]],
            ['name' => 'columns', 'type' => 'int', 'default' => '3', 'note' => [
                'fa' => 'تعداد ستون‌ها؛ آیتم‌ها به ترتیب بین آن‌ها پخش می‌شوند.',
                'en' => 'The column count; items are dealt into them in order.',
            ]],
            ['name' => 'lag / parallax', 'type' => 'int', 'default' => '90 / 40', 'note' => [
                'fa' => 'تأخیر فنر هر ستون (میلی‌ثانیه) و عمق پارالاکس (پیکسل).',
                'en' => 'Each column’s spring lag (ms) and the parallax depth (px).',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::elastic-grid :columns="3" :items="$cities" />
        BLADE,
    ],

    'workflow-card' => [
        'title' => ['fa' => 'کارت گردش‌کار', 'en' => 'Workflow card'],
        'icon' => 'cpu',
        'oneLiner' => [
            'fa' => 'خلاصهٔ یک گردش‌کار که با hover، فوکوس یا لمس باز می‌شود: جزئیات رشد می‌کنند و کنش‌ها یکی‌یکی سُر می‌خورند — خالص CSS.',
            'en' => 'A workflow summary that opens on hover, focus or tap: details grow in and the actions slide in one by one — pure CSS.',
        ],
        'js' => false,
        'props' => [
            ['name' => 'title / description', 'type' => 'string', 'default' => 'required / null', 'note' => [
                'fa' => 'نام گردش‌کار و توضیح یک‌خطی آن.',
                'en' => 'The workflow’s name and its one-line description.',
            ]],
            ['name' => 'status / statusTone', 'type' => 'string', 'default' => 'null / success', 'note' => [
                'fa' => 'متن وضعیت و رنگ آن: neutral | accent | success | warning | danger | info | gold.',
                'en' => 'The status text and its tone: neutral | accent | success | warning | danger | info | gold.',
            ]],
            ['name' => 'live', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'نقطهٔ وضعیت را نفس‌کش می‌کند — برای گردش‌کارهای در حال اجرا.',
                'en' => 'Makes the status dot breathe — for running workflows.',
            ]],
            ['name' => 'members / meta', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'members: [[\'name\' => …, \'src\' => …]] با آواتار گروهی؛ meta: [\'برچسب\' => \'مقدار\'] یا فهرستی از label/value.',
                'en' => 'members: [[\'name\' => …, \'src\' => …]] with a grouped avatar; meta: [\'Label\' => \'value\'] or a list of label/value.',
            ]],
            ['name' => 'slot:actions', 'type' => 'slot', 'default' => '—', 'note' => [
                'fa' => 'دکمه‌های کنش — مثلاً اجرا، ویرایش، تکثیر با wire:click.',
                'en' => 'The action buttons — run, edit, duplicate with wire:click.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::workflow-card title="همگام‌سازی شبانهٔ انبار" description="سفارش‌ها، موجودی، اعلان به تیم عملیات."
            status="در حال اجرا" live icon="cpu"
            :members="[['name' => 'کنجی ساتو'], ['name' => 'ماریا لوپز']]"
            :meta="['راه‌انداز' => 'هر روز · ۰۲:۰۰', 'آخرین اجرا' => '۳ دقیقه پیش']">
            <x-slot:actions>
                <x-nx::icon-button icon="play" label="اجرا" size="sm" wire:click="ping('گردش‌کار اجرا شد')" />
            </x-slot:actions>
        </x-nx::workflow-card>
        BLADE,
    ],

    'feature-card' => [
        'title' => ['fa' => 'کارت ویژگی', 'en' => 'Feature card'],
        'icon' => 'sparkles',
        'oneLiner' => [
            'fa' => 'کارتی با تصویرسازی کوچکِ متحرک بالایش؛ حلقه‌ها خالص CSS هستند و زیر حرکت کم می‌خوابند — چهار تصویرسازی آماده: صندوق ورودی، خلاصه، پردازش، تیم.',
            'en' => 'A card with a small animated illustration on top; the loops are pure CSS and rest under reduced motion — four built-ins: inbox, summary, processing, team.',
        ],
        'js' => false,
        'props' => [
            ['name' => 'visual', 'type' => 'string', 'default' => 'inbox', 'note' => [
                'fa' => 'inbox (نامه‌ها می‌رسند و مرتب می‌شوند) | summary (خطوط خوانده و جمع می‌شوند) | processing (رعد با حلقه‌ها و نوار پرشونده) | team (افراد در جای خود می‌نشینند).',
                'en' => 'inbox (mail arriving and re-sorting) | summary (lines read, then folded) | processing (a bolt with rings and a filling bar) | team (people moving into their slots).',
            ]],
            ['name' => 'title / description', 'type' => 'string', 'default' => 'required / null', 'note' => [
                'fa' => 'عنوان ویژگی و توضیح کوتاهش.',
                'en' => 'The feature’s title and its short description.',
            ]],
            ['name' => 'tone', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'رنگ صحنه: lapis | violet | cyan | gold.',
                'en' => 'The scene’s colour: lapis | violet | cyan | gold.',
            ]],
            ['name' => 'href / slot پیش‌فرض', 'type' => 'string / slot', 'default' => 'null', 'note' => [
                'fa' => 'با href کل کارت لینک می‌شود؛ اسلات پیش‌فرض زیر توضیح محتوا اضافه می‌کند.',
                'en' => 'With href the whole card becomes a link; the default slot adds content under the description.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::feature-card visual="inbox" tone="cyan" title="صندوق ورودی هوشمند" description="نامهٔ تازه بالا می‌آید و خودش مرتب می‌شود." />
        BLADE,
    ],

    'pit-slider' => [
        'title' => ['fa' => 'اسلایدر گودالی', 'en' => 'Pit slider'],
        'icon' => 'music',
        'oneLiner' => [
            'fa' => 'ردیفی از میله‌ها روی یک رِنج بومی: با hover بلند می‌شوند و هنگام کشیدن (یا راه‌اندازی با کلید) در گودی زیر thumb فرو می‌روند و مقدار بالایش شناور می‌ماند.',
            'en' => 'A row of sticks over a native range: they grow on hover and dip into a pit under the thumb while it is dragged (or nudged with the keys), the value floating above.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'label', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'برچسب دسترس‌پذیری رِنج — بالای اسلایدر نشان داده می‌شود.',
                'en' => 'The range’s accessible label — shown above the slider.',
            ]],
            ['name' => 'min / max / step / value', 'type' => 'number', 'default' => '—', 'note' => [
                'fa' => 'همهٔ attributeهای بومی به خود <input type="range"> می‌روند؛ wire:model و name هم همان‌جا می‌نشینند.',
                'en' => 'All native attributes land on the <input type="range"> itself; wire:model and name sit there too.',
            ]],
            ['name' => 'bars', 'type' => 'int', 'default' => '36', 'note' => [
                'fa' => 'تعداد میله‌ها.',
                'en' => 'How many sticks.',
            ]],
            ['name' => 'decimals / prefix / suffix', 'type' => 'int / string', 'default' => 'null / "" / ""', 'note' => [
                'fa' => 'خوانش مقدار: رقم اعشار و متن دو طرف آن (٪، دلار…).',
                'en' => 'How the value reads: fraction digits and the text on either side (%, $…).',
            ]],
            ['name' => 'dark', 'type' => 'bool', 'default' => 'true', 'note' => [
                'fa' => 'پنل تیره را در هر دو تم نگه می‌دارد؛ با :dark="false" از صفحه پیروی می‌کند.',
                'en' => 'Keeps the panel dark in both themes; :dark="false" follows the page.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::pit-slider label="صدا" min="0" max="100" value="42" suffix="٪" wire:model.live="state.volume" />
        BLADE,
    ],

    'precision-slider' => [
        'title' => ['fa' => 'اسلایدر دقیق', 'en' => 'Precision slider'],
        'icon' => 'sliders',
        'oneLiner' => [
            'fa' => 'اسلایدر با عدد بزرگ رولینگ، تیک‌های ریزِ پرشونده با مقدار و دو سر رِنج — برای مقادیری که باید دقیق تنظیم شوند.',
            'en' => 'The slider with a large rolling number, fine ticks that fill with the value, and the range’s two ends — for values that must be set precisely.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'label', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'برچسب بالا اسلایدر.',
                'en' => 'The label above the slider.',
            ]],
            ['name' => 'unit / prefix', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'متن دو طرف عدد رولینگ (واحد و پیشوند).',
                'en' => 'The text around the rolling number (unit and prefix).',
            ]],
            ['name' => 'decimals', 'type' => 'int', 'default' => 'null', 'note' => [
                'fa' => 'تعداد رقم اعشار عدد.',
                'en' => 'The number’s fraction digits.',
            ]],
            ['name' => 'ticks / majorEvery', 'type' => 'int', 'default' => 'null', 'note' => [
                'fa' => 'فاصلهٔ تیک‌ها و این‌که هر چند تیک یکی درشت شود.',
                'en' => 'The tick interval and how often a major one falls.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::precision-slider label="دما" min="0" max="2" step="0.01" value="0.7" decimals="2" wire:model.live="state.temperature" />
        BLADE,
    ],

    'infinite-grid' => [
        'title' => ['fa' => 'گرید بی‌پایان', 'en' => 'Infinite grid'],
        'icon' => 'wand',
        'oneLiner' => [
            'fa' => 'میدان نقطه‌ای رانده‌شونده با اسپات ماوس: نقطه‌هایی که نشانگر لمس می‌کند روشن می‌شوند و تراکم را می‌شود از گوشه‌اش کم و زیاد کرد — لایهٔ قهرمانِ متحرک.',
            'en' => 'An infinite drifting dot field with a pointer spotlight: the dots it touches light up and the density stepper in the corner tunes the mesh — a living hero layer.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'slot پیش‌فرض', 'type' => 'slot', 'default' => '—', 'note' => [
                'fa' => 'محتوای روی میدان — مثلاً تیتر و توضیح باکس قهرمانی.',
                'en' => 'The content over the field — say a hero’s heading and pitch.',
            ]],
            ['name' => 'cell', 'type' => 'int', 'default' => '28', 'note' => [
                'fa' => 'اندازهٔ سلول شبکه بر حسب پیکسل.',
                'en' => 'The mesh’s cell size in pixels.',
            ]],
            ['name' => 'min / max / step', 'type' => 'int', 'default' => '12 / 48 / 4', 'note' => [
                'fa' => 'دامنه و گام پلهٔ تراکم در گوشه.',
                'en' => 'The range and step of the corner density stepper.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div style="position: relative">
            <x-nx::infinite-grid>
                <h2>شبکه‌ای که تمامی ندارد</h2>
                <p>نشانگر را حرکت بده: نقطه‌هایی که لمس می‌کند روشن می‌شوند.</p>
            </x-nx::infinite-grid>
        </div>
        BLADE,
    ],

    'invoice' => [
        'title' => ['fa' => 'فاکتور', 'en' => 'Invoice'],
        'icon' => 'file',
        'oneLiner' => [
            'fa' => 'فاکتور کامل با سربرگ دوطرفه، ردیف‌هایی که یکی‌یکی بالا می‌آیند، جمع‌های رولینگ و حالت چاپ (@media print) — همه‌چیز از همان ردیف‌ها حساب می‌شود.',
            'en' => 'A full invoice with a two-sided letterhead, rows that rise one at a time, rolling totals and a print mode (@media print) — everything is computed from the lines.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'lines', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر ردیف: title، description، quantity، unitPrice (یا amount مستقیم)؛ جمع‌ها از همین‌ها حساب می‌شوند.',
                'en' => 'Each row: title, description, quantity, unitPrice (or a direct amount); the totals are computed from them.',
            ]],
            ['name' => 'extraTotals', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'پله‌های بین جمع کل و نهایی: [\'label\' => …, \'amount\' => …] یا [\'percent\' => 0.09] از جمع ردیف‌ها.',
                'en' => 'Steps between the subtotal and the total: [\'label\' => …, \'amount\' => …] or [\'percent\' => 0.09] of the subtotal.',
            ]],
            ['name' => 'status / statusLabel', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'paid | unpaid | overdue | draft؛ نشان وضعیت خودش رنگ و متن درست را می‌گیرد.',
                'en' => 'paid | unpaid | overdue | draft; the badge takes the right colour and words itself.',
            ]],
            ['name' => 'from / to', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'دو طرف فاکتور: [\'name\' => …, \'lines\' => [نشانی، ایمیل، شناسه مالی…]].',
                'en' => 'Both sides: [\'name\' => …, \'lines\' => [address, email, tax id…]].',
            ]],
            ['name' => 'currency / decimals', 'type' => 'string / int', 'default' => 'null / 0', 'note' => [
                'fa' => 'واحد پول (متن آزاد مثل «تومان») و ارقام اعشار جمع‌ها.',
                'en' => 'The currency (free text like “تومان”) and the totals’ fraction digits.',
            ]],
            ['name' => 'note / footer', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'یادداشت پای فاکتور و پانوشت نهایی.',
                'en' => 'The note at the foot and the closing footer line.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::invoice number="INV-1404-082" status="unpaid" currency="تومان"
            :from="['name' => 'استودیو نابو', 'lines' => ['تهران، ایران', 'hello@nabu.studio']]"
            :to="['name' => 'شرکت آدم', 'lines' => ['accounts@acme.ir']]"
            :lines="[
                ['title' => 'سیستم طراحی', 'quantity' => 1, 'unitPrice' => 480000000],
                ['title' => 'پشتیبانی ماهانه', 'quantity' => 3, 'unitPrice' => 25000000],
            ]"
            :extra-totals="[['label' => 'مالیات بر ارزش افزوده (۹٪)', 'percent' => 0.09]]" />
        BLADE,
    ],
];
