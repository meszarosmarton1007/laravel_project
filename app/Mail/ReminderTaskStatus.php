<?php

namespace App\Mail;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReminderTaskStatus extends Mailable
{
    use Queueable, SerializesModels;

    public $task;

    /**
     * Új üzenet létrehozása
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Az email boritékjának összeállítása
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Emlékeztető határidő közeledédéről',
        );
    }

    /**
     * Az üzenet tartalmának meghatározása
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.task-reminder',
        );
    }

    /**
     * Az üzenet mellékleteinek lekérése
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
