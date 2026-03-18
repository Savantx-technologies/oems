<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserExamAnswer extends Model
{
    protected $fillable = [
        'school_id',
        'attempt_id',
        'user_id',
        'exam_id',
        'question_id',
        'selected_option',
        'is_correct',
        'admin_checked',
        'marks_awarded',

    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function attempt()
    {
        return $this->belongsTo(ExamAttempt::class, 'attempt_id');
    }


}