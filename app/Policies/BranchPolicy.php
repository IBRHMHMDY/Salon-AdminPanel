<?php

namespace App\Policies;

use App\Models\Branch;
use App\Models\User;

class BranchPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_branch');
    }

    public function view(User $user, Branch $branch): bool
    {
        return $user->hasPermissionTo('view_branch');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_branch');
    }

    public function update(User $user, Branch $branch): bool
    {
        return $user->hasPermissionTo('update_branch');
    }

    public function delete(User $user, Branch $branch): bool
    {
        return $user->hasPermissionTo('delete_branch');
    }
}
