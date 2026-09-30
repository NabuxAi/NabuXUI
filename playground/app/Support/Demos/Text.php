<?php

/**
 * Demo manifest of the "text" group — typographic motion: headlines that spring
 * in, links that roll under the pointer, sections that gather or decode with
 * the scroll, the pulsing round CTA and the decorative backdrops they sit on.
 * The file name is the group id, every key below is a demo slug and its
 * partial lives at resources/views/demos/components/text/{slug}.blade.php.
 */
return [
    '__group' => ['fa' => 'متن', 'en' => 'Text'],

    'text-hero' => [
        'title' => ['fa' => 'تیتر قهرمان متن', 'en' => 'Text hero'],
        'icon' => 'star',
        'oneLiner' => [
            'fa' => 'تیترِ صفحه که حروفش با فنر سرِ جای خود می‌نشینند — فارسی و عربی کلمه‌به‌کلمه، لاتین حرف‌به‌حرف — و بخشی از آن گرادیان برند می‌گیرد.',
            'en' => 'The page headline whose letters spring into place — Persian and Arabic word by word, Latin letter by letter — with a slice of it on the brand gradient.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'title', 'type' => 'string', 'default' => "''", 'note' => [
                'fa' => 'تیتر اصلی؛ متن پنهانِ دسترس‌پذیری همان این متن است و نسخهٔ متحرک aria-hidden می‌شود.',
                'en' => 'The headline itself; the accessible text stays whole and the animated copy is aria-hidden.',
            ]],
            ['name' => 'highlight', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'قطعه‌ای از تیتر که گرادیان برند می‌گیرد؛ باید عیناً زیررشتهٔ title باشد.',
                'en' => 'The slice of the title that takes the brand gradient; must be an exact substring of title.',
            ]],
            ['name' => 'subtitle', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'خط توضیح زیر تیتر؛ با اسلات actions کنارش می‌آید.',
                'en' => 'The supporting line under the title; pairs with the actions slot.',
            ]],
            ['name' => 'stickers', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'برچسب‌های تزئینی دور تیتر: shape یکی از star · sparkle · heart · bolt · smiley · arrow، position یکی از top-start · top-end · bottom-start · bottom-end · start · end و tone یکی از accent · gold · violet · cyan · pink.',
                'en' => 'Decorative stickers around the title: shape one of star · sparkle · heart · bolt · smiley · arrow, position one of top-start · top-end · bottom-start · bottom-end · start · end and tone one of accent · gold · violet · cyan · pink.',
            ]],
            ['name' => 'align', 'type' => 'string', 'default' => "'center'", 'note' => [
                'fa' => "با 'start' تیتر و برچسب‌ها به ابتدای خط می‌چسبند — برای هیروهای داخل کارت.",
                'en' => "With 'start' the block hugs the reading start — for heroes inside a card.",
            ]],
            ['name' => 'as', 'type' => 'string', 'default' => "'h1'", 'note' => [
                'fa' => 'سطح عنوان (h1 · h2 · …)؛ برای هیروهای فرعی h2 بگذارید.',
                'en' => 'The heading level (h1 · h2 · …); use h2 for secondary heroes.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::text-hero as="h1" title="با هر زبانی که می‌شناسی بنویس" highlight="هر زبانی"
            subtitle="سلام · Bonjour · こんにちは — یک تیتر و همهٔ حروف با فنر سرِ جای خود می‌نشینند."
            :stickers="[
                ['shape' => 'star', 'position' => 'top-start'],
                ['shape' => 'sparkle', 'position' => 'top-end'],
                ['shape' => 'heart', 'position' => 'bottom-end', 'tone' => 'pink'],
            ]">
            <x-slot:actions>
                <x-nx::button variant="primary" shape="pill" icon-end="arrow-right" wire:click="save">
                    شروع نوشتن
                </x-nx::button>
                <x-nx::button variant="ghost" shape="pill">نمونه‌ها</x-nx::button>
            </x-slot:actions>
        </x-nx::text-hero>
        BLADE,
    ],

    'roll-text' => [
        'title' => ['fa' => 'متن غلتان', 'en' => 'Roll text'],
        'icon' => 'zap',
        'oneLiner' => [
            'fa' => 'هاور روی هر حرف، آن را بالا می‌فرستد و کپی‌اش را از پایین می‌آورد — در ترتیب خواندن و کلمه‌به‌کلمه در فارسی. خالص CSS.',
            'en' => 'On hover each letter rolls up and out while its copy rolls in from below — in reading order, word by word in Persian. Pure CSS.',
        ],
        'js' => false,
        'docs' => null,
        'props' => [
            ['name' => 'href', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'با href یک لینک <a> می‌شود؛ بدون آن as تعیین می‌کند span باشد یا button.',
                'en' => 'With href it renders an <a>; without it, `as` decides between span and button.',
            ]],
            ['name' => 'as', 'type' => 'string', 'default' => "'span'", 'note' => [
                'fa' => "span پیش‌فرض است؛ as=\"button\" یک دکمهٔ واقعی می‌سازد که wire:click می‌گیرد.",
                'en' => 'span by default; as="button" makes a real button that can take wire:click.',
            ]],
            ['name' => 'type', 'type' => 'string', 'default' => "'button'", 'note' => [
                'fa' => 'نوع دکمه وقتی as=button است.',
                'en' => 'The button type when as="button".',
            ]],
            ['name' => 'static', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'فشردن فنری هنگام کلیک را خاموش می‌کند — برای ردیف‌های شلوغ.',
                'en' => 'Drops the springy press scaling — for dense rows.',
            ]],
        ],
        'code' => <<<'BLADE'
        <nav aria-label="ناوبری سایت" style="display: flex; gap: 0 .25rem; font-weight: 700">
            <x-nx::roll-text href="/work">نمونه‌کارها</x-nx::roll-text>
            <x-nx::roll-text href="/studio">استودیو</x-nx::roll-text>
            <x-nx::roll-text href="/journal">ژورنال</x-nx::roll-text>
            <x-nx::roll-text as="button" wire:click="ping('منو')">تماس</x-nx::roll-text>
        </nav>
        BLADE,
    ],

    'hover-reveal' => [
        'title' => ['fa' => 'لینک‌های درخشان هاور', 'en' => 'Hover reveal'],
        'icon' => 'arrow-right',
        'oneLiner' => [
            'fa' => 'فهرست بزرگ لینک‌ها؛ آن‌که زیر موس یا فوکوس است همان‌جا می‌درخشد و فلشش می‌چرخد، بقیه کم‌رنگ و کمی دور می‌شوند.',
            'en' => 'A list of large links; the hovered or focused one glows where the pointer is and its arrow turns while the rest fade, blur and drift away.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'items', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر آیتم: label (لازم)، href و description (اختیاری) که زیر برچسب می‌آید.',
                'en' => 'Each item: label (required), href and an optional description shown under the label.',
            ]],
            ['name' => 'static', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'جابه‌جایی و مقیاسِ ردیف‌های کناری را خاموش می‌کند؛ فقط درخشش می‌ماند.',
                'en' => 'Drops the drift and scaling of the resting rows; only the glow stays.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::hover-reveal :items="[
            ['label' => 'طراحی محصول', 'href' => '/services/design', 'description' => 'رابط‌هایی که حس حرکت دارند'],
            ['label' => 'حرکت و تعامل', 'href' => '/services/motion'],
            ['label' => 'ساخت فرانت‌اند', 'href' => '/services/build', 'description' => 'Livewire، Inertia و React'],
        ]" />
        BLADE,
    ],

    'scroll-text-reveal' => [
        'title' => ['fa' => 'جمع‌شدن متن با اسکرول', 'en' => 'Scroll text reveal'],
        'icon' => 'arrow-up',
        'oneLiner' => [
            'fa' => 'بخشِ بلندِ پین‌شده: حروف پراکنده با اسکرول جمع می‌شوند و متنِ پرشده روی خطِ کم‌رنگش می‌نشیند؛ در حرکتِ کم، متنِ تمام‌شده دیده می‌شود.',
            'en' => 'A tall pinned section: scattered letters gather as you scroll and the filled text wipes in over its own outline; under reduced motion the finished text shows.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'text', 'type' => 'string', 'default' => "''", 'note' => [
                'fa' => 'جمله‌ای که اسکرول آن را می‌سازد؛ متن کامل در نسخهٔ پنهان برای صفحه‌خوان‌ها هست.',
                'en' => 'The sentence the scroll assembles; the whole text also lives in the screen-reader copy.',
            ]],
            ['name' => 'eyebrow', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'خط کوچک بالای تیتر — معمولاً دعوت به اسکرول.',
                'en' => 'The small line above the title — usually an invitation to scroll.',
            ]],
            ['name' => 'height', 'type' => 'string', 'default' => "'240vh'", 'note' => [
                'fa' => 'ارتفاع بخش؛ بلندتر یعنی جمع‌شدن آرام‌تر و نرم‌تر.',
                'en' => 'The section height; taller means a slower, gentler gather.',
            ]],
            ['name' => 'seed', 'type' => 'int', 'default' => '1', 'note' => [
                'fa' => 'دانهٔ پراکندگی حروف را عوض می‌کند تا دو بخشِ مشابه یکسان نپراکنند.',
                'en' => 'Scatters the letters another way so two similar sections never look alike.',
            ]],
            ['name' => 'as', 'type' => 'string', 'default' => "'h2'", 'note' => [
                'fa' => 'سطح عنوان تیتر پین‌شده.',
                'en' => 'The heading level of the pinned title.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::scroll-text-reveal
            text="هر کلمه جای خودش را پیدا می‌کند"
            eyebrow="اسکرول کنید · Scroll"
            height="220vh" />
        BLADE,
    ],

    'scroll-scramble' => [
        'title' => ['fa' => 'رمزگشایی متن با اسکرول', 'en' => 'Scroll scramble'],
        'icon' => 'wand',
        'oneLiner' => [
            'fa' => 'تیترِ پین‌شده که با اسکرول «دی‌کد» می‌شود — فارسی با حروف خودش — و چیپ‌ها از یک پشتهٔ پهلو به یک ردیف مرتب می‌پرند.',
            'en' => 'A pinned headline that “decodes” as you scroll — Persian with its own letters — while chips fly out of a side pile into a tidy row.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'title', 'type' => 'string', 'default' => "''", 'note' => [
                'fa' => 'تیتری که رمزگشایی می‌شود؛ خطِ هر زبان با حروف همان خط شلوغ می‌شود.',
                'en' => 'The headline being decoded; each script scrambles with letters of its own.',
            ]],
            ['name' => 'items', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'چیپ‌های زیر تیتر؛ از پشتهٔ پهلو شروع می‌کنند و با پیشرفت اسکرول سرِ ردیف می‌نشینند.',
                'en' => 'The chips under the title; they start as a side pile and settle into a row with the scroll.',
            ]],
            ['name' => 'height', 'type' => 'string', 'default' => "'220vh'", 'note' => [
                'fa' => 'ارتفاع بخش پین‌شده.',
                'en' => 'The height of the pinned section.',
            ]],
            ['name' => 'charset', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'مجموعهٔ حروف رمز: latin · persian · cuneiform یا هر رشتهٔ دلخواه؛ خالی یعنی خودکار از روی زبان تیتر.',
                'en' => 'The scramble alphabet: latin · persian · cuneiform or any custom string; empty picks one from the title’s script.',
            ]],
            ['name' => 'as', 'type' => 'string', 'default' => "'h2'", 'note' => [
                'fa' => 'سطح عنوان تیتر.',
                'en' => 'The heading level of the title.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::scroll-scramble
            title="رمزگشایی هر خط"
            :items="['فارسی', 'العربية', 'English', '日本語']"
            height="200vh" />
        BLADE,
    ],

    'pulse-button' => [
        'title' => ['fa' => 'دکمهٔ پالسی', 'en' => 'Pulse button'],
        'icon' => 'play',
        'oneLiner' => [
            'fa' => 'CTAی گرد با حلقه‌هایی که از آن بیرون می‌زنند و به سمت موس کشیده می‌شود؛ برچسب زیر دیسک می‌نشیند و بدون برچسب به aria-label نیاز دارد.',
            'en' => 'A round CTA with rings pulsing out of it, pulled toward the pointer; the label sits under the disc and without one it needs an aria-label.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'icon', 'type' => 'string', 'default' => "'arrow-right'", 'note' => [
                'fa' => 'نشانه روی دیسک؛ نام آیکون هسته یا SVG خودتان.',
                'en' => 'The glyph on the disc; a core icon name or your own SVG.',
            ]],
            ['name' => 'size', 'type' => 'string', 'default' => "'md'", 'note' => [
                'fa' => 'اندازه‌ها: sm · md · lg.',
                'en' => 'Sizes: sm · md · lg.',
            ]],
            ['name' => 'tone', 'type' => 'string', 'default' => "'accent'", 'note' => [
                'fa' => 'رنگ دیسک و حلقه‌ها: accent · gold · inverse.',
                'en' => 'The disc and rings colour: accent · gold · inverse.',
            ]],
            ['name' => 'href', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'با href به‌جای دکمه، لینک می‌شود.',
                'en' => 'With href it renders a link instead of a button.',
            ]],
            ['name' => 'static', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'کشش به سمت موس و مقیاس هاور/فشار را خاموش می‌کند.',
                'en' => 'Turns off the pointer pull and the hover/press scaling.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::pulse-button icon="play" tone="inverse" size="lg" wire:click="save">
            پخش ویدیوی معرفی
        </x-nx::pulse-button>

        <x-nx::pulse-button icon="arrow-right" aria-label="مرحلهٔ بعد" wire:click="ping('بعدی')" />
        BLADE,
    ],

    'backdrop' => [
        'title' => ['fa' => 'پس‌زمینهٔ تزئینی', 'en' => 'Backdrop'],
        'icon' => 'image',
        'oneLiner' => [
            'fa' => 'زمینهٔ تزئینی خالص CSS در هفت حالت — از شفق قطبی تا خطوط سیگنال — که اولِ هر بخشِ position‌دار گذاشته می‌شود و محتوای شما رویش می‌نشیند.',
            'en' => 'A pure-CSS decorative backdrop in seven moods — from aurora to signal stripes — dropped first inside any positioned section with your content on top.',
        ],
        'js' => false,
        'docs' => null,
        'props' => [
            ['name' => 'variant', 'type' => 'string', 'default' => "'aurora'", 'note' => [
                'fa' => 'aurora · grid · stars · beams · dots · mesh · stripes — همه با توکن‌های تم، پس روشن و تیره هر دو درست‌اند.',
                'en' => 'aurora · grid · stars · beams · dots · mesh · stripes — all in theme tokens, so light and dark both work.',
            ]],
        ],
        'code' => <<<'BLADE'
        <section style="position: relative; isolation: isolate; overflow: clip; display: grid; place-content: center; text-align: center; min-block-size: 22rem">
            <x-nx::backdrop variant="grid" />
            <h2>داشبورد خالی‌ات منتظر است</h2>
            <x-nx::button variant="primary" shape="pill">ساخت نخستین پروژه</x-nx::button>
        </section>
        BLADE,
    ],

    'dither-backdrop' => [
        'title' => ['fa' => 'پس‌زمینهٔ دیتر', 'en' => 'Dither backdrop'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'میدان دیترِ مرتبِ متحرک با رنگ‌های تم، روی canvas؛ بیرون از دید و در تب مخفی می‌ایستد و در حرکت کم یک فریم ساکن نشان می‌دهد.',
            'en' => 'An animated ordered-dither field in the theme’s colours, painted on a canvas; it pauses off-screen and in hidden tabs and holds one still frame under reduced motion.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'cell', 'type' => 'number', 'default' => 'null', 'note' => [
                'fa' => 'پیکسل CSS هر سلول دیتر؛ بزرگ‌تر یعنی درشت‌تر و ارزان‌تر.',
                'en' => 'CSS pixels per dither cell; bigger means coarser and cheaper.',
            ]],
            ['name' => 'matrix', 'type' => 'number', 'default' => 'null', 'note' => [
                'fa' => 'ماتریس بایر: ۴ یا ۸.',
                'en' => 'The Bayer matrix: 4 or 8.',
            ]],
            ['name' => 'speed', 'type' => 'number', 'default' => 'null', 'note' => [
                'fa' => 'سرعت رانِ میدان.',
                'en' => 'The drift speed of the field.',
            ]],
            ['name' => 'fps', 'type' => 'number', 'default' => 'null', 'note' => [
                'fa' => 'سقف فریم بر ثانیه — برای صرفه‌جویی پایین‌ش بیاورید.',
                'en' => 'Frames-per-second cap — lower it to save battery.',
            ]],
        ],
        'code' => <<<'BLADE'
        <section style="position: relative; isolation: isolate; overflow: clip; display: grid; place-content: center; text-align: center; min-block-size: 24rem">
            <x-nx::dither-backdrop :cell="3" :matrix="8" />
            <h2>پشتیبانی به زبان مشتری‌ات</h2>
            <x-nx::button variant="primary" shape="pill">شروع رایگان</x-nx::button>
        </section>
        BLADE,
    ],
];
