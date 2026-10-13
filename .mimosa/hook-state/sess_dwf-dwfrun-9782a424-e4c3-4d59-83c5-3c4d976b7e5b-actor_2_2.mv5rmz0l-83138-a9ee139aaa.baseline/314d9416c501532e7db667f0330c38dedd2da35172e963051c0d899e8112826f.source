{{--
    Number fields doing real work: a checkout line where quantity drives the
    total on the server (hold + to count up fast), a guest count with a hard
    max, and a design tool's inspector where you scrub the labels sideways
    like in Figma.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $n = fn ($v, $d = 0) => \NabuXUI\NabuXUI::formatNumber($v, $d);

    $qty = (int) ($state['qty'] ?? 2);
    $price = 1_850_000;
@endphp
<style>
    .nf-cart { display: grid; grid-template-columns: auto minmax(0, 1fr) auto; gap: 1rem; align-items: center; padding: 1rem 1.25rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); background: var(--nx-surface); }
    .nf-thumb { display: grid; place-items: center; inline-size: 3.5rem; block-size: 3.5rem; border-radius: var(--nx-radius-lg); background: var(--nx-accent-soft); color: var(--nx-accent-text); }
    .nf-thumb .nx-icon { inline-size: 1.5rem; block-size: 1.5rem; }
    .nf-inspector { display: grid; grid-template-columns: repeat(auto-fit, minmax(9rem, 1fr)); gap: 1rem; padding: 1.25rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); background: var(--nx-surface-2); }
    @media (max-width: 36rem) { .nf-cart { grid-template-columns: auto 1fr; } .nf-cart > :last-child { grid-column: 1 / -1; } }
</style>

<section class="nf-cart" aria-labelledby="nf-cart-title">
    <span class="nf-thumb" aria-hidden="true">{{ \NabuXUI\NabuXUI::icon('music') }}</span>
    <div>
        <h3 class="pg-title" id="nf-cart-title" style="margin: 0; font-size: var(--nx-text-base)">{{ $say('Wireless headphones', 'هدفون بی‌سیم') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $n($price) }} {{ $say('IRR each', 'ریال هر عدد') }} · <strong style="color: var(--nx-text)">{{ $say('Total', 'جمع') }} {{ $n($qty * $price) }}</strong></p>
    </div>
    <x-nx::number-field :label="$say('Quantity', 'تعداد')" name="qty" :value="$qty" :min="1" :max="20" wire:model.live="state.qty" />
</section>

<section class="pg-box" style="gap: 1rem" aria-labelledby="nf-trip-title">
    <h3 class="pg-title" id="nf-trip-title" style="margin: 0">{{ $say('Trip details', 'جزئیات سفر') }}</h3>
    <div class="pg-row" style="gap: 1.5rem; align-items: start; flex-wrap: wrap">
        <x-nx::number-field :label="$say('Guests', 'مسافران')" :value="2" :min="1" :max="6" :hint="$say('Up to 6 per room', 'حداکثر ۶ نفر در هر اتاق')" />
        <x-nx::number-field :label="$say('Budget per night', 'بودجهٔ هر شب')" :value="120" :min="0" :step="10" :large-step="100"
            :format="['style' => 'currency', 'currency' => 'USD', 'maximumFractionDigits' => 0]" />
    </div>
</section>

<section class="nf-inspector" aria-labelledby="nf-inspector-title">
    <h3 class="pg-title" id="nf-inspector-title" style="grid-column: 1 / -1; margin: 0">{{ $say('Inspector — drag a label sideways', 'بازرس — برچسب را افقی بکشید') }}</h3>
    <x-nx::number-field label="W" :value="320" :min="0" :max="1440" />
    <x-nx::number-field label="H" :value="180" :min="0" :max="1440" />
    <x-nx::number-field :label="$say('Radius', 'گردی')" :value="12" :min="0" :max="64" />
    <x-nx::number-field :label="$say('Opacity', 'شفافیت')" :value="0.8" :min="0" :max="1" :step="0.05"
        :format="['style' => 'percent']" />
</section>
