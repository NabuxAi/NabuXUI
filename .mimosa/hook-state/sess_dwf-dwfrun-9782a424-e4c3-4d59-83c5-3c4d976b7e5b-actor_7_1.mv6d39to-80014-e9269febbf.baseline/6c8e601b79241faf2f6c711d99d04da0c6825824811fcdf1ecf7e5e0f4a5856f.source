<?php

namespace App\Policies;

use App\Models\User;

/**
 * Authorization for the users themselves. The only locked door is the
 * edit: a user may edit their own record, or anyone whose email is
 * admin@nabux.test (the demo admin). Every other ability stays open so
 * the demo panel keeps working while still flowing through a real
 * policy — Filament's EditAction and EditUser page read `update` and
 * hide/deny on their own, so the resource needs no extra can() calls.
 */
class UserPolicy
{
    /** Who may edit a user: themselves, or the demo admin. */
    public function update(User $user, User $model): bool
    {
        return $user->id === $model->id
            || $user->email === 'admin@nabux.test';
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, User $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function delete(User $user, User $model): bool
    {
        return true;
    }

    public function deleteAny(User $user): bool
    {
        return true;
    }

    public function forceDelete(User $user, User $model): bool
    {
        return true;
    }

    public function forceDeleteAny(User $user): bool
    {
        return true;
    }

    public function restore(User $user, User $model): bool
    {
        return true;
    }

    public function restoreAny(User $user): bool
    {
        return true;
    }

    public function reorder(User $user): bool
    {
        return true;
    }

    public function replicate(User $user, User $model): bool
    {
        return true;
    }
}
