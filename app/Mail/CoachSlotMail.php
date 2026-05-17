<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CoachSlotMail extends Mailable
{
    use Queueable, SerializesModels;

    // ডাটা ধরার জন্য পাবলিক প্রোপার্টি ডিফাইন করা
    public $generatedCodes;
    public $coachName;

    /**
     * ডাটা রিসিভ করার জন্য কনস্ট্রাক্টর আপডেট করা হয়েছে
     */
    public function __construct($generatedCodes, $coachName)
    {
        $this->generatedCodes = $generatedCodes;
        $this->coachName = $coachName;
    }

    /**
     * মেইলের সাবজেক্ট সেট করা
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'IUPC Registration Slot Codes - DUET CSE CARNIVAL',
        );
    }

    /**
     * ব্লেড ভিউ ফাইল সেট করা
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.coach_slots', // নিশ্চিত করুন resources/views/emails/coach_slots.blade.php ফাইলটি আছে
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
