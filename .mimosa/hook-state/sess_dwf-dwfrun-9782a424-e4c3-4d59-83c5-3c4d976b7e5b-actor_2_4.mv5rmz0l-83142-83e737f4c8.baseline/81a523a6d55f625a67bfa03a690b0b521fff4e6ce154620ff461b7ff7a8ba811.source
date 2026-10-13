{{--
    The language menu as a docs preference: every language code is stacked in
    the trigger and the chosen one rolls in; the pick lands in Livewire and
    the caption shows which direction that locale would render in. (The demo
    page itself switches language from the menu at its top.)
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $languages = [
        ['id' => 'fa', 'name' => 'فارسی', 'short' => 'FA'],
        ['id' => 'en', 'name' => 'English', 'short' => 'EN'],
        ['id' => 'ar', 'name' => 'العربية', 'short' => 'AR'],
        ['id' => 'ja', 'name' => '日本語', 'short' => 'JA'],
    ];
    $lang = $state['docsLang'] ?? 'fa';
    $rtl = in_array($lang, ['fa', 'ar'], true);
@endphp

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Reading language', 'زبان خواندن') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('The trigger rolls the two-letter code; the panel is a plain listbox with a springy check. The hidden input posts as docsLang, so it drops into any plain form too.', 'تریگر کد دوحرفی را می‌چرخاند؛ پنل یک listbox ساده با تیک فنری است. input مخفی به‌صورت docsLang پست می‌شود، پس داخل هر فرم ساده‌ای هم می‌نشیند.') }}
            </p>
        </div>
        <div class="pg-row" style="min-block-size: 13rem; align-items: start">
            <x-nx::language-menu name="docsLang" :languages="$languages" :value="$lang" wire:model.live="state.docsLang" />
        </div>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('Saved on the server:', 'روی سرور ذخیره شد:') }} <code>{{ $lang }}</code>
        </p>
    </section>

    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('What the app does with it', 'اپ با آن چه می‌کند') }}</h3>
        <div class="pg-row" style="gap: .625rem">
            <x-nx::badge tone="{{ $rtl ? 'accent' : 'neutral' }}">{{ $rtl ? 'dir=rtl' : 'dir=ltr' }}</x-nx::badge>
            <span style="color: var(--nx-text-muted)">
                {{ $rtl
                    ? 'فارسی و عربی از راست می‌آیند؛ چیدمان با propertyهای منطقی آینه می‌شود و --nx-dir حرکت‌ها را برمی‌گرداند.'
                    : 'Left-to-right locales render as you see; every layout uses logical properties so the RTL mirror is free.' }}
            </span>
        </div>
        <div class="pg-row" style="gap: .625rem">
            <x-nx::badge tone="info">{{ $say('Vazirmatn', 'وزیرمتن') }}</x-nx::badge>
            <span style="color: var(--nx-text-muted)">
                {{ $say('Fonts swap through :lang(fa), tracking tokens zero out for joined scripts, and numbers roll in each locale’s digits.', 'فونت‌ها با :lang(fa) عوض می‌شوند، توکن‌های فاصله‌گذاری برای خطوط چسبان صفر می‌شوند و اعداد با ارقام هر زبان می‌غلتند.') }}
            </span>
        </div>
    </section>
</div>
