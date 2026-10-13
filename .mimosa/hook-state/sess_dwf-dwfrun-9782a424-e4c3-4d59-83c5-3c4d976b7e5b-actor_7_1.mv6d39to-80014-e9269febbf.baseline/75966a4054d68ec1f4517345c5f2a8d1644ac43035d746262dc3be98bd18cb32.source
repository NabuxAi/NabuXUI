{{--
    Pixel loader's real scenarios: a panel that is still fetching its table
    (the wide chaos strip as a row skeleton), then the three variants side by
    side the way a "connecting…" status row uses them.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1rem">
    <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A table that is still loading', 'جدولی که هنوز بار می‌شود') }}</h3>
    <p style="margin: 0; color: var(--nx-text-muted)">
        {{ $say('A wide chaos strip stands in for the row the server has not answered yet — pixels twitching at their own noise, never a blank hole.', 'یک نوار پهنِ chaos جای ردیفی را می‌گیرد که سرور هنوز جوابش را نداده — پیکسل‌ها با نویز خودشان می‌جنبند، نه یک جای خالی بی‌جان.') }}
    </p>
    <div style="display: grid; gap: .75rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg); padding: 1rem">
        <div class="pg-row" style="justify-content: space-between; font-size: var(--nx-text-sm); font-weight: 600">
            <span>{{ $say('Latest orders', 'آخرین سفارش‌ها') }}</span>
            <span style="color: var(--nx-text-subtle)">{{ $say('Loading…', 'در حال بارگیری…') }}</span>
        </div>
        <x-nx::pixel-loader variant="chaos" :rows="3" :cols="24" size="sm" :label="$say('Loading orders', 'بارگیری سفارش‌ها')" />
        <x-nx::pixel-loader variant="chaos" :rows="3" :cols="24" size="sm" :label="$say('Loading orders', 'بارگیری سفارش‌ها')" style="opacity: .65" />
    </div>
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Three moods of waiting', 'سه حال‌وهوای منتظرماندن') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('wave sweeps diagonally, center ripples out of the middle, chaos lets every cell do its own thing — one line each.', 'wave به‌طور مورب جارو می‌کند، center از وسط چنگک می‌زند و chaos به هر سلک وامی‌گذارد خودش باشد — هرکدام یک خط.') }}
        </p>
        <div class="pg-row" style="gap: 2rem; align-items: center">
            <x-nx::pixel-loader :label="$say('Thinking', 'در حال فکر')" />
            <x-nx::pixel-loader variant="center" :rows="7" :cols="7" size="sm" :label="$say('Rippling', 'در حال موج')" />
            <x-nx::pixel-loader variant="chaos" :rows="4" :cols="8" size="lg" :label="$say('Twitching', 'در حال جنبش')" />
        </div>
    </section>

    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Waiting on a wire:loading action', 'منتظر یک کنش wire:loading') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('The button hits the server; while it sleeps, the pixel loader keeps the panel honest about what is happening.', 'دکمه سرور را صدا می‌زند؛ تا وقتی خواب است، لودر پیکسلی صادقانه می‌گوید چه خبر است.') }}
        </p>
        <div class="pg-row" style="gap: 1.5rem; align-items: center">
            <x-nx::button variant="secondary" icon="chart" wire:click="save(@js($say('Numbers arrived', 'اعداد آمدند')))">
                {{ $say('Fetch the numbers', 'گرفتن اعداد') }}
            </x-nx::button>
            <span wire:loading.delay wire:target="save" style="display: inline-flex; align-items: center; gap: .75rem">
                <x-nx::pixel-loader :rows="3" :cols="3" size="sm" :label="$say('Fetching', 'در حال گرفتن')" />
                <span style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $say('Crunching…', 'در حال حساب…') }}</span>
            </span>
        </div>
        <p style="margin: 0; color: var(--nx-text-subtle); font-size: var(--nx-text-sm)">
            {{ $say('role="status" is built in, with the label visually hidden for screen readers.', 'role="status" از قبل هست و برچسب برای صفحه‌خوان‌ها پنهان است.') }}
        </p>
    </section>
</div>
