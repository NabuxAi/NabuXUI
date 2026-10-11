<?php
/** The Inertia flavor's root template — what @inertia renders into on the first
 *  visit of every /inertia page. The NabuXUI directives mirror
 *  resources/views/layouts/app.blade.php; the built apps/inertia bundle is
 *  resolved through the Vite manifest that the integrator copies to
 *  public/inertia/ (dist → public/inertia, manifest included), so the hashed
 *  entry and CSS names never have to be guessed here. */
use App\Support\Locales;

$inertiaEntry = null;
$inertiaCss = [];
$inertiaManifest = public_path('inertia/.vite/manifest.json');
if (is_file($inertiaManifest)) {
    $manifest = json_decode((string) file_get_contents($inertiaManifest), true) ?: [];
    // The HTML entry builds under the "index.html" key; fall back to any entry
    // flagged isEntry, so a future multi-entry build keeps working.
    $entry = $manifest['index.html'] ?? null;
    if (!$entry) {
        foreach ($manifest as $candidate) {
            if (is_array($candidate) && ($candidate['isEntry'] ?? false)) {
                $entry = $candidate;
                break;
            }
        }
    }
    if ($entry && isset($entry['file'])) {
        $inertiaEntry = $entry['file'];
        $inertiaCss = $entry['css'] ?? [];
    }
}
$locale = app()->getLocale();
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $locale) }}" dir="{{ Locales::dir($locale) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <title>NabuXUI · Inertia</title>
    @inertiaHead
    @nabuxuiHead
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500..800&family=Inter:wght@400..700&family=JetBrains+Mono:wght@400;500&family=Vazirmatn:wght@300..800&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500..800&family=Inter:wght@400..700&family=JetBrains+Mono:wght@400;500&family=Vazirmatn:wght@300..800&display=swap" rel="stylesheet"></noscript>
    @nabuxuiStyles
    {{-- The Inertia app's own stylesheet (page scaffold), then the bundle, both
         from the public inertia/ path the integrator fills from apps/inertia/dist. --}}
    @foreach ($inertiaCss as $css)
        <link rel="stylesheet" href="{{ asset('inertia/'.$css) }}">
    @endforeach
    @nabuxuiScripts
    @if ($inertiaEntry)
        <script type="module" src="{{ asset('inertia/'.$inertiaEntry) }}"></script>
    @else
        {{-- Nothing copied yet: say so instead of silently rendering an empty shell. --}}
        <!-- NabuXUI Inertia: apps/inertia/dist has not been copied to public/inertia yet (expected .vite/manifest.json). -->
    @endif
</head>
<body>
    @inertia
</body>
</html>
