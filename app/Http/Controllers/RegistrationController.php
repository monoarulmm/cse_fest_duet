<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Transaction;
use App\Models\Event;
use Illuminate\Http\Request;
use ShurjopayPlugin\Shurjopay;
use ShurjopayPlugin\PaymentRequest;
use Exception;

class RegistrationController extends Controller
{
    private $sp_instance;

    // কনস্ট্রাক্টরের মাধ্যমে Shurjopay ইনজেক্ট করা
    public function __construct(Shurjopay $sp_instance)
    {
        $this->sp_instance = $sp_instance;
    }

    public function create($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        return view('users.events.pre_reg_form', compact('event'));
    }

    public function store(Request $request)
    {
        // ১. কমন ভ্যালিডেশন রুলস
        $commonRules = [
            'event_id'        => 'required|exists:events,id',
            'university_name' => 'required|string|max:255',
            'm1_name'         => 'required|string|max:255',
            'm1_email'        => 'required|email|max:255',
            'm1_phone'        => 'required|string|max:20',
            'm1_tshirt'       => 'nullable|string',
            'prev_ex'         => 'nullable|string',
        ];

        $event = \App\Models\Event::findOrFail($request->event_id);
        $eventSlug = $event->slug;
        $eventRules = [];

        // ২. ইভেন্ট অনুযায়ী ডাইনামিক ভ্যালিডেশন (আগের মতোই)
        if ($eventSlug === 'ict-olympiad') {
            $eventRules = ['student_id' => 'required|string|max:50'];
        } elseif ($eventSlug === 'iupc') {
            $eventRules = [
                'team_name' => 'required|unique:registrations,team_name|max:255',
                // ... বাকি রুলস
            ];
        } elseif ($eventSlug === 'project-showcase' || $eventSlug === 'ai-hackathon') {
            $eventRules = [
                'team_name' => 'required|unique:registrations,team_name|max:255',
                // ... বাকি রুলস
            ];
        }

        $validatedData = $request->validate(array_merge($commonRules, $eventRules));

        try {
            // ৩. আদার ইউনিভার্সিটি হ্যান্ডলিং
            if ($request->university_name === 'Others' && $request->filled('other_university')) {
                $validatedData['university_name'] = $request->other_university;
            }

            // ৪. টিম আইডি জেনারেশন (ডাটাবেসে সেভ করার আগেই এটি করতে হবে)
            $teamEvents = ['iupc', 'project-showcase', 'ai-hackathon'];
            if (in_array($eventSlug, $teamEvents)) {
                $prefix = strtoupper(str_replace('-', '', $eventSlug));
                $prefix = substr($prefix, 0, 4);

                do {
                    $teamId = $prefix . '-' . rand(1000, 9999);
                } while (\App\Models\Registration::where('team_id', $teamId)->exists());

                // গুরুত্বপূর্ণ: ডাটা সেভ করার আগেই অ্যারেতে টিম আইডি ঢুকিয়ে দিন
                $validatedData['team_id'] = $teamId;
            } else {
                $validatedData['team_id'] = null;
            }

            // ৫. এবার ডাটা সেভ করুন (এখন $validatedData তে team_id আছে)
            $registration = \App\Models\Registration::create($validatedData);
            // ৬. যদি ICT Olympiad হয়, তবে সরাসরি পেমেন্ট মেথড কল করুন
            if ($eventSlug === 'ict-olympiad') {
                return $this->makePayment($registration->id);
            }
            // ৬. সাকসেস মেসেজ
            $msg = 'Registration submitted for ' . $event->title;
            if ($registration->team_id) {
                $msg .= '. Your Team ID: ' . $registration->team_id;
            }

            return redirect()->back()->with('success', $msg);
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage())->withInput();
        }
    }
    public function makePayment($registration_id)
    {
        $registration = Registration::with('event')->findOrFail($registration_id);

        try {
            $paymentRequest = new PaymentRequest();
            $paymentRequest->currency = 'BDT';
            $paymentRequest->amount = $registration->event->reg_fee;

            // ইউনিক অর্ডার আইডি তৈরি (ভেরিফিকেশনের জন্য গুরুত্বপূর্ণ)
            $order_id = "ICT-" . uniqid();
            $registration->update(['order_id' => $order_id]);

            $paymentRequest->orderId = $order_id;
            $paymentRequest->customerName = $registration->m1_name;
            $paymentRequest->customerPhone = $registration->m1_phone;
            $paymentRequest->customerEmail = $registration->m1_email;
            $paymentRequest->customerAddress = 'DUET, Gazipur';
            $paymentRequest->customerCity = 'Gazipur';

            // স্যান্ডবক্স সমস্যার সমাধান: value1 এ সরাসরি ID পাস করা
            $paymentRequest->value1 = $registration->id;

            return $this->sp_instance->makePayment($paymentRequest);
        } catch (Exception $e) {
            return back()->with('error', 'Payment Gateway Error: ' . $e->getMessage());
        }
    }

    public function callback(Request $request)
    {
        $order_id = $request->order_id;

        try {
            $verification = $this->sp_instance->verifyPayment($order_id);
            $data = $verification[0];

            if ($data->sp_code == '1000') {
                // customer_order_id অথবা value1 দিয়ে রেজিস্ট্রেশন খুঁজে বের করা
                $registration = Registration::where('order_id', $data->customer_order_id)
                    ->orWhere('id', $data->value1)
                    ->first();

                if ($registration && $registration->payment_status !== 'paid') {

                    // ১. ট্রানজেকশন টেবিলে এন্ট্রি
                    Transaction::create([
                        'transaction_id' => $data->bank_trx_id,
                        'event_id'       => $registration->event_id,
                        'team_id'        => $registration->id, // ICT এর জন্য টিম ID নাই, তাই রেজিস্ট্রেশন ID
                        'student_id'     => $registration->student_id,
                        'amount'         => $data->amount,
                        'currency'       => $data->currency,
                        'status'         => 'Successful',
                        'payment_method' => $data->method,
                    ]);

                    // ২. রেজিস্ট্রেশন স্ট্যাটাস আপডেট
                    $registration->update([
                        'transaction_id' => $data->bank_trx_id,
                        'payment_status' => 'paid', // আপনার ডাটাবেস কলাম অনুযায়ী (pending_status হলে সেটা দিন)
                        'status'         => 'verified'
                    ]);

                    return redirect()->route('event.dashboard', $registration->event->slug)
                        ->with('success', 'আপনার পেমেন্ট সফল হয়েছে!');
                }
            }

            return redirect()->route('home')->with('error', 'পেমেন্ট সফল হয়নি।');
        } catch (Exception $e) {
            return redirect()->route('home')->with('error', 'ভেরিফিকেশন এরর: ' . $e->getMessage());
        }
    }
}
