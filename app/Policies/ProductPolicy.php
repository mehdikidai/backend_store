<?php

namespace App\Policies;

use App\Enum\Roles;
use App\Models\User;

class ProductPolicy
{
    /**
     * Roles allowed for general actions (create, update, delete).
     */
    private const GENERAL_ROLES = [
        Roles::Admin->value,
        Roles::Editor->value,
    ];

    /**
     * Check if the user has a specific role.
     */
    private function hasRole(User $user, array $roles): bool
    {
        return in_array($user->role, $roles, true);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->hasRole($user, self::GENERAL_ROLES);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $this->hasRole($user, self::GENERAL_ROLES);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->roles->pluck('name')->contains(Roles::Admin->value);
    }
}
