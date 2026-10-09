<?php

namespace NabuXUI\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use NabuXUI\NabuXUI;

/**
 * Brings NabuXUI into a Filament panel: the nx theme script, stylesheet and
 * Alpine behaviours ride the panel's render hooks, so every nx field and any
 * hand-placed <x-nx::…> component works inside the panel — dark mode, RTL and
 * motion follow the same core the playground uses.
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
    }

    public function boot(Panel $panel): void
    {
        FilamentView::registerRenderHook(PanelsRenderHook::HEAD_END, function (): string {
            // The theme script must sit in <head> so the panel never paints in
            // the wrong theme; the stylesheet follows it.
            return (string) NabuXUI::head() . (string) NabuXUI::styles();
        });

        FilamentView::registerRenderHook(PanelsRenderHook::BODY_END, fn (): string => (string) NabuXUI::scripts());
    }
}
