{{--
    <x-nx::product-card title="هدفون بی‌سیم نابو" category="صدا" href="#"
        :image="['src' => '…', 'alt' => 'هدفون نابو، نمای سه‌رخ']"
        :price="4850000" :compareAt="6900000" currency="تومان"
        :rating="4.5" :ratingCount="1284" stock="low" :stockCount="2" />

    A shop card: the image zooms inside its frame on hover, the pre-discount
    price is crossed out beside the current one, stars fill to the fraction of
    the rating, the stock badge speaks in states (in / low / out — low shows
    the units left and breathes), and the add-to-cart button morphs into its
    "added" state (icon swap + label crossfade + recolour).

    State: `in-cart` is the truth — controlled from outside, or uncontrolled
    (a local toggle that flips the morph). `add-action` / `remove-action`
    name Livewire methods (addToCart($price…) — your signature) called after
    the optimistic flip; without them nx-add / nx-remove events bubble from
    the component for you to handle. Out of stock disables the button.
--}}
@props([
    'title' => null,
    'href' => null,
    'category' => null,
    'image' => null,
    'price' => 0,
    'compareAt' => null,
    'currency' => null,
    'decimals' => 0,
    'rating' => null,
    'ratingCount' => null,
    'stock' => null,
    'stockCount' => null,
    'inCart' => false,
    'addAction' => null,
    'removeAction' => null,
    'labels' => [],
    'locale' => null,
])
@php
    use NabuXUI\NabuXUI;

    $locale = str_replace('_', '-', $locale ?? app()->getLocale());
    $lang = substr($locale, 0, 2);
    // The card's own words live in the core i18n table (resources/lang, generated
    // from it with :name placeholders); `labels` overrides may use {name} or :name.
    $say = function (string $key, array $params = []) use ($lang, $labels) {
        $text = (string) ($labels[$key] ?? __('nabuxui::ui.'.$key, [], $lang));
        foreach ($params as $name => $value) {
            $text = str_replace([":{$name}", "{{$name}}"], (string) $value, $text);
        }

        return $text;
    };

    $stock = in_array($stock, ['in', 'low', 'out'], true) ? $stock : null;
    $stockText = match ($stock) {
        'in' => $say('productInStock'),
        'low' => $say('productLowStock', ['count' => NabuXUI::formatNumber((int) ($stockCount ?? 1), 0, $locale)]),
        'out' => $say('productOutOfStock'),
        default => null,
    };

    // The saving, as a whole percent — only when the price really dropped.
    $percent = ($compareAt !== null && $compareAt > $price && $price > 0)
        ? (int) round((1 - $price / $compareAt) * 100)
        : null;

    $rating = $rating !== null ? min(max((float) $rating, 0), 5) : null;
    $ratingText = $rating !== null && $rating > 0
        ? NabuXUI::formatNumber($rating, fmod($rating, 1.0) === 0.0 ? 0 : 1, $locale)
        : null;

    $decimals = max(0, min(6, (int) $decimals));
    $inCart = filter_var($inCart, FILTER_VALIDATE_BOOLEAN);
    $soldOut = $stock === 'out';

    $image = is_array($image) ? [
        'src' => (string) ($image['src'] ?? ''),
        'alt' => (string) ($image['alt'] ?? (is_string($title) ? $title : '')),
    ] : null;

    $id = NabuXUI::id('nx-product-card');
    $listenerAttrs = $attributes->whereStartsWith(['x-on:', '@', 'wire:']);
    $rest = $attributes->whereDoesntStartWith(['x-on:', '@', 'wire:']);
@endphp
<article {{ $rest->class('nx-product-card')->merge([
    'data-stock' => $stock,
    'data-nx-reveal' => '',
]) }} {{ $listenerAttrs }} x-data="nxProductCard(@js([
    'inCart' => $inCart,
    'addAction' => $addAction,
    'removeAction' => $removeAction,
    'title' => is_string($title) ? $title : null,
    'price' => (float) $price,
    'labels' => [
        'added' => $say('productAdded'),
        'removed' => $say('productRemove'),
    ],
]))">
    @if ($image)
        <div class="nx-product-card-media">
            <img class="nx-product-card-img" src="{{ $image['src'] }}" alt="{{ $image['alt'] }}" loading="lazy" />
            @if ($percent !== null || $stockText !== null)
                <div class="nx-product-card-tags">
                    @if ($percent !== null)
                        <span class="nx-product-card-discount">{{ $say('productDiscount', ['value' => NabuXUI::formatNumber($percent, 0, $locale)]) }}</span>
                    @endif
                    @if ($stockText !== null)
                        <span class="nx-product-card-stock" data-stock="{{ $stock }}">
                            <i class="nx-product-card-stock-dot" aria-hidden="true"></i>
                            <span>{{ $stockText }}</span>
                        </span>
                    @endif
                </div>
            @endif
        </div>
    @endif

    <div class="nx-product-card-body">
        @if (! $image && ($percent !== null || $stockText !== null))
            {{-- No photo: the tags ride along the body's top edge instead of over it. --}}
            <div class="nx-product-card-tags">
                @if ($percent !== null)
                    <span class="nx-product-card-discount">{{ $say('productDiscount', ['value' => NabuXUI::formatNumber($percent, 0, $locale)]) }}</span>
                @endif
                @if ($stockText !== null)
                    <span class="nx-product-card-stock" data-stock="{{ $stock }}">
                        <i class="nx-product-card-stock-dot" aria-hidden="true"></i>
                        <span>{{ $stockText }}</span>
                    </span>
                @endif
            </div>
        @endif
        @if ($category)<p class="nx-product-card-category">{{ $category }}</p>@endif
        <h3 class="nx-product-card-title">
            @if ($href)
                <a class="nx-product-card-link" href="{{ $href }}">{{ $title }}</a>
            @else
                {{ $title }}
            @endif
        </h3>

        @if ($ratingText !== null || $ratingCount !== null)
            @php
                $starsLabel = $ratingText !== null ? $say('stars', ['count' => $ratingText, 'total' => NabuXUI::formatNumber(5, 0, $locale)]) : null;
            @endphp
            <div class="nx-product-card-rating" @if ($starsLabel) role="img" aria-label="{{ $starsLabel }}" @endif>
                @if ($ratingText !== null)
                    <span class="nx-product-card-stars" style="--nx-product-rating: {{ $rating }}" aria-hidden="true">
                        <span class="nx-product-card-stars-row nx-product-card-stars-bg">
                            @for ($star = 0; $star < 5; $star++){{ NabuXUI::icon('star') }}@endfor
                        </span>
                        <span class="nx-product-card-stars-fill">
                            <span class="nx-product-card-stars-row">
                                @for ($star = 0; $star < 5; $star++){{ NabuXUI::icon('star') }}@endfor
                            </span>
                        </span>
                    </span>
                    <span class="nx-product-card-rating-value">{{ $ratingText }}</span>
                @endif
                @if ($ratingCount !== null)
                    <span class="nx-product-card-rating-count">{{ $say('productReviews', ['count' => NabuXUI::formatNumber((int) $ratingCount, 0, $locale)]) }}</span>
                @endif
            </div>
        @endif

        <div class="nx-product-card-pricing">
            <span class="nx-product-card-price">
                {{ NabuXUI::formatNumber($price, $decimals, $locale) }}
                @if ($currency)<span class="nx-product-card-currency">{{ $currency }}</span>@endif
            </span>
            @if ($compareAt !== null && $compareAt > $price)
                <s class="nx-product-card-compare">
                    {{ NabuXUI::formatNumber($compareAt, $decimals, $locale) }}
                    @if ($currency)<span class="nx-product-card-currency">{{ $currency }}</span>@endif
                </s>
            @endif
        </div>

        <button type="button" class="nx-product-card-add" wire:key="{{ $id }}-add"
            @if ($inCart) data-in-cart @endif
            x-bind:data-in-cart="inCart ? '' : null"
            @if ($soldOut) disabled @else x-bind:disabled="busy" @endif
            x-on:click="toggle()">
            <span class="nx-product-card-add-icon" aria-hidden="true">
                <span data-part="idle">{{ NabuXUI::icon('plus') }}</span>
                <span data-part="done">{{ NabuXUI::icon('check') }}</span>
            </span>
            {{-- Both labels live on one grid cell; the resting one is opacity-0, so it must also leave the accessibility tree. --}}
            <span class="nx-product-card-add-label">
                <span data-part="idle" @if ($inCart) aria-hidden="true" @endif
                    x-bind:aria-hidden="inCart ? 'true' : null">{{ $soldOut ? $say('productOutOfStock') : $say('productAddToCart') }}</span>
                <span data-part="done" @if (! $inCart) aria-hidden="true" @endif
                    x-bind:aria-hidden="inCart ? null : 'true'">{{ $say('productAdded') }}</span>
            </span>
        </button>
    </div>
    <p class="nx-visually-hidden" role="status" x-text="announce"></p>
</article>
