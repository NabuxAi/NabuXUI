{{--
    The fold menu as a product nav: sections with descriptions and a current
    page, Alpine click actions, and a CTA living in the footer fold. The second
    one is a compact two-fold variant whose links wire:navigate.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $ping = fn (string $en, string $faText) => '$wire.ping('.json_encode($say($en, $faText)).')';
@endphp

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The workspace menu', 'منوی ورک‌اسپیس') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Three folds read best. Each unfolds from the bottom edge of the one before; Escape folds everything back, and the last fold is our CTA.', 'سه تای بهتر خوانده می‌شود. هر بخش از لبهٔ پایینیِ قبلی باز می‌شود؛ Escape همه را جمع می‌کند و تای آخر CTA ماست.') }}
            </p>
        </div>
        <div class="pg-row" style="align-items: start">
            <x-nx::fold-menu label="{{ $say('Workspace', 'ورک‌اسپیس') }}" :sections="[
                ['title' => $say('Explore', 'کشف'), 'links' => [
                    ['label' => $say('Dashboard', 'داشبورد'), 'icon' => 'grid', 'current' => true],
                    ['label' => $say('New ticket', 'تیکت تازه'), 'icon' => 'message', 'description' => $say('First answer under 2 hours', 'نخستین پاسخ زیر ۲ ساعت'), 'click' => $ping('Ticket created', 'تیکت ساخته شد')],
                    ['label' => $say('Service status', 'وضعیت سرویس'), 'icon' => 'zap', 'click' => '$wire.save('.json_encode($say('All systems healthy', 'همهٔ سرویس‌ها پایدارند')).')'],
                ]],
                ['title' => $say('Team', 'تیم'), 'links' => [
                    ['label' => $say('Invite a teammate', 'دعوت هم‌کار'), 'icon' => 'users', 'click' => $ping('Invite sent', 'دعوت‌نامه فرستاده شد')],
                    ['label' => $say('Team settings', 'تنظیمات تیم'), 'icon' => 'settings'],
                ]],
                ['title' => $say('Account', 'حساب'), 'links' => [
                    ['label' => $say('Billing', 'صورتحساب'), 'icon' => 'chart'],
                    ['label' => $say('Sign out', 'خروج'), 'icon' => 'lock', 'click' => $ping('Signed out (not really)', 'خروج انجام شد (نه واقعاً)')],
                ]],
            ]">
                <x-slot:footer>
                    <x-nx::button variant="primary" size="sm" block icon="sparkles" x-on:click="$wire.ping(@js($say('Trial started', 'کارآزمایی آغاز شد'))); close(true)">
                        {{ $say('Start a free trial', 'شروع کارآزمایی رایگان') }}
                    </x-nx::button>
                </x-slot:footer>
            </x-nx::fold-menu>
        </div>
    </section>

    <section class="pg-box" style="gap: 1rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A compact two-fold, real links', 'دو تای فشرده، با لینک واقعی') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Links can be hrefs (navigate adds wire:navigate) and the panel can hang from the other side with align="end".', 'لینک‌ها می‌توانند href باشند (با navigate همان wire:navigate اضافه می‌شود) و پنل با align="end" از سمت دیگر باز می‌شود.') }}
            </p>
        </div>
        <div class="pg-row" style="justify-content: flex-end; align-items: start">
            <x-nx::fold-menu label="{{ $say('Demos', 'دموها') }}" align="end" navigate :sections="[
                ['title' => $say('Components', 'کامپوننت‌ها'), 'links' => [
                    ['label' => $say('All demos', 'همهٔ دموها'), 'icon' => 'grid', 'href' => '/components', 'current' => true],
                    ['label' => $say('Button', 'دکمه'), 'icon' => 'zap', 'href' => '/components/base/button'],
                ]],
                ['title' => $say('Glass', 'شیشه'), 'links' => [
                    ['label' => 'Glass panel', 'icon' => 'layers', 'href' => '/components/glass/glass-panel'],
                    ['label' => 'Reading glass', 'icon' => 'search', 'href' => '/components/glass/reading-glass'],
                ]],
            ]" />
        </div>
    </section>
</div>
