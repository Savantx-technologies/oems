<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     * Using guarded instead of fillable to protect the primary key only.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * Get the admins associated with the school.
     */
    public function admins()
    {
        return $this->hasMany(Admin::class);
    }

    /**
     * Get the students associated with the school.
     */
    public function students()
    {
        return $this->hasMany(User::class)->where('role', 'student');
    }

    /**
     * Get the exams associated with the school.
     */
    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    /**
     * Get the exam attempts associated with the school.
     */
    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }
}
