<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AIRegistrationStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    use Queueable, SerializesModels;

    public $registration;
    public $status;

    public function __construct($registration, $status)
    {
        $this->registration = $registration;
        $this->status = $status;
    }

    public function build()
    {
        $subject = $this->status == 'selected' ? 'Congratulations! You are Selected' : 'Update regarding your Registration';

        return $this->subject($subject)
            ->view('emails.ai_registration_status'); // এই ভিউ ফাইলটি তৈরি করতে হবে
    }
}
