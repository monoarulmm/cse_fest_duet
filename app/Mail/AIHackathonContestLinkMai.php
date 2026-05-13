<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AIHackathonContestLinkMai extends Mailable
{

    use Queueable, SerializesModels;

    public $registration;
    public $contestLink;

    /**
     * Create a new message instance.
     */
    public function __construct($registration, $contestLink)
    {
        $this->registration = $registration;
        $this->contestLink = $contestLink;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Invitation: AI-Hackathon Preliminary Contest Link')
            ->view('emails.ai_hackathon_link');
    }
}
