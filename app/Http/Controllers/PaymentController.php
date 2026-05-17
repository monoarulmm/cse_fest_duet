<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Registration;
use App\Models\Transaction;
use App\Models\Coupon;
use Exception;
use Throwable; // ✅ ADD

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
    // HELPER: Phone normalize
    // ─────────────────────────────────────────────────────────────────────────
    private function normalizePhone(?string $phone): string
    {
        if (empty($phone)) return '01700000000';

        $digits = preg_replace('/[^0-9]/', '', $phone);

        if (strlen($digits) === 13 && str_starts_with($digits, '880'))
            $digits = '0' . substr($digits, 3);
        if (strlen($digits) === 12 && str_starts_with($digits, '88'))
            $digits = '0' . substr($digits, 2);

        if (strlen($digits) !== 11 || !str_starts_with($digits, '01'))
            return '01700000000';

        return $digits;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // HELPER: Email normalize ✅ NEW
    // ─────────────────────────────────────────────────────────────────────────
    private function normalizeEmail(?string $email): string
    {
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Log::warning('Invalid email normalized to fallback', ['original' => $email]);
            return 'noreply@placeholder.com';
        }
        return $email;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // HELPER: Payment error view
    // ─────────────────────────────────────────────────────────────────────────
    private function paymentErrorView(string $message, ?Registration $registration = null, string $type = 'gateway_error')
    {
        $slug = optional($registration?->event)->slug ?? 'event';

        return view('payment.error', [
            'registration' => $registration,
            'error_type'   => $type,
            'message'      => $message,
            'slug'         => $slug,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // HELPER: Throwable → friendly message ✅ FIXED signature
    // ─────────────────────────────────────────────────────────────────────────
    private function friendlyError(Throwable $e): string
    {
        $msg = strtolower($e->getMessage() ?? '');

        if (str_contains($msg, 'phone') || str_contains($msg, 'mobile'))
            return 'ফোন নম্বর সঠিক নয়। সঠিক ১১ ডিজিটের বাংলাদেশী নম্বর দিন।';
        if (str_contains($msg, 'amount'))
            return 'পেমেন্টের পরিমাণে সমস্যা হয়েছে।';
        if (str_contains($msg, 'email'))
            return 'ইমেইল ঠিকানা সঠিক নয়। পেমেন্ট গেটওয়ে গ্রহণ করেনি।';
        if (str_contains($msg, 'curl') || str_contains($msg, 'connection') || str_contains($msg, 'timeout'))
            return 'পেমেন্ট গেটওয়েতে সংযোগ সমস্যা হয়েছে। কিছুক্ষণ পর আবার চেষ্টা করুন।';
        if (str_contains($msg, 'unauthorized') || str_contains($msg, '401'))
            return 'পেমেন্ট গেটওয়ে কনফিগারেশনে সমস্যা আছে। Admin কে জানান।';
        if (str_contains($msg, 'internal server') || str_contains($msg, '500'))
            return 'পেমেন্ট গেটওয়েতে সাময়িক সমস্যা হচ্ছে। কিছুক্ষণ পর আবার চেষ্টা করুন।';
        if (str_contains($msg, 'null') || str_contains($msg, 'getmessage'))
            return 'পেমেন্ট গেটওয়ে থেকে কোনো সাড়া পাওয়া যায়নি। কিছুক্ষণ পর আবার চেষ্টা করুন।';

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
            'coupon_code'  => 'required|string',
            'coach_name'   => 'required|string',
            'coach_email'  => 'required|email',
            'coach_phone'  => 'required|string',
            'm1_name'      => 'required|string',
            'm1_phone'     => 'required|digits:11',
            'm1_email'     => 'required|email',
            'm1_prev_ex'   => 'required|string',
            'm2_name'      => 'required|string',
            'm2_email'     => 'required|email',
            'm2_phone'     => 'required|digits:11',
            'm2_tshirt'    => 'required|string',
            'm2_prev_ex'   => 'required|string',
            'm3_name'      => 'required|string',
            'm3_email'     => 'required|email',
            'm3_phone'     => 'required|digits:11',
            'm3_tshirt'    => 'required|string',
            'team_name'    => 'required|string|max:255',
            'team_person'  => 'required|string',
            'coach_tshirt' => 'required|string',
            'm1_cf_handle' => 'required|string',
            'm2_cf_handle' => 'required|string',
            'm3_cf_handle' => 'required|string',
            'm3_prev_ex'   => 'nullable|string',
        ]);

        $coupon = Coupon::where('code', $request->coupon_code)
            ->where('event_id', $event->id)
            ->where('is_used', false)
            ->first();

        if (! $coupon)
            return back()->with('error', 'Invalid or already used coupon code!');

        if (strtolower($coupon->university) !== strtolower($team->university_name))
            return back()->with('error', 'This coupon does not belong to your university!');

        if ($team->fresh()->payment_status === 'paid')
            return redirect()->route('event.dashboard', 'iupc')->with('info', 'পেমেন্ট আগেই সম্পন্ন হয়েছে।');

        $team->update($request->except(['_token', 'team_id', 'amount', 'coupon_code']));

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
            $paymentRequest->customerName    = $team->team_name ?? $team->m1_name ?? 'Participant';
            $paymentRequest->customerPhone   = $this->normalizePhone($team->m1_phone);
            $paymentRequest->customerEmail   = $this->normalizeEmail($team->m1_email); // ✅ FIXED
            $paymentRequest->customerAddress = $team->university_name ?? 'Gazipur';
            $paymentRequest->customerCity    = 'Gazipur';
            $paymentRequest->value1          = $team->id;
            $paymentRequest->value2          = $coupon->id;

            return $this->shurjopay->makePayment($paymentRequest);
        } catch (Throwable $e) { // ✅ FIXED
            Log::error('IUPC Payment Error', [
                'team_id' => $team->id,
                'error'   => $e->getMessage() ?? 'Unknown',
                'trace'   => $e->getTraceAsString(),
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

        if ($team->fresh()->payment_status === 'paid')
            return redirect()->route('event.dashboard', $slug)->with('info', 'পেমেন্ট আগেই সম্পন্ন হয়েছে।');

        try {
            $order_id = strtoupper($slug) . '-FIN-' . $team->id . '-' . uniqid();
            $team->update(['order_id' => $order_id]);

            $paymentRequest                  = new PaymentRequest();
            $paymentRequest->currency        = 'BDT';
            $paymentRequest->amount          = $event->reg_fee;
            $paymentRequest->orderId         = $order_id;
            $paymentRequest->customerName    = $team->team_name ?? $team->m1_name ?? 'Participant';
            $paymentRequest->customerPhone   = $this->normalizePhone($team->m1_phone);
            $paymentRequest->customerEmail   = $this->normalizeEmail($team->m1_email); // ✅ FIXED
            $paymentRequest->customerAddress = $team->university_name ?? 'Gazipur';
            $paymentRequest->customerCity    = 'Gazipur';
            $paymentRequest->value1          = $team->id;

            return $this->shurjopay->makePayment($paymentRequest);
        } catch (Throwable $e) { // ✅ FIXED
            Log::error('DirectPay Error', [
                'slug'    => $slug,
                'team_id' => $team->id,
                'error'   => $e->getMessage() ?? 'Unknown',
                'trace'   => $e->getTraceAsString(),
            ]);
            return $this->paymentErrorView($this->friendlyError($e), $team, 'gateway_error');
        }
    }

    // ─────────────────────────────────────────────
    // 3. ICT-Olympiad — single pay
    // ─────────────────────────────────────────────
    public function makePayment($registration_id)
    {
        $registration = Registration::with('event')->findOrFail($registration_id);

        if ($registration->fresh()->payment_status === 'paid')
            return redirect()->route('event.dashboard', $registration->event->slug)
                ->with('info', 'পেমেন্ট আগেই সম্পন্ন হয়েছে।');

        try {
            $order_id = 'ICT-' . uniqid();
            $registration->update(['order_id' => $order_id]);

            $paymentRequest                  = new PaymentRequest();
            $paymentRequest->currency        = 'BDT';
            $paymentRequest->amount          = $registration->event->reg_fee;
            $paymentRequest->orderId         = $order_id;
            $paymentRequest->customerName    = $registration->m1_name ?? 'Participant';
            $paymentRequest->customerPhone   = $this->normalizePhone($registration->m1_phone);
            $paymentRequest->customerEmail   = $this->normalizeEmail($registration->m1_email); // ✅ FIXED
            $paymentRequest->customerAddress = 'DUET, Gazipur';
            $paymentRequest->customerCity    = 'Gazipur';
            $paymentRequest->value1          = $registration->id;

            return $this->shurjopay->makePayment($paymentRequest);
        } catch (Throwable $e) { // ✅ FIXED
            Log::error('ICT Payment Error', [
                'registration_id' => $registration_id,
                'error'           => $e->getMessage() ?? 'Unknown',
                'trace'           => $e->getTraceAsString(),
            ]);
            return $this->paymentErrorView($this->friendlyError($e), $registration, 'gateway_error');
        }
    }

    // ─────────────────────────────────────────────
    // 4. CALLBACK — সব event এর জন্য
    // ─────────────────────────────────────────────
    public function callback(Request $request)
    {
        $order_id = $request->order_id;

        try {
            $verification = $this->shurjopay->verifyPayment($order_id);

            // ✅ FIXED: empty response crash এড়ানো
            if (empty($verification) || !isset($verification[0])) {
                Log::error('Payment verification returned empty', ['order_id' => $order_id]);
                return view('payment.failed', [
                    'registration'   => null,
                    'payment_status' => 'error',
                    'message'        => 'পেমেন্ট যাচাই করা সম্ভব হয়নি। আমাদের সাথে যোগাযোগ করুন।',
                    'slug'           => 'event',
                ]);
            }

            $data   = $verification[0];
            $spCode = $data->sp_code ?? null; // ✅ FIXED: null-safe

            // ── FAILED ────────────────────────────────────────────────────────
            if ($spCode !== '1000') {
                $registration = Registration::where('order_id', $order_id)->first();
                $slug         = optional($registration?->event)->slug ?? 'event';

                return view('payment.failed', [
                    'registration'   => $registration,
                    'payment_status' => 'failed',
                    'sp_code'        => $spCode ?? 'N/A',
                    'message'        => 'পেমেন্ট সফল হয়নি। আবার চেষ্টা করুন।',
                    'slug'           => $slug,
                ]);
            }

            // ── SUCCESS ───────────────────────────────────────────────────────
            $registration = Registration::with('event')
                ->where('id', $data->value1 ?? null)
                ->first();

            if (! $registration) {
                return view('payment.failed', [
                    'registration'   => null,
                    'payment_status' => 'error',
                    'message'        => 'Registration খুঁজে পাওয়া যায়নি।',
                    'slug'           => 'event',
                ]);
            }

            $slug = $registration->event->slug;

            // ── Already PAID ──────────────────────────────────────────────────
            if ($registration->payment_status === 'paid') {
                $transaction = Transaction::where('team_id', $registration->id)->latest()->first();
                return $this->invoiceView($slug, $registration, $transaction, 'already_paid');
            }

            // ── DB Transaction ────────────────────────────────────────────────
            $registration = DB::transaction(function () use ($data, $registration, $slug) {

                $locked = Registration::with('event')
                    ->where('id', $registration->id)
                    ->lockForUpdate()
                    ->first();

                if ($locked->payment_status === 'paid') return $locked;

                $prefix = $this->getParticipantPrefix($slug);
                $last   = Registration::whereNotNull('participant_id')
                    ->where('participant_id', 'like', $prefix . '%')
                    ->orderBy('id', 'desc')
                    ->lockForUpdate()
                    ->first();

                $newId = $last
                    ? $prefix . str_pad((int) str_replace($prefix, '', $last->participant_id) + 1, 2, '0', STR_PAD_LEFT)
                    : $prefix . '01';

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

                $couponId = $locked->coupon_id ?? ($data->value2 ?? null);
                if ($couponId) {
                    $updated = Coupon::where('id', $couponId)
                        ->where('is_used', false)
                        ->update(['is_used' => true]);

                    if (! $updated) {
                        Log::warning('Coupon already used or not found', [
                            'coupon_id' => $couponId,
                            'team_id'   => $locked->id,
                        ]);
                    }
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
            $invoiceType = ($registration->fresh()->payment_status === 'paid') ? 'success' : 'already_paid';

            return $this->invoiceView($slug, $registration, $transaction, $invoiceType);
        } catch (Throwable $e) { // ✅ FIXED
            Log::error('Payment callback error', [
                'order_id' => $order_id ?? null,
                'error'    => $e->getMessage() ?? 'Unknown',
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
    // Helpers
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
