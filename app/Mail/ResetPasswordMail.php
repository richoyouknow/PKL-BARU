<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $resetCode;

    public function __construct($resetCode)
    {
        $this->resetCode = $resetCode;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Password - Koperasi Denmart',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.auth.reset-password',
            with: [
                'resetCode' => $this->resetCode,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
