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
Route::get('/admin/email', \App\Livewire\Admin\Email::class)->name('admin.email');
Route::get('/admin/files', \App\Livewire\Admin\Files::class)->name('admin.files');
Route::get('/admin/todo', \App\Livewire\Admin\Todo::class)->name('admin.todo');
Route::get('/admin/roles', \App\Livewire\Admin\Roles::class)->name('admin.roles');
Route::get('/admin/errors/404', \App\Livewire\Admin\ErrorPage::class)->defaults('code', '404')->name('admin.errors.404');
Route::get('/admin/errors/500', \App\Livewire\Admin\ErrorPage::class)->defaults('code', '500')->name('admin.errors.500');
Route::get('/admin/errors/maintenance', \App\Livewire\Admin\ErrorPage::class)->defaults('code', 'maintenance')->name('admin.errors.maintenance');

// Auth gate: one component, three panes (login / register / forgot-password).
Route::get('/login', \App\Livewire\Auth\Gate::class)->defaults('mode', 'login')->name('login');
Route::get('/register', \App\Livewire\Auth\Gate::class)->defaults('mode', 'register')->name('register');
Route::get('/forgot-password', \App\Livewire\Auth\Gate::class)->defaults('mode', 'forgot')->name('password.request');

