<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\Transaction;
use Exception;

use ShurjopayPlugin\Shurjopay;
use ShurjopayPlugin\PaymentRequest;

class PaymentController extends Controller
{
    protected $shurjopay;

    public function __construct(Shurjopay $shurjopay)
    {
        $this->shurjopay = $shurjopay;
    }






    // ─────────────────────────────────────────────
    // 1. IUPC — form update + pay
    // ─────────────────────────────────────────────
    public function iupc_updateAndPay(Request $request)
    {
        $team  = Registration::with('event')->findOrFail($request->team_id);
        $event = $team->event;

        $request->validate([
            'coupon_code'  => 'required|string',
            'coach_name'   => 'required|string',
            'm1_name'      => 'required|string',
            'm1_phone'     => 'required|digits:11',
            'm1_email'     => 'required|email',
        ]);

        // ✅ Coupon verify
        $coupon = \App\Models\Coupon::where('code', $request->coupon_code)
            ->where('event_id', $event->id)
            ->where('is_used', false)
            ->first();

        if (!$coupon) {
            return back()->with('error', 'Invalid or already used coupon code!');
        }

        if (strtolower($coupon->university) !== strtolower($team->university_name)) {
            return back()->with('error', 'This coupon does not belong to your university!');
        }

        $team->update($request->except(['_token', 'team_id', 'amount', 'coupon_code']));

        if ($team->payment_status === 'paid') {
            return redirect()->route('event.dashboard', 'iupc')
                ->with('info', 'পেমেন্ট আগেই সম্পন্ন হয়েছে।');
        }

        try {
            $order_id = 'IUPC-' . uniqid();

            // ✅ coupon_id save করো team এ — callback এ কাজে লাগবে
            $team->update([
                'order_id'  => $order_id,
                'coupon_id' => $coupon->id, // registrations table এ coupon_id column লাগবে
            ]);

            $paymentRequest                  = new PaymentRequest();
            $paymentRequest->currency        = 'BDT';
            $paymentRequest->amount          = $event->reg_fee;
            $paymentRequest->orderId         = $order_id;
            $paymentRequest->customerName    = $team->team_name ?? $team->m1_name;
            $paymentRequest->customerPhone   = $team->m1_phone;
            $paymentRequest->customerEmail   = $team->m1_email;
            $paymentRequest->customerAddress = $team->university_name ?? 'Gazipur';
            $paymentRequest->customerCity    = 'Gazipur';
            $paymentRequest->value1          = $team->id;
            $paymentRequest->value2          = $coupon->id; // ← callback এ is_used করতে লাগবে

            // ✅ এখানে is_used করা হচ্ছে না, callback এ হবে

            return $this->shurjopay->makePayment($paymentRequest);
        } catch (Exception $e) {
            return back()->with('error', 'পেমেন্ট গেটওয়ে এরর: ' . $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────
    // 2. Project-Showcase / AI-Hackathon — direct pay
    // ─────────────────────────────────────────────
    public function finalRegDirectPay($slug, $id)
    {
        $team  = Registration::with('event')->findOrFail($id);
        $event = $team->event;

        if ($team->payment_status === 'paid') {
            return redirect()->route('event.dashboard', $slug)
                ->with('info', 'পেমেন্ট আগেই সম্পন্ন হয়েছে।');
        }

        try {
            $phone = preg_replace('/[^0-9]/', '', $team->m1_phone);
            if (strlen($phone) > 11 && str_starts_with($phone, '88')) {
                $phone = substr($phone, 2);
            }

            $order_id = strtoupper($slug) . '-FIN-' . $team->id . '-' . uniqid();
            $team->update(['order_id' => $order_id]);

            $paymentRequest                  = new PaymentRequest();
            $paymentRequest->currency        = 'BDT';
            $paymentRequest->amount          = $event->reg_fee;
            $paymentRequest->orderId         = $order_id;
            $paymentRequest->customerName    = $team->team_name ?? $team->m1_name;
            $paymentRequest->customerPhone   = $phone;
            $paymentRequest->customerEmail   = $team->m1_email;
            $paymentRequest->customerAddress = $team->university_name ?? 'Gazipur';
            $paymentRequest->customerCity    = 'Gazipur';
            $paymentRequest->value1          = $team->id;   // ← callback এ লাগবে

            return $this->shurjopay->makePayment($paymentRequest);
        } catch (Exception $e) {
            $msg = $e->getMessage() ?: 'পেমেন্ট গেটওয়েতে টেকনিক্যাল সমস্যা হয়েছে।';
            return back()->with('error', 'পেমেন্ট এরর: ' . $msg);
        }
    }

    // ─────────────────────────────────────────────
    // 3. ICT-Olympiad & অন্যান্য — single registration pay
    // ─────────────────────────────────────────────
    public function makePayment($registration_id)
    {
        $registration = Registration::with('event')->findOrFail($registration_id);

        if ($registration->payment_status === 'paid') {
            return redirect()->route('event.dashboard', $registration->event->slug)
                ->with('info', 'পেমেন্ট আগেই সম্পন্ন হয়েছে।');
        }

        try {
            $order_id = 'ICT-' . uniqid();
            $registration->update(['order_id' => $order_id]);

            $paymentRequest                  = new PaymentRequest();
            $paymentRequest->currency        = 'BDT';
            $paymentRequest->amount          = $registration->event->reg_fee;
            $paymentRequest->orderId         = $order_id;
            $paymentRequest->customerName    = $registration->m1_name;
            $paymentRequest->customerPhone   = $registration->m1_phone;
            $paymentRequest->customerEmail   = $registration->m1_email;
            $paymentRequest->customerAddress = 'DUET, Gazipur';
            $paymentRequest->customerCity    = 'Gazipur';
            $paymentRequest->value1          = $registration->id;  // ← callback এ লাগবে

            return $this->shurjopay->makePayment($paymentRequest);
        } catch (Exception $e) {
            return back()->with('error', 'Payment Gateway Error: ' . $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────
    // 4. SINGLE CALLBACK — সব event এর জন্য একটাই
    //    কিন্তু slug দিয়ে view আলাদা হবে
    // ─────────────────────────────────────────────
    public function callback(Request $request)
    {
        $order_id = $request->order_id;

        try {
            $verification = $this->shurjopay->verifyPayment($order_id);
            $data         = $verification[0];

            // ─── Payment FAILED / Cancelled ───────────────────
            if ($data->sp_code !== '1000') {
                // order_id থেকে registration খোঁজার চেষ্টা
                $registration = Registration::where('order_id', $order_id)->first();
                $slug         = optional($registration?->event)->slug ?? 'event';

                return view('payment.failed', [
                    'registration'   => $registration,
                    'payment_status' => 'failed',
                    'sp_code'        => $data->sp_code ?? 'N/A',
                    'message'        => 'পেমেন্ট সফল হয়নি। আবার চেষ্টা করুন।',
                    'slug'           => $slug,
                ]);
            }

            // ─── Payment SUCCESS ──────────────────────────────
            $registration = Registration::with('event')->where('id', $data->value1)->first();

            if (! $registration) {
                return view('payment.failed', [
                    'registration'   => null,
                    'payment_status' => 'error',
                    'message'        => 'Registration খুঁজে পাওয়া যায়নি।',
                    'slug'           => 'event',
                ]);
            }

            $slug = $registration->event->slug;

            // Already paid হলে duplicate invoice দেওয়া
            if ($registration->payment_status === 'paid') {
                $transaction = Transaction::where('team_id', $registration->id)->latest()->first();
                return $this->invoiceView($slug, $registration, $transaction, 'already_paid');
            }

            // ─── Participant ID generate ──────────────────────
            $prefix      = $this->getParticipantPrefix($slug);
            $last        = Registration::whereNotNull('participant_id')
                ->where('participant_id', 'like', $prefix . '%')
                ->orderBy('id', 'desc')
                ->first();

            if ($last) {
                $number = (int) str_replace($prefix, '', $last->participant_id);
                $newId  = $prefix . str_pad($number + 1, 2, '0', STR_PAD_LEFT);
            } else {
                $newId = $prefix . '01';
            }

            // ─── Transaction save ─────────────────────────────
            $transaction = Transaction::create([
                'transaction_id' => $data->bank_trx_id,
                'event_id'       => $registration->event_id,
                'team_id'        => $registration->id,
                'student_id'     => $registration->student_id ?? null,
                'amount'         => $data->amount,
                'currency'       => $data->currency,
                'status'         => 'Successful',
                'payment_method' => $data->method,
            ]);

            // ─── Registration update ──────────────────────────
            $registration->update([
                'participant_id' => $newId,
                'transaction_id' => $data->bank_trx_id,
                'payment_status' => 'paid',
                'status'         => 'verified',
            ]);

            return $this->invoiceView($slug, $registration, $transaction, 'success');
        } catch (Exception $e) {
            return view('payment.failed', [
                'registration'   => null,
                'payment_status' => 'error',
                'message'        => 'সিস্টেম এরর: ' . $e->getMessage(),
                'slug'           => 'event',
            ]);
        }
    }

    // ─────────────────────────────────────────────
    // Helper: slug → participant ID prefix
    // ─────────────────────────────────────────────
    private function getParticipantPrefix(string $slug): string
    {
        return match ($slug) {
            'iupc'              => 'IUPC',
            'project-showcase'  => 'PS',
            'ai-hackathon'      => 'AI',
            'ict-olympiad'      => 'ICT',
            default             => strtoupper(str_replace(['-', '_'], '', $slug)),
        };
    }

    // ─────────────────────────────────────────────
    // Helper: slug → invoice view
    // প্রতিটি event এর জন্য আলাদা blade view থাকতে পারে,
    // না থাকলে generic invoice ব্যবহার হবে।
    // ─────────────────────────────────────────────
    private function invoiceView(string $slug, $registration, $transaction, string $status)
    {
        $viewMap = [
            'iupc'             => 'invoices.iupc',
            'project-showcase' => 'invoices.project',
            'ai-hackathon'     => 'invoices.hackathon',
            'ict-olympiad'     => 'invoices.ict',
        ];

        // event-specific view থাকলে সেটা, না হলে generic
        $view = isset($viewMap[$slug]) && view()->exists($viewMap[$slug])
            ? $viewMap[$slug]
            : 'invoices.generic';

        $message = match ($status) {
            'success'      => 'আপনার পেমেন্ট সফল হয়েছে!',
            'already_paid' => 'এই রেজিস্ট্রেশনের পেমেন্ট আগেই সম্পন্ন হয়েছে।',
            default        => '',
        };

        return view($view, [
            'registration'   => $registration->fresh(),  // updated data
            'transaction'    => $transaction,
            'payment_status' => $status,
            'message'        => $message,
            'slug'           => $slug,
        ]);
    }
}
