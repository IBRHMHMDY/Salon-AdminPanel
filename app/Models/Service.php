<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    protected $fillable = ['salon_id', 'name', 'duration_minutes', 'price', 'is_active'];

    public function salon(): BelongsTo
    {
        return $this->belongsTo(Salon::class);
    }

    public function providers(): BelongsToMany
    {
        return $this->belongsToMany(Staff::class, 'service_user', 'service_id', 'user_id');
    }
}
