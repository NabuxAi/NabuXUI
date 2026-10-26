{{-- shop: the product moment — gallery placeholder, price, chips, buy row. --}}
@php $fa = app()->getLocale() === 'fa'; $say = fn (string $e, string $f) => $fa ? $f : $e; @endphp
<div style="max-inline-size: 30rem; margin-inline: auto; display: grid; gap: 1rem">
    <div style="aspect-ratio: 16 / 9; border-radius: var(--nx-radius-lg); border: 1px solid var(--nx-border);
                background: radial-gradient(80% 70% at 70% 20%, var(--nx-accent-soft), transparent 70%), var(--nx-surface-2);
                display: grid; place-items: center; color: var(--nx-text-muted); font-size: var(--nx-text-sm)">
        {{ $say('Product photo', 'عکس محصول') }}
    </div>
    <div style="display: grid; gap: .4rem">
        <div class="aist-recipe-row" style="justify-content: space-between">
            <h3 style="margin: 0; font: 700 var(--nx-text-lg) / 1.3 var(--nx-font-display)">{{ $say('Nimbus K2 keyboard', 'کیبورد نیمبوس K2') }}</h3>
            <b style="font: 800 var(--nx-text-lg) / 1 var(--nx-font-display)">€149</b>
        </div>
        <div class="aist-recipe-row">
            <x-nx::rating :value="4.6" />
            <span style="font-size: var(--nx-text-xs); color: var(--nx-text-muted)">{{ $say('312 reviews', '۳۱۲ نظر') }}</span>
        </div>
        <div class="aist-recipe-row">
            <x-nx::badge tone="success">{{ $say('In stock', 'موجود') }}</x-nx::badge>
            <x-nx::badge tone="accent">{{ $say('Free shipping', 'ارسال رایگان') }}</x-nx::badge>
        </div>
    </div>
    <div class="aist-recipe-row">
        <x-nx::button variant="primary" icon="zap" style="flex: 1">{{ $say('Add to cart', 'افزودن به سبد') }}</x-nx::button>
        <x-nx::button variant="ghost" icon="heart" aria-label="{{ $say('Save', 'ذخیره') }}" />
    </div>
</div>
