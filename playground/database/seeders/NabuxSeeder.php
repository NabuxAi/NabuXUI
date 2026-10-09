<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NabuxSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed demo data for the Filament admin: 12 products, 18 orders
     * and 24 tasks. Idempotent — always wipes its own tables first.
     */
    public function run(): void
    {
        // Orders first: they hold the FK to products.
        Order::query()->delete();
        Task::query()->delete();
        Product::query()->delete();

        $products = Product::factory(12)->create();

        for ($i = 1; $i <= 18; $i++) {
            $product = $products[$i % $products->count()];
            $quantity = 1 + $i % 3;

            Order::factory()->create([
                'number' => 'ORD-'.(1000 + $i),
                'product_id' => $product->id,
                'total' => round($product->price * $quantity, 2),
            ]);
        }

        Task::factory(24)->create();
    }
}
