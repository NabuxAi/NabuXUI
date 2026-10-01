{{--
    /admin/products — the shop's catalogue: category chips + live search over
    the product-card grid. The cart round-trips (the cards' add/remove actions
    reach addToCart/removeFromCart so the server stays the truth), stock
    badges breathe from the card's own states and each card carries a meta
    strip — published/draft badge, sold count, view link. A filter that comes
    back empty falls to the empty-state. Words from admin.products_*; every
    number in the locale's digits.
--}}
<x-admin.page active="products" :title="__('admin.products_title')" :subtitle="__('admin.products_subtitle')">
    <style>
        /* Products-page pieces the shared blocks don't carry (tokens only, both themes). */
        .pp-count { font-size: var(--nx-text-sm); color: var(--nx-text-muted); }
        .pp-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 15rem), 1fr)); gap: var(--nx-space-5); align-items: start; }
        .pp-cell { display: grid; gap: var(--nx-space-2); }
        .pp-meta { display: flex; flex-wrap: wrap; align-items: center; gap: var(--nx-space-2); font-size: var(--nx-text-xs); color: var(--nx-text-subtle); }
        .pp-spacer { flex: 1 1 auto; }
        .pp-view { color: var(--nx-accent-text); text-decoration: underline; text-underline-offset: 0.25em; }
        .pp-view:hover { text-decoration-thickness: 0.125em; }
        .pp-view:focus-visible { outline: 2px solid var(--nx-ring); outline-offset: 2px; border-radius: var(--nx-radius-xs); }
    </style>
    <div class="ap-grid">
        <div class="ap-row" style="justify-content: space-between">
            <span class="pp-count">{{ $countText }}</span>
            <x-nx::button variant="primary" icon="plus" wire:click="newProduct">{{ __('admin.products_new') }}</x-nx::button>
        </div>

        <section class="ap-box">
            <div class="ap-row" style="justify-content: space-between">
                <x-nx::input :label="__('admin.products_search_label')"
                    :placeholder="__('admin.products_search_placeholder')" icon="search"
                    wire:model.live.debounce.300ms="search" style="max-inline-size: 20rem" />
                <x-nx::chip-filter :options="$chips" :counts="$counts" :label="__('admin.products_filter_category')" wire:model.live="category" />
            </div>

            @if (count($cards) === 0)
                <x-nx::empty-state icon="heart" size="lg"
                    :title="__('admin.products_empty_title')" :description="__('admin.products_empty_description')" />
            @else
                <div class="pp-grid">
                    @foreach ($cards as $card)
                        <div class="pp-cell" wire:key="p-{{ $card['id'] }}">
                            <x-nx::product-card :title="$card['title']" :category="$card['category']"
                                :image="$card['image']" :price="$card['price']" :compare-at="$card['compareAt']"
                                currency="{{ __('admin.invoice_currency') }}"
                                :rating="$card['rating']" :rating-count="$card['ratingCount']"
                                :stock="$card['stock']" :stock-count="$card['stockCount']"
                                :in-cart="$card['inCart']" add-action="addToCart" remove-action="removeFromCart" />
                            <div class="pp-meta">
                                @if ($card['draft'])
                                    <x-nx::badge tone="warning" data-size="sm">{{ __('admin.products_status_draft') }}</x-nx::badge>
                                @else
                                    <x-nx::badge tone="success" data-size="sm">{{ __('admin.products_status_published') }}</x-nx::badge>
                                @endif
                                <span>{{ __('admin.products_sold') }}: {{ $card['sold'] }}</span>
                                <span class="pp-spacer"></span>
                                <a class="pp-view" href="#">{{ __('admin.products_view') }}</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
</x-admin.page>
