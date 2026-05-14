<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProjectSelectionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $registration;
    public $type;

    public function __construct($registration, $type = 'general')
    {
        $this->registration = $registration;
        $this->type = $type;
    }

    public function envelope(): Envelope
    {
        // ইভেন্ট অনুযায়ী সাবজেক্ট নির্ধারণ
        $subject = match ($this->type) {
            'iupc' => 'Selected for IUPC - DUET CSE Carnival 2026',
            'ict-olympiad' => 'Selected for ICT Olympiad - DUET CSE Carnival ',
            'project-showcase' => 'Project Shawcasing Selected - DUET CSE Carnival ',
            default => 'Congratulations! Your Registration is Confirmed',
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.selection_notification',
            with: ['registration' => $this->registration, 'type' => $this->type]
        );
    }
}
