<?php

namespace App\Http\Middleware;

use App\Support\Locales;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Applies the visitor's chosen locale (kept in the session by the demo pages'
 * language menu) to the app, so the layout's lang/dir follow it too. Only
 * codes in the central Locales registry are accepted.
 */
class SetLocaleFromSession
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('locale');

        if (in_array($locale, Locales::codes(), true)) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
