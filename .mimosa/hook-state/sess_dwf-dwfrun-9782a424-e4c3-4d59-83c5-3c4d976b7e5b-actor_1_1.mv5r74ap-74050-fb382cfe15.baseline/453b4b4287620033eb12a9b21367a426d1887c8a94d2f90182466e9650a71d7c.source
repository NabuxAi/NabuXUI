<?php

namespace NabuXUI\Http;

use Illuminate\Http\Response;

/**
 * Serves the prebuilt bundle. Assets are addressed with ?v=<hash> by
 * NabuXUI::scripts()/styles(), so they can be cached for a year.
 */
class AssetController
{
    public function __invoke(string $file): Response
    {
        $path = realpath(__DIR__.'/../../dist/'.$file);
        $dist = realpath(__DIR__.'/../../dist');

        // The route pattern already restricts the name; this makes traversal impossible regardless.
        abort_unless($path && $dist && str_starts_with($path, $dist.DIRECTORY_SEPARATOR) && is_file($path), 404);

        $type = match (true) {
            str_ends_with($file, '.css') => 'text/css; charset=utf-8',
            str_ends_with($file, '.map') => 'application/json',
            default => 'application/javascript; charset=utf-8',
        };

        return response(file_get_contents($path), 200, [
            'Content-Type' => $type,
            'Cache-Control' => request()->has('v') ? 'public, max-age=31536000, immutable' : 'public, max-age=3600',
        ]);
    }
}
