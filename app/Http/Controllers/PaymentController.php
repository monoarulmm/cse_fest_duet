<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

    // ─────────────────────────────────────────────────────────────────────────
    // HELPER: Phone number normalize করে valid 11-digit BD number বানানো
    // ShurjoPay শুধু 01XXXXXXXXX format চায়
    // ─────────────────────────────────────────────────────────────────────────
    private function normalizePhone(?string $phone): string
    {
        if (empty($phone)) {
            return '01700000000'; // fallback — ShurjoPay requires a phone
        }

        // শুধু digits রাখো
        $digits = preg_replace('/[^0-9]/', '', $phone);

        // +8801... বা 8801... → 01...
        if (strlen($digits) === 13 && str_starts_with($digits, '880')) {
            $digits = '0' . substr($digits, 3);
        }
        if (strlen($digits) === 12 && str_starts_with($digits, '88')) {
            $digits = '0' . substr($digits, 2);
        }

        // যদি তারপরও 11 digit না হয়, fallback
        if (strlen($digits) !== 11 || !str_starts_with($digits, '01')) {
            return '01700000000';
        }

        return $digits;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // HELPER: যেকোনো payment error কে professional view এ পাঠানো
    // ─────────────────────────────────────────────────────────────────────────
    private function paymentErrorView(string $message, ?Registration $registration = null, string $type = 'gateway_error')
    {
        $slug = optional($registration?->event)->slug ?? 'event';

        return view('payment.error', [
            'registration'   => $registration,
            'error_type'     => $type,
            'message'        => $message,
            'slug'           => $slug,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // HELPER: ShurjoPay exception message কে friendly বাংলায় convert
    // ─────────────────────────────────────────────────────────────────────────
    private function friendlyError(Exception $e): string
    {
        $msg = strtolower($e->getMessage());

        if (str_contains($msg, 'phone') || str_contains($msg, 'mobile')) {
            return 'ফোন নম্বর সঠিক নয়। সঠিক ১১ ডিজিটের বাংলাদেশী নম্বর দিন।';
        }
        if (str_contains($msg, 'amount')) {
            return 'পেমেন্টের পরিমাণে সমস্যা হয়েছে।';
        }
        if (str_contains($msg, 'email')) {
            return 'ইমেইল ঠিকানা সঠিক নয়।';
        }
        if (str_contains($msg, 'curl') || str_contains($msg, 'connection') || str_contains($msg, 'timeout')) {
            return 'পেমেন্ট গেটওয়েতে সংযোগ সমস্যা হয়েছে। কিছুক্ষণ পর আবার চেষ্টা করুন।';
        }
        if (str_contains($msg, 'unauthorized') || str_contains($msg, '401')) {
            return 'পেমেন্ট গেটওয়ে কনফিগারেশনে সমস্যা আছে। Admin কে জানান।';
        }
        if (str_contains($msg, 'internal server') || str_contains($msg, '500')) {
            return 'পেমেন্ট গেটওয়েতে সাময়িক সমস্যা হচ্ছে। কিছুক্ষণ পর আবার চেষ্টা করুন।';
        }

        return 'পেমেন্ট গেটওয়েতে সমস্যা হয়েছে। আবার চেষ্টা করুন।';
    }


    // ─────────────────────────────────────────────
    // 1. IUPC — form update + pay
    // ─────────────────────────────────────────────
    public function iupc_updateAndPay(Request $request)
    {
        $team  = Registration::with('event')->findOrFail($request->team_id);
        $event = $team->event;

        $request->validate([
            'coupon_code' => 'required|string',
            'coach_name'  => 'required|string',
            'm1_name'     => 'required|string',
            'm1_phone'    => 'required|digits:11',
            'm1_email'    => 'required|email',
        ]);

        // Coupon verify
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

            $team->update([
                'order_id'  => $order_id,
                'coupon_id' => $coupon->id,
            ]);

            $paymentRequest                  = new PaymentRequest();
            $paymentRequest->currency        = 'BDT';
            $paymentRequest->amount          = $event->reg_fee;
            $paymentRequest->orderId         = $order_id;
            $paymentRequest->customerName    = $team->team_name ?? $team->m1_name;
            $paymentRequest->customerPhone   = $this->normalizePhone($team->m1_phone); // ✅ Normalized
            $paymentRequest->customerEmail   = $team->m1_email;
            $paymentRequest->customerAddress = $team->university_name ?? 'Gazipur';
            $paymentRequest->customerCity    = 'Gazipur';
            $paymentRequest->value1          = $team->id;
            $paymentRequest->value2          = $coupon->id;

            return $this->shurjopay->makePayment($paymentRequest);
        } catch (Exception $e) {
            Log::error('IUPC Payment Error', [
                'team_id' => $team->id,
                'error'   => $e->getMessage(),
            ]);

            return $this->paymentErrorView($this->friendlyError($e), $team, 'gateway_error');
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
            $order_id = strtoupper($slug) . '-FIN-' . $team->id . '-' . uniqid();
            $team->update(['order_id' => $order_id]);

            $paymentRequest                  = new PaymentRequest();
            $paymentRequest->currency        = 'BDT';
            $paymentRequest->amount          = $event->reg_fee;
            $paymentRequest->orderId         = $order_id;
            $paymentRequest->customerName    = $team->team_name ?? $team->m1_name;
            $paymentRequest->customerPhone   = $this->normalizePhone($team->m1_phone); // ✅ Normalized
            $paymentRequest->customerEmail   = $team->m1_email;
            $paymentRequest->customerAddress = $team->university_name ?? 'Gazipur';
            $paymentRequest->customerCity    = 'Gazipur';
            $paymentRequest->value1          = $team->id;

            return $this->shurjopay->makePayment($paymentRequest);
        } catch (Exception $e) {
            Log::error('DirectPay Error', [
                'slug'    => $slug,
                'team_id' => $team->id,
                'error'   => $e->getMessage(),
            ]);

            return $this->paymentErrorView($this->friendlyError($e), $team, 'gateway_error');
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
            $paymentRequest->customerPhone   = $this->normalizePhone($registration->m1_phone); // ✅ Normalized
            $paymentRequest->customerEmail   = $registration->m1_email;
            $paymentRequest->customerAddress = 'DUET, Gazipur';
            $paymentRequest->customerCity    = 'Gazipur';
            $paymentRequest->value1          = $registration->id;

            return $this->shurjopay->makePayment($paymentRequest);
        } catch (Exception $e) {
            Log::error('ICT Payment Error', [
                'registration_id' => $registration_id,
                'error'           => $e->getMessage(),
            ]);

            return $this->paymentErrorView($this->friendlyError($e), $registration, 'gateway_error');
        }
    }

    // ─────────────────────────────────────────────
    // 4. SINGLE CALLBACK — সব event এর জন্য একটাই
    // ─────────────────────────────────────────────
    public function callback(Request $request)
    {
        $order_id = $request->order_id;

        try {
            $verification = $this->shurjopay->verifyPayment($order_id);
            $data         = $verification[0];

            // ─── Payment FAILED / Cancelled ───────────────────
            if ($data->sp_code !== '1000') {
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

            // ─── Already PAID — Idempotency Check ─────────────
            if ($registration->payment_status === 'paid') {
                $transaction = Transaction::where('team_id', $registration->id)->latest()->first();
                return $this->invoiceView($slug, $registration, $transaction, 'already_paid');
            }

            // ─── DB Lock — Race Condition Protection ──────────
            $registration = DB::transaction(function () use ($data, $registration, $slug) {

                $locked = Registration::with('event')
                    ->where('id', $registration->id)
                    ->lockForUpdate()
                    ->first();

                if ($locked->payment_status === 'paid') {
                    return $locked;
                }

                // Participant ID Generate
                $prefix = $this->getParticipantPrefix($slug);
                $last   = Registration::whereNotNull('participant_id')
                    ->where('participant_id', 'like', $prefix . '%')
                    ->orderBy('id', 'desc')
                    ->lockForUpdate()
                    ->first();

                if ($last) {
                    $number = (int) str_replace($prefix, '', $last->participant_id);
                    $newId  = $prefix . str_pad($number + 1, 2, '0', STR_PAD_LEFT);
                } else {
                    $newId = $prefix . '01';
                }

                // Transaction Save (idempotent)
                Transaction::firstOrCreate(
                    ['transaction_id' => $data->bank_trx_id],
                    [
                        'event_id'       => $locked->event_id,
                        'team_id'        => $locked->id,
                        'student_id'     => $locked->student_id ?? null,
                        'amount'         => $data->amount,
                        'currency'       => $data->currency,
                        'status'         => 'Successful',
                        'payment_method' => $data->method,
                    ]
                );

                // Coupon mark as used (IUPC এর জন্য)
                if ($locked->coupon_id) {
                    \App\Models\Coupon::where('id', $locked->coupon_id)
                        ->update(['is_used' => true]);
                }

                $locked->update([
                    'participant_id' => $newId,
                    'transaction_id' => $data->bank_trx_id,
                    'payment_status' => 'paid',
                    'status'         => 'verified',
                ]);

                return $locked->fresh('event');
            });

            $transaction = Transaction::where('team_id', $registration->id)->latest()->first();
            $invoiceType = $registration->wasChanged() ? 'success' : 'already_paid';

            return $this->invoiceView($slug, $registration, $transaction, $invoiceType);
        } catch (Exception $e) {
            Log::error('Payment callback error', [
                'order_id' => $order_id ?? null,
                'error'    => $e->getMessage(),
                'trace'    => $e->getTraceAsString(),
            ]);

            return view('payment.failed', [
                'registration'   => null,
                'payment_status' => 'error',
                'message'        => 'সিস্টেম এরর হয়েছে। আমাদের সাথে যোগাযোগ করুন।',
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
            'iupc'             => 'IUPC',
            'project-showcase' => 'PS',
            'ai-hackathon'     => 'AI',
            'ict-olympiad'     => 'ICT',
            default            => strtoupper(str_replace(['-', '_'], '', $slug)),
        };
    }

    // ─────────────────────────────────────────────
    // Helper: slug → invoice view
    // ─────────────────────────────────────────────
    private function invoiceView(string $slug, $registration, $transaction, string $status)
    {
        $viewMap = [
            'iupc'             => 'invoices.iupc',
            'project-showcase' => 'invoices.project',
            'ai-hackathon'     => 'invoices.hackathon',
            'ict-olympiad'     => 'invoices.ict',
        ];

        $view = isset($viewMap[$slug]) && view()->exists($viewMap[$slug])
            ? $viewMap[$slug]
            : 'invoices.generic';

        $message = match ($status) {
            'success'      => 'আপনার পেমেন্ট সফল হয়েছে!',
            'already_paid' => 'এই রেজিস্ট্রেশনের পেমেন্ট আগেই সম্পন্ন হয়েছে।',
            default        => '',
        };

        return view($view, [
            'registration'   => $registration->fresh(),
            'transaction'    => $transaction,
            'payment_status' => $status,
            'message'        => $message,
            'slug'           => $slug,
        ]);
    }
}
