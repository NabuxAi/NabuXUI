{{--
    The stat strip twice: a shop overview that counts up once on scroll, and
    the same strip whose values the server actually changes — press “simulate
    the afternoon” and every figure rolls to its new number after the morph.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $bump = (int) ($state['bump'] ?? 0);
    $stats = [
        ['label' => $say('Orders today', 'سفارش امروز'), 'value' => 128 + 34 * $bump, 'icon' => 'layers', 'caption' => $say('+12 vs yesterday', '+۱۲ نسبت به دیروز')],
        ['label' => $say('Visits', 'بازدید'), 'value' => 9412 + 1150 * $bump, 'icon' => 'globe'],
        ['label' => $say('Avg. basket', 'سبد میانگین'), 'value' => 764000 + 50000 * $bump, 'icon' => 'chart'],
        ['label' => $say('In stock', 'موجود در انبار'), 'value' => 312 - 4 * $bump, 'icon' => 'grid'],
    ];
@endphp

<section class="pg-box" style="gap: 1rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The shop’s morning board', 'تابلوی صبح فروشگاه') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Figures are server-rendered in the page’s digits; each holds at zero until it scrolls into view, then rolls up exactly once.', 'اعداد روی سرور با ارقام زبان صفحه رندر می‌شوند؛ هر کدام تا رسیدن به دید صفر می‌ماند و دقیقاً یک‌بار بالا می‌غلتد.') }}
        </p>
    </div>
    <x-nx::stat-strip :label="$say('Morning figures', 'اعداد صبح')" :stats="$stats" />
</section>

<section class="pg-box" style="gap: 1rem">
    <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Change them from the server', 'از سرور عوضشان کنید') }}</h3>
    <p style="margin: 0; color: var(--nx-text-muted)">
        {{ $say('A Livewire action recomputes the same strip — after the morph, every digit rolls from the old number to the new one.', 'یک اکشن Livewire همین نوار را دوباره حساب می‌کند — پس از morph هر رقم از عدد قبلی به تازه می‌غلتد.') }}
    </p>
    <div class="pg-row">
        <x-nx::button variant="primary" icon="zap" wire:click="$set('state.bump', {{ $bump + 1 }})">
            {{ $say('Simulate the afternoon', 'شبیه‌سازی بعدازظهر') }}
        </x-nx::button>
        @if ($bump > 0)
            <x-nx::button variant="ghost" icon="arrow-left" wire:click="$set('state.bump', {{ max(0, $bump - 1) }})">{{ $say('Back to morning', 'بازگشت به صبح') }}</x-nx::button>
        @endif
    </div>
    <x-nx::stat-strip :label="$say('Live figures', 'اعداد زنده')" :stats="$stats" />
</section>
