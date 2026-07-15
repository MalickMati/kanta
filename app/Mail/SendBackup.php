<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;

/**
 * NOTE: This mailable intentionally does NOT implement ShouldQueue.
 * Sending is handled by the parent SendBackupJob which is already queued.
 * File content is embedded directly so the file can be safely deleted afterwards.
 */
class SendBackup extends Mailable
{
    public string $excelContent;
    public string $excelName;
    public ?string $sqlContent;
    public ?string $sqlName;

    public function __construct(string $excelPath, ?string $sqlPath)
    {
        $this->excelContent = file_exists($excelPath) ? file_get_contents($excelPath) : '';
        $this->excelName    = basename($excelPath);
        $this->sqlContent   = ($sqlPath && file_exists($sqlPath)) ? file_get_contents($sqlPath) : null;
        $this->sqlName      = $sqlPath ? basename($sqlPath) : null;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Database Backup – ' . now()->format('d M Y'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.backup',
        );
    }

    public function attachments(): array
    {
        $attachments = [];

        if (!empty($this->excelContent)) {
            $attachments[] = Attachment::fromData(
                fn () => $this->excelContent,
                $this->excelName
            )->withMime('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        }

        if (!empty($this->sqlContent)) {
            $attachments[] = Attachment::fromData(
                fn () => $this->sqlContent,
                $this->sqlName
            )->withMime('application/sql');
        }

        return $attachments;
    }
}
