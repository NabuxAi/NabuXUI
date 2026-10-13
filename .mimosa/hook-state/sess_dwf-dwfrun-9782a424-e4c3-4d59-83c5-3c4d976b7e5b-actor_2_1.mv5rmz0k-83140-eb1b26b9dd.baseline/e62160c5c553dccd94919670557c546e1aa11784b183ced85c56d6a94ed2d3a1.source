<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

/**
 * Every ability is open in this demo, but the decisions pass through a
 * real policy so the Filament wiring (EditAction, bulk actions, page
 * authorization) is exercised instead of short-circuiting on a missing
 * policy. Filament v5 asks Laravel for the standard abilities listed
 * below (see its HasAuthorization concern); each one answers true here.
 */
class ProductPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Product $product): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Product $product): bool
    {
        return true;
    }

    public function delete(User $user, Product $product): bool
    {
        return true;
    }

    public function deleteAny(User $user): bool
    {
        return true;
    }

    public function forceDelete(User $user, Product $product): bool
    {
        return true;
    }

    public function forceDeleteAny(User $user): bool
    {
        return true;
    }

    public function restore(User $user, Product $product): bool
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

    public function replicate(User $user, Product $product): bool
    {
        return true;
    }
}
