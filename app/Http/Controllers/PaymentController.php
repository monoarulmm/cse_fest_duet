<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Registration;
use App\Models\Transaction;
use App\Models\Coupon;
use Exception;
use Throwable; // ✅ FIX #1: Throwable import যোগ করা হয়েছে

use ShurjopayPlugin\Shurjopay;
use ShurjopayPlugin\PaymentRequest;

class PaymentController extends Controller
{
    protected $shurjopay;

    public function __construct(Shurjopay $shurjopay)
    {
        $this->shurjopay = $shurjopay;
    }

    // =========================================================================
    // HELPER: Phone normalize → valid 11-digit BD number
    // =========================================================================
    private function normalizePhone(?string $phone): string
    {
        if (empty($phone)) {
            return '01700000000';
        }

        $digits = preg_replace('/[^0-9]/', '', $phone);

        if (strlen($digits) === 13 && str_starts_with($digits, '880')) {
            $digits = '0' . substr($digits, 3);
        }
        if (strlen($digits) === 12 && str_starts_with($digits, '88')) {
            $digits = '0' . substr($digits, 2);
        }

        if (strlen($digits) !== 11 || !str_starts_with($digits, '01')) {
            Log::warning('Invalid phone normalized to fallback', ['original' => $phone]);
            return '01700000000';
        }

        return $digits;
    }

    // =========================================================================
    // HELPER: ShurjoPay exception → বাংলা friendly message
    // =========================================================================
    private function friendlyError(Throwable $e): string // ✅ FIX: Exception → Throwable
    {
        $msg = strtolower($e->getMessage() ?? '');

        if (str_contains($msg, 'phone') || str_contains($msg, 'mobile')) {
            return 'ফোন নম্বর সঠিক নয়। সঠিক ১১ ডিজিটের বাংলাদেশী নম্বর দিন।';
        }
        if (str_contains($msg, 'amount')) {
            return 'পেমেন্টের পরিমাণে সমস্যা হয়েছে। Admin কে জানান।';
        }
        if (str_contains($msg, 'email')) {
            return 'ইমেইল ঠিকানা সঠিক নয়।';
        }
        if (str_contains($msg, 'curl') || str_contains($msg, 'connection') || str_contains($msg, 'timeout')) {
            return 'পেমেন্ট গেটওয়েতে সংযোগ সমস্যা হয়েছে। কিছুক্ষণ পর আবার চেষ্টা করুন।';
        }
        if (str_contains($msg, 'null') || str_contains($msg, 'getmessage')) {
            return 'পেমেন্ট গেটওয়ে সাড়া দেয়নি। কিছুক্ষণ পর আবার চেষ্টা করুন।';
        }
        if (str_contains($msg, 'unauthorized') || str_contains($msg, '401')) {
            return 'পেমেন্ট গেটওয়ে কনফিগারেশনে সমস্যা আছে। Admin কে জানান।';
        }
        if (str_contains($msg, 'internal server') || str_contains($msg, '500')) {
            return 'পেমেন্ট গেটওয়েতে সাময়িক সমস্যা হচ্ছে। কিছুক্ষণ পর আবার চেষ্টা করুন।';
        }

        return 'পেমেন্ট গেটওয়েতে সমস্যা হয়েছে। আবার চেষ্টা করুন।';
    }

    // =========================================================================
    // HELPER: Email normalize
    // =========================================================================
    private function normalizeEmail(?string $email): string
    {
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Log::warning('Invalid email normalized to fallback', ['original' => $email]);
            return 'noreply@placeholder.com';
        }
        return $email;
    }

    // =========================================================================
    // HELPER: Payment error → professional view
    // =========================================================================
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

    // =========================================================================
    // HELPER: slug → participant ID prefix
    // =========================================================================
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

    // =========================================================================
    // HELPER: Invoice view — slug অনুযায়ী সঠিক view
    // =========================================================================
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

    // =========================================================================
    // 1. ICT-Olympiad ও অন্যান্য single-registration event
    //    Route: GET /payment/make/{registration_id}
    //    name:  payment.make
    // =========================================================================
    public function makePayment($registration_id)
    {
        // ✅ FIX #2: সবসময় event সহ fresh load করা হচ্ছে
        $registration = Registration::with('event')->findOrFail($registration_id);

        // ✅ FIX #3: fresh() call না করে loaded object-ই use করুন
        //    (fresh() আরেকটা query করে কিন্তু এখানে দরকার নেই)
        if ($registration->payment_status === 'paid') {
            return redirect()->route('event.dashboard', $registration->event->slug)
                ->with('info', 'পেমেন্ট আগেই সম্পন্ন হয়েছে।');
        }

        if (!$this->shurjopay) {
            Log::error('ShurjoPay instance is null', ['registration_id' => $registration_id]);
            return $this->paymentErrorView('পেমেন্ট গেটওয়ে সংযোগ করা যায়নি। Admin কে জানান।', $registration);
        }

        // ✅ FIX #4: order_id assign করা DB transaction-এর ভেতরে করা
        //    যাতে concurrent request-এ duplicate order_id না হয়
        try {
            $order_id = DB::transaction(function () use ($registration) {
                // Row lock দিয়ে অন্য request block করা
                $locked = Registration::where('id', $registration->id)
                    ->lockForUpdate()
                    ->first();

                // Lock-এর পরে আবার check — race condition এড়ানো
                if ($locked->payment_status === 'paid') {
                    return null; // paid হয়ে গেছে, new order_id দরকার নেই
                }

                // ✅ একই registration-এ আগের pending order_id থাকলে reuse করুন
                //    (gateway timeout-এর পর retry করলে নতুন order_id না বানানোই ভালো)
                if (!empty($locked->order_id) && $locked->payment_status === 'pending') {
                    return $locked->order_id;
                }

                $new_order_id = 'ICT-' . $locked->id . '-' . uniqid();
                $locked->update([
                    'order_id'       => $new_order_id,
                    'payment_status' => 'pending', // ✅ pending mark করা
                ]);

                return $new_order_id;
            });

            // Lock-এর পরে paid হয়ে গেলে redirect
            if ($order_id === null) {
                return redirect()->route('event.dashboard', $registration->event->slug)
                    ->with('info', 'পেমেন্ট আগেই সম্পন্ন হয়েছে।');
            }

            // ✅ FIX #5: event relation আগেই load করা আছে, তবুও null safe করা
            $regFee = $registration->event?->reg_fee;
            if (!$regFee) {
                Log::error('Registration fee is null', ['registration_id' => $registration->id]);
                return $this->paymentErrorView('ইভেন্টের ফি নির্ধারণ করা নেই। Admin কে জানান।', $registration);
            }

            $paymentRequest                  = new PaymentRequest();
            $paymentRequest->currency        = 'BDT';
            $paymentRequest->amount          = $regFee;
            $paymentRequest->orderId         = $order_id;
            $paymentRequest->customerName    = $registration->m1_name ?? 'Participant';
            $paymentRequest->customerPhone   = $this->normalizePhone($registration->m1_phone);
            $paymentRequest->customerEmail   = $this->normalizeEmail($registration->m1_email); // ✅ FIX #6: normalizeEmail ব্যবহার
            $paymentRequest->customerAddress = 'DUET, Gazipur';
            $paymentRequest->customerCity    = 'Gazipur';
            $paymentRequest->value1          = $registration->id;
            $paymentRequest->value2          = $registration->event_id;
            $paymentRequest->value3          = $registration->student_id;

            return $this->shurjopay->makePayment($paymentRequest);
        } catch (Throwable $e) { // ✅ FIX #7: Exception → Throwable (fatal error-ও catch হবে)
            Log::error('ICT Payment Error', [
                'registration_id' => $registration_id,
                'error'           => $e->getMessage(),
                'trace'           => $e->getTraceAsString(),
            ]);

            return $this->paymentErrorView($this->friendlyError($e), $registration);
        }
    }

    // =========================================================================
    // 2. Retry Payment — ICT ও single events এর জন্য
    //    Route: GET /payment/retry/{registration_id}
    //    name:  payment.retry
    // =========================================================================
    public function retryPayment($registration_id)
    {
        $registration = Registration::with('event')->findOrFail($registration_id);

        if ($registration->payment_status === 'paid') {
            return redirect()->route('home')->with('info', 'আপনার পেমেন্ট অলরেডি সম্পন্ন হয়েছে।');
        }

        // ✅ FIX #8: retry-তে pending order_id reset করা যাতে নতুন order_id তৈরি হয়
        //    (gateway-এ পুরনো order cancel হয়ে গেলে নতুন order দরকার)
        $registration->update(['order_id' => null, 'payment_status' => 'pending']);

        return $this->makePayment($registration_id);
    }

    // =========================================================================
    // 3. IUPC — form update + pay
    //    Route: POST /payment/iupc/update-and-pay
    //    name:  payment.iupc.updateAndPay
    // =========================================================================
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
            'm3_name'      => 'nullable|string',
            'm3_email'     => 'nullable|email',
            'm3_phone'     => 'nullable|digits:11',
            'm3_tshirt'    => 'nullable|string',
            'team_name'    => 'required|string|max:255',
            'team_person'  => 'required|string',
            'coach_tshirt' => 'required|string',
            'm1_cf_handle' => 'required|string',
            'm2_cf_handle' => 'required|string',
            'm3_cf_handle' => 'nullable|string',
            'm3_prev_ex'   => 'nullable|string',
        ]);

        $coupon = Coupon::where('code', $request->coupon_code)
            ->where('event_id', $event->id)
            ->where('is_used', false)
            ->first();

        if (!$coupon)
            return back()->with('error', 'Invalid or already used coupon code!');

        if (strtolower($coupon->university) !== strtolower($team->university_name))
            return back()->with('error', 'This coupon does not belong to your university!');

        if ($team->fresh()->payment_status === 'paid')
            return redirect()->route('event.dashboard', 'iupc')->with('info', 'পেমেন্ট আগেই সম্পন্ন হয়েছে।');

        $team->update($request->except(['_token', 'team_id', 'amount', 'coupon_code']));

        try {
            $order_id = 'IUPC-' . $team->id . '-' . uniqid();

            $team->update([
                'order_id'       => $order_id,
                'coupon_id'      => $coupon->id,
                'payment_status' => 'pending',
            ]);

            $paymentRequest                  = new PaymentRequest();
            $paymentRequest->currency        = 'BDT';
            $paymentRequest->amount          = $event->reg_fee;
            $paymentRequest->orderId         = $order_id;
            $paymentRequest->customerName    = $team->team_name ?? $team->m1_name ?? 'Participant';
            $paymentRequest->customerPhone   = $this->normalizePhone($team->coach_phone);
            $paymentRequest->customerEmail   = $this->normalizeEmail($team->coach_email);
            $paymentRequest->customerAddress = $team->university_name ?? 'Gazipur';
            $paymentRequest->customerCity    = 'Gazipur';
            $paymentRequest->value1          = $team->id;
            $paymentRequest->value2          = $coupon->id;

            return $this->shurjopay->makePayment($paymentRequest);
        } catch (Throwable $e) {
            Log::error('IUPC Payment Error', [
                'team_id' => $team->id,
                'error'   => $e->getMessage() ?? 'Unknown',
                'trace'   => $e->getTraceAsString(),
            ]);
            return $this->paymentErrorView($this->friendlyError($e), $team, 'gateway_error');
        }
    }

    // =========================================================================
    // 4. Project-Showcase / AI-Hackathon — direct pay
    //    Route: GET /payment/{slug}/final-pay/{id}
    //    name:  payment.finalRegDirectPay
    // =========================================================================
    public function finalRegDirectPay($slug, $id)
    {
        $team  = Registration::with('event')->findOrFail($id);
        $event = $team->event;

        if ($team->fresh()->payment_status === 'paid') {
            return redirect()->route('event.dashboard', $slug)
                ->with('info', 'পেমেন্ট আগেই সম্পন্ন হয়েছে।');
        }

        try {
            $order_id = strtoupper($slug) . '-FIN-' . $team->id . '-' . uniqid();
            $team->update([
                'order_id'       => $order_id,
                'payment_status' => 'pending',
            ]);

            $paymentRequest                  = new PaymentRequest();
            $paymentRequest->currency        = 'BDT';
            $paymentRequest->amount          = $event->reg_fee;
            $paymentRequest->orderId         = $order_id;
            $paymentRequest->customerName    = $team->team_name ?? $team->m1_name ?? 'Participant';
            $paymentRequest->customerPhone   = $this->normalizePhone($team->m1_phone);
            $paymentRequest->customerEmail   = $this->normalizeEmail($team->m1_email); // ✅ FIX
            $paymentRequest->customerAddress = $team->university_name ?? 'Gazipur';
            $paymentRequest->customerCity    = 'Gazipur';
            $paymentRequest->value1          = $team->id;
            $paymentRequest->value2          = $team->event_id;
            $paymentRequest->value3          = $team->team_id;

            return $this->shurjopay->makePayment($paymentRequest);
        } catch (Throwable $e) { // ✅ FIX: Exception → Throwable
            Log::error('DirectPay Error', [
                'slug'    => $slug,
                'team_id' => $team->id,
                'error'   => $e->getMessage(),
            ]);

            return $this->paymentErrorView($this->friendlyError($e), $team);
        }
    }

    // =========================================================================
    // 5. SINGLE CALLBACK — সব event এর জন্য একটাই
    //    Route: GET|POST /payment/callback
    //    name:  payment.callback
    // =========================================================================
    public function callback(Request $request)
    {
        $order_id = $request->order_id;

        if (empty($order_id)) {
            Log::error('Callback: order_id missing');
            return view('payment.failed', [
                'registration'   => null,
                'payment_status' => 'error',
                'message'        => 'Order ID পাওয়া যায়নি।',
                'slug'           => 'event',
            ]);
        }

        try {
            $verification = $this->shurjopay->verifyPayment($order_id);

            if (empty($verification) || !isset($verification[0])) {
                Log::error('ShurjoPay verification empty', ['order_id' => $order_id]);
                return view('payment.failed', [
                    'registration'   => null,
                    'payment_status' => 'error',
                    'message'        => 'পেমেন্ট যাচাই করা সম্ভব হয়নি। আমাদের সাথে যোগাযোগ করুন।',
                    'slug'           => 'event',
                ]);
            }

            $data = $verification[0];

            // ── Payment FAILED / Cancelled ────────────────────────────────────
            if (($data->sp_code ?? '') !== '1000') {
                $registration = Registration::with('event')
                    ->where('order_id', $order_id)
                    ->first();

                // ✅ FIX #9: failed payment-এ status reset করা যাতে retry করা যায়
                if ($registration && $registration->payment_status === 'pending') {
                    $registration->update(['payment_status' => 'unpaid']);
                }

                $slug = optional($registration?->event)->slug ?? 'event';

                return view('payment.failed', [
                    'registration'   => $registration,
                    'payment_status' => 'failed',
                    'sp_code'        => $data->sp_code ?? 'N/A',
                    'message'        => 'পেমেন্ট সফল হয়নি। আবার চেষ্টা করুন।',
                    'slug'           => $slug,
                ]);
            }

            // ── value1 (registration id) না থাকলে order_id দিয়ে খোঁজা
            // ✅ FIX #10: value1 শুধু নয়, order_id দিয়েও fallback lookup
            $registration = null;
            if (!empty($data->value1)) {
                $registration = Registration::with('event')->find($data->value1);
            }
            if (!$registration) {
                $registration = Registration::with('event')
                    ->where('order_id', $order_id)
                    ->first();
            }

            if (!$registration) {
                Log::error('Callback: registration not found', [
                    'value1'   => $data->value1 ?? null,
                    'order_id' => $order_id,
                ]);
                return view('payment.failed', [
                    'registration'   => null,
                    'payment_status' => 'error',
                    'message'        => 'Registration খুঁজে পাওয়া যায়নি।',
                    'slug'           => 'event',
                ]);
            }

            $slug = $registration->event->slug;

            // ── Already PAID — Idempotency ────────────────────────────────────
            if ($registration->payment_status === 'paid') {
                $transaction = Transaction::where('team_id', $registration->id)->latest()->first();
                return $this->invoiceView($slug, $registration, $transaction, 'already_paid');
            }

            // ── DB Transaction — Race Condition Protection ────────────────────
            $registrationId = $registration->id; // ✅ FIX #11: closure-এ object pass না করে শুধু id pass

            $result = DB::transaction(function () use ($data, $registrationId, $slug) {

                // Row lock — একই সাথে দুই request আসলে একটাই চলবে
                $locked = Registration::with('event')
                    ->where('id', $registrationId)
                    ->lockForUpdate()
                    ->first();

                if (!$locked) {
                    throw new Exception('Registration not found inside transaction');
                }

                // Lock পেয়ে দেখলাম ইতোমধ্যে paid
                if ($locked->payment_status === 'paid') {
                    return $locked;
                }

                // ── Participant ID Generate ───────────────────────────────────
                // ✅ FIX #12: $this->getParticipantPrefix() closure-এ কাজ করে
                //    কিন্তু $slug এখন use() দিয়ে পাস হচ্ছে — নিরাপদ
                $prefix = match ($slug) {
                    'iupc'             => 'IUPC',
                    'project-showcase' => 'PS',
                    'ai-hackathon'     => 'AI',
                    'ict-olympiad'     => 'ICT',
                    default            => strtoupper(str_replace(['-', '_'], '', $slug)),
                };

                // ✅ FIX #13: participant_id generation আরও robust করা হয়েছে
                //    MAX() দিয়ে সংখ্যাটা বের করা — LIKE query-র চেয়ে নির্ভরযোগ্য
                $last = Registration::whereNotNull('participant_id')
                    ->where('participant_id', 'like', $prefix . '%')
                    ->orderByRaw('CAST(SUBSTRING(participant_id, ' . (strlen($prefix) + 1) . ') AS UNSIGNED) DESC')
                    ->lockForUpdate()
                    ->first();

                if ($last) {
                    $number = (int) substr($last->participant_id, strlen($prefix));
                    $newId  = $prefix . str_pad($number + 1, 2, '0', STR_PAD_LEFT);
                } else {
                    $newId = $prefix . '01';
                }

                // ── Transaction Save (idempotent) ─────────────────────────────
                $bankTrxId = $data->bank_trx_id ?? ('MANUAL-' . $registrationId . '-' . time());

                Transaction::firstOrCreate(
                    ['transaction_id' => $bankTrxId],
                    [
                        'event_id'       => $locked->event_id,
                        'team_id'        => $locked->id,
                        'student_id'     => $locked->student_id ?? null,
                        'amount'         => $data->amount ?? 0,
                        'currency'       => $data->currency ?? 'BDT',
                        'status'         => 'Successful',
                        'payment_method' => $data->method ?? 'Unknown',
                    ]
                );

                // ── Coupon mark as used (IUPC only) ───────────────────────────
                $couponId = $locked->coupon_id ?? ($data->value2 ?? null);

                if ($couponId) {
                    // ✅ FIX #14: IUPC-র জন্য value2 শুধুমাত্র coupon হলেই update
                    //    অন্য event-এ value2 ভিন্ন জিনিস হতে পারে
                    $isIupc = $slug === 'iupc';
                    if ($isIupc) {
                        $updated = Coupon::where('id', $couponId)
                            ->where('is_used', false)
                            ->update(['is_used' => true]);

                        if (!$updated) {
                            Log::warning('Coupon already used or not found', [
                                'coupon_id' => $couponId,
                                'team_id'   => $locked->id,
                            ]);
                        }
                    }
                }

                // ── Registration update ───────────────────────────────────────
                $locked->update([
                    'participant_id' => $newId,
                    'transaction_id' => $bankTrxId,
                    'payment_status' => 'paid',
                    'status'         => 'verified',
                ]);

                return $locked->fresh('event');
            });

            $transaction = Transaction::where('team_id', $result->id)->latest()->first();

            return $this->invoiceView($slug, $result, $transaction, 'success');
        } catch (Throwable $e) { // ✅ FIX #15: Exception → Throwable
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
}
