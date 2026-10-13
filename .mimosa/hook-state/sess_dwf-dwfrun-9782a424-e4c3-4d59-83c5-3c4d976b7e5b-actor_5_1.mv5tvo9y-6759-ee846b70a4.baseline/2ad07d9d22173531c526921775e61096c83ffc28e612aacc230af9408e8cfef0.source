{{--
    The chip filter as an actual shop filter: the accent thumb springs under
    the checked chip and the grid below really refilters on the server —
    counts roll in Persian digits, an empty category shows its disabled chip.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $products = [
        ['name' => $say('Wool coat', 'کتان پشمی'), 'cat' => 'clothes', 'price' => 2450000, 'hot' => true],
        ['name' => $say('Linen shirt', 'پیراهن کتانی'), 'cat' => 'clothes', 'price' => 890000, 'hot' => false],
        ['name' => $say('Cotton socks', 'جوراب پنبه‌ای'), 'cat' => 'clothes', 'price' => 120000, 'hot' => false],
        ['name' => $say('Design systems, the book', 'سیستم طراحی، کتاب'), 'cat' => 'books', 'price' => 640000, 'hot' => true],
        ['name' => $say('Motion handbook', 'دستنامهٔ حرکت'), 'cat' => 'books', 'price' => 480000, 'hot' => false],
        ['name' => $say('Wooden rattle', 'غشای چوبی'), 'cat' => 'toys', 'price' => 260000, 'hot' => false],
        ['name' => $say('Building blocks', 'مکعب‌های ساختنی'), 'cat' => 'toys', 'price' => 520000, 'hot' => true],
    ];
    $cat = $state['cat'] ?? 'all';
    $shown = $cat === 'all' ? $products : array_values(array_filter($products, fn ($product) => $product['cat'] === $cat));

    $countFor = fn (string $key) => NabuXUI::formatNumber(count($cat === $key ? $products : array_filter($products, fn ($p) => $p['cat'] === $key)));
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Filtering a real shelf', 'پالایش یک قفسهٔ واقعی') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Tick a category — the thumb springs under the chip and the shelf below refilters server-side through wire:model. The row scrolls and fades at its edges when it overflows.', 'یک دسته را انتخاب کنید — thumb زیر چیپ می‌پرد و قفسهٔ پایین با wire:model روی سرور پالایش می‌شود. ردیف سرریز شود، لبه‌هایش هنگام لغزش محو می‌شوند.') }}
        </p>
    </div>
    <x-nx::chip-filter label="{{ $say('Shelf category', 'دستهٔ قفسه') }}" :value="$cat" wire:model.live="state.cat"
        :options="[
            'all' => $say('All', 'همه'),
            'clothes' => ['label' => $say('Clothes', 'پوشاک'), 'icon' => 'heart'],
            'books' => ['label' => $say('Books', 'کتاب'), 'icon' => 'file'],
            'toys' => ['label' => $say('Toys', 'اسباب‌بازی'), 'icon' => 'sparkles'],
            'soldout' => ['label' => $say('Sold out', 'تمام‌شده'), 'icon' => 'x', 'disabled' => true],
        ]"
        :counts="[
            'all' => $countFor('all'),
            'clothes' => $countFor('clothes'),
            'books' => $countFor('books'),
            'toys' => $countFor('toys'),
        ]" />

    <p style="margin: 0; color: var(--nx-text-muted)">
        {{ NabuXUI::formatNumber(count($shown)).' '.$say('of', 'از').' '.NabuXUI::formatNumber(count($products)).' '.$say('items on the shelf', 'کالا روی قفسه') }}
    </p>

    <div class="pg-grid" style="grid-template-columns: repeat(auto-fill, minmax(11rem, 1fr)); gap: .75rem">
        @forelse ($shown as $product)
            <div style="display: grid; gap: .5rem; padding: 1rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); background: var(--nx-surface-2)">
                <div aria-hidden="true" style="block-size: 4.5rem; border-radius: var(--nx-radius-lg); background: linear-gradient(135deg, var(--nx-accent-soft), var(--nx-glass))"></div>
                <div class="pg-row" style="justify-content: space-between; gap: .5rem">
                    <strong style="font-size: var(--nx-text-sm)">{{ $product['name'] }}</strong>
                    @if ($product['hot'])<x-nx::badge tone="accent" dot>{{ $say('hot', 'پرفروش') }}</x-nx::badge>@endif
                </div>
                <span style="font-size: var(--nx-text-sm); color: var(--nx-text-muted)">{{ NabuXUI::formatNumber($product['price']).' '.$say('tomans', 'تومان') }}</span>
            </div>
        @empty
            <x-nx::empty-state icon="folder" size="sm" style="grid-column: 1 / -1"
                :title="$say('This shelf is empty', 'این قفسه خالی است')"
                :description="$say('Pick another category above — or “All” to bring everything back.', 'دستهٔ دیگری را انتخاب کنید — یا «همه» تا همه برگردند.')">
                <x-slot:actions>
                    <x-nx::button variant="primary" size="sm" icon="grid" wire:click="$set('state.cat', 'all')">{{ $say('Show everything', 'نمایش همه') }}</x-nx::button>
                </x-slot:actions>
            </x-nx::empty-state>
        @endforelse
    </div>
</section>
