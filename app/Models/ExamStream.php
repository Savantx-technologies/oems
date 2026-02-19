<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamStream extends Model
{
    use HasFactory;

    protected $fillable = [
        'attempt_id',
        'viewer_id',
        'viewer_type',
        'viewer_session_id',
        'offer',
        'answer',
        'student_ice_candidates',
        'viewer_ice_candidates',
        'status',
    ];

    public function attempt()
    {
        return $this->belongsTo(ExamAttempt::class, 'attempt_id');
    }

    public function viewer()
    {
        return $this->morphTo();
    }
}
