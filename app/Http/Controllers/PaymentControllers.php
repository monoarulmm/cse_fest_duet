<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\Event;
use Shurjopay\Shurjopay;
use Shurjopay\ShurjopayConfig;
use Shurjopay\PaymentRequest;

class PaymentController extends Controller
{
    public function verifyCoupon(Request $request, $slug)
    {
        $request->validate([
            'team_name' => 'required',
            'coupon_code' => 'required'
        ]);

        $team = Registration::where('team_name', $request->team_name)
            ->where('coupon_code', $request->coupon_code)
            // ->where('event_slug', $slug)
            ->first();

        if (!$team) {
            return back()->with('error', 'Invalid Coupon Code for this team!');
        }

        $event = $team->event;
        $discount = 0; // আপনার ডিসকাউন্ট লজিক
        $finalAmount = $event->reg_fee ?? 1500;

        return view('users.iupc.final_reg', compact('team', 'event', 'discount', 'finalAmount'));
    }

    // ২. সরাসরি ফাইনাল রেজিস্ট্রেশনে যাওয়া (Hackathon/Project Showcase)
    public function finalRegDirect($slug, $id)
    {
        $team = Registration::findOrFail($id);
        $event = $team->event;

        // সরাসরি ফাইনাল পেজে পাঠিয়ে দেওয়া
        $discount = 0;
        $finalAmount = $event->reg_fee ?? 1000;

        return view('users.events.final_reg_form', compact('team', 'event', 'discount', 'finalAmount'));
    }
    public function updateAndPay(Request $request)
    {
        $team = Registration::findOrFail($request->team_id);
        $event = $team->event;

        // ১. ভ্যালিডেশন (শুধুমাত্র IUPC হলে রিকোয়ার্ড)
        if ($event->slug == 'iupc') {
            $request->validate([
                'coach_name' => 'required|string',
                'm1_name'    => 'required|string',
                'm1_phone'   => 'required|string',
                'm1_email'   => 'required|email',
            ]);

            // তথ্য আপডেট করা (শুধুমাত্র IUPC-এর জন্য)
            $team->update($request->except(['_token', 'team_id', 'amount']));
        }

        // ২. পেমেন্ট প্রসেস (সবার জন্য কমন)
        try {
            $registration_fee = $request->amount;
            $order_id = strtoupper($event->slug) . "-TID-" . $team->id . "-" . uniqid();

            $team->update(['order_id' => $order_id]);

            $shurjopay = new Shurjopay();
            $info = [
                'currency'         => 'BDT',
                'amount'           => $registration_fee,
                'order_id'         => $order_id,
                'client_ip'        => $request->ip(),
                'customer_name'    => $team->team_name ?? $team->m1_name,
                'customer_phone'   => $team->m1_phone,
                'customer_email'   => $team->m1_email,
                'customer_address' => $team->university_name,
                'customer_city'    => 'Gazipur',
                'return_url'       => route('iupc.payment.callback'),
                'cancel_url'       => route('iupc.payment.callback'),
            ];

            return $shurjopay->makePayment($info);
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }


    public function paymentCallback(Request $request)
    {
        $order_id = $request->order_id; // shurjoPay থেকে আসা আইডি
        $shurjopay = new Shurjopay();
        $response = $shurjopay->verifyPayment($order_id);

        if ($response[0]['sp_code'] == 1000) {
            // পেমেন্ট সফল
            $team = Registration::where('order_id', $order_id)->first();
            if ($team) {
                $team->update([
                    'payment_status' => 'paid',
                    'status'         => 'verified', // সরাসরি ফাইনাল লিস্টে চলে যাবে
                    'transaction_id' => $response[0]['bank_trx_id']
                ]);
            }
            return redirect()->route('event.admit_card', [$slug, $team->team_id])->with('success', 'Payment Successful! Download your Entry Pass.');
        } else {
            // পেমেন্ট ফেইল বা ক্যান্সেল
            return redirect()->route('event.dashboard', 'iupc')->with('error', 'Payment Failed or Cancelled.');
        }
    }



    /**
     * Selected Teams (Project Showcase/AI Hackathon এর জন্য)
     */


    private $sp_instance;

    public function __construct()
    {
        // Initialize the config manually since we are using Laravel's config helper
        $config = new ShurjopayConfig();
        $config->setMerchantUsername(config('shurjopay.username'));
        $config->setMerchantPassword(config('shurjopay.password'));
        $config->setMerchantKeyPrefix(config('shurjopay.prefix'));
        $config->setShurjopayApi(config('shurjopay.url'));
        $config->setShurjopayStatusLog(config('shurjopay.log_path'));
        $config->setCallBackUrl(config('shurjopay.callback'));

        $this->sp_instance = new Shurjopay($config);
    }

    public function pay()
    {
        $request = new PaymentRequest();
        $request->currency = 'BDT';
        $request->amount = 100;
        $request->customerName = 'John Doe';
        $request->customerPhone = '01700000000';
        $request->customerEmail = 'john@example.com';
        $request->customerAddress = 'Dhaka';
        $request->customerCity = 'Dhaka';
        // ... fill other required fields as per your documentation

        return $this->sp_instance->makePayment($request);
    }
}
