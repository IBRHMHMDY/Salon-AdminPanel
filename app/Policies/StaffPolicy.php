<?php

namespace App\Policies;

use App\Models\Staff;
use App\Models\User;

class StaffPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_staffs');
    }

    public function view(User $user, Staff $staff): bool
    {
        return $user->hasPermissionTo('view_staffs');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_staff');
    }

    public function update(User $user, Staff $staff): bool
    {
        return $user->hasPermissionTo('update_staff');
    }

    public function delete(User $user, Staff $staff): bool
    {
        // يمنع حذف حساب المالك لحماية النظام
        if ($staff->hasRole('Owner')) {
            return false;
        }

        return $user->hasPermissionTo('delete_staff');
    }
}
