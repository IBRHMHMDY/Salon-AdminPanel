<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use \App\Models\Traits\BelongsToSalon;

    protected $fillable = [
        'salon_id', // 🚀 أضف هذا
        'name',
        'phone',
        'address',
        'is_active',
    ];

    protected $guarded = [];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    public function workingHours(): HasMany
    {
        return $this->hasMany(BranchWorkingHour::class);
    }

    public function closures(): HasMany
    {
        return $this->hasMany(BranchClosure::class);
    }
}
