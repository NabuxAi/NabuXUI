{{--
    Liquid ripple around live content: a pick-your-plan gallery where every
    press sends a wave through the cards (and the buttons still work), then a
    stronger ripple under a checkout summary.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1rem; padding: 0; overflow: clip">
    <x-nx::liquid-ripple :strength="20">
        <div style="display: grid; gap: 1rem; padding: 1.5rem">
            <div>
                <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Pick a plan — the shelf reacts', 'یک طرح را انتخاب کنید — قفسه واکنش می‌دهد') }}</h3>
                <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                    {{ $say('Every press, anywhere in the frame, sends one wave through the cards; the buttons stay clickable and the text selectable.', 'هر فشار، هرجای قاب، یک موج بین کارت‌ها می‌فرستد؛ دکمه‌ها کلیک‌پذیر و متن انتخاب‌پذیر می‌مانند.') }}
                </p>
            </div>
            <div class="pg-grid" style="grid-template-columns: repeat(auto-fit, minmax(11rem, 1fr)); gap: .875rem">
                @foreach ([
                    ['name' => $say('Starter', 'آغازگر'), 'price' => 0, 'note' => $say('One project', 'یک پروژه')],
                    ['name' => $say('Studio', 'استودیو'), 'price' => 290000, 'note' => $say('Five projects', 'پنج پروژه')],
                    ['name' => $say('Agency', 'آژانس'), 'price' => 890000, 'note' => $say('Unlimited', 'نامحدود')],
                ] as $plan)
                    <div style="display: grid; gap: .625rem; align-content: start; padding: 1.125rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); background: var(--nx-surface-2)">
                        <strong>{{ $plan['name'] }}</strong>
                        <span style="font: 700 var(--nx-text-xl) / 1.1 var(--nx-font-display)">
                            {{ $plan['price'] === 0 ? $say('Free', 'رایگان') : NabuXUI::formatNumber($plan['price']).' '.$say('tomans', 'تومان') }}
                        </span>
                        <span style="font-size: var(--nx-text-sm); color: var(--nx-text-muted)">{{ $plan['note'] }}</span>
                        <x-nx::button size="sm" variant="secondary" icon="check" wire:click="ping(@js($say('Chose '.$plan['name'], 'انتخاب: '.$plan['name'])))">{{ $say('Choose', 'انتخاب') }}</x-nx::button>
                    </div>
                @endforeach
            </div>
        </div>
    </x-nx::liquid-ripple>
</section>

<section class="pg-box" style="gap: 1rem; padding: 0; overflow: clip">
    <x-nx::liquid-ripple :strength="28" :duration="1400">
        <div style="display: grid; gap: 1rem; padding: 1.5rem">
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A stronger wave under the checkout', 'موج قوی‌تر زیر تسویه') }}</h3>
            <div style="display: grid; gap: .75rem; padding: 1.125rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); background: var(--nx-surface-2)">
                @foreach ([
                    ['label' => $say('Wool coat', 'کتان پشمی'), 'amount' => 2450000],
                    ['label' => $say('Express courier', 'پیک فوری'), 'amount' => 49000],
                ] as $row)
                    <div class="pg-row" style="justify-content: space-between; font-size: var(--nx-text-sm)">
                        <span>{{ $row['label'] }}</span>
                        <strong>{{ NabuXUI::formatNumber($row['amount']).' '.$say('tomans', 'تومان') }}</strong>
                    </div>
                @endforeach
                <div class="pg-row" style="justify-content: space-between; border-block-start: 1px solid var(--nx-border); padding-block-start: .75rem">
                    <strong>{{ $say('Total', 'جمع') }}</strong>
                    <strong>{{ NabuXUI::formatNumber(2499000).' '.$say('tomans', 'تومان') }}</strong>
                </div>
                <x-nx::button variant="primary" icon="lock" wire:click="save(@js($say('Payment prepared', 'پرداخت آماده شد')))">{{ $say('Go to payment', 'رفتن به پرداخت') }}</x-nx::button>
            </div>
            <p style="margin: 0; color: var(--nx-text-muted); font-size: var(--nx-text-sm)">
                {{ $say('strength="28" duration="1400" — a heavier, slower pour for moments of consequence.', 'با strength="28" و duration="1400" — ریختنی سنگین‌تر و آرام‌تر برای لحظه‌های مهم.') }}
            </p>
        </div>
    </x-nx::liquid-ripple>
</section>
