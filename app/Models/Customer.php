<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Customer extends User
{
    protected $table = 'users';

    protected $guard_name = 'web';

    public function getMorphClass()
    {
        return User::class;
    }

    protected static function booted()
    {
        parent::booted();

        // فلتر: إظهار العملاء فقط
        static::addGlobalScope('customer_only', function (Builder $builder) {
            $builder->whereHas('roles', function ($query) {
                $query->where('name', 'Customer');
            });
        });
    }
}
