<?php

/**
 * The directory index for the copied Inertia build (public/inertia).
 *
 * /inertia is answered by the Inertia controllers, never by a static shell —
 * the real entry is the inertia-root Blade view, which resolves the hashed
 * client bundle from public/inertia/.vite/manifest.json. This file exists
 * only so a request for the bare /inertia directory still reaches Laravel's
 * front controller under `php artisan serve` (and under any web server whose
 * directory-index lands here): the built-in server serves a directory's
 * index.* instead of falling through to the router, and the app must boot
 * for the route to hit the controller. bin/sync-flavors.sh copies it to
 * playground/public/inertia/index.php; playground/Dockerfile does the same
 * inside the image.
 */

require __DIR__.'/../index.php';
