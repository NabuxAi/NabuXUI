{{--
    Auto height's real scenarios: a shipping-options card whose body changes
    with the tab (the card resizes on a spring instead of jumping), and an
    order summary that expands to show its line items.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Delivery options card', 'کارت گزینه‌های ارسال') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Each tab has a different amount of content. The card follows on a spring — the same happens when Livewire re-renders what is inside.', 'محتوای هر تب اندازهٔ متفاوتی دارد. کارت با فنر دنبالش می‌کند — وقتی Livewire محتوای داخلش را دوباره رندر کند هم همین می‌شود.') }}
        </p>
    </div>
    <div x-data="{ tab: 'post' }" style="max-inline-size: 30rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); background: var(--nx-surface); box-shadow: var(--nx-shadow-sm)">
        <div role="tablist" aria-label="{{ $say('Delivery', 'ارسال') }}" class="pg-row" style="gap: .25rem; padding: .5rem; border-block-end: 1px solid var(--nx-border)">
            @foreach (['post' => $say('Post', 'پست'), 'courier' => $say('Courier', 'پیک'), 'pickup' => $say('Pick-up', 'تحویل حضوری')] as $key => $name)
                <x-nx::button size="sm" role="tab" x-bind:data-variant="tab === '{{ $key }}' ? 'secondary' : 'ghost'"
                    x-bind:aria-selected="tab === '{{ $key }}'" x-on:click="tab = '{{ $key }}'">{{ $name }}</x-nx::button>
            @endforeach
        </div>
        <x-nx::auto-height>
            <div style="padding: 1rem 1.25rem">
                <div x-show="tab === 'post'" role="tabpanel">
                    <p style="margin: 0"><strong>{{ $say('3–5 working days', '۳ تا ۵ روز کاری') }}</strong> · {{ $say('free over $50', 'رایگان برای خرید بالای ۲ میلیون') }}</p>
                </div>
                <div x-show="tab === 'courier'" role="tabpanel" style="display: grid; gap: .75rem">
                    <p style="margin: 0"><strong>{{ $say('Today, within 3 hours', 'امروز، ظرف ۳ ساعت') }}</strong></p>
                    <p style="margin: 0; color: var(--nx-text-muted)">{{ $say('Available inside the city. The courier calls 15 minutes before arriving; you can track them live from your order page.', 'فقط داخل شهر. پیک ۱۵ دقیقه پیش از رسیدن تماس می‌گیرد و از صفحهٔ سفارش می‌توانید زنده دنبالش کنید.') }}</p>
                    <p style="margin: 0; color: var(--nx-text-muted)">{{ $say('Fee: $4.90, refunded if late.', 'هزینه: ۹۰ هزار تومان، در صورت تأخیر برگشت داده می‌شود.') }}</p>
                </div>
                <div x-show="tab === 'pickup'" role="tabpanel" style="display: grid; gap: .5rem">
                    <p style="margin: 0"><strong>{{ $say('Ready tomorrow from 10:00', 'آماده از فردا ساعت ۱۰') }}</strong></p>
                    <p style="margin: 0; color: var(--nx-text-muted)">{{ $say('Valiasr St, No. 12 — bring your order code.', 'خیابان ولیعصر، پلاک ۱۲ — کد سفارش را همراه داشته باشید.') }}</p>
                </div>
            </div>
        </x-nx::auto-height>
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Expanding order summary', 'خلاصهٔ سفارشِ بازشونده') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The toggle changes its label and icon too, so the motion is never the only cue.', 'دکمه برچسب و آیکونش را هم عوض می‌کند تا حرکت تنها نشانه نباشد.') }}
        </p>
    </div>
    <div x-data="{ open: false }" style="max-inline-size: 30rem; padding: 1rem 1.25rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); background: var(--nx-surface)">
        <div class="pg-row" style="justify-content: space-between">
            <strong>{{ $say('Total: $86.40', 'جمع: ۳٬۴۵۶٬۰۰۰ تومان') }}</strong>
            <x-nx::button size="sm" variant="ghost" x-on:click="open = !open" x-bind:aria-expanded="open" aria-controls="fx-order-lines">
                <span x-text="open ? @js($say('Hide items', 'پنهان کردن اقلام')) : @js($say('Show 3 items', 'نمایش ۳ قلم'))">{{ $say('Show 3 items', 'نمایش ۳ قلم') }}</span>
            </x-nx::button>
        </div>
        <x-nx::auto-height spring="snappy" id="fx-order-lines">
            <ul x-show="open" role="list" style="display: grid; gap: .5rem; margin: .75rem 0 0; padding: 0; list-style: none; color: var(--nx-text-muted)">
                <li class="pg-row" style="justify-content: space-between"><span>{{ $say('Leather sneakers ×1', 'کفش چرم ×۱') }}</span><span>{{ $say('$64.00', '۲٬۵۶۰٬۰۰۰') }}</span></li>
                <li class="pg-row" style="justify-content: space-between"><span>{{ $say('Wool socks ×2', 'جوراب پشمی ×۲') }}</span><span>{{ $say('$17.50', '۷۰۰٬۰۰۰') }}</span></li>
                <li class="pg-row" style="justify-content: space-between"><span>{{ $say('Courier', 'پیک') }}</span><span>{{ $say('$4.90', '۱۹۶٬۰۰۰') }}</span></li>
            </ul>
        </x-nx::auto-height>
    </div>
</section>
