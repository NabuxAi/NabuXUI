<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

/**
 * The Inertia flavor's middleware (the adapted vendor stub). The root view is
 * the Blade file that carries the NabuXUI directives and loads the built
 * apps/inertia bundle from the public `inertia/` path.
 */
class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'inertia-root';

    /**
     * Determines the current asset version.
     *
     * The app's hashed entry names live in the Vite manifest that the integrator
     * copies to public/inertia/. Hashing that file gives Inertia a version that
     * flips exactly when the front-end build changes, so history is forgotten
     * on deploy and kept otherwise. When the manifest has not been copied yet,
     * the parent's behavior stands.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        $manifest = public_path('inertia/.vite/manifest.json');

        if (is_file($manifest)) {
            return hash_file('xxh128', $manifest) ?: parent::version($request);
        }

        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            // The first paint follows the app locale (fa/en in this playground);
            // the shell starts from it unless the visitor already picked a
            // language client-side.
            'locale' => in_array(app()->getLocale(), ['fa', 'en'], true) ? app()->getLocale() : 'en',
            'appName' => config('app.name', 'NabuXUI'),
        ];
    }
}
