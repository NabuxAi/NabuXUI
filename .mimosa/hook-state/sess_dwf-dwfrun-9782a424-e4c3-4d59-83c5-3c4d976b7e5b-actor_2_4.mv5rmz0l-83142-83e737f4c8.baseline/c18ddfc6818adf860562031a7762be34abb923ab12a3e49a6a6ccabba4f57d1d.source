<?php

namespace App\Http\Controllers\Inertia;

use App\Http\Controllers\Controller;
use Inertia\Response;

/**
 * The Inertia flavor's block gallery. The blocks themselves live client-side
 * in the React page; the server only says how many are on show, which is what
 * the "All" chip's count badge and the page's footnote display.
 */
class GalleryController extends Controller
{
    public function __invoke(): Response
    {
        return inertia('Gallery', [
            'blocks' => 5, // 2 product cards, a profile card, a sort pill, an empty state
        ]);
    }
}
