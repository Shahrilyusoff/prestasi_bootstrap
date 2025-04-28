<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkTargetItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_target_id', 'activity', 'performance_indicator', 'is_added', 'is_removed'
    ];

    public function workTarget()
    {
        return $this->belongsTo(WorkTarget::class);
    }
}