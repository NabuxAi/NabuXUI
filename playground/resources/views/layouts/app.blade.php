<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ in_array(app()->getLocale(), ['fa', 'ar']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <title>{{ $title ?? 'NabuXUI · Livewire' }}</title>
    @nabuxuiHead
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500..800&family=Inter:wght@400..700&family=JetBrains+Mono:wght@400;500&family=Vazirmatn:wght@300..800&display=swap" rel="stylesheet">
    @nabuxuiStyles
    @nabuxuiScripts
    <style>
        body { margin: 0; }
        .pg { inline-size: min(72rem, 100% - 2rem); margin: 2rem auto 6rem; display: grid; grid-template-columns: minmax(0, 1fr); gap: 2.5rem; }
        .pg-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 20rem), 1fr)); gap: 1.25rem; }
        .pg-box { display: grid; gap: 1rem; align-content: start; padding: 1.5rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-2xl); background: var(--nx-surface); }
        .pg-row { display: flex; flex-wrap: wrap; gap: .75rem; align-items: center; }
        h2.pg-title { margin: 0; font: 700 var(--nx-text-2xl) / 1.2 var(--nx-font-display); }
    </style>
</head>
<body>
    <x-nx::route-progress />
    {{ $slot }}
    <x-nx::toaster />
</body>
</html>
