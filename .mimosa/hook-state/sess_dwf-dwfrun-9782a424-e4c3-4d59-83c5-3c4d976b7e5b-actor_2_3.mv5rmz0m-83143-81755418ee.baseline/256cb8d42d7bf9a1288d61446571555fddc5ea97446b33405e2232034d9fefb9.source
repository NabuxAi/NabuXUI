<?php

namespace NabuXUI\Filament;

use Illuminate\Support\ServiceProvider;

/**
 * Boots the Filament side of NabuXUI: the field views under the
 * `nabuxui-filament` namespace (publishable for overrides) — the assets and
 * panel wiring live in the plugin, not here, so non-panel usage stays light.
 */
class NabuXuiFilamentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'nabuxui-filament');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/nabuxui-filament'),
        ], 'nabuxui-filament-views');
    }
}
