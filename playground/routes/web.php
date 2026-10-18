<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/livewire', \App\Livewire\Showcase::class);
Route::get('/livewire/second', \App\Livewire\Second::class);
Route::get('/blocks/{group}', \App\Livewire\Blocks::class);

// Standalone component demos: the catalog and one full scenario page per component.
Route::get('/components', \App\Livewire\ComponentCatalog::class)->name('components.catalog');
Route::get('/components/{group}/{slug}', \App\Livewire\ComponentDemo::class)
    ->where(['group' => '[a-z][a-z0-9-]*', 'slug' => '[a-z][a-z0-9-]*'])
    ->name('components.demo');

// The MotionSites scenes (packages/motionsites/scenes): standalone pages the
// ms-* demos frame in an iframe. Served raw, never through Blade.
Route::get('/motionsites/{slug}.html', function (string $slug) {
    $file = \App\Support\MotionSites::sceneFile($slug);
    abort_if($file === null, 404);

    return response()->file($file, ['Content-Type' => 'text/html; charset=utf-8']);
})->where('slug', '[a-z][a-z0-9-]*')->name('motionsites.scene');
Route::get('/motionsites/thumbs/{slug}.webp', function (string $slug) {
    $file = \App\Support\MotionSites::thumbFile($slug);
    abort_if($file === null, 404);

    return response()->file($file, ['Content-Type' => 'image/webp', 'Cache-Control' => 'public, max-age=86400']);
})->where('slug', '[a-z][a-z0-9-]*')->name('motionsites.thumb');

/*
 * The admin panel (Livewire): one page per sidebar entry inside the shared
 * <x-admin.page> shell. The dashboard is complete; the rest are stubs that
 * later passes fill. No guard on purpose — the demo's sign-in/sign-out just
 * flips the `admin_auth` session flag between /login and /admin.
 */
Route::get('/admin', \App\Livewire\Admin\Dashboard::class)->name('admin.dashboard');
Route::get('/admin/analytics', \App\Livewire\Admin\Analytics::class)->name('admin.analytics');
Route::get('/admin/users', \App\Livewire\Admin\Users::class)->name('admin.users');
Route::get('/admin/kanban', \App\Livewire\Admin\Kanban::class)->name('admin.kanban');
Route::get('/admin/calendar', \App\Livewire\Admin\Calendar::class)->name('admin.calendar');
Route::get('/admin/chat', \App\Livewire\Admin\Chat::class)->name('admin.chat');
Route::get('/admin/invoice', \App\Livewire\Admin\Invoice::class)->name('admin.invoice');
Route::get('/admin/profile', \App\Livewire\Admin\Profile::class)->name('admin.profile');
Route::get('/admin/settings', \App\Livewire\Admin\Settings::class)->name('admin.settings');

// The shop group: the catalogue (product-card grid + category filter) and its
// orders (data-table with an order-tracking dialog per row).
Route::get('/admin/products', \App\Livewire\Admin\Products::class)->name('admin.products');
Route::get('/admin/orders', \App\Livewire\Admin\Orders::class)->name('admin.orders');

// The pages group: the full-page app demos — mail (nx-email), the file
// manager, the to-do list and the roles' permission matrix — plus the panel's
// standalone minimal error pages (no shell; one component, three flavours).
// The flavours answer with their own HTTP status on purpose, so the sidebar
// links to the 200-OK index instead of straight to the 404 flavour.
Route::get('/admin/email', \App\Livewire\Admin\Email::class)->name('admin.email');
Route::get('/admin/files', \App\Livewire\Admin\Files::class)->name('admin.files');
Route::get('/admin/todo', \App\Livewire\Admin\Todo::class)->name('admin.todo');
Route::get('/admin/roles', \App\Livewire\Admin\Roles::class)->name('admin.roles');
Route::get('/admin/errors', \App\Livewire\Admin\Errors::class)->name('admin.errors');
Route::get('/admin/errors/404', \App\Livewire\Admin\ErrorPage::class)->defaults('code', '404')->name('admin.errors.404');
Route::get('/admin/errors/500', \App\Livewire\Admin\ErrorPage::class)->defaults('code', '500')->name('admin.errors.500');
Route::get('/admin/errors/maintenance', \App\Livewire\Admin\ErrorPage::class)->defaults('code', 'maintenance')->name('admin.errors.maintenance');

// Auth gate: one component, three panes (login / register / forgot-password).
Route::get('/login', \App\Livewire\Auth\Gate::class)->defaults('mode', 'login')->name('login');
Route::get('/register', \App\Livewire\Auth\Gate::class)->defaults('mode', 'register')->name('register');
Route::get('/forgot-password', \App\Livewire\Auth\Gate::class)->defaults('mode', 'forgot')->name('password.request');

/*
 * One address per framework flavor. The SPA entry points are static builds
 * under public/{react,vue,svelte} (mirrored there by bin/sync-flavors.sh);
 * their assets load straight from the public tree, so all these routes do
 * is hand out the app's index.html — including on a deep refresh, because
 * every client router here is hash-based and only the shell path matters.
 * /react/admin is registered before the /react catch-all so it wins the
 * match, and the {any} pattern deliberately fails on any "assets/…"
 * segment so a missing asset stays a real 404 instead of an HTML shell
 * with the wrong MIME (the nested /react/admin catch-all would otherwise
 * fall through to the parent /react route). The Inertia flavor is a real server-rendered citizen instead: its
 * controllers answer /inertia* and the inertia-root Blade view loads the
 * built client bundle from public/inertia (see docs/FRAMEWORKS.md,
 * «نشانی‌ها در پلی‌گراوند»).
 */
Route::get('/react/admin/{any?}', fn () => response()->file(public_path('react/admin/index.html')))
    ->where('any', '^(?!.*assets/).*$')->name('react.admin');
Route::get('/react/{any?}', fn () => response()->file(public_path('react/index.html')))
    ->where('any', '^(?!.*assets/).*$')->name('react.showcase');
Route::get('/vue/{any?}', fn () => response()->file(public_path('vue/index.html')))
    ->where('any', '^(?!.*assets/).*$')->name('vue.admin');
Route::get('/svelte/{any?}', fn () => response()->file(public_path('svelte/index.html')))
    ->where('any', '^(?!.*assets/).*$')->name('svelte.admin');

Route::get('/inertia', \App\Http\Controllers\Inertia\DashboardController::class)->name('inertia.dashboard');
Route::get('/inertia/gallery', \App\Http\Controllers\Inertia\GalleryController::class)->name('inertia.gallery');
Route::get('/inertia/visits', \App\Http\Controllers\Inertia\VisitsController::class)->name('inertia.visits');

