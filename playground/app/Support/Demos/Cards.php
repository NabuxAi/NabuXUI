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

    'product-card' => [
        'title' => ['fa' => 'کارت محصول', 'en' => 'Product card'],
        'icon' => 'heart',
        'oneLiner' => [
            'fa' => 'کارت فروشگاهی با تصویری که در قاب خودش زوم می‌شود، قیمتِ قبل از تخفیف خط‌خورده، ستاره‌هایی که تا کسرِ امتیاز پر می‌شوند و نشان موجودی (کم‌موجود نفس می‌کشد و تعدادِ مانده را می‌گوید)؛ دکمهٔ افزودن به سبد با فنر به حالت «اضافه شد» مورف می‌شود.',
            'en' => 'The shop card: an image that zooms inside its frame on hover, the crossed-out pre-discount price, stars that fill to the fraction of the rating and a stock badge (the low one breathes and counts the units left); the add-to-cart button morphs into its added state on a spring.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'title / category / href', 'type' => 'string', 'default' => 'required / null / null', 'note' => [
                'fa' => 'نام محصول (دستهٔ کوچک بالایش) و نشانی صفحهٔ محصول؛ با href عنوان یک لینک واقعی می‌شود.',
                'en' => 'The product’s name (its small category above) and the product page’s URL; with href the title becomes a real link.',
            ]],
            ['name' => 'image', 'type' => 'array', 'default' => 'null', 'note' => [
                'fa' => "['src' => …, 'alt' => …] — قاب ۴:۵ که تصویر در آن زوم می‌کند؛ بدون تصویر، تگ‌های تخفیف/موجودی به‌جای روی عکس، لبهٔ بالای بدنه می‌نشینند.",
                'en' => "['src' => …, 'alt' => …] — a 4:5 frame the image zooms inside; without an image the discount/stock tags ride the body’s top edge instead of over a photo.",
            ]],
            ['name' => 'price / compareAt / currency / decimals', 'type' => 'number / number / string / int', 'default' => '0 / null / null / 0', 'note' => [
                'fa' => 'قیمت و قیمتِ قبل از تخفیف (خط می‌خورد و درصدِ پس‌انداز از خود همین دو حساب می‌شود)، واحد پول (متن آزاد مثل «تومان») و ارقام اعشار — همه با ارقام زبان صفحه.',
                'en' => 'The price and the pre-discount one (crossed out, its whole-percent saving computed from the pair), the currency (free text like “toman”) and the fraction digits — all in the page’s own digits.',
            ]],
            ['name' => 'rating / ratingCount', 'type' => 'number / int', 'default' => 'null', 'note' => [
                'fa' => 'امتیاز ۰ تا ۵ — ستاره‌ها تا کسر آن پر می‌شوند و برای صفحه‌خوان «X از ۵» خوانده می‌شود؛ ratingCount شمار نظرها با واژهٔ خود زبان.',
                'en' => 'A 0–5 rating — the stars fill to its fraction and screen readers get “X of 5”; ratingCount tallies the reviews in the reader’s own word.',
            ]],
            ['name' => 'stock / stockCount', 'type' => 'string / int', 'default' => 'null', 'note' => [
                'fa' => 'in | low | out — «موجود»، «فقط N عدد مانده» (نشان نفس‌کش) و «ناموجود» که دکمه را ازکار می‌اندازد؛ stockCount فقط با low معنا دارد.',
                'en' => 'in | low | out — in stock, “only N left” (a breathing badge) and out, which disables the button; stockCount only matters for low.',
            ]],
            ['name' => 'inCart / addAction / removeAction', 'type' => 'bool / string', 'default' => 'false / null / null', 'note' => [
                'fa' => 'in-cart مرجع حقیقت از بیرون است؛ بدون اکشن‌ها همه‌چیز سمت مرورگر می‌ماند و رویدادهای nx-add / nx-remove (با title و price) حباب می‌کنند. با add-action="addToCart" متد لایووایر شما پس از چرخش خوش‌بینانه با (title, price) صدا زده می‌شود و دکمه تا پایان رفت‌وبرگشت درنگ می‌کند.',
                'en' => 'in-cart is the truth from outside; without the actions everything stays client-side and the nx-add / nx-remove events (carrying title and price) bubble. With add-action="addToCart" your Livewire method fires after the optimistic flip with (title, price), the button holding still for the round-trip.',
            ]],
            ['name' => 'labels / locale', 'type' => 'array / string', 'default' => '[] / زبان اپ', 'note' => [
                'fa' => 'بازنویسی واژه‌های داخلی (افزودن/اضافه شد/حذف/موجودی‌ها با جای‌نشین {count} و {value}) و زبان ارقام و واژه‌ها — واژه‌های پیش‌فرض از i18n هسته (fa/en/ar).',
                'en' => 'Overrides for the built-in words (add/added/remove and the stock states with {count} and {value} placeholders) and the digits-and-words locale — defaults come from the core i18n table (fa/en/ar).',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::product-card title="هدفون بی‌سیم نابو B2" category="صدا" href="#"
            :image="['src' => $photo, 'alt' => 'هدفون نابو B2، نمای روبه‌رو']"
            :price="4850000" :compareAt="6900000" currency="تومان"
            :rating="4.5" :ratingCount="1284" stock="in" />

        {{-- کم‌موجود: نشانِ نفس‌کش با تعدادِ مانده می‌سازد --}}
        <x-nx::product-card title="اسپیکر کتابی نابو Mini" :price="2190000" currency="تومان"
            stock="low" :stockCount="2" />

        {{-- متصل به لایووایر: متد شما پس از چرخش خوش‌بینانه صدا زده می‌شود --}}
        <x-nx::product-card title="پایهٔ شارژ بی‌سیم نابو" :price="890000" currency="تومان"
            add-action="addToCart" remove-action="removeFromCart" />

            {{-- بدون اتصال: رویدادها را خودتان می‌گیرید --}}
        <x-nx::product-card :price="149000" currency="تومان" title="کابل تخت نابو"
            x-on:nx-add="$wire.ping('به سبد اضافه شد')" />
        BLADE,
    ],

    'order-tracking' => [
        'title' => ['fa' => 'رهگیری سفارش', 'en' => 'Order tracking'],
        'icon' => 'zap',
        'oneLiner' => [
            'fa' => 'مسیر سفارش به‌صورت یک خط افقی که تا گام جاری پر می‌شود (نشانگر پینگ‌زن در حین حرکت)، گزارش رویدادها با دوخت رنگی زیرش و وضعیت زنده روی نشان — با wire:poll بدون کلیک تازه می‌شود.',
            'en' => 'Where an order is and how it got there: a horizontal route that fills up to the current step (a pinging marker while it travels), a tone-stitched event log beneath, and the live status on a badge — refreshes itself under wire:poll.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'steps', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'کلیدهای هر گام: id، title، description، icon، time (تاریخ → زمان نسبی، یا برچسب) و state (complete | current | upcoming) که بر current مقدم است.',
                'en' => 'Step keys: id, title, description, icon, time (a date → relative time, or a plain label) and state (complete | current | upcoming), which wins over current.',
            ]],
            ['name' => 'current', 'type' => 'string | int', 'default' => 'null', 'note' => [
                'fa' => 'شناسه یا اندیس گام جاری؛ گام‌های قبلش کامل و بعدش در انتظار خوانده می‌شوند.',
                'en' => 'The current step’s id or index; the steps before it read as complete, the ones after as upcoming.',
            ]],
            ['name' => 'events', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'کلیدها: id، title، description، place، tone (success | warning | info | danger)، icon و time؛ خالی باشد، خود بلوک خبر می‌دهد.',
                'en' => 'Keys: id, title, description, place, tone (success | warning | info | danger), icon, time; empty and the block says so itself.',
            ]],
            ['name' => 'status / statusLabel / live', 'type' => 'string / string / bool', 'default' => 'null / null / true', 'note' => [
                'fa' => 'وضعیت روی status-badge: running | success | failed | queued | canceled؛ متن دلخواه و این‌که نقطهٔ وضعیت نفس بکشد.',
                'en' => 'The status on the badge: running | success | failed | queued | canceled; a custom label and whether its dot breathes.',
            ]],
            ['name' => 'number / carrier / eta', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'شمارهٔ سفارش (عدد باشد با ارقام همان زبان قالب‌بندی می‌شود)، شرکت حمل و تحویل تخمینی در سربرگ.',
                'en' => 'The order number (numeric ones get locale digits), the carrier and the expected delivery in the header.',
            ]],
            ['name' => 'label / labels / locale', 'type' => 'string / array / string', 'default' => 'null / [] / null', 'note' => [
                'fa' => 'نام دسترس‌پذیر کارت، بازنویسی واژه‌های خود بلوک و locale ارقام و تاریخ‌ها.',
                'en' => 'The card’s accessible name, overrides for the block’s own words, and the locale for digits and dates.',
            ]],
            ['name' => 'wire:poll و x-on:*', 'type' => 'attribute', 'default' => '—', 'note' => [
                'fa' => 'هر attribute سیمی/رویدادی به ریشه می‌رسد — wire:poll.30s="checkOrder" کل بلوک را با state تازه سرور مورف می‌کند.',
                'en' => 'Any wire/event attribute lands on the root — wire:poll.30s="checkOrder" morphs the whole block into its fresh server state.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::order-tracking number="۱۴۰۴-۰۸۲۱۵" status="running" carrier="پست ایران" eta="۲ روز دیگر"
            :steps="[
                ['id' => 'placed', 'title' => 'ثبت سفارش', 'icon' => 'file', 'time' => now()->subDays(2)],
                ['id' => 'transit', 'title' => 'در مسیر', 'icon' => 'zap'],
                ['id' => 'done', 'title' => 'تحویل شد', 'icon' => 'home'],
            ]"
            current="transit"
            :events="[['title' => 'بسته از مرکز توزیع خارج شد', 'place' => 'قم', 'time' => now()->subHours(2), 'tone' => 'success']]" />
        BLADE,
    ],

    'profile-card' => [
        'title' => ['fa' => 'کارت پروفایل', 'en' => 'Profile card'],
        'icon' => 'user',
        'oneLiner' => [
            'fa' => 'پروفایل روی کاور گرادیانی: آواتار روی درز کاور می‌نشیند، آمارها ارقام‌شان را می‌غلتانند، دکمهٔ دنبال‌کردن با تعویض آیکون و کراس‌فید برچسب بین دو حالت مورف می‌شود و تب‌های کوچک نشانگر فنری می‌آورند.',
            'en' => 'A profile over a gradient cover: the avatar rides the cover/body seam, the stats roll their digits, the follow button morphs between its two states (icon swap + label crossfade) and the small tabs carry a springing indicator.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'name / href', 'type' => 'string', 'default' => 'required / null', 'note' => [
                'fa' => 'نام پروفایل — حرف‌های اولش آواتارِ بدون عکس را می‌سازند؛ با href نام به مقصد لینک می‌شود.',
                'en' => 'The profile’s name — its first letters become the photo-less avatar; with href the name becomes a link.',
            ]],
            ['name' => 'role / handle', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'نقش زیر نام و هندل مونو (@…) کنار آن.',
                'en' => 'The role under the name and the mono @handle beside it.',
            ]],
            ['name' => 'avatar / cover', 'type' => 'array', 'default' => 'null', 'note' => [
                'fa' => "['src' => …, 'alt' => …]: عکس آواتار روی درز کاور و بدنه می‌نشیند و عکسِ کاور روی گرادیان برند می‌افتد؛ اگر عکس آواتار لود نشود، حرف‌های اول نام می‌آیند.",
                'en' => "['src' => …, 'alt' => …]: the avatar photo rides the cover/body seam and the cover photo sits over the brand gradient; a failed avatar image falls back to the name’s initials.",
            ]],
            ['name' => 'status / verified', 'type' => 'string / bool', 'default' => 'null / false', 'note' => [
                'fa' => 'نقطهٔ حضور روی لبهٔ آواتار (online | busy | away — فقط «آنلاین» نفس می‌کشد) و مهر طلایی تأیید کنار نام.',
                'en' => 'The presence dot on the avatar’s rim (online | busy | away — only “online” breathes) and the gold verified seal beside the name.',
            ]],
            ['name' => 'stats / followersStat', 'type' => 'array / int', 'default' => '[] / null', 'note' => [
                'fa' => 'ردیف‌های [\'label\' => …, \'value\' => …] با ارقام خود صفحه؛ followersStat اندیسِ آمارِ دنبال‌کننده است که با دکمه ±۱ می‌غلتد تا عددِ خود سرور برسد.',
                'en' => 'Rows of [\'label\' => …, \'value\' => …] in the page’s own digits; followersStat is the index of the follower figure that rolls ±1 with the button until the server’s number lands.',
            ]],
            ['name' => 'following / followAction / unfollowAction', 'type' => 'bool / string', 'default' => 'false / null', 'note' => [
                'fa' => 'دکمه از حالت دنبال‌شده شروع می‌کند (حقیقت، رندر سرور است)؛ با نام متد لایووایر، پس از چرخش خوش‌بینانه صدا زده می‌شود و دکمه تا رفت‌وبرگشت درنگ می‌کند؛ بدون آن‌ها رویداد nx-follow / nx-unfollow حباب می‌کند.',
                'en' => 'The button starts in its followed state (the server’s render is the truth); naming a Livewire method calls it after the optimistic flip while the button waits the round-trip out — without them the nx-follow / nx-unfollow events bubble.',
            ]],
            ['name' => 'messageHref / messageAction', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'دکمهٔ پیام: با message-href یک لینک می‌شود، با message-action متد لایووایر صدا زده می‌شود؛ بدون هر دو رویداد nx-message.',
                'en' => 'The message button: message-href turns it into a link, message-action calls a Livewire method; without either, the nx-message event.',
            ]],
            ['name' => 'tabs / tab / labels', 'type' => 'array / string / array', 'default' => '[] / null / []', 'note' => [
                'fa' => "تب‌ها به‌شکل ['about' => 'درباره'] با اسلاتی هم‌نام هر کلید؛ تب نخست باز است و نشانگر فنری روی انتخاب می‌لغزد؛ labels واژه‌های خود کارت و locale ارقام و اعلام‌ها را بازنویسی می‌کند.",
                'en' => "Tabs as ['about' => 'About'] with a slot named after each key; the first tab opens and a springing indicator slides onto the choice; labels overrides the card’s own words and locale the digits and announcements.",
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::profile-card name="نگار رستمی" role="نویسنده و مدرس تایپوگرافی فارسی" handle="@negar-types"
            href="#" status="online" verified
            :avatar="['src' => $avatar, 'alt' => '']" :cover="['src' => $cover, 'alt' => '']"
            :stats="[
                ['label' => 'دنبال‌کننده', 'value' => 12840],
                ['label' => 'دنبال‌شده', 'value' => 812],
                ['label' => 'نوشته', 'value' => 146],
            ]"
            :followers-stat="0" :tabs="['about' => 'درباره', 'activity' => 'فعالیت']"
            x-on:nx-follow="$wire.ping('دنبال شد')" x-on:nx-unfollow="$wire.ping('لغو شد')">
            <x-slot:about><p>…زندگی‌نامه…</p></x-slot:about>
            <x-slot:activity><ul>…کارهای تازه…</ul></x-slot:activity>
        </x-nx::profile-card>
        BLADE,
    ],

    'wizard' => [
        'title' => ['fa' => 'فرم چندگامی (ویزارد)', 'en' => 'Wizard'],
        'icon' => 'check-circle',
        'oneLiner' => [
            'fa' => 'پوستهٔ فرم چندگامی: نوار پیشرفت، گام‌های تمام‌شده که می‌توان به آن‌ها بازپرید، پن‌هایی که می‌لغزند و ارتفاع کارت مورف می‌شود، اعتبارسنجیِ خودِ فرم در هر گام (لرزش + خطا + فوکوس) و پن بازبینی که پاسخ‌ها را از خودِ کنترل‌ها می‌خواند.',
            'en' => 'A multi-step form shell: a progress bar, completed steps you can jump back to, panes that glide while the card’s height morphs, the form’s own validation on every hop (shake, error, focus) and a review pane that reads the answers back from the controls.',
        ],
        'js' => true,
        'props' => [
            ['name' => 'steps', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر گام: [\'id\' => …, \'title\' => …, \'description\' => …]؛ فیلدهای هر گام در اسلاتی به نام step + StudlyCase شناسه‌اش می‌نشیند (stepAccount برای id account) و وقتی id ندارد، step1، step2 و…',
                'en' => 'Each step: [\'id\' => …, \'title\' => …, \'description\' => …]; its fields go in the slot named step + the StudlyCase of its id (stepAccount for id account), or step1, step2, … when it has none.',
            ]],
            ['name' => 'review', 'type' => 'bool', 'default' => 'true', 'note' => [
                'fa' => 'پن بازبینی پایانی با دکمهٔ ویرایش هر گام؛ با false آخرین گام خودش دکمهٔ ثبت می‌شود.',
                'en' => 'The final review pane with a per-step edit button; with false the last step carries the submit itself.',
            ]],
            ['name' => 'step', 'type' => 'int', 'default' => '0', 'note' => [
                'fa' => 'گام آغازین؛ برای ادامهٔ فرم نیمه‌کاره — گام‌های پیشین «تمام‌شده» رندر می‌شوند و دکمهٔ بازگشت‌شان فعال است.',
                'en' => 'The opening step; for resuming a draft — the steps before it render as completed with their jump-back buttons live.',
            ]],
            ['name' => 'labels / locale', 'type' => 'array / string', 'default' => '[] / null', 'note' => [
                'fa' => 'بازنویسی واژه‌های خود ویزارد برای همین نمونه (review، submit، progress، of، announce، invalid، edit، editStep، empty، yes، back، next) و زبان ارقام و اعلام‌ها؛ پیش‌فرض‌ها از i18n هسته می‌آیند.',
                'en' => 'Override the wizard’s own words for one instance (review, submit, progress, of, announce, invalid, edit, editStep, empty, yes, back, next) and the locale of its digits and announcements; the defaults come from the core i18n.',
            ]],
            ['name' => 'wire:submit / action', 'type' => '—', 'default' => 'null', 'note' => [
                'fa' => 'ریشه یک <form> ساده است و اتریبیوت‌ها روی آن می‌نشینند؛ «بعدی» تا گذشتن گامِ جلو از اعتبارسنجی خود فرم رد نمی‌شود و ثبت نهایی همهٔ پن‌ها را فعال می‌کند تا هیچ پاسخی گم نشود — ورودی‌های wire:model داخل پن‌ها کار می‌کنند.',
                'en' => 'The root is a plain <form> and attributes land on it; “next” is refused until the step in view passes the form’s own validation, and the final submit re-enables every pane first so no answer is dropped — wire:model inputs work inside the panes.',
            ]],
            ['name' => 'رویدادها / data-nx-summary', 'type' => '—', 'default' => '—', 'note' => [
                'fa' => 'nx-step-change با هر پرش (همراه اندیس گام) و nx-submit درست پیش از ارسال حباب می‌کند؛ data-nx-summary روی هر کنترل پرسشِ همان ردیف را در بازبینی می‌دهد (off آن را از فهرست بیرون می‌گذارد).',
                'en' => 'nx-step-change bubbles on every hop (carrying the step index) and nx-submit just before the form goes out; data-nx-summary on a control names its row in the review (off leaves it out).',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::wizard heading="ساخت فضای کاری" subtitle="سه گام و آماده"
            :steps="[
                ['id' => 'identity', 'title' => 'هویت', 'description' => 'نام و زمینهٔ کار'],
                ['id' => 'team', 'title' => 'همکاران', 'description' => 'دعوت و نقش‌ها'],
            ]"
            wire:submit="save('فضای کاری ساخته شد')">
            <x-slot:stepIdentity>
                <x-nx::input name="wz-name" label="نام فضای کاری" required data-nx-summary="نام فضای کاری" />
            </x-slot:stepIdentity>
            <x-slot:stepTeam><!-- …فیلدهای گام دوم… --></x-slot:stepTeam>
        </x-nx::wizard>
        BLADE,
    ],
];
