<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationCriteria extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id', 'criteria', 'max_mark', 'weightage'
    ];

    public function section()
    {
        return $this->belongsTo(EvaluationSection::class);
    }

    public function marks()
    {
        return $this->hasMany(EvaluationMark::class);
    }
}