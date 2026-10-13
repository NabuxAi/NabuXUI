{{--
    The ring as a usage dashboard: storage, bandwidth and build minutes each
    with their own size, tone and label — one carries a custom slot instead of
    the default percentage.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('This month on the team plan', 'این ماه روی پلن تیم') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Each ring draws itself in on reveal and rests under prefers-reduced-motion. The middle one swaps the built-in percent for a slot of its own.', 'هر حلقه هنگام reveal کشیده می‌شود و زیر prefers-reduced-motion می‌خوابد. وسطی درصدِ پیش‌فرض را با اسلات دلخواهش عوض کرده.') }}
        </p>
    </div>
    <div class="pg-row" style="gap: 2.5rem; justify-content: space-around; flex-wrap: wrap">
        <div style="display: grid; justify-items: center; gap: .6rem">
            <x-nx::progress-ring :value="68" size="7rem" label="{{ $say('Storage', 'فضای ذخیره') }}" />
            <span style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $say('Storage', 'فضای ذخیره') }} · {{ NabuXUI::formatNumber(41, 0) }}/60 GB</span>
        </div>
        <div style="display: grid; justify-items: center; gap: .6rem">
            <x-nx::progress-ring :value="91" tone="warning" size="7rem" thickness="10px" label="{{ $say('Bandwidth', 'پهنای باند') }}">
                {{ NabuXUI::formatNumber(91, 0) }}٪
            </x-nx::progress-ring>
            <span style="color: var(--nx-warning-text); font-size: var(--nx-text-sm)">{{ $say('Bandwidth — nearly gone', 'پهنای باند — تقریباً تمام') }}</span>
        </div>
        <div style="display: grid; justify-items: center; gap: .6rem">
            <x-nx::progress-ring :value="34" size="7rem" label="{{ $say('Build minutes', 'دقیقهٔ بیلد') }}">
                <span style="font-size: var(--nx-text-xs)">{{ NabuXUI::formatNumber(690) }}′</span>
            </x-nx::progress-ring>
            <span style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $say('Build minutes left', 'دقیقهٔ بیلدِ باقی') }}</span>
        </div>
    </div>
    <x-nx::alert tone="warning" :title="$say('At 91% you have 11 days to act', 'در ۹۱٪، ۱۱ روز فرصت دارید')">
        {{ $say('Raise the plan or trim the video CDN — the ring turns red at 95%.', 'پلن را بالا ببرید یا CDN ویدیو را سبک کنید — حلقه در ۹۵٪ سرخ می‌شود.') }}
        <x-slot:actions>
            <x-nx::button size="sm" variant="secondary" wire:click="save('{{ $say('Plan upgraded', 'پلن ارتقا یافت') }}')">{{ $say('Raise the plan', 'بالا بردن پلن') }}</x-nx::button>
        </x-slot:actions>
    </x-nx::alert>
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Small, for cards and rows', 'کوچک، برای کارت و ردیف') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">{{ $say('A size dial keeps the ring legible at row height; success tone for finished migrations.', 'با size حلقه در ارتفاع ردیف هم خوانا می‌ماند؛ رنگ موفقیت برای مهاجرت‌های تمام‌شده.') }}</p>
        <div class="pg-row" style="gap: 1.25rem">
            <div style="display: grid; justify-items: center; gap: .4rem">
                <x-nx::progress-ring :value="100" tone="success" size="3rem" thickness="4px" label="{{ $say('Users migrated', 'کاربران منتقل‌شده') }}" />
                <span style="font-size: var(--nx-text-xs); color: var(--nx-text-muted)">{{ $say('users', 'کاربران') }}</span>
            </div>
            <div style="display: grid; justify-items: center; gap: .4rem">
                <x-nx::progress-ring :value="52" size="3rem" thickness="4px" label="{{ $say('Orders migrated', 'سفارش‌های منتقل‌شده') }}" />
                <span style="font-size: var(--nx-text-xs); color: var(--nx-text-muted)">{{ $say('orders', 'سفارش‌ها') }}</span>
            </div>
            <div style="display: grid; justify-items: center; gap: .4rem">
                <x-nx::progress-ring :value="13" tone="danger" size="3rem" thickness="4px" label="{{ $say('Media migrated', 'رسانه‌های منتقل‌شده') }}" />
                <span style="font-size: var(--nx-text-xs); color: var(--nx-text-muted)">{{ $say('media', 'رسانه') }}</span>
            </div>
        </div>
        <x-nx::button size="sm" variant="ghost" icon="play" wire:click="save('{{ $say('Migration resumed', 'مهاجرت ادامه یافت') }}')">{{ $say('Resume migration', 'ادامهٔ مهاجرت') }}</x-nx::button>
    </section>

    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A single hero number', 'یک عدد قهرمان') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">{{ $say('XL ring with a thinner stroke and the score in the slot — the sign-in health of the whole region.', 'حلقهٔ XL با خطی نازک‌تر و امتیاز در اسلات — سلامت ورود کل منطقه.') }}</p>
        <div class="pg-row" style="gap: 1.5rem">
            <x-nx::progress-ring :value="99" tone="success" size="9rem" thickness="8px" label="{{ $say('Sign-in success', 'موفقیت ورود') }}">
                <strong style="font: 700 var(--nx-text-xl) var(--nx-font-display)">{{ NabuXUI::formatNumber(99, 0) }}٪</strong>
            </x-nx::progress-ring>
            <div style="display: grid; gap: .5rem">
                <span style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $say('Sign-in success, 30 days', 'موفقیت ورود، ۳۰ روز') }}</span>
                <span class="pg-row" style="gap: .5rem">
                    <x-nx::badge tone="success" dot>{{ $say('all regions', 'همهٔ مناطق') }}</x-nx::badge>
                    <x-nx::badge tone="neutral">iad1 · fra1 · syd1</x-nx::badge>
                </span>
            </div>
        </div>
    </section>
</div>
