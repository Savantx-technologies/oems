<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ExamMonitorBlock extends Model
{
    protected $fillable = [
        'exam_id',
        'name',
        'assignee_type',
        'assignee_id',
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class, 'monitor_block_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'exam_monitor_block_student')
            ->withTimestamps();
    }

    public function assignee(): MorphTo
    {
        return $this->morphTo(null, 'assignee_type', 'assignee_id');
    }

    public function scopeAssignedTo($query, $user)
    {
        return $query->where('assignee_type', get_class($user))
            ->where('assignee_id', $user->id);
    }
}
