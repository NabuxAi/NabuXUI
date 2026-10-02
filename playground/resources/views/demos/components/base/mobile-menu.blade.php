{{--
    The mobile menu's real scenarios: the canonical host — a drawer from the
    inline-start carrying a bookshop's whole navigation — and the same
    drill-down standing alone in a narrow frame, with the default slot
    pinning the account actions at the end of the root list.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $shopNav = [
        ['label' => $say('Shop', 'فروشگاه'), 'children' => [
            ['label' => $say('New arrivals', 'تازه‌ها'), 'href' => '/components', 'icon' => 'star'],
            ['label' => $say('Bestsellers', 'پرفروش‌ها'), 'href' => '/components', 'icon' => 'trend-up'],
            ['label' => $say('Deals', 'تخفیف‌ها'), 'href' => '/components', 'icon' => 'zap'],
        ]],
        ['label' => $say('The club', 'باشگاه کتاب'), 'children' => [
            ['label' => $say('Reading circles', 'حلقه‌های کتاب‌خوانی'), 'href' => '/components', 'icon' => 'users'],
            ['label' => $say('Meet the author', 'گفت‌وگو با نویسنده'), 'href' => '/components', 'icon' => 'message'],
        ]],
        ['label' => $say('Track my order', 'پیگیری سفارش'), 'href' => '/components', 'current' => true],
    ];

    $accountNav = [
        ['label' => $say('My account', 'حساب من'), 'children' => [
            ['label' => $say('Profile', 'مشخصات'), 'href' => '/components', 'icon' => 'user'],
            ['label' => $say('Security', 'امنیت'), 'href' => '/components', 'icon' => 'lock'],
            ['label' => $say('Billing', 'صورت‌حساب'), 'href' => '/components', 'icon' => 'file'],
        ]],
        ['label' => $say('My library', 'کتاب‌خانهٔ من'), 'children' => [
            ['label' => $say('Favorites', 'نشان‌شده‌ها'), 'href' => '/components', 'icon' => 'heart'],
            ['label' => $say('My orders', 'سفارش‌های من'), 'href' => '/components', 'icon' => 'check'],
        ]],
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The bookshop’s navigation drawer', 'کشوی ناوبری کتاب‌فروشی') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The canonical host: a drawer from the inline-start carrying the same items the mega menu shows on desktop. Open “Shop” — the rows rise one after another and the panel glides with a spring; in Persian the slide flips by itself, because the track reads the direction. “Track my order” is the current page (aria-current), and with navigate every link swaps the page through wire:navigate — no reload.', 'میزبان همیشگی: کشویی از ابتدای خط که همان آیتم‌هایی را می‌آورد که مگامنو در دسکتاپ نشان می‌دهد. «فروشگاه» را باز کنید — ردیف‌ها پشت‌سرهم بالا می‌آیند و پنل با فنر می‌لغزد؛ در فارسی سمت لغزش خودش برمی‌گردد، چون مسیر جهتِ متن را می‌خواند. «پیگیری سفارش» صفحهٔ جاری است (aria-current) و با navigate همهٔ لینک‌ها از wire:navigate رد می‌شوند — بی‌رفرش.') }}
        </p>
    </div>
    <div class="pg-row">
        <x-nx::button variant="secondary" icon="menu" wire:click="$set('state.shopMenuOpen', true)">
            {{ $say('Open the menu', 'بازکردن منو') }}
        </x-nx::button>
        <span style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">
            {{ $say('Keyboard too: Enter drills in, focus lands on “Back”, and going back returns it to the very item you came from — the hidden panel is inert, so Tab never wanders into it.', 'با کیبورد هم همین‌طور: Enter دریل می‌کند، فوکوس روی «بازگشت» می‌نشیند و برگشتن، فوکوس را به همان آیتمی که از آن آمدید برمی‌گرداند — پنل پنهان inert است و Tab به درونش سرک نمی‌کشد.') }}
        </span>
    </div>
    <x-nx::drawer side="start" wire:model="state.shopMenuOpen" :title="$say('Lalezar Bookshop', 'کتاب‌فروشی لاله‌زار')">
        <x-nx::mobile-menu :items="$shopNav" navigate />
    </x-nx::drawer>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The account menu, in a narrow frame', 'فهرست حساب، در قاب تنگ') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The drill-down does not need a drawer: here it stands alone in a phone-width column. Each child carries its own icon, and the default slot pins whatever you hand it to the end of the root list — here a divider and the sign-out button, which answers with a toast.', 'دریل‌داون به کشو نیاز ندارد: این‌جا، تنها، در ستونی به پهنای گوشی ایستاده. هر فرزند آیکون خودش را دارد و اسلات پیش‌فرض هرچه بدهید انتهای فهرست سطح اول می‌نشیند — این‌جا یک جداکننده و دکمهٔ خروج، که با توست جواب می‌دهد.') }}
        </p>
    </div>
    <div style="max-inline-size: 20rem; padding: var(--nx-space-2); border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg); background: var(--nx-surface)">
        <x-nx::mobile-menu :items="$accountNav">
            <x-nx::divider style="margin-block: .5rem">{{ $say('or', 'یا') }}</x-nx::divider>
            <x-nx::button variant="ghost" icon="x" block wire:click="ping('{{ $say('Signed out — see you soon', 'خارج شدید — به امید دیدار') }}')">
                {{ $say('Sign out', 'خروج از حساب') }}
            </x-nx::button>
        </x-nx::mobile-menu>
    </div>
    <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
        {{ $say('The header does exactly this pairing for you: one $nav feeds the desktop mega menu, and below 56rem the burger serves the same items through this drill-down.', 'هدر دقیقاً همین جفت‌کردن را برایتان انجام می‌دهد: یک $nav هم به مگامنوی دسکتاپ می‌رسد و هم زیر ۵۶rem، همبرگر همان آیتم‌ها را از این دریل‌داون سرو می‌کند.') }}
    </p>
</section>
