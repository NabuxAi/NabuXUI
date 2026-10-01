{{--
    / — the playground's front door: a small bilingual NabuXUI landing. The
    language follows the session locale (the SetLocaleFromSession middleware
    every demo page uses); `?lang=fa|en` flips it — the plain-link pattern
    SwitchesDemoLocale documents — so the landing itself speaks either tongue.
    Everything renders with the package's own Blade components and backdrop.
--}}
@php
    $lang = request()->query('lang');
    if (in_array($lang, ['fa', 'en'], true)) {
        session(['locale' => $lang]);
        app()->setLocale($lang);
    }

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $fa ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <title>{{ $say('NabuXUI · Playground', 'NabuXUI · پلی‌گراوند') }}</title>
    @nabuxuiHead
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500..800&family=Inter:wght@400..700&family=JetBrains+Mono:wght@400;500&family=Vazirmatn:wght@300..800&display=swap" rel="stylesheet">
    @nabuxuiStyles
    @nabuxuiScripts
    <style>
        body { margin: 0; }
        .welcome { position: relative; min-height: 100dvh; display: flex; flex-direction: column; overflow: clip; }
        .welcome-top { position: relative; z-index: 1; display: flex; align-items: center; justify-content: space-between; gap: .75rem; padding: 1rem clamp(1rem, 4vw, 2.5rem); }
        .welcome-brand { margin: 0; font: 700 var(--nx-text-lg) / 1.2 var(--nx-font-display); letter-spacing: var(--nx-tracking-tight); }
        .welcome-main { position: relative; z-index: 1; flex: 1; display: grid; align-content: center; gap: 2.5rem; padding: clamp(2rem, 6vh, 4rem) 1.5rem 4rem; inline-size: min(56rem, 100% - 3rem); margin-inline: auto; text-align: center; }
        .welcome-head { display: grid; gap: .75rem; justify-items: center; }
        .welcome-eyebrow { margin: 0; color: var(--nx-text-muted); font: 600 var(--nx-text-sm) / 1.4 var(--nx-font-sans); letter-spacing: var(--nx-tracking-wide); }
        .welcome-title { margin: 0; font: 800 var(--nx-text-5xl) / 1.1 var(--nx-font-display); letter-spacing: var(--nx-tracking-tight); }
        .welcome-sub { margin: 0; max-inline-size: 42rem; color: var(--nx-text-muted); font: var(--nx-text-lg) / 1.7 var(--nx-font-sans); }
        .welcome-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 12.5rem), 1fr)); gap: 1rem; text-align: start; }
        .welcome-foot { position: relative; z-index: 1; margin: 0; padding: 1rem; text-align: center; color: var(--nx-text-subtle); font: var(--nx-text-sm) / 1.5 var(--nx-font-sans); }
    </style>
</head>
<body>
    <div class="welcome">
        <x-nx::backdrop variant="aurora" />

        <header class="welcome-top">
            <p class="welcome-brand"><strong>Nabu<span style="color: var(--nx-gold-text)">X</span>UI</strong></p>
            <div style="display: flex; align-items: center; gap: .5rem">
                <x-nx::button size="sm" variant="ghost" icon="globe" href="/?lang={{ $fa ? 'en' : 'fa' }}">{{ $fa ? 'English' : 'فارسی' }}</x-nx::button>
                <x-nx::theme-toggle />
            </div>
        </header>

        <main class="welcome-main">
            <div class="welcome-head">
                <p class="welcome-eyebrow">{{ $say('Design system playground', 'پلی‌گراوند سیستم طراحی') }}</p>
                <h1 class="welcome-title">{{ $say('Every surface, one motion language', 'یک زبان حرکت برای همهٔ سطح‌ها') }}</h1>
                <p class="welcome-sub">{{ $say(
                    'Livewire components, standalone demos and a full admin panel — bilingual, RTL-native, light and dark. Pick where to land.',
                    'کامپوننت‌های Livewire، دموهای مستقل و یک پنل مدیریت کامل — دوزبانه، راست‌به‌چپ، روشن و تیره. انتخاب کنید کجا فرود بیایید.',
                ) }}</p>
            </div>

            <div class="welcome-grid">
                <x-nx::card interactive href="{{ url('/livewire') }}" icon="sparkles"
                    :title="$say('Livewire showcase', 'نمایشگاه Livewire')"
                    :description="$say('Blade components with the package animations, wired to Livewire and Alpine.', 'کامپوننت‌های Blade با انیمیشن‌های پکیج، متصل به Livewire و Alpine.')" />
                <x-nx::card interactive href="{{ route('components.catalog') }}" icon="grid"
                    :title="$say('Component demos', 'دموهای کامپوننت‌ها')"
                    :description="$say('Every component on its own page, inside real scenarios.', 'هر کامپوننت در صفحهٔ خودش، در سناریوهای واقعی.')" />
                <x-nx::card interactive href="{{ route('admin.dashboard') }}" icon="layers"
                    :title="$say('Admin panel', 'پنل مدیریت')"
                    :description="$say('The store dashboard — the Livewire twin of the React panel.', 'داشبورد فروشگاه — دوقلوی Livewire پنل React.')" />
                <x-nx::card interactive href="{{ route('login') }}" icon="lock"
                    :title="$say('Sign in', 'ورود')"
                    :description="$say('The auth gate: sign in, register or reset a password.', 'دروازهٔ ورود: ورود، ثبت‌نام یا بازیابی رمز.')" />
            </div>
        </main>

        <footer class="welcome-foot">NabuXUI · {{ $say('the Nabu design system', 'سیستم طراحی نابو') }}</footer>
    </div>
</body>
</html>
