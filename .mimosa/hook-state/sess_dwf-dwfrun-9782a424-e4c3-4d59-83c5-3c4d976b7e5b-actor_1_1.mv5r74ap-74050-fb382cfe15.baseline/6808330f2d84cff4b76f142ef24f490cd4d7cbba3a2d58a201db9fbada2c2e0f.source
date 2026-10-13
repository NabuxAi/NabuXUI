{{--
    Odometer's real scenarios: a sales dashboard whose figures come from the
    server (each refresh re-renders a new value and the changed digits roll,
    up or down), a live visitor counter driven from Alpine, and currency in
    the page's own digits.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $orders = (int) ($this->state['orders'] ?? 1284);
    $revenue = (int) ($this->state['revenue'] ?? 48_250_000);
    $refunds = (int) ($this->state['refunds'] ?? 37);
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Today’s sales, from the server', 'فروش امروز، از سرور') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The figures roll in when they scroll into view. “Refresh” asks Livewire for new numbers: only the digits that changed move, upward when a figure grew and downward when it fell — refunds usually fall.', 'ارقام وقتی وارد دید می‌شوند می‌غلتند. «تازه‌سازی» از Livewire عددهای تازه می‌گیرد: فقط رقم‌های عوض‌شده حرکت می‌کنند، رو به بالا وقتی عدد بزرگ شده و رو به پایین وقتی کوچک شده — بازپرداخت‌ها معمولاً کم می‌شوند.') }}
        </p>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 12rem), 1fr)); gap: 1rem">
        @foreach ([
            [$say('Orders', 'سفارش‌ها'), $orders, []],
            [$say('Revenue (toman)', 'درآمد (تومان)'), $revenue, []],
            [$say('Refunds', 'بازپرداخت‌ها'), $refunds, []],
        ] as [$name, $number, $format])
            <div style="display: grid; gap: .35rem; padding: 1rem 1.25rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); background: var(--nx-surface)">
                <span style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $name }}</span>
                <x-nx::odometer :value="$number" style="font: 700 var(--nx-text-3xl) / 1 var(--nx-font-display)" />
            </div>
        @endforeach
    </div>
    <div class="pg-row">
        <x-nx::button size="sm" variant="secondary" icon="zap" x-data x-on:click="
                $wire.set('state.orders', {{ $orders }} + Math.floor(Math.random() * 90) + 3, false);
                $wire.set('state.revenue', {{ $revenue }} + Math.floor(Math.random() * 4000) * 1000, false);
                $wire.set('state.refunds', Math.max(0, {{ $refunds }} - Math.floor(Math.random() * 9) - 1));
            ">{{ $say('Refresh', 'تازه‌سازی') }}</x-nx::button>
    </div>
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem" x-data="{ n: 312 }">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Visitors right now', 'بازدیدکنندگانِ همین حالا') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('Driven from the browser with set(): the count drifts every couple of seconds, so the last column rolls both ways.', 'از مرورگر با set() هدایت می‌شود: شمارنده هر دو ثانیه کمی جابه‌جا می‌شود، پس ستون آخر به هر دو سو می‌غلتد.') }}
        </p>
        <div class="pg-row" style="align-items: baseline; gap: .5rem"
            x-init="setInterval(() => { n = Math.max(0, n + Math.round((Math.random() - .45) * 14)); $el.querySelector('.nx-odometer').setAttribute('data-value', n) }, 2200)">
            <span style="inline-size: .6rem; block-size: .6rem; border-radius: 50%; background: var(--nx-success)" aria-hidden="true"></span>
            <x-nx::odometer :value="312" :from="300" style="font: 700 var(--nx-text-2xl) / 1 var(--nx-font-display)" />
            <span style="color: var(--nx-text-muted)">{{ $say('online', 'آنلاین') }}</span>
        </div>
    </section>

    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Formatted with Intl', 'قالب‌بندی با Intl') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('Any Intl.NumberFormat options: a dollar balance with cents, and a Persian percentage in Persian digits whatever the page language.', 'هر گزینهٔ Intl.NumberFormat: موجودی دلاری با سنت، و درصدی فارسی با ارقام فارسی، زبان صفحه هرچه باشد.') }}
        </p>
        <div style="display: grid; gap: .75rem">
            <x-nx::odometer :value="12480.5" locale="en" :format="['style' => 'currency', 'currency' => 'USD']" style="font: 600 var(--nx-text-xl) / 1 var(--nx-font-display)" />
            <x-nx::odometer :value="0.874" locale="fa" :format="['style' => 'percent', 'maximumFractionDigits' => 1]" style="font: 600 var(--nx-text-xl) / 1 var(--nx-font-display)" />
        </div>
    </section>
</div>
