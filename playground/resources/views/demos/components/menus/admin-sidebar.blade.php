{{--
    The admin sidebar on its own: a docs site nav linking the playground's real
    demo pages (with a count badge on each group), a minimal brand-only
    variant, and the collapse behaviour explained for the in-shell case.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A docs sidebar, linking real pages', 'نوار کناری مستندات، با لینک به صفحات واقعی') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Standalone it is pure server state: href items are real links, the active prop places the spring highlight, and numeric badges come out in Persian digits.', 'بیرون از پوسته فقط server-state است: آیتم‌های href لینک واقعی‌اند، پراپ active جای هایلایت فنری را تعیین می‌کند و نشان‌های عددی با ارقام فارسی می‌آیند.') }}
            </p>
        </div>
        <x-nx::admin-sidebar brand="{{ $say('Nabu Docs', 'مستندات نابو') }}" active="menus" label="{{ $say('Docs navigation', 'راهبری مستندات') }}" :groups="[
            ['label' => $say('Start', 'شروع'), 'items' => [
                ['id' => 'intro', 'label' => $say('Introduction', 'مقدمه'), 'icon' => 'file', 'href' => '/components'],
                ['id' => 'install', 'label' => $say('Install', 'نصب'), 'icon' => 'upload', 'href' => '/components'],
            ]],
            ['label' => $say('Demos', 'دموها'), 'items' => [
                ['id' => 'base', 'label' => $say('Base', 'پایه'), 'icon' => 'zap', 'href' => '/components/base/button'],
                ['id' => 'menus', 'label' => $say('Menus & navigation', 'منو و ناوبری'), 'icon' => 'grid', 'badge' => 22, 'href' => '/components'],
                ['id' => 'glass', 'label' => $say('Glass', 'شیشه'), 'icon' => 'layers', 'badge' => 9, 'href' => '/components/glass/glass-panel'],
            ]],
            ['label' => $say('Account', 'حساب'), 'items' => [
                ['id' => 'settings', 'label' => $say('Settings', 'تنظیمات'), 'icon' => 'settings', 'disabled' => true],
            ]],
        ]">
            <x-slot:footer>
                <div class="pg-row" style="gap: .625rem; padding: .5rem .625rem; border-radius: var(--nx-radius-lg); background: var(--nx-surface-2); font-size: var(--nx-text-sm); font-weight: 600">
                    <x-nx::avatar name="Hussein" size="sm" />
                    <span>{{ $say('Hussein · Pro', 'حسین · پرو') }}</span>
                </div>
            </x-slot:footer>
        </x-nx::admin-sidebar>
    </section>

    <section class="pg-box" style="gap: 1rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Minimal, brand only', 'مینیمال، فقط برند') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('One group without a label keeps the bar quiet; the first letter of the brand becomes the mark. Inside the admin shell the same component also follows the shell’s state — buttons pick the active item and the collapse toggle appears.', 'یک گروه بدون برچسب نوار را ساکت نگه می‌دارد؛ حرف اول برند نشان می‌شود. داخل پوستهٔ پنل، همین کامپوننت وضعیت پوسته را هم دنبال می‌کند — دکمه‌ها آیتم فعال را انتخاب می‌کنند و دکمهٔ جمع‌کردن ظاهر می‌شود.') }}
            </p>
        </div>
        <x-nx::admin-sidebar brand="{{ $say('Nabu', 'نابو') }}" active="inbox" :groups="[
            ['items' => [
                ['id' => 'inbox', 'label' => $say('Inbox', 'صندوق'), 'icon' => 'mail'],
                ['id' => 'drafts', 'label' => $say('Drafts', 'پیش‌نویس‌ها'), 'icon' => 'edit'],
                ['id' => 'archive', 'label' => $say('Archive', 'بایگانی'), 'icon' => 'folder'],
            ]],
        ]" style="max-inline-size: 18rem" />
    </section>
</div>
