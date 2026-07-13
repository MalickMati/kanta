<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendBackup extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $excelPath;
    public $sqlPath;

    /**
     * Create a new message instance.
     */
    public function __construct($excelPath, $sqlPath)
    {
        $this->excelPath = $excelPath;
        $this->sqlPath = $sqlPath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Send Backup',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.backup',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        if (file_exists($this->excelPath)) {
            $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromPath($this->excelPath);
        }

        if (file_exists($this->sqlPath)) {
            $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromPath($this->sqlPath);
        }

        return $attachments;
    }
}
