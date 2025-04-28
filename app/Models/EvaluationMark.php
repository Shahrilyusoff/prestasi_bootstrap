<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationMark extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluation_id', 'criteria_id', 'ppp_mark', 'ppk_mark', 'ppp_comment', 'ppk_comment'
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function criteria()
    {
        return $this->belongsTo(EvaluationCriteria::class);
    }
}