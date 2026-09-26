<?php

namespace NabuXUI;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use NabuXUI\Http\AssetController;

/**
 * Registers the `nx` Blade namespace (<x-nx::button>), the package's
 * translations, the asset routes and the @nabuxui* directives.
 *
 * Nothing here needs a build step in the app: the compiled JavaScript and CSS
 * ship in the package's dist/ folder and are served by the asset routes.
 */
class NabuXUIServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/nabuxui.php', 'nabuxui');
        $this->app->singleton(NabuXUI::class);
    }

    public function boot(): void
    {
        Blade::anonymousComponentPath(__DIR__.'/../resources/views/components', 'nx');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'nabuxui');

        Blade::directive('nabuxuiHead', fn () => '<?php echo \\NabuXUI\\NabuXUI::head(); ?>');
        Blade::directive('nabuxuiStyles', fn () => '<?php echo \\NabuXUI\\NabuXUI::styles(); ?>');
        Blade::directive('nabuxuiScripts', fn () => '<?php echo \\NabuXUI\\NabuXUI::scripts(); ?>');

        if (config('nabuxui.serve_assets', true)) {
            Route::get(config('nabuxui.asset_path', 'nabuxui').'/{file}', AssetController::class)
                ->where('file', 'nabuxui\.(js|css)(\.map)?')
                ->name('nabuxui.asset');
        }

        $this->publishes([__DIR__.'/../config/nabuxui.php' => config_path('nabuxui.php')], 'nabuxui-config');
        $this->publishes([__DIR__.'/../resources/views/components' => resource_path('views/vendor/nx')], 'nabuxui-views');
        $this->publishes([__DIR__.'/../dist' => public_path('vendor/nabuxui')], 'nabuxui-assets');
    }
}
