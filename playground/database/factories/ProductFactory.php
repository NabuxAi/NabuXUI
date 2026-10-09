<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * A small, plausible catalog so every seed run produces the same
     * sensible products (re-seeding stays deterministic and demo-friendly).
     *
     * @var list<array{name: string, description: string, category: string, price: float, stock: int, status: string}>
     */
    public const CATALOG = [
        ['name' => 'Aurora Wireless Headphones', 'description' => 'Over-ear ANC headphones with 40h battery life.', 'category' => 'Audio', 'price' => 149.99, 'stock' => 42, 'status' => 'active'],
        ['name' => 'Nimbus Mechanical Keyboard', 'description' => 'Hot-swappable 75% keyboard with silent tactile switches.', 'category' => 'Accessories', 'price' => 119.00, 'stock' => 25, 'status' => 'active'],
        ['name' => 'Orbit 4K Webcam', 'description' => '4K webcam with auto-framing and dual noise-cancelling mics.', 'category' => 'Video', 'price' => 89.50, 'stock' => 60, 'status' => 'active'],
        ['name' => 'Pulse Fitness Tracker', 'description' => 'Slim tracker with heart-rate, sleep and swim tracking.', 'category' => 'Wearables', 'price' => 74.90, 'stock' => 0, 'status' => 'draft'],
        ['name' => 'Vertex Standing Desk Mat', 'description' => 'Anti-fatigue mat with contoured massage surface.', 'category' => 'Office', 'price' => 45.00, 'stock' => 130, 'status' => 'active'],
        ['name' => 'Halo Desk Lamp', 'description' => 'Dimmable LED lamp with wireless charging base.', 'category' => 'Lighting', 'price' => 59.99, 'stock' => 8, 'status' => 'active'],
        ['name' => 'Zephyr USB-C Hub', 'description' => '8-in-1 hub with dual HDMI, ethernet and 100W passthrough.', 'category' => 'Accessories', 'price' => 69.00, 'stock' => 95, 'status' => 'active'],
        ['name' => 'Echo Studio Microphone', 'description' => 'Cardioid condenser mic with built-in pop filter.', 'category' => 'Audio', 'price' => 129.99, 'stock' => 17, 'status' => 'active'],
        ['name' => 'Drift Portable SSD 1TB', 'description' => 'Shock-resistant NVMe SSD with 1050 MB/s read.', 'category' => 'Storage', 'price' => 109.00, 'stock' => 0, 'status' => 'archived'],
        ['name' => 'Lumen Ring Light 18"', 'description' => 'Bi-color ring light with remote and phone mount.', 'category' => 'Video', 'price' => 39.99, 'stock' => 74, 'status' => 'active'],
        ['name' => 'Cobalt Travel Charger', 'description' => '65W GaN charger with three ports and foldable plug.', 'category' => 'Power', 'price' => 34.50, 'stock' => 210, 'status' => 'active'],
        ['name' => 'Quartz Smart Speaker Mini', 'description' => 'Compact speaker with far-field voice assistant.', 'category' => 'Audio', 'price' => 49.00, 'stock' => 0, 'status' => 'archived'],
    ];

    protected static int $cursor = 0;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $item = self::CATALOG[self::$cursor % count(self::CATALOG)];
        self::$cursor++;

        return [
            'name' => $item['name'],
            'description' => $item['description'],
            'price' => $item['price'],
            'stock' => $item['stock'],
            'status' => $item['status'],
            'category' => $item['category'],
        ];
    }
}
