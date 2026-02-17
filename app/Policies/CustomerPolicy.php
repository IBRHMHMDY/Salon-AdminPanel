<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;

class CustomerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_customers');
    }

    public function view(User $user, Customer $customer): bool
    {
        return $user->hasPermissionTo('view_customers');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_customer');
    }

    public function update(User $user, Customer $customer): bool
    {
        return $user->hasPermissionTo('update_customer');
    }

    public function delete(User $user, Customer $customer): bool
    {
        return $user->hasPermissionTo('delete_customer');
    }
}
