<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Staff extends User
{
    protected $table = 'users';

    protected $guard_name = 'web';

    // هام جداً لكي تعمل صلاحيات Spatie بشكل صحيح مع هذا الموديل الوهمي
    public function getMorphClass()
    {
        return User::class;
    }

    protected static function booted()
    {
        parent::booted();

        // فلتر: إظهار من ليسوا عملاء (الملاك، مديرين الفروع، الموظفين)
        static::addGlobalScope('staff_only', function (Builder $builder) {
            $builder->whereHas('roles', function ($query) {
                $query->where('name', '!=', 'Customer');
            });
        });
    }
}
