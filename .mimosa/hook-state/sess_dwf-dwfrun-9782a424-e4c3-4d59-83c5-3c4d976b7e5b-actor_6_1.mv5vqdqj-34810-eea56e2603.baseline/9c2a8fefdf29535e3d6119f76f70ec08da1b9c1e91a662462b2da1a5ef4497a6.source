{{--
    partials.frameworks — the flavor switcher shared by the Livewire pages:
    one pill row linking every framework playground under its own address
    (docs/FRAMEWORKS.md, «نشانی‌ها در پلی‌گراوند»). Rendered with the
    system's own <x-nx::button>, so it picks up the theme, the locale
    direction and the motion language for free. Each label is bilingual by
    construction: the framework keeps its Latin name (the pages' own
    typewriter convention keeps brand names Latin in fa too) and the small
    word beside it says what lives at that address, in the page's locale.
    No wire:navigate here on purpose — every target outside / is its own
    app; leaving Livewire must be a full page load.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $path = trim(request()->path(), '/');
    $activeFlavor = match (true) {
        str_starts_with($path, 'react') => 'react',
        str_starts_with($path, 'vue') => 'vue',
        str_starts_with($path, 'svelte') => 'svelte',
        str_starts_with($path, 'inertia') => 'inertia',
        str_starts_with($path, 'filament') => 'filament',
        default => 'livewire',
    };

    // [id, href, icon, name, note-en, note-fa]
    $flavors = [
        ['livewire', '/', 'zap', 'Livewire', 'Home', 'خانه'],
        ['react', '/react', 'sparkles', 'React', 'Showcase', 'شوکیس'],
        ['vue', '/vue', 'layers', 'Vue', 'Panel', 'پنل'],
        ['svelte', '/svelte', 'wand', 'Svelte', 'Panel', 'پنل'],
        ['inertia', '/inertia', 'globe', 'Inertia', 'Demo', 'دمو'],
        ['filament', '/filament', 'grid', 'Filament', 'Panel', 'پنل'],
    ];
@endphp
@once
    <style>
        .nx-flavors { display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: .375rem; }
        .nx-flavors .nx-flavor-name { font-weight: 700; }
        .nx-flavors .nx-flavor-note { margin-inline-start: .3rem; color: var(--nx-text-muted); font-weight: 500; font-size: .8em; }
    </style>
@endonce
<nav class="nx-flavors" aria-label="{{ $say('Frameworks', 'فریم‌ورک‌ها') }}">
    @foreach ($flavors as [$id, $href, $icon, $name, $noteEn, $noteFa])
        <x-nx::button
            size="sm"
            variant="{{ $activeFlavor === $id ? 'primary' : 'ghost' }}"
            icon="{{ $icon }}"
            href="{{ $href }}"
            :aria-current="$activeFlavor === $id ? 'page' : null"
        >
            <span class="nx-flavor-name" dir="ltr">{{ $name }}</span>
            <span class="nx-flavor-note">{{ $say($noteEn, $noteFa) }}</span>
        </x-nx::button>
    @endforeach
</nav>
