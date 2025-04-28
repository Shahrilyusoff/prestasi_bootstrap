<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluation_id', 'type', 'pyd_report', 'ppp_report', 'approved'
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function items()
    {
        return $this->hasMany(WorkTargetItem::class);
    }
}