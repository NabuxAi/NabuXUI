{{--
    Code the way an agent hands it over: a Livewire component with the lines it
    changed highlighted, a patch it proposes (diff colours), and a long shell command
    that wraps on demand — all left-to-right inside an RTL page.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $labels = ['wrap' => $say('Wrap lines', 'شکستن خطوط'), 'code' => $say('Code', 'کد')];

    $component = <<<'PHP'
<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class OrderList extends Component
{
    public string $status = 'paid';

    // Uses the composite index (status, created_at).
    public function render()
    {
        return view('livewire.order-list', [
            'orders' => Order::where('status', $this->status)->latest()->paginate(20),
        ]);
    }
}
PHP;

    $patch = <<<'DIFF'
 export function total(items: Item[], coupon?: Coupon) {
-  const sum = items.reduce((acc, item) => acc + item.price, 0);
-  return sum - coupon.amount;
+  const sum = items.reduce((acc, item) => acc + item.price * item.qty, 0);
+  // An empty coupon used to arrive as null and crash checkout.
+  return Math.max(0, sum - (coupon?.amount ?? 0));
 }
DIFF;

    $shell = 'docker run --rm -it -v "$PWD":/app -w /app -e APP_ENV=testing -e DB_CONNECTION=sqlite php:8.4-cli vendor/bin/pest --parallel --coverage --min=85 --filter=Checkout';
@endphp
<div style="display: grid; gap: 1.25rem; max-inline-size: 48rem">
    <x-nx::code-block filename="app/Livewire/OrderList.php" language="php" highlight="12-17" :code="$component" :labels="$labels" />
    <x-nx::code-block filename="resources/js/cart.ts" language="ts" diff :code="$patch" :labels="$labels" />
    <x-nx::code-block language="bash" :code="$shell" :line-numbers="false" :labels="$labels" />
</div>
