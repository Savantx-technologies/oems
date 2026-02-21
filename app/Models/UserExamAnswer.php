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
        'selected_option_id',
        'is_correct',
        'marks_awarded',

    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function selectedOption()
    {
        return $this->belongsTo(QuestionOption::class, 'selected_option_id');
    }
}