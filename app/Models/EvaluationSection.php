<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'weightage', 'is_pyd_section', 'is_ppp_section', 'is_ppk_section'
    ];

    public function criterias()
    {
        return $this->hasMany(EvaluationCriteria::class);
    }
}