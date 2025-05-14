<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeNewUserEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $jobRequest;
    public $generatedPassword;

    /**
     * Create a new message instance.
     */
    public function __construct($jobRequest, $generatedPassword)
    {
        $this->jobRequest = $jobRequest;
        $this->generatedPassword = $generatedPassword;
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to RumahFix! Your account has been created.',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome_new_user_created',
            with: [
                'jobRequest' => $this->jobRequest,
                'generatedPassword' => $this->generatedPassword,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
