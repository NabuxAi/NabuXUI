{{--
    The nav menu doing page navigation: the shop panel's top bar (the pill
    chases the pointer and focus, then glides home to the current page), the
    same menu riding wire:navigate between real demo pages, and a customer
    page's second row — the sub-nav role with its accessible name.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $panelNav = [
        ['href' => '#', 'label' => $say('Overview', 'میزکار')],
        ['href' => '#', 'label' => $say('Orders', 'سفارش‌ها'), 'current' => true],
        ['href' => '#', 'label' => $say('Products', 'محصولات')],
        ['href' => '#', 'label' => $say('Customers', 'مشتریان')],
        ['href' => '#', 'label' => $say('Settings', 'تنظیمات')],
    ];

    $customerNav = [
        ['href' => '#', 'label' => $say('Overview', 'نمای کلی'), 'current' => true],
        ['href' => '#', 'label' => $say('Orders', 'سفارش‌ها')],
        ['href' => '#', 'label' => $say('Payments', 'پرداخت‌ها')],
        ['href' => '#', 'label' => $say('Files', 'فایل‌ها')],
        ['href' => '#', 'label' => $say('Notes', 'یادداشت‌ها')],
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The shop panel’s top bar', 'نوار بالای پنل فروشگاه') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Move the pointer across the links — the soft pill springs after it, and it follows the keyboard the same way. Step out and it glides home to the current page: سفارش‌ها sits bold, carries the accent dot underneath and aria-current="page" for screen readers.', 'اشاره‌گر را روی لینک‌ها بچرخانید — قرص نرم با فنر دنبالش می‌آید و با کیبورد هم همین‌طور. بیرون بروید تا به صفحهٔ جاری سُر بخورد: «سفارش‌ها» پررنگ نشسته، نقطهٔ لهجه زیرش است و aria-current="page" را برای صفحه‌خوان‌ها دارد.') }}
        </p>
    </div>
    <div class="pg-row" style="justify-content: space-between; padding: .5rem .75rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); background: var(--nx-surface-2)">
        <span class="pg-row" style="gap: .5rem; font-weight: 700">
            <x-nx::icon name="zap" />
            {{ $say('Hirmand Shop', 'فروشگاه هیرمند') }}
        </span>
        <x-nx::nav-menu :items="$panelNav" :label="$say('Panel sections', 'بخش‌های پنل')" />
        <x-nx::theme-toggle />
    </div>
    <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
        {{ $say('The hrefs here are placeholders so the demo stays put — in the product they come from your routes, and only the page you are on gets current.', 'hrefها این‌جا فقط جای‌نگهدارند تا دمو سرجایش بماند — در محصول از routeهای خودتان می‌آیند و فقط صفحه‌ای که هستید current می‌گیرد.') }}
    </p>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Walking the demo pages without a reload', 'گشتن میان صفحه‌های دمو بدون بارگیری دوباره') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('With navigate, every link rides wire:navigate: the next demo swaps in place, no full page load. These are the playground’s own pages — go on, click one and come back. «Nav menu» is the page you are reading, so its aria-current is not staged.', 'با navigate هر لینک از wire:navigate رد می‌شود: دموی بعدی در جا عوض می‌شود، بدون بارگیری کامل صفحه. این‌ها صفحه‌های خود پلی‌گراوندند — امتحان کنید و برگردید. «منوی ناوبری» همین صفحه‌ای است که می‌خوانید، پس aria-current آن بازی نیست.') }}
        </p>
    </div>
    <x-nx::nav-menu navigate :label="$say('Component demos', 'دموهای کامپوننت')" :items="[
        ['href' => '/components/base/tabs', 'label' => $say('Tabs', 'تب')],
        ['href' => '/components/base/segmented', 'label' => $say('Segmented', 'سگمنت')],
        ['href' => '/components/base/nav-menu', 'label' => $say('Nav menu', 'منوی ناوبری'), 'current' => true],
        ['href' => '/components/base/breadcrumbs', 'label' => $say('Breadcrumbs', 'بردکرامب')],
    ]" />
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The customer page’s second row', 'ردیف دوم صفحهٔ مشتری') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The same menu one level down: a sub-nav under the page heading, named for where it lives — label becomes the nav’s aria-label, so a screen reader announces "بخش‌های صفحهٔ مشتری" instead of a nameless nav.', 'همان منو یک سطح پایین‌تر: زیرمنو زیر عنوان صفحه، به نام جایی که زندگی می‌کند — label همان aria-labelِ nav می‌شود تا صفحه‌خوان به‌جای یک nav بی‌نام، «بخش‌های صفحهٔ مشتری» را اعلام کند.') }}
        </p>
    </div>
    <div style="display: grid; gap: .75rem">
        <div class="pg-row" style="gap: .75rem">
            <x-nx::avatar name="مریم رستمی" />
            <strong>{{ $say('Maryam Rostami', 'مریم رستمی') }}</strong>
            <x-nx::badge tone="gold">{{ $say('Guild member', 'عضو گروه') }}</x-nx::badge>
        </div>
        <x-nx::nav-menu :items="$customerNav" :label="$say('Customer page sections', 'بخش‌های صفحهٔ مشتری')" />
        <p style="margin: 0; padding: .75rem 1rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg); background: var(--nx-surface-2); color: var(--nx-text-muted)">
            {{ $say('Customer since 1402 — 12 orders, all delivered. The overview you would render under this row.', 'مشتری از ۱۴۰۲ — ۱۲ سفارش، همه تحویل‌شده. نمای کلی‌ای که زیر این ردیف رندر می‌شود.') }}
        </p>
    </div>
</section>
