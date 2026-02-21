<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;

class ServicePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_service');
    }

    public function view(User $user, Service $service): bool
    {
        return $user->hasPermissionTo('view_service');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_service');
    }

    public function update(User $user, Service $service): bool
    {
        return $user->hasPermissionTo('update_service');
    }

    public function delete(User $user, Service $service): bool
    {
        return $user->hasPermissionTo('delete_service');
    }
}
