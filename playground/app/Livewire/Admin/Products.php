<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\AdminPanel;
use Livewire\Attributes\Layout;
use Livewire\Component;
use NabuXUI\NabuXUI;

/**
 * /admin/products — the shop's catalogue: a product-card grid under category
 * chips and live search. The cart is the server's truth (the cards' add /
 * remove actions round-trip through addToCart / removeFromCart so the morph
 * keeps every card honest), stock badges breathe from the card's own states
 * and each card carries a meta strip — the published/draft badge, the sold
 * count and the view link. Product shots are inline SVG data URIs (no
 * external assets); words come from the admin.products_* keys.
 */
#[Layout('layouts.admin')]
class Products extends Component
{
    use AdminPanel;

    /** Free-text search over title, description, category and id (live, debounced in the view). */
    public string $search = '';

    /** The category chips' value: 'all' | 'apparel' | 'devices' | 'home' | 'media'. */
    public string $category = 'all';

    /** The ids of the products sitting in the cart — the cards render from this. */
    public array $cart = [];

    /**
     * The shared trait's updated() hook types its value ?string, which the
     * cart array crashes against — same behaviour for the locale switch,
     * but through a wider door. (Class methods win over the trait's.)
     */
    public function updated(string $name, mixed $value): void
    {
        if ($name === 'locale' && is_string($value) && in_array($value, ['fa', 'en'], true)) {
            session(['locale' => $value]);
            $this->redirect($this->path);
        }
    }

    public function mount(): void
    {
        $this->rememberLocale();
    }

    /** The card's add-action: one more product in the cart, one toast. */
    public function addToCart(string $title, float $price): void
    {
        $id = $this->idOfTitle($title);
        if ($id === null || in_array($id, $this->cart, true)) {
            return;
        }

        $this->cart[] = $id;
        $this->toast(__('admin.products_added_toast'), tone: 'success');
    }

    /** The card's remove-action: the product leaves the cart quietly. */
    public function removeFromCart(string $title, float $price): void
    {
        $id = $this->idOfTitle($title);
        if ($id !== null) {
            $this->cart = array_values(array_diff($this->cart, [$id]));
        }
    }

    /** The header's “new product” button (demo). */
    public function newProduct(): void
    {
        $this->toast(__('admin.products_new_toast'), tone: 'success');
    }

    public function render()
    {
        $locale = str_replace('_', '-', app()->getLocale());
        $fmt = fn ($value) => NabuXUI::formatNumber((float) $value, 0, $locale);

        $categories = [
            'apparel' => __('admin.products_cat_apparel'),
            'devices' => __('admin.products_cat_devices'),
            'home' => __('admin.products_cat_home'),
            'media' => __('admin.products_cat_media'),
        ];

        $catalogue = $this->catalogue();

        // Chips + counts: the four categories, however few sit in each.
        $counts = ['all' => count($catalogue)];
        foreach (array_keys($categories) as $id) {
            $counts[$id] = count(array_filter($catalogue, fn (array $product) => $product['category'] === $id));
        }

        // The search box + the chips, then the grid.
        $q = mb_strtolower(trim($this->search));
        $visible = array_values(array_filter($catalogue, function (array $product) use ($q, $categories) {
            if ($this->category !== 'all' && $product['category'] !== $this->category) {
                return false;
            }
            if ($q === '') {
                return true;
            }

            $haystack = mb_strtolower(implode(' ', [
                $product['title'], $product['desc'], $categories[$product['category']], $product['id'],
            ]));

            return str_contains($haystack, $q);
        }));

        $cards = [];
        foreach ($visible as $product) {
            $cards[] = [
                'id' => $product['id'],
                'title' => $product['title'],
                'category' => $categories[$product['category']],
                'image' => ['src' => $product['art'], 'alt' => $product['desc']],
                'price' => $product['price'],
                'compareAt' => $product['compareAt'],
                'rating' => $product['rating'],
                'ratingCount' => $product['ratingCount'],
                'stock' => $product['stock'],
                'stockCount' => $product['stockCount'],
                'inCart' => in_array($product['id'], $this->cart, true),
                'draft' => $product['draft'],
                'sold' => $fmt($product['sold']),
            ];
        }

        return view('livewire.admin.products', [
            'cards' => $cards,
            'chips' => ['all' => __('admin.products_filter_all')] + $categories,
            'counts' => array_map(fn ($count) => $fmt($count), $counts),
            'countText' => str_replace(':count', $fmt(count($visible)), __('admin.products_count')),
            'locale' => $locale,
        ]);
    }

    /** The product behind a card's title (titles come from the language file and are unique). */
    private function idOfTitle(string $title): ?string
    {
        foreach ($this->catalogue() as $product) {
            if ($product['title'] === $title) {
                return $product['id'];
            }
        }

        return null;
    }

    /**
     * Six sample products over the language file's names: prices in Toman,
     * ratings with their review counts, the three stock states (one low, one
     * out) and one draft among the published. Shots are inline SVG line art.
     *
     * @return array<int, array<string, mixed>>
     */
    private function catalogue(): array
    {
        $art = $this->art();

        return [
            ['id' => 'p1', 'title' => __('admin.products_item_1'), 'desc' => __('admin.products_item_1_desc'),
                'category' => 'devices', 'price' => 4850000, 'compareAt' => 5900000, 'rating' => 4.6, 'ratingCount' => 312,
                'stock' => 'in', 'stockCount' => null, 'sold' => 214, 'draft' => false, 'art' => $art['watch']],
            ['id' => 'p2', 'title' => __('admin.products_item_2'), 'desc' => __('admin.products_item_2_desc'),
                'category' => 'devices', 'price' => 2190000, 'compareAt' => 2690000, 'rating' => 4.4, 'ratingCount' => 216,
                'stock' => 'low', 'stockCount' => 2, 'sold' => 342, 'draft' => false, 'art' => $art['headphones']],
            ['id' => 'p3', 'title' => __('admin.products_item_3'), 'desc' => __('admin.products_item_3_desc'),
                'category' => 'apparel', 'price' => 3650000, 'compareAt' => null, 'rating' => 4.8, 'ratingCount' => 94,
                'stock' => 'in', 'stockCount' => null, 'sold' => 87, 'draft' => false, 'art' => $art['bag']],
            ['id' => 'p4', 'title' => __('admin.products_item_4'), 'desc' => __('admin.products_item_4_desc'),
                'category' => 'home', 'price' => 890000, 'compareAt' => 1120000, 'rating' => 4.2, 'ratingCount' => 158,
                'stock' => 'in', 'stockCount' => null, 'sold' => 126, 'draft' => true, 'art' => $art['lamp']],
            ['id' => 'p5', 'title' => __('admin.products_item_5'), 'desc' => __('admin.products_item_5_desc'),
                'category' => 'home', 'price' => 720000, 'compareAt' => null, 'rating' => 4.5, 'ratingCount' => 67,
                'stock' => 'out', 'stockCount' => null, 'sold' => 58, 'draft' => false, 'art' => $art['flask']],
            ['id' => 'p6', 'title' => __('admin.products_item_6'), 'desc' => __('admin.products_item_6_desc'),
                'category' => 'apparel', 'price' => 1480000, 'compareAt' => 1890000, 'rating' => 4.7, 'ratingCount' => 203,
                'stock' => 'in', 'stockCount' => null, 'sold' => 175, 'draft' => false, 'art' => $art['scarf']],
        ];
    }

    /**
     * The six product shots as inline SVG data URIs — line art on a tinted
     * field, the 4/5 frame the product card's media expects, no assets.
     *
     * @return array<string, string>
     */
    private function art(): array
    {
        $shot = fn (string $body) => 'data:image/svg+xml,'.rawurlencode(
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 500">'.$body.'</svg>'
        );

        return [
            'watch' => $shot('<rect width="400" height="500" fill="#fbf0d9"/><rect x="172" y="70" width="56" height="120" rx="26" fill="none" stroke="#b45309" stroke-width="12"/><rect x="172" y="310" width="56" height="120" rx="26" fill="none" stroke="#b45309" stroke-width="12"/><circle cx="200" cy="250" r="86" fill="none" stroke="#b45309" stroke-width="16"/><line x1="200" y1="250" x2="200" y2="200" stroke="#b45309" stroke-width="14" stroke-linecap="round"/><line x1="200" y1="250" x2="236" y2="276" stroke="#b45309" stroke-width="14" stroke-linecap="round"/>'),
            'headphones' => $shot('<rect width="400" height="500" fill="#eef0ff"/><path d="M118 260 v-50 a82 82 0 0 1 164 0 v50" fill="none" stroke="#4f46e5" stroke-width="16" stroke-linecap="round"/><rect x="96" y="244" width="44" height="96" rx="20" fill="#4f46e5"/><rect x="260" y="244" width="44" height="96" rx="20" fill="#4f46e5"/>'),
            'bag' => $shot('<rect width="400" height="500" fill="#ffede8"/><path d="M140 224 v-24 a60 60 0 0 1 120 0 v24" fill="none" stroke="#9a3412" stroke-width="14" stroke-linecap="round"/><path d="M112 214 h176 l-14 184 a16 16 0 0 1 -16 14 H142 a16 16 0 0 1 -16 -14 Z" fill="none" stroke="#9a3412" stroke-width="14" stroke-linejoin="round"/><rect x="182" y="244" width="36" height="30" rx="8" fill="#9a3412"/>'),
            'lamp' => $shot('<rect width="400" height="500" fill="#e6fffb"/><circle cx="200" cy="248" r="18" fill="#0d9488"/><path d="M110 262 a90 90 0 0 1 180 0 Z" fill="none" stroke="#0d9488" stroke-width="14" stroke-linejoin="round"/><line x1="200" y1="196" x2="200" y2="168" stroke="#0d9488" stroke-width="12" stroke-linecap="round"/><line x1="200" y1="262" x2="200" y2="382" stroke="#0d9488" stroke-width="14" stroke-linecap="round"/><path d="M130 402 h140" stroke="#0d9488" stroke-width="14" stroke-linecap="round"/>'),
            'flask' => $shot('<rect width="400" height="500" fill="#eff6ff"/><rect x="150" y="200" width="100" height="200" rx="34" fill="none" stroke="#2563eb" stroke-width="14"/><path d="M170 202 v-26 h60 v26" fill="none" stroke="#2563eb" stroke-width="14" stroke-linejoin="round"/><rect x="160" y="140" width="80" height="26" rx="12" fill="#2563eb"/><path d="M150 264 h100" stroke="#2563eb" stroke-width="12" stroke-linecap="round"/>'),
            'scarf' => $shot('<rect width="400" height="500" fill="#f3e8ff"/><path d="M158 124 v226" stroke="#7c3aed" stroke-width="46" stroke-linecap="round" opacity="0.38"/><path d="M240 144 v226" stroke="#7c3aed" stroke-width="46" stroke-linecap="round"/><path d="M226 372 v34 M254 372 v34" stroke="#7c3aed" stroke-width="10" stroke-linecap="round"/>'),
        ];
    }
}
