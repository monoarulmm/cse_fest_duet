<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Transaction;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use ShurjopayPlugin\Shurjopay;
use ShurjopayPlugin\PaymentRequest;
use Exception;

class RegistrationController extends Controller
{
    private $sp_instance;

    public function __construct(Shurjopay $sp_instance)
    {
        $this->sp_instance = $sp_instance;
    }

    // =========================================================================
    // HELPER: Phone normalize — ShurjoPay শুধু 01XXXXXXXXX নেয়
    // =========================================================================
    private function normalizePhone(?string $phone): string
    {
        if (empty($phone)) {
            return '01700000000';
        }

        $digits = preg_replace('/[^0-9]/', '', $phone);

        // +8801... বা 8801... → 01...
        if (strlen($digits) === 13 && str_starts_with($digits, '880')) {
            $digits = '0' . substr($digits, 3);
        }
        if (strlen($digits) === 12 && str_starts_with($digits, '88')) {
            $digits = '0' . substr($digits, 2);
        }

        // তারপরও ঠিক না হলে fallback
        if (strlen($digits) !== 11 || !str_starts_with($digits, '01')) {
            Log::warning('Invalid phone number normalized to fallback', ['original' => $phone]);
            return '01700000000';
        }

        return $digits;
    }

    // =========================================================================
    // HELPER: ShurjoPay exception → বাংলা friendly message
    // =========================================================================
    private function friendlyError(Exception $e): string
    {
        $msg = strtolower($e->getMessage());

        if (str_contains($msg, 'phone') || str_contains($msg, 'mobile')) {
            return 'ফোন নম্বর সঠিক নয়। ১১ ডিজিটের বাংলাদেশী নম্বর দিন (যেমন: 01XXXXXXXXX)।';
        }
        if (str_contains($msg, 'amount')) {
            return 'পেমেন্টের পরিমাণে সমস্যা হয়েছে। Admin কে জানান।';
        }
        if (str_contains($msg, 'email')) {
            return 'ইমেইল ঠিকানা সঠিক নয়।';
        }
        if (str_contains($msg, 'curl') || str_contains($msg, 'connection') || str_contains($msg, 'timeout')) {
            return 'পেমেন্ট গেটওয়েতে সংযোগ সমস্যা। কিছুক্ষণ পর আবার চেষ্টা করুন।';
        }
        if (str_contains($msg, 'null') || str_contains($msg, 'getmessage')) {
            return 'পেমেন্ট গেটওয়ে সাড়া দেয়নি। কিছুক্ষণ পর আবার চেষ্টা করুন।';
        }
        if (str_contains($msg, 'unauthorized') || str_contains($msg, '401')) {
            return 'পেমেন্ট গেটওয়ে কনফিগারেশন সমস্যা। Admin কে জানান।';
        }
        if (str_contains($msg, 'internal server') || str_contains($msg, '500')) {
            return 'পেমেন্ট গেটওয়েতে সাময়িক সমস্যা। কিছুক্ষণ পর আবার চেষ্টা করুন।';
        }

        return 'পেমেন্ট গেটওয়েতে সমস্যা হয়েছে। আবার চেষ্টা করুন।';
    }

    // =========================================================================
    // HELPER: Payment error → professional view
    // =========================================================================
    private function paymentErrorView(string $message, ?Registration $registration = null)
    {
        $slug = optional($registration?->event)->slug ?? 'event';

        return view('payment.error', [
            'registration' => $registration,
            'error_type'   => 'gateway_error',
            'message'      => $message,
            'slug'         => $slug,
        ]);
    }

    // =========================================================================
    // CREATE — Registration form দেখানো
    // =========================================================================
    public function create($slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        return view('users.events.pre_reg_form', compact('event'));
    }

    // =========================================================================
    // STORE — Registration save + conditional redirect
    // =========================================================================
    public function store(Request $request)
    {
        // ১. Common validation rules
        $commonRules = [
            'event_id'        => 'required|exists:events,id',
            'university_name' => 'required|string|max:255',
            'm1_name'         => 'required|string|max:255',
            'm1_email'        => 'required|email|max:255',
            'm1_phone'        => 'required|digits:11',
            'm1_tshirt'       => 'nullable|string',
            'm1_prev_ex'      => 'nullable|string',
        ];

        $event     = Event::findOrFail($request->event_id);
        $eventSlug = $event->slug;
        $eventRules = [];

        // ২. Event-specific validation
        if ($eventSlug === 'ict-olympiad') {
            $eventRules = [
                'student_id' => 'required|string|max:50',
            ];
        } elseif ($eventSlug === 'iupc') {
            $eventRules = [
                'team_name'         => 'required|unique:registrations,team_name|max:255',
                'coach_name'        => 'required|string',
                'coach_email'       => 'required|email',
                'coach_phone'       => 'required|digits:11',
                'coach_designation' => 'required|string',
                'coach_tshirt'      => 'required|string',
                'm1_cf_handle'      => 'required|string',
                'm2_name'           => 'required|string',
                'm2_email'          => 'required|email',
                'm2_phone'          => 'required|digits:11',
                'm2_tshirt'         => 'required|string',
                'm2_prev_ex'        => 'required|string',
                'm2_cf_handle'      => 'required|string',
                'm3_name'           => 'nullable|string',
                'm3_email'          => 'nullable|email',
                'm3_phone'          => 'nullable|digits:11',
                'm3_tshirt'         => 'nullable|string',
                'm3_prev_ex'        => 'nullable|string',
                'm3_cf_handle'      => 'nullable|string',
            ];
        } elseif (in_array($eventSlug, ['project-showcase', 'ai-hackathon'])) {
            $eventRules = [
                'team_name'     => 'required|unique:registrations,team_name|max:255',
                'team_person'   => 'required|string',
                'project_title' => $eventSlug === 'project-showcase' ? 'required|string' : 'nullable',
                'abstract_file' => $eventSlug === 'project-showcase' ? 'required|mimes:pdf|max:3072' : 'nullable',
                'm2_name'       => 'required|string',
                'm2_email'      => 'required|email',
                'm2_phone'      => 'required|digits:11',
                'm2_tshirt'     => 'required|string',
                'm3_name'       => 'nullable|string',
                'm3_email'      => 'nullable|email',
                'm3_phone'      => 'nullable|digits:11',
                'm3_tshirt'     => 'nullable|string',
            ];
        }

        $validatedData = $request->validate(array_merge($commonRules, $eventRules));

        // ৩. Registration DB save — payment এর বাইরে আলাদা try-catch
        try {
            // Other university handling
            if ($request->university_name === 'Others' && $request->filled('other_university')) {
                $validatedData['university_name'] = $request->other_university;
            }

            // Abstract file upload
            if ($request->hasFile('abstract_file')) {
                $path = $request->file('abstract_file')->store('abstracts', 'public');
                $validatedData['abstract_file'] = $path;
            }

            // Team ID generation
            $teamEvents = ['iupc', 'project-showcase', 'ai-hackathon'];
            if (in_array($eventSlug, $teamEvents)) {
                $prefix = strtoupper(substr(str_replace('-', '', $eventSlug), 0, 4));
                do {
                    $teamId = $prefix . '-' . rand(1000, 9999);
                } while (Registration::where('team_id', $teamId)->exists());

                $validatedData['team_id'] = $teamId;
            }

            $validatedData['status']   = 'pre-registered';
            $validatedData['event_id'] = $event->id;

            $registration = Registration::create($validatedData);
        } catch (Exception $e) {
            Log::error('Registration store error', ['error' => $e->getMessage()]);
            return back()
                ->with('error', 'রেজিস্ট্রেশন সেভ করতে সমস্যা হয়েছে: ' . $e->getMessage())
                ->withInput();
        }

        // ৪. Conditional redirect — DB save এর বাইরে
        // Team events: শুধু success message, payment পরে
        if (in_array($eventSlug, ['iupc', 'project-showcase', 'ai-hackathon'])) {
            $msg = 'Registration submitted successfully for ' . $event->title;
            if ($registration->team_id ?? false) {
                $msg .= '. Your Team ID: ' . $registration->team_id;
            }
            return redirect()->back()->with('success', $msg);
        }

        // ICT Olympiad ও অন্যান্য: সরাসরি payment এ
        // ⚠️ makePayment এর error এখানে আলাদাভাবে handle হবে
        return $this->makePayment($registration->id);
    }

    // =========================================================================
    // RETRY PAYMENT
    // =========================================================================
    public function retryPayment($registration_id)
    {
        $registration = Registration::with('event')->findOrFail($registration_id);

        if ($registration->payment_status === 'paid') {
            return redirect()->route('home')->with('info', 'আপনার পেমেন্ট অলরেডি সম্পন্ন হয়েছে।');
        }

        return $this->makePayment($registration->id);
    }

    // =========================================================================
    // MAKE PAYMENT — ShurjoPay gateway এ পাঠানো
    // =========================================================================
    public function makePayment($registration_id)
    {
        $registration = Registration::with('event')->findOrFail($registration_id);

        // Already paid check
        if ($registration->payment_status === 'paid') {
            return redirect()->route('home')->with('info', 'পেমেন্ট আগেই সম্পন্ন হয়েছে।');
        }

        // sp_instance null check — যেন "Call to member function on null" না আসে
        if (! $this->sp_instance) {
            Log::error('ShurjoPay instance is null', ['registration_id' => $registration_id]);
            return $this->paymentErrorView(
                'পেমেন্ট গেটওয়ে সংযোগ করা যায়নি। Admin কে জানান।',
                $registration
            );
        }

        try {
            $order_id = 'ICT-' . uniqid();
            $registration->update(['order_id' => $order_id]);

            $paymentRequest                  = new PaymentRequest();
            $paymentRequest->currency        = 'BDT';
            $paymentRequest->amount          = $registration->event->reg_fee;
            $paymentRequest->orderId         = $order_id;
            $paymentRequest->customerName    = $registration->m1_name;
            $paymentRequest->customerPhone   = $this->normalizePhone($registration->m1_phone); // ✅ Fixed
            $paymentRequest->customerEmail   = $registration->m1_email;
            $paymentRequest->customerAddress = 'DUET, Gazipur';
            $paymentRequest->customerCity    = 'Gazipur';
            $paymentRequest->value1          = $registration->id;

            return $this->sp_instance->makePayment($paymentRequest);
        } catch (Exception $e) {
            Log::error('makePayment error', [
                'registration_id' => $registration_id,
                'error'           => $e->getMessage(),
                'trace'           => $e->getTraceAsString(),
            ]);

            // ✅ Raw ShurjoPay error দেখাবে না — friendly page দেখাবে
            return $this->paymentErrorView($this->friendlyError($e), $registration);
        }
    }

    // =========================================================================
    // CALLBACK — ShurjoPay থেকে ফিরে আসার পর
    // =========================================================================
    public function callback(Request $request)
    {
        $order_id = $request->order_id;

        try {
            $verification = $this->sp_instance->verifyPayment($order_id);

            // verifyPayment null বা empty array দিলে
            if (empty($verification) || ! isset($verification[0])) {
                Log::error('ShurjoPay verification empty', ['order_id' => $order_id]);
                return view('payment.failed', [
                    'registration'   => null,
                    'payment_status' => 'error',
                    'message'        => 'পেমেন্ট যাচাই করা সম্ভব হয়নি। আমাদের সাথে যোগাযোগ করুন।',
                    'slug'           => 'event',
                ]);
            }

            $data = $verification[0];

            // ─── Payment FAILED / Cancelled ──────────────────────────────────
            if (($data->sp_code ?? '') !== '1000') {
                $registration = Registration::with('event')
                    ->where('order_id', $order_id)
                    ->first();
                $slug = optional($registration?->event)->slug ?? 'event';

                return view('payment.failed', [
                    'registration'   => $registration,
                    'payment_status' => 'failed',
                    'sp_code'        => $data->sp_code ?? 'N/A',
                    'message'        => 'পেমেন্ট সফল হয়নি। আবার চেষ্টা করুন।',
                    'slug'           => $slug,
                ]);
            }

            // ─── Payment SUCCESS ──────────────────────────────────────────────
            $registration = Registration::with('event')
                ->where('id', $data->value1)
                ->first();

            if (! $registration) {
                Log::error('Callback: registration not found', ['value1' => $data->value1]);
                return view('payment.failed', [
                    'registration'   => null,
                    'payment_status' => 'error',
                    'message'        => 'Registration খুঁজে পাওয়া যায়নি।',
                    'slug'           => 'event',
                ]);
            }

            // ─── Already PAID — Idempotency ──────────────────────────────────
            if ($registration->payment_status === 'paid') {
                $transaction = Transaction::where('team_id', $registration->id)->latest()->first();
                return view('invoice', [
                    'registration'   => $registration,
                    'transaction'    => $transaction,
                    'payment_status' => 'already_paid',
                    'message'        => 'এই রেজিস্ট্রেশনের পেমেন্ট আগেই সম্পন্ন হয়েছে।',
                ]);
            }

            // ─── DB Lock + Save — Race condition protection ───────────────────
            DB::transaction(function () use ($data, $registration) {

                // Row lock — একই সাথে দুই request আসলে একটাই চলবে
                $locked = Registration::where('id', $registration->id)
                    ->lockForUpdate()
                    ->first();

                // Lock পেয়ে দেখলাম ইতোমধ্যে paid
                if ($locked->payment_status === 'paid') {
                    return;
                }

                // Participant ID generate
                $lastParticipant = Registration::whereNotNull('participant_id')
                    ->where('participant_id', 'like', 'CSECERNIVAL%')
                    ->orderBy('id', 'desc')
                    ->lockForUpdate()
                    ->first();

                if ($lastParticipant) {
                    $number = (int) str_replace('CSECERNIVAL', '', $lastParticipant->participant_id);
                    $newId  = 'CSECERNIVAL' . str_pad($number + 1, 2, '0', STR_PAD_LEFT);
                } else {
                    $newId = 'CSECERNIVAL01';
                }

                // Transaction save — idempotent (duplicate হবে না)
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

                // Registration update
                $locked->update([
                    'participant_id' => $newId,
                    'transaction_id' => $data->bank_trx_id,
                    'payment_status' => 'paid',
                    'status'         => 'verified',
                ]);
            });

            // Fresh data নিয়ে invoice দেখানো
            $registration = $registration->fresh('event');
            $transaction  = Transaction::where('team_id', $registration->id)->latest()->first();

            return view('invoice', [
                'registration'   => $registration,
                'transaction'    => $transaction,
                'payment_status' => 'success',
                'message'        => 'আপনার পেমেন্ট সফল হয়েছে!',
            ]);
        } catch (Exception $e) {
            Log::error('Callback error', [
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
