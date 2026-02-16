<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    protected $fillable = [
        'school_id',
        'user_id',
        'exam_id',
        'total_questions',
        'total_correct',
        'score',
        'started_at',
        'submitted_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'expires_at' => 'datetime',
        'last_activity_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function answers()
    {
        return $this->hasMany(UserExamAnswer::class, 'attempt_id');
    }

    public function violations()
    {
        return $this->hasMany(ExamViolation::class, 'attempt_id');
    }
}
