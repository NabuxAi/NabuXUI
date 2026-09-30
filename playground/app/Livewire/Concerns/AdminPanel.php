<?php

namespace App\Livewire\Concerns;

use NabuXUI\Livewire\WithToasts;

/**
 * Shared behaviour of every /admin page: the fa/en switch from the topbar's
 * language menu (session + reload, via SwitchesDemoLocale), the bell's
 * "mark all read", and the user menu's sign-out (back to /login).
 */
trait AdminPanel
{
    use SwitchesDemoLocale;
    use WithToasts;

    /** The activity dropdown's "mark all as read" (x-on:nx-mark-all-read). */
    public function markAllActivityRead(): void
    {
        session(['admin_activity_read' => true]);
    }

    /** The user menu's sign-out: drop the demo session and land on /login. */
    public function logout(): void
    {
        session()->forget('admin_auth');
        $this->toastAfterRedirect(__('admin.auth_logout_done'), tone: 'info');

        $this->redirect(route('login'));
    }
}
