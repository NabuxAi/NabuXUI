<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Applies the visitor's chosen fa/en locale (kept in the session by the demo
 * pages' language menu) to the app, so the layout's lang/dir follow it too.
 */
class SetLocaleFromSession
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('locale');

        if (in_array($locale, ['fa', 'en'], true)) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
