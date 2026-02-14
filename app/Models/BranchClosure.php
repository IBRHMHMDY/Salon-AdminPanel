<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchClosure extends Model
{
    protected $fillable = ['branch_id', 'closure_date', 'reason'];

    protected $casts = [
        'closure_date' => 'date',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
