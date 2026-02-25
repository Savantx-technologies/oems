<?php

namespace App\Notifications;

use App\Models\Exam;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ExamCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Exam $exam;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\Exam $exam
     * @return void
     */
    public function __construct(Exam $exam)
    {
        $this->exam = $exam;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // We are storing it in the database. You could add 'mail' to send an email too.
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title'     => 'New Exam Created',
            'message'   => "A new exam '{$this->exam->name}' has been created by an admin.",
            'exam_id'   => $this->exam->id,
            'exam_name' => $this->exam->name,
            'url'       => route('superadmin.exams.show', $this->exam->id),
        ];
    }

    /**
     * Get the type of the notification.
     */
    public function databaseType(object $notifiable): string
    {
        return 'exam_created';
    }
}
