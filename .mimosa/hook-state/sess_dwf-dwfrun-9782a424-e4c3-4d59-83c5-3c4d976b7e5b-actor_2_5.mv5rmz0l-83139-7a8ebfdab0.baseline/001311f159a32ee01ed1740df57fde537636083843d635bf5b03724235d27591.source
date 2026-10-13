<?php

namespace App\Http\Controllers\Inertia;

use App\Http\Controllers\Controller;
use Inertia\Response;

/**
 * The Inertia flavor's routing lab. `pulse` is the partial-reload prop: it is
 * wrapped in Inertia::optional (the v3 name of v2's lazy), so it is skipped on
 * a full visit and only evaluated when a reload asks for it — press "refresh
 * only pulse" on the page and watch nothing else come back down the wire.
 */
class VisitsController extends Controller
{
    public function __invoke(): Response
    {
        return inertia('Visits', [
            'pulse' => \Inertia\Inertia::optional(fn () => now()->toIso8601String()),
        ]);
    }
}
