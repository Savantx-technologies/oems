<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSchedule extends Model
{
    protected $fillable = [
        'exam_id',
        'start_at',
        'end_at',
        'late_entry_allowed',
        'late_entry_minutes',
        'max_attempts',
    ];


    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
