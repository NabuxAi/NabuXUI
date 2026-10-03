{{--
    Date ranges in two everyday products: a hotel booking (from today on,
    bound to the server, with the nights counted back) and an analytics
    dashboard that leans on the presets. In Persian the grid is the Solar
    Hijri calendar; the values stay ISO days either way.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $n = fn ($v) => \NabuXUI\NabuXUI::formatNumber($v);

    $today = now()->toDateString();
    $stay = $state['stay'] ?? ['start' => now()->addDays(5)->toDateString(), 'end' => now()->addDays(8)->toDateString()];
    $nights = ! empty($stay['start']) && ! empty($stay['end']) ? (int) \Carbon\Carbon::parse($stay['start'])->diffInDays(\Carbon\Carbon::parse($stay['end'])) : 0;
    $rate = 4_200_000;
@endphp
<style>
    .dr-hotel { display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 1.25rem; align-items: center; padding: 1.25rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); background: var(--nx-surface); }
    .dr-hotel h3 { margin: 0; }
    .dr-hotel p { margin: .25rem 0 0; color: var(--nx-text-muted); font-size: var(--nx-text-sm); }
    .dr-total { display: grid; gap: .25rem; justify-items: end; text-align: end; }
    .dr-total strong { font: 700 var(--nx-text-xl) / 1.2 var(--nx-font-display); }
    @media (max-width: 40rem) { .dr-hotel { grid-template-columns: 1fr; } .dr-total { justify-items: start; text-align: start; } }
</style>

<section class="dr-hotel" aria-labelledby="dr-hotel-title">
    <div style="display: grid; gap: .875rem">
        <div>
            <h3 class="pg-title" id="dr-hotel-title">{{ $say('Abbasi Hotel, Isfahan', 'هتل عباسی، اصفهان') }}</h3>
            <p>{{ $say('Courtyard double room · breakfast included', 'اتاق دوتخته رو به حیاط · با صبحانه') }}</p>
        </div>
        <x-nx::date-range-picker name="stay" :label="$say('Check-in – check-out', 'ورود – خروج')" :value="$stay" :min="$today"
            :presets="[]" wire:model.live="state.stay" />
    </div>
    <div class="dr-total">
        <span style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $nights ? $say($nights.' nights', $n($nights).' شب') : $say('Pick your dates', 'تاریخ‌ها را انتخاب کنید') }}</span>
        <strong>{{ $n($nights * $rate) }} {{ $say('IRR', 'ریال') }}</strong>
        <x-nx::button variant="primary" :disabled="! $nights" wire:click="save(@js($say('Room held for 15 minutes', 'اتاق ۱۵ دقیقه برایتان نگه داشته شد')))">{{ $say('Reserve', 'رزرو') }}</x-nx::button>
    </div>
</section>

<section class="pg-box" style="gap: .875rem" aria-labelledby="dr-stats-title">
    <div class="pg-row" style="justify-content: space-between; flex-wrap: wrap; gap: .75rem">
        <div>
            <h3 class="pg-title" id="dr-stats-title" style="margin: 0">{{ $say('Store traffic', 'بازدید فروشگاه') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $say('Presets cover the usual questions; the grid answers the rest.', 'بازه‌های آماده پرسش‌های همیشگی را پوشش می‌دهند؛ تقویم بقیه را.') }}</p>
        </div>
        <x-nx::date-range-picker :label="$say('Report period', 'بازهٔ گزارش')" :max="$today"
            :value="['start' => now()->subDays(6)->toDateString(), 'end' => $today]"
            x-on:nx-change="$wire.ping(@js($say('Report updated', 'گزارش به‌روز شد')))" />
    </div>
    <x-nx::stat-strip :stats="[
        ['label' => $say('Visitors', 'بازدیدکننده'), 'value' => 18240, 'icon' => 'users', 'caption' => $say('+12% on last period', '+۱۲٪ نسبت به دورهٔ قبل')],
        ['label' => $say('Orders', 'سفارش'), 'value' => 642, 'icon' => 'zap', 'caption' => $say('+4% on last period', '+۴٪ نسبت به دورهٔ قبل')],
        ['label' => $say('Returning', 'بازگشتی'), 'value' => 3150, 'icon' => 'heart'],
    ]" />
</section>
