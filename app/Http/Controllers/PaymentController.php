<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\Transaction;
use App\Models\Coupon;

use ShurjopayPlugin\Shurjopay;
use ShurjopayPlugin\PaymentRequest;

class PaymentController extends Controller
{


    protected $shurjopay;

    public function __construct(Shurjopay $shurjopay)
    {
        // লারাভেল সার্ভিস কন্টেইনার স্বয়ংক্রিয়ভাবে ইনজেক্ট করবে
        $this->shurjopay = $shurjopay;
    }

    public function iupc_updateAndPay(Request $request)
    {
        $team = Registration::with('event')->findOrFail($request->team_id);
        $event = $team->event;

        // ১. ভ্যালিডেশন
        if ($event->slug == 'iupc') {
            $request->validate([
                'coach_name' => 'required|string',
                'm1_name'    => 'required|string',
                'm1_phone'   => 'required|digits:11',
                'm1_email'   => 'required|email',
            ]);

            $team->update($request->except(['_token', 'team_id', 'amount']));
        }

        // ২. পেমেন্ট রিকোয়েস্ট
        try {
            // ইভেন্ট টেবিল থেকে অটোমেটিক ফি নেওয়া হচ্ছে
            $registration_fee = $event->reg_fee;

            // একটি ইউনিক অর্ডার আইডি তৈরি (image_bdd263.png এ যেমন দেখা যাচ্ছে)
            $order_id = strtoupper($event->slug) . "-" . uniqid();
            $team->update(['order_id' => $order_id]);

            $paymentRequest = new PaymentRequest();
            $paymentRequest->currency = 'BDT';
            $paymentRequest->amount = $registration_fee;
            $paymentRequest->orderId = $order_id;
            $paymentRequest->customerName = $team->team_name ?? $team->m1_name;
            $paymentRequest->customerPhone = $team->m1_phone;
            $paymentRequest->customerEmail = $team->m1_email;
            $paymentRequest->customerAddress = $team->university_name ?? 'Gazipur';
            $paymentRequest->customerCity = 'Gazipur';

            return $this->shurjopay->makePayment($paymentRequest);
        } catch (Exception $e) {
            return back()->with('error', 'পেমেন্ট গেটওয়ে এরর: ' . $e->getMessage());
        }
    }

    //ai hacton and projectsh
    public function finalRegDirectPay($slug, $id)
    {
        $team = Registration::with('event')->where('id', $id)->firstOrFail();
        $event = $team->event;

        if ($team->payment_status === 'paid') {
            return redirect()->route('event.dashboard', $slug)->with('info', 'পেমেন্ট আগেই সম্পন্ন হয়েছে।');
        }

        try {
            // ১. ফোন নম্বর ক্লিন করা (ShurjoPay সাধারণত ১১ ডিজিট নম্বর পছন্দ করে)
            // নম্বর থেকে স্পেস বা অন্য ক্যারেক্টার বাদ দেওয়া
            $phone = preg_replace('/[^0-9]/', '', $team->m1_phone);

            // যদি নম্বরটি ৮৮০ দিয়ে শুরু হয় তবে ৮৮ বাদ দিয়ে ১১ ডিজিট করা
            if (strlen($phone) > 11 && str_starts_with($phone, '88')) {
                $phone = substr($phone, 2);
            }

            // ২. রিকোয়েস্ট তৈরি
            $paymentRequest = new PaymentRequest();
            $paymentRequest->currency = 'BDT';
            $paymentRequest->amount = $event->reg_fee;

            $order_id = strtoupper($slug) . "-FIN-" . $team->id . "-" . uniqid();
            $team->update(['order_id' => $order_id]);
            $paymentRequest->orderId = $order_id;

            $paymentRequest->customerName = $team->team_name ?? $team->m1_name;
            $paymentRequest->customerPhone = $phone; // পরিশোধিত ফোন নম্বর
            $paymentRequest->customerEmail = $team->m1_email;
            $paymentRequest->customerAddress = $team->university_name ?? 'Gazipur';
            $paymentRequest->customerCity = 'Gazipur';
            $paymentRequest->value1 = $team->id;

            return $this->shurjopay->makePayment($paymentRequest);
        } catch (Exception $e) {
            // image_bceabc.png এর মতো "getMessage() on null" এরর এড়াতে নিরাপদ ক্যাচিং
            $errorMessage = method_exists($e, 'getMessage') ? $e->getMessage() : 'পেমেন্ট গেটওয়েতে টেকনিক্যাল সমস্যা হয়েছে।';

            return back()->with('error', 'পেমেন্ট এরর: ' . $errorMessage);
        }
    }


    public function paymentCallback(Request $request)
    {
        $order_id = $request->order_id;

        try {
            $response = $this->shurjopay->verifyPayment($order_id);

            // ShurjoPay রেসপন্স অবজেক্ট না অ্যারো তা চেক করে ডেটা নেওয়া
            $data = is_array($response) ? (object)$response[0] : $response;

            // image_bdc3df.png অনুযায়ী sp_code "1000" মানে সফল পেমেন্ট
            if ($data->sp_code == "1000") {

                // ১. রেজিস্ট্রেশন খুঁজে বের করা (customer_order_id ব্যবহার করে)
                $team = Registration::where('order_id', $data->customer_order_id)->first();

                if ($team && $team->payment_status !== 'paid') {

                    // ২. অটোমেটিক Participant ID তৈরি (DUET-CSE-1001 স্টাইলে)
                    $prefix = strtoupper($team->event->slug ?? 'EVENT');
                    $count = Registration::where('event_id', $team->event_id)
                        ->where('payment_status', 'paid')
                        ->count();
                    $new_participant_id = $prefix . "-" . (1000 + $count + 1);

                    // ৩. Registration টেবিল আপডেট
                    $team->update([
                        'payment_status' => 'paid',
                        'status'         => 'verified',
                        'participant_id' => $new_participant_id,
                        'transaction_id' => $data->bank_trx_id, // e.g. 6a055812
                    ]);

                    // ৪. Transaction টেবিল পূরণ (আপনার মাইগ্রেশন অনুযায়ী)
                    \App\Models\Transaction::create([
                        'transaction_id' => $data->bank_trx_id,
                        'event_id'       => $team->event_id,
                        'team_id'        => $team->id,
                        'student_id'     => $team->m1_id ?? null,
                        'amount'         => $data->amount,        // 1000.0000
                        'currency'       => $data->currency,      // BDT
                        'status'         => 'Successful',
                        'payment_method' => $data->method,        // Nagad
                    ]);

                    return redirect()->route('event.dashboard', $team->event->slug ?? 'iupc')
                        ->with('success', 'পেমেন্ট সফল এবং ট্রানজেকশন রেকর্ড করা হয়েছে।');
                }
            }

            return redirect()->route('home')->with('error', 'পেমেন্ট ভেরিফাই করা যায়নি।');
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
