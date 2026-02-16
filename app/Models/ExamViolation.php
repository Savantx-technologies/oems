<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamViolation extends Model
{
    protected $fillable = [
        'attempt_id',
        'user_id',
        'type',
        'occurred_at',
        'ip_address',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
    ];

    public function attempt()
    {
        return $this->belongsTo(ExamAttempt::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
