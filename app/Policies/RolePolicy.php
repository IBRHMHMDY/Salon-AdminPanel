<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    // السماح للمالك ومدير الفرع فقط برؤية قائمة الأدوار
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Owner', 'Branch Manager']);
    }

    public function view(User $user, Role $role): bool
    {
        return $user->hasAnyRole(['Owner', 'Branch Manager']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Owner', 'Branch Manager']);
    }

    public function update(User $user, Role $role): bool
    {
        // يمنع نهائياً التعديل على دور "المالك" إلا إذا كان المستخدم الحالي هو المالك نفسه
        if ($role->name === 'Owner' && ! $user->hasRole('Owner')) {
            return false;
        }

        return $user->hasAnyRole(['Owner', 'Branch Manager']);
    }

    public function delete(User $user, Role $role): bool
    {
        // يمنع حذف الأدوار الأساسية للنظام حمايةً للبيانات
        if (in_array($role->name, ['Owner', 'Branch Manager', 'Staff', 'Customer'])) {
            return false;
        }

        return $user->hasAnyRole(['Owner', 'Branch Manager']);
    }
}
