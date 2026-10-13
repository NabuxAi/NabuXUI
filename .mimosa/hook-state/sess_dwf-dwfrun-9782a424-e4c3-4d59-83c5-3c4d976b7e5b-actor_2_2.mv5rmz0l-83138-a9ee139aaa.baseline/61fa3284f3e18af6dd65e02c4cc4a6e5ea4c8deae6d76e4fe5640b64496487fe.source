{{--
    The loading skeleton's real scenarios: the four variants side by side, and a
    live swap — a Livewire button "loads" a period and the skeleton stands in
    for the chart while the request runs.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $ready = (bool) ($state['ready'] ?? true);
@endphp

<div class="pg-grid">
    <section class="pg-box" style="grid-column: 1 / -1; gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Four shapes of waiting', 'چهار شکلِ انتظار') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Match the skeleton to the chart that is coming so the layout does not jump: a line, an area, bars or a donut. The shimmer sweeps toward inline-end (right to left on this page in Persian) and turns into a slow pulse under reduced motion. Each one is a polite status region that says “Loading chart”.', 'اسکلت را هم‌شکلِ نموداری که می‌آید انتخاب کنید تا چیدمان نپرد: خط، ناحیه، میله یا دونات. درخشش به سمت پایانِ خط می‌رود (در فارسی از راست به چپ) و زیر کاهش حرکت به تپشی آرام تبدیل می‌شود. هر کدام ناحیهٔ وضعیت مؤدبانه‌ای است که «در حال بارگذاری نمودار» را می‌گوید.') }}
            </p>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(14rem, 1fr)); gap: 1.5rem">
            <x-nx::chart-loading variant="line" height="9rem" />
            <x-nx::chart-loading variant="area" height="9rem" :legend="3" />
            <x-nx::chart-loading variant="bar" height="9rem" />
            <x-nx::chart-loading variant="donut" height="9rem" />
        </div>
    </section>

    <section class="pg-box" style="grid-column: 1 / -1; gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Standing in for a real chart', 'جانشینِ نمودار واقعی') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Toggle the data: while it is "loading" the skeleton keeps the card’s title and height, then the stacked area takes its place and draws in.', 'داده را جابه‌جا کنید: تا وقتی «در حال بارگذاری» است اسکلت عنوان و بلندی کارت را نگه می‌دارد و بعد نمودار انباشته جایش را می‌گیرد و رسم می‌شود.') }}
            </p>
        </div>
        <div class="pg-row">
            <x-nx::button size="xs" variant="ghost" wire:click="$set('state.ready', false)">{{ $say('Start loading', 'شروع بارگذاری') }}</x-nx::button>
            <x-nx::button size="xs" variant="ghost" wire:click="$set('state.ready', true)">{{ $say('Data arrived', 'داده رسید') }}</x-nx::button>
        </div>
        @if ($ready)
            <x-nx::stacked-area wire:key="cp-loading-ready"
                :title="$say('Sessions by device', 'نشست‌ها به تفکیک دستگاه')"
                height="220"
                :labels="$fa ? ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'] : ['Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri']"
                :series="[
                    ['name' => $say('Mobile', 'موبایل'), 'values' => [820, 760, 910, 880, 940, 1010, 690]],
                    ['name' => $say('Desktop', 'دسکتاپ'), 'values' => [410, 520, 560, 540, 500, 380, 260]],
                ]" />
        @else
            <x-nx::chart-loading wire:key="cp-loading-wait" variant="area" height="220px" :title="$say('Sessions by device', 'نشست‌ها به تفکیک دستگاه')" />
        @endif
    </section>
</div>
