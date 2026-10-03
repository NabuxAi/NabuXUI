<?php

/**
 * Demo manifest of the "effects" group — effects & layout motion: a beam of
 * light around a border, liquid pills, glitching and typed text, split panes,
 * reading progress, height that springs, a morphing island, a spotlight tour
 * and images that resolve out of noise. Scenarios live at
 * resources/views/demos/components/effects/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'افکت‌ها', 'en' => 'Effects'],

    'border-beam' => [
        'title' => ['fa' => 'پرتو دور لبه', 'en' => 'Border beam'],
        'icon' => 'sparkles',
        'oneLiner' => [
            'fa' => 'تکه‌ای نور درخشان که دور لبهٔ هر عنصری می‌چرخد — برای پلن پیشنهادی، کارت فعال یا ورودی در حال پردازش. بیرون از دید متوقف می‌شود و در حرکت کاهش‌یافته درخششی ثابت می‌ماند.',
            'en' => 'A glowing segment of light travelling around any element’s border — for the recommended plan, the active card or a field at work. Paused off screen; a still glow under reduced motion.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'tone', 'type' => 'string', 'default' => "'accent'", 'note' => [
                'fa' => 'accent · gold · violet · success؛ یا با color-from و color-to رنگ دلخواه.',
                'en' => 'accent · gold · violet · success; or any colours through color-from and color-to.',
            ]],
            ['name' => 'size', 'type' => 'number', 'default' => '70', 'note' => [
                'fa' => 'طول پرتو بر حسب درجه از یک دور کامل.',
                'en' => 'Length of the beam, in degrees of a lap.',
            ]],
            ['name' => 'duration', 'type' => 'number', 'default' => '6', 'note' => [
                'fa' => 'ثانیه برای هر دور؛ عدد بزرگ‌تر آرام‌تر.',
                'en' => 'Seconds per lap; larger is calmer.',
            ]],
            ['name' => 'width', 'type' => 'number', 'default' => '1.5', 'note' => [
                'fa' => 'ضخامت حلقه به پیکسل.',
                'en' => 'Ring thickness in px.',
            ]],
            ['name' => 'radius', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'گردی گوشهٔ قاب؛ پرتو همین گردی را دنبال می‌کند.',
                'en' => 'Corner radius of the wrapper; the beam follows it.',
            ]],
            ['name' => 'reverse · delay', 'type' => 'bool · number', 'default' => 'false · null', 'note' => [
                'fa' => 'چرخش برعکس، و جابه‌جایی شروع وقتی چند پرتو در یک صفحه‌اند.',
                'en' => 'Run the other way, and offset the start when several beams share a page.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::border-beam radius="var(--nx-radius-2xl)" tone="gold" :duration="8">
            <x-nx::card>
                <h3>پلن حرفه‌ای</h3>
                <x-nx::button variant="primary" wire:click="upgrade">ارتقا</x-nx::button>
            </x-nx::card>
        </x-nx::border-beam>

        {{-- or on any element --}}
        <div class="nx-beam" style="border-radius: 1rem; --nx-beam-duration: 4s">…</div>
        BLADE,
    ],

    'goo-stack' => [
        'title' => ['fa' => 'پشتهٔ مایع', 'en' => 'Goo stack'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'قرص‌هایی که در حالت آرام مثل مایع در هم ذوب می‌شوند و با هاور یا فوکوس از هم جدا؛ قرص تازه از بالای پشته جوانه می‌زند. فیلتر goo روی لایهٔ شکل‌ها، متن تیز روی لایهٔ جدا.',
            'en' => 'Pills that melt into one liquid shape at rest and split apart on hover or focus; a new pill buds off the top. The goo filter runs on a layer of shapes, the text stays crisp on its own layer.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'items', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر آیتم: id، label و icon اختیاری. بعداً با add({ label, icon }) یا رویداد nx-goo-add اضافه کنید.',
                'en' => 'Each item: id, label and an optional icon. Add more later with add({ label, icon }) or the nx-goo-add event.',
            ]],
            ['name' => 'state', 'type' => 'string', 'default' => "'auto'", 'note' => [
                'fa' => 'auto (در آرامش ذوب، با هاور جدا) · merged · split.',
                'en' => 'auto (melted at rest, split on hover) · merged · split.',
            ]],
            ['name' => 'strength', 'type' => 'number', 'default' => '8', 'note' => [
                'fa' => 'شعاع بلورِ goo؛ بیشتر یعنی قرص‌ها از فاصلهٔ دورتر به هم می‌چسبند.',
                'en' => 'Blur radius of the goo; more melts pills from further apart.',
            ]],
            ['name' => 'icons', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'نام آیکون‌هایی که قرص‌های بعدی ممکن است بخواهند.',
                'en' => 'Icon names that pills added later may use.',
            ]],
            ['name' => 'label · dismiss-label', 'type' => 'string', 'default' => "null · 'Dismiss'", 'note' => [
                'fa' => 'نام دسترس‌پذیر فهرست و دکمهٔ بستن هر قرص.',
                'en' => 'Accessible names of the list and of each pill’s dismiss button.',
            ]],
        ],
        'code' => <<<'BLADE'
        <div x-data x-on:nx-goo-dismiss="$wire.markRead($event.detail.id)">
            <x-nx::goo-stack label="اعلان‌ها" :icons="['bell']" :items="[
                ['id' => 'pay-1042', 'label' => 'فاکتور ۱۰۴۲ پرداخت شد', 'icon' => 'check-circle'],
                ['id' => 'cmt-77', 'label' => 'سارا روی نقشهٔ راه نظر داد', 'icon' => 'message'],
            ]" />
        </div>
        <x-nx::button x-data x-on:click="$dispatch('nx-goo-add', { label: 'یادآوری جلسه', icon: 'bell' })">افزودن</x-nx::button>
        BLADE,
    ],

    'glitch-text' => [
        'title' => ['fa' => 'متن گلیچ', 'en' => 'Glitch text'],
        'icon' => 'zap',
        'oneLiner' => [
            'fa' => 'دو نسخهٔ رنگی و aria-hidden پشت متن واقعی، نوار به نوار برش می‌خورند و جابه‌جا می‌شوند — روی هاور یا در جرقه‌های گاه‌به‌گاه. متن هیچ‌وقت حرف‌به‌حرف شکسته نمی‌شود، پس فارسی پیوسته می‌ماند. خالص CSS.',
            'en' => 'Two coloured, aria-hidden copies behind the real text slice and shift in bands — on hover or in occasional bursts. The text is never split into letters, so Persian stays joined. Pure CSS.',
        ],
        'js' => false,
        'docs' => null,
        'props' => [
            ['name' => 'text', 'type' => 'string', 'default' => "''", 'note' => [
                'fa' => 'متن؛ یک‌بار برای صفحه‌خوان، دو کپی تزئینی برای افکت.',
                'en' => 'The text; once for screen readers, two decorative copies for the effect.',
            ]],
            ['name' => 'trigger', 'type' => 'string', 'default' => "'hover'", 'note' => [
                'fa' => 'hover (یا هاور لینک/دکمهٔ والد) · loop (جرقه هر چند ثانیه) · always.',
                'en' => 'hover (or hovering the surrounding link/button) · loop (a burst every few seconds) · always.',
            ]],
            ['name' => 'as', 'type' => 'string', 'default' => "'span'", 'note' => [
                'fa' => 'تگ ریشه: span · strong · h1 · h2 · h3 · p.',
                'en' => 'Root tag: span · strong · h1 · h2 · h3 · p.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::glitch-text as="h2" text="خطای ۴۰۴ — این‌جا چیزی نیست" trigger="loop" />

        <a href="/status" class="nx-link">
            <x-nx::glitch-text text="System status" />
        </a>
        BLADE,
    ],

    'typewriter' => [
        'title' => ['fa' => 'ماشین تحریر', 'en' => 'Typewriter'],
        'icon' => 'edit',
        'oneLiner' => [
            'fa' => 'عبارت‌ها را گرافیم‌به‌گرافیم تایپ می‌کند (حرف فارسی با اعراب و نیم‌فاصله‌اش یک تکه است)، فقط تا بخش مشترک با عبارت بعدی پاک می‌کند و ادامه می‌دهد. نشانگر هنگام مکث چشمک می‌زند.',
            'en' => 'Types phrases a grapheme at a time (a Persian letter keeps its marks and joiners), deletes only back to what the next phrase shares, and goes on. The caret blinks while a phrase holds.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'phrases', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'عبارت‌ها به ترتیب؛ صفحه‌خوان همه را یک‌بار و ساده می‌خواند.',
                'en' => 'The phrases, in order; screen readers get them once, as plain text.',
            ]],
            ['name' => 'by', 'type' => 'string', 'default' => "'grapheme'", 'note' => [
                'fa' => "grapheme یا word برای تایپ کلمه‌به‌کلمه.",
                'en' => 'grapheme, or word to type whole words.',
            ]],
            ['name' => 'type-speed · delete-speed · hold', 'type' => 'int (ms)', 'default' => '55 · 28 · 1800', 'note' => [
                'fa' => 'سرعت تایپ و پاک کردن هر تکه، و مکث روی عبارت کامل.',
                'en' => 'Time per typed and deleted piece, and the hold on a finished phrase.',
            ]],
            ['name' => 'loop', 'type' => 'bool', 'default' => 'true', 'note' => [
                'fa' => 'چرخش بی‌پایان؛ با false روی عبارت آخر می‌ماند.',
                'en' => 'Cycle forever; false stops on the last phrase.',
            ]],
            ['name' => 'caret', 'type' => 'string', 'default' => "'bar'", 'note' => [
                'fa' => 'bar · block · none.',
                'en' => 'bar · block · none.',
            ]],
        ],
        'code' => <<<'BLADE'
        <h1>
            ما
            <x-nx::typewriter :phrases="['وب‌سایت می‌سازیم', 'اپلیکیشن می‌سازیم', 'برند می‌سازیم']" />
        </h1>
        <p><x-nx::typewriter by="word" :loop="false" :phrases="['Thinking…', 'Drafting a reply…']" /></p>
        BLADE,
    ],

    'resizable-panels' => [
        'title' => ['fa' => 'پنل‌های قابل‌تغییر اندازه', 'en' => 'Resizable panels'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'پنجره‌های تقسیم‌شده — افقی، عمودی و تودرتو — با دستگیره‌های کشیدنی، کیبورد (فلش‌ها، Home/End، Enter)، حداقل/حداکثر، جمع شدن با دوبار کلیک و یادسپاری اندازه‌ها. راست‌به‌چپ را می‌فهمد.',
            'en' => 'Split panes — horizontal, vertical, nested — with draggable handles, keyboard (arrows, Home/End, Enter), min/max sizes, collapse on double-click and remembered sizes. Right-to-left aware.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'orientation', 'type' => 'string', 'default' => "'horizontal'", 'note' => [
                'fa' => 'horizontal (کنار هم) یا vertical (زیر هم)؛ برای شبکه، گروهی را در پنلی تودرتو کنید.',
                'en' => 'horizontal (side by side) or vertical (stacked); nest a group in a pane for a grid.',
            ]],
            ['name' => 'storage-key', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'اندازه‌ها را در localStorage با این کلید نگه می‌دارد.',
                'en' => 'Remembers the sizes in localStorage under this key.',
            ]],
            ['name' => 'pane: size · min · max', 'type' => 'number (%)', 'default' => 'null', 'note' => [
                'fa' => 'اندازهٔ شروع و محدوده‌ها به درصد؛ پنل‌های بی‌اندازه باقی‌مانده را تقسیم می‌کنند.',
                'en' => 'Starting size and limits in percent; panes without a size share the rest.',
            ]],
            ['name' => 'pane: collapsible', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'زیر نصف حداقل که کشیده شود، دوبار کلیک یا Enter روی دستگیره، جمع می‌شود.',
                'en' => 'Folds when dragged under half its minimum, on double-click or Enter on its handle.',
            ]],
            ['name' => 'handle: label', 'type' => 'string', 'default' => "'Resize'", 'note' => [
                'fa' => 'نام دسترس‌پذیرِ جداکنندهٔ role=separator.',
                'en' => 'Accessible name of the role=separator handle.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::resizable-panels storage-key="editor" style="block-size: 30rem" x-on:nx-resize="$wire.set('layout', $event.detail.sizes)">
            <x-nx::resizable-pane :size="22" :min="15" :max="40" collapsible>…فایل‌ها…</x-nx::resizable-pane>
            <x-nx::resizable-handle label="تغییر اندازهٔ نوار کناری" />
            <x-nx::resizable-pane>
                <x-nx::resizable-panels orientation="vertical">
                    <x-nx::resizable-pane :size="70">…ویرایشگر…</x-nx::resizable-pane>
                    <x-nx::resizable-handle label="تغییر اندازهٔ ترمینال" />
                    <x-nx::resizable-pane :min="10" collapsible>…ترمینال…</x-nx::resizable-pane>
                </x-nx::resizable-panels>
            </x-nx::resizable-pane>
        </x-nx::resizable-panels>
        BLADE,
    ],

    'scroll-progress' => [
        'title' => ['fa' => 'پیشرفت مطالعه', 'en' => 'Scroll progress'],
        'icon' => 'trend-up',
        'oneLiner' => [
            'fa' => 'نوار باریک پیشرفت خواندن بالای صفحه یا حلقه‌ای که دکمهٔ «بازگشت به بالا» است؛ با انیمیشن‌های اسکرول‌محور CSS و جایگزین جاوااسکریپت برای مقاله یا جعبهٔ اسکرول‌دار.',
            'en' => 'A hairline reading-progress bar across the top, or a ring that is the back-to-top button; CSS scroll-driven animations with a JavaScript fallback for one article or a scrolling box.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'variant', 'type' => 'string', 'default' => "'bar'", 'note' => [
                'fa' => 'bar یا circle (دکمهٔ بازگشت به بالا با حلقهٔ پرشونده).',
                'en' => 'bar, or circle (a back-to-top button whose ring fills).',
            ]],
            ['name' => 'target', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'سلکتور مقاله‌ای که پیشرفت خواندنِ خودش سنجیده شود.',
                'en' => 'Selector of the article whose own reading progress is measured.',
            ]],
            ['name' => 'container', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'سلکتور جعبهٔ اسکرول‌دار؛ همراه با position="sticky".',
                'en' => 'Selector of a scrolling box; pair with position="sticky".',
            ]],
            ['name' => 'position', 'type' => 'string', 'default' => "'fixed'", 'note' => [
                'fa' => 'fixed · sticky · static.',
                'en' => 'fixed · sticky · static.',
            ]],
            ['name' => 'label', 'type' => 'string', 'default' => "'Reading progress'", 'note' => [
                'fa' => 'نام دسترس‌پذیر نوار، یا دکمهٔ حلقه.',
                'en' => 'Accessible name of the bar, or of the circle button.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::scroll-progress label="پیشرفت مطالعه" />
        <x-nx::scroll-progress variant="circle" label="بازگشت به بالا" />

        <article id="post">…</article>
        <x-nx::scroll-progress target="#post" />
        BLADE,
    ],

    'auto-height' => [
        'title' => ['fa' => 'ارتفاع خودکار', 'en' => 'Auto height'],
        'icon' => 'chevron-down',
        'oneLiner' => [
            'fa' => 'قابی که هر بار محتوایش عوض شود — رندر دوبارهٔ Livewire، عوض شدن تب، باز شدن کارت — ارتفاعش با فنر به اندازهٔ تازه می‌رسد. ResizeObserver و فنر، و interpolate-size هرجا هست.',
            'en' => 'A frame that springs to its new height whenever its content changes — a Livewire re-render, a tab switch, a card opening. ResizeObserver and a spring, plus interpolate-size where available.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'spring', 'type' => 'string', 'default' => "'gentle'", 'note' => [
                'fa' => 'snappy · gentle · bouncy · soft.',
                'en' => 'snappy · gentle · bouncy · soft.',
            ]],
            ['name' => 'collapsed', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'تا ارتفاع صفر جمع می‌شود و inert می‌شود.',
                'en' => 'Folds to zero height and turns inert.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::card>
            <x-nx::segmented wire:model.live="plan" :options="['month' => 'ماهانه', 'year' => 'سالانه']" />
            <x-nx::auto-height>
                @if ($plan === 'year')
                    …جزئیات بلندتر پلن سالانه…
                @else
                    …پلن ماهانه…
                @endif
            </x-nx::auto-height>
        </x-nx::card>
        BLADE,
    ],

    'dynamic-island' => [
        'title' => ['fa' => 'جزیرهٔ پویا', 'en' => 'Dynamic island'],
        'icon' => 'bell',
        'oneLiner' => [
            'fa' => 'قرص تیره‌ای که بین حالت‌های فشرده و باز — اعلان، تایمر، تماس — تغییر شکل می‌دهد؛ عرض، ارتفاع و گردی با فنر می‌رسند و محتوا محو‌به‌محو جابه‌جا می‌شود.',
            'en' => 'A dark pill that morphs between compact and expanded states — notification, timer, call — springing its width, height and radius while the content cross-fades.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'view', 'type' => 'string', 'default' => "'idle'", 'note' => [
                'fa' => 'نام نمای فعال؛ درون آن show(\'name\') صدا بزنید یا رویداد nx-island-show بفرستید. x-model هم کار می‌کند.',
                'en' => "The active view's name; call show('name') inside, dispatch nx-island-show, or bind it with x-model.",
            ]],
            ['name' => 'island-view: name · size', 'type' => 'string', 'default' => "— · 'compact'", 'note' => [
                'fa' => 'هر نما با نام خودش؛ expanded فاصله و حداقل عرض بیشتری دارد.',
                'en' => 'Each view by name; expanded gets more padding and a minimum width.',
            ]],
            ['name' => 'label', 'type' => 'string', 'default' => "'Live activity'", 'note' => [
                'fa' => 'نام دسترس‌پذیر ناحیه.',
                'en' => 'Accessible name of the region.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::dynamic-island view="idle" label="فعالیت زنده">
            <x-nx::island-view name="idle">
                <button class="nx-island-toggle" x-on:click="show('call')">…</button>
            </x-nx::island-view>
            <x-nx::island-view name="call" size="expanded">
                <div class="nx-island-row">…</div>
                <div class="nx-island-actions">
                    <button class="nx-island-btn" data-tone="danger" x-on:click="show('idle')">رد</button>
                    <button class="nx-island-btn" data-tone="success" wire:click="answer">پاسخ</button>
                </div>
            </x-nx::island-view>
        </x-nx::dynamic-island>
        BLADE,
    ],

    'spotlight-tour' => [
        'title' => ['fa' => 'تور راهنما', 'en' => 'Spotlight tour'],
        'icon' => 'star',
        'oneLiner' => [
            'fa' => 'راهنمای شروع کار: صفحه تیره می‌شود و دور عنصر هدف بریده می‌شود، پنجرهٔ هر گام با بعدی/قبلی/رد کنارش می‌نشیند؛ کیبورد، اسکرول تا هدف و مدیریت فوکوس.',
            'en' => 'Onboarding coachmarks: the page dims with a cut-out around the target and each step sits beside it with next/back/skip; keyboard, scroll into view and focus management.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'steps', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر گام: title، body، target (سلکتور؛ بدون آن وسط صفحه) و side.',
                'en' => 'Each step: title, body, target (a selector; centred without one) and side.',
            ]],
            ['name' => 'id', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => "با \$dispatch('nx-tour-start', 'id') این تور شروع می‌شود.",
                'en' => "\$dispatch('nx-tour-start', 'id') starts this tour.",
            ]],
            ['name' => 'open', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'بلافاصله پس از بارگذاری شروع شود.',
                'en' => 'Start right after the page loads.',
            ]],
            ['name' => 'next · back · skip · done · step-label', 'type' => 'string', 'default' => "'Next' …", 'note' => [
                'fa' => 'برچسب دکمه‌ها و شمارنده (":current of :total").',
                'en' => 'Button labels and the counter (":current of :total").',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::spotlight-tour id="welcome" next="بعدی" back="قبلی" skip="رد کردن" done="تمام" step-label="گام :current از :total" :steps="[
            ['target' => '#new-project', 'title' => 'از این‌جا شروع کنید', 'body' => 'نخستین پروژه‌تان را بسازید.'],
            ['target' => '#search', 'title' => 'همه‌چیز را پیدا کنید', 'body' => 'کلید / را بزنید.'],
            ['title' => 'آماده‌اید', 'body' => 'این تور از منوی راهنما دوباره پخش می‌شود.'],
        ]" x-on:nx-tour-finish="$wire.markOnboarded()" />

        <x-nx::button x-data x-on:click="$dispatch('nx-tour-start', 'welcome')">نمایش تور</x-nx::button>
        BLADE,
    ],

    'image-reveal' => [
        'title' => ['fa' => 'آشکار شدن تصویر', 'en' => 'Image reveal'],
        'icon' => 'image',
        'oneLiner' => [
            'fa' => 'جای خالی تصویر که از نویز پیکسلی و بلور به تصویر بارگذاری‌شده می‌رسد — برای تولید تصویر با هوش مصنوعی یا تصاویر تنبل — با وضعیت پیشرفت.',
            'en' => 'An image placeholder that resolves from pixel noise and blur into the loaded image — for AI generation or lazy images — with a progress state.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'src · alt', 'type' => 'string', 'default' => "null · ''", 'note' => [
                'fa' => 'تصویر و متن جایگزین؛ تا وقتی src خالی است نویز ادامه دارد.',
                'en' => 'The image and its alt; the noise keeps going while src is empty.',
            ]],
            ['name' => 'progress', 'type' => 'int|null', 'default' => 'null', 'note' => [
                'fa' => '۰ تا ۱۰۰؛ زیر ۱۰۰ تصویر پنهان می‌ماند و نویز ریزتر می‌شود. بدون آن فقط منتظر بارگذاری است.',
                'en' => '0–100; below 100 the image stays hidden while the noise grows finer. Without it, it just waits for the load.',
            ]],
            ['name' => 'placeholder', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'پیش‌نمایش ریز و بلور (LQIP)؛ بدون آن گرادیان شفق.',
                'en' => 'A tiny blurred preview (LQIP); the aurora gradient otherwise.',
            ]],
            ['name' => 'ratio', 'type' => 'string', 'default' => "'4 / 3'", 'note' => [
                'fa' => 'نسبت ابعاد قاب.',
                'en' => 'Aspect ratio of the frame.',
            ]],
            ['name' => 'label · error-label', 'type' => 'string', 'default' => "'Loading' · …", 'note' => [
                'fa' => 'متن وضعیت هنگام انتظار و هنگام خطا.',
                'en' => 'Status text while waiting and when the image fails.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::image-reveal :src="$result?->url" alt="فانوس دریایی در غروب"
            :progress="$progress" ratio="16 / 9" label="در حال ساخت" wire:poll.500ms="tick" />

        {{-- without Livewire --}}
        <div x-data>
            <x-nx::image-reveal id="art" alt="…" :progress="0" />
            <button x-on:click="document.getElementById('art').dispatchEvent(new CustomEvent('nx-image-update', { detail: { progress: 100, src: url } }))">…</button>
        </div>
        BLADE,
    ],
];
