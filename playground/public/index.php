<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// The SPA flavors live as directories with their own index.* under public/
// (public/react, public/vue, public/svelte, public/inertia). The PHP built-in
// web server — what `php artisan serve` runs, locally and in the image —
// resolves a sub-path like /react/settings or /inertia/gallery to that
// directory's index file and leaves SCRIPT_NAME pointing at it (…/index.html,
// …/index.php), which makes the request path Symfony computes come out
// truncated ("settings" instead of "react/settings") and every deep link 404.
// Restore the front controller's own script identity before the app boots;
// under php-fpm, Apache or nginx this SAPI never applies and the block is a
// no-op.
if (PHP_SAPI === 'cli-server'
    && ($_SERVER['SCRIPT_NAME'] ?? '/index.php') !== '/index.php'
) {
    $_SERVER['SCRIPT_NAME'] = '/index.php';
    $_SERVER['PHP_SELF'] = '/index.php';
    $_SERVER['SCRIPT_FILENAME'] = __DIR__.'/index.php';
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
