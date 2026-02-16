<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Exam extends Model
{
    protected $fillable = [
        'school_id',
        'created_by',
        'title',
        'class',
        'subject',
        'academic_session',
        'exam_type',
        'duration_minutes',
        'pass_marks',
        'negative_marking',
        'negative_marks',
        'shuffle_questions',
        'shuffle_options',
        'instructions',
        'total_marks',
        'status',
        'selected_questions',
    ];

    protected $casts = [
        'selected_questions' => 'array',
    ];

    public function questions()
    {
        return $this->belongsToMany(Question::class)
            ->withPivot(['serial_no', 'marks', 'set_code'])
            ->orderBy('pivot_set_code')
            ->orderBy('pivot_serial_no');
    }

    public function schedule()
    {
        return $this->hasOne(\App\Models\ExamSchedule::class,'exam_id');
    }

    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }

   public function isExpired()
{
    if (!$this->schedule) {
        return false;
    }

    return now()->greaterThanOrEqualTo($this->schedule->end_at);
}

}
