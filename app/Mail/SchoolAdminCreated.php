<?php

namespace App\Mail;

use App\Models\Admin;
use App\Models\School;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SchoolAdminCreated extends Mailable 
{
    use Queueable, SerializesModels;

    public $admin;
    public $school;
    public $password;

    public function __construct(Admin $admin, School $school, string $password)
    {
        $this->admin = $admin;
        $this->school = $school;
        $this->password = $password;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to ' . config('app.name') . ' - Your School Admin Account',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.school_admin_created',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
