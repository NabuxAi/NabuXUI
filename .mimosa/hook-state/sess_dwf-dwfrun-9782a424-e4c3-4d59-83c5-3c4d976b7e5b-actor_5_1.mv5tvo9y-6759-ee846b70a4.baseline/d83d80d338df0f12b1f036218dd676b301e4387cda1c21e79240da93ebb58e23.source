{{--
    The mega menu as a SaaS header: a Products panel with three columns of
    icon+description items, a two-column Resources panel, and plain links —
    one of them the current page. Moving between triggers slides the panel in
    from the side you came from.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A product header', 'سربرگ محصول') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Hover or keyboard into “Product” and “Resources”; the panel glides in from the side you arrived from. Arrow-down opens from the keyboard, and the underline follows the focused link.', 'با موس یا کیبورد وارد «محصول» و «منابع» شوید؛ پنل از سمت ورودتان می‌لغزد. فلش‌پایین آن را با کیبورد باز می‌کند و خط زیرِ لینک‌ها دنبال فوکوس می‌رود.') }}
        </p>
    </div>
    <div style="border-block-end: 1px solid var(--nx-border); padding-block-end: 1rem">
        <x-nx::mega-menu label="{{ $say('Main navigation', 'راهبری اصلی') }}" :items="[
            ['label' => $say('Product', 'محصول'), 'columns' => 3, 'children' => [
                ['label' => $say('Monitoring', 'مانیتورینگ'), 'icon' => 'chart', 'href' => '/components', 'description' => $say('Live metrics for every service', 'متریک‌های زندهٔ همهٔ سرویس‌ها')],
                ['label' => $say('Alerts', 'هشدارها'), 'icon' => 'bell', 'href' => '/components', 'description' => $say('Rules, routing, on-call', 'قواعد، مسیرها، روی‌تماس')],
                ['label' => $say('Logs', 'لاگ‌ها'), 'icon' => 'file', 'href' => '/components', 'description' => $say('Searchable to the field', 'جست‌وجو تا سطح فیلد')],
                ['label' => $say('Data warehouse', 'انبار داده'), 'icon' => 'layers', 'href' => '/components', 'description' => $say('Sync, model, query', 'هم‌گام‌سازی، مدل، پرس‌وجو')],
                ['label' => $say('Edge functions', 'توابع لبه'), 'icon' => 'zap', 'href' => '/components', 'description' => $say('40 regions, zero config', '۴۰ منطقه، بدون پیکربندی')],
                ['label' => $say('CDN', 'شبکهٔ توزیع'), 'icon' => 'globe', 'href' => '/components', 'description' => $say('Cached where your users are', 'کش‌شده همان‌جا که کاربران‌اند')],
            ]],
            ['label' => $say('Resources', 'منابع'), 'columns' => 2, 'children' => [
                ['label' => $say('Docs', 'مستندات'), 'icon' => 'file', 'href' => '/components', 'description' => $say('Guides and API reference', 'راهنما و مرجع API')],
                ['label' => $say('Changelog', 'تغییرات'), 'icon' => 'layers', 'href' => '/components', 'description' => $say('Shipped every Thursday', 'هر پنجشنبه منتشر می‌شود')],
                ['label' => $say('Status', 'وضعیت'), 'icon' => 'zap', 'href' => '/components', 'description' => $say('99.99% and the incident log', '٪۹۹٫۹۹ و گزارش حوادث')],
                ['label' => $say('Talk to us', 'گفت‌وگو'), 'icon' => 'message', 'href' => '/components', 'description' => $say('Humans, in your timezone', 'انسان‌ها، در منطقهٔ زمانی شما')],
            ]],
            ['label' => $say('Pricing', 'قیمت‌گذاری'), 'href' => '/components'],
            ['label' => $say('Demos', 'دموها'), 'href' => '/components', 'current' => true],
        ]" />
    </div>
    <p style="margin: 0; color: var(--nx-text-muted); min-block-size: 7rem">
        {{ $say('The page body flows under the open panel — try moving the pointer straight from “Product” to “Resources” to see the slide.', 'بدنهٔ صفحه از زیر پنلِ باز رد می‌شود — برای دیدن لغزش، از «محصول» مستقیم به «منابع» بروید.') }}
    </p>
</section>
