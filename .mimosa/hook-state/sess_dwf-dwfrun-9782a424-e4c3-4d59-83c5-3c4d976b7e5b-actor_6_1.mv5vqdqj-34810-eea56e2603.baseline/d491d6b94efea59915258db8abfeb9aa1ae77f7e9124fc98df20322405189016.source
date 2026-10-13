<?php

namespace NabuXUI\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use NabuXUI\NabuXUI;

/**
 * Brings NabuXUI into a Filament panel — not just the fields, the LOOK: the
 * panel wears the nx palette (lapis violet), the Inter/Vazirmatn stack and
 * the admin-shell surfaces (cream canvas, white sidebar and topbar, soft
 * rounded sections), and the nx assets ride the panel's render hooks so any
 * <x-nx::…> component works inside it, dark mode included.
 */
class NabuXuiPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'nabuxui';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->colors([
                'primary' => Color::hex('#5647e6'),
            ])
            ->font('Vazirmatn', url: 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Vazirmatn:wght@400;500;600;700;800&display=swap');
    }

    public function boot(Panel $panel): void
    {
        FilamentView::registerRenderHook(PanelsRenderHook::HEAD_END, function (): string {
            // The theme script must sit in <head> so the panel never paints in
            // the wrong theme; the stylesheet and the shell skin follow it.
            return (string) NabuXUI::head()
                . (string) NabuXUI::styles()
                . '<style data-nabuxui-theme>' . $this->themeCss() . '</style>'
                // Two-way theme bridge: the core switch (data-theme / nabu.theme)
                // and Filament's own dark toggle (localStorage.theme + the
                // Alpine store) stay in lockstep, whichever side moves.
                . <<<'JS'
                <script>
                (function () {
                    var root = document.documentElement;
                    var sync = function () {
                        var dark = root.dataset.theme === 'dark' || localStorage.getItem('nabu.theme') === 'dark';
                        localStorage.setItem('theme', dark ? 'dark' : 'light');
                        root.classList.toggle('dark', dark);
                        if (window.Alpine && window.Alpine.store('theme')) window.Alpine.store('theme', dark ? 'dark' : 'light');
                    };
                    sync();
                    window.addEventListener('nx-theme-change', sync);
                    window.addEventListener('theme-changed', function (event) {
                        var theme = event.detail === 'system'
                            ? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light')
                            : event.detail;
                        root.dataset.theme = theme;
                        localStorage.setItem('nabu.theme', theme);
                        root.classList.toggle('dark', theme === 'dark');
                    });
                })();
                </script>
                JS;
        });

        FilamentView::registerRenderHook(PanelsRenderHook::BODY_END, fn (): string => (string) NabuXUI::scripts());
    }

    /**
     * The NabuXUI admin-shell look, painted over Filament's compiled styles:
     * the cream canvas, the white sidebar/topbar with hairlines, soft rounded
     * sections, and the dark mirrors keyed to both Filament's `.dark` and the
     * core theme switch's `[data-theme]`.
     */
    protected function themeCss(): string
    {
        return <<<'CSS'
        .fi-layout, .fi-body { font-family: 'Vazirmatn', 'Inter', ui-sans-serif, system-ui, sans-serif; }
        .fi-layout { background: #faf9f6; }

        .fi-sidebar { background: #ffffff; border-inline-end: 1px solid rgba(24, 24, 27, .08); }
        [dir='rtl'] .fi-sidebar { box-shadow: inset 1px 0 0 rgba(24, 24, 27, .07); }
        .fi-topbar { background: rgba(255, 255, 255, .92); backdrop-filter: blur(12px); border-block-end: 1px solid rgba(24, 24, 27, .08); }

        .fi-section { border-radius: 1rem; border: 1px solid rgba(24, 24, 27, .07); box-shadow: 0 1px 2px rgba(24, 24, 27, .04), 0 10px 28px -18px rgba(24, 24, 27, .18); }
        .fi-sidebar-item-button, .fi-topbar-item-button { border-radius: .65rem; }
        .fi-btn { border-radius: .6rem; }

        html.dark .fi-layout, html[data-theme='dark'] .fi-layout { background: #17161c; }
        html.dark .fi-sidebar, html[data-theme='dark'] .fi-sidebar { background: #1d1c23; border-inline-end-color: rgba(255, 255, 255, .08); }
        [dir='rtl'] html.dark .fi-sidebar, [dir='rtl'] html[data-theme='dark'] .fi-sidebar { box-shadow: inset 1px 0 0 rgba(255, 255, 255, .07); }
        html.dark .fi-topbar, html[data-theme='dark'] .fi-topbar { background: rgba(29, 28, 35, .92); border-block-end-color: rgba(255, 255, 255, .08); }
        html.dark .fi-section, html[data-theme='dark'] .fi-section { border-color: rgba(255, 255, 255, .08); box-shadow: 0 1px 2px rgba(0, 0, 0, .3), 0 10px 28px -18px rgba(0, 0, 0, .5); }
        CSS;
    }
}
