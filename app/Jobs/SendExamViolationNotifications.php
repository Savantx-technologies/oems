<?php

namespace App\Jobs;

use App\Models\Admin;
use App\Models\Exam;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendExamViolationNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $studentId,
        public int $examId,
        public int $attemptId,
        public string $violationType
    ) {
    }

    public function handle(): void
    {
        $student = User::find($this->studentId);
        $exam = Exam::find($this->examId);

        if (!$student || !$exam) {
            return;
        }

        $admins = Admin::where('school_id', $student->school_id)->get(['id']);

        if ($admins->isEmpty()) {
            return;
        }

        $now = now();
        $message = "Student {$student->name} ({$student->admission_number}) recorded a violation in exam '{$exam->title}'. Type: " . ucfirst($this->violationType);

        Notification::insert(
            $admins->map(fn ($admin) => [
                'notifiable_id' => $admin->id,
                'notifiable_type' => Admin::class,
                'title' => 'Violation Alert',
                'message' => $message,
                'type' => 'violation',
                'data' => json_encode([
                    'exam_id' => $exam->id,
                    'attempt_id' => $this->attemptId,
                ]),
                'is_read' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ])->all()
        );
    }
}
