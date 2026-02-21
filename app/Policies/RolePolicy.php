<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('manage_roles');
    }

    public function view(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('manage_roles');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage_roles');
    }

    public function update(User $user, Role $role): bool
    {
        if ($role->name === 'Owner' && ! $user->hasRole('Owner')) {
            return false;
        }

        return $user->hasPermissionTo('manage_roles');
    }
}
