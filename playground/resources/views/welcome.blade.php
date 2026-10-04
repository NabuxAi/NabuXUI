{{--
    / — the playground's front door: a bilingual NabuXUI landing dressed with
    the system's own "expensive" tricks — a gradient display title, a
    typewriter that cycles the frameworks, magnetic + shine CTAs, odometer
    stats, a bento of entry tiles (one wearing a border beam, one holding a
    pull-cord that flips the theme) and a marquee wall of the demo groups.
    The language follows the session locale (the SetLocaleFromSession
    middleware every demo page uses); `?lang=fa|en` flips it. Everything
    renders with the package's own Blade components and backdrop.
--}}
@php
    use App\Support\DemoCatalog;

    $lang = request()->query('lang');
    if (in_array($lang, ['fa', 'en'], true)) {
        session(['locale' => $lang]);
        app()->setLocale($lang);
    }

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $groups = DemoCatalog::groups();
    $demoCount = count(DemoCatalog::flat());
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
    {{-- The welcome page is plain Blade (no Livewire), so it needs its own
         Alpine: the vendored copy below (public/vendor/alpine.js) is the CDN
         build with its auto-start patched to wait for the load event — module
         scripts always execute before it, so every x-data provider the package
         registers is in place before Alpine walks the tree. --}}
    <script src="{{ asset('vendor/alpine.js') }}"></script>
    @nabuxuiScripts
    <style>
        body { margin: 0; }
        .welcome { position: relative; min-height: 100dvh; display: flex; flex-direction: column; overflow: clip; }
        .welcome-top { position: relative; z-index: 1; display: flex; align-items: center; justify-content: space-between; gap: .75rem; padding: 1rem clamp(1rem, 4vw, 2.5rem); }
        .welcome-brand { margin: 0; font: 700 var(--nx-text-lg) / 1.2 var(--nx-font-display); letter-spacing: var(--nx-tracking-tight); }
        .welcome-main { position: relative; z-index: 1; flex: 1; display: grid; align-content: center; gap: 2.25rem; padding: clamp(2rem, 6vh, 4rem) 1.5rem 3.5rem; inline-size: min(58rem, 100% - 3rem); margin-inline: auto; text-align: center; }
        .welcome-head { display: grid; gap: .75rem; justify-items: center; }
        .welcome-eyebrow { margin: 0; color: var(--nx-text-muted); font: 600 var(--nx-text-sm) / 1.4 var(--nx-font-sans); letter-spacing: var(--nx-tracking-wide); }
        .welcome-title { margin: 0; font: 800 var(--nx-text-5xl) / 1.1 var(--nx-font-display); letter-spacing: var(--nx-tracking-tight); }
        .welcome-sub { margin: 0; max-inline-size: 42rem; color: var(--nx-text-muted); font: var(--nx-text-lg) / 1.7 var(--nx-font-sans); }
        .welcome-onecore { margin: 0; color: var(--nx-text-muted); font: 600 var(--nx-text-md) / 1.6 var(--nx-font-sans); }
        .welcome-onecore .nx-typewriter { color: var(--nx-text); font-weight: 700; }
        .welcome-ctas { display: flex; flex-wrap: wrap; justify-content: center; gap: .75rem; }
        .welcome-stats { display: flex; flex-wrap: wrap; justify-content: center; gap: clamp(1.25rem, 5vw, 3rem); }
        .welcome-stat { display: grid; gap: .15rem; justify-items: center; }
        .welcome-stat > .nx-odometer { font: 700 clamp(1.6rem, 4vw, 2.2rem) / 1.1 var(--nx-font-display); letter-spacing: var(--nx-tracking-tight); color: var(--nx-text); }
        .welcome-stat > small { color: var(--nx-text-subtle); font: 600 var(--nx-text-xs) / 1.4 var(--nx-font-sans); }
        .welcome-bento { text-align: start; }
        .pg-beam-tile { grid-column: span 2; display: grid; }
        @media (max-width: 36rem) { .pg-beam-tile { grid-column: span 1; } }
        .welcome-chip { display: inline-flex; align-items: center; gap: .45rem; padding: .45rem .8rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-full); background: color-mix(in oklab, var(--nx-surface) 72%, transparent); color: var(--nx-text-muted); font: 600 var(--nx-text-sm) / 1.4 var(--nx-font-sans); white-space: nowrap; }
        .welcome-chip > b { color: var(--nx-text); font-weight: 700; }
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
                <h1 class="welcome-title">
                    {{ $say('Every surface, ', 'هر سطح،') }}
                    <x-nx::gradient-text>{{ $say('one motion language', 'یک زبان حرکت') }}</x-nx::gradient-text>
                </h1>
                <p class="welcome-sub">{{ $say(
                    'Livewire components, standalone demos and a full admin panel — bilingual, RTL-native, light and dark. Pick where to land.',
                    'کامپوننت‌های Livewire، دموهای مستقل و یک پنل مدیریت کامل — دوزبانه، راست‌به‌چپ، روشن و تیره. انتخاب کنید کجا فرود بیایید.',
                ) }}</p>
                <p class="welcome-onecore">
                    {{ $say('One core, running on', 'یک هسته، روی') }}
                    <x-nx::typewriter by="word" :hold="2200" :phrases="$fa
                        ? ['Livewire و Alpine', 'React و Inertia', 'Vue', 'Svelte']
                        : ['Livewire + Alpine', 'React + Inertia', 'Vue', 'Svelte']" />
                </p>
            </div>

            <div class="welcome-ctas">
                <x-nx::button variant="primary" size="lg" magnetic effect="shine" icon="sparkles" href="{{ route('components.catalog') }}">
                    {{ $say('Explore the components', 'گشت‌وگذار در کامپوننت‌ها') }}
                </x-nx::button>
                <x-nx::button variant="secondary" size="lg" magnetic href="{{ route('admin.dashboard') }}">
                    {{ $say('Open the admin panel', 'پنل مدیریت') }}
                </x-nx::button>
            </div>

            <div class="welcome-stats">
                <div class="welcome-stat">
                    <x-nx::odometer :value="$demoCount" />
                    <small>{{ $say('live demos', 'دموی زنده') }}</small>
                </div>
                <div class="welcome-stat">
                    <x-nx::odometer :value="count($groups)" />
                    <small>{{ $say('demo groups', 'گروه دمو') }}</small>
                </div>
                <div class="welcome-stat">
                    <x-nx::odometer :value="5" />
                    <small>{{ $say('frameworks, one core', 'فریم‌ورک، یک هسته') }}</small>
                </div>
            </div>

            <div class="welcome-bento">
                <x-nx::bento :columns="3" row-height="11rem">
                    <x-nx::border-beam class="pg-beam-tile" tone="gold" radius="var(--nx-radius-2xl)" :duration="9" :size="80">
                        <x-nx::bento-item href="{{ route('components.catalog') }}">
                            <x-slot:media>
                                <div style="display: flex; gap: .5rem; align-items: center; padding-block: .25rem">
                                    <x-nx::badge tone="gold">{{ $say('Every component, its own page', 'هر کامپوننت، صفحهٔ خودش') }}</x-nx::badge>
                                </div>
                            </x-slot:media>
                            <h3 class="nx-bento-title">{{ $say('Component demos', 'دموهای کامپوننت‌ها') }}</h3>
                            <p class="nx-bento-description">{{ $say('Real scenarios, not screenshots — searchable, bilingual, light and dark.', 'سناریوهای واقعی، نه اسکرین‌شات — جست‌وجوپذیر، دوزبانه، روشن و تیره.') }}</p>
                        </x-nx::bento-item>
                    </x-nx::border-beam>

                    <x-nx::bento-item x-on:nx-cord-pull="$nxTheme.toggle()" style="justify-items: start">
                        <x-slot:media>
                            {{-- The media slot centres its grid items; a bare block has no intrinsic width, so give the cord one. --}}
                            <x-nx::pull-cord tone="gold" height="10rem" style="inline-size: 9rem" :label="$say('Pull to flip the theme', 'بکشید تا تم عوض شود')" />
                        </x-slot:media>
                        <h3 class="nx-bento-title">{{ $say('Pull the cord', 'ریسمان را بکش') }}</h3>
                        <p class="nx-bento-description">{{ $say('A verlet rope that flips light and dark. Yes, really.', 'طنابی با فیزیک واقعی که روشن و تیره را ورق می‌زند. جدی می‌گوییم.') }}</p>
                    </x-nx::bento-item>

                    <x-nx::bento-item href="{{ url('/livewire') }}">
                        <h3 class="nx-bento-title">{{ $say('Livewire showcase', 'نمایشگاه Livewire') }}</h3>
                        <p class="nx-bento-description">{{ $say('Blade components wired to Livewire and Alpine.', 'کامپوننت‌های Blade متصل به Livewire و Alpine.') }}</p>
                    </x-nx::bento-item>

                    <x-nx::bento-item href="{{ route('admin.dashboard') }}">
                        <h3 class="nx-bento-title">{{ $say('Admin panel', 'پنل مدیریت') }}</h3>
                        <p class="nx-bento-description">{{ $say('The store dashboard, in three flavors.', 'داشبورد فروشگاه، در سه طعم.') }}</p>
                    </x-nx::bento-item>

                    <x-nx::bento-item href="{{ route('login') }}">
                        <h3 class="nx-bento-title">{{ $say('Sign in', 'ورود') }}</h3>
                        <p class="nx-bento-description">{{ $say('The auth gate: sign in, register, reset.', 'دروازهٔ ورود: ورود، ثبت‌نام، بازیابی.') }}</p>
                    </x-nx::bento-item>
                </x-nx::bento>
            </div>

            <x-nx::marquee :duration="48">
                @foreach ($groups as $id => $group)
                    <li class="welcome-chip">
                        {{ DemoCatalog::pick($group['label']) }}
                        <b>{{ count($group['items']) }}</b>
                    </li>
                @endforeach
            </x-nx::marquee>
        </main>

        <footer class="welcome-foot">NabuXUI · {{ $say('the Nabu design system', 'سیستم طراحی نابو') }}</footer>
    </div>
</body>
</html>
