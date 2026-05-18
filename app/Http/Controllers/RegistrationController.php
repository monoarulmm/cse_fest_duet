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
    // HELPER: Payment error → professional view
    // =========================================================================

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
                'm3_prev_ex'     => 'nullable|string',
                'm2_prev_ex'     => 'nullable|string',
                'm3_cf_handle'     => 'nullable|string', // kaggle account link 1
                'm2_cf_handle'     => 'nullable|string',
                'm1_cf_handle'     => 'nullable|string',
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
}
