<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Event;
use Illuminate\Http\Request;
use ShurjopayPlugin\Shurjopay;

class IupcController extends Controller
{



    // ১. টিম লিস্ট এবং সার্চ
    public function teamList(Request $request)
    {
        $query = Registration::query();

        if ($request->has('search')) {
            $query->where('team_name', 'LIKE', '%' . $request->search . '%')
                ->orWhere('university_name', 'LIKE', '%' . $request->search . '%');
        }

        $teams = $query->where('status', '!=', 'paid')->get(); // যারা এখনো পেমেন্ট করেনি
        return view('users.iupc.team_list', compact('teams'));
    }


    public function showFinalForm($slug)
    {
        // ১. স্লাগ দিয়ে টিম এবং ইভেন্ট রিলেশন লোড করা
        $team = Registration::with('event')->where('slug', $slug)->firstOrFail();

        // ২. অরিজিনাল ফি
        $originalFee = $team->event->reg_fee;
        $discount = 0;

        // ৩. যেহেতু আপনার কুপন মডেল নেই, এখানে সরাসরি চেক করবেন কুপন আছে কি না


        $finalAmount = $originalFee - $discount;

        return view('users.iupc.final_reg', compact('team', 'discount', 'finalAmount'));
    }


    // ২. কুপন ভেরিফাই করে এডিট পেজে পাঠানো
    /**
     * ২. কুপন ভেরিফাই করে এডিট পেজে পাঠানো
     */
    public function verifyCoupon(Request $request)
    {
        $request->validate([
            'team_name' => 'required',
            'coupon_code' => 'required'
        ]);

        // টিম এবং কুপন কোড দিয়ে ডাটা খুঁজে বের করা
        $team = Registration::with('event')
            ->where('team_name', $request->team_name)
            ->where('coupon_code', $request->coupon_code)
            ->first();

        if (!$team) {
            return back()->with('error', 'Invalid Team Name or Coupon Code!');
        }

        // ইভেন্ট অনুযায়ী অরিজিনাল ফি
        $originalFee = $team->event->reg_fee;

        // কুপন ডিসকাউন্ট লজিক (এখানে আপনার প্রয়োজন মতো অ্যামাউন্ট বসান)
        $discount = 0;
        $finalAmount = $originalFee - $discount;

        return view('users.iupc.final_reg', compact('team', 'discount', 'finalAmount'));
    }

    /**
     * ৩. তথ্য আপডেট এবং পেমেন্ট গেটওয়েতে পাঠানো
     */
    public function updateAndPay(Request $request)
    {
        $request->validate([
            'team_id'           => 'required|exists:iupc_registrations,id',
            'coach_name'        => 'required|string|max:255',
            'coach_designation' => 'required|string|max:255',
            'coach_email'       => 'required|email',
            'm1_name'           => 'required|string',
            'm1_phone'          => 'required|string',
            'm1_email'          => 'required|email',
            'amount'            => 'required|numeric',
        ]);

        try {
            $team = Registration::findOrFail($request->team_id);

            // ১. তথ্য আপডেট করা
            $team->update($request->except(['_token', 'team_id']));

            // ২. ইউনিক অর্ডার আইডি তৈরি (Team ID + Unique String)
            $order_id = "IUPC26-TID-" . $team->id . "-" . uniqid();
            $team->update(['order_id' => $order_id]);

            // ৩. shurjoPay পেমেন্ট ইনিশিয়েট করা
            $shurjopay = new Shurjopay();
            $info = [
                'currency'         => 'BDT',
                'amount'           => $request->amount,
                'order_id'         => $order_id,
                'client_ip'        => $request->ip(),
                'customer_name'    => $team->team_name,
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

    /**
     * ৪. পেমেন্ট কলব্যাক (অটো ভেরিফিকেশন ও আইডি জেনারেশন)
     */
    public function paymentCallback(Request $request)
    {
        $order_id = $request->order_id;

        if (!$order_id) {
            return redirect()->route('home')->with('error', 'Invalid Transaction.');
        }

        $shurjopay = new Shurjopay();
        $payment_data = $shurjopay->verifyPayment($order_id);

        // sp_code 1000 মানে পেমেন্ট সফল
        if (isset($payment_data[0]['sp_code']) && $payment_data[0]['sp_code'] == '1000') {

            $team = Registration::where('order_id', $order_id)->first();

            if ($team && $team->status !== 'verified') {

                // ৫. Participant ID জেনারেশন (যেমন: IUPC-26-001)
                $count = Registration::where('status', 'verified')->count() + 1;
                $p_id = "IUPC-26-" . str_pad($count, 3, '0', STR_PAD_LEFT);

                // ৬. ফাইনাল ডাটাবেস আপডেট
                $team->update([
                    'status'         => 'verified',
                    'payment_status' => 'paid',
                    'transaction_id' => $payment_data[0]['bank_trx_id'],
                    'amount'         => $payment_data[0]['amount'],
                    'participant_id' => $p_id,
                ]);
            }

            return redirect()->route('iupc.success_page')->with('msg', 'Registration Confirmed! Your ID: ' . $p_id);
        }

        return redirect('/') // পেমেন্ট ফেইল করলে যেখানে পাঠাতে চান
            ->with('error', 'Payment Failed. Please try again.');
    }
}
