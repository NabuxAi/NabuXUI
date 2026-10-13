{{--
    The chip as a real shop filter: active filters come off with a click, the
    shelf follows instantly (Alpine-local — this filter never needed a server),
    and a second card shows the plain, non-removable label variant.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $tags = [
        'in_stock' => $say('In stock', 'موجود'),
        'free_ship' => $say('Free shipping', 'ارسال رایگان'),
        'new' => $say('New', 'تازه'),
        'sale' => $say('On sale', 'تخفیف‌دار'),
    ];
    $products = [
        ['name' => $say('Lapis notebook', 'دفتر لاجوردی'), 'tags' => ['in_stock', 'new']],
        ['name' => $say('Linen tote', 'کیف کتان'), 'tags' => ['in_stock', 'free_ship']],
        ['name' => $say('Gold-foil pen', 'خودکار طلایی'), 'tags' => ['sale']],
        ['name' => $say('Ink set — 4', 'ست جوهر — ۴'), 'tags' => ['sale', 'free_ship']],
        ['name' => $say('Desk felt pad', 'زیردستی میز'), 'tags' => ['in_stock', 'sale', 'free_ship']],
    ];
@endphp
<div x-data='{{ json_encode(['off' => [], 'products' => $products], JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_AMP) }}'>
    <section class="pg-box" style="gap: 1.25rem">
        <div class="pg-row" style="justify-content: space-between">
            <div>
                <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Filters you can see — and unsee', 'فیلترهایی که دیده می‌شوند — و می‌روند') }}</h3>
                <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                    {{ $say('Every active filter is a removable chip; taking one off reshapes the shelf below instantly, in the browser. The x button and the chip itself both work.', 'هر فیلتر فعال یک چیپِ قابل‌حذف است؛ برداشتنش قفسهٔ پایین را همان لحظه در مرورگر بازمی‌چیند. هم دکمهٔ x کار می‌کند هم خود چیپ.') }}
                </p>
            </div>
            <x-nx::button size="sm" variant="ghost" icon="x" x-show="off.length > 0" @click="off = []" :aria-label="$say('Reset filters', 'بازنشانی فیلترها')">{{ $say('Reset', 'بازنشانی') }}</x-nx::button>
        </div>
        <div class="pg-row">
            @foreach ($tags as $id => $label)
                <x-nx::chip removable x-show="! off.includes('{{ $id }}')" @click="off.push('{{ $id }}')">{{ $label }}</x-nx::chip>
            @endforeach
            <span style="color: var(--nx-text-subtle); font-size: var(--nx-text-sm)"
                x-show="off.length === {{ count($tags) }}">{{ $say('No filters left — the shelf shows everything.', 'فیلتری نماند — قفسه همه‌چیز را نشان می‌دهد.') }}</span>
        </div>
        <div class="pg-grid" style="grid-template-columns: repeat(auto-fill, minmax(min(100%, 14rem), 1fr))">
            <template x-for="product in products.filter(p => p.tags.every(t => ! off.includes(t)))" :key="product.name">
                <div class="pg-box" style="padding: 1rem; gap: .5rem">
                    <x-nx::skeleton shape="block" height="4.5rem" />
                    <strong style="font-weight: 600" x-text="product.name"></strong>
                    <span style="color: var(--nx-text-subtle); font-size: var(--nx-text-sm)">
                        {{ $say('carries', 'برچسب‌های') }} <span x-text="product.tags.length"></span> {{ $say('tags', 'تگ') }}
                    </span>
                </div>
            </template>
        </div>
    </section>
</div>

<section class="pg-box" style="gap: 1rem">
    <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The plain label, without an x', 'برچسب ساده، بدون x') }}</h3>
    <p style="margin: 0; color: var(--nx-text-muted)">
        {{ $say('A product page: style chips on one row, material chips on the other. No removal — they inform, they do not act.', 'صفحهٔ محصول: چیپ‌های سبک در یک ردیف، جنس در ردیف دیگر. حذف ندارند — خبر می‌دهند، کاری نمی‌کنند.') }}
    </p>
    <div class="pg-row">
        <span style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $say('Style:', 'سبک:') }}</span>
        <x-nx::chip>{{ $say('Minimal', 'مینیمال') }}</x-nx::chip>
        <x-nx::chip>{{ $say('Studio', 'استودیویی') }}</x-nx::chip>
        <x-nx::chip>{{ $say('Everyday', 'روزمره') }}</x-nx::chip>
    </div>
    <div class="pg-row">
        <span style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $say('Material:', 'جنس:') }}</span>
        <x-nx::chip>{{ $say('Linen', 'کتان') }}</x-nx::chip>
        <x-nx::chip>{{ $say('Recycled paper', 'کاغذ بازیافتی') }}</x-nx::chip>
    </div>
</section>
