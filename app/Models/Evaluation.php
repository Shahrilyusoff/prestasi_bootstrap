<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'pyd_id', 'ppp_id', 'ppk_id', 'pyd_group_id', 'year', 'status', 'evaluation_period_id'
    ];

    public function pyd()
    {
        return $this->belongsTo(User::class, 'pyd_id');
    }

    public function ppp()
    {
        return $this->belongsTo(User::class, 'ppp_id');
    }

    public function ppk()
    {
        return $this->belongsTo(User::class, 'ppk_id');
    }

    public function pydGroup()
    {
        return $this->belongsTo(PydGroup::class);
    }

    public function evaluationPeriod()
    {
        return $this->belongsTo(EvaluationPeriod::class);
    }

    public function marks()
    {
        return $this->hasMany(EvaluationMark::class);
    }

    public function workTargets()
    {
        return $this->hasMany(WorkTarget::class);
    }
}