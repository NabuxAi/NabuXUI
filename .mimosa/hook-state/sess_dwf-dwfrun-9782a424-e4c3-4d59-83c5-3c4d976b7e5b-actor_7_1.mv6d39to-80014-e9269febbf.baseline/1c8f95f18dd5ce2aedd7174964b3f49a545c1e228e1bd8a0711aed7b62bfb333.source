{{--
    Spinners at their real scale: the button that shows one by itself on a
    slow action, a row that swaps its text for a spinner while the server
    thinks, and the standalone sizes.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Buttons that wait, honestly', 'دکمه‌هایی که صادقانه صبر می‌کنند') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Nothing to wire up: a wire:click on the button is enough — the label becomes a spinner at the exact same width, then the toast lands.', 'چیز خاصی لازم نیست: همان wire:click روی دکمه کافی است — برچسب با همان عرض به اسپینر بدل می‌شود و بعد توست می‌نشیند.') }}
        </p>
    </div>
    <div class="pg-row">
        <x-nx::button variant="primary" icon="check" wire:click="save('{{ $say('Inventory synced', 'انبار همگام شد') }}')">{{ $say('Sync inventory', 'همگام‌سازی انبار') }}</x-nx::button>
        <x-nx::button variant="secondary" icon="upload" wire:click="save('{{ $say('Backup finished', 'پشتیبان تمام شد') }}')">{{ $say('Run backup', 'گرفتن پشتیبان') }}</x-nx::button>
        <x-nx::button variant="ghost" icon="grid" wire:click="save('{{ $say('Catalog rebuilt', 'کاتالگ بازسازی شد') }}')">{{ $say('Rebuild catalog', 'بازسازی کاتالوگ') }}</x-nx::button>
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A row that swaps, not shoves', 'ردیفی که جابه‌جا می‌کند، نه هُل می‌دهد') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('While the report runs, the row keeps its height and puts a text-sized spinner where the words were — layout never jumps.', 'تا گزارش می‌دود، ردیف ارتفاعش را حفظ می‌کند و به‌جای کلمات اسپینری هم‌اندازهٔ متن می‌گذارد — چیدمان نمی‌پرد.') }}
        </p>
    </div>
    <div class="pg-row" style="justify-content: space-between; padding: .7rem .9rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg)">
        <span class="pg-row" style="gap: .6rem">
            <x-nx::icon name="chart" />
            <span wire:loading.remove wire:target="save">{{ $say('Last month — 48,260 orders', 'ماه گذشته — ۴۸٬۲۶۰ سفارش') }}</span>
            <x-nx::spinner size="sm" wire:loading wire:target="save" :label="$say('Counting orders', 'شمارش سفارش‌ها')" />
        </span>
        <x-nx::button size="sm" variant="secondary" wire:click="save('{{ $say('Report refreshed', 'گزارش بروز شد') }}')">{{ $say('Refresh', 'بروزرسانی') }}</x-nx::button>
    </div>
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Sizes', 'اندازه‌ها') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">{{ $say('sm rides the baseline of a sentence, md stands alone, lg owns a blank slate page.', 'sm روی خط پایه جمله می‌نشیند، md تنها می‌ایستد، lg صفحهٔ خالی را از آنِ خودش می‌کند.') }}</p>
        <div class="pg-row" style="gap: 1.5rem">
            <x-nx::spinner size="sm" />
            <x-nx::spinner />
            <x-nx::spinner size="lg" />
        </div>
    </section>

    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('In a sentence', 'درون جمله') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">{{ $say('Screen readers hear the label, not the circle; the eye gets both.', 'صفحه‌خوان برچسب را می‌شنود نه دایره را؛ چشم هر دو را می‌بیند.') }}</p>
        <p class="pg-row" style="gap: .5rem">
            <x-nx::spinner size="sm" :label="$say('Opening the studio', 'باز کردن استودیو')" />
            {{ $say('Opening the studio…', 'استودیو باز می‌شود…') }}
        </p>
    </section>
</div>
