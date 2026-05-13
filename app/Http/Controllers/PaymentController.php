<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\Transaction;
use ShurjopayPluginPhp\Shurjopay;
use ShurjopayPluginPhp\ShurjopayConfig;
use ShurjopayPluginPhp\PaymentRequest;

class PaymentController extends Controller
{
    private $sp_instance;

    public function __construct()
    {
        // ডকুমেন্টেশন অনুযায়ী কনফিগ সেটআপ
        $config = new ShurjopayConfig();
        $config->setMerchantUsername(env('SP_USERNAME'));
        $config->setMerchantPassword(env('SP_PASSWORD'));
        $config->setMerchantPrefix(env('SP_PREFIX'));
        $config->setShurjopayApi(env('SHURJOPAY_API'));
        $config->setShurjopayCallbackUrl(env('SP_CALLBACK'));
        $config->setLogLocation(base_path(env('SP_LOG_LOCATION')));

        $this->sp_instance = new Shurjopay($config);
    }

    public function makePayment($registration_id)
    {
        $registration = Registration::with('event')->findOrFail($registration_id);

        // ১. পেমেন্ট রিকোয়েস্ট অবজেক্ট তৈরি
        $request = new PaymentRequest();
        $request->currency = 'BDT';
        $request->amount = $registration->event->reg_fee;
        $request->customerName = $registration->m1_name;
        $request->customerPhone = $registration->m1_phone;
        $request->customerEmail = $registration->m1_email;
        $request->customerAddress = 'DUET, Gazipur';
        $request->customerCity = 'Gazipur';
        $request->customerPostcode = '1700';
        $request->customerCountry = 'Bangladesh';

        // Custom Data (ভেরিফিকেশনের সময় কাজে লাগবে)
        $request->value1 = $registration->id;

        // ২. ট্রানজেকশন ডাটাবেসে সেভ (Status: Pending)
        // নোট: shurjoPay অটোমেটিক একটি order_id জেনারেট করে যা makePayment রিটার্ন করবে

        return $this->sp_instance->makePayment($request);
    }

    public function callback(Request $request)
    {
        // সূর্যমুখী থেকে ফিরে আসা অর্ডার আইডি
        $order_id = $request->order_id;

        // ৩. পেমেন্ট ভেরিফাই করা
        $verification = $this->sp_instance->verifyPayment($order_id);

        // সফল পেমেন্ট চেক (sp_code 1000 মানে Success)
        if ($verification[0]->sp_code == '1000') {

            $registration_id = $verification[0]->value1;
            $registration = Registration::find($registration_id);

            if ($registration) {
                // ট্রানজেকশন রেকর্ড আপডেট
                Transaction::create([
                    'transaction_id' => $order_id,
                    'event_id'       => $registration->event_id,
                    'team_id'        => $registration->team_id,
                    'student_id'     => $registration->student_id,
                    'amount'         => $verification[0]->amount,
                    'status'         => 'Successful',
                ]);

                // রেজিস্ট্রেশন স্ট্যাটাস আপডেট
                $registration->update([
                    'transaction_id' => $order_id,
                    'pending_status' => 'paid',
                    'status'         => 'verified'
                ]);

                return redirect()->route('event.hub', $registration->event->slug)
                    ->with('success', 'Payment successful!');
            }
        }

        return redirect()->route('home')->with('error', 'Payment failed!');
    }
}
