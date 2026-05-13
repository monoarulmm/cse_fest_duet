<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CouponCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $team;

    /**
     * কনস্ট্রাক্টরে রেজিস্ট্রেশন ডাটা (টিম) রিসিভ করতে হবে।
     */
    public function __construct($team)
    {
        $this->team = $team;
    }

    public function build()
    {
        // ইভেন্ট অনুযায়ী সাবজেক্ট ডাইনামিক করা হয়েছে
        $subject = $this->team->event->name . ' Registration Confirmation - CSE FEST 2026';

        return $this->view('emails.coupon_code')
            ->subject($subject);
    }
}
