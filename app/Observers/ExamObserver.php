<?php

namespace App\Observers;

use App\Models\Exam;
use App\Models\SuperAdmin;
use App\Models\User;
use App\Models\Notification;

class ExamObserver
{
/**
     * Handle the Exam "updated" event.
     *
     * @param  \App\Models\Exam  $exam
     * @return void
     */
    public function updated(Exam $exam): void
    {
        // Check if the status was changed to 'published' from something else
        if ($exam->wasChanged('status') && $exam->status === 'published') {
            // Find all students in the same school and class/grade
            $students = User::where('school_id', $exam->school_id)
                ->where('grade', $exam->class)
                ->where('role', 'student')
                ->get();

            $notifications = [];
            $now = now();
            $title = ($exam->exam_type === 'mock' ? 'New Mock Exam Available: ' : 'New Exam Published: ') . $exam->title;
            $message = "A new exam '{$exam->title}' for subject '{$exam->subject}' has been published.";

            foreach ($students as $student) {
                $notifications[] = [
                    'notifiable_id' => $student->id,
                    'notifiable_type' => get_class($student),
                    'title' => $title,
                    'message' => $message,
                    'type' => 'exam',
                    'data' => json_encode(['exam_id' => $exam->id]),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            
            // Notify the Admin who created the exam
            if ($exam->created_by) {
                $notifications[] = [
                    'notifiable_id' => $exam->created_by,
                    'notifiable_type' => \App\Models\Admin::class,
                    'title' => 'Exam Published Successfully',
                    'message' => "Your exam '{$exam->title}' is now live for students.",
                    'type' => 'exam_published',
                    'data' => json_encode(['exam_id' => $exam->id]),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // Notify all SuperAdmins that a new exam has been created and published
            $superAdmins = SuperAdmin::where('is_active', true)->get();
            if ($superAdmins->isNotEmpty()) {
                foreach ($superAdmins as $superAdmin) {
                    $notifications[] = [
                        'notifiable_id'   => $superAdmin->id,
                        'notifiable_type' => get_class($superAdmin),
                        'title'           => 'New Exam Created',
                        'message'         => "A new exam '{$exam->title}' for subject '{$exam->subject}' has been published.",
                        'type'            => 'exam_created',
                        'data'            => json_encode([
                            'exam_id'   => $exam->id,
                            'exam_name' => $exam->title,
                            'url'       => route('superadmin.exams.show', $exam->id),
                        ]),
                        'created_at'      => $now,
                        'updated_at'      => $now,
                    ];
                }
            }

            // Insert in chunks to avoid issues with large classes
            foreach (array_chunk($notifications, 200) as $chunk) {
                Notification::insert($chunk);
            }
        }
    }
}
