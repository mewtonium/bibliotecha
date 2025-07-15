<?php

declare(strict_types=1);

namespace App\Policies\Api;

use App\Models\User;

final class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $auth): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $auth, User $user): bool
    {
        return $auth->id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $auth): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $auth, User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $auth, User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $auth, User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $auth, User $user): bool
    {
        return false;
    }
}
