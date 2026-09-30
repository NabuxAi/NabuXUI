{{--
    The admin panel's skeleton: html head (theme script, fonts, NabuXUI
    styles+scripts), the page transition progress bar and the toaster. Each
    page's own view renders the <x-admin.page> shell inside {{ $slot }} so
    wire:model/wire:click in the topbar stay inside the Livewire root.
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ in_array(app()->getLocale(), ['fa', 'ar']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <title>{{ __('admin.panel') }} · NabuXUI</title>
    @nabuxuiHead
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500..800&family=Inter:wght@400..700&family=JetBrains+Mono:wght@400;500&family=Vazirmatn:wght@300..800&display=swap" rel="stylesheet">
    @nabuxuiStyles
    @nabuxuiScripts
    <style>
        body { margin: 0; }

        /* The page floats the shell as a framed card (fullscreen-style is the
           shell's default; here the playground wants the demo card look). */
        .ap-page { min-height: 100dvh; padding: clamp(.75rem, 2vw, 1.5rem); display: grid; align-items: stretch; }

        /* The panel's content grid. */
        .ap-grid { display: grid; gap: var(--nx-space-6); align-content: start; }
        .ap-row { display: flex; flex-wrap: wrap; gap: var(--nx-space-3); align-items: center; }

        /* Welcome hero riding the aurora backdrop block. */
        .ap-hero { border-radius: var(--nx-radius-2xl); border: 1px solid var(--nx-border); background: var(--nx-surface); overflow: clip; }
        .ap-hero-body { position: relative; padding: clamp(1.25rem, 3vw, 2.25rem); display: grid; gap: var(--nx-space-3); justify-items: start; }
        .ap-hero-hello { margin: 0; font: 700 var(--nx-text-xl) / 1.4 var(--nx-font-display); letter-spacing: var(--nx-tracking-tight); color: var(--nx-text); }
        .ap-hero-text { margin: 0; max-inline-size: 44rem; color: var(--nx-text-muted); }

        /* Stat cards, charts and boxes. */
        .ap-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 14rem), 1fr)); gap: var(--nx-space-4); }
        .ap-duo { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 26rem), 1fr)); gap: var(--nx-space-6); align-items: start; }
        .ap-box { display: grid; gap: var(--nx-space-4); align-content: start; padding: var(--nx-space-5); border: 1px solid var(--nx-border); border-radius: var(--nx-radius-2xl); background: var(--nx-surface); }
        .ap-box-title { margin: 0; font: 600 var(--nx-text-lg) / 1.4 var(--nx-font-display); letter-spacing: var(--nx-tracking-tight); color: var(--nx-text); }
        .ap-box-more { margin: 0; }

        /* Stub pages centre their empty-state. */
        .ap-stub { padding-block: var(--nx-space-10); justify-items: center; }

        /* The auth gate. */
        .ap-auth { min-height: 100dvh; display: grid; gap: var(--nx-space-5); align-content: center; justify-items: center; padding: clamp(1rem, 3vw, 2rem); }
        .ap-auth-top { inline-size: min(64rem, 100%); display: flex; flex-wrap: wrap; gap: var(--nx-space-3); align-items: center; justify-content: space-between; }
        .ap-auth-alert { inline-size: min(64rem, 100%); }
    </style>
</head>
<body>
    <x-nx::route-progress />
    {{ $slot }}
    <x-nx::toaster />
</body>
</html>
