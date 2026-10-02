{{--
    The header where it is actually shipped: a shop-builder's site header
    (brand + mega menu + actions, folding into the burger drawer below 56rem),
    a landing page's floating pill, and a reading header that steps aside while
    the reader scrolls down and returns the moment they head back up.

    Only the first scenario passes items: the mobile drawer's dialog name is
    fixed per page, so a second header with items would open its drawer too.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $shopNav = [
        ['label' => $say('Features', 'ویژگی‌ها'), 'columns' => 2, 'children' => [
            ['label' => $say('Store builder', 'فروشگاه‌ساز'), 'icon' => 'grid', 'href' => '/components',
                'description' => $say('Persian-first templates, ready tonight', 'قالب‌های راست‌چین، آمادهٔ امشب')],
            ['label' => $say('Payment gateways', 'درگاه‌های پرداخت'), 'icon' => 'zap', 'href' => '/components',
                'description' => $say('Iranian banks, settled weekly', 'بانک‌های ایرانی، تسویهٔ هفتگی')],
            ['label' => $say('Sales analytics', 'تحلیل فروش'), 'icon' => 'chart', 'href' => '/components',
                'description' => $say('Today’s orders, live', 'سفارش‌های امروز، زنده')],
            ['label' => $say('Human support', 'پشتیبانی انسانی'), 'icon' => 'message', 'href' => '/components',
                'description' => $say('On chat in your timezone', 'در چت، با شیفت شما')],
        ]],
        ['label' => $say('Pricing', 'قیمت‌ها'), 'href' => '/components'],
        ['label' => $say('Demos', 'دموها'), 'href' => '/components', 'current' => true],
    ];

    $ctaPing = $say('Your shop is being prepared', 'فروشگاه شما آماده می‌شود');
    $ctaPingMobile = $say('Your shop is being prepared', 'فروشگاه شما آماده می‌شود');
    $freePing = $say('Trial workspace created', 'ورک‌اسپیس آزمایشی ساخته شد');

    $shareUrl = url()->current();

    $paragraphs = [
        $say('Reading a Persian article under an always-visible sticky header is like reading a book whose publisher thought the cover should stay open over the text. What the reader wants is plain: the most pixels for the words, and a quick way back to the navigation only when it is needed.', 'خواندنِ یک مقالهٔ فارسی زیر هدرِ چسبانی که هیچ‌وقت نمی‌رود، مثل خواندن کتابی است که ناشرش تصور کرده جلد باید باز روی متن بماند. آنچه خواننده می‌خواهد ساده است: بیشترین پیکسل برای خودِ کلمات، و راهِ سریع به ناوبری، فقط هر وقت که لازمش شد.'),
        $say('The hide-on-scroll pattern is exactly that bargain. The header travels with the reader’s pace: while they head down the page — at the peak of focus — it slips away, and at the first motion back up — the moment they look for a way out — it returns instantly.', 'الگوی مخفی‌شونده دقیقاً همین معامله است. هدر با سرعتِ خواننده همراه می‌شود: وقتی رو به پایین است — در اوج تمرکز — بی‌سروصدا کنار می‌رود، و با اولین حرکت به بالا — همان لحظه‌ای که دنبال راه خروج می‌گردد — بی‌درنگ برمی‌گردد.'),
        $say('So it never becomes twitchy, the decision carries a small tolerance: a pixel of jitter or a slow crawl does not move it. The verdict follows the clear direction of the scroll, not every wheel event.', 'برای اینکه رفتارش پرش‌ و آزاردهنده نشود، تصمیمش یک تحمل کوچک دارد: چند پیکسل لرزش یا حرکت آرام، هدر را نمی‌لغزاند. حکم با جهتِ آشکار اسکرول گرفته می‌شود، نه با هر رخداد چرخِ موس.'),
        $say('Accessibility survives too: while anything inside the header holds focus, it refuses to hide, so a keyboard user never lands in a control they cannot see — and the share link never depends on the header being open; it sits by the title, one press away.', 'دسترس‌پذیری هم حفظ می‌شود: تا وقتی چیزی داخل هدر فوکوس دارد، هدر قایم نمی‌شود؛ پس کاربر کیبوردی هرگز دکمه‌ای را لمس نمی‌کند که دیده نمی‌شود — و اشتراک‌گذاری مقاله هم به بازبودن هدر وابسته نیست؛ کنار عنوان است، یک فاصله با انگشت.'),
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The shop-builder’s site header', 'سربرگِ سایتِ فروشگاه‌ساز') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Brand with its icon, a Features mega menu where every item carries its own description, a plain link marked aria-current, and the actions at the header’s end. “Log in” carries data-desktop: below 56rem it disappears and the burger takes over — the same items as a drill-down drawer, with the build-shop button arriving through the mobile-actions slot. The primary button answers a click with a toast and a spinner (wire:loading).', 'برند با آیکونش، مگامنوی «ویژگی‌ها» که هر آیتمش توضیح خودش را دارد، یک لینک ساده با aria-current و کنش‌های انتهای هدر. «ورود» data-desktop دارد: زیر ۵۶rem پنهان می‌شود و همبرگر فرمان را می‌گیرد — همان آیتم‌ها به‌شکل دریل‌داون داخل کشو، و دکمهٔ ساخت فروشگاه از اسلات mobile-actions می‌رسد. دکمهٔ اصلی کلیک را با توست و اسپینر جواب می‌دهد (wire:loading).') }}
        </p>
    </div>
    <x-nx::header :items="$shopNav" :label="$say('NabuShop navigation', 'راهبری نابوشاپ')" brand-href="/components" navigate>
        <x-slot:brand>{{ \NabuXUI\NabuXUI::icon('zap') }}{{ $say('NabuShop', 'نابوشاپ') }}</x-slot:brand>
        <x-slot:actions>
            <x-nx::theme-toggle />
            <x-nx::button size="sm" variant="ghost" href="/login" data-desktop wire:navigate>{{ $say('Log in', 'ورود') }}</x-nx::button>
            <x-nx::button size="sm" variant="primary" wire:click="save('{{ $ctaPing }}')">{{ $say('Build my shop', 'ساخت فروشگاه') }}</x-nx::button>
        </x-slot:actions>
        <x-slot:mobile-actions>
            <x-nx::button size="sm" variant="ghost" href="/login" wire:navigate>{{ $say('Log in', 'ورود') }}</x-nx::button>
            <x-nx::button size="sm" variant="primary" wire:click="save('{{ $ctaPingMobile }}')">{{ $say('Build my shop', 'ساخت فروشگاه') }}</x-nx::button>
        </x-slot:mobile-actions>
    </x-nx::header>
    <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
        {{ $say('Narrow the window under 56rem: the nav and “Log in” fold away, the burger stays — and picking anything in the drawer does not close it, exactly like the panel it came from.', 'پنجره را زیر ۵۶rem باریک کنید: ناوبری و «ورود» جمع می‌شوند و همبرگر می‌ماند — و انتخاب هر آیتم در کشو آن را نمی‌بندد؛ همان‌طور که خودش گفته بود.') }}
    </p>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The landing page’s floating pill', 'قرص شناور صفحهٔ فرود') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Without items the header lays out just the brand and the actions, as a floating pill. Scroll down the hero: from the first few pixels the pill tightens — thinner, bordered, glassed and narrower (data-scrolled) — and loosens again when you come back up. The trial button toasts through wire:loading, so it stays honest while it “creates” the workspace.', 'بدون items هدر فقط برند و کنش‌ها را می‌چیند، به‌شکل قرص شناور. در بدنهٔ هیرو پایین بروید: از همان چند پیکسلِ اول قرص جمع می‌شود — نازک‌تر، لبه‌دار، شیشه‌ای و باریک‌تر (data-scrolled) — و با برگشتن بالا دوباره شل می‌شود. دکمهٔ شروع با wire:loading کارش را با توست تمام می‌کند تا در همان لحظهٔ ساختِ «ورک‌اسپیس» صادق بماند.') }}
        </p>
    </div>
    <x-nx::header variant="floating" brand-href="/components">
        <x-slot:brand>{{ \NabuXUI\NabuXUI::icon('star') }}{{ $say('Nabu', 'نابو') }}</x-slot:brand>
        <x-slot:actions>
            <x-nx::theme-toggle />
            <x-nx::button size="sm" variant="ghost" href="/login" data-desktop wire:navigate>{{ $say('Log in', 'ورود') }}</x-nx::button>
            <x-nx::button size="sm" variant="primary" wire:click="save('{{ $freePing }}')">{{ $say('Start free', 'شروع رایگان') }}</x-nx:button>
        </x-slot:actions>
    </x-nx::header>
    <div style="display: grid; gap: 1.25rem; padding-block-start: .5rem">
        <div style="display: grid; gap: .5rem; max-inline-size: 40rem">
            <h4 style="margin: 0; font: 700 var(--nx-text-2xl) / 1.3 var(--nx-font-display)">{{ $say('Open your storefront tonight', 'ویترین‌تان را امشب باز کنید') }}</h4>
            <p style="margin: 0; color: var(--nx-text-muted)">
                {{ $say('From '.NabuXUI::formatNumber(190000).' tomans a month, 14 days free — cancel from the panel, no phone calls.', 'از '.NabuXUI::formatNumber(190000).' تومان در ماه، ۱۴ روز رایگان — لغو از خود پنل، بی‌تماس تلفنی.') }}
            </p>
        </div>
        <ul style="list-style: none; margin: 0; padding: 0; display: grid; gap: .5rem">
            @foreach ([
                ['icon' => 'grid', 'title' => $say('Persian-first templates', 'قالب‌های راست‌چین و فارسی-اول'), 'body' => $say('Right-to-left from the first pixel, not bolted on later.', 'از همان پیکسل اول راست‌به‌چپ، نه وصله‌ای بعداً.')],
                ['icon' => 'chart', 'title' => $say('Live sales analytics', 'تحلیل فروش زنده'), 'body' => $say('Today’s orders and best sellers while they happen.', 'سفارش‌ها و پرفروش‌های امروز، همان لحظه.')],
                ['icon' => 'shield', 'title' => $say('Secure by default', 'امن از روز اول'), 'body' => $say('SSL, Iranian gateways and '.NabuXUI::formatNumber(99.9, 1).'% uptime.', 'SSL، درگاه‌های ایرانی و '.NabuXUI::formatNumber(99.9, 1).'٪ آپ‌تایم.')],
            ] as $feature)
                <li class="pg-row" style="gap: 1rem; padding: .9rem 1rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg)">
                    <span style="display: grid; place-items: center; inline-size: 2.25rem; block-size: 2.25rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-sm); color: var(--nx-accent-text)" aria-hidden="true">{{ \NabuXUI\NabuXUI::icon($feature['icon']) }}</span>
                    <span style="display: grid; gap: .15rem">
                        <strong style="font-weight: 600">{{ $feature['title'] }}</strong>
                        <span style="font-size: var(--nx-text-sm); color: var(--nx-text-muted)">{{ $feature['body'] }}</span>
                    </span>
                </li>
            @endforeach
        </ul>
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The reading header steps aside', 'هدرِ خواندن، کنار می‌رود') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('hide-on-scroll is on: scrolling down the article, the header slips out of the way quietly; the first motion back up brings it back at once. While something inside it holds focus it refuses to hide, and the article’s link is copied by the button beside the title — never hostage to an open header.', 'hide-on-scroll روشن است: با پایین‌رفتن در مقاله هدر بی‌سروصدا از میان می‌رود؛ اولین حرکت به بالا بی‌درنگ برمی‌گرداندش. تا وقتی چیزی داخلش فوکوس دارد قایم نمی‌شود، و نشانی مقاله را دکمهٔ کنار عنوان کپی می‌کند — گروگانِ هدرِ باز نیست.') }}
        </p>
    </div>
    <x-nx::header hide-on-scroll brand-href="/components">
        <x-slot:brand>{{ \NabuXUI\NabuXUI::icon('file') }}{{ $say('The Nabu Notebook', 'دفترچهٔ نابو') }}</x-slot:brand>
        <x-slot:actions>
            <x-nx::copy-button :value="$shareUrl" size="sm" :aria-label="$say('Copy the article link', 'کپی نشانی مقاله')" />
            <x-nx::theme-toggle />
        </x-slot:actions>
    </x-nx::header>
    <article style="display: grid; gap: 1.25rem; max-inline-size: 44rem; padding-block-start: .5rem">
        <div style="display: grid; gap: .5rem">
            <div class="pg-row" style="justify-content: space-between; gap: .75rem">
                <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">{{ $say('The Nabu Notebook · 3 min read · 1404', 'دفترچهٔ نابو · ۳ دقیقه خواندن · ۱۴۰۴') }}</p>
                <x-nx::copy-button :value="$shareUrl" size="sm" variant="secondary">{{ $say('Share', 'اشتراک') }}</x-nx::copy-button>
            </div>
            <h4 style="margin: 0; font: 700 var(--nx-text-2xl) / 1.3 var(--nx-font-display)">{{ $say('Why the reading header must learn to leave', 'چرا هدرِ خواندن باید رفتن را یاد بگیرد؟') }}</h4>
        </div>
        @foreach ($paragraphs as $i => $paragraph)
            @if ($i === 2)
                <blockquote style="margin: 0; padding: .75rem 1.25rem; border-inline-start: 3px solid var(--nx-accent); border-radius: var(--nx-radius-md); background: var(--nx-surface-2); font: 500 var(--nx-text-md) / 1.9 var(--nx-font-display)">
                    {{ $say('A header should be like a good waiter: present the moment you need it, invisible the moment you don’t.', 'هدر باید مثل پیشخدمت خوب باشد: لحظهٔ نیاز حاضر، لحظهٔ بی‌نیازی غایب.') }}
                </blockquote>
            @endif
            <p style="margin: 0; line-height: 1.95; color: var(--nx-text-muted)">{{ $paragraph }}</p>
        @endforeach
        <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-subtle)">
            {{ $say('Try it: read a few paragraphs down until the header goes, then nudge the wheel up — it is already back.', 'امتحان کنید: چند پاراگراف پایین بروید تا هدر برود، بعد چرخ را کمی بالا بزنید — همین حالا برگشته است.') }}
        </p>
    </article>
</section>
