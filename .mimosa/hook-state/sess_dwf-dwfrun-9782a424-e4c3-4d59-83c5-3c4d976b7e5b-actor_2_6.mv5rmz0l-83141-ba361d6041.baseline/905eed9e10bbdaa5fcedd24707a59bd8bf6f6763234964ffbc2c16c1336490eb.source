<?php

namespace App\Http\Controllers\Inertia;

use App\Http\Controllers\Controller;
use Inertia\Response;

/**
 * The Inertia flavor's dashboard: plain Laravel on the way in, a React page
 * on the way out. The numbers below are deterministic demo data — no models,
 * no factories — but the flow is the real one: controller → props → components.
 */
class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        return inertia('Dashboard', [
            // The figures the StatStrip counts up and the chart plots. The
            // client maps each id to its own words and glyph, so the payload
            // stays about numbers, not translations.
            'stats' => [
                ['id' => 'visits', 'value' => 128409, 'delta' => 12.4],
                ['id' => 'uptime', 'value' => 99.98, 'delta' => 0.02],
                ['id' => 'deploys', 'value' => 47, 'delta' => 6.0],
                ['id' => 'incidents', 'value' => 1, 'delta' => -2.0],
            ],

            // The last 14 days of traffic; the labels are formatted client-side
            // so they follow the page language's calendar.
            'traffic' => [
                'days' => $this->days(14),
                'visits' => [8400, 9100, 8800, 10250, 11900, 7600, 6900, 9800, 10400, 11200, 12300, 13150, 12050, 13609],
            ],

            // Average server response, milliseconds — the Gauge's reading.
            'latency' => 148,

            // The activity feed: who did what, with the verb left to the client.
            'events' => [
                ['id' => 'e1', 'actor' => 'Amara Okafor', 'kind' => 'deploy', 'target' => 'edge-fra', 'minutesAgo' => 18],
                ['id' => 'e2', 'actor' => 'Jonas Becker', 'kind' => 'incident', 'target' => 'api-gateway', 'minutesAgo' => 64],
                ['id' => 'e3', 'actor' => 'Mei Tanaka', 'kind' => 'release', 'target' => 'v3.9.1', 'minutesAgo' => 190],
                ['id' => 'e4', 'actor' => 'Lucía Fernández', 'kind' => 'backup', 'target' => 'db-eu', 'minutesAgo' => 420],
            ],
        ]);
    }

    /** The last `$count` ISO days, oldest first, ending today. */
    private function days(int $count): array
    {
        $today = now()->startOfDay();

        return collect(range(0, $count - 1))
            ->map(fn (int $offset) => $today->copy()->subDays($count - 1 - $offset)->toDateString())
            ->all();
    }
}
